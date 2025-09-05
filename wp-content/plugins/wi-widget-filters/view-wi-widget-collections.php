<div class="container-filter-collections" ng-controller="ctrl">
	<aside class="widget" ng-show="is_presale">
		<h3 class="widget-title">Collection</h3>
		
		<div class="wi-static-filter-element">
			<util-select list="model.collections_list" ng-model="collections" filter2="grupo.brand" default-text="All" callback="update_url"></util-select>
		</div>
		 
	</aside>
	
</div>
<script>
	var main_cat=<?=$main_category?>;
</script>
<script>
jQuery(document).ready(function($){
	angular.module("app_filter_collections",["util_components"])
	.controller("ctrl",function($scope){
		$scope.grupos=[{}];

		$scope.model = [];
		$scope.brand = null;
		$scope.collections = [];

		$scope.model.collections = <?=json_encode($data["collections"]);?>;


		$scope.is_presale = main_cat!=3259;
		
		$scope.model.collections_list=$scope.model.collections.map(x=>{return {text:x.name,value:x.slug,group:x.brand}});
	
		$scope.final_url ="<?=$final_url?>";
		//console.log($scope.final_url);

		$scope.current_brands = <?=json_encode($current_element_brand)?>;
		$scope.current_collect = <?=json_encode($current_element_collect)?>;

		if($scope.current_brands.length>0 && $scope.current_brands[0][0]){
			$scope.brand= $scope.current_brands[0][0];
			 
		}
		if($scope.current_collect.length>0 && $scope.current_collect[0]){
			$scope.collections =$scope.current_collect[0];
			 
		}

	

		var current_brands_ini = <?=json_encode($current_element_brand)?>;
		var current_collect_ini = <?=json_encode($current_element_collect)?>;

	 
		$scope.update_url=function(){
			console.log($scope.collections);
			
			let diff_collect=false;


			let new_url = angular.copy($scope.final_url);
			
			let final_brand = $scope.brand!=null?"f_bc_brand[]="+ $scope.brand : "";

			let final_collect= $scope.collections.length>0 ? "f_bc_collection[0]="+$scope.collections.join(',') : "";

		
			console.log(final_collect,current_collect_ini);

			 
			diff_collect=$scope.collections!=current_collect_ini[0];

		 

			if(diff_collect){
				new_url+= (new_url.substr(-1)!="?" ? "&":"")+final_collect;
			}
			
			
			if(location.href!=new_url){
			//if(diff_brand || diff_collect || diff_team){
				location.href=new_url;
				//console.log(new_url);
			}else{

			}
			
		};
		
	});
	angular.bootstrap(angular.element(".container-filter-collections"), ['app_filter_collections']);
});

</script>