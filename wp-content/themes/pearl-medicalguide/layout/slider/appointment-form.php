<!--Start Make Appointment-->
<div class="make-appointment">
	<div class="container">

		<ul id="accordion" class="accordion">
			<li>

				<div class="link"></i>
					<span class="appointment-title"><?php esc_html_e( 'Make an Appointment', 'pearl-medicalguide' ); ?></span>
					<i class="icon-chevron-down"></i></div>

				<section class="bgcolor-3">
					<p class="error" id="error" style="display:none;"></p>
					<p class="success" id="success" style="display:none;"></p>

					<form name="appointment_form" id="appointment_form" method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">

                        <span class="input input--kohana">
                            <input class="input__field input__field--kohana required" type="text" id="input-29" name="name" title="<?php esc_html_e( '* Please enter your name.', 'pearl-medicalguide' ); ?>"/>
                            <label class="input__label input__label--kohana" for="name">
                                <i class="icon-user6 icon icon--kohana"></i>
                                <span class="input__label-content input__label-content--kohana"><?php esc_html_e( 'Your Name', 'pearl-medicalguide' ); ?></span>
                            </label>
                        </span>
						<span class="input input--kohana">
                            <input class="input__field input__field--kohana required email" type="text" id="emil" name="email" title="<?php esc_html_e( '* Please enter a valid email.', 'pearl-medicalguide' ); ?>"/>
                            <label class="input__label input__label--kohana" for="email">
                                <i class="icon-dollar icon icon--kohana"></i>
                                <span class="input__label-content input__label-content--kohana"><?php esc_html_e( 'Email Address', 'pearl-medicalguide' ); ?></span>
                            </label>
                        </span>
						<span class="input input--kohana last">
                            <input class="input__field input__field--kohana" type="text" id="number" name="number"/>
                            <label class="input__label input__label--kohana" for="number">
                                <i class="icon-phone5 icon icon--kohana"></i>
                                <span class="input__label-content input__label-content--kohana"><?php esc_html_e( 'Phone Number', 'pearl-medicalguide' ); ?></span>
                            </label>
                        </span>

						<span class="input input--kohana">
                            <input class="input__field input__field--kohana required" type="text" id="datepicker" placeholder="<?php esc_html_e( 'Appointment Date', 'pearl-medicalguide' ); ?>" name="app_date" title="<?php esc_html_e( '* Please select an appointment date.', 'pearl-medicalguide' ); ?>"/>
                        </span>

						<span class="input input--kohana message">
                            <input class="input__field input__field--kohana" type="text" id="message" name="message">
                            <label class="input__label input__label--kohana" for="message">
                                <i class="icon-new-message icon icon--kohana"></i>
                                <span class="input__label-content input__label-content--kohana"><?php esc_html_e( 'Message', 'pearl-medicalguide' ); ?></span>
                            </label>
                        </span>

						<input name="submit" type="submit" value="<?php esc_html_e( 'send', 'pearl-medicalguide' ); ?>">
						<!--                        <div class="col-md-1 ajax-loader-wrapper"><img src="--><?php //echo PEARL_THEME_DIRECTORY_URI; ?><!--/images/app-ajax-loader.svg" id="ajax-loader" alt="--><?php //esc_html_e( 'Loading...', 'pearl-medicalguide' ); ?><!--"></div>-->
						<input type="hidden" name="action" value="pearl_appointment_request"/>
						<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'appointment_request_nonce' ); ?>"/>
						<input type="hidden" name="target" value="<?php echo antispambot( get_option( 'pearl_appointment_form_email' ) ); ?>">
					</form>
				</section>
			</li>
		</ul>
	</div>
</div><!--End Make Appointment-->