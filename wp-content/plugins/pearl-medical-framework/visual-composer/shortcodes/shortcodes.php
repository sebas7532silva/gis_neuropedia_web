<?php
/**
 * Pearl Theme Shortcodes
 */

/**
 * Contact Form
 */
add_shortcode( 'pearl_contact_form', 'pearl_contact_form' );
function pearl_contact_form( $atts ) {

    // Params extraction
    extract(shortcode_atts(array(
        'email' => '',
        'cc_email' => '',
        'bcc_email' => '',
        'gdpr_checkbox' => '',
    ), $atts));

    ob_start();

    if ( ! empty( $email ) ) {
        ?>
        <form name="contact_form" id="contact_form" class="contact-form" method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>" novalidate="novalidate">
            <div class="col-md-4">
                <input type="text" data-delay="300" class="required" placeholder="<?php esc_html_e( 'Name*', 'pearl-medical-framework' ); ?>" name="name" title="<?php esc_html_e( '* Please provide your name', 'pearl-medical-framework' ); ?>" id="contact_name">
            </div>
            <div class="col-md-4">
                <input type="text" data-delay="300" class="required email" placeholder="<?php esc_html_e( 'Email*', 'pearl-medical-framework' ); ?>" name="email" title="<?php esc_html_e( '* Please provide your email', 'pearl-medical-framework' ); ?>" id="contact_email">
            </div>
            <div class="col-md-4">
                <input type="text" data-delay="300" placeholder="<?php esc_html_e( 'Number', 'pearl-medical-framework' ); ?>" name="number" title="<?php esc_html_e( 'Please provide your number', 'pearl-medical-framework' ); ?>">
            </div>
            <div class="col-md-12">
                <textarea data-delay="500" class="required" placeholder="<?php esc_html_e( 'Message*', 'pearl-medical-framework' ); ?>" name="message" title="<?php esc_html_e( '* Please provide your message', 'pearl-medical-framework' ); ?>" id="message"></textarea>
                <?php if( 'true' == $gdpr_checkbox ) : ?>
                    <p class="gdpr-checkbox">
                        <label class="gdpr-checkbox-label" for="contact_form_gdpr_checkbox">
                            <input type="checkbox" id="contact_form_gdpr_checkbox" name="contact_form_gdpr_checkbox" value="yes" class="required" title="<?php esc_html_e( '*Please check GDPR checkbox', 'pearl-medical-framework' ); ?>" aria-required="true">
                            <?php esc_html_e( 'I consent to having this website store my submitted information so they can respond to my inquiry.', 'pearl-medical-framework' ); ?>
                        </label>
                    </p>
                <?php endif; ?>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3 submit-and-loader">
                        <input name="submit" id="submit-button" type="submit" value="<?php esc_html_e( 'Submit', 'pearl-medical-framework' ); ?>">
                    </div>
                    <div class="col-md-2">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" id="ajax-loader" alt="<?php esc_html_e( 'Loading...', 'pearl-medical-framework' ); ?>">
                    </div>
                </div>
            </div>
            <input type="hidden" name="action" value="pearl_send_message"/>
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'send_message_nonce' ); ?>"/>
            <input type="hidden" name="target" value="<?php echo antispambot( sanitize_email( $email ) ); ?>">
            <input type="hidden" name="cc_email" value="<?php echo antispambot( sanitize_email( $cc_email ) ); ?>">
            <input type="hidden" name="bcc_email" value="<?php echo antispambot( sanitize_email( $bcc_email ) ); ?>">
        </form>
        <div class="col-md-12 contact-form-response-status">
            <p class="success" id="success" style="display:none;"></p>
            <p class="error" id="error" style="display:none;"></p>
        </div>
        <?php
    }

    return ob_get_clean();
}

/**
 * Contacts List
 */
add_shortcode( 'pearl_contact_information', 'pearl_contact_information' );
function pearl_contact_information( $atts ) {

    // Params extraction
    extract(shortcode_atts(array(
        'title' => '',
        'desc' => '',
        'phone' => '',
        'fax' => '',
        'email' => '',
        'site' => '',
        'address' => '',
        'facebook_link' => '',
        'twitter_link' => '',
        'google_link' => '',
        'vimeo_link' => '',
    ), $atts));

    $contact_title   = $title;
    $contact_desc    = $desc;
    $contact_phone   = $phone;
    $contact_fax     = $fax;
    $contact_email   = $email;
    $contact_site    = $site;
    $contact_address = $address;
    $contact_social['facebook_link'] = $facebook_link;
    $contact_social['twitter_link']  = $twitter_link;
    $contact_social['google_link']   = $google_link;
    $contact_social['vimeo_link']    = $vimeo_link;

    ob_start();
    ?>
    <div class="contact-get">
        <?php
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
                    echo '<span><b>' . esc_html__( 'Phone:', 'pearl-medical-framework' ) . '</b> ' . esc_html( $contact_phone ) . '</span>';
                endif;

                if ( ! empty( $contact_fax ) ) :
                    echo '<span><b>' . esc_html__( 'Fax:', 'pearl-medical-framework' ) . '</b> ' . esc_html( $contact_fax ) . '</span>';
                endif;

                if ( ! empty( $contact_email ) && is_email( $contact_email ) ) :
                    echo '<span><b>' . esc_html__( 'Email:', 'pearl-medical-framework' ) . '</b> <a href="mailto:' . esc_attr( $contact_email ) . '">' . antispambot( sanitize_email( $contact_email ) ) . '</a></span>';
                endif;

                if ( ! empty( $contact_site ) ) :
                    echo '<span><b>' . esc_html__( 'Website:', 'pearl-medical-framework' ) . '</b> <a href="' . esc_url( $contact_site ) . '" target="_blank">' . esc_url( $contact_site ) . '</a></span>';
                endif;

                if ( ! empty( $contact_address ) ) :
                    echo '<span><b>' . esc_html__( 'Address:', 'pearl-medical-framework' ) . '</b> ' . esc_textarea( $contact_address ) . '</span>';
                endif;
                ?>
            </div>
            <?php
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
    <?php
    return ob_get_clean();
}

/**
 * Google Map
 */
add_shortcode( 'pearl_google_map', 'pearl_google_map' );
function pearl_google_map( $atts ) {

    // Params extraction
    extract(shortcode_atts(array(
        'lat' => '',
        'lng' => '',
        'zoom' => '',
        'direction' => '',
    ), $atts));

    $map_lat        = $lat;
    $map_lng        = $lng;
    $map_zoom        = $zoom;
    $map_direction   = $direction;
    $unique_map_id   =  abs( $lat );
    $unique_map_id   =  str_replace('.','', $unique_map_id);

    ob_start();
    ?>
    <div class="google-map-wrapper our-location">
        <div id="google-map-<?php echo esc_attr($unique_map_id); ?>" class="google-map map"></div>
        <?php
        if ( ! empty( $map_direction ) ) { ?>
            <div class="get-directions">
                <form action="http://maps.google.com/maps" method="get" target="_blank">
                    <input type="text" name="saddr" placeholder="<?php esc_html_e( 'Enter Your Address', 'pearl-medicalguide' ) ?>"/>
                    <input type="hidden" name="daddr" value="<?php echo sanitize_text_field( $map_direction ); ?>"/>
                    <input type="submit" class="direction-btn" value="">
                </form>
            </div>
        <?php } ?>
    </div>
    <script>
        function initializeContactMap() {
            var officeLocation = new google.maps.LatLng( <?php echo esc_html($map_lat); ?> , <?php echo esc_html( $map_lng ); ?>);
            var contactMapOptions = {
                zoom:  <?php echo esc_html( $map_zoom ); ?>,
                center: officeLocation,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                scrollwheel: false
            };
            var contactMap = new google.maps.Map(document.getElementById('google-map-<?php echo esc_attr($unique_map_id); ?>'), contactMapOptions);
            var contactMarker = new google.maps.Marker({
                position: officeLocation,
                map: contactMap
            });
        }
        window.onload = initializeContactMap();
    </script>
    <?php
    return ob_get_clean();
}


/**
 * Services Tabs
 */
add_shortcode( 'pearl_services_tabs', 'pearl_services_tabs' );
function pearl_services_tabs( $atts ) {
	extract( shortcode_atts( array(
		'number_of_posts' => 4
	), $atts ) );

	ob_start();
	?>
	<div class="container">

		<div id="tabbed-nav">
			<ul>
				<?php

				$service_args = array( 'post_type' => 'service', 'posts_per_page' => $number_of_posts );
				$services     = get_posts( $service_args );

				foreach ( $services as $post ) { ?>
					<li data-post="<?php $post->ID; ?>">
						<a><?php echo sanitize_text_field( $post->post_title ); ?></a>
					</li>
				<?php } ?>
			</ul>


			<div>
				<?php

				$service_args = array(
					'post_type'      => 'service',
					'posts_per_page' => 5,
				);

				// The Query
				$services = new WP_Query( $service_args );

				// The Loop
				if ( $services->have_posts() ) {
					while ( $services->have_posts() ) {
						$services->the_post();
						?>
						<div data-post="<?php the_ID(); ?>">
							<div class="row">

								<div class="col-md-5">
									<div class="welcome-serv-img">
										<?php
										if ( has_post_thumbnail() ) :
											the_post_thumbnail( 'pearl_image_size_762_700' );
										endif;
										?>
									</div>
								</div>

								<div class="col-md-7">
									<div class="detail detail-btn">
										<h4><?php the_title(); ?></h4>
										<div class="ser-content"><p><?php echo wpautop(get_pearl_excerpt(80)); ?></p></div>
										<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'pearl-medical-framework' ); ?></a>
									</div>
								</div>

							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
		</div>

	</div>
	<?php
	return ob_get_clean();
}


/**
 * Main Heading
 */
add_shortcode( 'pearl_main_heading', 'pearl_main_heading' );
function pearl_main_heading( $atts ) {
	extract( shortcode_atts( array(
		'title'         => '',
		'desc'          => '',
		'heading_color' => ''
	), $atts ) );


	ob_start();
	?>
	<div class="container main-heading-container">
		<div class="main-title <?php echo $heading_color; ?>">
			<?php
			if ( ! empty( $title ) ) {
				echo '<h2>' . $title . '</h2>';
			}

			if ( ! empty( $desc ) ) {
				echo '<p>' . $desc . '</p>';
			}
			?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}


/**
 * Appointment Form
 */
add_shortcode( 'pearl_appointment_form', 'pearl_appointment_form' );
function pearl_appointment_form( $atts ) {
	extract( shortcode_atts( array(
		'title'     => '',
		'desc'      => '',
		'app_image' => '',
		'img_src'   => '',
	), $atts ) );

	if ( ! empty( $app_image ) ) {
		$img_src = wp_get_attachment_image_src( $app_image, 'full' );
		$img_src = $img_src[0];
	}

	ob_start();
	?>
	<div class="make-appointment-two appointment-shortcode">
		<div class="container">
			<div class="row">

				<?php
				if ( ! empty( $img_src ) ) {
					?>
					<div class="col-md-5">
						<img src="<?php echo esc_url( $img_src ); ?>" alt="">
					</div>
					<?php
				}
				?>

				<div class="col-md-7">
					<div class="appointment-form">

						<div class="main-title">
							<?php
							if ( ! empty( $title ) ) {
								?>
								<h2><?php echo wp_kses( $title, array( 'strong' => array() ) ); ?></h2>
								<?php
							}

							if ( ! empty( $desc ) ) {
								?>
								<p><?php echo esc_html( $desc ); ?></p>
								<?php
							}
							?>
						</div>

						<div class="form">
							<section class="bgcolor-a">
								<p class="error" id="error" style="display:none;"></p>
								<p class="success" id="success" style="display:none;"></p>

								<form name="appointment_form" id="appointment_form" method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">
                                    <span class="input input--kohana">
                                        <input class="input__field input__field--kohana required" type="text" id="name" title="<?php esc_html_e( '* Please enter your name.', 'pearl-medical-framework' ); ?>" name="name"/>
                                        <label class="input__label input__label--kohana" for="input-29">
                                            <i class="icon-user6 icon icon--kohana"></i>
                                            <span class="input__label-content input__label-content--kohana"><?php esc_html_e( 'Your Name', 'pearl-medical-framework' ); ?></span>
                                        </label>
                                    </span>
									<span class="input input--kohana">
                                        <input class="input__field input__field--kohana required email" type="text" id="email" title="<?php esc_html_e( '* Please enter a valid email.', 'pearl-medical-framework' ); ?>" name="email"/>
                                        <label class="input__label input__label--kohana" for="input-30">
                                            <i class="icon-dollar icon icon--kohana"></i>
                                            <span class="input__label-content input__label-content--kohana"><?php esc_html_e( 'Email Address', 'pearl-medical-framework' ); ?></span>
                                        </label>
                                    </span>
									<span class="input input--kohana last">
                                        <input class="input__field input__field--kohana" type="text" id="number" name="number"/>
                                        <label class="input__label input__label--kohana" for="input-31">
                                            <i class="icon-phone5 icon icon--kohana"></i>
                                            <span class="input__label-content input__label-content--kohana"><?php esc_html_e( 'Phone Number', 'pearl-medical-framework' ); ?></span>
                                        </label>
                                    </span>

									<span class="input input--kohana">
                                        <input class="input__field input__field--kohana required date" type="text required" id="datepicker" placeholder="<?php esc_html_e( 'Appointment Date', 'pearl-medical-framework' ); ?>" title="<?php esc_html_e( '* Please select an appointment date.', 'pearl-medical-framework' ); ?>" name="app_date"/>
                                    </span>

									<span class="input input--kohana message">
                                        <textarea class="input__field input__field--kohana" id="textarea" name="message"></textarea>

                                        <label class="input__label input__label--kohana" for="textarea">
                                            <i class="icon-new-message icon icon--kohana"></i>
                                            <span class="input__label-content input__label-content--kohana"><?php esc_html_e( 'Message', 'pearl-medical-framework' ); ?></span>
                                        </label>
                                    </span>

									<input name="submit_appointment" type="submit" id="submit-button" value="<?php esc_html_e( 'send', 'pearl-medical-framework' ); ?>">
									<div class="col-md-1 ajax-loader-wrapper">
										<img src="<?php echo get_template_directory_uri(); ?>/images/app-ajax-loader.svg" id="ajax-loader" alt="<?php esc_html_e( 'Loading...', 'pearl-medical-framework' ); ?>">
									</div>
									<input type="hidden" name="action" value="pearl_appointment_request"/>
									<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'appointment_request_nonce' ); ?>"/>
									<input type="hidden" name="target" value="<?php echo antispambot( get_option( 'pearl_contact_form_email' ) ); ?>">

								</form>
							</section>
						</div>

					</div>
				</div>

			</div>
		</div>
	</div><!--End Appointment-->
	<?php
	return ob_get_clean();
}


/**
 * Quote
 */
add_shortcode( 'pearl_single_quote', 'pearl_single_quote' );
function pearl_single_quote( $atts ) {
	extract( shortcode_atts( array(
		'author'     => '',
		'quote'      => '',
		'background' => ''
	), $atts ) );


	ob_start();
	?>
	<div class="dr-quote">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?php
					if ( ! empty( $quote ) ) {
						echo '<span class="quote">"' . $quote . '"</span>';
					}

					if ( ! empty( $author ) ) {
						echo '<span class="name">- "' . $author . '"</span>';
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Static Service
 */
add_shortcode( 'pearl_static_service', 'pearl_static_service' );
function pearl_static_service( $atts ) {
	extract( shortcode_atts( array(
		'icon'         => '',
		'title'        => '',
		'desc'         => '',
		'bottom_space' => 'high-space',
		'icon_type'    => '',
		'color_scheme' => '',
		'style'        => 'right_text',
	), $atts ) );


	ob_start();
	?>
	<div class="col-md-12 static-service">
		<?php
		if ( $style == 'right_text' ) {

			if ( $icon_type ) {

				?>
				<div class="services-sec-four <?php echo $color_scheme; ?> clearfix">

					<?php
					if ( ! empty( $icon ) ) {
						?>
						<div class="icon">
							<?php echo '<i class="' . $icon . '"></i>'; ?>
						</div>
						<?php
					}
					?>
					<div class="detail">
						<?php
						if ( ! empty( $title ) ) {
							echo '<h6>' . $title . '</h6>';
						}

						if ( ! empty( $desc ) ) {
							echo '<p>' . $desc . '</p>';
						}
						?>
					</div>

				</div>
				<?php
			} else {
				?>
				<div class="service-sec-one <?php echo sanitize_html_class( $bottom_space ); ?>">
					<?php
					if ( ! empty( $icon ) ) {
						?>
						<div class="icon">
							<?php echo '<i class="' . $icon . '"></i>'; ?>
						</div>
						<?php
					}
					?>
					<div class="detail">
						<?php
						if ( ! empty( $title ) ) {
							echo '<h5>' . $title . '</h5>';
						}

						if ( ! empty( $desc ) ) {
							echo '<p>' . $desc . '</p>';
						}
						?>
					</div>
				</div>
				<?php
			}
		} else {
			?>
			<div class="service-sec">
				<?php
				if ( ! empty( $icon ) ) {
					?>
					<div class="icon">
						<?php echo '<i class="' . $icon . '"></i>'; ?>
					</div>
					<?php
				}
				?>
				<?php
				if ( ! empty( $title ) ) {
					echo '<h6>' . $title . '</h6>';
				}

				if ( ! empty( $desc ) ) {
					echo '<p>' . $desc . '</p>';
				}
				?>
			</div>
			<?php
		}
		?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Guide Blocks
 */
add_shortcode( 'pearl_guide_blocks', 'pearl_guide_blocks' );
function pearl_guide_blocks( $atts ) {
	extract( shortcode_atts( array(
		'first_title'      => '',
		'first_desc'       => '',
		'first_link_text'  => 'Learn More',
		'first_link'       => '',
		'second_title'     => '',
		'second_desc'      => '',
		'second_link_text' => 'Learn More',
		'second_link'      => '',
		'third_title'      => '',
		'third_desc'       => '',
		'third_link_text'  => 'Learn More',
		'third_link'       => '',
	), $atts ) );


	ob_start();
	?>
	<div class="services-three">
		<?php
		if ( ! empty( $first_title ) || ! empty( $first_desc ) ) {
			?>
			<div class="serv-sec">
				<?php
				if ( ! empty( $first_title ) ) {
					echo '<h3>' . $first_title . '</h3>';
				}

				if ( ! empty( $first_desc ) ) {
					echo '<p>' . $first_desc . '</p>';
				}

				if ( ! empty( $first_link_text ) || ! empty( $first_link ) ) {
					echo '<a href="' . $first_link . '">' . $first_link_text . '</a>';
				}
				?>
			</div>
			<?php
		}

		if ( ! empty( $second_title ) || ! empty( $second_desc ) ) {
			?>
			<div class="serv-sec serv-sec2">
				<?php
				if ( ! empty( $second_title ) ) {
					echo '<h3>' . $second_title . '</h3>';
				}

				if ( ! empty( $second_desc ) ) {
					echo '<p>' . $second_desc . '</p>';
				}

				if ( ! empty( $second_link_text ) || ! empty( $second_link ) ) {
					echo '<a href="' . $second_link . '">' . $second_link_text . '</a>';
				}
				?>
			</div>
			<?php
		}

		if ( ! empty( $third_title ) || ! empty( $third_desc ) ) {
			?>
			<div class="serv-sec serv-sec3">
				<?php
				if ( ! empty( $third_title ) ) {
					echo '<h3>' . $third_title . '</h3>';
				}

				if ( ! empty( $third_desc ) ) {
					echo '<p>' . $third_desc . '</p>';
				}

				if ( ! empty( $third_link_text ) || ! empty( $third_link ) ) {
					echo '<a href="' . $third_link . '">' . $third_link_text . '</a>';
				}
				?>
			</div>
			<?php
		}
		?>
	</div>
	<div class="clear"></div>
	<?php
	return ob_get_clean();
}


/**
 * FAQ Section
 */
add_shortcode( 'pearl_faq_section', 'pearl_faq_section' );
function pearl_faq_section( $atts ) {
	extract( shortcode_atts( array(
		'number_of_posts' => 4,
		'style'           => 'simple',
	), $atts ) );

	ob_start();

	$args = array(
		'post_type'      => 'faq',
		'posts_per_page' => $number_of_posts
	);

	$faq_posts = new WP_Query( $args );
	$faq_id    = 'procedures-faq';
	$faq_class = 'accordion';
	$count     = 0;


	if ( $style == 'icon' ) {
		$faq_id    = 'pearl-accordion';
		$faq_class = 'pearl-accordion';
	}


	if ( $faq_posts->have_posts() ) {

		echo '<ul id="' . $faq_id . '" class="' . $faq_class . '">';
		while ( $faq_posts->have_posts() ) {
			$faq_posts->the_post();

			$li_class = $sub_class = '';

			if ( $count == 0 ) {
				$li_class  = 'open';
				$sub_class = 'display-block';
			}

			$icon_html = '';
			if ( $style == 'icon' ) {
				$meta_data = get_post_custom();

				if ( ! empty( $meta_data['PEARL_META_icon'] ) ) {
					$icon_html = '<i class="' . $meta_data['PEARL_META_icon'][0] . '"></i>';
				}
			}

			?>
			<li class="<?php echo $li_class; ?>">
				<div class="link"><?php echo $icon_html;
					the_title(); ?><i class="icon-chevron-down"></i></div>
				<ul class="submenu <?php echo $sub_class; ?>">
					<li>
						<?php the_content(); ?>
					</li>
				</ul>
			</li>
			<?php
			$count ++;
		}
		echo '</div>';
	}

	return ob_get_clean();
}


/**
 * Gallery section
 */
add_shortcode( 'pearl_gallery_section', 'pearl_gallery_section' );
function pearl_gallery_section( $atts ) {
	extract( shortcode_atts( array(
		'pearl_gallery_images' => ''
	), $atts ) );

	ob_start();

	$pearl_gallery_images = explode( ',', $pearl_gallery_images );

	if ( is_array( $pearl_gallery_images ) && ! empty( $pearl_gallery_images ) ) {
		?>
		<div class="owl-carousel services-slide">
			<?php
			foreach ( $pearl_gallery_images as $image_id ) {
				$image_url = wp_get_attachment_image_url( $image_id, 'full' );
				echo '<div class="item"><img src="' . $image_url . '" alt="' . get_the_title( $image_id ) . '"></div>';
			}
			?>

		</div>
		<?php

	}

	return ob_get_clean();
}


/**
 * Pearl List
 */
add_shortcode( 'pearl_list_section', 'pearl_list_section' );
function pearl_list_section( $atts ) {
	extract( shortcode_atts( array(
		'style'  => 'dot',
		'list'   => '',
		'layout' => 'fullwidth'
	), $atts ) );

	ob_start();

	$list = explode( '*', $list );

	if ( is_array( $list ) && ! empty( $list ) ) {

		if ( $style == 'dot' ) {
			?>
			<ul class="pearl-list-one <?php echo $layout; ?>">
				<?php
				foreach ( $list as $item ) {
					if ( ! empty( $item ) ) {
						echo '<li><span>' . $item . '</span></li>';
					}
				}
				?>
			</ul>
			<?php
		} else if ( $style == 'check' ) {
			?>
			<ul class="pearl-list-two">
				<?php
				foreach ( $list as $item ) {
					if ( ! empty( $item ) ) {
						echo '<li><i class="icon-checkmark"></i><span>' . $item . '</span></li>';
					}
				}
				?>
			</ul>
			<?php
		} else if ( $style == 'arrow' ) {
			?>
			<ul class="pearl-list-three">
				<?php
				foreach ( $list as $item ) {
					if ( ! empty( $item ) ) {
						echo '<li><a><i class="icon-arrow-long-right"></i> ' . $item . '</a></li>';
					}
				}
				?>
			</ul>
			<?php
		}
		?>

		<?php

	}

	return ob_get_clean();
}


/**
 * Stats Counter
 */
add_shortcode( 'pearl_stats_counter', 'pearl_stats_counter' );
function pearl_stats_counter( $atts ) {
	extract( shortcode_atts( array(
		'first_count_title'  => '',
		'first_count_num'    => '',
		'second_count_title' => '',
		'second_count_num'   => '',
		'third_count_title'  => '',
		'third_count_num'    => '',
		'fourth_count_title' => '',
		'fourth_count_num'   => '',
		'fifth_count_title'  => '',
		'fifth_count_num'    => '',
		'style'              => 'simple'
	), $atts ) );


	ob_start();

	if ( $style == 'simple' ) {
		?>
		<div class="fun-facts counter-iconic" id="counters">
			<div class="row">
				<?php
				if ( ! empty( $first_count_title ) && ! empty( $first_count_num ) ) {
					?>
					<div class="col-md-3">
						<div class="counter">
							<span class="number quantity-counter1 quantity-counter highlight"><?php echo $first_count_num; ?></span>
							<span class="what-do"><?php echo $first_count_title; ?></span>
						</div>
					</div>
					<?Php
				}

				if ( ! empty( $second_count_title ) && ! empty( $second_count_num ) ) {
					?>
					<div class="col-md-3">
						<div class="counter">
							<span class="number quantity-counter2 quantity-counter highlight"><?php echo $second_count_num; ?></span>
							<span class="what-do"><?php echo $second_count_title; ?></span>
						</div>
					</div>
					<?Php
				}

				if ( ! empty( $third_count_title ) && ! empty( $third_count_num ) ) {
					?>
					<div class="col-md-3">
						<div class="counter">
							<span class="number quantity-counter3 quantity-counter highlight"><?php echo $third_count_num; ?></span>
							<span class="what-do"><?php echo $third_count_title; ?></span>
						</div>
					</div>
					<?Php
				}

				if ( ! empty( $fourth_count_title ) && ! empty( $fourth_count_num ) ) {
					?>
					<div class="col-md-3">
						<div class="counter">
							<span class="number quantity-counter4 quantity-counter highlight"><?php echo $fourth_count_num; ?></span>
							<span class="what-do"><?php echo $fourth_count_title; ?></span>
						</div>
					</div>
					<?Php
				}
				?>
			</div>
		</div>
		<?php
	} else {
		?>
		<div id="pie-charts" class="container">

			<div class="text-center">
				<?php
				if ( ! empty( $first_count_title ) && ! empty( $first_count_num ) ) {
					?>
					<span class="chart first" data-percent="<?php echo $first_count_num; ?>">
                            <span class="percent"><?php echo $first_count_num; ?></span>
                            <span class="year"><?php echo $first_count_title; ?></span>
                            <canvas height="181" width="181"></canvas></span>
					<?Php
				}

				if ( ! empty( $second_count_title ) && ! empty( $second_count_num ) ) {
					?>
					<span class="chart" data-percent="<?php echo $second_count_num; ?>">
                            <span class="percent"><?php echo $second_count_num; ?></span>
                            <span class="year"><?php echo $second_count_title; ?></span>
                            <canvas height="181" width="181"></canvas></span>
					<?Php
				}

				if ( ! empty( $third_count_title ) && ! empty( $third_count_num ) ) {
					?>
					<span class="chart" data-percent="<?php echo $third_count_num; ?>">
                            <span class="percent"><?php echo $third_count_num; ?></span>
                            <span class="year"><?php echo $third_count_title; ?></span>
                            <canvas height="181" width="181"></canvas></span>
					<?Php
				}

				if ( ! empty( $fourth_count_title ) && ! empty( $fourth_count_num ) ) {
					?>
					<span class="chart" data-percent="<?php echo $fourth_count_num; ?>">
                            <span class="percent"><?php echo $fourth_count_num; ?></span>
                            <span class="year"><?php echo $fourth_count_title; ?></span>
                            <canvas height="181" width="181"></canvas></span>
					<?Php
				}

				if ( ! empty( $fifth_count_title ) && ! empty( $fifth_count_num ) ) {
					?>
					<span class="chart" data-percent="<?php echo $fifth_count_num; ?>">
                                <span class="percent"><?php echo $fifth_count_num; ?></span>
                                <span class="year"><?php echo $fifth_count_title; ?></span>
                                <canvas height="181" width="181"></canvas></span>
					<?Php
				}
				?>
			</div>

		</div>
		<?php
	}

	return ob_get_clean();
}

/**
 * Testimonials
 */
add_shortcode( 'pearl_testimonials_list', 'pearl_testimonials_list' );
function pearl_testimonials_list( $atts ) {
	extract( shortcode_atts( array(
		'heading'         => '',
		'number_of_posts' => 3,
		'font_color'      => 'dark-testi',
	), $atts ) );


	ob_start();
	?>
	<div class="patients-testi <?php echo sanitize_html_class( $font_color ); ?>">
		<div class="container">

			<?php
			if ( ! empty( $heading ) ) {
				?>
				<div class="row">
					<div class="col-md-12">
						<div class="main-title main-title2">
							<h2><?php echo sanitize_text_field( $heading ); ?></h2>
						</div>
					</div>
				</div>
				<?php
			}
			?>

			<div id="testimonials">
				<div class="container">
					<div class="row">

						<div class="col-md-12">
							<div class="span12">

								<div id="owl-demo2" class="owl-carousel">

									<?php
									$testimonials_args = array(
										'post_type'      => 'testimonial',
										'posts_per_page' => $number_of_posts
									);

									$testimonials = new WP_Query( $testimonials_args );

									if ( $testimonials->have_posts() ) {
										while ( $testimonials->have_posts() ) {
											$testimonials->the_post();
											$meta_data = get_post_custom();
											?>
											<div class="testi-sec">
												<?php
												if ( has_post_thumbnail() ) {
													the_post_thumbnail( 'pearl_image_size_270_270' );
												}
												?>
												<div class="height10"></div>
												<?php
												if ( ! empty( $meta_data['PEARL_META_patient_name'] ) ) :
													echo '<span class="name">' . $meta_data['PEARL_META_patient_name'][0] . '</span>';
												endif;
												if ( ! empty( $meta_data['PEARL_META_the_patient'] ) ) :
													echo '<span class="patient">' . $meta_data['PEARL_META_the_patient'][0] . '</span>';
												endif;
												if ( ! empty( $meta_data['PEARL_META_testimonial_text'] ) ) :
													echo '<div class="height30"></div>';
													echo '<p>' . $meta_data['PEARL_META_testimonial_text'][0] . '</p>';
												endif;

												?>
												<div class="height35"></div>
											</div>
											<?php
										}
									}
									?>
								</div>

							</div>
						</div>

					</div>
				</div>
			</div>

		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Pricing Table
 */
add_shortcode( 'pearl_pricing_table', 'pearl_pricing_table' );
function pearl_pricing_table( $atts ) {
	extract( shortcode_atts( array(
		'style'        => 'default',
		'heading'      => 'Standard',
		'currency'     => '£',
		'price'        => '100',
		'interval'     => 'Per Month',
		'button_text'  => '',
		'button_url'   => '',
		'table_fields' => '',
	), $atts ) );

	$table_fields = explode( '*', $table_fields );

	ob_start();
	?>
	<div class="pricing-table text-center <?php echo $style; ?>">
		<h3 class="pricing-table-heading"><?php echo $heading; ?></h3>
		<p class="table-price"><span class="currency"><?php echo $currency; ?></span><?php echo $price; ?>
			<span><?php echo $interval; ?></span></p>
		<ul class="list list-unstyled">
			<?php
			if ( ! empty( $table_fields ) ) {
				foreach ( $table_fields as $field ) {
					if ( ! empty( $field ) ) {
						echo '<li>' . $field . '</li>';
					}
				}
			}
			?>
		</ul>
		<?php
		if ( ! empty( $button_text ) && ! empty( $button_url ) ) {
			?>
			<br>
			<div class="pricing-table-footer">
				<a href="<?php echo $button_url; ?>"><?php echo $button_text; ?></a>
			</div>
			<?php
		}
		?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Static Service
 */
add_shortcode( 'pearl_posts_list', 'pearl_posts_list' );
function pearl_posts_list( $atts ) {
	extract( shortcode_atts( array(
		'number_of_posts' => 3
	), $atts ) );


	ob_start();
	?>
	<div id="latest-news" class="latest-news">
		<div class="container">
			<div id="owl-demo" class="owl-carousel">

				<?php
				$page = '';
				if ( is_home() || is_front_page() ) {
					$page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				}

				$posts_args = array(
					'post_type'           => 'post',
					'posts_per_page'      => $number_of_posts,
					'paged'               => $page,
					'ignore_sticky_posts' => 1,
					'meta_query'          => array(
						array(
							'key'     => '_thumbnail_id',
							'compare' => 'EXISTS'
						),
					)
				);

				$posts = new WP_Query( $posts_args );

				if ( $posts->have_posts() ) {
					while ( $posts->have_posts() ) {
						$posts->the_post();
						if ( has_post_thumbnail() ) {
							?>
							<div class="post item">
								<?php

								the_post_thumbnail( 'pearl_image_size_712_446', array( 'class' => 'lazyOwl' ) );

								?>
								<div class="detail">
									<?php echo get_avatar( get_the_author_meta( 'ID' ), 112 ); ?>
									<h4>
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h4>
									<p><?php the_pearl_excerpt( 12 ); ?></p>
									<span><a href="<?php the_permalink(); ?>"><i class="icon-clock3"></i> <?php the_date( 'F j, Y' ); ?></a></span>
									<span class="comment"><a href="<?php comments_link(); ?>"><i class="icon-icons206"></i> <?php comments_number( esc_html__( 'no comments', 'pearl-medical-framework' ), esc_html__( 'one comment', 'pearl-medical-framework' ), esc_html__( '% comments', 'pearl-medical-framework' ) ); ?></a></span>
								</div>
							</div>
							<?php
						}
					}
				}
				?>
			</div>
		</div>

	</div>

	<?php
	return ob_get_clean();
}


/**
 * Doctors List
 */
add_shortcode( 'pearl_doctors_list', 'pearl_doctors_list' );
function pearl_doctors_list( $atts ) {
	extract( shortcode_atts( array(
		'number_of_posts' => 3,
		'style'           => 'multiple',
		'department'      => '',
	), $atts ) );

	ob_start();

	$doctors_args = array(
		'post_type'      => 'doctor',
		'posts_per_page' => $number_of_posts,
	);

	if( ! empty( $department ) ) {

		$departments = explode( ',', $department );
		$taxonomy = array(
			array(
				'taxonomy' => 'doctor-department',
				'field'    => 'department',
				'terms'    => $departments,
			),
		);

		$doctors_args['tax_query'] = $taxonomy;
	}

	$doctors = new WP_Query( $doctors_args );

	if ( $style == 'single' ) {
		?>
		<div class="member-detail">
			<div class="container">
				<div id="team-detail" class="owl-carousel">
					<?php

					if ( $doctors->have_posts() ) {
						while ( $doctors->have_posts() ) {
							$doctors->the_post();
							$meta_data = get_post_custom();
							?>
							<div class="post item">

								<div class="col-md-5">

									<div class="gallery-sec">
										<div class="image-hover img-layer-slide-left-right">
											<?php
											if ( has_post_thumbnail() ) :
												the_post_thumbnail( 'pearl_image_size_762_700', true );
											endif;
											?>
											<div class="layer">
												<?php
												if ( ! empty( $meta_data['PEARL_META_facebook_url'] ) ) :
													echo '<a href="' . $meta_data['PEARL_META_facebook_url'][0] . '" target="_blank"><i class="icon-euro"></i></a> ';
												endif;
												if ( ! empty( $meta_data['PEARL_META_twitter_url'] ) ) :
													echo '<a href="' . $meta_data['PEARL_META_twitter_url'][0] . '" target="_blank"><i class="icon-yen"></i></a> ';
												endif;
												if ( ! empty( $meta_data['PEARL_META_google_url'] ) ) :
													echo '<a href="' . $meta_data['PEARL_META_google_url'][0] . '" target="_blank"><i class="icon-caddieshoppingstreamline"></i></a> ';
												endif;
												?>
											</div>
										</div>
									</div>

								</div>

								<div class="col-md-7">
									<div class="team-detail">

										<div class="name">
											<h6><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
											<span><?php the_terms( get_the_ID(), 'doctor-department', ' ', ', ', ' ' ); ?></span>
										</div>

										<ul>
											<?php
											if ( ! empty( $meta_data['PEARL_META_speciality'] ) ) :
												echo '<li><span class="title">' . esc_html__( 'Speciality', 'pearl-medical-framework' ) . '</span> <span>' . $meta_data['PEARL_META_speciality'][0] . '</span></li>';
											endif;

											if ( ! empty( $meta_data['PEARL_META_degree'] ) ) :
												echo '<li><span class="title">' . esc_html__( 'Degrees', 'pearl-medical-framework' ) . '</span> <span>' . $meta_data['PEARL_META_degree'][0] . '</span></li>';
											endif;

											if ( ! empty( $meta_data['PEARL_META_experience'] ) ) :
												echo '<li><span class="title">' . esc_html__( 'Experience', 'pearl-medical-framework' ) . '</span> <span>' . $meta_data['PEARL_META_experience'][0] . '</span></li>';
											endif;

											if ( ! empty( $meta_data['PEARL_META_training'] ) ) :
												echo '<li><span class="title">' . esc_html__( 'Training', 'pearl-medical-framework' ) . '</span> <span>' . $meta_data['PEARL_META_training'][0] . '</span></li>';
											endif;

											if ( ! empty( $meta_data['PEARL_META_work_days'] ) ) :
												echo '<li><span class="title">' . esc_html__( 'Work days', 'pearl-medical-framework' ) . '</span> <span>' . $meta_data['PEARL_META_work_days'][0] . '</span></li>';
											endif;
											?>
										</ul>

									</div>
								</div>
							</div>
							<?php
						}
					}
					?>
				</div>
			</div>
		</div>
		<?php
	} else {
		?>
		<div class="meet-specialists">
			<div class="container">
				<div id="owl-demo4" class="owl-carousel">
					<?php

					if ( $doctors->have_posts() ) {
						while ( $doctors->have_posts() ) {
							$doctors->the_post();

							$meta_data = get_post_custom();
							?>
							<div class="post item">

								<div class="gallery-sec">
									<div class="image-hover img-layer-slide-left-right">
										<?php
										if ( has_post_thumbnail() ) {
											the_post_thumbnail( 'pearl_image_size_762_700' );
										}
										?>
										<div class="layer">
											<?php
											if ( ! empty( $meta_data['PEARL_META_facebook_url'] ) ) :
												echo '<a href="' . $meta_data['PEARL_META_facebook_url'][0] . '" target="_blank"><i class="icon-euro"></i></a> ';
											endif;
											if ( ! empty( $meta_data['PEARL_META_twitter_url'] ) ) :
												echo '<a href="' . $meta_data['PEARL_META_twitter_url'][0] . '" target="_blank"><i class="icon-yen"></i></a> ';
											endif;
											if ( ! empty( $meta_data['PEARL_META_google_url'] ) ) :
												echo '<a href="' . $meta_data['PEARL_META_google_url'][0] . '" target="_blank"><i class="icon-caddieshoppingstreamline"></i></a> ';
											endif;
											?>
										</div>
									</div>
								</div>

								<div class="detail">
                                    <h6><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
									<span><?php the_terms( get_the_ID(), 'doctor-department', ' ', ', ', ' ' ); ?></span>
									<p><?php the_pearl_excerpt( 18 ) ?></p>
									<a href="<?php the_permalink(); ?>"><?php esc_html_e( '- View Profile', 'pearl-medical-framework' ); ?></a>
								</div>

							</div>
							<?php
						}
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}

	return ob_get_clean();
}