<?php
	
namespace Barn2\Plugin\WC_Bulk_Variations\Util;

use \Barn2\Plugin\WC_Bulk_Variations as WC_Bulk_Variations_Base;
use \Barn2\Plugin\WC_Bulk_Variations\Admin as WC_Bulk_Variations_Admin;

/**
 * Utility functions for WooCommerce Bulk Variations.
 *
 * @package   Barn2\woocommerce-bulk-variations\Util
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */
final class Util {
	    
    public static function get_asset_url( $path = '' ) {
        return plugins_url( 'assets/' . ltrim( $path, '/' ), WC_Bulk_Variations_Base\Plugin::FILE );
    }

    public static function get_wc_asset_url( $path = '' ) {
        if ( defined( 'WC_PLUGIN_FILE' ) ) {
            return plugins_url( 'assets/' . ltrim( $path, '/' ), WC_PLUGIN_FILE );
        }
        return false;
    }
        
    public static function sanitize_class_name( $class ) {
        return preg_replace( '/[^a-zA-Z0-9-_]/', '', $class );
    }
    
    public static function get_server_request_method() {
        return ( isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '' );
    }
    
    public static function get_attribute_label( $name, $product = '' ) {
	    
	    global $wc_product_attributes;
	    
		$original_label = wc_attribute_label( $name, $product );
		$label = $original_label;
        
        $is_product_attribute = false;

		foreach ( $wc_product_attributes as $k_attribute => $v_attribute ) {
			
			if ( $term = get_term_by( 'slug', $name, $k_attribute ) ) {
				if ( $term instanceof \WP_Term ) {
					return $term->name;
				}
			}
		}   
		     
        if ( !$is_product_attribute ) {
            $label = str_replace( array( '-', '_' ), ' ', $label );
		}
		
		// perhaps this was a negative number, bring the sign back:
		if ( substr( $original_label, 0, 1) === '-' ) {
			$label = '-' . $label;
		}
        
        return $label;
	}

    
    public static function set_wc_price( $base ) {
	    
	    $currency_symbol = get_woocommerce_currency_symbol();
	    $currenct_pos    = get_option( 'woocommerce_currency_pos' );
	    $currency        = '';
	    
	    switch( $currenct_pos ) {
		    case 'left':
		    	$currency = "{$currency_symbol}$base";
		    	break;
		    case 'left_space':
		    	$currency = "$currency_symbol $base";
		    	break;
		    case 'right':
		    	$currency = "{$base}$currency_symbol";
		    	break;
		    case 'right_space':
		    	$currency = "$base $currency_symbol";
		    	break;
		    default:
		    	$currency = "{$currency_symbol}$base";
		    	break;
	    }
        return $currency;
    }
}

// class Util