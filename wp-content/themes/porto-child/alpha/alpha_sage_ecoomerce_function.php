<?php 

// alpha create new factory table
add_action( 'wp_ajax_alpha_custom_add_factory','alpha_custom_add_factory' );
add_action( 'wp_ajax_nopriv_alpha_custom_add_factory','alpha_custom_add_factory' );
function alpha_custom_add_factory(){
	global $wpdb;

	if(!empty($_REQUEST['fcode']))         { $fcode = $_REQUEST['fcode']; }  else { $fcode = ''; }
	if(!empty($_REQUEST['fname']))         { $fname = trim($_REQUEST['fname']); } else { 	$fname = ''; }
   if(!empty($_REQUEST['supplier_slug'])) { $supplier_slug = trim($_REQUEST['supplier_slug']);  }  else { $supplier_slug = '';  }
	if(!empty($_REQUEST['faddress']))      { $faddress = $_REQUEST['faddress']; } else { 	$faddress = ''; }
	if(!empty($_REQUEST['fperson']))       { $fperson = $_REQUEST['fperson']; } else { 	$fperson = ''; }
	if(!empty($_REQUEST['fphone1']))       { $fphone1 = $_REQUEST['fphone1']; } else { $fphone1 = ''; }
	if(!empty($_REQUEST['fphone2']))       { $fphone2 = $_REQUEST['fphone2']; } else { $fphone2 = ''; }
   if(!empty($_REQUEST['femail'])) 	      { $femail = $_REQUEST['femail']; } else { $femail = ''; }

   $slug_data= sanitize_title_with_dashes( $_REQUEST['fname']);
   $supplier_slug = str_replace("-", "_", $slug_data);

   $file_name = $supplier_slug.'.php';
   $file_name1 = $supplier_slug.'.php';

   //CreateNewFileInToDirecotory($file_name, $_REQUEST['fname']))
   


   if(!empty($fname)){

       $query = $wpdb->prepare('SELECT supplier_name FROM alpha_wp_factory_list WHERE supplier_name = %s', $fname);
       $cID = $wpdb->get_var( $query );
       if ( !empty($cID) ) {
             echo "Not inserted";
       } else {                

            $wpdb->insert("alpha_wp_factory_list", array(
               'sage_code' => $fcode,
               'sage_order_number' => '',
               'supplier_name' => $fname,
               'supplier_slug' => $supplier_slug.'.php',
               'address' => $faddress,
               'contact_person' => $fperson,
               'phone_no' => $fphone1,
               'phone_no2' => $fphone2,
               'email_address' => $femail,
            ));


            $content = "";
            $fp = fopen($_SERVER['DOCUMENT_ROOT']. "/ss22/factory/$file_name","wb");
            fwrite($fp,$content);
            fclose($fp);

            copy($_SERVER['DOCUMENT_ROOT']. "/ss22/factory/text_demo.php" , $_SERVER['DOCUMENT_ROOT']. "/ss22/factory/$file_name");


            $path_to_file = $_SERVER['DOCUMENT_ROOT']. "/ss22/factory/$file_name";
            $write_file = file_get_contents($path_to_file);
            $replace_word = str_replace("eastman",$fname,$write_file);
            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/ss22/factory/$file_name1","wb");
            fwrite($fp,$replace_word);
            fclose($fp);
            


            $old_file = $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/porto-child/kk_exim_order_lists.php';
            $write_file = file_get_contents($old_file);
            $replace_word = str_replace("K.K. Exim",$fname,$write_file);
            $file_name = $fname.'.php';
            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/php/$file_name","wb");
            fwrite($fp,$replace_word);
            fclose($fp);
            echo "inserted";
            
         }
   }else{
      echo "Not inserted";
   }
  


	


	die();	
}


// Alpha adding factroy data 

add_action( 'wp_ajax_alpha_adding_ss22_factory_data','alpha_adding_ss22_factory_data' );
add_action( 'wp_ajax_nopriv_alpha_adding_ss22_factory_data','alpha_adding_ss22_factory_data' );
function alpha_adding_ss22_factory_data(){
   global $wpdb;
   $date=date('Y-m-d');
   
   $getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];
   $getCurrentRowFactoryUnits = $_REQUEST['getCurrentRowFactoryUnits'];
   $getCurrentRowFactoryOrder = $_REQUEST['getCurrentRowFactoryOrder'];
   
   $checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM alpha_wp_factory_order_confirmation_list WHERE `vid`= '$getCurrentRowVariationID' AND `forderid` = '$getCurrentRowFactoryOrder' ");
   if($checkdataExist == 1)
   {
      $wpdb->update( 
         "alpha_wp_factory_order_confirmation_list", 
         array( 
            'forderunits' => $getCurrentRowFactoryUnits,       
            'new' => '',         
            'update' => 'updated',        
            'update_date' => $date,       
         ), 
         array( 'vid' => $getCurrentRowVariationID ), 
         array( '%s','%s','%s','%s','%s','%s','%s','%s' ), 
         array( '%d' ) 
      );
   }
   else
   {  
      $wpdb->insert("alpha_wp_factory_order_confirmation_list", array(
         'vid' => $getCurrentRowVariationID,
         'forderid' => $getCurrentRowFactoryOrder,
         'forderunits' => $getCurrentRowFactoryUnits,
         'fnumber' => '',
         'factoryname' => '',
         'deliverydate' => '',
         'costprice' => '',
         'new' => 'New entry',
         'new_insert_date' => $date,
         'update' => '',
         'update_date' => '',
      ));
   } 
   
   $getallOrdersNumbers = $wpdb->get_results("SELECT DISTINCT `forderid` FROM alpha_wp_factory_order_confirmation_list", ARRAY_A );   
   echo  "<option value=''>Select Order No.</option>";
   foreach($getallOrdersNumbers as $value)
   {
      echo "<option value='" . $value['forderid'] . "'>" . $value['forderid'] . "</option>";
   }
   echo "</select'>";
  
   die();
}

 
// GET Alpha ss22 place order screen data get from table 
add_action('wp_ajax_movie_datatables', 'datatables_server_side_callback');
add_action('wp_ajax_nopriv_movie_datatables', 'datatables_server_side_callback');


function datatables_server_side_callback() {
  global $wpdb;	

  header("Content-Type: application/json");
	return;
  $request= $_POST;
	// echo "<pre>";
	// print_r($request);
	// die;

  $getallOrdersNumbers = $wpdb->get_results("SELECT DISTINCT `forderid` FROM alpha_wp_factory_order_confirmation_list", ARRAY_A );
	$return_array3 = "<select class='onumbers_lists'>";
	$return_array3 .= "<option value=''>Select Order No.</option>";
	foreach($getallOrdersNumbers as $value)
	{
		$return_array3 .= "<option value='" . $value['forderid'] . "'>" . $value['forderid'] . "</option>";
	}
	$return_array3 .= "</select'>";
  
	$get_total_records = $wpdb->get_results("SELECT `vid` from  {$wpdb->prefix}run_time_v_id Order by id asc ");
 
	$totalData = count($get_total_records);

  if($request['length'] == -1){
	$request['length'] = $totalData;
  }
  $get_variations_arr = $wpdb->get_results("SELECT `vid`, `item_id` from  {$wpdb->prefix}run_time_v_id Order by id ".$request['order'][0]['dir']." LIMIT ".$request['length']." OFFSET ".$request['start']."  ");


  if( !empty($request['search']['value']) ) { 
	$get_variations_arr1 = $wpdb->get_results("SELECT `vid`, `item_id` from  {$wpdb->prefix}run_time_v_id Order by id ".$request['order'][0]['dir']." LIMIT ".$request['length']." OFFSET ".$request['start']."  ");
	foreach($get_variations_arr1 as $key => $data_record){
		$variation_id = $data_record->vid;
		$_product =  wc_get_product( $variation_id);
		$serchText = sanitize_text_field($request['search']['value']);
		
		
		$itemsIdsArr = explode(",", $data_record->item_id);
		$sum = 0;
		$d = 0;
		foreach($itemsIdsArr as $key4 => $abc)
		{
			$c1 = 0;
			$c5 = 0;
			$last = 0;
				$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
				$ap = wc_get_order_item_meta( $abc, '_qty', true );
				foreach ($variation_size as $key45 => $size) 
				{
					$c1 += $size['value'];
					$merge1[$key][$size['label']][] = $ap * $size['value'];
				}
				
				$sum += $c1 * $ap;
				$merge2 = $c1;
		
			//echo "<p>" . $key4 . " " . $sum . "</p>";
		}
		
		$checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM alpha_wp_factory_order_confirmation_list WHERE `vid`= '$variation_id'");
		$getQtyRemaining = $wpdb->get_row( "SELECT * FROM alpha_wp_factory_order_confirmation_list WHERE vid = $variation_id" );
		
		
		if($sum >= $getQtyRemaining->forderunits )
		{
			$aq = $sum - $getQtyRemaining->forderunits;
		}
		else
		{
			$aq = 0;
		}
		if($checkdataExist == 1)
		{				
		$qty = $getQtyRemaining->fnumber;
		}
		else
		{
			$qty = '';
		}

		$_product =  wc_get_product( $variation_id);
		$main_product = wc_get_product( $_product->get_parent_id() );
		$image_id			= $_product->get_image_id();
		$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
		$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
		$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

		$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
		$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));

		$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
		$css_slugGender = array();
		$css_slugCategory = array();
		$css_slugSubCategory = array();
		//print_r($cat);
		foreach($cat as $cvalue)
		{
			if($cvalue->parent != 0)
			{
				$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
				$css_slugSubCategory[] = $cvalue->name;
				$css_slugCategory[] = $term->name;
				if($cvalue->parent == '1818')
				{
					$css_slugGender[] = $cvalue->name;
				}
			}
			else
			{
				if($cvalue->name == 'All Mens')
				{
					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
				}
				elseif($cvalue->name == 'All Womens')
				{
					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
				}
			}
		}

		$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
		$array_logo = array();
		if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
		if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
		if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
		if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
		
		$logoApplicationString = implode(', ', $array_logo);
		if($getQtyRemaining->forderunits >= $sum){
			$stockQty = $getQtyRemaining->forderunits - $sum;
		}else if($getQtyRemaining->forderunits <= $sum){
			$stockQty = 0;
		}else{
			$stockQty = 0;
		}


		$item_name =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) ;
		$product_sku =  $_product->get_sku();
		$delivery_date =  $main_product->get_attribute( 'pa_delivery-date' );
		$prod_brand =  $main_product->get_attribute( 'pa_brand' );
		$gender =  implode(", ", $css_slugGender);
		$category =  implode(", ", $css_slugCategory);
		$sub_category =  implode(", ", $css_slugSubCategory);
		$season =   $main_product->get_attribute( 'pa_season' );
		$composition =  $fabricCompositionString;
		$producto_logo =  $logoApplicationString;
		$unit_sold =  $sum;
		$factory_order =  $getQtyRemaining->forderunits;
		$open_units =  $aq;
		$stock_qty =  $stockQty;
		$order_number = $getQtyRemaining->forderid;

		if (strpos( strtolower($item_name), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($product_sku), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($delivery_date), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($prod_brand), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($gender), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($category), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($sub_category), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($season), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($composition), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($producto_logo), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($unit_sold), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($factory_order), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($open_units), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($stock_qty), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($order_number), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else{}
			
		
	}
	
	
  }else{}
  

  
	if(!empty($get_variations_arr2)){
		$get_variations_arr = $get_variations_arr2;
	}
	
  if ( !empty ($get_variations_arr ) ) {
    
    foreach($get_variations_arr as $key => $data_record){
		$variation_id = $data_record->vid;
		$itemsIdsArr = explode(",", $data_record->item_id);
		$sum = 0;
		$d = 0;
		foreach($itemsIdsArr as $key4 => $abc)
		{
			$c1 = 0;
			$c5 = 0;
			$last = 0;
				$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
				$ap = wc_get_order_item_meta( $abc, '_qty', true );
				foreach ($variation_size as $key45 => $size) 
				{
					$c1 += $size['value'];
					$merge1[$key][$size['label']][] = $ap * $size['value'];
				}
				
				$sum += $c1 * $ap;
				$merge2 = $c1;
		
			//echo "<p>" . $key4 . " " . $sum . "</p>";
		}
		
		$checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM alpha_wp_factory_order_confirmation_list WHERE `vid`= '$variation_id'");
		$getQtyRemaining = $wpdb->get_row( "SELECT * FROM alpha_wp_factory_order_confirmation_list WHERE vid = $variation_id" );
		
		
		if($sum >= $getQtyRemaining->forderunits )
		{
			$aq = $sum - $getQtyRemaining->forderunits;
		}
		else
		{
			$aq = 0;
		}
		if($checkdataExist == 1)
		{				
		$qty = $getQtyRemaining->fnumber;
		}
		else
		{
			$qty = '';
		}

		$_product =  wc_get_product( $variation_id);
		$main_product = wc_get_product( $_product->get_parent_id() );
		 $image_id			= $_product->get_image_id();
		 $gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
		 $thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
		 $thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

		$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
		$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));

		$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
		$css_slugGender = array();
		$css_slugCategory = array();
		$css_slugSubCategory = array();
		//print_r($cat);
		foreach($cat as $cvalue)
		{
			if($cvalue->parent != 0)
			{
				$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
				$css_slugSubCategory[] = $cvalue->name;
				$css_slugCategory[] = $term->name;
				if($cvalue->parent == '1818')
				{
					$css_slugGender[] = $cvalue->name;
				}
			}
			else
			{
				if($cvalue->name == 'All Mens')
				{
					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
				}
				elseif($cvalue->name == 'All Womens')
				{
					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
				}
			}
		}

		$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
		$array_logo = array();
		if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
		if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
		if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
		if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
		
		$logoApplicationString = implode(', ', $array_logo);
		
		$row3 = "<div class='cart-sizes-attribute'>";
		$row3 .= '<div class="size-guide"><h5>Sizes</h5>';
			foreach ($merge1[$key] as $akkk => $akkkv) {
				$q  = 0;
				$row3 .= "<div class='inner-size'><span>" . $akkk  . "</span>";
				foreach($akkkv as $akkk1 => $akkkv1)
				{
					$q += $akkkv1;
				}
				$row3 .= "<span class='clr_val'>" . $q . "</span>";
				$row3 .= "</div>";
			}
		$row3 .= "</div>";
		$row3 .= "</div>";

		if($getQtyRemaining->forderunits >= $sum){
			$stockQty = $getQtyRemaining->forderunits - $sum;
		}else if($getQtyRemaining->forderunits <= $sum){
			$stockQty = 0;
		}else{
			$stockQty = 0;
		}
		
		$nestedData = array();
		if($getQtyRemaining->vid == $variation_id)
		{
			$nestedData['product_image'] = $thumbnail_src[0];
			$nestedData['item_name' ] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 ;
			$nestedData['product_sku' ] =  $_product->get_sku();
			$nestedData['delivery_date' ] =  $main_product->get_attribute( 'pa_delivery-date' );
			$nestedData['prod_brand' ] =  $main_product->get_attribute( 'pa_brand' );
			$nestedData['gender' ] =  implode(", ", $css_slugGender);
			$nestedData['category' ] =  implode(", ", $css_slugCategory);
			$nestedData['sub_category' ] =  implode(", ", $css_slugSubCategory);
			$nestedData['season' ] =   $main_product->get_attribute( 'pa_season' );
			$nestedData['composition' ] =  $fabricCompositionString;
			$nestedData['producto_logo' ] =  $logoApplicationString;
			$nestedData['unit_sold' ] =  $sum;
			$nestedData['factory_order' ] =  "<input type='number' name='factory_order' class='factory_order' data-variation_id ='" . $variation_id . "' data-minimum_units ='" . $merge2[$variation_id][0] . "' placeholder='Min 24 Units' value='".$getQtyRemaining->forderunits."'/> <span class='for-Excel-only'>".$getQtyRemaining->forderunits."</span>";
			$nestedData['open_units' ] =  $aq;
			$nestedData['stock_qty' ] =  $stockQty;
			$nestedData['order_number' ] =  "<span class='order1-number2'>".$getQtyRemaining->forderid."</span>";
			$nestedData['edit_option' ] =  "<a href='Javascript:void(0);' class='single-submit-it'><i class='ti-save'></i></a>";
		}else{

			$nestedData['product_image'] = $thumbnail_src[0] ;
			$nestedData['item_name' ] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 ;
			$nestedData['product_sku' ] =  $_product->get_sku();
			$nestedData['delivery_date' ] =  $main_product->get_attribute( 'pa_delivery-date' );
			$nestedData['prod_brand' ] =  $main_product->get_attribute( 'pa_brand' );
			$nestedData['gender' ] =  implode(", ", $css_slugGender);
			$nestedData['category' ] =  implode(", ", $css_slugCategory);
			$nestedData['sub_category' ] =  implode(", ", $css_slugSubCategory);
			$nestedData['season' ] =   $main_product->get_attribute( 'pa_season' );
			$nestedData['composition' ] =  $fabricCompositionString;
			$nestedData['producto_logo' ] =  $logoApplicationString;
			$nestedData['unit_sold' ] =  $sum;
			$nestedData['factory_order' ] =  "<input type='number' name='factory_order' class='factory_order' data-variation_id ='" . $variation_id . "' data-minimum_units ='" . $merge2[$variation_id][0] . "' placeholder='Min 24 Units' value='".$sum."'/> <span class='for-Excel-only'>".$sum."</span>";
			$nestedData['open_units' ] =  0;
			$nestedData['stock_qty' ] =  0;
			$nestedData['order_number' ] =  $return_array3 . "<input type='text' name='factory_order_number' class='factory_order_number' placeholder='fex0001'/><div class='add-new'>Add New</div> <span class='order1-number2'></span>";
			$nestedData['edit_option' ] =  "<a href='Javascript:void(0);' class='single-submit-it'><i class='ti-save'></i></a>";

		}

		$data[] = $nestedData;
    }

	
	
    $json_data = array(
      "draw" => intval($request['draw']),
      "recordsTotal" => intval($totalData),
      "recordsFiltered" => intval($totalData),
      "data" => $data
    );

    echo json_encode($json_data);

  } else {

    $json_data = array(
      "data" => array()
    );

    echo json_encode($json_data);
  }
  
  wp_die();

}

// GET Alpha ss22 factory Order Lists

add_action('wp_ajax_alpha_factory_order_list_datatables', 'alpha_factory_order_list_datatables');
add_action('wp_ajax_nopriv_alpha_factory_order_list_datatables', 'alpha_factory_order_list_datatables');

function alpha_factory_order_list_datatables() {
	global $wpdb;	
	$return_array = array();
	$return_array1 = array();
	$return_array2 = array();
	$return_array3 = array();
	$merge1 = array();
	$merge = array();
	header("Content-Type: application/json");
	$request= $_POST;

	$getAllSupplier = $wpdb->get_results("SELECT * FROM alpha_wp_factory_list", ARRAY_A );
	$return_array3 = "<select class='factory_name'>";
	$return_array3 .= "<option value=''>Select Factory</option>";
	foreach($getAllSupplier as $value)
	{
		$return_array3 .= "<option value='" . $value['supplier_name'] . "'>" . $value['supplier_name'] . "</option>";
	}
	$return_array3 .= "</select'>";

	$get_total_records = $wpdb->get_results("SELECT `id` from  alpha_wp_factory_order_confirmation_list Order by id asc ");
	$totalData = count($get_total_records);
	if($request['length'] == -1){
		$request['length'] = $totalData;
	}

	$get_variations_arr = $wpdb->get_results("SELECT * from  alpha_wp_factory_order_confirmation_list Order by id ".$request['order'][0]['dir']." LIMIT ".$request['length']." OFFSET ".$request['start']."  ");

	foreach($get_variations_arr as $abc)
	{
		
		$vID = $abc->vid;
		$variation = wc_get_product($abc->vid);
		$allData = $wpdb->get_results("SELECT `order_item_id`,`order_id`   FROM {$wpdb->prefix}wc_order_product_lookup WHERE `variation_id` = '$vID'", ARRAY_A );
		foreach($allData as $bk)
		{
			if ( get_post_status ( $bk['order_id'] ) != 'wc-presale3' ) 
			{
				continue;
			}
			else
			{
				$return_array1[$abc->vid][] = $bk['order_item_id'];
			}
		}
	
	
	}
	$j = 0;
	foreach($return_array1 as $key3 => $value3)
	{
		

		$sum = 0;
		foreach($value3 as $key4 => $abc)
		{
			$c1 = 0;
			
				$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
				$get_variation_id = wc_get_order_item_meta( $abc, '_variation_id', true );
				$ap = wc_get_order_item_meta( $abc, '_qty', true );
				if(empty($ap))
				{
					$ap = 0;
				}
				else
				{
					$ap = $ap;
				}
				if(!in_array($abc, $return_array2))
				{
					if($get_variation_id == $key3)
					{
						//echo $key3 . " - " . $abc . " - " . $ap . "<br>";
						
						foreach ($variation_size as $key => $size) 
						{
							
							$c1 += $size['value'];
							/* if(!in_array($label, $return_array3))
							{
								array_push($return_array3, $label);
							} */
							//echo $key3 . " - " . $size['label'] . " = " . $ap * $size['value'] . "<br>";
							$merge1[$key3][$size['label']][] = $ap * $size['value'];
							$merge3[$size['label']] = $size['label'];
						}
						
					}
					array_push($return_array2, $abc);
				}
				
				$sum += $c1 * $ap; 
				
			//echo "<p>" . $key4 . " " . $sum . "</p>";
		}
		$merge[$key3][] = $sum;
		
	}


	if ( !empty ($get_variations_arr ) ) {
		$kl = array();
		foreach($get_variations_arr as $key => $data_record){
			if(!empty(wc_get_product( $data_record->vid)))
			{
				$variation_id = $data_record->vid;
				$productParentId = wp_get_post_parent_id($variation_id);
				$_product =  wc_get_product( $variation_id);

				if($data_record->deliverydate == '0000-00-00'){
					$pa_delivery_date = wc_get_product_terms( $_product->get_parent_id(), 'pa_delivery-date' );
					$data_record->deliverydate = date("Y-m-d", strtotime($pa_delivery_date[0]->name));	
				}
				$file = get_field('custom_pdf', $productParentId);
				if(!empty($file))
				{
					$pdf = $file;
					$target = 'target="_blank"';
					$pdf1 = $file;
				}
				else
				{
					$pdf = "Javascript:void(0);";
					$pdf1 = '';
					$target = '';
				}

				
				$image_id			= $_product->get_image_id();
				$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
				$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
				$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

				$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
				$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));
				$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
				$Other_Row = "";
			}else{
				$_parent_product =  wc_get_product($_product->get_parent_id());
				if(!in_array($_parent_product->get_sku(), $kl))
				{
					$Other_Row =  "<span class='no-v1' style='display:none'>This Style SKU is not avialable in Presale3 anymore : <strong>". $_parent_product->get_sku() . "</strong>. Please remove from Factory Order List. <strong>(Marked with Red Color Row)</strong></span>";
				}
			}

			($merge[$data_record->vid][0] >= $data_record->forderunits ) ? $alk = $merge[$data_record->vid][0] - $data_record->forderunits : $alk = "0";

			$array_logo = array();
			if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
			if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
			if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
			if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
			
			$logoApplicationString = implode(', ', $array_logo);
	

			$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
			$css_slugGender = array();
			$css_slugCategory = array();
			$css_slugSubCategory = array();
			//print_r($cat);
			foreach($cat as $cvalue)
			{
				if($cvalue->parent != 0)
				{
					$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
					$css_slugSubCategory[] = $cvalue->name;
					$css_slugCategory[] = $term->name;
					
					
					if($cvalue->parent == '1818')
					{
						$css_slugGender[] = $cvalue->name;
					}
				}
				else
				{
					if($cvalue->name == 'All Mens')
					{
						$css_slugGender[] = str_replace('All ', '', $cvalue->name);
					}
					elseif($cvalue->name == 'All Womens')
					{
						$css_slugGender[] = str_replace('All ', '', $cvalue->name);
					}
					//$css_slugGender[] = $cvalue->name;
				}
			}

			

			$row3 = "<div class='cart-sizes-attribute'>";
			$row3 .= '<div class="size-guide"><h5>Sizes</h5>';
			if(!empty($merge1[$data_record->vid]))
			{
				$k = '';
				foreach ($merge1[$data_record->vid] as $akkk => $akkkv) {
					$q  = 0;
					$row3 .= "<div class='inner-size'><span>" . $akkk  . "</span>";
					foreach($akkkv as $akkk1 => $akkkv1)
					{
						$q += $akkkv1;
					}
					$row3 .= "<span class='clr_val'>" . $q . "</span>";
					$row3 .= "</div>";
				}
			}
			else
			{
				$k = 'red';
			}
			$row3 .= "</div>";
			$row3 .= "</div>";

			$nestedData = array();

			$nestedData['action_edit'] = "<a href='Javascript:void(0);' class='single-submit-it'><i class='ti-save'></i></a>".$Other_Row;
			$nestedData['action_delete'] = "<a href='Javascript:void(0);' class='single-delete-it'><i class='ti-trash'></i></a>";
			$nestedData['order_number'] = "<span class='onumber'> <input type='text' name='textContent'  value='" . $data_record->forderid . "' data-tabVid='".$data_record->vid."' disabled /> </span> <span class='EditOrderNumber'>Edit Order Number</span> ";
			$nestedData['product_image'] = $thumbnail_src[0];
			$nestedData['item_name' ] = $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3;
			$nestedData['product_sku' ] = $_product->get_sku();
			$nestedData['gender' ] = implode(", ", $css_slugGender);
			$nestedData['category' ] = implode(", ", $css_slugCategory) ;
			$nestedData['sub_category' ] = (!empty($css_slugSubCategory)) ? implode(", ", $css_slugSubCategory) : '';
			$nestedData['composition' ] = $fabricCompositionString;
			$nestedData['producto_logo' ] = $logoApplicationString;
			$nestedData['unit_sold' ] =  $merge[$data_record->vid][0];
			$nestedData['factory_order' ] = "<input type='number' name='factory_order' class='factory_order' data-variation_id ='" . $data_record->vid . "' value='" . $data_record->forderunits ."'/><span class='for-Excel-only'>" . $data_record->forderunits . "</span>" ;
			$nestedData['open_units' ] =   $alk;
			$nestedData['factory_name' ] =   $return_array3 . " <input type='hidden' /><span class='order1-number2'>" .$data_record->factoryname . "</span>" ;
			$nestedData['delivery_date' ] = "<input type='date' class='delivery-date' value='" . $data_record->deliverydate . "'/><span class='deliverydate-value'>". $data_record->deliverydate ."</span>" ;
			$nestedData['cost_price' ] = "<input type='number' class='cost-price' placeholder='$' value='" . $data_record->costprice . "'/><span class='costprice'>" . $data_record->costprice . "</span>";
			$nestedData['comments' ] =  "<textarea class='comments' placeholder='Add comments' l'>" . $data_record->comments . "</textarea><span class='comments-add'>" . $data_record->comments . "</span>";
			$nestedData['pdf_download' ] =  "<a href='$pdf' $target>Download</a><span class='pdf-add'>$pdf1</span>";
		
			$data[] = $nestedData;

		}

		

		$json_data = array(
			"draw" => intval($request['draw']),
			"recordsTotal" => intval($totalData),
			"recordsFiltered" => intval($totalData),
			"data" => $data
		);
	
		echo json_encode($json_data);

	}else{
		$json_data = array(
			"data" => array()
		);
	
		echo json_encode($json_data);
	}

wp_die();

}

add_action( 'wp_ajax_alpha_export_factory_order_list_data','alpha_export_factory_order_list_data' );
add_action( 'wp_ajax_nopriv_alpha_export_factory_order_list_data','alpha_export_factory_order_list_data' );
function alpha_export_factory_order_list_data(){ 
		
		global $wpdb;
		$url1 = site_url();
		$path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';
		$base_path = wp_upload_dir();
		$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';
		define('SITEURL', $url1);
		define('SITEPATH', str_replace('\\', '/', $path1));

		

		$dataHeader = json_decode(stripslashes($_POST['getHeaderArray']));
		if(!empty($_POST['hide_columns'])){
			$dataHeader1 = json_decode(stripslashes($_POST['getHeaderArray']));
			unset($dataHeader1[0]);
			unset($dataHeader1[1]);
			$dataHeader = array_values($dataHeader1);
		}

		
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
	


	//echo $getTotalCountHeader;
	if($_POST['exportData'] != 'All' ){
			$offset = ($_POST['exportData'] * $_POST['currentPage']) - $_POST['exportData'];
			$dataBody = $wpdb->get_results("SELECT * from  alpha_wp_ss22_factory_order_confirmation_list Order by id asc LIMIT ".$_POST['exportData']." OFFSET ".$offset."  ");
	}else{
			$dataBody = $wpdb->get_results("SELECT * from  alpha_wp_ss22_factory_order_confirmation_list Order by id asc ");
	}

	foreach($dataBody as $abc)
		{
			
			$vID = $abc->vid;
			$variation = wc_get_product($abc->vid);
			$allData = $wpdb->get_results("SELECT `order_item_id`,`order_id`   FROM {$wpdb->prefix}wc_order_product_lookup WHERE `variation_id` = '$vID'", ARRAY_A );
			foreach($allData as $bk)
			{
				if ( get_post_status ( $bk['order_id'] ) != 'wc-presale3' ) 
				{
					continue;
				}
				else
				{
					$return_array1[$abc->vid][] = $bk['order_item_id'];
				}
			}
		
		
		}
		$j = 0;
		foreach($return_array1 as $key3 => $value3)
		{
			

			$sum = 0;
			foreach($value3 as $key4 => $abc)
			{
				$c1 = 0;
				
					$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
					$get_variation_id = wc_get_order_item_meta( $abc, '_variation_id', true );
					$ap = wc_get_order_item_meta( $abc, '_qty', true );
					if(empty($ap))
					{
						$ap = 0;
					}
					else
					{
						$ap = $ap;
					}
					if(!in_array($abc, $return_array2))
					{
						if($get_variation_id == $key3)
						{
							//echo $key3 . " - " . $abc . " - " . $ap . "<br>";
							
							foreach ($variation_size as $key => $size) 
							{
								
								$c1 += $size['value'];
								/* if(!in_array($label, $return_array3))
								{
									array_push($return_array3, $label);
								} */
								//echo $key3 . " - " . $size['label'] . " = " . $ap * $size['value'] . "<br>";
								$merge1[$key3][$size['label']][] = $ap * $size['value'];
								$merge3[$size['label']] = $size['label'];
							}
							
						}
						array_push($return_array2, $abc);
					}
					
					$sum += $c1 * $ap; 
					
				//echo "<p>" . $key4 . " " . $sum . "</p>";
			}
			$merge[$key3][] = $sum;
			
		}

	$getTotalCountBody = count($dataBody);
	$kl = array();
	foreach($dataBody as $key => $data_record)
	{
			if(!empty(wc_get_product( $data_record->vid)))
			{
				$variation_id = $data_record->vid;
				$productParentId = wp_get_post_parent_id($variation_id);
				$_product =  wc_get_product( $variation_id);

				if($data_record->deliverydate == '0000-00-00'){
					$pa_delivery_date = wc_get_product_terms( $_product->get_parent_id(), 'pa_delivery-date' );
					$data_record->deliverydate = date("Y-m-d", strtotime($pa_delivery_date[0]->name));	
				}
				$file = get_field('custom_pdf', $productParentId);
				if(!empty($file))
				{
					$pdf = $file;
					$target = 'target="_blank"';
					$pdf1 = $file;
				}
				else
				{
					$pdf = "Javascript:void(0);";
					$pdf1 = '';
					$target = '';
				}

				
				$image_id			= $_product->get_image_id();
				$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
				$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
				$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

				$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
				$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));
				$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
				$Other_Row = "";
			}else{
				$_parent_product =  wc_get_product($_product->get_parent_id());
				if(!in_array($_parent_product->get_sku(), $kl))
				{
					$Other_Row =  "<span class='no-v1' style='display:none'>This Style SKU is not avialable in Presale3 anymore : <strong>". $_parent_product->get_sku() . "</strong>. Please remove from Factory Order List. <strong>(Marked with Red Color Row)</strong></span>";
				}
			}

			($merge[$data_record->vid][0] >= $data_record->forderunits ) ? $alk = $merge[$data_record->vid][0] - $data_record->forderunits : $alk = "0";

			$array_logo = array();
			if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
			if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
			if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
			if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
			
			$logoApplicationString = implode(', ', $array_logo);
		

			$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
			$css_slugGender = array();
			$css_slugCategory = array();
			$css_slugSubCategory = array();
			//print_r($cat);
			foreach($cat as $cvalue)
			{
				if($cvalue->parent != 0)
				{
					$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
					$css_slugSubCategory[] = $cvalue->name;
					$css_slugCategory[] = $term->name;
					
					
					if($cvalue->parent == '1818')
					{
						$css_slugGender[] = $cvalue->name;
					}
				}
				else
				{
					if($cvalue->name == 'All Mens')
					{
						$css_slugGender[] = str_replace('All ', '', $cvalue->name);
					}
					elseif($cvalue->name == 'All Womens')
					{
						$css_slugGender[] = str_replace('All ', '', $cvalue->name);
					}
					//$css_slugGender[] = $cvalue->name;
				}
			}

			$imageUrlThumb = str_replace("https://shop.fexpro.com", "",$thumbnail_src[0]);
			$imageUrlThumb1 = $_SERVER['DOCUMENT_ROOT'] . $imageUrlThumb;
		
			$nestedData = array();
		
			$nestedData[] =  $data_record->forderid;
			$nestedData[] =  $imageUrlThumb1;
			$nestedData[] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' );
			$nestedData[] =  $_product->get_sku();
			$nestedData[] =  implode(", ", $css_slugGender);
			$nestedData[] =  implode(", ", $css_slugCategory) ;
			$nestedData[] =  (!empty($css_slugSubCategory)) ? implode(", ", $css_slugSubCategory) : ''; 
			$nestedData[] =  $fabricCompositionString;
			$nestedData[] =  $logoApplicationString;
			$nestedData[] =  $merge[$data_record->vid][0];
			$nestedData[] =  $data_record->forderunits;
			$nestedData[] =  $alk;
			$nestedData[] =  $data_record->factoryname;
			$nestedData[] =  $data_record->deliverydate;
			$nestedData[] =  $data_record->costprice;
			$nestedData[] =  $data_record->comments;
			$nestedData[] =  $pdf1;
			$data[] = $nestedData;
		}


		$xlsx_data_new_allBody= $data;
	
		
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
				

				case 'Product image':
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
			$newCounter++;
		}     //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);
			
			$rowCount++; 
		
	}  

		//$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);   
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
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
		$objPHPExcel->disconnectWorksheets();
	
		unset($objPHPExcel);
		die();


}




add_action( 'wp_ajax_alpha_delete_fw22_single_factory_data','alpha_delete_fw22_single_factory_data' );

add_action( 'wp_ajax_nopriv_alpha_delete_fw22_single_factory_data','alpha_delete_fw22_single_factory_data' );

function alpha_delete_fw22_single_factory_data(){

   global $wpdb;
   $getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];
   $wpdb->query( 'DELETE  FROM '. $wpdb->prefix . 'alpha_wp_factory_order_confirmation_list WHERE vid = "'.$getCurrentRowVariationID.'"');

   

   echo "deleted";

   

   die();

}




add_action( 'wp_ajax_alpha_export_cart_entries_all_data','alpha_export_cart_entries_all_data' );

add_action( 'wp_ajax_nopriv_alpha_export_cart_entries_all_data','alpha_export_cart_entries_all_data' );

function alpha_export_cart_entries_all_data(){ 

   $url2 = site_url();

   $path2 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';

   $base_path = wp_upload_dir();

   $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';

   define('SITEURL', $url2);

   define('SITEPATH', str_replace('\\', '/', $path2));

   $dataHeader = json_decode(stripslashes($_POST['getHeaderArray']));

   $dataBody = array();//get_transient('getTableBodyData');

   $k = 1;

   $i = 0;

   $getTotalCountHeader = count($dataHeader);

   //echo $getTotalCountHeader;

   $getTotalCountBody = count($dataBody);


   $newLoop = $getTotalCountBody / $getTotalCountHeader;

   $count = 0;

   foreach($dataHeader as $keyHeader => $dHeader)

   {

      $xlsx_data_new_allHeader= array();

      $alpha = num_to_letters($keyHeader+1);

      

      $dataH["$alpha"] = $dHeader;

      

      array_push($xlsx_data_new_allHeader, $dataH);

      $k++;

   }



   $xlsx_data_new_allBody= array();

   foreach($dataBody as $keyBody => $dBody)

   {

      if(!in_array($dBody->data, $dataB))

      {

         if (strpos($dBody->data, 'uploads/') !== false) {

            

            $dp = $_SERVER['DOCUMENT_ROOT'] . $dBody->data;



         }

         else

         {

            $dp = $dBody->data;

         }

         $dataB[$dBody->Title][] = $dp;

      }

      

   }

   

   foreach($dataB as $kk => $vv)

   {

      $i = 0;

      foreach($vv as $ap)

      {

      $akp[$dataHeader[$i]] = $ap;

      //echo $dataAK[$dataHeader][$i];

      

      $i++;

      }

      if(!in_array($akp, $xlsx_data_new_allBody))

      {

         array_push($xlsx_data_new_allBody, $akp);

      }

   }





    $totalArr = array();

    $totalUnitPurchsedArr = array();

   foreach($xlsx_data_new_allBody as $key56 => $value56){

      foreach($value56 as $key60 => $value60){

         if($key60 == 'Total Value'){

            $totalArr[] = str_replace(',', '', $value60);

         }

         if($key60 == 'Total Unit Purchased'){

            $totalUnitPurchsedArr[] = str_replace(',', '', $value60);

         }

      }

   }



   setlocale(LC_MONETARY, 'en_IN');



   $totalValueLastColumn = array_sum($totalArr);

   $totalValueLastColumn = money_format('%!i', $totalValueLastColumn); 
   $totalUnitPurchsedArrColumn = array_sum($totalUnitPurchsedArr);

   $totalUnitPurchsedArrColumn = $totalUnitPurchsedArrColumn;





   $newDatURL = array();

   foreach($xlsx_data_new_allBody[0] as $key85 => $value85){

      if($key85 == 'Total Value'){

         $newDatURL[$key85] = '$'.$totalValueLastColumn;

      }else if($key85 == 'Total Unit Purchased'){

         $newDatURL[$key85] = $totalUnitPurchsedArrColumn;

      }else{

         $newDatURL[$key85] = '';

      }

       

   }

 

   array_push($xlsx_data_new_allBody, $newDatURL);



   /* print_r($reportdetails);

   die(); */

   /**/



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



   // Build cells

   while( $rowCount < count($reportdetails) ){ 

      $cell = $rowCount + 2;

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

            case 'Product image':

               if (file_exists($reportdetails[$rowCount][$value])) {

                    $objDrawing = new PHPExcel_Worksheet_Drawing();

                    $objDrawing->setName('Customer Signature');

                    $objDrawing->setDescription('Customer Signature');

                  

                    //Path to signature .jpg file

                  $signature = $reportdetails[$rowCount][$value];

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

               $objPHPExcel->getActiveSheet()->setCellValue($column.$cell, $reportdetails[$rowCount][$value] ); 

               break;

         }



      }     //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);

         

       $rowCount++; 

   }  

   //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);   



   $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

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

   $objPHPExcel->disconnectWorksheets();



   unset($objPHPExcel);

   die();

   



}




add_action( 'wp_ajax_alpha_save_ss22_factory_data_order_number','alpha_save_ss22_factory_data_order_number' );
add_action( 'wp_ajax_nopriv_alpha_save_ss22_factory_data_order_number','alpha_save_ss22_factory_data_order_number' );
function alpha_save_ss22_factory_data_order_number(){
   global $wpdb;
   if($_REQUEST['action'] == 'alpha_save_ss22_factory_data_order_number'){
      $tabVid = $_REQUEST['tabVid'];
      $orderNumberText = $_REQUEST['orderNumberText'];
      if(!empty($orderNumberText)){
         $wpdb->update( 
            "alpha_wp_factory_order_confirmation_list", 
            array( 
               'forderid' => $orderNumberText,       
               'update' => 'updated',        
            ), 
            array( 'vid' => $tabVid ), 
            array( '%s'), 
            array( '%d' ) 
         );
         echo "edited";  
         die();
      }else{
         echo "Not edited";  
         die();   
      }
   }
   

}

add_action( 'wp_ajax_alpha_edit_ss22_factory_data','alpha_edit_ss22_factory_data' );
add_action( 'wp_ajax_nopriv_alpha_edit_ss22_factory_data','alpha_edit_ss22_factory_data' );
function alpha_edit_ss22_factory_data(){
   global $wpdb;
   $date=date('Y-m-d');
   
   $getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];
   $getCurrentRowFactoryUnits = $_REQUEST['getCurrentRowFactoryUnits'];
   $getCurrentRowFactoryNameSelect = $_REQUEST['getCurrentRowFactoryNameSelect'] != '' ? $_REQUEST['getCurrentRowFactoryNameSelect'] : '';
   $getCurrentRowFactoryNamecartoon_dimensions = $_REQUEST['getCurrentRowFactoryNamecartoon_dimensions'] != '' ? $_REQUEST['getCurrentRowFactoryNamecartoon_dimensions'] : '';
   $getCurrentRowFactoryNamecbms_x_ctn = $_REQUEST['getCurrentRowFactoryNamecbms_x_ctn'] != '' ? $_REQUEST['getCurrentRowFactoryNamecbms_x_ctn'] : '';
   $getCurrentRowFactoryNameweight_x_ctn = $_REQUEST['getCurrentRowFactoryNameweight_x_ctn'] != '' ? $_REQUEST['getCurrentRowFactoryNameweight_x_ctn'] : '';
   $getCurrentRowFactoryNamefabric = $_REQUEST['getCurrentRowFactoryNamefabric'] != '' ? $_REQUEST['getCurrentRowFactoryNamefabric'] : '';
   $getCurrentRowFactoryOrderDate = $_REQUEST['getCurrentRowFactoryOrderDate'] != '' ? $_REQUEST['getCurrentRowFactoryOrderDate'] : '';
   $getCurrentRowFactoryOrderCost = $_REQUEST['getCurrentRowFactoryOrderCost'] != '' ? $_REQUEST['getCurrentRowFactoryOrderCost'] : '';
   $getCurrentRowFactoryOrdercomments = $_REQUEST['getCurrentRowFactoryOrdercomments'] != '' ? $_REQUEST['getCurrentRowFactoryOrdercomments'] : '';
   
   $wpdb->update( 
         "alpha_wp_factory_order_confirmation_list", 
         array( 
            'forderunits' => $getCurrentRowFactoryUnits,       
            'factoryname' => $getCurrentRowFactoryNameSelect,        
            'cartoon_dimensions' => $getCurrentRowFactoryNamecartoon_dimensions,       
            'cbms_x_ctn' => $getCurrentRowFactoryNamecbms_x_ctn,        
            'weight_x_ctn' => $getCurrentRowFactoryNameweight_x_ctn,       
            'fabric' => $getCurrentRowFactoryNamefabric,       
            'deliverydate' => $getCurrentRowFactoryOrderDate,        
            'costprice' => $getCurrentRowFactoryOrderCost,        
            'comments' => $getCurrentRowFactoryOrdercomments,        
            'new' => '',         
            'update' => 'updated',        
            'update_date' => $date,       
         ), 
         array( 'vid' => $getCurrentRowVariationID ), 
         array( '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s' ), 
         array( '%d' ) 
      );


   echo "edited";

   die();
}

// End Alpha ss22 Factory Order Lists 


// Apha Summer spring 22 
add_action('wp_ajax_alpha_get_ss22_data', 'alpha_get_ss22_data');
add_action('wp_ajax_nopriv_alpha_get_ss22_data', 'alpha_get_ss22_data');

function alpha_get_ss22_data() {
  global $wpdb;	

  header("Content-Type: application/json");
	return;
  	$request= $_POST;

	$get_total_records = $wpdb->get_results("SELECT `vid` from  {$wpdb->prefix}run_time_v_id Order by id asc ");
 
	$totalData = count($get_total_records);

	if($request['length'] == -1){
		$request['length'] = $totalData;
	}
  	$get_variations_arr = $wpdb->get_results("SELECT `vid`, `item_id` from  {$wpdb->prefix}run_time_v_id Order by id ".$request['order'][0]['dir']." LIMIT ".$request['length']." OFFSET ".$request['start']."  ");

	// if(!empty($get_variations_arr2)){
	// 	$get_variations_arr = $get_variations_arr2;
	// }
	
  	if ( !empty ($get_variations_arr ) ) {
    
    foreach($get_variations_arr as $key => $data_record){
		$variation_id = $data_record->vid;
		$itemsIdsArr = explode(",", $data_record->item_id);
		$sum = 0;
		$d = 0;
		foreach($itemsIdsArr as $key4 => $abc)
		{
			$c1 = 0;
			$c5 = 0;
			$last = 0;
				$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
				$ap = wc_get_order_item_meta( $abc, '_qty', true );
				foreach ($variation_size as $key45 => $size) 
				{
					$c1 += $size['value'];
					$merge1[$key][$size['label']][] = $ap * $size['value'];
				}
				
				$sum += $c1 * $ap;
				$merge2 = $c1;
		
			//echo "<p>" . $key4 . " " . $sum . "</p>";
		}
		
		 $_product =  wc_get_product( $variation_id);
		 $main_product = wc_get_product( $_product->get_parent_id() );
		 $image_id			= $_product->get_image_id();
		 $gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
		 $thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
		 $thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

		$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
		$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));

		$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
		$css_slugGender = array();
		$css_slugCategory = array();
		$css_slugSubCategory = array();
		//print_r($cat);
		foreach($cat as $cvalue)
		{
			if($cvalue->parent != 0)
			{
				$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
				$css_slugSubCategory[] = $cvalue->name;
				$css_slugCategory[] = $term->name;
				if($cvalue->parent == '3259')
				{
					$css_slugGender[] = $cvalue->name;
				}
			}
			else
			{
				if($cvalue->name == 'All Mens')
				{
					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
				}
				elseif($cvalue->name == 'All Womens')
				{
					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
				}
			}
		}

		$e = get_post_meta($variation_id, '_stock', true);
		if($e)
		{
			if($e < 0)
			{
				$e = 0;
			}
			else
			{
				$e = $e * $merge2[$key][0];
			}
		}
		else
		{
			$e = "Stock limit removed";
		}

		$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
		$array_logo = array();
		if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
		if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
		if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
		if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
		
		$logoApplicationString = implode(', ', $array_logo);
		
		$row3 = "<div class='cart-sizes-attribute'>";
		$row3 .= '<div class="size-guide"><h5>Sizes</h5>';
			foreach ($merge1[$key] as $akkk => $akkkv) {
				$q  = 0;
				$row3 .= "<div class='inner-size'><span>" . $akkk  . "</span>";
				foreach($akkkv as $akkk1 => $akkkv1)
				{
					$q += $akkkv1;
				}
				$row3 .= "<span class='clr_val'>" . $q . "</span>";
				$row3 .= "</div>";
			}
		$row3 .= "</div>";
		$row3 .= "</div>";

	
		$apk = 0;
		
		$nestedData = array();
		if($getQtyRemaining->vid == $variation_id)
		{
			
			$nestedData['product_image'] = $thumbnail_src[0];
			$nestedData['item_name' ] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 ;
			$nestedData['product_sku' ] =  $_product->get_sku();
			$nestedData['pa_brand' ] =  $main_product->get_attribute( 'pa_brand' );
			$nestedData['gender' ] =  implode(", ", $css_slugGender);
			$nestedData['category' ] =  implode(", ", $css_slugCategory);
			$nestedData['sub_category' ] =  implode(", ", $css_slugSubCategory);
			$nestedData['season' ] =   $main_product->get_attribute( 'pa_season' );
			$nestedData['composition' ] =  $fabricCompositionString;
			$nestedData['producto_logo' ] =  $logoApplicationString;


			$nestedData['selling_price' ] =  get_post_meta($variation_id, '_regular_price', true);
			$nestedData['total_purchased_unit' ] =  $q;
			$nestedData['open_stock' ] =  $e;
			$nestedData['total_amount' ] =  wc_price(get_post_meta($variation_id, '_regular_price', true) * $q);
			
		}else{

			$nestedData['product_image'] = $thumbnail_src[0] ;
			$nestedData['item_name' ] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 ;
			$nestedData['product_sku' ] =  $_product->get_sku();
			$nestedData['pa_brand' ] =  $main_product->get_attribute( 'pa_brand' );
			$nestedData['gender' ] =  implode(", ", $css_slugGender);
			$nestedData['category' ] =  implode(", ", $css_slugCategory);
			$nestedData['sub_category' ] =  implode(", ", $css_slugSubCategory);
			$nestedData['season' ] =   $main_product->get_attribute( 'pa_season' );
			$nestedData['composition' ] =  $fabricCompositionString;
			$nestedData['producto_logo' ] =  $logoApplicationString;
			
			$nestedData['selling_price' ] = get_post_meta($variation_id, '_regular_price', true);
			$nestedData['total_purchased_unit' ] =  $q;
			$nestedData['open_stock' ] =  $e;
			$nestedData['total_amount' ] =  wc_price(get_post_meta($variation_id, '_regular_price', true) * $q);
			

		}

		$data[] = $nestedData;
    }
	// echo "<pre>";
	// print_r($data);
	// die;
	
	
    $json_data = array(
      "draw" => intval($request['draw']),
      "recordsTotal" => intval($totalData),
      "recordsFiltered" => intval($totalData),
      "data" => $data
    );

    echo json_encode($json_data);

  } else {

    $json_data = array(
      "data" => array()
    );

    echo json_encode($json_data);
  }
  
  wp_die();

}

// END SS22 get data


// Apha Summer spring 22 
add_action('wp_ajax_alpha_export_without_user1', 'alpha_export_without_user1');
add_action('wp_ajax_nopriv_alpha_export_without_user1', 'alpha_export_without_user1');

function alpha_export_without_user1() {
   global $wpdb;	

   $url2 = site_url();

   $path2 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';

   $base_path = wp_upload_dir();

   $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';

   define('SITEURL', $url2);

   define('SITEPATH', str_replace('\\', '/', $path2));

   $dataHeader = json_decode(stripslashes($_POST['getHeaderArray']));


   $k = 1;

   $i = 0;

   $getTotalCountHeader = count($dataHeader);

   //echo $getTotalCountHeader;

   $getTotalCountBody = count($dataBody);


   $newLoop = $getTotalCountBody / $getTotalCountHeader;

   $count = 0;

   foreach($dataHeader as $keyHeader => $dHeader)

   {

      $xlsx_data_new_allHeader= array();

      $alpha = num_to_letters($keyHeader+1);

      

      $dataH["$alpha"] = $dHeader;

      

      array_push($xlsx_data_new_allHeader, $dataH);

      $k++;

   }


  	$request= $_POST;

	$get_variations_arr = $wpdb->get_results("SELECT `vid`, `item_id` from  {$wpdb->prefix}run_time_v_id Order by id asc  ");
 
	
  	if ( !empty ($get_variations_arr ) ) {
    
     foreach($get_variations_arr as $key => $data_record){
		$variation_id = $data_record->vid;
		$itemsIdsArr = explode(",", $data_record->item_id);
		$sum = 0;
		$d = 0;
		foreach($itemsIdsArr as $key4 => $abc)
		{
			$c1 = 0;
			$c5 = 0;
			$last = 0;
				$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
				$ap = wc_get_order_item_meta( $abc, '_qty', true );
				foreach ($variation_size as $key45 => $size) 
				{
					$c1 += $size['value'];
					$merge1[$key][$size['label']][] = $ap * $size['value'];
				}
				
				$sum += $c1 * $ap;
				$merge2 = $c1;
		
			//echo "<p>" . $key4 . " " . $sum . "</p>";
		}
		
		 $_product =  wc_get_product( $variation_id);
		 $main_product = wc_get_product( $_product->get_parent_id() );
		 $image_id			= $_product->get_image_id();
		 $gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
		 $thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
		 $thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

		 $imageUrlThumb = str_replace("https://shop.fexpro.com", "",$thumbnail_src[0]);
					$imageUrlThumb1 = $_SERVER['DOCUMENT_ROOT'] . $imageUrlThumb;


		$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
		$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));

		$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
		$css_slugGender = array();
		$css_slugCategory = array();
		$css_slugSubCategory = array();
		//print_r($cat);
		foreach($cat as $cvalue)
		{
			if($cvalue->parent != 0)
			{
				$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
				$css_slugSubCategory[] = $cvalue->name;
				$css_slugCategory[] = $term->name;
				if($cvalue->parent == '3259')
				{
					$css_slugGender[] = $cvalue->name;
				}
			}
			else
			{
				if($cvalue->name == 'All Mens')
				{
					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
				}
				elseif($cvalue->name == 'All Womens')
				{
					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
				}
			}
		}

		$e = get_post_meta($variation_id, '_stock', true);
		if($e)
		{
			if($e < 0)
			{
				$e = 0;
			}
			else
			{
				$e = $e * $merge2[$key][0];
			}
		}
		else
		{
			$e = "Stock limit removed";
		}

		$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
		$array_logo = array();
		if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
		if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
		if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
		if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
		
		$logoApplicationString = implode(', ', $array_logo);
		
		$row3 = "<div class='cart-sizes-attribute'>";
		$row3 .= '<div class="size-guide"><h5>Sizes</h5>';
			foreach ($merge1[$key] as $akkk => $akkkv) {
				$q  = 0;
				$row3 .= "<div class='inner-size'><span>" . $akkk  . "</span>";
				foreach($akkkv as $akkk1 => $akkkv1)
				{
					$q += $akkkv1;
				}
				$row3 .= "<span class='clr_val'>" . $q . "</span>";
				$row3 .= "</div>";
			}
		$row3 .= "</div>";
		$row3 .= "</div>";

	
		$apk = 0;
		
		$nestedData = array();
		if($getQtyRemaining->vid == $variation_id)
		{
			
			$nestedData[] = $imageUrlThumb1;
			$nestedData[] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 ;
			$nestedData[] =  $_product->get_sku();
			$nestedData[] =  $main_product->get_attribute( 'pa_brand' );
			$nestedData[] =  implode(", ", $css_slugGender);
			$nestedData[] =  implode(", ", $css_slugCategory);
			$nestedData[] =  implode(", ", $css_slugSubCategory);
			$nestedData[] =   $main_product->get_attribute( 'pa_season' );
			$nestedData[] =  $fabricCompositionString;
			$nestedData[] =  $logoApplicationString;
			$nestedData[] =  get_post_meta($variation_id, '_regular_price', true);
			$nestedData[] =  $q;
			$nestedData[] =  $e;
			$nestedData[] =  (get_post_meta($variation_id, '_regular_price', true) * $q);
			
		}else{

			$nestedData[] = $imageUrlThumb1 ;
			$nestedData[] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' )  ;
			$nestedData[] =  $_product->get_sku();
			$nestedData[] =  $main_product->get_attribute( 'pa_brand' );
			$nestedData[] =  implode(", ", $css_slugGender);
			$nestedData[] =  implode(", ", $css_slugCategory);
			$nestedData[] =  implode(", ", $css_slugSubCategory);
			$nestedData[] =   $main_product->get_attribute( 'pa_season' );
			$nestedData[] =  $fabricCompositionString;
			$nestedData[] =  $logoApplicationString;
			$nestedData[] = get_post_meta($variation_id, '_regular_price', true);
			$nestedData[] =  $q;
			$nestedData[] =  $e;
			$nestedData[] =  (get_post_meta($variation_id, '_regular_price', true) * $q);
			
			

		}

		$data[] = $nestedData;
    }

 }
 	
  $xlsx_data_new_allBody= $data;


	
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
			

			 case 'Product image':
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
		  $newCounter++;
	   }     //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);
		  
		$rowCount++; 
	
	}  

	//$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);   
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
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
	$objPHPExcel->disconnectWorksheets();
 
	unset($objPHPExcel);
	die();


   




  
  wp_die();

}

// GET Alpha View Factory Lists
	add_action('wp_ajax_alpha_view_factory_datatables', 'alpha_view_factory_datatables');
	add_action('wp_ajax_nopriv_alpha_view_factory_datatables', 'alpha_view_factory_datatables');
	function alpha_view_factory_datatables() {
		$return_array = array();
		$return_array1 = array();
		$return_array2 = array();
		global $wpdb;

		header("Content-Type: application/json");
		$request= $_POST;

		
		$namee = $request['factory_name'];   

		$result_string = ltrim($namee);
		$result_string = rtrim($result_string);

		if($result_string == 'YANGZHOU YAXIYA HEADWEAR'){
			$namee = 'YANGZHOU YAXIYA HEADWEAR & GAR';
		}
		if($result_string == 'TAIZHOU J'){
			$namee = 'TAIZHOU J&F HEADWEAR';
		}
		if($result_string == 'Dishang Group/Weihai Textile Group Import'){
			$namee = 'Dishang Group/Weihai Textile Group Import & Export Co,. Ltd';
		}

		$get_total_records =  $wpdb->get_results("SELECT * FROM alpha_wp_factory_order_confirmation_list WHERE `factoryname` = '$namee'  ", ARRAY_A );
		
		$totalData = count($get_total_records);
		if($request['length'] == -1){
			$request['length'] = $totalData;
		}

		

		$getZenlineOrdersList = $wpdb->get_results("SELECT * FROM alpha_wp_factory_order_confirmation_list WHERE `factoryname` = '$namee' Order by id ".$request['order'][0]['dir']." LIMIT ".$request['length']." OFFSET ".$request['start']."   ", ARRAY_A );


		
		if( !empty($request['search']['value']) ) { 
			$get_variations_arr2 = array();
			$get_variations_arr1 = $wpdb->get_results("SELECT * from  alpha_wp_factory_order_confirmation_list WHERE `factoryname` = '$namee' Order by id ".$request['order'][0]['dir']." LIMIT ".$request['length']." OFFSET ".$request['start']."  ", ARRAY_A );
			
			foreach($get_variations_arr1 as $key => $data_record){
				$variation_id = $data_record['vid'];
				$_product =  wc_get_product( $variation_id);
				$serchText = sanitize_text_field($request['search']['value']);

				$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
				$fabricCompositionString = $fabricComposition[0]->name; 
				$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );

				$array_logo = array();
				if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
				if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
				if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
				if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
				
				$logoApplicationString = implode(', ', $array_logo);
			

				$order_number = $data_record['forderid'];
				$item_name = $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3;
				$product_sku = $_product->get_sku() ;
				$composition = $fabricCompositionString;
				$producto_logo = $logoApplicationString;
				$factory_order = $data_record['forderunits'];
				$factory_name = $data_record['factoryname'];
				$delivery_date = $data_record['deliverydate'];
				$cost_price = $data_record['costprice'];
		
				if (strpos( strtolower($order_number), strtolower($serchText) ) !== false) {
					$get_variations_arr2[] = array( 'id' => $data_record['id'],'vid' => $data_record['vid'],'forderid' =>$data_record['forderid'],'forderunits' => $data_record['forderunits'],'fnumber' =>  $data_record['fnumber'],'factoryname' => $data_record['factoryname'],'cartoon_dimensions' => $data_record['cartoon_dimensions'],'cbms_x_ctn' => $data_record['cbms_x_ctn'],'weight_x_ctn' => $data_record['weight_x_ctn'],'fabric' => $data_record['fabric'],'deliverydate' =>$data_record['deliverydate'],'costprice' =>$data_record['costprice'],'comments' => $data_record['comments'],'new' => $data_record['new'],'new_insert_date' => $data_record['new_insert_date'],'update' => $data_record['update'],'update_date' => $data_record['update_date']) ;
				}else if (strpos( strtolower($item_name), strtolower($serchText) ) !== false) {
					$get_variations_arr2[] = array( 'id' => $data_record['id'],'vid' => $data_record['vid'],'forderid' =>$data_record['forderid'],'forderunits' => $data_record['forderunits'],'fnumber' =>  $data_record['fnumber'],'factoryname' => $data_record['factoryname'],'cartoon_dimensions' => $data_record['cartoon_dimensions'],'cbms_x_ctn' => $data_record['cbms_x_ctn'],'weight_x_ctn' => $data_record['weight_x_ctn'],'fabric' => $data_record['fabric'],'deliverydate' =>$data_record['deliverydate'],'costprice' =>$data_record['costprice'],'comments' => $data_record['comments'],'new' => $data_record['new'],'new_insert_date' => $data_record['new_insert_date'],'update' => $data_record['update'],'update_date' => $data_record['update_date']) ;
				}else if (strpos( strtolower($product_sku), strtolower($serchText) ) !== false) {
					$get_variations_arr2[] = array( 'id' => $data_record['id'],'vid' => $data_record['vid'],'forderid' =>$data_record['forderid'],'forderunits' => $data_record['forderunits'],'fnumber' =>  $data_record['fnumber'],'factoryname' => $data_record['factoryname'],'cartoon_dimensions' => $data_record['cartoon_dimensions'],'cbms_x_ctn' => $data_record['cbms_x_ctn'],'weight_x_ctn' => $data_record['weight_x_ctn'],'fabric' => $data_record['fabric'],'deliverydate' =>$data_record['deliverydate'],'costprice' =>$data_record['costprice'],'comments' => $data_record['comments'],'new' => $data_record['new'],'new_insert_date' => $data_record['new_insert_date'],'update' => $data_record['update'],'update_date' => $data_record['update_date']) ;
				}else if (strpos( strtolower($composition), strtolower($serchText) ) !== false) {
					$get_variations_arr2[] = array( 'id' => $data_record['id'],'vid' => $data_record['vid'],'forderid' =>$data_record['forderid'],'forderunits' => $data_record['forderunits'],'fnumber' =>  $data_record['fnumber'],'factoryname' => $data_record['factoryname'],'cartoon_dimensions' => $data_record['cartoon_dimensions'],'cbms_x_ctn' => $data_record['cbms_x_ctn'],'weight_x_ctn' => $data_record['weight_x_ctn'],'fabric' => $data_record['fabric'],'deliverydate' =>$data_record['deliverydate'],'costprice' =>$data_record['costprice'],'comments' => $data_record['comments'],'new' => $data_record['new'],'new_insert_date' => $data_record['new_insert_date'],'update' => $data_record['update'],'update_date' => $data_record['update_date']) ;
				}else if (strpos( strtolower($producto_logo), strtolower($serchText) ) !== false) {
					$get_variations_arr2[] = array( 'id' => $data_record['id'],'vid' => $data_record['vid'],'forderid' =>$data_record['forderid'],'forderunits' => $data_record['forderunits'],'fnumber' =>  $data_record['fnumber'],'factoryname' => $data_record['factoryname'],'cartoon_dimensions' => $data_record['cartoon_dimensions'],'cbms_x_ctn' => $data_record['cbms_x_ctn'],'weight_x_ctn' => $data_record['weight_x_ctn'],'fabric' => $data_record['fabric'],'deliverydate' =>$data_record['deliverydate'],'costprice' =>$data_record['costprice'],'comments' => $data_record['comments'],'new' => $data_record['new'],'new_insert_date' => $data_record['new_insert_date'],'update' => $data_record['update'],'update_date' => $data_record['update_date']) ;
				}else if (strpos( strtolower($factory_order), strtolower($serchText) ) !== false) {
					$get_variations_arr2[] = array( 'id' => $data_record['id'],'vid' => $data_record['vid'],'forderid' =>$data_record['forderid'],'forderunits' => $data_record['forderunits'],'fnumber' =>  $data_record['fnumber'],'factoryname' => $data_record['factoryname'],'cartoon_dimensions' => $data_record['cartoon_dimensions'],'cbms_x_ctn' => $data_record['cbms_x_ctn'],'weight_x_ctn' => $data_record['weight_x_ctn'],'fabric' => $data_record['fabric'],'deliverydate' =>$data_record['deliverydate'],'costprice' =>$data_record['costprice'],'comments' => $data_record['comments'],'new' => $data_record['new'],'new_insert_date' => $data_record['new_insert_date'],'update' => $data_record['update'],'update_date' => $data_record['update_date']) ;
				}else if (strpos( strtolower($factory_name), strtolower($serchText) ) !== false) {
					$get_variations_arr2[] = array( 'id' => $data_record['id'],'vid' => $data_record['vid'],'forderid' =>$data_record['forderid'],'forderunits' => $data_record['forderunits'],'fnumber' =>  $data_record['fnumber'],'factoryname' => $data_record['factoryname'],'cartoon_dimensions' => $data_record['cartoon_dimensions'],'cbms_x_ctn' => $data_record['cbms_x_ctn'],'weight_x_ctn' => $data_record['weight_x_ctn'],'fabric' => $data_record['fabric'],'deliverydate' =>$data_record['deliverydate'],'costprice' =>$data_record['costprice'],'comments' => $data_record['comments'],'new' => $data_record['new'],'new_insert_date' => $data_record['new_insert_date'],'update' => $data_record['update'],'update_date' => $data_record['update_date']) ;
				}else if (strpos( strtolower($delivery_date), strtolower($serchText) ) !== false) {
					$get_variations_arr2[] = array( 'id' => $data_record['id'],'vid' => $data_record['vid'],'forderid' =>$data_record['forderid'],'forderunits' => $data_record['forderunits'],'fnumber' =>  $data_record['fnumber'],'factoryname' => $data_record['factoryname'],'cartoon_dimensions' => $data_record['cartoon_dimensions'],'cbms_x_ctn' => $data_record['cbms_x_ctn'],'weight_x_ctn' => $data_record['weight_x_ctn'],'fabric' => $data_record['fabric'],'deliverydate' =>$data_record['deliverydate'],'costprice' =>$data_record['costprice'],'comments' => $data_record['comments'],'new' => $data_record['new'],'new_insert_date' => $data_record['new_insert_date'],'update' => $data_record['update'],'update_date' => $data_record['update_date']) ;
				}else if (strpos( strtolower($cost_price), strtolower($serchText) ) !== false) {
					$get_variations_arr2[] = array( 'id' => $data_record['id'],'vid' => $data_record['vid'],'forderid' =>$data_record['forderid'],'forderunits' => $data_record['forderunits'],'fnumber' =>  $data_record['fnumber'],'factoryname' => $data_record['factoryname'],'cartoon_dimensions' => $data_record['cartoon_dimensions'],'cbms_x_ctn' => $data_record['cbms_x_ctn'],'weight_x_ctn' => $data_record['weight_x_ctn'],'fabric' => $data_record['fabric'],'deliverydate' =>$data_record['deliverydate'],'costprice' =>$data_record['costprice'],'comments' => $data_record['comments'],'new' => $data_record['new'],'new_insert_date' => $data_record['new_insert_date'],'update' => $data_record['update'],'update_date' => $data_record['update_date']) ;
				}else{

				}

				
				


				
			}
			
			
		}else{}
		
		foreach($getZenlineOrdersList as $key => $value)
		{
			$vid = $value['vid'];
			$allData = $wpdb->get_results("SELECT `order_item_id`,`order_id`   FROM {$wpdb->prefix}wc_order_product_lookup WHERE `variation_id` = '$vid'", ARRAY_A );
			foreach($allData as $bk)
			{
				if ( get_post_status ( $bk['order_id'] ) != 'wc-presale3' )
				{
					continue;
				}
				else
				{
					$return_array1[$value['vid']][] = $bk['order_item_id'];
				}
			}
		}
		$j = 0;
		foreach($return_array1 as $key3 => $value3)
		{
			$sum = 0;
			foreach($value3 as $key4 => $abc)
			{
				$c1 = 0;
				$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
					$get_variation_id = wc_get_order_item_meta( $abc, '_variation_id', true );
					$ap = wc_get_order_item_meta( $abc, '_qty', true );
					if(!in_array($abc, $return_array2))
					{
						if($get_variation_id == $key3)
						{
							foreach ($variation_size as $key => $size) 
							{
								$c1 += $size['value'];
								$merge1[$key3][$size['label']][] = $ap * $size['value'];
								$merge3[$size['label']] = $size['label'];
							}
							
						}
						array_push($return_array2, $abc);
					}
					
					$sum += $c1 * $ap; 
			}
		}

		if(!empty($get_variations_arr2)){
			
			$getZenlineOrdersList = $get_variations_arr2;
			
		}

		if ( !empty ($getZenlineOrdersList ) ) {
			$i = 0;
			$len = count($getZenlineOrdersList);
			foreach($getZenlineOrdersList as $key => $data_record)
			{
				if(!empty(wc_get_product( $data_record['vid'] )))
				{
					$_product =  wc_get_product( $data_record['vid']);
					$image_id           = $_product->get_image_id();
					$gallery_thumbnail  = wc_get_image_size( array(100, 100) );
					$thumbnail_size     = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
					$thumbnail_src      = wp_get_attachment_image_src( $image_id, $thumbnail_size );
					
					$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
					$fabricCompositionString = $fabricComposition[0]->name; 
					
					$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
					$otherData = "";
				}else{
					$_parent_product =  wc_get_product($_product->get_parent_id());
					if(!in_array($_parent_product->get_sku(), $kl))
					{
						$otherData = "<span class='no-v1' style='display:none;' >This Style SKU is not for Printing purpose: <strong>". $_parent_product->get_sku() . " (Completely marked with Red Color Row)</strong></span>";
					}
				}
				$array_logo = array();
				if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
				if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
				if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
				if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
				
				$logoApplicationString = implode(', ', $array_logo);
				
				if(empty($data_record['costprice']))
				{
					$classs = 'red';
				}
				else
				{
					$classs = '';
				}

				$row3 = "<div class='cart-sizes-attribute'>";
				$row3 .= '<div class="size-guide"><h5>Sizes</h5>';
				if(!empty($merge1[$data_record['vid']]))
				{
					$k = '';
					foreach ($merge1[$data_record['vid']] as $akkk => $akkkv) {
						$q  = 0;
						$row3 .= "<div class='inner-size'><span>" . $akkk  . "</span>";
						foreach($akkkv as $akkk1 => $akkkv1)
						{
							$q += $akkkv1;
						}
						$row3 .= "<span class='clr_val'>" . $q . "</span>";
						$row3 .= "</div>";
					}
				}  
				else
				{
					$k = 'red';
				}
				$row3 .= "</div>";
				$row3 .= "</div>";


				$nestedData = array();


				$nestedData['order_number'] = $data_record['forderid'];
				$nestedData['product_image'] = $thumbnail_src[0];
				$nestedData['item_name'] = $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3;
				$nestedData['product_sku'] = $_product->get_sku() ;
				$nestedData['composition'] = $fabricCompositionString;
				$nestedData['producto_logo'] = $logoApplicationString;
				$nestedData['factory_order'] = $data_record['forderunits'];
				$nestedData['factory_name'] = $data_record['factoryname'];
				$nestedData['delivery_date'] = $data_record['deliverydate'];
				$nestedData['cost_price'] = $data_record['costprice'];
				$nestedData['total_amount'] = wc_price($data_record['forderunits'] * $data_record['costprice']);
				$nestedData['other_column'] = $otherData;

				$data[] = $nestedData;
			}
			$json_data = array(
				"draw" => intval($request['draw']),
				"recordsTotal" => intval($totalData),
				"recordsFiltered" => intval($totalData),
				"data" => $data
			);
		
			echo json_encode($json_data);
		}else{
			$json_data = array(
				"data" => array()
			);
		
			echo json_encode($json_data);
		}
		wp_die();

	}


	// Export View Factory Data 
	add_action( 'wp_ajax_alpha_export_view_factory_data','alpha_export_view_factory_data' );
	add_action( 'wp_ajax_nopriv_alpha_export_view_factory_data','alpha_export_view_factory_data' );
	function alpha_export_view_factory_data(){ 
		global $wpdb;
		$url1 = site_url();
		$path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';
		$base_path = wp_upload_dir();
		$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';
		define('SITEURL', $url1);
		define('SITEPATH', str_replace('\\', '/', $path1));

		$request= $_POST;

		
		$namee = $request['factory_name'];   

		$result_string = ltrim($namee);
		$result_string = rtrim($result_string);

		if($result_string == 'YANGZHOU YAXIYA HEADWEAR'){
			$namee = 'YANGZHOU YAXIYA HEADWEAR & GAR';
		}
		if($result_string == 'TAIZHOU J'){
			$namee = 'TAIZHOU J&F HEADWEAR';
		}
		if($result_string == 'Dishang Group/Weihai Textile Group Import'){
			$namee = 'Dishang Group/Weihai Textile Group Import & Export Co,. Ltd';
		}

		$dataHeader = json_decode(stripslashes($_POST['getHeaderArray']));
		
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



	//echo $getTotalCountHeader;
	if($_POST['exportData'] != 'All' ){
			$offset = ($_POST['exportData'] * $_POST['currentPage']) - $_POST['exportData'];
			$dataBody = $wpdb->get_results("SELECT * FROM alpha_wp_factory_order_confirmation_list WHERE `factoryname` = '$namee' Order by id asc LIMIT ".$_POST['exportData']." OFFSET ".$offset."  ", ARRAY_A );

	}else{
			$dataBody = $wpdb->get_results("SELECT * FROM alpha_wp_factory_order_confirmation_list WHERE `factoryname` = '$namee'  Order by id asc  ", ARRAY_A );
	}
	


	$getTotalCountBody = count($dataBody);

	foreach($dataBody as $key => $data_record)
	{

				if(!empty(wc_get_product( $data_record['vid'] )))
				{
					$_product =  wc_get_product( $data_record['vid']);
					$image_id           = $_product->get_image_id();
					$gallery_thumbnail  = wc_get_image_size( array(100, 100) );
					$thumbnail_size     = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
					$thumbnail_src      = wp_get_attachment_image_src( $image_id, $thumbnail_size );
					
					$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
					$fabricCompositionString = $fabricComposition[0]->name; 
					
					$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
					$otherData = "";


				}else{
					$_parent_product =  wc_get_product($_product->get_parent_id());
					if(!in_array($_parent_product->get_sku(), $kl))
					{
						$otherData = "<span class='no-v1' style='display:none;' >This Style SKU is not for Printing purpose: <strong>". $_parent_product->get_sku() . " (Completely marked with Red Color Row)</strong></span>";
					}
				
				}

				$array_logo = array();
				if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
				if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
				if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
				if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
				
				$logoApplicationString = implode(', ', $array_logo);

				$imageUrlThumb = str_replace("https://shop.fexpro.com", "",$thumbnail_src[0]);
				$imageUrlThumb1 = $_SERVER['DOCUMENT_ROOT'] . $imageUrlThumb;


				$nestedData = array();

				$nestedData[] = $data_record['forderid'];
				$nestedData[] = $imageUrlThumb1;
				$nestedData[] = $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' );
				$nestedData[] = $_product->get_sku() ;
				$nestedData[] = $fabricCompositionString;
				$nestedData[] = $logoApplicationString;
				$nestedData[] = $data_record['forderunits'];
				$nestedData[] = $data_record['factoryname'];
				$nestedData[] = $data_record['deliverydate'];
				$nestedData[] = $data_record['costprice'];
				$nestedData[] = $data_record['forderunits'] * $data_record['costprice'];
				$nestedData[] = $otherData;

				

				$data[] = $nestedData;
		}
						$array_logo = array();
					if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
					if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
					if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
					if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
					
					$logoApplicationString = implode(', ', $array_logo);

					$imageUrlThumb = str_replace("https://shop.fexpro.com", "",$thumbnail_src[0]);
					$imageUrlThumb1 = $_SERVER['DOCUMENT_ROOT'] . $imageUrlThumb;

		
		$xlsx_data_new_allBody= $data;
		
		
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
				

				case 'Product image':
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
			$newCounter++;
		}     //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);
			
			$rowCount++; 
		
		}  

		//$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);   
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
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
		$objPHPExcel->disconnectWorksheets();
	
		unset($objPHPExcel);
		die();

	}


// END Alpha view factory order list


add_action( 'wp_ajax_alpha_export_pagination_based_ss22_place_order_data','alpha_export_ss22_place_order_data' );
add_action( 'wp_ajax_nopriv_alpha_export_pagination_based_ss22_place_order_data','alpha_export_ss22_place_order_data' );
function alpha_export_ss22_place_order_data(){ 
	
	global $wpdb;
   	$url1 = site_url();
	$path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';
	$base_path = wp_upload_dir();
	$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';
	define('SITEURL', $url1);
	define('SITEPATH', str_replace('\\', '/', $path1));

    

    $dataHeader = json_decode(stripslashes($_POST['getHeaderArray']));
       
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



   //echo $getTotalCountHeader;
   if($_POST['exportData'] != 'All' ){
	    $offset = ($_POST['exportData'] * $_POST['currentPage']) - $_POST['exportData'];
   		$dataBody = $wpdb->get_results("SELECT * from  {$wpdb->prefix}run_time_v_id Order by id asc LIMIT ".$_POST['exportData']." OFFSET ".$offset."  ");
   }else{
		$dataBody = $wpdb->get_results("SELECT * from  {$wpdb->prefix}run_time_v_id Order by id asc ");
   }

   $getTotalCountBody = count($dataBody);

   foreach($dataBody as $key => $data_record)
   {
		$variation_id = $data_record->vid;
		$itemsIdsArr = explode(",", $data_record->item_id);
		$sum = 0;
		$d = 0;
		foreach($itemsIdsArr as $key4 => $abc)
		{
			$c1 = 0;
			$c5 = 0;
			$last = 0;
				$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
				$ap = wc_get_order_item_meta( $abc, '_qty', true );
				foreach ($variation_size as $key45 => $size) 
				{
					$c1 += $size['value'];
					$merge1[$key][$size['label']][] = $ap * $size['value'];
				}
				
				$sum += $c1 * $ap;
				$merge2 = $c1;
		
			//echo "<p>" . $key4 . " " . $sum . "</p>";
		}
		
		$checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM alpha_wp_factory_order_confirmation_list WHERE `vid`= '$variation_id'");
		$getQtyRemaining = $wpdb->get_row( "SELECT * FROM alpha_wp_factory_order_confirmation_list WHERE vid = $variation_id" );
		
		
		if($sum >= $getQtyRemaining->forderunits )
		{
			$aq = $sum - $getQtyRemaining->forderunits;
		}
		else
		{
			$aq = 0;
		}
		if($checkdataExist == 1)
		{				
		$qty = $getQtyRemaining->fnumber;
		}
		else
		{
			$qty = '';
		}

		$_product =  wc_get_product( $variation_id);
		$main_product = wc_get_product( $_product->get_parent_id() );
		$image_id			= $_product->get_image_id();
		$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
		$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
		$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

		$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
		$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));

		$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
		$css_slugGender = array();
		$css_slugCategory = array();
		$css_slugSubCategory = array();
		//print_r($cat);
		foreach($cat as $cvalue)
		{
			if($cvalue->parent != 0)
			{
				$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
				$css_slugSubCategory[] = $cvalue->name;
				$css_slugCategory[] = $term->name;
				if($cvalue->parent == '1818')
				{
					$css_slugGender[] = $cvalue->name;
				}
			}
			else
			{
				if($cvalue->name == 'All Mens')
				{
					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
				}
				elseif($cvalue->name == 'All Womens')
				{
					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
				}
			}
		}

		$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
		$array_logo = array();
		if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
		if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
		if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
		if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
		
		$logoApplicationString = implode(', ', $array_logo);
		
		if($getQtyRemaining->forderunits >= $sum){
			$stockQty = $getQtyRemaining->forderunits - $sum;
		}else if($getQtyRemaining->forderunits <= $sum){
			$stockQty = 0;
		}else{
			$stockQty = 0;
		}
		
		$imageUrlThumb = str_replace("https://shop.fexpro.com", "",$thumbnail_src[0]);
		$imageUrlThumb1 = $_SERVER['DOCUMENT_ROOT'] . $imageUrlThumb;
	
		$nestedData = array();
		if($getQtyRemaining->vid == $variation_id)
		{
			$nestedData[] = $imageUrlThumb1;
			$nestedData[] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) ;
			$nestedData[] =  $_product->get_sku();
			$nestedData[] =  $main_product->get_attribute( 'pa_delivery-date' );
			$nestedData[] =  $main_product->get_attribute( 'pa_brand' );
			$nestedData[] =  implode(", ", $css_slugGender);
			$nestedData[] =  implode(", ", $css_slugCategory);
			$nestedData[] =  implode(", ", $css_slugSubCategory);
			$nestedData[] =   $main_product->get_attribute( 'pa_season' );
			$nestedData[] =  $fabricCompositionString;
			$nestedData[] =  $logoApplicationString;
			$nestedData[] =  $sum;
			$nestedData[] = $getQtyRemaining->forderunits;
			$nestedData[] =  $aq;
			$nestedData[] =  $stockQty;
			$nestedData[] =  $getQtyRemaining->forderid;
		}else{

			$nestedData[] = $imageUrlThumb1 ;
			$nestedData[] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 ;
			$nestedData[] =  $_product->get_sku();
			$nestedData[] =  $main_product->get_attribute( 'pa_delivery-date' );
			$nestedData[] =  $main_product->get_attribute( 'pa_brand' );
			$nestedData[] =  implode(", ", $css_slugGender);
			$nestedData[] =  implode(", ", $css_slugCategory);
			$nestedData[] =  implode(", ", $css_slugSubCategory);
			$nestedData[] =   $main_product->get_attribute( 'pa_season' );
			$nestedData[] =  $fabricCompositionString;
			$nestedData[] =  $logoApplicationString;
			$nestedData[] =  $sum;
			$nestedData[] =  $sum;
			$nestedData[] =  0;
			$nestedData[] =  0;
			$nestedData[] =  '';
		}

		$data[] = $nestedData;
	}


	$xlsx_data_new_allBody= $data;

	
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
			

			 case 'Product image':
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
		  $newCounter++;
	   }     //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);
		  
		$rowCount++; 
	
	}  

	//$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);   
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
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
	$objPHPExcel->disconnectWorksheets();
 
	unset($objPHPExcel);
	die();


}


//add_action('wp_ajax_alpha_ss22_sku_list_datatables', 'alpha_ss22_sku_list_datatables');
//add_action('wp_ajax_nopriv_alpha_ss22_sku_list_datatables', 'alpha_ss22_sku_list_datatables');

// function alpha_ss22_sku_list_datatables() {
// 	global $wpdb;	
// 	$return_array = array();
// 	$return_array1 = array();
// 	$return_array2 = array();
// 	$return_array3 = array();

// 	$request = $_POST;

// 	$get_total_records = $wpdb->get_results("SELECT * from  alpha_wp_sku_order_lists ");
 
// 	$totalData = count($get_total_records);

// 	$get_order_arr = $wpdb->get_results("SELECT * from `alpha_wp_sku_order_lists` Order by id ".$request['order'][0]['dir']." LIMIT ".$request['length']." OFFSET ".$request['start']."  ", ARRAY_A );

	
// 	$nestedData = array();
	

		
// 	foreach($get_order_arr as $key => $value)
// 	{
		
// 			$nestedData['product_sku' ] =  $value['sku'];
// 			$nestedData['order_status' ] =  $value['status'];
// 			$nestedData['order_ids' ] =  $value['order_ids'];
		
// 		}
// 		$data[] = $nestedData;
// 	}

// 	$json_data = array(
//       "draw" => intval($request['draw']),
//       "recordsTotal" => intval($totalData),
//       "recordsFiltered" => intval($totalData),
//       "data" => $data
//     );
//     echo json_encode($json_data);

//   wp_die();

// }



// GET Alpha Presale4 place order screen data get from table 
add_action('wp_ajax_get_presale4_order_lists', 'get_presale4_order_lists');
add_action('wp_ajax_nopriv_get_presale4_order_lists', 'get_presale4_order_lists');


function get_presale4_order_lists() {
  global $wpdb;	

  header("Content-Type: application/json");

  $request= $_POST;
	// echo "<pre>";
	// print_r($request);
	// die;

  $getallOrdersNumbers = $wpdb->get_results("SELECT DISTINCT `forderid` FROM alpha_wp_ss22_presale4_factory_order_confirmation_list", ARRAY_A );
	$return_array3 = "<select class='onumbers_lists'>";
	$return_array3 .= "<option value=''>Select Order No.</option>";
	foreach($getallOrdersNumbers as $value)
	{
		$return_array3 .= "<option value='" . $value['forderid'] . "'>" . $value['forderid'] . "</option>";
	}
	$return_array3 .= "</select'>";
  
	$get_total_records = $wpdb->get_results("SELECT `vid` from  {$wpdb->prefix}presale4_run_time_v_id Order by id asc ");
 
	$totalData = count($get_total_records);

  if($request['length'] == -1){
	$request['length'] = $totalData;
  }
  $get_variations_arr = $wpdb->get_results("SELECT `vid`, `item_id` from  {$wpdb->prefix}presale4_run_time_v_id Order by id ".$request['order'][0]['dir']." LIMIT ".$request['length']." OFFSET ".$request['start']."  ");


  if( !empty($request['search']['value']) ) { 
	$get_variations_arr1 = $wpdb->get_results("SELECT `vid`, `item_id` from  {$wpdb->prefix}presale4_run_time_v_id Order by id ".$request['order'][0]['dir']." LIMIT ".$request['length']." OFFSET ".$request['start']."  ");
	foreach($get_variations_arr1 as $key => $data_record){
		$variation_id = $data_record->vid;
		$_product =  wc_get_product( $variation_id);
		$serchText = sanitize_text_field($request['search']['value']);
		
		
		$itemsIdsArr = explode(",", $data_record->item_id);
		$sum = 0;
		$d = 0;
		foreach($itemsIdsArr as $key4 => $abc)
		{
			$c1 = 0;
			$c5 = 0;
			$last = 0;
				$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
				$ap = wc_get_order_item_meta( $abc, '_qty', true );
				foreach ($variation_size as $key45 => $size) 
				{
					$c1 += $size['value'];
					$merge1[$key][$size['label']][] = $ap * $size['value'];
				}
				
				$sum += $c1 * $ap;
				$merge2 = $c1;
		
			//echo "<p>" . $key4 . " " . $sum . "</p>";
		}
		
		$checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM alpha_wp_ss22_presale4_factory_order_confirmation_list WHERE `vid`= '$variation_id'");
		$getQtyRemaining = $wpdb->get_row( "SELECT * FROM alpha_wp_ss22_presale4_factory_order_confirmation_list WHERE vid = $variation_id" );
		
		
		if($sum >= $getQtyRemaining->forderunits )
		{
			$aq = $sum - $getQtyRemaining->forderunits;
		}
		else
		{
			$aq = 0;
		}
		if($checkdataExist == 1)
		{				
		$qty = $getQtyRemaining->fnumber;
		}
		else
		{
			$qty = '';
		}

		$_product =  wc_get_product( $variation_id);
		$main_product = wc_get_product( $_product->get_parent_id() );
		$image_id			= $_product->get_image_id();
		$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
		$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
		$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

		$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
		$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));

		$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
		$css_slugGender = array();
		$css_slugCategory = array();
		$css_slugSubCategory = array();
		//print_r($cat);
		foreach($cat as $cvalue)
		{
			if($cvalue->parent != 0)
			{
				$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
				$css_slugSubCategory[] = $cvalue->name;
				$css_slugCategory[] = $term->name;
				if($cvalue->parent == '1818')
				{
					$css_slugGender[] = $cvalue->name;
				}
			}
			else
			{
				if($cvalue->name == 'All Mens')
				{
					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
				}
				elseif($cvalue->name == 'All Womens')
				{
					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
				}
			}
		}

		$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
		$array_logo = array();
		if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
		if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
		if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
		if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
		
		$logoApplicationString = implode(', ', $array_logo);
		if($getQtyRemaining->forderunits >= $sum){
			$stockQty = $getQtyRemaining->forderunits - $sum;
		}else if($getQtyRemaining->forderunits <= $sum){
			$stockQty = 0;
		}else{
			$stockQty = 0;
		}


		$item_name =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) ;
		$product_sku =  $_product->get_sku();
		$delivery_date =  $main_product->get_attribute( 'pa_delivery-date' );
		$prod_brand =  $main_product->get_attribute( 'pa_brand' );
		$gender =  implode(", ", $css_slugGender);
		$category =  implode(", ", $css_slugCategory);
		$sub_category =  implode(", ", $css_slugSubCategory);
		$season =   $main_product->get_attribute( 'pa_season' );
		$composition =  $fabricCompositionString;
		$producto_logo =  $logoApplicationString;
		$unit_sold =  $sum;
		$factory_order =  $getQtyRemaining->forderunits;
		$open_units =  $aq;
		$stock_qty =  $stockQty;
		$order_number = $getQtyRemaining->forderid;

		if (strpos( strtolower($item_name), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($product_sku), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($delivery_date), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($prod_brand), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($gender), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($category), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($sub_category), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($season), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($composition), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($producto_logo), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($unit_sold), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($factory_order), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($open_units), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($stock_qty), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else if(strpos( strtolower($order_number), strtolower($serchText) ) !== false) {
			$get_variations_arr2[] = (object) array( 'vid' => $variation_id, 'item_id' => $data_record->item_id ) ;
		}else{}
			
		
	}
	
	
  }else{}
  

  
	if(!empty($get_variations_arr2)){
		$get_variations_arr = $get_variations_arr2;
	}
	
  if ( !empty ($get_variations_arr ) ) {
    
    foreach($get_variations_arr as $key => $data_record){
		$variation_id = $data_record->vid;
		$itemsIdsArr = explode(",", $data_record->item_id);
		$sum = 0;
		$d = 0;
		foreach($itemsIdsArr as $key4 => $abc)
		{
			$c1 = 0;
			$c5 = 0;
			$last = 0;
				$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
				$ap = wc_get_order_item_meta( $abc, '_qty', true );
				foreach ($variation_size as $key45 => $size) 
				{
					$c1 += $size['value'];
					$merge1[$key][$size['label']][] = $ap * $size['value'];
				}
				
				$sum += $c1 * $ap;
				$merge2 = $c1;
		
			//echo "<p>" . $key4 . " " . $sum . "</p>";
		}
		
		$checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM alpha_wp_factory_order_confirmation_list WHERE `vid`= '$variation_id'");
		$getQtyRemaining = $wpdb->get_row( "SELECT * FROM alpha_wp_factory_order_confirmation_list WHERE vid = $variation_id" );
		
		
		if($sum >= $getQtyRemaining->forderunits )
		{
			$aq = $sum - $getQtyRemaining->forderunits;
		}
		else
		{
			$aq = 0;
		}
		if($checkdataExist == 1)
		{				
		$qty = $getQtyRemaining->fnumber;
		}
		else
		{
			$qty = '';
		}

		$_product =  wc_get_product( $variation_id);
		$main_product = wc_get_product( $_product->get_parent_id() );
		 $image_id			= $_product->get_image_id();
		 $gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
		 $thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
		 $thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

		$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
		$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));

		$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
		$css_slugGender = array();
		$css_slugCategory = array();
		$css_slugSubCategory = array();
		//print_r($cat);
		foreach($cat as $cvalue)
		{
			if($cvalue->parent != 0)
			{
				$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
				$css_slugSubCategory[] = $cvalue->name;
				$css_slugCategory[] = $term->name;
				if($cvalue->parent == '1818')
				{
					$css_slugGender[] = $cvalue->name;
				}
			}
			else
			{
				if($cvalue->name == 'All Mens')
				{
					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
				}
				elseif($cvalue->name == 'All Womens')
				{
					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
				}
			}
		}

		$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
		$array_logo = array();
		if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
		if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
		if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
		if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
		
		$logoApplicationString = implode(', ', $array_logo);
		
		$row3 = "<div class='cart-sizes-attribute'>";
		$row3 .= '<div class="size-guide"><h5>Sizes</h5>';
			foreach ($merge1[$key] as $akkk => $akkkv) {
				$q  = 0;
				$row3 .= "<div class='inner-size'><span>" . $akkk  . "</span>";
				foreach($akkkv as $akkk1 => $akkkv1)
				{
					$q += $akkkv1;
				}
				$row3 .= "<span class='clr_val'>" . $q . "</span>";
				$row3 .= "</div>";
			}
		$row3 .= "</div>";
		$row3 .= "</div>";

		if($getQtyRemaining->forderunits >= $sum){
			$stockQty = $getQtyRemaining->forderunits - $sum;
		}else if($getQtyRemaining->forderunits <= $sum){
			$stockQty = 0;
		}else{
			$stockQty = 0;
		}
		
		$nestedData = array();
		if($getQtyRemaining->vid == $variation_id)
		{
			$nestedData['product_image'] = $thumbnail_src[0];
			$nestedData['item_name' ] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 ;
			$nestedData['product_sku' ] =  $_product->get_sku();
			$nestedData['delivery_date' ] =  $main_product->get_attribute( 'pa_delivery-date' );
			$nestedData['prod_brand' ] =  $main_product->get_attribute( 'pa_brand' );
			$nestedData['gender' ] =  implode(", ", $css_slugGender);
			$nestedData['category' ] =  implode(", ", $css_slugCategory);
			$nestedData['sub_category' ] =  implode(", ", $css_slugSubCategory);
			$nestedData['season' ] =   $main_product->get_attribute( 'pa_season' );
			$nestedData['composition' ] =  $fabricCompositionString;
			$nestedData['producto_logo' ] =  $logoApplicationString;
			$nestedData['unit_sold' ] =  $sum;
			$nestedData['factory_order' ] =  "<input type='number' name='factory_order' class='factory_order' data-variation_id ='" . $variation_id . "' data-minimum_units ='" . $merge2[$variation_id][0] . "' placeholder='Min 24 Units' value='".$getQtyRemaining->forderunits."'/> <span class='for-Excel-only'>".$getQtyRemaining->forderunits."</span>";
			$nestedData['open_units' ] =  $aq;
			$nestedData['stock_qty' ] =  $stockQty;
			$nestedData['order_number' ] =  "<span class='order1-number2'>".$getQtyRemaining->forderid."</span>";
			$nestedData['edit_option' ] =  "<a href='Javascript:void(0);' class='single-submit-it'><i class='ti-save'></i></a>";
		}else{

			$nestedData['product_image'] = $thumbnail_src[0] ;
			$nestedData['item_name' ] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 ;
			$nestedData['product_sku' ] =  $_product->get_sku();
			$nestedData['delivery_date' ] =  $main_product->get_attribute( 'pa_delivery-date' );
			$nestedData['prod_brand' ] =  $main_product->get_attribute( 'pa_brand' );
			$nestedData['gender' ] =  implode(", ", $css_slugGender);
			$nestedData['category' ] =  implode(", ", $css_slugCategory);
			$nestedData['sub_category' ] =  implode(", ", $css_slugSubCategory);
			$nestedData['season' ] =   $main_product->get_attribute( 'pa_season' );
			$nestedData['composition' ] =  $fabricCompositionString;
			$nestedData['producto_logo' ] =  $logoApplicationString;
			$nestedData['unit_sold' ] =  $sum;
			$nestedData['factory_order' ] =  "<input type='number' name='factory_order' class='factory_order' data-variation_id ='" . $variation_id . "' data-minimum_units ='" . $merge2[$variation_id][0] . "' placeholder='Min 24 Units' value='".$sum."'/> <span class='for-Excel-only'>".$sum."</span>";
			$nestedData['open_units' ] =  0;
			$nestedData['stock_qty' ] =  0;
			$nestedData['order_number' ] =  $return_array3 . "<input type='text' name='factory_order_number' class='factory_order_number' placeholder='fex0001'/><div class='add-new'>Add New</div> <span class='order1-number2'></span>";
			$nestedData['edit_option' ] =  "<a href='Javascript:void(0);' class='single-submit-it'><i class='ti-save'></i></a>";

		}

		$data[] = $nestedData;
    }

	
	
    $json_data = array(
      "draw" => intval($request['draw']),
      "recordsTotal" => intval($totalData),
      "recordsFiltered" => intval($totalData),
      "data" => $data
    );

    echo json_encode($json_data);

  } else {

    $json_data = array(
      "data" => array()
    );

    echo json_encode($json_data);
  }
  
  wp_die();

}


add_action( 'wp_ajax_alpha_export_presale4_ss22_place_order_data','alpha_export_presale4_ss22_place_order_data' );
add_action( 'wp_ajax_nopriv_alpha_export_presale4_ss22_place_order_data','alpha_export_presale4_ss22_place_order_data' );
function alpha_export_presale4_ss22_place_order_data(){ 
	
	global $wpdb;
   	$url1 = site_url();
	$path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';
	$base_path = wp_upload_dir();
	$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';
	define('SITEURL', $url1);
	define('SITEPATH', str_replace('\\', '/', $path1));

    

    $dataHeader = json_decode(stripslashes($_POST['getHeaderArray']));
       
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



   //echo $getTotalCountHeader;
   if($_POST['exportData'] != 'All' ){
	    $offset = ($_POST['exportData'] * $_POST['currentPage']) - $_POST['exportData'];
   		$dataBody = $wpdb->get_results("SELECT * from  {$wpdb->prefix}presale4_run_time_v_id Order by id asc LIMIT ".$_POST['exportData']." OFFSET ".$offset."  ");
   }else{
		$dataBody = $wpdb->get_results("SELECT * from  {$wpdb->prefix}presale4_run_time_v_id Order by id asc ");
   }

   $getTotalCountBody = count($dataBody);

   foreach($dataBody as $key => $data_record)
   {
		$variation_id = $data_record->vid;
		$itemsIdsArr = explode(",", $data_record->item_id);
		$sum = 0;
		$d = 0;
		foreach($itemsIdsArr as $key4 => $abc)
		{
			$c1 = 0;
			$c5 = 0;
			$last = 0;
				$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
				$ap = wc_get_order_item_meta( $abc, '_qty', true );
				foreach ($variation_size as $key45 => $size) 
				{
					$c1 += $size['value'];
					$merge1[$key][$size['label']][] = $ap * $size['value'];
				}
				
				$sum += $c1 * $ap;
				$merge2 = $c1;
		
			//echo "<p>" . $key4 . " " . $sum . "</p>";
		}
		
		$checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM alpha_wp_factory_order_confirmation_list WHERE `vid`= '$variation_id'");
		$getQtyRemaining = $wpdb->get_row( "SELECT * FROM alpha_wp_factory_order_confirmation_list WHERE vid = $variation_id" );
		
		
		if($sum >= $getQtyRemaining->forderunits )
		{
			$aq = $sum - $getQtyRemaining->forderunits;
		}
		else
		{
			$aq = 0;
		}
		if($checkdataExist == 1)
		{				
		$qty = $getQtyRemaining->fnumber;
		}
		else
		{
			$qty = '';
		}

		$_product =  wc_get_product( $variation_id);
		$main_product = wc_get_product( $_product->get_parent_id() );
		$image_id			= $_product->get_image_id();
		$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
		$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
		$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

		$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
		$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));

		$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
		$css_slugGender = array();
		$css_slugCategory = array();
		$css_slugSubCategory = array();
		//print_r($cat);
		foreach($cat as $cvalue)
		{
			if($cvalue->parent != 0)
			{
				$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
				$css_slugSubCategory[] = $cvalue->name;
				$css_slugCategory[] = $term->name;
				if($cvalue->parent == '1818')
				{
					$css_slugGender[] = $cvalue->name;
				}
			}
			else
			{
				if($cvalue->name == 'All Mens')
				{
					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
				}
				elseif($cvalue->name == 'All Womens')
				{
					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
				}
			}
		}

		$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
		$array_logo = array();
		if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
		if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
		if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
		if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
		
		$logoApplicationString = implode(', ', $array_logo);
		
		if($getQtyRemaining->forderunits >= $sum){
			$stockQty = $getQtyRemaining->forderunits - $sum;
		}else if($getQtyRemaining->forderunits <= $sum){
			$stockQty = 0;
		}else{
			$stockQty = 0;
		}
		
		$imageUrlThumb = str_replace("https://shop.fexpro.com", "",$thumbnail_src[0]);
		$imageUrlThumb1 = $_SERVER['DOCUMENT_ROOT'] . $imageUrlThumb;
	
		$nestedData = array();
		if($getQtyRemaining->vid == $variation_id)
		{
			$nestedData[] = $imageUrlThumb1;
			$nestedData[] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) ;
			$nestedData[] =  $_product->get_sku();
			$nestedData[] =  $main_product->get_attribute( 'pa_delivery-date' );
			$nestedData[] =  $main_product->get_attribute( 'pa_brand' );
			$nestedData[] =  implode(", ", $css_slugGender);
			$nestedData[] =  implode(", ", $css_slugCategory);
			$nestedData[] =  implode(", ", $css_slugSubCategory);
			$nestedData[] =   $main_product->get_attribute( 'pa_season' );
			$nestedData[] =  $fabricCompositionString;
			$nestedData[] =  $logoApplicationString;
			$nestedData[] =  $sum;
			$nestedData[] = $getQtyRemaining->forderunits;
			$nestedData[] =  $aq;
			$nestedData[] =  $stockQty;
			$nestedData[] =  $getQtyRemaining->forderid;
		}else{

			$nestedData[] = $imageUrlThumb1 ;
			$nestedData[] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 ;
			$nestedData[] =  $_product->get_sku();
			$nestedData[] =  $main_product->get_attribute( 'pa_delivery-date' );
			$nestedData[] =  $main_product->get_attribute( 'pa_brand' );
			$nestedData[] =  implode(", ", $css_slugGender);
			$nestedData[] =  implode(", ", $css_slugCategory);
			$nestedData[] =  implode(", ", $css_slugSubCategory);
			$nestedData[] =   $main_product->get_attribute( 'pa_season' );
			$nestedData[] =  $fabricCompositionString;
			$nestedData[] =  $logoApplicationString;
			$nestedData[] =  $sum;
			$nestedData[] =  $sum;
			$nestedData[] =  0;
			$nestedData[] =  0;
			$nestedData[] =  '';
		}

		$data[] = $nestedData;
	}


	$xlsx_data_new_allBody= $data;

	
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
			

			 case 'Product image':
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
		  $newCounter++;
	   }     //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);
		  
		$rowCount++; 
	
	}  

	//$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);   
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
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
	$objPHPExcel->disconnectWorksheets();
 
	unset($objPHPExcel);
	die();


}




// Apha Summer spring 22 
add_action('wp_ajax_alpha_get_ss22_presale4_data', 'alpha_get_ss22_presale4_data');
add_action('wp_ajax_nopriv_alpha_get_ss22_presale4_data', 'alpha_get_ss22_presale4_data');

function alpha_get_ss22_presale4_data() {
  global $wpdb;	

  header("Content-Type: application/json");
  	$request= $_POST;

  	$get_total_records = $wpdb->get_results("SELECT `vid` from  {$wpdb->prefix}presale4_run_time_v_id Order by id asc ");
	$totalData = count($get_total_records);
	if($request['length'] == -1){
		$request['length'] = $totalData;
	}
  	$get_variations_arr = $wpdb->get_results("SELECT `vid`, `item_id` from  {$wpdb->prefix}presale4_run_time_v_id Order by id ".$request['order'][0]['dir']." LIMIT ".$request['length']." OFFSET ".$request['start']."  ");

	
  	$variations_arr = array();
	if($_POST['purchased'] == 'without-users'){
		foreach($get_variations_arr as $key => $data_record){
			$variations_arr[] = $data_record;
		}
	}else{
		foreach($get_variations_arr as $key => $data_record){
			$variation_id = $data_record->vid;
			$_product =  wc_get_product( $variation_id);
			if( has_term( $_POST['cat_purchased'] , 'product_cat' ,  $_product->get_parent_id() ) )
			{
				$variations_arr[] = $data_record;
			}
		}
	}



	// if(!empty($get_variations_arr2)){
	// 	$get_variations_arr = $get_variations_arr2;
	// }
	
  	if ( !empty ($variations_arr ) ) {
    
    foreach($variations_arr as $key => $data_record){
    	
    			$variation_id = $data_record->vid;
				$_product =  wc_get_product( $variation_id);

				$itemsIdsArr = explode(",", $data_record->item_id);
				$sum = 0;
				$d = 0;
				foreach($itemsIdsArr as $key4 => $abc)
				{
					$c1 = 0;
					$c5 = 0;
					$last = 0;
						$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
						$ap = wc_get_order_item_meta( $abc, '_qty', true );
						foreach ($variation_size as $key45 => $size) 
						{
							$c1 += $size['value'];
							$merge1[$key][$size['label']][] = $ap * $size['value'];
						}
						
						$sum += $c1 * $ap;
						$merge2 = $c1;
				
					//echo "<p>" . $key4 . " " . $sum . "</p>";
				}
				
				
				 $main_product = wc_get_product( $_product->get_parent_id() );
				 $image_id			= $_product->get_image_id();
				 $gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
				 $thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
				 $thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

				$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
				$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));

				$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
				$css_slugGender = array();
				$css_slugCategory = array();
				$css_slugSubCategory = array();
				//print_r($cat);
				foreach($cat as $cvalue)
				{
					if($cvalue->parent != 0)
					{
						$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
						$css_slugSubCategory[] = $cvalue->name;
						$css_slugCategory[] = $term->name;
						if($cvalue->parent == '1818')
						{
							$css_slugGender[] = $cvalue->name;
						}
					}
					else
					{
						if($cvalue->name == 'All Mens')
						{
							$css_slugGender[] = str_replace('All ', '', $cvalue->name);
						}
						elseif($cvalue->name == 'All Womens')
						{
							$css_slugGender[] = str_replace('All ', '', $cvalue->name);
						}
					}
				}

				$e = get_post_meta($variation_id, '_stock', true);
				if($e)
				{
					if($e < 0)
					{
						$e = 0;
					}
					else
					{
						$e = $e * $merge2[$key][0];
					}
				}
				else
				{
					$e = "Stock limit removed";
				}

				$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
				$array_logo = array();
				if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
				if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
				if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
				if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
				
				$logoApplicationString = implode(', ', $array_logo);
				
				$row3 = "<div class='cart-sizes-attribute'>";
				$row3 .= '<div class="size-guide"><h5>Sizes</h5>';
					foreach ($merge1[$key] as $akkk => $akkkv) {
						$q  = 0;
						$row3 .= "<div class='inner-size'><span>" . $akkk  . "</span>";
						foreach($akkkv as $akkk1 => $akkkv1)
						{
							$q += $akkkv1;
						}
						$row3 .= "<span class='clr_val'>" . $q . "</span>";
						$row3 .= "</div>";
					}
				$row3 .= "</div>";
				$row3 .= "</div>";

			
				$apk = 0;
				
				$nestedData = array();
				if($getQtyRemaining->vid == $variation_id)
				{
					
					$nestedData['product_image'] = $thumbnail_src[0];
					$nestedData['item_name' ] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 ;
					$nestedData['product_sku' ] =  $_product->get_sku();
					$nestedData['pa_brand' ] =  $main_product->get_attribute( 'pa_brand' );
					$nestedData['gender' ] =  implode(", ", $css_slugGender);
					$nestedData['category' ] =  implode(", ", $css_slugCategory);
					$nestedData['sub_category' ] =  implode(", ", $css_slugSubCategory);
					$nestedData['season' ] =   $main_product->get_attribute( 'pa_season' );
					$nestedData['composition' ] =  $fabricCompositionString;
					$nestedData['producto_logo' ] =  $logoApplicationString;
					$nestedData['selling_price' ] =  get_post_meta($variation_id, '_regular_price', true);
					$nestedData['total_purchased_unit' ] =  $q;
					$nestedData['open_stock' ] =  $e;
					$nestedData['total_amount' ] =  wc_price(get_post_meta($variation_id, '_regular_price', true) * $q);
					
				}else{

					$nestedData['product_image'] = $thumbnail_src[0] ;
					$nestedData['item_name' ] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 ;
					$nestedData['product_sku' ] =  $_product->get_sku();
					$nestedData['pa_brand' ] =  $main_product->get_attribute( 'pa_brand' );
					$nestedData['gender' ] =  implode(", ", $css_slugGender);
					$nestedData['category' ] =  implode(", ", $css_slugCategory);
					$nestedData['sub_category' ] =  implode(", ", $css_slugSubCategory);
					$nestedData['season' ] =   $main_product->get_attribute( 'pa_season' );
					$nestedData['composition' ] =  $fabricCompositionString;
					$nestedData['producto_logo' ] =  $logoApplicationString;
					$nestedData['selling_price' ] = get_post_meta($variation_id, '_regular_price', true);
					$nestedData['total_purchased_unit' ] =  $q;
					$nestedData['open_stock' ] =  $e;
					$nestedData['total_amount' ] =  wc_price(get_post_meta($variation_id, '_regular_price', true) * $q);
					

				}

				$data[] = $nestedData;
	
	
    }

	
	
    $json_data = array(
      "draw" => intval($request['draw']),
      "recordsTotal" => intval($totalData),
      "recordsFiltered" => intval($totalData),
      "data" => $data
    );

    echo json_encode($json_data);

  } else {

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



	// Export View Factory Data 
	add_action( 'wp_ajax_alpha_presale4_export_view_factory_data','alpha_presale4_export_view_factory_data' );
	add_action( 'wp_ajax_nopriv_alpha_presale4_export_view_factory_data','alpha_presale4_export_view_factory_data' );
	function alpha_presale4_export_view_factory_data(){ 
		global $wpdb;
		$url1 = site_url();
		$path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';
		$base_path = wp_upload_dir();
		$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';
		define('SITEURL', $url1);
		define('SITEPATH', str_replace('\\', '/', $path1));

		$request= $_POST;

		$dataHeader = json_decode(stripslashes($_POST['getHeaderArray']));


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

		$get_variations_arr = $wpdb->get_results("SELECT `vid`, `item_id` from  {$wpdb->prefix}presale4_run_time_v_id");

		$variations_arr = array();
		if($_POST['purchased'] == 'without-users'){
			foreach($get_variations_arr as $key => $data_record){
				$variations_arr[] = $data_record;
			}
		}else{
			foreach($get_variations_arr as $key => $data_record){
				$variation_id = $data_record->vid;
				$_product =  wc_get_product( $variation_id);
				if( has_term( $_POST['cat_purchased'] , 'product_cat' ,  $_product->get_parent_id() ) )
				{
					$variations_arr[] = $data_record;
				}
			}
		}


  
		foreach($variations_arr as $key => $data_record){


			$variation_id = $data_record->vid;
			$itemsIdsArr = explode(",", $data_record->item_id);
			$sum = 0;
			$d = 0;
			foreach($itemsIdsArr as $key4 => $abc)
			{
				$c1 = 0;
				$c5 = 0;
				$last = 0;
					$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
					$ap = wc_get_order_item_meta( $abc, '_qty', true );
					foreach ($variation_size as $key45 => $size) 
					{
						$c1 += $size['value'];
						$merge1[$key][$size['label']][] = $ap * $size['value'];
					}
					
					$sum += $c1 * $ap;
					$merge2 = $c1;
			
				//echo "<p>" . $key4 . " " . $sum . "</p>";
			}
	
			 $_product =  wc_get_product( $variation_id);
			 $main_product = wc_get_product( $_product->get_parent_id() );
			 $image_id			= $_product->get_image_id();
			 $gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
			 $thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
			 $thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

			$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
			$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));

			$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
			$css_slugGender = array();
			$css_slugCategory = array();
			$css_slugSubCategory = array();
			//print_r($cat);
			foreach($cat as $cvalue)
			{
				if($cvalue->parent != 0)
				{
					$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
					$css_slugSubCategory[] = $cvalue->name;
					$css_slugCategory[] = $term->name;
					if($cvalue->parent == '1818')
					{
						$css_slugGender[] = $cvalue->name;
					}
				}
				else
				{
					if($cvalue->name == 'All Mens')
					{
						$css_slugGender[] = str_replace('All ', '', $cvalue->name);
					}
					elseif($cvalue->name == 'All Womens')
					{
						$css_slugGender[] = str_replace('All ', '', $cvalue->name);
					}
				}
			}

			$e = get_post_meta($variation_id, '_stock', true);
			if($e)
			{
				if($e < 0)
				{
					$e = 0;
				}
				else
				{
					$e = $e * $merge2[$key][0];
				}
			}
			else
			{
				$e = "Stock limit removed";
			}

			$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
			$array_logo = array();
			if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
			if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
			if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
			if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
			
			$logoApplicationString = implode(', ', $array_logo);

			$imageUrlThumb = str_replace("https://shop.fexpro.com", "",$thumbnail_src[0]);
			$imageUrlThumb1 = $_SERVER['DOCUMENT_ROOT'] . $imageUrlThumb;

			$apk = 0;
			
			$nestedData = array();
			if($getQtyRemaining->vid == $variation_id)
			{
				
				$nestedData[] = $imageUrlThumb1;
				$nestedData[] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) ;
				$nestedData[] =  $_product->get_sku();
				$nestedData[] =  $main_product->get_attribute( 'pa_brand' );
				$nestedData[] =  implode(", ", $css_slugGender);
				$nestedData[] =  implode(", ", $css_slugCategory);
				$nestedData[] =  implode(", ", $css_slugSubCategory);
				$nestedData[] =   $main_product->get_attribute( 'pa_season' );
				$nestedData[] =  $fabricCompositionString;
				$nestedData[] =  $logoApplicationString;
				$nestedData[] =  get_post_meta($variation_id, '_regular_price', true);
				$nestedData[] =  $q;
				$nestedData[] =  $e;
				$nestedData[] =  (get_post_meta($variation_id, '_regular_price', true) * $q);
				
			}else{

				$nestedData[] = $imageUrlThumb1 ;
				$nestedData[] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' )  ;
				$nestedData[] =  $_product->get_sku();
				$nestedData[] =  $main_product->get_attribute( 'pa_brand' );
				$nestedData[] =  implode(", ", $css_slugGender);
				$nestedData[] =  implode(", ", $css_slugCategory);
				$nestedData[] =  implode(", ", $css_slugSubCategory);
				$nestedData[] =   $main_product->get_attribute( 'pa_season' );
				$nestedData[] =  $fabricCompositionString;
				$nestedData[] =  $logoApplicationString;
				$nestedData[] = get_post_meta($variation_id, '_regular_price', true);
				$nestedData[] =  $q;
				$nestedData[] =  $e;
				$nestedData[] =  (get_post_meta($variation_id, '_regular_price', true) * $q);
				

			}

			$data[] = $nestedData;

			
	   }
		$xlsx_data_new_allBody= $data;

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
				

				case 'Product image':
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
			$newCounter++;
		}     //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);
			
			$rowCount++; 
		
		}  

		//$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);   
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
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
		$objPHPExcel->disconnectWorksheets();
	
		unset($objPHPExcel);
		die();


}
// END Alpha view factory order list





add_action('wp_ajax_alpha_presale4_factory_order_list_datatables', 'alpha_presale4_factory_order_list_datatables');
add_action('wp_ajax_nopriv_alpha_presale4_factory_order_list_datatables', 'alpha_presale4_factory_order_list_datatables');

function alpha_presale4_factory_order_list_datatables() {
	global $wpdb;	
	$return_array = array();
	$return_array1 = array();
	$return_array2 = array();
	$return_array3 = array();
	$merge1 = array();
	$merge = array();
	header("Content-Type: application/json");
	$request= $_POST;


	$getAllSupplier = $wpdb->get_results("SELECT * FROM alpha_wp_factory_list", ARRAY_A );
	$return_array3 = "<select class='factory_name'>";
	$return_array3 .= "<option value=''>Select Factory</option>";
	foreach($getAllSupplier as $value)
	{
		$return_array3 .= "<option value='" . $value['supplier_name'] . "'>" . $value['supplier_name'] . "</option>";
	}
	$return_array3 .= "</select'>";

	$get_total_records = $wpdb->get_results("SELECT `id` from  alpha_wp_ss22_presale4_factory_order_confirmation_list Order by id asc ");
	if(count($get_total_records) != 0){
			$totalData = count($get_total_records);
		if($request['length'] == -1){
			$request['length'] = $totalData;
		}

	}else{
		$totalData = 0;
	}

	

	$get_variations_arr = $wpdb->get_results("SELECT * from  alpha_wp_ss22_presale4_factory_order_confirmation_list Order by id ".$request['order'][0]['dir']." LIMIT ".$request['length']." OFFSET ".$request['start']."  ");

	foreach($get_variations_arr as $abc)
	{
		
		$vID = $abc->vid;
		$variation = wc_get_product($abc->vid);
		$allData = $wpdb->get_results("SELECT `order_item_id`,`order_id`   FROM {$wpdb->prefix}wc_order_product_lookup WHERE `variation_id` = '$vID'", ARRAY_A );
		foreach($allData as $bk)
		{
			if ( get_post_status ( $bk['order_id'] ) != 'wc-presale4' ) 
			{
				continue;
			}
			else
			{
				$return_array1[$abc->vid][] = $bk['order_item_id'];
			}
		}
	
	
	}
	$j = 0;
	foreach($return_array1 as $key3 => $value3)
	{
		

		$sum = 0;
		foreach($value3 as $key4 => $abc)
		{
			$c1 = 0;
			
				$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
				$get_variation_id = wc_get_order_item_meta( $abc, '_variation_id', true );
				$ap = wc_get_order_item_meta( $abc, '_qty', true );
				if(empty($ap))
				{
					$ap = 0;
				}
				else
				{
					$ap = $ap;
				}
				if(!in_array($abc, $return_array2))
				{
					if($get_variation_id == $key3)
					{
						//echo $key3 . " - " . $abc . " - " . $ap . "<br>";
						
						foreach ($variation_size as $key => $size) 
						{
							
							$c1 += $size['value'];
							/* if(!in_array($label, $return_array3))
							{
								array_push($return_array3, $label);
							} */
							//echo $key3 . " - " . $size['label'] . " = " . $ap * $size['value'] . "<br>";
							$merge1[$key3][$size['label']][] = $ap * $size['value'];
							$merge3[$size['label']] = $size['label'];
						}
						
					}
					array_push($return_array2, $abc);
				}
				
				$sum += $c1 * $ap; 
				
			//echo "<p>" . $key4 . " " . $sum . "</p>";
		}
		$merge[$key3][] = $sum;
		
	}


	if ( !empty ($get_variations_arr ) ) {
		$kl = array();
		foreach($get_variations_arr as $key => $data_record){
			if(!empty(wc_get_product( $data_record->vid)))
			{
				$variation_id = $data_record->vid;
				$productParentId = wp_get_post_parent_id($variation_id);
				$_product =  wc_get_product( $variation_id);

				if($data_record->deliverydate == '0000-00-00'){
					$pa_delivery_date = wc_get_product_terms( $_product->get_parent_id(), 'pa_delivery-date' );
					$data_record->deliverydate = date("Y-m-d", strtotime($pa_delivery_date[0]->name));	
				}
				$file = get_field('custom_pdf', $productParentId);
				if(!empty($file))
				{
					$pdf = $file;
					$target = 'target="_blank"';
					$pdf1 = $file;
				}
				else
				{
					$pdf = "Javascript:void(0);";
					$pdf1 = '';
					$target = '';
				}

				
				$image_id			= $_product->get_image_id();
				$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
				$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
				$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

				$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
				$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));
				$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
				$Other_Row = "";
			}else{
				$_parent_product =  wc_get_product($_product->get_parent_id());
				if(!in_array($_parent_product->get_sku(), $kl))
				{
					$Other_Row =  "<span class='no-v1' style='display:none'>This Style SKU is not avialable in Presale4 anymore : <strong>". $_parent_product->get_sku() . "</strong>. Please remove from Factory Order List. <strong>(Marked with Red Color Row)</strong></span>";
				}
			}

			($merge[$data_record->vid][0] >= $data_record->forderunits ) ? $alk = $merge[$data_record->vid][0] - $data_record->forderunits : $alk = "0";

			$array_logo = array();
			if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
			if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
			if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
			if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
			
			$logoApplicationString = implode(', ', $array_logo);
	

			$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
			$css_slugGender = array();
			$css_slugCategory = array();
			$css_slugSubCategory = array();
			//print_r($cat);
			foreach($cat as $cvalue)
			{
				if($cvalue->parent != 0)
				{
					$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
					$css_slugSubCategory[] = $cvalue->name;
					$css_slugCategory[] = $term->name;
					
					
					if($cvalue->parent == '1818')
					{
						$css_slugGender[] = $cvalue->name;
					}
				}
				else
				{
					if($cvalue->name == 'All Mens')
					{
						$css_slugGender[] = str_replace('All ', '', $cvalue->name);
					}
					elseif($cvalue->name == 'All Womens')
					{
						$css_slugGender[] = str_replace('All ', '', $cvalue->name);
					}
					//$css_slugGender[] = $cvalue->name;
				}
			}

			

			$row3 = "<div class='cart-sizes-attribute'>";
			$row3 .= '<div class="size-guide"><h5>Sizes</h5>';
			if(!empty($merge1[$data_record->vid]))
			{
				$k = '';
				foreach ($merge1[$data_record->vid] as $akkk => $akkkv) {
					$q  = 0;
					$row3 .= "<div class='inner-size'><span>" . $akkk  . "</span>";
					foreach($akkkv as $akkk1 => $akkkv1)
					{
						$q += $akkkv1;
					}
					$row3 .= "<span class='clr_val'>" . $q . "</span>";
					$row3 .= "</div>";
				}
			}
			else
			{
				$k = 'red';
			}
			$row3 .= "</div>";
			$row3 .= "</div>";

			$nestedData = array();

			$nestedData['action_edit'] = "<a href='Javascript:void(0);' class='single-submit-it'><i class='ti-save'></i></a>".$Other_Row;
			$nestedData['action_delete'] = "<a href='Javascript:void(0);' class='single-delete-it'><i class='ti-trash'></i></a>";
			$nestedData['order_number'] = "<span class='onumber'> <input type='text' name='textContent'  value='" . $data_record->forderid . "' data-tabVid='".$data_record->vid."' disabled /> </span> <span class='EditOrderNumber'>Edit Order Number</span> ";
			$nestedData['product_image'] = $thumbnail_src[0];
			$nestedData['item_name' ] = $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3;
			$nestedData['product_sku' ] = $_product->get_sku();
			$nestedData['gender' ] = implode(", ", $css_slugGender);
			$nestedData['category' ] = implode(", ", $css_slugCategory) ;
			$nestedData['sub_category' ] = (!empty($css_slugSubCategory)) ? implode(", ", $css_slugSubCategory) : '';
			$nestedData['composition' ] = $fabricCompositionString;
			$nestedData['producto_logo' ] = $logoApplicationString;
			$nestedData['unit_sold' ] =  $merge[$data_record->vid][0];
			$nestedData['factory_order' ] = "<input type='number' name='factory_order' class='factory_order' data-variation_id ='" . $data_record->vid . "' value='" . $data_record->forderunits ."'/><span class='for-Excel-only'>" . $data_record->forderunits . "</span>" ;
			$nestedData['open_units' ] =   $alk;
			$nestedData['factory_name' ] =   $return_array3 . " <input type='hidden' /><span class='order1-number2'>" .$data_record->factoryname . "</span>" ;
			$nestedData['delivery_date' ] = "<input type='date' class='delivery-date' value='" . $data_record->deliverydate . "'/><span class='deliverydate-value'>". $data_record->deliverydate ."</span>" ;
			$nestedData['cost_price' ] = "<input type='number' class='cost-price' placeholder='$' value='" . $data_record->costprice . "'/><span class='costprice'>" . $data_record->costprice . "</span>";
			$nestedData['comments' ] =  "<textarea class='comments' placeholder='Add comments' l'>" . $data_record->comments . "</textarea><span class='comments-add'>" . $data_record->comments . "</span>";
			$nestedData['pdf_download' ] =  "<a href='$pdf' $target>Download</a><span class='pdf-add'>$pdf1</span>";
		
			$data[] = $nestedData;

		}

		

		$json_data = array(
			"draw" => intval($request['draw']),
			"recordsTotal" => intval($totalData),
			"recordsFiltered" => intval($totalData),
			"data" => $data
		);
	
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



// Alpha adding factroy data 

add_action( 'wp_ajax_alpha_adding_presale4_ss22_factory_data','alpha_adding_presale4_ss22_factory_data' );
add_action( 'wp_ajax_nopriv_alpha_adding_presale4_ss22_factory_data','alpha_adding_presale4_ss22_factory_data' );
function alpha_adding_presale4_ss22_factory_data(){
   global $wpdb;
   $date=date('Y-m-d');
   
   $getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];
   $getCurrentRowFactoryUnits = $_REQUEST['getCurrentRowFactoryUnits'];
   $getCurrentRowFactoryOrder = $_REQUEST['getCurrentRowFactoryOrder'];
   
   $checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM alpha_wp_ss22_presale4_factory_order_confirmation_list WHERE `vid`= '$getCurrentRowVariationID' AND `forderid` = '$getCurrentRowFactoryOrder' ");
   if($checkdataExist == 1)
   {
      $wpdb->update( 
         "alpha_wp_ss22_presale4_factory_order_confirmation_list", 
         array( 
            'forderunits' => $getCurrentRowFactoryUnits,       
            'new' => '',         
            'update' => 'updated',        
            'update_date' => $date,       
         ), 
         array( 'vid' => $getCurrentRowVariationID ), 
         array( '%s','%s','%s','%s','%s','%s','%s','%s' ), 
         array( '%d' ) 
      );
   }
   else
   {  
      $wpdb->insert("alpha_wp_ss22_presale4_factory_order_confirmation_list", array(
         'vid' => $getCurrentRowVariationID,
         'forderid' => $getCurrentRowFactoryOrder,
         'forderunits' => $getCurrentRowFactoryUnits,
         'fnumber' => '',
         'factoryname' => '',
         'deliverydate' => '',
         'costprice' => '',
         'new' => 'New entry',
         'new_insert_date' => $date,
         'update' => '',
         'update_date' => '',
      ));
   } 
   
   $getallOrdersNumbers = $wpdb->get_results("SELECT DISTINCT `forderid` FROM alpha_wp_ss22_presale4_factory_order_confirmation_list", ARRAY_A );   
   echo  "<option value=''>Select Order No.</option>";
   foreach($getallOrdersNumbers as $value)
   {
      echo "<option value='" . $value['forderid'] . "'>" . $value['forderid'] . "</option>";
   }
   echo "</select'>";
  
   die();
}




add_action( 'wp_ajax_alpha_save_presale4_ss22_factory_data_order_number','alpha_save_presale4_ss22_factory_data_order_number' );
add_action( 'wp_ajax_nopriv_alpha_save_presale4_ss22_factory_data_order_number','alpha_save_presale4_ss22_factory_data_order_number' );
function alpha_save_presale4_ss22_factory_data_order_number(){
   global $wpdb;
   if($_REQUEST['action'] == 'alpha_save_presale4_ss22_factory_data_order_number'){
      $tabVid = $_REQUEST['tabVid'];
      $orderNumberText = $_REQUEST['orderNumberText'];
      if(!empty($orderNumberText)){
         $wpdb->update( 
            "alpha_wp_ss22_presale4_factory_order_confirmation_list", 
            array( 
               'forderid' => $orderNumberText,       
               'update' => 'updated',        
            ), 
            array( 'vid' => $tabVid ), 
            array( '%s'), 
            array( '%d' ) 
         );
         echo "edited";  
         die();
      }else{
         echo "Not edited";  
         die();   
      }
   }
   

}





add_action( 'wp_ajax_alpha_edit_presale4_ss22_factory_data','alpha_edit_presale4_ss22_factory_data' );
add_action( 'wp_ajax_nopriv_alpha_edit_presale4_ss22_factory_data','alpha_edit_presale4_ss22_factory_data' );
function alpha_edit_presale4_ss22_factory_data(){
   global $wpdb;
   $date=date('Y-m-d');
   
   $getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];
   $getCurrentRowFactoryUnits = $_REQUEST['getCurrentRowFactoryUnits'];
   $getCurrentRowFactoryNameSelect = $_REQUEST['getCurrentRowFactoryNameSelect'] != '' ? $_REQUEST['getCurrentRowFactoryNameSelect'] : '';
   $getCurrentRowFactoryNamecartoon_dimensions = $_REQUEST['getCurrentRowFactoryNamecartoon_dimensions'] != '' ? $_REQUEST['getCurrentRowFactoryNamecartoon_dimensions'] : '';
   $getCurrentRowFactoryNamecbms_x_ctn = $_REQUEST['getCurrentRowFactoryNamecbms_x_ctn'] != '' ? $_REQUEST['getCurrentRowFactoryNamecbms_x_ctn'] : '';
   $getCurrentRowFactoryNameweight_x_ctn = $_REQUEST['getCurrentRowFactoryNameweight_x_ctn'] != '' ? $_REQUEST['getCurrentRowFactoryNameweight_x_ctn'] : '';
   $getCurrentRowFactoryNamefabric = $_REQUEST['getCurrentRowFactoryNamefabric'] != '' ? $_REQUEST['getCurrentRowFactoryNamefabric'] : '';
   $getCurrentRowFactoryOrderDate = $_REQUEST['getCurrentRowFactoryOrderDate'] != '' ? $_REQUEST['getCurrentRowFactoryOrderDate'] : '';
   $getCurrentRowFactoryOrderCost = $_REQUEST['getCurrentRowFactoryOrderCost'] != '' ? $_REQUEST['getCurrentRowFactoryOrderCost'] : '';
   $getCurrentRowFactoryOrdercomments = $_REQUEST['getCurrentRowFactoryOrdercomments'] != '' ? $_REQUEST['getCurrentRowFactoryOrdercomments'] : '';
   
   $wpdb->update( 
         "alpha_wp_ss22_presale4_factory_order_confirmation_list", 
         array( 
            'forderunits' => $getCurrentRowFactoryUnits,       
            'factoryname' => $getCurrentRowFactoryNameSelect,        
            'cartoon_dimensions' => $getCurrentRowFactoryNamecartoon_dimensions,       
            'cbms_x_ctn' => $getCurrentRowFactoryNamecbms_x_ctn,        
            'weight_x_ctn' => $getCurrentRowFactoryNameweight_x_ctn,       
            'fabric' => $getCurrentRowFactoryNamefabric,       
            'deliverydate' => $getCurrentRowFactoryOrderDate,        
            'costprice' => $getCurrentRowFactoryOrderCost,        
            'comments' => $getCurrentRowFactoryOrdercomments,        
            'new' => '',         
            'update' => 'updated',        
            'update_date' => $date,       
         ), 
         array( 'vid' => $getCurrentRowVariationID ), 
         array( '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s' ), 
         array( '%d' ) 
      );


   echo "edited";

   die();
}




add_action( 'wp_ajax_alpha_export_presale4_factory_order_list_data','alpha_export_presale4_factory_order_list_data' );
add_action( 'wp_ajax_nopriv_alpha_export_presale4_factory_order_list_data','alpha_export_presale4_factory_order_list_data' );
function alpha_export_presale4_factory_order_list_data(){ 
		
		global $wpdb;
		$url1 = site_url();
		$path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';
		$base_path = wp_upload_dir();
		$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';
		define('SITEURL', $url1);
		define('SITEPATH', str_replace('\\', '/', $path1));

		

		$dataHeader = json_decode(stripslashes($_POST['getHeaderArray']));
		if(!empty($_POST['hide_columns'])){
			$dataHeader1 = json_decode(stripslashes($_POST['getHeaderArray']));
			unset($dataHeader1[0]);
			unset($dataHeader1[1]);
			$dataHeader = array_values($dataHeader1);
		}

		
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
	


	//echo $getTotalCountHeader;
	if($_POST['exportData'] != 'All' ){
			$offset = ($_POST['exportData'] * $_POST['currentPage']) - $_POST['exportData'];
			$dataBody = $wpdb->get_results("SELECT * from  alpha_wp_ss22_presale4_factory_order_confirmation_list Order by id asc LIMIT ".$_POST['exportData']." OFFSET ".$offset."  ");
	}else{
			$dataBody = $wpdb->get_results("SELECT * from  alpha_wp_ss22_presale4_factory_order_confirmation_list Order by id asc ");
	}

	foreach($dataBody as $abc)
		{
			
			$vID = $abc->vid;
			$variation = wc_get_product($abc->vid);
			$allData = $wpdb->get_results("SELECT `order_item_id`,`order_id`   FROM {$wpdb->prefix}wc_order_product_lookup WHERE `variation_id` = '$vID'", ARRAY_A );
			foreach($allData as $bk)
			{
				if ( get_post_status ( $bk['order_id'] ) != 'wc-presale3' ) 
				{
					continue;
				}
				else
				{
					$return_array1[$abc->vid][] = $bk['order_item_id'];
				}
			}
		
		
		}
		$j = 0;
		foreach($return_array1 as $key3 => $value3)
		{
			

			$sum = 0;
			foreach($value3 as $key4 => $abc)
			{
				$c1 = 0;
				
					$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
					$get_variation_id = wc_get_order_item_meta( $abc, '_variation_id', true );
					$ap = wc_get_order_item_meta( $abc, '_qty', true );
					if(empty($ap))
					{
						$ap = 0;
					}
					else
					{
						$ap = $ap;
					}
					if(!in_array($abc, $return_array2))
					{
						if($get_variation_id == $key3)
						{
							//echo $key3 . " - " . $abc . " - " . $ap . "<br>";
							
							foreach ($variation_size as $key => $size) 
							{
								
								$c1 += $size['value'];
								/* if(!in_array($label, $return_array3))
								{
									array_push($return_array3, $label);
								} */
								//echo $key3 . " - " . $size['label'] . " = " . $ap * $size['value'] . "<br>";
								$merge1[$key3][$size['label']][] = $ap * $size['value'];
								$merge3[$size['label']] = $size['label'];
							}
							
						}
						array_push($return_array2, $abc);
					}
					
					$sum += $c1 * $ap; 
					
				//echo "<p>" . $key4 . " " . $sum . "</p>";
			}
			$merge[$key3][] = $sum;
			
		}

	$getTotalCountBody = count($dataBody);
	$kl = array();
	foreach($dataBody as $key => $data_record)
	{
			if(!empty(wc_get_product( $data_record->vid)))
			{
				$variation_id = $data_record->vid;
				$productParentId = wp_get_post_parent_id($variation_id);
				$_product =  wc_get_product( $variation_id);

				if($data_record->deliverydate == '0000-00-00'){
					$pa_delivery_date = wc_get_product_terms( $_product->get_parent_id(), 'pa_delivery-date' );
					$data_record->deliverydate = date("Y-m-d", strtotime($pa_delivery_date[0]->name));	
				}
				$file = get_field('custom_pdf', $productParentId);
				if(!empty($file))
				{
					$pdf = $file;
					$target = 'target="_blank"';
					$pdf1 = $file;
				}
				else
				{
					$pdf = "Javascript:void(0);";
					$pdf1 = '';
					$target = '';
				}

				
				$image_id			= $_product->get_image_id();
				$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
				$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
				$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

				$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
				$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));
				$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
				$Other_Row = "";
			}else{
				$_parent_product =  wc_get_product($_product->get_parent_id());
				if(!in_array($_parent_product->get_sku(), $kl))
				{
					$Other_Row =  "<span class='no-v1' style='display:none'>This Style SKU is not avialable in Presale3 anymore : <strong>". $_parent_product->get_sku() . "</strong>. Please remove from Factory Order List. <strong>(Marked with Red Color Row)</strong></span>";
				}
			}

			($merge[$data_record->vid][0] >= $data_record->forderunits ) ? $alk = $merge[$data_record->vid][0] - $data_record->forderunits : $alk = "0";

			$array_logo = array();
			if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
			if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
			if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
			if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
			
			$logoApplicationString = implode(', ', $array_logo);
		

			$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
			$css_slugGender = array();
			$css_slugCategory = array();
			$css_slugSubCategory = array();
			//print_r($cat);
			foreach($cat as $cvalue)
			{
				if($cvalue->parent != 0)
				{
					$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
					$css_slugSubCategory[] = $cvalue->name;
					$css_slugCategory[] = $term->name;
					
					
					if($cvalue->parent == '1818')
					{
						$css_slugGender[] = $cvalue->name;
					}
				}
				else
				{
					if($cvalue->name == 'All Mens')
					{
						$css_slugGender[] = str_replace('All ', '', $cvalue->name);
					}
					elseif($cvalue->name == 'All Womens')
					{
						$css_slugGender[] = str_replace('All ', '', $cvalue->name);
					}
					//$css_slugGender[] = $cvalue->name;
				}
			}

			$imageUrlThumb = str_replace("https://shop.fexpro.com", "",$thumbnail_src[0]);
			$imageUrlThumb1 = $_SERVER['DOCUMENT_ROOT'] . $imageUrlThumb;
		
			$nestedData = array();
		
			$nestedData[] =  $data_record->forderid;
			$nestedData[] =  $imageUrlThumb1;
			$nestedData[] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' );
			$nestedData[] =  $_product->get_sku();
			$nestedData[] =  implode(", ", $css_slugGender);
			$nestedData[] =  implode(", ", $css_slugCategory) ;
			$nestedData[] =  (!empty($css_slugSubCategory)) ? implode(", ", $css_slugSubCategory) : ''; 
			$nestedData[] =  $fabricCompositionString;
			$nestedData[] =  $logoApplicationString;
			$nestedData[] =  $merge[$data_record->vid][0];
			$nestedData[] =  $data_record->forderunits;
			$nestedData[] =  $alk;
			$nestedData[] =  $data_record->factoryname;
			$nestedData[] =  $data_record->deliverydate;
			$nestedData[] =  $data_record->costprice;
			$nestedData[] =  $data_record->comments;
			$nestedData[] =  $pdf1;
			$data[] = $nestedData;
		}


		$xlsx_data_new_allBody= $data;
	
		
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
				

				case 'Product image':
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
			$newCounter++;
		}     //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);
			
			$rowCount++; 
		
	}  

		//$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);   
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
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
		$objPHPExcel->disconnectWorksheets();
	
		unset($objPHPExcel);
		die();


}


add_action( 'wp_ajax_delete_presale4_ss22_single_factory_data','delete_presale4_ss22_single_factory_data' );
add_action( 'wp_ajax_nopriv_delete_presale4_ss22_single_factory_data','delete_presale4_ss22_single_factory_data' );
function delete_presale4_ss22_single_factory_data(){
   global $wpdb;
   
   $getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];
   $wpdb->query(
      'DELETE  FROM alpha_wp_ss22_presale4_factory_order_confirmation_list
      WHERE vid = "'.$getCurrentRowVariationID.'"'
   );
   
   echo "deleted";
   
   die();
}

 

 // alpha create new factory table
add_action( 'wp_ajax_alpha_custom_presale4_add_factory','alpha_custom_presale4_add_factory' );
add_action( 'wp_ajax_nopriv_alpha_custom_presale4_add_factory','alpha_custom_presale4_add_factory' );
function alpha_custom_presale4_add_factory(){
	global $wpdb;

	if(!empty($_REQUEST['fcode']))         { $fcode = $_REQUEST['fcode']; }  else { $fcode = ''; }
	if(!empty($_REQUEST['fname']))         { $fname = trim($_REQUEST['fname']); } else { 	$fname = ''; }
   if(!empty($_REQUEST['supplier_slug'])) { $supplier_slug = trim($_REQUEST['supplier_slug']);  }  else { $supplier_slug = '';  }
	if(!empty($_REQUEST['faddress']))      { $faddress = $_REQUEST['faddress']; } else { 	$faddress = ''; }
	if(!empty($_REQUEST['fperson']))       { $fperson = $_REQUEST['fperson']; } else { 	$fperson = ''; }
	if(!empty($_REQUEST['fphone1']))       { $fphone1 = $_REQUEST['fphone1']; } else { $fphone1 = ''; }
	if(!empty($_REQUEST['fphone2']))       { $fphone2 = $_REQUEST['fphone2']; } else { $fphone2 = ''; }
   if(!empty($_REQUEST['femail'])) 	      { $femail = $_REQUEST['femail']; } else { $femail = ''; }

   $slug_data= sanitize_title_with_dashes( $_REQUEST['fname']);
   $supplier_slug = str_replace("-", "_", $slug_data);

   $file_name = $supplier_slug.'.php';
   $file_name1 = $supplier_slug.'.php';

   //CreateNewFileInToDirecotory($file_name, $_REQUEST['fname']))
   


   if(!empty($fname)){

       $query = $wpdb->prepare('SELECT supplier_name FROM alpha_wp_factory_list WHERE supplier_name = %s', $fname);
       $cID = $wpdb->get_var( $query );
       if ( !empty($cID) ) {
             echo "Not inserted";
       } else {                

            $wpdb->insert("alpha_wp_factory_list", array(
               'sage_code' => $fcode,
               'sage_order_number' => '',
               'supplier_name' => $fname,
               'supplier_slug' => $supplier_slug.'.php',
               'address' => $faddress,
               'contact_person' => $fperson,
               'phone_no' => $fphone1,
               'phone_no2' => $fphone2,
               'email_address' => $femail,
            ));


            $content = "";
            $fp = fopen($_SERVER['DOCUMENT_ROOT']. "/presale4/factory/$file_name","wb");
            fwrite($fp,$content);
            fclose($fp);

            copy($_SERVER['DOCUMENT_ROOT']. "/presale4/factory/text_demo.php" , $_SERVER['DOCUMENT_ROOT']. "/presale4/factory/$file_name");


            $path_to_file = $_SERVER['DOCUMENT_ROOT']. "/presale4/factory/$file_name";
            $write_file = file_get_contents($path_to_file);
            $replace_word = str_replace("eastman",$fname,$write_file);
            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/presale4/factory/$file_name1","wb");
            fwrite($fp,$replace_word);
            fclose($fp);
            


            $old_file = $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/porto-child/kk_exim_order_lists.php';
            $write_file = file_get_contents($old_file);
            $replace_word = str_replace("K.K. Exim",$fname,$write_file);
            $file_name = $fname.'.php';
            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/php/$file_name","wb");
            fwrite($fp,$replace_word);
            fclose($fp);
            echo "inserted";
            
         }
   }else{
      echo "Not inserted";
   }
  


	


	die();	
}


// Export View Factory Data 
	add_action( 'wp_ajax_export_cart_presale4_entries_all_data','export_cart_presale4_entries_all_data' );
	add_action( 'wp_ajax_nopriv_export_cart_presale4_entries_all_data','export_cart_presale4_entries_all_data' );
	function export_cart_presale4_entries_all_data(){ 
		global $wpdb;
		$url1 = site_url();
		$path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';
		$base_path = wp_upload_dir();
		$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';
		define('SITEURL', $url1);
		define('SITEPATH', str_replace('\\', '/', $path1));

		$request= $_POST;

		
		$namee = $request['factory_name'];   

		$result_string = ltrim($namee);
		$result_string = rtrim($result_string);

		if($result_string == 'YANGZHOU YAXIYA HEADWEAR'){
			$namee = 'YANGZHOU YAXIYA HEADWEAR & GAR';
		}
		if($result_string == 'TAIZHOU J'){
			$namee = 'TAIZHOU J&F HEADWEAR';
		}
		if($result_string == 'Dishang Group/Weihai Textile Group Import'){
			$namee = 'Dishang Group/Weihai Textile Group Import & Export Co,. Ltd';
		}

		$dataHeader = json_decode(stripslashes($_POST['getHeaderArray']));
		
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




	$dataBody = $wpdb->get_results("SELECT * FROM alpha_wp_ss22_presale4_factory_order_confirmation_list WHERE `factoryname` = '$namee' Order by id asc  ", ARRAY_A );


	$getTotalCountBody = count($dataBody);



	foreach($dataBody as $key => $data_record)
	{

				if(!empty(wc_get_product( $data_record['vid'] )))
				{
					$_product =  wc_get_product( $data_record['vid']);
					$image_id           = $_product->get_image_id();
					$gallery_thumbnail  = wc_get_image_size( array(100, 100) );
					$thumbnail_size     = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
					$thumbnail_src      = wp_get_attachment_image_src( $image_id, $thumbnail_size );
					
					$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
					$fabricCompositionString = $fabricComposition[0]->name; 
					
					$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
					$otherData = "";


				}else{
					$_parent_product =  wc_get_product($_product->get_parent_id());
					if(!in_array($_parent_product->get_sku(), $kl))
					{
						$otherData = "<span class='no-v1' style='display:none;' >This Style SKU is not for Printing purpose: <strong>". $_parent_product->get_sku() . " (Completely marked with Red Color Row)</strong></span>";
					}
				
				}

				$array_logo = array();
				if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
				if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
				if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
				if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
				
				$logoApplicationString = implode(', ', $array_logo);

				$imageUrlThumb = str_replace("https://shop.fexpro.com", "",$thumbnail_src[0]);
				$imageUrlThumb1 = $_SERVER['DOCUMENT_ROOT'] . $imageUrlThumb;


				$nestedData = array();

				$nestedData[] = $data_record['forderid'];
				$nestedData[] = $imageUrlThumb1;
				$nestedData[] = $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' );
				$nestedData[] = $_product->get_sku() ;
				$nestedData[] = $fabricCompositionString;
				$nestedData[] = $logoApplicationString;
				$nestedData[] = $data_record['forderunits'];
				$nestedData[] = $data_record['factoryname'];
				$nestedData[] = $data_record['deliverydate'];
				$nestedData[] = $data_record['costprice'];
				$nestedData[] = $data_record['forderunits'] * $data_record['costprice'];
				$nestedData[] = $otherData;

				

				$data[] = $nestedData;
		}
						$array_logo = array();
					if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
					if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
					if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
					if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
					
					$logoApplicationString = implode(', ', $array_logo);

					$imageUrlThumb = str_replace("https://shop.fexpro.com", "",$thumbnail_src[0]);
					$imageUrlThumb1 = $_SERVER['DOCUMENT_ROOT'] . $imageUrlThumb;

		
		$xlsx_data_new_allBody= $data;
		
		
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
				

				case 'Product image':
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
			$newCounter++;
		}     //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);
			
			$rowCount++; 
		
		}  

		//$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);   
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
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
		$objPHPExcel->disconnectWorksheets();
	
		unset($objPHPExcel);
		die();

	}

