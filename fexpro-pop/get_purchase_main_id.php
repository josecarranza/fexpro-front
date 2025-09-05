<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../wp-load.php');
$return_array = array();
$return_array1 = array();
$return_array2 = array();
$totalOfUnitPurchased = 0;
$totalOfAmount = 0;
global $wpdb;

$orders = wc_get_orders( array(
    'limit'    => -1,
    //'status'   => array('wc-pending'),
    'status' => array('wc-processing'),
	'return' => 'ids',
) );

foreach($orders as $order_id)
{
	
	$order = wc_get_order( $order_id );
	//$order_items  = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
	foreach ( $order->get_items() as $item_id => $item ) {
	$a = array();
	   $product_id = $item->get_product_id();
	   $variation_id = $item->get_variation_id();
	    if(!empty($product_id) && !empty($variation_id))
	    {
			if( has_term( array( 'popup'), 'product_cat' ,  $product_id) ) 
			{
				$getCustomerID = get_post_meta($order_id, '_customer_user', true);
				//$final_result1[$variation_id][] = $item_id;			
				$final_result2[$product_id][] = $variation_id;			
			}
		}
	}
}

/* echo "<pre>";
print_r($final_result2);
echo "</pre>"; */
foreach($final_result2 as $key => $value)
{
	//echo $key . "<br>";
	foreach(array_unique($value) as $v1)
	{
		echo $v1 . "<br>";
	}
}
?>
