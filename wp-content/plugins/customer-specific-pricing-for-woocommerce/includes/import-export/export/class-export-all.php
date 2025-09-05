<?php

namespace cspImportExport\cspExport;

if (!class_exists('WdmWuspExportAll')) { 
	/**
	 * A class containing method to export all the csp rules , generate the backup & create an archieve for the same.
	 *
	 * @since 4.6.3
	 */
	class WdmWuspExportAll {
		public $filePath = '';
		public $fileUrl  = '';
		public $fileName = '';

		/**
		 * Calls a function to generate all the export files, creates zip archive 
		 * & store the generated file path & url in the class members.
		 */
		public function generateBackup() {
			$files 	 = $this->generateExportFiles();
			if (!self::isEmptyAssocArray($files)) {
				$this->setFileName('CSPRuleBackup.zip');
				$archive = $this->createArchive($files);
				$this->filePath = isset($archive['path'])?$archive['path']:'';
				$this->fileUrl  = isset($archive['url'])?$archive['url']:'';
			}
		}

		/**
		 * This function is used to generate rule CSVs, store the records of generated backup files
		 * and returns them as an array.
		 *
		 * @return array filename => filepath
		 */
		private function generateExportFiles() {
			include_once plugin_dir_path(CSP_PLUGIN_FILE) . 'includes/import-export/export/class-wdm-wusp-export.php';
			$filesGenerated 					= array();
			$filesGenerated['product_usp.csv']  = $this->getFileProductUSP();
			$filesGenerated['product_rsp.csv']  = $this->getFileProductRSP();
			$filesGenerated['product_gsp.csv']  = $this->getFileProductGSP();
			$filesGenerated['category_usp.csv'] = $this->getFileCategoryRulesFor('User');
			$filesGenerated['category_rsp.csv'] = $this->getFileCategoryRulesFor('Role');
			$filesGenerated['category_gsp.csv'] = $this->getFileCategoryRulesFor('Group');
			$filesGenerated['global_usp.csv']   = $this->getFileGlobalRulesFor('User');
			$filesGenerated['global_rsp.csv']   = $this->getFileGlobalRulesFor('Role');
			$filesGenerated['global_gsp.csv']   = $this->getFileGlobalRulesFor('Group');
			return $filesGenerated;
		}

		/**
		 * This file creates a zip archive of the file paths specified as the arguements
		 * and returns the path of a created zip archive.
		 *
		 * @param array $files array of filename->filepath pairs
		 * @param string $returnFile URL|Path
		 * @return void
		 */
		private function createArchive( $files, $returnFile = 'url') {
			$uploadDir = wp_upload_dir();
			if (empty($files)) {
				return ;
			}
			$exportArchiveName = $this->getFileName();
			$exportArchive     = $uploadDir['basedir'] . '/' . $exportArchiveName;
			$deleteFile		   = glob($exportArchive);
			if ($deleteFile) {
				foreach ($deleteFile as $file) {
					unlink($file);
				}
			}
			$zip = new \ZipArchive();
			if ($zip->open($exportArchive, \ZipArchive::CREATE)!==true) {
				return '';
			}

			foreach ($files as $fileName => $filePath) {
				if (''!==$filePath) {
					$zip->addFile($filePath, $fileName);
				}
			}
			$zip->close();
			return array('path'=>$exportArchive, 'url'=>$uploadDir['baseurl'] . '/' . $exportArchiveName);
		}

		/**
		 * This function generates user specific product pricing rule.
		 *
		 * @return string filePath
		 */
		private function getFileProductUSP() {
			include_once plugin_dir_path(CSP_PLUGIN_FILE) . 'includes/import-export/export/process-export/class-wdm-wusp-user-specific-pricing-export.php';
			$ruleFile	 = ''; 
			$uspProducts = new \cspImportExport\cspExport\WdmWuspUserSpecificPricingExport();
			if (!empty($uspProducts)) {
				$ruleFile = $uspProducts->getCSVFile( '', 'path');
			}
			return $ruleFile; 
		}
		
		/**
		 * This function generates role specific product pricing rule.
		 *
		 * @return string filePath
		 */
		private function getFileProductRSP() {
			include_once plugin_dir_path(CSP_PLUGIN_FILE) . 'includes/import-export/export/process-export/class-wdm-wusp-role-specific-pricing-export.php';
			$ruleFile	 = ''; 
			$rspProducts = new \cspImportExport\cspExport\WdmWuspRoleSpecificPricingExport();
			if (!empty($rspProducts)) {
				$ruleFile = $rspProducts->getCSVFile( '', 'path');
			}
			return $ruleFile; 
		}

		/**
		 * This function generates group specific product pricing rule.
		 *
		 * @return string filePath
		 */
		private function getFileProductGSP() {
			include_once plugin_dir_path(CSP_PLUGIN_FILE) . 'includes/import-export/export/process-export/class-wdm-wusp-group-specific-pricing-export.php';
			$ruleFile	 = ''; 
			$gspProducts = new \cspImportExport\cspExport\WdmWuspGroupSpecificPricingExport();
			if (!empty($gspProducts)) {
				$ruleFile = $gspProducts->getCSVFile( '', 'path');
			}
			return $ruleFile; 
		}

		/**
		 * This function generates user, role & group specific category pricing rule files based on the argument.
		 *
		 * @param string $ruleType User|Role|Group
		 * @return string filePath
		 */
		private function getFileCategoryRulesFor( $ruleType ) {
			$ruleFile = ''; 
			include_once plugin_dir_path(CSP_PLUGIN_FILE) . 'includes/import-export/export/class-category-rule-export.php';
			if (class_exists('cspCategoryPricing\cspExport\CSPCategoryRuleExport')) {
				$ruleFile = \cspCategoryPricing\cspExport\CSPCategoryRuleExport::getExportFile($ruleType, 'path');
			}
			return $ruleFile; 
		}
		
		/**
		 * This function generates user, role & group specific global pricing rule files based on the argument.
		 *
		 * @param string $ruleType User|Role|Group
		 * @return string filePath
		 */
		private function getFileGlobalRulesFor( $ruleType ) {
			$ruleFile = ''; 
			include_once plugin_dir_path(CSP_PLUGIN_FILE) . 'includes/import-export/export/class-global-discounts-rule-export.php';
			if (class_exists('cspGlobalDiscounts\CSPGlobalDiscountsRuleExport')) {
				$ruleFile = \cspGlobalDiscounts\CSPGlobalDiscountsRuleExport::getExportFile($ruleType, 'path');
			}
			return $ruleFile; 
		}
		

		/**
		 * This function can be used to store the automatically generated backup files to the
		 * storage pah generated for the backup files, the parameter $minimumFilesToStore defines
		 * maximum backup files to be stored on the location, using which the function removes the 
		 * older files generated by automatic backups.
		 *
		 * @param integer $minimumFilesToStore
		 * @param string $filePath
		 * @param string $fileName
		 * @return void
		 */
		public function autoBackupFileStorage( $minimumFilesToStore = 5, $filePath = '', $fileName = '') {
			$fileList 	   = array();
			$backupDirPath = WP_CONTENT_DIR . '/csp_backups';
			$backupUrlBase = content_url();
			if (!is_dir($backupDirPath)) {
				mkdir($backupDirPath, 755, true);
			}

			if (is_dir($backupDirPath)) {
				$files = scandir($backupDirPath);
				if (!empty($files)) {
					foreach ($files as $fName) {
						$fPath = $backupDirPath . '/' . $fName;
						if (is_file($fPath)) {
							$fileTime = gmdate('U', filemtime($fPath));
							// $fileUrl  = $backupUrlBase . '/csp_backups/' . $fileName;	
							$fileList[$fileTime] = $fName;
						}
					}
				}

				$numberOfFilesToDelete = count($fileList)-$minimumFilesToStore;
				if (!empty($fileList) && $numberOfFilesToDelete>=0) {
					\arsort($fileList);
					for ($i=0; $i <=$numberOfFilesToDelete ; $i++) { 
						$fileToDelete = array_pop($fileList);
						unlink($backupDirPath . '/' . $fileToDelete);
					}
				}

				$status = rename($filePath, $backupDirPath . '/' . $fileName);

			}
			return false;
		}


		/**
		 * Getter for File Path.
		 *
		 * @return string path of the downloadable zip file.
		 */
		public function getFilePath() {
			return $this->filePath;
		}

		/**
		 * Getter for File URL.
		 *
		 * @return string url of the downloadable zip file.
		 */
		public function getFileUrl() {
			return $this->fileUrl;
		}


		/**
		 * Getter function for the archive file name
		 *
		 * @return string $fileName name of the backup archive
		 */
		public function getFileName() {
			if (isset($this->fileName)) {
				return $this->fileName;
			} 

			return apply_filters('wdm_csp_filter_rule_archive_file_name', 'CSPRuleBackup.zip');
		}

		/**
		 * Setter function for the archive file name
		 *
		 * @param string $fileName
		 */
		public function setFileName( $fileName = '') {
			$fileName 		= sanitize_text_field($fileName);
			$this->fileName = $fileName;
		}


		/**
		 * Checks if associative array is empty & returns false if any of the
		 * value for any of the key is not empty.
		 *
		 * @param array $array
		 * @return boolean
		 */
		public static function isEmptyAssocArray( $array = array() ) {
			if (empty($array)) {
				return false;
			}

			$empty = true;
			foreach ($array as $key => $value) {
				if (!empty($value) ) {
					$empty = false;
					break;
				}
			}
			return $empty;
		}
	}
}
