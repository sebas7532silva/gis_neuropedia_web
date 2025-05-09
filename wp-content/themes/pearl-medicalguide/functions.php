<?php
/**
 *
 *    Medical Guide Theme
 *
 * @author PearlThemes <hello@pearlthemes.com>
 * @version 1.3.1
 * @link https://pearlthemes.com
 *
 */

/**
 * The current version of the theme.
 */
define( 'PEARL_THEME_VERSION', '1.3.3' );
define( 'PEARL_THEME_DIRECTORY', get_template_directory() );
define( 'PEARL_THEME_DIRECTORY_URI', get_template_directory_uri() );


/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since 1.0.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 710;
}

/**
 *  TGM plugin activation
 */
require_once( PEARL_THEME_DIRECTORY . '/inc/tgm/config.php' );

/**
 * Customizer
 */
require_once( get_template_directory() . '/inc/customizer/customizer.php' );

/**
 * MetaBoxes
 */
require_once( get_template_directory() . '/inc/meta-box/pearl-meta-box.php' );

/**
 * Widgets
 */
include_once( get_template_directory() . '/inc/widgets/contact.php' );
include_once( get_template_directory() . '/inc/widgets/newsletter.php' );
include_once( get_template_directory() . '/inc/widgets/recent-posts.php' );


if ( ! function_exists( 'pearl_theme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @since 1.0.0
	 */
	function pearl_theme_setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'pearl-medicalguide', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		// Add custom background support to the site.
		add_theme_support( 'custom-background' );

		/*
		 * Used on:
		 * Single post page and Post archive pages
		 * Single property page
		 * Gallery pages
		 */
		set_post_thumbnail_size( 1534, 792, true );

		/*
		 * Image Sizes
		 */
		add_image_size( 'pearl_image_size_270_270', 270, 270, true ); // testimonials posts
		add_image_size( 'pearl_image_size_762_700', 762, 700, true ); // doctor, services, gallery (2,3 and 4 columns layouts)
		add_image_size( 'pearl_image_size_712_446', 712, 446, true ); // blog posts shortcode

		/*
		 * Theme theme uses wp_nav_menu in one location.
		 */
		register_nav_menus( array(
			'main-menu' => esc_html__( 'Header Menu', 'pearl-medicalguide' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		) );

		/*
		 * Add WooCommerce support for the theme
		 */
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );


	}

	add_action( 'after_setup_theme', 'pearl_theme_setup' );

endif; // pearl_theme_setup


if ( ! function_exists( 'pearl_theme_sidebars' ) ) :
	/**
	 * Register theme sidebars
	 *
	 * @since 1.0.0
	 */
	function pearl_theme_sidebars() {

		// Location: Default Sidebar
		register_sidebar( array(
			'name'          => esc_html__( 'Default Sidebar', 'pearl-medicalguide' ),
			'id'            => 'default-sidebar',
			'description'   => esc_html__( 'Widget area for blog and single pages.', 'pearl-medicalguide' ),
			'before_widget' => '<section id="%1$s" class="widget clearfix %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<div class="title"><h5>',
			'after_title'   => '</h5></div>',
		) );

		// Location: Pages Sidebar
		register_sidebar( array(
			'name'          => esc_html__( 'Pages Sidebar', 'pearl-medicalguide' ),
			'id'            => 'page-sidebar',
			'description'   => esc_html__( 'Widget area for default pages sidebar.', 'pearl-medicalguide' ),
			'before_widget' => '<section id="%1$s" class="widget clearfix %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<div class="title"><h5>',
			'after_title'   => '</h5></div>',
		) );

		// Location: Shop Sidebar
		register_sidebar( array(
			'name'          => esc_html__( 'Shop Sidebar', 'pearl-medicalguide' ),
			'id'            => 'shop-sidebar',
			'description'   => esc_html__( 'Widget area for shop pages sidebar.', 'pearl-medicalguide' ),
			'before_widget' => '<section id="%1$s" class="widget clearfix %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<div class="title"><h5>',
			'after_title'   => '</h5></div>',
		) );

		// Location: Footer First Column
		register_sidebar( array(
			'name'          => esc_html__( 'Footer First Column', 'pearl-medicalguide' ),
			'id'            => 'footer-first-column',
			'description'   => esc_html__( 'Widget area for first column in footer.', 'pearl-medicalguide' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="title"><h5>',
			'after_title'   => '</h5></div>',
		) );

		// Location: Footer Second Column
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Second Column', 'pearl-medicalguide' ),
			'id'            => 'footer-second-column',
			'description'   => esc_html__( 'Widget area for second column in footer.', 'pearl-medicalguide' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="title"><h5>',
			'after_title'   => '</h5></div>',
		) );

		// Location: Footer Third Column
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Third Column', 'pearl-medicalguide' ),
			'id'            => 'footer-third-column',
			'description'   => esc_html__( 'Widget area for third column in footer.', 'pearl-medicalguide' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="title"><h5>',
			'after_title'   => '</h5></div>',
		) );

		// Location: Footer Fourth Column
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Fourth Column', 'pearl-medicalguide' ),
			'id'            => 'footer-fourth-column',
			'description'   => esc_html__( 'Widget area for fourth column in footer.', 'pearl-medicalguide' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="title"><h5>',
			'after_title'   => '</h5></div>',
		) );

	}

	add_action( 'widgets_init', 'pearl_theme_sidebars' );

endif;

if ( ! function_exists( 'pearl_fonts_url' ) ) {
	/**
	 * Build Google Fonts URL
	 *
	 * @since 1.0.0
	 */
	function pearl_fonts_url() {

		$fonts_url = '';

		/* Translators: If there are characters in your language that are not
		* supported by Raleway, translate this to 'off'. Do not translate
		* into your own language.
		*/
		$raleway = _x( 'on', 'Raleway font: on or off', 'pearl-medicalguide' );

		/* Translators: If there are characters in your language that are not
		* supported by Sans Pro, translate this to 'off'. Do not translate
		* into your own language.
		*/
		$sans_pro = _x( 'on', 'Sans Pro font: on or off', 'pearl-medicalguide' );

		/* Translators: If there are characters in your language that are not
		* supported by Droid Serif, translate this to 'off'. Do not translate
		* into your own language.
		*/
		$droid_serif = _x( 'on', 'Droid Serif font: on or off', 'pearl-medicalguide' );

		if ( 'off' !== $raleway || 'off' !== $sans_pro || 'off' !== $droid_serif ) {
			$font_families = array();

			if ( 'off' !== $raleway ) {
				$font_families[] = 'Raleway:500,600,700,800,900,400,300';
			}

			if ( 'off' !== $sans_pro ) {
				$font_families[] = 'Source Sans Pro:300,400,600,700';
			}

			if ( 'off' !== $droid_serif ) {
				$font_families[] = 'Droid Serif:400,400italic';
			}

			$query_args = array(
				'family' => urlencode( implode( '|', $font_families ) ),
				'subset' => urlencode( 'latin,latin-ext' ),
			);

			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
		}

		return esc_url_raw( $fonts_url );
	}
}

if ( ! function_exists( 'pearl_enqueue_styles' ) ) :
	/**
	 * Enqueue required styles for front end
	 * @since   1.0.0
	 * @return  void
	 */
	function pearl_enqueue_styles() {

		if ( ! is_admin() ) :

            // google fonts
            wp_enqueue_style( 'pearl-medicalguide-fonts', pearl_fonts_url(), array(), null );

            if( 'true' === get_option('pearl_optimise_styles','true') ){

                // common styles
                wp_enqueue_style(
                    'pearl-common-styles',
                    PEARL_THEME_DIRECTORY_URI . '/css/pearl-common-styles.min.css',
                    array(),
                    PEARL_THEME_VERSION
                );

            }else{

                //main-styles
                wp_enqueue_style(
                    'main-styles',
                    PEARL_THEME_DIRECTORY_URI . '/css/main-styles.css',
                    array(),
                    PEARL_THEME_VERSION
                );

                // main file
                wp_enqueue_style(
                    'medical-guide',
                    PEARL_THEME_DIRECTORY_URI . '/css/medical-guide.css',
                    array(),
                    PEARL_THEME_VERSION
                );

                // bootstrap
                wp_enqueue_style(
                    'bootstrap',
                    PEARL_THEME_DIRECTORY_URI . '/css/bootstrap.css',
                    array(),
                    '3.3.5'
                );

                // medical guide icons
                wp_enqueue_style(
                    'medical-guide-icons',
                    PEARL_THEME_DIRECTORY_URI . '/css/medical-guide-icons.css',
                    array(),
                    PEARL_THEME_VERSION
                );

                // dropmenu
                wp_enqueue_style(
                    'dropmenu',
                    PEARL_THEME_DIRECTORY_URI . '/css/dropmenu.css',
                    array(),
                    PEARL_THEME_VERSION
                );

                // pie chart
                wp_enqueue_style(
                    'piechart',
                    PEARL_THEME_DIRECTORY_URI . '/css/piechart-style.css',
                    array(),
                    PEARL_THEME_VERSION
                );

                global $pearl_options;
                // if sticky header is enabled
                if ( $pearl_options['sticky_header'] == '1' ) :

                    wp_enqueue_style(
                        'sticky-header',
                        PEARL_THEME_DIRECTORY_URI . '/css/sticky-header.css',
                        array(),
                        PEARL_THEME_VERSION
                    );

                endif;

                // accordion
                wp_enqueue_style(
                    'accordion',
                    PEARL_THEME_DIRECTORY_URI . '/css/accordion.css',
                    array(),
                    PEARL_THEME_VERSION
                );

                // tabs
                wp_enqueue_style(
                    'tabs',
                    PEARL_THEME_DIRECTORY_URI . '/css/tabs.css',
                    array(),
                    '6.5'
                );

                // owl carousel
                wp_enqueue_style(
                    'owl-carousel',
                    PEARL_THEME_DIRECTORY_URI . '/css/owl.carousel.css',
                    array(),
                    '1.3.3'
                );

                // mobile menu
                wp_enqueue_style(
                    'mobile-menu',
                    PEARL_THEME_DIRECTORY_URI . '/css/jquery.mmenu.all.css',
                    array(),
                    PEARL_THEME_VERSION
                );

                // preLoader
                wp_enqueue_style(
                    'preLoader',
                    PEARL_THEME_DIRECTORY_URI . '/css/loader.css',
                    array(),
                    PEARL_THEME_VERSION
                );

                if ( is_page_template( 'page-templates/gallery-2-column.php' )
                    || is_page_template( 'page-templates/gallery-3-column.php' )
                    || is_page_template( 'page-templates/gallery-4-column.php' )
                    || is_page_template( 'page-templates/doctor-2-column.php' )
                    || is_page_template( 'page-templates/doctor-3-column.php' )
                    || is_page_template( 'page-templates/doctor-4-column.php' )
                    || is_tax( 'doctor-department' )
                ) {
                    // cube portfolio
                    wp_enqueue_style(
                        'cube-portfolio',
                        PEARL_THEME_DIRECTORY_URI . '/css/cubeportfolio.min.css',
                        array(),
                        '2.3.4'
                    );
                }

                if ( is_page_template( 'page-templates/gallery-2-column.php' )
                    || is_page_template( 'page-templates/gallery-3-column.php' )
                    || is_page_template( 'page-templates/gallery-4-column.php' )
                ) {
                    // fancy box
                    wp_enqueue_style(
                        'fancy-box',
                        PEARL_THEME_DIRECTORY_URI . '/css/jquery.fancybox.css',
                        array(),
                        '2.1.5'
                    );
                }

            }

			// design switcher styles
			$design_switcher = get_option( 'pearl_design_switcher', 'no' );
			if ( 'yes' == $design_switcher ) {
				wp_enqueue_style(
					'switcher',
					PEARL_THEME_DIRECTORY_URI . '/switcher/switcher.css',
					array(),
					PEARL_THEME_VERSION
				);
			}

			// theme default color
			$styles_file = get_option( 'pearl_color_scheme' );
			if ( empty( $styles_file ) ) {
				$styles_file = 'default-color';
			}
			wp_enqueue_style(
				'color',
				PEARL_THEME_DIRECTORY_URI . '/css/theme-colors/' . $styles_file . '.css',
				array(),
				PEARL_THEME_VERSION
			);

			// Register Default and Custom Styles
			wp_register_style(
				'parent-default',
				get_stylesheet_uri(),
				array(),
				PEARL_THEME_VERSION,
				'all'
			);

			// default css.
			wp_enqueue_style( 'parent-default' );

			// custom
			wp_enqueue_style(
				'pearl-custom',
				PEARL_THEME_DIRECTORY_URI . '/css/custom.css',
				array(),
				PEARL_THEME_VERSION
			);

		endif;
	}

endif; // pearl_enqueue_styles

add_action( 'wp_enqueue_scripts', 'pearl_enqueue_styles' );

if ( ! function_exists( 'pearl_enqueue_scripts' ) ) :
	/**
	 * Enqueue required java scripts for front end
	 * @since   1.0.0
	 * @return  void
	 */
	function pearl_enqueue_scripts()
    {

        if ( ! is_admin() ) {

            if( 'true' === get_option('pearl_optimise_scripts','true') ){

                // common scripts
                wp_enqueue_script(
                    'pearl-common-scripts.js',
                    PEARL_THEME_DIRECTORY_URI . '/js/pearl-common-scripts.min.js"',
                    array('jquery'),
                    PEARL_THEME_VERSION,
                    true
                );

            }else{

                // date picker and input hover over
                wp_enqueue_script(
                    'classie',
                    PEARL_THEME_DIRECTORY_URI . '/js/classie.js',
                    array('jquery'),
                    PEARL_THEME_VERSION,
                    true
                );

                wp_enqueue_script(
                    'jquery-ui-custom',
                    PEARL_THEME_DIRECTORY_URI . '/js/jquery-ui-1.10.3.custom.js',
                    array('jquery'),
                    PEARL_THEME_VERSION,
                    true
                );

                // counter
                wp_enqueue_script(
                    'counter',
                    PEARL_THEME_DIRECTORY_URI . '/js/counter.js',
                    array('jquery'),
                    PEARL_THEME_VERSION,
                    true
                );

                // pie chart
                wp_enqueue_script(
                    'piechart',
                    PEARL_THEME_DIRECTORY_URI . '/js/piechart.min.js',
                    array('jquery'),
                    PEARL_THEME_VERSION,
                    true
                );

                // tabs
                wp_enqueue_script(
                    'tabs',
                    PEARL_THEME_DIRECTORY_URI . '/js/tabs.js',
                    array('jquery'),
                    PEARL_THEME_VERSION,
                    true
                );

                // owl carousel
                wp_enqueue_script(
                    'owl-carousel',
                    PEARL_THEME_DIRECTORY_URI . '/js/owl.carousel.js',
                    array('jquery'),
                    PEARL_THEME_VERSION,
                    true
                );

                // mobile menu
                wp_enqueue_script(
                    'mobile-menu',
                    PEARL_THEME_DIRECTORY_URI . '/js/jquery.mmenu.min.all.js',
                    array('jquery'),
                    PEARL_THEME_VERSION,
                    true
                );

                if (is_page_template('page-templates/gallery-2-column.php')
                    || is_page_template('page-templates/gallery-3-column.php')
                    || is_page_template('page-templates/gallery-4-column.php')
                    || is_page_template('page-templates/doctor-2-column.php')
                    || is_page_template('page-templates/doctor-3-column.php')
                    || is_page_template('page-templates/doctor-4-column.php')
                    || is_tax('doctor-department')
                ) {

                    // cube portfolio
                    wp_enqueue_script(
                        'cube-portfolio',
                        PEARL_THEME_DIRECTORY_URI . '/js/jquery.cubeportfolio.min.js',
                        array('jquery'),
                        '2.3.4',
                        true
                    );
                }

                if (is_page_template('page-templates/gallery-2-column.php')
                    || is_page_template('page-templates/gallery-3-column.php')
                    || is_page_template('page-templates/gallery-4-column.php')
                ) {

                    // fancy box
                    wp_enqueue_script(
                        'fancy-box',
                        PEARL_THEME_DIRECTORY_URI . '/js/jquery.fancybox.js',
                        array('jquery'),
                        '2.1.5',
                        true
                    );

                    // fancy box media
                    wp_enqueue_script(
                        'fancy-box-media',
                        PEARL_THEME_DIRECTORY_URI . '/js/jquery.fancybox-media.js',
                        array('jquery'),
                        '1.0.6',
                        true
                    );

                }

                // validate
                wp_enqueue_script(
                    'validate',
                    PEARL_THEME_DIRECTORY_URI . '/js/jquery.validate.min.js',
                    array('jquery'),
                    '1.13.1',
                    true
                );

                // form
                wp_enqueue_script(
                    'form',
                    PEARL_THEME_DIRECTORY_URI . '/js/jquery.form.js',
                    array('jquery'),
                    '3.51.0',
                    true
                );
            }

            if (!wp_script_is('select2')) {
                wp_enqueue_script('select2');
            }

            // Comment reply script
            if (is_singular() && comments_open() && get_option('thread_comments')) {
                wp_enqueue_script('comment-reply');
            }

            // main
            wp_enqueue_script(
                'main',
                PEARL_THEME_DIRECTORY_URI . '/js/main.js"',
                array('jquery'),
                PEARL_THEME_VERSION,
                true
            );

            /**
             * Wp_localization_script
             * For Mobile Navigation Header Footer
             * Customizer
             */
            wp_enqueue_script(
                'custom',
                PEARL_THEME_DIRECTORY_URI . '/js/custom.js',
                array('jquery'),
                PEARL_THEME_VERSION,
                true
            );

            $header = get_option('pearl_menu_header_text');
            $footer = get_option('pearl_menu_footer_text');
            $data =
                array(
                    'mheader' => $header,
                    'mfooter' => $footer,
                );
            wp_localize_script(
                'custom',
                'menuCustom',
                $data
            );


            $google_map_arguments = array();
            $map_api_key = get_option('pearl_map_api_key');

            // Get Google Map API Key if available
            if (isset($map_api_key) && !empty($map_api_key)) {
                $google_map_arguments['key'] = urlencode($map_api_key);
                $google_map_api_uri = add_query_arg(apply_filters('pearl_google_map_arguments', $google_map_arguments), '//maps.google.com/maps/api/js');

                wp_enqueue_script(
                    'google-map-api',
                    esc_url_raw($google_map_api_uri),
                    array(),
                    '3.21',
                    false
                );
            }
        }
	}

endif; // pearl_enqueue_scripts

add_action( 'wp_enqueue_scripts', 'pearl_enqueue_scripts' );

if ( ! function_exists( 'pearl_editor_styles' ) ) {

	function pearl_editor_styles() {
		add_editor_style( array( PEARL_THEME_DIRECTORY_URI . '/css/editor-styles.css', pearl_fonts_url() ) );
	}

	add_action( 'after_setup_theme', 'pearl_editor_styles' );
}

/*-----------------------------------------------------------------------------------*/
/*	Generate Dynamic JavaScript
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'generate_dynamic_javascript' ) ) {
	function generate_dynamic_javascript() {

		if ( is_page_template( 'page-templates/contact.php' ) ) {
			$display_map = get_option( 'pearl_show_contact_map' );
			/* check if related theme option is enabled */
			if ( $display_map != 'hide' ) {

				$map_lati = get_option( 'pearl_map_lati' );
				$map_long = get_option( 'pearl_map_longi' );
				$map_zoom = get_option( 'pearl_map_zoom' );
				?>
				<script>
                    function initializeContactMap() {
                        var officeLocation = new google.maps.LatLng( <?php echo esc_js( $map_lati ); ?> , <?php echo esc_js( $map_long ); ?>);
                        var contactMapOptions = {
                            zoom:  <?php echo esc_js( $map_zoom );  ?>,
                            center: officeLocation,
                            mapTypeId: google.maps.MapTypeId.ROADMAP,
                            scrollwheel: false
                        }

                        var contactMap = new google.maps.Map(document.getElementById('contact-map'), contactMapOptions);
                        var contactMarker = new google.maps.Marker({
                            position: officeLocation,
                            map: contactMap
                        });

                    }

                    window.onload = initializeContactMap();
				</script>
				<?php

				if ( $display_map == 'dual' ) {

					$map_lati_2 = get_option( 'pearl_map_lati_2' );
					$map_long_2 = get_option( 'pearl_map_longi_2' );
					$map_zoom_2 = get_option( 'pearl_map_zoom_2' );
					?>
					<script>
                        function initializeContactMap() {
                            var officeLocation = new google.maps.LatLng(<?php echo esc_js( $map_lati_2 ); ?>, <?php echo esc_js( $map_long_2 ); ?>);
                            var contactMapOptions = {
                                zoom:  <?php echo esc_js( $map_zoom_2 );  ?>,
                                center: officeLocation,
                                mapTypeId: google.maps.MapTypeId.ROADMAP,
                                scrollwheel: false
                            }

                            var contactMap = new google.maps.Map(document.getElementById('contact-map-2'), contactMapOptions);
                            var contactMarker = new google.maps.Marker({
                                position: officeLocation,
                                map: contactMap
                            });

                        }

                        window.onload = initializeContactMap();
					</script>
				<?php }
			}
		}
	}
}
/* Attach dynamic javascript generation function with wp_footer action hook */
add_action( 'wp_footer', 'generate_dynamic_javascript' );


if ( ! function_exists( 'pearl_page_parent_breadcrumbs' ) ) :
	function pearl_page_parent_breadcrumbs( $page ) {
		$parent_id = $page->post_parent;
		if ( $parent_id ) {

			$parents = array();

			while ( $parent_id ) {
				$parents[] = $parent_id;
				$page      = get_post( $parent_id );
				$parent_id = $page->post_parent;
			}

			$parents_count = count( $parents );
			for ( $i = $parents_count; $i > 0; ) {
				$parent_id = $parents[ -- $i ];
				echo '<li>';
				echo '<a href="' . get_the_permalink( $parent_id ) . '">';
				echo get_the_title( $parent_id );
				echo '</a>';
				echo '</li>';
			}
		}
	}
endif;

if ( ! function_exists( 'pearl_bread_crumb' ) ) {
	/**
	 * Display breadcrumbs
	 */
	function pearl_bread_crumb() {

		if ( is_woocommerce_activated() && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
			woocommerce_breadcrumb( array(
				'delimiter'   => '',
				'before'      => '<li>',
				'after'       => '</li>',
				'wrap_before' => '<ul>',
				'wrap_after'  => '</ul>'
			) );

			return;
		}

		echo '<ul>';

		/* For all pages other than front page */
		if ( ! is_front_page() ) {
			echo '<li>';
			echo '<a href="' . home_url() . '">' . get_bloginfo( 'name' ) . '</a>';
			echo '</li>';
		}

		/* For index.php OR blog posts page */
		if ( is_home() ) {

			$page_for_posts = get_option( 'page_for_posts' );

			if ( $page_for_posts ) {

				$blog = get_post( $page_for_posts );
				echo '<li>' . $blog->post_title . '</li>';

			} else {
				echo '<li>' . esc_html__( 'Blog', 'pearl-medicalguide' ) . '<li>';
			}
		}

		if ( is_category() || is_singular( 'post' ) ) {
			$category = get_the_category();
			$ID       = $category[0]->cat_ID;
			echo '<li>' . get_category_parents( $ID, true, ' </li>', false );
		}

		if ( is_singular( 'post' ) || is_singular( 'doctor' ) || is_singular( 'service' ) || is_singular( 'gallery-item' ) || is_page() ) {

			echo '<li>' . the_title() . '</li>';
		}

		if ( is_tag() ) {
			echo '<li>' . esc_html__( 'Tag: ', 'pearl-medicalguide' ) . single_tag_title( '', false ) . '</li>';
		}

		if ( is_404() ) {
			echo '<li>' . esc_html__( '404 - Page not Found', 'pearl-medicalguide' ) . '</li>';

		}

		if ( is_search() ) {
			echo '<li>' . esc_html__( 'Search', 'pearl-medicalguide' ) . '</li>';
		}

		if ( is_month() || is_year() || is_date() || is_day() ) {
			echo '</li>' . get_the_time( 'D, M Y' ) . '</li>';
		}

		echo "</ul>";
	}
}


//
//function custom_excerpt_length( $length ) {
//    return 500;
//}
//add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

if ( ! function_exists( 'the_pearl_excerpt' ) ) {
	/**
	 * echo excerpt for given number of words.
	 *
	 * @param int $len
	 * @param string $trim
	 */
	function the_pearl_excerpt( $len = 40, $trim = "&hellip;" ) {
		echo get_pearl_excerpt( $len, $trim );
	}
}

if ( ! function_exists( 'get_pearl_excerpt' ) ) {
	/**
	 * Return excerpt for given number of words.
	 *
	 * @param int $len
	 * @param string $trim
	 *
	 * @return string
	 */
	function get_pearl_excerpt( $len = 40, $trim = "&hellip;" ) {
		$limit     = $len + 1;
		$excerpt   = explode( ' ', get_the_content(), $limit );
		$num_words = count( $excerpt );
		if ( $num_words >= $len ) {
			array_pop( $excerpt );
		} else {
			$trim = "";
		}
		$excerpt = implode( " ", $excerpt ) . $trim;

		return $excerpt;
	}
}


if ( ! function_exists( 'pearl_custom_logo' ) ) {
	/**
	 * custom logo
	 */
	function pearl_custom_logo( $html ) {
		$html = str_replace( 'custom-logo-link', 'custom-logo-link logo', $html );

		return $html;
	}
}

add_filter( 'get_custom_logo', 'pearl_custom_logo', 10 );

/**
 * Force Visual Composer to initialize as "built into the theme".
 * This will hide certain tabs under the Settings->Visual Composer page
 */
add_action( 'vc_before_init', 'pearl_vcSetAsTheme' );
function pearl_vcSetAsTheme() {
	vc_set_as_theme();
}

function pearl_excerpt_length( $length ) {
	return 500;
}

add_filter( 'excerpt_length', 'pearl_excerpt_length', 999 );

if ( ! function_exists( 'pearl_theme_comment' ) ) {
	/**
	 * Theme Custom Comment Template
	 */
	function pearl_theme_comment( $comment, $args, $depth ) {

		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
				?>
				<li class="pingback comment-box">
					<p><?php esc_html_e( 'Pingback:', 'pearl-medicalguide' ); ?><?php comment_author_link(); ?><?php edit_comment_link( esc_html__( '(Edit)', 'pearl-medicalguide' ), ' ' ); ?></p>
				</li>
				<?php
				break;

			default :
				?>
				<li <?php comment_class( 'comment-box' ); ?> id="li-comment-<?php comment_ID(); ?>">
					<article id="comment-<?php comment_ID(); ?>" class="comment-body">

						<div class="author-photo">
							<a class="avatar" href="<?php comment_author_url(); ?>">
								<?php echo get_avatar( $comment, 200 ); ?>
							</a>
						</div>
						<div class="detail">
							<?php comment_reply_link( array_merge( array( 'before' => '' ), array(
								'depth'     => $depth,
								'max_depth' => $args['max_depth']
							) ) ); ?>
							<span class="name"><?php echo get_comment_author_link(); ?></span> <span class="date"><time datetime="<?php comment_time( 'c' ); ?>">
                           <?php printf( esc_html__( '%1$s', 'pearl-medicalguide' ), get_comment_date( 'F j, Y' ) ); ?>
                            </time>
								</a><?php edit_comment_link( esc_html__( '(Edit)', 'pearl-medicalguide' ), '  ', '' ); ?></time></span>
							<?php comment_text(); ?>
						</div>
						<div class="clear"></div>
					</article>
				</li>
				<?php
				break;

		endswitch;
	}
}

if ( ! function_exists( 'pearl_quick_css' ) ) {
	/**
	 * Output Quick CSS
	 */
	function pearl_quick_css() {
		// Quick CSS from customizer settings
		$quick_css = stripslashes( get_option( 'pearl_quick_css' ) );
		if ( ! empty( $quick_css ) ) {
			echo "<style type='text/css' id='pearl-quick-css'>" . esc_html( $quick_css ) . "</style>";
		}
	}

	add_action( 'wp_head', 'pearl_quick_css' );
}

if ( ! function_exists( 'pearl_search_filter' ) ) {
	/**
	 * Filter the default search results
	 *
	 * @param object $query
	 *
	 * @return object
	 */
	function pearl_search_filter( $query ) {

		if ( $query->is_search ) {
			$query->set( 'post_type', 'post' );
		}

		return $query;

	}

	add_filter( 'pre_get_posts', 'pearl_search_filter' );
}

if ( ! function_exists( 'get_pearl_default_banner' ) ) {
	/**
	 * Get Default Banner
	 *
	 * @return mixed
	 */
	function get_pearl_default_banner() {
		$banner_image_url = get_option( 'pearl_banner_image' );
		return empty( $banner_image_url ) ? get_template_directory_uri() . '/images/header-banner.jpg' : $banner_image_url;
	}
}

/**
 * Determines whether or not the current page is a paginated page.
 * @return   boolean    True if the page is paginated; false, otherwise.
 **/
if ( ! function_exists( 'pearl_is_paginated_page' ) ) {
	function pearl_is_paginated_page() {
		global $multipage;

		return 0 !== $multipage;
	}
}

/**
 * Setting up demo content files path
 * @return array
 */
function pearl_import_files() {
	return array(
		array(
			'import_file_name'             => 'MedicalGuide Demo Content',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'inc/demo-import-files/content.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'inc/demo-import-files/widgets.wie',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'inc/demo-import-files/customizer.dat',
			'import_preview_image_url'     => get_stylesheet_directory_uri() . '/screenshot.png',
			'import_notice'                => __( '1. After you import this demo, you will have to setup the permalinks settings to <strong>Post name</strong> from <code>Settings > Permalinks</code> page.<br><br>2. Setup Revolution Slider Manually (if you want to use). You can follow the theme documentation for help.', 'pearl-medicalguide' ),
		)
	);
}

add_filter( 'pt-ocdi/import_files', 'pearl_import_files' );

/**
 * MedicalGuide demo import page setup
 *
 * @param $default_settings
 *
 * @return mixed
 */
function pearl_plugin_page_setup( $default_settings ) {

	$default_settings['parent_slug'] = 'themes.php';
	$default_settings['page_title']  = esc_html__( 'MedicalGuide Demo Import', 'pearl-medicalguide' );
	$default_settings['menu_title']  = esc_html__( 'MedicalGuide Import', 'pearl-medicalguide' );
	$default_settings['capability']  = 'import';
	$default_settings['menu_slug']   = 'medicalguide-demo-import';

	return $default_settings;
}

add_filter( 'pt-ocdi/plugin_page_setup', 'pearl_plugin_page_setup' );

/**
 * Imported contents settings
 */
function pearl_after_import_setup() {

	// Assign menus to their locations.
	$main_menu = get_term_by( 'name', 'Header Menu', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations', array(
			'main-menu' => $main_menu->term_id,
		)
	);

	// Assign front page and posts page (blog page).
	$front_page_id = get_page_by_title( 'Home' );
	$blog_page_id  = get_page_by_title( 'Blog' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_page_id->ID );
	update_option( 'page_for_posts', $blog_page_id->ID );

}

add_action( 'pt-ocdi/after_import', 'pearl_after_import_setup' );


/* CREATE the new function, with SKU added */
function woocommerce_template_loop_product_title_with_sku() {
	global $product;
	echo '<span class="loop-title-sku">' . $product->get_sku() . '</span>';
	echo '<h3 class="loop-title">' . get_the_title() . '</h3>';
}

/*REMOVE old loop-title action             */
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

/* ADD new loop-title-with sku action      */
add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title_with_sku', 10 );


///////////////// Thumbnail
if ( ! function_exists( 'pearl_woocommerce_template_loop_product_thumbnail' ) ) {

	/**
	 * Get the product thumbnail for the loop.
	 *
	 * @subpackage    Loop
	 */
	function pearl_woocommerce_template_loop_product_thumbnail() {
		echo '<div class="product-thumbnail">';
		echo woocommerce_get_product_thumbnail();
		echo '</div><div class="product-content clearfix">';
	}
}


remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'pearl_woocommerce_template_loop_product_thumbnail', 10 );

///////////// add to cart
if ( ! function_exists( 'pearl_woocommerce_template_loop_add_to_cart' ) ) {

	/**
	 * Get the add to cart template for the loop.
	 *
	 * @subpackage    Loop
	 */
	function pearl_woocommerce_template_loop_add_to_cart( $args = array() ) {
		global $product;

		if ( $product ) {
			$defaults = array(
				'quantity' => 1,
				'class'    => implode( ' ', array_filter( array(
					'button',
					'product_type_' . $product->get_type(),
					$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
					$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : ''
				) ) )
			);

			$args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );

			wc_get_template( 'loop/add-to-cart.php', $args );
		}

		echo '</div>';
	}
}

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'pearl_woocommerce_template_loop_add_to_cart', 10 );


// Change number or products per row to 3
add_filter( 'loop_shop_columns', 'loop_columns' );
if ( ! function_exists( 'loop_columns' ) ) {
	function loop_columns() {
		return 3; // 3 products per row
	}
}

if ( ! function_exists( 'pearl_woocommerce_template_single_sharing' ) ) {
	/**
	 * Display social sharing button
	 */
	function pearl_woocommerce_template_single_sharing() {
		$post_id       = get_the_ID();
		$product_title = get_the_title( $post_id );
		$product_url   = get_permalink( $post_id );
		$product_img   = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );

		$facebook_url  = 'https://www.facebook.com/share.php?u=' . $product_url . '&amp;title=' . $product_title;
		$twitter_url   = 'https://twitter.com/intent/tweet?status=' . $product_title . ' - ' . $product_url;
		$pinterest_url = 'https://pinterest.com/pin/create/bookmarklet/?media=' . $product_img[0] . '&amp;url=' . $product_url . '&amp;is_video=false&amp;description=' . $product_title;
		?>
		<ul class="boxed-social clearfix">
			<li>
				<a href="<?php echo esc_url( $facebook_url ); ?>" class="facebook" target="_blank">
					<i class="icon-euro"></i><?php esc_html_e( 'Share On Facebook', 'pearl-medicalguide' ); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo esc_url( $twitter_url ); ?>" class="twitter" target="_blank">
					<i class="icon-twitter5"></i><?php esc_html_e( 'Tweet This Product', 'pearl-medicalguide' ); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo esc_url( $pinterest_url ); ?>" class="pinterest" target="_blank">
					<i class="icon-pinterest"></i><?php esc_html_e( 'Pin This Product', 'pearl-medicalguide' ); ?>
				</a>
			</li>
		</ul>
		<?php
	}
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
add_action( 'woocommerce_single_product_summary', 'pearl_woocommerce_template_single_sharing', 50 );


add_filter( 'woocommerce_show_page_title', 'woo_hide_page_title' );
/**
 * woo_hide_page_title
 *
 * Removes the "shop" title on the main shop page
 *
 * @access      public
 * @since       1.0
 * @return      void
 */
function woo_hide_page_title() {
	return false;
}

// Display 9 products per page. Goes in functions.php
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 9;' ), 20 );

if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	/**
	 * Check whether WooCommerce plugin is activated or not
	 * @return bool
	 */
	function is_woocommerce_activated() {
		if ( class_exists( 'WooCommerce' ) ) {
			return true;
		} else {
			return false;
		}
	}
}


if ( ! function_exists( 'pearl_logo_img' ) ) {
	/**
	 * Display logo image
	 * @since 2.0.0
	 *
	 * @param string $logo_url Logo img url.
	 * @param string $retina_logo_url Retina logo image url.
	 *
	 * @return void
	 */
	function pearl_logo_img( $logo_url, $retina_logo_url ) {

		global $is_IE;

		if ( ! empty( $logo_url ) && ! empty( $retina_logo_url ) && ! $is_IE ) {
			echo '<img class="logo-image" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" src="' . esc_url( $logo_url ) . '" srcset="' . esc_url( $logo_url ) . ', ' . esc_url( $retina_logo_url ) . ' 2x">';
		} else if ( ! empty( $retina_logo_url ) ) {
			echo '<img class="logo-image" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" src="' . esc_url( $retina_logo_url ) . '">';
		} else {
			echo '<img class="logo-image" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" src="' . esc_url( $logo_url ) . '">';
		}
	}
}


if ( ! function_exists( 'pearl_add_medicalguide_doctor_menu' ) ) {
	function pearl_add_medicalguide_doctor_menu( $args ) {
		$args['show_in_menu'] = 'pearl-medicalguide';
		return $args;
	}

	add_filter( 'pearl_doctor_post_type_args', 'pearl_add_medicalguide_doctor_menu' );
}


if ( ! function_exists( 'pearl_post_show_in_menu' ) ) {
	function pearl_post_show_in_menu( $args ) {
		$args['show_in_menu'] = false;
		return $args;
	}

	add_filter( 'pearl_services_post_type_args', 'pearl_post_show_in_menu' );
	add_filter( 'pearl_faq_post_type_args', 'pearl_post_show_in_menu' );
	add_filter( 'pearl_testimonial_post_type_args', 'pearl_post_show_in_menu' );
	add_filter( 'pearl_gallery_post_type_args', 'pearl_post_show_in_menu' );
}


if ( ! function_exists( 'pearl_register_medicalguide_custom_menu_page' ) ) {
	/**
	 * Register a custom menu page.
	 */
	function pearl_register_medicalguide_custom_menu_page() {

		add_menu_page(
			esc_html__( 'MedicalGuide', 'pearl-medicalguide' ),
			esc_html__( 'MedicalGuide', 'pearl-medicalguide' ),
			'manage_categories',
			'pearl-medicalguide',
			'',
			'dashicons-clipboard',
			'5'
		);

		// Sub menus.
		$sub_menus = array();

		$sub_menus['add_new'] = array(
			'pearl-medicalguide',
			esc_html__('Add New Doctor', 'pearl-medicalguide'),
			esc_html__('Add New Doctor', 'pearl-medicalguide'),
			'manage_categories',
			'post-new.php?post_type=doctor',
		);

		$sub_menus['doctor_department'] = array(
			'pearl-medicalguide',
			esc_html__('Add New Doctor Department', 'pearl-medicalguide'),
			esc_html__('Doctor Department', 'pearl-medicalguide'),
			'manage_categories',
			'edit-tags.php?taxonomy=doctor-department&post_type=doctor',
		);

		$sub_menus['services'] = array(
			'pearl-medicalguide',
			esc_html__( 'Services', 'pearl-medicalguide' ),
			esc_html__( 'Services', 'pearl-medicalguide' ),
			'manage_categories',
			'edit.php?post_type=service',
		);

		$sub_menus['add_new_services'] = array(
			'pearl-medicalguide',
			esc_html__( 'Add New Service', 'pearl-medicalguide' ),
			esc_html__( 'Add New Service', 'pearl-medicalguide' ),
			'manage_categories',
			'post-new.php?post_type=service',
		);

		$sub_menus['faqs'] = array(
			'pearl-medicalguide',
			esc_html__( 'FAQs', 'pearl-medicalguide' ),
			esc_html__( 'FAQs', 'pearl-medicalguide' ),
			'manage_categories',
			'edit.php?post_type=faq',
		);

		$sub_menus['add_new_faqs'] = array(
			'pearl-medicalguide',
			esc_html__( 'Add New FAQ', 'pearl-medicalguide' ),
			esc_html__( 'Add New FAQ', 'pearl-medicalguide' ),
			'manage_categories',
			'post-new.php?post_type=faq',
		);

		$sub_menus['testimonials'] = array(
			'pearl-medicalguide',
			esc_html__( 'Testimonials', 'pearl-medicalguide' ),
			esc_html__( 'Testimonials', 'pearl-medicalguide' ),
			'manage_categories',
			'edit.php?post_type=testimonial',
		);

		$sub_menus['add_new_testimonials'] = array(
			'pearl-medicalguide',
			esc_html__( 'Add New Testimonial', 'pearl-medicalguide' ),
			esc_html__( 'Add New Testimonial', 'pearl-medicalguide' ),
			'manage_categories',
			'post-new.php?post_type=testimonial',
		);

		$sub_menus['gallery_items'] = array(
			'pearl-medicalguide',
			esc_html__( 'Gallery Items', 'pearl-medicalguide' ),
			esc_html__( 'Gallery Items', 'pearl-medicalguide' ),
			'manage_categories',
			'edit.php?post_type=gallery',
		);

		$sub_menus['add_new_gallery_items'] = array(
			'pearl-medicalguide',
			esc_html__( 'Add New Gallery Item', 'pearl-medicalguide' ),
			esc_html__( 'Add New Gallery Item', 'pearl-medicalguide' ),
			'manage_categories',
			'post-new.php?post_type=gallery',
		);

		$sub_menus['gallery_item_type'] = array(
			'pearl-medicalguide',
			esc_html__( 'Gallery Item Types', 'pearl-medicalguide' ),
			esc_html__( 'Gallery Item Types', 'pearl-medicalguide' ),
			'manage_categories',
			'edit-tags.php?taxonomy=gallery-type&post_type=gallery',
		);

		$sub_menus['settings'] = array(
			'pearl-medicalguide',
			esc_html__( 'Customize Settings', 'pearl-medicalguide' ),
			esc_html__( 'Customize Settings', 'pearl-medicalguide' ),
			'manage_options',
			'customize.php'
		);

		if ( class_exists( 'OCDI_Plugin' ) ) {
			$sub_menus['demo_import'] = array(
				'pearl-medicalguide',
				esc_html__( 'Demo Import', 'pearl-medicalguide' ),
				esc_html__( 'Demo Import', 'pearl-medicalguide' ),
				'manage_categories',
				'admin.php?page=pt-one-click-demo-import',
			);
		}

		$sub_menus = apply_filters( 'pearl_medicalguide_sub_menus', $sub_menus, 20 );

		if ( $sub_menus ) {
			foreach ( $sub_menus as $sub_menu ) {
				call_user_func_array( 'add_submenu_page', $sub_menu );
			}
		}
	}

	add_action( 'admin_menu', 'pearl_register_medicalguide_custom_menu_page' );
}


if ( ! function_exists( 'pearl_medicalguide_current_menu' ) ) {
	/**
	 * Actives current page menu.
	 */
	function pearl_medicalguide_current_menu() {
		global $submenu_file;
		$screen     = get_current_screen();
		$menu_items = array(
			'edit-doctor-department',
			'edit-gallery-type',
			'admin_page_pt-one-click-demo-import'
		);

		if ( in_array( $screen->id, $menu_items ) ) {
			?>
			<script id="pearl-medicalguide-current-menu" type="text/javascript">
				jQuery("body").removeClass("sticky-menu");
				jQuery("#toplevel_page_pearl-medicalguide").addClass('wp-has-current-submenu wp-menu-open').removeClass('wp-not-current-submenu');
				jQuery("#toplevel_page_pearl-medicalguide > a").addClass('wp-has-current-submenu wp-menu-open').removeClass('wp-not-current-submenu');
				<?php
				$get_array = filter_input_array( INPUT_GET );
				if ( isset( $get_array['page'] ) && ( 'pt-one-click-demo-import' === $get_array['page'] ) ) {
					$submenu_file = 'admin.php?page=pt-one-click-demo-import';
				}
				?>
				jQuery("#toplevel_page_pearl-medicalguide ul li a[href='<?php echo wp_specialchars_decode( $submenu_file ); ?>']").parent("li").addClass("current");
			</script>
			<?php
		}
	}

	add_action( 'admin_footer', 'pearl_medicalguide_current_menu' );
}


if ( ! function_exists( 'pearl_move_import_demo_data' ) ) {
	/**
	 * Move demo-import page to MedicalGuide menu
	 */
	function pearl_move_import_demo_data( $args ) {

		if ( empty( $args ) || ! is_array( $args ) ) {
			return $args;
		}

		$args = array(
			'parent_slug' => 'admin.php',
			'page_title'  => esc_html__( 'One Click Demo Import', 'pearl-medicalguide' ),
			'menu_title'  => esc_html__( 'Demo Import', 'pearl-medicalguide' ),
			'capability'  => 'import',
			'menu_slug'   => 'pt-one-click-demo-import',
		);

		return $args;
	}

	// Move demo-import page to MedicalGuide menu
	add_filter( 'pt-ocdi/plugin_page_setup', 'pearl_move_import_demo_data',10, 1  );
}