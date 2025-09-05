<div class="container-filter-brands" ng-controller="ctrl">
	<aside class="widget">
			<h3 class="widget-title">League</h3>
		<div class="wi-static-filter-element">
			<div>

			<select name="" id="" ng-options="item.value as item.label for item in model.brands" ng-model="brand" ng-change="update_url()">
				<option value="">All</option>
			</select>
			</div>
		</div>
	</aside>
	
</div>
<script>
	var main_cat=<?=$main_category?>;
</script>
<script>
jQuery(document).ready(function($){
	angular.module("app_filter_brands",["util_components"])
	.controller("ctrl",function($scope){
		$scope.final_url ="<?=$final_url?>";
		$scope.grupos=[{}];

		$scope.model = [];
		$scope.model.brands = <?=json_encode($data["brands"]);?>;

		$scope.brand=null;
	 
		$scope.is_presale = main_cat!=3259;
	 

		$scope.current_brands = <?=json_encode($current_element_brand)?>;
		if($scope.current_brands.length>0 && $scope.current_brands[0][0]){
			$scope.brand= $scope.current_brands[0][0];
			console.log($scope.brand);
		}
	 

		var current_brands_ini = <?=json_encode($current_element_brand)?>;
	 
		
 
 
		$scope.update_url=function(){
			let diff_brand=false;
		 

			let new_url = angular.copy($scope.final_url);
			let final_brand = $scope.brand!=null?"f_bc_brand[]="+ $scope.brand : "";
			 
			diff_brand=$scope.brand!=current_brands_ini[0];

			 
			if(diff_brand){
				new_url+= (new_url.substr(-1)!="?" ? "&":"")+final_brand;
			}
			
 
			if(location.href!=new_url){
			//if(diff_brand || diff_collect || diff_team){
				location.href=new_url;
				//console.log(new_url);
			}else{

			}
			
		};
		
	});
	angular.bootstrap(angular.element(".container-filter-brands"), ['app_filter_brands']);
});

</script>