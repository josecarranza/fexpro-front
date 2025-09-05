<?php 

/*
 * Plugin Name:       Extendons: Woocommerce Registration Plugin - Add Custom Registration Fields
 * Plugin URI:        http://extendons.com
 * Description:       Obtain additional information from customers on registration form with Woocommerce Custom Registration fields plugin. You can also allow customers to login using their social networks. This Plugin supports 10 different types of custom fields such as text box, text area, select box, multi select box, checkbox, radio button, etc.
 * Version:           3.1.0
 * Author:            Extendons
 * Developed By:  	  Extendons Team
 * Author URI:        http://www.extendons.com/
 * Support URI:		  http://support.extendons.com/
 * Text Domain:       eorf
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Check if WooCommerce is active
 * if wooCommerce is not active module will not work.
 **/
if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	echo 'This plugin required woocommerce installed!';
}

if ( !class_exists( 'Extendons_Registration_Fields' ) ) { 

	class Extendons_Registration_Fields {

		

		function __construct() {

			$this->globconstants();
			$this->datatables();

			add_action( 'wp_loaded', array( $this, 'Extendons_init' ) );

			if ( is_admin() ) {
				require_once( EORF_PLUGIN_DIR . 'admin/class-eo-registration-fields-admin.php' );
				register_activation_hook( __FILE__, array( $this, 'install_module' ) );

				register_deactivation_hook( __FILE__, array( $this, 'exd_remove_database_tables' ));

				add_filter( 'extra_plugin_headers', array($this, 'eo_extra_plugin_headers' ));
				
			} 

			require_once( EORF_PLUGIN_DIR . 'front/class-eo-registration-fields-front.php' );

			add_action( 'wp_ajax_support_extend_contact', array($this,'support_extendon_callback' ));
			add_action( 'wp_ajax_nopriv_support_extend_contact', array($this,'support_extendon_callback' ));

			add_action( 'wp_ajax_extendon_settingopt', array($this,'extendon_settingopt_callback' ));
			add_action( 'wp_ajax_nopriv_extendon_settingopt', array($this,'extendon_settingopt_callback' ));
			
		}

		function eo_extra_plugin_headers($headers) {

			$headers['support'] = 'Support';
			return $headers;		
		}

		function user_snd_mail($firstname , $lastname , $email , $email_settings , $str){
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";
			if ($email_settings[$str.'_enable_mail'] == 'on') {
				$requested_role_message = $email_settings[$str.'_message'];
				$requested_role_message = str_replace("{first_name}", $firstname, $requested_role_message);
				$requested_role_message = str_replace("{last_name}",$lastname, $requested_role_message);
				$requested_role_message = str_replace("{email}", $email, $requested_role_message);
				wp_mail($email , $email_settings[$str.'_subject'] , $requested_role_message,$headers);
			}
		}


		public function globconstants() {
            
            if ( !defined( 'EORF_URL' ) )
                define( 'EORF_URL', plugin_dir_url( __FILE__ ) );

            if ( !defined( 'EORF_BASENAME' ) )
                define( 'EORF_BASENAME', plugin_basename( __FILE__ ) );

            if ( ! defined( 'EORF_PLUGIN_DIR' ) )
                define( 'EORF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        }

        public function install_module() {

        	$this->datatables();
        	

        	if (!file_exists(EORF_PLUGIN_DIR.'uploaded_img')) {
			    mkdir(EORF_PLUGIN_DIR.'uploaded_img', 0777, true);
			}
			$custom_role = get_option('custom_role');
			if(empty($custom_role)){
				$role_arr =array();
				update_option('custom_role' ,$role_arr);
			}
			

        }

        private function datatables() {
            
			global $wpdb;
		
			$wpdb->eorf_fields = $wpdb->prefix . 'eorf_fields';
			$wpdb->eorf_de_fields = $wpdb->prefix . 'eorf_de_fields';
			$wpdb->eorf_meta = $wpdb->prefix . 'eorf_meta';

			$this->create_tables();
			$this->create_module_data();
		}


		
		function Extendons_init() {
	        if ( function_exists( 'load_plugin_textdomain' ) )
	            load_plugin_textdomain( 'eorf', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	   	}


	   	function exd_remove_database_tables() {

	   		global $wpdb;
		     

	   		$wpdb->eorf_fields = $wpdb->prefix . 'eorf_fields';
			$wpdb->eorf_de_fields = $wpdb->prefix . 'eorf_de_fields';
			$wpdb->eorf_meta = $wpdb->prefix . 'eorf_meta';

		    $sql1 = "DROP TABLE IF EXISTS $wpdb->eorf_fields";
		    $wpdb->query($sql1);

		    $sql2 = "DROP TABLE IF EXISTS $wpdb->eorf_de_fields";
		    $wpdb->query($sql2);

		    $sql3 = "DROP TABLE IF EXISTS $wpdb->eorf_meta";
		    $wpdb->query($sql3);
	   	}


        public function create_tables() {
            
			global $wpdb;
			
			$charset_collate = '';
		
			if ( !empty( $wpdb->charset ) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( !empty( $wpdb->collate ) )
				$charset_collate .= " COLLATE $wpdb->collate";	
				
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$wpdb->eorf_meta'" ) != $wpdb->eorf_meta ) {
				$sql1 = "CREATE TABLE " . $wpdb->eorf_meta . " (
									 meta_id int(25) NOT NULL auto_increment,
									 field_id varchar(255) NULL,
									 meta_key varchar(255) NULL,
									 meta_value text(255) NULL,
									 
									 PRIMARY KEY (meta_id)
									 ) $charset_collate;";
		
			
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql1 );
			}



			if ( $wpdb->get_var( "SHOW TABLES LIKE '$wpdb->eorf_fields'" ) != $wpdb->eorf_fields ) {
				$sql = "CREATE TABLE " . $wpdb->eorf_fields . " (
									 field_id int(25) NOT NULL auto_increment,
									 field_name varchar(255) NULL,
									 field_label varchar(255) NULL,
									 field_placeholder varchar(255) NULL,
									 is_required int(25) NOT NULL,
									 is_hide int(25) NOT NULL,
									 is_readonly int(25) NOT NULL,
									 width varchar(255) NULL,
									 sort_order int(25) NOT NULL,
									 field_type varchar(255) NULL,
									 type varchar(255) NULL,
									 field_mode varchar(255) NULL,
									 field_message text NULL,
									 showif varchar(255),
									 cfield varchar(255),
									 ccondition varchar(255),
									 ccondition_value varchar(255),
									 user_checkbox_label text,
									 
									 PRIMARY KEY (field_id)
									 ) $charset_collate;";
		
			
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
			}

			
			

			if ( $wpdb->get_var( "SHOW TABLES LIKE '$wpdb->eorf_de_fields'" ) != $wpdb->eorf_de_fields ) {
				$sql2 = "CREATE TABLE " . $wpdb->eorf_de_fields . " (
									 field_id int(25) NOT NULL auto_increment,
									 field_name varchar(255) NULL,
									 field_label varchar(255) NULL,
									 field_placeholder varchar(255) NULL,
									 is_required int(25) NOT NULL,
									 width varchar(255) NULL,
									 sort_order int(25) NOT NULL,
									 field_type varchar(255) NULL,
									 type varchar(255) NULL,
									 field_mode varchar(255) NULL,
									 field_message text NULL,
									 field_status text NULL,
									 
									 
									 PRIMARY KEY (field_id)
									 ) $charset_collate;";
		
			
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql2 );
			}

			


		}

		
		public function create_module_data() {

            global $wpdb;   
            if ( $wpdb->get_var( "SHOW TABLES LIKE '$wpdb->eorf_de_fields'" ) == $wpdb->eorf_de_fields ) {
            
            $result = $wpdb->get_results("SELECT * FROM ".$wpdb->eorf_de_fields);  
	        count($result); 
	            if(count($result)==0) {
	            	$this->set_module_default_data();
	            }
            }
            
        }

		function set_module_default_data() {
			global $wpdb;

			update_option( 'login_facebook', 'disabled' );
			update_option( 'login_twitter', 'disabled' );
			//First Name
			$wpdb->query($wpdb->prepare( 
            "
            INSERT INTO $wpdb->eorf_de_fields
	            (field_name, field_label, field_placeholder, is_required, width, sort_order, field_type, type, field_mode, field_message, field_status)
	            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
	            ", 
	            'first_name',
	            'First Name',
	            'Enter your first name',
	            '1',
	            'half',
	            '1',
	            'text',
	            'default',
	            'de_registration',
	            '',
	            'disabled'
            
            ) );

			//Last Name
            $wpdb->query($wpdb->prepare( 
            "
            INSERT INTO $wpdb->eorf_de_fields
	            (field_name, field_label, field_placeholder, is_required, width, sort_order, field_type, type, field_mode, field_message, field_status)
	            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
	            ", 
	            'last_name',
	            'Last Name',
	            'Enter your last name',
	            '1',
	            'half',
	            '2',
	            'text',
	            'default',
	            'de_registration',
	            '',
	            'disabled'
            
            ) );

           

            //Company
            $wpdb->query($wpdb->prepare( 
            "
            INSERT INTO $wpdb->eorf_de_fields
	            (field_name, field_label, field_placeholder, is_required, width, sort_order, field_type, type, field_mode, field_message, field_status)
	            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
	            ", 
	            'billing_company',
	            'Company',
	            'Enter your company',
	            '0',
	            'full',
	            '5',
	            'text',
	            'default',
	            'de_registration',
	            '',
	            'disabled'
            
            ) );


            //Country
            $wpdb->query($wpdb->prepare( 
            "
            INSERT INTO $wpdb->eorf_de_fields
	            (field_name, field_label, field_placeholder, is_required, width, sort_order, field_type, type, field_mode, field_message, field_status)
	            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
	            ", 
	            'billing_country',
	            'Country',
	            'Select your country',
	            '1',
	            'full',
	            '6',
	            'select',
	            'default',
	            'de_registration',
	            '',
	            'disabled'
            
            ) );

            //Address Line 1
            $wpdb->query($wpdb->prepare( 
            "
            INSERT INTO $wpdb->eorf_de_fields
	            (field_name, field_label, field_placeholder, is_required, width, sort_order, field_type, type, field_mode, field_message, field_status)
	            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
	            ", 
	            'billing_address_1',
	            'Street Address',
	            'House number and street name',
	            '1',
	            'full',
	            '7',
	            'text',
	            'default',
	            'de_registration',
	            '',
	            'disabled'
            
            ) );

            //Address Line 2
            $wpdb->query($wpdb->prepare( 
            "
            INSERT INTO $wpdb->eorf_de_fields
	            (field_name, field_label, field_placeholder, is_required, width, sort_order, field_type, type, field_mode, field_message, field_status)
	            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
	            ", 
	            'billing_address_2',
	            'Address 2',
	            'Apartment, suite, unit etc. (optional)',
	            '0',
	            'full',
	            '8',
	            'text',
	            'default',
	            'de_registration',
	            '',
	            'disabled'
            
            ) );

            //City
            $wpdb->query($wpdb->prepare( 
            "
            INSERT INTO $wpdb->eorf_de_fields
	            (field_name, field_label, field_placeholder, is_required, width, sort_order, field_type, type, field_mode, field_message, field_status)
	            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
	            ", 
	            'billing_city',
	            'Town / City',
	            'Enter your city',
	            '1',
	            'full',
	            '9',
	            'text',
	            'default',
	            'de_registration',
	            '',
	            'disabled'
            
            ) );

            //State
            $wpdb->query($wpdb->prepare( 
            "
            INSERT INTO $wpdb->eorf_de_fields
	            (field_name, field_label, field_placeholder, is_required, width, sort_order, field_type, type, field_mode, field_message, field_status)
	            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
	            ", 
	            'billing_state',
	            'State / County',
	            'Select your state / county',
	            '1',
	            'full',
	            '10',
	            'select',
	            'default',
	            'de_registration',
	            '',
	            'disabled'
            
            ) );


            //Postcode
            $wpdb->query($wpdb->prepare( 
            "
            INSERT INTO $wpdb->eorf_de_fields
	            (field_name, field_label, field_placeholder, is_required, width, sort_order, field_type, type, field_mode, field_message, field_status)
	            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
	            ", 
	            'billing_postcode',
	            'Postcode / Zip',
	            'Enter your postcode / zip',
	            '1',
	            'half',
	            '11',
	            'text',
	            'default',
	            'de_registration',
	            '',
	            'disabled'
            
            ) );


            //Phone
            $wpdb->query($wpdb->prepare( 
            "
            INSERT INTO $wpdb->eorf_de_fields
	            (field_name, field_label, field_placeholder, is_required, width, sort_order, field_type, type, field_mode, field_message, field_status)
	            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
	            ", 
	            'billing_phone',
	            'Phone',
	            'Enter your phone',
	            '1',
	            'half',
	            '12',
	            'tel',
	            'default',
	            'de_registration',
	            '',
	            'disabled'
            
            ) );

            

            
		}



        // support email/contact function
		function support_extendon_callback () {
			
			if(isset($_POST['condition']) && $_POST['condition'] == "extendons_support_contact") {

					$support_fname = $_POST['suppextfname'];
					$support_lname = $_POST['suppextlname'];
					$support_email = $_POST['suppextemail'];
					$support_number = $_POST['suppextnumber'];
					$support_subject = $_POST['suppextsubj'];
					$support_message = $_POST['suppextmasg'];	

					$to = "support@extendons.com";
					$subject = $support_subject;

					$message = "
					<html>
					<head>
					<title>"._e('Question Woocommerece.', 'eorf')."</title>
					</head>
					<body>
					<table>
					<tr>
					<td><b>"._e('First Name:', 'eorf')."</b></td>
					<td>$support_fname</td>
					</tr>
					<tr>
					<td><b>"._e('Last Name:', 'eorf')."</b></td>
					<td>$support_lname</td>
					</tr>
					<tr>
					<td><b>"._e('Email:', 'eorf')."</b></td>
					<td>$support_email</td>
					</tr>
					<tr>
					<td><b>"._e('Phone:', 'eorf')."</b></td>
					<td>$support_number</td>
					</tr>
					<tr>
					<td><b>"._e('Subject:', 'eorf')."</b></td>
					<td>$support_subject</td>
					</tr>
					<tr>
					<td><b>"._e('Message:', 'eorf')."</b></td>
					<td>$support_message</td>
					</tr>
					</table>
					</body>
					</html>
					";
					
					$headers .= "MIME-Version: 1.0\n";
					$headers .= "Content-type: text/html; charset=iso-8859-1\n";
					// $headers .= 'From: '.$admin_email.'' . "\r\n";
					// $headers .= 'Cc: '.$admin_email.'' . "\r\n";
					
					mail($to,$subject,$message,$headers);
				
			}

			die();
		}


		 //extednon saving setting option
		function extendon_settingopt_callback() {
			
			if(isset($_POST['condition']) && $_POST['condition'] == "setting_extend") {
				
				update_option( 'account_title', $_POST['account_title'], null );
				update_option( 'pending_user_msg', $_POST['pending_user_msg'], null );
				update_option( 'block_user_msg', $_POST['block_user_msg'], null );
				update_option( 'profile_title', $_POST['profile_title'], null );
				update_option( 'login_facebook', $_POST['login_facebook'], null );
				update_option( 'facebook_app_id', $_POST['facebook_app_id'], null );

				update_option( 'facebook_app_secret', $_POST['facebook_app_secret'], null );
				update_option( 'login_twitter', $_POST['login_twitter'], null );
				update_option( 'twitter_consumer_key', $_POST['twitter_consumer_key'], null );
				update_option( 'twitter_consumer_secret', $_POST['twitter_consumer_secret'], null );
				update_option( 'twitter_access_token', $_POST['twitter_access_token'], null );
				update_option( 'twitter_access_token_secret', $_POST['twitter_access_token_secret'], null );
				update_option( 'recaptcha_site_key', $_POST['recaptcha_site_key'], null );
				update_option( 'recaptcha_secret_key', $_POST['recaptcha_secret_key'], null );
				update_option( 'enable_user_role', $_POST['enable_user_role'], null );

				parse_str( $_POST['registation_settings'], $data );

				update_option( 'registation_settings', $data, null );
				print_r($_POST);

			}

			die();
		}

        


	}

	new Extendons_Registration_Fields();


}

?>
