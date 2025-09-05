<?php 
require_once 'include/common.php';
get_currentuserinfo();
global $wpdb;
if(is_user_logged_in()) {

	if($_GET['purchased'] == 'with-users')
	{
		$t = 'Presale4 --- Users Purchased list';
		$p = 'Users Purchased list';
	}
	elseif($_GET['purchased'] == 'without-users')
	{
		$t = 'Presale4 --- Without Users Purchased list';
		$p = 'Without Users Purchased list';
	}
	elseif($_GET['cat_purchased'] == 'mens-basics')
	{
		$t = 'Spring Summer 22 --- Mens Basics';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'womens-basics')
	{
		$t = 'Spring Summer 22 --- Womens Basics';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'boys-basics')
	{
		$t = 'Spring Summer 22 --- Boys Basics';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'sports-mens-apparel')
	{
		$t = 'Spring Summer 22 --- Sports Mens Apparel';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'sports-womens-apparel')
	{
		$t = 'Spring Summer 22 --- Sports Womens Apparel';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'sports-boys-apparel')
	{
		$t = 'Spring Summer 22 --- Sports Boys Apparel';
		$p = 'Mens Basics';
	}
	
	elseif($_GET['cat_purchased'] == 'sports-unisex-apparel')
	{
		$t = 'Spring Summer 22 --- Sports Unisex Apparel';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'pop-mens-apparel')
	{
		$t = 'Spring Summer 22 --- Pop Mens Apparel';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'pop-womens-apparel')
	{
		$t = 'Spring Summer 22 --- Pop Womens Apparel';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'underwear-and-boxers')
	{
		$t = 'Spring Summer 22 --- Underwear and Boxers';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'socks-summer-spring-22')
	{
		$t = 'Spring Summer 22 --- Socks';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'mens-pijamas')
	{
		$t = 'Spring Summer 22 --- Mens Pijamas';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'pijamas-underwear-sleep-womens-summer-spring-22')
	{
		$t = 'Spring Summer 22 --- Womens Pijamas';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'footwear-mens-summer-spring-22')
	{
		$t = 'Spring Summer 22 --- Footwear Mens';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'footwear-boys-summer-spring-22')
	{
		$t = 'Spring Summer 22 --- Footwear Boys';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'headwear')
	{
		$t = 'Spring Summer 22 --- Headwear';
		$p = 'Mens Basics';
	}
	


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
	    <title>Fexpro Sage - Presale4 Factory Order Lists</title>
	    <!-- chartist CSS -->
	    <link href="<?php echo SAGE_SITEURL; ?>/dist/css/pages/ecommerce.css" rel="stylesheet">
	    <!-- Custom CSS -->
	    <link href="<?php echo SAGE_SITEURL; ?>/dist/css/style.min.css" rel="stylesheet">

	    <link href="<?php echo SAGE_SITEURL; ?>/include/css/custom-fexpro.css" rel="stylesheet">

	    <script src="https://cdn.anychart.com/releases/8.0.1/js/anychart-base.min.js"></script>

	   


  	<?php if($_GET['purchased'] == 'with-users') { ?>

  		 <script src="../../include/js/custom-fexpro.js"></script>

	  	<style>
	    input, textarea{width: 100%}
	    table#demo tr > td:first-child {
	        width: 20%;
	    }
	    [role="alert"]{display: none;}
	    .submit{margin-bottom: 15px;}
	    .red{border-color: #f00;}
	    </style>
	<?php }else {?>
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
			tr.red , td.red {background: #ff00002e !important;}
			tr.success , td.success {background: #00800021;}
			table#demo thead tr.fltrow > td:first-child input, table#demo thead tr.fltrow > th:first-child input, .deliverydate-value, table#demo thead tr.fltrow > th:nth-child(0) input, .cartoon_dimensions-value, .cbms_x_ctn-value, .weight_x_ctn-value, .fabric-value, .comments-add, .pdf-add {	display: none !important;}
			input[type="text"][name="textContent"]:disabled { border: none; font-weight: 900; color: #000; text-align: center; }
	        span.EditOrderNumber, span.SaveOrderNumber {background: #000;color: #fff;padding: 5px;border-radius: 5px;cursor: pointer;display: inline-block;position: relative;top: 23px;left: 40px;}
	        span.no-v {display: block;width: 100%;font-size: 16px;color: #000;font-weight: 500;}
			span.no-v strong {color: #f00;text-decoration: underline;font-weight: 900;}
	        /* div#demo_table_wrapper.dt-bootstrap {display: flex;}
			div#demo_table_wrapper.dt-bootstrap > .row:last-child {order: -1;width: 100%;}
			div#demo_table_wrapper.dt-bootstrap > .row:last-child > div {flex: 0 0 25%;max-width: 25%;}
			div#demo_table_wrapper table {display: block;overflow-x: scroll;margin: 30px;width: 100% !important;} */
	        div#demo_table_filter, select.form-control.input-sm {background: #fff;float: left;margin-left: 26px; margin-bottom: 10px;}
	        div#demo_table_paginate {float: left;margin-left: 100px;margin-bottom: 30px;}
	        div#demo_table_info {margin-left: 25px;}
	        div#demo_table_length {margin-left: 30px;}
	        .loader__label {float: left;margin-left: 50%;-webkit-transform: translateX(-50%);-moz-transform: translateX(-50%);   -ms-transform: translateX(-50%);-o-transform: translateX(-50%);transform: translateX(-50%);
	        margin: .5em 0 0 50%;font-size: .875em;letter-spacing: .1em;line-height: 1.5em;color: #1976d2;white-space: nowrap;animation:none !important;font-size:20px;}
	        h2.dispalyTitle {
	    width: 100%;
	    
	    color: red;
	}
	  	</style>
	<?php } ?>
<script>
			window.onload = function () {    

  				jQuery('.user-profile .dropdown a.dropdown-toggle').click(function(e){
                	jQuery('.user-profile .dropdown').find('.dropdown-menu').toggle();
            	});

			}


		</script>
        

	    </head>
	<body class="skin-default fixed-layout">
	    <!-- ============================================================== -->
	    <!-- Preloader - style you can find in spinners.css -->
	    <!-- ============================================================== -->
	    <div class="preloader">
	        <div class="loader">
	            <div class="loader__figure"></div>
	            <p class="loader__label">&nbsp;</p>
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
	                    <a class="navbar-brand" href="<?php echo SAGE_SITEURL; ?>/alpha/">
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
						<h4 class="text-themecolor"><?php echo $t; ?></h4>
	                    </div>
	                    <div class="col-md-7 align-self-center text-right">
	                        <div class="d-flex justify-content-end align-items-center">
	                            <ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                <li class="breadcrumb-item active"><?php echo $p; ?></li>
	                            </ol>
	                        </div>
	                    </div>
	                </div>
	                <!-- ============================================================== -->
	                <!-- End Bread crumb and right sidebar toggle -->
	                <!-- ============================================================== -->
						
	                <div class="row">
	                	<?php if($_GET['purchased'] == 'with-users') { delete_transient('getTableBodyData');?>

	                		<?php 

								$return_array = array();
								$return_array1 = array();
								$return_array2 = array();

								$orders = wc_get_orders( array(
								    'limit'    => -1,
								    'status' => array('wc-presale4'),
									'return' => 'ids',
								) );


								foreach($orders as $order_id)
								{
									
									$order = wc_get_order( $order_id );

									
									//$order_items  = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
									foreach ( $order->get_items() as $item_id => $item ) {
									$a = array();
									   $product_id = $item->get_product_id();
									   $variation_id = $item->get_variation_id();


									    if(!empty($product_id) && !empty($variation_id))
									    {
											$getCustomerID = get_post_meta($order_id, '_customer_user', true);
											if($_GET['purchased'] == 'with-users')
											{
												$final_result1[$variation_id][] = $order_id;
											}
										
											
											$final_result3[$variation_id][$getCustomerID][] = $item_id;					
										}
									}
								}



								if($_GET['purchased'] == 'with-users')
								{
									foreach($final_result1 as $key => $value)
									{
									  foreach($value as $ak)
									  {
										$user_meta = get_post_meta($ak, '_customer_user', true);	
										if(!in_array($user_meta, $return_array1))
										{
											array_push($return_array1, $user_meta);
										}
									  }
									}
									
									foreach($final_result3 as $key3 => $value3)
									{
										foreach($value3 as $key4 => $abc)
										{
											$sum = 0;
											$d = 0;
											foreach($abc as $c)
											{
												$c1 = 0;
												$variation_size = wc_get_order_item_meta( $c, 'item_variation_size', true );
												$ap = wc_get_order_item_meta( $c, '_qty', true );
												foreach ($variation_size as $key => $size) 
												{
													$c1 += $size['value'];
													$merge1[$key3][$size['label']][] = $ap * $size['value'];
												}
												
												
												$sum += $c1 * $ap; 
												$d += $c1;
											}
											
											$merge[$key3][$key4][] = $sum;
											$merge2[$key3][] = $d;
										}
									}

								}
								



	                		?>
	                		<span id="exportexcel" onclick="fnExcelReport();">Export to XLSX</span>	
							<span id="exportexcel1" onclick="fnExcelReport1();">Export All to XLSX</span>
							<span id="stop-refresh">Exporting is inprogress. Please don't refresh the page.</span>

							<div class="exporting-it">
							<table class="table table-bordered" id="demo">
								

							   <thead>
									<tr>
									  <th style="vertical-align : middle;text-align:center;">Product image</th>
									  <th  style="vertical-align : middle;text-align:center;">Style name</th>
									  <th  style="vertical-align : middle;text-align:center;">Style sku</th>
									  <th  style="vertical-align : middle;text-align:center;">Brand</th>
									  <th  style="vertical-align : middle;text-align:center;">Gender</th>
									  <th  style="vertical-align : middle;text-align:center;">Category</th>
									  <th  style="vertical-align : middle;text-align:center;">Sub-category</th>
									  <th  style="vertical-align : middle;text-align:center;">Season</th>
									  <th style="vertical-align : middle;text-align:center;min-width: 215px;">Composition</th>
									  <th style="vertical-align : middle;text-align:center;min-width: 215px;">Producto logo</th>
									  <?php
									  if($_GET['purchased'] == 'with-users')
									  {
										  $countries = WC()->countries->get_countries();
										  //print_r($countries);
										  foreach($return_array1 as $key1 => $value1)
										  {
											  //echo $value1 . "<br>";
												$user_info = get_userdata($value1);
												$first_name = $user_info->first_name;
												$last_name = $user_info->last_name;
												$getCompany = get_user_meta($value1, 'billing_company', true);
												$getCountry = $countries[get_user_meta($value1, 'billing_country', true)];
												echo "<th style='vertical-align : middle;text-align:center; min-width: 215px;' data-customer_id='". $first_name  . " " . $last_name ."'>" . $first_name  . " " . $last_name . " - " . $getCompany . " - " . $getCountry . "</th>";			 
										  }
									  }
									  ?>
									  <th style="vertical-align : middle;text-align:center;">Selling Price</th>
									  <th style="vertical-align : middle;text-align:center;">Total Unit Purchased</th>
									  <th style="vertical-align : middle;text-align:center;">Open Stock</th>
									  <th style="vertical-align : middle;text-align:center;">Total Value</th>
									</tr>
								  </thead>
								<tbody id="myTable">
									<?php 
										$i = 0;
										$len = count($merge);

										$tableBody = array();
										foreach($merge as $key => $value)
										{
											
											$_product =  wc_get_product( $key); 
											$main_product = wc_get_product( $_product->get_parent_id() );
											$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
											/* $terms_string = join(', ', wp_list_pluck($cat, 'name'));
											echo $terms_string; */
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
											
											$e = get_post_meta($key, '_stock', true);
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
											
										
											$image_id			= $_product->get_image_id();
											$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
											$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
											$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );
											$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
											$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));
											
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

											echo "<tr>";
											echo "<td class='".$_product->get_sku()."'><img src='" . $thumbnail_src[0] . "'/></td>";
											echo "<td class='".$_product->get_sku()."'>" . $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' );
											echo $row3;
											echo "</td>";
											echo "<td class='".$_product->get_sku()."'>" . $_product->get_sku() . "</td>";
											echo "<td class='".$_product->get_sku()."'>" . $main_product->get_attribute( 'pa_brand' ) . "</td>";
											echo "<td class='".$_product->get_sku()."'>" . implode(", ", $css_slugGender) . "</td>";
											echo "<td class='".$_product->get_sku()."'>" . implode(", ", $css_slugCategory) . "</td>";
											echo "<td class='".$_product->get_sku()."'>" . implode(", ", $css_slugSubCategory) . "</td>";
											echo "<td class='".$_product->get_sku()."'>" . $main_product->get_attribute( 'pa_season' ) . "</td>";
											echo "<td class='".$_product->get_sku()."'>" . $fabricCompositionString . "</td>";
											echo "<td class='".$_product->get_sku()."'>" . $logoApplicationString . "</td>";

											$imageUrlThumb = str_replace("https://shop.fexpro.com", "",$thumbnail_src[0]);

											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $imageUrlThumb ));
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' )) );
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $_product->get_sku()));
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $main_product->get_attribute( 'pa_brand' )));

											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugGender) ));

											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugCategory) ));
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugSubCategory) ));
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $main_product->get_attribute( 'pa_season' ) ));
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $logoApplicationString ));
											array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $fabricCompositionString ));

											$apk = 0;
											if($_GET['purchased'] == 'with-users')
											{
												
												foreach($return_array1 as $key1 => $value1)
												{
													$user_info = get_userdata($value1);
													$first_name = $user_info->first_name;
													$last_name = $user_info->last_name;
													//$company_name = get_user_meta($value1, 'billing_company', true);
													echo "<td class='".$_product->get_sku()."' data-customer_id='". $first_name  . " " . $last_name ."'>" . $value[$value1][0] . "</td>";
													$apk += $value[$value1][0];

													array_push($tableBody, (object)  array('Title' => $_product->get_sku(), 'data' => $value[$value1][0]));
												}
											}	
											elseif($_GET['purchased'] == 'without-users' || isset($_GET['cat_purchased']))
											{
												$apk = $value[0];
											}
											
										array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' =>  get_post_meta($key, '_regular_price', true) ));
										array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $apk ));
										array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $e));

											$getTotalPrice = number_format((get_post_meta($key, '_regular_price', true) * $apk),2,'.', ',' );

										array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $getTotalPrice)  );

												
											echo "<td class='".$_product->get_sku()."'>" . get_post_meta($key, '_regular_price', true) . "</td>";					
											echo "<td class='".$_product->get_sku()."'>" . $apk . "</td>";
											echo "<td class='".$_product->get_sku()."'>" . $e . "</td>";
											echo "<td class='".$_product->get_sku()."'>" . wc_price(get_post_meta($key, '_regular_price', true) * $apk) . "</td>";
											echo "</tr>";
											echo "</tr>";	


											if ($i == $len - 1) {


												echo "<tr style='background-color: #000; color: #fff;' class='last-tr'>";
														echo "<td class='total-line'></td>";
														echo "<td class='total-line'></td>";
														echo "<td class='total-line'></td>";
														echo "<td class='total-line'></td>";
														echo "<td class='total-line'></td>";
														echo "<td class='total-line'></td>";
														echo "<td class='total-line'></td>";
														echo "<td class='total-line'></td>";
														echo "<td class='total-line'></td>";
														echo "<td class='total-line'></td>";
														if($_GET['purchased'] == 'with-users')
														{
															foreach($return_array1 as $key1 => $value1)
															{
																$user_info = get_userdata($value1);
																$first_name = $user_info->first_name;
																$last_name = $user_info->last_name;
																echo "<td class='total-line' data-customer_id='" . $first_name  . " " . $last_name . "'></td>";
															}
														}											
														echo "<td class='total-line'></td>";
														echo "<td class='total-line'></td>";
														echo "<td class='total-line'></td>";
														echo "<td class='total-line'></td>";
												echo "</tr>";
											}
										$i++;
										}
									?>
								</tbody>
							</table>
							</div>
							<?php 
							delete_transient('getTableBodyData');

							set_transient('getTableBodyData', $tableBody, 21600);
							?>
	                	<?php }else{ ?>
                        <h2 class="dispalyTitle"><?php echo $_GET['name'] ?></h2>
                        
	                	<span id="exportexcel1" onclick="fnExcelReport1();">Export All to XLSX</span>
						<span id="stop-refresh">Exporting is inprogress. Please don't refresh the page.</span>

                        <span class='no-v'></span>
						<div class="exporting-it">
							<table class="table table-bordered" id="demo_table">
							<thead>
									<tr>
									<th style="vertical-align : middle;text-align:center;">Product image</th>
									<th  style="vertical-align : middle;text-align:center;">Style name</th>
									<th  style="vertical-align : middle;text-align:center;">Style sku</th>
									<th  style="vertical-align : middle;text-align:center;">Brand</th>
									<th  style="vertical-align : middle;text-align:center;">Gender</th>
									<th  style="vertical-align : middle;text-align:center;">Category</th>
									<th  style="vertical-align : middle;text-align:center;">Sub-category</th>
									<th  style="vertical-align : middle;text-align:center;">Season</th>
									<th style="vertical-align : middle;text-align:center;min-width: 215px;">Composition</th>
									<th style="vertical-align : middle;text-align:center;min-width: 215px;">Producto logo</th>
									<th style="vertical-align : middle;text-align:center;">Selling Price</th>
									<th style="vertical-align : middle;text-align:center;">Total Unit Purchased</th>
									<th style="vertical-align : middle;text-align:center;">Open Stock</th>
									<th style="vertical-align : middle;text-align:center;">Total Value</th>
									</tr>
								</thead>
								
								<tfoot>
									<th style="vertical-align : middle;text-align:center;">Product image</th>
									<th  style="vertical-align : middle;text-align:center;">Style name</th>
									<th  style="vertical-align : middle;text-align:center;">Style sku</th>
									<th  style="vertical-align : middle;text-align:center;">Brand</th>
									<th  style="vertical-align : middle;text-align:center;">Gender</th>
									<th  style="vertical-align : middle;text-align:center;">Category</th>
									<th  style="vertical-align : middle;text-align:center;">Sub-category</th>
									<th  style="vertical-align : middle;text-align:center;">Season</th>
									<th style="vertical-align : middle;text-align:center;min-width: 215px;">Composition</th>
									<th style="vertical-align : middle;text-align:center;min-width: 215px;">Producto logo</th>

								
									<th style="vertical-align : middle;text-align:center;">Selling Price</th>
									<th style="vertical-align : middle;text-align:center;">Total Unit Purchased</th>
									<th style="vertical-align : middle;text-align:center;">Open Stock</th>
									<th style="vertical-align : middle;text-align:center;">Total Value</th>
								</tfoot>
							
							</table>
						</div>

						<?php } ?>

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
		<script src="<?php echo SAGE_ECOMMERCEURL; ?>/assets/node_modules/raphael/raphael-min.js"></script>
		<script src="<?php echo SAGE_ECOMMERCEURL; ?>/assets/node_modules/morrisjs/morris.min.js"></script>
	    <!-- ============================================================== -->
	    <!-- This page plugins -->
	    <!-- ============================================================== -->
	    <!--Custom JavaScript -->
	    <script src="<?php echo SAGE_SITEURL; ?>/dist/js/ecom-dashboard.js"></script>



	    <?php if($_GET['purchased'] == 'with-users') { ?>
	    	<script src="../dist/tablefilter/tablefilter.js"></script>
			<script src="../test-filters-visibility-factory.js"></script>


			<script>
				const formatToCurrency = amount => {
				  return "$" + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
				};
				jQuery(window).bind("load", function () {
					var d = 0;	
					var c = 0;
					setTimeout(function() {
					jQuery("table#demo tbody > tr:not('.last-tr'):visible td:nth-last-child(3)").each(function(){
						c += Number($(this).text());
					});
					console.log(c);
					jQuery(".last-tr td:nth-last-child(3)").text(c);
					
					jQuery("table#demo tbody > tr:not('.last-tr'):visible td:last-child .woocommerce-Price-amount bdi").each(function(){
						//console.log(Number($(this).text().replace(/[^0-9.-]+/g,"")));
						d += Number($(this).text().replace(/[^0-9.-]+/g,""));
					});
					jQuery(".last-tr td:last-child").text(formatToCurrency(d));
					},500);
				});
				$('#demo tr.fltrow input').keypress(function (e) {
					var key = e.which;
					if(key == 13)  // the enter key code
					{
						var c = 0;
						var d = 0;
						setTimeout(function() {
						jQuery("table#demo tbody > tr:not('.last-tr'):visible td:nth-last-child(3)").each(function(){
							c += Number($(this).text());
							
						});
						console.log(c);
						jQuery(".last-tr td:nth-last-child(3)").text(c);
						
						jQuery("table#demo tbody > tr:not('.last-tr'):visible td:last-child .woocommerce-Price-amount bdi").each(function(){
							//console.log(Number($(this).text().replace(/[^0-9.-]+/g,"")));
							d += Number($(this).text().replace(/[^0-9.-]+/g,""));
						});
						jQuery(".last-tr td:last-child").text(formatToCurrency(d));
						
						},500);
					}
					jQuery(".last-tr").css({"display": ""});
				});

				$('.pgSlc, .rspg').on('change', function (e) {
					var key = e.which;
						var c = 0;
						var d = 0;
						setTimeout(function() {
						jQuery("table#demo tbody > tr:not('.last-tr'):visible td:nth-last-child(3)").each(function(){
							c += Number($(this).text());
							
						});
						console.log(c);
						jQuery(".last-tr td:nth-last-child(3)").text(c);
						
						jQuery("table#demo tbody > tr:not('.last-tr'):visible td:last-child .woocommerce-Price-amount bdi").each(function(){
							//console.log(Number($(this).text().replace(/[^0-9.-]+/g,"")));
							d += Number($(this).text().replace(/[^0-9.-]+/g,""));
						});
						jQuery(".last-tr td:last-child").text(formatToCurrency(d));
						
						},500);
					
					jQuery(".last-tr").css({"display": ""});
				});

				$('input.pgInp.nextPage, input.pgInp.lastPage, input.pgInp.previousPage, input.pgInp.firstPage').on('click', function (e) {
					var key = e.which;
						var c = 0;
						var d = 0;
						setTimeout(function() {
						jQuery("table#demo tbody > tr:not('.last-tr'):visible td:nth-last-child(3)").each(function(){
							c += Number($(this).text());
							
						});
						console.log(c);
						jQuery(".last-tr td:nth-last-child(3)").text(c);
						
						jQuery("table#demo tbody > tr:not('.last-tr'):visible td:last-child .woocommerce-Price-amount bdi").each(function(){
							//console.log(Number($(this).text().replace(/[^0-9.-]+/g,"")));
							d += Number($(this).text().replace(/[^0-9.-]+/g,""));
						});
						jQuery(".last-tr td:last-child").text(formatToCurrency(d));
						
						},500);
					
					jQuery(".last-tr").css({"display": ""});
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
					
					jQuery( 'table#demo thead > tr:nth-child(2) th' ).each(function() {
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
								if(tab.rows[i].cells[k].innerHTML.indexOf("uploads") != -1)
								{
									var abc = tab.rows[i].cells[k].innerHTML.split("https://v9r8j6s9.stackpathcdn.com");
									var res = abc[1].replace('">', "");
									//myArray1.push(res);
									myArray1.push({
										'Title': tab.rows[i].cells[k].getAttribute("class"), 
										'data':  res
									});
								}
								else if(tab.rows[i].cells[k].innerHTML.indexOf("woocommerce-Price-amount amount") != -1)
								{
									var abc2 = tab.rows[i].cells[k].innerHTML.split("$</span>");
									var res2 = abc2[1].replace('</bdi></span>', "");
									//myArray1.push(res2);
									myArray1.push({
										'Title': tab.rows[i].cells[k].getAttribute("class"), 
										'data':  res2
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
					
					jQuery( 'table#demo thead > tr:nth-child(2) th' ).each(function() {
							myArray.push(jQuery(this).text());		
					});
					form_data.append('getHeaderArray', JSON.stringify(myArray));
					form_data.append('action', 'export_cart_entries_all_data');

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

	   	<?php }else{ ?> 	
	   			<script src="<?php echo SAGE_SITEURL; ?>/include/js/custom-fexpro.js"></script>
		   		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
				<link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
				<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css"> 

				<script src="jquery.dataTables.min.js"></script>
				<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
				<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
				<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
				<script src="https://cdn.datatables.net/fixedcolumns/3.3.3/js/dataTables.fixedColumns.min.js"></script>

					<script>

				$(function () {
					
					count = 0;
					wordsArray = [
						"Panama is the only place in the world where you can see the sun rise on the Pacific and set on the Atlantic.", 
						"The canal generates fully one-third of Panama's entire economy.", 
						"A man, a plan, a canal; Panama. is a palindrome.", 
						"Panama was the first Latin American country to adopt the U.S. currency as its own.",
						"The cargo ship Ancon was the first vessel to transit the Canal on August 15, 1914.",
						"The lowest toll paid was $0.36 and was paid by Richard Halliburton who crossed the Canal swimming in 1928.",
						"The City of Panama boasts the oldest, longest, continuosly running Municipal Council in the american continent.",
						"Seven out of ten Panamanians haven't heard of the song \"Panama\" by Van Halen.  Ha!",
						"Senator John McCain was born in Panama, in the Canal Zone that was, at the time considered U.S. Territory.",
						"The Panama Hat is really made in Ecuador.",
						"In Panama, you can swim in the Atlantic Ocean and the Pacific Ocean in the same day.",
						"The oldest continually operating railroad is in Panama. It travels from Panama City to Colon and back.",
						"Panama City is the only capital city that has a rain forest within the city limits.",
					];
					$(".loader__label").html(wordsArray[0]);
					setInterval(function () {
						count++;
						$(".loader__label").fadeOut(1000, function () {
							$(this).text(wordsArray[count % wordsArray.length]).fadeIn(1000);
						});
					}, 4000);
				});

				$ = jQuery;
					$(document).ready(function() {
	                    var purchased = '<?php echo $_GET['purchased']; ?>';
						var cat_purchased = '<?php echo $_GET['cat_purchased']; ?>';
						var demo_table = $('#demo_table').dataTable({
							dom: '<"wrapper"flipt>',

							columns: [
								{ data: 'product_image',name: 'product_image', render: function (data, type, row) {
									return type === 'export' ? data : '<img src="' + data + '" />';
								} },
								{ data: 'item_name',name: 'item_name'},
								{ data: 'product_sku',name: 'product_sku' },
								{ data: 'pa_brand',name: 'pa_brand' },
								{ data: 'gender',name: 'gender' },
								{ data: 'category',name: 'category' },
								{ data: 'sub_category',name: 'sub_category' },
								{ data: 'season',name: 'season' },
								{ data: 'composition',name: 'composition' },
								{ data: 'producto_logo',name: 'producto_logo' },
								{ data: 'selling_price',name: 'selling_price' },
								{ data: 'total_purchased_unit' ,name: 'total_purchased_unit'},
								{ data: 'open_stock' ,name: 'open_stock'},
								{ data: 'total_amount' ,name: 'total_amount', render: function (data, type, row) {
	                                var data1 = data.replace('$', '');
									return type === 'export' ?  data1 : data ;
								} }
	                            
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
								url: "https://shop.fexpro.com/wp-admin/admin-ajax.php",
								data:{action:"alpha_get_ss22_presale4_data",purchased:purchased,cat_purchased:cat_purchased},
								type: "POST",
								
							},
							
							
							
						} );

						$('div.dataTables_filter input').unbind();
						$('div.dataTables_filter input').bind('keyup', function(e) {
							if(e.keyCode == 13) {
								$('.preloader').css('display','block');
								demo_table.fnFilter(this.value); 
								
							}
						});     


					
						
						$( document ).ajaxComplete(function() {
							$('.preloader').css('display','none');
							$('#exportexcel1').attr('data-currentpage', $(this).find('a.current').text());
							jQuery("#myTable > tr").each(function() {
								// FOr sku print on td
								var c = $(this).children('td').eq(2).text();
	                            jQuery(this).children('td').addClass(c);

							});
						});

						
						$('#exportexcel1').attr('data-exportData', $('#demo_table_length').find('select > option:selected').text());
						$('#demo_table_length').on('change', function(){
							var onChangeEventVal = $(this).find('select > option:selected').text();
							$('#exportexcel1').attr('data-exportData', onChangeEventVal);
							$('.preloader').css('display','block');
						});

						


						$('#demo_table_paginate').on('click', function(){
							$('.preloader').css('display','block');
						});

					

					} );

				function fnExcelReport1()
				{	

					var exportData = jQuery('#exportexcel1').attr('data-exportdata');
					var currentPage = jQuery('#exportexcel1').attr('data-currentpage');
					var factory_name = '<?php echo $_GET['name']; ?>';
					
					var SITEURL = "<?php echo site_url(); ?>/wp-content/themes/porto-child/";
					var form_data = new FormData();   
					var myArray = [];
					var myArray1 = [];
					var myArrayImage2 = [];
					var data = {};
					var tab = document.getElementById('myTable');
					var i=0, k=0;
					
					jQuery( 'table#demo_table thead > tr > th' ).each(function() {
							myArray.push(jQuery(this).text());		
					});

					form_data.append('getHeaderArray', JSON.stringify(myArray));
					form_data.append('exportData', exportData );
					form_data.append('currentPage', currentPage );
					form_data.append('action', 'alpha_presale4_export_view_factory_data');
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

		<?php } ?>
	
		

	

	
</body>

</html>

<?php 
} else {
    header('location: https://shop.fexpro.com/sagelogin/ecommerce/alpha/pages-login.php');
    exit;
}
?>