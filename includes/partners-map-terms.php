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
