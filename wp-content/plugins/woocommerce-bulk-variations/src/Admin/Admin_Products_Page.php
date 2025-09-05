<?php

namespace Barn2\Plugin\WC_Bulk_Variations\Admin;

use \Barn2\Plugin\WC_Bulk_Variations as WC_Bulk_Variations_Base;
use \Barn2\Plugin\WC_Bulk_Variations\Util as WC_Bulk_Variations_Util;

use Barn2\WBV_Lib\Plugin\License\Admin\License_Setting;

use Barn2\WBV_Lib\Registerable,
    Barn2\WBV_Lib\Service;

/**
 * Provides functions for the plugin settings page in the WordPress admin.
 *
 * Settings can be accessed at WooCommerce -> Settings -> Products -> Product tables.
 *
 * @package   Barn2\woocommerce-bulk-variations\Admin
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */
class Admin_Products_Page implements Registerable, Service {

    private $license;
    private $templates_path;

    public function __construct( License_Setting $license, $templates_path ) {
        $this->license        = $license;
        $this->templates_path = $templates_path;
    }
    
    public function register(){ 
	    
	    // Add product tabs
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_product_tab' ) );
		
		// Add bulk variation fields view
		add_action ( 'woocommerce_product_data_panels', array( $this, 'add_product_view' ) );
		
		// Save bulk variation fields
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_fields' ), 999 );
		
		// Product page JS
		add_action( 'admin_enqueue_scripts', array( $this, 'product_admin_scripts' ) );
    }
    
    public function update_fields() {
	    
	    $update   = false;
	    $settings = WC_Bulk_Variations_Util\Settings::get_setting( WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_DATA );
	    $override = isset( $_POST[WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_DATA . '_override'] ) ? $_POST[WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_DATA . '_override'] : null;
	    $data     = isset( $_POST[WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_DATA] ) ? $_POST[WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_DATA] : array();
	    
	    if ( isset( $data['hide_add_to_cart'] ) && $data['hide_add_to_cart'] ) {
		    
		    $update = true;
	    } else {
	    	if ( $settings ) {
			    foreach ( $settings as $k_setting => $v_setting ) {
				    
				    switch ( $k_setting ) {
					    case 'enable':
					    	if ( $v_setting != $override ) {
						    	$update = true;
					    	}
					    	break;
					    default:
					    	if ( isset( $data[ $k_setting ] ) && $data[ $k_setting ] != $v_setting ) {
						    	$update = true;
					    	}
					    	break;
				    }		    
			    }
		    } else {
			    
			    $update = true;
		    }
	    }
	    	    
	    return $update;
    }
    
    public function save_product_fields( $post_id ) {
	    
	    $product_obj = wc_get_product( $post_id );
	    if ( $product_obj instanceof \WC_Product_Variable ) {
	    	
	    	$attributes = $product_obj->get_variation_attributes();
		    $update     = $this->update_fields();
		    
		    if ( $update ) {
			    
			    // Save fields
				$override = isset( $_POST[WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_DATA . '_override'] ) ? $_POST[WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_DATA . '_override'] : null;
				if ( !is_null( $override ) ) {
					update_post_meta( $post_id, WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_DATA . '_override', esc_attr( $override ) );	
				}
				
				$data = isset( $_POST[WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_DATA] ) ? $_POST[WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_DATA] : null;
				if ( !is_null( $data ) && is_array( $data ) ) {
					update_post_meta( $post_id, WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_DATA, $data );	
				}
			} else {
				
				delete_post_meta( $post_id, WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_DATA . '_override' );
				delete_post_meta( $post_id, WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_DATA );
			}
			
			$single_attribute = ( count( $attributes ) == 1 ) ? true : false;
			if( $single_attribute ) {
				
				$settings      = WC_Bulk_Variations_Util\Settings::get_setting( WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_DATA );
				$single_layout = isset( $settings['variation_attribute'] ) ? $settings['variation_attribute'] : '';
				$structure     = isset( $_POST[WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_STRUCTURE] ) ? $_POST[WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_STRUCTURE] : null;
				
				if ( $structure['rows'] && $single_layout ) {
					delete_post_meta( $post_id, WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_STRUCTURE );
				} else if( $structure['columns'] && !$single_layout ) {
					delete_post_meta( $post_id, WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_STRUCTURE );
				} else {
					update_post_meta( $post_id, WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_STRUCTURE, $structure );
				}
			}
			else{
				
				$structure = isset( $_POST[WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_STRUCTURE] ) ? $_POST[WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_STRUCTURE] : null;
				if ( !is_null( $structure ) && is_array( $structure ) ) {
					update_post_meta( $post_id, WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_STRUCTURE, $structure );	
				}
			}
		}
    }
    
    public function add_product_view() {
	    	    
	    include_once $this->templates_path . 'bulk-variation-data-panel.php';
    }
    
    public function product_admin_scripts() {
    	
    	global $post;
    	if ( $post && $post->post_type == 'product' ) {
	    	
	    	$is_bulk          = false;
	    	$variations_count = 0;
	    	
	    	$product_obj = wc_get_product( $post->ID );
	    	
	    	if ( $product_obj && $product_obj instanceof \WC_Product_Variable ) {
		    	
		    	$variation_child  = $product_obj->get_children();
		    	$variations_count = count( $variation_child );
		    	if ( $variation_child && count( $variation_child ) > 0 ) {
			    	
			    	$attributes = $product_obj->get_variation_attributes();
			    	
			    	if ( $attributes && count( $attributes ) <= 2 ) {
				    	
				    	$is_bulk = true;				    	
			    	}
		    	}
	    	}
	    	
	    	wp_enqueue_style( 'wc-bulk-variations-product', WC_Bulk_Variations_Util\Util::get_asset_url( "css/admin/wc-bulk-variations-product.css" ), array(), WC_Bulk_Variations_Base\Plugin::VERSION );
	    	wp_enqueue_script( 'wc-bulk-variations-product', WC_Bulk_Variations_Util\Util::get_asset_url( "js/admin/wc-bulk-variations-product.min.js" ), array( 'jquery' ), WC_Bulk_Variations_Base\Plugin::VERSION, true );
	    	
	    	$settings      = WC_Bulk_Variations_Util\Settings::get_setting( WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_DATA );
	    	
	    	$data = array( 'variations_count' => $variations_count, 'is_bulk_variation' => $is_bulk, 'option_variation_data' => WC_Bulk_Variations_Util\Settings::OPTION_VARIATIONS_DATA, 'settings' => $settings );
	    	
	    	wp_localize_script( 'wc-bulk-variations-product', 'wcbvp_data', $data );
    	}
    }
    
    public function add_product_tab( $tabs ) {
	    
	    $tabs['bulk_variations'] = array( 'label' => 'Bulk Variations', 'target' => 'bulk_variations_product_data', 'class' => array( 'show_if_bulk_variations' ), 'priority' => 65 );
	    return $tabs;
    }
}

// class Admin_Products_Page