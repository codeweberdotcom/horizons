<?php
/**
 * Award card template — overlay-5 with meta fields
 *
 * Meta: _award_organization, _award_partners, _award_url
 */

if ( ! isset( $post_data ) || ! $post_data ) {
	return;
}

$display      = cw_get_post_card_display_settings( $display_settings ?? [] );
$template_args = wp_parse_args( $template_args ?? [], [
	'image_size'   => 'cw_landscape_lg',
	'border_radius' => 'rounded',
] );

$post_id = $post_data['id'];

$award_organization = get_post_meta( $post_id, '_award_organization', true );
$award_partners     = get_post_meta( $post_id, '_award_partners', true );

$categories = get_the_terms( $post_id, 'award_category' );
$category_text = '';
if ( $categories && ! is_wp_error( $categories ) ) {
	$category_text = implode( ', ', wp_list_pluck( $categories, 'name' ) );
}

$formatted_date = get_the_date( 'F Y', $post_id );

$partners_text = '';
if ( $award_partners && is_array( $award_partners ) && ! empty( $award_partners ) ) {
	if ( count( $award_partners ) > 1 ) {
		$partners_text = esc_html__( 'Horizons', 'horizons' );
	} else {
		$names = [];
		foreach ( $award_partners as $partner_id ) {
			$partner = get_post( $partner_id );
			if ( $partner ) {
				$names[] = $partner->post_title;
			}
		}
		$partners_text = implode( ', ', $names );
	}
}

$border_radius = $template_args['border_radius'] ?? 'rounded';
?>

<figure class="overlay overlay-5 hover-scale card <?php echo esc_attr( $border_radius ); ?>">
	<a href="<?php echo esc_url( $post_data['link'] ); ?>">
		<?php if ( $post_data['image_url'] ) : ?>
			<img src="<?php echo esc_url( $post_data['image_url'] ); ?>"
			     alt="<?php echo esc_attr( $post_data['image_alt'] ); ?>"
			     class="img-fluid w-100">
		<?php else : ?>
			<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/placeholder.jpg' ); ?>"
			     alt="<?php echo esc_attr( $post_data['title'] ); ?>"
			     class="img-fluid w-100">
		<?php endif; ?>
	</a>

	<figcaption>
		<?php if ( $display['show_title'] && $post_data['title'] ) : ?>
			<<?php echo esc_attr( $display['title_tag'] ); ?> class="from-left body-l-r mb-3 <?php echo esc_attr( $display['title_class'] ); ?>">
				<?php echo esc_html( $post_data['title'] ); ?>
			</<?php echo esc_attr( $display['title_tag'] ); ?>>
		<?php endif; ?>

		<div class="award-desc-group d-flex flex-wrap">
			<?php if ( $display['show_category'] && $category_text ) : ?>
				<span class="from-left mb-1 me-3 text-square-before label-u"><?php echo esc_html( $category_text ); ?></span>
			<?php endif; ?>

			<?php if ( $award_organization ) : ?>
				<span class="from-left mb-1 me-3 text-square-before label-u"><?php echo esc_html( $award_organization ); ?></span>
			<?php endif; ?>

			<div class="d-flex flex-wrap">
				<?php if ( $display['show_date'] && $formatted_date ) : ?>
					<span class="from-left mb-1 me-3 text-square-before label-u"><?php echo esc_html( $formatted_date ); ?></span>
				<?php endif; ?>

				<?php if ( $partners_text ) : ?>
					<span class="from-left mb-1 me-3 text-square-before label-u"><?php echo esc_html( $partners_text ); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</figcaption>
</figure>
