<?php 
require_once 'include/common.php';
get_currentuserinfo();
global $wpdb;
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
	    <title>Fexpro Sage - Season Products</title>
	    <!-- chartist CSS -->
	    <link href="<?php echo SAGE_SITEURL; ?>/dist/css/pages/ecommerce.css" rel="stylesheet">
	    <!-- Custom CSS -->
	    <link href="<?php echo SAGE_SITEURL; ?>/dist/css/style.min.css?v=2" rel="stylesheet">

	    <link href="<?php echo SAGE_SITEURL; ?>/alpha/include/css/custom-fexpro.css" rel="stylesheet">

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
						<h4 class="text-themecolor">Season Products</h4>
	                    </div>
	                    <div class="col-md-7 align-self-center text-right">
	                        <div class="d-flex justify-content-end align-items-center">
	                            <ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                <li class="breadcrumb-item active"><?php echo "Season products"; ?></li>
	                            </ol>
	                        </div>
	                    </div>
	                </div>
	                <!-- ============================================================== -->
	                <!-- End Bread crumb and right sidebar toggle -->
	                <!-- ============================================================== -->
						
	                <div class="row">
                        <div class="col-6">
							<a class="btn-dd" href="https://shop2.fexpro.com/wp-admin/admin-ajax.php?action=season_products_report&export=1&{{url_params}}" target="_blank">Export All to XLSX</a>
		                	<label for="" style="margin-left:30px">Season: </label>
							<select name="" id="" class="form-control" style="width:200px" ng-model="season"
							ng-options="item.term_id as item.name for item in model.seasons track by item.term_id">
								<option value="">-Select-</option>
							</select>
							<buttton class="btn btn-secondary" ng-click="apply_filter()">Apply</buttton>
					   </div>
                        <span class='no-v'></span>
						<div class="exporting-it">
						<table class="table table-bordered" id="demo_table">
							<thead>
									<tr>
									<th style="vertical-align : middle;text-align:center;">Product image</th>
									<th  style="vertical-align : middle;text-align:center;">Style name</th>
									<th  style="vertical-align : middle;text-align:center;">Style sku</th>
									<th  style="vertical-align : middle;text-align:center;" > Division
										 
									</th>
									<th  style="vertical-align : middle;text-align:center;">
										Brand
									</th>
									<th  style="vertical-align : middle;text-align:center;">Gender</th>
									<th  style="vertical-align : middle;text-align:center;">Category</th>
									<th  style="vertical-align : middle;text-align:center;">Group</th>
									<th  style="vertical-align : middle;text-align:center;">Product</th>
									<th  style="vertical-align : middle;text-align:center;">Season</th>
									<th  style="vertical-align : middle;text-align:center;">Collection</th>
									<th  style="vertical-align : middle;text-align:center;">Date</th>
									<th  style="vertical-align : middle;text-align:center;">Team</th>
									<th style="vertical-align : middle;text-align:center;">Player</th>
									<th style="vertical-align : middle;text-align:center;min-width: 215px;">Composition</th>
									<th style="vertical-align : middle;text-align:center;min-width: 215px;">Product logo</th>

									<th style="vertical-align : middle;text-align:center;">Selling Price</th>
									<th style="vertical-align : middle;text-align:center;">Sold</th>
									</tr>
								</thead>
								<tbody id="myTable">
									<tr ng-repeat="row in report" class="odd">
									<td ng-repeat="(key, value) in row" ng-if="key=='image'" style="white-space:nowrap"><img src="{{value}}" height="100" ><img src="{{row.image2}}" height="100" ng-if="row.image2!=''" style="margin-right:-10px" ></td>
										<td ng-repeat="(key, value) in row" ng-if="!['id','image','image2'].includes(key)"><span ng-if="key!='price' && key!='subtotal' ">{{value}}</span><span ng-if="key=='price' || key=='subtotal' ">{{value | currency:'$'}}</span></td>
										
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
									<th  style="vertical-align : middle;text-align:center;">Brand</th>
									<th  style="vertical-align : middle;text-align:center;">Gender</th>
									<th  style="vertical-align : middle;text-align:center;">Category</th>
									<th  style="vertical-align : middle;text-align:center;">Group</th>
									<th  style="vertical-align : middle;text-align:center;">Product</th>
									<th  style="vertical-align : middle;text-align:center;">Season</th>
									<th  style="vertical-align : middle;text-align:center;">Collection</th>
									<th  style="vertical-align : middle;text-align:center;">Date</th>
									<th  style="vertical-align : middle;text-align:center;">Team</th>
									<th style="vertical-align : middle;text-align:center;">Player</th>
									<th style="vertical-align : middle;text-align:center;min-width: 215px;">Composition</th>
									<th style="vertical-align : middle;text-align:center;min-width: 215px;">Product logo</th>
								
									<th style="vertical-align : middle;text-align:center;">Selling Price</th>
									<th style="vertical-align : middle;text-align:center;">Sold</th>
								</tfoot>
							
							</table>
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
		<script>
			angular.module("app",[]).controller("ctrl",function($scope,$http){
				$scope.model={};
				$scope.model.sellers=[];
				$scope.report=[];
				$scope.filters={};
				$scope.filters_active={};
				$scope.sort={dir:'asc',param:""};
				$scope.season="";
				$scope.pag=1;
				$scope.url_params="";
				$scope.model.seasons=[];

				$scope.get_seasons=function(){
					$http({
						url:"https://shop2.fexpro.com/wp-admin/admin-ajax.php?action=get_ajax_seasons_list",
						method:"POST"
					}).then(function(response){
						$scope.model.seasons=response.data.data;
					});
				}
				$scope.get_seasons();

				$scope.get_data=function(){
					
					
					if($scope.pag==1){
						$scope.report=[];
					}
					if($scope.season==""){
						$scope.report=[];
						return;
					}
					var _params={};
				 
					_params={..._params,season:$scope.season,page:$scope.pag};

					$scope.url_params=$.param( _params );
					$http({
						url:"https://shop2.fexpro.com/wp-admin/admin-ajax.php?action=season_products_report",
						method:"POST",
						data:_params
					}).then(function(response){
						//$scope.model.sellers=response.data.sellers;
						if($scope.pag==1){
							$scope.report=response.data.data;
						}else{
							$scope.report=$scope.report.concat(response.data.data);
							
						}
						$scope.pag++;
						trigger_send=false;
					});
				};
				$scope.get_data();

				$scope.apply_filter=function(){
					$scope.pag=1;
					$scope.get_data();
				}
 
				$scope.$on("season",function(newval){
					 $scope.pag=1;
					 //$scope.get_data();
				});
				 
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