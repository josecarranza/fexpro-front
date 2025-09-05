<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../wp-load.php');

global $wpdb;

$parent = 'parent';
$getproductsLookupTable = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wc_product_meta_lookup WHERE `tax_class` = %s", $parent) );
 
echo "<pre>";
print_r($getproductsLookupTable);
echo "</pre>";

?>