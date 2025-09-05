<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../../wp-load.php');
global $wpdb;

$args = array(
    'post_type' => array('product_variation'),
    'post_status' => array('publish', 'private'),
	'numberposts'=> -1 
);

$allProducts = get_posts( $args );

/* echo "<pre>";
print_r($allProducts);
echo "</pre>"; */

//$multipleArray = Array("BRAND" => Array("ATLETICO MADRID" => "ATM", "BARCELONA" => "BC", "GOLD'S GYM" => "GG", "JUVENTUS" => "JV", "LIVERPOOL" => "LV", "MANCHESTER CITY" => "MAN", "MLB" => "MLB", "NBA" => "NBA", "NFL" => "NFL", "UMBRO" => "UM"));

$multipleArray = Array("BRAND" => Array("47BRAND" => "47", "AC/DC" => "AC", "ADIDAS BOXING" => "AD", "ATLETICO DE MADRID" => "ATM", "AVENGERS" => "AV", "BEAVIS AND BUTT-HEAD" => "BB", "BARCELONA" => "BC", "BUDWEISER" => "BD", "COPA AMERICA" => "CA", "CARDI B" => "CB", "COCA COLA" => "CC", "CHAMPION" => "CH", "CORONA" => "CN", "CAYLER AND SON" => "CS", "DC" => "DC", "DEF LEPPARD" => "DL", "UMBRO-CLUBS" => "DO", "DISNEY" => "DS", "E-ONE" => "E1", "FIFA" => "FI", "FANTA" => "FT", "GREEN DAY" => "GD", "GENERICO" => "GE", "GOLD GYM" => "GG", "GI JOE" => "GJ", "THE GODFATHER" => "GOD", "HEAD" => "HD", "HULKMANIA" => "HLK", "JLO" => "JL", "JUVENTUS" => "JV", "KISS" => "KIS", "LUCAS FILM" => "LF", "LIVERPOOL" => "LV", "MANCHESTER CITY" => "MAN", "MARVEL COMICS" => "MC", "MICKEY MOUSE" => "MK", "MAJOR LEAGUE BASEBALL" => "ML", "MLB" => "MLB", "MY LITTLE PONY" => "MLP", "MITCHEL AND NESS" => "MN", "MARVEL" => "MV", "NASA" => "NAS", "NBA" => "NBA", "NBA PLAYERS" => "NBP", "NFL" => "NFL", "NIKE" => "NIK", "NIRVANA" => "NIR", "NICKELODEON" => "NK", "MATERIAL PUBLICITARIO" => "POP", "PRESIDENTE" => "PRE", "PARIS ST GERMAIN" => "PSG", "QUIKSILVER" => "QS", "REAL MADRID" => "RM", "ROXY" => "RX", "SMURFS" => "SMU", "SOUTH PARK" => "SOU", "SPRITE" => "SPR", "STARTER" => "ST", "STAR TREK" => "STR", "TOP GUN" => "TOP", "TRANSFORMER" => "TR", "TWISTER" => "TWI", "UEFA" => "UE", "UMBRO" => "UM", "WIZ KHALIFA" => "WKH", "WWE" => "WWE", "GRATEFUL DEAD" => "GD", "SLASH" => "SL", "POISON" => "PO", "THE ROCK" => "ROC", "CUSQUEÑA" => "CU", "NERF" => "NF", "CLUE" => "CLU"));

$multipleSizeArray = Array("SIZES" => Array("S-M-L-XL" => "25", "4-6-8-10-12-14-16" => "3", "7-8-9-10-11" => "SD", "28-29-30-31-32-33" => "CL", "OS" => "OS", "8-10-12-14-16" => "YH", "XS-S-M-L" => "WO"));

//$multipleCategoryArray = Array("CATEGORIES/GROUP" => Array("ACCESORIES" => "AC", "APPAREL" => "AP", "FOOTWEAR" => "FT", "UNDERWEAR &AMP; SLEEP" => "UN", "UNDERWEAR AND SLEEPWEAR" => "UN", "DRINKWEAR" => "DK", "DRINKWARE" => "DK", "YOUTH" => "ACC"));

$multipleCategoryArray = Array("CATEGORIES/GROUP" => Array("ACCESORIES" => "ACC", "APPAREL" => "AP", "FOOTWEAR" => "FT", "UNDERWEAR AND SLEEPWEAR" => "UN",  "LARGE EQUIPMENT" => "LEQ", "SMALL EQUIPMENT" => "SEQ", "HOODIE" => "HD", "JERSEY" => "JS",  "SHORTS" => "SH",  "TANK TOP" => "TT", "TRAINING JERSEY" => "JT", "TSHIRTS" => "TS", "DRINKWEAR" => "DK", "DRINKWARE" => "DK", "UNDERWEAR &AMP; SLEEP" => "UN", "YOUTH" => "ACC"));

$multipleSeasonArray = Array("SEASON" => Array("Fall Winter 21" => "212", "Basics" => "BAS"));

$multipleSubCategoryArray = Array("SUB-CATEGORIES/SUB-GROUP" => Array("T-Shirts" => "TS", "Tank Top" => "TT", "Tank Tops" => "TT", "Jerseys" => "JS", "Jackets" => "JK", "Hoodies" => "HD", "Long Sleeves" => "LS", "Sweatshirts" => "SW", "Training Jerseys" => "JT", "Pants" => "PT", "Polos" => "PL", "Shorts" => "SH", "Boxers and Underwear" => "BX", "Socks" => "SO", "Pijamas" => "PJ", "Slides" => "SL", "Flip Flops" => "FF", "Clogs" => "CLO", "Caps" => "CA", "Beanies" => "BN", "Scarf" => "SC", "Sport Bra" => "SBR", "Crop Top Tees" => "CT", "ACCESSORIES" => "AC"));

//$multipleSubCategoryArray = array_change_key_case($multipleSubCategoryArray['SUB-CATEGORIES/SUB-GROUP'],CASE_UPPER);

//print_r($multipleSubCategoryArray);
//echo "<br>";

$multipleGenderArray = Array("GENDER" => Array("Mens" => "5", "Boys" => "3", "Womens" => "6"));

$multipleTeamArray = Array("TEAM NAME" => Array("49ERS" => "49E", "MLB" => "MLB", "BARCELONA" => "BAR", "BRAVES" => "BRA", "BUCCANEERS" => "BUC", "BULLS" => "BUL", "CELTICS" => "CEL", "CLIPPERS" => "CLI", "CUBS" => "CUB", "CHIEFS" => "CHI", "DODGERS" => "DOD", "DOLPHINS" => "DOL", "EAGLES" => "EAG", "GIANTS" => "GIA", "HEAT" => "HEA", "HORNETS" => "HOR", "JAZZ" => "JAZ", "KNICKS" => "KNI", "LAKERS" => "LAK", "MAVERICKS" => "MAV", "METS" => "MET", "NBA" => "NBA",  "NETS" => "NET", "NUGGETS" => "NUG", "PACKERS" => "PAC", "PATRIOTS" => "PAT", "PELICANS" => "PEL", "RAIDERS" => "RAI", "RAMS" => "RAM", "RAPTORS" => "RAP", "RAVENS" => "RAV", "RED SOX" => "RED", "ROCKETS" => "ROC", "SAINTS" => "SAI", "SEAHAWKS" => "SEA", "SPURS" => "SPU", "STEELERS" => "STE", "SUNS" => "SUN", "TRAIL BLAZERS" => "TRA", "WARRIORS" => "WAR", "YANKEES" => "YAN"));

/* echo "<pre>";
print_r($multipleArray);
echo "</pre>"; */


$dom = new DOMDocument('1.0','UTF-8');
$dom->formatOutput = true;

$root = $dom->createElement('Sagemobile');
$dom->appendChild($root);

foreach($allProducts as $key => $value)
{
	$parentID = $value->post_parent;
	$variationID = $value->ID;
	
	
	$cat = get_the_terms( $parentID , 'product_cat' );
	$css_slugGender = array();
	$css_slugCategory = array();
	$css_slugSubCategory = array();
	$codigodetalla = array();
	echo "<pre>";
	print_r($cat);
	echo "</pre>";
	foreach($cat as $cvalue)
	{
		if($cvalue->parent != 0)
		{
			//$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
			//$css_slugSubCategory[] = $cvalue->name;
			//$css_slugCategory[] = $term->name;
			
			if($cvalue->slug == 'youth' || $cvalue->slug == 'caps-youth' || $cvalue->slug == 'paris-caps' || $cvalue->slug == 'dominican-caps')
			{
				$css_slugSubCategory[] = 'Caps';
				$css_slugCategory[] = 'Youth';
			}
			else
			{
				if($cvalue->parent == '1423')
				{
				$css_slugGender[] = $cvalue->name;	
				}
				else
				{
					$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
					$css_slugSubCategory[] = $cvalue->name;
					$css_slugCategory[] = $term->name;
				}
				//echo $cvalue->parent . "<br>";
			}
		}
		else
		{
			//echo "kairav";
			//$css_slugGender[] = $cvalue->name;
			continue;
		}
	}
	
	if(!has_term( 'popup', 'product_cat', $parentID ))
	{
		continue;
	}
	else
	{
		echo "<hr>";
		echo $parentSKU . "<br>";
		print_r($css_slugCategory);
		echo "<br>";
		print_r($css_slugSubCategory);
		echo "<br>";
		echo "Gender: ";
		print_r($css_slugGender);
		echo "<br>";
		
		//die();
		$customProduct = wc_get_product( $parentID );
		
		
		$sku = get_post_meta($variationID, '_sku', true);
		$parentSKU = preg_replace ('/\-[^-]*$/', '', $sku);
		$colorName = str_replace($parentSKU . "-", "", $sku);
		
		$description_spanish = get_post_meta($parentID, 'descripcion', true);
		$description_english = $customProduct->get_description();
		
		
		$Composicion = $customProduct->get_attribute( 'pa_fabric-composition' );
		$brand = strtoupper($customProduct->get_attribute( 'pa_brand' ));
		//echo $brand . "<br>";
		$brand = $multipleArray['BRAND'][$brand];
		echo strtoupper($css_slugCategory[0]) . "<br>";
		$group = $multipleCategoryArray['CATEGORIES/GROUP'][strtoupper($css_slugCategory[0])];
		$sub_group = $multipleSubCategoryArray['SUB-CATEGORIES/SUB-GROUP'][$css_slugSubCategory[0]];
		$gender = $multipleGenderArray['GENDER'][$css_slugGender[0]];
		
		/* $season = $customProduct->get_attribute( 'pa_season' );
		$season = $multipleSeasonArray['SEASON'][$season]; */
		$season = '212';
		
		$logo_Application = $customProduct->get_attribute( 'pa_logo-application' );
		
		if(!empty(get_post_meta($variationID, 'product_team', true)))
		{
			$getTeamName = strtoupper(get_post_meta($variationID, 'product_team', true));
			$getTeamName1 = $multipleTeamArray['TEAM NAME'][$getTeamName];
		}
		else
		{
			$getTeamName1 = '';
		}

		$codigodetalla[] = trim(get_post_meta( $variationID, 'custom_field1', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field2', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field3', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field4', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field5', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field6', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field7', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field8', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field9', true ));
		$codigodetalla[] .= trim(get_post_meta( $variationID, 'custom_field10', true ));		
		$codigodetallaCombine = implode("-", array_filter($codigodetalla));

		$codigo_de_talla = $multipleSizeArray['SIZES'][strtoupper($codigodetallaCombine)];

		echo $codigo_de_talla;
		echo "<br>";
		
		//$getFirstBarcode = get_post_meta($variationID, 'size_barcode1', true);
		
		//echo $getFirstBarcode . "<br>";
		$result = $dom->createElement('Linea');
		$root->appendChild($result);
		
		$result->setAttribute('Cia', "08");
		$result->setAttribute('Producto_reference', $parentSKU . $colorName);
		$result->setAttribute('Description', "$description_english");
		$result->setAttribute('Description_spanish', "$description_spanish");
		$result->setAttribute('Master_pack', "24");
		$result->setAttribute('Inner_pack', "12");
		$result->setAttribute('Cbms', "0.04");
		$result->setAttribute('Weight', "1.74");
		$result->setAttribute('Composicion', "$Composicion");
		$result->setAttribute('Unit', "PZA");
		$result->setAttribute('Woven_or_plane', "PUNTO");
		$result->setAttribute('Fob', "2.60");
		$result->setAttribute('Codigo_de_talla', "$codigo_de_talla");
		$result->setAttribute('Brand', "$brand");
		$result->setAttribute('Group', "$group");
		$result->setAttribute('Sub_group', "$sub_group");
		$result->setAttribute('Arancel', "$sub_group");
		$result->setAttribute('Season', "$season");
		$result->setAttribute('Equipo', "$getTeamName1");
		$result->setAttribute('Gender', "$gender");
		$result->setAttribute('Logo_specs', "$logo_Application");
		
		
		$variationSize = array();
		$variationBarode = array();
		
		$variationSize[] = get_post_meta($variationID, 'custom_field1', true);
		$variationSize[] = get_post_meta($variationID, 'custom_field2', true);
		$variationSize[] = get_post_meta($variationID, 'custom_field3', true);
		$variationSize[] = get_post_meta($variationID, 'custom_field4', true);
		$variationSize[] = get_post_meta($variationID, 'custom_field5', true);
		$variationSize[] = get_post_meta($variationID, 'custom_field6', true);
		$variationSize[] = get_post_meta($variationID, 'custom_field7', true);
		$variationSize[] = get_post_meta($variationID, 'custom_field8', true);
		$variationSize[] = get_post_meta($variationID, 'custom_field9', true);
		$variationSize[] = get_post_meta($variationID, 'custom_field10', true);
		
		$variationBarode[] = get_post_meta($variationID, 'size_barcode1', true);
		$variationBarode[] = get_post_meta($variationID, 'size_barcode2', true);
		$variationBarode[] = get_post_meta($variationID, 'size_barcode3', true);
		$variationBarode[] = get_post_meta($variationID, 'size_barcode4', true);
		$variationBarode[] = get_post_meta($variationID, 'size_barcode5', true);
		$variationBarode[] = get_post_meta($variationID, 'size_barcode6', true);
		$variationBarode[] = get_post_meta($variationID, 'size_barcode7', true);
		$variationBarode[] = get_post_meta($variationID, 'size_barcode8', true);
		$variationBarode[] = get_post_meta($variationID, 'size_barcode9', true);
		$variationBarode[] = get_post_meta($variationID, 'size_barcode10', true);
		
		
		//print_r($variationBarode);
		$i = 0;
		foreach($variationSize as $vSize)
		{
			
			
			if(!empty($vSize))
			{
				
				if(!empty($variationBarode[$i]))
				{
					$getFirstBarcodeNumber = str_replace(",","", number_format($variationBarode[$i]));
				}
				else
				{
					$getFirstBarcodeNumber = '';
				}
				
				$q = strtoupper($vSize);
				
				$result1 = $dom->createElement('Colors');
				$result->appendChild($result1);
			
				$result1->setAttribute('Color', "$colorName");
				$result1->setAttribute('Barcode', "$getFirstBarcodeNumber");
				$result1->setAttribute('Sizes', "$q");
				
				$result1->appendChild($dom->createTextNode(''));
				$result->appendChild($result1);
			}
			
			$i++;
			
		}
		
		
		/* $result1->appendChild($dom->createTextNode(''));
		$result->appendChild($result1); */
	}	
	
}
if($dom->save('INV_all_fexpro_pop_products_mexico.xml'))
{
   echo "FEXpro Pop Product XML is created please click <a href='". site_url() ."/wp-content/themes/porto-child/fexpro_pop_products_xml/mexico/INV_all_fexpro_pop_products_mexico.xml' target='_blank'>Click Here All products </a> <br><hr>"; 
}
else
{
	die('XML Create Error');
}




