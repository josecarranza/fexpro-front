<div class="wrap"><h3 class="csp-tab-header">
	<?php esc_html_e('Search By & Delete', 'customer-specific-pricing-for-woocommerce'); ?></h3>
	<input type="hidden" name="search-by-page-nonce" id='search-by-page-nonce' value="<?php esc_attr_e(wp_create_nonce('search-by-page-nonce')); ?>">
</div>
<hr/>				
<div class="wdm-csp-single-view-search-wrapper">
	<div class="form-group row wdm-csp-single-view-from-group">
		<label class="col-md-3 form-control-label"> <?php echo esc_html_e('Search price being applied for a', 'customer-specific-pricing-for-woocommerce'); ?> </label>
		<div class="col-md-6 form-control-wrap">
			<fieldset>
				  <div class="price-for-radio-select">
					<?php
					if (!empty($available_options) && is_array($available_options)) {
						foreach ($available_options as $key => $value) { 
							?>
								<input type="radio" class="price-for-radio" name="price-for-select" value="<?php echo esc_attr($value); ?>" id="<?php echo esc_attr($key); ?>" />
								<label class='price-for-radio' for="<?php echo esc_attr($key); ?>"><?php echo esc_attr($value); ?></label>
							<?php
						} //foreach ends
					}
					?>
				  </div>
			</fieldset>
		</div>
	</div>
	<!-- Customer Picker -->
	<div class="form-group row wdm-csp-single-view-from-group row-selection row-customer-selection" style="display:none;">
		<label class="col-md-3 form-control-label" for="csp-search-user-selection"> 
			<?php echo esc_html_e('Select a customer', 'customer-specific-pricing-for-woocommerce'); ?>
		</label>
		<div class="col-md-6 form-control-wrap csp-selection-wrap" >
			<select name="csp-search-user-selection" id="csp-search-user-selection" class="csp-selection user-selection" style="width: 60%;">
				<option value=""></option>
			</select>
		</div>
	</div>
	<!-- User Role Picker -->
	<div class="form-group row wdm-csp-single-view-from-group row-selection row-user-role-selection" style="display:none;">
		<label class="col-md-3 form-control-label" for="csp-user-role-selection"> 
			<?php echo esc_html_e('Select a user role', 'customer-specific-pricing-for-woocommerce'); ?>
		</label>
		<div class="col-md-6 form-control-wrap csp-selection-wrap">
			<select name="user-role-selection" class="csp-selection user-role-selection" id="csp-user-role-selection" style="width: 60%;">
				<option></option>
				<?php
				foreach ($userRoles as $key => $value) {
					?>
					<option value="<?php echo esc_attr($key); ?>"><?php esc_html_e($value); ?></option>
					<?php
				}
				?>
			</select>
		</div>
	</div>
	<!-- User Group Picker -->
	<div class="form-group row wdm-csp-single-view-from-group row-selection row-user-group-selection" style="display:none;">
		<label class="col-md-3 form-control-label" for="csp-user-group-selection"> 
			<?php echo esc_html_e('Select a user group', 'customer-specific-pricing-for-woocommerce'); ?>
		</label>
		<div class="col-md-6 form-control-wrap csp-selection-wrap">
			<select name="user-group-selection" id="csp-user-group-selection" class="csp-selection user-group-selection" style="width: 60%;">
				<option></option>
				<?php
				if (!empty($userGroups)) {
					foreach ($userGroups as $key => $value) {
						?>
						<option value="<?php echo esc_attr($key); ?>"><?php esc_html_e($value); ?></option>
						<?php
					}
				}
				?>
			</select>
		</div>
	</div>

	<div class="wdm-csp-single-view-result-wrapper">
	</div>
	<input type="button" class="button-primary wdm-remove-sel-csp-price" name="remove-sel-csp-price" id="remove-sel-csp-price" value="<?php esc_attr_e('Delete', 'customer-specific-pricing-for-woocommerce'); ?>">
</div>
