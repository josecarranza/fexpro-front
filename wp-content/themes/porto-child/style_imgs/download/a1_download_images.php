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
/*   
$url = 'https://shop.fexpro.com/wp-content/uploads/2021/02/AMHD52110-NVYBack-878x1024.png'; 
  
$img = 'logo.png'; 
  
// Function to write image into file
file_put_contents($img, file_get_contents($url));
  
echo "File downloaded!" */

/* if( ini_get('allow_url_fopen') ) {
    die('allow_url_fopen is enabled. file_get_contents should work well');
} else {
    die('allow_url_fopen is disabled. file_get_contents would not work');
} */

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

foreach($allProducts as $value)
{
	$parentID = $value->post_parent;
	$variationID = $value->ID;
	
	$thumb_image = wp_get_attachment_image_src(get_post_thumbnail_id( $variationID ), $size, true);
		
	//echo $thumb_image[0];
	$tokens = explode('/', $thumb_image[0]);
	$str = trim(end($tokens));
	//echo substr($thumb_image[0], 0, strrpos( $thumb_image[0], '/'));
	//echo "End Title: " . $str . "<br>";
	if (strpos($thumb_image[0], 'shop.fexpro.com') !== false)
	{
		if($str != 'default.png')
		{
			$abc = strstr($thumb_image[0], 'https://shop.fexpro.com/');
			
			echo "Main image: " . $abc . "<br>";
			
			$data = file_get_contents_curl($abc);
			  
			$fp = $str;
			  
			file_put_contents( $fp, $data );
			echo "File downloaded! " . $str . "<br>";
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