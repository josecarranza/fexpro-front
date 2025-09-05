<div class="wi-qep-container" ng-app="app-qep" ng-controller="ctrl">
    <div>
        <select name="presale" id="presale" ng-model="select_presale" ng-change="change_presale()">
            <option value="0">In stocks</option>
            <option value="1">Presale</option>
        </select>
    </div>
    <div class="d-flex" style="margin-bottom:15px">
        <div>
            <div class="qep-current-filters">
                <div class="qep-current-filter-item" ng-repeat="f in current_filters" ng-click="remove_filter(f.value,f.text)">
                    {{f.label}}{{f.label!=''?':':''}} {{f.text}}
                </div>
            </div>
        </div>
        <div class="text-right">
            <span class="loading-spinner" ng-show="downloading_xlsx"></span>
            <button class="qep-btn-black" type="button" ng-click="export_xlsx()" ng-disabled="downloading_xlsx">Export All to XLSX</button>
        </div>
    </div>
   
<div class="qep-filters-area d-flex">
        <div>
            <wi-filter list="model.delivery_dates" label="DELIVERY" selected="filtros_selected.meta_delivery_date"></wi-filter>
            <wi-filter list="model.lob" label="DIVISION" selected="filtros_selected.meta_lob"></wi-filter>
            <wi-filter list="model.groups" label="GROUP" selected="filtros_selected.pa_group"></wi-filter>
            <wi-filter list="model.products_type" label="PRODUCT" selected="filtros_selected.pa_product_type"></wi-filter>
            <wi-filter list="model.brands" label="BRAND" selected="filtros_selected.pa_brand"></wi-filter>
            <wi-filter list="model.genders" label="GENDER" selected="filtros_selected.pa_gender"></wi-filter>
            <wi-filter list="model.basics" label="BASICS" selected="filtros_selected.pa_only_basics" ng-show="is_presale==0" hide-search="true"></wi-filter>
            <wi-filter-stock default="model.stock" label="AVAILABLE" selected="filtros_selected.stock" ng-show="is_presale==0"></wi-filter-stock>
            
            <button class="qep-btn-black" type="button" ng-click="aplicar_filtros()">SUBMIT</button>

            <a href="/quick-easy-purchase{{is_presale==1?'?is_presale=1':''}}" class="clear-filter">Clear filters</a>
        </div>
        <div class="text-right">
             Show <select name="" id=""><option value="100">100</option></select> Entries
        </div>
    </div>
    <br>
   
    
    <table class="qep-table">
    <thead>
        <tr>
            <th>IMAGE</th>
            <th>ITEM</th>
            <th>PRICE</th>
            <th>BRAND</th>
            <th >GENDER</th>
            <th >DELIVERIES</th>
            <th ng-show="is_presale==0">AVAILABLE</th>
            <th>CART SUMMARY</th>
            <th class="text-left" style="width:192px">ADD PREPACKS TO CART</th>
            <th style="width:110px"></th>
        </tr>
    </thead>
    <tbody>
        <tr ng-if="model.products.length==0 && !getting_data">
            <td colspan="10" class="text-center">No data available in table</td>
        </tr>
        <tr ng-if="getting_data">
            <td colspan="10" class="text-center"><span class="loading-spinner"></span></td>
        </tr>
        <tr ng-repeat="row in model.products">
            
            <td width="130">
                <a href="{{row.product_url}}" target="_blank">
                <img  alt="" ng-src="{{row.image}}" />
                </a>
            </td>
            <td class="text-left"><a href="{{row.product_url}}" target="_blank" class="title-product">
                {{row.product_title}}
                </a>
                <div class="cell-data">
                    <span>SKU</span>
                    <span>{{row.sku}}</span>
                </div>
                <div class="cell-data">
                    <span>TEAM:</span>
                    <span><b>{{row.team}}</b></span>
                </div>
                <div class="cell-data">
                    <span class="popup-text">Logo application <div class="popup">{{row.logo_application || 'Data not available'}}</div></span>
                    <span class="popup-text">Composition <div class="popup">{{row.composition || 'Data not available'}}</div></span>
                </div>
            </td>
            <td>{{row.price | currency:'$'}}</td>
            <td>{{row.brand}}</td>
            <td>{{row.gender}}</td>
            <td>
                <div class="min-h extend-line {{row.stock_future==0?'no-border':''}}" ng-show="row.stock_present>0" ng-if="is_presale==0">
                    IMMEDIATE
                </div>
                <div class="min-h no-border" ng-show="row.stock_future>0">
                {{row.delivery_date | date:'MMMM d\'th\''}}
                </div>
                    
            </td>
            <td ng-show="is_presale==0">
                <div class="min-h extend-line {{row.stock_future==0?'no-border':''}}" ng-show="row.stock_present>0">
                    <span class="info-units">{{row.units_per_pack*row.stock_present}} units</span>
                </div>
                <div class="min-h no-border" ng-show="row.stock_future>0">
                    <span class="info-units">{{row.units_per_pack*row.stock_future}} units</span>
                </div>
            </td>
            <td style="vertical-align:top">
                <wi-sizes-table data="row.sizes" qty="row.qty" price="row.price" title="IMMEDIATE" ng-show="row.stock_present>0" class="{{row.stock_future==0?'no-border':''}}" ng-if="is_presale==0"></wi-sizes-table>
                <wi-sizes-table data="row.sizes" qty="row.qty_future" price="row.price" title="DELIVERY DATE: {{row.delivery_date | date:'MMMM d\'th\', y'}}" ng-show="row.stock_future>0" ng-if="is_presale==0"></wi-sizes-table>
                <wi-sizes-table data="row.sizes" qty="row.qty_future" price="row.price" title="DELIVERY DATE: {{row.delivery_date | date:'MMMM d\'th\', y'}}" ng-if="is_presale==1"></wi-sizes-table>
            </td>
            
            <td class="text-left">
                <div class="min-h {{row.stock_future==0?'no-border':''}} justify-content-start" ng-show="row.stock_present>0" ng-if="is_presale==0" >
                    <div>
                    <jh-number-picker min="0" max="{{row.stock_present}}" number="row.qty" ng-init="row.qty=0"></jh-number-picker><br>
                    <b class="sm-info">Available: {{row.stock_present}} prepacks</b><br>
                    <span class="sm-info" ng-show="row.stock_future==0">No more stock coming! Get this style before it runs out</span>
                    </div>
                </div>
                <div class="min-h no-border" ng-show="row.stock_future>0" ng-if="is_presale==0">
                    <div>
                        <jh-number-picker min="0" max="{{row.stock_future}}" number="row.qty_future" ng-init="row.qty_future=0"></jh-number-picker><br>
                        <b class="sm-info">Available: {{row.stock_future}} prepacks</b><br>
                        <span class="sm-info">Some stock is still in transit, save it now!</span>
                    </div>
                </div>
                <div class="min-h no-border" ng-if="is_presale==1">
                    <div>
                        <jh-number-picker min="0" max="9999" number="row.qty_future" ng-init="row.qty_future=0"></jh-number-picker>
                    </div>
                </div>
            </td>
            <td>
                <button class="qep-add-to-cart" ng-click="add_to_cart(row)" ng-show="!row.sending" ng-disabled="row.qty==0 && row.qty_future==0" ng-if="is_presale==0">ADD TO CART</button>
                <button class="qep-add-to-cart" ng-click="add_to_cart(row)" ng-show="!row.sending" ng-disabled="row.qty_future==0" ng-if="is_presale==1">ADD TO CART</button>
                <span class="loading-spinner" ng-show="row.sending"></span>

                <a href="<?=get_site_url()?>/cart/" class="qep-view-cart" ng-show="row.show_cart_link" target="_blank">View cart</a>
            </td>
        </tr>
    </tbody>
    </table>

    <div class="pagination">
        <span>{{pag}} of {{pages}}</span>  <button ng-click="prev()" ng-disabled="getting_data || pag==1"><i class="arrow left"></i></button> <button ng-click="next()" ng-disabled="getting_data || pag==pages"><i class="arrow right"></i></button>
    </div>
    <wi-modal show="show_modal"><div class="qep-alert-error">{{modal_text}}</div></wi-modal>
</div>
<script src="<?=WI_PLUGIN_URL."assets/js/angular.min.js"?>"></script>
<script src="<?=WI_PLUGIN_URL."assets/js/angular-components.js?v=1"?>"></script>
<script>var site_url="<?=get_site_url();?>"</script>
<script>
    angular.module("app-qep",["angular-components"]).controller("ctrl",function($scope,$http,$timeout){
        $scope.model={};
        $scope.model.categories=<?=json_encode($categories)?>;
        $scope.model.brands = <?=json_encode($brands)?>;
        $scope.model.lob = <?=json_encode($lob)?>;
        $scope.model.delivery_dates = <?=json_encode($delivery_dates)?>;
        $scope.model.genders = <?=json_encode($genders)?>;
        $scope.model.groups = <?=json_encode($groups)?>;
        $scope.model.products_type = <?=json_encode($products_type)?>;
        $scope.model.basics = <?=json_encode($basics)?>;
        $scope.model.stock = "<?=$stock?>";
        $scope.is_presale = <?=$is_presale?1:0?>;
        $scope.select_presale=$scope.is_presale?"1":"0";
        $scope.is_submit = <?=$is_submit?>;
        $scope.model.products=[];
        
        $scope.filters=[];
        $scope.current_filters=[];

        $scope.pag=1;
        $scope.pages=1;

        $scope.filtros_selected={
            product_cat:[],
            pa_brand:[],
            meta_lob:[],
            meta_delivery_date:[],
            pag:$scope.pag,
            is_presale:$scope.is_presale
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
            angular.forEach($scope.model.genders,function(item,index){
                if(item.checked){
                    $scope.current_filters.push({
                        label:"GENDER",
                        text:item.text,
                        value : item.value
                    });
                }
            });
            angular.forEach($scope.model.groups,function(item,index){
                if(item.checked){
                    $scope.current_filters.push({
                        label:"GROUP",
                        text:item.text,
                        value : item.value
                    });
                }
            });
            angular.forEach($scope.model.products_type,function(item,index){
                if(item.checked){
                    $scope.current_filters.push({
                        label:"TYPE",
                        text:item.text,
                        value : item.value
                    });
                }
            });
            if($scope.model.stock!=""){
                let _range = $scope.model.stock.split("-");
                 _range[0] = _range[0]==""?0:_range[0];
                let label = _range[0]+(_range[1]?"-":"")+(_range[1]||'');
                $scope.current_filters.push({
                    label:"STOCK",
                    text:label,
                    value : label
                });
            }
            angular.forEach($scope.model.basics,function(item,index){
                if(item.checked){
                    $scope.current_filters.push({
                        label:"",
                        text:item.text,
                        value : item.value
                    });
                }
            });
            
            console.log($scope.current_filters);
        };
        $scope.build_current_filters();

        $scope.remove_filter=function(slug,text){
            url = location.origin+location.pathname+location.search;
            if(text!='ONLY BASICS'){
                url=url.replace(slug,"");
            }else{
                url=url.replace("pa_only_basics=1","");
            }
            
            location.href=url;
        };

        $scope.get_data=function(){
            $scope.getting_data=true;
            $scope.model.products=[];
            $scope.filtros_selected.pag=$scope.pag;
            $http({
                url:site_url+"/wp-admin/admin-ajax.php?action=qep_get_products",
                method:"GET",
                params:$scope.filtros_selected
            }).then(function(response){
                if( $scope.pag==1){
                    $scope.pages=response.data.pages;
                }
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
                    product_id:item.main_id,
                    from_presale:$scope.is_presale
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
        $scope.next=function(){
            $scope.pag++;
            $scope.get_data();
        }
        $scope.prev=function(){
            $scope.pag--;
            $scope.get_data();
        };

        $scope.change_presale=function(){
            url = location.origin+location.pathname;
            if($scope.select_presale==1){
                location.href=url+"?is_presale=1";
            }else{
                location.href=url;
            }
            
        }
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
