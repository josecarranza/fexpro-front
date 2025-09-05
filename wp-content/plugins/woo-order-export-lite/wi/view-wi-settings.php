<div>
    <div class="my-block" style="border:none; margin-bottom:15px">
        <form action="" method="post" id="form_wi_new_profile">
            <label for="">Profile</label>
            <select name="" id="select_wi_profile">
                <option value="">-default-</option>
                <?php foreach($wi_profiles as $key=> $w_profile):?>
                    <option value="<?=$key?>" <?=$wi_current_profile==$key?"selected":""?>><?=$w_profile["name"]?></option>
                <?php endforeach;?>
            </select>
            <button id="btn_wi_change_profile" type="button" class="button-primary">Select profile</button>
            OR
            
                <input type="text" id="txt_wi_new_profile" placeholder="Create new profile"  required/>
                <button id="btn_wi_new_profile" class="button-secondary">Create profile</button>
        </form>
    </div>
</div>
<script>
    var wi_profile_settings = {};
    <?php foreach($wi_profiles as $key=> $w_profile):?>
        wi_profile_settings['<?=$key?>'] = "<?=urlencode($w_profile["content"])?>";
     <?php endforeach;?>
</script>
<script>
    $=jQuery;
    $("#form_wi_new_profile").submit(function(e){
        e.preventDefault();
        let dataPost={
            profile_name: $("#txt_wi_new_profile").val(),
            action:"order_exporter_wi",
            method:"new_profile"
        };
        $.post(ajaxurl,dataPost,function(data){
            location.reload();
        });
    });
    $("#btn_wi_change_profile").click(function(){
       
		woe_set_form_submitting();

		woe_move_fields_in_product();

		var data = 'json=' + wi_profile_settings[$("#select_wi_profile").val()]
		data = data + "&action=order_exporter&method=save_settings&mode=" + mode + "&id=" + job_id + '&woe_nonce=' + settings_form.woe_nonce + '&tab=' + settings_form.woe_active_tab;

		$( '#Settings_updated' ).hide();

		$.post( ajaxurl, data, function ( response ) {
			$( '#Settings_updated' ).show().delay( 5000 ).fadeOut();

            
            
            location.href=location.origin+location.pathname+"?page=wc-order-export&tab=export&profile="+$("#select_wi_profile").val();
		}, "json" );
        return false;
    });
    $(document).ready(function(){
        $( "#save-only-btn" ).click( function () {
            console.log("entroo");
            if ( ! woe_validate_export() ) {
                return false;
            }

            let dataPost={
                profile: $("#select_wi_profile").val(),
                action:"order_exporter_wi",
                method:"save_profile_settings",
                content: woe_make_json_var( $( '#export_job_settings' ) )
            };
            $.post(ajaxurl,dataPost,function(data){

            });
            
            return false;
        });
    });
   
</script>