<div ng-app="app-lists" ng-controller="ctrl">
	<div class="list-accordion">
		<div class="list-item-accordion">
			<div class="list-item-accordion-header {{!open_presale?'close-tab':''}}" ng-click='open_presale=!open_presale'>
				PRESALE
			</div>
			<div class="list-item-accordion-body" ng-show="open_presale">
				<div class="row">
					<div class="col-6 col-md-4 text-center" ng-repeat="list in lists" ng-show="list.category=='PRESALE'">
						<div class="list-item" >
							<div class="list-item-image" style="background-image:url('{{list.image}}')"></div>
							<div class="list-item-body">
								<span class="ico-download" ng-click="download_list(list)" ng-show="!list.isDownload"></span>
								<span class="loading-spinner" ng-show="list.isDownload"></span>
								<div>
								<label for="" class="list-item-name">{{list.name}}</label>
								<label for="" class="list-item-info">CREATED BY: {{list.display_name}}</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="text-center" ng-show="lists.length == 0">
					<h3>without lists</h3>
				</div>
			</div>
		</div>
		<div class="list-item-accordion">
			<div class="list-item-accordion-header bg-blue {{!open_inventory?'close-tab':''}}" ng-click='open_inventory=!open_inventory'>
				INVENTORY
			</div>
			<div class="list-item-accordion-body" ng-show="open_inventory">
				<div class="row">
					<div class="col-6 col-md-4 text-center" ng-repeat="list in lists" ng-show="list.category=='INVENTORY'">
						<div class="list-item" >
							<div class="list-item-image" style="background-image:url('{{list.image}}')"></div>
							<div class="list-item-body bg-red">
								<span class="ico-download" ng-click="download_list(list)" ng-show="!list.isDownload"></span>
								<span class="loading-spinner" ng-show="list.isDownload"></span>
								<div>
								<label for="" class="list-item-name">{{list.name}}</label>
								<label for="" class="list-item-info">CREATED BY: {{list.display_name}}</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="text-center" ng-show="lists.length == 0">
					<h3>without lists</h3>
				</div>
				
			</div>
		</div>
	</div>
</div>
<script src="<?=WI_PLUGIN_URL."assets/js/angular.min.js"?>"></script>
<script src="<?=WI_PLUGIN_URL."assets/js/angular-components.js?v=1"?>"></script>
<script src="<?=WI_PLUGIN_URL."assets/js/sweetalert2.all.min.js"?>"></script>
<script>var site_url="<?=get_site_url();?>"</script>
<link rel="stylesheet" href="<?=WI_PLUGIN_URL."assets/js/sweetalert2.min.css?v=1"?>">
<script>
	angular.module("app-lists",["angular-components","util_components"]).controller("ctrl",($scope,$http)=>{
		$scope.model={};
		$scope.lists=[];
		$scope.open_presale=true;
		$scope.open_inventory=true;

		$scope.get_lists=()=>{
			$http({
				url:site_url+"/wp-admin/admin-ajax.php?action=qep_list_get_all",
				method:"GET"
			}).then((response)=>{
				$scope.lists=response.data.lists;
			});
		}
		$scope.get_lists();

		$scope.download_list=(item)=>{
			item.isDownload=true;
            $http({
                url:site_url+"/wp-admin/admin-ajax.php?action=qep_get_products_export",
                method:"POST",
                data:{download_list:item.id_list} //$scope.filtros_selected
            }).then(function(response){
                if(response.data.error==0){
                    window.open(response.data.download, '_blank');
                }
                item.isDownload=false;
            },function(){
                item.isDownload=false;
            });

		}
		
	});
</script>