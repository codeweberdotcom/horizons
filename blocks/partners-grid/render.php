<?php
if (!defined('ABSPATH')) exit;

// Получаем атрибуты
$posts_per_page = isset($attributes['postsPerPage']) ? (int) $attributes['postsPerPage'] : 3;
$orderby = $attributes['orderBy'] ?? 'date';
$order = $attributes['order'] ?? 'ASC';
$grid_columns = $attributes['gridColumns'] ?? '3';
$grid_columns_md = $attributes['gridColumnsMd'] ?? '3';
$grid_gap = $attributes['gridGap'] ?? '3';
$block_class = $attributes['blockClass'] ?? '';

// Аргументы для WP_Query
$args = array(
    'post_type' => 'partners',
    'posts_per_page' => $posts_per_page,
    'orderby' => $orderby,
    'order' => $order
);

$query = new WP_Query($args);
$wrapper_attrs = get_block_wrapper_attributes(['class' => 'horizons-partners-grid-block ' . $block_class]);

if ($query->have_posts()) :
    // Формируем классы для сетки
    $col_class = 'col-md-' . (12 / intval($grid_columns));
    $col_class_md = 'col-md-' . (12 / intval($grid_columns_md));
    $gap_class = 'g-' . $grid_gap . ' g-md-' . $grid_gap;
    ?>
    <div <?php echo $wrapper_attrs; ?>>
        <div class="row <?php echo esc_attr($gap_class); ?>">
            <?php while ($query->have_posts()) : $query->the_post();
                $post_id = get_the_ID();
                
                // Получаем метаданные
                $position = get_post_meta($post_id, '_partners_position', true);
                $name = get_post_meta($post_id, '_partners_name', true);
                $surname = get_post_meta($post_id, '_partners_surname', true);
                $regions = get_post_meta($post_id, '_partners_regions', true);
                $thumbnail = get_the_post_thumbnail_url($post_id, 'full');
                
                // Формируем полное имя
                $full_name = trim($name . ' <span class="text-uppercase">' . $surname . '</span>');
                ?>
                <div class="<?php echo esc_attr($col_class . ' ' . $col_class_md); ?>">
                    <a href="<?php echo esc_url(get_permalink()); ?>" class="swiper-slide" data-cue="slideInDown">
                        <figure class="lift">
                            <?php if ($thumbnail) : ?>
                                <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($full_name); ?>" />
                            <?php else : ?>
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/placeholder.jpg'); ?>" alt="<?php echo esc_attr($full_name); ?>" />
                            <?php endif; ?>
                            
                            <div class="caption-wrapper p-7">
                                <div class="caption bg-matte-color mt-auto label-u text-neutral-50 px-4 py-2"><?php echo esc_html($position); ?></div>
                            </div>
                        </figure>
                        <div class="team-item-content text-dark mt-4">
                            <h3 class="h4"><?php echo $full_name; ?></h3>
                            <?php if ($regions) : ?>
                                <div class="label-u"><?php echo esc_html($regions); ?></div>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php
    wp_reset_postdata();
else :
    ?>
    <div <?php echo $wrapper_attrs; ?>>
        <p><?php _e('No partners found', 'horizons'); ?></p>
    </div>
    <?php
endif;
?>
