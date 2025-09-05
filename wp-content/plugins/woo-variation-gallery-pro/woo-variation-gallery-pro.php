<?php
	/**
	 * Plugin Name: Additional Variation Images Gallery for WooCommerce - Pro
	 * Plugin URI: https://getwooplugins.com/plugins/woocommerce-variation-gallery/
	 * Description: Additional Variation Images Gallery for WooCommerce - Pro. Requires WooCommerce 3.2+
	 * Author: Emran Ahmed
	 * Version: 1.2.6
	 * Domain Path: /languages
	 * Requires at least: 4.8
	 * WC requires at least: 4.5
	 * Tested up to: 5.7
	 * WC tested up to: 5.2
	 * Text Domain: woo-variation-gallery-pro
	 * Author URI: https://getwooplugins.com/
	 */
	
	defined( 'ABSPATH' ) || die( 'Keep Silent' );
	
	if ( ! class_exists( 'Woo_Variation_Gallery_Pro' ) ) :
		
		final class Woo_Variation_Gallery_Pro {
			
			protected $_version = '1.2.6';
			
			protected static $_instance = null;
			
			public static function instance() {
				if ( is_null( self::$_instance ) ) {
					self::$_instance = new self();
				}
				
				return self::$_instance;
			}
			
			public function __construct() {
				$this->constants();
				$this->language();
				$this->includes();
				$this->hooks();
				do_action( 'woo_variation_gallery_pro_loaded', $this );
			}
			
			public function define( $name, $value, $case_insensitive = false ) {
				if ( ! defined( $name ) ) {
					define( $name, $value, $case_insensitive );
				}
			}
			
			public function constants() {
				$this->define( 'WOO_VG_PRO_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
				$this->define( 'WOO_VG_PRO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
				$this->define( 'WOO_VG_PRO_VERSION', $this->version() );
				$this->define( 'WOO_VG_PRO_PLUGIN_INCLUDE_PATH', trailingslashit( plugin_dir_path( __FILE__ ) . 'includes' ) );
				$this->define( 'WOO_VG_PRO_PLUGIN_DIRNAME', dirname( plugin_basename( __FILE__ ) ) );
				$this->define( 'WOO_VG_PRO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
				$this->define( 'WOO_VG_PRO_PLUGIN_FILE', __FILE__ );
				$this->define( 'WOO_VG_PRO_IMAGES_URI', trailingslashit( plugin_dir_url( __FILE__ ) . 'images' ) );
				$this->define( 'WOO_VG_PRO_ASSETS_URI', trailingslashit( plugin_dir_url( __FILE__ ) . 'assets' ) );
			}
			
			public function includes() {
				if ( $this->is_required_php_version() ) {
					require_once $this->include_path( 'class-woo-variation-gallery-pro-updater.php' );
					// require_once $this->include_path( 'gwp-functions.php' );
					require_once $this->include_path( 'functions.php' );
					require_once $this->include_path( 'hooks.php' );
					require_once $this->include_path( 'theme-supports.php' );
					require_once $this->include_path( 'rest-api-response.php' );
				}
			}
			
			public function include_path( $file ) {
				$file = ltrim( $file, '/' );
				
				return WOO_VG_PRO_PLUGIN_INCLUDE_PATH . $file;
			}
			
			public function enqueue_scripts() {
				
				// Disable Pro Scripts
				if ( apply_filters( 'disable_wvg_pro_enqueue_scripts', false ) ) {
					return false;
				}
				
				// Disable gallery on specific product
				
				if ( apply_filters( 'disable_woo_variation_gallery', false ) ) {
					return false;
				}
				
				
				// wp_enqueue_style( 'woo-variation-gallery-pro', esc_url( $this->assets_uri( "/css/frontend-pro{$suffix}.css" ) ), array(), $this->version() );
				
				do_action( 'woo_variation_gallery_pro_enqueue_scripts', $this );
				
				$this->dokan_support_admin_scripts();
			}
			
			public function dokan_support_admin_scripts() {
				
				$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
				
				if ( current_user_can( 'dokan_edit_product' ) && function_exists( 'dokan_is_product_edit_page' ) && dokan_is_product_edit_page() ) {
					
					wp_enqueue_style( 'woo-variation-gallery-admin-pro', esc_url( $this->assets_uri( "/css/admin-pro{$suffix}.css" ) ), array( 'dashicons' ), $this->version() );
					wp_enqueue_script( 'woo-variation-gallery-admin-pro', esc_url( $this->assets_uri( "/js/admin-pro{$suffix}.js" ) ), array( 'jquery', 'jquery-ui-sortable', 'wp-util' ), $this->version(), true );
					
					wp_localize_script( 'woo-variation-gallery-admin-pro', 'woo_variation_gallery_admin', array(
						'choose_video' => esc_html__( 'Choose Video', 'woo-variation-gallery-pro' ),
						'choose_image' => esc_html__( 'Choose Image', 'woo-variation-gallery-pro' ),
						'add_image'    => esc_html__( 'Add Images', 'woo-variation-gallery-pro' ),
						'add_video'    => esc_html__( 'Add Video', 'woo-variation-gallery-pro' ),
						'update_image' => esc_html__( 'Update Images', 'woo-variation-gallery-pro' )
					) );
					
				}
			}
			
			public function admin_enqueue_scripts() {
				
				$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
				
				wp_enqueue_style( 'woo-variation-gallery-admin-pro', esc_url( $this->assets_uri( "/css/admin-pro{$suffix}.css" ) ), array( 'dashicons' ), $this->version() );
				wp_enqueue_script( 'woo-variation-gallery-admin-pro', esc_url( $this->assets_uri( "/js/admin-pro{$suffix}.js" ) ), array( 'jquery', 'jquery-ui-sortable', 'wp-util' ), $this->version(), true );
				
				wp_localize_script( 'woo-variation-gallery-admin-pro', 'woo_variation_gallery_admin', array(
					'choose_video' => esc_html__( 'Choose Video', 'woo-variation-gallery-pro' ),
					'choose_image' => esc_html__( 'Choose Image', 'woo-variation-gallery-pro' ),
					'add_image'    => esc_html__( 'Add Images', 'woo-variation-gallery-pro' ),
					'add_video'    => esc_html__( 'Add Video', 'woo-variation-gallery-pro' ),
					'update_image' => esc_html__( 'Update Images', 'woo-variation-gallery-pro' )
				) );
				
				wp_enqueue_script( 'gwp-functions', $this->assets_uri( "/js/gwp-functions{$suffix}.js" ), array( 'jquery', 'wp-util' ), $this->version(), true );
				
				wp_localize_script( 'gwp-functions', 'gwp_functions_data', array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce(),
				) );
				
				do_action( 'woo_variation_gallery_pro_admin_enqueue_scripts', $this );
			}
			
			public function hooks() {
				
				if ( $this->is_org_version_active() ) {
					
					// add_action( 'admin_notices', array( $this, 'add_license_notice' ) );
					add_action( 'admin_init', array( $this, 'updater' ) );
					
					add_filter( 'body_class', array( $this, 'body_class' ) );
					//add_action( 'admin_footer', array( $this, 'admin_template_js' ) );
					//add_action( 'wp_footer', array( $this, 'gallery_template_js' ) );
					add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
					add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
					add_filter( 'plugin_action_links_' . $this->basename(), array( $this, 'plugin_action_links' ) );
					
					add_action( 'in_plugin_update_message-' . $this->basename(), array( $this, 'plugin_update_message_row' ), 10, 2 );
					
				} else {
					add_action( 'admin_notices', array( $this, 'org_version_requirement_notice' ) );
				}
				
				do_action( 'woo_variation_gallery_pro_hooks', $this );
			}
			
			public function plugin_update_message_row( $plugin_data, $response ) {
				
				if ( empty( $response->package ) ) {
					
					$license_key  = trim( get_option( 'woo_variation_gallery_license' ) );
					$license_link = esc_url( $this->license_page_link() );
					
					if ( empty( $license_key ) ) {
						printf( ' <strong>Please <a href="%s">add license key</a> to get automatic updates.</strong>', $license_link );
					}
				}
			}
			
			public function updater() {
				if ( class_exists( 'Woo_Variation_Gallery_Pro_Updater' ) ) {
					$license = trim( get_option( 'woo_variation_gallery_license' ) );
					new Woo_Variation_Gallery_Pro_Updater( __FILE__, 1850, $license );
				}
			}
			
			public function license_page_link() {
				
				return add_query_arg( array(
					                      'tab'     => 'woo-variation-gallery',
					                      'page'    => 'wc-settings',
					                      'section' => 'license',
				                      ), admin_url( 'admin.php' ) );
				
				
			}
			
			public function add_license_notice() {
				if ( ! get_option( 'woo_variation_gallery_license' ) ) {
					$license_link = esc_url( $this->license_page_link() );
					
					$download_link = esc_url( 'https://getwooplugins.com/my-account/downloads/' );
					
					echo '<div class="notice notice-error"><p><strong>Warning!</strong> you didn\'t add license key for <strong>Additional Variation Images Gallery for WooCommerce - Pro</strong> which means you\'re missing automatic updates.</p> <p>Please <a href="' . esc_url( $license_link ) . '"><strong>Add License Key</strong></a> and don\'t forget to collect License Key from <a target="_blank" href="' . esc_url( $download_link ) . '"><strong>My Downloads</strong></a> page</p></div>';
				}
			}
			
			public function body_class( $classes ) {
				array_push( $classes, 'woo-variation-gallery-pro' );
				
				return array_unique( $classes );
			}
			
			public function plugin_action_links( $links ) {
				
				$new_links = array();
				
				$settings_link = esc_url( add_query_arg( array(
					                                         'page' => 'wc-settings',
					                                         'tab'  => 'woo-variation-gallery'
				                                         ), admin_url( 'admin.php' ) ) );
				
				$new_links[ 'settings' ] = sprintf( '<a href="%1$s" title="%2$s">%2$s</a>', $settings_link, esc_attr__( 'Settings', 'woo-variation-gallery' ) );
				
				return array_merge( $links, $new_links );
			}
			
			public function is_required_php_version() {
				return version_compare( PHP_VERSION, '5.6.0', '>=' );
			}
			
			public function is_required_wc_version() {
				return version_compare( WC_VERSION, '4.5', '>' );
			}
			
			public function org_version_requirement_notice() {
				
				$class = 'notice notice-error';
				
				$text    = esc_html__( 'Additional Variation Images for WooCommerce', 'woo-variation-gallery-pro' );
				$link    = esc_url( add_query_arg( array(
					                                   'tab'       => 'plugin-information',
					                                   'plugin'    => 'woo-variation-gallery',
					                                   'TB_iframe' => 'true',
					                                   'width'     => '640',
					                                   'height'    => '500',
				                                   ), admin_url( 'plugin-install.php' ) ) );
				$message = wp_kses( __( "<strong>Additional Variation Images for WooCommerce - Pro</strong> is an add-on of ", 'woo-variation-gallery-pro' ), array( 'strong' => array() ) );
				
				printf( '<div class="%1$s"><p>%2$s <a class="thickbox open-plugin-details-modal" href="%3$s"><strong>%4$s</strong></a></p></div>', $class, $message, $link, $text );
				
			}
			
			public function is_org_version_active() {
				return class_exists( 'Woo_Variation_Gallery' ) && class_exists( 'WooCommerce' );
			}
			
			public function language() {
				load_plugin_textdomain( 'woo-variation-gallery-pro', false, trailingslashit( WOO_VG_PRO_PLUGIN_DIRNAME ) . 'languages' );
			}
			
			public function is_wc_active() {
				return class_exists( 'WooCommerce' );
			}
			
			public function basename() {
				return WOO_VG_PRO_PLUGIN_BASENAME;
			}
			
			public function dirname() {
				return WOO_VG_PRO_PLUGIN_DIRNAME;
			}
			
			public function version() {
				return esc_attr( $this->_version );
			}
			
			public function plugin_path() {
				return untrailingslashit( plugin_dir_path( __FILE__ ) );
			}
			
			public function plugin_uri() {
				return untrailingslashit( plugins_url( '/', __FILE__ ) );
			}
			
			public function images_uri( $file ) {
				$file = ltrim( $file, '/' );
				
				return WOO_VG_PRO_IMAGES_URI . $file;
			}
			
			public function assets_uri( $file ) {
				$file = ltrim( $file, '/' );
				
				return WOO_VG_PRO_ASSETS_URI . $file;
			}
			
			public function plugin_row_meta( $links, $file ) {
				if ( $file == $this->basename() ) {
					
					$report_url = add_query_arg( array(
						                             'utm_source'   => 'wp-admin-plugins',
						                             'utm_medium'   => 'row-meta-link',
						                             'utm_campaign' => 'woo-variation-gallery'
					                             ), 'https://getwooplugins.com/tickets/' );
					
					$documentation_url = add_query_arg( array(
						                                    'utm_source'   => 'wp-admin-plugins',
						                                    'utm_medium'   => 'row-meta-link',
						                                    'utm_campaign' => 'woo-variation-gallery'
					                                    ), 'https://getwooplugins.com/documentation/woocommerce-variation-gallery/' );
					
					$row_meta[ 'documentation' ] = sprintf( '<a target="_blank" href="%1$s" title="%2$s">%2$s</a>', esc_url( $documentation_url ), esc_html__( 'Read Documentation', 'woo-variation-gallery' ) );
					$row_meta[ 'issues' ]        = sprintf( '%2$s <a target="_blank" href="%1$s">%3$s</a>', esc_url( $report_url ), esc_html__( 'Facing issue?', 'woo-variation-gallery' ), '<span style="color: red">' . esc_html__( 'Please open a ticket.', 'woo-variation-gallery' ) . '</span>' );
					
					return array_merge( $links, $row_meta );
				}
				
				return (array) $links;
			}
			
			public function get_theme_name() {
				return wp_get_theme()->get( 'Name' );
			}
			
			public function get_parent_theme_dir() {
				return strtolower( basename( get_template_directory() ) );
			}
			
			public function get_parent_theme_name() {
				return wp_get_theme( get_template() )->get( 'Name' );
			}
			
			public function get_theme_dir() {
				return strtolower( basename( get_stylesheet_directory() ) );
			}
		}
		
		function woo_variation_gallery_pro() {
			return Woo_Variation_Gallery_Pro::instance();
		}
		
		add_action( 'plugins_loaded', 'woo_variation_gallery_pro', 15 );
	
	endif;