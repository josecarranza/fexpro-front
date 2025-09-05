<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../wp-load.php');
global $wpdb;

$row = 1;
$finalSkuArr = array();

if (($handle = fopen("../missing-images-02dex.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    
    	$num = count($data);
	    //echo "<p> $num fields in line $row: <br /></p>\n";
	    $row++;
	    for ($c=0; $c < $num; $c++) {

	    	$finalSkuArr[] = $data[$c];
	    }
  
    
  }
  fclose($handle);
}
$size = 'large';
if(!empty($finalSkuArr)){


	
	$splitArr = array_chunk($finalSkuArr, 165);

	


	foreach($splitArr as $sk => $sv){



		$size = 'large';

		$dom = new DOMDocument('1.0','UTF-8');
		$dom->formatOutput = true;

		$root = $dom->createElement('Sage');
		$dom->appendChild($root);


		$ak = time(); 
		$missingImageArr = "";
		foreach($sv as $key => $sku){
			
			$result = $dom->createElement('Imagenes');
			$root->appendChild($result);
			
			$result->setAttribute('Producto', $sku);
			$regenerateSku = strtolower($sku);
			$thumb_image = $wpdb->get_var( $wpdb->prepare( "SELECT guid FROM $wpdb->posts WHERE ( post_title='%s' OR post_name = '%s' ) AND post_type='attachment' LIMIT 1", $sku, $regenerateSku ) );

			//$thumb_image = wp_get_attachment_image_src(get_post_thumbnail_id( $variationID ), $size, true);

			//echo $thumb_image[0];
			$tokens = explode('/', $thumb_image);
			$str = trim(end($tokens));
			//echo substr($thumb_image[0], 0, strrpos( $thumb_image[0], '/'));
			if($str != 'default.png')
			{
				//echo "Main image: " . $str . "<br>";
				$result->setAttribute('MainImage', $str);
			}

			if(empty($thumb_image) || is_null($thumb_image) || $thumb_image == ''){
				$missingImageArr  .= "Missing Images From Media => " . $sku ."<BR>";
			}
		}

		$result->appendChild($dom->createTextNode(''));
		$root->appendChild($result);
		$dom->save('IMG_' . $ak . '.xml');

		if($dom->save('IMG_' . $ak . '.xml'))
		{
		   echo "FEXpro Style Images XML is created please click <a href='". site_url() ."/wp-content/themes/porto-child/ss22_pre_filter/IMG_" . $ak . ".xml' target='_blank'>Click Here " . $ak ."</a> <br><hr>"; 

		  echo $missingImageArr . "<BR><hr>";
		}
		else
		{
			die('XML Create Error');
		}
			
		$root->removeChild($result);




	}

}


?>