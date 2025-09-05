<?php 
class wi_widget_filter_product_type extends WP_Widget {
	function __construct() {
	 	parent::__construct(
		// Base ID of your widget
		'wi_widget_filter_product_type', 
		// Widget name will appear in UI
		__('Filter Products by Product type', 'wi_widget_filter_product_type_domain'), 
		// Widget description
		array( 'description' => __( 'Filter Products by Product type', 'wi_widget_filter_product_type_domain' ), )
		);
	}
 
	public function form( $instance ) {

		$html='<p>
	   <label >Title</label>
	   <input class="widefat" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" type="text" value="'.(isset($instance["title"])?$instance["title"]:"").'" />
	   </p>';

	   $html.='<p>
	   <label >Slug type</label>
	   <input class="widefat" id="'.$this->get_field_id( 'slug_type' ).'" name="'.$this->get_field_name( 'slug_type' ).'" type="text" value="'.(isset($instance["slug_type"])?$instance["slug_type"]:"").'" />
	   </p>';
   
	   echo $html;
   }
   // Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
	   $instance = array();
	   $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	   $instance['slug_type'] = ( ! empty( $new_instance['slug_type'] ) ) ? strip_tags( $new_instance['slug_type'] ) : '';
	   return $instance;
	}
	public function widget( $args, $instance ) {
		//$q=get_queried_object();
		$slug_type = $instance['slug_type']??"";
		$title = $instance['title']??"";
	    $data = $this->filter_product_type($slug_type);

		$current_filters = $this->current_filters();

		$current_product_type = [];
		if(isset($current_filters["product_type_".$slug_type]) && $current_filters["product_type_".$slug_type]!=""){
			$current_product_type=explode(",",$current_filters["product_type_".$slug_type]);
		}
		unset($current_filters["product_type_".$slug_type]);

		$current_uri=parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
		$final_url = get_site_url().$current_uri."?".urldecode(http_build_query($current_filters));

		@include("view-wi-widget-product-type.php");
	}

	function filter_product_type($type=""){

		global $wpdb;
		$q=get_queried_object();
		$id_cat = $q->term_id?? 0;

		$include_core = $id_cat == 5931 ? true : false;

        $sql="SELECT * FROM (
        SELECT trim(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(NAME,'SPORTS ',''),'POP ',''),'GIRLS',''),'WOMENS',''),'MENS',''),'KIDS',''),'UNIVERISITIES','')) as name
        FROM wp_terms tt1
        INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id AND tt2.count > 0
        WHERE tt2.taxonomy='product_cat' and (name!='' and name!='Uncategorized' ) 
            AND  tt2.parent IN ( ";
            if($type==""){
                $sql.="SELECT tt3.term_id FROM wp_term_taxonomy tt3 WHERE tt3.parent IN (SELECT tt3.term_id  FROM wp_term_taxonomy tt3 WHERE tt3.parent = ".$id_cat.") ";
            }else{
                $sql.="SELECT tt3.term_id FROM wp_term_taxonomy tt3 INNER JOIN wp_terms t3 ON tt3.term_id=t3.term_id 
				
				WHERE tt3.parent IN (SELECT tt3.term_id  FROM wp_term_taxonomy tt3 WHERE tt3.parent = ".$id_cat.") AND t3.name LIKE '%".$type."%' ";
			}
        $sql.="        ) ";
		if($include_core && $type!=""){
			$sql.="
			UNION ALL
			SELECT t3.name
			FROM wp_term_relationships trs1
			INNER JOIN (
					 wp_terms t2
					 INNER JOIN wp_term_relationships trs2 ON t2.term_id=trs2.term_taxonomy_id AND t2.name LIKE '%".$type."%'
				 ) ON trs2.object_id=trs1.object_id
				 INNER JOIN wp_posts pp 
				 ON pp.ID=trs1.object_id AND pp.post_status = 'publish'
				 INNER JOIN (
					 wp_terms t3
					 INNER JOIN wp_term_taxonomy tt3 ON t3.term_id=tt3.term_id
					 INNER JOIN wp_term_relationships trs3 ON t3.term_id = trs3.term_taxonomy_id
				 ) ON tt3.parent = t2.term_id AND trs3.object_id = pp.ID
			WHERE trs1.term_taxonomy_id=6497
			
			";
		}

        $sql.=" ) tmp
        GROUP BY tmp.name
        ORDER BY NAME ASC";
		//echo "<!-- {$sql} -->";
        $r=$wpdb->get_results($sql);
        
        $current_filters = array();
        $current_filters["pa_product_type"] = isset($_GET["pa_product_type"])?explode(",",$_GET["pa_product_type"]):array();
        $current_filters["pa_product_type"] = array_map(function($item){ return addslashes($item);},$current_filters["pa_product_type"]);
        
        $products_type=array();
        foreach ($r as $value) {
            $item=array();
            $item["text"] = $value->name;
            $item["value"] = sanitize_title($value->name);
            $item["checked"] = in_array($item["value"],$current_filters["pa_product_type"]);
            $products_type[]=$item;
        }
        return $products_type;

    }
	function current_filters(){
        $current_filters = isset($_GET) && count($_GET)>0 ? $_GET : array();
		
        $clean_filters=array();
        foreach ($current_filters as $key => $item) {
            $item= preg_replace("/[^a-zA-Z0-9\-,_ \+]+/", "",$item);
			$item= str_replace(" ","+",$item);
            $clean_filters[preg_replace("/[^a-zA-Z0-9\-,_ \+]+/", "",$key)] = $item;
        }
		/*echo "<pre>";
		print_r($clean_filters);
		echo "</pre>";*/
        return $clean_filters;
    }
}
