<div id="extedndons-tabs">

    <?php

    $active_plugins = (array) get_option( 'active_plugins', array() );

    if ( is_multisite() ) {
        $active_plugins = array_merge( $active_plugins, array_keys( get_site_option( 'active_sitewide_plugins', array() ) ) );
    }

    foreach ( $active_plugins as $plugin ) {

        $plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );

        if ( in_array('Extendons', $plugin_data)) {

            $outpluing = $plugin_data;
        }
    }

    ?>

    <div class="extendons-tabs-ulli">

        <div class="extendon-logo-ui">
            <img src="<?php echo EORF_URL.'images/Extendons-logo.png'; ?>">
            <h2><?php _e('WooCommerce Registration Fields Version ('.$outpluing['Version'].')', 'eorf'); ?></h2>
        </div>

        <ul>
            <li><a href="#tabs-1"><span class="dashicons dashicons-sos"></span><?php _e('General Settings', 'eorf'); ?></a></li>
            <li><a href="#tabs-6"><span class="dashicons dashicons-admin-users"></span><?php _e('New Users Settings', 'eorf'); ?></a></li>
            <li><a href="#tabs-5"><span class="dashicons dashicons-businessman  "></span><?php _e('User Role Settings', 'eorf'); ?></a></li>
            <li><a href="#tabs-2"><span class="dashicons dashicons-facebook"></span><?php _e('Facebook Settings', 'eorf'); ?></a></li>
            <li><a href="#tabs-3"><span class="dashicons dashicons-twitter"></span><?php _e('Twitter Settings', 'eorf'); ?></a></li>
            <li><a href="#tabs-4"><span class="dashicons dashicons-update"></span><?php _e('Google reCaptcha Settings', 'eorf'); ?></a></li>
        </ul>

        <ul class="collapsed-extendon">
            <li id="coll"><a href="#"><span class="dashicons dashicons-arrow-left"></span><?php _e('Collapse Menu', 'eorf'); ?></a></li>
        </ul>

    </div>

    <div class="extendons-tabs-content">

        <!-- form starts from here -->
        <form id="extendfaq_setting_optionform" action="" method="post">

            <div class="extendon-top-content">
                <h1>
                    <?php _e('Extension configuration settings', 'eorf'); ?></h1>
                <p>
                    <?php _e('Configure basic settings to personalize the extension to your website specific requirements. With an enticing user interface, you can easily enable or disable an option or functionality. Try customization the extension and explore the useful features of this extension.
    ', 'eorf'); ?></p>

                <div id="option-success"><p><?php _e('Settings Saved!', 'eorf'); ?></p></div>

                <div class="extendon-support-actions">
                    <div class="actions extendon-support-links">
                        <a href="#" target="_blank"><span class="dashicons dashicons-thumbs-up"></span>Support Center</a>
                    </div>
                    <div class="actions extendon-submit">
                        <span id="ajax-extend"></span>
                        <input onclick="extendsettopt()" class="button button-primary" type="button" name="" value="Save Changes">
                        <?php wp_nonce_field(); ?>
                    </div>
                </div>

            </div>

            <div class="extendon-singletab" id="tabs-1">

                <h2><?php _e('General Settings', 'eorf'); ?></h2>

                <table class="extendon-table-optoin">

                    <tbody>

                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Account Section Title', 'eorf'); ?></h3>
                            </div>
                            <span class="description">
                                            <p><?php _e('Main heading of the account section', 'eorf'); ?></p>
                                        </span>
                        </th>
                        <td>
                            <input id="account_title" value="<?php echo __(get_option('account_title'), 'eorf'); ?>"  class="extendon-input-field" type="text">
                        </td>
                    </tr>

                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Profile Section Title', 'eorf'); ?></h3>
                            </div>
                            <span class="description">
                                            <p><?php _e('Main heading of the profile section.', 'eorf'); ?></p>
                                        </span>
                        </th>
                        <td>
                            <input id="profile_title" value="<?php echo __(get_option('profile_title'), 'eorf'); ?>"  class="extendon-input-field" type="text">
                        </td>
                    </tr>

                    <tr class="submit-extendon extendon-option-field">
                        <th></th>
                        <td>
                            <div class="actions extendon-submit">
                                <input onclick="extendsettopt()" class="button button-primary" type="button" name="" value="<?php _e('Save Changes', 'eorf') ?>">
                            </div>
                        </td>
                    </tr>

                    </tbody>

                </table>

            </div>

            <div class="extendon-singletab" id="tabs-6">

                <h2><?php _e('New Registration Settings', 'eorf'); ?></h2>

                <table class="extendon-table-optoin">

                    <tbody>
                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Approval New Users', 'eorf'); ?></h3>
                            </div>
                            <span class="description"><p><?php _e('Auto or Manually Approve Simple Users(If User Roles are Disabled).', 'eorf'); ?></p></span>
                        </th>
                        <td>
                            <div id="like_dislikegg">
                                <?php $registation_settings = get_option('registation_settings');?>
                                <input checked value="pending" class="enable_user_role likespermissiongg" id="extld0gg" type="radio" name="enable_approvel" <?php echo checked( $registation_settings['enable_approvel'], 'pending') ?>>
                                <label class="extndcgg" for="extld0gg"><?php _e('Manual', 'eorf'); ?></label>
                                <input value="approved" class="enable_user_role likespermissiongg" id="extld1gg" type="radio" name="enable_approvel" <?php echo checked( $registation_settings['enable_approvel'], 'approved') ?>>
                                <label class="extndcgg" for="extld1gg"><?php _e('Auto', 'eorf') ?></label>
                                <div id="like_dislikebgg"></div>
                            </div>
                        </td>
                    </tr>
                     <tr class="extendon-option-field" id="new_us_hiding">
                        <th id="new_us_hiding_th">
                        <div class="new_us_hiding"><?php _e('New Users Settings for Requested User Role ', 'eorf'); ?></div>
                        <th>
                    </tr>
                        
                         <?php
                    global $wp_roles;
                    $registation_settings = get_option('registation_settings');
                    $all_roles = $wp_roles->roles;
                    foreach ($all_roles as $key => $item){
                        ?>
                        <tr class="extendon-option-field">
                            <th>
                                <div class="option-head">
                                    <h3 id="user_role_heading"><?php _e( preg_replace('/[^A-Za-z0-9\-]/', ' ', $key), 'eorf'); ?></h3>
                                </div>
                                <span class="description"><p><?php _e('Select default user status for '.$key.' role ', 'eorf'); ?></p></span>
                            </th>
                            <td>
                                <select class="extendon-input-field" name="<?php echo $key;?>">
                                   
                                    <option value="pending" <?php selected($registation_settings[$key],'pending')?>><?php _e('Pending', 'eorf'); ?></option>
                                    <option value="approved" <?php selected($registation_settings[$key],'approved')?> ><?php _e('Approved', 'eorf'); ?></option>
                                    <option value="limited" <?php selected($registation_settings[$key],'limited')?> ><?php _e('Limited Access', 'eorf'); ?></option>
                                </select>
                            </td>
                        </tr>
                        <?php
                    }

                    ?>

                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Block User Message', 'eorf'); ?></h3>
                            </div>
                            <span class="description">
                                            <p><?php _e('User status messages.', 'eorf'); ?></p>
                                        </span>
                        </th>
                        <td>
                            <input id="block_user_msg" value="<?php echo __(get_option('block_user_msg'), 'eorf'); ?>" name="block_user_msg"  class="extendon-input-field" type="text">
                        </td>
                    </tr>

                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Pending User Message', 'eorf'); ?></h3>
                            </div>
                            <span class="description">
                                            <p><?php _e('User status messages.', 'eorf'); ?></p>
                                        </span>
                        </th>
                        <td>
                            <input id="pending_user_msg" value="<?php echo __(get_option('pending_user_msg'), 'eorf'); ?>"  name="pending_user_msg" class="extendon-input-field" type="text">
                        </td>
                    </tr>

                    </tbody>

                </table>

            </div>

            <div class="extendon-singletab" id="tabs-5">

                <h2><?php _e('User Role Settings', 'eorf'); ?></h2>
                <table class="extendon-table-optoin">

                    <tbody>
                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Enable User Role', 'eorf'); ?></h3>
                            </div>
                            <span class="description"><p><?php _e('Enable or Disable user role on registration page.', 'eorf'); ?></p></span>
                        </th>
                        <td>
                            <div id="like_dislikeu">
                                <?php $registation_settings = get_option('registation_settings');?>
                                <input checked value="enabled" class="enable_user_role likespermissionu" id="extld0u" type="radio" name="enable_user_role" <?php echo checked( $registation_settings['enable_user_role'], 'enabled') ?>>
                                <label class="extndcu" for="extld0u"><?php _e('Enable', 'eorf'); ?></label>
                                <input value="disabled" class="enable_user_role likespermissionu" id="extld1u" type="radio" name="enable_user_role" <?php echo checked( $registation_settings['enable_user_role'], 'disabled') ?>>
                                <label class="extndcu" for="extld1u"><?php _e('Disable', 'eorf') ?></label>
                                <div id="like_dislikebu"></div>
                            </div>
                        </td>
                    </tr>

                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Select User Roles', 'eorf'); ?></h3>
                            </div>
                            <span class="description"><p><?php _e('Select user role on registration page.', 'eorf'); ?></p></span>
                        </th>
                        <td>
                            <select class="role-select2 extendon-input-field" name="frontuserrole[]" multiple="multiple">
                                <?php
                                global $wp_roles;
                                $registation_settings = get_option('registation_settings');
                                $all_roles = $wp_roles->roles;
                                foreach ($all_roles as $key => $item){
                                    // 
                                    ?>
                                    <option value="<?php echo $key;?>"  <?php if (!empty($registation_settings['frontuserrole'])) { if(in_array($key, $registation_settings['frontuserrole'])){ echo "selected"; } }  ?> ><?php _e( preg_replace('/[^A-Za-z0-9\-]/', ' ', $key), 'eorf'); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>

                    <tr class="extendon-option-field">
                        <th>

                            <div class="option-head">
                                <h3><?php _e('Approve User Roles', 'eorf'); ?></h3>
                            </div>
                            <span class="description"><p><?php _e('Auto or Manually Approve New Requested User Roles.', 'eorf'); ?></p></span>
                        </th>
                        <td>
                            <div id="like_dislikeg">
                                <?php $registation_settings = get_option('registation_settings');?>
                                <input checked value="enabled" class="enable_user_role likespermissiong" id="extld0g" type="radio" name="enable_grant_user_role" <?php echo checked( $registation_settings['enable_grant_user_role'], 'enabled') ?>>
                                <label class="extndcg" for="extld0g"><?php _e('Manual', 'eorf'); ?></label>
                                <input value="disabled" class="enable_user_role likespermissiong" id="extld1g" type="radio" name="enable_grant_user_role" <?php echo checked( $registation_settings['enable_grant_user_role'], 'disabled') ?>>
                                <label class="extndcg" for="extld1g"><?php _e('Auto', 'eorf') ?></label>
                                <div id="like_dislikebg"></div>
                            </div>
                        </td>
                    </tr>


                   
                    </tbody>

                </table>

            </div>

            <div class="extendon-singletab" id="tabs-2">

                <h2><?php _e('Facebook Settings', 'eorf'); ?></h2>

                <table class="extendon-table-optoin">
                    <tbody>
                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Login With Facebook', 'eorf'); ?></h3>
                            </div>
                            <span class="description"><p><?php _e('Enable or Disable login with facebook feature on registration page.', 'eorf'); ?></p></span>
                        </th>
                        <td>
                            <div id="like_dislike">
                                <input checked value="enabled" class="login_facebook likespermission" id="extld0" type="radio" name="login_facebook" <?php echo checked( get_option('login_facebook'), 'enabled') ?>>
                                <label class="extndc" for="extld0"><?php _e('Enabled', 'eorf'); ?></label>
                                <input value="disabled" class="login_facebook likespermission" id="extld1" type="radio" name="login_facebook" <?php echo checked( get_option('login_facebook'), 'disabled') ?>>
                                <label class="extndc" for="extld1"><?php _e('Disabled', 'eorf') ?></label>
                                <div id="like_dislikeb"></div>
                            </div>
                        </td>
                    </tr>

                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Facebook App ID', 'eorf'); ?></h3>
                            </div>
                            <span class="description">
                                            <p><?php _e('This is the facebook app id, you can get this id from your facebook app settings.', 'eorf'); ?></p>
                                        </span>
                        </th>
                        <td>
                            <input id="facebook_app_id" value="<?php echo get_option('facebook_app_id'); ?>"  class="extendon-input-field" type="text">
                        </td>
                    </tr>

                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Facebook App Secret', 'eorf'); ?></h3>
                            </div>
                            <span class="description">
                                            <p><?php _e('This is the facebook app secret, you can get this id from your facebook app settings.', 'eorf'); ?></p>
                                        </span>
                        </th>
                        <td>
                            <input id="facebook_app_secret" value="<?php echo get_option('facebook_app_secret'); ?>"  class="extendon-input-field" type="text">
                        </td>
                    </tr>

                    </tbody>
                </table>

            </div>
            <div class="extendon-singletab" id="tabs-3">
                <h2><?php _e('Twitter Settings', 'eorf'); ?></h2>
                <table class="extendon-table-optoin">
                    <tbody>
                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Login With Twitter', 'eorf'); ?></h3>
                            </div>
                            <span class="description">
                                            <p><?php _e('Enable or Disable login with twitter feature on registration page.', 'eorf'); ?></p>
                                        </span>
                        </th>
                        <td>
                            <div id="radios">
                                <input checked class="login_twitter userfaq" value="enable" id="rad1" type="radio" name="login_twitter" <?php echo checked( get_option('login_twitter'), 'enable') ?>>
                                <label class="labels" for="rad1"><?php _e('Enabled', 'eorf') ?></label>
                                <input class="login_twitter userfaq" value="disable" id="rad2" type="radio" name="login_twitter" <?php echo checked( get_option('login_twitter'), 'disable') ?>>
                                <label class="labels" for="rad2"><?php _e('Disabled', 'eorf') ?></label>
                                <div id="bckgrnd"></div>
                            </div>
                        </td>
                    </tr>

                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Twitter App Consumer Key', 'eorf'); ?></h3>
                            </div>
                            <span class="description">
                                            <p><?php _e('This is the twitter app consumer key, you can get this key from your twitter app keys and access token tab.', 'eorf'); ?></p>
                                        </span>
                        </th>
                        <td>
                            <input id="twitter_consumer_key" value="<?php echo get_option('twitter_consumer_key'); ?>"  class="extendon-input-field" type="text">
                        </td>
                    </tr>

                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Twitter App Consumer Secret', 'eorf'); ?></h3>
                            </div>
                            <span class="description">
                                            <p><?php _e('This is the twitter app consumer secret, you can get this key from your twitter app keys and access token tab.', 'eorf'); ?></p>
                                        </span>
                        </th>
                        <td>
                            <input id="twitter_consumer_secret" value="<?php echo get_option('twitter_consumer_secret'); ?>"  class="extendon-input-field" type="text">
                        </td>
                    </tr>

                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Twitter App Access Token', 'eorf'); ?></h3>
                            </div>
                            <span class="description">
                                            <p><?php _e('This is the twitter app access token, you can get this key from your twitter app keys and access token tab.', 'eorf'); ?></p>
                                        </span>
                        </th>
                        <td>
                            <input id="twitter_access_token" value="<?php echo get_option('twitter_access_token'); ?>"  class="extendon-input-field" type="text">
                        </td>
                    </tr>

                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Twitter App Access Token Secret', 'eorf'); ?></h3>
                            </div>
                            <span class="description">
                                            <p><?php _e('This is the twitter app access token secret, you can get this key from your twitter app keys and access token tab.', 'eorf'); ?></p>
                                        </span>
                        </th>
                        <td>
                            <input id="twitter_access_token_secret" value="<?php echo get_option('twitter_access_token_secret'); ?>"  class="extendon-input-field" type="text">
                        </td>
                    </tr>

                    <tr class="extendon-option-field">
                        <td width="100%" colspan="2">
                            <p class="redd"><?php _e('In your app permission you must check "Request email addresses from users" for getting user email, otherwise account will not be created.', 'eorf'); ?>
                        </td>

                    </tr>

                    </tbody>
                </table>

            </div>

            <div class="extendon-singletab" id="tabs-4">
                <h2><?php _e('Google reCaptcha Settings', 'eorf'); ?></h2>
                <table class="extendon-table-optoin">
                    <tbody>


                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Site Key', 'eorf'); ?></h3>
                            </div>
                            <span class="description">
                                            <p><?php _e('Go to <a href="https://www.google.com/recaptcha/intro/index.html" target="_blank">Google reCaptcha</a> site then click on top right reCaptcha button and follow the Instructions. Register you site by giving url path and get the secret key.', 'eorf'); ?></p>
                                        </span>
                        </th>
                        <td>
                            <input id="recaptcha_site_key" value="<?php echo get_option('recaptcha_site_key'); ?>"  class="extendon-input-field" type="text">
                        </td>
                    </tr>

                    <tr class="extendon-option-field">
                        <th>
                            <div class="option-head">
                                <h3><?php _e('Secret Key', 'eorf'); ?></h3>
                            </div>
                            <span class="description">
                                            <p><?php _e('Go to <a href="https://www.google.com/recaptcha/intro/index.html" target="_blank">Google reCaptcha</a> site then click on top right reCaptcha button and follow the Instructions. Register you site by giving url path and get the secret key.', 'eorf'); ?></p>
                                        </span>
                        </th>
                        <td>
                            <input id="recaptcha_secret_key" value="<?php echo get_option('recaptcha_secret_key'); ?>"  class="extendon-input-field" type="text">
                        </td>
                    </tr>



                    </tbody>
                </table>

            </div>

        </form>

    </div>

</div>

<script>

    jQuery( function() {
        jQuery( "#extedndons-tabs" ).tabs().addClass('ui-tabs-vertical ui-helper-clearfix');
    });

    // ajax function for submitting setting option
    function extendsettopt() {
        var form_data = jQuery("form").serialize();

        var ajaxurl = "<?php echo admin_url( 'admin-ajax.php'); ?>";

        var condition = 'setting_extend';

        var account_title = jQuery('#account_title').val();
        var pending_user_msg = jQuery('#pending_user_msg').val();
        var block_user_msg = jQuery('#block_user_msg').val();
        var profile_title = jQuery('#profile_title').val();
        var login_facebook = jQuery('.login_facebook:checked').val();
        var enable_user_role = jQuery('.enable_user_role:checked').val();
        var facebook_app_id = jQuery('#facebook_app_id').val();
        var facebook_app_secret = jQuery('#facebook_app_secret').val();

        var login_twitter = jQuery('.login_twitter:checked').val();

        var twitter_consumer_key = jQuery('#twitter_consumer_key').val();
        var twitter_consumer_secret = jQuery('#twitter_consumer_secret').val();
        var twitter_access_token = jQuery('#twitter_access_token').val();
        var twitter_access_token_secret = jQuery('#twitter_access_token_secret').val();
        var recaptcha_site_key = jQuery('#recaptcha_site_key').val();
        var recaptcha_secret_key = jQuery('#recaptcha_secret_key').val();
        jQuery('#ajax-extend').show();
        jQuery.ajax({
            url : ajaxurl,
            type : 'post',
            data : {
                action : 'extendon_settingopt',

                condition : condition,

                account_title : account_title,
                block_user_msg :block_user_msg,
                pending_user_msg : pending_user_msg,
                profile_title : profile_title,
                login_facebook :login_facebook,
                facebook_app_id : facebook_app_id,
                facebook_app_secret : facebook_app_secret,
                login_twitter : login_twitter,
                twitter_consumer_key : twitter_consumer_key,
                twitter_consumer_secret : twitter_consumer_secret,
                twitter_access_token : twitter_access_token,
                twitter_access_token_secret : twitter_access_token_secret,
                recaptcha_site_key : recaptcha_site_key,
                recaptcha_secret_key : recaptcha_secret_key,
                registation_settings : form_data,
                enable_user_role : enable_user_role

            },
            success : function(response) {
                console.log(response);
                jQuery("#option-success").show().delay(3000).fadeOut("slow");
            },
            complete: function(){
                jQuery('#ajax-extend').hide();
            }
        });
    }

    jQuery(document).ready(function(){

        jQuery("#coll").click(function() {

            jQuery('.extendons-tabs-ulli').toggleClass('red');
            jQuery(".extendon-logo-ui h2").toggleClass('reddisnon');
            jQuery('.extendons-tabs-content').toggleClass('green');
            jQuery('#coll span.dashicons').toggleClass('dashicons-arrow-left dashicons-arrow-right');

            if (jQuery('.extendons-tabs-ulli').hasClass('red')){

                jQuery('#ui-id-1').get(0).lastChild.nodeValue = "";
                jQuery('#ui-id-2').get(0).lastChild.nodeValue = "";
                jQuery('#ui-id-3').get(0).lastChild.nodeValue = "";
                jQuery('#ui-id-4').get(0).lastChild.nodeValue = "";
                jQuery('#ui-id-5').get(0).lastChild.nodeValue = "";
                jQuery('#ui-id-6').get(0).lastChild.nodeValue = "";

                jQuery('#coll a').get(0).lastChild.nodeValue = "";

            } else {

                jQuery('.extendons-tabs-ulli').addClass('redd');
                jQuery('#ui-id-1').get(0).lastChild.nodeValue = "<?php _e('General Settings', 'eorf') ?>";
                jQuery('#ui-id-2').get(0).lastChild.nodeValue = "<?php _e('New Users Settings', 'eorf') ?>";
                jQuery('#ui-id-3').get(0).lastChild.nodeValue = "<?php _e('User Role Settings', 'eorf') ?>";
                jQuery('#ui-id-4').get(0).lastChild.nodeValue = "<?php _e('Facebook Settings', 'eorf') ?>";
                jQuery('#ui-id-5').get(0).lastChild.nodeValue = "<?php _e('Twitter Settings', 'eorf') ?>";
                jQuery('#ui-id-6').get(0).lastChild.nodeValue = "<?php _e('Google reCaptcha Settings', 'eorf') ?>";

                jQuery('#coll a').get(0).lastChild.nodeValue = "<?php _e('Collapse Menu', 'eorf') ?>";
            }
        });
    });
    jQuery(document).ready(function() {
        jQuery('.role-select2').select2();
    });

</script>