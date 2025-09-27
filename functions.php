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



add_filter( 'register_post_type_args', 'modify_faq_post_type_args', 10, 2 );
function modify_faq_post_type_args( $args, $post_type ) {
    if ( 'faq' === $post_type ) {
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