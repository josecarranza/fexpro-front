<?php
namespace Barn2\Plugin\WC_Bulk_Variations;

use Barn2\WBV_Lib\Registerable,
    Barn2\WBV_Lib\Translatable,
    Barn2\WBV_Lib\Service_Provider,
    Barn2\WBV_Lib\Plugin\Premium_Plugin,
    Barn2\WBV_Lib\Plugin\Licensed_Plugin,
    Barn2\WBV_Lib\Util as Lib_Util;

/**
 * The main plugin class for WooCommerce Bulk Variations.
 *
 *
 * @package   Barn2\woocommerce-bulk-variations
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */
class Plugin extends Premium_Plugin implements Licensed_Plugin, Registerable, Translatable, Service_Provider {

    const NAME    = 'WooCommerce Bulk Variations';
    const FILE    = PLUGIN_FILE;
    const ITEM_ID = 194350;
    const VERSION = PLUGIN_VERSION;

    /* Our plugin license */

    private $services = [];

    public function __construct( $file = null, $version = null ) {

        parent::__construct( [
            'name'               => self::NAME,
            'item_id'            => self::ITEM_ID,
            'version'            => $version,
            'file'               => $file,
            'is_woocommerce'     => true,
            'settings_path'      => 'admin.php?page=wc-settings&tab=products&section=bulk-variations',
            'documentation_path' => 'kb-categories/bulk-variations-kb/'
        ] );
    }

    /**
     * Registers the plugin with WordPress.
     */
    public function register() {
        parent::register();

        \add_action( 'plugins_loaded', [ $this, 'maybe_load_services' ] );
    }

    public function maybe_load_services() {
        // Don't load anything if WooCommerce not active.
        if ( ! Lib_Util::is_woocommerce_active() ) {
            $this->add_missing_woocommerce_notice();
            return;
        }

        add_action( 'init', array( $this, 'load_textdomain' ), 5 );
        add_action( 'init', array( $this, 'load_services' ) );
    }

    public function load_services() {
        // Create the admin service.
        if ( \is_admin() ) {
            $this->services['admin'] = new Admin\Admin( $this, $this->get_license_setting() );
        }

        // Initialise plugin if valid and WC active.
        if ( Lib_Util::is_woocommerce_active() && $this->get_license()->is_valid() ) {

            if ( \Barn2\WBV_Lib\Util::is_front_end() ) {

                $this->services['products_frontend']  = new WC_Bulk_Variations_Products();
                $this->services['products_shortcode'] = new WC_Bulk_Variations_Shortcode();
                $this->services['cart_handler']       = new WC_Bulk_Variations_Table_Cart_Handler();
                $this->services['scripts_frontend']   = new WC_Bulk_Variations_Table_Frontend_Scripts();
            } else if ( \is_admin() ) {
                $this->services['products_admin'] = new Admin\Admin_Products_Page( $this->get_license_setting(), plugin_dir_path( PLUGIN_FILE ) . 'templates/' );
            }
        }

        Lib_Util::register_services( $this->services );
    }

    private function add_missing_woocommerce_notice() {
        if ( is_admin() ) {
            $admin_notice = new \Barn2\WBV_Lib\Admin\Notices();
            $admin_notice->add(
                'wps_woocommerce_missing',
                '',
                sprintf( __( 'Please %1$sinstall WooCommerce%2$s in order to use WooCommerce Bulk Variations.', 'woocommerce-bulk-variations' ), Lib_Util::format_link_open( 'https://woocommerce.com/', true ), '</a>' ),
                array(
                    'type'       => 'error',
                    'capability' => 'install_plugins'
            ) );
            $admin_notice->boot();
        }
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'woocommerce-bulk-variations', false, dirname( plugin_basename( PLUGIN_FILE ) ) . '/languages' );
    }

    public function get_service( $id ) {
        return isset( $this->services[$id] ) ? $this->services['id'] : null;
    }

    public function get_services() {
        return $this->services;
    }

}
