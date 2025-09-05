<?php 
class wi_widget_filter_brand_collection extends WP_Widget {
	function __construct() {
	 	parent::__construct(
		// Base ID of your widget
		'wi_widget_filter_brand_collection', 
		// Widget name will appear in UI
		__('Filter Products by Brand, Collection and Team', 'wi_widget_filter_brand_collection_domain'), 
		// Widget description
		array( 'description' => __( 'Filter Products by Brand, Collection and Team', 'wi_widget_filter_brand_collection_domain' ), )
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

			$current_element_team = [];
			if(isset($current_filters["f_bc_team"]) && is_array($current_filters["f_bc_team"])){
				$current_element_team=array_map(function($item){return explode(",",$item);},$current_filters["f_bc_team"]);
			}

			unset($current_filters["f_bc_brand"]);
			unset($current_filters["f_bc_collection"]);
			unset($current_filters["f_bc_team"]);

			$fil=[
				"division" => $current_filters["f_division"]??"",
				"brands"    => $current_element_brand	
			];
			if(isset($current_filters["filter_gender"]) && $current_filters["filter_gender"]!=""){
				$fil["department"] = $current_filters["filter_gender"];
			}
		

			$user_config= $this->get_user_collections();
			
			if(count($user_config)>0){
				//$fil["user_collections"] = $user_config;
			}

			$data = $this->get_data($main_category,$fil);

			$current_uri=parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
			$final_url = get_site_url().$current_uri."?".urldecode(http_build_query($current_filters));
			 
			
			
			@include("view-wi-widget-brand-collection.php");
			
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
		if($filtros["division"]!=""){

			if($filtros["division"]=="universities"){
				$filtros["division"]="universities%' OR t.slug like 'univerisities%";
			}
			$sql_cats="SELECT t.* 
			FROM wp_term_taxonomy tt
			INNER JOIN wp_term_taxonomy tt2 ON tt.term_taxonomy_id=tt2.parent
			INNER JOIN wp_terms t ON tt2.term_id=t.term_id
			WHERE tt.parent=".$id_main_category."
			AND t.slug LIKE '".$filtros["division"]."%'";
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
			$ids=array_column($r,"term_id");
			$brands = $r;//array_column($r,"name","slug");
			$brands = array_map(function($item){
				return ["value"=>urlencode($item["slug"]),"label"=>$item["name"]];
			},$brands);

			if(count($filtros["brands"])>0){
				$one_arr = array_map('current', $filtros["brands"]);
			
				/*$sql2="SELECT  t3.name, t3.slug, t2.slug brand
				FROM wp_term_relationships tr
				INNER JOIN wp_terms t ON tr.term_taxonomy_id=t.term_id
				INNER JOIN wp_term_relationships tr2 ON tr.object_id=tr2.object_id
				INNER JOIN wp_terms t2 ON tr2.term_taxonomy_id=t2.term_id
				INNER JOIN (
					wp_term_relationships tr3 
					INNER JOIN wp_term_taxonomy tt ON tr3.term_taxonomy_id=tt.term_taxonomy_id AND tt.taxonomy='pa_collection'
					INNER JOIN wp_terms t3 ON tt.term_id=t3.term_id
				) ON tr2.object_id=tr3.object_id
				WHERE tr.term_taxonomy_id IN (".implode(",",$ids_cats).") AND t2.slug IN ('".(implode("','",$one_arr))."')
				GROUP BY t3.slug
				ORDER BY t3.name";*/

				$sql2="SELECT t.name,t.slug,a.brand FROM wi_collection_config a 
				INNER JOIN (wp_terms t INNER JOIN wp_term_taxonomy tt ON t.term_id=tt.term_id ) ON a.collection=t.slug AND tt.taxonomy='pa_collection'
				WHERE a.brand IN ('".(implode("','",$one_arr))."') ";
				if(isset($filtros["department"])){
					$sql2.="AND a.department='".$filtros["department"]."' ";
				}
				if(isset($filtros["user_collections"])){
					$sql2.="AND a.collection IN ('".(implode("','",$filtros["user_collections"]))."') ";
				}
				$sql2.=" group by t.slug ORDER BY t.name asc";
				
				$collections = $wpdb->get_results($sql2,ARRAY_A);
			
				 
				$sql3="SELECT trim(pm.meta_value) team,  t2.slug brand
				FROM wp_term_relationships tr
				INNER JOIN wp_terms t ON tr.term_taxonomy_id=t.term_id AND tr.term_taxonomy_id IN (".implode(",",$ids_cats).") 
				INNER JOIN wp_term_relationships tr2 ON tr.object_id=tr2.object_id
				INNER JOIN wp_terms t2 ON tr2.term_taxonomy_id=t2.term_id AND t2.slug IN ('".(implode("','",$one_arr))."') ";
				
				$sql3.=" INNER JOIN wp_posts p0 ON tr2.object_id=p0.post_parent
				INNER JOIN wp_postmeta pm ON p0.ID=pm.post_id AND pm.meta_key='product_team' AND pm.meta_value !=''
				GROUP BY pm.meta_value
				ORDER BY pm.meta_value ASC ";
				
				$teams = $wpdb->get_results($sql3,ARRAY_A);
				$teams = array_map(function($item){
					return ["value"=>urlencode($item["team"]),"label"=>$item["team"],"brand"=>$item["brand"]];
				},$teams);
			}else{
				$collections=[];
				$teams=[];
			}

				
		}else{
			$brands=[];
			$collections=[];
			$teams=[];
		}
		

		return [
			"brands" => $brands,
			"collections" => $collections,
			"teams" => $teams
		];
	}
	function get_user_collections(){
		$user_id=get_current_user_id();
		$user_config = get_user_meta($user_id,"wi_collection_config");
		$user_config = isset($user_config[0]) ? json_decode($user_config[0]):[];
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