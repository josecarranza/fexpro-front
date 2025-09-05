<?php 

class wi_widget_filter_stock extends WP_Widget {
 
	// The construct part
	function __construct() {
	 	parent::__construct(
		// Base ID of your widget
		'wi_widget_filter_stock', 
		// Widget name will appear in UI
		__('Filter Products by Stock', 'wi_widget_filter_stock_domain'), 
		// Widget description
		array( 'description' => __( 'Filter Products by Stock', 'wi_widget_filter_stock_domain' ), )
		);
	}
	 
	// Creating widget front-end
	public function widget( $args, $instance ) {
	 	$title = apply_filters( 'widget_title', $instance['title'] );
 
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];

        $current_filters = isset($_GET) && count($_GET)>0 ? $_GET : array();
		
		$clean_filters=array();
		foreach ($current_filters as $key => $item) {
			$item= preg_replace("/[^a-zA-Z0-9\-,_]+/", "",$item);
			$clean_filters[preg_replace("/[^a-zA-Z0-9\-,_]+/", "",$key)] = $item;
		}
		unset($clean_filters["filter_stock_min"]);
        unset($clean_filters["filter_stock_max"]);

        $min = isset($_GET["filter_stock_min"])?(int)$_GET["filter_stock_min"]:0;
        $max = isset($_GET["filter_stock_max"])?(int)$_GET["filter_stock_max"]:0;
		 
        $html='<form>
        <div class="wi_widget_filter_stock-control">
            <div><input type="number" name="filter_stock_min" value="'.($min!=0?$min:"").'" placeholder="Min" title="Minimun quantity is 24" min="24" onblur="this.value=((this.value!=\'\' && this.value<24)?24:this.value)"/></div>
            <div><input type="number" name="filter_stock_max" value="'.($max!=0?$max:"").'"  placeholder="Max" /></div>
            <div><button>Filter</button></div>';
            foreach ($clean_filters as $key=> $cf) {
                $html.='<input type="hidden" name="'.$key.'" value="'.$cf.'" />';
            }
        $html.='</div></form>';
        
        echo $html;

		//$categorias = $this->get_categories($root_category,$include_only);
		
		//$categorias_tree = $this->menu_tree($categorias,"parent");
		
		//@include("view-wi-widget-category2.php");
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

}


 ?>