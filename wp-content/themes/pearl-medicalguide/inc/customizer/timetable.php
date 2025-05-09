<?php
/**
 * Customizer settings for Slider
 */

if ( ! function_exists( 'pearl_timetable_customizer' ) ) :
	function pearl_timetable_customizer( WP_Customize_Manager $wp_customize ) {

		/**
		 * Slider Section
		 */
		$wp_customize->add_section( 'pearl_timetable_section', array(
			'title'    => esc_html__( 'Timetable Settings', 'pearl-medicalguide' ),
			'priority' => 125,
		) );

		/* Timetable over sliders */
		$wp_customize->add_setting( 'pearl_timetable_heading', array(
			'type'              => 'option',
			'default'           => 'Working Days',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_timetable_heading', array(
			'label'    => esc_html__( 'Timetable Heading', 'pearl-medicalguide' ),
			'type'     => 'text',
			'settings' => 'pearl_timetable_heading',
			'section'  => 'pearl_timetable_section',
		) );

		$wp_customize->add_setting( 'pearl_timetable_days_1', array(
			'type'              => 'option',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_timetable_days_1', array(
			'label'    => esc_html__( 'Working Days (slot 1)', 'pearl-medicalguide' ),
			'type'     => 'text',
			'settings' => 'pearl_timetable_days_1',
			'section'  => 'pearl_timetable_section',
		) );

		$wp_customize->add_setting( 'pearl_timetable_days_time_1', array(
			'type'              => 'option',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_timetable_days_time_1', array(
			'label'    => esc_html__( 'Working Days Time (slot 1)', 'pearl-medicalguide' ),
			'type'     => 'text',
			'settings' => 'pearl_timetable_days_time_1',
			'section'  => 'pearl_timetable_section',
		) );

		$wp_customize->add_setting( 'pearl_timetable_days_2', array(
			'type'              => 'option',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_timetable_days_2', array(
			'label'    => esc_html__( 'Working Days (slot 2)', 'pearl-medicalguide' ),
			'type'     => 'text',
			'settings' => 'pearl_timetable_days_2',
			'section'  => 'pearl_timetable_section',
		) );

		$wp_customize->add_setting( 'pearl_timetable_days_time_2', array(
			'type'              => 'option',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_timetable_days_time_2', array(
			'label'    => esc_html__( 'Working Days Time (slot 2)', 'pearl-medicalguide' ),
			'type'     => 'text',
			'settings' => 'pearl_timetable_days_time_2',
			'section'  => 'pearl_timetable_section',
		) );

		$wp_customize->add_setting( 'pearl_timetable_days_3', array(
			'type'              => 'option',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_timetable_days_3', array(
			'label'    => esc_html__( 'Working Days (slot 3)', 'pearl-medicalguide' ),
			'type'     => 'text',
			'settings' => 'pearl_timetable_days_3',
			'section'  => 'pearl_timetable_section',
		) );

		$wp_customize->add_setting( 'pearl_timetable_days_time_3', array(
			'type'              => 'option',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_timetable_days_time_3', array(
			'label'    => esc_html__( 'Working Days Time (slot 3)', 'pearl-medicalguide' ),
			'type'     => 'text',
			'settings' => 'pearl_timetable_days_time_3',
			'section'  => 'pearl_timetable_section',
		) );

	}
endif;

add_action( 'customize_register', 'pearl_timetable_customizer' );