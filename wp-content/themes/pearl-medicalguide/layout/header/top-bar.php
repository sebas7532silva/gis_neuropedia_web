<div class="top-bar">
	<div class="container">
		<div class="row">
			<div class="col-md-5">
				<?php

					$welcome_note = get_option( 'pearl_welcome_text' );

					if ( ! empty( $welcome_note ) ) {
						echo '<span >' . esc_textarea( $welcome_note ) . '</span>';

					}
				?>
			</div>
			<div class="col-md-7">
				<div class="get-touch">

					<ul>
						<?php

							$email_address = get_option( 'pearl_header_email' );
							$phone_number  = get_option( 'pearl_header_phone' );

							if ( ! empty( $phone_number ) ) {
								echo '<li><a href="tel:'. $phone_number .'" ><i class="icon-phone4"></i> ' . esc_html( $phone_number ) . '</a></li>';
							}

							if ( ! empty( $email_address ) ) {
								echo '<li><a href="mailto:' . antispambot( sanitize_email( $email_address ) ) . '"><i class="icon-mail"></i> ' . antispambot( sanitize_email( $email_address ) ) . '</a></li>';
							}
						?>
					</ul>

					<?php
					$show_facebook = get_option('pearl_header_facebook', true);
					$facebook_url = get_option('pearl_social_link_facebook');

					$show_twitter = get_option('pearl_header_twitter', true);
					$twitter_url = get_option('pearl_social_link_twitter');

					$show_google = get_option('pearl_header_google', true);
					$google_url = get_option('pearl_social_link_google');

					$show_vimeo = get_option('pearl_header_vimeo', true);
					$vimeo_url = get_option('pearl_social_link_vimeo');

					$show_instagram = get_option('pearl_header_instagram', true);
					$instagram_url = get_option('pearl_social_link_instagram');

					$show_pinterest = get_option('pearl_header_pinterest', true);
					$pinterest_url = get_option('pearl_social_link_pinterest');

					$show_linkedin = get_option('pearl_header_linkedin', true);
					$linkedin_url = get_option('pearl_social_link_linkedin');

					if ( ! empty( $facebook_url ) || ! empty( $twitter_url ) || ! empty( $google_url ) || ! empty( $vimeo_url )
						 || ! empty( $instagram_url ) || ! empty( $pinterest_url ) || ! empty( $linkedin_url ) ) {
						?>
						<ul class="social-icons">
							<?php
								if ( $show_facebook && ! empty( $facebook_url ) ) :
									echo '<li><a href="' . esc_url( $facebook_url ) . '" class="fb"><i class="icon-euro"></i></a></li>';
								endif;
								if ( $show_twitter && ! empty( $twitter_url ) ) :
									echo '<li><a href="' . esc_url( $twitter_url ) . '" class="tw"><i class="icon-yen"></i></a></li>';
								endif;
								if ( $show_google && ! empty( $google_url ) ) :
									echo '<li><a href="' . esc_url( $google_url ) . '" class="gp"><i class="icon-caddieshoppingstreamline"></i></a></li>';
								endif;
								if ( $show_vimeo && ! empty( $vimeo_url ) ) :
									echo '<li><a href="' . esc_url( $vimeo_url ) . '" class="vo"><i class="icon-pound"></i></a></li>';
								endif;
								if ( $show_instagram && ! empty( $instagram_url ) ) :
									echo '<li><a href="' . esc_url( $instagram_url ) . '" class="instagram"><i class="icon-instagram"></i></a></li>';
								endif;
								if ( $show_pinterest && ! empty( $pinterest_url ) ) :
									echo '<li><a href="' . esc_url( $pinterest_url ) . '" class="pinterest"><i class="icon-pinterest"></i></a></li>';
								endif;
								if ( $show_linkedin && ! empty( $linkedin_url ) ) :
									echo '<li><a href="' . esc_url( $linkedin_url ) . '" class="linkedin"><i class="icon-linkedin3"></i></a></li>';
								endif;
							?>
						</ul>
						<?php
					}
					?>
				</div>
			</div>

		</div><!--end .row -->
	</div><!-- end .container -->
</div><!-- end .top-bar -->