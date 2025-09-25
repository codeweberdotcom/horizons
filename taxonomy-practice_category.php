<?php get_header(); ?>
<?php get_pageheader(); ?>
<?php
$post_type = get_post_type();
$post_type_lc = strtolower($post_type);
$sidebar_position = Redux::get_option($opt_name, 'sidebar_position_archive_' . $post_type);
$pageheader_name = Redux::get_option($opt_name, 'global_page_header_model');


// Определяем класс колонки для контента
$content_class = ($sidebar_position === 'none') ? 'col-12' : 'col-md-8';
?>

<section class="wrapper bg-white">
   <div class="container py-8 py-md-12">

      <?php
      // Получаем все термины таксономии partner_region
      $countries = get_terms(array(
         'taxonomy' => 'partner_region',
         'hide_empty' => true,
      ));

      if (!empty($countries) && !is_wp_error($countries)) :
      ?>
         <div class="grid grid-view projects-masonry">
            <div class="isotope-filter filter mb-10">
               <ul>
                  <li><a class="filter-item active" data-filter="*"><?php echo __('All World', 'horizons'); ?></a></li>
                  <?php foreach ($countries as $country) : ?>
                     <li><a class="filter-item" data-filter=".<?php echo sanitize_title($country->slug); ?>"><?php echo $country->name; ?></a></li>
                  <?php endforeach; ?>
               </ul>
            </div>

            <div class="row gx-md-6 gy-6 isotope">
               <?php
               // Аргументы для WP_Query
               $args = array(
                  'post_type' => 'partners',
                  'posts_per_page' => -1,
                  'post_status' => 'publish',
                  'orderby' => 'date',  // Сортировка по дате создания
                  'order' => 'DESC'     // По умолчанию новые сначала
               );

               $partners_query = new WP_Query($args);

               if ($partners_query->have_posts()) :
                  while ($partners_query->have_posts()) : $partners_query->the_post();
                     // Получаем метаданные
                     $name = get_post_meta($post->ID, '_partners_name', true);
                     $surname = get_post_meta($post->ID, '_partners_surname', true);
                     $company = get_post_meta($post->ID, '_partners_company', true);
                     $position = get_post_meta($post->ID, '_partners_position', true);

                     // Полное имя
                     $full_name = trim($name . ' ' . $surname);
                     if (empty($full_name)) {
                        $full_name = get_the_title();
                     }

                     // Логика вывода подписи на фото: сначала позиция, если нет - компания
                     $caption_text = '';
                     if (!empty($position)) {
                        $caption_text = $position;
                     } elseif (!empty($company)) {
                        $caption_text = $company;
                     }

                     // Получаем страны для текущего партнера
                     $partner_countries = get_the_terms(get_the_ID(), 'partner_region');
                     $country_classes = '';
                     $country_names = array();

                     if ($partner_countries && !is_wp_error($partner_countries)) {
                        foreach ($partner_countries as $country) {
                           $country_classes .= ' ' . sanitize_title($country->slug);
                           $country_names[] = $country->name;
                        }
                     }

                     // Форматируем список стран
                     $countries_string = implode(', ', $country_names);

                     // Получаем изображение
                     $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'codeweber_staff_800');

                     // Если нет изображения, можно использовать заглушку
                     if (!$thumbnail_url) {
                        $thumbnail_url = get_template_directory_uri() . '/assets/img/placeholder.jpg';
                     }
               ?>

                     <div class="project item col-md-6 col-xl-4<?php echo $country_classes; ?>">
                        <a href="<?php the_permalink(); ?>" class="swiper-slide partner-card">
                           <figure class="lift">
                              <img decoding="async" src="<?php echo $thumbnail_url; ?>" alt="<?php echo esc_attr($full_name); ?>" class="w-100">

                              <?php if (!empty($caption_text)) : ?>
                                 <div class="caption-wrapper p-7">
                                    <div class="caption bg-matte-color mt-auto label-u text-neutral-50 px-4 py-2">
                                       <?php echo esc_html($caption_text); ?>
                                    </div>
                                 </div>
                              <?php endif; ?>
                           </figure>

                           <div class="team-item-content text-dark mt-3">
                              <h3 class="h4 mb-1">
                                 <?php if ($name && $surname) : ?>
                                    <?php echo esc_html($name); ?> <span class="text-uppercase"><?php echo esc_html($surname); ?></span>
                                 <?php else : ?>
                                    <?php echo esc_html($full_name); ?>
                                 <?php endif; ?>
                              </h3>

                              <?php $countries = wp_get_post_terms($post->ID, 'partner_country'); ?>
                              <?php
                              if (!empty($countries) && !is_wp_error($countries)) {
                                 $country_names = array(); ?>

                                 <?php
                                 foreach ($countries as $country) {
                                    $country_names[] = $country->name;
                                 }
                                 ?>
                                 <div class="label-u">

                                 <?php
                                 echo implode(', ', $country_names);
                              }
                                 ?>
                                 </div>
                           </div>
                        </a>
                     </div>
                     <!-- /.project -->

                  <?php endwhile;
                  wp_reset_postdata();
               else : ?>
                  <div class="col-12">
                     <p><?php __('No partners found.', 'horizons'); ?></p>
                  </div>
               <?php endif; ?>
            </div>
            <!-- /.row -->
         </div>
         <!-- /.grid -->
      <?php else : ?>
         <div class="alert alert-warning"><?php __('No partner countries found.', 'horizons'); ?></div>
      <?php endif; ?>
   </div>
   <!-- /.container -->
</section>



<?php get_footer(); ?>