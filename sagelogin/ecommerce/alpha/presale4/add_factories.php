<?php 
require_once 'include/common.php';

get_currentuserinfo();

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
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITEURL; ?>/wp-content/uploads/2021/01/logo.png">
    <title>Fexpro Sage</title>
    <!-- chartist CSS -->
    <link href="../dist/css/pages/ecommerce.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet">

    <link href="../include/css/custom-fexpro.css" rel="stylesheet">

    <script src="https://cdn.anychart.com/releases/8.0.1/js/anychart-base.min.js"></script>


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
    </style>
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
                    <a class="navbar-brand" href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/">
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
                        <h4 class="text-themecolor">Add Factories</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                <li class="breadcrumb-item active">Add Factories</li>
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
                    <div class="container">
    <div class="alert alert-danger" role="alert">
      Atleast factory name is required.
    </div>
<table class="table table-bordered" id="demo">
    <tbody>
        <tr>
            <td style="vertical-align : middle;">Factory code</td>
            <td><input type="text" class='fcode'/></td>
        </tr>
        <tr>
            <td style="vertical-align : middle;">Factory name</td>
            <td><input type="text" class='fname'/></td>
        </tr>
        <?php /* <tr>
            <td style="vertical-align : middle;">Supplier Slug</td>
            <td><input type="text" class='supplier_slug'/></td>
        </tr> */ ?>
        <tr>
            <td style="vertical-align : middle;">Factory address</td>
            <td><input type="text" class='faddress' /></td>
        </tr>
        <tr>
            <td style="vertical-align : middle;">Contact person</td>
            <td><input type="text" class='fperson'/></td>
        </tr>
        <tr>
            <td style="vertical-align : middle;">Phone no.</td>
            <td><input type="text" class='fphone1'/></td>
        </tr>
        <tr>
            <td style="vertical-align : middle;">Phone no. 2</td>
            <td><input type="text" class='fphone2'/></td>
        </tr>
        <tr>
            <td style="vertical-align : middle;">Email address</td>
            <td><input type="email" class='femail'/></td>
        </tr>
    </tbody>
</table>
<button class="submit" value="SUBMIT">SUBMIT</button>
<div class="alert alert-success" role="alert">
  Add Successfully.
</div>


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
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->


    <script src="../../../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../../../assets/node_modules/popper/popper.min.js"></script>
    <script src="../../../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="../dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="../../../assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="../../../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    <script src="../../../assets/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--Custom JavaScript -->
    

    <script src="../include/js/custom-fexpro.js"></script>

    <script>
jQuery(document).ready(function(){
    jQuery(".submit").on('click', function() {
        var form_data = new FormData(); 
        
        var fcode = $(".fcode").val();
        var fname = $(".fname").val();
        var supplier_slug = '';
        var faddress = $(".faddress").val();
        var fperson = $(".fperson").val();
        var fphone1 = $(".fphone1").val();
        var fphone2 = $(".fphone2").val();
        var femail = $(".femail").val();
        
        form_data.append('fcode', fcode);
        form_data.append('fname', fname);
        form_data.append('supplier_slug', supplier_slug);
        form_data.append('faddress', faddress);
        form_data.append('fperson', fperson);
        form_data.append('fphone1', fphone1);
        form_data.append('fphone2', fphone2);
        form_data.append('femail', femail);
        form_data.append('action', 'alpha_custom_presale4_add_factory');
        
        if(fname == '')
        {
            jQuery(".alert-danger").show();
            setTimeout(function() {
                jQuery(".alert-danger").hide();
            },2000);
            jQuery(".fname").addClass('red');
        }
        else
        {
            jQuery(".fname").removeClass('red');
        
        
        
        
        jQuery.ajax({
            type: "POST",
            url: "https://shop.fexpro.com/wp-admin/admin-ajax.php",
            contentType: false,
            processData: false,
            data: form_data,
            success:function(msg) {
                console.log(msg);
                if(msg == 'inserted'){
                    jQuery(".alert-success").show();
                    setTimeout(function() {
                        jQuery(".alert-success").hide();
                    },500);

                    jQuery("input, textarea").val('');
                }
                if(msg == 'Not inserted'){
                    jQuery(".alert-danger").html('Factory name is already exists!.');
                    jQuery(".alert-danger").show();
                    setTimeout(function() {
                        jQuery(".alert-danger").hide();
                    },1000);
                }
                
            },
            error: function(errorThrown){
                console.log(errorThrown);
                console.log('No update');
            }
        });
        }
    });
});
</script>

</body>

</html>
<?php 
} else {
    header('location: https://shop.fexpro.com/sagelogin/ecommerce/alpha/pages-login.php');
    exit;
}
?>