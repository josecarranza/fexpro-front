<div class="container-filter-teams" ng-controller="ctrl">
 
	<aside class="widget" ng-show="is_presale">
			<h3 class="widget-title">Teams</h3>
			<div class="wi-static-filter-element">
				<util-select list="model.teams_list" ng-model="teams" filter2="grupo.brand" default-text="All" callback="update_url"></util-select>
			</div>
		</aside>
</div>
<script>
	var main_cat=<?=$main_category?>;
</script>
<script>
jQuery(document).ready(function($){
	angular.module("app_filter_teams",["util_components"])
	.controller("ctrl",function($scope){
		$scope.grupos=[{}];

		$scope.model = [];
		$scope.brand = null;
		$scope.teams = [];

		$scope.model.teams = <?=json_encode($data["teams"]);?>;


		$scope.is_presale = main_cat!=3259;
		
		$scope.model.teams_list=$scope.model.teams.map(x=>{return {text:x.label,value:x.value,group:x.brand}});
	
		$scope.final_url ="<?=$final_url?>";
		//console.log($scope.final_url);

		$scope.current_brands = <?=json_encode($current_element_brand)?>;
		$scope.current_team = <?=json_encode($current_element_team)?>;

		if($scope.current_brands.length>0 && $scope.current_brands[0][0]){
			$scope.brand= $scope.current_brands[0][0];
			 
		}
		if($scope.current_team.length>0 && $scope.current_team[0]){
			$scope.teams =$scope.current_team[0];
			 
		}

	

		var current_brands_ini = <?=json_encode($current_element_brand)?>;
 
		var current_team_ini = <?=json_encode($current_element_team)?>;
	 
		$scope.update_url=function(){
			console.log($scope.teams);
			
			let diff_team=false;


			let new_url = angular.copy($scope.final_url);
			
			let final_brand = $scope.brand!=null?"f_bc_brand[]="+ $scope.brand : "";

			let final_team= $scope.teams.length>0 ? "f_bc_team[0]="+$scope.teams.join(',') : "";

	 
			diff_team=$scope.teams!=$scope.current_team[0];

		 

			if(diff_team){
				new_url+= (new_url.substr(-1)!="?" ? "&":"")+final_team;
			}
			
			
			if(location.href!=new_url){
			//if(diff_brand || diff_collect || diff_team){
				location.href=new_url;
				//console.log(new_url);
			}else{

			}
			
		};
		
	});
	angular.bootstrap(angular.element(".container-filter-teams"), ['app_filter_teams']);
});

</script>