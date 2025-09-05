<?php 

class wi_widget_filter_delivery_date extends WP_Widget {
    // The construct part
	function __construct() {
        parent::__construct(
       // Base ID of your widget
       'wi_widget_filter_delivery_date', 
       // Widget name will appear in UI
       __('Filter Products by Delivery Date', 'wi_widget_filter_delivery_date_domain'), 
       // Widget description
       array( 'description' => __( 'Filter Products by Delivery Date', 'wi_widget_filter_delivery_date_domain' ), )
       );
    }
    public function form( $instance ) {
        $html='<p>
            <label >Title</label>
            <input class="widefat" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" type="text" value="'.(isset($instance["title"])?$instance["title"]:"").'" />
            </p>';
        echo $html;     
    }
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        
        return $instance;
    }
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
 
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
        
        $months = $this->get_next_months();
        array_unshift($months,array("date"=>"now","data_str"=>"IMMEDIATE"));

        $current_filters = $this->current_filters();

        $current_delivery = isset($current_filters["filter_delivery"])?explode(",",$current_filters["filter_delivery"]):array();
        unset($current_filters["filter_delivery"]);
        $current_uri=parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $final_url = get_site_url().$current_uri."?".urldecode(http_build_query($current_filters));

        $html='<ul class="woocommerce-widget-layered-nav-list">';
		foreach ($months as $item) {
            $new_filter=$current_delivery;
            $chosen = false;
            if(in_array($item["date"],$new_filter)){
                $pos = array_search($item["date"],$new_filter);
                unset($new_filter[$pos]);
                $chosen = true;
            }else{
                $new_filter[]=$item["date"];
            }
            if(count($new_filter)>0){
                $segment_filter = "&filter_delivery=".implode(",",$new_filter);
            }else{
                $segment_filter = "";
            }


            $html.='<li class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term '.($chosen?'chosen':'').'">';
            $html.='<a href="'.$final_url.$segment_filter.'" >'.$item["data_str"].'</a>';
            $html.="</li>";
        }
        $html.="</ul>";
        echo $html;
		//@include("view-wi-widget-delivery-date.php");
		echo $args['after_widget'];
    }

    function get_next_months(){
        
        $date=date("Y-m-01");
        $current_month = (int)date("m");
        $current_year = date("Y");
        $months=array();
        for($i=0;$i<6;$i++){
            if($i>0)
                $current_month=$current_month+1;
        
            if($current_month>12){
                $current_month=1;
                $current_year++;
            }
            $tmp_d=$current_year."-".($current_month<10?"0":"").$current_month;
            $tmp_str = date("F",strtotime($tmp_d."-01")).", ".$current_year;
            $months[]=array("date"=>$tmp_d,"data_str"=>strtoupper($tmp_str));
        }
        return $months;
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