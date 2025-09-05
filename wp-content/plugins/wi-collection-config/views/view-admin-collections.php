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
		<h1 class="wp-heading-inline">Collections setting</h1>
	</div>
	<div class="col-4">
		<div class="card">
			<div class="card-header">Collection</div>
			<div class="card-body">
				<form action="" ng-submit="submit($event)">
					<div class="mb-3">
						<label for="">Division</label>
						<select name="" id="" class="form-control" ng-model="dataPost.division"
						ng-options="item.slug as item.name for item in model.divisions" required>
							<option value="">-Select-</option>
						</select>
					</div>
					<div class="mb-3">
						<label for="">Global brand</label>
						<select name="" id="" class="form-control" ng-model="dataPost.global_brand"
						ng-options="item.slug as item.name for item in model.global_brands" required>
							<option value="">-Select-</option>
						</select>
					</div>
					<div class="mb-3">
						<label for="">Brand</label>
						<select name="" id="" class="form-control" ng-model="dataPost.brand"
						ng-options="item.slug as item.name for item in model.brands" required>
							<option value="">-Select-</option>
						</select>
					</div>
					<div class="mb-3">
						<label for="">Department</label>
						<select name="" id="" class="form-control" ng-model="dataPost.department"
						ng-options="item.slug as item.name for item in model.departaments" required>
							<option value="">-Select-</option>
						</select>
					</div>
					<div class="mb-3">
						<label for="">Collection</label>
						<select name="" id="" class="form-control" ng-model="dataPost.collection"
						ng-options="item.slug as item.name for item in model.collections" required>
							<option value="">-Select-</option>
						</select>
					</div>
					<div class="mb-3">
						<input type="checkbox" ng-model="dataPost.show_presale" ng-true-value="'1'" ng-false-value="'0'" />
						<label for="">Show in Presale</label>
					</div>
					<div class="mb-3">
						<label for="">Link PDF</label>
						<textarea name="" id="" rows="3" class="form-control" ng-model="dataPost.link_pdf"></textarea>
					</div>
					<div class="mb-3">
						<label for="">Link Catalog</label>
						<textarea name="" id="" rows="3" class="form-control" ng-model="dataPost.link_catalog"></textarea>
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
	</div>
	<div class="col-8">
		<div class="card">
			<div class="card-header">
				
				Global brand <select name="" id="" ng-model="filter.global_brand"
						ng-options="item.slug as item.name for item in model.global_brands" ng-change="apply_filter()">
							<option value="">-Select-</option>
						</select>
				Brand <select name="" id="" ng-model="filter.brand"
						ng-options="item.slug as item.name for item in model.brands" ng-change="apply_filter()">
							<option value="">-Select-</option>
						</select>
				Department <select name="" id="" ng-model="filter.department"
						ng-options="item.slug as item.name for item in model.departaments" ng-change="apply_filter()">
							<option value="">-Select-</option>
						</select>
			</div>
			<div class="card-body">
				<table class="table">
					<thead>
						<tr>
							<th>Division</th>
							<th>Global Brand</th>
							<th>Brand</th>
							<th>Department</th>
							<th>Collection</th>
							<th style="width:100px"></th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="item in model.config_filter"> 
							<td>{{item.division_str}}</td>
							<td>{{item.global_brand_str}}</td>
							<td>{{item.brand_str}}</td>
							<td>{{item.department_str}}</td>
							<td>{{item.collection_str}} {{item.show_presale=='1'?'(presale)':''}}</td>
							<td>
								<a href="javascrip:void(0)" ng-click="edit(item,$index)">edit</a>
								|
								<a href="javascrip:void(0)" ng-click="delete(item)">delete</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>
<script>
	var brands = <?=json_encode($brands);?>;
	var collections = <?=json_encode($collections);?>;
	var departaments = <?=json_encode($departments);?>;
	var divisions = <?=json_encode($divisions);?>;
	var base_url = "<?=get_site_url();?>";
	var global_brands = <?=json_encode($global_brands);?>;
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.3/angular.min.js"></script>
<script>
	angular.module("admin_app",[])
	.controller("ctrl",function($scope,$http,$timeout){
		$scope.model={};
		$scope.model.departaments = departaments;
		$scope.model.divisions = divisions;
		$scope.model.brands= brands;
		$scope.model.global_brands = global_brands;
 
		$scope.model.collections= angular.copy(collections);

		$scope.model.configuraciones=[];
		$scope.model.config_filter=[];

		$scope.dataPost={};
		$scope.filter={};
		$scope.show_msj=false;
		$scope.loading=false;

		$timeout(()=>{
			$scope.get_data();
		});
		$scope.get_data=function(){
			$scope.loading=true;
			$http({
				url:base_url +"/wp-admin/admin-ajax.php?action=collconf_get",
				method:"GET"
			}).then(function(response){
				$scope.model.configuraciones=response.data??[];
				$scope.set_detalle();
				$scope.apply_filter();
				$scope.loading=false;
			})
		};

		$scope.submit=function($event){
			$event.preventDefault();
			$scope.show_msj=false;
			$scope.loading=true;
			$http({
				url: base_url +"/wp-admin/admin-ajax.php?action=collconf_save",
				method:"POST",
				data:$scope.dataPost
			}).then((response)=>{
				$scope.model.configuraciones=response.data??[];
				$scope.set_detalle();
				$scope.apply_filter();
				$scope.show_msj=true;
				$scope.loading=false;
				$timeout(()=>{$scope.show_msj=false;},5000);
				$scope.dataPost={};
			});
			return false;
		};
		$scope.set_detalle=function(){
			$scope.model.configuraciones.map(x=>{
				if(x){
					x.brand_str = $scope.model.brands.filter(y=>y.slug==x.brand).map(y=>y.name)[0];
					x.department_str = $scope.model.departaments.filter(y=>y.slug==x.department).map(y=>y.name)[0];
					x.collection_str = collections.filter(y=>y.slug==x.collection).map(y=>y.name)[0];
					x.division_str = divisions.filter(y=>y.slug==x.division).map(y=>y.name)[0];
					x.global_brand_str = $scope.model.global_brands.filter(y=>y.slug==x.global_brand).map(y=>y.name)[0];
					return x;
				}
				
			});
		};
		$scope.edit=function(item,index){
			$scope.dataPost=angular.copy(item);
			$scope.dataPost.index=index;
		}
		$scope.delete=function(item){
			var _confirm = confirm("Remove this item?");
			if(!_confirm)
				return;

			$scope.loading=true;
			$http({
				url: base_url +"/wp-admin/admin-ajax.php?action=collconf_delete",
				method:"POST",
				data:{id:item.id}
			}).then((response)=>{
				$scope.model.configuraciones=response.data;
				$scope.set_detalle();
				$scope.apply_filter();
				$scope.loading=false;
			});
		}
		$scope.cancel=function(){
			$scope.dataPost={};
		};
		$scope.apply_filter=function(){
			$scope.model.config_filter=angular.copy($scope.model.configuraciones);
			//console.log($scope.filter.brand)
			if($scope.filter.global_brand){
				$scope.model.config_filter=$scope.model.config_filter.filter(x=>x.global_brand==$scope.filter.global_brand);
			}
			if($scope.filter.brand){
				$scope.model.config_filter=$scope.model.config_filter.filter(x=>x.brand==$scope.filter.brand);
			}
			if($scope.filter.department){
				$scope.model.config_filter=$scope.model.config_filter.filter(x=>x.department==$scope.filter.department);
			}  
			
		}
	});
</script>