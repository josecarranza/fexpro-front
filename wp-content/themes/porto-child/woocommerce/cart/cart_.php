<?php 
if(isset($_GET["old"])){
    include("cart-oldnew.php");
    return;
}
if($_SERVER['REQUEST_URI']=="/cart/"){
	ob_clean();
	wp_redirect("/checkout");
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
WC()->cart->calculate_totals();
$data_cart=array();
$items=WC()->cart->get_cart();
foreach($items as $key => $item){
    $tmp=array();
    $tmp["key"] = $key;
    $tmp["type_stock"] = isset($item["type_stock"]) && $item["type_stock"] == "future" ? "future":"present";
    $tmp["sizes"] = $item["variation_size"];
    $tmp["is_presale"] = isset($item["is_presale"]) && $item["is_presale"]==1?true:false;
	$tmp["is_basic"] = $item["is_basic"]??false;


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
    $tmp["link_remove"] =str_replace("&amp;","&" ,wc_get_cart_remove_url( $key ));
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
	$tmp["image"] = str_replace("http://34.205.89.113","https://shop2.fexpro.com",$tmp["image"]);
    $data_cart[]=$tmp;
    
}
?>
<?php do_action( 'woocommerce_before_cart_table' ); ?>
<?php do_action( 'woocommerce_before_cart_contents' ); ?>
<?php do_action( 'woocommerce_cart_contents' ); ?>
<div ng-app="app_cart" ng-controller="ctrl">
	<div class="row">
		<div class="col-8">
			<h2>Payment detail <a href="javascript:void(0)" ng-click="export_cart()" class="btn-export {{exporting && 'exporting'}}">Export order <span class="ico-export"></span></a></h2>
		</div>
		<div class="col-4 text-right">
		<a href="/cart/?empty_cart=yes" class="empty-cart"> Empty Cart <span class="ico-remove"></span></a>
		</div>
	</div>
	<div class="row">
		<div class="col-9">
			<form action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="POST" class="woocommerce-cart-form">
				<input type="hidden" name="cart[{{item.key}}][qty]" value="{{item.qty}}" ng-repeat="item in cart" >
				
				<input type="hidden" name="update_cart" value="Update Cart">
				<?php do_action( 'woocommerce_cart_actions' ); ?>
				<?php wp_nonce_field('woocommerce-cart',"woocommerce-cart-nonce")?>
			
			</form>
			<div class="accordions">
				<div class="item-accordion show">
					<div class="item-accordion-header">
						Orden summary
					</div>
					<div class="item-accordion-body">
						
						<div ng-if="has_presale>0">
							<label for="" class="cart-group">Pre sale</label>
							<table class="cart-items">
								<tr ng-repeat="item in cart" ng-if="item.is_presale">
								<td>
										<a href="{{item.link_product}}">
										<div class="cart-item-image">
											<img ng-src="{{item.image}}" alt="">
											<span class="tag-image  d-none" ng-if="item.is_basic">Basic</span>
										</div>
										</a>
									</td>
									<td>
										<b>{{item.title}}</b>
										<br />
										<b>{{item.price|currency:'$'}}</b>
									</td>
									<td>
										<div class="table-sizes">
											<div ng-repeat="size in item.sizes">
												<i>{{size.label}}</i>
												<i>{{size.value}}</i>
											</div>
										</div>
									</td>
									<td class="col-packs">
										<b>{{item.units_per_pack*item.qty}} units</b><br />
										({{item.qty}} packs)
									</td>
									<td>
										<util-number-stepper ng-model="item.qty" max="item.is_presale?-1:(item.type_stock=='future'?item.stock_future:item.stock_present)"></util-number-stepper>
									</td>
									<td class="col-total">
										{{item.total | currency:'$'}}
									</td>
									<td>
										<a class="cart-action" href="{{item.link_remove}}">Delete <span class="ico-remove"></span></a><br>
										<a class="cart-action" href="#">Save for later <span class="ico-heart"></span></a>
									</td>
								</tr>
							</table>
						</div>
						<div ng-if="has_stock>0">
							<label for="" class="cart-group">Inventory</label>
							<table class="cart-items">
								<tr ng-repeat="item in cart" ng-if="!item.is_presale">
									<td>
										<a href="{{item.link_product}}">
										<div class="cart-item-image">
											<img ng-src="{{item.image}}" alt="">
											<span class="tag-image d-none" ng-if="item.is_basic">Basic</span>
										</div>
										</a>
									</td>
									<td  >
										<b>{{item.title}}</b>
										<br />
										<b>{{item.price|currency:'$'}}</b>
									</td>
									<td>
										<div class="table-sizes">
											<div ng-repeat="size in item.sizes">
												<i>{{size.label}}</i>
												<i>{{size.value}}</i>
											</div>
										</div>
									</td>
									<td class="col-packs">
										<b>{{item.units_per_pack*item.qty}} units</b><br />
										({{item.qty}} packs)
									</td>
									<td>
										<util-number-stepper ng-model="item.qty" max="item.is_presale?-1:(item.type_stock=='future'?item.stock_future:item.stock_present)"></util-number-stepper>
									</td>
									<td class="col-total">
										{{item.total | currency:'$'}}
									</td>
									<td>
										<a class="cart-action" href="{{item.link_remove}}">Delete <span class="ico-remove"></span></a><br>
										<a class="cart-action" href="#">Save for later <span class="ico-heart"></span></a>
									</td>
								</tr>
							</table>
						</div>
						<div class="panel-wait" ng-show="wait"></div>
					</div>
				</div>
				<div class="item-accordion">
					<div class="item-accordion-header">
						Billing details
					</div>
					<div class="item-accordion-body">
						<?php  //do_shortcode("[woocommerce_checkout]");?>
						<div class="woocommerce">
						<?php  do_action( 'woocommerce_before_checkout_form' ); ?>
							<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

								

								<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

								<div class2="col2-set" id="customer_details">
									<div class="">
										<?php do_action( 'woocommerce_checkout_billing' ); ?>
										<?php do_action( "woocommerce_after_order_notes" ); ?>
									</div>

									<div class2="col-2 d-none2">
										<?php  do_action( 'woocommerce_checkout_shipping' ); ?>
									</div>
								</div>

								<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

								<div class="d-none">
								<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
								
								<h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>
								
								<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

									<div id="order_review" class="woocommerce-checkout-review-order ">
										<?php do_action( 'woocommerce_checkout_order_review' ); ?>
									</div>
								</div>
							
							<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

							</form>
							</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-3">
		<div class="cart-collaterals">
            <?php
                /**
                 * Cart collaterals hook.
                 *
                 * @hooked woocommerce_cross_sell_display
                 * @hooked woocommerce_cart_totals - 10
                 */
				include("cart-totals.php");
               // echo do_action( 'woocommerce_cart_collaterals' );
            ?>
			<div class="text-center">
				<button class="btn-place-order" ng-click="place_order()">Place order</button>
			</div>
        </div>
		</div>
	</div>

</div>
<?php do_action( 'woocommerce_after_cart' ); ?>


<script>
	cart=<?=json_encode($data_cart);?>;
	var nonce="<?=wp_create_nonce('woocommerce-cart')?>";
</script>
<script>
	var base_url="<?=get_site_url()?>/";
	angular.module("app_cart",["util_components"])
	.controller("ctrl",function($scope,$http,$timeout){
		$scope.model={};
		$scope.has_presale=0;
		$scope.has_stock=0;
		$scope.wait=false;

		$scope.exporting=false;

		var timer;
		$scope.cart=cart.map((x)=>{
			let total = 0;
			x.sizes.forEach(s=>{
				total+=Number(s.value);
			});
			$scope.has_presale += x.is_presale?1:0;
			$scope.has_stock   += !x.is_presale?1:0;
			x.units_per_pack=total;
			return x;
		});
		console.log($scope.has_presale);

		$scope.$watch("cart",function(newval,oldvalue){
			let item_cart=null;
			newval.forEach((item,index)=>{
				if(item.qty!=oldvalue[index].qty){
					item_cart=item;
					return;
				}
			});
			
			$scope.update_cart(item_cart);
		},true);

		$scope.update_cart=function(item_cart){
			console.log(item_cart);
			$timeout.cancel(timer);
			
			if(item_cart==null) return;
			timer = $timeout(function(){
				$scope.wait=true;
				$('[name="update_cart"]').prop("disabled",false);
				$(".woocommerce-cart-form").submit();
			},1000);
			
			return;

			let dataPost={
				cart:{},
				update_cart:"Update Cart",
				"woocommerce-cart-nonce":nonce
			};
			dataPost.cart[item_cart.key] = {qty:item_cart.qty};
			//console.log(dataPost);
			$http({
				url:location.href,
				method:"POST",
				//data:dataPost,
				data: $.param(dataPost),
   				headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
			}).then(function(response){
				//location.reload();
			});
		};

		$scope.place_order=function(){
			$("form[name='checkout']").submit();
			$scope.check_errors_submit();
		}
		$scope.export_cart=function(){
			$scope.exporting=true;
			$http({
				url:base_url+"wp-admin/admin-ajax.php?action=qep_export_cart",
				method:"GET",
				
			}).then(function(response){
				if(response.data.error==0){
                    window.open(response.data.download, '_blank');
                }
                $scope.exporting=false;
			});
		}
		$scope.check_errors_submit=function(){
			if($(".woocommerce-NoticeGroup").length>0){
				$(".accordions .item-accordion:eq(1) .item-accordion-header").click();
			}else{
				$timeout(function(){
					$scope.check_errors_submit();
				},1000);
			}
		}
		
	});
</script>
<script>
	$(".accordions .item-accordion-header").click(function(){
		$(".accordions .item-accordion").removeClass("show");
		$(this).parent().addClass("show");
	});
</script>