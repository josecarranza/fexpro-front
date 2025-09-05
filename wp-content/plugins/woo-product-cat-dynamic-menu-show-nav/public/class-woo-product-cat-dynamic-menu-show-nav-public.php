<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.webindiainc.com
 * @since      1.0.0
 *
 * @package    Woo_Product_Cat_Dynamic_Menu_Show_Nav
 * @subpackage Woo_Product_Cat_Dynamic_Menu_Show_Nav/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woo_Product_Cat_Dynamic_Menu_Show_Nav
 * @subpackage Woo_Product_Cat_Dynamic_Menu_Show_Nav/public
 * @author     Vishal <vishalrathod@webindiainc.com>
 */
class Woo_Product_Cat_Dynamic_Menu_Show_Nav_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Product_Cat_Dynamic_Menu_Show_Nav_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Product_Cat_Dynamic_Menu_Show_Nav_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-product-cat-dynamic-menu-show-nav-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Product_Cat_Dynamic_Menu_Show_Nav_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Product_Cat_Dynamic_Menu_Show_Nav_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-product-cat-dynamic-menu-show-nav-public.js', array( 'jquery' ), $this->version, false );

	}

/*	public function addNewMenuItemBasedOnCategory($items , $args){
		global $wpdb;
		$menu_name = 'main_menu'; //menu slug
		$locations = get_nav_menu_locations();
		$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
		if( $menu->slug == 'fexpro-dynamic-primary-menu' ){
	    	$menuitems = wp_get_nav_menu_items( $menu->term_id, array( 'orderby' => 'object_id' , 'order' => 'DESC', 'update_post_term_cache' => false ) );


	    	foreach ($menuitems as $value) {
	    			$newArr[] = $value->object_id;	    
	    			$newArr1[] = $value;				
	    			$newArr2[] = $value->post_parent;	
	    	}
	    	
	    	$taxonomies = get_terms( array(
				'taxonomy' => 'product_cat',
				'hide_empty' => false
			) );
		

			if ( !empty($taxonomies) ) {
				

				foreach( $taxonomies as $key => $value ) {

					if(get_option("taxonomy_" . $value->term_id)){
						$term_meta_array = get_option("taxonomy_" . $value->term_id);
						// Remove menu hierarchy				
						if(empty($term_meta_array['daynamic_menu_id'])){
							
									$args_query = array(
								        'taxonomy' => 'product_cat', 
								        'hide_empty' => true, 
								        'child_of' => $term_meta_array['remove_menu_id']
								    );

									//Parent Category Arr of main array
									foreach ( get_terms( $args_query ) as $term ) {
					    	 			if( $term->parent == $term_meta_array['remove_menu_id'] ) {
					    	 				$parent_arr[] = $term->term_id;

					    	 				//get child category of parent cat

					    	 				$args_query1 = array(
										        'taxonomy' => 'product_cat', 
										        'hide_empty' => true, 
										        'child_of' => $term->term_id
										    );

										    foreach ( get_terms( $args_query1 ) as $childTerm ) {
										    	if( $childTerm->parent == $term->term_id ) {
										    		//Remove child category item first
										    		$wpdb->query( "DELETE p, pm FROM wp_posts p INNER  JOIN wp_postmeta pm ON pm.post_id = p.ID WHERE pm.meta_key = '_menu_item_object_id' AND pm.meta_value = ".$childTerm->term_id." AND p.post_type='nav_menu_item' ");
						 				
										    		//$child_arr[] = $childTerm->term_id;
										    	}
										    }
					    	 			}
					    	 		}

					    	 		if(!empty($parent_arr)){
					    	 			foreach($parent_arr as $parentId){
					    	 				// Remove all child parent after child remove
					    	 				$wpdb->query( "DELETE p, pm FROM wp_posts p INNER  JOIN wp_postmeta pm ON pm.post_id = p.ID WHERE pm.meta_key = '_menu_item_object_id' AND pm.meta_value = ".$parentId." AND p.post_type='nav_menu_item' ");
					    	 			}
					    	 		}

					    	 		// Remove Main parent of all child category
					    	 		$wpdb->query( "DELETE p, pm FROM wp_posts p INNER  JOIN wp_postmeta pm ON pm.post_id = p.ID WHERE pm.meta_key = '_menu_item_object_id' AND pm.meta_value = ".$term_meta_array['remove_menu_id']." AND p.post_type='nav_menu_item' ");
						 			delete_option('taxonomy_'.$term_meta_array['remove_menu_id']);

						}


						//Remove switch before new place

						if(!empty($term_meta_array['switch_menu_id']) && !empty($term_meta_array['daynamic_menu_id'])){
	    					foreach ($newArr1 as $parentids) {
	    						if($parentids->menu_item_parent == $term_meta_array['switch_menu_id']){
	    							$parent_ids[] = $parentids->db_id;	
	    						}
	    					}

	    					foreach ($newArr1 as $childids) {
	    						if($childids->menu_item_parent == $parent_ids[0]){
	    							$childs_arr[] = $childids->db_id;	
	    							$wpdb->query( "DELETE p, pm FROM wp_posts p INNER  JOIN wp_postmeta pm ON pm.post_id = p.ID WHERE p.ID=".$childids->db_id." AND p.post_type='nav_menu_item' ");
	    						}
	    					}
	    					// Remove Category from parent 
	    					if(!empty($parent_ids)){
	    						$wpdb->query( "DELETE p, pm FROM wp_posts p INNER  JOIN wp_postmeta pm ON pm.post_id = p.ID WHERE p.ID=".$parent_ids[0]." AND p.post_type='nav_menu_item' ");
	    					}


						}else{}
							
						// Add Parent Category Only
						if(!in_array($value->term_id, $newArr)){
							wp_update_nav_menu_item($menu->term_id, 0, array(
					    	 	  'menu-item-object-id' => $value->term_id,
								  'menu-item-object' => 'product_cat',
								  'menu-item-parent-id' => $term_meta_array['daynamic_menu_id'],
								  'menu-item-type' => 'taxonomy',
								  'menu-item-status' => 'publish',
							));
						}else{

						}
					}
					
				}


				


				//Adding Child Category lists based on parent
				foreach ($newArr1 as $value) {
					if ( $value->menu_item_parent != 0 ){
						$args_query = array(
					        'taxonomy' => 'product_cat', 
					        'hide_empty' => true, 
					        'child_of' => $value->object_id
					    );

					    foreach ( get_terms( $args_query ) as $term ) {
					    	 if( $term->parent == $value->object_id ) {
					    	 	if(!in_array($term->parent, $newArr2)){
					    	 		wp_update_nav_menu_item($menu->term_id, 0, array(
							    	 	  'menu-item-object-id' => $term->term_id,
										  'menu-item-object' => 'product_cat',
										  'menu-item-parent-id' => $value->db_id,
										  'menu-item-type' => 'taxonomy',
										  'menu-item-status' => 'publish',
									));
					    	 	}else{
					    	 	}
            				 }
					    }
					}
				}


		
			}
			
	    }

	    return $items;
	}*/



}
