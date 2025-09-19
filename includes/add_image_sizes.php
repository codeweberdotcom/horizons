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