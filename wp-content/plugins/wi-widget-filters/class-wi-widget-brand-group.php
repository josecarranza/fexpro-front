<?php 

class wi_widget_filter_brand_group extends WP_Widget {
 
	// The construct part
	function __construct() {
	 	parent::__construct(
		// Base ID of your widget
		'wi_widget_filter_brand_group', 
		// Widget name will appear in UI
		__('Filter Products by brand group', 'wi_widget_filter_brand_group_domain'), 
		// Widget description
		array( 'description' => __( 'Filter Products by brand group', 'wi_widget_filter_brand_group_domain' ), )
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

        $current_brand = isset($current_filters["filter_brand"])?explode(",",$current_filters["filter_brand"]):[]; 
		
        unset($current_filters["filter_brand"]);
        $current_uri=parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $final_url = get_site_url().$current_uri."?".urldecode(http_build_query($current_filters));
		$brands = $this->getBrands();
		$brands_list = array(
			"music_bands"=>"MUSIC BANDS",
			"movies_series"=>"MOVIES & SERIES",
			"pop_artists"=>"POP ARTISTS",
			"drinks"=>"DRINKS",
			"entertainment"=>"ENTERTAINMENT",
		);
		/*$options=array(
			"sold"=>"Previously sold",
			"not_sold" => "Not sold"
		);

        $html='<ul class="woocommerce-widget-layered-nav-list">';
		foreach($options as $key=>$label){

			$new_current_sold=$current_sold;
			$chosen = false;
			if(in_array($key,$new_current_sold)){
				$pos = array_search($key,$new_current_sold);
				unset($new_current_sold[$pos]);
				$chosen = true;
			}else{
				$new_current_sold[]=$key;
			}
			if(count($new_current_sold)>0){
				$segment_filter = "&pa_sold=".implode(",",$new_current_sold);
			}else{
				$segment_filter = "";
			}
			
			$html.='<li style="display:block; width:100%" class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term '.($chosen?'woocommerce-widget-layered-nav-list__item--chosen chosen':'').'">';
            $html.='<a href="'.$final_url.$segment_filter.'" >'.$label.'</a>';
            $html.="</li>";
		}

        $html.="</ul>";
        echo $html;*/

		@include("view-wi-widget-brand-group.php");
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

	function getBrands(){

		global $wpdb;
		$sql="SELECT b.term_id,b.name,b.slug,c.meta_value brand_group FROM wp_term_taxonomy a 
		INNER JOIN wp_terms b ON a.term_id=b.term_id
		INNER JOIN wp_termmeta c ON a.term_id=c.term_id AND c.meta_key='brand_group' AND (c.meta_value IS NOT NULL AND c.meta_value!='')
		WHERE a.taxonomy='pa_brand'
		ORDER BY b.name asc";

		$r=$wpdb->get_results($sql);

		return $r;
	}

}


 ?>