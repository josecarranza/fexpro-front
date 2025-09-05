<?php
namespace cspImportExport\Import\FileTypeImport;

class RspCategoryImport extends \cspImportExport\Import\FileTypeImport\CSPImportBase {

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
			$dbKey = $aRule['role'] . '_' . $aRule['category_slug'] . '_' . $aRule['min_quantity'];

			if (isset($cspRelatedRules[$dbKey])) {
				$flatOrPercent = ( !empty($aRule['%_discount']) && $aRule['%_discount']>0 )?2:1;
				$price         = 2==$flatOrPercent?$aRule['%_discount']:$aRule['flat_price'];
				$value 		   = $flatOrPercent . '_' . round($price, 4);
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
	 * Replaces the usernames with User Ids
	 * Validates if the product Id entered exists or not.
	 * update the validation status accordingly & returns the rule array
	 *
	 * @param array $rules
	 * @return void $validatedRules
	 */
	public function validateRules( $rules ) {
		$roles              = self::getAllUserRoleSlugs();
		$validatedRules  	= array();
		$validCategorySlugs = self::getAllCategorySlugs();
		
		foreach ($rules as $aRule) {
			if (!in_array($aRule['role'], $roles)) {
				$aRule['status'] = __('User role not found', 'customer-specific-pricing-for-woocommerce');
			}

			if (!in_array($aRule['category_slug'], $validCategorySlugs)) {
				$aRule['status'] = __('Category not found', 'customer-specific-pricing-for-woocommerce');				
			}
			$validatedRules[] = $aRule;
		}

		return $validatedRules;
	}


		/**
	 * Fetches unique Users, Product Id, Minimum Quantity values from the
	 * valid rules. maintains an array of such unique entities & returns the same.
	 *
	 * @param array $rules list of CSP rules fetched from the CSV file processed with the basic validations.
	 * @param array $headerMap mapping of CSP headers to the columns in the CSV file
	 * @return array array of unique entities {
	 * 	'users' => array(),
	 *  'categories' = array(),
	 *  'minimum_quantities' => array(),
	 * }
	 */
	public function getUniqueueEntitiesFromValidRules( $rules, $headerMap = array()) {
		$uniqueRoles           = array();
		$uniqueueCatSlugs      = array();
		$uniqueueMinQuantities = array();
		
		foreach ($rules as $aRule) {
			if ('valid'!=$aRule['status']) {
				continue;
			} 

			if (!in_array($aRule['role'], $uniqueRoles, true)) {
				array_push( $uniqueRoles, $aRule['role']);
			}

			if (!in_array($aRule['category_slug'], $uniqueueCatSlugs, true)) {
				array_push( $uniqueueCatSlugs, $aRule['category_slug']);
			}

			if (!in_array($aRule['min_quantity'], $uniqueueMinQuantities, true)) {
				array_push( $uniqueueMinQuantities, $aRule['min_quantity']);
			}
		}

		return array('roles'=>$uniqueRoles, 'category_slugs'=>$uniqueueCatSlugs, 'quantities'=>$uniqueueMinQuantities);
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

		if (empty($distictEntities['category_slugs']) || empty($distictEntities['quantities']) || empty($distictEntities['roles'])) {
			return $rules;	
		}

		$prepareArgs = array_merge($distictEntities['category_slugs'], $distictEntities['quantities']);
		$prepareArgs = array_merge($distictEntities['roles'], $prepareArgs);

		$result = $wpdb->get_results($wpdb->prepare('SELECT * From ' . $wpdb->prefix . 'wcsp_role_category_pricing_mapping WHERE role IN (' . implode(', ', array_fill(0, count($distictEntities['roles']), '%s')) . ') AND cat_slug IN(' . implode(', ', array_fill(0, count($distictEntities['category_slugs']), '%s')) . ') AND min_qty IN (' . implode(', ', array_fill(0, count($distictEntities['quantities']), '%d')) . ')', $prepareArgs));

		foreach ($result as $aRule) {
			$key 		 = $aRule->role . '_' . $aRule->cat_slug . '_' . $aRule->min_qty;
			$rules[$key] = $aRule->flat_or_discount_price . '_' . (float) $aRule->price;			
		}

		return $rules;
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
			$insertStatus  =$wpdb->insert($wpdb->prefix . 'wcsp_role_category_pricing_mapping', array(
				'price'                     => $price,
				'cat_slug'                  => $aRule['category_slug'],
				'role'                   	=> $aRule['role'],
				'flat_or_discount_price'    => $flatOrPercent,
				'min_qty'                   => $aRule['min_quantity'],
				), array(
				'%s',
				'%s',
				'%s',
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

			$updated = $wpdb->update($wpdb->prefix . 'wcsp_role_category_pricing_mapping', 
				array('price'=>$price, 'flat_or_discount_price'=>$flatOrPercent),
				array('role'=>$aRule['role'], 'cat_slug'=>$aRule['category_slug'], 'min_qty'=>$aRule['min_quantity']), 
				array('%s','%d'), 
				array('%s','%s','%d')
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
