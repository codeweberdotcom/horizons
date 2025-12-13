<?php get_header();

while (have_posts()) :
	the_post();
	get_pageheader();

	$post_type = universal_get_post_type();
	$post_type_lc = strtolower($post_type);
	$sidebar_position = get_sidebar_position($opt_name);

	// Определяем класс контента
	$content_class = ($sidebar_position === 'none') ? 'col-12' : 'col-md-8';
	$pageheader_name = Redux::get_option($opt_name, 'global_page_header_model');

	// Проверяем, не отключен ли заголовок для этого типа записи
	$single_pageheader_id = Redux::get_option($opt_name, 'single_page_header_select_' . $post_type);
	$show_universal_title = ($pageheader_name === '1' && $single_pageheader_id !== 'disabled');
?>

	<section class="wrapper">
		<div class="container">
			<?php do_action('before_single_content', $post_type); ?>
			<div class="row gx-lg-8 gx-xl-12">
				<?php get_sidebar('left'); ?>
				<!-- #sidebar-left -->

				<div id="article-wrapper" class="<?php echo $content_class; ?> py-14">
					<?php if ($show_universal_title) { ?>
						<h1 class="display-4 mb-10"><?php echo universal_title(); ?></h1>
					<?php } ?>
					<!-- #title -->

			<?php
			$template_loaded = false;

			// ПРИОРИТЕТ: Сначала проверяем файл в дочерней теме {post_type}_1.php
			// Если файл найден - подключаем сразу и прекращаем все проверки
			if (is_child_theme()) {
				$child_template_file = get_stylesheet_directory() . "/templates/singles/{$post_type_lc}/{$post_type_lc}_1.php";
				if (file_exists($child_template_file)) {
					get_template_part("templates/singles/{$post_type_lc}/{$post_type_lc}_1");
					$template_loaded = true;
				}
			}

			// Если файла нет в дочерней теме - продолжаем обычную логику
			if (!$template_loaded) {
				$templatesingle = Redux::get_option($opt_name, 'single_template_select_' . $post_type);
				$template_file = "templates/singles/{$post_type_lc}/{$templatesingle}.php";
				
				// 1. Пытаемся загрузить выбранный шаблон из Redux
				if (!empty($templatesingle) && locate_template($template_file)) {
					get_template_part("templates/singles/{$post_type_lc}/{$templatesingle}");
					$template_loaded = true;
				}
				
				// 2. Если шаблон не найден, пробуем default.php для этого типа записи
				if (!$template_loaded) {
					$default_template = "templates/singles/{$post_type_lc}/default.php";
					if (locate_template($default_template)) {
						get_template_part("templates/singles/{$post_type_lc}/default");
						$template_loaded = true;
					}
				}
				
				// 3. Пробуем файл из дочерней темы templates/content/single-{post_type}.php
				if (!$template_loaded) {
					if (locate_template("templates/content/single-{$post_type_lc}.php")) {
						get_template_part("templates/content/single", $post_type_lc);
						$template_loaded = true;
					}
				}
				
				// 4. Последний fallback - общий шаблон для всех типов записей
				if (!$template_loaded) {
					get_template_part("templates/content/single", '');
				}
			}
			?>

					<!-- УБИРАЕМ условие - навигация выводится ВСЕГДА -->
					<nav class="nav mt-8">
						<?php
						// Предыдущий пост
						$previous_post = get_adjacent_post(false, '', true);
						if ($previous_post) {
							printf(
								'<a href="%s" class="hover-5 left label-u text-charcoal-blue me-4 mb-5">%s</a>',
								get_permalink($previous_post->ID),
								__('Previous', 'horizons')
							);
						}

						// Следующий пост
						$next_post = get_adjacent_post(false, '', false);
						if ($next_post) {
							printf(
								'<a href="%s" class="hover-5 right label-u text-charcoal-blue ms-auto mb-5">%s</a>',
								get_permalink($next_post->ID),
								__('Next', 'horizons')
							);
						}
						?>
					</nav>
				</div> <!-- #article-wrapper -->

				<?php get_sidebar('right'); ?>
				<!-- #sidebar-right -->
				<?php do_action('after_single_content', $post_type); ?>
			</div>
		</div>
	</section> <!-- #content-wrapper -->
	<?php do_action('after_single_post', $post_type); ?>
<?php
endwhile;
get_footer();
