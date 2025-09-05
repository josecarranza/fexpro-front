<?php 

class wi_widget_filter_category2 extends WP_Widget {
	public $SLUG_STOCK =  3259;//"stock-inmediato";
    public $cats_stock     = array(4595,4104,1829);
    public $SLUG_PRESALE = 5931;//"presale";
    public $cats_presale = array(5935,5934,5932,5933,6372);//5935 - BOYS , 5934 - GIRLS , 5932 - MENS, 5933 - WOMENS, 6372 - KIDS
    public $is_presale=false;

	// The construct part
	function __construct() {
	 	parent::__construct(
		// Base ID of your widget
		'wi_widget_filter_category2', 
		// Widget name will appear in UI
		__('Filter Products by Category Resumen', 'wi_widget_filter_category2_domain'), 
		// Widget description
		array( 'description' => __( 'Filter Products by Category Resumen', 'wi_widget_filter_category2_domain' ), )
		);
	}
	 
	// Creating widget front-end
	public function widget( $args, $instance ) {
	 	$title = apply_filters( 'widget_title', $instance['title'] );
 
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		
		$root_category=$this->SLUG_STOCK;

        $query_current = get_queried_object();

		$ancestors = get_ancestors( $query_current->term_id, 'product_cat' );  
		if(in_array($this->SLUG_PRESALE,$ancestors) || $query_current->term_id == $this->SLUG_PRESALE){
			$this->is_presale=true;
			$root_category=$this->SLUG_PRESALE;
		}
		
		

        $category = get_term_by( 'slug', $query_current->slug, 'product_cat' );
        $cat_current = $category;
        $level=1;
        if($category->term_id!=$root_category){
            for($i=1;$i<=4;$i++){
                $level++;
                if($category->parent==$root_category){
                    break;
                }else{
                    $category = get_term($category->parent, 'product_cat' );
                }
                
            }
        }
        
		$current_filters = isset($_GET) && count($_GET)>0 ? $_GET : array();
		
		$clean_filters=array();
		foreach ($current_filters as $key => $item) {
			$item= preg_replace("/[^a-zA-Z0-9\-,_]+/", "",$item);
			$clean_filters[preg_replace("/[^a-zA-Z0-9\-,_]+/", "",$key)] = $item;
		}


		$currents_categories = isset($clean_filters["filter_category2"]) && $clean_filters["filter_category2"]!="" ? explode(",",$clean_filters["filter_category2"]) : array();
		unset($clean_filters["filter_category2"]);
		$current_uri=parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
		
		$final_url = get_site_url().$current_uri."?".urldecode(http_build_query($clean_filters));
       

        $cat_names = $this->get_category_names($level,$cat_current->term_id);

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
				$segment_filter = "&filter_category2=".implode(",",$new_categories_filter);
			}else{
				$segment_filter = "";
			}

            $html.='<li class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term '.($chosen?'chosen':'').'">';

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
	
		echo $html;
	}
	 
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
	 	$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		
		return $instance;
	}

	function get_categories($parent=0,$include_only=""){
		global $wpdb;
		$sql="SELECT a.term_id id, a.parent, b.name, b.slug 
			FROM wp_term_taxonomy a
			INNER JOIN wp_terms b ON a.term_id=b.term_id
			WHERE a.taxonomy='product_cat'";
		if($parent!=0){
			$include_only=$include_only!=""?$include_only:$parent;
			$parent=$include_only!=""?$parent.",".$include_only:$parent;
			$sql.="
			AND (a.term_id IN (".$parent.") OR a.parent IN (".$include_only.") 
			OR a.parent IN (SELECT t.term_id FROM wp_term_taxonomy t WHERE t.parent IN (".$include_only.")  UNION SELECT t.term_id FROM wp_term_taxonomy t WHERE t.parent IN (SELECT t.term_id FROM wp_term_taxonomy t WHERE t.parent IN (".$include_only.") ) ))
			-- GROUP BY b.name
			";
		}
		//echo $sql;
		$sql.="ORDER BY b.name ASC";
		$r=$wpdb->get_results($sql,ARRAY_A);
		$r = array_column($r,null,"id");
	
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

}


 ?>