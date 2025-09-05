<?php 
include_once('../../../../wp-load.php');
if(is_admin()) return;
global $wpdb;

$menu = wp_get_nav_menu_object("sidebar-spring-summer-22" );

if( $menu->slug == 'sidebar-spring-summer-22' ){
	$menuitems = wp_get_nav_menu_items( $menu->term_id, array( 'orderby' => 'object_id' , 'order' => 'DESC', 'update_post_term_cache' => false ) );

	$parentMenuItems = [];
	foreach ($menuitems as $value) {
		if(!empty($value->classes[0]) && $value->classes[0] == 'show-child'){
			$getParenObjectID[] = $value->db_id;	

			$parentMenuItems['parentMenuURL'][$value->object_id] = $value->url;
			$parentMenuItems['parentMenuID'][$value->object_id] = $value->db_id;

	        $product_ids_arr[$value->db_id]  = get_terms( 'product_cat', array('hide_empty' => 0,'parent' => $value->object_id));
	 
		}
	}

	
	$get_parents = [];
	foreach ($getParenObjectID as $currentMenuParentID) {
		foreach ($menuitems as $value) {
			if($value->menu_item_parent == $currentMenuParentID){
				$get_parents[] = $value->db_id;	
			}
		}
	}

	if(!empty($get_parents)){
		foreach($get_parents as $parent_id){
			$wpdb->query( "DELETE p, pm FROM wp_posts p INNER  JOIN wp_postmeta pm ON pm.post_id = p.ID WHERE p.ID=".$parent_id." AND p.post_type='nav_menu_item' ");	
		}
	}




	$ddd = [];
	foreach($product_ids_arr as $key20 => $terms_arr){
		foreach($terms_arr as $key31 => $term){
			if($term->name != 'Q1'){
				wp_update_nav_menu_item($menu->term_id, 0, array(
				 	  'menu-item-object-id' => $term->term_id,
					  'menu-item-object' => 'product_cat',
					  'menu-item-parent-id' => $key20,
					  'menu-item-type' => 'taxonomy',
					  'menu-item-status' => 'publish',
				));	
			}
			
		}
	}


}

