<?php
require_once EORF_PLUGIN_DIR . 'admin/class-eo-registration-fields-admin.php';
$manage_fields = new EO_Registration_Fields_Admin();

$billing_fields = $manage_fields->get_reg_fields();

?>
<div class="wrap">
	<h2><?php _e('Registration Fields','eorf'); ?></h2>

	<h3><?php _e('Registration Form','eorf'); ?></h3>

	<div class="div.widget-liquid-left">
		<div class="form-left">
			<h3><?php _e('Form Fields', 'eorf'); ?></h3>
			<div class="shop-container" id="bdrag">
				<ul>
				
					<!--Text Field-->
					<li id="bf" class="bf ui-state-default widget draggable">
					  	<div id="bwt" class="widget-top">
					  		<div class="widget-title-action">
								<a href="#available-widgets" class="widget-action"></a>
							</div>
					  		<div class="widget-title ui-sortable-handle"><h4><?php _e('Text', 'eorf'); ?><span class="in-widget-title"></span></h4></div>
					  		<input type="hidden" name="fieldtype" value="text" id="fieldtype" />
					  		<input type="hidden" name="type" value="registration" id="type" />
					  		<input type="hidden" name="label" value="Text" id="label" />
					  		<input type="hidden" name="name" value="registration_text" id="name" />
					  		<input type="hidden" name="mode" value="registration_additional" id="mode" />
					  		<input type="hidden" name="showif" value="" id="showif" />
					  		<input type="hidden" name="cfield" value="" id="cfield" />
					  		<input type="hidden" name="ccondition" value="" id="ccondition" />
					  		<input type="hidden" name="ccondition_value" value="" id="ccondition_value" />
					  	</div>
					  	<div id="bw" class="widget-inside win">

					  		<p><label for="label"><?php _e('Label:', 'eorf'); ?></label>
					  		<input type="text" value="" name="fieldlabel[]" class="widefat"></p>

					  		<p><label for="required"><?php _e('Required:', 'eorf'); ?></label>
					  		<input type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>

					  		<p><label for="hide"><?php _e('Hide:', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldhide[]" class="widefat"></p>

					  		<p><label for="readonly"><?php _e('Read Only(Do not allow customers to edit in myaccount):', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldreadonly[]" class="widefat"></p>

					  		<p><label for="placeholder"><?php _e('Placeholder:', 'eorf'); ?></label>
					  		<input type="text" value="" name="fieldplaceholder[]" class="widefat"></p>

					  		<p><label for="width"><?php _e('Field Width:', 'eorf'); ?></label> 
					  			<select name="fieldwidth[]" class="widefat">
					  				<option  value="full"><?php _e('Full Width', 'eorf'); ?></option>
					  				<option  value="half"><?php _e('Half Width','eorf'); ?></option>
					  			</select>
					  		</p>

					  		<p><label for="fieldmessage"><?php _e('Field Message:','eorf'); ?></label>
					  		<input type="text" value="" name="fieldmessage[]" class="widefat"></p>

					  		<p>
					  			<label for="clogic"><?php _e('Conditional Logic:','eorf'); ?></label>
					  			<div class="showf">
					  				<select name="showif[]">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="Show"><?php _e('Show','eorf'); ?></option>
					  					<option value="Hide"><?php _e('Hide','eorf'); ?></option>
					  				</select>
					  			</div>
					  			<div class="showf_text"><?php _e('if value of','eorf'); ?></div>
					  			<div class="showf" id="cl">
					  				<select name="cfield[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					
					  				</select>
					  			</div>
					  			<div class="showf" id="cll">
					  				<select id="cll_select" name="ccondition[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="is not empty"><?php _e('is not empty','eorf'); ?></option>
					  					<option value="is equal to"><?php _e('is equal to','eorf'); ?></option>
					  					<option value="is not equal to"><?php _e('is not equal to','eorf'); ?></option>
					  					<option value="is checked"><?php _e('is checked','eorf'); ?></option>
					  					
					  				</select>
					  			</div>

					  			<div class="showf" id="clll">
					  				<input type="text" name="ccondition_value[]" class="clll_field" size="13">
					  			</div>
					  		</p>



					  		<p id="textapp"></p>
					  		<input type="hidden" name="fieldids[]" value="" id="fieldids" />

					  		

					  	</div>
					</li>

					<!-- Text Area Field-->
					<li id="bf" class="bf ui-state-default widget draggable">
					  	<div id="bwt" class="widget-top">
					  		<div class="widget-title-action">
								<a href="#available-widgets" class="widget-action"></a>
							</div>
					  		<div class="widget-title ui-sortable-handle"><h4><?php _e('Textarea', 'eorf') ?><span class="in-widget-title"></span></h4></div>
					  		<input type="hidden" name="fieldtype" value="textarea" id="fieldtype" />
					  		<input type="hidden" name="type" value="registration" id="type" />
					  		<input type="hidden" name="label" value="Textarea" id="label" />
					  		<input type="hidden" name="name" value="registration_textarea" id="name" />
					  		<input type="hidden" name="mode" value="registration_additional" id="mode" />
					  	</div>
					  	<div id="bw" class="widget-inside win">

					  		<p><label for="label"><?php _e('Label:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldlabel[]" class="widefat"></p>

					  		<p><label for="required"><?php _e('Required:', 'eorf') ?></label>
					  		<input type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>

					  		<p><label for="hide"><?php _e('Hide:', 'eorf') ?></label>
					  		<input  type="checkbox" value="1" name="fieldhide[]" class="widefat"></p>

					  		<p><label for="readonly"><?php _e('Read Only(Do not allow customers to edit in myaccount):', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldreadonly[]" class="widefat"></p>

					  		<p><label for="placeholder"><?php _e('Placeholder:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldplaceholder[]" class="widefat"></p>

					  		<p><label for="width"><?php _e('Field Width:', 'eorf') ?></label> 
					  			<select name="fieldwidth[]" class="widefat">
					  				<option  value="full"><?php _e('Full Width', 'eorf') ?></option>
					  				<option  value="half"><?php _e('Half Width', 'eorf') ?></option>
					  			</select>
					  		</p>

					  		<p><label for="fieldmessage"><?php _e('Field Message:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldmessage[]" class="widefat"></p>


					  		<p>
					  			<label for="clogic"><?php _e('Conditional Logic:','eorf'); ?></label>
					  			<div class="showf">
					  				<select name="showif[]">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="Show"><?php _e('Show','eorf'); ?></option>
					  					<option value="Hide"><?php _e('Hide','eorf'); ?></option>
					  				</select>
					  			</div>
					  			<div class="showf_text"><?php _e('if value of','eorf'); ?></div>
					  			<div class="showf" id="cl">
					  				<select name="cfield[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					
					  				</select>
					  			</div>
					  			<div class="showf" id="cll">
					  				<select id="cll_select" name="ccondition[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="is not empty"><?php _e('is not empty','eorf'); ?></option>
					  					<option value="is equal to"><?php _e('is equal to','eorf'); ?></option>
					  					<option value="is not equal to"><?php _e('is not equal to','eorf'); ?></option>
					  					<option value="is checked"><?php _e('is checked','eorf'); ?></option>
					  					
					  				</select>
					  			</div>

					  			<div class="showf" id="clll">
					  				<input type="text" name="ccondition_value[]" class="clll_field" size="13">
					  			</div>
					  		</p>

					  		<p id="textapp"></p>
					  		<input type="hidden" name="fieldids[]" value="" id="fieldids" />

					  		

					  	</div>
					</li>

					<!-- Select Box-->
					<li id="bf" class="bf ui-state-default widget draggable">
					  	<div id="bwt" class="widget-top">
					  		<div class="widget-title-action">
								<a href="#available-widgets" class="widget-action"></a>
							</div>
					  		<div class="widget-title ui-sortable-handle"><h4><?php _e('Select Box', 'eorf') ?><span class="in-widget-title"></span></h4></div>
					  		<input type="hidden" name="fieldtype" value="select" id="fieldtype" />
					  		<input type="hidden" name="type" value="registration" id="type" />
					  		<input type="hidden" name="label" value="Select Box" id="label" />
					  		<input type="hidden" name="name" value="registration_select" id="name" />
					  		<input type="hidden" name="mode" value="registration_additional" id="mode" />
					  	</div>
					  	<div id="bw" class="widget-inside win">

					  		<p><label for="label"><?php _e('Label:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldlabel[]" class="widefat"></p>

					  		<p><label for="required"><?php _e('Required:', 'eorf') ?></label>
					  		<input type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>

					  		<p><label for="hide"><?php _e('Hide:', 'eorf') ?></label>
					  		<input  type="checkbox" value="1" name="fieldhide[]" class="widefat"></p>

					  		<p><label for="readonly"><?php _e('Read Only(Do not allow customers to edit in myaccount):', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldreadonly[]" class="widefat"></p>


					  		<p>
					  		<label for="options"><?php _e('Options:', 'eorf') ?></label>

					  		<div class="field_wrapper">
								<div>
							    	<input class="opval" placeholder="<?php _e('Option Value', 'eorf') ?>" type="text" name="option_value[]" value=""/>
							    	<input class="opval" placeholder="<?php _e('Option Text', 'eorf') ?>" type="text" name="option_text[]" value=""/>
							    	<input id="option_field_ids" class="opval" placeholder="" type="hidden" name="option_field_ids[]" value=""/>
							        <a href="javascript:void(0);"  title="<?php _e('Add Option', 'eorf') ?>">
							        <img onClick="" class="add_button" src="<?php echo EORF_URL; ?>images/add-icon.png"/></a>
							    </div>
							</div>

					  		
					  		</p>

					  		<p><label for="width"><?php _e('Field Width:', 'eorf') ?></label> 
					  			<select name="fieldwidth[]" class="widefat">
					  				<option  value="full"><?php _e('Full Width', 'eorf') ?></option>
					  				<option  value="half"><?php _e('Half Width', 'eorf') ?></option>
					  			</select>
					  		</p>

					  		<p><label for="fieldmessage"><?php _e('Field Message:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldmessage[]" class="widefat"></p>

					  		<p>
					  			<label for="clogic"><?php _e('Conditional Logic:','eorf'); ?></label>
					  			<div class="showf">
					  				<select name="showif[]">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="Show"><?php _e('Show','eorf'); ?></option>
					  					<option value="Hide"><?php _e('Hide','eorf'); ?></option>
					  				</select>
					  			</div>
					  			<div class="showf_text"><?php _e('if value of','eorf'); ?></div>
					  			<div class="showf" id="cl">
					  				<select name="cfield[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					
					  				</select>
					  			</div>
					  			<div class="showf" id="cll">
					  				<select id="cll_select" name="ccondition[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="is not empty"><?php _e('is not empty','eorf'); ?></option>
					  					<option value="is equal to"><?php _e('is equal to','eorf'); ?></option>
					  					<option value="is not equal to"><?php _e('is not equal to','eorf'); ?></option>
					  					<option value="is checked"><?php _e('is checked','eorf'); ?></option>
					  					
					  				</select>
					  			</div>

					  			<div class="showf" id="clll">
					  				<input type="text" name="ccondition_value[]" class="clll_field" size="13">
					  			</div>
					  		</p>

					  		<p id="textapp"></p>
					  		<input type="hidden" value="noplaceholder" name="fieldplaceholder[]" class="widefat"></p>
					  		<input type="hidden" name="fieldids[]" value="" id="fieldids" />

					  		

					  	</div>
					 </li>

					 <!-- Multi Select Box-->
					 <li id="bf" class="bf ui-state-default widget draggable">
					  	<div id="bwt" class="widget-top">
					  		<div class="widget-title-action">
								<a href="#available-widgets" class="widget-action"></a>
							</div>
					  		<div class="widget-title ui-sortable-handle"><h4><?php _e('Multi Select Box', 'eorf') ?><span class="in-widget-title"></span></h4></div>
					  		<input type="hidden" name="fieldtype" value="multiselect" id="fieldtype" />
					  		<input type="hidden" name="type" value="registration" id="type" />
					  		<input type="hidden" name="label" value="Multi Select Box" id="label" />
					  		<input type="hidden" name="name" value="registration_multi_select" id="name" />
					  		<input type="hidden" name="mode" value="registration_additional" id="mode" />
					  	</div>
					  	<div id="bw" class="widget-inside win">

					  		<p><label for="label"><?php _e('Label:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldlabel[]" class="widefat"></p>

					  		<p><label for="required"><?php _e('Required:', 'eorf') ?></label>
					  		<input type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>

					  		<p><label for="hide"><?php _e('Hide:', 'eorf') ?></label>
					  		<input  type="checkbox" value="1" name="fieldhide[]" class="widefat"></p>

					  		<p><label for="readonly"><?php _e('Read Only(Do not allow customers to edit in myaccount):', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldreadonly[]" class="widefat"></p>

					  		<p>
					  		<label for="options"><?php _e('Options:', 'eorf') ?></label>
					  		<div class="field_wrapper">
								<div>
							    	<input class="opval" placeholder="<?php _e('Option Value', 'eorf') ?>" type="text" name="option_value[]" value=""/>
							    	<input class="opval" placeholder="<?php _e('Option Text', 'eorf') ?>" type="text" name="option_text[]" value=""/>
							    	<input id="option_field_ids" class="opval" placeholder="" type="hidden" name="option_field_ids[]" value=""/>
							        <a href="javascript:void(0);"  title="<?php _e('Add Option', 'eorf') ?>">
							        <img onClick="" class="add_button" src="<?php echo EORF_URL; ?>images/add-icon.png"/></a>
							    </div>
							</div>
					  		</p>

					  		<p><label for="width"><?php _e('Field Width:', 'eorf') ?></label> 
					  			<select name="fieldwidth[]" class="widefat">
					  				<option  value="full"><?php _e('Full Width', 'eorf') ?></option>
					  				<option  value="half"><?php _e('Half Width', 'eorf') ?></option>
					  			</select>
					  		</p>

					  		<p><label for="fieldmessage"><?php _e('Field Message:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldmessage[]" class="widefat"></p>


					  		<p>
					  			<label for="clogic"><?php _e('Conditional Logic:','eorf'); ?></label>
					  			<div class="showf">
					  				<select name="showif[]">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="Show"><?php _e('Show','eorf'); ?></option>
					  					<option value="Hide"><?php _e('Hide','eorf'); ?></option>
					  				</select>
					  			</div>
					  			<div class="showf_text"><?php _e('if value of','eorf'); ?></div>
					  			<div class="showf" id="cl">
					  				<select name="cfield[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					
					  				</select>
					  			</div>
					  			<div class="showf" id="cll">
					  				<select id="cll_select" name="ccondition[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="is not empty"><?php _e('is not empty','eorf'); ?></option>
					  					<option value="is equal to"><?php _e('is equal to','eorf'); ?></option>
					  					<option value="is not equal to"><?php _e('is not equal to','eorf'); ?></option>
					  					<option value="is checked"><?php _e('is checked','eorf'); ?></option>
					  					
					  				</select>
					  			</div>

					  			<div class="showf" id="clll">
					  				<input type="text" name="ccondition_value[]" class="clll_field" size="13">
					  			</div>
					  		</p>

					  		<p id="textapp"></p>
					  		<input type="hidden" value="noplaceholder" name="fieldplaceholder[]" class="widefat"></p>
					  		<input type="hidden" name="fieldids[]" value="" id="fieldids" />

					  		

					  	</div>
					 </li>

					 <!-- Check Box-->
					 <li id="bf" class="bf ui-state-default widget draggable">
					  	<div id="bwt" class="widget-top">
					  		<div class="widget-title-action">
								<a href="#available-widgets" class="widget-action"></a>
							</div>
					  		<div class="widget-title ui-sortable-handle"><h4><?php _e('Checkbox', 'eorf') ?><span class="in-widget-title"></span></h4></div>
					  		<input type="hidden" name="fieldtype" value="checkbox" id="fieldtype" />
					  		<input type="hidden" name="type" value="registration" id="type" />
					  		<input type="hidden" name="label" value="Checkbox" id="label" />
					  		<input type="hidden" name="name" value="registration_checkbox" id="name" />
					  		<input type="hidden" name="mode" value="registration_additional" id="mode" />
					  	</div>
					  	<div id="bw" class="widget-inside win">

					  		<p><label for="label"><?php _e('Label:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldlabel[]" class="widefat"></p>

					  		<p><label for="required"><?php _e('Required:', 'eorf') ?></label>
					  		<input type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>

					  		<p><label for="hide"><?php _e('Hide:', 'eorf') ?></label>
					  		<input  type="checkbox" value="1" name="fieldhide[]" class="widefat"></p>

					  		<p><label for="readonly"><?php _e('Read Only(Do not allow customers to edit in myaccount):', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldreadonly[]" class="widefat"></p>


					  		<p><label for="width"><?php _e('Field Width:', 'eorf') ?></label> 
					  			<select name="fieldwidth[]" class="widefat">
					  				<option  value="full"><?php _e('Full Width', 'eorf') ?></option>
					  				<option  value="half"><?php _e('Half Width', 'eorf') ?></option>
					  			</select>
					  		</p>

					  		<p><label for="fieldmessage"><?php _e('Field Message:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldmessage[]" class="widefat"></p>


					  		<p>
					  			<label for="clogic"><?php _e('Conditional Logic:','eorf'); ?></label>
					  			<div class="showf">
					  				<select name="showif[]">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="Show"><?php _e('Show','eorf'); ?></option>
					  					<option value="Hide"><?php _e('Hide','eorf'); ?></option>
					  				</select>
					  			</div>
					  			<div class="showf_text"><?php _e('if value of','eorf'); ?></div>
					  			<div class="showf" id="cl">
					  				<select name="cfield[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					
					  				</select>
					  			</div>
					  			<div class="showf" id="cll">
					  				<select id="cll_select" name="ccondition[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="is not empty"><?php _e('is not empty','eorf'); ?></option>
					  					<option value="is equal to"><?php _e('is equal to','eorf'); ?></option>
					  					<option value="is not equal to"><?php _e('is not equal to','eorf'); ?></option>
					  					<option value="is checked"><?php _e('is checked','eorf'); ?></option>
					  					
					  				</select>
					  			</div>

					  			<div class="showf" id="clll">
					  				<input type="text" name="ccondition_value[]" class="clll_field" size="13">
					  			</div>
					  		</p>

					  		<p id="textapp"></p>
					  		<input type="hidden" value="noplaceholder" name="fieldplaceholder[]" class="widefat"></p>
					  		<input type="hidden" name="fieldids[]" value="" id="fieldids" />

					  		

					  	</div>
					</li>



					<!-- User Acceptance Check Box-->
					 <li id="bf" class="bf ui-state-default widget draggable">
					  	<div id="bwt" class="widget-top">
					  		<div class="widget-title-action">
								<a href="#available-widgets" class="widget-action"></a>
							</div>
					  		<div class="widget-title ui-sortable-handle"><h4><?php _e('Checkbox(User Acceptance)', 'eorf') ?><span class="in-widget-title"></span></h4></div>
					  		<input type="hidden" name="fieldtype" value="user_checkbox" id="fieldtype" />
					  		<input type="hidden" name="type" value="registration" id="type" />
					  		<input type="hidden" name="label" value="Checkbox User Acceptance" id="label" />
					  		<input type="hidden" name="name" value="registration_user_checkbox" id="name" />
					  		<input type="hidden" name="mode" value="registration_additional" id="mode" />
					  	</div>
					  	<div id="bw" class="widget-inside win">

					  		<p><label for="label"><?php _e('Label(for internal use only):', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldlabel[]" class="widefat"></p>

					  		<p><label for="label"><?php _e('Label(This will show on frontend. you can add html in this.):', 'eorf') ?></label>

					  		<p class="impnote"><?php _e('Note: This field is for just user acceptance like terms and conditions, this field will not be saved or sent in order or emails etc. You can add anchor tag to link with other page.', 'eorf'); ?></p>

					  		<textarea name="check_fieldlabel[]" class="widefat"></textarea></p>

					  		<p><label for="required"><?php _e('Required:', 'eorf') ?></label>
					  		<input type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>

					  		<p><label for="width"><?php _e('Field Width:', 'eorf') ?></label> 
					  			<select name="fieldwidth[]" class="widefat">
					  				<option  value="full"><?php _e('Full Width', 'eorf') ?></option>
					  				<option  value="half"><?php _e('Half Width', 'eorf') ?></option>
					  			</select>
					  		</p>

					  		<p><label for="fieldmessage"><?php _e('Field Message:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldmessage[]" class="widefat"></p>


					  		<p>
					  			<label for="clogic"><?php _e('Conditional Logic:','eorf'); ?></label>
					  			<div class="showf">
					  				<select name="showif[]">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="Show"><?php _e('Show','eorf'); ?></option>
					  					<option value="Hide"><?php _e('Hide','eorf'); ?></option>
					  				</select>
					  			</div>
					  			<div class="showf_text"><?php _e('if value of','eorf'); ?></div>
					  			<div class="showf" id="cl">
					  				<select name="cfield[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					
					  				</select>
					  			</div>
					  			<div class="showf" id="cll">
					  				<select id="cll_select" name="ccondition[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="is not empty"><?php _e('is not empty','eorf'); ?></option>
					  					<option value="is equal to"><?php _e('is equal to','eorf'); ?></option>
					  					<option value="is not equal to"><?php _e('is not equal to','eorf'); ?></option>
					  					<option value="is checked"><?php _e('is checked','eorf'); ?></option>
					  					
					  				</select>
					  			</div>

					  			<div class="showf" id="clll">
					  				<input type="text" name="ccondition_value[]" class="clll_field" size="13">
					  			</div>
					  		</p>

					  		<p id="textapp"></p>
					  		<input type="hidden" value="noplaceholder" name="fieldplaceholder[]" class="widefat"></p>
					  		<input type="hidden" name="fieldids[]" value="" id="fieldids" />

					  		

					  	</div>
					</li>




					<!-- Radio Button-->

					<li id="bf" class="bf ui-state-default widget draggable">
					  	<div id="bwt" class="widget-top">
					  		<div class="widget-title-action">
								<a href="#available-widgets" class="widget-action"></a>
							</div>
					  		<div class="widget-title ui-sortable-handle"><h4><?php _e('Radio Button', 'eorf') ?><span class="in-widget-title"></span></h4></div>
					  		<input type="hidden" name="fieldtype" value="radioselect" id="fieldtype" />
					  		<input type="hidden" name="type" value="registration" id="type" />
					  		<input type="hidden" name="label" value="Radio Button" id="label" />
					  		<input type="hidden" name="name" value="registration_radio_select" id="name" />
					  		<input type="hidden" name="mode" value="registration_additional" id="mode" />
					  	</div>
					  	<div id="bw" class="widget-inside win">

					  		<p><label for="label"><?php _e('Label:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldlabel[]" class="widefat"></p>

					  		<p><label for="required"><?php _e('Required:', 'eorf') ?></label>
					  		<input type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>

					  		<p><label for="hide"><?php _e('Hide:', 'eorf') ?></label>
					  		<input  type="checkbox" value="1" name="fieldhide[]" class="widefat"></p>

					  		<p><label for="readonly"><?php _e('Read Only(Do not allow customers to edit in myaccount):', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldreadonly[]" class="widefat"></p>

					  		<p>
						  		<label for="options"><?php _e('Options:', 'eorf') ?></label>

						  		<div class="field_wrapper">
									<div>
								    	<input class="opval" placeholder="<?php _e('Option Value', 'eorf') ?>" type="text" name="option_value[]" value=""/>
								    	<input class="opval" placeholder="<?php _e('Option Text', 'eorf') ?>" type="text" name="option_text[]" value=""/>
								    	<input id="option_field_ids" class="opval" placeholder="" type="hidden" name="option_field_ids[]" value=""/>
								        <a href="javascript:void(0);"  title="<?php _e('Add Option', 'eorf') ?>">
								        <img onClick="" class="add_button" src="<?php echo EORF_URL; ?>images/add-icon.png"/></a>
								    </div>
								</div>
					  		</p>


					  		<p><label for="width"><?php _e('Field Width:', 'eorf') ?></label> 
					  			<select name="fieldwidth[]" class="widefat">
					  				<option  value="full"><?php _e('Full Width', 'eorf') ?></option>
					  				<option  value="half"><?php _e('Half Width', 'eorf') ?></option>
					  			</select>
					  		</p>

					  		<p><label for="fieldmessage"><?php _e('Field Message:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldmessage[]" class="widefat"></p>

					  		<p>
					  			<label for="clogic"><?php _e('Conditional Logic:','eorf'); ?></label>
					  			<div class="showf">
					  				<select name="showif[]">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="Show"><?php _e('Show','eorf'); ?></option>
					  					<option value="Hide"><?php _e('Hide','eorf'); ?></option>
					  				</select>
					  			</div>
					  			<div class="showf_text"><?php _e('if value of','eorf'); ?></div>
					  			<div class="showf" id="cl">
					  				<select name="cfield[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					
					  				</select>
					  			</div>
					  			<div class="showf" id="cll">
					  				<select id="cll_select" name="ccondition[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="is not empty"><?php _e('is not empty','eorf'); ?></option>
					  					<option value="is equal to"><?php _e('is equal to','eorf'); ?></option>
					  					<option value="is not equal to"><?php _e('is not equal to','eorf'); ?></option>
					  					<option value="is checked"><?php _e('is checked','eorf'); ?></option>
					  					
					  				</select>
					  			</div>

					  			<div class="showf" id="clll">
					  				<input type="text" name="ccondition_value[]" class="clll_field" size="13">
					  			</div>
					  		</p>

					  		<p id="textapp"></p>
					  		<input type="hidden" value="noplaceholder" name="fieldplaceholder[]" class="widefat"></p>
					  		<input type="hidden" name="fieldids[]" value="" id="fieldids" />

					  		

					  	</div>
					 </li>

					 <!-- Date Picker-->
					 <li id="bf" class="bf ui-state-default widget draggable">
					  	<div id="bwt" class="widget-top">
					  		<div class="widget-title-action">
								<a href="#available-widgets" class="widget-action"></a>
							</div>
					  		<div class="widget-title ui-sortable-handle"><h4><?php _e('Date Picker', 'eorf') ?><span class="in-widget-title"></span></h4></div>
					  		<input type="hidden" name="fieldtype" value="datepicker" id="fieldtype" />
					  		<input type="hidden" name="type" value="registration" id="type" />
					  		<input type="hidden" name="label" value="Date Picker" id="label" />
					  		<input type="hidden" name="name" value="registration_date_picker" id="name" />
					  		<input type="hidden" name="mode" value="registration_additional" id="mode" />
					  	</div>
					  	<div id="bw" class="widget-inside win">

					  		<p><label for="label"><?php _e('Label:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldlabel[]" class="widefat"></p>

					  		<p><label for="required"><?php _e('Required:', 'eorf') ?></label>
					  		<input type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>

					  		<p><label for="hide"><?php _e('Hide:', 'eorf') ?></label>
					  		<input  type="checkbox" value="1" name="fieldhide[]" class="widefat"></p>

					  		<p><label for="readonly"><?php _e('Read Only(Do not allow customers to edit in myaccount):', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldreadonly[]" class="widefat"></p>

					  		<p><label for="placeholder"><?php _e('Placeholder:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldplaceholder[]" class="widefat"></p>

					  		<p><label for="width"><?php _e('Field Width:', 'eorf') ?></label> 
					  			<select name="fieldwidth[]" class="widefat">
					  				<option  value="full"><?php _e('Full Width', 'eorf') ?></option>
					  				<option  value="half"><?php _e('Half Width', 'eorf') ?></option>
					  			</select>
					  		</p>

					  		<p><label for="fieldmessage"><?php _e('Field Message:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldmessage[]" class="widefat"></p>

					  		<p>
					  			<label for="clogic"><?php _e('Conditional Logic:','eorf'); ?></label>
					  			<div class="showf">
					  				<select name="showif[]">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="Show"><?php _e('Show','eorf'); ?></option>
					  					<option value="Hide"><?php _e('Hide','eorf'); ?></option>
					  				</select>
					  			</div>
					  			<div class="showf_text"><?php _e('if value of','eorf'); ?></div>
					  			<div class="showf" id="cl">
					  				<select name="cfield[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					
					  				</select>
					  			</div>
					  			<div class="showf" id="cll">
					  				<select id="cll_select" name="ccondition[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="is not empty"><?php _e('is not empty','eorf'); ?></option>
					  					<option value="is equal to"><?php _e('is equal to','eorf'); ?></option>
					  					<option value="is not equal to"><?php _e('is not equal to','eorf'); ?></option>
					  					<option value="is checked"><?php _e('is checked','eorf'); ?></option>
					  					
					  				</select>
					  			</div>

					  			<div class="showf" id="clll">
					  				<input type="text" name="ccondition_value[]" class="clll_field" size="13">
					  			</div>
					  		</p>

					  		<p id="textapp"></p>
					  		<input type="hidden" name="fieldids[]" value="" id="fieldids" />

					  		

					  	</div>
					 </li>

					 <!-- Time Picker-->
					 <li id="bf" class="bf ui-state-default widget draggable">
					  	<div id="bwt" class="widget-top">
					  		<div class="widget-title-action">
								<a href="#available-widgets" class="widget-action"></a>
							</div>
					  		<div class="widget-title ui-sortable-handle"><h4><?php _e('Time Picker', 'eorf') ?><span class="in-widget-title"></span></h4></div>
					  		<input type="hidden" name="fieldtype" value="timepicker" id="fieldtype" />
					  		<input type="hidden" name="type" value="registration" id="type" />
					  		<input type="hidden" name="label" value="Time Picker" id="label" />
					  		<input type="hidden" name="name" value="registration_time_picker" id="name" />
					  		<input type="hidden" name="mode" value="registration_additional" id="mode" />
					  	</div>
					  	<div id="bw" class="widget-inside win">

					  		<p><label for="label"><?php _e('Label:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldlabel[]" class="widefat"></p>

					  		<p><label for="required"><?php _e('Required:', 'eorf') ?></label>
					  		<input type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>

					  		<p><label for="hide"><?php _e('Hide:', 'eorf') ?></label>
					  		<input  type="checkbox" value="1" name="fieldhide[]" class="widefat"></p>

					  		<p><label for="readonly"><?php _e('Read Only(Do not allow customers to edit in myaccount):', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldreadonly[]" class="widefat"></p>

					  		<p><label for="placeholder"><?php _e('Placeholder:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldplaceholder[]" class="widefat"></p>

					  		<p><label for="width"><?php _e('Field Width:', 'eorf') ?></label> 
					  			<select name="fieldwidth[]" class="widefat">
					  				<option  value="full"><?php _e('Full Width', 'eorf') ?></option>
					  				<option  value="half"><?php _e('Half Width', 'eorf') ?></option>
					  			</select>
					  		</p>

					  		<p><label for="fieldmessage"><?php _e('Field Message:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldmessage[]" class="widefat"></p>


					  		<p>
					  			<label for="clogic"><?php _e('Conditional Logic:','eorf'); ?></label>
					  			<div class="showf">
					  				<select name="showif[]">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="Show"><?php _e('Show','eorf'); ?></option>
					  					<option value="Hide"><?php _e('Hide','eorf'); ?></option>
					  				</select>
					  			</div>
					  			<div class="showf_text"><?php _e('if value of','eorf'); ?></div>
					  			<div class="showf" id="cl">
					  				<select name="cfield[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					
					  				</select>
					  			</div>
					  			<div class="showf" id="cll">
					  				<select id="cll_select" name="ccondition[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="is not empty"><?php _e('is not empty','eorf'); ?></option>
					  					<option value="is equal to"><?php _e('is equal to','eorf'); ?></option>
					  					<option value="is not equal to"><?php _e('is not equal to','eorf'); ?></option>
					  					<option value="is checked"><?php _e('is checked','eorf'); ?></option>
					  					
					  				</select>
					  			</div>

					  			<div class="showf" id="clll">
					  				<input type="text" name="ccondition_value[]" class="clll_field" size="13">
					  			</div>
					  		</p>

					  		<p id="textapp"></p>
					  		<input type="hidden" name="fieldids[]" value="" id="fieldids" />

					  		

					  	</div>
					 </li>

					 <!-- Password -->
					 <li id="bf" class="bf ui-state-default widget draggable">
					  	<div id="bwt" class="widget-top">
					  		<div class="widget-title-action">
								<a href="#available-widgets" class="widget-action"></a>
							</div>
					  		<div class="widget-title ui-sortable-handle"><h4>Password<span class="in-widget-title"></span></h4></div>
					  		<input type="hidden" name="fieldtype" value="password" id="fieldtype" />
					  		<input type="hidden" name="type" value="registration" id="type" />
					  		<input type="hidden" name="label" value="Password" id="label" />
					  		<input type="hidden" name="name" value="registration_password" id="name" />
					  		<input type="hidden" name="mode" value="registration_additional" id="mode" />
					  	</div>
					  	<div id="bw" class="widget-inside win">

					  		<p><label for="label"><?php _e('Label:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldlabel[]" class="widefat"></p>

					  		<p><label for="required"><?php _e('Required:', 'eorf') ?></label>
					  		<input type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>

					  		<p><label for="hide"><?php _e('Hide:', 'eorf') ?></label>
					  		<input  type="checkbox" value="1" name="fieldhide[]" class="widefat"></p>

					  		<p><label for="readonly"><?php _e('Read Only(Do not allow customers to edit in myaccount):', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldreadonly[]" class="widefat"></p>

					  		<p><label for="placeholder"><?php _e('Placeholder:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldplaceholder[]" class="widefat"></p>

					  		<p><label for="width"><?php _e('Field Width:', 'eorf') ?></label> 
					  			<select name="fieldwidth[]" class="widefat">
					  				<option  value="full"><?php _e('Full Width', 'eorf') ?></option>
					  				<option  value="half"><?php _e('Half Width', 'eorf') ?></option>
					  			</select>
					  		</p>

					  		<p><label for="fieldmessage"><?php _e('Field Message:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldmessage[]" class="widefat"></p>


					  		<p>
					  			<label for="clogic"><?php _e('Conditional Logic:','eorf'); ?></label>
					  			<div class="showf">
					  				<select name="showif[]">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="Show"><?php _e('Show','eorf'); ?></option>
					  					<option value="Hide"><?php _e('Hide','eorf'); ?></option>
					  				</select>
					  			</div>
					  			<div class="showf_text"><?php _e('if value of','eorf'); ?></div>
					  			<div class="showf" id="cl">
					  				<select name="cfield[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					
					  				</select>
					  			</div>
					  			<div class="showf" id="cll">
					  				<select id="cll_select" name="ccondition[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="is not empty"><?php _e('is not empty','eorf'); ?></option>
					  					<option value="is equal to"><?php _e('is equal to','eorf'); ?></option>
					  					<option value="is not equal to"><?php _e('is not equal to','eorf'); ?></option>
					  					<option value="is checked"><?php _e('is checked','eorf'); ?></option>
					  					
					  				</select>
					  			</div>

					  			<div class="showf" id="clll">
					  				<input type="text" name="ccondition_value[]" class="clll_field" size="13">
					  			</div>
					  		</p>

					  		<p id="textapp"></p>
					  		<input type="hidden" name="fieldids[]" value="" id="fieldids" />

					  		

					  	</div>
					 </li>

					 <!-- Image / File Upload -->
					 <li id="bf" class="bf ui-state-default widget draggable">
					  	<div id="bwt" class="widget-top">
					  		<div class="widget-title-action">
								<a href="#available-widgets" class="widget-action"></a>
							</div>
					  		<div class="widget-title ui-sortable-handle"><h4><?php _e('File / Image Upload', 'eorf') ?><span class="in-widget-title"></span></h4></div>
					  		<input type="hidden" name="fieldtype" value="file" id="fieldtype" />
					  		<input type="hidden" name="type" value="registration" id="type" />
					  		<input type="hidden" name="label" value="File / Image Upload" id="label" />
					  		<input type="hidden" name="name" value="registration_file" id="name" />
					  		<input type="hidden" name="mode" value="registration_additional" id="mode" />
					  	</div>
					  	<div id="bw" class="widget-inside win">

					  		<p><label for="label"><?php _e('Label:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldlabel[]" class="widefat"></p>

					  		<p><label for="required"><?php _e('Required:', 'eorf') ?></label>
					  		<input type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>

					  		<p><label for="hide"><?php _e('Hide:', 'eorf') ?></label>
					  		<input  type="checkbox" value="1" name="fieldhide[]" class="widefat"></p>

					  		<p><label for="readonly"><?php _e('Read Only(Do not allow customers to edit in myaccount):', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldreadonly[]" class="widefat"></p>

					  		<p><label for="placeholder"><?php _e('Placeholder:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldplaceholder[]" class="widefat"></p>

					  		<p><label for="width"><?php _e('Field Width:', 'eorf') ?></label> 
					  			<select name="fieldwidth[]" class="widefat">
					  				<option  value="full"><?php _e('Full Width', 'eorf') ?></option>
					  				<option  value="half"><?php _e('Half Width', 'eorf') ?></option>
					  			</select>
					  		</p>

					  		<p><label for="fieldmessage"><?php _e('Field Message:', 'eorf') ?></label>
					  		<input type="text" value="" name="fieldmessage[]" class="widefat"></p>


					  		<p>
					  			<label for="clogic"><?php _e('Conditional Logic:','eorf'); ?></label>
					  			<div class="showf">
					  				<select name="showif[]">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="Show"><?php _e('Show','eorf'); ?></option>
					  					<option value="Hide"><?php _e('Hide','eorf'); ?></option>
					  				</select>
					  			</div>
					  			<div class="showf_text"><?php _e('if value of','eorf'); ?></div>
					  			<div class="showf" id="cl">
					  				<select name="cfield[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					
					  				</select>
					  			</div>
					  			<div class="showf" id="cll">
					  				<select id="cll_select" name="ccondition[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="is not empty"><?php _e('is not empty','eorf'); ?></option>
					  					<option value="is equal to"><?php _e('is equal to','eorf'); ?></option>
					  					<option value="is not equal to"><?php _e('is not equal to','eorf'); ?></option>
					  					<option value="is checked"><?php _e('is checked','eorf'); ?></option>
					  					
					  				</select>
					  			</div>

					  			<div class="showf" id="clll">
					  				<input type="text" name="ccondition_value[]" class="clll_field" size="13">
					  			</div>
					  		</p>

					  		<p id="textapp"></p>
					  		<input type="hidden" name="fieldids[]" value="" id="fieldids" />

					  		

					  	</div>
					 </li>


					 <!--Numeric Field-->
					<li id="bf" class="bf ui-state-default widget draggable">
					  	<div id="bwt" class="widget-top">
					  		<div class="widget-title-action">
								<a href="#available-widgets" class="widget-action"></a>
							</div>
					  		<div class="widget-title ui-sortable-handle"><h4><?php _e('Numeric Field', 'eorf'); ?><span class="in-widget-title"></span></h4></div>
					  		<input type="hidden" name="fieldtype" value="numeric" id="fieldtype" />
					  		<input type="hidden" name="type" value="registration" id="type" />
					  		<input type="hidden" name="label" value="Numeric Field" id="label" />
					  		<input type="hidden" name="name" value="registration_numeric" id="name" />
					  		<input type="hidden" name="mode" value="registration_additional" id="mode" />
					  		<input type="hidden" name="showif" value="" id="showif" />
					  		<input type="hidden" name="cfield" value="" id="cfield" />
					  		<input type="hidden" name="ccondition" value="" id="ccondition" />
					  		<input type="hidden" name="ccondition_value" value="" id="ccondition_value" />
					  	</div>
					  	<div id="bw" class="widget-inside win">

					  		<p class="impnote"><?php _e('Note: Only Numbers are allowed in this field type.', 'eorf'); ?></p>

					  		<p><label for="label"><?php _e('Label:', 'eorf'); ?></label>
					  		<input type="text" value="" name="fieldlabel[]" class="widefat"></p>

					  		<p><label for="required"><?php _e('Required:', 'eorf'); ?></label>
					  		<input type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>

					  		<p><label for="hide"><?php _e('Hide:', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldhide[]" class="widefat"></p>

					  		<p><label for="readonly"><?php _e('Read Only(Do not allow customers to edit in myaccount):', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldreadonly[]" class="widefat"></p>

					  		<p><label for="placeholder"><?php _e('Placeholder:', 'eorf'); ?></label>
					  		<input type="text" value="" name="fieldplaceholder[]" class="widefat"></p>

					  		<p><label for="width"><?php _e('Field Width:', 'eorf'); ?></label> 
					  			<select name="fieldwidth[]" class="widefat">
					  				<option  value="full"><?php _e('Full Width', 'eorf'); ?></option>
					  				<option  value="half"><?php _e('Half Width','eorf'); ?></option>
					  			</select>
					  		</p>

					  		<p><label for="fieldmessage"><?php _e('Field Message:','eorf'); ?></label>
					  		<input type="text" value="" name="fieldmessage[]" class="widefat"></p>

					  		<p>
					  			<label for="clogic"><?php _e('Conditional Logic:','eorf'); ?></label>
					  			<div class="showf">
					  				<select name="showif[]">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="Show"><?php _e('Show','eorf'); ?></option>
					  					<option value="Hide"><?php _e('Hide','eorf'); ?></option>
					  				</select>
					  			</div>
					  			<div class="showf_text"><?php _e('if value of','eorf'); ?></div>
					  			<div class="showf" id="cl">
					  				<select name="cfield[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					
					  				</select>
					  			</div>
					  			<div class="showf" id="cll">
					  				<select id="cll_select" name="ccondition[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="is not empty"><?php _e('is not empty','eorf'); ?></option>
					  					<option value="is equal to"><?php _e('is equal to','eorf'); ?></option>
					  					<option value="is not equal to"><?php _e('is not equal to','eorf'); ?></option>
					  					<option value="is checked"><?php _e('is checked','eorf'); ?></option>
					  					
					  				</select>
					  			</div>

					  			<div class="showf" id="clll">
					  				<input type="text" name="ccondition_value[]" class="clll_field" size="13">
					  			</div>
					  		</p>

					  		<p id="textapp"></p>
					  		<input type="hidden" name="fieldids[]" value="" id="fieldids" />

					  		

					  	</div>
					</li>



					<!--Google Captcha Field-->
					<li id="bf" class="bf ui-state-default widget draggable">
					  	<div id="bwt" class="widget-top">
					  		<div class="widget-title-action">
								<a href="#available-widgets" class="widget-action"></a>
							</div>
					  		<div class="widget-title ui-sortable-handle"><h4><?php _e('Google Captcha', 'eorf'); ?><span class="in-widget-title"></span></h4></div>
					  		<input type="hidden" name="fieldtype" value="google_captcha" id="fieldtype" />
					  		<input type="hidden" name="type" value="registration" id="type" />
					  		<input type="hidden" name="label" value="Google Captcha" id="label" />
					  		<input type="hidden" name="name" value="registration_google_captcha" id="name" />
					  		<input type="hidden" name="mode" value="registration_additional" id="mode" />
					  		
					  	</div>
					  	<div id="bw" class="widget-inside win">

					  		<p class="impnote"><?php _e('Note: Before use this fields enter google captcha api Credentials in module setting tab.', 'eorf'); ?></p>

					  		<p><label for="label"><?php _e('Label:', 'eorf'); ?></label>
					  		<input type="text" value="" name="fieldlabel[]" class="widefat"></p>

					  		<p><label for="width"><?php _e('Field Width:', 'eorf'); ?></label> 
					  			<select name="fieldwidth[]" class="widefat">
					  				<option  value="full"><?php _e('Full Width', 'eorf'); ?></option>
					  				<option  value="half"><?php _e('Half Width','eorf'); ?></option>
					  			</select>
					  		</p>

					  		<p style="display: none;"><label for="required"><?php _e('Required:', 'eorf'); ?></label>
					  		<input type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>

					  		<p style="display: none;"><label for="hide"><?php _e('Hide:', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldhide[]" class="widefat"></p>

					  		<p style="display: none;"><label for="readonly"><?php _e('Read Only(Do not allow customers to edit in myaccount):', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldreadonly[]" class="widefat"></p>

					  		<p><label for="fieldmessage"><?php _e('Field Message:','eorf'); ?></label>
					  		<input type="text" value="" name="fieldmessage[]" class="widefat"></p>


					  		<p style="display: none;">
					  			<label for="clogic"><?php _e('Conditional Logic:','eorf'); ?></label>
					  			<div class="showf">
					  				<select name="showif[]">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="Show"><?php _e('Show','eorf'); ?></option>
					  					<option value="Hide"><?php _e('Hide','eorf'); ?></option>
					  				</select>
					  			</div>
					  			<div class="showf_text"><?php _e('if value of','eorf'); ?></div>
					  			<div class="showf" id="cl">
					  				<select name="cfield[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					
					  				</select>
					  			</div>
					  			<div class="showf" id="cll">
					  				<select id="cll_select" name="ccondition[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="is not empty"><?php _e('is not empty','eorf'); ?></option>
					  					<option value="is equal to"><?php _e('is equal to','eorf'); ?></option>
					  					<option value="is not equal to"><?php _e('is not equal to','eorf'); ?></option>
					  					<option value="is checked"><?php _e('is checked','eorf'); ?></option>
					  					
					  				</select>
					  			</div>

					  			<div class="showf" id="clll">
					  				<input type="text" name="ccondition_value[]" class="clll_field" size="13">
					  			</div>
					  		</p>


					  		<p id="textapp"></p>
					  		<input type="hidden" name="fieldids[]" value="" id="fieldids" />

					  		

					  	</div>
					</li>



					<!--Color Picker Field-->
					<li id="bf" class="bf ui-state-default widget draggable">
					  	<div id="bwt" class="widget-top">
					  		<div class="widget-title-action">
								<a href="#available-widgets" class="widget-action"></a>
							</div>
					  		<div class="widget-title ui-sortable-handle"><h4><?php _e('Color Picker Field', 'eorf'); ?><span class="in-widget-title"></span></h4></div>
					  		<input type="hidden" name="fieldtype" value="color_picker" id="fieldtype" />
					  		<input type="hidden" name="type" value="registration" id="type" />
					  		<input type="hidden" name="label" value="Color Picker Field" id="label" />
					  		<input type="hidden" name="name" value="registration_color_picker" id="name" />
					  		<input type="hidden" name="mode" value="registration_additional" id="mode" />
					  		<input type="hidden" name="showif" value="" id="showif" />
					  		<input type="hidden" name="cfield" value="" id="cfield" />
					  		<input type="hidden" name="ccondition" value="" id="ccondition" />
					  		<input type="hidden" name="ccondition_value" value="" id="ccondition_value" />
					  	</div>
					  	<div id="bw" class="widget-inside win">


					  		<p><label for="label"><?php _e('Label:', 'eorf'); ?></label>
					  		<input type="text" value="" name="fieldlabel[]" class="widefat"></p>

					  		<p><label for="required"><?php _e('Required:', 'eorf'); ?></label>
					  		<input type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>

					  		<p><label for="hide"><?php _e('Hide:', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldhide[]" class="widefat"></p>

					  		<p><label for="readonly"><?php _e('Read Only(Do not allow customers to edit in myaccount):', 'eorf'); ?></label>
					  		<input  type="checkbox" value="1" name="fieldreadonly[]" class="widefat"></p>

					  		<p><label for="placeholder"><?php _e('Placeholder:', 'eorf'); ?></label>
					  		<input type="text" value="" name="fieldplaceholder[]" class="widefat"></p>

					  		<p><label for="width"><?php _e('Field Width:', 'eorf'); ?></label> 
					  			<select name="fieldwidth[]" class="widefat">
					  				<option  value="full"><?php _e('Full Width', 'eorf'); ?></option>
					  				<option  value="half"><?php _e('Half Width','eorf'); ?></option>
					  			</select>
					  		</p>

					  		<p><label for="fieldmessage"><?php _e('Field Message:','eorf'); ?></label>
					  		<input type="text" value="" name="fieldmessage[]" class="widefat"></p>

					  		<p>
					  			<label for="clogic"><?php _e('Conditional Logic:','eorf'); ?></label>
					  			<div class="showf">
					  				<select name="showif[]">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="Show"><?php _e('Show','eorf'); ?></option>
					  					<option value="Hide"><?php _e('Hide','eorf'); ?></option>
					  				</select>
					  			</div>
					  			<div class="showf_text"><?php _e('if value of','eorf'); ?></div>
					  			<div class="showf" id="cl">
					  				<select name="cfield[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					
					  				</select>
					  			</div>
					  			<div class="showf" id="cll">
					  				<select id="cll_select" name="ccondition[]" class="cfields">
					  					<option value=""><?php _e('Select','eorf'); ?></option>
					  					<option value="is not empty"><?php _e('is not empty','eorf'); ?></option>
					  					<option value="is equal to"><?php _e('is equal to','eorf'); ?></option>
					  					<option value="is not equal to"><?php _e('is not equal to','eorf'); ?></option>
					  					<option value="is checked"><?php _e('is checked','eorf'); ?></option>
					  					
					  				</select>
					  			</div>

					  			<div class="showf" id="clll">
					  				<input type="text" name="ccondition_value[]" class="clll_field" size="13">
					  			</div>
					  		</p>

					  		<p id="textapp"></p>
					  		<input type="hidden" name="fieldids[]" value="" id="fieldids" />

					  		

					  	</div>
					</li>


				</ul>
			</div>	
		</div>

		<div class="form-right">
            <h3><?php _e('Registration Form Fields', 'eorf'); ?></h3>
            <div id="bfields">
            	<form method="post" action="" id="savefields" accept-charset="utf-8">
            		<ul id="sortable" class="sortable">
        				<?php foreach ($billing_fields as $billing_field) { ?>
					  	
					  <li id="<?php echo $billing_field->field_id; ?>" class="ui-state-default widget">
					  	<div id="bwt<?php echo $billing_field->field_id; ?>" class="widget-top">
					  		<div class="widget-title-action">
								<a href="#available-widgets" class="widget-action"></a>
							</div>
					  		<div class="widget-title ui-sortable-handle"><h4><?php echo __($billing_field->field_label, 'eorf'); ?><span class="in-widget-title"></span></h4></div>
					  	</div>
					  	<div id="bw<?php echo $billing_field->field_id; ?>" class="widget-inside win">

					  		<?php if($billing_field->field_type == 'numeric') { ?>
					  		<p class="impnote"><?php _e('Note: Only Numbers are allowed in this field type.', 'eorf'); ?></p>
					  		<?php } else if($billing_field->field_type == 'google_captcha') { ?>

					  			<p class="impnote"><?php _e('Note: Before use this fields enter google captcha api Credentials in module setting tab.', 'eorf'); ?></p>

					  		<?php } ?>

					  		<?php if($billing_field->field_type == 'user_checkbox') { ?>

					  			<p><label for="label"><?php _e('Label(for internal use only):', 'eorf') ?></label>
					  			<input type="text" value="<?php echo __($billing_field->field_label, 'eorf'); ?>" name="fieldlabel[]" class="widefat"></p>

					  		<?php } else { ?>

					  		<p><label for="label"><?php _e('Label:', 'eorf') ?></label>
					  		<input type="text" value="<?php echo __($billing_field->field_label, 'eorf'); ?>" name="fieldlabel[]" class="widefat"></p>

					  		<?php } ?>

					  		<?php if($billing_field->field_type == 'user_checkbox') { ?>
						  		<p><label for="label"><?php _e('Label(This will show on frontend. you can add html in this.):', 'eorf') ?></label>
						  			<p class="impnote"><?php _e('Note: This field is for just user acceptance like terms and conditions, this field will not be saved or sent in order or emails etc. You can add anchor tag to link with other page.', 'eorf'); ?></p>
						  		<textarea name="check_fieldlabel[]" class="widefat"><?php echo __("$billing_field->user_checkbox_label", "eorf"); ?></textarea></p>
					  		<?php } else { ?>
					  			<textarea style="display: none;" name="check_fieldlabel[]" class="widefat"></textarea>
					  		<?php } ?>

					  		<?php if($billing_field->field_type == 'google_captcha') { ?>
						  		<p style="display: none"><label for="required"><?php _e('Required:', 'eorf') ?></label>
						  		<input <?php checked($billing_field->is_required,1); ?> type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>


						  		<p style="display: none"><label for="hide"><?php _e('Hide:', 'eorf') ?></label>
						  		<input <?php checked($billing_field->is_hide,1); ?> type="checkbox" value="1" name="fieldhidden[]" class="widefat"></p>

						  		<p style="display: none"><label for="readonly"><?php _e('Read Only(Do not allow customers to edit in myaccount):', 'eorf') ?></label>
						  		<input <?php checked($billing_field->is_readonly,1); ?> type="checkbox" value="1" name="fieldreadonly[]" class="widefat"></p>

					  		<?php } else if($billing_field->field_type == 'user_checkbox') { ?>

					  			<p><label for="required"><?php _e('Required:', 'eorf') ?></label>
						  		<input <?php checked($billing_field->is_required,1); ?> type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>


						  		<p style="display: none"><label for="hide"><?php _e('Hide:', 'eorf') ?></label>
						  		<input <?php checked($billing_field->is_hide,1); ?> type="checkbox" value="1" name="fieldhidden[]" class="widefat"></p>

						  		<p style="display: none"><label for="readonly"><?php _e('Read Only(Do not allow customers to edit in myaccount):', 'eorf') ?></label>
						  		<input <?php checked($billing_field->is_readonly,1); ?> type="checkbox" value="1" name="fieldreadonly[]" class="widefat"></p>

					  		<?php } else { ?>
					  			<p><label for="required"><?php _e('Required:', 'eorf') ?></label>
						  		<input <?php checked($billing_field->is_required,1); ?> type="checkbox" value="1" name="fieldrequired[]" class="widefat"></p>


						  		<p><label for="hide"><?php _e('Hide:', 'eorf') ?></label>
						  		<input <?php checked($billing_field->is_hide,1); ?> type="checkbox" value="1" name="fieldhidden[]" class="widefat"></p>

						  		<p><label for="readonly"><?php _e('Read Only(Do not allow customers to edit in myaccount):', 'eorf') ?></label>
						  		<input <?php checked($billing_field->is_readonly,1); ?> type="checkbox" value="1" name="fieldreadonly[]" class="widefat"></p>
					  		<?php } ?>

					  		<?php if(($billing_field->field_type == 'select') && ($billing_field->field_mode == 'registration_additional')) { ?>
					  		
					  			<p>
						  		<label for="options"><?php _e('Options:', 'eorf') ?></label>
						  		<div class="field_wrapper">
									<div style="width:100%; float:left">

								        <a href="javascript:void(0);"  title="Add Option">
								        <img style="float:right; clear:both" onClick="getdata('<?php echo $billing_field->field_id; ?>')" id="<?php echo $billing_field->field_id; ?>" class="add_button" src="<?php echo EORF_URL; ?>images/add-icon.png"/></a>
								    </div>

										<?php 
											$options = $manage_fields->getOptions($billing_field->field_id);
											$a = 1;
											foreach ($options as $option) {
												

										?>
								  		 <div style="width:100%; float:left" id="b<?php echo $a; ?>">
								    	<input class="opval" placeholder="Option Value" type="text" name="option_value[]" value="<?php echo $option->meta_key; ?>"/>
								    	<input class="opval" placeholder="Option Text" type="text" name="option_text[]" value="<?php echo $option->meta_value; ?>"/>
								    	<input id="option_field_ids" class="opval" placeholder="" type="hidden" name="option_field_ids[]" value="<?php echo $billing_field->field_id; ?>"/>
								        <a href="javascript:void(0);" class="remove_bt"  title="Remove Option">
								        <img onClick="deldata('b<?php echo $a; ?>')"  class="remove_button" src="<?php echo EORF_URL; ?>images/remove-icon.png"/></a>
								        </div>
								        <?php $a++;  } ?>
								    
								</div>
						  		</p>
						  		<input type="hidden" value="noplaceholder" name="fieldplaceholder[]" class="widefat"></p>

					  		<?php } else if(($billing_field->field_type == 'multiselect') && ($billing_field->field_mode == 'registration_additional')) { ?>


					  			<p>
						  		<label for="options"><?php _e("Options:", "eorf") ?></label>
						  		<div class="field_wrapper">
									<div style="width:100%; float:left">

								        <a href="javascript:void(0);"  title="Add Option">
								        <img style="float:right; clear:both" onClick="getdata('<?php echo $billing_field->field_id; ?>')" id="<?php echo $billing_field->field_id; ?>" class="add_button" src="<?php echo EORF_URL; ?>images/add-icon.png"/></a>
								    </div>

										<?php 
											$options = $manage_fields->getOptions($billing_field->field_id);
											$a = 1;
											foreach ($options as $option) {
												

										?>
								  		 <div style="width:100%; float:left" id="bmu<?php echo $a; ?>">
								    	<input class="opval" placeholder="<?php _e('Option Value', 'eorf') ?>" type="text" name="option_value[]" value="<?php echo __($option->meta_key, 'eorf') ?>"/>
								    	<input class="opval" placeholder="<?php _e('Option Text', 'eorf') ?>" type="text" name="option_text[]" value="<?php echo __($option->meta_value, 'eorf'); ?>"/>
								    	<input id="option_field_ids" class="opval" placeholder="" type="hidden" name="option_field_ids[]" value="<?php echo $billing_field->field_id; ?>"/>
								        <a href="javascript:void(0);" class="remove_bt"  title="<?php _e('Remove Option', 'eorf'); ?>">
								        <img onClick="deldata('bmu<?php echo $a; ?>')"  class="remove_button" src="<?php echo EORF_URL; ?>images/remove-icon.png"/></a>
								        </div>
								        <?php $a++;  } ?>
								    
								</div>
						  		</p>
						  		<input type="hidden" value="noplaceholder" name="fieldplaceholder[]" class="widefat"></p>

					  		<?php } else if($billing_field->field_type == 'radioselect' && $billing_field->field_mode == 'registration_additional') { ?>

					  			<p>
						  		<label for="options"><?php _e('Options:', 'eorf') ?></label>
						  		<div class="field_wrapper">
									<div style="width:100%; float:left">

								        <a href="javascript:void(0);"  title="Add Option">
								        <img style="float:right; clear:both" onClick="getdata('<?php echo $billing_field->field_id; ?>')" id="<?php echo $billing_field->field_id; ?>" class="add_button" src="<?php echo EORF_URL; ?>images/add-icon.png"/></a>
								    </div>

										<?php 
											$options = $manage_fields->getOptions($billing_field->field_id);
											$a = 1;
											foreach ($options as $option) {
												

										?>
								  		 <div style="width:100%; float:left" id="b<?php echo $a; ?>">
								    	<input class="opval" placeholder="<?php _e('Option Value', 'eorf') ?>" type="text" name="option_value[]" value="<?php echo __($option->meta_key, 'eorf'); ?>"/>
								    	<input class="opval" placeholder="<?php _e('Option Text', 'eorf') ?>" type="text" name="option_text[]" value="<?php echo __($option->meta_value, 'eorf'); ?>"/>
								    	<input id="option_field_ids" class="opval" placeholder="" type="hidden" name="option_field_ids[]" value="<?php echo $billing_field->field_id; ?>"/>
								        <a href="javascript:void(0);" class="remove_bt"  title="<?php _e('Remove Option', 'eorf'); ?>">
								        <img onClick="deldata('b<?php echo $a; ?>')"  class="remove_button" src="<?php echo EORF_URL; ?>images/remove-icon.png"/></a>
								        </div>
								        <?php $a++;  } ?>
								    
								</div>
						  		</p>
						  		<input type="hidden" value="noplaceholder" name="fieldplaceholder[]" class="widefat"></p>

					  		<?php } else if($billing_field->field_type == 'checkbox' && $billing_field->field_mode == 'registration_additional') { ?>

					  		<input type="text" value="noplaceholder" name="fieldplaceholder[]" class="widefat"></p>

					  		<?php } else if($billing_field->field_type == 'user_checkbox') { ?>

					  		<input type="hidden" value="noplaceholder" name="fieldplaceholder[]" class="widefat">

					  		<?php } else if($billing_field->field_type != 'google_captcha' || $billing_field->field_type != 'google_captcha') { ?>

					  			<p><label for="placeholder"><?php _e('Placeholder:', 'eorf') ?></label>
					  			<input type="text" value="<?php echo __($billing_field->field_placeholder, 'eorf'); ?>" name="fieldplaceholder[]" class="widefat"></p>

					  		<?php } ?>


					  		<p><label for="width"><?php _e('Field Width:', 'eorf') ?></label> 
					  			<select name="fieldwidth[]" class="widefat">
					  				<option <?php selected($billing_field->width,'full'); ?> value="full"><?php _e('Full Width', 'eorf') ?></option>
					  				<option <?php selected($billing_field->width,'half'); ?> value="half"><?php _e('Half Width', 'eorf') ?></option>
					  			</select>
					  		</p>

					  		<p><label for="fieldmessage"><?php _e('Field Message:', 'eorf') ?></label>
					  			<input type="text" value="<?php echo __($billing_field->field_message, 'eorf'); ?>" name="fieldmessage[]" class="widefat">
					  		</p>

					  		<?php if($billing_field->field_type == 'google_captcha') {  ?>
					  		<div class="widd" style="display: none;">
					  			<label for="clogic"><?php _e('Conditional Logic:','eorf'); ?></label>
					  			<div class="showf">
					  				<select name="showif[]">
					  					<option value="" <?php echo selected($billing_field->showif,''); ?>><?php _e('Select','eorf'); ?></option>
					  					<option value="Show" <?php echo selected($billing_field->showif,'Show'); ?>><?php _e('Show','eorf'); ?></option>
					  					<option value="Hide" <?php echo selected($billing_field->showif,'Hide'); ?>><?php _e('Hide','eorf'); ?></option>
					  				</select>
					  			</div>
					  			<div class="showf_text"><?php _e('if value of','eorf'); ?></div>
					  			<div class="showf clshowf" id="cl">
					  				<?php 
					  				global $wpdb;
						            $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->eorf_fields." WHERE field_id!=%d", $billing_field->field_id));      
						            ?>
						            <select name="cfield[]" class="cfields">
						            <option value=""><?php _e('Select','eorf'); ?></option>
						            <?php 
						            foreach($results as $res) { ?>
										<option value="<?php echo $res->field_name; ?>" <?php echo selected($billing_field->cfield,$res->field_name); ?>><?php echo __($res->field_label, "eorf"); ?></option>
						            <?php } ?>
						            </select>
					  			</div>
					  			<div class="showf" id="cll">
					  				<select id="cll_select" name="ccondition[]" class="cfields">
					  					<option value="" <?php echo selected($billing_field->ccondition,''); ?>><?php _e('Select','eorf'); ?></option>
					  					<option value="is not empty" <?php echo selected($billing_field->ccondition,'is not empty'); ?>><?php _e('is not empty','eorf'); ?></option>
					  					<option value="is equal to" <?php echo selected($billing_field->ccondition,'is equal to'); ?>><?php _e('is equal to','eorf'); ?></option>
					  					<option value="is not equal to" <?php echo selected($billing_field->ccondition,'is not equal to'); ?>><?php _e('is not equal to','eorf'); ?></option>
					  					<option value="is checked" <?php echo selected($billing_field->ccondition,'is checked'); ?>><?php _e('is checked','eorf'); ?></option>
					  					
					  				</select>
					  			</div>

					  			<div class="showf" id="clll">
					  				<input type="text" name="ccondition_value[]" class="clll_field" size="13" value="<?php echo __($billing_field->ccondition_value, 'eorf'); ?>">
					  			</div>
					  		</div>
					  		<?php } else { ?>

					  			<div class="widd">
						  			<label for="clogic"><?php _e('Conditional Logic:','eorf'); ?></label>
						  			<div class="showf">
						  				<select name="showif[]">
						  					<option value="" <?php echo selected($billing_field->showif,''); ?>><?php _e('Select','eorf'); ?></option>
						  					<option value="Show" <?php echo selected($billing_field->showif,'Show'); ?>><?php _e('Show','eorf'); ?></option>
						  					<option value="Hide" <?php echo selected($billing_field->showif,'Hide'); ?>><?php _e('Hide','eorf'); ?></option>
						  				</select>
						  			</div>
						  			<div class="showf_text"><?php _e('if value of','eorf'); ?></div>
						  			<div class="showf clshowf" id="cl">
						  				<?php 
						  				global $wpdb;
							            $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->eorf_fields." WHERE field_id!=%d", $billing_field->field_id));      
							            ?>
							            <select name="cfield[]" class="cfields">
							            <option value=""><?php _e('Select','eorf'); ?></option>
							            <?php 
							            foreach($results as $res) { ?>
											<option value="<?php echo $res->field_name; ?>" <?php echo selected($billing_field->cfield,$res->field_name); ?>><?php echo $res->field_label; ?></option>
							            <?php } ?>
							            </select>
						  			</div>
						  			<div class="showf" id="cll">
						  				<select id="cll_select" name="ccondition[]" class="cfields">
						  					<option value="" <?php echo selected($billing_field->ccondition,''); ?>><?php _e('Select','eorf'); ?></option>
						  					<option value="is not empty" <?php echo selected($billing_field->ccondition,'is not empty'); ?>><?php _e('is not empty','eorf'); ?></option>
						  					<option value="is equal to" <?php echo selected($billing_field->ccondition,'is equal to'); ?>><?php _e('is equal to','eorf'); ?></option>
						  					<option value="is not equal to" <?php echo selected($billing_field->ccondition,'is not equal to'); ?>><?php _e('is not equal to','eorf'); ?></option>
						  					<option value="is checked" <?php echo selected($billing_field->ccondition,'is checked'); ?>><?php _e('is checked','eorf'); ?></option>
						  					
						  				</select>
						  			</div>

						  			<div class="showf" id="clll">
						  				<input type="text" name="ccondition_value[]" class="clll_field" size="13" value="<?php echo $billing_field->ccondition_value; ?>">
						  			</div>
						  		</div>

					  		<?php } ?>


					  		<p>
					  			<?php if($billing_field->field_mode == 'registration_additional') { ?>
					  			<a onClick="deleteDiv('<?php echo $billing_field->field_id; ?>','<?php echo $billing_field->field_label; ?>')" class="widget-control-remove" href="javascript:void(0)">Delete</a>
									|
								<?php } ?>
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
		<input type="button" onClick="savedata()" value="<?php _e('Save Changes', 'eorf') ?>" class="button button-primary widget-control-save right" id="widget-archives-2-savewidget" name="savewidget"><span class="spinner"></span>
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
					data: {"action": "update_sortorder","fieldids":order}, // Sending data dname to post_word_count function.
					success: function(data){ 
					}
				});                                                            

			}  

		});

	});

	var my_delay = 3000;

	jQuery(function() {
	    callAjax();
	});

	jQuery(document).ready(function($) {
		
		// var count = $('#sortable').find('li');
		// alert(count.length);





		$( "#bdrag .draggable" ).draggable({ 

			connectToSortable: "#sortable",
            helper: "clone",
            revert: "invalid",
            start : function(event, ui) {
			      ui.helper.width('300');
			  },
            stop: function(event, ui) {

            	$("#sortable .draggable").attr( "style", "" );
            	$("#sortable .draggable").attr( "id", "newfield" );

            	$('#newfield #bf').removeClass('ui-state-default widget').addClass('ui-state-default widget open');
			    $("#newfield  #bw").slideDown('slow');
            	$('#newfield #bwt').toggle(function(){
			       $('#newfield #bf').removeClass('ui-state-default widget').addClass('ui-state-default widget open');
			       $("#newfield  #bw").slideDown('slow');
			   	},function(){
			   	$('#newfield  #bf').removeClass('ui-state-default widget open').addClass('ui-state-default widget');
			       $("#newfield  #bw").slideUp('slow');
			   	});

			   	var ajaxurl = "<?php echo admin_url( 'admin-ajax.php'); ?>";
				var fieldtype = $("#newfield #fieldtype").val();
				var type = $("#newfield #type").val();
				var label = $("#newfield #label").val();
				var name = $("#newfield #name").val();
				var mode = $("#newfield #mode").val();

				

				jQuery.ajax({
				type: 'POST',   // Adding Post method
				url: ajaxurl, // Including ajax file
				data: {"action": "insert_field","fieldtype":fieldtype,"type":type,"label":label,"name":name,"mode":mode}, // Sending data dname to post_word_count function.
				dataType: 'json',
				success: function(data) {

					setTimeout(callAjax(data), my_delay);

					if(data == 'extalready') {
						alert('<?php _e("Google Captcha Field is already added! you can not add more than one google captcha fields.") ?>');
						location.reload();
					} else {

						if($("#sortable .draggable").attr( "id" ) == 'newfield') {
							$('#sortable #newfield').attr( 'id', data );
						}
						$('#sortable #'+data).attr('class', '');
						$('#sortable #'+data).attr('class', 'ui-state-default widget ui-sortable-handle');
						$('#sortable #'+data+' #textapp').html('');
						$('#sortable #'+data+' #textapp').append("<a onClick='deleteDiv("+data+","+data+")' class='widget-control-remove' href='javascript:void(0)'>Delete</a> | <a onClick='closeDiv("+data+")' class='widget-control-close' href='javascript:void(0)'>Close</a>");
						$('#sortable #'+data+' #bwt').attr('id','bwt'+data);
						// $('#bwt'+data).css({'display':'block'});
						$('#sortable #'+data+' #bw').attr('id','bw'+data);
						$('#sortable #bw'+data).find('input[name="fieldlabel[]"]').attr('value','registration_field_'+data);
						jQuery('#sortable #'+data+' #bw').attr('id','bw'+data);
						$('#sortable #'+data+' img').attr('id','bi'+data);
						$('#sortable #'+data+' img').attr('onClick','getdata('+data+')');
						$('#sortable #'+data+' #option_field_ids').val(data);
						$('#sortable #'+data+' #fieldids').val(data);
						$('#bwt'+data).css({'display':'block'});

						$('#bwt'+data).load(document.URL +  ' #bwt'+data);

						// $('#bwt'+data).css('display':'block !important');
						// $('body').on('click', '#bwt'+data ,function(){
						// 	alert();
						//  var x = jQuery('#bw'+data).attr('style');
						//  console.log(jQuery('#bw'+data));
						//  // jQuery('#bw'+data).toggle();

						//  // setTimeout(function(){
						//  // 	if(jQuery('#bw'+data).is(":visible") ) {
						//  // 		jQuery('#bw'+data).hide();
						//  // 		alert('visible');
						//  // 	} else {
						//  // 		jQuery('#bw'+data).show();
						//  // 		alert("hidden");
						//  // 	}
						//  // },1000);
						
						//  //alert(x);
						//  // jQuery('#bw'+data).attr('style','display: block;');
						//  // // alert(x);
						//   // if (x === "" || x=='display: none;') {
						//   // 	jQuery('#bw'+data).attr('style','display: block;');
						//   // } else if(x==='display: block;') {
						//   // 	jQuery('#bw'+data).attr('style','display: none;');
						//   // } 
						// });
						// location.reload();



					  $('#'+data+' #cll_select').on('change', function() {
					  	if(this.value == 'is empty' || this.value == 'is not empty' || this.value == 'is checked' || this.value == 'is not checked') {
					  		$('#'+data+' .clll_field').prop('disabled', true);
					  	} else {
					  		$('#'+data+' .clll_field').prop('disabled', false);
					  	}
					  });
					  // console.log($('#sortable #'+data));
					}
					$('#sortable #bwt'+data).click(function(){
						$('#bw'+data).toggle();
					});
				}
				});
            }
		});
	});

	
	// jQuery('body').on('click', '.widget-top', function(){
	// 	var id = jQuery(this).attr('id');
	// 	var id = id.substring(3);
	// 	jQuery('#bw'+id).show();
	// 	var check_style = jQuery('#bw'+id).attr('style');
	// 	alert(check_style);
	// 	if (check_style == "" || check_style =='display:none;') {
	// 		jQuery('#bw'+id).show();
	// 	} else if(check_style =='display:block;') {
	// 		jQuery('#bw'+id).hide();
	// 	} 

	// });
	function callAjax(dataw) {
		//conditional logic drop down options

				jQuery.ajax({
					type: 'POST',   // Adding Post method
					url: ajaxurl, // Including ajax file
					data: {"action": "fetch_drop_options","fieldid":dataw}, // Sending data dname to post_word_count function.
					
					success: function(ndata) { 
						
						jQuery('#'+dataw+' #cl').html(ndata);
					}
				});
	}

	

	jQuery(document).ready(function(jQuery){ 
		"use strict";
		
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

	function deleteDiv(field_id,field_label) { 
	var ajaxurl = '<?php echo admin_url( 'admin-ajax.php'); ?>';
	if(confirm("Are you sure to delete "+field_label+" field?"))
		{
			jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: {"action": "del_field", "field_id":field_id},
			success: function() {

				jQuery("#"+field_id).fadeOut('slow');
				jQuery("#"+field_id).remove();
				location.reload();

			}
			});

		}
	return false;
	}

	function closeDiv(field_id) { 

		jQuery('#'+field_id).removeClass('ui-state-default widget open').addClass('ui-state-default widget');
	    jQuery("#bw"+field_id).slideUp('slow');
	}

	function savedata() { 
		jQuery('#savefields').find(':checkbox:not(:checked)').attr('value', '0').prop('checked', true);
		jQuery('#ssavefields').find(':checkbox:not(:checked)').attr('value', '0').prop('checked', true);
		jQuery('#asavefields').find(':checkbox:not(:checked)').attr('value', '0').prop('checked', true);
		var data2 = jQuery('#savefields, #ssavefields, #asavefields').serialize();
		var ajaxurl = '<?php echo admin_url( 'admin-ajax.php'); ?>';

		jQuery.ajax({
		    type: 'POST',
		    url: ajaxurl,
		    data: data2 + '&action=save_all_data',
		    success: function() {
		        window.location.reload(true);

		    }
		});
	}

	function getdata(id) {
		var maxField = 10000; //Input fields increment limitation
	
	
		var x = 1; //Initial field counter is 1
	 	//Once add button is clicked
		//var id = this.id; alert(id);
		var wrapper = jQuery('#'+id+' .field_wrapper'); //Input field wrapper
		var fieldHTML = '<div><input class="opval" placeholder="Option Value" type="text" name="option_value[]" value=""/><input class="opval opval2" placeholder="Option Text" type="text" name="option_text[]" value=""/><a href="javascript:void(0);" class="remove_bt"  title="Remove Option"><input class="opval" placeholder="" type="hidden" name="option_field_ids[]" value="'+id+'"/><img class="remove_button" src="<?php echo EORF_URL; ?>images/remove-icon.png"/></a></div>'; //New input field html 
		if(x < maxField){ //Check maximum number of input fields
			x++; //Increment field counter
			jQuery(wrapper).append(fieldHTML); // Add field html
		}
		jQuery(wrapper).on('click', '.remove_bt', function(e){ //Once remove button is clicked
		e.preventDefault();
		jQuery(this).parent('div').remove(); //Remove field html
		x--; //Decrement field counter
		});
		

	}

	function deldata(id) {
		jQuery("#"+id).remove();	
		
	}

</script>