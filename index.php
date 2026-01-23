<?php get_header(); ?>
<?php get_pageheader(); ?>
<?php


$post_type = universal_get_post_type();
$post_type_lc = strtolower($post_type);
$sidebar_position = Redux::get_option($opt_name, 'sidebar_position_archive_' . $post_type);

$content_class = ($sidebar_position === 'none') ? 'col-12' : 'col-md-8';
$pageheader_name = Redux::get_option($opt_name, 'global_page_header_model');

$archive_pageheader_id = Redux::get_option($opt_name, 'archive_page_header_select_' . $post_type);
$show_universal_title = ($pageheader_name === '1' && $archive_pageheader_id !== 'disabled');

// Проверяем, какой шаблон используется
$templateloop = Redux::get_option($opt_name, 'archive_template_select_' . $post_type);
$template_file = "templates/archives/{$post_type_lc}/{$templateloop}.php";

// Шаблоны practices_1 и practices_2 управляют своей структурой самостоятельно
$needs_full_wrapper = ($templateloop !== 'practices_1' && $templateloop !== 'practices_2');
$needs_inner_wrapper = $needs_full_wrapper;
?>

<?php if ($needs_full_wrapper) : ?>
<section id="content-wrapper" class="wrapper">
	<div class="container">
		<div class="row gx-lg-8 gx-xl-12">

			<?php get_sidebar('left'); ?>
			<!-- #sidebar-left -->
<?php endif; ?>

			<?php
			if ($needs_inner_wrapper) : ?>
				<div id="loop-wrapper" class="<?php echo $content_class; ?> py-14">
					<div class="blog classic-view row">
			<?php endif; ?>
						
						<?php if ($pageheader_name === '1' && $needs_inner_wrapper) { ?>
							<h1 class="display-4 mb-10"><?php echo universal_title(); ?></h1>
						<?php } ?>
						<!-- #title -->
						<?php
						$has_posts = have_posts();
						if ($has_posts) :

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
						else :
							get_template_part('templates/content/loop', 'none');
						endif;
						// Проверяем, не отключена ли пагинация для текущего шаблона
						global $disable_pagination;
						if ($has_posts && empty($disable_pagination)) {
							codeweber_posts_pagination();
						}
						?>

			<?php if ($needs_inner_wrapper) : ?>
					</div>
				</div> <!-- #loop-wrapper -->
			<?php endif; ?>

<?php if ($needs_full_wrapper) : ?>
			<?php get_sidebar('right'); ?>
			<!-- #sidebar-right -->
		</div>
	</div>
</section> <!-- #content-wrapper -->
<?php endif; ?>

<?php get_footer(); ?>