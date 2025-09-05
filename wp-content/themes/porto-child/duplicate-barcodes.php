<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../wp-load.php');
$return_array = array();
$return_array1 = array();
$return_array2 = array();
$totalOfUnitPurchased = 0;
$totalOfAmount = 0;
global $wpdb;


$variations = get_posts(array('post_type'=>'product_variation','numberposts'=>-1, 'post_status'=>'publish'));
/* echo "<pre>";
print_r($variations);
echo "</pre>"; */
foreach($variations as $key => $value)
{
	for($i=1;$i<=10;$i++)
	{
		echo $value->ID . "<br>";
		echo get_post_meta($value->ID, 'size_barcode'.$i, true) . "<br>";
	}
}