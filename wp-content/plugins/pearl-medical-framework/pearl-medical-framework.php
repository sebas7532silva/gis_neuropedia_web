<?php
/**
 * @link              http://themeforest.net/user/pearlthemes
 * @package           Pearl_Medical_Framework
 *
 * @wordpress-plugin
 * Plugin Name:       Pearl Medical Framework
 * Plugin URI:        http://www.pearlthemes.com
 * Description:       Pearl Medical Framework plugin provides Doctors, Services, Testimonials and Gallery post types with related functionality.
 * Version:           2.0.0
 * Author:            Pearl Themes
 * Author URI:        http://themeforest.net/user/pearlthemes
 * Text Domain:       pearl-medical-framework
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pearl-medical-framework-activator.php
 */
function activate_pearl_medical_framework() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pearl-medical-framework-activator.php';
	Pearl_Medical_Framework_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pearl-medical-framework-deactivator.php
 */
function deactivate_pearl_medical_framework() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pearl-medical-framework-deactivator.php';
	Pearl_Medical_Framework_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pearl_medical_framework' );
register_deactivation_hook( __FILE__, 'deactivate_pearl_medical_framework' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pearl-medical-framework.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pearl_medical_framework() {

	$plugin = new Pearl_Medical_Framework();
	$plugin->run();

}
run_pearl_medical_framework();

/**
 * Visual Composer elements
 *
 * @since 1.0.0
 */
add_action('plugins_loaded', 'load_vc_elements');

function load_vc_elements() {
    if ( class_exists('Vc_Manager') ) {
        require_once( plugin_dir_path( __FILE__ ) . 'visual-composer/shortcodes/shortcodes.php' );
        require_once( plugin_dir_path( __FILE__ ) . 'visual-composer/vc_map.php' );
    } else {
        add_action('admin_notices', 'vc_not_loaded');
    }
}

function vc_not_loaded() {
    printf(
        '<div class="error"><p>%s</p></div>',
        esc_html__( 'Sorry, you cannot use MedicalGuide theme elements because Visual Composer plugin is not activated.', 'pearl-medical-framework' )
    );
}
