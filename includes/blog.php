<?php

/**
 * Shortcode: Blog Posts Slider
 * 
 * Генерирует адаптивный слайдер записей блога на основе библиотеки Swiper.js.
 * Предоставляет расширенные возможности для фильтрации, настройки внешнего вида и поведения слайдера.
 * 
 * @since 1.0.0
 * 
 * @param array $atts {
 *     Массив атрибутов шорткода.
 *
 *     @type int    $posts_per_page Количество выводимых записей. По умолчанию 4.
 *     @type string $category       Slug категорий для фильтрации (через запятую). По умолчанию пусто.
 *     @type string $tag            Slug меток для фильтрации (через запятую). По умолчанию пусто.
 *     @type string $orderby        Поле для сортировки записей ('date', 'title', 'rand' и др.). По умолчанию 'date'.
 *     @type string $order          Направление сортировки ('ASC' или 'DESC'). По умолчанию 'DESC'.
 *     @type string $image_size     Размер изображения записи. По умолчанию 'codeweber_staff'.
 *     @type int    $excerpt_length Длина текста анонса (в словах). По умолчанию 20.
 *     @type string $items_xl       Количество слайдов при разрешении ≥1200px. По умолчанию '3'.
 *     @type string $items_lg       Количество слайдов при разрешении ≥992px. По умолчанию '3'.
 *     @type string $items_md       Количество слайдов при разрешении ≥768px. По умолчанию '2'.
 *     @type string $items_sm       Количество слайдов при разрешении ≥576px. По умолчанию '2'.
 *     @type string $items_xs       Количество слайдов при разрешении <576px. По умолчанию '1'.
 *     @type string $items_xxs      Количество слайдов на очень маленьких экранах. По умолчанию '1'.
 *     @type string $margin         Отступ между слайдами в пикселях. По умолчанию '30'.
 *     @type string $dots           Отображать навигационные точки ('true' или 'false'). По умолчанию 'true'.
 *     @type string $nav         Отображать навигационные стрелки ('true' или 'false'). По умолчанию 'false'.
 *     @type string $autoplay       Включить автопрокрутку ('true' или 'false'). По умолчанию 'false'.
 *     @type string $loop           Зациклить прокрутку ('true' или 'false'). По умолчанию 'false'.
 * }
 * @return string HTML-разметка слайдера или сообщение об ошибке, если записи не найдены.
 * 
 * @example [blog_posts_slider] // Выведет слайдер с 4 последними записями
 * @example [blog_posts_slider posts_per_page="6" category="news,events" items_xl="4" autoplay="true"] // Слайдер из 6 записей категорий "news" и "events", 4 слайда в ряд с автопрокруткой
 * @example [blog_posts_slider posts_per_page="8" tag="акции" orderby="rand" items_xl="4" items_md="3" items_sm="2" margin="20" dots="false" nav="true" loop="true"] // 8 случайных записей с меткой "акции", адаптивная сетка, навигация стрелками
 * @example [blog_posts_slider posts_per_page="5" category="обзоры" image_size="large" excerpt_length="30" items_xl="3" items_xxs="1" autoplay="true"] // 5 записей категории "обзоры" с большими изображениями и длинными анонсами
 * @example [blog_posts_slider posts_per_page="3" category="главное" order="ASC" items_xl="1" items_lg="1" nav="true" dots="false"] // 3 первые записи категории "главное" по одной на весь экран
 */

add_shortcode('blog_posts_slider', 'codeweber_blog_posts_slider_shortcode');

function codeweber_blog_posts_slider_shortcode($atts)
{
   // Атрибуты по умолчанию
   $atts = shortcode_atts(array(
      'posts_per_page' => 4,
      'category' => '',      // Фильтр по категориям (slug через запятую)
      'tag' => '',           // Фильтр по меткам (slug через запятую)
      'orderby' => 'date',
      'order' => 'DESC',
      'image_size' => 'codeweber_staff',
      'excerpt_length' => 20,
      'items_xl' => '3',
      'items_lg' => '3',
      'items_md' => '2',
      'items_sm' => '2',
      'items_xs' => '1',
      'items_xxs' => '1',
      'margin' => '30',
      'dots' => 'true',
      'nav' => 'false',
      'autoplay' => 'false',
      'loop' => 'false'
   ), $atts);

   // Аргументы для WP_Query
   $args = array(
      'post_type' => 'post',
      'posts_per_page' => intval($atts['posts_per_page']),
      'orderby' => $atts['orderby'],
      'order' => $atts['order'],
      'post_status' => 'publish'
   );

   // Добавляем фильтр по категориям если указан
   if (!empty($atts['category'])) {
      $categories = array_map('trim', explode(',', $atts['category']));
      $args['category_name'] = implode(',', $categories);
   }

   // Добавляем фильтр по меткам если указан
   if (!empty($atts['tag'])) {
      $tags = array_map('trim', explode(',', $atts['tag']));
      $args['tag_slug__in'] = $tags;
   }

   $blog_query = new WP_Query($args);

   if (!$blog_query->have_posts()) {
      return '<p>Записи не найдены</p>';
   }

   // Формируем data-атрибуты для Swiper
   $swiper_data = array(
      'data-margin' => esc_attr($atts['margin']),
      'data-dots' => esc_attr($atts['dots']),
      'data-nav' => esc_attr($atts['nav']),
      'data-autoplay' => esc_attr($atts['autoplay']),
      'data-loop' => esc_attr($atts['loop']),
      'data-items-xxl' => esc_attr($atts['items_xl']),
      'data-items-xl' => esc_attr($atts['items_xl']),
      'data-items-lg' => esc_attr($atts['items_lg']),
      'data-items-md' => esc_attr($atts['items_md']),
      'data-items-sm' => esc_attr($atts['items_sm']),
      'data-items-xs' => esc_attr($atts['items_xs']),
      'data-items-xxs' => esc_attr($atts['items_xxs'])
   );

   $swiper_attrs = '';
   foreach ($swiper_data as $key => $value) {
      $swiper_attrs .= $key . '="' . $value . '" ';
   }

   ob_start();
?>

   <div class="swiper-container dots-closer blog grid-view mb-6" <?php echo $swiper_attrs; ?>>
      <div class="swiper">
         <div class="swiper-wrapper">
            <?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
               <div class="swiper-slide">
                  <div class="mb-1">
                     <article>
                        <div class="post-col">
                           <figure class="post-figure overlay overlay-1 hover-scale rounded mb-5">
                              <a href="<?php the_permalink(); ?>">
                                 <?php if (has_post_thumbnail()) : ?>
                                 <?php
                                    $image_size = $atts['image_size'];
                                    $thumbnail = get_the_post_thumbnail(get_the_ID(), $image_size, array(
                                       'decoding' => 'async',
                                       'alt' => esc_attr(get_the_title()),
                                       'class' => 'post-image swiper-lazy'
                                    ));
                                    echo $thumbnail;
                                 ?>
                                 <?php else : ?>
                                 <img decoding="async"
                                 src="<?php echo esc_url(get_template_directory_uri() . '/dist/assets/img/placeholder_400x400.jpg'); ?>"
                                 alt="<?php echo esc_attr(get_the_title()); ?>"
                                 class="post-image swiper-lazy" />
                           <?php endif; ?>

                           <!-- Всегда отображаем основную категорию -->
                           <div class="caption-wrapper p-7">
                              <div class="caption bg-matte-color mt-auto label-u text-neutral-50 px-4 py-2">
                                 <?php
                                 $categories = get_the_category();
                                 if (!empty($categories)) {
                                    echo esc_html($categories[0]->name);
                                 }
                                 ?>
                              </div>
                           </div><span class="bg"></span>
                           </a>
                           <figcaption>
                              <div class="from-top mb-0 label-u"><?php echo __('Read', 'horizons'); ?></div>
                           </figcaption>
                           </figure>

                           <div class="post-body mt-4">
                              <h3 class="h4 post-title"><?php the_title(); ?></h3>
                              <div class="body-l-l mb-4 post-excerpt">
                                 <?php
                                 $excerpt = get_the_excerpt();
                                 if (empty($excerpt)) {
                                    $excerpt = get_the_content();
                                 }
                                 $excerpt_length = intval($atts['excerpt_length']);
                                 echo wp_trim_words($excerpt, $excerpt_length, '...');
                                 ?>
                              </div>

                              <a href="<?php the_permalink(); ?>" class="hover-4 link-body label-s text-charcoal-blue me-4 post-read-more">
                                 <?php _e('Читать полностью', 'codeweber'); ?>
                              </a>
                           </div>
                           <!--/.post-body -->
                        </div>
                        <!-- /.post-col -->
                     </article>
                     <!-- /article -->
                  </div>
                  <!-- /.item-inner -->
               </div>
               <!--/.swiper-slide -->
            <?php endwhile; ?>
         </div>
         <!--/.swiper-wrapper -->
      </div>
      <!-- /.swiper -->
   </div>
   <!-- /.swiper-container -->

<?php
   wp_reset_postdata();
   return ob_get_clean();
}




// Добавьте этот код в functions.php вашей темы

// Регистрация шорткода
add_shortcode('custom_menu', 'custom_menu_shortcode');

function custom_menu_shortcode($atts)
{
   // Атрибуты шорткода
   $atts = shortcode_atts(array(
      'id' => '',
      'title' => '',
   ), $atts);

   // Если ID меню не указан, возвращаем пустую строку
   if (empty($atts['id'])) {
      return '<!-- Menu ID not specified -->';
   }

   // Получаем меню по ID
   $menu_items = wp_get_nav_menu_items($atts['id']);

   // Если меню не найдено или пустое
   if (!$menu_items || is_wp_error($menu_items)) {
      return '<!-- Menu not found or empty -->';
   }

   // Начинаем формировать HTML
   $output = '<div class="widget mb-10">';

   // Добавляем заголовок если указан (с поддержкой перевода)
   if (!empty($atts['title'])) {
      $translated_title = apply_filters('wpml_translate_single_string', $atts['title'], 'custom-menu-shortcode', 'menu-title-' . sanitize_title($atts['title']));
      $output .= '<div class="text-line-after label-u mb-4">' . esc_html($translated_title) . '</div>';
   }

   $output .= '<nav id="awards-category-nav">';
   $output .= '<ul class="list-unstyled">';

   // Перебираем элементы меню
   foreach ($menu_items as $item) {
      $count = get_post_count_for_menu_item($item);

      $output .= '<li class="mt-0">';
      $output .= '<a class="label-s text-neutral-500" href="' . esc_url($item->url) . '">';
      $output .= esc_html($item->title) . $count;
      $output .= '</a>';
      $output .= '</li>';
   }

   $output .= '</ul>';
   $output .= '</nav>';
   $output .= '</div>';

   return $output;
}

// Вспомогательная функция для получения количества записей
function get_post_count_for_menu_item($menu_item)
{
   $count = '';

   // Если это ссылка на категорию
   if ($menu_item->type === 'taxonomy' && $menu_item->object === 'category') {
      $category = get_category($menu_item->object_id);
      if ($category) {
         $count = ' <span class="text-muted">(' . $category->count . ')</span>';
      }
   }
   // Если это ссылка на произвольную таксономию
   elseif ($menu_item->type === 'taxonomy') {
      $term = get_term($menu_item->object_id, $menu_item->object);
      if ($term && !is_wp_error($term)) {
         $count = ' <span class="text-muted">(' . $term->count . ')</span>';
      }
   }
   // Если это ссылка с параметром category в URL
   elseif ($menu_item->type === 'custom' && strpos($menu_item->url, 'category=') !== false) {
      preg_match('/category=([^&]+)/', $menu_item->url, $matches);
      if (!empty($matches[1])) {
         $category = get_category_by_slug($matches[1]);
         if ($category) {
            $count = ' <span class="text-muted">(' . $category->count . ')</span>';
         }
      }
   }

   return $count;
}

// Регистрация строк для перевода (для WPML)
add_action('init', 'register_custom_menu_strings');
function register_custom_menu_strings()
{
   if (function_exists('wpml_register_single_string')) {
      do_action('wpml_register_single_string', 'custom-menu-shortcode', 'Menu Title', 'Категория');
   }
}