var cspRulessProcessed = 0;
var cspInsertCount     = 0;
var cspUpdateCount     = 0;
var cspSkipCount       = 0;
var cspTotalRecords    = 0;
var cspBatchesProcesed = 0;
var importStatus	   = "processing";

jQuery( document ).ready(function() {
	
	// Show the name of the file selected on select box
	jQuery("#csp-csv-picker").on("change", function() {
		var fileName = jQuery(this).val().split("\\").pop();
		jQuery(this).siblings(".custom-file-label").addClass("selected").html(fileName);
  	});

	/**
	 * Upload the selected CSV file, print error message if the file is invalid, 
	 * Start import operation if file is valid & start showing the progress status.
	 */
	jQuery('#csp-start-import').click(function() {
		var formData = new FormData();
		var files = jQuery('#csp-csv-picker')[0].files[0];
		
		if ("undefined"==typeof(files)) {
			// showToastMessage(wdm_csp_import_object.notice_header, 'warning',  wdm_csp_import_object.import_button_warning);
			jQuery('#csp-csv-picker').focus().click();
			return;
		}
		formData.append('file',files);
		formData.append('action', 'csp_get_import_csv'); 
		formData.append('import_nonce', wdm_csp_import_object.nonce);
		
		showToastMessage(wdm_csp_import_object.notice_header, 'update',  wdm_csp_import_object.upload_in_progress_text);
		changeImportButtonStatus(true);
		
		// AJAX request
		jQuery.ajax({
			url: wdm_csp_import_object.ajax_url,
			type: 'post',
			data: formData,
			contentType: false,
        	processData: false,
			timeout: 120000, // sets timeout to 2 minute
			success: function(response){
				changeImportButtonStatus(false);
				if (!empty(response) && !response.success) {
					alert(response.data[0].message);
					return;
				}

				if (response.success) {
					showToastMessage(wdm_csp_import_object.notice_header, 'success',  wdm_csp_import_object.upload_successful_text);
					jQuery('div.row-file-upload').hide();
					jQuery('div.loading-section').show();
					initiateImportProcess(response.data.data);
				}
			
		  	},
		  	error: function(eventData){
				if( 'timeout' === eventData['status']) {     
					 alert(wdm_csp_import_object.error_messages.request_timeout_message);         
				} 
				changeImportButtonStatus(false);
			},
		});
	});


	jQuery('.csp-download-report').click(function() {
		jQuery(this).attr('disabled',true);
		jQuery(this).css('cursor', 'progress');
	
		jQuery.ajax({
			type: "POST",
			url: wdm_csp_import_object.ajax_url,
			data: {
				'action' : 'csp_download_report_new',
				'_wp_import_nonce' : wdm_csp_import_object.nonce,
			},
			success: function(response){
				download(response,"ImportReport.csv")
				//operation to start download
				jQuery('.csp-download-report').removeAttr('disabled');
				jQuery('.csp-download-report').css('cursor', 'pointer');
			}
		});
	 });

});

/**
 * Function to disable & enable the button during and after the upload operation when pressed.
 *  
 * @param {bool} inProgress defines if the button is pressed 
 */
function changeImportButtonStatus(inProgress = true) {
	if (inProgress) {
		jQuery('#csp-start-import').attr('disabled', true).css('cursor','progress');
		jQuery('#csp-start-import').attr('title', wdm_csp_import_object.upload_in_progress_text);
	} else {
		jQuery('#csp-start-import').removeAttr('disabled').css('cursor','pointer');
		jQuery('#csp-start-import').attr('title', wdm_csp_import_object.import_button_warning);
	}
  }

  /**
   * This function can be used to update the contents of the toast notification on the import page
   * and to display the toast notification.
   * 
   * @param {string} header Header of the toast notification
   * @param {string} type type of the toast notification update|warning|error
   * @param {string} message Message to be displayed in the toast notification.
   */
  function showToastMessage(header, type, message) {
		let toastElement = jQuery('#csp-import-live-toast');
		toastElement.find('.csp-import-toast-header > strong').text(header);
		toastElement.find('.csp-import-toast-header > small').text(type);
		toastElement.find('.csp-import-toast-body').text(message);
		toastElement.toast('show');
  }


/**
* This function can be used to update the contents(neumeric) on the
* html span element to animate to the number specified.
* 
* @param {object} element jQuery span element
* @param {int} toNumber A number upto which we want to animate numbers on the field given.
*/
function animateCountUpdate(element, toNumber) {
    element.prop('Counter',element.attr('current_val')).animate({
        Counter: toNumber
    }, {
        duration: 400,
        easing: 'swing',
        step: function (now) {
            element.text(Math.ceil(now));
        }
    }); 
}

/**
 * This function is called with the uploaded file details as the parameter
 * based on the file type & headers this calls the method to split the larger files into the batcher.
 * triggers the batch import method after reciving the response.
 * @param {*} importFile array containing the details about the import file. 
 */
function initiateImportProcess(importFile) {
	// let filesInAQueue   = importQueue.length;
	let importCompleted = 0;
	// animateCountUpdate(jQuery('#total-files-in-a-queue'), filesInAQueue);
	showToastMessage(wdm_csp_import_object.notice_header, 'update',  wdm_csp_import_object.preparing_file_for_import);
	
	// importQueue.forEach(function(e,i){
	// 	if ('complete'==e.status) {
	// 		importCompleted = importCompleted + 1;
	// 	} else {
	// 		currentFile = e;
	// 	}
	// });

	if (importFile.success) {
		jQuery.ajax({
			type: 'POST',
			url: wdm_csp_import_object.ajax_url,
			data: {
				action: 'csp_start_import_process',
				file_details : importFile.data,
				import_nonce: wdm_csp_import_object.nonce
			},
			success: function (response) {
				cspTotalRecords  = response.total_records;
				cspRulessProcessed = 0;
				cspInsertCount	 = 0;
				cspSkipCount 	 = 0;
				cspUpdateCount	 = 0;
				cspImportBatches = response.batch_details;
				setResetProgressStatusView(0, cspTotalRecords, cspImportBatches[0]['ruleType']);
				startBatchImport();
			}
		});
	}
}

function startBatchImport() {
	if (cspImportBatches.length>0) {
		if(empty(wdm_csp_import_object.no_of_simultaneous_batches)) {
			wdm_csp_import_object.no_of_simultaneous_batches=2;
		}

		for (let i = 0; i < wdm_csp_import_object.no_of_simultaneous_batches; i++) {
			if(!empty(cspImportBatches[i])) {
				cspImportBatch(cspImportBatches[i]);
				cspBatchesProcesed++;
			}
		}

	}
}

function cspImportBatch(batchData) {
	// AJAX request
	jQuery.ajax({
		url: wdm_csp_import_object.ajax_url,
		type: 'post',
		data: {
			action:'csp_start_batch_import',
			batchDetails:batchData,
			import_nonce: wdm_csp_import_object.nonce
		},
		timeout: 180000, // sets timeout to 3 minute
		success: function(response){
			if (response.success) {
				cspUpdateImportCounters(response.data.status.recordsProcessed, response.data.status.recordsInserted, response.data.status.recordsUpdated, response.data.status.recordsSkipped);
				cspImportNextBranch();
			}
		  },
		  error: function(eventData){
			if( 'timeout' === eventData['status']) {     
				showToastMessage(wdm_csp_import_object.notice_header, 'error',  wdm_csp_import_object.error_messages.request_timeout_message);         
			} 
			
		},
	});
}


function cspUpdateImportCounters(recordsProcessed, recordsInserted, recordsUpdated, recordsSkipped) {
	cspRulessProcessed = cspRulessProcessed + recordsProcessed;
	cspInsertCount     = cspInsertCount + recordsInserted;
	cspUpdateCount     = cspUpdateCount + recordsUpdated;
	cspSkipCount       = cspSkipCount + recordsSkipped;
	percentProgress    = parseInt((cspRulessProcessed/cspTotalRecords)*100);
	percentProgress    = percentProgress>100?100:percentProgress;

	animateCountUpdate(jQuery('#total-processed'), cspRulessProcessed);
	animateCountUpdate(jQuery('#csp-insert-count'), cspInsertCount);
	animateCountUpdate(jQuery('#csp-update-count'), cspUpdateCount);
	animateCountUpdate(jQuery('#csp-skip-count'), cspSkipCount);

	jQuery('#percent-progress').text(percentProgress+'%');
    jQuery('#import-progress-bar').css('width',percentProgress+'%');
    jQuery('#import-progress-bar').attr('aria-valuenow',percentProgress);

	if (cspRulessProcessed>=cspTotalRecords) {
		jQuery('#import-progress-bar').removeClass('progress-bar-striped').removeClass('active').removeClass('progress-bar-animated');
		jQuery('button.csp-download-report').removeAttr('disabled');
	}
}

/**
 * This function checks if theres the batch remaining to process based on the
 * cspBatchesProcessed counter.
 * 
 */
function cspImportNextBranch() {
	if (0!=cspBatchesProcesed && cspBatchesProcesed<cspImportBatches.length && !empty(cspImportBatches[cspBatchesProcesed])) {
		cspImportBatch(cspImportBatches[cspBatchesProcesed]);
		cspBatchesProcesed++;
	}
}


function setResetProgressStatusView(progress=0, totalRecords = '', fileType = '' ) {
	jQuery('div.import-progress').show(500);
	fileType = !empty(wdm_csp_import_object.import_file_types[fileType])?wdm_csp_import_object.import_file_types[fileType]:'';
	jQuery('#current-import-file-type').text(fileType);
	animateCountUpdate(jQuery('#total-records'), totalRecords);
	animateCountUpdate(jQuery('#total-processed'), 0);
	animateCountUpdate(jQuery('#csp-insert-count'), 0);
	animateCountUpdate(jQuery('#csp-update-count'), 0);
	animateCountUpdate(jQuery('#csp-skip-count'), 0);
	jQuery('div.loading-section').hide();
}


 function download(dataurl, filename) {
	var a = document.createElement("a");
	a.href = dataurl;
	a.setAttribute("download", filename);
	var event = document.createEvent("MouseEvents");
	event.initMouseEvent(
		"click", true, false, window, 0, 0, 0, 0, 0
		, false, false, false, false, 0, null
		);
	a.dispatchEvent(event);
	return false;
  }