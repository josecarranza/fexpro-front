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

/* echo "<pre>";
print_r($allProducts);
echo "</pre>"; */

$size = 'medium';

$dom = new DOMDocument('1.0','UTF-8');
$dom->formatOutput = true;

$root = $dom->createElement('Sage');
$dom->appendChild($root);

foreach($allProducts as $value)
{
	$parentID = $value->post_parent;
	$variationID = $value->ID;
	$cat = get_the_terms( $parentID , 'product_cat' );
	$css_slugGender = array();
	$css_slugCategory = array();
	$css_slugSubCategory = array();
	$codigodetalla = array();
	
	$getSKU = get_post_meta($variationID, '_sku', true);
	$ak = str_replace(' ', '-', strtolower($getSKU));
	
	/* foreach($cat as $cvalue)
	{
		if($cvalue->parent != 0)
		{
			$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
			$css_slugSubCategory[] = $cvalue->name;
			$css_slugCategory[] = $term->name;
		}
		else
		{
			$css_slugGender[] = $cvalue->name;
		}
	} */
	
	if(!has_term( 'popup', 'product_cat', $parentID ))
	{
		continue;
	}
	else
	{
		
		$result = $dom->createElement('Imagenes');
		$root->appendChild($result);
		
		
		$result->setAttribute('Producto', $getSKU);
		
		echo "Variation ID: " . $variationID . "<br>";
		
		$thumb_image = wp_get_attachment_image_src(get_post_thumbnail_id( $variationID ), $size, true);
		
		//echo $thumb_image[0];
		$tokens = explode('/', $thumb_image[0]);
		$str = trim(end($tokens));
		//echo substr($thumb_image[0], 0, strrpos( $thumb_image[0], '/'));
		if($str != 'default.png')
		{
			echo "Main image: " . $str . "<br>";
			$result->setAttribute('MainImage', $str);
		}
		
		$gallery  = maybe_unserialize(get_post_meta( $variationID, 'woo_variation_gallery_images', true ));
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
	}
	
	$result->appendChild($dom->createTextNode(''));
	$root->appendChild($result);
	if($dom->save('IMG_' . $ak . '_pop_image.xml'))
	{
	   echo "FEXpro pop Style Images XML is created please click <a href='". site_url() ."/wp-content/themes/porto-child/style_imgs/pop/IMG_" . $ak . "_pop_image.xml' target='_blank'>Click Here " . $ak ."</a> <br><hr>"; 
	}
	else
	{
		die('XML Create Error');
	}
		
	$root->removeChild($result);
}

