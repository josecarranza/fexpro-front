<?php 
$categorias_tree=array_values($categorias_tree);

$current_filters = isset($_GET) && count($_GET)>0 ? $_GET : array();
//$current_filters = arrap_map(function($item){ $item=addslashes($item); return $item; },$current_filters);
$clean_filters=array();
foreach ($current_filters as $key => $item) {
	$item= preg_replace("/[^a-zA-Z0-9\-,_]+/", "",$item);
	$clean_filters[preg_replace("/[^a-zA-Z0-9\-,_]+/", "",$key)] = $item;
}
global $currents_categories;
$currents_categories = isset($clean_filters["filter_category"]) && $clean_filters["filter_category"]!="" ? explode(",",$clean_filters["filter_category"]) : array();
unset($clean_filters["filter_category"]);
$current_uri=parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
global $final_url;
$final_url = get_site_url().$current_uri."?".urldecode(http_build_query($clean_filters));


function render_filter_cat($tree,$level=0,$selected=""){
	global $final_url,$currents_categories;
	$margin=str_repeat("- ",$level);
	$html='<ul class="woocommerce-widget-layered-nav-list">';
	foreach ($tree as $key => $item) {
		$new_categories_filter=$currents_categories;
		$chosen = false;
		if(in_array($item["slug"],$new_categories_filter)){
			$pos = array_search($item["slug"],$new_categories_filter);
			unset($new_categories_filter[$pos]);
			$chosen = true;
		}else{
			$new_categories_filter[]=$item["slug"];
		}
		if(count($new_categories_filter)>0){
			$segment_filter = "&filter_category=".implode(",",$new_categories_filter);
		}else{
			$segment_filter = "";
		}
		
		//$html.='<option value="'.$item["id"].'" '.($selected!="" && $selected==$item["id"]?"selected":"").'>'.$margin." ".$item["name"].'</option>';
		$has_items=isset($item["children"]) && count($item["children"])>0 ? true :false;
		$html.='<li class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term '.($chosen?'chosen':'').'">';
		if($has_items){
			$html.='<i class="ico-collapse"></i>';
		}
		$html.='<a href="'.$final_url.$segment_filter.'" >'.$item["name"].'</a>';
		
		if($has_items){
			$html.=render_filter_cat($item["children"],$level+1,$selected);
		}
		$html.='</li>';
	}
	$html.="</ul>";
	return $html;
}
echo render_filter_cat($categorias_tree[0]["children"],0);
?>

<script>
	$=jQuery;
	$(".widget.widget_wi_widget_filter_category .ico-collapse").click(function(){
		$(this).parent().children('ul').slideToggle();
	});
</script>
 