<?php

namespace cspGlobalDiscounts;

if (!class_exists('GlobalDiscountApplication')) {
	include_once CSP_PLUGIN_PATH . '/includes/global-discount/class-global-discount-data-store.php';
	/**
	 * This class contains the application logic for the global discounts to be applied
	 * Some commonly used abbreviations in this class
	 * * USP - User  Specific Pricing
	 * * RSP - Role  Specific Pricing
	 * * GSP - Group Specific Pricing
	 *
	 * @since 4.5.0
	 */
	class GlobalDiscountApplication extends WisdmGlobalDiscountDataStore {
		/**
		 * Used to indicate wether feature Global Discounts is Enabled or not
		 *
		 * @var boolean
		 */
		public static $featureStatus = false;
		/**
		 * Used to indicate wether category discounts are enabled or not.
		 *
		 * @var boolean
		 */
		public static $categoryPricingEnabled = true;

		public function __construct() {
			self::updateFeatureStatuses();
			$this->initHooksForGlobalDiscounts();
		} 
		
		/**
		 * When called this function updates the values of the
		 * variables used to indicate status of the features
		 *
		 * @return void
		 */
		protected static function updateFeatureStatuses() {
			self::$featureStatus          = 'enable'==get_option('wdm_csp_gd_status', 'disable')?true:false;
			self::$categoryPricingEnabled = 'enable'==get_option('cspCatPricingStatus', 'enable')?true:false;
		}
		
		/**
		 * This method hooks the different global discounting functions to 
		 * the different hooks according to the feature statuses.
		 * this is used to manage the behaviour of the feature when category specific
		 * pricing is enabled.  
		 *
		 * @return void
		 */
		public function initHooksForGlobalDiscounts() {
			if (!self::$featureStatus) {
				return ;
			}
			if (self::$categoryPricingEnabled) {
				add_filter('wdm_csp_filter_usp_rules_for_categories', array($this, 'filterCategoryUSPsforGD'), 11, 4);
				add_filter('wdm_csp_filter_rsp_rules_for_categories', array($this, 'filterCategoryRSPsforGD'), 10, 4);
				add_filter('wdm_csp_filter_gsp_rules_for_categories', array($this, 'filterCategoryGSPsforGD'), 10, 4);

				// Special functions wriiten for quantity one rules to speedup the page loading
				add_filter('wdm_csp_filter_usp_qty_one_rules_for_categories', array($this, 'filterCategoryUSPQtyOneRulesforGD'), 11, 3);
				add_filter('wdm_csp_filter_rsp_qty_one_rules_for_categories', array($this, 'filterCategoryRSPQtyOneRulesforGD'), 10, 3);
				add_filter('wdm_csp_filter_gsp_qty_one_rules_for_categories', array($this, 'filterCategoryGSPQtyOneRulesforGD'), 10, 3);
				
			} else {
				add_filter('wdm_csp_filter_usp_rules_for_a_product', array($this, 'filterProductUSPsforGD'), 10, 3);
				add_filter('wdm_csp_filter_rsp_rules_for_a_product', array($this, 'filterProductRSPsforGD'), 10, 3);
				add_filter('wdm_csp_filter_gsp_rules_for_a_product', array($this, 'filterProductGSPsforGD'), 10, 3);
			}
		}
		

		/**
		 * This method is hooked to the filter 'wdm_csp_filter_usp_rules_for_categories'
		 * fetches the user specific global discount rules for the user
		 * & returns the merged array of rules.
		 * 
		 * @param array $uspRules- array of user specific pricing rules
		 * @param int $currentUserId - user id of the currently logge dinuser
		 * @param array $catArray - array of product category slugs
		 * @param int $productId - product Id for which the rules are being fetched now
		 * @return array $uspRules - Array of merged global discounts with category usp rules
		 */
		public function filterCategoryUSPsforGD( $uspRules, $currentUserId, $catArray, $productId = '') {
			$gdRules			= self::getUSPRulesFor( $currentUserId);
			$existingQuantities	= $this->getQuantitiesFormRules($uspRules);
			$filteredGdRules	= $this->filterGdRulesForCategories($gdRules, $existingQuantities);
			$uspRules			= array_merge($uspRules, $filteredGdRules);
			return $uspRules;
		}
		
		/**
		 * This method is hooked to the filter 'wdm_csp_filter_rsp_rules_for_categories'
		 * fetches the user specific global discount rules for the user
		 * & returns the merged array of rules.
		 *
		 * @param array $rspRules - array of role specific pricing rules
		 * @param array $roleSlugs - array of user role slugs
		 * @param array $catArray - array of category slugs
		 * @param int $productId - product Id for which the rules are being fetched now
		 * @return array $rspRules - Array of merged global discounts with category rsp rules
		 */
		public function filterCategoryRSPsforGD( $rspRules, $roleSlugs, $catArray, $productId = '') {
			$gdRules  			= self::getRSPRulesFor($roleSlugs);
			$existingQuantities	= $this->getQuantitiesFormRules($rspRules);
			$filteredGdRules	= $this->filterGdRulesForCategories($gdRules, $existingQuantities);
			$rspRules 			= array_merge($rspRules, $filteredGdRules);
			return $rspRules;
		}

		/**
		 * This method is hooked to the filter 'wdm_csp_filter_rsp_rules_for_categories'
		 * fetches the user specific global discount rules for the user
		 * & returns the merged array of rules.
		 *
		 * @param array $gspRules - group specific pricing rules
		 * @param array $groupIds - Array of user group ids
		 * @param array $catArray - Array of category slugs
		 * @param int $productId - product Id for which the rules are being fetched now
		 * @return array $gspRules - Array of merged global discounts with category gsp rules
		 */
		public function filterCategoryGSPsforGD( $gspRules, $groupIds, $catArray, $productId = '') {
			$gdRules  			= self::getGSPRulesFor($groupIds);
			$existingQuantities	= $this->getQuantitiesFormRules($gspRules);
			$filteredGdRules	= $this->filterGdRulesForCategories($gdRules, $existingQuantities);
			$gspRules 			= array_merge($gspRules, $filteredGdRules);
			return $gspRules;
		}


		/**
		 * This method is hooked to the filter 'wdm_csp_filter_usp_qty_one_rules_for_categories'
		 * fetches the user specific global discount rules with quantity one for the user
		 * & returns the merged array of rules.
		 *
		 * @param array $uspRules - user specific pricing rules
		 * @param array $currentUserId - user id of the currently logge din user
		 * @param array $catArray - Array of category slugs
		 * @return array $uspRules - Array of merged global discounts with category usp rules having minimum quantity 1
		 */
		public function filterCategoryUSPQtyOneRulesforGD( $uspRules, $currentUserId, $catArray) {
			$gdRules			= self::getUSPRulesFor( $currentUserId);
			$gdRules			= self::getRulesHavingMinQtyOne($gdRules);
			$existingQuantities	= $this->getQuantitiesFormRules($uspRules);
			$filteredGdRules	= $this->filterGdRulesForCategories($gdRules, $existingQuantities);
			$uspRules			= array_merge($uspRules, $filteredGdRules);
			return $uspRules;
		}

		/**
		 * This method is hooked to the filter 'wdm_csp_filter_rsp_qty_one_rules_for_categories'
		 * fetches the role specific global discount rules with quantity one for the user
		 * & returns the merged array of rules.
		 *
		 * @param array $rspRules - role specific pricing rules
		 * @param array $currentUserId - user id of the currently logge din user
		 * @param array $catArray - Array of category slugs
		 * @return array $rspRules - Array of merged global discounts with category rsp rules having minimum quantity 1
		 */
		public function filterCategoryRSPQtyOneRulesforGD( $rspRules, $roleSlugs, $catArray) {
			$gdRules			= self::getRSPRulesFor( $roleSlugs);
			$gdRules			= self::getRulesHavingMinQtyOne($gdRules);
			$existingQuantities	= $this->getQuantitiesFormRules($rspRules);
			$filteredGdRules	= $this->filterGdRulesForCategories($gdRules, $existingQuantities);
			$rspRules			= array_merge($rspRules, $filteredGdRules);
			return $rspRules;
		}

		/**
		 * This method is hooked to the filter 'wdm_csp_filter_gsp_qty_one_rules_for_categories'
		 * fetches the group specific global discount rules with quantity one for the user
		 * & returns the merged array of rules.
		 *
		 * @param array $gspRules - group specific pricing rules
		 * @param array $currentUserId - user id of the currently logge din user
		 * @param array $catArray - array of category slugs
		 * @return array $gspRules - Array of merged global discounts with category gsp rules having minimum quantity 1
		 */
		public function filterCategoryGSPQtyOneRulesforGD( $gspRules, $groupIds, $catArray) {
			$gdRules			= self::getGSPRulesFor( $groupIds);
			$gdRules			= self::getRulesHavingMinQtyOne($gdRules);
			$existingQuantities	= $this->getQuantitiesFormRules($gspRules);
			$filteredGdRules	= $this->filterGdRulesForCategories($gdRules, $existingQuantities);
			$gspRules			= array_merge($gspRules, $filteredGdRules);
			return $gspRules;
		}
		
		
		/**
		 * This method is hooked to the filter 'wdm_csp_filter_usp_rules_for_a_product'
		 * fetches the user specific global discount rules for the user
		 * & returns the merged array of rules.
		 * 
		 * @param array $uspRules -  user specific pricing rules
		 * @param int 	$currentUserId - user Id fo the currently logged in user
		 * @param array $productIds - array of wc_product product ids
		 * @return array $uspRules - Array of merged global discounts with product level usp rules 
		 */
		public function filterProductUSPsforGD( $uspRules, $userId, $productIds) {
			$gdRules = self::getUSPRulesFor( $userId);
			//Variable Product Variations
			if (is_array($productIds)) {
				$newGdRules 	= array();
				$gdRules 		= self::getRulesHavingMinQtyOne($gdRules);
				foreach ($productIds as $productId) {
					if (!empty($gdRules)) {
						$uspQuantitiesForProduct	= $this->getQuantitiesFormRulesForProduct($uspRules, $productId);	
						$filteredGdRules			= $this->filterGdRulesForProduct($gdRules, $uspQuantitiesForProduct, $productId);
						foreach ($filteredGdRules as $gdRule) {
							$gdRule               	= (array) $gdRule;
							$gdRule['product_id'] 	= $productId;
							$newGdRules[]         	= (object) $gdRule;
						}
					}
				}
				$uspRules = \array_merge($uspRules, $newGdRules);
			} else {
				$uspQuantitiesForProduct= $this->getQuantitiesFormRulesForProduct($uspRules, $productIds);
				$filteredGdRules		= $this->filterGdRulesForProduct($gdRules, $uspQuantitiesForProduct, $productIds); 
				$uspRules 				= \array_merge($uspRules, $filteredGdRules);
			}

			return $uspRules;
		}
		
		/**
		 * This method is hooked to the filter 'wdm_csp_filter_rsp_rules_for_a_product'
		 * fetches the user specific global discount rules for the user
		 * & returns the merged array of rules.
		 * 
		 * @param array $rspRules - role specific pricing rule array
		 * @param array $roleSlugs - array of user role slugs
		 * @param array $productIds - array of product ids to fetch GSP rules for.
		 * @return array $rspRules - Array of merged global discounts with product level rsp rules
		 */
		public function filterProductRSPsforGD( $rspRules, $roleSlugs, $productIds) {
			$gdRules = self::getRSPRulesFor( $roleSlugs);
			if (is_array($productIds)) {
				$newGdRules = array();
				$gdRules = self::getRulesHavingMinQtyOne($gdRules);
				foreach ($productIds as $productId) {
					if (!empty($gdRules)) {
						$rspQuantitiesForProduct	= $this->getQuantitiesFormRulesForProduct($rspRules, $productId);
						$filteredGdRules			= $this->filterGdRulesForProduct($gdRules, $rspQuantitiesForProduct, $productId);
						foreach ($filteredGdRules as $gdRule) {
							$gdRule               = (array) $gdRule;
							$gdRule['product_id'] = $productId;
							$newGdRules[]         = (object) $gdRule;
						}
					}
				}
				$rspRules = \array_merge($rspRules, $newGdRules);
			} else {
				$rspQuantitiesForProduct= $this->getQuantitiesFormRulesForProduct($rspRules, $productIds);
				$filteredGdRules		= $this->filterGdRulesForProduct($gdRules, $rspQuantitiesForProduct, $productIds); 
				$rspRules				= \array_merge($rspRules, $filteredGdRules);
			}
			return $rspRules;
		}
		

		/**
		 * This method is hooked to the filter 'wdm_csp_filter_gsp_rules_for_a_product'
		 * fetches the user specific global discount rules for the user
		 * & returns the merged array of rules.
		 * 
		 * @param array $gspRules - group specific pricing rule array
		 * @param array $groupIds - array of user group ids
		 * @param array $productIds - array of product ids to fetch GSP rules for.
		 * @return array $gspRules - Array of merged global discounts with product level gsp rules
		 */
		public function filterProductGSPsforGD( $gspRules, $groupIds, $productIds) {
			$gdRules = self::getGSPRulesFor( $groupIds);
			if (is_array($productIds)) {
				$newGdRules = array();
				$gdRules = self::getRulesHavingMinQtyOne($gdRules);
				foreach ($productIds as $productId) {
					if (!empty($gdRules)) {
						$gspQuantitiesForProduct	= $this->getQuantitiesFormRulesForProduct($gspRules, $productId);
						$filteredGdRules			= $this->filterGdRulesForProduct($gdRules, $gspQuantitiesForProduct, $productId);
						foreach ($filteredGdRules as $gdRule) {
							$gdRule               = (array) $gdRule;
							$gdRule['product_id'] = $productId;
							$newGdRules[]         = (object) $gdRule;
						}
					}
				}
				$gspRules = \array_merge($gspRules, $newGdRules);
			} else {
				$gspQuantitiesForProduct= $this->getQuantitiesFormRulesForProduct($rspRules, $productIds);
				$filteredGdRules 		= $this->filterGdRulesForProduct($gdRules, $gspQuantitiesForProduct, $productIds); 
				$gspRules				= \array_merge($gspRules, $filteredGdRules);
			}
			return $gspRules;
		}


		/**
		 * This method goes through all the rules & returns the array of 
		 * quantities for the productID in the array form array($productId=>array(1,2,5))
		 *
		 * @param array		$rules	- Array of CSP rules
		 * @param int		$productId - wc_product id
		 * @param string	$qtyField optional - to generalize the function for picking up unique field values in the csp rules array passed
		 * @return array	$quantities rule Quantities for the given product id. 
		 */
		private function getQuantitiesFormRulesForProduct( $rules, $productId, $qtyField = '') {
			$quantities = array();
			if (!empty($rules)) {
				foreach ($rules as $aRule) {
					if (!isset($aRule->product_id) || $productId == $aRule->product_id) {
						$quantities[$productId][] = $aRule->min_qty;
					}
				}
			}
			return $quantities;
		}


		/**
		 * This function picks the quantity from each of the rules
		 * passed & returns an array of quantities
		 *
		 * @param array $cspRules - array of CSP rule objects
		 * @return array $quantities - array of unique values in the field 'min_qty' in the rules passed
		 */
		private function getQuantitiesFormRules( $cspRules) {
			$quantities = array();
			if (!empty($cspRules)) {
				foreach ($cspRules as $aRule) {
					if (!in_array($aRule->min_qty, $quantities)) {
						$quantities[]	= $aRule->min_qty;
					}
				}
			}
			return $quantities;
		}


		/**
		 * This method is written to prioratize the product level rules over the global discount rules,
		 * this method checks if the product level rules for quantity are defined, if yes then remove the
		 * rules having same quantity from the global discount rules array and return the rules having
		 * different quantities.
		 *
		 * @param array $gdRules - Array of global discount rules
		 * @param array $quantitiesInExistingRules - Array of quantities existing in the rules
		 * @param int $productId - wc_product id
		 * @return array $filteredGdRules - array of rules that does not have existing min_qty values
		 */
		public function filterGdRulesForProduct( $gdRules, $quantitiesInExistingRules, $productId) {
			$filteredGdRules	= array();	
			if (!isset($quantitiesInExistingRules[$productId]) || empty($quantitiesInExistingRules[$productId])) {
				return $gdRules;
			}
			if (!empty($gdRules)) {
				foreach ($gdRules as $gdRule) {
					if (!in_array($gdRule->min_qty, $quantitiesInExistingRules[$productId])) {
						$filteredGdRules[] = $gdRule;
					}
				}
			}
			return $filteredGdRules;
		}


		/**
		 * This method filters global discounts for the categories such that,
		 * only the global discount rules with the quantities which are not
		 * present in the existing rules will be returned. 
		 *
		 * @param array $cspRules - array of CSP rule objects
		 * @param array $existingQuantities - Array of quantities existing in the rules
		 * @return array $filteredGdRules - array of rules that does not have existing min_qty values
		 */
		public function filterGdRulesForCategories( $cspRules, $existingQuantities) {
			$filteredGdRules	= array();
			if (!empty($cspRules) && !empty($existingQuantities)) {
				foreach ($cspRules as $aRule) {
					if (!\in_array($aRule->min_qty, $existingQuantities)) {
						$filteredGdRules[]	=  $aRule;
					}
				}
			} else {
				$filteredGdRules = $cspRules;
			}
			return $filteredGdRules;
		}


		/**
		 * From the given list of rules filters the rules having
		 * minimum quantity for discount 'One'(1)
		 * 
		 * @param array $rules - array of CSP rules Object
		 * @return array $rulesWithMinQtyOne - Array of CSP rules having minimum quantity one
		 */
		public static function getRulesHavingMinQtyOne( $rules) {
			$rulesWithMinQtyOne	= array();
			foreach ($rules as $aRule) {
				if (1==$aRule->min_qty) {
					$rulesWithMinQtyOne[]=$aRule;
				}
			}
			return $rulesWithMinQtyOne;
		}

	} 
}
