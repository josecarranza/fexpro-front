<div class="container-filter-attribute" ng-controller="ctrl">
	<aside class="widget">
			<h3 class="widget-title"><?=$title?></h3>
		<div class="wi-static-filter-element">
			<div>

			<select name="" id="" ng-options="item.value as item.label for item in model.att_data" ng-model="att" ng-change="update_url()">
				<option value="">All</option>
			</select>
			</div>
		</div>
	</aside>
	
</div>
<script>
	 var att_slug = "<?=$att_slug?>";
</script>
<script>
jQuery(document).ready(function($){
	angular.module("app_filter_attribute",["util_components"])
	.controller("ctrl",function($scope){
		$scope.final_url ="<?=$final_url?>";
 

		$scope.model = [];
		$scope.model.att_data = <?=json_encode($data["att_data"]);?>;

		$scope.att=null;
 
	 

		$scope.current_element_att = <?=json_encode($current_element_att)?>;
		if($scope.current_element_att.length>0 && $scope.current_element_att[0]){
			$scope.att= $scope.current_element_att[0];
			console.log($scope.att);
		}
	 

		var current_element_att_ini = <?=json_encode($current_element_att)?>;
	 
		
 
 
		$scope.update_url=function(){
			let diff_brand=false;
		 

			let new_url = angular.copy($scope.final_url);
			let final_att = $scope.att!=null?att_slug+"="+ $scope.att : "";
			 console.log(final_att);
			diff_att=$scope.att!=current_element_att_ini[0];

			 
			if(diff_att){
				new_url+= (new_url.substr(-1)!="?" ? "&":"")+final_att;
			}
			
 
			if(location.href!=new_url){
			//if(diff_brand || diff_collect || diff_team){
				location.href=new_url;
				//console.log(new_url);
			}else{

			}
			
		};
		
	});
	angular.bootstrap(angular.element(".container-filter-attribute"), ['app_filter_attribute']);
});

</script>