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
    '/includes/custom-cpt.php',
    '/includes/add_image_sizes.php',
);

foreach ($theme_includes as $file) {
    $file_path = get_stylesheet_directory() . $file;
    if (file_exists($file_path)) {
        require_once $file_path;
    }
}
