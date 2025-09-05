<?php 
add_action( 'wp_ajax_customFilterExportLiveDataFilter','customFilterExportLiveDataFilter' );

add_action( 'wp_ajax_nopriv_customFilterExportLiveDataFilter','customFilterExportLiveDataFilter' );

function customFilterExportLiveDataFilter(){
   session_start();
	global $wpdb;

	$url1 = site_url();

	$path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';

	$base_path = wp_upload_dir();

	$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';

	define('SITEURL', $url1);

	define('SITEPATH', str_replace('\\', '/', $path1));


   $u =  wp_get_current_user();
   $um = $u->ID;
   $user_meta=get_userdata($um);
   $getGroupID = $wpdb->get_row("SELECT `group_id` from {$wpdb->prefix}groups_user_group WHERE `user_id` = '$um'");
   $mexicoUserGroupID = $getGroupID->group_id;    


   $getas = get_option('afpvu_user_role_visibility');
   $kk = maybe_unserialize($getas);
   $hideProductArr = array();
   $hideCategoryArr = array();
   if(isset($_SESSION['abc']) && !empty($_SESSION['abc']))
   {
      if($kk[$_SESSION['abc']]['afpvu_show_hide_role'] == 'hide')
      {
         $hideProductArr = $kk[$_SESSION['abc']]['afpvu_applied_products_role']; 
         $hideCategoryArr = $kk[$_SESSION['abc']]['afpvu_applied_categories_role'];

      }
   }

   //hide by category 

   if(!empty($_POST['pabrand'])){
      if(!empty($hideCategoryArr)){
         $get_variations_arr21 = array();
         $query_sql1 = "SELECT p_id FROM wp_ss22_bulk_filter_brand_custom_filter WHERE 1=1";

         if(count($hideCategoryArr) > 1){
            $extraQuery = "(";
            foreach($hideCategoryArr as $key => $dataVal ){
               if ($key === array_key_last($hideCategoryArr)) {
                  $or = "";
               }else{
                  $or = "OR";
               }
               $extraQuery .= " cat_id LIKE '%$dataVal%' ".$or;
            }
            $extraQuery .= ")";
            $query_sql1 .= " AND". $extraQuery;
         }else{
            $query_sql1 .= " AND cat_id LIKE '%$hideCategoryArr[0]%'";
         }

         $get_variations_arr21 = $wpdb->get_results(" ".$query_sql1."  " );
          

         $categoryHideArr = array();
         foreach($get_variations_arr21 as $key => $ddd){
            if(!in_array($ddd->p_id, $categoryHideArr)){
               array_push($categoryHideArr, $ddd->p_id);
            }
         }
      } 

      $hideProductAllinOneArr = array();
      if(!empty($hideProductArr) && !empty($categoryHideArr)){
         $hideProductAllinOneArr = array_merge($hideProductArr, $categoryHideArr);
      }else if(!empty($hideProductArr) && empty($categoryHideArr)  ){
          $hideProductAllinOneArr = $hideProductArr;
      }else if(!empty($categoryHideArr) && empty($hideProductArr)  ){
          $hideProductAllinOneArr = $categoryHideArr;
      }else{
         $hideProductAllinOneArr = array();
      }

   }

   $namee = ($_POST['pabrand'])?$_POST['pabrand']:"";

	$attr_pa_cat_name = ($_POST['pacat'])?$_POST['pacat']:"";

	$attr_pa_color_name = ($_POST['pacolor'])?$_POST['pacolor']:"";

	$attr_pa_team_name = ($_POST['pateam'])?$_POST['pateam']:"";
   $attr_pa_season_name = ($_POST['paseason'])?$_POST['paseason']:"";


   $query_sql = "SELECT * FROM wp_ss22_bulk_filter_brand_custom_filter WHERE 1=1";
   if(!empty($_POST['pabrand'])){
      $query_sql .= " AND brand_id IN($namee)";
   }

   if(!empty($_POST['pacat'])){
      $attr_pa_cat_name_arr = explode(",", $attr_pa_cat_name);
      if(count($attr_pa_cat_name_arr) > 1){
         $extraQuery = "(";
         foreach($attr_pa_cat_name_arr as $key => $dataVal ){
            if ($key === array_key_last($attr_pa_cat_name_arr)) {
               $or = "";
            }else{
               $or = "OR";
            }
            $extraQuery .= " cat_id LIKE '%$dataVal%' ".$or;
         }
         $extraQuery .= ")";
         $query_sql .= " AND". $extraQuery;
      }else{
         $query_sql .= " AND cat_id LIKE '%$attr_pa_cat_name%'";
      }
   
   }



   if(!empty($_POST['pacolor'])){
     
      $query_sql .= " AND FIND_IN_SET(color_id,'$attr_pa_color_name')";
   }
   if(!empty($_POST['pateam'])){
      $query_sql .= " AND FIND_IN_SET(team,'$attr_pa_team_name')";
   }
   if(!empty($_POST['paseason'])){
      $query_sql .= " AND FIND_IN_SET(season,'$attr_pa_season_name')"; 
   }

   $get_variations_arr2 = $wpdb->get_results(" ".$query_sql." " );
   
	$dataHeader_sub = array();
	foreach($get_variations_arr2 as $key => $data_record){

		$variation_id = $data_record->v_id;
      $_product =  wc_get_product( $variation_id);

      if(!in_array($_product->get_parent_id(), $hideProductAllinOneArr)){
         //$variation_size = wc_get_order_item_meta( $variation_id, 'item_variation_size', true );
         for($i=1;$i<11;$i++)
         {
               if(get_post_meta( $variation_id, 'custom_field'.$i, true ))
               {
               if(!in_array('Size: ' . get_post_meta( $variation_id, 'custom_field'.$i, true ), $dataHeader_sub)){
                  array_push($dataHeader_sub, 'Size: ' . get_post_meta( $variation_id, 'custom_field'.$i, true ) );
               }
               }
            }
         }

      }


		

   	foreach($get_variations_arr2 as $key => $data_record){

   		$variation_id = $data_record->v_id;

   		$_product =  wc_get_product( $variation_id);
         if( !in_array($_product->get_parent_id(), $hideProductAllinOneArr) ){

         		$main_product = wc_get_product( $_product->get_parent_id() );
         		
         		$terms1 = get_the_terms( $_product->get_parent_id() , 'product_cat' );
         		$apk = '';
         		foreach ($terms1 as $term1) {
         			if($term1->parent == 3259)
         			{
         				$apk = $term1->name;
         			}
         		}			

         		$image_id			= $_product->get_image_id();

         		$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );

         		$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );

         		$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );



         		$imageUrlThumb = str_replace("https://shop2.fexpro.com", "",$thumbnail_src[0]);

         		$imageUrlThumb1 = $_SERVER['DOCUMENT_ROOT'] . $imageUrlThumb;



         		//$variation_size = wc_get_order_item_meta( $variation_id, 'item_variation_size', true );

               if(get_user_meta( $um, 'customer_margin', true))
               {
                  $getMargin = get_user_meta( $um, 'customer_margin', true);
                  if(get_user_meta( $um, 'customer_iva_margin', true)){
                     $getMargin = $getMargin + get_user_meta( $um, 'customer_iva_margin', true);
                  }
                  $discountRule = (100 - $getMargin) / 100;
                  //echo $discountRule;
               }
               else
               {
                  $discountRule = 1;
               }
               if($mexicoUserGroupID == 2)
               {
                  //echo $discountRule;
                  $getGroupPrice = $wpdb->get_row("SELECT `price` from {$wpdb->prefix}wusp_group_product_price_mapping WHERE `group_id` = '$mexicoUserGroupID' AND `product_id` = $variation_id");
                  $price        = $getGroupPrice->price * $discountRule;         
                  $priceCustomCalc        = $getGroupPrice->price;         
                  $price_html   = wc_price($priceCustomCalc * $discountRule);
               }
               else if($user_meta->roles[0] == 'custom_role_puerto_rico')
               {
                  //echo $discountRule;
                  
                  $price        = $_product->get_price() * 1.25;         
                  $price_html   = wc_price($_product->get_price() * 1.25);
               }
               else
               {
                  $price        = $_product->get_price();
                  $price_html   = $_product->get_price_html();
               }
         	

               $product_metas = get_post_meta($variation_id);
         		 

         		$nestedData = array();

         		$nestedData['Image'] =  $imageUrlThumb1;

               $nestedData['Product SKU'] =  $_product->get_sku();

         		$nestedData['Product Title'] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) ;

         		$nestedData['Color'] =  $_product->get_attribute( 'pa_color' ) ;

               $nestedData['Season'] =  $main_product->get_attribute( 'pa_season' ) ;
               
               $nestedData['Price'] =  $price;

               $nestedData['Division'] =  "";

               $nestedData['Brand'] =  $main_product->get_attribute( 'pa_brand' );

               $nestedData['Departament'] =  "";

               $nestedData['Category'] =  "";
               $nestedData['Subcategory'] =  "";

               $nestedData['totalprice'] = 0;
               $nestedData['Stok'] = $_product->get_stock_quantity();

         		$nestedData['Qty'] =  0;

               $nestedData['units_per_pack'] =  isset($product_metas["size_box_qty1"][0]) ? $product_metas["size_box_qty1"][0]:"";

               $nestedData['meta_custom_1'] =  isset($product_metas["custom_field1"][0]) ? $product_metas["custom_field1"][0]:"";
               $nestedData['meta_custom_2'] =  isset($product_metas["custom_field2"][0]) ? $product_metas["custom_field2"][0]:"";
               $nestedData['meta_custom_3'] =  isset($product_metas["custom_field3"][0]) ? $product_metas["custom_field3"][0]:"";
               $nestedData['meta_custom_4'] =  isset($product_metas["custom_field4"][0]) ? $product_metas["custom_field4"][0]:"";
               $nestedData['meta_custom_5'] =  isset($product_metas["custom_field5"][0]) ? $product_metas["custom_field5"][0]:"";
               $nestedData['meta_custom_6'] =  isset($product_metas["custom_field6"][0]) ? $product_metas["custom_field6"][0]:"";
               $nestedData['meta_custom_7'] =  isset($product_metas["custom_field7"][0]) ? $product_metas["custom_field7"][0]:"";
               $nestedData['meta_custom_8'] =  isset($product_metas["custom_field8"][0]) ? $product_metas["custom_field8"][0]:"";
               $nestedData['meta_custom_9'] =  isset($product_metas["custom_field9"][0]) ? $product_metas["custom_field9"][0]:"";
               $nestedData['meta_custom_10'] =  isset($product_metas["custom_field10"][0]) ? $product_metas["custom_field10"][0]:"";

               /*
         		$nestedData['Gender'] =  $apk;
         		
         		$nestedData['Collection'] =  $main_product->get_attribute( 'pa_collection' );
         		
         		$nestedData['Fabric Composition'] =  $main_product->get_attribute( 'pa_fabric-composition' );
         		
         		$nestedData['Logo Application'] =  $main_product->get_attribute( 'logo-application' );

         		*/
         		

         		//$ohterData = array();

         		/* foreach ($variation_size as $key => $size) 

         		{

         			$ohterData[ 'Size: ' . $size['label'] ] = $size['value'] ;

         		} */

      		/*for($i=1;$i<11;$i++)
      		{
      			if(get_post_meta( $variation_id, 'size_box_qty'.$i, true ))
      			{

      			$ohterData[ 'Size: ' . get_post_meta( $variation_id, 'custom_field'.$i, true ) ] = get_post_meta( $variation_id, 'size_box_qty'.$i, true );
      			}
      		}



         		foreach($dataHeader_sub as $kkkk => $vvvvv){

         			$nestedData[$vvvvv] = $ohterData[$vvvvv];

         			

         		}*/



         		$reAssignIndexingToArr = array_values($nestedData);

         		$data[] = $reAssignIndexingToArr;

         }

   	}

	



		$xlsx_data_new_allBody= $data;



		$getTotalCountBody = count($data);


		$dataMainHeader = array('Image','SKU', 'Name', 'Color', 'Season', 'Regular Price', 'Division', 'Licence', 'Department','Category','Subcategory','Total Price (USD)','Stock','Qty','Units per pack', 'Meta: custom_field1','Meta: custom_field2','Meta: custom_field3','Meta: custom_field4','Meta: custom_field5','Meta: custom_field6','Meta: custom_field7','Meta: custom_field8','Meta: custom_field9','Meta: custom_field10');



		$dataHeader = $dataMainHeader;//array_merge($dataMainHeader,$dataHeader_sub);



		$k = 1;

		$i = 0;

		$getTotalCountHeader = count($dataHeader);

		$count = 0;

		foreach($dataHeader as $keyHeader => $dHeader)

		{

			$xlsx_data_new_allHeader= array();

			$alpha = num_to_letters($keyHeader+1);

			

			$dataH["$alpha"] = $dHeader;

			

			array_push($xlsx_data_new_allHeader, $dataH);

			$k++;

		}

	

			

		require_once SITEPATH . 'PHPExcel/Classes/PHPExcel.php';



		$objPHPExcel = new PHPExcel(); 

		$objPHPExcel->getProperties()

			->setCreator("user")

			->setLastModifiedBy("user")

			->setTitle("Office 2007 XLSX Test Document")

			->setSubject("Office 2007 XLSX Test Document")

			->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")

			->setKeywords("office 2007 openxml php")

			->setCategory("Test result file");



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

		$rowCount = 0; 



		$cell_definition = $xlsx_data_new_allHeader[0];

		$reportdetails = $xlsx_data_new_allBody;



		// Build headers

		foreach( $cell_definition as $column => $value )

		{

			$objPHPExcel->getActiveSheet()->getColumnDimension("{$column}")->setAutoSize(true);

			$objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 

			$objPHPExcel->getActiveSheet()->getStyle( "{$column}1" )->getFont()->setBold( true );

		}  
      $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(false);
      $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(14);


		// Build cells



		while( $rowCount < count($reportdetails) ){ 

			$cell = $rowCount + 2;

			$newCounter = 0;

			foreach( $cell_definition as $column => $value ) {

				//$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 

				$objPHPExcel->getActiveSheet()->getStyle($column.$cell)->applyFromArray(

					array(

						'borders' => array(

						'allborders' => array(

							'style' => PHPExcel_Style_Border::BORDER_THIN,

							'color' => array('rgb' => '000000')

						)

						)

					)

				);

				$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(100);       

				switch ($value) {

				
					case 'Image':

						if (file_exists($reportdetails[$rowCount][$newCounter])) {

							$objDrawing = new PHPExcel_Worksheet_Drawing();

							$objDrawing->setName('Customer Signature');

							$objDrawing->setDescription('Customer Signature');

						

							//Path to signature .jpg file

						$signature = $reportdetails[$rowCount][$newCounter];

							$objDrawing->setPath($signature);

							$objDrawing->setOffsetX(5);                     //setOffsetX works properly

							$objDrawing->setOffsetY(10);                     //setOffsetY works properly

							$objDrawing->setCoordinates($column.$cell);             //set image to cell 

							$objDrawing->setHeight(80);                     //signature height  

							$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 

							

								

						} else {

						//$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, "Image not found" ); 

						}

						break;



					default:

						$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, $reportdetails[$rowCount][$newCounter] ); 

						break;

				}
            if($column=="L"){
               $objPHPExcel->getActiveSheet()->setCellValue($column.$cell, "=IF(N$cell=\"\",0,N$cell)*F$cell*O$cell"); 
               $objPHPExcel->getActiveSheet()->getStyle($column.$cell)->getNumberFormat()->setFormatCode('0.00'); 

               $objPHPExcel->getActiveSheet()->getStyle($column.$cell)->applyFromArray(
                      array(
                          'fill' => array(
                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                              'color' => array('rgb' => 'DDEBF7')
                          )
                      )
                  );
            }
            if($column=="N"){
           

               $objPHPExcel->getActiveSheet()->getStyle($column.$cell)->applyFromArray(
                      array(
                          'fill' => array(
                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                              'color' => array('rgb' => 'ED7D31')
                          )
                      )
                  );
            }
				$newCounter++;

			}     //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);

				

			$rowCount++; 



		}  


		//$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);   


		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->setPreCalculateFormulas(true);
		ob_start();

		//ob_end_clean();

  
		$saveExcelToLocalFile1 = saveExcelToLocalFile1($objWriter);

		ob_end_clean();



		$response = array(

			'success' => true,

			'filename' => $saveExcelToLocalFile1['filename'],

			'url' => $saveExcelToLocalFile1['filePath']

		);




		echo json_encode($response);

	die();

}


add_action( 'wp_ajax_get_custom_filter_option_brand','get_custom_filter_option_brand' );

add_action( 'wp_ajax_nopriv_get_custom_filter_option_brand','get_custom_filter_option_brand' );

function get_custom_filter_option_brand(){   
   session_start();
   global $wpdb;

   $u =  wp_get_current_user();
   $um = $u->ID;
   $user_meta=get_userdata($um);
   $getGroupID = $wpdb->get_row("SELECT `group_id` from {$wpdb->prefix}groups_user_group WHERE `user_id` = '$um'");
   $mexicoUserGroupID = $getGroupID->group_id;    


   header("Content-Type: application/json");
   $getas = get_option('afpvu_user_role_visibility');
   $kk = maybe_unserialize($getas);
   $hideProductArr = array();

   $request= $_POST;

   if(!empty($request['attr_pa_brand'])){

      $hideCategoryArr = array();
      if(isset($_SESSION['abc']) && !empty($_SESSION['abc']))
      {
         if($kk[$_SESSION['abc']]['afpvu_show_hide_role'] == 'hide')
         {
            $hideProductArr = $kk[$_SESSION['abc']]['afpvu_applied_products_role']; 
            $hideCategoryArr = $kk[$_SESSION['abc']]['afpvu_applied_categories_role'];
         }
      }

      // if(!empty($hideCategoryArr)){
      //     $query_sql1 = $wpdb->get_results("SELECT product_ids FROM wp_ss22_bulk_filter_role_category_products WHERE role='".$_SESSION['abc']."' " );
      //     $categoryHideArr = array();
      //    foreach($query_sql1 as $key => $ddd){
      //       $categoryHideArr = unserialize($ddd->product_ids);
      //    }

      // }


      //hide by category 
      if(!empty($hideCategoryArr)){
         $get_variations_arr21 = array();
         $query_sql1 = "SELECT p_id FROM wp_ss22_bulk_filter_brand_custom_filter WHERE 1=1";

         if(count($hideCategoryArr) > 1){
            $extraQuery = "(";
            foreach($hideCategoryArr as $key => $dataVal ){
               if ($key === array_key_last($hideCategoryArr)) {
                  $or = "";
               }else{
                  $or = "OR";
               }
               $extraQuery .= " cat_id LIKE '%$dataVal%' ".$or;
            }
            $extraQuery .= ")";
            $query_sql1 .= " AND". $extraQuery;
         }else{
            $query_sql1 .= " AND cat_id LIKE '%$hideCategoryArr[0]%'";
         }

         $get_variations_arr21 = $wpdb->get_results(" ".$query_sql1."  " );
          

         $categoryHideArr = array();
         foreach($get_variations_arr21 as $key => $ddd){
            if(!in_array($ddd->p_id, $categoryHideArr)){
               array_push($categoryHideArr, $ddd->p_id);
            }
         }
      } 


      $hideProductAllinOneArr = array();
      if(!empty($hideProductArr) && !empty($categoryHideArr)){
         $hideProductAllinOneArr = array_merge($hideProductArr, $categoryHideArr);
      }else if(!empty($hideProductArr) && empty($categoryHideArr)  ){
          $hideProductAllinOneArr = $hideProductArr;
      }else if(!empty($categoryHideArr) && empty($hideProductArr)  ){
          $hideProductAllinOneArr = $categoryHideArr;
        // print_r($categoryHideArr);
      }else{
         $hideProductAllinOneArr = array();
      }

   }

   $namee = $request['attr_pa_brand'] ;   



   $attr_pa_color_name = $request['attr_pa_color'];   

   $attr_pa_team_name = $request['attr_pa_team'];   

   $attr_pa_cat_name = $request['attr_pa_cat'];   

   $attr_pa_season_name = $request['attr_pa_season'];   

   
   $attr_pa_cat_arr = array();
   $attr_pa_color_arr = array();
   $attr_pa_team_arr = array();
   $attr_pa_season_arr = array();
   if($request['attr_pa_brand']){

      $query_sql = "SELECT * FROM wp_ss22_bulk_filter_brand_custom_filter WHERE 1=1";
      if(!empty($request['attr_pa_brand'])){
         $query_sql .= " AND brand_id IN($namee)";
      }
      if(!empty($request['attr_pa_cat'])){
         $attr_pa_cat_name_arr = explode(",", $attr_pa_cat_name);
         if(count($attr_pa_cat_name_arr) > 1){
            $extraQuery = "(";
            foreach($attr_pa_cat_name_arr as $key => $dataVal ){
               if ($key === array_key_last($attr_pa_cat_name_arr)) {
                  $or = "";
               }else{
                  $or = "OR";
               }
               $extraQuery .= " cat_id LIKE '%$dataVal%' ".$or;
            }
            $extraQuery .= ")";
            $query_sql .= " AND". $extraQuery;
         }else{
            $query_sql .= " AND cat_id LIKE '%$attr_pa_cat_name%'";
         }
        
         
      }
      if(!empty($request['attr_pa_color'])){
        
         $query_sql .= " AND FIND_IN_SET(color_id,'$attr_pa_color_name')";
      }
      if(!empty($request['attr_pa_team'])){
         $query_sql .= " AND FIND_IN_SET(team,'$attr_pa_team_name')";
      }
      if(!empty($request['attr_pa_season'])){
         $query_sql .= " AND FIND_IN_SET(season,'$attr_pa_season_name')"; 
      }

     
   }else{
      $query_sql = "";
   }

   // echo "<pre>";
   // print_r($query_sql);
   // die;

   if($request['attr_pa_brand']){
      if(!empty($request['attr_pa_cat']) || !empty($request['attr_pa_color']) ||  !empty($request['attr_pa_team']) || !empty($request['attr_pa_season']) ) {
         $get_variations_arr2 = $wpdb->get_results(" ".$query_sql."   " );
      }else{
          $get_variations_arr2 = $wpdb->get_results(" ".$query_sql." ORDER BY id ".$request['order'][0]['dir']."  LIMIT ".$request['length']." OFFSET ".$request['start']."  " );
         
      }


      
      $get_variations_search_arr2 = array();

      if( !empty($request['search']['value']) ) {

         foreach($get_variations_arr2 as $key => $data_record){

            $serchText = sanitize_text_field($request['search']['value']);



            $variation_id = $data_record->v_id;

            $_product =  wc_get_product( $variation_id);

            $main_product = wc_get_product( $_product->get_parent_id() );
            
            

            $image_id         = $_product->get_image_id();

            $gallery_thumbnail   = wc_get_image_size( array(100, 100) );

            $thumbnail_size      = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );

            $thumbnail_src       = wp_get_attachment_image_src( $image_id, $thumbnail_size );





            $product_title =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) ;

            $product_sku =  $_product->get_sku();

            $delivery_date =  $main_product->get_attribute( 'pa_delivery-date' );

            $prod_brand =  $main_product->get_attribute( 'pa_brand' );

            $price =  $_product->get_price();



            if (strpos( strtolower($product_title), strtolower($serchText) ) !== false) {

               $get_variations_search_arr2[] = $data_record;

            }else if (strpos( strtolower($product_sku), strtolower($serchText) ) !== false) {

               $get_variations_search_arr2[] = $data_record;

            }else if (strpos( strtolower($delivery_date), strtolower($serchText) ) !== false) {

               $get_variations_search_arr2[] = $data_record;

            }else if (strpos( strtolower($prod_brand), strtolower($serchText) ) !== false) {

               $get_variations_search_arr2[] = $data_record;

            }else if (strpos( strtolower($price), strtolower($serchText) ) !== false) {

               $get_variations_search_arr2[] = $data_record;

            }



         }

      }

      if(!empty($get_variations_search_arr2)){
         $get_variations_arr2 = $get_variations_search_arr2;
      }

      
      
     if(!empty($request['attr_pa_cat']) || !empty($request['attr_pa_color']) ||  !empty($request['attr_pa_team']) || !empty($request['attr_pa_season']) ) {
         $totalData = 0;
      }else{
           $totalData = count($get_variations_arr2);
      }

      foreach($get_variations_arr2 as $key => $data_record){

         $variation_id = $data_record->v_id;

         $_product =  wc_get_product( $variation_id);

         if( !in_array($_product->get_parent_id(), $hideProductAllinOneArr) ){

            
            $main_product = wc_get_product( $_product->get_parent_id() );

            $image_id         = $_product->get_image_id();
            
            $terms1 = get_the_terms( $_product->get_parent_id() , 'product_cat' );


            $apk = '';
            foreach ($terms1 as $term1) {
               if($term1->parent == 3259)
               {
                  $apk = $term1->name;
               }
            }

            $gallery_thumbnail   = wc_get_image_size( array(100, 100) );

            $thumbnail_size      = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );

            $thumbnail_src       = wp_get_attachment_image_src( $image_id, $thumbnail_size );



            $getVariationBack = get_post_meta($variation_id, 'woo_variation_gallery_images');

            $imageSrc = array();

            foreach($getVariationBack as $varKey => $varVal){

               foreach($varVal as $key44 => $value44) {

                  $imageId = $value44; 

                  $thumbnail_src1   = wp_get_attachment_image_src( $imageId, $thumbnail_size );

                  $imageSrc[] = $thumbnail_src1[0];

               }

            }

            

            $c1 = 0;

            $c5 = 0;

            $last = 0;

            $variation_size = wc_get_order_item_meta( $variation_id, 'item_variation_size', true );

            $ap = wc_get_order_item_meta( $variation_id, '_qty', true );

            foreach ($variation_size as $key45 => $size) 

            {

               $c1 += $size['value'];

               $merge1[$key][$size['label']][] = $ap * $size['value'];

            }

            

            $sum += $c1 * $ap;

            $merge2 = $c1;

            $getVarID = $variation_id;

            $row5 = "<div class='cart-sizes-attribute'>";

            $row5 .= '<div class="size-guide"><h5>Sizes</h5>';


            $ak = 0;
            $ak1 = 0;
            if(get_post_meta( $getVarID, 'custom_field1', true ) && get_post_meta( $getVarID, 'size_box_qty1', true ))
            {
               $ak = intval(get_post_meta( $getVarID, 'size_box_qty1', true ));
               $ak1 = intval(get_post_meta( $getVarID, 'size_box_qty1', true )) * $getA;
               $row5 .= "<div class='inner-size'><span>" . get_post_meta( $getVarID, 'custom_field1', true )  . "</span><span>" . get_post_meta( $getVarID, 'size_box_qty1', true ) . "</span></div>";                        
            }
            if(get_post_meta( $getVarID, 'custom_field2', true ) && get_post_meta( $getVarID, 'size_box_qty2', true ))
            {
               $ak += intval(get_post_meta( $getVarID, 'size_box_qty2', true ));
               $ak1 += intval(get_post_meta( $getVarID, 'size_box_qty2', true )) * $getA;
               $row5 .= "<div class='inner-size'><span>" . get_post_meta( $getVarID, 'custom_field2', true )  . "</span><span>" . get_post_meta( $getVarID, 'size_box_qty2', true ) . "</span></div>";
            }
            if(get_post_meta( $getVarID, 'custom_field3', true ) && get_post_meta( $getVarID, 'size_box_qty3', true ))
            {
               $ak += intval(get_post_meta( $getVarID, 'size_box_qty3', true ));
               $ak1 += intval(get_post_meta( $getVarID, 'size_box_qty3', true )) * $getA;
               $row5 .= "<div class='inner-size'><span>" . get_post_meta( $getVarID, 'custom_field3', true )  . "</span><span>" . get_post_meta( $getVarID, 'size_box_qty3', true ) . "</span></div>";
            }
            if(get_post_meta( $getVarID, 'custom_field4', true ) && get_post_meta( $getVarID, 'size_box_qty4', true ))
            {
               $ak += intval(get_post_meta( $getVarID, 'size_box_qty4', true ));
               $ak1 += intval(get_post_meta( $getVarID, 'size_box_qty4', true )) * $getA;
               $row5 .= "<div class='inner-size'><span>" . get_post_meta( $getVarID, 'custom_field4', true )  . "</span><span>" . get_post_meta( $getVarID, 'size_box_qty4', true ) . "</span></div>";
            }
            if(get_post_meta( $getVarID, 'custom_field5', true ) && get_post_meta( $getVarID, 'size_box_qty5', true ))
            {
               $ak += intval(get_post_meta( $getVarID, 'size_box_qty5', true ));
               $ak1 += intval(get_post_meta( $getVarID, 'size_box_qty5', true )) * $getA;
               $row5 .= "<div class='inner-size'><span>" . get_post_meta( $getVarID, 'custom_field5', true )  . "</span><span>" . get_post_meta( $getVarID, 'size_box_qty5', true ) . "</span></div>";
            }
            if(get_post_meta( $getVarID, 'custom_field6', true ) && get_post_meta( $getVarID, 'size_box_qty6', true ))
            {
               $ak += intval(get_post_meta( $getVarID, 'size_box_qty6', true ));
               $ak1 += intval(get_post_meta( $getVarID, 'size_box_qty6', true )) * $getA;
               $row5 .= "<div class='inner-size'><span>" . get_post_meta( $getVarID, 'custom_field6', true )  . "</span><span>" . get_post_meta( $getVarID, 'size_box_qty6', true ) . "</span></div>";
            }
            if(get_post_meta( $getVarID, 'custom_field7', true ) && get_post_meta( $getVarID, 'size_box_qty7', true ))
            {
               $ak += intval(get_post_meta( $getVarID, 'size_box_qty7', true ));
               $ak1 += intval(get_post_meta( $getVarID, 'size_box_qty7', true )) * $getA;
               $row5 .= "<div class='inner-size'><span>" . get_post_meta( $getVarID, 'custom_field7', true )  . "</span><span>" . get_post_meta( $getVarID, 'size_box_qty7', true ) . "</span></div>";
            }
            if(get_post_meta( $getVarID, 'custom_field8', true ) && get_post_meta( $getVarID, 'size_box_qty8', true ))
            {
               $ak += intval(get_post_meta( $getVarID, 'size_box_qty8', true ));
               $ak1 += intval(get_post_meta( $getVarID, 'size_box_qty8', true )) * $getA;
               $row5 .= "<div class='inner-size'><span>" . get_post_meta( $getVarID, 'custom_field8', true )  . "</span><span>" . get_post_meta( $getVarID, 'size_box_qty8', true ) . "</span></div>";
            }
            if(get_post_meta( $getVarID, 'custom_field9', true ) && get_post_meta( $getVarID, 'size_box_qty9', true ))
            {
               $ak += intval(get_post_meta( $getVarID, 'size_box_qty9', true ));
               $ak1 += intval(get_post_meta( $getVarID, 'size_box_qty9', true )) * $getA;
               $row5 .= "<div class='inner-size'><span>" . get_post_meta( $getVarID, 'custom_field9', true )  . "</span><span>" . get_post_meta( $getVarID, 'size_box_qty9', true ) . "</span></div>";
            }
            if(get_post_meta( $getVarID, 'custom_field10', true ) && get_post_meta( $getVarID, 'size_box_qty10', true ))
            {
               $ak += intval(get_post_meta( $getVarID, 'size_box_qty10', true ));
               $ak1 += intval(get_post_meta( $getVarID, 'size_box_qty10', true )) * $getA;
               $row5 .= "<div class='inner-size'><span>" . get_post_meta( $getVarID, 'custom_field10', true )  . "</span><span>" . get_post_meta( $getVarID, 'size_box_qty10', true ) . "</span></div>";
            }

            $row5 .= "<div class='inner-size " . (($ak == 0)  ? 'hide-it' : 'no-hide-it')  . "'><span>Total</span><span>" . $ak . "</span></div></div>";
         
            $row5 .= "</div>";
            $row5 .= "</div>";


            if(get_user_meta( $um, 'customer_margin', true))
            {
               $getMargin = get_user_meta( $um, 'customer_margin', true);
               if(get_user_meta( $um, 'customer_iva_margin', true)){
                  $getMargin = $getMargin + get_user_meta( $um, 'customer_iva_margin', true);
               }
               $discountRule = (100 - $getMargin) / 100;

               //echo $discountRule;
            }
            else
            {
               $discountRule = 1;
            }
            if($mexicoUserGroupID == 2)
            {
               //echo $discountRule;
               $getGroupPrice = $wpdb->get_row("SELECT `price` from {$wpdb->prefix}wusp_group_product_price_mapping WHERE `group_id` = '$mexicoUserGroupID' AND `product_id` = $variation_id");
               $price        = $getGroupPrice->price * $discountRule;         
               $priceCustomCalc        = $getGroupPrice->price;         
               $price_html   = wc_price($priceCustomCalc * $discountRule);
            }
            else if($user_meta->roles[0] == 'custom_role_puerto_rico')
            {
               //echo $discountRule;
               
               $price        = $_product->get_price() * 1.25;         
               $price_html   = wc_price($_product->get_price() * 1.25);
            }
            else
            {
               $price        = $_product->get_price();
               $price_html   = $_product->get_price_html();
            }


         
            $nestedData = array();

            $nestedData['select_checkbox'] = $variation_id.'##'.$_product->get_parent_id();

            $nestedData['product_image'] =  $thumbnail_src[0] ."## " . implode(',',$imageSrc)  ;

            $nestedData['product_title' ] = $_product->get_permalink(). '##' . $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' )  .'##' . $row5 ;

            $nestedData['product_sku' ] =  $_product->get_sku();

            $nestedData['delivery_date' ] =  $main_product->get_attribute( 'pa_delivery-date' );
            
            $nestedData['prod_gender' ] =  $apk;

            $nestedData['prod_brand' ] =  $main_product->get_attribute( 'pa_brand' );

            $nestedData['price' ] =  $price_html;

            $nestedData['qty' ] =  '<div class="quantity wcpt-quantity wcpt-noselect wcpt-display-type-input wcpt-controls-on-edges wcpt-hide-browser-controls wcpt-1628165232012 buttons_added"><button type="button" value="-" class="minus">-</button><span class="wcpt-minus wcpt-qty-controller wcpt-noselect"></span><input type="number" id="quantity_612487f323760" class="input-text qty text" step="1" min="1" data-wcpt-min="1" data-wcpt-return-to-initial="1" data-wcpt-reset-on-variation-change="" max="" name="quantity" value="1" title="Quantity" size="4" data-wcpt-initial-value="min" pattern="[0-9]*" inputmode="numeric" aria-labelledby="'.$_product->get_title().'" autocomplete="off"><span class="wcpt-plus wcpt-qty-controller wcpt-noselect"></span><div class="wcpt-quantity-error-message wcpt-quantity-error-message--max">Max: <span class="wcpt-quantity-error-placeholder--max"></span></div><div class="wcpt-quantity-error-message wcpt-quantity-error-message--min">Min: <span class="wcpt-quantity-error-placeholder--min">1</span></div><div class="wcpt-quantity-error-message wcpt-quantity-error-message--step">Step: <span class="wcpt-quantity-error-placeholder--step">1</span></div><button type="button" value="+" class="plus">+</button></div>';

            //$nestedData['edit_option' ] =  '<a class="single_add_to_cart_button" data-wcpt-link-code="cart_ajax" href="https://shop2.fexpro.com/?add-to-cart='.$_product->get_parent_id().'&variation_id='.$variation_id.'&quantity=1" >Add to Cart</a> ';

            if(get_post_meta($variation_id,'_stock_status',true) != 'outofstock'){
               if(intval($price) === 0 ){
                  $nestedData['edit_option' ] = '<a href="javascript:void(0)" class="ajax_add_to_cart add_to_cart_button single_add_to_cart_button outOfStock" disabled="disabled" > Zero Price </a>';
               }else{
                  $nestedData['edit_option' ] = '<a href="'.$_product->add_to_cart_url().'" value="'.esc_attr( $_product->get_parent_id() ).'" class="ajax_add_to_cart add_to_cart_button single_add_to_cart_button" data-product_id="'.$variation_id .'" data-product_sku="'.esc_attr($_product->get_sku()).'" data-quantity="1"  > Add to Cart </a>' ;

               }
               
            }else{

               $nestedData['edit_option' ] = '<a href="javascript:void(0)" class="ajax_add_to_cart add_to_cart_button single_add_to_cart_button outOfStock" disabled="disabled" > Out Of Stock </a>';

            }

            



            $data[] = $nestedData;

         }

      }
       
      if($data != null || !empty($data)){

         $json_data = array(

            "draw" => intval($request['draw']),

            "recordsTotal" => intval($totalData),

            "recordsFiltered" => intval($totalData),

            "data" => $data

           );

        

      }else{

         $json_data = array(

            "draw" => intval($request['draw']),

            "recordsTotal" => intval($totalData),

            "recordsFiltered" => intval($totalData),

            "data" => array()

           );

      }

        echo json_encode($json_data);

   }else{

      $json_data = array(

            "draw" => intval($request['draw']),

            "recordsTotal" => intval(0),

            "recordsFiltered" => intval(0),

            "data" => array()

           );


      echo json_encode($json_data);

   }


   wp_die();

}
