<section id="post-<?php the_ID(); ?>" <?php post_class('blog single'); ?>>
   <div class="card">

      <figure class="card-img-top">
         <?php
         // Получаем ID миниатюры текущего поста
         $thumbnail_id = get_post_thumbnail_id();

         // Получаем URL изображения размера 'large'
         $large_image_url = wp_get_attachment_image_src($thumbnail_id, 'codeweber_extralarge');

         if ($large_image_url) :
         ?>
            <a href="<?php echo esc_url($large_image_url[0]); ?>" data-glightbox data-gallery="g1">
               <?php the_post_thumbnail('codeweber_extralarge', array('class' => 'img-fluid')); ?>
            </a>
         <?php endif; ?>
      </figure>

      <div class="card-body">
         <div class="classic-view">
            <article class="post">
               <div class="post-content mb-5">
                  <?php the_content(); ?>
               </div>
               <!-- /.post-content -->
               <?php
               $tags = get_the_tags();
               if ($tags) : ?>
                  <div class="post-footer d-md-flex flex-md-row justify-content-md-between align-items-center mt-8">
                     <div>
                        <ul class="list-unstyled tag-list mb-0">
                           <?php foreach ($tags as $tag) : ?>
                              <li>
                                 <a
                                    href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"
                                    class="btn btn-primary btn-xs has-ripple py-1 mb-0<?php echo getThemeButton(); ?>">
                                    <?php echo esc_html($tag->name); ?>
                                 </a>
                              </li>
                           <?php endforeach; ?>
                        </ul>
                     </div>
                  </div>
                  <!-- /.post-footer -->
               <?php endif; ?>
            </article>
            <!-- /.post -->
         </div>
         <!-- /.classic-view -->
         <hr>
         <?php
         $link_pages = wp_link_pages(
            array(
               'before'        => '<nav class="nav"><span class="nav-link">' . esc_html__('Part:', 'codeweber') . '</span>',
               'after'         => '</nav>',
               'link_before'   => '<span class="nav-link">',
               'link_after'    => '</span>',
               'echo'          => false,
            )
         );
         if (!empty($link_pages)) : ?>
            <div>
               <?php echo $link_pages; ?>
            </div>
         <?php endif; ?>
         <div class="author-info d-md-flex align-items-center my-5">
            <div class="d-flex align-items-center">

               <?php
               $user_id = get_the_author_meta('ID');

               // Проверяем оба возможных ключа
               $avatar_id = get_user_meta($user_id, 'avatar_id', true);
               if (empty($avatar_id)) {
                  $avatar_id = get_user_meta($user_id, 'custom_avatar_id', true);
               }

               if (!empty($avatar_id)) :
                  $avatar_src = wp_get_attachment_image_src($avatar_id, 'thumbnail');
               ?>
                  <img decoding="async" class="w-48 h-48  me-3 shadow-lg" alt="<?php the_author_meta('display_name'); ?>" src="<?php echo esc_url($avatar_src[0]); ?>">
               <?php else : ?>
                  <figure class="me-3">
                     <?php echo get_avatar(get_the_author_meta('user_email'), 48); ?>
                  </figure>
               <?php endif; ?>


               <?php
               $user_link = get_user_partner_link($user_id);
               ?>

               <div class="avatar-info mt-0">
                  <a href="<?php echo esc_url($user_link['url']); ?>" class="hover-7 link-body label-u text-charcoal-blue  d-block lh-0">
                     <?php the_author_meta('first_name'); ?> <?php the_author_meta('last_name'); ?>
                  </a>

                  <?php
                  $job_title = get_user_meta($user_id, 'user_position', true);
                  if (empty($job_title)) {
                     $job_title = __('Writer', 'codeweber');
                  }
                  ?>
                  <span class="body-s lh-0 text-neutral-500"><?php echo esc_html($job_title); ?></span>
               </div>
            </div>

            <div class="mt-3 mt-md-0 ms-auto">
               <?php codeweber_share_page(['region' => 'eu', 'button_class' => 'btn btn-dusty-navy has-ripple btn-xs btn-icon btn-icon-start dropdown-toggle mb-0 me-0']); ?>
            </div>
         </div>
         <!-- /.author-info -->

         <?php $bio = get_user_meta($user_id, 'description', true); ?>
         <?php
         if (!empty($bio)) : ?>
            <p><?php echo esc_html($bio); ?></p>
         <?php endif; ?>
         <!-- /.author-bio -->


         <hr class="mb-5"/>
         <?php get_template_part('templates/components/another-awards-grid'); ?>
         <?php
         if (comments_open() || get_comments_number()) { ?>
            <hr />
         <?php
            comments_template();
         }
         ?>
      </div>

   </div>

</section> <!-- #post-<?php the_ID(); ?> -->

