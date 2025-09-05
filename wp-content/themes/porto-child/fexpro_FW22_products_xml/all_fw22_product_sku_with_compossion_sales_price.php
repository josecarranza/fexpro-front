<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../wp-load.php');
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

	if(!has_term( 'fall-winter-22', 'product_cat', $parentID ))
	{
		continue;
	}
	else
	{
		
		$customProduct = wc_get_product( $parentID );
	

		if(get_post_meta($variationID, 'composition', true)){
			$Composicion = get_post_meta($variationID, 'composition', true);
			$sku = get_post_meta($variationID, '_sku', true);
					
			$Precio = get_post_meta($variationID, '_regular_price', true);
			
			$result = $dom->createElement('Linea');
			$root->appendChild($result);

			$result->setAttribute('Producto_reference', "$sku");
			$result->setAttribute('Composicion', "$Composicion");
			$result->setAttribute('Precio', "$Precio");

			
		}
		

		
		
	}
	
	
}
if($dom->save('Fw22_product_sku_with_compossion_sales_price.xml'))
{
   echo "FEXpro FW22 Product XML is created please click <a href='". site_url() ."/wp-content/themes/porto-child/fexpro_FW22_products_xml/Fw22_product_sku_with_compossion_sales_price.xml' target='_blank'>Click Here All products </a> <br><hr>"; 
}
else
{
	die('XML Create Error');
}




