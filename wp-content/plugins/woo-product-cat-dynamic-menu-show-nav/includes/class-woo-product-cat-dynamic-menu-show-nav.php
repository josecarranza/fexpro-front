<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.webindiainc.com
 * @since      1.0.0
 *
 * @package    Woo_Product_Cat_Dynamic_Menu_Show_Nav
 * @subpackage Woo_Product_Cat_Dynamic_Menu_Show_Nav/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_Product_Cat_Dynamic_Menu_Show_Nav
 * @subpackage Woo_Product_Cat_Dynamic_Menu_Show_Nav/includes
 * @author     Vishal <vishalrathod@webindiainc.com>
 */
class Woo_Product_Cat_Dynamic_Menu_Show_Nav {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Woo_Product_Cat_Dynamic_Menu_Show_Nav_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WOO_PRODUCT_CAT_DYNAMIC_MENU_SHOW_NAV_VERSION' ) ) {
			$this->version = WOO_PRODUCT_CAT_DYNAMIC_MENU_SHOW_NAV_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'woo-product-cat-dynamic-menu-show-nav';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Woo_Product_Cat_Dynamic_Menu_Show_Nav_Loader. Orchestrates the hooks of the plugin.
	 * - Woo_Product_Cat_Dynamic_Menu_Show_Nav_i18n. Defines internationalization functionality.
	 * - Woo_Product_Cat_Dynamic_Menu_Show_Nav_Admin. Defines all hooks for the admin area.
	 * - Woo_Product_Cat_Dynamic_Menu_Show_Nav_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-product-cat-dynamic-menu-show-nav-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-product-cat-dynamic-menu-show-nav-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woo-product-cat-dynamic-menu-show-nav-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woo-product-cat-dynamic-menu-show-nav-public.php';

		//Custom file for cron set only menually
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-product-cat-dynamic-menu-show-nav-trigger-cron-prodcut-attribute.php';


		$this->loader = new Woo_Product_Cat_Dynamic_Menu_Show_Nav_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Woo_Product_Cat_Dynamic_Menu_Show_Nav_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Woo_Product_Cat_Dynamic_Menu_Show_Nav_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Woo_Product_Cat_Dynamic_Menu_Show_Nav_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	//	$this->loader->add_action('product_cat_add_form_fields', $plugin_admin,'text_domain_taxonomy_add_new_meta_field');
	//	$this->loader->add_action('product_cat_edit_form_fields', $plugin_admin,'text_domain_taxonomy_edit_meta_field');


	//	$this->loader->add_action('edited_product_cat', $plugin_admin,'save_taxonomy_custom_meta');
	//	$this->loader->add_action('create_product_cat', $plugin_admin,'save_taxonomy_custom_meta');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Woo_Product_Cat_Dynamic_Menu_Show_Nav_Public( $this->get_plugin_name(), $this->get_version() );


		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		//$this->loader->add_shortcode( 'storeFronPrimaryMenu', $plugin_public, 'getStoreFronPrimaryMenu' );
		//$this->loader->add_filter( 'wp_nav_menu_items', $plugin_public, 'addNewMenuItemBasedOnCategory', 10,2 );

		//$this->loader->add_filter('wp_nav_menu_objects',$plugin_public, 'ad_filter_menu', 10, 2);




	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Woo_Product_Cat_Dynamic_Menu_Show_Nav_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
