<?php
namespace cspImportExport\Import\FileTypeImport;

require_once 'class-import-usp-product.php';

class UspProductSkuImport extends \cspImportExport\Import\FileTypeImport\UspProductImport {

	public function importRules( $rules, $headerMap = array()) {
		$toInsert 	   = array();
		$toUpdate 	   = array();
		$toSkip   	   = array();
		$importedRules = array();

		$rules           = $this->addProductIdsForSKUs($rules);
		$rules           = $this->validateRules($rules);
		$distictEntities = $this->getUniqueueEntitiesFromValidRules($rules, $headerMap);
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
	 * This function fetches all the product SKU-Id pairs & replases all the SKU entries with respective
	 * product Ids
	 *
	 * @param array $rules
	 * @return array $rules
	 */
	public function addProductIdsForSKUs( $rules ) {
		$rulesWithProductIds = array();
		$skuIdPairs 		 = self::getAllSKUProductIdPairs();

		if (!empty($rules)) {
			foreach ($rules as $aRule) {
				if (!isset($skuIdPairs[$aRule['product_sku']]) || $skuIdPairs[$aRule['product_sku']]<=0) {
					$aRule['product_id'] = '';
					$aRule['status'] = __('SKU not found', 'customer-specific-pricing-for-woocommerce');
				} else {
					$aRule['product_id'] = $skuIdPairs[$aRule['product_sku']];
				}
				 
				$rulesWithProductIds[] = $aRule;
			}
		}
		return $rulesWithProductIds;
	}


}
