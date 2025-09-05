<?php

namespace cspGlobalDiscounts;

if (!class_exists('WisdmGlobalDiscountSettings')) {
	/**
	 * This class contains the functionality of storing and retriving the data
	 * for the feature Global Discounts.
	 * 
	 * @since 4.5.0
	 */
	class WisdmGlobalDiscountDataStore {
		/**
		 * Static class variable containing the database table name
		 * for the future GlobalDiscounts
		 *
		 * @var static string 
		 */
		public static $tableName = 'wcsp_global_discount_rule_mapping';



		/**
		 * This function queries the database to check wether the global discount rules are exist for
		 * the selected user, user roles and for the usergroups, returns the total number of global
		 * discounting rules exists for user + roles + groups.
		 *
		 * @since 4.5.0
		 * @param int $userId
		 * @param array $userRoles
		 * @param array $userGroups
		 * @return int $ruleCount - No of rules found in total.
		 */
		public static function checkIfRulesExistsFor( $userId, $roleSlugs, $groupIds) {
			$ruleCount = 0;
			global $wpdb;
			if (!empty($userId) && 0<$userId) {
				$ruleCount = $ruleCount + $wpdb->get_results($wpdb->prepare('SELECT COUNT(*) as numberOfRules FROM ' . $wpdb->prefix . 'wcsp_global_discount_rule_mapping WHERE rule_type=%s AND type_id=%d', 'USP', $userId))[0]->numberOfRules;
			}
			if (!empty($roleSlugs)) {
				$ruleCount = $ruleCount + $wpdb->get_results($wpdb->prepare('SELECT COUNT(*) as numberOfRules FROM ' . $wpdb->prefix . 'wcsp_global_discount_rule_mapping WHERE rule_type="RSP" AND type_id IN (' . implode(', ', array_fill(0, count($roleSlugs), '%s')) . ')', $roleSlugs))[0]->numberOfRules;				
			}
			if (!empty($groupIds)) {
				$ruleCount = $ruleCount + $wpdb->get_results($wpdb->prepare('SELECT COUNT(*) as numberOfRules FROM ' . $wpdb->prefix . 'wcsp_global_discount_rule_mapping WHERE rule_type="GSP" AND type_id IN (' . implode(', ', array_fill(0, count((array) $groupIds), '%d')) . ')', (array) $groupIds))[0]->numberOfRules;
			}
			return $ruleCount;
		}

		/**
		 * This function fetches an array of all the user/role/group specific 
		 * global discount rules from the database, based on the parameter 
		 * * USP - User  Specific pricing.
		 * * RSP - Role  Specific pricing.
		 * * GSP - Group Specific pricing.
		 * 
		 * @method static
		 * @param string $type USP|RSP|GSP
		 * @return array $rules An array of CSP rule objects.
		 */
		public static function getAllRulesFor( $type = null) {
			$rules = array();
			global $wpdb;
			if (!empty($type) && in_array($type, array('USP', 'RSP', 'GSP'))) {
				$rules = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wcsp_global_discount_rule_mapping WHERE rule_type=%s', $type));
			}
			return $rules;
		}

		/**
		 * This function fetches the user specific global discount rules
		 * added for the user id specified from the database, and returns 
		 * an array of objects contating a CSP rule.
		 *
		 * @method  static 
		 * @param int $userId
		 * @return array $rules - An array of CSP rule objects.
		 */
		public static function getUSPRulesFor( $userId) {
			$rules = array();
			global $wpdb;
			if (!empty($userId) && 0<$userId) {
				$rules = $wpdb->get_results($wpdb->prepare('SELECT price, min_qty, flat_or_discount_price as price_type, "csp_global" as cat_slug, "set" as price_set  FROM ' . $wpdb->prefix . 'wcsp_global_discount_rule_mapping WHERE rule_type=%s AND type_id=%d', 'USP', $userId));
			}
			return $rules;
		}
		


		/**
		 * This function fetches the role specific global discount rules
		 * added for the role slugs specified from the database, and returns 
		 * an array of objects contating a CSP rule.
		 *
		 * @method static
		 * @param int $roleSlugs- array of use role slugs
		 * @return array $rules - An array of CSP rule objects.
		 */
		public static function getRSPRulesFor( $roleSlugs = null) {
			$rules = array();
			global $wpdb;
			if (!empty($roleSlugs)) {
				$rules = $wpdb->get_results($wpdb->prepare('SELECT price, min_qty, flat_or_discount_price as price_type, "csp_global" as cat_slug, "set" as price_set FROM ' . $wpdb->prefix . 'wcsp_global_discount_rule_mapping WHERE rule_type="RSP" AND type_id IN (' . implode(', ', array_fill(0, count($roleSlugs), '%s')) . ')', $roleSlugs));
				
			}
			return $rules;
		}
		
		/**
		 * This function fetches the role specific global discount rules
		 * added for the group ids specified from the database, and returns 
		 * an array of objects contating a CSP rule.
		 *
		 * @method static 
		 * @param int $userId
		 * @return array $rules - An array of CSP rule objects.
		 */
		public static function getGSPRulesFor( $groupIds = null) {
			$rules = array();
			global $wpdb;
			if (!empty($groupIds)) {
				$rules = $wpdb->get_results($wpdb->prepare('SELECT price, min_qty, flat_or_discount_price as price_type, "csp_global" as cat_slug, "set" as price_set FROM ' . $wpdb->prefix . 'wcsp_global_discount_rule_mapping WHERE rule_type="GSP" AND type_id IN (' . implode(', ', array_fill(0, count((array) $groupIds), '%d')) . ')', (array) $groupIds));
			}
			return $rules;
		}


		/**
		 * This method is used to store all the global discount rules.
		 * * Performs a validation on all the three type of CSP rules.
		 * * Saves only the valid rules to the database. 
		 * 
		 * @method static
		 * @param array $uspRules - User Specific Pricing Rules For Global Discounts
		 * @param array $rspRules - Role Specific Pricing Rules For Global Discounts
		 * @param array $gspRules - Group Specific Pricing Rules For Global Discounts
		 * @return array saves the global discount rules & returns the detailed status of the operation
		 */
		public static function saveDiscountRules( $uspRules, $rspRules, $gspRules) {
			$uspRules = self::validateUserRules($uspRules);
			$rspRules = self::validateRoleRules($rspRules);
			$gspRules = self::validateGroupRules($gspRules);

			return self::saveRulesToDatabaseTable($uspRules, $rspRules, $gspRules);
		}
		


		/**
		 * Saves the rules passed to the database table using the following steps.
		 * * Deletes all the previously stored rules from the database for the entity.
		 * * Saves newly added rules for the entity.
		 * for all the three rule types.
		 * 
		 * @method static
		 * @param array $uspRules - User specific pricing rules
		 * @param array $rspRules - Role specific pricing rules
		 * @param array $gspRules - Group specific pricing rules
		 */
		protected static function saveRulesToDatabaseTable( $uspRules, $rspRules, $gspRules) {
			global $wpdb;
			$saveStatus = array('uspRules'=>0,'rspRules'=>0,'gspRules'=>0);
			
			$wpdb->delete($wpdb->prefix . 'wcsp_global_discount_rule_mapping', array('rule_type' => 'USP'), array('%s'));
			foreach ($uspRules as $aRule) {
				$insertStatus =$wpdb->insert($wpdb->prefix . 'wcsp_global_discount_rule_mapping', array(
					'rule_type'        		=> 'USP',
					'type_id'          		=> $aRule['rule_beneficiary'],
					'min_qty'          		=> $aRule['min_qty'],
					'flat_or_discount_price'=> $aRule['discount_type'],
					'price'                   => $aRule['value'],
					), array(
					'%s',
					'%d',
					'%d',
					'%d',
					'%s',
					));
				if ($insertStatus) {
					$saveStatus['uspRules'] =$saveStatus['uspRules']+1;
				} else {
					$saveStatus['uspRules']['failed'][] = $aRule;
				}
			}

			$wpdb->delete($wpdb->prefix . 'wcsp_global_discount_rule_mapping', array('rule_type' => 'RSP'), array('%s'));
			foreach ($rspRules as $aRule) {
				$insertStatus =$wpdb->insert($wpdb->prefix . 'wcsp_global_discount_rule_mapping', array(
					'rule_type'        		=> 'RSP',
					'type_id'          		=> $aRule['rule_beneficiary'],
					'min_qty'          		=> $aRule['min_qty'],
					'flat_or_discount_price'=> $aRule['discount_type'],
					'price'                 => $aRule['value'],
					), array(
					'%s',
					'%s',
					'%d',
					'%d',
					'%s',
					));
				if ($insertStatus) {
					$saveStatus['rspRules'] =$saveStatus['rspRules']+1;
				} else {
					$saveStatus['rspRules']['failed'][] = $aRule;
				}
			}
			if (defined('GROUPS_CORE_VERSION')) {
				$wpdb->delete($wpdb->prefix . 'wcsp_global_discount_rule_mapping', array('rule_type' => 'GSP'), array('%s'));
				foreach ($gspRules as $aRule) {
					$insertStatus =$wpdb->insert($wpdb->prefix . 'wcsp_global_discount_rule_mapping', array(
						'rule_type'        		=> 'GSP',
						'type_id'          		=> $aRule['rule_beneficiary'],
						'min_qty'          		=> $aRule['min_qty'],
						'flat_or_discount_price'=> $aRule['discount_type'],
						'price'                 => $aRule['value'],
						), array(
						'%s',
						'%d',
						'%d',
						'%d',
						'%s',
						));
					if ($insertStatus) {
						$saveStatus['gspRules'] = $saveStatus['gspRules']+1;
					} else {
						$saveStatus['gspRules']['failed'][] = $aRule;
					}
				}
			}
			return $saveStatus;
		}

		/**
		 * Validates wether the rules submited are valid & returns
		 * the rules which are valid
		 *
		 * @method static
		 * @param array $rules
		 * @return array
		 */
		public static function validateUserRules( $rules) {
			$validRules = array();
			if (!empty($rules)) {
				foreach ($rules as $aRule) {
					if (empty($aRule['rule_beneficiary']) || -1===$aRule['rule_beneficiary']) {
						continue ;
					}
					if (!self::hasValidDiscountConditions($aRule)) {
						continue;
					}
					$validRules[] = $aRule;
				}
			}
			return $validRules;
		}


		/**
		 * This method validates the role specific pricing rules.
		 * returns an array of valid rules.
		 * 
		 * @method static
		 * @param array $rules - role specific CSP Rules 
		 * @return $rules - valid CSP rules
		 */
		public static function  validateRoleRules( $rules) {
			$validRules = array();
			if (!empty($rules)) {
				foreach ($rules as $aRule) {
					if (!self::isRoleExists($aRule['rule_beneficiary']) || empty($aRule['rule_beneficiary']) || -1===$aRule['rule_beneficiary']) {
						continue ;
					}
					if (!self::hasValidDiscountConditions($aRule)) {
						continue;
					}
					$validRules[] = $aRule;
				}
			}
			return $validRules;
		}


		/**
		 * This method validates the group specific pricing rules.
		 * returns an array of valid rules.
		 * 
		 * @method static
		 * @param array $rules - role specific CSP rules
		 * @return $rules - valid CSP Rules
		 */
		public static function  validateGroupRules( $rules) {
			$validRules = array();
			if (!empty($rules)) {
				foreach ($rules as $aRule) {
					if (-1===$aRule['rule_beneficiary'] || empty($aRule['rule_beneficiary'])) {
						continue ;
					}
					if (!self::hasValidDiscountConditions($aRule)) {
						continue;
					}
					$validRules[] = $aRule;
				}
			}
			return $validRules;
		}

		/**
		 * Validates if a rule have,
		 * * A valid Discount Type selected.
		 * * A valid Minimum Quantity entered.
		 * * A valid Discount Value Or Price.
		 * 
		 * @method static
		 * @param array $aRule - CSP Rule
		 * @return boolean 
		 */
		public static function hasValidDiscountConditions( $aRule) {
			$isValidRule = true;
			//1=> Flat Pricing 2=>Percent Discount
			$validDiscountTypes = array(1, 2);
			if (!in_array((int) $aRule['discount_type'], $validDiscountTypes )) {
				$isValidRule = false;
			}
			if (0>=$aRule['min_qty'] || empty($aRule['min_qty'])) {
				$isValidRule = false;
			}
			$discountValue = (float) $aRule['value']; 
			//[discount_type] 1=> Flat Pricing 2=>Percent Discount
			if (0>$discountValue || ''==$discountValue || ( '2'==$aRule['discount_type'] && ( 100<$discountValue || 0>=$discountValue ) )) {
				$isValidRule = false;
			}
			return $isValidRule;
		}

		/**
		 * Checks if the user role is exists or not
		 *
		 * @method static
		 * @param string $role - Wordpress Users user role 
		 * @return boolean
		 */
		public static function isRoleExists( $role) {
			return $GLOBALS['wp_roles']->is_role($role);
		}
	}
}
