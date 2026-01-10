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

// Загрузка переводов
function horizons_theme_setup()
{
    // Загрузка переводов дочерней темы
    load_child_theme_textdomain('horizons', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'horizons_theme_setup'); // ← ДОБАВЬТЕ ЭТУ СТРОЧКУ!

// Подключение дополнительных файлов
$theme_includes = array(
    '/includes/metaboxes.php',
    '/includes/shortcodes.php',
    '/includes/add_image_sizes.php',
    '/includes/disable-parent-widgets.php', // Отключение виджетов родительской темы (должен быть загружен до sidebars.php)
    '/includes/sidebars.php',
    '/includes/partners.php',
    '/includes/practices.php',
    '/includes/blog.php',
    '/includes/vacancies.php',
    '/includes/user.php'
);

foreach ($theme_includes as $file) {
    $file_path = get_stylesheet_directory() . $file;
    if (file_exists($file_path)) {
        require_once $file_path;
    }
}

// Добавляем выпадающую кнопку в TinyMCE
function horizons_custom_mce_buttons($buttons)
{
    // Добавляем кнопку меню
    array_push($buttons, 'horizons_elements_menu');
    return $buttons;
}
add_filter('mce_buttons', 'horizons_custom_mce_buttons');

// Регистрируем TinyMCE plugin
function horizons_custom_mce_plugin($plugin_array)
{
    $plugin_array['horizons_mce'] = get_stylesheet_directory_uri() . '/js/mce-horizons-menu.js';
    return $plugin_array;
}
add_filter('mce_external_plugins', 'horizons_custom_mce_plugin');


// Подключаем скрипт для элементов Horizons
function horizons_elements_scripts()
{
    wp_enqueue_script(
        'horizons-elements',
        get_stylesheet_directory_uri() . '/js/horizons-elements.js',
        array('jquery'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('wp_enqueue_scripts', 'horizons_elements_scripts');




function modify_cpt_args($args, $post_type)
{
    if ($post_type === 'documents') {
        $args['has_archive'] = false; // Просто меняем параметр
    }
    return $args;
}
add_filter('register_post_type_args', 'modify_cpt_args', 10, 2);


function modify_taxonomy_args($args, $taxonomy_name)
{
    if ($taxonomy_name === 'document_category' || $taxonomy_name === 'document_type') {
        $args['public'] = false; // Полностью отключаем публичность
        $args['publicly_queryable'] = false; // Запрещаем query запросы
        $args['show_ui'] = true; // Оставляем интерфейс в админке
        $args['show_in_menu'] = true; // Показываем в меню админки
        $args['show_in_rest'] = true; // Для Gutenberg если нужно
        $args['rewrite'] = false; // Отключаем ЧПУ
        $args['query_var'] = false; // Полностью отключаем query_var
    }
    return $args;
}
add_filter('register_taxonomy_args', 'modify_taxonomy_args', 10, 2);

function modify_taxonomy_args1($args, $taxonomy_name)
{
    if ($taxonomy_name === 'document_type') {
        $args['public'] = false; // Полностью отключаем публичность
        $args['publicly_queryable'] = false; // Запрещаем query запросы
        $args['show_ui'] = true; // Оставляем интерфейс в админке
        $args['show_in_menu'] = true; // Показываем в меню админки
        $args['show_in_rest'] = true; // Для Gutenberg если нужно
        $args['rewrite'] = false; // Отключаем ЧПУ
        $args['query_var'] = false; // Полностью отключаем query_var
    }
    return $args;
}
add_filter('register_taxonomy_args', 'modify_taxonomy_args1', 10, 2);



add_filter('register_post_type_args', 'modify_faq_post_type_args', 10, 2);
function modify_faq_post_type_args($args, $post_type)
{
    if ('faq' === $post_type) {
        $args['has_archive'] = false;
        $args['publicly_queryable'] = false; // опционально
    }
    return $args;
}


// Универсальная функция для получения ссылки (партнер или пользователь)
function get_user_partner_link($user_id = null)
{
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    $partner_id = get_user_meta($user_id, 'user_partner', true);

    // Если есть партнер - возвращаем ссылку на партнера
    if ($partner_id) {
        return array(
            'url' => get_permalink($partner_id),
            'title' => get_the_title($partner_id),
            'id' => $partner_id,
            'edit_url' => get_edit_post_link($partner_id),
            'type' => 'partner'
        );
    }

    // Если нет партнера - возвращаем ссылку на профиль пользователя
    return array(
        'url' => get_author_posts_url($user_id),
        'title' => get_the_author_meta('display_name', $user_id),
        'id' => $user_id,
        'edit_url' => get_edit_user_link($user_id),
        'type' => 'user'
    );
}


// Регистрация блока Practice Categories Grid
function horizons_register_practice_categories_grid_block() {
	$block_path = get_stylesheet_directory() . '/blocks/practice-categories-grid';
	
	if (!file_exists($block_path . '/block.json')) {
		return;
	}
	
	// Проверяем наличие необходимых файлов
	if (!file_exists($block_path . '/index.js') || !file_exists($block_path . '/index.asset.php')) {
		return;
	}
	
	// Регистрируем блок напрямую через register_block_type
	$result = register_block_type($block_path);
	
	if (is_wp_error($result)) {
		if (defined('WP_DEBUG') && WP_DEBUG) {
			error_log('Practice Categories Grid registration error: ' . $result->get_error_message());
		}
	}
}
add_action('init', 'horizons_register_practice_categories_grid_block', 20);

// Подключение скриптов для фильтрации наград
add_action('wp_enqueue_scripts', 'enqueue_awards_filter_scripts');
function enqueue_awards_filter_scripts()
{
    // Проверяем, находимся ли мы на страницах, связанных с наградами
    if (is_post_type_archive('awards') || is_tax('award_category') || is_tax('award_year') || is_singular('awards')) {

        // Подключаем наш скрипт фильтрации
        wp_enqueue_script(
            'awards-filter',
            get_stylesheet_directory_uri() . '/js/awards-filter.js',
            array('jquery'),
            '1.0.0',
            true
        );

        // Локализация параметров для AJAX
        wp_localize_script('awards-filter', 'awardsAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('awards_filter_nonce')
        ));
    }
}

// AJAX обработчик для фильтрации наград
add_action('wp_ajax_filter_awards', 'handle_filter_awards');
add_action('wp_ajax_nopriv_filter_awards', 'handle_filter_awards');

function handle_filter_awards()
{
    if (!wp_verify_nonce($_POST['nonce'], 'awards_filter_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }

    $filter_type = sanitize_text_field($_POST['filter_type']);
    $filter_url = esc_url_raw($_POST['filter_url']);

    $url_parts = parse_url($filter_url);
    $query_params = [];
    if (isset($url_parts['query'])) {
        parse_str($url_parts['query'], $query_params);
    }

    $args = array(
        'post_type' => 'awards',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );

    if (isset($query_params['category'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'award_category',
                'field' => 'slug',
                'terms' => sanitize_text_field($query_params['category'])
            )
        );
    } elseif (isset($query_params['year'])) {
        $args['date_query'] = array(
            array(
                'year' => intval($query_params['year'])
            )
        );
    } elseif (isset($query_params['partner'])) {
        $args['meta_query'] = array(
            array(
                'key' => '_award_partners',
                'value' => intval($query_params['partner']),
                'compare' => 'LIKE'
            )
        );
    }
    elseif (basename($url_parts['path']) === 'awards') {
        $args = array(
            'post_type' => 'awards',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        );
    }

    $awards_query = new WP_Query($args);

    ob_start();
?>
    <div class="row g-3 isotope">
        <?php if ($awards_query->have_posts()) : ?>
            <?php while ($awards_query->have_posts()) : $awards_query->the_post(); ?>
                <?php
                $award_organization = get_post_meta(get_the_ID(), '_award_organization', true);
                $award_url = get_post_meta(get_the_ID(), '_award_url', true);
                $award_partners = get_post_meta(get_the_ID(), '_award_partners', true);
                $formatted_date = get_the_date('F Y');

                $categories = get_the_terms(get_the_ID(), 'award_category');
                $category_text = '';
                if ($categories && !is_wp_error($categories)) {
                    $category_names = array();
                    foreach ($categories as $category) {
                        $category_names[] = $category->name;
                    }
                    $category_text = implode(', ', $category_names);
                }

                $partners_text = '';
                if ($award_partners && is_array($award_partners) && !empty($award_partners)) {
                    $partners_count = count($award_partners);

                    if ($partners_count > 1) {
                        $partners_text = __('Horizons', 'horizons');
                    } else {
                        $partner_names = array();
                        foreach ($award_partners as $partner_id) {
                            $partner = get_post($partner_id);
                            if ($partner) {
                                $partner_names[] = $partner->post_title;
                            }
                        }
                        $partners_text = implode(', ', $partner_names);
                    }
                }
                ?>
                <div class="project item col-6 col-xl-6">
                    <figure class="overlay overlay-3 hover-scale card">
                        <a href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('codeweber_awards', array(
                                    'class' => 'img-fluid w-100',
                                    'alt' => get_the_title()
                                )); ?>
                            <?php else : ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/photos/p6.jpg"
                                    srcset="<?php echo get_template_directory_uri(); ?>/assets/img/photos/p6@2x.jpg 2x"
                                    alt="<?php the_title_attribute(); ?>" class="img-fluid w-100" />
                            <?php endif; ?>
                        </a>
                        <figcaption>
                            <h2 class="from-left body-l-r mb-3"><?php the_title(); ?></h2>
                            <div class="award-desc-group d-flex flex-wrap">
                                <?php if ($category_text) : ?>
                                    <span class="from-left mb-1  me-3 text-square-before label-u"><?php echo esc_html($category_text); ?></span>
                                <?php endif; ?>

                                <?php if ($award_organization) : ?>
                                    <span class="from-left mb-1  me-3 text-square-before label-u"><?php echo esc_html($award_organization); ?></span>
                                <?php endif; ?>

                                <div class="d-flex flex-wrap">
                                    <?php if ($formatted_date) : ?>
                                        <span class="from-left mb-1 me-3  text-square-before label-u">
                                            <?php echo esc_html($formatted_date); ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($partners_text) : ?>
                                        <span class="from-left mb-1  me-3 text-square-before label-u">
                                            <?php echo esc_html($partners_text); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </figcaption>
                    </figure>
                </div>
                <!-- /.item -->
            <?php endwhile; ?>
        <?php else : ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <span><?php _e('No awards found.', 'horizons'); ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- /.row -->
<?php
    $html = ob_get_clean();

    // Сбрасываем post data
    wp_reset_postdata();

    wp_send_json_success(array(
        'html' => $html,
        'found_posts' => $awards_query->found_posts
    ));
}
?>

