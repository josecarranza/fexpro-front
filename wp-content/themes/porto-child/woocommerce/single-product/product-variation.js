angular.module("component_variations", [])
    .component('wiProductVariableStock', {
        template: `<div class="item-product-variation">
        <div class="item-product-variation-image">
            <label for="" class="color-variation">{{$ctrl.data.variation_name}}</label>
            <div class="image-box"><img ng-src="{{$ctrl.data.image}}" ng-click="show_gallery($ctrl.data)" /></div>
           
            
        </div>
        <div class="item-product-variation-sizes">
            <table>
                <thead>
                    <tr>
                        <th style="max-width:100px" ng-show="$ctrl.ispresale==0">Available</th>
                        <th ng-repeat="size in $ctrl.data.sizes">{{size.size}}</th>
                        <th class="col-totals">Total Units.</th>
                        <th class="col-price">Price</th>
                        <th class="col-packs">Packs ({{$ctrl.data.piezes_x_pack}} pieces)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td  ng-show="$ctrl.ispresale==0"><span class="info-units">{{$ctrl.data.total_units}} units</span></td>
                        <td ng-repeat="size in $ctrl.data.sizes">{{size.value*$ctrl.factor}}</td>
                        <td>{{$ctrl.data.total_sizes}}</td>
                        <td>{{$ctrl.data.price | currency:'$'}}</td>
                        <td class="text-left">
                        <jh-number-picker number="$ctrl.data.qty" min="0" max="{{$ctrl.max_stock}}" step="1" ></jh-number-picker>
                            <label for="" class="sm-text" ng-show="$ctrl.ispresale==0">Available: {{$ctrl.max_stock}} packs</label>
                            <label for="" class="sm-text white-space" ng-show="$ctrl.typeStock=='future' && $ctrl.ispresale==0">Est. Delivery: {{$ctrl.data.delivery_date.replace('-','/').replace('-','/')}}</label>
                            <label for="" class="sm-text white-space" ng-show="$ctrl.typeStock=='future' && $ctrl.ispresale==1">Est. Delivery: {{$ctrl.data.presale_delivery_date.replace('-','/').replace('-','/')}}</label>   
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>`,
        bindings: {
            data: "=",
            typeStock: "@",
            ispresale: "="
        },
        controller: function($scope, $rootScope, $q, $http) {
            var $ctrl = this;


            $ctrl.factor = 0;
            $ctrl.$onInit = function() {


                $ctrl.data.qty = 0;
                $ctrl.data.total_amount = 0;

                $ctrl.data.qty_future = 0;
                $ctrl.data.total_amount_future = 0;

                $ctrl.max_stock = $ctrl.typeStock == "future" ? $ctrl.data.stock_future : $ctrl.data.stock_present;

                if ($ctrl.ispresale == 1) {
                    $ctrl.max_stock = 9999;
                }

                $ctrl.data.total_sizes = 0;


                $ctrl.data.piezes_x_pack = 0;

                for (i in $ctrl.data.sizes) {
                    $ctrl.data.total_sizes += $ctrl.data.sizes[i].value;

                    $ctrl.data.sizes[i].max_value = $ctrl.data.sizes[i].value * $ctrl.data.max_stock;


                    $ctrl.data.piezes_x_pack += $ctrl.data.sizes[i].value;
                }

                $ctrl.data.total_size_max = $ctrl.data.total_sizes * $ctrl.data.max_stock;
                $ctrl.data.total_units = $ctrl.data.piezes_x_pack * $ctrl.max_stock;

                console.log($ctrl.data);


            };
            $scope.imageNoExists = function(image_url) {
                return $q(function(resolve, reject) {
                    $http.get(image_url).then(function() {
                        //reject(false);
                    }, function() {
                        resolve(true);
                    });
                });
            };

            $ctrl.updata_totals = function() {

            };
            $scope.calcule_totals = function() {
                $ctrl.data.total_sizes = 0;
                $ctrl.data.total_amount = 0;

                $ctrl.factor = $ctrl.data.qty == 0 ? 1 : $ctrl.data.qty;

                for (i in $ctrl.data.sizes) {
                    $ctrl.data.total_sizes += $ctrl.data.sizes[i].value * $ctrl.factor;
                }

                $ctrl.data.total_amount = $ctrl.data.total_sizes * $ctrl.data.price;

                $rootScope.update_totales();
            }

            $scope.$watch("$ctrl.data.qty", function(val1, val2) {
                $scope.calcule_totals();
            });

            $scope.show_gallery = function(_data) {
                color = _data.pa_color;
                //color=color.toLowerCase().replace(" ","-");
                console.log(color);
                jQuery('.variations_form.cart .filter-item-list a[data-value="' + color + '"]').click();
                $scope.$emit("set_current_sku", _data.sku);
            }
        }
    })
    .component('jhNumberPicker', {
        bindings: {
            number: '=',
            min: '@',
            max: '@',
            step: '@',
            buttonClass: '@'
        },
        controller: function($scope, $element, $timeout) {
            var vm = this;

            vm.number = parseInt(vm.number, 10) || 0;
            vm.number2 = vm.number;
            var opts = {};
            $scope.render_end = false;
            this.$onInit = function() {
                opts = {
                    min: this.min,
                    max: this.max,
                    step: 1
                };
                $timeout(function() {
                    $scope.render_end = true;
                });
            };

            this.decrement = function() {
                if (vm.number <= opts.min) {
                    return;
                }
                vm.number = vm.number - parseInt(opts.step, 10);

            };

            this.increment = function() {

                if (vm.number >= opts.max) {
                    return;
                }
                vm.number = vm.number + parseInt(opts.step, 10);
            };


            $scope.$watch("$ctrl.number", function(newval, oldval) {
                if (!$scope.render_end) {
                    return false;
                }
                if (Number(jQuery($element).find('input')[0].value) > vm.max) {
                    jQuery($element).find('input')[0].value = vm.max;
                    vm.number = Number(vm.max);
                }
                console.log(jQuery($element).find('input')[0].value);
                console.log(newval);
                if (newval == null) {
                    //vm.number=oldval;
                    return;
                }
                if (jQuery($element).find('input')[0].value == "") {
                    jQuery($element).find('input')[0].value = 0;
                    vm.number = 0;
                }
                vm.number = parseInt(vm.number);

            });
            $scope.blur = function() {

                if (Number(jQuery($element).find('input')[0].value) < 0) {
                    jQuery($element).find('input')[0].value = 0;
                    vm.number = 0;
                }
                jQuery($element).find('input')[0].value = parseInt(vm.number);
                vm.number = parseInt(vm.number);
            }

        },
        template: '<div class="jh-number-picker">' +
            '<button class="jh-number-picker--btn jh-number-picker--decrement" ng-click="$ctrl.decrement()" ng-class="$ctrl.buttonClass">-</button>' +
            '<div class="jh-number-picker--value"><input type="number" ng-model="$ctrl.number" step="1" min="{{$ctrl.min}}" max="{{$ctrl.max}}" ng-blur="blur()" /></div>' +
            '<button class="jh-number-picker--btn jh-number-picker--increment" ng-click="$ctrl.increment()" ng-class="$ctrl.buttonClass">+</button>' +
            '</div>'
    });