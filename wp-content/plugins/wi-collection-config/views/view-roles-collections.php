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
	ul{
		padding-left:0px;
	}
</style>
<div ng-app="admin_app" ng-controller="ctrl" class="admin-area">
	<div class="loading" ng-show="loading"></div>
	<form action="" ng-submit="submit($event)">
	<div class="row">
		<div class="col-12">
			<h1 class="wp-heading-inline">Roles collections</h1>
		</div>
		<div class="col-6 col-lg-4">
			<div class="form-group">
				<label for="">Select role</label>
				<select name="rol" id="" class="form-control"
				ng-options="item.key as item.name for item in roles track by item.key"
				ng-model="role"
				ng-change="change_role()">
					<option value="">-Select-</option>
				</select>
			</div>
		</div>
		<div class="col-6 col-lg-8 text-end">
			<div class="mb-3 text-start" ng-show="show_msj">
				<div class="alert alert-success" role="alert">
					Data saved successfully
				</div>
			</div>
			<button class="btn btn-primary">Save</button>
			
		</div>
	</div>
	<div ng-show="role!=null">
		<div class="row">
			<div class="col-12 mt-3">
				<input type="checkbox" ng-click="select_all($event)" > Select all
				<span style="display:inline-block; margin-left:20px"><input type="checkbox" ng-click="show_only_presale($event)" > Show only presale</span>
			</div>
		</div>
		<div class="row">
			<div class="col-3" ng-repeat="brand in config">
				<div class="card">
					<div class="card-header">{{brand.name}}</div>
					<div class="card-body">
						<div ng-repeat="dep in brand.departments">
							<label for=""><b>{{dep.name}}</b></label>
							<ul>
								<li ng-repeat="coll in dep.collections">
									<input type="checkbox" ng-true-value="'{{coll.id}}'" 
									value="{{coll.id}}" ng-checked="selected.includes(coll.id)" id="coll-{{coll.id}}"
									ng-click="change($event)" class="check-collection"> {{coll.name}}
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.3/angular.min.js"></script>
<script>
	var config=<?=json_encode($config);?>;
	var roles=<?=json_encode($roles);?>;
	var roles_config = <?=json_encode($roles_config);?>;
	var base_url = "<?=get_site_url();?>";
</script>
<script>
	angular.module("admin_app",[])
	.controller("ctrl",function($scope,$http,$timeout){
		$scope.model={};

		$scope.roles = roles;
		$scope.role = null;
		$scope.roles_config = roles_config;
		$scope.config = config;
		
		$scope.selected=[];

		$scope.change_role=()=>{
			if($scope.roles_config[$scope.role]){
				$scope.selected=$scope.roles_config[$scope.role];
			}else{
				$scope.selected = [];
			}
		}

		$scope.change=function($event){
			if($event.target.checked){
				if(!$scope.selected.includes($event.target.value)){
					$scope.selected.push($event.target.value);
				}
			}else{
				$scope.selected=$scope.selected.filter(x=>x!=$event.target.value);
			}

			console.log($scope.selected)
		}; 
		 $scope.submit=function($event){
			$event.preventDefault();
			$scope.show_msj=false;
			$scope.loading=true;
			$scope.dataPost={
				role:$scope.role,
				config:$scope.selected
			}
			$http({
				url: base_url +"/wp-admin/admin-ajax.php?action=collconf_role_save",
				method:"POST",
				data:$scope.dataPost
			}).then((response)=>{
			 
				$scope.show_msj=true;
				$scope.loading=false;
				$timeout(()=>{$scope.show_msj=false;},5000);
				$scope.roles_config = response.data.roles_config;
			});
			return false;
		};
 
		$scope.select_all=function(e){
			
			//if(e.target.checked){
				//jQuery(".check-collection").prop("checked",true);
				jQuery(".check-collection").each(function(index,item){
					jQuery(this).trigger("click",e);
					//angular.element(document.getElementById(jQuery(this).attr("id"))).triggerHandler('ng-change');
				})
				
				
			//}
			
		}
		$scope.show_only_presale=($event)=>{
			let checked = $event.target.checked;
			if(checked){
				let _config = angular.copy(config);
				/* $scope.config = _config.map((brand)=>{
				brand.departments = brand.departments.map((dep)=>{
					dep.collections = dep.collections.filter(x.show_presale=='1');
					return dep;
				});
				return brand;
				}) */

				for(i in _config){
					for(j in _config[i].departments){
						_config[i].departments[j].collections = _config[i].departments[j].collections.filter(x=>x.show_presale=='1');
					}
				}
				$scope.config = _config;
			}else{
				$scope.config = angular.copy(config);
			}
			
		}
	 

	});
</script>