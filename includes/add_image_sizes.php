<?php

// Добавляем новый размер изображения (если нужно)
function my_child_theme_image_sizes()
{
   add_image_size('codeweber_awards', 960, 600, true);
   add_image_size('codeweber_staff_800', 800, 800, true);
}
add_action('after_setup_theme', 'my_child_theme_image_sizes', 20);


// Добавляем разрешённые размеры для нового типа записи
function my_child_theme_allowed_sizes($sizes)
{
   // Для нового типа записи 'awards'
   $sizes['awards'] = ['codeweber_awards'];
   $sizes['post'] = ['codeweber_staff','codeweber_awards','thumbnail'];
   $sizes['vacancies'] = ['codeweber_staff_800','thumbnail','codeweber_staff'];
   $sizes['partners'] = ['codeweber_staff','codeweber_staff_800','woocommerce_gallery_thumbnail'];
   return $sizes;
}
add_filter('codeweber_allowed_image_sizes', 'my_child_theme_allowed_sizes');


// Добавляем размер изображения для категорий
add_image_size('practice_category_image', 720, 850, true);

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
            'height' => 850,
            'crop' => true
         ),
         'codeweber_staff_800' => array(
            'width' => 800,
            'height' => 800,
            'crop' => true
         ),
      );
   }

   return $sizes;
}