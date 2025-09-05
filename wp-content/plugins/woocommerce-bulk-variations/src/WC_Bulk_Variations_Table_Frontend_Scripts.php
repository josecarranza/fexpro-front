<?php

namespace Barn2\Plugin\WC_Bulk_Variations;

use Barn2\WBV_Lib\Registerable,
	Barn2\WBV_Lib\Service;

/**
 * Handles the registering of the front-end scripts and stylesheets. Also creates the inline CSS (if required) for the product tables.
 *
 * @package   Barn2\woocommerce-bulk-variations
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */
class WC_Bulk_Variations_Table_Frontend_Scripts implements Registerable, Service {

	const SCRIPT_HANDLE  = 'wc-bulk-variations-table';
	const SCRIPT_VERSION = Plugin::VERSION;


	public function __construct() {}

	/**
	 * Register the scripts & styles for an individual bulk variations table.
	**/
	public function register() {

		$settings = Util\Settings::get_setting( Util\Settings::OPTION_VARIATIONS_DATA );

		add_action( 'wp_enqueue_scripts', array( $this, 'register_table_scripts' ) );

		if ( ! empty( $settings['use_lightbox'] ) ) {
			add_filter( 'wc_quick_view_pro_quick_view_button', [ $this, 'maybe_add_photoswipe' ], 10, 2 );
		}
	}

	public function register_table_scripts() {

		wp_enqueue_script( 'wc-bulk-variations-product-table', Util\Util::get_asset_url( "js/wc-bulk-variations-product_table.min.js" ), array( 'jquery' ), Plugin::VERSION, true );
		wp_enqueue_style( ' wc-bulk-variations-frontend_table', Util\Util::get_asset_url( "css/frontend/wc-bulk-variations-frontend_table.css" ), array( ), Plugin::VERSION, 'all' );
		do_action( 'wc_bulk_variations_table_load_scripts' );

		// Add currency options to script
		$currency_options = array(
			'decimals'    => wc_get_price_decimals(),
			'd_separator' => wc_get_price_decimal_separator(),
			't_separator' => wc_get_price_thousand_separator()
		);

		wp_localize_script( 'wc-bulk-variations-product-table', 'b2_currency_options', $currency_options );

    }

    public function maybe_add_photoswipe( $button, $product ) {
		
		if ( $product->is_type( 'variable' )
          && false === has_action( 'wp_footer', 'woocommerce_photoswipe' )
          && false === has_action( 'wp_footer', [ self::class, 'load_photoswipe_template' ] ) ) {
            wp_enqueue_style( 'photoswipe-default-skin' );
            wp_enqueue_script( 'photoswipe-ui-default' );
            add_action( 'wp_footer', [ self::class, 'load_photoswipe_template' ] );
        }

        return $button;
    }

    public static function load_photoswipe_template() {
        wc_get_template( 'single-product/photoswipe.php' );
    }
}
