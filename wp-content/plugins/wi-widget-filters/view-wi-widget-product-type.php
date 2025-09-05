<?php $uniq=uniqid().rand(1,9);?>
<div class="container-filter-product-type container-filter-product-type-<?=$uniq?>" ng-controller="ctrl">
	<aside class="widget">
			<h3 class="widget-title"><?=$title?></h3>
			<div class="wi-static-filter-element">
			<util-select list="model.categories" ng-model="current_filter" default-text="All" callback="update_url"></util-select>
			</div>
	</aside>
</div>
<script>
jQuery(document).ready(function($){
	angular.module("app_filter_product_type_<?=$uniq?>",["util_components"])
	.controller("ctrl",function($scope){
		$scope.grupos=[{}];
		const slug_type = "<?=$slug_type?>";
		$scope.final_url ="<?=$final_url?>";
		

		$scope.model = [];
		$scope.model.categories = <?=json_encode($data);?>;

		$scope.current_filter = <?=json_encode($current_product_type)?>;
		$scope.initial_filter =  <?=json_encode($current_product_type)?>;

		$scope.update_url=function(){
			let new_url = angular.copy($scope.final_url);
			let part_url="";
			if($scope.current_filter.length>0){
				part_url="product_type_"+slug_type+"="+$scope.current_filter.join(",");
				new_url+= (new_url.substr(-1)!="?" ? "&":"")+part_url;
			}
			
			console.log(part_url);
			if($scope.current_filter.length!=$scope.initial_filter.length){
				location.href=new_url;
				//console.log(new_url);
			}else{

			}
			
		};



	});
	angular.bootstrap(angular.element(".container-filter-product-type-<?=$uniq?>"), ['app_filter_product_type_<?=$uniq?>']);
});

</script>