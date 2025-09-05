<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../wp-load.php');
$return_array = array();
$return_array1 = array();
$return_array2 = array();
global $wpdb;
	



$key = "attribute_pa_color"; //custom_color for example

$value = "black";//or red or any color

$query_color = array('key' => $key, 'value' => $value);

$meta_query[] = $query_color;

$args = array(
    'meta_query' => $meta_query,
    'tax_query' => array(
        $query_tax
    ),
    'posts_per_page' => 10,
    'post_type' => 'product',
    // 'orderby' => $orderby,
    // 'order' => $order,
    // 'paged' => $paged
);

$query = new WP_Query($args);
echo "<pre>";
print_r($query);
echo "</pre>";

