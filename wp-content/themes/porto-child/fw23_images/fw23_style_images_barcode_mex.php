<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../wp-load.php');
global $wpdb;


$qdate="q2";

$sql_variations_selled = "SELECT pm.meta_value variation_id
FROM wp_woocommerce_order_items p 
INNER JOIN wp_woocommerce_order_itemmeta pm ON p.order_item_id=pm.order_item_id AND pm.meta_key='_variation_id'
INNER JOIN wp_posts pp ON pp.ID=pm.meta_value
INNER JOIN (
	wp_term_relationships tr
	INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id AND tt.taxonomy='pa_date'
	INNER JOIN wp_terms t ON tt.term_id=t.term_id AND t.slug='".$qdate."'
) ON tr.object_id=pp.post_parent
INNER JOIN wp_postmeta ppm ON ppm.post_id=pm.meta_value AND ppm.meta_key LIKE 'size_barcode%' AND ppm.meta_value != ''
WHERE p.order_id IN (
  	SELECT pp.ID FROM wp_posts pp
	WHERE pp.post_type='shop_order' AND pp.post_status='wc-ss24-q2' AND pp.ID >=126125
 )
 GROUP BY pm.meta_value";

$variations = $wpdb->get_results($sql_variations_selled,ARRAY_A);
$variations = array_column($variations,"variation_id");

$args = array(
	'post_type' => array('product_variation'),
	'post_status' => array('publish', 'private'),
	'numberposts' => -1,
	'post__in' => $variations
);


// $args = array(
//     'post_type' => array('product_variation'),
//     'post_status' => array('publish', 'private'),
// 	'numberposts'=> 10
// );

$allProducts = get_posts( $args );
// echo "<pre>";
// print_r($allProducts);
// echo "</pre>";
// exit;
$splitArr = array_chunk($allProducts, 100);

$images_path="/var/www/vhosts/fexpro.com/shop.fexpro.com/";
$url_path = "https://shop2.fexpro.com/";
$self_path =  realpath(dirname(__FILE__));

foreach($splitArr as $sk => $sv){
    /* echo "<pre>";
    print_r($allProducts);
    echo "</pre>"; */
	$zip_folder_name=substr(md5(mt_rand()), 0, 10);
	if(!file_exists($self_path."/images_mex/".$zip_folder_name)) {
		mkdir($self_path."/images_mex/".$zip_folder_name, 0777);
	}
    
    $size = 'large';
    
    $dom = new DOMDocument('1.0','UTF-8');
    $dom->formatOutput = true;
    
    $root = $dom->createElement('Sage');
    $dom->appendChild($root);
    
    foreach($sv as $value)
    {
    	$parentID = $value->post_parent;
    	$variationID = $value->ID;
    	if( has_term( 'PRESALE' , 'product_cat' ,  $parentID) ){
    
    		$cat = get_the_terms( $parentID , 'product_cat' );
    		$css_slugGender = array();
    		$css_slugCategory = array();
    		$css_slugSubCategory = array();
    		$codigodetalla = array();
    		
    		$getSKU = get_post_meta($variationID, '_sku', true);
    		$ak = str_replace('-', '', $getSKU);
    		
    		foreach($cat as $cvalue)
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
    		}
    		
    		if($css_slugGender[0] == 'Fitness')
    		{
    			continue;
    		}
    		else
    		{
    			
    			$result = $dom->createElement('Imagenes');
    			$root->appendChild($result);
    			
    			
    			$result->setAttribute('Producto', $ak);
    			
    			//echo "Producto " . $getSKU . "<br>";
    			
    			$thumb_image = wp_get_attachment_image_src(get_post_thumbnail_id( $variationID ), $size, true);
    			
    		
				$server_path=str_replace($url_path,$images_path,$thumb_image[0]);
				
				
    			$tokens = explode('/', $thumb_image[0]);
    			$str = trim(end($tokens));
    			//echo substr($thumb_image[0], 0, strrpos( $thumb_image[0], '/'));

				

    			if($str != 'default.png')
    			{
    				//echo "Main image: " . $str . "<br>";
    				$result->setAttribute('MainImage', $str);

					@copy($server_path,$self_path."/images_mex/".$zip_folder_name."/".$str );
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
    					//echo "Child image: " . $str1 . "<br>";
    					
    					$result1->setAttribute('Archivo', $str1);
    					
    					$result1->appendChild($dom->createTextNode(''));
    					$result->appendChild($result1);

						$server_path1=str_replace($url_path,$images_path,$thumb_image1[0]);

						@copy($server_path1,$self_path."/images_mex/".$zip_folder_name."/".$str1 );
    				}
    			}
    			
    		}
    		
    	
    	}
    }
    
    	$result->appendChild($dom->createTextNode(''));
		$root->appendChild($result);
		if($dom->save('barcode_mex/IMG_' . strtolower($ak). '.xml'))
		{
			$zip_file = 'IMG_' . strtolower($ak).".zip";
			$command = "cd ".$self_path."/images_mex/".$zip_folder_name." && zip -r  ../".$zip_file." *";
			shell_exec($command);
			shell_exec("cd ".$self_path."/images_mex/ && rm -rf ".$zip_folder_name);
			echo "FEXpro Style Images XML is created please click <a href='". site_url() ."/wp-content/themes/porto-child/fw23_images/barcode_mex/IMG_" .strtolower($ak). ".xml' target='_blank'>Click Here " . $ak ."</a> | <a href='". site_url() ."/wp-content/themes/porto-child/fw23_images/images_mex/" . $zip_file . "'>".$zip_file."</a><br><hr>"; 
		}
		else
		{
			die('XML Create Error');
		}
			
		$root->removeChild($result);
		//die;
}
