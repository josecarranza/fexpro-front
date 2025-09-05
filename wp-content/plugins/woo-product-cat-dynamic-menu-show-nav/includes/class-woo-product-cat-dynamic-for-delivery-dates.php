<?php 
include_once('../../../../wp-load.php');
if(is_admin()) return;
global $wpdb;
$menu_name = 'main_menu'; //menu slug
$locations = get_nav_menu_locations();
$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );


$PreBookingSS22 = 31162;


if( $menu->slug == 'fexpro-purchased-stock-menu' ){
	$menuitems = wp_get_nav_menu_items( $menu->term_id, array( 'orderby' => 'object_id' , 'order' => 'DESC', 'update_post_term_cache' => false ) );

	$parentMenuItems = [];
	foreach ($menuitems as $value) {
		if($value->menu_item_parent == $PreBookingSS22){

			$getParenObjectID[] = $value->db_id;	
			$parentMenuItems['parentMenuURL'][$value->object_id] = $value->url;
			$parentMenuItems['parentMenuID'][$value->object_id] = $value->db_id;
			$parentMenuItems['P_Menu'][$value->db_id] = array('test' => array( $value) );

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
							$return_array4['pa_delivery_date'][$key9][$pa_delivery_date[0]->name][] = array( 'name'=>$pa_delivery_date[0]->name,'slug'=>$pa_delivery_date[0]->slug);
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


		$return_array5 = array();
		foreach($return_array4['pa_delivery_date'] as $key11 => $value){
			foreach($value as $key12 => $pbd){
				$return_array5[$key11][$key12] =  array_values(array_unique($pbd));
				$return_array5[$key11][$key12] =  array_values(array_unique($pbd));
			}
		}
		
	}

	
	


	//Remove Delivery Dates from parent category
	$get_parents = [];
	$jjjj = [];
	foreach($return_array4['pa_delivery_date'] as $key13 => $deliveryDateArr){
		foreach($deliveryDateArr as $key14 => $deliveryDate){
			$parentMenuID = $return_array3[$key13]['others']['parentMenuID'][$key13];


			foreach ($menuitems as $parnetId) {
				if($parnetId->menu_item_parent==$parentMenuID){
					foreach ($menuitems as $childIds) {
						if($childIds->menu_item_parent == $parnetId->db_id){
							if(!in_array( $childIds->db_id, $jjjj)){
								array_push($jjjj,$childIds->db_id);	
								$get_childs[] = $childIds->db_id;
							}
						}
					}
					$get_parents[] = $parnetId->db_id;		
				}
			}

		}

	}

	$get_parents = array_unique($get_parents);
	$get_parents = array_values($get_parents);

					
	if(!empty($get_childs)){
		foreach($get_childs as $child_id){
			$wpdb->query( "DELETE p, pm FROM wp_posts p INNER  JOIN wp_postmeta pm ON pm.post_id = p.ID WHERE p.ID=".$child_id." AND p.post_type='nav_menu_item' ");	
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
	$counter=0;
	$merge33 = array();
	foreach($return_array5 as $key20 => $deliveryDate){
			foreach($deliveryDate as $key21 => $paDeliveryDate){
				$merge33[$key20][$key21]['pa_brand'] = count($return_array2[$key20][$key21]);	
				$merge33[$key20][$key21]['delivery_date'] = $paDeliveryDate;
				$merge33[$key20][$key21]['products'] = $getChildElementArr[$counter];
			}
		$counter++;
	}



	/*echo "<pre>";
	print_r($merge33);
	die;*/

	// Inserting to Parent 
	$dddd = [];
	foreach($merge33 as $key30 => $value30){
		foreach($value30 as $key31 => $value31){
			$product_dbid = $value31['products']->db_id;
			$parentMenuURL = $value31['products']->url;
			$classes='';
			if($value31['pa_brand'] > 10){
				$classes = 'make-width-wide';
			}
			foreach($value31['delivery_date'] as $delivery_date ){

				wp_update_nav_menu_item($menu->term_id, 0, array(
					  'menu-item-title' => $delivery_date['name'],
					  'menu-item-url' => $parentMenuURL.'?filter_delivery-date='.$delivery_date['slug'].'&query_type_brand=or',
					  'menu-item-parent-id' => $product_dbid,
					  'menu-item-object' => 'custom',
					  'menu-item-type' => 'custom',
					  'menu-item-classes' => $classes,
					  'menu-item-status' => 'publish'
				));
			}


			
		}
	}
	
	
	

}

