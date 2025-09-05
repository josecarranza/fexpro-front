<?php  session_start();
if ( ! defined( 'WPINC' ) ) {
    die;
}

include (EORF_PLUGIN_DIR . 'facebook-sdk/autoload.php');

if ( !class_exists( 'EO_Registration_Fields_Front' ) ) { 

	class EO_Registration_Fields_Front extends Extendons_Registration_Fields {

    
    	private $access_token;
    	private $redirect_url;
    	private $facebook_details;

    	

		public function __construct() {


			
			add_action( 'wp_loaded', array( $this, 'front_scripts' ) );
			//$this->module_settings = $this->get_module_settings();
			add_action( 'woocommerce_register_form_start', array($this, 'eo_extra_registration_form_start' ));

			add_action( 'woocommerce_register_form', array($this, 'eo_extra_registration_form_end' ));
			add_action( 'woocommerce_register_post', array($this, 'eo_validate_extra_register_fields'), 10, 3 );
			add_action( 'woocommerce_created_customer', array($this, 'eo_save_extra_register_fields' ));
			//add_action('woocommerce_before_my_account', array($this, 'eo_my_profile'));

			//add_action( 'init', array($this, 'add_eorf_query_vars' ));
			//add_action( 'template_include', array( $this, 'change_template' ) );


			add_action( 'init', array( $this, 'add_endpoints' ) );
			add_action('init', array($this, 'ex_tw_load'), 1);

			

			add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );
			add_filter( 'the_title', array( $this, 'endpoint_title' ) );
			add_filter( 'woocommerce_account_menu_items', array( $this, 'new_menu_items' ) );
			add_action( 'woocommerce_account_edit-profile_endpoint', array( $this, 'endpoint_content' ) );

			// if (isset($_POST['action'])) {
			//     if ($_POST['action'] == 'SubmitRegForm') {
			//         $this->submit_reg_edit_form($_POST['user_id']);
			//     }
			// }


			add_action('wp_ajax_save_all_eddata', array($this, 'save_all_eddata'));
			add_action('wp_ajax_nopriv_save_all_eddata', array($this, 'save_all_eddata'));
			add_action('wp_ajax_save_upload_file', array($this, 'save_upload_file'));
			add_action('wp_ajax_nopriv_save_upload_file', array($this, 'save_upload_file'));

			

			add_action( 'woocommerce_login_form_end', array($this, 'eo_login_form_end' ));
			add_action( 'wp_ajax_eo_facebook_login', array($this, 'FBCallback'));
       		add_action( 'wp_ajax_nopriv_eo_facebook_login', array($this, 'FBCallback'));

       		add_action( 'woocommerce_checkout_init', array($this, 'eo_billing_fields' ), 10, 1);

       		add_filter( 'woocommerce_form_field_multiselect', array($this, 'eo_custom_multiselect_handler'), 10, 4 );

       		add_action('wp_ajax_de_get_states', array($this, 'de_get_states'));
			add_action('wp_ajax_nopriv_de_get_states', array($this, 'de_get_states'));

			add_action('woocommerce_checkout_process', array($this, 'validate_fields_checkout'));

            add_filter( 'wp_authenticate_user', array($this,'extendon_authenticate_users'), 10, 3 );


            add_filter ( 'woocommerce_account_menu_items', array($this,'provide_access_to_user') );

            add_filter( 'woocommerce_registration_redirect', array($this,'user_status_at_registation') );

            add_action('woocommerce_before_customer_login_form' ,  array($this,'error_message_after_register_user'));


        }

        function error_message_after_register_user(){
		    if(isset($_GET['status'])) {
                ?>
                <div class="woocommerce">
                    <ul class="woocommerce-info flex-class" role="alert">
                        <li>
                            <strong><?php echo esc_html('Pending Status','eorf')?></strong> : <?php if($_GET['status'] == 'pending') {echo esc_attr(get_option('pending_user_msg'));} else if ($_GET['status'] == 'block'){echo esc_attr(get_option('block_user_msg'));}?>
                        </li>
						<a href="<?php echo site_url(); ?>/brand">Shop</a>
                    </ul>
                </div>
                <?php
				
            }
        }
        function user_status_at_registation(){
            $user = wp_get_current_user();
            $myaccount_page = get_option( 'woocommerce_myaccount_page_id' );
//            $roles = ( array ) $user->roles;
            $roles = get_user_meta(get_current_user_id(),'grant_user_role');
            $a = (get_option('registation_settings'));
            if($a['enable_user_role'] != 'disabled'){
                if( $a[$roles[0]] == 'pending') {
                    wp_logout();
                    return get_permalink( $myaccount_page).'?status=pending';
                }
                elseif ($a[$roles[0]] == 'block'){
                    wp_logout();
                    return get_permalink( $myaccount_page ).'?status=block';
                }
                else{
                    return get_permalink( $myaccount_page );
                }
            }
            else{
                if($a['enable_approvel'] == 'pending'){
                    wp_logout();
                    return get_permalink( $myaccount_page).'?status=pending';
                }
                else{
                    return get_permalink( $myaccount_page );
                }
            }

        }

        function provide_access_to_user( $menu_links ){
            if(is_user_logged_in()){
                $status = (get_user_meta(get_current_user_id(), 'user_status', false));
                if ($status[0] == 'limited'){
                    unset($menu_links['edit-address']); // Addresses
                    unset( $menu_links['edit-profile'] ); // Remove Payment Methods
                    unset( $menu_links['orders'] ); // Remove Orders
                    unset( $menu_links['downloads'] ); // Disable Downloads
                    unset( $menu_links['edit-account'] ); // Remove Account details tab
                }
            }
            return $menu_links;
        }


        function extendon_authenticate_users($username, $password) {
            // your code
            $status = (get_user_meta($username->ID, 'user_status', false));
            if ($status[0] == "pending") {
                remove_action('authenticate', 'wp_authenticate_username_password', 20);
                $user_message = new WP_Error('denied', __("<strong>ERROR</strong>: ".get_option('pending_user_msg'),'eorf'));
            } elseif ($status[0] == 'block') {
                remove_action('authenticate', 'wp_authenticate_username_password', 20);
                $user_message = new WP_Error('denied', __("<strong>ERROR</strong>: ".get_option('block_user_msg'),'eorf'    ));
            } else {
                return $username;
            }

            return $user_message;
        }

		public function add_endpoints() {
			add_rewrite_endpoint( 'edit-profile', EP_ROOT | EP_PAGES );
			flush_rewrite_rules();
		}
		
		public function add_query_vars( $vars ) { 
			$vars[] = 'edit-profile';
			return $vars;
		}
			
		public function endpoint_title( $title ) {
			global $wp_query;
			$is_endpoint = isset( $wp_query->query_vars[ 'edit-profile' ] );
			if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
				// New page title.
				$title = __( 'Profile Fields', 'eorf' );
				remove_filter( 'the_title', array( $this, 'endpoint_title' ) );
			}
			return $title;
		}

		public function new_menu_items( $items ) {
			// Remove the logout menu item.
			$logout = $items['customer-logout'];
			unset( $items['customer-logout'] );
			// Insert your custom endpoint.
			$items[ 'edit-profile' ] = __( 'Profile Fields', 'eorf' );
			// Insert back the logout item.
			$items['customer-logout'] = $logout;
			return $items;
		}

		public function endpoint_content() { ?>


			<?php if(get_query_var( 'edit-profile') == 'profile') { 

				$user_id = get_current_user_id();
				$this->eo_extra_registration_form_edit($user_id);

				?>

			<?php } else { ?>
			
			<div class="col2-set addresses">
				<header class="title">
					<h3><?php echo get_option('profile_title'); ?></h3>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-profile', 'profile' ) ); ?>" class="edit"><?php _e( 'Edit', 'eorf' ); ?></a>
					
				</header>
			</div>
			<table class="shop_table shop_table_responsive my_account_orders">
			<tbody>
			<?php 
				$user_id = get_current_user_id();
				$fields =  $this->get_fields();
				foreach ($fields as $field) {

					$check = get_user_meta( $user_id, $field->field_name, true );
					$label = $this->get_fieldByName($field->field_name);
					if($check!='') {

						$value = get_user_meta( $user_id, $field->field_name, true );
					?>
						<tr class="order" style="text-align:left">
							<?php if($label->field_type=='user_checkbox') { } else { ?>
								<td style="width:30%;"><b><?php echo $label->field_label; ?></b></td>
							<?php } ?>
							<td>
								<?php 
									if($label->field_type == 'file') { 
										$aa = $value;
										
										$ext = pathinfo($aa, PATHINFO_EXTENSION);
										if($ext == 'pdf' || $ext == 'PDF') { ?>
											<a href="<?php echo EORF_URL; ?>uploaded_img/<?php echo $value; ?>" target="_blank">
												<img src="<?php echo EORF_URL; ?>images/pdf.png" width="150" height="150" title="Click to View" />
											</a>
										<?php } else { ?>
											<img src="<?php echo $value; ?>" width="150" height="150" />
										<?php } ?>
										
									<?php } else if($label->field_type=='checkbox' && $value==1) { 
										echo "Yes";
									} else if($label->field_type=='checkbox' && $value==0) {
										echo "No";
									} else if($label->field_type=='user_checkbox') {

									} else if($label->field_type=='select' || $label->field_type=='radioselect') { 
										$meta = $this->get_OptionByid($value, $label->field_id);
										echo $meta->meta_value;
									} else if($label->field_type=='multiselect') {
										
										$multi = '';
										$mdata = explode(', ',$value);
										$prefix = '';
										for($a=0; $a < sizeof($mdata);$a++) {
											
											$meta = $this->get_OptionByid($mdata[$a], $label->field_id);
											$multi .= $prefix.$meta->meta_value;
    										$prefix = ', ';
										}

										echo $multi;

									} else if($label->field_type =='color_picker') { echo $value; ?>
										<div style="background-color: <?php echo $value; ?>; width: 50px; height: 50px;"></div>
									<?php } else {
										echo $value;
									}
									
								?>
							</td>
						</tr>
						
					<?php }
				}

			?>

			</tbody>
			</table>
			
		<?php } }

		

		public function front_scripts() {	
            
			// if (!$_GET['page']=='eo-registration-fields') {
				wp_enqueue_script( 'eorf-ui-script', '//code.jquery.com/ui/1.11.4/jquery-ui.js', array('jquery'), false );
			// }
        	wp_enqueue_script( 'eorf-front-timepicker', plugins_url( '/js/jquery-ui-timepicker-addon.js', __FILE__ ), array('jquery'), false );
        	wp_enqueue_script( 'eorf-front-jsssssss', plugins_url( '/js/script.js', __FILE__ ), array('jquery'), false );
        	wp_enqueue_style( 'eorf-front-css', plugins_url( '/css/eorf_style_front.css', __FILE__ ), false );
        	if(!is_admin()) {
        		wp_enqueue_style( 'eorf-UI-css', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css', false );
        	}

        	wp_enqueue_script( 'Google reCaptcha JS', '//www.google.com/recaptcha/api.js', false );

        	wp_enqueue_script( 'color_spectrum_js', plugins_url( '/js/color_spectrum.js', __FILE__ ), array('jquery'), false );
        	wp_enqueue_style( 'color_spectrum_css', plugins_url( '/css/color_spectrum.css', __FILE__ ), false );
        }

        function eo_extra_registration_form_start() { ?>

        	<h3><?php echo get_option('account_title'); ?></h3>
        	<?php 

        	$fields = $this->get_de_fields();
        	$clss = '';

        	foreach ($fields as $field) {
        		if($field->field_type == 'text' && $field->field_status == 'enabled') { ?>

        			<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
						<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
							<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
						</label>
						<input type="text" class="input-text" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="<?php if ( ! empty( $_POST[$field->field_name] ) ) esc_attr_e( $_POST[$field->field_name] ); ?>" placeholder="<?php echo $field->field_placeholder; ?>" />
						<?php if(isset($field->field_message) && $field->field_message!='') { ?>
							<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
						<?php } ?>
					</p>

        		<?php } else if($field->field_type == 'tel' && $field->field_status == 'enabled') { ?>

        			<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
						<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
							<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
						</label>
						<input type="tel" class="input-text" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="<?php if ( ! empty( $_POST[$field->field_name] ) ) esc_attr_e( $_POST[$field->field_name] ); ?>" placeholder="<?php echo $field->field_placeholder; ?>" />
						<?php if(isset($field->field_message) && $field->field_message!='') { ?>
							<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
						<?php } ?>
					</p>

        		<?php } else if($field->field_type == 'select' && $field->field_status == 'enabled') { ?>

        			<?php if($field->field_name == 'billing_country') { 

        				global $woocommerce;
						$countries_obj   = new WC_Countries();
						$countries   = $countries_obj->__get('countries');

						if ( ! empty( $_POST[$field->field_name] ) ) {
							$bcountry = $_POST[$field->field_name]; 
						} else {
							$bcountry = '';
						}
						
						

        			?>

	        			<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
							<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
								<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
							</label>

							<select class="js-example-basic-single" name="<?php echo $field->field_name; ?>" onchange="selectState(this.value);">
								<option value=""><?php echo _e('Select a country...', 'eorf'); ?></option>
								<?php foreach($countries as $key => $value) { ?>
									<option value="<?php echo $key; ?>" <?php echo selected($bcountry,$key); ?>><?php echo $value; ?></option>
								<?php } ?>
							</select>
							
							<?php if(isset($field->field_message) && $field->field_message!='') { ?>
								<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
							<?php } ?>
						</p>


						
					<?php } ?>

					<?php if($field->field_name == 'billing_state') { ?>

						<p id="statedrop" class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
							<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
								<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
							</label>

							<input type="text" class="input-text" name="<?php echo $field->field_name; ?>" id="statedrop" value="" placeholder="<?php echo $field->field_placeholder; ?>" />
							
							<?php if(isset($field->field_message) && $field->field_message!='') { ?>
								<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
							<?php } ?>
						</p>

					<?php } ?>

					<script type="text/javascript">
						jQuery(document).ready(function() {
						    jQuery('.js-example-basic-single').select2();
						});

						jQuery(document).ready(function() {

							<?php if($field->field_name == 'billing_country') { ?>

							<?php if(isset($_POST[$field->field_name]) && $_POST[$field->field_name]!='') { ?>
								var country = "<?php echo $_POST[$field->field_name]; ?>";
							<?php } else { ?>
								var country = "";
							<?php } ?>

							<?php if(isset($_POST[$field->field_name]) && $_POST[$field->field_name]!='') { ?>
								var se_state = "<?php echo $_POST['billing_state']; ?>";
							<?php } else { ?>
								var se_state = "";
							<?php } ?>

							

							var ajaxurl = "<?php echo admin_url( 'admin-ajax.php'); ?>";
							var fname = "<?php echo $field->field_name; ?>";
							var flabel = "<?php echo __($field->field_label, 'eorf'); ?>";
							var fmessage = "<?php echo __($field->field_message, 'eorf'); ?>";
							var required = "<?php echo $field->is_required; ?>";
							var width = "<?php echo $field->width; ?>";


							jQuery.ajax({
							type: 'POST',   // Adding Post method
							url: ajaxurl, // Including ajax file
								data: {"action": "de_get_states","country":country,"fname":fname,"flabel":flabel,"fmessage":fmessage,"required":required,"width":width,"se_state":se_state}, 
								success: function(data){ 
									jQuery('#statedrop').html(data);
								}
							}); 
							<?php } ?>
						});

						function selectState(country) { 
							var ajaxurl = "<?php echo admin_url( 'admin-ajax.php'); ?>";
							var fname = "<?php echo $field->field_name; ?>";
							var flabel = "<?php echo $field->field_label; ?>";
							var fmessage = "<?php echo $field->field_message; ?>";
							var required = "<?php echo $field->is_required; ?>";
							var width = "<?php echo $field->width; ?>";


							jQuery.ajax({
							type: 'POST',   // Adding Post method
							url: ajaxurl, // Including ajax file
								data: {"action": "de_get_states","country":country,"fname":fname,"flabel":flabel,"fmessage":fmessage,"required":required,"width":width}, 
								success: function(data){ 
									jQuery('#statedrop').html(data);
								}
							});  
						}

					</script>

        		<?php } ?>






        	<?php }
//            $check_enable_userrole = get_option('registation_settings');
//        	if($check_enable_userrole['enable_user_role'] !='disabled'){
//                global $wp_roles;
//                $registation_settings = get_option('registation_settings');
//                $all_roles = $registation_settings['frontuserrole'];
//                ?>
<!--                  <p class="form-row">-->
<!--                    <label>User Role</label>-->
<!--                    <select class="role-select" name="selectuserrole">-->
<!--                        --><?php
//
//                        foreach ($all_roles as $key){
//                            ?>
<!--                            <option value="--><?php //echo $key;?><!--"  --><?php //if(in_array($key, $registation_settings['frontuserrole'])) echo "selected";?><!-- >--><?php //_e( preg_replace('/[^A-Za-z0-9\-]/', ' ', $key), 'eorf'); ?><!--</option>-->
<!--                            --><?php
//                        }
//                        ?>
<!--                    </select>-->
<!--                </p>-->
<!--                --><?php
//            }
       }
		
      function eo_extra_registration_form_end() {
//		    create userrole dropdown
           $check_enable_userrole = get_option('registation_settings');
           if($check_enable_userrole['enable_user_role'] !='disabled'){
               global $wp_roles;
               $registation_settings = get_option('registation_settings');
               $all_roles = $registation_settings['frontuserrole'];
               ?>
               <p class="form-row">
                   <label>User Role</label>
                   <select class="role-select" name="selectuserrole">
                       <?php

                       foreach ($all_roles as $key){
                           ?>
                           <option value="<?php echo $key;?>"  <?php if(in_array($key, $registation_settings['frontuserrole'])) echo "selected";?> ><?php _e( preg_replace('/[^A-Za-z0-9\-]/', ' ', $key), 'eorf'); ?></option>
                           <?php
                       }
                       ?>
                   </select>
               </p>
               <?php
           }
           //		    create userrole dropdown

           ?>

        	<h3><?php echo __(get_option('profile_title'), 'eorf'); ?></h3>
        	<input type="hidden" name="pageID" value="<?php echo get_queried_object_id(); ?>" />
        	<?php 
        	$fields = $this->get_fields();
        	$clss = '';
        	foreach ($fields as $field) { 
        		if($field->showif == 'Show') {
        			$clss = 'hide';
        		} elseif($field->showif == 'Hide') {
        			$clss = 'show';
        		} else {
        			$clss = '';
        		}

        		if($field->field_type == 'text' && $field->is_hide == 0) {
        	?>
				
	        	<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					<input type="text" class="input-text" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="<?php if ( ! empty( $_POST[$field->field_name] ) ) esc_attr_e( $_POST[$field->field_name] ); ?>" placeholder="<?php echo $field->field_placeholder; ?>" />
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
				</p>
	        		
	        	<?php } else if($field->field_type == 'textarea' && $field->is_hide == 0) { ?>

	        	<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?>  <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					<textarea name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" class="input-text" cols="5" rows="2" placeholder="<?php echo $field->field_placeholder; ?>"><?php if ( ! empty( $_POST[$field->field_name] ) ) esc_attr_e( $_POST[$field->field_name] ); ?></textarea>
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
				</p>

	        	<?php } else if($field->field_type == 'select' && $field->is_hide == 0) { ?>

	        	<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?>  <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					<select name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>">
					
					<?php $options = $this->getSelectOptions($field->field_id);
						foreach($options as $option) {
					?>
						<?php if ( ! empty( $_POST[$field->field_name] ) ) { ?>
							<option value="<?php echo $option->meta_key; ?>" <?php echo selected($_POST[$field->field_name], $option->meta_key); ?>><?php echo $option->meta_value; ?></option>
						<?php } else { ?>
							<option value="<?php echo $option->meta_key; ?>"><?php echo __($option->meta_value, 'eorf'); ?></option>
						<?php } ?>

					<?php } ?>

					</select>
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
					
				</p>

	        	<?php } else if($field->field_type == 'multiselect' && $field->is_hide == 0) { ?>

        		<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?>  <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					<select style="height:150px;" multiple="true"  name="<?php echo $field->field_name; ?>[]" id="<?php echo $field->field_name; ?>">
					
					<?php $options = $this->getSelectOptions($field->field_id);
						foreach($options as $option) {
					?>
						
						<?php if ( ! empty( $_POST[$field->field_name] ) ) { ?>
							<option value="<?php echo $option->meta_key; ?>" <?php if(in_array($option->meta_key, $_POST[$field->field_name])) { echo "selected"; } ?>><?php echo $option->meta_value; ?></option>
						<?php } else { ?>
							<option value="<?php echo $option->meta_key; ?>"><?php echo __($option->meta_value, 'eorf'); ?></option>
						<?php } ?>

					<?php } ?>

					</select>
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
					
				</p>

	        	<?php } else if($field->field_type == 'checkbox' && $field->is_hide == 0) { ?>

	        	<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?>  <?php echo $clss; ?>">
					
					<?php if ( ! empty( $_POST[$field->field_name] ) ) { ?>
					
						<input type="checkbox" id="c<?php echo $field->field_name; ?>" name="<?php echo $field->field_name; ?>" value="1" class="input-checkbox" <?php echo checked($_POST[$field->field_name], 1); ?>>
					<?php } else { ?>
						<input type="checkbox" id="c<?php echo $field->field_name; ?>" name="<?php echo $field->field_name; ?>" value="1" class="input-checkbox">
					<?php } ?>

					<b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
				</p>

	        	<?php } else if($field->field_type == 'user_checkbox') { ?>

	        	<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?>  <?php echo $clss; ?>">
					
					<?php if ( ! empty( $_POST[$field->field_name] ) ) { ?>
					
						<input type="checkbox" id="c<?php echo $field->field_name; ?>" name="<?php echo $field->field_name; ?>" value="1" class="input-checkbox" <?php echo checked($_POST[$field->field_name], 1); ?>>
					<?php } else { ?>
						<input type="checkbox" id="c<?php echo $field->field_name; ?>" name="<?php echo $field->field_name; ?>" value="1" class="input-checkbox">
					<?php } ?>
					<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					<?php _e( $field->user_checkbox_label, 'eorf' ); ?> 
						
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo $field->field_message; ?></span>
					<?php } ?>
				</p>


	        	<?php } else if($field->field_type == 'radioselect' && $field->is_hide == 0) { ?>

	        	<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?>  <?php echo $clss; ?>">
	        		<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					<?php $options = $this->getSelectOptions($field->field_id);
						foreach($options as $option) {
					?>

					<?php if ( ! empty( $_POST[$field->field_name] ) ) { ?>
						<input type="radio" name="<?php echo $field->field_name; ?>" value="<?php echo $option->meta_key; ?>" class="input-checkbox"  <?php echo checked($_POST[$field->field_name], $option->meta_key); ?>> <?php echo $option->meta_value; ?>
					<?php } else { ?>
						<input type="radio" name="<?php echo $field->field_name; ?>" value="<?php echo $option->meta_key; ?>" class="input-checkbox"> <?php echo $option->meta_value; ?>
					<?php } ?>
					
					<?php } ?>
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
	        	</p>

	        	<?php } else if($field->field_type == 'datepicker' && $field->is_hide == 0) { ?>

	        	<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?>  <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					<input type="text" class="input-text datepick" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="<?php if ( ! empty( $_POST[$field->field_name] ) ) esc_attr_e( $_POST[$field->field_name] ); ?>" placeholder="<?php echo $field->field_placeholder; ?>" />
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
				</p>

	        	<?php } else if($field->field_type == 'timepicker' && $field->is_hide == 0) { ?>

	        	<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?>  <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					<input type="text" class="input-text timepick" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="<?php if ( ! empty( $_POST[$field->field_name] ) ) esc_attr_e( $_POST[$field->field_name] ); ?>" placeholder="<?php echo $field->field_placeholder; ?>" />
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
				</p>

	        	<?php } else if($field->field_type == 'password' && $field->is_hide == 0) { ?>

	        	<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?>  <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					<input type="password" class="input-text" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="<?php if ( ! empty( $_POST[$field->field_name] ) ) esc_attr_e( $_POST[$field->field_name] ); ?>" placeholder="<?php echo $field->field_placeholder; ?>" />
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
				</p>

	        	<?php } else if($field->field_type == 'file' && $field->is_hide == 0) { ?>

	        	<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?>  <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					<input type="file" class="input-text" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="" placeholder="<?php echo $field->field_placeholder; ?>" />
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
					<span style="width:100%; display:none; color:red; float: left" id = "typem<?php echo $field->field_name; ?>">
						File type not allowed!
					</span>
					<span style="width:100%; display:none; color:red; float: left" id = "sizem<?php echo $field->field_name; ?>">
						File size exceeded the maximum size!
					</span>
					<img style="display:none;" id="im<?php echo $field->field_name; ?>" src="#" alt="" width="150" />

					<script type="text/javascript">

							function readURL(input,id) { 
								
								jQuery('#typem'+id).hide();
								jQuery('#sizem'+id).hide();
								jQuery('#im'+id).attr('src', '');
						        if (input.files && input.files[0]) { 

						        	if(!(input.files[0].type == 'application/pdf' || input.files[0].type == 'image/jpeg' || input.files[0].type == 'image/gif' || input.files[0].type == 'image/png')) {
						        		jQuery('#typem'+id).show();
						        		jQuery("input[name=register]").prop('disabled', true);
						        	} else if(input.files[0].size > 49000000) {

						        		jQuery('#sizem'+id).show();
						        		jQuery("input[name=register]").prop('disabled', true);

						        	} else {

						        		jQuery("input[name=register]").prop('disabled', false);
						        		jQuery('#im'+id).show();
							            var reader = new FileReader();
							            
							            reader.onload = function (e) {
							                jQuery('#im'+id).attr('src', e.target.result);
							            }
							            
							            reader.readAsDataURL(input.files[0]);
						       	 	}
						        }
						    }
						    
						    jQuery("#<?php echo $field->field_name; ?>").change(function(){
						        readURL(this,'<?php echo $field->field_name; ?>');
						    });

					</script>
				</p>

	        	<?php } elseif($field->field_type == 'numeric' && $field->is_hide == 0) {  ?>

	        		<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
						<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
							<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
						</label>
						<input type="number" class="input-text" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="<?php if ( ! empty( $_POST[$field->field_name] ) ) esc_attr_e( $_POST[$field->field_name] ); ?>" placeholder="<?php echo $field->field_placeholder; ?>" />
						<?php if(isset($field->field_message) && $field->field_message!='') { ?>
							<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
						<?php } ?>
					</p>

	        	<?php } else if($field->field_type == 'google_captcha') { ?>
	        		<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
						<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>		
							 <span class="required">*</span>
						</label>
						<div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
						<?php if(isset($field->field_message) && $field->field_message!='') { ?>
							<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
						<?php } ?>

						
					</p>

					

	        	<?php } else if($field->field_type == 'color_picker' && $field->is_hide == 0) { ?>
	        		<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
						<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
							<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
						</label>
						<input type="text" class="input-text color_sepctrum" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="<?php if ( ! empty( $_POST[$field->field_name] ) ) esc_attr_e( $_POST[$field->field_name] ); ?>" placeholder="<?php echo $field->field_placeholder; ?>" />
						<?php if(isset($field->field_message) && $field->field_message!='') { ?>
							<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
						<?php } ?>
					</p>
	        	<?php } ?> 

	        	<script type="text/javascript">
	        		jQuery(document).ready(function($) { 
	        			
						<?php
							if($field->showif == 'Show') { ?>

								<?php if($field->ccondition == 'is not empty') { ?>
									$("#<?php echo $field->cfield ?>").change(function(){
										$("#a<?php echo $field->field_name ?>").show();
									});

									$("#a<?php echo $field->field_name ?>").show();

									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }

										if(aa != '') {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	}
								 	});

								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	}


								<?php } else if($field->ccondition == 'is equal to') { ?> 
									$("#<?php echo $field->cfield ?>").change(function(){ 
									 	if($("#<?php echo $field->cfield ?>").val() == "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	}
									});

									if($("#<?php echo $field->cfield ?>").val() == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	}

									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }


										if(aa == "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	}
								 	});

								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	}

									

								<?php } else if($field->ccondition == 'is not equal to') { ?>

									$("#<?php echo $field->cfield ?>").change(function(){
									 	if($("#<?php echo $field->cfield ?>").val() != "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	}
									});

									if($("#<?php echo $field->cfield ?>").val() != "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	}

									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }


										if(aa != "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	}
								 	});


								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa != "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	}


								<?php } else if($field->ccondition == 'is checked') { ?>

									$("#c<?php echo $field->cfield ?>").change(function(){ 

										if ($(this).is(':checked')) {
								           $("#a<?php echo $field->field_name ?>").show();
								        } else {
								        	$("#a<?php echo $field->field_name ?>").hide();
								        }
									});

									if ($("#c<?php echo $field->cfield ?>").is(':checked')) { 
										$("#a<?php echo $field->field_name ?>").show();
									} else {
										$("#a<?php echo $field->field_name ?>").hide();
									}



								<?php } ?>



			        			
			        		<?php } elseif($field->showif == 'Hide') { ?>

			        			<?php if($field->ccondition == 'is not empty') { ?>
									$("#<?php echo $field->cfield ?>").change(function(){
										$("#a<?php echo $field->field_name ?>").hide();
									});

									$("#a<?php echo $field->field_name ?>").hide();

									
									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }

										if(aa != '') {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	}
								 	});


								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	}


								<?php } else if($field->ccondition == 'is equal to') { ?>
									$("#<?php echo $field->cfield ?>").change(function(){
									 	if($("#<?php echo $field->cfield ?>").val() == "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	}
									});

									if($("#<?php echo $field->cfield ?>").val() == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	}

									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }


										if(aa == "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	}
								 	});

								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	}


									

								<?php } else if($field->ccondition == 'is not equal to') { ?>

									$("#<?php echo $field->cfield ?>").change(function(){
									 	if($("#<?php echo $field->cfield ?>").val() != "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	}
									});

									if($("#<?php echo $field->cfield ?>").val() != "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	}


									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }


										if(aa != "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	}
								 	});

								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa != "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	}


									


								<?php } else if($field->ccondition == 'is checked') { ?>

									$("#c<?php echo $field->cfield ?>").change(function(){ 

										if ($(this).is(':checked')) {
								           $("#a<?php echo $field->field_name ?>").hide();
								        } else {
								        	$("#a<?php echo $field->field_name ?>").show();
								        }
									});


									if ($("#c<?php echo $field->cfield ?>").is(':checked')) { 
										$("#a<?php echo $field->field_name ?>").hide();
									} else {
										$("#a<?php echo $field->field_name ?>").show();
									}



								<?php } ?>
			        			
			        		<?php } else { ?>
			        			
			        		<?php } ?>
						
					});
	        	</script>

	        	<script>
					jQuery(".color_sepctrum").spectrum({
					    color: "#f00",
					    preferredFormat: "hex",
					});
				</script>

        	<?php } ?>

        	
        	
       <?php }

       function eo_extra_registration_form_edit($user_id) {  ?>

        	<h3><?php echo __(get_option('profile_title'), 'eorf'); ?></h3>
        	<form method="post" enctype="multipart/form-data" id="edform">
        	<?php $this->eorf_show_error_messages(); ?>

        	<?php 

        	$fields = $this->get_fields();
        	$clss = '';
        	$i=0;
        	foreach ($fields as $field) { 

        	if($field->showif == 'Show') {
        			$clss = 'hide';
        		} elseif($field->showif == 'Hide') {
        			$clss = 'show';
        		} else {
        			$clss = '';
        		}
        		
        	$value = get_user_meta( $user_id, $field->field_name, true );
        		
        		if($field->field_type == 'text' && $field->is_hide == 0) {
        	?>

	        	<p id="a<?php echo $field->field_name; ?>"  class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					<input type="text" class="input-text" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="<?php echo $value; ?>" placeholder="<?php echo $field->field_placeholder; ?>" />
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
				</p>
	        		
	        	<?php } else if($field->field_type == 'textarea' && $field->is_hide == 0) { ?>

	        	<p id="a<?php echo $field->field_name; ?>"  class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					<textarea name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" class="input-text" cols="5" rows="2" placeholder="<?php echo $field->field_placeholder; ?>"><?php echo $value; ?></textarea>
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo $field->field_message; ?></span>
					<?php } ?>
				</p>

	        	<?php } else if($field->field_type == 'select' && $field->is_hide == 0) { ?>

	        	<p id="a<?php echo $field->field_name; ?>"  class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					<select name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>">
					<?php $options = $this->getSelectOptions($field->field_id);
						foreach($options as $option) {
					?>
						<option value="<?php echo $option->meta_key; ?>"  <?php if($option->meta_key == $value) { echo "selected"; } ?>>
							<?php echo __($option->meta_value, 'eorf'); ?>
						</option>

					<?php } ?>

					</select>
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
				</p>

	        	<?php } else if($field->field_type == 'multiselect' && $field->is_hide == 0) { ?>

        		<p id="a<?php echo $field->field_name; ?>"  class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					
					<select style="height:150px;" multiple="true" name="<?php echo $field->field_name; ?>[]" id="<?php echo $field->field_name; ?>">
					<?php $options = $this->getSelectOptions($field->field_id);
						
						foreach($options as $option) {
						$mdata = explode(', ',$value);

						
					?>
						<option value="<?php echo $option->meta_key; ?>"  <?php if(in_array($option->meta_key, $mdata)) { echo "selected"; } ?>>
							<?php echo __($option->meta_value, 'eorf'); ?>
						</option>
						

					<?php } ?>

					</select>
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
				</p>

	        	<?php } else if($field->field_type == 'checkbox' && $field->is_hide == 0) { ?>

	        	<p id="a<?php echo $field->field_name; ?>"  class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
					
					<input type="checkbox" id="c<?php echo $field->field_name; ?>" name="<?php echo $field->field_name; ?>" value="1" <?php if($value == 1) { echo "checked"; } ?> class="input-checkbox">

					<b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>

						<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
					
				</p>

	        	<?php } else if($field->field_type == 'radioselect' && $field->is_hide == 0) { ?>

	        	<p id="a<?php echo $field->field_name; ?>"  class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
	        		<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					<?php $options = $this->getSelectOptions($field->field_id);
						foreach($options as $option) {
					?>

					<input type="radio" name="<?php echo $field->field_name; ?>" value="<?php echo $option->meta_key; ?>" <?php if($option->meta_key == $value) { echo "checked"; } ?> class="input-checkbox"> <?php echo __($option->meta_value, 'eorf'); ?>

					<?php } ?>
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
	        	</p>

	        	<?php } else if($field->field_type == 'datepicker' && $field->is_hide == 0) { ?>

	        	<p id="a<?php echo $field->field_name; ?>"  class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					<input type="text" class="input-text datepick" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="<?php echo $value; ?>" placeholder="<?php echo $field->field_placeholder; ?>" />
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
				</p>

	        	<?php } else if($field->field_type == 'timepicker' && $field->is_hide == 0) { ?>

	        	<p id="a<?php echo $field->field_name; ?>"  class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					<input type="text" class="input-text timepick" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="<?php echo $value; ?>" placeholder="<?php echo $field->field_placeholder; ?>" />
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
				</p>

	        	<?php } else if($field->field_type == 'password' && $field->is_hide == 0) { ?>

	        	<p id="a<?php echo $field->field_name; ?>"  class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
					</label>
					<input type="password" class="input-text" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="<?php echo $value; ?>" placeholder="<?php echo $field->field_placeholder; ?>" />
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
				</p>

	        	<?php } else if($field->field_type == 'file' && $field->is_hide == 0) { ?>

	        	<p id="a<?php echo $field->field_name; ?>"  class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><?php _e('Current', 'eorf') ?> <?php echo __($field->field_label, 'eorf'); ?></label>
					
					<?php 
					
						$aa = $value;
						$ext = pathinfo($aa, PATHINFO_EXTENSION);
						if($ext == 'pdf' || $ext == 'PDF') { ?>
							<a href="<?php echo $value; ?>" target="_blank">
								<img src="<?php echo EORF_URL; ?>images/pdf.png" width="150" height="150" title="Click to View" />
							</a>
						<?php } else { ?>
							<img src="<?php echo $value; ?>" width="150" height="150" />
						<?php } ?>

					<input type="hidden" class="input-text" value="<?php echo $value; ?>" id="curr_<?php echo $field->field_name; ?>" name="curr_<?php echo $field->field_name; ?>">
					
				</p>

	        	<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
					<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
						
					</label>
					
					<input type="file" class="input-text" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="<?php if ( ! empty( $_POST[$field->field_name] ) ) esc_attr_e( $_POST[$field->field_name] ); ?>" placeholder="<?php echo $field->field_placeholder; ?>" />
					<input type="hidden" name="<?php echo $field->field_name; ?>" id="policy_image" >
					 <script type="text/javascript">
        	 
         var input = document.getElementById("<?php echo $field->field_name; ?>"); 
			
          formdata = formnames = [], loadfiles = [];

			if(window.FormData) {
				formdata = new FormData();
				
			}
                  
                      	input.addEventListener("change", function (evt) {
                      		
						var file_data = $("#<?php echo $field->field_name; ?>")[0].files[0];
               
					formdata.append("<?php echo $field->field_name; ?>", file_data);
					
                 

			$.ajax({
			    url         : "<?php echo admin_url('/admin-ajax.php?action=save_upload_file');  ?>",
			    cache       : false,
			    contentType : false,
			    processData : false,
			    data        : formdata,                         
			    type        : 'post',
			    success     : function(output){
			      
			       document.getElementById("curr_<?php echo $field->field_name; ?>").value = output;
			      
			    }
			});

			}, false);



						</script>
					<?php if(isset($field->field_message) && $field->field_message!='') { ?>
						<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
					<?php } ?>
					
					<span style="width:100%; display:none; color:red; float: left" id = "typem<?php echo $field->field_name; ?>">
						<?php _e('File type not allowed!', 'eorf') ?>
					</span>
					<span style="width:100%; display:none; color:red; float: left" id = "sizem<?php echo $field->field_name; ?>">
						<?php _e('File size exceeded the maximum size!', 'eorf') ?>
					</span>
					<img style = "display:none;" id="im<?php echo $field->field_name; ?>" src="#" alt="" width="150" />

					<script type="text/javascript">


							function readURL(input,id) { 
								
								jQuery('#typem'+id).hide();
								jQuery('#sizem'+id).hide();
								jQuery('#im'+id).attr('src', '');
								
						        if (input.files && input.files[0]) { 

						        	if(!(input.files[0].type == 'application/pdf' || input.files[0].type == 'image/jpeg' || input.files[0].type == 'image/gif' || input.files[0].type == 'image/png')) {
						        		jQuery('#typem'+id).show();
						        		jQuery("input[name=save_profile]").prop('disabled', true);
						        	} else if(input.files[0].size > 49000000) {

						        		jQuery('#sizem'+id).show();
						        		jQuery("input[name=save_profile]").prop('disabled', true);

						        	} else {

						        		jQuery("input[name=save_profile]").prop('disabled', false);
						        		jQuery('#im'+id).show();
							            var reader = new FileReader();
							            
							            reader.onload = function (e) {
							                jQuery('#im'+id).attr('src', e.target.result);
							            }
							            
							            reader.readAsDataURL(input.files[0]);
						       	 	}
						        }
						    }
						    
						    jQuery("#<?php echo $field->field_name; ?>").change(function(){
						        readURL(this,'<?php echo $field->field_name; ?>');
						    });

					</script>

				</p>

				

	        	<?php } else if($field->field_type == 'numeric' && $field->is_hide == 0) { ?>
	        		<p id="a<?php echo $field->field_name; ?>"  class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
						<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
							<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
						</label>
						<input type="number" class="input-text" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="<?php echo $value; ?>" placeholder="<?php echo $field->field_placeholder; ?>" />
						<?php if(isset($field->field_message) && $field->field_message!='') { ?>
							<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
						<?php } ?>
					</p>
	        	<?php } else if($field->field_type == 'google_captcha') { ?>
	        		<p id="a<?php echo $field->field_name; ?>" class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
						<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>		
							 <span class="required">*</span>
						</label>
						<div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
						<?php if(isset($field->field_message) && $field->field_message!='') { ?>
							<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
						<?php } ?>

						
					</p>

					

	        	<?php } else if($field->field_type == 'color_picker' && $field->is_hide == 0) { ?>
	        		<p id="a<?php echo $field->field_name; ?>"  class="form-row <?php echo $field->width; ?> <?php echo $clss; ?>">
						<label for="<?php echo $field->field_name; ?>"><b><?php _e( $field->field_label, 'eorf' ); ?></b>
							<?php if($field->is_required == 1) { ?> <span class="required">*</span> <?php } ?>
						</label>
						<input type="text" class="input-text color_spectrum<?php echo $i; ?>" name="<?php echo $field->field_name; ?>" id="<?php echo $field->field_name; ?>" value="<?php echo $value; ?>" placeholder="<?php echo $field->field_placeholder; ?>" />
						<?php if(isset($field->field_message) && $field->field_message!='') { ?>
							<span style="width:100%;float: left"><?php echo __($field->field_message, 'eorf'); ?></span>
						<?php } ?>
					</p>

					<script>
						
					jQuery(".color_spectrum<?php echo $i; ?>").spectrum({
					    color: "<?php echo $value; ?>",
					    preferredFormat: "hex",
					});

					</script>
	        	<?php $i++;} ?>

	        	<script type="text/javascript">
	        		jQuery(document).ready(function($) { 
	        			
						<?php
							if($field->showif == 'Show') { ?>

								<?php if($field->ccondition == 'is not empty') { ?>
									$("#<?php echo $field->cfield ?>").change(function(){
										$("#a<?php echo $field->field_name ?>").show();
									});

									$("#a<?php echo $field->field_name ?>").show();

									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }

										if(aa != '') {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	}
								 	});

								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	}


								<?php } else if($field->ccondition == 'is equal to') { ?>
									$("#<?php echo $field->cfield ?>").change(function(){
									 	if($("#<?php echo $field->cfield ?>").val() == "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	}
									});



									if($("#<?php echo $field->cfield ?>").val() == "<?php echo $field->ccondition_value; ?>") {
										
								 		$("#a<?php echo $field->field_name ?>").show();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	}

									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }


										if(aa == "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	}
								 	});

								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	}

									

								<?php } else if($field->ccondition == 'is not equal to') { ?>

									$("#<?php echo $field->cfield ?>").change(function(){
									 	if($("#<?php echo $field->cfield ?>").val() != "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	}
									});

									if($("#<?php echo $field->cfield ?>").val() != "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	}

									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }


										if(aa != "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	}
								 	});


								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa != "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	}


								<?php } else if($field->ccondition == 'is checked') { ?>

									$("#c<?php echo $field->cfield ?>").change(function(){ 

										if ($(this).is(':checked')) {
								           $("#a<?php echo $field->field_name ?>").show();
								        } else {
								        	$("#a<?php echo $field->field_name ?>").hide();
								        }
									});

									if ($("#c<?php echo $field->cfield ?>").is(':checked')) { 
										$("#a<?php echo $field->field_name ?>").show();
									} else {
										$("#a<?php echo $field->field_name ?>").hide();
									}



								<?php } ?>



			        			
			        		<?php } elseif($field->showif == 'Hide') { ?>

			        			<?php if($field->ccondition == 'is not empty') { ?>
									$("#<?php echo $field->cfield ?>").change(function(){
										$("#a<?php echo $field->field_name ?>").hide();
									});

									$("#a<?php echo $field->field_name ?>").hide();

									
									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }

										if(aa != '') {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	}
								 	});


								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	}


								<?php } else if($field->ccondition == 'is equal to') { ?>
									$("#<?php echo $field->cfield ?>").change(function(){
									 	if($("#<?php echo $field->cfield ?>").val() == "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	}
									});

									if($("#<?php echo $field->cfield ?>").val() == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	}

									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }


										if(aa == "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	}
								 	});

								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa == "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	}


									

								<?php } else if($field->ccondition == 'is not equal to') { ?>

									$("#<?php echo $field->cfield ?>").change(function(){
									 	if($("#<?php echo $field->cfield ?>").val() != "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	}
									});

									if($("#<?php echo $field->cfield ?>").val() != "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	}


									$('input:radio').change(function() {

										if ($(this).is(':checked')) {
											var aa = $(this).val();
								        }


										if(aa != "<?php echo $field->ccondition_value; ?>") {
									 		$("#a<?php echo $field->field_name ?>").hide();
									 	} else {
									 		$("#a<?php echo $field->field_name ?>").show();
									 	}
								 	});

								 	var aa = $("input[type='radio']:checked").val();
								 	if(aa != "<?php echo $field->ccondition_value; ?>") {
								 		$("#a<?php echo $field->field_name ?>").hide();
								 	} else {
								 		$("#a<?php echo $field->field_name ?>").show();
								 	}


									


								<?php } else if($field->ccondition == 'is checked') { ?>

									$("#c<?php echo $field->cfield ?>").change(function(){ 

										if ($(this).is(':checked')) {
								           $("#a<?php echo $field->field_name ?>").hide();
								        } else {
								        	$("#a<?php echo $field->field_name ?>").show();
								        }
									});


									if ($("#c<?php echo $field->cfield ?>").is(':checked')) { 
										$("#a<?php echo $field->field_name ?>").hide();
									} else {
										$("#a<?php echo $field->field_name ?>").show();
									}



								<?php } ?>
			        			
			        		<?php } else { ?>
			        			
			        		<?php } ?>
						
					});
	        	</script>



	        	<?php if($field->is_readonly == 1) { ?>
	        		<script type="text/javascript">document.getElementById("<?php echo $field->field_name ?>").disabled = true; </script>
	        	<?php } ?>

        	<?php } ?>

        	<!--<input type="hidden" name="action" value="SubmitRegForm" />-->
			<input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />

			<p>
				<input type="button" value="<?php _e('Save Changes', 'eorf') ?>" name="save_profile" class="button" onclick="saveProfile();">
				

			</p>

        </form>


        <script type="text/javascript">
        	
        	function saveProfile() {

        		var data2 = jQuery('#edform').serialize();
				var ajaxurl = '<?php echo admin_url( 'admin-ajax.php'); ?>';

				jQuery.ajax({
				    type: 'POST',
				    url: ajaxurl,
				    data: data2 + '&action=save_all_eddata',
				    success: function(res) {
				        window.location.reload(true);
				        console.log(res);

				    }
				});
	
        	}

        </script>
        	
       <?php }



       function eo_validate_extra_register_fields($username, $email, $validation_errors) {



       		global $woocommerce;

       		
			if(isset($_POST['pageID']) && $_POST['pageID']!='') {
				$current_page = $_POST['pageID'];
			} else {
				$current_page = 0;
			}
       		$check_page = wc_get_page_id( 'checkout' );
       		


       		//Default Fields
        	//First Name
        	$fieldData = $this->getDefaultField('first_name');

        	if( isset( $_POST['first_name'] ) && empty( $_POST['first_name'] ) && $fieldData->field_status == 'enabled' && $fieldData->is_required == 1) {
					$validation_errors->add( $fieldData->field_name.'_error', __($fieldData->field_label." ", 'eorf').__("is required!", "eorf"  ));
			}

			//Last Name
        	$fieldData = $this->getDefaultField('last_name');

        	if( isset( $_POST['last_name'] ) && empty( $_POST['last_name'] ) && $fieldData->field_status == 'enabled' && $fieldData->is_required == 1) {
					$validation_errors->add( $fieldData->field_name.'_error', __($fieldData->field_label." ", 'eorf').__("is required!", "eorf"  ));
			}

			//Company
        	$fieldData = $this->getDefaultField('billing_company');

        	if( isset( $_POST['billing_company'] ) && empty( $_POST['billing_company'] ) && $fieldData->field_status == 'enabled' && $fieldData->is_required == 1) {
					$validation_errors->add( $fieldData->field_name.'_error', __($fieldData->field_label." ", 'eorf').__("is required!", "eorf"  ));
			}

			//Country
        	$fieldData = $this->getDefaultField('billing_country');

        	if( isset( $_POST['billing_country'] ) && empty( $_POST['billing_country'] ) && $fieldData->field_status == 'enabled' && $fieldData->is_required == 1) {
					$validation_errors->add( $fieldData->field_name.'_error', __($fieldData->field_label." ", 'eorf').__("is required!", "eorf"  ));
			}

			//Address 1
        	$fieldData = $this->getDefaultField('billing_address_1');

        	if( isset( $_POST['billing_address_1'] ) && empty( $_POST['billing_address_1'] ) && $fieldData->field_status == 'enabled' && $fieldData->is_required == 1) {
					$validation_errors->add( $fieldData->field_name.'_error', __($fieldData->field_label." ", 'eorf').__("is required!", "eorf"  ));
			}

			//Address 2
        	$fieldData = $this->getDefaultField('billing_address_2');

        	if( isset( $_POST['billing_address_2'] ) && empty( $_POST['billing_address_2'] ) && $fieldData->field_status == 'enabled' && $fieldData->is_required == 1) {
					$validation_errors->add( $fieldData->field_name.'_error', __($fieldData->field_label." ", 'eorf').__("is required!", "eorf"  ));
			}

			//City
        	$fieldData = $this->getDefaultField('billing_city');

        	if( isset( $_POST['billing_city'] ) && empty( $_POST['billing_city'] ) && $fieldData->field_status == 'enabled' && $fieldData->is_required == 1) {
					$validation_errors->add( $fieldData->field_name.'_error', __($fieldData->field_label." ", 'eorf').__("is required!", "eorf"  ));
			}

			//State
        	$fieldData = $this->getDefaultField('billing_state');

        	if( isset( $_POST['billing_state'] ) && empty( $_POST['billing_state'] ) && $fieldData->field_status == 'enabled' && $fieldData->is_required == 1) {
					$validation_errors->add( $fieldData->field_name.'_error', __($fieldData->field_label." ", 'eorf').__("is required!", "eorf"  ));
			}


			//PostCode
        	$fieldData = $this->getDefaultField('billing_postcode');

        	if( isset( $_POST['billing_postcode'] ) && empty( $_POST['billing_postcode'] ) && $fieldData->field_status == 'enabled' && $fieldData->is_required == 1) {
					$validation_errors->add( $fieldData->field_name.'_error', __($fieldData->field_label." ", 'eorf').__("is required!", "eorf"  ));
			}

			//Phone
        	$fieldData = $this->getDefaultField('billing_phone');

        	if( isset( $_POST['billing_phone'] ) && empty( $_POST['billing_phone'] ) && $fieldData->field_status == 'enabled' && $fieldData->is_required == 1) {
					$validation_errors->add( $fieldData->field_name.'_error', __($fieldData->field_label." ", 'eorf').__("is required!", "eorf"  ));
			}





       		$fields = $this->get_fields();
       		
        	foreach ($fields as $field) { 

        		if ( isset( $_POST[$field->field_name] ) && empty( $_POST[$field->field_name] ) && ($field->is_required == 1 && $field->showif == '') ) {

						
						$validation_errors->add( $field->field_name.'_error', __($field->field_label." ", 'eorf').__("is required!", "eorf"  ));
				}


				if ( isset( $_POST[$field->field_name] ) && empty( $_POST[$field->field_name] ) && ($field->is_required == 1 && $field->showif != '') ) {

					
					if($field->ccondition!='is checked') {

						if($_POST[$field->cfield] == $field->ccondition_value) {
	        				
	        				$validation_errors->add( $field->field_name.'_error', __($field->field_label." ", 'eorf').__("is required!", "eorf"  ));

	        			}
        			} 

        			if($field->ccondition == 'is checked' && $_POST[$field->cfield] == 1) {

        				$validation_errors->add( $field->field_name.'_error', __($field->field_label." ", 'eorf').__("is required!", "eorf"  ));

        			}
				}



        	}

        	foreach ($fields as $field) { 

        		if($field->field_type == 'multiselect') {
        			
        			if(!array_key_exists($field->field_name, $_POST)) {
	        			if($field->is_required == 1 && $field->is_hide == 0) {
	        				$validation_errors->add( $field->field_name.'_error', __($field->field_label." ", 'eorf').__("is required!", "eorf"  ));
	        			}
	        		}
        		}

        		if($field->field_type == 'google_captcha'  && $current_page != 0) {
        			
        			if(isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response'] != '') {
        				$ccheck = $this->captcha_check($_POST['g-recaptcha-response']);
        				if($ccheck == 'error') {
        					$validation_errors->add( $field->field_name.'_error', __( 'Invalid reCaptcha!', 'eorf' ) );
        				}
        			} else {
        				$validation_errors->add( $field->field_name.'_error', __($field->field_label." ", 'eorf').__("is required!", "eorf"  ));
        			}
        		}

        		if($field->field_type!='file') { 
	        		if(!array_key_exists($field->field_name, $_POST)) {
	        			if($field->is_required == 1 && $field->is_hide == 0) {
	        				$validation_errors->add( $field->field_name.'_error', __($field->field_label." ", 'eorf').__("is required!", "eorf"  ));
	        			}
	        		}
        		} else {
      
        			if(array_key_exists($field->field_name, $_FILES)) {
	        			if(isset($_FILES[$field->field_name]['name']) && empty($_FILES[$field->field_name]['name']) && $field->is_required == 1 && $field->is_hide == 0) {
	        				$validation_errors->add( $field->field_name.'_error', __($field->field_label." ", 'eorf').__("is required!", "eorf"  ));
	        			}
	        		}
        		}

        	}
        	

        	return $validation_errors;

       }



       function validate_fields_checkout() { 
		    

       		global $woocommerce;

       		if(!is_user_logged_in()) { 
       			if(isset($_POST['createaccount']) && $_POST['createaccount'] == 1) {
		       		$fields = $this->get_fields();
		       		
		        	foreach ($fields as $field) { 





		        		if ( isset( $_POST[$field->field_name] ) && empty( $_POST[$field->field_name] ) && ($field->is_required == 1 && $field->showif == '')  ) { 
 
								if($field->field_type!='google_captcha') { 

									wc_add_notice( __("<b>".$field->field_label."</b> ").__( "is required1!", "eorf" ), 'error' );
								}
						}


						if ( isset( $_POST[$field->field_name] ) && empty( $_POST[$field->field_name] ) && ($field->is_required == 1 && $field->showif != '') ) { 

							
							if($field->ccondition!='is checked') {

								if($_POST[$field->cfield] == $field->ccondition_value) { 


			        				
			        				if($field->field_type!='google_captcha') {

										wc_add_notice( __("<b>".$field->field_label."</b> ").__( "is required2!", "eorf" ), 'error' );
									}

			        			}
		        			} 

		        			if($field->ccondition == 'is checked' && $_POST[$field->cfield] == 1) { 

		        				if($field->field_type!='google_captcha') {

									wc_add_notice( __("<b>".$field->field_label."</b> ").__( "is required3!", "eorf" ), 'error' );
								}
		        			}
						}



		        	}
        		}
        	}

		    
	   }


       function getDefaultField($fname) {

       		global $wpdb;
             
            $result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$wpdb->eorf_de_fields." WHERE field_name = %s", $fname));      
            return $result;
       }

       function captcha_check($res) {

				$secret = get_option('recaptcha_secret_key');
       
        		$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$res);
        		
        		$responseData = json_decode($verifyResponse);

        		if($responseData->success) {
        			return "success";
        		} else {
        			return "error";
        		}
       }

       function eorf_errors() {
		    static $wp_error; // Will hold global variable safely
		    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
		}

		function eorf_show_error_messages() {
			if($codes = $this->eorf_errors()->get_error_codes()) {
				echo '<ul class="woocommerce-error">';
				    // Loop error codes and display errors
				   foreach($codes as $code){
				        $message = $this->eorf_errors()->get_error_message($code);
				         $aa = explode('*', $message);
				        echo '<li>' ._e($aa[0].' ', 'eorf').' '._e($aa[1], 'eorf').'</li>';
				    }
				echo '</ul>';
			}	
		}

       function save_all_eddata() {
        
       		$user_id = get_current_user_id();
			
			$fields = $this->get_fields();

        	foreach ($fields as $field) { 
               //print_r($field);
        		if($field->field_type == 'google_captcha') { 

					if(isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response'] != '') { 
        				$ccheck = $this->captcha_check($_POST['g-recaptcha-response']);
        				if($ccheck == 'error') {
        					
        					$this->eorf_errors()->add( $field->field_name.'_error', __( "Invalid reCaptcha!", "eorf" ) );
        				}
        			} else {
        				$this->eorf_errors()->add( $field->field_name.'_error', __($field->field_label."*", 'eorf').__("is required!", "eorf"  ));
        			}
        			

				} else {

        		if($field->is_readonly!=1) {



        			if ( isset( $_POST[$field->field_name] ) && empty( $_POST[$field->field_name] ) && ($field->is_required == 1 && $field->showif != '') ) {

					
						if($field->ccondition!='is checked') {

							if($_POST[$field->cfield] == $field->ccondition_value) {
		        				
		        				

		        				$this->eorf_errors()->add( $field->field_name.'_error', __($field->field_label."*", 'eorf').__("is required!", "eorf"  ));

		        			}
	        			} 

	        			if((isset($_POST[$field->cfield]) && $_POST[$field->cfield] == 1) && ($field->ccondition == 'is checked')) {

	        				$this->eorf_errors()->add( $field->field_name.'_error', __($field->field_label."*", 'eorf').__("is required!", "eorf"  ));

	        			}
					}



	        		if ( isset( $_POST[$field->field_name] ) && empty( $_POST[$field->field_name] ) && ($field->is_required == 1) && ($field->field_type != 'file')  && ($field->showif == '') ) {
$this->eorf_errors()->add($field->field_name.'_error', __($field->field_label.'*', 'eorf').__("is required!", "eorf"  ));
					} else { 



						if ( isset( $_POST[$field->field_name] ) || isset( $_FILES[$field->field_name] ) ) {

			        		if($field->field_type == 'file') {

								if($_FILES[$field->field_name]['name']!='') { 

									
										

										$file = time('m').$_FILES[$field->field_name]['name'];
										echo EORF_URL.'uploaded_img/'.$file;
										$target_path = EORF_PLUGIN_DIR.'uploaded_img/';
										$target_path = $target_path . $file;
										$temp = move_uploaded_file($_FILES[$field->field_name]['tmp_name'], $target_path);
									
								} else {
									$file = $_POST['curr_'.$field->field_name];
								}

									update_user_meta($user_id,$field->field_name, $file);

			        		} else if($field->field_type == 'multiselect') { 
			        			$prefix = '';
			        			$multi = '';
			        			foreach ($_POST[$field->field_name] as $value) {
			        				$multi .= $prefix.$value;
		    						$prefix = ', ';
			        			}
			        			update_user_meta( $user_id, $field->field_name, $multi );

			        		} else { 
			        			
								update_user_meta( $user_id, $field->field_name,  $_POST[$field->field_name] );
							}

		        		} else {
		        			update_user_meta( $user_id, $field->field_name, '' );
		        		}


					}

	        	} }
        	}
		}

		function save_upload_file() {
        
       		$user_id = get_current_user_id();
			
			$fields = $this->get_fields();

        	foreach ($fields as $field) { 
          

        		if($field->field_type == 'file') {

					if($_FILES[$field->field_name]['name']!='') { 


							$file = time('m').$_FILES[$field->field_name]['name'];
							echo EORF_URL.'uploaded_img/'.$file;
							$target_path = EORF_PLUGIN_DIR.'uploaded_img/';
							$target_path = $target_path . $file;
							$temp = move_uploaded_file($_FILES[$field->field_name]['tmp_name'], $target_path);
		
					} else {
						$file = $_POST['curr_'.$field->field_name];
					}	
        		}
			}
			die();
		}

       function eo_save_extra_register_fields($customer_id) {
       		
//       		//Default Fields
           $em_value = '';
           $email_settings = (get_option('email_notification_setting'));
           $check_enable_userrole = get_option('enable_user_role',false);
           $us_status = get_option('registation_settings');

           if ($us_status['enable_user_role'] == 'disabled'){
               $em_value = 'customer';

               update_user_meta( $customer_id, 'user_status', $us_status['enable_approvel'] );
               update_user_meta ($customer_id ,'grant_user_role', 'customer');
               if($us_status['enable_grant_user_role'] == 'disabled'){
                   update_user_meta ($customer_id ,'grant_user_role_status', '1');
               }
               else{
                   update_user_meta ($customer_id ,'grant_user_role_status', '0');
               }
           }
           else{
               $em_value =  $_POST['selectuserrole'];
               update_user_meta( $customer_id, 'user_status', $us_status[$_POST['selectuserrole']] );
               update_user_meta ($customer_id ,'grant_user_role', $_POST['selectuserrole']);
               if($us_status['enable_grant_user_role'] == 'disabled'){
                   update_user_meta ($customer_id ,'grant_user_role_status', '1');
                   $wp_capabilities_arr = array ( $_POST['selectuserrole']  => '1');
                   update_user_meta( $customer_id, 'wp_capabilities', ( $wp_capabilities_arr ) );
               }
               else{
                   update_user_meta ($customer_id ,'grant_user_role_status', '0');
               }
           }


       		//First Name
       		if ( isset( $_POST['first_name'] ) && $_POST['first_name']!='' ) {
		             //First name field which is by default
		             update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['first_name'] ) );
		             // First name field which is used in WooCommerce
		             update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['first_name'] ) );
		      }

		      //Last Name
       		if ( isset( $_POST['last_name'] ) && $_POST['last_name']!='' ) {
		             //First name field which is by default
		             update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['last_name'] ) );
		             // First name field which is used in WooCommerce
		             update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['last_name'] ) );
		      }

		      //Company
		      if ( isset( $_POST['billing_company'] ) ) {
	                 // Phone input filed which is used in WooCommerce
	                 update_user_meta( $customer_id, 'billing_company', sanitize_text_field( $_POST['billing_company'] ) );
	          }

	          //country
		      if ( isset( $_POST['billing_country'] ) ) {
	                 // Phone input filed which is used in WooCommerce
	                 update_user_meta( $customer_id, 'billing_country', sanitize_text_field( $_POST['billing_country'] ) );
	          }


	          //address 1
		      if ( isset( $_POST['billing_address_1'] ) ) {
	                 // Phone input filed which is used in WooCommerce
	                 update_user_meta( $customer_id, 'billing_address_1', sanitize_text_field( $_POST['billing_address_1'] ) );
	          }

	          //address 2
		      if ( isset( $_POST['billing_address_2'] ) ) {
	                 // Phone input filed which is used in WooCommerce
	                 update_user_meta( $customer_id, 'billing_address_2', sanitize_text_field( $_POST['billing_address_2'] ) );
	          }

	          //city
		      if ( isset( $_POST['billing_city'] ) ) {
	                 // Phone input filed which is used in WooCommerce
	                 update_user_meta( $customer_id, 'billing_city', sanitize_text_field( $_POST['billing_city'] ) );
	          }

	          //state
		      if ( isset( $_POST['billing_state'] ) ) {
	                 // Phone input filed which is used in WooCommerce
	                 update_user_meta( $customer_id, 'billing_state', sanitize_text_field( $_POST['billing_state'] ) );
	          }

	          //postcode
		      if ( isset( $_POST['billing_postcode'] ) ) {
	                 // Phone input filed which is used in WooCommerce
	                 update_user_meta( $customer_id, 'billing_postcode', sanitize_text_field( $_POST['billing_postcode'] ) );
	          }

	          //phone
		      if ( isset( $_POST['billing_phone'] ) ) {
	                 // Phone input filed which is used in WooCommerce
	                 update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
	          }


       		$fields = $this->get_fields();
        	foreach ($fields as $field) { 

        		if ( isset( $_POST[$field->field_name] ) || isset( $_FILES[$field->field_name] ) ) {

	        		if($field->field_type == 'file') {

	        			if($_FILES[$field->field_name]['name']!='') { 


									$file = time('m').$_FILES[$field->field_name]['name'];
									$target_paths = EORF_PLUGIN_DIR.'uploaded_img/';
									$target_path = $target_paths . $file;
									$temp = move_uploaded_file($_FILES[$field->field_name]['tmp_name'], $target_path);
									update_user_meta($customer_id,$field->field_name,EORF_URL.'uploaded_img/'.$file);
								
							} else {
								
							}

						

	        		} else if($field->field_type == 'multiselect') { 
	        			$prefix = '';
	        			$multi = '';
	        			foreach ($_POST[$field->field_name] as $value) {
	        				$multi .= $prefix.$value;
    						$prefix = ', ';
	        			}
	        			update_user_meta( $customer_id, $field->field_name, $multi );

	        		} else {

						update_user_meta( $customer_id, $field->field_name,  $_POST[$field->field_name]);
					}

        		}
        	}



        	$user = new WP_User($customer_id);
 
	        $user_login = stripslashes($user->user_login);
	        $user_email = stripslashes($user->user_email);
           $headers .= "MIME-Version: 1.0\n";
           $headers .= "Content-type: text/html; charset=iso-8859-1\n";

        	$message  = sprintf(__('New user registration on your store %s: <br>'), get_option('blogname')) . "\n\n";
	        if ($us_status[$em_value] != 'approved'){
	            $message .= sprintf(__('Click the following link to assign user privilages. <a href = %s>Click on</a> <br>'), admin_url( 'users.php'))."\n\n";
	        }
           if($us_status['enable_grant_user_role'] == 'enabled'){
               $message .= sprintf(__('<a href = %s >Click Here</a> to grant the user requested role to users.<br> '),admin_url( 'users.php'))."\n\n";
               if ($email_settings['pending_role_enable_mail'] == 'on') {
                   $requested_role_message = $email_settings['pending_role_message'];
                   $requested_role_message = str_replace("{first_name}", $_POST['first_name'], $requested_role_message);
                   $requested_role_message = str_replace("{last_name}", $_POST['last_name'], $requested_role_message);
                   $requested_role_message = str_replace("{email}", $user_email, $requested_role_message);
                   wp_mail($user_email , $email_settings['pending_role_subject'] , $requested_role_message,$headers);
               }
           }
	        $message .= sprintf(__('Username: <strong> %s</strong> <br>'), $user_login) . "\n\n";
	        $message .= sprintf(__('E-mail:<strong> %s</strong>'), $user_email) . "\n\n";

	        foreach ($fields as $field) {

	        	$check = get_user_meta( $customer_id, $field->field_name, true );
				$label = $this->get_fieldByName($field->field_name);
				if($check!='') {

					$value = get_user_meta( $customer_id, $field->field_name, true );

					if($label->field_type=='checkbox' && $value==1) {
						$message .= sprintf(__($label->field_label.': %s'), 'Yes') . "\n\n";
					} else if($label->field_type=='checkbox' && $value==0) {
						$message .= sprintf(__($label->field_label.': %s'), 'No') . "\n\n";
					} else if($label->field_type=='select' || $label->field_type=='radioselect') {
						$meta = $this->get_OptionByid($value, $label->field_id);

						$message .= sprintf(__($label->field_label.': %s'), $meta->meta_value) . "\n\n";
					} else {
						$message .= sprintf(__($label->field_label.': %s'), $value) . "\n\n";
					}

				}



	        }

           wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message,$headers);

	        // user mail
           if ($us_status[$em_value] == 'approved'){
                $this->user_snd_mail($_POST['first_name'] , $_POST['last_name'] , $user_email ,$email_settings , 'approve_user');
           }
           else if ($us_status[$em_value] == 'pending') {
               $this->user_snd_mail($_POST['first_name'] , $_POST['last_name'] , $user_email ,$email_settings , 'pending_user');
           }
           else if ($us_status[$em_value] == 'limited'){
               $this->user_snd_mail($_POST['first_name'] , $_POST['last_name'] , $user_email ,$email_settings , 'limited_access');
           }
           else if ($us_status[$em_value] == 'block'){
               $this->user_snd_mail($_POST['first_name'] , $_POST['last_name'] , $user_email ,$email_settings , 'block_user');
           }


       }


       function get_fields() {
			global $wpdb;
             
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->eorf_fields." WHERE field_type!='' AND type = %s ORDER BY length(sort_order), sort_order", 'registration'));      
            return $result;
		}


		function get_de_fields() {
			global $wpdb;
             
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->eorf_de_fields." WHERE field_type!='' AND type = %s ORDER BY length(sort_order), sort_order", 'default'));      
            return $result;
		}


		function get_fieldByName($name) {
			global $wpdb;
             
            $result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$wpdb->eorf_fields." WHERE field_name = %s", $name));      
            return $result;
		}

		function get_OptionByid($name, $id) {
			global $wpdb;
             
            $result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$wpdb->eorf_meta." WHERE meta_key = %s AND field_id = %d", $name, $id));      
            return $result;
		}



		function getSelectOptions($id) {
			global $wpdb;
             
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->eorf_meta." WHERE field_id = %d", $id));      
            return $result;

		}

		function add_eorf_query_vars() {
			add_rewrite_endpoint( 'edit-profile', EP_PERMALINK | EP_PAGES );
			flush_rewrite_rules();
		}

		function change_template( $template ) {
 		
 		$template;

		if( get_query_var( 'edit-profile') != '' ) { 
 
			//Check plugin directory
			$newTemplate = plugin_dir_path( __FILE__ ) . 'view/edit_profile.php';
			if( file_exists( $newTemplate ) )
				return $newTemplate;
		}
 
		return $template;
 
	}

		

		


		function eo_my_profile() { ?>
			<div class="col2-set addresses">
				<header class="title">
					<h3><?php echo __(get_option('profile_title', 'eorf')); ?></h3>
					<?php $profile_url = wc_get_endpoint_url( 'edit-profile', get_current_user_id(), wc_get_page_permalink( 'myaccount' ) ); ?>
					<a class="edit" href="<?php echo $profile_url; ?>"><?php echo __('Edit', 'eorf') ?></a>
				</header>
			</div>
			<table class="shop_table shop_table_responsive my_account_orders">
			<tbody>
			<?php 
				$user_id = get_current_user_id();
				$fields =  $this->get_fields();
				foreach ($fields as $field) {

					$check = get_user_meta( $user_id, $field->field_name, true );
					$label = $this->get_fieldByName($field->field_name);
					if($check!='') {

						$value = get_user_meta( $user_id, $field->field_name, true );
					?>
						<tr class="order" style="text-align:left">
							<td style="width:30%;"><b><?php echo __($label->field_label, 'eorf'); ?></b></td>
							<td>
								<?php 
									if($label->field_type == 'file') { 
										$aa = EORF_URL.'uploaded_img/'.$value;
										$ext = pathinfo($aa, PATHINFO_EXTENSION);
										if($ext == 'pdf' || $ext == 'PDF') { ?>
											<a href="<?php echo EORF_URL; ?>uploaded_img/<?php echo $value; ?>" target="_blank">
												<img src="<?php echo EORF_URL; ?>images/pdf.png" width="150" height="150" title="Click to View" />
											</a>
										<?php } else { ?>
											<img src="<?php echo $value; ?>" width="150" height="150" />
										<?php } ?>
										
									<?php } else if($label->field_type=='checkbox' && $value==1) { 
										echo "Yes";
									} else if($label->field_type=='checkbox' && $value==0) {
										echo "No";
									} else if($label->field_type=='select' || $label->field_type=='radioselect') { 
										$meta = $this->get_OptionByid($value, $label->field_id);
										echo $meta->meta_value;
									} else if($label->field_type=='multiselect') {
										
										$multi = '';
										$mdata = explode(', ',$value);
										$prefix = '';
										for($a=0; $a < sizeof($mdata);$a++) {
											
											$meta = $this->get_OptionByid($mdata[$a], $label->field_id);
											$multi .= $prefix.$meta->meta_value;
    										$prefix = ', ';
										}

										echo $multi;

									} else if($label->field_type =='color_picker') { ?>
										<div style="background-color: <?php echo $value; ?>" width="50" height="50"></div>
									<?php } else {
										echo $value;
									}
								?>
							</td>
						</tr>
						
					<?php }
				}

			?>

			</tbody>
			</table>
			
		<?php }



		function eo_login_form_end() { 

			// Start the session
	        if(!session_id()) {
	            session_start();
	        }

	        // No need for the button is the user is already logged
	        if(is_user_logged_in())
	            return;

		        if(get_option('login_facebook') == 'enabled') {

		        // HTML markup
		        $html = '<div id="eo-facebook-wrapper">';

		        // Messages
		        if(isset($_SESSION['eo_facebook_message'])) {
		            $message = $_SESSION['alka_facebook_message'];
		            $html .= '<div id="eo-facebook-message" class="alert alert-danger">'.$message.'</div>';
		            unset($_SESSION['eo_facebook_message']);
		        }

		        // Button
		        $html .= '<a href="'.$this->getLoginUrl().'"><img src="'.EORF_URL.'images/fb1.png" /></a>';

		        $html .= '</div>';

		        echo $html;
	    	}
	    	

	       if(get_option('login_twitter') == 'enable') {
	        	echo $this->get_login_link();
	       }

	      

		}

		


		function FbApiCall() {

			$app_id = get_option('facebook_app_id');
    		$app_secret = get_option('facebook_app_secret');

	        $facebook = new Facebook\Facebook([
	            'app_id' => $app_id,
	            'app_secret' => $app_secret,
	            'default_graph_version' => 'v2.2',
	            'persistent_data_handler' => 'session'
	        ]);

	        return $facebook;

	    }

	    function getLoginUrl() {

	        if(!session_id()) {
	            session_start();
	        }

	        $fb = $this->FbApiCall();

	        $helper = $fb->getRedirectLoginHelper();

	        $permissions = ['email'];

	        $callbackurl = admin_url( 'admin-ajax.php').'?action=eo_facebook_login';

	        $url = $helper->getLoginUrl($callbackurl, $permissions);

	        return esc_url($url);

	    }


	    function FBCallback() {
	    	
	    	if(!session_id()) {
	            session_start();
	        }

	        $this->redirect_url = get_permalink( get_option('woocommerce_myaccount_page_id') );

	        $fb = $this->FbApiCall();

	        $this->access_token = $this->getToken($fb);

	        $this->facebook_details = $this->getUserDetails($fb);
	        $this->loginUser();
	        $this->createUser();
	        header("Location: ".$this->redirect_url, true);
	        die();
		}



		private function getToken($fb) {

	        $_SESSION['FBRLH_state'] = $_GET['state'];

	        $helper = $fb->getRedirectLoginHelper();

	        try {
	            $accessToken = $helper->getAccessToken();
	        }
	        catch(Facebook\Exceptions\FacebookResponseException $e) {
	            $error = __('Graph returned an error: ','eorf'). $e->getMessage();
	            $message = array(
	                'type' => 'error',
	                'content' => $error
	            );
	        }
	        catch(Facebook\Exceptions\FacebookSDKException $e) {
	            $error = __('Facebook SDK returned an error: ','eorf'). $e->getMessage();
	            $message = array(
	                'type' => 'error',
	                'content' => $error
	            );
	        }

	        if (!isset($accessToken)) {
	            $_SESSION['alka_facebook_message'] = $message;
	            header("Location: ".$this->redirect_url, true);
	            die();
	        }

	        return $accessToken->getValue();

	    }

	    
	    function getUserDetails($fb)
	    {

	        try {
	            $response = $fb->get('/me?fields=id,name,first_name,last_name,email,link', $this->access_token);
	        } catch(Facebook\Exceptions\FacebookResponseException $e) {
	            $message = __('Graph returned an error: ','eorf'). $e->getMessage();
	            $message = array(
	                'type' => 'error',
	                'content' => $error
	            );
	        } catch(Facebook\Exceptions\FacebookSDKException $e) {
	            $message = __('Facebook SDK returned an error: ','eorf'). $e->getMessage();
	            $message = array(
	                'type' => 'error',
	                'content' => $error
	            );
	        }

	        // If we caught an error
	        if (isset($message)) {
	            $_SESSION['eo_facebook_message'] = $message;
	            header("Location: ".$this->redirect_url, true);
	            die();
	        }

	        return $response->getGraphUser();

	    }

	    
	    function loginUser() {

	        $wp_users = get_users(array(
	            'meta_key'     => 'eo_facebook_id',
	            'meta_value'   => $this->facebook_details['id'],
	            'number'       => 1,
	            'count_total'  => false,
	            'fields'       => 'id',
	        ));

	        if(empty($wp_users[0])) {
	            return false;
	        }

	        wp_set_auth_cookie( $wp_users[0] );

	    }

	    function createUser() {


	        $fb_user = $this->facebook_details;

	        $username = sanitize_user(str_replace(' ', '_', strtolower($this->facebook_details['name'])));

	        $new_user = wp_create_user($username, wp_generate_password(), $fb_user['email']);

	        if(is_wp_error($new_user)) {
	            $_SESSION['eo_facebook_message'] = $new_user->get_error_message();
	            header("Location: ".$this->redirect_url, true);
	            die();
	        }

	        update_user_meta( $new_user, 'first_name', $fb_user['first_name'] );
	        update_user_meta( $new_user, 'last_name', $fb_user['last_name'] );
	        update_user_meta( $new_user, 'user_url', $fb_user['link'] );
	        update_user_meta( $new_user, 'eo_facebook_id', $fb_user['id'] );

	        wp_set_auth_cookie( $new_user );

	    }




	    public function ex_tw_load() {	
			if (isset($_GET['tw']) && $_GET['tw']=='login') { 
				$this->login();

			}else if(isset($_REQUEST['oauth_verifier'])) {
				$this->callback();
			}
		}

		public function twitter_init() { 
			global $tmhOAuth;
			if (!isset($_SESSION)) {session_start();}
			
			require_once(EORF_PLUGIN_DIR.'tmhOAuth/tmhOAuth.php');
			require_once(EORF_PLUGIN_DIR.'tmhOAuth/tmhUtilities.php');
			
			$consumerKey = get_option('twitter_consumer_key');
			$consumerSecret = get_option('twitter_consumer_secret');
			$ac_token = get_option('twitter_access_token');
			$ac_token_secret = get_option('twitter_access_token_secret');
			$tmhOAuth = new tmhOAuth(array(
				'consumer_key'    => $consumerKey,
				'consumer_secret' => $consumerSecret,
				'token'           => $ac_token,
  				'secret'          => $ac_token_secret,
			));
			$this->here = tmhUtilities::php_self();
			return true;
		}
		
	
		public function login() { 
			global $tmhOAuth;
			$this->twitter_init();
				
			if ( isset($_SESSION['access_token']) ) { 
				$tmhOAuth->config['user_token']  = $_SESSION['access_token']['oauth_token'];
				$tmhOAuth->config['user_secret'] = $_SESSION['access_token']['oauth_token_secret'];
				
				$code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/account/verify_credentials'));
				if ($code == 200) {
					$resp = json_decode($tmhOAuth->response['response']);
					$this->wplogin($resp->screen_name);
				} else {
					$this->outputError($tmhOAuth, 'login session');
				}
				 
			}else{ 

				$callback = isset($_REQUEST['oob']) ? 'oob' : $this->here;
			
				$params = array(
					'oauth_callback'  => $callback,
				);
			
				if (isset($_REQUEST['force_write'])) :
					$params['x_auth_access_type'] = 'write';
				elseif (isset($_REQUEST['force_read'])) :
					$params['x_auth_access_type'] = 'read';
				endif;
			
				$code = $tmhOAuth->request('GET', $tmhOAuth->url('oauth/request_token', ''), $params);
			
				if ($code == 200) {
					$_SESSION['oauth'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
					$method = isset($_REQUEST['authenticate']) ? 'authenticate' : 'authorize';
					$force  = isset($_REQUEST['force']) ? '&force_login=1' : '';
					$authurl = $tmhOAuth->url("oauth/{$method}", '') .  "?oauth_token={$_SESSION['oauth']['oauth_token']}{$force}";
					?>
					<script type="text/javascript">
						window.location = '<?php echo $authurl; ?>';
					</script>
					<?php 
				} else {
					$this->outputError($tmhOAuth, 'login first');
				}
			}
			
		}
		

		public function callback() {
			
				global $tmhOAuth;
				$this->twitter_init();

				$tmhOAuth->config['user_token']  = $_SESSION['oauth']['oauth_token'];
				$tmhOAuth->config['user_secret'] = $_SESSION['oauth']['oauth_token_secret'];
			
				$code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/access_token', ''), array(
					'oauth_verifier' => $_REQUEST['oauth_verifier']
				));
			
				if ($code == 200) {
					$_SESSION['access_token'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
					$tmhOAuth->config['user_token']  = $_SESSION['access_token']['oauth_token'];
		  		$tmhOAuth->config['user_secret'] = $_SESSION['access_token']['oauth_token_secret'];
					$code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/account/verify_credentials'),['include_email' => 'true']);
					if ($code == 200) {
						$resp = json_decode($tmhOAuth->response['response']);
						$wpuserarray = array(
							"user_login" => $resp->screen_name,
							"user_pass" => $resp->id,
							"user_email" => $resp->email,
							"first_name" => $resp->name,
							"description" => $resp->description,
							"user_url" => $resp->url,
							"nickname" => $resp->screen_name,
						);
						
						$userid = $this->create_user($wpuserarray);
						
						unset($_SESSION['oauth']);
						
						if ($userid) { 
							$this->wplogin($wpuserarray['user_login']);
							header("Location: ".$this->here);
						}else{
							header("Location: ".$this->here."?tw=error");
						}
					}else{
						$this->outputError($tmhOAuth, 'callback 2');
					}
				} else {
					$this->outputError($tmhOAuth, 'callback 1');
				}
				
		}
	
		private function wplogin($username) {
			$user = get_user_by('login' ,$username);
			if($user) {
				$userid = $user->ID;
				wp_set_current_user($userid, $username);
				wp_set_auth_cookie($userid);
				do_action('wp_login', $username, $user);
			}
		}

		private function outputError($tmhOAuth, $position = '') {
			global $tmhOAuth;
			
				echo 'Error '.$position.': ' . $tmhOAuth->response['response'] . PHP_EOL;
				tmhUtilities::pr($tmhOAuth);
			
		}
		
		private function create_user($array) {		
			
			if (!username_exists($array['user_login'])) {
				$userid = wp_insert_user($array );
				return $userid;
				
			}else{
				return 'usernameexists';
			}
		}

		public function get_login_link() {
			$authUrl = get_permalink( get_option('woocommerce_myaccount_page_id') ).'?tw=login&authenticate=1';
			return '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'"><img style="margin-top:10px;" src="'.EORF_URL.'images/tw.png"/></a>';
		}


		


		public function eo_billing_fields($checkout) { 

			if(!is_user_logged_in()) { 
				
	        	


				$fields = $checkout->get_checkout_fields();

				//print_r($fields);

	        	$bfields = $this->get_fields();
	        	$clss = '';

	        	foreach($bfields as $bfield) {

	        				

	        				if($bfield->showif == 'Show') {
			        			$clss = 'hide';
			        		} elseif($bfield->showif == 'Hide') {
			        			$clss = 'show';
			        		} else {
			        			$clss = '';
			        		}


			        		if($bfield->field_type == 'select') {
						   		$opts = $this->getSelectOptions($bfield->field_id);
						   		foreach ($opts as $opt) {
						   			
						   			$options[$opt->meta_key] = $opt->meta_value;
						   		}
					   		}

					   		if($bfield->field_type == 'radioselect') {
						   		$opts = $this->getSelectOptions($bfield->field_id);
						   		foreach ($opts as $opt) {
						   			
						   			$options1[$opt->meta_key] = $opt->meta_value;
						   		}
					   		}

					   		if($bfield->field_type == 'multiselect') {
						   		$opts = $this->getSelectOptions($bfield->field_id);
						   		foreach ($opts as $opt) {
						   			
						   			$options2[$opt->meta_key] = $opt->meta_value;
						   		}
					   		}

			        		if($bfield->field_type == 'text' && $bfield->is_hide == 0) {

			        			if($bfield->is_required == 1) {

			        				$fields['account'][$bfield->field_name] = array(
								        'label'         => __($bfield->field_label, 'eorf'),
								        'required'      => true,
								        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
								        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
								        'clear'         => false,
								        'id'         	=> $bfield->field_name,
								        'type'			=> $bfield->field_type,
								        

								    );

			        			} else {
				        			$fields['account'][$bfield->field_name] = array(
								        'label'         => __($bfield->field_label, 'eorf'),
								        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
								        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
								        'clear'         => false,
								        'id'         	=> $bfield->field_name,
								        'type'			=> $bfield->field_type,
								        

								    );
			        			}

			        		} 



			        		 else if($bfield->field_type == 'textarea' && $bfield->is_hide == 0) {

			        		 	if($bfield->is_required == 1) {

			        		 		$fields['account'][$bfield->field_name] = array(
								        'label'         => __($bfield->field_label, 'eorf'),
								        'required'      => true,
								        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
								        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
								        'clear'         => false,
								        'id'         	=> $bfield->field_name,
								        'type'			=> $bfield->field_type,
								        

								    );

			        		 	} else {

			        		 		$fields['account'][$bfield->field_name] = array(
								        'label'         => __($bfield->field_label, 'eorf'),
								        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
								        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
								        'clear'         => false,
								        'id'         	=> $bfield->field_name,
								        'type'			=> $bfield->field_type,
								        

								    );

			        		 	}

			        		} 

			        		 else if($bfield->field_type == 'select' && $bfield->is_hide == 0) {

			        		 	if($bfield->is_required == 1) {

			        		 		$fields['account'][$bfield->field_name] = array(
								        'label'         => __($bfield->field_label, 'eorf'),
								        'required'      => true,
								        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
								        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
								        'clear'         => false,
								        'id'         	=> $bfield->field_name,
								        'type'			=> $bfield->field_type,
								        'options'     	=> $options,
							        );

			        		 	} else {

			        		 		$fields['account'][$bfield->field_name] = array(
								        'label'         => __($bfield->field_label, 'eorf'),
								        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
								        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
								        'clear'         => false,
								        'id'         	=> $bfield->field_name,
								        'type'			=> $bfield->field_type,
								        'options'     	=> $options,
							        );
			        		 	}


					   		}

					   		 else if($bfield->field_type == 'radioselect' && $bfield->is_hide == 0) {

					   		 	if($bfield->is_required == 1) {

					   		 		$fields['account'][$bfield->field_name] = array(
								        'label'         => __($bfield->field_label, 'eorf'),
								        'required'      => true,
								        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
								        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
								        'clear'         => false,
								        'type'			=> 'radio',
								        'options'     	=> $options1,

								    );

					   		 	} else {

					   		 		$fields['account'][$bfield->field_name] = array(
								        'label'         => __($bfield->field_label, 'eorf'),
								        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
								        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
								        'clear'         => false,
								        'type'			=> 'radio',
								        'options'     	=> $options1,

								    );
					   		 	}

					   			

					   		}

					   		

					   		 else if($bfield->field_type == 'multiselect' && $bfield->is_hide == 0) {

					   		 	if($bfield->is_required == 1) {

					   		 		$fields['account'][$bfield->field_name.'[]'] = array(
								        'label'         => __($bfield->field_label, 'eorf'),
								        'required'      => true,
								        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
								        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
								        'clear'         => false,
								        'id'         	=> $bfield->field_name,
								        'type'			=> $bfield->field_type,
								        'options'     	=> $options2,
							        );

					   		 	} else {

					   		 		$fields['account'][$bfield->field_name.'[]'] = array(
								        'label'         => __($bfield->field_label, 'eorf'),
								        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
								        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
								        'clear'         => false,
								        'id'         	=> $bfield->field_name,
								        'type'			=> $bfield->field_type,
								        'options'     	=> $options2,
							        );
					   		 	}
					   			

					   		} else if($bfield->field_type == 'datepicker' && $bfield->is_hide == 0) {

					   			if($bfield->is_required == 1) {

					   				$fields['account'][$bfield->field_name] = array(

									   'type' => 'text',
									   'label'         => __($bfield->field_label, 'eorf'),
									   'required'      => true,
									   'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
									   'input_class'   => array('input-text datepick'),
									   'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
									   'id'         	=> $bfield->field_name,
									   'clear'     => false
								    );

					   			} else {

					   				$fields['account'][$bfield->field_name] = array(

									   'type' => 'text',
									   'label'      => __($bfield->field_label, 'eorf'),
									   'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
									   'input_class'   => array('input-text datepick'),
									   'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
									   'id'         	=> $bfield->field_name,
									   'clear'     => false
								    );
					   			}

					   			

					   		} else if($bfield->field_type == 'timepicker' && $bfield->is_hide == 0) {


					   			if($bfield->is_required == 1) {

					   				$fields['account'][$bfield->field_name] = array(

									   'type' => 'text',
									   'label'         => __($bfield->field_label, 'eorf'),
									   'required'      => true,
									   'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
									   'input_class'   => array('input-text timepick'),
									   'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
									   'id'         	=> $bfield->field_name,
									   'clear'     => false
								    );

					   			} else {

					   				$fields['account'][$bfield->field_name] = array(

									   'type' => 'text',
									   'label'      => __($bfield->field_label, 'eorf'),
									   'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
									   'input_class'   => array('input-text timepick'),
									   'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
									   'id'         	=> $bfield->field_name,
									   'clear'     => false
								    );
					   			}

					   			

					   		}

					   		else if($bfield->field_type == 'file' && $bfield->is_hide == 0) {

					   			
					   			

					   			

					   		} else if($bfield->field_type == 'checkbox' && $bfield->is_hide == 0) {

					   			if($bfield->is_required == 1) {

					   				$fields['account'][$bfield->field_name] = array(
								        'label'         => __($bfield->field_label, 'eorf'),
								        'required'      => true,
								        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
								        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
								        'clear'         => false,
								        'id'         	=> 'c'.$bfield->field_name,
								        'type'			=> $bfield->field_type,
								        

								    );

								} else {

									$fields['account'][$bfield->field_name] = array(
								        'label'         => __($bfield->field_label, 'eorf'),
								        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
								        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
								        'clear'         => false,
								        'id'         	=> 'c'.$bfield->field_name,
								        'type'			=> $bfield->field_type,
								        

								    );
									
								}

						    

						    

						} else if($bfield->field_type == 'user_checkbox') { 

							if($bfield->is_required == 1) {

					   				$fields['account'][$bfield->field_name] = array(
								        'label'         => __($bfield->user_checkbox_label, 'eorf'),
								        'required'      => true,
								        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
								        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
								        'clear'         => false,
								        'id'         	=> 'c'.$bfield->field_name,
								        'type'			=> 'checkbox'
								        

								    );

								} else {

									$fields['account'][$bfield->field_name] = array(
								        'label'         => __($bfield->user_checkbox_label, 'eorf'),
								        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
								        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
								        'clear'         => false,
								        'id'         	=> 'c'.$bfield->field_name,
								        'type'			=> 'checkbox'
								        

								    );
									
								}
						} 

						else if($bfield->field_type == 'numeric' && $bfield->is_hide == 0) {

							if($bfield->is_required == 1) {

								$fields['account'][$bfield->field_name] = array(
							        'label'         => __($bfield->field_label, 'eorf'),
							        'required'      => true,
							        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
							        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
							        'clear'         => false,
							        'id'         	=> $bfield->field_name,
							        'type'			=> 'number',
							        

							    );

							} else {

								$fields['account'][$bfield->field_name] = array(
							        'label'         => __($bfield->field_label, 'eorf'),
							        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
							        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
							        'clear'         => false,
							        'id'         	=> $bfield->field_name,
							        'type'			=> 'number',
							        

							    );

							}

						    

						    

						} else if($bfield->field_type == 'color_picker' && $bfield->is_hide == 0) {



							if($bfield->is_required == 1) {

								$fields['account'][$bfield->field_name] = array(
							        'label'         => __($bfield->field_label, 'eorf'),
							        'required'      => true,
							        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
							        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
							        'clear'         => false,
							        'id'         	=> $bfield->field_name,
							        'type'			=> 'text',
							        

							    );

							} else {

								$fields['account'][$bfield->field_name] = array(
							        'label'         => __($bfield->field_label, 'eorf'),
							        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
							        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
							        'clear'         => false,
							        'id'         	=> $bfield->field_name,
							        'type'			=> 'text',
							        

							    );

							}

							?>

							<script>
						
							jQuery("#<?php echo $bfield->field_name; ?>").spectrum({
							    color: "#000",
							    preferredFormat: "hex",
							});

							</script>

							<?php



						} else if($bfield->is_hide == 0) {

					   			if($bfield->is_required == 1) {

					   				$fields['account'][$bfield->field_name] = array(
								        'label'         => __($bfield->field_label, 'eorf'),
								        'required'      => true,
								        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
								        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
								        'clear'         => false,
								        'id'         	=> $bfield->field_name,
								        'type'			=> $bfield->field_type,
								        

								    );

					   			} else {

					   				$fields['account'][$bfield->field_name] = array(
								        'label'         => __($bfield->field_label, 'eorf'),
								        'placeholder'   => _x($bfield->field_placeholder, 'placeholder', 'eorf'),
								        'class'         => array(($bfield->width == 'full' ? 'full' : 'half'), $clss),
								        'clear'         => false,
								        'id'         	=> $bfield->field_name,
								        'type'			=> $bfield->field_type,
								        

								    );

					   			}

						    

						    

						} 
			        		




			        		?>





			        		<script type="text/javascript">
			        			
			        			jQuery(document).ready(function($) { 

			        			
			        			
								<?php
									if($bfield->showif == 'Show') { ?>

										<?php if($bfield->ccondition == 'is not empty') { ?>
											$("#<?php echo $bfield->cfield ?>").change(function(){
												$("#<?php echo $bfield->field_name ?>_field").show();
												$("#<?php echo $bfield->field_name ?>").show();
											});

											$("#<?php echo $bfield->field_name ?>_field").show();
											$("#<?php echo $bfield->field_name ?>").show();

											$('input:radio').change(function() { 

												if ($(this).is(':checked')) { 
													var aa = $(this).val();
										        }

												if(aa != '') {
											 		$("#<?php echo $bfield->field_name ?>_field").show();
											 		$("#<?php echo $bfield->field_name ?>").show();
											 	} else {
											 		$("#<?php echo $bfield->field_name ?>_field").hide();
											 		$("#<?php echo $bfield->field_name ?>").hide();
											 	}
										 	});

										 	var aa = $("input[type='radio']:checked").val();
										 	if(aa == "<?php echo $bfield->ccondition_value; ?>") {
										 		$("#<?php echo $bfield->field_name ?>_field").show();
										 		$("#<?php echo $bfield->field_name ?>").show();
										 	} else {
										 		$("#<?php echo $bfield->field_name ?>_field").hide();
										 		$("#<?php echo $bfield->field_name ?>").hide();
										 	}


										<?php } else if($bfield->ccondition == 'is equal to') { ?>
											$("#<?php echo $bfield->cfield ?>").change(function(){
											 	if($("#<?php echo $bfield->cfield ?>").val() == "<?php echo $bfield->ccondition_value; ?>") {
											 		$("#<?php echo $bfield->field_name ?>_field").show();
											 		$("#<?php echo $bfield->field_name ?>").show();
											 	} else {
											 		$("#<?php echo $bfield->field_name ?>_field").hide();
											 		$("#<?php echo $bfield->field_name ?>").hide();
											 	}
											});

											if($("#<?php echo $bfield->cfield ?>").val() == "<?php echo $bfield->ccondition_value; ?>") {
										 		$("#<?php echo $bfield->field_name ?>_field").show();
										 		$("#<?php echo $bfield->field_name ?>").show();
										 	} else {
										 		$("#<?php echo $bfield->field_name ?>_field").hide();
										 		$("#<?php echo $bfield->field_name ?>").hide();
										 	}

											$('input:radio').change(function() {

												if ($(this).is(':checked')) {
													var aa = $(this).val();
										        }


												if(aa == "<?php echo $bfield->ccondition_value; ?>") {
											 		$("#<?php echo $bfield->field_name ?>_field").show();
											 		$("#<?php echo $bfield->field_name ?>").show();
											 	} else {
											 		$("#<?php echo $bfield->field_name ?>_field").hide();
											 		$("#<?php echo $bfield->field_name ?>").hide();
											 	}
										 	});

										 	var aa = $("input[type='radio']:checked").val();
										 	if(aa == "<?php echo $bfield->ccondition_value; ?>") {
										 		$("#<?php echo $bfield->field_name ?>_field").show();
										 		$("#<?php echo $bfield->field_name ?>").show();
										 	} else {
										 		$("#<?php echo $bfield->field_name ?>_field").hide();
										 		$("#<?php echo $bfield->field_name ?>").hide();
										 	}

											

										<?php } else if($bfield->ccondition == 'is not equal to') { ?>

											$("#<?php echo $bfield->cfield ?>").change(function(){
											 	if($("#<?php echo $bfield->cfield ?>").val() != "<?php echo $bfield->ccondition_value; ?>") {
											 		$("#<?php echo $bfield->field_name ?>_field").show();
											 		$("#<?php echo $bfield->field_name ?>").show();
											 	} else {
											 		$("#<?php echo $bfield->field_name ?>_field").hide();
											 		$("#<?php echo $bfield->field_name ?>").hide();
											 	}
											});

											if($("#<?php echo $bfield->cfield ?>").val() != "<?php echo $bfield->ccondition_value; ?>") {
										 		$("#<?php echo $bfield->field_name ?>_field").show();
										 		$("#<?php echo $bfield->field_name ?>").show();
										 	} else {
										 		$("#<?php echo $bfield->field_name ?>_field").hide();
										 		$("#<?php echo $bfield->field_name ?>").hide();
										 	}

											$('input:radio').change(function() {

												if ($(this).is(':checked')) {
													var aa = $(this).val();
										        }


												if(aa != "<?php echo $bfield->ccondition_value; ?>") {
											 		$("#<?php echo $bfield->field_name ?>_field").show();
											 		$("#<?php echo $bfield->field_name ?>").show();
											 	} else {
											 		$("#<?php echo $bfield->field_name ?>_field").hide();
											 		$("#<?php echo $bfield->field_name ?>").hide();
											 	}
										 	});


										 	var aa = $("input[type='radio']:checked").val();
										 	if(aa != "<?php echo $bfield->ccondition_value; ?>") {
										 		$("#<?php echo $bfield->field_name ?>_field").show();
										 		$("#<?php echo $bfield->field_name ?>").show();
										 	} else {
										 		$("#<?php echo $bfield->field_name ?>_field").hide();
										 		$("#<?php echo $bfield->field_name ?>").hide();
										 	}


										<?php } else if($bfield->ccondition == 'is checked') { ?>

											$("#c<?php echo $bfield->cfield ?>").change(function(){ 

												if ($(this).is(':checked')) {
										           $("#<?php echo $bfield->field_name ?>_field").show();
										           $("#<?php echo $bfield->field_name ?>").show();
										        } else {
										        	$("#<?php echo $bfield->field_name ?>_field").hide();
										        	$("#<?php echo $bfield->field_name ?>").hide();
										        }
											});

											if ($("#c<?php echo $bfield->cfield ?>").is(':checked')) { 
												$("#<?php echo $bfield->field_name ?>_field").show();
												$("#<?php echo $bfield->field_name ?>").show();
											} else {
												$("#<?php echo $bfield->field_name ?>_field").hide();
												$("#<?php echo $bfield->field_name ?>").hide();
											}



										<?php } ?>



					        			
					        		<?php } elseif($bfield->showif == 'Hide') { ?>

					        			<?php if($bfield->ccondition == 'is not empty') { ?>
											$("#<?php echo $bfield->cfield ?>").change(function(){
												$("#<?php echo $bfield->field_name ?>_field").hide();
												$("#<?php echo $bfield->field_name ?>").hide();
											});

											$("#<?php echo $bfield->field_name ?>_field").hide();
											$("#<?php echo $bfield->field_name ?>").hide();

											
											$('input:radio').change(function() {

												if ($(this).is(':checked')) {
													var aa = $(this).val();
										        }

												if(aa != '') {
											 		$("#<?php echo $bfield->field_name ?>_field").hide();
											 		$("#<?php echo $bfield->field_name ?>").hide();
											 	} else {
											 		$("#<?php echo $bfield->field_name ?>_field").show();
											 		$("#<?php echo $bfield->field_name ?>").show();
											 	}
										 	});


										 	var aa = $("input[type='radio']:checked").val();
										 	if(aa == "<?php echo $bfield->ccondition_value; ?>") {
										 		$("#<?php echo $bfield->field_name ?>_field").hide();
										 		$("#<?php echo $bfield->field_name ?>").hide();
										 	} else {
										 		$("#<?php echo $bfield->field_name ?>_field").show();
										 		$("#<?php echo $bfield->field_name ?>").show();
										 	}


										<?php } else if($bfield->ccondition == 'is equal to') { ?>
											$("#<?php echo $bfield->cfield ?>").change(function(){
											 	if($("#<?php echo $bfield->cfield ?>").val() == "<?php echo $bfield->ccondition_value; ?>") {
											 		$("#<?php echo $bfield->field_name ?>_field").hide();
											 		$("#<?php echo $bfield->field_name ?>").hide();
											 	} else {
											 		$("#<?php echo $bfield->field_name ?>_field").show();
											 		$("#<?php echo $bfield->field_name ?>").show();
											 	}
											});

											if($("#<?php echo $bfield->cfield ?>").val() == "<?php echo $bfield->ccondition_value; ?>") {
										 		$("#<?php echo $bfield->field_name ?>_field").hide();
										 		$("#<?php echo $bfield->field_name ?>").hide();
										 	} else {
										 		$("#<?php echo $bfield->field_name ?>_field").show();
										 		$("#<?php echo $bfield->field_name ?>").show();
										 	}

											$('input:radio').change(function() {

												if ($(this).is(':checked')) {
													var aa = $(this).val();
										        }


												if(aa == "<?php echo $bfield->ccondition_value; ?>") {
											 		$("#<?php echo $bfield->field_name ?>_field").hide();
											 		$("#<?php echo $bfield->field_name ?>").hide();
											 	} else {
											 		$("#<?php echo $bfield->field_name ?>_field").show();
											 		$("#<?php echo $bfield->field_name ?>").show();
											 	}
										 	});

										 	var aa = $("input[type='radio']:checked").val();
										 	if(aa == "<?php echo $bfield->ccondition_value; ?>") {
										 		$("#<?php echo $bfield->field_name ?>_field").hide();
										 		$("#<?php echo $bfield->field_name ?>").hide();
										 	} else {
										 		$("#<?php echo $bfield->field_name ?>_field").show();
										 		$("#<?php echo $bfield->field_name ?>").show();
										 	}


											

										<?php } else if($bfield->ccondition == 'is not equal to') { ?>

											$("#<?php echo $bfield->cfield ?>").change(function(){
											 	if($("#<?php echo $bfield->cfield ?>").val() != "<?php echo $bfield->ccondition_value; ?>") {
											 		$("#<?php echo $bfield->field_name ?>_field").hide();
											 		$("#<?php echo $bfield->field_name ?>").hide();
											 	} else {
											 		$("#<?php echo $bfield->field_name ?>_field").show();
											 		$("#<?php echo $bfield->field_name ?>").show();
											 	}
											});

											if($("#<?php echo $bfield->cfield ?>").val() != "<?php echo $bfield->ccondition_value; ?>") {
										 		$("#<?php echo $bfield->field_name ?>_field").hide();
										 		$("#<?php echo $bfield->field_name ?>").hide();
										 	} else {
										 		$("#<?php echo $bfield->field_name ?>_field").show();
										 		$("#<?php echo $bfield->field_name ?>").show();
										 	}


											$('input:radio').change(function() {

												if ($(this).is(':checked')) {
													var aa = $(this).val();
										        }


												if(aa != "<?php echo $bfield->ccondition_value; ?>") {
											 		$("#<?php echo $bfield->field_name ?>_field").hide();
											 		$("#<?php echo $bfield->field_name ?>").hide();
											 	} else {
											 		$("#<?php echo $bfield->field_name ?>_field").show();
											 		$("#<?php echo $bfield->field_name ?>").show();
											 	}
										 	});

										 	var aa = $("input[type='radio']:checked").val();
										 	if(aa != "<?php echo $bfield->ccondition_value; ?>") {
										 		$("#<?php echo $bfield->field_name ?>_field").hide();
										 		$("#<?php echo $bfield->field_name ?>").hide();
										 	} else {
										 		$("#<?php echo $bfield->field_name ?>_field").show();
										 		$("#<?php echo $bfield->field_name ?>").show();
										 	}


											


										<?php } else if($bfield->ccondition == 'is checked') { ?>

											$("#c<?php echo $bfield->cfield ?>").change(function(){ 

												if ($(this).is(':checked')) {
										           $("#<?php echo $bfield->field_name ?>_field").hide();
										           $("#<?php echo $bfield->field_name ?>").hide();
										        } else {
										        	$("#<?php echo $bfield->field_name ?>_field").show();
										        	$("#<?php echo $bfield->field_name ?>").show();
										        }
											});


											if ($("#c<?php echo $bfield->cfield ?>").is(':checked')) { 
												$("#<?php echo $bfield->field_name ?>_field").hide();
												$("#<?php echo $bfield->field_name ?>").hide();
											} else {
												$("#<?php echo $bfield->field_name ?>_field").show();
												$("#<?php echo $bfield->field_name ?>").show();
											}



										<?php } ?>
					        			
					        		<?php } else { ?>
					        			
					        		<?php } ?>
								
							});

			        		</script>


			        	<?php 

	        	}
        	





        	 $checkout->__set( 'checkout_fields', $fields );
        	 }

		}



		function eo_custom_multiselect_handler( $field, $key, $args, $value  ) {
					

		    $options = '';

		    $ekey = explode('[', $key);
		    $er = $this->getRequired($ekey[0]);
		    if($er!='') {
		    	if($er->is_required == 1) {
		    		$required = '<abbr class="required" title="required">*</abbr>';
		    	} else {
		    		$required = '';
		    	}
		    }
		    if ( ! empty( $args['options'] ) ) {
		        foreach ( $args['options'] as $option_key => $option_text ) {
		            $options .= '<option value="' . $option_key . '" '. selected( $value, $option_key, false ) . '>' .__($option_text, 'eorf').'</option>';
		        }

		        $field = '<p class="form-row ' . implode( ' ', $args['class'] ) .'" id="' . $key . '_field">
		            <label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'].$required.'</label>
		            <select name="' . $key . '" id="' . $key . '" class="select" multiple="multiple">
		                ' . $options . '
		            </select>
		        </p>';
		    }

		    return $field;
		}


		function getRequired($name)  { 
			global $wpdb;
			

           	$result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$wpdb->eorf_fields." WHERE  field_name = %s", $name));      
            return $result;
		}

		
		function de_get_states() {

			$country = $_POST['country'];
			$width = $_POST['width'];
			$fname = $_POST['fname'];
			$flabel = $_POST['flabel'];
			$fmessage = $_POST['fmessage'];
			$required = $_POST['required'];
			$se_state = $_POST['se_state'];

			global $woocommerce;
			$countries_obj   = new WC_Countries();
			$states = $countries_obj->get_states( $country );
			
			if(!empty($states)) {
			?>

			<p id="statedrop" class="form-row <?php echo $width; ?>">
				<label for="<?php echo $fname; ?>"><?php _e( $flabel, 'eorf' ); ?> 
					<?php if($required == 1) { ?> <span class="required">*</span> <?php } ?>
				</label>

				<select class="js-example-basic-single" name="billing_state">
					<option value=""><?php echo _e('Select a county / state...', 'eorf'); ?></option>
					
					<?php foreach($states as $key => $value) { ?>
						<option value="<?php echo $key; ?>" <?php echo selected($se_state, $key); ?>><?php echo $value; ?></option>
					<?php } ?>
				</select>

				<?php if(isset($fmessage) && $message!='') { ?>
					<span style="width:100%;float: left"><?php echo $fmessage; ?></span>
				<?php } ?>
			</p>

			

			<?php } else { ?>
				<p id="statedrop" class="form-row <?php echo $width; ?>">
					<input type="hidden" name="billing_state" value="<?php echo $country; ?>" />
				</p>

			<?php } ?>

			<script type="text/javascript">
				jQuery(document).ready(function() {
				    jQuery('.js-example-basic-single').select2();
				});

				  
				

			</script>


		<?php die();
		}




		

	}


	new EO_Registration_Fields_Front();
}
