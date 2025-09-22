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
      <div class="row g-5">
         <?php if (!empty($your_task_text)) : ?>
            <div class="col-md-12">
               <div class="practice-section your-task card card-body h-100">
                  <h2 class="h3 mb-4"><?php _e('Your Task', 'horizons'); ?></h2>
                  <div class="practice-content body-l-r"><?php echo $your_task_text; ?></div>
               </div>
            </div>
         <?php endif; ?>

         <?php if (!empty($our_solution_text)) : ?>
            <div class="col-md-12">
               <div class="practice-section our-solution card card-body h-100">
                  <h2 class="h3 mb-4"><?php _e('Our Solution', 'horizons'); ?></h2>
                  <div class="practice-content body-l-r"><?php echo $our_solution_text; ?></div>
               </div>
            </div>
         <?php endif; ?>

         <?php if (!empty($advantages_text)) : ?>
            <div class="col-md-6">
               <div class="practice-section advantages card card-body h-100">
                  <h2 class="h3 mb-4"><?php _e('Advantages', 'horizons'); ?></h2>
                  <div class="practice-content body-l-r"><?php echo $advantages_text; ?></div>
               </div>
            </div>
         <?php endif; ?>

         <div class="col-md-6">
            <div class="card shadow border-0 h-100">
               <div class="card-body bg-dusty-navy">
                  <p class="text-line-before label-u text-sub-white">Связаться с нами</p>
                  <div class="h3 text-white mb-6">Получить консультацию <br> нашего специалиста</div>
                  <a href="#" class="btn btn-neutral-50 has-ripple btn-lg w-100" data-ripple-initialized="true">Отправить сообщение</a>
               </div>
            </div>
         </div>

      </div>
   </div>

<?php endif; ?>