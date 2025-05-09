<?php
/**
 * Register meta boxes
 */

add_filter( 'rwmb_meta_boxes', 'pearl_register_meta_boxes' );

if ( ! function_exists( 'pearl_register_meta_boxes' ) ) {
	function pearl_register_meta_boxes( $meta_boxes ) {

		$prefix = 'pearl_';

		// Banner image metabox
		$meta_boxes[] = array(
			'id'         => 'banner-meta-box',
			'title'      => esc_html__( 'Header Banner Image', 'pearl-medicalguide' ),
			'post_types' => array( 'page', 'post', 'service' ),
			'context'    => 'normal',
			'priority'   => 'high',
			'fields'     => array(
				array(
					'name'             => esc_html__( 'Banner Image', 'pearl-medicalguide' ),
					'id'               => "{$prefix}banner_image",
					'desc'             => esc_html__( 'Please upload the Banner Image.', 'pearl-medicalguide' ),
					'type'             => 'image_advanced',
					'max_file_uploads' => 1
				)
			)
		);

		// Homepage slider area metaboxes
		$meta_boxes[] = array(
			'id'       => 'slider-area-meta-box',
			'title'    => esc_html__( 'Slider Area Configuration', 'pearl-antarctica' ),
			'pages'    => array( 'page' ),
			'context'  => 'normal',
			'priority' => 'high',
			'show'     => array(
				'template' => 'page-templates/home.php'
			),
			'fields'   => array(
				array(
					'name'    => esc_html__( 'Please choose a Type', 'pearl-antarctica' ),
					'id'      => "{$prefix}slider_type",
					'type'    => 'select',
					'std'     => 'one',
					'options' => array(
						'banner'     => esc_html__( 'Default Banner', 'pearl-medicalguide' ),
						'static'     => esc_html__( 'Content Banner', 'pearl-medicalguide' ),
						'revolution' => esc_html__( 'Revolution Slider', 'pearl-medicalguide' )
					)
				),

				// revolution slider alias field
				array(
					'name'    => esc_html__( 'Slider Revolution Alias', 'pearl-antarctica' ),
					'id'      => "{$prefix}rv_slider_alias",
					'type'    => 'text',
					'size'    => '50',
					'visible' => array( 'pearl_slider_type', '=', 'revolution' ),
				),

				// static content banner fields
				array(
					'name'    => 'Banner Icon',
					'id'      => "{$prefix}static_banner_icon",
					'desc'    => sprintf( esc_html__( 'Set a banner icon class. Example: icon-patient-bed ( for the icons list please visit this page: %s )', 'pearl-medicalguide' ), '<a href="http://www.pearlthemes.com/theme/medicalguide/icons-classes/" target="_blank">http://www.pearlthemes.com/theme/medicalguide/icons-classes/</a>' ),
					'type'    => 'text',
					'visible' => array( 'pearl_slider_type', '=', 'static' ),
				),
				array(
					'name'    => esc_html__( 'Banner Heading', 'pearl-medicalguide' ),
					'id'      => "{$prefix}static_banner_heading",
					'desc'    => esc_html__( 'Provide a banner heading.', 'pearl-medicalguide' ),
					'type'    => 'text',
					'visible' => array( 'pearl_slider_type', '=', 'static' ),
				),
				array(
					'name'    => esc_html__( 'Banner Description', 'pearl-medicalguide' ),
					'id'      => "{$prefix}static_banner_description",
					'desc'    => esc_html__( 'Provide the banner description.', 'pearl-medicalguide' ),
					'type'    => 'textarea',
					'visible' => array( 'pearl_slider_type', '=', 'static' ),
				),
				array(
					'name'             => esc_html__( 'Upload a banner image', 'pearl-medicalguide' ),
					'id'               => "{$prefix}static_banner_image",
					'type'             => 'image_advanced',
					'max_file_uploads' => 1,
					'image_size'       => 'thumbnail',
					'visible'          => array( 'pearl_slider_type', '=', 'static' ),
				),
				array(
					'type'    => 'divider',
					'visible' => array( 'pearl_slider_type', '=', 'static' ),
				),
				array(
					'name'    => esc_html__( 'Timetable', 'pearl-medicalguide' ),
					'id'      => "{$prefix}static_banner_timetable",
					'type'    => 'select',
					'options' => array(
						'show' => esc_html__( 'Show', 'pearl-medicalguide' ),
						'hide' => esc_html__( 'Hide', 'pearl-medicalguide' ),
					),
					'visible' => array( 'pearl_slider_type', '!=', 'banner' ),
				),
				array(
					'name'    => esc_html__( 'Appointment Form', 'pearl-medicalguide' ),
					'id'      => "{$prefix}static_banner_app_form",
					'type'    => 'select',
					'options' => array(
						'show' => esc_html__( 'Show', 'pearl-medicalguide' ),
						'hide' => esc_html__( 'Hide', 'pearl-medicalguide' ),
					),
					'visible' => array( 'pearl_slider_type', '!=', 'banner' ),
				),
			)
		);


		// apply a filter before returning meta boxes
		$meta_boxes = apply_filters( 'pearl_theme_meta_boxes', $meta_boxes );

		return $meta_boxes;

	}
}
