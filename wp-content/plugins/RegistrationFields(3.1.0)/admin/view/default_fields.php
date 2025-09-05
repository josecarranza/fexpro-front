<?php
require_once EORF_PLUGIN_DIR . 'admin/class-eo-registration-fields-admin.php';
$manage_fields = new EO_Registration_Fields_Admin();

$billing_fields = $manage_fields->get_reg_de_fields();

?>
<div class="wrap">
	<h2><?php _e('Enable Default Registration Fields','eorf'); ?></h2>

	

	<div class="div.widget-liquid-left">
		<div class="form-full">
			<h3><?php _e('Default Fields', 'eorf'); ?></h3>
			<div id="bfields">
            	<form method="post" action="" id="savefields" accept-charset="utf-8">
            		<ul id="sortable" class="sortable">
        				<?php foreach ($billing_fields as $billing_field) { ?>
					  	
					  <li id="<?php echo $billing_field->field_id; ?>" class="ui-state-default widget">
					  	<div id="bwt<?php echo $billing_field->field_id; ?>" class="widget-top">
					  		<div class="widget-title-action">
								<a href="#available-widgets" class="widget-action"></a>
							</div>
					  		<div class="widget-title ui-sortable-handle"><h4><?php echo $billing_field->field_label; ?><span class="in-widget-title"></span><span style="float:right; text-transform: capitalize;"><?php echo $billing_field->field_status; ?></span></h4></div>
					  	</div>
					  	<div id="bw<?php echo $billing_field->field_id; ?>" class="widget-inside win">

					  		

					  		<p><label for="label"><?php _e('Label:', 'eorf') ?></label>
					  		<input type="text" value="<?php echo __($billing_field->field_label, "eorf"); ?>" name="fieldlabel[]" class="widefat"></p>


					  		
					  		<p><label for="required"><?php _e('Required:', 'eorf') ?></label>
					  		<input <?php checked($billing_field->is_required,1); ?> type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>
					  		


					  		<p><label for="width"><?php _e('Field Width:', 'eorf') ?></label> 
					  			<select name="fieldwidth[]" class="widefat">
					  				<option <?php selected($billing_field->width,'full'); ?> value="full"><?php _e('Full Width', 'eorf') ?></option>
					  				<option <?php selected($billing_field->width,'half'); ?> value="half"><?php _e('Half Width', 'eorf') ?></option>
					  			</select>
					  		</p>

					  		
					  		<p><label for="width"><?php _e('Status:', 'eorf') ?></label> 
					  			<select name="fieldstatus[]" class="widefat">
					  				<option <?php selected($billing_field->field_status,'disabled'); ?> value="disabled"><?php _e('Disabled', 'eorf') ?></option>
					  				<option <?php selected($billing_field->field_status,'enabled'); ?> value="enabled"><?php _e('Enabled', 'eorf') ?></option>
					  			</select>
					  		</p>
					  		

					  		<p><label for="fieldmessage"><?php _e('Field Message:', 'eorf') ?></label>
					  			<input type="text" value="<?php echo __($billing_field->field_message, 'eorf'); ?>" name="fieldmessage[]" class="widefat">
					  		</p>			  		


					  		<p>
					  			
								<a onClick="closeDiv('<?php echo $billing_field->field_id; ?>')" class="widget-control-close" href="javascript:void(0)">Close</a>
					  		</p>

					  		<input type="hidden" value="<?php echo $billing_field->field_id; ?>" name="fieldids[]" class="widefat"></p>
					  		

					  	</div>
					  </li>
					  <?php } ?>
        			</ul>
            	</form>
            </div>	
		</div>

		


	</div>

	<div class="savebt">
		<input type="button" onClick="de_savedata()" value="<?php _e('Save Changes', 'eorf') ?>" class="button button-primary widget-control-save right" id="widget-archives-2-savewidget" name="savewidget"><span class="spinner"></span>
	</div>
	
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {

	$( "#sortable" ).sortable({ revert: true, update: function( event, ui ) {

			var ajaxurl = "<?php echo admin_url( 'admin-ajax.php'); ?>";
			var order = $(this).sortable('toArray');
			jQuery.ajax({
			type: 'POST',   // Adding Post method
			url: ajaxurl, // Including ajax file
				data: {"action": "de_update_sortorder","fieldids":order}, // Sending data dname to post_word_count function.
				success: function(data){ 
				}
			});                                                            

		}  

	});

});


jQuery(document).ready(function($) { 
    	
	<?php foreach ($billing_fields as $billing_field) { ?>
		jQuery('body').on('click','#bwt<?php echo filter_var($billing_field->field_id); ?>' ,function(){
			var x = document.getElementById('bw<?php echo filter_var($billing_field->field_id); ?>');
			if (x.style.display === "" || x.style.display=='none') {
				x.style.display = "block";
			} else if(x.style.display==='block') {
				x.style.display = "none";
			} 
		});
	<?php } ?>
});

function closeDiv(field_id) { 

	jQuery('#'+field_id).removeClass('ui-state-default widget open').addClass('ui-state-default widget');
    jQuery("#bw"+field_id).slideUp('slow');
}

function de_savedata() { 
	jQuery('#savefields').find(':checkbox:not(:checked)').attr('value', '0').prop('checked', true);
	var data2 = jQuery('#savefields').serialize();
	var ajaxurl = '<?php echo admin_url( 'admin-ajax.php'); ?>';

	jQuery.ajax({
	    type: 'POST',
	    url: ajaxurl,
	    data: data2 + '&action=de_save_all_data',
	    success: function() {
	        window.location.reload(true);

	    }
	});
}

</script>