<div class="container-filter-brand-collection" ng-controller="ctrl">
	<div class="group" ng-repeat="grupo in grupos">
		<aside class="widget" ng-show="$index>0">
			<div class="text-right" >
				<a href="" class="add-another-group" ng-click="remove($index)">Remove -</a>
			</div>
		</aside>
		<aside class="widget">
			<h3 class="widget-title">Brands</h3>
			<div class="wi-static-filter-element">
				<div>
				<select name="" id="" ng-options="item.value as item.label for item in model.brands" ng-model="grupo.brand" ng-change="update_url()">
					<option value="">All</option>
				</select>
				</div>
			</div>
		</aside>
		<aside class="widget" ng-show2="is_presale">
			<h3 class="widget-title">Collection</h3>
			
			<div class="wi-static-filter-element">
				<util-select list="model.collections_list" ng-model="grupo.collection" filter="grupo.brand" default-text="All" callback="update_url"></util-select>
			</div>
			 
		</aside>
		<aside class="widget" ng-show="is_presale">
			<h3 class="widget-title">Teams</h3>
			<div class="wi-static-filter-element">
				<util-select list="model.teams_list" ng-model="grupo.team" filter="grupo.brand" default-text="All" callback="update_url"></util-select>
			</div>
		</aside>
	</div>
	<aside class="widget" ng-show="is_presale">
	<a href="" class="add-another-group" ng-click="add_group()">Add another brand +</a>
	</aside>
	
</div>
<script>
	var main_cat=<?=$main_category?>;
</script>
<script>
jQuery(document).ready(function($){
	angular.module("app_filter_brand_collection",["util_components"])
	.controller("ctrl",function($scope){
		$scope.grupos=[{}];

		$scope.model = [];
		$scope.model.brands = <?=json_encode($data["brands"]);?>;
		$scope.model.collections = <?=json_encode($data["collections"]);?>;
		$scope.model.teams = <?=json_encode($data["teams"]);?>;

		$scope.is_presale = main_cat!=3259;
		
		$scope.model.collections_list=$scope.model.collections.map(x=>{return {text:x.name,value:x.slug,group:x.brand}});
		//console.log($scope.model.collections_list);
		$scope.model.teams_list=$scope.model.teams.map(x=>{return {text:x.label,value:x.value,group:x.brand}});

		$scope.final_url ="<?=$final_url?>";
		//console.log($scope.final_url);

		$scope.current_brands = <?=json_encode($current_element_brand)?>;
		$scope.current_collect = <?=json_encode($current_element_collect)?>;
		$scope.current_team = <?=json_encode($current_element_team)?>;

		var current_brands_ini = <?=json_encode($current_element_brand)?>;
		var current_collect_ini = <?=json_encode($current_element_collect)?>;
		var current_team_ini = <?=json_encode($current_element_team)?>;
		
		if($scope.current_brands.length>0){
			$scope.grupos=[];
			for(let i in $scope.current_brands){
				$scope.grupos.push({brand:$scope.current_brands[i][0]})
			}
			for(let i in $scope.current_collect){
				if($scope.grupos[i]){
					$scope.grupos[i].collection=$scope.current_collect[i];
				}
			}
			for(let i in $scope.current_team){
				if($scope.grupos[i]){
					$scope.grupos[i].team=$scope.current_team[i];
				}
			}
		}
		

		//$scope.grupos=[{collection:['basic']}];
		//console.log($scope.grupos);

		$scope.add_group=function(){
			$scope.grupos.push({});
		};
		$scope.remove=function($index){
			$scope.grupos.splice($index,1);
			$scope.update_url();
		}

		$scope.update_url=function(){
			let diff_brand=false;
			let diff_collect=false;
			let diff_team=false;

			let new_url = angular.copy($scope.final_url);
			let final_brand=$scope.grupos.map(x=>{
				if(x.brand !=undefined){
					return "f_bc_brand[]="+x.brand;
				}
				return "";
			}).filter(x=>x!="");
			diff_brand=final_brand.length!=current_brands_ini.length;

			final_brand=final_brand.join("&");
			if(final_brand!=""){
				new_url+= (new_url.substr(-1)!="?" ? "&":"")+final_brand;
			}
			

			let final_collect=$scope.grupos.map((x,index)=>{
				if(x.collection !=undefined){
					return "f_bc_collection["+index+"]="+x.collection;
				}
				return "";
			}).filter(x=>x!="");
			console.log(final_collect,current_collect_ini);
			diff_collect=final_collect.length!=current_collect_ini.length;

			final_collect=final_collect.join("&");
			if(final_collect!=""){
				new_url+=(new_url.substr(-1)!="?" ? "&":"")+final_collect;
			}

			let final_team=$scope.grupos.map((x,index)=>{
				if(x.team !=undefined){
					return "f_bc_team["+index+"]="+x.team;
				}
				return "";
			}).filter(x=>x!="");

			diff_team=final_team.length!=current_team_ini.length;

			final_team=final_team.join("&");
			if(final_team!=""){
				new_url+=(new_url.substr(-1)!="?" ? "&":"")+final_team;
			}
			

			
			if(location.href!=new_url){
			//if(diff_brand || diff_collect || diff_team){
				location.href=new_url;
				//console.log(new_url);
			}else{

			}
			
		};
		
	});
	angular.bootstrap(angular.element(".container-filter-brand-collection"), ['app_filter_brand_collection']);
});

</script>