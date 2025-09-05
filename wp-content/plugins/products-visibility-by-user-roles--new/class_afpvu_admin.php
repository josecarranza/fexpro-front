<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( !class_exists( 'Addify_Products_Visibility_Admin' ) ) {

	class Addify_Products_Visibility_Admin extends Addify_Products_Visibility {

		public function __construct() {

			add_action( 'admin_menu', array( $this, 'afpvu_custom_menu_admin' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'afpvu_admin_scripts' ) );
			add_action('admin_init', array($this, 'afpvu_include_setting_files'));
			add_action('wp_ajax_afpvusearchProducts', array($this, 'afpvusearchProducts'));
		}

		public function afpvu_admin_scripts() {

			$current_screen = get_current_screen();


			if ('woocommerce_page_addify-products-visibility' == $current_screen->id) {
				wp_enqueue_style('thickbox');
				wp_enqueue_script('thickbox');
				wp_enqueue_script('media-upload');
				wp_enqueue_media();
				wp_enqueue_script('jquery-ui-accordion');
				wp_enqueue_style( 'afpvu-admin-css', plugins_url( 'assets/css/afpvu_admin.css', __FILE__ ), false, '1.0' );
				wp_enqueue_script( 'afpvu-admin-js', plugins_url( 'assets/js/afpvu_admin.js', __FILE__ ), false, '1.0' );
				$afpvu_data = array(
					'admin_url'  => admin_url('admin-ajax.php'),
					'nonce' => wp_create_nonce('afpvu-ajax-nonce'),
					
				);
				wp_localize_script( 'afpvu-admin-js', 'afpvu_php_vars', $afpvu_data );
				wp_enqueue_style( 'select2', plugins_url( '/assets/css/select2.css', __FILE__ ), false, '1.0' );
				wp_enqueue_script( 'select2', plugins_url( '/assets/js/select2.js', __FILE__ ), false, '1.0' );
			}
		}

		public function afpvu_custom_menu_admin() {

			if ( defined('AFB2B_PLUGIN_DIR') ) {
				return;
			}

			add_submenu_page( 'woocommerce', esc_html__('Products Visibility', 'addify_products_visibility'), esc_html__('Products Visibility', 'addify_products_visibility'), 'manage_options', 'addify-products-visibility', array($this, 'afpvu_module_settings') );
		}

		public function afpvu_module_settings() {

			if ( isset( $_GET[ 'tab' ] ) ) {  
				$active_tab = sanitize_text_field($_GET[ 'tab' ]);  
			} else {
				$active_tab = 'tab_one';
			}
			?>
				<div class="wrap">
					<h2><?php echo esc_html__('Products Visibility by User Roles', 'addify_products_visibility'); ?></h2>
					<?php settings_errors(); ?> 

					<h2 class="nav-tab-wrapper">  
						<a href="?page=addify-products-visibility&tab=tab_three" class="nav-tab <?php echo esc_attr($active_tab) == 'tab_three' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('General Settings', 'addify_products_visibility'); ?></a>
						<a href="?page=addify-products-visibility&tab=tab_one" class="nav-tab <?php echo esc_attr($active_tab) == 'tab_one' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('Global Visibility', 'addify_products_visibility'); ?></a> 
						<a href="?page=addify-products-visibility&tab=tab_two" class="nav-tab <?php echo esc_attr($active_tab) == 'tab_two' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('Visibility By User Roles', 'addify_products_visibility'); ?></a>
					</h2>

					<form method="post" action="options.php" class="addify_pro_visibility"> 
						<?php
						if ( 'tab_one' == $active_tab ) {  
							settings_fields( 'setting-group-1' );
							do_settings_sections( 'addify-products-visibility-1' );
						}

						if ( 'tab_two' == $active_tab ) {  
							settings_fields( 'setting-group-2' );
							do_settings_sections( 'addify-products-visibility-2' );
						}

						if ( 'tab_three' == $active_tab ) {  
							settings_fields( 'setting-group-3' );
							do_settings_sections( 'addify-products-visibility-3' );
						}

						?>
						<?php submit_button(); ?> 
					</form> 

				</div>
			<?php 

		}

		public function afpvu_include_setting_files() {

			include_once AFPVU_PLUGIN_DIR . 'settings/general.php';
			include_once AFPVU_PLUGIN_DIR . 'settings/global-visibility.php';
			include_once AFPVU_PLUGIN_DIR . 'settings/visibility-by-user-roles.php';
			
		}

		public function afpvusearchProducts() {

			

			if (isset($_POST['nonce']) && '' != $_POST['nonce']) {

				$nonce = sanitize_text_field( $_POST['nonce'] );
			} else {
				$nonce = 0;
			}

			if (isset($_POST['q']) && '' != $_POST['q']) {

				if ( ! wp_verify_nonce( $nonce, 'afpvu-ajax-nonce' ) ) {

					die ( 'Failed ajax security check!');
				}
				

				$pro = sanitize_text_field( $_POST['q'] );

			} else {

				$pro = '';

			}


			$data_array = array();
			$args       = array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'numberposts' => -1,
				's'	=>  $pro
			);
			$pros       = get_posts($args);

			if ( !empty($pros)) {

				foreach ($pros as $proo) {

					$title        = ( mb_strlen( $proo->post_title ) > 50 ) ? mb_substr( $proo->post_title, 0, 49 ) . '...' : $proo->post_title;
					$data_array[] = array( $proo->ID, $title ); // array( Post ID, Post Title )
				}
			}
			
			echo json_encode( $data_array );

			die();
		}

	}

	new Addify_Products_Visibility_Admin();

}
