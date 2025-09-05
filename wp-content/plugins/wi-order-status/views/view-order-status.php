<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<style>
	.card{
		padding:0px;
		max-width:100%;
	}
	.form-control{
		max-width:100% !important;
	}
	.admin-area{
		position: relative;
		min-height:100%;
		padding:30px 0px;
	}
	.loading{
		position: absolute;
		width: 100%;
		height:100%;
		top:0px;
		left:0px;
		background:rgba(255,255,255,0.6);
		z-index: 9;
		
	}
 

	.loading:after {
		content: " ";
		display: block;
		width: 50px;
    	height: 50px;
		
		border-radius: 50%;
		border: 6px solid #000;
		border-color: #000 transparent #000 transparent;
		animation: loading-spinner 1.2s linear infinite;
		position: absolute;
		top:50%;
		left:50%;
		margin-left:-13px;
		margin-top:-13px;
	}
	.ico-default{
		font-size:24px;
		cursor: pointer;
	}

	@keyframes loading-spinner {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}

</style>
<div ng-app="admin_app" ng-controller="ctrl" class="admin-area">
	<div class="loading" ng-show="loading"></div>
<div class="row">
	<div class="col-12">
		<h1 class="wp-heading-inline">Order status config</h1>
	</div>
	<div class="col-5">
		<div class="card">
			<div class="card-header">Order Status</div>
			<div class="card-body">
				<form action="" ng-submit="submit($event)">
					<div class="mb-3">
						<label for="">Name</label>
						<input type="text" class="form-control" ng-model="editItem.name" />
					</div>
					<div class="mb-3">
						<label for="">Code</label>
						<input type="text" class="form-control" ng-model="editItem.code" ng-change="valid_code()" />
					</div>
					 
					<div class="mb-3" ng-show="show_msj">
						<div class="alert alert-success" role="alert">
							Data saved successfully
						</div>
					</div>
					<button class="btn btn-primary">Save</button>
					<button type="button" class="btn btn-default" ng-click="cancel()">Cancel</button>
				</form>
			</div>
		</div>
		<div class="card">
			<div class="card-header">Order status for Global Brand</div>
			<div class="card-body">
				<form action="" ng-submit="submit_current_presale($event)">
					<div class="mb-3" ng-repeat="gb in global_brands">
						<label for="">{{gb.name}}</label>
						<select name="" id="" class="form-control" ng-model="gb.order_status"
						ng-options="item.code as item.name for item in order_status"
						>
							<option value="">-Select-</option>
						</select>
						
					</div>
					
					 
					<div class="mb-3" ng-show="show_msj2">
						<div class="alert alert-success" role="alert">
							Data saved successfully
						</div>
					</div>
					<button class="btn btn-primary">Save</button>
				
				</form>
			</div>
		</div>
	</div>
	<div class="col-7">
		<div class="card">
			<div class="card-header">
				Ordes status created
			</div>
			<div class="card-body">
				<table class="table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Code</th>
							<th  style="text-align:center">Default</th>
					
							<!-- <th style="width:100px"></th> -->
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="item in order_status"> 
							<td>{{item.name}}</td>
							<td>{{item.code}}</td>
							<td style="text-align:center">
								<span class="ico-default" ng-show="item.default!=1" ng-click="confirm_default($index)">&#9734;</span>
								<span class="ico-default" ng-show="item.default==1">&#9733;</span>
							</td>
						
							<!-- <td>
								<a href="javascrip:void(0)" ng-click="edit(item,$index)">edit</a>
								|
								<a href="javascrip:void(0)" ng-click="delete(item)">delete</a>
							</td> -->
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>
<script>
	var order_status = <?=json_encode($status_list)?>;
	var base_url = "<?=get_site_url();?>";
	var global_brands = <?=json_encode($global_brands)?>;
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.3/angular.min.js"></script>
<script>
	angular.module("admin_app",[])
	.controller("ctrl",function($scope,$http,$timeout){
		$scope.model={};
		$scope.order_status = order_status;
		$scope.global_brands = global_brands;

		$scope.submit = ($event)=>{
			
			$event.preventDefault();
			
			let tmp = {...$scope.editItem}
			$scope.order_status.push(tmp);
			
			$scope.save();
			return false;
		}
		$scope.save = ()=>{
			$scope.show_msj=false;
			$scope.loading=true;
			$http({
				url: base_url +"/wp-admin/admin-ajax.php?action=wi_order_status_save",
				method:"POST",
				data:$scope.order_status
			}).then((response)=>{
				
				$scope.order_status = response.data.data;
				
				$scope.show_msj=true;
				$scope.loading=false;
				$timeout(()=>{$scope.show_msj=false;},5000);

				$scope.editItem = {};
				
			});
		}
		$scope.confirm_default =(index)=>{
			if(confirm("Set default this order status?")){
				let list = [...$scope.order_status];
				list=list.map((x)=>{
					x.default = null;
					return x;
					});
				list[index].default = 1;
				$scope.order_status = list;
				$scope.save();
			}
		}
		$scope.valid_code = ()=>{
			let str = $scope.editItem.code ?? '';
			str = str.toLowerCase().replace(/[^a-z0-9_-]/g,'');
			$scope.editItem.code = str.trim();
		}
		
		$scope.submit_current_presale = (e)=>{
			e.preventDefault();

			$scope.show_msj2=false;

			let _data = $scope.global_brands.map(x=>{
				let item ={} ;
				item.term_id = x.term_id;
				item.order_status = x.order_status;
				item.global_brand = x.slug;
				return item;

			});
			console.log(_data);
			$http({
				url: base_url +"/wp-admin/admin-ajax.php?action=wi_order_status_global_brand_save",
				method:"POST",
				data:_data
			}).then((response)=>{
				
				
				$scope.show_msj2=true;
				$timeout(()=>{$scope.show_msj2=false;},5000);

				
			});
		}
		 
	});
</script>