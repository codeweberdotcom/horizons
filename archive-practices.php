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
      $content_order = ($counter % 2 == 0) ? 'order-lg-1 pe-md-14 py-14' : 'order-lg-2 ps-md-14 py-14';

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
               <div class="col-lg-6 position-lg-sticky <?php echo esc_attr($image_order); ?>" style="top: 4rem;">
                  <?php
                  if ($category_image_id) { ?>
                     <figure class="">
                        <?php
                        $category_image = wp_get_attachment_image($category_image_id, 'practice_category_image', false, array(
                           'class' => 'category-image',
                           'alt' => $category->name
                        ));
                        echo $category_image; ?>
                     </figure>
                  <?php
                  }
                  ?>
               </div>
               <!-- /column -->
               <div class="col-lg-6 ms-auto bg-white  <?php echo esc_attr($content_order); ?>">
                  <div class="pe-none mb-5">
                     <div class="brand-square-md <?php echo esc_attr($color_class); ?>"></div>
                  </div>
                  <h3 class="h2 mb-5"><?php echo esc_html($category->name); ?></h3>
                  <?php if (!empty($category_description)) : ?>
                     <div class="practice-category-description body-l-l mb-5">
                        <?php echo wp_kses_post($category_description); ?>
                     </div>
                  <?php endif; ?>
                  <div class="text-line-after label-u"><?php echo __('Practices', 'horizons'); ?></div>
                  <?php if ($posts->have_posts()) : ?>
                     <div class="practice-posts-list">
                        <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                           <article id="post-<?php the_ID(); ?>" <?php post_class('practice-item'); ?>>
                              <a class="" href="<?php the_permalink(); ?>">
                                 <div class="card-body border-bottom d-flex flex-row p-3 ps-4 align-items-center justify-content-between">
                                    <h2 href="#" class="h4 mb-0"><?php the_title(); ?></h2>
                                    <i class="uil uil-angle-right fs-22"></i>
                                 </div>
                              </a>
                           </article>

                        <?php endwhile; ?>
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