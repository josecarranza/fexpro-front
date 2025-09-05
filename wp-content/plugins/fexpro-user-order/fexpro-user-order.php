<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              webindiainc.com
 * @since             1.0.0
 * @package           Fexpro_User_Order
 *
 * @wordpress-plugin
 * Plugin Name:       Fexpro Order
 * Plugin URI:        webindiainc.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            WebIndia inc
 * Author URI:        webindiainc.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fexpro-user-order
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'FEXPRO_USER_ORDER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-fexpro-user-order-activator.php
 */
function activate_fexpro_user_order() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fexpro-user-order-activator.php';
	Fexpro_User_Order_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-fexpro-user-order-deactivator.php
 */
function deactivate_fexpro_user_order() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fexpro-user-order-deactivator.php';
	Fexpro_User_Order_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_fexpro_user_order' );
register_deactivation_hook( __FILE__, 'deactivate_fexpro_user_order' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-fexpro-user-order.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_fexpro_user_order() {

	$plugin = new Fexpro_User_Order();
	$plugin->run();

}
run_fexpro_user_order();




/* ADMIN PANEL ADD CUSTOM PAGE STARTS */

add_action( 'admin_menu', 'wpse_91693_user_orders' );

function wpse_91693_user_orders()
 {	
		    add_menu_page(
		        'Fexpro Orders ',     // page title
		        'Fexpro Orders ',     // menu title
		        'manage_options',   // capability
		        'fexpro-user-order',     // menu slug		         
		        'wpse_91693_render_order_details' // callback function 
		    );

							    
 }

function wpse_91693_render_order_details()
{
    global $title;

    print '<div class="wrap">';
    print "<h1>$title</h1>";

    $file = plugin_dir_path( __FILE__ ) . "fexpro_order_data.php";

    if ( file_exists( $file ) )
        require $file;

//    print "<p class='description'>This Admin File Included from <code>$file</code></p>";

    print '</div>';
}





/* ADMIN PANEL ADD CUSTOM PAGE OVER*/