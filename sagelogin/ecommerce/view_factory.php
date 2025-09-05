<?php 
require_once 'include/common.php';
get_currentuserinfo();

if(is_user_logged_in()) { ?>
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
    <link href="dist/css/pages/ecommerce.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">

    <link href="include/css/custom-fexpro.css" rel="stylesheet">

    <script src="https://cdn.anychart.com/releases/8.0.1/js/anychart-base.min.js"></script>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<style>
  
    h2 {text-align: center; padding: 15px 0;  text-transform: uppercase;  font-weight: bold;  color: red;  }
    table#demo {  overflow-x: scroll;  max-width: 100%;  display: block;}
    tbody#myTable > tr > td:first-child img { width: 100px;  }    
    span.error {color: #f00;font-size: 10px;display: block;}
    .order_screen_container {float: left;width: 100%;margin-bottom: 15px;padding: 15px;   }
    a.submit-it {background: black;color: #fff;padding: .375rem .75rem;font-size: 1rem;height: calc(1.5em + .75rem + 2px);line-height: 1.5;
        width: auto;float: right;}   
    a.single-submit-it {background: black;  color: #fff;padding: .375rem .75rem;font-size: 1rem;height: calc(1.5em + .75rem + 2px);line-height: 1.5;
    }
    .order_screen_container input {float: left;width: auto;}
    tbody#myTable tr td .red {border-color: red !important;}
    .cart-sizes-attribute {min-width: 350px;  width: 100%; margin-top: 20px; }
    .cart-sizes-attribute .size-guide h5 {border: solid 1px #000;padding: 20px 9px!important;}
    .size-guide h5{color: #000;font-size: 13px;font-weight: 700;line-height: 20px;margin-bottom: 0;}
    .cart-sizes-attribute .size-guide {display: -webkit-box;display: -ms-flexbox;display: flex;background: #fff;}
    .inner-size { display: block;  width: 100%;  -webkit-box-align: center; -ms-flex-align: center; align-items: center;text-align: center;}
    .cart-sizes-attribute .size-guide .inner-size {border: solid 1px #000;border-right: 0;border-left: 0;}
    .inner-size span:first-child {font-weight: bold;background: #008188;color: #fff;   }
    .inner-size span {display: block;width: 100%;border-bottom: solid 1px #000;border-right: 1px solid #000;color: #000;padding: 5px 10px;}
    span#exportexcel {background: #000;color: #fff;cursor: pointer;font-size: 24px;text-align: center;font-weight: bold;margin-bottom: 15px;
        padding: 5px 15px;display: inline-block;margin-left: 5px;border-radius: 5px;transition: all 0.2s ease;}
    span#exportexcel:hover {background: #b41520;   }
    span#stop-refresh {display: none;color: #f00;font-size: 18px;margin-left: 5px;margin-bottom: 15px;width: 100%; }
    .for-Excel-only, .order1-number2, .factory_order_number{display: none;}
    input.factory_order_number {margin-top: 10px;}
    .add-new {cursor: pointer;background: #000;display: inline-block;color: #fff;padding: 5px;border-radius: 5px;margin: 10px 0;   }
    .order1-number2, .only1 {   text-align: center;font-size: 17px;color: #f00;font-weight: bold;}
    .show .order1-number2{display: block;}
    .adding-data:before {content: "";background: rgba(0,0,0,0.5); z-index: 2; position: absolute; width: 100%; height: 100%;}
    table caption{caption-side: top;}
    .TF.sticky tr.fltrow th {top: -1px !important;background: #af0f2c;}
    .TF.sticky th {top: 33px !important;}
    body table.TF tr.fltrow th {border-bottom: 1px solid #000;border-top: 1px solid #000;border-left: 1px solid #000;border-right: 1px solid #000;
    padding: 0;   color: #fff;}
    a.submit-in-one {position: fixed;bottom: 0; background: #af0f2c; color: #fff; padding: 5px; right: 0; font-size: 18px; cursor: pointer;}
    input.factory_order { max-width: 110px;}
    select.rspg + span { display: none;}
    td.red{ background: #ff00002e;}
    tr.red { background: #f00;}
	span.no-v {
    display: block;
    width: 100%;
    font-size: 16px;
    color: #000;
    font-weight: 500;
}
span.no-v strong {
    color: #f00;
    text-decoration: underline;
    font-weight: 900;
}
  </style>

</head>

<body class="skin-default fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Fexpro Sage admin</p>
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
                    <a class="navbar-brand" href="<?php echo SITEURL; ?>/sagelogin/ecommerce/">
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="<?php echo SITEURL; ?>/sagelogin/ecommerce/logo.webp" alt="homepage" class="dark-logo" />
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
                       
                        
                    </ul>
                    
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
                        <h4 class="text-themecolor">View Factory</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                <li class="breadcrumb-item active">View Factory</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
					
                <div class="row">
                        <?php

delete_transient('getTableBodyData');                        
$return_array = array();
$return_array1 = array();
$return_array2 = array();
global $wpdb;

$namee = $_GET['name'];   

$result_string = ltrim($namee);
$result_string = rtrim($result_string);

/*echo $result_string ."<BR>";
$str = str_replace(' ', '_', strtolower($result_string));

echo $str;*/

if($result_string == 'YANGZHOU YAXIYA HEADWEAR'){
    $namee = 'YANGZHOU YAXIYA HEADWEAR & GAR';
}
if($result_string == 'TAIZHOU J'){
    $namee = 'TAIZHOU J&F HEADWEAR';
}
if($result_string == 'Dishang Group/Weihai Textile Group Import'){
    $namee = 'Dishang Group/Weihai Textile Group Import & Export Co,. Ltd';
}


$getZenlineOrdersList = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ss22_factory_order_confirmation_list WHERE `factoryname` = '$namee'  UNION SELECT * FROM  {$wpdb->prefix}pop_factory_order_confirmation_list  WHERE `factoryname` = '$namee'   UNION SELECT * FROM  {$wpdb->prefix}factory_order_confirmation_list  WHERE `factoryname` = '$namee'  ", ARRAY_A );


foreach($getZenlineOrdersList as $key => $value)
{
    $vid = $value['vid'];
    $allData = $wpdb->get_results("SELECT `order_item_id`,`order_id`   FROM {$wpdb->prefix}wc_order_product_lookup WHERE `variation_id` = '$vid'", ARRAY_A );
    foreach($allData as $bk)
    {
        if ( get_post_status ( $bk['order_id'] ) != 'wc-presale3' )
        {
            continue;
        }
        else
        {
            $return_array1[$value['vid']][] = $bk['order_item_id'];
        }
    }
}
$j = 0;
foreach($return_array1 as $key3 => $value3)
{
    $sum = 0;
    foreach($value3 as $key4 => $abc)
    {
        $c1 = 0;
        $variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
            $get_variation_id = wc_get_order_item_meta( $abc, '_variation_id', true );
            $ap = wc_get_order_item_meta( $abc, '_qty', true );
            if(!in_array($abc, $return_array2))
            {
                if($get_variation_id == $key3)
                {
                    foreach ($variation_size as $key => $size) 
                    {
                        $c1 += $size['value'];
                        $merge1[$key3][$size['label']][] = $ap * $size['value'];
                        $merge3[$size['label']] = $size['label'];
                    }
                    
                }
                array_push($return_array2, $abc);
            }
            
            $sum += $c1 * $ap; 
    }
}
?>

  
<h2><?php echo $_GET['name'] ?></h2>

<div class="order_screen_container">
<span id="exportexcel" onclick="fnExcelReport();" style="display:none;">Export to XLSX</span>
<span id="exportexcel1" onclick="fnExcelReport1();">Export All to XLSX</span>
<span id="stop-refresh">Exporting is inprogress. Please don't refresh the page.</span>
</div>
<table class="table table-bordered" id="demo">
   <thead>
        <tr>
          <th style="vertical-align : middle;text-align:center;">Order Number</th>
          <th style="vertical-align : middle;text-align:center;">Product image</th>
          <th style="vertical-align : middle;text-align:center;">Item name</th>
          <th style="vertical-align : middle;text-align:center;">Style sku</th>
          <?php           
          foreach ($merge3 as $akkk3 => $akkkv3) 
          {
            echo '<th style="vertical-align : middle;text-align:center; display: none">'. $akkk3 .'</th>';
          }
          ?>
          <th style="vertical-align : middle;text-align:center;">Composition</th>
          <th style="vertical-align : middle;text-align:center;">Producto logo</th>
          <th style="vertical-align : middle;text-align:center;">Factory Order</th>
          <th style="vertical-align : middle;text-align:center;">Factory Name</th>
          <th style="vertical-align : middle;text-align:center;">Delivery Date</th>
          <th style="vertical-align : middle;text-align:center;">Cost price</th>
          <th style="vertical-align : middle;text-align:center;">Total Amount</th>
        </tr>
      </thead>
    <tbody id="myTable">
        <?php

        $tableBody = array();
        $kl = array();

        foreach($getZenlineOrdersList as $key => $value)
        {
            foreach ($merge3 as $akkk3 => $akkkv3) 
            {
                if(!empty($merge1[$value['vid']]))
                {
                    foreach($merge1[$value['vid']] as $ko => $ko1)
                    {
                        $q1  = 0;
                        
                        if( $akkk3 == $ko)
                        {
                            foreach($ko1 as $ko2 => $ko22)
                            {
                                $q1 += $ko22;
                            }
                            $merge67[$value['vid']][$akkk3][] = $q1;
                        }
                        else                    
                        {
                            $merge67[$value['vid']][$akkk3][] = '';
                        }
                    }   
                }
            }
        }   
            
        $i = 0;
        $len = count($getZenlineOrdersList);
        foreach($getZenlineOrdersList as $key => $value)
        {
			if(!empty(wc_get_product( $value['vid'] )))
			{
            $_product =  wc_get_product( $value['vid']);
            $image_id           = $_product->get_image_id();
            $gallery_thumbnail  = wc_get_image_size( array(100, 100) );
            $thumbnail_size     = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
            $thumbnail_src      = wp_get_attachment_image_src( $image_id, $thumbnail_size );
            
            $fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
            $fabricCompositionString = $fabricComposition[0]->name; 
            
            $logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
			}
			else
			{	
				$_parent_product =  wc_get_product($_product->get_parent_id());
				if(!in_array($_parent_product->get_sku(), $kl))
				{
					echo "<span class='no-v'>This Style SKU is not for Printing purpose: <strong>". $_parent_product->get_sku() . " (Completely marked with Red Color Row)</strong></span>";
					array_push($kl, $_parent_product->get_sku());
				}
			}
			
            $array_logo = array();
            if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
            if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
            if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
            if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
            
            $logoApplicationString = implode(', ', $array_logo);
            
            if(empty($value['costprice']))
            {
                $classs = 'red';
            }
            else
            {
                $classs = '';
            }

            

            
            $row3 = "<div class='cart-sizes-attribute'>";
            $row3 .= '<div class="size-guide"><h5>Sizes</h5>';
            if(!empty($merge1[$value['vid']]))
            {
				$k = '';
                foreach ($merge1[$value['vid']] as $akkk => $akkkv) {
                    $q  = 0;
                    $row3 .= "<div class='inner-size'><span>" . $akkk  . "</span>";
                    foreach($akkkv as $akkk1 => $akkkv1)
                    {
                        $q += $akkkv1;
                    }
                    $row3 .= "<span class='clr_val'>" . $q . "</span>";
                    $row3 .= "</div>";
                }
            }  
			else
			{
				$k = 'red';
			}
            $row3 .= "</div>";
            $row3 .= "</div>";
            
            echo "<tr class='" . $k . "'>";
                echo "<td class='".$_product->get_sku()."'>" . $value['forderid'] . "</td>";
                echo "<td class='".$_product->get_sku()."'><img src='" . $thumbnail_src[0] . "'/></td>";
                echo "<td class='".$_product->get_sku()."'>" . $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' ) . $row3 . "</td>";
                echo "<td class='".$_product->get_sku()."'>" . $_product->get_sku() . "</td>";

                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['forderid']));
                $imageUrlThumb = str_replace("https://shop2.fexpro.com", "",$thumbnail_src[0]);
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $imageUrlThumb ));
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' )) );
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $_product->get_sku()));

                if(!empty($merge67[$value['vid']]))
                {
                        foreach($merge67[$value['vid']] as $qw => $qr)
                        {
                            $fk = 0;
                            foreach($qr as $vl)
                            {
                                if($vl == '')
                                {
                                    continue;
                                }
                                else
                                {
                                    $fk = $vl;
                                }
                            }
                            if($fk == 0)
                            {
                                echo "<td class='".$_product->get_sku()."' style='display: none;'></td>"; 
                                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => '' ));                      
                            }
                            else
                            {
                                echo "<td class='".$_product->get_sku()."' style='display: none;'>" . $fk . "</td>";
                                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $fk ));                        
                            }
                        }
                }
                else
                {
                     foreach ($merge3 as $akkk3 => $akkkv3) 
                  {
                    echo "<td class='".$_product->get_sku()."' style='display: none;'></td>";
                    array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => '' ));   
                  }
                }

                $newClass = ($value['deliverydate'] == '0000-00-00')?'red':'';
                
                echo "<td class='".$_product->get_sku()."'>" . $fabricCompositionString . "</td>";
                echo "<td class='".$_product->get_sku()."'>" . $logoApplicationString . "</td>";
                echo "<td class='".$_product->get_sku()."'>" . $value['forderunits'] . "</td>";             
                echo "<td class='".$_product->get_sku()."'>" . $value['factoryname'] . "</td>";
                echo "<td class='".$_product->get_sku()." $newClass'>" . $value['deliverydate'] . "</td>";
                echo "<td class='".$_product->get_sku()." $classs'> " . $value['costprice'] . "</td>";               
                echo "<td class='".$_product->get_sku()." $classs'>" . wc_price($value['forderunits'] * $value['costprice']) . " </td>";    

                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $fabricCompositionString ));   
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $logoApplicationString ));   
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['forderunits'] ));   
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['factoryname'] ));   
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['deliverydate'] ));   
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['costprice'] ));
                
                array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => floatval(($value['forderunits'] * $value['costprice']) )) );   


            echo "</tr>";
        }

        delete_transient('getTableBodyData');

                set_transient('getTableBodyData', $tableBody, 21600);


        ?>
    </tbody>
    <tfoot>
    <?php
    foreach($getZenlineOrdersList as $key => $value)
        {
            if ($i == $len - 1) {
                echo "<tr style='background-color: #000; color: #fff;' class='last-tr'>";
                    echo "<td class='total-line'></td>";
                    echo "<td class='total-line'></td>";
                    echo "<td class='total-line'></td>";
                    echo "<td class='total-line'></td>";
                    echo "<td class='total-line'></td>";
                    echo "<td class='total-line'></td>";
                    echo "<td class='total-line'></td>";
                    echo "<td class='total-line'></td>";
                    echo "<td class='total-line'></td>";
                    echo "<td class='total-line'></td>";
                    echo "<td class='total-line'></td>";
                echo "</tr>";
            }
            $i++;
        }
    ?>
    </tfoot>



                </div>
               
                
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
        
    </div>
    


    <script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/node_modules/popper/popper.min.js"></script>
    <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="../assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
    <script src="../assets/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--Custom JavaScript -->
    <script src="dist/js/ecom-dashboard.js"></script>

    <script src="include/js/custom-fexpro.js"></script>

    <script src="jquery_script"></script>
    <script src="dist/tablefilter/tablefilter.js"></script>
	<script src="test-filters-visibility-factory.js"></script>

<script>
const formatToCurrency = amount => {
  return "$" + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
};

$(document).ready(function(){
$( "table thead tr:not(.fltrow) th" ).each(function( index ) {
  if($(this).is(":hidden"))
  {
  console.log( index + ": ");
  jQuery(".fltrow th [ct='"+index+"']").parent().hide();
  }
});

    var d = 0;
    setTimeout(function() { 
    jQuery("table#demo tbody > tr:visible td:last-child .woocommerce-Price-amount bdi").each(function(){
        d += Number($(this).text().replace(/[^0-9.-]+/g,""));
    });
    jQuery(".last-tr td:last-child").text(formatToCurrency(d));
    },500);

$('#demo tr.fltrow input').keypress(function (e) {
    var key = e.which;
    if(key == 13)  
    {
        var d = 0;
        setTimeout(function() {
        
        jQuery("table#demo tbody > tr:visible td:last-child .woocommerce-Price-amount bdi").each(function(){
            d += Number($(this).text().replace(/[^0-9.-]+/g,""));
        });
        jQuery(".last-tr td:last-child").text(formatToCurrency(d));
        
        },500);
    }
    jQuery(".last-tr").css({"display": ""});
});

});

function fnExcelReport()
{
    var SITEURL = "<?php echo site_url(); ?>/wp-content/themes/porto-child/";
    var form_data = new FormData();   
    var myArray = [];
    var myArray1 = [];
    var myArrayImage2 = [];
    var data = {};
    var tab = document.getElementById('myTable');
    var i=0, k=0;
    
    jQuery( 'table#demo thead > tr:nth-child(2) th' ).each(function() {
            myArray.push(jQuery(this).text());      
    });
    console.log(myArray);
    form_data.append('getHeaderArray', JSON.stringify(myArray));
    console.log(tab.rows);
    for(i = 0 ; i < tab.rows.length ; i++) 
    {     
        if(tab.rows[i].getAttribute("style") == 'display: none;')
        {   
            continue;
        }
        else
        {
            for(k = 0 ; k < tab.rows[i].cells.length ; k++) 
            {
                if(tab.rows[i].cells[k].innerHTML.indexOf("uploads") != -1)
                {
                    var abc = tab.rows[i].cells[k].innerHTML.split("https://shop2.fexpro.com");
                    var res = abc[1].replace('">', "");
                    myArray1.push({
                        'Title': tab.rows[i].cells[k].getAttribute("class"), 
                        'data':  res
                    });
                }
                else if(tab.rows[i].cells[k].innerHTML.indexOf("woocommerce-Price-amount amount") != -1)
                {
                    var abc2 = tab.rows[i].cells[k].innerHTML.split("$</span>");
                    var res2 = abc2[1].replace('</bdi></span>', "");
                    myArray1.push({
                        'Title': tab.rows[i].cells[k].getAttribute("class"), 
                        'data':  res2
                    });
                }
                else
                {
                    myArray1.push({
                        'Title': tab.rows[i].cells[k].getAttribute("class"), 
                        'data':  tab.rows[i].cells[k].innerHTML
                    });
                }
            }
        }
    } 

    console.log(myArray1);
    form_data.append('getBodyArray', JSON.stringify(myArray1));
    form_data.append('action', 'export_cart_entries1');
    jQuery.ajax({
        type: "POST",
        url: "https://shop2.fexpro.com/wp-admin/admin-ajax.php",
        contentType: false,
        processData: false,
        data: form_data,
        beforeSend: function() {
            jQuery('#exportexcel').text('Creating XLSX File');
            jQuery('#stop-refresh').show();
        },
        success:function(msg) {
            console.log(msg);   
            jQuery('#exportexcel').text('Data Exported');
            setTimeout(function() {
                jQuery('#exportexcel').text('Export Cart in XLSX');
            },500);
            jQuery('#stop-refresh').hide();
            var data = JSON.parse(msg);
            window.open(SITEURL+"orders/"+data.filename, '_blank');
        },
        error: function(errorThrown){
            console.log(errorThrown);
            console.log('No update');
        }
    });
    
}


            function fnExcelReport1()
            {   

                var SITEURL = "<?php echo site_url(); ?>/wp-content/themes/porto-child/";
                var form_data = new FormData();   
                var myArray = [];
                var myArray1 = [];
                var myArrayImage2 = [];
                var data = {};
                var tab = document.getElementById('myTable');
                var i=0, k=0;
                
                jQuery( 'table#demo thead > tr:nth-child(2) th' ).each(function() {
                        myArray.push(jQuery(this).text());      
                });
                console.log(myArray);
                form_data.append('getHeaderArray', JSON.stringify(myArray));
                form_data.append('exportFrom', 'view_fectory');
                form_data.append('action', 'export_cart_ss22_entries_all_data');
                jQuery.ajax({
                    type: "POST",
                    url: "https://shop2.fexpro.com/wp-admin/admin-ajax.php",
                    contentType: false,
                    processData: false,
                    data: form_data,
                    beforeSend: function() {
                        jQuery('#exportexcel1').text('Creating XLSX File');
                        jQuery('#stop-refresh').show();
                    },
                    success:function(msg) {
                        console.log(msg);   
                        jQuery('#exportexcel1').text('Data Exported');
                        setTimeout(function() {
                            jQuery('#exportexcel1').text('Export All to XLSX');
                        },500);
                        jQuery('#stop-refresh').hide();
                        var data = JSON.parse(msg);
                        window.open(SITEURL+"orders/"+data.filename, '_blank');
                    },
                    error: function(errorThrown){
                        console.log(errorThrown);
                        console.log('No update');
                    }
                });


            }


</script>

    

</body>

</html>
<?php }else{
    header('location: http://shop2.fexpro.com/sagelogin/ecommerce/pages-login.php');
    exit;
} ?>