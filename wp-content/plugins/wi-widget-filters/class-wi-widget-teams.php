<?php 
class wi_widget_filter_teams extends WP_Widget {
	function __construct() {
	 	parent::__construct(
		// Base ID of your widget
		'wi_widget_filter_teams', 
		// Widget name will appear in UI
		__('Filter Products by Teams', 'wi_widget_filter_teams_domain'), 
		// Widget description
		array( 'description' => __( 'Filter Products by Teams', 'wi_widget_filter_teams_domain' ), )
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
			
			$current_element_team = [];
			if(isset($current_filters["f_bc_team"]) && is_array($current_filters["f_bc_team"])){
				$current_element_team=array_map(function($item){return explode(",",$item);},$current_filters["f_bc_team"]);
			}

			 
		 
			unset($current_filters["f_bc_team"]);
		 

			$fil=[
				"brands"    => $current_element_brand	
			];
		

			$data = $this->get_data($main_category,$fil);

			$current_uri=parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
			$final_url = get_site_url().$current_uri."?".urldecode(http_build_query($current_filters));
			 
			
			
			@include("view-wi-widget-teams.php");
			
		}
	}
	
	

	function get_data($id_main_category,$filtros=[]){
		global $wpdb;

		ini_set('display_errors', 1);
   		ini_set('display_startup_errors', 1);
   		error_reporting(E_ALL);
		
		if(count($filtros["brands"])>0){

			$sql_cats="SELECT t.* 
			FROM wp_term_taxonomy tt
			INNER JOIN wp_term_taxonomy tt2 ON tt.term_taxonomy_id=tt2.parent
			INNER JOIN wp_terms t ON tt2.term_id=t.term_id
			WHERE tt.parent=".$id_main_category." ";
		
			$ids_cats = $wpdb->get_results($sql_cats,ARRAY_A);
			$ids_cats = array_column($ids_cats,"term_id");

			$one_arr = array_map('current', $filtros["brands"]);

			$sql3="SELECT trim(pm.meta_value) team,  t2.slug brand
				FROM wp_term_relationships tr
				INNER JOIN wp_terms t ON tr.term_taxonomy_id=t.term_id AND tr.term_taxonomy_id IN (".implode(",",$ids_cats).") 
				INNER JOIN wp_term_relationships tr2 ON tr.object_id=tr2.object_id
				INNER JOIN wp_terms t2 ON tr2.term_taxonomy_id=t2.term_id 
				AND t2.slug IN ('".implode("','",$one_arr)."') 
				INNER JOIN wp_posts p0 ON tr2.object_id=p0.post_parent
				INNER JOIN wp_postmeta pm ON p0.ID=pm.post_id AND pm.meta_key='product_team' AND pm.meta_value !=''
				GROUP BY pm.meta_value
				ORDER BY pm.meta_value ASC ";
				
				$teams = $wpdb->get_results($sql3,ARRAY_A);
				$teams = array_map(function($item){
					return ["value"=>urlencode($item["team"]),"label"=>$item["team"],"brand"=>$item["brand"]];
				},$teams);
		
			
		}else{
			$teams=[];
		}

				
		return [
			"teams" => $teams
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