<?php

// Добавляем новый размер изображения (если нужно)
function my_child_theme_image_sizes()
{
   add_image_size('codeweber_awards', 960, 600, true);
}
add_action('after_setup_theme', 'my_child_theme_image_sizes', 20);



// Добавляем разрешённые размеры для нового типа записи
function my_child_theme_allowed_sizes($sizes)
{
   // Для нового типа записи 'awards'
   $sizes['awards'] = ['codeweber_awards'];
   $sizes['post'] = ['codeweber_staff', 'codeweber_awards', 'thumbnail'];
   $sizes['partners'] = ['codeweber_staff', 'woocommerce_gallery_thumbnail'];
   return $sizes;
}
add_filter('codeweber_allowed_image_sizes', 'my_child_theme_allowed_sizes');


// // Добавляем дополнительные размеры к существующему типу 'staff'
// function modify_staff_image_sizes($sizes)
// {
//    // Добавляем большой размер для staff
//    return array_merge($sizes, ['large', 'codeweber_big']);
// }
// add_filter('codeweber_allowed_image_sizes_staff', 'modify_staff_image_sizes');

// // Полностью заменяем размеры для 'projects'
// function replace_projects_image_sizes($sizes)
// {
//    // Полностью новые размеры для projects
//    return ['codeweber_project_900-900', 'large', 'thumbnail'];
// }
// add_filter('codeweber_allowed_image_sizes_projects', 'replace_projects_image_sizes');




// Добавляем размер изображения для категорий
add_image_size('practice_category_image', 720, 900, true);

// Определяем контекст загрузки и фильтруем размеры
add_filter('intermediate_image_sizes_advanced', 'limit_image_sizes_by_context', 10, 2);

function limit_image_sizes_by_context($sizes, $metadata)
{
   // Проверяем различные способы определения контекста
   $context = '';

   // 1. Проверяем параметр taxonomy
   if (!empty($_REQUEST['taxonomy']) && $_REQUEST['taxonomy'] === 'practice_category') {
      $context = 'practice_category';
   }
   // 2. Проверяем referer (откуда пришел запрос)
   elseif (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'taxonomy=practice_category') !== false) {
      $context = 'practice_category';
   }
   // 3. Проверяем post_id для таксономий (если есть)
   elseif (!empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === '') {
      // Для таксономий post_id может быть 0 или не существовать
      $context = 'practice_category';
   }

   // Если это загрузка для practice_category, ограничиваем размеры
   if ($context === 'practice_category') {
      return array(
         'practice_category_image' => array(
            'width' => 720,
            'height' => 900,
            'crop' => true
         )
      );
   }

   return $sizes;
}