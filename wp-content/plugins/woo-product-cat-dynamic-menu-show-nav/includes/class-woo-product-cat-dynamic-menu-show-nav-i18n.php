<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.webindiainc.com
 * @since      1.0.0
 *
 * @package    Woo_Product_Cat_Dynamic_Menu_Show_Nav
 * @subpackage Woo_Product_Cat_Dynamic_Menu_Show_Nav/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woo_Product_Cat_Dynamic_Menu_Show_Nav
 * @subpackage Woo_Product_Cat_Dynamic_Menu_Show_Nav/includes
 * @author     Vishal <vishalrathod@webindiainc.com>
 */
class Woo_Product_Cat_Dynamic_Menu_Show_Nav_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woo-product-cat-dynamic-menu-show-nav',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
