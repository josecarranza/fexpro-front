<?php 

class WiOrderStatus{
	public $db;
	public $order_status_list = [];
	public $config_key = "wi_order_status_list";
	public $config_key_global_brand = "wi_order_status_global_brand";
	public function __construct() {

        /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/
        global $wpdb;
        $this->db = $wpdb;

		$this->order_status_list = $this->load_status();

		add_filter( 'woocommerce_cod_process_payment_order_status', array($this,'set_default_payment_order_status'),10,2);

		//add_action( 'woocommerce_order_status_ss24-q2', array($this,'stock_update'));
		add_filter('wc_order_statuses', array($this,'register_new_status'),20,1);
		add_filter('woocommerce_register_shop_order_post_statuses',  array($this,'register_new_status_details'));

		add_filter( 'bulk_actions-edit-shop_order', array($this,'bulk_change_order_status'),11,1);
		//$this->register_new_status();

		add_action('admin_menu', array($this,"setup_menu") ,100,1 );
		add_action('admin_init', array($this,"setup_config_option") ,100,1 );

		add_action('wp_ajax_wi_order_status_save', array($this,"wi_order_status_save"));
        add_action('wp_ajax_nopriv_wi_order_status_save', array($this,"wi_order_status_save"));

		add_action('wp_ajax_wi_order_status_global_brand_save', array($this,"wi_order_status_global_brand_save"));
        add_action('wp_ajax_nopriv_wi_order_status_global_brand_save', array($this,"wi_order_status_global_brand_save"));

		add_filter( 'wc_order_is_editable', array($this,'wc_make_processing_orders_editable'), 20, 2 );

		add_filter( 'woocommerce_my_account_my_orders_actions', array($this,'edit_order_my_account_orders_actions'), 50, 2 );

	}
	function setup_config_option(){
	/* 	add_settings_field(  
			'notify_email_order_stock',                      
			'Nofify email for Stock orders',               
			 "callback_save_notify_email",
			 array($this,'myprefix_settings-section-name'),
			'general',
			'default'
		); */

		// Register a new setting
		register_setting('general', 'notify_email_order_stock');

		// Add a new section to the General Settings page
		//add_settings_section('custom_section', 'Custom Settings', 'custom_section_callback', 'general');
	
		// Add a field to the new section
		add_settings_field('notify_email_order_stock', 'Notify email for Stock orders', array($this,'callback_save_notify_email'), 'general', 'default');
		
	}
	function callback_save_notify_email(){
		$value = get_option('notify_email_order_stock');
    	echo '<input type="text" id="notify_email_order_stock" name="notify_email_order_stock" style="width:400px" value="' . esc_attr($value) . '" />';
	}
	function load_status(){

		$config = json_decode(get_option($this->config_key),true);
		if(is_array($config)){
			$list = $config;
		}else{
			$list = [];
		}
		//$list = [["code"=>"presale_test","name"=>"Presale Test"]];
		return $list;
	}
	function save_config($data){
		$json = json_encode($data);
		update_option($this->config_key,$json);
	}
	function set_default_payment_order_status( $order_status, $order ) {
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
		$default = "completed";
		foreach($this->order_status_list as $os){
			if(isset($os["default"]) && $os["default"] == 1){
				$default = $os["code"];
				break;
			}
		}

		$order_items = $order->get_items();

        $first_item  = current($order_items)->get_data();
        $metas = array_map(function($item){ return $item->get_data();},$first_item['meta_data']);
        $metas = array_column($metas,"value","key");
        $type_order = "available";
        if(isset($metas['is_presale']) && $metas['is_presale'] == '1'){
            $type_order = "presale";
			$default = $default; // default value in presale order status config
			//verify custom order status for global brand
			$product = wc_get_product(current($order_items)->get_product_id());
            $atts = $product->get_attributes();

			if(isset($atts['pa_global-brand'])){
				$op = $atts['pa_global-brand']->get_options();
				$_order_status = $this->get_order_status_by_global_brand($op[0]);
				$default = $_order_status !=null ? $_order_status : $default;
			}
			//$default = "pending";
        }else{
            if(isset($metas['type_stock']) && $metas['type_stock'] == 'future'){
                $type_order = "future";
				$default = "futurestock"; // default value for orders stock future
            }else{
                $type_order = "available"; // available and available_china
				$default = "stock"; // default for orders stock
            }

			//send main notification for stock orders
			$order_id = $order->get_id();

			$_email = trim(get_option('notify_email_order_stock'));
			if($_email!=""){
				$html = "New order registered<br> <h4>ORDER: {$order_id}</h4>";
				wi_mail($_email,"New stock order registered",$html);
			}
        }

		
		//return 'presale5';
		//return 'presale6';
		//return 'presale7';
		//return 'presale7';
		//return 'ss24-q2';
		return $default;
	}
	function stock_update($order_id){
		

		// Lets grab the order

		$order = wc_get_order( $order_id );

		// This is how to grab line items from the order 

		$line_items = $order->get_items();



		// This loops over line items

		foreach ( $line_items as $item_id => $item ) {

			// This will be a product

			$product_id = $item->get_product_id();

			$variation_id = $item->get_variation_id();

			//echo $product_id;

			if($product_id != 0)

			{

				if(get_post_meta($variation_id, '_manage_stock', true) == 'yes')

				{

					$product = wc_get_product($product_id); 

					if($product->is_type( 'variable' ))

					{

						$variations = $product->get_available_variations(); 

						$variations_id = wp_list_pluck( $variations, 'variation_id' );	

						//print_r($variations_id);

						$k = wc_get_product_terms( $product_id, 'pa_stock', array( 'fields' => 'names' ) );

						//print_r($k);

						foreach($k as $vk)

						{

							wp_remove_object_terms( $product_id, $vk, 'pa_stock' );

						}

						foreach($variations_id as $value)

						{

							$c = 0;

							$d = 0;

							for($i=1;$i<11;$i++)

							{

								if(get_post_meta( $value, 'size_box_qty'.$i, true ))

								{

									$c += get_post_meta( $value, 'size_box_qty'.$i, true );

								}

							}

							//echo $c . "<br>";

							$e = get_post_meta($value, '_stock', true);

							if($e < 0)

							{

								$e = 0;

							}

							else

							{

								$e = $e;

							}

							if(!empty($e) || $e != 0)

							{

								$d = $c * $e;

								$term_taxonomy_ids = wp_set_object_terms( $product_id, "$d", 'pa_stock', true );

								$thedata = Array(

									'pa_stock'=>Array( 

										'name'=>'pa_stock', 

										'value'=>"$d",

										'is_visible' => '1',

										'is_variation' => '0',

										'is_taxonomy' => '1'

									)

								);

								//First getting the Post Meta

								$_product_attributes = get_post_meta($product_id, '_product_attributes', TRUE);

								//Updating the Post Meta

								update_post_meta($product_id, '_product_attributes', array_merge($_product_attributes, $thedata));

							}

						}

					}

				}

			}		

		}


	}
	function register_new_status($order_statuses){
		 
		foreach($this->order_status_list as $item){
			if(isset($item["code"]) && isset($item["name"])){
				$order_statuses['wc-'.$item["code"]] = _x( $item["name"], 'Order status', 'woocommerce' );
			}
		}
		return $order_statuses;
	}
	function register_new_status_details($order_statuses){
		 

		foreach($this->order_status_list as $item){
			if(isset($item["code"]) && isset($item["name"])){
				$order_statuses['wc-'.$item["code"]] = array(

					'label'                     => _x( $item["name"], 'Order status', 'woocommerce' ),
				
					'public'                    => false,
				
					'exclude_from_search'       => false,
				
					'show_in_admin_all_list'    => true,
				
					'show_in_admin_status_list' => true,
				
					'label_count'               => _n_noop( $item["name"].'<span class="count">(%s)</span>', $item["name"].'<span class="count">(%s)</span>', 'woocommerce' ),
				
					);
			}
		}
		return $order_statuses;
	}

	function bulk_change_order_status($bulk_actions){
		 
		foreach($this->order_status_list as $item){
			if(isset($item["code"]) && isset($item["name"])){
				$bulk_actions['mark_'.$item["code"]] = 'Change status to '.$item["name"].' Status';
			}
		}
		return $bulk_actions;
		
	}

	function setup_menu(){
		 
 

		add_submenu_page(
			'woocommerce',             // Parent menu slug (WooCommerce menu)
			'Order Status',          // Page title
			'Order Status',          // Menu title
			'manage_woocommerce',      // Capability required to access
			'wi-order-status-config',     // Menu slug (should be unique)
			array($this,"order_status_admin_page_init")      // Callback function to display the page
		);

		
	}

	function order_status_admin_page_init(){
	 
		$status_list = $this->load_status();
		$global_brands = $this->get_global_brands();
		include(WI_PLUGIN_ORDERSTATUS_PATH."/views/view-order-status.php");
	}

	function wi_order_status_save(){
		$post = json_decode(file_get_contents('php://input'));

		if(is_array($post) && count($post)>0){
			$this->save_config($post);
		}else{
			$this->save_config(null);
		}
		$json["error"] = 0;
		$json["data"] = $this->load_status();
		echo wp_send_json($json);
		wp_die();
	}

	

	 function wc_make_processing_orders_editable( $is_editable, $order ) {
	 	$orders_code = array_column($this->order_status_list,"code");
	    
	    if(in_array($order->get_status(),$orders_code)){
	    	$is_editable = true;
	    }

	    return $is_editable;

	}

	
 

	function edit_order_my_account_orders_actions( $actions, $order ) {
		$orders_code = array_column($this->order_status_list,"code");
	    if ( in_array($order->get_status(),$orders_code)) {

	        $actions['edit-order'] = array(

	            'url'  => wp_nonce_url( add_query_arg( array( 'order_again' => $order->get_id(), 'edit_order' => $order->get_id() ) ), 'woocommerce-order_again' ),

	            'name' => __( 'Edit Order', 'woocommerce' )

	        );

	    }

	    return $actions;

	}

	function get_global_brands(){

		$sql = "SELECT * 
			from wp_terms t 
			inner join wp_term_taxonomy tt on t.term_id = tt.term_id
			where tt.taxonomy='pa_global-brand'
			and t.slug in ('fexpro','nike','mitchell-ness','fifa')";
		
		$g_brands = $this->db->get_results($sql);
		$config = json_decode(get_option($this->config_key_global_brand),true);
		$config = is_array($config) && count($config)>0 ? array_column($config,'order_status','term_id') : [];
		if(is_array($g_brands)){
			foreach($g_brands as $i=> $gb){
				$g_brands[$i]->order_status = isset($config[$gb->term_id]) ? $config[$gb->term_id] : null;
			}
		}
		return $g_brands;
	}
	
	function wi_order_status_global_brand_save(){
		$post = json_decode(file_get_contents('php://input'));
		if(is_array($post) && count($post)>0){
		
			$json = json_encode($post);
			update_option($this->config_key_global_brand,$json);
		}else{
			update_option($this->config_key_global_brand,null);
		}
		$json["error"] = 0;
		wp_die();
	}

	function get_order_status_by_global_brand($term_id){

		$config = json_decode(get_option($this->config_key_global_brand),true);
		$status = is_array($config) ? array_column($config,'order_status','term_id'):[];
		return isset($status[$term_id]) ? $status[$term_id] : null;
	}
}

?>