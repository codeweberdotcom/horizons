<?php
global $opt_name, $wp_query, $practices_template_rendered, $disable_pagination;
if (empty($opt_name)) {
   $opt_name = 'redux_demo';
}

// Проверяем, был ли уже выведен контент (шаблон вызывается внутри цикла)
if (!empty($practices_template_rendered)) {
   return; // Выходим, если контент уже был выведен
}

// Отключаем пагинацию для этого шаблона
$disable_pagination = true;

// Сохраняем глобальный запрос, так как шаблон вызывается внутри цикла
$original_query = $wp_query;
$original_post = isset($GLOBALS['post']) ? $GLOBALS['post'] : null;

$post_type = universal_get_post_type();
$post_type_lc = strtolower($post_type);
$sidebar_position = Redux::get_option($opt_name, 'sidebar_position_archive_' . $post_type);
$pageheader_name = Redux::get_option($opt_name, 'global_page_header_model');
$content_class = ($sidebar_position === 'none') ? 'col-12' : 'col-md-8';
?>

<?php
// Получаем все категории таксономии practice_category
$categories = get_terms(array(
   'taxonomy' => 'practice_category',
   'hide_empty' => true,
   'orderby' => 'name',
   'order' => 'ASC'
));

if ($categories && !is_wp_error($categories)) :
   $counter = 0;
   foreach ($categories as $category) :
      $counter++;
      // Определяем позицию для зеркального отображения
      // Нечетные (1, 3, 5...): изображение слева, контент справа
      // Четные (2, 4, 6...): изображение справа, контент слева
      $is_even = ($counter % 2 == 0);
      $image_position = $is_even ? 'end-0' : 'start-0';
      $content_offset = $is_even ? 'offset-lg-0' : 'offset-lg-6';
      $content_padding = $is_even ? 'pe-lg-12 pe-xxl-16 ps-lg-0' : 'ps-lg-12 ps-xxl-16 pe-lg-0';
      
      $color = get_term_meta($category->term_id, 'practice_category_color', true);
      $color_class = $color ? 'bg-' . $color : '';
      $category_description = $category->description;

      $category_image_id = get_term_meta($category->term_id, 'practice_category_image', true);

      $practice_query = new WP_Query(array(
         'post_type' => 'practices',
         'posts_per_page' => -1,
         'tax_query' => array(
            array(
               'taxonomy' => 'practice_category',
               'field' => 'slug',
               'terms' => $category->slug
            )
         ),
         'orderby' => 'title',
         'order' => 'ASC'
      ));

      // Получаем URL изображения категории
      $category_image_url = '';
      if ($category_image_id) {
         $image_data = wp_get_attachment_image_src($category_image_id, 'full');
         $category_image_url = $image_data ? $image_data[0] : '';
      }

      // Получаем видео для категории (если есть)
      $category_video = get_term_meta($category->term_id, 'practice_category_video', true);
?>
      <section class="wrapper position-relative min-vh-60 d-lg-flex align-items-center">
         <?php if ($category_image_id && $category_image_url) : ?>
            <div class="col-lg-6 position-lg-absolute top-0 <?php echo esc_attr($image_position); ?> h-100 d-flex flex-column justify-content-start">
               <div class="image-wrapper bg-image bg-cover h-100 w-100 position-sticky top-0 vh-100" data-image-src="<?php echo esc_url($category_image_url); ?>" style="background-image: url('<?php echo esc_url($category_image_url); ?>');">
                  <?php if ($category_video) : ?>
                     <a href="<?php echo esc_url($category_video); ?>" class="btn btn-circle btn-primary btn-play ripple mx-auto position-absolute d-lg-none" style="top:50%; left: 50%; transform: translate(-50%,-50%); z-index:3;" data-glightbox data-gallery="mobile-video-<?php echo esc_attr($category->slug); ?>"><i class="icn-caret-right"></i></a>
                  <?php endif; ?>
               </div>
            </div>
         <?php endif; ?>
         <!--/column -->
         <div class="container position-relative" data-cue="fadeIn" data-delay="600">
            <?php if ($category_video) : ?>
               <a href="<?php echo esc_url($category_video); ?>" class="btn btn-circle btn-primary btn-play ripple mx-auto position-absolute d-none d-lg-flex" style="top:50%; left: 50%; transform: translate(-50%,-50%); z-index:3;" data-glightbox data-gallery="desktop-video-<?php echo esc_attr($category->slug); ?>"><i class="icn-caret-right"></i></a>
            <?php endif; ?>
            <div class="row gx-0">
               <div class="col-lg-6 <?php echo esc_attr($content_offset); ?>">
                  <div class="py-12 py-lg-16 py-xxl-14 <?php echo esc_attr($content_padding); ?> position-relative" data-cues="slideInDown" data-group="page-title">
                     <div class="pe-none mb-5">
                        <div class="brand-square-md <?php echo esc_attr($color_class); ?>"></div>
                     </div>
                     <h2 class="h2 mb-5"><?php echo esc_html($category->name); ?></h2>
                     <?php if (!empty($category_description)) : ?>
                        <div class="practice-category-description body-l-l mb-5">
                           <?php echo wp_kses_post($category_description); ?>
                        </div>
                     <?php endif; ?>
                     <div class="text-line-after label-u mb-3"><?php echo __('Practices', 'horizons'); ?></div>
                     <?php if ($practice_query->have_posts()) : ?>
                        <div class="practice-posts-list">
                           <?php while ($practice_query->have_posts()) : $practice_query->the_post(); ?>
                              <?php $is_last = ($practice_query->current_post + 1) === $practice_query->post_count; ?>
                              <article id="post-<?php the_ID(); ?>" <?php post_class('practice-item mb-0'); ?>>
                                 <a class="h4 text-dark py-3 pe-2 d-flex justify-content-between align-items-center mb-0 service-link <?php echo !$is_last ? 'border-bottom' : ''; ?>" href="<?php the_permalink(); ?>">
                                    <span class="d-flex align-items-center">
                                       <div class="brand-square-sm me-3 flex-shrink-0"></div>
                                       <?php the_title(); ?>
                                    </span>
                                    <i class="uil uil-angle-right"></i>
                                 </a>
                              </article>
                           <?php endwhile; ?>
                        </div>

                     <?php else : ?>
                        <p class="no-practices"><?php _e('No practices found in this category.', 'horizons'); ?></p>
                     <?php endif; ?>
                  </div>
               </div>
               <!-- /column -->
            </div>
            <!--/.row -->
         </div>
         <!-- /.container -->
         <?php wp_reset_postdata(); ?>
      </section>
      <!-- /section -->
   <?php 
   endforeach; 
   // Восстанавливаем глобальный запрос после вывода всего контента
   wp_reset_query();
   // Помечаем, что контент был выведен
   $practices_template_rendered = true;
   ?>
<?php else : ?>
   <p class="no-categories"><?php _e('No categories found.', 'horizons'); ?></p>
   <?php 
   // Помечаем, что контент был выведен даже если категорий нет
   $practices_template_rendered = true;
   ?>
<?php endif; ?>
