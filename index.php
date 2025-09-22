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

<section id="content-wrapper" class="wrapper">
	<div class="container">
		<div class="row gx-lg-8 gx-xl-12">

			<?php get_sidebar('left'); ?>
			<!-- #sidebar-left -->

			<div id="loop-wrapper" class="<?php echo $content_class; ?> py-12">
				<div class="blog classic-view row">
					<?php if ($pageheader_name === '1') { ?>
						<h1 class="display-4 mb-10"><?php echo universal_title(); ?></h1>
					<?php } ?>
					<!-- #title -->
					<?php
					$templateloop = Redux::get_option($opt_name, 'archive_template_select_' . $post_type);
					$template_file = "templates/archives/{$post_type_lc}/{$templateloop}.php";

					if (have_posts()) :

						while (have_posts()) :
							the_post();

							if (!empty($templateloop) && locate_template($template_file)) {
								get_template_part("templates/archives/{$post_type_lc}/{$templateloop}");
							} else {
								if (locate_template("templates/content/loop-{$post_type_lc}.php")) {
									get_template_part("templates/content/loop", $post_type_lc);
								} else {
									get_template_part("templates/content/loop", '');
								}
							}
						endwhile;
						codeweber_posts_pagination();
					else :
						get_template_part('templates/content/loop', 'none');
					endif;
					?>

				</div>
			</div> <!-- #loop-wrapper -->

			<?php get_sidebar('right'); ?>
			<!-- #sidebar-right -->
		</div>
	</div>
</section> <!-- #content-wrapper -->

<?php get_footer(); ?>