<?php get_header(); ?>
<?php while (have_posts()) {
   the_post();
   get_pageheader();
   global $opt_name;
   $pageheader_name = Redux::get_option($opt_name, 'global_page_header_model');
?>

   <section class="video-wrapper  bg-overlay  px-0 mt-0 min-vh-80">
      <video poster="/wp-content/uploads/2025/09/vlcsnap-2025-09-24-16h06m00s512.jpg" src="/wp-content/uploads/2025/09/Timeline-138.mp4" autoplay loop playsinline muted></video>
      <div class="video-content">
         <div class="container text-start">
            <div class="row">
               <div class="col-lg-10 col-xl-10 text-start text-white">
                  <h1 class="display-1 fs-76 lh-1 text-white mb-3 text-uppercase fw-bold"><?php _e('Corporate Advisory Solutions for local, regional and global challenges', 'сodeweber'); ?></h1>
                  <div class="mb-3">
                     <span class="typer text-primary text-uppercase fw-bold fs-48"
                        data-delay="100"
                        data-words="<?php echo do_shortcode('[partner_countries]'); ?>">
                     </span><span class="cursor text-primary w-bold fs-48" data-owner="typer"></span>
                  </div>
                  <span><a href="#practice" class="btn btn-primary has-ripple btn-elg scroll me-2" data-ripple-initialized="true"><?php _e('Practices', 'сodeweber'); ?></a></span>
                  <span><a href="#" class="btn btn-neutral-50 has-ripple btn-elg" data-ripple-initialized="true"><?php _e('Consultation', 'сodeweber'); ?></a></span>
               </div>
            </div>
            <!-- /column -->
         </div>
      </div>
      <!-- /.video-content -->
      </div>
      <!-- /.content-overlay -->
   </section>
   <!-- /section -->

   <section class="wrapper">
      <div class="container py-14 py-md-16">
         <div class="row mb-8">
            <div class="col-md-6 col-lg-4" data-cue="slideInLeft">
               <div class="text-line-before label-u mb-2"><?php _e('About Company', 'сodeweber'); ?></div>
               <h2 class="h2 mb-10"><?php _e('We have been protecting your interests for over 40 years.', 'сodeweber'); ?></h2>
            </div>
            <!-- /column -->
            <div class="col-md-6 col-lg-7 offset-md-1" data-cue="slideInRight">
               <p class="h4"><?php _e('Horizons Advisory was founded in Hong Kong in 1983 as a firm of accountants and auditors.', 'сodeweber'); ?></p>
               <p class="body-l-l"><?php _e('We combine the efforts of international and local specialists working seamlessly across 55 countries to provide a full range of services for your project. Each team member has the necessary knowledge and experience to support you at every stage.', 'сodeweber'); ?></p>
            </div>
            <!-- /column -->
         </div>
         <!-- /.row -->
         <div class="position-relative">
            <div class="row gx-md-5 gy-5">
               <div class="col-md-4">
                  <div class="card h-100" data-cue="slideInDown">
                     <div class="card-body">
                        <div class="pe-none mb-5">
                           <i class="uil uil-balance-scale lh-1 text-dark fs-36"></i>
                        </div>
                        <h3 class="h3"><?php _e('Company history', 'сodeweber'); ?></h3>
                        <p class="mb-5 body-l-l"><?php _e('Horizons Advisory was founded in Hong Kong in 1983 as a firm of accountants and auditors...', 'сodeweber'); ?></p>
                        <a href="#" class="hover-4 link-body label-s"><?php _e('Read more', 'сodeweber'); ?></a>
                     </div>
                     <!--/.card-body -->
                  </div>
                  <!--/.card -->
               </div>
               <!--/column -->
               <div class="col-md-4">
                  <div class="card h-100" data-cue="slideInDown">
                     <div class="card-body">
                        <div class="pe-none mb-5">
                           <i class="uil uil-analytics lh-1 text-dark fs-36"></i>
                        </div>
                        <h3 class="h3"><?php _e('Horizons in Figures', 'сodeweber'); ?></h3>
                        <p class="mb-5 body-l-l"><?php _e('From its original 31 employees in 1983, Horizons Advisory now employs 5,929 people (as of...', 'сodeweber'); ?></p>
                        <a href="#" class="hover-4 link-body label-s"><?php _e('Read more', 'сodeweber'); ?></a>
                     </div>
                     <!--/.card-body -->
                  </div>
                  <!--/.card -->
               </div>
               <!--/column -->
               <div class="col-md-4">
                  <div class="card h-100" data-cue="slideInDown">
                     <div class="card-body">
                        <div class="pe-none mb-5">
                           <i class="uil uil-shield-check lh-1 text-dark fs-36"></i>
                        </div>
                        <h3 class="h3"><?php _e('Confidentiality', 'сodeweber'); ?></h3>
                        <p class="mb-5 body-l-l"><?php _e('To prevent data leakage, abuse, and unauthorized access, the company...', 'сodeweber'); ?></p>
                        <a href="#" class="hover-4 link-body label-s"><?php _e('Read more', 'сodeweber'); ?></a>
                     </div>
                     <!--/.card-body -->
                  </div>
                  <!--/.card -->
               </div>
               <!--/column -->
            </div>
            <!--/.row -->
         </div>
         <!-- /.position-relative -->
      </div>
      <!-- /.container -->
   </section>
   <!-- /section -->


   <section class="wrapper bg-dusty-navy position-relative min-vh-60 d-lg-flex align-items-center">
      <div class="col-lg-6 position-lg-absolute top-0 start-0 image-wrapper bg-image bg-cover h-100" data-image-src="/wp-content/uploads/2025/09/attic-see-city-1-scaled.jpg">
      </div>
      <!--/column -->
      <div class="container position-relative" data-cue="fadeIn" data-delay="600">
         <div class="row gx-0">
            <div class="col-lg-6 offset-lg-6">
               <div class="py-12 py-lg-16 ps-lg-12 py-xxl-15 ps-xxl-15 pe-lg-0 position-relative" data-cues="slideInDown" data-group="page-title">
                  <div class="text-line-before label-u mb-2 text-sub-white"><?php _e('Comprehensive Program', 'сodeweber'); ?></div>
                  <h2 class="h1 mb-4 text-white mt-md-22">China Desk 2025</h2>
                  <p class="text-white body-l-l mb-7"><?php _e('Comprehensive Business Advisory Program for Russian Enterprises in 2025', 'сodeweber'); ?></p>
                  <div>
                     <a href="#" class="hover-4 link-body label-s text-white"><?php _e('Read more', 'сodeweber'); ?></a>
                  </div>
               </div>
            </div>
            <!-- /column -->
         </div>
         <!--/.row -->
      </div>
      <!-- /.container -->
   </section>
   <!-- /section -->


   <section class="wrapper" id="practice">
      <div class="container py-14 py-md-16">
         <div class="row">
            <div class="col-md-6 col-lg-4 pe-10" data-cue="slideInLeft">
               <div class="text-line-before label-u mb-2"><?php _e('Practices', 'сodeweber'); ?></div>
               <h2 class="h2"><?php _e('Key Focus Areas and Practices', 'сodeweber'); ?></h2>
               <p class="body-l-l mb-7"><?php _e('Our company specializes in consulting and legal services, helping businesses solve complex problems, mitigate risks, and grow effectively.', 'сodeweber'); ?></p>
            </div>
            <!-- /column -->
            <div class="col-md-6 col-lg-8" data-cue="slideInRight">
               <div class="row gx-md-5 gy-5">
                  <?php echo do_shortcode('[practice_categories_cards columns="3"]'); ?>
               </div>
               <!--/.row -->
            </div>
            <!-- /column -->
         </div>
         <!-- /.row -->
      </div>
      <!-- /.container -->
   </section>
   <!-- /section -->


   <section class="wrapper practice-section" id="practice">
      <div class="container pb-14 pb-md-16">
         <div class="row">
            <div class="col-md-8" data-cue="slideInLeft">
               <div class="text-line-before label-u mb-2"><?php _e('Partners', 'сodeweber'); ?></div>
               <h2 class="h2"><?php _e('One team, one focal point, one purpose', 'сodeweber'); ?></h2>
               <p class="body-l-l mb-7"><?php _e('Our company specializes in consulting and legal services, helping businesses solve complex problems, minimize risks, and grow effectively.', 'сodeweber'); ?></p>
            </div>
            <!-- /column -->
            <div class="col-12" data-cue="slideInRight">
               <div class="row gx-md-5 gy-5">
                  <?php echo do_shortcode('[partners_grid order="ASC" posts_per_page="3"]'); ?>
               </div>
               <!--/.row -->
            </div>
            <!-- /column -->
         </div>
         <!-- /.row -->
      </div>
      <!-- /.container -->
   </section>
   <!-- /section -->


   <section class="wrapper bg-dusty-navy position-relative min-vh-60 d-lg-flex align-items-center">
      <div class="col-lg-6 position-lg-absolute top-0 end-0 image-wrapper bg-image bg-cover h-100" data-image-src="/wp-content/uploads/2025/09/handshake.jpg">
      </div>
      <!--/column -->
      <div class="container position-relative" data-cue="fadeIn" data-delay="600">
         <div class="row gx-0">
            <div class="col-lg-6">
               <div class="py-12 py-lg-16 pe-lg-12 py-xxl-15 pe-xxl-15 ps-lg-0 position-relative" data-cues="slideInDown" data-group="page-title">
                  <div class="text-line-before label-u mb-2 text-sub-white"><?php _e('Career', 'сodeweber'); ?></div>
                  <h2 class="h1 mb-4 text-white mt-md-22"><?php _e('Start your career at Horizons', 'сodeweber'); ?></h2>
                  <p class="text-white body-l-l mb-7"><?php _e("Join our team! We're looking for talented professionals ready to grow and develop with us. Explore our open positions in your area of ​​interest.", "сodeweber"); ?></p>
                  <div>
                     <a href="/vacancies/" class="hover-4 link-body label-s text-white"><?php _e('All vacancies', 'сodeweber'); ?></a>
                  </div>
               </div>
            </div>
            <!-- /column -->
         </div>
         <!--/.row -->
      </div>
      <!-- /.container -->
   </section>
   <!-- /section -->


   <section class="wrapper awards-section" id="practice">
      <div class="container py-14 py-md-16">
         <div class="row">
            <div class="col-md-8" data-cue="slideInLeft">
               <div class="text-line-before label-u mb-2"><?php _e('Awards', 'сodeweber'); ?></div>
               <h2 class="h2"><?php _e('Awards and publications', 'сodeweber'); ?></h2>
               <p class="body-l-l mb-7"><?php _e('Our team of experienced lawyers and consultants, united by a commitment to excellence, helps clients find optimal solutions and achieve success.', 'сodeweber'); ?></p>
            </div>
            <!-- /column -->
            <div class="col-12" data-cue="slideInRight">
               <?php echo do_shortcode('[awards_grid posts_per_page="7"]'); ?>
               <!--/.row -->
            </div>
            <!-- /column -->
         </div>
         <!-- /.row -->
      </div>
      <!-- /.container -->
   </section>
   <!-- /section -->


   <section class="wrapper blog-section" id="news">
      <div class="container pb-14 pb-md-16">
         <div class="row align-items-center mb-8">
            <div class="col-md-8">
               <div class="text-line-before label-u mb-2"><?php _e('News', 'сodeweber'); ?></div>
               <h2 class="h2"><?php _e('Horizons News', 'сodeweber'); ?></h2>
            </div>
            <div class="col text-md-end">
               <a href="/blog" class="hover-5 label-u right text-charcoal-blue mb-5"><?php _e('All news', 'сodeweber'); ?></a>
            </div>
            <!-- /column -->
         </div>
         <!--/.row -->
         <div class="row">
            <div class="col-12">
               <?php echo do_shortcode('[blog_posts_slider posts_per_page="6" order="ASC" nav="true" dots="true" margin="24" items_xl="4" items_lg="3" items_md="2" items_sm="2" items_xs="1" items_xxs="1" image_size="codeweber_staff" excerpt_length="0" title_length = "58"]'); ?>
            </div>
            <!-- /column -->
         </div>
         <!-- /.row -->
      </div>
      <!-- /.container -->
   </section>
   <!-- /section -->


<?php } ?>
<?php
get_footer();
