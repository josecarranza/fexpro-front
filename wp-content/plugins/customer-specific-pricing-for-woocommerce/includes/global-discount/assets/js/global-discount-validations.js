
jQuery( document ).ready(function() {
    var changesMade = false;
    /**If the feature is enabled show the rule section to edit the rules */
    if (jQuery('#csp-gd-feature-switch').prop('checked')) {
        jQuery('.row.csp-gd-main-div').css('display','block');
    }

    /**
     * Expand & shrink the notes section
     */
    jQuery('div.gd-notes-title').click(function(){
        $content = jQuery(this).next('.gd-notes-content');
        $content.addClass('active');
        if($content.css('max-height')=='0px') {
            $content.css('max-height','min-content');
        } else {
            $content.css('max-height','0px');
        }
    });

    /**
     * When Feature gets enabled or disabled using a switch.
     * ajax call to save the feature setting and enable/ disable the display of the form
     */
    jQuery('#csp-gd-feature-switch').change(function() {
        let featureStatus = jQuery('#csp-gd-feature-switch').prop('checked')?"enable":"disable";
        let data          = {'action': 'csp_set_gd_feature_status',
                                'featureStatus':featureStatus, 
                                'cd_nonce': wdm_csp_gd_object.nonce,
                            };
        
        jQuery('.loading-text').css('display','block');
        jQuery.ajax(
            {
                type: 'POST',
                url: wdm_csp_gd_object.ajax_url,
                data: data,
                error: function(eventData){
                    if('timeout' === eventData['status']) {     
                         alert(wdm_csp_gd_object.request_timeout_message);         
                    }
                },
                success: function(statusUpdated){
                    if (statusUpdated) {
                        if (jQuery('#csp-gd-feature-switch').prop('checked')) {
                            jQuery('.row.csp-gd-main-div').css('display','block');
                            jQuery('.row.csp-gd-main-div').focus();
                        } else {
                            jQuery('.row.csp-gd-main-div').css('display','none');
                        }
                        jQuery('.loading-text').css('display','none');   
                    }
                }, timeout: 30000 // sets timeout to 30 seconds
            });
    });


    /*****************************
     * Accordion Expand & shrink *
     *****************************/
    jQuery('.panel-collapse').on('show.bs.collapse', function () {
        jQuery(this).siblings('.panel-heading').addClass('active');
      });
    
    jQuery('.panel-collapse').on('hide.bs.collapse', function () {
        jQuery(this).siblings('.panel-heading').removeClass('active');
      });


    /******************************************************************
     * Adding and removing the rows on the add and remove icons click *
     ******************************************************************/
     /** Add a new rule row */
     jQuery('div.panel-body').delegate('img.add_new_user_row_image', 'click', function(){
        let $this           =  jQuery(this);
        let ruleRow         = $this.closest('.gd-rule-row');
        let parentSection   = $this.closest('.panel-body'); 
        let valid           = checkRuleValidation(ruleRow);
        if (valid) {
            let clonedRuleRow   = jQuery(ruleRow).clone();
            resetRuleRow(clonedRuleRow);
            jQuery(parentSection).append(jQuery(clonedRuleRow));
            $this.remove();   
        }
        changesMade = true;
     });

      /** Remove a rule row */
      jQuery('div.panel-body').delegate('img.remove_user_row_image', 'click', function(){
        let $this           =  jQuery(this);
        let ruleRow         = $this.closest('.gd-rule-row');
        let parentSection   = $this.closest('.panel-body');
        if (jQuery(ruleRow).find('img.add_new_user_row_image').length>0) {
            let addBtn      = jQuery(ruleRow).find('img.add_new_user_row_image').clone();
            let newLastRow  = jQuery(parentSection).find(".gd-rule-row:nth-last-child(2)");
            jQuery(newLastRow).find('span.add_remove_button:first').append(addBtn);            
        }
        if (jQuery(parentSection).find(".gd-rule-row").length>1) {
            jQuery(ruleRow).remove();   
        } else {
            resetRuleRow(ruleRow);
        }
        changesMade = true;
     });

     /**Add or remove rows on pressing an enter key */
     jQuery('div.panel-body').on('keypress', '.remove_user_row_image, .add_new_user_row_image', function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode==13){
        event.target.click();
        }
    });

    /************************
     * Saving All The Rules *
     ************************/
    jQuery('button.save-all-gd-rules').click(function(){
        jQuery(this).text(wdm_csp_gd_object.saving_text).attr('disabled', true).css('cursor','progress');
        
        let uspRules = getAllPricingRulesFor('users');
        let rspRules = getAllPricingRulesFor('roles');
        let gspRules = getAllPricingRulesFor('groups');

        if (0===uspRules.length && 0===rspRules.length && 0===gspRules.length) {
            let removeAll = confirm(wdm_csp_gd_object.error_mesages.no_valid_rules);
            if (!removeAll) {
                jQuery(this).attr('disabled', false).text(wdm_csp_gd_object.save_text).css('cursor','pointer');
                return ;   
            }
        }

        jQuery.ajax(
            {
                type: 'POST',
                url: wdm_csp_gd_object.ajax_url,
                data:  { 
                        'action'   : 'csp_save_gd_rule_data',
                        'cd_nonce' : wdm_csp_gd_object.nonce,
                        'usp_rules': uspRules,
                        'rsp_rules': rspRules,
                        'gsp_rules': gspRules,
                        },
                error: function(eventData){
                    if( 'timeout' === eventData['status']) {     
                         alert(wdm_csp_gd_object.request_timeout_message);         
                    }
                },
                success: function(response){
                        if ('Security Check'===response) {
                            alert(wdm_csp_gd_object.save_failed_message);
                            return;    
                        }
                        jQuery('button.save-all-gd-rules').text(wdm_csp_gd_object.save_text);
                        jQuery('button.save-all-gd-rules').removeAttr('disabled');
                        jQuery('button.save-all-gd-rules').css('cursor','pointer');
                        changesMade = false;
                        alert(wdm_csp_gd_object.save_success_message);
                        location.reload(true);
                    }, timeout: 60000 // sets timeout to 1 minute
            });
    });


    /**
     * Marks the changes made variable true when any changes are made in the rule.
     */
    jQuery(document).on('change', 'input, select', function (event) {
        if ('csp-gd-feature-switch'!=event.currentTarget.id) {
            changesMade = true;    
        }
    });
    
    /**
     * Prompt asking to confirm closing the window when close button
     * is clicked by the user
     */
    jQuery(window).bind('beforeunload', function(){
        if (changesMade) {
            return wdm_csp_gd_object.window_close_confirm_message;   
        }
      });


});


/**
 * This method validates if the rule in the row passed as the parameter is valid
 * in case of the invalid rule highlight the invalid field and returns false,
 * otherwise returns true.
 * 
 * @param DOMobject ruleRow -  an Jquery object containg html element 
 * @return boolean true | false
 */
function checkRuleValidation(ruleRow) {
    let typeSelected = jQuery(ruleRow).find('.gd-type-select:first').val();
    if (-1 === typeSelected) {
        jQuery(ruleRow).find('.gd-type-select:first').focus();
        return false;
    }
    let discountTypeSelected = jQuery(ruleRow).find('select[name="discount-type"]:first').val();
    if (-1===discountTypeSelected) {
        jQuery(ruleRow).find('select[name="discount-type"]:first').focus();
        return false;
    }
    let qty = jQuery(ruleRow).find('input[name="min-qty"]:first').val();
    if (1>qty) {
        jQuery(ruleRow).find('input[name="min-qty"]:first').focus();
        return false;
    }
    let value = jQuery(ruleRow).find('input[name="discount-value"]:first').val();
    if (0>value || ''===value || (2===discountTypeSelected  && (100<=value || 0>=value))) {
        jQuery(ruleRow).find('input[name="discount-value"]:first').focus();
        return false;
    }
    return true;
}

/**
 * Fetches All the user specific pricing rules on the global
 * discounts setting page and returns an associated array of
 * user specific rules defined. 
 * 
 * @param {string} entity - Rule Type users|roles|groups
 * @returns {array} - an array of associative arrays containing the csp Rules
 */
function getAllPricingRulesFor(entity) {
    let rules = Array();
    switch (entity) {
            case 'users':
                rules = jQuery('#users-gd-rule-panel').find('.panel-body > .gd-rule-row');       
                break;
            case 'roles':
                rules = jQuery('#roles-gd-rule-panel').find('.panel-body > .gd-rule-row');       
                break;
            case 'groups':
                rules = jQuery('#groups-gd-rule-panel').find('.panel-body > .gd-rule-row');       
                break;
            }
    let validRules = Array();
    if (!empty(rules)) {
        jQuery.each(rules, function(i, aRule) {
           let ruleValues = getValuesFromRuleRow(aRule);
           if (isValidCSPRule(ruleValues)) {
            validRules.push(ruleValues);
           }
        });
    }
    return validRules;
}

/**
 * Returns an array of rule entity-value pairs form the
 * rule row passed as a prameter.
 * 
 * @param {DOMobject} aRuleRow  
 * @returns {array} - associative array containing the CSP rule
 */
function getValuesFromRuleRow(aRuleRow) {
    $aRuleRow       = jQuery(aRuleRow);
    
    let ruleType       = $aRuleRow.find('.gd-type-select:first').val();
    let discountType   = $aRuleRow.find('select[name="discount-type"]:first').val();
    let minQty         = $aRuleRow.find('input[name="min-qty"]:first').val();
    let value          = $aRuleRow.find('input[name="discount-value"]:first').val();
    return {'rule_beneficiary':ruleType, 'discount_type':discountType, 'min_qty': minQty, 'value':value};
}

/**
 * Checks if given CSP rule is a valid or invalid
 * returns true if valid otherwise false
 * 
 * @param {array} ruleValues
 * @returns {boolean} 
 */
function isValidCSPRule(ruleValues) {
    let isValid = true;
    if ('-1'===ruleValues['rule_beneficiary'] || ''===ruleValues['rule_beneficiary']) {
        isValid = false;
    }
    if ('-1'===ruleValues['discount_type'] || ''===ruleValues['discount_type']) {
        isValid = false;
    }    
    if (0>=ruleValues['min_qty']) {
        isValid = false;
    }
    if (0>ruleValues['value'] || ''===ruleValues['value'] || ('2'===ruleValues['discount_type'] && (100<ruleValues['value'] || 0>=ruleValues['value']))) {
        isValid = false;
    }
    return isValid;
}

/**
 * Resets all the input elements from the html row.
 * 
 * @param {object} ruleRow 
 */
function resetRuleRow(ruleRow) {
    jQuery(ruleRow).find('select').prop('selectedIndex',0);
    jQuery(ruleRow).find('input').val('');
}