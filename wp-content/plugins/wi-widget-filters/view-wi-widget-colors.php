<div class="container-filter-colors" ng-controller="ctrl">

<aside class="widget">
	<h3 class="widget-title">Color</h3>
	<div class="wi-static-filter-element">
		<util-select list="model.colors" ng-model="color"  default-text="All" callback="update_url"></util-select>
	</div>
</aside>
	
</div>

<script>
jQuery(document).ready(function($){
	angular.module("app_filter_colors",["util_components"])
	.controller("ctrl",function($scope){
		 

		$scope.model = [];
		$scope.model.colors = <?=json_encode($colors);?>;
		$scope.color=[];
		
		$scope.final_url ="<?=$final_url?>";
		 console.log($scope.final_url);

		$scope.color = <?=json_encode($current_color)?>;
		
		$scope.update_url=function(){
			let new_url = angular.copy($scope.final_url);

			if($scope.color.length>0){
				new_url+= (new_url.substr(-1)!="?" ? "&":"")+"filter_color="+$scope.color.join(",");
			}
			
			if(location.href!=new_url){
				location.href=new_url;
				//console.log(new_url);
			}else{

			}
			
		};
		
	});
	angular.bootstrap(angular.element(".container-filter-colors"), ['app_filter_colors']);
});

</script>