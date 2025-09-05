<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../wp-load.php');
$return_array = array();
$return_array1 = array();
$return_array2 = array();
$return_array3 = array();
$return_array4 = array();
global $wpdb;

$getFexproproductsQuantity = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}show_fexpro_products_ss22",ARRAY_A);

/* _stock
_stock_status
_manage_stock */

foreach($getFexproproductsQuantity as $value)
{
	if((get_post_meta($value['vid'], '_stock', true) == 0 || get_post_meta($value['vid'], '_stock', true)) && get_post_meta($value['vid'], '_manage_stock', true) == 'yes')
	{
		echo $value['vid'] . "<br>";
	}
	else
	{
		update_post_meta($value['vid'], '_stock', $value['fexpro_stock']);
		update_post_meta($value['vid'], '_manage_stock', 'yes');
		$product = wc_get_product( $value['vid'] );
		$product->save();
	}
	
	/* $product = wc_get_product( $value['vid'] );
	$wpdb->update( 
		"{$wpdb->prefix}posts", 
		array( 
			'post_status' => 'publish',
		), 
		array( 'ID' => $value['vid'] ), 
		array( 
			'%s'
		), 
		array( '%d' )
	); */
	//$product1 = wc_get_product( $product->parent_id );
	//echo $product->parent_id;
	//$product->save();
}
	
?>


