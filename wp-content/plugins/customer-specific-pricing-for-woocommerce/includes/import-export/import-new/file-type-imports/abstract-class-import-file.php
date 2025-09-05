<?php
namespace cspImportExport\Import\FileTypeImport;

require_once CSP_PLUGIN_PATH . '/includes/import-export/import-new/class-import-commons.php';

if (!class_exists('CSPImportBase')) {
	abstract class CSPImportBase extends \cspImportExport\Import\WisdmCSPImportCommons {


		abstract public function importRules( $rules, $headerMap);

		/**
		 * * This function fetches the csv import file content & prepare the CSP rules for import.
		 * * Validates basic fields of the csp rules & triggers the import process.
		 * * Returns the report data eg. No of rules processed, added, updated, skipped, etc.
		 *
		 * @param string $fileName Name of the batch file currently in process.
		 * @param string $fileType 
		 * @param array  $headerMap array of valid CSV fields to the csv column number pairs ex. array('user'=>0,'flat_price'=>7, ...)
		 * @param string $fildDir directory from where the file is being imported
		 * @return array $reportData no of rules processed, inserted, updated, skipped, etc.
		 */
		public function importFile( $fileName, $fileType = '', $headerMap = array(), $fileDir = '') {
			$fileDir 	  = empty($fileDir)?self::getCSPImportBatchesDirectory():$fileDir;
			$filePath 	  = $fileDir . '/' . $fileName;
			$statusReport = array(
									'recordsProcessed' => 0,
									'recordsInserted'  => 0,
									'recordsUpdated'   => 0,
									'recordsSkipped'   => 0
								);
			$rules 		  = $this->getRulesArrayFromCSV($filePath); 
			$rules 		  = $this->mapRulesToHeaders($rules, $headerMap);
			$rules 		  = $this->basicCSPRuleValidation($rules);
			
			$importReport = $this->importRules($rules, $headerMap);

			$this->writeToReport($fileName, $importReport['importDetails'], $fileDir);

			$statusReport = !empty($importReport['statusReport'])?$importReport['statusReport']:$statusReport;
			// get file contents if file exists and store in an array
			// validate the fundamental rule fields %, flat, price and if entities exists and store the status in array fields
			// send processed array of rules & headers to the import function of the type , returns array with import statuses
			// Create the report file for the batch & store the array received.
			
			return $statusReport;
		}
	

		/**
		 * This function deletes the processed import batch csv file & 
		 * replaces the same with the result of the import.
		 *
		 * @param string $fileName
		 * @param array $data
		 */
		public function writeToReport( $fileName, $data, $filePath = '') {	
			$delFile  = $filePath . '/' . $fileName;
			unlink($delFile);
			$fileName = 'report_' . $fileName;
			$filePath = $filePath . '/' . $fileName;
			$file     = fopen($filePath, 'w');
			
			foreach ($data as $rule) {
				if (is_array($rule)) {
					$rule = array_values($rule);
					fwrite($file, implode(',', $rule) . PHP_EOL);
				}	
			}
			
			\fclose($file);
		}
	
		/**
		 * Removes the addtional column data from the row fetched from the CSV file & keeps 
		 * the data of only required fields.
		 *
		 * @param array $data array of rule row field values
		 * @param array $headerMap an associated array having header to column number mapping
		 * @return array array containing only valid fields.
		 */
		public function getValidFieldData( $data, $headerMap) {
			$row = array();
			if (!empty($data)) {
				foreach ($headerMap as $header => $column) {
					$row =  $row . ',' . $data[$column];
				}
				$row = trim($row, ',');
			}
			return $row;
		}
	
		
	
		/**
		 * * Checks if the CSV file exits.
		 * * Gets the CSV file content.
		 * * Prepare rule array and returns
		 * * returns empty array if no rule is found or no file exists 
		 *
		 * @param string $filePath absolute path to the file on a server.
		 * @return array array of CSP rules
		 */
		public function getRulesArrayFromCSV( $filePath ) {
			$rules = array();
			ini_set('auto_detect_line_endings', true);
			if (\file_exists($filePath)) {
				$file = \fopen($filePath, 'r');
				if (false!=$file) {
					set_time_limit(0);
					while (( $data = fgetcsv($file) ) !== false) {
						$rules[] = $data;
					}
				}
				\fclose($file);
			}
			
			return $rules;
		}
	
	
		/**
		 * Parse each rules and maps the rule fields(columns)
		 * to the headers specified in the header map, all the
		 * nonrequired fields will be mapped to the header 'unused_<number of unused header>'
		 * the unused headers could be removed but we are keeping them 
		 * in order to be able to include them as they are in the report file 
		 *
		 * @param array $rules array of CSP rules.
		 * @param array $headerMap array of header to the column number pairs. 
		 * @return array $rules
		 */
		public function mapRulesToHeaders( $rules, $headerMap) {
			$rulesWithHeaderKeys = array();
			$extraColumnText     = 'unused_';
			foreach ($rules as $aRule) {
				$fieldId = 0;
				foreach ($aRule as $key => $value) {
					$extraFieldHeader = $extraColumnText . $fieldId;					
					$header           = array_search($key, $headerMap);
					if (false!==$header) {
						$newRule[$header] = $value; 	
					} else {
						$newRule[$extraFieldHeader] = $value;
						$fieldId++;
					}
				}
				$rulesWithHeaderKeys[] = $newRule;
			}
			return $rulesWithHeaderKeys;
		}


		/**
		 * Performs validation on bacis CSP rule fields, 
		 * Add the status field with a value 'valid' by default & reason of being invalid in case of invalid data.
		 * returns the same array. this step instantly filters out the invalid rules so that they can be skipped during
		 * the import process. 
		 *
		 * @param array $rules 
		 * @return array $filterdRules 
		 */
		public function basicCSPRuleValidation( $rules = array()) {
			$validatedRules = array();
			$errors     	= array(
				'both_flat_%_discount_set' => __('Specify either %_discount or flat_price', 'cuatomer-specific-pricing-for-woocommerce'), 
				'invalid_percent_discount' => __('%_discount should be between 0 to 100', 'cuatomer-specific-pricing-for-woocommerce'),
				'invalid_flat_price'	   => __('invalid flat price', 'cuatomer-specific-pricing-for-woocommerce'),
				'invalid_quantity'	   	   => __('invalid minimum quantity', 'cuatomer-specific-pricing-for-woocommerce'),
			);
			foreach ($rules as $aRule) {
				$aRule['status'] = 'valid';

				if (empty($aRule['min_quantity']) || $aRule['min_quantity']<0) {
					$aRule['status']  = $errors['invalid_quantity'];
					$validatedRules[] = $aRule;
					continue;
				} elseif ( ( !empty($aRule['%_discount']) && \is_numeric($aRule['%_discount']) ) && ( !empty($aRule['flat_price']) && \is_numeric($aRule['flat_price']) ) ) {
					$aRule['status']  = $errors['both_flat_%_discount_set'];
					$validatedRules[] = $aRule;
					continue;
				} elseif (empty($aRule['flat_price']) && !empty($aRule['%_discount']) && ( $aRule['%_discount']>100 || $aRule['%_discount']<0 )) {
					$aRule['status']  = $errors['invalid_percent_discount'];
					$validatedRules[] = $aRule;
					continue;
				} elseif (!empty($aRule['flat_price']) && $aRule['flat_price']<0) {
					$aRule['status']  = $errors['invalid_flat_price'];
					$validatedRules[] = $aRule;
					continue;
				} 

				$validatedRules[] = $aRule;
			}

			return $validatedRules;
		}

		/**
		 * Fetches userid-userlogin pairs from the users database on the site
		 *
		 * @return array $users Array of userid-username pairs
		 */
		public static function getAllUserNameIdPairs() {
			$users = array();
			global $wpdb;
			$results = $wpdb->get_results('SELECT id, user_login FROM ' . $wpdb->prefix . 'users');
			foreach ($results as $userNameIdPair) {
				$users[$userNameIdPair->user_login] = $userNameIdPair->id;
			}
			return $users;
		}

		/**
		 * Gets all the ids of the products which are supported by CSP
		 * - Gets products ids, product variation ids.
		 * - Removes the parent product ids from product ids
		 * - Merge product & variation ids & return
		 *
		 * @return array $productIds array of CSP supported product Ids
		 */
		public static function getAllValidProductIds() {
			global $wpdb;
			$productIds   = array();
			$productsList =	$wpdb->get_results(
				'SELECT ID FROM ' . $wpdb->prefix . 
				'posts where post_type="product" AND 
				post_status IN ("draft", "publish", "pending")'
			);

			foreach ($productsList as $product) {
				$productIds[] = $product->ID;
			}

			$variantsList =	$wpdb->get_results(
				'SELECT ID, post_parent FROM ' . $wpdb->prefix . 
				'posts where post_type="product_variation" AND 
				post_status IN ("private", "publish", "pending")'
			);

			$parentProducts  = array();
			$productVariants = array();
			if (!empty($variantsList)) {
				foreach ($variantsList as $variant) {
					$parentProducts[]  = $variant->post_parent;
					$productVariants[] = $variant->ID;
				}
			}

			$productIds = array_diff($productIds, $parentProducts);
			$productIds = array_merge($productIds, $productVariants);

			return $productIds;
		}

		/**
		 * Fetches all the wp user roles , prepares an array of user role slugs
		 * and returns the array.
		 *
		 * @return array $userRoles
		 */
		public static function getAllUserRoleSlugs() {
			$userRoles = array();
			global $wp_roles;
			$allRoles = $wp_roles->roles;
			foreach ($allRoles as $roleSlug => $role) {
				$userRoles[] = $roleSlug;
			}
			return $userRoles;
		}


		/**
		 * Gets all the group Ids & names
		 * - Based on the parameteres the id-name or name-id array is returned.
		 * 
		 * @return array $productIds array of CSP supported product Ids
		 */
		public static function getAllUserGroupIdNamePairs( $format = 'name-id-pairs') {
			global $wpdb;
			$groupIdNamePairs = array();
			if ($wpdb->get_var('SHOW TABLES LIKE "' . $wpdb->prefix . 'groups_group"')) {
				$result = $wpdb->get_results('SELECT group_id as ID, name FROM ' . $wpdb->prefix . 'groups_group');
			}

			if (!empty($result)) {
				if ('id-name-pairs'===$format) {
					$groupIdNamePairs = self::getArrayInFormat($result, 'ID', 'name');
				} else {
					$groupIdNamePairs = self::getArrayInFormat($result, 'name', 'ID');
				}
			}

			return $groupIdNamePairs;
		}


		/**
		 * Gets all the group Ids & names
		 * - Based on the parameteres the id-name or name-id array is returned.
		 * 
		 * @return array $productIds array of CSP supported product Ids
		 */
		public static function getAllSKUProductIdPairs( $format = 'sku-pid-pairs') {
			$dataSKU = array();
			global $wpdb;
			$result = $wpdb->get_results('SELECT post_id as ID, meta_value as SKU FROM ' . $wpdb->prefix . 'postmeta WHERE meta_key="_sku"');
			if (!empty($result)) {
				if ('sku-pid-pairs'===$format) {
					$dataSKU = self::getArrayInFormat($result, 'SKU', 'ID');
				} else {
					$dataSKU = self::getArrayInFormat($result, 'ID', 'SKU');
				}
			}
			return $dataSKU;
		}

		/**
		 * Prepares associative array from the object by using value of the param $key as a key element
		 * & value of $value as a value. (eg. for the object $obj->ID = 5 & $obj->Name = abc), if $key is ID &
		 * $value is Name then returns an array array(5=>"abc",...) if $key is Name & $value is ID 
		 * returns array('abc'=>5,...)
		 *
		 * @param array $dataArray
		 * @param string $key
		 * @param string $value
		 * @return array
		 */
		public static function getArrayInFormat( $dataArray, $key, $value) {
			$data = array();
			if (!empty($dataArray) && !empty($key) && !empty($value)) {
				foreach ($dataArray as $object) {
					$data[$object->$key] = $object->$value;	
				}
			}
			return $data;
		}

		/**
		* Gets the Product categories which are active now.
		 *
		* @return array $catSlugArray Product Categories slugs.
		*/
		public static function getAllCategorySlugs() {
			$catSlugArray = array();
			$taxonomy     = 'product_cat';
			$orderby      = 'name';
			$show_count   = 0;      // 1 for yes, 0 for no
			$pad_counts   = 0;      // 1 for yes, 0 for no
			$hierarchical = 1;      // 1 for yes, 0 for no
			$title        = '';
			$empty        = false;

			$args           = array(
				 'taxonomy'     => $taxonomy,
				 'orderby'      => $orderby,
				 'show_count'   => $show_count,
				 'pad_counts'   => $pad_counts,
				 'hierarchical' => $hierarchical,
				 'title_li'     => $title,
				 'hide_empty'   => $empty
			);
			$all_categories = get_categories($args);

			foreach ($all_categories as $cat) {
				$catSlugArray[] = $cat->slug;
			}

			return $catSlugArray;
		}
	
	}	
}
