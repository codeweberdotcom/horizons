<?php

//--------------------------------
//PARTNERS
//--------------------------------

add_action('codeweber_after_widget', function ($sidebar_id) {
   if ($sidebar_id === 'partners') {
      if (!post_type_exists('partners')) {
         return;
      }

      // Получаем все категории партнеров с сортировкой по порядковому номеру
      $categories = get_terms([
         'taxonomy'   => 'partner_category',
         'hide_empty' => true,
         'orderby'    => 'meta_value_num',
         'meta_key'   => 'partner_category_order',
         'order'      => 'ASC'
      ]);

      $current_id = get_queried_object_id();
      $accordion_id = 'partnersAccordion';

      echo '<div class="accordion accordion-wrapper" id="' . $accordion_id . '">';

      if ($categories && !is_wp_error($categories)) {
         $category_index = 0;

         foreach ($categories as $category) {
            // Получаем партнеров для текущей категории
            $partner_posts = get_posts([
               'post_type'      => 'partners',
               'posts_per_page' => -1,
               'post_status'    => 'publish',
               'orderby'        => 'title',
               'order'          => 'ASC',
               'tax_query'      => [
                  [
                     'taxonomy' => 'partner_category',
                     'field'    => 'term_id',
                     'terms'    => $category->term_id,
                  ]
               ]
            ]);

            // Проверяем, есть ли видимые посты в категории
            $has_visible_posts = false;
            $has_active_post = false;

            foreach ($partner_posts as $post) {
               $hide = get_post_meta($post->ID, '_hide_from_archive', true);
               if ($hide !== '1') {
                  $has_visible_posts = true;
                  if ($current_id === $post->ID) {
                     $has_active_post = true;
                  }
               }
            }

            // Пропускаем категории без видимых постов
            if (!$has_visible_posts) {
               continue;
            }

            $category_index++;
            $heading_id = 'headingCategory' . $category_index;
            $collapse_id = 'collapseCategory' . $category_index;

            // Определяем, нужно ли развернуть категорию (если есть активный пост)
            $is_expanded = $has_active_post ? 'true' : 'false';
            $show_class = $has_active_post ? 'show' : '';
            $button_class = $has_active_post ? 'accordion-button text-line-after text-uppercase fs-14' : 'collapsed accordion-button text-line-after text-uppercase fs-14';

            echo '<div class="card plain accordion-item">';
            echo '<div class="card-header" id="' . $heading_id . '">';
            echo '<button class="' . $button_class . '" data-bs-toggle="collapse" data-bs-target="#' . $collapse_id . '" aria-expanded="' . $is_expanded . '" aria-controls="' . $collapse_id . '">';
            echo esc_html($category->name);
            echo '</button>';
            echo '</div>';
            echo '<!--/.card-header -->';

            echo '<div id="' . $collapse_id . '" class="accordion-collapse collapse ' . $show_class . '" aria-labelledby="' . $heading_id . '" data-bs-parent="#' . $accordion_id . '">';
            echo '<div class="card-body">';
            echo '<nav id="sidebar-nav">';
            echo '<ul class="list-unstyled">';

            foreach ($partner_posts as $post) {
               $hide = get_post_meta($post->ID, '_hide_from_archive', true);
               if ($hide === '1') {
                  continue;
               }

               $permalink = get_permalink($post);
               $active_class = ($current_id === $post->ID) ? ' active' : '';
               echo '<li><a class="label-s text-neutral-500' . $active_class . '" href="' . esc_url($permalink) . '">' . esc_html(get_the_title($post)) . '</a></li>';
            }

            echo '</ul>';
            echo '</nav>';
            echo '</div>';
            echo '<!--/.card-body -->';
            echo '</div>';
            echo '<!--/.accordion-collapse -->';
            echo '</div>';
            echo '<!--/.accordion-item -->';
         }
      } else {
         // Если нет категорий, выводим всех партнеров без группировки в одной категории
         $partner_posts = get_posts([
            'post_type'      => 'partners',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
         ]);

         if ($partner_posts) {
            $has_active_post = false;
            foreach ($partner_posts as $post) {
               if ($current_id === $post->ID) {
                  $has_active_post = true;
                  break;
               }
            }

            $is_expanded = $has_active_post ? 'true' : 'false';
            $show_class = $has_active_post ? 'show' : '';
            $button_class = $has_active_post ? 'accordion-button' : 'collapsed accordion-button';

            echo '<div class="card plain accordion-item">';
            echo '<div class="card-header" id="headingAllPartners">';
            echo '<button class="' . $button_class . '" data-bs-toggle="collapse" data-bs-target="#collapseAllPartners" aria-expanded="' . $is_expanded . '" aria-controls="collapseAllPartners">';
            echo __('Partner Biographies', 'horizons');
            echo '</button>';
            echo '</div>';
            echo '<!--/.card-header -->';

            echo '<div id="collapseAllPartners" class="accordion-collapse collapse ' . $show_class . '" aria-labelledby="headingAllPartners" data-bs-parent="#' . $accordion_id . '">';
            echo '<div class="card-body">';
            echo '<nav id="sidebar-nav">';
            echo '<ul class="list-unstyled">';

            foreach ($partner_posts as $post) {
               $hide = get_post_meta($post->ID, '_hide_from_archive', true);
               if ($hide === '1') {
                  continue;
               }

               $permalink = get_permalink($post);
               $active_class = ($current_id === $post->ID) ? ' active' : '';
               echo '<li><a class="label-s text-neutral-500' . $active_class . '" href="' . esc_url($permalink) . '">' . esc_html(get_the_title($post)) . '</a></li>';
            }

            echo '</ul>';
            echo '</nav>';
            echo '</div>';
            echo '<!--/.card-body -->';
            echo '</div>';
            echo '<!--/.accordion-collapse -->';
            echo '</div>';
            echo '<!--/.accordion-item -->';
         }
      }

      echo '</div>';
      echo '<!--/.accordion -->';
   }
});



add_action('codeweber_after_widget', function ($sidebar_id) {
   if ($sidebar_id === 'practices') {
      if (!post_type_exists('practices')) {
         return;
      }

      // Получаем все категории для типа записи practices
      $categories = get_terms([
         'taxonomy'   => 'practice_category',
         'hide_empty' => true,
         'orderby'    => 'name',
         'order'      => 'ASC'
      ]);

      $current_id = get_queried_object_id();
      $accordion_id = 'practicesAccordion';

      echo '<div class="accordion accordion-wrapper" id="' . $accordion_id . '">';

      if ($categories && !is_wp_error($categories)) {
         $category_index = 0;

         foreach ($categories as $category) {
            // Получаем записи для текущей категории
            $practice_posts = get_posts([
               'post_type'      => 'practices',
               'posts_per_page' => -1,
               'post_status'    => 'publish',
               'orderby'        => 'title',
               'order'          => 'ASC',
               'tax_query'      => [
                  [
                     'taxonomy' => 'practice_category',
                     'field'    => 'term_id',
                     'terms'    => $category->term_id,
                  ]
               ]
            ]);

            // Проверяем, есть ли видимые посты в категории
            $has_visible_posts = false;
            $has_active_post = false;

            foreach ($practice_posts as $post) {
               $hide = get_post_meta($post->ID, '_hide_from_archive', true);
               if ($hide !== '1') {
                  $has_visible_posts = true;
                  if ($current_id === $post->ID) {
                     $has_active_post = true;
                  }
               }
            }

            // Пропускаем категории без видимых постов
            if (!$has_visible_posts) {
               continue;
            }

            $category_index++;
            $heading_id = 'headingPracticeCategory' . $category_index;
            $collapse_id = 'collapsePracticeCategory' . $category_index;

            // Определяем, нужно ли развернуть категорию (если есть активный пост)
            $is_expanded = $has_active_post ? 'true' : 'false';
            $show_class = $has_active_post ? 'show' : '';
            $button_class = $has_active_post ? 'accordion-button text-line-after text-uppercase fs-14' : 'collapsed accordion-button text-line-after text-uppercase fs-14';

            echo '<div class="card plain accordion-item">';
            echo '<div class="card-header" id="' . $heading_id . '">';
            echo '<button class="' . $button_class . '" data-bs-toggle="collapse" data-bs-target="#' . $collapse_id . '" aria-expanded="' . $is_expanded . '" aria-controls="' . $collapse_id . '">';
            echo esc_html($category->name);
            echo '</button>';
            echo '</div>';
            echo '<!--/.card-header -->';

            echo '<div id="' . $collapse_id . '" class="accordion-collapse collapse ' . $show_class . '" aria-labelledby="' . $heading_id . '" data-bs-parent="#' . $accordion_id . '">';
            echo '<div class="card-body">';
            echo '<nav id="sidebar-nav">';
            echo '<ul class="list-unstyled">';

            foreach ($practice_posts as $post) {
               $hide = get_post_meta($post->ID, '_hide_from_archive', true);
               if ($hide === '1') {
                  continue;
               }

               $permalink = get_permalink($post);
               $active_class = ($current_id === $post->ID) ? ' active' : '';
               echo '<li><a class="label-s text-neutral-500' . $active_class . '" href="' . esc_url($permalink) . '">' . esc_html(get_the_title($post)) . '</a></li>';
            }

            echo '</ul>';
            echo '</nav>';
            echo '</div>';
            echo '<!--/.card-body -->';
            echo '</div>';
            echo '<!--/.accordion-collapse -->';
            echo '</div>';
            echo '<!--/.accordion-item -->';
         }
      }

      echo '</div>';
      echo '<!--/.accordion -->';
   }
});



//--------------------------------
//VACANCIES
//--------------------------------

add_action('codeweber_after_widget', function ($sidebar_id) {
   if ($sidebar_id === 'vacancies') {
      if (!post_type_exists('vacancies')) {
         return;
      }

      $vacancy_data = get_vacancy_data_array();

      // Массив переводов для типа занятости
      $employment_types = array(
         'full-time'  => __('Full-time', 'horizons'),
         'part-time'  => __('Part-time', 'horizons'),
         'internship' => __('Internship', 'horizons'),
         'contract'   => __('Contract', 'horizons')
      );

      $type = $vacancy_data['employment_type'] ?? '';
      $display_type = isset($employment_types[$type]) ? $employment_types[$type] : $type;

      $user_id = get_the_author_meta('ID');
      $avatar_id = get_user_meta($user_id, 'avatar_id', true);
      if (empty($avatar_id)) {
         $avatar_id = get_user_meta($user_id, 'custom_avatar_id', true);
      }

      $job_title = get_user_meta($user_id, 'user_position', true);
      if (empty($job_title)) {
         $job_title = __('Writer', 'horizons');
      }
?>

      <div class="card border">
         <?php $image_url = get_the_post_thumbnail_url(get_the_ID(), 'codeweber_vacancy'); ?>
         <?php if ($image_url) : ?>
            <img src="<?php echo esc_url($image_url); ?>" class="card-img-top" alt="<?php echo esc_attr(get_the_title()); ?>">
         <?php endif; ?>

         <div class="card-body bg-neutral-100">
            <div class="mb-6">
               <div class="text-line-after label-u mb-4"><?php _e('Details', 'horizons'); ?></div>

               <?php if (!empty($vacancy_data['location'])) : ?>
                  <p class="mb-2 body-l-r d-flex align-content-center">
                     <i class="uil uil-map-marker-alt me-2"></i>
                     <?php echo esc_html($vacancy_data['location']); ?>
                  </p>
               <?php endif; ?>

               <?php if (!empty($vacancy_data['employment_type'])) : ?>
                  <p class="mb-2 body-l-r d-flex align-content-center">
                     <i class="uil uil-calendar-alt me-2"></i>
                     <?php echo esc_html($display_type); ?>
                  </p>
               <?php endif; ?>

               <?php if (!empty($vacancy_data['salary'])) : ?>
                  <p class="mb-2 body-l-r d-flex align-content-center">
                     <i class="uil uil-money-stack me-2"></i>
                     <?php echo esc_html($vacancy_data['salary']); ?>
                  </p>
               <?php endif; ?>
            </div>

            <div class="text-line-after label-u mb-4"><?php _e('Contact Person', 'horizons'); ?></div>

            <div class="author-info d-md-flex align-items-center mb-4">
               <div class="d-flex align-items-center">
                  <?php if (!empty($avatar_id)) : ?>
                     <?php $avatar_src = wp_get_attachment_image_src($avatar_id, 'thumbnail'); ?>
                     <img decoding="async" class="w-48 h-48 me-3" alt="<?php the_author_meta('display_name'); ?>" src="<?php echo esc_url($avatar_src[0]); ?>">
                  <?php else : ?>
                     <figure class="me-3">
                        <?php echo get_avatar(get_the_author_meta('user_email'), 48); ?>
                     </figure>
                  <?php endif; ?>

                  <?php
                  $user_link = get_user_partner_link($user_id);
                  ?>

                  <div class="avatar-info mt-0">
                        <a href="<?php echo esc_url($user_link['url']); ?>" class="hover-7 link-body label-u text-charcoal-blue d-block lh-0">
                        <?php the_author_meta('first_name'); ?> <?php the_author_meta('last_name'); ?>
                     </a>
                     <span class="body-s lh-0 text-neutral-500"><?php echo esc_html($job_title); ?></span>
                  </div>
               </div>
            </div>

            <?php if (!empty($vacancy_data['pdf_url'])) : ?>
               <a href="<?php echo esc_url($vacancy_data['pdf_url']); ?>" target="_blank" class="btn btn-outline-dusty-navy has-ripple btn-lg w-100 mb-1">
                  <?php _e('Download document', 'horizons'); ?>
               </a>
            <?php endif; ?>

            <button class="btn btn-dusty-navy has-ripple btn-lg w-100">
               <?php _e('Submit a request', 'horizons'); ?>
            </button>
         </div>
      </div>
<?php
   }
});





//--------------------------------
//AWARDS SIDEBAR MENUS
//--------------------------------

add_action('codeweber_after_widget', function ($sidebar_id) {
   if ($sidebar_id === 'awards') {
      if (!post_type_exists('awards')) {
         return;
      }

      // Меню категорий наград
      $categories = get_terms([
         'taxonomy'   => 'award_category',
         'hide_empty' => true,
         'orderby'    => 'name',
         'order'      => 'ASC'
      ]);

      if ($categories && !is_wp_error($categories)) {
         echo '<div class="widget mb-10">';
         echo '<div class="text-line-after label-u mb-4">' . __('Award Categories', 'horizons') . '</div>';
         echo '<nav id="awards-category-nav">';
         echo '<ul class="list-unstyled">';

         foreach ($categories as $category) {
            $active_class = is_tax('award_category', $category->term_id) ? ' active' : '';
            echo '<li class="mt-0"><a class="label-s text-neutral-500' . $active_class . '" href="' . esc_url(get_single_filter_url('category', $category->slug)) . '">' . esc_html($category->name) . ' <span class="text-muted">(' . $category->count . ')</span></a></li>';
         }

         echo '</ul>';
         echo '</nav>';
         echo '</div>';
         echo '<!--/.widget -->';
      }

      // Меню лет наград (из даты публикации)
      $years = get_awards_years_from_posts();

      if (!empty($years)) {
         echo '<div class="widget mb-10">';
         echo '<div class="text-line-after label-u mb-4">' . __('Award Years', 'horizons') . '</div>';
         echo '<nav id="awards-year-nav">';
         echo '<ul class="list-unstyled">';

         foreach ($years as $year_data) {
            $active_class = is_year_active($year_data['year']) ? ' active' : '';
            echo '<li class="mt-0"><a class="label-s text-neutral-500' . $active_class . '" href="' . esc_url(get_single_filter_url('year', $year_data['year'])) . '">' . esc_html($year_data['year']) . ' <span class="text-muted">(' . $year_data['count'] . ')</span></a></li>';
         }

         echo '</ul>';
         echo '</nav>';
         echo '</div>';
         echo '<!--/.widget -->';
      }

      // Меню партнеров из метаполя
      $partners_with_awards = get_partners_with_published_awards();

      if (!empty($partners_with_awards)) {
         echo '<div class="widget mb-10">';
         echo '<div class="text-line-after label-u mb-4">' . __('Award Partners', 'horizons') . '</div>';
         echo '<nav id="awards-partners-nav">';
         echo '<ul class="list-unstyled">';

         foreach ($partners_with_awards as $partner) {
            $active_class = is_partner_active($partner->ID) ? ' active' : '';
            echo '<li class="mt-0"><a class="label-s text-neutral-500' . $active_class . '" href="' . esc_url(get_single_filter_url('partner', $partner->ID)) . '">' . esc_html($partner->post_title) . '</a></li>';
         }

         echo '</ul>';
         echo '</nav>';
         echo '</div>';
         echo '<!--/.widget -->';
      }
   }
});

/**
 * Генерирует URL только с одним фильтром (очищает другие)
 */
function get_single_filter_url($filter_type, $filter_value)
{
   $base_url = get_post_type_archive_link('awards');

   // Очищаем все параметры и добавляем только текущий фильтр
   return add_query_arg($filter_type, $filter_value, $base_url);
}

/**
 * Получает года из даты публикации постов наград
 */
function get_awards_years_from_posts()
{
   $awards = get_posts([
      'post_type' => 'awards',
      'posts_per_page' => -1,
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'DESC'
   ]);

   $years = [];

   foreach ($awards as $award) {
      $year = get_the_date('Y', $award->ID);

      if (!isset($years[$year])) {
         $years[$year] = 0;
      }

      $years[$year]++;
   }

   // Сортируем по году (по убыванию)
   krsort($years);

   // Форматируем результат
   $formatted_years = [];
   foreach ($years as $year => $count) {
      $formatted_years[] = [
         'year' => $year,
         'count' => $count
      ];
   }

   return $formatted_years;
}

/**
 * Проверяет активен ли текущий год в URL
 */
function is_year_active($year)
{
   if (!isset($_GET['year'])) {
      return false;
   }

   return intval($_GET['year']) === $year;
}

/**
 * Получает партнеров у которых есть опубликованные награды
 */
function get_partners_with_published_awards()
{
   $partners = get_posts([
      'post_type'      => 'partners',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
      'orderby'        => 'title',
      'order'          => 'ASC'
   ]);

   $partners_with_awards = [];

   if (!$partners) {
      return $partners_with_awards;
   }

   foreach ($partners as $partner) {
      // Получаем все награды, связанные с этим партнером
      $partner_awards = get_post_meta($partner->ID, '_partners_awards', true);

      // Проверяем, что поле существует и не пустое
      if (!$partner_awards || !is_array($partner_awards) || empty($partner_awards)) {
         continue;
      }

      // Проверяем, что хотя бы одна награда опубликована
      $has_published_awards = false;
      foreach ($partner_awards as $award_id) {
         // Проверяем существование поста и его статус
         if (get_post_status($award_id) === 'publish') {
            $has_published_awards = true;
            break;
         }
      }

      if ($has_published_awards) {
         $partners_with_awards[] = $partner;
      }
   }

   return $partners_with_awards;
}

/**
 * Проверяет активен ли текущий партнер в URL
 */
function is_partner_active($partner_id)
{
   if (!isset($_GET['partner'])) {
      return false;
   }

   return intval($_GET['partner']) === $partner_id;
}