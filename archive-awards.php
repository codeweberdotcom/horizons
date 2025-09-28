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
?>

<section class="wrapper bg-white practice-section">
   <div class="container py-8 py-md-12">
      <div class="row gx-lg-8 gx-xl-12">

         <?php get_sidebar('left'); ?>

         <div class="grid grid-view projects-masonry shop mb-13 py-12 <?php echo $content_class; ?>">
            <div class="row  g-3 isotope">

               <?php if (have_posts()) : ?>
                  <?php while (have_posts()) : the_post(); ?>

                     <?php
                     // Получаем данные из метаполей
                     $award_organization = get_post_meta(get_the_ID(), '_award_organization', true);
                     $award_url = get_post_meta(get_the_ID(), '_award_url', true);
                     $award_partners = get_post_meta(get_the_ID(), '_award_partners', true);


                     $formatted_date = get_the_date('F Y');


                     // Получаем категории
                     $categories = get_the_terms(get_the_ID(), 'award_category');
                     $category_text = '';
                     if ($categories && !is_wp_error($categories)) {
                        $category_names = array();
                        foreach ($categories as $category) {
                           $category_names[] = $category->name;
                        }
                        $category_text = implode(', ', $category_names);
                     }


                     // Получаем партнеров
                     $partners_text = '';
                     if ($award_partners && is_array($award_partners) && !empty($award_partners)) {
                        $partners_count = count($award_partners);

                        if ($partners_count > 1) {
                           $partners_text = __('Horizons', 'horizons');
                        } else {
                           $partner_names = array();
                           foreach ($award_partners as $partner_id) {
                              $partner = get_post($partner_id);
                              if ($partner) {
                                 $partner_names[] = $partner->post_title;
                              }
                           }
                           $partners_text = implode(', ', $partner_names);
                        }
                     }
                     ?>

                     <div class="project item col-md-6 col-xl-6">
                        <figure class="overlay overlay-3 hover-scale card">
                           <a href="<?php the_permalink(); ?>">
                              <?php if (has_post_thumbnail()) : ?>
                                 <?php the_post_thumbnail('large', array(
                                    'class' => 'img-fluid w-100',
                                    'alt' => get_the_title()
                                 )); ?>
                              <?php else : ?>
                                 <img src="<?php echo get_template_directory_uri(); ?>/assets/img/photos/p6.jpg"
                                    srcset="<?php echo get_template_directory_uri(); ?>/assets/img/photos/p6@2x.jpg 2x"
                                    alt="<?php the_title_attribute(); ?>" class="img-fluid w-100" />
                              <?php endif; ?>
                           </a>

                           <figcaption>
                              <h2 class="from-left body-l-r mb-3"><?php the_title(); ?></h2>
                              <div class="award-desc-group">
                                 <?php if ($category_text) : ?>
                                    <span class="from-left mb-1  me-3 text-square-before label-u"><?php echo esc_html($category_text); ?></span>
                                 <?php endif; ?>

                                 <?php if ($award_organization) : ?>
                                    <span class="from-left mb-1  me-3 text-square-before label-u"><?php echo esc_html($award_organization); ?></span>
                                 <?php endif; ?>

                                 <div class="d-flex flex-wrap">
                                    <?php if ($formatted_date) : ?>
                                       <span class="from-left mb-1 me-3  text-square-before label-u">
                                          <?php echo esc_html($formatted_date); ?>
                                       </span>
                                    <?php endif; ?>

                                    <?php if ($partners_text) : ?>
                                       <span class="from-left mb-1  me-3 text-square-before label-u">
                                          <?php echo esc_html($partners_text); ?>
                                       </span>
                                    <?php endif; ?>
                                 </div>
                              </div>
                           </figcaption>
                        </figure>
                     </div>
                     <!-- /.item -->

                  <?php endwhile; ?>
               <?php else : ?>

                  <div class="col-12">
                     <div class="alert alert-info text-center">
                        <p><?php _e('No awards found.', 'horizons'); ?></p>
                     </div>
                  </div>

               <?php endif; ?>

            </div>
            <!-- /.row -->
         </div>
         <!-- /.grid -->

         <?php get_sidebar('right'); ?>
         <!-- #sidebar-right -->
      </div>

   </div>
   <!-- /.container -->
</section>

<?php get_footer(); ?>