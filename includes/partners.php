<?php

// Добавляем баннеры через хук
add_action('before_single_content', 'add_custom_single_banner_partners', 10, 1);

function add_custom_single_banner_partners($post_type)
{
   global $post;

   if ($post_type === 'partners') {

      // Получаем данные
      $name = get_post_meta($post->ID, '_partners_name', true);
      $surname = get_post_meta($post->ID, '_partners_surname', true);
      $full_position = get_post_meta($post->ID, '_partners_full_position', true);
      $countries = wp_get_post_terms($post->ID, 'partner_country');
      $short_description = get_post_meta($post->ID, '_partners_short_description', true);
      $language_skills = wp_get_post_terms($post->ID, 'partner_language');
      $email = get_post_meta($post->ID, '_partners_email', true);
      $phone = get_post_meta($post->ID, '_partners_phone', true);
      $location = get_post_meta($post->ID, '_partners_location', true);
      $company = get_post_meta($post->ID, '_partners_company', true);
      $website = get_post_meta($post->ID, '_partners_website', true);
      $linkedin = get_post_meta($post->ID, '_partners_linkedin', true);
      $regions = wp_get_post_terms($post->ID, 'partner_region');



      // Получаем thumbnail разными способами
      $thumbnail_url = '';
      if (has_post_thumbnail($post->ID)) {
         $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'codeweber_staff_800');
      }

      // Запасное изображение
      $background_image = $thumbnail_url ?: get_template_directory_uri() . '/assets/images/default-staff.jpg';
?>

      <section class="partners-banner">
         <div class="col-12">
            <div class="row  wrapper g-0">
               <div class="col-md-12 col-xl-5 ">
                  <div class="card h-100 overflow-hidden bg-dusty-navy">
                     <figure class="bg-cover wrapper image-wrapper bg-image h-100" data-image-src="<?php echo esc_url($background_image); ?>"> </figure>
                  </div>
               </div>
               <!--/column -->
               <div class="col-12 col-xl-7 order-lg-2 ">
                  <div class="card h-100 bg-dusty-navy">
                     <div class="p-md-15 card-body align-content-center p-8">
                        <?php if (!empty($full_position)) : ?>
                           <div class="text-line-before label-u mb-2 text-white">
                              <?php echo esc_html($full_position); ?>
                           </div>
                        <?php endif; ?>
                        <h1 class="h1 mb-1 text-white mt-md-3 mb-4">
                           <?php echo $name; ?><span class="text-uppercase"> <?php echo $surname; ?></span>
                           </h2>
                           <?php if (!empty($short_description)) : ?>
                              <blockquote class="icon body-l-r text-white mb-4">
                                 <?php echo esc_html($short_description); ?>
                              </blockquote>
                           <?php endif; ?>
                           <?php if (!empty($language_skills) || !empty($regions)) : ?>
                              <table class="w-100 text-white label-u mb-4">
                                 <tbody>
                                    <?php if (!empty($language_skills)) : ?>
                                       <tr>
                                          <td class="pb-1 pe-3 align-text-top">
                                             <?php
                                             if (!empty($language_skills) && !is_wp_error($language_skills)) {
                                                echo '<div class="text-square-before">';
                                                echo __('Languages', 'horizons') . ':';
                                                echo '</div>';
                                             }
                                             ?>
                                          </td>
                                          <td class="pb-1">
                                             <?php
                                             if (!empty($language_skills) && !is_wp_error($language_skills)) {
                                                $language_names = array();
                                                foreach ($language_skills as $language) {
                                                   $language_names[] = $language->name;
                                                }
                                                echo implode(', ', $language_names);
                                             }
                                             ?>
                                          </td>
                                       </tr>
                                    <?php endif; ?>



                                    <?php if (!empty($countries)) : ?>
                                       <tr>
                                          <td class="pb-1 pe-3 align-text-top">
                                             <div class="text-square-before">
                                                <?php echo __('Continents', 'horizons') ?>:
                                             </div>
                                          </td>
                                          <td class="pb-1">
                                             <?php

                                             if (!empty($regions) && !is_wp_error($regions)) {
                                                $region_names = array();
                                                foreach ($regions as $region) {
                                                   $region_names[] = $region->name;
                                                }
                                                echo implode(', ', $region_names);
                                             }
                                             ?>
                                          </td>
                                       </tr>
                                    <?php endif; ?>

                                    <?php if (!empty($countries)) : ?>
                                       <tr>
                                          <td class="pb-1 pe-3 align-text-top">
                                             <div class="text-square-before">
                                                <?php echo __('Countries', 'horizons') ?>:
                                             </div>
                                          </td>
                                          <td class="pb-1">
                                             <?php
                                             if (!empty($countries) && !is_wp_error($countries)) {
                                                $country_names = array();
                                                foreach ($countries as $country) {
                                                   $country_names[] = $country->name;
                                                }
                                                echo implode(', ', $country_names);
                                             }
                                             ?>
                                          </td>
                                       </tr>
                                    <?php endif; ?>
                                 </tbody>
                              </table>
                           <?php endif; ?>
                           <div class="text-line-neutral-after text-sub-white label-u mb-3"><?= __('Get in touch', 'horizons'); ?>
                           </div>
                           <div class="group_contact_wrapper group_contact_wrapper d-md-flex flex-md-row justify-content-between align-items-end">
                              <div class="group_contact">
                                 <?php if (!empty($company)) : ?>
                                    <div class="mt-0">
                                       <?php if (!empty($website)) : ?>
                                          <a href="<?php echo esc_url($website); ?>" title="<?= __('Website', 'horizons'); ?>" target="_blank" rel="noopener noreferrer" class="label-s hover-6 text-white d-inline-flex align-items-center">
                                             <i class="uil uil uil-building fs-18 text-primary me-1"></i><?php echo esc_html($company); ?>
                                          </a>
                                       <?php else : ?>
                                          <div class="label-s text-white d-inline-flex align-items-center" title="<?= __('Address', 'horizons'); ?>"><i class="uil uil uil-building fs-18 text-primary me-1"></i><?php echo esc_html($company); ?></div>
                                       <?php endif; ?>
                                    </div>
                                 <?php endif; ?>

                                 <?php if (!empty($location)) : ?>
                                    <div class="mt-0">
                                       <div class="label-s text-white d-inline-flex align-items-center" title="<?= __('Address', 'horizons'); ?>">
                                          <i class="uil uil-location-point fs-18 text-primary me-1"></i><?php echo esc_html($location); ?>
                                       </div>
                                    </div>
                                 <?php endif; ?>

                                 <?php if (!empty($phone)) : ?>
                                    <div class="mt-0">
                                       <a href="tell:<?php echo esc_attr($phone); ?>" title="<?= __('Phone', 'horizons'); ?>"
                                          class="label-s hover-6 text-white d-inline-flex align-items-center">
                                          <i class="uil uil-phone-alt fs-18 text-primary me-1"></i><?php echo esc_html($phone); ?>
                                       </a>
                                    </div>
                                 <?php endif; ?>
                              </div>

                              <div class="d-flex mt-3">
                                 <?php if (!empty($linkedin)) : ?>
                                    <a href="<?php echo esc_attr($linkedin); ?>" class="btn btn-circle btn-outline-white btn-sm me-1"><i class="uil uil uil-linkedin"></i></a>
                                 <?php endif; ?>

                                 <?php if (!empty($email)) : ?>
                                    <a href="mailto:<?php echo esc_attr($email); ?>" class="btn btn-circle btn-outline-white btn-sm me-1"><i class="uil uil uil-envelope"></i></a>
                                 <?php endif; ?>

                                 <?php if (!empty($website)) : ?>
                                    <a href="<?php echo esc_url($website); ?>" class="btn btn-circle btn-outline-white btn-sm me-1"><i class="uil uil uil-link"></i></a>
                                 <?php endif; ?>
                              </div>
                           </div>
                     </div>
                     <!--/column -->

                  </div>
               </div>
            </div>
         </div>
         <!-- /.container -->
      </section>
   <?php
   }
}



function add_news_section_after_partner($post_type)
{
   if ($post_type === 'partners') {
      ob_start(); ?>
      <section class="wrapper blog-section" id="news">
         <div class="container pb-14 pb-md-16">
            <div class="row align-items-center mb-8">
               <div class="col-md-8">
                  <div class="text-line-before label-u mb-2"><?php echo esc_html__('News', 'horizons'); ?></div>
                  <h2 class="h2"><?php echo esc_html__('Related news', 'horizons'); ?></h2>
               </div>
               <div class="col text-md-end">
                  <a href="<?php echo esc_url(home_url('/blog')); ?>" class="hover-5 label-u right text-charcoal-blue mb-5">
                     <?php echo esc_html__('All News', 'horizons'); ?>
                  </a>
               </div>
            </div>
            <div class="row">
               <div class="col-12">
                  <?php echo do_shortcode('[blog_posts_slider posts_per_page="6" order="ASC" nav="true" dots="true" margin="24" items_xl="4" items_lg="3" items_md="2" items_sm="2" items_xs="1" items_xxs="1" image_size="codeweber_staff" excerpt_length="15"]'); ?>
               </div>
            </div>
         </div>
      </section>
<?php
      echo ob_get_clean();
   }
}
add_action('after_single_post', 'add_news_section_after_partner', 10, 1);
