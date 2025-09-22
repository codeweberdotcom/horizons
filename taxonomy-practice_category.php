<?php get_header(); ?>
<?php get_pageheader(); ?>
<?php
$post_type = get_post_type();
$post_type_lc = strtolower($post_type);
$sidebar_position = Redux::get_option($opt_name, 'sidebar_position_archive_' . $post_type);
$pageheader_name = Redux::get_option($opt_name, 'global_page_header_model');


// Определяем класс колонки для контента
$content_class = ($sidebar_position === 'none') ? 'col-12' : 'col-md-8';
?>

<section class="bg-light">
   <div class="container py-15 py-md-17 pb-md-25">


      <div class="accordion accordion-wrapper" id="accordionSimpleExample">
         <div class="card plain accordion-item">
            <div class="card-header" id="headingSimpleOne">
               <button class="accordion-button h4" data-bs-toggle="collapse" data-bs-target="#collapseSimpleOne" aria-expanded="true" aria-controls="collapseSimpleOne"> Professional Design </button>
            </div>
            <!--/.card-header -->
            <div id="collapseSimpleOne" class="accordion-collapse collapse show" aria-labelledby="headingSimpleOne" data-bs-parent="#accordionSimpleExample">
               <div class="card-body">
                  <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Cras mattis consectetur purus sit amet fermentum. Praesent commodo cursus magna, vel.</p>
               </div>
               <!--/.card-body -->
            </div>
            <!--/.accordion-collapse -->
         </div>
         <!--/.accordion-item -->
         <div class="card plain accordion-item">
            <div class="card-header" id="headingSimpleTwo">
               <button class="collapsed h4" data-bs-toggle="collapse" data-bs-target="#collapseSimpleTwo" aria-expanded="false" aria-controls="collapseSimpleTwo"> Top-Notch Support </button>
            </div>
            <!--/.card-header -->
            <div id="collapseSimpleTwo" class="accordion-collapse collapse" aria-labelledby="headingSimpleTwo" data-bs-parent="#accordionSimpleExample">
               <div class="card-body">
                  <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Cras mattis consectetur purus sit amet fermentum. Praesent commodo cursus magna, vel.</p>
               </div>
               <!--/.card-body -->
            </div>
            <!--/.accordion-collapse -->
         </div>
         <!--/.accordion-item -->
         <div class="card plain accordion-item">
            <div class="card-header" id="headingSimpleThree">
               <button class="collapsed h4" data-bs-toggle="collapse" data-bs-target="#collapseSimpleThree" aria-expanded="false" aria-controls="collapseSimpleThree"> Header and Slider Options </button>
            </div>
            <!--/.card-header -->
            <div id="collapseSimpleThree" class="accordion-collapse collapse" aria-labelledby="headingSimpleThree" data-bs-parent="#accordionSimpleExample">
               <div class="card-body">
                  <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Cras mattis consectetur purus sit amet fermentum. Praesent commodo cursus magna, vel.</p>
               </div>
               <!--/.card-body -->
            </div>
            <!--/.accordion-collapse -->
         </div>
         <!--/.accordion-item -->
      </div>
      <!--/.accordion -->


   </div>
   <!-- /.container -->
</section>



<?php get_footer(); ?>