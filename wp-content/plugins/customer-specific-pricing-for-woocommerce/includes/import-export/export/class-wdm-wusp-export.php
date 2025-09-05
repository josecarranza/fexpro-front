<?php

namespace cspImportExport\cspExport;

/**
 * Display the export option for customer specific,role specific and group specific pricing
 */

if (!class_exists('WdmWuspExport')) {
	/**
	* Class to display export options.
	*/
	class WdmWuspExport {
	

		private $_class_value_pairs = array();

		/**
		 * Call the function for display export option and create csv file
		 */
		public function __construct() {
			add_action('show_export', array($this, 'wdmShowExportOptions'));
			add_filter('csp_product_sku', array($this, 'returnVariationProductEmptySKU'), 20, 2);
		}

		/**
		 * Store class value pairs for display in dropdown
		 *
		 * @param array $class_value_pairs
		 */
		public function setOptionValuesPair( $class_value_pairs) {
			$this->_class_value_pairs = $class_value_pairs;
		}

		/**
		 * Display export form
		 * Prepares data for js.
		 * Creates nonce for export, error messages.
		 */
		public function wdmShowExportOptions() {
			$array_to_be_send = array(
					'ajaxurl'                           	=>  admin_url('admin-ajax.php'),
					'export_nonce'                      	=> wp_create_nonce('export_nonce'),
					'please_Assign_valid_user_file_msg' 	=> __('Please Assign User Specific Prices to export the CSV file successfully.', 'customer-specific-pricing-for-woocommerce'),
					'please_Assign_valid_role_file_msg' 	=> __('Please Assign Role Specific Prices to export the CSV file successfully.', 'customer-specific-pricing-for-woocommerce'),
					'please_Assign_valid_group_file_msg'	=> __('Please Assign Group Specific Prices to export the CSV file successfully.', 'customer-specific-pricing-for-woocommerce'),
					'preparing_csv_message'					=> __('Please Wait', 'customer-specific-pricing-for-woocommerce'),
					'timeout_message'						=> __('The export request you made is timed out, please try again', 'customer-specific-pricing-for-woocommerce'),
					'export_button_text'					=> __('Export', 'customer-specific-pricing-for-woocommerce'),
					'get_product_list_button_text'			=> __('Get Product List', 'customer-specific-pricing-for-woocommerce'),
					'failed_getting_product_list'			=> __('Error generating the product list', 'customer-specific-pricing-for-woocommerce'),
					'generating_backup_for_all_rules'		=> __('Please wait, Generation of the CSP rule backup is in progress.', 'customer-specific-pricing-for-woocommerce'),
					'backup_scheduled_message'				=> __('The backup is successfuly scheduled as per your request', 'customer-specific-pricing-for-woocommerce'),
				);
			wp_enqueue_style('wdm_csp_export_css', plugins_url('/css/export-css/wdm-csp-export.css', dirname(dirname(dirname(__FILE__)))), array(), CSP_VERSION);
			wp_enqueue_script('wdm_csp_export_js', plugins_url('/js/export-js/wdm-csp-export.js', dirname(dirname(dirname(__FILE__)))), array('jquery'), CSP_VERSION, true);
			wp_localize_script('wdm_csp_export_js', 'wdm_csp_export_ajax', $array_to_be_send);
			wp_enqueue_style('csp_time_picker_lib_style', plugins_url('/css/single-view/jquery.timepicker.min.css', dirname(dirname(dirname(__FILE__)))), array(), CSP_VERSION);
			wp_enqueue_script('csp_time_picker_lib_script', plugins_url('/js/single-view/jquery.timepicker.min.js', dirname(dirname(dirname(__FILE__)))), array('jquery' ), CSP_VERSION, true);
			wp_enqueue_script('csp_scheduled_exports', plugins_url('/js/single-view/rule-backups.js', dirname(dirname(dirname(__FILE__)))), array('jquery', 'csp_time_picker_lib_script' ), CSP_VERSION, true);
			$this->showExportPage();
		}


		/**
		 * Hook - returnVariationProductEmptySKU
		 * Returns the empty string. By default, if SKU is not set for variation product,
		 * then parent SKU is returned. This function returns empty string if
		 * variation product is not having any SKU set.
		 */
		public function returnVariationProductEmptySKU( $sku, $product) {
			// If product is variation product and parent SKU is same as SKU, it means that
			// the SKU is not set for the current variation product as SKU is unique can't 
			// be duplicated/repeated.
			if (!empty($product) && 'variation' == $product->get_type() && $sku == $product->get_parent_data()['sku']) {
				$sku = '';
			}

			return $sku;
		}

		/**
		 * Displays the content of export admin page
		 *
		 * @return void
		 */
		public function showExportPage() {
			global $pagenow;
			$currentTab = 'export';
			if ('admin.php'==$pagenow) {
				$currentTab = isset($_GET['page']) && isset($_GET['tab']) ? sanitize_text_field($_GET['tab']):'';
			} 
			?>
			<h3 class='csp-tab-header'> <?php esc_html_e('Backup/Export CSP Rules', 'customer-specific-pricing-for-woocommerce'); ?>
			</h3>
			<div class='csp-export-header-options'>
				<?php
				if ('scheduled-backups'===$currentTab) {
					$scheduleDetails = get_option('wdm_csp_backup_schedule', array());
					$frequency  	 = isset($scheduleDetails['frequency'])?$scheduleDetails['frequency']:'daily';
					$weekDay    	 = isset($scheduleDetails['week_day'])?$scheduleDetails['week_day']:'';
					$time       	 = isset($scheduleDetails['time'])?$scheduleDetails['time']:'';
					$maxBackups 	 = isset($scheduleDetails['maxBackups'])?$scheduleDetails['maxBackups']:'';
					
					$backedUpFileList = self::getCSPBackedUpFiles();
					$pageTemplate 	  = 'templates/admin/backups-page.php';
					?>
						<a href="?page=wisdm-csp-export&tab=export-page"><?php esc_html_e('Export Rules', 'customer-specific-pricing-for-woocommerce'); ?></a> 
						<span>|</span>
						<?php esc_html_e('Schedule Backups', 'customer-specific-pricing-for-woocommerce'); ?>
					<?php
				} else {
					$pageTemplate = 'templates/admin/export-page.php';
					?>
						<?php esc_html_e('Export Rules', 'customer-specific-pricing-for-woocommerce'); ?> 
						<span>|</span>
						<a href="?page=wisdm-csp-export&tab=scheduled-backups"><?php esc_html_e('Schedule Backups', 'customer-specific-pricing-for-woocommerce'); ?></a>
					<?php
				}
				?>
			</div>
			<hr>
			<?php
			include_once plugin_dir_path(CSP_PLUGIN_FILE) . $pageTemplate;
		}

		/**
		 * Generates File Path, Deletes the file if exist
		 * Write the data to the file & returns file url or path.
		 * 
		 * @since 4.6.3
		 * @param string $fileName name of the CSV file to store data.
		 * @param string $returnFile url|path default: path
		 * @return string path of the generated file
		 */
		public function getCSVFile( $fileName = '', $returnFile = 'path' ) {
			$fileName = ''===$fileName?$this->wdmFileName():$fileName;
			$data	  = $this->wdmFetchData();
			if (!empty($data)) {
				$upload_dir = wp_upload_dir();

				$deleteFile = glob($upload_dir['basedir'] . $fileName);
				if ($deleteFile) {
					foreach ($deleteFile as $file) {
						unlink($file);
					}
				}

				$output = fopen($upload_dir['basedir'] . $fileName, 'w');
				fputcsv($output, $data[0]);
				foreach ($data[1] as $row) {
					$array = (array) $row;
					fputcsv($output, $array);
				}
				fclose($output);
				$output = 'path'===$returnFile?$upload_dir['basedir'] . $fileName:esc_url($upload_dir['baseurl'] . $fileName);
				return $output;
			}
			return '';
		}


		/**
		 * This function fetches the backup files created in the past 
		 * & returns an array with the backup files and 
		 * 
		 * @since 4.6.3
		 * @return array
		 */
		public static function getCSPBackedUpFiles() {
			$fileList 	   = array();
			$backupDirPath = WP_CONTENT_DIR . '/csp_backups';
			$backupUrlBase = content_url();
			if (is_dir($backupDirPath)) {
				$files = scandir($backupDirPath);
				if (!empty($files)) {
					global $cspFunctions;
					foreach ($files as $fileName) {
						$filePath = $backupDirPath . '/' . $fileName;
						if (is_file($filePath)) {
							$fileDate = gmdate('F d Y, H:i', $cspFunctions->convertToLocalTime(filemtime($filePath)));
							$fileUrl  = $backupUrlBase . '/csp_backups/' . $fileName;	
							
							$fileList[] = array('fileName'=>$fileName, 'fileUrl'=>$fileUrl, 'fileDate'=>$fileDate);
						}
					}
				}
			}
			return $fileList;
		}
	}
}

/**
 * Include all files required for Export
 */
require_once 'process-export/class-wdm-wusp-group-specific-pricing-export.php';
require_once 'process-export/class-wdm-wusp-role-specific-pricing-export.php';
require_once 'process-export/class-wdm-wusp-user-specific-pricing-export.php';
