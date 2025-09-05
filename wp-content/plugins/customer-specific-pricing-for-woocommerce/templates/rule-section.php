<?php

?>
<div id="userSpecificPricingTab_data" class="panel woocommerce_options_panel" style="display:none;">
	<!-- Notes shown before the CSP rule edit section on the simple product edit page-->
	<div class="csp-notes-div">
		<span class="csp-notes-title"><?php esc_html_e('Notes', 'customer-specific-pricing-for-woocommerce'); ?>:</span>
		<ol class="csp-notes-content">
				<li><?php esc_html_e('If a customer is added more than once, the customer-price combination first in the list will be saved, and other combinations will be removed.', 'customer-specific-pricing-for-woocommerce'); ?></li>
				<li><?php esc_html_e('If the price field is left blank, then default product price will be shown.', 'customer-specific-pricing-for-woocommerce'); ?></li>
				<li><?php esc_html_e('Please set the min qty before saving. Only then, the discounted amount will be saved and will reflect to the logged in user, role or group.', 'customer-specific-pricing-for-woocommerce'); ?></li>
				<li><?php esc_html_e('If a customer belongs to multiple groups (or roles), the least price set for the group (or role) will be applied', 'customer-specific-pricing-for-woocommerce'); ?></li>
				<li><?php esc_html_e('The priorities are as follows', 'customer-specific-pricing-for-woocommerce'); ?> - 
				<ol>
					<li><?php esc_html_e('Customer Specific Price', 'customer-specific-pricing-for-woocommerce'); ?></li>
					<li><?php esc_html_e('Role Specific Price', 'customer-specific-pricing-for-woocommerce'); ?></li>
					<li><?php esc_html_e('Group Specific Price', 'customer-specific-pricing-for-woocommerce'); ?></li>
					<li><?php esc_html_e('Regular Price', 'customer-specific-pricing-for-woocommerce'); ?></li>
				</ol>
				</li>
		</ol>
		<?php 
			/**
			 * This action gets triggered after showing rule notes section
			 */
			do_action('wdm_csp_after_rule_notes'); 
		?>
	</div>

	<!-- CSP Rule Accordians By Type -->
	<div class="accordion csp-accordion">
	<?php
		/**
		 * This action gets triggred while loading the CSP rule edit sections for the simple products on product edit pages,
		 * the action is used internally to list all the three CSP price type rule edit sections
		 */
		do_action('wdm_csp_simple_rule_section');
	?>
	</div>
	<?php 
	/**
	 * This action triggers after generating the html for the CSP prices sections on the simple product edit pages.
	 */
	do_action('wdm_after_csp_simple_rule_section'); 
	?>
	<p>
	<strong><?php esc_html_e('Remember to Publish/Update the product to save any changes made.', 'customer-specific-pricing-for-woocommerce'); ?></strong>
	</p>
</div>
