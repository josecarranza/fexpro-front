<?php 
//if(!isset($_GET["dev"]))
//    return;
//echo $product_id;
global $wpdb;
$sql_variaciones="SELECT a.*, pm1.meta_value thumbnail_id, pm2.meta_value image ,
(SELECT group_concat(concat(b.meta_key,'||',b.meta_value) SEPARATOR '///')  FROM wp_postmeta b WHERE b.post_id=a.ID ) metas
FROM wp_posts a 
LEFT JOIN wp_postmeta pm1 ON (pm1.post_id=a.ID AND pm1.meta_value IS NOT NULL AND pm1.meta_key='_thumbnail_id')
LEFT JOIN wp_postmeta pm2 ON (pm2.post_id=pm1.meta_value AND pm2.meta_key='_wp_attached_file')
WHERE a.post_status IN ('publish')
AND a.post_parent=".$product_id;
$variaciones = $wpdb->get_results($sql_variaciones);
$var_data=array();
$url_image = get_site_url()."/wp-content/uploads/";

$u =  wp_get_current_user();
$rol = isset($u->roles[0])?$u->roles[0]:array();

foreach ($variaciones as $key => $var) {
                     
    $metas_variation = explode("///",$var->metas);
    $metas_variation = array_map(function($row){
        $f = explode("||",$row);
        return array("key"=>$f[0],"value"=>$f[1]);
    },$metas_variation);
    $metas_variation=array_column($metas_variation,"value","key");
  
    $color = preg_replace('/[0-9]+/', '',str_replace("Color: ","",$var->post_excerpt));
    //$pa_color = strtolower(str_replace("Color: ","",$var->post_excerpt));
    $pa_color = isset($metas_variation["attribute_pa_color"]) ? $metas_variation["attribute_pa_color"]:"";

    $variation_id = $var->ID;
    $tmp=explode('.', $var->image);
    $extension = end($tmp);
    $extension = strtolower($extension)=="jfif"?"jpg":$extension;
    $image = (str_replace(".".$extension,"-150x150.".$extension,$var->image));//$base_url
    $image2 = (str_replace(".".$extension,"-300x300.".$extension,$var->image));//$base_url
    //$image = wp_get_attachment_image_src(get_post_thumbnail_id($variation_id), 'thumbnail');
    //$_product =  wc_get_product( $variation_id);

    $item=array();
    $item["id"] = $variation_id;
    $item["main_id"] = $product_id;
    $item["image"] = $image!=""?array($url_image.$image,$url_image.$image2):"";

    $item['sku'] =  isset($metas_variation["_sku"])?$metas_variation["_sku"]:"";

    $item['product_title'] =  $var->post_title;// . " - " . $color ;
    

    $item['color'] =  $color ;
    $item["pa_color"] = $pa_color;

    //$item['Season'] =  isset($main_atts["pa_season"])?$main_atts["pa_season"]:"";
    $price=isset($metas_variation["_price"])?$metas_variation["_price"]:0;
    if($rol!="administrator"){
        $price_ = role_price_get_by_id($variation_id,$rol);
        $price = $price_!==null?$price_:$price;
    }
    
    $item['price'] =  $price;

    $item['Division'] =  "";

    $item['brand'] =  isset($main_atts["pa_brand"])?$main_atts["pa_brand"]:""; 
    $item['gender'] =  isset($main_atts["pa_gender"])?$main_atts["pa_gender"]:"";
    
    $item['stock_present'] =  isset($metas_variation["_stock_present"]) ? (int)$metas_variation["_stock_present"]:0;
    $item['stock_future'] =  isset($metas_variation["_stock_future"]) ? (int)$metas_variation["_stock_future"]:0;
    $item["product_team"] = isset($metas_variation["product_team"]) ? $metas_variation["product_team"]:"";

    $item["variation_name"] = ($item["product_team"]!=""?$item["product_team"]." - ":"").$color;

    $sizes=array();
    for($i=1;$i<=10;$i++){
        if(isset($metas_variation["custom_field".$i]) && $metas_variation["custom_field".$i]!=""){
            $sizes[]=array("size"=>$metas_variation["custom_field".$i],"value"=>isset($metas_variation["size_box_qty".$i])?(int)$metas_variation["size_box_qty".$i]:"0");
        }
    }
    $item["sizes"]=$sizes;

    $var_data[]=$item;
}
/*
echo "<pre>";
print_r($var_data);
echo "</pre>";
*/
?>
<style>
    .variations-list{
        margin-bottom:15px;
    }
    .var-item-stock{
        border:1px solid #000;
        display:inline-block;
        font-size:13px;
        vertical-align:top;
    }
    .var-item-stock-name{
        background:#000;
        color:#fff;
        font-weight:bold;
        font-size:13px;
        padding:6.5px;
        line-height:20px;
        text-transform: uppercase;
    }
    .var-item-stock-image{
        height:64px;
        border-bottom:1px solid #000;
    }
    .var-item-stock-image img{
        height:100%;
        cursor: pointer;
    }
    .var-item-stock-row{
        display:flex;
        position: relative;
        align-items: stretch;
    }
    .var-item-stock-row .var-item-stock-col{
        flex:1;
        color:#000;
        padding:6.5px;
        border-right:1px solid #000;
        border-bottom:1px solid #000;
        text-align:center;
        align-items: stretch;
    }
    .var-item-stock-row .var-item-stock-col:last-child{
        border-right:0px;
    }
    .var-item-stock .bg-green{
        background:#358d37;
        color:#fff;
    }
    .var-item-stock .bg-grey{
        background:#7f7f7f;
        color:#fff;
    }
    .text-left{
        text-align:left;
    }
    .var-item-stock-row.row-totals{
        background:#a2a2a2;
        color:#fff;
        min-height:47px;
    }
    .row-totals input[type='number']{
        appearance:auto!important;
        border:2px solid #000;
        padding:5px;
        font-size:14px;
        color:#000;
        width: 65px;
    }
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button{
        -webkit-appearance: auto!important;
        opacity: 1!important;
    }
    .var-item-stock-table td,
    .var-item-stock-table th{
        border-bottom:1px solid #000;
        border-right:1px solid #000;
        color:#000;
        text-align:center;
        padding:5px 10px;
        
    }
    .var-item-stock-table td:last-child,
    .var-item-stock-table th:last-child{
        border-right:0px;
    }
    .var-item-stock-table.bg-grey td,
    .var-item-stock-table.bg-grey th{
        background:#7f7f7f;
        color:#fff;
    }
    .row-totals .var-item-stock-col{
        border:none;
        background:#a2a2a2;
    }
    .row-totals .text-white{
        font-size: 18px;
    display: inline-block;
    vertical-align: middle;
    line-height:34px;
    }
    .variations-totals{
        font-size:16px;
        color:#000;
    }
    .div-float-right{
        display:inline-block;
        float:right;
    }
    .disable-add-to-cart{
        background: rgba(0,0,0,0.5) !important;
        cursor: not-allowed !important;
    }
    .loading-spinner {
    display: inline-block;
    width: 50px;
    height: 50px;
    vertical-align: middle;
    }

    .loading-spinner:after {
        content: " ";
        display: block;
        width: 80%;
        height: 80%;
        margin: 10%;
        border-radius: 50%;
        border: 3px solid #c0c0c0;
        border-color: #c0c0c0 transparent #c0c0c0 transparent;
        animation: loading-spinner 1.2s linear infinite;
    }

    @keyframes loading-spinner {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    
</style>
<div ng-app="app_var" ng-controller="ctrl">
    <div class="variations-list">
        <wi-product-variable-stock ng-repeat="var in model.variations" data="var" type-stock="future"></wi-product-variable-stock>
    </div>
    <div>
        <div class="variations-totals" style="display:inline-block">
            <label for="">Total Units: {{total_units}}</label><br>
            <label for="">Total: {{total_amount | currency:'$'}}</label>
           
        </div>
        <div class="div-float-right">
                <span class="loading-spinner" ng-show="sending_to_cart" ></span>
                <button class="single_add_to_cart_button button alt {{!ready_to_add?'disable-add-to-cart':''}}" ng-disabled="!ready_to_add || sending_to_cart" ng-click="add_to_cart()">Add to cart</button>
        </div>
    </div>
</div>
<script src="<?=get_site_url()?>/wp-content/themes/porto-child/angular.min.js"></script>
<script>
    angular.module("single_product",[])
    .component('wiProductVariableStock',{
        template:`<div class="var-item-stock">
        <div class="var-item-stock-name">{{$ctrl.data.variation_name}}</div>
        <div class="var-item-stock-image">
            <img ng-src="{{$ctrl.data.image}}" alt="" ng-click="show_gallery($ctrl.data.pa_color)" >
        </div>
        <div>
            <div class="var-item-stock-row">
                <div class="var-item-stock-col text-left"><b>Sizes</b></div>
            </div>
            <table class="var-item-stock-table">
                <tr>
                    <th ng-repeat="size in $ctrl.data.sizes">{{size.size}}</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td ng-repeat="size in $ctrl.data.sizes">{{size.value*$ctrl.factor}}</td>
                    <td><b>{{$ctrl.data.total_sizes}}</b></td>
                </tr>
            </table>
            
            <div class="var-item-stock-row">
                <div class="var-item-stock-col bg-green text-left"><b>Open Stock</b></div>
            </div>
            <table class="var-item-stock-table bg-grey">
                <tr>
                    <th ng-repeat="size in $ctrl.data.sizes">{{size.size}}</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td ng-repeat="size in $ctrl.data.sizes">{{size.max_value}}</td>
                    <td><b>{{$ctrl.data.total_size_max}}</b></td>
                </tr>
            </table>
            <div class="var-item-stock-row row-totals">
                <div class="var-item-stock-col" ng-show="$ctrl.data.stock_present>0">
                    <input type="number" step="1" min="0" max="{{$ctrl.data.stock_present}}" size="4" inputmode="numeric" value="0" ng-change=""  ng-model="$ctrl.data.qty" ng-change="$ctrl.updata_totals()"> 
                </div>
                <div class="var-item-stock-col" ng-show="$ctrl.data.stock_present>0">
                    <span class="text-white">{{$ctrl.data.price | currency:'$'}}</span>
                </div>
            </div>
        </div>
        <div>
            <div class="var-item-stock-row">
                <div class="var-item-stock-col text-left"><b>Sizes</b></div>
            </div>
            <table class="var-item-stock-table">
                <tr>
                    <th ng-repeat="size in $ctrl.data.sizes">{{size.size}}</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td ng-repeat="size in $ctrl.data.sizes">{{size.value*$ctrl.factor_future}}</td>
                    <td><b>{{$ctrl.data.total_sizes_future}}</b></td>
                </tr>
            </table>
            <div class="var-item-stock-row">
                <div class="var-item-stock-col bg-green text-left"><b>Future Stock</b></div>
            </div>
            <table class="var-item-stock-table bg-grey">
                <tr>
                    <th ng-repeat="size in $ctrl.data.sizes">{{size.size}}</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td ng-repeat="size in $ctrl.data.sizes">{{size.max_value_future}}</td>
                    <td><b>{{$ctrl.data.total_size_max_future}}</b></td>
                </tr>
            </table>
            <div class="var-item-stock-row row-totals">
                <div class="var-item-stock-col" ng-show="$ctrl.data.stock_future>0">
                    <input type="number" step="1" min="0" max="{{$ctrl.data.stock_future}}" size="4" inputmode="numeric" value="0" ng-change=""  ng-model="$ctrl.data.qty_future" ng-change="$ctrl.updata_totals()"> 
                </div>
                <div class="var-item-stock-col" ng-show="$ctrl.data.stock_future>0">
                    <span class="text-white">{{$ctrl.data.price | currency:'$'}}</span>
                </div>
            </div>
        </div>
    </div>`,
        bindings:{
            data:"=",
            typeStock:"@"
        },
        controller:function($scope,$rootScope,$q,$http){
            var $ctrl=this;
            
           
            $ctrl.factor=0;
            $ctrl.$onInit = function () {
                
                console.log($ctrl.data);
                $ctrl.data.qty=0;
                $ctrl.data.total_amount=0;
                
                $ctrl.data.qty_future=0;
                $ctrl.data.total_amount_future=0;
                
                $ctrl.max_stock=$ctrl.typeStock=="future"?$ctrl.data.stock_future:$ctrl.data.stock_present;

                $ctrl.data.total_sizes=0;
 

                for(i in $ctrl.data.sizes){
                    $ctrl.data.total_sizes+=$ctrl.data.sizes[i].value;
                    $ctrl.data.sizes[i].max_value=$ctrl.data.sizes[i].value*$ctrl.data.stock_present;

                    $ctrl.data.sizes[i].max_value_future=$ctrl.data.sizes[i].value*$ctrl.data.stock_future;
              
                }
    
                $ctrl.data.total_size_max=$ctrl.data.total_sizes*$ctrl.data.stock_present;
                $ctrl.data.total_size_max_future=$ctrl.data.total_sizes*$ctrl.data.stock_future;

                if($ctrl.data.image.length>1){
                    var images = $ctrl.data.image.length > 1 ? $ctrl.data.image:"";
                    $ctrl.data.image=images[0];
                    /*if(!$scope.imageExists(images[0])){
                        $ctrl.data.image=images[1];
                    }*/
                    $scope.imageNoExists(images[0]).then(function(r){
                            $ctrl.data.image=images[1];
                    });
                }
                


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

            $ctrl.updata_totals=function(){
                
            };
            $scope.calcule_totals=function(){
                $ctrl.data.total_sizes=0;
                $ctrl.data.total_amount=0;

                $ctrl.data.total_sizes_future=0;
                $ctrl.data.total_amount_future=0;


                $ctrl.factor = $ctrl.data.qty==0?1:$ctrl.data.qty;
                $ctrl.factor_future = $ctrl.data.qty_future==0?1:$ctrl.data.qty_future;

                for(i in $ctrl.data.sizes){
                    $ctrl.data.total_sizes+=$ctrl.data.sizes[i].value*$ctrl.factor;
                    $ctrl.data.total_sizes_future+=$ctrl.data.sizes[i].value*$ctrl.factor_future;
                }

                $ctrl.data.total_amount= $ctrl.data.total_sizes * $ctrl.data.price;
                $ctrl.data.total_amount_future= $ctrl.data.total_sizes_future * $ctrl.data.price;
                $rootScope.update_totales();
            }

            $scope.$watch("$ctrl.data.qty",function(val1,val2){
                $scope.calcule_totals();
            });
            $scope.$watch("$ctrl.data.qty_future",function(val1,val2){
                $scope.calcule_totals();
            });
            $scope.show_gallery=function(color){
                //color=color.toLowerCase().replace(" ","-");
                //console.log(color);
                jQuery('.variations_form.cart .filter-item-list a[data-value="' + color + '"]').click();
            }
        }
    });
</script>
<script>
    var site_url="<?=get_site_url()?>";
    angular.module("app_var",["single_product"]).controller("ctrl",function($scope,$rootScope,$http){
        $scope.model={};
        $scope.model.variations=<?=json_encode($var_data)?>;
        
        $scope.ready_to_add=false;
        
        $scope.total_units=0;

        $scope.local_cart=[];

        $scope.product_id="<?=$product_id?>";

        $scope.sending_to_cart=false;
     

        $rootScope.update_totales=function(){
            $scope.total_units=0;
            $scope.total_amount=0;

            $scope.local_cart={};
            
            angular.forEach($scope.model.variations,function(variation,i){
                $scope.local_cart[variation.id]={};
                if(variation.qty>0){
                    $scope.total_units+=variation.total_sizes;
                    $scope.total_amount+=variation.total_amount;
                    $scope.local_cart[variation.id]["qty"] = variation.qty;
                }

                if(variation.qty_future>0){
                    $scope.total_units+=variation.total_sizes_future;
                    $scope.total_amount+=variation.total_amount_future;

                    $scope.local_cart[variation.id]["qty_future"] = variation.qty_future;
                }
                    
            });
            if($scope.total_units>0){
                $scope.ready_to_add=true;
            }else{
                $scope.ready_to_add=false;
            }
            
            
        };

        $scope.add_to_cart=function(){
            $scope.sending_to_cart=true;

            $http({
                url:site_url+"/wp-admin/admin-ajax.php?action=qep_add_to_cart_bulk",
                method:"POST",
                data:{cart:$scope.local_cart,product_id:$scope.product_id}
            }).then(function(response){
               location.reload();
            },function(){
                location.reload();
            });
        
        }

        
      
    });
</script>