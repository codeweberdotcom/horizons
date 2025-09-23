<?php

function add_news_section_after_practices($post_type)
{
   display_faq_section(get_the_ID());

   if ($post_type === 'practices') {
      $post_id = get_the_ID();
      $related_categories = get_post_meta($post_id, 'related_blog_categories', true);
      $related_tags = get_post_meta($post_id, 'related_blog_tags', true);

      // Формируем параметры для шорткода
      $shortcode_atts = 'posts_per_page="6" order="ASC" nav="true" dots="true" margin="24" items_xl="4" items_lg="3" items_md="2" items_sm="2" items_xs="1" items_xxs="1" image_size="codeweber_staff" excerpt_length="15"';

      // Добавляем категории если есть (преобразуем ID в slug'и)
      if (!empty($related_categories)) {
         $category_slugs = array();
         foreach ((array)$related_categories as $cat_id) {
            $category = get_term($cat_id, 'category');
            if ($category && !is_wp_error($category)) {
               $category_slugs[] = $category->slug;
            }
         }
         if (!empty($category_slugs)) {
            $shortcode_atts .= ' category="' . implode(',', $category_slugs) . '"';
         }
      }

      // Добавляем теги если есть (преобразуем ID в slug'и)
      if (!empty($related_tags)) {
         $tag_slugs = array();
         foreach ((array)$related_tags as $tag_id) {
            $tag = get_term($tag_id, 'post_tag');
            if ($tag && !is_wp_error($tag)) {
               $tag_slugs[] = $tag->slug;
            }
         }
         if (!empty($tag_slugs)) {
            $shortcode_atts .= ' tag="' . implode(',', $tag_slugs) . '"';
         }
      }

      ob_start(); ?>
      <section class="wrapper blog-section" id="practices_news">
         <div class="container py-14 py-md-16">
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
                  <?php echo do_shortcode('[blog_posts_slider ' . $shortcode_atts . ']'); ?>
               </div>
            </div>
         </div>
      </section>
<?php
      echo ob_get_clean();
   }
}
add_action('after_single_post', 'add_news_section_after_practices', 10, 1);




