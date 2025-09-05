<?php 


// add_action('wp_ajax_movie_datatables', 'datatables_server_side_callback');
// add_action('wp_ajax_nopriv_movie_datatables', 'datatables_server_side_callback');


// function datatables_server_side_callback() {
//   global $wpdb;	

//   header("Content-Type: application/json");

//   $request= $_GET;
	
//   $columns = array(
//     0 => 'product_image',
// 	1 => 'item_name',
// 	2 => 'product_sku',
// 	3 => 'delivery_date',
// 	4 => 'prod_brand',
// 	5 => 'gender',
// 	6 => 'category',
// 	7 => 'sub_category',
// 	8 => 'season',
// 	9 => 'composition',
// 	10 => 'producto_logo',
// 	11 => 'unit_sold',
// 	12 => 'factory_order',
// 	13 => 'open_units',
// 	14 => 'stock_qty',
// 	15 => 'order_number',
//   );

//   $getallOrdersNumbers = $wpdb->get_results("SELECT DISTINCT `forderid` FROM {$wpdb->prefix}ss22_factory_order_confirmation_list", ARRAY_A );
// 	$return_array3 = "<select class='onumbers_lists'>";
// 	$return_array3 .= "<option value=''>Select Order No.</option>";
// 	foreach($getallOrdersNumbers as $value)
// 	{
// 		$return_array3 .= "<option value='" . $value['forderid'] . "'>" . $value['forderid'] . "</option>";
// 	}
// 	$return_array3 .= "</select'>";
  

//   $get_variations_arr = $wpdb->get_results("SELECT `vid`, `item_id` from  {$wpdb->prefix}run_time_v_id Order by id ".$request['order'][0]['dir']." LIMIT ".$request['length']." OFFSET ".$request['start']."  ");
  
//   if ($request['order'][0]['column'] == 0) {

//     $args['orderby'] = $columns[$request['order'][0]['column']];

//   } elseif ($request['order'][0]['column'] == 1 || $request['order'][0]['column'] == 2) {
//     $args['orderby'] = 'meta_value_num';

//     $args['meta_key'] = $columns[$request['order'][0]['column']];
//   }
//   //$request['search']['value'] <= Value from search

// //   if( !empty($request['search']['value']) ) { // When datatables search is used
// //     $args['meta_query'] = array(
// //       'relation' => 'OR',
// //       array(
// //         'key' => 'product_sku',
// //         'value' => sanitize_text_field($request['search']['value']),
// //         'compare' => 'LIKE'
// //       )
// //     );
// //   }


  

//   $get_total_records = $wpdb->get_results("SELECT `vid` from  {$wpdb->prefix}run_time_v_id Order by id asc ");
 
//   $totalData = count($get_total_records);
  
//   if ( !empty ($get_variations_arr ) ) {
    
//     foreach($get_variations_arr as $key => $data_record){
// 		$variation_id = $data_record->vid;
// 		$itemsIdsArr = explode(",", $data_record->item_id);
// 		$sum = 0;
// 		$d = 0;
// 		foreach($itemsIdsArr as $key4 => $abc)
// 		{
// 			$c1 = 0;
// 			$c5 = 0;
// 			$last = 0;
// 				$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
// 				$ap = wc_get_order_item_meta( $abc, '_qty', true );
// 				foreach ($variation_size as $key45 => $size) 
// 				{
// 					$c1 += $size['value'];
// 					$merge1[$key][$size['label']][] = $ap * $size['value'];
// 				}
				
// 				$sum += $c1 * $ap;
// 				$merge2 = $c1;
		
// 			//echo "<p>" . $key4 . " " . $sum . "</p>";
// 		}
		
// 		$checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM {$wpdb->prefix}ss22_factory_order_confirmation_list WHERE `vid`= '$variation_id'");
// 		$getQtyRemaining = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}ss22_factory_order_confirmation_list WHERE vid = $variation_id" );
		
		
// 		if($sum >= $getQtyRemaining->forderunits )
// 		{
// 			$aq = $sum - $getQtyRemaining->forderunits;
// 		}
// 		else
// 		{
// 			$aq = 0;
// 		}
// 		if($checkdataExist == 1)
// 		{				
// 		$qty = $getQtyRemaining->fnumber;
// 		}
// 		else
// 		{
// 			$qty = '';
// 		}

// 		$_product =  wc_get_product( $variation_id);
// 		$main_product = wc_get_product( $_product->get_parent_id() );
// 		$image_id			= $_product->get_image_id();
// 		$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
// 		$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
// 		$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

// 		$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
// 		$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));

// 		$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
// 		$css_slugGender = array();
// 		$css_slugCategory = array();
// 		$css_slugSubCategory = array();
// 		//print_r($cat);
// 		foreach($cat as $cvalue)
// 		{
// 			if($cvalue->parent != 0)
// 			{
// 				$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
// 				$css_slugSubCategory[] = $cvalue->name;
// 				$css_slugCategory[] = $term->name;
// 				if($cvalue->parent == '1818')
// 				{
// 					$css_slugGender[] = $cvalue->name;
// 				}
// 			}
// 			else
// 			{
// 				if($cvalue->name == 'All Mens')
// 				{
// 					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
// 				}
// 				elseif($cvalue->name == 'All Womens')
// 				{
// 					$css_slugGender[] = str_replace('All ', '', $cvalue->name);
// 				}
// 			}
// 		}

// 		$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
// 		$array_logo = array();
// 		if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
// 		if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
// 		if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
// 		if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
		
// 		$logoApplicationString = implode(', ', $array_logo);
		
// 		$row3 = "<div class='cart-sizes-attribute'>";
// 		$row3 .= '<div class="size-guide"><h5>Sizes</h5>';
// 			foreach ($merge1[$key] as $akkk => $akkkv) {
// 				$q  = 0;
// 				$row3 .= "<div class='inner-size'><span>" . $akkk  . "</span>";
// 				foreach($akkkv as $akkk1 => $akkkv1)
// 				{
// 					$q += $akkkv1;
// 				}
// 				$row3 .= "<span class='clr_val'>" . $q . "</span>";
// 				$row3 .= "</div>";
// 			}
// 		$row3 .= "</div>";
// 		$row3 .= "</div>";

// 		if($getQtyRemaining->forderunits >= $sum){
// 			$stockQty = $getQtyRemaining->forderunits - $sum;
// 		}else if($getQtyRemaining->forderunits <= $sum){
// 			$stockQty = 0;
// 		}else{
// 			$stockQty = 0;
// 		}
		
// 		$nestedData = array();
// 		if($getQtyRemaining->vid == $variation_id)
// 		{
// 			$nestedData['product_image'] = $thumbnail_src[0];
// 			$nestedData['item_name' ] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 ;
// 			$nestedData['product_sku' ] =  $_product->get_sku();
// 			$nestedData['delivery_date' ] =  $main_product->get_attribute( 'pa_delivery-date' );
// 			$nestedData['prod_brand' ] =  $main_product->get_attribute( 'pa_brand' );
// 			$nestedData['gender' ] =  implode(", ", $css_slugGender);
// 			$nestedData['category' ] =  implode(", ", $css_slugCategory);
// 			$nestedData['sub_category' ] =  implode(", ", $css_slugSubCategory);
// 			$nestedData['season' ] =   $main_product->get_attribute( 'pa_season' );
// 			$nestedData['composition' ] =  $fabricCompositionString;
// 			$nestedData['producto_logo' ] =  $logoApplicationString;
// 			$nestedData['unit_sold' ] =  $sum;
// 			$nestedData['factory_order' ] =  "<input type='number' name='factory_order' class='factory_order' data-variation_id ='" . $variation_id . "' data-minimum_units ='" . $merge2[$variation_id][0] . "' placeholder='Min 24 Units' value='".$getQtyRemaining->forderunits."'/> <span class='for-Excel-only'>".$getQtyRemaining->forderunits."</span>";
// 			$nestedData['open_units' ] =  $aq;
// 			$nestedData['stock_qty' ] =  $stockQty;
// 			$nestedData['order_number' ] =  "<span class='order1-number2'>".$getQtyRemaining->forderid."</span>";
// 		}else{

// 			$nestedData['product_image'] = $thumbnail_src[0] ;
// 			$nestedData['item_name' ] =  $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 ;
// 			$nestedData['product_sku' ] =  $_product->get_sku();
// 			$nestedData['delivery_date' ] =  $main_product->get_attribute( 'pa_delivery-date' );
// 			$nestedData['prod_brand' ] =  $main_product->get_attribute( 'pa_brand' );
// 			$nestedData['gender' ] =  implode(", ", $css_slugGender);
// 			$nestedData['category' ] =  implode(", ", $css_slugCategory);
// 			$nestedData['sub_category' ] =  implode(", ", $css_slugSubCategory);
// 			$nestedData['season' ] =   $main_product->get_attribute( 'pa_season' );
// 			$nestedData['composition' ] =  $fabricCompositionString;
// 			$nestedData['producto_logo' ] =  $logoApplicationString;
// 			$nestedData['unit_sold' ] =  $sum;
// 			$nestedData['factory_order' ] =  "<input type='number' name='factory_order' class='factory_order' data-variation_id ='" . $variation_id . "' data-minimum_units ='" . $merge2[$variation_id][0] . "' placeholder='Min 24 Units' value='".$sum."'/> <span class='for-Excel-only'>".$sum."</span>";
// 			$nestedData['open_units' ] =  0;
// 			$nestedData['stock_qty' ] =  0;
// 			$nestedData['order_number' ] =  $return_array3 . "<input type='text' name='factory_order_number' class='factory_order_number' placeholder='fex0001'/><div class='add-new'>Add New</div> <span class='order1-number2'></span>";

// 		}

// 		$data[] = $nestedData;
//     }

	
	
//     $json_data = array(
//       "draw" => intval($request['draw']),
//       "recordsTotal" => intval($totalData),
//       "recordsFiltered" => intval($totalData),
//       "data" => $data
//     );

//     echo json_encode($json_data);

//   } else {

//     $json_data = array(
//       "data" => array()
//     );

//     echo json_encode($json_data);
//   }
  
//   wp_die();

// }




require_once 'include/common.php';
get_currentuserinfo();

if(is_user_logged_in()) {

?>
<!DOCTYPE html>
<html lang="en">

	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <!-- Tell the browser to be responsive to screen width -->
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta name="description" content="">
	    <meta name="author" content="">
	    <!-- Favicon icon -->
	    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITEURL; ?>/wp-content/uploads/2021/01/logo.png">
	    <title>Fexpro Sage - SS22 Factory Order</title>
	    <!-- chartist CSS -->
	    <link href="<?php echo SAGE_SITEURL; ?>/dist/css/pages/ecommerce.css" rel="stylesheet">
	    <!-- Custom CSS -->
	    <link href="<?php echo SAGE_SITEURL; ?>/dist/css/style.min.css" rel="stylesheet">

	    <link href="<?php echo SAGE_SITEURL; ?>/include/css/custom-fexpro.css" rel="stylesheet">
        
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css" rel="stylesheet">
        

        <style>
	    <?php /* Custom CSS for Table   */ ?>
			tbody#myTable > tr > td:first-child img {width: 100px;}
			span.error {color: #f00;font-size: 10px;display: block;}
			.order_screen_container {float: left;width: 100%;margin-bottom: 15px;background: red;padding: 15px;}
			a.submit-it {background: black;	color: #fff;padding: .375rem .75rem;font-size: 1rem;height: calc(1.5em + .75rem + 2px);line-height: 1.5;width: auto;float: right;}	
			a.single-submit-it {background: black;	color: #fff;padding: .375rem .75rem;font-size: 1rem;height: calc(1.5em + .75rem + 2px);line-height: 1.5;}
			.order_screen_container input {	float: left;width: auto;}
			tbody#myTable tr td .red {border-color: red !important;	}
			table#demo thead tr.fltrow > td:first-child input, table#demo thead tr.fltrow > th:last-child input {display: none !important;}
			.cart-sizes-attribute {	min-width: 350px;width: 100%;margin-top: 20px; }
			.cart-sizes-attribute .size-guide h5 {   border: solid 1px #000;padding: 20px 9px!important;}
			.size-guide h5{color: #000; font-size: 13px; font-weight: 700; line-height: 20px;margin-bottom: 0;	}
			.cart-sizes-attribute .size-guide {   display: -webkit-box;display: -ms-flexbox;display: flex;}
			.inner-size {display: block;width: 100%;-webkit-box-align: center;-ms-flex-align: center;align-items: center;text-align: center;}
			.cart-sizes-attribute .size-guide .inner-size {	border: solid 1px #000;	border-right: 0;	border-left: 0;	}
			.inner-size span:first-child {font-weight: bold;background: #008188;color: #fff;}
			.inner-size span {display: block;width: 100%;border-bottom: solid 1px #000;	border-right: 1px solid #000;	color: #000;padding: 5px 10px;}
			span#exportexcel {background: #000;	color: #fff;cursor: pointer;font-size: 24px;text-align: center;font-weight: bold;margin-bottom: 15px;padding: 5px 15px;
			display: inline-block;	margin-left: 5px;border-radius: 5px;transition: all 0.2s ease;}
			span#exportexcel:hover {background: #b41520;}
			span#stop-refresh {	display: none;color: #f00;	font-size: 18px;margin-left: 5px;margin-bottom: 15px;width: 100%;}
			.for-Excel-only, .factory_order_number{display: none;}
			input.factory_order_number {margin-top: 10px;}
			.add-new {	cursor: pointer;background: #000;display: inline-block;color: #fff;	padding: 5px;border-radius: 5px;margin: 10px 0;}
			.order1-number2, .only1 {text-align: center;  font-size: 17px;  color: #f00; font-weight: bold;}
			.show .order1-number2{display: block;}
			.adding-data:before {content: ""; background: rgba(0,0,0,0.5);  z-index: 2; position: absolute; width: 100%; height: 100%;}
			table caption{caption-side: top;}
			.TF.sticky tr.fltrow th {top: -1px !important;  background: #af0f2c;}
			.TF.sticky th { top: 33px !important;}
			body table.TF tr.fltrow th { border-bottom: 1px solid #000; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;padding: 0; color: #fff;}
			a.submit-in-one { position: fixed; bottom: 0; background: #af0f2c; color: #fff; padding: 5px;  right: 0; font-size: 18px;  cursor: pointer;}
			input.factory_order {  max-width: 110px;}
			select.rspg + span {  display: none;}
			div#demo_table_wrapper.dt-bootstrap {
				display: flex;
			}
			div#demo_table_wrapper.dt-bootstrap > .row:last-child {
			order: -1;
			width: 100%;
			}
			div#demo_table_wrapper.dt-bootstrap > .row:last-child > div {
			flex: 0 0 25%;
			max-width: 25%;
			}
			div#demo_table_wrapper table {
    max-width: 100%;
    display: block;
    overflow-x: scroll;
    width: 98% !important;
}

div#demo_table_filter, select.form-control.input-sm {
    background: #fff;
}



	  </style>






	    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	    <!--[if lt IE 9]>
	    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->


	</head>
	<body class="skin-default fixed-layout">
	    <!-- ============================================================== -->
	    <!-- Preloader - style you can find in spinners.css -->
	    <!-- ============================================================== -->
	    <div class="preloader">
	        <div class="loader">
	            <div class="loader__figure"></div>
	            <p class="loader__label">Fexpro Sage admin</p>
	        </div>
	    </div>
	    <!-- ============================================================== -->
	    <!-- Main wrapper - style you can find in pages.scss -->
	    <!-- ============================================================== -->
	    <div id="main-wrapper">
	        <!-- ============================================================== -->
	        <!-- Topbar header - style you can find in pages.scss -->
	        <!-- ============================================================== -->
	        <header class="topbar">
	            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
	                <!-- ============================================================== -->
	                <!-- Logo -->
	                <!-- ============================================================== -->
	                <div class="navbar-header">
	                    <a class="navbar-brand" href="<?php echo SAGE_SITEURL; ?>/">
	                        <!-- Logo icon --><b>
	                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
	                            <!-- Dark Logo icon -->
	                            <img src="<?php echo SAGE_SITEURL; ?>/logo.webp" alt="homepage" class="dark-logo" />
	                            <!-- Light Logo icon -->
	                            
	                        </b>
	                    </a>
	                        <!--End Logo icon -->
	                        <!-- Logo text --><span>
	                         <!-- dark Logo text -->
	                         
	                </div>
	                <!-- ============================================================== -->
	                <!-- End Logo -->
	                <!-- ============================================================== -->
	                <div class="navbar-collapse">
	                    <!-- ============================================================== -->
	                    <!-- toggle and nav items -->
	                    <!-- ============================================================== -->
	                    <ul class="navbar-nav mr-auto">
	                        <!-- This is  -->
	                        <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
	                        <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
	                       
	                        
	                    </ul>
	                    
	                    <ul class="navbar-nav my-lg-0">
	                        
	                        <li class="nav-item right-side-toggle"> <a class="nav-link  waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li>
	                    </ul>
	                </div>
	            </nav>
	        </header>
	        
	        <?php require_once('include/sidebar.php'); ?>

	        
	        <div class="page-wrapper">
	            <!-- ============================================================== -->
	            <!-- Container fluid  -->
	            <!-- ============================================================== -->
	            <div class="container-fluid">
	                <!-- ============================================================== -->
	                <!-- Bread crumb and right sidebar toggle -->
	                <!-- ============================================================== -->
	                <div class="row page-titles">
	                    <div class="col-md-5 align-self-center">
	                        <h4 class="text-themecolor">View SS22 Place Orders</h4>
	                    </div>
	                    <div class="col-md-7 align-self-center text-right">
	                        <div class="d-flex justify-content-end align-items-center">
	                            <ol class="breadcrumb">
	                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
	                                <li class="breadcrumb-item active">View SS22 Place Orders</li>
	                            </ol>
	                        </div>
	                    </div>
	                </div>
	                <!-- ============================================================== -->
	                <!-- End Bread crumb and right sidebar toggle -->
	                <!-- ============================================================== -->
						
	                <div class="row">
                        <table class="table table-bordered" id="demo_table">
                        <thead>
                             <th style="vertical-align : middle;text-align:center;">Product image</th>
                             <th style="vertical-align : middle;text-align:center;">Item name</th>
                            <th style="vertical-align : middle;text-align:center;">Style sku</th>
                            <th  style="vertical-align : middle;text-align:center;">Delivery Date</th>
                            <th  style="vertical-align : middle;text-align:center;">Brand</th>
                            <th  style="vertical-align : middle;text-align:center;">Gender</th>
                            <th  style="vertical-align : middle;text-align:center;">Category</th>
                            <th  style="vertical-align : middle;text-align:center;">Sub-category</th>
                            <th  style="vertical-align : middle;text-align:center;">Season</th>
                            <th style="vertical-align : middle;text-align:center;">Composition</th>
                            <th style="vertical-align : middle;text-align:center;">Producto logo</th>

                            <th style="vertical-align : middle;text-align:center;">Unit Sold</th>
                            <th style="vertical-align : middle;text-align:center;">Factory Order</th>
                            <th style="vertical-align : middle;text-align:center;">Open Units</th>
                            <th style="vertical-align : middle;text-align:center;">Stock Qty</th>
                            <th style="vertical-align : middle;text-align:center;">Order Number</th>

                        </thead>
                      
					
						</table>
	                </div>

                    <!-- .right-sidebar -->
	                <div class="right-sidebar">
	                    <div class="slimscrollright">
	                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
	                        <div class="r-panel-body">
	                            <ul id="themecolors" class="m-t-20">
	                                <li><b>With Light sidebar</b></li>
	                                <li><a href="javascript:void(0)" data-skin="skin-default" class="default-theme working">1</a></li>
	                                <li><a href="javascript:void(0)" data-skin="skin-green" class="green-theme">2</a></li>
	                                <li><a href="javascript:void(0)" data-skin="skin-red" class="red-theme">3</a></li>
	                                <li><a href="javascript:void(0)" data-skin="skin-blue" class="blue-theme">4</a></li>
	                                <li><a href="javascript:void(0)" data-skin="skin-purple" class="purple-theme">5</a></li>
	                                <li><a href="javascript:void(0)" data-skin="skin-megna" class="megna-theme">6</a></li>
	                                <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
	                                <li><a href="javascript:void(0)" data-skin="skin-default-dark" class="default-dark-theme ">7</a></li>
	                                <li><a href="javascript:void(0)" data-skin="skin-green-dark" class="green-dark-theme">8</a></li>
	                                <li><a href="javascript:void(0)" data-skin="skin-red-dark" class="red-dark-theme">9</a></li>
	                                <li><a href="javascript:void(0)" data-skin="skin-blue-dark" class="blue-dark-theme">10</a></li>
	                                <li><a href="javascript:void(0)" data-skin="skin-purple-dark" class="purple-dark-theme">11</a></li>
	                                <li><a href="javascript:void(0)" data-skin="skin-megna-dark" class="megna-dark-theme ">12</a></li>
	                            </ul>
	                            
	                        </div>
	                    </div>
	                </div>
	                <!-- ============================================================== -->
	                <!-- End Right sidebar -->
	                <!-- ============================================================== -->
	            </div>
	            <!-- ============================================================== -->
	            <!-- End Container fluid  -->
	            <!-- ============================================================== -->
	        </div>
	        
	    </div>
	    
		

	    <script src="<?php echo SAGE_ECOMMERCEURL; ?>/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
	    <!-- Bootstrap tether Core JavaScript -->
	    <script src="<?php echo SAGE_ECOMMERCEURL; ?>/assets/node_modules/popper/popper.min.js"></script>
	    <script src="<?php echo SAGE_ECOMMERCEURL; ?>/assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
	    <!-- slimscrollbar scrollbar JavaScript -->
	    <script src="<?php echo SAGE_SITEURL; ?>/dist/js/perfect-scrollbar.jquery.min.js"></script>
	    <!--Wave Effects -->
	    <script src="<?php echo SAGE_SITEURL; ?>/dist/js/waves.js"></script>
	    <!--Menu sidebar -->
	    <script src="<?php echo SAGE_SITEURL; ?>/dist/js/sidebarmenu.js"></script>
	    <!--stickey kit -->
	    <script src="<?php echo SAGE_ECOMMERCEURL; ?>/assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
	    <script src="<?php echo SAGE_ECOMMERCEURL; ?>/assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
	    <!--Custom JavaScript -->
	    <script src="<?php echo SAGE_SITEURL; ?>/dist/js/custom.min.js"></script>
	    <script src="<?php echo SAGE_ECOMMERCEURL; ?>/assets/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
	    <!-- ============================================================== -->
	    <!-- This page plugins -->
	    <!-- ============================================================== -->
	    <!--Custom JavaScript -->
	    <script src="<?php echo SAGE_SITEURL; ?>/dist/js/ecom-dashboard.js"></script>
	    <script src="<?php echo SAGE_SITEURL; ?>/include/js/custom-fexpro.js"></script>
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<!-- 
		<link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
		<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css"> -->


        <!-- 
		<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
        <script src="//cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
		<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script> -->
		
		<script src="jquery.dataTables.min.js"></script>
		
		<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
		

		
		
		<?php $root_url = $_SERVER['DOCUMENT_ROOT']; ?>
        
		<script>
			$ = jQuery;
            $(document).ready(function() {
				// $('#demo_table thead th').each( function () {
				// 	var title = $(this).text();
				// 	if(title == 'Style sku'){
				// 		$(this).html( '<input type="text" id="SKUFILTER" placeholder="Search '+title+'" />' );
				// 	}
				
				// } );

				
				var demo_table = $('#demo_table').dataTable({
					dom: 'Bfrltip',
					buttons: {
						buttons: [ {
							extend: 'excelHtml5',
							text: 'Excel',
							exportOptions: {
								stripHtml: false,
								orthogonal: 'export',
							
							}
							
						}]
					},
					columns: [
						{ data: 'product_image', render: function (data, type, row) {
							// var newData = data.replace('https://shop.fexpro.com/', '');
							// var filterData = newData.replace(/<.*>/, "");
							// var root_url = '<?php echo $root_url; ?>';
							// var image_path_url = root_url+'/'+filterData;
							return type === 'export' ? data : '<img src="' + data + '" />';
						} },
						{ data: 'item_name', render: function (data, type, row) {
							var newData = data.replace(/<div class="cart-sizes-attribute"\">/, '');
							var filterData = newData.replace(/<.*>/, "");
							return type === 'export' ? filterData : data;
						} },
						{ data: 'product_sku' },
						{ data: 'delivery_date' },
						{ data: 'prod_brand' },
						{ data: 'gender' },
						{ data: 'category' },
						{ data: 'sub_category' },
						{ data: 'season' },
						{ data: 'composition' },
						{ data: 'producto_logo' },
						{ data: 'unit_sold' },
						{ data: 'factory_order', render: function (data, type, row) {
							var newData = data.replace(/<span class="for-Excel-only"\">/, '');
							var filterData = newData.replace(/<.*?>/g, "");
							return type === 'export' ? filterData : data;
						}  },
						{ data: 'open_units' },
						{ data: 'stock_qty' },
						{ data: 'order_number', render: function (data, type, row) {
							var newData = data.replace(/<span class="order1-number2"\">/, '');
							var filterData = newData.replace(/<.*?>/g, "");
							if(filterData.length > 100){
								var newDataContent = '';
							}else{
								var newDataContent = filterData;
							}
							return type === 'export' ? newDataContent : data;
						}  },
					],
					language:
					{
						search: "",
						searchPlaceholder: "Search...",
						processing: "<div class='overlay custom-loader-background'><i class='fa fa-cog fa-spin custom-loader-color'></i></div>"
					},
					processing: true,
					serverSide: true,
					info: true,
					searching: true,
					paging: true,
				
					
					lengthMenu: [[10, 300, 500, 1000, 3000 , 5000 , -1], [10, 300, 500, 1000, 3000 , 5000, "All"]],
					pagingType: "full_numbers",
               
                    ajax: {
						url: "https://shop.fexpro.com/wp-admin/admin-ajax.php?action=movie_datatables",
    					type: "GET",
					
					},
					
					
                } );

				// $('#SKUFILTER').on( 'keyup', function () {
				// 	demo_table.columns( 2 ).search( this.value ).draw();
				// } );

				
				
				$( document ).ajaxComplete(function() {
					jQuery("#myTable > tr").each(function() {
						var c = $(this).children('td').eq(2).text();
						jQuery(this).children('td').addClass(c);
					});
				});

				
				



            } );

    	</script>


	</body>

</html>
<?php 
} else {
    header('location: https://shop.fexpro.com/sagelogin/ecommerce/pages-login.php');
    exit;
}
?>