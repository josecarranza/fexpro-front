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

$getFexproproductsQuantity = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}show_fexpro_products",ARRAY_A);

/* _stock
_stock_status
_manage_stock */

foreach($getFexproproductsQuantity as $value)
{
	$product = wc_get_product( $value['vid'] );
	if(!empty($product->parent_id))
	{
	$parentProduct = wc_get_product( $product->parent_id );
	$brands = $parentProduct->get_attribute( 'pa_brand' );
	
		if(!in_array($parentProduct->get_id(), $return_array))
		{	
			echo $brands . "<br>";
			$parentProduct->save();
			array_push($return_array, $parentProduct->get_id());
		}
	}
}