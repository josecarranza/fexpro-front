<div class="wi-qep-container" ng-app="app-qep" ng-controller="ctrl">
    <div class="qep-current-filters">
        <div class="qep-current-filter-item" ng-repeat="f in current_filters" ng-click="remove_filter(f.value)">
            {{f.label}}: {{f.text}}
        </div>
    </div>
<div><a href="/quick-easy-purchase">Clear filters</a></div>    
<div class="qep-filters-area d-flex">
        <div>
            <wi-filter list="model.categories" label="Category" selected="filtros_selected.product_cat"></wi-filter>
            <wi-filter list="model.brands" label="Brand" selected="filtros_selected.pa_brand"></wi-filter>
            <wi-filter list="model.lob" label="LOB" selected="filtros_selected.meta_lob"></wi-filter>
            <wi-filter list="model.delivery_dates" label="Delivery Date" selected="filtros_selected.meta_delivery_date"></wi-filter>
            <button class="qep-btn-black" type="button" ng-click="aplicar_filtros()">Submit</button>
        </div>
        <div class="text-right">
            <span class="loading-spinner" ng-show="downloading_xlsx"></span>
            <button class="qep-btn-black" type="button" ng-click="export_xlsx()" ng-disabled="downloading_xlsx">Export All to XLSX</button>
        </div>
    </div>
    <br>
    <br>
    <div class="d-flex qep-table-filters">
        <div class="col-2">
            Show <select name="" id=""><option value="100">100</option></select> Entries
        </div>
        <div class="col-4">
            <input type="text" placeholder="Search...">
        </div>
        <div></div>
    </div>
    <br>
    <table class="qep-table">
    <thead>
        <tr>
            
            <th>IMAGE</th>
            <th>PRODUCT TITLE</th>
            <th>PRODUCT SKU</th>
            <th>GENDER</th>
            <th>BRAND</th>
            <th>PRICE</th>
            <th>STOCK</th>
            <th>QTY</th>
            <th style="width:135px">ACTION</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-if="model.products.length==0 && !getting_data">
            <td colspan="9" class="text-center">No data vailable in table</td>
        </tr>
        <tr ng-if="getting_data">
            <td colspan="9" class="text-center"><span class="loading-spinner"></span></td>
        </tr>
        <tr ng-repeat="row in model.products">
            
            <td width="130">
                <a href="{{row.product_url}}" target="_blank">
                <img  alt="" ng-src="{{row.image[0]}}" second-image="{{row.image[1]}}"  />
                </a>
            </td>
            <td><a href="{{row.product_url}}" target="_blank">
                {{row.product_title}}
                </a>
                <table class="qep-table-sub">
                    <tr>
                        <td rowspan="2" class="v-align-middle"><b>Sizes</b></td>
                        <th ng-repeat="s in row.sizes">{{s.size}}</th>
                        <th>Total</th>
                    </tr>
                    <tr>
                        <td ng-repeat="s in row.sizes" ng-init="row.total=row.total+s.value">{{s.value}}</td>
                        <td><b>{{row.total}}</b></td>
                    </tr>
                </table>
            </td>
            <td>{{row.sku}}</td>
            <td>{{row.gender}}</td>
            <td>{{row.brand}}</td>
            <td>{{row.price | currency:'$'}}</td>
            <td><b>Available:</b> {{row.stock_present}}<br><b>Future:</b> {{row.stock_future}}</td>
            
            <td>
            <b>Available</b><br>
            <jh-number-picker min="0" max="{{row.stock_present}}" number="row.qty" ng-init="row.qty=0"></jh-number-picker>
            <br>
            <b>Future</b><br>
            <jh-number-picker min="0" max="{{row.stock_future}}" number="row.qty_future" ng-init="row.qty_future=0"></jh-number-picker>
            </td>
            <td>
                <button class="qep-add-to-cart" ng-click="add_to_cart(row)" ng-show="!row.sending" ng-disabled="row.qty==0 && row.qty_future==0">ADD TO CART</button>
                <span class="loading-spinner" ng-show="row.sending"></span>

                <a href="<?=get_site_url()?>/cart/" class="qep-view-cart" ng-show="row.show_cart_link" target="_blank">View cart</a>
            </td>
        </tr>
    </tbody>
    </table>
    <wi-modal show="show_modal"><div class="qep-alert-error">{{modal_text}}</div></wi-modal>
</div>
<script src="<?=WI_PLUGIN_URL."assets/js/angular.min.js"?>"></script>
<script src="<?=WI_PLUGIN_URL."assets/js/angular-components.js"?>"></script>
<script>var site_url="<?=get_site_url();?>"</script>
<script>
    angular.module("app-qep",["angular-components"]).controller("ctrl",function($scope,$http,$timeout){
        $scope.model={};
        $scope.model.categories=<?=json_encode($categories)?>;
        $scope.model.brands = <?=json_encode($brands)?>;
        $scope.model.lob = <?=json_encode($lob)?>;
        $scope.model.delivery_dates = <?=json_encode($delivery_dates)?>;
        $scope.is_submit = <?=$is_submit?>;
        $scope.model.products=[];
        
        $scope.filters=[];
        $scope.current_filters=[];

        $scope.filtros_selected={
            product_cat:[],
            pa_brand:[],
            meta_lob:[],
            meta_delivery_date:[]
        };

        $scope.getting_data=false;
        $scope.show_modal=false;
        $scope.modal_text="";

        $scope.aplicar_filtros=function(){
            url = location.origin+location.pathname;
            location.href=url+"?submit=1&"+jQuery.param($scope.filtros_selected);
        };

        $scope.build_current_filters=function(){
            angular.forEach($scope.model.categories,function(cat,index){
                if(cat.checked){
                            $scope.current_filters.push({
                                label:"Category",
                                text:cat.text,
                                value : cat.value
                            });
                        }
                angular.forEach(cat.items,function(subcat1,index1){
                    if(subcat1.checked){
                            $scope.current_filters.push({
                                label:"Category",
                                text:subcat1.text,
                                value : subcat1.value
                            });
                        }
                    angular.forEach(subcat1.items,function(subcat2,index2){
                        if(subcat2.checked){
                            $scope.current_filters.push({
                                label:"Category",
                                text:subcat2.text,
                                value : subcat2.value
                            });
                        }
                    });
                });
            });
            angular.forEach($scope.model.brands,function(item,index){
                if(item.checked){
                    $scope.current_filters.push({
                        label:"Brand",
                        text:item.text,
                        value : item.value
                    });
                }
            });
            angular.forEach($scope.model.lob,function(item,index){
                if(item.checked){
                    $scope.current_filters.push({
                        label:"LOB",
                        text:item.text,
                        value : item.value
                    });
                }
            });
            angular.forEach($scope.model.delivery_dates,function(item,index){
                if(item.checked){
                    $scope.current_filters.push({
                        label:"Delivery",
                        text:item.text,
                        value : item.value
                    });
                }
            });
            console.log($scope.current_filters);
        };
        $scope.build_current_filters();

        $scope.remove_filter=function(slug){
            url = location.origin+location.pathname+location.search;
            url=url.replace(slug,"");
            location.href=url;
        };

        $scope.get_data=function(){
            $scope.getting_data=true;
            $http({
                url:site_url+"/wp-admin/admin-ajax.php?action=qep_get_products",
                method:"GET",
                params:$scope.filtros_selected
            }).then(function(response){
                if(response.data.products){
                    $scope.model.products=response.data.products;
                }else{
                    $scope.model.products=[];
                }
                $scope.getting_data=false;
            },function(){
                $scope.getting_data=false;
            });
        }
        $timeout(function(){
            if($scope.is_submit==1){
                $scope.get_data();        
            }
            
        },150);

        $scope.export_xlsx=function(){
            $scope.downloading_xlsx=true;
            $http({
                url:site_url+"/wp-admin/admin-ajax.php?action=qep_get_products_export",
                method:"GET",
                params:$scope.filtros_selected
            }).then(function(response){
                if(response.data.error==0){
                    window.open(response.data.download, '_blank');
                }
                $scope.downloading_xlsx=false;
            },function(){
                $scope.downloading_xlsx=false;
            });

            /*params_str = jQuery.param($scope.filtros_selected);
            $scope.url_export = site_url+"/wp-admin/admin-ajax.php?action=qep_get_products_export&"+params_str;
            downloadUsingFetch($scope.url_export,function(){
                $scope.isDownload=false;
                $scope.$apply();
            });*/

        }

        $scope.add_to_cart=function(item){

            item.sending=true;
            
            $http({
                //url:site_url+"?wc-ajax=add_to_cart",
                url:site_url+"/wp-admin/admin-ajax.php?action=qep_add_to_cart",
                method:"POST",
                headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'},
                data:jQuery.param({
                    quantity:item.qty,
                    quantity_future:item.qty_future,
                    product_sku:item.sku,
                    variation_id:item.id,
                    product_id:item.main_id
                })
            }).then(function(response){
                item.sending=false;
                if(response.data.error==0){
                    item.qty=0;
                    item.qty_future=0;
                    item.show_cart_link=true;
                }else{
                    $scope.modal_text=response.data.message;
                    $scope.show_modal=true;
                }
            },function(){
                item.sending=false;
            });
            
        };
        $scope.imageNoExists =  function(image_url){
                return $q(function(resolve, reject) {
                    $http.get(image_url).then(function(){
                        //reject(false);
                    },function(){
                        resolve(true);
                    });
                });
            };
        
        
    }).directive('secondImage',['$q','$http','$timeout',function($q,$http,$timeout){
        return {
            restrict : "A",
            scope:{secondImage:"@"},
            link:function(scope,elem,attr){
                //console.log(scope.secondImage);

                scope.imageNoExists =  function(image_url){
                    
                return $q(function(resolve, reject) {
                    if(image_url==undefined){
                        resolve(true);
                        return;
                    }
                    $http.get(image_url).then(function(){
                        //reject(false);
                    },function(){
                        resolve(true);
                        });
                    });
                };

                $timeout(function(){
                        scope.imageNoExists(angular.element(elem).attr("src")).then(function(){
                        angular.element(elem).attr("src",scope.secondImage);
                    });
                });

            }
        }
    }]);

    
</script>
