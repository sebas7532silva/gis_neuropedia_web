<div class="col-sm-5 col-md-3">
	<?php
	$logo_url        = get_option( 'pearl_site_logo' );
	$retina_logo_url = get_option( 'pearl_site_logo_retina' );

	if ( ! empty( $logo_url ) ) {
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php pearl_logo_img( $logo_url, $retina_logo_url ); ?>
		</a>
		<?php
	} else {

		$pearl_site_name = get_bloginfo( 'name' );

		?>
		<h1 class="logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php echo esc_html( $pearl_site_name ); ?>
			</a>
		</h1>
		<?php
	}
	?>
</div>