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

$sql="SELECT p.ID,SUM(pm.meta_value) units_per_pack
FROM wp_posts p
INNER JOIN  wp_postmeta pm ON pm.post_id=p.ID AND pm.meta_key LIKE 'size_box_qty%'
WHERE p.post_type='product_variation'
GROUP BY p.ID";

$all=$wpdb->get_results($sql);
foreach ($all as $p) {
	update_post_meta( $p->ID, 'units_per_pack', (int)$p->units_per_pack );
}