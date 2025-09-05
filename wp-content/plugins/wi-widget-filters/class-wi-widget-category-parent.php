<?php 

class wi_widget_filter_category_parent extends WP_Widget {
	public $SLUG_STOCK =  3259;//"stock-inmediato";
    public $cats_stock     = array(4595,4104,1829);
    public $SLUG_PRESALE = 5931;//"presale";
    public $cats_presale = array(5935,5934,5932,5933,6372);//5935 - BOYS , 5934 - GIRLS , 5932 - MENS, 5933 - WOMENS, 6372 - KIDS
    public $is_presale=false;

	// The construct part
	function __construct() {
	 	parent::__construct(
		// Base ID of your widget
		'wi_widget_filter_category_parent', 
		// Widget name will appear in UI
		__('Filter Products by Category Parent', 'wi_widget_filter_category_parent_domain'), 
		// Widget description
		array( 'description' => __( 'Filter Products by Category Parent', 'wi_widget_filter_category_parent_domain' ), )
		);
	}
	 
	// Creating widget front-end
	public function widget( $args, $instance ) {
	 	$title = apply_filters( 'widget_title', $instance['title'] );
 
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		
		$ids=explode(",",$instance["cat_parent"]);	
		$param_name = $instance["param_name"];

		$current_filters = $this->current_filters();

		$currents_categories = isset($current_filters[$param_name]) && $current_filters[$param_name]!="" ? explode(",",$current_filters[$param_name]) : array();
		unset($current_filters[$param_name]);
		$current_uri=parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
		
		$final_url = get_site_url().$current_uri."?".urldecode(http_build_query($current_filters));
       

        $cat_names = $this->get_categories($ids);
		
        $html='<ul class="woocommerce-widget-layered-nav-list">';
        $chosen=false;
        foreach($cat_names as $catn){
			$catn->slug = sanitize_title($catn->name);
			$new_categories_filter=$currents_categories;
			$chosen = false;
			if(in_array($catn->slug,$new_categories_filter)){
				$pos = array_search($catn->slug,$new_categories_filter);
				unset($new_categories_filter[$pos]);
				$chosen = true;
			}else{
				$new_categories_filter[]=$catn->slug;
			}
			if(count($new_categories_filter)>0){
				$segment_filter = "&".$param_name."=".implode(",",$new_categories_filter);
			}else{
				$segment_filter = "";
			}

            $html.='<li style="display:block; width:100%; border:none;" class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term '.($chosen?'woocommerce-widget-layered-nav-list__item--chosen chosen':'').'">';

            $html.='<a href="'.$final_url.$segment_filter.'" >'.$catn->name.'</a>';

            $html.='</li>';
        }

        $html.="</ul>";
        
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

		$html.='<p>
		<label >IDs category parent</label>
		<input class="widefat" id="'.$this->get_field_id( 'cat_parent' ).'" name="'.$this->get_field_name( 'cat_parent' ).'" type="text" value="'.(isset($instance["cat_parent"])?$instance["cat_parent"]:"").'" />
		</p>';

		$html.='<p>
		<label >URL param name</label>
		<input class="widefat" id="'.$this->get_field_id( 'param_name' ).'" name="'.$this->get_field_name( 'param_name' ).'" type="text" value="'.(isset($instance["param_name"])?$instance["param_name"]:"").'" />
		</p>';
	
		echo $html;
	}
	 
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
	 	$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['cat_parent'] = ( ! empty( $new_instance['cat_parent'] ) ) ? strip_tags( $new_instance['cat_parent'] ) : '';
		$instance['param_name'] = ( ! empty( $new_instance['param_name'] ) ) ? strip_tags( $new_instance['param_name'] ) : '';
		
		return $instance;
	}

	function get_categories($ids=array()){

		global $wpdb;
		$sql="SELECT t.name FROM wp_terms t INNER JOIN wp_term_taxonomy tt ON t.term_id=tt.term_id WHERE tt.parent IN (".(implode(",",$ids)).") 
		GROUP BY t.name
		ORDER BY t.name ASC ";
	 
		$r=$wpdb->get_results($sql);
		
		return $r;
	}

    function get_category_names($level,$cat_id=0){
		
		$mains_cats=$this->cats_stock;
		if($this->is_presale){
			$mains_cats=$this->cats_presale;
		}

        global $wpdb;
        $sql=" SELECT * FROM (
            SELECT trim(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(NAME,'SPORTS ',''),'POP ',''),'GIRLS',''),'WOMENS',''),'MENS',''),'KIDS','')) as name
            FROM wp_terms tt1
            INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
            WHERE tt2.taxonomy='product_cat' and (name!='' and name!='Uncategorized' ) AND tt2.count>0 ";
            if($level==1){
                $sql.=" AND  tt2.parent IN (SELECT tt3.term_id FROM wp_term_taxonomy tt3 WHERE tt3.parent IN (".implode(",",$mains_cats)."))";
            }
            if($level==2){
                $sql.=" AND  tt2.parent IN (SELECT tt3.term_id FROM wp_term_taxonomy tt3 WHERE tt3.parent IN (".$cat_id.")) ";
            }
            if($level==3){
                $sql.=" AND tt2.parent = ".$cat_id;
            }
            if($level==4){
                $sql.=" AND tt2.term_id = ".$cat_id;
            }

            $sql.=") tmp
            GROUP BY tmp.name
            ORDER BY NAME ASC";
         $r=$wpdb->get_results($sql);
         return $r;
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