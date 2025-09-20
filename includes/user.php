<?php

/**
 * Display author information with optional button
 *
 * @param bool $show_button Whether to show the "All Posts" button
 * @param string $button_class Additional CSS classes for the button
 * @param string $avatar_size Size of the avatar image
 * @return void
 */
function codeweber_author_info($show_button = true, $button_class = '', $avatar_size = 'thumbnail')
{
   $user_id = get_the_author_meta('ID');

   // Check both possible avatar meta keys
   $avatar_id = get_user_meta($user_id, 'avatar_id', true);
   if (empty($avatar_id)) {
      $avatar_id = get_user_meta($user_id, 'custom_avatar_id', true);
   }

   // Get job title or use default
   $job_title = get_user_meta($user_id, 'user_position', true);
   if (empty($job_title)) {
      $job_title = __('Writer', 'codeweber');
   }

   // Default button classes
   $default_button_class = 'btn btn-sm btn-primary btn-icon btn-icon-start mb-0';
   $final_button_class = $button_class ? $button_class : $default_button_class;
?>


   <div class="author-info d-md-flex align-items-center">
      <div class="d-flex align-items-center">
         <?php if (!empty($avatar_id)) :
            $avatar_src = wp_get_attachment_image_src($avatar_id, $avatar_size);
         ?>
            <img decoding="async" class="avatar w-48 me-3" alt="<?php the_author_meta('display_name'); ?>" src="<?php echo esc_url($avatar_src[0]); ?>">
         <?php else : ?>
            <?php echo get_avatar(get_the_author_meta('user_email'), 96); ?>
         <?php endif; ?>
         <div class="avatar-info mt-1">
            <a href="<?php echo esc_url(get_author_posts_url($user_id)); ?>" class="label-s r link-dark d-block lh-0">
               <?php the_author_meta('first_name'); ?> <?php the_author_meta('last_name'); ?>
            </a>
            <span class="body-s lh-0"><?php echo esc_html($job_title); ?></span>
         </div>
      </div>
      <?php if ($show_button) : ?>
         <div class="mt-3 mt-md-0 ms-auto">
            <a href="<?php echo esc_url(get_author_posts_url($user_id)); ?>" class="<?php echo esc_attr($final_button_class); ?>">
               <i class="uil uil-file-alt"></i> <?php esc_html_e('All Posts', 'codeweber'); ?>
            </a>
         </div>
      <?php endif; ?>
   </div>
   <!-- /.author-info -->
<?php
}
