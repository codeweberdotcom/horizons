<?php

/**
 * horizons functions and definitions
 *
 * @package horizons
 */

add_action('wp_enqueue_scripts', 'horizons_enqueue_styles');
function horizons_enqueue_styles()
{
    wp_enqueue_style(
        'horizons-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('codeweber-style'),
        wp_get_theme()->get('Version')
    );
}

//

/**
 * Add second metabox for additional staff information
 */
function codeweber_add_staff_additional_meta_boxes()
{
    add_meta_box(
        'staff_additional_details',
        'Additional Information',
        'codeweber_staff_additional_meta_box_callback',
        'staff',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'codeweber_add_staff_additional_meta_boxes');

/**
 * Callback function for displaying the second metabox
 */
function codeweber_staff_additional_meta_box_callback($post)
{
    // Add nonce for security
    wp_nonce_field('staff_additional_meta_box', 'staff_additional_meta_box_nonce');

    // Get existing field values
    $full_position = get_post_meta($post->ID, '_staff_full_position', true);
    $regions = get_post_meta($post->ID, '_staff_regions', true);
    $short_description = get_post_meta($post->ID, '_staff_short_description', true);
    $language_skills = get_post_meta($post->ID, '_staff_language_skills', true);
?>

    <div style="display: grid; gap: 16px;">
        <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: center;">
            <label for="staff_full_position"><strong>Full Position Title:</strong></label>
            <input type="text" id="staff_full_position" name="staff_full_position" value="<?php echo esc_attr($full_position); ?>"
                placeholder="For example: Senior Financial Analyst and Investment Advisor" style="width: 100%; padding: 8px;">
        </div>

        <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: center;">
            <label for="staff_regions"><strong>Regions:</strong></label>
            <input type="text" id="staff_regions" name="staff_regions" value="<?php echo esc_attr($regions); ?>"
                placeholder="For example: Europe, Asia, North America" style="width: 100%; padding: 8px;">
        </div>

        <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: start;">
            <label for="staff_short_description"><strong>Short Description:</strong></label>
            <textarea id="staff_short_description" name="staff_short_description"
                placeholder="Brief information about the employee"
                style="width: 100%; padding: 8px; min-height: 80px; resize: vertical;"><?php echo esc_textarea($short_description); ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: start;">
            <label for="staff_language_skills"><strong>Language Skills:</strong></label>
            <textarea id="staff_language_skills" name="staff_language_skills"
                placeholder="For example: English (C1), German (B2), French (A2)"
                style="width: 100%; padding: 8px; min-height: 60px; resize: vertical;"><?php echo esc_textarea($language_skills); ?></textarea>
        </div>
    </div>
<?php
}

/**
 * Save data from the second metabox
 */
function codeweber_save_staff_additional_meta($post_id)
{
    // Check nonce
    if (!isset($_POST['staff_additional_meta_box_nonce']) || !wp_verify_nonce($_POST['staff_additional_meta_box_nonce'], 'staff_additional_meta_box')) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Save fields from the second metabox
    $additional_fields = ['staff_full_position', 'staff_regions', 'staff_short_description', 'staff_language_skills'];

    foreach ($additional_fields as $field) {
        if (isset($_POST[$field])) {
            if ($field === 'staff_short_description' || $field === 'staff_language_skills') {
                // Use sanitize_textarea_field for text areas
                update_post_meta($post_id, '_' . $field, sanitize_textarea_field($_POST[$field]));
            } else {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
}
add_action('save_post_staff', 'codeweber_save_staff_additional_meta');

/**
 * Add columns to admin for the second block
 */
function codeweber_add_staff_additional_admin_columns($columns)
{
    // Insert columns after existing ones
    $new_columns = [];

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        // Insert after "Phone" column
        if ($key === 'staff_phone') {
            $new_columns['staff_full_position'] = 'Full Position';
            $new_columns['staff_regions'] = 'Regions';
            $new_columns['staff_language_skills'] = 'Languages';
        }
    }

    return $new_columns;
}
add_filter('manage_staff_posts_columns', 'codeweber_add_staff_additional_admin_columns');

/**
 * Fill new columns with data
 */
function codeweber_fill_staff_additional_admin_columns($column, $post_id)
{
    switch ($column) {
        case 'staff_full_position':
            echo esc_html(get_post_meta($post_id, '_staff_full_position', true));
            break;
        case 'staff_regions':
            echo esc_html(get_post_meta($post_id, '_staff_regions', true));
            break;
        case 'staff_language_skills':
            $languages = get_post_meta($post_id, '_staff_language_skills', true);
            echo esc_html(mb_strimwidth($languages, 0, 50, '...')); // Trim long text
            break;
    }
}
add_action('manage_staff_posts_custom_column', 'codeweber_fill_staff_additional_admin_columns', 10, 2);

/**
 * Make new columns sortable
 */
function codeweber_make_staff_additional_columns_sortable($columns)
{
    $columns['staff_full_position'] = 'staff_full_position';
    $columns['staff_regions'] = 'staff_regions';
    $columns['staff_language_skills'] = 'staff_language_skills';
    return $columns;
}
add_filter('manage_edit-staff_sortable_columns', 'codeweber_make_staff_additional_columns_sortable');




/**
 * Шорткод для вывода сотрудников (staff) с сортировкой по дате создания
 */
function codeweber_staff_shortcode($atts)
{
    // Атрибуты шорткода
    $atts = shortcode_atts(array(
        'posts_per_page' => -1,
        'orderby' => 'date',  // Сортировка по дате создания
        'order' => 'DESC'     // По умолчанию новые сначала
    ), $atts);

    // Аргументы для WP_Query
    $args = array(
        'post_type' => 'staff',
        'posts_per_page' => $atts['posts_per_page'],
        'orderby' => $atts['orderby'],
        'order' => $atts['order']
    );

    $query = new WP_Query($args);
    $output = '';

    if ($query->have_posts()) {
        $output .= '<div class="row gx-md-5 gy-5">';

        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            // Получаем метаданные
            $position = get_post_meta($post_id, '_staff_position', true);
            $name = get_post_meta($post_id, '_staff_name', true);
            $surname = get_post_meta($post_id, '_staff_surname', true);
            $regions = get_post_meta($post_id, '_staff_regions', true);
            $thumbnail = get_the_post_thumbnail_url($post_id, 'full');

            // Формируем полное имя
            $full_name = trim($name . ' <span class="text-uppercase">' . $surname . '</span>');

            $output .= '
            <div class="col-md-4">
                <a href="' . get_permalink() . '" class="swiper-slide" data-cue="slideInDown">
                    <figure class="lift">';

            if ($thumbnail) {
                $output .= '<img src="' . esc_url($thumbnail) . '" alt="' . esc_attr($full_name) . '" />';
            } else {
                $output .= '<img src="/wp-content/uploads/placeholder.jpg" alt="' . esc_attr($full_name) . '" />';
            }

            $output .= '
                        <div class="caption-wrapper p-7">
                            <div class="caption bg-matte-color mt-auto label-u text-neutral-50 px-4 py-2">' . esc_html($position) . '</div>
                        </div>
                    </figure>
                    <div class="team-item-content text-dark mt-4">
                        <h3 class="h4">' . $full_name . '</h3>';

            if ($regions) {
                $output .= '<div class="label-u">' . esc_html($regions) . '</div>';
            }

            $output .= '
                    </div>
                </a>
            </div>';
        }

        $output .= '</div>';
        wp_reset_postdata();
    } else {
        $output = '<p>No employees found</p>';
    }

    return $output;
}
add_shortcode('staff_grid', 'codeweber_staff_shortcode');