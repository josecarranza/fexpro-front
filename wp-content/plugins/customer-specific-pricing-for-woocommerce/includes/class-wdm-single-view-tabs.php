<?php

namespace SingleView;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('WdmShowTabs')) {
	/**
	* Class for showing tabs of CSP.
	*/
	class WdmShowTabs {
	
		 /**
		 * Constructor that Adds the Menu Page action
		 * Loads scripts for import tab.
		 * Display admin notices for import tabs.
		 * Include the files for the tabs of CSP :
		 * Product-pricing,import-export,category-pricing
		 */
		public function __construct() {
			global $singleView, $categoryPricing;
			global $importExport;

			add_action('current_screen', array($this, 'csp_conditional_includes'));
			add_action('admin_init', array($this, 'loadUploadWdmCsp'));
			add_action('admin_init', array($this, 'addPluginRowMeta'));
			add_action('admin_menu', array($this, 'cspPageInit'), 99);

			if (is_admin()) {
				include_once 'single-view/class-wdm-single-view.php';
				$singleView = new \cspSingleView\WdmSingleView();
				//including file Import/Export functionality
				include_once 'import-export/class-wdm-wusp-import-export.php';
				$importExport = new \cspImportExport\WdmWuspImportExport();

				include_once 'category-pricing/class-wdm-wusp-category-pricing.php';
				$categoryPricing = new \cspCategoryPricing\WdmWuspCategoryPricing();

				include_once 'settings/class-wdm-csp-general-settings.php';
				$generalSettings = new \CSPGenSettings\WdmCSPGeneralSettings();

				include_once 'feedback/class-wdm-csp-feedback.php';
				$generalSettings = new \CSPFeedbackTab\WdmCSPFeedbackTab();

				include_once 'cart-discount/class-wdm-csp-cart-discount.php';
				$generalSettings = new \CSPCartDiscount\WdmCSPCartDiscount();
				include_once 'promotion/init.php';

				add_action('csp_global_discounts_tab', array($this, 'loadGlobalDiscountSettings'), 99);

			}
		}

		/**
		* Load the Scripts for the import tab.
		* Gets the current tab , if it is import enqueue scripts.
		*/
		public function loadUploadWdmCsp() {
			$currentTab = $this->getCurrentTab();
			
			$cspSettings 	  = get_option('wdm_csp_settings');
			$isOldImportInUse = ( !empty($cspSettings['csp_use_old_import']) && 'enable'==$cspSettings['csp_use_old_import'] );

			if ('wisdm-csp-import' == $currentTab && $isOldImportInUse) {
				wp_enqueue_script(
					'wdm_csp_import_js',
					plugins_url('/js/import-js/wdm-csp-import.js', dirname(__FILE__)),
					array('jquery'),
					CSP_VERSION
				);

				wp_localize_script(
					'wdm_csp_import_js',
					'wdm_csp_import',
					array(
						'admin_ajax_path'     => admin_url('admin-ajax.php'),
						'import_nonce'        => wp_create_nonce('import_nonce'),
						'header_text'         => __('Import Pricing Rules', 'customer-specific-pricing-for-woocommerce'),
						'loading_image_path'  => plugins_url('/images/loading .gif', dirname(__FILE__)),
						'loading_text'        => __('Importing . .(Please do not close this window until the  import is finished)', 'customer-specific-pricing-for-woocommerce'),
						'import_successfull'  => __('File Imported Successfully', 'customer-specific-pricing-for-woocommerce'),
						'total_no_of_rows'    => __('Total number of rows found ', 'customer-specific-pricing-for-woocommerce'),
						'total_insertion'     => __('. Total number of rows inserted ', 'customer-specific-pricing-for-woocommerce'),
						'total_updated'       => __(', total number of rows updated ', 'customer-specific-pricing-for-woocommerce'),
						'total_skkiped'       => __(', and total number of rows skipped ', 'customer-specific-pricing-for-woocommerce'),
						'import_page_url'     => menu_page_url('customer_specific_pricing_single_view', false) . '&tabie=import',
						'templates_url'       => plugins_url('/templates/', dirname(__FILE__)),
						'user_specific_sample'  => __('User Specific Sample', 'customer-specific-pricing-for-woocommerce'),
						'role_specific_sample'  => __('Role Specific Sample', 'customer-specific-pricing-for-woocommerce'),
						'group_specific_sample' => __('Group Specific Sample', 'customer-specific-pricing-for-woocommerce'),
						/* translators: HelpContext*/
						'HelpContext'			=> sprintf(__('%1$sRecords in a Batch%2$s : specifies number of records to process at a time. %3$s %4$sSimultaneous Batches%5$s : specifies number of such batches to process at the same time.', 'customer-specific-pricing-for-woocommerce'), '<b>', '</b>', '<br>', '<b>', '</b>'),
						)
				);
			}
		}

		/**
		 * Function To add menu page and sub menu page for csp
		 *
		 * @return [void]
		 */
		public function cspPageInit() {
			global $singleViewPage;
			$pluginVersion  = defined('CSP_VERSION')?CSP_VERSION:'';
			/**
			 * Determine which user should be capable of accessing the CSP admin menu page.
			 * 
			 * @param string $capability wordpress capability of the user default, manage_options(Admin).
			 */
			$capability		= apply_filters('wisdm_csp_menu_page_capability', 'manage_options');
			$menuPageTitle	= esc_html__('CSP Administration', 'customer-specific-pricing-for-woocommerce');
			$singleViewPage = add_menu_page($menuPageTitle, 'CSP', $capability, 'customer_specific_pricing_single_view', array(
				$this,
				'productPricingMenuContent'
			), plugins_url('images/usp_icon.png', dirname(__FILE__)), 58);

			$submenuTitles['product_pricing']	= __('Product Pricing', 'customer-specific-pricing-for-woocommerce');
			$submenuTitles['search_by']			= __('Search By & Delete', 'customer-specific-pricing-for-woocommerce');
			$submenuTitles['cat_pricing']		= __('Category Pricing', 'customer-specific-pricing-for-woocommerce');
			$submenuTitles['cart_discounts']	= __('Cart Discounts', 'customer-specific-pricing-for-woocommerce');
			$submenuTitles['global_discounts']	= __('Global Discounts', 'customer-specific-pricing-for-woocommerce');
			$submenuTitles['import']			= __('Import', 'customer-specific-pricing-for-woocommerce');
			$submenuTitles['export']			= __('Export', 'customer-specific-pricing-for-woocommerce');
			$submenuTitles['settings']			= __('Settings', 'customer-specific-pricing-for-woocommerce');
			$submenuTitles['whats_new']			= __('What\'s New', 'customer-specific-pricing-for-woocommerce');
			$submenuTitles['fedback']			= __('Feedback', 'customer-specific-pricing-for-woocommerce');
			
			add_submenu_page('customer_specific_pricing_single_view', $submenuTitles['product_pricing'], $submenuTitles['product_pricing'], $capability, 'customer_specific_pricing_single_view', array($this, 'productPricingMenuContent'));
			add_submenu_page('customer_specific_pricing_single_view', $submenuTitles['search_by'], $submenuTitles['search_by'], $capability, 'wisdm-csp-search-by', array($this, 'searchRulesMenuContent'));
			add_submenu_page('customer_specific_pricing_single_view', $submenuTitles['cat_pricing'], $submenuTitles['cat_pricing'], $capability, 'wisdm-csp-cat-pricing', array($this, 'categoryPricingMenuContent'));
			add_submenu_page('customer_specific_pricing_single_view', $submenuTitles['cart_discounts'], $submenuTitles['cart_discounts'], $capability, 'wisdm-csp-cart-discounts', array($this, 'cartDiscountsMenuContent'));
			add_submenu_page('customer_specific_pricing_single_view', $submenuTitles['global_discounts'], $submenuTitles['global_discounts'], $capability, 'wisdm-csp-global-discounts', array($this, 'globalDiscountsMenuContent'));
			add_submenu_page('customer_specific_pricing_single_view', $submenuTitles['import'], $submenuTitles['import'], $capability, 'wisdm-csp-import', array($this, 'importMenuContent'));
			add_submenu_page('customer_specific_pricing_single_view', $submenuTitles['export'], $submenuTitles['export'], $capability, 'wisdm-csp-export', array($this, 'exportMenuContent'));
			add_submenu_page('customer_specific_pricing_single_view', $submenuTitles['settings'], $submenuTitles['settings'], $capability, 'wisdm-csp-settings', array($this, 'settingsMenuContent'));
			add_submenu_page('customer_specific_pricing_single_view', $submenuTitles['whats_new'], $submenuTitles['whats_new'], $capability, 'wisdm-csp-whats-new', array($this, 'whatsNewPageContent'));
			add_submenu_page('customer_specific_pricing_single_view', $submenuTitles['fedback'], $submenuTitles['fedback'], $capability, 'wisdm-csp-fedback', array($this, 'showFeedbackForm'));

			wp_register_style('csp-common-single-view', plugins_url('single-view/assets/css/wdm-common-single-view.css', __FILE__), array(), $pluginVersion);
		}

		/**
		 * Shows the various tabs in CSP.
		 *
		 * @param string $current current tab name.
		 * @return [void]
		 */
		public function singleViewShowTabs( $current = 'import') {
			$tabs = array(
				'customer_specific_pricing_single_view'=> __('Product Pricing', 'customer-specific-pricing-for-woocommerce'),
				'wisdm-csp-search-by'=> __('Search By & Delete', 'customer-specific-pricing-for-woocommerce'),
				'wisdm-csp-cat-pricing'=> __('Category Pricing', 'customer-specific-pricing-for-woocommerce'),
				'wisdm-csp-cart-discounts'=> __('Cart Discounts', 'customer-specific-pricing-for-woocommerce'),
				'wisdm-csp-global-discounts'=> __('Global Discounts', 'customer-specific-pricing-for-woocommerce'),
				'wisdm-csp-import'=> __('Import', 'customer-specific-pricing-for-woocommerce'),
				'wisdm-csp-export'=> __('Export', 'customer-specific-pricing-for-woocommerce'),
				'wisdm-csp-settings'=>  __('Settings', 'customer-specific-pricing-for-woocommerce'),
				'wisdm-csp-whats-new'=>  __('What\'s New', 'customer-specific-pricing-for-woocommerce'),
			);

			?>
			<h2 class="nav-tab-wrapper">
			<?php
			foreach ($tabs as $tab => $name) {
				// echo $name;
				$class = ( $tab == $current ) ? ' nav-tab-active' : '';
				echo '<a class="nav-tab' . esc_attr($class) . '" href="admin.php?page=' . esc_attr($tab) . '">' .
				esc_html($name) . '</a>';
			}
			?>
			</h2>
			<?php
		}

		/**
		* Returns the current tab.
		*
		* @return string $currentTab current tab.
		*/
		public function getCurrentTab() {
			global $pagenow;
			
			static $validCSPPages = array(
				'customer_specific_pricing_single_view',
				'wisdm-csp-search-by',
				'wisdm-csp-cat-pricing',
				'wisdm-csp-cart-discounts',
				'wisdm-csp-global-discounts',
				'wisdm-csp-import',
				'wisdm-csp-export',
				'wisdm-csp-settings',
				'wisdm-csp-whats-new',
				'wisdm-csp-fedback',
			);


			$pageRequested = isset($_GET['page'])?sanitize_text_field($_GET['page']):'';
			if ('admin.php'== $pagenow && in_array($pageRequested, $validCSPPages)) {
				return $pageRequested;
			}

			return 'customer_specific_pricing_single_view';
		}
		

		/**
		 * This method is hooked to admin edit hook & adds extra meta links to the
		 * plugin listing on the admin dashboard.
		 *
		 * @since 4.4.3
		 * @return void
		 */
		public function addPluginRowMeta() {
			include_once CSP_PLUGIN_PATH . '/includes/class-wdm-plugin-links.php';
			add_filter('plugin_row_meta', array('WisdmPluginLinks\Links','cspPluginRowMeta'), 10, 2 );
		}

		/**
		 * This method is hooked to current_screen hook and adds an upgrade notice before update to the
		 * plugin listing on the admin dashboard.
		 *
		 * @param object $screen Screen Object.
		 * @since 4.6.3
		 * @return void
		 */
		public function csp_conditional_includes( $screen ) {
			if ( ! $screen ) {
				return;
			}

			switch ( $screen->id ) {
				case 'plugins':
					include_once CSP_PLUGIN_PATH . '/includes/update-notice/class-wdm-admin-plugin-updates.php';
					break;
			}
		}


		/**
		 * Displays the CSP Poll link
		 * 
		 * @since 4.4.4
		 */
		public function getFloatingSideButtonHtml() {
			wp_enqueue_style('csp-common-single-view');
			$message = __('1-Question Feedback', 'customer-specific-pricing-for-woocommerce');
			?>
			<div class="csp-poll-bar">
				<a href="https://surveys.hotjar.com/s?siteId=769948&surveyId=153645" class="csp-poll" target="_blank"> <?php esc_html_e($message); ?></a> 
			</div>
			<?php
		}

		/**
		 * This function executes an action
		 * responsible to display the feedback form
		 * on the page
		 *
		 * @since 4.5.0
		 * @return void
		 */
		public function showFeedbackForm() {
			/**
			 * This action is being used for loading the Feedback Submenu page content on the WP admin settings,
			 * The same hook can be used to add functionality/html above or under the page.  
			 * 
			 * @since 4.3.0
			 */
			do_action('csp_feedback_tab');
		}

		public function loadGlobalDiscountSettings() {
			include_once 'global-discount/class-global-discount-settings.php';
			\cspGlobalDiscounts\WisdmGlobalDiscountSettings::displaySettingsPage();
		}


		public function cspAdminPageHeaderContent() {
			//Display the poll link as a floating side action button
			$this->getFloatingSideButtonHtml();
			?>
			<div class="wrap">
				<?php
					$currentTab = $this->getCurrentTab();
					$this->singleViewShowTabs($currentTab);
				?>
				<div id="poststuffIE">
			<?php
		}

		/**
		 * This function loads submenu page content for the CSPs Submenu Page.
		 * 
		 * @since 4.6.3
		 */
		public function productPricingMenuContent () {
			global  $singleView;
			$this->cspAdminPageHeaderContent();
			$singleView->cspSingleView();
			$this->cspAdminPageFooterContent();
		}

		/**
		 * This function loads submenu page content for the CSPs Submenu Page.
		 * 
		 * @since 4.6.3
		 */
		public function searchRulesMenuContent() {
			$this->cspAdminPageHeaderContent();
			/**
			 * This action is being used for loading the search by & delete tab on content on the WP admin settings page,
			 * The same hook can be used to add functionality/html above or under the search by & delete page.  
			 */
			do_action('csp_single_view_search_settings');
			$this->cspAdminPageFooterContent();
		}

		/**
		 * This function loads submenu page content for the CSPs Submenu Page.
		 * 
		 * @since 4.6.3
		 */
		public function categoryPricingMenuContent() {
			global $categoryPricing;
			$this->cspAdminPageHeaderContent();
			$categoryPricing->cspShowCategoryPricing();
			$this->cspAdminPageFooterContent();

		}

		/**
		 * This function loads submenu page content for the CSPs Submenu Page.
		 *
		 * @since 4.6.3
		 */
		public function cartDiscountsMenuContent() {
			$this->cspAdminPageHeaderContent();
			/**
			 * This action is being used for loading the Cart Discounts tab on content on the WP admin settings page,
			 * The same hook can be used to add functionality/html above or under the page.  
			 * 
			 * @since 4.3.0
			 */
			do_action('csp_cart_discounts_tab');
			$this->cspAdminPageFooterContent();
		}

		/**
		 * This function loads submenu page content for the CSPs Submenu Page.
		 * 
		 * @since 4.6.3
		 */
		public function globalDiscountsMenuContent() {
			$this->cspAdminPageHeaderContent();
			/**
			 * This action is being used for loading the Global Discounts tab on content on the WP admin settings page,
			 * The same hook can be used to add functionality/html above or under the page.  
			 * 
			 * @since 4.5.0
			 */
			do_action('csp_global_discounts_tab');
			$this->cspAdminPageFooterContent();
		}

		/**
		 * This function loads submenu page content for the CSPs Submenu Page.
		 * 
		 * @since 4.6.3
		 */
		public function importMenuContent() {
			$this->cspAdminPageHeaderContent();
			$cspSettings = get_option('wdm_csp_settings');
			
			if (!empty($cspSettings['csp_use_old_import']) && 'enable'==$cspSettings['csp_use_old_import']) {
				global $importExport;
				$importExport->cspImport();
			} else {
				include_once  CSP_PLUGIN_PATH . '/includes/import-export/import-new/class-wdm-import.php';
				if (class_exists('cspImportExport\Import\WisdmCSPImport')) {
					$importObject = new \cspImportExport\Import\WisdmCSPImport();
					$importObject->showImportPageContent();
				}
			}
			$this->cspAdminPageFooterContent();
		}

		/**
		 * This function loads submenu page content for the CSPs Submenu Page.
		 * 
		 * @since 4.6.3
		 */
		public function exportMenuContent() {
			global $importExport;
			$this->cspAdminPageHeaderContent();
			$importExport->cspExport();
			$this->cspAdminPageFooterContent();
		}

		/**
		 * This function loads submenu page content for the CSPs Submenu Page.
		 * 
		 * @since 4.6.3
		 */
		public function settingsMenuContent() {
			$this->cspAdminPageHeaderContent();
			/**
			 * This action is being used for loading the CSP General Settings tab on content on the WP admin settings page,
			 * The same hook can be used to add functionality/html above or under the page.  
			 */
			do_action('csp_general_settings');
			$this->cspAdminPageFooterContent();
		}

		/**
		 * This function loads submenu page content for the CSPs Submenu Page.
		 * 
		 * @since 4.6.3
		 */
		public function whatsNewPageContent() {
			$this->cspAdminPageHeaderContent();
			/**
			 * This action is being used for loading the promotions & whats new tab on content on the WP admin settinpage,
			 * The same hook can be used to add functionality/html above or under the page.  
			 */
			do_action('csp_single_view_promotions');
			$this->cspAdminPageFooterContent();
		}


		/**
		 * Adds the footer tags to the CSP administration pages.
		 *
		 * @return void
		 */
		public function cspAdminPageFooterContent() {
			?>
				</div><!-- </div "poststuffIE">  -->
			<?php
		}

	} //end of class
} //end of if class exists
