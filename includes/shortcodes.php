<?php
//--------------------------------
//STAFF
//--------------------------------
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
                $output .= '<img src="' . esc_url(get_template_directory_uri() . '/assets/img/placeholder.jpg') . '" alt="' . esc_attr($full_name) . '" />';
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
        $output = '<p>' . __('No employees found', 'codeweber') . '</p>';
    }

    return $output;
}
add_shortcode('staff_grid', 'codeweber_staff_shortcode');


//--------------------------------
//SERVICES
//--------------------------------

add_shortcode('service_categories_cards', 'service_categories_cards_shortcode');

function service_categories_cards_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'posts_per_page' => -1,
        'orderby' => 'meta_value_num', // Changed default to meta_value_num for ordering by our custom field
        'order' => 'ASC',
        'columns' => '3',
        'use_alt_title' => 'true' // New parameter to control title display
    ), $atts);

    // Convert string to boolean
    $use_alt_title = filter_var($atts['use_alt_title'], FILTER_VALIDATE_BOOLEAN);

    // Get terms with proper parameters
    $args = array(
        'taxonomy' => 'service_category',
        'hide_empty' => false,
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    );

    // Add meta_key if ordering by meta_value or meta_value_num
    if ($atts['orderby'] === 'meta_value_num' || $atts['orderby'] === 'meta_value') {
        $args['meta_key'] = 'service_category_order';
    }

    // Add number only if not -1
    if ($atts['posts_per_page'] != -1) {
        $args['number'] = $atts['posts_per_page'];
    }

    $terms = get_terms($args);

    // Check for errors and empty results
    if (is_wp_error($terms)) {
        return '<p>' . sprintf(__('Error loading categories: %s', 'codeweber'), $terms->get_error_message()) . '</p>';
    }

    if (empty($terms)) {
        return '<p>' . __('No categories found', 'codeweber') . '</p>';
    }

    // Manual sorting if terms don't have order meta
    if ($atts['orderby'] === 'meta_value_num' || $atts['orderby'] === 'meta_value') {
        usort($terms, function ($a, $b) use ($atts) {
            $order_a = get_term_meta($a->term_id, 'service_category_order', true);
            $order_b = get_term_meta($b->term_id, 'service_category_order', true);

            $order_a = empty($order_a) ? 0 : intval($order_a);
            $order_b = empty($order_b) ? 0 : intval($order_b);

            if ($atts['order'] === 'ASC') {
                return $order_a <=> $order_b;
            } else {
                return $order_b <=> $order_a;
            }
        });
    }

    $column_class = 'col-md-' . (12 / intval($atts['columns']));

    ob_start();
?>
    <?php foreach ($terms as $term):
        $color = get_term_meta($term->term_id, 'service_category_color', true);
        $color_class = $color ? 'bg-' . $color : '';
        $term_link = get_term_link($term);

        // Get alternative title if enabled
        $display_title = $term->name;
        if ($use_alt_title) {
            $alt_title = get_term_meta($term->term_id, 'service_category_alt_title', true);
            if (!empty($alt_title)) {
                $display_title = $alt_title;
            }
        }

        // Check link for errors
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
                    <h3 class="h4"><?php echo wp_kses_post($display_title); ?></h3>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
    <div class="col-md-4">
        <a href="/services" class="card practice-card bg-dusty-navy h-100" data-cue="slideInDown">
            <div class="card-body align-content-center text-center">
                <span class="hover-4 link-body label-s text-sub-white"><?php echo __('All Practice', 'horizons'); ?></span>
            </div>
            <!--/.card-body -->
        </a>
        <!--/.card -->
    </div>
    <!--/column -->
    <?php
    return ob_get_clean();
}


//--------------------------------
//AWARDS
//--------------------------------

/**
 * Шорткод для вывода сетки наград (awards)
 * 
 * Примеры использования:
 * [awards_grid] - выведет 6 записей в 3 колонки
 * [awards_grid staff_awards="true"] - выведет награды текущего сотрудника
 * [awards_grid posts_per_page="4" columns="2"] - 4 записи в 2 колонки
 * [awards_grid posts_per_page="8" columns="4" columns_md="3" columns_sm="1"] - кастомные колонки
 * [awards_grid orderby="title" order="ASC"] - сортировка по названию по возрастанию
 * 
 * Параметры:
 * @param int    $posts_per_page - Количество записей для вывода (по умолчанию: 6)
 * @param string $orderby        - Поле для сортировки (date, title, menu_order etc.)
 * @param string $order          - Направление сортировки (ASC, DESC)
 * @param int    $columns        - Количество колонок на больших экранах (lg)
 * @param int    $columns_md     - Количество колонок на средних экранах (md)
 * @param int    $columns_sm     - Количество колонок на маленьких экранах (sm)
 * @param bool   $staff_awards   - Если true, выводит награды текущего сотрудника
 */
add_shortcode('awards_grid', 'awards_grid_shortcode');

function awards_grid_shortcode($atts)
{
    // Параметры по умолчанию
    $atts = shortcode_atts(array(
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'columns'        => 4,
        'columns_md'     => 4,
        'columns_sm'     => 1,
        'staff_awards'   => false
    ), $atts);

    // Если запрашиваем награды сотрудника
    if ($atts['staff_awards'] === 'true' && is_singular('staff')) {
        global $post;

        // Get selected awards for this staff member
        $selected_awards = get_post_meta($post->ID, '_staff_awards', true);
        if (!is_array($selected_awards)) {
            $selected_awards = array();
        }

        // Если нет выбранных наград, выводим только кнопку "Все награды"
        if (empty($selected_awards)) {
            ob_start();
    ?>
            <div class="row row-cols-1 row-cols-sm-1 row-cols-md-4 row-cols-lg-4 gx-3 gy-3">
                <div class="col">
                    <a href="/awards" class="card h-100 bg-dusty-navy" style="min-height: 191.3px">
                        <div class="card-body align-content-center text-center">
                            <span class="hover-4 link-body label-s text-sub-white"><?php echo __('All Awards', 'horizons'); ?></span>
                        </div>
                        <!--/.card-body -->
                    </a>
                    <!--/.card -->
                </div>
            </div>
        <?php
            return ob_get_clean();
        }

        $args = array(
            'post_type'      => 'awards',
            'posts_per_page' => intval($atts['posts_per_page']),
            'post__in'       => $selected_awards,
            'orderby'        => 'post__in', // Сохраняем порядок выбора
            'post_status'    => 'publish'
        );
    } else {
        // Обычный вывод
        $args = array(
            'post_type'      => 'awards',
            'posts_per_page' => intval($atts['posts_per_page']),
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
            'post_status'    => 'publish'
        );
    }

    $awards_query = new WP_Query($args);

    ob_start();

    if ($awards_query->have_posts()) :
        // Формируем классы для колонок
        $column_classes = sprintf(
            'row row-cols-1 row-cols-sm-%d row-cols-md-%d row-cols-lg-%d gx-3 gy-3',
            intval($atts['columns_sm']),
            intval($atts['columns_md']),
            intval($atts['columns'])
        );
        ?>
        <div class="<?php echo esc_attr($column_classes); ?>">
            <?php while ($awards_query->have_posts()) : $awards_query->the_post();
                $image_id = get_post_thumbnail_id();
                $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
                $permalink = get_permalink();
            ?>
                <div class="col">
                    <a href="<?php echo esc_url($permalink); ?>" class="card hover-scale h-100 align-items-center">
                        <div class="card-body align-items-center d-flex p-0">
                            <figure class="p-0 mb-0">
                                <?php if ($image_url) : ?>
                                    <img decoding="async" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                <?php else : ?>
                                    <div style="width: 100%; height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                        <span><?php _e('No image', 'horizons'); ?></span>
                                    </div>
                                <?php endif; ?>
                            </figure>
                        </div>
                        <!--/.card-body -->
                    </a>
                    <!--/.card -->
                </div>
            <?php endwhile; ?>

            <!-- Кнопка "All Awards" отображается ВСЕГДА -->
            <div class="col">
                <a href="/awards" class="card h-100 bg-dusty-navy" style="min-height: 191.3px">
                    <div class="card-body align-content-center text-center">
                        <span class="hover-4 link-body label-s text-sub-white"><?php echo __('All Awards', 'horizons'); ?></span>
                    </div>
                    <!--/.card-body -->
                </a>
                <!--/.card -->
            </div>
        </div>
    <?php
    else :
        // Если нет записей, все равно выводим кнопку "Все награды"
    ?>
        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-4 row-cols-lg-4 gx-3 gy-3">
            <div class="col">
                <a href="/awards" class="card h-100 bg-dusty-navy" style="min-height: 191.3px">
                    <div class="card-body align-content-center text-center">
                        <span class="hover-4 link-body label-s text-sub-white"><?php echo __('All Awards', 'horizons'); ?></span>
                    </div>
                    <!--/.card-body -->
                </a>
                <!--/.card -->
            </div>
        </div>
<?php
    endif;

    wp_reset_postdata();

    return ob_get_clean();
}
