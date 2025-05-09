<?php
/**
 * Shortcodes Mapping to Visual Composer
 */
add_action( 'vc_before_init', 'pearl_integrateWithVC' );

function pearl_integrateWithVC() {

	/**
	 * Main Heading
	 */
	vc_map( array(
		"name"     => esc_html__( "Pearl Heading", "pearl-medical-framework" ),
		"base"     => "pearl_main_heading",
		"class"    => "",
		"category" => esc_html__( "MedicalGuide Theme", "pearl-medical-framework" ),
		"params"   => array(
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Title", "pearl-medical-framework" ),
				"param_name"  => "title",
				"value"       => "",
				"description" => esc_html__( "Please enter a title.", "pearl-medical-framework" ),
				"admin_label" => true
			),
			array(
				"type"        => "textarea",
				"class"       => "",
				"heading"     => esc_html__( "Description", "pearl-medical-framework" ),
				"param_name"  => "desc",
				"value"       => "",
				"description" => esc_html__( "Please enter a description.", "pearl-medical-framework" ),
				"admin_label" => true
			),
			array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => esc_html__( "Color", "pearl-medical-framework" ),
				"param_name"  => "heading_color",
				"value"       => array(
					'Dark'  => 'dark-color',
					'White' => 'white-color'
				),
				"admin_label" => true
			)
		)
	) );

	/**
	 * Main Heading
	 */
	vc_map( array(
		"name"     => esc_html__( "Pearl Appointment Form", "pearl-medical-framework" ),
		"base"     => "pearl_appointment_form",
		"class"    => "",
		"category" => esc_html__( "MedicalGuide Theme", "pearl-medical-framework" ),
		"params"   => array(
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Title", "pearl-medical-framework" ),
				"param_name"  => "title",
				"value"       => "",
				"description" => esc_html__( "Please enter a title.", "pearl-medical-framework" ),
				"admin_label" => true
			),
			array(
				"type"        => "textarea",
				"class"       => "",
				"heading"     => esc_html__( "Description", "pearl-medical-framework" ),
				"param_name"  => "desc",
				"value"       => "",
				"description" => esc_html__( "Please enter a description.", "pearl-medical-framework" ),
				"admin_label" => true
			),
			array(
				"type"        => "attach_image",
				"class"       => "",
				"heading"     => esc_html__( "Appointment Form Image", "pearl-medical-framework" ),
				"param_name"  => "app_image",
				"value"       => "",
				"description" => esc_html__( "Please set an appointment image to display on the left side.", "pearl-medical-framework" )
			)
		)
	) );

	/**
	 * Services Tabs
	 */
	vc_map( array(
		"name"     => esc_html__( "Pearl Services Tabs", 'pearl-medical-framework' ),
		"base"     => "pearl_services_tabs",
		"class"    => "",
		"category" => esc_html__( "MedicalGuide Theme", 'pearl-medical-framework' ),
		"params"   => array(
			array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => esc_html__( "Number of Services to display", 'pearl-medical-framework' ),
				"param_name"  => "number_of_posts",
				"value"       => array( 1, 2, 3, 4, 5, 6, 7, 8 ),
				"description" => esc_html__( "Select a number of services posts you want to display.", 'pearl-medical-framework' ),
				"admin_label" => true
			)
		)
	) );

	/**
	 * Quote
	 */
	vc_map( array(
		"name"     => esc_html__( "Pearl Quote", 'pearl-medical-framework' ),
		"base"     => "pearl_single_quote",
		"class"    => "",
		"category" => esc_html__( "MedicalGuide Theme", 'pearl-medical-framework' ),
		"params"   => array(
			array(
				"type"        => "textarea",
				"class"       => "",
				"heading"     => esc_html__( "Quote", 'pearl-medical-framework' ),
				"param_name"  => "quote",
				"value"       => '',
				"description" => esc_html__( "Please provide quote text here.", 'pearl-medical-framework' ),
				"admin_label" => true
			),
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Author", 'pearl-medical-framework' ),
				"param_name"  => "author",
				"value"       => '',
				"description" => esc_html__( "Please provide quote author name here.", 'pearl-medical-framework' ),
				"admin_label" => true
			)
		)
	) );

	/**
	 * Static Service
	 */
	vc_map( array(
		"name"     => esc_html__( "Pearl Static Service", 'pearl-medical-framework' ),
		"base"     => "pearl_static_service",
		"class"    => "",
		"category" => esc_html__( "MedicalGuide Theme", 'pearl-medical-framework' ),
		"params"   => array(
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Icon", 'pearl-medical-framework' ),
				"param_name"  => "icon",
				"value"       => '',
				"description" => esc_html__( "Please provide an icon class here.", 'pearl-medical-framework' ),
			),
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Title", 'pearl-medical-framework' ),
				"param_name"  => "title",
				"value"       => '',
				"description" => esc_html__( "Please provide a service title here.", 'pearl-medical-framework' ),
				"admin_label" => true
			),
			array(
				"type"        => "textarea",
				"class"       => "",
				"heading"     => esc_html__( "Description", 'pearl-medical-framework' ),
				"param_name"  => "desc",
				"value"       => '',
				"description" => esc_html__( "Please provide service description here.", 'pearl-medical-framework' ),
				"admin_label" => true
			),
			array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => esc_html__( "Style", 'pearl-medical-framework' ),
				"param_name"  => "style",
				"value"       => array(
					'Right Text'  => 'right_text',
					'Bottom Text' => 'bottom_text'
				),
				"admin_label" => true
			),
			array(
				'type'       => 'checkbox',
				'heading'    => "Small Icon.",
				'param_name' => "icon_type",
				'value'      => "small",
				"dependency" => array(
					'element' => 'style',
					'value'   => array( 'right_text' )
				)
			),
			array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => esc_html__( "Color Scheme", 'pearl-medical-framework' ),
				"param_name"  => "color_scheme",
				"description" => esc_html__( "If you choose light color scheme then the service block text color will be dark. However, if you choose dark color scheme then the service block text color will be white.", 'pearl-medical-framework' ),
				"value"       => array(
					'Light' => 'light',
					'Dark'  => 'dark'
				),
				"dependency"  => array(
					'element' => 'icon_type',
					'value'   => array( 'true' )
				)
			),
			array(
				"type"       => "dropdown",
				"class"      => "",
				"heading"    => esc_html__( "Bottom Space", 'pearl-medical-framework' ),
				"param_name" => "bottom_space",
				"value"      => array(
					'High' => 'high-space',
					'Low'  => 'low-space'
				),
				"dependency" => array(
					'element' => 'style',
					'value'   => array( 'right_text' )
				)
			)
		)
	) );

	/**
	 * Guide Block
	 */
	vc_map( array(
		"name"     => esc_html__( "Pearl Guide Blocks", 'pearl-medical-framework' ),
		"base"     => "pearl_guide_blocks",
		"class"    => "",
		"category" => esc_html__( "MedicalGuide Theme", 'pearl-medical-framework' ),
		"params"   => array(
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "First Block Title", 'pearl-medical-framework' ),
				"param_name"  => "first_title",
				"value"       => '',
				"admin_label" => true,
				"group"       => 'First'
			),
			array(
				"type"       => "textarea",
				"class"      => "",
				"heading"    => esc_html__( "First Block Description", 'pearl-medical-framework' ),
				"param_name" => "first_desc",
				"value"      => '',
				"group"      => 'First'
			),
			array(
				"type"       => "textfield",
				"class"      => "",
				"heading"    => esc_html__( "First Block Button Text", 'pearl-medical-framework' ),
				"param_name" => "first_link_text",
				"value"      => '',
				"group"      => 'First'
			),
			array(
				"type"       => "textfield",
				"class"      => "",
				"heading"    => esc_html__( "First Block Button link", 'pearl-medical-framework' ),
				"param_name" => "first_link",
				"value"      => '',
				"group"      => 'First'
			),
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Second Block Title", 'pearl-medical-framework' ),
				"param_name"  => "second_title",
				"value"       => '',
				"admin_label" => true,
				"group"       => 'Second'
			),
			array(
				"type"       => "textarea",
				"class"      => "",
				"heading"    => esc_html__( "Second Block Description", 'pearl-medical-framework' ),
				"param_name" => "second_desc",
				"value"      => '',
				"group"      => 'Second'
			),
			array(
				"type"       => "textfield",
				"class"      => "",
				"heading"    => esc_html__( "Second Block Button Text", 'pearl-medical-framework' ),
				"param_name" => "second_link_text",
				"value"      => '',
				"group"      => 'Second'
			),
			array(
				"type"       => "textfield",
				"class"      => "",
				"heading"    => esc_html__( "Second Block Button link", 'pearl-medical-framework' ),
				"param_name" => "second_link",
				"value"      => '',
				"group"      => 'Second'
			),
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Third Block Title", 'pearl-medical-framework' ),
				"param_name"  => "third_title",
				"value"       => '',
				"admin_label" => true,
				"group"       => 'Third'
			),
			array(
				"type"       => "textarea",
				"class"      => "",
				"heading"    => esc_html__( "Third Block Description", 'pearl-medical-framework' ),
				"param_name" => "third_desc",
				"value"      => '',
				"group"      => 'Third'
			),
			array(
				"type"       => "textfield",
				"class"      => "",
				"heading"    => esc_html__( "Third Block Button Text", 'pearl-medical-framework' ),
				"param_name" => "third_link_text",
				"value"      => '',
				"group"      => 'Third'
			),
			array(
				"type"       => "textfield",
				"class"      => "",
				"heading"    => esc_html__( "Third Block Button link", 'pearl-medical-framework' ),
				"param_name" => "third_link",
				"value"      => '',
				"group"      => 'Third'
			)
		)
	) );

	/**
	 * Stats Counter
	 */
	vc_map( array(
		"name"              => esc_html__( "Pearl Stats Counter", 'pearl-medical-framework' ),
		"base"              => "pearl_stats_counter",
		"class"             => "",
		"category"          => esc_html__( "MedicalGuide Theme", 'pearl-medical-framework' ),
		'admin_enqueue_css' => array( get_template_directory_uri() . '/css/piechart-style.css' ),
		"params"            => array(
			array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => esc_html__( "Style", 'pearl-medical-framework' ),
				"param_name"  => "style",
				"value"       => array(
					'Simple' => 'simple',
					'Circle' => 'circle'
				),
				"admin_label" => true
			),
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "First Counter Title", 'pearl-medical-framework' ),
				"param_name"  => "first_count_title",
				"value"       => "",
				"admin_label" => true,
				"group"       => 'First'
			),
			array(
				"type"       => "textfield",
				"class"      => "",
				"heading"    => esc_html__( "First Counter Numbers", 'pearl-medical-framework' ),
				"param_name" => "first_count_num",
				"value"      => "",
				"group"      => 'First'
			),
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Second Counter Title", 'pearl-medical-framework' ),
				"param_name"  => "second_count_title",
				"value"       => "",
				"admin_label" => true,
				"group"       => 'Second'
			),
			array(
				"type"       => "textfield",
				"class"      => "",
				"heading"    => esc_html__( "Second Counter Numbers", 'pearl-medical-framework' ),
				"param_name" => "second_count_num",
				"value"      => "",
				"group"      => 'Second'
			),
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Third Counter Title", 'pearl-medical-framework' ),
				"param_name"  => "third_count_title",
				"value"       => "",
				"admin_label" => true,
				"group"       => 'Third'
			),
			array(
				"type"       => "textfield",
				"class"      => "",
				"heading"    => esc_html__( "Third Counter Numbers", 'pearl-medical-framework' ),
				"param_name" => "third_count_num",
				"value"      => "",
				"group"      => 'Third'
			),
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Fourth Counter Title", 'pearl-medical-framework' ),
				"param_name"  => "fourth_count_title",
				"value"       => "",
				"admin_label" => true,
				"group"       => 'Fourth'
			),
			array(
				"type"       => "textfield",
				"class"      => "",
				"heading"    => esc_html__( "Fourth Counter Numbers", 'pearl-medical-framework' ),
				"param_name" => "fourth_count_num",
				"value"      => "",
				"group"      => 'Fourth'
			),
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Fifth Counter Title", 'pearl-medical-framework' ),
				"param_name"  => "fifth_count_title",
				"value"       => "",
				"admin_label" => true,
				"group"       => 'Fifth',
				"dependency"  => array(
					'element' => 'style',
					'value'   => array( 'circle' )
				)
			),
			array(
				"type"       => "textfield",
				"class"      => "",
				"heading"    => esc_html__( "Fifth Counter Numbers", 'pearl-medical-framework' ),
				"param_name" => "fifth_count_num",
				"value"      => "",
				"group"      => 'Fifth',
				"dependency" => array(

					'element' => 'style',
					'value'   => array( 'circle' )
				)
			),
		)
	) );

	/**
	 * Doctors List
	 */
	$get_terms = get_terms( 'doctor-department', array(
		'orderby'    => 'count',
		'hide_empty' => 0
	) );

	$doctor_department = array();

	foreach ( $get_terms as $term ) {
		$doctor_department[ $term->name ] = $term->term_id;
	}

	vc_map( array(
		"name"     => esc_html__( "Pearl Doctors", 'pearl-medical-framework' ),
		"base"     => "pearl_doctors_list",
		"class"    => "",
		"category" => esc_html__( "MedicalGuide Theme", 'pearl-medical-framework' ),
		"params"   => array(
			array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => esc_html__( "Number of Doctors to display", 'pearl-medical-framework' ),
				"param_name"  => "number_of_posts",
				"value"       => array( 3, 6, 9, 12 ),
				"description" => esc_html__( "Select a number of doctors posts you want to display.", 'pearl-medical-framework' ),
				"admin_label" => true
			),
			array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => esc_html__( "Style", 'pearl-medical-framework' ),
				"param_name"  => "style",
				"value"       => array(
					'Multiple' => 'multiple',
					'Single'   => 'single'
				),
				"admin_label" => true
			),
			array(
				"type"        => "checkbox",
				"class"       => "",
				"heading"     => esc_html__( "Departments", 'pearl-medical-framework' ),
				"param_name"  => "department",
				"value"       => $doctor_department,
				"admin_label" => true
			),
		)
	) );

	/**
	 * Pricing Table
	 */
	vc_map( array(
		"name"              => esc_html__( "Pearl Pricing Table", 'pearl-medical-framework' ),
		"base"              => "pearl_pricing_table",
		"class"             => "",
		"category"          => esc_html__( "MedicalGuide Theme", 'pearl-medical-framework' ),
		'admin_enqueue_css' => array( get_template_directory_uri() . '/css/vc_extend.css' ),
		"params"            => array(
			array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => esc_html__( "Table Type", 'pearl-medical-framework' ),
				"param_name"  => "style",
				"value"       => array(
					'Default'   => 'default',
					'Highlight' => 'highlight'
				),
				"admin_label" => true,
				"group"       => 'Basic'
			),
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Heading", 'pearl-medical-framework' ),
				"param_name"  => "heading",
				"value"       => "",
				"description" => esc_html__( "Provide pricing table heading.", 'pearl-medical-framework' ),
				"admin_label" => true,
				"group"       => 'Basic'
			),
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Currency", 'pearl-medical-framework' ),
				"param_name"  => "currency",
				"value"       => "",
				"description" => esc_html__( "Provide pricing table currency e.g £ or $.", 'pearl-medical-framework' ),
				"group"       => 'Basic'

			),
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Price", 'pearl-medical-framework' ),
				"param_name"  => "price",
				"value"       => "",
				"description" => esc_html__( "Provide pricing table pricing.", 'pearl-medical-framework' ),
				"group"       => 'Basic'
			),
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Interval", 'pearl-medical-framework' ),
				"param_name"  => "interval",
				"value"       => "",
				"description" => esc_html__( "Provide pricing table package interval e.g Per Month.", 'pearl-medical-framework' ),
				"group"       => 'Basic'
			),
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Button Text", 'pearl-medical-framework' ),
				"param_name"  => "button_text",
				"value"       => "",
				"description" => esc_html__( "Provide pricing table button text e.g Sign Up.", 'pearl-medical-framework' ),
				"group"       => 'Basic'
			),
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Button URL", 'pearl-medical-framework' ),
				"param_name"  => "button_url",
				"value"       => "",
				"description" => esc_html__( "Provide pricing table button URL.", 'pearl-medical-framework' ),
				"group"       => 'Basic'
			),
			array(
				"type"       => "pearl_clone_one",
				"heading"    => esc_html__( "Pricing Table Fields", 'pearl-medical-framework' ),
				"param_name" => "table_fields",
				"group"      => 'Fields'
			),
		)
	) );

	/**
	 * FAQs Section
	 */
	vc_map( array(
		"name"     => esc_html__( "Pearl FAQs", 'pearl-medical-framework' ),
		"base"     => "pearl_faq_section",
		"class"    => "",
		"category" => esc_html__( "MedicalGuide Theme", 'pearl-medical-framework' ),
		"params"   => array(
			array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => esc_html__( "Style", 'pearl-medical-framework' ),
				"param_name"  => "style",
				"value"       => array(
					'Simple' => 'simple',
					'Icon'   => 'icon'
				),
				"admin_label" => true
			),
			array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => esc_html__( "Number of FAQs to display", 'pearl-medical-framework' ),
				"param_name"  => "number_of_posts",
				"value"       => array( 2, 4, 6, 8 ),
				"description" => esc_html__( "Select a number of faqs posts you want to display.", 'pearl-medical-framework' ),
				"admin_label" => true
			)
		)
	) );

	/**
	 * Gallery Section
	 */
	vc_map( array(
		"name"     => esc_html__( "Pearl Gallery", 'pearl-medical-framework' ),
		"base"     => "pearl_gallery_section",
		"class"    => "",
		"category" => esc_html__( "MedicalGuide Theme", 'pearl-medical-framework' ),
		"params"   => array(
			array(
				"type"        => "attach_images",
				"class"       => "",
				"heading"     => esc_html__( "Gallery Images", 'pearl-medical-framework' ),
				"param_name"  => "pearl_gallery_images",
				"value"       => "",
				"description" => esc_html__( "Upload gallery images.", 'pearl-medical-framework' )
			),
		)
	) );

	/**
	 * Pearl List
	 */
	vc_map( array(
		"name"     => esc_html__( "Pearl List", 'pearl-medical-framework' ),
		"base"     => "pearl_list_section",
		"class"    => "",
		"category" => esc_html__( "MedicalGuide Theme", 'pearl-medical-framework' ),
		"params"   => array(
			array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => esc_html__( "List Style", 'pearl-medical-framework' ),
				"param_name"  => "style",
				"value"       => array(
					'Dot'   => 'dot',
					'Check' => 'check',
					'Arrow' => 'arrow'
				),
				"admin_label" => true,
			),
			array(
				"type"       => "pearl_clone_one",
				"heading"    => esc_html__( "List Items", 'pearl-medical-framework' ),
				"param_name" => "list",
			),
			array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => esc_html__( "Layout", 'pearl-medical-framework' ),
				"param_name"  => "layout",
				"value"       => array(
					'Fullwidth' => 'fullwidth',
					'Columns'   => 'columns'
				),
				"admin_label" => true,
				"dependency"  => array(
					'element' => 'style',
					'value'   => array( 'dot' )
				)
			)
		)
	) );

	/**
	 * Testimonials List
	 */
	vc_map( array(
		"name"     => esc_html__( "Pearl Testimonials", 'pearl-medical-framework' ),
		"base"     => "pearl_testimonials_list",
		"class"    => "",
		"category" => esc_html__( "MedicalGuide Theme", 'pearl-medical-framework' ),
		"params"   => array(
			array(
				"type"        => "textfield",
				"class"       => "",
				"heading"     => esc_html__( "Heading", 'pearl-medical-framework' ),
				"param_name"  => "heading",
				"value"       => "",
				"description" => esc_html__( "Provide testimonial section heading.", 'pearl-medical-framework' ),
				"admin_label" => true
			),
			array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => esc_html__( "Number of Testimonials to display", 'pearl-medical-framework' ),
				"param_name"  => "number_of_posts",
				"value"       => array( 1, 2, 3, 4, 5, 6, 7, 8 ),
				"description" => esc_html__( "Select a number of testimonials posts you want to display.", 'pearl-medical-framework' ),
				"admin_label" => true
			),
			array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => esc_html__( "Font Color", 'pearl-medical-framework' ),
				"param_name"  => "font_color",
				"value"       => array(
					'White' => 'dark-testi',
					'Dark'  => 'dark-testimonial'
				),
				"admin_label" => true
			)
		)
	) );

	/**
	 * Posts List
	 */
	vc_map( array(
		"name"     => esc_html__( "Pearl News", 'pearl-medical-framework' ),
		"base"     => "pearl_posts_list",
		"class"    => "",
		"category" => esc_html__( "MedicalGuide Theme", 'pearl-medical-framework' ),
		"params"   => array(
			array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => esc_html__( "Number of News to display", 'pearl-medical-framework' ),
				"param_name"  => "number_of_posts",
				"value"       => array( 1, 2, 3, 4, 5, 6, 7, 8 ),
				"description" => esc_html__( "Select a number of news posts you want to display.", 'pearl-medical-framework' ),
				"admin_label" => true
			)
		)
	) );

	/**
	 * Contact Form
	 */
	vc_map( array(
		'name'     => esc_html__( 'Contact Form', 'pearl-medical-framework' ),
		'base'     => 'pearl_contact_form',
		'class'    => '',
		"category" => esc_html__( "MedicalGuide Theme", 'pearl-medical-framework' ),
		'params'   => array(
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => esc_html__( 'Target Email Address', 'pearl-medical-framework' ),
				'description' => esc_html__( 'Provide a target email address where you would like to receive the contact form requests.', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'email',
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => esc_html__( 'CC Email Address', 'pearl-medical-framework' ),
				'description' => esc_html__( 'You can add multiple comma separated cc email addresses, to get a carbon copy of contact form message.', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'cc_email',
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => esc_html__( 'BCC Email Address', 'pearl-medical-framework' ),
				'description' => esc_html__( 'You can add multiple comma separated bcc email addresses, to get a blind carbon copy of contact form message.', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'bcc_email',
			),
			array(
				'type'        => 'checkbox',
				'class'       => '',
				'heading'     => esc_html__( 'GDPR Checkbox Feature', 'pearl-medical-framework' ),
				'description' => esc_html__( 'Check this to turn on GDPR checkbox in the contact form.', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'gdpr_checkbox',
			)
		)
	) );

	/**
	 * Contact Information
	 */
	vc_map( array(
		'name'     => esc_html__( 'Contact Information', 'pearl-medical-framework' ),
		'base'     => 'pearl_contact_information',
		'class'    => '',
		"category" => esc_html__( "MedicalGuide Theme", 'pearl-medical-framework' ),
		'params'   => array(
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => esc_html__( 'Title', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'title',
			),
			array(
				'type'        => 'textarea',
				'class'       => '',
				'heading'     => esc_html__( 'Description', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'desc',
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => esc_html__( 'Phone Number', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'phone',
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => esc_html__( 'Fax Number', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'fax',
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => esc_html__( 'Email', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'email',
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => esc_html__( 'Website URL', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'site',
			),
			array(
				'type'        => 'textarea',
				'class'       => '',
				'heading'     => esc_html__( 'Address', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'address',
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => esc_html__( 'Facebook URL', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'facebook_link',
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => esc_html__( 'Twitter URL', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'twitter_link',
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => esc_html__( 'Google URL', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'google_link',
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => esc_html__( 'Vimeo URL', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'vimeo_link',
			)
		)
	) );

	/**
	 * Google Map
	 */
	vc_map( array(
		'name'     => esc_html__( 'Google Map', 'pearl-medical-framework' ),
		'base'     => 'pearl_google_map',
		'class'    => '',
		"category" => esc_html__( "MedicalGuide Theme", 'pearl-medical-framework' ),
		'params'   => array(
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => esc_html__( 'Google Map Latitude', 'pearl-medical-framework' ),
				'description' => sprintf( esc_html__( 'You can use %s OR %s to get Latitude and longitude of your desired location.', 'pearl-medical-framework' ), '<a href="https://getlatlong.net" target="_blank">https://getlatlong.net</a>', '<a href="https://www.latlong.net" target="_blank">https://www.latlong.net</a>' ),
				"admin_label" => true,
				'param_name'  => 'lat',
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => esc_html__( 'Google Map Longitude', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'lng',
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => esc_html__( 'Google Map Zoom Level', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'zoom',
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => esc_html__( 'Map Direction Address', 'pearl-medical-framework' ),
				"admin_label" => true,
				'param_name'  => 'direction',
			)
		)
	) );

	/**
	 * Update Visual Composer Row
	 */
	$attributes = array(
		'type'        => 'checkbox',
		'heading'     => "Enable Container?",
		'param_name'  => "container",
		'value'       => "yes",
		'description' => esc_html__( "Enable container if you want to display boxed layout for your contents.", "pearl-medical-framework" )
	);
	vc_add_param( 'vc_row', $attributes );
}

require_once( plugin_dir_path( __FILE__ ) . '/shortcodes/custom/custom_param.php' );