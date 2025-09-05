<div class="container">
	<div class="row">
		<div class="col-2"></div>
		<div class="col-8" id="csp-schedule-list-container">
			<div class="container container-existing-schedules">
				<div class="row">
					<div class="col-6">
						<h5><?php esc_html_e('Scheduled Imports', 'customer-specific-pricing-for-woocommerce'); ?></h5>
					</div>
					<div class="col-6 text-right">
						<a href="#schedule-form-container"><?php esc_html_e('Add New Schedule', 'customer-specific-pricing-for-woocommerce'); ?></a>
					</div>
				</div>
				<?php
				if (!empty($scheduledTasks)) {
					?>
					<div class="panel-group schedule-rule-panels">
					<?php
					$weekdays = array(
						0 => __('Sunday', 'customer-specific-pricing-for-woocommerce'),
						1 => __('Monday', 'customer-specific-pricing-for-woocommerce'),
						2 => __('Tuesday', 'customer-specific-pricing-for-woocommerce'),
						3 => __('Wednesday', 'customer-specific-pricing-for-woocommerce'),
						4 => __('Thursday', 'customer-specific-pricing-for-woocommerce'),
						5 => __('Friday', 'customer-specific-pricing-for-woocommerce'),
						6 => __('Saturday', 'customer-specific-pricing-for-woocommerce'),
					);

					foreach ($scheduledTasks as $taskId => $scheduleDetails) {
						$scheduledTask       = $scheduleDetails['schedule_details'];
						$reportFile			 = $scheduleDetails['report_file'];
						$scheduleType  		 = $scheduledTask['schedule']['scheduleType'];
						$scheduleTitle 		 = !empty($scheduledTask['schedule']['title'])?$scheduledTask['schedule']['title']:$taskId;
						$ScheduleDescription = ''; 
						switch ($scheduleType) {
							case 'daily':
								/* translators: v%1s:Time */
								$scheduleDescription = sprintf(esc_html__('Daily at %1$s', 'customer-specific-pricing-for-woocommerce'), $scheduledTask['schedule']['time']);
								break;
							case 'weekly':
								/* translators: v%1s:Date v%2s:Time */
								$scheduleDescription = sprintf(esc_html__('Weekly on every %1$s at %2$s', 'customer-specific-pricing-for-woocommerce'), $weekdays[$scheduledTask['schedule']['weekDay']], $scheduledTask['schedule']['time']);
								break;
							case 'once':
								/* translators: v%1s:Date v%2s:Time */
								$scheduleDescription = sprintf(esc_html__('Once on %1$s at %2$s', 'customer-specific-pricing-for-woocommerce'), $scheduledTask['schedule']['date'], $scheduledTask['schedule']['time']);
								break;
						}
						$fileConnection = $scheduledTask['file']['file_connect'];
						$fileDetails	= '';
						
						?>
						
							<div class="panel csp-schedule-panel" data-schedule-id="<?php esc_html_e($taskId); ?>">
							  <div class="panel-heading csp-schedule-panel-heading">
								<a data-toggle="collapse" href="#<?php esc_html_e($taskId); ?>" class="panel-collapse">
								<div class="row">
									<div class="col-5"> <?php esc_html_e($scheduleTitle); ?></div>
									<div class="col-5"> <?php esc_html_e($scheduleDescription); ?> </div>
									<div class="col-1 delete-schedule-icon text-center"><span class="dashicons schedule-import-dashicons"></span></div>
									<div class="col-1 expand-collapse-icon text-left"><span class="dashicons schedule-import-dashicons"></span></div>
								</div>
								</a>
							  </div>
							  <div id="<?php esc_html_e($taskId); ?>" class="panel-collapse collapse">
								<div class="panel-body csp-schedule-panel-body">
									<?php
									switch ($fileConnection) {
										case 'file-url':
											?>
											<div class="row">
												<div class="col-4"><?php esc_html_e('File Url', 'customer-specific-pricing-for-woocommerce'); ?></div>
												<div class="col-8">
													<a href="<?php esc_attr_e($scheduledTask['file']['fileUrl']); ?>" target="_blank" rel="noopener noreferrer"><?php esc_attr_e($scheduledTask['file']['fileUrl']); ?></a>
												</div>
											</div>
											<?php
											break;
										case 'ftp':
											?>
											<div class="row">
												<div class="col-3"><?php esc_html_e('FTP Host/Url', 'customer-specific-pricing-for-woocommerce'); ?></div>
												<div class="col-5">
													<a href="<?php esc_attr_e($scheduledTask['file']['ftpUrl']); ?>" target="_blank" rel="noopener noreferrer"><?php esc_attr_e($scheduledTask['file']['ftpUrl']); ?></a>
												</div>
												<div class="col-2"><?php esc_html_e('FTP Port', 'customer-specific-pricing-for-woocommerce'); ?></div>
												<div class="col-2"><?php esc_attr_e($scheduledTask['file']['ftpPort']); ?></div>
											</div>
											<div class="row">
												<div class="col-3"><?php esc_html_e('FTP Username', 'customer-specific-pricing-for-woocommerce'); ?></div>
												<div class="col-9">
													<?php esc_attr_e($scheduledTask['file']['ftpUserName']); ?>
												</div>
											</div>
											<div class="row">
												<div class="col-3"><?php esc_html_e('FTP Password', 'customer-specific-pricing-for-woocommerce'); ?></div>
												<div class="col-9">
													<?php esc_html_e('**********'); ?>
												</div>
											</div>
											<div class="row">
												<div class="col-3"><?php esc_html_e('File path', 'customer-specific-pricing-for-woocommerce'); ?></div>
												<div class="col-9">
													<?php esc_html_e($scheduledTask['file']['ftpFilePath']); ?>
												</div>
											</div>
											<?php
											break;
										case 'sftp':
											?>
											<div class="row">
												<div class="col-3"><?php esc_html_e('SFTP Host/Url', 'customer-specific-pricing-for-woocommerce'); ?></div>
												<div class="col-5">
													<a href="<?php esc_attr_e($scheduledTask['file']['ftpUrl']); ?>" target="_blank" rel="noopener noreferrer"><?php esc_attr_e($scheduledTask['file']['ftpUrl']); ?></a>
												</div>
												<div class="col-2"><?php esc_html_e('SFTP Port', 'customer-specific-pricing-for-woocommerce'); ?></div>
												<div class="col-2"><?php esc_attr_e($scheduledTask['file']['ftpPort']); ?></div>
											</div>
											<div class="row">
												<div class="col-3"><?php esc_html_e('SFTP Username', 'customer-specific-pricing-for-woocommerce'); ?></div>
												<div class="col-9">
													<?php esc_attr_e($scheduledTask['file']['ftpUserName']); ?>
												</div>
											</div>
											<div class="row">
												<div class="col-3"><?php esc_html_e('SFTP Password', 'customer-specific-pricing-for-woocommerce'); ?></div>
												<div class="col-9">
													<?php esc_html_e('**********'); ?>
												</div>
											</div>
											<div class="row">
												<div class="col-3"><?php esc_html_e('File path', 'customer-specific-pricing-for-woocommerce'); ?></div>
												<div class="col-9">
													<?php esc_html_e($scheduledTask['file']['ftpFilePath']); ?>
												</div>
											</div>
											<?php
											break;
										default:
											# code...
											break;
									}
									if (!empty($reportFile)) {
										?>
										<hr>
										<div class="row">
											<div class="col-8">
											<?php
											esc_html_e('Last Imported : ', 'customer-specific-pricing-for-woocommerce');
											esc_html_e($reportFile['time']);
											?>
											</div>
											<div class="col-4">
												<a href="<?php esc_attr_e($reportFile['url']); ?>" target="_blank" rel="noopener noreferrer"><?php esc_attr_e('Download Report', 'customer-specific-pricing-for-woocommerce'); ?></a>
											</div>
										</div>
										<?php
									}
									?>
								</div>
							  </div>
							</div>
							<?php
					}
					?>
					</div>
					<?php
				} else {
					?>
						<div class="row">
							<div class="col-12">
								<?php esc_html_e('No Scheduled Imports Found', 'customer-specific-pricing-for-woocommerce'); ?>
							</div>
						</div>
					<?php
				}
				?>
				
			</div>
		</div>
		<div class="col-2"></div>
	</div>

	<div class="row">
		<div class="col-2">
		</div>
		<div class="col-8 border border-secondary rounded" id="schedule-form-container">
			<div class="row header-row title-row">
				<div class="col-8">
				<h5><?php esc_html_e('Scheduled CSP Rule Imports', 'customer-specific-pricing-for-woocommerce'); ?></h5>
				</div>
				<div class="col-2"></div>
				<div class="col-2 text-right"><span class="dashicons dashicons-info-outline" data-toggle="modal" data-target="#import-info-modal"></span></div>
			</div>
			<div class="row import-schedule-name-row">
				<div class="col-4">
					<label for="import-schedule-name"><?php esc_html_e('Schedule Title', 'customer-specific-pricing-for-woocommerce'); ?></label>	
				</div>
				<div class="col-8">
					<input type="text" autocomplete="off" name="import-schedule-name" id="import-schedule-name" maxlength="150" title="<?php esc_attr_e('Add a recongnizable name to this schedule'); ?>" placeholder="<?php esc_html_e('Ex. Weekly changing prices for reseller role', 'customer-specific-pricing-for-woocommerce'); ?>">
				</div>
			</div>
			<div class="row frequency-row">
				<div class="col-4">
				<label for="auto-import-frequency"><?php esc_html_e('Frequency', 'customer-specific-pricing-for-woocommerce'); ?></label>
				</div>
				<div class="col-8">
				<select name="auto-import-frequency" id="auto-import-frequency">
					<option value="once">  <?php esc_html_e('Once', 'customer-specific-pricing-for-woocommerce'); ?></option>
					<option value="daily"> <?php esc_html_e('Every Day', 'customer-specific-pricing-for-woocommerce'); ?></option>
					<option value="weekly"><?php esc_html_e('Every Week', 'customer-specific-pricing-for-woocommerce'); ?></option>
				</select>
				</div>
			</div>
			<div class="row import-date-row">
				<div class="col-4">
				<label for="auto-import-date"><?php esc_html_e('Date', 'customer-specific-pricing-for-woocommerce'); ?></label>
				</div>
				<div class="col-8">
				<input type="date" name="auto-import-date" min="<?php echo esc_attr(date_i18n('Y-m-d')); ?>" id="auto-import-date">
				</div>
			</div>
			<div class="row import-weekday-row">
				<div class="col-4">
				<label for="auto-import-weekday"><?php esc_html_e('Week Day', 'customer-specific-pricing-for-woocommerce'); ?></label>
				</div>
				<div class="col-8">
					<select name="auto-import-weekday" id="auto-import-weekday">
						<option value="1"><?php esc_html_e('Monday', 'customer-specific-pricing-for-woocommerce'); ?></option>
						<option value="2"><?php esc_html_e('Tuesday', 'customer-specific-pricing-for-woocommerce'); ?></option>
						<option value="3"><?php esc_html_e('Wednesday', 'customer-specific-pricing-for-woocommerce'); ?></option>
						<option value="4"><?php esc_html_e('Thursday', 'customer-specific-pricing-for-woocommerce'); ?></option>
						<option value="5"><?php esc_html_e('Friday', 'customer-specific-pricing-for-woocommerce'); ?></option>
						<option value="6"><?php esc_html_e('Saturday', 'customer-specific-pricing-for-woocommerce'); ?></option>
						<option value="0"><?php esc_html_e('Sunday', 'customer-specific-pricing-for-woocommerce'); ?></option>
					</select>
				</div>
			</div>
			<div class="row import-time-row">
				<div class="col-4">
					<label for="import-time"><?php esc_html_e('At', 'customer-specific-pricing-for-woocommerce'); ?></label>	
				</div>
				<div class="col-8">
				<input type="time" pattern="([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]" autocomplete="off" name="import-time" maxlength="5" id="import-time" data-time-format="H:i" placeholder="23:59">
				</div>
			</div>
			<hr>
			<!-- File Selection -->
			<div class="row">
				<div class="col-4">
					<?php esc_html_e('Get File Using', 'customer-specific-pricing-for-woocommerce'); ?>
				</div>
				<div class="col-8 file-connection-type-selector">
					<input type="radio" id="file-url" name="connection_method" value="file-url">
					<label for="file-url" class="connection-radio-label"><?php esc_html_e('File URL', 'customer-specific-pricing-for-woocommerce'); ?></label>
					<input type="radio" id="ftp" name="connection_method" value="ftp">
					<label for="ftp" class="connection-radio-label"><?php esc_html_e('FTP', 'customer-specific-pricing-for-woocommerce'); ?></label>

					<?php
					if ('disabled'==$sftpDisabled) {
						?>
						<input type="radio" id="sftp" name="connection_method" class="sftp-disabled" value="sftp" disabled>
						<label for="sftp" title="<?php esc_html_e('SSH2 extension might be disabled on your server, please consult hosting provider.', 'customer-specific-pricing-for-woocommerce'); ?>" class="connection-radio-label sftp-disabled"><?php esc_html_e('SFTP', 'customer-specific-pricing-for-woocommerce'); ?></label><br>
						<?php
					} else {
						?>
						<input type="radio" id="sftp" name="connection_method" value="sftp">
						<label for="sftp" class="connection-radio-label"><?php esc_html_e('SFTP', 'customer-specific-pricing-for-woocommerce'); ?></label><br>
						<?php
					}
					?>
					
				</div>
			</div>
			<div class="row file-url-row" style="display:none;">
				<div class="col-4">
					<label for="import-file-url"><?php esc_html_e('File URL', 'customer-specific-pricing-for-woocommerce'); ?></label>	
				</div>
				<div class="col-8">
				<input type="text" autocomplete="off" name="import-file-url" id="import-file-url" data-time-format="H:i"  placeholder="<?php esc_html_e('URL of the file to be imported.', 'customer-specific-pricing-for-woocommerce'); ?>">
				</div>
			</div>

			<div class="row ftp-input-rows ftp-url-input-row" style="display:none;">
				<div class="col-4">
					<label for="ftp-url"><?php esc_html_e('URL', 'customer-specific-pricing-for-woocommerce'); ?></label>	
				</div>
				<div class="col-5">
					<input type="url" autocomplete="off" name="ftp-url" id="ftp-url" placeholder="shell.example.com">
				</div>
				<div class="col-1">
					<label for="ftp-port"><?php esc_html_e('Port', 'customer-specific-pricing-for-woocommerce'); ?></label>	
				</div>
				<div class="col-2">
					<input type="number" min=0 name="ftp-port" id="ftp-port" placeholder="21">
				</div>
			</div>
			
			<div class="row ftp-input-rows ftp-username-input-row" style="display:none;">
				<div class="col-4">
					<label for="import-file-username"><?php esc_html_e('User Name', 'customer-specific-pricing-for-woocommerce'); ?></label>	
				</div>
				<div class="col-8">
				<input type="text" autocomplete="off" name="import-file-username" id="import-file-username" placeholder="<?php esc_html_e('Username for the SFTP/FTP server', 'customer-specific-pricing-for-woocommerce'); ?>">
				</div>
			</div>
			<div class="row ftp-input-rows ftp-password-input-row" style="display:none;">
				<div class="col-4">
					<label for="import-file-password"><?php esc_html_e('Password', 'customer-specific-pricing-for-woocommerce'); ?></label>	
				</div>
				<div class="col-8">
				<input type="password" autocomplete="new-password" name="import-file-password" id="import-file-password" placeholder="<?php esc_html_e('Password of the SFTP/FTP Server', 'customer-specific-pricing-for-woocommerce'); ?>">
				</div>
			</div>
			<div class="row ftp-input-rows ftp-filepath-input-row" style="display:none;">
				<div class="col-4">
					<label for="import-file-path"><?php esc_html_e('File Path', 'customer-specific-pricing-for-woocommerce'); ?></label>	
				</div>
				<div class="col-8">
				<input type="text" autocomplete="off" name="import-file-path" id="import-file-path" placeholder="<?php esc_html_e('path/to/the/file/on/a/server.csv', 'customer-specific-pricing-for-woocommerce'); ?>">
				</div>
			</div>
			<!-- File Selection End -->

			<!-- Form Buttons -->
			<div class="row text-center row-buttons">
				<div class="col-2"><!-- Space --></div>
				<div class="col-10 text-right">
					<button id="csp-schedule-import" class="btn btn-primary"><?php esc_html_e('Schedule File For Import', 'customer-specific-pricing-for-woocommerce'); ?></button>
					<button id="csp-import-test-connection" class="btn btn-secondary" style="display:none;"><?php esc_html_e('Test Connection', 'customer-specific-pricing-for-woocommerce'); ?></button>
					<button id="csp-schedule-reset-form" class="btn btn-secondary" ><?php esc_html_e('Reset', 'customer-specific-pricing-for-woocommerce'); ?></button>
				</div>
			</div>
			<div class="row text-right" id="schedule-form-loader-row" style="display:none;">
				   <div class="clearfix col-12">
					<div class="spinner-border float-right" role="status">
						<span class="sr-only"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12 text-right">
					<a href="#"><?php esc_html_e('List Of Scheduled Imports', 'customer-specific-pricing-for-woocommerce'); ?></a>
				</div>
			</div>
		</div>
		<div class="col-2">
		</div>
	</div>

</div>


<!-- Information Modal -->
<?php
	$sampleFilesDirUrl = CSP_PLUGIN_SITE_URL . '/includes/import-export/import-new/csv_examples/'
?>
<div class="modal fade" id="import-info-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h4 class="modal-title" id="import-instructions-modal-title"><?php esc_html_e('Instructions', 'customer-specific-pricing-for-woocommerce'); ?>
		<a target="_blank" href="https://wisdmlabs.com/docs/article/wisdm-customer-specific-pricing/csp-getting-started/csp-user-guide/scheduled-imports/">
		<span class="dashicons dashicons-external"></span></a></h4>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	  <div class="modal-body">
		  <ul>
			<li><?php esc_html_e('The import file should be formatted as a comma seperated CSV file according to the format specified in the headers below.', 'customer-specific-pricing-for-woocommerce'); ?></li>
			<li><?php esc_html_e('The scheduled import functionality works with action scheduler, please try to use system crons in case of failure.', 'customer-specific-pricing-for-woocommerce'); ?></li>
			<li><?php esc_html_e('Please make sure to not to include the same column multiple times the import functionality will automatically select the file type based on the headers.', 'customer-specific-pricing-for-woocommerce'); ?></li>
			<li><?php esc_html_e('Try to keep healthy time duration & file size to avoid timeouts.', 'customer-specific-pricing-for-woocommerce'); ?></li>
			<li><?php esc_html_e('It is recomended to create dedicated FTP/SFTP user with readonly access for scheduled import operation on the remote servers.', 'customer-specific-pricing-for-woocommerce'); ?></li>
		  </ul>
		  <div class="file-types-and-samples">
			<dl>
				<dt><b><?php esc_html_e('Product Id Based User Specific Product Level Rules', 'customer-specific-pricing-for-woocommerce'); ?></b></dt>
				<dd><p><?php esc_html_e('File Headers: [user<user_name>,  product_id, min_quantity, %_discount, flat_price]', 'customer-specific-pricing-for-woocommerce'); ?>		
						<a target="_blank" href="<?php esc_attr_e($sampleFilesDirUrl . 'usp-product.csv'); ?>">
						<?php esc_html_e('Download Sample File', 'customer-specific-pricing-for-woocommerce'); ?>
						</a>	
					</p>
				</dd>
				<dt><b><?php esc_html_e('Product Id Based Role Specific Product Level Rules', 'customer-specific-pricing-for-woocommerce'); ?></b></dt>
				<dd><p><?php esc_html_e('File Headers: [role<role_slug>,  product_id, min_quantity, %_discount, flat_price]', 'customer-specific-pricing-for-woocommerce'); ?>		
						<a target="_blank" href="<?php esc_attr_e($sampleFilesDirUrl . 'rsp-product.csv'); ?>">
						<?php esc_html_e('Download Sample File', 'customer-specific-pricing-for-woocommerce'); ?>
						</a>	
					</p>
				</dd>
				<dt><b><?php esc_html_e('Product Id Based Group Specific Product Level Rules', 'customer-specific-pricing-for-woocommerce'); ?></b></dt>
				<dd><p><?php esc_html_e('File Headers: [group<group_name>,  product_id, min_quantity, %_discount, flat_price]', 'customer-specific-pricing-for-woocommerce'); ?>		
						<a target="_blank" href="<?php esc_attr_e($sampleFilesDirUrl . 'gsp-product.csv'); ?>">
						<?php esc_html_e('Download Sample File', 'customer-specific-pricing-for-woocommerce'); ?>
						</a>	
					</p>
				</dd>

				<dt><b><?php esc_html_e('Product SKU Based User Specific Product Level Rules', 'customer-specific-pricing-for-woocommerce'); ?></b></dt>
				<dd><p><?php esc_html_e('File Headers: [user<user_name>,  product_sku, min_quantity, %_discount, flat_price]', 'customer-specific-pricing-for-woocommerce'); ?>		
						<a target="_blank" href="<?php esc_attr_e($sampleFilesDirUrl . 'usp-product-sku.csv'); ?>">
						<?php esc_html_e('Download Sample File', 'customer-specific-pricing-for-woocommerce'); ?>
						</a>	
					</p>
				</dd>
				<dt><b><?php esc_html_e('Product SKU Based Role Specific Product Level Rules', 'customer-specific-pricing-for-woocommerce'); ?></b></dt>
				<dd><p><?php esc_html_e('File Headers: [role<role_slug>,  product_sku, min_quantity, %_discount, flat_price]', 'customer-specific-pricing-for-woocommerce'); ?>		
						<a target="_blank" href="<?php esc_attr_e($sampleFilesDirUrl . 'rsp-product-sku.csv'); ?>">
						<?php esc_html_e('Download Sample File', 'customer-specific-pricing-for-woocommerce'); ?>
						</a>	
					</p>
				</dd>
				<dt><b><?php esc_html_e('Product SKU Based Group Specific Product Level Rules', 'customer-specific-pricing-for-woocommerce'); ?></b></dt>
				<dd><p><?php esc_html_e('File Headers: [group<group_name>,  product_sku, min_quantity, %_discount, flat_price]', 'customer-specific-pricing-for-woocommerce'); ?>		
						<a target="_blank" href="<?php esc_attr_e($sampleFilesDirUrl . 'gsp-product-sku.csv'); ?>">
						<?php esc_html_e('Download Sample File', 'customer-specific-pricing-for-woocommerce'); ?>
						</a>	
					</p>
				</dd>

				<dt><b><?php esc_html_e('User Specific Category Level Rules', 'customer-specific-pricing-for-woocommerce'); ?></b></dt>
				<dd><p><?php esc_html_e('File Headers: [user<user_name>,  category_slug, min_quantity, %_discount, flat_price]', 'customer-specific-pricing-for-woocommerce'); ?>		
						<a target="_blank" href="<?php esc_attr_e($sampleFilesDirUrl . 'usp-category.csv'); ?>">
						<?php esc_html_e('Download Sample File', 'customer-specific-pricing-for-woocommerce'); ?>
						</a>	
					</p>
				</dd>
				<dt><b><?php esc_html_e('Role Specific Category Level Rules', 'customer-specific-pricing-for-woocommerce'); ?></b></dt>
				<dd><p><?php esc_html_e('File Headers: [role<role_slug>,  category_slug, min_quantity, %_discount, flat_price]', 'customer-specific-pricing-for-woocommerce'); ?>		
						<a target="_blank" href="<?php esc_attr_e($sampleFilesDirUrl . 'rsp-category.csv'); ?>">
						<?php esc_html_e('Download Sample File', 'customer-specific-pricing-for-woocommerce'); ?>
						</a>	
					</p>
				</dd>
				<dt><b><?php esc_html_e('Group Specific Category Level Rules', 'customer-specific-pricing-for-woocommerce'); ?></b></dt>
				<dd><p><?php esc_html_e('File Headers: [group<group_name>,  category_slug, min_quantity, %_discount, flat_price]', 'customer-specific-pricing-for-woocommerce'); ?>		
						<a target="_blank" href="<?php esc_attr_e($sampleFilesDirUrl . 'gsp-category.csv'); ?>">
						<?php esc_html_e('Download Sample File', 'customer-specific-pricing-for-woocommerce'); ?>
						</a>	
					</p>
				</dd>

				<dt><b><?php esc_html_e('User Specific Global(Sitewide) Rules', 'customer-specific-pricing-for-woocommerce'); ?></b></dt>
				<dd><p><?php esc_html_e('File Headers: [user<user_name>,  category_slug, min_quantity, %_discount, flat_price]', 'customer-specific-pricing-for-woocommerce'); ?>		
						<a target="_blank" href="<?php esc_attr_e($sampleFilesDirUrl . 'usp-global.csv'); ?>">
						<?php esc_html_e('Download Sample File', 'customer-specific-pricing-for-woocommerce'); ?>
						</a>	
					</p>
				</dd>
				<dt><b><?php esc_html_e('Role Specific Global(Sitewide) Rules', 'customer-specific-pricing-for-woocommerce'); ?></b></dt>
				<dd><p><?php esc_html_e('File Headers: [role<role_slug>,  category_slug, min_quantity, %_discount, flat_price]', 'customer-specific-pricing-for-woocommerce'); ?>		
						<a target="_blank" href="<?php esc_attr_e($sampleFilesDirUrl . 'rsp-global.csv'); ?>">
						<?php esc_html_e('Download Sample File', 'customer-specific-pricing-for-woocommerce'); ?>
						</a>	
					</p>
				</dd>
				<dt><b><?php esc_html_e('Group Specific Global(Sitewide) Rules', 'customer-specific-pricing-for-woocommerce'); ?></b></dt>
				<dd><p><?php esc_html_e('File Headers: [group<group_name>,  category_slug, min_quantity, %_discount, flat_price]', 'customer-specific-pricing-for-woocommerce'); ?>		
						<a target="_blank" href="<?php esc_attr_e($sampleFilesDirUrl . 'gsp-global.csv'); ?>">
						<?php esc_html_e('Download Sample File', 'customer-specific-pricing-for-woocommerce'); ?>
						</a>	
					</p>
				</dd>
			</dl>
		</div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	  </div>
	</div>
  </div>
</div>
