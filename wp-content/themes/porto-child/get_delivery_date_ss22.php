<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../wp-load.php');
$return_array = array();
$return_array1 = array();
$return_array2 = array();
global $wpdb;


$abc = array("MLBTS52201", "MLBTS52202", "MLBTS52203", "MLBTS52204", "MLBTS52205", "MLBTS52206", "MLBLS52201", "MLBLS52202", "MLBSW52201", "MLBLS52203", "MLBSW52202", "MLBHD52201", "MLBHD52202", "MLBHD52203", "MLBJP52201", "MLBJP52202", "MLBJK52201", "MLBTT52201", "MLBSH52201", "NBASW52201", "NBALS52202", "NBAHD52201", "NBAHD52202", "NBAHD52203", "NBAHD52204", "NBAJP52201", "NBAJP52202", "NBAJK52201", "NBAJK52202", "NBAJK52203", "NBATS52201", "NBATS52202", "NBATS52203", "NBATT52201", "NBASH52201", "NBABD52201", "NBAHD02201", "NBAHD02202", "NBATS02202", "NBATS02203", "NBATS02204", "NBATS02205", "NBATS02206", "NBATS02207", "NBATT02201", "NBATT02208", "NBASW52202", "NBASW52208", "NBALS52203", "NBAHD52205", "NBAHD52206", "NBAHD52207", "NBAHD52208", "NBAHD52209", "NBAJP52203", "NBAJP52204", "NBAJP5225", "NBAWR52201", "NBAWR52202", "NBAJK52206", "NBAJK52205", "NBATS52208", "NBATS52209", "NBATS52206", "NBATS52210", "NBATT52202", "NBATT52203");

foreach($abc as $value)
{
	$product = wc_get_product(wc_get_product_id_by_sku( $value ));
	$d = $product->get_attribute( 'pa_delivery-date' );
	echo "SKU: " . $value . " = " . $d . "<br>";
}