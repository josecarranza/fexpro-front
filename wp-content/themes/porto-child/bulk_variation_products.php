<?php

namespace Barn2\Plugin\WC_Bulk_Variations;

use Barn2\WBV_Lib\Registerable,
    Barn2\WBV_Lib\Service;

/**
 * This class handles our bulk variations table on the product page.
 *
 * @package   Barn2\woocommerce-bulk-variations
 * @author    Barn2 Plugins <support@barn2.co.uk>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */
class WC_Bulk_Variations_Products implements Registerable, Service {

	public function register() {

		// Remove not needed product fields
		add_action( 'wp', array( __CLASS__, 'remove_product_fields' ) );

		// Integrate with WooCommerce Quick View Pro
		add_action( 'wc_quick_view_pro_before_quick_view', array( __CLASS__, 'integrate_quick_view' ), 9999 );

		// Add compatibility with shopkeeper theme
		add_action( 'woocommerce_single_product_summary_single_add_to_cart', array( __CLASS__, 'shopkeeper_compat' ) );
	}

	public static function shopkeeper_compat() {

		global $post;
		if ( $post && $post->post_type == 'product' ) {

			$product_id  = $post->ID;
			$product_obj = wc_get_product( $product_id );

			$settings = get_option( Util\Settings::OPTION_VARIATIONS_DATA, false );
			$override = ( isset( $settings['enable'] ) && $settings['enable'] == 'yes' ) ? $settings['enable'] : false;
			if ( metadata_exists( 'post', $product_id, Util\Settings::OPTION_VARIATIONS_DATA . '_override' ) ) {
				$override = get_post_meta( $product_id, Util\Settings::OPTION_VARIATIONS_DATA . '_override', TRUE );
			}
			if ( $override ) {

				$attributes_count = 0;
				if ( $product_obj instanceof \WC_Product_Variable ) {
					$attributes       = $product_obj->get_variation_attributes();
					$attributes_count = count( $attributes );
				}

				if ( $product_obj->is_in_stock() && $product_obj->get_price() && $attributes_count && $attributes_count <= 2 ) {

					// Add compatibility with shopkeeper theme
					remove_action( 'woocommerce_single_product_summary_single_add_to_cart', 'woocommerce_template_single_add_to_cart', 30 );
					remove_action( 'woocommerce_single_product_summary_single_meta', 'woocommerce_template_single_meta', 40 );
					remove_action( 'woocommerce_single_product_summary_single_sharing', 'woocommerce_template_single_sharing', 50 );
				}
			} else {
				$variations_data = get_post_meta( $product_id, Util\Settings::OPTION_VARIATIONS_DATA, TRUE );
				if ( isset( $variations_data['hide_add_to_cart'] ) && $variations_data['hide_add_to_cart'] ) {

					// Add compatibility with shopkeeper theme
					remove_action( 'woocommerce_single_product_summary_single_add_to_cart', 'woocommerce_template_single_add_to_cart', 30 );
					remove_action( 'woocommerce_single_product_summary_single_meta', 'woocommerce_template_single_meta', 40 );
					remove_action( 'woocommerce_single_product_summary_single_sharing', 'woocommerce_template_single_sharing', 50 );
				}
			}
		}
	}

	public static function integrate_quick_view() {

		global $post;
		if ( $post && $post->post_type == 'product' ) {

			$product_id  = $post->ID;
			$product_obj = wc_get_product( $product_id );
			if ( $product_obj && $product_obj instanceof \WC_Product_Variable ) {

				$settings = get_option( Util\Settings::OPTION_VARIATIONS_DATA, false );
				$override = ( isset( $settings['enable'] ) && $settings['enable'] == 'yes' ) ? $settings['enable'] : false;
				if ( metadata_exists( 'post', $product_id, Util\Settings::OPTION_VARIATIONS_DATA . '_override' ) ) {
					$override = get_post_meta( $product_id, Util\Settings::OPTION_VARIATIONS_DATA . '_override', TRUE );
				}
				if ( $override ) {

					$attributes_count = 0;
					if ( $product_obj instanceof \WC_Product_Variable ) {
						$attributes       = $product_obj->get_variation_attributes();
						$attributes_count = count( $attributes );
					}

					if ( $product_obj->is_in_stock() && $product_obj->get_price() && $attributes_count && $attributes_count <= 2 ) {

						remove_action( 'wc_quick_view_pro_quick_view_product_details', 'woocommerce_template_single_price', 10 );
						remove_action( 'wc_quick_view_pro_quick_view_product_details', 'woocommerce_template_single_add_to_cart', 30 );
						add_action( 'wc_quick_view_pro_quick_view_product_details', array( __CLASS__, 'setup_variations_table' ), 9 );
					}
				} else {
					$variations_data = get_post_meta( $product_id, Util\Settings::OPTION_VARIATIONS_DATA, TRUE );
					if ( isset( $variations_data['hide_add_to_cart'] ) && $variations_data['hide_add_to_cart'] ) {

						remove_action( 'wc_quick_view_pro_quick_view_product_details', 'woocommerce_template_single_add_to_cart', 30 );
					}
				}
			}
		}
	}

	public static function remove_product_fields() {

		global $post;
		if ( $post && $post->post_type == 'product' ) {

			$product_id  = $post->ID;
			$product_obj = wc_get_product( $product_id );

			$settings = get_option( Util\Settings::OPTION_VARIATIONS_DATA, false );
			$override = ( isset( $settings['enable'] ) && $settings['enable'] == 'yes' ) ? $settings['enable'] : false;
			if ( metadata_exists( 'post', $product_id, Util\Settings::OPTION_VARIATIONS_DATA . '_override' ) ) {
				$override = get_post_meta( $product_id, Util\Settings::OPTION_VARIATIONS_DATA . '_override', TRUE );
			}
			if ( $override ) {

				$attributes_count = 0;
				if ( $product_obj instanceof \WC_Product_Variable ) {
					$attributes       = $product_obj->get_variation_attributes();
					$attributes_count = count( $attributes );
				}

				if ( $product_obj->is_in_stock() && $product_obj->get_price() && $attributes_count && $attributes_count <= 2 ) {

					add_action( 'woocommerce_before_single_product', array( __CLASS__, 'hook_into_summary_actions' ), 1 );
				}

			} else {
				$variations_data = get_post_meta( $product_id, Util\Settings::OPTION_VARIATIONS_DATA, TRUE );
				if ( isset( $variations_data['hide_add_to_cart'] ) && $variations_data['hide_add_to_cart'] ) {

					remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
				}
			}
		}

	}

	public static function hook_into_summary_actions() {

		$location = has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart' );
		
		if ( $location ) {

			//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', $location );
			add_action( 'woocommerce_single_product_summary', array( __CLASS__, 'setup_variations_table' ), 21 );

		} elseif ( $location = has_action( 'woocommerce_single_product_summary_single_add_to_cart', 'woocommerce_template_single_add_to_cart' ) ) {

			remove_action( 'woocommerce_single_product_summary_single_add_to_cart', 'woocommerce_template_single_add_to_cart', $location );
			add_action( 'woocommerce_single_product_summary_single_add_to_cart', array( __CLASS__, 'setup_variations_table' ) );

		} else {

			remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
			add_action( 'woocommerce_variable_add_to_cart', array( __CLASS__, 'setup_variations_table' ), 30 );

		}

	}

	public static function setup_variations_table() {

		global $product;
		$product_id = $product ? $product->get_id() : 0;
		if ( $product_id ) {

			$variations_data      = get_post_meta( $product_id, Util\Settings::OPTION_VARIATIONS_DATA, TRUE );
			$variations_structure = get_post_meta( $product_id, Util\Settings::OPTION_VARIATIONS_STRUCTURE, TRUE );

			$atts = array( 'include' => $product_id );

			if ( isset( $variations_data['variation_images'] ) ) {
				$images                   = $variations_data['variation_images'] ? true : false;
				$atts['variation_images'] = $images;
			}
			if ( isset( $variations_structure['rows'] ) ) {
				$rows         = str_replace( 'pa_', '', $variations_structure['rows'] );
				$atts['rows'] = $rows;
			}
			if ( isset( $variations_structure['columns'] ) ) {
				$columns         = str_replace( 'pa_', '', $variations_structure['columns'] );
				$atts['columns'] = $columns;
			}
			if ( isset( $variations_data['disable_purchasing'] ) ) {
				$disable = $variations_data['disable_purchasing'] ? true : false;
				$atts['disable_purchasing'] = $disable;
			}

			// Fill-in missing attributes
			$r = shortcode_atts( WC_Bulk_Variations_Args::get_defaults(), $atts );
			
			// Return the table as HTML
			$output = apply_filters( 'wc_bulk_variations_product_output', wc_get_bulk_variations_table( $r, $atts ) );

			//echo '<div class="wc-bulk-variations-table-wrapper">' . $output . '</div>';
			include("bulk_variation_table_wi.php");

			//echo '<div class="wc-bulk-variations-table-wrapper">' . $output . '</div>';
		}
	}
}
// class WC_Bulk_Variations_Products
