/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery('document').ready(function (jQuery) {
    var export_using_id = "product id";

    //Export tab for CSP.
    jQuery('#wdm_message').hide();
    jQuery('#bulk_export_message').hide();
    jQuery(".wdm_export_form, .wdm_bulk_export_form").submit(function () {
        return false;
    });

    jQuery( 'form.wdm_export_form' ).delegate( "li > label > input.wdm_csp_export_using", 'change', function () {
        export_using_id = jQuery(this).val();
    });

    //Download csv file button is clicked.
    jQuery('#export').click(function () {
        jQuery('#wdm_message').hide();
        jQuery(this).val(wdm_csp_export_ajax.preparing_csv_message).attr('disabled', true).css('cursor','progress');
        //get the option of rule-type
        var val = jQuery('#dd_show_export_options').val();
        var export_type = jQuery('input.wdm_csp_export_using:checked').val();
        jQuery.ajax({
            type: 'POST',
            url: wdm_csp_export_ajax.ajaxurl, //'http://csp.mirealux.com/wp-admin/admin-ajax.php', //ajaxurl,
            data: {
                action: 'create_csv',
                export_type: export_type,
                option_val: val,
                _wpnonce : wdm_csp_export_ajax.export_nonce
            },
            "timeout": 60000,
            error: function(jqXHR, textStatus, errorThrown) {
                if(textStatus==="timeout") {
                   alert(wdm_csp_export_ajax.timeout_message);
                   jQuery('#export').val(wdm_csp_export_ajax.export_button_text).removeAttr('disabled', false).css('cursor','pointer');
                } 
            },
            success: function (response) {//response is value returned from php
                jQuery('#export').val(wdm_csp_export_ajax.export_button_text).removeAttr('disabled', false).css('cursor','pointer');
                response = response.trim();
                if (response.search(".csv") === -1) {
                    jQuery('#wdm_message').addClass('error');
                    if( val === 'User' || val ==='user') {
                        jQuery('.wdm_message_p').text(wdm_csp_export_ajax.please_Assign_valid_user_file_msg);
                    } else if( val === 'Role' || val ==='role') {
                        jQuery('.wdm_message_p').text(wdm_csp_export_ajax.please_Assign_valid_role_file_msg);
                    } else if( val === 'Group' || val ==='group' ) {
                        jQuery('.wdm_message_p').text(wdm_csp_export_ajax.please_Assign_valid_group_file_msg);
                    }
                    jQuery('#wdm_message').show();
                }
                else {
                    var link = document.createElement("a");
                    location.href = encodeURI(response);//'http://csp.mirealux.com/wp-content/uploads/role.csv';  
                }
            }
        });
    });

    // Show SKU export related message when SKU is selected.
    jQuery("input.wdm_csp_export_using").on("change", function(){
        var importUsing = jQuery("input[name='wdm_csp_export_using']:checked").val() == 'sku' ? jQuery("input[name='wdm_csp_export_using']:checked").val() : 'product_id';
        
    });


    //Export the bulk discount rules created for the categories & the global discounts
    jQuery('#export_bulk').click(function () {
        jQuery('#bulk_export_message').hide();
        let val             = jQuery('#dd_show_bulk_export_options').val();
        let exportRulesFor  = jQuery('input.wdm_csp_bulk_export_for:checked').val();
        jQuery(this).val(wdm_csp_export_ajax.preparing_csv_message).attr('disabled', true).css('cursor','progress');

        jQuery.ajax({
            type: 'POST',
            url: wdm_csp_export_ajax.ajaxurl,
            data: {
                action: 'create_csv_bulk',
                export_rules_for: exportRulesFor,
                option_val: val,
                _wpnonce : wdm_csp_export_ajax.export_nonce
            },
            "timeout": 60000,
            error: function(jqXHR, textStatus, errorThrown) {
                if(textStatus==="timeout") {
                   alert(wdm_csp_export_ajax.timeout_message);
                   jQuery('#export_bulk').val(wdm_csp_export_ajax.export_button_text).removeAttr('disabled', false).css('cursor','pointer');
                } 
            }, 
            success: function (response) {
                jQuery('#export_bulk').val(wdm_csp_export_ajax.export_button_text).removeAttr('disabled', false).css('cursor','pointer');
                
                response = response.trim();
                if (response.search(".csv") === -1) {
                    jQuery('#bulk_export_message').addClass('error');
                    if( val === 'User' || val ==='user') {
                        jQuery('.wdm_message_bulk').text(wdm_csp_export_ajax.please_Assign_valid_user_file_msg);
                    } else if( val === 'Role' || val ==='role') {
                        jQuery('.wdm_message_bulk').text(wdm_csp_export_ajax.please_Assign_valid_role_file_msg);
                    } else if( val === 'Group' || val ==='group' ) {
                        jQuery('.wdm_message_bulk').text(wdm_csp_export_ajax.please_Assign_valid_group_file_msg);
                    }
                    jQuery('#bulk_export_message').show();
                }
                else {
                    var link = document.createElement("a");
                    location.href = encodeURI(response); 
                }
            }
        });
    });



    //Download csv file button is clicked.
    jQuery('#get-product-list').click(function () {
        jQuery(this).val(wdm_csp_export_ajax.preparing_csv_message).attr('disabled', true).css('cursor','progress');
        
        jQuery.ajax({
            type: 'POST',
            url: wdm_csp_export_ajax.ajaxurl, //'http://csp.mirealux.com/wp-admin/admin-ajax.php', //ajaxurl,
            data: {
                action: 'csp_get_product_list',
                _wpnonce : wdm_csp_export_ajax.export_nonce
            },
            "timeout": 60000,
            error: function(jqXHR, textStatus, errorThrown) {
                if(textStatus==="timeout") {
                   alert(wdm_csp_export_ajax.timeout_message);
                   jQuery('#get-product-list').val(wdm_csp_export_ajax.get_product_list_button_text).removeAttr('disabled', false).css('cursor','pointer');
                } 
            },
            success: function (response) {
                jQuery('#get-product-list').val(wdm_csp_export_ajax.get_product_list_button_text).removeAttr('disabled', false).css('cursor','pointer');
                response = response.trim();
                if (response.search(".csv") === -1) {
                    jQuery('#wdm_message').addClass('error');
                    jQuery('.wdm_message_p').text(wdm_csp_export_ajax.failed_getting_product_list);
                    jQuery('#wdm_message').show();
                }
                else {
                    var link = document.createElement("a");
                    location.href = encodeURI(response); 
                }
            }
        });
    });

    //Download rule backup archive
    jQuery('#btn-csv-export-all').click(function() {
        jQuery(this).attr('disabled', true).css('cursor','progress');
        jQuery('#wdm_all_export_message').removeClass('error');
        jQuery('#wdm_all_export_message > p').text('');
        jQuery('#wdm_all_export_message').hide();

        jQuery.ajax({
            type: 'POST',
            url: wdm_csp_export_ajax.ajaxurl,
            data: {
                action: 'csp_get_all_rules_backup',
                _wpnonce : wdm_csp_export_ajax.export_nonce
            },
            "timeout": 60000,
            error: function(jqXHR, textStatus, errorThrown) {
                if(textStatus==="timeout") {
                   alert(wdm_csp_export_ajax.timeout_message);
                   jQuery('#btn-csv-export-all').removeAttr('disabled', false).css('cursor','pointer');
                } 
            },
            success: function (response) {
                jQuery('#btn-csv-export-all').removeAttr('disabled', false).css('cursor','pointer');
                response = response.trim();
                if (response.search(".zip") === -1) {
                    jQuery('#wdm_all_export_message').addClass('error');
                    jQuery('#wdm_all_export_message > p').text(response);
                    jQuery('#wdm_all_export_message').show();
                }
                else {
                    var link = document.createElement("a");
                    location.href = encodeURI(response); 
                }
            }
        });
    });
});
