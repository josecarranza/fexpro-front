<div class="scheduled-backups-container">
<div class="csp-flex-container">
  <div class="csp-flex-item-left"></div>
  <div class="csp-flex-item-mid">
  <div class="schedule-form">
	  <table class="schedule-form-fields">
		<tr>
			<td>
				<label for="backupfrequency"><?php esc_html_e('Frequency', 'customer-specific-pricing-for-woocommerce'); ?></label>
			</td>
			<td>
				<select name="backupfrequency" id="backupfrequency">
					<option value="daily" <?php echo 'daily'==$frequency?'selected':''; ?>><?php esc_html_e('Every Day', 'customer-specific-pricing-for-woocommerce'); ?></option>
					<option value="weekly" <?php echo 'weekly'==$frequency?'selected':''; ?>><?php esc_html_e('Every Week', 'customer-specific-pricing-for-woocommerce'); ?></option>
				</select>
			</td>
		</tr>
		<tr class="weekday-selection">
			<td><label for="on-weekday"><?php esc_html_e('On', 'customer-specific-pricing-for-woocommerce'); ?></label></td>
			<td>
			<select name="on-weekday" id="on-weekday">
					<option value="1" <?php echo $weekDay?'selected':''; ?>><?php esc_html_e('Monday', 'customer-specific-pricing-for-woocommerce'); ?></option>
					<option value="2" <?php echo $weekDay?'selected':''; ?>><?php esc_html_e('Tuesday', 'customer-specific-pricing-for-woocommerce'); ?></option>
					<option value="3" <?php echo $weekDay?'selected':''; ?>><?php esc_html_e('Wednesday', 'customer-specific-pricing-for-woocommerce'); ?></option>
					<option value="4" <?php echo $weekDay?'selected':''; ?>><?php esc_html_e('Thursday', 'customer-specific-pricing-for-woocommerce'); ?></option>
					<option value="5" <?php echo $weekDay?'selected':''; ?>><?php esc_html_e('Friday', 'customer-specific-pricing-for-woocommerce'); ?></option>
					<option value="6" <?php echo $weekDay?'selected':''; ?>><?php esc_html_e('Saturday', 'customer-specific-pricing-for-woocommerce'); ?></option>
					<option value="0" <?php echo $weekDay?'selected':''; ?>><?php esc_html_e('Sunday', 'customer-specific-pricing-for-woocommerce'); ?></option>
			  </select>
			</td>
		</tr>
		<tr>
			<td><label for="backup-time"><?php esc_html_e('At', 'customer-specific-pricing-for-woocommerce'); ?></label></td>
			<td>
			  <input type="time" name="backup-time" id="backup-time" data-time-format="H:i" maxlength="5" value="<?php echo esc_attr($time); ?>">
			</td>
		</tr>
		<tr>
			<td>
			<label for="max-backups-to-store"><?php esc_html_e('Max. Backups To Store', 'customer-specific-pricing-for-woocommerce'); ?></label>
			</td>
			<td>
				<input type="number" name="max-backups-to-store" id="max-backups-to-store" min="1" placeholder="5" value="<?php echo esc_attr($maxBackups); ?>">
			</td>
		</tr>
		<tr>
			<td class="button-container"><button type="submit" id="saveBackupSchedule" class="button button-primary"><?php esc_html_e('Save Schedule', 'customer-specific-pricing-for-woocommerce'); ?></button></td>
			<td class="button-container"><button type="submit" id="StopBackupSchedule" class="button button-secondary"><?php esc_html_e('Remove Schedule', 'customer-specific-pricing-for-woocommerce'); ?></button></td>
		</tr>
	  </table>
  </div>
  </div>
  <div class="csp-flex-item-right"></div>
</div>

<div class="csp-flex-container-list">
	<div class="csp-flex-item-left-list"></div>
	<div class="csp-flex-item-mid-list">
		<div class="backup-list">
			<h4><?php esc_html_e('Recently Created Backups', 'customer-specific-pricing-for-woocommerce'); ?></h4>
			<table class="backup-table">
				<tbody>
				<?php
				if (!empty($backedUpFileList)) {
					foreach ($backedUpFileList as $file) {
						?>
						<tr>
							<td class="created-backup"><a href="<?php esc_attr_e($file['fileUrl']); ?>"><?php esc_html_e($file['fileName']); ?></a></td>
							<td class="date-of-creation"><?php esc_html_e($file['fileDate']); ?></td>
							<td><span title="<?php echo esc_html__('Delete backup file : ', 'customer-specific-pricing-for-woocommerce') . esc_attr($file['fileName']); ?>" class="dashicons dashicons-trash delete-backup-file" data-delete="<?php esc_html_e($file['fileName']); ?>"></span></td>
						</tr>
						<?php
					}
				} else {
					?>
					<tr>
						<td colspan="2"><?php esc_html_e('No Backups Found', 'customer-specific-pricing-for-woocommerce'); ?></td>
					</tr>
					<?php
				}
				?>
			  </tbody>
		  </table>
		
	  </div>
  </div>
  <div class="csp-flex-item-right-list"></div>
</div>
</div>
