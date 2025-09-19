<?php get_header();

while (have_posts()) :
	the_post();
	get_pageheader();

	$post_type = get_post_type();
	$post_type_lc = strtolower($post_type);
	$sidebar_position = get_sidebar_position($opt_name);

	// Определяем класс контента
	$content_class = ($sidebar_position === 'none') ? 'col-12' : 'col-md-9';
	$pageheader_name = Redux::get_option($opt_name, 'global_page_header_model');

	// Проверяем, не отключен ли заголовок для этого типа записи
	$single_pageheader_id = Redux::get_option($opt_name, 'single_page_header_select_' . $post_type);
	$show_universal_title = ($single_pageheader_id !== 'disabled');
?>

	<section class="wrapper">
		<div class="container">
			<?php do_action('before_single_content', $post_type); ?>
			<div class="row gx-lg-8 gx-xl-12">
				<?php get_sidebar('left'); ?>
				<!-- #sidebar-left -->

				<div id="article-wrapper" class="<?php echo $content_class; ?> py-12">
					<?php if ($pageheader_name === '1' && $show_universal_title) { ?>
						<h1 class="display-4 mb-10"><?php echo universal_title(); ?></h1>
					<?php } ?>
					<!-- #title -->

					<?php
					$templatesingle = Redux::get_option($opt_name, 'single_template_select_' . $post_type);
					$template_file = "templates/singles/{$post_type_lc}/{$templatesingle}.php";

					// Проверяем, не отключен ли вообще вывод контента
					if ($single_pageheader_id !== 'disabled') {
						if (!empty($templatesingle) && locate_template($template_file)) {
							get_template_part("templates/content/single/{$post_type_lc}/{$templatesingle}");
						} else {
							if (locate_template("templates/content/single-{$post_type_lc}.php")) {
								get_template_part("templates/content/single", $post_type_lc);
							} else {
								get_template_part("templates/content/single", '');
							}
						}
					}
					?>
					<?php if ($single_pageheader_id !== 'disabled') : ?>
						<nav class="nav mt-8">
							<?php
							// Предыдущий пост
							$previous_post = get_adjacent_post(false, '', true);
							if ($previous_post) {
								printf(
									'<a href="%s" class="hover-5 left label-u text-charcoal-blue me-4 mb-5">%s</a>',
									get_permalink($previous_post->ID),
									get_the_title($previous_post->ID)
								);
							}

							// Следующий пост
							$next_post = get_adjacent_post(false, '', false);
							if ($next_post) {
								printf(
									'<a href="%s" class="hover-5 right label-u text-charcoal-blue ms-auto mb-5">%s</a>',
									get_permalink($next_post->ID),
									get_the_title($next_post->ID)
								);
							}
							?>
						</nav>
					<?php endif; ?>
				</div> <!-- #article-wrapper -->

				<?php get_sidebar('right'); ?>
				<!-- #sidebar-right -->
				<?php do_action('after_single_content', $post_type); ?>
			</div>
		</div>
	</section> <!-- #content-wrapper -->

<?php
endwhile;
get_footer();
