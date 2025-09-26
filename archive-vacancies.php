<?php get_header(); ?>
<?php get_pageheader(); ?>
<?php

$post_type = 'get_post_type()';
$post_type_lc = strtolower($post_type);
$sidebar_position = Redux::get_option($opt_name, 'sidebar_position_archive_' . $post_type);
$pageheader_name = Redux::get_option($opt_name, 'global_page_header_model');
$content_class = ($sidebar_position === 'none') ? 'col-12' : 'col-md-8';
?>

<section class="wrapper bg-dusty-navy position-relative min-vh-60 d-lg-flex align-items-center">
   <div class="container position-relative" data-cue="fadeIn" data-delay="600">
      <div class="row gx-0">
         <div class="col-lg-6">
            <div class="py-12 py-lg-16 pe-lg-12 py-xxl-15 pe-xxl-15 ps-lg-0 position-relative" data-cues="slideInDown" data-group="page-title">
               <div class="text-line-before label-u mb-2 text-sub-white"><?php echo __('Careers', 'horizons'); ?></div>
               <h1 class="h1 mb-4 text-white mt-md-18"><?= esc_html(universal_title(false, false)); ?></h1>
               <?php
               echo the_subtitle($html_structure = '<p class="text-white body-l-r mb-0">%s</p>')
               ?>
            </div>
         </div>
         <!-- /column -->
      </div>
      <!--/.row -->
   </div>
   <!-- /.container -->
   <div class="col-lg-6 position-lg-absolute top-0 end-0 image-wrapper bg-image bg-cover h-100" data-image-src="/wp-content/uploads/2025/09/vertical-shot-modern-stairway-beautiful-white-building-scaled.jpg">
   </div>
   <!--/column -->
</section>
<!-- /section -->

<?php if (have_posts()) : ?>

   <section id="content-wrapper" class="wrapper">
      <div class="container py-12 py-md-14">
         <?php
         // Группируем вакансии по типам
         $vacancies_by_type = array();

         while (have_posts()) :
            the_post();
            $vacancy_array = get_vacancy_data_array($post->ID);
            $vacancy_types = $vacancy_array['vacancy_types'] ?? array();

            if (!empty($vacancy_types) && !is_wp_error($vacancy_types)) {
               foreach ($vacancy_types as $type) {
                  $type_slug = $type->slug;
                  if (!isset($vacancies_by_type[$type_slug])) {
                     $vacancies_by_type[$type_slug] = array(
                        'name' => $type->name,
                        'count' => 0,
                        'vacancies' => array()
                     );
                  }
                  $vacancies_by_type[$type_slug]['vacancies'][] = array(
                     'post' => $post,
                     'vacancy_array' => $vacancy_array
                  );
                  $vacancies_by_type[$type_slug]['count']++;
               }
            } else {
               if (!isset($vacancies_by_type['no-type'])) {
                  $vacancies_by_type['no-type'] = array(
                     'name' => __('Other Vacancies', 'horizons'),
                     'count' => 0,
                     'vacancies' => array()
                  );
               }
               $vacancies_by_type['no-type']['vacancies'][] = array(
                  'post' => $post,
                  'vacancy_array' => $vacancy_array
               );
               $vacancies_by_type['no-type']['count']++;
            }
         endwhile;

         // Выводим вакансии по группам
         foreach ($vacancies_by_type as $type_slug => $type_data) :
         ?>
            <div class="vacancy-type-group mb-12">
               <!-- Заголовок типа вакансии с количеством -->
               <div class="d-flex justify-content-between align-items-center mb-4">
                  <div class="text-line-before label-u"><?php echo esc_html($type_data['name']); ?></div>
               </div>

               <!-- Список вакансий этого типа -->
               <div class="row">
                  <?php foreach ($type_data['vacancies'] as $vacancy) :
                     $post = $vacancy['post'];
                     $vacancy_array = $vacancy['vacancy_array'];
                  ?>
                     <article id="<?php echo $post->post_name; ?>" class="col-12">
                        <a class="h4 text-dark py-3 pe-2 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-0 service-link border-bottom" href="<?php the_permalink(); ?>">
                           <!-- Заголовок вакансии - 100% ширины на мобильном -->
                           <span class="d-flex align-items-center me-3 w-100 w-md-auto mb-2 mb-md-0">
                              <div class="brand-square-sm me-3"></div>
                              <?php the_title(); ?>
                           </span>

                           <!-- Location и стрелка - 100% ширины на мобильном -->
                           <span class="d-flex align-items-center justify-content-between w-100 w-md-auto ms-md-3">
                              <span class="d-flex align-items-center">
                                 <?php if (!empty($vacancy_array['location'])): ?>
                                    <span class="ms-3 me-3 uil body-l-r"><?php echo $vacancy_array['location']; ?></span>
                                 <?php endif; ?>

                              </span>
                              <i class="uil uil-angle-right ms-2"></i>
                           </span>
                        </a>
                     </article>
                  <?php endforeach; ?>
               </div>
            </div>
         <?php endforeach; ?>

         <?php codeweber_posts_pagination(); ?>
      </div>
   </section>
<?php
else :
   get_template_part('templates/content/loop', 'none');
endif;
?>

<?php get_footer(); ?>