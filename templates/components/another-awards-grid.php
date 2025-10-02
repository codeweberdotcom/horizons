<?php

/**
 * Blog Lats Posts - Slider
 */
?>

<?php
$my_posts = new WP_Query;
$myposts = $my_posts->query(array(
   'post_type' => 'awards'
)); ?>
<h3 class="mb-6"><?php esc_html_e('Other publications', 'horizons'); ?></h3>
<div class="swiper-container blog grid-view mb-6" data-margin="30" data-nav="false" data-dots="true" data-items-md="2" data-items-xs="1">
   <div class="swiper">
      <div class="swiper-wrapper">
         <?php
         // обрабатываем результат
         foreach ($myposts as $post_single) {
            setup_postdata($post_single);
         ?>
            <div class="swiper-slide">
               <article>
                  <figure class="overlay overlay-1 hover-scale rounded mb-5">
                     <a href="<?php the_permalink($post_single->ID); ?>">
                        <img src="<?php echo get_the_post_thumbnail_url($post_single->ID, 'codeweber_single'); ?>" alt=""><span class="bg"></span></a>
                     <figcaption>
                        <div class="from-top mb-0 h5"><?php esc_html_e('Read More', 'horizons'); ?></div>
                     </figcaption>
                  </figure>
               </article>
               <!-- /article -->
            </div>
            <!--/.swiper-slide -->
         <?php } ?>
         <?php wp_reset_postdata(); ?>
      </div>
      <!--/.swiper-wrapper -->
   </div>
   <!-- /.swiper -->
</div>