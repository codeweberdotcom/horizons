<?php if (get_the_content()): ?>
   <div class="mb-3" id="partners_section_1">
      <div class="body-l-r mb-7">
         <?php the_content(); ?>
      </div>
   </div>
<?php endif; ?>

<?php
// Проверяем что мы на странице записи типа practices
if (get_post_type() == 'practices') :
   $post_id = get_the_ID();

   // Получаем все метаполя
   $your_task_text = get_post_meta($post_id, 'your_task_text', true);
   $our_solution_text = get_post_meta($post_id, 'our_solution_text', true);
   $advantages_text = get_post_meta($post_id, 'advantages_text', true);
?>

   <div class="practice-details">
      <?php if (!empty($your_task_text)) : ?>
         <div class="practice-section your-task">
            <h2 class="h3 mb-4"><?php _e('Your Task', 'horizons'); ?></h2>
            <div class="practice-content body-l-r mb-8"><?php echo wpautop($your_task_text); ?></div>
         </div>
      <?php endif; ?>

      <?php if (!empty($our_solution_text)) : ?>
         <div class="practice-section our-solution">
            <h2 class="h3 mb-4"><?php _e('Our Solution', 'horizons'); ?></h2>
            <div class="practice-content body-l-r  mb-8"><?php echo wpautop($our_solution_text); ?></div>
         </div>
      <?php endif; ?>

      <?php if (!empty($advantages_text)) : ?>
         <div class="practice-section advantages">
            <h2 class="h3 mb-4"><?php _e('Advantages', 'horizons'); ?></h2>
            <div class="practice-content body-l-r  mb-8"><?php echo wpautop($advantages_text); ?></div>
         </div>
      <?php endif; ?>
   </div>

<?php endif; ?>