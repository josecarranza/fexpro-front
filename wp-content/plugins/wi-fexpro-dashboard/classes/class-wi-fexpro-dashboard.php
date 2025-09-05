<?php
class WiFexproDashboard
{
	public $db;
	public $model;
	public $ordersXml;

	// public $sftpServer= 'wwwfexpro.eastus2.cloudapp.azure.com';
	public $sftpServer = '20.57.63.38';
	public $sftpUsername = 'ftpfexpro';
	public $sftpPassword = 'WP820.1.com';

	/* public $sftpServer= 'demo.web-informatica.info';
	public $sftpUsername = 'demo';
	public $sftpPassword = 't!N!p@LCnNoy'; */

	public function __construct()
	{
		global $wpdb;
		$this->db = $wpdb;
		/* ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL); */


		$this->model =  new ModelFexproDashboard();
		add_action('admin_menu', array($this, "wi_collection_setup_menu"));

		add_action('wp_ajax_widash_getorderstatus', array($this, "getOrderStatus"));
		add_action('wp_ajax_nopriv_widash_getorderstatus', array($this, "getOrderStatus"));

		add_action('wp_ajax_widash_getcountries', array($this, "getCountries"));
		add_action('wp_ajax_nopriv_widash_getcountries', array($this, "getCountries"));

		add_action('wp_ajax_widash_getsummary', array($this, "getSummary"));
		add_action('wp_ajax_nopriv_widash_getsummary', array($this, "getSummary"));

		add_action('wp_ajax_widash_getseasons', array($this, "getSeasons"));
		add_action('wp_ajax_nopriv_widash_getseasons', array($this, "getSeasons"));

		add_action('wp_ajax_widash_export_products', array($this, "exportProducts"));
		add_action('wp_ajax_nopriv_widash_export_products', array($this, "exportProducts"));

		add_action('wp_ajax_widash_export_images', array($this, "exportImages"));
		add_action('wp_ajax_nopriv_widash_export_images', array($this, "exportImages"));

		add_action('wp_ajax_widash_get_orders', array($this, "getOrders"));
		add_action('wp_ajax_nopriv_widash_get_orders', array($this, "getOrders"));

		add_action('wp_ajax_widash_test', array($this, "test"));
		add_action('wp_ajax_nopriv_widash_test', array($this, "test"));

		add_action('wp_ajax_widash_download_zip_products', array($this, "download_zip_products"));
		add_action('wp_ajax_nopriv_widash_download_zip_products', array($this, "download_zip_products"));

		add_action('wp_nav_menu_item_custom_fields', [$this, 'menu_item_collection_config']);
		add_action('wp_update_nav_menu_item', [$this, 'menu_item_collection_config_save'], 10, 2);

		$this->ordersXml = new WiOrdersXml();
		// add_action('wp_ajax_widash_orders_xml', array($this->ordersXml, "test"));
		// add_action('wp_ajax_nopriv_widash_orders_xml', array($this->ordersXml, "test"));
		
	}
	function respond($data)
	{
		wp_send_json($data);
		die();
	}
	function wi_collection_setup_menu()
	{

		add_menu_page('Fexpro Dashboard', 'Fexpro Dashboard', 'manage_options', 'wi_fexpro_dashboard', '', '');

		add_submenu_page(
			'wi_fexpro_dashboard',
			"Summary",
			"Summary",
			'manage_options',
			'wi_fexpro_dashboard',
			array($this, "page_summary")
		);
		add_submenu_page(
			'wi_fexpro_dashboard',
			"Products",
			"Products",
			'manage_options',
			'wi_fexpro_dashboard_products',
			array($this, "page_products")
		);
		add_submenu_page(
			'wi_fexpro_dashboard',
			"Images",
			"Images",
			'manage_options',
			'wi_fexpro_dashboard_images',
			array($this, "page_images")
		);
		add_submenu_page(
			'wi_fexpro_dashboard',
			"Orders",
			"Orders",
			'manage_options',
			'wi_fexpro_dashboard_orders',
			array($this, "page_orders")
		);

	}

	function page_summary()
	{

		include(WI_PLUGIN_FEXPRO_DASHBOARD_PATH . "/views/page-summary.php");
	}

	function page_products()
	{
		include(WI_PLUGIN_FEXPRO_DASHBOARD_PATH . "/views/page-products.php");
	}
	function page_images()
	{
		include(WI_PLUGIN_FEXPRO_DASHBOARD_PATH . "/views/page-images.php");
	}
	function page_orders()
	{
		include(WI_PLUGIN_FEXPRO_DASHBOARD_PATH . "/views/page-orders.php");
	}
	function getOrderStatus()
	{
		$list = $this->model->getOrderStatus();

		$json['order_status'] = $list;
		$this->respond($json);
	}
	function getCountries()
	{
		$countries = $this->model->getCountries();
		$json['countries'] = $countries;
		$this->respond($json);
	}

	function getSummary()
	{
		$post = json_decode(file_get_contents('php://input'));
		$params = [];
		$params["status"] = $post->status ?? [];
		$params["countries"] = $post->countries ?? [];

		$summary = $this->model->getReportSummary($params);
		$json['summary'] = $summary;
		$this->respond($json);
	}
	function getSeasons()
	{
		$seasons = $this->model->getSeasons();
		$json['seasons'] = $seasons;
		$this->respond($json);
	}

	function exportProducts()
	{

		$post = json_decode(file_get_contents('php://input'));
		$filter = [];
		$filter['status'] = $post->status ?? "";
		$filter['season'] = $post->season ?? "";
		$filter['qs'] = $post->qs ?? [];
		$sold = $post->sold ? 1 : 0;

		$skus = $post->skus ?? '';

		if ($filter['status'] != "") {
			$products =  $this->model->getXMLProducts($filter);
		}
		if ($filter['season'] != "") {
			$products =  $this->model->getXMLProductsSeason($filter, $sold);
		}
		if ($skus != '') {
			$skus = explode(',', $skus);
			$products =  $this->model->getXMLProductsSKU($skus);
		}

		$chunks = array_chunk($products, 500);

		$rand = uniqid();
		mkdir(WI_PLUGIN_FEXPRO_DASHBOARD_PATH . "/tmp/" . $rand);
		$files = [];
		$name = date("Ymd_His");
		foreach ($chunks as $i => $chunk) {
			$files[] = $this->buildXMLProduct($chunk, $i, $rand, $name);
		}
		// exit;
		$success = false;
		/* foreach($files as $i=> $file){
			$success = $this->ftp($file["filename"],$file["path"]);
			$files[$i]['upload'] = $success?1:0;
			@unlink($file["path"]);
			//var_dump($success);
		} */
		//zip folder
		$zip_file = "INV_products_" . $name . ".zip";
		$command = "cd " . WI_PLUGIN_FEXPRO_DASHBOARD_PATH . "/tmp/" . $rand . "/" . " && zip -r  ../" . $zip_file . " *";
		shell_exec($command);

		shell_exec("cd " . WI_PLUGIN_FEXPRO_DASHBOARD_PATH . "/tmp/" . " && rm -rf " . $rand . "/*");
		rmdir(WI_PLUGIN_FEXPRO_DASHBOARD_PATH . "/tmp/" . $rand);


		$json["error"] = 0;// $success ? 0 : 1;
		$json["files"] = $files;
		$json['zip'] = get_site_url()."/wp-admin/admin-ajax.php?action=widash_download_zip_products&zip=".$zip_file;

		$this->respond($json);
	}
	function buildXMLProduct($allProducts, $i_file, $folder, $name)
	{
		$multipleGenderArray = array(
			"GENDER" => array(
				"MEN" => "05",
				"BOYS" => "03",
				"KIDS" => "03",
				"WOMEN" => "06",
				"UNISEX" => "UNI",
				"TEENS" => "TEENS"
			)
		);
		$multipleTeamArray = array("TEAM NAME" => array("NINERS" => "49E", "TEXANS" => "TXA", "VIKINGS" => "VIK", "PANTHERS" => "PAN", "JAGUARS" => "JAG", "BRONCOS" => "BRO", "LIONS" => "LIO", "BEARS" => "BEA", "ASTROS" => "AST", "49ERS" => "49E", "MLB" => "MLB", "BARCELONA" => "BAR", "BRAVES" => "BRA", "BUCCANEERS" => "BUC", "BULLS" => "BUL", "CELTICS" => "CEL", "CLIPPERS" => "CLI", "CUBS" => "CUB", "CHIEFS" => "CHI", "DODGERS" => "DOD", "DOLPHINS" => "DOL", "EAGLES" => "EAG", "GIANTS" => "GIA", "HEAT" => "HEA", "HORNETS" => "HOR", "JAZZ" => "JAZ", "KNICKS" => "KNI", "LAKERS" => "LAK", "MAVERICKS" => "MAV", "METS" => "MET", "NBA" => "NBA",  "NETS" => "NET", "NUGGETS" => "NUG", "PACKERS" => "PAC", "PATRIOTS" => "PAT", "PELICANS" => "PEL", "RAIDERS" => "RAI", "RAMS" => "RAM", "RAPTORS" => "RAP", "RAVENS" => "RAV", "RED SOX" => "RED", "ROCKETS" => "ROC", "SAINTS" => "SAI", "SEAHAWKS" => "SEA", "SPURS" => "SPU", "STEELERS" => "STE", "SUNS" => "SUN", "TRAIL BLAZERS" => "TRA", "WARRIORS" => "WAR", "YANKEES" => "YAN", "CORONA" => "CN", "FANTA" => "FT", "COCA COLA" => "CC", "SPRITE" => "SPR", "BUCKS" => "BCK", "RED SOXS" => "RED", "76ERS" => "76E", "7ERS" => "76E", "PHOENIX" => "SUN", "REAL MADRID" => "RMD", "PARIS ST GERMAIN" => "PSG"));

		$multipleSizeArray = array("SIZES" => array("S-M-L-XL" => "25", "4-6-8-10-12-14-16" => "3", "7-8-9-10-11" => "SD", "28-29-30-31-32-33" => "CL", "OS" => "OS", "8-10-12-14-16" => "YH", "XS-S-M-L" => "WO", "10-11-12-13-1" => "C2", "40-41-43-44-45" => "E1",  "4-6-8-10" => "62", "10-12-14-16" => "TE", "S/M,L/XL" => "GG"));
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->formatOutput = true;

		$root = $dom->createElement('Sagemobile');
		$dom->appendChild($root);

		foreach ($allProducts as $key => $value) {
			$parentID = $value->post_parent;
			$variationID = $value->variation_id;


			//}

			$cat = get_the_terms($parentID, 'product_cat');

			$codigodetalla = array();

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
			$almacen = isset($meta['warehouse'][0]) ? $meta['warehouse'][0] : '';
			$group = isset($meta['grupo'][0]) ? $meta['grupo'][0] : '';
			$sub_group = isset($meta['subgrupo'][0]) ? $meta['subgrupo'][0] : '';
			//$supplier = isset($meta['supplier'][0]) ? $meta['supplier'][0] : '';
			$gender = $multipleGenderArray['GENDER'][strtoupper($customProduct->get_attribute('pa_gender'))] ?? "";
			$drop = isset($meta['drop'][0]) ? $meta['drop'][0] : '';

			$price = isset($meta['_regular_price'][0]) ? $meta['_regular_price'][0] : '';
			$logo_Application = isset($meta['logo_application'][0]) ? $meta['logo_application'][0] : '';
			$fob = isset($meta['fob'][0]) ? $meta['fob'][0] : '';
			$arancel = isset($meta['arancel'][0]) ? $meta['arancel'][0] : '';
			$lob = isset($meta['lob'][0]) ? $meta['lob'][0] : '';
			$season = isset($meta['season'][0]) ? $meta['season'][0] : '';
			$codigo_sat = isset($meta['codigo_sat'][0]) ? $meta['codigo_sat'][0] : '';

			if (!empty(isset($meta['product_team'][0]) ? $meta['product_team'][0] : '')) {
				$getTeamName = strtoupper(isset($meta['product_team'][0]) ? $meta['product_team'][0] : '');
				$getTeamName1 = $multipleTeamArray['TEAM NAME'][trim($getTeamName)] ?? "";
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
			$result->setAttribute('Drop', "$drop");
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
			//$result->setAttribute('NoSuplidor', "$supplier");
			$result->setAttribute('Equipo', "$getTeamName1");
			$result->setAttribute('Gender', "$gender");
			$result->setAttribute('Logo_specs', "$logo_Application");

			$result->setAttribute('CODIGO_SAT', "$codigo_sat");
			$result->setAttribute('Almacen', "$almacen");


			$variationSize = array();
			$variationBarode = array();

			$variationSize[] = isset($meta['custom_field1'][0]) ? $meta['custom_field1'][0] : '';
			$variationSize[] = isset($meta['custom_field2'][0]) ? $meta['custom_field2'][0] : '';
			$variationSize[] = isset($meta['custom_field3'][0]) ? $meta['custom_field3'][0] : '';
			$variationSize[] = isset($meta['custom_field4'][0]) ? $meta['custom_field4'][0] : '';
			$variationSize[] = isset($meta['custom_field5'][0]) ? $meta['custom_field5'][0] : '';
			$variationSize[] = isset($meta['custom_field6'][0]) ? $meta['custom_field6'][0] : '';
			$variationSize[] = isset($meta['custom_field7'][0]) ? $meta['custom_field7'][0] : '';
			$variationSize[] = isset($meta['custom_field8'][0]) ? $meta['custom_field8'][0] : '';
			$variationSize[] = isset($meta['custom_field9'][0]) ? $meta['custom_field9'][0] : '';
			$variationSize[] = isset($meta['custom_field10'][0]) ? $meta['custom_field10'][0] : '';

			$variationBarode[] = isset($meta['size_barcode1'][0]) ? $meta['size_barcode1'][0] : '';
			$variationBarode[] = isset($meta['size_barcode2'][0]) ? $meta['size_barcode2'][0] : '';
			$variationBarode[] = isset($meta['size_barcode3'][0]) ? $meta['size_barcode3'][0] : '';
			$variationBarode[] = isset($meta['size_barcode4'][0]) ? $meta['size_barcode4'][0] : '';
			$variationBarode[] = isset($meta['size_barcode5'][0]) ? $meta['size_barcode5'][0] : '';
			$variationBarode[] = isset($meta['size_barcode6'][0]) ? $meta['size_barcode6'][0] : '';
			$variationBarode[] = isset($meta['size_barcode7'][0]) ? $meta['size_barcode7'][0] : '';
			$variationBarode[] = isset($meta['size_barcode8'][0]) ? $meta['size_barcode8'][0] : '';
			$variationBarode[] = isset($meta['size_barcode9'][0]) ? $meta['size_barcode9'][0] : '';
			$variationBarode[] = isset($meta['size_barcode10'][0]) ? $meta['size_barcode10'][0] : '';


			//print_r($variationBarode);
			$i = 0;
			foreach ($variationSize as $vSize) {


				if (!empty($vSize)) {

					if (!empty($variationBarode[$i])) {
						$getFirstBarcodeNumber = str_replace(",", "", number_format((float)$variationBarode[$i]));
					} else {
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
		$basepath = WI_PLUGIN_FEXPRO_DASHBOARD_PATH . "/tmp/" . $folder . "/";
		$filename = 'INV_products_' . $name . "_" . ($i_file + 1) . '.xml';
		if ($dom->save($basepath . $filename)) {
			return ["filename" => $filename, "path" => $basepath . $filename];
		} else {
			return null;
		}
	}

	function exportImages()
	{
		set_time_limit(300);
		ini_set('memory_limit', '1024M');
		$post = json_decode(file_get_contents('php://input'));
		$filter = [];
		$filter['status'] = $post->status ?? "";
		$filter['season'] = $post->season ?? "";
		$filter['qs'] = $post->qs ?? [];
		//$sold = $post->sold ? 1: 0;
		$type_export = isset($post->images) && $post->images == 1 ? 1 : 2;

		$skus = $post->skus ?? '';

		if ($filter['status'] != "") {
			$products =  $this->model->getXMLProducts($filter);
		}
		if ($filter['season'] != "") {
			$products =  $this->model->getXMLProductsSeason($filter, 1);
		}
		if ($skus != '') {
			$skus = explode(',', $skus);
			$products =  $this->model->getXMLProductsSKU($skus);
		}

		$chunks = array_chunk($products, 250);

		$rand = uniqid();
		mkdir(WI_PLUGIN_FEXPRO_DASHBOARD_PATH . "/tmp/" . $rand);
		$files = [];
		$name = date("Ymd_His");
		$log_files = [];
		foreach ($chunks as $i => $chunk) {

			if ($type_export == 1) {
				$tmp_files = [];
				$zipname = $this->buildZipImages($chunk, $i, $rand, $name, $tmp_files);

				$files[] = $zipname;
				$log_files[$zipname['filename']] = $tmp_files;
			} else {
				$files[] = $this->buildXMLImages($chunk, $i, $rand, $name);
			}
		}
		$success = false;
		foreach ($files as $i => $file) {
			$success = $this->ftp($file["filename"], $file["path"]);
			$files[$i]['upload'] = $success ? 1 : 0;
			@unlink($file["path"]);
			//var_dump($success);
		}
		rmdir(WI_PLUGIN_FEXPRO_DASHBOARD_PATH . "/tmp/" . $rand);
		$json["error"] = $success ? 0 : 1;
		$json["files"] = $files;
		$json["log_files"] = $log_files;

		$this->respond($json);
	}
	function buildXMLImages($allProducts, $i_file, $folder, $name)
	{

		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->formatOutput = true;

		$root = $dom->createElement('Sagemobile');
		$dom->appendChild($root);

		$size = "medium";

		foreach ($allProducts as $key => $value) {
			$parentID = $value->post_parent;
			$variationID = $value->variation_id;

			if (has_term('PRESALE', 'product_cat',  $parentID)) {

				$cat = get_the_terms($parentID, 'product_cat');
				$css_slugGender = array();
				$css_slugCategory = array();
				$css_slugSubCategory = array();
				$codigodetalla = array();

				$getSKU = get_post_meta($variationID, '_sku', true);
				$ak = str_replace(' ', '-', strtolower($getSKU));

				foreach ($cat as $cvalue) {
					if ($cvalue->parent != 0) {
						$term = get_term_by('id', $cvalue->parent, 'product_cat');
						$css_slugSubCategory[] = $cvalue->name;
						$css_slugCategory[] = $term->name;
					} else {
						$css_slugGender[] = $cvalue->name;
					}
				}

				if ($css_slugGender[0] == 'Fitness') {
					continue;
				} else {

					$result = $dom->createElement('Imagenes');
					$root->appendChild($result);


					$result->setAttribute('Producto', $getSKU);

					//echo "Producto " . $getSKU . "<br>";

					$thumb_image = wp_get_attachment_image_src(get_post_thumbnail_id($variationID), $size, true);


					$server_path = str_replace($url_path, $images_path, $thumb_image[0]);


					$tokens = explode('/', $thumb_image[0]);
					$str = trim(end($tokens));
					//echo substr($thumb_image[0], 0, strrpos( $thumb_image[0], '/'));



					if ($str != 'default.png') {
						//echo "Main image: " . $str . "<br>";
						$result->setAttribute('MainImage', $str);

						//@copy($server_path,$self_path."/images/".$zip_folder_name."/".$str );
					}

					$gallery  = maybe_unserialize(get_post_meta($variationID, 'woo_variation_gallery_images', true));
					if (! empty($gallery)) {
						foreach ($gallery as $gvalue) {
							$result1 = $dom->createElement('Secondary');
							$result->appendChild($result1);

							$thumb_image1 = wp_get_attachment_image_src($gvalue, $size);
							$tokens1 = explode('/', $thumb_image1[0]);
							$str1 = trim(end($tokens1));
							//echo "Child image: " . $str1 . "<br>";

							$result1->setAttribute('Archivo', $str1);

							$result1->appendChild($dom->createTextNode(''));
							$result->appendChild($result1);

							$server_path1 = str_replace($url_path, $images_path, $thumb_image1[0]);

							//@copy($server_path1,$self_path."/images/".$zip_folder_name."/".$str1 );
						}
					}
				}
			}
		}
		$basepath = WI_PLUGIN_FEXPRO_DASHBOARD_PATH . "/tmp/" . $folder . "/";
		$filename = 'IMG_' . $name . "_" . ($i_file + 1) . '.xml';
		if ($dom->save($basepath . $filename)) {
			return ["filename" => $filename, "path" => $basepath . $filename];
		} else {
			return null;
		}
	}
	function buildZipImages($allProducts, $i_file, $folder, $name, &$log_files = [])
	{

		$images_path = "/var/www/vhosts/fexpro.com/shop.fexpro.com/";
		$url_path = "https://shop2.fexpro.com/";
		$size = "medium";

		$tmp_path = WI_PLUGIN_FEXPRO_DASHBOARD_PATH . "/tmp/";

		foreach ($allProducts as $key => $value) {
			$parentID = $value->post_parent;
			$variationID = $value->variation_id;

			if (has_term('PRESALE', 'product_cat',  $parentID)) {

				$thumb_image = wp_get_attachment_image_src(get_post_thumbnail_id($variationID), $size, true);

				$server_path = str_replace($url_path, $images_path, $thumb_image[0]);

				$tokens = explode('/', $thumb_image[0]);
				$str = trim(end($tokens));
				//echo substr($thumb_image[0], 0, strrpos( $thumb_image[0], '/'));

				if ($str != 'default.png') {
					@copy($server_path, $tmp_path . $folder . "/" . $str);
					$log_files[] = $str;
				}

				$gallery  = maybe_unserialize(get_post_meta($variationID, 'woo_variation_gallery_images', true));
				if (! empty($gallery)) {
					foreach ($gallery as $gvalue) {

						$thumb_image1 = wp_get_attachment_image_src($gvalue, $size);
						$tokens1 = explode('/', $thumb_image1[0]);
						$str1 = trim(end($tokens1));

						$server_path1 = str_replace($url_path, $images_path, $thumb_image1[0]);

						@copy($server_path1, $tmp_path . $folder . "/" . $str1);
						$log_files[] = $str1;
					}
				}
			}
		}
		$zip_file = 'IMG_' . $name . "_" . ($i_file + 1) . ".zip";
		$command = "cd " . $tmp_path . $folder . "/" . " && zip -r  ../" . $zip_file . " *";
		shell_exec($command);
		//echo "cd ".$tmp_path.$folder."/"." && rm -rf ".$tmp_path.$folder."/";
		shell_exec("cd " . $tmp_path . $folder . "/" . " && rm -rf " . $tmp_path . $folder . "/*");
		return ["filename" => $zip_file, "path" => $tmp_path . $zip_file];
	}

	function getOrders()
	{
		$post = json_decode(file_get_contents('php://input'));
		$params = [];
		$params["status"] = $post->status ?? [];
		$params["countries"] = $post->countries ?? [];

		// if(count($params["status"])==0 OR count($params["countries"])==0){
		if (count($params["status"]) == 0) {
			$json['error'] = 1;
			$json['message'] = 'params required';
			$this->respond($json);
		}

		$orders = $this->model->getOrders($params);
		$json['orders'] = $orders;
		$this->respond($json);
	}

	function test()
	{
		sleep(5);
		$json["error"] = 0;
		$json['id'] = $_POST['orderid'];


		$this->respond($json);
	}

	function ftp($name, $dataFile)
	{
		//return true;
		$sftpPort = 21;
		$sftpServer = $this->sftpServer;
		$sftpUsername = $this->sftpUsername;
		$sftpPassword = $this->sftpPassword;
		$sftpRemoteDir = "";
		//$sftpRemoteDir="/test";

		$protocol = $sftpPort == 22 ? "sftp" : "ftp";
		$ch = curl_init($protocol . '://' . $sftpServer . ':' . $sftpPort . $sftpRemoteDir . '/' . $name);

		$fh = fopen($dataFile, 'r');

		if ($fh) {
			curl_setopt($ch, CURLOPT_USERPWD, $sftpUsername . ':' . $sftpPassword);
			curl_setopt($ch, CURLOPT_UPLOAD, true);
			curl_setopt($ch, CURLOPT_FTP_CREATE_MISSING_DIRS, true);
			if ($protocol == "sftp") {
				curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
			}

			curl_setopt($ch, CURLOPT_INFILE, $fh);
			curl_setopt($ch, CURLOPT_INFILESIZE, filesize($dataFile));
			curl_setopt($ch, CURLOPT_VERBOSE, true);

			$verbose = fopen('php://temp', 'w+');
			curl_setopt($ch, CURLOPT_STDERR, $verbose);

			$response = curl_exec($ch);
			$error = curl_error($ch);
			curl_close($ch);

			if ($response) {
				return true;
			} else {
				return false;
			}
		}
	}

	function download_zip_products()
	{
		$zipname = $_GET['zip'] ?? "";
		$zipname = basename($zipname);
		if($zipname == ""){
			header("HTTP/1.1 404 Not Found");
			exit;
		}

		$zipFilePath = WI_PLUGIN_FEXPRO_DASHBOARD_PATH . "/tmp/".$zipname;
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename="' . $zipname . '"');
		header('Content-Length: ' . filesize($zipFilePath));
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

		// Enviar el archivo al navegador
		readfile($zipFilePath);

		// Eliminar el archivo temporal después de enviarlo (opcional)
		@unlink($zipFilePath);
		// @unlink(WI_PLUGIN_FEXPRO_DASHBOARD_PATH . "/tmp/");
	}


}
