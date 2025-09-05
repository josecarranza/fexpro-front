<?php 
class wi_widget_filter_static_filter extends WP_Widget {
	function __construct() {
	 	parent::__construct(
		// Base ID of your widget
		'wi_widget_filter_static_filter', 
		// Widget name will appear in UI
		__('Filter Products by static custom filters', 'wi_widget_filter_static_filter_domain'), 
		// Widget description
		array( 'description' => __( 'Filter Products by static custom filters', 'wi_widget_filter_static_filter_domain' ), )
		);
	}
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$values = $instance['values_element']??"";
		$values = explode("\n",$values);
		$values = array_map(function($item){
			$item = explode("|",$item);
			$label = trim($item[0]); 
			$val_ = trim($item[1]??"");
			return ["value"=>$val_,"label"=>$label];
		},$values);
		$type = $instance["type_element"];
		$default = $instance["default_value_element"] ?? "";
		$url_param = $instance["url_param"]??"";

		
		
		$current_filters = $this->current_filters();

        $current_element = isset($current_filters[$url_param])?explode(",",$current_filters[$url_param]):[]; 
		
        unset($current_filters[$url_param]);
        $current_uri=parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $final_url = get_site_url().$current_uri."?".urldecode(http_build_query($current_filters));
		$url_clear=get_site_url().$current_uri;
		$selected=implode(",",$current_element);

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		@include("view-wi-widget-static-filter.php");
		

		echo $args['after_widget'];
	}
	// Creating widget Backend
	public function form( $instance ) {

		$html='<p>
	   <label >Title</label>
	   <input class="widefat" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" type="text" value="'.(isset($instance["title"])?$instance["title"]:"").'" />
	   </p>';

	   $html.='<p>
	   <label >Display type</label>
	   <select class="widefat" id="'.$this->get_field_id( 'type_element' ).'" name="'.$this->get_field_name( 'type_element' ).'">
			<option value="select" '.(isset($instance["type_element"]) && $instance["type_element"]=="select" ?"selected" :"").'>Select</option>
			<option value="radio" '.(isset($instance["type_element"]) && $instance["type_element"]=="radio" ?"selected" :"").'>Radio</option>
			<option value="tag" '.(isset($instance["type_element"]) && $instance["type_element"]=="tag" ?"selected" :"").'>Tag</option>
			<option value="clear" '.(isset($instance["type_element"]) && $instance["type_element"]=="clear" ?"selected" :"").'>Clear filters</option>
			<option value="shopby" '.(isset($instance["type_element"]) && $instance["type_element"]=="shopby" ?"selected" :"").'>Shop by</option>
	   </select>
	   
	   </p>';
	   $html.='<p>
	   <label >Values</label>
	  <textarea class="widefat" id="'.$this->get_field_id( 'values_element' ).'" name="'.$this->get_field_name( 'values_element' ).'" placeholder="Label | Value">'.($instance["values_element"]??"").'</textarea>
	   </p>';
	   $html.='<p>
	   <label >Default value</label>
	   <input class="widefat" id="'.$this->get_field_id( 'default_value_element' ).'" name="'.$this->get_field_name( 'default_value_element' ).'" type="text" value="'.(isset($instance["default_value_element"])?$instance["default_value_element"]:"").'" />
	   </p>';
	   $html.='<p>
	   <label >URL param</label>
	   <input class="widefat" id="'.$this->get_field_id( 'url_param' ).'" name="'.$this->get_field_name( 'url_param' ).'" type="text" value="'.(isset($instance["url_param"])?$instance["url_param"]:"").'" />
	   </p>';
	   echo $html;
   }
	
   // Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['type_element'] = ( ! empty( $new_instance['type_element'] ) ) ? strip_tags( $new_instance['type_element'] ) : '';
		$instance['values_element'] = ( ! empty( $new_instance['values_element'] ) ) ? strip_tags( $new_instance['values_element'] ) : '';
		$instance['default_value_element'] = ( ! empty( $new_instance['default_value_element'] ) ) ? strip_tags( $new_instance['default_value_element'] ) : '';
		$instance['url_param'] = ( ! empty( $new_instance['url_param'] ) ) ? strip_tags( $new_instance['url_param'] ) : '';
		
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
}

 ?>