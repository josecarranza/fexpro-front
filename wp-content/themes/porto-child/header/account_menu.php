<?php global $wp; ?>
<div class="account-menu-container">
	<div class="account-profile-img"></div>
	<div class="account-menu-list">
		<ul>
			<li><label for="">My account</label></li>
			<li>
				<a href="<?=get_site_url()?>/my-account/edit-account/"><span class="ico-menu ico-profile"></span> Profile information</a>
			</li>
			<li>
				<a href="<?=get_site_url()?>/my-account/orders"><span class="ico-menu ico-orders"></span> Orders</a>
			</li>
			<li>
				<a href="<?=get_site_url()?>/my-account/edit-address"><span class="ico-menu ico-address"></span> Address book</a>
			</li>
			<li>
				<a href="<?php echo wp_logout_url( home_url( $wp->request ) ) ?>"><span class="ico-menu ico-logout"></span> Log out</a>
			</li>
			
		</ul>
	</div>
</div>
<script>
	$=jQuery;
	$(".account-menu-container .account-profile-img").click(function(event){
		event.stopPropagation();
		if($(".account-menu-container .account-menu-list").hasClass("open")){
			$(".account-menu-container .account-menu-list").removeClass("open");
		}else{
			$(".account-menu-container .account-menu-list").addClass("open");
		}
		
	});
	$(window).click(function(){
		$(".account-menu-container .account-menu-list").removeClass("open");
	});
</script>