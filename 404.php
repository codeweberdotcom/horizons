<?php get_header(); ?>

<section class="wrapper bg-light">
	<div class="container pt-12 pt-md-14 pb-14 pb-md-16">
		<div class="row">

			<div class="col-lg-8 col-xl-7 col-xxl-6 py-18 py-md-20 mx-auto text-center">
				<div class="display-1 text-center">404</div>

				<div class="label-u mb-5"><?php echo esc_html__('Oops! Page Not Found.', 'codeweber'); ?></div>

				<a href="<?php echo esc_url(home_url()); ?>" class="btn btn-dusty-navy">
					<?php echo esc_html__('Go to Homepage', 'codeweber'); ?>
				</a>
			</div>
			<!-- /column -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container -->
</section>

<?php
get_footer();
