<?php

/**
 * Функция для вывода всех данных вакансии
 */
function display_vacancy_data($post_id = null)
{
   if (!$post_id) {
      global $post;
      $post_id = $post->ID;
   }

   // Проверяем, что это запись типа vacancies
   if (get_post_type($post_id) !== 'vacancies') {
      return '';
   }

   // Получаем все метаполя
   $company = get_post_meta($post_id, '_vacancy_company', true);
   $location = get_post_meta($post_id, '_vacancy_location', true);
   $email = get_post_meta($post_id, '_vacancy_email', true);
   $apply_url = get_post_meta($post_id, '_vacancy_apply_url', true);
   $introduction = get_post_meta($post_id, '_vacancy_introduction', true);
   $additional_info = get_post_meta($post_id, '_vacancy_additional_info', true);
   $employment_type = get_post_meta($post_id, '_vacancy_employment_type', true);
   $experience = get_post_meta($post_id, '_vacancy_experience', true);
   $education = get_post_meta($post_id, '_vacancy_education', true);
   $status = get_post_meta($post_id, '_vacancy_status', true);

   // Получаем массивы
   $responsibilities = get_post_meta($post_id, '_vacancy_responsibilities', true);
   $requirements = get_post_meta($post_id, '_vacancy_requirements', true);
   $languages = get_post_meta($post_id, '_vacancy_languages', true);
   $skills = get_post_meta($post_id, '_vacancy_skills', true);

   // PDF файл
   $pdf_id = get_post_meta($post_id, '_vacancy_pdf', true);
   $pdf_url = $pdf_id ? wp_get_attachment_url($pdf_id) : '';

   // Таксономия
   $vacancy_types = get_the_terms($post_id, 'vacancy_type');

   ob_start();
?>

   <div class="vacancy-details">

      <!-- Статус вакансии -->
      <?php if ($status): ?>
         <div class="vacancy-status status-<?php echo esc_attr($status); ?>">
            <strong><?php _e('Status:', 'codeweber'); ?></strong>
            <?php
            $status_labels = [
               'open' => __('Open', 'codeweber'),
               'closed' => __('Closed', 'codeweber'),
               'archived' => __('Archived', 'codeweber')
            ];
            echo esc_html($status_labels[$status] ?? $status);
            ?>
         </div>
      <?php endif; ?>

      <!-- Основная информация -->
      <div class="vacancy-basic-info">
         <?php if ($company): ?>
            <div class="vacancy-field">
               <strong><?php _e('Company:', 'codeweber'); ?></strong>
               <span><?php echo esc_html($company); ?></span>
            </div>
         <?php endif; ?>

         <?php if ($location): ?>
            <div class="vacancy-field">
               <strong><?php _e('Location:', 'codeweber'); ?></strong>
               <span><?php echo esc_html($location); ?></span>
            </div>
         <?php endif; ?>

         <?php if ($employment_type): ?>
            <div class="vacancy-field">
               <strong><?php _e('Employment Type:', 'codeweber'); ?></strong>
               <span><?php echo esc_html(ucfirst(str_replace('-', ' ', $employment_type))); ?></span>
            </div>
         <?php endif; ?>

         <?php if ($experience): ?>
            <div class="vacancy-field">
               <strong><?php _e('Experience:', 'codeweber'); ?></strong>
               <span><?php echo esc_html($experience); ?></span>
            </div>
         <?php endif; ?>

         <?php if ($vacancy_types && !is_wp_error($vacancy_types)): ?>
            <div class="vacancy-field">
               <strong><?php _e('Vacancy Type:', 'codeweber'); ?></strong>
               <span>
                  <?php
                  $type_names = array_map(function ($term) {
                     return $term->name;
                  }, $vacancy_types);
                  echo esc_html(implode(', ', $type_names));
                  ?>
               </span>
            </div>
         <?php endif; ?>
      </div>

      <!-- Введение -->
      <?php if ($introduction): ?>
         <div class="vacancy-section">
            <h3><?php _e('Introduction', 'codeweber'); ?></h3>
            <div class="vacancy-content">
               <?php echo wpautop(wp_kses_post($introduction)); ?>
            </div>
         </div>
      <?php endif; ?>

      <!-- Обязанности -->
      <?php if ($responsibilities && is_array($responsibilities) && !empty(array_filter($responsibilities))): ?>
         <div class="vacancy-section">
            <h3><?php _e('Responsibilities', 'codeweber'); ?></h3>
            <ul class="vacancy-list">
               <?php foreach ($responsibilities as $responsibility):
                  if (!empty(trim($responsibility))): ?>
                     <li><?php echo esc_html($responsibility); ?></li>
                  <?php endif; ?>
               <?php endforeach; ?>
            </ul>
         </div>
      <?php endif; ?>

      <!-- Требования -->
      <?php if ($requirements && is_array($requirements) && !empty(array_filter($requirements))): ?>
         <div class="vacancy-section">
            <h3><?php _e('Requirements', 'codeweber'); ?></h3>
            <ul class="vacancy-list">
               <?php foreach ($requirements as $requirement):
                  if (!empty(trim($requirement))): ?>
                     <li><?php echo esc_html($requirement); ?></li>
                  <?php endif; ?>
               <?php endforeach; ?>
            </ul>
         </div>
      <?php endif; ?>

      <!-- Образование -->
      <?php if ($education): ?>
         <div class="vacancy-section">
            <h3><?php _e('Education & Qualification', 'codeweber'); ?></h3>
            <div class="vacancy-content">
               <?php echo wpautop(wp_kses_post($education)); ?>
            </div>
         </div>
      <?php endif; ?>

      <!-- Языки -->
      <?php if ($languages && is_array($languages) && !empty(array_filter($languages))): ?>
         <div class="vacancy-section">
            <h3><?php _e('Languages', 'codeweber'); ?></h3>
            <ul class="vacancy-list">
               <?php foreach ($languages as $language):
                  if (!empty(trim($language))): ?>
                     <li><?php echo esc_html($language); ?></li>
                  <?php endif; ?>
               <?php endforeach; ?>
            </ul>
         </div>
      <?php endif; ?>

      <!-- Навыки -->
      <?php if ($skills && is_array($skills) && !empty(array_filter($skills))): ?>
         <div class="vacancy-section">
            <h3><?php _e('Skills', 'codeweber'); ?></h3>
            <div class="vacancy-skills">
               <?php foreach ($skills as $skill):
                  if (!empty(trim($skill))): ?>
                     <span class="skill-tag"><?php echo esc_html($skill); ?></span>
                  <?php endif; ?>
               <?php endforeach; ?>
            </div>
         </div>
      <?php endif; ?>

      <!-- Дополнительная информация -->
      <?php if ($additional_info): ?>
         <div class="vacancy-section">
            <h3><?php _e('Additional Information', 'codeweber'); ?></h3>
            <div class="vacancy-content">
               <?php echo wpautop(wp_kses_post($additional_info)); ?>
            </div>
         </div>
      <?php endif; ?>

      <!-- Кнопки применения -->
      <div class="vacancy-actions">
         <?php if ($email): ?>
            <a href="mailto:<?php echo esc_attr($email); ?>?subject=Application for <?php echo rawurlencode(get_the_title($post_id)); ?>"
               class="btn btn-primary">
               <?php _e('Apply by Email', 'codeweber'); ?>
            </a>
         <?php endif; ?>

         <?php if ($apply_url): ?>
            <a href="<?php echo esc_url($apply_url); ?>" target="_blank" class="btn btn-secondary">
               <?php _e('Apply Online', 'codeweber'); ?>
            </a>
         <?php endif; ?>

         <?php if ($pdf_url): ?>
            <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" class="btn btn-outline">
               <?php _e('Download PDF Version', 'codeweber'); ?>
            </a>
         <?php endif; ?>
      </div>

   </div>

   <style>
      .vacancy-details {
         max-width: 800px;
         margin: 0 auto;
      }

      .vacancy-status {
         padding: 10px;
         margin-bottom: 20px;
         border-radius: 5px;
         text-align: center;
         font-weight: bold;
      }

      .status-open {
         background: #d4edda;
         color: #155724;
      }

      .status-closed {
         background: #f8d7da;
         color: #721c24;
      }

      .status-archived {
         background: #e2e3e5;
         color: #383d41;
      }

      .vacancy-basic-info {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
         gap: 15px;
         margin-bottom: 30px;
         padding: 20px;
         background: #f8f9fa;
         border-radius: 5px;
      }

      .vacancy-field {
         display: flex;
         flex-direction: column;
      }

      .vacancy-field strong {
         margin-bottom: 5px;
         color: #495057;
      }

      .vacancy-section {
         margin-bottom: 30px;
         padding-bottom: 20px;
         border-bottom: 1px solid #e9ecef;
      }

      .vacancy-section h3 {
         color: #212529;
         margin-bottom: 15px;
         font-size: 1.3em;
      }

      .vacancy-content {
         line-height: 1.6;
      }

      .vacancy-list {
         list-style: none;
         padding-left: 0;
      }

      .vacancy-list li {
         position: relative;
         padding-left: 20px;
         margin-bottom: 8px;
         line-height: 1.5;
      }

      .vacancy-list li:before {
         content: "•";
         color: #007bff;
         position: absolute;
         left: 0;
         font-weight: bold;
      }

      .vacancy-skills {
         display: flex;
         flex-wrap: wrap;
         gap: 10px;
      }

      .skill-tag {
         background: #007bff;
         color: white;
         padding: 5px 12px;
         border-radius: 20px;
         font-size: 0.9em;
      }

      .vacancy-actions {
         display: flex;
         gap: 15px;
         flex-wrap: wrap;
         margin-top: 30px;
         padding-top: 20px;
         border-top: 2px solid #dee2e6;
      }

      .btn {
         padding: 12px 24px;
         text-decoration: none;
         border-radius: 5px;
         font-weight: 500;
         transition: all 0.3s ease;
      }

      .btn-primary {
         background: #007bff;
         color: white;
      }

      .btn-secondary {
         background: #6c757d;
         color: white;
      }

      .btn-outline {
         border: 2px solid #007bff;
         color: #007bff;
         background: transparent;
      }

      .btn:hover {
         opacity: 0.9;
         transform: translateY(-2px);
      }

      @media (max-width: 768px) {
         .vacancy-basic-info {
            grid-template-columns: 1fr;
         }

         .vacancy-actions {
            flex-direction: column;
         }

         .btn {
            text-align: center;
         }
      }
   </style>

<?php
   return ob_get_clean();
}
