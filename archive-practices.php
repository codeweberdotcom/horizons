<?php get_header(); ?>
<?php get_pageheader(); ?>
<?php
$post_type = get_post_type();
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
      $image_order = ($counter % 2 == 0) ? 'order-lg-2' : 'order-lg-1 ';
      $content_order = ($counter % 2 == 0) ? 'order-lg-1 p-8 p-md-12' : 'order-lg-2 p-8 p-md-12';

      $color = get_term_meta($category->term_id, 'practice_category_color', true);
      $color_class = $color ? 'bg-' . $color : '';
      $category_description = $category->description;

      $category_image_id = get_term_meta($category->term_id, 'practice_category_image', true);

      $posts = new WP_Query(array(
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
?>
      <section id="content-wrapper" class="wrapper">
         <div class="container">
            <div class="row d-flex align-items-start g-0">
               <div class="col-lg-6 position-lg-sticky <?php echo esc_attr($image_order); ?>" style="top: 4rem; height: calc(100vh - 100px);">
                  <?php
                  if ($category_image_id) { ?>
                     <figure class="practice-category-image-wrapper" style="height: 100%;">
                        <?php
                        $category_image_url = wp_get_attachment_image_src($category_image_id, 'codeweber_staff_800');
                        $image_url = $category_image_url[0];
                        $category_image = wp_get_attachment_image($category_image_id, 'practice_category_image', false, array(
                           'class' => 'category-image h-100 w-100',
                           'style' => 'object-fit: cover;',
                           'alt' => $category->name
                        ));
                        echo $category_image; ?>
                     </figure>
                  <?php
                  }
                  ?>
               </div>
               <!-- /column -->
               <div class="col-lg-6 ms-auto bg-white <?php echo esc_attr($content_order); ?>">
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
                  <?php if ($posts->have_posts()) : ?>
                     <div class="practice-posts-list mb-5">
                        <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                           <?php $is_last = ($posts->current_post + 1) === $posts->post_count; ?>
                           <article id="post-<?php the_ID(); ?>" <?php post_class('practice-item mb-0'); ?>>
                              <a class="h4 text-dark py-3 pe-2 d-flex justify-content-between align-items-center mb-0 service-link <?php echo !$is_last ? 'border-bottom' : ''; ?>" href="<?php the_permalink(); ?>">
                                 <span class="d-flex align-items-center">
                                    <div class="brand-square-sm me-3"></div>
                                    <?php the_title(); ?>
                                 </span>
                                 <i class="uil uil-angle-right"></i>
                              </a>
                           </article>
                        <?php endwhile; ?>
                     </div>

                     <div class="card shadow border-0">
                        <div class="row g-0">
                           <div class="col-md-12">
                              <div class="card-body bg-dusty-navy h-100">
                                 <p class="text-line-before label-u text-sub-white">Связаться с нами</p>
                                 <div class="h3 text-white mb-6">Получить консультацию <br> нашего специалиста</div>
                                 <a href="#" class="btn btn-neutral-50 has-ripple btn-lg w-100" data-ripple-initialized="true">Отправить сообщение</a>
                              </div>
                           </div>
                        </div>
                     </div>

                  <?php else : ?>
                     <p class="no-practices"><?php _e('No practices found in this category.', 'text-domain'); ?></p>
                  <?php endif; ?>
               </div>
               <!-- /column -->
               <?php wp_reset_postdata(); ?>
            </div>
         </div>
      </section> <!-- #content-wrapper -->
   <?php endforeach; ?>
<?php else : ?>
   <p class="no-categories"><?php _e('No categories found.', 'text-domain'); ?></p>
<?php endif; ?>

<?php get_footer(); ?>