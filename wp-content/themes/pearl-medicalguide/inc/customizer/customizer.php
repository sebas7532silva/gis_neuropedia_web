<?php
/**
 * Customizer
 */

/**
 * Load custom controls
 */
if ( ! function_exists( 'pearl_load_customize_controls' ) ) :
	function pearl_load_customize_controls() {
		require_once( get_template_directory() . '/inc/customizer/custom/separator.php' );
		require_once( get_template_directory() . '/inc/customizer/custom/dropdown.php' );
		require_once( get_template_directory() . '/inc/customizer/custom/radio-image-control.php' );
	}
endif;
add_action( 'customize_register', 'pearl_load_customize_controls', 0 );

if ( ! function_exists( 'pearl_sanitize' ) ) :
	/**
	 * A sanitization placeholder
	 *
	 * @param $str
	 *
	 * @return mixed
	 */
	function pearl_sanitize( $str ) {
		return $str;
	}
endif;

if ( ! function_exists( 'pearl_customizer_style' ) ) :
	/**
	 * Add customizer control styles
	 *
	 * @param void
	 *
	 * @return mixed
	 */
	function pearl_customizer_style() {
		wp_add_inline_style( 'customize-controls', '.customize-control-title { }' );
	}
endif;
add_action( 'customize_controls_enqueue_scripts', 'pearl_customizer_style' );


/**
 * Header Settings
 */
require_once( get_template_directory() . '/inc/customizer/header.php' );

/**
 * Timetable Settings
 */
require_once( get_template_directory() . '/inc/customizer/timetable.php' );

/**
 * Appointment page Settings
 */
require_once( get_template_directory() . '/inc/customizer/appointment.php' );

/**
 * News Settings
 */
require_once( get_template_directory() . '/inc/customizer/news.php' );

/**
 * Social Links Settings
 */
require_once( get_template_directory() . '/inc/customizer/social.php' );

/**
 * Footer Settings
 */
require_once( get_template_directory() . '/inc/customizer/footer.php' );

/**
 * General Settings
 */
require_once( get_template_directory() . '/inc/customizer/general.php' );

/**
 * Styles Settings
 */
require_once( get_template_directory() . '/inc/customizer/styles.php' );
