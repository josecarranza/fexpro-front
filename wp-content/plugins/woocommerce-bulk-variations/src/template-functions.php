<?php
/**
 * Template functions for WooCommerce Bulk Variations.
 *
 * @package   Barn2\woocommerce-bulk-variations
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */
// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wc_get_bulk_variations_table' ) ) {

	function wc_get_bulk_variations_table( $args = array() ) {
		
		// Create and return the table as HTML
		$table = Barn2\Plugin\WC_Bulk_Variations\Bulk_Variations_Table_Factory::create( $args );
		return $table->get_table( 'html' );
	}
}

if ( ! function_exists( 'wc_the_bulk_variations_table' ) ) {

	function wc_the_bulk_variations_table( $args = array() ) {
		echo wc_get_bulk_variations_table( $args );
	}
}

add_filter( 'wc_bulk_variations_stock_message', 'ucfirst', 10 );