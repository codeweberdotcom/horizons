<?php

add_action('codeweber_after_widget', function ($sidebar_id) {
   if ($sidebar_id === 'staff') {
      // Проверяем, существует ли тип записи 'legal'
      if (!post_type_exists('staff')) {
         return; // Прекращаем выполнение, если тип записи не существует
      }

      $legal_posts = get_posts([
         'post_type'      => 'staff',
         'posts_per_page' => -1,
         'post_status'    => 'publish',
         'orderby'        => 'menu_order',
         'order'          => 'ASC',
      ]);

      if ($legal_posts) {
         echo '<div class="widget">
         <div class="text-line-after label-u mb-6">'.__('Partners', 'horizons').'</div>
                    <nav id="sidebar-nav">
                        <ul class="list-unstyled">';

         $index = 1;
         $current_id = get_queried_object_id();

         foreach ($legal_posts as $post) {
            // Проверяем мета _hide_from_archive
            $hide = get_post_meta($post->ID, '_hide_from_archive', true);
            if ($hide === '1') {
               continue; // пропускаем скрытую запись
            }

            $permalink = get_permalink($post);
            $active_class = ($current_id === $post->ID) ? ' active' : '';
            echo '<li><a class="label-s text-neutral-500' . $active_class . '" href="' . esc_url($permalink) . '">' . esc_html(get_the_title($post)) . '</a></li>';
            $index++;
         }

         echo '</ul>
                 </nav>
              </div>';
      }
   }
});