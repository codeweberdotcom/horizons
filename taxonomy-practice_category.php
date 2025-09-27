<?php get_header(); ?>
<?php
$post_type = universal_get_post_type();
$post_type_lc = strtolower($post_type);
$sidebar_position = Redux::get_option($opt_name, 'sidebar_position_archive_' . $post_type);
$pageheader_name = Redux::get_option($opt_name, 'global_page_header_model');

// Определяем класс колонки для контента
$content_class = ($sidebar_position === 'none') ? 'col-12' : 'col-md-8';
?>

<?php
$current_term = get_queried_object();

$color = get_term_meta($current_term->term_id, 'practice_category_color', true);
$color_class = $color ? 'bg-' . $color : '';
$category_description = $current_term->description;



if ($current_term && !is_wp_error($current_term)) {
   $category_image_id = get_term_meta($current_term->term_id, 'practice_category_image', true);

   if ($category_image_id) {
      // Получаем URL изображения в нужном размере
      $image_url = wp_get_attachment_image_src($category_image_id, 'codeweber_awards');

      if ($image_url) {
         $image_src = $image_url[0];
      }
   }
}
?>

<section class="wrapper <?php echo $color_class; ?> position-relative min-vh-60 d-lg-flex align-items-center">
   <div class="container position-relative" data-cue="fadeIn" data-delay="600">
      <div class="row gx-0">
         <div class="col-lg-6">
            <div class="py-12 py-lg-16 pe-lg-12 py-xxl-15 pe-xxl-15 ps-lg-0 position-relative" data-cues="slideInDown" data-group="page-title">
               <div class="text-line-before label-u mb-2 text-sub-white"><?php echo __('Practices', 'horizons'); ?></div>
               <h1 class="h1 mb-4 text-white mt-md-18"><?= esc_html(universal_title(false, false)); ?></h1>


               <?php if (!empty($category_description)) : ?>
                  <div class="text-white body-l-r mb-0">
                     <?php echo wp_kses_post($category_description); ?>
                  </div>
               <?php endif; ?>


            </div>
         </div>
         <!-- /column -->
      </div>
      <!--/.row -->
   </div>
   <!-- /.container -->
   <div class="col-lg-6 position-lg-absolute top-0 end-0 image-wrapper bg-image bg-cover h-100" data-image-src="<?php echo esc_url($image_src); ?>">
   </div>
   <!--/column -->
</section>
<!-- /section -->




<?php get_footer(); ?>