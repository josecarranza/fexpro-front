<?php

namespace wdmCSPPromotion;

/**
 * Promotions
 *
 * To register the Quick Course Module.
 *
 * @package Advanced_Modules
 *
 * @since   1.0
 *
 *
 */
if (!class_exists('CspPromotion')) {

	class CspPromotion {
	
		private static $instance = null;
		// private $pluginSlug = 'csp';

		/**
		 * Private constructor to make this a singleton
		 *
		 */
		public function __construct() {
			add_action('csp_single_view_promotions', array($this, 'cspNewFeaturesPromotionPage'), 10);
			add_action('admin_enqueue_scripts', array($this, 'cspLoadStyles'));
		}
		/**
		 * Function to instantiate our class and make it a singleton
		 */
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * This function outputs the html content required for presenting the newly added
		 * features in the current plugin release. 
		 *
		 * @return void
		 */
		public function cspNewFeaturesPromotionPage() {
			update_option('csp-update-status', 'visited_whats_new_page');
			$promotionsDirPath = plugin_dir_url(__FILE__, '/includes/promotion');
			include_once 'whats-new-page.php';
		}


		public function cspLoadStyles() {
			$pluginSlug = 'csp';
			
			if (!empty($_GET['page']) && 'wisdm-csp-whats-new' == $_GET['page']) {
				// $min = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG === true) ? '':'.min';
				wp_register_style($pluginSlug . '-promotion', plugins_url('assets/css/extension.css', __FILE__), array(), CSP_VERSION);
				// Enqueue admin styles
				wp_enqueue_style($pluginSlug . '-promotion');
			}
		}

		// End of functions
	}
	CspPromotion::getInstance();
}
