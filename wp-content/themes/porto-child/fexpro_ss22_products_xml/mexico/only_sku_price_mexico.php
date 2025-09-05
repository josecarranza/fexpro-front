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
	
	
	$cat = get_the_terms( $parentID , 'product_cat' );
	$css_slugGender = array();
	$css_slugCategory = array();
	$css_slugSubCategory = array();
	$codigodetalla = array();
	/* echo "<pre>";
	print_r($cat);
	echo "</pre>"; */
	
	
	if(!has_term( 'summer-spring-22', 'product_cat', $parentID ))
	{
		continue;
	}
	else
	{
		
		$customProduct = wc_get_product( $parentID );
		
		
		$sku = get_post_meta($variationID, '_sku', true);
		$parentSKU = preg_replace ('/\-[^-]*$/', '', $sku);
		$colorName = str_replace($parentSKU . "-", "", $sku);
		$mexicoPrice = $wpdb->get_row("SELECT `price` from {$wpdb->prefix}wusp_group_product_price_mapping WHERE `group_id` = '2' AND `product_id` = '$variationID'");
		$mexPrice = $mexicoPrice->price;
		
		$codigodetalla[] = trim(get_post_meta( $variationID, 'custom_field1', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field2', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field3', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field4', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field5', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field6', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field7', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field8', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field9', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field10', true ));		
		$codigodetallaCombine = implode("-", array_filter($codigodetalla));

		$codigo_de_talla = $multipleSizeArray['SIZES'][strtoupper($codigodetallaCombine)];

		/* echo $codigo_de_talla;
		echo "<br>"; */
		
		//$getFirstBarcode = get_post_meta($variationID, 'size_barcode1', true);
		
		//echo $getFirstBarcode . "<br>";
		$result = $dom->createElement('Linea');
		$root->appendChild($result);
		
		$result->setAttribute('Cia', "08");
		$result->setAttribute('Producto_reference', $parentSKU . $colorName);
		$result->setAttribute('Precio', "$mexPrice");
		
				
		$result->appendChild($dom->createTextNode(''));
		$root->appendChild($result);
			
		}
		
		
		/* $result1->appendChild($dom->createTextNode(''));
		$result->appendChild($result1); */
	}	
	
if($dom->save('only_sku_price_MEX.xml'))
{
   echo "FEXpro SS22 MEXICO Product XML is created please click <a href='". site_url() ."/wp-content/themes/porto-child/fexpro_ss22_products_xml/mexico/only_sku_price_MEX.xml' target='_blank'>Click Here All products </a> <br><hr>"; 
}
else
{
	die('XML Create Error');
}




