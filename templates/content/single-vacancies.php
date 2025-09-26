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
                  echo '<div class="body-l-r mb-12">' . esc_html($vacancy_data['introduction']) . '</div>';
               }


               if (!empty($vacancy_data['requirements']) && is_array($vacancy_data['requirements'])) : ?>
                  <div class="mb-12">
                     <div class="text-line-after label-u mb-6"><?php _e('Requirements', 'horizons'); ?></div>
                     <ul class="unordered-list bullet-primary body-l-r">
                        <?php foreach ($vacancy_data['requirements'] as $requirement) : ?>
                           <li><?php echo esc_html($requirement); ?></li>
                        <?php endforeach; ?>
                     </ul>
                  </div>
            <?php endif;


               if (!empty($vacancy_data['location'])) {
                  echo '<p>' . __('Location', 'horizons') . ': ' . esc_html($vacancy_data['location']) . '</p>';
               }

               if (!empty($vacancy_data['email'])) {
                  echo '<p>' . __('Email', 'horizons') . ': ' . esc_html($vacancy_data['email']) . '</p>';
               }

               if (!empty($vacancy_data['apply_url'])) {
                  echo '<p>' . __('Apply URL', 'horizons') . ': ' . esc_html($vacancy_data['apply_url']) . '</p>';
               }



               if (!empty($vacancy_data['additional_info'])) {
                  echo '<p>' . __('Additional Info', 'horizons') . ': ' . esc_html($vacancy_data['additional_info']) . '</p>';
               }

               if (!empty($vacancy_data['employment_type'])) {
                  echo '<p>' . __('Employment Type', 'horizons') . ': ' . esc_html($vacancy_data['employment_type']) . '</p>';
               }

               if (!empty($vacancy_data['experience'])) {
                  echo '<p>' . __('Experience', 'horizons') . ': ' . esc_html($vacancy_data['experience']) . '</p>';
               }

               if (!empty($vacancy_data['education'])) {
                  echo '<p>' . __('Education', 'horizons') . ': ' . esc_html($vacancy_data['education']) . '</p>';
               }

               if (!empty($vacancy_data['status'])) {
                  echo '<p>' . __('Status', 'horizons') . ': ' . esc_html($vacancy_data['status']) . '</p>';
               }

               // Обработка массивов
               if (!empty($vacancy_data['responsibilities']) && is_array($vacancy_data['responsibilities'])) {
                  echo '<p>' . __('Responsibilities', 'horizons') . ': ' . esc_html(implode(', ', $vacancy_data['responsibilities'])) . '</p>';
               }



               if (!empty($vacancy_data['languages']) && is_array($vacancy_data['languages'])) {
                  echo '<p>' . __('Languages', 'horizons') . ': ' . esc_html(implode(', ', $vacancy_data['languages'])) . '</p>';
               }

               if (!empty($vacancy_data['skills']) && is_array($vacancy_data['skills'])) {
                  echo '<p>' . __('Skills', 'horizons') . ': ' . esc_html(implode(', ', $vacancy_data['skills'])) . '</p>';
               }

               if (!empty($vacancy_data['pdf_url'])) {
                  echo '<p>' . __('PDF URL', 'horizons') . ': ' . esc_html($vacancy_data['pdf_url']) . '</p>';
               }

               if (!empty($vacancy_data['vacancy_types']) && is_array($vacancy_data['vacancy_types'])) {
                  echo '<p>' . __('Vacancy Types', 'horizons') . ': ' . esc_html(implode(', ', wp_list_pluck($vacancy_data['vacancy_types'], 'name'))) . '</p>';
               }
            }


            printr($vacancy_data);

            ?>
         </div>
         <!-- /.post-content -->
         <div class="post-footer d-md-flex flex-md-row justify-content-md-between align-items-center mt-8">
            <div>
               <ul class="list-unstyled tag-list mb-0">
                  <li><a href="#" class="btn btn-soft-ash btn-sm rounded-pill mb-0">Still Life</a></li>
                  <li><a href="#" class="btn btn-soft-ash btn-sm rounded-pill mb-0">Urban</a></li>
                  <li><a href="#" class="btn btn-soft-ash btn-sm rounded-pill mb-0">Nature</a></li>
               </ul>
            </div>
            <div class="mb-0 mb-md-2">
               <div class="dropdown share-dropdown btn-group">
                  <button class="btn btn-sm btn-red rounded-pill btn-icon btn-icon-start dropdown-toggle mb-0 me-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <i class="uil uil-share-alt"></i> Share </button>
                  <div class="dropdown-menu">
                     <a class="dropdown-item" href="#"><i class="uil uil-twitter"></i>Twitter</a>
                     <a class="dropdown-item" href="#"><i class="uil uil-facebook-f"></i>Facebook</a>
                     <a class="dropdown-item" href="#"><i class="uil uil-linkedin"></i>Linkedin</a>
                  </div>
                  <!--/.dropdown-menu -->
               </div>
               <!--/.share-dropdown -->
            </div>
         </div>
         <!-- /.post-footer -->
      </article>
      <!-- /.post -->
   </div>
   <!-- /.classic-view -->
</div>