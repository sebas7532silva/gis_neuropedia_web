<?php
/**
 * Customizer settings for Appointment Page
 */


if ( ! function_exists( 'pearl_appointment_customizer' ) ) :
	function pearl_appointment_customizer( WP_Customize_Manager $wp_customize ) {

		/**
		 * Appointment Form Section
		 */
		$wp_customize->add_section( 'pearl_appointment_section', array(
			'title'    => esc_html__( 'Appointment Form', 'pearl-medicalguide' ),
			'priority' => 135,
		) );

		/* Appointment page form email */
		$wp_customize->add_setting( 'pearl_appointment_form_email', array(
			'type'              => 'option',
			'default'           => get_option( 'admin_email' ),
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_appointment_form_email', array(
			'label'       => esc_html__( 'Appointment Target Email', 'pearl-medicalguide' ),
			'type'        => 'text',
			'section'     => 'pearl_appointment_section',
			'settings'    => 'pearl_appointment_form_email',
			'description' => esc_html__( 'Provide an email address where you would like to receive appointment forms requests.', 'pearl-medicalguide' )
		) );
	}

	add_action( 'customize_register', 'pearl_appointment_customizer' );
endif;