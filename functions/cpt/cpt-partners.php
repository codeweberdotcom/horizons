<?php

function cptui_register_my_cpts_partners()
{
	/**
	 * Post Type: Partners.
	 */
	$labels = [
		"name" => esc_html__("Partners", "horizons"),
		"singular_name" => esc_html__("Partner", "horizons"),
		"menu_name" => esc_html__("Partners", "horizons"),
		"all_items" => esc_html__("All Partners", "horizons"),
		"add_new" => esc_html__("Add Partner", "horizons"),
		"add_new_item" => esc_html__("Add New Partner", "horizons"),
		"edit_item" => esc_html__("Edit Partner", "horizons"),
		"new_item" => esc_html__("New Partner", "horizons"),
		"view_item" => esc_html__("View Partner", "horizons"),
		"view_items" => esc_html__("View Partners", "horizons"),
		"search_items" => esc_html__("Search Partners", "horizons"),
		"not_found" => esc_html__("No Partners found", "horizons"),
		"not_found_in_trash" => esc_html__("No Partners found in Trash", "horizons"),
		"parent" => esc_html__("Parent Partner", "horizons"),
		"featured_image" => esc_html__("Partner Image", "horizons"),
		"set_featured_image" => esc_html__("Set partner image", "horizons"),
		"remove_featured_image" => esc_html__("Remove partner image", "horizons"),
		"use_featured_image" => esc_html__("Use as partner image", "horizons"),
		"archives" => esc_html__("Partners archive", "horizons"),
		"items_list" => esc_html__("Partners list", "horizons"),
		"name_admin_bar" => esc_html__("Partner", "horizons"),
		"item_published" => esc_html__("Partner published", "horizons"),
		"item_reverted_to_draft" => esc_html__("Partner reverted to draft", "horizons"),
		"item_scheduled" => esc_html__("Partner scheduled", "horizons"),
		"item_updated" => esc_html__("Partner updated", "horizons"),
		"parent_item_colon" => esc_html__("Parent Partner", "horizons"),
	];

	$args = [
		"label" => esc_html__("Partners", "horizons"),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => true,
		"rewrite" => ["slug" => "partners", "with_front" => true],
		"query_var" => true,
		"supports" => ["title", "thumbnail", "editor", "revisions"],
		"show_in_graphql" => false,
		"taxonomies" => ["partner_category", "partner_country", "partner_language", "partner_region"],
	];

	register_post_type("partners", $args);
}

add_action('init', 'cptui_register_my_cpts_partners');

// Регистрируем таксономию "Категория партнеров"
function register_partner_category_taxonomy()
{
	$labels = [
		'name' => esc_html__('Partner Categories', 'horizons'),
		'singular_name' => esc_html__('Partner Category', 'horizons'),
		'menu_name' => esc_html__('Categories', 'horizons'),
		'all_items' => esc_html__('All Categories', 'horizons'),
		'edit_item' => esc_html__('Edit Category', 'horizons'),
		'view_item' => esc_html__('View Category', 'horizons'),
		'update_item' => esc_html__('Update Category', 'horizons'),
		'add_new_item' => esc_html__('Add New Category', 'horizons'),
		'new_item_name' => esc_html__('New Category Name', 'horizons'),
		'parent_item' => esc_html__('Parent Category', 'horizons'),
		'parent_item_colon' => esc_html__('Parent Category:', 'horizons'),
		'search_items' => esc_html__('Search Categories', 'horizons'),
		'popular_items' => esc_html__('Popular Categories', 'horizons'),
		'separate_items_with_commas' => esc_html__('Separate categories with commas', 'horizons'),
		'add_or_remove_items' => esc_html__('Add or remove categories', 'horizons'),
		'choose_from_most_used' => esc_html__('Choose from the most used categories', 'horizons'),
		'not_found' => esc_html__('No categories found', 'horizons'),
	];

	$args = [
		'label' => esc_html__('Partner Categories', 'horizons'),
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => false, // Отключаем публичные URL
		'hierarchical' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => false, // Скрываем из меню навигации
		'show_in_rest' => true,
		'show_tagcloud' => false, // Отключаем облако тегов
		'show_in_quick_edit' => true,
		'show_admin_column' => true,
		'rewrite' => false, // Отключаем перезапись URL
		'query_var' => false, // Отключаем query var
	];

	register_taxonomy('partner_category', ['partners'], $args);
}

// Регистрируем таксономию "Страна партнеров"
function register_partner_country_taxonomy()
{
	$labels = [
		'name' => esc_html__('Partner Countries', 'horizons'),
		'singular_name' => esc_html__('Partner Country', 'horizons'),
		'menu_name' => esc_html__('Countries', 'horizons'),
		'all_items' => esc_html__('All Countries', 'horizons'),
		'edit_item' => esc_html__('Edit Country', 'horizons'),
		'view_item' => esc_html__('View Country', 'horizons'),
		'update_item' => esc_html__('Update Country', 'horizons'),
		'add_new_item' => esc_html__('Add New Country', 'horizons'),
		'new_item_name' => esc_html__('New Country Name', 'horizons'),
		'search_items' => esc_html__('Search Countries', 'horizons'),
		'popular_items' => esc_html__('Popular Countries', 'horizons'),
		'separate_items_with_commas' => esc_html__('Separate countries with commas', 'horizons'),
		'add_or_remove_items' => esc_html__('Add or remove countries', 'horizons'),
		'choose_from_most_used' => esc_html__('Choose from the most used countries', 'horizons'),
		'not_found' => esc_html__('No countries found', 'horizons'),
	];

	$args = [
		'label' => esc_html__('Partner Countries', 'horizons'),
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => false, // Отключаем публичные URL
		'hierarchical' => false,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => false, // Скрываем из меню навигации
		'show_in_rest' => true,
		'show_tagcloud' => false, // Отключаем облако тегов
		'show_in_quick_edit' => true,
		'show_admin_column' => true,
		'rewrite' => false, // Отключаем перезапись URL
		'query_var' => false, // Отключаем query var
	];

	register_taxonomy('partner_country', ['partners'], $args);
}

// Регистрируем таксономию "Языки партнеров"
function register_partner_language_taxonomy()
{
	$labels = [
		'name' => esc_html__('Partner Languages', 'horizons'),
		'singular_name' => esc_html__('Partner Language', 'horizons'),
		'menu_name' => esc_html__('Languages', 'horizons'),
		'all_items' => esc_html__('All Languages', 'horizons'),
		'edit_item' => esc_html__('Edit Language', 'horizons'),
		'view_item' => esc_html__('View Language', 'horizons'),
		'update_item' => esc_html__('Update Language', 'horizons'),
		'add_new_item' => esc_html__('Add New Language', 'horizons'),
		'new_item_name' => esc_html__('New Language Name', 'horizons'),
		'search_items' => esc_html__('Search Languages', 'horizons'),
		'popular_items' => esc_html__('Popular Languages', 'horizons'),
		'separate_items_with_commas' => esc_html__('Separate languages with commas', 'horizons'),
		'add_or_remove_items' => esc_html__('Add or remove languages', 'horizons'),
		'choose_from_most_used' => esc_html__('Choose from the most used languages', 'horizons'),
		'not_found' => esc_html__('No languages found', 'horizons'),
	];

	$args = [
		'label' => esc_html__('Partner Languages', 'horizons'),
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => false, // Отключаем публичные URL
		'hierarchical' => false,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => false, // Скрываем из меню навигации
		'show_in_rest' => true,
		'show_tagcloud' => false, // Отключаем облако тегов
		'show_in_quick_edit' => true,
		'show_admin_column' => true,
		'rewrite' => false, // Отключаем перезапись URL
		'query_var' => false, // Отключаем query var
	];

	register_taxonomy('partner_language', ['partners'], $args);
}

// Регистрируем таксономию "Регионы партнеров"
function register_partner_region_taxonomy()
{
	$labels = [
		'name' => esc_html__('Partner Regions', 'horizons'),
		'singular_name' => esc_html__('Partner Region', 'horizons'),
		'menu_name' => esc_html__('Regions', 'horizons'),
		'all_items' => esc_html__('All Regions', 'horizons'),
		'edit_item' => esc_html__('Edit Region', 'horizons'),
		'view_item' => esc_html__('View Region', 'horizons'),
		'update_item' => esc_html__('Update Region', 'horizons'),
		'add_new_item' => esc_html__('Add New Region', 'horizons'),
		'new_item_name' => esc_html__('New Region Name', 'horizons'),
		'parent_item' => esc_html__('Parent Region', 'horizons'),
		'parent_item_colon' => esc_html__('Parent Region:', 'horizons'),
		'search_items' => esc_html__('Search Regions', 'horizons'),
		'popular_items' => esc_html__('Popular Regions', 'horizons'),
		'separate_items_with_commas' => esc_html__('Separate regions with commas', 'horizons'),
		'add_or_remove_items' => esc_html__('Add or remove regions', 'horizons'),
		'choose_from_most_used' => esc_html__('Choose from the most used regions', 'horizons'),
		'not_found' => esc_html__('No regions found', 'horizons'),
	];

	$args = [
		'label' => esc_html__('Partner Regions', 'horizons'),
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => false, // Отключаем публичные URL
		'hierarchical' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => false, // Скрываем из меню навигации
		'show_in_rest' => true,
		'show_tagcloud' => false, // Отключаем облако тегов
		'show_in_quick_edit' => true,
		'show_admin_column' => true,
		'rewrite' => false, // Отключаем перезапись URL
		'query_var' => false, // Отключаем query var
	];

	register_taxonomy('partner_region', ['partners'], $args);
}

add_action('init', 'register_partner_category_taxonomy');
add_action('init', 'register_partner_country_taxonomy');
add_action('init', 'register_partner_language_taxonomy');
add_action('init', 'register_partner_region_taxonomy');

add_filter('use_block_editor_for_post_type', 'disable_gutenberg_for_partners', 10, 2);
function disable_gutenberg_for_partners($current_status, $post_type)
{
	if ($post_type === 'partners') {
		return false;
	}
	return $current_status;
}
