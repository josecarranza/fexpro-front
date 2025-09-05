<?php 
namespace cspImportExport\Import;

if (!class_exists('WisdmCSPImportCommons')) {
	/**
	 * This class contains the common methods used by CSP import feature.
	 */
	class WisdmCSPImportCommons {
		
		/**
		 * This method validates if the CSP import file headers are valid,
		 * determines the type of import rules based on the headers & returns the same.
		 *
		 * @param array $rawFileHeaders array of header strings
		 * @return bool|string false if headers are invalid for any rule type else type of the file based on the headers
		 */
		public static function  validateCSVByHeaders( $rawFileHeaders ) {
			$validHeaders = array(
				'usp-product' 		=> array('user',  'product_id', 'min_quantity', '%_discount', 'flat_price'),
				'rsp-product' 		=> array('role',  'product_id', 'min_quantity', '%_discount', 'flat_price'),
				'gsp-product' 		=> array('group', 'product_id', 'min_quantity', '%_discount', 'flat_price'),
				'usp-product-sku' 	=> array('user',  'product_sku', 'min_quantity', '%_discount', 'flat_price'),
				'rsp-product-sku' 	=> array('role',  'product_sku', 'min_quantity', '%_discount', 'flat_price'),
				'gsp-product-sku' 	=> array('group', 'product_sku', 'min_quantity', '%_discount', 'flat_price'),
				'usp-category'	    => array('user',  'category_slug', 'min_quantity', '%_discount', 'flat_price'),
				'rsp-category'	    => array('role',  'category_slug', 'min_quantity', '%_discount', 'flat_price'),
				'gsp-category'	    => array('group', 'category_slug', 'min_quantity', '%_discount', 'flat_price'),
				'usp-global'		=> array('user',  'min_quantity', '%_discount', 'flat_price'),
				'rsp-global'		=> array('role',  'min_quantity', '%_discount', 'flat_price'),
				'gsp-global'		=> array('group', 'min_quantity', '%_discount', 'flat_price'),
			);

			
			$rawFileHeaders = self::getEquivalentNewHeadersForOldHeaders($rawFileHeaders);
			$fileHeaders 	= self::getValidFileHeaders($rawFileHeaders);

			if (empty($fileHeaders) || \count($fileHeaders)<4) {
				return array('success'=>false, 'data'=>__('Empty or Invalid File Headers', 'customer-specific-pricing-for-woocommerce'));
			}

			$noOfFileHeaders = \count($fileHeaders);
			foreach ($validHeaders as $headerType => $headers) {

				if (\count($headers)==$noOfFileHeaders && \count(array_intersect($fileHeaders, $headers))==$noOfFileHeaders) {
					return array('success' => true,
								 'data'	   => array('type' => $headerType,
													'valid_headers_file_columns_mapping' => self::getValidHeadersAndFileColumnsMapping($headers, $rawFileHeaders)
								 ));
				}
			}

			return array('success'=>false, 'data'=>__('File headers did not matched any CSP rule type for Import', 'customer-specific-pricing-for-woocommerce'));
		}

		/**
		 * Replaces the file headers used in the older CSP import feature with the equivalent new headers
		 * and returns the header array.
		 *
		 * @param array $fileHeaders - 
		 * @return array $fileHeaders Array of file headers replased with the new headers. 
		 */
		public static function getEquivalentNewHeadersForOldHeaders( $fileHeaders = array()) {
			$headers 		= array();
			$headers['old'] = array( 'user_id', 'group_id', 'product id', 'sku', '%', 'flat', 'min qty', 'min_qty', 'group name');
			$headers['new'] = array( 'user', 'group', 'product_id', 'product_sku', '%_discount', 'flat_price', 'min_quantity', 'min_quantity', 'group');

			$newHeaders = array();
			foreach ($fileHeaders as $header) {
				$header = \strtolower($header);
				$key 	= \array_search($header, $headers['old']);
				if (false!==$key) {
					$header = $headers['new'][$key];
				}
				$newHeaders[] = $header;
			}
			return $newHeaders;
		}

		/**
		 * This function filters out the non required headers(column names) from the received
		 * array of coliumns of the uploaded csv file & returns the filtered array.  
		 *
		 * @param array $fileHeaders array of column names in the CSV file uploaded
		 * @return array 
		 */
		public static function getValidFileHeaders( $fileHeaders = array()) {
			$validHeaders = array('user', 'role', 'group', 'product_id', 'product_sku', 'category_slug', 'min_quantity', 'flat_price', '%_discount');
			/**
			 * This filter can be used to add/remove the valid/supported column names from the list of valid headers,
			 * this filter can be used to conditionally disable filtering the values when & if required.
			 * 
			 * @param array $validHeaders - Supported column values in CSP import.
			 */
			$validHeaders = apply_filters('wdm_csp_import_filter_all_valid_columns', $validHeaders);

			if (empty($validHeaders)) {
				return $fileHeaders;
			}

			$validHeadersFromFileHeaders = array();
			foreach ($fileHeaders as $columnName) {
				if (in_array($columnName, $validHeaders) && !in_array($columnName, $validHeadersFromFileHeaders)) {
					$validHeadersFromFileHeaders[] = $columnName;
				}
			}

			return $validHeadersFromFileHeaders;
		}


		/**
		 * This function maps the indexes of header array as per their position in actual uploaded file
		 * based on the position of the header in raw file.
		 *
		 * @param array $headers
		 * @param array $rawFileHeaders
		 * @return array array with the indexes pointing to the column numbers of the uploaded files.
		 */
		public static function getValidHeadersAndFileColumnsMapping( $headers = array(), $rawFileHeaders = array()) {
			$columnMapping = array();
			
			foreach ($headers as $validHeader) {
				$position = array_search($validHeader, $rawFileHeaders);
				if (false!==$position) {
					$columnMapping[$validHeader] = $position;
				}
			}

			return $columnMapping;
		}


		/**
		 * Returns the csp default batch size after aplying the filters
		 *
		 * @return int
		 */
		public static function getBatchSize() {
			/**
			 * Filter to increase or reduce the batch size defined in CSP rule import
			 * 
			 * @param int $batchSize default 2000.
			 */
			$batchsize = apply_filters('csp_import_batch_size', 2000);
			//split huge CSV file by BatchSize we can modify this based on the need
			//$batchsize =get_option('dd_import_batch_size')?get_option('dd_import_batch_size'):$batchsize;
			return $batchsize;
		}

		/**
		 * This method generates directory path for the csv batches directory
		 * Checks if that directory exists if it does not exists create the directory
		 * if it's exists unlink all the previous files in the directory & returns
		 * the directory path.
		 *
		 */
		public static function getCSPImportBatchesDirectory( $dirName = 'batch_files') {
			// generate upload dir path
			$importDir = self::getDefaultImportFileDirectory();
			$batchDir  = $importDir . "/$dirName";

			/*Creating importCsv dir if not exist
			in uploads dir to save batch/chunks files*/
			if (!file_exists($batchDir)) {
				wp_mkdir_p($batchDir);
			}
		
			return $batchDir;
		}


		/**
		 * Removes all the existing files in the directory
		 * specified by the parameter
		 *
		 * @param string $dirName - path of the directory to be cleared up
		 * @return string directory path which is cleared
		 */
		public static function clearDirectory( $dirName) {
			$all_files = glob($dirName . '/*.csv');
			if ($all_files) {
				foreach ($all_files as $file) {
					unlink($file);
				}
			}
			return $dirName;
		}


		/**
		 * Returns the default name for the import file
		 */
		public static function getDefaultImportFileName() {
			/**
			 * Filter which can be used to change the default temporary storage file name of the import file before import
			 * 
			 * @param string $name file name.
			 */
			return apply_filters('wdm_csp_filter_new_import_raw_file_name', 'current_csp_import.csv');
		}


		/**
		 * Returns the default name for the import report file 
		 *
		 * @return string default name for the uploaded import report file.
		 */
		public static function getDefaultImportReportFileName() {
			/**
			 * Filter which can be used to change the default temporary storage file name of the import file before import
			 * 
			 * @param string $name file name.
			 */
			return apply_filters('wdm_csp_filter_new_import_report_file_name', 'import_report.csv');
		}


		/**
		 * Returns the default import file directory path.
		 *
		 * @return string import file direcory path
		 */
		public static function getDefaultImportFileDirectory() {
			/**
			 * Filter which can be used to change the default temporary storage directory name of the import file before import
			 * 
			 * @param string $name directory name.
			 */
			$cspImportDirName = apply_filters('wdm_csp_filter_new_import_raw_upload_path', 'csp-import-files');
			$uploadsDir       = wp_upload_dir();
			return $uploadsDir['basedir'] . '/' . $cspImportDirName; 
		}
	}
	
}
