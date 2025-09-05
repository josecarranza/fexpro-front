<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../wp-load.php');
global $wpdb;


$sql_variations_selled = "SELECT pm.meta_value variation_id
FROM wp_woocommerce_order_items p 
INNER JOIN wp_woocommerce_order_itemmeta pm ON p.order_item_id=pm.order_item_id AND pm.meta_key='_variation_id'
WHERE p.order_id IN (
  SELECT pp.ID FROM wp_posts pp
WHERE pp.post_type='shop_order' AND pp.post_status='wc-fw24-q3' AND pp.ID >=126125
 )
 GROUP BY pm.meta_value";

$variations = $wpdb->get_results($sql_variations_selled,ARRAY_A);
$variations = array_column($variations,"variation_id");

$season = "FALL WINTER 24";


$args = array(
	'post_type' => array('product_variation'),
	'post_status' => array('publish'),
	'numberposts' => -1,
	/*'meta_query' => array(
		'relation' => 'OR',
        array(
            'key' => 'pa_season',
            'value' => $season,
            'compare' => '='
		),
		array(
            'key' => 'season',
            'value' => $season,
            'compare' => '='
        )
		),*/
	'post__not_in' => $variations
);

$allProducts_list = get_posts($args);

$chunks =array_chunk($allProducts_list,100);
/*
echo "<pre>";
// print_r($allProducts);
$variationID = $allProducts[0]->post_parent;
// $meta = get_post_meta($variationID);
$terms = get_the_terms($variationID, 'product_cat' );
// $atributos = wc_get_product($variationID);
print_r($terms);
echo "</pre>";
die(); */

$multipleSizeArray = array("SIZES" => array("S-M-L-XL" => "25", "4-6-8-10-12-14-16" => "3", "7-8-9-10-11" => "SD", "28-29-30-31-32-33" => "CL", "OS" => "OS", "8-10-12-14-16" => "YH", "XS-S-M-L" => "WO", "10-11-12-13-1" => "C2", "40-41-43-44-45" => "E1"));

$multipleGenderArray = array(
	"GENDER" => array(
		"MEN" => "05", 
		"BOYS" => "03", 
        "KIDS" => "03",
		"WOMEN" => "06", 
		"UNISEX" => "UNI"
	)
);

$multipleTeamArray = array("TEAM NAME" => array("GRIZZLIES" => "GRI", "NINERS" => "49E","TEXANS" => "TXA", "VIKINGS" => "VIK", "PANTHERS" => "PAN", "JAGUARS" => "JAG", "BRONCOS" => "BRO", "LIONS" => "LIO", "BEARS" => "BEA", "ASTROS" => "AST", "49ERS" => "49E", "MLB" => "MLB", "BARCELONA" => "BAR", "BRAVES" => "BRA", "BUCCANEERS" => "BUC", "BULLS" => "BUL", "CELTICS" => "CEL", "CLIPPERS" => "CLI", "CUBS" => "CUB", "CHIEFS" => "CHI", "DODGERS" => "DOD", "DOLPHINS" => "DOL", "EAGLES" => "EAG", "GIANTS" => "GIA", "HEAT" => "HEA", "HORNETS" => "HOR", "JAZZ" => "JAZ", "KNICKS" => "KNI", "LAKERS" => "LAK", "MAVERICKS" => "MAV", "METS" => "MET", "NBA" => "NBA",  "NETS" => "NET", "NUGGETS" => "NUG", "PACKERS" => "PAC", "PATRIOTS" => "PAT", "PELICANS" => "PEL", "RAIDERS" => "RAI", "RAMS" => "RAM", "RAPTORS" => "RAP", "RAVENS" => "RAV", "RED SOX" => "RED", "ROCKETS" => "ROC", "SAINTS" => "SAI", "SEAHAWKS" => "SEA", "SPURS" => "SPU", "STEELERS" => "STE", "SUNS" => "SUN", "TRAIL BLAZERS" => "TRA", "WARRIORS" => "WAR", "YANKEES" => "YAN", "CORONA" => "CN", "FANTA" => "FT", "COCA COLA" => "CC", "SPRITE" => "SPR", "BUCKS" => "BCK", "RED SOXS" => "RED", "76ERS" => "76E", "7ERS" => "76E", "PHOENIX" => "SUN"));

foreach($chunks as $i_file =>  $allProducts){//START FOREACH CHUNCKS



$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;

$root = $dom->createElement('Sagemobile');
$dom->appendChild($root);

foreach ($allProducts as $key => $value) {
	$parentID = $value->post_parent;
	$variationID = $value->ID;


	$cat = get_the_terms($parentID, 'product_cat');
	$css_slugGender = array();
	$css_slugCategory = array();
	$css_slugSubCategory = array();
	$codigodetalla = array();

	foreach ($cat as $cvalue) {
		if ($cvalue->parent != 0) {
			if ($cvalue->parent == '3259') {
				$css_slugGender[] = $cvalue->name;
			}
		} else {
			continue;
		}
	}

	//if (!has_term('fw23', 'product_cat', $parentID)) {
	if (false) {	
		continue;
	} else {
		$customProduct = wc_get_product($parentID);

		$meta = get_post_meta($variationID);
		$sku = isset($meta['_sku'][0]) ? $meta['_sku'][0] : '';
		$parentSKU = preg_replace('/\-[^-]*$/', '', $sku);
		$colorName = str_replace($parentSKU . "-", "", $sku);
		$description_spanish = get_post_meta($parentID, 'descripcion', true);
		$description_english = $customProduct->get_description();


		$Composicion = isset($meta['pa_fabric_composition'][0]) ? $meta['pa_fabric_composition'][0] : '';
		$Composicion_esp = isset($meta['pa_compositions'][0]) ? $meta['pa_compositions'][0] : '';
		$brand = $customProduct->get_attribute('pa_brand');
		$group = isset($meta['grupo'][0]) ? $meta['grupo'][0] : '';
		$sub_group = isset($meta['subgrupo'][0]) ? $meta['subgrupo'][0] : '';
		$gender = $multipleGenderArray['GENDER'][strtoupper($customProduct->get_attribute('pa_gender'))];

		$price = isset($meta['_regular_price'][0]) ? $meta['_regular_price'][0] : '';
		$logo_Application = isset($meta['logo_application'][0]) ? $meta['logo_application'][0] : '';
		$fob = isset($meta['fob'][0]) ? $meta['fob'][0] : '';
		$arancel = isset($meta['arancel'][0]) ? $meta['arancel'][0] : '';
		$lob = isset($meta['lob'][0]) ? $meta['lob'][0] : '';
		$season = isset($meta['season'][0]) ? $meta['season'][0] : '';
		if (!empty(isset($meta['product_team'][0]) ? $meta['product_team'][0] : '')) {
			$getTeamName = strtoupper(isset($meta['product_team'][0]) ? $meta['product_team'][0] : '');
			$getTeamName1 = $multipleTeamArray['TEAM NAME'][trim($getTeamName)];
		} else {
			$getTeamName1 = '';
		}

		$codigodetalla[] = trim(isset($meta['custom_field1'][0]) ? $meta['custom_field1'][0] : '');
		$codigodetalla[] .= trim(isset($meta['custom_field2'][0]) ? $meta['custom_field2'][0] : '');
		$codigodetalla[] .= trim(isset($meta['custom_field3'][0]) ? $meta['custom_field3'][0] : '');
		$codigodetalla[] .= trim(isset($meta['custom_field4'][0]) ? $meta['custom_field4'][0] : '');
		$codigodetalla[] .= trim(isset($meta['custom_field5'][0]) ? $meta['custom_field5'][0] : '');
		$codigodetalla[] .= trim(isset($meta['custom_field6'][0]) ? $meta['custom_field6'][0] : '');
		$codigodetalla[] .= trim(isset($meta['custom_field7'][0]) ? $meta['custom_field7'][0] : '');
		$codigodetalla[] .= trim(isset($meta['custom_field8'][0]) ? $meta['custom_field8'][0] : '');
		$codigodetalla[] .= trim(isset($meta['custom_field9'][0]) ? $meta['custom_field9'][0] : '');
		$codigodetalla[] .= trim(isset($meta['custom_field10'][0]) ? $meta['custom_field10'][0] : '');
		$codigodetallaCombine = implode("-", array_filter($codigodetalla));

		$codigo_de_talla = $multipleSizeArray['SIZES'][strtoupper($codigodetallaCombine)];

		/* echo $codigo_de_talla;
		echo "<br>"; */

		//$getFirstBarcode = get_post_meta($variationID, 'size_barcode1', true);

		//echo $getFirstBarcode . "<br>";
		$result = $dom->createElement('Linea');
		$root->appendChild($result);

		$result->setAttribute('Cia', "02");
		$result->setAttribute('Referencia', "$parentSKU");
		$result->setAttribute('Producto_reference', "$sku");
		$result->setAttribute('Dtll_Color', "$colorName");
		$result->setAttribute('Description', "$description_english");
		$result->setAttribute('Description_spanish', "$description_spanish");
		$result->setAttribute('Master_pack', "24");
		$result->setAttribute('Inner_pack', "12");
		$result->setAttribute('Cbms', "0.04");
		$result->setAttribute('Weight', "1.74");
		$result->setAttribute('Composicion', "$Composicion");
		// compesp -> compocision en español
		// Compesp=""
		// Price=""

		// $result->setAttribute('Compesp', "$Composicion_esp");
		$result->setAttribute('Compesp', "$Composicion_esp");
		$result->setAttribute('Price', "$price");
		$result->setAttribute('Unit', "PZA");
		$result->setAttribute('Woven_or_plane', "PUNTO");
		$result->setAttribute('Fob', "$fob");
		//$result->setAttribute('Fob', "");
		$result->setAttribute('Codigo_de_talla', "$codigo_de_talla");
		$result->setAttribute('Brand', "$brand");
		$result->setAttribute('Group', "$group");
		$result->setAttribute('Sub_group', "$sub_group");
		$result->setAttribute('Arancel', "$arancel");
		$result->setAttribute('Season', "$season");
		$result->setAttribute('LOB', "$lob");
		$result->setAttribute('Equipo', "$getTeamName1");
		$result->setAttribute('Gender', "$gender");
		$result->setAttribute('Logo_specs', "$logo_Application");
		
		

	  
	}
}


	if ($dom->save('ss24_products_no_sales_xml/INV_all_fexpro_ss24_products_no_sales_'.($i_file+1).'.xml')) {
		echo "Fexpro SS24 Product XML is created please click <a href='" . site_url() . "/wp-content/themes/porto-child/export/ss24_products_no_sales_xml/INV_all_fexpro_ss24_products_no_sales_".($i_file+1).".xml' target='_blank'>Click Here All products part ".($i_file+1)." </a> <br><hr>";
	} else {
		die('XML Create Error');
	}
}//END FOREACH CHUNCKS
