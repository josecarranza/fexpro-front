<?php 

class wi_widget_filter_attribute extends WP_Widget {
 
	// The construct part
	function __construct() {
	 	parent::__construct(
		// Base ID of your widget
		'wi_widget_filter_attribute', 
		// Widget name will appear in UI
		__('Filter Products by only attribute', 'wi_widget_filter_attribute_domain'), 
		// Widget description
		array( 'description' => __( 'Filter Products by only attribute', 'wi_widget_filter_attribute_domain' ), )
		);
	}
	 
	// Creating widget front-end
	public function widget( $args, $instance ) {
		$current_filters = $this->current_filters();
		$title = isset($instance['title']) && $instance['title']!=''? $instance['title'] : '';
		$att_slug = isset($instance['att_slug']) && $instance['att_slug']!=''? $instance['att_slug'] : '';
		
		$current_element_att = [];
		if(isset($current_filters[$att_slug]) ){
			//$current_element_att=array_map(function($item){return explode(",",$item);},$current_filters[$att_slug]);
			$current_element_att= explode(",",$current_filters[$att_slug]);
		}

		unset($current_filters[$att_slug]);

		$fil=[
			"att_elements"    => $current_element_att	
		];

	
		$data = $this->get_data($att_slug,$fil);
		

		$current_uri=parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
		$final_url = get_site_url().$current_uri."?".urldecode(http_build_query($current_filters));

		@include("view-wi-widget-attribute.php");
	}
	 
	// Creating widget Backend
	public function form( $instance ) {

		 $html='<p>
		<label >Title</label>
		<input class="widefat" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" type="text" value="'.(isset($instance["title"])?$instance["title"]:"").'" />
		<label >Attribute Slug</label>
		<input class="widefat" id="'.$this->get_field_id( 'att_slug' ).'" name="'.$this->get_field_name( 'att_slug' ).'" type="text" value="'.(isset($instance["att_slug"])?$instance["att_slug"]:"").'" />
		</p>';
	
		echo $html;
	}
	 
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
	 	$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['att_slug'] = ( ! empty( $new_instance['att_slug'] ) ) ? strip_tags( $new_instance['att_slug'] ) : '';
		
		return $instance;
	}
	function current_filters(){
        $current_filters = isset($_GET) && count($_GET)>0 ? $_GET : array();
      
        $clean_filters=array();
        foreach ($current_filters as $key => $item) {
            $item= preg_replace("/[^a-zA-Z0-9\-,_]+/", "",$item);
            $clean_filters[preg_replace("/[^a-zA-Z0-9\-,_]+/", "",$key)] = $item;
        }
        return $clean_filters;
    }
	function get_data($att_slug,$filtros=[]){
		global $wpdb;

		ini_set('display_errors', 1);
   		ini_set('display_startup_errors', 1);
   		error_reporting(E_ALL);
		 
	 
		$sql="SELECT a.term_id, a.name, a.slug 
		FROM wp_terms a
		INNER JOIN wp_term_taxonomy b ON a.term_id = b.term_id
		WHERE b.taxonomy='{$att_slug}'
		ORDER BY a.name ASC";
		$r = $wpdb->get_results($sql,ARRAY_A);
		 
		$att_data = array_map(function($item){
			return ["value"=>urlencode($item["slug"]),"label"=>$item["name"]];
		},$r);

		 
		

		return [
			"att_data" => $att_data
		];
	}

}


 ?>