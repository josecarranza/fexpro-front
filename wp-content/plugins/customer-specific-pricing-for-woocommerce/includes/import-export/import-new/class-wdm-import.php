<?php 
namespace cspImportExport\Import;

require_once 'class-import-commons.php';
if (!class_exists('WisdmCSPImport')) {
	/**
	 * This class contains all the import related main methods 
	 * 
	 * @since 4.6.3
	 */
	class WisdmCSPImport extends WisdmCSPImportCommons {
		

		/**
		 * This function loads & shows the content for the selected subtab in the import
		 * feature based on the selection.
		 *
		 */
		public function showImportPageContent() {
			self::enqueueStyles();
			self::showAdminPageHeaderAndContent();
			self::enqueueScripts();
		}


		/**
		 * Enqueue stylesheets required for the styling on the 
		 * CSP admin import page.
		 * 
		 */
		private static function enqueueStyles() {
			wp_enqueue_style('csp_import_page_bootstrap', plugins_url('import-export/import-new/assets/css/bootstrap.min.css', dirname(dirname(__FILE__))), array(), CSP_VERSION);
			wp_enqueue_style('csp_import_page_style', plugins_url('import-export/import-new/assets/css/import-page.css', dirname(dirname(__FILE__))), array(), CSP_VERSION);
		}


		/**
		 * Prints admin page content based on the subtab selection
		 *
		 * @return void
		 */
		private function showAdminPageHeaderAndContent() {
			global $pagenow;
			if ('admin.php'!=$pagenow) {
				return ;
			} 
			$currentTab = 'import';
			$currentTab = isset($_GET['page']) && isset($_GET['tab']) ? sanitize_text_field($_GET['tab']):$currentTab;
			?>
			<h3 class='csp-tab-header'> <?php esc_html_e('Import CSP Rules', 'customer-specific-pricing-for-woocommerce'); ?>
			</h3>
			<div class='csp-import-header-options'>
				<?php
				if ('scheduled-imports'===$currentTab) {
					wp_enqueue_style('csp_time_picker_lib_style', CSP_PLUGIN_SITE_URL . '/css/single-view/jquery.timepicker.min.css', array(), CSP_VERSION);
					wp_enqueue_script('csp_time_picker_lib_script', CSP_PLUGIN_SITE_URL . '/js/single-view/jquery.timepicker.min.js', array('jquery' ), CSP_VERSION, true);
					wp_enqueue_script('csp_scheduled_imports_script', CSP_PLUGIN_SITE_URL . '/includes/import-export/import-new/assets/js/wdm-csp-scheduled-import.js', array('csp_time_picker_lib_script'), CSP_VERSION);
					$scheduledTasks = self::getScheduledTasks();
					$sftpDisabled   = function_exists('ssh2_connect')?'':'disabled';
					$pageTemplate   = '/includes/import-export/import-new/scheduled-imports-template.php';
					?>
						<a href="?page=wisdm-csp-import&tab=import-page"><?php esc_html_e('Import Rules', 'customer-specific-pricing-for-woocommerce'); ?></a> 
						<span>|</span>
						<?php esc_html_e('Schedule Imports', 'customer-specific-pricing-for-woocommerce'); ?>
					<?php
				} else {
					$pageTemplate = '/includes/import-export/import-new/import-page-template.php';
					?>
						<?php esc_html_e('Import Rules', 'customer-specific-pricing-for-woocommerce'); ?> 
						<span>|</span>
						<a href="?page=wisdm-csp-import&tab=scheduled-imports"><?php esc_html_e('Schedule Imports', 'customer-specific-pricing-for-woocommerce'); ?></a>
					<?php
				}
				?>
			</div>
			<hr>

			<?php
			include_once CSP_PLUGIN_PATH . $pageTemplate;
		}

		/**
		 * Enqueues & localize the scripts required on the Import admin menu page
		 *
		 * @return void
		 */
		private static function enqueueScripts() {
			wp_enqueue_script('csp_import_bootstrap_js', plugins_url('import-export/import-new/assets/js/bootstrap.min.js', dirname(dirname(__FILE__))), array('jquery'), CSP_VERSION, true);
			wp_enqueue_script('csp_import_settings_js', plugins_url('import-export/import-new/assets/js/wdm-csp-import.js', dirname(dirname(__FILE__))), array('jquery', 'jquery-effects-drop'), CSP_VERSION, true);
			$errorMessages = array(
				'no_valid_rules'			=> __('No valid rules found to save, do you want to remove all the existing global discount rules', 'customer-specific-pricing-for-woocommerce'),
				'save_failed_message'		=> __('Failed to save the rules please try again', 'customer-specific-pricing-for-woocommerce'),
				'request_timeout_message'	=> __('Request timeout occured', 'customer-specific-pricing-for-woocommerce'),
				'invalid_date'				=> __('The date selected seems invalid please check again.', 'customer-specific-pricing-for-woocommerce'),
				'invalid_time'				=> __('The time selected seems invalid please check again.', 'customer-specific-pricing-for-woocommerce'),
				'invalid_weekday'			=> __('The weekday selected seems invalid please check again.', 'customer-specific-pricing-for-woocommerce'),
				'invalid_file_connection'	=> __('Please select by which method you want to import the file.', 'customer-specific-pricing-for-woocommerce'),
				'invalid_file_url'			=> __('The file URL entered seems invalid please check again.', 'customer-specific-pricing-for-woocommerce'),
				'invalid_ftp_url'			=> __('The FTP/SFTP URL entered seems invalid please check again.', 'customer-specific-pricing-for-woocommerce'),
				'invalid_file_path'			=> __('The file path entered is invalid or empty', 'customer-specific-pricing-for-woocommerce'),
			);


			$importFileTypes = array(
				'usp-product' 		=> __('Importing, User Specific Rules For Products Based On Product Id\'s', 'customer-specific-pricing-for-woocommerce'),
				'rsp-product' 		=> __('Importing, Role Specific Rules For Products Based On Product Id\'s', 'customer-specific-pricing-for-woocommerce'),
				'gsp-product' 		=> __('Importing, Group Specific Rules For Products Based On Product Id\'s', 'customer-specific-pricing-for-woocommerce'),
				'usp-product-sku' 	=> __('Importing, User Specific Rules For Products Based On SKU', 'customer-specific-pricing-for-woocommerce'),
				'rsp-product-sku' 	=> __('Importing, Role Specific Rules For Products Based On SKU', 'customer-specific-pricing-for-woocommerce'),
				'gsp-product-sku' 	=> __('Importing, Group Specific Rules For Products Based On SKU', 'customer-specific-pricing-for-woocommerce'),
				'usp-category'	    => __('Importing, User Specific Rules For Categories', 'customer-specific-pricing-for-woocommerce'),
				'rsp-category'	    => __('Importing, Role Specific Rules For Categories', 'customer-specific-pricing-for-woocommerce'),
				'gsp-category'	    => __('Importing, Group Specific Rules For Categories', 'customer-specific-pricing-for-woocommerce'),
				'usp-global'		=> __('Importing, User Specific Sitewide(global) Rules', 'customer-specific-pricing-for-woocommerce'),
				'rsp-global'		=> __('Importing, Role Specific Sitewide(global) Rules ', 'customer-specific-pricing-for-woocommerce'),
				'gsp-global'		=> __('Importing, Group Specific Sitewide(global) Rules ', 'customer-specific-pricing-for-woocommerce'),
			);

			$importSettingsPageObject = array(
				'ajax_url' 			=> admin_url('admin-ajax.php'),
				'nonce'    			=> wp_create_nonce('wdm-csp-import'),
				'error_mesages'		=> $errorMessages,
				'import_file_types' => $importFileTypes,
				'save_text'			=> __('Save All', 'customer-specific-pricing-for-woocommerce'),
				'saving_text'		=> __('Saving', 'customer-specific-pricing-for-woocommerce'),
				'save_success_message'=> __('Successfully Updated The Rules', 'customer-specific-pricing-for-woocommerce'),
				'upload_in_progress_text'=>__('Please Wait, File upload is in progress', 'customer-specific-pricing-for-woocommerce'),
				'upload_successful_text'=>__('The CSV file is uploaded successfully, Starting Import.', 'customer-specific-pricing-for-woocommerce'),
				'import_button_warning' => __('Select a file & import', 'customer-specific-pricing-for-woocommerce'),
				'window_close_confirm_message' => __('Are you sure to leave this page ?', 'customer-specific-pricing-for-woocommerce'),
				'notice_header'		=> __('CSP Import Notification', 'customer-specific-pricing-for-woocommerce'),
				'preparing_file_for_import'=> __('Preparing the file for import', 'customer-specific-pricing-for-woocommerce'),
				'no_of_simultaneous_batches'=> get_option('dd_simultaneous_threads', 2),
				'ftp_connection_success_text'=> __('Success, connection using the credentials specified is working as expected.', 'customer-specific-pricing-for-woocommerce'),
				'connection_testing' =>	__('Test Connection', 'customer-specific-pricing-for-woocommerce'),
				'schedule_for_import' => __('Schedule File For Import', 'customer-specific-pricing-for-woocommerce'),
				'schedule_deletion_warning' => __('Are you sure you want to delete the schedule ?', 'customer-specific-pricing-for-woocommerce'),
			);
			wp_localize_script('csp_import_settings_js', 'wdm_csp_import_object', $importSettingsPageObject);
		}



		public function validateImportFile( $fileName = '', $filePath = '') {
			if (file_exists($filePath)) {
				$file    = fopen($filePath, 'r');
				$headers = fgetcsv($file, 0, ',');
				$headers = implode(',', $headers);
				$headers = array_map('trim', explode(',', $headers));
				
				$validity = self::validateCSVByHeaders($headers);
				if (!$validity['success']) {
					return $validity;
				} 
				
				$validity['data']['file_path'] = $filePath;
				$validity['data']['file_name'] = $fileName;
				return $validity;
			}
		}

		// public function addImportFileToTheQueue( $fileData ) {
		// 	$importQueue   = get_option('wdm_csp_import_queue', array());
		// 	$importQueue[] = array(	'file_name'   => $fileData['file_name'],
		// 							'file_path'   => $fileData['file_path'],
		// 							'size'	      => '',
		// 							'file_type'	  => $fileData['type'],
		// 							'headers_map' => $fileData['valid_headers_file_columns_mapping'],
		// 							'status'      => '',
		// 							'report_path' => '',
		// 						);
		// 	update_option('wdm_csp_import_queue', $importQueue);
		// 	return get_option('wdm_csp_import_queue', array());
		// }


		// public function updateQueueRecord( $fileName, $fieldsToUpdate) {
		// 	$importQueue   = get_option('wdm_csp_import_queue', array());
		// 	$importQueue[] = array(	'file_name'   => $fileData['file_name'],
		// 							'file_path'   => $fileData['file_path'],
		// 							'size'	      => '',
		// 							'file_type'	  => $fileData['type'],
		// 							'headers_map' => $fileData['valid_headers_file_columns_mapping'],
		// 							'status'      => '',
		// 							'report_path' => '',
		// 						);

		// 	$updatedRecords = array();
		// 	foreach ($importQueue as $key => $record) {
		// 		if ($record['file_name']==$fileName) {
		// 			foreach ($fieldsToUpdate as $fieldName => $fieldValue) {
		// 				if (isset($record[$fieldName])) {
		// 					$record[$fieldName] = $fieldValue;
		// 				}
		// 			}
		// 		}
		// 		$updatedRecords[] = $record;
		// 	}
		// 	update_option('wdm_csp_import_queue', $updatedRecords);
		// }


		public function splitLargeCSVImportFile( $filePath, $fileType, $headerMap) {

			if (empty($fileInfo)) {
				$fileInfo = array(); //get file data from import queue
			}
			$splitDetails = array();
			$batchDir  	  = self::getCSPImportBatchesDirectory();
			$batchDir  	  = self::clearDirectory($batchDir);
			$batchSize 	  = self::getBatchSize();

			$batchDetails = array();
			$handle       = fopen($filePath, 'r');
			
			if ( false!==$handle) {
				set_time_limit(0);
				$row         = 0;
				$batchNumber = 0;
				$file        = null;
				$data 		 = fgetcsv($handle);
				while (( $data = fgetcsv($handle) ) !== false) {
					$data = array_map('trim', $data);
					//Create new batch if current is full
					if (0 == $row % $batchSize) {
						//closing the previous file handler
						if (null!= $file ) {
							fclose($file);
						}
						$newBatch = "csp_import_batch_$batchNumber.csv";
						$fileName = $batchDir . '/' . $newBatch;
						$file     = fopen($fileName, 'w');
					}

					// $rowData = $this->getValidFieldData($data, $headerMap);					
					fwrite($file, implode(',', $data) . PHP_EOL);

					//sending the splitted CSV files, batch by batch...
					if (0 == $row % $batchSize) {
						
						array_push($batchDetails, array('batchName' => $newBatch,
														'ruleType'  => $fileType,
														'batchNo'   => $batchNumber,
														'headerMap' => $headerMap
														));
						$batchNumber++;
					}
					$row++;
				}
				$splitDetails['total_records'] = $row;
				$splitDetails['batch_details'] = $batchDetails;
				unset($firstLineHeader);
				fclose($handle);
			}

			return $splitDetails;
		}


		/**
		 * This function gets the list of all the scheduled import tasks, fetch their details
		 * and returns array of the details for all the schedules.
		 * 
		 * @since 4.6.3
		 * @return array
		 */
		public static function getScheduledTasks() {
			global $wpdb, $cspFunctions;
			$existingSchedules = array();
			
			$results 	   = $wpdb->get_results('SELECT option_name FROM ' . $wpdb->prefix . 'options WHERE option_name LIKE "csp_import_schedule_%"');
			$reportFile    = array();
			if (!empty($results)) {
				foreach ($results as $option) {
					try {
						$uploadDir   = wp_upload_dir();
						$reportsFile = $uploadDir['basedir'] . '/csp-import-files/' . $option->option_name . '/report_' . $option->option_name . '.csv';
						if ( file_exists($reportsFile) ) {
							$reportFile['url']  = $uploadDir['baseurl'] . '/csp-import-files/' . $option->option_name . '/report_' . $option->option_name . '.csv';
							$reportFile['time'] = gmdate('F d Y, H:i', $cspFunctions->convertToLocalTime(filemtime($reportsFile)));
						}
					} catch (\Throwable $th) {
						$reportFile = array();
					}
					
					$existingSchedules[$option->option_name] = array('schedule_details'=>get_option($option->option_name), 'report_file'=>$reportFile);
					
					$reportFile = array();
				}
			}

			return $existingSchedules;
		}
		
	}
}
