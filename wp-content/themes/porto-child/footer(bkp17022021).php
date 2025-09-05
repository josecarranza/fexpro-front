<?php
global $porto_settings, $porto_layout;

$default_layout = porto_meta_default_layout();
$wrapper        = porto_get_wrapper_type();
?>
		<?php get_sidebar(); ?>

		<?php if ( porto_get_meta_value( 'footer', true ) ) : ?>

			<?php

			$cols = 0;
			for ( $i = 1; $i <= 4; $i++ ) {
				if ( is_active_sidebar( 'content-bottom-' . $i ) ) {
					$cols++;
				}
			}

			if ( is_404() ) {
				$cols = 0;
			}

			if ( $cols ) :
				?>
				<?php if ( 'boxed' == $wrapper || 'fullwidth' == $porto_layout || 'left-sidebar' == $porto_layout || 'right-sidebar' == $porto_layout ) : ?>
					<div class="container sidebar content-bottom-wrapper">
					<?php
				else :
					if ( 'fullwidth' == $default_layout || 'left-sidebar' == $default_layout || 'right-sidebar' == $default_layout ) :
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
					switch ( $cols ) {
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
						for ( $i = 1; $i <= 4; $i++ ) {
							if ( is_active_sidebar( 'content-bottom-' . $i ) ) {
								?>
								<div class="<?php echo esc_attr( $col_class[ $cols++ ] ); ?>">
									<?php dynamic_sidebar( 'content-bottom-' . $i ); ?>
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
			do_action( 'porto_after_main' );
			$footer_view = porto_get_meta_value( 'footer_view' );
			?>

			<div class="footer-wrapper<?php echo 'wide' == $porto_settings['footer-wrapper'] ? ' wide' : '', $footer_view ? ' ' . esc_attr( $footer_view ) : '', isset( $porto_settings['footer-reveal'] ) && $porto_settings['footer-reveal'] ? ' footer-reveal' : ''; ?>">

				<?php if ( porto_get_wrapper_type() != 'boxed' && 'boxed' == $porto_settings['footer-wrapper'] ) : ?>
				<div id="footer-boxed">
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'footer-top' ) && ! $footer_view ) : ?>
					<div class="footer-top">
						<div class="container">
							<?php dynamic_sidebar( 'footer-top' ); ?>
						</div>
					</div>
				<?php endif; ?>

				<?php
					get_template_part( 'footer/footer' );
				?>

				<?php if ( porto_get_wrapper_type() != 'boxed' && 'boxed' == $porto_settings['footer-wrapper'] ) : ?>
				</div>
				<?php endif; ?>

			</div>

		<?php else : ?>

			</div><!-- end main -->

			<?php
			do_action( 'porto_after_main' );
		endif;
		?>

		<?php if ( 'side' == porto_get_header_type() ) : ?>
			</div>
		<?php endif; ?>

	</div><!-- end wrapper -->
	<?php do_action( 'porto_after_wrapper' ); ?>

<?php

if ( isset( $porto_settings['mobile-panel-type'] ) && 'side' === $porto_settings['mobile-panel-type'] && 'overlay' != $porto_settings['menu-type'] ) {
	// navigation panel
	get_template_part( 'panel' );
}

?>

<!--[if lt IE 9]>
<script src="<?php echo esc_url( PORTO_JS ); ?>/libs/html5shiv.min.js"></script>
<script src="<?php echo esc_url( PORTO_JS ); ?>/libs/respond.min.js"></script>
<![endif]-->

<?php wp_footer(); ?>
<?php 
if ( is_user_logged_in() ) {

}else{
  echo '<div class="model-popup">';
  echo '<div class="model-inner">
  <div class="row">';
  echo '<div class="col-md-6"><div class="from-title"><h2>Register Your account</h2></div><div class="from-inner">'.do_shortcode("[wc_reg_form_bbloomer]").'</div></div>';
  echo '<div class="col-md-6"><div class="from-title"><h2>Login</h2></div><div class="from-inner">'.do_shortcode("[wc_login_form_bbloomer]").'</div></div>';  
  echo  ' </div> </div>  </div>';
} ?>



<?php
// js code (Theme Settings/General)
if ( isset( $porto_settings['js-code'] ) && $porto_settings['js-code'] ) {
	?>
	<script>
		<?php echo porto_filter_output( $porto_settings['js-code'] ); ?>
	</script>
<?php } ?>
<?php if ( isset( $porto_settings['page-share-pos'] ) && $porto_settings['page-share-pos'] ) : ?>
	<div class="page-share position-<?php echo esc_attr( $porto_settings['page-share-pos'] ); ?>">
		<?php get_template_part( 'share' ); ?>
	</div>
<?php endif; ?>
<?php genrate_xlsx_file(); ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
<script>

jQuery(document).ready(function() {
	jQuery(".wc-bulk-variations-table tbody tr td img").on('click', function() {
		var a = jQuery(this).data('gallery_trigger');
		jQuery('.variations_form.cart .filter-item-list a[data-value="'+a+'"]').click();
		// jQuery('div.product-thumbs-slider .img-thumbnail img[src="'+a+'"]').trigger('click');
	});
	jQuery(".single-product form.variations_form.cart .single_variation_wrap .woocommerce-variation-add-to-cart").remove();
	
	jQuery(document).on('click', ".export_xlsx", function() {
		jQuery.ajax({
			type: "POST",
			url: wc_cart_params.ajax_url,
			data: {
			'action': 'export_cart_entries',
			'doing_something' : 'doing_something'},
			beforeSend: function() {
				jQuery('.export_xlsx').text('Creating XLSX File');
			},
			success:function(data) {
				console.log(data);
				//window.location.reload(true);
				jQuery('.export_xlsx').text('Data Exported');
				setTimeout(function() {
					jQuery('.export_xlsx').text('Export Cart in XLSX');
				},500);
			},
			error: function(errorThrown){
				console.log(errorThrown);
				console.log('No update');
			}
		});
	}); 
	
	jQuery('a.woocommerce-button.button.cancel').click(function(){
	   if (confirm('Are you sure you want to cancel this order?')) {
		  // Save it!
		  
			return true;
		} else {
		  // Do nothing!
		 return false;
		}
	});

});
/* if(jQuery('#header .main-menu>li.menu-item').hasClass('active')){
	
var marker = jQuery('.marker'),
    current = jQuery('.active');

// Initialize the marker position and the active class
current.addClass('border-menu');
marker.css({
    // Place the marker in the middle of the border-menu
    top: -(marker.height() / 46),
    left: current.position().left,
    width: current.outerWidth(),
    display: "block"
});

}
if (Modernizr.csstransitions) {
  console.log("using css3 transitions");
jQuery('#header .main-menu>li.menu-item').mouseover(function () {
    var self = jQuery(this),
        offsetLeft = self.position().left,
        // Use the element under the pointer OR the current page item
        width = self.outerWidth() || current.outerWidth(),
        // Ternary operator, because if using OR when offsetLeft is 0, it is considered a falsy value, thus causing a bug for the first element.
        left = offsetLeft == 0 ? 0 : offsetLeft || current.position().left;
  // Play with the border-menu class
    jQuery('.border-menu').removeClass('border-menu');
    self.addClass('border-menu');
    marker.css({
        left: left,
        width: width,
    });
});

// When the mouse leaves the menu
jQuery('#header .main-menu').mouseleave(function () {
  // remove all border-menu classes, add border-menu class to the current page item
    jQuery('.border-menu').removeClass('border-menu');
    current.addClass('border-menu');
  // reset the marker to the current page item position and width
    marker.css({
        left: current.position().left,
        width: current.outerWidth()
    });
});

} else {
console.log("using jquery animate");
  
jQuery('#header .main-menu>li.menu-item').mouseover(function () {
    var self = jQuery(this),
        offsetLeft = self.position().left,
        // Use the element under the pointer OR the current page item
        width = self.outerWidth() || current.outerWidth(),
        // Ternary operator, because if using OR when offsetLeft is 0, it is considered a falsy value, thus causing a bug for the first element.
        left = offsetLeft == 0 ? 0 : offsetLeft || current.position().left;
  // Play with the border-menu class
    jQuery('.border-menu').removeClass('border-menu');
    self.addClass('border-menu');
    marker.stop().animate({
        left: left,
        width: width,
    }, 300);
});

// When the mouse leaves the menu
jQuery('.main-menu').mouseleave(function () {
  // remove all border-menu classes, add border-menu class to the current page item
    jQuery('.border-menu').removeClass('border-menu');
    current.addClass('border-menu');
  // reset the marker to the current page item position and width
    marker.stop().animate({
        left: current.position().left,
        width: current.outerWidth()
    }, 300);
});
}; */
jQuery('a.custom_search-toggle').on('click',function(){
	jQuery('form.search-form').slideToggle();
});


 jQuery('#reg_username').hide();
jQuery('#first_name, #last_name').bind('keypress blur', function() {
        
    jQuery('#reg_username').val(jQuery('#first_name').val() + ' ' +  jQuery('#last_name').val());
   
});

</script>

<?php 
if($_GET['abc']) {
/* foreach($items as $item => $values) { 

            $_product =  wc_get_product( $values['data']->get_id()); 
			//echo $_product->get_sku() . "<br>";
            echo "<b>".$_product->get_title().'</b>  <br> Quantity: '.$values['quantity'].'<br>'; 
            $price = get_post_meta($_product , '_price', true);
            $sku = get_post_meta($_product , '_sku', true);
            echo "  Price: ".$_product->get_price()."<br>";
            echo "  SKU: ".$_product->get_sku()."<br>";
        }  */
		
/* global $woocommerce;
$items = $woocommerce->cart->get_cart(); */
/* global $wpdb;
$uid = get_current_user_id();
$checkdataExist1 = $wpdb->get_results("SELECT `long_data` FROM {$wpdb->prefix}cart_export_data WHERE `uid`= '$uid'");

$ab = maybe_unserialize($checkdataExist1[0]->long_data); */

global $woocommerce;
$items = $woocommerce->cart->get_cart();

//print_r($items);	
	$xlsx_data= array();	
	$xlsx_data1= array();	
      foreach($items as $item => $values) {
		$xlsx_data= array();
		//$data = [];		
		if(!array_key_exists("ProductID", $xlsx_data))
		{
			$data['ProductID'] = 'Product ID';
			$data['ProductName'] = 'Product Name';
			$data['ProductSKU'] = 'Product SKU';
			$data['Unitprice'] = 'Unit Price';
			$data['Boxunits'] = 'Box Units';
		}
		
		/* if(!array_key_exists("SizeTotal", $xlsx_data))
			{
				$data['SizeTotal'] = 'Size Total';
				$data['Subtotal'] = 'Subtotal';
			} */
		
		foreach ($values['variation_size'] as $key => $size) 
			{
				//$xlsx_data= array();
				//$data['Size: ' . $size['label']] = '';				
				
					//$data['Size: ' . $size['label']] = '';
					
						$data['Size:' . $size['label']] = 'Size:' . $size['label'];
						$data2[] = 'Size:' . $size['label'];
					
			} 
			//array_unique($xlsx_data);
			
			array_push($xlsx_data1, $data2);
			array_push($xlsx_data, $data);
			
        }
		//array_merge($xlsx_data1, $xlsx_data);
		//$xlsx_data= array();
		
		foreach($items as $item => $values) { 
		$data1 = [];
		$_product =  wc_get_product( $values['data']->get_id()); 
		$c = 0;
		$d = 0;
			$data1['ProductID'] = $values['data']->get_id();
			if(get_post_meta( $values['data']->get_id(), 'product_team', true ))
			{
				$data1['ProductName'] = $_product->get_title() . " " . get_post_meta( $values['data']->get_id(), 'product_team', true ) . " - " . preg_replace('/[0-9]+/', '', $_product->get_attribute( 'pa_color' ));
			}
			else
			{
				$data1['ProductName'] = $_product->get_title() . " - " . preg_replace('/[0-9]+/', '', $_product->get_attribute( 'pa_color' ));
			}
			$data1['ProductSKU'] = $_product->get_sku();
			$data1['Unitprice'] = $_product->get_price();
			$data1['Boxunits'] = $values['quantity'];
			//print_r($values['variation_size']);
			
			foreach ($values['variation_size'] as $key => $size) 
			{
				$c += $size['value']; 				
				
			} 
			$d  = $c * $values['quantity'];
			$data1['SizeTotal'] = $c * $values['quantity'];
			$data1['Subtotal'] = $c * $values['quantity'] * $_product->get_price();
			
			//$data1['Size: ' . $size['label']] = array();
			foreach ($values['variation_size'] as $key => $size) 
			{
				
				// echo "<pre>";
				// print_r($xlsx_data);
				// echo "</pre>";
				/* foreach($data2 as $kp)
				{
					
					$data1['Size: ' . $size['label']] = '';			
					
				} */			
							
				$data1['Size:' . $size['label']] = $size['value'] * $values['quantity'];
				//array_merge($xlsx_data, $data['Size: ' . $size['label']]);
							
				
			} 
			array_push($xlsx_data, $data1);
        }

echo "<pre>";
print_r($xlsx_data);
echo "</pre>";
foreach($xlsx_data[0] as $keey1 => $q)
{
	$xlsx_data_new= array();
	$noSpace = preg_replace('/\s+/', '', $q);
	$data["$keey1"] = $q;
	
	array_push($xlsx_data_new, $data);
}
	$data1["SizeTotal"] = "Size Total";
	$data1["Subtotal"] = "Subtotal";
	array_push($xlsx_data_new, $data1);
	$xlsx_data_final = array_merge($xlsx_data_new[0], $xlsx_data_new[1]);

foreach($xlsx_data_final as $keey => $q)
{
	$xlsx_data_final_new = array();

	$noSpace = preg_replace('/\s+/', '', $q);	
	//$noSpace = $q;	
	$data4["$keey"] = $q;
	//array_push($xlsx_data_final_new, $data4);	
	//$data5 = [];
	$i=1;
	foreach(array_slice($xlsx_data, 1) as $key => $ka)
	{
		//echo $noSpace . "<br>";
		//echo $ab[$i]["$keey"];
		$data5["$keey"][] = $xlsx_data[$i]["$keey"];
		/* $data5["ProductName"] = $ab[$i]["ProductName"];
		$data5["ProductSKU"] = $ab[$i]["ProductSKU"];
		$data5["Unitprice"] = $ab[$i]["Unitprice"];
		$data5["Boxunits"] = $ab[$i]["Boxunits"];
		$data5["Boxunits"] = $ab[$i]["Boxunits"];
	 */	/* if (strpos($noSpace, 'Size:') !== false) {
			//echo $noSpace . "<br>";
			$data6["$noSpace"][] = $ab[$i]["$noSpace"];
		} */
		
		/* foreach($ka as $new_key => $lpa)
		{

			$noSpace1 = preg_replace('/\s+/', '', $q);	
			if($noSpace1 === $noSpace)
			{
				//echo $lpa . "<br>";
				$data5["$noSpace1"] = $lpa;
			}
			else
			{
				$data5["$noSpace1"] = '';
			}

			
		} */
		//print_r($data4);
		
		$i++;
	}
array_push($xlsx_data_final_new, $data5);
	
	
}
	
/* echo "<pre>";
print_r($data6);
echo "</pre>"; */
		
echo "<pre>";
print_r($xlsx_data_final_new);
echo "</pre>"; 

foreach($xlsx_data_final_new[0] as $key_ap => $akp)
{
	$xlsx_data_final_new9 = array();
	$xlsx_data_final_new10 = array();
	//echo $key_ap . "<br>";
	$data9["$key_ap"] = $key_ap;
	array_push($xlsx_data_final_new10, $data9);	
	
	$i = 0;
	foreach($akp as $key_app => $new)
	{
		$data10[$key_ap] = $akp[0];
		$data11[$key_ap] = $akp[1];
		$data12[$key_ap] = $akp[2];
		if(!empty($akp[3]))
		{	
		$data13[$key_ap] = $akp[3];
		}	

		
		$i++;
//echo $new . "<br>";
	
	
	
	}
array_push($xlsx_data_final_new10,$data10);	
array_push($xlsx_data_final_new10,$data11);	
array_push($xlsx_data_final_new10,$data12);	
if(!empty($data13))
{
array_push($xlsx_data_final_new10,$data13);
}


	
}

echo "<pre>";
print_r($xlsx_data_final_new10);
echo "</pre>";
 
}
?>

</body>
</html>



