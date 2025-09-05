<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../wp-load.php');
global $wpdb;

$getAllfactorylist = $wpdb->get_results("SELECT `supplier_name` FROM {$wpdb->prefix}factory_list");

foreach($getAllfactorylist as $value)
{
	echo $value->supplier_name . "<br>";
}