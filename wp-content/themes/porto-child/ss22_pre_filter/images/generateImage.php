<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../../wp-load.php');
$return_array = array();
$return_array1 = array();
$return_array2 = array();
$return_array3 = array();
$return_array4 = array();
global $wpdb;


$row = 1;
$finalSkuArr = array();

if (($handle = fopen("../../SS22MissingPngs.csv", "r")) !== FALSE) {
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
	foreach($finalSkuArr as $key => $sku){

		$regenerateSku = strtolower($sku);
		$thumb_image = $wpdb->get_var( $wpdb->prepare( "SELECT guid FROM $wpdb->posts WHERE ( post_title='%s' OR post_name = '%s' ) AND post_type='attachment' LIMIT 1", $sku, $regenerateSku ) );

		if(empty($thumb_image) || is_null($thumb_image) || $thumb_image == ''){ continue; }else{
			
			/* $tokens1 = explode('/', $thumb_image);
			$str1 = trim(end($tokens1));
			
			echo "Main image: " . $thumb_image . "<br>";
			$data = file_get_contents_curl($thumb_image);
			 

			$fp = $str1;
			  
		  
			 file_put_contents( $fp, $data ); */
			 echo $regenerateSku .  " -customnew- " . $thumb_image . "<br>";			 
		}

	}

	

	

}

function file_get_contents_curl($url) {
		
	    $ch = curl_init();
	  
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_URL, $url);
	  
	    $data = curl_exec($ch);
	    curl_close($ch);
	  
	    return $data;
}


?>