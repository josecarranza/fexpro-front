<?php 

class wi_widget_filter_category extends WP_Widget {
 
	// The construct part
	function __construct() {
	 	parent::__construct(
		// Base ID of your widget
		'wi_widget_filter_category', 
		// Widget name will appear in UI
		__('Filter Products by Category', 'wi_widget_filter_category_domain'), 
		// Widget description
		array( 'description' => __( 'Filter Products by Category', 'wi_widget_filter_category_domain' ), )
		);
	}
	 
	// Creating widget front-end
	public function widget( $args, $instance ) {
	 	$title = apply_filters( 'widget_title', $instance['title'] );
 
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		 
		// This is where you run the code and display the output
		$root_category = $instance["root_category"];
		$include_only = $instance["include_only"];
		
		$categorias = $this->get_categories($root_category,$include_only);
		
		$categorias_tree = $this->menu_tree($categorias,"parent");
		
		@include("view-wi-widget-category.php");
		echo $args['after_widget'];
	}
	 
	// Creating widget Backend
	public function form( $instance ) {

		 $html='<p>
		<label >Title</label>
		<input class="widefat" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" type="text" value="'.(isset($instance["title"])?$instance["title"]:"").'" />
		</p>';
		$categorias = $this->get_categories();
		
		$categorias_tree = $this->menu_tree($categorias,"parent");
		$selected_cat = isset($instance["root_category"])?$instance["root_category"]:"";
		
		/* $html.='<p>
		<label>Root category</label>
		<select class="widefat" name="'.$this->get_field_name( 'root_category' ).'" ><option>-Select-</option>'.$this->build_select_tree($categorias_tree,0,$selected_cat).'</select>
		
		</p>';*/

		$html.='<p>
		<label>Root category</label>
		<input class="widefat" id="'.$this->get_field_name( 'root_category' ).'" name="'.$this->get_field_name( 'root_category' ).'" type="text" value="'.(isset($instance["root_category"])?$instance["root_category"]:"").'" />
		</p>';

		$html.='<p>
		<label>Include Only</label>
		<input class="widefat" id="'.$this->get_field_name( 'include_only' ).'" name="'.$this->get_field_name( 'include_only' ).'" type="text" value="'.(isset($instance["include_only"])?$instance["include_only"]:"").'" />
		</p>';

		echo $html;
	}
	 
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
	 	$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['root_category'] = ( ! empty( $new_instance['root_category'] ) ) ? strip_tags( $new_instance['root_category'] ) : '';
		$instance['include_only'] = ( ! empty( $new_instance['include_only'] ) ) ? strip_tags( $new_instance['include_only'] ) : '';
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
	function menu_tree($menu_ids,$name_parent,$search_seccion=null,&$seccion=array()){
		$index=array();
		$tree=array();
		foreach ($menu_ids as $key=>$var) {
			$var=array_shift($menu_ids);

			if ((string)$var[$name_parent]==='0') {
				$tree[$key]=$var;
				if(isset($index[$key])){
					$c = $index[$key]["children"];
					$tree[$key]=$var;
					$tree[$key]["children"] = $c;
				}
				$index[$key]=&$tree[$key];
			} else if (isset($index[$var[$name_parent]])) {
				if (!isset($index[$var[$name_parent]]['children'])){
					$index[$var[$name_parent]]['children']=array();
				}
				if(isset($index[$key])){
					$c = $index[$key]["children"];
					$index[$key] = $var;
					$index[$key]["children"] = $c;
					$index[$var[$name_parent]]['children'][$key]=$index[$key];
				}else{
					$index[$var[$name_parent]]['children'][$key]=$var;
				}
				$index[$key]=&$index[$var[$name_parent]]['children'][$key];
			} else {
				$index[$var[$name_parent]]=array();
				$index[$var[$name_parent]]['children']=array();
				$index[$var[$name_parent]]['children'][$key]=$var;
				$index[$key]=&$index[$var[$name_parent]]['children'][$key];
				array_push($menu_ids,$var);
			}
		}
		if($search_seccion!=null && isset($index[$search_seccion])){
			$seccion = $index[$search_seccion];
		}
		
		return $tree;
	}
	function build_select_tree($tree,$level=0,$selected=""){
		$margin=str_repeat("- ",$level);
		$html="";
		foreach ($tree as $key => $item) {
			$html.='<option value="'.$item["id"].'" '.($selected!="" && $selected==$item["id"]?"selected":"").'>'.$margin." ".$item["name"].'</option>';
			if(isset($item["children"]) && count($item["children"])>0){
				$html.=$this->build_select_tree($item["children"],$level+1,$selected);
			}
		}
		return $html;
	}
}


 ?>