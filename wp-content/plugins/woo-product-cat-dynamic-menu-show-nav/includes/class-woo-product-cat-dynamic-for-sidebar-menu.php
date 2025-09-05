<?php 
include_once('../../../../wp-load.php');
if(is_admin()) return;
global $wpdb;

$menu = wp_get_nav_menu_object("sidebar-spring-summer-22" );
if( $menu->slug == 'sidebar-spring-summer-22' ){
	$menuitems = wp_get_nav_menu_items( $menu->term_id, array( 'orderby' => 'object_id' , 'order' => 'DESC', 'update_post_term_cache' => false ) );

	$parentMenuItems = [];
	foreach ($menuitems as $value) {
		if(!empty($value->classes[0]) && $value->classes[0] == 'show-child' ){
			$getParenObjectID[] = $value->db_id;	

			$parentMenuItems['parentMenuURL'][$value->object_id] = $value->url;
			$parentMenuItems['parentMenuID'][$value->object_id] = $value->db_id;

			$args = array(
			    'post_type'             => 'product',
			    'post_status'           => 'publish',
			    'ignore_sticky_posts'   => 1,
			    'posts_per_page'        => '-1',

			    'tax_query'             => array(
			        array(
			            'taxonomy'      => 'product_cat',
			            'field' => 'term_id', //This is optional, as it defaults to 'term_id'
			            'terms'         => $value->object_id,
			            'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
			        )
			    ),
			    'fields' => 'ids',
			);

			$product_ids_arr[] = new WP_Query($args);	
		}
		


	}

	$return_array3 = [];
	foreach($product_ids_arr as $posts_data){
		$return_array3[$posts_data->query_vars['term_id']] = $posts_data->posts;
		$return_array3[$posts_data->query_vars['term_id']]['others'] = $parentMenuItems;
	}

	$return_array4 = [];
	if($return_array3){
		foreach($return_array3 as $key9 => $productIdArr){
		
			foreach($productIdArr as $key10 => $productId){

				if(!$key10['others']){
					if(wc_get_product_terms( $productId, 'pa_delivery-date' )){
						$pa_delivery_date = wc_get_product_terms( $productId, 'pa_delivery-date' );
						$pa_brand = wc_get_product_terms( $productId, 'pa_brand' );
						$return_array4['product_ids'][$key9][] = $productId;

						if(!in_array($pa_delivery_date[0]->name, $return_array1))
						{
							$return_array4['pa_delivery_date'][$key9][] = array( 'name'=>$pa_delivery_date[0]->name,'slug'=>$pa_delivery_date[0]->slug);
							array_push($return_array1, $pa_delivery_date[0]->name);
						}

						$return_array4['pa_brand'][$key9][$pa_delivery_date[0]->name][] = $pa_brand[0]->name;

					}

				}
				
			}

			
		}
		$return_array2 = array();
		foreach($return_array4['pa_brand'] as $key11 => $value){
			foreach($value as $key12 => $pbd){
				$return_array2[$key11][$key12] =  array_values(array_unique($pbd));
				$return_array3[$key11][$key12] =  array_values(array_unique($pbd));
			}
		}
	}


	//Remove Delivery Dates from parent category
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


	// Get Child Element Arr for 
	foreach ($getParenObjectID as $currentMenuParentID) {
		foreach ($menuitems as $value) {
			if($value->db_id == $currentMenuParentID){
				$getChildElementArr[] = $value;

			}
		}
	}

	
	//Merge Both array to single
	$counter=0;
	$merge33 = array();
	foreach($return_array2 as $key15 => $brands){
		foreach($brands as $key16 => $pa_brnd){
			$merge33[$key15][$key16]['brands'] = $pa_brnd;
			$merge33[$key15][$key16]['products'] = $getChildElementArr[$counter];
		}
		$counter++;
	}


	$dddd = [];
	foreach($merge33 as $key30 => $value30){
		foreach($value30 as $key31 => $value31){
			$product_dbid = $value31['products']->db_id;
			$parentMenuURL = $value31['products']->url;
			foreach($value31['brands'] as $brand ){
				wp_update_nav_menu_item($menu->term_id, 0, array(
					  'menu-item-title' => $brand,
					  'menu-item-url' => $parentMenuURL.'?filter_brand='.strtolower($brand),
					  'menu-item-parent-id' => $product_dbid,
					  'menu-item-object' => 'custom',
					  'menu-item-type' => 'custom',
					  'menu-item-status' => 'publish'
				));
			}
			
		}
	}

}
