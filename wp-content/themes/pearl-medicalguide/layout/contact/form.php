<?php

global $pearl_options;

$form_column     = '12';
$button_column   = '2';
$contact_details = get_option( 'pearl_show_details' );

// only for demo purpose
if ( isset( $_GET['map'] ) && $_GET['map'] == 'single' ) {
	$contact_map     = 'single';
	$contact_details = true;
}

if ( $contact_details == 'true' ) {
	$form_column   = '7';
	$button_column = '3';
}

$form_title = get_option( 'pearl_contact_form_heading' );
$form_desc  = get_option( 'pearl_contact_form_desc' );

?>

<div class="col-md-<?php echo sanitize_html_class( $form_column ); ?>">
	<?php if ( ! empty( $form_title ) || ! empty( $form_desc ) ) : ?>
		<div class="main-title">
			<?php
			if ( ! empty( $form_title ) ) : echo '<h2>' . wp_kses( $form_title, array( 'strong' => array() ) ) . '</h2>'; endif;
			if ( ! empty( $form_desc ) ) : echo '<p>' . esc_textarea( $form_desc ) . '</p>'; endif;
			?>
		</div>
	<?php endif; ?>
	<div class="form">
		<div class="row">
			<?php
			$shortcode_form = get_option( 'pearl_contact_form_shortcode' );
			if ( ! empty( $shortcode_form ) ) :
				echo do_shortcode( $shortcode_form );
			else:
				?>
				<form name="contact_form" id="contact_form" method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">
					<div class="col-md-4">
						<input type="text" data-delay="300" class="required" placeholder="<?php esc_html_e( 'Name*', 'pearl-medicalguide' ); ?>" name="name" title="<?php esc_html_e( '* Please provide your name', 'pearl-medicalguide' ); ?>" id="contact_name" class="input">
					</div>
					<div class="col-md-4">
						<input type="text" data-delay="300" class="required email" placeholder="<?php esc_html_e( 'Email*', 'pearl-medicalguide' ); ?>" name="email" title="<?php esc_html_e( '* Please provide your email', 'pearl-medicalguide' ); ?>" id="contact_email" class="input">
					</div>
					<div class="col-md-4">
						<input type="text" data-delay="300" placeholder="<?php esc_html_e( 'Number', 'pearl-medicalguide' ); ?>" name="number" title="<?php esc_html_e( 'Please provide your number', 'pearl-medicalguide' ); ?>" class="input">
					</div>
					<div class="col-md-12">
						<textarea data-delay="500" class="required" placeholder="<?php esc_html_e( 'Message*', 'pearl-medicalguide' ); ?>" name="message" title="<?php esc_html_e( '* Please provide your message', 'pearl-medicalguide' ); ?>" id="message"></textarea>
					</div>

					<div class="clearfix">
						<div class="col-md-<?php echo sanitize_html_class( $button_column ); ?>">
							<input name="submit" id="submit-button" type="submit" value="<?php esc_html_e( 'Submit', 'pearl-medicalguide' ); ?>">
						</div>
						<div class="col-md-1">
							<img src="<?php echo PEARL_THEME_DIRECTORY_URI; ?>/images/ajax-loader.gif" id="ajax-loader" alt="<?php esc_html_e( 'Loading...', 'pearl-medicalguide' ); ?>">
						</div>
						<input type="hidden" name="action" value="pearl_send_message"/>
						<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'send_message_nonce' ); ?>"/>
						<input type="hidden" name="target" value="<?php echo antispambot( get_option( 'pearl_contact_form_email' ) ); ?>">
					</div>
				</form>
			<?php endif; ?>
			<br>
			<p class="success" id="success" style="display:none;"></p>
			<p class="error" id="error" style="display:none;"></p>
		</div>
	</div>
</div>
