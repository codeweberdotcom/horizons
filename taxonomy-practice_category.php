<?php get_header(); ?>
<?php
$post_type = universal_get_post_type();
$post_type_lc = strtolower($post_type);
$sidebar_position = Redux::get_option($opt_name, 'sidebar_position_archive_' . $post_type);
$pageheader_name = Redux::get_option($opt_name, 'global_page_header_model');

// Определяем класс колонки для контента
$content_class = ($sidebar_position === 'none') ? 'col-12' : 'col-md-8';

// Получаем текущий термин таксономии
$current_term = get_queried_object();

// Получаем автора категории
$category_author_id = '';
$category_author = null;
$category_author_avatar = '';
$category_author_job_title = '';

if ($current_term && !is_wp_error($current_term)) {
   $category_author_id = get_term_meta($current_term->term_id, 'practice_category_author', true);

   if ($category_author_id) {
      $category_author = get_userdata($category_author_id);
      if ($category_author) {
         // Получаем аватар автора категории
         $avatar_id = get_user_meta($category_author_id, 'avatar_id', true);
         if (empty($avatar_id)) {
            $avatar_id = get_user_meta($category_author_id, 'custom_avatar_id', true);
         }

         if ($avatar_id) {
            $avatar_src = wp_get_attachment_image_src($avatar_id, 'thumbnail');
            if ($avatar_src) {
               $category_author_avatar = $avatar_src[0];
            }
         }

         // Получаем должность автора категории
         $category_author_job_title = get_user_meta($category_author_id, 'user_position', true);
         if (empty($category_author_job_title)) {
            $category_author_job_title = __('Practice Manager', 'horizons');
         }
      }
   }
}

// Получаем записи для этой таксономии
if ($current_term && !is_wp_error($current_term)) {
   $posts_args = array(
      'post_type' => 'practices',
      'posts_per_page' => -1,
      'tax_query' => array(
         array(
            'taxonomy' => $current_term->taxonomy,
            'field' => 'term_id',
            'terms' => $current_term->term_id,
         )
      ),
      'orderby' => 'title',
      'order' => 'ASC'
   );

   $posts = new WP_Query($posts_args);
}

$color = get_term_meta($current_term->term_id, 'practice_category_color', true);
$color_class = $color ? 'bg-' . $color : '';
$category_description = $current_term->description;

// Получаем изображение категории
$image_src = '';
if ($current_term && !is_wp_error($current_term)) {
   $category_image_id = get_term_meta($current_term->term_id, 'practice_category_image', true);

   if ($category_image_id) {
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
   <?php if ($image_src) : ?>
      <div class="col-lg-6 position-lg-absolute top-0 end-0 image-wrapper bg-image bg-cover h-100" data-image-src="<?php echo esc_url($image_src); ?>">
      </div>
   <?php endif; ?>
   <!--/column -->
</section>
<!-- /section -->

<?php if ($current_term && $posts && $posts->have_posts()) : ?>
   <section class="wrapper bg-white">
      <div class="container">
         <div class="row">
            <div class="col-lg-8 py-14">
               <div class="practice-posts-list">
                  <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                     <?php $is_last = ($posts->current_post + 1) === $posts->post_count; ?>
                     <article id="post-<?php the_ID(); ?>" <?php post_class('practice-item mb-0'); ?>>
                        <a class="h4 text-dark py-3 pe-2 d-flex justify-content-between align-items-center mb-0 service-link <?php echo !$is_last ? 'border-bottom' : ''; ?>" href="<?php the_permalink(); ?>">
                           <span class="d-flex align-items-center">
                              <div class="brand-square-sm me-3"></div>
                              <?php the_title(); ?>
                           </span>
                           <i class="uil uil-angle-right"></i>
                        </a>
                     </article>
                  <?php endwhile; ?>
                  <?php wp_reset_postdata(); ?>
               </div>
            </div>

            <aside class="col-xl-4 sidebar sticky-sidebar mt-md-0 py-12 d-none d-xl-block">
               <div class="card border">
                  <div class="card-body bg-neutral-100">
                     <div class="mb-6">
                        <div class="text-line-after label-u mb-4"><?php _e('Area Details', 'horizons'); ?></div>

                        <?php if (!empty($current_term->name)) : ?>
                           <p class="mb-2 body-l-r d-flex align-content-center">
                              <i class="uil uil-tag me-2"></i>
                              <?php echo esc_html($current_term->name); ?>
                           </p>
                        <?php endif; ?>

                        <?php if (!empty($current_term->count)) : ?>
                           <p class="mb-2 body-l-r d-flex align-content-center">
                              <i class="uil uil-file me-2"></i>
                              <?php
                              printf(
                                 _n('%d practice', '%d practices', $current_term->count, 'horizons'),
                                 $current_term->count
                              );
                              ?>
                           </p>
                        <?php endif; ?>
                     </div>

                     <?php if ($category_author) : ?>
                        <div class="text-line-after label-u mb-4"><?php _e('Category Manager', 'horizons'); ?></div>

                        <div class="author-info d-md-flex align-items-center mb-4">
                           <div class="d-flex align-items-center">
                              <?php if (!empty($category_author_avatar)) : ?>
                                 <img decoding="async" class="w-48 h-48 me-3 rounded-circle"
                                    alt="<?php echo esc_attr($category_author->display_name); ?>"
                                    src="<?php echo esc_url($category_author_avatar); ?>">
                              <?php else : ?>
                                 <figure class="me-3">
                                    <?php echo get_avatar($category_author->user_email, 48, '', '', array('class' => 'rounded-circle')); ?>
                                 </figure>
                              <?php endif; ?>

                              <div class="avatar-info mt-0">
                                 <a href="<?php echo esc_url(get_author_posts_url($category_author_id)); ?>"
                                    class="hover-7 link-body label-u text-charcoal-blue d-block lh-0">
                                    <?php echo esc_html($category_author->first_name . ' ' . $category_author->last_name); ?>
                                 </a>
                                 <span class="body-s lh-0 text-neutral-500">
                                    <?php echo esc_html($category_author_job_title); ?>
                                 </span>
                              </div>
                           </div>
                        </div>

                        <div class="contact-buttons">
                           <a href="mailto:<?php echo esc_attr($category_author->user_email); ?>"
                              class="btn btn-outline-dusty-navy has-ripple btn-lg w-100 mb-2">
                              <i class="uil uil-envelope me-2"></i>
                              <?php _e('Send Email', 'horizons'); ?>
                           </a>

                           <button class="btn btn-dusty-navy has-ripple btn-lg w-100"
                              onclick="window.location.href='tel:<?php echo esc_attr(get_user_meta($category_author_id, 'phone', true)); ?>'">
                              <?php _e('Submit a request', 'horizons'); ?>
                           </button>
                        </div>
                     <?php else : ?>
                        <div class="text-center py-4">
                           <i class="uil uil-user-circle display-4 text-muted mb-3"></i>
                           <p class="text-muted mb-0"><?php _e('No category manager assigned', 'horizons'); ?></p>
                        </div>
                     <?php endif; ?>
                  </div>
               </div>
            </aside>
         </div>
      </div>
   </section>
<?php elseif ($current_term) : ?>
   <section class="wrapper bg-white">
      <div class="container py-14 py-md-16">
         <div class="row">
            <div class="col-lg-8 mx-auto text-center">
               <div class="">
                  <i class="uil uil-info-circle me-2"></i>
                  <?php _e('No services found in this category.', 'horizons'); ?>
               </div>
            </div>
         </div>
      </div>
   </section>
<?php endif; ?>

<?php get_footer(); ?>