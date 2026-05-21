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

// Load cw_render_post_card if not yet available
if ( ! function_exists( 'cw_render_post_card' ) ) {
	$tpl_path = get_template_directory() . '/functions/post-card-templates.php';
	if ( file_exists( $tpl_path ) ) {
		require_once $tpl_path;
	}
}

// Col classes from block attributes
$col_parts = array_filter( [
	$grid_columns    ? 'col-' . $grid_columns       : '',
	$grid_columns_sm ? 'col-sm-' . $grid_columns_sm : '',
	$grid_columns_md ? 'col-md-' . $grid_columns_md : '',
] );
$col_classes = implode( ' ', $col_parts );

$display_settings = [
	'show_title'     => true,
	'show_date'      => true,
	'show_category'  => true,
	'show_comments'  => false,
	'title_length'   => 0,
	'excerpt_length' => 0,
	'title_tag'      => 'h2',
	'title_class'    => '',
];

$template_args = [
	'image_size' => 'codeweber_awards',
];

// Wrapper attributes
$wrapper_attr_arr = [ 'class' => trim( 'cwgb-awards-grid-block ' . $block_class ) ];
if ( $block_id ) {
	$wrapper_attr_arr['id'] = $block_id;
}
if ( $block_data ) {
	$wrapper_attr_arr['data-block-data'] = $block_data;
}
$wrapper_attributes = get_block_wrapper_attributes( $wrapper_attr_arr );

?>
<div <?php echo $wrapper_attributes; ?>>
	<div class="row g-3 isotope">
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<?php
			$card_html = function_exists( 'cw_render_post_card' )
				? cw_render_post_card( get_post(), 'card', $display_settings, $template_args )
				: '';

			if ( empty( $card_html ) ) {
				continue;
			}
			?>
			<div class="project item <?php echo esc_attr( $col_classes ); ?>">
				<?php echo $card_html; ?>
			</div>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>

	<?php if ( $show_all_link ) :
		$archive_url = get_post_type_archive_link( 'awards' );
		if ( $archive_url ) : ?>
			<div class="text-center mt-6">
				<a href="<?php echo esc_url( $archive_url ); ?>" class="btn btn-primary rounded-pill">
					<?php esc_html_e( 'All Awards', 'horizons' ); ?>
				</a>
			</div>
		<?php endif;
	endif; ?>
</div>
