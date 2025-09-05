<?php 
 
defined( 'ABSPATH' ) || exit;

prefix_update_existing_cart_item_meta();
$return_array1 = array();
global $wpdb;
do_action( 'woocommerce_before_cart' );

?>
<?php do_action( 'woocommerce_before_cart_table' ); ?>
<?php do_action( 'woocommerce_before_cart_contents' ); ?>
<?php do_action( 'woocommerce_cart_contents' ); ?>
<div ng-app="app_cart" ng-controller="ctrl">
	<div class="row">
		<div class="col-8">
			<h2>Payment detail <a href="javascript:void(0)"  class="btn-export disabled">Export order <span class="ico-export"></span></a></h2>
		</div>
		<div class="col-4 text-right">
			<a href="javascript:void(0)"  class="btn-export" ng-click="modal_import()">Import Excel <span class="ico-import"></span></a>
		</div>
	</div>
	<div class="row">
		<div class="col-9">
	 
			<div class="accordions">
				<div class="item-accordion ">
					<div class="item-accordion-header">
						Orden summary
					</div>
					<div class="item-accordion-body">
						
					
					</div>
				</div>
				<div class="item-accordion">
					<div class="item-accordion-header">
						Billing details
					</div>
					<div class="item-accordion-body">
						 
					</div>
				</div>
			</div>
		</div>
		<div class="col-3">
		<div class="cart-collaterals">
            <?php
                /**
                 * Cart collaterals hook.
                 *
                 * @hooked woocommerce_cross_sell_display
                 * @hooked woocommerce_cart_totals - 10
                 */
				include("cart-totals.php");
               // echo do_action( 'woocommerce_cart_collaterals' );
            ?>
			<div class="text-center">
				<button class="btn-place-order disabled" disabled >Place order</button>
			</div>
        </div>
		</div>
	</div>
	<div class="modal fade modal-js modal-upload" tabindex="-1" role="dialog">
			
		<div class="modal-dialog" role="document">
		  <div class="modal-content">
		  <div class="btn-modal-close"  data-dismiss="modal"></div>
		  
			<div class="modal-body">
				<div class="area-drop" id="drop-area" ng-show="!uploading">
					<div class="upload-origin-selector">
						<label for="">Select the products origin</label>
						<div class="upload-origin-selector-items">
							<span>
								<input type="radio" name="inventory_origin" value="available" ng-model="inventory_origin"> Inventory inmediate
							</span>
							<span>
								<input type="radio" name="inventory_origin" value="available_future" ng-model="inventory_origin"> Inventory future
							</span>
							<span>
								<input type="radio" name="inventory_origin" value="presale" ng-model="inventory_origin" checked> Presale
							</span>
						</div>
					</div>
					<span class="img-upload"></span>

					<div class="info-box-upload">
						<label for="">Important</label>
						<p>Remember to keep the file the same as the one you downloaded to avoid any erros when importing it.</p>
					</div>
					<label for="">Drag and drop your files</label>
					
					<label for="">Or</label>
				</div>
				<div class="upload-proccess" ng-show="uploading">
					<span class="img-upload sm"></span>
					<div class="bar-upload-container">
						<div class="row">
							<div class="col text-left"><label for="">Linesheet.xlsx</label></div>
							<div class="col text-right">
							<span class="ico-loading"></span>
							</div>
						</div>
						
						
						<div class="progress-bar-content">
							<div class="progress-bar" style="width:{{percent}}%"></div>
						</div>
						<div class="row">
							<div class="col text-left">
								<span class="percent">{{percent}}%</span>
							</div>
							<div class="col text-right">
								<span class="processing-text">{{text_processing}}</span>
							</div>
						</div>
					</div>
					<div class="error-box-upload" ng-show="error_upload">
						<label for="">Sorry, we have a problem</label>
						<p>There is an error in the file you uploaded, please contact your seller to help you fix the problem.</p>
					</div>
				</div>
				<button class="btn-place-order" ng-disabled="uploading" ng-click="pick_files()">Add your files</button>
			</div>
			 
		  </div>
		</div>
	  </div>
	  </div>
	  <input type="file" id="input-upload" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"  style="display:none"/>
</div>
<?php do_action( 'woocommerce_after_cart' ); ?>
<script src="<?=get_site_url()?>/wp-content/themes/porto-child/ajax-upload.js"></script>
<script>
	let dropArea = document.getElementById('drop-area');
	['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
	dropArea.addEventListener(eventName, preventDefaults, false)
	})

	function preventDefaults (e) {
		e.preventDefault()
		//e.stopPropagation()
	}
	['dragenter', 'dragover'].forEach(eventName => {
  	dropArea.addEventListener(eventName, highlight, false)
	});

	['dragleave', 'drop'].forEach(eventName => {
	dropArea.addEventListener(eventName, unhighlight, false)
	});

	function highlight(e) {
	dropArea.classList.add('highlight')
	}

	function unhighlight(e) {
	dropArea.classList.remove('highlight')
	}

	angular.module("app_cart",[]).controller("ctrl",function($scope,$http){
		$scope.model={};
		$scope.uploading=false;
		$scope.inventory_origin ="presale";


		$scope.modal_import=function(){
			$scope.percent = 0;
			$scope.filename="";
			$scope.text_processing="Processing...";
			$scope.uploading=false;
			$("#input-upload").val("");
			$(".modal-upload").modal();
		};

		$scope.text_processing="Processing...";
		$scope.percent = 0;
		$scope.filename="";
		$scope.message={
			show:false,
			text:""
		}
		$scope.error_upload=false;

		$scope.pick_files=function(){
			if($scope.uploading){
				$scope.uploading=false;
			}else{
				$("#input-upload").click();
			}
			
			return;
			
		}

		$("#input-upload").change(function(){
			console.log($("#input-upload").eq(0)[0].files);
			var file = $("#input-upload").eq(0)[0].files[0];
			$scope.error_upload=false;
			if(file){
				$scope.filename=file.name;
				$.ajax_upload({
				url:base_url+"/wp-admin/admin-ajax.php?action=import_order",
				file:file,
				data:{inventory_origin:$scope.inventory_origin},
				dataType:"json",
				start:function(){
					$scope.uploading=true;
					$scope.$apply();
				},
				progress:function(data){
					
					$scope.percent = (data.loaded / data.total) * 100;
					$scope.percent = $scope.percent.toFixed(0) ;
					$scope.$apply();
				},
				finish:function(data){
					//$scope.uploading=false;
					if(data.error==0){
						location.href=base_url+"/cart";
					}else{
						$scope.message={
							show:true,
							text:"Error, try again"
						};
						$scope.error_upload=true;
						$scope.text_processing="File not uploaded";
					}
					$scope.$apply();
					//location.reload();
				}
			});
			}
			

		});
		

		dropArea.addEventListener('drop', (e)=>{
			let dt = e.dataTransfer;
			let files = dt.files;
			$("#input-upload").eq(0)[0].files=files;
			console.log(files);
			$("#input-upload").change();
		}, false)
		

	});
</script>