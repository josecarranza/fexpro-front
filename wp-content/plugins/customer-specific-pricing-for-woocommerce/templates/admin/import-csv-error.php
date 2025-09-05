<div class="wdm_message_p error">
<h5> <?php esc_html_e('Please check the csv file you are trying to upload', 'customer-specific-pricing-for-woocommerce'); ?> </h5>
	<p> 
		<strong><?php esc_html_e('Required Headers : ', 'customer-specific-pricing-for-woocommerce'); ?></strong> 
		<?php echo esc_html($requiredHeaders); ?>
			  
		<br>
		<strong><?php esc_html_e('Headers of the uploaded file : ', 'customer-specific-pricing-for-woocommerce'); ?></strong>
		<?php echo esc_html($currentHeaders); ?>
	</p>
</div>
