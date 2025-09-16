<?php
//--------------------------------
//STAFF
//--------------------------------

function codeweber_add_staff_additional_meta_boxes()
{
   add_meta_box(
      'staff_additional_details',
      __('Additional Information', 'codeweber'),
      'codeweber_staff_additional_meta_box_callback',
      'staff',
      'normal',
      'default'
   );
}
add_action('add_meta_boxes', 'codeweber_add_staff_additional_meta_boxes');

/**
 * Callback function for displaying the second metabox
 */
function codeweber_staff_additional_meta_box_callback($post)
{
   // Add nonce for security
   wp_nonce_field('staff_additional_meta_box', 'staff_additional_meta_box_nonce');

   // Get existing field values
   $full_position = get_post_meta($post->ID, '_staff_full_position', true);
   $regions = get_post_meta($post->ID, '_staff_regions', true);
   $short_description = get_post_meta($post->ID, '_staff_short_description', true);
   $language_skills = get_post_meta($post->ID, '_staff_language_skills', true);

   // Get all awards
   $awards = get_posts(array(
      'post_type' => 'awards',
      'numberposts' => -1,
      'orderby' => 'title',
      'order' => 'ASC',
      'post_status' => 'publish'
   ));

   // Get selected awards for this staff member
   $selected_awards = get_post_meta($post->ID, '_staff_awards', true);
   if (!is_array($selected_awards)) {
      $selected_awards = array();
   }
?>

   <div style="display: grid; gap: 16px;">
      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: center;">
         <label for="staff_full_position"><strong><?php _e('Full Position Title:', 'codeweber'); ?></strong></label>
         <input type="text" id="staff_full_position" name="staff_full_position" value="<?php echo esc_attr($full_position); ?>"
            placeholder="<?php esc_attr_e('For example: Senior Financial Analyst and Investment Advisor', 'codeweber'); ?>" style="width: 100%; padding: 8px;">
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: center;">
         <label for="staff_regions"><strong><?php _e('Regions:', 'codeweber'); ?></strong></label>
         <input type="text" id="staff_regions" name="staff_regions" value="<?php echo esc_attr($regions); ?>"
            placeholder="<?php esc_attr_e('For example: Europe, Asia, North America', 'codeweber'); ?>" style="width: 100%; padding: 8px;">
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: start;">
         <label for="staff_short_description"><strong><?php _e('Short Description:', 'codeweber'); ?></strong></label>
         <textarea id="staff_short_description" name="staff_short_description"
            placeholder="<?php esc_attr_e('Brief information about the employee', 'codeweber'); ?>"
            style="width: 100%; padding: 8px; min-height: 80px; resize: vertical;"><?php echo esc_textarea($short_description); ?></textarea>
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: start;">
         <label for="staff_language_skills"><strong><?php _e('Language Skills:', 'codeweber'); ?></strong></label>
         <textarea id="staff_language_skills" name="staff_language_skills"
            placeholder="<?php esc_attr_e('For example: English (C1), German (B2), French (A2)', 'codeweber'); ?>"
            style="width: 100%; padding: 8px; min-height: 60px; resize: vertical;"><?php echo esc_textarea($language_skills); ?></textarea>
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: start;">
         <label for="staff_awards"><strong><?php _e('Awards:', 'codeweber'); ?></strong></label>
         <select id="staff_awards" name="staff_awards[]" multiple="multiple" style="width: 100%; padding: 8px; min-height: 120px;">
            <?php foreach ($awards as $award) : ?>
               <option value="<?php echo esc_attr($award->ID); ?>"
                  <?php selected(in_array($award->ID, $selected_awards), true); ?>>
                  <?php echo esc_html($award->post_title); ?>
               </option>
            <?php endforeach; ?>
         </select>
         <p class="description" style="margin-top: 5px; font-size: 12px; color: #666;">
            <?php _e('Hold Ctrl/Cmd to select multiple awards', 'codeweber'); ?>
         </p>
      </div>
   </div>
<?php
}

/**
 * Save data from the second metabox
 */
function codeweber_save_staff_additional_meta($post_id)
{
   // Check nonce
   if (!isset($_POST['staff_additional_meta_box_nonce']) || !wp_verify_nonce($_POST['staff_additional_meta_box_nonce'], 'staff_additional_meta_box')) {
      return;
   }

   // Check user permissions
   if (!current_user_can('edit_post', $post_id)) {
      return;
   }

   // Check autosave
   if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
   }

   // Save fields from the second metabox
   $additional_fields = ['staff_full_position', 'staff_regions', 'staff_short_description', 'staff_language_skills'];

   foreach ($additional_fields as $field) {
      if (isset($_POST[$field])) {
         if ($field === 'staff_short_description' || $field === 'staff_language_skills') {
            // Use sanitize_textarea_field for text areas
            update_post_meta($post_id, '_' . $field, sanitize_textarea_field($_POST[$field]));
         } else {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
         }
      }
   }

   // Save awards (multiple selection)
   if (isset($_POST['staff_awards'])) {
      $awards = array_map('intval', $_POST['staff_awards']); // Sanitize as integers
      update_post_meta($post_id, '_staff_awards', $awards);
   } else {
      // If no awards selected, save empty array
      update_post_meta($post_id, '_staff_awards', array());
   }
}
add_action('save_post_staff', 'codeweber_save_staff_additional_meta');

/**
 * Add columns to admin for the second block
 */
function codeweber_add_staff_additional_admin_columns($columns)
{
   // Insert columns after existing ones
   $new_columns = [];

   foreach ($columns as $key => $value) {
      $new_columns[$key] = $value;

      // Insert after "Phone" column
      if ($key === 'staff_phone') {
         $new_columns['staff_full_position'] = __('Full Position', 'codeweber');
         $new_columns['staff_regions'] = __('Regions', 'codeweber');
         $new_columns['staff_language_skills'] = __('Languages', 'codeweber');
         $new_columns['staff_awards'] = __('Awards', 'codeweber');
      }
   }

   return $new_columns;
}
add_filter('manage_staff_posts_columns', 'codeweber_add_staff_additional_admin_columns');

/**
 * Fill new columns with data
 */
function codeweber_fill_staff_additional_admin_columns($column, $post_id)
{
   switch ($column) {
      case 'staff_full_position':
         echo esc_html(get_post_meta($post_id, '_staff_full_position', true));
         break;
      case 'staff_regions':
         echo esc_html(get_post_meta($post_id, '_staff_regions', true));
         break;
      case 'staff_language_skills':
         $languages = get_post_meta($post_id, '_staff_language_skills', true);
         echo esc_html(mb_strimwidth($languages, 0, 50, '...')); // Trim long text
         break;
      case 'staff_awards':
         $selected_awards = get_post_meta($post_id, '_staff_awards', true);
         if (!empty($selected_awards) && is_array($selected_awards)) {
            $award_titles = array();
            foreach ($selected_awards as $award_id) {
               $award_title = get_the_title($award_id);
               if ($award_title) {
                  $award_titles[] = $award_title;
               }
            }
            echo esc_html(implode(', ', $award_titles));
         } else {
            echo '—';
         }
         break;
   }
}
add_action('manage_staff_posts_custom_column', 'codeweber_fill_staff_additional_admin_columns', 10, 2);

/**
 * Make new columns sortable
 */
function codeweber_make_staff_additional_columns_sortable($columns)
{
   $columns['staff_full_position'] = 'staff_full_position';
   $columns['staff_regions'] = 'staff_regions';
   $columns['staff_language_skills'] = 'staff_language_skills';
   $columns['staff_awards'] = 'staff_awards';
   return $columns;
}
add_filter('manage_edit-staff_sortable_columns', 'codeweber_make_staff_additional_columns_sortable');


//--------------------------------
//SERVICES
//--------------------------------

add_action('service_category_add_form_fields', 'add_service_category_fields', 10, 2);
add_action('service_category_edit_form_fields', 'edit_service_category_fields', 10, 2);
add_action('created_service_category', 'save_service_category_meta', 10, 2);
add_action('edited_service_category', 'save_service_category_meta', 10, 2);

// Add fields when creating term
function add_service_category_fields($taxonomy)
{
?>
   <div class="form-field term-color-wrap">
      <label for="service_category_color"><?php _e('Category Color', 'codeweber'); ?></label>

      <!-- Color preview box for add form -->
      <div class="color-preview-container">
         <span class="color-preview-label"><?php _e('Selected Color Preview:', 'codeweber'); ?></span>
         <div class="color-preview-box primary"></div>
      </div>

      <select name="service_category_color" id="service_category_color" class="color-select">
         <option value="primary" selected><?php _e('Primary (#FEBE10)', 'codeweber'); ?></option>
         <option value="vintage-burgundy"><?php _e('Vintage Burgundy (#893B41)', 'codeweber'); ?></option>
         <option value="slate-gray"><?php _e('Slate Gray (#86909B)', 'codeweber'); ?></option>
         <option value="olive-green"><?php _e('Olive Green (#738D50)', 'codeweber'); ?></option>
         <option value="muted-teal"><?php _e('Muted Teal (#47878F)', 'codeweber'); ?></option>
         <option value="charcoal-blue"><?php _e('Charcoal Blue (#303C42)', 'codeweber'); ?></option>
         <option value="dusty-navy"><?php _e('Dusty Navy (#3D5567)', 'codeweber'); ?></option>
      </select>
      <p><?php _e('Select color for service category display', 'codeweber'); ?></p>
   </div>

   <div class="form-field term-alt-title-wrap">
      <label for="service_category_alt_title"><?php _e('Alternative Title', 'codeweber'); ?></label>
      <input type="text" name="service_category_alt_title" id="service_category_alt_title" value="">
      <p><?php _e('Alternative title for display purposes', 'codweber'); ?></p>
   </div>

   <div class="form-field term-order-wrap">
      <label for="service_category_order"><?php _e('Order Number', 'codeweber'); ?></label>
      <input type="number" name="service_category_order" id="service_category_order" value="0" min="0">
      <p><?php _e('Set order for category display (lower numbers first)', 'codeweber'); ?></p>
   </div>
<?php
}

function edit_service_category_fields($term, $taxonomy)
{
   $color = get_term_meta($term->term_id, 'service_category_color', true);
   $alt_title = get_term_meta($term->term_id, 'service_category_alt_title', true);
   $order = get_term_meta($term->term_id, 'service_category_order', true);
   $order = $order ? $order : 0;
?>
   <tr class="form-field term-color-wrap">
      <th scope="row">
         <label for="service_category_color"><?php _e('Category Color', 'codeweber'); ?></label>
      </th>
      <td>
         <!-- Color preview box -->
         <div class="color-preview-container">
            <span class="color-preview-label"><?php _e('Selected Color Preview:', 'codeweber'); ?></span>
            <div class="color-preview-box <?php echo esc_attr($color ? $color : ''); ?>"></div>
         </div>

         <select name="service_category_color" id="service_category_color" class="color-select">
            <option value="primary" <?php selected($color, 'primary'); ?>><?php _e('Primary (#FEBE10)', 'codeweber'); ?></option>
            <option value="vintage-burgundy" <?php selected($color, 'vintage-burgundy'); ?>><?php _e('Vintage Burgundy (#893B41)', 'codeweber'); ?></option>
            <option value="slate-gray" <?php selected($color, 'slate-gray'); ?>><?php _e('Slate Gray (#86909B)', 'codeweber'); ?></option>
            <option value="olive-green" <?php selected($color, 'olive-green'); ?>><?php _e('Olive Green (#738D50)', 'codeweber'); ?></option>
            <option value="muted-teal" <?php selected($color, 'muted-teal'); ?>><?php _e('Muted Teal (#47878F)', 'codeweber'); ?></option>
            <option value="charcoal-blue" <?php selected($color, 'charcoal-blue'); ?>><?php _e('Charcoal Blue (#303C42)', 'codeweber'); ?></option>
            <option value="dusty-navy" <?php selected($color, 'dusty-navy'); ?>><?php _e('Dusty Navy (#3D5567)', 'codeweber'); ?></option>
         </select>
         <p class="description"><?php _e('Select color for service category display', 'codeweber'); ?></p>
      </td>
   </tr>

   <tr class="form-field term-alt-title-wrap">
      <th scope="row">
         <label for="service_category_alt_title"><?php _e('Alternative Title', 'codeweber'); ?></label>
      </th>
      <td>
         <input type="text" name="service_category_alt_title" id="service_category_alt_title" value="<?php echo esc_attr($alt_title); ?>">
         <p class="description"><?php _e('Alternative title for display purposes', 'codeweber'); ?></p>
      </td>
   </tr>

   <tr class="form-field term-order-wrap">
      <th scope="row">
         <label for="service_category_order"><?php _e('Order Number', 'codeweber'); ?></label>
      </th>
      <td>
         <input type="number" name="service_category_order" id="service_category_order" value="<?php echo esc_attr($order); ?>" min="0">
         <p class="description"><?php _e('Set order for category display (lower numbers first)', 'codeweber'); ?></p>
      </td>
   </tr>
<?php
}

function save_service_category_meta($term_id)
{
   if (isset($_POST['service_category_color'])) {
      update_term_meta(
         $term_id,
         'service_category_color',
         sanitize_text_field($_POST['service_category_color'])
      );
   }

   if (isset($_POST['service_category_alt_title'])) {
      update_term_meta(
         $term_id,
         'service_category_alt_title',
         sanitize_text_field($_POST['service_category_alt_title'])
      );
   }

   if (isset($_POST['service_category_order'])) {
      update_term_meta(
         $term_id,
         'service_category_order',
         intval($_POST['service_category_order'])
      );
   }
}

add_action('admin_enqueue_scripts', 'service_category_admin_styles_scripts');
function service_category_admin_styles_scripts()
{
   $screen = get_current_screen();

   if ($screen->taxonomy === 'service_category') {
      // Styles
      echo '
        <style>
        .color-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
            max-width: 250px;
        }
        
        .color-option-preview {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border: 1px solid #ddd;
            vertical-align: middle;
            border-radius: 3px;
        }
        
        .color-option-preview.primary { background-color: #FEBE10; }
        .color-option-preview.vintage-burgundy { background-color: #893B41; }
        .color-option-preview.slate-gray { background-color: #86909B; }
        .color-option-preview.olive-green { background-color: #738D50; }
        .color-option-preview.muted-teal { background-color: #47878F; }
        .color-option-preview.charcoal-blue { background-color: #303C42; }
        .color-option-preview.dusty-navy { background-color: #3D5567; }
        
        /* Styles for color preview above select */
        .color-preview-container {
            margin-bottom: 8px;
        }
        
        .color-preview-label {
            display: block;
            margin-bottom: 4px;
            font-weight: 600;
            color: #1d2327;
        }
        
        .color-preview-box {
            width: 40px;
            height: 40px;
            border: 2px solid #ddd;
            border-radius: 4px;
            margin-bottom: 8px;
        }
        
        .color-preview-box.primary { background-color: #FEBE10; }
        .color-preview-box.vintage-burgundy { background-color: #893B41; }
        .color-preview-box.slate-gray { background-color: #86909B; }
        .color-preview-box.olive-green { background-color: #738D50; }
        .color-preview-box.muted-teal { background-color: #47878F; }
        .color-preview-box.charcoal-blue { background-color: #303C42; }
        .color-preview-box.dusty-navy { background-color: #3D5567; }
        
        .color-select-wrapper {
            position: relative;
            display: inline-block;
            max-width: 250px;
            width: 100%;
        }
        </style>
        ';

      // JavaScript for color preview
      echo '
        <script>
        jQuery(document).ready(function($) {
            // Function to update color preview
            function updateColorPreview() {
                $(".color-select").each(function() {
                    var $select = $(this);
                    var selectedValue = $select.val();
                    var $previewBox = $select.closest("td").find(".color-preview-box");
                    
                    if ($previewBox.length) {
                        // Remove all color classes
                        $previewBox.removeClass("primary vintage-burgundy slate-gray olive-green muted-teal charcoal-blue dusty-navy");
                        
                        // Add current color class
                        if (selectedValue) {
                            $previewBox.addClass(selectedValue);
                        }
                    }
                });
            }
            
            // Initialize color preview
            updateColorPreview();
            
            // Update preview on select change
            $(".color-select").on("change", updateColorPreview);
            
            // Also update when form is loaded (for edit pages)
            $(document).on("ajaxComplete", function() {
                setTimeout(updateColorPreview, 100);
            });
        });
        </script>
        ';
   }
}

add_filter('manage_edit-service_category_columns', 'add_service_category_columns');
add_filter('manage_service_category_custom_column', 'show_service_category_columns', 10, 3);

function add_service_category_columns($columns)
{
   $columns['color'] = __('Color', 'codeweber');
   $columns['alt_title'] = __('Alt. Title', 'codeweber');
   $columns['order'] = __('Order', 'codeweber');
   return $columns;
}

function show_service_category_columns($content, $column_name, $term_id)
{
   if ($column_name === 'color') {
      $color = get_term_meta($term_id, 'service_category_color', true);
      if ($color) {
         $color_names = [
            'primary' => __('Primary', 'codeweber'),
            'vintage-burgundy' => __('Vintage Burgundy', 'codeweber'),
            'slate-gray' => __('Slate Gray', 'codeweber'),
            'olive-green' => __('Olive Green', 'codeweber'),
            'muted-teal' => __('Muted Teal', 'codeweber'),
            'charcoal-blue' => __('Charcoal Blue', 'codeweber'),
            'dusty-navy' => __('Dusty Navy', 'codeweber')
         ];

         return '<div class="color-option-preview ' . esc_attr($color) . '"></div>' .
            '<span style="font-size: 12px;">' . $color_names[$color] . '</span>';
      }
   }

   if ($column_name === 'alt_title') {
      $alt_title = get_term_meta($term_id, 'service_category_alt_title', true);
      return $alt_title ? esc_html($alt_title) : '—';
   }

   if ($column_name === 'order') {
      $order = get_term_meta($term_id, 'service_category_order', true);
      return $order ? intval($order) : '0';
   }

   return $content;
}

// Function to get terms sorted by order number
function get_service_categories_ordered($args = array())
{
   $defaults = array(
      'taxonomy' => 'service_category',
      'hide_empty' => false,
      'meta_key' => 'service_category_order',
      'orderby' => 'meta_value_num',
      'order' => 'ASC'
   );

   $args = wp_parse_args($args, $defaults);
   return get_terms($args);
}

// Pre-get posts filter to replace title
add_action('pre_get_posts', 'service_category_alt_title_pre_get_posts');
function service_category_alt_title_pre_get_posts($query)
{
   if ($query->is_main_query() && $query->is_tax('service_category')) {
      $term = $query->get_queried_object();
      if ($term) {
         $alt_title = get_term_meta($term->term_id, 'service_category_alt_title', true);
         if (!empty($alt_title)) {
            // Set alternative title for use in theme
            add_filter('single_term_title', function ($title) use ($alt_title) {
               return $alt_title;
            });
         }
      }
   }
}

function get_service_category_color_html($term_id = null)
{
   // If term_id is not provided, try to get it automatically
   if ($term_id === null) {
      // For archives and taxonomies - get current term
      if (is_tax('service_category') || is_category()) {
         $term = get_queried_object();
         $term_id = $term->term_id;
      }
      // For single posts - get first term
      elseif (is_singular('services')) {
         $terms = get_the_terms(get_the_ID(), 'service_category');
         if ($terms && !is_wp_error($terms)) {
            $term_id = $terms[0]->term_id;
         }
      }
   }

   // If term_id still not found, return default
   if (!$term_id) {
      return '<div class="brand-square-md"></div>';
   }

   $color = get_term_meta($term_id, 'service_category_color', true);
   $color_class = $color ? 'bg-' . $color : '';

   return '<div class="brand-square-md ' . $color_class . '"></div>';
}

// Function to get alternative term title
function get_service_category_alt_title($term_id = null)
{
   if (!$term_id) {
      if (is_tax('service_category')) {
         $term = get_queried_object();
         $term_id = $term->term_id;
      }
   }

   if ($term_id) {
      $alt_title = get_term_meta($term_id, 'service_category_alt_title', true);
      return !empty($alt_title) ? $alt_title : '';
   }

   return '';
}
