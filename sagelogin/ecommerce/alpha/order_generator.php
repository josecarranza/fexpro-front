<?php
require_once 'include/common.php';
include('include/FexproReporte.php');
get_currentuserinfo();

if (!is_user_logged_in()) {
    header('location: https://shop2.fexpro.com/sagelogin/ecommerce/alpha/pages-login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITEURL; ?>/wp-content/uploads/2021/01/logo.png">
    <title>Fexpro Sage</title>
    <!-- chartist CSS -->
    <link href="../../assets/node_modules/morrisjs/morris.css" rel="stylesheet">
    <link href="dist/css/pages/ecommerce.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">

    <link href="include/css/custom-fexpro.css?v=1" rel="stylesheet">

    <!-- <script src="https://cdn.anychart.com/releases/8.0.1/js/anychart-base.min.js"></script> -->


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="skin-default fixed-layout" ng-app="app" ng-controller="ctrl">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Fexpro Sage</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/">
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/logo.webp" alt="homepage" class="dark-logo" />
                            <!-- Light Logo icon -->

                        </b>
                    </a>


                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->

                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">

                        <li class="nav-item right-side-toggle"> <a class="nav-link  waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li>
                    </ul>
                </div>
            </nav>
        </header>

        <?php include('include/sidebar.php'); ?>

        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">Order Generator</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                <li class="breadcrumb-item active">Order Generator</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <label for="">Presale: </label>
                        <select name="" id="" class="form-control" style="width:200px" ng-model="filter_order_status" ng-change="filtrar()"
                            ng-options="item.code as item.name for item in order_status_list">

                        </select>
                    </div>
                    <div class="col-12">
                        <div class="exporting-it">
                            <table class="table table-bordered table-order-generator" id="demo_table">
                                <thead>
                                    <tr>

                                        <th style="vertical-align : middle;text-align:center">PI# <sage-filter2 list="filters.pi" /></th>
                                        <th>Supplier <sage-filter2 list="filters.supplier" /></th>
                                        <th>Sourcing office <sage-filter2 list="filters.sourcing" /></th>

                                        <th>Status <sage-filter2 list="filters.status" /></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="9" style="text-align:center" ng-if="loading">
                                            <span class="ico-loading"></span>
                                        </td>
                                    </tr>
                                    <tr ng-repeat="item in report_pi_filtered">

                                        <td>
                                            <span class="pi_n">{{item.pi_numeral}}</span>
                                            <span class="ico-info" ng-click="showDetail(item)" style="float:right"></span>
                                        </td>
                                        <td>{{item.supplier}}</td>
                                        <td>{{item.sourcing_office}}</td>

                                        <td>{{item.status}}</td>
                                        <td>
                                            <span class="ico-loading" ng-show="item.loading"></span>
                                            <a ng-href="{{item.url_xml}}" target="_blank">
                                                <!-- <button class="btn-dd" ng-click="show_xml(item)" ng-show="!item.loading">Export</button> -->
                                                <button class="btn-dd">Export</button>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal fade modal-detail" id="">
                    <div class="modal-dialog  modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">PI# {{selected.pi_numeral}}</h4>
                                <button type="button" class="close" data-dismiss="modal" onclick="$('.modal-detail').modal('hide');">&times;</button>
                            </div>
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-9">
                                        <div class="box-info-order">
                                            <table>
                                                <tr>
                                                    <th>Supplier</th>
                                                    <td>{{selected.supplier}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Sourcing office</th>
                                                    <td>{{selected.sourcing_office}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total items</th>
                                                    <td>{{total_items| currency:'':0}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total order</th>
                                                    <td>{{total_order | currency:'$'}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-3 text-right">
                                        <a ng-href="{{url_xml}}" class="btn-dd" target="_blank">
                                            View XML
                                        </a>
                                    </div>
                                </div>


                                <div class="exporting-it">
                                    <table class="table table-bordered table-order-generator" id="demo_table2">
                                        <thead>
                                            <tr>
                                                <th style="vertical-align : middle;text-align:center"> <span style="width:224px; display:inline-block">Product image</span>
                                                <th>Style name</th>
                                                <th>Style SKU</th>
                                                <th>Size chart</th>
                                                <th>Units ordered factory</th>
                                                <th>Cost FOB</th>
                                                <th>Total cost</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="6" style="text-align:center" ng-if="loading2">
                                                    <span class="ico-loading"></span>
                                                </td>
                                            </tr>
                                            <tr ng-repeat="row in products">
                                                <td style="white-space:nowrap" class="fixed-image">
                                                    <img src="{{row.image}}" height="100">
                                                    <img src="{{row.image2}}" height="100" ng-if="row.image2!=''" style="margin-right:-10px">
                                                </td>
                                                <td><span>{{row.product_title}}</span></td>
                                                <td><span>{{row.sku}}</span></td>
                                                <td><span style="white-space:nowrap">{{row.size_chart}}</span></td>
                                                <td><span>{{row.ordenado_fab}}</span></td>
                                                <td><span>{{row.fob}}</span></td>
                                                <td><span>{{row.total_cost}}</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- ============================================================== -->
            <!-- End Page wrapper  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->

            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Wrapper -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- All Jquery -->
        <!-- ============================================================== -->
        <script src="../../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
        <!-- Bootstrap tether Core JavaScript -->
        <script src="../../assets/node_modules/popper/popper.min.js"></script>
        <script src="../../assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- slimscrollbar scrollbar JavaScript -->
        <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
        <!--Wave Effects -->
        <script src="dist/js/waves.js"></script>
        <!--Menu sidebar -->
        <script src="dist/js/sidebarmenu.js"></script>
        <!--stickey kit -->
        <script src="../../assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
        <script src="../../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
        <!--Custom JavaScript -->
        <script src="dist/js/custom.min.js"></script>
        <script src="../../assets/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>




        <script src="include/js/custom-fexpro.js"></script>


        <script src="<?php echo SAGE_SITEURL; ?>/alpha/angular.min.js"></script>
        <script src="<?php echo SAGE_SITEURL; ?>/alpha/angular.components.js?v=2.1"></script>
        <script>
            var base_url = "<?= SITEURL ?>";
            angular.module("app", ['util-components'])
                .controller("ctrl", ($scope, $http, $timeout, $httpParamSerializer) => {
                    $scope.model = {};
                    $scope.report_pi = [];
                    $scope.report_pi_filtered = [];
                    $scope.loading = false;
                    $scope.loading2 = false;
                    $scope.selected = {};
                    $scope.url_xml = "";

                    $scope.order_status_list = [];

                    $scope.filters = {
                        supplier: []
                    }

                    $scope.products = [];
                    $timeout(() => {
                        $scope.order_status_get();
                        $scope.getReportPi();
                    });

                    $scope.getReportPi = () => {
                        $scope.loading = true;
                        $http({
                            url: base_url + '/sagelogin/ecommerce/alpha/ajax.php?action=report_group_by_pi',
                            method: "GET",
                            params: {
                                presale_status: $scope.filter_order_status
                            }
                        }).then((response) => {
                            $scope.report_pi = response.data.data;

                            $scope.loading = false;

                            let fil_pi = [];
                            let fil_supplier = [];
                            let fil_sourcing = [];
                            let fil_status = [];

                            $scope.report_pi.forEach(x => {
                                if (!fil_pi.includes(x.pi_numeral)) {
                                    fil_pi.push(x.pi_numeral);
                                }
                                if (!fil_supplier.includes(x.supplier)) {
                                    fil_supplier.push(x.supplier);
                                }
                                if (!fil_sourcing.includes(x.sourcing_office)) {
                                    fil_sourcing.push(x.sourcing_office);
                                }
                                if (!fil_status.includes(x.status)) {
                                    fil_status.push(x.status);
                                }
                                x.url_xml = base_url + '/sagelogin/ecommerce/alpha/ajax.php?action=products_in_pi_order&export=xml&' + $httpParamSerializer({
                                    pi_numeral:x.pi_numeral,
                                    presale_status:$scope.filter_order_status,
                                    sourcing_office:x.sourcing_office,
                                    supplier:x.supplier,
                                    variationID:x.variationID,
                                    download:1
                                });
                            });
                            fil_pi.sort();
                            fil_supplier.sort();
                            fil_sourcing.sort();
                            fil_status.sort();
                            $scope.filters.pi = fil_pi.map(x => ({
                                text: x
                            }));
                            $scope.filters.supplier = fil_supplier.map(x => ({
                                text: x
                            }));
                            $scope.filters.sourcing = fil_sourcing.map(x => ({
                                text: x
                            }));
                            $scope.filters.status = fil_status.map(x => ({
                                text: x
                            }));

                            $scope.apply_filter();

                        });
                    };

                    $scope.showDetail = (item) => {
                        console.log(item);
                        $scope.selected = {
                            ...item,
                            presale_status: $scope.filter_order_status
                        }
                        $(".modal-detail").modal('show');

                        $scope.loading2 = true;
                        $scope.products = [];
                        $scope.total_items = 0;
                        $scope.total_order = 0;
                        $scope.url_xml = base_url + '/sagelogin/ecommerce/alpha/ajax.php?action=products_in_pi_order&export=xml&' + $httpParamSerializer($scope.selected);
                        $http({
                            url: base_url + '/sagelogin/ecommerce/alpha/ajax.php?action=products_in_pi_order',
                            method: "GET",
                            params: $scope.selected
                        }).then((response) => {

                            $scope.products = response.data.data;
                            $scope.loading2 = false;
                            $scope.products.forEach(x => {
                                $scope.total_items += Number(x.ordenado_fab);
                                x.total_cost = (Number(x.ordenado_fab) * (!isNaN(x.fob) ? Number(x.fob) : 0));
                                $scope.total_order += x.total_cost;

                            });

                        });

                    };
                    $scope.upload_ftp = (item) => {
                        console.log(item);
                        $scope.selected = {
                            ...item
                        }
                        item.loading = true;
                        item.status = "EXPORTING";
                        $http({
                            url: base_url + '/sagelogin/ecommerce/alpha/ajax.php?action=products_in_pi_order',
                            method: "GET",
                            params: {
                                ...$scope.selected,
                                upload: 1,
                                export: 'xml',
                                presale_status: $scope.filter_order_status
                            }
                        }).then((response) => {
                            if (response.data.error == 0) {
                                item.status = "EXPORTED";
                            } else {
                                item.status = "PENDING";
                            }
                            item.loading = false;
                        });
                    }
                    $scope.show_xml = (item) => {

                        console.log(item);
                        $scope.selected = {
                            ...item
                        }
                        // item.loading=true;
                        // item.status = "EXPORTING";
                    }
                    $scope.apply_filter = () => {
                        $scope.report_pi_filtered = [];
                        let _data = [...$scope.report_pi];
                        let selected_pi = $scope.filters.pi.filter(x => x.checked).map(x => x.text);
                        let selected_supplier = $scope.filters.supplier.filter(x => x.checked).map(x => x.text);
                        let selected_sourcing = $scope.filters.sourcing.filter(x => x.checked).map(x => x.text);
                        let selected_status = $scope.filters.status.filter(x => x.checked).map(x => x.text);

                        $scope.report_pi_filtered = _data.filter(x =>
                            (selected_pi.length == 0 || selected_pi.includes(x.pi_numeral)) &&
                            (selected_supplier.length == 0 || selected_supplier.includes(x.supplier)) &&
                            (selected_sourcing.length == 0 || selected_sourcing.includes(x.sourcing_office)) &&
                            (selected_status.length == 0 || selected_status.includes(x.status))
                        );
                    }
                    $scope.$on("apply_filters", () => {
                        $scope.apply_filter();
                    });

                    $scope.order_status_get = () => {
                        $http({
                            url: base_url + '/sagelogin/ecommerce/alpha/ajax.php?action=orders_status_get',
                            method: "GET"
                        }).then((response) => {
                            $scope.order_status_list = response.data.data;

                            $scope.filter_order_status = $scope.order_status_list.filter(x => x.default == '1')[0].code;
                        });
                    };

                    $scope.filtrar = () => {
                        $scope.report_pi = [];
                        $scope.report_pi_filtered = [];
                        $scope.getReportPi();
                    }



                });
        </script>

</body>

</html>