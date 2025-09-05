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
 * @package           Fexpro_factory_detials
 *
 * @wordpress-plugin
 * Plugin Name:       Fexpro Factory
 * Plugin URI:        webindiainc.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            WebIndiaINC
 * Author URI:        webindiainc.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fexpro_factory_detials
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
define( 'FEXPRO_FACTORY_DETIALS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-fexpro_factory_detials-activator.php
 */
function activate_fexpro_factory_detials() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fexpro_factory_detials-activator.php';
	Fexpro_factory_detials_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-fexpro_factory_detials-deactivator.php
 */
function deactivate_fexpro_factory_detials() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fexpro_factory_detials-deactivator.php';
	Fexpro_factory_detials_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_fexpro_factory_detials' );
register_deactivation_hook( __FILE__, 'deactivate_fexpro_factory_detials' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-fexpro_factory_detials.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_fexpro_factory_detials() {

	$plugin = new Fexpro_factory_detials();
	$plugin->run();

}
run_fexpro_factory_detials();




/* ADMIN PANEL ADD CUSTOM PAGE STARTS */

add_action( 'admin_menu', 'wpse_91693_register' );

function wpse_91693_register()
 {	
		    add_menu_page(
		        'Fexpro Factory Details',     // page title
		        'Factory Details',     // menu title
		        'manage_options',   // capability
		        'fexpro-factory-details',     // menu slug
		        'wpse_91693_render_factory_details' // callback function 
		    );

							    
 }

function wpse_91693_render_factory_details()
{
    global $title;

    print '<div class="wrap">';
    print "<h1>$title</h1>";

    $file = plugin_dir_path( __FILE__ ) . "factory_details_data.php";

    if ( file_exists( $file ) )
        require $file;

//    print "<p class='description'>This Admin File Included from <code>$file</code></p>";

    print '</div>';
}





/* ADMIN PANEL ADD CUSTOM PAGE OVER*/
