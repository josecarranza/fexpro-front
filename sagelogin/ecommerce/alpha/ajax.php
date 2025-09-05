<?php 
require_once 'include/common.php';
include ('include/FexproReporte.php');
get_currentuserinfo();

if(!is_user_logged_in()) {
	header("HTTP/1.1 401 Unauthorized");
    exit;	
}

class AjaxReport{
	function test(){
		echo "test";
	}

	function report_group_by_pi(){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			$post = json_decode(file_get_contents('php://input'));
		}else{
			$post = (object)$_GET;
		}
		$presale_status = isset($post->presale_status) && $post->presale_status!="" ? addslashes($post->presale_status) : "";
		
		$fexproReporte = new FexproReporte($presale_status);

		$report = $fexproReporte->report_group_by_pi();

		$log_sage_orders = get_option("WI_SAGE_ORDER_GENERATOR");
		$log_sage_orders = json_decode($log_sage_orders) ?? [];

		foreach ($report as $i=> $item) {
			$report[$i]->status = "PENDING";
			foreach ($log_sage_orders as $sub) {
				if($sub->pi_numeral == $item->pi_numeral && $sub->supplier == $item->supplier && $sub->sourcing_office == $item->sourcing_office){
					$report[$i]->status = $sub->status;
					break;
				}
			}
		}

		$json["data"]  = $report;
		$json["error"] = 0;
		echo json_encode($json);
		die();
	}

	function products_in_pi_order(){
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			$post = json_decode(file_get_contents('php://input'));
		}else{
			$post = (object)$_GET;
		}

		$filters=array();
		$filters["pi_numeral"] = addslashes(trim($post->pi_numeral ?? "")) ;
		$filters["supplier"] = addslashes(trim($post->supplier ?? "")) ;
		$filters["sourcing_office"] = addslashes(trim($post->sourcing_office ?? "")) ;

		$export_xml = isset($_GET['export'])? true : false;

		$presale_status = isset($post->presale_status) && $post->presale_status!="" ? addslashes($post->presale_status) : "";

		$fexproReporte = new FexproReporte($presale_status);
		$r = $fexproReporte->products_in_pi_order($filters);
		
		$products = $this->build_array_data($r);

		if(!$export_xml){
			$json_data = array(
				//"draw" => intval($request['draw']),
				"recordsTotal" => intval($products),
				"recordsFiltered" => intval($products),
				"data" => $products
				);
				echo json_encode($json_data);
		}else{
			$xml = $this->build_xml_sage($products,$filters);

			$pi_numeral = $filters['pi_numeral'] ?? '';
			$pi_numeral = $pi_numeral == '' ? '(no_pi_number)' :$pi_numeral ;

			$upload = isset($_GET["upload"]) ? true:false;

			if(!$upload){
				header("Content-type: text/xml");
				if(isset($post->download) && $post->download == '1'){
					header('Content-Disposition: attachment; filename="CNF_'.$pi_numeral.'.xml"');
				}
				print($xml);
				die();
			}else{
				$name = "CNF_{$pi_numeral}.xml";
				$isUpload = $this->upload_by_ftp($xml,$name);
				if($isUpload){
					$log_sage_orders = get_option("WI_SAGE_ORDER_GENERATOR");
					$log_sage_orders = json_decode($log_sage_orders) ?? [];
					$encontrado = false;
					foreach($log_sage_orders as $i=> $item){
						if($item->pi_numeral == $filters['pi_numeral'] && $item->supplier == $filters['supplier'] && $item->sourcing_office == $filters['sourcing_office']){
							$encontrado = true;
							$log_sage_orders[$i]->status = "EXPORTED";
						}
					}
					if(!$encontrado){
						$log_sage_orders[]=[
							'pi_numeral'=>$filters['pi_numeral'],
							'supplier'=>$filters['supplier'],
							'sourcing_office'=>$filters['sourcing_office'],
							'status' => 'EXPORTED'
						];
					}
					$log_sage_orders = json_encode($log_sage_orders);
					update_option("WI_SAGE_ORDER_GENERATOR",$log_sage_orders);
					$json["error"] = 0;
					$json['status'] = "EXPORTED";
					echo json_encode($json);
				}else{
					$json["error"] = 1;
					$json['status'] = "PENDING ";
					echo json_encode($json);
				}
				
			}
			
		}
		
	}

	function build_array_data($r){
		$fexproReporte = new FexproReporte();
		$imagen_base_url="";
		$imagen_base_url = get_site_url() . "/wp-content/uploads/";
		 
		foreach($r as $var_data){
			$cats=array();
			$main_product_atts = explode("///",$var_data->attributes);
			$pa_date=[];
			$pa_season=[];
			$pa_player= [];
			$main_product_atts = array_map(function($row) use (&$cats,&$pa_date,&$pa_season,&$pa_player){
			
				$f = explode("||",$row);
				if($f[0]=="product_cat"){
				
					$line = explode(":",isset($f[1])?$f[1]:"");
					$cats[]=array("cat"=>$line[0],"parent"=>isset($line[1])?(int)$line[1]:"0","id"=>isset($line[2])?(int)$line[2]:"0");
				}
				if($f[0]=="pa_date"){
					$pa_date[]=$f[1];
				}
				if($f[0]=="pa_season"){
					$pa_season[]=$f[1];
				}
				if($f[0]=="pa_player"){
					$line = explode(":",isset($f[1])?$f[1]:"");
					$pa_player[]=array("title"=>$line[0],"slug"=>isset($line[1])?$line[1]:"");
				}
				return array("key"=>$f[0],"value"=>$f[1]);
			},$main_product_atts);
		
			$main_atts=array_column($main_product_atts,"value","key");
			$main_atts["pa_date"] = implode(",",$pa_date);
			$main_atts["pa_season"] = implode(",",$pa_season);
			$pa_player = array_column($pa_player,"title","slug");
		

			$cats_uni = array_column($cats,"id");
			$category="";
			if(in_array(3259,$cats_uni)){
				$category="STOCK";
				if(in_array(6497,$cats_uni)) $category = "STOCK/CORE";
			}else if(in_array(5931,$cats_uni)){
				$category="PRESALE";
				if(in_array(6497,$cats_uni)) $category = "CORE";
			}else if(in_array(6497,$cats_uni)){
				$category="CORE";
			}
		
			$cats=unflattenArray($cats);
			//$cats=array();

			$metas_variation = explode("///",$var_data->metas);
			$metas_variation = array_map(function($row){
				$f = explode("||",$row);
				return array("key"=>$f[0],"value"=>($f[1]??''));
			},$metas_variation);
			$metas_variation=array_column($metas_variation,"value","key");

			$miniaturas = unserialize($var_data->miniaturas);
			$miniaturas= isset($miniaturas["sizes"])? $miniaturas["sizes"]:"";
			$upload_path = $var_data->image!=""?explode("/",$var_data->image):"";
			if($upload_path!=""){
				array_pop($upload_path);
				$upload_path=implode("/",$upload_path);
			}
			$mini = isset($miniaturas["thumbnail"])?$miniaturas["thumbnail"]["file"]:(isset($miniaturas["medium"])?$miniaturas["medium"]["file"]:"");
			$mini2="";
			if(isset($metas_variation["woo_variation_gallery_images"])){
				$_galle = unserialize($metas_variation["woo_variation_gallery_images"]);
				if(isset($_galle[0]) && is_numeric($_galle[0])){
					//$meta_galle = $wpdb->get_row("SELECT meta_value FROM wp_postmeta pm3 WHERE pm3.meta_key = '_wp_attachment_metadata' AND pm3.post_id IN (".implode(",",$_galle).")");
					$meta_galle = $fexproReporte->get_meta_value_post($_galle);
					if(isset($meta_galle->meta_value)){
						$_galle_images = unserialize($meta_galle->meta_value);
						if(isset($_galle_images["file"])){
							$upload_path_galle = explode("/",$_galle_images["file"]);
							array_pop($upload_path_galle);
							$upload_path_galle=implode("/",$upload_path_galle);
							$mini2 = isset($_galle_images["sizes"]["thumbnail"])?$_galle_images["sizes"]["thumbnail"]["file"]:(isset($_galle_images["sizes"]["medium"])?$_galle_images["sizes"]["medium"]["file"]:"");
							$mini2 = $mini2!=""?(string)$imagen_base_url.$upload_path_galle."/".$mini2:"" ;
						}
					}
				}
			}

			$price=isset($metas_variation["_price"])?$metas_variation["_price"]:0;
			$sizes=array();
			$sizes_values = array();
			$units_per_pack=0;
			for($i=1;$i<=10;$i++){
				if(isset($metas_variation["custom_field".$i]) && $metas_variation["custom_field".$i]!=""){
					$sizes[]=$metas_variation["custom_field".$i];
					$sizes_values[] = (int)$metas_variation["size_box_qty".$i] ?? 0;
					$units_per_pack+=isset($metas_variation["size_box_qty".$i])?(int)$metas_variation["size_box_qty".$i]:0;
				}
			}

			$item=array();
			$item["id"] = $var_data->variationID;
			//$item["main_id"] = $var->post_parent;
			$total_units_purchased = $units_per_pack*$var_data->qty;
			$_drop = isset($metas_variation["drop"])?$metas_variation["drop"]:0;
			$_ordenado_fab = isset($metas_variation["ordenado_fab"])?(int)$metas_variation["ordenado_fab"]:0;

			$status_prod = $_drop=="1"?"Drop":($_ordenado_fab>0?"ordenado fabrica":"pendiente");
			$open_units = $_ordenado_fab - $total_units_purchased;

			$scale_drop  = isset($metas_variation["scale_drop"])?$metas_variation["scale_drop"]  : "";
			$scale_stock = isset($metas_variation["scale_stock"])?$metas_variation["scale_stock"]: "";
			$scale_ok    = isset($metas_variation["scale_ok"])?$metas_variation["scale_ok"]      : "";
			$scale_drop  = $scale_drop!='' ? (strpos($scale_drop,"-")> -1 ? end(explode("-",$scale_drop))  : $scale_drop):null;
			$scale_stock  = $scale_stock !='' ? (count(explode("-",$scale_stock)) == 2 ? explode("-",$scale_stock) :null) : null;
			$scale_ok  = $scale_ok !='' ? explode('-',$scale_ok)[0] : null;
			$suggestion = "";
			if($scale_drop != null && $scale_stock != null && $scale_ok != null){
				$scale_drop = ((int)$scale_drop)<0?((int)$scale_drop)*-1:(int)$scale_drop;

				$suggestion = $total_units_purchased < $scale_drop ? 'DROP' : ($total_units_purchased >= (int)$scale_stock[0] && $total_units_purchased <= (int)$scale_stock[1] ? 'STOCK' : ($total_units_purchased >= $scale_ok ? 'OK':''));
			}

			$item["image"] = $mini!=""?$imagen_base_url.(string)$upload_path."/".$mini:"";
			$item["image2"] = $mini2;
			
			$item['product_title'] =  $var_data->order_item_name;// . " - " . $color ;

			$item['sku'] =  isset($metas_variation["_sku"])?$metas_variation["_sku"]:"";
			$item['qty'] = $var_data->qty;

			$item['division'] =  getDivision(isset($cats[0]["children"][0]["children"][0]["cat"])?$cats[0]["children"][0]["children"][0]["cat"]:(isset($cats[1]["children"][0]["children"][0]["cat"])?$cats[1]["children"][0]["children"][0]["cat"]:""));
			$item['brand'] =  isset($main_atts["pa_brand"])?$main_atts["pa_brand"]:""; 
			$item['gender'] =  isset($main_atts["pa_gender"])?$main_atts["pa_gender"]:"";

			$item["category"] = $category;
			
			$item['group'] = getGroup(isset($cats[0]["children"][0]["children"][0]["cat"])?$cats[0]["children"][0]["children"][0]["cat"]:(isset($cats[1]["children"][0]["children"][0]["cat"])?$cats[1]["children"][0]["children"][0]["cat"]:""));
			$item['product'] = isset($cats[0]["children"][0]["children"][0]["children"][0]["cat"])?$cats[0]["children"][0]["children"][0]["children"][0]["cat"]:( isset($cats[1]["children"][0]["children"][0]["children"][0]["cat"])?$cats[1]["children"][0]["children"][0]["children"][0]["cat"]:"");
			//$item['product']=$cats;

			$item["season"] = isset($main_atts["pa_season"])?$main_atts["pa_season"]:"";// isset($metas_variation["season"])?$metas_variation["season"]:"";
			$item['collection'] =  isset($main_atts["pa_collection"])?$main_atts["pa_collection"]:"";

			$item['date'] =  isset($main_atts["pa_date"])?$main_atts["pa_date"]:"";
			$item['team'] =  isset($metas_variation["product_team"])?$metas_variation["product_team"]:"";
			$item['player'] = isset($metas_variation["attribute_pa_player"]) && isset($pa_player[$metas_variation["attribute_pa_player"]])?$pa_player[$metas_variation["attribute_pa_player"]]:"";
			//$item['player'] = ($metas_variation["attribute_pa_player"] ?? "" ) ;
		
			$item["composition"] = isset($metas_variation["pa_fabric_composition"])?$metas_variation["pa_fabric_composition"]:"";
			$item["logo_application"] = isset($metas_variation["logo_application"])?$metas_variation["logo_application"]:"";
			$item['size_chart'] = implode("-",$sizes);
			$item['price'] =  $price;

			$item['total_units_purchased'] =  $total_units_purchased;

			//$item['units_per_pack'] =$units_per_pack;
			
			$item['subtotal'] =  round($var_data->subtotal,2);
			
			$item["suggestion"]      = $suggestion;
			$item["status_prod"]     = $status_prod;
			$item["ordenado_fab"]    = $_ordenado_fab;
			$item["fob"]             = isset($metas_variation["fob"])?$metas_variation["fob"]  : "";
			$item["supplier"]        = isset($metas_variation["supplier"])?$metas_variation["supplier"]  : "";
			$item["pi"]              = isset($metas_variation["pi_numeral"])?$metas_variation["pi_numeral"]  : "";
			$item["sourcing_office"] = isset($metas_variation["sourcing_office"])?$metas_variation["sourcing_office"]  : "";

			$item["open_units"] = $open_units;
			$item["percent_units"] = ($_ordenado_fab > 0 ? round(($open_units<0?$open_units*-1 : $open_units)/$_ordenado_fab * 100) :0)."%";

			$item["size_s"] = $metas_variation["size_box_qty1"] ?? 0;
			$item["size_m"] = $metas_variation["size_box_qty2"] ?? 0;
			$item["size_l"] = $metas_variation["size_box_qty3"] ?? 0;
			$item["size_xl"] = $metas_variation["size_box_qty4"] ?? 0;

			$item["sizes"] = $sizes;
			$item["sizes_values"] = $sizes_values;
			
			
			
			
			$products[] = $item;
		

		}
		return $products;
	}
	function build_xml_sage($r=[],$filters=[],$path=null){

		$pi_numeral = $filters['pi_numeral'] ?? '';
		$pi_numeral = $pi_numeral == '' ? '(no_pi_number)' :$pi_numeral ;
		$supplier = $filters['supplier'] ?? '';

		$cia = "02";
		$fecha = date("d/m/Y");
		$FechaEntrega  = "15/04/2024";
		$FechaEmbarque = "30/04/2024";
		$FechaLlegada  = "15/06/2024";
		$FechaMuestras = "30/02/2024";

 

		$temporada = '243' ;
		$supplierCode = $this->getSupplierCode($supplier );

		$dom = new DOMDocument('1.0','UTF-8');
		$dom->formatOutput = true;

		$root = $dom->createElement('Sage');
		$dom->appendChild($root);

		$result = $dom->createElement('Confirmacion');
		$root->appendChild($result);

		$result->setAttribute('Cia', $cia);
		$result->setAttribute('NoConfirm', $pi_numeral);
		 
		$result->setAttribute('Suplidor', $supplierCode);
		$result->setAttribute('Temporada', $temporada);
		$result->setAttribute('Fecha', $fecha);
		$result->setAttribute('FechaEntrega', $FechaEntrega);
		$result->setAttribute('FechaEmbarque', $FechaEmbarque);
		$result->setAttribute('FechaLlegada', $FechaLlegada);
		$result->setAttribute('FechaMuestras', $FechaMuestras);
		
		foreach($r as $item){
			
			$sku = $item['sku'];
			$color = explode("-",$sku);
			$color = isset($color[1]) ? $color[1] : '';

			$result1 = $dom->createElement('Linea');
			$result->appendChild($result1);	

			$ordenado_fab = $item['ordenado_fab'];
			$cant_packs = intval($ordenado_fab/24);
			
			$result1->setAttribute('Producto', $sku);
			$result1->setAttribute('Color', $color);

			// $result1->setAttribute('size_S', intval($item['size_s'])*$cant_packs);
			// $result1->setAttribute('size_M', intval($item['size_m'])*$cant_packs);
			// $result1->setAttribute('size_L', intval($item['size_l'])*$cant_packs);
			// $result1->setAttribute('size_XL', intval($item['size_xl'])*$cant_packs);
			 
			for($i=0;$i<count($item['sizes']);$i++){
				$att_name =  str_replace("/",'', 'size_'.$item['sizes'][$i]);
				$result1->setAttribute($att_name, intval($item['sizes_values'][$i])*$cant_packs);
			}
			 

			$result1->setAttribute('Total_Box_Qty', $ordenado_fab);

			$result1->setAttribute('Costo', $item['fob']);
			$result1->setAttribute('FechaEntrega', $FechaEntrega);
			$result1->setAttribute('FechaLlegada', $FechaLlegada);
			$result1->setAttribute('FechaMuestras', $FechaMuestras);
			
			$result1->appendChild($dom->createTextNode(''));
			$result->appendChild($result1);
		}

		

		//$dom->save('CNF_all_factory_export.xml');
		if($path==null){
			$xmlString = $dom->saveXML();
			return $xmlString;
		}else{
			return true;
		}

	}
	function getSupplierCode($string){
		$FactoryArray = array(
			"HONGDOU" => "1",
			"YUANXIANG GARMENTS / AOQUE" => "2",
			"GREAT ASIA" => "3",
			"J & F" => "4",
			"PLANET SOX" => "5",
			"YP-MITCHELL & NESS" => "6",
			"ASI- MITCHELL & NESS" => "7",
			"JIAXING PARISUN IMPORT & EXPOR" => "8",
			"CAYLER AND SON" => "9",
			"DIAMOND ICON" => "10",
			"JRT" => "100",
			"NANTONG LONGDU HOME TEXTILE" => "102",
			"KANGYE" => "103",
			"QUANZHOU SENYA BAGS CO.,LTD" => "104",
			"FORCE FIVE" => "105",
			"OT- CHENGXING FASHION" => "106",
			"QUANZHOU HANHAN GARMENT" => "107",
			"JIMJIAMG WEIDENG IMP & EXP" => "108",
			"UNDESA S.A" => "109",
			"DAPAI (CHINA) CO. LTD" => "11",
			"XIAMEN LEADPACK TRADE & INDUST" => "110",
			"LEADPACKS INDUSTRY & TRADE CO" => "111",
			"CORPORACION INDYKNIT S.A" => "112",
			"GREEN VINA CO.LTD." => "113",
			"P.T SEMARANG GARMENT" => "114",
			"HANES PANAMA INC" => "115",
			"DOUBLE D" => "116",
			"DOUBLE D" => "117",
			"SUNSEA GARMENT CO. LTD" => "118",
			"NANCHANG XIN DONG YANG" => "119",
			"C&D" => "12",
			"KICKER SPORTS" => "120",
			"XIAMEN DIN-STONE IMPORT & EXP" => "121",
			"INDIANA KNITWEAR LLC" => "122",
			"TEGRA GLOBAL LLC" => "123",
			"SNOGEN GREEN CO.,LTD." => "124",
			"HONG SENG KNITTING CO. LTD." => "126",
			"VERTICAL KNITS SA DE CV" => "127",
			"DRAGON CROWD GARMENT INC." => "128",
			"TOPBALL" => "13",
			"GOLD GYM" => "130",
			"NANCHANG FURCHINE  INDUSTRIAL" => "131",
			"HONGKONG BEMY SPORTS LIMITED" => "132",
			"FANATICS" => "133",
			"TIME DEVELOP LIMITED" => "134",
			"KK EXIM" => "135",
			"XIAMEN WANGUOXING TRADE CO.,LT" => "136",
			"JOYSTEP INDUSTRIAL" => "137",
			"ZHEJIANG TIANQI SOCKS" => "138",
			"TANGREN INTERNATIONAL LTD" => "139",
			"WELLPOWER SPORTING" => "14",
			"NANCHANG KUNHAN" => "140",
			"NANCHANG FUZHONG GARMENTS CO" => "141",
			"JINHUA OKADI" => "142",
			"NANCHANG  PENG  XU" => "143",
			"NANTONG GOLDEN BELL" => "145",
			"MM CALCETINES SA DE CV" => "146",
			"FORD" => "147",
			"JINJIANG DALI GARMENT & WEAVIN" => "148",
			"ZHEJIANG KUANGDI INDUSTRY" => "149",
			"ELITE" => "15",
			"DACRIMAR" => "150",
			"SUNNY JET TEXTILES PTE. LTD" => "151",
			"YANGZHOU EVERBRIGHT CAPS MANUF" => "152",
			"JOYLING LIMITED" => "153",
			"ZEN CORPORATION" => "154",
			"QUANZHOU ZI YUN XING GARMENT C" => "155",
			"CLIFTON EXPORT PVT LTD." => "156",
			"EASTMAN EXPORTS" => "157",
			"MILLENNIUM ORIENTAL SOURCING" => "158",
			"DINGJIA  GARMENTS CO.,LTD" => "159",
			"SILVERSTAR" => "16",
			"NANCHANG KANGLONG GARMENT CO.," => "168",
			"PSD" => "169",
			"SOCCER INDIA" => "17",
			"BULLFROG PERU SAC" => "174",
			"OUTERSTUFF, LLC" => "175",
			"HANGZHOU MOSHANGHUA COMPUT" => "176",
			"NINGBO MITSUBANA APPERAL CO.,L" => "177",
			"TAIAN HITEX TEXTILE TECHNOLOGY" => "178",
			"ZAFARI GLOBAL INCORPORATED" => "179",
			"PHILLA (CHINA)" => "18",
			"FOREMOST GARMENTS & ACCESSORIE" => "180",
			"UNITED KNITTING MILLS" => "181",
			"SUMMER INTERNATIONAL HOLDING C" => "182",
			"JIANGXI NEW VISION IMPORT AND" => "184",
			"QUANZHOU JUNLONG GARMENT CO." => "185",
			"YIWU BERTA GARMENT CO.,LTD" => "188",
			"KING SPORTS INC. (MYANMAR)" => "19",
			"ITALMOD S.A." => "190",
			"SOCCER INTERNATIONAL PVT LTD" => "192",
			"PINE SUCCESS (HK) LTD" => "193",
			"ASI GLOBAL LIMITED" => "194",
			"QICAIHU GARMENTS WEAVING CO.," => "196",
			"GL DAMECK LTD" => "198",
			"NANTONG CAPGLOBAL INTERNATIONA" => "199",
			"USL INTERNATIONAL LTD" => "20"
			);
		
		  return isset($FactoryArray[$string]) ?$FactoryArray[$string] : '';
		  
	}

	function upload_by_ftp($xml,$filename){

		file_put_contents("./tmp/".$filename,$xml);
		
		$sftpPort=21;
        //$sftpServer= 'wwwfexpro.eastus2.cloudapp.azure.com';
        $sftpServer= '20.57.63.38';
        
        $sftpUsername = 'ftpfexpro';
        $sftpPassword = 'WP820.1.com';
        $sftpRemoteDir="";
		
		$protocol = $sftpPort==22?"sftp":"ftp";
        $ch = curl_init($protocol.'://' . $sftpServer . ':' . $sftpPort . $sftpRemoteDir . '/' . $filename);

        $fh = fopen("./tmp/".$filename, 'r');

        if ($fh) {
            curl_setopt($ch, CURLOPT_USERPWD, $sftpUsername . ':' . $sftpPassword);
            curl_setopt($ch, CURLOPT_UPLOAD, true);
            curl_setopt($ch,CURLOPT_FTP_CREATE_MISSING_DIRS ,true);
            if($protocol=="sftp"){
                curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
            }
            
            curl_setopt($ch, CURLOPT_INFILE, $fh);
            curl_setopt($ch, CURLOPT_INFILESIZE, filesize("./tmp/".$filename));
            curl_setopt($ch, CURLOPT_VERBOSE, true);

            $verbose = fopen('php://temp', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $verbose);

            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);
			@unlink("./tmp/".$filename);
            if ($response) {
                return true;
            } else {	
                return false;
            }
        }
	}

	function orders_status_get(){
		$fexproReporte = new FexproReporte();
		$list = $fexproReporte->ordersStatusGet();
		$json["error"] = 0;
		$json['data'] = $list;
		echo json_encode($json);
	}

	function download_xml(){

	}
	


}
$method = $_GET["action"] ?? "";
$ajaxReport = new AjaxReport();
if(method_exists($ajaxReport,$method)){
	call_user_func( array( $ajaxReport ,$method ) );
}