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
    '/includes/blog.php'
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