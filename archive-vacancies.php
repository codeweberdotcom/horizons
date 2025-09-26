<?php get_header(); ?>
<?php get_pageheader(); ?>
<?php
$post_type = get_post_type();
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
   <div class="col-lg-6 position-lg-absolute top-0 end-0 image-wrapper bg-image bg-cover h-100" data-image-src="https://bricksnew.test/wp-content/uploads/2025/09/vertical-shot-modern-stairway-beautiful-white-building-scaled.jpg">
   </div>
   <!--/column -->
</section>
<!-- /section -->

<?php if (have_posts()) : ?>

   <section id="content-wrapper" class="wrapper">
      <div class="container py-12 py-md-14>
         <?php
         while (have_posts()) :
            the_post();
            $vacancy_array = get_vacancy_data_array($post->ID);
         ?>
            <article id=" <?php echo $post->post_name; ?>" class="col-12">
         <a class="h4 text-dark py-3 pe-2 d-flex justify-content-between align-items-center mb-0 service-link border-bottom flex-wrap flex-md-nowrap" href="<?php the_permalink(); ?>">
            <span class="d-flex align-items-center me-3">
               <div class="brand-square-sm me-3"></div>
               <?php the_title(); ?>
            </span>
            <span class="d-flex align-items-center ms-3">
               <?php if (!empty($vacancy_array['location'])): ?>
                  <span class="me-3 uil"><?php echo $vacancy_array['location']; ?></span>
               <?php endif; ?>
               <i class="uil uil-angle-right"></i>
            </span>
         </a>
         </article> <!-- #post-<?php the_ID(); ?> -->
      <?php
         endwhile;
         codeweber_posts_pagination();
      ?>
      </div>
   </section>
<?php
else :
   get_template_part('templates/content/loop', 'none');
endif;
?>

<?php get_footer(); ?>