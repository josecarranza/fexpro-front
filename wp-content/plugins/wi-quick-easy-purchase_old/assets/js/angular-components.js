(function() {
    'use strict';

    angular.module('angular-components', [])
        .component('wiFilter', wiFilter())
        .component('jhNumberPicker', {
            bindings: {
                number: '=',
                min: '@',
                max: '@',
                step: '@',
                buttonClass: '@'
            },
            controller: jhNumberPickerCtrl,
            template: '<div class="jh-number-picker">' +
                '<button class="jh-number-picker--btn jh-number-picker--decrement" ng-click="$ctrl.decrement()" ng-class="$ctrl.buttonClass">-</button>' +
                '<div class="jh-number-picker--value"><input type="number" ng-model="$ctrl.number" step="1" min="{{$ctrl.min}}" max="{{$ctrl.max}}" ng-blur="blur()" /></div>' +
                '<button class="jh-number-picker--btn jh-number-picker--increment" ng-click="$ctrl.increment()" ng-class="$ctrl.buttonClass">+</button>' +
                '</div>'
        }).component('wiModal', {
            transclude: true,
            bindings: {
                show: '='
            },
            template: `<div class="qep-modal-overlayer" ng-if="$ctrl.show">
            <div class="qep-modal">
                <div class="qep-modal-content">
                    <span class="qep-ico-close" ng-click="$ctrl.show=false"></span>
                    <div class="qep-modal-body">
                    <ng-transclude></ng-transclude>
                    </div>
                </div>
            </div>
        </div>`
        });

    function wiFilter() {
        wiFilterController.$inject = ["$scope", "$timeout"];

        function wiFilterController($scope, $timeout) {
            var $ctrl = this;
            $scope.list_filter = [];


            $scope.filter_appy = function() {
                $scope.list_filter = [];
                let search = $scope.text_filter.toLowerCase() || "";
                $scope.list_filter = $scope.filtrar($ctrl.list, search);

            }

            $scope.filtrar = function(lista, search) {
                var list_filter = [];
                angular.forEach(angular.copy(lista), function(item, index) {
                    if (item.items) {
                        console.log(item.items);
                        item.items = $scope.filtrar(item.items, search);
                        if (item.items.length > 0) {
                            list_filter.push(item);
                        }
                    } else {
                        if (item.text.toLowerCase().includes(search)) {
                            list_filter.push(item);
                        }
                    }
                });
                return list_filter;
            };
            $scope.open = function(item) {
                if (!item.open) {
                    item.open = true;
                } else {
                    item.open = false;
                }
            };
            $scope.upd = function() {
                $ctrl.selected = [];
                angular.forEach($scope.list_filter, function(item, index) {
                    if (item.checked) {
                        $ctrl.selected.push(item.value);
                    }
                    if (item.items && item.items.length > 0) {
                        angular.forEach(item.items, function(subitem1, index1) {
                            if (subitem1.checked) {
                                $ctrl.selected.push(subitem1.value);
                            }
                            if (subitem1.items && subitem1.items.length > 0) {
                                angular.forEach(subitem1.items, function(subitem2, index2) {
                                    if (subitem2.checked) {
                                        $ctrl.selected.push(subitem2.value);
                                    }
                                });
                            }
                        });
                    }
                });
                $ctrl.selected = $ctrl.selected.join(",");
                console.log($ctrl.selected);
            }
            $ctrl.$onInit = function() {
                $scope.list_filter = $ctrl.list;
                $timeout(function() {
                    $scope.upd();
                }, 100);
            };



        }

        return {
            template: `<div class="qep-form-filter">
            <div class="text-filter">{{$ctrl.label}}</div>
            <div class="qep-form-filter-options-container">
                <input type="text" ng-change="filter_appy()" ng-model="text_filter" placeholder="Search {{$ctrl.label}}" ng-show="!$ctrl.hideSearch">
                <div class="qep-form-filter-options-wrapper">
                    <ul>
                        <li ng-repeat="item in list_filter">
                            <input type="checkbox" value="{{item.value}}" ng-model="item.checked" ng-change="upd()"> 
                            <span>{{item.text}}</span>
                            <i class="ico-collapse {{!item.open?'close':''}}" ng-if="item.items.length" ng-click="open(item)"></i>
                            <ul ng-if="item.items && item.items.length && item.open">
                                <li ng-repeat="subitem1 in item.items">
                                    <input type="checkbox" value="{{subitem1.value}}" ng-model="subitem1.checked" ng-change="upd()"> 
                                    <span>{{subitem1.text}}</span>
                                    <i class="ico-collapse {{!subitem1.open?'close':''}}" ng-if="subitem1.items.length" ng-click="open(subitem1)"></i>
                                    <ul ng-if="subitem1.items && subitem1.items.length && subitem1.open" >
                                        <li ng-repeat="subitem2 in subitem1.items">
                                            <input type="checkbox" value="{{subitem2.value}}" ng-model="subitem2.checked" ng-change="upd()"> 
                                            <span>{{subitem2.text}}</span>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            
                        </li>
                    </ul>
                </div>
            </div>
        </div>`,
            bindings: {
                list: '=',
                label: "@",
                selected: '=?',
                hideSearch: '=?'
            },
            controller: wiFilterController,
        };
    }

    function jhNumberPickerCtrl($scope, $timeout, $element) {

        var vm = this;

        vm.number = parseInt(vm.number, 10) || 0;
        $scope.render_end = false;
        /*var opts = assign({
            min: this.min,
            max: this.max,
            step: this.step
        }, defaults);*/
        var opts = {};

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

    }

    angular.module('angular-components')
        .component('wiSizesTable', {
            template: `<div class="qep-sizes-box">
                            <table class="qep-table-sub">
                                <tr>
                                    <th ng-repeat="s in $ctrl.data">{{s.size}}</th>
                                    <th>TOTAL UNITS</th>
                                    <th>SUBTOTAL</th>
                                </tr>
                                <tr>
                                    <td ng-repeat="s in $ctrl.data" ng-init="$ctrl.total=$ctrl.total+s.value">{{s.value*$ctrl.factor}}</td>
                                    <td><b>{{$ctrl.total*$ctrl.factor}}</b></td>
                                    <td><b>{{($ctrl.total*$ctrl.factor*$ctrl.price)|currency:'$'}}</b></td>
                                </tr>
                            </table>
                        </div>`,
            bindings: {
                data: "=",
                qty: "=",
                title: "@",
                price: "="
            },
            controller: function($scope) {
                var $ctrl = this;
                $ctrl.total = 0;
                $ctrl.factor = 1;
                $ctrl.$onInit = function() {
                    $ctrl.factor = $ctrl.qty <= 0 ? 1 : $ctrl.qty;
                };
                $scope.$watch("$ctrl.qty", function(newval) {
                    $ctrl.factor = $ctrl.qty <= 0 ? 1 : $ctrl.qty;
                });
            }
        });
    angular.module('angular-components')
        .component('wiFilterStock', {
            template: `<div class="qep-form-filter">
            <div class="text-filter">{{$ctrl.label}}</div>
            <div class="qep-form-filter-options-container">
                <div class="qep-min-max-box">
                    <span>MIN</span>
                    <input type="number" ng-model="min" ng-change="upd()" />
                    <span>MAX</span>
                    <input type="number" ng-model="max" ng-change="upd()"/>
                </div>
            </div>
        </div>`,
            bindings: {
                label: "@",
                selected: '=?',
                default: '=?'
            },
            controller: function($scope) {
                var $ctrl = this;
                $scope.min = "";
                $scope.max = "";
                $ctrl.$onInit = function() {

                    let parts = $ctrl.default != '' && $ctrl.default != undefined ? $ctrl.default.split('-') : [];
                    $scope.min = parseInt(parts[0]);
                    $scope.max = parseInt(parts[1]);
                    $scope.upd();
                };


                $scope.upd = function() {
                    let range = "";
                    let min_val = !isNaN($scope.min) && $scope.min != null ? $scope.min : "";
                    let max_val = !isNaN($scope.max) && $scope.max != null ? $scope.max : "";
                    $ctrl.selected = min_val + (max_val != "" ? "-" : "") + max_val;
                    console.log($ctrl.selected);
                }
            }
        });

})();