<?php
namespace cspImportExport\Import\FileTypeImport;

class UspProductImport extends \cspImportExport\Import\FileTypeImport\CSPImportBase {

	/**
	 * This function validates, prepare & proceed with the import & updatiing of the rules passed as
	 * the parameter , returns the detailed status of the import
	 *
	 * @param array $rules
	 * @param array $headerMap
	 * @return array
	 */
	public function importRules( $rules, $headerMap = array()) {
		$toInsert 	   = array();
		$toUpdate 	   = array();
		$toSkip   	   = array();
		$importedRules = array();

		$rules           = $this->validateRules($rules);
		$distictEntities = $this->getUniqueueEntitiesFromValidRules($rules, $headerMap);
		//get related rules from the database;
		$cspRelatedRules = $this->getRelatedRulesFromDB($distictEntities);
		unset($distictEntities);

		foreach ($rules as $key => $aRule) {
			if ('valid'!==$aRule['status']) {
				$toSkip[$key] = $aRule;
				continue;
			} 
			$dbKey = $aRule['user'] . '_' . $aRule['product_id'] . '_' . $aRule['min_quantity'];

			if (isset($cspRelatedRules[$dbKey])) {
				$flatOrPercent = ( !empty($aRule['%_discount']) && $aRule['%_discount']>0 )?2:1;
				$price         = 2==$flatOrPercent?$aRule['%_discount']:$aRule['flat_price'];
				$value 		   = $flatOrPercent . '_' . round((float) $price, 4);
				if ($cspRelatedRules[$dbKey]==$value) {
					$aRule['status'] = __('Already Exists', 'customer-specific-pricing-for-woocommerce');
					$toSkip[]        = $aRule;
					continue;
				}
				$toUpdate[$key] = $aRule;
			} else {
				$toInsert[$key] = $aRule;
			}
		}

		$inserted = $this->insertRules($toInsert);
		unset($toInsert);
		
		$updated = $this->updateRules($toUpdate);
		unset($toUpdate);
		

		for ($i=0; $i < count($rules); $i++) { 
			$importedRules[$i] = isset($inserted[$i])?$inserted[$i]:'';
			$importedRules[$i] = isset($updated[$i])?$updated[$i]:$importedRules[$i];
			$importedRules[$i] = isset($toSkip[$i])?$toSkip[$i]:$importedRules[$i];
		}

		$statusReport = array(
			'recordsProcessed' => \count($rules),
			'recordsInserted'  => \count($inserted),
			'recordsUpdated'   => \count($updated),
			'recordsSkipped'   => \count($toSkip)
		);

		return array('statusReport' => $statusReport, 'importDetails'=>$importedRules);
	}

	/**
	 * Fetches unique Users, Product Id, Minimum Quantity values from the
	 * valid rules. maintains an array of such unique entities & returns the same.
	 *
	 * @param array $rules list of CSP rules fetched from the CSV file processed with the basic validations.
	 * @param array $headerMap mapping of CSP headers to the columns in the CSV file
	 * @return array array of unique entities {
	 * 	'users' => array(),
	 *  'product_ids' = array(),
	 *  'minimum_quantities' => array(),
	 * }
	 */
	public function getUniqueueEntitiesFromValidRules( $rules, $headerMap = array()) {
		$uniqueUsers           = array();
		$uniqueueProductIds    = array();
		$uniqueueMinQuantities = array();
		
		foreach ($rules as $aRule) {
			if ('valid'!=$aRule['status']) {
				continue;
			} 

			if (!in_array($aRule['user'], $uniqueUsers, true) && is_numeric($aRule['user'])) {
				array_push( $uniqueUsers, $aRule['user']);
			}

			if (!in_array($aRule['product_id'], $uniqueueProductIds, true)) {
				array_push( $uniqueueProductIds, $aRule['product_id']);
			}

			if (!in_array($aRule['min_quantity'], $uniqueueMinQuantities, true)) {
				array_push( $uniqueueMinQuantities, $aRule['min_quantity']);
			}
		}

		return array('users'=>$uniqueUsers, 'product_ids'=>$uniqueueProductIds, 'quantities'=>$uniqueueMinQuantities);
	}

	/**
	 * Fetches existing CSP rules from the DB based on the unique user ids, product ids & miniimum quantities
	 *
	 * @param array $distictEntities
	 * @return array cspRules
	 */
	public function getRelatedRulesFromDB( $distictEntities ) {
		global $wpdb;
		$rules = array();

		if (empty($distictEntities['product_ids']) || empty($distictEntities['quantities']) || empty($distictEntities['users'])) {
			return $rules;	
		}

		$prepareArgs = array_merge($distictEntities['product_ids'], $distictEntities['quantities']);
		$prepareArgs = array_merge($distictEntities['users'], $prepareArgs);

		$result = $wpdb->get_results($wpdb->prepare('SELECT * From ' . $wpdb->prefix . 'wusp_user_pricing_mapping WHERE user_id IN (' . implode(', ', array_fill(0, count($distictEntities['users']), '%d')) . ') AND product_id IN(' . implode(', ', array_fill(0, count($distictEntities['product_ids']), '%d')) . ') AND min_qty IN (' . implode(', ', array_fill(0, count($distictEntities['quantities']), '%d')) . ')', $prepareArgs));

		foreach ($result as $aRule) {
			$key 		 = $aRule->user_id . '_' . $aRule->product_id . '_' . $aRule->min_qty;
			$rules[$key] = $aRule->flat_or_discount_price . '_' . (float) $aRule->price;			
		}

		return $rules;
	}


	/**
	 * Iterate through all the rules, replaces all the usernames with the
	 * user ids of that perticular user, marks the rule status invalid if the
	 * user id is not found in the site users.
	 *
	 * @param array $rules array of rules fetched from the import file.
	 * @return array $rulesWithUserIds  rules with user ids for the names in the rules.
	 */
	public function replaceUserNamesWithIds( $rules = array()) {
		$rulesWithUserIds = array();
		$userNameIdPairs  = self::getAllUserNameIdPairs();

		if (!empty($rules)) {
			foreach ($rules as $aRule) {
				if (!isset($userNameIdPairs[$aRule['user']]) || $userNameIdPairs[$aRule['user']]<=0) {
					$aRule['status'] = __('User not found', 'customer-specific-pricing-for-woocommerce');
				} else {
					$aRule['user'] = $userNameIdPairs[$aRule['user']];
				}
				 
				$rulesWithUserIds[] = $aRule;
			}
		}
		return $rulesWithUserIds;
	}



	/**
	 * Replaces the usernames with User Ids
	 * Validates if the product Id entered exists or not.
	 * update the validation status accordingly & returns the rule array
	 *
	 * @param array $rules
	 * @return void $validatedRules
	 */
	public function validateRules( $rules ) {
		$rules              = $this->replaceUserNamesWithIds($rules);
		$validateProductIds = apply_filters('wdm_csp_import_validte_product_ids', true);
		if (!$validateProductIds) {
			return $rules;
		} 

		$validatedRules  = array();
		$validProductIds = self::getAllValidProductIds();
		foreach ($rules as $aRule) {
			if (!in_array($aRule['product_id'], $validProductIds)) {
				$aRule['status'] = __('Product Id does not exists or the product is not supported', 'customer-specific-pricing-for-woocommerce');
			}
			$validatedRules[] = $aRule;
		}

		return $validatedRules;
	}

	/**
	 * Inserts the rules passed to the database one after the other,
	 * saves the status of the insert operation in the rule status &
	 * returns the same array with the insert status. 
	 *
	 * @param array $rules 
	 * @return array $rules
	 */
	public function insertRules( $rules = array()) {
		global $wpdb;
		$insertedRules = array();

		foreach ($rules as $aRule) {
			$flatOrPercent = ( !empty($aRule['%_discount']) && $aRule['%_discount']>0 )?2:1;
			$price         = 2==$flatOrPercent?$aRule['%_discount']:$aRule['flat_price'];
			$insertStatus  =$wpdb->insert($wpdb->prefix . 'wusp_user_pricing_mapping', array(
				'price'                     => $price,
				'product_id'                => $aRule['product_id'],
				'user_id'                   => $aRule['user'],
				'flat_or_discount_price'    => $flatOrPercent,
				'min_qty'                   => $aRule['min_quantity'],
				), array(
				'%s',
				'%d',
				'%d',
				'%d',
				'%d',
			));

			if ($insertStatus) {
				$aRule['status'] = __('Inserted', 'customer-specific-pricing-for-woocommerce');
			} else {
				$aRule['status'] = __('Failed inserting the rule', 'customer-specific-pricing-for-woocommerce');
			}
			$insertedRules[] = $aRule;
		}
		return $insertedRules;
	}


	/**
	 * Updates the rules passed in the database table, 
	 * records the status of the update & returns an array of rules
	 * with the update status.
	 *
	 * @param array $rules
	 * @return array $rules
	 */
	public function updateRules( $rules = array()) {
		global $wpdb;
		$updatedRules = array();

		foreach ($rules as $aRule) {
			$flatOrPercent = ( !empty($aRule['%_discount']) && $aRule['%_discount']>0 )?2:1;
			$price         = 2==$flatOrPercent?$aRule['%_discount']:$aRule['flat_price'];

			$updated = $wpdb->update($wpdb->prefix . 'wusp_user_pricing_mapping', 
				array('price'=>$price, 'flat_or_discount_price'=>$flatOrPercent),
				array( 'user_id'=>$aRule['user'], 'product_id'=>$aRule['product_id'], 'min_qty'=>$aRule['min_quantity']), 
				array('%s','%d'), 
				array( '%d','%d','%d')
			);

			if (false==$updated) {
				$aRule['status'] = __('Failed updating the rule', 'customer-specific-pricing-for-woocommerce');
			} else {
				$aRule['status'] = __('Updated', 'customer-specific-pricing-for-woocommerce');
			}
			$updatedRules[] = $aRule;
		}
		return $updatedRules;
	}

}
