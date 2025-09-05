jQuery( document ).ready(function() { 
	jQuery('input').attr('autocomplete', 'off');
	jQuery('#import-time').timepicker();
	cspManageFieldsForSelectedFrequency(jQuery('#auto-import-frequency').val());
	
	// On Selecting the Frequency
	jQuery('#auto-import-frequency').change(function() {
		cspManageFieldsForSelectedFrequency(jQuery('#auto-import-frequency').val());
	});

	jQuery('input[name="connection_method"]').change(function() {
		let fileConnection = jQuery(this).val();
		
		switch (fileConnection) {
			case 'file-url':
				jQuery('.ftp-input-rows').hide();
				jQuery('#csp-import-test-connection').hide();
				jQuery('.file-url-row').show(200);	
			break;
			case 'ftp':
			case 'sftp':
				jQuery('#csp-import-test-connection').show(200);
				jQuery('.ftp-input-rows').show(200);
				jQuery('.file-url-row').hide();
				jQuery('#ftp-port').attr('placeholder', '21');
				if ('sftp'==fileConnection) {
					jQuery('#ftp-port').attr('placeholder', '22');
				}
			break;
			default:
				jQuery('.file-url-row').hide(200);
				jQuery('#csp-import-test-connection').hide(200);
				jQuery('.ftp-input-rows').hide(200);
				jQuery('.file-url-row').hide(200);
			break;
		}
		
	});

	jQuery('#import-time').change(function () {
		let timeEntered = jQuery('#import-time').val();
		if(!cspValidTime(timeEntered)) {
			cspHighlightInputElementOnError(jQuery('#import-time'));
			jQuery('#import-time').val('');
		}
	})

	jQuery('#csp-import-test-connection').click(function() {
			let fileConnection = jQuery('input[name="connection_method"]:checked').val();
			let ftpUrl		   = jQuery('#ftp-url').val();
			let ftpPort		   = jQuery('#ftp-port').val();
			let ftpUserName	   = jQuery('#import-file-username').val();
			let ftpPassword	   = jQuery('#import-file-password').val();
			let ftpFilePath    = jQuery('#import-file-path').val();
		
			jQuery('#csp-import-test-connection').attr('disabled', true).text('Please Wait').css('cursor','progress');
			ftpUrl = cspIsValidUrl(ftpUrl, 'ftp');
			if (!ftpUrl) {
				cspHighlightInputElementOnError(jQuery('#ftp-url'));
				jQuery('#csp-import-test-connection').removeAttr('disabled').text(wdm_csp_import_object.connection_testing).css('cursor','pointer');
				return;
			}

			ftpPort = empty(ftpPort)?'':ftpPort;
			if (empty(ftpUserName)) {
				cspHighlightInputElementOnError(jQuery('#import-file-username'));
				jQuery('#csp-import-test-connection').removeAttr('disabled').text(wdm_csp_import_object.connection_testing).css('cursor','pointer');
				return;
			}

			if (empty(ftpPassword)) {
				cspHighlightInputElementOnError(jQuery('#import-file-password'));
				jQuery('#csp-import-test-connection').removeAttr('disabled').text(wdm_csp_import_object.connection_testing).css('cursor','pointer');
				return;
			}


			jQuery('#schedule-form-loader-row').show();
			jQuery.ajax({
				type: 'POST',
				url: wdm_csp_import_object.ajax_url,
				data: {
					action			: 'csp_test_ftp_sftp_connection',
					connection		: fileConnection,
					url				: ftpUrl,
					port			: ftpPort,
					user_name		: ftpUserName,
					password		: ftpPassword,
					file_path		: ftpFilePath,
					_wp_import_nonce: wdm_csp_import_object.nonce,
				},
				"timeout": 60000,
				error: function(jqXHR, textStatus, errorThrown) {
					if(textStatus==="timeout") {
					   alert(wdm_csp_import_object.error_mesages.request_timeout_message);
					} 
					jQuery('#csp-import-test-connection').removeAttr('disabled').text(wdm_csp_import_object.connection_testing).css('cursor','pointer');
					jQuery('#schedule-form-loader-row').hide();
				},
				success: function (response) {
					jQuery('#csp-import-test-connection').removeAttr('disabled').text(wdm_csp_import_object.connection_testing).css('cursor','pointer');
					jQuery('#schedule-form-loader-row').hide();
					if (response.data.status=='success') {
						alert(wdm_csp_import_object.ftp_connection_success_text);
					} else {
						alert(response.data.error_message);
					}
				}
			});	
	}); 


	jQuery('#csp-schedule-reset-form').click(function() {
		jQuery('#auto-import-date').val('');
		jQuery('#import-time').val('');
		jQuery('input[name="connection_method"]').removeAttr('checked');
		jQuery('#import-file-url').val('');
		jQuery('#ftp-url').val('');
		jQuery('#ftp-port').val('');
		jQuery('#import-file-username').val('');
		jQuery('#import-file-password').val('');
		jQuery('#import-file-path').val('');
		jQuery('#schedule-form-container').find('select').prop('selectedIndex', 0);
		jQuery('#auto-import-frequency').change();
		jQuery('.ftp-input-rows').hide();
		jQuery('.file-url-row').hide();
	});

	
	jQuery('.delete-schedule-icon > .dashicons').click(function () {
		let rulePanel = jQuery(this).closest('.csp-schedule-panel');
		let id        = jQuery(rulePanel).attr('data-schedule-id');
		if(confirm(wdm_csp_import_object.schedule_deletion_warning)) {
			jQuery.ajax({
				type: 'POST',
				url: wdm_csp_import_object.ajax_url,
				data: {
					action			: 'csp_delete_import_schedule',
					schedule_id		: id,
					_wp_import_nonce: wdm_csp_import_object.nonce,
				},
				"timeout": 60000,
				error: function(jqXHR, textStatus, errorThrown) {
					if(textStatus==="timeout") {
					   alert(wdm_csp_import_object.error_mesages.request_timeout_message);
					} 
				},
				success: function (response) { 
					if (response.data.status=='success') {
						let element = '.csp-schedule-panel[data-schedule-id='+response.data.schedule_id+']'
						jQuery(element).remove();
					}
				}
			});
			// rulePanel.remove();
		}
	});

	jQuery('#csp-schedule-import').click(function() {
		let scheduleData = cspValidateImportScheduleData();
		if (!empty(scheduleData)) {
			jQuery('#csp-schedule-import').attr('disabled', true).text('Please Wait').css('cursor','progress');
			scheduleData['action']			 = 'csp_add_update_import_schedule';
			scheduleData['_wp_import_nonce'] = wdm_csp_import_object.nonce;
			jQuery('#schedule-form-loader-row').show();
			jQuery.ajax({
				type: 'POST',
				url: wdm_csp_import_object.ajax_url,
				data: scheduleData,
				"timeout": 60000,
				error: function(jqXHR, textStatus, errorThrown) {
					if(textStatus==="timeout") {
					   alert(wdm_csp_import_object.error_mesages.request_timeout_message);
					   jQuery('#csp-schedule-import').removeAttr('disabled', false).text(wdm_csp_import_object.schedule_for_import).css('cursor','pointer');
					   jQuery('#schedule-form-loader-row').hide();
					} 
				},
				success: function (response) {
					jQuery('#schedule-form-loader-row').hide();
					jQuery('#csp-schedule-import').removeAttr('disabled', false).text(wdm_csp_import_object.schedule_for_import).css('cursor','pointer');
					if (response.data.status=='success') {
						jQuery('#csp-schedule-reset-form').click();
						alert(response.data.message);
						location.reload();
					} else {
						alert(response.data.message);
					}
				}
			});
		}
	});


});


/**
 * This function on called manages display of the fields according to the selection of the 
 * frequency for the scheduled import.
 * 
 * @param {string} $frequency 
 */
function cspManageFieldsForSelectedFrequency($frequency) {
	switch ($frequency) {
		case 'daily':
			jQuery('.import-weekday-row').hide();
			jQuery('.import-date-row').hide();
			break;
	
		case 'weekly':
			jQuery('.import-date-row').hide();
			jQuery('.import-weekday-row').show(200);
			break;
		
		default:
			jQuery('.import-weekday-row').hide();
			jQuery('.import-date-row').show(200);
			break;
	}
}

/**
 * Validates the data entered for the schedule & returns the data in the form of array
 *  
 */
function cspValidateImportScheduleData() {
	let name 		   = jQuery('#import-schedule-name').val();
	let frequency      = jQuery('#auto-import-frequency').val();
	let date	       = jQuery('#auto-import-date').val();
	let weekDay	       = jQuery('#auto-import-weekday').val();
	let time	       = jQuery('#import-time').val();
	let fileConnection = jQuery('input[name="connection_method"]:checked').val();
	let fileUrl        = jQuery('#import-file-url').val();
	let ftpUrl		   = jQuery('#ftp-url').val();
	let ftpPort		   = jQuery('#ftp-port').val();
	let ftpUserName	   = jQuery('#import-file-username').val();
	let ftpPassword	   = jQuery('#import-file-password').val();
	let ftpFilePath    = jQuery('#import-file-path').val();
	
	if (empty(name)) {
		cspHighlightInputElementOnError(jQuery('#import-schedule-name'));
		return false;
	}

	switch (frequency) {
		case 'once':
			if (!cspValidDate(date)) {
				alert(wdm_csp_import_object.error_mesages.invalid_date);
				return false;
			} else if(!cspValidTime(time)) {
				alert(wdm_csp_import_object.error_mesages.invalid_time);
				return ;
			}
			break;
		case 'daily':
			if(!cspValidTime(time)) {
				alert(wdm_csp_import_object.error_mesages.invalid_time);
				return false;
			}
			break;
		case 'weekly':
			if(!cspValidTime(time)) {
				alert(wdm_csp_import_object.error_mesages.invalid_time);
				return false;
			}
			if (!cspValidWeek(weekDay)) {
				alert(wdm_csp_import_object.error_mesages.invalid_weekday);
				return false;
			}
			break;
		default:
			break;
	}
	

	if (empty(fileConnection)) {
		alert(wdm_csp_import_object.error_mesages.invalid_file_connection);
		return false;
	}


	switch (fileConnection) {
		case 'file-url':
			fileUrl = cspIsValidUrl(fileUrl, 'http');
			if (!fileUrl) {
					alert(wdm_csp_import_object.error_mesages.invalid_file_url);
				return false;
			}	
			break;
		
		case 'sftp':
		case 'ftp':
			ftpUrl = cspIsValidUrl(ftpUrl, 'ftp');
			if (!ftpUrl) {
				alert(wdm_csp_import_object.error_mesages.invalid_ftp_url);
				return false;
			}

			ftpPort = empty(ftpPort)?21:ftpPort;
			if (empty(ftpUserName)) {
				alert(wdm_csp_import_object.error_mesages.invalid_ftp_url);
				return false;
			}

			if (empty(ftpPassword)) {
				alert(wdm_csp_import_object.error_mesages.invalid_ftp_password);
				return false;
			}

			if (empty(ftpFilePath)) {
				alert(wdm_csp_import_object.error_mesages.invalid_file_path);
				return false;
			}
			break;

		default:
			break;
	}

	
	
	let data = {'name':name,
				'frequency':frequency, 
				'date':date, 
				'week_day':weekDay, 
				'time':time, 
				'file_connection_type':fileConnection, 
				'file_url':fileUrl,
				'ftp_url':ftpUrl,
				'ftp_port':ftpPort,
				'ftp_username':ftpUserName,
				'ftp_password':ftpPassword,
				'ftp_file_path':ftpFilePath,
			}; 
	return data;
}


/**
 * Validates if the entered date is valid returns true if the date is valid, false otherwise.
 * 
 * @param {string} dateString 
 * @returns {bool} true if the date is valid false otherwise. 
 */
function cspValidDate(dateString) {	
	if(dateString == '') {
	  return false;
	}
	let dateObj = new Date(dateString);
	if(isNaN(dateObj.getTime())) {
		return false;
	}

	return true;
}

/**
 * This function validates if the time string passed indicates the correct time entry.
 * 
 * @param {string} timeString 23:59
 * @returns true if the time is valid, false otherwise.
 */
function cspValidTime(timeString) {
	if(timeString == '') {
		return false;
	}
	var timeRegex = new RegExp('^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$');
	if(!timeRegex.test(timeString)){
	    return false;
	}
	return true;
}

/**
 * Validates if the weekday selected is correct.
 * 
 * @param {int} weekDay
 */
function cspValidWeek(weekDay) {
	if (empty(weekDay) || (weekDay<0 || weekDay>6)) {
		return false;
	}
	return true;
}

/**
 * Validates if the string passed is the valid URL
 * 
 * @param {string} string 
 * @returns false if invalid string, url if it is valid
 */
function cspIsValidUrl(string, type) {
	let url;
	if (!string.includes(type)) {
		urlString = type + '://';
		string = urlString.concat(string);
	}

	if(/^(http|https|ftp|sftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z0-9]{1,5}(:[0-9]{1,5})?(\/.*)?$/i.test(string)){
		return string;
	} else {
		return false;
	} 
}

/**
 * Adds the error class 'invalid-data-indicator' to the element 
 * and removes it after the defined time.
 * 
 * @param {object} element element to add error class.
 * @param {int} time time in ms 
 */
function cspHighlightInputElementOnError(element, time = 4000) {
	element.addClass('invalid-data-indicator');
	setTimeout(function(){
		jQuery(element).removeClass('invalid-data-indicator');
	}, time);
}

function cspDisableFormFields() {
	jQuery('#schedule-form-container').find('select').attr('disabled', true).css('cursor','progress');
	jQuery('#schedule-form-container').find('input').attr('disabled', true).css('cursor','progress');
	jQuery('#schedule-form-container').css('cursor','progress');
}