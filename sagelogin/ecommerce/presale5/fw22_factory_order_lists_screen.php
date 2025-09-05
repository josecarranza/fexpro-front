
<?php 
require_once 'include/common.php';
get_currentuserinfo();

if(is_user_logged_in()) {
delete_transient('getTableBodyData');

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

$return_array = array();
$return_array1 = array();
$return_array2 = array();
$return_array3 = array();
$return_array4 = array();
global $wpdb;

$merge3 = array();
$getallOrdersList = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}fw22_factory_order_confirmation_list", ARRAY_A );
//print_r($getZenlineOrdersList);

foreach($getallOrdersList as $abc)
{
	$vID = $abc['vid'];
	$variation = wc_get_product($abc['vid']);
	//$variable = substr($variation->get_formatted_name(), 0, strpos($variation->get_formatted_name(), " ("));
	//$variable = esc_sql($variable);
	$allData = $wpdb->get_results("SELECT `order_item_id`,`order_id`   FROM {$wpdb->prefix}wc_order_product_lookup WHERE `variation_id` = '$vID'", ARRAY_A );
	//print_r($allData);
	//$akp = array_unique($allData);
	foreach($allData as $bk)
	{
		if ( get_post_status ( $bk['order_id'] ) != 'wc-pending' ) 
		{
			continue;
		}
		else
		{
			$return_array1[$abc['vid']][] = $bk['order_item_id'];
		}
	}
}


/* echo "<pre>";
print_r($return_array1);
echo "</pre>";  */
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
/* echo "<pre>";
print_r($merge1);
echo "</pre>"; */

$getAllSupplier = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}factory_list", ARRAY_A );
$return_array3 = "<select class='factory_name'>";
$return_array3 .= "<option value=''>Select Factory</option>";
foreach($getAllSupplier as $value)
{
	$return_array3 .= "<option value='" . $value['supplier_name'] . "'>" . $value['supplier_name'] . "</option>";
}
$return_array3 .= "</select'>";


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
	    <title>Fexpro Sage - SS22 Factory Order Lists</title>
	    <!-- chartist CSS -->
	    <link href="<?php echo SAGE_SITEURL; ?>/dist/css/pages/ecommerce.css" rel="stylesheet">
	    <!-- Custom CSS -->
	    <link href="<?php echo SAGE_SITEURL; ?>/dist/css/style.min.css" rel="stylesheet">

	    <link href="<?php echo SAGE_SITEURL; ?>/include/css/custom-fexpro.css" rel="stylesheet">

	    <script src="https://cdn.anychart.com/releases/8.0.1/js/anychart-base.min.js"></script>

	     <style>
  		tbody#myTable > tr > td:first-child img {width: 100px;}
		span.error {color: #f00;font-size: 10px;display: block;}
		.order_screen_container {float: left;width: 100%;margin-bottom: 15px;background: red;padding: 15px;}
		a.submit-it {background: black;color: #fff;	padding: .375rem .75rem;font-size: 1rem;height: calc(1.5em + .75rem + 2px);	line-height: 1.5;width: auto;float: right;}	
		a.single-submit-it {background: black;color: #fff;	padding: .375rem .75rem;font-size: 1rem;height: calc(1.5em + .75rem + 2px);	line-height: 1.5;}
		.order_screen_container input {	float: left;width: auto;}
		tbody#myTable tr td .red {	border-color: red !important;}
		
		.cart-sizes-attribute {	min-width: 350px;	width: 100%;	margin-top: 20px;   }
		.cart-sizes-attribute .size-guide h5 {   border: solid 1px #000;padding: 20px 9px!important;}
		.size-guide h5{   color: #000;  font-size: 13px;   font-weight: 700;   line-height: 20px;  margin-bottom: 0; }
		.cart-sizes-attribute .size-guide {   display: -webkit-box;   display: -ms-flexbox;   display: flex;}
		.inner-size {display: block;width: 100%;-webkit-box-align: center;	-ms-flex-align: center;	align-items: center;	text-align: center;	}
		.cart-sizes-attribute .size-guide .inner-size {	border: solid 1px #000;	border-right: 0;	border-left: 0;}
		.inner-size span:first-child {	font-weight: bold;	background: #008188;	color: #fff;	}
		.inner-size span {	display: block;	width: 100%;	border-bottom: solid 1px #000;	border-right: 1px solid #000;	color: #000;padding: 5px 10px;	}
		span#exportexcel {	background: #000;	color: #fff;cursor: pointer;font-size: 24px;text-align: center;	font-weight: bold;	margin-bottom: 15px;padding: 5px 15px;	display: inline-block;margin-left: 5px;border-radius: 5px;	transition: all 0.2s ease;	}
		span#exportexcel:hover {background: #b41520;}
		span#stop-refresh {	display: none;	color: #f00;font-size: 18px;margin-left: 5px;	margin-bottom: 15px;width: 100%;}
		.for-Excel-only, .order1-number2, .factory_order_number, .costprice{display: none;}	
		input.factory_order_number {margin-top: 10px;}
		.add-new {	cursor: pointer;background: #000;	display: inline-block;	color: #fff;	padding: 5px;	border-radius: 5px;	margin: 10px 0;	}
		.order1-number2, .only1 {   text-align: center;  font-size: 17px;  color: #f00;  font-weight: bold;}
		.show .order1-number2{display: block;}
		.adding-data:before {   content: "";  background: rgba(0,0,0,0.5);  z-index: 2;  position: absolute;   width: 100%;  height: 100%;}
		table caption{caption-side: top;}
		.TF.sticky tr.fltrow th {   top: -1px !important;   background: #af0f2c; z-index: 1;}
		.TF.sticky th {   top: 34px !important; z-index: 1;}

		body table.TF tr.fltrow th {   border-bottom: 1px solid #000;   border-top: 1px solid #000;   border-left: 1px solid #000;   border-right: 1px solid #000;    padding: 0;   color: #fff;}
		a.submit-in-one {   position: fixed;  bottom: 0;  background: #af0f2c;  color: #fff; padding: 5px;  right: 0;  font-size: 18px; cursor: pointer;}
		input.factory_order, input.cost-price {  max-width: 90px;}
		.onumber{display: block;  min-width: 210px;  width: 100%;  text-align: center;	font-weight: bold;}
		input.delivery-date {   max-width: 150px;}
		a.single-delete-it {  background: #af0f2c;   color: #fff;   padding: .375rem .75rem;   font-size: 1rem;   height: calc(1.5em + .75rem + 2px);   line-height: 1.5;}
		select.rspg + span {   display: none;}
		tr.red , td.red {background: #ff00002e;}
		tr.success , td.success {background: #00800021;}
		table#demo thead tr.fltrow > td:first-child input, table#demo thead tr.fltrow > th:first-child input, .deliverydate-value, table#demo thead tr.fltrow > th:nth-child(0) input, .cartoon_dimensions-value, .cbms_x_ctn-value, .weight_x_ctn-value, .fabric-value, .comments-add, .pdf-add {	display: none !important;}
		input[type="text"][name="textContent"]:disabled {
    border: none;
    font-weight: 900;
    color: #000;
    text-align: center;
}

span.EditOrderNumber, span.SaveOrderNumber {
    background: #000;
    color: #fff;
    padding: 5px;
    border-radius: 5px;
    cursor: pointer;
    display: inline-block;
    position: relative;
    top: 23px;
    left: -29px;
}

span.no-v {
    display: block;
    width: 100%;
    font-size: 16px;
    color: #000;
    font-weight: 500;
}
span.no-v strong {
    color: #f00;
    text-decoration: underline;
    font-weight: 900;
}


  	</style>

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
	                        <h4 class="text-themecolor">View FW22 Factory Order Lists</h4>
	                    </div>
	                    <div class="col-md-7 align-self-center text-right">
	                        <div class="d-flex justify-content-end align-items-center">
	                            <ol class="breadcrumb">
	                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
	                                <li class="breadcrumb-item active">View FW22 Factory Order Lists</li>
	                            </ol>
	                        </div>
	                    </div>
	                </div>
	                <!-- ============================================================== -->
	                <!-- End Bread crumb and right sidebar toggle -->
	                <!-- ============================================================== -->
						
	                <div class="row">

	                	<span id="exportexcel" onclick="fnExcelReport();" style="display:none">Export to XLSX</span>
	                	<span id="exportexcel1" onclick="fnExcelReport1();">Export All to XLSX</span>
						<span id="stop-refresh">Exporting is inprogress. Please don't refresh the page.</span>
						<div class="exporting-it">

						<table class="table table-bordered" id="demo">
						   <thead>
						        <tr>
						          <th></th>
								  <th></th>
								  <th style="vertical-align : middle;text-align:center;">Order Number</th>
						          <th style="vertical-align : middle;text-align:center;">Product image</th>
						          <th style="vertical-align : middle;text-align:center;">Item name</th>
						          <th style="vertical-align : middle;text-align:center;">Style sku</th>
								  <th  style="vertical-align : middle;text-align:center;">Gender</th>
								  <th  style="vertical-align : middle;text-align:center;">Category</th>
						          <th  style="vertical-align : middle;text-align:center;">Sub-category</th>
						          <th style="vertical-align : middle;text-align:center;">Composition</th>
						          <th style="vertical-align : middle;text-align:center;">Producto logo</th>
								  <?php 

								  if($merge3){
								  	foreach ($merge3 as $akkk3 => $akkkv3) 
									  {
										echo '<th style="vertical-align : middle;text-align:center; display: none;">'. $akkk3 .'</th>';
									  }	
								  }else{}
								  
								  ?>
						          <th style="vertical-align : middle;text-align:center;">Unit Sold</th>
						          <th style="vertical-align : middle;text-align:center;">Factory Order</th>
						          <th style="vertical-align : middle;text-align:center;">Open Units</th>
						          <th style="vertical-align : middle;text-align:center;">Factory Name</th>
						         <?php /* <th style="vertical-align : middle;text-align:center;">Carton Dimensions</th>
						          <th style="vertical-align : middle;text-align:center;">CBMS X CTN</th>
						          <th style="vertical-align : middle;text-align:center;">WEIGHT X CTN</th>
						          <th style="vertical-align : middle;text-align:center;">Fabric</th> */ ?>
						          <th style="vertical-align : middle;text-align:center;">Delivery Date</th>
						          <th style="vertical-align : middle;text-align:center;">Cost price</th>          
						          <th style="vertical-align : middle;text-align:center;">Comments</th>          
						          <th style="vertical-align : middle;text-align:center;">PDF</th>          
								  
						        </tr>
						      </thead>
							<tbody id="myTable">
								<?php 
								$ak = 0;
								$merge67 = array();
								foreach($getallOrdersList as $key => $value)
								{
									foreach ($merge3 as $akkk3 => $akkkv3) 
									{
										if(!empty($merge1[$value['vid']]))
										{
											foreach($merge1[$value['vid']] as $ko => $ko1)
											{
												$q1  = 0;
												
												if(	$akkk3 == $ko)
												{
													foreach($ko1 as $ko2 => $ko22)
													{
														$q1 += $ko22;
													}
													$merge67[$value['vid']][$akkk3][] = $q1;
												}
												else					
												{
													$merge67[$value['vid']][$akkk3][] = '';
												}
											}	
										}
									}
								}

								$tableBody = array();
								$kl = array();
								foreach($getallOrdersList as $key => $value)
								{
									if(!empty(wc_get_product( $value['vid'] )))
									{
										$_product =  wc_get_product( $value['vid'] );

										//echo $_product->get_sku()."<BR>";
										
										$productParentId = wp_get_post_parent_id($value['vid']);

										if($value['deliverydate'] == '0000-00-00'){
											$pa_delivery_date = wc_get_product_terms( $_product->get_parent_id(), 'pa_delivery-date' );
											$value['deliverydate'] = date("Y-m-d", strtotime($pa_delivery_date[0]->name));	
										}
									

									


										//0000-00-00

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
										//$main_product = wc_get_product( $_product->get_parent_id() );

									
									
										$image_id			= $_product->get_image_id();
										$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
										$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
										$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );
										
										$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
										$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));
										
										$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
										}
										else
										{	
											$_parent_product =  wc_get_product($_product->get_parent_id());
											if(!in_array($_parent_product->get_sku(), $kl))
											{
												echo "<span class='no-v'>This Style SKU is not avialable in Presale3 anymore : <strong>". $_parent_product->get_sku() . "</strong>. Please remove from Factory Order List. <strong>(Marked with Red Color Row)</strong></span>";
												array_push($kl, $_parent_product->get_sku());
											}
										}
										
										($merge[$value['vid']][0] >= $value['forderunits']) ? $alk = $merge[$value['vid']][0] - $value['forderunits'] : $alk = "0";
										
										$array_logo = array();
										if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
										if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
										if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
										if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
										
										$logoApplicationString = implode(', ', $array_logo);
										
										$row3 = "<div class='cart-sizes-attribute'>";
										$row3 .= '<div class="size-guide"><h5>Sizes</h5>';
										if(!empty($merge1[$value['vid']]))
										{
											$k = '';
											foreach ($merge1[$value['vid']] as $akkk => $akkkv) {
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
										
										
										echo "<tr class='" . $k . "'>";
											echo "<td class='hide-in-excel' style='vertical-align : middle;text-align:center;'><a href='Javascript:void(0);' class='single-submit-it'><i class='fas fa-save'></i></a></td>";
											echo "<td class='hide-in-excel' style='vertical-align : middle;text-align:center;'><a href='Javascript:void(0);' class='single-delete-it'><i class='fas fa-trash-alt'></i></a></td>";
											echo "<td class='".$_product->get_sku()."' style='vertical-align : middle;text-align:center;'><span class='onumber'> <input type='text' name='textContent'  value='" . $value['forderid'] . "' data-tabVid='".$value['vid']."' disabled /> </span> <span class='EditOrderNumber'>Edit Order Number</span></td>";
											echo "<td class='".$_product->get_sku()."'><img src='" . $thumbnail_src[0] . "'/></td>";
											echo "<td class='".$_product->get_sku()."'>" . $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' );
												echo $row3;
											echo "</td>";
											echo "<td class='".$_product->get_sku()."'>" . $_product->get_sku() . "</td>";
											echo "<td class='".$_product->get_sku()."'>" . implode(", ", $css_slugGender) . "</td>";
											echo "<td class='".$_product->get_sku()."'>" . implode(", ", $css_slugCategory) . "</td>";
											echo "<td class='".$_product->get_sku()."'>";
											if(!empty($css_slugSubCategory))
											{
												echo implode(", ", $css_slugSubCategory);

											}
											echo "</td>";
											echo "<td class='".$_product->get_sku()."'>" . $fabricCompositionString . "</td>";
											echo "<td class='".$_product->get_sku()."'>" . $logoApplicationString . "</td>";



											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => '' ));
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => '' ));
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['forderid'] ));
											$imageUrlThumb = str_replace("https://shop.fexpro.com", "",$thumbnail_src[0]);
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $imageUrlThumb ));
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' )) );
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $_product->get_sku()));
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugGender) ));
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugCategory) ));
											if(!empty($css_slugSubCategory))
											{
												array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugSubCategory) ));
											}
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $fabricCompositionString ));
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $logoApplicationString ));




											if(!empty($merge67[$value['vid']]))
											{
												foreach($merge67[$value['vid']] as $qw => $qr)
												{
													$fk = 0;
													foreach($qr as $vl)
													{
														if($vl == '')
														{
															continue;
														}
														else
														{
															$fk = $vl;
														}
													}
													if($fk == 0)
													{
														echo "<td class='".$_product->get_sku()."' style='display: none;'></td>";	
														array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => ''  ));					
													}
													else
													{
														echo "<td class='".$_product->get_sku()."' style='display: none;'>" . $fk . "</td>";
														array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $fk  ));						
													}
												}
											}
											else
											{
												foreach ($merge3 as $akkk3 => $akkkv3) 
												{
													echo "<td class='".$_product->get_sku()."' style='display: none;'></td>";
													array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => ''  ));	
												}
											}		
											echo "<td class='".$_product->get_sku()."'>" . $merge[$value['vid']][0] . "</td>";
											echo "<td class='".$_product->get_sku()."'><input type='number' name='factory_order' class='factory_order' data-variation_id ='" . $value['vid'] . "' value='" . $value['forderunits'] ."'/><span class='for-Excel-only'>" . $value['forderunits'] . "</span></td>";
											echo "<td class='".$_product->get_sku()."'>" . $alk ." </td>";
											echo "<td class='".$_product->get_sku()."'>" . $return_array3 . " <input type='hidden' /><span class='order1-number2'>" . $value['factoryname'] . "</span></td>";
											/*echo "<td class='".$_product->get_sku()."'><input type='text' class='cartoon_dimensions' value='" . $value['cartoon_dimensions'] . "'/><span class='cartoon_dimensions-value'>". $value['cartoon_dimensions'] ."</span></td>";
											echo "<td class='".$_product->get_sku()."'><input type='text' class='cbms_x_ctn' value='" . $value['cbms_x_ctn'] . "'/><span class='cbms_x_ctn-value'>". $value['cbms_x_ctn'] ."</span></td>";
											echo "<td class='".$_product->get_sku()."'><input type='text' class='weight_x_ctn' value='" . $value['weight_x_ctn'] . "'/><span class='weight_x_ctn-value'>". $value['weight_x_ctn'] ."</span></td>";
											echo "<td class='".$_product->get_sku()."'><input type='text' class='fabric' value='" . $value['fabric'] . "'/><span class='fabric-value'>". $value['fabric'] ."</span></td>";				*/
											echo "<td class='".$_product->get_sku()."'><input type='date' class='delivery-date' value='" . $value['deliverydate'] . "'/><span class='deliverydate-value'>". $value['deliverydate'] ."</span></td>";
											echo "<td class='".$_product->get_sku()."'><input type='number' class='cost-price' placeholder='$' value='" . $value['costprice'] . "'/><span class='costprice'>" . $value['costprice'] . "</span></td>";				
											echo "<td class='".$_product->get_sku()."'><textarea class='comments' placeholder='Add comments' l'>" . $value['comments'] . "</textarea><span class='comments-add'>" . $value['comments'] . "</span></td>";	
											echo "<td class='".$_product->get_sku()."'><a href='$pdf' $target>Download</a><span class='pdf-add'>$pdf1</span></td>";	

											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $merge[$value['vid']][0]  ));		
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['forderunits']  ));	
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $alk  ));	
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['factoryname']  ));	
											/*array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['cartoon_dimensions']  ));	
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['cbms_x_ctn']  ));	
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['weight_x_ctn']  ));	
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['fabric']  ));	*/
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['deliverydate']  ));	
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['costprice']  ));	
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['comments']  ));	
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $pdf1  ));	

										echo "</tr>";
									}
									
								?>
							</tbody>
						</table>
	                	
	                </div>


	                <?php 

					delete_transient('getTableBodyData');

					set_transient('getTableBodyData', $tableBody, 21600);

					?>



	                
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

	    <script src="<?php echo SAGE_SITEURL; ?>/dist/tablefilter/tablefilter.js"></script>
		<script src="<?php echo SAGE_SITEURL; ?>/test-filters-visibility-factory.js"></script>


		<script>
	$(document).ready(function(){  

	$( "table thead tr:not(.fltrow) th" ).each(function( index ) {
	  if($(this).is(":hidden"))
	  {
	  console.log( index + ": ");
	  //jQuery(".fltrow td [ct='"+index+"']").parent().hide();
	  jQuery(".fltrow th [ct='"+index+"']").parent().hide();
	  }
	});



	jQuery('.EditOrderNumber').click(function(){
		jQuery(this).parent().find('span.onumber input').removeAttr('disabled');
		
		if (jQuery(this).hasClass("SaveOrderNumber")) {
			var form_data = new FormData();		
			var c = jQuery(this).parent();
			var d = jQuery(this);

			var tabVid = jQuery(this).parent().find('span.onumber input').attr('data-tabVid');
			var orderNumberText = jQuery(this).parent().find('span.onumber input').val();

			form_data.append('tabVid', tabVid);
			form_data.append('orderNumberText', orderNumberText);
			form_data.append('action', 'save_fw22_factory_data_order_number');
			
			$.ajax({
				type: "POST",
				url: "https://shop.fexpro.com/wp-admin/admin-ajax.php",
				contentType: false,
				processData: false,
				data: form_data,
				beforeSend: function() {
					c.addClass('red');
				},
				success:function(data) {
					if(data=='edited'){
						c.find('span.onumber input').attr('disabled','disabled');
						d.html('Edit Order Number');
						d.removeClass('SaveOrderNumber');
						c.removeClass('red');
						c.addClass('success');
						setTimeout(function() {
							c.removeClass('success');
						},1000);
					}
					
					console.log(data);
				},
				error: function(errorThrown){
					console.log(errorThrown);
					console.log('No update');
				}
			});
		}
		else
		{
		jQuery(this).addClass('SaveOrderNumber');
		jQuery(this).html('Save Order Number');
		}
	});

	

	

	jQuery(".order1-number2").each(function() {
		var getThis = jQuery(this).text();
		jQuery(this).parent().find(".factory_name").children("option[value='"+getThis+"']").attr("selected","selected");
	});
	
	jQuery(".factory_order").on('keyup', function() {		
		var getLiveValue = $(this).val();
		var getUnitSoldValue = parseInt($(this).parent().prev().text());
		if(getUnitSoldValue < getLiveValue)
		{
			//$(this).parent().append('<span class="error">Value must be less than Unit Sold Total</span>');
			$(this).parent().next().text("0");
			//$(this).parent().parent().children().last().text("0");
		}
		else
		{
			var getDifference = getUnitSoldValue - getLiveValue;
			$(this).parent().next().text(getDifference);
			//$(this).parent().parent().children().last().text(getDifference);
		}
		$(this).parent().find('span').text(getLiveValue);
	});
	
	jQuery(".factory_name").change(function() {
		var getLiveValue = $(this).val();
		$(this).parent().find('.order1-number2').text(getLiveValue);
	});
	
	jQuery(".delivery-date").change(function() {
		var getLiveValueDate = $(this).val();
		$(this).parent().find('.deliverydate-value').text(getLiveValueDate);
	});
	
	jQuery(".cost-price").on('keyup', function() {
		var getLiveValueCostPrice = $(this).val();
		$(this).parent().find('.costprice').text(getLiveValueCostPrice);
	});
	
	jQuery(".cartoon_dimensions").on('keyup', function() {
		var getLiveValueCostPrice = $(this).val();
		$(this).parent().find('.cartoon_dimensions-value').text(getLiveValueCostPrice);
	});
	
	jQuery(".cbms_x_ctn").on('keyup', function() {
		var getLiveValueCostPrice = $(this).val();
		$(this).parent().find('.cbms_x_ctn-value').text(getLiveValueCostPrice);
	});
	
	jQuery(".weight_x_ctn").on('keyup', function() {
		var getLiveValueCostPrice = $(this).val();
		$(this).parent().find('.weight_x_ctn-value').text(getLiveValueCostPrice);
	});
	
	jQuery(".fabric").on('keyup', function() {
		var getLiveValueCostPrice = $(this).val();
		$(this).parent().find('.fabric-value').text(getLiveValueCostPrice);
	});
	
	jQuery(".comments").on('keyup', function() {
		var getLiveValueCostPrice = $(this).val();
		$(this).parent().find('.comments-add').text(getLiveValueCostPrice);
	});
	
	jQuery(".single-submit-it").on('click', function() {
		
		var form_data = new FormData();		
		var getCurrentRowVariationID = $(this).parent().parent().find("td .factory_order").data('variation_id');
		var getCurrentRowFactoryUnits = $(this).parent().parent().find("td .factory_order").val();
		var getCurrentRowFactoryNameSelect = $(this).parent().parent().find("td select.factory_name:visible option:selected").val();
		/*var getCurrentRowFactoryNamecartoon_dimensions = $(this).parent().parent().find("td .cartoon_dimensions").val();
		var getCurrentRowFactoryNamecbms_x_ctn = $(this).parent().parent().find("td .cbms_x_ctn").val();
		var getCurrentRowFactoryNameweight_x_ctn = $(this).parent().parent().find("td .weight_x_ctn").val();
		var getCurrentRowFactoryNamefabric = $(this).parent().parent().find("td .fabric").val();*/
		var getCurrentRowFactoryOrderDate = $(this).parent().parent().find("td .delivery-date:visible").val();
		var getCurrentRowFactoryOrderCost = $(this).parent().parent().find("td .cost-price:visible").val();
		var getCurrentRowFactoryOrdercomments = $(this).parent().parent().find("td .comments").val();
				
		var getCurrentRowClass = $(this).parent().parent();
		var getCurrentRowClass1 = $(this);
	/*	
		console.log(getCurrentRowVariationID);
		console.log(getCurrentRowFactoryUnits);
		console.log(getCurrentRowFactoryNameSelect);
		console.log(getCurrentRowFactoryOrderDate);
		console.log(getCurrentRowFactoryOrderCost);
		
	*/	
	
			
			form_data.append('getCurrentRowVariationID', getCurrentRowVariationID);
			form_data.append('getCurrentRowFactoryUnits', getCurrentRowFactoryUnits);
			form_data.append('getCurrentRowFactoryNameSelect', getCurrentRowFactoryNameSelect);
			/*form_data.append('getCurrentRowFactoryNamecartoon_dimensions', getCurrentRowFactoryNamecartoon_dimensions);
			form_data.append('getCurrentRowFactoryNamecbms_x_ctn', getCurrentRowFactoryNamecbms_x_ctn);
			form_data.append('getCurrentRowFactoryNameweight_x_ctn', getCurrentRowFactoryNameweight_x_ctn);
			form_data.append('getCurrentRowFactoryNamefabric', getCurrentRowFactoryNamefabric);*/
			form_data.append('getCurrentRowFactoryOrderDate', getCurrentRowFactoryOrderDate);
			form_data.append('getCurrentRowFactoryOrderCost', getCurrentRowFactoryOrderCost);
			form_data.append('getCurrentRowFactoryOrdercomments', getCurrentRowFactoryOrdercomments);
			
			form_data.append('action', 'edit_fw22_factory_data');
			
			$.ajax({
				type: "POST",
				url: "https://shop.fexpro.com/wp-admin/admin-ajax.php",
				contentType: false,
				processData: false,
				data: form_data,
				beforeSend: function() {
					getCurrentRowClass.addClass('red');

				},
				success:function(data) {
					if(data=='edited'){
						getCurrentRowClass.removeClass('red');
						getCurrentRowClass.addClass('success');
						
					}
					setTimeout(function() {
							getCurrentRowClass.removeClass('success');
					},1000);
					console.log(data);
				},
				error: function(errorThrown){
					console.log(errorThrown);
					console.log('No update');
				}
			});
		/* } */
	});
	jQuery(".single-delete-it").on('click', function() {
		var form_data = new FormData();		
		var getCurrentRowVariationStyleName = $(this).parent().parent().find("td:eq(3)").text();
		var getCurrentRowVariationID = $(this).parent().parent().find("td .factory_order").data('variation_id');
		var getCurrentRowClass = $(this).parent().parent();
		
		form_data.append('getCurrentRowVariationID', getCurrentRowVariationID);	
		form_data.append('action', 'delete_fw22_single_factory_data');	
		
		var txt;
		var r = confirm("Are you sure you want to delete this style SKU: " + getCurrentRowVariationStyleName);
		if (r == true) {
		console.log("pressed OK!");
		
			$.ajax({
				type: "POST",
				url: "https://shop.fexpro.com/wp-admin/admin-ajax.php",
				contentType: false,
				processData: false,
				data: form_data,
				beforeSend: function() {

				},
				success:function(data) {
					console.log(data);
					getCurrentRowClass.find("td .factory_order").parent().parent().remove();
				},
				error: function(errorThrown){
					console.log(errorThrown);
					console.log('No update');
				}
			});
		} else {
		console.log("pressed Cancel!");
		}
	});
});

function fnExcelReport()
{
    var SITEURL = "<?php echo site_url(); ?>/wp-content/themes/porto-child/";
	var form_data = new FormData();   
	var myArray = [];
	var myArray1 = [];
	var myArrayImage2 = [];
	var data = {};
	var tab = document.getElementById('myTable');
	var i=0, k=0;
	
	jQuery( 'table#demo thead > tr:nth-child(2) th:not(:first-child):not(:nth-child(2))' ).each(function() {
			myArray.push(jQuery(this).text());		
	});
	console.log(myArray);
	form_data.append('getHeaderArray', JSON.stringify(myArray));
	console.log(tab.rows);
	for(i = 0 ; i < tab.rows.length ; i++) 
    {     
		if(tab.rows[i].getAttribute("style") == 'display: none;')
		{	
			continue;
		}
		else
		{
			// console.log(tab.rows[j]);
			
			//console.log(tab.rows[j].innerHTML);
			for(k = 0 ; k < tab.rows[i].cells.length ; k++) 
			{
				if(tab.rows[i].cells[k].innerHTML.indexOf("pdf-add") != -1)
				{
					var abc689 = tab.rows[i].cells[k].innerHTML.split('<span class="pdf-add">');
					//console.log(abc689);
					var res689 = abc689[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  ''
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("uploads") != -1)
				{
					var abc = tab.rows[i].cells[k].innerHTML.split("https://shop.fexpro.com");
					var res = abc[1].replace('">', "");
					//myArray1.push(res);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("single-submit-it") != -1)
				{
					continue;
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("single-delete-it") != -1)
				{
					continue;
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("for-Excel-only") != -1)
				{
					var abc4 = tab.rows[i].cells[k].innerHTML.split('<span class="for-Excel-only">');
					var res4 = abc4[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res4
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("order1-number2") != -1)
				{
					var abc5 = tab.rows[i].cells[k].innerHTML.split('<span class="order1-number2">');
					var res5 = abc5[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res5
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("deliverydate-value") != -1)
				{
					var abc2 = tab.rows[i].cells[k].innerHTML.split('<span class="deliverydate-value">');
					var res2 = abc2[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res2
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("onumber") != -1)
				{
					var abc7 = tab.rows[i].cells[k].innerHTML.split('<span class="onumber">');
					var res7 = abc7[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res7
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("costprice") != -1)
				{
					var abc6 = tab.rows[i].cells[k].innerHTML.split('<span class="costprice">');
					var res6 = abc6[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res6
					});
				}
				/*else if(tab.rows[i].cells[k].innerHTML.indexOf("cartoon_dimensions-value") != -1)
				{
					var abc66 = tab.rows[i].cells[k].innerHTML.split('<span class="cartoon_dimensions-value">');
					var res66 = abc66[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res66
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("cbms_x_ctn-value") != -1)
				{
					var abc67 = tab.rows[i].cells[k].innerHTML.split('<span class="cbms_x_ctn-value">');
					var res67 = abc67[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res67
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("weight_x_ctn-value") != -1)
				{
					var abc68 = tab.rows[i].cells[k].innerHTML.split('<span class="weight_x_ctn-value">');
					var res68 = abc68[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res68
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("fabric-value") != -1)
				{
					var abc69 = tab.rows[i].cells[k].innerHTML.split('<span class="fabric-value">');
					var res69 = abc69[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res69
					});
				}*/
				else if(tab.rows[i].cells[k].innerHTML.indexOf("comments-add") != -1)
				{
					var abc60 = tab.rows[i].cells[k].innerHTML.split('<span class="comments-add">');
					var res60 = abc60[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res60
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("cart-sizes-attribute") != -1)
				{
					var abc3 = tab.rows[i].cells[k].innerHTML.split('<div class="cart-sizes-attribute"');
					var res3 = abc3[0].replace('<div class="cart-sizes-attribute"', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res3
					});
				}
				else
				{
					//myArray1.push(tab.rows[i].cells[k].innerHTML);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  tab.rows[i].cells[k].innerHTML
					});
				}
			}
			//tab.rows[j].innerHTML;
		}
    } 

	
	
	//console.log(tab);
	console.log(myArray1);
	form_data.append('getBodyArray', JSON.stringify(myArray1));
	form_data.append('action', 'export_cart_entries1');
	jQuery.ajax({
		type: "POST",
		url: "https://shop.fexpro.com/wp-admin/admin-ajax.php",
		contentType: false,
		processData: false,
		data: form_data,
		beforeSend: function() {
			jQuery('#exportexcel').text('Creating XLSX File');
			jQuery('#stop-refresh').show();
		},
		success:function(msg) {
			console.log(msg);	
			jQuery('#exportexcel').text('Data Exported');
			setTimeout(function() {
				jQuery('#exportexcel').text('Export Cart in XLSX');
			},500);
			jQuery('#stop-refresh').hide();
			var data = JSON.parse(msg);
			window.open(SITEURL+"orders/"+data.filename, '_blank');
		},
		error: function(errorThrown){
			console.log(errorThrown);
			console.log('No update');
		}
	});
}


	function fnExcelReport1()
			{	

				 var SITEURL = "<?php echo site_url(); ?>/wp-content/themes/porto-child/";
				var form_data = new FormData();   
				var myArray = [];
				var myArray1 = [];
				var myArrayImage2 = [];
				var data = {};
				var tab = document.getElementById('myTable');
				var i=0, k=0;
				
				jQuery( 'table#demo thead > tr:nth-child(2) th:not(:last-child)' ).each(function() {
						myArray.push(jQuery(this).text());		
				});
				console.log(myArray);
				form_data.append('getHeaderArray', JSON.stringify(myArray));
				form_data.append('action', 'export_cart_ss22_entries_all_data');
				jQuery.ajax({
					type: "POST",
					url: "https://shop.fexpro.com/wp-admin/admin-ajax.php",
					contentType: false,
					processData: false,
					data: form_data,
					beforeSend: function() {
						jQuery('#exportexcel1').text('Creating XLSX File');
						jQuery('#stop-refresh').show();
					},
					success:function(msg) {
						console.log(msg);	
						jQuery('#exportexcel1').text('Data Exported');
						setTimeout(function() {
							jQuery('#exportexcel1').text('Export All to XLSX');
						},500);
						jQuery('#stop-refresh').hide();
						var data = JSON.parse(msg);
						window.open(SITEURL+"orders/"+data.filename, '_blank');
					},
					error: function(errorThrown){
						console.log(errorThrown);
						console.log('No update');
					}
				});


			}
</script>

</body>

</html>

<?php 
} else {
    header('location: https://shop.fexpro.com/sagelogin/ecommerce/pages-login.php');
    exit;
}
?>