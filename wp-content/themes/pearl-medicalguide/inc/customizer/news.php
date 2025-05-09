<?php

/**
 * Customizer settings for News
 */

if ( ! function_exists( 'pearl_news_customizer' ) ) :
	function pearl_news_customizer( WP_Customize_Manager $wp_customize ) {

		/**
		 * News Section
		 */
		$wp_customize->add_section( 'pearl_news_section', array(
			'title'    => esc_html__( 'News', 'pearl-medicalguide' ),
			'priority' => 130,
		) );

		$wp_customize->add_setting( 'pearl_news_style', array(
			'type'              => 'option',
			'default'           => 'sidebar',
			'sanitize_callback' => 'sanitize_text_field'
		) );
		$wp_customize->add_control(
			new Pearl_Dropdown_Control(
				$wp_customize,
				'pearl_news_style',
				array(
					'label'    => esc_html__( 'Select a News Page Style', 'pearl-medicalguide' ),
					'section'  => 'pearl_news_section',
					'settings' => 'pearl_news_style',
					'choices'  => array(
						'sidebar'   => 'Right Sidebar',
						'double'    => 'Double Post',
						'fullwidth' => 'Full Width',
						'text'      => 'Text Based'
					)
				)
			)
		);

	}

	add_action( 'customize_register', 'pearl_news_customizer' );
endif;