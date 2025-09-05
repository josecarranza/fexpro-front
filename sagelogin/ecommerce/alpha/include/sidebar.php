        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User Profile-->
                <div class="user-profile">
                    <div class="user-pro-body">
                        <?php $profileURL = get_avatar_url($_SESSION['userId']); if($profileURL) :?>
                            <div><img src="<?= $profileURL; ?>" alt="user-img" class="img-circle"></div>
                        <?php else : ?>
                            <div><img src="../assets/images/users/2.jpg" alt="user-img" class="img-circle"></div>
                        <?php endif; ?>
                       

                        
                        <div class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle u-dropdown link hide-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo ($_SESSION['userName']) ? $_SESSION['userName'] : ""; ?> <span class="caret"></span></a>
                            <div class="dropdown-menu animated flipInY">
                                <!-- <a href="javascript:void(0)" id="userLogout" class="dropdown-item"><i class="fa fa-power-off" data-userId=></i> Logout</a> -->
                                <a href="<?php echo wp_logout_url( '<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/pages-login.php' ); ?>" id="userLogout" class="dropdown-item"><i class="fa fa-power-off" data-userId=></i> Logout</a>
                                <!-- text-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li>
                            <div class="hide-menu text-center">
                                <div id="eco-spark"></div>
                                <small>TOTAL EARNINGS - JUNE 2020</small>
                                <h4>$2,478.00</h4>
                            </div>
                        </li>
                        <!-- <li style="display:none;"> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">Factories </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/add_factories.php">Add New Factory </a></li>
                                
                             <?php $get_all_factories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}factory_list"); 
                             foreach ($get_all_factories as $value) { ?>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/view_factory.php?name=<?php echo $value->supplier_name ?>"><?php echo $value->supplier_name ?></a></li> 
                             <?php } ?>

                                
                                
                            </ul>
                        </li>
                             -->
						
						
						


                         <li style="display:none;"> <a class="waves-effect waves-dark" href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/ss22_place_order_screen_re_gen_table.php" ><i class="icon-list"></i><span class="hide-menu">SS22 Place Orders </span></a>


                             
                        </li>     
                        <li style="display:none;"> <a class="waves-effect waves-dark" href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/ss22_factory_order_lists_screen.php" ><i class="icon-list"></i><span class="hide-menu">SS22 Factory Order Lists </span></a>
                                
                                </li>                   



                                
                        <li style="display:none;"> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">Under Development List</span></a>
                            <ul aria-expanded="false" class="collapse">

                                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">Spring Summer 22</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?purchased=with-users">User Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?purchased=without-users">Without Users Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=mens-basics">Mens Basics Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=womens-basics">Womens Basics Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=boys-basics">Boys Basics Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=sports-mens-apparel">Sports Mens Apparel Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=sports-womens-apparel">Sports Womens Apparel Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=sports-boys-apparel">Sports Boys Apparel Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=sports-unisex-apparel">Sports Unisex Apparel Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=pop-mens-apparel">POP Mens Apparel Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=pop-womens-apparel">POP Womens Apparel Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=underwear-and-boxers">Underwear & Boxers Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=socks-summer-spring-22">Socks Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=mens-pijamas">Mens Pijamas Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=pijamas-underwear-sleep-womens-summer-spring-22">Womens Pijamas Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=footwear-mens-summer-spring-22">Mens Footwear Purchased list</a></li>
                                    <!-- <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/spring_summer_22.php?cat_purchased=womens-footwear">Womens Footwear Purchased list</a></li> -->
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=footwear-boys-summer-spring-22">Boys Footwear Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=headwear">Headwear Purchased list</a></li>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring_summer_22.php?cat_purchased=nba-headwear">NBA Headwear Purchased list</a></li>
                                </ul>
                            </li> 
                                
                                  
        
                                <li> <a class="waves-effect waves-dark" href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/ss22_factory_users_lists.php" ><i class="icon-list"></i><span class="hide-menu">For Factory Users Links </span></a>
                                    
                                </li>  
                                
                                <li> <a class="waves-effect waves-dark" href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/spring-summer-sku-order-list.php" ><i class="icon-list"></i><span class="hide-menu">SKU Order Lists </span></a>
                                    
                                </li>  
                             </ul>
                             
                        </li>  
                        <li style="display:none;"> <a class="waves-effect waves-dark" href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/presale4/ss22_place_order_screen_re_gen_table.php" ><i class="icon-list"></i><span class="hide-menu"> Presale 4 </span></a></li>

                        <!--<li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">Factories </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li style="display:none;"><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/add_factories.php">Add New Factory </a></li>
                                
                             <?php $get_all_factories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}factory_list"); 
                             foreach ($get_all_factories as $value) { ?>
                                    <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/view_factory.php?name=<?php echo $value->supplier_name ?>"><?php echo $value->supplier_name ?></a></li> 
                             <?php } ?>

                                
                                
                            </ul>
                        </li> -->

                        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">Order Summary</span></a>
                            <ul aria-expanded="false" class="collapse"> 
                                <!-- <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/fall_winter_22_with_user.php?purchased=with-users">With User List</a></li>

                                 <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/fall_winter_22.php?purchased=without-users">Without User List</a></li> -->
                                 <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/season_products.php">Season Products</a></li>

                                 <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/fall_winter_22_with_user_new.php?purchased=with-users">New With User List</a></li>
                                 <li><a href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/fall_winter_22_new.php?purchased=without-users">New Without User List</a></li>

                            </ul>
                        </li>

                        <!-- <li> <a class="waves-effect waves-dark" href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/fw22_place_order_screen_re_gen_table.php" ><i class="icon-list"></i><span class="hide-menu">FW22 Place Orders </span></a>

             
                        </li>     
                         <li> <a class="waves-effect waves-dark" href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/fw22_factory_order_lists_screen.php" ><i class="icon-list"></i><span class="hide-menu">FW22 Factory Order Lists </span></a>
                                
                                </li>     


                                 <li> <a class="waves-effect waves-dark" href="<?php echo SITEURL; ?>/sagelogin/ecommerce/alpha/fw22_factory_users_lists.php" ><i class="icon-list"></i><span class="hide-menu">For Factory Users Links </span></a>
                                    
                                </li> 
                             -->
                             

                    </ul>
                </nav> 
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
