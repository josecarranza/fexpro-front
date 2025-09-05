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

$orders = wc_get_orders( array(
    'limit'    => -1,
    'status'   => array('wc-processing'),
	'return' => 'ids',
) );

//print_r($orders);

//$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}show_fexpro_products_hidden");

foreach($orders as $order_id)
{
	$getFexproOrders = get_post_meta($order_id, '_billing_company', true);
	$order = wc_get_order( $order_id );
	if($getFexproOrders == 'Fexpro Incorporated')
	{
		//echo $order_id . "<br>";
		foreach ( $order->get_items() as $item_id => $item ) {
		   $product_id = $item->get_product_id();
		   $variation_id = $item->get_variation_id();
		   $getVSKU = get_post_meta($variation_id, '_sku', true);
			if(!empty($product_id) && !empty($variation_id))
			{
				/* $quantity = $item->get_quantity();
				$final_result3[$variation_id . " > " . $getVSKU][] = $item_id;
				$final_result4[$variation_id][] = $quantity; */
				echo $product_id . "<br>";
			}
		}
	}
}

	
?>


