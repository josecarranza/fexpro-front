<?php 
		include_once('../../../../wp-load.php');
		
		if(is_admin()) return;

			$menu_name = 'main_menu'; //menu slug
			$locations = get_nav_menu_locations();
			$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );


			$brand_parent = 28926;
			$delivery_date_parent = 28925;
			// Parent Id : 1504

			$category_slug = 'summer-spring-22';

			$query_args = array(
			    'status'    => 'publish',
			    'limit'     => -1,
			    'category'  => array( $category_slug ),
			);

			$data = array();
			foreach( wc_get_products($query_args) as $product ){
			    foreach( $product->get_attributes() as $taxonomy => $attribute ){
			        $attribute_name = wc_attribute_label( $taxonomy ); // Attribute name
			        // Or: $attribute_name = get_taxonomy( $taxonomy )->labels->singular_name;
			        foreach ( $attribute->get_terms() as $term ){
			        	if($taxonomy == 'pa_brand' || $taxonomy == 'pa_delivery-date'){
			      			$data[$taxonomy][$term->term_id] = array('term_name' => $term->name, 'term_slug' =>$term->slug  );
			        	}
			        }
			    }
			}

			//Remove before insert all prodcut attribute
			$menuitems = wp_get_nav_menu_items( $menu->term_id, array( 'orderby' => 'object_id' , 'order' => 'DESC', 'update_post_term_cache' => false ) );

	    	foreach ($menuitems as $value) {
	    		if($value->menu_item_parent == $brand_parent){
	    			$wpdb->query( "DELETE p, pm FROM wp_posts p INNER  JOIN wp_postmeta pm ON pm.post_id = p.ID WHERE pm.meta_key = '_menu_item_object_id' AND pm.meta_value = ".$value->object_id." AND p.post_type='nav_menu_item' ");
	    		}

	    		if($value->menu_item_parent == $delivery_date_parent){
	    			$wpdb->query( "DELETE p, pm FROM wp_posts p INNER  JOIN wp_postmeta pm ON pm.post_id = p.ID WHERE pm.meta_key = '_menu_item_object_id' AND pm.meta_value = ".$value->object_id." AND p.post_type='nav_menu_item' ");
	    		}

	    	}
	    	


			if(!empty($data)){
				foreach($data['pa_brand'] as $key => $brand){
					wp_update_nav_menu_item($menu->term_id, 0, array(
		 	    	      'menu-item-title' => $brand['term_name'],
						  'menu-item-url' => '/brand/?filter_brand='.$brand['term_slug'],
						  'menu-item-parent-id' => $brand_parent,
						  'menu-item-object' => 'custom',
						  'menu-item-type' => 'custom',
						  'menu-item-status' => 'publish',
					));
				}
				foreach($data['pa_delivery-date'] as $key => $delivery){
					wp_update_nav_menu_item($menu->term_id, 0, array(
		 	    	      'menu-item-title' => $delivery['term_name'],
						  'menu-item-url' => '/product-category/popup/?filter_delivery-date='.$delivery['term_slug'].'&query_type_brand=or',
						  'menu-item-parent-id' => $delivery_date_parent,
						  'menu-item-object' => 'custom',
						  'menu-item-type' => 'custom',
						  'menu-item-status' => 'publish',
					));
				}

			}
			


