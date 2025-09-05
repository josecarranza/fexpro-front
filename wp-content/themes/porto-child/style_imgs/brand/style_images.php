<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../../wp-load.php');
global $wpdb;
$ak = array();

$args = array(
    'post_type' => array('product_variation'),
    'post_status' => array('publish', 'private'),
	'numberposts'=> -1
);

$allProducts = get_posts( $args );

/* echo "<pre>";
print_r($allProducts);
echo "</pre>"; */

$size = 'large';

$dom = new DOMDocument('1.0','UTF-8');
$dom->formatOutput = true;

$root = $dom->createElement('Sage');
$dom->appendChild($root);

foreach($allProducts as $value)
{
	$parentID = $value->post_parent;
	$variationID = $value->ID;
	if( has_term( array( 'popup', 'fitness'), 'product_cat' ,  $parentID) ) 
	{
		continue;
	}
	else
	{
		$main_product = wc_get_product( $parentID );
		$brand = $main_product->get_attribute( 'pa_brand' );
		$ak[$brand][] = $variationID;
	}
}

/* echo "<pre>";
print_r($ak);
echo "</pre>"; */

foreach($ak as $key => $value0)
{
	//echo $key . "<br>";
	$k2 = strtolower($key);
	$k1 = str_replace(" ", "-", str_replace("/", "-", $k2));
	//$k1 = strtolower($key);
	foreach($value0 as $key2 => $value2)
	{
		
		//$ak = str_replace(' ', '-', strtolower($getSKU));
		$getSKU = get_post_meta($value2, '_sku', true);	
			$result = $dom->createElement('Imagenes');
			$root->appendChild($result);
			
			
			$result->setAttribute('Cia', '02');
			$result->setAttribute('Producto', $getSKU);
			
			echo "Variation ID: " . $value2 . "<br>";
			
			$thumb_image = wp_get_attachment_image_src(get_post_thumbnail_id( $value2 ), $size, true);
			
			//echo $thumb_image[0];
			$tokens = explode('/', $thumb_image[0]);
			$str = trim(end($tokens));
			//echo substr($thumb_image[0], 0, strrpos( $thumb_image[0], '/'));
			if($str != 'default.png')
			{
				echo "Main image: " . $str . "<br>";
				$result->setAttribute('MainImage', $str);
			}
			
			$gallery  = maybe_unserialize(get_post_meta( $value2, 'woo_variation_gallery_images', true ));
			if ( ! empty( $gallery ))
			{
				foreach($gallery as $gvalue)
				{
					$result1 = $dom->createElement('Secondary');
					$result->appendChild($result1);
					
					$thumb_image1 = wp_get_attachment_image_src($gvalue, $size);
					$tokens1 = explode('/', $thumb_image1[0]);
					$str1 = trim(end($tokens1));
					echo "Child image: " . $str1 . "<br>";
					
					$result1->setAttribute('Archivo', $str1);
					
					$result1->appendChild($dom->createTextNode(''));
					$result->appendChild($result1);
				}
			}
			echo "<hr>";
		
			$result->appendChild($dom->createTextNode(''));
			$root->appendChild($result);
			
	}
	if($dom->save('IMG_' . $k1 . '.xml'))
	{
	   echo "FEXpro Style Images XML is created please click <a href='". site_url() ."/wp-content/themes/porto-child/style_imgs/brand/IMG_" . $k1 . ".xml' target='_blank'>Click Here " . $k1 ."</a> <br><hr>"; 
	}
	else
	{
		die('XML Create Error');
	}
		
	$root->removeChild($result);
}