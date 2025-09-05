<?php include("header.php"); ?>

<div class="wi-fexpro-dashboard-container" ng-app="app" ng-controller="ctrl">
	<div class="row">
		<div class="col-3">
			<h4>Status</h4>
			<ul class="list-checkbox">
				<li ng-repeat="item in model.order_status_list track by $index">
					<input type="checkbox" name="order_status[]" value="{{item.code}}" ng-model="item.selected">
					{{item.name}}
				</li>
			</ul>
			<span class="msj-error">{{error1}}</span>
			<!-- <button class="btn btn-outline-primary" ng-click="exportByStatus()" ng-disabled="loading">Export XML of sold<br /> products to SAGE</button> -->
			<button class="btn btn-outline-primary" ng-click="exportByStatus()" ng-disabled="loading">Download XMLs of sold<br /> products</button>
			<div class="mt-2">
				<fex-loading finish="finish" ng-show="loading || finish" />
			</div>
		</div>
		<div class="col-3">
			<h4>Season</h4>
			<div class="form-group mb-4">
				<select name="season" id="" class="form-control" ng-model="season"
					ng-options="item.slug as item.name for item in model.seasons track by item.slug">
					<option value="">-selected-</option>
				</select>
			</div>
			<ul class="list-checkbox">
				<li ng-repeat="item in model.qs track by $index">

					<input type="checkbox" name="q" value="{{item.id}}" ng-model="item.selected">
					{{item.id.toUpperCase()}}
				</li>
			</ul>
			<span class="msj-error">{{error2}}</span>
			<div class="mb-4 text-center">
				<button class="btn btn-outline-primary" ng-click="exportBySeasonSold()" ng-disabled="loading2">Export XML of sold<br /> products to SAGE</button>
			</div>
			<div class="text-center">
				<button class="btn btn-outline-primary" ng-click="exportBySeasonNotSold()" ng-disabled="loading2">Export XML of not sold<br /> products to SAGE</button>
			</div>
			<div class="mt-2">
				<fex-loading finish="finish2" ng-show="loading2 || finish2" />
			</div>
		</div>
		<div class="col-6">
			<h4>Create your own list</h4>
			<div class="form-group">
				<textarea name="" id="" cols="30" rows="10" class="form-control" ng-model="skus"></textarea>
			</div>
			<div class="text-center">
				<span class="msj-error">{{error3}}</span>
			</div>
			<div class="text-right mt-4" style="text-align:right">
				<button class="btn btn-outline-primary" ng-click="exportBySKUS()" ng-disabled="loading3">Export XML of products to SAGE</button>
			</div>
			<div class="mt-2">
				<fex-loading finish="finish3" ng-show="loading3 || finish3" />
			</div>
		</div>

	</div>

</div>
<script>
	var base_url = "<?= get_site_url() ?>";
</script>
<script>
	angular.module("app", ["fex-components"]).controller("ctrl", function($scope, $http, $timeout) {
		$scope.model = {};
		$scope.model.order_status_list = [];
		$scope.model.seasons = [];
		$scope.model.qs = [{
			id: 'q1'
		}, {
			id: 'q2'
		}, {
			id: 'q3'
		}, {
			id: 'q4'
		}, {
			id: 'core'
		}];
		$scope.loading = false;
		$scope.finish = false;

		$scope.loading2 = false;
		$scope.finish2 = false;

		$scope.loading3 = false;
		$scope.finish3 = false;

		$scope.error1 = '';
		$scope.error2 = '';
		$scope.error3 = '';


		$scope.getOrderStatus = () => {
			$http({
				method: "GET",
				url: base_url + "/wp-admin/admin-ajax.php?action=widash_getorderstatus",
			}).then((response) => {
				$scope.model.order_status_list = response.data.order_status;
			})
		};
		$scope.getOrderStatus();
		$scope.getSeasons = () => {
			$http({
				method: "GET",
				url: base_url + "/wp-admin/admin-ajax.php?action=widash_getseasons",
			}).then((response) => {
				$scope.model.seasons = response.data.seasons;
			})
		};
		$scope.getSeasons();

		$scope.exportByStatus = () => {
			let status = $scope.model.order_status_list.filter(x => x.selected).map(x => x.code);

			$scope.error1 = "";
			if (!status.length || status.length == 0) {
				$scope.error1 = "Select a value";
				return;
			}

			$scope.loading = true;
			$scope.finish = false;
			let dataPost = {
				status
			}
			$http({
				method: "POST",
				url: base_url + "/wp-admin/admin-ajax.php?action=widash_export_products",
				data: dataPost
			}).then((response) => {

				$scope.loading = false;
				$scope.finish = true;
				if (response.data.error == 0 && response.data.zip) {
					// window.open(response.data.zip, '_blank');
					const iframe = document.createElement('iframe');

					iframe.src = response.data.zip; // URL del archivo o script
					iframe.style.display = 'none'; // Hacerlo invisible

					document.body.appendChild(iframe);

					setTimeout(() => {
						document.body.removeChild(iframe);
					}, 5000);
				}


			})
		}

		$scope.exportBySeasonSold = () => {


			let qs = $scope.model.qs.filter(x => x.selected).map(x => x.id);

			$scope.error2 = "";
			if (!qs.length || qs.length == 0) {
				$scope.error2 = "Select a value";
				return;
			}
			if (!$scope.season || $scope.season == "") {
				$scope.error2 = "Select a season";
				return;
			}

			$scope.loading2 = true;
			$scope.finish2 = false;

			let dataPost = {
				season: $scope.season,
				qs,
				sold: 1
			}
			$http({
				method: "POST",
				url: base_url + "/wp-admin/admin-ajax.php?action=widash_export_products",
				data: dataPost
			}).then((response) => {

				$scope.loading2 = false;
				$scope.finish2 = true;


			});
		}
		$scope.exportBySeasonNotSold = () => {


			let qs = $scope.model.qs.filter(x => x.selected).map(x => x.id);
			let dataPost = {
				season: $scope.season,
				qs,
				sold: 0
			}

			$scope.error2 = "";
			if (!qs.length || qs.length == 0) {
				$scope.error2 = "Select a value";
				return;
			}
			if (!$scope.season || $scope.season == "") {
				$scope.error2 = "Select a season";
				return;
			}

			$scope.loading2 = true;
			$scope.finish2 = false;

			$http({
				method: "POST",
				url: base_url + "/wp-admin/admin-ajax.php?action=widash_export_products",
				data: dataPost
			}).then((response) => {

				$scope.loading2 = false;
				$scope.finish2 = true;


			});
		}
		$scope.exportBySKUS = () => {

			$scope.error3 = "";

			if (!$scope.skus || $scope.skus == "") {
				$scope.error3 = "No Skus";
				return;
			}

			$scope.loading3 = true;
			$scope.finish3 = false;
			let skus = $scope.skus;
			let dataPost = {
				skus
			}
			$http({
				method: "POST",
				url: base_url + "/wp-admin/admin-ajax.php?action=widash_export_products",
				data: dataPost
			}).then((response) => {

				$scope.loading3 = false;
				$scope.finish3 = true;


			});
		}
	});
</script>