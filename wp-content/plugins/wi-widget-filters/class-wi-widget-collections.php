<?php 
class wi_widget_filter_collections extends WP_Widget {
	function __construct() {
	 	parent::__construct(
		// Base ID of your widget
		'wi_widget_filter_collections', 
		// Widget name will appear in UI
		__('Filter Products by Collections', 'wi_widget_filter_collections_domain'), 
		// Widget description
		array( 'description' => __( 'Filter Products by Collections', 'wi_widget_filter_collections_domain' ), )
		);
	}
	public function form($instance){
		$html='<p>
	   <label>ID Main Category</label>
	   <input class="widefat" id="'.$this->get_field_id( 'main_category' ).'" name="'.$this->get_field_name( 'main_category' ).'" type="text" value="'.(isset($instance["main_category"])?$instance["main_category"]:"").'" />
	   </p>';
	   echo $html;
	}
	public function widget( $args, $instance ) {
		$main_category = $instance['main_category']??"";
		if($main_category!=""){
			

			$current_filters = $this->current_filters();
			
			$current_element_brand = [];
			if(isset($current_filters["f_bc_brand"]) && is_array($current_filters["f_bc_brand"])){
				$current_element_brand=array_map(function($item){return explode(",",$item);},$current_filters["f_bc_brand"]);
			}
			
			$current_element_collect = [];
			if(isset($current_filters["f_bc_collection"]) && is_array($current_filters["f_bc_collection"])){
				$current_element_collect=array_map(function($item){return explode(",",$item);},$current_filters["f_bc_collection"]);
			}

			 
			//unset($current_filters["f_bc_brand"]);
			unset($current_filters["f_bc_collection"]);
		 

			$fil=[
				"brands"    => $current_element_brand	
			];
			if(isset($current_filters["filter_gender"]) && $current_filters["filter_gender"]!=""){
				$fil["department"] = $current_filters["filter_gender"];
			}
		

			$user_config= $this->get_user_collections();
			
			if(count($user_config)>0){
				$fil["user_collections"] = $user_config;
			}

			$data = $this->get_data($main_category,$fil);

			$current_uri=parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
			$final_url = get_site_url().$current_uri."?".urldecode(http_build_query($current_filters));
			 
			
			
			@include("view-wi-widget-collections.php");
			
		}
	}
	
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['main_category'] = ( ! empty( $new_instance['main_category'] ) ) ? strip_tags( $new_instance['main_category'] ) : '';
	
		return $instance;
	}

	function get_data($id_main_category,$filtros=[]){
		global $wpdb;

		ini_set('display_errors', 1);
   		ini_set('display_startup_errors', 1);
   		error_reporting(E_ALL);
		
		if(count($filtros["brands"])>0){
			$one_arr = array_map('current', $filtros["brands"]);

			$sql2="
			SELECT t.name,t.slug,a.brand 
			FROM wi_collection_config a 
			INNER JOIN (wp_terms t INNER JOIN wp_term_taxonomy tt ON t.term_id=tt.term_id ) ON a.collection=t.slug AND tt.taxonomy='pa_collection'
			WHERE a.brand IN ('".(implode("','",$one_arr))."') 
			AND a.show_presale=1 ";
			if(isset($filtros["department"])){
				$sql2.="AND a.department='".$filtros["department"]."' ";
			}
			if(isset($filtros["user_collections"])){
				$sql2.="AND a.collection IN ('".(implode("','",$filtros["user_collections"]))."') ";
			}
			$sql2.=" group by t.slug ORDER BY t.name asc";
			//echo $sql2;
			$collections = $wpdb->get_results($sql2,ARRAY_A);
		
			
		}else{
			$collections=[];
		}

				
		return [
			"collections" => $collections
		];
	}
	function get_user_collections(){
		$user_id=get_current_user_id();

		$user_config = get_user_meta($user_id,"wp_capabilities");
    
		$user_config = is_array($user_config ) && count($user_config )>0 ? array_keys($user_config[0]) : [] ;

		global $wpdb;
		$sql = "SELECT distinct id_collection from wi_collection_roles a WHERE a.role IN ('".implode("','",$user_config)."')";
		//echo $sql;
		$r = $wpdb->get_results($sql,ARRAY_A);
		if(is_array($r) && count($r)>0){
		
			$user_config = array_column($r,"id_collection");
		}else{
			$user_config = [];
		}
 
		if(count($user_config)>0){
			global $wpdb;
			$r=$wpdb->get_results("select collection from wi_collection_config WHERE id IN (".implode(",",$user_config).")",ARRAY_A);
			$user_config = array_column($r,"collection");
		}
		return $user_config;
	}
	function current_filters(){
        $current_filters = isset($_GET) && count($_GET)>0 ? $_GET : array();
		
        $clean_filters=array();
        foreach ($current_filters as $key => $item) {
            $item= preg_replace("/[^a-zA-Z0-9\-,_ ]+/", "",$item);
			$item= str_replace(" ","+",$item);
            $clean_filters[preg_replace("/[^a-zA-Z0-9\-,_ ]+/", "",$key)] = $item;
        }
		/*echo "<pre>";
		print_r($clean_filters);
		echo "</pre>";*/
        return $clean_filters;
    }
}
 ?>