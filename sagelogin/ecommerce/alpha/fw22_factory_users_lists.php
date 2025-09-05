<?php 

require_once 'include/common.php';
get_currentuserinfo();
delete_transient('getTableBodyData');
if(is_user_logged_in()) {


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

    <script>
			window.onload = function () {    

  				jQuery('.user-profile .dropdown a.dropdown-toggle').click(function(e){
                	jQuery('.user-profile .dropdown').find('.dropdown-menu').toggle();
            	});

			}


		</script>

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
                    <a class="navbar-brand" href="https://shop2.fexpro.com/sagelogin/ecommerce/alpha">
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
                        <h4 class="text-themecolor">For Factory Users List</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                <li class="breadcrumb-item active">Factory Users List</li>
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
                <div class="row">
                	<table class="table table-bordered" id="demo_table">
                        <thead>
                                <tr>
                                   <th style="vertical-align : middle;text-align:center;">Factory Name</th>
                                  <th style="vertical-align : middle;text-align:center;">Link</th>
                                  
                                </tr>
                            </thead>
   						<tbody id="myTable">
   							<?php $get_all_factories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}factory_list");  ?>
                            <?php foreach ($get_all_factories as $value) { ?>
                            	<tr>
	   								<td><?php echo $value->supplier_name; ?></td>
	   								<td><a href="<?php echo SITEURL; ?>/alpha_presale5/factory/<?php echo $value->supplier_slug ?>" target="_blank"><?php echo SITEURL .'/alpha_presale5/factory/'.$value->supplier_slug; ?></a></td>
	   							</tr>
                            <?php } ?>

   						</tbody>
                	</table>

                </div>

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
	<script src="<?php echo SAGE_ECOMMERCEURL; ?>/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?php echo SAGE_ECOMMERCEURL; ?>/assets/node_modules/popper/popper.min.js"></script>
    <script src="<?php echo SAGE_ECOMMERCEURL; ?>/assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
	<!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
	
	<!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
    <script src="<?php echo SAGE_ECOMMERCEURL; ?>/assets/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--Custom JavaScript -->
    

    <script src="include/js/custom-fexpro.js"></script>


    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css"> 

    <script src="jquery.dataTables.min.js"></script>

    <script>

            $(function () {
                
                count = 0;
                wordsArray = [
                    "Panama is the only place in the world where you can see the sun rise on the Pacific and set on the Atlantic.", 
                    "The canal generates fully one-third of Panama's entire economy.", 
                    "A man, a plan, a canal; Panama. is a palindrome.", 
                    "Panama was the first Latin American country to adopt the U.S. currency as its own.",
                    "The cargo ship Ancon was the first vessel to transit the Canal on August 15, 1914.",
                    "The lowest toll paid was $0.36 and was paid by Richard Halliburton who crossed the Canal swimming in 1928.",
                    "The City of Panama boasts the oldest, longest, continuosly running Municipal Council in the american continent.",
                    "Seven out of ten Panamanians haven't heard of the song \"Panama\" by Van Halen.  Ha!",
                    "Senator John McCain was born in Panama, in the Canal Zone that was, at the time considered U.S. Territory.",
                    "The Panama Hat is really made in Ecuador.",
                    "In Panama, you can swim in the Atlantic Ocean and the Pacific Ocean in the same day.",
                    "The oldest continually operating railroad is in Panama. It travels from Panama City to Colon and back.",
                    "Panama City is the only capital city that has a rain forest within the city limits.",
                ];
                $(".loader__label").html(wordsArray[0]);
                setInterval(function () {
                    count++;
                    $(".loader__label").fadeOut(1000, function () {
                        $(this).text(wordsArray[count % wordsArray.length]).fadeIn(1000);
                    });
                }, 4000);
            });

            $ = jQuery;
                $(document).ready(function() {
                    var demo_table = $('#demo_table').dataTable();
                });
        </script>


	</body>

</body>
</html>	
<?php 
} else {
    header('location: https://shop2.fexpro.com/sagelogin/ecommerce/alpha/pages-login.php');
    exit;
}
?>