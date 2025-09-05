<?php 
include_once('../../../../wp-load.php');
if(is_admin()) return;
global $wpdb;

$product_ids_arr=get_terms( 'product_cat', array('hide_empty' => 0,'parent' => 1874));
$newData = [];

$product_cat_html = '<ul class="">';
foreach($product_ids_arr as $key20 => $terms_arr){
	if($terms_arr->name != 'Q1'){
		$term_link = get_term_link( $terms_arr->term_id, 'product_cat' );
		$product_cat_html .= '<li id="menu-item-'.$terms_arr->term_id.'" class="menu-item menu-item-type-taxonomy menu-item-'.$terms_arr->term_id.'">';
		$product_cat_html .= '<a href="'.$term_link.'">'.$terms_arr->name.'</a>';
		$product_cat_html .= '</li>';
	}
}
$product_cat_html .= '</ul>';

echo "<pre>";
print_r($product_cat_html);
echo "</pre>";
die;



