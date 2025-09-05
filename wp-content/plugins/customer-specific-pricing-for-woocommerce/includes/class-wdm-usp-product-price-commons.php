<?php

namespace WuspSimpleProduct;

//use WuspGetData as cspGetData;

//check whether a class with the same name exists
if (! class_exists('WuspCSPProductPriceCommons')) {
	/**
	 * Class contains common variables and their getters & setters used in class WuspSCPProductPrice
	 */
	class WuspCSPProductPriceCommons {
	
		private $currentAddToCartId      ='';
		private $insideShopLoop          = false;
		public $skipCSPForProductsInCart = array();
		public $groupIdsForTheUser       = array();
		public $cspSettings              = array();
		private $uspCatCache             = array();
		private $rspCatCache             = array();
		private $gspCatCache             = array();

		
		/**
		 * Getter & setters for $currentAddToCartId
		 */
		public function getcurrentAddToCartId() {
			return $this->currentAddToCartId;
		}
		public function setcurrentAddToCartId( $productId) {
			$this->currentAddToCartId =$productId;
		}

		/**
		 * Getter & setters for $insideShopLoop
		 */
		public function setInsideShopLoopFlag() {
			$this->insideShopLoop = true;
		}

		public function unsetInsideShopLoopFlag() {
			$this->insideShopLoop = false;
		}

		public function getInsideShopLoopFlag() {
			return $this->insideShopLoop;
		}


		/**
		 * This function checks if the sent product variations id is on sale or not according to the following logic.
		 * 1. Product variation has sale price set and it is less than regular price.
		 * 2. Product variations current price is equal to the sale price (This happens only during the sale schedule).
		 *
		 * @since 4.2.4         - Created to check wether product variation is on sale while resolving bug #54282
		 * @param array $prices - Current,Regular,Sale prices of all the variations of the product
		 * @param int $vId      - id of the variation.
		 * @return bool true when variation is on sale.
		 */
		public function variationSaleEnabled( $prices, $vId) {
			$cspSettings  			= $this->getCSPSettings(); 
			$salePrice    			= !empty($prices['price'][$vId])?(float) $prices['price'][$vId]:'';
			$regularPrice 			= !empty($prices['regular_price'][$vId])?(float) $prices['regular_price'][$vId]:'';
			$isStrikeThroughEnabled = isset($cspSettings['enable_striketh']) && 'enable' == $cspSettings['enable_striketh'] ? true : false;
			if (''!=$salePrice && $salePrice<$regularPrice && $isStrikeThroughEnabled) {
				if ($prices['price'][$vId]==$salePrice) {
					return true;
				}
			}
			return false;
		}


		 /**
		 * Adds regular price before the pricing table(provided html) & returns the modified html.
		 *
		 * @since 4.6.0
		 * @param string $table - table html
		 * @param array $cspSettings - CSP settings array
		 * @return string html containing pricing table
		 */
		public function wdmAddRegularPriceNearPriceTable( $cspSettings, $product) {
			$regularPriceHtml = '';
			$regularPrice     = $product->get_regular_price();
			if (!wc_prices_include_tax()) {
				$regularPrice = wc_get_price_including_tax($product, array('price' => $regularPrice));
			}
			/**
			 * Filter regulkar price to be displayed in Regular price section of CSP when enabled.
			 * 
			 * @param float $regularPrice Regular price of the product.
			 * @param object $product woocommerce product object.
			 */
			$regularPrice = apply_filters('wisdm_csp_regular_price_for_display', $regularPrice, $product);
			if (isset($cspSettings['show_regular_price']) && 'enable'==$cspSettings['show_regular_price'] && !empty($regularPrice)) {
				$regularPriceText =isset($cspSettings['regular_price_display_text'])?$cspSettings['regular_price_display_text']:'Regular Price';
				$regularPriceHtml ="<div class='qty-fieldset-regular-price'>$regularPriceText " . wc_price($regularPrice) . ' ' . $product->get_price_suffix($regularPrice, 1) . '</div>';
				/**
				 * This filter can be used to filter the regular price html displayed by CSP.
				 * 
				 * @param string $regularPriceHtml Html for the regular price display.
				 * @param string $RegularPriceText Text to be displayed in front of the regular price in CSP settings.
				 * @param float $regularPrice Actual regular price of the product.
				 * @param int $productId WooCommerce Product ID.
				 */
				$regularPriceHtml =apply_filters('wdm_csp_regular_price_html', $regularPriceHtml, $regularPriceText, $regularPrice, $product->get_id());
			}
			return $regularPriceHtml;
		}

		/**
		 * This method returns the discription to be displayed on the product page
		 * if saved in the settings returns empty string if not available.
		 *
		 * @since 4.3.0
		 * @param array $cspSettings - CSP settings array
		 * @param int $productId - wc_product id
		 * @return string $cspDiscriptionHtml
		 */
		public function wdmGetCSPDiscriptionIfAvailable( $cspSettings, $productId) {
			$cspDiscriptionHtml ='';
			if (!empty($cspSettings['csp_discription_text'])) {
				$cspDiscriptionHtml ="<div class='csp-discription-text'>" . $cspSettings['csp_discription_text'] . '</div>';
				/**
				 * Filter CSP description 
				 * 
				 * @param string $cspDescriptionHtml 
				 * @param string $descriptionText Description text saved in the CSP settings.
				 */
				$cspDiscriptionHtml = apply_filters('wdm_csp_discription_html', $cspDiscriptionHtml, $cspSettings['csp_discription_text'], $productId);
			}
			return $cspDiscriptionHtml;
		}


		/**
		 * Retrives CSP prices of the product for the user &
		 * returns the CSP for thespecified quantity for the user.
		 *
		 * @since 4.3.0
		 * @param int $productId	- WC_Product ID
		 * @param float $price		- current / regular price of the product
		 * @param int $quantity		- quantity to get the price based on the quantity
		 * @param int $userId		- user id of the user to fetch CSP for that user
		 * @return float $price
		 */
		public function wdmGetCSPrice( $price, $productId, $quantity, $userId) {
			$price = WuspCSPProductPrice::getDBPrice($productId, $price, $quantity, $userId);
			return $price;
		}


		 /**
		 * This method is hooked to the 'woocommerce_cart_item_price' hook,
		 * which is used to update the price multipier in the woocommerce minicart
		 * (The cart displayed on hovering over the store cart icon)
		 *
		 * @param float $price			-  current / regular price of the product
		 * @param object $cart_item		- wc_cart_item object
		 * @param string $cart_item_key - wc_cart_item Key
		 * @return string $price
		 */
		public function filterMiniCartPrice( $price, $cart_item, $cart_item_key) {
			/**
			 * Filter to manage CSP price application in the minicart & item price.
			 * 
			 * @param bool $applyCSP to apply CSP or not.
			 * @param int $cart_item_key cart product id.
			 * @param object $cart_item WooCommerce cart item object.
			 */
			$applyCSPinMiniCart = apply_filters('wdm_csp_apply_quantity_price_in_mini_cart', true, $cart_item_key, $cart_item);
			
			if ($applyCSPinMiniCart) {
				$old_price 		 = $price;
				$cart_product_id = !empty($cart_item['variation_id']) ? $cart_item['variation_id'] : $cart_item['product_id'];
				$price           = WuspCSPProductPrice::getDBPrice($cart_product_id, $cart_item['data']->get_price(), $cart_item['quantity']);
				
				/**
				 * This filter can be used to change the CSP price calculated for the cart item & the price of the product in the minicart
				 * 
				 * @param float $price CSP price calculated fo the product.
				 * @param float $old_price Price before applying the CSP price.
				 * @param object $cart_item WC cart item object.
				 */
				$price = apply_filters('wdm_csp_filter_mini_cart_csp_item_price', $price, $old_price, $cart_item);
				$price = wc_price($price);	
			}
			return $price; 
		}


		/**
		 * Fetches CSP settings array from the cache or from the database.
		 * & returns the same
		 * 
		 * @since 4.4.3
		 * @return array $cspSettings - CSP Settings Array 
		 */
		public function getCSPSettings() {
			if ( !empty($this->cspSettings)) {
				return $this->cspSettings;
			}
			$cspSettings       = get_option('wdm_csp_settings');
			$this->cspSettings = $cspSettings;
			return $cspSettings;
		}

		/**
		 * Returns the group ids to which the user belong
		 *
		 * @since 4.4.3
		 * @param int $userId       - Id of the WordPress user.
		 * @return array $groupIds	- aray of group ids the user belongs to.
		 */
		public function getGroupIdsForTheUser( $userId) {
			$groupIds = array();
			if (!is_user_logged_in() || !defined('GROUPS_CORE_VERSION')) {
				return $groupIds;
			}
			if (!empty($this->groupIdsForTheUser[$userId])) {
				return $this->groupIdsForTheUser[$userId];
			}
			global $wpdb;
			$userGroupId = $wpdb->get_results($wpdb->prepare('SELECT group_id FROM ' . $wpdb->prefix . 'groups_user_group WHERE user_id=%d', $userId));
			foreach ($userGroupId as $groupId) {
				$groupIds[] = $groupId->group_id;
			}
			$this->groupIdsForTheUser[$userId] = $groupIds;
			return $groupIds;
		}

		/**
		 * $prices is an array with prices associated with all the variation Ids
		 * This method replaces the prices for the variation ids present in the 
		 * arguement $cspPrices .
		 *  
		 * @since 4.4.3
		 * @param	array	$prices		- priceArray of variable product variations
		 * @param	array 	$cspPrices	- user/role/group specific product|category prices
		 * @return	array	$prices		- modified priceArray with prices for variations found in $cspPrices
		 */
		public function replaceWithCspPrices( $prices, $cspPrices) {
			if (!empty($cspPrices)) {
				foreach ($cspPrices as $vid => $price) {
					$prices[$vid] = (string) wc_format_decimal($price);
				}
			}
			return $prices;
		}

		/**
		 * For each csp rule of a type (usp, rsp, gsp)
		 * * calculates the csp price according to the rule.
		 * * if calculated price is lesser than the previosly calculated price replaces the price
		 * * returns the filtered array of prices 
		 *
		 * @since	4.4.3
		 * @param	array $rules 			- (USP|RSP|GSP)
		 * @param	array $prices			- regular & sale prices of the products
		 * @param	array $variantsToSkip	- product ids for which the csp price is not required 
		 * @return	array $cspPrices		- Prices calulated by CSP rules
		 */
		public function cspCalculatePrices( $rules, $prices, $product, $variantsToSkip = array()) {
			$cspPrices = array();

			if (!empty($rules) && !empty($prices)) {
				foreach ($rules as $aRule) {
					$vid = $aRule->product_id;
					if (\in_array($vid, $variantsToSkip)) {
						continue;
					}
					$cspPrice = $this->getCspFromRule( $aRule, $prices[$vid], $vid, $product);
					if (!empty($cspPrices[$vid])) {
						$cspPrices[$vid] =$cspPrices[$vid]<$cspPrice?$cspPrices[$vid]:$cspPrice;
					} else {
						$cspPrices[$vid] =$cspPrice;
					}
				}
			}
			return $cspPrices;
		}

		/**
		 * For each csp rule of a type (usp, rsp, gsp)
		 * * calculates the csp price according to the rule.
		 * * if calculated price is lesser than the previosly calculated price replaces the price
		 * * returns the filtered array of prices 
		 *
		 * @since 4.4.3
		 * @param array $rules 			- (USP|RSP|GSP) - array or rule object
		 * @param array $prices			- Regular & sale prices of the products
		 * @param array $variantsToSkip - product ids for which the csp price is not required 
		 * @return array $cspPrices 	- array of CSP prices
		 */
		public function cspCalculateCategoryPrices( $rules, $prices, $variantsToSkip = array()) {
			$cspPrices = array();
			if (!empty($rules) && !empty($prices)) {
				foreach ($prices as $vid => $price) {
					if (\in_array($vid, $variantsToSkip)) {
						continue;
					}
					foreach ($rules as $aRule) {
						$cspPrice = $this->getCspFromRule( $aRule, $price, $vid);
						if (!empty($cspPrices[$vid])) {
							$cspPrices[$vid] =$cspPrices[$vid]<$cspPrice?$cspPrices[$vid]:$cspPrice;
						} else {
							$cspPrices[$vid] =$cspPrice;
						}	
					}
				}
			}
			return $cspPrices;
		}

		/**
		 * Calculates & returns the CSP price for the given
		 * CSP rule & price of the product
		 *
		 * @since	4.4.3
		 * @param	object	$rule	 - CSP Rule object
		 * @param	array	$price	 - Current price of the product
		 * @param	object  $product - WooCommerce Product object
		 * @return	float			- Calculated price according to the CSP rule
		 */
		public function getCspFromRule( $rule, $price, $vid, $product = '') {
			// 1=> Flat Pricing, 2=> % Discount. 
			if (2==$rule->price_type) {
				$discountValue = $rule->price;
				$price         = $price - ( $price * $discountValue/100 );
			} else {
				$price			  		= $rule->price;
				// TODO: Remove the code post merge request.
				// $taxedPriceInShop 		= 'incl'===get_option( 'woocommerce_tax_display_shop' )?true:false; 
				// $priceEnteredWithTax	= wc_prices_include_tax();
				// if (!empty($product)) {
				// 	if ($taxedPriceInShop && !$priceEnteredWithTax) {
				// 		$price = wc_get_price_including_tax($product, array('price' => $rule->price));
				// 	}
					
				// 	if (!$taxedPriceInShop && $priceEnteredWithTax) {
				// 		$price = wc_get_price_excluding_tax($product, array('price' => $rule->price));		
				// 	}
				// }
			}

			/**
			 * This filter can be used to filter the CSP calculated price for the price display on the variableproduct page.
			 * 
			 * @param float $price price of the variation.
			 * @param int $vid id of a product variation.
			 * @param object $rule CSP rule Object.
			 * @param object $variableProduct parent variable product object.
			 */
			return apply_filters('wdm_csp_variation_price_for_variable_price_range', $price, $vid, $rule, $product);
		}


		/**
		 * Fetches & returns an array of user specific CSP rules for the current user
		 * & for specified variation ids for quantity one.
		 *
		 * @since 4.4.3
		 * @param	array	$productIds	- Array of product Ids
		 * @param	array	$userId		- User Id of the user for which csp needs to be calculated
		 * @return	array	$uspRules	- Role Specifc Pricing Rules For The Products Specified in $productIds
		 */
		public function getAllUspRulesForUser( $productIds, $userId) {
			global $wpdb;
			$uspRules = array();
			if (!empty($productIds)) {
				$prepareArgs = array_merge((array) $userId, (array) $productIds);
				$uspRules    = $wpdb->get_results($wpdb->prepare('SELECT id, product_id, user_id, price, min_qty, flat_or_discount_price as price_type FROM ' . $wpdb->prefix . 'wusp_user_pricing_mapping WHERE user_id = %d AND product_id IN(' . implode(', ', array_fill(0, count((array) $productIds), '%d')) . ') AND min_qty=1', $prepareArgs));
				/**
				 * Filter to filter the CSP user specific pricing rules for the product.
				 * 
				 * @param array $uspRules array of CSP rules.
				 * @param int $userId Id of the user for which these rules are fetched.
				 * @param array $productIds {
				 * 		@type int $productId 
				 * }
				 */
				$uspRules = apply_filters('wdm_csp_filter_usp_rules_for_a_product', $uspRules, $userId, $productIds);
			}
			return $uspRules;
		}

		/**
		 * Fetches & returns an array of role specific CSP rules for the current user
		 * & for specified variation ids for quantity one.
		 * 
		 * @since 4.4.3
		 * @param	array	$productIds 	- Array of product Ids
		 * @param	array	$roles			- Array of user role slugs
		 * @return	array					- Role Specifc Pricing Rules For The Products Specified in $productIds
		 */
		public function getAllRspRulesForUser( $productIds, $roles) {
			global $wpdb;
			$rspRules = array();
			if (!empty($productIds) && !empty($roles)) {
				$prepareArgs = array_merge((array) $roles, (array) $productIds);
				$rspRules    = $wpdb->get_results($wpdb->prepare('SELECT product_id, role, price, min_qty, flat_or_discount_price as price_type FROM ' . $wpdb->prefix . 'wusp_role_pricing_mapping WHERE role IN (' . implode(', ', array_fill(0, count((array) $roles), '%s')) . ') AND product_id IN(' . implode(', ', array_fill(0, count((array) $productIds), '%d')) . ') AND min_qty=1', $prepareArgs));
				
				/**
				 * Filter to filter the CSP role specific pricing rules for the product.
				 * 
				 * @param array $rspRules array of CSP rules.
				 * @param array $roles role slugs for which these rules are fetched.
				 * @param array $productIds {
				 * 		@type int $productId 
				 * }
				 */
				$rspRules =  apply_filters('wdm_csp_filter_rsp_rules_for_a_product', $rspRules, $roles, $productIds);
			}
			return $rspRules;
		}

		/**
		 * Fetches & returns an array of group specific CSP rules for the current user
		 * & for specified variation ids for quantity one.
		 *
		 * @since 4.4.3
		 * @param	array	$productIds		- Array of product Ids
		 * @param	array	$groupIds		- Array of user group ids
		 * @return	array 					- Group Specific Pricing Rules For The Products Specified in $productIds
		 */
		public function getAllGspRulesForUser( $productIds, $groupIds) {
			global $wpdb;
			$gspRules = array();
			if (!empty($productIds) && !empty($groupIds)) {
				$prepareArgs = array_merge((array) $groupIds, (array) $productIds);	
				$gspRules    = $wpdb->get_results($wpdb->prepare('SELECT product_id, group_id, price, min_qty, flat_or_discount_price as price_type FROM ' . $wpdb->prefix . 'wusp_group_product_price_mapping WHERE group_id IN(' . implode(', ', array_fill(0, count((array) $groupIds), '%d')) . ') AND product_id IN(' . implode(', ', array_fill(0, count((array) $productIds), '%d')) . ') AND min_qty=1', $prepareArgs));
				
				/**
				 * Filter to filter the CSP group specific pricing rules for the product.
				 * 
				 * @param array $gspRules array of group specific CSP rules.
				 * @param array $groups group Ids for which these rules are fetched.
				 * @param array $productIds {
				 * 		@type int $productId 
				 * }
				 */
				$gspRules = apply_filters('wdm_csp_filter_gsp_rules_for_a_product', $gspRules, $groupIds, $productIds);
			}			
			return $gspRules;
		}



		/**********************************
		 * User Specific Category Pricing *
		 **********************************/
		/**
		 * Fetches & returns an array of user specific CSP rules for the current user
		 * & for specified product categories for quantity one.
		 *
		 * @since 4.4.3
		 * @param array $catSlugs - array of category slugs
		 * @param int $userId - WordPress user id 
		 * @return array $uspRules array of CSP rule objects
		 */
		public function getAllUspCatRulesForUser( $catSlugs, $userId) {
			global $wpdb;
			$uspRules = array();
			if (!empty($catSlugs)) {
				$prepareArgs = array_merge((array) $userId, (array) $catSlugs);
				$uspRules    = $this->getCatCache('user', $prepareArgs);
				if (false===$uspRules) {
					$uspRules = $wpdb->get_results($wpdb->prepare('SELECT user_id, price, min_qty, cat_slug, flat_or_discount_price as price_type, "set" as price_set FROM ' . $wpdb->prefix . 'wcsp_user_category_pricing_mapping WHERE user_id = %d AND cat_slug IN(' . implode(', ', array_fill(0, count((array) $catSlugs), '%s')) . ') AND min_qty=1', $prepareArgs));
					
					/**
					 * Filter to filter the CSP user specific pricing rules for the categories.
					 * 
					 * @param array $uspRules array of CSP rules.
					 * @param array $userId user Id for which these rules are fetched.
					 * @param array $catSlugs {
					 * 		@type string $catSlug 
					 * }
					 */
					$uspRules = apply_filters('wdm_csp_filter_usp_qty_one_rules_for_categories', $uspRules, $userId, $catSlugs);
					$this->setCatCache('user', $prepareArgs, $uspRules);
				}	
			}
			return $uspRules;
		}


		/**********************************
		 * Role Specific Category Pricing *
		 **********************************/
		/**
		 * Fetches & returns an array of role specific CSP rules for the current user
		 * & for specified product categories for quantity one.
		 *
		 * @since 4.4.3
		 * @param array $catSlugs - aray of product category slugs
		 * @param array $roles - array of user role slugs
		 * @return array $rspRules - array of role specific pricing rule objects
		 */
		public function getAllRspCatRulesForUser( $catSlugs, $roles) {
			global $wpdb;
			$rspRules = array();
			if (!empty($catSlugs) && !empty($roles)) {
				$prepareArgs = array_merge((array) $roles, (array) $catSlugs);
				$rspRules    = $this->getCatCache('role', $prepareArgs);
				if (false===$rspRules) {
					$rspRules = $wpdb->get_results($wpdb->prepare('SELECT role, price, min_qty, cat_slug, flat_or_discount_price as price_type, "set" as price_set FROM ' . $wpdb->prefix . 'wcsp_role_category_pricing_mapping WHERE role IN (' . implode(', ', array_fill(0, count((array) $roles), '%s')) . ') AND cat_slug IN(' . implode(', ', array_fill(0, count((array) $catSlugs), '%s')) . ') AND min_qty=1', $prepareArgs));	
					/**
					 * Filter to filter the CSP role specific pricing rules for the categories.
					 * 
					 * @param array $uspRules array of CSP rules.
					 * @param array $roles role slugs for which these rules are fetched.
					 * @param array $catSlugs {
					 * 		@type string $catSlug 
					 * }
					 */
					$rspRules = apply_filters('wdm_csp_filter_rsp_qty_one_rules_for_categories', $rspRules, $roles, $catSlugs);
					$this->setCatCache('role', $prepareArgs, $rspRules);
				}
			}
			return $rspRules;
		}


		/***********************************
		 * Group Specific Category Pricing *
		 ***********************************/
		/**
		 * Fetches & returns an array of group specific CSP rules for the current user
		 * & for specified product categories for quantity one.
		 *
		 * @since 4.4.3
		 * @param array $catSlugs - array of product category slugs
		 * @param array $groupIds - array of user group ids
		 * @return array $gspRules- array of group specific pricing rule objects
		 */
		public function getAllGspCatRulesForUser( $catSlugs, $groupIds) {
			global $wpdb;
			$gspRules = array();
			if (!empty($catSlugs) && !empty($groupIds)) {
				$prepareArgs = array_merge((array) $groupIds, (array) $catSlugs);	
				$gspRules    = $this->getCatCache('group', $prepareArgs);
				if (false===$gspRules) {
					$gspRules = $wpdb->get_results($wpdb->prepare('SELECT group_id, price, min_qty, cat_slug, flat_or_discount_price as price_type, "set" as price_set FROM ' . $wpdb->prefix . 'wcsp_group_category_pricing_mapping WHERE group_id IN(' . implode(', ', array_fill(0, count((array) $groupIds), '%d')) . ') AND cat_slug IN(' . implode(', ', array_fill(0, count((array) $catSlugs), '%s')) . ') AND min_qty=1', $prepareArgs));	
					/**
					 * Filter to filter the CSP group specific pricing rules for the categories.
					 * 
					 * @param array $gspRules array of CSP rules.
					 * @param array $groupIds group Ids for which these rules are fetched.
					 * @param array $catSlugs {
					 * 		@type string $catSlug 
					 * }
					 */
					$gspRules = apply_filters('wdm_csp_filter_gsp_qty_one_rules_for_categories', $gspRules, $groupIds, $catSlugs);
					$this->setCatCache('group', $prepareArgs, $gspRules);
				}
			}
			return $gspRules;
		}



		/****************************************************************
		 * Implementation of rule caching for category specific pricing *
		 ****************************************************************/
		/**
		 * This method is used to check if the rules for category
		 * are fethed previously & catched if the rules are cached
		 * then return the rules else returns false
		 *
		 * @param array $keyCandidates an array used to form the unique cache key, these are the cominations of unique items used to identify the rule
		 * @return mixed - array of cached rules for users/user roles/user groups.
		 */
		private function getCatCache( $ruleType, $keyCandidates) {
			sort($keyCandidates);
			$cacheKey = \sha1(implode('_', $keyCandidates));

			switch ($ruleType) {
				case 'user':
					if (isset($this->uspCatCache[$cacheKey])) {
						return $this->uspCatCache[$cacheKey];
					}
					break;
				case 'role':
					if (isset($this->rspCatCache[$cacheKey])) {
						return $this->rspCatCache[$cacheKey];
					}
					break;
				case 'group':
					if (isset($this->gspCatCache[$cacheKey])) {
						return $this->gspCatCache[$cacheKey];
					}
					break;
			}
			return false;
		}

		/**
		 * This method is used to save the rules for category
		 * in a cache
		 * 
		 * @param array $keyCandidates an array used to form the unique cache key, these are the cominations of unique items used to identify the rule
		 * @return void
		 */
		private function setCatCache( $ruleType, $keyCandidates, $rulesData) {
			sort($keyCandidates);	
			$cacheKey = \sha1(implode('_', $keyCandidates));
			switch ($ruleType) {
				case 'user':
						$this->uspCatCache[$cacheKey] = $rulesData;
					break;
				case 'role':
						$this->rspCatCache[$cacheKey] = $rulesData;
					break;
				case 'group':
						$this->gspCatCache[$cacheKey] = $rulesData;
					break;
			}
		}

		/**
		 * This function checks if all the prices of the product variations of the variable product are same,
		 * If all the prices are same returns true, else returns false.
		 * This check is performed to manage the display of the price on variation selection.
		 * 
		 * @since 4.5.0
		 * @param array $cspPrices - All the CSP prices for all the variations for quantity one
		 * @return bool true|false
		 */
		public static function doesAllPricesForQtyOneAreEqual( $cspPrices) {
			$equal = false;
			$price = '';
			foreach ($cspPrices as $productId => $priceArray) {
				$qtyOnePrice = $priceArray[1];
				if (''==$price || $price==$qtyOnePrice) {
					$price = $qtyOnePrice;
					$equal = true;
				} else {
					$equal = false;
					break;
				}
			}
			return $equal;
		}
	}
}
