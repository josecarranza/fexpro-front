<?php 
require_once( 'wp-load.php' ); //put correct absolute path for this file
 
 
global $wpdb;
 
if(isset($_GET['key']) && !empty($_GET['key'])){
 
    $email_decoded = base64_decode(strtr($_GET['key'], '-_', '+/'));  // decrypt email 
    $username_decoded = base64_decode(strtr($_GET['detail'], '-_', '+/')); // decrypt username
 
    $received_email = sanitize_text_field($email_decoded);
    $received_username = sanitize_text_field($username_decoded);
 
 
    if( email_exists( $received_email )) {
 
            //get the user id for the user record exists for received email from database 
            $user_id = $wpdb->get_var($wpdb->prepare("SELECT * FROM ".$wpdb->users." WHERE user_email = %s", $received_email ) );
 
            wp_set_auth_cookie( $user_id); //login the previously exist user
 
            wp_redirect(site_url()); // put the url where you want to redirect user after logged in
 
    }else {
 
             //register those user whose mail id does not exists in database 
 
            if(username_exists( $received_username )){
 
                //if username coming from first site exists in our database for any other user,
                //then the email id will be set as username
                $userdata = array(
                'user_login'  =>  $received_email,
                'user_email'  =>  $received_email, 
                'user_pass'   =>  $received_username,   // password will be username always
                'first_name'  =>  $received_username,  // first name will be username
                //'role'        =>  'subscriber'     //register the user with subscriber role only
            );
 
            }else {
 
                $userdata = array(
                'user_login'  =>  $received_username,
                'user_email'  =>  $received_email, 
                'user_pass'   =>  $received_username,   // password will be username always
                'first_name'  =>  $received_username,  // first name will be username
               // 'role'        =>  'subscriber'     //register the user with subscriber role only
            );
 
            }
 
 
            $user_id = wp_insert_user( $userdata ) ; // adding user to the database
 
            //On success
            if ( ! is_wp_error( $user_id ) ) {
                 
                wp_set_auth_cookie( $user_id); //login that newly created user
                wp_redirect(site_url()); // put the url where you want to redirect user after logged in
 
            }else{
 
                echo "There may be a mismatch of email/username with the existing record.
                      Check the users with your current email/username or try with any other account.";die;
            }
 
 
    }
 
     die;
 
} ?>