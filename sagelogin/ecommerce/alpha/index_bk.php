<?php 
require_once 'include/common.php';
include ('include/FexproReporte.php');
get_currentuserinfo();

if(is_user_logged_in()) {


$date_from = date('Y-m-d');
$date_to =  date('Y-m-d');
/*$date_from = date('2021-06-23');
$date_to =  date('2021-06-23');*/

/*$post_status = implode("','", array('wc-processing', 'wc-completed','wc-cancelled','wc-pending','wc-hold') );
    AND post_status IN ('{$post_status}')*/
$post_status = implode("','", array('wc-processing', 'wc-pending', 'wc-presale3', 'wc-presale5') );


/* Yearly income count*/
$yearToDate = date('Y').'-01-01';
$yearFromDate = date('Y').'-12-31';
$orderReceivedYearArr = $wpdb->get_results( "SELECT ID,post_status, DATE_FORMAT(post_date,'%d') AS post_day FROM $wpdb->posts
    WHERE post_type = 'shop_order'
    AND post_status IN ('{$post_status}')
    AND post_date BETWEEN '{$yearToDate}' AND '{$yearFromDate}'
    ORDER BY post_date ASC
");

if(count($orderReceivedYearArr) > 0){


    foreach($orderReceivedYearArr as $key => $postId){
       $orderReceivedYearSum += get_post_meta($postId->ID,'_order_total',true);
    }

    $orderReceivedYearTotal =  number_format((float)$orderReceivedYearSum, 2);

    /*setlocale(LC_MONETARY, 'en_IN');
    $orderReceivedYearTotal = money_format('%!i', $orderReceivedYearSum);*/ 
}



/* END */


/* ORDER STATS COde start */
$totalOrderCount = $wpdb->get_results( "SELECT ID FROM $wpdb->posts WHERE post_type = 'shop_order'
    AND post_status IN ('wc-pending','wc-processing', 'wc-presale3')
");

$FW21OrderCount = $wpdb->get_results( "SELECT ID FROM $wpdb->posts WHERE post_type = 'shop_order'
    AND post_status IN ('wc-pending')
");

$FexproPOPOrderCount = $wpdb->get_results( "SELECT ID FROM $wpdb->posts WHERE post_type = 'shop_order'
    AND post_status IN ('wc-processing')
");

$SpringSummer22 = $wpdb->get_results( "SELECT ID FROM $wpdb->posts WHERE post_type = 'shop_order'
    AND post_status IN ('wc-presale6')
");

$Fallwinterpr5 = $wpdb->get_results( "SELECT ID FROM $wpdb->posts WHERE post_type = 'shop_order'
    AND post_status IN ('wc-presale7')
");

/* END */


/* PRODUCT SALES by month code start */
//

if($_GET['month']=='january'){ $currentDate = '2021-01-21'; }
else if($_GET['month']=='february'){ $currentDate = '2021-02-21'; }
else if($_GET['month']=='march'){ $currentDate = '2021-03-21'; }
else if($_GET['month']=='april'){ $currentDate = '2021-04-21'; }
else if($_GET['month']=='may'){ $currentDate = '2021-05-21'; }
else if($_GET['month']=='june'){ $currentDate = '2021-06-21'; }
else if($_GET['month']=='july'){ $currentDate = '2021-07-21'; }
else if($_GET['month']=='august'){ $currentDate = '2021-08-21'; }
else if($_GET['month']=='september'){ $currentDate = '2021-09-21'; }
else if($_GET['month']=='october'){ $currentDate = '2021-10-21'; }
else if($_GET['month']=='november'){ $currentDate = '2021-11-21'; }
else if($_GET['month']=='december'){ $currentDate = '2021-12-21'; }
else{   $currentDate = Date('Y-m-d'); $currentMonth = Date('m'); }

$monthStartDate = date('Y-m-1',strtotime($currentDate));
$monthLastDate = date('Y-m-t',strtotime($currentDate));
$monthLastDate = explode('-',$monthLastDate);
$monthLastDate[2] = $monthLastDate[2] + 1;
$monthLastDate = implode('-',$monthLastDate);


$monthStartDay = date('1',strtotime($currentDate));
$monthLastDay = date('t',strtotime($currentDate));


$SpringSummer22ProductSaleArr = $wpdb->get_results( "SELECT ID,post_status, DATE_FORMAT(post_date,'%d') AS post_day FROM $wpdb->posts
    WHERE post_type = 'shop_order'
    AND post_status IN ('{$post_status}')
    AND (post_date >= '{$monthStartDate}' AND post_date <= '{$monthLastDate}' )
    ORDER BY post_date ASC
");

?>
 <?php 

$post_status = array();
$wcProcessing = array();
$wcPresale3 = array();
$wcPresale5 = array();
$wcFw21 = array();
foreach ($SpringSummer22ProductSaleArr as $key => $value) {
     if($value->post_status == 'wc-pending'){
        $wcFw21[$value->post_day][]=$value->post_day;   
     }else if($value->post_status == 'wc-processing'){
        $wcProcessing[$value->post_day][]=$value->post_day;   
     }else if($value->post_status == 'wc-presale3'){
        $wcPresale3[$value->post_day][]=$value->post_day;   
     }else if($value->post_status == 'wc-presale5'){
        $wcPresale5[$value->post_day][]=$value->post_day;   
     }else{}
}


$post_status_arr1 = $post_status_arr2 = $post_status_arr3 = $post_status_arr5 =  [];
$totalFW21Orders = $totalPOPOrders = $totalFW21Orders = $totalPresale5rders = '';
for ($i= intval($monthStartDay); $i <= intval($monthLastDay) ; $i++) { 
    $total_post_status_arr[] = intval(str_pad($i, 2, "0", STR_PAD_LEFT));       
    $post_status_arr1[] = count($wcFw21[str_pad($i, 2, "0", STR_PAD_LEFT)]);    
    $post_status_arr2[] = count($wcProcessing[str_pad($i, 2, "0", STR_PAD_LEFT)]); 
    $post_status_arr3[] = count($wcPresale3[str_pad($i, 2, "0", STR_PAD_LEFT)]); 
    $post_status_arr5[] = count($wcPresale5[str_pad($i, 2, "0", STR_PAD_LEFT)]); 
}

$totalFW21Orders = array_sum($post_status_arr1);
$totalPOPOrders = array_sum($post_status_arr2);
$totalPresale3Orders = array_sum($post_status_arr3);
$totalPresale5rders = array_sum($post_status_arr5);


$fexproReporte = new FexproReporte();

$ventas_pais = $fexproReporte->totalUnidadesPais();

echo "<pre>";
print_r($ventas_pais);
echo "</pre>";

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

    <link href="include/css/custom-fexpro.css" rel="stylesheet">

    <!-- <script src="https://cdn.anychart.com/releases/8.0.1/js/anychart-base.min.js"></script> -->


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="skin-default fixed-layout">
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
                        <!--End Logo icon -->
                        <!-- Logo text --><span>
                         <!-- dark Logo text -->
                         
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
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <?php /* <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="ti-email"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                                <ul>
                                    <li>
                                        <div class="drop-title">Notifications</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Luanch Admin</h5> <span class="mail-desc">Just see the my new admin!</span> <span class="time">9:30 AM</span> </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="btn btn-success btn-circle"><i class="ti-calendar"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Event today</h5> <span class="mail-desc">Just a reminder that you have event</span> <span class="time">9:10 AM</span> </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="btn btn-info btn-circle"><i class="ti-settings"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Settings</h5> <span class="mail-desc">You can customize this template as you want</span> <span class="time">9:08 AM</span> </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center link" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li> */ ?>
                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                       <?php /* <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon-note"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a>
                            <div class="dropdown-menu mailbox dropdown-menu-right animated bounceInDown" aria-labelledby="2">
                                <ul>
                                    <li>
                                        <div class="drop-title">You have 4 new messages</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="user-img"> <img src="../assets/images/users/1.jpg" alt="user" class="img-circle"> <span class="profile-status online pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:30 AM</span> </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="user-img"> <img src="../assets/images/users/2.jpg" alt="user" class="img-circle"> <span class="profile-status busy pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Sonu Nigam</h5> <span class="mail-desc">I've sung a song! See you at</span> <span class="time">9:10 AM</span> </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="user-img"> <img src="../assets/images/users/3.jpg" alt="user" class="img-circle"> <span class="profile-status away pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Arijit Sinh</h5> <span class="mail-desc">I am a singer!</span> <span class="time">9:08 AM</span> </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="user-img"> <img src="../assets/images/users/4.jpg" alt="user" class="img-circle"> <span class="profile-status offline pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center link" href="javascript:void(0);"> <strong>See all e-Mails</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>*/ ?>
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- mega menu -->
                        <!-- ============================================================== -->
                        <?php /* <li class="nav-item dropdown mega-dropdown"> <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti-layout-width-default"></i></a>
                            <div class="dropdown-menu animated bounceInDown">
                                <ul class="mega-dropdown-menu row">
                                    <li class="col-lg-3 col-xlg-2 m-b-30">
                                        <h4 class="m-b-20">CAROUSEL</h4>
                                        <!-- CAROUSEL -->
                                        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                            <div class="carousel-inner" role="listbox">
                                                <div class="carousel-item active">
                                                    <div class="container"> <img class="d-block img-fluid" src="../assets/images/big/img1.jpg" alt="First slide"></div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="container"><img class="d-block img-fluid" src="../assets/images/big/img2.jpg" alt="Second slide"></div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="container"><img class="d-block img-fluid" src="../assets/images/big/img3.jpg" alt="Third slide"></div>
                                                </div>
                                            </div>
                                            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a>
                                            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span> <span class="sr-only">Next</span> </a>
                                        </div>
                                        <!-- End CAROUSEL -->
                                    </li>
                                    <li class="col-lg-3 m-b-30">
                                        <h4 class="m-b-20">ACCORDION</h4>
                                        <!-- Accordian -->
                                        <div id="accordion" class="nav-accordion" role="tablist" aria-multiselectable="true">
                                            <div class="card">
                                                <div class="card-header" role="tab" id="headingOne">
                                                    <h5 class="mb-0">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                  Collapsible Group Item #1
                                                </a>
                                              </h5> </div>
                                                <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                                                    <div class="card-body"> Anim pariatur cliche reprehenderit, enim eiusmod high. </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" role="tab" id="headingTwo">
                                                    <h5 class="mb-0">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                  Collapsible Group Item #2
                                                </a>
                                              </h5> </div>
                                                <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
                                                    <div class="card-body"> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" role="tab" id="headingThree">
                                                    <h5 class="mb-0">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                  Collapsible Group Item #3
                                                </a>
                                              </h5> </div>
                                                <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree">
                                                    <div class="card-body"> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="col-lg-3  m-b-30">
                                        <h4 class="m-b-20">CONTACT US</h4>
                                        <!-- Contact -->
                                        <form>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="exampleInputname1" placeholder="Enter Name"> </div>
                                            <div class="form-group">
                                                <input type="email" class="form-control" placeholder="Enter email"> </div>
                                            <div class="form-group">
                                                <textarea class="form-control" id="exampleTextarea" rows="3" placeholder="Message"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-info">Submit</button>
                                        </form>
                                    </li>
                                    <li class="col-lg-3 col-xlg-4 m-b-30">
                                        <h4 class="m-b-20">List style</h4>
                                        <!-- List style -->
                                        <ul class="list-style-none">
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> You can give link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Give link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Another Give link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Forth link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Another fifth link</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li> */ ?>
                        <!-- ============================================================== -->
                        <!-- End mega menu -->
                        <!-- ============================================================== -->
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
                        <h4 class="text-themecolor">Dashboard</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                            <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Info box Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-center">ORDER RECEIVED</h4>
                                <div class="text-center"> 
                                    <h1 class="font-light"> <?php echo (!empty($totalOrderCount)) ? count($totalOrderCount) : 0 ?></h1>
                                </div>
                                <span class="text-success"><?php echo (!empty($totalOrderCount)) ? (count($totalOrderCount) / 100).'%' : '0%'; ?></span>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo (!empty($totalOrderCount)) ? (count($totalOrderCount) / 100).'%' : '0%'; ?>; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-center">FW21 ORDERS</h4>
                                <div class="text-center"> 
                                    <h1 class="font-light"><?php echo (!empty($FW21OrderCount)) ? count($FW21OrderCount) : 0 ?></h1>
                                </div>
                                <span class="text-primary"><?php echo (!empty($FW21OrderCount)) ? (count($FW21OrderCount) / 100).'%' : '0%'; ?></span>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo (!empty($FW21OrderCount)) ? (count($FW21OrderCount) / 100).'%' : '0%'; ?>; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-center">FALL WINTER 23 ORDERS</h4>
							  <div class="text-center"> 
                                    <h1 class="font-light"><?php echo (!empty($SpringSummer22)) ? count($SpringSummer22) : 0 ?></h1>
                                </div>
                                <span class="text-info"><?php echo (!empty($SpringSummer22)) ? (count($SpringSummer22) / 100).'%' : '0%'; ?></span>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo (!empty($SpringSummer22)) ? (count($SpringSummer22) / 100).'%' : '0%'; ?>; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-center">SPRING SUMMER 24 ORDERS</h4>
                              <div class="text-center"> 
                                    <h1 class="font-light"><?php echo (!empty($Fallwinterpr5)) ? count($Fallwinterpr5) : 0 ?></h1>
                                </div>
                                <span class="text-info"><?php echo (!empty($Fallwinterpr5)) ? (count($Fallwinterpr5) / 100).'%' : '0%'; ?></span>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo (!empty($Fallwinterpr5)) ? (count($Fallwinterpr5) / 100).'%' : '0%'; ?>; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-center">YEARLY SALES</h4>
                                <div class="text-center"> 
                                    <h1 class="font-light"> <?php echo (!empty($orderReceivedYearTotal)) ? '$'.$orderReceivedYearTotal : '$00.00' ;  ?></h1>
                                </div>
                                <span class="text-inverse"><?php echo (!empty($orderReceivedYearArr)) ? (count($orderReceivedYearArr) / 100).'%' : '0%'; ?></span>
                                <div class="progress">
                                    <div class="progress-bar bg-inverse" role="progressbar" style="width: <?php echo (!empty($orderReceivedYearArr)) ? (count($orderReceivedYearArr) / 100).'%' : '0%'; ?>; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
                <!-- ============================================================== -->
                <!-- charts -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-8 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex m-b-10 align-items-center no-block">
                                    <h5 class="card-title ">PRODUCT SALES</h5>
                                    <div class="ml-auto">
                                        <div class="customFilters">
                                            <div class="yearMonths mr-3">
                                                <label>Months</label>
                                                <select id="yearMonthsSelect">
                                                    <option value="<?php echo SAGE_SITEURL .'?month=january'; ?>" <?php echo ($_GET['month'] == 'january') ? 'selected="selected"' : '';  ?> <?php echo ($currentMonth == '01') ? 'selected="selected"' : ''; ?>>January</option>
                                                    <option value="<?php echo SAGE_SITEURL .'?month=february'; ?>" <?php echo ($_GET['month'] == 'february') ? 'selected="selected"' : '';  ?> <?php echo ($currentMonth == '02') ? 'selected="selected"' : ''; ?>>February</option>
                                                    <option value="<?php echo SAGE_SITEURL .'?month=march'; ?>" <?php echo ($_GET['month'] == 'march') ? 'selected="selected"' : '';  ?> <?php echo ($currentMonth == '03') ? 'selected="selected"' : ''; ?>>March</option>
                                                    <option value="<?php echo SAGE_SITEURL .'?month=april'; ?>" <?php echo ($_GET['month'] == 'april') ? 'selected="selected"' : '';  ?> <?php echo ($currentMonth == '04') ? 'selected="selected"' : ''; ?>>April</option>
                                                    <option value="<?php echo SAGE_SITEURL .'?month=may'; ?>" <?php echo ($_GET['month'] == 'may') ? 'selected="selected"' : '';  ?> <?php echo ($currentMonth == '05') ? 'selected="selected"' : ''; ?>>May</option>
                                                    <option value="<?php echo SAGE_SITEURL .'?month=june'; ?>" <?php echo ($_GET['month'] == 'june') ? 'selected="selected"' : '';  ?> <?php echo ($currentMonth == '06') ? 'selected="selected"' : ''; ?>>June</option>
                                                    <option value="<?php echo SAGE_SITEURL .'?month=july'; ?>" <?php echo ($_GET['month'] == 'july') ? 'selected="selected"' : '';  ?> <?php echo ($currentMonth == '07') ? 'selected="selected"' : ''; ?>>July</option>
                                                    <option value="<?php echo SAGE_SITEURL .'?month=august'; ?>" <?php echo ($_GET['month'] == 'august') ? 'selected="selected"' : '';  ?> <?php echo ($currentMonth == '08') ? 'selected="selected"' : ''; ?> >August</option>
                                                    <option value="<?php echo SAGE_SITEURL .'?month=september'; ?> <?php echo ($_GET['month'] == 'september') ? 'selected="selected"' : '';  ?>" <?php echo ($currentMonth == '09') ? 'selected="selected"' : ''; ?>>September</option>
                                                    <option value="<?php echo SAGE_SITEURL .'?month=october'; ?>" <?php echo ($_GET['month'] == 'october') ? 'selected="selected"' : '';  ?> <?php echo ($currentMonth == '10') ? 'selected="selected"' : ''; ?>>October</option>
                                                    <option value="<?php echo SAGE_SITEURL .'?month=november'; ?>" <?php echo ($_GET['month'] == 'november') ? 'selected="selected"' : '';  ?> <?php echo ($currentMonth == '11') ? 'selected="selected"' : ''; ?>>November</option>
                                                    <option value="<?php echo SAGE_SITEURL .'?month=december'; ?>" <?php echo ($_GET['month'] == 'december') ? 'selected="selected"' : '';  ?> <?php echo ($currentMonth == '12') ? 'selected="selected"' : ''; ?>>December</option>

                                                </select>    
                                            </div>
                                            <div class="Years mr-3">
                                                <label>Years</label>
                                                <select>
                                                    <option>2021</option>
                                                </select>    
                                            </div>
                                            <ul class="list-inline text-right">
                                                <li><h5><i class="fa fa-circle m-r-5 text-inverse" style="color: #01c0c8;"></i>FW21</h5></li>
                                                <li><h5><i class="fa fa-circle m-r-5 text-info" style="color: #4f5467 !important;"></i>FEXPRO POP</h5></li>
                                                <li><h5><i class="fa fa-circle m-r-5 text-success" style="color: #4168ff !important;"></i>SS22 PRESALE3</h5></li>
                                                <li><h5><i class="fa fa-circle m-r-5 text-success" style="color: #2ee301 !important;"></i>FF22 </h5></li>
                                            </ul>
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                                 <div class="d-flex m-b-40 align-items-center no-block">
                                <div class="ml-auto customOrderTotal">
                                        <label>Total Orders</label>
                                        <?php if($totalFW21Orders != '0') :?> 
                                            <div class="fw21OrderTotal">
                                        
                                                <?php echo $totalFW21Orders; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($totalPOPOrders != '0') :?> 
                                            <div class="totalPOPOrders">
                                                <?php echo $totalPOPOrders; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($totalPresale3Orders != '0') :?> 
                                            <div class="totalPresale3Orders">
                                                <?php echo $totalPresale3Orders; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($totalPresale5rders != '0') :?> 
                                            <div class="totalPresale5rders">
                                                <?php echo $totalPresale5rders; ?>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>

                              <!--   <div id="morris-area-chart2" style="height: 400px;"></div> -->

                                 <div id="morris-area-chart"></div>
                               
                                 <input type="hidden" name="morris_area_line_chart_loop" id="morris_area_line_chart_loop" value="<?php echo json_encode($total_post_status_arr); ?>">
                                 <input type="hidden" name="post_status_arr1" id="post_status_arr1" value="<?php echo json_encode($post_status_arr1); ?>">
                                 <input type="hidden" name="post_status_arr2" id="post_status_arr2" value="<?php echo json_encode($post_status_arr2); ?>">
                                 <input type="hidden" name="post_status_arr3" id="post_status_arr3" value="<?php echo json_encode($post_status_arr3); ?>">
                                 <input type="hidden" name="post_status_arr5" id="post_status_arr5" value="<?php echo json_encode($post_status_arr5); ?>">
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-4 col-md-12">
                        <div class="row">
                            <!-- Column -->
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">ORDER STATS</h5>
                                        <div id="morris-donut-chart" class="ecomm-donute"></div>
                                        <ul class="list-inline m-t-30 text-center mb-1 d-flex">
                                            <li class="list-inline-item p-r-20">
                                                <h5 class="text-muted"><i class="fa fa-circle" style="color: #fb9678;"></i> Order</h5>
                                                <h4 class="m-b-0 totalOrderCount"><?php echo (!empty($totalOrderCount)) ? count($totalOrderCount) : 0 ; ?></h4>
                                            </li>
                                            <li class="list-inline-item p-r-20">
                                                <h5 class="text-muted"><i class="fa fa-circle" style="color: #01c0c8;"></i> FW21</h5>
                                                <h4 class="m-b-0  totalPendingOrderCount"><?php echo (!empty($FW21OrderCount)) ? count($FW21OrderCount) : 0 ; ?></h4>
                                            </li>
                                            <li class="list-inline-item">
                                                <h5 class="text-muted"> <i class="fa fa-circle" style="color: #4F5467;"></i> Fexpro POP</h5>
                                                <h4 class="m-b-0 totalDeliveredOrderCount"><?php echo (!empty($FexproPOPOrderCount)) ? count($FexproPOPOrderCount) : 0 ; ?></h4>
                                            </li>
											<li class="list-inline-item">
                                                <h5 class="text-muted"> <i class="fa fa-circle" style="color: #4168ff;"></i> Spring Summer 22</h5>
                                                <h4 class="m-b-0 totalpresaleOrderCount"><?php echo (!empty($SpringSummer22)) ? count($SpringSummer22) : 0 ; ?></h4>
                                            </li>
                                            <li class="list-inline-item p-r-5">
                                                <h5 class="text-muted"> <i class="fa fa-circle" style="color: #2ee301;"></i> FALL WINTER 22</h5>
                                                <h4 class="m-b-0 totalFallwinterpr5"><?php echo (!empty($Fallwinterpr5)) ? count($Fallwinterpr5) : 0 ; ?></h4>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <!-- Column -->
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- charts -->
                <!-- ============================================================== -->
                 <!-- <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Order Lists</h5>
                                <div class="table-responsive m-t-30">
                                    <table class="table product-overview">
                                        <thead>
                                            <tr>
                                                <th>Order number</th>
                                                <th>Client Name</th>
                                                <th>Company Name</th>
                                                <th>Total Units</th>
                                                <th>Salesperson</th>
                                                <th>Delivery date</th>
                                                <th>Date</th>
                                                <th>Billing</th>
                                                <th>Total</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Steave Jobs</td>
                                                <td>#85457898</td>
                                                <td>
                                                    <img src="../assets/images/gallery/chair.jpg" alt="iMac" width="80">
                                                </td>
                                                <td>Rounded Chair</td>
                                                <td>20</td>
                                                <td>10-7-2017</td>
                                                <td>10-7-2017</td>
                                                <td>10-7-2017</td>
                                                <td>
                                                    <span class="label label-success">Paid</span>
                                                </td>
                                                <td><a href="javascript:void(0)" class="text-inverse p-r-10" data-toggle="tooltip" title="" data-original-title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="" data-toggle="tooltip" data-original-title="Delete"><i class="ti-trash"></i></a></td>
                                            </tr>
                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  -->
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" data-skin="skin-default" class="default-theme working">1</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-red" class="red-theme">3</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-blue" class="blue-theme">4</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-megna" class="megna-theme">6</a></li>
                                <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                                <li><a href="javascript:void(0)" data-skin="skin-default-dark" class="default-dark-theme ">7</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-red-dark" class="red-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-megna-dark" class="megna-dark-theme ">12</a></li>
                            </ul>
                            <?php /* <ul class="m-t-20 chatonline">
                                <li><b>Chat option</b></li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/1.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/2.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/3.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/4.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/5.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/6.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/7.jpg" alt="user-img" class="img-circle"> <span>Hritik Roshan<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/8.jpg" alt="user-img" class="img-circle"> <span>Pwandeep rajan <small class="text-success">online</small></span></a>
                                </li>
                            </ul> */ ?>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
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
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--morris JavaScript -->
    <script src="../../assets/node_modules/raphael/raphael-min.js"></script>
    <script src="../../assets/node_modules/morrisjs/morris.min.js"></script>

    

    <!--Custom JavaScript -->
    <script src="dist/js/ecom-dashboard.js"></script>

    <script src="include/js/custom-fexpro.js"></script>

    <script src="dist/js/pages/morris-data.js"></script>


    <script type="text/javascript">
        jQuery(document).ready(function(){

            // jQuery('.user-profile .dropdown a.dropdown-toggle').click(function(e){
                
            //     jQuery('.user-profile .dropdown').find('.dropdown-menu').toggle();
                
            // });

            jQuery('.yearMonths select').change(function(){
                var optionVal = jQuery("#yearMonthsSelect option:selected").val();
                window.location.href = optionVal;
            })
        });
    </script>


</body>

</html>
<?php 
} else {
    header('location: https://shop2.fexpro.com/sagelogin/ecommerce/alpha/pages-login.php');
    exit;
}