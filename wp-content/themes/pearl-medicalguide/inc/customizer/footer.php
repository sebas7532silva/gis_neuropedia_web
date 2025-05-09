<?php

/**
 * Customizer settings for Footer
 */

if ( ! function_exists( 'pearl_footer_customizer' ) ) :
	function pearl_footer_customizer( WP_Customize_Manager $wp_customize ) {

		/**
		 * Footer Section
		 */
		$wp_customize->add_section( 'pearl_footer_section', array(
			'title'    => esc_html__( 'Footer', 'pearl-medicalguide' ),
			'priority' => 140,
		) );

		$wp_customize->add_setting( 'pearl_footer_style', array(
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field'
		) );
		$wp_customize->add_control(
			new Pearl_Dropdown_Control(
				$wp_customize,
				'pearl_footer_style',
				array(
					'label'    => esc_html__( 'Select a Footer Style', 'pearl-medicalguide' ),
					'section'  => 'pearl_footer_section',
					'settings' => 'pearl_footer_style',
					'choices'  => array(
						'dark'  => 'Dark',
						'light' => 'Light'
					)
				)
			)
		);

		$wp_customize->add_setting( 'pearl_footer_phone_title', array(
			'type'              => 'option',
			'default'           => esc_html__( 'For emergency cases', 'pearl-medicalguide' ),
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_footer_phone_title', array(
			'label'           => esc_html__( 'Footer Phone Title', 'pearl-medicalguide' ),
			'type'            => 'text',
			'section'         => 'pearl_footer_section',
			'active_callback' => 'pearl_is_header_top_bar_enabled',
		) );

		$wp_customize->add_setting( 'pearl_footer_phone_number', array(
			'type'              => 'option',
			'default'           => esc_html__( '1-300-400-8211', 'pearl-medicalguide' ),
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_footer_phone_number', array(
			'label'   => esc_html__( 'Footer Phone Number', 'pearl-medicalguide' ),
			'type'    => 'text',
			'section' => 'pearl_footer_section'
		) );

		// widget Layout
		$wp_customize->add_setting( 'pearl_footer_widget_layout', array(
			'type'              => 'option',
			'default'           => 'four-column',
			'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( 'pearl_footer_widget_layout', array(
			'label'    => __( 'Footer Columns', 'pearl_footer_section' ),
			'section'  => 'pearl_footer_section',
			'settings' => 'pearl_footer_widget_layout',
			'type'     => 'radio',
			'choices'  => array(
				'one-column'   => __( 'One Column', 'pearl-medicalguide' ),
				'two-column'   => __( 'Two Columns', 'pearl-medicalguide' ),
				'three-column' => __( 'Three Columns', 'pearl-medicalguide' ),
				'four-column'  => __( 'Four Columns', 'pearl-medicalguide' ),
			),
		) );

		/* Footer bottom Bar  */
		$wp_customize->add_setting( 'pearl_footer_bottom_bar', array(
			'type'              => 'option',
			'default'           => 'true',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_footer_bottom_bar', array(
			'label'   => esc_html__( 'Bottom Bar', 'pearl-medicalguide' ),
			'type'    => 'radio',
			'section' => 'pearl_footer_section',
			'choices' => array(
				'true'  => esc_html__( 'Show', 'pearl-medicalguide' ),
				'false' => esc_html__( 'Hide', 'pearl-medicalguide' ),
			)
		) );

		$wp_customize->add_setting( 'pearl_footer_copyright', array(
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => sprintf( esc_html__( 'Copyright &copy; %s Medical Guide. All right reserved.', 'pearl-medicalguide' ), date( "Y" ) ),
		) );
		$wp_customize->add_control( 'pearl_footer_copyright', array(
			'label'   => esc_html__( 'Copyright Text', 'pearl-medicalguide' ),
			'type'    => 'text',
			'section' => 'pearl_footer_section',
		) );

		/* Separator */
		$wp_customize->add_setting( 'pearl_footer_social_separator', array(
			'type'              => 'option',
			'sanitize_callback' => 'pearl_sanitize'
		) );
		$wp_customize->add_control(
			new Pearl_Separator_Control(
				$wp_customize,
				'pearl_footer_social_separator',
				array(
					'section' => 'pearl_header_basics_section',
				)
			)
		);

		/* Facebook */
		$wp_customize->add_setting( 'pearl_footer_facebook', array(
			'type'              => 'option',
			'default'           => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_footer_facebook', array(
			'label'           => esc_html__( 'Show Facebook', 'pearl-medicalguide' ),
			'type'            => 'checkbox',
			'section'         => 'pearl_footer_section',
		) );

		/* Twitter */
		$wp_customize->add_setting( 'pearl_footer_twitter', array(
			'type'              => 'option',
			'default'           => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_footer_twitter', array(
			'label'           => esc_html__( 'Show Twitter', 'pearl-medicalguide' ),
			'type'            => 'checkbox',
			'section'         => 'pearl_footer_section',
		) );

		/* Google Plus */
		$wp_customize->add_setting( 'pearl_footer_google', array(
			'type'              => 'option',
			'default'           => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_footer_google', array(
			'label'           => esc_html__( 'Show Google Plus', 'pearl-medicalguide' ),
			'type'            => 'checkbox',
			'section'         => 'pearl_footer_section',
		) );

		/* Vimeo */
		$wp_customize->add_setting( 'pearl_footer_vimeo', array(
			'type'              => 'option',
			'default'           => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_footer_vimeo', array(
			'label'           => esc_html__( 'Show Vimeo', 'pearl-medicalguide' ),
			'type'            => 'checkbox',
			'section'         => 'pearl_footer_section',
		) );

		/* Instagram */
		$wp_customize->add_setting( 'pearl_footer_instagram', array(
			'type'              => 'option',
			'default'           => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_footer_instagram', array(
			'label'           => esc_html__( 'Show Instagram', 'pearl-medicalguide' ),
			'type'            => 'checkbox',
			'section'         => 'pearl_footer_section',
		) );

		/* Pinterest */
		$wp_customize->add_setting( 'pearl_footer_pinterest', array(
			'type'              => 'option',
			'default'           => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_footer_pinterest', array(
			'label'           => esc_html__( 'Show Pinterest', 'pearl-medicalguide' ),
			'type'            => 'checkbox',
			'section'         => 'pearl_footer_section',
		) );

		/* Linkedin */
		$wp_customize->add_setting( 'pearl_footer_linkedin', array(
			'type'              => 'option',
			'default'           => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_footer_linkedin', array(
			'label'           => esc_html__( 'Show Linkedin', 'pearl-medicalguide' ),
			'type'            => 'checkbox',
			'section'         => 'pearl_footer_section',
		) );
	}

	add_action( 'customize_register', 'pearl_footer_customizer' );
endif;