<?php
if (!defined('ABSPATH')) exit;

// Register lat/lng term meta for partner taxonomies
function horizons_register_partner_term_meta() {
    foreach (['partner_region', 'partner_country'] as $taxonomy) {
        register_term_meta($taxonomy, 'partner_lat', [
            'type'              => 'number',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'floatval',
        ]);
        register_term_meta($taxonomy, 'partner_lng', [
            'type'              => 'number',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'floatval',
        ]);
    }
}
add_action('init', 'horizons_register_partner_term_meta');

// Yandex Maps v3 coordinate picker widget (shared by add and edit forms)
function horizons_partner_term_map_widget($lat_value, $lng_value, $map_id) {
    global $opt_name;
    if (empty($opt_name)) $opt_name = 'redux_demo';
    $api_key = class_exists('Redux') ? Redux::get_option($opt_name, 'yandexapi') : '';

    if (empty($api_key)) {
        echo '<p style="color:#d63638;padding:8px 10px;background:#fcf0f1;border-left:4px solid #d63638;margin:8px 0;">'
            . esc_html__('Yandex Maps API key is not configured. Set it in Redux settings.', 'horizons')
            . '</p>';
        return;
    }

    static $script_loaded = false;
    ?>
    <div style="margin-top:10px;">
        <div style="position:relative;margin-bottom:8px;">
            <input type="text" id="<?php echo esc_attr($map_id); ?>-search"
                   placeholder="<?php esc_attr_e('Search address...', 'horizons'); ?>"
                   style="width:100%;padding:8px;border:1px solid #8c8f94;border-radius:4px;box-sizing:border-box;">
        </div>
        <div id="<?php echo esc_attr($map_id); ?>"
             style="width:100%;height:350px;border-radius:4px;overflow:hidden;border:1px solid #ddd;"></div>
    </div>
    <?php if (!$script_loaded) : $script_loaded = true; ?>
    <script src="https://api-maps.yandex.ru/v3/?apikey=<?php echo esc_attr($api_key); ?>&lang=ru_RU"></script>
    <?php endif; ?>
    <script>
    (function() {
        var apiKey     = '<?php echo esc_js($api_key); ?>';
        var mapId      = '<?php echo esc_js($map_id); ?>';
        var geocodeUrl = 'https://geocode-maps.yandex.ru/1.x/?apikey=' + encodeURIComponent(apiKey) + '&format=json&lang=ru_RU';

        ymaps3.ready.then(function() {
            var YMap                  = ymaps3.YMap;
            var YMapDefaultSchemeLayer  = ymaps3.YMapDefaultSchemeLayer;
            var YMapDefaultFeaturesLayer = ymaps3.YMapDefaultFeaturesLayer;
            var YMapMarker            = ymaps3.YMapMarker;
            var YMapListener          = ymaps3.YMapListener;

            var latField    = document.querySelector("input[name='partner_lat']");
            var lngField    = document.querySelector("input[name='partner_lng']");
            var searchInput = document.getElementById(mapId + '-search');

            var lat  = parseFloat(latField && latField.value ? latField.value : '') || 55.76;
            var lng  = parseFloat(lngField && lngField.value ? lngField.value : '') || 37.64;
            var hasCoords = !!(latField && latField.value && lngField && lngField.value);

            var map = new YMap(document.getElementById(mapId), {
                location: { center: [lng, lat], zoom: hasCoords ? 10 : 4 }
            });
            map.addChild(new YMapDefaultSchemeLayer());
            map.addChild(new YMapDefaultFeaturesLayer());

            var el = document.createElement('div');
            el.style.cssText = 'cursor:grab;width:28px;height:28px;transform:translate(-50%,-100%)';
            el.innerHTML = '<svg viewBox="0 0 24 24" fill="#d63638" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>';

            var marker = new YMapMarker({
                coordinates: [lng, lat],
                draggable: true,
                onDragEnd: function(coords) { syncFields(coords[1], coords[0]); }
            }, el);
            map.addChild(marker);

            map.addChild(new YMapListener({
                onClick: function(obj, event) {
                    var coords = event && event.coordinates ? event.coordinates : null;
                    if (!coords) return;
                    marker.update({ coordinates: coords });
                    syncFields(coords[1], coords[0]);
                }
            }));

            function syncFields(latVal, lngVal) {
                if (latField) { latField.value = latVal; latField.dispatchEvent(new Event('input', {bubbles: true})); }
                if (lngField) { lngField.value = lngVal; lngField.dispatchEvent(new Event('input', {bubbles: true})); }
            }

            function geocodeAndMove(query) {
                if (!query) return;
                fetch(geocodeUrl + '&geocode=' + encodeURIComponent(query) + '&results=1')
                    .then(function(r) { return r.json(); })
                    .then(function(d) {
                        var fm = d.response && d.response.GeoObjectCollection && d.response.GeoObjectCollection.featureMember;
                        if (!fm || !fm.length) return;
                        var pos = fm[0].GeoObject.Point.pos.split(' ');
                        var fLng = parseFloat(pos[0]), fLat = parseFloat(pos[1]);
                        if (isNaN(fLat) || isNaN(fLng)) return;
                        marker.update({ coordinates: [fLng, fLat] });
                        map.update({ location: { center: [fLng, fLat], zoom: 12 } });
                        syncFields(fLat, fLng);
                    }).catch(function() {});
            }

            function initSuggest(input) {
                var wrap = input.parentNode;
                var drop = document.createElement('div');
                drop.style.cssText = 'display:none;position:absolute;z-index:99999;left:0;right:0;top:100%;background:#fff;border:1px solid #c3c4c7;border-top:none;border-radius:0 0 4px 4px;box-shadow:0 4px 8px rgba(0,0,0,.12);max-height:220px;overflow-y:auto;font-size:13px;';
                wrap.appendChild(drop);
                var timer, active = -1;
                function hide() { drop.style.display = 'none'; active = -1; }
                function hl(i) { active = i; Array.from(drop.children).forEach(function(c, j) { c.style.background = j === i ? '#f0f7ff' : ''; }); }
                function pick(t, s) { input.value = t + (s ? ', ' + s : ''); hide(); geocodeAndMove(input.value); }
                function esc(s) { return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); }
                input.addEventListener('input', function() {
                    clearTimeout(timer);
                    var q = input.value.trim();
                    if (q.length < 2) { hide(); return; }
                    timer = setTimeout(function() {
                        ymaps3.suggest({ text: q, lang: 'ru_RU', results: 5 })
                            .then(function(items) {
                                drop.innerHTML = '';
                                items = (items || []).filter(function(r) { return r.title && r.title.text; });
                                if (!items.length) { hide(); return; }
                                items.forEach(function(r, i) {
                                    var t = r.title.text, s = r.subtitle && r.subtitle.text ? r.subtitle.text : '';
                                    var div = document.createElement('div');
                                    div.style.cssText = 'padding:7px 12px;cursor:pointer;border-bottom:1px solid #f0f0f1;line-height:1.3;';
                                    div.innerHTML = '<span style="font-weight:600">' + esc(t) + '</span>' + (s ? '<br><span style="color:#777;font-size:12px">' + esc(s) + '</span>' : '');
                                    div.addEventListener('mousedown', function(e) { e.preventDefault(); pick(t, s); });
                                    div.addEventListener('mouseover', function() { hl(i); });
                                    drop.appendChild(div);
                                });
                                drop.style.display = 'block';
                            }).catch(function() {});
                    }, 250);
                });
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'ArrowDown') { e.preventDefault(); hl(Math.min(active + 1, drop.children.length - 1)); }
                    else if (e.key === 'ArrowUp') { e.preventDefault(); hl(Math.max(active - 1, 0)); }
                    else if (e.key === 'Enter') {
                        e.preventDefault();
                        if (active >= 0 && drop.children[active]) drop.children[active].dispatchEvent(new MouseEvent('mousedown', {bubbles: true}));
                        else geocodeAndMove(input.value.trim());
                        hide();
                    } else if (e.key === 'Escape') { hide(); }
                });
                input.addEventListener('blur', function() { setTimeout(hide, 200); });
            }

            if (searchInput) initSuggest(searchInput);
        });
    })();
    </script>
    <?php
}

// Fields on term ADD form
function horizons_partner_term_add_form_fields() {
    ?>
    <div class="form-field">
        <label for="partner_lat"><?php esc_html_e('Latitude', 'horizons'); ?></label>
        <input type="text" name="partner_lat" id="partner_lat" value="" placeholder="55.7558">
        <p><?php esc_html_e('Map marker latitude (e.g. 55.7558)', 'horizons'); ?></p>
    </div>
    <div class="form-field">
        <label for="partner_lng"><?php esc_html_e('Longitude', 'horizons'); ?></label>
        <input type="text" name="partner_lng" id="partner_lng" value="" placeholder="37.6176">
        <p><?php esc_html_e('Map marker longitude (e.g. 37.6176)', 'horizons'); ?></p>
    </div>
    <div class="form-field">
        <?php horizons_partner_term_map_widget('', '', 'partner-term-map-add'); ?>
    </div>
    <?php
}

// Fields on term EDIT form
function horizons_partner_term_edit_form_fields($term) {
    $lat = get_term_meta($term->term_id, 'partner_lat', true);
    $lng = get_term_meta($term->term_id, 'partner_lng', true);
    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="partner_lat"><?php esc_html_e('Latitude', 'horizons'); ?></label>
        </th>
        <td>
            <input type="text" name="partner_lat" id="partner_lat"
                   value="<?php echo esc_attr($lat); ?>" placeholder="55.7558">
            <p class="description"><?php esc_html_e('Map marker latitude (e.g. 55.7558)', 'horizons'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row">
            <label for="partner_lng"><?php esc_html_e('Longitude', 'horizons'); ?></label>
        </th>
        <td>
            <input type="text" name="partner_lng" id="partner_lng"
                   value="<?php echo esc_attr($lng); ?>" placeholder="37.6176">
            <p class="description"><?php esc_html_e('Map marker longitude (e.g. 37.6176)', 'horizons'); ?></p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <?php horizons_partner_term_map_widget($lat, $lng, 'partner-term-map-' . $term->term_id); ?>
        </td>
    </tr>
    <?php
}

// Save term meta
function horizons_save_partner_term_meta($term_id) {
    if (isset($_POST['partner_lat'])) {
        update_term_meta($term_id, 'partner_lat', (float) sanitize_text_field(wp_unslash($_POST['partner_lat'])));
    }
    if (isset($_POST['partner_lng'])) {
        update_term_meta($term_id, 'partner_lng', (float) sanitize_text_field(wp_unslash($_POST['partner_lng'])));
    }
}

foreach (['partner_region', 'partner_country'] as $_taxonomy) {
    add_action("{$_taxonomy}_add_form_fields",  'horizons_partner_term_add_form_fields');
    add_action("{$_taxonomy}_edit_form_fields", 'horizons_partner_term_edit_form_fields');
    add_action("created_{$_taxonomy}",          'horizons_save_partner_term_meta');
    add_action("edited_{$_taxonomy}",           'horizons_save_partner_term_meta');
}
unset($_taxonomy);
