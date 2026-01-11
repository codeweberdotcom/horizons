<?php
if (!defined('ABSPATH')) exit;

// Получаем атрибуты
$grid_type = $attributes['gridType'] ?? 'classic';
$terms_per_page = isset($attributes['termsPerPage']) ? (int) $attributes['termsPerPage'] : -1;
$order_by = $attributes['orderBy'] ?? 'meta_value_num';
$order = $attributes['order'] ?? 'ASC';
$use_alt_title = $attributes['useAltTitle'] ?? true;
$show_all_practice_link = $attributes['showAllPracticeLink'] ?? true;
$block_class = $attributes['blockClass'] ?? '';

// Функция для получения row-cols классов
if (!function_exists('get_practice_categories_row_cols_classes')) {
	function get_practice_categories_row_cols_classes($attributes, $prefix = 'grid', $fallback_count = null) {
		$row_cols = $attributes[$prefix . 'RowCols'] ?? '';
		$row_cols_sm = $attributes[$prefix . 'RowColsSm'] ?? '';
		$row_cols_md = $attributes[$prefix . 'RowColsMd'] ?? '';
		$row_cols_lg = $attributes[$prefix . 'RowColsLg'] ?? '';
		$row_cols_xl = $attributes[$prefix . 'RowColsXl'] ?? '';
		$row_cols_xxl = $attributes[$prefix . 'RowColsXxl'] ?? '';
		
		$classes = [];
		if ($row_cols || $fallback_count) {
			$classes[] = 'row-cols-' . ($row_cols ?: $fallback_count);
		}
		if ($row_cols_sm) $classes[] = 'row-cols-sm-' . $row_cols_sm;
		if ($row_cols_md) $classes[] = 'row-cols-md-' . $row_cols_md;
		if ($row_cols_lg) $classes[] = 'row-cols-lg-' . $row_cols_lg;
		if ($row_cols_xl) $classes[] = 'row-cols-xl-' . $row_cols_xl;
		if ($row_cols_xxl) $classes[] = 'row-cols-xxl-' . $row_cols_xxl;
		
		return array_filter($classes);
	}
}

// Функция для получения gap классов
if (!function_exists('get_practice_categories_gap_classes')) {
	function get_practice_categories_gap_classes($attributes, $prefix = 'grid') {
		$gap_type = $attributes[$prefix . 'GapType'] ?? 'general';
		$gap = $attributes[$prefix . 'Gap'] ?? '';
		$gap_md = $attributes[$prefix . 'GapMd'] ?? '';
		$gap_x = $attributes[$prefix . 'GapX'] ?? '';
		$gap_x_md = $attributes[$prefix . 'GapXMd'] ?? '';
		$gap_y = $attributes[$prefix . 'GapY'] ?? '';
		$gap_y_md = $attributes[$prefix . 'GapYMd'] ?? '';
		
		$classes = [];
		
		if ($gap_type === 'general' || $gap_type === 'x' || $gap_type === 'y') {
			if ($gap) $classes[] = 'g-' . $gap;
			if ($gap_md) $classes[] = 'g-md-' . $gap_md;
		}
		
		if ($gap_type === 'x' || $gap_type === 'general') {
			if ($gap_x) $classes[] = 'gx-' . $gap_x;
			if ($gap_x_md) $classes[] = 'gx-md-' . $gap_x_md;
		}
		
		if ($gap_type === 'y' || $gap_type === 'general') {
			if ($gap_y) $classes[] = 'gy-' . $gap_y;
			if ($gap_y_md) $classes[] = 'gy-md-' . $gap_y_md;
		}
		
		return array_filter($classes);
	}
}

// Функция для получения классов контейнера
if (!function_exists('get_practice_categories_container_classes')) {
	function get_practice_categories_container_classes($attributes, $grid_type) {
	$classes = ['row'];
	
	if ($grid_type === 'columns-grid') {
		$row_cols_classes = get_practice_categories_row_cols_classes($attributes, 'grid', $attributes['gridColumns'] ?? null);
		$gap_classes = get_practice_categories_gap_classes($attributes, 'grid');
		
		$gap_classes_str = implode(' ', $gap_classes);
		
		// Fallback на старые атрибуты gridGapX и gridGapY
		if (!$gap_classes_str) {
			$grid_gap_x = $attributes['gridGapX'] ?? '';
			$grid_gap_y = $attributes['gridGapY'] ?? '';
			if ($grid_gap_x || $grid_gap_y) {
				$old_gap_classes = [];
				if ($grid_gap_y) $old_gap_classes[] = 'gy-' . $grid_gap_y;
				if ($grid_gap_x) $old_gap_classes[] = 'gx-' . $grid_gap_x;
				$gap_classes = $old_gap_classes;
			}
		}
		
		$classes = array_merge($classes, $gap_classes, $row_cols_classes);
	} else {
		$gap_classes = get_practice_categories_gap_classes($attributes, 'grid');
		$gap_classes_str = implode(' ', $gap_classes);
		
		// Fallback на старые атрибуты gridGapX и gridGapY
		if (!$gap_classes_str) {
			$grid_gap_x = $attributes['gridGapX'] ?? '';
			$grid_gap_y = $attributes['gridGapY'] ?? '';
			if ($grid_gap_x || $grid_gap_y) {
				$old_gap_classes = [];
				if ($grid_gap_y) $old_gap_classes[] = 'gy-' . $grid_gap_y;
				if ($grid_gap_x) $old_gap_classes[] = 'gx-' . $grid_gap_x;
				$gap_classes = $old_gap_classes;
			}
		}
		
		$classes = array_merge($classes, $gap_classes);
	}
	
		return trim(implode(' ', array_filter($classes)));
	}
}

// Функция для получения col классов
if (!function_exists('get_practice_categories_col_classes')) {
	function get_practice_categories_col_classes($attributes, $grid_type) {
	if ($grid_type !== 'classic') {
		return '';
	}
	
	$col_classes = [];
	$cols_default = $attributes['gridColumns'] ?? '';
	$cols_xs = $attributes['gridColumnsXs'] ?? '';
	$cols_sm = $attributes['gridColumnsSm'] ?? '';
	$cols_md = $attributes['gridColumnsMd'] ?? '';
	$cols_lg = $attributes['gridColumnsLg'] ?? '';
	$cols_xl = $attributes['gridColumnsXl'] ?? '';
	$cols_xxl = $attributes['gridColumnsXxl'] ?? '';
	
	if ($cols_default) $col_classes[] = 'col-' . (12 / intval($cols_default));
	if ($cols_xs) $col_classes[] = 'col-' . (12 / intval($cols_xs));
	if ($cols_sm) $col_classes[] = 'col-sm-' . (12 / intval($cols_sm));
	if ($cols_md) $col_classes[] = 'col-md-' . (12 / intval($cols_md));
	if ($cols_lg) $col_classes[] = 'col-lg-' . (12 / intval($cols_lg));
	if ($cols_xl) $col_classes[] = 'col-xl-' . (12 / intval($cols_xl));
		if ($cols_xxl) $col_classes[] = 'col-xxl-' . (12 / intval($cols_xxl));
		
		return implode(' ', array_filter($col_classes));
	}
}

// Получаем термины
$args = ['taxonomy' => 'practice_category', 'hide_empty' => false, 'orderby' => $order_by, 'order' => $order];
if ($order_by === 'meta_value_num' || $order_by === 'meta_value') {
	$args['meta_key'] = 'practice_category_order';
}
if ($terms_per_page != -1) {
	$args['number'] = $terms_per_page;
}

$terms = get_terms($args);

if (is_wp_error($terms) || empty($terms)) {
	echo '<p>' . __('No categories found', 'horizons') . '</p>';
	return;
}

// Сортировка по мета-полю
if ($order_by === 'meta_value_num' || $order_by === 'meta_value') {
	usort($terms, function ($a, $b) use ($order) {
		$order_a = intval(get_term_meta($a->term_id, 'practice_category_order', true) ?: 0);
		$order_b = intval(get_term_meta($b->term_id, 'practice_category_order', true) ?: 0);
		return $order === 'ASC' ? $order_a <=> $order_b : $order_b <=> $order_a;
	});
}

// Вычисляем классы
$grid_classes = get_practice_categories_container_classes($attributes, $grid_type);
$col_classes = get_practice_categories_col_classes($attributes, $grid_type);

$wrapper_attrs = get_block_wrapper_attributes(['class' => 'horizons-practice-categories-grid-block ' . $block_class]);
?>
<div <?php echo $wrapper_attrs; ?>>
	<div class="<?php echo esc_attr($grid_classes); ?>">
		<?php foreach ($terms as $term) :
			$color = get_term_meta($term->term_id, 'practice_category_color', true);
			$color_class = $color ? 'bg-' . $color : '';
			$term_link = get_term_link($term);
			$display_title = $term->name;
			if ($use_alt_title && ($alt_title = get_term_meta($term->term_id, 'practice_category_alt_title', true))) {
				$display_title = $alt_title;
			}
			if (is_wp_error($term_link)) {
				$term_link = '#';
			}
		?>
			<div class="<?php echo esc_attr($grid_type === 'classic' ? $col_classes : ($grid_type === 'columns-grid' ? 'col' : '')); ?>">
				<a href="<?php echo esc_url($term_link); ?>" class="card h-100 practice-card" data-cue="slideInDown">
					<div class="brand-square-xs <?php echo esc_attr($color_class); ?> opacity-0 position-absolute top-0 start-0"></div>
					<div class="card-body d-flex flex-column justify-content-between">
						<div class="pe-none mb-5">
							<div class="practice-card-hover brand-square-md <?php echo esc_attr($color_class); ?>"></div>
						</div>
						<h3 class="h4"><?php echo wp_kses_post($display_title); ?></h3>
						<div class="icontext right label-s text-white opacity-0 position-absolute" style="transition: all .8s ease; top: 45px; left: 45px;">
							<?php echo __('More details', 'horizons'); ?>
						</div>
					</div>
				</a>
			</div>
		<?php endforeach; ?>
		
		<?php if ($show_all_practice_link) : ?>
			<div class="<?php echo esc_attr($grid_type === 'classic' ? $col_classes : ($grid_type === 'columns-grid' ? 'col' : '')); ?>">
				<a href="<?php echo esc_url(get_post_type_archive_link('practices')); ?>" class="card practice-card bg-dusty-navy h-100" data-cue="slideInDown">
					<div class="card-body align-content-center text-center">
						<span class="hover-4 link-body label-s text-sub-white"><?php echo __('All Practice', 'horizons'); ?></span>
					</div>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>
