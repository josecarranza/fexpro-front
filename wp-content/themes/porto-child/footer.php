<?php
global $porto_settings, $porto_layout;

$default_layout = porto_meta_default_layout();
$wrapper        = porto_get_wrapper_type();
?>
<?php get_sidebar(); ?>

<?php if (porto_get_meta_value('footer', true)) : ?>

	<?php

	$cols = 0;
	for ($i = 1; $i <= 4; $i++) {
		if (is_active_sidebar('content-bottom-' . $i)) {
			$cols++;
		}
	}

	if (is_404()) {
		$cols = 0;
	}

	if ($cols) :
	?>
		<?php if ('boxed' == $wrapper || 'fullwidth' == $porto_layout || 'left-sidebar' == $porto_layout || 'right-sidebar' == $porto_layout) : ?>
			<div class="container sidebar content-bottom-wrapper">
				<?php
			else :
				if ('fullwidth' == $default_layout || 'left-sidebar' == $default_layout || 'right-sidebar' == $default_layout) :
				?>
					<div class="container sidebar content-bottom-wrapper">
					<?php else : ?>
						<div class="container-fluid sidebar content-bottom-wrapper">
					<?php
				endif;
			endif;
					?>

					<div class="row">

						<?php
						$col_class = array();
						switch ($cols) {
							case 1:
								$col_class[1] = 'col-md-12';
								break;
							case 2:
								$col_class[1] = 'col-md-12';
								$col_class[2] = 'col-md-12';
								break;
							case 3:
								$col_class[1] = 'col-lg-4';
								$col_class[2] = 'col-lg-4';
								$col_class[3] = 'col-lg-4';
								break;
							case 4:
								$col_class[1] = 'col-lg-3';
								$col_class[2] = 'col-lg-3';
								$col_class[3] = 'col-lg-3';
								$col_class[4] = 'col-lg-3';
								break;
						}
						?>
						<?php
						$cols = 1;
						for ($i = 1; $i <= 4; $i++) {
							if (is_active_sidebar('content-bottom-' . $i)) {
						?>
								<div class="<?php echo esc_attr($col_class[$cols++]); ?>">
									<?php dynamic_sidebar('content-bottom-' . $i); ?>
								</div>
						<?php
							}
						}
						?>

					</div>
						</div>
					<?php endif; ?>

					</div><!-- end main -->

					<?php
					do_action('porto_after_main');
					$footer_view = porto_get_meta_value('footer_view');
					?>

					<div class="footer-wrapper<?php echo 'wide' == $porto_settings['footer-wrapper'] ? ' wide' : '', $footer_view ? ' ' . esc_attr($footer_view) : '', isset($porto_settings['footer-reveal']) && $porto_settings['footer-reveal'] ? ' footer-reveal' : ''; ?>">

						<?php if (porto_get_wrapper_type() != 'boxed' && 'boxed' == $porto_settings['footer-wrapper']) : ?>
							<div id="footer-boxed">
							<?php endif; ?>

							<?php if (is_active_sidebar('footer-top') && !$footer_view) : ?>
								<div class="footer-top">
									<div class="container">
										<?php dynamic_sidebar('footer-top'); ?>
									</div>
								</div>
							<?php endif; ?>

							<?php
							get_template_part('footer/footer');
							?>

							<?php if (porto_get_wrapper_type() != 'boxed' && 'boxed' == $porto_settings['footer-wrapper']) : ?>
							</div>
						<?php endif; ?>

					</div>

				<?php else : ?>

			</div><!-- end main -->

		<?php
				do_action('porto_after_main');
			endif;
		?>

		<?php if ('side' == porto_get_header_type()) : ?>
			</div>
		<?php endif; ?>

		</div><!-- end wrapper -->
		<?php do_action('porto_after_wrapper'); ?>

		<?php

		if (isset($porto_settings['mobile-panel-type']) && 'side' === $porto_settings['mobile-panel-type'] && 'overlay' != $porto_settings['menu-type']) {
			// navigation panel
			get_template_part('panel');
		}

		?>


		<!-- <script src="<?php echo esc_url(PORTO_JS); ?>/libs/html5shiv.min.js"></script>
		<script src="<?php echo esc_url(PORTO_JS); ?>/libs/respond.min.js"></script> -->


		<?php wp_footer(); ?>
		<?php
		if (is_user_logged_in()) {
		} else {
			if (!is_wc_endpoint_url('lost-password')) {
				/*echo '<div class="model-popup">';
				echo '<div class="model-inner"><div class="row">';
				// echo '<div class="col-md-6"><div class="from-title"><h2>Register Your account</h2></div><div class="from-inner">' . do_shortcode("[wc_reg_form_bbloomer]") . '</div></div>';
				echo '<div class="col-md-12"><div class="from-title"><h2>Login</h2></div><div class="from-inner">' . do_shortcode("[wc_login_form_bbloomer]") . '</div></div>';
				echo  ' </div> </div>  </div>';*/
			}
		} ?>



		<?php
		// js code (Theme Settings/General)
		if (isset($porto_settings['js-code']) && $porto_settings['js-code']) {
		?>
			<script>
				<?php echo porto_filter_output($porto_settings['js-code']); ?>
			</script>
		<?php } ?>
		<?php if (isset($porto_settings['page-share-pos']) && $porto_settings['page-share-pos']) : ?>
			<div class="page-share position-<?php echo esc_attr($porto_settings['page-share-pos']); ?>">
				<?php get_template_part('share'); ?>
			</div>
		<?php endif; ?>
		<?php
		if (!is_admin() && sizeof(WC()->cart->get_cart()) > 0 && is_cart()) {
			//genrate_xlsx_file(); 
		}
		$user_meta = get_userdata(get_current_user_id());
		
		$user_roles = $user_meta->roles;
		$abc = $user_roles[0];

		unset($_SESSION['abc']);
		$_SESSION['abc'] = $abc;

		$getas = get_option('afpvu_user_role_visibility');
		$ak = maybe_unserialize($getas);


		//customize code for woocommerce widget sidebar color filter 
		if (is_archive() || is_shop()) {
			$filter_color = '';
			/*if (isset($_GET['filter_color'])) {
				$filter_color = $_GET['filter_color'];
			}*/
		}


		?>
		<script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
		<script>
			jQuery(document).ready(function() {

				//Customize code for active current filter
				jQuery('.chosen-color').each(function(index, value) {
					var checkSting = jQuery(this).find('a').text();
					var removeDigitFromString = checkSting.replace(/\d+/g, '');
					if (removeDigitFromString == jQuery(this).find('a').text()) {} else {
						jQuery(this).closest('li').remove();
					}
				});
				jQuery('.chosen-color').click(function(e) {
					e.preventDefault();
					var removeFilter = jQuery(this).find('a').text();
					jQuery('.have_elements').each(function() {
						if (jQuery(this).hasClass('chosen')) {
							var findRemoveValue = jQuery(this).find('a').text();

							if (findRemoveValue.toLowerCase() == removeFilter.toLowerCase()) {
								jQuery(this).trigger('click');
							}
						}
					});
				});
				//END code 

				//Customize code woocommerce sidebar color filter widget
				jQuery('.have_elements').click(function(e) {
					e.preventDefault();
					if (jQuery(this).hasClass('chosen')) {
						var filter_color = '<?php echo $filter_color; ?>';
						var clickAtt = jQuery(this).find('a').attr('data-filterColor');
						var crrentUrl = jQuery(this).find('a').attr('data-crrentUrl');
						if (filter_color == clickAtt) {
							window.location.href = crrentUrl;
						} else {
							var removeComma = filter_color.replace(clickAtt, '');
							removeComma = removeComma.replace(/^,/, '');
							filter_color = removeComma.replace(/,\s*$/, "");
							window.location.href = crrentUrl + '&filter_color=' + filter_color;
						}
					} else {
						var filter_color = '<?php echo $filter_color; ?>';
						if (filter_color != '') {
							var clickAtt = jQuery(this).find('a').attr('data-filterColor');
							var crrentUrl1 = jQuery(this).find('a').attr('data-crrentUrl');
							var fullURL = crrentUrl1 + '&filter_color=' + filter_color + ',' + clickAtt;
							window.location.href = fullURL;
						} else {
							var clickAtt = jQuery(this).find('a').attr('data-filterColor');
							var crrentUrl = jQuery(this).find('a').attr('data-crrentUrl');
							window.location.href = crrentUrl + '&filter_color=' + clickAtt;
						}

					}

				});
				//END code


				/* 
				
				------------- Old  bulk variation image swap js -------------
				
				jQuery(".wc-bulk-variations-table tbody tr td img").on('click', function() {
					var a = jQuery(this).data('gallery_trigger');
					jQuery('.variations_form.cart .filter-item-list a[data-value="'+a+'"]').click();
					// jQuery('div.product-thumbs-slider .img-thumbnail img[src="'+a+'"]').trigger('click');
				}); */

				jQuery("body:not(.archive) .wc-bulk-variations-table tbody tr td img").on('click', function() {
					var a = jQuery(this).data('gallery_trigger');
					jQuery('.variations_form.cart .filter-item-list a[data-value="' + a + '"]').click();
					// jQuery('div.product-thumbs-slider .img-thumbnail img[src="'+a+'"]').trigger('click');
				});

				jQuery("body.archive .wc-bulk-variations-table tbody tr td img").on('click', function() {
					var a = jQuery(this).data('gallery_trigger');
					jQuery(this).closest('.wc-bulk-variations-table-wrapper').parent().find('form.variations_form.cart .filter-item-list a[data-value="' + a + '"]').click();
					//jQuery('.variations_form.cart .filter-item-list a[data-value="'+a+'"]').click();
					// jQuery('div.product-thumbs-slider .img-thumbnail img[src="'+a+'"]').trigger('click');
				});

				/*jQuery(".wc-bulk-variations-table tbody tr.product-row-variation-images > td:first-child img").trigger('click');*/

				jQuery(".single-product form.variations_form.cart .single_variation_wrap .woocommerce-variation-add-to-cart").remove();

				jQuery(document).on('click', ".export_xlsx", function() {
					var SITEURL = "<?php echo site_url(); ?>/wp-content/themes/porto-child/";
					jQuery.ajax({

						type: "POST",
						url: wc_cart_params.ajax_url,
						data: {
							'action': 'export_cart_entries',
							'doing_something': 'doing_something'
						},
						beforeSend: function() {
							jQuery('.export_xlsx').text('Creating XLSX File');
						},
						success: function(msg) {
							console.log(msg);
							//window.location.reload(true);
							jQuery('.export_xlsx').text('Data Exported');
							setTimeout(function() {
								jQuery('.export_xlsx').text('Export Cart in XLSX');
							}, 500);
							var data = JSON.parse(msg);
							window.open(SITEURL + "reports/" + data.filename, '_blank');
						},
						error: function(errorThrown) {
							console.log(errorThrown);
							console.log('No update');
						}
					});
				});

				jQuery('a.woocommerce-button.button.cancel').click(function() {
					if (confirm('Are you sure you want to cancel this order?')) {
						// Save it!

						return true;
					} else {
						// Do nothing!
						return false;
					}
				});

				//my-account order pagination
				jQuery('.woocommerce-MyAccount-content .woocommerce-pagination ul li a').click(function() {
					setTimeout(function() {
						location.reload();
					}, 3000);
				});
				//sessionStorage.setItem("loginData",JSON.stringify(logObj));
				document.cookie = "username= <?php echo $abc ?>";


				jQuery('.sidebar-content').wrapAll("<div class='filter-cstm'></div>");
				jQuery('.filter-cstm').prepend("<a class='filter-btn-cstm'></a>");


				jQuery(".filter-btn-cstm").click(function() {
					jQuery(".sidebar-content").toggleClass("slider-toggle-active");
					jQuery(".filter-btn-cstm").toggleClass("filter-btn-cstm-active");
				});

				jQuery(".hide_price tr#product-row-single-attribute, .hide_price form.wcbvp-cart").remove();

				jQuery("#nav_menu-2 #menu-sidebar-spring-summer-22 .sub-menu").removeClass("open").hide();
				jQuery("#nav_menu-2 #menu-sidebar-spring-summer-22 > .menu-item-has-children > ul.sub-menu").addClass("open").show();
				jQuery("<span class='dropdown'></span>").insertAfter("ul#menu-sidebar-spring-summer-22 li.show-child > a, ul#menu-sidebar-spring-summer-22 li.parent-menu > a");
				jQuery("#menu-sidebar-spring-summer-22 > li > a").on("click", function(e) {
					e.preventDefault();
					jQuery(this).next(".sub-menu").slideToggle();
				});


				jQuery(document).on("click", ".dropdown", function(e) {
					jQuery(this).next(".sub-menu").slideToggle();
					jQuery(this).toggleClass("open");
					jQuery(this).parent().toggleClass("open");
				});


			});
			jQuery('a.custom_search-toggle').on('click', function() {
				jQuery('form.search-form').slideToggle();
			});


			jQuery('#reg_username').hide();
			jQuery('#first_name, #last_name').bind('keypress blur', function() {
				jQuery('#reg_username').val(jQuery('#first_name').val() + ' ' + jQuery('#last_name').val());

			});


			jQuery(window).load(function() {

				jQuery('#nav_menu-2 #menu-sidebar-spring-summer-22 li > a').each(function() {
					var c = jQuery(this).attr('aria-current');
					if (c == 'page') {
						jQuery(this).closest('ul').parent('li').addClass('currentActive');
						jQuery(this).parents("ul").css('display', 'block')

					}

				});

				jQuery('#nav_menu-2 #menu-sidebar-spring-summer-22 ul > li ').each(function() {
					console.log(jQuery(this).text());
				});



			});
		</script>

		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.4/jquery.lazy.min.js"></script>
		<script>
			jQuery(function() {
				jQuery('.wp-post-image').lazy();
			});
		</script>


		<?php if (is_product()) { ?>
			<!-- <script>
				jQuery(document).ready(function() {
					jQuery(".wc-bulk-variations-table thead tr th").each(function() {
						var c = jQuery(this).data('pending-moq');
						var moq = 600;
						var finalPending;
						if (moq > c) {
							finalPending = moq - c;
						} else {
							finalPending = c;
						}

						var d;
						if (c == 0) {
							d = jQuery(this).data('custom-id');
							jQuery("<span class='pendingmoq red'>Pending Moq: 600</span>").insertAfter(".wc-bulk-variations-table tbody tr td .size-guide[data-id='" + d + "']");
							console.log(d);
						} else {
							d = jQuery(this).data('custom-id');
							console.log(d);
							if (c < 239) {
								jQuery("<span class='pendingmoq red'>Pending Moq: " + finalPending + "</span>").insertAfter(".wc-bulk-variations-table tbody tr td .size-guide[data-id='" + d + "']");
							} else if (c >= 239 && c < 600) {
								jQuery("<span class='pendingmoq yellow'>Pending Moq: " + finalPending + "</span>").insertAfter(".wc-bulk-variations-table tbody tr td .size-guide[data-id='" + d + "']");
							} else {}
						}

					});
				});
			</script> -->
		<?php } ?>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
		<script>
			jQuery(document).ready(function() {
				jQuery('.nav-link a[href^="#"]').on('click', function(e) {
					e.preventDefault();
					var target = this.hash;
					var $target = jQuery(target);
					jQuery('html, body').stop().animate({
						'scrollTop': $target.offset().top - 100
					}, 900, 'swing', function() {});
				});

				jQuery('.customer-logos .vc_column-inner').slick({
					slidesToShow: 5,
					slidesToScroll: 1,
					autoplay: true,
					autoplaySpeed: 5000,
					arrows: true,
					dots: false,
					pauseOnHover: false,
					responsive: [{
						breakpoint: 768,
						settings: {
							slidesToShow: 3
						}
					}, {
						breakpoint: 520,
						settings: {
							slidesToShow: 2
						}
					}, {
						breakpoint: 320,
						settings: {
							slidesToShow: 1
						}
					}]
				});
			});
		</script>

		<script type="text/javascript">
			jQuery(document).ready(function() {



				jQuery('.mega-menu-column .mega-sub-menu li.mega-menu-item-has-children .mega-sub-menu li.mega-menu-item-has-children a span').bind('click', function(e) {
					e.preventDefault();

					// jQuery('.mega-menu-column .mega-sub-menu li.mega-menu-item-has-children .mega-sub-menu li.mega-menu-item-has-children').removeClass('active');
					// jQuery('.mega-menu-column').removeClass('active');
					if (jQuery(this).closest("li").hasClass('active')) {
						jQuery(this).closest(".mega-menu-column").removeClass("active");
						jQuery(this).closest("li").removeClass("active");
					} else {
						jQuery('.mega-menu-column .mega-sub-menu li.mega-menu-item-has-children .mega-sub-menu li.mega-menu-item-has-children').removeClass('active');
						jQuery('.mega-menu-column').removeClass('active');
						jQuery(this).closest(".mega-menu-column").addClass("active");
						jQuery(this).closest("li").addClass("active");
						var d = jQuery(this).closest("li.active").height();
						var c = jQuery(this).closest("li.active").prevAll().length;
						d = d * c;
						console.log(d);
						// jQuery(this).parent().next().hide();
						// jQuery(this).parent().next().css("top","-" + d + "px !important");
						// setTimeout(function() { jQuery(this).parent().next().css("top","-" + d + "px !important"); }, 3000);
					}

					// jQuery(".mega-menu-column .mega-sub-menu li.mega-menu-item-has-children .mega-sub-menu li.mega-menu-item-has-children").addClass('active');
				});

				// jQuery(".mega-menu-column .mega-sub-menu li.mega-menu-item-has-children .mega-sub-menu li.mega-menu-item-has-children a span").on('click', function() {
				// 	if (jQuery(this).hasClass('active')) {
				// 		jQuery('.mega-menu-column .mega-sub-menu li.mega-menu-item-has-children .mega-sub-menu li.mega-menu-item-has-children').closest().removeClass('active');
				// 		jQuery(this).closest(".mega-menu-column").removeClass("active");
				// 	} else {
				// 		jQuery(".mega-menu-column .mega-sub-menu li.mega-menu-item-has-children .mega-sub-menu li.mega-menu-item-has-children").closest().addClass('active');
				// 		jQuery(this).closest(".mega-menu-column").addClass("active");
				// 	}
				// });
			});
		</script>

		</body>

		</html>