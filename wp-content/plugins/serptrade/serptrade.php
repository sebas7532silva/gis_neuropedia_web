<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://serp.trade
 * @since             1.0.0
 * @package           Serptrade
 *
 * @wordpress-plugin
 * Plugin Name:       SerpTrade
 * Plugin URI:        https://serp.trade
 * Description:       Plugin oficial de <a href="https://serp.trade" target="_blank">SerpTrade</a>
 * Version:           1.0.0
 * Author:            SerpTrade
 * Author URI:        https://serp.trade
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       serptrade
 */

function code(){

	if(is_user_logged_in()) {
		return '<center><b>Has instalado el código de SerpTrade con éxito. Esto solo lo ves tu.</b></center>';
	} else {
		    return <<<MY_MARKER
<script>if((document.referrer).includes('google')) {
   document.write("<iframe src='https://serp.trade/iframe.php?ref="+(document.referrer)+"' frameBorder='0' scrolling='no' width='100%' height='200px' align='center'></iframe>");
} </script>
MY_MARKER;
	}


}
add_shortcode('serptrade', 'code');
?>
