<?php
global $porto_settings, $porto_layout,$current_user;
?>
<?php
    if ( is_user_logged_in() ) {
    	$second_website_url = 'https://zafari.fexpro.com'; // put your second website url
    	$user_email = $current_user->user_email;
    	$user_login = $current_user->user_login;
    	if($user_email != ''){
    	    $email_encoded = rtrim(strtr(base64_encode($user_email), '+/', '-_'), '='); //email encryption
    	    $user_login_encoded = rtrim(strtr(base64_encode($user_login), '+/', '-_'), '='); //username encryption
    	   $stock_url = $second_website_url.'/shop_login.php?key='.$email_encoded.'&detail='.$user_login_encoded;
        }
    }
?>
	<header id="header" class="header-8<?php echo ! $porto_settings['logo-overlay'] || ! $porto_settings['logo-overlay']['url'] ? '' : ' logo-overlay-header'; ?> header-fifa">
	<?php if ( $porto_settings['show-header-top'] && false ) : ?>
		<div class="header-top">
			<div class="container">
				<div class="header-left">
					<div class="welcome-msg">
						
						<div class="right-logo top-log">
							<a href="<?php echo $stock_url; ?>" target="_blank"><img src="<?php echo get_site_url(); ?>/wp-content/themes/porto-child/assets/images/zafari_logo-03-1.png"></a>
						</div>
						<label for="">Our new shop Zafari is available <a href="">Go now ></a></label>
					</div>

					<?php
					// show welcome message
					// if ( $porto_settings['welcome-msg'] ) {
					// 	echo '<span class="welcome-msg">' . do_shortcode( $porto_settings['welcome-msg'] ) . '</span>';
					// }
					?>
				</div>
				<div class="header-right">
					
					<?php
					// show social links
					echo porto_header_socials();
					?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="header-main">
		<div class="container">
			<div class="header-left">
				<?php
				// show logo
				echo porto_logo();
				?>
			</div>
			<div class="header-center">
				<div id="main-menu">
					<?php
					// show main menu
					echo do_shortcode('[fexpro_menu id_menu="1" css_class="menu-top"]');
					// if(get_the_ID()==200977){//new home
					// 	echo do_shortcode('[fexpro_menu id_menu="1" css_class="menu-top"]');
					// }else{
					// 	echo porto_main_menu();
					// }
					?>
				</div>
			</div>
			<div class="header-right">
				<div>
					<?php // show mobile toggle ?>
					<a class="mobile-toggle"><i class="fas fa-bars"></i></a>
					<div class="block-nowrap">
						<?php
						// show search form
						//echo porto_search_form();
						echo "<div class='custom-search-sidebar'>";
						echo '<a class="custom_search-toggle"><i class="fas fa-search"></i><span class="search-text">Search</span></a>';
						echo do_shortcode('[smart_search id="1"]');
						echo "</div>";

						// show top navigation
						//echo porto_top_navigation();
						?>
					</div>

					<?php
					// show currency and view switcher
					$currency_switcher = porto_currency_switcher();
					$view_switcher     = porto_view_switcher();

					if ( $currency_switcher || $view_switcher ) {
						echo '<div class="switcher-wrap">';
					}

					echo porto_filter_output( $view_switcher );

					echo porto_filter_output( $currency_switcher );

					if ( $currency_switcher || $view_switcher ) {
						echo '</div>';
					}

					// show contact info and mini cart
					$contact_info = $porto_settings['header-contact-info'];
					if ( $contact_info ) {
						echo '<div class="header-contact">' . do_shortcode( $contact_info ) . '</div>';
					}

					// show mini cart
					echo porto_minicart();
					
					//echo do_shortcode('[yith_wcwl_items_count]');
					
					echo '<div class="panel-notification">
						<div class="panel-notification-ico-container">
							<a href="'.get_site_url().'/lists">
							<span class="ico-notification"></span>
							</a>
						</div>
						<div></div>
					</div>';

					get_template_part( 'header/account_menu' );
					?>
				</div>

				<?php
				//get_template_part( 'header/header_tooltip' );
				?>

			</div>
		</div>
		<?php
			get_template_part( 'header/mobile_menu' );
		?>
	</div>
</header>
