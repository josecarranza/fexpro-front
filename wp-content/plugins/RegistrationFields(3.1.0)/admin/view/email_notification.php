<?php
    $email_settings = (get_option('email_notification_setting'));

?>

<div class="container bootstrap-iso">

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h3 id="head"><?php echo esc_html('Email Notification Settings', 'eorf'); ?> </h3>
        </div>
    </div>
    <div class="row">
        <form method="POST" enctype="multipart/form-data" id="Form">
            <div class="col-sm-12 col-md-6 lb" id="vertical-line">
                <h4 id="tamplate_hedding"><?php echo esc_html('Role Grant Notification', 'eorf'); ?> </h4>
                <label><?php echo esc_html('Notification:', 'eorf'); ?></label>
                <input type="checkbox" id="on" value="on" data-toggle="toggle" data-on="<?php echo esc_html('Enable', 'ext_CTYP'); ?>"
                           data-off="<?php echo esc_html('Disable', 'ext_CTYP'); ?>"
                           name="grant_role_enable_mail"  <?php checked('on', esc_attr($email_settings['grant_role_enable_mail']), true);?>  >

                <p id="email_noti"><?php echo esc_html('', 'eorf'); ?></p>


                <label><?php echo esc_html('Email Subject', 'eorf'); ?></label>
                <input type="text" name="grant_role_subject" value="<?php echo esc_attr($email_settings['grant_role_subject'])?>" id="C_Slug" class="form-control">
                <p id="email_noti"></p>


                <label><?php echo esc_html('Message', 'eorf'); ?></label>
                <?php wp_editor(
                    ($email_settings['grant_role_message']),
                    'grant_role_message',
                    array(
                        'textarea_rows' => 4,
                    )
                );?>
                <p id="email_noti"></p>
            </div>

            <div class="col-sm-12 col-md-6 lb">
                <h4 id="tamplate_hedding"><?php echo esc_html('Reject Role Notification', 'eorf'); ?> </h4>

                <label><?php echo esc_html('Notification', 'eorf'); ?></label>
                <input type="checkbox" id="on" value="on" data-toggle="toggle" data-on="<?php echo esc_html('Enable', 'ext_CTYP'); ?>"
                       data-off="<?php echo esc_html('Disable', 'ext_CTYP'); ?>"
                       name="reject_role_enable_mail"  <?php checked('on', esc_attr($email_settings['reject_role_enable_mail']), true);?>  >

                <p id="email_noti"><?php echo esc_html('', 'eorf'); ?></p>


                <label><?php echo esc_html('Email Subject', 'eorf'); ?></label>
                <input type="text" name="reject_role_subject" value="<?php echo esc_attr($email_settings['reject_role_subject'])?>" id="C_Slug" class="form-control">
                <p id="email_noti"></p>


                <label><?php echo esc_html('Message', 'eorf'); ?></label>
                <?php wp_editor(
                    ($email_settings['reject_role_message']),
                    'reject_role_message',
                    array(
                        'textarea_rows' => 4,
                    )
                );?>
                <p id="email_noti"></p>
            </div>
            <div class="col-sm-12 col-md-6 lb" id="vertical-line">
                <h4 id="tamplate_hedding"><?php echo esc_html('Pending Requested Role', 'eorf'); ?> </h4>
                <label><?php echo esc_html('Notification:', 'eorf'); ?></label>
                <input type="checkbox" id="on" value="on" data-toggle="toggle" data-on="<?php echo esc_html('Enable', 'ext_CTYP'); ?>"
                       data-off="<?php echo esc_html('Disable', 'ext_CTYP'); ?>"
                       name="pending_role_enable_mail"  <?php checked('on', esc_attr($email_settings['pending_role_enable_mail']), true);?>  >

                <p id="email_noti"><?php echo esc_html('', 'eorf'); ?></p>


                <label><?php echo esc_html('Email Subject', 'eorf'); ?></label>
                <input type="text" name="pending_role_subject" value="<?php echo esc_attr($email_settings['pending_role_subject'])?>" id="C_Slug" class="form-control">
                <p id="email_noti"></p>


                <label><?php echo esc_html('Message', 'eorf'); ?></label>
                <?php wp_editor(
                    ($email_settings['pending_role_message']),
                    'pending_role_message',
                    array(
                        'textarea_rows' => 4,
                    )
                );?>
                <p id="email_noti"></p>
            </div>

            <div class="col-sm-12 col-md-6 lb" id="">
                <h4 id="tamplate_hedding"><?php echo esc_html('Approve User', 'eorf'); ?> </h4>
                <label><?php echo esc_html('Notification:', 'eorf'); ?></label>
                <input type="checkbox" id="on" value="on" data-toggle="toggle" data-on="<?php echo esc_html('Enable', 'ext_CTYP'); ?>"
                       data-off="<?php echo esc_html('Disable', 'ext_CTYP'); ?>"
                       name="approve_user_enable_mail"  <?php checked('on', esc_attr($email_settings['approve_user_enable_mail']), true);?>  >

                <p id="email_noti"><?php echo esc_html('', 'eorf'); ?></p>


                <label><?php echo esc_html('Email Subject', 'eorf'); ?></label>
                <input type="text" name="approve_user_subject" value="<?php echo esc_attr($email_settings['approve_user_subject'])?>" id="C_Slug" class="form-control">
                <p id="email_noti"></p>


                <label><?php echo esc_html('Message', 'eorf'); ?></label>
                <?php wp_editor(
                    ($email_settings['approve_user_message']),
                    'approve_user_message',
                    array(
                        'textarea_rows' => 4,
                    )
                );?>
                <p id="email_noti"></p>
            </div>

            <div class="col-sm-12 col-md-6 lb" id="vertical-line">
                <h4 id="tamplate_hedding"><?php echo esc_html('Pending User', 'eorf'); ?> </h4>
                <label><?php echo esc_html('Notification:', 'eorf'); ?></label>
                <input type="checkbox" id="on" value="on" data-toggle="toggle" data-on="<?php echo esc_html('Enable', 'ext_CTYP'); ?>"
                       data-off="<?php echo esc_html('Disable', 'ext_CTYP'); ?>"
                       name="pending_user_enable_mail"  <?php checked('on', esc_attr($email_settings['pending_user_enable_mail']), true);?>  >

                <p id="email_noti"><?php echo esc_html('', 'eorf'); ?></p>


                <label><?php echo esc_html('Email Subject', 'eorf'); ?></label>
                <input type="text" name="pending_user_subject" value="<?php echo esc_attr($email_settings['pending_user_subject'])?>" id="C_Slug" class="form-control">
                <p id="email_noti"></p>


                <label><?php echo esc_html('Message', 'eorf'); ?></label>
                <?php wp_editor(
                    ($email_settings['pending_user_message']),
                    'pending_user_message',
                    array(
                        'textarea_rows' => 4,
                    )
                );?>
                <p id="email_noti"></p>
            </div>

            <div class="col-sm-12 col-md-6 lb" id="">
                <h4 id="tamplate_hedding"><?php echo esc_html('Block User', 'eorf'); ?> </h4>
                <label><?php echo esc_html('Notification:', 'eorf'); ?></label>
                <input type="checkbox" id="on" value="on" data-toggle="toggle" data-on="<?php echo esc_html('Enable', 'ext_CTYP'); ?>"
                       data-off="<?php echo esc_html('Disable', 'ext_CTYP'); ?>"
                       name="block_user_enable_mail"  <?php checked('on', esc_attr($email_settings['block_user_enable_mail']), true);?>  >

                <p id="email_noti"><?php echo esc_html('', 'eorf'); ?></p>


                <label><?php echo esc_html('Email Subject', 'eorf'); ?></label>
                <input type="text" name="block_user_subject" value="<?php echo esc_attr($email_settings['block_user_subject'])?>" id="C_Slug" class="form-control">
                <p id="email_noti"></p>


                <label><?php echo esc_html('Message', 'eorf'); ?></label>
                <?php wp_editor(
                    ($email_settings['block_user_message']),
                    'block_user_message',
                    array(
                        'textarea_rows' => 4,
                    )
                );?>
                <p id="email_noti"></p>
            </div>

            <div class="col-sm-12 col-md-6 lb" id="vertical-line">
                <h4 id="tamplate_hedding"><?php echo esc_html('Limited Access ', 'eorf'); ?> </h4>
                <label><?php echo esc_html('Notification:', 'eorf'); ?></label>
                <input type="checkbox" id="on" value="on" data-toggle="toggle" data-on="<?php echo esc_html('Enable', 'ext_CTYP'); ?>"
                       data-off="<?php echo esc_html('Disable', 'ext_CTYP'); ?>"
                       name="limited_access_enable_mail"  <?php checked('on', esc_attr($email_settings['limited_access_enable_mail']), true);?>  >

                <p id="email_noti"><?php echo esc_html('', 'eorf'); ?></p>


                <label><?php echo esc_html('Email Subject', 'eorf'); ?></label>
                <input type="text" name="limited_access_subject" value="<?php echo esc_attr($email_settings['limited_access_subject'])?>" id="C_Slug" class="form-control">
                <p id="email_noti"></p>


                <label><?php echo esc_html('Message', 'eorf'); ?></label>
                <?php wp_editor(
                    ($email_settings['limited_access_message']),
                    'limited_access_message',
                    array(
                        'textarea_rows' => 4,
                    )
                );?>
                <p id="email_noti"></p>
            </div>

            <div class="col-sm-12 col-md-12 lb" >
                <input type="submit" value="Save Changes" class="btn btn-primary" name="save_email_notification"/>
            </div>

        </form>
    </div>
</div>