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
 * @package    Fexpro_Pop
 * @subpackage Fexpro_Pop/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Fexpro_Pop
 * @subpackage Fexpro_Pop/includes
 * @author     WebIndia <author@gmail.com>
 */
class Fexpro_Pop_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'fexpro-pop',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
