<?php
/**
 * horizons functions and definitions
 *
 * @package horizons
 */

add_action( 'wp_enqueue_scripts', 'horizons_enqueue_styles' );
function horizons_enqueue_styles() {
    wp_enqueue_style( 'horizons-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'codeweber-style' ),
        wp_get_theme()->get('Version')
    );
}
