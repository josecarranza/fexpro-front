jQuery(document).ready(function () {
	jQuery('#backup-time').timepicker();

	if (jQuery('#backupfrequency').val()=='weekly') {
		jQuery('.weekday-selection').show(200);
	} else {
		jQuery('.weekday-selection').hide(200);
	}

	jQuery('#backupfrequency').change( function(){
		let val = jQuery(this).val();
		if ('daily'==val) {
			jQuery('.weekday-selection').hide(200);
		} else {
			jQuery('.weekday-selection').show(200);
		}
	});


	jQuery('#saveBackupSchedule').click(function(){
		let freq 	   = jQuery('#backupfrequency').val();
		let weekDay    = jQuery('#on-weekday').val();
		let time	   = jQuery('#backup-time').val();
		let maxBackups = jQuery('#max-backups-to-store').val();
		jQuery('#saveBackupSchedule').attr('disabled', true).css('cursor','progress');
		jQuery.ajax({
            type: 'POST',
            url: wdm_csp_export_ajax.ajaxurl,
            data: {
                action		: 'csp_schedule_backups',
                _wpnonce    : wdm_csp_export_ajax.export_nonce,
				frequency   : freq,
				week_day    : weekDay,
				backup_time : time,
				backup_limit: maxBackups
            },
            "timeout": 60000,
            error: function(jqXHR, textStatus, errorThrown) {
                if(textStatus==="timeout") {
                   alert(wdm_csp_export_ajax.timeout_message);
                   jQuery('#saveBackupSchedule').removeAttr('disabled', false).css('cursor','pointer');
                } 
            },
            success: function (response) {
                jQuery('#saveBackupSchedule').removeAttr('disabled', false).css('cursor','pointer');
                if (response.data.status=='success') {
					alert(wdm_csp_export_ajax.backup_scheduled_message);
				} else {
					alert(response.data.error_message);
				}
            }
        });
	});

	jQuery('#StopBackupSchedule').click(function(){
		
		jQuery('#StopBackupSchedule').attr('disabled', true).css('cursor','progress');
		jQuery.ajax({
            type: 'POST',
            url: wdm_csp_export_ajax.ajaxurl,
            data: {
                action		: 'csp_stop_scheduled_backups',
                _wpnonce    : wdm_csp_export_ajax.export_nonce,
            },
            "timeout": 60000,
            error: function(jqXHR, textStatus, errorThrown) {
                if(textStatus==="timeout") {
                   alert(wdm_csp_export_ajax.timeout_message);
                   jQuery('#StopBackupSchedule').removeAttr('disabled', false).css('cursor','pointer');
                } 
            },
            success: function (response) {
                jQuery('#StopBackupSchedule').removeAttr('disabled', false).css('cursor','pointer');
                if (response.data.status=='success') {
					location.reload();
				} else {
					alert(response.data.error_message);
				}
            }
        });	
	});

	jQuery('.delete-backup-file').click(function(){
		
		jQuery(this).css('cursor','progress');
		let fileName = jQuery(this).attr('data-delete'); 
		jQuery.ajax({
            type: 'POST',
            url: wdm_csp_export_ajax.ajaxurl,
            data: {
                action		: 'csp_remove_auto_backup_file',
                _wpnonce    : wdm_csp_export_ajax.export_nonce,
				file_name   : fileName,
            },
            "timeout": 60000,
            error: function(jqXHR, textStatus, errorThrown) {
                if(textStatus==="timeout") {
                   alert(wdm_csp_export_ajax.timeout_message);
                } 
            },
            success: function (response) {
                if (response.data.status=='success') {
					location.reload();
				} else {
					alert(response.data.error_message);
				}
            }
        });	
	});

});