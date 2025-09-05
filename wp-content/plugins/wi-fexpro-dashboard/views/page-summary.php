<?php include("header.php");?>

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
		</div>
		<div class="col-5 text-center">
			<div class="loader" ng-if="loading"> <div class="loader-wheel"></div> <div class="loader-text"></div> </div>
			<div class="area-grafica-1" id="area-grafica-1">
				
			</div>
		</div>
		<div class="col-3">
			<div class="fexpro-escalas">
				<div class="item-escala escala-drop">
					<label for="">${{total.drop | currency:'':0}}</label>
					<label for="">Drop for {{total.units_drop| currency:'':0}} units of {{total.skus_drop| currency:'':0}} Skus</label>
				</div>
				<div class="item-escala escala-ok">
					<label for="">${{total.ok | currency:''}}</label>
					<label for="">Ok for {{total.units_ok| currency:'':0}} units of {{total.skus_ok| currency:'':0}} Skus</label>
				</div>
				<div class="item-escala escala-stock">
					<label for="">${{total.stock | currency:'':0}}</label>
					<label for="">Stock for {{total.units_stock| currency:'':0}} units of {{total.skus_stock| currency:'':0}} Skus</label>
				</div>
				<div class="item-escala escala-total">
					<label for="">${{total.total | currency:'':0}}</label>
					<label for="">Total Sale for {{total.units| currency:'':0}} units of {{total.skus| currency:'':0}} Skus</label>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-3">
			<h4>Country</h4>
			<ul class="list-checkbox">
				<li ng-repeat="c in model.countries track by $index">
					<input type="checkbox" name="country[]" value="{{c.country}}" ng-model="c.selected">
					{{c.country_str}}
				</li>
				 
			</ul>
			<div class="mt-5">
				<button class="btn btn-primary" ng-click="getReport()">Apply</button>
			</div>
		</div>
		<div class="col-8 text-center">
			<div class="loader" ng-if="loading"> <div class="loader-wheel"></div> <div class="loader-text"></div> </div>
			<div class="area-grafica-paises mt-5" id="area-grafica-paises"></div>
		</div>
	</div>
</div>
<script>
	var base_url = "<?=get_site_url()?>";
</script>
<script>
	angular.module("app",[]).controller("ctrl",function($scope,$http,$timeout){
		$scope.model = {};
		$scope.model.order_status_list = [];
		$scope.model.countries = [];
		$scope.report = [];
		$scope.total={};
		$scope.loading=false;

		$scope.getOrderStatus = ()=>{
			$http({
				method:"GET",
				url:base_url+"/wp-admin/admin-ajax.php?action=widash_getorderstatus",
			}).then((response)=>{
				$scope.model.order_status_list = response.data.order_status;
			})
		};
		$scope.getCountries=()=>{
			$http({
				method:"GET",
				url:base_url+"/wp-admin/admin-ajax.php?action=widash_getcountries",
			}).then((response)=>{
				$scope.model.countries = response.data.countries;
			})
		}
		$scope.getOrderStatus();
		$scope.getCountries();

		$scope.getReport=()=>{
			let status = $scope.model.order_status_list.filter(x=>x.selected).map(x=>x.code);
		

			let countries = $scope.model.countries.filter(x=>x.selected).map(x=>x.country);
			let dataPost={
				status,
				countries
			}
			$scope.loading=true;
			$http({
				method:"POST",
				url:base_url+"/wp-admin/admin-ajax.php?action=widash_getsummary",
				data:dataPost
			}).then((response)=>{
				$scope.loading=false;
				$scope.report = response.data.summary;

				$scope.total={
					drop:0,
					ok:0,
					stock:0,
					units_drop:0,
					skus_drop:0,
					units_stock:0,
					skus_stock:0,
					units_ok:0,
					skus_ok:0,
					total:0,
					units:0,
					skus:0
				};
				$scope.report.forEach(x=>{
					console.log(x);
					if(x.country!="MX"){
						$scope.total.drop += Number(x.total_drop);
						$scope.total.ok += Number(x.total_ok);
						$scope.total.stock += Number(x.total_stock);
					}else{
						$scope.total.drop += Number(x.total_calc_drop);
						$scope.total.ok += Number(x.total_calc_ok);
						$scope.total.stock += Number(x.total_calc_stock);
					}
					

					$scope.total.units_drop += Number(x.total_units_drop);
					$scope.total.units_ok += Number(x.total_units_ok);
					$scope.total.units_stock += Number(x.total_units_stock);

					$scope.total.skus_drop += Number(x.total_skus_drop);
					$scope.total.skus_ok += Number(x.total_skus_ok);
					$scope.total.skus_stock += Number(x.total_skus_stock);
					if(x.country!="MX"){
						$scope.total.total += Number(x.total);
					} else{
						$scope.total.total += Number(x.total_calc);
					}
					$scope.total.skus += Number(x.skus);
					$scope.total.units += Number(x.total_units);

				});

			 

				let data = {}
				data.title = $scope.model.order_status_list.filter(x=>x.selected).map(x=>x.name).join(", ");
				data.data = [];
				data.data.push({
					name:"Ok",
					y:$scope.total.units_ok
				});
				data.data.push({
					name:"Stock",
					y:$scope.total.units_stock
				});
				data.data.push({
					name:"Drop",
					y:$scope.total.units_drop
				});

				$scope.render_chart_status(data);

				let data_c = {};
				data_c.title = $scope.model.order_status_list.filter(x=>x.selected).map(x=>x.name).join(", ");
				data_c.categories = $scope.report.map(x=>x.country_str);
				data_c.data = [];
				data_c.data.push({
					name:'Ok',
					data:$scope.report.map(x=>({y:x.country=="MX"?x.total_calc_ok:x.total_ok,units:x.total_units_ok}))
				});
				data_c.data.push({
					name:'Stock',
					data:$scope.report.map(x=>({y:x.country=="MX"?x.total_calc_stock:x.total_stock,units:x.total_units_stock}))
				});
				data_c.data.push({
					name:'drop',
					data:$scope.report.map(x=>({y:x.country=="MX"?x.total_calc_drop:x.total_drop,units:x.total_units_drop}))
				});
				 
				$scope.render_chart_countries(data_c);
			})
		}

		$scope.render_chart_status=(data)=>{
			Highcharts.chart('area-grafica-1', {
			chart: {
				type: 'pie'
			},
			title: {
				text: data.title
			},
			tooltip: {
				formatter: function() {
					return  `<b>${Highcharts.numberFormat(this.point.y, 0, '.', ',')} units</b>`;
				}
			},
			subtitle: {
				text:'SKU performance by Status'
			},
			colors: [
				'#75b675',
				'#fd9c41',
				'#ff3030'
			],
			plotOptions: {
				series: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: [{
						enabled: true,
						distance: 20
					}, {
						enabled: true,
						distance: -40,
						format: '{point.percentage:.1f}%',
						style: {
							fontSize: '1.2em',
							textOutline: 'none',
							opacity: 0.7
						},
						filter: {
							operator: '>',
							property: 'percentage',
							value: 10
						}
					}]
				}
			},
			series: [
				{
					name: 'Percentage',
					colorByPoint: true,
					data: data.data
				}
			]
		});
		};

		$scope.render_chart_countries = (data)=>{
			Highcharts.chart('area-grafica-paises', {
			chart: {
				type: 'column'
			},
			title: {
				text: data.title,
				align: 'center'
			},
			colors: [
				'#75b675',
				'#fd9c41',
				'#ff3030'
			],
			subtitle: {
				text: 'Country Summary',
				align: 'center'
			},
			xAxis: {
				categories: data.categories,
				crosshair: true,
				accessibility: {
					description: 'Countries'
				}
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Sales ($)'
				}
			},
			tooltip: {
				formatter: function() {
					return  `<b>$${Highcharts.numberFormat(this.point.y, 0, '.', ',')}</b><br/>${Highcharts.numberFormat(this.point.units, 0, '.', ',')} units`;
				}
			},
			plotOptions: {
				column: {
					pointPadding: 0.2,
					borderWidth: 0
				}
			},
			series:data.data
			/* series: [
				{
					name: 'Corn',
					data: [406292, 260000, 107000, 68300, 27500, 14500]
				},
				{
					name: 'Wheat',
					data: [51086, 136000, 5500, 141000, 107180, 77000]
				}
			] */
		});

		}
		//$scope.render_chart_status();
		//$scope.render_chart_countries();
	});
</script>