<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       webindiainc.com
 * @since      1.0.0
 *
 * @package    Fexpro_User_Order
 * @subpackage Fexpro_User_Order/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Fexpro_User_Order
 * @subpackage Fexpro_User_Order/includes
 * @author     WebIndia inc <hardikraval@webindiainc.com>
 */
class Fexpro_User_Order_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'fexpro-user-order',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
