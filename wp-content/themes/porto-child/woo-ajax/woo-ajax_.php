<?php 

function get_ajax_posts() {


    $per_page = 8;

    $slug_category = isset($_GET["cat"]) ? trim($_GET["cat"]):"";
    $brand = isset($_GET["brand"]) ? trim($_GET["brand"]):"";
    $pag = isset($_GET["pag"])?(int)$_GET["pag"]:1;

    $get_totals = isset($_GET["get_totals"])?true:false;

    $color = isset($_GET["pa_color"]) ? trim($_GET["pa_color"]):"";
    $collection = isset($_GET["pa_collection"]) ? trim($_GET["pa_collection"]):"";
    $country = isset($_GET["pa_country-of-origin"]) ? trim($_GET["pa_country-of-origin"]):"";
    $date = isset($_GET["pa_date"]) ? trim($_GET["pa_date"]):"";
    $gender = isset($_GET["pa_gender"]) ? trim($_GET["pa_gender"]):"";

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        //'paged' => $pag,
        'post_status' => array('publish'),
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $slug_category
            )
        )
    );
    if ($brand!="") {
        $filter_brand = explode(',', $brand);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_brand',
            'field'           => 'slug',
            'terms'           =>  $filter_brand,
            'operator'        => 'IN',
        );
    }

    
    if ($color!="") {
        $filter_color = explode(',', $color);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_color',
            'field'           => 'slug',
            'terms'           =>  $filter_color,
            'operator'        => 'IN',
        );
    }
    if ($collection!="") {
        $filter_collection = explode(',', $collection);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_collection',
            'field'           => 'slug',
            'terms'           =>  $filter_collection,
            'operator'        => 'IN',
        );
    }
    if ($country!="") {
        $country = explode(',', $country);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_country-of-origin',
            'field'           => 'slug',
            'terms'           =>  $country,
            'operator'        => 'IN',
        );
    }

    if ($date!="") {
        $filter_date = explode(',', $date);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_date',
            'field'           => 'slug',
            'terms'           =>  $filter_date,
            'operator'        => 'IN',
        );
    }
    if ($gender!="") {
        $filter_gender = explode(',', $gender);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_gender',
            'field'           => 'slug',
            'terms'           =>  $filter_gender,
            'operator'        => 'IN',
        );
    }

    $result=array();
    
    $products=[];

    $u =  wp_get_current_user();
    $rol = isset($u->roles[0])?$u->roles[0]:array();

    if($get_totals){
        $total = new WP_Query($args);
        $total = isset($total->post_count) && $total->post_count>0 ?  $total->post_count :0;
        $result["total"] = $total;
        $result["pages"] = $total>0? ceil($total/$per_page):0;
        

    }
        $args["posts_per_page"] = $per_page;
        $args["paged"] = $pag;
        $loop = new WP_Query($args);
        if ($loop->have_posts()) {
            foreach ($loop->posts as $key => $value) {
                //$main_product = wc_get_product($value->ID);
                //$brand = $main_product->get_attribute('pa_brand');

                $meta = get_post_meta($value->ID);
                $price = isset($meta["_price"][0])?array($meta["_price"][0]):array(0);
    
                if($rol!="administrator"){
                    $price_ = role_price_get_by_parent_id($value->ID,$rol);
                    $price = count($price_)>0?$price_:$price;
                }

                $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($value->ID), 'thumbnail');
                $products[] = [
                    'id' => $value->ID,
                    'titulo' => $value->post_title,
                    'slug' => $value->post_name,
                    'img' => $thumb,
                    'link' => get_site_url() . '/product/' . $value->post_name,
                    'terms' => get_the_terms($value->ID, 'product_cat'),
                    'price' => $price
                    //'meta' => get_post_meta($value->ID)
                ];
            }
        }
        $result["products"] = $products;

    
    
   
    

    print_r(json_encode($result));


    exit; // exit ajax call(or it will return useless information to the response)
}

// Fire AJAX action for both logged in and non-logged in users
add_action('wp_ajax_get_ajax_posts', 'get_ajax_posts');
add_action('wp_ajax_nopriv_get_ajax_posts', 'get_ajax_posts');



function export_catalog() {

  
    set_time_limit(120);
    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/
    global $wpdb;

    $slug_category = isset($_GET["cat"]) ? trim($_GET["cat"])                                  : "";
    $brand         = isset($_GET["brand"]) ? trim($_GET["brand"])                              : "";
    $pag           = isset($_GET["pag"])?(int)$_GET["pag"]                                     : 1;

    $get_totals    = isset($_GET["get_totals"])?true                                           : false;

    $color         = isset($_GET["pa_color"]) ? trim($_GET["pa_color"])                        : "";
    $collection    = isset($_GET["pa_collection"]) ? trim($_GET["pa_collection"])              : "";
    $country       = isset($_GET["pa_country-of-origin"]) ? trim($_GET["pa_country-of-origin"]): "";
    $date          = isset($_GET["pa_date"]) ? trim($_GET["pa_date"])                          : "";
    $gender        = isset($_GET["pa_gender"]) ? trim($_GET["pa_gender"]):"";

    $products_id = isset($_GET["products"])?$_GET["products"]:"";

    $post_json = json_decode(file_get_contents('php://input'));
    if(isset($post_json->products)){
        $products_id =$post_json->products;
    }

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => array('publish','private'),
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $slug_category
            )
        )
    );
    
    if ($brand!="") {
        $filter_brand = explode(',', $brand);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_brand',
            'field'           => 'slug',
            'terms'           =>  $filter_brand,
            'operator'        => 'IN',
        );
    }

    
    if ($color!="") {
        $filter_color = explode(',', $color);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_color',
            'field'           => 'slug',
            'terms'           =>  $filter_color,
            'operator'        => 'IN',
        );
    }
    if ($collection!="") {
        $filter_collection = explode(',', $collection);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_collection',
            'field'           => 'slug',
            'terms'           =>  $filter_collection,
            'operator'        => 'IN',
        );
    }
    if ($country!="") {
        $country = explode(',', $country);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_country-of-origin',
            'field'           => 'slug',
            'terms'           =>  $country,
            'operator'        => 'IN',
        );
    }

    if ($date!="") {
        $filter_date = explode(',', $date);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_date',
            'field'           => 'slug',
            'terms'           =>  $filter_date,
            'operator'        => 'IN',
        );
    }
    if ($gender!="") {
        $filter_gender = explode(',', $gender);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_gender',
            'field'           => 'slug',
            'terms'           =>  $filter_gender,
            'operator'        => 'IN',
        );
    }

   

    $post_result_filters=array();
    
    
    if ($brand!="") {
        $loop = new WP_Query($args);

        if ($loop->have_posts()) {
            $post_result_filters= array_merge($post_result_filters, $loop->posts);
        }
    }
    
    
    if($products_id!=""){
      
        $filter_products = explode(',',$products_id);
        $args2=array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => array('publish','private'),
            'post__in' => $filter_products
        );
        $loop2 = new WP_Query($args2);
 
        if ($loop2->have_posts()) {
            $post_result_filters= array_merge($post_result_filters, $loop2->posts);
        }

    }

    
   
    $result=array();
    
    $products=[];
    $base_url=get_site_url()."/wp-content/uploads/";
    if (count($post_result_filters)>0) {


        foreach ($post_result_filters as $key => $value) {

            $sql_variaciones="SELECT a.*, pm1.meta_value thumbnail_id, pm2.meta_value image ,
            (SELECT group_concat(concat(b.meta_key,'||',b.meta_value) SEPARATOR '///')  FROM wp_postmeta b WHERE b.post_id=a.ID ) metas
            FROM wp_posts a 
            LEFT JOIN wp_postmeta pm1 ON (pm1.post_id=a.ID AND pm1.meta_value IS NOT NULL AND pm1.meta_key='_thumbnail_id')
            LEFT JOIN wp_postmeta pm2 ON (pm2.post_id=pm1.meta_value AND pm2.meta_key='_wp_attached_file')
            WHERE a.post_status IN ('publish','private')
            AND a.post_parent=".$value->ID;
            $variaciones = $wpdb->get_results($sql_variaciones);

            if(is_array($variaciones) && count($variaciones)>0){

                //$main_product = wc_get_product( $value->ID );
                $sql_main_atts="SELECT tt.taxonomy,tt.term_id,t.name,t.slug,tt.parent
                FROM wp_term_relationships tr
                INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id
                INNER JOIN wp_terms t ON tt.term_id=t.term_id
                WHERE tr.object_id=".$value->ID ;

                $main_product_atts=$wpdb->get_results($sql_main_atts, ARRAY_A);
                $cats=array();
                $main_atts=array();
                if(is_array($main_product_atts)){
                    foreach ($main_product_atts as $item) {
                       if($item["taxonomy"] == "product_cat"){
                        $cats[$item["term_id"]] = $item;
                       }
                       if( substr($item["taxonomy"],0,3) == "pa_" ){
                           $main_atts[$item["taxonomy"]] = $item["name"];
                       }
                    }
                }
                array_multisort(
                    array_column($cats, 'parent'),
                    array_column($cats, 'term_id'),
                    $cats
                );
               // $categories = $main_product->get_categories();//get_the_terms($value->ID, 'product_cat');
                $cats=array_reverse($cats);
              
                $category = isset($cats[1])?$cats[1]["name"]:"";
                $sub_category = isset($cats[0])?$cats[0]["name"]:"";
                foreach ($variaciones as $key => $var) {
                 
                    $metas_variation = explode("///",$var->metas);
                    $metas_variation = array_map(function($row){
                        $f = explode("||",$row);
                        
                        return array("key"=>$f[0],"value"=>$f[1]);
                    },$metas_variation);
                    $metas_variation=array_column($metas_variation,"value","key");
                  
                    $color = preg_replace('/[0-9]+/', '',str_replace("Color: ","",$var->post_excerpt));

                    $variation_id = $var->ID;
                    $tmp=explode('.', $var->image);
                    $extension = end($tmp);
                    $image = (str_replace(".".$extension,"-150x150.".$extension,$var->image));//$base_url
                    //$_product =  wc_get_product( $variation_id);

                    $item=array();
                    //$item["id"] = $value->ID;
                    $item["Image"] = $image;

                    $item['Product SKU'] =  isset($metas_variation["_sku"])?$metas_variation["_sku"]:"";

                    $item['Product Title'] =  $var->post_title;// . " - " . $color ;

                    $item['Color'] =  $color ;

                    $item['Season'] =  isset($main_atts["pa_season"])?$main_atts["pa_season"]:"";
                    
                    $item['Price'] =  isset($metas_variation["_price"])?$metas_variation["_price"]:"";

                    $item['Division'] =  "";

                    $item['Brand'] =  isset($main_atts["pa_brand"])?$main_atts["pa_brand"]:""; 

                    $item['Departament'] =  "";

                    $item['Category'] =  $category;
                    $item['Subcategory'] =  $sub_category;

                    $item['totalprice'] = 0;
                    $item['Stok'] =  isset($metas_variation["_stock"]) ? $metas_variation["_stock"]:0;

                    $item['Qty'] =  0;

                    $item['units_per_pack'] =  isset($metas_variation["size_box_qty1"]) ? $metas_variation["size_box_qty1"]:"";

                    $item['meta_custom_1'] =  isset($metas_variation["custom_field1"]) ? $metas_variation["custom_field1"]:"";
                    $item['meta_custom_2'] =  isset($metas_variation["custom_field2"]) ? $metas_variation["custom_field2"]:"";
                    $item['meta_custom_3'] =  isset($metas_variation["custom_field3"]) ? $metas_variation["custom_field3"]:"";
                    $item['meta_custom_4'] =  isset($metas_variation["custom_field4"]) ? $metas_variation["custom_field4"]:"";
                    $item['meta_custom_5'] =  isset($metas_variation["custom_field5"]) ? $metas_variation["custom_field5"]:"";
                    $item['meta_custom_6'] =  isset($metas_variation["custom_field6"]) ? $metas_variation["custom_field6"]:"";
                    $item['meta_custom_7'] =  isset($metas_variation["custom_field7"]) ? $metas_variation["custom_field7"]:"";
                    $item['meta_custom_8'] =  isset($metas_variation["custom_field8"]) ? $metas_variation["custom_field8"]:"";
                    $item['meta_custom_9'] =  isset($metas_variation["custom_field9"]) ? $metas_variation["custom_field9"]:"";
                    $item['meta_custom_10'] =  isset($metas_variation["custom_field10"]) ? $metas_variation["custom_field10"]:"";


                    $products[] = $item;
                }
            }

         
        }
    }
    $result["products"] = $products;

    
    build_excel_catalog( $products);
   
    

    print_r(json_encode($result));


    exit; // exit ajax call(or it will return useless information to the response)
}


// Fire AJAX action for both logged in and non-logged in users
add_action('wp_ajax_export_catalog', 'export_catalog');
add_action('wp_ajax_nopriv_export_catalog', 'export_catalog');


function build_excel_catalog($data){
   
    $path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';
	

	$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';


	define('SITEPATH', str_replace('\\', '/', $path1));

    $dataMainHeader = array('Image','SKU', 'Name', 'Color', 'Season', 'Regular Price', 'Division', 'Licence', 'Department','Category','Subcategory','Total Price (USD)','Stock','Qty','Units per pack', 'Meta: custom_field1','Meta: custom_field2','Meta: custom_field3','Meta: custom_field4','Meta: custom_field5','Meta: custom_field6','Meta: custom_field7','Meta: custom_field8','Meta: custom_field9','Meta: custom_field10');

    require_once SITEPATH . 'PHPExcel/Classes/PHPExcel.php';

		$objPHPExcel = new PHPExcel(); 

		$objPHPExcel->getProperties()
			->setCreator("Fexpro")
			->setLastModifiedBy("Fexpro")
			->setTitle("Products")
			->setSubject("Products");

		// Set the active Excel worksheet to sheet 0

		$objPHPExcel->setActiveSheetIndex(0); 


      $objPHPExcel->getActiveSheet()->setAutoFilter('A1:O1');
      $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray(
                      array(
                          'fill' => array(
                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                              'color' => array('rgb' => 'CCCCCC')
                          )
                      )
                  );

		// Initialise the Excel row number

		 

		// Build headers
        $j=0;
		foreach( $dataMainHeader as $i => $row )
		{

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(($i),1,$row);
            $objPHPExcel->getActiveSheet()
            ->getColumnDimensionByColumn($i+1)
            ->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(($i),1)->getFont()->setBold(true);
           

		}  

        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(14);

        $objPHPExcel->getActiveSheet()->getColumnDimension("N")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("N")->setWidth(6);


		// Build cells

        $rowCount = 0;

		while( $rowCount < count($data) ){ 

			$cell = $rowCount+2;

            $column=0;
			foreach( $data[$rowCount] as $key => $value ) {
                $columnChar=PHPExcel_Cell::stringFromColumnIndex((string)$column);
				//$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 

				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$cell)->applyFromArray(
					array(
						'borders' => array(
						'allborders' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('rgb' => '000000')
						    )
						)
					)
				);

				$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(75);
				switch ($key) {
					case 'Image':
                        $file = $upload_path.$value;
						if (file_exists($file)) {

							$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing->setName('Product image');
							$objDrawing->setDescription('Product image');


							//Path to signature .jpg file

						    $signature = $file;

							$objDrawing->setPath($signature);

							$objDrawing->setOffsetX(5);                     //setOffsetX works properly

							$objDrawing->setOffsetY(10);                     //setOffsetY works properly

							$objDrawing->setCoordinates($columnChar.$cell);             //set image to cell 

							$objDrawing->setHeight(80);                     //signature height  

                            //$objDrawing->getHyperlink()->setUrl('http://www.google.com');

							$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 
                          
                            //$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($column,$cell)->getHyperlink()->setUrl('http://www.google.com');
						}
						break;
					default:

						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column,$cell, $value); 

						break;

				}
            if($columnChar=="L"){
               $objPHPExcel->getActiveSheet()->setCellValue($columnChar.$cell, "=IF(N$cell=\"\",0,N$cell)*F$cell*O$cell"); 
               $objPHPExcel->getActiveSheet()->getStyle($columnChar.$cell)->getNumberFormat()->setFormatCode('0.00'); 

               $objPHPExcel->getActiveSheet()->getStyle($columnChar.$cell)->applyFromArray(
                      array(
                          'fill' => array(
                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                              'color' => array('rgb' => 'DDEBF7')
                          )
                      )
                  );
            }
            if($columnChar=="N"){
           

               $objPHPExcel->getActiveSheet()->getStyle($columnChar.$cell)->applyFromArray(
                      array(
                          'fill' => array(
                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                              'color' => array('rgb' => 'ED7D31')
                          )
                      )
                  );
            }
	
            $column++;
			}     
			$rowCount++; 
		}  

        @ob_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export1.xlsx"'); 
        header('Cache-Control: max-age=0');

	  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->setPreCalculateFormulas(true);
        
 
        $objWriter->save('php://output'); 
        die();
	  //ob_start();

		//ob_end_clean();

  



}

function import_order(){
    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/
    if(isset($_FILES["file"]) && $_FILES["file"]["error"]==0){
        $excel = ExcelGet($_FILES["file"]["tmp_name"],0);
        if(!isset($excel[1]["B"]) || $excel[1]["B"]!="SKU" || !isset($excel[1]["O"]) ||  $excel[1]["O"]!="Qty"){
            $json["error"] = 1;
            $json["message"] = "Invalid file";
            echo json_encode($json);
            die();
        }
       
        unset($excel[1]);
        if(count($excel)>0){
            global $woocommerce;
            foreach($excel as $row){
                $sku = addslashes(trim($row["B"]));
                $qty = (int)$row["O"];
                $units_pack = $row["P"];
                $is_future = $row["G"]!="IMMEDIATE"?true:false;
                $product = get_product_by_sku($sku);

                

                
                if(isset($product->ID)){

                    $cats = (array)get_the_terms( $product->post_parent , 'product_cat' );
                    $cats =array_column($cats,"slug");
                    $is_presale = in_array("presale",$cats)?true:false;

                    $data_extra=array();
                    if($is_future){
                        $data_extra["type_stock"] = "future";
                    }
                    if($is_presale){
                        $data_extra["type_stock"] = "future";
                        $data_extra["is_presale"] = 1;
                    }
                    $woocommerce->cart->add_to_cart($product->post_parent,$qty,$product->ID,null,$data_extra);
                }
                
            }
            $json["error"]=0;
        }
    }else{
        $json["error"] = 1;
        $json["message"] = "Invalid file";
    }
    
    echo json_encode($json);
    die();
}
function get_product_by_sku($sku){
    global $wpdb;
    $product = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM wp_posts p WHERE p.ID = (SELECT post_id FROM wp_postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1)", $sku ) );
    return $product;
}
function ExcelGet($fileName,$sheet=-1,$index=true){
    $path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';
    define('SITEPATH', str_replace('\\', '/', $path1));

    require_once SITEPATH . 'PHPExcel/Classes/PHPExcel.php';

	//$objPHPExcel = new PHPExcel(); 

	//PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
	PHPExcel_Settings::setZipClass(PHPExcel_Settings::ZIPARCHIVE);
	$excelReader = PHPExcel_IOFactory::createReaderForFile($fileName);

	$excelReader->setReadDataOnly();
	
	$excelReader->setLoadAllSheets();

	$excelObj = $excelReader->load($fileName);
	$excelObj->getActiveSheet()->toArray(null, true,true,true);

	$worksheetNames = $excelObj->getSheetNames($fileName);
	$return = array();
	foreach($worksheetNames as $key => $sheetName){
                    //set the current active worksheet by name
		$excelObj->setActiveSheetIndexByName($sheetName);
                    //create an assoc array with the sheet name as key and the sheet contents array as value
		$return[$key] = $excelObj->getActiveSheet()->toArray(null, true,true,$index);
	}
	if($sheet!=-1){
		return $return[$sheet];
	}
	else
		return $return;
}
add_action('wp_ajax_import_order', 'import_order');
add_action('wp_ajax_nopriv_import_order', 'import_order');


function get_ajax_products_stock() {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    global $wpdb;
   
    $per_page = (int)$_GET["per_page"]??24;
    $per_page = !in_array($per_page,[24,48,96])?24:$per_page;

    $slug_category = isset($_GET["cat"]) ? preg_replace("/[^a-zA-Z0-9\-,_\+]+/", "",trim($_GET["cat"]))  :"";
    $brand = isset($_GET["pa_brand"]) ? trim($_GET["pa_brand"]):"";
    $pag = isset($_GET["pag"])?(int)$_GET["pag"]:1;

    $get_totals = isset($_GET["get_totals"])?true:false;

    $color = isset($_GET["pa_color"]) ? trim($_GET["pa_color"]):"";
    $collection = isset($_GET["pa_collection"]) ? trim($_GET["pa_collection"]):"";
    $country = isset($_GET["pa_country-of-origin"]) ? trim($_GET["pa_country-of-origin"]):"";
    $date = isset($_GET["pa_date"]) ? trim($_GET["pa_date"]):"";
    $gender = isset($_GET["pa_gender"]) ? trim($_GET["pa_gender"]):"";

    $user_collections = get_user_collections();

    $delivery_date = isset($_GET["meta_delivery_date"]) ? preg_replace("/[^a-zA-Z0-9\-,_\+]+/", "",trim($_GET["meta_delivery_date"])):"";
    if(in_array($delivery_date,["q1","q2","q3","q4"])){
        $date=$delivery_date;
        $delivery_date="";
    }

    $category2 = isset($_GET["filter_category2"]) ? preg_replace("/[^a-zA-Z0-9\-,_\+]+/", "",trim($_GET["filter_category2"])):"";

    $division = isset($_GET["cat_division"])?  preg_replace("/[^a-zA-Z0-9\-,_\+]+/", "",trim($_GET["cat_division"])) :"";
    if(count($user_collections) > 0){
        $_divis = array_unique(array_column($user_collections,"division"));
        if($division!=""){
            if(!in_array($division,$_divis)){
                $division="none404";
            }
        }else{
            $division=implode(",",$_divis);
        }
       
    }
    
    $apparel = isset($_GET["product_type_apparel"]) ? preg_replace("/[^a-zA-Z0-9\-,_\+ ]+/", "",trim($_GET["product_type_apparel"])):"";
    $accesories = isset($_GET["product_type_accesories"]) ? preg_replace("/[^a-zA-Z0-9\-,_\+ ]+/", "",trim($_GET["product_type_accesories"])):"";

    $orderby = isset($_GET["sort"]) ? trim($_GET["sort"]) : "default";

    $only_basics = isset($_GET["pa_only_basics"]) && $_GET["pa_only_basics"]==1?true:false;

    $only_pop = isset($_GET["only_pop"]) && $_GET["only_pop"]==1?true:false;

    $categories_pop = isset($_GET["cat_pop"]) ? preg_replace("/[^a-zA-Z0-9\-,_\+]+/", "",trim($_GET["cat_pop"])):"";
    $sold = isset($_GET["pa_sold"]) ? preg_replace("/[^a-zA-Z0-9\-,_\+]+/", "",trim($_GET["pa_sold"])):"";

    $team="";

    $filter_stock_min = isset($_GET["filter_stock_min"]) ? (int)$_GET["filter_stock_min"] : 0;
    $filter_stock_max = isset($_GET["filter_stock_max"]) ? (int)$_GET["filter_stock_max"] : 0;
    global $is_presale;
    $is_presale=false;

    if($category2!=""){
        $root_category=3259;
        $cat_presale_id=5931;
        $category = get_term_by( 'slug', $slug_category, 'product_cat' );

        $ancestors = get_ancestors( $category->term_id, 'product_cat' );
        if(in_array($cat_presale_id,$ancestors) || $category->term_id == $cat_presale_id){
			$is_presale=true;
			$root_category=$cat_presale_id;
		}
        $cat_current = $category;
        $level=1;
        if($category->term_id!=$root_category){
            for($i=1;$i<=4;$i++){
                $level++;
                if($category->parent==$root_category){
                    break;
                }else{
                    $category = get_term($category->parent, 'product_cat' );
                }
                
            }
        }
      
        $_cats = category_search($level,$cat_current->term_id,$category2);
     
        if(is_array($_cats) && count($_cats)>0){
            $slug_category=implode(",",$_cats);
        }
    }
    if($categories_pop!=""){
        $cats_pop=[7004,7017,3717,1920,5232,5769,5850,5792];
        $sql="SELECT t.slug from wp_terms t INNER JOIN wp_term_taxonomy tt ON t.term_id=tt.term_id WHERE tt.parent IN (".implode(",",$cats_pop).") 
             AND t.slug REGEXP '".(str_replace(",","|",$categories_pop))."' ";
        $slug_cats_pop =$wpdb->get_results($sql,ARRAY_A);
        $slug_cats_pop=array_column($slug_cats_pop,"slug");
        //$cats_pop=["pop-womens-apparel-womens","pop-mens-apparel"];
       // $cats_pop=["pop"];
         $slug_category=implode(",",$slug_cats_pop);
    }
    $slug_division="";
    if($division !=""){
        $sql="SELECT  t3.* 
        FROM  wp_terms t 
        INNER JOIN wp_term_taxonomy tt2 ON tt2.parent=t.term_id
        INNER JOIN wp_terms t2 ON tt2.term_taxonomy_id=t2.term_id
        INNER JOIN wp_term_taxonomy tt3 ON tt3.parent=t2.term_id
        INNER JOIN wp_terms t3 ON tt3.term_taxonomy_id=t3.term_id
        WHERE t.slug='".$slug_category."' AND t3.slug REGEXP '".(str_replace(",","|",$division))."' ";
        //echo $sql;
        $slug_division =$wpdb->get_results($sql,ARRAY_A);
        $slug_division =array_column($slug_division,"slug");
         
        $slug_division =implode(",",$slug_division);
        if($slug_division==""){
            $slug_division="none404";
        }
    }
    $slug_apparel="";
    if($apparel !=""){
        $sql="SELECT  t4.* 
        FROM  wp_terms t 
        INNER JOIN wp_term_taxonomy tt2 ON tt2.parent=t.term_id
        INNER JOIN wp_terms t2 ON tt2.term_taxonomy_id=t2.term_id
        INNER JOIN wp_term_taxonomy tt3 ON tt3.parent=t2.term_id
        INNER JOIN wp_terms t3 ON tt3.term_taxonomy_id=t3.term_id
        INNER JOIN wp_term_taxonomy tt4 ON tt4.parent=t3.term_id
        INNER JOIN wp_terms t4 ON tt4.term_taxonomy_id=t4.term_id
        WHERE t.slug='".$slug_category."' 
		AND t3.slug LIKE '%apparel%' AND ( t4.slug REGEXP '".str_replace(",","|",$apparel)."' )";
        $slug_apparel =$wpdb->get_results($sql,ARRAY_A);
        $slug_apparel =array_column($slug_apparel,"slug");
         
        $slug_apparel =implode(",",$slug_apparel);
        if($slug_apparel==""){
            $slug_apparel="none404";
        }
       
    }

    $slug_accesories="";
    if($accesories !=""){
        $sql="SELECT  t4.* 
        FROM  wp_terms t 
        INNER JOIN wp_term_taxonomy tt2 ON tt2.parent=t.term_id
        INNER JOIN wp_terms t2 ON tt2.term_taxonomy_id=t2.term_id
        INNER JOIN wp_term_taxonomy tt3 ON tt3.parent=t2.term_id
        INNER JOIN wp_terms t3 ON tt3.term_taxonomy_id=t3.term_id
        INNER JOIN wp_term_taxonomy tt4 ON tt4.parent=t3.term_id
        INNER JOIN wp_terms t4 ON tt4.term_taxonomy_id=t4.term_id
        WHERE t.slug='".$slug_category."' 
		AND t3.slug LIKE '%accesories%' AND ( t4.slug REGEXP '".str_replace(",","|",$accesories)."' )";
        $slug_accesories =$wpdb->get_results($sql,ARRAY_A);
        $slug_accesories =array_column($slug_accesories,"slug");
         
        $slug_accesories =implode(",",$slug_accesories);
        if($slug_accesories==""){
            $slug_accesories="none404";
        }
       // echo $slug_accesories;
    }


    if(isset($_GET["f_bc_brand"])){
        $brand=preg_replace("/[^a-zA-Z0-9\-,_\+]+/", "",trim($_GET["f_bc_brand"]));
    }
    if(count($user_collections) > 0){
        $_brand = array_column($user_collections,"brand");
        if($brand!=""){
            $brand_arr=explode(",",$brand);
            $matches=array_intersect($brand_arr,$_brand);

            if(count($matches)==0){
                $brand="none404";
            }else{
                $brand = implode(",",$matches);
            }
        }else{
            $brand=implode(",",$_brand);
        }
        //echo $division;
    }
    if(isset($_GET["f_bc_collection"])){
        $collection=preg_replace("/[^a-zA-Z0-9\-,_\+]+/", "",trim($_GET["f_bc_collection"]));
    }
    if(count($user_collections) > 0){
        $_collection = array_unique(array_column($user_collections,"collection"));

        if($collection!=""){
            $collection_arr=explode(",",$collection);
            $matches=array_intersect($collection_arr,$_collection);

            if(count($matches)==0){
                $collection="none404";
            }else{
                $collection = implode(",",$matches);
            }
        }else{
            $collection=implode(",",$_collection);
        }
        //echo $division;
    }
    if(isset($_GET["f_bc_team"])){
        $team=preg_replace("/[^a-zA-Z0-9\-,_\+ ]+/", "",trim($_GET["f_bc_team"]));
    }
   

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,//$per_page, //-1,
        //'paged' => $pag,
        'post_status' => array('publish'),
        'tax_query' => array('relation' => 'AND'),
        'meta_query' => array()
    );

    $categories = explode(",",$slug_category);
    if($slug_category!="" && count($categories)>0){

        $args['tax_query'][] = array(
            'taxonomy'        => 'product_cat',
            'field'           => 'slug',
            'terms'           =>  $categories,
            'operator'        => 'IN'
        );
    }
    if($only_basics){
        $args['tax_query'][] = array(
            'taxonomy'        => 'product_cat',
            'field'           => 'slug',
            'terms'           => array("basics"),
            'operator'        => 'IN'
        );
    }
    if ($slug_division!="") {
        $filter_division = explode(',', $slug_division);
        $args['tax_query'][] = array(
            'taxonomy'        => 'product_cat',
            'field'           => 'slug',
            'terms'           =>  $filter_division,
            'operator'        => 'IN',
        );
    }
   
    $filter_apparel_accesories = [];
    if ($slug_apparel!="") {
        $filter_apparel = explode(',', $slug_apparel);
        $filter_apparel_accesories = array_merge($filter_apparel_accesories,$filter_apparel);
    }

    if ($slug_accesories!="") {
        $filter_accesories = explode(',', $slug_accesories);
        $filter_apparel_accesories = array_merge($filter_apparel_accesories,$filter_accesories);
       
    }
    if(count($filter_apparel_accesories) > 0 ){
        $args['tax_query'][] = array(
            'taxonomy'        => 'product_cat',
            'field'           => 'slug',
            'terms'           =>  $filter_apparel_accesories,
            'operator'        => 'IN',
        );
    }
    

    if ($brand!="") {
        $filter_brand = explode(',', $brand);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_brand',
            'field'           => 'slug',
            'terms'           =>  $filter_brand,
            'operator'        => 'IN',
        );
    }

    
    if ($color!="") {
        $filter_color = explode(',', $color);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_color',
            'field'           => 'slug',
            'terms'           =>  $filter_color,
            'operator'        => 'IN',
        );
    }
    if ($collection!="") {
        $filter_collection = explode(',', $collection);
       
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_collection',
            'field'           => 'slug',
            'terms'           =>  $filter_collection,
            'operator'        => 'IN',
        );
    }
    if ($country!="") {
        $country = explode(',', $country);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_country-of-origin',
            'field'           => 'slug',
            'terms'           =>  $country,
            'operator'        => 'IN',
        );
    }

    if ($date!="") {
        $filter_date = explode(',', $date);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_date',
            'field'           => 'slug',
            'terms'           =>  $filter_date,
            'operator'        => 'IN',
        );
    }
    if ($gender!="") {
        $filter_gender = explode(',', $gender);
        $args['tax_query'][] = array(
            'taxonomy'        => 'pa_gender',
            'field'           => 'slug',
            'terms'           =>  $filter_gender,
            'operator'        => 'IN',
        );
    }

    $filter_variation=array();

    if($delivery_date!=""){
        $filter_variation["delivery_dates"] = explode(",",$delivery_date);
    }
    if ($color!="") {
        $filter_variation["pa_color"] = explode(',', $color);
    }
    if($filter_stock_min!=0){
        $filter_variation["filter_stock_min"] = $filter_stock_min;
    }
    if($filter_stock_max!=0){
        $filter_variation["filter_stock_max"] = $filter_stock_max;
    }
    if($sold!=""){
        $filter_variation["pa_sold"] = explode(',', $sold);
    }
    if($team!=""){
        $filter_variation["team"] = explode(",",$team);
    }

    $u =  wp_get_current_user();
    $rol = isset($u->roles[0])?$u->roles[0]:array();
    
    $role_selected_data  = (array) get_option('afpvu_user_role_visibility');
   
    if(isset($role_selected_data[$rol]) && isset( $role_selected_data[$rol]["afpvu_enable_role"]) && $role_selected_data[$rol]["afpvu_enable_role"] =="yes" && is_array($role_selected_data[$rol]["afpvu_applied_products_role"]) && count($role_selected_data[$rol]["afpvu_applied_products_role"])>0 ){
        $args["post__not_in"] = $role_selected_data[$rol]["afpvu_applied_products_role"];
    }
    $result=array();
    $products=[];
    
    if($get_totals){
        $total = new WP_Query($args);
    
        $total=filter_variations($total,$filter_variation);
        $all_ids = isset($total->posts)?array_column($total->posts,"ID"):[];
        $total = isset($total->post_count) && $total->post_count>0 ?  $total->post_count :0;
        $result["total"] = $total;
        $result["pages"] = $total>0? ceil($total/$per_page):0;
        $result["all_ids"] = $all_ids;
        unset($all_ids);

    }
    $extra_args=array();
    if($orderby!="default" && in_array($orderby,array("price_asc","price_desc"))){
        $extra_args["meta_key"] = "_price";
        $extra_args["orderby"] = "meta_value_num";
        $extra_args["order"] = $orderby=="price_asc"?"ASC":"DESC";
    }
    if(in_array($orderby,array("stock_asc","stock_desc"))){
        $extra_args["orderbystock"] = "meta_value_num";
        $extra_args["order"] = $orderby=="stock_asc"?"ASC":"DESC";
    }
    if($orderby!="default" && in_array($orderby,array("alphabethic_asc","alphabethic_desc"))){
        $extra_args["meta_key"] = "alphabethic";
        $extra_args["order"] = $orderby=="alphabethic_asc"?"ASC":"DESC";
    }
    if($orderby!="default" && in_array($orderby,array("newest_asc","newest_desc"))){
        $extra_args["meta_key"] = "newest";
        $extra_args["order"] = $orderby=="newest_asc"?"ASC":"DESC";
    }
    $extra_args["posts_per_page"] = $per_page;
    $extra_args["paged"] = $pag;


    $loop = new WP_Query($args);
    $loop=filter_variations($loop,$filter_variation,$extra_args);



    
    

    if ($loop!=null && $loop->have_posts()) {
        foreach ($loop->posts as $key => $value) {

            $meta = get_post_meta($value->ID);
       

            $collections =   get_the_terms( $value->ID,"pa_collection" );
            $collections =  is_array($collections)?array_column($collections,"name"):[];
            $collections =  implode(", ",array_unique($collections));

            $sql_colors="SELECT pm.meta_value slug FROM wp_posts p
            INNER JOIN wp_postmeta pm ON p.ID=pm.post_id AND pm.meta_key IN ('attribute_pa_color','attribute_color')
            WHERE p.post_status='publish' AND p.post_parent=".$value->ID;

            $colors = $wpdb->get_results( $sql_colors,ARRAY_A);

            //$colors = get_the_terms( $value->ID,"pa_color" );
            //$all_colors=$colors;
            $colors = array_column($colors,"slug");
            $colors = array_map(function($x){ return preg_replace('/[0-9]+/', '', str_replace(" ","-",trim(strtolower($x))));},$colors);
            $colors = array_unique($colors);
            $price = isset($meta["_price"][0])?array($meta["_price"][0]):array(0);
            if($price[0]==0){
                $sql_price = "SELECT pmw.meta_value price
                FROM wp_posts pw 
                INNER JOIN wp_postmeta pmw on pw.ID=pmw.post_id AND pmw.meta_key='_price'
                WHERE pw.post_parent=".$value->ID." AND pw.post_status='publish'
                GROUP BY pmw.meta_value";
                $price = $wpdb->get_results( $sql_price,ARRAY_A);
                $price = array_column($price,"price");
                
            }

            $cats = unflattenArray2((array)get_the_terms($value->ID, 'product_cat'));
            
            $product_type = isset($cats[0]) && isset($cats[0]["children"])
                            && isset($cats[0]["children"][0]) && isset($cats[0]["children"][0]["children"])
                            && isset($cats[0]["children"][0]["children"][0]) && isset($cats[0]["children"][0]["children"][0]["children"])
                            ? $cats[0]["children"][0]["children"][0]["children"][0]["name"] : "";

            if($rol!="administrator"){
                $price_ = role_price_get_by_parent_id($value->ID,$rol);
                $price = count($price_)>0?$price_:$price;
            }
            // MARK : Product image
            if($color=="" && $team==""){
                //$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($value->ID), 'thumbnail');
                $thumb = product_variation_color_get($value->ID,[],[],true);
            }else{
                $filter_color = $color!=""?explode(',', $color):[];
                $filter_team = $team!=""?explode(',',strtolower($team)):[];
                $thumb = product_variation_color_get($value->ID,$filter_color,$filter_team);

                $colors = array_filter($colors,function($item) use ($filter_color){
                    return in_array($item,$filter_color);
                });
            }
            $thumb = str_replace("http://34.205.89.113","https://shop2.fexpro.com",$thumb);

           // $collection = $meta["pa_collection"]

            $products[] = [
                'id' => $value->ID,
                'titulo' => $value->post_title,
                'slug' => $value->post_name,
                'img' => $thumb,
                'link' => get_site_url() . '/product/' . $value->post_name,
                'terms' => $cats,
                'price' => $price,
                'collection' => $collections,
                "product_type" => $product_type,
                "colors" => $colors,
                //"all_colors" => $all_colors
                //'meta' => $meta
            ];
        }
    }
   
    $result["products"] = $products;

    print_r(json_encode($result));
    exit; // exit ajax call(or it will return useless information to the response)
}

// Fire AJAX action for both logged in and non-logged in users
add_action('wp_ajax_get_ajax_products_stock', 'get_ajax_products_stock');
add_action('wp_ajax_nopriv_get_ajax_products_stock', 'get_ajax_products_stock');

function filter_variations($loop,$filter_variation=array(),$extra_args=array()){
    if ($loop->have_posts()) {
        global $wpdb;
        $ids_posts=array_column($loop->posts,"ID");

        $delivery_dates=isset($filter_variation["delivery_dates"])?$filter_variation["delivery_dates"]:null;
        $pos = $delivery_dates!=null ? array_search("now",$delivery_dates):false;
        $only_stock_present=$pos!==false?true:false;
        if($only_stock_present){
            unset($delivery_dates[$pos]);
        }


        $extra_field="";
        if(isset($filter_variation["filter_stock_min"]) && $filter_variation["filter_stock_min"]>0){
            $extra_field=" , SUM(pm_s.meta_value*(SELECT SUM(meta_value) FROM wp_postmeta WHERE post_id=p.ID AND meta_key LIKE 'size_box_qty%')) stock";
        }
        /*if(isset($filter_variation["pa_sold"])){
            $extra_field=" , (SELECT count(oi.order_id) cant FROM wp_woocommerce_order_items oi 
            INNER JOIN wp_woocommerce_order_itemmeta oim ON oi.order_item_id=oim.order_item_id AND oim.meta_key='_product_id' AND oim.meta_value=pp.ID) sold ";
        }*/

        $sql="SELECT pp.ID, pp.post_title, pp.post_name, pp.post_date, pp.post_parent ".$extra_field." FROM wp_posts p INNER JOIN wp_posts pp ON p.post_parent=pp.ID";
        
        if($only_stock_present || (is_array($delivery_dates) && count($delivery_dates)>0)):
            $sql.=" INNER JOIN wp_postmeta pm ON p.ID = pm.post_id AND (";
                if($only_stock_present){
                    $sql.=" (pm.meta_key = '_stock_present' AND CAST(pm.meta_value AS SIGNED) > 0) ";
                }    
                if(count($delivery_dates)>0){
                    $jj=0;
                    foreach ($delivery_dates as  $dd) {
                            $_date = date("Y-m",strtotime($dd."-01"));
                            $sql.=((!$only_stock_present && $jj>0) || $only_stock_present ? "OR" :"")." (pm.meta_key = 'delivery_date' AND DATE_FORMAT(CAST(pm.meta_value AS DATE),'%Y-%m') ='".$_date ."') ";
                            $jj++;
                    }
                }
            $sql.=" ) ";
        endif;
        if(isset($filter_variation["pa_color"]) && count($filter_variation["pa_color"])>0):
            $sql.=" INNER JOIN wp_postmeta pm1 ON p.ID=pm1.post_id  AND (pm1.meta_key='attribute_pa_color' AND pm1.meta_value IN ('".implode("','",$filter_variation["pa_color"])."')) ";
        endif;

        if(isset($filter_variation["team"]) && count($filter_variation["team"])>0):
            $sql.=" INNER JOIN wp_postmeta pm_team ON p.ID=pm_team.post_id  AND (pm_team.meta_key='product_team' AND LOWER(TRIM(pm_team.meta_value)) IN ('".implode("','",$filter_variation["team"])."')) ";
        endif;

        if((isset($filter_variation["filter_stock_min"]) && $filter_variation["filter_stock_min"]>0) || isset($extra_args["orderbystock"])){
            $search_in =array();
            if($only_stock_present && count($delivery_dates)==0){
                $search_in =array('_stock_present');
            }
            if(!$only_stock_present && count($delivery_dates)>0){
                $search_in =array('_stock_future');
            }
            if((!$only_stock_present && count($delivery_dates)==0) || ($only_stock_present && count($delivery_dates)>0)){
                $search_in =array('_stock_present','_stock_future');
            }

            $sql.=" INNER JOIN wp_postmeta pm_s ON p.ID=pm_s.post_id AND (pm_s.meta_key IN ('".implode("','",$search_in)."')) ";
        }
        
        if(isset($extra_args["orderby"]) && $extra_args["meta_key"]=="_price"){
            $sql.=" INNER JOIN wp_postmeta pm_p ON p.ID=pm_p.post_id AND pm_p.meta_key='_price'";
        }

        if(isset($filter_variation["pa_sold"])){
            $sql.=" LEFT JOIN wp_postmeta pm2 ON p.ID=pm2.post_id  AND (pm2.meta_key='sold' AND pm2.meta_value = '1') ";
        }


        $sql.=" WHERE 1=1 
        AND p.post_parent IN (".implode(",",$ids_posts).")
        AND p.post_status IN ('publish') ";
        if(isset($filter_variation["pa_sold"])){
            if($filter_variation["pa_sold"][0]=="sold")
                $sql.=" AND pm2.meta_value = '1' ";
            else
            $sql.=" AND pm2.meta_value IS NUll ";
        }
        $sql.=" GROUP BY p.post_parent";
        if(isset($filter_variation["filter_stock_min"]) && $filter_variation["filter_stock_min"]>0){
            $sql.=" HAVING stock >= ".$filter_variation["filter_stock_min"];
            if(isset($filter_variation["filter_stock_max"]) && $filter_variation["filter_stock_max"]>0){
                $sql.=" AND stock <=".$filter_variation["filter_stock_max"];
            }
        }
        /*if(isset($filter_variation["pa_sold"])){
            if($filter_variation["pa_sold"][0]=="sold")
                $sql.=" HAVING sold > 0 ";
            else
            $sql.=" HAVING sold = 0 ";
        }*/
        
        if(isset( $extra_args["meta_key"]) && $extra_args["meta_key"]=="_price"){
            $sql.=" ORDER BY CAST(pm_p.meta_value AS double) ". $extra_args["order"];
        }
        if(isset($extra_args["orderbystock"])){
            $sql.=" ORDER BY SUM(pm_s.meta_value*(SELECT SUM(meta_value) FROM wp_postmeta WHERE post_id=p.ID AND meta_key LIKE 'size_box_qty%')) ". $extra_args["order"];
        }

        if(isset( $extra_args["meta_key"]) && $extra_args["meta_key"]=="alphabethic"){
            $sql.=" ORDER BY pp.post_title ". $extra_args["order"];
        }
        if(isset( $extra_args["meta_key"]) && $extra_args["meta_key"]=="newest"){
            $sql.=" ORDER BY pp.post_date ". $extra_args["order"];
        }
        

        if(isset($extra_args["posts_per_page"])){
            $limit_start = ($extra_args["paged"]-1)*$extra_args["posts_per_page"];
            $sql.=" LIMIT ".$limit_start.", ".$extra_args["posts_per_page"];
        }
        unset($ids_posts);
        unset($loop);
        //echo $sql;
        $r=$wpdb->get_results($sql);
        if(is_array($r) && count($r)>0){

            $loop=new class{
                public function have_posts(){ return true;}
                public $posts;
                public $post_count;
            };
            $loop->posts=$r;
            $loop->post_count=count($r);
           
            return $loop;
        }

        //echo $sql;
        /*$r = $wpdb->get_results($sql,ARRAY_A);
        $posts_id=count($r)>0?array_column($r,"post_parent"):array(0);
        $loop=array();

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post__in' => $posts_id
            );
        $args = count($extra_args)>0? array_merge($args,$extra_args):$args;
        $loop = new WP_Query($args);

        if(!isset($extra_args["orderbystock"])){
           
        }else{

        }*/
        
        return null;
        //return $loop;
    }else{
        return null;
    }
}

function product_variation_color_get($id_product,$colors=array(),$team=array(),$first=false){
    $url_image = get_site_url()."/wp-content/uploads/";
   
    global $wpdb;
    $sql_variaciones="SELECT  pm1.meta_value thumbnail_id, pm2.meta_value image ,
    (SELECT group_concat(concat(b.meta_key,'||',b.meta_value) SEPARATOR '///')  FROM wp_postmeta b WHERE b.post_id=a.ID ) metas
    FROM wp_posts a 
    LEFT JOIN wp_postmeta pm1 ON (pm1.post_id=a.ID AND pm1.meta_value IS NOT NULL AND pm1.meta_key='_thumbnail_id')
    LEFT JOIN wp_postmeta pm2 ON (pm2.post_id=pm1.meta_value AND pm2.meta_key='_wp_attachment_metadata')
    WHERE a.post_status IN ('publish')
    AND a.post_parent=".$id_product;
    $variaciones = $wpdb->get_results($sql_variaciones);
    
    foreach ($variaciones as $key => $var) {
        $metas_variation = explode("///",$var->metas);
        $metas_variation = array_map(function($row){
            $f = explode("||",$row);
            return array("key"=>$f[0],"value"=>strtolower($f[1]));
        },$metas_variation);
        $metas_variation=array_column($metas_variation,"value","key");
        
       //echo "TEAM: ".($metas_variation["product_team"]??"")."->".implode(",",$team)."<br>";
        if(
            (      count($colors)>0 && count($team)>0 
                && isset($metas_variation["attribute_pa_color"]) && in_array($metas_variation["attribute_pa_color"],$colors)
                && isset($metas_variation["product_team"]) && in_array($metas_variation["product_team"],$team)
            )
            || (count($colors)>0 && count($team)==0 && isset($metas_variation["attribute_pa_color"]) && in_array($metas_variation["attribute_pa_color"],$colors))
            || (count($team)>0 && count($colors)==0 && isset($metas_variation["product_team"]) && in_array($metas_variation["product_team"],$team))
            ){

            $g = unserialize($var->image);
            

            $_upload_path = $g["file"]!=""?explode("/",$g["file"]):"";
            if($_upload_path!=""){
                array_pop($_upload_path);
                $_upload_path=implode("/",$_upload_path);
            }
            $image=isset($g["sizes"]) && isset($g["sizes"]["thumbnail"]) && $g["sizes"]["thumbnail"]["file"]!=""?(string)$url_image.$_upload_path."/".$g["sizes"]["thumbnail"]["file"]:"";
            if($image==""){
                $image=isset($g["sizes"]) && isset($g["sizes"]["medium"]) && $g["sizes"]["medium"]["file"]!=""?(string)$url_image.$_upload_path."/".$g["sizes"]["medium"]["file"]:"";
            }
        
            $back_img="";

                $gallery = isset($metas_variation["woo_variation_gallery_images"]) ? unserialize($metas_variation["woo_variation_gallery_images"]):null;
                if(is_array($gallery)){
                    $sql_ga = "SELECT * FROM wp_postmeta pm3 WHERE pm3.meta_key = '_wp_attachment_metadata' AND pm3.post_id IN (".implode(",",$gallery).") limit 1";
                    $gallery = $wpdb->get_results($sql_ga);

                    if(is_array($gallery)){
                        foreach($gallery as $g){
                            $g=unserialize($g->meta_value);
                            
                            $_upload_path = $g["file"]!=""?explode("/",$g["file"]):"";
                            if($_upload_path!=""){
                                array_pop($_upload_path);
                                $_upload_path=implode("/",$_upload_path);
                            }
                            $back_img=isset($g["sizes"]) && isset($g["sizes"]["thumbnail"]) && $g["sizes"]["thumbnail"]["file"]!=""?(string)$url_image.$_upload_path."/".$g["sizes"]["thumbnail"]["file"]:"";
                            if($back_img==""){
                                $back_img=isset($g["sizes"]) && isset($g["sizes"]["medium"]) && $g["sizes"]["medium"]["file"]!=""?(string)$url_image.$_upload_path."/".$g["sizes"]["medium"]["file"]:"";
                            }
                        }
                     
                    }
                }

            return  array($image!=""?$image:null,($back_img!=""?$back_img:null));
        }else{
            // MARK : First image gallery
            

            if($first){
                $g = unserialize($var->image);
             

                $_upload_path = isset($g["file"]) && $g["file"]!=""?explode("/",$g["file"]):"";
                if($_upload_path!=""){
                    array_pop($_upload_path);
                    $_upload_path=implode("/",$_upload_path);
                }
                $image=isset($g["sizes"]) && isset($g["sizes"]["thumbnail"]) && $g["sizes"]["thumbnail"]["file"]!=""?(string)$url_image.$_upload_path."/".$g["sizes"]["thumbnail"]["file"]:"";
                if($image==""){
                    $image=isset($g["sizes"]) && isset($g["sizes"]["medium"]) && $g["sizes"]["medium"]["file"]!=""?(string)$url_image.$_upload_path."/".$g["sizes"]["medium"]["file"]:"";
                }

                $back_img="";

                $gallery = isset($metas_variation["woo_variation_gallery_images"]) ? unserialize($metas_variation["woo_variation_gallery_images"]):null;
                if(is_array($gallery)){
                    $sql_ga = "SELECT * FROM wp_postmeta pm3 WHERE pm3.meta_key = '_wp_attachment_metadata' AND pm3.post_id IN (".implode(",",$gallery).") limit 1";
                    $gallery = $wpdb->get_results($sql_ga);

                    if(is_array($gallery)){
                        foreach($gallery as $g){
                            $g=unserialize($g->meta_value);
                            
                            $_upload_path = $g["file"]!=""?explode("/",$g["file"]):"";
                            if($_upload_path!=""){
                                array_pop($_upload_path);
                                $_upload_path=implode("/",$_upload_path);
                            }
                            $back_img=isset($g["sizes"]) && isset($g["sizes"]["thumbnail"]) && $g["sizes"]["thumbnail"]["file"]!=""?(string)$url_image.$_upload_path."/".$g["sizes"]["thumbnail"]["file"]:"";
                            if($back_img==""){
                                $back_img=isset($g["sizes"]) && isset($g["sizes"]["medium"]) && $g["sizes"]["medium"]["file"]!=""?(string)$url_image.$_upload_path."/".$g["sizes"]["medium"]["file"]:"";
                            }
                        }
                     
                    }
                }
                return  array($image!=""?$image:null,($back_img!=""?$back_img:null));

            }
            
        }
    }
    return array();
}

function category_search($level,$cat_id, $_c=""){
    global $is_presale;
    $mains_cats=array(4595,4104,1829);
    if($is_presale){
        $mains_cats=array(6372,7884,7912);
    }

    global $wpdb;

    $cats = explode(",",$_c);
    $cats=array_map(function($item){
        if($item!="t-shirt"){
            $item = str_replace("-"," ",$item);
        }
        return "tt1.name LIKE '%".$item."%'";
    },$cats);
    $cats_q = implode(' OR ',$cats);

    $sql="SELECT tt1.slug
    FROM wp_terms tt1
    INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
    WHERE tt2.taxonomy='product_cat'
    AND (".$cats_q.") ";
    if($level==1){
        $sql.=" AND  tt2.parent IN (SELECT tt3.term_id FROM wp_term_taxonomy tt3 WHERE tt3.parent IN (".implode(",",$mains_cats)."))";
    }
    if($level==2){
        $sql.=" AND  tt2.parent IN (SELECT tt3.term_id FROM wp_term_taxonomy tt3 WHERE tt3.parent IN (".$cat_id.")) ";
    }
    if($level==3){
        $sql.=" AND tt2.parent = ".$cat_id;
    }
    
    $r=$wpdb->get_results($sql,ARRAY_A);
  
    return array_column($r,"slug");
}

function products_sort_by_stock($ids,$extra_args){
    //$extra_args["orderbystock"] = "meta_value_num";
    //$extra_args["order"] = $orderby=="stock_asc"?"ASC":"DESC";
    //$extra_args["posts_per_page"] = $per_page;
    //$extra_args["paged"] = $pag;

    $sql="SELECT p.post_parent , SUM(pm_s.meta_value*(SELECT SUM(meta_value) FROM wp_postmeta WHERE post_id=p.ID AND meta_key LIKE 'size_box_qty%')) stock 
    FROM wp_posts p 
    INNER JOIN wp_postmeta pm ON p.ID = pm.post_id AND ( (pm.meta_key = '_stock_present' AND CAST(pm.meta_value AS SIGNED) > 0) ) 
    INNER JOIN wp_postmeta pm_s ON p.ID=pm_s.post_id AND (pm_s.meta_key IN ('_stock_present')) 
    WHERE 1=1 AND p.post_parent IN (".implode(",",$ids).") 
    AND p.post_status IN ('publish') 
    GROUP BY p.post_parent 
    HAVING stock >= 1000";

}

add_action('wp_ajax_report_purchase', 'report_purchase');
add_action('wp_ajax_nopriv_report_purchase', 'report_purchase');

function report_purchase(){
    set_time_limit(120);

    $is_export=isset($_GET["export"])?true:false;
    $filtros = json_decode( file_get_contents('php://input') );
    if($is_export){
        $filtros=(object)$_GET;
    }
    $sort_col = $filtros->param ?? "";
    $sort_dir = $filtros->dir ?? "asc";

    $pag= $filtros->page??1;

    
    global $wpdb;

    $sql="SELECT tmp.order_item_name, tmp.productID, tmp.variationID,SUM(tmp.Qty) qty, SUM(tmp.subtotal) subtotal,pm2.meta_value image ,
    (SELECT group_concat(concat(b.meta_key,'||',b.meta_value) SEPARATOR '///')  FROM wp_postmeta b WHERE b.post_id=tmp.variationID ) metas,
    ( SELECT group_concat(concat(tt.taxonomy,'||',t.name,if(tt.taxonomy='product_cat',CONCAT(':',tt.parent,':',tt.term_id),'')) SEPARATOR '///')
                            FROM wp_term_relationships tr
                            INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id
                            INNER JOIN wp_terms t ON tt.term_id=t.term_id
                            WHERE tr.object_id=tmp.productID
                    ) attributes,
                    pm3.meta_value miniaturas";
    if($sort_col!="" && $sort_col!="team"){
        $sql.=" , group_concat(ifnull(trm1.name,'zzz')) sort_col ";
    }
    if($sort_col!="" && $sort_col=="team"){
        $sql.=" , group_concat(ifnull(trm1.meta_value,'zzz')) sort_col ";
    }
    $sql.=" FROM (
        select ttmp.order_id,ttmp.order_item_id,ttmp.order_item_name,ttmp.productID,SUM(ttmp.Qty) Qty,ttmp.variationID,SUM(ttmp.subtotal) subtotal FROM (
            SELECT ttmp.* from(
        select
        p.order_id,
        p.order_item_id,
        p.order_item_name,
        max( CASE WHEN pm.meta_key = '_product_id' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as productID,
        max( CASE WHEN pm.meta_key = '_qty' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as Qty,
        max( CASE WHEN pm.meta_key = '_variation_id' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as variationID,
        max( CASE WHEN pm.meta_key = '_line_subtotal' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as subtotal
        from
        wp_woocommerce_order_items as p,
        wp_woocommerce_order_itemmeta as pm
        where order_item_type = 'line_item' and
        p.order_item_id = pm.order_item_id
        AND p.order_id IN (
        SELECT pp.ID FROM wp_posts pp
        WHERE pp.post_type='shop_order' AND pp.post_status='wc-presale6' AND pp.ID >=126125
        )
        group by
        p.order_item_id
        ) AS ttmp
        INNER JOIN wp_term_relationships tr ON tr.object_id=ttmp.productID AND tr.term_taxonomy_id IN (6497,5931) ";
        if(isset($filtros->filter_cat) && count($filtros->filter_cat)>0){
            $id_cats = term_id_cat($filtros->filter_cat);
            $sql.=" INNER JOIN wp_term_relationships tr0 ON tr0.object_id=ttmp.productID AND tr0.term_taxonomy_id IN (".implode(",",$id_cats).") ";
        }
        if(isset($filtros->filter_lob) && count($filtros->filter_lob)>0){
            $ids_lob = term_id_lob($filtros->filter_lob);
            $sql.=" INNER JOIN wp_term_relationships tr1 ON tr1.object_id=ttmp.productID AND tr1.term_taxonomy_id IN (".implode(",",$ids_lob).") ";
        }
        if(isset($filtros->filter_brand) && count($filtros->filter_brand)>0){
            $ids_brand = term_id_brand($filtros->filter_brand);
            $sql.=" INNER JOIN wp_term_relationships tr2 ON tr2.object_id=ttmp.productID AND tr2.term_taxonomy_id IN (".implode(",",$ids_brand).") ";
        }

        if(isset($filtros->filter_gender) && count($filtros->filter_gender)>0){
            $ids_gender = term_id_gender($filtros->filter_gender);
            $sql.=" INNER JOIN wp_term_relationships tr3 ON tr3.object_id=ttmp.productID AND tr3.term_taxonomy_id IN (".implode(",",$ids_gender).") ";
        }
        if(isset($filtros->filter_group) && count($filtros->filter_group)>0){
            $ids_group = term_id_group($filtros->filter_group);
            $sql.=" INNER JOIN wp_term_relationships tr4 ON tr4.object_id=ttmp.productID AND tr4.term_taxonomy_id IN (".implode(",",$ids_group).") ";
        }
        if(isset($filtros->filter_product_type) && count($filtros->filter_product_type)>0){
            $ids_product_type = term_id_product_type($filtros->filter_product_type);
            $sql.=" INNER JOIN wp_term_relationships tr5 ON tr5.object_id=ttmp.productID AND tr5.term_taxonomy_id IN (".implode(",",$ids_product_type).") ";
        }
        if(isset($filtros->filter_date) && count($filtros->filter_date)>0){
            $ids_date = term_id_date($filtros->filter_date);
            $sql.=" INNER JOIN wp_term_relationships tr6 ON tr6.object_id=ttmp.productID AND tr6.term_taxonomy_id IN (".implode(",",$ids_date).") ";
        }
        if(isset($filtros->filter_season) && count($filtros->filter_season)>0){
            $ids_season = term_id_season($filtros->filter_season);
            $sql.=" INNER JOIN wp_term_relationships tr7 ON tr7.object_id=ttmp.productID AND tr7.term_taxonomy_id IN (".implode(",",$ids_season).") ";
        }
        if(isset($filtros->filter_collection) && count($filtros->filter_collection)>0){
            $ids_collection = term_id_collection($filtros->filter_collection);
            $sql.=" INNER JOIN wp_term_relationships tr8 ON tr8.object_id=ttmp.productID AND tr8.term_taxonomy_id IN (".implode(",",$ids_collection).") ";
        }
        if(isset($filtros->filter_team) && count($filtros->filter_team)>0){
            $ids_team = ("'".implode("','",$filtros->filter_team)."'");
            $sql.=" INNER JOIN wp_postmeta tr9 ON tr9.post_id=ttmp.variationID AND tr9.meta_key='product_team' AND tr9.meta_value IN (".$ids_team.") ";
        }
        
        $sql.=" GROUP BY ttmp.order_item_id
        ) ttmp
        GROUP BY ttmp.variationID
    ) tmp
    LEFT JOIN wp_postmeta pm1 ON (pm1.post_id=tmp.variationID AND pm1.meta_value IS NOT NULL AND pm1.meta_key='_thumbnail_id')
    LEFT JOIN wp_postmeta pm2 ON (pm2.post_id=pm1.meta_value AND pm2.meta_key='_wp_attached_file')
    LEFT JOIN wp_postmeta pm3 ON (pm3.post_id=pm1.meta_value AND pm3.meta_key='_wp_attachment_metadata')
    ";
    if($sort_col!=""){
        if($sort_col=="brand" || $sort_col=="gender" || $sort_col=="season" || $sort_col=="date" || $sort_col=="collection"){
            $sql.=" LEFT JOIN (wp_term_relationships tr1  INNER JOIN wp_term_taxonomy tt1 ON tr1.term_taxonomy_id=tt1.term_taxonomy_id AND tt1.taxonomy='pa_".$sort_col."' INNER JOIN wp_terms trm1 ON trm1.term_id= tt1.term_id) ON tr1.object_id=tmp.productID ";
        }
        if($sort_col=="cat"){
            $sql.=" LEFT JOIN (wp_term_relationships tr1  INNER JOIN wp_term_taxonomy tt1 ON tr1.term_taxonomy_id=tt1.term_taxonomy_id AND tt1.taxonomy='product_cat' AND tt1.term_id IN (6497,5931) INNER JOIN wp_terms trm1 ON trm1.term_id= tt1.term_id) ON tr1.object_id=tmp.productID ";
        }
        if($sort_col=="lob"){
            $sql.=" LEFT JOIN (wp_term_relationships tr1  INNER JOIN wp_term_taxonomy tt1 ON tr1.term_taxonomy_id=tt1.term_taxonomy_id AND tt1.taxonomy='product_cat' AND tt1.term_id IN (5868,1920,7017,5769,5232,5792,7004,3717,5850,5772,5842,4105,6430,5071,6452,4967,5848,5857,6969,4596,5936,5069,5164,6052,5774,1984,6832,5248,5821,6958,6953) INNER JOIN wp_terms trm1 ON trm1.term_id= tt1.term_id) ON tr1.object_id=tmp.productID ";
        }
        if($sort_col=="group"){
            $sql.=" LEFT JOIN (wp_term_relationships tr1  INNER JOIN wp_term_taxonomy tt1 ON tr1.term_taxonomy_id=tt1.term_taxonomy_id AND tt1.taxonomy='product_cat' AND tt1.term_id IN (5868,1920,7017,5769,5232,5792,7004,3717,5850,5772,5842,4105,6430,5071,6452,4967,5848,5857,6969,4596,5936,5069,5164,6052,5774,1984,6832,5248,5821,6958,6953) INNER JOIN wp_terms trm1 ON trm1.term_id= tt1.term_id) ON tr1.object_id=tmp.productID ";
        }
        if($sort_col=="product_type"){
            $sql.=" LEFT JOIN (wp_term_relationships tr1  INNER JOIN wp_term_taxonomy tt1 ON tr1.term_taxonomy_id=tt1.term_taxonomy_id AND tt1.taxonomy='product_cat' AND tt1.term_id IN (9282,9251,9253,8807,9223,9224,8919,9069,6454,9067,9063,9053,9052,9215,8918,6436,6438,6439,6432,6431,7957,9249,9295,7956,9252,8692,8419,9408,8840,7876,7893,9248,7887,8913,8068,7923,7918,7899,7900,7905,7906,8734,7955,8057,8060,8067,8071,8076,8077,8078,8079,8749,8103,9065,9064,8264,8265,9216,8599,8836,8416,9245,8691,9044,9045,8592,8593,8595,8598,8690,8689,8688,8914,8603,8604,8605,8606,8837,9119,9121,9122,9124,9125,9126,9127,9128,9166,9167,9165,9219,9177,9178,9179,9221,9205,9208,9217,9250,9227,9228,9229,9230,9231,9232,9234,9235,9236,9247,9238,9239,9240,9241,9242,9243
            ) INNER JOIN wp_terms trm1 ON trm1.term_id= tt1.term_id) ON tr1.object_id=tmp.productID ";
        }
        if($sort_col=="team"){
            $sql.=" LEFT JOIN wp_postmeta trm1 ON (trm1.post_id=tmp.variationID AND trm1.meta_key='product_team' and trm1.meta_value is not null ) ";
        }
    }
    $sql.="
    GROUP BY tmp.variationID ";
    if($sort_col!=""){
        $sql.=" ORDER BY sort_col ".($sort_dir=="desc"?"DESC":"ASC");
    }
    if(!$is_export){
        $sql.=" limit ".(($pag-1)*30).", 30";//limit 5
    }
    
     //echo $sql;
   
    $imagen_base_url="";
    if(!$is_export){
        $imagen_base_url = get_site_url() . "/wp-content/uploads/";
    }
    $r=$wpdb->get_results($sql);
    foreach($r as $var_data){
        $cats=array();
        $main_product_atts = explode("///",$var_data->attributes);
        $pa_date=[];
        $pa_season=[];
        $main_product_atts = array_map(function($row) use (&$cats,&$pa_date,&$pa_season){
         
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
            return array("key"=>$f[0],"value"=>$f[1]);
        },$main_product_atts);
      
        $main_atts=array_column($main_product_atts,"value","key");
        $main_atts["pa_date"] = implode(",",$pa_date);
        $main_atts["pa_season"] = implode(",",$pa_season);


        $cats_uni = array_column($cats,"id");
        $category="";
        if(in_array(3259,$cats_uni)){
            $category="STOCK";
            if(in_array(6497,$cats_uni)) $category = "STOCK/BASICS";
        }else if(in_array(5931,$cats_uni)){
            $category="PRESALE";
            if(in_array(6497,$cats_uni)) $category = "BASICS";
        }else if(in_array(6497,$cats_uni)){
            $category="BASICS";
        }
      
        $cats=unflattenArray($cats);
        //$cats=array();

        $metas_variation = explode("///",$var_data->metas);
        $metas_variation = array_map(function($row){
            $f = explode("||",$row);
            return array("key"=>$f[0],"value"=>$f[1]);
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
        $price=isset($metas_variation["_price"])?$metas_variation["_price"]:0;

        $units_per_pack=0;
        for($i=1;$i<=10;$i++){
            if(isset($metas_variation["custom_field".$i]) && $metas_variation["custom_field".$i]!=""){
                $sizes[]=$metas_variation["custom_field".$i];
                $units_per_pack+=isset($metas_variation["size_box_qty".$i])?(int)$metas_variation["size_box_qty".$i]:0;
            }
        }

        $item=array();
        //$item["id"] = $variation_id;
        //$item["main_id"] = $var->post_parent;

        $item["image"] = $mini!=""?$imagen_base_url.(string)$upload_path."/".$mini:"";
        
        $item['product_title'] =  $var_data->order_item_name;// . " - " . $color ;

        $item['sku'] =  isset($metas_variation["_sku"])?$metas_variation["_sku"]:"";
        
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
       
        $item["composition"] = isset($metas_variation["pa_fabric_composition"])?$metas_variation["pa_fabric_composition"]:"";
        $item["logo_application"] = isset($metas_variation["logo_application"])?$metas_variation["logo_application"]:"";

        $item['price'] =  $price;

        $item['total_units_purchased'] =  $units_per_pack*$var_data->qty;

        //$item['units_per_pack'] =$units_per_pack;
        
        $item['subtotal'] =  round($var_data->subtotal,2);
       // $item["sort_col"] = $var_data->sort_col??"";
   
        $products[] = $item;

    }

    if(!$is_export){
        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($products),
            "recordsFiltered" => intval($products),
            "data" => $products
          );
          echo json_encode($json_data);
    }else{
        //build_excel_catalog_report($products);
        include("build-excel.php");
        export_phpspreadsheet($products);
    }
     
      die();
    //build_excel_catalog_report($products);

}

function orderParents(&$array){
    usort(
        $array,
        function ($a, $b) {    
            if ($a['parent'] == $b['parent']) {
                return 0;
            }
            return ($a['parent'] < $b['parent']) ? -1 : 1;
        }

    );
}
function unflattenArray($flatArray){
    for($i=0;$i<count($flatArray);$i++){
        if($flatArray[$i]["cat"]=="BASICS"){
            unset($flatArray[$i]);
            break;
        }
    }
    $flatArray=array_values($flatArray);
    $refs = array(); //for setting children without having to search the parents in the result tree.
      $result = array();
  
      //process all elements until nohting could be resolved.
      //then add remaining elements to the root one by one. 
      $limit=0;
      while(count($flatArray) > 0){
          for ($i=count($flatArray)-1; $i>=0; $i--){
              if ($flatArray[$i]["parent"]==0){
                  //root element: set in result and ref!
                  $result[$flatArray[$i]["id"]] = $flatArray[$i]; 
                  $refs[$flatArray[$i]["id"]] = &$result[$flatArray[$i]["id"]];
                  unset($flatArray[$i]);
          $flatArray = array_values($flatArray);
              }
  
              else if ($flatArray[$i]["parent"] != 0){
                  //no root element. Push to the referenced parent, and add to references as well. 
                  if (array_key_exists($flatArray[$i]["parent"], $refs)){
                      //parent found
                      $o = $flatArray[$i];
                      $refs[$flatArray[$i]["id"]] = $o;
            $refs[$flatArray[$i]["parent"]]["children"][] = &$refs[$flatArray[$i]["id"]];
                      unset($flatArray[$i]);
            $flatArray = array_values($flatArray);
                  }
              }
          }
          $limit++;
          if($limit>=10){
            $flatArray=array();
          }
    }
    return array_values($result);
  }
  function getDivision($string){
  
        $list=array("SPORTS","POP","UNIVERSITIES");
        foreach($list as $l){
            if(strpos($string,$l)>-1){
                return $l;
            }
        }
        return "";
  }
  function getGroup($string){
    $list=array("SPORTS","POP","UNIVERSITIES","GIRLS","WOMENS","MENS","KIDS");
    $string=str_replace($list,"",$string);
    return trim($string);
}

function build_excel_catalog_report($data,$headers=array()){
   
    $path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';
    

    $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';


    define('SITEPATH', str_replace('\\', '/', $path1));

    $dataMainHeader = array('Product image','Style name', 'Style sku', 'Division','Brand', 'Gender','Category', 'Group','Product', 'Season','Collection','Date','Team','Composition', 'Product logo');
    if(count($headers)>0){
        $dataMainHeader = array_merge($dataMainHeader,$headers);
    }
    $last_headers=array('Selling Price',"Total Units","Total Value");
    $dataMainHeader = array_merge($dataMainHeader,$last_headers);
    require_once SITEPATH . 'PHPExcel/Classes/PHPExcel.php';

        $objPHPExcel = new PHPExcel(); 

        $objPHPExcel->getProperties()
            ->setCreator("Fexpro")
            ->setLastModifiedBy("Fexpro")
            ->setTitle("Products")
            ->setSubject("Products");

        // Set the active Excel worksheet to sheet 0

        $objPHPExcel->setActiveSheetIndex(0); 


       $objPHPExcel->getActiveSheet()->setAutoFilter('A1:M1');
       /*$objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray( //Q1
                      array(
                          'fill' => array(
                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                              'color' => array('rgb' => '0000000')
                          ),
                          'font'  => array(
                            'color' => array('rgb' => 'FFFFFF'),
                          )
                      )
                  );*/
          
                  
        // Initialise the Excel row number

         

        // Build headers
        $j=0;
        foreach( $dataMainHeader as $i => $row )
        {

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(($i),1,$row);
            $objPHPExcel->getActiveSheet()
            ->getColumnDimensionByColumn($i+1)
            ->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(($i),1)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(($i),1)->applyFromArray( //Q1
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '0000000')
                    ),
                    'font'  => array(
                      'color' => array('rgb' => 'FFFFFF'),
                    )
                )
            );

        }  

        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(14);

        $objPHPExcel->getActiveSheet()->getColumnDimension("J")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("J")->setWidth(40);


        // Build cells

        $rowCount = 0;

        while( $rowCount < count($data) ){ 

            $cell = $rowCount+2;

            $column=0;
            foreach( $data[$rowCount] as $key => $value ) {
                $columnChar=PHPExcel_Cell::stringFromColumnIndex((string)$column);
                //$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 

                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column,$cell)->applyFromArray(
                    array(
                        'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                            )
                        )
                    )
                );

                $objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(75);
                switch ($key) {
                    case 'image':
                        $file = $upload_path.$value;
                        
                        if (file_exists($file)) {

                            $objDrawing = new PHPExcel_Worksheet_Drawing();
                            $objDrawing->setName('Product image');
                            $objDrawing->setDescription('Product image');


                            //Path to signature .jpg file

                            $signature = $file;

                            $objDrawing->setPath($signature);

                            $objDrawing->setOffsetX(5);                     //setOffsetX works properly

                            $objDrawing->setOffsetY(10);                     //setOffsetY works properly

                            $objDrawing->setCoordinates($columnChar.$cell);             //set image to cell 

                            $objDrawing->setHeight(80);                     //signature height  

                            //$objDrawing->getHyperlink()->setUrl('http://www.google.com');

                            $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 
                          
                            //$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($column,$cell)->getHyperlink()->setUrl('http://www.google.com');
                        }
                        break;
                    default:

                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column,$cell, $value); 

                        break;

                }
            if($key=="price" || $key=="subtotal"){
                $objPHPExcel->getActiveSheet()->getStyle($columnChar.$cell)->getNumberFormat()->setFormatCode("\$#,##0.00");  //'0.00');
            }
          
          
         
            $column++;
            }     
            $rowCount++; 
        }  

    @ob_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="export1.xlsx"'); 
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //$objWriter->setPreCalculateFormulas(true);
    $objWriter->save("php://output");
    /*$tmp_name=time();
    $objWriter->save(WI_PLUGIN_PATH."/tmp/".$tmp_name); 
    return $tmp_name;*/

}
add_action('wp_ajax_report_purchase_users', 'report_purchase_users');
add_action('wp_ajax_nopriv_report_purchase_users', 'report_purchase_users');
function report_purchase_users(){
    set_time_limit(120);
    ini_set('memory_limit', '512M');
    $is_export=isset($_GET["export"])?true:false;

    $filtros = json_decode( file_get_contents('php://input') );
    if($is_export){
        $filtros=(object)$_GET;
    }
    $sort_col = $filtros->param ?? "";
    $sort_dir = $filtros->dir ?? "asc";

    $pag= $filtros->page??1;

    

    global $wpdb;

    $sql="SELECT group_concat(tmp.order_id) order_id,tmp.order_item_name, tmp.productID, tmp.variationID,SUM(tmp.Qty) qty, SUM(tmp.subtotal) subtotal,pm2.meta_value image ,
    (SELECT group_concat(concat(b.meta_key,'||',b.meta_value) SEPARATOR '///')  FROM wp_postmeta b WHERE b.post_id=tmp.variationID ) metas,
    ( SELECT group_concat(concat(tt.taxonomy,'||',t.name,if(tt.taxonomy='product_cat',CONCAT(':',tt.parent,':',tt.term_id),'')) SEPARATOR '///')
                            FROM wp_term_relationships tr
                            INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id
                            INNER JOIN wp_terms t ON tt.term_id=t.term_id
                            WHERE tr.object_id=tmp.productID
                    ) attributes,
                    pm3.meta_value miniaturas";
    if($sort_col!="" && $sort_col!="team"){
        $sql.=" , group_concat(ifnull(trm1.name,'zzz')) sort_col ";
    }
    if($sort_col!="" && $sort_col=="team"){
        $sql.=" , group_concat(ifnull(trm1.meta_value,'zzz')) sort_col ";
    }
    $sql.=" FROM (
        select ttmp.order_id,ttmp.order_item_id,ttmp.order_item_name,ttmp.productID,SUM(ttmp.Qty) Qty,ttmp.variationID,SUM(ttmp.subtotal) subtotal FROM (
            SELECT ttmp.* from(
        select
        p.order_id,
        p.order_item_id,
        p.order_item_name,
        max( CASE WHEN pm.meta_key = '_product_id' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as productID,
        max( CASE WHEN pm.meta_key = '_qty' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as Qty,
        max( CASE WHEN pm.meta_key = '_variation_id' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as variationID,
        max( CASE WHEN pm.meta_key = '_line_subtotal' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as subtotal
        from
        wp_woocommerce_order_items as p,
        wp_woocommerce_order_itemmeta as pm
        where order_item_type = 'line_item' and
        p.order_item_id = pm.order_item_id
        AND p.order_id IN (
        SELECT pp.ID FROM wp_posts pp
        WHERE pp.post_type='shop_order' AND pp.post_status='wc-presale6' AND pp.ID >=126125
        )
        group by
        p.order_item_id
        ) AS ttmp
        INNER JOIN wp_term_relationships tr ON tr.object_id=ttmp.productID AND tr.term_taxonomy_id IN (6497,5931) ";
        if(isset($filtros->filter_cat) && count($filtros->filter_cat)>0){
            $id_cats = term_id_cat($filtros->filter_cat);
            $sql.=" INNER JOIN wp_term_relationships tr0 ON tr0.object_id=ttmp.productID AND tr0.term_taxonomy_id IN (".implode(",",$id_cats).") ";
        }
        if(isset($filtros->filter_lob) && count($filtros->filter_lob)>0){
            $ids_lob = term_id_lob($filtros->filter_lob);
            $sql.=" INNER JOIN wp_term_relationships tr1 ON tr1.object_id=ttmp.productID AND tr1.term_taxonomy_id IN (".implode(",",$ids_lob).") ";
        }
        if(isset($filtros->filter_brand) && count($filtros->filter_brand)>0){
            $ids_brand = term_id_brand($filtros->filter_brand);
            $sql.=" INNER JOIN wp_term_relationships tr2 ON tr2.object_id=ttmp.productID AND tr2.term_taxonomy_id IN (".implode(",",$ids_brand).") ";
        }

        if(isset($filtros->filter_gender) && count($filtros->filter_gender)>0){
            $ids_gender = term_id_gender($filtros->filter_gender);
            $sql.=" INNER JOIN wp_term_relationships tr3 ON tr3.object_id=ttmp.productID AND tr3.term_taxonomy_id IN (".implode(",",$ids_gender).") ";
        }
        if(isset($filtros->filter_group) && count($filtros->filter_group)>0){
            $ids_group = term_id_group($filtros->filter_group);
            $sql.=" INNER JOIN wp_term_relationships tr4 ON tr4.object_id=ttmp.productID AND tr4.term_taxonomy_id IN (".implode(",",$ids_group).") ";
        }
        if(isset($filtros->filter_product_type) && count($filtros->filter_product_type)>0){
            $ids_product_type = term_id_product_type($filtros->filter_product_type);
            $sql.=" INNER JOIN wp_term_relationships tr5 ON tr5.object_id=ttmp.productID AND tr5.term_taxonomy_id IN (".implode(",",$ids_product_type).") ";
        }
        if(isset($filtros->filter_date) && count($filtros->filter_date)>0){
            $ids_date = term_id_date($filtros->filter_date);
            $sql.=" INNER JOIN wp_term_relationships tr6 ON tr6.object_id=ttmp.productID AND tr6.term_taxonomy_id IN (".implode(",",$ids_date).") ";
        }
        if(isset($filtros->filter_season) && count($filtros->filter_season)>0){
            $ids_season = term_id_season($filtros->filter_season);
            $sql.=" INNER JOIN wp_term_relationships tr7 ON tr7.object_id=ttmp.productID AND tr7.term_taxonomy_id IN (".implode(",",$ids_season).") ";
        }
        if(isset($filtros->filter_collection) && count($filtros->filter_collection)>0){
            $ids_collection = term_id_collection($filtros->filter_collection);
            $sql.=" INNER JOIN wp_term_relationships tr8 ON tr8.object_id=ttmp.productID AND tr8.term_taxonomy_id IN (".implode(",",$ids_collection).") ";
        }
        if(isset($filtros->filter_team) && count($filtros->filter_team)>0){
            $ids_team = ("'".implode("','",$filtros->filter_team)."'");
            $sql.=" INNER JOIN wp_postmeta tr9 ON tr9.post_id=ttmp.variationID AND tr9.meta_key='product_team' AND tr9.meta_value IN (".$ids_team.") ";
        }
        $sql.=" GROUP BY ttmp.order_item_id
        ) ttmp
        GROUP BY ttmp.variationID
    ) tmp
    LEFT JOIN wp_postmeta pm1 ON (pm1.post_id=tmp.variationID AND pm1.meta_value IS NOT NULL AND pm1.meta_key='_thumbnail_id')
    LEFT JOIN wp_postmeta pm2 ON (pm2.post_id=pm1.meta_value AND pm2.meta_key='_wp_attached_file')
    LEFT JOIN wp_postmeta pm3 ON (pm3.post_id=pm1.meta_value AND pm3.meta_key='_wp_attachment_metadata')
    ";
    if($sort_col!=""){
        if($sort_col=="brand" || $sort_col=="gender" || $sort_col=="season" || $sort_col=="date" || $sort_col=="collection"){
            $sql.=" LEFT JOIN (wp_term_relationships tr1  INNER JOIN wp_term_taxonomy tt1 ON tr1.term_taxonomy_id=tt1.term_taxonomy_id AND tt1.taxonomy='pa_".$sort_col."' INNER JOIN wp_terms trm1 ON trm1.term_id= tt1.term_id) ON tr1.object_id=tmp.productID ";
        }
        if($sort_col=="cat"){
            $sql.=" LEFT JOIN (wp_term_relationships tr1  INNER JOIN wp_term_taxonomy tt1 ON tr1.term_taxonomy_id=tt1.term_taxonomy_id AND tt1.taxonomy='product_cat' AND tt1.term_id IN (6497,5931) INNER JOIN wp_terms trm1 ON trm1.term_id= tt1.term_id) ON tr1.object_id=tmp.productID ";
        }
        if($sort_col=="lob"){
            $sql.=" LEFT JOIN (wp_term_relationships tr1  INNER JOIN wp_term_taxonomy tt1 ON tr1.term_taxonomy_id=tt1.term_taxonomy_id AND tt1.taxonomy='product_cat' AND tt1.term_id IN (5868,1920,7017,5769,5232,5792,7004,3717,5850,5772,5842,4105,6430,5071,6452,4967,5848,5857,6969,4596,5936,5069,5164,6052,5774,1984,6832,5248,5821,6958,6953) INNER JOIN wp_terms trm1 ON trm1.term_id= tt1.term_id) ON tr1.object_id=tmp.productID ";
        }
        if($sort_col=="group"){
            $sql.=" LEFT JOIN (wp_term_relationships tr1  INNER JOIN wp_term_taxonomy tt1 ON tr1.term_taxonomy_id=tt1.term_taxonomy_id AND tt1.taxonomy='product_cat' AND tt1.term_id IN (5868,1920,7017,5769,5232,5792,7004,3717,5850,5772,5842,4105,6430,5071,6452,4967,5848,5857,6969,4596,5936,5069,5164,6052,5774,1984,6832,5248,5821,6958,6953) INNER JOIN wp_terms trm1 ON trm1.term_id= tt1.term_id) ON tr1.object_id=tmp.productID ";
        }
        if($sort_col=="product_type"){
            $sql.=" LEFT JOIN (wp_term_relationships tr1  INNER JOIN wp_term_taxonomy tt1 ON tr1.term_taxonomy_id=tt1.term_taxonomy_id AND tt1.taxonomy='product_cat' AND tt1.term_id IN (9282,9251,9253,8807,9223,9224,8919,9069,6454,9067,9063,9053,9052,9215,8918,6436,6438,6439,6432,6431,7957,9249,9295,7956,9252,8692,8419,9408,8840,7876,7893,9248,7887,8913,8068,7923,7918,7899,7900,7905,7906,8734,7955,8057,8060,8067,8071,8076,8077,8078,8079,8749,8103,9065,9064,8264,8265,9216,8599,8836,8416,9245,8691,9044,9045,8592,8593,8595,8598,8690,8689,8688,8914,8603,8604,8605,8606,8837,9119,9121,9122,9124,9125,9126,9127,9128,9166,9167,9165,9219,9177,9178,9179,9221,9205,9208,9217,9250,9227,9228,9229,9230,9231,9232,9234,9235,9236,9247,9238,9239,9240,9241,9242,9243
            ) INNER JOIN wp_terms trm1 ON trm1.term_id= tt1.term_id) ON tr1.object_id=tmp.productID ";
        }
        if($sort_col=="team"){
            $sql.=" LEFT JOIN wp_postmeta trm1 ON (trm1.post_id=tmp.variationID AND trm1.meta_key='product_team' and trm1.meta_value is not null ) ";
        }
       
    }
    $sql.="

    GROUP BY tmp.variationID ";
    if($sort_col!=""){
        $sql.=" ORDER BY sort_col ".($sort_dir=="desc"?"DESC":"ASC");
    }
    if(!$is_export){
        $sql.=" limit ".(($pag-1)*30).", 30";//limit 5
        //$sql.=" limit 1";//limit 5
    }
    //echo $sql;
  
    $imagen_base_url="";
    if(!$is_export){
        $imagen_base_url = get_site_url() . "/wp-content/uploads/";
    }
    $r=$wpdb->get_results($sql);

    $orders_id = array_column($r,"order_id");

    $group_by = isset($_GET["group_by"])? addslashes($_GET["group_by"]) :"user";
    //MARK: SAGE get sellers
    //$sellers = get_sellers($orders_id,$group_by);
    $sellers = get_all_sellers($group_by);
    

    
    
    // echo "<pre>";
    // print_r($sellers);
    // echo "</pre>";
    // exit;
    foreach($r as $var_data){
        $cats=array();
        $main_product_atts = explode("///",$var_data->attributes);
        $main_product_atts = array_map(function($row) use (&$cats){
         
            $f = explode("||",$row);
            if($f[0]=="product_cat"){
               
                $line = explode(":",isset($f[1])?$f[1]:"");
                $cats[]=array("cat"=>$line[0],"parent"=>isset($line[1])?(int)$line[1]:"0","id"=>isset($line[2])?(int)$line[2]:"0");
            }
            return array("key"=>$f[0],"value"=>$f[1]);
        },$main_product_atts);
        $main_atts=array_column($main_product_atts,"value","key");

        $cats_uni = array_column($cats,"id");
        $category="";
        if(in_array(3259,$cats_uni)){
            $category="STOCK";
            if(in_array(6497,$cats_uni)) $category = "STOCK/BASICS";
        }else if(in_array(5931,$cats_uni)){
            $category="PRESALE";
            if(in_array(6497,$cats_uni)) $category = "BASICS";
        }else if(in_array(6497,$cats_uni)){
            $category="BASICS";
        }

        $cats=unflattenArray($cats);
        //$cats=array();

        $metas_variation = explode("///",$var_data->metas);
        $metas_variation = array_map(function($row){
            $f = explode("||",$row);
            return array("key"=>$f[0],"value"=>$f[1]);
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
        $price=isset($metas_variation["_price"])?$metas_variation["_price"]:0;

        $units_per_pack=0;
        for($i=1;$i<=10;$i++){
            if(isset($metas_variation["custom_field".$i]) && $metas_variation["custom_field".$i]!=""){
                $sizes[]=$metas_variation["custom_field".$i];
                $units_per_pack+=isset($metas_variation["size_box_qty".$i])?(int)$metas_variation["size_box_qty".$i]:0;
            }
        }

        $item=array();
        //$item["id"] = $variation_id;
        //$item["main_id"] = $var->post_parent;

        $item["image"] = $mini!=""?$imagen_base_url.(string)$upload_path."/".$mini:"";
        
        $item['product_title'] =  $var_data->order_item_name;// . " - " . $color ;

        $item['sku'] =  isset($metas_variation["_sku"])?$metas_variation["_sku"]:"";
        //$item["variationID"] = $var_data->variationID;
        $item['division'] =  getDivision(isset($cats[0]["children"][0]["children"][0]["cat"])?$cats[0]["children"][0]["children"][0]["cat"]:(isset($cats[1]["children"][0]["children"][0]["cat"])?$cats[1]["children"][0]["children"][0]["cat"]:""));
        $item['brand'] =  isset($main_atts["pa_brand"])?$main_atts["pa_brand"]:""; 
        $item['gender'] =  isset($main_atts["pa_gender"])?$main_atts["pa_gender"]:"";
        
        $item["category"] = $category;

        $item['group'] = getGroup(isset($cats[0]["children"][0]["children"][0]["cat"])?$cats[0]["children"][0]["children"][0]["cat"]:(isset($cats[1]["children"][0]["children"][0]["cat"])?$cats[1]["children"][0]["children"][0]["cat"]:""));
        //$item['product'] = isset($cats[0]["children"][0]["children"][0]["children"][0]["cat"])?$cats[0]["children"][0]["children"][0]["children"][0]["cat"]:"";

        $item['product'] = isset($cats[0]["children"][0]["children"][0]["children"][0]["cat"])?$cats[0]["children"][0]["children"][0]["children"][0]["cat"]:( isset($cats[1]["children"][0]["children"][0]["children"][0]["cat"])?$cats[1]["children"][0]["children"][0]["children"][0]["cat"]:"");


        $item["season"] = isset($main_atts["pa_season"])?$main_atts["pa_season"]:"";
        $item['collection'] =  isset($main_atts["pa_collection"])?$main_atts["pa_collection"]:"";

        $item['date'] =  isset($main_atts["pa_date"])?$main_atts["pa_date"]:"";

        $item['team'] =  isset($metas_variation["product_team"])?$metas_variation["product_team"]:"";

        $item["composition"] = isset($metas_variation["pa_fabric_composition"])?$metas_variation["pa_fabric_composition"]:"";
        $item["logo_application"] = isset($metas_variation["logo_application"])?$metas_variation["logo_application"]:"";
        

        foreach($sellers as $seller){
            $item["seller_".$seller["user_id"]]=isset($seller["detail"][$var_data->variationID])?$seller["detail"][$var_data->variationID]:"";
        }


        $item['price'] =  $price;
        $item['total_units_purchased'] =  $units_per_pack*$var_data->qty;
        $item['subtotal'] =  round($var_data->subtotal,2);
   
        $products[] = $item;

    }

    if(!$is_export){
        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($products),
            "recordsFiltered" => intval($products),
            "data" => $products,
            "sellers" => $sellers
          );
          echo json_encode($json_data);
    }else{
        $headers=array();
        foreach ($sellers as $seller) {
            $headers[]=$seller["label"];
        }
        include("build-excel.php");
        export_phpspreadsheet($products,$headers);
        //build_excel_catalog_report($products,$headers);
    }
     
      die();
    //build_excel_catalog_report($products);

}

function get_sellers($orders_id,$group_by="user"){
    $_orders_id =array();
    foreach($orders_id as $oi){
        $_ids = explode(",",$oi);
        $_orders_id=array_merge($_orders_id,$_ids);
    }
    $col_group="um.user_id";
    if($group_by=="user"){
        $col_group="um.user_id";
    }else if($group_by=="company"){
        $col_group="um3.meta_value";
    }else{
        $col_group="um4.meta_value";
    }

    global $wpdb;
    $sql="SELECT um.user_id,group_concat(pm.post_id) orders , um.meta_value customer_name,um2.meta_value customer_lastname,um3.meta_value company,um4.meta_value country
    FROM wp_postmeta pm 
    LEFT JOIN wp_usermeta um ON pm.meta_value=um.user_id AND um.meta_key = ('first_name')
    LEFT JOIN wp_usermeta um2 ON pm.meta_value=um2.user_id AND um2.meta_key = ('last_name')
    LEFT JOIN wp_usermeta um3 ON pm.meta_value=um3.user_id AND um3.meta_key = ('billing_company')
    LEFT JOIN wp_usermeta um4 ON pm.meta_value=um4.user_id AND um4.meta_key = ('shipping_country')
    WHERE pm.post_id IN (".(implode(",",$_orders_id)).") AND pm.meta_key ='_customer_user'
    GROUP BY ".$col_group;
    $r=$wpdb->get_results($sql,ARRAY_A);
    $r=array_column($r,null,"user_id");

    $countries=WC()->countries->get_countries();
    foreach($r as $i=> $s){
        //$d=explode("||",$s["userdata"]);
        $r[$i]["nombre"] = $s["customer_name"]." ".$s["customer_lastname"];
        //$r[$i]["company"] = $d[2];
        //$r[$i]["country"] = $d[3];
        $r[$i]["label"] = ($group_by == "user" ? ($s["customer_name"]." ".$s["customer_lastname"]." - ") :"") .
                          ($group_by == "user" || $group_by=="company" ? ($s["company"]." - ") :"").
                           (isset($countries[$s["country"]])?$countries[$s["country"]]:"");
        $r[$i]["detail"] = purchases_seller($s["orders"]);
    }
    return $r;
}
function purchases_seller($orders_id){
    global $wpdb;
    $sql="SELECT oim2.meta_value variation, sum(cast(oim.meta_value AS int))  subtotal, oim3.meta_value sizes
    FROM wp_woocommerce_order_items oi
    INNER JOIN wp_woocommerce_order_itemmeta oim ON oi.order_item_id=oim.order_item_id AND oim.meta_key='_qty' 
    INNER JOIN wp_woocommerce_order_itemmeta oim2 ON oi.order_item_id=oim2.order_item_id AND oim2.meta_key='_variation_id'
    LEFT JOIN wp_woocommerce_order_itemmeta oim3 ON oi.order_item_id=oim3.order_item_id AND oim3.meta_key='item_variation_size' 
    WHERE oi.order_id IN (".$orders_id.")
    GROUP BY oim2.meta_value";
    $r=$wpdb->get_results($sql,ARRAY_A);

    $r=array_map(function($item){
        //$sizes=array_unique(unserialize($item["sizes"]));
        $sizes=array_map("unserialize", array_unique(array_map("serialize", unserialize($item["sizes"]))));
        if(count($sizes)>0){
            $total_pack=array_sum(array_map(function($item2) { 
                return (int)$item2['value']; 
            }, $sizes));
            $item["subtotal"] = $total_pack * $item["subtotal"];
        }
        return $item;
    },$r);
    return array_column($r,"subtotal","variation");
}

function get_all_sellers($group_by="user"){
    global $wpdb;

    $sql_all="   select ttmp.order_id FROM (
        select
        p.order_id,
        max( CASE WHEN pm.meta_key = '_product_id' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as productID

        from
        wp_woocommerce_order_items as p,
        wp_woocommerce_order_itemmeta as pm
        where order_item_type = 'line_item' and
        p.order_item_id = pm.order_item_id
        AND p.order_id IN (
        SELECT pp.ID FROM wp_posts pp
        WHERE pp.post_type='shop_order' AND pp.post_status='wc-presale6' AND pp.ID >=126125
        )
        group by
        p.order_item_id
        ) AS ttmp
        INNER JOIN wp_term_relationships tr ON tr.object_id=ttmp.productID AND tr.term_taxonomy_id IN (6497,5931)
        GROUP BY ttmp.order_id";
    $_orders_id=$wpdb->get_results($sql_all,ARRAY_A);
    $_orders_id=array_column($_orders_id,"order_id");

    $col_group="um.user_id";
    if($group_by=="user"){
        $col_group="um.user_id";
    }else if($group_by=="company"){
        $col_group="um3.meta_value";
    }else{
        $col_group="um4.meta_value";
    }

    $sql="SELECT um.user_id,group_concat(pm.post_id) orders , um.meta_value customer_name,um2.meta_value customer_lastname,um3.meta_value company,um4.meta_value country
    FROM wp_postmeta pm 
    LEFT JOIN wp_usermeta um ON pm.meta_value=um.user_id AND um.meta_key = ('first_name')
    LEFT JOIN wp_usermeta um2 ON pm.meta_value=um2.user_id AND um2.meta_key = ('last_name')
    LEFT JOIN wp_usermeta um3 ON pm.meta_value=um3.user_id AND um3.meta_key = ('billing_company')
    LEFT JOIN wp_usermeta um4 ON pm.meta_value=um4.user_id AND um4.meta_key = ('shipping_country')
    WHERE pm.post_id IN (".(implode(",",$_orders_id)).") AND pm.meta_key ='_customer_user'
    GROUP BY ".$col_group;
    $r=$wpdb->get_results($sql,ARRAY_A);
    $r=array_column($r,null,"user_id");

    $countries=WC()->countries->get_countries();
    foreach($r as $i=> $s){
        $r[$i]["nombre"] = $s["customer_name"]." ".$s["customer_lastname"];
        $r[$i]["label"] = ($group_by == "user" ? ($s["customer_name"]." ".$s["customer_lastname"]." - ") :"") .
                          ($group_by == "user" || $group_by=="company" ? ($s["company"]." - ") :"").
                           (isset($countries[$s["country"]])?$countries[$s["country"]]:"");
        $r[$i]["detail"] = purchases_seller($s["orders"]);
    }
    return $r;

}
add_action('wp_ajax_filtros_reportes_get', 'filtros_reportes_get');
add_action('wp_ajax_nopriv_filtros_reportes_get', 'filtros_reportes_get');
function filtros_reportes_get(){

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    global $wpdb;
    $sql="select ttmp.productID,group_concat(ttmp.variationID) variationID FROM (
        select
        max( CASE WHEN pm.meta_key = '_product_id' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as productID,
        max( CASE WHEN pm.meta_key = '_variation_id' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as variationID
        from
        wp_woocommerce_order_items as p,
        wp_woocommerce_order_itemmeta as pm
        where order_item_type = 'line_item' and
        p.order_item_id = pm.order_item_id
        AND p.order_id IN (
        SELECT pp.ID FROM wp_posts pp
        WHERE pp.post_type='shop_order' AND pp.post_status='wc-presale5' AND pp.ID >=126125
        )
        group by
        p.order_item_id
        ) AS ttmp
        INNER JOIN wp_term_relationships tr ON tr.object_id=ttmp.productID AND tr.term_taxonomy_id IN (6497,5931)
        GROUP BY ttmp.productID";
    $products_id= $wpdb->get_results($sql,ARRAY_A);
    $variations_id = array_column($products_id,"variationID");
    $products_id = array_column($products_id,"productID");
    
    $json["filter_cat"] = filter_category();
    $json["filter_lob"] = filter_lob();
    $json["filter_brand"] = filter_brand($products_id);
    $json["filter_gender"] = filter_gender($products_id);
    $json["filter_date"] = filter_date($products_id);
    $json["filter_group"] = filter_group($products_id);
    $json["filter_product_type"] = filter_product_type($products_id);
    $json["filter_season"] = filter_season($products_id);
    $json["filter_collection"] = filter_collection($products_id);
    $json["filter_team"] = filter_team($variations_id);

    echo json_encode($json);
    die;

}
function filter_category(){
    $cat=array( array("value"=>"presale","text"=>"Presale"),array("value"=>"basics","text"=>"Basics"));
    return $cat;
}
function filter_lob(){
    $lob=array(array("value"=>"POP","text"=>"POP"),array("value"=>"SPO","text"=>"SPORT"),array("value"=>"UNI","text"=>"UNIVERSITIES"));

    $current_filters = array();
    $current_filters["meta_lob"] = isset($_GET["meta_lob"])?explode(",",$_GET["meta_lob"]):array();
    $current_filters["meta_lob"] = array_map(function($item){ return addslashes($item);},$current_filters["meta_lob"]);

    foreach ($lob as $key => $value) {
        $lob[$key]["checked"] = in_array($value["value"],$current_filters["meta_lob"]);
    }
    return $lob;
}
function filter_brand($products_id){
    global $wpdb;
    $sql="SELECT b.term_id, b.slug value, b.name text
        FROM wp_term_taxonomy a
        INNER JOIN wp_terms b ON a.term_id=b.term_id
        INNER JOIN wp_term_relationships c ON a.term_taxonomy_id=c.term_taxonomy_id
        WHERE a.taxonomy='pa_brand'
        AND c.object_id IN(".implode(",",$products_id)."
        )
        GROUP BY b.term_id
        ORDER BY b.name ASC";

    $r=$wpdb->get_results($sql,ARRAY_A);

    return $r;

}
function filter_gender($products_id){
    global $wpdb;
    $sql="SELECT b.term_id, b.slug value, b.name text
        FROM wp_term_taxonomy a
        INNER JOIN wp_terms b ON a.term_id=b.term_id
        INNER JOIN wp_term_relationships c ON a.term_taxonomy_id=c.term_taxonomy_id
        WHERE a.taxonomy='pa_gender'
        AND c.object_id IN(".implode(",",$products_id)."
        )
        GROUP BY b.term_id
        ORDER BY b.name ASC";

    $r=$wpdb->get_results($sql,ARRAY_A);

    return $r;

}
function filter_date($products_id){
    global $wpdb;
    $sql="SELECT b.term_id, b.slug value, b.name text
        FROM wp_term_taxonomy a
        INNER JOIN wp_terms b ON a.term_id=b.term_id
        INNER JOIN wp_term_relationships c ON a.term_taxonomy_id=c.term_taxonomy_id
        WHERE a.taxonomy='pa_date'
        AND c.object_id IN(".implode(",",$products_id)."
        )
        GROUP BY b.term_id
        ORDER BY b.name ASC";

    $r=$wpdb->get_results($sql,ARRAY_A);

    return $r;

}

function filter_group($products_id){
    global $wpdb;
    $sql="SELECT * FROM (
        SELECT trim(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(NAME,'SPORTS ',''),'POP ',''),'GIRLS',''),'WOMENS',''),'MENS',''),'KIDS',''),'UNIVERISITIES','')) as name
        FROM wp_terms tt1
        INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
        INNER JOIN wp_term_relationships tr ON tt2.term_taxonomy_id=tr.term_taxonomy_id
        WHERE tt2.taxonomy='product_cat' and (name!='' and name!='Uncategorized' ) AND tt2.count>0 
            AND  tt2.term_id IN (
				SELECT tt3.term_id FROM wp_term_taxonomy tt3 
				WHERE tt3.parent IN (4595,4104,1829,5935,5934,5932,5933,6372)
				)
			AND tr.object_id IN (".implode(",",$products_id).")
        ) tmp
        GROUP BY tmp.name
        ORDER BY NAME ASC";
    $r=$wpdb->get_results($sql);

    $groups=array();
    foreach ($r as $value) {
        $item=array();
        $item["text"] = $value->name;
        $item["value"] = sanitize_title($value->name);
       
        $groups[]=$item;
    }
    return $groups;

}

function filter_product_type($products_id){
    global $wpdb;

    $sql="SELECT * FROM (
    SELECT trim(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(NAME,'SPORTS ',''),'POP ',''),'GIRLS',''),'WOMENS',''),'MENS',''),'KIDS',''),'UNIVERISITIES','')) as name
    FROM wp_terms tt1
    INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
    INNER JOIN wp_term_relationships tr ON tt2.term_taxonomy_id=tr.term_taxonomy_id
    WHERE tt2.taxonomy='product_cat' and (name!='' and name!='Uncategorized' ) AND tt2.count>0 
        AND  tt2.parent IN (SELECT tt3.term_id FROM wp_term_taxonomy tt3 WHERE tt3.parent IN (4595,4104,1829,5935,5934,5932,5933,6372))
        AND tr.object_id IN (".implode(",",$products_id).")
    ) tmp
    GROUP BY tmp.name
    ORDER BY NAME ASC";
    $r=$wpdb->get_results($sql);
    
    
    $products_type=array();
    foreach ($r as $value) {
        $item=array();
        $item["text"] = $value->name;
        $item["value"] = sanitize_title($value->name);
        $products_type[]=$item;
    }
    return $products_type;

}

function filter_season($products_id){
    global $wpdb;
    $sql="SELECT b.term_id, b.slug value, b.name text
        FROM wp_term_taxonomy a
        INNER JOIN wp_terms b ON a.term_id=b.term_id
        INNER JOIN wp_term_relationships c ON a.term_taxonomy_id=c.term_taxonomy_id
        WHERE a.taxonomy='pa_season'
        AND c.object_id IN(".implode(",",$products_id)."
        )
        GROUP BY b.term_id
        ORDER BY b.name ASC";

    $r=$wpdb->get_results($sql,ARRAY_A);

    return $r;

}
function filter_collection($products_id){
    global $wpdb;
    $sql="SELECT b.term_id, b.slug value, b.name text
        FROM wp_term_taxonomy a
        INNER JOIN wp_terms b ON a.term_id=b.term_id
        INNER JOIN wp_term_relationships c ON a.term_taxonomy_id=c.term_taxonomy_id
        WHERE a.taxonomy='pa_collection'
        AND c.object_id IN(".implode(",",$products_id)."
        )
        GROUP BY b.term_id
        ORDER BY b.name ASC";

    $r=$wpdb->get_results($sql,ARRAY_A);

    return $r;

}
function filter_team($products_id){
    global $wpdb;
    $sql="SELECT pm.meta_value value, pm.meta_value text FROM  wp_postmeta pm where pm.meta_key='product_team'
    and pm.post_id IN (".implode(",",$products_id).")
    AND pm.meta_value!=''
     GROUP BY pm.meta_value
     ORDER BY pm.meta_value ASC";
     $r=$wpdb->get_results($sql,ARRAY_A);

     return $r;
}
function term_id_cat($cats){
    
    $ids=array();
    if(in_array("presale",$cats)){
        $id_cats[]=5931;
    };
    if(in_array("basics",$cats)){
        $id_cats[]=6497;
    };
    if(in_array("stock_basic",$cats)){
        $id_cats[]=6497;
        $id_cats[]=5931;
    };
    
   
    return $id_cats;
}
function term_id_lob($lobs){
    global $wpdb;
    //$types = explode(",",$lobs);
    
    $includes = array_map(function($item){
        
        //if($item=="UNI") $item="UNIVERISITIES";
        
        if($item!="UNI"){
            if($item=="SPO") $item="SPORTS";

            return " tt1.name LIKE '".$item."%' ";
        }else{
            return " (tt1.name LIKE 'UNIVERISITIES%' OR tt1.name LIKE 'UNIVERSITY%' ) ";
        }
    },$lobs);
    $includes = implode(" OR ",$includes);
    $sql="SELECT tt1.term_id,tt1.slug
    FROM wp_terms tt1
    INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
    WHERE tt2.taxonomy='product_cat'
    AND  tt2.term_id IN (SELECT tt3.term_id FROM wp_term_taxonomy tt3 WHERE tt3.parent IN (4595,4104,1829,5935,5934,5932,5933,6372))
    AND (".$includes.")";
    // echo $sql;
    // die;
    $r=$wpdb->get_results($sql,ARRAY_A);
    $r=array_column($r,"term_id");
    return $r;
}
function term_id_brand($brands){
    global $wpdb;

    $includes = implode("','",$brands);

    $sql="SELECT tt1.term_id,tt1.slug
    FROM wp_terms tt1
    INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
    WHERE tt2.taxonomy='pa_brand'
    AND tt1.slug IN ('".$includes."')";

    $r=$wpdb->get_results($sql,ARRAY_A);
    $r=array_column($r,"term_id");
    return $r;
}

function term_id_gender($gender){
    global $wpdb;

    $includes = implode("','",$gender);

    $sql="SELECT tt1.term_id,tt1.slug
    FROM wp_terms tt1
    INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
    WHERE tt2.taxonomy='pa_gender'
    AND tt1.slug IN ('".$includes."')";

    $r=$wpdb->get_results($sql,ARRAY_A);
    $r=array_column($r,"term_id");
    return $r;
}
function term_id_group($groups){
    global $wpdb;
    //$types = explode(",",$lobs);
    
   // $groups = explode(",",$groups);
        
    $includes = array_map(function($item){
        if($item!="t-shirt"){
            $item=str_replace("-"," ",$item);
        }
        return " tt1.name LIKE '%".$item."%' ";
    },$groups);
    $includes = implode(" OR ",$includes);
    $sql="SELECT tt1.term_id,tt1.slug
    FROM wp_terms tt1
    INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
    WHERE tt2.taxonomy='product_cat'
    AND  tt2.term_id IN (SELECT tt3.term_id FROM wp_term_taxonomy tt3 WHERE tt3.parent IN (4595,4104,1829,5935,5934,5932,5933,6372))
    AND (".$includes.")";
    // echo $sql;
    // die;
    $r=$wpdb->get_results($sql,ARRAY_A);
    $r=array_column($r,"term_id");
    return $r;
}
function term_id_product_type($types){
    global $wpdb;
    //$types = explode(",",$lobs);
    
    //$types = explode(",",$types);
        
    $includes = array_map(function($item){
        if($item!="t-shirt"){
            $item=str_replace("-"," ",$item);
        }
        return " tt1.name LIKE '%".$item."%' ";
    },$types);
    $includes = implode(" OR ",$includes);
    $sql="SELECT tt1.term_id,tt1.slug
    FROM wp_terms tt1
    INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
    WHERE tt2.taxonomy='product_cat'
    AND  tt2.parent IN (SELECT tt3.term_id FROM wp_term_taxonomy tt3 WHERE tt3.parent IN (4595,4104,1829,5935,5934,5932,5933,6372))
    AND (".$includes.")";

    $r=$wpdb->get_results($sql,ARRAY_A);
    $r=array_column($r,"term_id");
    return $r;
}
function term_id_date($dates){
    global $wpdb;

    $includes = implode("','",$dates);

    $sql="SELECT tt1.term_id,tt1.slug
    FROM wp_terms tt1
    INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
    WHERE tt2.taxonomy='pa_date'
    AND tt1.slug IN ('".$includes."')";
    
    $r=$wpdb->get_results($sql,ARRAY_A);
    $r=array_column($r,"term_id");
    return $r;
}  
function term_id_season($seasons){
    global $wpdb;

    $includes = implode("','",$seasons);

    $sql="SELECT tt1.term_id,tt1.slug
    FROM wp_terms tt1
    INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
    WHERE tt2.taxonomy='pa_season'
    AND tt1.slug IN ('".$includes."')";

    $r=$wpdb->get_results($sql,ARRAY_A);
    $r=array_column($r,"term_id");
    return $r;
}
function term_id_collection($collection){
    global $wpdb;

    $includes = implode("','",$collection);

    $sql="SELECT tt1.term_id,tt1.slug
    FROM wp_terms tt1
    INNER JOIN wp_term_taxonomy tt2 ON tt1.term_id=tt2.term_id
    WHERE tt2.taxonomy='pa_collection'
    AND tt1.slug IN ('".$includes."')";

    $r=$wpdb->get_results($sql,ARRAY_A);
    $r=array_column($r,"term_id");
    return $r;
}
function unflattenArray2($flatArray){
   
    $flatArray=array_values($flatArray);
    $refs = array(); 
      $result = array();
  
      $limit=0;
      while(count($flatArray) > 0){
          for ($i=count($flatArray)-1; $i>=0; $i--){
            $flatArray[$i]=(array)$flatArray[$i];
            $flatArray[$i]["id"] = $flatArray[$i]["term_id"];
            
              if ($flatArray[$i]["parent"]==0){
                  //root element: set in result and ref!
                  $result[$flatArray[$i]["id"]] = $flatArray[$i]; 
                  $refs[$flatArray[$i]["id"]] = &$result[$flatArray[$i]["id"]];
                  unset($flatArray[$i]);
                 $flatArray = array_values($flatArray);
              }
  
              else if ($flatArray[$i]["parent"] != 0){
                  //no root element. Push to the referenced parent, and add to references as well. 
                  if (array_key_exists($flatArray[$i]["parent"], $refs)){
                      //parent found
                      $o = $flatArray[$i];
                      $refs[$flatArray[$i]["id"]] = $o;
            $refs[$flatArray[$i]["parent"]]["children"][] = &$refs[$flatArray[$i]["id"]];
                      unset($flatArray[$i]);
            $flatArray = array_values($flatArray);
                  }
              }
          }
          $limit++;
          if($limit>=10){
            $flatArray=array();
          }
    }
    return array_values($result);
  }
  function get_user_collections(){
    $user_id=get_current_user_id();
    $user_config = get_user_meta($user_id,"wi_collection_config");
    $user_config = isset($user_config[0]) ? json_decode($user_config[0]):[];
    if(count($user_config)>0){
        global $wpdb;
        $r=$wpdb->get_results("select division,brand,department,collection from wi_collection_config WHERE id IN (".implode(",",$user_config).")",ARRAY_A);
        $user_config = $r;
    }
    return $user_config;
}
 ?>