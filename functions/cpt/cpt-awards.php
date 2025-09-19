<?php

// Register Custom Post Type Awards
function create_awards_cpt()
{

	$labels = array(
		'name'                  => _x('Awards', 'Post Type General Name', 'horizons'),
		'singular_name'         => _x('Award', 'Post Type Singular Name', 'horizons'),
		'menu_name'             => __('Awards', 'horizons'),
		'name_admin_bar'        => __('Award', 'horizons'),
		'archives'              => __('Award Archives', 'horizons'),
		'attributes'            => __('Award Attributes', 'horizons'),
		'parent_item_colon'     => __('Parent Award:', 'horizons'),
		'all_items'             => __('All Awards', 'horizons'),
		'add_new_item'          => __('Add New Award', 'horizons'),
		'add_new'               => __('Add New', 'horizons'),
		'new_item'              => __('New Award', 'horizons'),
		'edit_item'             => __('Edit Award', 'horizons'),
		'update_item'           => __('Update Award', 'horizons'),
		'view_item'             => __('View Award', 'horizons'),
		'view_items'            => __('View Awards', 'horizons'),
		'search_items'          => __('Search Award', 'horizons'),
		'not_found'             => __('Not found', 'horizons'),
		'not_found_in_trash'    => __('Not found in Trash', 'horizons'),
		'featured_image'        => __('Award Image', 'horizons'),
		'set_featured_image'    => __('Set award image', 'horizons'),
		'remove_featured_image' => __('Remove award image', 'horizons'),
		'use_featured_image'    => __('Use as award image', 'horizons'),
		'insert_into_item'      => __('Insert into award', 'horizons'),
		'uploaded_to_this_item' => __('Uploaded to this award', 'horizons'),
		'items_list'            => __('Awards list', 'horizons'),
		'items_list_navigation' => __('Awards list navigation', 'horizons'),
		'filter_items_list'     => __('Filter awards list', 'horizons'),
	);

	$args = array(
		'label'                 => __('Award', 'horizons'),
		'description'           => __('Company awards and achievements', 'horizons'),
		'labels'                => $labels,
		'supports'              => array('title', 'editor', 'thumbnail'),
		'taxonomies'            => array('award_category', 'award_year', 'award_tags'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 25,
		'menu_icon'             => 'dashicons-awards',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'show_in_rest'          => true,
		'rewrite'               => array('slug' => 'awards'),
	);

	register_post_type('awards', $args);
}
add_action('init', 'create_awards_cpt', 0);

// Register Custom Taxonomy Award Category
function create_award_category_taxonomy()
{

	$labels = array(
		'name'                       => _x('Award Categories', 'Taxonomy General Name', 'horizons'),
		'singular_name'              => _x('Award Category', 'Taxonomy Singular Name', 'horizons'),
		'menu_name'                  => __('Categories', 'horizons'),
		'all_items'                  => __('All Categories', 'horizons'),
		'parent_item'                => __('Parent Category', 'horizons'),
		'parent_item_colon'          => __('Parent Category:', 'horizons'),
		'new_item_name'              => __('New Category Name', 'horizons'),
		'add_new_item'               => __('Add New Category', 'horizons'),
		'edit_item'                  => __('Edit Category', 'horizons'),
		'update_item'                => __('Update Category', 'horizons'),
		'view_item'                  => __('View Category', 'horizons'),
		'separate_items_with_commas' => __('Separate categories with commas', 'horizons'),
		'add_or_remove_items'        => __('Add or remove categories', 'horizons'),
		'choose_from_most_used'      => __('Choose from the most used', 'horizons'),
		'popular_items'              => __('Popular Categories', 'horizons'),
		'search_items'               => __('Search Categories', 'horizons'),
		'not_found'                  => __('Not Found', 'horizons'),
		'no_terms'                   => __('No categories', 'horizons'),
		'items_list'                 => __('Categories list', 'horizons'),
		'items_list_navigation'      => __('Categories list navigation', 'horizons'),
	);

	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
		'rewrite'                    => array('slug' => 'award-category'),
	);

	register_taxonomy('award_category', array('awards'), $args);
}
add_action('init', 'create_award_category_taxonomy', 0);

// Register Custom Taxonomy Award Year
function create_award_year_taxonomy()
{

	$labels = array(
		'name'                       => _x('Award Years', 'Taxonomy General Name', 'horizons'),
		'singular_name'              => _x('Award Year', 'Taxonomy Singular Name', 'horizons'),
		'menu_name'                  => __('Years', 'horizons'),
		'all_items'                  => __('All Years', 'horizons'),
		'parent_item'                => __('Parent Year', 'horizons'),
		'parent_item_colon'          => __('Parent Year:', 'horizons'),
		'new_item_name'              => __('New Year', 'horizons'),
		'add_new_item'               => __('Add New Year', 'horizons'),
		'edit_item'                  => __('Edit Year', 'horizons'),
		'update_item'                => __('Update Year', 'horizons'),
		'view_item'                  => __('View Year', 'horizons'),
		'separate_items_with_commas' => __('Separate years with commas', 'horizons'),
		'add_or_remove_items'        => __('Add or remove years', 'horizons'),
		'choose_from_most_used'      => __('Choose from the most used', 'horizons'),
		'popular_items'              => __('Popular Years', 'horizons'),
		'search_items'               => __('Search Years', 'horizons'),
		'not_found'                  => __('Not Found', 'horizons'),
		'no_terms'                   => __('No years', 'horizons'),
		'items_list'                 => __('Years list', 'horizons'),
		'items_list_navigation'      => __('Years list navigation', 'horizons'),
	);

	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
		'show_in_rest'               => true,
		'rewrite'                    => array('slug' => 'award-year'),
	);

	register_taxonomy('award_year', array('awards'), $args);
}
add_action('init', 'create_award_year_taxonomy', 0);

// Register Custom Taxonomy Award Tags
function create_award_tags_taxonomy()
{

	$labels = array(
		'name'                       => _x('Award Tags', 'Taxonomy General Name', 'horizons'),
		'singular_name'              => _x('Award Tag', 'Taxonomy Singular Name', 'horizons'),
		'menu_name'                  => __('Tags', 'horizons'),
		'all_items'                  => __('All Tags', 'horizons'),
		'parent_item'                => __('Parent Tag', 'horizons'),
		'parent_item_colon'          => __('Parent Tag:', 'horizons'),
		'new_item_name'              => __('New Tag Name', 'horizons'),
		'add_new_item'               => __('Add New Tag', 'horizons'),
		'edit_item'                  => __('Edit Tag', 'horizons'),
		'update_item'                => __('Update Tag', 'horizons'),
		'view_item'                  => __('View Tag', 'horizons'),
		'separate_items_with_commas' => __('Separate tags with commas', 'horizons'),
		'add_or_remove_items'        => __('Add or remove tags', 'horizons'),
		'choose_from_most_used'      => __('Choose from the most used', 'horizons'),
		'popular_items'              => __('Popular Tags', 'horizons'),
		'search_items'               => __('Search Tags', 'horizons'),
		'not_found'                  => __('Not Found', 'horizons'),
		'no_terms'                   => __('No tags', 'horizons'),
		'items_list'                 => __('Tags list', 'horizons'),
		'items_list_navigation'      => __('Tags list navigation', 'horizons'),
	);

	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
		'rewrite'                    => array('slug' => 'award-tags'),
	);

	register_taxonomy('award_tags', array('awards'), $args);
}
add_action('init', 'create_award_tags_taxonomy', 0);