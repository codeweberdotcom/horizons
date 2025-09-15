<?php
//--------------------------------
//STAFF
//--------------------------------
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


//--------------------------------
//SERVICES
//--------------------------------
// functions.php
add_shortcode('service_categories_cards', 'service_categories_cards_shortcode');

function service_categories_cards_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'posts_per_page' => -1,
        'orderby' => 'name',
        'order' => 'ASC',
        'columns' => '3'
    ), $atts);

    // Получаем термины с правильными параметрами
    $args = array(
        'taxonomy' => 'service_category',
        'hide_empty' => false,
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    );

    // Добавляем number только если не -1
    if ($atts['posts_per_page'] != -1) {
        $args['number'] = $atts['posts_per_page'];
    }

    $terms = get_terms($args);

    // Проверяем ошибки и пустой результат
    if (is_wp_error($terms)) {
        return '<p>Ошибка загрузки категорий: ' . $terms->get_error_message() . '</p>';
    }

    if (empty($terms)) {
        return '<p>Категории не найдены</p>';
    }

    $column_class = 'col-md-' . (12 / intval($atts['columns']));

    ob_start();
?>
        <?php foreach ($terms as $term):
            $color = get_term_meta($term->term_id, 'service_category_color', true);
            $color_class = $color ? 'bg-' . $color : '';
            $term_link = get_term_link($term);

            // Проверяем ссылку на ошибки
            if (is_wp_error($term_link)) {
                $term_link = '#';
            }
        ?>
            <div class="<?php echo esc_attr($column_class); ?>">
                <a href="<?php echo esc_url($term_link); ?>" class="card h-100 practice-card" data-cue="slideInDown">
                    <div class="brand-square-xs <?php echo esc_attr($color_class); ?> opacity-0 position-absolute top-0 start-0"></div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="pe-none mb-5">
                            <div class="practice-card-hover brand-square-md <?php echo esc_attr($color_class); ?>"></div>
                        </div>
                        <h3 class="h4"><?php echo esc_html($term->name); ?></h3>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
<?php
    return ob_get_clean();
}
