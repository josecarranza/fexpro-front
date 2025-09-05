<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(500);
ini_set('memory_limit', '256M');
define('WP_USE_THEMES', false);
global $wp, $wpdb, $wp_the_query;// $wp_rewrite, $wp_did_header;
require( '../wp-load.php');
return;

$sql="SELECT a.ID,pm2.meta_value,
(SELECT b.meta_value FROM wp_postmeta b WHERE b.post_id=a.ID AND b.meta_key='woo_variation_gallery_images' ) metas
FROM wp_posts a
 LEFT JOIN wp_postmeta pm1 ON (pm1.post_id=a.ID AND pm1.meta_value IS NOT NULL AND pm1.meta_key='_thumbnail_id')
    LEFT JOIN wp_postmeta pm2 ON (pm2.post_id=pm1.meta_value AND pm2.meta_key='_wp_attachment_metadata')
WHERE date_format(a.post_date,'%Y-%m') IN ( '2023-04','2023-03','2023-05')
AND a.post_status IN ('publish')
AND LENGTH (pm2.meta_value) <600";

$r=$wpdb->get_results($sql);

$BASEPATH = "/var/www/vhosts/fexpro.com/shop.fexpro.com/";
foreach($r as $i => $item):
	$images = unserialize($item->meta_value);
	copy($BASEPATH."wp-content/uploads/".$images["file"],$BASEPATH."buscar_imagenes/img/".basename($images["file"]));
	if($item->metas!=null){
		$metas = unserialize($item->metas);
		$galleries = $wpdb->get_results("SELECT * FROM wp_postmeta pm3 WHERE pm3.meta_key = '_wp_attachment_metadata' AND pm3.post_id IN (".implode(",",$metas).")");
		foreach($galleries as $g => $gg):
			$gale = unserialize($gg->meta_value);
			if(isset($gale["file"]) && isset($gale["sizes"]) && count($gale["sizes"])==0){
				copy($BASEPATH."wp-content/uploads/".$gale["file"],$BASEPATH."buscar_imagenes/img/".basename($gale["file"]));
			}
		endforeach;
	}
	//echo "<pre>";
	//print_r($images);
	//exit;
endforeach;