<?php 
/**
 * The template for displaying product content in the single-product.php template
 *
 * @version     3.6.0
 */
defined( 'ABSPATH' ) || exit;
/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
//do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
global $product;

$product_id = $product->get_id();
$sku = $product->get_sku();
include("single-product/variations-custom.php");

$terms = get_the_terms ( $product->get_id(), 'product_cat' );
foreach ( $terms as $term ) {
$cat_id = $term->term_id;
$c[] = $cat_id;
}
$akp = 'cat-' . implode("-",$c);
$cats =array_column($terms,"slug");
$is_presale = in_array("presale",$cats)?true:false;

$is_basic = in_array("core",$cats)?true:false;

/*$groups_team = [];
foreach($var_data as $variation){
	if(!isset($groups_team[$variation["pa_team"]])){
		$groups_team[$variation["pa_team"]]=[];
	}
	$groups_team[$variation["pa_team"]][]=$variation;
}*/
$general_var = $var_data[0];
$general_var["product_title"]= $product->get_name();
$general_var["price"] = $product->get_price();
$general_var["sku"] = $product->get_sku();

//if($general_var["price"]==0){
	$general_var["price"]=$var_data[0]["price"];
	$general_var["old_price"]=$var_data[0]["old_price"];
//}

$current_team = preg_replace("/[^a-zA-Z0-9\-,_\+ ]+/", "",(trim($_GET["team"]??"none")));
$current_color = preg_replace("/[^a-zA-Z0-9\-,_]+/", "",strtolower(trim($_GET["color"]??"none")));
?>
<div id="product-<?php the_ID(); ?>" class="<?php echo esc_attr( $post_class ) ?>" 
ng-app="app_product" ng-controller="ctrl" ng-cloak>
	<div class="row mt-producto-detail">
		<div class="col-12 col-md-6 col-lg-5 ">
			<div>
				<h2 class="product-title">{{current_variation.product_title}}</h2>
				<h4 class="product-price"> <span class="old-price" ng-if="current_variation.old_price!=0">{{current_variation.old_price | currency:'$'}}</span> {{current_variation.price | currency:'$'}}</h4>

				<span class="product-att"><b>Collection:</b> {{current_variation.collection}}</span><br>
				<span class="product-att"><b>{{current_variation.product_type}}</b></span>
			</div>
			<div class="product-gallery">
				<div class="product-gallery-main ">
				<img ng-src="{{gallery_selected}}"  />	
				</div>
				<div class="product-gallery-mini-list">
					<div class="product-gallery-mini-item {{g.selected && 'selected'}}" ng-repeat="g in gallery" ng-click="setGalleryImg(g)">
						<img ng-src="{{g.mini}}" />
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6 offset-lg-1">
			<div class="text-right">
					<span class="total-label">Total:</span>
					<span class="total-value">{{total_price|currency:'$'}}</span>
					<br>
					<span class="total-value sm">Total units:</span>
					<span class="total-value sm">{{total_units}}</span>
					<br />
					<br />
					<button class="btn-add-to-cart {{sending_items && 'sending'}}" type="button" ng-click="addToCart()" ng-disabled="total_units==0 || sending_items">Add to cart</button>
			</div>
		
			
			<div>
				<select name="" id="" class="selector-type-stock" ng-model="cat_stock" ng-show="is_basic" ng-change="checkTypeStoke()">
					<option value="available">Available</option>
					<option value="presale">Presale</option>
				</select>
				<select name="" id="" class="selector-type-stock" ng-model="type_stock" ng-show="cat_stock=='available'" ng-change="checkTypeStoke()">
					<option value="inmediate">Inmediate stock</option>
					<option value="future">Future stock</option>
				</select>
			</div>
			<div class="variation-list">
				<div class="item-variation" ng-repeat="variation in variations">
					<div class="variation-image" ng-click="setVariation(variation)">
						<img ng-src="{{variation.image}}" alt="">
						<span class="ico-lupa"></span>
					</div>
					<div class="table-sizes" >
						<table>
							<thead>
								<tr>
									<th>Sizes</th>
									<th>Packs</th>
									<th>Number of packs</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<div class="sizes">
											<div ng-repeat="size in variation.sizes"><i>{{size.size}}</i><i>{{size.value}}</i></div>
										</div>
									</td>
									<td>
										<label class="table-title">{{variation.packs.units_pack}} units</label>
										<span>(1 pack)</span>
									</td>
									<td>
										
										<util-number-stepper ng-model="variation.packs.packs" max="variation.packs.max"></util-number-stepper>

										<span class="leyend" ng-show="variation.packs.max>-1"><b>Available:</b> {{variation.packs.max}} packs</span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
			
		</div>
	</div>
	<div class="product-description-box">
		<label for="">Description</label>
		<div class="row">
			<div class="col col-5">
				<ul>
					<li>
						<span>SKU</span> {{current_variation.sku}}
					</li>
					<li>
						<span>Description</span> {{current_variation.product_title}}
					</li>
				</ul>
			</div>
			<div class="col col-7">
				<ul>
					<li>
						<span>Country of origin</span> {{current_variation.country}}
					</li>
					<li>
						<span>Fabric composition</span> {{current_variation.fabric_composition}}
					</li>
				 </ul>
			</div>
		</div>
	</div>
	<div class="accordions">
	<div class="packing-info-panel item-accordion show">
		<div class="packing-info-header item-accordion-header">
			Packing information
		</div>
		<div class="packing-info-body">
			<label for="">1 pack contains:</label>
			<table>
				<thead>
					<tr>
						<th>Item</th>
						<th>SKU</th>
						<th>Size</th>
						<th>Qty</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							{{current_variation.product_title}}
						</td>
						<td style="white-space:nowrap">
						{{current_variation.sku}}
						</td>
						<td>
							<i ng-repeat="size in current_variation.sizes">{{size.size}}</i>
						</td>
						<td>
							<i ng-repeat="size in current_variation.sizes">{{size.value}}</i>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	</div>
</div>


<script>
	variation_data=<?=json_encode($var_data);?>;
	general_prod = <?=json_encode($general_var);?>;
	var from_presale = <?=isset($_GET["from"]) && $_GET["from"]=="presale"?1:0?>;
</script>
<script>
	var base_url="<?=get_site_url()?>/";
	angular.module("app_product",["util_components"])
	.controller("ctrl",function($scope,$http,$timeout){
		$scope.model={}
		$scope.variations = variation_data;
		$scope.url_product = location.origin + location.pathname;
		
		$scope.current_team_slug = "<?=$current_team?>";
		$scope.current_team_slug = $scope.current_team_slug.split(",");
		general_prod.gallery=[];
		$scope.total_price = 0;
		$scope.total_units = 0;

		$scope.is_presale = <?=$is_presale?'true':'false'?>;
		$scope.is_basic = <?=$is_basic?'true':'false'?>;
		if($scope.is_basic && from_presale==1){
			$scope.is_basic=false;
		}

		$scope.cat_stock= $scope.is_presale && !$scope.is_basic ? 'presale':'available';
		$scope.type_stock = $scope.cat_stock=='available' ? 'inmediate':'';
		$scope.sending_items=false;

		angular.forEach($scope.variations,(item,index)=>{
			general_prod.gallery.push(item.gallery[0]);
			item.units_pack  = item.sizes.map(x=>x.value).reduce((a, b) => a + b, 0);
			console.log(item.units_pack);
			item.packs={
				packs:0,
				units_pack:item.units_pack,
				total:0
			};
		});
		
		$timeout(function(){
			$scope.set_sizes_max();
			if($scope.current_team_slug.length){
			 
				for(i in $scope.current_team_slug){
					let tmp = $scope.variations.filter(x=>x.product_team==$scope.current_team_slug[i]);
					if(tmp[0]){
						$scope.setVariation(tmp[0]);
					}else{
						$scope.setVariation(general_prod);
					}
					break;
				}
	 
			}else{
				$scope.setVariation(general_prod);
			}

			
		});

		$scope.set_sizes_max=function(){
			angular.forEach($scope.variations,(item,index)=>{

				let max=-1;

				if($scope.cat_stock=="available"){
					max = $scope.type_stock=="inmediate" ? item.stock_present :item.stock_future;
				}
		
				 
				item.units_pack  = item.sizes.map(x=>x.value).reduce((a, b) => a + b, 0);
				console.log(item.units_pack);
				item.packs={
					packs:0,
					units_pack:parseFloat(item.units_pack),
					unit_price:parseFloat(item.price),
					total:0,
					total_units:0,
					max:max
				};
			});
		}
		
		

		//$scope.current_variation = general_prod;//$scope.variations[0];

		$scope.gallery = [];//$scope.current_variation.gallery;
		$scope.gallery_selected="";

		
		
		//console.log($scope.current_team);
		
		$scope.setVariation=function(variation){
			//$scope.current_variation = $scope.current_team[index];
			$scope.current_variation = variation;
			$scope.gallery = $scope.current_variation.gallery;
			$scope.setGalleryImg($scope.gallery[0]);

			let sum=0;
			$scope.current_variation.sizes.forEach(element => {
				sum += Number(element.value);
			});
			console.log($scope.current_variation);
			
			$scope.calcule_total();

		}
		$scope.goVariation=function(team){
			location.href= $scope.url_product +"?team="+team;
		}
		$scope.setGalleryImg=function(item){
			$scope.gallery.map(x=>x.selected=false);
			item.selected=true;
			$scope.gallery_selected=item.image;
		}
		

		$scope.$watch("variations",function(newval){
			$scope.calcule_total();
		},true);
		$scope.calcule_total=function(){
			$scope.total_price=0;
			$scope.total_units=0;
			angular.forEach($scope.variations,(item,index)=>{
				item.packs.total_units = item.packs.units_pack*item.packs.packs;
				$scope.total_units+= item.packs.total_units;
				$scope.total_price += item.packs.total_units*item.packs.unit_price;
			});
			//$scope.packs.total = ($scope.packs.packs*$scope.packs.units_pack)*$scope.packs.unit_price;
			
		}
		$scope.checkTypeStoke=function(){
			/*let max=-1;

			if($scope.cat_stock=="available"){
				max = $scope.type_stock=="inmediate" ? $scope.current_variation.stock_present :$scope.current_variation.stock_future;
			}
			$scope.packs.max=max;
			$scope.packs.total=0;
			$scope.packs.packs=0;*/
			$scope.set_sizes_max();
			$scope.calcule_total();
		}
		$scope.addToCart_old=function(){
			var dataPost={
				product_sku:$scope.current_variation.sku,
				variation_id:$scope.current_variation.id,
				product_id:$scope.current_variation.main_id,
				from_presale:$scope.cat_stock=="presale"?1:0,
				quantity:$scope.cat_stock=="available" && $scope.type_stock=="inmediate" ? $scope.packs.packs:0,
				quantity_future:($scope.cat_stock=="available" && $scope.type_stock=="future" || $scope.cat_stock=="presale" ) ? $scope.packs.packs:0,
			}
			$http({
				url:base_url+"wp-admin/admin-ajax.php?action=qep_add_to_cart",
				method:"POST",
				//data:dataPost,
				data: $.param(dataPost),
   				headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
			}).then(function(response){

			});
		}
		$scope.addToCart=function(){
 
			var dataPost={
				cart:{},
				from_presale:$scope.cat_stock=="presale"?1:0,
				product_id: $scope.variations[0].main_id
			};
			angular.forEach($scope.variations,(item,index)=>{
				if(item.packs.packs>0){
					dataPost.cart[item.id]={
						qty : $scope.cat_stock=="available" && $scope.type_stock=="inmediate" ? item.packs.packs:0,
						qty_future: ($scope.cat_stock=="available" && $scope.type_stock=="future" || $scope.cat_stock=="presale" ) ? item.packs.packs:0
					}
					
				}
			});
			$scope.sending_items=true;
			$http({
				url:base_url+"wp-admin/admin-ajax.php?action=qep_add_to_cart_bulk",
				method:"POST",
				data: dataPost,
			}).then(function(response){

				location.reload();
			});
		}
	});
</script>
<script>
	$(".magnified").hover(function(e){
  //Store position & dimension information of image
  var imgPosition = $(".magnify").position(),
      imgHeight = $(".magnified").height(),
      imgWidth = $(".magnified").width();
  
  //Show mangifier on hover
  $(".magnifier").show();
  
  //While the mouse is moving and over the image move the magnifier and magnified image
  $(this).mousemove(function(e){
    //Store position of mouse as it moves and calculate its position in percent
    var posX = e.pageX - imgPosition.left,
        posY = e.pageY - imgPosition.top,
        percX = (posX / imgWidth) * 100,
        percY = (posY / imgHeight) * 100,
        perc = percX + "% " + percY + "%";
    
    //Change CSS of magnifier, move it to mouse location and change background position based on the percentages stored.
    $(".magnifier").css({
      top:posY,
      left:posX,
      backgroundPosition: perc
    });
  });
}, function(){
  //Hide the magnifier when mouse is no longer hovering over image.
  $(".magnifier").hide();
});
</script>
<script>
	$(".carrouser-product-similar-btn-next .ico-next").click(function(){
		let wrapper=$(".carrouser-product-similar-wrapper");
		let w = $(".item-carrouser-similar").eq(0).outerWidth()+30;
		wrapper.animate({"scrollLeft":"+="+w+"px"},500);
	});
	$(".carrouser-product-similar-btn-prev .ico-prev").click(function(){
		let wrapper=$(".carrouser-product-similar-wrapper");
		let w = $(".item-carrouser-similar").eq(0).outerWidth()+30;
		wrapper.animate({"scrollLeft":"-="+w+"px"},500);
	});
</script>

<script>
	$(".accordions .item-accordion-header").click(function(){
		if($(this).parent().hasClass("show")){
			$(".accordions .item-accordion").removeClass("show");
		}else{
			$(this).parent().addClass("show");
		}
		
		
	});
</script>