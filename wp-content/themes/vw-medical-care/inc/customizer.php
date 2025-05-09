<?php
/**
 * VW Medical Care Theme Customizer
 *
 * @package VW Medical Care
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function vw_medical_care_custom_controls() {

    load_template( trailingslashit( get_template_directory() ) . '/inc/custom-controls.php' );
}
add_action( 'customize_register', 'vw_medical_care_custom_controls' );

function vw_medical_care_customize_register( $wp_customize ) {

	load_template( trailingslashit( get_template_directory() ) . 'inc/customize-homepage/class-customize-homepage.php' );

	//add home page setting pannel
	$wp_customize->add_panel( 'vw_medical_care_panel_id', array(
	    'priority' => 10,
	    'capability' => 'edit_theme_options',
	    'theme_supports' => '',
	    'title' => __( 'VW Settings', 'vw-medical-care' ),
	) );

	// Layout
	$wp_customize->add_section( 'vw_medical_care_left_right', array(
    	'title'      => __( 'General Settings', 'vw-medical-care' ),
		'panel' => 'vw_medical_care_panel_id'
	) );

	$wp_customize->add_setting('vw_medical_care_width_option',array(
        'default' => __('Full Width','vw-medical-care'),
        'sanitize_callback' => 'vw_medical_care_sanitize_choices'
	));
	$wp_customize->add_control(new VW_Medical_Care_Image_Radio_Control($wp_customize, 'vw_medical_care_width_option', array(
        'type' => 'select',
        'label' => __('Width Layouts','vw-medical-care'),
        'description' => __('Here you can change the width layout of Website.','vw-medical-care'),
        'section' => 'vw_medical_care_left_right',
        'choices' => array(
            'Full Width' => get_template_directory_uri().'/assets/images/full-width.png',
            'Wide Width' => get_template_directory_uri().'/assets/images/wide-width.png',
            'Boxed' => get_template_directory_uri().'/assets/images/boxed-width.png',
    ))));

	// Add Settings and Controls for Layout
	$wp_customize->add_setting('vw_medical_care_theme_options',array(
        'default' => __('Right Sidebar','vw-medical-care'),
        'sanitize_callback' => 'vw_medical_care_sanitize_choices'	        
	) );
	$wp_customize->add_control('vw_medical_care_theme_options', array(
        'type' => 'select',
        'label' => __('Post Sidebar Layout','vw-medical-care'),
        'description' => __('Here you can change the sidebar layout for posts. ','vw-medical-care'),
        'section' => 'vw_medical_care_left_right',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','vw-medical-care'),
            'Right Sidebar' => __('Right Sidebar','vw-medical-care'),
            'One Column' => __('One Column','vw-medical-care'),
            'Three Columns' => __('Three Columns','vw-medical-care'),
            'Four Columns' => __('Four Columns','vw-medical-care'),
            'Grid Layout' => __('Grid Layout','vw-medical-care')
        ),
	));

	$wp_customize->add_setting('vw_medical_care_page_layout',array(
        'default' => __('One Column','vw-medical-care'),
        'sanitize_callback' => 'vw_medical_care_sanitize_choices'
	));
	$wp_customize->add_control('vw_medical_care_page_layout',array(
        'type' => 'select',
        'label' => __('Page Sidebar Layout','vw-medical-care'),
        'description' => __('Here you can change the sidebar layout for pages. ','vw-medical-care'),
        'section' => 'vw_medical_care_left_right',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','vw-medical-care'),
            'Right Sidebar' => __('Right Sidebar','vw-medical-care'),
            'One Column' => __('One Column','vw-medical-care')
        ),
	) );

	//Topbar
	$wp_customize->add_section( 'vw_medical_care_topbar', array(
    	'title'      => __( 'Topbar Settings', 'vw-medical-care' ),
		'panel' => 'vw_medical_care_panel_id'
	) );

	$wp_customize->add_setting('vw_medical_care_header_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_medical_care_header_text',array(
		'label'	=> __('Add Text','vw-medical-care'),
		'input_attrs' => array(
            'placeholder' => __( 'Do you have any question?', 'vw-medical-care' ),
        ),
		'section'=> 'vw_medical_care_topbar',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'vw_medical_care_header_search',
       array(
          'default' => 1,
          'transport' => 'refresh',
          'sanitize_callback' => 'vw_medical_care_switch_sanitization'
    ));  
    $wp_customize->add_control( new VW_Medical_Care_Toggle_Switch_Custom_Control( $wp_customize, 'vw_medical_care_header_search',
       array(
          'label' => esc_html__( 'Show / Hide Search','vw-medical-care' ),
          'section' => 'vw_medical_care_topbar'
    )));
    
	//Slider
	$wp_customize->add_section( 'vw_medical_care_slidersettings' , array(
    	'title'      => __( 'Slider Section', 'vw-medical-care' ),
		'panel' => 'vw_medical_care_panel_id'
	) );

	$wp_customize->add_setting( 'vw_medical_care_slider_hide_show',
       array(
          'default' => 1,
          'transport' => 'refresh',
          'sanitize_callback' => 'vw_medical_care_switch_sanitization'
    ));  
    $wp_customize->add_control( new VW_Medical_Care_Toggle_Switch_Custom_Control( $wp_customize, 'vw_medical_care_slider_hide_show',
       array(
          'label' => esc_html__( 'Show / Hide Slider','vw-medical-care' ),
          'section' => 'vw_medical_care_slidersettings'
    )));

	for ( $count = 1; $count <= 4; $count++ ) {

		$wp_customize->add_setting( 'vw_medical_care_slider_page' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'vw_medical_care_sanitize_dropdown_pages'
		) );
		$wp_customize->add_control( 'vw_medical_care_slider_page' . $count, array(
			'label'    => __( 'Select Slider Page', 'vw-medical-care' ),
			'description' => __('Slider image size (1500 x 590)','vw-medical-care'),
			'section'  => 'vw_medical_care_slidersettings',
			'type'     => 'dropdown-pages'
		) );
	}

	//content layout
	$wp_customize->add_setting('vw_medical_care_slider_content_option',array(
        'default' => __('Center','vw-medical-care'),
        'sanitize_callback' => 'vw_medical_care_sanitize_choices'
	));
	$wp_customize->add_control(new VW_Medical_Care_Image_Radio_Control($wp_customize, 'vw_medical_care_slider_content_option', array(
        'type' => 'select',
        'label' => __('Slider Content Layouts','vw-medical-care'),
        'section' => 'vw_medical_care_slidersettings',
        'choices' => array(
            'Left' => get_template_directory_uri().'/assets/images/slider-content1.png',
            'Center' => get_template_directory_uri().'/assets/images/slider-content2.png',
            'Right' => get_template_directory_uri().'/assets/images/slider-content3.png',
    ))));

    //Slider excerpt
	$wp_customize->add_setting( 'vw_medical_care_slider_excerpt_number', array(
		'default'              => 30,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'absint',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'vw_medical_care_slider_excerpt_number', array(
		'label'       => esc_html__( 'Slider Excerpt length','vw-medical-care' ),
		'section'     => 'vw_medical_care_slidersettings',
		'type'        => 'range',
		'settings'    => 'vw_medical_care_slider_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	//Opacity
	$wp_customize->add_setting('vw_medical_care_slider_opacity_color',array(
      'default'              => 0.5,
      'sanitize_callback' => 'vw_medical_care_sanitize_choices'
	));

	$wp_customize->add_control( 'vw_medical_care_slider_opacity_color', array(
	'label'       => esc_html__( 'Slider Image Opacity','vw-medical-care' ),
	'section'     => 'vw_medical_care_slidersettings',
	'type'        => 'select',
	'settings'    => 'vw_medical_care_slider_opacity_color',
	'choices' => array(
      '0' =>  esc_attr('0','vw-medical-care'),
      '0.1' =>  esc_attr('0.1','vw-medical-care'),
      '0.2' =>  esc_attr('0.2','vw-medical-care'),
      '0.3' =>  esc_attr('0.3','vw-medical-care'),
      '0.4' =>  esc_attr('0.4','vw-medical-care'),
      '0.5' =>  esc_attr('0.5','vw-medical-care'),
      '0.6' =>  esc_attr('0.6','vw-medical-care'),
      '0.7' =>  esc_attr('0.7','vw-medical-care'),
      '0.8' =>  esc_attr('0.8','vw-medical-care'),
      '0.9' =>  esc_attr('0.9','vw-medical-care')
	),
	));

	//Contact us
	$wp_customize->add_section( 'vw_medical_care_contact', array(
    	'title'      => __( 'Contact us', 'vw-medical-care' ),
		'panel' => 'vw_medical_care_panel_id'
	) );

	$wp_customize->add_setting('vw_medical_care_call_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_medical_care_call_text',array(
		'label'	=> __('Add Call Text','vw-medical-care'),
		'input_attrs' => array(
            'placeholder' => __( 'Phone No.', 'vw-medical-care' ),
        ),
		'section'=> 'vw_medical_care_contact',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_medical_care_call',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_medical_care_call',array(
		'label'	=> __('Add Phone No.','vw-medical-care'),
		'input_attrs' => array(
            'placeholder' => __( '+00 987 654 1230', 'vw-medical-care' ),
        ),
		'section'=> 'vw_medical_care_contact',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_medical_care_address_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_medical_care_address_text',array(
		'label'	=> __('Add Location Text','vw-medical-care'),
		'input_attrs' => array(
            'placeholder' => __( 'Hospital Address', 'vw-medical-care' ),
        ),
		'section'=> 'vw_medical_care_contact',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_medical_care_address',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_medical_care_address',array(
		'label'	=> __('Add Location','vw-medical-care'),
		'input_attrs' => array(
            'placeholder' => __( '123 dummy street opp to dummy appartment, DUMMY', 'vw-medical-care' ),
        ),
		'section'=> 'vw_medical_care_contact',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_medical_care_email_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_medical_care_email_text',array(
		'label'	=> __('Add Email Text','vw-medical-care'),
		'input_attrs' => array(
            'placeholder' => __( 'Email Address', 'vw-medical-care' ),
        ),
		'section'=> 'vw_medical_care_contact',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_medical_care_email',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_medical_care_email',array(
		'label'	=> __('Add Email','vw-medical-care'),
		'input_attrs' => array(
            'placeholder' => __( 'example@gmail.com', 'vw-medical-care' ),
        ),
		'section'=> 'vw_medical_care_contact',
		'type'=> 'text'
	));
    
	//Facilities section
	$wp_customize->add_section( 'vw_medical_care_facilities_section' , array(
    	'title'      => __( 'Our Facilities Section', 'vw-medical-care' ),
		'priority'   => null,
		'panel' => 'vw_medical_care_panel_id'
	) );

	$categories = get_categories();
	$cat_post = array();
	$cat_post[]= 'select';
	$i = 0;	
	foreach($categories as $category){
		if($i==0){
			$default = $category->slug;
			$i++;
		}
		$cat_post[$category->slug] = $category->name;
	}

	$wp_customize->add_setting('vw_medical_care_facilities',array(
		'default'	=> 'select',
		'sanitize_callback' => 'vw_medical_care_sanitize_choices',
	));
	$wp_customize->add_control('vw_medical_care_facilities',array(
		'type'    => 'select',
		'choices' => $cat_post,
		'label' => __('Select Category to display facilities','vw-medical-care'),
		'description' => __('Image Size (250 x 250)','vw-medical-care'),
		'section' => 'vw_medical_care_facilities_section',
	));

	//Facilities excerpt
	$wp_customize->add_setting( 'vw_medical_care_facilities_excerpt_number', array(
		'default'              => 30,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'absint',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'vw_medical_care_facilities_excerpt_number', array(
		'label'       => esc_html__( 'Facilities Excerpt length','vw-medical-care' ),
		'section'     => 'vw_medical_care_facilities_section',
		'type'        => 'range',
		'settings'    => 'vw_medical_care_facilities_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	//Blog Post
	$wp_customize->add_section('vw_medical_care_blog_post',array(
		'title'	=> __('Blog Post Settings','vw-medical-care'),
		'panel' => 'vw_medical_care_panel_id',
	));	

	$wp_customize->add_setting( 'vw_medical_care_toggle_postdate',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_medical_care_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Medical_Care_Toggle_Switch_Custom_Control( $wp_customize, 'vw_medical_care_toggle_postdate',array(
        'label' => esc_html__( 'Post Date','vw-medical-care' ),
        'section' => 'vw_medical_care_blog_post'
    )));

    $wp_customize->add_setting( 'vw_medical_care_toggle_author',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_medical_care_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Medical_Care_Toggle_Switch_Custom_Control( $wp_customize, 'vw_medical_care_toggle_author',array(
		'label' => esc_html__( 'Author','vw-medical-care' ),
		'section' => 'vw_medical_care_blog_post'
    )));

    $wp_customize->add_setting( 'vw_medical_care_toggle_comments',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_medical_care_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Medical_Care_Toggle_Switch_Custom_Control( $wp_customize, 'vw_medical_care_toggle_comments',array(
		'label' => esc_html__( 'Comments','vw-medical-care' ),
		'section' => 'vw_medical_care_blog_post'
    )));

    $wp_customize->add_setting( 'vw_medical_care_excerpt_number', array(
		'default'              => 30,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'absint',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'vw_medical_care_excerpt_number', array(
		'label'       => esc_html__( 'Excerpt length','vw-medical-care' ),
		'section'     => 'vw_medical_care_blog_post',
		'type'        => 'range',
		'settings'    => 'vw_medical_care_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	//Content Craetion
	$wp_customize->add_section( 'vw_medical_care_content_section' , array(
    	'title' => __( 'Customize Home Page', 'vw-medical-care' ),
		'priority' => null,
		'panel' => 'vw_medical_care_panel_id'
	) );

	$wp_customize->add_setting('vw_medical_care_content_creation_main_control', array(
		'sanitize_callback' => 'esc_html',
	) );

	$homepage= get_option( 'page_on_front' );

	$wp_customize->add_control(	new VW_Medical_Care_Content_Creation( $wp_customize, 'vw_medical_care_content_creation_main_control', array(
		'options' => array(
			esc_html__( 'First select static page in homepage setting for front page.Below given edit button is to customize Home Page. Just click on the edit option, add whatever elements you want to include in the homepage, save the changes and you are good to go.','vw-medical-care' ),
		),
		'section' => 'vw_medical_care_content_section',
		'button_url'  => admin_url( 'post.php?post='.$homepage.'&action=edit'),
		'button_text' => esc_html__( 'Edit', 'vw-medical-care' ),
	) ) );

	//Footer Text
	$wp_customize->add_section('vw_medical_care_footer',array(
		'title'	=> __('Footer','vw-medical-care'),
		'panel' => 'vw_medical_care_panel_id',
	));	
	
	$wp_customize->add_setting('vw_medical_care_footer_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('vw_medical_care_footer_text',array(
		'label'	=> __('Copyright Text','vw-medical-care'),
		'input_attrs' => array(
            'placeholder' => __( 'Copyright 2019, .....', 'vw-medical-care' ),
        ),
		'section'=> 'vw_medical_care_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'vw_medical_care_hide_show_scroll',array(
    	'default' => 1,
      	'transport' => 'refresh',
      	'sanitize_callback' => 'vw_medical_care_switch_sanitization'
    ));  
    $wp_customize->add_control( new VW_Medical_Care_Toggle_Switch_Custom_Control( $wp_customize, 'vw_medical_care_hide_show_scroll',array(
      	'label' => esc_html__( 'Show / Hide Scroll To Top','vw-medical-care' ),
      	'section' => 'vw_medical_care_footer'
    )));

	$wp_customize->add_setting('vw_medical_care_scroll_top_alignment',array(
        'default' => __('Right','vw-medical-care'),
        'sanitize_callback' => 'vw_medical_care_sanitize_choices'
	));
	$wp_customize->add_control(new VW_Medical_Care_Image_Radio_Control($wp_customize, 'vw_medical_care_scroll_top_alignment', array(
        'type' => 'select',
        'label' => __('Scroll To Top','vw-medical-care'),
        'section' => 'vw_medical_care_footer',
        'settings' => 'vw_medical_care_scroll_top_alignment',
        'choices' => array(
            'Left' => get_template_directory_uri().'/assets/images/layout1.png',
            'Center' => get_template_directory_uri().'/assets/images/layout2.png',
            'Right' => get_template_directory_uri().'/assets/images/layout3.png'
    ))));	
}

add_action( 'customize_register', 'vw_medical_care_customize_register' );

load_template( trailingslashit( get_template_directory() ) . '/inc/logo/logo-resizer.php' );

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class VW_Medical_Care_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	 */
	public function sections( $manager ) {

		// Load custom sections.
		load_template( trailingslashit( get_template_directory() ) . '/inc/section-pro.php' );

		// Register custom section types.
		$manager->register_section_type( 'VW_Medical_Care_Customize_Section_Pro' );

		// Register sections.
		$manager->add_section(new VW_Medical_Care_Customize_Section_Pro($manager,'example_1',array(
				'priority'   => 1,
				'title'    => esc_html__( 'VW Medical Care', 'vw-medical-care' ),
				'pro_text' => esc_html__( 'UPGRADE PRO', 'vw-medical-care' ),
				'pro_url'  => esc_url('https://www.vwthemes.com/themes/medical-wordpress-theme/'),
			)));

		// Register sections.
		$manager->add_section(new VW_Medical_Care_Customize_Section_Pro($manager,'example_2',array(
				'priority'   => 1,
				'title'    => esc_html__( 'DOCUMENTATION', 'vw-medical-care' ),
				'pro_text' => esc_html__( 'DOCS', 'vw-medical-care' ),
				'pro_url'  => admin_url('themes.php?page=vw_medical_care_guide'),
			)));
	}
	

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'vw-medical-care-customize-controls', trailingslashit( get_template_directory_uri() ) . '/assets/js/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'vw-medical-care-customize-controls', trailingslashit( get_template_directory_uri() ) . '/assets/css/customize-controls.css' );
	}
}

// Doing this customizer thang!
VW_Medical_Care_Customize::get_instance();