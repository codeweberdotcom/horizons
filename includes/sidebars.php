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

      if ($categories && !is_wp_error($categories)) {
         $current_id = get_queried_object_id();

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
            foreach ($partner_posts as $post) {
               $hide = get_post_meta($post->ID, '_hide_from_archive', true);
               if ($hide !== '1') {
                  $has_visible_posts = true;
                  break;
               }
            }

            // Пропускаем категории без видимых постов
            if (!$has_visible_posts) {
               continue;
            }

            echo '<div class="widget">';
            echo '<div class="text-line-after label-u mb-6">' . esc_html($category->name) . '</div>';
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
         }
      } else {
         // Если нет категорий, выводим всех партнеров без группировки
         $partner_posts = get_posts([
            'post_type'      => 'partners',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
         ]);

         if ($partner_posts) {
            echo '<div class="widget">';
            echo '<div class="text-line-after label-u mb-6">' . __('Partner Biographies', 'horizons') . '</div>';
            echo '<nav id="sidebar-nav">';
            echo '<ul class="list-unstyled">';

            $current_id = get_queried_object_id();

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
         }
      }
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

      if ($categories && !is_wp_error($categories)) {
         foreach ($categories as $category) {
            echo '<div class="widget">';
            echo '<div class="text-line-after label-u mb-6">' . esc_html($category->name) . '</div>';
            echo '<nav id="sidebar-nav">';
            echo '<ul class="list-unstyled">';

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

            $current_id = get_queried_object_id();

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
         }
      }
   }
});