<?php
/**
 * The main plugin file for WooCommerce Bulk Variations.
 *
 * This file is included during the WordPress bootstrap process if the plugin is active.
 *
 * @wordpress-plugin
 * Plugin Name:     WooCommerce Bulk Variations
 * Plugin URI:      https://barn2.com/wordpress-plugins/woocommerce-bulk-variations/
 * Description:     Displays product variations in a grid layout or price matrix.
 * Version:         1.1.9
 * Author:          Barn2 Plugins
 * Author URI:      https://barn2.com
 * Text Domain:     woocommerce-bulk-variations
 * Domain Path:     /languages
 *
 * WC requires at least: 3.5
 * WC tested up to: 5.3.0
 *
 * Copyright:       Barn2 Plugins Ltd
 * License:         GNU General Public License v3.0
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.html
 */
namespace Barn2\Plugin\WC_Bulk_Variations;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

const PLUGIN_FILE    = __FILE__;
const PLUGIN_VERSION = '1.1.9';

require_once plugin_dir_path( __FILE__ ) . 'autoloader.php';

/**
 * Helper function to return the main plugin instance.
 *
 * @return Plugin
 */
if ( ! function_exists( 'wbv' ) ) {

    function wbv() {
        return Plugin_Factory::create( PLUGIN_FILE, PLUGIN_VERSION );
    }

}

// Load the plugin.
wbv()->register();
