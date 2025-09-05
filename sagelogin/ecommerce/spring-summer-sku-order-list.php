<?php 

require_once 'include/common.php';
get_currentuserinfo();
delete_transient('getTableBodyData');
if(is_user_logged_in()) {


$t = 'Spring Summer 22 --- SKU Order list';
$p = 'SKU Order list';
	
$return_array = array();
$return_array1 = array();
$return_array2 = array();

$orders = wc_get_orders( array(
    'limit'    => -1,
    'status' => array('wc-presale3', 'wc-cancelled'),
	'return' => 'ids',
) );


foreach($orders as $order_id)
{
	
	$order = wc_get_order( $order_id );
	//$order_items  = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
	foreach ( $order->get_items() as $item_id => $item ) {
	$a = array();
	   $product_id = $item->get_product_id();
	   $variation_id = $item->get_variation_id();
		if(!empty($product_id) && !empty($variation_id))
	    {
			if( has_term( $_GET['summer-spring-22'] , 'product_cat' ,  $product_id) )
			{
				$k = get_post_meta($variation_id, '_sku', true);
				$final_result1[$k . "removeText" . $order->get_status()][] = "<a href='https://shop2.fexpro.com/wp-admin/post.php?post=$order_id&action=edit'>$order_id</a>";
                $return_array2[$k . "removeText" . $order->get_status()][] = $order_id;
              
			}
		}
	}
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
    <link rel="icon" type="image/png" sizes="16x16" href="https://shop2.fexpro.com/wp-content/uploads/2021/01/logo.png">
    <title>Fexpro Sage</title>
    <!-- chartist CSS -->
    <link href="dist/css/pages/ecommerce.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">

    <link href="include/css/custom-fexpro.css" rel="stylesheet">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<style>
    input, textarea{width: 100%}
    table#demo tr > td:first-child {
        width: 20%;
    }
    [role="alert"]{display: none;}
    .submit{margin-bottom: 15px;}
    .red{border-color: #f00;}
	tbody#myTable td {
    font-weight: 500;
    text-align: center;
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
                    <a class="navbar-brand" href="https://shop2.fexpro.com/sagelogin/ecommerce/">
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="https://shop2.fexpro.com/sagelogin/ecommerce/logo.webp" alt="homepage" class="dark-logo" />
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
                        <h4 class="text-themecolor"><?php echo $t; ?></h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                <li class="breadcrumb-item active"><?php echo $p; ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Info box Content -->
                <!-- ============================================================== -->
                
                <!-- ============================================================== -->
                <!-- charts -->
                <!-- ============================================================== -->
                <div class="row no-flex">               	
		                
				<span id="exportexcel1" onclick="fnExcelReport1();">Export All to XLSX</span>
				<span id="stop-refresh">Exporting is inprogress. Please don't refresh the page.</span>

				<div class="exporting-it">
				<table class="table table-bordered" id="demo">
					

				   <thead>
						<tr>
						  <th  style="vertical-align : middle;text-align:center;">Style sku</th>
						  <th  style="vertical-align : middle;text-align:center;">Presale3 Status Orders</th>
						  <th  style="vertical-align : middle;text-align:center;">Presale3 Order ID's</th>
						</tr>
					  </thead>
					<tbody id="myTable">
						<?php 
                            $tableBody = array();
							$i = 0;
							$len = count($merge);

							$tableBody = array();
							foreach($final_result1 as $key => $value)
							{
								$a = explode("removeText",$key);
								echo "<tr>";
								echo "<td class='".$a[0]."'>" . $a[0] . "</td>";
								echo "<td class='".$a[0]."'>" . $a[1] . "</td>";
								echo "<td class='".$a[0]."'>" . implode(", ", $value) . "</td>";

                                $content = implode(", ", $return_array2[$a[0].'removeText'.$a[1]] );
								array_push($tableBody,  (object) array('Title' => $a[0] . 'kairav' . $i, 'data' => $a[0]));
								array_push($tableBody,  (object) array('Title' => $a[0] . 'kairav' . $i, 'data' => $a[1] ));
								array_push($tableBody,  (object) array('Title' => $a[0] . 'kairav' . $i, 'data' => $content ));
                                echo "</tr>";	
								$i++;
							}
						?>
                         

					</tbody>
				</table>
				</div>
				<?php 
                
                
				 delete_transient('getTableBodyData');

				set_transient('getTableBodyData', $tableBody, 21600); 

				?>
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
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer">
            © 2021 Fexpro Sage
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
	<script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/node_modules/popper/popper.min.js"></script>
    <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
	<!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
	
	<!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
    <script src="../assets/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--Custom JavaScript -->
    

    <script src="include/js/custom-fexpro.js"></script>
	<script src="dist/tablefilter/tablefilter.js"></script>
	<script src="sku-order-filters-visibility-factory.js"></script>
<script>
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
	form_data.append('getHeaderArray', JSON.stringify(myArray));
	form_data.append('action', 'export_cart_entries_all_data');

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
<?php 
} else {
    header('location: https://shop2.fexpro.com/sagelogin/ecommerce/pages-login.php');
    exit;
}
?>