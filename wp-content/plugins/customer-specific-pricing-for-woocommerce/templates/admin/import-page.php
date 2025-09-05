<div class="wrap">
	<h3 class="import-export-header import-header"><?php esc_html_e('Import Pricing Rules', 'customer-specific-pricing-for-woocommerce'); ?></h3>
	<div id='wdm_message' class='updated hidePrev'><p class="wdm_message_p"></p></div>
</div>

<div id='wdm_import_form'>
	<div class="row csp-import-main-div-notes" style="display: block;">
		<div class="import-notes-title"><?php esc_html_e('Notes', 'customer-specific-pricing-for-woocommerce'); ?></div>
		<div class="import-notes-content">
			<ol>
				<li><?php esc_html_e('A comma separeted CSV file required for import hence the comma cannot be used as the decimal separetor in CSVs', 'customer-specific-pricing-for-woocommerce'); ?></li>
				<li><?php esc_html_e('Please confirm the required CSV file headers before creating the CSV files.', 'customer-specific-pricing-for-woocommerce'); ?></li>
			</ol>
			<ul>
				<li>
					<a href="https://wisdmlabs.com/docs/article/wisdm-customer-specific-pricing/csp-getting-started/csp-user-guide/import-export-pricing/" target="_blank">
					<?php esc_html_e('CSP Import/Export'); ?><span class="dashicons dashicons-external" style="font-size: 16px;"></span>
					</a>
				</li>
			</ul>
		</div>
	</div>
	<form name="import_form" class="wdm_import_form" method="POST" enctype="multipart/form-data">
		<div class="row">
			<div class="col-md-6">
				<?php
					wp_nonce_field('import_upload_nonce');
				?>
				<div class="wdm-input-group wdm-select-import-entity">
					<label for="dd_show_import_entities">
					<?php
					esc_html_e('Import Rules For', 'customer-specific-pricing-for-woocommerce');
					?>
					</label>                
					
					<fieldset class = "wdm_csp_fieldset">
						<legend class="screen-reader-text">
						</legend>
						<ul class = "wdm_csp_ul">
							<li>
								<label>
									<input type="radio" class="wdm_csp_import_rules_for" id="wdm_csp_import_rules_for_products" name="wdm_csp_import_rules_for" value="Products" class="" checked="checked">
									<span class="wdm_span_label"><?php esc_html_e('Products', 'customer-specific-pricing-for-woocommerce'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" class="wdm_csp_import_rules_for" id="wdm_csp_import_rules_for_categories" name="wdm_csp_import_rules_for" value="Categories" class="">
									<span class="wdm_span_label"><?php esc_html_e('Categories', 'customer-specific-pricing-for-woocommerce'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" class="wdm_csp_import_rules_for" id="wdm_csp_import_rules_for_global_discounts" name="wdm_csp_import_rules_for" value="GlobalDiscounts" class="">
									<span class="wdm_span_label"><?php esc_html_e('Global Discounts', 'customer-specific-pricing-for-woocommerce'); ?></span>
								</label>
							</li>
						</ul>
					</fieldset>
				</div>
				<div class="wdm-input-group product-import-options">
					<label for="dd_show_import_options">
					<?php
					esc_html_e('Import Using', 'customer-specific-pricing-for-woocommerce');
					?>
					</label>                
					
					<fieldset class = "wdm_csp_fieldset">
						<legend class="screen-reader-text">
						</legend>
						<ul class = "wdm_csp_ul">
							<li>
								<label>
									<input type="radio" class="wdm_csp_import_using" name="wdm_csp_import_using" value="product id" class="" checked="checked">
									<span class="wdm_span_label"><?php esc_html_e('Product Id', 'customer-specific-pricing-for-woocommerce'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" class="wdm_csp_import_using" name="wdm_csp_import_using" value="sku" class="">
									<span class="wdm_span_label"><?php esc_html_e('SKU', 'customer-specific-pricing-for-woocommerce'); ?></span>
								</label>
							</li>
						</ul>
					</fieldset>
				</div>

				<div class="wdm-input-group">
					<label for="dd_show_import_options"><?php esc_html_e('Import Type', 'customer-specific-pricing-for-woocomerce'); ?> </label>
						<select name="dd_show_import_options" id="dd_show_import_options">
							<?php
							foreach ($this->_class_value_pairs as $key => $val) {
								echo '<option value=' . esc_attr($key) . '>' . esc_html($val) . '</option>';
							}
							?>
						</select>
					<a class='sample-csv-import-template-link' target="_blank" href="<?php echo esc_url(CSP_PLUGIN_SITE_URL . '/templates/user_specific_pricing_sample.csv'); ?>"><?php esc_html_e('Sample File', 'customer-specific-pricing-for-woocomerce'); ?></a>
				</div>
				<input type="file" name="csv" id="csv" class="file" accept=".csv" data-show-preview="false" data-show-upload="false" required title="<?php esc_attr_e('Select File', 'customer-specific-pricing-for-woocommerce'); ?>">
				<div class="wdm-input-group">
					<input type="submit" id="wdm_import" name="wdm_import_csp" class="button button-primary" value="<?php esc_attr_e('Import', 'customer-specific-pricing-for-woocommerce'); ?>">
				</div>
				<div class="wdm-input-group import-message-info">
					<br>
					<i>
						<?php echo esc_html__('If the customer specific price already exists, the existing values will be overwritten by the new values. While importing using SKU please make sure that all products have SKUs before import', 'customer-specific-pricing-for-woocommerce'); ?>
					</i>
				</div>
				</div> <!--BS row column 1 end-->
			
				<?php
					$BatchSize           = get_option('dd_import_batch_size')?get_option('dd_import_batch_size'):'1000';
					$SimultaneousBatches = get_option('dd_simultaneous_threads')?get_option('dd_simultaneous_threads'):'2';
					$helpInfo            = sprintf('<a id="import-help-text" href="#" data-placement="auto" data-trigger="hover" data-toggle="popover" data-html="true" title="%s" >%s</a>', __('Recommended Settings for Importing Large Files', 'customer-specific-pricing-for-woocommerce'), __('Help ?', 'customer-specific-pricing-for-woocommerce'));
				?>
				<div class="wdm-input-group col-md-6 wdm-import-notes">
					<div style="text-align: justify;">
						<b><?php esc_html_e('Note:', 'customer-specific-pricing-for-woocommerce'); ?></b>
						<br>
						<?php 
							esc_html_e('For a large CSV file, kindly consider splitting the entries by deciding the number of splits (simultaneous batches) and capacity of each split (records in a batch).', 'customer-specific-pricing-for-woocommerce'); 
							echo ' [' . wp_kses_post($helpInfo) . ']';	
						?>
					</div>
						<br>
						<label for="dd_import_batch_size"><?php esc_html_e('Records In a Batch ', 'customer-specific-pricing-for-woocommerce'); ?> </label>
						<input type="number" min="20" name="dd_import_batch_size" id="dd_import_batch_size" placeholder=" 1000 (Recommended)" value="<?php echo esc_attr($BatchSize); ?>">
								<br/><br/>
						<label for="dd_simultaneous_threads"><?php esc_html_e('Simultaneous Batches ', 'customer-specific-pricing-for-woocommerce'); ?> </label>
						<input type="number" min="1" max="5" name="dd_simultaneous_threads" id="dd_simultaneous_threads" placeholder="2 (Recommended)" value="<?php echo esc_attr($SimultaneousBatches); ?>">
				</div>
		</div><!-- BS row closed -->
	</form>
</div>
<style>
.csp-gd-main-div{
	margin: auto;
	}
.csp-gd-main-div-notes {
	  margin:auto 30px auto 30px;
	}
.gd-notes-title{
		font-weight: 700;
		cursor: pointer;
		text-decoration: underline;
		color: #337ab7;
		margin-bottom: 5px;
	}
.gd-notes-content {
		padding: 0 18px;
		max-height: 0;
		overflow: hidden;
		transition: max-height 0.5s ease-out;
	}
</style>
