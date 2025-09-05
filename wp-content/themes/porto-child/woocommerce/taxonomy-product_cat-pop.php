<?php

get_header();
global $porto_sidebar;
$porto_sidebar="sidebar-category-pop";

?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>



<!-- <link rel="stylesheet" href="https://pagination.js.org/dist/2.1.5/pagination.css"> -->
<?php
$query_current = get_queried_object();

$filtros_slugs = array();
$filtros_pa = array();
$filtros = array();
if (isset($_GET["filter_brand"]) && $_GET["filter_brand"] != "") {
    $pa_brand  = explode(",", $_GET["filter_brand"]);
    $filtros["pa_brand"] = array();
    foreach ($pa_brand as $i => $item) :
        $filtros_slugs[] = addslashes($item);
        $filtros["pa_brand"][] = addslashes($item);
    endforeach;
    $filtros_pa[] = "pa_brand";
    $filtros["pa_brand"] = implode(",", $filtros["pa_brand"]);
}
if (isset($_GET["filter_color"]) && $_GET["filter_color"] != "") {
    $pa_color  = explode(",", $_GET["filter_color"]);
    $filtros["pa_color"] = array();
    foreach ($pa_color as $i => $item) :

        $filtros_slugs[] = addslashes($item);
        $filtros["pa_color"][] = addslashes($item);
    endforeach;
    $filtros_pa[] = "pa_color";
    $filtros["pa_color"] = implode(",", $filtros["pa_color"]);
}
if (isset($_GET["filter_collection"]) && $_GET["filter_collection"] != "") {
    $pa_collection  = explode(",", $_GET["filter_collection"]);
    $filtros["pa_collection"] = array();
    foreach ($pa_collection as $i => $item) :

        $filtros_slugs[] = addslashes($item);
        $filtros["pa_collection"][] = addslashes($item);
    endforeach;
    $filtros_pa[] = "pa_collection";
    $filtros["pa_collection"] = implode(",", $filtros["pa_collection"]);
}
if (isset($_GET["filter_country-of-origin"]) && $_GET["filter_country-of-origin"] != "") {
    $pa_country  = explode(",", $_GET["filter_country-of-origin"]);
    $filtros["pa_country-of-origin"] = array();
    foreach ($pa_country as $i => $item) :

        $filtros_slugs[] = addslashes($item);
        $filtros["pa_country-of-origin"][] = addslashes($item);
    endforeach;
    $filtros_pa[] = "pa_country-of-origin";
    $filtros["pa_country-of-origin"] = implode(",", $filtros["pa_country-of-origin"]);
}
if (isset($_GET["filter_date"]) && $_GET["filter_date"] != "") {
    $pa_date  = explode(",", $_GET["filter_date"]);
    $filtros["pa_date"] = array();
    foreach ($pa_date as $i => $item) :

        $filtros_slugs[] = addslashes($item);
        $filtros["pa_date"][] = addslashes($item);
    endforeach;
    $filtros_pa[] = "pa_date";
    $filtros["pa_date"] = implode(",", $filtros["pa_date"]);
}
if (isset($_GET["filter_gender"]) && $_GET["filter_gender"] != "") {
    $pa_gender  = explode(",", $_GET["filter_gender"]);
    $filtros["pa_gender"] = array();
    foreach ($pa_gender as $i => $item) :

        $filtros_slugs[] = addslashes($item);
        $filtros["pa_gender"][] = addslashes($item);
    endforeach;
    $filtros_pa[] = "pa_gender";
    $filtros["pa_gender"] = implode(",", $filtros["pa_gender"]);
}
if (isset($_GET["filter_cat_pop"]) && $_GET["filter_cat_pop"] != "") {
    $pa_cat_pop  = explode(",", $_GET["filter_cat_pop"]);
    $filtros["cat_pop"] = array();
    foreach ($pa_cat_pop as $i => $item) :

        $filtros_slugs[] = addslashes($item);
        $filtros["cat_pop"][] = addslashes($item);
    endforeach;
    $filtros_pa[] = "cat_pop";
    $filtros["cat_pop"] = implode(",", $filtros["cat_pop"]);
}
if (isset($_GET["filter_sold"]) && $_GET["filter_sold"] != "") {
    $pa_sold  = explode(",", $_GET["filter_sold"]);
    $filtros["pa_sold"] = array();
    foreach ($pa_sold as $i => $item) :

        $filtros_slugs[] = addslashes($item);
        $filtros["pa_sold"][] = addslashes($item);
    endforeach;
    $filtros_pa[] = "pa_sold";
    $filtros["pa_sold"] = implode(",", $filtros["pa_sold"]);
}


/* fin filtros*/

?>



<div ng-app="app" ng-controller="ctrl">


<div class="row">
		<div class="col-6">
		<button class="btn btn-success mb-5 " id="export" ng-click="export_products()" ng-disabled="isDownload">Export all to Excel</button>
		<span class="ico-loading" ng-if="isDownload"></span>
		</div>
		<div class="col-6 text-right">
			<span class="text-sort">Sort by: </span> 
		<select  class="select-sort" ng-model="sort" ng-options="item as item.label for item in model.sort_options track by item.val" ng-change="update_sort()">
		</select>
		</div>
	</div>



	<div class="group-container" ng-if="show_sold">
		<div class="group-container-header">
			<h2 class="titulo-marca">Previously sold {{total_selected_sold>0?'('+total_selected_sold+' items selected)':''}}</h2>
			<div class="brand-check"><div class="check-content {{modelSold.checked_all?'checked':''}}"><input type="checkbox" ng-model="modelSold1" ng-click="set_select_all(modelSold)"></div> <label>SELECT ALL</label>
				
			</div>
		</div>
		<div class="" id="seccion-sold">
            <products-group cat="category" brand="brand.slug" filters="filtros" my-model="modelSold"></products-group>
        </div>
	</div>
	<div class="group-container" ng-if="show_not_sold">
		<div class="group-container-header">
			<h2 class="titulo-marca">Not sold {{total_selected_not_sold>0?'('+total_selected_not_sold+' items selected)':''}}</h2>
			<div class="brand-check"><div class="check-content {{modelNotSold.checked_all?'checked':''}}"><input type="checkbox" ng-model="modelSold2" ng-click="set_select_all(modelNotSold)"></div> <label>SELECT ALL</label>
				
			</div>
		</div>
		<div class="" id="seccion-no-sold">
            <products-group cat="category" brand="brand.slug" filters="filtros2" my-model="modelNotSold"></products-group>
        </div>
	</div>
    <!--/.products-->
</div>
<style>
	.group-container-header{
		margin-bottom:10px;
	}
    h2.titulo-marca {
        background-color: #16243E;
        padding: 6px 9px;
        color: white;
        font-size: 21px !important;
		margin-bottom:5px
    }

    .seccion-productos {

        overflow-y: auto;
        max-height: 38rem;
		scrollbar-width: thin;
  		scrollbar-color: #4479DC #CBCBCB;
		
    }
	.seccion-productos::-webkit-scrollbar
	{
		width: 12px;
		background-color: #F5F5F5;
	}
	.seccion-productos::-webkit-scrollbar-track {
		background-color:#CBCBCB;
	}
	.seccion-productos::-webkit-scrollbar-thumb {
		background-color:#4479DC;
		border-radius: 6px;
	}
    .seccion-productos>.row {
        margin-right: 0px;
        margin-left: 0px;

    }

    .card-product {
        border: 1px solid #c4c4c4;
        border-radius: 8px;
        padding: 11px;
        flex: 0 0 23%;
        margin-right: 2%;
        margin-bottom: 2%;
    }

    .card-img {
        height: 150px;
        margin: auto;
    }

    .card-img img {
        margin: auto;
        display: block;
        height: 150px !important;
    }

    .card-product a.link {
        width: 2rem;
        height: 2rem;
        display: flex;
        border-radius: 50%;
        position: absolute;
        box-shadow: 0 0 5px black;
        top: 1rem;
        right: 4rem;
        align-items: center;
        justify-content: center;
        visibility: hidden;
        opacity: 0;
        transition: .3s all;
    }

    .card-product:hover a.link {
        top: 1rem;
        right: 1rem;
        visibility: visible;
        opacity: 1;
        transition: .3s all;
    }

    .card-product a.link:hover {
        text-decoration: none;
    }

    .categorias {
        margin-bottom: 7px;
    }

    .div-loading {
        width: 100%;
        height: 80px;
        background: url('<?= get_site_url() ?>/wp-content/themes/porto-child/woo-ajax/loader.gif') no-repeat center;
        background-size: 206px;

    }
    .check-content-float {
        position: absolute;
        top: 1rem;
        left: 1rem;
    }
    .check-content {
        display:inline-block;
        width: 20px;
        height:20px;
        border:1px solid #ccc;
        background:#fff;
    }
    .check-content:hover{
        box-shadow:0px 0px 1px 2px #000;
    }
    .check-content input{
        opacity: 0;
        zoom:2;
    }
    .check-content.checked{
        background-color:#000;
	background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='iso-8859-1'%3F%3E%3C!-- Generator: Adobe Illustrator 18.0.0  SVG Export Plug-In . SVG Version: 6.00 Build 0) --%3E%3C!DOCTYPE svg PUBLIC '-//W3C//DTD SVG 1.1//EN' 'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd'%3E%3Csvg version='1.1' id='Capa_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 297 297' style='enable-background:new 0 0 297 297%3B fill:%23fff' xml:space='preserve'%3E%3Cg%3E%3Cpath d='M294.033 82.033l-54.675-54.701c-1.899-1.901-4.479-2.97-7.167-2.97c-2.688 0-5.268 1.068-7.168 2.97L113.636 138.765L71.975 97.09c-1.901-1.9-4.479-2.969-7.169-2.969c-2.688 0-5.267 1.069-7.167 2.97L2.966 151.794c-3.955 3.958-3.955 10.372 0.001 14.329l103.501 103.545c1.9 1.902 4.478 2.97 7.168 2.97c2.689 0 5.267-1.067 7.167-2.97L294.033 96.361C297.989 92.405 297.989 85.99 294.033 82.033z'/%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3C/svg%3E");
    background-repeat:no-repeat;
    background-position:center;
    background-size:12px;
    border:1px solid #000;
    }
    .check-content.check-disabled{
        background-color: #eee !important;
        border-color: #ddd !important;
    }
    .check-content.check-disabled:hover{
        box-shadow:none !important;
    }

    .brand-check label {
        margin: 0;
    }

    .brand-check {
        display: inline-flex;
        
        align-items: center;
        width: 7rem;
        justify-content: space-between;
    }
    .ico-loading{
        color:transparent !important;
        background-image: url('<?= get_site_url() ?>/wp-content/themes/porto-child/woo-ajax/loader.gif');
        background-repeat:no-repeat;
        background-position:center;
        background-size:100px;
        display:inline-block;
        width: 40px;
        height:40px;
        vertical-align:top;
    }
    .card-product .product-price {
    color: #000;
    font-size: 20px;
    font-weight: 700;
	}
	.group-container.hide-header .group-container-header h2{
		display:none;
	}
	.group-container.hide-header .seccion-productos {

		overflow-y: initial;
		max-height: none;
		height: auto;
	}
	.btn.btn-success{
		background:#3C3939;
		border:1px solid #3C3939;
		border-radius:3px;
	}
	.group-container{
		margin-bottom:30px;
	}
</style>

<script>
    var base_url="<?= get_site_url() ?>";
</script>
<script>
    angular.module("fexpro_components", []).controller("fexpro_ctrl", function($scope, $http) {})
        .component("productsGroup", {
            template: `<div class="seccion-productos"><div class="row">
                        <div class="col-3 card-product" ng-repeat="item in productos">
                            <div class="card-img">
                                <a href="{{item.link}}"><img ng-src="{{item.img[0]}}"></a>
                                <div class="check-content check-content-float {{item.checked?'checked':''}}  {{is_selected_all?'check-disabled':''}}"><input type="checkbox" name="checkbox" value="{{item.id}}" ng-change="set_select(item)" ng-model="item.checked" ng-disabled="is_selected_all" ></div>
                            </div>
                            <div class="categorias">
                                <span>{{item.terms}}</span>
                                <!-- <a href="{{base_url}}/product-category/{{item_cat.slug}}" ng-repeat="item_cat in item.terms">{{item_cat.name}}</a>  -->
                            </div>
                            <a href="{{item.link}}"><h5>{{item.titulo}}</h5></a>
                            <span class="product-price">{{item.price[0] | currency:'$'}} {{item.price[1]?' - ':''}} {{item.price[1] | currency:'$'}}</span>
                        </div>
                        <div class="col-12" ng-if="loading"><div class="div-loading"></div></div>
                    </div>
                    </div>
                    <div class="paginationjs" ng-if="pages>1">
                    	<div class="paginationjs-pages">
                    		<ul>
                    			<li class="paginationjs-next J-paginationjs-next" style="margin-left:0px" title="Next page" ><a href="javascript:void(0)" ng-click="next_pag()">See more <i class="fas fa-arrow-right"></i></a></li>
                    		</ul>
                    	</div>
                    <div class="paginationjs-nav J-paginationjs-nav">{{pag}} / {{pages}}</div>
                </div>
                    `,
                    replace: false,
            bindings: {
                cat: "<",
                brand: "<",
                filters: "<",
                myModel:"="
            },
            controller: function($scope, $http, $element) {
                let $ctrl = this;
                $scope.base_url=base_url;
				

                $scope.pag = 1;
                $scope.brand = null;

                $scope.productos = [];
                $scope.pages = 1;
                $scope.total_products=0;
                $scope.all_ids=[];
                $scope.loading = false;
                $scope.is_selected_all=false;
                

                $scope.get_products = function() {
                    return;
                    $scope.loading = true;
                    $scope.params = {
                        cat: $ctrl.cat,
                        brand: $scope.brand,
                        pag: $scope.pag,
                        get_totals: $scope.pag == 1 ? 1 : null
                    }
                    if ($ctrl.filters) {
                        for (i in $ctrl.filters) {
                            $scope.params[i] = $ctrl.filters[i];
                        }
                    }
                    $http({
                        method: "GET",
                        url: $scope.base_url+'/wp-admin/admin-ajax.php?action=get_ajax_products_stock',
                        params: $scope.params
                    }).then(function(response) {
                        if ($scope.pag == 1) {
                            $scope.pages = response.data.pages;
                            $scope.total_products=response.data.total;
                            $ctrl.myModel.total_products =response.data.total;
                            $ctrl.myModel.all_ids = response.data.all_ids;
                        }
                        for(i in response.data.products){
                        	response.data.products[i].terms=response.data.products[i].terms.map(function(elem){return elem.name;}).join(', ');
                        }
                       
                        $scope.productos = $scope.productos.concat(response.data.products);

                        if($scope.is_selected_all){
                            $scope.set_checked_all(true);
                        }
                        //$scope.productos = response.data.products;
                        $scope.loading = false;
                        $scope.autoscroll();
                        //icheck_init();
						$scope.$emit("over_end");
                    });
                };
                $ctrl.$onInit = function() {
                    $scope.brand = angular.copy($ctrl.brand);
                    $ctrl.myModel.products_selected=[];
					$ctrl.myModel.set_checked_all=$scope.set_checked_all;
                    $scope.get_products();
                    //icheck_init();

                };

                $scope.next_pag = function() {
                    if (!$scope.loading && $scope.pag < $scope.pages) {
                        $scope.pag++;
						$scope.autoscroll();
                        $scope.get_products();
                        //$scope.autoscroll();
                        //icheck_init();
                    }

                };
                $scope.autoscroll = function() {
                    target = jQuery($element).find(".seccion-productos");
                    console.log(target);
                    max_h = target.children(".row").outerHeight();
                    target.animate({
                        "scrollTop": max_h + "px"
                    }, 500);
                    //icheck_init();
                };

			

				jQuery($element).find(".seccion-productos").on("scroll", function() {
					current_scroll = $(this).scrollTop();
					wh = $(this).height();
					max_h = $(this).children(".row").outerHeight();
					
					if (current_scroll==parseInt(max_h-wh) ) {
						$scope.next_pag();


					}

				});


                $scope.set_select=function(item){

                    $scope.fill_selected();
                };
                $scope.fill_selected=function(){
                    //console.log($scope.productos);
                    $ctrl.myModel.products_selected=[];
                    for(i in $scope.productos){
                        if($scope.productos[i].checked){
                            $ctrl.myModel.products_selected.push($scope.productos[i].id);
                        }
                    }
                   //console.log($ctrl.myModel.products_selected);
                 
                   $scope.$emit("update_selected");
                }
				$scope.$on("next_pag",function(evt,data){

					$scope.next_pag();
				});
				$scope.$on("reset_search",function(){
					$scope.pag=1;
					$scope.productos = [];
					$scope.get_products();
				});

				$scope.set_checked_all=function(check){
					for(i in $scope.productos){
						$scope.productos[i].checked=check;
                    }
                    $scope.is_selected_all=check;
					$scope.fill_selected();
					
				}	
            }
        });
    angular.module("app", ["fexpro_components"])
        .controller("ctrl", function($scope, $timeout,$http) {
            $scope.model = {};
			
			$scope.model.sort_options=[
      			{val:'price_asc',label:'Price: low to high'},
      			{val:'price_desc',label:'Price: high to low'},
                // {val:'stock_asc',label:'Stock: low to high'},
                // {val:'stock_desc',label:'Stock: high to low'},
                {val:'alphabethic_asc',label:'Alphabethic: A to Z'},
                {val:'alphabethic_desc',label:'Alphabethic: Z to A'},
                {val:'newest_asc',label:'Newest First'},
                {val:'newest_desc',label:'Oldest First'},
      			{val:'default',label:'Default sorting'},
      		];
      		$scope.sort=$scope.model.sort_options[6];
            $scope.category = "<?= $query_current->slug ?>";
            $scope.base_url=base_url;
			
			$scope.show_not_sold=false;

            $scope.filtros = <?= json_encode($filtros) ?>;
			$scope.filtros.only_pop=1;
			$scope.filtros2=angular.copy($scope.filtros);

			//console.log($scope.filtros);

			
            $scope.show_not_sold=true;
            $scope.show_sold=true;

            if($scope.filtros.pa_sold){
				let _sold = $scope.filtros.pa_sold.split(",");
				let has_sold = _sold[0] == "sold" || _sold[1] == "sold";
				let has_not_sold = _sold[0] == "not_sold" || _sold[1] == "not_sold";
                if(!has_sold){
                    $scope.show_sold = false;
                }
                if(!has_not_sold){
                    $scope.show_not_sold = false;
                }
			}

            $scope.filtros.pa_sold="sold";
            $scope.filtros2.pa_sold="not_sold";

            $scope.url_export="";
            $scope.isDownload=false;

			$scope.modelSold={};
			$scope.modelNotSold={};

            $scope.total_selected_sold=0;
            $scope.total_selected_not_sold=0;

            $ = jQuery;

            $scope.is_over = false;

            $scope.show_more = function() {
				if(!$scope.show_not_sold){
					console.log("show_mode")
					$scope.$broadcast("next_pag", {});
				}
            };
           
			$scope.$on("over_end",function(){
				$scope.is_over = false;
			});
            $(document).on("scroll", function() {
                current_scroll = $("html").scrollTop();
                wh = $(window).height();
                body_h = $("body").height() - wh;
                if (!$scope.is_over && (((current_scroll + (wh / 2)) >= body_h) || ($(".seccion-productos").height()<1760 ) )) {
                    $scope.is_over = true;

                    $scope.show_more();
                    //icheck_init()


                }

            });

      
            $scope.set_select_all=function(model){
				if(model.checked_all){
					model.checked_all=false;
                	model.set_checked_all(false);
				}else{
					model.checked_all=true;
                	model.set_checked_all(true);
				}
            };
            $scope.$on("update_selected",function(){
                if($scope.modelSold.products_selected){
                    $scope.total_selected_sold=$scope.modelSold.products_selected.length;
                    if($scope.modelSold.checked_all){
                        $scope.total_selected_sold = $scope.modelSold.total_products;
                    }
                }
                if($scope.modelNotSold.products_selected){
                    $scope.total_selected_not_sold=$scope.modelNotSold.products_selected.length;
                    if($scope.modelNotSold.checked_all){
                        $scope.total_selected_not_sold = $scope.modelNotSold.total_products;
                    }
                }
                
               
            });
            $scope.export_products=function(){

				let selected={products:[],allsold:false,allnotsold:false};
                if($scope.modelSold.products_selected){
                    selected.products=$scope.modelSold.products_selected;
                    if($scope.modelSold.checked_all){
                        selected.products=$scope.modelSold.all_ids;
                        //selected.allsold=true;
                        //selected.sold_filters=$scope.filtros;
                    }
                }
				if($scope.modelNotSold.products_selected){
                    if($scope.modelNotSold.checked_all){
                         
                       // selected.allnotsold=true;
                        //selected.notsold_filters=$scope.filtros2;
                        selected.products=selected.products.concat($scope.modelNotSold.all_ids);
                    }else{
                        selected.products=selected.products.concat($scope.modelNotSold.products_selected);
                    }
					
				}
                
		
                let params = {
                        cat: $scope.category,
                        //brand: selected.brand.join(","),
                        products:selected.products.join(","),
                       
                    }
                    /*if ($scope.filtros) {
                        for (i in $scope.filtros) {
                            params[i] = $scope.filtros[i];
                        }
                    }*/
                    $scope.isDownload=true;
                    params_str = $.param(params);
                    $scope.url_export = $scope.base_url+'/wp-admin/admin-ajax.php?action=export_catalog&';//+params_str;
                    downloadUsingFetch($scope.url_export,params,function(){
                        $scope.isDownload=false;
                        $scope.$apply();
                    });
            };

			$scope.update_sort=function(){
            	$scope.pag=1;
            	$scope.productos=[];
				$scope.filtros.sort=$scope.sort.val;
				$scope.filtros2.sort=$scope.sort.val;
            	$scope.$broadcast("reset_search"); 
            };

        });
</script>
<script>
  
  async function downloadUsingFetch(URLDownload,data,callback) {

    const config = {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    }

  const image = await fetch(URLDownload,config);
  const imageBlog = await image.blob();
  const imageURL = URL.createObjectURL(imageBlog);

  const anchor = document.createElement("a");
  anchor.href = imageURL;
  anchor.download = "download.xlsx";//FILE_NAME;

  document.body.appendChild(anchor);
  anchor.click();
  document.body.removeChild(anchor);

  URL.revokeObjectURL(imageURL);
    if(typeof callback === "function"){
        callback();
    }
}

</script>
<script>
    setTimeout(() => {
        jQuery("#woocommerce_layered_nav-24 ul li").addClass("chosen").addClass("woocommerce-widget-layered-nav-list__item--chosen");    
    }, 1000);
    
</script>
<?php
get_footer();
?>