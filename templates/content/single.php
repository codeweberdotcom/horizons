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
					<div class="post-footer d-md-flex flex-md-row justify-content-md-between align-items-center mt-8">
						<div>
							<?php
							$tags = get_the_tags();

							if ($tags) : ?>
								<ul class="list-unstyled tag-list mb-0">
									<?php foreach ($tags as $tag) : ?>
										<li>
											<a
												href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"
												class="btn btn-primary btn-xs has-ripple mb-0<?php echo getThemeButton(); ?>">
												<?php echo esc_html($tag->name); ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
						<div class="mb-0 mb-md-2">
							<?php codeweber_share_page(['region' => 'eu', 'button_class' => 'btn btn-dusty-navy has-ripple btn-xs btn-icon btn-icon-start dropdown-toggle mb-0 me-0']); ?>
						</div>
					</div>
					<!-- /.post-footer -->
				</article>
				<!-- /.post -->
			</div>
			<!-- /.classic-view -->
			<hr>
			<div>
				<?php

				wp_link_pages(
					array(
						'before'        => '<nav class="nav"><span class="nav-link">' . esc_html__('Part:', 'codeweber') . '</span>',
						'after'         => '</nav>',
						'link_before'   => '<span class="nav-link">',
						'link_after'    => '</span>',
					)
				);

				?>

			</div>
			<div class="author-info d-md-flex align-items-center">
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
						<img decoding="async" class="w-48 h-48  me-3" alt="<?php the_author_meta('display_name'); ?>" src="<?php echo esc_url($avatar_src[0]); ?>">
					<?php else : ?>
						<figure class="me-3">
							<?php echo get_avatar(get_the_author_meta('user_email'), 48); ?>
						</figure>
					<?php endif; ?>

					<div class="avatar-info mt-0">
						<a href="<?php echo esc_url(get_author_posts_url($user_id)); ?>" class="hover-7 link-body label-u text-charcoal-blue  d-block lh-0">
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
					<a href="<?php echo esc_url(get_author_posts_url($user_id)); ?>" class="btn btn-xs btn-charcoal-blue <?php echo esc_attr(GetThemeButton('rounded mt-2')); ?> btn-icon btn-icon-start mb-0">
						<i class="uil uil-file-alt"></i> <?php esc_html_e('All Posts', 'codeweber'); ?>
					</a>
				</div>
			</div>
			<!-- /.author-info -->



			<!-- /.author-info -->

			<?php $bio = get_user_meta($user_id, 'description', true); ?>
			<?php
			if (!empty($bio)) : ?>
				<p><?php echo esc_html($bio); ?></p>
			<?php endif; ?>
			<!-- /.author-bio -->

			<nav class="nav social">
				<a href="#"><i class="uil uil-twitter"></i></a>
				<a href="#"><i class="uil uil-facebook-f"></i></a>
				<a href="#"><i class="uil uil-dribbble"></i></a>
				<a href="#"><i class="uil uil-instagram"></i></a>
				<a href="#"><i class="uil uil-youtube"></i></a>
			</nav>
			<!-- /.social -->

			<hr />
			<?php get_template_part('templates/components/lastpostslider-blog'); ?>
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