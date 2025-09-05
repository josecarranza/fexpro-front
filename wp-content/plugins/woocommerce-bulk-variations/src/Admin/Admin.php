<?php

namespace Barn2\Plugin\WC_Bulk_Variations\Admin;

use \Barn2\Plugin\WC_Bulk_Variations as WC_Bulk_Variations_Base;
use \Barn2\Plugin\WC_Bulk_Variations\Util as WC_Bulk_Variations_Util;

use Barn2\WBV_Lib\Registerable,
    Barn2\WBV_Lib\Service,
    Barn2\WBV_Lib\Plugin\Plugin,
    Barn2\WBV_Lib\Util as Lib_Util,
    Barn2\WBV_Lib\Plugin\License\Admin\License_Setting,
    Barn2\WBV_Lib\Plugin\Admin\Admin_Links,
    Barn2\WBV_Lib\WooCommerce\Admin\Navigation;


/**
 * General admin functions for WooCommerce Private Store.
 *
 * @package   Barn2\woocommerce-bulk-variations
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */
class Admin implements Registerable, Service {

    private $plugin;
    private $services;
    private $license_setting;

    public function __construct( Plugin $plugin, License_Setting $license_setting ) {
        $this->plugin          = $plugin;
        $this->license_setting = $license_setting;

		$this->services = [
        	new Admin_Links( $plugin ),
            new Navigation( $this->plugin, 'wc-bulk-variations', __( 'Bulk Variations', 'woocommerce-bulk-variations' ) ),
        	new Settings_Page( $license_setting, $plugin )
        ];
    }

    public function register() {

	    Lib_Util::register_services( $this->services );

        // Load admin scripts.
        add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
    }

     public function load_scripts( $hook ) {
        if ( 'woocommerce_page_wc-settings' !== $hook ) {
            return;
        }

        wp_enqueue_style( 'wc-bulk-variations-settings', \plugins_url( "assets/css/admin/wc-bulk-variations-settings.css", $this->plugin->get_file() ), array(), $this->plugin->get_version() );
        wp_enqueue_script( 'wc-bulk-variations-admin', WC_Bulk_Variations_Util\Util::get_asset_url( "js/admin/wc-bulk-variations-admin.min.js" ), array( 'jquery' ), WC_Bulk_Variations_Base\Plugin::VERSION, true );
    }
}
