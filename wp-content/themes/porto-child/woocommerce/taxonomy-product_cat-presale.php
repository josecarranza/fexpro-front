<?php
get_header();
?>




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
if (isset($_GET["filter_delivery"]) && $_GET["filter_delivery"] != "") {
    $filtros["meta_delivery_date"] = preg_replace("/[^a-zA-Z0-9\-,_\+]+/", "",$_GET["filter_delivery"]);
}
if (isset($_GET["f_division"]) && $_GET["f_division"] != "") {
    $filtros["cat_division"] = preg_replace("/[^a-zA-Z0-9\-,_\+]+/", "",$_GET["f_division"]);
}

if (isset($_GET["f_bc_brand"]) && is_array($_GET["f_bc_brand"]) && count($_GET["f_bc_brand"])>0) {
    $filtros["f_bc_brand"] = preg_replace("/[^a-zA-Z0-9\-,_\+]+/", "",implode(",",$_GET["f_bc_brand"]));
}
if (isset($_GET["f_bc_collection"]) && is_array($_GET["f_bc_collection"]) && count($_GET["f_bc_collection"])>0) {
    $filtros["f_bc_collection"] = preg_replace("/[^a-zA-Z0-9\-,_\+]+/", "",implode(",",$_GET["f_bc_collection"]));
}
if (isset($_GET["f_bc_team"]) && is_array($_GET["f_bc_team"]) && count($_GET["f_bc_team"])>0) {
    $filtros["f_bc_team"] = preg_replace("/[^a-zA-Z0-9\-,_\+ ]+/", "", trim(implode(",",$_GET["f_bc_team"])) );
}
if (isset($_GET["product_type_apparel"]) && $_GET["product_type_apparel"]!="") {
    $filtros["product_type_apparel"] = preg_replace("/[^a-zA-Z0-9\-,_\+ ]+/", "", trim($_GET["product_type_apparel"]));
}
if (isset($_GET["product_type_accesories"]) && $_GET["product_type_accesories"]!="") {
    $filtros["product_type_accesories"] = preg_replace("/[^a-zA-Z0-9\-,_\+ ]+/", "", trim($_GET["product_type_accesories"]));
}
if (isset($_GET["pa_global-brand"]) && $_GET["pa_global-brand"]!="") {
    $filtros["pa_global-brand"] = preg_replace("/[^a-zA-Z0-9\-,_\+ ]+/", "", trim($_GET["pa_global-brand"]));
}

/* fin filtros*/
$category = !isset($_GET["filter_category"])?$query_current->slug : preg_replace("/[^a-zA-Z0-9\-,_\+]+/", "",$_GET["filter_category"]) ;
$category2 = isset($_GET["filter_category2"])?preg_replace("/[^a-zA-Z0-9\-,_\+]+/", "",$_GET["filter_category2"]):"";
if($category2!=""){
    $filtros["filter_category2"] = $category2;
}
if (isset($_GET["filter_stock_min"]) && $_GET["filter_stock_min"] != "") {
    $filtros["filter_stock_min"] = (int)$_GET["filter_stock_min"];
}
if (isset($_GET["filter_stock_max"]) && $_GET["filter_stock_max"] != "") {
    $filtros["filter_stock_max"] = (int)$_GET["filter_stock_max"];
}

$per_page = (int)$_GET["per_page"]??24;
$per_page = !in_array($per_page,[24,48,96])?24:$per_page;
?>



<div ng-app="app" ng-controller="ctrl" ng-cloak >


	<div class="row">
		<div class="col-4 col-lg-6 text-left">
			<span class="text-sort">Sort by: </span> 
            <select  class="select-sort" ng-model="sort" ng-options="item as item.label for item in model.sort_options track by item.val" ng-change="update_sort()">
            </select>
		</div>
        <div class="col-8 col-lg-6 text-right">
            <div class="pagination-bar">
                <div class="numbers-container">
                    <a href="javascript:void(0)" ng-click="prev_pag()"><span class="ico-left"></span></a>
                    <a href="javascript:void(0)" class="{{p==pag && 'selected'}}" ng-repeat="p in pages_arr" ng-click="set_pag(p)">{{p}}</a>
                   
                    <a href="javascript:void(0)" ng-click="next_pag()"><span class="ico-right"></span></a>
                </div>
                <div>
                    <b>{{total_items>per_page?per_page:total_items}} of {{total_items}} results</b>
                </div>
                <div class="selector-per-page">
                    Show
                    <select name="" id="" ng-model="per_page" ng-change="change_per_page()">
                        <option ng-value="24">24</option>
                        <option ng-value="48">48</option>
                        <option ng-value="96">96</option>
                    </select>
                    entries
                </div>
            </div>
        </div>
	</div>
	<br><br>
    <div class="seccion-productos">
    	<div class="row">
            <div class="col-6 col-md-4 col-lg-4 col-xl-3" ng-repeat="item in productos">
            	<div class="card-product">
	                <div class="card-img {{item.show_custom_image=='1' ? 'card-image-slider' : ''}}">
	                	<a href="{{item.link}}?from=presale">
                        <img ng-src="{{item.custom_image[0]}}" ng-if="item.show_custom_image=='1'">
	                    <img ng-src="{{item.img[0]}}">
                        <img ng-src="{{item.img[1]}}" ng-if="item.img[1]!=''">
                        <img ng-src="{{item.img[0]}}" ng-if="item.img[1]==''">
	                  	</a>
	                </div>
                    <div class="product-colors">
                        <span class="color color-{{color}}" ng-repeat="color in item.colors"></span>
                        <span class="more-colors" ng-if="item.colors_count>10">+</span>
                    </div>
	                <div class="name-collection">
	                	<span>COLLECTION: {{item.collection}}</span>
	                </div>

	                <a href="{{item.link}}?from=presale"><h5>{{item.titulo2 ? item.titulo2:item.titulo}}</h5></a>
                    <span class="product-type">{{item.gender}}</span>
	                <span class="product-price">{{item.price[0] | currency:'$'}} {{item.price[1]?' - ':''}} {{item.price[1] | currency:'$'}}</span>
                </div>
            </div>
           
        </div>
        <div class="row" ng-if="!loading && productos.length==0">
            <div class="col-12 text-center">
                <h4>Products not found</h4>
            </div>
        </div>
        <div class="div-loading" ng-if="loading"></div>
     </div>

    <!--/.products-->
    <!--pagination 2-->
    <div class="row" style="margin-bottom:50px">
		<div class="col-4 col-lg-6 text-left">
			
		</div>
        <div class="col-8 col-lg-6 text-right">
            <div class="pagination-bar">
                <div class="numbers-container">
                    <a href="javascript:void(0)" ng-click="prev_pag()"><span class="ico-left"></span></a>
                    <a href="javascript:void(0)" class="{{p==pag && 'selected'}}" ng-repeat="p in pages_arr" ng-click="set_pag(p)">{{p}}</a>
                   
                    <a href="javascript:void(0)" ng-click="next_pag()"><span class="ico-right"></span></a>
                </div>
                <div>
                    <b>{{total_items>per_page?per_page:total_items}} of {{total_items}} results</b>
                </div>
                <div class="selector-per-page">
                    Show
                    <select name="" id="" ng-model="per_page" ng-change="change_per_page()">
                        <option ng-value="24">24</option>
                        <option ng-value="48">48</option>
                        <option ng-value="96">96</option>
                    </select>
                    entries
                </div>
            </div>
        </div>
	</div>
    <!--/.pagination 2-->
</div>
<style>
 
   
    .div-loading {
        width: 100%;
        height: 80px;
        background: url('<?= get_site_url() ?>/wp-content/themes/porto-child/woo-ajax/loader.gif') no-repeat center;
        background-size: 206px;
        margin-bottom: 50px;
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
</style>

<script>
    var base_url="<?= get_site_url() ?>";
</script>
<script>

    angular.module("app", [])
        .controller("ctrl", function($scope, $timeout,$http) {
            $scope.model = {};
      		
      		$scope.model.sort_options=[
      			{val:'price_asc',label:'Price: low to high'},
      			{val:'price_desc',label:'Price: high to low'},
                /*{val:'stock_asc',label:'Stock: low to high'},
                {val:'stock_desc',label:'Stock: high to low'},*/
                {val:'alphabethic_asc',label:'Alphabethic: A to Z'},
                {val:'alphabethic_desc',label:'Alphabethic: Z to A'},
                {val:'newest_asc',label:'Newest First'},
                {val:'newest_desc',label:'Oldest First'},
      			{val:'default',label:'Default sorting'},
      		];
      		$scope.sort=$scope.model.sort_options[6];

            $scope.category = "<?= $category ?>";
            $scope.base_url=base_url;

            $scope.loading = false;
            $scope.have_more=true;
           

            $scope.filtros = <?= json_encode($filtros) ?>;

            $scope.url_export="";
            $scope.isDownload=false;

            $scope.pag=1;
            $scope.pages=1;
            $scope.pages_arr=[];
            $scope.total_items=0;
            $scope.per_page=Number(localStorage.getItem("fex_perpage")??24);

            $scope.productos=[];

            $ = jQuery;

            $scope.is_over = false;

            $scope.get_products = function() {
                    $scope.loading = true;
                    $scope.params = {
                        cat: $scope.category,
                        pag: $scope.pag,
                        get_totals: $scope.pag == 1 ? 1 : null,
                        sort:$scope.sort.val,
                        per_page:$scope.per_page
                    }
                    if ($scope.filtros) {
                        for (i in $scope.filtros) {
                            $scope.params[i] = $scope.filtros[i];
                        }
                    }
                    $scope.productos =[];
                    $http({
                        method: "GET",
                        url: $scope.base_url+'/wp-admin/admin-ajax.php?action=get_ajax_products_stock',
                        params: $scope.params
                    }).then(function(response) {
                        if ($scope.pag == 1) {
                            $scope.pages = response.data.pages;
                            $scope.total_items = response.data.total;
                        }
                        if(response.data.products.length==0){
                            $scope.have_more=false;
                        }
                        for(i in response.data.products){
                        	//response.data.products[i].terms=response.data.products[i].terms.map(function(elem){return elem.name;}).join(', ');
                            response.data.products[i].link+="?";
                            if($scope.filtros.f_bc_team){
                                response.data.products[i].link+="&team="+$scope.filtros.f_bc_team;
                            }
                            if($scope.filtros.pa_color){
                                response.data.products[i].link+="&color="+$scope.filtros.pa_color;
                            }
                            response.data.products[i].colors_count = Object.entries(response.data.products[i].colors).length;
                            response.data.products[i].colors = Object.fromEntries(Object.entries(response.data.products[i].colors).slice(0, 10));
                        }
                        //$scope.productos = $scope.productos.concat(response.data.products);
                        $scope.productos =  response.data.products;

                        $scope.pages_arr = paginate(3,$scope.pag,$scope.pages);

                        //$scope.productos = response.data.products;
                        $scope.loading = false;
                        //$scope.autoscroll();
                        //icheck_init();

                        $timeout(function() {
		                    $scope.is_over = false;
		                    //icheck_init();
		                }, 500);

                    });
                };
            $scope.get_products();

            $scope.update_sort=function(){
            	$scope.pag=1;
            	$scope.productos=[];
            	$scope.get_products();
            };
            $scope.set_pag=function(p){
                if(p=="...")
                return;
                $scope.pag=p;
                $("html").animate({"scrollTop": "0px"},500);
                //$scope.is_over = true;
                $scope.get_products();

            }
            $scope.prev_pag=function(){
                if($scope.pag==1){
                    return;
                }
                $scope.set_pag(Number($scope.pag)-1);
            }
            $scope.next_pag=function(){
                if($scope.pag==$scope.pages){
                    return;
                }
                $scope.set_pag(Number($scope.pag)+1);
            }
            $scope.change_per_page=function(){
                localStorage.setItem("fex_perpage",$scope.per_page);
                $scope.set_pag(1);
            }

            /*$(document).on("scroll", function() {
            	target = $(".seccion-productos");
                current_scroll = $("html").scrollTop();
                wh = $(window).height();
                div_prod=target.offset().top;
                div_h=target.outerHeight();
                max_scroll = (div_prod + div_h) - $(window).height();

                body_h = $("body").height() - wh;
                if (!$scope.is_over &&  current_scroll>=max_scroll) {
                	if($scope.pag>1 && $scope.pag==$scope.pages){
                		return;
                	}
                    if(!$scope.have_more){
                        return;
                    }
                	$scope.pag++;
                    $scope.is_over = true;
                    $scope.get_products();
                    //icheck_init()


                }

            });*/
        });
</script>
<script>
  
  async function downloadUsingFetch(URLDownload,callback) {
  const image = await fetch(URLDownload);
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

<?php
get_footer();
?>