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
 * @package    Fexpro_factory_detials
 * @subpackage Fexpro_factory_detials/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Fexpro_factory_detials
 * @subpackage Fexpro_factory_detials/includes
 * @author     WebIndiaINC <hardikraval@webindiainc.com>
 */
class Fexpro_factory_detials_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'fexpro_factory_detials',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
