<?php 
class wi_widget_filter_colors extends WP_Widget {
	function __construct() {
	 	parent::__construct(
		// Base ID of your widget
		'wi_widget_filter_color', 
		// Widget name will appear in UI
		__('Filter Products by Colors', 'wi_widget_filter_color_domain'), 
		// Widget description
		array( 'description' => __( 'Filter Products by Colors', 'wi_widget_filter_color_domain' ), )
		);
	}
	public function widget( $args, $instance ) {
		$colors=$this->colors_get();
		
		$current_filters = $this->current_filters();

		
		
		$current_color = [];
		if(isset($current_filters["filter_color"]) && $current_filters["filter_color"]!=""){
			$all_color = explode(",",$current_filters["filter_color"]);
			$current_color=[];
			foreach($all_color as $c){
				foreach ($colors as $key => $value) {
					$c_color = explode(",",$value["value"]);
					if(in_array($c,$c_color) && !in_array($value["value"],$current_color)){
						$current_color[]=$value["value"];
						break;
					}
			   }
			}
			
		}
	
		unset($current_filters["filter_color"]);
			
		$current_uri=parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
		$final_url = get_site_url().$current_uri."?".urldecode(http_build_query($current_filters));
			
		@include("view-wi-widget-colors.php");
	}

	function colors_get(){
		global $wpdb;
		$cat = get_queried_object();
		$catID = $cat->term_id;
		$sql="
			SELECT b.name, b.slug FROM wp_term_taxonomy a 
			INNER JOIN wp_terms b ON a.term_id=b.term_id
			INNER JOIN wp_term_relationships tr ON a.term_taxonomy_id=tr.term_taxonomy_id
			WHERE a.taxonomy='pa_color'
			AND tr.object_id IN (
			SELECT tr.object_id 
			FROM wp_term_relationships tr
			WHERE tr.term_taxonomy_id =".$catID."
			)
			GROUP BY b.term_id ORDER BY b.name";
			$r=$wpdb->get_results($sql);
			
		$colors = [];
		foreach($r as $item){
			$color_name = preg_replace('/[0-9\ ]+/', '', $item->name);
			$color_name = ucfirst(strtolower($color_name));
			if(!isset($colors[$color_name])){
				$colors[$color_name] = [];
			}
			if(!in_array($item->slug,$colors[$color_name] )){
				$colors[$color_name][]=$item->slug;
			}
		}
		$colors = array_map(function($item,$key){
			$slug = str_replace("/","-",strtolower($key));
			return ["text"=>$key,"value"=>implode(",",$item),"color"=>$slug];
		},$colors,array_keys($colors));
		return $colors;
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