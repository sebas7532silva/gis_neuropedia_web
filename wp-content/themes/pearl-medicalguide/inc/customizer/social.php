<?php

/**
 * Customizer settings for Social Links
 */

if ( ! function_exists( 'pearl_social_links_customizer' ) ) :
	function pearl_social_links_customizer( WP_Customize_Manager $wp_customize ) {

		/* Social Links Section */
		$wp_customize->add_section( 'pearl_social_links_section', array(
			'title'    => esc_html__( 'Social Links', 'pearl-medicalguide' ),
			'priority' => 140,
		) );

		/* Facebook URL */
		$wp_customize->add_setting( 'pearl_social_link_facebook', array(
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'pearl_social_link_facebook', array(
			'label'   => esc_html__( 'Facebook URL', 'pearl-medicalguide' ),
			'type'    => 'url',
			'section' => 'pearl_social_links_section',
		) );

		/* Twitter URL */
		$wp_customize->add_setting( 'pearl_social_link_twitter', array(
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'pearl_social_link_twitter', array(
			'label'   => esc_html__( 'Twitter URL', 'pearl-medicalguide' ),
			'type'    => 'url',
			'section' => 'pearl_social_links_section',
		) );

		/* Google URL */
		$wp_customize->add_setting( 'pearl_social_link_google', array(
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'pearl_social_link_google', array(
			'label'   => esc_html__( 'Google URL', 'pearl-medicalguide' ),
			'type'    => 'url',
			'section' => 'pearl_social_links_section',
		) );

		/* Vimeo URL */
		$wp_customize->add_setting( 'pearl_social_link_vimeo', array(
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'pearl_social_link_vimeo', array(
			'label'   => esc_html__( 'Vimeo URL', 'pearl-medicalguide' ),
			'type'    => 'url',
			'section' => 'pearl_social_links_section',
		) );

		/* Instagram URL */
		$wp_customize->add_setting( 'pearl_social_link_instagram', array(
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'pearl_social_link_instagram', array(
			'label'   => esc_html__( 'Instagram URL', 'pearl-medicalguide' ),
			'type'    => 'url',
			'section' => 'pearl_social_links_section',
		) );

		/* Pinterest URL */
		$wp_customize->add_setting( 'pearl_social_link_pinterest', array(
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'pearl_social_link_pinterest', array(
			'label'   => esc_html__( 'Pinterest URL', 'pearl-medicalguide' ),
			'type'    => 'url',
			'section' => 'pearl_social_links_section',
		) );

		/* Linkedin URL */
		$wp_customize->add_setting( 'pearl_social_link_linkedin', array(
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'pearl_social_link_linkedin', array(
			'label'   => esc_html__( 'Linkedin URL', 'pearl-medicalguide' ),
			'type'    => 'url',
			'section' => 'pearl_social_links_section',
		) );

	}

	add_action( 'customize_register', 'pearl_social_links_customizer' );
endif;