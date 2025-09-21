<?php
//--------------------------------
//PARTNERS
//--------------------------------

function codeweber_add_partners_additional_meta_boxes()
{
   add_meta_box(
      'partners_additional_details',
      __('Additional Information', 'horizons'),
      'codeweber_partners_additional_meta_box_callback',
      'partners',
      'normal',
      'default'
   );
}
add_action('add_meta_boxes', 'codeweber_add_partners_additional_meta_boxes');

/**
 * Callback function for displaying the second metabox
 */
function codeweber_partners_additional_meta_box_callback($post)
{
   // Add nonce for security
   wp_nonce_field('partners_additional_meta_box', 'partners_additional_meta_box_nonce');

   // ДОБАВЛЕННЫЕ ПОЛЯ
   $position = get_post_meta($post->ID, '_partners_position', true);
   $name = get_post_meta($post->ID, '_partners_name', true);
   $surname = get_post_meta($post->ID, '_partners_surname', true);
   $email = get_post_meta($post->ID, '_partners_email', true);
   $phone = get_post_meta($post->ID, '_partners_phone', true); // Исправлено: было _partnersr_phone

   // Get existing field values
   $full_position = get_post_meta($post->ID, '_partners_full_position', true);
   $regions = get_post_meta($post->ID, '_partners_regions', true);
   $location = get_post_meta($post->ID, '_partners_location', true);
   $short_description = get_post_meta($post->ID, '_partners_short_description', true);
   $language_skills = get_post_meta($post->ID, '_partners_language_skills', true);

   // Get all awards
   $awards = get_posts(array(
      'post_type' => 'awards',
      'numberposts' => -1,
      'orderby' => 'title',
      'order' => 'ASC',
      'post_status' => 'publish'
   ));

   // Get selected awards for this partners member
   $selected_awards = get_post_meta($post->ID, '_partners_awards', true);
   if (!is_array($selected_awards)) {
      $selected_awards = array();
   }
?>

   <div style="display: grid; gap: 16px;">
      <!-- ДОБАВЛЕННЫЕ ПОЛЯ -->
      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: center;">
         <label for="partners_position"><strong><?php _e('Position:', 'horizons'); ?></strong></label>
         <input type="text" id="partners_position" name="partners_position" value="<?php echo esc_attr($position); ?>" style="width: 100%; padding: 8px;">
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: center;">
         <label for="partners_name"><strong><?php _e('Name:', 'horizons'); ?></strong></label>
         <input type="text" id="partners_name" name="partners_name" value="<?php echo esc_attr($name); ?>" style="width: 100%; padding: 8px;">
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: center;">
         <label for="partners_surname"><strong><?php _e('Surname:', 'horizons'); ?></strong></label>
         <input type="text" id="partners_surname" name="partners_surname" value="<?php echo esc_attr($surname); ?>" style="width: 100%; padding: 8px;">
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: center;">
         <label for="partners_email"><strong><?php _e('E-Mail:', 'horizons'); ?></strong></label>
         <input type="email" id="partners_email" name="partners_email" value="<?php echo esc_attr($email); ?>" style="width: 100%; padding: 8px;">
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: center;">
         <label for="partners_phone"><strong><?php _e('Phone:', 'horizons'); ?></strong></label>
         <input type="tel" id="partners_phone" name="partners_phone" value="<?php echo esc_attr($phone); ?>" style="width: 100%; padding: 8px;">
      </div>

      <!-- СУЩЕСТВУЮЩИЕ ПОЛЯ -->
      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: center;">
         <label for="partners_full_position"><strong><?php _e('Full Position Title:', 'horizons'); ?></strong></label>
         <input type="text" id="partners_full_position" name="partners_full_position" value="<?php echo esc_attr($full_position); ?>"
            placeholder="<?php esc_attr_e('For example: Senior Financial Analyst and Investment Advisor', 'horizons'); ?>" style="width: 100%; padding: 8px;">
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: center;">
         <label for="partners_regions"><strong><?php _e('Regions:', 'horizons'); ?></strong></label>
         <input type="text" id="partners_regions" name="partners_regions" value="<?php echo esc_attr($regions); ?>"
            placeholder="<?php esc_attr_e('For example: Europe, Asia, North America', 'horizons'); ?>" style="width: 100%; padding: 8px;">
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: center;">
         <label for="partners_location"><strong><?php _e('Location:', 'horizons'); ?></strong></label>
         <input type="text" id="partners_location" name="partners_location" value="<?php echo esc_attr($location); ?>"
            placeholder="<?php esc_attr_e('For example: New York, USA', 'horizons'); ?>" style="width: 100%; padding: 8px;">
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: start;">
         <label for="partners_short_description"><strong><?php _e('Short Description:', 'horizons'); ?></strong></label>
         <textarea id="partners_short_description" name="partners_short_description"
            placeholder="<?php esc_attr_e('Brief information about the employee', 'horizons'); ?>"
            style="width: 100%; padding: 8px; min-height: 80px; resize: vertical;"><?php echo esc_textarea($short_description); ?></textarea>
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: start;">
         <label for="partners_language_skills"><strong><?php _e('Language Skills:', 'horizons'); ?></strong></label>
         <textarea id="partners_language_skills" name="partners_language_skills"
            placeholder="<?php esc_attr_e('For example: English (C1), German (B2), French (A2)', 'horizons'); ?>"
            style="width: 100%; padding: 8px; min-height: 60px; resize: vertical;"><?php echo esc_textarea($language_skills); ?></textarea>
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: start;">
         <label for="partners_awards"><strong><?php _e('Awards:', 'horizons'); ?></strong></label>
         <select id="partners_awards" name="partners_awards[]" multiple="multiple" style="width: 100%; padding: 8px; min-height: 120px;">
            <?php foreach ($awards as $award) : ?>
               <option value="<?php echo esc_attr($award->ID); ?>"
                  <?php selected(in_array($award->ID, $selected_awards), true); ?>>
                  <?php echo esc_html($award->post_title); ?>
               </option>
            <?php endforeach; ?>
         </select>
         <p class="description" style="margin-top: 5px; font-size: 12px; color: #666;">
            <?php _e('Hold Ctrl/Cmd to select multiple awards', 'horizons'); ?>
         </p>
      </div>
   </div>
<?php
}


/**
 * Save data from the second metabox
 */
function codeweber_save_partners_additional_meta($post_id)
{
   // Check nonce
   if (!isset($_POST['partners_additional_meta_box_nonce']) || !wp_verify_nonce($_POST['partners_additional_meta_box_nonce'], 'partners_additional_meta_box')) {
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

   // Save ALL fields with partners_ prefix
   $additional_fields = [
      'partners_position',
      'partners_name',
      'partners_surname',
      'partners_email',
      'partners_phone',
      'partners_full_position',
      'partners_regions',
      'partners_location',
      'partners_short_description',
      'partners_language_skills'
   ];

   foreach ($additional_fields as $field) {
      if (isset($_POST[$field])) {
         if ($field === 'partners_short_description' || $field === 'partners_language_skills') {
            // Use sanitize_textarea_field for text areas
            update_post_meta($post_id, '_' . $field, sanitize_textarea_field($_POST[$field]));
         } else {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
         }
      } else {
         // Если поле не передано, очищаем метаданные
         delete_post_meta($post_id, '_' . $field);
      }
   }

   // Save awards (multiple selection)
   if (isset($_POST['partners_awards'])) {
      $awards = array_map('intval', $_POST['partners_awards']);
      update_post_meta($post_id, '_partners_awards', $awards);
   } else {
      update_post_meta($post_id, '_partners_awards', array());
   }
}
add_action('save_post_partners', 'codeweber_save_partners_additional_meta');


//--------------------------------
//AWARDS
//--------------------------------


// Add custom meta boxes for Awards
function add_awards_meta_boxes()
{
   add_meta_box(
      'award_details',
      __('Award Details', 'horizons'),
      'award_details_meta_box_callback',
      'awards',
      'normal',
      'high'
   );
}
add_action('add_meta_boxes', 'add_awards_meta_boxes');


function award_details_meta_box_callback($post)
{
   // Add nonce for security
   wp_nonce_field('award_details_nonce', 'award_details_nonce');

   // Get existing values
   $award_organization = get_post_meta($post->ID, '_award_organization', true);
   $award_date = get_post_meta($post->ID, '_award_date', true);
   $award_url = get_post_meta($post->ID, '_award_url', true);
   $award_partners = get_post_meta($post->ID, '_award_partners', true);

   // Convert to array if it's not already
   if (!is_array($award_partners)) {
      $award_partners = $award_partners ? array($award_partners) : array();
   }

   // Get all partners members
   $partners_args = array(
      'post_type' => 'partners',
      'posts_per_page' => -1,
      'orderby' => 'title',
      'order' => 'ASC',
      'post_status' => 'publish'
   );
   $partners_members = get_posts($partners_args);
?>

   <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
      <div>
         <p>
            <label for="award_organization" style="display: block; margin-bottom: 5px; font-weight: bold;">
               <?php _e('Organization', 'horizons'); ?>
            </label>
            <input type="text" id="award_organization" name="award_organization"
               value="<?php echo esc_attr($award_organization); ?>"
               style="width: 100%; padding: 8px;"
               placeholder="<?php _e('e.g., International Design Awards', 'horizons'); ?>">
         </p>

         <p>
            <label for="award_date" style="display: block; margin-bottom: 5px; font-weight: bold;">
               <?php _e('Award Date', 'horizons'); ?>
            </label>
            <input type="date" id="award_date" name="award_date"
               value="<?php echo esc_attr($award_date); ?>"
               style="width: 100%; padding: 8px;">
         </p>
      </div>

      <div>
         <p>
            <label for="award_url" style="display: block; margin-bottom: 5px; font-weight: bold;">
               <?php _e('Award URL', 'horizons'); ?>
            </label>
            <input type="url" id="award_url" name="award_url"
               value="<?php echo esc_url($award_url); ?>"
               style="width: 100%; padding: 8px;"
               placeholder="<?php _e('https://example.com/award', 'horizons'); ?>">
         </p>

         <p>
            <label for="award_partners" style="display: block; margin-bottom: 5px; font-weight: bold;">
               <?php _e('Associated partners Members', 'horizons'); ?>
            </label>
            <select id="award_partners" name="award_partners[]" multiple="multiple" style="width: 100%; padding: 8px; height: 120px;">
               <?php foreach ($partners_members as $partners) : ?>
                  <option value="<?php echo $partners->ID; ?>" <?php selected(in_array($partners->ID, $award_partners), true); ?>>
                     <?php echo esc_html($partners->post_title); ?>
                  </option>
               <?php endforeach; ?>
            </select>
         <p class="description"><?php _e('Hold Ctrl/Cmd to select multiple partners members', 'horizons'); ?></p>
         </p>
      </div>
   </div>

<?php
}

// Save award meta box data with two-way synchronization
function save_award_meta_box_data($post_id)
{
   // Check nonce
   if (! isset($_POST['award_details_nonce']) || ! wp_verify_nonce($_POST['award_details_nonce'], 'award_details_nonce')) {
      return;
   }

   // Check autosave
   if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
   }

   // Check permissions
   if (! current_user_can('edit_post', $post_id)) {
      return;
   }

   // Get old partners IDs for comparison
   $old_partners_ids = get_post_meta($post_id, '_award_partners', true);
   if (!is_array($old_partners_ids)) {
      $old_partners_ids = $old_partners_ids ? array($old_partners_ids) : array();
   }

   // Get new partners IDs
   $new_partners_ids = isset($_POST['award_partners']) ? array_map('intval', $_POST['award_partners']) : array();

   // Save basic meta fields
   $fields = array(
      'award_organization' => 'sanitize_text_field',
      'award_date' => 'sanitize_text_field',
      'award_url' => 'esc_url_raw'
   );

   foreach ($fields as $field => $sanitize_callback) {
      if (isset($_POST[$field])) {
         $value = call_user_func($sanitize_callback, $_POST[$field]);
         update_post_meta($post_id, '_' . $field, $value);
      } else {
         delete_post_meta($post_id, '_' . $field);
      }
   }

   // Save partners IDs
   update_post_meta($post_id, '_award_partners', $new_partners_ids);

   // Two-way synchronization with partners post type
   $removed_partners_ids = array_diff($old_partners_ids, $new_partners_ids);
   $added_partners_ids = array_diff($new_partners_ids, $old_partners_ids);

   // Remove this award from old partners members' awards lists
   foreach ($removed_partners_ids as $partners_id) {
      $partners_awards = get_post_meta($partners_id, '_partners_awards', true);
      if (is_array($partners_awards)) {
         $partners_awards = array_diff($partners_awards, array($post_id));
         update_post_meta($partners_id, '_partners_awards', $partners_awards);
      }
   }

   // Add this award to new partners members' awards lists
   foreach ($added_partners_ids as $partners_id) {
      $partners_awards = get_post_meta($partners_id, '_partners_awards', true);
      if (! is_array($partners_awards)) {
         $partners_awards = array();
      }
      if (! in_array($post_id, $partners_awards)) {
         $partners_awards[] = $post_id;
         update_post_meta($partners_id, '_partners_awards', $partners_awards);
      }
   }
}
add_action('save_post_awards', 'save_award_meta_box_data');

// Also update when partners awards are changed (from partners side)
function sync_partners_awards_to_award($partners_id, $awards)
{
   // First, remove this partners from all awards
   $all_awards = get_posts(array(
      'post_type' => 'awards',
      'posts_per_page' => -1,
      'fields' => 'ids'
   ));

   foreach ($all_awards as $award_id) {
      $award_partners = get_post_meta($award_id, '_award_partners', true);
      if (!is_array($award_partners)) {
         $award_partners = $award_partners ? array($award_partners) : array();
      }

      if (in_array($partners_id, $award_partners)) {
         $award_partners = array_diff($award_partners, array($partners_id));
         update_post_meta($award_id, '_award_partners', $award_partners);
      }
   }

   // Then set this partners for the selected awards
   foreach ($awards as $award_id) {
      $award_partners = get_post_meta($award_id, '_award_partners', true);
      if (!is_array($award_partners)) {
         $award_partners = $award_partners ? array($award_partners) : array();
      }

      if (!in_array($partners_id, $award_partners)) {
         $award_partners[] = $partners_id;
         update_post_meta($award_id, '_award_partners', $award_partners);
      }
   }
}

// Hook into partners save to maintain two-way sync
function on_partners_save_sync_awards($post_id)
{
   if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
   }

   if (get_post_type($post_id) !== 'partners') {
      return;
   }

   if (! current_user_can('edit_post', $post_id)) {
      return;
   }

   if (isset($_POST['partners_awards'])) {
      $awards = array_map('intval', $_POST['partners_awards']);
      sync_partners_awards_to_award($post_id, $awards);
   } else {
      // If no awards selected, remove all associations
      sync_partners_awards_to_award($post_id, array());
   }
}
add_action('save_post_partners', 'on_partners_save_sync_awards');

// Update custom column display for multiple partners
function display_awards_custom_columns($column, $post_id)
{
   switch ($column) {
      case 'award_organization':
         echo get_post_meta($post_id, '_award_organization', true) ?: '—';
         break;

      case 'award_partners':
         $partners_ids = get_post_meta($post_id, '_award_partners', true);
         if (!is_array($partners_ids)) {
            $partners_ids = $partners_ids ? array($partners_ids) : array();
         }

         if (!empty($partners_ids)) {
            $partners_names = array();
            foreach ($partners_ids as $partners_id) {
               $partners = get_post($partners_id);
               if ($partners) {
                  $partners_names[] = '<a href="' . get_edit_post_link($partners_id) . '">' . esc_html($partners->post_title) . '</a>';
               }
            }
            echo implode(', ', $partners_names);
         } else {
            echo '—';
         }
         break;

      case 'award_year':
         $years = get_the_terms($post_id, 'award_year');
         if ($years && ! is_wp_error($years)) {
            $year_names = array();
            foreach ($years as $year) {
               $year_names[] = $year->name;
            }
            echo implode(', ', $year_names);
         } else {
            echo '—';
         }
         break;

      case 'award_category':
         $categories = get_the_terms($post_id, 'award_category');
         if ($categories && ! is_wp_error($categories)) {
            $category_names = array();
            foreach ($categories as $category) {
               $category_names[] = $category->name;
            }
            echo implode(', ', $category_names);
         } else {
            echo '—';
         }
         break;
   }
}

// Enqueue Select2 for better UX
function codeweber_enqueue_select2()
{
   $screen = get_current_screen();

   // Load only on awards and partners edit pages
   if ($screen->post_type === 'awards' || $screen->post_type === 'partners' || $screen->post_type === 'practices') {
      wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), '4.1.0-rc.0', true);
      wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '4.1.0-rc.0');

      wp_add_inline_script('select2', '
            jQuery(document).ready(function($) {
                // Initialize Select2 for partners awards
                $("#partners_awards").select2({
                    placeholder: "' . __('Select awards...', 'codeweber') . '",
                    allowClear: true
                });
                
                // Initialize Select2 for award partners
                $("#award_partners").select2({
                    placeholder: "' . __('Select partners members...', 'codeweber') . '",
                    allowClear: true
                });

                 // Initialize Select2 for award partners
                $("#related_blog_categories").select2({
                    placeholder: "' . __('Select related blog categories...', 'codeweber') . '",
                    allowClear: true
                });

                // Initialize Select2 for award partners
                $("#related_blog_tags").select2({
                    placeholder: "' . __('Select related blog tags...', 'codeweber') . '",
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




//--------------------------------
//PRACTICES
//--------------------------------

//Category

add_action('practice_category_add_form_fields', 'add_practice_category_fields', 10, 2);
add_action('practice_category_edit_form_fields', 'edit_practice_category_fields', 10, 2);
add_action('created_practice_category', 'save_practice_category_meta', 10, 2);
add_action('edited_practice_category', 'save_practice_category_meta', 10, 2);

// Add fields when creating term
function add_practice_category_fields($taxonomy)
{
?>
   <div class="form-field term-color-wrap">
      <label for="practice_category_color"><?php _e('Category Color', 'horizons'); ?></label>

      <!-- Color preview box for add form -->
      <div class="color-preview-container">
         <span class="color-preview-label"><?php _e('Selected Color Preview:', 'horizons'); ?></span>
         <div class="color-preview-box primary"></div>
      </div>

      <select name="practice_category_color" id="practice_category_color" class="color-select">
         <option value="primary" selected><?php _e('Primary (#FEBE10)', 'horizons'); ?></option>
         <option value="vintage-burgundy"><?php _e('Vintage Burgundy (#893B41)', 'horizons'); ?></option>
         <option value="slate-gray"><?php _e('Slate Gray (#86909B)', 'horizons'); ?></option>
         <option value="olive-green"><?php _e('Olive Green (#738D50)', 'horizons'); ?></option>
         <option value="muted-teal"><?php _e('Muted Teal (#47878F)', 'horizons'); ?></option>
         <option value="charcoal-blue"><?php _e('Charcoal Blue (#303C42)', 'horizons'); ?></option>
         <option value="dusty-navy"><?php _e('Dusty Navy (#3D5567)', 'horizons'); ?></option>
      </select>
      <p><?php _e('Select color for practice category display', 'horizons'); ?></p>
   </div>

   <div class="form-field term-alt-title-wrap">
      <label for="practice_category_alt_title"><?php _e('Alternative Title', 'horizons'); ?></label>
      <input type="text" name="practice_category_alt_title" id="practice_category_alt_title" value="">
      <p><?php _e('Alternative title for display purposes', 'codweber'); ?></p>
   </div>

   <div class="form-field term-order-wrap">
      <label for="practice_category_order"><?php _e('Order Number', 'horizons'); ?></label>
      <input type="number" name="practice_category_order" id="practice_category_order" value="0" min="0">
      <p><?php _e('Set order for category display (lower numbers first)', 'horizons'); ?></p>
   </div>
<?php
}

function edit_practice_category_fields($term, $taxonomy)
{
   $color = get_term_meta($term->term_id, 'practice_category_color', true);
   $alt_title = get_term_meta($term->term_id, 'practice_category_alt_title', true);
   $order = get_term_meta($term->term_id, 'practice_category_order', true);
   $order = $order ? $order : 0;
?>
   <tr class="form-field term-color-wrap">
      <th scope="row">
         <label for="practice_category_color"><?php _e('Category Color', 'horizons'); ?></label>
      </th>
      <td>
         <!-- Color preview box -->
         <div class="color-preview-container">
            <span class="color-preview-label"><?php _e('Selected Color Preview:', 'horizons'); ?></span>
            <div class="color-preview-box <?php echo esc_attr($color ? $color : ''); ?>"></div>
         </div>

         <select name="practice_category_color" id="practice_category_color" class="color-select">
            <option value="primary" <?php selected($color, 'primary'); ?>><?php _e('Primary (#FEBE10)', 'horizons'); ?></option>
            <option value="vintage-burgundy" <?php selected($color, 'vintage-burgundy'); ?>><?php _e('Vintage Burgundy (#893B41)', 'horizons'); ?></option>
            <option value="slate-gray" <?php selected($color, 'slate-gray'); ?>><?php _e('Slate Gray (#86909B)', 'horizons'); ?></option>
            <option value="olive-green" <?php selected($color, 'olive-green'); ?>><?php _e('Olive Green (#738D50)', 'horizons'); ?></option>
            <option value="muted-teal" <?php selected($color, 'muted-teal'); ?>><?php _e('Muted Teal (#47878F)', 'horizons'); ?></option>
            <option value="charcoal-blue" <?php selected($color, 'charcoal-blue'); ?>><?php _e('Charcoal Blue (#303C42)', 'horizons'); ?></option>
            <option value="dusty-navy" <?php selected($color, 'dusty-navy'); ?>><?php _e('Dusty Navy (#3D5567)', 'horizons'); ?></option>
         </select>
         <p class="description"><?php _e('Select color for practice category display', 'horizons'); ?></p>
      </td>
   </tr>

   <tr class="form-field term-alt-title-wrap">
      <th scope="row">
         <label for="practice_category_alt_title"><?php _e('Alternative Title', 'horizons'); ?></label>
      </th>
      <td>
         <input type="text" name="practice_category_alt_title" id="practice_category_alt_title" value="<?php echo esc_attr($alt_title); ?>">
         <p class="description"><?php _e('Alternative title for display purposes', 'horizons'); ?></p>
      </td>
   </tr>

   <tr class="form-field term-order-wrap">
      <th scope="row">
         <label for="practice_category_order"><?php _e('Order Number', 'horizons'); ?></label>
      </th>
      <td>
         <input type="number" name="practice_category_order" id="practice_category_order" value="<?php echo esc_attr($order); ?>" min="0">
         <p class="description"><?php _e('Set order for category display (lower numbers first)', 'horizons'); ?></p>
      </td>
   </tr>
<?php
}

function save_practice_category_meta($term_id)
{
   if (isset($_POST['practice_category_color'])) {
      update_term_meta(
         $term_id,
         'practice_category_color',
         sanitize_text_field($_POST['practice_category_color'])
      );
   }

   if (isset($_POST['practice_category_alt_title'])) {
      update_term_meta(
         $term_id,
         'practice_category_alt_title',
         $_POST['practice_category_alt_title']
      );
   }

   if (isset($_POST['practice_category_order'])) {
      update_term_meta(
         $term_id,
         'practice_category_order',
         intval($_POST['practice_category_order'])
      );
   }
}

add_action('admin_enqueue_scripts', 'practice_category_admin_styles_scripts');
function practice_category_admin_styles_scripts()
{
   $screen = get_current_screen();

   if ($screen->taxonomy === 'practice_category') {
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

add_filter('manage_edit-practice_category_columns', 'add_practice_category_columns');
add_filter('manage_practice_category_custom_column', 'show_practice_category_columns', 10, 3);

function add_practice_category_columns($columns)
{
   $columns['color'] = __('Color', 'horizons');
   $columns['alt_title'] = __('Alt. Title', 'horizons');
   $columns['order'] = __('Order', 'horizons');
   return $columns;
}

function show_practice_category_columns($content, $column_name, $term_id)
{
   if ($column_name === 'color') {
      $color = get_term_meta($term_id, 'practice_category_color', true);
      if ($color) {
         $color_names = [
            'primary' => __('Primary', 'horizons'),
            'vintage-burgundy' => __('Vintage Burgundy', 'horizons'),
            'slate-gray' => __('Slate Gray', 'horizons'),
            'olive-green' => __('Olive Green', 'horizons'),
            'muted-teal' => __('Muted Teal', 'horizons'),
            'charcoal-blue' => __('Charcoal Blue', 'horizons'),
            'dusty-navy' => __('Dusty Navy', 'horizons')
         ];

         return '<div class="color-option-preview ' . esc_attr($color) . '"></div>' .
            '<span style="font-size: 12px;">' . $color_names[$color] . '</span>';
      }
   }

   if ($column_name === 'alt_title') {
      $alt_title = get_term_meta($term_id, 'practice_category_alt_title', true);
      return $alt_title ? esc_html($alt_title) : '—';
   }

   if ($column_name === 'order') {
      $order = get_term_meta($term_id, 'practice_category_order', true);
      return $order ? intval($order) : '0';
   }

   return $content;
}

// Function to get terms sorted by order number
function get_practice_categories_ordered($args = array())
{
   $defaults = array(
      'taxonomy' => 'practice_category',
      'hide_empty' => false,
      'meta_key' => 'practice_category_order',
      'orderby' => 'meta_value_num',
      'order' => 'ASC'
   );

   $args = wp_parse_args($args, $defaults);
   return get_terms($args);
}

// Pre-get posts filter to replace title
add_action('pre_get_posts', 'practice_category_alt_title_pre_get_posts');
function practice_category_alt_title_pre_get_posts($query)
{
   if ($query->is_main_query() && $query->is_tax('practice_category')) {
      $term = $query->get_queried_object();
      if ($term) {
         $alt_title = get_term_meta($term->term_id, 'practice_category_alt_title', true);
         if (!empty($alt_title)) {
            // Set alternative title for use in theme
            add_filter('single_term_title', function ($title) use ($alt_title) {
               return $alt_title;
            });
         }
      }
   }
}

function get_practice_category_color_html($term_id = null)
{
   // If term_id is not provided, try to get it automatically
   if ($term_id === null) {
      // For archives and taxonomies - get current term
      if (is_tax('practice_category') || is_category()) {
         $term = get_queried_object();
         $term_id = $term->term_id;
      }
      // For single posts - get first term
      elseif (is_singular('practices')) {
         $terms = get_the_terms(get_the_ID(), 'practice_category');
         if ($terms && !is_wp_error($terms)) {
            $term_id = $terms[0]->term_id;
         }
      }
   }

   // If term_id still not found, return default
   if (!$term_id) {
      return '<div class="brand-square-md"></div>';
   }

   $color = get_term_meta($term_id, 'practice_category_color', true);
   $color_class = $color ? 'bg-' . $color : '';

   return '<div class="brand-square-md ' . $color_class . '"></div>';
}

// Function to get alternative term title
function get_practice_category_alt_title($term_id = null)
{
   if (!$term_id) {
      if (is_tax('practice_category')) {
         $term = get_queried_object();
         $term_id = $term->term_id;
      }
   }

   if ($term_id) {
      $alt_title = get_term_meta($term_id, 'practice_category_alt_title', true);
      return !empty($alt_title) ? $alt_title : '';
   }

   return '';
}

//

// Добавляем поле изображения для таксономии practice_category
add_action('practice_category_add_form_fields', 'add_practice_category_image_field', 10, 2);
add_action('practice_category_edit_form_fields', 'edit_practice_category_image_field', 10, 2);

function add_practice_category_image_field($taxonomy)
{
?>
   <div class="form-field term-group">
      <label for="practice_category_image"><?php _e('Изображение категории', 'horizons'); ?></label>
      <input type="hidden" id="practice_category_image" name="practice_category_image" class="custom_media_url" value="">
      <div id="category-image-wrapper"></div>
      <p>
         <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e('Добавить изображение', 'horizons'); ?>" />
         <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e('Удалить изображение', 'horizons'); ?>" />
      </p>
   </div>
<?php
}

function edit_practice_category_image_field($term, $taxonomy)
{
   $image_id = get_term_meta($term->term_id, 'practice_category_image', true);
?>
   <tr class="form-field term-group-wrap">
      <th scope="row">
         <label for="practice_category_image"><?php _e('Изображение категории', 'horizons'); ?></label>
      </th>
      <td>
         <input type="hidden" id="practice_category_image" name="practice_category_image" value="<?php echo esc_attr($image_id); ?>">
         <div id="category-image-wrapper">
            <?php if ($image_id) : ?>
               <?php echo wp_get_attachment_image($image_id, 'medium'); ?>
            <?php endif; ?>
         </div>
         <p>
            <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e('Добавить изображение', 'horizons'); ?>" />
            <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e('Удалить изображение', 'horizons'); ?>" />
         </p>
      </td>
   </tr>
   <?php
}


// Сохраняем поле изображения
add_action('created_practice_category', 'save_practice_category_image', 10, 2);
add_action('edited_practice_category', 'save_practice_category_image', 10, 2);

function save_practice_category_image($term_id, $tt_id)
{
   if (isset($_POST['practice_category_image']) && '' !== $_POST['practice_category_image']) {
      $image = sanitize_text_field($_POST['practice_category_image']);
      update_term_meta($term_id, 'practice_category_image', $image);
   } else {
      delete_term_meta($term_id, 'practice_category_image');
   }
}

// Подключаем скрипты для медиабиблиотеки
add_action('admin_enqueue_scripts', 'load_media_scripts');
function load_media_scripts()
{
   wp_enqueue_media();
}

// Добавляем скрипт для работы с медиабиблиотекой
add_action('admin_footer', 'add_media_script');
function add_media_script()
{
   $current_screen = get_current_screen();

   if ($current_screen->base == 'edit-tags' || $current_screen->base == 'term') {
   ?>
      <script>
         jQuery(document).ready(function($) {
            function ct_media_upload(button_class) {
               var _custom_media = true,
                  _orig_send_attachment = wp.media.editor.send.attachment;

               $('body').on('click', button_class, function(e) {
                  var button_id = '#' + $(this).attr('id');
                  var send_attachment_bkp = wp.media.editor.send.attachment;
                  var button = $(button_id);
                  _custom_media = true;

                  wp.media.editor.send.attachment = function(props, attachment) {
                     if (_custom_media) {
                        $('#practice_category_image').val(attachment.id);
                        $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                        $('#category-image-wrapper .custom_media_image').attr('src', attachment.url).css('display', 'block');
                     } else {
                        return _orig_send_attachment.apply(this, [props, attachment]);
                     }
                  }

                  wp.media.editor.open(button);
                  return false;
               });
            }

            ct_media_upload('.ct_tax_media_button.button');

            $('body').on('click', '.ct_tax_media_remove', function() {
               $('#practice_category_image').val('');
               $('#category-image-wrapper').html('');
            });

            // Исправляем баг: убираем стандартный медиа-загрузчик
            $('a.add_media').on('click', function() {
               _custom_media = false;
            });
         });
      </script>
   <?php
   }
}

// Practice Post

/**
 * Регистрируем метабоксы для типа записи "Practices"
 */
function add_practices_meta_boxes()
{
   add_meta_box(
      'practices_details',
      __('Practice Details', 'horizons'),
      'render_practices_meta_box',
      'practices',
      'normal',
      'high'
   );
}
add_action('add_meta_boxes', 'add_practices_meta_boxes');

/**
 * Функция отрисовки метабокса
 */
function render_practices_meta_box($post)
{
   // Добавляем nonce для безопасности
   wp_nonce_field('practices_meta_save', 'practices_meta_nonce');

   // Получаем существующие значения из базы данных
   $your_task_text = get_post_meta($post->ID, 'your_task_text', true);
   $our_solution_text = get_post_meta($post->ID, 'our_solution_text', true);
   $advantages_text = get_post_meta($post->ID, 'advantages_text', true);
   $selected_categories = get_post_meta($post->ID, 'related_blog_categories', true);
   $selected_tags = get_post_meta($post->ID, 'related_blog_tags', true);

   // Если это массив, работаем с ним, если нет, создаем пустой массив
   $selected_categories = is_array($selected_categories) ? $selected_categories : array();
   $selected_tags = is_array($selected_tags) ? $selected_tags : array();

   // Получаем все категории и теги из таксономий для блога
   $blog_categories = get_terms(array(
      'taxonomy' => 'category',
      'hide_empty' => false,
   ));

   $blog_tags = get_terms(array(
      'taxonomy' => 'post_tag',
      'hide_empty' => false,
   ));
   ?>

   <p><strong><?php _e('Practice description (main content)', 'horizons'); ?></strong><br>
      <em><?php _e('Filled in the standard WordPress editor above.', 'horizons'); ?></em>
   </p>
   <hr>

   <p>
      <label for="your_task_text"><strong><?php _e('"Your task" text:', 'horizons'); ?></strong></label>
      <textarea id="your_task_text" name="your_task_text" rows="5" style="width: 100%; margin-top: 5px;"><?php echo esc_textarea($your_task_text); ?></textarea>
   </p>
   <hr>

   <p>
      <label for="our_solution_text"><strong><?php _e('"Our solution" text:', 'horizons'); ?></strong></label>
      <textarea id="our_solution_text" name="our_solution_text" rows="10" style="width: 100%; margin-top: 5px;"><?php echo esc_textarea($our_solution_text); ?></textarea>
   </p>
   <hr>

   <p>
      <label for="advantages_text"><strong><?php _e('"Advantages" text:', 'horizons'); ?></strong></label>
      <textarea id="advantages_text" name="advantages_text" rows="10" style="width: 100%; margin-top: 5px;"><?php echo esc_textarea($advantages_text); ?></textarea>
   </p>
   <hr>

   <p>
      <label for="related_blog_categories[]"><strong><?php _e('Blog article categories for display:', 'horizons'); ?></strong></label><br>
      <em><?php _e('Select one or more categories. Articles that belong to ANY of the selected categories will be shown.', 'horizons'); ?></em>
      <select id="related_blog_categories" name="related_blog_categories[]" multiple="multiple" style="width: 100%; height: 150px; margin-top: 10px;">
         <?php
         if (!empty($blog_categories) && !is_wp_error($blog_categories)) {
            foreach ($blog_categories as $category) {
               $selected = in_array($category->term_id, $selected_categories) ? 'selected="selected"' : '';
               echo '<option value="' . esc_attr($category->term_id) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
            }
         }
         ?>
      </select>
      <small><?php _e('Hold Ctrl (Cmd on Mac) for multiple selection.', 'horizons'); ?></small>
   </p>

   <p>
      <label for="related_blog_tags[]"><strong><?php _e('Blog article tags for display:', 'horizons'); ?></strong></label><br>
      <em><?php _e('Select one or more tags. Articles that have ANY of the selected tags will be shown.', 'horizons'); ?></em>
      <select id="related_blog_tags" name="related_blog_tags[]" multiple="multiple" style="width: 100%; height: 150px; margin-top: 10px;">
         <?php
         if (!empty($blog_tags) && !is_wp_error($blog_tags)) {
            foreach ($blog_tags as $tag) {
               $selected = in_array($tag->term_id, $selected_tags) ? 'selected="selected"' : '';
               echo '<option value="' . esc_attr($tag->term_id) . '" ' . $selected . '>' . esc_html($tag->name) . '</option>';
            }
         }
         ?>
      </select>
      <small><?php _e('Hold Ctrl (Cmd on Mac) for multiple selection.', 'horizons'); ?></small>
   </p>
   <p><strong><?php _e('Note:', 'horizons'); ?></strong> <?php _e('On the frontend, articles will be displayed that belong to ANY of the selected categories AND have ANY of the selected tags (logical "AND" between taxonomies, "OR" within them).', 'horizons'); ?></p>

<?php
}

/**
 * Функция сохранения метаполей
 */
function save_practices_meta($post_id)
{
   // Проверяем nonce
   if (!isset($_POST['practices_meta_nonce']) || !wp_verify_nonce($_POST['practices_meta_nonce'], 'practices_meta_save')) {
      return;
   }

   // Проверяем права пользователя
   if (!current_user_can('edit_post', $post_id)) {
      return;
   }

   // Проверяем, что это не автосохранение
   if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
   }

   // Массив полей для сохранения
   $meta_fields = array(
      'your_task_text',
      'our_solution_text',
      'advantages_text'
   );

   // Сохраняем каждое текстовое поле
   foreach ($meta_fields as $field) {
      if (isset($_POST[$field])) {
         $value = sanitize_textarea_field($_POST[$field]);
         update_post_meta($post_id, $field, $value);
      }
   }

   // Сохраняем выбранные категории (массив)
   if (isset($_POST['related_blog_categories'])) {
      $categories = array_map('intval', $_POST['related_blog_categories']);
      update_post_meta($post_id, 'related_blog_categories', $categories);
   } else {
      update_post_meta($post_id, 'related_blog_categories', array());
   }

   // Сохраняем выбранные теги (массив)
   if (isset($_POST['related_blog_tags'])) {
      $tags = array_map('intval', $_POST['related_blog_tags']);
      update_post_meta($post_id, 'related_blog_tags', $tags);
   } else {
      update_post_meta($post_id, 'related_blog_tags', array());
   }
}
add_action('save_post_practices', 'save_practices_meta');
