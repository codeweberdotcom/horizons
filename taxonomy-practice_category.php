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
      <div class="row d-flex align-items-start gy-10">
         <div class="col-lg-5 position-lg-sticky" style="top: 8rem;">
            <div class="text-line-before label-u">Subtitle</div>
            <h3 class="h2 mb-5">Корпоративный сектор</a>
         </div>
         <!-- /column -->
         <div class="col-lg-6 ms-auto">

            <a class="btn btn-primary btn-icon btn-icon-end rounded mb-2 w-100">
               Google Play <i class="uil uil-google-play"></i>
            </a>

            <div class="card mb-2">
               <div class="card-body d-flex flex-row p-3 ps-4">
                  <a href="#" class="h4 mb-0">Marketing Automation</a>
               </div>
               <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="card mb-2">
               <div class="card-body d-flex flex-row p-3 ps-4">
                  <a href="#" class="h4 mb-0">Project Management</a>
               </div>
               <!-- /.card-body -->
            </div>
            <!-- /.card -->

         </div>
         <!-- /column -->
      </div>
      <!-- /.row -->
   </div>
   <!-- /.container -->
</section>



<?php get_footer(); ?>