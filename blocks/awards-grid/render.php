<?php
/**
 * Awards Grid block — server-side render
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$posts_per_page  = isset( $attributes['postsPerPage'] ) ? (int) $attributes['postsPerPage'] : 7;
$order_by        = $attributes['orderBy'] ?? 'date';
$order           = $attributes['order'] ?? 'DESC';
$grid_columns    = $attributes['gridColumns'] ?? '4';
$grid_columns_md = $attributes['gridColumnsMd'] ?? '4';
$grid_columns_sm = $attributes['gridColumnsSm'] ?? '2';
$show_all_link   = $attributes['showAllAwardsLink'] ?? true;
$award_category  = $attributes['awardCategory'] ?? [];
$award_tags      = $attributes['awardTags'] ?? [];
$block_class     = $attributes['blockClass'] ?? '';
$block_id        = $attributes['blockId'] ?? '';
$block_data      = $attributes['blockData'] ?? '';

// Build query
$args = [
	'post_type'      => 'awards',
	'posts_per_page' => $posts_per_page,
	'post_status'    => 'publish',
	'orderby'        => $order_by,
	'order'          => $order,
];

if ( ! empty( $award_category ) || ! empty( $award_tags ) ) {
	$tax_query = [ 'relation' => 'AND' ];

	if ( ! empty( $award_category ) ) {
		$tax_query[] = [
			'taxonomy' => 'award_category',
			'field'    => 'term_id',
			'terms'    => array_map( 'intval', $award_category ),
		];
	}

	if ( ! empty( $award_tags ) ) {
		$tax_query[] = [
			'taxonomy' => 'award_tags',
			'field'    => 'term_id',
			'terms'    => array_map( 'intval', $award_tags ),
		];
	}

	$args['tax_query'] = $tax_query;
}

$query = new WP_Query( $args );

if ( ! $query->have_posts() ) {
	return;
}

// Wrapper attributes — match editor's class structure
$wrapper_attr_arr = [ 'class' => trim( 'horizons-awards-grid-block ' . $block_class ) ];
if ( $block_id ) {
	$wrapper_attr_arr['id'] = $block_id;
}
if ( $block_data ) {
	$wrapper_attr_arr['data-block-data'] = $block_data;
}
$wrapper_attributes = get_block_wrapper_attributes( $wrapper_attr_arr );

// Row classes — mirrors editor: row-cols-2 row-cols-sm-{sm} row-cols-md-{md} row-cols-lg-{lg}
$row_classes = implode( ' ', array_filter( [
	'row',
	'row-cols-2',
	$grid_columns_sm ? 'row-cols-sm-' . $grid_columns_sm : '',
	$grid_columns_md ? 'row-cols-md-' . $grid_columns_md : '',
	$grid_columns    ? 'row-cols-lg-' . $grid_columns    : '',
	'gx-3',
	'gy-3',
] ) );

?>
<div <?php echo $wrapper_attributes; ?>>
	<div class="<?php echo esc_attr( $row_classes ); ?>">

		<?php while ( $query->have_posts() ) : $query->the_post();
			$post_id    = get_the_ID();
			$post_link  = get_permalink();
			$post_title = get_the_title();
			$image_url  = get_the_post_thumbnail_url( $post_id, 'codeweber_awards' );
			if ( ! $image_url ) {
				$image_url = get_the_post_thumbnail_url( $post_id, 'full' );
			}
		?>
			<div class="col">
				<a href="<?php echo esc_url( $post_link ); ?>" class="card hover-scale h-100 align-items-center">
					<div class="card-body align-items-center d-flex p-0">
						<figure class="p-0 mb-0">
							<?php if ( $image_url ) : ?>
								<img decoding="async"
								     src="<?php echo esc_url( $image_url ); ?>"
								     alt="<?php echo esc_attr( $post_title ); ?>"
								     style="width:100%;height:auto">
							<?php else : ?>
								<div style="width:100%;height:200px;background:#f0f0f0;display:flex;align-items:center;justify-content:center">
									<span><?php echo esc_html( $post_title ); ?></span>
								</div>
							<?php endif; ?>
						</figure>
					</div>
				</a>
			</div>

		<?php endwhile; wp_reset_postdata(); ?>

		<?php if ( $show_all_link ) :
			$archive_url = get_post_type_archive_link( 'awards' );
			if ( $archive_url ) : ?>
				<div class="col">
					<a href="<?php echo esc_url( $archive_url ); ?>" class="card h-100 bg-dusty-navy text-decoration-none">
						<div class="card-body align-content-center text-center">
							<span class="hover-4 link-body label-s text-sub-white">
								<?php esc_html_e( 'All Awards', 'horizons' ); ?>
							</span>
						</div>
					</a>
				</div>
			<?php endif;
		endif; ?>

	</div>
</div>
