<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../wp-load.php');
global $wpdb;


//$deleteTableData1 = "DELETE FROM wp_ss22_bulk_filter_role_category_products";
//$wpdb->query( $deleteTableData1 );
//$regenerateId1 = "ALTER TABLE wp_ss22_bulk_filter_role_category_products AUTO_INCREMENT = 1";
//$wpdb->query( $regenerateId1 );




$getas = get_option('afpvu_user_role_visibility');
$hideCategoryArr = array();
$FinaleArr = array();
foreach($getas as $key => $value){
	print_r($value['afpvu_applied_categories_role']);
	if(!empty($value['afpvu_applied_categories_role'])){

		$args = array(
		    'post_type'             => 'product',
		    'posts_per_page'  => -1,
		    'post_status' => array('publish', 'private'),
		  	'fields'   				=> 'ids',
		    'tax_query'             => array(
		        array(
		            'taxonomy'      => 'product_cat',
		            'field' => 'term_id', //This is optional, as it defaults to 'term_id'
		            'terms'         => $value['afpvu_applied_categories_role'],
		            'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
		        )
		    )
		);
		$products =  new WP_Query($args);
		$posts = $products->posts;

	}

	$posts_data = ($posts) ? serialize($posts) : serialize(array());
	//print_r($posts_data);

	//$wp_sql = $wpdb->prepare("INSERT INTO wp_ss22_bulk_filter_role_category_products (`role`, `product_ids`) values (%s, %s)", $key, $posts_data );
	//$wpdb->query($wp_sql);
}


echo "Table Update Success fully";

?>