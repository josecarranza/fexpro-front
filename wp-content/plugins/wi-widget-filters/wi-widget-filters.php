<?php 
/*
Plugin Name: WI Widget Filters
Description: WI Widget Filters
Version: 1.0
Requires PHP: 7.0
Author: Web Info Team
Author URI: https://web-informatica.com/
 */
include_once("class-wi-widget-category.php");
include_once("class-wi-widget-delivery-date.php");
include_once("class-wi-widget-category2.php");
include_once("class-wi-widget-stock.php");
include_once("class-wi-widget-basics.php");
include_once("class-wi-widget-sold.php");
include_once("class-wi-widget-brand-group.php");
include_once("class-wi-widget-category-parent.php");
include_once("class-wi-widget-static-filter.php");
include_once("class-wi-widget-brand-collection.php");
include_once("class-wi-widget-colors.php");
include_once("class-wi-widget-product-type.php");
include_once("class-wi-widget-brands.php");
include_once("class-wi-widget-collections.php");
include_once("class-wi-widget-teams.php");
include_once("class-wi-widget-attribute.php");
function wi_load_widget() {
    register_widget( 'wi_widget_filter_category' );
	register_widget( 'wi_widget_filter_delivery_date' );
	register_widget( 'wi_widget_filter_category2' );
	register_widget( 'wi_widget_filter_stock' );
	register_widget( 'wi_widget_filter_basics' );
	register_widget( 'wi_widget_filter_sold' );
	register_widget( 'wi_widget_filter_brand_group' );
	register_widget( 'wi_widget_filter_category_parent' );
	register_widget( 'wi_widget_filter_static_filter' );
	register_widget( 'wi_widget_filter_brand_collection' );
	register_widget( 'wi_widget_filter_colors' );
	register_widget( 'wi_widget_filter_product_type' );
	register_widget( 'wi_widget_filter_brands' );
	register_widget( 'wi_widget_filter_collections' );
	register_widget( 'wi_widget_filter_teams' );
	register_widget( 'wi_widget_filter_attribute' );
}
add_action( 'widgets_init', 'wi_load_widget' );

function wi_add_custom_filters($link="",$obj=null){
	$currents_categories = isset($_GET["filter_category"]) && $_GET["filter_category"]!="" ? explode(",",$_GET["filter_category"]) : array();

	$current_delivery =  isset($_GET["filter_delivery"]) && $_GET["filter_delivery"]!="" ? explode(",",$_GET["filter_delivery"]) : array();

	$currents_categories2 = isset($_GET["filter_category2"]) && $_GET["filter_category2"]!="" ? explode(",",$_GET["filter_category2"]) : array();
	
	$segment= "";
	if(count($currents_categories)>0){
		$segment.=($segment!=""?"&":"")."filter_category=".preg_replace("/[^a-zA-Z0-9\-,_]+/", "",implode(",",$currents_categories));
	}

	if(count($current_delivery)>0){
		$segment.=($segment!=""?"&":"")."filter_delivery=".preg_replace("/[^a-zA-Z0-9\-,_]+/", "",implode(",",$current_delivery));
	}

	if(count($currents_categories2)>0){
		$segment.=($segment!=""?"&":"")."filter_category2=".preg_replace("/[^a-zA-Z0-9\-,_]+/", "",implode(",",$currents_categories2));
	}

	$segment = urlencode($segment);
	$glue = strpos($link, "?")!==false?"&":"?";
	return $link.$glue.$segment;
}

add_filter("woocommerce_widget_get_current_page_url","wi_add_custom_filters");

function wi_selected_options_filters($prev=false){
	$currents_categories = isset($_GET["filter_category"]) && $_GET["filter_category"]!="" ? explode(",",$_GET["filter_category"]) : array();
	$has_filter=$prev || count($currents_categories)>0?true:false;

	$current_delivery = isset($_GET["filter_delivery"]) && $_GET["filter_delivery"]!="" ? explode(",",$_GET["filter_delivery"]) : array();

	$has_filter=$has_filter || count($current_delivery)>0?true:false;

	//$currents_categories2 = isset($_GET["filter_category2"]) && $_GET["filter_category2"]!="" ? explode(",",$_GET["filter_category2"]) : array();

	//$has_filter=$has_filter || count($current_delivery2)>0?true:false;

	return $has_filter;
}
add_filter("wi_widget_has_filters","wi_selected_options_filters");


function wi_add_filter_selected($base_link,$html=''){
	$currents_categories = isset($_GET["filter_category"]) && $_GET["filter_category"]!="" ? explode(",",$_GET["filter_category"]) : array();
	foreach ( $currents_categories as $cat_slug ) {
		$term = get_term_by( 'slug', $cat_slug, "product_cat");
			if ( ! $term ) {
				continue;
			}
		$new_categories_filter=$currents_categories;
		if(in_array($cat_slug,$new_categories_filter)){
			$pos = array_search($cat_slug,$new_categories_filter);
			unset($new_categories_filter[$pos]);
		}else{
			$new_categories_filter[]=$cat_slug;
		}
		if(count($new_categories_filter)>0){
			$segment_filter = "filter_category=".implode(",",$new_categories_filter);
		}else{
			$segment_filter = "";
		}	
		
		$link = remove_query_arg("filter_category",$base_link);
		$glue = strpos($link, "?")!==false?"&":"?";
		$link = $link.$glue.$segment_filter;
		//$link = $base_link;
		//$current_filter = array_map( 'sanitize_title', $current_filter );
		$html .= '<li class=""><a rel="nofollow" href="'.$link.'">' . esc_html($term->name) . '</a></li>';
		$base_link = $link;
	}

	$current_delivery = isset($_GET["filter_delivery"]) && $_GET["filter_delivery"]!="" ? explode(",",$_GET["filter_delivery"]) : array();
	
	foreach ( $current_delivery as $date ) {

		$new_delivery=$current_delivery;
		if(in_array($date,$new_delivery)){
			$pos = array_search($date,$new_delivery);
			unset($new_delivery[$pos]);
		}else{
			$new_delivery[]=$date;
		}

		if(count($new_delivery)>0){
			$segment_filter = "filter_delivery=".implode(",",$new_delivery);
		}else{
			$segment_filter = "";
		}	
		
		$link = remove_query_arg("filter_delivery",$base_link);
		//echo $link;
		$glue = strpos($link, "?")!==false?"&":"?";
		$link = $link.$glue.$segment_filter;
		//$link = $base_link;
		//$current_filter = array_map( 'sanitize_title', $current_filter );
		if($date!="now"){
			$dd=strtotime($date."-01");
			$date_str=date("F",$dd)." ".date("Y",$dd);
		}else{
			
			$date_str="IMMEDIATE";
		}
		

		$html .= '<li class=""><a rel="nofollow" href="'.$link.'">' . strtoupper($date_str ). '</a></li>';

	}
	return $html;
}
add_filter("wi_widget_filters_selected","wi_add_filter_selected");
 ?>