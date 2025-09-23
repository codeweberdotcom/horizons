<?php

// Обновляем основной код вывода категорий
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