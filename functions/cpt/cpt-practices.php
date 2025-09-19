<?php

function cptui_register_my_cpts_practices()
{
	/**
	 * Post Type: Practices.
	 */
	$labels = [
		"name" => esc_html__("Practices", "horizons"),
		"singular_name" => esc_html__("Practice", "horizons"),
		"menu_name" => esc_html__("Practices", "horizons"),
		"all_items" => esc_html__("All Practices", "horizons"),
		"add_new" => esc_html__("Add Practice", "horizons"),
		"add_new_item" => esc_html__("Add New Practice", "horizons"),
		"edit_item" => esc_html__("Edit Practice", "horizons"),
		"new_item" => esc_html__("New Practice", "horizons"),
		"view_item" => esc_html__("View Practice", "horizons"),
		"view_items" => esc_html__("View Practices", "horizons"),
		"search_items" => esc_html__("Search Practices", "horizons"),
		"not_found" => esc_html__("No Practices found", "horizons"),
		"not_found_in_trash" => esc_html__("No Practices found in Trash", "horizons"),
		"parent" => esc_html__("Parent Practice", "horizons"),
		"featured_image" => esc_html__("Practice Image", "horizons"),
		"set_featured_image" => esc_html__("Set practice image", "horizons"),
		"remove_featured_image" => esc_html__("Remove practice image", "horizons"),
		"use_featured_image" => esc_html__("Use as practice image", "horizons"),
		"archives" => esc_html__("Practices archive", "horizons"),
		"items_list" => esc_html__("Practices list", "horizons"),
		"name_admin_bar" => esc_html__("Practice", "horizons"),
		"item_published" => esc_html__("Practice published", "horizons"),
		"item_reverted_to_draft" => esc_html__("Practice reverted to draft", "horizons"),
		"item_scheduled" => esc_html__("Practice scheduled", "horizons"),
		"item_updated" => esc_html__("Practice updated", "horizons"),
		"parent_item_colon" => esc_html__("Parent Practice", "horizons"),
	];

	$args = [
		"label" => esc_html__("Practices", "horizons"),
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
		"rewrite" => ["slug" => "practices", "with_front" => true],
		"query_var" => true,
		"supports" => ["title", "thumbnail", "editor", "revisions"],
		"show_in_graphql" => false,
	];

	register_post_type("practices", $args);
}

add_action('init', 'cptui_register_my_cpts_practices');


function cptui_register_my_taxes_practice_category()
{
	/**
	 * Taxonomy: Practice Categories.
	 */
	$labels = [
		"name" => __("Practice Categories", "horizons"),
		"singular_name" => __("Practice Category", "horizons"),
	];

	$args = [
		"label" => __("Practice Categories", "horizons"),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => ['slug' => 'practice_category', 'with_front' => true],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "practice_category",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];

	register_taxonomy("practice_category", ["practices"], $args);
}

add_action('init', 'cptui_register_my_taxes_practice_category');

function cptui_register_my_taxes_types_of_practices()
{
	/**
	 * Taxonomy: Types.
	 */
	$labels = [
		"name" => __("Types", "horizons"),
		"singular_name" => __("Type", "horizons"),
	];

	$args = [
		"label" => __("Types", "horizons"),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => ['slug' => 'types_of_practices', 'with_front' => true],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "types_of_practices",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];

	register_taxonomy("types_of_practices", ["practices"], $args);
}

add_action('init', 'cptui_register_my_taxes_types_of_practices');

add_filter('use_block_editor_for_post_type', 'disable_gutenberg_for_practices', 10, 2);
function disable_gutenberg_for_practices($current_status, $post_type)
{
	if ($post_type === 'practices') {
		return false;
	}
	return $current_status;
}



