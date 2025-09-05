<?php 
require_once 'include/common.php';

if(isset($_POST['submitLogin'])){

    $creds = array();
    $creds['user_login'] = $_POST['username'];
    $creds['user_password'] = $_POST['password'];
    if($_POST['rememberMe']){
        $creds['remember'] = true;
    }else{
         $creds['remember'] = false;
    }
    $user = wp_signon( $creds, false );
    if ( is_wp_error($user) ) {
       $errorMsg = "The username or password you entered is incorrect";
    } else {
        session_start();
        $_SESSION['userId'] = $user->data->ID;
        $_SESSION['userName'] = $user->data->user_login;
        $_SESSION['userEmail'] = $user->data->user_email;
        echo "<script type'text/javascript'> window.location.href='".SITEURL."/sagelogin/ecommerce'; </script>";  
    }

}

if($_SESSION['userId']){
     echo "<script type'text/javascript'> window.location.href='".SITEURL."/sagelogin/ecommerce'; </script>";
}

?>
<script type="text/javascript"></script>
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
    <title>Fexpro Sage - login</title>

    <!-- page css -->

    <link href="dist/css/pages/login-register-lock.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">

    <link href="include/css/custom-fexpro.css" rel="stylesheet">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!--[if lt IE 9]>

    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>

    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

<![endif]-->

</head>



<body class="skin-default card-no-border">

    <!-- ============================================================== -->

    <!-- Preloader - style you can find in spinners.css -->

    <!-- ============================================================== -->

    <div class="preloader">

        <div class="loader">

            <div class="loader__figure"></div>

            <p class="loader__label">Elite admin</p>

        </div>

    </div>

    <!-- ============================================================== -->

    <!-- Main wrapper - style you can find in pages.scss -->

    <!-- ============================================================== -->

    <section id="wrapper">

        <div class="login-register" style="background-image:url(../assets/images/background/login-register.jpg);">

            <div class="login-box card">

                <div class="card-body">
                    <?php if($errorMsg) : ?>
                        <div class="errorMsg"><?php echo $errorMsg; ?></div>
                    <?php endif; ?>

                    <form class="form-horizontal form-material" id="loginform" action="" method="post">

                        <h3 class="text-center m-b-20">Sign In</h3>

                        <div class="form-group ">

                            <div class="col-xs-12">

                                <input class="form-control" name="username" id="username" type="text" required="" placeholder="Username"> </div>

                        </div>

                        <div class="form-group">

                            <div class="col-xs-12">

                                <input class="form-control" type="password" name="password" id="password" required="" placeholder="Password"> </div>

                        </div>

                        <div class="form-group row">

                            <div class="col-md-12">

                                <div class="d-flex no-block align-items-center">

                                    <div class="custom-control custom-checkbox">

                                        <input type="checkbox" class="custom-control-input" name="rememberMe" id="customCheck1" value="1">

                                        <label class="custom-control-label" for="customCheck1">Remember me</label>

                                    </div> 


                                </div>

                            </div>

                        </div>

                        <div class="form-group text-center">

                            <div class="col-xs-12 p-b-20">

                                <button class="btn btn-block btn-lg btn-info btn-rounded" name="submitLogin" type="submit">Log In</button>

                            </div>

                        </div>

                    <?php /*   <div class="row">

                            <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">

                                <div class="social">

                                    <button class="btn  btn-facebook" data-toggle="tooltip" title="Login with Facebook"> <i aria-hidden="true" class="fab fa-facebook-f"></i> </button>

                                    <button class="btn btn-googleplus" data-toggle="tooltip" title="Login with Google"> <i aria-hidden="true" class="fab fa-google-plus-g"></i> </button>

                                </div>

                            </div>

                        </div>

                        <div class="form-group m-b-0">

                            <div class="col-sm-12 text-center">

                                Don't have an account? <a href="pages-register.html" class="text-info m-l-5"><b>Sign Up</b></a>

                            </div>

                        </div> */ ?>

                    </form>

                    <?php /*<form class="form-horizontal" id="recoverform" action="index.html">

                        <div class="form-group ">

                            <div class="col-xs-12">

                                <h3>Recover Password</h3>

                                <p class="text-muted">Enter your Email and instructions will be sent to you! </p>

                            </div>

                        </div>

                        <div class="form-group ">

                            <div class="col-xs-12">

                                <input class="form-control" type="text" required="" placeholder="Email"> </div>

                        </div>

                        <div class="form-group text-center m-t-20">

                            <div class="col-xs-12">

                                <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>

                            </div>

                        </div>

                    </form> */ ?>

                </div>

            </div>

        </div>

    </section>

    

    <!-- ============================================================== -->

    <!-- End Wrapper -->

    <!-- ============================================================== -->

    <!-- ============================================================== -->

    <!-- All Jquery -->

    <!-- ============================================================== -->

    <script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>

    <!-- Bootstrap tether Core JavaScript -->

    <script src="../assets/node_modules/popper/popper.min.js"></script>

    <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

    <!--Custom JavaScript -->

    <script type="text/javascript">

        $(function() {

            $(".preloader").fadeOut();

        });

        $(function() {

            $('[data-toggle="tooltip"]').tooltip()

        });

        // ============================================================== 

        // Login and Recover Password 

        // ============================================================== 

        $('#to-recover').on("click", function() {

            $("#loginform").slideUp();

            $("#recoverform").fadeIn();

        });

		 localStorage.clear();
    </script>


</body>



</html>