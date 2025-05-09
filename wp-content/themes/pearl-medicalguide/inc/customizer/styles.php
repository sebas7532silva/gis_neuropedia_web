<?php
/**
 * Customizer settings for styles
 */

if ( ! function_exists( 'pearl_styles_customizer' ) ) :
	function pearl_styles_customizer( WP_Customize_Manager $wp_customize ) {

		$image_path = get_template_directory_uri() . '/css/theme-colors/images/colors/';

		/**
		 * News Section
		 */
		$wp_customize->add_section( 'pearl_styles_section', array(
			'title'    => esc_html__( 'Styles', 'pearl-medicalguide' ),
			'priority' => 145,
		) );

		// site design switcher
		$wp_customize->add_setting( 'pearl_design_switcher', array(
			'type'              => 'option',
			'default'           => 'no',
			'sanitize_callback' => 'sanitize_text_field'
		) );
		$wp_customize->add_control(
			new Pearl_Dropdown_Control(
				$wp_customize,
				'pearl_design_switcher',
				array(
					'label'    => esc_html__( 'Design Switcher', 'pearl-medicalguide' ),
					'section'  => 'pearl_styles_section',
					'settings' => 'pearl_design_switcher',
					'choices'  => array(
						'yes' => 'Enable',
						'no'  => 'Disable'
					)
				)
			)
		);

		/* Color Scheme */
		$wp_customize->add_setting( 'pearl_color_scheme', array(
			'type'              => 'option',
			'default'           => 'default-color',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control(
			new Pearl_Custom_Radio_Image_Control(
				$wp_customize,
				'pearl_color_scheme',
				array(
					'section'     => 'pearl_styles_section',
					'label'       => esc_html__( 'Theme Color Scheme', 'pearl-medicalguide' ),
					'description' => esc_html__( 'Choose your desired color scheme.', 'pearl-medicalguide' ),
					'choices'     => array(
						'light-blue'    => $image_path . 'light-blue.png',
						'red'           => $image_path . 'red.png',
						'green'         => $image_path . 'green.png',
						'light-green'   => $image_path . 'light-green.png',
						'dark-blue'     => $image_path . 'dark-blue.png',
						'orange'        => $image_path . 'orange.png',
						'yellow'        => $image_path . 'yellow.png',
						'pink'          => $image_path . 'pink.png',
						'purple'        => $image_path . 'purple.png',
						'brown'         => $image_path . 'brown.png',
						'default-color' => $image_path . 'default-color.png'
					)
				)
			)
		);


		/* Quick CSS */
		$wp_customize->add_setting( 'pearl_quick_css', array(
			'type'              => 'option',
			'sanitize_callback' => 'esc_textarea',
		) );
		$wp_customize->add_control( 'pearl_quick_css', array(
			'label'       => esc_html__( 'Quick CSS', 'pearl-medicalguide' ),
			'description' => esc_html__( 'Enter small CSS changes here. If you need to change major portions of the theme then use child-custom.css file in child theme.', 'pearl-medicalguide' ),
			'type'        => 'textarea',
			'section'     => 'pearl_styles_section',
		) );

	}

	add_action( 'customize_register', 'pearl_styles_customizer' );
endif;