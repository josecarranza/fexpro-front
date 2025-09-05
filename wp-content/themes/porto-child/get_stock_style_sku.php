<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../wp-load.php');
$return_array = array();
$return_array1 = array();
$return_array2 = array();
global $wpdb;


$getVids  = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}show_fexpro_products");

foreach($getVids as $value)
{
	$c = 0;
	$p = wc_get_product($value->vid);
	//echo $p->parent_id . "<br>";
	if(get_post_meta( $value->vid, 'size_box_qty1', true ))
	{
		$c += get_post_meta( $value->vid, 'size_box_qty1', true );
	}
	if(get_post_meta( $value->vid, 'size_box_qty2', true ))
	{
		$c += get_post_meta( $value->vid, 'size_box_qty2', true );
	}
	if(get_post_meta( $value->vid, 'size_box_qty3', true ))
	{
		$c += get_post_meta( $value->vid, 'size_box_qty3', true );
	}
	if(get_post_meta( $value->vid, 'size_box_qty4', true ))
	{
		$c += get_post_meta( $value->vid, 'size_box_qty4', true );
	}
	if(get_post_meta( $value->vid, 'size_box_qty5', true ))
	{
		$c += get_post_meta( $value->vid, 'size_box_qty5', true );
	}
	if(get_post_meta( $value->vid, 'size_box_qty6', true ))
	{
		$c += get_post_meta( $value->vid, 'size_box_qty6', true );
	}
	if(get_post_meta( $value->vid, 'size_box_qty7', true ))
	{
		$c += get_post_meta( $value->vid, 'size_box_qty7', true );
	}
	if(get_post_meta( $value->vid, 'size_box_qty8', true ))
	{
		$c += get_post_meta( $value->vid, 'size_box_qty8', true );
	}
	$final_result1[$p->parent_id][$value->vid][] = $value->stock;
	$final_result1[$p->parent_id][$value->vid][] = $c;
}

/* echo "<pre>";
print_r($final_result1);
echo "</pre>"; */

foreach($final_result1 as $k => $v)
{
	$d = 0;
	foreach($v as $k1 => $v1)
	{
		$d = $v1[0] * $v1[1];
		echo $d . "<br>";
		echo $k . "<br>";
		$term_taxonomy_ids = wp_set_object_terms( $k, "$d", 'pa_stock', true );
		$thedata = Array(
			 'pa_stock'=>Array( 
				   'name'=>'pa_stock', 
				   'value'=>"$d",
				   'is_visible' => '1',
				   'is_variation' => '0',
				   'is_taxonomy' => '1'
			 )
		);
		//First getting the Post Meta
		$_product_attributes = maybe_unserialize(get_post_meta($k, '_product_attributes', TRUE));
		//Updating the Post Meta
		update_post_meta($k, '_product_attributes', array_merge($_product_attributes, $thedata));
	}
}

/* $k = wc_get_product_terms( 19325, 'pa_stock', array( 'fields' => 'names' ) );
//print_r($k);
foreach($k as $vk)
{
	wp_remove_object_terms( 19325, $vk, 'pa_stock' );
}

$term_taxonomy_ids = wp_set_object_terms( 19325, '100', 'pa_stock', true );
$thedata = Array(
     'pa_stock'=>Array( 
           'name'=>'pa_stock', 
           'value'=>'100',
           'is_visible' => '1',
           'is_variation' => '0',
           'is_taxonomy' => '1'
     )
);
//First getting the Post Meta
$_product_attributes = get_post_meta(19325, '_product_attributes', TRUE);
//Updating the Post Meta
update_post_meta(19325, '_product_attributes', array_merge($_product_attributes, $thedata));
 */


