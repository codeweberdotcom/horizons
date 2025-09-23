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


<?php
/**
 * Функция для вывода секции с FAQ по выбранным категориям и тегам
 */
function display_faq_section($post_id)
{
   // Получаем выбранные категории и теги FAQ из метаполей
   $selected_faq_categories = get_post_meta($post_id, 'related_faq_categories', true);
   $selected_faq_tags = get_post_meta($post_id, 'related_faq_tags', true);

   // Если нет выбранных категорий и тегов, выходим
   if (empty($selected_faq_categories) && empty($selected_faq_tags)) {
      return;
   }

   // Подготавливаем аргументы для WP_Query
   $args = array(
      'post_type' => 'faq', // предполагаем, что CPT называется 'faq'
      'posts_per_page' => -1, // получаем все записи
      'orderby' => 'title',
      'order' => 'ASC',
      'tax_query' => array(
         'relation' => 'OR' // Ищем посты, которые имеют любую из указанных таксономий
      )
   );

   // Добавляем таксономии в tax_query только если они выбраны
   $tax_queries = array();

   if (!empty($selected_faq_categories)) {
      $tax_queries[] = array(
         'taxonomy' => 'faq_categories',
         'field' => 'term_id',
         'terms' => $selected_faq_categories,
         'operator' => 'IN'
      );
   }

   if (!empty($selected_faq_tags)) {
      $tax_queries[] = array(
         'taxonomy' => 'faq_tag',
         'field' => 'term_id',
         'terms' => $selected_faq_tags,
         'operator' => 'IN'
      );
   }

   // Если есть хотя бы одна таксономия, добавляем tax_query
   if (!empty($tax_queries)) {
      $args['tax_query'] = $tax_queries;

      // Если есть и категории и теги, меняем relation на AND
      if (!empty($selected_faq_categories) && !empty($selected_faq_tags)) {
         $args['tax_query']['relation'] = 'AND';
      }
   }

   // Выполняем запрос
   $faq_query = new WP_Query($args);

   // Если есть посты, выводим секцию с аккордеоном
   if ($faq_query->have_posts()) {
      ob_start(); // Начинаем буферизацию вывода
?>

      <section class="wrapper bg-dusty-navy" id="practice_faq">
         <div class="container py-14 py-md-16">
            <div class="row">
               <div class="col-md-6 col-lg-5 pe-10" data-cue="slideInLeft">
                  <div class="text-line-before label-u text-sub-white mb-2">FAQ</div>
                  <h2 class="h2 text-white"><?= __('Frequently Asked Questions', 'horizons'); ?></h2>
                  <p class="body-l-l mb-7 text-white">Nec dignissim sagittis amet varius ac et elementum ut. Dignissim risus integer phasellus bibendum pulvinar sit.</p>
               </div>
               <!-- /column -->
               <div class="col-md-6 col-lg-7" data-cue="slideInRight">
                  <div class="accordion accordion-wrapper" id="accordionFaqExample">
                     <?php
                     $counter = 0;
                     while ($faq_query->have_posts()) {
                        $faq_query->the_post();
                        $counter++;

                        // Получаем ID текущего поста
                        $faq_id = get_the_ID();

                        // Создаем уникальные ID для аккордеона
                        $heading_id = 'headingFaq' . $counter;
                        $collapse_id = 'collapseFaq' . $counter;

                        // Определяем, должен ли первый элемент быть открытым
                        $show_class = ($counter === 1) ? 'show' : '';
                        $expanded = ($counter === 1) ? 'true' : 'false';
                        $collapsed_class = ($counter === 1) ? '' : 'collapsed';
                     ?>

                        <div class="card plain accordion-item">
                           <div class="card-header" id="<?php echo $heading_id; ?>">
                              <button class="accordion-button text-white <?php echo $collapsed_class; ?>"
                                 data-bs-toggle="collapse"
                                 data-bs-target="#<?php echo $collapse_id; ?>"
                                 aria-expanded="<?php echo $expanded; ?>"
                                 aria-controls="<?php echo $collapse_id; ?>">
                                 <?php the_title(); ?>
                              </button>
                           </div>
                           <!--/.card-header -->
                           <div id="<?php echo $collapse_id; ?>"
                              class="accordion-collapse collapse <?php echo $show_class; ?>"
                              aria-labelledby="<?php echo $heading_id; ?>"
                              data-bs-parent="#accordionFaqExample">
                              <div class="card-body text-white">
                                 <?php the_content(); ?>
                              </div>
                              <!--/.card-body -->
                           </div>
                           <!--/.accordion-collapse -->
                        </div>
                        <!--/.accordion-item -->

                     <?php
                     }
                     ?>
                  </div>
                  <!--/.accordion -->
               </div>
               <!-- /column -->
            </div>
            <!-- /.row -->
         </div>
         <!-- /.container -->
      </section>
      <!-- /section -->

<?php
      $output = ob_get_clean(); // Получаем содержимое буфера
      echo $output; // Выводим результат

      // Сбрасываем postdata
      wp_reset_postdata();
   }
}