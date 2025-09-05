<?php

namespace CSPProductArchive;

/**
 * This class contains the implementation of the following feature,
 * * A shortcode to list all the user sppecific discounted products.
 */

if (!class_exists('CustomerSpecificProductArchive')) {
	class CustomerSpecificProductArchive {
		
		/**
		 * Constructs the class to create the shortcodes,
		 * * To display the products with the specal pricin options.
		 */
		public function __construct() {
			add_shortcode('csp-products-for-user', array($this, 'listAllTheProductsWithSpecialPrice'));
		}


		public function cspRemoveSortingByPrice( $orderBy ) {
			/**
			 * This filter can be used to enable/disable price sorting in csp special shop page.
			 * the filtering is disabled by default as woocommerce uses product meta for quicker price sorting,
			 * the same cannot be implemented using CSP price or it will be slower.
			 * 
			 * @param bool $disabled default True.
			 */
			if (apply_filters('csp-archive-disable-price-sorting', true)) {
				unset($orderBy['price']);
				unset($orderBy['price-desc']);
			}
			return $orderBy;
		}

		/**
		 * This method is called when the shortcode 'csp-products-for-user' is used,
		 * * Fetches all the ids of the products with CSP applied.
		 * * Use the woocommerce default shortcode ['products'] with limit & the pagination option,
		 * & displays the products accordingly.
		 * * When the user is not signed in displays the message "Please log in to see the Special Offers for you"
		 * * When no products with CSP are available for the user displays the message, 
		 * "Oops no special offers available"
		 *
		 * @param array $args
		 * @return void
		 */
		public function listAllTheProductsWithSpecialPrice( $args) {
			
			$cspSettings = get_option('wdm_csp_settings');
			$limit    	 = isset($args['limit'])?(int) $args['limit']:10;
			$paginate 	 = isset($args['paginate'])?(string) $args['paginate']:true;
			$columns  	 = isset($args['columns'])?(int) $args['columns']:4;
			
			if (!is_user_logged_in()) {
				$signInRequiredText = isset($cspSettings['csp_archive_signed_in_required_text'])?$cspSettings['csp_archive_signed_in_required_text']:esc_html__('Please log in to see the Special Offers for you', 'customer-specific-pricing-for-woocommerce');
				ob_start();
				?>
				<div class='csp-archive-not-notice'>
					<span><?php esc_html_e($signInRequiredText); ?></span>
				</div>
				<?php
				return ob_get_clean();
			}

			$userId   = get_current_user_id();
			$userRoles	= self::getUserRoles($userId);
			$userGroups	= self::getUserGroupIds($userId);
			add_filter('woocommerce_catalog_orderby', array($this, 'cspRemoveSortingByPrice'), 20 , 1);

			//Checks if global discounts enabled if enabled and rules exists list all the products
			$globalDiscountsActive = 'enable'==get_option('wdm_csp_gd_status', 'disable')?true:false;
			/**
			 * This filter can be used to disable showing the products with global discounts on the special shop page.
			 * 
			 * @param bool $globalDiscountsActive does the global discounts feature of CSP active.
			 */
			$globalDiscountsActive = apply_filters('csp_archive_show_products_with_global_discounts', $globalDiscountsActive);
			if ($globalDiscountsActive) {
				include_once CSP_PLUGIN_PATH . '/includes/global-discount/class-global-discount-data-store.php';
				$gdRulesForUser = \cspGlobalDiscounts\WisdmGlobalDiscountDataStore::checkIfRulesExistsFor($userId, $userRoles, $userGroups);
				if (0<$gdRulesForUser) {
					ob_start();
					echo do_shortcode('[products paginate="' . $paginate . '" limit="' . $limit . '" columns="' . $columns . '"]');
					return ob_get_clean();
				}
			}
			
			include_once 'csp-data/class-csp-applied-product-ids.php';
			$cspAppliedProductIds = new CSPData\CSPAppliedProductIds($userId, $userRoles, $userGroups);
			$productIds           = $cspAppliedProductIds->getUniqueProductIds();
			$productIds           = implode(', ', $productIds);
			if (empty($productIds)) {
				$noOffersText = isset($cspSettings['csp_archive_no_offers_text'])?$cspSettings['csp_archive_no_offers_text']:esc_html__('Oops! No special offers available currently', 'customer-specific-pricing-for-woocommerce');
				ob_start();
				?>
				<div class='csp-archive-not-notice'>
					<span><?php esc_html_e($noOffersText); ?></span>
				</div>
				<?php
				return ob_get_clean();
			}
			ob_start();
			echo do_shortcode('[products paginate="' . $paginate . '" limit="' . $limit . '" columns="' . $columns . '" ids="' . $productIds . '"]');
			return ob_get_clean();
		}

		/**
		 * Fetches the user roles by the provided user Id.
		 *
		 * @since 4.5.0
		 * @param int $userId
		 * @return array - array of role slugs
		 */
		public static function getUserRoles( $userId) {
			$userRoles	= array();
			$userMeta	= get_userdata($userId);
			if (!empty($userMeta)) {
				$userRoles	= $userMeta->roles;
			}
			return $userRoles;
		}

		/**
		 * Finds the groups the user with the specified user id belogns to
		 * and returns an array of group ids for the user.
		 *
		 * @since 4.5.0
		 * @param int $userId
		 * @return array
		 */
		public static function getUserGroupIds( $userId) {
			$groupIds = array();
			if (!is_user_logged_in() || !defined('GROUPS_CORE_VERSION')) {
				return $groupIds;
			}
			global $wpdb;
			$userGroupId        = $wpdb->get_results($wpdb->prepare('SELECT group_id FROM ' . $wpdb->prefix . 'groups_user_group WHERE user_id=%d', $userId));
			foreach ($userGroupId as $groupId) {
				$groupIds[]		= $groupId->group_id;
			}
			return $groupIds;
		}
	}
}
