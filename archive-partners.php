<?php get_header(); ?>
<?php get_pageheader(); ?>
<?php
$post_type = get_post_type();
$post_type_lc = strtolower($post_type);
$sidebar_position = Redux::get_option($opt_name, 'sidebar_position_archive_' . $post_type);
$pageheader_name = Redux::get_option($opt_name, 'global_page_header_model');
$content_class = ($sidebar_position === 'none') ? 'col-12' : 'col-md-8';
?>


<!-- Секция с партнерами -->
<section class="wrapper bg-white">
   <div class="container pb-14 pb-md-16">
      <?php
      /**
       * Функция для вывода партнеров, сгруппированных по категориям
       */
      function display_partners_grouped()
      {
         // Получаем все категории партнеров
         $partner_categories = get_terms(array(
            'taxonomy' => 'partner_category',
            'hide_empty' => true,
            'orderby'    => 'meta_value_num',
            'meta_key'   => 'partner_category_order',
            'order' => 'ASC'
         ));

         // Если нет категорий, выводим всех партнеров без группировки
         if (empty($partner_categories) || is_wp_error($partner_categories)) {
            display_all_partners();
            return;
         }

         // Для каждой категории получаем партнеров
         foreach ($partner_categories as $category) {
            // Запрос партнеров текущей категории
            $args = array(
               'post_type' => 'partners',
               'posts_per_page' => -1,
               'orderby' => 'title',
               'order' => 'ASC',
               'tax_query' => array(
                  array(
                     'taxonomy' => 'partner_category',
                     'field' => 'term_id',
                     'terms' => $category->term_id,
                  )
               )
            );

            $partners_query = new WP_Query($args);

            if ($partners_query->have_posts()) {
               // Выводим заголовок категории
               echo '<div class="partner-category-group">';
               echo '<h2 class="partner-category-title h3 mb-5">' . esc_html($category->name) . '</h2>';

               // Начало сетки
               echo '<div class="row gx-md-5 gy-5">';

               $animation_delay = 0;
               while ($partners_query->have_posts()) {
                  $partners_query->the_post();
                  display_partner_card($animation_delay);
                  $animation_delay += 300;
               }

               // Закрываем сетку и группу категории
               echo '</div><!-- /.row -->';
               echo '</div><!-- /.partner-category-group -->';

               // Добавляем отступ между категориями
               echo '<div class="my-10"></div>';
            }

            wp_reset_postdata();
         }
      }

      /**
       * Функция для вывода всех партнеров без группировки
       */
      function display_all_partners()
      {
         $args = array(
            'post_type' => 'partners',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
         );

         $partners_query = new WP_Query($args);

         if ($partners_query->have_posts()) {
            echo '<div class="row gx-md-5 gy-5">';

            $animation_delay = 0;
            while ($partners_query->have_posts()) {
               $partners_query->the_post();
               display_partner_card($animation_delay);
               $animation_delay += 300;
            }

            echo '</div><!-- /.row -->';
         } else {
            echo '<div class="alert alert-info">';
            echo '<p>' . __('No partners found.', 'horizons') . '</p>';
            echo '</div>';
         }

         wp_reset_postdata();
      }

      /**
       * Функция для вывода карточки партнера
       */
      function display_partner_card($animation_delay = 0)
      {
         global $post;

         // Получаем данные партнера
         $name = get_post_meta($post->ID, '_partners_name', true);
         $surname = get_post_meta($post->ID, '_partners_surname', true);
         $countries = wp_get_post_terms($post->ID, 'partner_country');
         $full_position = get_post_meta($post->ID, '_partners_full_position', true);
         $company = get_post_meta($post->ID, '_partners_company', true);

         // Формируем полное имя
         $full_name = trim($name . ' ' . $surname);
         $full_name_html = $name . ' <span class="text-uppercase">' . $surname . '</span>';

         // Получаем страну (первую из списка)
         $country_name = '';
         if (!empty($countries) && !is_wp_error($countries)) {
            $country_name = $countries[0]->name;
         }

         // Получаем изображение
         $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
         $alt_text = $full_name;
      ?>

         <div class="col-md-4 col-lg-4">
            <a href="<?php the_permalink(); ?>" class="swiper-slide partner-card" data-cue="slideInDown" data-show="true" style="animation-name: slideInDown; animation-duration: 700ms; animation-timing-function: ease; animation-delay: <?php echo $animation_delay; ?>ms; animation-direction: normal; animation-fill-mode: both;">
               <figure class="lift rounded">
                  <?php if ($thumbnail_url) : ?>
                     <img decoding="async" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($alt_text); ?>" class="w-100">
                  <?php else : ?>
                     <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                        <span class="text-muted"><?php _e('No image', 'horizons'); ?></span>
                     </div>
                  <?php endif; ?>



                  <?php if (!empty($full_position)) : ?>
                     <div class="caption-wrapper p-7">
                        <div class="caption bg-matte-color mt-auto label-u text-neutral-50 px-4 py-2">
                           <?php echo esc_html($full_position); ?>
                        </div>
                     </div>
                  <?php else : ?>
                     <div class="caption-wrapper p-7">
                        <div class="caption bg-matte-color mt-auto label-u text-neutral-50 px-4 py-2">
                           <?php echo esc_html($company); ?>
                        </div>
                     </div>
                  <?php endif; ?>


               </figure>

               <div class="team-item-content text-dark mt-3">
                  <h3 class="h5 mb-1"><?php echo wp_kses($full_name_html, array('span' => array('class' => array()))); ?></h3>
                  <div class="label-u"><?php
                                       if (!empty($countries) && !is_wp_error($countries)) {
                                          $country_names = array();
                                          foreach ($countries as $country) {
                                             $country_names[] = $country->name;
                                          }
                                          echo implode(', ', $country_names);
                                       }
                                       ?></div>
               </div>
            </a>
         </div>
      <?php
      }

      // Выводим партнеров
      display_partners_grouped();
      ?>
   </div>
</section>

<?php get_footer(); ?>