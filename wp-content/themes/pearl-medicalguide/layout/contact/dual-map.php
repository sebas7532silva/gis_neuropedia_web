<div class="get-touch clearfix">
	<div class="col-md-6">
		<?php

			$map_title       = get_option( 'pearl_map_title' );
			$map_description = get_option( 'pearl_map_description' );
			$map_direction   = get_option( 'pearl_map_direction' );

			if ( ! empty( $map_title ) || ! empty( $map_description ) ) {

				echo '<div class="main-title">';

				if ( ! empty( $map_title ) ) {
					echo '<h2>' . wp_kses( $map_title, array( 'strong' => array() ) ) . '</h2>';
				}

				if ( ! empty( $map_description ) ) {
					echo '<p>' . esc_textarea( $map_description ) . '</p>';
				}

				echo '</div>';
			}
		?>

		<div class="map">
			<div class="our-location">
				<div id="contact-map" class="map"></div>
			</div>
			<?php
				if ( ! empty( $map_direction ) ) {
					?>
					<div class="get-directions">
						<form action="http://maps.google.com/maps" method="get" target="_blank">
							<input type="text" name="saddr" placeholder="<?php esc_html_e( 'Enter Your Address', 'pearl-medicalguide' ) ?>"/>
							<input type="hidden" name="daddr" value="<?php echo sanitize_text_field( $map_direction ); ?>"/>
							<input type="submit" class="direction-btn" value="">
						</form>
					</div>
				<?php } ?>
		</div>

		<?php
			$map_detail['phone']   = get_option( 'pearl_map_detail_phone' );
			$map_detail['email']   = get_option( 'pearl_map_detail_email' );
			$map_detail['site']    = get_option( 'pearl_map_detail_site' );
			$map_detail['address'] = get_option( 'pearl_map_detail_address' );

			if ( array_filter( $map_detail ) ) {
				echo '<div class="detail">';
				if ( ! empty( $map_detail['phone'] ) ) :
					echo '<span><b>' . esc_html__( 'Phone', 'pearl-medicalguide' ) . ':</b> ' . esc_html( $map_detail['phone'] ) . '</span>';
				endif;

				if ( ! empty( $map_detail['email'] ) ) :
					echo '<span><b>' . esc_html__( 'Email', 'pearl-medicalguide' ) . ':</b> ' . sanitize_email( $map_detail['email'] ) . '</span>';
				endif;

				if ( ! empty( $map_detail['site'] ) ) :
					echo '<span><b>' . esc_html__( 'Web', 'pearl-medicalguide' ) . ':</b> ' . esc_url( $map_detail['site'] ) . '</span>';
				endif;

				if ( ! empty( $map_detail['address'] ) ) :
					echo '<span><b>' . esc_html__( 'Address', 'pearl-medicalguide' ) . ':</b> ' . esc_html( $map_detail['address'] ) . '</span>';
				endif;
				echo '</div>';
			}
		?>
	</div>

	<div class="col-md-6">
		<div class="contact-adrs2">

			<?php

				$map_title       = get_option( 'pearl_map_title_2' );
				$map_description = get_option( 'pearl_map_description_2' );
				$map_direction   = get_option( 'pearl_map_direction_2' );

				if ( ! empty( $map_title ) || ! empty( $map_description ) ) {

					echo '<div class="main-title">';

					if ( ! empty( $map_title ) ) {
						echo '<h2>' . wp_kses( $map_title, array( 'strong' => array() ) ) . '</h2>';
					}

					if ( ! empty( $map_description ) ) {
						echo '<p>' . esc_textarea( $map_description ) . '</p>';
					}

					echo '</div>';
				}
			?>

			<div class="map">
				<div class="our-location">
					<div id="contact-map-2" class="map"></div>
				</div>
				<?php
					if ( ! empty( $map_direction ) ) {
						?>
						<div class="get-directions">
							<form action="http://maps.google.com/maps" method="get" target="_blank">
								<input type="text" name="saddr" placeholder="<?php esc_html_e( 'Enter Your Address', 'pearl-medicalguide' ) ?>"/>
								<input type="hidden" name="daddr" value="<?php echo sanitize_text_field( $map_direction ); ?>"/>
								<input type="submit" class="direction-btn" value="">
							</form>
						</div>
					<?php } ?>
			</div>

			<?php
				$map_detail['phone']   = get_option( 'pearl_map_detail_phone_2' );
				$map_detail['email']   = get_option( 'pearl_map_detail_email_2' );
				$map_detail['site']    = get_option( 'pearl_map_detail_site_2' );
				$map_detail['address'] = get_option( 'pearl_map_detail_address_2' );

				if ( array_filter( $map_detail ) ) {
					echo '<div class="detail">';
					if ( ! empty( $map_detail['phone'] ) ) :
						echo '<span><b>' . esc_html__( 'Phone', 'pearl-medicalguide' ) . ':</b> ' . esc_html( $map_detail['phone'] ) . '</span>';
					endif;

					if ( ! empty( $map_detail['email'] ) ) :
						echo '<span><b>' . esc_html__( 'Email', 'pearl-medicalguide' ) . ':</b> ' . sanitize_email( $map_detail['email'] ) . '</span>';
					endif;

					if ( ! empty( $map_detail['site'] ) ) :
						echo '<span><b>' . esc_html__( 'Web', 'pearl-medicalguide' ) . ':</b> ' . esc_url( $map_detail['site'] ) . '</span>';
					endif;

					if ( ! empty( $map_detail['address'] ) ) :
						echo '<span><b>' . esc_html__( 'Address', 'pearl-medicalguide' ) . ':</b> ' . esc_html( $map_detail['address'] ) . '</span>';
					endif;
					echo '</div>';
				}
			?>
		</div>
	</div>

</div>