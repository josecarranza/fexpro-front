<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(500);
ini_set('memory_limit', '256M');
define('WP_USE_THEMES', false);
global $wp, $wp_query, $wp_the_query;// $wp_rewrite, $wp_did_header;
require( '../wp-load.php');
return;
/*
$sql_total="SELECT p.ID

FROM wp_posts p 
INNER JOIN wp_term_relationships tr ON tr.object_id=p.ID
INNER JOIN wp_term_taxonomy tt ON tt.term_taxonomy_id=tr.term_taxonomy_id AND tt.taxonomy='product_cat'
INNER JOIN wp_terms t ON t.term_id=tt.term_id AND t.term_id=3259
WHERE p.post_type='product'
AND p.post_status='publish'
";

$all = $wpdb->get_results($sql_total);
$count=0;
foreach ($all as $p) {
	$sql="SELECT * FROM wp_postmeta WHERE post_id=".$p->ID." AND meta_key='_product_attributes'";

	$r=$wpdb->get_row($sql);
	
	$att=unserialize($r->meta_value);

	if(is_array($att) && isset($att["pa_color"])){
		$att["pa_color"]["is_variation"]=1;
	
		$att_ser = serialize($att);
	
		$wpdb->update("wp_postmeta",array("meta_value"=>$att_ser),array("post_id"=>$p->ID,"meta_key"=>'_product_attributes'));
	}
	$count++;
	echo $count."<br>";
	flush();
    ob_flush();
}
*/
$sql_total="SELECT p.ID

FROM wp_posts p 
INNER JOIN wp_term_relationships tr ON tr.object_id=p.ID
INNER JOIN wp_term_taxonomy tt ON tt.term_taxonomy_id=tr.term_taxonomy_id AND tt.taxonomy='product_cat'
INNER JOIN wp_terms t ON t.term_id=tt.term_id AND t.term_id=3259
WHERE p.post_type='product'
AND p.post_status='publish'

";

$all = $wpdb->get_results($sql_total);
$count=0;
foreach ($all as $p) {
	$sql="SELECT t.slug
	FROM wp_term_relationships tr 
	INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id
	INNER JOIN wp_terms t ON tt.term_id=t.term_id
	WHERE tr.object_id=".$p->ID."
	AND tt.taxonomy='pa_color'";
	$exist=$wpdb->get_results($sql,ARRAY_A);
	if(is_array($exist) && count($exist)>0){
		$exist = array_column($exist,"slug");
	}else{
		$exist=array();
	}
	

	$sql2="SELECT pm.meta_value,t.term_id  FROM wp_postmeta pm 
	INNER JOIN wp_terms t ON t.slug=pm.meta_value
	WHERE pm.meta_key='attribute_pa_color'
	AND pm.post_id IN (SELECT ID FROM wp_posts WHERE post_parent=".$p->ID.")";

	$encontrados = $wpdb->get_results($sql2);
	if(is_array($encontrados) && count($encontrados)>0){
		foreach($encontrados as $item){
			if( !in_array($item->meta_value,$exist)){
			
				$insert=array("object_id"=>$p->ID,"term_taxonomy_id"=>$item->term_id);
				/*echo "<pre>";
				print_r($insert);
				echo "</pre>";*/
				$wpdb->insert("wp_term_relationships",$insert);
			}
		}
	}

	$count++;
	echo $count."<br>";
	flush();
    ob_flush();

}



 ?>