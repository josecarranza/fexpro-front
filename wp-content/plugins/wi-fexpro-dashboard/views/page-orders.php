<?php include("header.php");?>

<div class="wi-fexpro-dashboard-container" ng-app="app" ng-controller="ctrl">
	<div class="row">
		<div class="col-3">
			<div>
				<h4>Status</h4>
				<ul class="list-checkbox">
				<li ng-repeat="item in model.order_status_list track by $index">
					<input type="checkbox" name="order_status[]" value="{{item.code}}" ng-model="item.selected">
				{{item.name}}
				</li>
				</ul>
			</div>
			<div>
				<h4>Country</h4>
				<ul class="list-checkbox">
					<li ng-repeat="c in model.countries track by $index">
						<input type="checkbox" name="country[]" value="{{c.country}}" ng-model="c.selected">
						{{c.country_str}}
					</li>
					
				</ul>
			 
			</div>
			<span class="msj-error d-block">{{error1}}</span>
			<button class="btn btn-outline-primary" ng-click="searchOrders()" ng-disabled="loading">Apply</button>
			 
			 
		</div>
		
		<div class="col-9">
			<h4>Orders</h4>
			<div style="text-align:right">
				<button class="btn btn-outline-primary" ng-click="exportOrders()" ng-disabled="exportingOrders">Export orders pending to Sage</button>
			</div>
			<table class="table">
				<thead>
					<tr>
						<th>ORDER ID</th>
						<th>Country</th>
						<th>Sum total units</th>
						<th>Company</th>
						<th>Sum subtotal USD$</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="order in orders">
						<td>{{order.ID}}</td>
						<td>{{order.country_str}}</td>
						<td align="right">{{order.total_units}}</td>
						<td>{{order.company}}</td>
						<td align="right">${{order.total_sale | currency:''}}</td>
						<td>
							<!-- <span ng-show="order.exported!='1'">Pending</span> -->
							<!-- <span ng-show="order.exported=='1'">Exported <a target="_blank" href="<?=get_site_url()?>/wp-admin/admin-ajax.php?_fs_blog_admin=true&action=render_sage_xml&order_id={{order.ID}}">Download</a></span> -->
							<span><a target="_blank" href="<?=get_site_url()?>/wp-admin/admin-ajax.php?_fs_blog_admin=true&action=render_sage_xml&order_id={{order.ID}}&download=1">Download</a></span>
							<span class="exporting"  ng-if="order.exporting" >
								<div class="loader"  style="height:30px; zoom:0.5"> <div class="loader-wheel"></div></div>
							</span>
						</td>
					</tr>
					<tr ng-if="orders.length==0">
						<td colspan="6" class="text-center">No data found</td>
					</tr>
				</tbody>
			</table>
			<div class="text-center">
				<div class="loader" ng-if="loading"> <div class="loader-wheel"></div> <div class="loader-text"></div> </div>
			</div>
			 
		</div>
		 
	</div>
 
</div>
<script>
	var base_url = "<?=get_site_url()?>";
</script>
<script>
	angular.module("app",["fex-components"]).controller("ctrl",function($scope,$http,$timeout){
		$scope.model = {};
		$scope.model.order_status_list = [];
		$scope.model.seasons = [];

		$scope.model.countries = [];

		$scope.orders = [];

		 
		$scope.loading=false;
		$scope.finish=false;
 

		$scope.error1='';
		$scope.exportingOrders=false;
		 
		
		$scope.getOrderStatus = ()=>{
			$http({
				method:"GET",
				url:base_url+"/wp-admin/admin-ajax.php?action=widash_getorderstatus",
			}).then((response)=>{
				$scope.model.order_status_list = response.data.order_status;
			})
		};
		$scope.getOrderStatus();
		$scope.getCountries=()=>{
			$http({
				method:"GET",
				url:base_url+"/wp-admin/admin-ajax.php?action=widash_getcountries",
			}).then((response)=>{
				$scope.model.countries = Object.values(response.data.countries);
			})
		}
		$scope.getCountries();

		$scope.searchOrders = ( )=>{
		 
			let status = $scope.model.order_status_list.filter(x=>x.selected).map(x=>x.code);
			console.log($scope.model.countries)
			let countries = $scope.model.countries.filter(x=>x.selected).map(x=>x.country);
		

			$scope.error1="";
			if(!status.length || status.length==0){
				$scope.error1="Select a Order Status";
				return;
			}

			$scope.error1="";
			/* if(!countries.length || countries.length==0){
				$scope.error1="Select a Country";
				return;
			} */

			$scope.loading=true;
			$scope.finish=false;
			let dataPost={
				status,
				countries
			}
			$http({
				method:"POST",
				url:base_url+"/wp-admin/admin-ajax.php?action=widash_get_orders",
				data:dataPost
			}).then((response)=>{
				 
				$scope.loading=false;
				$scope.finish=true;

				 $scope.orders = response.data.orders;

				 
			})
		}

		$scope.exportOrders = async ()=>{
			$scope.exportingOrders=true;
			for(let i=0;i<$scope.orders.length;i++){
				if($scope.orders[i].exported!='1'){
					$scope.orders[i].exporting=true;
					let r = await $scope.sendData($scope.orders[i].ID);
					console.log(r);
					$scope.orders[i].exporting=false;
					$scope.orders[i].exported = r.data.error==0?'1':0;
					$scope.$apply();
				}	
				
			}
			 

			$scope.exportingOrders=false;
			$scope.$apply();
		}
		$scope.sendData=async (orderId)=>{
			let p = new Promise((resolve,reject)=>{
				let dataPost = new FormData();
				dataPost.append('orderid', orderId);
				$http({
					transformRequest: angular.identity,
					method:"POST",
					url:base_url+"/wp-admin/admin-ajax.php?action=export_xml_sage_order",
    				headers: {'Content-Type': undefined},
					data: dataPost
				}).then((response)=>{
					resolve(response);
				})
			});
			return p;
		}
 
	});
</script>