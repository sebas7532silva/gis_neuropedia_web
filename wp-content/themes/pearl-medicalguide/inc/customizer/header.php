<?php
/**
 * Customizer settings for Header
 */
if ( ! function_exists( 'pearl_header_customizer' ) ) :
	function pearl_header_customizer( WP_Customize_Manager $wp_customize ) {
		/* Site Identity (logo) */
		$wp_customize->add_setting( 'pearl_site_logo', array(
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control(
			$wp_customize,
			'pearl_site_logo',
			array(
				'label'    => esc_html__( 'Site Logo', 'pearl-antarctica' ),
				'section'  => 'title_tagline',
				// id of site identity section - Ref: https://developer.wordpress.org/themes/advanced-topics/customizer-api/
				'settings' => 'pearl_site_logo',
				'priority' => 100,
			) ) );

		/* Site Identity ( retina logo) */
		$wp_customize->add_setting( 'pearl_site_logo_retina', array(
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control(
			$wp_customize,
			'pearl_site_logo_retina',
			array(
				'label'       => esc_html__( 'Site Retina Logo', 'pearl-antarctica' ),
				'description' => esc_html__( 'Upload double size of your default logo image. For example, if your default logo image size is 185px by 24px then your retina logo image size should be 370px by 48px.', 'pearl-antarctica' ),
				'section'     => 'title_tagline',
				'settings'    => 'pearl_site_logo_retina',
				'priority'    => 110,
			) ) );

		/* Header Panel */
		$wp_customize->add_panel( 'pearl_header_panel', array(
			'title'    => esc_html__( 'Header', 'pearl-medicalguide' ),
			'priority' => 121
		) );

		/* Header Basic Section */
		$wp_customize->add_section( 'pearl_header_basics_section', array(
			'title'    => esc_html__( 'Basics', 'pearl-medicalguide' ),
			'panel'    => 'pearl_header_panel',
		) );

		/* Header Type */
		$wp_customize->add_setting( 'pearl_header_variation', array(
			'type'              => 'option',
			'default'           => 'top-bar',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_header_variation', array(
			'label'   => esc_html__( 'Header Type', 'pearl-medicalguide' ),
			'type'    => 'radio',
			'section' => 'pearl_header_basics_section',
			'choices' => array(
				'simple'  => esc_html__( 'Simple Header', 'pearl-medicalguide' ),
				'top-bar' => esc_html__( 'Header with TopBar', 'pearl-medicalguide' ),
			)
		) );

		/* Sticky Header */
		$wp_customize->add_setting( 'pearl_sticky_header', array(
			'type'              => 'option',
			'default'           => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_sticky_header', array(
			'label'   => esc_html__( 'Sticky Header', 'pearl-medicalguide' ),
			'type'    => 'checkbox',
			'section' => 'pearl_header_basics_section',
		) );

		/* Header Top Bar  */
		$wp_customize->add_setting( 'pearl_header_top_bar', array(
			'type'              => 'option',
			'default'           => 'true',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_header_top_bar', array(
			'label'           => esc_html__( 'Top Bar', 'pearl-medicalguide' ),
			'type'            => 'radio',
			'section'         => 'pearl_header_basics_section',
			'choices'         => array(
				'true'  => esc_html__( 'Show', 'pearl-medicalguide' ),
				'false' => esc_html__( 'Hide', 'pearl-medicalguide' ),
			),
			'active_callback' => 'pearl_is_header_top_bar_enabled',
		) );

		/* Welcome Text */
		$wp_customize->add_setting( 'pearl_welcome_text', array(
			'type'              => 'option',
			'default'           => 'MedicalGuide Come to Expect the Best in Town.',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_welcome_text', array(
			'label'           => esc_html__( 'Welcome note', 'pearl-medicalguide' ),
			'type'            => 'text',
			'section'         => 'pearl_header_basics_section',
			'active_callback' => 'pearl_is_header_top_bar_enabled',
		) );

		/* Email Address */
		$wp_customize->add_setting( 'pearl_header_email', array(
			'type'              => 'option',
			'default'           => get_option( 'admin_email' ),
			'sanitize_callback' => 'sanitize_email',
		) );
		$wp_customize->add_control( 'pearl_header_email', array(
			'label'           => esc_html__( 'Email Address', 'pearl-medicalguide' ),
			'type'            => 'text',
			'section'         => 'pearl_header_basics_section',
			'active_callback' => 'pearl_is_header_top_bar_enabled',
		) );

		/* Phone Number */
		$wp_customize->add_setting( 'pearl_header_phone', array(
			'type'              => 'option',
			'default'           => '1-300-400-8211',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_header_phone', array(
			'label'           => esc_html__( 'Phone Number', 'pearl-medicalguide' ),
			'type'            => 'text',
			'section'         => 'pearl_header_basics_section',
			'active_callback' => 'pearl_is_header_top_bar_enabled',
		) );

		/* Mobile Menu Header Text*/
		$wp_customize->add_setting( 'pearl_menu_header_text', array(
			'type'              => 'option',
			'default'           => 'Medical Guide',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_menu_header_text', array(
			'label'   => esc_html__( 'Mobile Menu Header Text', 'pearl-medicalguide' ),
			'type'    => 'text',
			'section' => 'pearl_header_basics_section',
		) );

		/* Mobile Menu Footer Text */
		$wp_customize->add_setting( 'pearl_menu_footer_text', array(
			'type'              => 'option',
			'default'           => sprintf( esc_html__( 'Copyrights %s Medical Guide.', 'pearl-medicalguide' ), date("Y")),
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_menu_footer_text', array(
			'label'   => esc_html__( 'Mobile Menu Footer Text', 'pearl-medicalguide' ),
			'type'    => 'text',
			'section' => 'pearl_header_basics_section',
		) );

		/* Separator */
		$wp_customize->add_setting( 'pearl_header_social_separator', array(
			'type'              => 'option',
			'sanitize_callback' => 'pearl_sanitize'
		) );
		$wp_customize->add_control(
			new Pearl_Separator_Control(
				$wp_customize,
				'pearl_header_social_separator',
				array(
					'section' => 'pearl_header_basics_section',
				)
			)
		);

		/* Facebook */
		$wp_customize->add_setting( 'pearl_header_facebook', array(
			'type'              => 'option',
			'default'           => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_header_facebook', array(
			'label'           => esc_html__( 'Show Facebook', 'pearl-medicalguide' ),
			'type'            => 'checkbox',
			'section'         => 'pearl_header_basics_section',
			'active_callback' => 'pearl_is_header_top_bar_enabled',
		) );

		/* Twitter */
		$wp_customize->add_setting( 'pearl_header_twitter', array(
			'type'              => 'option',
			'default'           => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_header_twitter', array(
			'label'           => esc_html__( 'Show Twitter', 'pearl-medicalguide' ),
			'type'            => 'checkbox',
			'section'         => 'pearl_header_basics_section',
			'active_callback' => 'pearl_is_header_top_bar_enabled',
		) );

		/* Google Plus */
		$wp_customize->add_setting( 'pearl_header_google', array(
			'type'              => 'option',
			'default'           => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_header_google', array(
			'label'           => esc_html__( 'Show Google Plus', 'pearl-medicalguide' ),
			'type'            => 'checkbox',
			'section'         => 'pearl_header_basics_section',
			'active_callback' => 'pearl_is_header_top_bar_enabled',
		) );

		/* Vimeo */
		$wp_customize->add_setting( 'pearl_header_vimeo', array(
			'type'              => 'option',
			'default'           => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_header_vimeo', array(
			'label'           => esc_html__( 'Show Vimeo', 'pearl-medicalguide' ),
			'type'            => 'checkbox',
			'section'         => 'pearl_header_basics_section',
			'active_callback' => 'pearl_is_header_top_bar_enabled',
		) );

		/* Instagram */
		$wp_customize->add_setting( 'pearl_header_instagram', array(
			'type'              => 'option',
			'default'           => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_header_instagram', array(
			'label'           => esc_html__( 'Show Instagram', 'pearl-medicalguide' ),
			'type'            => 'checkbox',
			'section'         => 'pearl_header_basics_section',
			'active_callback' => 'pearl_is_header_top_bar_enabled',
		) );

		/* Pinterest */
		$wp_customize->add_setting( 'pearl_header_pinterest', array(
			'type'              => 'option',
			'default'           => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_header_pinterest', array(
			'label'           => esc_html__( 'Show Pinterest', 'pearl-medicalguide' ),
			'type'            => 'checkbox',
			'section'         => 'pearl_header_basics_section',
			'active_callback' => 'pearl_is_header_top_bar_enabled',
		) );

		/* Linkedin */
		$wp_customize->add_setting( 'pearl_header_linkedin', array(
			'type'              => 'option',
			'default'           => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'pearl_header_linkedin', array(
			'label'           => esc_html__( 'Show Linkedin', 'pearl-medicalguide' ),
			'type'            => 'checkbox',
			'section'         => 'pearl_header_basics_section',
			'active_callback' => 'pearl_is_header_top_bar_enabled',
		) );

		/* Header Banner Section */
		$wp_customize->add_section( 'pearl_header_banner_section', array(
			'title'    => esc_html__( 'Banner', 'pearl-medicalguide' ),
			'panel'    => 'pearl_header_panel',
		) );

		/* Banner image */
		$wp_customize->add_setting( 'pearl_banner_image', array(
			'type'              => 'option',
			'sanitize_callback' => 'esc_url'
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control(
			$wp_customize,
			'pearl_banner_image',
			array(
				'label'       => esc_html__( 'Banner Image', 'pearl-medicalguide' ),
				'description' => esc_html__( 'Recommended minimum width is 2000px and minimum height is 300px.', 'pearl-medicalguide' ),
				'section'     => 'pearl_header_banner_section'
			)
		) );



        /* Breadcrumb Section */
        $wp_customize->add_section( 'pearl_header_breadcrumb_section', array(
            'title'    => esc_html__( 'Breadcrumb', 'pearl-medicalguide' ),
            'panel'    => 'pearl_header_panel',
        ) );

        /* Breadcrumb */
        $wp_customize->add_setting( 'pearl_breadcrumb_display', array(
            'type'              => 'option',
            'default'           => 'true',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'pearl_breadcrumb_display', array(
            'label'           => esc_html__( 'Breadcrumb Display', 'pearl-medicalguide' ),
            'type'            => 'radio',
            'section'         => 'pearl_header_breadcrumb_section',
            'choices'         => array(
                'true'  => esc_html__( 'Show', 'pearl-medicalguide' ),
                'false' => esc_html__( 'Hide', 'pearl-medicalguide' ),
            )
        ) );
	}

	add_action( 'customize_register', 'pearl_header_customizer' );
endif;

if ( ! function_exists( 'pearl_is_header_top_bar_enabled' ) ) :
	/**
	 * Checks if slideable sidebar is required or not
	 * @return bool
	 */
	function pearl_is_header_top_bar_enabled() {
		$pearl_header_variation = get_option( 'pearl_header_variation', 'simple' );
		if ( 'simple' != $pearl_header_variation ) {
			return true;
		}

		return false;
	}
endif;