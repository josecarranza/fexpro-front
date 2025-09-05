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
			<h1 class="wp-heading-inline">User collections</h1>
		</div>
		<div class="col-6 col-lg-4">
			<div class="card">
				<div class="card-header">User information</div>
				<div class="card-body">
					<div class="mb-3">
						<label for="">Name: <b><?=$user_data->data->display_name?></b></label>
					</div>
					<div class="mb-3">
						<label for="">Username: <b><?=$user_data->data->user_nicename?></b></label>
					</div>
					<div class="mb-3">
						<label for="">Email: <b><?=$user_data->data->user_email?></b></label>
					</div>
				</div>
			</div>
		</div>
		<div class="col-6 col-8 text-end">
			<div class="mb-3 text-start" ng-show="show_msj">
				<div class="alert alert-success" role="alert">
					Data saved successfully
				</div>
			</div>
			<button class="btn btn-primary">Save</button>
			
		</div>
	</div>
	<div class="row">
		<div class="col-12 mt-3">
			<input type="checkbox" ng-click="select_all($event)" > Select all
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
	</form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.3/angular.min.js"></script>
<script>
	var config=<?=json_encode($config);?>;
	var user_config = <?=json_encode($user_config);?>;
	var base_url = "<?=get_site_url();?>";
</script>
<script>
	angular.module("admin_app",[])
	.controller("ctrl",function($scope,$http,$timeout){
		$scope.model={};

		$scope.config = config;
		
		
		$scope.selected=user_config;

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
				user:<?=$user_id;?>,
				config:$scope.selected
			}
			$http({
				url: base_url +"/wp-admin/admin-ajax.php?action=collconf_user_save",
				method:"POST",
				data:$scope.dataPost
			}).then((response)=>{
			 
				$scope.show_msj=true;
				$scope.loading=false;
				$timeout(()=>{$scope.show_msj=false;},5000);
				 
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


	});
</script>