angular.module("component_variations", [])
    .component('wiProductCartItem', {
        template: `<div class="wi-item-cart">
        <div class="wi-item-cart-imagen">
            <img ng-src="{{$ctrl.data.image}}"  />
        </div>
        <div class="wi-item-cart-detail">
            <a ng-href="{{$ctrl.data.link_product}}"><label for="" class="wi-item-cart-title">{{$ctrl.data.title}}</label></a>
            <label for="" class="wi-item-cart-sku">{{$ctrl.data.sku}}</label>
            <a ng-href="{{$ctrl.data.link_remove}}" class="link-remove"><span class="ico-remove"></span></a>
            <div class="item-product-variation-sizes">
                <table>
                    <thead>
                        <tr>
                            <th style="max-width:100px" ng-show="!is_presale">Available</th>
                            <th ng-repeat="size in $ctrl.data.sizes">{{size.label}}</th>
                            <th class="col-totals">Total Units.</th>
                            <th class="col-price">Price</th>
                            <th class="col-packs">Packs ({{$ctrl.data.piezes_x_pack}} pieces)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td ng-show="!is_presale"><span class="info-units">{{$ctrl.data.total_units_available}} units</span></td>
                            <td ng-repeat="size in $ctrl.data.sizes">{{size.value*$ctrl.factor}}</td>
                            <td>{{$ctrl.data.total_units}}</td>
                            <td>{{$ctrl.data.price | currency:'$'}}</td>
                            <td>
                            <jh-number-picker number="$ctrl.data.qty" min="1" max="{{$ctrl.max_stock}}" step="1" ></jh-number-picker>
                                <label for="" class="sm-text" ng-show="!is_presale">Available: {{$ctrl.max_stock}} packs</label>  
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>`,
        bindings: {
            data: "=",
            typeStock: "="
        },
        controller: function($scope, $rootScope, $q, $http) {
            var $ctrl = this;


            $ctrl.factor = 0;
            $ctrl.$onInit = function() {


                $ctrl.data.qty = $ctrl.data.qty | 0;
                $ctrl.data.total_amount = 0;

                $ctrl.data.qty_future = 0;
                $ctrl.data.total_amount_future = 0;

                

                $ctrl.max_stock = $ctrl.typeStock == "future" ? $ctrl.data.stock_future : $ctrl.data.stock_present;

                $scope.is_presale=$ctrl.data.is_presale;
                if($scope.is_presale){
                    $ctrl.max_stock=9999;
                }

                $ctrl.data.total_units = 0;


                $ctrl.data.piezes_x_pack = 0;

                for (i in $ctrl.data.sizes) {
                    $ctrl.data.total_units += Number($ctrl.data.sizes[i].value);

                    $ctrl.data.sizes[i].max_value = $ctrl.data.sizes[i].value * $ctrl.data.max_stock;
                    $ctrl.data.piezes_x_pack += Number($ctrl.data.sizes[i].value);
                }

                $ctrl.data.total_size_max = $ctrl.data.total_units * $ctrl.data.max_stock;
                $ctrl.data.total_units_available = $ctrl.max_stock*$ctrl.data.total_units;
                
                

                //console.log($ctrl.data);
                $ctrl.data.link_remove = $ctrl.data.link_remove.replace(/&amp;/g, "\&");
            };

            $ctrl.updata_totals = function() {

            };
            $scope.calcule_totals = function() {
                $ctrl.data.total_units = 0;
                $ctrl.data.total_amount = 0;

                $ctrl.factor = $ctrl.data.qty == 0 ? 1 : $ctrl.data.qty;

                for (i in $ctrl.data.sizes) {
                    $ctrl.data.total_units += $ctrl.data.sizes[i].value * $ctrl.factor;
                }

                $ctrl.data.total_amount = $ctrl.data.total_units * $ctrl.data.price;

                //$rootScope.update_totales();
                $scope.$emit("update_totals");
            }

            $scope.$watch("$ctrl.data.qty", function(val1, val2) {
                $scope.calcule_totals();
            });

            $scope.show_gallery = function(color) {
                //color=color.toLowerCase().replace(" ","-");
                //console.log(color);
                jQuery('.variations_form.cart .filter-item-list a[data-value="' + color + '"]').click();
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
        controller: function($scope,$element,$timeout) {
            var vm = this;

            vm.number = parseInt(vm.number, 10) || 0;
            vm.number2 = vm.number;
            var opts = {};
            $scope.render_end=false;

            this.$onInit = function() {
                opts = {
                    min: this.min,
                    max: this.max,
                    step: 1
                };
                $timeout(function(){
                    $scope.render_end=true;
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

     
            $scope.$watch("$ctrl.number",function(newval, oldval){
                if(! $scope.render_end){
                    return false;
                }
                if(Number(jQuery($element).find('input')[0].value)>vm.max){
                    jQuery($element).find('input')[0].value=vm.max;
                    vm.number=Number(vm.max);
                }
                console.log(jQuery($element).find('input')[0].value);
                console.log(newval);
                if(newval==null){
                    //vm.number=oldval;
                    return;
                }
                if(jQuery($element).find('input')[0].value==""){
                    jQuery($element).find('input')[0].value=1;
                    vm.number=1;
                }
                vm.number=parseInt(vm.number);
                
            });
            $scope.blur=function(){
    
                if(Number(jQuery($element).find('input')[0].value)<0){
                    jQuery($element).find('input')[0].value=1;
                    vm.number=1;
                }
                jQuery($element).find('input')[0].value=parseInt(vm.number);
                vm.number=parseInt(vm.number);
            }
            
        },
        template: '<div class="jh-number-picker">' +
        '<button class="jh-number-picker--btn jh-number-picker--decrement" ng-click="$ctrl.decrement()" ng-class="$ctrl.buttonClass">-</button>' +
        '<div class="jh-number-picker--value"><input type="number" ng-model="$ctrl.number" step="1" min="{{$ctrl.min}}" max="{{$ctrl.max}}" ng-blur="blur()" /></div>' +
        '<button class="jh-number-picker--btn jh-number-picker--increment" ng-click="$ctrl.increment()" ng-class="$ctrl.buttonClass">+</button>' +
        '</div>'
    });