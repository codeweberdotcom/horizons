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
 *     @type int    $posts_per_page   Количество выводимых записей. По умолчанию 4.
 *     @type string $category         Slug категорий для фильтрации (через запятую). По умолчанию пусто.
 *     @type string $tag              Slug меток для фильтрации (через запятую). По умолчанию пусто.
 *     @type string $orderby          Поле для сортировки записей ('date', 'title', 'rand' и др.). По умолчанию 'date'.
 *     @type string $order            Направление сортировки ('ASC' или 'DESC'). По умолчанию 'DESC'.
 *     @type string $image_size       Размер изображения записи. По умолчанию 'horizons_staff'.
 *     @type int    $excerpt_length   Длина текста анонса (в словах). По умолчанию 20.
 *     @type int    $title_length     Максимальное количество символов в заголовке. По умолчанию 0 (без ограничения).
 *     @type string $items_xl         Количество слайдов при разрешении ≥1200px. По умолчанию '3'.
 *     @type string $items_lg         Количество слайдов при разрешении ≥992px. По умолчанию '3'.
 *     @type string $items_md         Количество слайдов при разрешении ≥768px. По умолчанию '2'.
 *     @type string $items_sm         Количество слайдов при разрешении ≥576px. По умолчанию '2'.
 *     @type string $items_xs         Количество слайдов при разрешении <576px. По умолчанию '1'.
 *     @type string $items_xxs        Количество слайдов на очень маленьких экранах. По умолчанию '1'.
 *     @type string $margin           Отступ между слайдами в пикселях. По умолчанию '30'.
 *     @type string $dots             Отображать навигационные точки ('true' или 'false'). По умолчанию 'true'.
 *     @type string $nav              Отображать навигационные стрелки ('true' или 'false'). По умолчанию 'false'.
 *     @type string $autoplay         Включить автопрокрутку ('true' или 'false'). По умолчанию 'false'.
 *     @type string $loop             Зациклить прокрутку ('true' или 'false'). По умолчанию 'false'.
 * }
 * @return string HTML-разметка слайдера или сообщение об ошибке, если записи не найдены.
 * 
 * @example [blog_posts_slider] // Выведет слайдер с 4 последними записями
 * @example [blog_posts_slider posts_per_page="6" category="news,events" items_xl="4" autoplay="true"] // Слайдер из 6 записей категорий "news" и "events", 4 слайда в ряд с автопрокруткой
 * @example [blog_posts_slider posts_per_page="8" tag="акции" orderby="rand" title_length="50" items_xl="4" items_md="3" items_sm="2" margin="20" dots="false" nav="true" loop="true"] // 8 случайных записей с меткой "акции", заголовки до 50 символов, адаптивная сетка
 * @example [blog_posts_slider posts_per_page="5" category="обзоры" image_size="large" excerpt_length="30" title_length="60" items_xl="3" items_xxs="1" autoplay="true"] // 5 записей категории "обзоры" с ограничением заголовка до 60 символов
 */

add_shortcode('blog_posts_slider', 'horizons_blog_posts_slider_shortcode');

function horizons_blog_posts_slider_shortcode($atts)
{
   // Атрибуты по умолчанию
   $atts = shortcode_atts(array(
      'posts_per_page' => 4,
      'category' => '',      // Фильтр по категориям (slug через запятую)
      'tag' => '',           // Фильтр по меткам (slug через запятую)
      'orderby' => 'date',
      'order' => 'DESC',
      'image_size' => 'horizons_staff',
      'excerpt_length' => 20,
      'title_length' => 0,   // Максимальное количество символов в заголовке (0 - без ограничения)
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
   } else {
      // Если категория не указана, проверяем, находимся ли мы на странице single post
      if (is_single() && get_post_type() === 'post') {
         $current_post_categories = get_the_category();
         if (!empty($current_post_categories)) {
            $category_slugs = array();
            foreach ($current_post_categories as $category) {
               $category_slugs[] = $category->slug;
            }
            $args['category_name'] = implode(',', $category_slugs);
         }
      }
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

   <div class="swiper-container dots-closer blog grid-view mb-12" <?php echo $swiper_attrs; ?>>
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
                              <?php
                              display_post_meta(array(
                                 'wrapper_class' => 'post-meta d-flex mb-3 fs-16',
                                 'comments_class' => '',
                                 'comments_show_text' => false,
                                 'author_text' => __('%s', 'horizons'),
                              ));
                              ?>
                              <h3 class="h4 post-title" title="<?php the_title(); ?>">
                                 <?php
                                 $title = get_the_title();
                                 $title_length = intval($atts['title_length']);

                                 if ($title_length > 0 && mb_strlen($title) > $title_length) {
                                    $shortened_title = mb_substr($title, 0, $title_length) . '...';
                                    echo esc_html($shortened_title);
                                 } else {
                                    echo esc_html($title);
                                 }
                                 ?>
                              </h3>
                              <?php if (intval($atts['excerpt_length']) > 0) : ?>
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
                              <?php endif; ?>

                              <a href="<?php the_permalink(); ?>" class="hover-4 link-body label-s text-charcoal-blue me-4 post-read-more">
                                 <?php _e('Read more', 'horizons'); ?>
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
   ), $atts);

   // Если ID меню не указан, возвращаем пустую строку
   if (empty($atts['id'])) {
      return '<!-- Menu ID not specified -->';
   }

   // Получаем меню по ID
   $menu_items = wp_get_nav_menu_items($atts['id']);

   // Получаем название меню
   $menu = wp_get_nav_menu_object($atts['id']);

   // Если меню не найдено или пустое
   if (!$menu_items || is_wp_error($menu_items) || !$menu) {
      return '<!-- Menu not found or empty -->';
   }

   // Получаем текущий URL для сравнения
   $current_url = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
   $current_url = trailingslashit($current_url);

   // Начинаем формировать HTML
   $output = '<div class="widget mb-10">';

   // Добавляем заголовок из названия меню (с поддержкой перевода)
   $menu_name = apply_filters('wpml_translate_single_string', $menu->name, 'nav_menu', 'menu_name_' . $menu->term_id);
   $output .= '<div class="text-line-after label-u mb-4">' . esc_html($menu_name) . '</div>';

   $output .= '<nav class="sidebar" id="awards-category-nav">';
   $output .= '<ul class="list-unstyled">';

   // Перебираем элементы меню
   foreach ($menu_items as $item) {
      $count = get_post_count_for_menu_item($item);

      // Проверяем активен ли текущий пункт меню
      $is_active = is_menu_item_active($item, $current_url);
      $active_class = $is_active ? ' active' : '';

      $output .= '<li class="mt-0">';
      $output .= '<a class="label-s text-neutral-500 my-1 d-block position-relative' . $active_class . '" href="' . esc_url($item->url) . '">';
      $output .= esc_html($item->title) . $count;
      $output .= '</a>';
      $output .= '</li>';
   }

   $output .= '</ul>';
   $output .= '</nav>';
   $output .= '</div>';

   return $output;
}

// Функция для проверки активного пункта меню
function is_menu_item_active($menu_item, $current_url)
{
   $menu_url = trailingslashit($menu_item->url);

   // Прямое сравнение URL
   if ($menu_url == $current_url) {
      return true;
   }

   // Для таксономий (категории, метки)
   if ($menu_item->type === 'taxonomy') {
      if (is_tax($menu_item->object, $menu_item->object_id) || is_category($menu_item->object_id) || is_tag($menu_item->object_id)) {
         return true;
      }
   }

   // Для страниц и записей
   if ($menu_item->type === 'post_type') {
      if (is_singular($menu_item->object) && get_queried_object_id() == $menu_item->object_id) {
         return true;
      }
      if (is_post_type_archive($menu_item->object) && $menu_item->object_id == 0) {
         return true;
      }
   }

   // Для произвольных ссылок с параметрами (как в примере ?category=)
   if ($menu_item->type === 'custom') {
      // Если URL содержит параметры, сравниваем текущую страницу
      if (strpos($menu_item->url, '?') !== false) {
         $current_params = $_GET;
         $menu_url_parts = parse_url($menu_item->url);
         if (isset($menu_url_parts['query'])) {
            parse_str($menu_url_parts['query'], $menu_params);

            // Проверяем совпадают ли параметры
            $params_match = true;
            foreach ($menu_params as $key => $value) {
               if (!isset($current_params[$key]) || $current_params[$key] != $value) {
                  $params_match = false;
                  break;
               }
            }

            if ($params_match) {
               return true;
            }
         }
      }
   }

   return false;
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

function display_post_meta($args = array())
{
   $defaults = array(
      'wrapper_class' => 'post-meta d-flex',
      'show_date' => true,
      'show_author' => true,
      'show_comments' => true,
      'comments_class' => 'ms-auto',
      'comments_show_text' => true,
      'date_format' => 'd.m.Y',
      'author_text' => __('Author %s', 'horizons')
   );

   $args = wp_parse_args($args, $defaults);

   echo '<ul class="' . esc_attr($args['wrapper_class']) . '">';

   // Date
   if ($args['show_date']) {
      echo '<li class="post-date">';
      echo '<i class="uil uil-calendar-alt"></i>';
      echo '<span>' . get_the_date($args['date_format']) . '</span>';
      echo '</li>';
   }

   // Author
   if ($args['show_author']) {
      echo '<li class="post-author">';
      echo '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">';
      echo '<i class="uil uil-user"></i>';
      echo '<span>' . sprintf($args['author_text'], get_the_author()) . '</span>';
      echo '</a>';
      echo '</li>';
   }

   // Comments (only if open)
   if ($args['show_comments'] && comments_open()) {
      // Определяем ссылку в зависимости от типа страницы
      $comments_link = is_single() ? '#comments' : get_comments_link();
      $comments_class = is_single() ? 'scroll' : '';

      echo '<li class="post-comments ' . esc_attr($args['comments_class']) . '">';
      echo '<a href="' . esc_url($comments_link) . '" class="' . esc_attr($comments_class) . '">';
      echo '<i class="uil uil-comment"></i>';
      echo $args['comments_show_text'] ? get_comments_number_text() : get_comments_number();
      echo '</a>';
      echo '</li>';
   }

   echo '</ul>';
}


// Добавляем метаполе чекбокса для записей
function add_blog_banner_meta_box()
{
   add_meta_box(
      'blog_banner_meta',
      'Отображать на главной блога',
      'blog_banner_meta_callback',
      'post',
      'side',
      'high'
   );
}
add_action('add_meta_boxes', 'add_blog_banner_meta_box');

// Колбек функция для вывода чекбокса
function blog_banner_meta_callback($post)
{
   wp_nonce_field('blog_banner_meta_nonce', 'blog_banner_nonce');
   $value = get_post_meta($post->ID, '_show_on_blog_home', true);
?>
   <label for="show_on_blog_home">
      <input type="checkbox" id="show_on_blog_home" name="show_on_blog_home" value="1" <?php checked($value, '1'); ?> />
      Выводить баннер на главной блога
   </label>
   <?php
}

// Сохраняем значение чекбокса
function save_blog_banner_meta($post_id)
{
   if (!isset($_POST['blog_banner_nonce']) || !wp_verify_nonce($_POST['blog_banner_nonce'], 'blog_banner_meta_nonce')) {
      return;
   }

   if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
   }

   if (!current_user_can('edit_post', $post_id)) {
      return;
   }

   $show_banner = isset($_POST['show_on_blog_home']) ? '1' : '0';
   update_post_meta($post_id, '_show_on_blog_home', $show_banner);
}
add_action('save_post', 'save_blog_banner_meta');



// Функция для вывода баннера на главной блога
function display_blog_banner()
{
   // Получаем все записи с включенным чекбоксом
   $banner_posts = get_posts(array(
      'post_type' => 'post',
      'meta_key' => '_show_on_blog_home',
      'meta_value' => '1',
      'posts_per_page' => -1,
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'DESC'
   ));

   if (empty($banner_posts)) {
      return;
   }

   // Если запись одна - выводим обычный баннер
   if (count($banner_posts) === 1) {
      $post = $banner_posts[0];
      setup_postdata($post);

      // Получаем данные поста
      $post_id = $post->ID;
      $title = get_the_title($post_id);
      $excerpt = wp_trim_words(get_the_excerpt($post_id), 10, '...');
      $permalink = get_permalink($post_id);

      // Получаем изображение
      $image_url = get_the_post_thumbnail_url($post_id, 'large');
      if (!$image_url) {
         $image_url = get_stylesheet_directory_uri() . '/assets/images/partner-banner.jpg';
      }
   ?>

      <section class="p-5 p-md-10 mt-8 mt-md-12 wrapper swiper-hero-blog image-wrapper bg-image bg-overlay bg-overlay-400 bg-full" data-image-src="<?php echo esc_url($image_url); ?>">
         <div class="container h-100">
            <div class="row h-100">
               <div class="col-lg-8 col-xl-7 col-xxl-6 text-center text-lg-start justify-content-center align-self-center align-items-start">

                  <?php
                  display_post_meta(array(
                     'wrapper_class' => 'post-meta d-md-flex mt-3',
                     'comments_class' => '',
                     'comments_show_text' => false
                  ));
                  ?>


                  <h2 class=" mb-4 text-white"><?php echo esc_html($title); ?></h2>

                  <?php if ($excerpt): ?>
                     <p class="body-l-r mb-4 text-white"><?php echo esc_html($excerpt); ?></p>
                  <?php endif; ?>

                  <a href="<?php echo esc_url($permalink); ?>" class="hover-4 link-body label-s text-sub-white"><?php echo __('Read more', 'horizons'); ?></a>
               </div>
               <!--/column -->
            </div>
            <!--/.row -->
         </div>
         <!--/.container -->
      </section>

   <?php
      wp_reset_postdata();
   } else {
      // Если записей несколько - выводим Swiper
   ?>

      <div class="swiper-container dots-over swiper-hero-blog mt-8 mt-md-12" data-margin="15" data-autoplay="false" data-autoplaytime="5000" data-nav="true" data-dots="true" data-items="1" data-items-xxl="1">
         <div class="swiper">
            <div class="swiper-wrapper">
               <?php foreach ($banner_posts as $post):
                  setup_postdata($post);

                  // Получаем данные поста
                  $post_id = $post->ID;
                  $title = get_the_title($post_id);
                  $excerpt = wp_trim_words(get_the_excerpt($post_id), 10, '...');
                  $permalink = get_permalink($post_id);

                  // Получаем изображение
                  $image_url = get_the_post_thumbnail_url($post_id, 'large');
                  if (!$image_url) {
                     $image_url = get_stylesheet_directory_uri() . '/assets/images/partner-banner.jpg';
                  }
               ?>

                  <div class="swiper-slide p-5 p-md-10 h-100 wrapper image-wrapper bg-image bg-overlay bg-overlay-400 bg-full" data-image-src="<?php echo esc_url($image_url); ?>">
                     <div class="container h-100">
                        <div class="row h-100">
                           <div class="col-12 text-center text-lg-start justify-content-center align-self-center align-items-start">

                              <?php
                              display_post_meta(array(
                                 'wrapper_class' => 'post-meta d-md-flex mt-3',
                                 'comments_class' => '',
                                 'comments_show_text' => false
                              ));
                              ?>


                              <h2 class=" mb-4 text-white"><?php echo esc_html($title); ?></h2>

                              <?php if ($excerpt): ?>
                                 <p class="body-l-r mb-4 text-white"><?php echo esc_html($excerpt); ?></p>
                              <?php endif; ?>


                              <a href="<?php echo esc_url($permalink); ?>" class="hover-4 link-body label-s text-sub-white"><?php echo __('Read more', 'horizons'); ?></a>




                           </div>
                           <!--/column -->
                        </div>
                        <!--/.row -->
                     </div>
                     <!--/.container -->
                  </div>
                  <!--/.swiper-slide -->

               <?php endforeach; ?>
            </div>
            <!--/.swiper-wrapper -->
         </div>
         <!-- /.swiper -->
      </div>
      <!-- /.swiper-container -->

<?php
      wp_reset_postdata();
   }
}

// Вешаем функцию на хук
add_action('blog_banner', 'display_blog_banner');
