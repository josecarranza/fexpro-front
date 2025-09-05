<?php
get_header();
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>

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
    $filtros["meta_delivery_date"] = preg_replace("/[^a-zA-Z0-9\-,_]+/", "",$_GET["filter_delivery"]);
}

/* fin filtros*/
$category = !isset($_GET["filter_category"])?$query_current->slug : preg_replace("/[^a-zA-Z0-9\-,_]+/", "",$_GET["filter_category"]) ;
$category2 = isset($_GET["filter_category2"])?preg_replace("/[^a-zA-Z0-9\-,_]+/", "",$_GET["filter_category2"]):"";
if($category2!=""){
    $filtros["filter_category2"] = $category2;
}
if (isset($_GET["filter_stock_min"]) && $_GET["filter_stock_min"] != "") {
    $filtros["filter_stock_min"] = (int)$_GET["filter_stock_min"];
}
if (isset($_GET["filter_stock_max"]) && $_GET["filter_stock_max"] != "") {
    $filtros["filter_stock_max"] = (int)$_GET["filter_stock_max"];
}
?>



<div ng-app="app" ng-controller="ctrl" ng-cloak >


	<div class="row">
		<div class="col-12 text-right">
			<span class="text-sort">Sort by: </span> 
		<select  class="select-sort" ng-model="sort" ng-options="item as item.label for item in model.sort_options track by item.val" ng-change="update_sort()">
		</select>
		</div>
	</div>
	<br><br>
    <div class="seccion-productos">
    	<div class="row">
            <div class="col-3" ng-repeat="item in productos">
            	<div class="card-product">
	                <div class="card-img">
	                	<a href="{{item.link}}">
	                    <img ng-src="{{item.img[0]}}">
	                  	</a>
	                </div>
	                <div class="categorias">
	                	<span>{{item.terms}}</span>
	                	
	                </div>
	                <a href="{{item.link}}"><h5>{{item.titulo}}</h5></a>
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
</div>
<style>
 
    h2.titulo-marca {
        background-color: #2A5277;
        padding: 6px 9px;
        color: white;
        font-size: 21px !important;
        border-bottom: 3px solid #CC132C;
    }

    .seccion-productos {
    	margin-right: -10px
       
    }

    .seccion-productos>.row {
        margin-right: 0px;
        margin-left: 0px;

    }

    .card-product {
        border: 1px solid #EDEDED;
        border-radius: 5px;
        padding: 11px;
        /*flex: 0 0 23%;
        margin-right: 2%;
        margin-bottom: 2%;*/
        margin-bottom: 20px;
    }

    .card-img {
        height: 200px;
        margin: auto;
    }

    .card-img img {
        margin: auto;
        display: block;
        height: 200px !important;
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

    .card-product .categorias {
        margin-bottom: 7px;
        font-size: 12px;
        color: #767676;
        line-height: 14px;
    }
    .card-product h5{
    	color: #000;
    	font-size: 16px;
    	font-weight: 500;
    	min-height: 36px;
    }
    .card-product .product-price{
    	color: #000;
    	font-size: 20px;
    	font-weight: 700;
    }
    .div-loading {
        width: 100%;
        height: 80px;
        background: url('<?= get_site_url() ?>/wp-content/themes/porto-child/woo-ajax/loader.gif') no-repeat center;
        background-size: 206px;

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
                {val:'newest_asc',label:'Newest'},
                {val:'newest_desc',label:'Oldest'},
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

            $scope.productos=[];

            $ = jQuery;

            $scope.is_over = false;

            $scope.get_products = function() {
                    $scope.loading = true;
                    $scope.params = {
                        cat: $scope.category,
                        pag: $scope.pag,
                        get_totals: $scope.pag == 1 ? 1 : null,
                        sort:$scope.sort.val
                    }
                    if ($scope.filtros) {
                        for (i in $scope.filtros) {
                            $scope.params[i] = $scope.filtros[i];
                        }
                    }
                    $http({
                        method: "GET",
                        url: $scope.base_url+'/wp-admin/admin-ajax.php?action=get_ajax_products_stock',
                        params: $scope.params
                    }).then(function(response) {
                        if ($scope.pag == 1) {
                            $scope.pages = response.data.pages;
                        }
                        if(response.data.products.length==0){
                            $scope.have_more=false;
                        }
                        for(i in response.data.products){
                        	response.data.products[i].terms=response.data.products[i].terms.map(function(elem){return elem.name;}).join(', ');
                        }
                        $scope.productos = $scope.productos.concat(response.data.products);
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


            $(document).on("scroll", function() {
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

            });
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