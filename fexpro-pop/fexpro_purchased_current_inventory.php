<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../wp-load.php");
//include("../../woocommerce/woocommerce.php");
global $wpdb;
//{"Username":"ApiUser","Password":"Cosmo.2020"}

$return_array = array();
$return_array1 = array();
$return_array2 = array();
$totalOfUnitPurchased = 0;
$totalOfAmount = 0;
global $wpdb;

$orders = wc_get_orders( array(
    'limit'    => -1,
    //'status'   => array('wc-pending'),
    'status' => array('wc-pending', 'wc-processing', 'wc-completed'),
	'return' => 'ids',
) );

foreach($orders as $order_id)
{
	$getFexproOrders = get_post_meta($order_id, '_billing_company', true);
	$order = wc_get_order( $order_id );
	//$order_items  = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
	if($getFexproOrders == 'Fexpro Incorporated')
	{
		foreach ( $order->get_items() as $item_id => $item ) {
		$a = array();
		   $product_id = $item->get_product_id();
		   $variation_id = $item->get_variation_id();
			if(!empty($product_id) && !empty($variation_id))
			{
				/* if( has_term( array( 'popup'), 'product_cat' ,  $product_id) ) 
				{ */
					$getCustomerID = get_post_meta($order_id, '_customer_user', true);
					$final_result1[$variation_id][] = $item_id;			
					//$final_result2[$variation_id][] = $order_id;			
				/* } */
			}
		}
	}
}
	
$delimiter = ",";  
$headers = 
array(
	"Sku",
	"Available Stock"
); 

$fp = fopen('product-stock.csv', 'wb');
fputcsv($fp, $headers, $delimiter);

	foreach ($final_result1 as $key => $res) {
		$sku = get_post_meta($key, '_sku', true);
		$e = get_post_meta($key, '_stock', true);
		if($e)
		{
			if($e < 0)
			{
				$e = 0;
				
			}
			else
			{
				$e = $e;
			}
		}
		else
		{
			//$e = "Stock limit removed";
			$e = "";
		}
		$csv = array($sku, $e);
		fputcsv($fp,$csv,$delimiter);
	}
fclose($fp);	