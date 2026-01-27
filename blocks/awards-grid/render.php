<?php
if (!defined('ABSPATH')) exit;

// Получаем атрибуты
$posts_per_page = isset($attributes['postsPerPage']) ? (int) $attributes['postsPerPage'] : 7;
$orderby = $attributes['orderBy'] ?? 'date';
$order = $attributes['order'] ?? 'DESC';
$grid_columns = $attributes['gridColumns'] ?? '4';
$grid_columns_md = $attributes['gridColumnsMd'] ?? '4';
$grid_columns_sm = $attributes['gridColumnsSm'] ?? '2';
$show_all_awards_link = isset($attributes['showAllAwardsLink']) ? $attributes['showAllAwardsLink'] : true;
$award_category = isset($attributes['awardCategory']) && is_array($attributes['awardCategory']) ? array_map('intval', array_filter($attributes['awardCategory'])) : array();
$award_tags = isset($attributes['awardTags']) && is_array($attributes['awardTags']) ? array_map('intval', array_filter($attributes['awardTags'])) : array();
$block_class = $attributes['blockClass'] ?? '';

// Аргументы для WP_Query
$args = array(
    'post_type' => 'awards',
    'posts_per_page' => $posts_per_page,
    'orderby' => $orderby,
    'order' => $order,
    'post_status' => 'publish'
);

if (!empty($award_category) || !empty($award_tags)) {
    $tax_queries = array();
    if (!empty($award_category)) {
        $tax_queries[] = array(
            'taxonomy' => 'award_category',
            'field'    => 'term_id',
            'terms'    => $award_category,
            'operator' => 'IN',
        );
    }
    if (!empty($award_tags)) {
        $tax_queries[] = array(
            'taxonomy' => 'award_tags',
            'field'    => 'term_id',
            'terms'    => $award_tags,
            'operator' => 'IN',
        );
    }
    if (count($tax_queries) > 1) {
        $args['tax_query'] = array('relation' => 'AND', $tax_queries);
    } else {
        $args['tax_query'] = $tax_queries;
    }
}

$query = new WP_Query($args);
$wrapper_attrs = get_block_wrapper_attributes(['class' => 'horizons-awards-grid-block ' . $block_class]);

if ($query->have_posts()) :
    // Формируем классы для сетки (используем row-cols как в шорткоде)
    $column_classes = sprintf(
        'row row-cols-2 row-cols-sm-%d row-cols-md-%d row-cols-lg-%d gx-3 gy-3',
        intval($grid_columns_sm),
        intval($grid_columns_md),
        intval($grid_columns)
    );
    ?>
    <div <?php echo $wrapper_attrs; ?>>
        <div class="<?php echo esc_attr($column_classes); ?>">
            <?php while ($query->have_posts()) : $query->the_post();
                $image_id = get_post_thumbnail_id();
                $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
                $permalink = get_permalink();
            ?>
                <div class="col">
                    <a href="<?php echo esc_url($permalink); ?>" class="card hover-scale h-100 align-items-center">
                        <div class="card-body align-items-center d-flex p-0">
                            <figure class="p-0 mb-0">
                                <?php if ($image_url) : ?>
                                    <img decoding="async" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                <?php else : ?>
                                    <div style="width: 100%; height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                        <span><?php _e('No image', 'horizons'); ?></span>
                                    </div>
                                <?php endif; ?>
                            </figure>
                        </div>
                        <!--/.card-body -->
                    </a>
                    <!--/.card -->
                </div>
            <?php endwhile; ?>

            <?php if ($show_all_awards_link) : ?>
                <!-- Кнопка "All Awards" отображается ВСЕГДА -->
                <div class="col">
                    <a href="<?php echo esc_url(get_post_type_archive_link('awards')); ?>" class="card h-100 bg-dusty-navy">
                        <div class="card-body align-content-center text-center">
                            <span class="hover-4 link-body label-s text-sub-white"><?php echo __('All Awards', 'horizons'); ?></span>
                        </div>
                        <!--/.card-body -->
                    </a>
                    <!--/.card -->
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    wp_reset_postdata();
else :
    ?>
    <div <?php echo $wrapper_attrs; ?>>
        <p><?php _e('No awards found', 'horizons'); ?></p>
    </div>
    <?php
endif;
?>
