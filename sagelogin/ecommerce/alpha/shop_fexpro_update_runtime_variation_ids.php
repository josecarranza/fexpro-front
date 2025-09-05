<?php 
require_once 'include/common.php';
if(is_admin()) return;
global $wpdb;
$deleteTableData = "DELETE FROM wp_run_time_v_id";
$wpdb->query( $deleteTableData );
$regenerateId = "ALTER TABLE wp_run_time_v_id AUTO_INCREMENT = 1";
$wpdb->query( $ddl );


$orders = wc_get_orders( array(
    'limit'    => -1,
    'status'   => array('wc-presale5'),
	'return' => 'ids',
) );
$final_result1 = array();
foreach($orders as $order_id)
{
	$order = wc_get_order( $order_id );
	foreach ( $order->get_items() as $item_id => $item ) {
	$a = array();
	   $product_id = $item->get_product_id();
	   $variation_id = $item->get_variation_id();
	    if(!empty($product_id) && !empty($variation_id))
	    {
	    	if( has_term( $_GET['fall-winter-22'] , 'product_cat' ,  $product_id) )
			{
				$getCustomerID = get_post_meta($order_id, '_customer_user', true);
				$final_result1[$variation_id][] = $item_id;		
			}	
		}
	}
}

foreach($final_result1 as $variation_id => $orderItemIds){
	$itemIdsArr = implode(',',$orderItemIds);
	$wpdb->query("INSERT INTO wp_run_time_v_id (vid,item_id) VALUES ('$variation_id','$itemIdsArr')"  );		
}
