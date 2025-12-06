<?php get_header(); ?>
<?php get_pageheader(); ?>
<?php

$post_type = universal_get_post_type();
$post_type_lc = strtolower($post_type);
$sidebar_position = Redux::get_option($opt_name, 'sidebar_position_archive_' . $post_type);

$content_class = ($sidebar_position === 'none') ? 'col-12' : 'col-md-8';
$pageheader_name = Redux::get_option($opt_name, 'global_page_header_model');

$archive_pageheader_id = Redux::get_option($opt_name, 'archive_page_header_select_' . $post_type);
$show_universal_title = ($pageheader_name === '1' && $archive_pageheader_id !== 'disabled');

// Получаем текущую категорию
$current_category = get_queried_object();
$category_name = $current_category ? $current_category->name : '';
$category_description = $current_category ? $current_category->description : '';
?>

<section id="content-wrapper" class="wrapper">
   <div class="container">

      <?php do_action('blog_banner'); ?>
      <div class="row gx-lg-8 gx-xl-12">
         <?php get_sidebar('left'); ?>
         <!-- #sidebar-left -->

         <div id="loop-wrapper" class="<?php echo $content_class; ?> py-14">
            <div class="blog grid grid-view">
               <div class="row isotope gx-md-8 gy-8 mb-8">


                  <?php
                  $templateloop = Redux::get_option($opt_name, 'archive_template_select_' . $post_type);
                  $template_file = "templates/archives/{$post_type_lc}/{$templateloop}.php";

                  if (have_posts()) :
                     while (have_posts()) :
                        the_post();
                  ?>
                        <article class="item post col-md-12">
                           <div class="post-col d-md-flex shadow-lg">
                              <figure class="post-figure overlay overlay-1 hover-scale rounded col-md-4">
                                 <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                       <?php
                                       // Используем значение по умолчанию если $atts не определен
                                       $image_size = isset($atts['image_size']) ? $atts['image_size'] : 'codeweber_staff';
                                       $thumbnail = get_the_post_thumbnail(get_the_ID(), $image_size, array(
                                          'decoding' => 'async',
                                          'alt' => esc_attr(get_the_title()),
                                          'class' => 'post-image swiper-lazy'
                                       ));
                                       echo $thumbnail;
                                       ?>
                                    <?php else : ?>
                                       <img decoding="async"
                                          src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/placeholder_600x600.jpg'); ?>"
                                          alt="<?php echo esc_attr(get_the_title()); ?>"
                                          class="post-image swiper-lazy" />
                                    <?php endif; ?>
                                    <span class="bg"></span>
                                 </a>
                                 <figcaption>
                                    <div class="from-top mb-0 label-u"><?php echo __('Read', 'horizons'); ?></div>
                                 </figcaption>
                              </figure>

                              <div class="post-body p-8 col-md-8 align-self-center">
                                 <a href="<?php the_permalink(); ?>">
                                    <h2 class="h4 post-title"><?php the_title(); ?></h2>
                                 </a>

                                 <div class="body-l-l mb-4 post-excerpt">
                                    <?php
                                    $excerpt = get_the_excerpt();
                                    if (empty($excerpt)) {
                                       $excerpt = get_the_content();
                                    }
                                    // Используем значение по умолчанию если $atts не определен
                                    $excerpt_length = isset($atts['excerpt_length']) ? intval($atts['excerpt_length']) : 10;
                                    echo wp_trim_words($excerpt, $excerpt_length, '...');
                                    ?>
                                 </div>
                                 <hr class="my-3">
                                 <?php
                                 display_post_meta(array(
                                    'wrapper_class' => 'post-meta d-md-flex mb-3',
                                    'comments_class' => 'ms-auto',
                                    'comments_show_text' => true
                                 ));
                                 ?>
                              </div>
                              <!--/.post-body -->
                           </div>
                           <!-- /.post-col -->
                        </article>
                        <!-- /article -->
                  <?php
                     endwhile;
                  else :
                     get_template_part('templates/content/loop', 'none');
                  endif;
                  ?>
               </div>
               <?php codeweber_posts_pagination(); ?>
            </div>
         </div> <!-- #loop-wrapper -->

         <?php get_sidebar('right'); ?>
         <!-- #sidebar-right -->
      </div>
   </div>
</section> <!-- #content-wrapper -->

<?php get_footer(); ?>

