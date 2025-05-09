<div class="col-md-12">
	<div class="our-location">

		<?php

		$map_title       = get_option( 'pearl_map_title' );
		$map_description = get_option( 'pearl_map_description' );
		$map_direction   = get_option( 'pearl_map_direction' );

		// only for demo purpose
		if ( isset( $_GET['map'] ) && $_GET['map'] == 'single' ) {
			$map_title       = '';
			$map_description = '';
		}

		if ( ! empty( $map_title ) || ! empty( $map_description ) ) {

			echo '<div class="main-title">';

			if ( ! empty( $map_title ) ) {
				echo '<h2>' . esc_html( $map_title ) . '</h2>';
			}

			if ( ! empty( $map_description ) ) {
				echo '<p>' . esc_textarea( $map_description ) . '</p>';
			}

			echo '</div>';
		}
		?>


		<div id="contact-map" class="map"></div>
		<div class="get-directions">
			<form action="http://maps.google.com/maps" method="get" target="_blank">
				<input type="text" name="saddr" placeholder="<?php esc_html_e( 'Enter Your Address', 'pearl-medicalguide' ) ?>"/>
				<input type="hidden" name="daddr" value="<?php echo sanitize_text_field( $map_direction ); ?>"/>
				<input type="submit" value="" class="direction-btn"/>
			</form>
		</div>
	</div>
</div>