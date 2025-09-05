<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../../wp-load.php');
global $wpdb;

$args = array(
    'post_type' => array('product_variation'),
    'post_status' => array('publish', 'private'),
    'numberposts'=> -1 
);

$allProducts = get_posts( $args );

$dom = new DOMDocument('1.0','UTF-8');
$dom->formatOutput = true;

$root = $dom->createElement('Sagemobile');
$dom->appendChild($root);

foreach($allProducts as $key => $value)
{
	$parentID = $value->post_parent;
	$variationID = $value->ID;
	
	
	if(!has_term( 'summer-spring-22', 'product_cat', $parentID ))
	{
		continue;
	}
	else
	{
		
		/* print_r($css_slugCategory);
		echo "<br>";
		print_r($css_slugSubCategory);
		echo "<br>";
		echo "Gender: ";
		print_r($css_slugGender);
		echo "<br>"; */
		//die();
		$customProduct = wc_get_product( $parentID );
		
		
		$sku = get_post_meta($variationID, '_sku', true);
		$precio = get_post_meta($variationID, '_regular_price', true);
		
		$result = $dom->createElement('Linea');
		$root->appendChild($result);
		
		$result->setAttribute('Cia', "02");
		$result->setAttribute('Producto_reference', "$sku");
		$result->setAttribute('Precio', "$precio");
		
		$result->appendChild($dom->createTextNode(''));
		$root->appendChild($result);		
	}	
	
}
if($dom->save('PRI_panama_price.xml'))
{
   echo "FEXpro SS22 Sale Price XML is created please click <a href='". site_url() ."/wp-content/themes/porto-child/fexpro_ss22_products_xml/panama-sale-price/PRI_panama_price.xml' target='_blank'>Click Here All products </a> <br><hr>"; 
}
else
{
	die('XML Create Error');
}




