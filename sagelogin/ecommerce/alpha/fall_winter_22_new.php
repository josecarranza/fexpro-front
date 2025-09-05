<?php 
require_once 'include/common.php';
get_currentuserinfo();
global $wpdb;
if(is_user_logged_in()) {

	if($_GET['purchased'] == 'with-users')
	{
		$t = 'Users Purchased List';
		$p = 'Users Purchased list';
	}
	elseif($_GET['purchased'] == 'without-users')
	{
		$t = 'Without Users Purchased list';
		$p = 'Without Users Purchased list';
	}
	elseif($_GET['cat_purchased'] == 'mens-basics')
	{
		$t = 'Mens Basics';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'womens-basics')
	{
		$t = 'Womens Basics';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'boys-basics')
	{
		$t = 'Boys Basics';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'sports-mens-apparel')
	{
		$t = 'Sports Mens Apparel';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'sports-womens-apparel')
	{
		$t = 'Sports Womens Apparel';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'sports-boys-apparel')
	{
		$t = 'Sports Boys Apparel';
		$p = 'Mens Basics';
	}
	
	elseif($_GET['cat_purchased'] == 'sports-unisex-apparel')
	{
		$t = 'Sports Unisex Apparel';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'pop-mens-apparel')
	{
		$t = 'Pop Mens Apparel';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'pop-womens-apparel')
	{
		$t = 'Pop Womens Apparel';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'underwear-and-boxers')
	{
		$t = 'Underwear and Boxers';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'socks-summer-spring-22')
	{
		$t = 'Socks';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'mens-pijamas')
	{
		$t = 'Mens Pijamas';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'pijamas-underwear-sleep-womens-summer-spring-22')
	{
		$t = 'Womens Pijamas';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'footwear-mens-summer-spring-22')
	{
		$t = 'Footwear Mens';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'footwear-boys-summer-spring-22')
	{
		$t = 'Footwear Boys';
		$p = 'Mens Basics';
	}
	elseif($_GET['cat_purchased'] == 'headwear')
	{
		$t = 'Headwear';
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
	    <title>Fexpro Sage - FW22 Factory Order Lists</title>
	    <!-- chartist CSS -->
	    <link href="<?php echo SAGE_SITEURL; ?>/dist/css/pages/ecommerce.css" rel="stylesheet">
	    <!-- Custom CSS -->
	    <link href="<?php echo SAGE_SITEURL; ?>/dist/css/style.min.css?v=2.1" rel="stylesheet">

	    <link href="<?php echo SAGE_SITEURL; ?>/alpha/include/css/custom-fexpro.css?v=2.2" rel="stylesheet">

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
		.btn-dd{
			padding: 10px;
			background: #000;
			color:#fff;
			display: inline-block;
		}
		#demo_table tbody td{
			background:#fff;
		}
		#demo_table thead th{
			font-weight:bold;
		}
		.cell-bg-DROP{
			background-color:#ff7878 !important;
		}
		.cell-bg-STOCK{
			background-color:#fff578 !important;
		}
		.cell-bg-OK{
			background-color:#89c160 !important;
		}
		.input-edit-value2{
			position: relative;
		}
		.input-edit-value2 input{
			width: 100%;
			padding:5px;
			font-size:12px;
			border:1px solid #ccc;
			min-width:120px
		}

		.input-edit-value2 .btn-save{
			display: inline-block;
			width: 30px;
			height:30px; 
			position: absolute;
			top:0px;
			right: 0px;
			cursor: pointer;
		}
		.input-edit-value2 .btn-save:hover{
			box-shadow:0px 0px 3px #6a58ff;
		}
		.input-edit-value2 .btn-save img{
			width: 100%;
		}
		
  	</style>

<script>
			window.onload = function () {    

  				jQuery('.user-profile .dropdown a.dropdown-toggle').click(function(e){
                	jQuery('.user-profile .dropdown').find('.dropdown-menu').toggle();
            	});

			}


		</script>
        

	    </head>
	<body class="skin-default fixed-layout" ng-app="app" ng-controller="ctrl">
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
						<div class="col-10">
							<a class="btn-dd" href="https://shop2.fexpro.com/wp-admin/admin-ajax.php?action=report_purchase&export=1&new_columns=1&{{url_params}}" target="_blank">Export All to XLSX</a>

							<label for="" style="margin-left:30px">Presale: </label>
							<select name="" id="" class="form-control" style="width:200px" ng-model="filter_order_status" ng-change="filtrar()"
							ng-options="item.code as item.name for item in filters.order_status">
								
							</select>

						</div>
					</div>

                        <span class='no-v'></span>
						<div class="exporting-it">
						<table class="table table-bordered fixed-table" id="demo_table">
							<thead>
									<tr>
									<th style="vertical-align : middle;text-align:center" > <span style="width:224px; display:inline-block">Product image</span>
									
									</th>
									<th  style="vertical-align : middle;text-align:center;" ><span style="width:118px; display:inline-block">Style name</span>
									
									</th>
									<th  style="vertical-align : middle;text-align:center;">Style sku 
										<sage-filter-text list="filters.filter_sku" />
									</th>
									<th  style="vertical-align : middle;text-align:center;" > 
										<sort-dir col="lob" text="Division"></sort-dir>
											
										<sage-filter list="filters.filter_lob" />
									</th>
									<th  style="vertical-align : middle;text-align:center;">
										<sort-dir col="global_brand" text="Global brand"></sort-dir>
										<sage-filter list="filters.filter_global_brand" />
									</th>
									<th  style="vertical-align : middle;text-align:center;">
										<sort-dir col="brand" text="Brand"></sort-dir>
										<sage-filter list="filters.filter_brand" />
									</th>
									<th  style="vertical-align : middle;text-align:center;"><sort-dir col="gender" text="Gender"></sort-dir> <sage-filter list="filters.filter_gender" /></th>
									<th  style="vertical-align : middle;text-align:center;"><sort-dir col="cat" text="Category"></sort-dir> <sage-filter list="filters.filter_cat" /></th>
									<th  style="vertical-align : middle;text-align:center;"><sort-dir col="group" text="Group"></sort-dir> <sage-filter list="filters.filter_group" /></th>
									<th  style="vertical-align : middle;text-align:center;"><sort-dir col="product_type" text="Product"></sort-dir> <sage-filter list="filters.filter_product_type" /></th>
									<th  style="vertical-align : middle;text-align:center;"><sort-dir col="season" text="Season"></sort-dir> <sage-filter list="filters.filter_season" /></th>
									<th  style="vertical-align : middle;text-align:center;"><sort-dir col="collection" text="Collection"></sort-dir> <sage-filter list="filters.filter_collection" /></th>
									<th  style="vertical-align : middle;text-align:center;"><sort-dir col="date" text="Date"></sort-dir> <sage-filter list="filters.filter_date" /></th>
									<th  style="vertical-align : middle;text-align:center;"><sort-dir col="team" text="Team"></sort-dir> <sage-filter list="filters.filter_team" /></th>
									<th style="vertical-align : middle;text-align:center;">Player</th>
									<th style="vertical-align : middle;text-align:center;min-width: 215px;">Composition</th>
									<th style="vertical-align : middle;text-align:center;min-width: 215px;">Product logo</th>
									<th style="vertical-align : middle;text-align:center;min-width: 120px;">Size chart</th>
									<th style="vertical-align : middle;text-align:center;min-width: 100px;">Dimensions</th>
									<th style="vertical-align : middle;text-align:center;">Selling Price</th>

									<th style="vertical-align : middle;text-align:center;">Total Units</th>
									<th style="vertical-align : middle;text-align:center;">Total Value</th>
									<th style="vertical-align : middle;text-align:center;">Price MX</th>
									<th style="vertical-align : middle;text-align:center;">
										System Suggestion <sage-filter list="filters.filter_suggestion" />
									</th>
									<th style="vertical-align : middle;text-align:center;">MOQ</th>
									<th style="vertical-align : middle;text-align:center;">Stock Panamá</th>
									<th style="vertical-align : middle;text-align:center;">Stock China</th>
									<th style="vertical-align : middle;text-align:center;">Stock Future</th>
									<th style="vertical-align : middle;text-align:center;">
										Factory order status <sage-filter list="filters.filter_factory_status" />
									</th>
									<th style="vertical-align : middle;text-align:center;">Ordenado fábrica</th>
									<th style="vertical-align : middle;text-align:center;">Cost FOB</th>
									<th style="vertical-align : middle;text-align:center;">Supplier</th>
									<th style="vertical-align : middle;text-align:center;">Supplier code</th>
									<th style="vertical-align : middle;text-align:center;">PI#</th>
									<th style="vertical-align : middle;text-align:center;">Sourcing Office</th>
									<th style="vertical-align : middle;text-align:center;">Open units</th>
									<th style="vertical-align : middle;text-align:center;">% units</th>
									<th></th>
									</tr>
								</thead>
								<tbody id="myTable">
									<tr ng-repeat="row in report" class="odd">
										<td ng-repeat="(key, value) in row" ng-if="key=='image'" style="white-space:nowrap" class="fixed-image"><img src="{{value}}" height="100" ><img src="{{row.image2}}" height="100" ng-if="row.image2!=''" style="margin-right:-10px" ></td>
										<td ng-repeat="(key, value) in row" ng-if="!['id','image','image2','sending','finished'].includes(key)" class="{{key=='suggestion'? 'cell-bg-'+value :''}} {{key=='product_title'?'fixed-title':''}}">
											<span ng-if="!['fob','supplier','supplier_code','pi','sourcing_office','ordenado_fab'].includes(key)">
												<span ng-if="key!='price' && key!='subtotal' && key!='price_mx' ">{{value}}</span>
												<span ng-if="key=='price' || key=='subtotal' || key=='price_mx'">{{value | currency:'$'}}</span>
											</span>
											<div ng-if="['fob','pi','sourcing_office','ordenado_fab','supplier_code'].includes(key)">
												 <input-save value="value" row="row"  id-variation="{{row['id']}}" meta-key="{{key}}"  disabled="row.sending" />
											</div>
											<div ng-if="['supplier'].includes(key)">
												<sage-autocomplete list="filters.filter_supplier" value="value" row="row" meta-key="{{key}}" />
											</div>
										</td>
										<td>
											<div class="actions-buttons">
												<span class="ico-save" ng-show="!row.sending && !row.finished" ng-click="saveRow($index,row)"></span>
												<span class="ico-loading" ng-show="row.sending"></span>
												<span class="ico-ok"  ng-show="row.finished"></span>
											</div>
										</td>
										
									</tr>
									<tr ng-if="report.length==0">
										<td colspan="21"> LOADING...</td>
									</tr>
								</tbody>
								
								<tfoot>
								<th style="vertical-align : middle;text-align:center;">Product image</th>
									<th  style="vertical-align : middle;text-align:center;">Style name</th>
									<th  style="vertical-align : middle;text-align:center;">Style sku</th>
									<th  style="vertical-align : middle;text-align:center;">Division</th>
									<th  style="vertical-align : middle;text-align:center;">Global brand</th>
									<th  style="vertical-align : middle;text-align:center;">Brand</th>
									<th  style="vertical-align : middle;text-align:center;">Gender</th>
									<th  style="vertical-align : middle;text-align:center;">Category</th>
									<th  style="vertical-align : middle;text-align:center;">Group</th>
									<th  style="vertical-align : middle;text-align:center;">Product</th>
									<th  style="vertical-align : middle;text-align:center;">Season</th>
									<th  style="vertical-align : middle;text-align:center;">Collection</th>
									<th  style="vertical-align : middle;text-align:center;">Date</th>
									<th  style="vertical-align : middle;text-align:center;">Team</th>
									<th  style="vertical-align : middle;text-align:center;">Player</th>
									<th style="vertical-align : middle;text-align:center;min-width: 215px;">Composition</th>
									<th style="vertical-align : middle;text-align:center;min-width: 215px;">Product logo</th>
									<th style="vertical-align : middle;text-align:center;min-width: 120px;">Size chart</th>
									<th style="vertical-align : middle;text-align:center;min-width: 100px;">Dimensions</th>
									<th style="vertical-align : middle;text-align:center;">Selling Price</th>
									<th style="vertical-align : middle;text-align:center;">Total Units</th>

									<th style="vertical-align : middle;text-align:center;">Total Value</th>
									<th style="vertical-align : middle;text-align:center;">Price MX</th>
									<th style="vertical-align : middle;text-align:center;">System Suggestion</th>
									<th style="vertical-align : middle;text-align:center;">MOQ</th>
									<th style="vertical-align : middle;text-align:center;">Stock Panamá</th>
									<th style="vertical-align : middle;text-align:center;">Stock China</th>
									<th style="vertical-align : middle;text-align:center;">Stock Future</th>
																		
									<th style="vertical-align : middle;text-align:center;">Factory order status</th>
									<th style="vertical-align : middle;text-align:center;">Ordenado fábrica</th>
									<th style="vertical-align : middle;text-align:center;">Cost FOB</th>
									<th style="vertical-align : middle;text-align:center;">Supplier</th>
									<th style="vertical-align : middle;text-align:center;">Supplier code</th>
									<th style="vertical-align : middle;text-align:center;">PI#</th>
									<th style="vertical-align : middle;text-align:center;">Sourcing Office</th>
									<th style="vertical-align : middle;text-align:center;">Open units</th>
									<th style="vertical-align : middle;text-align:center;">% units</th>
									<th></th>
								</tfoot>
							
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
		<script src="<?php echo SAGE_ECOMMERCEURL; ?>/assets/node_modules/raphael/raphael-min.js"></script>
		<script src="<?php echo SAGE_ECOMMERCEURL; ?>/assets/node_modules/morrisjs/morris.min.js"></script>
	    <!-- ============================================================== -->
	    <!-- This page plugins -->
	    <!-- ============================================================== -->
	    <!--Custom JavaScript -->
	    <script src="<?php echo SAGE_SITEURL; ?>/dist/js/ecom-dashboard.js"></script>

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
		

	

<script src="<?php echo SAGE_SITEURL; ?>/alpha/angular.min.js"></script>
<script src="<?php echo SAGE_SITEURL; ?>/alpha/angular.components.js?v=2.2"></script>
		<script>
			angular.module("app",['util-components']).controller("ctrl",function($scope,$http,$timeout){
				$scope.model={};
				$scope.model.sellers=[];
				$scope.report=[];
				$scope.filters={};
				$scope.filters_active={};
				$scope.sort={dir:'asc',param:""};
				$scope.pag=1;
				$scope.url_params="";
				$scope.get_data=function(){
					var _params={};
					if($scope.filters_active){
						_params={...$scope.filters_active}
					}
					_params={..._params,...$scope.sort,page:$scope.pag,presale_status:$scope.filter_order_status};
					if($scope.pag==1){
						$scope.report=[];
					}
					$scope.url_params=$.param( _params );
					
					$http({
						url:"https://shop2.fexpro.com/wp-admin/admin-ajax.php?action=report_purchase&new_columns=1",
						method:"POST",
						data:_params
					}).then(function(response){
						//$scope.model.sellers=response.data.sellers;
						if($scope.pag==1){
							$scope.report=response.data.data;
						}else{
							if(response.data.data != null){
								$scope.report=$scope.report.concat(response.data.data);
							}
							
						}
						$scope.pag++;
						trigger_send=false;
					});
				};
				$scope.get_data();
				$scope.filtrar=function(){
				 
					$scope.report=[];
					$scope.pag=1;
					$scope.get_data();
				}
				$scope.get_filters=function(){
					$http({
						url:"https://shop2.fexpro.com/wp-admin/admin-ajax.php?action=filtros_reportes_get",
						method:"GET"
					}).then(function(response){
						$scope.filters=response.data;
						$scope.filters.filter_sku=[{text:"sku",value:""}];
						let suppliers  = [];
						angular.forEach($scope.filters.filter_supplier,(v,k)=>{
							suppliers.push({key:k,value:`${v} - ${k}`});
						})
						$scope.filters.filter_supplier =suppliers;

						//$scope.filters.order_status = $scope.filters.order_status.map(x=>{ x.checked = x.default == '1'; return x});

						$scope.filter_order_status = $scope.filters.order_status.filter(x=>x.default == '1')[0].code;
						
					});
				};
				$scope.get_filters();
				$scope.$on("update_filters",function(){
					$scope.filters_active={};
					angular.forEach($scope.filters,(element,key) => {
						if(!$scope.filters_active[key]){
							$scope.filters_active[key]=[]
						}
						angular.forEach(element,(item,key2) => {
							if(item.checked){
								$scope.filters_active[key].push(item.value);
							}
						});
					});
					console.log($scope.filters_active);
				});
				$scope.$on("apply_filters",function(){
					$scope.pag=1;
					$scope.get_data();
				});

			
				$scope.$on("update_sort_parent",function(event,data){
					$scope.sort=data;
					$scope.$broadcast('update_sort',data);
				});

				$scope.saveMeta = (_data)=>{
					console.log(_data);
					$http({
						url:"https://shop2.fexpro.com/wp-admin/admin-ajax.php?action=save_meta_variation",
						method:"POST",
						data:_data
					}).then(function(response){
						console.lo(response.data);
					});
				};


				$scope.saveRow=(_index,row)=>{
					row.sending=true;
					console.log(row)
					$http({
						url:"https://shop2.fexpro.com/wp-admin/admin-ajax.php?action=save_meta_variation",
						method:"POST",
						data:row
					}).then(function(response){
						console.log(response.data);
						row.sending=false;
						row.finished=true;

						if(Number(row.ordenado_fab)> 0 && row.status_prod == 'pendiente'){
							row.status_prod = 'ordenado fabrica';
						}else{
							if(row.status_prod == 'ordenado fabrica' && Number(row.ordenado_fab) == 0){
								row.status_prod = 'pendiente';
							}
						}
						let ordenado_fab = Number(row.ordenado_fab);
						row.open_units = ordenado_fab - row.total_units_purchased;
						row.percent_units = (ordenado_fab > 0 ? Math.round((row.open_units<0 ?  row.open_units*-1 : row.open_units) / ordenado_fab * 100) :  0 )+'%';
						$timeout(()=>{ 
							row.finished=false;
						},2000);
					});
 
				}


			});
			var trigger_send=false;
			$(".exporting-it").on("scroll",function(){
				var st = $(".exporting-it").scrollTop();
				var div_h = $(".exporting-it").height();
				var max_h = $(".exporting-it > table").height();
				var trigger = max_h - div_h - 200;
				if(st>trigger && !trigger_send){
					trigger_send=true;
					$(".exporting-it").scope().get_data();
				}
			});

			angular.module("app").component("sageFilter",{
				template:`<div class="filtro-col">
										<div class="filtro-col-container">
											
											<div class="filtro-col-wrapper">
												<ul>
													<li ng-repeat="item in $ctrl.list" class="ng-scope">
														<input type="checkbox" value="1" ng-model="item.checked" ng-change="upd()" class="ng-pristine ng-untouched ng-valid ng-empty"> 
														<span >{{item.text}}</span>
													</li>
												</ul>
											</div>
											<div class="filtro-col-btb">
												<button ng-click="apply_filters()">Apply</button>
											</div>
										</div>
									</div>`,
				bindings:{
					list:"="
				},
				controller:function($scope){
					$scope.upd=function(){
						$scope.$emit('update_filters');
					}
					$scope.apply_filters=function(){
						$scope.$emit('apply_filters');
					}
				}
			}
			);
			angular.module("app").component("sortDir",{
				template:`<span class="ico-up" ng-show="current_param==$ctrl.col && current_sort=='asc'"></span>
				<span class="ico-down"  ng-show="current_param==$ctrl.col && current_sort=='desc'"></span> <span class="label-sort" ng-click="upd_sort()">{{$ctrl.text}}</span>`,
				bindings:{
					col:"@",
					text:"@"
				},
				controller:function($scope){
					var $ctrl=this;
					$scope.current_sort="";
					$scope.current_param="asc";
					$scope.$on("update_sort",function(event,data){
						$scope.current_sort=data.dir;
						$scope.current_param = data.param;
						
					});

					$scope.upd_sort=function(){

						if($ctrl.col!=$scope.current_param){
							$scope.current_param=$ctrl.col;
							$scope.current_sort="asc";
						}else if($ctrl.col==$scope.current_param && $scope.current_sort=="asc"){
							$scope.current_sort="desc";
						}else if($ctrl.col==$scope.current_param && $scope.current_sort=="desc"){
							$scope.current_param="";
							$scope.current_sort="asc";
						}
						$scope.$emit("update_sort_parent", {dir:$scope.current_sort,param:$scope.current_param });
					}
				}
			});
			
		</script>

	

	
</body>

</html>

<?php 
} else {
    header('location: https://shop2.fexpro.com/sagelogin/ecommerce/alpha/pages-login.php');
    exit;
}
?>