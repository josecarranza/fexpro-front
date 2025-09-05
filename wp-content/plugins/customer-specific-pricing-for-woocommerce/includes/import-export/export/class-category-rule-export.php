<?php

namespace cspCategoryPricing\cspExport;

/**
 * Fetch and return the customer specific pricing data for exporting in csv file
 *
 */
if (! class_exists('CSPCategoryRuleExport')) {
	/**
	* Generates the CSV files for exporting the CSP rules for the categories
	* * User Specific Category Pricing
	* * Role Specific Category Pricing
	* * Group Specific Category Pricing
	*
	* @since 4.6.0
	*/
	class CSPCategoryRuleExport {

		/**
		 * Names of the export files for different rule types.
		 *
		 * @var array
		 */
		public static $fileNames = array(
								'User' =>'category-pricing-rules-for-users.csv',
								'Role' =>'category-pricing-rules-for-roles.csv',
								'Group'=>'category-pricing-rules-for-groups.csv',
		);
	
		/**
		 * Generates the export file for the selected rule file
		 * returns the url of the generated file, returns empty string in
		 * case of the failure in generation of CSV file
		 *
		 * @param string $ruleType User|Role|Group
		 * @return string $filePath Url of the generated csv file
		 */
		public static function getExportFile( $ruleType, $returnFile = 'url') {
			$filePath      = '';
			$categoryRules = array();
			switch ($ruleType) {
				case 'User':
					$categoryRules = self::getCategoryUSPRules();
					$categoryRules = self::replaceUserIdsWithUserNames($categoryRules);
					break;
				case 'Role':
					$categoryRules = self::getCategoryRSPRules();
					break;
				case 'Group':
					$categoryRules = self::getCategoryGSPRules();
					$categoryRules = self::replaceGroupIdsWithGroupNames($categoryRules);
					break;
				
				default:
					break;
			}
		
			$filePath = self::generateExportFile($categoryRules, $ruleType, $returnFile);
			return $filePath;
		}

		/**
		 * Retrives all the user-specific pricing rules set for the categories
		 * & returns the array of results 
		 *
		 * @return array list of CSP category rules for the users
		 */
		public static function getCategoryUSPRules() {
			global $wpdb;
			$results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wcsp_user_category_pricing_mapping');				
			return $results;
		}

		/**
		 * Retrives all the role-specific pricing rules set for the categories
		 * & returns the array of results 
		 *
		 * @return array list of CSP category rules for the roles
		 */
		public static function getCategoryRSPRules() {
			global $wpdb;
			$results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wcsp_role_category_pricing_mapping');
			return $results;
		}

		/**
		 * Retrives all the group-specific pricing rules set for the categories
		 * & returns the array of results 
		 *
		 * @return array list of CSP category rules for the user groups
		 */
		public static function getCategoryGSPRules() {
			global $wpdb;
			$results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wcsp_group_category_pricing_mapping');
			return $results;
		}

		/**
		 * This function generates & stores the csv file from the list of rules provided.
		 *
		 * @param array $categoryRules formated array of rules suitable to be used in the CSV file
		 * @param string $ruleType User|Role|Group
		 * @param string $returnFile url|path default:url
		 * @return string $result url path of the generated csv file for download
		 */
		public static function generateExportFile( $categoryRules, $ruleType, $returnFile = 'url') {
			$ruleTypeId  	= self::getTypeIdentifierFor($ruleType);
			$csvData     	= self::getFormattedDataForCSV($categoryRules, $ruleTypeId);
			$csvHeadings 	= array( $ruleTypeId, 'category_slug', 'min_qty', 'flat_price', '%_discount' );
			/**
			 * This filter can be used to filter the headings of the category rule Export CSV file.
			 * 
			 * @param array $csvHeadings list of CSV headings
			 * @param string $ruleType CSP rule type.
			 */
			$csvHeadings 	= apply_filters('wdm_csp_category_rule_export_heading_filter', $csvHeadings, $ruleType);
			$exportFileName = self::$fileNames[$ruleType];
			$result			= '';
			if ( !empty($csvData) ) {
				$uploadDir  = wp_upload_dir();
				$deleteFile = glob($uploadDir['basedir'] . '/' . $exportFileName);
				if ($deleteFile) {
					foreach ($deleteFile as $file) {
						unlink($file);
					}
				}

				$output = fopen($uploadDir['basedir'] . '/' . $exportFileName, 'w');
				fputcsv($output, $csvHeadings);
				foreach ($csvData as $row) {
						$array = (array) $row;
						fputcsv($output, $array);
				}
				fclose($output);
				$result = 'path'===$returnFile?$uploadDir['basedir'] . '/' . $exportFileName:esc_url($uploadDir['baseurl'] . '/' . $exportFileName);
			}
			return $result;
		}

		/**
		 * This function finds the identifier for rule from the rule type
		 * - Example user_id is the identifier of the rule type "User Specific Pricing"
		 * 
		 * @param string $ruleType rule type as received from the request
		 * @return string $ruleTypeId identifier of the rule type in the category based rule
		 */
		public static function getTypeIdentifierFor( $ruleType ) {
			$ruleTypeIds = array('User'=>'user_id', 'Role'=>'role', 'Group'=>'group_id');
			if (array_key_exists($ruleType, $ruleTypeIds)) {
				$ruleTypeId = $ruleTypeIds[$ruleType];
			}
			return $ruleTypeId;
		}

		/**
		 * Formats the retrived database rule objects to the array & returns the data
		 *
		 * @param array $categoryRules array of category specific pricing rules
		 * @param array $ruleTypeId identifier of the rule type
		 * @return array $ruleArray formated data suitable to be used in the CSV file
		 */
		public static function getFormattedDataForCSV( $categoryRules, $ruleTypeId) {
			$ruleArray = array();
			if (!empty($categoryRules)) {
				foreach ($categoryRules as $key => $value) {
					$ruleArray[$key]		     	   = new \stdClass();
					$ruleArray[$key]->{$ruleTypeId}    = $value->$ruleTypeId;
					$ruleArray[$key]->cat_slug   	   = $value->cat_slug;
					$ruleArray[$key]->min_qty		   = $value->min_qty;
					$ruleArray[$key]->flat_price   	   = '';
					$ruleArray[$key]->percent_discount = '';
					if (2==$value->flat_or_discount_price) {
						$ruleArray[$key]->percent_discount = $value->price;
					} else {
						$ruleArray[$key]->flat_price = $value->price;
					}
				}	
			}
			/**
			 * Filter the CSP rules to be included in the  category rule export file.
			 * 
			 * @param array $ruleArray array of CSP rules.
			 * @param array $categoryRules array of category rule objects fetched from the database.
			 * @param string $ruleTypeId Rule type identifier.
			 */
			$ruleArray = apply_filters('wdm_csp_category_rule_export_values_filter', $ruleArray, $categoryRules, $ruleTypeId);
			return array_values($ruleArray);
		}

		/**
		 * This function replaces user Ids with userNames for better redability of a CSV file
		 *
		 * @param array $categoryRules
		 * @return array $categoryRulesWithUserNames
		 */
		public static function replaceUserIdsWithUserNames( $categoryRules) {
			$userIdNamePairs            = self::getUserIdNamePairs();
			$categoryRulesWithUserNames = array();
			foreach ($categoryRules as $aRule) {
				$aRule->user_id               = isset($userIdNamePairs[$aRule->user_id])?$userIdNamePairs[$aRule->user_id]:$aRule->user_id;
				$categoryRulesWithUserNames[] = $aRule;
			}
			return $categoryRulesWithUserNames;
		}

		/**
		 * This function replaces group Ids with Groups Names for better redability of a CSV file
		 *
		 * @param array $categoryRules
		 * @return array $categoryRulesWithGroupNames
		 */
		public static function replaceGroupIdsWithGroupNames( $categoryRules) {
			$groupIdNamePairs            = self::getGroupIdNamePairs();
			$categoryRulesWithGroupNames = array();
			foreach ($categoryRules as $aRule) {
				$aRule->group_id               = isset($groupIdNamePairs[$aRule->group_id])?$groupIdNamePairs[$aRule->group_id]:$aRule->group_id;
				$categoryRulesWithGroupNames[] = $aRule;
			}
			return $categoryRulesWithGroupNames;
		}
		
	
		/**
		 * Fetches userid-userlogin pairs from the users database on the site
		 *
		 * @return array $users Array of userid-username pairs
		 */
		public static function getUserIdNamePairs() {
			$users = array();
			global $wpdb;
			$results = $wpdb->get_results('SELECT id, user_login FROM ' . $wpdb->prefix . 'users');
			foreach ($results as $userNameIdPair) {
				$users[$userNameIdPair->id] = $userNameIdPair->user_login;
			}
			return $users;
		}

		/**
		 * Fetches groupId-Name pairs from the users database on the site
		 *
		 * @return array $groups Array of groupId-name pairs
		 */
		public static function getGroupIdNamePairs() {
			$groups = array();
			global $wpdb;
			$results = $wpdb->get_results('SELECT group_id, name FROM ' . $wpdb->prefix . 'groups_group');
			foreach ($results as $groupNameIdPair) {
				$groups[$groupNameIdPair->group_id] = $groupNameIdPair->name;
			}
			return $groups;
		}		
	}
}
