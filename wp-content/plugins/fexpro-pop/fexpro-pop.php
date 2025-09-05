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
 * @package           Fexpro_Pop
 *
 * @wordpress-plugin
 * Plugin Name:       Fexpro Pop
 * Plugin URI:        webindiainc.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            WebIndia
 * Author URI:        webindiainc.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fexpro-pop
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
define( 'FEXPRO_POP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-fexpro-pop-activator.php
 */
function activate_fexpro_pop() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fexpro-pop-activator.php';
	Fexpro_Pop_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-fexpro-pop-deactivator.php
 */
function deactivate_fexpro_pop() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fexpro-pop-deactivator.php';
	Fexpro_Pop_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_fexpro_pop' );
register_deactivation_hook( __FILE__, 'deactivate_fexpro_pop' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-fexpro-pop.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_fexpro_pop() {

	$plugin = new Fexpro_Pop();
	$plugin->run();

}
run_fexpro_pop();



/* ADMIN PANEL ADD CUSTOM PAGE STARTS */

add_action( 'admin_menu', 'wpse_91693_fexpro_pop' );

function wpse_91693_fexpro_pop()
 {	
		    add_menu_page(
		        'Fexpro Pop ',     // page title
		        'Fexpro Pop ',     // menu title
		        'manage_options',   // capability
		        'fexpro-pop',     // menu slug		         
		        'wpse_91693_render_fexpro_pop' // callback function 
		    );

		    //Submenu
	     /*add_submenu_page(
	        'fexpro-pop',  //The slug name for the parent menu.
	        'Without User', //the title
	        'Without User', //The text to be used for the menu.
	        'manage_options',  //capability 
	        'wpdocs_my_custom_submenu_page_callback',  //he slug name to refer to this menu
	        'wpdocs_my_custom_submenu_page_callback' ); //The function to be called to output */

							    
 }

function wpse_91693_render_fexpro_pop()
{
    global $title;

    print '<div class="wrap">';
    print "<h1>$title</h1>";

    $file = plugin_dir_path( __FILE__ ) . "fexpro_pop_data.php";

    if ( file_exists( $file ) )
        require $file;

//    print "<p class='description'>This Admin File Included from <code>$file</code></p>";

    print '</div>';
}


function wpdocs_my_custom_submenu_page_callback() {
    global $title;

    print '<div class="wrap">';
    print "<h1>$title</h1>";

    $file = plugin_dir_path( __FILE__ ) . "fexpro_without_out.php";

    if ( file_exists( $file ) )
        require $file;

    //print "<p class='description'>Product Import Script<code>$file</code></p>";

    print '</div>';
    
}


/* ADMIN PANEL ADD CUSTOM PAGE OVER*/