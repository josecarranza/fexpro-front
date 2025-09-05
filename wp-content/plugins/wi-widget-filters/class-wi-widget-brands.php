<?php 
class wi_widget_filter_brands extends WP_Widget {
	function __construct() {
	 	parent::__construct(
		// Base ID of your widget
		'wi_widget_filter_brans', 
		// Widget name will appear in UI
		__('Filter Products by Brands', 'wi_widget_filter_brans_domain'), 
		// Widget description
		array( 'description' => __( 'Filter Products by Brands', 'wi_widget_filter_brans_domain' ), )
		);
	}
	public function form($instance){
		$html='<p>
	   <label>ID Main Category</label>
	   <input class="widefat" id="'.$this->get_field_id( 'main_category' ).'" name="'.$this->get_field_name( 'main_category' ).'" type="text" value="'.(isset($instance["main_category"])?$instance["main_category"]:"").'" />
	   </p>';
	   echo $html;
	}
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['main_category'] = ( ! empty( $new_instance['main_category'] ) ) ? strip_tags( $new_instance['main_category'] ) : '';
	
		return $instance;
	}
	public function widget( $args, $instance ) {
		$main_category = $instance['main_category']??"";
		if($main_category!=""){
			$current_filters = $this->current_filters();

			$current_element_brand = [];
			if(isset($current_filters["f_bc_brand"]) && is_array($current_filters["f_bc_brand"])){
				$current_element_brand=array_map(function($item){return explode(",",$item);},$current_filters["f_bc_brand"]);
			}

			unset($current_filters["f_bc_brand"]);

			$fil=[
				"brands"    => $current_element_brand	
			];

		
			$data = $this->get_data($main_category,$fil);

			$current_uri=parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
			$final_url = get_site_url().$current_uri."?".urldecode(http_build_query($current_filters));

			@include("view-wi-widget-brands.php");
		}
	}
	
	

	function get_data($id_main_category,$filtros=[]){
		global $wpdb;

		ini_set('display_errors', 1);
   		ini_set('display_startup_errors', 1);
   		error_reporting(E_ALL);
		 
		$sql_cats="SELECT t.* 
		FROM wp_term_taxonomy tt
		INNER JOIN wp_term_taxonomy tt2 ON tt.term_taxonomy_id=tt2.parent
		INNER JOIN wp_terms t ON tt2.term_id=t.term_id
		WHERE tt.parent=".$id_main_category."
		 ";
		//echo $sql_cats;
		$ids_cats = $wpdb->get_results($sql_cats,ARRAY_A);
		$ids_cats = array_column($ids_cats,"term_id");
	 
		$sql="SELECT b.*  FROM wp_term_taxonomy a 
		INNER JOIN wp_terms b ON a.term_id=b.term_id
		INNER JOIN wp_term_relationships tr ON a.term_taxonomy_id=tr.term_taxonomy_id
		WHERE a.taxonomy='pa_brand'
		AND tr.object_id IN (
		SELECT tr.object_id 
		FROM wp_term_relationships tr
		WHERE tr.term_taxonomy_id IN (".implode(",",$ids_cats).")
		)
		GROUP BY b.term_id ORDER BY b.name";
		$r = $wpdb->get_results($sql,ARRAY_A);
		 
		$brands = array_map(function($item){
			return ["value"=>urlencode($item["slug"]),"label"=>$item["name"]];
		},$r);

		 
		

		return [
			"brands" => $brands
		];
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