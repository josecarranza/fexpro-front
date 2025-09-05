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

$is_basic = in_array("basics",$cats)?true:false;

$groups_team = [];
foreach($var_data as $variation){
	if(!isset($groups_team[$variation["pa_team"]])){
		$groups_team[$variation["pa_team"]]=[];
	}
	$groups_team[$variation["pa_team"]][]=$variation;
}
$current_team = preg_replace("/[^a-zA-Z0-9\-,_]+/", "",strtolower(trim($_GET["team"]??"none")));
$current_color = preg_replace("/[^a-zA-Z0-9\-,_]+/", "",strtolower(trim($_GET["color"]??"none")));
?>
<div id="product-<?php the_ID(); ?>" class="<?php echo esc_attr( $post_class ) ?>" 
ng-app="app_product" ng-controller="ctrl" ng-cloak>
	<div class="row">
		<div class="col-12 col-md-6">
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
		<div class="col-12 col-md-6 col-lg-5">
			<h2 class="product-title">{{current_variation.product_title}}</h2>
			<h4 class="product-price">{{current_variation.price | currency:'$'}}</h4>

			<span class="product-att"><b>Collection:</b> {{current_variation.collection}}</span><br>
			<span class="product-att"><b>{{current_variation.product_type}}</b></span>
			<div class="product-divisor"></div>
			<span class="product-att"><b>Color</b></span>
			<div class="products-color">
				<span class="color color-{{color.color_clear}} {{color.pa_color==current_variation.pa_color && 'selected'}}" ng-click="setColor($index)" ng-repeat="color in current_team"></span>
			</div>
			<span class="product-att"><b>Select your packs</b></span>
			
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
			<div class="table-sizes">
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
									<div ng-repeat="size in current_variation.sizes"><i>{{size.size}}</i><i>{{size.value}}</i></div>
								</div>
							</td>
							<td>
								<label class="table-title">{{packs.units_pack}} units</label>
								<span>(1 pack)</span>
							</td>
							<td>
								
								<util-number-stepper ng-model="packs.packs" max="packs.max"></util-number-stepper>

								<span class="leyend" ng-show="packs.max>-1"><b>Available:</b> {{packs.max}} packs</span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="row">
				<div class="col-6">
					<span class="total-label">Total:</span>
					<span class="total-value">{{packs.total|currency:'$'}}</span>
				</div>
				<div class="col-6">
					<button class="btn-add-to-cart" type="button" ng-click="addToCart()">Add to cart</button>
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
						<span>Description</span> 
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

	<label for="" class="label-others-items">Other items you might like</label>
	<div class="carrouser-product-similar-container">
		<div class="carrouser-product-similar-btn-prev">
			<span class="ico-prev"></span>
		</div>
		<div class="carrouser-product-similar-wrapper">
			<div class="item-carrouser-similar" ng-repeat="g in groups_team" ng-if="g[0].pa_team!=current_variation.pa_team"
			ng-click="goVariation(g[0].pa_team)">
				<div class="product-image">
					<img ng-src="{{g[0].image}}" alt="{{g[0].product_team_name}}" />
				</div>
				<div class="product-colors">
					<span class="color color-{{color.color_clear}}" ng-repeat="color in g"></span>
					
				</div>
				<div class="item-similar-collection">COLLECTION: {{g[0].collection}}</div>
				<label for="" class="item-similar-name">{{g[0].product_team_name}}</label>
				<span class="item-similar-type-product">{{g[0].product_type}}</span>
				<span class="item-similar-price">{{g[0].price | currency:'$'}}</span>
			</div>

		</div>
		<div class="carrouser-product-similar-btn-next">
			<span class="ico-next"></span>
		</div>
	</div>

</div>


<script>
	variation_data=<?=json_encode($groups_team);?>;
</script>
<script>
	var base_url="<?=get_site_url()?>/";
	angular.module("app_product",["util_components"])
	.controller("ctrl",function($scope,$http,$timeout){
		$scope.model={}
		$scope.groups_team = variation_data;
		$scope.url_product = location.origin + location.pathname;
		$scope.current_team_slug = "<?=$current_team?>";
		$scope.current_team_slug = $scope.current_team_slug.split(",");
		if($scope.current_team_slug.length){
			 
			for(i in $scope.current_team_slug){
				if($scope.groups_team[$scope.current_team_slug[i]]){
					$scope.current_team = $scope.groups_team[$scope.current_team_slug[i]];
					break;
				}
			}
			$scope.current_team = $scope.current_team?$scope.current_team:$scope.groups_team[Object.keys($scope.groups_team)[0]] ;
		}else{
			$scope.current_team = $scope.groups_team[Object.keys($scope.groups_team)[0]] ;
		}
		//$scope.current_team = $scope.groups_team[$scope.current_team_slug] ??$scope.groups_team[Object.keys($scope.groups_team)[0]] ;
		
		$scope.is_presale = <?=$is_presale?'true':'false'?>;
		$scope.is_basic = <?=$is_basic?'true':'false'?>;

		$scope.cat_stock= $scope.is_presale && !$scope.is_basic ? 'presale':'available';
		$scope.type_stock = $scope.cat_stock=='available' ? 'inmediate':'';

		$scope.current_variation = $scope.current_team[0];

		$scope.gallery = $scope.current_variation.gallery;
		$scope.gallery_selected="";

		$scope.packs={
			packs:0,
			units_pack:24,
			unit_price:0,
			total:0
		};
		
		console.log($scope.current_team);
		
		$scope.setColor=function(index){
			$scope.current_variation = $scope.current_team[index];
			$scope.gallery = $scope.current_variation.gallery;
			$scope.setGalleryImg($scope.gallery[0]);

			let sum=0;
			$scope.current_variation.sizes.forEach(element => {
				sum += Number(element.value);
			});
			console.log($scope.current_variation);
			let max=-1;

			if($scope.cat_stock=="available"){
				max = $scope.type_stock=="inmediate" ? $scope.current_variation.stock_present :$scope.current_variation.stock_future;
			}
			$scope.packs={
				packs:0,
				units_pack: sum,
				unit_price:parseFloat($scope.current_variation.price),
				total:0,
				max:max,
			};
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
		$timeout(function(){
			$scope.setColor(0);
			//$scope.setGalleryImg($scope.gallery[0]);
		});

		$scope.$watch("packs.packs",function(newval){
			$scope.calcule_total();
		});
		$scope.calcule_total=function(){
			$scope.packs.total = ($scope.packs.packs*$scope.packs.units_pack)*$scope.packs.unit_price;
			console.log($scope.packs);
		}
		$scope.checkTypeStoke=function(){
			let max=-1;

			if($scope.cat_stock=="available"){
				max = $scope.type_stock=="inmediate" ? $scope.current_variation.stock_present :$scope.current_variation.stock_future;
			}
			$scope.packs.max=max;
			$scope.packs.total=0;
			$scope.packs.packs=0;
			
			$scope.calcule_total();
		}
		$scope.addToCart=function(){
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