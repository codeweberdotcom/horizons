<?php

// Register Custom Post Type Awards
function create_awards_cpt() {

    $labels = array(
        'name'                  => _x( 'Awards', 'Post Type General Name', 'textdomain' ),
        'singular_name'         => _x( 'Award', 'Post Type Singular Name', 'textdomain' ),
        'menu_name'             => __( 'Awards', 'textdomain' ),
        'name_admin_bar'        => __( 'Award', 'textdomain' ),
        'archives'              => __( 'Award Archives', 'textdomain' ),
        'attributes'            => __( 'Award Attributes', 'textdomain' ),
        'parent_item_colon'     => __( 'Parent Award:', 'textdomain' ),
        'all_items'             => __( 'All Awards', 'textdomain' ),
        'add_new_item'          => __( 'Add New Award', 'textdomain' ),
        'add_new'               => __( 'Add New', 'textdomain' ),
        'new_item'              => __( 'New Award', 'textdomain' ),
        'edit_item'             => __( 'Edit Award', 'textdomain' ),
        'update_item'           => __( 'Update Award', 'textdomain' ),
        'view_item'             => __( 'View Award', 'textdomain' ),
        'view_items'            => __( 'View Awards', 'textdomain' ),
        'search_items'          => __( 'Search Award', 'textdomain' ),
        'not_found'             => __( 'Not found', 'textdomain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'textdomain' ),
        'featured_image'        => __( 'Award Image', 'textdomain' ),
        'set_featured_image'    => __( 'Set award image', 'textdomain' ),
        'remove_featured_image' => __( 'Remove award image', 'textdomain' ),
        'use_featured_image'    => __( 'Use as award image', 'textdomain' ),
        'insert_into_item'      => __( 'Insert into award', 'textdomain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this award', 'textdomain' ),
        'items_list'            => __( 'Awards list', 'textdomain' ),
        'items_list_navigation' => __( 'Awards list navigation', 'textdomain' ),
        'filter_items_list'     => __( 'Filter awards list', 'textdomain' ),
    );
    
    $args = array(
        'label'                 => __( 'Award', 'textdomain' ),
        'description'           => __( 'Company awards and achievements', 'textdomain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail'),
        'taxonomies'            => array( 'award_category', 'award_year', 'award_tags' ),
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
        'rewrite'               => array( 'slug' => 'awards' ),
    );
    
    register_post_type( 'awards', $args );

}
add_action( 'init', 'create_awards_cpt', 0 );

// Register Custom Taxonomy Award Category
function create_award_category_taxonomy() {

    $labels = array(
        'name'                       => _x( 'Award Categories', 'Taxonomy General Name', 'textdomain' ),
        'singular_name'              => _x( 'Award Category', 'Taxonomy Singular Name', 'textdomain' ),
        'menu_name'                  => __( 'Categories', 'textdomain' ),
        'all_items'                  => __( 'All Categories', 'textdomain' ),
        'parent_item'                => __( 'Parent Category', 'textdomain' ),
        'parent_item_colon'          => __( 'Parent Category:', 'textdomain' ),
        'new_item_name'              => __( 'New Category Name', 'textdomain' ),
        'add_new_item'               => __( 'Add New Category', 'textdomain' ),
        'edit_item'                  => __( 'Edit Category', 'textdomain' ),
        'update_item'                => __( 'Update Category', 'textdomain' ),
        'view_item'                  => __( 'View Category', 'textdomain' ),
        'separate_items_with_commas' => __( 'Separate categories with commas', 'textdomain' ),
        'add_or_remove_items'        => __( 'Add or remove categories', 'textdomain' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'textdomain' ),
        'popular_items'              => __( 'Popular Categories', 'textdomain' ),
        'search_items'               => __( 'Search Categories', 'textdomain' ),
        'not_found'                  => __( 'Not Found', 'textdomain' ),
        'no_terms'                   => __( 'No categories', 'textdomain' ),
        'items_list'                 => __( 'Categories list', 'textdomain' ),
        'items_list_navigation'      => __( 'Categories list navigation', 'textdomain' ),
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
        'rewrite'                    => array( 'slug' => 'award-category' ),
    );
    
    register_taxonomy( 'award_category', array( 'awards' ), $args );

}
add_action( 'init', 'create_award_category_taxonomy', 0 );

// Register Custom Taxonomy Award Year
function create_award_year_taxonomy() {

    $labels = array(
        'name'                       => _x( 'Award Years', 'Taxonomy General Name', 'textdomain' ),
        'singular_name'              => _x( 'Award Year', 'Taxonomy Singular Name', 'textdomain' ),
        'menu_name'                  => __( 'Years', 'textdomain' ),
        'all_items'                  => __( 'All Years', 'textdomain' ),
        'parent_item'                => __( 'Parent Year', 'textdomain' ),
        'parent_item_colon'          => __( 'Parent Year:', 'textdomain' ),
        'new_item_name'              => __( 'New Year', 'textdomain' ),
        'add_new_item'               => __( 'Add New Year', 'textdomain' ),
        'edit_item'                  => __( 'Edit Year', 'textdomain' ),
        'update_item'                => __( 'Update Year', 'textdomain' ),
        'view_item'                  => __( 'View Year', 'textdomain' ),
        'separate_items_with_commas' => __( 'Separate years with commas', 'textdomain' ),
        'add_or_remove_items'        => __( 'Add or remove years', 'textdomain' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'textdomain' ),
        'popular_items'              => __( 'Popular Years', 'textdomain' ),
        'search_items'               => __( 'Search Years', 'textdomain' ),
        'not_found'                  => __( 'Not Found', 'textdomain' ),
        'no_terms'                   => __( 'No years', 'textdomain' ),
        'items_list'                 => __( 'Years list', 'textdomain' ),
        'items_list_navigation'      => __( 'Years list navigation', 'textdomain' ),
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
        'rewrite'                    => array( 'slug' => 'award-year' ),
    );
    
    register_taxonomy( 'award_year', array( 'awards' ), $args );

}
add_action( 'init', 'create_award_year_taxonomy', 0 );

// Register Custom Taxonomy Award Tags
function create_award_tags_taxonomy() {

    $labels = array(
        'name'                       => _x( 'Award Tags', 'Taxonomy General Name', 'textdomain' ),
        'singular_name'              => _x( 'Award Tag', 'Taxonomy Singular Name', 'textdomain' ),
        'menu_name'                  => __( 'Tags', 'textdomain' ),
        'all_items'                  => __( 'All Tags', 'textdomain' ),
        'parent_item'                => __( 'Parent Tag', 'textdomain' ),
        'parent_item_colon'          => __( 'Parent Tag:', 'textdomain' ),
        'new_item_name'              => __( 'New Tag Name', 'textdomain' ),
        'add_new_item'               => __( 'Add New Tag', 'textdomain' ),
        'edit_item'                  => __( 'Edit Tag', 'textdomain' ),
        'update_item'                => __( 'Update Tag', 'textdomain' ),
        'view_item'                  => __( 'View Tag', 'textdomain' ),
        'separate_items_with_commas' => __( 'Separate tags with commas', 'textdomain' ),
        'add_or_remove_items'        => __( 'Add or remove tags', 'textdomain' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'textdomain' ),
        'popular_items'              => __( 'Popular Tags', 'textdomain' ),
        'search_items'               => __( 'Search Tags', 'textdomain' ),
        'not_found'                  => __( 'Not Found', 'textdomain' ),
        'no_terms'                   => __( 'No tags', 'textdomain' ),
        'items_list'                 => __( 'Tags list', 'textdomain' ),
        'items_list_navigation'      => __( 'Tags list navigation', 'textdomain' ),
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
        'rewrite'                    => array( 'slug' => 'award-tags' ),
    );
    
    register_taxonomy( 'award_tags', array( 'awards' ), $args );

}
add_action( 'init', 'create_award_tags_taxonomy', 0 );

// Add custom meta boxes for Awards
function add_awards_meta_boxes() {
    add_meta_box(
        'award_details',
        __( 'Award Details', 'textdomain' ),
        'award_details_meta_box_callback',
        'awards',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'add_awards_meta_boxes' );


function award_details_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'award_details_nonce', 'award_details_nonce' );
    
    // Get existing values
    $award_organization = get_post_meta( $post->ID, '_award_organization', true );
    $award_date = get_post_meta( $post->ID, '_award_date', true );
    $award_url = get_post_meta( $post->ID, '_award_url', true );
    $award_staff = get_post_meta( $post->ID, '_award_staff', true );
    
    // Convert to array if it's not already
    if (!is_array($award_staff)) {
        $award_staff = $award_staff ? array($award_staff) : array();
    }
    
    // Get all staff members
    $staff_args = array(
        'post_type' => 'staff',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'post_status' => 'publish'
    );
    $staff_members = get_posts( $staff_args );
    ?>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <p>
                <label for="award_organization" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    <?php _e( 'Organization', 'textdomain' ); ?>
                </label>
                <input type="text" id="award_organization" name="award_organization" 
                       value="<?php echo esc_attr( $award_organization ); ?>" 
                       style="width: 100%; padding: 8px;" 
                       placeholder="<?php _e( 'e.g., International Design Awards', 'textdomain' ); ?>">
            </p>
            
            <p>
                <label for="award_date" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    <?php _e( 'Award Date', 'textdomain' ); ?>
                </label>
                <input type="date" id="award_date" name="award_date" 
                       value="<?php echo esc_attr( $award_date ); ?>" 
                       style="width: 100%; padding: 8px;">
            </p>
        </div>
        
        <div>
            <p>
                <label for="award_url" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    <?php _e( 'Award URL', 'textdomain' ); ?>
                </label>
                <input type="url" id="award_url" name="award_url" 
                       value="<?php echo esc_url( $award_url ); ?>" 
                       style="width: 100%; padding: 8px;" 
                       placeholder="<?php _e( 'https://example.com/award', 'textdomain' ); ?>">
            </p>
            
            <p>
                <label for="award_staff" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    <?php _e( 'Associated Staff Members', 'textdomain' ); ?>
                </label>
                <select id="award_staff" name="award_staff[]" multiple="multiple" style="width: 100%; padding: 8px; height: 120px;">
                    <?php foreach ( $staff_members as $staff ) : ?>
                        <option value="<?php echo $staff->ID; ?>" <?php selected( in_array($staff->ID, $award_staff), true ); ?>>
                            <?php echo esc_html( $staff->post_title ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="description"><?php _e( 'Hold Ctrl/Cmd to select multiple staff members', 'textdomain' ); ?></p>
            </p>
        </div>
    </div>
    
    <?php
}

// Save award meta box data with two-way synchronization
function save_award_meta_box_data( $post_id ) {
    // Check nonce
    if ( ! isset( $_POST['award_details_nonce'] ) || ! wp_verify_nonce( $_POST['award_details_nonce'], 'award_details_nonce' ) ) {
        return;
    }
    
    // Check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    // Get old staff IDs for comparison
    $old_staff_ids = get_post_meta( $post_id, '_award_staff', true );
    if (!is_array($old_staff_ids)) {
        $old_staff_ids = $old_staff_ids ? array($old_staff_ids) : array();
    }
    
    // Get new staff IDs
    $new_staff_ids = isset( $_POST['award_staff'] ) ? array_map( 'intval', $_POST['award_staff'] ) : array();
    
    // Save basic meta fields
    $fields = array(
        'award_organization' => 'sanitize_text_field',
        'award_date' => 'sanitize_text_field',
        'award_url' => 'esc_url_raw'
    );
    
    foreach ( $fields as $field => $sanitize_callback ) {
        if ( isset( $_POST[$field] ) ) {
            $value = call_user_func( $sanitize_callback, $_POST[$field] );
            update_post_meta( $post_id, '_' . $field, $value );
        } else {
            delete_post_meta( $post_id, '_' . $field );
        }
    }
    
    // Save staff IDs
    update_post_meta( $post_id, '_award_staff', $new_staff_ids );
    
    // Two-way synchronization with Staff post type
    $removed_staff_ids = array_diff( $old_staff_ids, $new_staff_ids );
    $added_staff_ids = array_diff( $new_staff_ids, $old_staff_ids );
    
    // Remove this award from old staff members' awards lists
    foreach ( $removed_staff_ids as $staff_id ) {
        $staff_awards = get_post_meta( $staff_id, '_staff_awards', true );
        if ( is_array( $staff_awards ) ) {
            $staff_awards = array_diff( $staff_awards, array( $post_id ) );
            update_post_meta( $staff_id, '_staff_awards', $staff_awards );
        }
    }
    
    // Add this award to new staff members' awards lists
    foreach ( $added_staff_ids as $staff_id ) {
        $staff_awards = get_post_meta( $staff_id, '_staff_awards', true );
        if ( ! is_array( $staff_awards ) ) {
            $staff_awards = array();
        }
        if ( ! in_array( $post_id, $staff_awards ) ) {
            $staff_awards[] = $post_id;
            update_post_meta( $staff_id, '_staff_awards', $staff_awards );
        }
    }
}
add_action( 'save_post_awards', 'save_award_meta_box_data' );

// Also update when staff awards are changed (from staff side)
function sync_staff_awards_to_award( $staff_id, $awards ) {
    // First, remove this staff from all awards
    $all_awards = get_posts( array(
        'post_type' => 'awards',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ) );
    
    foreach ( $all_awards as $award_id ) {
        $award_staff = get_post_meta( $award_id, '_award_staff', true );
        if (!is_array($award_staff)) {
            $award_staff = $award_staff ? array($award_staff) : array();
        }
        
        if (in_array($staff_id, $award_staff)) {
            $award_staff = array_diff($award_staff, array($staff_id));
            update_post_meta( $award_id, '_award_staff', $award_staff );
        }
    }
    
    // Then set this staff for the selected awards
    foreach ( $awards as $award_id ) {
        $award_staff = get_post_meta( $award_id, '_award_staff', true );
        if (!is_array($award_staff)) {
            $award_staff = $award_staff ? array($award_staff) : array();
        }
        
        if (!in_array($staff_id, $award_staff)) {
            $award_staff[] = $staff_id;
            update_post_meta( $award_id, '_award_staff', $award_staff );
        }
    }
}

// Hook into staff save to maintain two-way sync
function on_staff_save_sync_awards( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( get_post_type( $post_id ) !== 'staff' ) {
        return;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    if ( isset( $_POST['staff_awards'] ) ) {
        $awards = array_map( 'intval', $_POST['staff_awards'] );
        sync_staff_awards_to_award( $post_id, $awards );
    } else {
        // If no awards selected, remove all associations
        sync_staff_awards_to_award( $post_id, array() );
    }
}
add_action( 'save_post_staff', 'on_staff_save_sync_awards' );

// Update custom column display for multiple staff
function display_awards_custom_columns( $column, $post_id ) {
    switch ( $column ) {
        case 'award_organization':
            echo get_post_meta( $post_id, '_award_organization', true ) ?: '—';
            break;
            
        case 'award_staff':
            $staff_ids = get_post_meta( $post_id, '_award_staff', true );
            if (!is_array($staff_ids)) {
                $staff_ids = $staff_ids ? array($staff_ids) : array();
            }
            
            if ( !empty($staff_ids) ) {
                $staff_names = array();
                foreach ( $staff_ids as $staff_id ) {
                    $staff = get_post( $staff_id );
                    if ( $staff ) {
                        $staff_names[] = '<a href="' . get_edit_post_link( $staff_id ) . '">' . esc_html( $staff->post_title ) . '</a>';
                    }
                }
                echo implode( ', ', $staff_names );
            } else {
                echo '—';
            }
            break;
            
        case 'award_year':
            $years = get_the_terms( $post_id, 'award_year' );
            if ( $years && ! is_wp_error( $years ) ) {
                $year_names = array();
                foreach ( $years as $year ) {
                    $year_names[] = $year->name;
                }
                echo implode( ', ', $year_names );
            } else {
                echo '—';
            }
            break;
            
        case 'award_category':
            $categories = get_the_terms( $post_id, 'award_category' );
            if ( $categories && ! is_wp_error( $categories ) ) {
                $category_names = array();
                foreach ( $categories as $category ) {
                    $category_names[] = $category->name;
                }
                echo implode( ', ', $category_names );
            } else {
                echo '—';
            }
            break;
    }
}

// Enqueue Select2 for better UX
function codeweber_enqueue_select2() {
    $screen = get_current_screen();
    
    // Load only on awards and staff edit pages
    if ($screen->post_type === 'awards' || $screen->post_type === 'staff') {
        wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), '4.1.0-rc.0', true);
        wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '4.1.0-rc.0');

        wp_add_inline_script('select2', '
            jQuery(document).ready(function($) {
                // Initialize Select2 for staff awards
                $("#staff_awards").select2({
                    placeholder: "' . __('Select awards...', 'codeweber') . '",
                    allowClear: true
                });
                
                // Initialize Select2 for award staff
                $("#award_staff").select2({
                    placeholder: "' . __('Select staff members...', 'codeweber') . '",
                    allowClear: true
                });
            });
        ');
    }
}
add_action('admin_enqueue_scripts', 'codeweber_enqueue_select2');




add_filter('use_block_editor_for_post_type', 'disable_gutenberg_for_awards', 10, 2);
function disable_gutenberg_for_awards($current_status, $post_type)
{
    if ($post_type === 'awards') {
        return false;
    }
    return $current_status;
}