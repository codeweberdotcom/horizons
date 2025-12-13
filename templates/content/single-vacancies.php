<?php
$vacancy_data_array = get_vacancy_data_array($post->ID);
?>
<div class="blog single">
   <div class="classic-view">
      <article class="post">
         <div class="post-content mb-5">
            <?php
            $vacancy_data = get_vacancy_data_array();

            if ($vacancy_data) {

               if (!empty($vacancy_data['introduction'])) {
                  echo '<div class="body-l-r mb-12">' . wp_kses_post($vacancy_data['introduction']) . '</div>';
               }
            ?>
               <?php if (!empty($vacancy_data['requirements']) && is_array($vacancy_data['requirements'])) : ?>
                  <div class="mb-12">
                     <div class="text-line-after label-u mb-6"><?php _e('Requirements', 'horizons'); ?></div>
                     <ul class="unordered-list bullet-primary body-l-r">
                        <?php foreach ($vacancy_data['requirements'] as $requirement) : ?>
                           <li><?php echo esc_html($requirement); ?></li>
                        <?php endforeach; ?>
                     </ul>
                  </div>
               <?php endif; ?>


               <?php if (!empty($vacancy_data['responsibilities']) && is_array($vacancy_data['responsibilities'])) : ?>
                  <div class="mb-12">
                     <div class="text-line-after label-u mb-6"><?php _e('Responsibilities', 'horizons'); ?></div>
                     <ul class="unordered-list bullet-primary body-l-r">
                        <?php foreach ($vacancy_data['responsibilities'] as $responsibility) : ?>
                           <li><?php echo esc_html($responsibility); ?></li>
                        <?php endforeach; ?>
                     </ul>
                  </div>
            <?php endif;


               if (!empty($vacancy_data['additional_info'])) {
                  echo '<div class="body-l-r">' . wp_kses_post($vacancy_data['additional_info']) . '</div>';
               }

               // if (!empty($vacancy_data['location'])) {
               //    echo '<p>' . __('Location', 'horizons') . ': ' . esc_html($vacancy_data['location']) . '</p>';
               // }

               // if (!empty($vacancy_data['employment_type'])) {
               //    echo '<p>' . __('Employment Type', 'horizons') . ': ' . esc_html($vacancy_data['employment_type']) . '</p>';
               // }

               // if (!empty($vacancy_data['experience'])) {
               //    echo '<p>' . __('Experience', 'horizons') . ': ' . esc_html($vacancy_data['experience']) . '</p>';
               // }

               // if (!empty($vacancy_data['education'])) {
               //    echo '<p>' . __('Education', 'horizons') . ': ' . esc_html($vacancy_data['education']) . '</p>';
               // }

               // if (!empty($vacancy_data['status'])) {
               //    echo '<p>' . __('Status', 'horizons') . ': ' . esc_html($vacancy_data['status']) . '</p>';
               // }

               // if (!empty($vacancy_data['languages']) && is_array($vacancy_data['languages'])) {
               //    echo '<p>' . __('Languages', 'horizons') . ': ' . esc_html(implode(', ', $vacancy_data['languages'])) . '</p>';
               // }

               // if (!empty($vacancy_data['skills']) && is_array($vacancy_data['skills'])) {
               //    echo '<p>' . __('Skills', 'horizons') . ': ' . esc_html(implode(', ', $vacancy_data['skills'])) . '</p>';
               // }

               // if (!empty($vacancy_data['vacancy_types']) && is_array($vacancy_data['vacancy_types'])) {
               //    echo '<p>' . __('Vacancy Types', 'horizons') . ': ' . esc_html(implode(', ', wp_list_pluck($vacancy_data['vacancy_types'], 'name'))) . '</p>';
               // }

               //    if (!empty($vacancy_data['pdf_url'])) {
               //    echo '<p>' . __('PDF URL', 'horizons') . ': ' . esc_html($vacancy_data['pdf_url']) . '</p>';
               // }
            } else {
               // Если данных вакансии нет, выводим стандартный контент поста
               the_content();
            }

            ?>
         </div>
         <!-- /.post-content -->

         <div class="post-footer d-md-flex flex-md-row justify-content-md-between align-items-center mt-8">
            <?php $vacancy_data = get_vacancy_data_array(); ?>
            <?php if ($vacancy_data) { ?>
               <div class="d-flex">
                  <?php
                  if (!empty($vacancy_data['email'])) {
                  ?>
                     <a href="mailto:<?php esc_html($vacancy_data['email']); ?>" class="btn btn-circle btn-outline-dusty-navy has-ripple btn-sm me-1"><i class="uil uil uil-envelope"></i></a>
                  <?php
                  }
                  ?>
                  <?php
                  if (!empty($vacancy_data['apply_url'])) {
                  ?>
                     <a href="<?php esc_html($vacancy_data['apply_url']); ?>" class="btn btn-circle btn-outline-dusty-navy has-ripple btn-sm me-1"><i class="uil uil uil-link"></i></a>
                  <?php
                  }
                  ?>
                  <?php
                  if (!empty($vacancy_data['linkedin_url'])) {
                  ?>
                     <a href="<?php esc_html($vacancy_data['linkedin_url']); ?>" class="btn btn-circle btn-outline-dusty-navy has-ripple btn-sm me-1"><i class="uil uil uil-linkedin"></i></a>
                  <?php
                  }
                  ?>
               </div>
            <?php  } ?>
            <div class="mt-3 mt-md-0 ms-auto">
               <?php codeweber_share_page(['region' => 'eu', 'button_class' => 'btn btn-dusty-navy has-ripple btn-xs btn-icon btn-icon-start dropdown-toggle mb-0 me-0']); ?>
            </div>
         </div>
         <!-- /.post-footer -->
      </article>
      <!-- /.post -->
   </div>
   <!-- /.classic-view -->
</div>