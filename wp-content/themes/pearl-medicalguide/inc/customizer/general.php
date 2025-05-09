<?php
/**
 * Customizer settings for General
 */
if ( ! function_exists( 'pearl_misc_customizer' ) ) :
	function pearl_misc_customizer( WP_Customize_Manager $wp_customize ) {
		/**
		 * General Section
		 */
		$wp_customize->add_section( 'pearl_general_section', array(
			'title'    => esc_html__( 'General', 'pearl-medicalguide' ),
			'priority' => 140,
		) );

		// site loader
		$wp_customize->add_setting( 'pearl_theme_loader', array(
			'type'              => 'option',
			'default'           => 'yes',
			'sanitize_callback' => 'sanitize_text_field'
		) );
		$wp_customize->add_control(
			new Pearl_Dropdown_Control(
				$wp_customize,
				'pearl_theme_loader',
				array(
					'label'    => esc_html__( 'Site Loader', 'pearl-medicalguide' ),
					'section'  => 'pearl_general_section',
					'settings' => 'pearl_theme_loader',
					'choices'  => array(
						'yes' => 'Enable',
						'no'  => 'Disable'
					)
				)
			)
		);


		$wp_customize->add_setting( 'pearl_optimise_scripts', array(
			'type'              => 'option',
			'default'           => 'true',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_optimise_scripts', array(
			'label'    => __( 'Optimise Scripts to Improve Performance', 'pearl-medicalguide' ),
			'section'  => 'pearl_general_section',
			'settings' => 'pearl_optimise_scripts',
			'type'     => 'radio',
			'choices'  => array(
				'true'  => __( 'Yes', 'pearl-medicalguide' ),
				'false' => __( 'No', 'pearl-medicalguide' ),
			),
		) );

		$wp_customize->add_setting( 'pearl_optimise_styles', array(
			'type'              => 'option',
			'default'           => 'true',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_optimise_styles', array(
			'label'    => __( 'Optimise Styles to Improve Performance', 'pearl-medicalguide' ),
			'section'  => 'pearl_general_section',
			'settings' => 'pearl_optimise_styles',
			'type'     => 'radio',
			'choices'  => array(
				'true'  => __( 'Yes', 'pearl-medicalguide' ),
				'false' => __( 'No', 'pearl-medicalguide' ),
			),
		) );

		/* Google Map API Key */
		$wp_customize->add_setting( 'pearl_map_api_key', array(
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_map_api_key', array(
			'label'       => esc_html__( 'Google Map API Key *', 'pearl-medicalguide' ),
			'type'        => 'text',
			'section'     => 'pearl_general_section',
			'settings'    => 'pearl_map_api_key',
			'description' => esc_html__( 'Get your Google Map API Key from here: https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key', 'pearl-medicalguide' )
		) );
	}

	add_action( 'customize_register', 'pearl_misc_customizer' );
endif;