<?php 
return;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(500);
ini_set('memory_limit', '256M');
define('WP_USE_THEMES', false);
global $wp, $wp_query, $wp_the_query;// $wp_rewrite, $wp_did_header;
require( '../wp-load.php');

$sql="SELECT pm.*, pm1.meta_value images FROM wp_postmeta pm
INNER JOIN wp_postmeta pm1 ON pm.post_id=pm1.post_id AND pm1.meta_key='_wp_attachment_metadata'
WHERE pm.meta_key='_wp_attached_file' AND pm.meta_value LIKE '2021/08/%'
";

$base_path="/var/www/vhosts/fexpro.com/shop.fexpro.com/wp-content/uploads/";
$all=$wpdb->get_results($sql);
$total_remove=0;
foreach ($all as $p) {
	 $file = $p->meta_value;
	 $folder_path = explode("/",$file);
	 array_pop($folder_path);
	
	 $folder_path=implode("/",$folder_path);
	 echo $folder_path."<br>";
	 $images = unserialize($p->images);
	 $not=[];
	 $yes=[];
	 foreach($images["sizes"] as $key => $s):
	 	if(in_array($s["width"].$s["height"],["150150","300300"])){
	 		$yes[]=$base_path.$folder_path."/".$s["file"];
	 	}else{
	 		$_f = $base_path.$folder_path."/".$s["file"];
	 		//echo $_f;
	 		$not[]= $_f;
	 		//@unlink($_f);
	 		//exit;
	 	}
	 	
	 endforeach;
	 $yes=array_unique($yes);
	 $not=array_unique($not);
	 echo "<pre style='display:block'>";
	 print_r($yes);
	 print_r($not);
	 echo "</pre>";
	 $total_remove+=count($not);
}
echo $total_remove;