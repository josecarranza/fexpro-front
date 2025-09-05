<?php

namespace cspGlobalDiscounts;

/**
 * Fetch and return the customer specific pricing data for exporting
 *
 */
if (! class_exists('CSPGlobalDiscountsdRuleExport')) {
	/**
	* Generates the CSV files for exporting the CSP rules for the categories
	* * User Specific Global Pricing
	* * Role Specific Global Pricing
	* * Group Specific Global Pricing
	*
	* @since 4.6.0
	*/
	class CSPGlobalDiscountsRuleExport {

		/**
		 * Names of the export files for different rule types.
		 *
		 * @var array
		 */
		public static $fileNames = array(
								'User' =>'global-pricing-rules-for-users.csv',
								'Role' =>'global-pricing-rules-for-roles.csv',
								'Group'=>'global-pricing-rules-for-groups.csv',
		);
	
		/**
		 * Generates the export file for the selected rule file
		 * returns the url of the generated file, returns empty string in
		 * case of the failure in generation of CSV file
		 *
		 * @param string $ruleType User|Role|Group
		 * @param string $returnFile Get files path on the server or web url based on this parameter
		 * @return string $filePath Url of the generated csv file
		 */
		public static function getExportFile( $ruleType, $returnFile = 'url') {
			$filePath    = '';
			$globalRules = array();
			switch ($ruleType) {
				case 'User':
					$globalRules = self::getGlobalUSPRules();
					$globalRules = self::replaceUserIdsWithUserNames($globalRules);
					break;
				case 'Role':
					$globalRules = self::getGlobalRSPRules();
					break;
				case 'Group':
					$globalRules = self::getGlobalGSPRules();
					$globalRules = self::replaceGroupIdsWithGroupNames($globalRules);
					break;
				
				default:
					break;
			}
		
			$filePath = self::generateExportFile($globalRules, $ruleType, $returnFile);
			return $filePath;
		}

		/**
		 * Retrives all the user-specific pricing rules set for the categories
		 * & returns the array of results 
		 *
		 * @return array list of CSP global rules for the users
		 */
		public static function getGlobalUSPRules() {
			global $wpdb;
			$results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wcsp_global_discount_rule_mapping WHERE rule_type="USP"');				
			return $results;
		}

		/**
		 * Retrives all the role-specific pricing rules set for the categories
		 * & returns the array of results 
		 *
		 * @return array list of CSP Global rules for the roles
		 */
		public static function getGlobalRSPRules() {
			global $wpdb;
			$results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wcsp_global_discount_rule_mapping WHERE rule_type="RSP"');
			return $results;
		}

		/**
		 * Retrives all the group-specific pricing rules set for the categories
		 * & returns the array of results 
		 *
		 * @return array list of CSP Global rules for the user groups
		 */
		public static function getGlobalGSPRules() {
			global $wpdb;
			$results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wcsp_global_discount_rule_mapping WHERE rule_type="GSP"');
			return $results;
		}

		/**
		 * This function generates & stores the csv file from the list of rules provided.
		 *
		 * @param array $globalRules formated array of rules suitable to be used in the CSV file
		 * @param string $ruleType User|Role|Group
		 * @param string $returnFile based on this file url or path will be returned, default 'url'
		 * @return string $result url path of the generated csv file for download
		 */
		public static function generateExportFile( $globalRules, $ruleType, $returnFile = 'url') {
			$ruleTypeId  	= self::getTypeIdentifierFor($ruleType);
			$csvData     	= self::getFormattedDataForCSV($globalRules, $ruleTypeId);
			$csvHeadings 	= array( $ruleTypeId, 'min_qty', 'flat_price', '%_discount' );
			/**
			 * This filter can be used to filter the headings of the global discount rule Export CSV file.
			 * 
			 * @param array $csvHeadings list of CSV headings
			 * @param string $ruleType CSP rule type.
			 */
			$csvHeadings 	= apply_filters('wdm_csp_global_rule_export_heading_filter', $csvHeadings, $ruleType);
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
		 * @return string $ruleTypeId identifier of the rule type in the Global
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
		 * @param array $globalDiscountRules array of global discount pricing rules
		 * @param array $ruleTypeId identifier of the rule type
		 * @return array $ruleArray formated data suitable to be used in the CSV file
		 */
		public static function getFormattedDataForCSV( $globalDiscountRules, $ruleTypeId) {
			$ruleArray = array();
			if (!empty($globalDiscountRules)) {
				foreach ($globalDiscountRules as $key => $value) {
					$ruleArray[$key]		     	   = new \stdClass();
					$ruleArray[$key]->{$ruleTypeId}    = $value->type_id;
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
			 * Filter the CSP rules to be included in the global rule export file.
			 * 
			 * @param array $ruleArray array of CSP rules.
			 * @param array $categoryRules array of global rule objects fetched from the database.
			 * @param string $ruleTypeId Rule type identifier.
			 */
			$ruleArray = apply_filters('wdm_csp_global_rule_export_values_filter', $ruleArray, $globalDiscountRules, $ruleTypeId);
			return array_values($ruleArray);
		}


		/**
		 * This function replaces user Ids with userNames for better redability of a CSV file
		 *
		 * @param array $globalRules
		 * @return array $globalRulesWithUserNames
		 */
		public static function replaceUserIdsWithUserNames( $globalRules) {
			$userIdNamePairs          = self::getUserIdNamePairs();
			$globalRulesWithUserNames = array();
			foreach ($globalRules as $aRule) {
				$aRule->type_id             = isset($userIdNamePairs[$aRule->type_id])?$userIdNamePairs[$aRule->type_id]:$aRule->type_id;
				$globalRulesWithUserNames[] = $aRule;
			}
			return $globalRulesWithUserNames;
		}

		/**
		 * This function replaces group Ids with Groups Names for better redability of a CSV file
		 *
		 * @param array $globalRules
		 * @return array $globalRulesWithGroupNames
		 */
		public static function replaceGroupIdsWithGroupNames( $globalRules) {
			$groupIdNamePairs            = self::getGroupIdNamePairs();
			$globalRulesWithGroupNames = array();
			foreach ($globalRules as $aRule) {
				$aRule->type_id               = isset($groupIdNamePairs[$aRule->type_id])?$groupIdNamePairs[$aRule->type_id]:$aRule->type_id;
				$globalRulesWithGroupNames[] = $aRule;
			}
			return $globalRulesWithGroupNames;
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
