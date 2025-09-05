<?php
namespace cspImportExport\Import\FileTypeImport;

class GspGlobalImport extends \cspImportExport\Import\FileTypeImport\CSPImportBase {

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

		$rules           = $this->replaceGroupNamesWithGroupIds($rules);
		$distictEntities = $this->getUniqueueEntitiesFromValidRules($rules, $headerMap);
		//get related rules from the database;
		$cspRelatedRules = $this->getRelatedRulesFromDB($distictEntities);
		unset($distictEntities);

		foreach ($rules as $key => $aRule) {
			if ('valid'!==$aRule['status']) {
				$toSkip[$key] = $aRule;
				continue;
			} 
			$dbKey = $aRule['group'] . '_' . $aRule['min_quantity'];

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
	 * Iterate through all the rules, replaces all the usernames with the
	 * user ids of that perticular user, marks the rule status invalid if the
	 * user id is not found in the site users.
	 *
	 * @param array $rules array of rules fetched from the import file.
	 * @return array $rulesWithUserIds  rules with user ids for the names in the rules.
	 */
	public function replaceGroupNamesWithGroupIds( $rules = array()) {
		$rulesWithGroupIds = array();
		$groupNameIdPairs  = self::getAllUserGroupIdNamePairs();

		if (!empty($rules)) {
			foreach ($rules as $aRule) {
				if (!isset($groupNameIdPairs[$aRule['group']]) || $groupNameIdPairs[$aRule['group']]<=0) {
					$aRule['status'] = __('User group not found', 'customer-specific-pricing-for-woocommerce');
				} else {
					$aRule['group'] = $groupNameIdPairs[$aRule['group']];
				}
				 
				$rulesWithGroupIds[] = $aRule;
			}
		}
		return $rulesWithGroupIds;
	}


		/**
	 * Fetches unique Users, Product Id, Minimum Quantity values from the
	 * valid rules. maintains an array of such unique entities & returns the same.
	 *
	 * @param array $rules list of CSP rules fetched from the CSV file processed with the basic validations.
	 * @param array $headerMap mapping of CSP headers to the columns in the CSV file
	 * @return array array of unique entities {
	 * 	'users' => array(),
	 *  'minimum_quantities' => array(),
	 * }
	 */
	public function getUniqueueEntitiesFromValidRules( $rules, $headerMap = array()) {
		$uniqueGroups          = array();
		$uniqueueMinQuantities = array();
		
		foreach ($rules as $aRule) {
			if ('valid'!=$aRule['status']) {
				continue;
			} 

			if (!in_array($aRule['group'], $uniqueGroups, true) && is_numeric($aRule['group'])) {
				array_push( $uniqueGroups, $aRule['group']);
			}

			if (!in_array($aRule['min_quantity'], $uniqueueMinQuantities, true)) {
				array_push( $uniqueueMinQuantities, $aRule['min_quantity']);
			}
		}

		return array('groups'=>$uniqueGroups, 'quantities'=>$uniqueueMinQuantities);
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

		if (empty($distictEntities['quantities']) || empty($distictEntities['groups'])) {
			return $rules;	
		}

		$prepareArgs = array_merge($distictEntities['groups'], $distictEntities['quantities']);

		$result = $wpdb->get_results($wpdb->prepare('SELECT * From ' . $wpdb->prefix . 'wcsp_global_discount_rule_mapping WHERE rule_type = "GSP" AND type_id IN (' . implode(', ', array_fill(0, count($distictEntities['groups']), '%d')) . ') AND min_qty IN (' . implode(', ', array_fill(0, count($distictEntities['quantities']), '%d')) . ')', $prepareArgs));

		foreach ($result as $aRule) {
			$key 		 = $aRule->type_id . '_' . $aRule->min_qty;
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
			$insertStatus  =$wpdb->insert($wpdb->prefix . 'wcsp_global_discount_rule_mapping', array(
				'price'                     => $price,
				'rule_type'					=> 'GSP',
				'type_id'                   => $aRule['group'],
				'flat_or_discount_price'    => $flatOrPercent,
				'min_qty'                   => $aRule['min_quantity'],
				), array(
				'%s',
				'%s',
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

			$updated = $wpdb->update($wpdb->prefix . 'wcsp_global_discount_rule_mapping', 
				array('price'=>$price, 'flat_or_discount_price'=>$flatOrPercent),
				array('type_id'=>$aRule['group'], 'rule_type'=>'GSP', 'min_qty'=>$aRule['min_quantity']), 
				array('%s','%d'), 
				array('%d','%s','%d')
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
