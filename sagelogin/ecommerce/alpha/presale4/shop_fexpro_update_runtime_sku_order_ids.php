<?php 
require_once 'include/common.php';
if(is_admin()) return;
global $wpdb;

// $deleteTableData = "DELETE FROM alpha_wp_sku_order_lists";
// $wpdb->query( $deleteTableData );
// $regenerateId = "ALTER TABLE alpha_wp_sku_order_lists AUTO_INCREMENT = 1";
// $wpdb->query( $ddl );


$return_array = array();
$return_array1 = array();
$return_array2 = array();

$orders = wc_get_orders( array(
    'limit'    => -1,
    'status' => array('wc-presale3', 'wc-cancelled'),
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
			if( has_term( $_GET['summer-spring-22'] , 'product_cat' ,  $product_id) )
			{
				$k = get_post_meta($variation_id, '_sku', true);
				$final_result1[$k . "removeText" . $order->get_status()][] = $order_id;
              
			}
		}
	}
}

foreach($final_result1 as $key => $value)
{

	$a = explode("removeText",$key);
	$implode_aa = implode(", ", $value);	
	 $wpdb->query("INSERT INTO alpha_wp_sku_order_lists (sku,status,order_ids) VALUES ('$a[0]','$a[1]','$implode_aa')"  );		
	
}

?>