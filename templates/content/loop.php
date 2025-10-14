<article>
	<div class="post-col">
		<figure class="post-figure overlay overlay-1 hover-scale rounded mb-5">
			<a href="<?php the_permalink(); ?>">
				<?php if (has_post_thumbnail()) : ?>
					<?php
					// Используем значение по умолчанию если $atts не определен
					$image_size = isset($atts['image_size']) ? $atts['image_size'] : 'medium';
					$thumbnail = get_the_post_thumbnail(get_the_ID(), $image_size, array(
						'decoding' => 'async',
						'alt' => esc_attr(get_the_title()),
						'class' => 'post-image swiper-lazy'
					));
					echo $thumbnail;
					?>
				<?php else : ?>
					<img decoding="async"
						src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/placeholder_850x480.jpg'); ?>"
						alt="<?php echo esc_attr(get_the_title()); ?>"
						class="post-image swiper-lazy" />
				<?php endif; ?>

				<!-- Всегда отображаем основную категорию -->
				<div class="caption-wrapper p-7">
					<div class="caption bg-matte-color mt-auto label-u text-neutral-50 px-4 py-2">
						<?php
						$categories = get_the_category();
						if (!empty($categories)) {
							echo esc_html($categories[0]->name);
						}
						?>
					</div>
				</div>
				<span class="bg"></span>
			</a>
			<figcaption>
				<div class="from-top mb-0 label-u"><?php echo __('Read', 'horizons'); ?></div>
			</figcaption>
		</figure>

		<div class="post-body mt-4">
			<h3 class="h4 post-title"><?php the_title(); ?></h3>

			<!-- Добавляем мета-информацию здесь -->
			<?php
			display_post_meta(array(
				'wrapper_class' => 'post-meta d-flex mb-3',
				'comments_class' => 'ms-auto',
				'comments_show_text' => true
			));
			?>

			<div class="body-l-l mb-4 post-excerpt">
				<?php
				$excerpt = get_the_excerpt();
				if (empty($excerpt)) {
					$excerpt = get_the_content();
				}
				// Используем значение по умолчанию если $atts не определен
				$excerpt_length = isset($atts['excerpt_length']) ? intval($atts['excerpt_length']) : 20;
				echo wp_trim_words($excerpt, $excerpt_length, '...');
				?>
			</div>

			<a href="<?php the_permalink(); ?>" class="hover-4 link-body label-s text-charcoal-blue me-4 post-read-more">
				<?php _e('Читать полностью', 'horizons'); ?>
			</a>
		</div>
		<!--/.post-body -->
	</div>
	<!-- /.post-col -->
</article>
<!-- /article -->