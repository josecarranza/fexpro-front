<?php 
namespace cspImportExport\Import;

if (!class_exists('WisdmCSPScheduledImports')) {
	/**
	 * This class contains the common methods used by CSP import feature.
	 */
	class WisdmCSPScheduledImports {

		/**
		 * Schedules the job with action scheduler & returns the job Id
		 *
		 * @param string $jobName
		 * @param array $scheduleData
		 * @return bool|int
		 */
		public function scheduleRuleImportJob( $jobName, $scheduleData) {
			//logic for import once, daily, weekly schedules.
			switch ($scheduleData['scheduleType']) {
				case 'once':
					return as_schedule_single_action($scheduleData['timestampUTC'], 'wdm_csp_process_rule_import', array($jobName), 'csp_scheduled_import');
					break;
				case 'daily':
					return as_schedule_recurring_action( $scheduleData['nextScheduleTime'], 86400, 'wdm_csp_process_rule_import', array($jobName), 'csp_scheduled_import');
					break;
				case 'weekly':
					return as_schedule_recurring_action( $scheduleData['nextScheduleTime'], 604800, 'wdm_csp_process_rule_import', array($jobName), 'csp_scheduled_import');
					break;
				default:
					# code...
					break;
			}
			return false;
		}

		/**
		 * Saved the schedule data to the WP option 
		 *
		 * @param string $jobName name of the option for the storage.
		 * @param array $scheduleData deatils about the schedule.
		 * @param array $fileData deatils about the file scheduled.
		 * @return bool
		 */
		public function saveUpdateImportScheduleData( $jobName, $scheduleData, $fileData ) {
			$scheduleDetails = array('schedule' => $scheduleData, 'file'=>$fileData);
			return update_option( $jobName, $scheduleDetails);
		}

		/**
		 * This function validates the data received to create the schedule call the
		 * functions to schedule & to store the jobs.
		 *
		 * @param array $scheduleDetails
		 * @return array array with the status of the import schedule operation & reason for failure as message
		 */
		public function scheduleImport( $scheduleDetails ) {
			if (empty($scheduleDetails)) {
				return array('status'=>'failed', 'message' => __('Invalid schedule details.', 'customer-specific-pricing-for-woocommerce'));
			}

			$scheduleType 	= !empty($scheduleDetails['frequency'])?$scheduleDetails['frequency']:'';
			$scheduleTitle 	= !empty($scheduleDetails['name'])?$scheduleDetails['name']:'';
			$date 		  	= !empty($scheduleDetails['date'])?$scheduleDetails['date']:'';
			$time 		  	= !empty($scheduleDetails['time'])?$scheduleDetails['time']:'';
			$weekDay		= !empty($scheduleDetails['week_day'])?$scheduleDetails['week_day']:'';
			$fileConnection = !empty($scheduleDetails['file_connection_type'])?$scheduleDetails['file_connection_type']:'';
			$fileUrl		= !empty($scheduleDetails['file_url'])?$scheduleDetails['file_url']:'';
			$ftpUrl			= !empty($scheduleDetails['ftp_url'])?$scheduleDetails['ftp_url']:'';
			$ftpPort	    = !empty($scheduleDetails['ftp_port'])?$scheduleDetails['ftp_port']:'';
			$ftpUserName	= !empty($scheduleDetails['ftp_username'])?$scheduleDetails['ftp_username']:'';
			$ftpPassword	= !empty($scheduleDetails['ftp_password'])?$scheduleDetails['ftp_password']:'';
			$ftpFilePath	= !empty($scheduleDetails['ftp_file_path'])?$scheduleDetails['ftp_file_path']:'';

			$scheduleData = $this->getValidScheduleData( $scheduleType, $date, $time, $weekDay );
			if ('failed'==$scheduleData['status']) {
				return $scheduleData;
			}
			$scheduleData		   = $scheduleData['data'];
			$scheduleData['title'] = $scheduleTitle;
			$fileData              = $this->getValidFileData($fileConnection, $fileUrl, $ftpUrl, $ftpPort, $ftpUserName, $ftpPassword, $ftpFilePath);
			if ('failed'==$fileData['status']) {
				return $fileData;
			}
			$fileData = $fileData['data'];
			if ('ftp'==$fileData['file_connect'] || 'sftp'==$fileData['file_connect']) {
				if (!$this->checkFTPConnections($fileData)) {
					return array('status'=>'failed', 'message'=>__('Unable to connect using the FTP/SFTP credentials provided', 'customer-specific-pricing-for-woocommerce'));
				}
			}
			
			//File & schedule details are correct save the schedule & file details to the option csp_import_schedule_<user_id>_<utc timestamp>
			$jobName	  = 'csp_import_schedule_' . get_current_user_id() . '_' . date_i18n( 'U', false, true );
			$jobScheduled = $this->scheduleRuleImportJob($jobName, $scheduleData);
			if (!$jobScheduled) {
				return array('status'=>'failed', 'message' => __('Failed to schedule import action.', 'customer-specific-pricing-for-woocommerce'));
			}

			$status = $this->saveUpdateImportScheduleData($jobName, $scheduleData, $fileData);
			$status = $status?'success':'failed';
			return array('status'=>$status, 'message' => __('Succesfully added & scheduled the import', 'customer-specific-pricing-for-woocommerce'), 'data' => array('job_id'=>$jobName, 'schedule_details'=>$scheduleData, 'fileDetails'=>$fileData));
		}

		public function getValidScheduleData( $scheduleType, $date, $time, $weekDay ) {
			global $cspFunctions;
			switch ($scheduleType) {
				case 'once':
					if (empty($date) || empty($time)) {
						return array('status'=>'failed', 'message'=>__('Date and time is required to set import schedule for once.', 'customer-specific-pricing-for-woocommerce'));
					}
					$dateTime		   = $date . ' ' . $time;
					$scheduleTimeStamp = strtotime($dateTime);
					$scheduleTimeStamp = $cspFunctions->convertToUTC($scheduleTimeStamp);
					$currentTime	   = date_i18n( 'U', false, true );
					if ($scheduleTimeStamp<$currentTime) {
						return array('status'=>'failed', 'message'=>__('Cannot schedule the import for the past time/date', 'customer-specific-pricing-for-woocommerce'));
					}
					return array('status'=>'success', 'data'=>array('scheduleType'=>$scheduleType, 'date'=>$date, 'time'=>$time, 'timestampUTC'=>$scheduleTimeStamp));
					break;
				
				case 'daily':
					if (empty($time)) {
						return array('status'=>'failed', 'message'=>__('Please select the time in HH:MM(24Hr) format', 'customer-specific-pricing-for-woocommerce'));
					}
					$currentTime    	   = date_i18n('U');
					$timeToday 	    	   = strtotime(date_i18n('Y-m-d'));
					$timeData 		       = explode(':', $time);
					$hour 		    	   = (int) $timeData[0] * 60 * 60;
					$minute 	    	   = (int) $timeData[1] * 60;
					$timeInSeconds   	   = $hour + $minute;
					$scheduledTimeOfTheDay = $timeToday + $timeInSeconds;
					$nextScheduleTime 	   = $scheduledTimeOfTheDay;
					if ($scheduledTimeOfTheDay<$currentTime) {
						$nextScheduleTime = $scheduledTimeOfTheDay + 86400; //+1 day
					}

					$nextScheduleTime = $cspFunctions->convertToUTC($nextScheduleTime);
					return array('status' => 'success', 'data'=>array('scheduleType'=>$scheduleType, 'time'=>$time, 'nextScheduleTime'=>$nextScheduleTime));
					break;
				
				case 'weekly':
					if (empty($time) || empty($weekDay)) {
						return array('status'=>'failed', 'message'=>__('Please select the correct weekday & time in HH:MM(24Hr) format', 'customer-specific-pricing-for-woocommerce'));
					}
					$weekDayToday  = current_time('w');
					$currentTime   = date_i18n('U');
					$timeToday 	   = strtotime(date_i18n('Y-m-d'));
					$timeData	   = explode(':', $time);
					$hour 		   = (int) $timeData[0] * 60 * 60;
					$minute 	   = (int) $timeData[1] * 60;
					$timeInSeconds = $hour + $minute;

					if ($weekDayToday==$weekDay) {
						$scheduledTimeOfTheDay = $timeToday + $timeInSeconds;
						$nextScheduleTime 	   = $scheduledTimeOfTheDay;
						if ($scheduledTimeOfTheDay<$currentTime) {
							$nextScheduleTime = $scheduledTimeOfTheDay + 604800; //+1 Week
						}
					} else {
						$scheduledTimeOfTheDay = $timeToday + $timeInSeconds;
						$nextScheduleTime 	   = $scheduledTimeOfTheDay + ( ( ( 7 - $weekDayToday + $weekDay ) % 7 ) * 86400 );
					}

					$nextScheduleTime = $cspFunctions->convertToUTC($nextScheduleTime);
					return array('status' => 'success', 'data'=>array('scheduleType'=>$scheduleType, 'weekDay'=>$weekDay, 'time'=>$time, 'nextScheduleTime'=>$nextScheduleTime));
					break;
				default:
					# code...
					break;
			}
			return array('status'=>'failed', 'message'=>__('Invalid schedule type', 'customer-specific-pricing-for-woocommerce'));
		}

		public function getValidFileData( $fileConnection, $fileUrl = '', $ftpUrl = '', $ftpPort = '', $ftpUserName = '', $ftpPassword = '', $ftpFilePath = '' ) {
			switch ($fileConnection) {
				case 'file-url':
					if (empty($fileUrl)) {
						return array('status'=>'failed', 'message'=>__('The file URL is invalid', 'customer-specific-pricing-for-woocommerce'));
					}
					$remoteFile = @fopen($fileUrl, 'r');									
					if (!$remoteFile) {
						return array('status'=>'failed', 'message'=>__('The file is not found on the url shared.', 'customer-specific-pricing-for-woocommerce'));
					} else {
						return array('status'=>'success', 'data'=>array('file_connect'=> $fileConnection, 'fileUrl'=>$fileUrl));
					}
					break;
				case 'ftp':
					$ftpPort = !empty($ftpPort)?$ftpPort:21;
					if (empty($ftpUrl) || empty($ftpUserName) || empty($ftpFilePath)) {
						return array('status'=>'failed', 'message'=>__('Fields FTP URL, Username, File Path are required.', 'customer-specific-pricing-for-woocommerce'));
					}
					return array('status'=>'success', 'data'=>array('file_connect'=> $fileConnection, 'ftpUrl'=>$ftpUrl, 'ftpPort'=>$ftpPort, 'ftpUserName'=>$ftpUserName, 'ftpPassword'=>$ftpPassword, 'ftpFilePath'=>$ftpFilePath));
					break;				
				case 'sftp':
					$ftpPort = ( !empty($ftpPort) && 21!=$ftpPort )?$ftpPort:22;
					if (empty($ftpUrl) || empty($ftpUserName) || empty($ftpFilePath)) {
						return array('status'=>'failed', 'message'=>__('Fields FTP URL, Username, File Path are required.', 'customer-specific-pricing-for-woocommerce'));
					}
					return array('status'=>'success', 'data'=>array('file_connect'=> $fileConnection, 'ftpUrl'=>$ftpUrl, 'ftpPort'=>$ftpPort, 'ftpUserName'=>$ftpUserName, 'ftpPassword'=>$ftpPassword, 'ftpFilePath'=>$ftpFilePath));
					break;
				
				default:
					break;
			}
			return array('status'=>'failed', 'message'=>__('Please select correct file type', 'customer-specific-pricing-for-woocommerce'));
		}

		public function deleteScheduledImport( $scheduleId ) {
			if (!empty($scheduleId)) {
				$schedule = get_option($scheduleId, false);
				as_unschedule_action('wdm_csp_process_rule_import', array($scheduleId), 'csp_scheduled_import');	
				delete_option($scheduleId);
				return array('status'=>'success','message' => __('Deleted the selected scheduled import action', 'customer-specific-pricing-for-woocommerce'), 'schedule_id'=>$scheduleId);
			}
			return array('status'=>'failed', 'message' => __('Failed to delete the scheduled action or action does not exists', 'customer-specific-pricing-for-woocommerce'), 'schedule_id'=>$scheduleId);
		}

		public function importFileForTheSchedule( $scheduleDataOption ) {
			$schedule = get_option($scheduleDataOption);
			if (empty($schedule)) {
				return array('status'=>'error', 'message'=>__('Details not found for the scheduled import.', 'customer-specific-pricing-for-woocommerce'));
			}
			
			$fileStatus = $this->getRemoteFileToLocal($scheduleDataOption, $schedule['file']);
			if ('error'==$fileStatus['status']) {
				return $fileStatus;
			}

			$filePath = $fileStatus['filePath'];
			$fileDir  = $fileStatus['fileDir'];
			$fileName = $fileStatus['fileName'];
			require_once CSP_PLUGIN_PATH . '/includes/import-export/import-new/class-wdm-import.php';
			$importManager = new \cspImportExport\Import\WisdmCSPImport();
			$fileDetails   = $importManager->validateImportFile($fileName, $filePath);
			if (!$fileDetails['success']) {
				return array('status'=>'error', 'message'=>__('The file specified in invalid', 'customer-specific-pricing-for-woocommerce'));
			}
			unset($importManager);
			
			$fileDetails['data']['file_dir'] = $fileDir;
			$status                          = $this->processImport($fileDetails['data']);
			
			return $status;
		}

		/**
		 * Fetches the remote file specified in the schedule by url/FTP/SFTP , stores it on the server in the
		 * folder name similar to the schedule data option name & returns the file path.
		 *
		 * @param string $scheduleName name of the option in which the schedule is saved.
		 * @param array $fileData details for fetching the remotely stored file.
		 * @return string 
		 */
		public function getRemoteFileToLocal( $scheduleName, $fileData ) {
			$connection = $fileData['file_connect'];
			$uploadsDir = wp_upload_dir();
			/**
			 * Filter which can be used to change the default temporary storage directory name of the import file before import
			 * 
			 * @param string $name directory name.
			 */
			$rawImportFileDir = apply_filters('wdm_csp_filter_new_import_raw_upload_path', 'csp-import-files');
			$rawImportFileDir = $uploadsDir['basedir'] . '/' . $rawImportFileDir . '/' . $scheduleName;
			if (!\is_dir($rawImportFileDir)) {
				\mkdir($rawImportFileDir, 755, true);
			}
			$importFilePath   = $rawImportFileDir . '/' . $scheduleName . '.csv';
			if (!is_dir($rawImportFileDir) && ! \mkdir($rawImportFileDir)) {
				return array('status'=>'error', 'message'=>__('Unable to create the directory to store the file fetched', 'customer-specific-pricing-for-woocommerce'));
			}
			$status 	   = false; 
			switch ($connection) {
				case 'file-url':
					$url  = $fileData['fileUrl'];
					$data = wp_remote_get($url);
					if (empty($data)) {
						return array('status'=>'error', 'message'=>__('Unable to download the file specified by the link', 'customer-specific-pricing-for-woocommerce'));
					}
					$data = $data['body'];
					file_put_contents($importFilePath, $data);
					break;
				case 'ftp':
					$host		   = $fileData['ftpUrl'];
					$port          = empty($fileData['ftpPort'])?21:$fileData['ftpPort'];
					$host 		   = str_replace('ftp://', '', $host);
					$host 		   = rtrim($host, '/');
					$userName	   = $fileData['ftpUserName'];
					$password	   = $fileData['ftpPassword'];
					$filePath      = $fileData['ftpFilePath'];
					$ftpConnection = false;
					$connectStatus = false;
					try {
						$ftpConnection = ftp_connect( $host, $port, 45);
						if (!empty($ftpConnection) && ftp_login($ftpConnection, $userName, $password)) {
							$connectStatus = true;
						} else {
							$ftpConnection = ftp_ssl_connect($host, $port, 45);
							if (!empty($ftpConnection) && ftp_login($ftpConnection, $userName, $password)) {
								$connectStatus = true;
							}		
						}
					} catch (\Throwable $th) {
						$connectStatus = false;
					}
					if ($connectStatus) {
						ftp_pasv($ftpConnection, true);
						$status = ftp_get($ftpConnection, $importFilePath, $filePath, FTP_BINARY);
					}
					ftp_close($ftpConnection);

					if (!$status) {
						return array('status'=>'error', 'message'=>__('Unable to get the file from FTP', 'customer-specific-pricing-for-woocommerce'));
					}
					
					break;
				case 'sftp':
					if (!function_exists('ssh2_connect')) {
						return array('status'=>'error', 'message'=>__('Unable to use SFTP connection, libssh2 might be missing please contact your host.', 'customer-specific-pricing-for-woocommerce'));
					}
					$host	  = $fileData['ftpUrl'];
					$port     = empty($fileData['ftpPort'])?22:(int) $fileData['ftpPort'];
					$host 	  = str_replace('sftp://', '', $host);
					$host 	  = str_replace('ftp://', '', $host);
					$host 	  = rtrim($host, '/');
					$userName = $fileData['ftpUserName'];
					$password = $fileData['ftpPassword'];
					$filePath = $fileData['ftpFilePath'];
					
					$connect = false;
					$sftp 	 = ssh2_connect($host, $port);
						
					if (!empty($sftp) && ssh2_auth_password($sftp, $userName, $password)) {
						$status = ssh2_scp_recv($sftp, $filePath, $importFilePath);
					}
					ssh2_disconnect($sftp);

					if (!$status) {
						return array('status'=>'error', 'message'=>__('Unable to get the file from FTP', 'customer-specific-pricing-for-woocommerce'));
					}
					
					break;
				default:
					return array('status'=>'error', 'message'=>__('Invalid connection type', 'customer-specific-pricing-for-woocommerce'));
					break;
			}

			$fileExtension = pathinfo($importFilePath, PATHINFO_EXTENSION);
			$fileExtension = strtolower($fileExtension);
			if ('csv'!=$fileExtension) {
				return array('status'=>'error', 'message'=>__('Unable to create the directory to store the file fetched', 'customer-specific-pricing-for-woocommerce'));
			}

			return array('status'=>'success', 'filePath'=>$importFilePath, 'fileDir'=>$rawImportFileDir, 'fileName'=> $scheduleName . '.csv');
		}


		/**
		 * Initiates the import process for the downloaded file, on completion returns 
		 * the count for updated, added & skipped CSP pricing rules.
		 *
		 * @param array $fileDetails
		 * @return array
		 */
		public function processImport( $fileDetails ) {
			global $cspFunctions;

			$ruleType	   = $fileDetails['type'];
			$headerMapping = $fileDetails['valid_headers_file_columns_mapping'];
			$filePath 	   = $fileDetails['file_path'];
			$fileName	   = $fileDetails['file_name'];
			$fileDir	   = $fileDetails['file_dir'];
			
			$classFile = CSP_PLUGIN_PATH . '/includes/import-export/import-new/file-type-imports/class-import-' . $ruleType . '.php';
			include_once CSP_PLUGIN_PATH . '/includes/import-export/import-new/file-type-imports/abstract-class-import-file.php'; 
			include_once $classFile;
			$class = '\cspImportExport\Import\FileTypeImport\\' . $cspFunctions::getCamelCaseOfDashCase($ruleType) . 'Import';

			$importObject = new $class();
			$importStatus = $importObject->ImportFile($fileName, $ruleType, $headerMapping, $fileDir);

			$response = array(
				'status' => 'success',
				'import_status' => $importStatus,
				'report_file' => 'report_' . $fileName,
				'report_dir' => $fileDir,
			);
			return $response;
		}


		/**
		 * Checks FTP & SFTP connections and returns the connection status.
		 *
		 * @param [type] $fileDetails
		 * @return void
		 */
		public function checkFTPConnections( $fileDetails ) {
			$connectionType = isset($fileDetails['file_connect'])?$fileDetails['file_connect']:'ftp';
			$defaultPort    = 'sftp'==$connectionType?22:21;
			$ftpUrl			= isset($fileDetails['ftpUrl'])?$fileDetails['ftpUrl']:'';
			$ftpPort		= isset($fileDetails['ftpPort'])?$fileDetails['ftpPort']:$defaultPort;
			$userName		= isset($fileDetails['ftpUserName'])?$fileDetails['ftpUserName']:'';
			$password		= isset($fileDetails['ftpPassword'])?$fileDetails['ftpPassword']:'';
			$filePath		= isset($fileDetails['ftpFilePath'])?$fileDetails['ftpFilePath']:'';
			$connection 	= false;
			
			if ('ftp'==$connectionType) {
				$connection = $this->ftpConnectionCheck($ftpUrl, $ftpPort, $userName, $password);
			} elseif ('sftp'==$connectionType) {
				$connection = $this->sftpConnectionCheck($ftpUrl, $ftpPort, $userName, $password);
			}

			return $connection;
		}


		/**
		 * Checks if the configuration provided for the FTP is 
		 * working as expected.
		 *
		 * @param string $host
		 * @param int $port
		 * @param string $userName
		 * @param string $password
		 */
		public function ftpConnectionCheck( $host, $port, $userName, $password ) {
			$port          = empty($port)?21:$port;
			$host 		   = str_replace('ftp://', '', $host);
			$host 		   = rtrim($host, '/');
			$ftpConnection = false;
			$connect 	   = false;
			try {
				$ftpConnection = ftp_connect( $host, $port, 45);
				if (!empty($ftpConnection) && ftp_login($ftpConnection, $userName, $password)) {
					$connect =  true;
				} else {
					$ftpConnection = ftp_ssl_connect($host, $port, 45);
					if (!empty($ftpConnection) && ftp_login($ftpConnection, $userName, $password)) {
						$connect =  true;
					}
				}
				ftp_close($ftpConnection);
			} catch (\Throwable $th) {
				$connect = false;
			} 
			
			return $connect;
		}


		/**
		 * Uses phpseclib to check if the configuration provided for the SFTP is 
		 * working as expected.
		 *
		 * @param string $host
		 * @param int $port
		 * @param string $userName
		 * @param string $password
		 */
		public function sftpConnectionCheck( $host, $port, $userName, $password ) {
			if (!function_exists('ssh2_connect')) {
				return false;
			}
			$host 	 = str_replace('sftp://', '', $host);
			$host 	 = str_replace('ftp://', '', $host);
			$host 	 = rtrim($host, '/');
			$connect = false;
			$port	 = empty($port)?22:(int) $port;
			$sftp 	 = ssh2_connect($host, $port);

			if (!empty($sftp) && ssh2_auth_password($sftp, $userName, $password)) {
				$connect = true;
			}
			ssh2_disconnect($sftp);
			return $connect;
		}
	}
}

