<?php
//--------------------------------
//STAFF
//--------------------------------
/**
 * Add second metabox for additional staff information
 */
function codeweber_add_staff_additional_meta_boxes()
{
   add_meta_box(
      'staff_additional_details',
      'Additional Information',
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
?>

   <div style="display: grid; gap: 16px;">
      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: center;">
         <label for="staff_full_position"><strong>Full Position Title:</strong></label>
         <input type="text" id="staff_full_position" name="staff_full_position" value="<?php echo esc_attr($full_position); ?>"
            placeholder="For example: Senior Financial Analyst and Investment Advisor" style="width: 100%; padding: 8px;">
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: center;">
         <label for="staff_regions"><strong>Regions:</strong></label>
         <input type="text" id="staff_regions" name="staff_regions" value="<?php echo esc_attr($regions); ?>"
            placeholder="For example: Europe, Asia, North America" style="width: 100%; padding: 8px;">
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: start;">
         <label for="staff_short_description"><strong>Short Description:</strong></label>
         <textarea id="staff_short_description" name="staff_short_description"
            placeholder="Brief information about the employee"
            style="width: 100%; padding: 8px; min-height: 80px; resize: vertical;"><?php echo esc_textarea($short_description); ?></textarea>
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: start;">
         <label for="staff_language_skills"><strong>Language Skills:</strong></label>
         <textarea id="staff_language_skills" name="staff_language_skills"
            placeholder="For example: English (C1), German (B2), French (A2)"
            style="width: 100%; padding: 8px; min-height: 60px; resize: vertical;"><?php echo esc_textarea($language_skills); ?></textarea>
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
         $new_columns['staff_full_position'] = 'Full Position';
         $new_columns['staff_regions'] = 'Regions';
         $new_columns['staff_language_skills'] = 'Languages';
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
   return $columns;
}
add_filter('manage_edit-staff_sortable_columns', 'codeweber_make_staff_additional_columns_sortable');


//--------------------------------
//SERVICES
//--------------------------------
add_action('service_category_add_form_fields', 'add_service_category_color_field', 10, 2);
add_action('service_category_edit_form_fields', 'edit_service_category_color_field', 10, 2);
add_action('created_service_category', 'save_service_category_color_meta', 10, 2);
add_action('edited_service_category', 'save_service_category_color_meta', 10, 2);

// Добавляем поле при создании термина
function add_service_category_color_field($taxonomy)
{
?>
   <div class="form-field term-color-wrap">
      <label for="service_category_color">Цвет категории</label>
      <select name="service_category_color" id="service_category_color" class="color-select">
         <option value="primary">Primary (#FEBE10)</option>
         <option value="vintage-burgundy">Vintage Burgundy (#893B41)</option>
         <option value="slate-gray">Slate Gray (#86909B)</option>
         <option value="olive-green">Olive Green (#738D50)</option>
         <option value="muted-teal">Muted Teal (#47878F)</option>
         <option value="charcoal-blue">Charcoal Blue (#303C42)</option>
         <option value="dusty-navy">Dusty Navy (#3D5567)</option>
      </select>
      <p>Выберите цвет для отображения категории услуг</p>
   </div>
<?php
}

// Добавляем поле при редактировании термина
function edit_service_category_color_field($term, $taxonomy)
{
   $color = get_term_meta($term->term_id, 'service_category_color', true);
?>
   <tr class="form-field term-color-wrap">
      <th scope="row">
         <label for="service_category_color">Цвет категории</label>
      </th>
      <td>
         <select name="service_category_color" id="service_category_color" class="color-select">
            <option value="primary" <?php selected($color, 'primary'); ?>>Primary (#FEBE10)</option>
            <option value="vintage-burgundy" <?php selected($color, 'vintage-burgundy'); ?>>Vintage Burgundy (#893B41)</option>
            <option value="slate-gray" <?php selected($color, 'slate-gray'); ?>>Slate Gray (#86909B)</option>
            <option value="olive-green" <?php selected($color, 'olive-green'); ?>>Olive Green (#738D50)</option>
            <option value="muted-teal" <?php selected($color, 'muted-teal'); ?>>Muted Teal (#47878F)</option>
            <option value="charcoal-blue" <?php selected($color, 'charcoal-blue'); ?>>Charcoal Blue (#303C42)</option>
            <option value="dusty-navy" <?php selected($color, 'dusty-navy'); ?>>Dusty Navy (#3D5567)</option>
         </select>
         <p class="description">Выберите цвет для отображения категории услуг</p>
      </td>
   </tr>
<?php
}

// Сохраняем метаданные
function save_service_category_color_meta($term_id)
{
   if (isset($_POST['service_category_color'])) {
      update_term_meta(
         $term_id,
         'service_category_color',
         sanitize_text_field($_POST['service_category_color'])
      );
   }
}


// functions.php
add_action('admin_enqueue_scripts', 'service_category_admin_styles');
function service_category_admin_styles()
{
   $screen = get_current_screen();

   if ($screen->taxonomy === 'service_category') {
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
        </style>
        ';
   }
}



add_filter('manage_edit-service_category_columns', 'add_service_category_color_column');
add_filter('manage_service_category_custom_column', 'show_service_category_color_column', 10, 3);

function add_service_category_color_column($columns)
{
   $columns['color'] = 'Цвет';
   return $columns;
}

function show_service_category_color_column($content, $column_name, $term_id)
{
   if ($column_name === 'color') {
      $color = get_term_meta($term_id, 'service_category_color', true);
      if ($color) {
         $color_names = [
            'primary' => 'Primary',
            'vintage-burgundy' => 'Vintage Burgundy',
            'slate-gray' => 'Slate Gray',
            'olive-green' => 'Olive Green',
            'muted-teal' => 'Muted Teal',
            'charcoal-blue' => 'Charcoal Blue',
            'dusty-navy' => 'Dusty Navy'
         ];

         return '<div class="color-option-preview ' . esc_attr($color) . '"></div>' .
            '<span style="font-size: 12px;">' . $color_names[$color] . '</span>';
      }
   }
   return $content;
}


function get_service_category_color_html($term_id = null)
{
   // Если term_id не передан, пытаемся получить автоматически
   if ($term_id === null) {
      // Для архивов и таксономий - получаем текущий термин
      if (is_tax('service_category') || is_category()) {
         $term = get_queried_object();
         $term_id = $term->term_id;
      }
      // Для одиночных записей - получаем первый термин
      elseif (is_singular('services')) {
         $terms = get_the_terms(get_the_ID(), 'service_category');
         if ($terms && !is_wp_error($terms)) {
            $term_id = $terms[0]->term_id;
         }
      }
   }

   // Если term_id так и не нашли, возвращаем дефолтный
   if (!$term_id) {
      return '<div class="brand-square-md"></div>';
   }

   $color = get_term_meta($term_id, 'service_category_color', true);
   $color_class = $color ? 'bg-' . $color : '';

   return '<div class="brand-square-md ' . $color_class . '"></div>';
}