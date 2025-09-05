<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../wp-load.php');
$return_array = array();
$return_array1 = array();
$return_array2 = array();
$return_array3 = array();
$return_array4 = array();
global $wpdb;

//$getAllProducts = get_posts(array('post_type' => 'product_variation', 'post_status' => 'publish', 'posts_per_page' => -1));
$getAllProducts = get_posts(array('post_type' => 'product', 'post_status' => array('publish', 'private'), 'posts_per_page' => -1));
//$getAllFexproProducts = $wpdb->get_results("SELECT `vid` FROM {$wpdb->prefix}show_fexpro_products", ARRAY_A);
//print_r($getAllProducts);
//die();

foreach($getAllProducts as $value)
{
	if(has_term( 'youth', 'product_cat', $value->ID )) {
		echo $value->ID . "<br>";
		// (if needed) Get an instance of the WC_product object (from a dynamic product ID)
		$product = wc_get_product($value->ID);

		// Get children product variation IDs in an array
		$children_ids = $product->get_children();
		foreach($children_ids as $v)
		{
			$product1 = wc_get_product($v);
			/* $wpdb->update( 
				"{$wpdb->prefix}posts", 
				array( 
					'post_status' => 'publish',
				), 
				array( 'ID' => $v ), 
				array( 
					'%s'
				), 
				array( '%d' )
			); */
			$product1->save();
		}
		/* $wpdb->update( 
			"{$wpdb->prefix}posts", 
			array( 
				'post_status' => 'publish',
			), 
			array( 'ID' => $value->ID ), 
			array( 
				'%s'
			), 
			array( '%d' )
		); */
	}
}