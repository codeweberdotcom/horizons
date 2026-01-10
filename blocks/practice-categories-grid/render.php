<?php
if (!defined('ABSPATH')) exit;
$grid_type = $attributes['gridType'] ?? 'classic';
$terms_per_page = isset($attributes['termsPerPage']) ? (int) $attributes['termsPerPage'] : -1;
$order_by = $attributes['orderBy'] ?? 'meta_value_num';
$order = $attributes['order'] ?? 'ASC';
$use_alt_title = $attributes['useAltTitle'] ?? true;
$show_all_practice_link = $attributes['showAllPracticeLink'] ?? true;
$block_class = $attributes['blockClass'] ?? '';
$args = ['taxonomy' => 'practice_category', 'hide_empty' => false, 'orderby' => $order_by, 'order' => $order];
if ($order_by === 'meta_value_num' || $order_by === 'meta_value') $args['meta_key'] = 'practice_category_order';
if ($terms_per_page != -1) $args['number'] = $terms_per_page;
$terms = get_terms($args);
if (is_wp_error($terms) || empty($terms)) { echo '<p>' . __('No categories found', 'horizons') . '</p>'; return; }
if ($order_by === 'meta_value_num' || $order_by === 'meta_value') {
	usort($terms, function ($a, $b) use ($order) {
		$order_a = intval(get_term_meta($a->term_id, 'practice_category_order', true) ?: 0);
		$order_b = intval(get_term_meta($b->term_id, 'practice_category_order', true) ?: 0);
		return $order === 'ASC' ? $order_a <=> $order_b : $order_b <=> $order_a;
	});
}
$grid_classes = 'row g-3';
$col_classes = 'col-4 col-md-4';
$wrapper_attrs = get_block_wrapper_attributes(['class' => 'horizons-practice-categories-grid-block ' . $block_class]);
?>
<div <?php echo $wrapper_attrs; ?>>
	<div class="<?php echo esc_attr($grid_classes); ?>">
		<?php foreach ($terms as $term) :
			$color = get_term_meta($term->term_id, 'practice_category_color', true);
			$color_class = $color ? 'bg-' . $color : '';
			$term_link = get_term_link($term);
			$display_title = $term->name;
			if ($use_alt_title && ($alt_title = get_term_meta($term->term_id, 'practice_category_alt_title', true))) $display_title = $alt_title;
			if (is_wp_error($term_link)) $term_link = '#';
		?>
			<div class="<?php echo esc_attr($col_classes); ?>">
				<a href="<?php echo esc_url($term_link); ?>" class="card h-100 practice-card" data-cue="slideInDown">
					<div class="brand-square-xs <?php echo esc_attr($color_class); ?> opacity-0 position-absolute top-0 start-0"></div>
					<div class="card-body d-flex flex-column justify-content-between">
						<div class="pe-none mb-5"><div class="practice-card-hover brand-square-md <?php echo esc_attr($color_class); ?>"></div></div>
						<h3 class="h4"><?php echo wp_kses_post($display_title); ?></h3>
						<div class="icontext right label-s text-white opacity-0 position-absolute" style="transition: all .8s ease; top: 45px; left: 45px;"><?php echo __('More details', 'horizons'); ?></div>
					</div>
				</a>
			</div>
		<?php endforeach; ?>
		<?php if ($show_all_practice_link) : ?>
			<div class="<?php echo esc_attr($col_classes); ?>">
				<a href="<?php echo esc_url(get_post_type_archive_link('practices')); ?>" class="card practice-card bg-dusty-navy h-100" data-cue="slideInDown">
					<div class="card-body align-content-center text-center">
						<span class="hover-4 link-body label-s text-sub-white"><?php echo __('All Practice', 'horizons'); ?></span>
					</div>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>
