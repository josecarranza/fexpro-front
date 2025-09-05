<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../wp-load.php');

$a = get_option('afpvu_user_role_visibility')['custom_role_sportline_segmentacion']['afpvu_applied_products_role'];

foreach($a as $value)
{
	echo $value . "<br>";
}
