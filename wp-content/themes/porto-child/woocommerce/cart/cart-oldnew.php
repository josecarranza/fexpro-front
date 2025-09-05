<?php 
if(isset($_GET["oldlayout"])){
    include("cart-old.php");
    return;
}
defined( 'ABSPATH' ) || exit;

prefix_update_existing_cart_item_meta();
$return_array1 = array();
global $wpdb;
do_action( 'woocommerce_before_cart' );

$u =  wp_get_current_user();
$rol = isset($u->roles[0])?$u->roles[0]:array();

$discounts = discount_by_rol_margin($u->ID);

$data_cart=array();
$items=WC()->cart->get_cart();
foreach($items as $key => $item){
    $tmp=array();
    $tmp["key"] = $key;
    $tmp["type_stock"] = isset($item["type_stock"]) && $item["type_stock"] == "future" ? "future":"present";
    $tmp["sizes"] = $item["variation_size"];
    $tmp["is_presale"] = isset($item["is_presale"]) && $item["is_presale"]==1?true:false;


    $tmp["product_id"] = $item["product_id"];
    $tmp["variation_id"] = $item["variation_id"];
    $tmp["pa_color"] =  isset($item["variation"]["attribute_pa_color"])?$item["variation"]["attribute_pa_color"]:"";

    $tmp["qty"] = $item["quantity"];
    $tmp["total"] = $item["line_total"];

    $product = wc_get_product($item["variation_id"]);
    $metas = get_post_meta($item["variation_id"]);

    $img = wp_get_attachment_image_src(get_post_thumbnail_id($item["variation_id"]), 'thumbnail');

    $tmp["image"] = isset($img[0])?$img[0]:"";
    $tmp["title"] = $product->get_name();
    $tmp["link_remove"] =addslashes( wc_get_cart_remove_url( $key ));
    $tmp["link_product"] = $product->get_permalink();
   
    $delivery=isset($metas["delivery_date"][0])?$metas["delivery_date"][0]:"";
    if($tmp["is_presale"]){
        $delivery=isset($metas["presale_delivery_date"][0])?$metas["presale_delivery_date"][0]:"";
        
    }

    if($delivery!=""){
        $dd= str_replace("/","-",$delivery);
        $dd= explode("-",$dd);
        if(count($dd)==3){
            $delivery=str_pad($dd[2], 2, "0", STR_PAD_LEFT)."-".str_pad($dd[1], 2, "0", STR_PAD_LEFT)."-".$dd[0];
        }
    }
    $tmp["delivery_date"] = $delivery;

    $tmp["stock_present"] = isset($metas["_stock_present"][0])?(int)$metas["_stock_present"][0]:0;
    $tmp["stock_future"] = isset($metas["_stock_future"][0])?(int)$metas["_stock_future"][0]:0;
    $tmp["sku"] = isset($metas["_sku"][0])?$metas["_sku"][0]:"";

    $get_productBrand = get_the_terms($item["product_id"],'pa_brand');
    
    $tmp["brand"] = isset($get_productBrand[0]->name)?$get_productBrand[0]->name:"others";
    $tmp["pa_brand"] = isset($get_productBrand[0]->slug)?$get_productBrand[0]->slug:"others";

    $price = $product->get_price();
    $price_ = role_price_get_by_id($item["variation_id"],$rol);
    $price = $price_!==null?$price_:$price;
    if(in_array($rol,array("custom_role_mexico1","custom_role_mexico2")) && $discounts["margin"]!=0){
        $_margin = $price - ($price * ($discounts["margin"]/100));
        $iva = 1+($discounts["iva"]/100);
        $final=$_margin / $iva;
        $price=$final;
        
    }
    $tmp["price"] = $price;

    $data_cart[]=$tmp;
    
}
/*echo "<pre>";
print_r($data_cart);
echo "</pre>";*/
?>
<link rel="stylesheet" id="cart-style-css" href="<?=get_site_url()?>/wp-content/themes/porto-child/woocommerce/cart/cart-styles.css?ver=6.0" media="all">
<div ng-app="app_cart" ng-controller="ctrl" style="width:100%">
<?php do_action( 'woocommerce_before_cart_table' ); ?>
<?php do_action( 'woocommerce_before_cart_contents' ); ?>
<?php do_action( 'woocommerce_cart_contents' ); ?>
<div class="row">
    <div class="col-9">
        <div class="wi-cart">
            <div class="wi-cart-sidebar">
                <div class="wi-cart-tab static">
                    <label for="">Summary by</label>
                    <select name="" id="" ng-model="type_group" ng-options="item as item.label for item in type_group_list track by item.id" ng-change="change_group_by()">

                    </select>
                </div>
                <div class="wi-cart-tab {{filter_group=='all'?'active':''}} d-flex align-items-center justify-content-center" ng-click="set_filter_group('all')">
                    <label for="">ALL PRODUCTS</label>
                    <div class="separator"></div>
                </div>
                <div class="wi-cart-tab {{filter_group==resumen.key?'active':''}}" ng-repeat="resumen in model.resumen_list | customSorter:'key'" ng-click="set_filter_group(resumen.key)" ng-show="resumen.total_items > 0">
                    <label for="">{{resumen.title}}</label>
                    <div class="resumen-units">
                        <span>{{resumen.total_items}} Items</span>
                        <span>{{resumen.total_units}} Units</span>
                    </div>
                    <label for="" class="lbl-totals"><b>Brand subtotals:</b> {{resumen.sub_total | currency:'$'}}</label>
                    <div class="separator"></div>
                </div>
               
            </div>
            <div class="wi-cart-content">
                    <div class="group-stock" ng-repeat="group in model.groups  | customSorter:'key'" ng-if="group.items.length>0">
                        <label class="title-group-stock" ng-click="toggle_group(group,$event)">{{group.title}}  <span class="green-title" ng-show="group.subtitle!=null">{{group.subtitle}}</span><span class="ico-sign" ng-show="group.group_open">+</span> <span class="ico-sign" ng-show="!group.group_open">-</span></label>
                        <div class="group-totals">
                            <b>Delivery subtotal:</b> {{group.total | currency:'$'}} <span>{{group.total_qty}} Items</span><span>{{group.total_units}} Units</span>
                        </div>
                        <div class="wi-list-items-cart" >
                            <wi-product-cart-item ng-repeat="var in group.items" data="var" type-stock="var.type_stock" presale="var.is_presale"></wi-product-cart-item>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="info-update-cart" ng-show="need_update">
            Units per size will be processed and showed to you once you update your shopping cart
        </div>
        <div class="wi-cart-options">
            <div class="row">
                <div class="col-6">
                    <form action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="POST" class="woocommerce-cart-form">
                    <input type="hidden" name="cart[{{item.key}}][qty]" value="{{item.qty}}" ng-repeat="item in model.cart_items" >
                  
                    <input type="hidden" name="update_cart" value="Update Cart">
                    <?php do_action( 'woocommerce_cart_actions' ); ?>
                    <?php wp_nonce_field('woocommerce-cart',"woocommerce-cart-nonce")?>
                    
                    </form>
                    <a href="javascript:void(0)" ng-click="submit_cart()"><span class="ico-update"></span> Update cart</a>
                </div>
                
                <div class="col-6 text-right">
                    <a href="/cart/?empty_cart=yes"><span class="ico-remove"></span> Empty Cart</a>
                </div>
            </div>
        </div>
        <div class="cart-collaterals">
            <?php
                /**
                 * Cart collaterals hook.
                 *
                 * @hooked woocommerce_cross_sell_display
                 * @hooked woocommerce_cart_totals - 10
                 */
                do_action( 'woocommerce_cart_collaterals' );
            ?>
        </div>
    </div>
</div>
</div>
<?php do_action( 'woocommerce_after_cart' ); ?>
<script src="<?=get_site_url()?>/wp-content/themes/porto-child/angular.min.js"></script>
<script src="<?=get_site_url()?>/wp-content/themes/porto-child/woocommerce/cart/cart-components.js?ver=6.0"></script>
<script>
    angular.module("app_cart",["component_variations"]).controller("ctrl",function($scope,$rootScope,$timeout,$filter){
        $scope.model={};
        $scope.model.cart_items=<?=json_encode($data_cart)?>;
        $scope.model.cart_items_base = angular.copy($scope.model.cart_items);

        $scope.model.groups={};
        $scope.model.resumen_list={};
        $scope.type_group_list=[{id:'brand',label:"Brand"},{id:'delivery',label:"Delivery"}];
        $scope.type_group=$scope.type_group_list[0];
        $scope.filter_group="all";
        $scope.need_update = false;

        $scope.dformat=function(d){
            return d.split("-").reverse().join("-");
        };
        
        $scope.sort=function(obj) {
            return Object.keys(obj).sort().reduce(function (result, key) {
                result[key] = obj[key];
                return result;
            }, {});
        }

        $scope.build_resumen=function(){
            $scope.model.resumen_list={};
            if($scope.type_group.id=="delivery"){
                var resumen_immediate={
                    key:'immediate',
                    title:"IMMEDIATE",
                    total_items:0,
                    total_units:0,
                    sub_total:0
                };
                var resumen_others={
                    key:'others',
                    title:"(Undefined)",
                    total_items:0,
                    total_units:0,
                    sub_total:0
                };

                var resumen_items={};
                angular.forEach($scope.model.cart_items,function(item,key){

                    if(item.type_stock=="future"){
                       let date_month=$filter("date")($scope.dformat(item.delivery_date),'yMM');
                       

                       if(item.delivery_date!="" && resumen_items[date_month]==undefined){
                        resumen_items[date_month]={
                               key:date_month,
                               title: ($filter("date")($scope.dformat(item.delivery_date),"MMM y")), //"MMM d'th' y"
                               total_items:0,
                               total_units:0,
                               sub_total:0
                        };
                       }
                   }

                });

                resumen_items = $scope.sort(resumen_items);
                $scope.model.resumen_list=Object.assign({immediate:resumen_immediate},resumen_items,{others:resumen_others});
            }
            if($scope.type_group.id=="brand"){
                var resumen_others={
                    key:'others',
                    title:"(No brand)",
                    total_items:0,
                    total_units:0,
                    sub_total:0,
                };
                var resumen_items={};
                angular.forEach($scope.model.cart_items,function(item,key){
                    if(item.pa_brand!="others" && resumen_items[item.pa_brand]==undefined){
                        resumen_items[item.pa_brand]={
                                key:item.pa_brand,
                                title:item.brand,
                                total_items:0,
                                total_units:0,
                                sub_total:0
                        };
                    }

                    //console.log(item);
                });
                resumen_items=$scope.sort(resumen_items);
                $scope.model.resumen_list=Object.assign(resumen_items,{others:resumen_others});
            }

           console.log( $scope.model.resumen_list);

        }
        //$timeout(function(){console.log($scope.model.cart_items)},2000);

        $scope.group_by=function(){
            $scope.model.groups={};
            if($scope.type_group.id=="brand"){
                var groups={};
                var item_immediate={
                    key:'immediate',
                    title:"Immediate Stock",
                    total_qty:0,
                    total_units:0,
                    total:0,
                    items:[]
                }
                var item_others={
                    key:'others',
                    title:"Future Stock",
                    subtitle:"Delivery date: Undefined",
                    total_qty:0,
                    total_units:0,
                    total:0,
                    items:[]
                }
                angular.forEach($scope.model.cart_items,function(item,key){
                    
                    if( (item.type_stock=="present" && $scope.filter_group=='all') ||
                        (item.type_stock=="present" && $scope.filter_group==item.pa_brand)){
                        item_immediate.items.push(item);
                    }
                    if( (item.type_stock=="future" && $scope.filter_group=='all') ||  (item.type_stock=="future" && $scope.filter_group==item.pa_brand) ){
                        let date_month=$filter("date")($scope.dformat(item.delivery_date),'yMM');
                        if(item.delivery_date!="" && groups[date_month]==undefined){
                            groups[date_month]={
                                key:date_month,
                                title:"Future Stock",
                                subtitle:"Delivery date: "+($filter("date")($scope.dformat(item.delivery_date),"MMMM y")),//"MMMM d'th', y"
                                items:[]
                            }
                        }
                        if(item.delivery_date!=""){
                            groups[date_month].items.push(item);
                        }else{
                            item_others.items.push(item);
                        }
                    }
                });

                groups = $scope.sort(groups);

                $scope.model.groups=Object.assign({immediate:item_immediate},groups,{others:item_others});
            
            }
            if($scope.type_group.id=="delivery" && $scope.filter_group=='all'){
                var groups={};
                var item_immediate={
                    key:'immediate',
                    title:"Immediate Stock",
                    total_qty:0,
                    total_units:0,
                    total:0,
                    items:[]
                }
                var item_others={
                    key:'others',
                    title:"Future Stock",
                    total_qty:0,
                    total_units:0,
                    total:0,
                    subtitle:"Delivery date: Undefined",
                    items:[]
                }
                angular.forEach($scope.model.cart_items,function(item,key){
                    if( item.type_stock=="present"){
                        item_immediate.items.push(item);
                    }
                    if( item.type_stock=="future"){
                        let date_month=$filter("date")($scope.dformat(item.delivery_date),'yMM');
                        if(item.delivery_date!="" && groups[date_month]==undefined){
                            groups[date_month]={
                                key:date_month,
                                title:"Future Stock",
                                subtitle:"Delivery date: "+($filter("date")($scope.dformat(item.delivery_date),"MMMM y")),
                                items:[]
                            }
                        }
                        if(item.delivery_date!=""){
                            groups[date_month].items.push(item);
                        }else{
                            item_others.items.push(item);
                        }
                    }
                });
                groups = $scope.sort(groups);
                $scope.model.groups=Object.assign({immediate:item_immediate},groups,{others:item_others});
            
            }
            if($scope.type_group.id=="delivery" && $scope.filter_group!='all'){
                var groups={};

                var item_others={
                    key:'others',
                    title:"Others",
                    subtitle:"",
                    total_qty:0,
                    total_units:0,
                    total:0,
                    items:[]
                }
                angular.forEach($scope.model.cart_items,function(item,key){
                    let date_month=$filter("date")($scope.dformat(item.delivery_date),'yMM');
                    if(    ($scope.filter_group!='immediate' && date_month==$scope.filter_group)
                        || ($scope.filter_group=='immediate' && item.type_stock=='present')
                        || ($scope.filter_group=='others' && item.delivery_date=='' && item.type_stock=='future') ){
                        if( groups[item.pa_brand]==undefined){
                            groups[item.pa_brand]={
                                key:item.pa_brand,
                                title:(item.brand!='others')?item.brand:'(No brand)',
                                subtitle:"",
                                items:[]
                            }
                        }
                        groups[item.pa_brand].items.push(item);
                        
                    }
                });

                groups = $scope.sort(groups);

                $scope.model.groups=groups;
            }

            console.log( $scope.model.groups);

        }
        

        $scope.change_group_by=function(){
            $scope.filter_group="all";
            $scope.build_resumen();
            $scope.group_by();
            $scope.update_group_totals();
            jQuery(".wi-list-items-cart").show();
        }
        

        $scope.set_filter_group=function(filter){
            
            $scope.filter_group=filter;
            $scope.group_by();
            $scope.update_group_totals();
            jQuery(".wi-list-items-cart").show();
        }
        $scope.$on("update_totals",function(evt){
            $scope.update_group_totals();
            
            $scope.compare_changes();
        });
        $scope.update_group_totals=function(){
            angular.forEach($scope.model.groups,function(item,index){
                item.total_qty=0;
                item.total_units=0;
                item.total=0;
                angular.forEach(item.items,function(item2,index2){
                    item.total_qty+=item2.qty;
                    item.total_units+=item2.total_units;
                    item.total+= (item2.total_units * item2.price);
                });
               
            });
            $scope.update_resumen_totals();
        };
        $scope.update_resumen_totals=function(){
            angular.forEach($scope.model.resumen_list,function(item,key){
                item.total_units=0;
                item.total_items=0;
                item.sub_total=0;
            });

            if($scope.type_group.id=="brand"){
                
                angular.forEach($scope.model.cart_items,function(item,key){
                    if($scope.model.resumen_list[item.pa_brand]!=undefined && item.piezes_x_pack!=undefined){
                        $scope.model.resumen_list[item.pa_brand].total_units += (item.piezes_x_pack*item.qty);
                        $scope.model.resumen_list[item.pa_brand].total_items += item.qty;
                        $scope.model.resumen_list[item.pa_brand].sub_total += (item.piezes_x_pack*item.qty*item.price);
                    }
                });
            }
            if($scope.type_group.id=="delivery"){
                
                angular.forEach($scope.model.cart_items,function(item,key){
                    if(item.piezes_x_pack!=undefined){
                        let date_month=$filter("date")($scope.dformat(item.delivery_date),'yMM');
                        //console.log(date_month);
                       
                        if($scope.model.resumen_list[date_month]!=undefined){
                            $scope.model.resumen_list[date_month].total_units += (item.piezes_x_pack*item.qty);
                            $scope.model.resumen_list[date_month].total_items += item.qty;
                            $scope.model.resumen_list[date_month].sub_total += (item.piezes_x_pack*item.qty*item.price);
                        }
                        if(item.type_stock=="present"){
                            $scope.model.resumen_list['immediate'].total_units += (item.piezes_x_pack*item.qty);
                            $scope.model.resumen_list['immediate'].total_items += item.qty;
                            $scope.model.resumen_list['immediate'].sub_total += (item.piezes_x_pack*item.qty*item.price);
                        }
                        if(item.type_stock=="future" && item.delivery_date==""){
                            $scope.model.resumen_list['others'].total_units += (item.piezes_x_pack*item.qty);
                            $scope.model.resumen_list['others'].total_items += item.qty;
                            $scope.model.resumen_list['others'].sub_total += (item.piezes_x_pack*item.qty*item.price);
                        }
                    }
                    
                });
            }
            jQuery('[name="update_cart"]').prop("disabled",false);
        }

        $scope.change_group_by();

        $scope.submit_cart=function(){
            jQuery('.woocommerce-cart-form').submit();
        }
        $scope.toggle_group=function(group,event){
                group.group_open=group.group_open?false:true;

                jQuery(event.target).parent().children(".wi-list-items-cart").slideToggle(250);
        };

        $scope.compare_changes=function(){
            $scope.need_update = false;
            jQuery(".cart-collaterals .checkout-button").removeClass("disabled");
            angular.forEach($scope.model.cart_items,function(item,index){
                if(item.qty!=$scope.model.cart_items_base[index].qty){
                    $scope.need_update = true;
                }
            });
            if($scope.need_update ){
                jQuery(".cart-collaterals .checkout-button").addClass("disabled");
            }
        };

    });

    angular.module("app_cart").filter('customSorter', function() {
        function CustomOrder(item) {
        switch(item) {
            case 'immediate': 
            return 1;

            case 'others':  
            return 999999;

            default:
                if(parseInt(item)!=NaN){
                    return parseInt(item);
                }else{
                    return Number(item.charCodeAt(0));
                }
               
            
        }  
        }

        return function(items, field) {
        var filtered = [];
        angular.forEach(items, function(item) {
            filtered.push(item);
        });
        filtered.sort(function (a, b) {    
            return (CustomOrder(a.key) > CustomOrder(b.key) ? 1 : -1);
        });
        return filtered;
        };
        });

</script>
<script>
   jQuery(".cart-collaterals .checkout-button").click(function(){
       return !jQuery(this).hasClass("disabled");
   })
</script>