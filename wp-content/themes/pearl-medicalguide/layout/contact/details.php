<div class="col-md-5">
	<div class="contact-get">

		<?php

			$contact_title   = get_option( 'pearl_contact_detail_title' );
			$contact_desc    = get_option( 'pearl_contact_detail_desc' );
			$contact_phone   = get_option( 'pearl_contact_detail_phone' );
			$contact_fax     = get_option( 'pearl_contact_detail_fax' );
			$contact_email   = get_option( 'pearl_contact_detail_email' );
			$contact_site    = get_option( 'pearl_contact_detail_site' );
			$contact_address = get_option( 'pearl_contact_detail_address' );

			if ( ! empty( $contact_title ) || ! empty( $contact_desc ) ) : ?>
				<div class="main-title">
					<?php
						if ( ! empty( $contact_title ) ) : echo '<h2>' . wp_kses( $contact_title, array( 'strong' => array() ) ) . '</h2>'; endif;
						if ( ! empty( $contact_desc ) ) : echo '<p>' . esc_textarea( $contact_desc ) . '</p>'; endif;
					?>
				</div>
			<?php endif; ?>

		<div class="get-in-touch">

			<div class="detail">
				<?php

					if ( ! empty( $contact_phone ) ) :
						echo '<span><b>' . esc_html__( 'Phone:', 'pearl-medicalguide' ) . '</b> ' . esc_html( $contact_phone ) . '</span>';
					endif;

					if ( ! empty( $pearl_options['contact_fax'] ) ) :
						echo '<span><b>' . esc_html__( 'Fax:', 'pearl-medicalguide' ) . '</b> ' . esc_html( $contact_fax ) . '</span>';
					endif;

					if ( ! empty( $contact_email ) && is_email( $contact_email ) ) :
						echo '<span><b>' . esc_html__( 'Email:', 'pearl-medicalguide' ) . '</b> <a href="mailto:' . esc_attr( $contact_email ) . '">' . antispambot( sanitize_email( $contact_email ) ) . '</a></span>';
					endif;

					if ( ! empty( $contact_site ) ) :
						echo '<span><b>' . esc_html__( 'Website:', 'pearl-medicalguide' ) . '</b> <a href="' . esc_url( $contact_site ) . '" target="_blank">' . esc_url( $contact_site ) . '</a></span>';
					endif;

					if ( ! empty( $contact_address ) ) :
						echo '<span><b>' . esc_html__( 'Address:', 'pearl-medicalguide' ) . '</b> ' . esc_textarea( $contact_address ) . '</span>';
					endif;

				?>
			</div>

			<?php
				$contact_social['facebook_link'] = get_option( 'pearl_contact_facebook_link' );
				$contact_social['twitter_link']  = get_option( 'pearl_contact_twitter_link' );
				$contact_social['google_link']   = get_option( 'pearl_contact_google_link' );
				$contact_social['vimeo_link']    = get_option( 'pearl_contact_vimeo_link' );

				if ( array_filter( $contact_social ) ) {
					echo '<div class="social-icons">';
					if ( ! empty( $contact_social['facebook_link'] ) ) :
						echo '<a href="' . esc_url( $contact_social['facebook_link'] ) . '" class="fb" target="_blank"><i class="icon-euro"></i></a>';
					endif;

					if ( ! empty( $contact_social['twitter_link'] ) ) :
						echo '<a href="' . esc_url( $contact_social['twitter_link'] ) . '" class="tw" target="_blank"><i class="icon-yen"></i></a>';
					endif;

					if ( ! empty( $contact_social['google_link'] ) ) :
						echo '<a href="' . esc_url( $contact_social['google_link'] ) . '" class="gp" target="_blank"><i class="icon-google-plus"></i></a>';
					endif;

					if ( ! empty( $contact_social['vimeo_link'] ) ) :
						echo '<a href="' . esc_url( $contact_social['vimeo_link'] ) . '" class="vimeo" target="_blank"><i class="icon-vimeo4"></i></a>';
					endif;
					echo '</div>';
				}
			?>
		</div>

	</div>

</div>