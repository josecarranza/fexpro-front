<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../../../wp-load.php');
global $wpdb;

$args = array(
    'post_type' => array('product_variation'),
    'post_status' => array('publish', 'private'),
	'numberposts'=> -1 
);

$allProducts = get_posts( $args );
//print_r($allProducts);

$dom = new DOMDocument('1.0','UTF-8');
$dom->formatOutput = true;

$root = $dom->createElement('Sagemobile');
$dom->appendChild($root);

foreach($allProducts as $key => $value)
{
	$parentID = $value->post_parent;
	
	$variationID = $value->ID;
	
	if(has_term( 'popup', 'product_cat', $parentID ) || has_term( 'fitness', 'product_cat', $parentID ))
	{
		continue;
	}
	else
	{	
		
		$sku = get_post_meta($variationID, '_sku', true);
		$parentSKU = preg_replace ('/\-[^-]*$/', '', $sku);
		$colorName = str_replace($parentSKU . "-", "", $sku);
		//$price = get_post_meta($variationID, '_regular_price', true);
		
		$getGroupPrice = $wpdb->get_row("SELECT `price` from {$wpdb->prefix}wusp_group_product_price_mapping WHERE `product_id` = $variationID");
		$price = number_format($getGroupPrice->price, 2);
		//$getFirstBarcode = get_post_meta($variationID, 'size_barcode1', true);
		
		//echo $getFirstBarcode . "<br>";
		$result = $dom->createElement('Linea');
		$root->appendChild($result);
		
		$result->setAttribute('Cia', "08");
		$result->setAttribute('Producto_reference', "$parentSKU" . "$colorName");
		$result->setAttribute('Precio', "$price");
		
		$result->appendChild($dom->createTextNode(''));
		$root->appendChild($result);
	}	
	
}

if($dom->save('INV_revised_all_products_with_price_fw21_MEX.xml'))
{
	echo "FEXpro Product XML is created please click <a href='https://shop.fexpro.com/wp-content/themes/porto-child/revised_fw21_pop_products_price/fw21/mexico/INV_revised_all_products_with_price_fw21_MEX.xml' target='_blank'>Click Here All products </a> <br><hr>"; 
}
else
{
	die('XML Create Error');
}


