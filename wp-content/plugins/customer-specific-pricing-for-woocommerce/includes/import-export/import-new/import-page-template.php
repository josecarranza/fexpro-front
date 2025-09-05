<?php
$filePickerPlaceholder = __('Choose Rule CSV file', 'customer-specific-pricing-for-woocommerce');
$importButtonText	   = __('Start Import', 'customer-specific-pricing-for-woocommerce');
?>
<div class='import-page-wrapper container'>
	<!-- Progress Bar -->
	<div class="row import-progress m-4" style="display:none;">
		<div class="col-12">
			<div class="container">
				<div class="wdmcsp-progress-status-container">
					<div class="space-top ">
					</div>

					<div class="import-status">
						<div class="row">
							<div class="col-md-3">
							</div>
							<div class="col-md-6 text-center">
								<h6>
									<div class="text-center" style="display:none">
										<span id="file-number" current_val="0">1</span> <span class="seperator"> Of </span>
										<span id="total-files-in-a-queue"><?php echo esc_html(1); ?></span>
									</div>
								</h6>
								<h6>
									<div class="text-center">
										<label>
											<span id='current-import-file-type'></span>
										</label>
									</div>
								</h6>
							</div>
							<div class="col-md-3">
							</div>
						</div>

						<div class="row">
							<div class="col-md-3">
							</div>
							<div class="col-md-6 text-center">
							<h4><label><?php esc_html_e('Processed :', 'customer-specific-pricing-for-woocommerce'); ?></label>
								<span id="total-processed" current_val="0"><?php echo esc_html('--'); ?></span>
								<span class="seperator">/</span>
								<span id="total-records" current_val="0"><?php echo esc_html('--'); ?></span>
							</h4>
							</div>
							<div class="col-md-3">
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="progress">
									<div class="progress-bar progress-bar-striped active progress-bar-animated" 
									role="progressbar" aria-valuenow="0" aria-valuemin="0"
									aria-valuemax="100" id="import-progress-bar">
									<span id="percent-progress">0%</span>
									</div>
								</div>
							</div>
						</div>

						<div class="row text-center status-details">
							<div class="col-md-4">
								<span><?php esc_html_e('Inserted:', 'customer-specific-pricing-for-woocommerce'); ?> </span>
								<span id="csp-insert-count">0</span>
							</div>
							<div class="col-md-4">
								<span><?php esc_html_e('Updated:', 'customer-specific-pricing-for-woocommerce'); ?> </span>
								<span id="csp-update-count">0</span>
							</div>
							<div class="col-md-4">
								<span><?php esc_html_e('Skipped:', 'customer-specific-pricing-for-woocommerce'); ?> </span>
								<span id="csp-skip-count">0</span>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12 text-center">
								<button type="button" class="btn btn-primary csp-download-report" disabled>
									<?php esc_html_e('Download Report', 'customer-specific-pricing-for-woocommerce'); ?>
								</button>
							</div>
						</div>
					</div>

					<div class="space-bottom">
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Loading Icon -->
	<div class="row standby loading-section text-center" style="display:none;">
		<div class="d-flex justify-content-center">
			  <div class="spinner-border" role="status">
				<span class="sr-only"><?php esc_html_e('Loading...', 'customer-specific-pricing-for-woocommerce'); ?></span>
			  </div>
		</div>
	</div>

	<!-- File Upload Form -->
	<div class='row row-mid row-file-upload align-items-center mt-4'>
		<div class="col-3">
		</div>
		<div class="col-6">
			<div class="justify-content-center"> 
				<div class="container pt-2 pb-2 border border-primary rounded import-box-container">
					<div class="row m-2">
						<div class="col-10">
						</div>
						<div class="col-2 text-right">
							<span class="dashicons dashicons-info-outline" data-toggle="modal" data-target="#import-info-modal"></span>
						</div>
						<!-- <div class="col-1">
							<span class="dashicons dashicons-admin-generic"></span>
						</div> -->
					</div>
					<div class="row m-4 import-filepicker">
						<div class="col-1">
						</div>
						<div class="col-10">
							<form>
							  <div class="custom-file">
								<input type="file" accept=".csv" class="custom-file-input" id="csp-csv-picker">
								<label class="custom-file-label" for="csp-csv-picker"><?php echo esc_html($filePickerPlaceholder); ?></label>
							  </div>
							</form>
						</div>
						<div class="col-1">
						</div>
					</div>
					<div class="row m-4 submit-request">
						<div class="col-2">
						</div>
						<div class="col-8">
							<button type="button" title="<?php esc_attr_e('Select a file & import', 'customer-specific-pricing-for-woocommerce'); ?>" class="btn btn-primary btn-block" id="csp-start-import"><?php echo esc_html($importButtonText); ?></button>
						</div>
						<div class="col-2">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-3">
		</div>
	</div>

	<!-- Bootstrap toast notification body to show import related notifications -->
	<div class="position-fixed top-0 right-0 p-3 csp-import-live-toast-wrapper">
	  <div id="csp-import-live-toast" class="import-toast toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2500">
		<div class="toast-header csp-import-toast-header">
		  <img src="<?php echo esc_url(CSP_PLUGIN_SITE_URL . '/images/wisdmlabs_logo.png'); ?>" class="rounded mr-2 csp-import-toast-header-image" alt="Import notifications">
		  <strong class="mr-auto"><?php esc_html_e('CSP Import Update', 'customer-specific-pricing-for-woocommerce'); ?></strong>
		  <small><?php esc_html_e('Update', 'customer-specific-pricing-for-woocommerce'); ?></small>
		  <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="toast-body csp-import-toast-body"><?php esc_html_e('CSP import related updates will be displayed here', 'customer-specific-pricing-for-woocommerce'); ?></div>
	  </div>
	</div>

</div>

<?php
	$sampleFilesDirUrl = CSP_PLUGIN_SITE_URL . '/includes/import-export/import-new/csv_examples/'
?>
<!-- Information Modal -->
<div class="modal fade" id="import-info-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h4 class="modal-title" id="import-instructions-modal-title"><?php esc_html_e('Instructions', 'customer-specific-pricing-for-woocommerce'); ?>
		<a target="_blank" href="https://wisdmlabs.com/docs/article/wisdm-customer-specific-pricing/csp-getting-started/csp-user-guide/new-import-feature-in-csp/">
		<span class="dashicons dashicons-external"></span></a></h4>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	  <div class="modal-body">
		  <ul>
			<li><?php esc_html_e('The import file should be formatted as a comma seperated CSV file according to the formats specified in the headers below.', 'customer-specific-pricing-for-woocommerce'); ?></li>
			<li><?php esc_html_e('You can simply download the sample files & import the same file by adding data to the rows(CSP rules).', 'customer-specific-pricing-for-woocommerce'); ?></li>
			<li><?php esc_html_e('Please make sure to not to include the same column multiple times the import functionality will automatically select the file type based on the headers.', 'customer-specific-pricing-for-woocommerce'); ?></li>
			<li><?php esc_html_e('You can also enable the older version of import feature from CSP settings', 'customer-specific-pricing-for-woocommerce'); ?></li>
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

