<?php
get_header();

/* Header Banner */
get_template_part( 'layout/header/header-banner' );

?>
<div class="content site-pages">

	<div class="container">
		<div class="row">

			<div class="col-md-12">
				<!--Start 404 Error-->
				<div class="error-404">
					<div class="container">

						<div class="row">
							<div class="col-md-12">
								<img src="<?php echo get_template_directory_uri() ?>/images/404-error-no-text.png" alt="error404">
								<p style="font-size: 17px; text-align: center; margin: -30px -75px 0px 0px; font-family: 'Bookman Old Style';"><?php esc_html_e( "Sorry, we couldn't find the page you're looking for.", 'pearl-medicalguide' ); ?></p>
								<div class="clear"></div>
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go back to home page', 'pearl-medicalguide' ); ?></a>
							</div>
						</div>

					</div>
				</div>
				<!--End 404 Error-->
			</div>

		</div>
	</div>
</div>

<?php get_footer(); ?>
