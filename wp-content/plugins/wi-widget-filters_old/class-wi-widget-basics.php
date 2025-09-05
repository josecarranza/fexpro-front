<?php 

class wi_widget_filter_basics extends WP_Widget {
 
	// The construct part
	function __construct() {
	 	parent::__construct(
		// Base ID of your widget
		'wi_widget_filter_basics', 
		// Widget name will appear in UI
		__('Filter Products by only basics', 'wi_widget_filter_basics_domain'), 
		// Widget description
		array( 'description' => __( 'Filter Products by only basics', 'wi_widget_filter_basics_domain' ), )
		);
	}
	 
	// Creating widget front-end
	public function widget( $args, $instance ) {
	 	$title = apply_filters( 'widget_title', $instance['title'] );
 
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];

        $current_filters = $this->current_filters();

        $current_basics = isset($current_filters["pa_only_basics"]) && $current_filters["pa_only_basics"]==1?1:0;
        unset($current_filters["pa_only_basics"]);
        $current_uri=parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $final_url = get_site_url().$current_uri."?".urldecode(http_build_query($current_filters));
		$chosen = $current_basics == 1 ? true:false;
		$segment_filter="";
		if(!$chosen){
			$segment_filter="&pa_only_basics=1";
		}
        $html='<ul class="woocommerce-widget-layered-nav-list">';
		$html.='<li class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term '.($chosen?'chosen':'').'">';
            $html.='<a href="'.$final_url.$segment_filter.'" >ONLY BASICS</a>';
            $html.="</li>";
        $html.="</ul>";
        echo $html;
		//@include("view-wi-widget-delivery-date.php");
		echo $args['after_widget'];
	}
	 
	// Creating widget Backend
	public function form( $instance ) {

		 $html='<p>
		<label >Title</label>
		<input class="widefat" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" type="text" value="'.(isset($instance["title"])?$instance["title"]:"").'" />
		</p>';
	
		echo $html;
	}
	 
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
	 	$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		
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