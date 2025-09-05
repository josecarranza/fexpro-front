<?php 
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
		tr.red , td.red {background: #ff00002e !important;}
		tr.success , td.success {background: #00800021;}
		table#demo thead tr.fltrow > td:first-child input, table#demo thead tr.fltrow > th:first-child input, .deliverydate-value, table#demo thead tr.fltrow > th:nth-child(0) input, .cartoon_dimensions-value, .cbms_x_ctn-value, .weight_x_ctn-value, .fabric-value, .comments-add, .pdf-add {	display: none !important;}
		input[type="text"][name="textContent"]:disabled { border: none; font-weight: 900; color: #000; text-align: center; }
        span.EditOrderNumber, span.SaveOrderNumber {background: #000;color: #fff;padding: 5px;border-radius: 5px;cursor: pointer;display: inline-block;position: relative;top: 23px;left: 40px;}
        span.no-v {display: block;width: 100%;font-size: 16px;color: #000;font-weight: 500;}
		span.no-v strong {color: #f00;text-decoration: underline;font-weight: 900;}
        div#demo_table_wrapper.dt-bootstrap {display: flex;}
		div#demo_table_wrapper.dt-bootstrap > .row:last-child {order: -1;width: 100%;}
		div#demo_table_wrapper.dt-bootstrap > .row:last-child > div {flex: 0 0 25%;max-width: 25%;}
		div#demo_table_wrapper table {display: block;overflow-x: scroll;margin: 30px;width: 100% !important;}
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
	                        <h4 class="text-themecolor">View SS22 Factory Order Lists</h4>
	                    </div>
	                    <div class="col-md-7 align-self-center text-right">
	                        <div class="d-flex justify-content-end align-items-center">
	                            <ol class="breadcrumb">
	                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
	                                <li class="breadcrumb-item active">View SS22 Factory Order Lists</li>
	                            </ol>
	                        </div>
	                    </div>
	                </div>
	                <!-- ============================================================== -->
	                <!-- End Bread crumb and right sidebar toggle -->
	                <!-- ============================================================== -->
						
	                <div class="row">
                        <h2 class="dispalyTitle"><?php echo $_GET['name'] ?></h2>
                        
	                	<span id="exportexcel1" onclick="fnExcelReport1();">Export All to XLSX</span>
						<span id="stop-refresh">Exporting is inprogress. Please don't refresh the page.</span>

                        <span class='no-v'></span>

						<table class="table table-bordered" id="demo_table">
						   <thead>
						        <tr>
                                <th style="vertical-align : middle;text-align:center;">Order Number</th>
                                <th style="vertical-align : middle;text-align:center;">Product image</th>
                                <th style="vertical-align : middle;text-align:center;">Item name</th>
                                <th style="vertical-align : middle;text-align:center;">Style sku</th>
                                <th style="vertical-align : middle;text-align:center;">Composition</th>
                                <th style="vertical-align : middle;text-align:center;">Producto logo</th>
                                <th style="vertical-align : middle;text-align:center;">Factory Order</th>
                                <th style="vertical-align : middle;text-align:center;">Factory Name</th>
                                <th style="vertical-align : middle;text-align:center;">Delivery Date</th>
                                <th style="vertical-align : middle;text-align:center;">Cost price</th>
                                <th style="vertical-align : middle;text-align:center;">Total Amount</th>
								<th style="display:none"></th>  
						        </tr>
						    </thead>
							
							<tfoot>
                                <th style="vertical-align : middle;text-align:center;">Order Number</th>
                                <th style="vertical-align : middle;text-align:center;">Product image</th>
                                <th style="vertical-align : middle;text-align:center;">Item name</th>
                                <th style="vertical-align : middle;text-align:center;">Style sku</th>
                                <th style="vertical-align : middle;text-align:center;">Composition</th>
                                <th style="vertical-align : middle;text-align:center;">Producto logo</th>
                                <th style="vertical-align : middle;text-align:center;">Factory Order</th>
                                <th style="vertical-align : middle;text-align:center;">Factory Name</th>
                                <th style="vertical-align : middle;text-align:center;">Delivery Date</th>
                                <th style="vertical-align : middle;text-align:center;">Cost price</th>
                                <th style="vertical-align : middle;text-align:center;">Total Amount</th>
                                <th style="display:none"></th>
							</tfoot>
						
						</table>
	                	
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
                    var factory_name = '<?php echo $_GET['name']; ?>';
					var demo_table = $('#demo_table').dataTable({
						dom: '<"wrapper"flipt>',
						
						columns: [
							{ data: 'order_number',name: 'order_number'},
							{ data: 'product_image',name: 'product_image', render: function (data, type, row) {
								return type === 'export' ? data : '<img src="' + data + '" />';
							} },
							{ data: 'item_name',name: 'item_name'},
							{ data: 'product_sku',name: 'product_sku' },
							{ data: 'composition',name: 'composition' },
							{ data: 'producto_logo',name: 'producto_logo' },
							{ data: 'factory_order',name: 'factory_order' },
							{ data: 'factory_name' ,name: 'factory_name'},
							{ data: 'delivery_date' ,name: 'delivery_date'},
							{ data: 'cost_price' ,name: 'cost_price'},
							{ data: 'total_amount' ,name: 'total_amount', render: function (data, type, row) {
                                var data1 = data.replace('$', '');
								return type === 'export' ?  data1 : data ;
							} },
                            { data: 'other_column' ,name: 'other_column'},
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
							data:{action:"alpha_view_factory_datatables",factory_name:factory_name},
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
                            if($(this).children('td').find('span.no-v1').text() != ''){
								$('span.no-v').html($(this).children('td').find('span.no-v1').text());
								
							}
                            if($(this).children('td').find('.inner-size').text() == ''){
                                $(this).addClass('red');    
                            }
                            

                            // FOr sku print on td
							var c = $(this).children('td').eq(3).text();
                            jQuery(this).children('td').addClass(c);

                            // For adding blank datae only 
                            var blankDate = $(this).children('td').eq(8).text();
                            if(blankDate=='0000-00-00'){
                                $(this).children('td').eq(8).addClass('red');    
                            }

                            // For adding cost blank
                            var blankCost = $(this).children('td').eq(9).text();
                            if(blankCost==''){
                                $(this).children('td').eq(9).addClass('red');    
                            }

                            // For adding total amount
                            var blankAmount = $(this).children('td').eq(10).text();
                            if(blankAmount=='$0.00'){
                                $(this).children('td').eq(10).addClass('red');    
                            }


							
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
				form_data.append('factory_name', factory_name );
				form_data.append('action', 'alpha_export_view_factory_data');
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
    header('location: https://shop.fexpro.com/sagelogin/ecommerce/alpha/pages-login.php');
    exit;
}
?>