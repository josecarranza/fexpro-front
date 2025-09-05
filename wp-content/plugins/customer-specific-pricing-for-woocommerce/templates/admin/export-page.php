<div class="export-parent-wrap">
	<!-- Left Column -->
	<div class="export-col export-col-left">
		<div class="wrap">
			<h4 class="import-export-header"> <?php esc_html_e('Product Specific Rules', 'customer-specific-pricing-for-woocommerce'); ?> </h4>
		 </div>
			<table cellspacing="10px" class = "wdm_csp_export_table">
				<tr>
					<th scope="row">
						<label><?php esc_html_e('Export Using', 'customer-specific-pricing-for-woocommerce'); ?></label>
						<div>
							<span class="icon-help"></span>
						</div>
					</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text">
							</legend>
							<ul class="wdm_csp_ul">
								<li>
									<label class="">
										<input type="radio" class="wdm_csp_export_using" name="wdm_csp_export_using" value="product id" checked="checked">
										<span class=""><?php esc_html_e('Product Id', 'customer-specific-pricing-for-woocommerce'); ?></span>
									</label>
								</li>
								<li>
									<label>
										<input type="radio" class="wdm_csp_export_using" name="wdm_csp_export_using" value="sku">
										<span class=""><?php esc_html_e('SKU', 'customer-specific-pricing-for-woocommerce'); ?></span>
									</label>
								</li>
							</ul>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th>
						<label for="dd_show_export_options"><?php esc_html_e('Export Type :', 'customer-specific-pricing-for-woocommerce'); ?> </label>
					</th>
					<td>
						<select name="dd_show_export_options" id="dd_show_export_options">
							<?php
							foreach ($this->_class_value_pairs as $key => $val) {
								echo '<option value=' . esc_attr($key) . '>' . esc_html($val) . '</option>';
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
					<input type="button" value="<?php esc_html_e('Export', 'customer-specific-pricing-for-woocommerce'); ?>" id="export" name="export" class="button button-primary btn-csv-export">
					</td>
					<td>
					<input type="button" value="<?php esc_html_e('Get Product List', 'customer-specific-pricing-for-woocommerce'); ?>" id="get-product-list" name="get-product-list" class="button btn-csv-export get-product-list" title="<?php esc_html_e('Get a CSV file with product Ids & titles for a referance', 'customer-specific-pricing-for-woocommerce'); ?>">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div id="wdm_message" class="below-h2" style="display: block;"><p class="wdm_message_p"></p></div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<?php
						esc_html_e('While exporting using SKU, Please make sure all products have SKUs.', 'customer-specific-pricing-for-woocommerce');
						?>
						<br>
						<?php
						esc_html_e('The products which do not have an associated SKU will be skipped during the export operation', 'customer-specific-pricing-for-woocommerce');
						?>
					</td>
				</tr>
			</table>
	</div>
	<!-- Right Column -->
	<div class="export-col export-col-right">
		<div class="wrap">
		<h4 class="import-export-header"> <?php esc_html_e('Bulk Pricing Rules', 'customer-specific-pricing-for-woocommerce'); ?> </h4>
		 </div>
			<table cellspacing="10px" class = "wdm_csp_bulk_export_table">
				<tr>
					<th scope="row">
						<label><?php esc_html_e('Export', 'customer-specific-pricing-for-woocommerce'); ?></label>
						<div>
							<span class="icon-help"></span>
						</div>
					</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text">
							</legend>
							<ul class="wdm_csp_ul">
								<li>
									<label class="">
										<input type="radio" class="wdm_csp_bulk_export_for" name="wdm_csp_bulk_export_for" value="category" checked="checked">
										<span class=""><?php esc_html_e('Category Rules', 'customer-specific-pricing-for-woocommerce'); ?></span>
									</label>
								</li>
								<li>
									<label>
										<input type="radio" class="wdm_csp_bulk_export_for" name="wdm_csp_bulk_export_for" value="global">
										<span class=""><?php esc_html_e('Global Discount Rules', 'customer-specific-pricing-for-woocommerce'); ?></span>
									</label>
								</li>
							</ul>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th>
						<label for="dd_show_bulk_export_options"><?php esc_html_e('Export Type :', 'customer-specific-pricing-for-woocommerce'); ?> </label>
					</th>
					<td>
						<select name="dd_show_bulk_export_options" id="dd_show_bulk_export_options">
							<?php
							foreach ($this->_class_value_pairs as $key => $val) {
								echo '<option value=' . esc_attr($key) . '>' . esc_html($val) . '</option>';
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
					<input type="submit" value="<?php esc_html_e('Export', 'customer-specific-pricing-for-woocommerce'); ?>" id="export_bulk" name="export_bulk" class="button button-primary btn-csv-export">
					
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div id="bulk_export_message" class="below-h2" style="display: block;"><p class="wdm_message_bulk"></p></div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<?php
						esc_html_e('Select the export type & click the export button to download the CSV file.', 'customer-specific-pricing-for-woocommerce');
						?>
						<br>
						<?php
						esc_html_e('You can export category specific pricing rules & global discount rules using this feature.', 'customer-specific-pricing-for-woocommerce');
						?>
					</td>
				</tr>
			</table>
	</div>
</div>
<!-- Export All -->
<div class="export-all-wrap">
		<button type="button" id="btn-csv-export-all" class="button button-primary btn-csv-export-all" name="export-all" value="export-all" title="<?php esc_html_e('Prepare and export all the CSP rules as a ZIP Archive', 'customer-specific-pricing-for-woocommerce'); ?>"><?php esc_html_e('Export All CSP Rules', 'customer-specific-pricing-for-woocommerce'); ?>
		<span class="dashicons dashicons-media-archive"></span>
		</button>
		<div id="wdm_all_export_message" class="below-h2" style="display: block;">
			<p class="wdm_message_p">
			</p>
		</div>
</div>
