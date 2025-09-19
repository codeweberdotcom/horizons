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
	];

	register_post_type("partners", $args);
}

add_action('init', 'cptui_register_my_cpts_partners');



add_filter('use_block_editor_for_post_type', 'disable_gutenberg_for_partners', 10, 2);
function disable_gutenberg_for_partners($current_status, $post_type)
{
	if ($post_type === 'partners') {
		return false;
	}
	return $current_status;
}