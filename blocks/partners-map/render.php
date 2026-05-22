<?php
if (!defined('ABSPATH')) exit;

// Check Yandex Maps
if (!class_exists('Codeweber_Yandex_Maps') || !Codeweber_Yandex_Maps::get_instance()->has_api_key()) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        echo '<p style="padding:16px;border:1px solid #e00;">' . esc_html__('Partners Map: Yandex Maps API key not configured.', 'horizons') . '</p>';
    }
    return;
}

// ── Attributes ────────────────────────────────────────────────────────────────
$data_source      = $attributes['dataSource']      ?? 'both';
$height           = max(300, (int) ($attributes['height']    ?? 600));
$zoom             = max(1, min(19, (int) ($attributes['zoom'] ?? 4)));
$center_lat       = (float) ($attributes['centerLat']        ?? 55.76);
$center_lng       = (float) ($attributes['centerLng']        ?? 37.64);
$map_type         = $attributes['mapType']         ?? 'normal';
$scroll_zoom      = (bool)  ($attributes['scrollZoom']       ?? false);
$auto_fit_bounds  = (bool)  ($attributes['autoFitBounds']    ?? true);
$marker_color     = $attributes['markerColor']     ?? '#C8A96E';
$marker_size      = max(1, (int) ($attributes['markerSize'] ?? 40));
$clusterer        = (bool)  ($attributes['clustererEnabled'] ?? false);
$sidebar_enabled  = (bool)  ($attributes['sidebarEnabled']   ?? true);
$sidebar_position = $attributes['sidebarPosition'] ?? 'left';
$sidebar_title    = $attributes['sidebarTitle']    ?? '';
$style_json       = $attributes['styleJson']       ?? '';

// Validate styleJson
$style_json_valid = '';
if ($style_json) {
    $decoded = json_decode($style_json);
    if (json_last_error() === JSON_ERROR_NONE) {
        $style_json_valid = $style_json;
    }
}

// ── 1. Collect region terms with coordinates ──────────────────────────────────
$region_terms = [];
if (in_array($data_source, ['both', 'regions'], true)) {
    $terms = get_terms(['taxonomy' => 'partner_region', 'hide_empty' => true, 'number' => 0]);
    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            $lat = get_term_meta($term->term_id, 'partner_lat', true);
            $lng = get_term_meta($term->term_id, 'partner_lng', true);
            if ($lat !== '' && $lng !== '') {
                $region_terms[$term->term_id] = [
                    'term' => $term,
                    'lat'  => (float) $lat,
                    'lng'  => (float) $lng,
                    'type' => 'region',
                ];
            }
        }
    }
}

// ── 2. Collect country terms with coordinates ─────────────────────────────────
$country_terms = [];
if (in_array($data_source, ['both', 'countries'], true)) {
    $terms = get_terms(['taxonomy' => 'partner_country', 'hide_empty' => true, 'number' => 0]);
    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            $lat = get_term_meta($term->term_id, 'partner_lat', true);
            $lng = get_term_meta($term->term_id, 'partner_lng', true);
            if ($lat !== '' && $lng !== '') {
                $country_terms[$term->term_id] = [
                    'term' => $term,
                    'lat'  => (float) $lat,
                    'lng'  => (float) $lng,
                    'type' => 'country',
                ];
            }
        }
    }
}

if (empty($region_terms) && empty($country_terms)) {
    echo '<p class="py-4 text-center">' . esc_html__('No partner locations with coordinates found. Add latitude and longitude to partner regions or countries.', 'horizons') . '</p>';
    return;
}

// ── 3. Query partners → build per-term lists and region→country map ───────────
$partners_by_term  = [];
$region_to_country = [];

$partners_query = new WP_Query([
    'post_type'      => 'partners',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'fields'         => 'ids',
    'no_found_rows'  => true,
]);

foreach ($partners_query->posts as $partner_id) {
    $p_countries = wp_get_post_terms($partner_id, 'partner_country', ['fields' => 'ids']);
    $p_regions   = wp_get_post_terms($partner_id, 'partner_region',  ['fields' => 'ids']);
    if (is_wp_error($p_countries)) $p_countries = [];
    if (is_wp_error($p_regions))   $p_regions   = [];

    foreach ($p_regions as $rid) {
        if (!isset($region_to_country[$rid])) {
            foreach ($p_countries as $cid) {
                $region_to_country[$rid] = $cid;
                break;
            }
        }
    }

    $assigned = false;
    foreach ($p_regions as $rid) {
        if (isset($region_terms[$rid])) {
            $partners_by_term[$rid][] = $partner_id;
            $assigned = true;
        }
    }
    if (!$assigned) {
        foreach ($p_countries as $cid) {
            if (isset($country_terms[$cid])) {
                $partners_by_term[$cid][] = $partner_id;
            }
        }
    }
}
wp_reset_postdata();

// ── 4. Build markers JSON (+ operator preserves numeric keys, array_merge reindexes) ──
$markers_json = [];
$all_term_data = $region_terms + $country_terms;

foreach ($all_term_data as $term_id => $tdata) {
    $partner_ids = $partners_by_term[$term_id] ?? [];
    if (empty($partner_ids)) continue;

    $markers_json[] = [
        'termId'   => $term_id,
        'termType' => $tdata['type'],
        'lat'      => $tdata['lat'],
        'lng'      => $tdata['lng'],
        'title'    => $tdata['term']->name,
        'count'    => count($partner_ids),
    ];
}

// ── 5. Build sidebar tree: country → regions ──────────────────────────────────
$total_count   = 0;
$sidebar_items = [];

foreach ($country_terms as $cid => $cdata) {
    $c_count = count($partners_by_term[$cid] ?? []);
    $sidebar_items[$cid] = [
        'term_id' => $cid,
        'name'    => $cdata['term']->name,
        'count'   => $c_count,
        'type'    => 'country',
        'regions' => [],
    ];
    $total_count += $c_count;
}

foreach ($region_terms as $rid => $rdata) {
    $r_count = count($partners_by_term[$rid] ?? []);
    if ($r_count === 0) continue;

    $total_count += $r_count;
    $cid = $region_to_country[$rid] ?? null;

    if ($cid && isset($sidebar_items[$cid])) {
        $sidebar_items[$cid]['regions'][$rid] = [
            'term_id' => $rid,
            'name'    => $rdata['term']->name,
            'count'   => $r_count,
        ];
        $sidebar_items[$cid]['count'] += $r_count;
    } else {
        $sidebar_items['r-' . $rid] = [
            'term_id' => $rid,
            'name'    => $rdata['term']->name,
            'count'   => $r_count,
            'type'    => 'region',
            'regions' => [],
        ];
    }
}

$sidebar_items = array_filter($sidebar_items, fn($item) => $item['count'] > 0);

// ── 6. Enqueue scripts ────────────────────────────────────────────────────────
wp_enqueue_script('yandex-maps-api-v3');
wp_enqueue_script(
    'horizons-partners-map',
    get_stylesheet_directory_uri() . '/blocks/partners-map/partners-map.js',
    ['yandex-maps-api-v3'],
    '1.1.0',
    true
);

// ── 7. Build map config ───────────────────────────────────────────────────────
$marker_shape      = $attributes['markerShape']      ?? 'circle';
$marker_show_count = (bool) ($attributes['markerShowCount']  ?? true);
$marker_show_label = (bool) ($attributes['markerShowLabel']  ?? false);
$marker_label_size  = max(1, (int) ($attributes['markerLabelSize']  ?? 11));
$marker_label_color = $attributes['markerLabelColor'] ?? '#1a1a1a';

$map_config = wp_json_encode([
    'center'          => [$center_lng, $center_lat],
    'zoom'            => $zoom,
    'mapType'         => $map_type,
    'scrollZoom'      => $scroll_zoom,
    'autoFitBounds'   => $auto_fit_bounds,
    'markers'         => $markers_json,
    'markerColor'     => $marker_color,
    'markerSize'      => $marker_size,
    'markerShape'     => $marker_shape,
    'markerShowCount' => $marker_show_count,
    'markerShowLabel' => $marker_show_label,
    'markerLabelSize'  => $marker_label_size,
    'markerLabelColor' => $marker_label_color,
    'clusterer'       => $clusterer,
    'styleJson'       => $style_json_valid,
]);

// ── 8. Output ─────────────────────────────────────────────────────────────────
$wrapper_classes = 'horizons-partners-map';
if (!$sidebar_enabled)           $wrapper_classes .= ' horizons-partners-map--no-sidebar';
if ($sidebar_position === 'right') $wrapper_classes .= ' horizons-partners-map--sidebar-right';

$wrapper_attrs = get_block_wrapper_attributes(['class' => 'horizons-partners-map-block']);
?>
<div <?php echo $wrapper_attrs; ?>>
    <div class="<?php echo esc_attr($wrapper_classes); ?>"
         style="height:<?php echo (int) $height; ?>px;"
         data-map-config="<?php echo esc_attr($map_config); ?>">

        <?php /* ── Sidebar ── */ ?>
        <?php if ($sidebar_enabled) : ?>
        <aside class="horizons-partners-map__sidebar"
               aria-label="<?php esc_attr_e('Filter partners by location', 'horizons'); ?>">

            <?php if ($sidebar_title) : ?>
            <div class="horizons-partners-map__sidebar-title">
                <?php echo esc_html($sidebar_title); ?>
            </div>
            <?php endif; ?>

            <div class="horizons-partners-map__sidebar-search">
                <input type="search"
                       class="horizons-partners-map__search-input"
                       placeholder="<?php esc_attr_e('Search country…', 'horizons'); ?>"
                       aria-label="<?php esc_attr_e('Search countries', 'horizons'); ?>">
            </div>

            <div class="horizons-partners-map__sidebar-inner">

                <button class="horizons-partners-map__filter-btn label-u text-neutral-50 is-active" data-filter="all">
                    <?php esc_html_e('All partners', 'horizons'); ?>
                </button>

                <?php foreach ($sidebar_items as $item) : ?>
                    <?php if ($item['type'] === 'region') : ?>

                        <button class="horizons-partners-map__filter-btn horizons-partners-map__filter-region label-u text-neutral-50"
                                data-filter="term"
                                data-term-id="<?php echo (int) $item['term_id']; ?>">
                            <?php echo esc_html($item['name']); ?>
                        </button>

                    <?php else : ?>

                        <div class="horizons-partners-map__country-group">
                            <button class="horizons-partners-map__filter-btn horizons-partners-map__filter-country label-u text-neutral-50"
                                    data-filter="term"
                                    data-term-id="<?php echo (int) $item['term_id']; ?>">
                                <?php echo esc_html($item['name']); ?>
                            </button>

                            <?php if (!empty($item['regions'])) : ?>
                                <div class="horizons-partners-map__regions">
                                    <?php foreach ($item['regions'] as $region) : ?>
                                        <button class="horizons-partners-map__filter-btn horizons-partners-map__filter-region label-u text-neutral-50"
                                                data-filter="term"
                                                data-term-id="<?php echo (int) $region['term_id']; ?>">
                                            <?php echo esc_html($region['name']); ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                    <?php endif; ?>
                <?php endforeach; ?>

            </div>
        </aside>
        <?php endif; ?>

        <?php /* ── Map canvas ── */ ?>
        <div class="horizons-partners-map__canvas"
             aria-label="<?php esc_attr_e('Partners map', 'horizons'); ?>">
        </div>

    </div>
</div>
