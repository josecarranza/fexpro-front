<?php

namespace cspImportExport\cspImport;

/**
 * This class contains the methods used for the import process of the
 * global discount pricing rules in CSP.
 *
 * @since 4.6.0
 */
if (! class_exists('CSPGlobalRuleImport')) {

	class CSPGlobalRuleImport {

		/**
		 * Checks if the file type is correct according to the selected
		 * import type(user, role or group based import)
		 *
		 * @param string $file
		 * @param  string $importType
		 * @return mixed boolean|array
		 */
		public static function isValidFile( $fileHeaders, $importType) {
			$validFile       = false;
			$requiredHeaders = self::getValidHeaderListFor( $importType); 
			$validFile  	 = self::mapFileHeaders($fileHeaders, $requiredHeaders);
			return $validFile;
		}

		/**
		 * Generates & returns an array of required headers for the import types 
		 *
		 * @param string $importType usp|rsp|gsp
		 * @return array array of required headers for import type.
		 */
		public static function getValidHeaderListFor( $importType) {
			$entityIdHeader = self::getEntityIdHeaderByRuleType($importType);
			return array( $entityIdHeader, 'min_qty', 'flat_price', '%_discount');
		}

		/**
		 * The user, user role & user group type rules have different IDs
		 * by which those entities are identified, eg. user_id is the identifier
		 * for a user in user-specifc pricing rules. thease are used as the
		 * headers/the column names in the exv export files.
		 * this function returns the correct identifier for the selected file.
		 *
		 * @param string $ruleType usp|rsp|gsp
		 * @return string $entityIdHeader
		 */
		public static function getEntityIdHeaderByRuleType( $ruleType) {
			$entityIdHeader = 'user_id';
			switch ($ruleType) {
				case 'usp':
				case 'USP':
					$entityIdHeader = 'user_id';
					break;
				case 'rsp':
				case 'RSP':
					$entityIdHeader = 'role';
					break;
				case 'gsp':
				case 'GSP':
					$entityIdHeader = 'group_id';
					break;
				default:
					# code...
					break;
			}
			/**
			 * Filter for the heading of the global discounts rule export CSV.
			 * 
			 * @param array $entityIdHeader array of csv headers user_id|role|group_id.
			 * @param string $ruleType  type of the CSP rules usp|rsp|csp.
			 */
			return apply_filters('wdm_csp_import_global_entity_header', $entityIdHeader, $ruleType);
		}

		/**
		 * This function checks if all the required headers/columns required for
		 * the import process are present in the uploaded CSV file, if present maps
		 * the columns with the standard structure & returns the array with the 
		 * column_name - column_position pairs. else return false.
		 *
		 * @param array $fileHeaders columns in the actual file.
		 * @param array $headers columns required for import
		 * @return mixed false|array of column_name - column_position pairs
		 */
		public static function mapFileHeaders( $fileHeaders, $headers) {
			$headerMap     = array();
			$fileHeaders   = array_map('strtolower', $fileHeaders);
			$foundRequired = false;
			if (!empty($fileHeaders) && !empty($headers)) {
				foreach ($headers as $value) {
					$index = array_search($value, $fileHeaders);
					if ( false !== $index ) {
						$headerMap[$value] = $index;
						$foundRequired     = true;
					} else {
						$foundRequired = false;
						break;
					}
				}
			}
			if ($foundRequired) {
				return $headerMap;
			}
			return false;
		}



		/**
		 * Generates CSV batches from the given CSV file according to the settings provided
		 * stores the batches in the batch directory & returns an array with the details of the
		 * created batches.
		 * 
		 * @param string $batchDirectory where to store the batch files.
		 * @param string $fileName name of the csv file uploaded.
		 * @param int $batchSize number of records to be stored in a batch.
		 * @param string $importType usp|rsp|gsp
		 * @return array $batchData detailed information of the batches created.
		 */
		public static function generateBatches( $batchDirectory, $fileName, $batchSize, $importType) {
			$inputFile = fopen($fileName, 'r');
			$batchData = array();
			if (!empty($inputFile)) {
				$firstLineHeader   = fgetcsv($inputFile, 0, ',');
				$firstLineHeader[] ='Import Status';
				$recordsToStore[]  = $firstLineHeader;
				$entityIdHeader    = self::getEntityIdHeaderByRuleType($importType);
				set_time_limit(0);
				$row         = 0;
				$batchNumber = 0;
				$file        = null;
				while (( $data = fgetcsv($inputFile) ) !== false) {
					$data = array_map('trim', $data);
					if (0 === $row %$batchSize) {
						//closing the previous file handler
						if (null!== $file ) {
							fclose($file);
						}
						$newBatch = "minpoints$row.csv";
						$fileName = $batchDirectory . '/' . $newBatch;
						$file     = fopen($fileName, 'w');
					}

					$entry = implode (', ', $data);
					
					fwrite($file, $entry . PHP_EOL);
					if (0 === $row % $batchSize) {
						$batchNumber++;
						array_push($batchData, array('batchName'=> $newBatch,
													 'fileType'	=> 'global_' . $importType,
													 'batchNo'	=> $batchNumber,
													 'uniqueId' => '',
													));
					}
					$row++;
				}
			}
			return $batchData;
		}

		/**
		 * The import CSV may have additional columns for the referances, 
		 * all these callumns may or may not be required to process importing of
		 * the rule in to the storage.
		 * On accessing such a row this method filters out the non-required data according to 
		 * the valid file headers and returns an array of required fields.
		 *
		 * @param array $data array of the fields in the CSV row.
		 * @param array $validFileHeaders file headres which are must required to process the import.
		 * @param string $entityIdHeader identifier wich is used to identify user, role, group.
		 * @return array
		 */
		public static function getBatchEntryByRuleTypeHeaders( $data, $validFileHeaders, $entityIdHeader) {
			$entityId        = isset($data[$validFileHeaders[$entityIdHeader]]) ? $data[$validFileHeaders[$entityIdHeader]] : '';
			$minQty          = isset($data[$validFileHeaders['min_qty']]) ? $data[$validFileHeaders['min_qty']] : '';
			$flatPrice       = isset($data[$validFileHeaders['flat_price']]) ? $data[$validFileHeaders['flat_price']] : '';
			$percentDiscount = isset($data[$validFileHeaders['%_discount']]) ? $data[$validFileHeaders['%_discount']] : '';
		
			$entry = array(
							$entityIdHeader => $entityId,
							'min_qty'=> $minQty,
							'flat_price'=>$flatPrice,
							'%_discount'=>$percentDiscount,
						);

			return $entry;
		}

		/**
		 * Traverce through the Batch file & imports the csv batch
		 * for the users, roles or groups according to the rule type 
		 * returns the status of the records updated, inserted & skipped.
		 *
		 * @param string $batchFile
		 * @param int $batchNumber
		 * @param string $ruleType usp|rsp|gsp
		 * @return array $statusArray
		 */
		public static function importBatch( $batchFile, $batchNumber, $ruleType) {
			$headers        = self::getStoredValidHeaders();
			$headerMap      = self::isValidFile($headers, $ruleType);
			$entityIdHeader = self::getEntityIdHeaderByRuleType( $ruleType);
			$batchFile      = fopen($batchFile, 'r');
			$importStatus   = array();
			if (false !== $batchFile) {
				switch ($ruleType) {
					case 'usp':
						$importStatus = self::importUSPRules($batchFile, $headerMap, $entityIdHeader);
						break;
					case 'rsp':
						$importStatus = self::importRSPRules($batchFile, $headerMap, $entityIdHeader);
						break;
					case 'gsp':
						$importStatus = self::importGSPRules($batchFile, $headerMap, $entityIdHeader);
						break;
					default:
						# code...
						break;
				}
				fclose($batchFile);
				$csvName ='batch' . $batchNumber;
				global $cspFunctions;
				$cspFunctions->wdmSaveCSV($csvName, $importStatus['resultsArray'], 'cspReports');
				$responseData['rows_read']  = $importStatus['counters']['rows_read'];
				$responseData['insert_cnt'] = $importStatus['counters']['insert_cnt'];
				$responseData['update_cnt'] = $importStatus['counters']['update_cnt'];
				$responseData['skip_cnt']   = $importStatus['counters']['skip_cnt'];
				return $responseData;
			}
			return false;
		}


		/**
		 * This menthods initiates & processes the import for the batch of the
		 * CSV uploaded, the batch number & file is provided by the calling method
		 *
		 * @param file $batchFile
		 * @param array $headerMap
		 * @param string $entityIdHeader
		 * @return void
		 */
		public static function importUSPRules( $batchFile, $headerMap, $entityIdHeader) {
			$updateCnt    = 0;
			$insertCnt    = 0;
			$skipCnt      = 0;
			$count        = 0;
			$responseData = array();
			$resultsArray = array();
			$users		  = self::getUserNameIdPairs(); 
			while (false !== ( $data = fgetcsv($batchFile, 0, ',') )) {
				$count++;
				$result  = $data;
				$str     = implode(',', $result);
				$rowData = array_map('trim', explode(',', $str));
				
				$rule 	   = self::getBatchEntryByRuleTypeHeaders($rowData, $headerMap, $entityIdHeader);
				$rule 	   = self::validateUserAndGetRuleWithUserId($rule, $users);
				$ruleValid = self::isValidRule($rule);
				
				if (!$ruleValid['status'] || false===$rule[$entityIdHeader]) {
					$status = isset($ruleValid['message'])?$ruleValid['message']:esc_html__('Invalid User', 'customer-specific-pricing-for-woocommerce');
					$skipCnt++;
					$rowData[]      = $status;
					$resultsArray[] = $rowData;
				} else {
					$status = self::updateGdRule($rule , 'USP');
					if ('inserted'===$status) {
						$insertCnt++;
						$rowData[] = esc_html__('Inserted', 'customer-specific-pricing-for-woocommerce');
					} elseif ('updated' ===$status) {
						$updateCnt++;
						$rowData[] = esc_html__('Updated', 'customer-specific-pricing-for-woocommerce');
					} else {
						$skipCnt++;
						$failureMessage = 'exists'===$status?esc_html__('Rule already exists', 'customer-specific-pricing-for-woocommerce'):esc_html__('Filed to add a rule', 'customer-specific-pricing-for-woocommerce');
						$rowData[]      = $failureMessage;
					}
					$resultsArray[] = $rowData;
				}
			}

			$importStatus = array(
				'counters' => array(
					'rows_read'  => $count,
					'skip_cnt'   => $skipCnt,
					'insert_cnt' => $insertCnt,
					'update_cnt' => $updateCnt, 
				),
				'resultsArray' => $resultsArray,
			);
			return $importStatus;
		}


		/**
		 * This menthods initiates & processes the import for the batch of the
		 * CSV uploaded, the batch number & file is provided by the calling method
		 *
		 * @param file $batchFile
		 * @param array $headerMap
		 * @param string $entityIdHeader
		 * @return void
		 */
		public static function importRSPRules( $batchFile, $headerMap, $entityIdHeader) {
			$updateCnt    = 0;
			$insertCnt    = 0;
			$skipCnt      = 0;
			$count        = 0;
			$responseData = array();
			$resultsArray = array();
			$roles		  = self::getUserRoles();
			while (false !== ( $data = fgetcsv($batchFile, 0, ',') )) {
				$count++;
				$result  = $data;
				$str     = implode(',', $result);
				$rowData = array_map('trim', explode(',', $str));
				
				$rule 	   = self::getBatchEntryByRuleTypeHeaders($rowData, $headerMap, $entityIdHeader);
				$rule 	   = self::validateRoleAndGetRuleWithRoleSlug($rule, $roles);
				$ruleValid = self::isValidRule($rule);
				
				if (!$ruleValid['status'] || false===$rule['role']) {
					$status = isset($ruleValid['message'])?$ruleValid['message']:esc_html__('Invalid User Role', 'customer-specific-pricing-for-woocommerce');
					$skipCnt++;
					$rowData[]      = $status;
					$resultsArray[] = $rowData;
				} else {
					$status = self::updateGdRule($rule , 'RSP');
					if ('inserted'===$status) {
						$insertCnt++;
						$rowData[] = esc_html__('Inserted', 'customer-specific-pricing-for-woocommerce');
					} elseif ('updated' ===$status) {
						$updateCnt++;
						$rowData[] = esc_html__('Updated', 'customer-specific-pricing-for-woocommerce');
					} else {
						$skipCnt++;
						$failureMessage = 'exists'===$status?esc_html__('Rule already exists', 'customer-specific-pricing-for-woocommerce'):esc_html__('Filed to add a rule', 'customer-specific-pricing-for-woocommerce');
						$rowData[]      = $failureMessage;
					}
					$resultsArray[] = $rowData;
				}
			}

			$importStatus = array(
				'counters' => array(
					'rows_read'  => $count,
					'skip_cnt'   => $skipCnt,
					'insert_cnt' => $insertCnt,
					'update_cnt' => $updateCnt, 
				),
				'resultsArray' => $resultsArray,
			);
			return $importStatus;
		}
		

		/**
		 * This menthods initiates & processes the import for the batch of the
		 * CSV uploaded, the batch number & file is provided by the calling method
		 *
		 * @param file $batchFile
		 * @param array $headerMap
		 * @param string $entityIdHeader
		 * @return array $status status of imorted records
		 */
		public static function importGSPRules( $batchFile, $headerMap, $entityIdHeader) {
			$updateCnt    = 0;
			$insertCnt    = 0;
			$skipCnt      = 0;
			$count        = 0;
			$responseData = array();
			$resultsArray = array();
			$groups		  = self::getGroupNameIdPairs();
			while (false !== ( $data = fgetcsv($batchFile, 0, ',') )) {
				$count++;
				$result  = $data;
				$str     = implode(',', $result);
				$rowData = array_map('trim', explode(',', $str));
				
				$rule 	   = self::getBatchEntryByRuleTypeHeaders($rowData, $headerMap, $entityIdHeader);
				$rule 	   = self::validateGroupAndGetRuleWithGroupId($rule, $groups);
				$ruleValid = self::isValidRule($rule);
				
				if (!$ruleValid['status'] || false===$rule['group_id']) {
					$status = isset($ruleValid['message'])?$ruleValid['message']:esc_html__('Invalid User Group', 'customer-specific-pricing-for-woocommerce');
					$skipCnt++;
					$rowData[]      = $status;
					$resultsArray[] = $rowData;
				} else {
					$status = self::updateGdRule($rule , 'GSP');
					if ('inserted'===$status) {
						$insertCnt++;
						$rowData[] = esc_html__('Inserted', 'customer-specific-pricing-for-woocommerce');
					} elseif ('updated' ===$status) {
						$updateCnt++;
						$rowData[] = esc_html__('Updated', 'customer-specific-pricing-for-woocommerce');
					} else {
						$skipCnt++;
						$failureMessage = 'exists'===$status?esc_html__('Rule already exists', 'customer-specific-pricing-for-woocommerce'):esc_html__('Filed to add a rule', 'customer-specific-pricing-for-woocommerce');
						$rowData[]      = $failureMessage;
					}
					$resultsArray[] = $rowData;
				}
			}

			$importStatus = array(
				'counters' => array(
					'rows_read'  => $count,
					'skip_cnt'   => $skipCnt,
					'insert_cnt' => $insertCnt,
					'update_cnt' => $updateCnt, 
				),
				'resultsArray' => $resultsArray,
			);
			return $importStatus;
		}


		/**
		 * Checks if the provided rule already exists in the database
		 * * Updates the rule in case if its already exists
		 * * Insert if the rule does not already exists
		 *
		 * @param array $rule CSP category USP rule
		 * @return srting status of the rule import
		 */
		public static function updateGdRule( $rule, $ruleType) {
			global $wpdb;
			$typeIdKey  = self::getEntityIdHeaderByRuleType($ruleType);
			$typeId    	= $rule[$typeIdKey];
			$minQty    	= $rule['min_qty'];
			$priceType 	= !empty($rule['flat_price'])?1:2;
			$priceValue	= 2===$priceType?$rule['%_discount']:$rule['flat_price'];
			$result    	= $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wcsp_global_discount_rule_mapping WHERE rule_type=%s AND type_id=%s AND min_qty=%d', $ruleType, $typeId, $minQty));
			if (empty($result)) {
				$status = $wpdb->insert($wpdb->prefix . 'wcsp_global_discount_rule_mapping', array(
					'rule_type'				=> $ruleType,
					'type_id'               => $typeId,
					'min_qty'               => $minQty,
					'flat_or_discount_price'=> $priceType,
					'price'                 => $priceValue,
					), array(
					'%s',
					'%s',
					'%d',
					'%d',
					'%f',
					));
				$status = empty($status)?'failed':'inserted';
			} else {
				$status = $wpdb->update($wpdb->prefix . 'wcsp_global_discount_rule_mapping', array(
					'price'                     => $priceValue,
					'flat_or_discount_price'    => $priceType,
					), array(
					'rule_type'	=> $ruleType,
					'type_id'   => $typeId,
					'min_qty'  	=> $minQty			
					), array(
					 '%f', 
					 '%d'
					), array(
					 '%s',
					 '%s',
					 '%d'
					)
				);
				$status = empty($status)?'exists':'updated';
			}
			return $status;
		}


		/**
		 * Validate the category specific pricing rules by checking
		 * * If the caqtegory exists & is a valid product category
		 * * The minimum quantity field value is correct
		 * * Correct %discount & flat pricing values are set
		 * * all the required values are set
		 * returns an array with the validation status and error message.
		 *
		 * @param array $rule csp category rule.
		 * @return array array with the validation status and error message
		 */
		public static function isValidRule( $rule) {
			if (empty($rule['min_qty']) || ( empty($rule['flat_price']) && empty($rule['%_discount']) )) {
				return array('status'=>false, 'message'=>esc_html__('Invalid or empty rule values', 'customer-specific-pricing-for-woocommerce'));
			}	
				
			if (0>=$rule['min_qty']) {
				return array('status'=>false, 'message'=>esc_html__('Minimum quantity should be greater than 0.', 'customer-specific-pricing-for-woocommerce'));
			}

			if (empty($rule['flat_price'])) {
				if (!is_numeric($rule['%_discount']) || ( $rule['%_discount']<=0 || $rule['%_discount']>100 )) {
					return array('status'=>false, 'message'=>esc_html__('% discount value should be between 0-100', 'customer-specific-pricing-for-woocommerce'));
				}
			} else {
				if (!is_numeric($rule['flat_price']) || $rule['flat_price']<0) {
					return array('status'=>false, 'message'=>esc_html__('Price should be greater than or equals to 0', 'customer-specific-pricing-for-woocommerce'));
				}
			}
			return array('status'=>true);
		}

		/**
		 * Get the headers of the uploaded file from the header file stored
		 * during the batch creation process & return the array of header
		 * columns
		 *
		 * @return array $headers headers of the uploaded CSV file
		 */
		public static function getStoredValidHeaders() {
			$file    = wp_upload_dir()['basedir'] . '/cspReports/batch0.csv';
			$headers = array();
			$file    = fopen($file, 'r');
			if (!empty($file)) {
				$headers = fgetcsv($file, 0, ',');
			}
			fclose($file);
			return $headers;
		}


		/**
		 * Fetches userid-userlogin pairs from the users database on the site
		 *
		 * @return array $users Array of userid-username pairs
		 */
		public static function getUserNameIdPairs() {
			$users = array();
			global $wpdb;
			$results = $wpdb->get_results('SELECT id, user_login FROM ' . $wpdb->prefix . 'users');
			foreach ($results as $userNameIdPair) {
				$users[$userNameIdPair->user_login] = $userNameIdPair->id;
			}
			return $users;
		}

		/**
		 * Fetches all the user-role slugs from the site
		 *
		 * @return array $roles Array of user role slugs
		 */
		public static function getUserRoles() {
			$roles = array();
			global $wp_roles;
			$allRoles = $wp_roles->roles;
			foreach ($allRoles as $roleSlug => $role) {
				$roles[] = $roleSlug;
			}
			return $roles;
		}

		/**
		 * Fetches userid-userlogin pairs from the users database on the site
		 *
		 * @return array $users Array of userid-username pairs
		 */
		public static function getGroupNameIdPairs() {
			$users = array();
			global $wpdb;
			$results = $wpdb->get_results('SELECT group_id, name FROM ' . $wpdb->prefix . 'groups_group');
			foreach ($results as $userNameIdPair) {
				$users[$userNameIdPair->name] = $userNameIdPair->group_id;
			}
			return $users;
		}
		
		/**
		 * Replaces the user_name in the rule with the user id for
		 * import process if found in the existing users list. else
		 * replaces it with a value false.
		 *
		 * @param array $rule category pricing rule for users.
		 * @param array $users username-id pairs.
		 * @return array $rule rule updated with the userid if valid with the false value ifinvalid.
		 */
		public static function validateUserAndGetRuleWithUserId( $rule, $users) {
			$rule['user_id'] = isset($users[$rule['user_id']])?$users[$rule['user_id']]:false;
			return $rule;
		}

		/**
		 * Replaces the role in the rule with the value false if the role
		 * does not exists in the list of roles.
		 *
		 * @param array $rule category pricing rule for users.
		 * @param array $roles roles.
		 * @return array $rule rule updated with the role if valid with the false value ifinvalid.
		 */
		public static function validateRoleAndGetRuleWithRoleSlug( $rule, $roles) {
			$rule['role'] = in_array( $rule['role'], $roles, true)?$rule['role']:false;
			return $rule;
		}
		
		/**
		 * Replaces the group name in the rule with the group id for
		 * import process if found in the existing groups list. else
		 * replaces it with a value false.
		 *
		 * @param array $rule category pricing rule for users.
		 * @param array $groups groupname-id pairs.
		 * @return array $rule rule updated with the groupId if valid, with the false value if invalid.
		 */
		public static function validateGroupAndGetRuleWithGroupId( $rule, $groups) {
			$rule['group_id'] = isset($groups[$rule['group_id']])?$groups[$rule['group_id']]:false;
			return $rule;
		}
		
	}
}
