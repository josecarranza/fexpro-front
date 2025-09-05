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

//$general_var["product_title"]= $product->get_name();
$general_var["product_title"]= $product->get_short_description()!=""?$product->get_short_description():$product->get_name();
$general_var["price"] = $product->get_price();
$general_var["sku"] = $product->get_sku();

//if($general_var["price"]==0){
	$general_var["price"]=$var_data[0]["price"];
	$general_var["old_price"]=$var_data[0]["old_price"];
//}

$current_team = preg_replace("/[^a-zA-Z0-9\-,_\+ ]+/", "",(trim($_GET["team"]??"none")));
$current_color = preg_replace("/[^a-zA-Z0-9\-,_]+/", "",strtolower(trim($_GET["color"]??"none")));

$mini_img_main = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'thumbnail');
$full_img_main = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'full');

$main_img = ["mini"=>isset($mini_img_main[0])?$mini_img_main[0]:"" ,"image"=> isset($full_img_main[0])? $full_img_main[0]:""];

?>
<div id="product-<?php the_ID(); ?>" class="<?php echo esc_attr( $post_class ) ?>" 
ng-app="app_product" ng-controller="ctrl" ng-cloak>
	<div class="row mt-producto-detail">
		<div class="col-12 col-md-5  ">
			<div>
				<h2 class="product-title">{{current_variation.product_title}}</h2>
				<h4 class="product-price"> <span class="old-price" ng-if="current_variation.old_price!=0">{{current_variation.old_price | currency:'$'}}</span> {{current_variation.price | currency:'$'}}</h4>

				<span class="product-att"><b>Collection:</b> {{current_variation.collection}}</span><br>
				<span class="product-att" ng-show="false"><b>{{current_variation.product_type}}</b></span>
				<span class="product-att"><b>{{current_variation.sku}}</b></span>
			</div>
			<div class="product-gallery">
				<div class="product-gallery-main ">
					 
						<img ng-src="{{gallery_selected}}" class="img-zoom"  />	
					 
				</div>
				<div class="product-gallery-mini-list">
					<div class="product-gallery-mini-item {{g.selected && 'selected'}}" ng-repeat="g in gallery" ng-click="setGalleryImg(g)">
						<img ng-src="{{g.mini}}" />
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-7 col-lg-7 col-xl-6 offset-xl-1" is-basic="{{is_basic}}">
			<div class="{{is_basic && 'box-shadow-div'}}">
 
			<div class="stock-selector" ng-show="is_basic || cat_stock=='available'">
				<!-- <div class="row">
					<div class="col"  ng-show="is_basic" >
						<label for="">Shop by</label>
						<select name="" id="" class="selector-type-stock" ng-model="cat_stock"ng-change="checkTypeStoke()">
							<option value="available">Inventory</option>
							<option value="presale">Presale</option>
						</select>
					</div>
				</div> -->
				<div class="tabs-type-stock line-{{cat_stock}}" ng-show="is_basic">
					<div class="item-tab-type-stock tab-inventory {{cat_stock=='available' && 'active'}}" ng-click="cat_stock='available';checkTypeStoke();">Inventory</div>
					<div class="item-tab-type-stock tab-presale {{cat_stock=='presale' && 'active'}}" ng-click="cat_stock='presale';checkTypeStoke();">Presale</div>
				</div>

				<div class="row">
					<div class="col-6"  ng-show="cat_stock=='available'" >
						<label for="" class="d-inline mr-2">Delivery</label>
						<div class="radio-group">
							<span class="item-radio color-blue {{type_stock=='inmediate' && 'selected'}}" ng-click="type_stock='inmediate';checkTypeStoke();">Immediate</span>
							<span class="item-radio color-yellow {{type_stock=='future' && 'selected'}}" ng-click="type_stock='future';checkTypeStoke();">Future</span>
						</div>
					</div>
					<div class="col-6 nowrap text-right"  ng-show="cat_stock=='available' && type_stock=='inmediate'" >
						 

							<label for="" class="d-inline  mr-2">Port of Shipment</label>
							<div class="radio-group">
								<span class="item-radio color-green {{warehouse=='china' && 'selected'}}" ng-click="setWarehouse('china')">China</span>
								<span class="item-radio color-blue2 {{warehouse=='panama' && 'selected'}}" ng-click="setWarehouse('panama')">Panamá</span>
							</div>

					</div>
				</div>
			</div>

				
		
		
			<div class="row">
				<div class="col-6">
					<span class="total-label">Total:</span>
					<span class="total-value">{{total_price|currency:'$'}}</span>
					<br>
					<span class="total-value sm">Total units:</span>
					<span class="total-value sm">{{total_units}}</span>
				</div>
				<div class="col-6 text-right">
					<button class="btn-add-to-cart {{sending_items && 'sending'}}" type="button" ng-click="addToCart()" ng-disabled="total_units==0 || sending_items">Add to cart</button>
				</div>
			</div>
			<br/>
			<div class="text-right">
				<span class="text-grey-bold-variation">Select the number of packs and then add them to the cart</span>
			</div>
			
			<div class="variation-list">
				<div class="item-variation" ng-repeat="variation in variations" ng-show="cat_stock=='presale' || (variation.packs.max>0 && cat_stock=='available')">
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
									<th style="position:relative">Number of packs <span ng-show="cat_stock=='presale'" class="ico-semaforo {{variation.suggestion}}"></span> </th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<div class="sizes">
											<div ng-repeat="size in variation.sizes"><i>{{size.size}}{{size.medidas?' ('+size.medidas+')':''}}</i><i>{{size.value}}</i></div>
										</div>
									</td>
									<td>
										<label class="table-title">{{variation.packs.units_pack}} units</label>
										<span>(1 pack)</span>
									</td>
									<td >
										
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
	</div>
	<div class="product-description-box">
		<label for="">Description</label>

		<div class="row">
			<div class="col col-5">
				<ul>
					<li>
						<div class="row">
							<div class="col-5"><span>Country of origin</span></div>
							<div class="col-7"> {{current_variation.country}}</div>
						</div>
						
					</li>
					<li>
						<div class="row">
							<div class="col-5"><span>Fabric composition</span></div>
							<div class="col-7">{{current_variation.fabric_composition}}</div>
						</div>
						
					</li>
				 </ul>
			</div>
			<div class="col col-7">
				<ul>
					<li>
						<div class="row">
							<div class="col-4"><span>Delivery</span></div>
							<div class="col-8"> {{current_variation.date}}</div>
						</div>
						
					</li>
					<li>
						<div class="row">
							<div class="col-4"><span>Logo application</span></div>
							<div class="col-8"> {{current_variation.logo_application}}</div>
						</div>
						 
					</li>
				 </ul>
			</div>
		</div>
	</div>

</div>


<script>
	variation_data=<?=json_encode($var_data);?>;
	general_prod = <?=json_encode($general_var);?>;
	var from_presale = <?=isset($_GET["from"]) && $_GET["from"]=="presale"?1:0?>;
	var delivery = "<?=((isset($_GET["f_delivery"]) && ($_GET["f_delivery"]=="now" || $_GET["f_delivery"]=="")) || !isset($_GET["f_delivery"]))?"inmediate":"future"?>";
	var wh = "<?=((isset($_GET["wh"]) && ($_GET["wh"]=="panama" || $_GET["wh"]=="" )) || !isset( $_GET["wh"])  )?"panama":"china"?>";
	var main_image = <?=json_encode($main_img)?>;
</script>
<script src="<?=get_site_url()?>/wp-content/themes/porto-child/assets/js/jquery.ez-plus.js"></script>

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
		if($scope.is_basic && from_presale==1 && $scope.is_presale){
			$scope.is_basic=false;
		}

		$scope.cat_stock= ($scope.is_presale && !$scope.is_basic ) || ($scope.is_basic && from_presale==1)? 'presale':'available';
		//$scope.type_stock = $scope.cat_stock=='available' ? 'inmediate':'';
		$scope.type_stock = $scope.cat_stock=='available' ? delivery:'';
		$scope.warehouse=wh;//'panama';
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
		general_prod.gallery.push(main_image);
		$timeout(()=>{
			$(".img-zoom").ezPlus({
					lensSize: 200,
					zoomWindowHeight: 600,
					zoomWindowWidth: 600,
					lenszoom:true,
					mantainZoomAspectRatio:true,
 
					maxZoomLevel:3,
				
				});
		},500);
		
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

			$scope.buscar_disponibles();
			
		});
		$scope.buscar_disponibles=function(){
			
			if($scope.cat_stock=="available"){
				let t = $scope.tiene_stock();
				console.log(t);
				if($scope.type_stock=="inmediate" && t==0){
					$scope.warehouse= $scope.warehouse=="panama"?"china":"panama";
					$scope.set_sizes_max();
					t = $scope.tiene_stock();
					console.log(t);
					if(t==0){
						$scope.type_stock="future";
						$scope.set_sizes_max();
						t = $scope.tiene_stock();
					}
				}
				if($scope.type_stock=="future" && t==0){
					$scope.type_stock="inmediate";
					$scope.warehouse="panama";
					$scope.set_sizes_max();
					t = $scope.tiene_stock();
					if(t==0){
						$scope.warehouse="china";
						$scope.set_sizes_max();
					}
				}
			}
		}
		$scope.tiene_stock=function(){
			let total = 0;
			console.log($scope.variations);
			angular.forEach($scope.variations,(item)=>{
				total+=Number(item.packs.max);
			});
			return total;
		}

		$scope.set_sizes_max=function(){
			angular.forEach($scope.variations,(item,index)=>{

				let max=-1;

				if($scope.cat_stock=="available"){
					max = $scope.type_stock=="inmediate" ? ($scope.warehouse=="panama"?item.stock_present:item.stock_present_china) :item.stock_future;
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
		
		$scope.setWarehouse=function(t){
			$scope.warehouse=t;
			$scope.checkTypeStoke();
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
			var ez = $('.img-zoom').data('ezPlus');
			if(ez){
						ez.destroy();

				$timeout(()=>{
					$(".img-zoom").ezPlus({
						lensSize: 200,
						zoomWindowHeight: 600,
						zoomWindowWidth: 600,
						lenszoom:true,
						mantainZoomAspectRatio:true,
	 
						maxZoomLevel:3,
					});
				},100);
			}
		

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
						qty : $scope.cat_stock=="available" && $scope.type_stock=="inmediate" && $scope.warehouse=="panama" ? item.packs.packs:0,
						qty_future: ($scope.cat_stock=="available" && $scope.type_stock=="future" || $scope.cat_stock=="presale" ) ? item.packs.packs:0,
						qty_china : $scope.cat_stock=="available" && $scope.type_stock=="inmediate" && $scope.warehouse=="china" ? item.packs.packs:0,
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