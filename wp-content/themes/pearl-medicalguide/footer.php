<?php
$footer_style = get_option( 'pearl_footer_style' );
if ( $footer_style == 'light' ) {
	$footer_class = 'footer-light';
} else {
	$footer_class = 'footer';
}
?>
<footer class="<?php echo sanitize_html_class( $footer_class ); ?>" id="footer">
	<div class="container">

		<?php
		$footer_phone['title']  = get_option( 'pearl_footer_phone_title', esc_html__( 'Footer Phone Title', 'pearl-medicalguide' ) );
		$footer_phone['number'] = get_option( 'pearl_footer_phone_number', esc_html__( '1-300-400-8211', 'pearl-medicalguide' ) );

		if ( array_filter( $footer_phone ) ) {
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="emergency">
						<i class="icon-phone5"></i>
						<?php
						if ( ! empty( $footer_phone['title'] ) ) {
							echo '<span class="text">' . esc_html( $footer_phone['title'] ) . '</span>';
						}
						if ( ! empty( $footer_phone['number'] ) ) {
							echo '<span class="number">' . esc_html( $footer_phone['number'] ) . '</span>';
						}
						?>
						<img src="<?php echo get_template_directory_uri(); ?>/images/emergency-divider.png" alt="">
					</div>
				</div>
			</div>
			<?php
		}
		?>

		<div class="main-footer">
			<div class="row">
				<?php

				$footer_widget_layout = get_option( 'pearl_footer_widget_layout' );

				$column_2     = $column_3 = $column_4 = false;
				$column_class = 'col-md-3';

				if ( 'one-column' == $footer_widget_layout ) {
					$column_class = 'col-md-12';
				} elseif ( 'two-column' == $footer_widget_layout ) {
					$column_2     = true;
					$column_class = 'col-md-6';
				} elseif ( 'three-column' == $footer_widget_layout ) {
					$column_2     = true;
					$column_3     = true;
					$column_class = 'col-md-4';
				} else {
					$column_2 = true;
					$column_3 = true;
					$column_4 = true;
				}

				if ( is_active_sidebar( 'footer-first-column' ) ) : ?>
					<div class="<?php echo $column_class; ?>">
						<?php dynamic_sidebar( 'footer-first-column' ); ?>
					</div>
				<?php
				endif;

				if ( is_active_sidebar( 'footer-second-column' ) && $column_2 ) : ?>
					<div class="<?php echo $column_class; ?>">
						<?php dynamic_sidebar( 'footer-second-column' ); ?>
					</div>
				<?php
				endif;

				if ( is_active_sidebar( 'footer-third-column' ) && $column_3 ) : ?>
					<div class="<?php echo $column_class; ?>">
						<?php dynamic_sidebar( 'footer-third-column' ); ?>
					</div>
				<?php
				endif;

				if ( is_active_sidebar( 'footer-fourth-column' ) && $column_4 ) : ?>
					<div class="<?php echo $column_class; ?>">
						<?php dynamic_sidebar( 'footer-fourth-column' ); ?>
					</div>
				<?php
				endif;
				?>
			</div>
		</div>

	</div><!-- end .container -->

	<?php

	$footer_bar = get_option( 'pearl_footer_bottom_bar', 'true' );
	if ( $footer_bar == 'true' ) {
		?>
		<div class="footer-bottom">
			<div class="container">
				<div class="row">
					<?php
					$footer_copyright = get_option( 'pearl_footer_copyright', sprintf( esc_html__( 'Copyright &copy; %s Medical Guide. All right reserved.', 'pearl-medicalguide' ), date( "Y" ) ) );

					if ( ! empty( $footer_copyright ) ) {
						?>
						<div class="col-md-6 col-sm-6 clearfix">
							<span class="copyrights"><?php echo esc_html( $footer_copyright ); ?></span>
						</div>
						<?php
					}

					$show_footer_facebook = get_option('pearl_footer_facebook', true);
					$facebook_url = get_option('pearl_social_link_facebook');

					$show_footer_twitter = get_option('pearl_footer_twitter', true);
					$twitter_url = get_option('pearl_social_link_twitter');

					$show_footer_google = get_option('pearl_footer_google', true);
					$google_url = get_option('pearl_social_link_google');

					$show_footer_vimeo = get_option('pearl_footer_vimeo', true);
					$vimeo_url = get_option('pearl_social_link_vimeo');

					$show_footer_instagram = get_option('pearl_footer_instagram', true);
					$instagram_url = get_option('pearl_social_link_instagram');

					$show_footer_pinterest = get_option('pearl_footer_pinterest', true);
					$pinterest_url = get_option('pearl_social_link_pinterest');

					$show_footer_linkedin = get_option('pearl_footer_linkedin', true);
					$linkedin_url = get_option('pearl_social_link_linkedin');

					if ( ! empty( $facebook_url ) || ! empty( $twitter_url ) || ! empty( $google_url ) || ! empty( $vimeo_url )
						|| ! empty( $instagram_url ) || ! empty( $pinterest_url ) || ! empty( $linkedin_url ) ) {
						?>
						<div class="col-md-6 col-sm-6 clearfix">
							<div class="social-icons">
								<?php
								if ( $show_footer_facebook && ! empty( $facebook_url ) ) :
									echo '<a href="' . esc_url( $facebook_url ) . '" class="fb"><i class="icon-euro"></i></a>';
								endif;
								if ( $show_footer_twitter && ! empty( $twitter_url ) ) :
									echo '<a href="' . esc_url( $twitter_url ) . '" class="tw"><i class="icon-yen"></i></a>';
								endif;
								if ( $show_footer_google && ! empty( $google_url ) ) :
									echo '<a href="' . esc_url( $google_url ) . '" class="gp"><i class="icon-google-plus"></i></a>';
								endif;
								if ( $show_footer_vimeo && ! empty( $vimeo_url ) ) :
									echo '<a href="' . esc_url( $vimeo_url ) . '" class="vimeo"><i class="icon-vimeo4"></i></a>';
								endif;
								if ( $show_footer_instagram && ! empty( $instagram_url ) ) :
									echo '<a href="' . esc_url( $instagram_url ) . '" class="instagram"><i class="icon-instagram"></i></a>';
								endif;
								if ( $show_footer_pinterest && ! empty( $pinterest_url ) ) :
									echo '<a href="' . esc_url( $pinterest_url ) . '" class="pinterest"><i class="icon-pinterest"></i></a>';
								endif;
								if ( $show_footer_linkedin && ! empty( $linkedin_url ) ) :
									echo '<a href="' . esc_url( $linkedin_url ) . '" class="linkedin"><i class="icon-linkedin3"></i></a>';
								endif;
								?>
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}
	?>

</footer>

<a href="#" class="cd-top"></a>

</div><!-- end #wrap -->

<?php wp_footer(); ?>

</body></html>
