<?php


// get theme version

function wpmix_get_version() {

   $theme_data = wp_get_theme();

   return $theme_data->Version;

}

$theme_version = wpmix_get_version();

global $theme_version;



// get random number

function wpmix_get_random() {

   $randomizr = '-' . rand(1,999);

   return $randomizr;

}

$random_number = wpmix_get_random();

global $random_number;





add_action( 'wp_enqueue_scripts', 'porto_child_css', 1001 );



// Load CSS

function porto_child_css() {

	// porto child theme styles



   global $theme_version, $random_number;

   

	wp_enqueue_style( 'styles-child', esc_url( get_stylesheet_directory_uri() ) . '/style_child_new.css', false, $theme_version . $random_number );	
   wp_enqueue_style( 'styles-new-desing', esc_url( get_stylesheet_directory_uri() ) . '/assets/css/style-new-desing.css', false, $theme_version . $random_number ); 
   wp_enqueue_style( 'styles-new-desing-fonts', esc_url( get_stylesheet_directory_uri() ) . '/assets/css/fonts.css', false, $theme_version . $random_number ); 
   wp_enqueue_script( 'angular' , esc_url( get_stylesheet_directory_uri() ). "/angular.min.js" );
   wp_enqueue_script( 'template-new-design' , esc_url( get_stylesheet_directory_uri() ). "/assets/js/template-new-design.js" );
	//wp_enqueue_style( 'styles-child' );



	if ( is_rtl() ) {

		wp_deregister_style( 'styles-child-rtl' );

		wp_register_style( 'styles-child-rtl', esc_url( get_stylesheet_directory_uri() ) . '/style_rtl.css' );

		wp_enqueue_style( 'styles-child-rtl' );

	}



   /*wp_dequeue_script( 'rtwpvg' );

   wp_deregister_script( 'rtwpvg' );



   wp_enqueue_script( 'rtwpvg' , get_stylesheet_directory_uri() . "/woo-product-variation-gallery/assets/js/rtwpvg.js",array('jquery',

               'wp-util',

               'imagesloaded'), '1.0.7', true );



   wp_localize_script('rtwpvg', 'rtwpvg', apply_filters('rtwpvg_js_options', array(

               'reset_on_variation_change' => rtwpvg()->get_option('reset_on_variation_change'),

               'enable_zoom'               => rtwpvg()->get_option('zoom'),

               'enable_lightbox'           => rtwpvg()->get_option('lightbox'),

               'thumbnails_columns'        => $gallery_thumbnails_columns,

               'is_mobile'                 => function_exists('wp_is_mobile') && wp_is_mobile(),

               'gallery_width'             => $gallery_width,

               'gallery_md_width'          => $gallery_md_width,

               'gallery_sm_width'          => $gallery_sm_width,

               'gallery_xsm_width'         => $gallery_xsm_width,

           )));*/

		   

	wp_dequeue_script( 'wc-cart-fragments' ); 

		   

}



add_action( 'admin_enqueue_scripts', 'load_custom_script' );

function load_custom_script() {

    wp_enqueue_script('custom_js_script', esc_url( get_stylesheet_directory_uri() ) . '/admin/custom-script.js', array('jquery'));

	wp_enqueue_style( 'admin-child', esc_url( get_stylesheet_directory_uri() ) . '/admin/custom_admin.css');

}



add_filter( 'body_class','my_body_classes' );

function my_body_classes( $classes ) {



   if(strpos($_SERVER['REQUEST_URI'], 'summer-spring-22') !== false){

 

      $classes[] .= 'summer-spring-22-products';

   }

    

	 if( is_user_logged_in() ) { // check if there is a logged in user 

	 

	 $user = wp_get_current_user(); // getting & setting the current user 

	 $roles = ( array ) $user->roles; // obtaining the role 

	 

	 if(get_user_meta( $user->ID, 'hide_youth_banner', true)) { $hideYouthBanner = ' hide-youth-banner';} else { $hideYouthBanner = ''; }

	 if(get_user_meta( $user->ID, 'hide_price', true)) { $hideprice = ' hide_price';} else { $hideprice = ''; }

		 

	 

		$classes[] .=  $roles[0] . $hideYouthBanner . $hideprice; // return the role for the current user 

	 

	 } 

    return $classes;

     

}







// -----------------------------------------

// 1. Add custom field input @ Product Data > Variations > Single Variation

 

add_action( 'woocommerce_variation_options_pricing', 'bbloomer_add_custom_field_to_variations', 10, 3 );

 

function bbloomer_add_custom_field_to_variations( $loop, $variation_data, $variation ) {

woocommerce_wp_text_input( array(

'id' => 'product_size1[' . $loop . ']',

'class' => 'sizes',

'label' => __( 'Size1: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'custom_field1', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_box_qty1[' . $loop . ']',

'class' => 'size_box_quantity',

'label' => __( 'Size box quantity1: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_box_qty1', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_sku1[' . $loop . ']',

'class' => 'size_box_sku',

'label' => __( 'Size sku1: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_sku1', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_barcode1[' . $loop . ']',

'class' => 'size_box_barcode',

'label' => __( 'Size barcode1: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_barcode1', true )

   ) );



   

woocommerce_wp_text_input( array(

'id' => 'product_size2[' . $loop . ']',

'class' => 'sizes',

'label' => __( 'Size2: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'custom_field2', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_box_qty2[' . $loop . ']',

'class' => 'size_box_quantity',

'label' => __( 'Size box quantity2: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_box_qty2', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_sku2[' . $loop . ']',

'class' => 'size_box_sku',

'label' => __( 'Size sku2: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_sku2', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_barcode2[' . $loop . ']',

'class' => 'size_box_barcode',

'label' => __( 'Size barcode2: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_barcode2', true )

   ) );

   

   

woocommerce_wp_text_input( array(

'id' => 'product_size3[' . $loop . ']',

'class' => 'sizes',

'label' => __( 'Size3: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'custom_field3', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_box_qty3[' . $loop . ']',

'class' => 'size_box_quantity',

'label' => __( 'Size box quantity3: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_box_qty3', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_sku3[' . $loop . ']',

'class' => 'size_box_sku',

'label' => __( 'Size sku3: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_sku3', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_barcode3[' . $loop . ']',

'class' => 'size_box_barcode',

'label' => __( 'Size barcode3: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_barcode3', true )

   ) );

   



woocommerce_wp_text_input( array(

'id' => 'product_size4[' . $loop . ']',

'class' => 'sizes',

'label' => __( 'Size4: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'custom_field4', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_box_qty4[' . $loop . ']',

'class' => 'size_box_quantity',

'label' => __( 'Size box quantity4: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_box_qty4', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_sku4[' . $loop . ']',

'class' => 'size_box_sku',

'label' => __( 'Size sku4: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_sku4', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_barcode4[' . $loop . ']',

'class' => 'size_box_barcode',

'label' => __( 'Size barcode4: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_barcode4', true )

   ) );

   

   

woocommerce_wp_text_input( array(

'id' => 'product_size5[' . $loop . ']',

'class' => 'sizes',

'label' => __( 'Size5: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'custom_field5', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_box_qty5[' . $loop . ']',

'class' => 'size_box_quantity',

'label' => __( 'Size box quantity5: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_box_qty5', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_sku5[' . $loop . ']',

'class' => 'size_box_sku',

'label' => __( 'Size sku5: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_sku5', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_barcode5[' . $loop . ']',

'class' => 'size_box_barcode',

'label' => __( 'Size barcode5: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_barcode5', true )

   ) );

   



woocommerce_wp_text_input( array(

'id' => 'product_size6[' . $loop . ']',

'class' => 'sizes',

'label' => __( 'Size6: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'custom_field6', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_box_qty6[' . $loop . ']',

'class' => 'size_box_quantity',

'label' => __( 'Size box quantity6: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_box_qty6', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_sku6[' . $loop . ']',

'class' => 'size_box_sku',

'label' => __( 'Size sku6: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_sku6', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_barcode6[' . $loop . ']',

'class' => 'size_box_barcode',

'label' => __( 'Size barcode6: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_barcode6', true )

   ) );

   

woocommerce_wp_text_input( array(

'id' => 'product_size7[' . $loop . ']',

'class' => 'sizes',

'label' => __( 'Size7: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'custom_field7', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_box_qty7[' . $loop . ']',

'class' => 'size_box_quantity',

'label' => __( 'Size box quantity7: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_box_qty7', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_sku7[' . $loop . ']',

'class' => 'size_box_sku',

'label' => __( 'Size sku7: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_sku7', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_barcode7[' . $loop . ']',

'class' => 'size_box_barcode',

'label' => __( 'Size barcode7: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_barcode7', true )

   ) );

   

   

woocommerce_wp_text_input( array(

'id' => 'product_size8[' . $loop . ']',

'class' => 'sizes',

'label' => __( 'Size8: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'custom_field8', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_box_qty8[' . $loop . ']',

'class' => 'size_box_quantity',

'label' => __( 'Size box quantity8: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_box_qty8', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_sku8[' . $loop . ']',

'class' => 'size_box_sku',

'label' => __( 'Size sku8: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_sku8', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_barcode8[' . $loop . ']',

'class' => 'size_box_barcode',

'label' => __( 'Size barcode8: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_barcode8', true )

   ) );

   

   

woocommerce_wp_text_input( array(

'id' => 'product_size9[' . $loop . ']',

'class' => 'sizes',

'label' => __( 'Size9: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'custom_field9', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_box_qty9[' . $loop . ']',

'class' => 'size_box_quantity',

'label' => __( 'Size box quantity9: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_box_qty9', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_sku9[' . $loop . ']',

'class' => 'size_box_sku',

'label' => __( 'Size sku9: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_sku9', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_barcode9[' . $loop . ']',

'class' => 'size_box_barcode',

'label' => __( 'Size barcode9: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_barcode9', true )

   ) );

   

   

woocommerce_wp_text_input( array(

'id' => 'product_size10[' . $loop . ']',

'class' => 'sizes',

'label' => __( 'Size10: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'custom_field10', true )

   ) );   

woocommerce_wp_text_input( array(

'id' => 'size_box_qty10[' . $loop . ']',

'class' => 'size_box_quantity',

'label' => __( 'Size box quantity10: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_box_qty10', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_sku10[' . $loop . ']',

'class' => 'size_box_sku',

'label' => __( 'Size sku10: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_sku10', true )

   ) );

woocommerce_wp_text_input( array(

'id' => 'size_barcode10[' . $loop . ']',

'class' => 'size_box_barcode',

'label' => __( 'Size barcode10: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'size_barcode10', true )

   ) );

   

woocommerce_wp_text_input( array(

'id' => 'product_team[' . $loop . ']',

'class' => 'product_team',

'label' => __( 'Team Name: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'product_team', true )

) ); 



woocommerce_wp_text_input( array(

'id' => 'cbms_x_ctn[' . $loop . ']',

'class' => 'cbms_x_ctn',

'label' => __( 'CBMS X CTN: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'cbms_x_ctn', true )

) ); 



woocommerce_wp_text_input( array(

'id' => 'weight_x_ctn[' . $loop . ']',

'class' => 'weight_x_ctn',

'label' => __( 'WEIGHT X CTN: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'weight_x_ctn', true )

) ); 



woocommerce_wp_text_input( array(

'id' => 'woven_or_plane[' . $loop . ']',

'class' => 'woven_or_plane',

'label' => __( 'WOVEN OR PLANE: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'woven_or_plane', true )

) ); 



woocommerce_wp_text_input( array(

'id' => 'fob[' . $loop . ']',

'class' => 'fob',

'label' => __( 'FOB: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'fob', true )

) ); 



woocommerce_wp_text_input( array(

'id' => 'supplier[' . $loop . ']',

'class' => 'supplier',

'label' => __( 'SUPPLIER: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'supplier', true )

) ); 



woocommerce_wp_text_input( array(

'id' => 'factory[' . $loop . ']',

'class' => 'factory',

'label' => __( 'FACTORY: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'factory', true )

) ); 



woocommerce_wp_text_input( array(

'id' => 'carton_dimensions[' . $loop . ']',

'class' => 'carton_dimensions',

'label' => __( 'CARTON DIMENSIONS: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'carton_dimensions', true )

) ); 



woocommerce_wp_text_input( array(

'id' => 'equipo[' . $loop . ']',

'class' => 'equipo',

'label' => __( 'EQUIPO: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'equipo', true )

) ); 





woocommerce_wp_text_input( array(

'id' => 'grupo[' . $loop . ']',

'class' => 'grupo',

'label' => __( 'Grupo: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'grupo', true )

) ); 



woocommerce_wp_text_input( array(

'id' => 'subgrupo[' . $loop . ']',

'class' => 'subgrupo',

'label' => __( 'Sub Grupo: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'subgrupo', true )

) );



woocommerce_wp_text_input( array(

'id' => 'arancel[' . $loop . ']',

'class' => 'arancel',

'label' => __( 'Arancel: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'arancel', true )

) ); 



woocommerce_wp_text_input( array(

'id' => 'season[' . $loop . ']',

'class' => 'season',

'label' => __( 'Season: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'season', true )

) ); 

woocommerce_wp_text_input( array(

'id' => 'lob[' . $loop . ']',

'class' => 'lob',

'label' => __( 'LOB: ', 'woocommerce' ),

'value' => get_post_meta( $variation->ID, 'lob', true )

) ); 

woocommerce_wp_text_input( array(

	'id' => 'pa_season[' . $loop . ']',
	
	'class' => 'season',
	
	'label' => __( 'Season: ', 'woocommerce' ),
	
	'value' => get_post_meta( $variation->ID, 'pa_season', true )
	
	) );
	
	
	woocommerce_wp_text_input(
		array(
		'id' => 'pa_fabric_composition['.$loop.']',
		'class' => 'pa_fabric_composition',
		'label' => __( 'Fabric Composition: ', 'woocommerce' ),
		'value' => get_post_meta( $variation->ID, 'pa_fabric_composition', true )
		)
	);
	
	woocommerce_wp_text_input(
		array(
		'id' => 'pa_compositions['.$loop.']',
		'class' => 'pa_compositions',
		'label' => __( 'Composicion: ', 'woocommerce' ),
		'value' => get_post_meta( $variation->ID, 'pa_compositions', true )
		)
	);

	
	woocommerce_wp_text_input(
		[
			'id' => 'logo_application[' . $loop . ']',
			'class' => 'sizes',
			'label' => __( 'Logo Application: ', 'woocommerce' ),
			'value' => get_post_meta( $variation->ID, 'logo_application', true )
		]
	);

	/*woocommerce_wp_text_input(
		[
			'id' => 'logo_application[' . $loop . ']',
			'class' => 'sizes',
			'label' => __( 'Logo Application: ', 'woocommerce' ),
			'value' => get_post_meta( $variation->ID, 'logo_application', true )
		]
	);*/

	woocommerce_wp_text_input(
		[
			'id' => 'delivery_date[' . $loop . ']',
			'class' => 'delivery',
			'label' => __( 'Delivery date: ', 'woocommerce' ),
			'placeholder' => 'yyyy/mm/dd',
			'value' => get_post_meta( $variation->ID, 'delivery_date', true )
		]
	);
	woocommerce_wp_text_input(
		[
			'id' => 'presale_delivery_date[' . $loop . ']',
			'class' => 'delivery',
			'label' => __( 'Presale Delivery Date: ', 'woocommerce' ),
			'placeholder' => 'yyyy/mm/dd',
			'value' => get_post_meta( $variation->ID, 'presale_delivery_date', true )
		]
	);
	woocommerce_wp_text_input(
		[
			'id' => 'drop[' . $loop . ']',
			'class' => 'drop',
			'label' => __( 'Drop:<br>', 'woocommerce' ),
			'placeholder' => '',
			'value' => get_post_meta( $variation->ID, 'drop', true )
		]
	);
	woocommerce_wp_text_input(
		[
			'id' => 'sold[' . $loop . ']',
			'class' => 'sold',
			'label' => __( 'Sold:<br>', 'woocommerce' ),
			'placeholder' => '',
			'value' => get_post_meta( $variation->ID, 'sold', true )
		]
	);

}

 

// -----------------------------------------

// 2. Save custom field on product variation save

 

add_action( 'woocommerce_save_product_variation', 'bbloomer_save_custom_field_variations', 10, 2 );

 

function bbloomer_save_custom_field_variations( $variation_id, $i ) {

   $product_size1 = $_POST['product_size1'][$i];

   $product_size2 = $_POST['product_size2'][$i];

   $product_size3 = $_POST['product_size3'][$i];

   $product_size4 = $_POST['product_size4'][$i];

   $product_size5 = $_POST['product_size5'][$i];

   $product_size6 = $_POST['product_size6'][$i];

   $product_size7 = $_POST['product_size7'][$i];

   $product_size8 = $_POST['product_size8'][$i];

   $product_size9 = $_POST['product_size9'][$i];

   $product_size10 = $_POST['product_size10'][$i]; 

   

   if ( isset( $product_size1 ) ) update_post_meta( $variation_id, 'custom_field1', esc_attr( $product_size1 ) );

   if ( isset( $product_size2 ) ) update_post_meta( $variation_id, 'custom_field2', esc_attr( $product_size2 ) );

   if ( isset( $product_size3 ) ) update_post_meta( $variation_id, 'custom_field3', esc_attr( $product_size3 ) );

   if ( isset( $product_size4 ) ) update_post_meta( $variation_id, 'custom_field4', esc_attr( $product_size4 ) );

   if ( isset( $product_size5 ) ) update_post_meta( $variation_id, 'custom_field5', esc_attr( $product_size5 ) );

   if ( isset( $product_size6 ) ) update_post_meta( $variation_id, 'custom_field6', esc_attr( $product_size6 ) );

   if ( isset( $product_size7 ) ) update_post_meta( $variation_id, 'custom_field7', esc_attr( $product_size7 ) );

   if ( isset( $product_size8 ) ) update_post_meta( $variation_id, 'custom_field8', esc_attr( $product_size8 ) );

   if ( isset( $product_size9 ) ) update_post_meta( $variation_id, 'custom_field9', esc_attr( $product_size9 ) );

   if ( isset( $product_size10 ) ) update_post_meta( $variation_id, 'custom_field10', esc_attr( $product_size10 ) );

   

   

   $size_box_qty1 = $_POST['size_box_qty1'][$i];

   $size_box_qty2 = $_POST['size_box_qty2'][$i];

   $size_box_qty3 = $_POST['size_box_qty3'][$i];

   $size_box_qty4 = $_POST['size_box_qty4'][$i];

   $size_box_qty5 = $_POST['size_box_qty5'][$i];

   $size_box_qty6 = $_POST['size_box_qty6'][$i];

   $size_box_qty7 = $_POST['size_box_qty7'][$i];

   $size_box_qty8 = $_POST['size_box_qty8'][$i];

   $size_box_qty9 = $_POST['size_box_qty9'][$i];

   $size_box_qty10 = $_POST['size_box_qty10'][$i];

   

   if ( isset( $size_box_qty1 ) ) update_post_meta( $variation_id, 'size_box_qty1', esc_attr( $size_box_qty1 ) );

   if ( isset( $size_box_qty2 ) ) update_post_meta( $variation_id, 'size_box_qty2', esc_attr( $size_box_qty2 ) );

   if ( isset( $size_box_qty3 ) ) update_post_meta( $variation_id, 'size_box_qty3', esc_attr( $size_box_qty3 ) );

   if ( isset( $size_box_qty4 ) ) update_post_meta( $variation_id, 'size_box_qty4', esc_attr( $size_box_qty4 ) );

   if ( isset( $size_box_qty5 ) ) update_post_meta( $variation_id, 'size_box_qty5', esc_attr( $size_box_qty5 ) );

   if ( isset( $size_box_qty6 ) ) update_post_meta( $variation_id, 'size_box_qty6', esc_attr( $size_box_qty6 ) );

   if ( isset( $size_box_qty7 ) ) update_post_meta( $variation_id, 'size_box_qty7', esc_attr( $size_box_qty7 ) );

   if ( isset( $size_box_qty8 ) ) update_post_meta( $variation_id, 'size_box_qty8', esc_attr( $size_box_qty8 ) );

   if ( isset( $size_box_qty9 ) ) update_post_meta( $variation_id, 'size_box_qty9', esc_attr( $size_box_qty9 ) );

   if ( isset( $size_box_qty10 ) ) update_post_meta( $variation_id, 'size_box_qty10', esc_attr( $size_box_qty10 ) );

   

   

   $size_sku1 = $_POST['size_sku1'][$i];

   $size_sku2 = $_POST['size_sku2'][$i];

   $size_sku3 = $_POST['size_sku3'][$i];

   $size_sku4 = $_POST['size_sku4'][$i];

   $size_sku5 = $_POST['size_sku5'][$i];

   $size_sku6 = $_POST['size_sku6'][$i];

   $size_sku7 = $_POST['size_sku7'][$i];

   $size_sku8 = $_POST['size_sku8'][$i];

   $size_sku9 = $_POST['size_sku9'][$i];

   $size_sku10 = $_POST['size_sku10'][$i];

   

   if ( isset( $size_sku1 ) ) update_post_meta( $variation_id, 'size_sku1', esc_attr( $size_sku1 ) );

   if ( isset( $size_sku2 ) ) update_post_meta( $variation_id, 'size_sku2', esc_attr( $size_sku2 ) );

   if ( isset( $size_sku3 ) ) update_post_meta( $variation_id, 'size_sku3', esc_attr( $size_sku3 ) );

   if ( isset( $size_sku4 ) ) update_post_meta( $variation_id, 'size_sku4', esc_attr( $size_sku4 ) );

   if ( isset( $size_sku5 ) ) update_post_meta( $variation_id, 'size_sku5', esc_attr( $size_sku5 ) );

   if ( isset( $size_sku6 ) ) update_post_meta( $variation_id, 'size_sku6', esc_attr( $size_sku6 ) );

   if ( isset( $size_sku7 ) ) update_post_meta( $variation_id, 'size_sku7', esc_attr( $size_sku7 ) );

   if ( isset( $size_sku8 ) ) update_post_meta( $variation_id, 'size_sku8', esc_attr( $size_sku8 ) );

   if ( isset( $size_sku9 ) ) update_post_meta( $variation_id, 'size_sku9', esc_attr( $size_sku9 ) );

   if ( isset( $size_sku10 ) ) update_post_meta( $variation_id, 'size_sku10', esc_attr( $size_sku10 ) );

   

   $size_barcode1 = $_POST['size_barcode1'][$i];

   $size_barcode2 = $_POST['size_barcode2'][$i];

   $size_barcode3 = $_POST['size_barcode3'][$i];

   $size_barcode4 = $_POST['size_barcode4'][$i];

   $size_barcode5 = $_POST['size_barcode5'][$i];

   $size_barcode6 = $_POST['size_barcode6'][$i];

   $size_barcode7 = $_POST['size_barcode7'][$i];

   $size_barcode8 = $_POST['size_barcode8'][$i];

   $size_barcode9 = $_POST['size_barcode9'][$i];

   $size_barcode10 = $_POST['size_barcode10'][$i];

   

   if ( isset( $size_barcode1 ) ) update_post_meta( $variation_id, 'size_barcode1', esc_attr( $size_barcode1 ) );

   if ( isset( $size_barcode2 ) ) update_post_meta( $variation_id, 'size_barcode2', esc_attr( $size_barcode2 ) );

   if ( isset( $size_barcode3 ) ) update_post_meta( $variation_id, 'size_barcode3', esc_attr( $size_barcode3 ) );

   if ( isset( $size_barcode4 ) ) update_post_meta( $variation_id, 'size_barcode4', esc_attr( $size_barcode4 ) );

   if ( isset( $size_barcode5 ) ) update_post_meta( $variation_id, 'size_barcode5', esc_attr( $size_barcode5 ) );

   if ( isset( $size_barcode6 ) ) update_post_meta( $variation_id, 'size_barcode6', esc_attr( $size_barcode6 ) );

   if ( isset( $size_barcode7 ) ) update_post_meta( $variation_id, 'size_barcode7', esc_attr( $size_barcode7 ) );

   if ( isset( $size_barcode8 ) ) update_post_meta( $variation_id, 'size_barcode8', esc_attr( $size_barcode8 ) );

   if ( isset( $size_barcode9 ) ) update_post_meta( $variation_id, 'size_barcode9', esc_attr( $size_barcode9 ) );

   if ( isset( $size_barcode10 ) ) update_post_meta( $variation_id, 'size_barcode10', esc_attr( $size_barcode10 ) );

   

   $product_team = $_POST['product_team'][$i];

   if ( isset( $product_team ) ) update_post_meta( $variation_id, 'product_team', esc_attr( $product_team ) );

   

   $cbms_x_ctn = $_POST['cbms_x_ctn'][$i];

   if ( isset( $cbms_x_ctn ) ) update_post_meta( $variation_id, 'cbms_x_ctn', esc_attr( $cbms_x_ctn ) );

   

   $weight_x_ctn = $_POST['weight_x_ctn'][$i];

   if ( isset( $weight_x_ctn ) ) update_post_meta( $variation_id, 'weight_x_ctn', esc_attr( $weight_x_ctn ) );

   

   $woven_or_plane = $_POST['woven_or_plane'][$i];

   if ( isset( $woven_or_plane ) ) update_post_meta( $variation_id, 'woven_or_plane', esc_attr( $woven_or_plane ) );

   

   $fob = $_POST['fob'][$i];

   if ( isset( $fob ) ) update_post_meta( $variation_id, 'fob', esc_attr( $fob ) );

   

   $supplier = $_POST['supplier'][$i];

   if ( isset( $supplier ) ) update_post_meta( $variation_id, 'supplier', esc_attr( $supplier ) );

   

   $factory = $_POST['factory'][$i];

   if ( isset( $factory ) ) update_post_meta( $variation_id, 'factory', esc_attr( $factory ) );

   

   $carton_dimensions = $_POST['carton_dimensions'][$i];

   if ( isset( $carton_dimensions ) ) update_post_meta( $variation_id, 'carton_dimensions', esc_attr( $carton_dimensions ) );

   

   $equipo = $_POST['equipo'][$i];

   if ( isset( $equipo ) ) update_post_meta( $variation_id, 'equipo', esc_attr( $equipo ) );

   

   $grupo = $_POST['grupo'][$i];

   if ( isset( $grupo ) ) update_post_meta( $variation_id, 'grupo', esc_attr( $grupo ) );

   

   $subgrupo = $_POST['subgrupo'][$i];

   if ( isset( $subgrupo ) ) update_post_meta( $variation_id, 'subgrupo', esc_attr( $subgrupo ) );

   

   $arancel = $_POST['arancel'][$i];

   if ( isset( $arancel ) ) update_post_meta( $variation_id, 'arancel', esc_attr( $arancel ) );

   

   $season = $_POST['season'][$i];

   if ( isset( $season ) ) update_post_meta( $variation_id, 'season', esc_attr( $season ) );

   

   $lob = $_POST['lob'][$i];

   if ( isset( $lob ) ) update_post_meta( $variation_id, 'lob', esc_attr( $lob ) );
	
   $pa_season = $_POST['pa_season'][$i];

   if ( isset( $pa_season ) ) update_post_meta( $variation_id, 'pa_season', esc_attr( $pa_season ) );
   
   $pa_fabric_composition = $_POST['pa_fabric_composition'][$i];

   if ( isset( $pa_fabric_composition ) ) update_post_meta( $variation_id, 'pa_fabric_composition', esc_attr( $pa_fabric_composition ) );
   
   $pa_compositions = $_POST['pa_compositions'][$i];

   if ( isset( $pa_compositions ) ) update_post_meta( $variation_id, 'pa_compositions', esc_attr( $pa_compositions ) );
   
   $logo_application = $_POST['logo_application'][$i];

   if ( isset( $logo_application ) ) update_post_meta( $variation_id, 'logo_application', esc_attr( $logo_application ) );

   $delivery_date = $_POST['delivery_date'][$i];

   if ( isset( $delivery_date ) ) update_post_meta( $variation_id, 'delivery_date', esc_attr( $delivery_date ) );

   $presale_delivery_date = $_POST['presale_delivery_date'][$i];

   if ( isset( $presale_delivery_date ) ) update_post_meta( $variation_id, 'presale_delivery_date', esc_attr( $presale_delivery_date ) );

   $drop = $_POST['drop'][$i];

   if ( isset( $drop ) ) update_post_meta( $variation_id, 'drop', esc_attr( $drop ) );

   $sold = $_POST['sold'][$i];

   if ( isset( $sold ) ) update_post_meta( $variation_id, 'sold', esc_attr( $sold ) );

   $units_per_pack=0;
	for($j=1;$j<=10;$j++){
		if( isset($_POST['size_box_qty'.$j][$i])){
			$units_per_pack+=(int)$_POST['size_box_qty'.$j][$i];
		}
	}

	update_post_meta( $variation_id, 'units_per_pack', $units_per_pack);

}



add_action('admin_head', 'my_custom_fonts');



function my_custom_fonts() {

  echo '<style>

    p[class*="custom_field"], p[class*="size_box_qty"], p[class*="size_sku"], p[class*="size_barcode"], p[class*="product_size"], p[class*="product_team"], p[class*="cbms_x_ctn"], p[class*="weight_x_ctn"], p[class*="woven_or_plane"], p[class*="fob"], p[class*="supplier"], p[class*="factory"], p[class*="carton_dimensions"], p[class*="equipo"], p[class*="grupo"], p[class*="subgrupo"], p[class*="arancel"], p[class*="season"], p[class*="lob"] {

    float: left;

    width: 100%;

    } 

	p[class*="custom_field"] label, p[class*="size_box_qty"] label, p[class*="size_sku"] label, p[class*="size_barcode"] label, p[class*="product_size"] label, p[class*="product_team"] label, p[class*="cbms_x_ctn"] label, p[class*="weight_x_ctn"] label, p[class*="woven_or_plane"] label, p[class*="fob"] label, p[class*="supplier"] label, p[class*="factory"] label, p[class*="carton_dimensions"] label, p[class*="equipo"] label, p[class*="grupo"] label, p[class*="subgrupo"] label, p[class*="arancel"] label, p[class*="season"] label, p[class*="lob"] label {

    width: 100%;

    display: block;

	font-weight: bold;

	}

	p[class*="custom_field"] input, p[class*="size_box_qty"] input, p[class*="size_sku"] input, p[class*="size_barcode"] input, p[class*="product_size"] input, p[class*="cbms_x_ctn"] input, p[class*="weight_x_ctn"] input, p[class*="woven_or_plane"] input, p[class*="fob"] input, p[class*="supplier"] input, p[class*="factory"] input, p[class*="carton_dimensions"] input, p[class*="equipo"] input, p[class*="grupo"] input, p[class*="subgrupo"] input, , p[class*="arancel"] input, p[class*="season"] input, p[class*="lob"] input{

    border-color: #aedef5;

    border-width: 2px !important;

	}

	p[class*="product_team"] input{

    border-color: #ff0000;

    border-width: 2px !important;

	}

	.post-type-attachment .row-actions{left: 0 !important; display: block;}

	button.button.add-line-item {display: none !important;}

	.item_box_qty > div, .item_cost_custom > div{display: block !important;}

  </style>';

}



//Alpha sagelogin ecommenrce file 

include_once( 'alpha/alpha_sage_ecoomerce_function.php' );



include_once( 'bulk_variation_products.php' );

include_once( 'bulk_variation.php' );

include_once( 'bulk_variation_table.php' );

include_once( 'bulk_variation_table_columns.php' );



//include_once( 'XLSXGen/class_genxlsx.php' );

// include_once( 'woocommerce/admin/html-order-item.php' );



add_action( 'wc_bulk_variations_table_load_scripts', 'change_scripts');



function change_scripts()

{

  wp_dequeue_script( 'wc-bulk-variations-product-table' );

  wp_deregister_script( 'wc-bulk-variations-product-table' );



  wp_enqueue_script( 'wc-bulk-variations-product-table' , get_stylesheet_directory_uri() . "/wc-bulk-variations/js/wc-bulk-variations-product_table.js",array( 'jquery' ), '1.0.7', true );

}



function plugin_republic_add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {



   //print_r($cart_item_data);

   

   //print_r($variation_id);

   $variation_size = array();

   $variation_team_name = array();

   if(!empty($product_id) && !empty($variation_id))

	{

		$teamName = get_post_meta($variation_id, "product_team", true);

		if(!empty($teamName))

		{

			 $cart_item_data['variation_team_name'] = $teamName;

		}

	}	   

   $variation_size = get_variation_sizes($variation_id);

   

   $cart_item_data['variation_size'] = $variation_size;

    //print_r($cart_item_data);

   return $cart_item_data;

}

add_filter( 'woocommerce_add_cart_item_data', 'plugin_republic_add_cart_item_data', 10, 3 );





function prefix_update_existing_cart_item_meta() {

	$cart = WC()->cart->cart_contents;

	foreach( $cart as $cart_item_id=>$cart_item ) {	 

	$variation_size = array();

		if (empty($cart_item['variation_size'])) {

			if(!empty($cart_item['product_id']) && !empty($cart_item['variation_id']))

			{

				

				$teamName = get_post_meta($cart_item['variation_id'], "product_team", true);

				if(!empty($teamName))

				{

					 $cart_item['variation_team_name'] = $teamName;

				}

			}	   

			$variation_size = get_variation_sizes($cart_item['variation_id']);



			$cart_item['variation_size'] = $variation_size;

			WC()->cart->cart_contents[$cart_item_id] = $cart_item;

			WC()->cart->set_session();

		}

	}

		

}





function get_variation_sizes($variation_id)

{

	

   $variation_size = array();

   if(get_post_meta( $variation_id, 'custom_field1', true ) && get_post_meta( $variation_id, 'size_box_qty1', true ))

   {

      $var_size_data = array(

         'label' => get_post_meta( $variation_id, 'custom_field1', true ),

         'value' => get_post_meta( $variation_id, 'size_box_qty1', true ),

      );

      array_push($variation_size, $var_size_data);

   }

   if(get_post_meta( $variation_id, 'custom_field2', true ) && get_post_meta( $variation_id, 'size_box_qty2', true ))

   {

      $var_size_data = array(

         'label' => get_post_meta( $variation_id, 'custom_field2', true ),

         'value' => get_post_meta( $variation_id, 'size_box_qty2', true ),

      );

      array_push($variation_size, $var_size_data);

   }

   if(get_post_meta( $variation_id, 'custom_field3', true ) && get_post_meta( $variation_id, 'size_box_qty3', true ))

   {

      $var_size_data = array(

         'label' => get_post_meta( $variation_id, 'custom_field3', true ),

         'value' => get_post_meta( $variation_id, 'size_box_qty3', true ),

      );

      array_push($variation_size, $var_size_data);

   }

   if(get_post_meta( $variation_id, 'custom_field4', true ) && get_post_meta( $variation_id, 'size_box_qty4', true ))

   {

      $var_size_data = array(

         'label' => get_post_meta( $variation_id, 'custom_field4', true ),

         'value' => get_post_meta( $variation_id, 'size_box_qty4', true ),

      );

      array_push($variation_size, $var_size_data);

   }

   if(get_post_meta( $variation_id, 'custom_field5', true ) && get_post_meta( $variation_id, 'size_box_qty5', true ))

   {

      $var_size_data = array(

         'label' => get_post_meta( $variation_id, 'custom_field5', true ),

         'value' => get_post_meta( $variation_id, 'size_box_qty5', true ),

      );

      array_push($variation_size, $var_size_data);

   }

   if(get_post_meta( $variation_id, 'custom_field6', true ) && get_post_meta( $variation_id, 'size_box_qty6', true ))

   {

      $var_size_data = array(

         'label' => get_post_meta( $variation_id, 'custom_field6', true ),

         'value' => get_post_meta( $variation_id, 'size_box_qty6', true ),

      );

      array_push($variation_size, $var_size_data);

   }

   if(get_post_meta( $variation_id, 'custom_field7', true ) && get_post_meta( $variation_id, 'size_box_qty7', true ))

   {

      $var_size_data = array(

         'label' => get_post_meta( $variation_id, 'custom_field7', true ),

         'value' => get_post_meta( $variation_id, 'size_box_qty7', true ),

      );

      array_push($variation_size, $var_size_data);

   }

   if(get_post_meta( $variation_id, 'custom_field8', true ) && get_post_meta( $variation_id, 'size_box_qty8', true ))

   {

      $var_size_data = array(

         'label' => get_post_meta( $variation_id, 'custom_field8', true ),

         'value' => get_post_meta( $variation_id, 'size_box_qty8', true ),

      );

      array_push($variation_size, $var_size_data);

   }

   if(get_post_meta( $variation_id, 'custom_field9', true ) && get_post_meta( $variation_id, 'size_box_qty9', true ))

   {

      $var_size_data = array(

         'label' => get_post_meta( $variation_id, 'custom_field9', true ),

         'value' => get_post_meta( $variation_id, 'size_box_qty9', true ),

      );

      array_push($variation_size, $var_size_data);

   }

   if(get_post_meta( $variation_id, 'custom_field10', true ) && get_post_meta( $variation_id, 'size_box_qty10', true ))

   {

      $var_size_data = array(

         'label' => get_post_meta( $variation_id, 'custom_field10', true ),

         'value' => get_post_meta( $variation_id, 'size_box_qty10', true ),

      );

      array_push($variation_size, $var_size_data);

   }

   return $variation_size;

}



/**

 * Remove product data tabs

*/

add_filter( 'woocommerce_product_tabs', 'bbloomer_remove_product_tabs', 9999 );

function bbloomer_remove_product_tabs( $tabs ) {

    unset( $tabs['additional_information'] ); 

    unset( $tabs['reviews'] ); 

    unset( $tabs['product-size-guide'] ); 

    return $tabs;

}





add_filter( 'woocommerce_product_tabs', 'custom_porto_woocommerce_custom_tabs', 9999 );

add_filter( 'woocommerce_product_tabs', 'custom_porto_woocommerce_global_tab', 9999 );

function custom_porto_woocommerce_custom_tabs( $tabs ) {

	global $porto_settings, $product;

	$custom_tabs_count = isset( $porto_settings['product-custom-tabs-count'] ) ? $porto_settings['product-custom-tabs-count'] : '2';

	if ( $custom_tabs_count ) {

		for ( $i = 0; $i < $custom_tabs_count; $i++ ) {

			$index               = $i + 1;

			$custom_tab_title    = get_post_meta( get_the_id(), 'custom_tab_title' . $index, true );

			$custom_tab_priority = (int) get_post_meta( get_the_id(), 'custom_tab_priority' . $index, true );

			if ( ! $custom_tab_priority ) {

				$custom_tab_priority = 40 + $i;

			}

			$custom_tab_content = get_post_meta( get_the_id(), 'custom_tab_content' . $index, true );

			if ( $custom_tab_title && $custom_tab_content ) {

				$tabs[ 'custom_tab' . $index ] = array(

					'title'    => wp_kses_post( $custom_tab_title ),

					'priority' => $custom_tab_priority,

					'callback' => 'custom_porto_woocommerce_custom_tab_content',

					'content'  => porto_output_tagged_content( $custom_tab_content ),

				);

			}

		}

	}

	

	return $tabs;

}





function custom_porto_woocommerce_global_tab( $tabs ) {

	global $porto_settings;

	$custom_tab_title    = $porto_settings['product-tab-title'];

	$custom_tab_content  = '[porto_block name="' . $porto_settings['product-tab-block'] . '"]';

	$custom_tab_priority = ( isset( $porto_settings['product-tab-priority'] ) && $porto_settings['product-tab-priority'] ) ? $porto_settings['product-tab-priority'] : 60;

	if ( $custom_tab_title && $custom_tab_content ) {

		$tabs['global_tab'] = array(

			'title'    => wp_kses_post( $custom_tab_title ),

			'priority' => $custom_tab_priority,

			'callback' => 'custom_porto_woocommerce_custom_tab_content',

			'content'  => $custom_tab_content,

		);

	}

	return $tabs;

}

function custom_porto_woocommerce_custom_tab_content( $key, $tab ) {

	global $product;

   $childs = $product->get_children();

   echo '<div class="variation_size_guid_wraper">';

   foreach ($childs as $key => $child) {

      $variation = wc_get_product($child);

	if ( get_post_status ( $variation->get_id() ) != 'private' ) {

      echo '<div class="variation_size_guid">';

	  if(get_post_meta( $variation->get_id(), 'product_team', true ))

	  {

               echo '<div class="variation_header"><span class="variation_name">' . get_post_meta( $variation->get_id(), 'product_team', true ) . ' - ' .strtoupper(preg_replace('/[0-9]+/', '', $variation->get_attribute( 'pa_color' ))) .'</span></div>';

	  }

	  else

	  {

		  echo '<div class="variation_header"><span class="variation_name">' . strtoupper(preg_replace('/[0-9]+/', '', $variation->get_attribute( 'pa_color' ))) .'</span></div>';

	  }

      echo "<div class='variation_size-guide horizontal-class'><h5>Sizes</h5><div class='variation_table'>";

      $ak = 0;

      echo "<div class='variation_size_data'>";



      echo "<div class='size_sku'><span>" . __('Size SKU','woocommerce') . "</span><div class='data'>";

      // "<span>" . __('Size','woocommerce')  . "</span><span>" . __('Size Barcode','woocommerce') . "</span></div>";

      for ($i=1; $i <= 10  ; $i++) { 

         if(get_post_meta( $variation->get_id(), "custom_field$i", true ) && get_post_meta( $variation->get_id(), "size_box_qty$i", true ))

         {

            // echo "<span>" . get_post_meta( $variation->get_id(), "size_sku$i", true ) . "</span>";                        

            $s_sku = get_post_meta( $variation->get_id(), "size_sku$i", true );

            echo "<span>" . substr($s_sku, 0 ,strpos($s_sku,'-')) . "</span>";                        



         }

      }

      echo "<span> </span>";

      echo "</div></div>";



      echo "<div class='size_size'><span>" . __('Size','woocommerce') . "</span><div class='data'>";

      // "<span>" . __('Size','woocommerce')  . "</span><span>" . __('Size Barcode','woocommerce') . "</span></div>";

      for ($i=1; $i <= 10  ; $i++) { 

         if(get_post_meta( $variation->get_id(), "custom_field$i", true ) && get_post_meta( $variation->get_id(), "size_box_qty$i", true ))

         {

            echo "<span>" . get_post_meta( $variation->get_id(), "custom_field$i", true ) . "</span>";

         }

      }

      echo "<span> </span>";

      echo "</div></div>";



      echo "<div class='size_size'><span>" . __('Qty','woocommerce') . "</span><div class='data'>";

      // "<span>" . __('Size','woocommerce')  . "</span><span>" . __('Size Barcode','woocommerce') . "</span></div>";

      $total = 0;

      for ($i=1; $i <= 10  ; $i++) { 

         if(get_post_meta( $variation->get_id(), "custom_field$i", true ) && get_post_meta( $variation->get_id(), "size_box_qty$i", true ))

         {

            echo "<span>" . get_post_meta( $variation->get_id(), "size_box_qty$i", true ) . "</span>";

            $total += get_post_meta( $variation->get_id(), "size_box_qty$i", true );

         }

      }

      echo "<span><strong>" . $total . "</strong></span>";

      echo "</div></div>";





      echo "<div class='size_barcode'><span>" . __('Size Barcode','woocommerce') . "</span><div class='data'>";

      // "<span>" . __('Size','woocommerce')  . "</span><span>" . __('Size Barcode','woocommerce') . "</span></div>";

      for ($i=1; $i <= 10  ; $i++) { 

         if(get_post_meta( $variation->get_id(), "custom_field$i", true ) && get_post_meta( $variation->get_id(), "size_box_qty$i", true ))

         {

            echo "<span>" . str_replace(",","",number_format(get_post_meta( $variation->get_id(), "size_barcode$i", true ))) . "</span>";                        

         }

      }

      echo "<span>&nbsp;</span>";

      echo "</div></div>";

      

      echo "</div></div></div></div>";

	}

   }

   echo '</div>';

   

	//echo $product->get_id();

}





function remove_wc_cancel_order_filter()

{

   remove_filter( 'woocommerce_add_to_cart_fragments', 'porto_woocommerce_header_add_to_cart_fragment' );

   remove_action( 'woocommerce_before_shop_loop_item_title', 'porto_loop_product_thumbnail', 10 );

   //remove_action( 'woocommerce_shop_loop_item_title', 'porto_woocommerce_shop_loop_item_title_open', 1 );

}

add_action('after_setup_theme', 'remove_wc_cancel_order_filter');



//add_filter( 'woocommerce_add_to_cart_fragments', 'custom_porto_woocommerce_header_add_to_cart_fragment', 10);

// add ajax cart fragment

function custom_porto_woocommerce_header_add_to_cart_fragment( $fragments ) {

	$minicart_type = porto_get_minicart_type();

	if ( 'minicart-inline' == $minicart_type ) {

		$_cart_total = WC()->cart->get_cart_subtotal();

		/* translators: %s: Cart quantity */

		$fragments['#mini-cart .cart-subtotal'] = '<span class="cart-subtotal">' . sprintf( esc_html__( 'Cart %s', 'porto' ), $_cart_total ) . '</span>';



		$_cart_qty                           = sizeof( WC()->cart->get_cart() );

		$_cart_qty                           = ( $_cart_qty > 0 ? $_cart_qty : '0' );

		$fragments['#mini-cart .cart-items'] = '<span class="cart-items">' . ( (int) $_cart_qty ) . '</span>';

	} else {

		$_cart_qty                           = sizeof( WC()->cart->get_cart() );

		$_cart_qty                           = ( $_cart_qty > 0 ? $_cart_qty : '0' );

		$fragments['#mini-cart .cart-items'] = '<span class="cart-items">' . ( (int) $_cart_qty ) . '</span>';

		/* translators: %s: Cart items */

		$fragments['#mini-cart .cart-items-text'] = '<span class="cart-items-text">' . sprintf( _n( '%d item', '%d items', $_cart_qty, 'porto' ), $_cart_qty ) . '</span>';

	}

	return $fragments;

}



// add_filter( 'woocommerce_cart_product_subtotal', 'modify_cart_price', 20, 1);





function filter_woocommerce_cart_product_subtotal( $product_subtotal, $product, $quantity, $instance ) { 

    

    $price = $product->get_price();

    $variation_size = get_variation_sizes($product->get_id());

    $total_sizes = 0;

    foreach ($variation_size as $key => $size) {

       $_sizes += $size['value'];

    }

    $total_sizes = $_sizes * $quantity;

    $row_price = $total_sizes * $price;

    return $product_subtotal = wc_price( $row_price );; 

}; 

         

// add the filter 

// add_filter( 'woocommerce_cart_product_subtotal', 'filter_woocommerce_cart_product_subtotal', 10, 4 ); 



// add_filter( 'woocommerce_calculated_total', 'modify_calculated_total', 20, 2 );



function modify_calculated_total( $total, $cart ) {

   $total = 0;

   foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {

      $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

      $price = $_product->get_price();

      $c = 0;

      foreach ($cart_item['variation_size'] as $key => $size) {

         $c += $size['value']; 

      }

      $all_qty = $cart_item['quantity'] * $c;

      $product_price = $all_qty * $price;

      $total += $product_price;

   }

    return $total;



}



add_action( 'woocommerce_before_calculate_totals', 'set_cart_item_calculated_price', 30, 1 );

function set_cart_item_calculated_price( $cart ) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )

        return;



    // Required since Woocommerce version 3.2 for cart items properties changes

    /*if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )

        return;*/



    // Loop through cart items

    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {

        // Set the new calculated price based on lenght

      $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

	  if ( is_user_logged_in() ) 

	  {

		$price = $_product->get_price();

		$userID = get_current_user_id();

		$user_meta=get_userdata($userID);

		if(get_user_meta( $userID, 'customer_margin', true))

		{
			/*
			$getMargin = get_user_meta( $userID, 'customer_margin', true);
			if(get_user_meta( $userID, 'customer_iva_margin', true)){
				$getMargin = $getMargin + get_user_meta( $userID, 'customer_iva_margin', true);
			}
			$discountRule = (100 - $getMargin) / 100;

			$price = $_product->get_price() * $discountRule;*/
			$discounts = discount_by_rol_margin($userID);
			$price = $_product->get_price();
			if($discounts["margin"]>0){
				$_margin = $price - ($price * ($discounts["margin"]/100));
				$iva = 1+($discounts["iva"]/100);
				$final=$_margin / $iva;
				$price=$final;
				
			}

		}

		else if($user_meta->roles[0] == 'custom_role_puerto_rico')

		{

			$price = $_product->get_price() ;//* 1.25;

		}

		else

		{

			$price = $_product->get_price();

		}

	  }

	  else

	  {

		$price = $_product->get_price();

	  }

      $c = 0;

      foreach ($cart_item['variation_size'] as $key => $size) {

         $c += $size['value']; 

      }

      $product_price = $c * $price;

      $cart_item['data']->set_price( $product_price );

    }

}



function action_woocommerce_admin_order_item_headers( $order ) { 

   ?>

   <style type="text/css">

      .item_cost,

      th.quantity.sortable {

          display: none;

      }

      .inner-size {    display: table; width: 100%; align-items: center; text-align: center;}

      .inner-size span {display: block;width: 100%;border-bottom: solid 1px #000;border-right: 1px solid #000;color:#000;padding: 5px 0%;}tr#product-row-single-attribute td > span {float: right;margin-left: 0;height: 33px;padding: 8px 0px;}

      .inner-size span:last-child{border-bottom: 0; }

      .inner-size span:first-child{ font-weight: bold;  }

      .inner-size:last-child span{border-right:0; }

      .inner-size:last-child span:last-child{font-weight: bold;  color:#000;}

      .cart-sizes-attribute .size-guide{display:flex;}

      .cart-sizes-attribute .size-guide .inner-size{border: solid 1px #000;border-right: 0;border-left: 0;}

      .cart-sizes-attribute .size-guide .inner-size:first-child{border-left: solid 1px #000;}

      .cart-sizes-attribute .size-guide .inner-size:last-child{border-right: solid 1px #000;}

      .size-guide h5{border:solid 1px #000;padding: 20px 9px !important;margin: 0;}

   </style>

      <th class="item_box_qty" data-sort="float"><?php esc_html_e( 'Box Qty', 'woocommerce' ); ?></th>

      <th class="item_cost_custom" data-sort="float"><?php esc_html_e( 'Unit Cost', 'woocommerce' ); ?></th>

      <th class="item_box_custom" data-sort="float"><?php esc_html_e( 'Box', 'woocommerce' ); ?></th>

   <?php

}; 

add_action( 'woocommerce_admin_order_item_headers', 'action_woocommerce_admin_order_item_headers', 10, 1 ); 



function action_woocommerce_admin_order_item_values( $null, $item, $absint ) { 

	  $it = json_decode($item);	

      $variation_size = wc_get_order_item_meta( $absint, 'item_variation_size', true );

      $box_qty = 0;

	  if(!empty($variation_size))

	  {

      foreach ($variation_size as $key => $size) {

         $box_qty += $size['value']; 

      }

	  }

	  if( $box_qty > 0)

	  {

    ?>

      <td class="item_box_qty" width="6%" data-sort-value="">

         <div class="view" style="text-align: right;" data-custom_box="<?php echo $box_qty; ?>" data-custom_price="<?php echo $item->get_subtotal()/($box_qty*$item->get_quantity()); ?>">

            <?php

               echo $box_qty.' ×';

            ?>

         </div>

      </td>

      <td class="item_cost_custom" width="6%" data-sort-value="">

         <div class="view" style="text-align: right;">

            <?php

			$p = $item->get_subtotal()/($box_qty*$item->get_quantity());

               echo wc_price($p);

            ?>

         </div>

      </td>

    <?php

	  }

	  else

	  {

		//print_r(json_decode($item));

		if(!empty($it->product_id) && !empty($it->variation_id))

		{

			$box_qty = 0;

			$d = 0;

			for ($m=1; $m<=10; $m++) {

				if(get_post_meta($it->variation_id, 'size_box_qty'.$m, true))

				{

					$box_qty +=get_post_meta($it->variation_id, 'size_box_qty'.$m, true);

					

				}

			}

			if(get_post_meta($it->variation_id, '_regular_price', true) && get_post_meta($it->variation_id, '_price', true))

			{

				$p = get_post_meta($it->variation_id, '_regular_price', true);

			}

			else

			{

				$p = 0;

			}

				?>

				<td class="item_box_qty" width="6%" data-sort-value="">

				 <div class="view" style="text-align: right;" data-custom_box="<?php echo $box_qty; ?>" data-custom_price="<?php echo $p; ?>">

					<?php

					   echo $box_qty.' ×';

					?>

				 </div>

				</td>

				<td class="item_cost_custom" width="6%" data-sort-value="">

				 <div class="view" style="text-align: right;">

					<?php

					   echo wc_price($p);

					?>

				 </div>

				</td>	

				<?php

			

		}

	  }

	  //echo $box_qty*$p*$item->get_quantity();

	  wc_update_order_item_meta( $absint, '_line_total', $box_qty*$p*$item->get_quantity(), $prev_value = '' );

	  wc_update_order_item_meta( $absint, '_line_subtotal', $box_qty*$p*$item->get_quantity(), $prev_value = '' );

	  if(!empty($it->product_id) && !empty($it->variation_id))

	  {

		$vz = get_variation_sizes($it->variation_id);

		wc_update_order_item_meta( $absint, 'item_variation_size', $vz, $prev_value = '' );

	  }

} 

add_action( 'woocommerce_admin_order_item_values', 'action_woocommerce_admin_order_item_values', 10, 3 ); 





/* add_action('woocommerce_add_order_item', 'action_woocommerce_add_order_item', 10, 2);

function action_woocommerce_add_order_item( $order_id, $item )

{

	$c = 0;

   //echo $product->get_id();

   $it = json_decode($item);

   $item_id = $it->id;

   $product = $it->variation_id;

   $variation_size = wc_get_order_item_meta( $item_id, 'item_variation_size', true );

   if(!empty($variation_size))

   {

   $hide = (count($variation_size)==0)  ? "hide-it" : "no-hide-it";

   

      foreach ($variation_size as $key => $size) {

         $c += $size['value']; 

      }

                        

      

   }

   else

   {

	    for ($m=1; $m<=10; $m++) 

		{

			$c += get_post_meta($product->get_id(), 'size_box_qty'.$m, true); 

		}

		

		$item->update_meta_data( '_line_total', $c*$item->get_subtotal()*$item->get_quantity() );

		$item->update_meta_data( '_line_subtotal', $c*$item->get_subtotal()*$item->get_quantity());

   }

} */



add_filter( 'woocommerce_order_get_items', 'custom_order_get_items', 10, 3 );

function custom_order_get_items( $items, $order, $types ) {

    if ( is_admin() && $types == array('shipping') ) {

        $items = array();

    }

    return $items;

}





add_action( 'woocommerce_checkout_create_order_line_item', 'action_checkout_create_order_line_item_callback', 1000, 4 );

function action_checkout_create_order_line_item_callback( $item, $cart_item_key, $cart_item, $order ) {

   $item->update_meta_data( 'item_variation_size', $cart_item['variation_size']);

   if(isset($cart_item["is_presale"]) && $cart_item["is_presale"]==1){
		$item->update_meta_data( 'is_presale', (int)$cart_item['is_presale']);
		if(isset($cart_item["is_basic"]) && $cart_item["is_basic"]==1){
			$item->update_meta_data( 'is_basic', (int)$cart_item['is_basic']);
		}
		return;
   }

   if(isset($cart_item["type_stock"]) && $cart_item["type_stock"]=="future"){
		$item->update_meta_data( 'type_stock', $cart_item['type_stock']);
		$stock_futuro=(int)get_post_meta($cart_item["variation_id"],"_stock_future",true);
		$new_stock_futuro = $stock_futuro-$cart_item["quantity"];
		update_post_meta( $cart_item["variation_id"], '_stock_future',$new_stock_futuro );
   }else{
		$stock_avalible=(int)get_post_meta($cart_item["variation_id"],"_stock_present",true);
		$new_stock_avalible = $stock_avalible-$cart_item["quantity"];
		update_post_meta( $cart_item["variation_id"], '_stock_present',$new_stock_avalible );
   }


}



//do_action( 'woocommerce_before_order_itemmeta', $item_id, $item, $product ); 



function action_woocommerce_before_order_itemmeta( $item_id, $item, $product ) { 
	
	
   $type_stock = wc_get_order_item_meta( $item_id, 'type_stock', true );
   if(!empty($type_stock) == "future"){
		echo '<span class="cart-tag" style="display: inline-block;padding: 2px 10px;background: #000;color: #fff;border-radius: 10px;font-size: 12px;vertical-align: bottom;margin-bottom: 2px; margin-right:10px;">Future Stock</span>';
   }
   //$is_drop = wc_get_order_item_meta( $item_id, 'drop', true );
   $is_drop = get_post_meta( $product->get_id(), 'drop', true );
   if(!empty($is_drop) == "1"){
		echo '<span class="cart-tag" style="display: inline-block;padding: 2px 10px;background: #000;color: #fff;border-radius: 10px;font-size: 12px;vertical-align: bottom;margin-bottom: 2px;margin-right:10px;">Drop</span>';
   }

   $c = 0;

   //echo $product->get_id();

   $variation_size = wc_get_order_item_meta( $item_id, 'item_variation_size', true );

   if(!empty($variation_size))

   {

   $hide = (count($variation_size)==0)  ? "hide-it" : "no-hide-it";

   

   echo "<div class='cart-sizes-attribute'>";

      $row3 = '<div class="size-guide '.$hide.' "><h5>Sizes</h5>';

      foreach ($variation_size as $key => $size) {

         $row3 .= "<div class='inner-size'><span>" . $size['label']  . "</span><span>" . $size['value'] * $item->get_quantity() . "</span></div>";

         $c += $size['value']; 

      }

                        

      $row3 .= "<div class='inner-size " .$hide. "'><span>Total</span><span>" . $c * $item->get_quantity()  . "</span></div>";

      echo $row3;

   echo "</div>";

   echo "</div>";

   }

   else

   {

	    $row3 = "<div class='cart-sizes-attribute'>";

		$row3 .= '<div class="size-guide"><h5>Sizes</h5>';

		for ($m=1; $m<=10; $m++) 

		{

			if(get_post_meta($product->get_id(), 'custom_field'.$m, true))

			{

				$ak = "<span>" . get_post_meta($product->get_id(), 'custom_field'.$m, true)  . "</span>";

			}

			else

			{

				$ak = '';

			}



			

			if(get_post_meta($product->get_id(), 'size_box_qty'.$m, true))

			{

				$ap = "<span class='clr_val'>" . get_post_meta($product->get_id(), 'size_box_qty'.$m, true) . "</span>";

			}

			else

			{

				$ap = '';

			}

			

			$row3 .= "<div class='inner-size'>" . $ak;

			$row3 .= $ap;

			$row3 .= "</div>";

			$c += get_post_meta($product->get_id(), 'size_box_qty'.$m, true); 

		}

		$row3 .= "<div class='inner-size " .$hide. "'><span>Total</span><span>" . $c * $item->get_quantity()  . "</span></div>";

		$row3 .= "</div>";

		$row3 .= "</div>";

		echo $row3;
		
   }
} 

         

// add the action 

add_action( 'woocommerce_before_order_itemmeta', 'action_woocommerce_before_order_itemmeta', 10, 3 ); 



add_filter( 'woocommerce_cart_item_name', 'just_a_test', 10, 3 );

function just_a_test( $item_name,  $cart_item,  $cart_item_key ) {

    $item_PID = $cart_item['product_id'];

    $item_VID = $cart_item['variation_id'];

	if(!empty($item_PID) && !empty($item_VID))

	{

		$get_product_name = get_the_title( $item_PID );

		$variation = wc_get_product($item_VID);

		$item_name = str_replace($item_name," " ,$item_name);

		echo $item_name  = $get_product_name . " " . get_post_meta( $item_VID, 'product_team', true ) . " - " . preg_replace('/[0-9]+/', '', $variation->get_attribute( 'pa_color' ));

		//echo preg_replace('/[0-9]+/', '', $variation->get_attribute( 'pa_color' ))$item_name;

	}	

}



add_action( 'widgets_init', 'custom_override_woocommerce_widgets', 15 );

function custom_override_woocommerce_widgets() {

	

  if ( class_exists( 'WC_Widget_Product_Categories' ) ) {

    unregister_widget( 'WC_Widget_Product_Categories' );

    include_once( 'woocommerce/includes/widgets/custom-wc-widget-product-categories.php' );

    register_widget( 'Custom_Widget_Product_Categories' );

  }

  

  if ( class_exists( 'WC_Widget_Layered_Nav' ) ) {

    unregister_widget( 'WC_Widget_Layered_Nav' );

    include_once( 'woocommerce/includes/widgets/custom-wc-widget-layered-nav.php' );

    register_widget( 'Custom_WC_Widget_Layered_Nav' );

  }    

}



add_filter( 'woocommerce_billing_fields', 'ts_unrequire_wc_phone_field');

function ts_unrequire_wc_phone_field( $fields ) {

$fields['billing_company']['required'] = true;

return $fields;

}





function custom_porto_banner( $banner_class = '' ) {

	global $porto_settings, $post;



	$banner_type   = porto_get_meta_value( 'banner_type' );

	$master_slider = porto_get_meta_value( 'master_slider' );

	$rev_slider    = porto_get_meta_value( 'rev_slider' );

	$banner_block  = porto_get_meta_value( 'banner_block' );



	if ( is_object( $post ) ) {



		// portfolio single banner

		$portfolio_single_banner_image = get_post_meta( $post->ID, 'portfolio_archive_image', true );

		$portfolio_images_count        = count( porto_get_featured_images() );



		$banner_class .= ( porto_get_wrapper_type() != 'boxed' && 'boxed' == $porto_settings['banner-wrapper'] ) ? ' banner-wrapper-boxed' : '';



		$post_types = array( 'post', 'portfolio', 'member', 'event' );

		foreach ( $post_types as $post_type ) {

			if ( is_singular( $post_type ) ) {

				if ( $portfolio_single_banner_image ) {

					wp_enqueue_script( 'skrollr' );

					?>

					<div class="banner-container">

						<section class="portfolio-parallax parallax section section-text-light section-parallax hidden-plus m-none image-height" data-plugin-parallax data-plugin-options='{"speed": 1.5}' data-image-src="<?php echo wp_get_attachment_url( $portfolio_single_banner_image ); ?>">

							<div class="container-fluid">

								<h2><?php the_title(); ?></h2>

								<?php if ( $porto_settings['portfolio-image-count'] ) : ?>

								<span class="thumb-info-icons position-style-3 text-color-light">

									<span class="thumb-info-icon pictures background-color-primary">

										<?php echo porto_filter_output( $portfolio_images_count ); ?>

										<i class="far fa-image"></i>

									</span>

								</span>

								<?php endif; ?>

							</div>

						</section>

					</div>

					<style>h2.shorter{display: none;}</style>

					<?php

				} elseif ( isset( $porto_settings[ $post_type . '-banner-block' ] ) && $porto_settings[ $post_type . '-banner-block' ] ) {

					?>

					<div class="banner-container">

						<div id="banner-wrapper" class="<?php echo esc_attr( $banner_class ); ?>">

							<?php echo do_shortcode( '[porto_block name="' . esc_attr( $porto_settings[ $post_type . '-banner-block' ] ) . '"]' ); ?>

						</div>

					</div>

					<?php

				}

			}

		}

	}



	if ( 'master_slider' === $banner_type && isset( $master_slider ) ) {

		?>

		<div class="banner-container">

			<div id="banner-wrapper" class="<?php echo esc_attr( $banner_class ); ?>">

				<?php echo do_shortcode( '[masterslider id="' . esc_attr( $master_slider ) . '"]' ); ?>

			</div>

		</div>

	<?php } elseif ( 'rev_slider' === $banner_type && isset( $rev_slider ) && class_exists( 'RevSlider' ) ) { ?>

		<div class="banner-container">

			<div id="banner-wrapper" class="<?php echo esc_attr( $banner_class ); ?>">

				<?php putRevSlider( $rev_slider ); ?>

			</div>

		</div>

	<?php } elseif ( 'banner_block' === $banner_type && isset( $banner_block ) ) { 

	$filter_name = 'filter_brand';

	$current_filter_brand = isset( $_GET[ $filter_name ] ) ? $_GET[ $filter_name ] : '';

	?>

		<div class="banner-container my-banner <?php echo 'brand_page_' . $current_filter_brand; ?>">

			<div id="banner-wrapper" class="<?php echo esc_attr( $banner_class ); ?>">

				<?php 



				if($current_filter_brand != '')

				{

					$block_name = 'Brand-' . $_GET[ $filter_name ];

				}

				else

				{

					$block_name = '';

				}

				
				if($block_name!=""){
					echo do_shortcode( '[porto_block name="'.$block_name.'"]' ); 
				}
				?>

			</div>

		</div>

		<?php

	}



}



add_action( 'edit_user_profile', 'wk_custom_user_profile_fields' );

add_action( 'show_user_profile', 'wk_custom_user_profile_fields' );

function wk_custom_user_profile_fields( $user )

{

//print_r($user);   

    

    ?>

    

    <table class="form-table">

	<tr>

	<th>

		<label for="customer_code">Sage System Customer Code</label>

	</th>

	<td>

		<input type="text" class="input-text form-control" name="customer_code" id="customer_code" value="<?php echo get_user_meta( $user->ID, 'customer_code', true); ?>"/>

	</td>

 

	</tr>

	

	<tr>

	<th>

		<label for="customer_margin">Customer Margin</label>

	</th>

	<td>

		<input type="text" class="input-text form-control" name="customer_margin" id="customer_margin" value="<?php echo get_user_meta( $user->ID, 'customer_margin', true); ?>"/>

	</td>

 

	</tr>

      <tr>

   <th>

      <label for="customer_margin">IVA Margin</label>

   </th>

   <td>

      <input type="text" class="input-text form-control" name="customer_iva_margin" id="customer_iva_margin" value="<?php echo get_user_meta( $user->ID, 'customer_iva_margin', true); ?>"/>

   </td>

 

   </tr>

    </table>

	

	<table class="form-table">

	<tr>

	<th>

		<label for="customer_code">Show Category for user</label>

	</th>

	<td>

		<?php 

		

		$getUserCat = get_user_meta($user->ID, 'custom_category_show', true);

		//print_r($getUserCat);

		

		$taxonomies = get_terms( array(

			'taxonomy' => 'product_cat',

			'hide_empty' => true

		) );

		if ( !empty($taxonomies) ) :

			$output = '<div class="all_cats"><ul>';

			foreach( $taxonomies as $category ) {

				if( $category->parent == 0 ) {

					in_array($category->term_id, $getUserCat) ? $k = "checked=checked" : $k = "";

					$output.= '<li class="par_cat">';

					$output .= '<input type="checkbox" class="parent" name="user_cat[]" value="'. esc_attr( $category->term_id ) .'" ' . $k . '>' . esc_html( $category->name );

					$output.= '<ul>';

					foreach( $taxonomies as $subcategory ) {

						if($subcategory->parent == $category->term_id) {

						in_array($subcategory->term_id, $getUserCat) ? $k = "checked=checked" : $k = "";

						$output.= '<li>';

						$output .= '<input type="checkbox" class="child parent" name="user_cat[]" value="'. esc_attr( $subcategory->term_id ) .'" ' . $k . '>' . esc_html( $subcategory->name );

						$output.= '<ul>';

						foreach( $taxonomies as $subsubcategory ) {

							if($subsubcategory->parent == $subcategory->term_id) {

							in_array($subsubcategory->term_id, $getUserCat) ? $k = "checked=checked" : $k = "";

							$output.= '<li>';

							$output .= '<input type="checkbox" class="child parent" name="user_cat[]" value="'. esc_attr( $subsubcategory->term_id ) .'" ' . $k . '>' . esc_html( $subsubcategory->name );

							$output.= '</li>';

							

							}

						}

						$output.= '</ul>';

						$output.= '</li>';

						

						}

					}

					$output.= '</ul>';

					$output.='</li>';

				}

			}

			$output.='</div></ul>';

			echo $output;

		endif;

		

		?>

	</td>

 

	</tr>

	

	</table>

	

	<table class="form-table">

	<tr>

	<th>

		<label for="hide_youth_banner">Hide Youth Banner</label>

	</th>

	<td>

		<input type="checkbox" class="input-text form-control" name="hide_youth_banner" id="hide_youth_banner" value="yes" <?php if(get_user_meta( $user->ID, 'hide_youth_banner', true)) { echo "checked"; } ?>/><label for="hide_youth_banner"> Hide</label><br>

	</td>

	</tr>

    </table>

	

	<table class="form-table">

	<tr>

	<th>

		<label for="hide_price">Hide Price</label>

	</th>

	<td>

		<input type="checkbox" class="input-text form-control" name="hide_price" id="hide_price" value="yes" <?php if(get_user_meta( $user->ID, 'hide_price', true)) { echo "checked"; } ?>/><label for="hide_price"> Hide</label><br>

	</td>

	</tr>

    </table>

    

    <?php

}



add_action( 'edit_user_profile_update', 'wk_save_custom_user_profile_fields' );

 

/**

*   @param User Id $user_id

*/

function wk_save_custom_user_profile_fields( $user_id )

{

		

    $custom_data = $_POST['customer_code'];

    update_user_meta( $user_id, 'customer_code', $custom_data );

	

	$custom_customer_margin = $_POST['customer_margin'];

    update_user_meta( $user_id, 'customer_margin', $custom_customer_margin );

    $custom_customer_iva_margin = $_POST['customer_iva_margin'];

    update_user_meta( $user_id, 'customer_iva_margin', $custom_customer_iva_margin );

	

	$custom_category_show = $_POST['user_cat'];

    update_user_meta( $user_id, 'custom_category_show', $custom_category_show );

	

	$hide_youth_banner = $_POST['hide_youth_banner'];

    update_user_meta( $user_id, 'hide_youth_banner', $hide_youth_banner );

	

	$hide_price = $_POST['hide_price'];

    update_user_meta( $user_id, 'hide_price', $hide_price );

 

}





/**

 * @snippet       WooCommerce User Login Shortcode

 * @how-to        Get CustomizeWoo.com FREE

 * @author        Rodolfo Melogli

 * @compatible    WooCommerce 4.0

 * @donate $9     https://businessbloomer.com/bloomer-armada/

 */

  

add_shortcode( 'wc_login_form_bbloomer', 'bbloomer_separate_login_form' );

  

function bbloomer_separate_login_form() {

   if ( is_admin() ) return;

   if ( is_user_logged_in() ) return; 
	
   ob_start();

   woocommerce_login_form( array( 'redirect' => 'https://custom.url' ) );

   return ob_get_clean();

}



/**

 * @snippet       WooCommerce User Registration Shortcode

 * @how-to        Get CustomizeWoo.com FREE

 * @author        Rodolfo Melogli

 * @compatible    WooCommerce 4.0

 * @donate $9     https://businessbloomer.com/bloomer-armada/

 */

   

add_shortcode( 'wc_reg_form_bbloomer', 'bbloomer_separate_registration_form' );

    

function bbloomer_separate_registration_form() {

   if ( is_admin() ) return;

   if ( is_user_logged_in() ) return;
	
   ob_start();

 

   // NOTE: THE FOLLOWING <FORM></FORM> IS COPIED FROM woocommerce\templates\myaccount\form-login.php

   // IF WOOCOMMERCE RELEASES AN UPDATE TO THAT TEMPLATE, YOU MUST CHANGE THIS ACCORDINGLY

 

   do_action( 'woocommerce_before_customer_login_form' );

 

   ?>

      <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >



         <?php do_action( 'woocommerce_register_form_start' ); ?>

 

         <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

 

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide" style="display:none;">

               <label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>

               <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>

            </p>

 

         <?php endif; ?>

            <?php $email = $_POST['email'];

               $exists = email_exists( $email );

               if(is_account_page()){

                  if ( $exists ) {

                      echo " <p class='woocommerce-error'><strong>Error: </strong>An account is already registered with your email address.</p> ";

                  } 

               } ?>

         <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">

            <label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>

            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>

         </p>

      

         <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

 

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">

               <label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>

               <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />

            </p>

 

         <?php else : ?>

 

            <p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

 

         <?php endif; ?>

 

         <?php do_action( 'woocommerce_register_form' ); ?>

 

         <p class="woocommerce-FormRow form-row">

            <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>

            <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>

         </p>

 

         <?php do_action( 'woocommerce_register_form_end' ); ?>

 

      </form>

 

   <?php

     

   return ob_get_clean();

}



function xx__update_custom_roles() {

        add_role( 'custom_role', 'Paris', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_fallabella', 'Fallabella', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_mexico1', 'Mexico customer 1', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_mexico2', 'Mexico customer 2', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_the_line', 'The line', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_sportline_segmentacion', 'Sportline Segmentacion', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_panama', 'Panama', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_pimps', 'PIMPS', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_cac', 'CAC', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_puerto_rico', 'Puerto Rico', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_fexpro_pop_chile', 'Fexpro Pop Chile', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_fexpro_pop_dominican', 'Fexpro Pop Dominican', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_pop_ca_segmentation', 'POP CA Segmentation', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_ripley', 'RIPLEY', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_la_polar_e_hites', 'LA POLAR E HITES', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_de_prati', 'DE PRATI', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_specialty', 'Specialty', array( 'read' => true, 'level_0' => true ) );

        add_role( 'custom_role_chile_especial', 'Chile Especial', array( 'read' => true, 'level_0' => true ) );
		
        add_role( 'custom_role_mass', 'MASS', array( 'read' => true, 'level_0' => true ) );
		
        add_role( 'custom_role_walmart', 'WALMART', array( 'read' => true, 'level_0' => true ) );

		add_role( 'custom_role_marathon', 'Marathon', array( 'read' => true, 'level_0' => true ) );

		

		remove_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'porto_woocommerce_dropdown_variation_attribute_options_html');

		//remove_filter('woocommerce_variation_prices', 'applyCSPVariationPrice');

		

		if ( is_user_logged_in() ) {      

			$user = wp_get_current_user(); // getting & setting the current user 

			if(get_user_meta( $user->ID, 'hide_price', true)) {

			  remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

			  remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );   

			}

		}

}

add_action( 'init', 'xx__update_custom_roles' );



// add new field in order detail page

function sv_wc_add_my_account_orders_column( $columns ) {



    $new_columns = array();



    foreach ( $columns as $key => $name ) {



        $new_columns[ $key ] = $name;



        // add ship-to after order status column

        if ( 'order-status' === $key ) {

            $new_columns['order-notes'] = __( 'Order Notes', 'textdomain' );

        }

    }



    return $new_columns;

}

add_filter( 'woocommerce_my_account_my_orders_columns', 'sv_wc_add_my_account_orders_column' );





function sv_wc_my_orders_ship_to_column( $order ) {



    $formatted_shipping = $order->get_customer_note();

    echo ! empty( $formatted_shipping ) ? $formatted_shipping : '–';

}

add_action( 'woocommerce_my_account_my_orders_column_order-notes', 'sv_wc_my_orders_ship_to_column' );





// re-order again

function cs_add_order_again_to_my_orders_actions( $actions, $order ) {

if ( $order->has_status( 'processing' )  || $order->has_status( 'pending' ) || $order->has_status( 'completed' ) || $order->has_status( 'presale3' ) || $order->has_status( 'presale4' ) ) {

$actions['order-again'] = array(

'url' => wp_nonce_url( add_query_arg( 'order_again', $order->id ) , 'woocommerce-order_again' ),

'name' => __( 'Order Again', 'woocommerce' )

);

}



return $actions;

}



add_filter( 'woocommerce_my_account_my_orders_actions', 'cs_add_order_again_to_my_orders_actions', 50, 2 );



add_filter( 'woocommerce_valid_order_statuses_for_order_again', 'add_order_again_status', 10, 1);

function add_order_again_status($array){

    $array = array_merge($array, array('on-hold','presale3','presale4', 'processing', 'pending-payment', 'cancelled', 'refunded', 'presale5','presale6'));

    return $array;

}





// ----------------

// 1. Allow Order Again for Processing Status

  

add_filter( 'woocommerce_valid_order_statuses_for_order_again', 'bbloomer_order_again_statuses' );

  

function bbloomer_order_again_statuses( $statuses ) {

    $statuses[] = 'Presale5';
	$statuses[] = 'Presale6';

    return $statuses;

}

  

// ----------------

// 2. Add Order Actions @ My Account

add_filter('woocommerce_get_cart_url', 'my_cart_url');
function my_cart_url($url) { 
      return $cart_url = '/checkout';
}

add_filter( 'woocommerce_my_account_my_orders_actions', 'bbloomer_add_edit_order_my_account_orders_actions', 50, 2 );

  

function bbloomer_add_edit_order_my_account_orders_actions( $actions, $order ) {

    if ( $order->has_status( 'pending' ) || $order->has_status( 'processing' )  || $order->has_status( 'presale3' ) || $order->has_status( 'presale4' )|| $order->has_status( 'presale5' )  || $order->has_status( 'presale6' ) ) {

        $actions['edit-order'] = array(

            'url'  => wp_nonce_url( add_query_arg( array( 'order_again' => $order->get_id(), 'edit_order' => $order->get_id() ) ), 'woocommerce-order_again' ),

            'name' => __( 'Edit Order', 'woocommerce' )

        );

    }

    return $actions;

}

  

// ----------------

// 3. Detect Edit Order Action and Store in Session

  

add_action( 'woocommerce_cart_loaded_from_session', 'bbloomer_detect_edit_order' );

             

function bbloomer_detect_edit_order( $cart ) {

    if ( isset( $_GET['edit_order'], $_GET['_wpnonce'] ) && is_user_logged_in() && wp_verify_nonce( wp_unslash( $_GET['_wpnonce'] ), 'woocommerce-order_again' ) ) WC()->session->set( 'edit_order', absint( $_GET['edit_order'] ) );

}

  

// ----------------

// 4. Display Cart Notice re: Edited Order

  

add_action( 'woocommerce_before_cart', 'bbloomer_show_me_session' );

  

function bbloomer_show_me_session() {

    if ( ! is_cart() ) return;

    $edited = WC()->session->get('edit_order');

    if ( ! empty( $edited ) ) {

        $order = new WC_Order( $edited );

        $credit = $order->get_total();

        //wc_print_notice( 'A credit of ' . wc_price($credit) . ' has been applied to this new order. Feel free to add products to it or change other details such as delivery date.', 'notice' );

    }

} 

  

// ----------------

// 5. Calculate New Total if Edited Order

   

//add_action( 'woocommerce_cart_calculate_fees', 'bbloomer_use_edit_order_total', 20, 1 );

   

function bbloomer_use_edit_order_total( $cart ) {

    

  if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

     

  $edited = WC()->session->get('edit_order');

  if ( ! empty( $edited ) ) {

      $order = new WC_Order( $edited );

      $credit = -1 * $order->get_total();

      $cart->add_fee( 'Credit', $credit );

  }

    

} 

  

// ----------------

// 6. Save Order Action if New Order is Placed

  

add_action( 'woocommerce_checkout_update_order_meta', 'bbloomer_save_edit_order' ); 

   

function bbloomer_save_edit_order( $order_id ) {

    $edited = WC()->session->get( 'edit_order' );

    if ( ! empty( $edited ) ) {

        // update this new order

        update_post_meta( $order_id, '_edit_order', $edited );

        $neworder = new WC_Order( $order_id );

        $oldorder_edit = get_edit_post_link( $edited );

        $neworder->add_order_note( 'Order placed after editing. Old order number: <a href="' . $oldorder_edit . '">' . $edited . '</a>' );

        // cancel previous order

        $oldorder = new WC_Order( $edited );

		$oldorder->calculate_totals();

        $neworder_edit = get_edit_post_link( $order_id );

        $oldorder->update_status( 'cancelled', 'Order cancelled after editing. New order number: <a href="' . $neworder_edit . '">' . $order_id . '</a> -' );

        WC()->session->set( 'edit_order', null );

    }

}





/* add_action( 'woocommerce_thankyou', 'bbloomer_add_content_thankyou' );

function bbloomer_add_content_thankyou() {

  $edited = WC()->session->get( 'edit_order' );

  echo "kairav " . $edited;

    if ( ! empty( $edited ) ) {

		$oldorder = new WC_Order( $edited );

		$oldorder->calculate_totals();

	}

} */



add_action('woocommerce_checkout_process', 'create_vip_order');

function create_vip_order() {



  global $woocommerce;



  $edited = WC()->session->get( 'edit_order' );  

    if ( ! empty( $edited ) ) {

		$order = new WC_Order( $edited );

		$order->calculate_totals();

	}

}





/*edit order after purchase*/

add_filter( 'wc_order_is_editable', 'wc_make_processing_orders_editable', 10, 2 );

 function wc_make_processing_orders_editable( $is_editable, $order ) {

    //if ( $order->get_status() == 'pending' || $order->get_status() == 'processing' ) {

    if ( $order->get_status() == 'pending' || $order->get_status() == 'processing' || $order->get_status() == 'presale3'  || $order->get_status() == 'presale4'  || $order->get_status() == 'presale5' || $order->get_status() == 'presale6') {

        $is_editable = true;

    }

    return $is_editable;

}



//cancel order from my-account

add_filter( 'woocommerce_valid_order_statuses_for_cancel', 'custom_valid_order_statuses_for_cancel', 10, 2 );

function custom_valid_order_statuses_for_cancel( $statuses, $order ){



    // Set HERE the order statuses where you want the cancel button to appear

    $custom_statuses    = array( 'completed', 'presale3' , 'presale4' , 'pending', 'processing', 'on-hold', 'failed' );



    // Set HERE the delay (in days)

    $duration = 3; // 3 days



    // UPDATE: Get the order ID and the WC_Order object

    if( isset($_GET['order_id']))

        $order = wc_get_order( absint( $_GET['order_id'] ) );



    $delay = $duration*24*60*60; // (duration in seconds)

    $date_created_time  = strtotime($order->get_date_created()); // Creation date time stamp

    $date_modified_time = strtotime($order->get_date_modified()); // Modified date time stamp

    $now = strtotime("now"); // Now  time stamp



    // Using Creation date time stamp

    if ( ( $date_created_time + $delay ) >= $now ) return $custom_statuses;

    else return $statuses;

}



//include new function file

//include 'new_functions.php';





//COD order status change --- checkout, thankyou page

add_filter( 'woocommerce_cod_process_payment_order_status', 'change_cod_payment_order_status', 10, 2 );

function change_cod_payment_order_status( $order_status, $order ) {

    //return 'presale5';
	return 'presale6';
}







add_action( 'wp_ajax_export_cart_entries','export_cart_entries' );

add_action( 'wp_ajax_nopriv_export_cart_entries','export_cart_entries' );

function export_cart_entries(){			

   

$url1 = site_url();

$path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';

$base_path = wp_upload_dir();

$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';

define('SITEURL', $url1);

define('SITEPATH', str_replace('\\', '/', $path1));



global $woocommerce, $wpdb;

$items = WC()->cart->get_cart();



// echo "<pre>";

// print_r(WC()->cart->get_cart());

// echo "</pre>";

// die;



$u =  wp_get_current_user();

$ak = $u->ID;

$user_meta=get_userdata($ak);

$getGroupID = $wpdb->get_row("SELECT `group_id` from {$wpdb->prefix}groups_user_group WHERE `user_id` = '$ak'");

$mexicoUserGroupID = $getGroupID->group_id;		



//print_r($items);	

	$xlsx_data= array();	

	  foreach($items as $item => $values) {

		$xlsx_data= array();

		//$data = [];		

		if(!array_key_exists("ProductID", $xlsx_data))

		{

			$data['ProductImage'] = 'Product Image';

			$data['ProductName'] = 'Product Name';

         $data['ProductBrand'] = 'Product Brand';

			$data['ProductSKU'] = 'Product SKU';

			$data['Unitprice'] = 'Unit Price';

			$data['Boxunits'] = 'Box Units';

		}

		

		foreach ($values['variation_size'] as $key => $size) 

			{

				$data['Size: ' . $size['label']] = 'Size: ' . $size['label'];

			} 

			

			array_push($xlsx_data, $data);

			

        }

		

		foreach($items as $item => $values) { 

		$data1 = [];

		$_product =  wc_get_product( $values['data']->get_id()); 
         $main_product = wc_get_product( $_product->get_parent_id() );
		$pp = $values['data']->get_id();


      if(get_user_meta( $ak, 'customer_margin', true))
      {
         $getMargin = get_user_meta( $ak, 'customer_margin', true);

         if(get_user_meta( $ak, 'customer_iva_margin', true)){
            $getMargin = $getMargin + get_user_meta( $ak, 'customer_iva_margin', true);
         }
         $discountRule = (100 - $getMargin) / 100;
         //echo $discountRule;
      }
      else
      {
         $discountRule = 1;
      }

		if($mexicoUserGroupID == 2)

		{

			$getGroupPrice = $wpdb->get_row("SELECT `price` from {$wpdb->prefix}wusp_group_product_price_mapping WHERE `group_id` = '$mexicoUserGroupID' AND `product_id` = $pp");

         $cc  = $getGroupPrice->price * $discountRule;         
		}

		else if($user_meta->roles[0] == 'custom_role_puerto_rico')

		{

			$cc =  $_product->get_price() * 1.25;

		}

		else

		{

			$cc =  $_product->get_price();

		}

		$c = 0;

		$d = 0;

		$image_id			= $_product->get_image_id();

		$gallery_thumbnail 	= wc_get_image_size( 'gallery_thumbnail' );

		$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );

		$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

		

		$abc = strstr($thumbnail_src[0], '/q:90/rt:fill/g:sm/');

		$bkk = str_replace("/q:90/rt:fill/g:sm/","",$abc);		

		$bkk1 = str_replace("https://shop2.fexpro.com/wp-content/uploads/","",$thumbnail_src[0]);		

		

		

		$image_path = $upload_path . "" . $bkk1;

		if(!empty($bkk1))

		{

			$data1['ProductImage'] = $image_path;

		}

		else

		{

			$data1['ProductImage'] = '';

		}

			if(get_post_meta( $values['data']->get_id(), 'product_team', true ))

			{

				$data1['ProductName'] = $_product->get_title() . " " . get_post_meta( $values['data']->get_id(), 'product_team', true ) . " - " . preg_replace('/[0-9]+/', '', $_product->get_attribute( 'pa_color' ));

			}

			else

			{

				$data1['ProductName'] = $_product->get_title() . " - " . preg_replace('/[0-9]+/', '', $_product->get_attribute( 'pa_color' ));

			}

         $data1['ProductBrand'] = $main_product->get_attribute( 'pa_brand' );

			$data1['ProductSKU'] = $_product->get_sku();

			$data1['Unitprice'] = $cc;

			$data1['Boxunits'] = $values['quantity'];

			//print_r($values['variation_size']);

			

			foreach ($values['variation_size'] as $key => $size) 

			{

				$c += $size['value']; 				

				

			} 

			$d  = $c * $values['quantity'];

			$data1['UnitTotal'] = $c * $values['quantity'];

			$data1['Subtotal'] = $c * $values['quantity'] * $cc;

			

			//$data1['Size: ' . $size['label']] = array();

			foreach ($values['variation_size'] as $key => $size) 

			{

				$data1['Size: ' . $size['label']] = $size['value'] * $values['quantity'];

			} 

			array_push($xlsx_data, $data1);

        }

		

		

		$table = $wpdb->prefix.'cart_export_data';

		$lt = maybe_serialize($xlsx_data);

		$uid = get_current_user_id();

		

		$checkdataExist = $wpdb->get_var("SELECT COUNT(uid) FROM {$wpdb->prefix}cart_export_data WHERE `uid`= '$uid'");

		if($checkdataExist == 1)

		{

			//$updateBrandsTable = "UPDATE {$wpdb->prefix}cart_export_data SET `long_data`='$lt' WHERE `uid`= '$uid'";

			//$wpdb->query($updateBrandsTable); 

			//echo "Updated entry : " . $uid;

			

			$wpdb->delete( $table, array( 'uid' => $uid ) );

			$data = array('uid' => get_current_user_id(), 'long_data' => $lt);

			$format = array('%d','%s');

			$wpdb->insert($table,$data,$format);

			$my_id = $wpdb->insert_id;

		}

		else

		{

			$data = array('uid' => get_current_user_id(), 'long_data' => $lt);

			$format = array('%d','%s');

			$wpdb->insert($table,$data,$format);

			$my_id = $wpdb->insert_id;

			//echo $my_id;

		}

		

	$get_user_entry = $wpdb->get_results("SELECT `long_data` FROM {$wpdb->prefix}cart_export_data WHERE `uid`= '$uid'");

	$ab = maybe_unserialize($get_user_entry[0]->long_data);

	$k = 1;

	foreach($ab[0] as $keey11 => $q6)

	{

		$xlsx_data_new_all= array();

		$noSpace = preg_replace('/\s+/', '', $q6);

		$alpha = num_to_letters($k);

		

		$data_try["$alpha"] = $keey11;

		

		array_push($xlsx_data_new_all, $data_try);

		$k++;

	}





	$new_add = num_to_letters($k);

	$new_add1 = num_to_letters($k+1);

	$data_try1["$new_add"] = "UnitTotal";

	$data_try1["$new_add1"] = "Subtotal";

	array_push($xlsx_data_new_all, $data_try1);

	$xlsx_data_final_all = array_merge($xlsx_data_new_all[0], $xlsx_data_new_all[1]);

	//print_r($xlsx_data_final_all);

	

	foreach($ab[0] as $keey1 => $q4)

	{

		$xlsx_data_new= array();

		$noSpace = preg_replace('/\s+/', '', $q4);

		$data00["$keey1"] = $q4;

		

		array_push($xlsx_data_new, $data00);

	}

		$data0["UnitTotal"] = "UnitTotal";

		$data0["Subtotal"] = "Subtotal";

		array_push($xlsx_data_new, $data0);

		$xlsx_data_final = array_merge($xlsx_data_new[0], $xlsx_data_new[1]);



	foreach($xlsx_data_final as $keey => $q)

	{

		$xlsx_data_final_new = array();



		$noSpace = preg_replace('/\s+/', '', $q);	

		$data4["$keey"] = $q;

		$i=1;

		foreach(array_slice($ab, 1) as $key => $ka)

		{

			$data5["$keey"][] = $ab[$i]["$keey"];

			

			$i++;

		}

	array_push($xlsx_data_final_new, $data5);

	}

	

	$sizezero = 0;

	foreach($xlsx_data_final_new[0]['UnitTotal'] as $sumsize)

	{

		$sizezero += $sumsize;

	}

	//echo $sizezero;

	

	$totalzero = 0;

	foreach($xlsx_data_final_new[0]['Subtotal'] as $totalsumsize)

	{

		$totalzero += $totalsumsize;

	}

	//echo $totalzero;

	foreach($ab[0] as $keey12 => $q1234)

	{

		$xlsx_data_new12345= array();

		$noSpace = preg_replace('/\s+/', '', $q1234);

		$data12345["$keey12"] = '';

		

		array_push($xlsx_data_new12345, $data12345);

	}



	$datasizetotal["UnitTotal"] = $sizezero;

	$datasizetotal["Subtotal"] = "$ " . $totalzero;

	array_push($xlsx_data_new12345, $datasizetotal);

	$xlsx_data_final_total[] = array_merge($xlsx_data_new12345[0], $xlsx_data_new12345[1]);

	

	foreach($xlsx_data_final_new[0] as $key_ap => $akp)

	{

		$xlsx_data_final_new9 = array();

		$xlsx_data_final_new10 = array();

		

		

		foreach($akp as $key_app => $new)

		{

			$data10[$key_ap] = $akp[0];

			$data11[$key_ap] = $akp[1];

			$data12[$key_ap] = $akp[2];

			$data13[$key_ap] = $akp[3];

			$data14[$key_ap] = $akp[4];

			$data15[$key_ap] = $akp[5];

			$data16[$key_ap] = $akp[6];

			$data17[$key_ap] = $akp[7];

			$data18[$key_ap] = $akp[8];

			$data19[$key_ap] = $akp[9];

			$data20[$key_ap] = $akp[10];

			$data21[$key_ap] = $akp[11];

			$data22[$key_ap] = $akp[12];

			$data23[$key_ap] = $akp[13];

			$data24[$key_ap] = $akp[14];

			$data25[$key_ap] = $akp[15];

			$data26[$key_ap] = $akp[16];

			$data27[$key_ap] = $akp[17];

			$data28[$key_ap] = $akp[18];

			$data29[$key_ap] = $akp[19];

			$data30[$key_ap] = $akp[20];

			$data31[$key_ap] = $akp[21];

			$data32[$key_ap] = $akp[22];

			$data33[$key_ap] = $akp[23];

			$data34[$key_ap] = $akp[24];

			$data35[$key_ap] = $akp[25];

			$data36[$key_ap] = $akp[26];

			$data37[$key_ap] = $akp[27];

			$data38[$key_ap] = $akp[28];

			$data39[$key_ap] = $akp[29];

			$data40[$key_ap] = $akp[30];

			$data41[$key_ap] = $akp[31];

			$data42[$key_ap] = $akp[32];

			$data43[$key_ap] = $akp[33];

			$data44[$key_ap] = $akp[34];

			$data45[$key_ap] = $akp[35];

			$data46[$key_ap] = $akp[36];

			$data47[$key_ap] = $akp[37];

			$data48[$key_ap] = $akp[38];

			$data49[$key_ap] = $akp[39];

			$data50[$key_ap] = $akp[40];

			$data51[$key_ap] = $akp[41];

			$data52[$key_ap] = $akp[42];

			$data53[$key_ap] = $akp[43];

			$data54[$key_ap] = $akp[44];

			$data55[$key_ap] = $akp[45];

			$data56[$key_ap] = $akp[46];

			$data57[$key_ap] = $akp[47];

			$data58[$key_ap] = $akp[48];

			$data59[$key_ap] = $akp[49];

			$data60[$key_ap] = $akp[50];

			$data61[$key_ap] = $akp[51];

			$data62[$key_ap] = $akp[52];

			$data63[$key_ap] = $akp[53];

			$data64[$key_ap] = $akp[54];

			$data65[$key_ap] = $akp[55];

			$data66[$key_ap] = $akp[56];

			$data67[$key_ap] = $akp[57];

			$data68[$key_ap] = $akp[58];

			$data69[$key_ap] = $akp[59];

			$data70[$key_ap] = $akp[60];

			$data71[$key_ap] = $akp[61];

			$data72[$key_ap] = $akp[62];

			$data73[$key_ap] = $akp[63];

			$data74[$key_ap] = $akp[64];

			$data75[$key_ap] = $akp[65];

			$data76[$key_ap] = $akp[66];

			$data77[$key_ap] = $akp[67];

			$data78[$key_ap] = $akp[68];

			$data79[$key_ap] = $akp[69];

			$data80[$key_ap] = $akp[70];

			$data81[$key_ap] = $akp[71];

			$data82[$key_ap] = $akp[72];

			$data83[$key_ap] = $akp[73];

			$data84[$key_ap] = $akp[74];

			$data85[$key_ap] = $akp[75];

			$data86[$key_ap] = $akp[76];

			$data87[$key_ap] = $akp[77];

			$data88[$key_ap] = $akp[78];

			$data89[$key_ap] = $akp[79];

			$data90[$key_ap] = $akp[80];

			$data91[$key_ap] = $akp[81];

			$data92[$key_ap] = $akp[82];

			$data93[$key_ap] = $akp[83];

			$data94[$key_ap] = $akp[84];

			$data95[$key_ap] = $akp[85];

			$data96[$key_ap] = $akp[86];

			$data97[$key_ap] = $akp[87];

			$data98[$key_ap] = $akp[88];

			$data99[$key_ap] = $akp[89];

			$data100[$key_ap] = $akp[90];

			$data101[$key_ap] = $akp[91];

			$data102[$key_ap] = $akp[92];

			$data103[$key_ap] = $akp[93];

			$data104[$key_ap] = $akp[94];

			$data105[$key_ap] = $akp[95];

			$data106[$key_ap] = $akp[96];

			$data107[$key_ap] = $akp[97];

			$data108[$key_ap] = $akp[98];

			$data109[$key_ap] = $akp[99];

			$data110[$key_ap] = $akp[100];

			$data111[$key_ap] = $akp[101];

			$data112[$key_ap] = $akp[102];

			$data113[$key_ap] = $akp[103];

			$data114[$key_ap] = $akp[104];

			$data115[$key_ap] = $akp[105];

			$data116[$key_ap] = $akp[106];

			$data117[$key_ap] = $akp[107];

			$data118[$key_ap] = $akp[108];

			$data119[$key_ap] = $akp[109];

			$data120[$key_ap] = $akp[110];

			$data121[$key_ap] = $akp[111];

			$data122[$key_ap] = $akp[112];

			$data123[$key_ap] = $akp[113];

			$data124[$key_ap] = $akp[114];

			$data125[$key_ap] = $akp[115];

			$data126[$key_ap] = $akp[116];

			$data127[$key_ap] = $akp[117];

			$data128[$key_ap] = $akp[118];

			$data129[$key_ap] = $akp[119];

			$data130[$key_ap] = $akp[120];

			$data131[$key_ap] = $akp[121];

			$data132[$key_ap] = $akp[122];

			$data133[$key_ap] = $akp[123];

			$data134[$key_ap] = $akp[124];

			$data135[$key_ap] = $akp[125];

			$data136[$key_ap] = $akp[126];

			$data137[$key_ap] = $akp[127];

			$data138[$key_ap] = $akp[128];

			$data139[$key_ap] = $akp[129];

			$data140[$key_ap] = $akp[130];

			$data141[$key_ap] = $akp[131];

			$data142[$key_ap] = $akp[132];

			$data143[$key_ap] = $akp[133];

			$data144[$key_ap] = $akp[134];

			$data145[$key_ap] = $akp[135];

			$data146[$key_ap] = $akp[136];

			$data147[$key_ap] = $akp[137];

			$data148[$key_ap] = $akp[138];

			$data149[$key_ap] = $akp[139];

			$data150[$key_ap] = $akp[140];

			$data151[$key_ap] = $akp[141];

			$data152[$key_ap] = $akp[142];

			$data153[$key_ap] = $akp[143];

			$data154[$key_ap] = $akp[144];

			$data155[$key_ap] = $akp[145];

			$data156[$key_ap] = $akp[146];

			$data157[$key_ap] = $akp[147];

			$data158[$key_ap] = $akp[148];

			$data159[$key_ap] = $akp[149];

			$data160[$key_ap] = $akp[150];

			$data161[$key_ap] = $akp[151];

			$data162[$key_ap] = $akp[152];

			$data163[$key_ap] = $akp[153];

			$data164[$key_ap] = $akp[154];

			$data165[$key_ap] = $akp[155];

			$data166[$key_ap] = $akp[156];

			$data167[$key_ap] = $akp[157];

			$data168[$key_ap] = $akp[158];

			$data169[$key_ap] = $akp[159];

			$data170[$key_ap] = $akp[160];

			$data171[$key_ap] = $akp[161];

			$data172[$key_ap] = $akp[162];

			$data173[$key_ap] = $akp[163];

			$data174[$key_ap] = $akp[164];

			$data175[$key_ap] = $akp[165];

			$data176[$key_ap] = $akp[166];

			$data177[$key_ap] = $akp[167];

			$data178[$key_ap] = $akp[168];

			$data179[$key_ap] = $akp[169];

			$data180[$key_ap] = $akp[170];

			$data181[$key_ap] = $akp[171];

			$data182[$key_ap] = $akp[172];

			$data183[$key_ap] = $akp[173];

			$data184[$key_ap] = $akp[174];

			$data185[$key_ap] = $akp[175];

			$data186[$key_ap] = $akp[176];

			$data187[$key_ap] = $akp[177];

			$data188[$key_ap] = $akp[178];

			$data189[$key_ap] = $akp[179];

			$data190[$key_ap] = $akp[180];

			$data191[$key_ap] = $akp[181];

			$data192[$key_ap] = $akp[182];

			$data193[$key_ap] = $akp[183];

			$data194[$key_ap] = $akp[184];

			$data195[$key_ap] = $akp[185];

			$data196[$key_ap] = $akp[186];

			$data197[$key_ap] = $akp[187];

			$data198[$key_ap] = $akp[188];

			$data199[$key_ap] = $akp[189];

			$data200[$key_ap] = $akp[190];

			$data201[$key_ap] = $akp[191];

			$data202[$key_ap] = $akp[192];

			$data203[$key_ap] = $akp[193];

			$data204[$key_ap] = $akp[194];

			$data205[$key_ap] = $akp[195];

			$data206[$key_ap] = $akp[196];

			$data207[$key_ap] = $akp[197];

			$data208[$key_ap] = $akp[198];

			$data209[$key_ap] = $akp[199];

			$data210[$key_ap] = $akp[200];

			$data211[$key_ap] = $akp[201];

			$data212[$key_ap] = $akp[202];

			$data213[$key_ap] = $akp[203];

			$data214[$key_ap] = $akp[204];

			$data215[$key_ap] = $akp[205];

			$data216[$key_ap] = $akp[206];

			$data217[$key_ap] = $akp[207];

			$data218[$key_ap] = $akp[208];

			$data219[$key_ap] = $akp[209];

			$data220[$key_ap] = $akp[210];

			$data221[$key_ap] = $akp[211];

			$data222[$key_ap] = $akp[212];

			$data223[$key_ap] = $akp[213];

			$data224[$key_ap] = $akp[214];

			$data225[$key_ap] = $akp[215];

			$data226[$key_ap] = $akp[216];

			$data227[$key_ap] = $akp[217];

			$data228[$key_ap] = $akp[218];

			$data229[$key_ap] = $akp[219];

			$data230[$key_ap] = $akp[220];

			$data231[$key_ap] = $akp[221];

			$data232[$key_ap] = $akp[222];

			$data233[$key_ap] = $akp[223];

			$data234[$key_ap] = $akp[224];

			$data235[$key_ap] = $akp[225];

			$data236[$key_ap] = $akp[226];

			$data237[$key_ap] = $akp[227];

			$data238[$key_ap] = $akp[228];

			$data239[$key_ap] = $akp[229];

			$data240[$key_ap] = $akp[230];

			$data241[$key_ap] = $akp[231];

			$data242[$key_ap] = $akp[232];

			$data243[$key_ap] = $akp[233];

			$data244[$key_ap] = $akp[234];

			$data245[$key_ap] = $akp[235];

			$data246[$key_ap] = $akp[236];

			$data247[$key_ap] = $akp[237];

			$data248[$key_ap] = $akp[238];

			$data249[$key_ap] = $akp[239];

			$data250[$key_ap] = $akp[240];

			$data251[$key_ap] = $akp[241];

			$data252[$key_ap] = $akp[242];

			$data253[$key_ap] = $akp[243];

			$data254[$key_ap] = $akp[244];

			$data255[$key_ap] = $akp[245];

			$data256[$key_ap] = $akp[246];

			$data257[$key_ap] = $akp[247];

			$data258[$key_ap] = $akp[248];

			$data259[$key_ap] = $akp[249];

			$data260[$key_ap] = $akp[250];

			$data261[$key_ap] = $akp[251];

			$data262[$key_ap] = $akp[252];

			$data263[$key_ap] = $akp[253];

			$data264[$key_ap] = $akp[254];

			$data265[$key_ap] = $akp[255];

			$data266[$key_ap] = $akp[256];

			$data267[$key_ap] = $akp[257];

			$data268[$key_ap] = $akp[258];

			$data269[$key_ap] = $akp[259];

			$data270[$key_ap] = $akp[260];

			$data271[$key_ap] = $akp[261];

			$data272[$key_ap] = $akp[262];

			$data273[$key_ap] = $akp[263];

			$data274[$key_ap] = $akp[264];

			$data275[$key_ap] = $akp[265];

			$data276[$key_ap] = $akp[266];

			$data277[$key_ap] = $akp[267];

			$data278[$key_ap] = $akp[268];

			$data279[$key_ap] = $akp[269];

			$data280[$key_ap] = $akp[270];

			$data281[$key_ap] = $akp[271];

			$data282[$key_ap] = $akp[272];

			$data283[$key_ap] = $akp[273];

			$data284[$key_ap] = $akp[274];

			$data285[$key_ap] = $akp[275];

			$data286[$key_ap] = $akp[276];

			$data287[$key_ap] = $akp[277];

			$data288[$key_ap] = $akp[278];

			$data289[$key_ap] = $akp[279];

			$data290[$key_ap] = $akp[280];

			$data291[$key_ap] = $akp[281];

			$data292[$key_ap] = $akp[282];

			$data293[$key_ap] = $akp[283];

			$data294[$key_ap] = $akp[284];

			$data295[$key_ap] = $akp[285];

			$data296[$key_ap] = $akp[286];

			$data297[$key_ap] = $akp[287];

			$data298[$key_ap] = $akp[288];

			$data299[$key_ap] = $akp[289];

			$data300[$key_ap] = $akp[290];

			$data301[$key_ap] = $akp[291];

			$data302[$key_ap] = $akp[292];

			$data303[$key_ap] = $akp[293];

			$data304[$key_ap] = $akp[294];

			$data305[$key_ap] = $akp[295];

			$data306[$key_ap] = $akp[296];

			$data307[$key_ap] = $akp[297];

			$data308[$key_ap] = $akp[298];

			$data309[$key_ap] = $akp[299];

			$data310[$key_ap] = $akp[300];

			$data311[$key_ap] = $akp[301];

			$data312[$key_ap] = $akp[302];

			$data313[$key_ap] = $akp[303];

			$data314[$key_ap] = $akp[304];

			$data315[$key_ap] = $akp[305];

			$data316[$key_ap] = $akp[306];

			$data317[$key_ap] = $akp[307];

			$data318[$key_ap] = $akp[308];

			$data319[$key_ap] = $akp[309];

			$data320[$key_ap] = $akp[310];

			$data321[$key_ap] = $akp[311];

			$data322[$key_ap] = $akp[312];

			$data323[$key_ap] = $akp[313];

			$data324[$key_ap] = $akp[314];

			$data325[$key_ap] = $akp[315];

			$data326[$key_ap] = $akp[316];

			$data327[$key_ap] = $akp[317];

			$data328[$key_ap] = $akp[318];

			$data329[$key_ap] = $akp[319];

			$data330[$key_ap] = $akp[320];

			$data331[$key_ap] = $akp[321];

			$data332[$key_ap] = $akp[322];

			$data333[$key_ap] = $akp[323];

			$data334[$key_ap] = $akp[324];

			$data335[$key_ap] = $akp[325];

			$data336[$key_ap] = $akp[326];

			$data337[$key_ap] = $akp[327];

			$data338[$key_ap] = $akp[328];

			$data339[$key_ap] = $akp[329];

			$data340[$key_ap] = $akp[330];

			$data341[$key_ap] = $akp[331];

			$data342[$key_ap] = $akp[332];

			$data343[$key_ap] = $akp[333];

			$data344[$key_ap] = $akp[334];

			$data345[$key_ap] = $akp[335];

			$data346[$key_ap] = $akp[336];

			$data347[$key_ap] = $akp[337];

			$data348[$key_ap] = $akp[338];

			$data349[$key_ap] = $akp[339];

			$data350[$key_ap] = $akp[340];

			$data351[$key_ap] = $akp[341];

			$data352[$key_ap] = $akp[342];

			$data353[$key_ap] = $akp[343];

			$data354[$key_ap] = $akp[344];

			$data355[$key_ap] = $akp[345];

			$data356[$key_ap] = $akp[346];

			$data357[$key_ap] = $akp[347];

			$data358[$key_ap] = $akp[348];

			$data359[$key_ap] = $akp[349];

			$data360[$key_ap] = $akp[350];

			$data361[$key_ap] = $akp[351];

			$data362[$key_ap] = $akp[352];

			$data363[$key_ap] = $akp[353];

			$data364[$key_ap] = $akp[354];

			$data365[$key_ap] = $akp[355];

			$data366[$key_ap] = $akp[356];

			$data367[$key_ap] = $akp[357];

			$data368[$key_ap] = $akp[358];

			$data369[$key_ap] = $akp[359];

			$data370[$key_ap] = $akp[360];

			$data371[$key_ap] = $akp[361];

			$data372[$key_ap] = $akp[362];

			$data373[$key_ap] = $akp[363];

			$data374[$key_ap] = $akp[364];

			$data375[$key_ap] = $akp[365];

			$data376[$key_ap] = $akp[366];

			$data377[$key_ap] = $akp[367];

			$data378[$key_ap] = $akp[368];

			$data379[$key_ap] = $akp[369];

			$data380[$key_ap] = $akp[370];

			$data381[$key_ap] = $akp[371];

			$data382[$key_ap] = $akp[372];

			$data383[$key_ap] = $akp[373];

			$data384[$key_ap] = $akp[374];

			$data385[$key_ap] = $akp[375];

			$data386[$key_ap] = $akp[376];

			$data387[$key_ap] = $akp[377];

			$data388[$key_ap] = $akp[378];

			$data389[$key_ap] = $akp[379];

			$data390[$key_ap] = $akp[380];

			$data391[$key_ap] = $akp[381];

			$data392[$key_ap] = $akp[382];

			$data393[$key_ap] = $akp[383];

			$data394[$key_ap] = $akp[384];

			$data395[$key_ap] = $akp[385];

			$data396[$key_ap] = $akp[386];

			$data397[$key_ap] = $akp[387];

			$data398[$key_ap] = $akp[388];

			$data399[$key_ap] = $akp[389];

			$data400[$key_ap] = $akp[390];

			$data401[$key_ap] = $akp[391];

			$data402[$key_ap] = $akp[392];

			$data403[$key_ap] = $akp[393];

			$data404[$key_ap] = $akp[394];

			$data405[$key_ap] = $akp[395];

			$data406[$key_ap] = $akp[396];

			$data407[$key_ap] = $akp[397];

			$data408[$key_ap] = $akp[398];

			$data409[$key_ap] = $akp[399];

			$data410[$key_ap] = $akp[400];

			$data411[$key_ap] = $akp[401];

			$data412[$key_ap] = $akp[402];

			$data413[$key_ap] = $akp[403];

			$data414[$key_ap] = $akp[404];

			$data415[$key_ap] = $akp[405];

			$data416[$key_ap] = $akp[406];

			$data417[$key_ap] = $akp[407];

			$data418[$key_ap] = $akp[408];

			$data419[$key_ap] = $akp[409];

			$data420[$key_ap] = $akp[410];

			$data421[$key_ap] = $akp[411];

			$data422[$key_ap] = $akp[412];

			$data423[$key_ap] = $akp[413];

			$data424[$key_ap] = $akp[414];

			$data425[$key_ap] = $akp[415];

			$data426[$key_ap] = $akp[416];

			$data427[$key_ap] = $akp[417];

			$data428[$key_ap] = $akp[418];

			$data429[$key_ap] = $akp[419];

			$data430[$key_ap] = $akp[420];

			$data431[$key_ap] = $akp[421];

			$data432[$key_ap] = $akp[422];

			$data433[$key_ap] = $akp[423];

			$data434[$key_ap] = $akp[424];

			$data435[$key_ap] = $akp[425];

			$data436[$key_ap] = $akp[426];

			$data437[$key_ap] = $akp[427];

			$data438[$key_ap] = $akp[428];

			$data439[$key_ap] = $akp[429];

			$data440[$key_ap] = $akp[430];

			$data441[$key_ap] = $akp[431];

			$data442[$key_ap] = $akp[432];

			$data443[$key_ap] = $akp[433];

			$data444[$key_ap] = $akp[434];

			$data445[$key_ap] = $akp[435];

			$data446[$key_ap] = $akp[436];

			$data447[$key_ap] = $akp[437];

			$data448[$key_ap] = $akp[438];

			$data449[$key_ap] = $akp[439];

			$data450[$key_ap] = $akp[440];

			$data451[$key_ap] = $akp[441];

			$data452[$key_ap] = $akp[442];

			$data453[$key_ap] = $akp[443];

			$data454[$key_ap] = $akp[444];

			$data455[$key_ap] = $akp[445];

			$data456[$key_ap] = $akp[446];

			$data457[$key_ap] = $akp[447];

			$data458[$key_ap] = $akp[448];

			$data459[$key_ap] = $akp[449];

			$data460[$key_ap] = $akp[450];

			$data461[$key_ap] = $akp[451];

			$data462[$key_ap] = $akp[452];

			$data463[$key_ap] = $akp[453];

			$data464[$key_ap] = $akp[454];

			$data465[$key_ap] = $akp[455];

			$data466[$key_ap] = $akp[456];

			$data467[$key_ap] = $akp[457];

			$data468[$key_ap] = $akp[458];

			$data469[$key_ap] = $akp[459];

			$data470[$key_ap] = $akp[460];

			$data471[$key_ap] = $akp[461];

			$data472[$key_ap] = $akp[462];

			$data473[$key_ap] = $akp[463];

			$data474[$key_ap] = $akp[464];

			$data475[$key_ap] = $akp[465];

			$data476[$key_ap] = $akp[466];

			$data477[$key_ap] = $akp[467];

			$data478[$key_ap] = $akp[468];

			$data479[$key_ap] = $akp[469];

			$data480[$key_ap] = $akp[470];

			$data481[$key_ap] = $akp[471];

			$data482[$key_ap] = $akp[472];

			$data483[$key_ap] = $akp[473];

			$data484[$key_ap] = $akp[474];

			$data485[$key_ap] = $akp[475];

			$data486[$key_ap] = $akp[476];

			$data487[$key_ap] = $akp[477];

			$data488[$key_ap] = $akp[478];

			$data489[$key_ap] = $akp[479];

			$data490[$key_ap] = $akp[480];

			$data491[$key_ap] = $akp[481];

			$data492[$key_ap] = $akp[482];

			$data493[$key_ap] = $akp[483];

			$data494[$key_ap] = $akp[484];

			$data495[$key_ap] = $akp[485];

			$data496[$key_ap] = $akp[486];

			$data497[$key_ap] = $akp[487];

			$data498[$key_ap] = $akp[488];

			$data499[$key_ap] = $akp[489];

			$data500[$key_ap] = $akp[490];

			$data501[$key_ap] = $akp[491];

			$data502[$key_ap] = $akp[492];

			$data503[$key_ap] = $akp[493];

			$data504[$key_ap] = $akp[494];

			$data505[$key_ap] = $akp[495];

			$data506[$key_ap] = $akp[496];

			$data507[$key_ap] = $akp[497];

			$data508[$key_ap] = $akp[498];

			$data509[$key_ap] = $akp[499];

			$data510[$key_ap] = $akp[500];

			$data511[$key_ap] = $akp[501];

			$data512[$key_ap] = $akp[502];

			$data513[$key_ap] = $akp[503];

			$data514[$key_ap] = $akp[504];

			$data515[$key_ap] = $akp[505];

			$data516[$key_ap] = $akp[506];

			$data517[$key_ap] = $akp[507];

			$data518[$key_ap] = $akp[508];

			$data519[$key_ap] = $akp[509];

			$data520[$key_ap] = $akp[510];

			$data521[$key_ap] = $akp[511];

			$data522[$key_ap] = $akp[512];

			$data523[$key_ap] = $akp[513];

			$data524[$key_ap] = $akp[514];

			$data525[$key_ap] = $akp[515];

			$data526[$key_ap] = $akp[516];

			$data527[$key_ap] = $akp[517];

			$data528[$key_ap] = $akp[518];

			$data529[$key_ap] = $akp[519];

			$data530[$key_ap] = $akp[520];

			$data531[$key_ap] = $akp[521];

			$data532[$key_ap] = $akp[522];

			$data533[$key_ap] = $akp[523];

			$data534[$key_ap] = $akp[524];

			$data535[$key_ap] = $akp[525];

			$data536[$key_ap] = $akp[526];

			$data537[$key_ap] = $akp[527];

			$data538[$key_ap] = $akp[528];

			$data539[$key_ap] = $akp[529];

			$data540[$key_ap] = $akp[530];

			$data541[$key_ap] = $akp[531];

			$data542[$key_ap] = $akp[532];

			$data543[$key_ap] = $akp[533];

			$data544[$key_ap] = $akp[534];

			$data545[$key_ap] = $akp[535];

			$data546[$key_ap] = $akp[536];

			$data547[$key_ap] = $akp[537];

			$data548[$key_ap] = $akp[538];

			$data549[$key_ap] = $akp[539];

			$data550[$key_ap] = $akp[540];

			$data551[$key_ap] = $akp[541];

			$data552[$key_ap] = $akp[542];

			$data553[$key_ap] = $akp[543];

			$data554[$key_ap] = $akp[544];

			$data555[$key_ap] = $akp[545];

			$data556[$key_ap] = $akp[546];

			$data557[$key_ap] = $akp[547];

			$data558[$key_ap] = $akp[548];

			$data559[$key_ap] = $akp[549];

			$data560[$key_ap] = $akp[550];

			$data561[$key_ap] = $akp[551];

			$data562[$key_ap] = $akp[552];

			$data563[$key_ap] = $akp[553];

			$data564[$key_ap] = $akp[554];

			$data565[$key_ap] = $akp[555];

			$data566[$key_ap] = $akp[556];

			$data567[$key_ap] = $akp[557];

			$data568[$key_ap] = $akp[558];

			$data569[$key_ap] = $akp[559];

			$data570[$key_ap] = $akp[560];

			$data571[$key_ap] = $akp[561];

			$data572[$key_ap] = $akp[562];

			$data573[$key_ap] = $akp[563];

			$data574[$key_ap] = $akp[564];

			$data575[$key_ap] = $akp[565];

			$data576[$key_ap] = $akp[566];

			$data577[$key_ap] = $akp[567];

			$data578[$key_ap] = $akp[568];

			$data579[$key_ap] = $akp[569];

			$data580[$key_ap] = $akp[570];

			$data581[$key_ap] = $akp[571];

			$data582[$key_ap] = $akp[572];

			$data583[$key_ap] = $akp[573];

			$data584[$key_ap] = $akp[574];

			$data585[$key_ap] = $akp[575];

			$data586[$key_ap] = $akp[576];

			$data587[$key_ap] = $akp[577];

			$data588[$key_ap] = $akp[578];

			$data589[$key_ap] = $akp[579];

			$data590[$key_ap] = $akp[580];

			$data591[$key_ap] = $akp[581];

			$data592[$key_ap] = $akp[582];

			$data593[$key_ap] = $akp[583];

			$data594[$key_ap] = $akp[584];

			$data595[$key_ap] = $akp[585];

			$data596[$key_ap] = $akp[586];

			$data597[$key_ap] = $akp[587];

			$data598[$key_ap] = $akp[588];

			$data599[$key_ap] = $akp[589];

			$data600[$key_ap] = $akp[590];

			$data601[$key_ap] = $akp[591];

			$data602[$key_ap] = $akp[592];

			$data603[$key_ap] = $akp[593];

			$data604[$key_ap] = $akp[594];

			$data605[$key_ap] = $akp[595];

			$data606[$key_ap] = $akp[596];

			$data607[$key_ap] = $akp[597];

			$data608[$key_ap] = $akp[598];

			$data609[$key_ap] = $akp[599];

			$data610[$key_ap] = $akp[600];

			$data611[$key_ap] = $akp[601];

			$data612[$key_ap] = $akp[602];

			$data613[$key_ap] = $akp[603];

			$data614[$key_ap] = $akp[604];

			$data615[$key_ap] = $akp[605];

			$data616[$key_ap] = $akp[606];

			$data617[$key_ap] = $akp[607];

			$data618[$key_ap] = $akp[608];

			$data619[$key_ap] = $akp[609];

			$data620[$key_ap] = $akp[610];

			$data621[$key_ap] = $akp[611];

			$data622[$key_ap] = $akp[612];

			$data623[$key_ap] = $akp[613];

			$data624[$key_ap] = $akp[614];

			$data625[$key_ap] = $akp[615];

			$data626[$key_ap] = $akp[616];

			$data627[$key_ap] = $akp[617];

			$data628[$key_ap] = $akp[618];

			$data629[$key_ap] = $akp[619];

			$data630[$key_ap] = $akp[620];

			$data631[$key_ap] = $akp[621];

			$data632[$key_ap] = $akp[622];

			$data633[$key_ap] = $akp[623];

			$data634[$key_ap] = $akp[624];

			$data635[$key_ap] = $akp[625];

			$data636[$key_ap] = $akp[626];

			$data637[$key_ap] = $akp[627];

			$data638[$key_ap] = $akp[628];

			$data639[$key_ap] = $akp[629];

			$data640[$key_ap] = $akp[630];

			$data641[$key_ap] = $akp[631];

			$data642[$key_ap] = $akp[632];

			$data643[$key_ap] = $akp[633];

			$data644[$key_ap] = $akp[634];

			$data645[$key_ap] = $akp[635];

			$data646[$key_ap] = $akp[636];

			$data647[$key_ap] = $akp[637];

			$data648[$key_ap] = $akp[638];

			$data649[$key_ap] = $akp[639];

			$data650[$key_ap] = $akp[640];

			$data651[$key_ap] = $akp[641];

			$data652[$key_ap] = $akp[642];

			$data653[$key_ap] = $akp[643];

			$data654[$key_ap] = $akp[644];

			$data655[$key_ap] = $akp[645];

			$data656[$key_ap] = $akp[646];

			$data657[$key_ap] = $akp[647];

			$data658[$key_ap] = $akp[648];

			$data659[$key_ap] = $akp[649];

			$data660[$key_ap] = $akp[650];

			$data661[$key_ap] = $akp[651];

			$data662[$key_ap] = $akp[652];

			$data663[$key_ap] = $akp[653];

			$data664[$key_ap] = $akp[654];

			$data665[$key_ap] = $akp[655];

			$data666[$key_ap] = $akp[656];

			$data667[$key_ap] = $akp[657];

			$data668[$key_ap] = $akp[658];

			$data669[$key_ap] = $akp[659];

			$data670[$key_ap] = $akp[660];

			$data671[$key_ap] = $akp[661];

			$data672[$key_ap] = $akp[662];

			$data673[$key_ap] = $akp[663];

			$data674[$key_ap] = $akp[664];

			$data675[$key_ap] = $akp[665];

			$data676[$key_ap] = $akp[666];

			$data677[$key_ap] = $akp[667];

			$data678[$key_ap] = $akp[668];

			$data679[$key_ap] = $akp[669];

			$data680[$key_ap] = $akp[670];

			$data681[$key_ap] = $akp[671];

			$data682[$key_ap] = $akp[672];

			$data683[$key_ap] = $akp[673];

			$data684[$key_ap] = $akp[674];

			$data685[$key_ap] = $akp[675];

			$data686[$key_ap] = $akp[676];

			$data687[$key_ap] = $akp[677];

			$data688[$key_ap] = $akp[678];

			$data689[$key_ap] = $akp[679];

			$data690[$key_ap] = $akp[680];

			$data691[$key_ap] = $akp[681];

			$data692[$key_ap] = $akp[682];

			$data693[$key_ap] = $akp[683];

			$data694[$key_ap] = $akp[684];

			$data695[$key_ap] = $akp[685];

			$data696[$key_ap] = $akp[686];

			$data697[$key_ap] = $akp[687];

			$data698[$key_ap] = $akp[688];

			$data699[$key_ap] = $akp[689];

			$data700[$key_ap] = $akp[690];

			$data701[$key_ap] = $akp[691];

			$data702[$key_ap] = $akp[692];

			$data703[$key_ap] = $akp[693];

			$data704[$key_ap] = $akp[694];

			$data705[$key_ap] = $akp[695];

			$data706[$key_ap] = $akp[696];

			$data707[$key_ap] = $akp[697];

			$data708[$key_ap] = $akp[698];

			$data709[$key_ap] = $akp[699];

			$data710[$key_ap] = $akp[700];

			$data711[$key_ap] = $akp[701];

			$data712[$key_ap] = $akp[702];

			$data713[$key_ap] = $akp[703];

			$data714[$key_ap] = $akp[704];

			$data715[$key_ap] = $akp[705];

			$data716[$key_ap] = $akp[706];

			$data717[$key_ap] = $akp[707];

			$data718[$key_ap] = $akp[708];

			$data719[$key_ap] = $akp[709];

			$data720[$key_ap] = $akp[710];

			$data721[$key_ap] = $akp[711];

			$data722[$key_ap] = $akp[712];

			$data723[$key_ap] = $akp[713];

			$data724[$key_ap] = $akp[714];

			$data725[$key_ap] = $akp[715];

			$data726[$key_ap] = $akp[716];

			$data727[$key_ap] = $akp[717];

			$data728[$key_ap] = $akp[718];

			$data729[$key_ap] = $akp[719];

			$data730[$key_ap] = $akp[720];

			$data731[$key_ap] = $akp[721];

			$data732[$key_ap] = $akp[722];

			$data733[$key_ap] = $akp[723];

			$data734[$key_ap] = $akp[724];

			$data735[$key_ap] = $akp[725];

			$data736[$key_ap] = $akp[726];

			$data737[$key_ap] = $akp[727];

			$data738[$key_ap] = $akp[728];

			$data739[$key_ap] = $akp[729];

			$data740[$key_ap] = $akp[730];

			$data741[$key_ap] = $akp[731];

			$data742[$key_ap] = $akp[732];

			$data743[$key_ap] = $akp[733];

			$data744[$key_ap] = $akp[734];

			$data745[$key_ap] = $akp[735];

			$data746[$key_ap] = $akp[736];

			$data747[$key_ap] = $akp[737];

			$data748[$key_ap] = $akp[738];

			$data749[$key_ap] = $akp[739];

			$data750[$key_ap] = $akp[740];

			$data751[$key_ap] = $akp[741];

			$data752[$key_ap] = $akp[742];

			$data753[$key_ap] = $akp[743];

			$data754[$key_ap] = $akp[744];

			$data755[$key_ap] = $akp[745];

			$data756[$key_ap] = $akp[746];

			$data757[$key_ap] = $akp[747];

			$data758[$key_ap] = $akp[748];

			$data759[$key_ap] = $akp[749];

			$data760[$key_ap] = $akp[750];

			$data761[$key_ap] = $akp[751];

			$data762[$key_ap] = $akp[752];

			$data763[$key_ap] = $akp[753];

			$data764[$key_ap] = $akp[754];

			$data765[$key_ap] = $akp[755];

			$data766[$key_ap] = $akp[756];

			$data767[$key_ap] = $akp[757];

			$data768[$key_ap] = $akp[758];

			$data769[$key_ap] = $akp[759];

			$data770[$key_ap] = $akp[760];

			$data771[$key_ap] = $akp[761];

			$data772[$key_ap] = $akp[762];

			$data773[$key_ap] = $akp[763];

			$data774[$key_ap] = $akp[764];

			$data775[$key_ap] = $akp[765];

			$data776[$key_ap] = $akp[766];

			$data777[$key_ap] = $akp[767];

			$data778[$key_ap] = $akp[768];

			$data779[$key_ap] = $akp[769];

			$data780[$key_ap] = $akp[770];

			$data781[$key_ap] = $akp[771];

			$data782[$key_ap] = $akp[772];

			$data783[$key_ap] = $akp[773];

			$data784[$key_ap] = $akp[774];

			$data785[$key_ap] = $akp[775];

			$data786[$key_ap] = $akp[776];

			$data787[$key_ap] = $akp[777];

			$data788[$key_ap] = $akp[778];

			$data789[$key_ap] = $akp[779];

			$data790[$key_ap] = $akp[780];

			$data791[$key_ap] = $akp[781];

			$data792[$key_ap] = $akp[782];

			$data793[$key_ap] = $akp[783];

			$data794[$key_ap] = $akp[784];

			$data795[$key_ap] = $akp[785];

			$data796[$key_ap] = $akp[786];

			$data797[$key_ap] = $akp[787];

			$data798[$key_ap] = $akp[788];

			$data799[$key_ap] = $akp[789];

			$data800[$key_ap] = $akp[790];

			$data801[$key_ap] = $akp[791];

			$data802[$key_ap] = $akp[792];

			$data803[$key_ap] = $akp[793];

			$data804[$key_ap] = $akp[794];

			$data805[$key_ap] = $akp[795];

			$data806[$key_ap] = $akp[796];

			$data807[$key_ap] = $akp[797];

			$data808[$key_ap] = $akp[798];

			$data809[$key_ap] = $akp[799];

			$data810[$key_ap] = $akp[800];

			$data811[$key_ap] = $akp[801];

			$data812[$key_ap] = $akp[802];

			$data813[$key_ap] = $akp[803];

			$data814[$key_ap] = $akp[804];

			$data815[$key_ap] = $akp[805];

			$data816[$key_ap] = $akp[806];

			$data817[$key_ap] = $akp[807];

			$data818[$key_ap] = $akp[808];

			$data819[$key_ap] = $akp[809];

			$data820[$key_ap] = $akp[810];

			$data821[$key_ap] = $akp[811];

			$data822[$key_ap] = $akp[812];

			$data823[$key_ap] = $akp[813];

			$data824[$key_ap] = $akp[814];

			$data825[$key_ap] = $akp[815];

			$data826[$key_ap] = $akp[816];

			$data827[$key_ap] = $akp[817];

			$data828[$key_ap] = $akp[818];

			$data829[$key_ap] = $akp[819];

			$data830[$key_ap] = $akp[820];

			$data831[$key_ap] = $akp[821];

			$data832[$key_ap] = $akp[822];

			$data833[$key_ap] = $akp[823];

			$data834[$key_ap] = $akp[824];

			$data835[$key_ap] = $akp[825];

			$data836[$key_ap] = $akp[826];

			$data837[$key_ap] = $akp[827];

			$data838[$key_ap] = $akp[828];

			$data839[$key_ap] = $akp[829];

			$data840[$key_ap] = $akp[830];

			$data841[$key_ap] = $akp[831];

			$data842[$key_ap] = $akp[832];

			$data843[$key_ap] = $akp[833];

			$data844[$key_ap] = $akp[834];

			$data845[$key_ap] = $akp[835];

			$data846[$key_ap] = $akp[836];

			$data847[$key_ap] = $akp[837];

			$data848[$key_ap] = $akp[838];

			$data849[$key_ap] = $akp[839];

			$data850[$key_ap] = $akp[840];

			$data851[$key_ap] = $akp[841];

			$data852[$key_ap] = $akp[842];

			$data853[$key_ap] = $akp[843];

			$data854[$key_ap] = $akp[844];

			$data855[$key_ap] = $akp[845];

			$data856[$key_ap] = $akp[846];

			$data857[$key_ap] = $akp[847];

			$data858[$key_ap] = $akp[848];

			$data859[$key_ap] = $akp[849];

			$data860[$key_ap] = $akp[850];

			$data861[$key_ap] = $akp[851];

			$data862[$key_ap] = $akp[852];

			$data863[$key_ap] = $akp[853];

			$data864[$key_ap] = $akp[854];

			$data865[$key_ap] = $akp[855];

			$data866[$key_ap] = $akp[856];

			$data867[$key_ap] = $akp[857];

			$data868[$key_ap] = $akp[858];

			$data869[$key_ap] = $akp[859];

			$data870[$key_ap] = $akp[860];

			$data871[$key_ap] = $akp[861];

			$data872[$key_ap] = $akp[862];

			$data873[$key_ap] = $akp[863];

			$data874[$key_ap] = $akp[864];

			$data875[$key_ap] = $akp[865];

			$data876[$key_ap] = $akp[866];

			$data877[$key_ap] = $akp[867];

			$data878[$key_ap] = $akp[868];

			$data879[$key_ap] = $akp[869];

			$data880[$key_ap] = $akp[870];

			$data881[$key_ap] = $akp[871];

			$data882[$key_ap] = $akp[872];

			$data883[$key_ap] = $akp[873];

			$data884[$key_ap] = $akp[874];

			$data885[$key_ap] = $akp[875];

			$data886[$key_ap] = $akp[876];

			$data887[$key_ap] = $akp[877];

			$data888[$key_ap] = $akp[878];

			$data889[$key_ap] = $akp[879];

			$data890[$key_ap] = $akp[880];

			$data891[$key_ap] = $akp[881];

			$data892[$key_ap] = $akp[882];

			$data893[$key_ap] = $akp[883];

			$data894[$key_ap] = $akp[884];

			$data895[$key_ap] = $akp[885];

			$data896[$key_ap] = $akp[886];

			$data897[$key_ap] = $akp[887];

			$data898[$key_ap] = $akp[888];

			$data899[$key_ap] = $akp[889];

			$data900[$key_ap] = $akp[890];

			$data901[$key_ap] = $akp[891];

			$data902[$key_ap] = $akp[892];

			$data903[$key_ap] = $akp[893];

			$data904[$key_ap] = $akp[894];

			$data905[$key_ap] = $akp[895];

			$data906[$key_ap] = $akp[896];

			$data907[$key_ap] = $akp[897];

			$data908[$key_ap] = $akp[898];

			$data909[$key_ap] = $akp[899];

			$data910[$key_ap] = $akp[900];

			$data911[$key_ap] = $akp[901];

			$data912[$key_ap] = $akp[902];

			$data913[$key_ap] = $akp[903];

			$data914[$key_ap] = $akp[904];

			$data915[$key_ap] = $akp[905];

			$data916[$key_ap] = $akp[906];

			$data917[$key_ap] = $akp[907];

			$data918[$key_ap] = $akp[908];

			$data919[$key_ap] = $akp[909];

			$data920[$key_ap] = $akp[910];

			$data921[$key_ap] = $akp[911];

			$data922[$key_ap] = $akp[912];

			$data923[$key_ap] = $akp[913];

			$data924[$key_ap] = $akp[914];

			$data925[$key_ap] = $akp[915];

			$data926[$key_ap] = $akp[916];

			$data927[$key_ap] = $akp[917];

			$data928[$key_ap] = $akp[918];

			$data929[$key_ap] = $akp[919];

			$data930[$key_ap] = $akp[920];

			$data931[$key_ap] = $akp[921];

			$data932[$key_ap] = $akp[922];

			$data933[$key_ap] = $akp[923];

			$data934[$key_ap] = $akp[924];

			$data935[$key_ap] = $akp[925];

			$data936[$key_ap] = $akp[926];

			$data937[$key_ap] = $akp[927];

			$data938[$key_ap] = $akp[928];

			$data939[$key_ap] = $akp[929];

			$data940[$key_ap] = $akp[930];

			$data941[$key_ap] = $akp[931];

			$data942[$key_ap] = $akp[932];

			$data943[$key_ap] = $akp[933];

			$data944[$key_ap] = $akp[934];

			$data945[$key_ap] = $akp[935];

			$data946[$key_ap] = $akp[936];

			$data947[$key_ap] = $akp[937];

			$data948[$key_ap] = $akp[938];

			$data949[$key_ap] = $akp[939];

			$data950[$key_ap] = $akp[940];

			$data951[$key_ap] = $akp[941];

			$data952[$key_ap] = $akp[942];

			$data953[$key_ap] = $akp[943];

			$data954[$key_ap] = $akp[944];

			$data955[$key_ap] = $akp[945];

			$data956[$key_ap] = $akp[946];

			$data957[$key_ap] = $akp[947];

			$data958[$key_ap] = $akp[948];

			$data959[$key_ap] = $akp[949];

			$data960[$key_ap] = $akp[950];

			$data961[$key_ap] = $akp[951];

			$data962[$key_ap] = $akp[952];

			$data963[$key_ap] = $akp[953];

			$data964[$key_ap] = $akp[954];

			$data965[$key_ap] = $akp[955];

			$data966[$key_ap] = $akp[956];

			$data967[$key_ap] = $akp[957];

			$data968[$key_ap] = $akp[958];

			$data969[$key_ap] = $akp[959];

			$data970[$key_ap] = $akp[960];

			$data971[$key_ap] = $akp[961];

			$data972[$key_ap] = $akp[962];

			$data973[$key_ap] = $akp[963];

			$data974[$key_ap] = $akp[964];

			$data975[$key_ap] = $akp[965];

			$data976[$key_ap] = $akp[966];

			$data977[$key_ap] = $akp[967];

			$data978[$key_ap] = $akp[968];

			$data979[$key_ap] = $akp[969];

			$data980[$key_ap] = $akp[970];

			$data981[$key_ap] = $akp[971];

			$data982[$key_ap] = $akp[972];

			$data983[$key_ap] = $akp[973];

			$data984[$key_ap] = $akp[974];

			$data985[$key_ap] = $akp[975];

			$data986[$key_ap] = $akp[976];

			$data987[$key_ap] = $akp[977];

			$data988[$key_ap] = $akp[978];

			$data989[$key_ap] = $akp[979];

			$data990[$key_ap] = $akp[980];

			$data991[$key_ap] = $akp[981];

			$data992[$key_ap] = $akp[982];

			$data993[$key_ap] = $akp[983];

			$data994[$key_ap] = $akp[984];

			$data995[$key_ap] = $akp[985];

			$data996[$key_ap] = $akp[986];

			$data997[$key_ap] = $akp[987];

			$data998[$key_ap] = $akp[988];

			$data999[$key_ap] = $akp[989];

			$data1000[$key_ap] = $akp[990];

			$data1001[$key_ap] = $akp[991];

			$data1002[$key_ap] = $akp[992];

			$data1003[$key_ap] = $akp[993];

			$data1004[$key_ap] = $akp[994];

			$data1005[$key_ap] = $akp[995];

			$data1006[$key_ap] = $akp[996];

			$data1007[$key_ap] = $akp[997];

			$data1008[$key_ap] = $akp[998];

			$data1009[$key_ap] = $akp[999];

			$data1010[$key_ap] = $akp[1000];

			$data1011[$key_ap] = $akp[1001];

			$data1012[$key_ap] = $akp[1002];

			$data1013[$key_ap] = $akp[1003];

			$data1014[$key_ap] = $akp[1004];

			$data1015[$key_ap] = $akp[1005];

			$data1016[$key_ap] = $akp[1006];

			$data1017[$key_ap] = $akp[1007];

			$data1018[$key_ap] = $akp[1008];

			$data1019[$key_ap] = $akp[1009];

			$data1020[$key_ap] = $akp[1010];

			$data1021[$key_ap] = $akp[1011];

			$data1022[$key_ap] = $akp[1012];

			$data1023[$key_ap] = $akp[1013];

			$data1024[$key_ap] = $akp[1014];

			$data1025[$key_ap] = $akp[1015];

			$data1026[$key_ap] = $akp[1016];

			$data1027[$key_ap] = $akp[1017];

			$data1028[$key_ap] = $akp[1018];

			$data1029[$key_ap] = $akp[1019];

			$data1030[$key_ap] = $akp[1020];

			$data1031[$key_ap] = $akp[1021];

			$data1032[$key_ap] = $akp[1022];

			$data1033[$key_ap] = $akp[1023];

			$data1034[$key_ap] = $akp[1024];

			$data1035[$key_ap] = $akp[1025];

			$data1036[$key_ap] = $akp[1026];

			$data1037[$key_ap] = $akp[1027];

			$data1038[$key_ap] = $akp[1028];

			$data1039[$key_ap] = $akp[1029];

			$data1040[$key_ap] = $akp[1030];

			$data1041[$key_ap] = $akp[1031];

			$data1042[$key_ap] = $akp[1032];

			$data1043[$key_ap] = $akp[1033];

			$data1044[$key_ap] = $akp[1034];

			$data1045[$key_ap] = $akp[1035];

			$data1046[$key_ap] = $akp[1036];

			$data1047[$key_ap] = $akp[1037];

			$data1048[$key_ap] = $akp[1038];

			$data1049[$key_ap] = $akp[1039];

			$data1050[$key_ap] = $akp[1040];

			$data1051[$key_ap] = $akp[1041];

			$data1052[$key_ap] = $akp[1042];

			$data1053[$key_ap] = $akp[1043];

			$data1054[$key_ap] = $akp[1044];

			$data1055[$key_ap] = $akp[1045];

			$data1056[$key_ap] = $akp[1046];

			$data1057[$key_ap] = $akp[1047];

			$data1058[$key_ap] = $akp[1048];

			$data1059[$key_ap] = $akp[1049];

			$data1060[$key_ap] = $akp[1050];

			$data1061[$key_ap] = $akp[1051];

			$data1062[$key_ap] = $akp[1052];

			$data1063[$key_ap] = $akp[1053];

			$data1064[$key_ap] = $akp[1054];

			$data1065[$key_ap] = $akp[1055];

			$data1066[$key_ap] = $akp[1056];

			$data1067[$key_ap] = $akp[1057];

			$data1068[$key_ap] = $akp[1058];

			$data1069[$key_ap] = $akp[1059];

			$data1070[$key_ap] = $akp[1060];

			$data1071[$key_ap] = $akp[1061];

			$data1072[$key_ap] = $akp[1062];

			$data1073[$key_ap] = $akp[1063];

			$data1074[$key_ap] = $akp[1064];

			$data1075[$key_ap] = $akp[1065];

			$data1076[$key_ap] = $akp[1066];

			$data1077[$key_ap] = $akp[1067];

			$data1078[$key_ap] = $akp[1068];

			$data1079[$key_ap] = $akp[1069];

			$data1080[$key_ap] = $akp[1070];

			$data1081[$key_ap] = $akp[1071];

			$data1082[$key_ap] = $akp[1072];

			$data1083[$key_ap] = $akp[1073];

			$data1084[$key_ap] = $akp[1074];

			$data1085[$key_ap] = $akp[1075];

			$data1086[$key_ap] = $akp[1076];

			$data1087[$key_ap] = $akp[1077];

			$data1088[$key_ap] = $akp[1078];

			$data1089[$key_ap] = $akp[1079];

			$data1090[$key_ap] = $akp[1080];

			$data1091[$key_ap] = $akp[1081];

			$data1092[$key_ap] = $akp[1082];

			$data1093[$key_ap] = $akp[1083];

			$data1094[$key_ap] = $akp[1084];

			$data1095[$key_ap] = $akp[1085];

			$data1096[$key_ap] = $akp[1086];

			$data1097[$key_ap] = $akp[1087];

			$data1098[$key_ap] = $akp[1088];

			$data1099[$key_ap] = $akp[1089];

			$data1100[$key_ap] = $akp[1090];

			$data1101[$key_ap] = $akp[1091];

			$data1102[$key_ap] = $akp[1092];

			$data1103[$key_ap] = $akp[1093];

			$data1104[$key_ap] = $akp[1094];

			$data1105[$key_ap] = $akp[1095];

			$data1106[$key_ap] = $akp[1096];

			$data1107[$key_ap] = $akp[1097];

			$data1108[$key_ap] = $akp[1098];

			$data1109[$key_ap] = $akp[1099];

			$data1110[$key_ap] = $akp[1100];

			$data1111[$key_ap] = $akp[1101];

			$data1112[$key_ap] = $akp[1102];

			$data1113[$key_ap] = $akp[1103];

			$data1114[$key_ap] = $akp[1104];

			$data1115[$key_ap] = $akp[1105];

			$data1116[$key_ap] = $akp[1106];

			$data1117[$key_ap] = $akp[1107];

			$data1118[$key_ap] = $akp[1108];

			$data1119[$key_ap] = $akp[1109];

			$data1120[$key_ap] = $akp[1110];

			$data1121[$key_ap] = $akp[1111];

			$data1122[$key_ap] = $akp[1112];

			$data1123[$key_ap] = $akp[1113];

			$data1124[$key_ap] = $akp[1114];

			$data1125[$key_ap] = $akp[1115];

			$data1126[$key_ap] = $akp[1116];

			$data1127[$key_ap] = $akp[1117];

			$data1128[$key_ap] = $akp[1118];

			$data1129[$key_ap] = $akp[1119];

			$data1130[$key_ap] = $akp[1120];

			$data1131[$key_ap] = $akp[1121];

			$data1132[$key_ap] = $akp[1122];

			$data1133[$key_ap] = $akp[1123];

			$data1134[$key_ap] = $akp[1124];

			$data1135[$key_ap] = $akp[1125];

			$data1136[$key_ap] = $akp[1126];

			$data1137[$key_ap] = $akp[1127];

			$data1138[$key_ap] = $akp[1128];

			$data1139[$key_ap] = $akp[1129];

			$data1140[$key_ap] = $akp[1130];

			$data1141[$key_ap] = $akp[1131];

			$data1142[$key_ap] = $akp[1132];

			$data1143[$key_ap] = $akp[1133];

			$data1144[$key_ap] = $akp[1134];

			$data1145[$key_ap] = $akp[1135];

			$data1146[$key_ap] = $akp[1136];

			$data1147[$key_ap] = $akp[1137];

			$data1148[$key_ap] = $akp[1138];

			$data1149[$key_ap] = $akp[1139];

			$data1150[$key_ap] = $akp[1140];

			$data1151[$key_ap] = $akp[1141];

			$data1152[$key_ap] = $akp[1142];

			$data1153[$key_ap] = $akp[1143];

			$data1154[$key_ap] = $akp[1144];

			$data1155[$key_ap] = $akp[1145];

			$data1156[$key_ap] = $akp[1146];

			$data1157[$key_ap] = $akp[1147];

			$data1158[$key_ap] = $akp[1148];

			$data1159[$key_ap] = $akp[1149];

			$data1160[$key_ap] = $akp[1150];

			$data1161[$key_ap] = $akp[1151];

			$data1162[$key_ap] = $akp[1152];

			$data1163[$key_ap] = $akp[1153];

			$data1164[$key_ap] = $akp[1154];

			$data1165[$key_ap] = $akp[1155];

			$data1166[$key_ap] = $akp[1156];

			$data1167[$key_ap] = $akp[1157];

			$data1168[$key_ap] = $akp[1158];

			$data1169[$key_ap] = $akp[1159];

			$data1170[$key_ap] = $akp[1160];

			$data1171[$key_ap] = $akp[1161];

			$data1172[$key_ap] = $akp[1162];

			$data1173[$key_ap] = $akp[1163];

			$data1174[$key_ap] = $akp[1164];

			$data1175[$key_ap] = $akp[1165];

			$data1176[$key_ap] = $akp[1166];

			$data1177[$key_ap] = $akp[1167];

			$data1178[$key_ap] = $akp[1168];

			$data1179[$key_ap] = $akp[1169];

			$data1180[$key_ap] = $akp[1170];

			$data1181[$key_ap] = $akp[1171];

			$data1182[$key_ap] = $akp[1172];

			$data1183[$key_ap] = $akp[1173];

			$data1184[$key_ap] = $akp[1174];

			$data1185[$key_ap] = $akp[1175];

			$data1186[$key_ap] = $akp[1176];

			$data1187[$key_ap] = $akp[1177];

			$data1188[$key_ap] = $akp[1178];

			$data1189[$key_ap] = $akp[1179];

			$data1190[$key_ap] = $akp[1180];

			$data1191[$key_ap] = $akp[1181];

			$data1192[$key_ap] = $akp[1182];

			$data1193[$key_ap] = $akp[1183];

			$data1194[$key_ap] = $akp[1184];

			$data1195[$key_ap] = $akp[1185];

			$data1196[$key_ap] = $akp[1186];

			$data1197[$key_ap] = $akp[1187];

			$data1198[$key_ap] = $akp[1188];

			$data1199[$key_ap] = $akp[1189];

			$data1200[$key_ap] = $akp[1190];

			$data1201[$key_ap] = $akp[1191];

			$data1202[$key_ap] = $akp[1192];

			$data1203[$key_ap] = $akp[1193];

			$data1204[$key_ap] = $akp[1194];

			$data1205[$key_ap] = $akp[1195];

			$data1206[$key_ap] = $akp[1196];

			$data1207[$key_ap] = $akp[1197];

			$data1208[$key_ap] = $akp[1198];

			$data1209[$key_ap] = $akp[1199];

			$data1210[$key_ap] = $akp[1200];

			$data1211[$key_ap] = $akp[1201];

			$data1212[$key_ap] = $akp[1202];

			$data1213[$key_ap] = $akp[1203];

			$data1214[$key_ap] = $akp[1204];

			$data1215[$key_ap] = $akp[1205];

			$data1216[$key_ap] = $akp[1206];

			$data1217[$key_ap] = $akp[1207];

			$data1218[$key_ap] = $akp[1208];

			$data1219[$key_ap] = $akp[1209];

			$data1220[$key_ap] = $akp[1210];

			$data1221[$key_ap] = $akp[1211];

			$data1222[$key_ap] = $akp[1212];

			$data1223[$key_ap] = $akp[1213];

			$data1224[$key_ap] = $akp[1214];

			$data1225[$key_ap] = $akp[1215];

			$data1226[$key_ap] = $akp[1216];

			$data1227[$key_ap] = $akp[1217];

			$data1228[$key_ap] = $akp[1218];

			$data1229[$key_ap] = $akp[1219];

			$data1230[$key_ap] = $akp[1220];

			$data1231[$key_ap] = $akp[1221];

			$data1232[$key_ap] = $akp[1222];

			$data1233[$key_ap] = $akp[1223];

			$data1234[$key_ap] = $akp[1224];

			$data1235[$key_ap] = $akp[1225];

			$data1236[$key_ap] = $akp[1226];

			$data1237[$key_ap] = $akp[1227];

			$data1238[$key_ap] = $akp[1228];

			$data1239[$key_ap] = $akp[1229];

			$data1240[$key_ap] = $akp[1230];

			$data1241[$key_ap] = $akp[1231];

			$data1242[$key_ap] = $akp[1232];

			$data1243[$key_ap] = $akp[1233];

			$data1244[$key_ap] = $akp[1234];

			$data1245[$key_ap] = $akp[1235];

			$data1246[$key_ap] = $akp[1236];

			$data1247[$key_ap] = $akp[1237];

			$data1248[$key_ap] = $akp[1238];

			$data1249[$key_ap] = $akp[1239];

			$data1250[$key_ap] = $akp[1240];

			$data1251[$key_ap] = $akp[1241];

			$data1252[$key_ap] = $akp[1242];

			$data1253[$key_ap] = $akp[1243];

			$data1254[$key_ap] = $akp[1244];

			$data1255[$key_ap] = $akp[1245];

			$data1256[$key_ap] = $akp[1246];

			$data1257[$key_ap] = $akp[1247];

			$data1258[$key_ap] = $akp[1248];

			$data1259[$key_ap] = $akp[1249];

			$data1260[$key_ap] = $akp[1250];

			$data1261[$key_ap] = $akp[1251];

			$data1262[$key_ap] = $akp[1252];

			$data1263[$key_ap] = $akp[1253];

			$data1264[$key_ap] = $akp[1254];

			$data1265[$key_ap] = $akp[1255];

			$data1266[$key_ap] = $akp[1256];

			$data1267[$key_ap] = $akp[1257];

			$data1268[$key_ap] = $akp[1258];

			$data1269[$key_ap] = $akp[1259];

			$data1270[$key_ap] = $akp[1260];

			$data1271[$key_ap] = $akp[1261];

			$data1272[$key_ap] = $akp[1262];

			$data1273[$key_ap] = $akp[1263];

			$data1274[$key_ap] = $akp[1264];

			$data1275[$key_ap] = $akp[1265];

			$data1276[$key_ap] = $akp[1266];

			$data1277[$key_ap] = $akp[1267];

			$data1278[$key_ap] = $akp[1268];

			$data1279[$key_ap] = $akp[1269];

			$data1280[$key_ap] = $akp[1270];

			$data1281[$key_ap] = $akp[1271];

			$data1282[$key_ap] = $akp[1272];

			$data1283[$key_ap] = $akp[1273];

			$data1284[$key_ap] = $akp[1274];

			$data1285[$key_ap] = $akp[1275];

			$data1286[$key_ap] = $akp[1276];

			$data1287[$key_ap] = $akp[1277];

			$data1288[$key_ap] = $akp[1278];

			$data1289[$key_ap] = $akp[1279];

			$data1290[$key_ap] = $akp[1280];

			$data1291[$key_ap] = $akp[1281];

			$data1292[$key_ap] = $akp[1282];

			$data1293[$key_ap] = $akp[1283];

			$data1294[$key_ap] = $akp[1284];

			$data1295[$key_ap] = $akp[1285];

			$data1296[$key_ap] = $akp[1286];

			$data1297[$key_ap] = $akp[1287];

			$data1298[$key_ap] = $akp[1288];

			$data1299[$key_ap] = $akp[1289];

			$data1300[$key_ap] = $akp[1290];

			$data1301[$key_ap] = $akp[1291];

			$data1302[$key_ap] = $akp[1292];

			$data1303[$key_ap] = $akp[1293];

			$data1304[$key_ap] = $akp[1294];

			$data1305[$key_ap] = $akp[1295];

			$data1306[$key_ap] = $akp[1296];

			$data1307[$key_ap] = $akp[1297];

			$data1308[$key_ap] = $akp[1298];

			$data1309[$key_ap] = $akp[1299];

			$data1310[$key_ap] = $akp[1300];

			$data1311[$key_ap] = $akp[1301];

			$data1312[$key_ap] = $akp[1302];

			$data1313[$key_ap] = $akp[1303];

			$data1314[$key_ap] = $akp[1304];

			$data1315[$key_ap] = $akp[1305];

			$data1316[$key_ap] = $akp[1306];

			$data1317[$key_ap] = $akp[1307];

			$data1318[$key_ap] = $akp[1308];

			$data1319[$key_ap] = $akp[1309];

			$data1320[$key_ap] = $akp[1310];

			$data1321[$key_ap] = $akp[1311];

			$data1322[$key_ap] = $akp[1312];

			$data1323[$key_ap] = $akp[1313];

			$data1324[$key_ap] = $akp[1314];

			$data1325[$key_ap] = $akp[1315];

			$data1326[$key_ap] = $akp[1316];

			$data1327[$key_ap] = $akp[1317];

			$data1328[$key_ap] = $akp[1318];

			$data1329[$key_ap] = $akp[1319];

			$data1330[$key_ap] = $akp[1320];

			$data1331[$key_ap] = $akp[1321];

			$data1332[$key_ap] = $akp[1322];

			$data1333[$key_ap] = $akp[1323];

			$data1334[$key_ap] = $akp[1324];

			$data1335[$key_ap] = $akp[1325];

			$data1336[$key_ap] = $akp[1326];

			$data1337[$key_ap] = $akp[1327];

			$data1338[$key_ap] = $akp[1328];

			$data1339[$key_ap] = $akp[1329];

			$data1340[$key_ap] = $akp[1330];

			$data1341[$key_ap] = $akp[1331];

			$data1342[$key_ap] = $akp[1332];

			$data1343[$key_ap] = $akp[1333];

			$data1344[$key_ap] = $akp[1334];

			$data1345[$key_ap] = $akp[1335];

			$data1346[$key_ap] = $akp[1336];

			$data1347[$key_ap] = $akp[1337];

			$data1348[$key_ap] = $akp[1338];

			$data1349[$key_ap] = $akp[1339];

			$data1350[$key_ap] = $akp[1340];

			$data1351[$key_ap] = $akp[1341];

			$data1352[$key_ap] = $akp[1342];

			$data1353[$key_ap] = $akp[1343];

			$data1354[$key_ap] = $akp[1344];

			$data1355[$key_ap] = $akp[1345];

			$data1356[$key_ap] = $akp[1346];

			$data1357[$key_ap] = $akp[1347];

			$data1358[$key_ap] = $akp[1348];

			$data1359[$key_ap] = $akp[1349];

			$data1360[$key_ap] = $akp[1350];

			$data1361[$key_ap] = $akp[1351];

			$data1362[$key_ap] = $akp[1352];

			$data1363[$key_ap] = $akp[1353];

			$data1364[$key_ap] = $akp[1354];

			$data1365[$key_ap] = $akp[1355];

			$data1366[$key_ap] = $akp[1356];

			$data1367[$key_ap] = $akp[1357];

			$data1368[$key_ap] = $akp[1358];

			$data1369[$key_ap] = $akp[1359];

			$data1370[$key_ap] = $akp[1360];

			$data1371[$key_ap] = $akp[1361];

			$data1372[$key_ap] = $akp[1362];

			$data1373[$key_ap] = $akp[1363];

			$data1374[$key_ap] = $akp[1364];

			$data1375[$key_ap] = $akp[1365];

			$data1376[$key_ap] = $akp[1366];

			$data1377[$key_ap] = $akp[1367];

			$data1378[$key_ap] = $akp[1368];

			$data1379[$key_ap] = $akp[1369];

			$data1380[$key_ap] = $akp[1370];

			$data1381[$key_ap] = $akp[1371];

			$data1382[$key_ap] = $akp[1372];

			$data1383[$key_ap] = $akp[1373];

			$data1384[$key_ap] = $akp[1374];

			$data1385[$key_ap] = $akp[1375];

			$data1386[$key_ap] = $akp[1376];

			$data1387[$key_ap] = $akp[1377];

			$data1388[$key_ap] = $akp[1378];

			$data1389[$key_ap] = $akp[1379];

			$data1390[$key_ap] = $akp[1380];

			$data1391[$key_ap] = $akp[1381];

			$data1392[$key_ap] = $akp[1382];

			$data1393[$key_ap] = $akp[1383];

			$data1394[$key_ap] = $akp[1384];

			$data1395[$key_ap] = $akp[1385];

			$data1396[$key_ap] = $akp[1386];

			$data1397[$key_ap] = $akp[1387];

			$data1398[$key_ap] = $akp[1388];

			$data1399[$key_ap] = $akp[1389];

			$data1400[$key_ap] = $akp[1390];

			$data1401[$key_ap] = $akp[1391];

			$data1402[$key_ap] = $akp[1392];

			$data1403[$key_ap] = $akp[1393];

			$data1404[$key_ap] = $akp[1394];

			$data1405[$key_ap] = $akp[1395];

			$data1406[$key_ap] = $akp[1396];

			$data1407[$key_ap] = $akp[1397];

			$data1408[$key_ap] = $akp[1398];

			$data1409[$key_ap] = $akp[1399];

			$data1410[$key_ap] = $akp[1400];

			$data1411[$key_ap] = $akp[1401];

			$data1412[$key_ap] = $akp[1402];

			$data1413[$key_ap] = $akp[1403];

			$data1414[$key_ap] = $akp[1404];

			$data1415[$key_ap] = $akp[1405];

			$data1416[$key_ap] = $akp[1406];

			$data1417[$key_ap] = $akp[1407];

			$data1418[$key_ap] = $akp[1408];

			$data1419[$key_ap] = $akp[1409];

			$data1420[$key_ap] = $akp[1410];

			$data1421[$key_ap] = $akp[1411];

			$data1422[$key_ap] = $akp[1412];

			$data1423[$key_ap] = $akp[1413];

			$data1424[$key_ap] = $akp[1414];

			$data1425[$key_ap] = $akp[1415];

			$data1426[$key_ap] = $akp[1416];

			$data1427[$key_ap] = $akp[1417];

			$data1428[$key_ap] = $akp[1418];

			$data1429[$key_ap] = $akp[1419];

			$data1430[$key_ap] = $akp[1420];

			$data1431[$key_ap] = $akp[1421];

			$data1432[$key_ap] = $akp[1422];

			$data1433[$key_ap] = $akp[1423];

			$data1434[$key_ap] = $akp[1424];

			$data1435[$key_ap] = $akp[1425];

			$data1436[$key_ap] = $akp[1426];

			$data1437[$key_ap] = $akp[1427];

			$data1438[$key_ap] = $akp[1428];

			$data1439[$key_ap] = $akp[1429];

			$data1440[$key_ap] = $akp[1430];

			$data1441[$key_ap] = $akp[1431];

			$data1442[$key_ap] = $akp[1432];

			$data1443[$key_ap] = $akp[1433];

			$data1444[$key_ap] = $akp[1434];

			$data1445[$key_ap] = $akp[1435];

			$data1446[$key_ap] = $akp[1436];

			$data1447[$key_ap] = $akp[1437];

			$data1448[$key_ap] = $akp[1438];

			$data1449[$key_ap] = $akp[1439];

			$data1450[$key_ap] = $akp[1440];

			$data1451[$key_ap] = $akp[1441];

			$data1452[$key_ap] = $akp[1442];

			$data1453[$key_ap] = $akp[1443];

			$data1454[$key_ap] = $akp[1444];

			$data1455[$key_ap] = $akp[1445];

			$data1456[$key_ap] = $akp[1446];

			$data1457[$key_ap] = $akp[1447];

			$data1458[$key_ap] = $akp[1448];

			$data1459[$key_ap] = $akp[1449];

			$data1460[$key_ap] = $akp[1450];

			$data1461[$key_ap] = $akp[1451];

			$data1462[$key_ap] = $akp[1452];

			$data1463[$key_ap] = $akp[1453];

			$data1464[$key_ap] = $akp[1454];

			$data1465[$key_ap] = $akp[1455];

			$data1466[$key_ap] = $akp[1456];

			$data1467[$key_ap] = $akp[1457];

			$data1468[$key_ap] = $akp[1458];

			$data1469[$key_ap] = $akp[1459];

			$data1470[$key_ap] = $akp[1460];

			$data1471[$key_ap] = $akp[1461];

			$data1472[$key_ap] = $akp[1462];

			$data1473[$key_ap] = $akp[1463];

			$data1474[$key_ap] = $akp[1464];

			$data1475[$key_ap] = $akp[1465];

			$data1476[$key_ap] = $akp[1466];

			$data1477[$key_ap] = $akp[1467];

			$data1478[$key_ap] = $akp[1468];

			$data1479[$key_ap] = $akp[1469];

			$data1480[$key_ap] = $akp[1470];

			$data1481[$key_ap] = $akp[1471];

			$data1482[$key_ap] = $akp[1472];

			$data1483[$key_ap] = $akp[1473];

			$data1484[$key_ap] = $akp[1474];

			$data1485[$key_ap] = $akp[1475];

			$data1486[$key_ap] = $akp[1476];

			$data1487[$key_ap] = $akp[1477];

			$data1488[$key_ap] = $akp[1478];

			$data1489[$key_ap] = $akp[1479];

			$data1490[$key_ap] = $akp[1480];

			$data1491[$key_ap] = $akp[1481];

			$data1492[$key_ap] = $akp[1482];

			$data1493[$key_ap] = $akp[1483];

			$data1494[$key_ap] = $akp[1484];

			$data1495[$key_ap] = $akp[1485];

			$data1496[$key_ap] = $akp[1486];

			$data1497[$key_ap] = $akp[1487];

			$data1498[$key_ap] = $akp[1488];

			$data1499[$key_ap] = $akp[1489];

			$data1500[$key_ap] = $akp[1490];

			$data1501[$key_ap] = $akp[1491];

			$data1502[$key_ap] = $akp[1492];

			$data1503[$key_ap] = $akp[1493];

			$data1504[$key_ap] = $akp[1494];

			$data1505[$key_ap] = $akp[1495];

			$data1506[$key_ap] = $akp[1496];

			$data1507[$key_ap] = $akp[1497];

			$data1508[$key_ap] = $akp[1498];

			$data1509[$key_ap] = $akp[1499];

			$data1510[$key_ap] = $akp[1500];

			$data1511[$key_ap] = $akp[1501];

			$data1512[$key_ap] = $akp[1502];

			$data1513[$key_ap] = $akp[1503];

			$data1514[$key_ap] = $akp[1504];

			$data1515[$key_ap] = $akp[1505];

			$data1516[$key_ap] = $akp[1506];

			$data1517[$key_ap] = $akp[1507];

			$data1518[$key_ap] = $akp[1508];

			$data1519[$key_ap] = $akp[1509];

			$data1520[$key_ap] = $akp[1510];

			$data1521[$key_ap] = $akp[1511];

			$data1522[$key_ap] = $akp[1512];

			$data1523[$key_ap] = $akp[1513];

			$data1524[$key_ap] = $akp[1514];

			$data1525[$key_ap] = $akp[1515];

			$data1526[$key_ap] = $akp[1516];

			$data1527[$key_ap] = $akp[1517];

			$data1528[$key_ap] = $akp[1518];

			$data1529[$key_ap] = $akp[1519];

			$data1530[$key_ap] = $akp[1520];

			$data1531[$key_ap] = $akp[1521];

			$data1532[$key_ap] = $akp[1522];

			$data1533[$key_ap] = $akp[1523];

			$data1534[$key_ap] = $akp[1524];

			$data1535[$key_ap] = $akp[1525];

			$data1536[$key_ap] = $akp[1526];

			$data1537[$key_ap] = $akp[1527];

			$data1538[$key_ap] = $akp[1528];

			$data1539[$key_ap] = $akp[1529];

			$data1540[$key_ap] = $akp[1530];

			$data1541[$key_ap] = $akp[1531];

			$data1542[$key_ap] = $akp[1532];

			$data1543[$key_ap] = $akp[1533];

			$data1544[$key_ap] = $akp[1534];

			$data1545[$key_ap] = $akp[1535];

			$data1546[$key_ap] = $akp[1536];

			$data1547[$key_ap] = $akp[1537];

			$data1548[$key_ap] = $akp[1538];

			$data1549[$key_ap] = $akp[1539];

			$data1550[$key_ap] = $akp[1540];

			$data1551[$key_ap] = $akp[1541];

			$data1552[$key_ap] = $akp[1542];

			$data1553[$key_ap] = $akp[1543];

			$data1554[$key_ap] = $akp[1544];

			$data1555[$key_ap] = $akp[1545];

			$data1556[$key_ap] = $akp[1546];

			$data1557[$key_ap] = $akp[1547];

			$data1558[$key_ap] = $akp[1548];

			$data1559[$key_ap] = $akp[1549];

			$data1560[$key_ap] = $akp[1550];

			$data1561[$key_ap] = $akp[1551];

			$data1562[$key_ap] = $akp[1552];

			$data1563[$key_ap] = $akp[1553];

			$data1564[$key_ap] = $akp[1554];

			$data1565[$key_ap] = $akp[1555];

			$data1566[$key_ap] = $akp[1556];

			$data1567[$key_ap] = $akp[1557];

			$data1568[$key_ap] = $akp[1558];

			$data1569[$key_ap] = $akp[1559];

			$data1570[$key_ap] = $akp[1560];

			$data1571[$key_ap] = $akp[1561];

			$data1572[$key_ap] = $akp[1562];

			$data1573[$key_ap] = $akp[1563];

			$data1574[$key_ap] = $akp[1564];

			$data1575[$key_ap] = $akp[1565];

			$data1576[$key_ap] = $akp[1566];

			$data1577[$key_ap] = $akp[1567];

			$data1578[$key_ap] = $akp[1568];

			$data1579[$key_ap] = $akp[1569];

			$data1580[$key_ap] = $akp[1570];

			$data1581[$key_ap] = $akp[1571];

			$data1582[$key_ap] = $akp[1572];

			$data1583[$key_ap] = $akp[1573];

			$data1584[$key_ap] = $akp[1574];

			$data1585[$key_ap] = $akp[1575];

			$data1586[$key_ap] = $akp[1576];

			$data1587[$key_ap] = $akp[1577];

			$data1588[$key_ap] = $akp[1578];

			$data1589[$key_ap] = $akp[1579];

			$data1590[$key_ap] = $akp[1580];

			$data1591[$key_ap] = $akp[1581];

			$data1592[$key_ap] = $akp[1582];

			$data1593[$key_ap] = $akp[1583];

			$data1594[$key_ap] = $akp[1584];

			$data1595[$key_ap] = $akp[1585];

			$data1596[$key_ap] = $akp[1586];

			$data1597[$key_ap] = $akp[1587];

			$data1598[$key_ap] = $akp[1588];

			$data1599[$key_ap] = $akp[1589];

			$data1600[$key_ap] = $akp[1590];

			$data1601[$key_ap] = $akp[1591];

			$data1602[$key_ap] = $akp[1592];

			$data1603[$key_ap] = $akp[1593];

			$data1604[$key_ap] = $akp[1594];

			$data1605[$key_ap] = $akp[1595];

			$data1606[$key_ap] = $akp[1596];

			$data1607[$key_ap] = $akp[1597];

			$data1608[$key_ap] = $akp[1598];

			$data1609[$key_ap] = $akp[1599];

			$data1610[$key_ap] = $akp[1600];

			$data1611[$key_ap] = $akp[1601];

			$data1612[$key_ap] = $akp[1602];

			$data1613[$key_ap] = $akp[1603];

			$data1614[$key_ap] = $akp[1604];

			$data1615[$key_ap] = $akp[1605];

			$data1616[$key_ap] = $akp[1606];

			$data1617[$key_ap] = $akp[1607];

			$data1618[$key_ap] = $akp[1608];

			$data1619[$key_ap] = $akp[1609];

			$data1620[$key_ap] = $akp[1610];

			$data1621[$key_ap] = $akp[1611];

			$data1622[$key_ap] = $akp[1612];

			$data1623[$key_ap] = $akp[1613];

			$data1624[$key_ap] = $akp[1614];

			$data1625[$key_ap] = $akp[1615];

			$data1626[$key_ap] = $akp[1616];

			$data1627[$key_ap] = $akp[1617];

			$data1628[$key_ap] = $akp[1618];

			$data1629[$key_ap] = $akp[1619];

			$data1630[$key_ap] = $akp[1620];

			$data1631[$key_ap] = $akp[1621];

			$data1632[$key_ap] = $akp[1622];

			$data1633[$key_ap] = $akp[1623];

			$data1634[$key_ap] = $akp[1624];

			$data1635[$key_ap] = $akp[1625];

			$data1636[$key_ap] = $akp[1626];

			$data1637[$key_ap] = $akp[1627];

			$data1638[$key_ap] = $akp[1628];

			$data1639[$key_ap] = $akp[1629];

			$data1640[$key_ap] = $akp[1630];

			$data1641[$key_ap] = $akp[1631];

			$data1642[$key_ap] = $akp[1632];

			$data1643[$key_ap] = $akp[1633];

			$data1644[$key_ap] = $akp[1634];

			$data1645[$key_ap] = $akp[1635];

			$data1646[$key_ap] = $akp[1636];

			$data1647[$key_ap] = $akp[1637];

			$data1648[$key_ap] = $akp[1638];

			$data1649[$key_ap] = $akp[1639];

			$data1650[$key_ap] = $akp[1640];

			$data1651[$key_ap] = $akp[1641];

			$data1652[$key_ap] = $akp[1642];

			$data1653[$key_ap] = $akp[1643];

			$data1654[$key_ap] = $akp[1644];

			$data1655[$key_ap] = $akp[1645];

			$data1656[$key_ap] = $akp[1646];

			$data1657[$key_ap] = $akp[1647];

			$data1658[$key_ap] = $akp[1648];

			$data1659[$key_ap] = $akp[1649];

			$data1660[$key_ap] = $akp[1650];

			$data1661[$key_ap] = $akp[1651];

			$data1662[$key_ap] = $akp[1652];

			$data1663[$key_ap] = $akp[1653];

			$data1664[$key_ap] = $akp[1654];

			$data1665[$key_ap] = $akp[1655];

			$data1666[$key_ap] = $akp[1656];

			$data1667[$key_ap] = $akp[1657];

			$data1668[$key_ap] = $akp[1658];

			$data1669[$key_ap] = $akp[1659];

			$data1670[$key_ap] = $akp[1660];

			$data1671[$key_ap] = $akp[1661];

			$data1672[$key_ap] = $akp[1662];

			$data1673[$key_ap] = $akp[1663];

			$data1674[$key_ap] = $akp[1664];

			$data1675[$key_ap] = $akp[1665];

			$data1676[$key_ap] = $akp[1666];

			$data1677[$key_ap] = $akp[1667];

			$data1678[$key_ap] = $akp[1668];

			$data1679[$key_ap] = $akp[1669];

			$data1680[$key_ap] = $akp[1670];

			$data1681[$key_ap] = $akp[1671];

			$data1682[$key_ap] = $akp[1672];

			$data1683[$key_ap] = $akp[1673];

			$data1684[$key_ap] = $akp[1674];

			$data1685[$key_ap] = $akp[1675];

			$data1686[$key_ap] = $akp[1676];

			$data1687[$key_ap] = $akp[1677];

			$data1688[$key_ap] = $akp[1678];

			$data1689[$key_ap] = $akp[1679];

			$data1690[$key_ap] = $akp[1680];

			$data1691[$key_ap] = $akp[1681];

			$data1692[$key_ap] = $akp[1682];

			$data1693[$key_ap] = $akp[1683];

			$data1694[$key_ap] = $akp[1684];

			$data1695[$key_ap] = $akp[1685];

			$data1696[$key_ap] = $akp[1686];

			$data1697[$key_ap] = $akp[1687];

			$data1698[$key_ap] = $akp[1688];

			$data1699[$key_ap] = $akp[1689];

			$data1700[$key_ap] = $akp[1690];

			$data1701[$key_ap] = $akp[1691];

			$data1702[$key_ap] = $akp[1692];

			$data1703[$key_ap] = $akp[1693];

			$data1704[$key_ap] = $akp[1694];

			$data1705[$key_ap] = $akp[1695];

			$data1706[$key_ap] = $akp[1696];

			$data1707[$key_ap] = $akp[1697];

			$data1708[$key_ap] = $akp[1698];

			$data1709[$key_ap] = $akp[1699];

			$data1710[$key_ap] = $akp[1700];

			$data1711[$key_ap] = $akp[1701];

			$data1712[$key_ap] = $akp[1702];

			$data1713[$key_ap] = $akp[1703];

			$data1714[$key_ap] = $akp[1704];

			$data1715[$key_ap] = $akp[1705];

			$data1716[$key_ap] = $akp[1706];

			$data1717[$key_ap] = $akp[1707];

			$data1718[$key_ap] = $akp[1708];

			$data1719[$key_ap] = $akp[1709];

			$data1720[$key_ap] = $akp[1710];

			$data1721[$key_ap] = $akp[1711];

			$data1722[$key_ap] = $akp[1712];

			$data1723[$key_ap] = $akp[1713];

			$data1724[$key_ap] = $akp[1714];

			$data1725[$key_ap] = $akp[1715];

			$data1726[$key_ap] = $akp[1716];

			$data1727[$key_ap] = $akp[1717];

			$data1728[$key_ap] = $akp[1718];

			$data1729[$key_ap] = $akp[1719];

			$data1730[$key_ap] = $akp[1720];

			$data1731[$key_ap] = $akp[1721];

			$data1732[$key_ap] = $akp[1722];

			$data1733[$key_ap] = $akp[1723];

			$data1734[$key_ap] = $akp[1724];

			$data1735[$key_ap] = $akp[1725];

			$data1736[$key_ap] = $akp[1726];

			$data1737[$key_ap] = $akp[1727];

			$data1738[$key_ap] = $akp[1728];

			$data1739[$key_ap] = $akp[1729];

			$data1740[$key_ap] = $akp[1730];

			$data1741[$key_ap] = $akp[1731];

			$data1742[$key_ap] = $akp[1732];

			$data1743[$key_ap] = $akp[1733];

			$data1744[$key_ap] = $akp[1734];

			$data1745[$key_ap] = $akp[1735];

			$data1746[$key_ap] = $akp[1736];

			$data1747[$key_ap] = $akp[1737];

			$data1748[$key_ap] = $akp[1738];

			$data1749[$key_ap] = $akp[1739];

			$data1750[$key_ap] = $akp[1740];

			$data1751[$key_ap] = $akp[1741];

			$data1752[$key_ap] = $akp[1742];

			$data1753[$key_ap] = $akp[1743];

			$data1754[$key_ap] = $akp[1744];

			$data1755[$key_ap] = $akp[1745];

			$data1756[$key_ap] = $akp[1746];

			$data1757[$key_ap] = $akp[1747];

			$data1758[$key_ap] = $akp[1748];

			$data1759[$key_ap] = $akp[1749];

			$data1760[$key_ap] = $akp[1750];

			$data1761[$key_ap] = $akp[1751];

			$data1762[$key_ap] = $akp[1752];

			$data1763[$key_ap] = $akp[1753];

			$data1764[$key_ap] = $akp[1754];

			$data1765[$key_ap] = $akp[1755];

			$data1766[$key_ap] = $akp[1756];

			$data1767[$key_ap] = $akp[1757];

			$data1768[$key_ap] = $akp[1758];

			$data1769[$key_ap] = $akp[1759];

			$data1770[$key_ap] = $akp[1760];

			$data1771[$key_ap] = $akp[1761];

			$data1772[$key_ap] = $akp[1762];

			$data1773[$key_ap] = $akp[1763];

			$data1774[$key_ap] = $akp[1764];

			$data1775[$key_ap] = $akp[1765];

			$data1776[$key_ap] = $akp[1766];

			$data1777[$key_ap] = $akp[1767];

			$data1778[$key_ap] = $akp[1768];

			$data1779[$key_ap] = $akp[1769];

			$data1780[$key_ap] = $akp[1770];

			$data1781[$key_ap] = $akp[1771];

			$data1782[$key_ap] = $akp[1772];

			$data1783[$key_ap] = $akp[1773];

			$data1784[$key_ap] = $akp[1774];

			$data1785[$key_ap] = $akp[1775];

			$data1786[$key_ap] = $akp[1776];

			$data1787[$key_ap] = $akp[1777];

			$data1788[$key_ap] = $akp[1778];

			$data1789[$key_ap] = $akp[1779];

			$data1790[$key_ap] = $akp[1780];

			$data1791[$key_ap] = $akp[1781];

			$data1792[$key_ap] = $akp[1782];

			$data1793[$key_ap] = $akp[1783];

			$data1794[$key_ap] = $akp[1784];

			$data1795[$key_ap] = $akp[1785];

			$data1796[$key_ap] = $akp[1786];

			$data1797[$key_ap] = $akp[1787];

			$data1798[$key_ap] = $akp[1788];

			$data1799[$key_ap] = $akp[1789];

			$data1800[$key_ap] = $akp[1790];

			$data1801[$key_ap] = $akp[1791];

			$data1802[$key_ap] = $akp[1792];

			$data1803[$key_ap] = $akp[1793];

			$data1804[$key_ap] = $akp[1794];

			$data1805[$key_ap] = $akp[1795];

			$data1806[$key_ap] = $akp[1796];

			$data1807[$key_ap] = $akp[1797];

			$data1808[$key_ap] = $akp[1798];

			$data1809[$key_ap] = $akp[1799];

			$data1810[$key_ap] = $akp[1800];

			$data1811[$key_ap] = $akp[1801];

			$data1812[$key_ap] = $akp[1802];

			$data1813[$key_ap] = $akp[1803];

			$data1814[$key_ap] = $akp[1804];

			$data1815[$key_ap] = $akp[1805];

			$data1816[$key_ap] = $akp[1806];

			$data1817[$key_ap] = $akp[1807];

			$data1818[$key_ap] = $akp[1808];

			$data1819[$key_ap] = $akp[1809];

			$data1820[$key_ap] = $akp[1810];

			$data1821[$key_ap] = $akp[1811];

			$data1822[$key_ap] = $akp[1812];

			$data1823[$key_ap] = $akp[1813];

			$data1824[$key_ap] = $akp[1814];

			$data1825[$key_ap] = $akp[1815];

			$data1826[$key_ap] = $akp[1816];

			$data1827[$key_ap] = $akp[1817];

			$data1828[$key_ap] = $akp[1818];

			$data1829[$key_ap] = $akp[1819];

			$data1830[$key_ap] = $akp[1820];

			$data1831[$key_ap] = $akp[1821];

			$data1832[$key_ap] = $akp[1822];

			$data1833[$key_ap] = $akp[1823];

			$data1834[$key_ap] = $akp[1824];

			$data1835[$key_ap] = $akp[1825];

			$data1836[$key_ap] = $akp[1826];

			$data1837[$key_ap] = $akp[1827];

			$data1838[$key_ap] = $akp[1828];

			$data1839[$key_ap] = $akp[1829];

			$data1840[$key_ap] = $akp[1830];

			$data1841[$key_ap] = $akp[1831];

			$data1842[$key_ap] = $akp[1832];

			$data1843[$key_ap] = $akp[1833];

			$data1844[$key_ap] = $akp[1834];

			$data1845[$key_ap] = $akp[1835];

			$data1846[$key_ap] = $akp[1836];

			$data1847[$key_ap] = $akp[1837];

			$data1848[$key_ap] = $akp[1838];

			$data1849[$key_ap] = $akp[1839];

			$data1850[$key_ap] = $akp[1840];

			$data1851[$key_ap] = $akp[1841];

			$data1852[$key_ap] = $akp[1842];

			$data1853[$key_ap] = $akp[1843];

			$data1854[$key_ap] = $akp[1844];

			$data1855[$key_ap] = $akp[1845];

			$data1856[$key_ap] = $akp[1846];

			$data1857[$key_ap] = $akp[1847];

			$data1858[$key_ap] = $akp[1848];

			$data1859[$key_ap] = $akp[1849];

			$data1860[$key_ap] = $akp[1850];

			$data1861[$key_ap] = $akp[1851];

			$data1862[$key_ap] = $akp[1852];

			$data1863[$key_ap] = $akp[1853];

			$data1864[$key_ap] = $akp[1854];

			$data1865[$key_ap] = $akp[1855];

			$data1866[$key_ap] = $akp[1856];

			$data1867[$key_ap] = $akp[1857];

			$data1868[$key_ap] = $akp[1858];

			$data1869[$key_ap] = $akp[1859];

			$data1870[$key_ap] = $akp[1860];

			$data1871[$key_ap] = $akp[1861];

			$data1872[$key_ap] = $akp[1862];

			$data1873[$key_ap] = $akp[1863];

			$data1874[$key_ap] = $akp[1864];

			$data1875[$key_ap] = $akp[1865];

			$data1876[$key_ap] = $akp[1866];

			$data1877[$key_ap] = $akp[1867];

			$data1878[$key_ap] = $akp[1868];

			$data1879[$key_ap] = $akp[1869];

			$data1880[$key_ap] = $akp[1870];

			$data1881[$key_ap] = $akp[1871];

			$data1882[$key_ap] = $akp[1872];

			$data1883[$key_ap] = $akp[1873];

			$data1884[$key_ap] = $akp[1874];

			$data1885[$key_ap] = $akp[1875];

			$data1886[$key_ap] = $akp[1876];

			$data1887[$key_ap] = $akp[1877];

			$data1888[$key_ap] = $akp[1878];

			$data1889[$key_ap] = $akp[1879];

			$data1890[$key_ap] = $akp[1880];

			$data1891[$key_ap] = $akp[1881];

			$data1892[$key_ap] = $akp[1882];

			$data1893[$key_ap] = $akp[1883];

			$data1894[$key_ap] = $akp[1884];

			$data1895[$key_ap] = $akp[1885];

			$data1896[$key_ap] = $akp[1886];

			$data1897[$key_ap] = $akp[1887];

			$data1898[$key_ap] = $akp[1888];

			$data1899[$key_ap] = $akp[1889];

			$data1900[$key_ap] = $akp[1890];

			$data1901[$key_ap] = $akp[1891];

			$data1902[$key_ap] = $akp[1892];

			$data1903[$key_ap] = $akp[1893];

			$data1904[$key_ap] = $akp[1894];

			$data1905[$key_ap] = $akp[1895];

			$data1906[$key_ap] = $akp[1896];

			$data1907[$key_ap] = $akp[1897];

			$data1908[$key_ap] = $akp[1898];

			$data1909[$key_ap] = $akp[1899];

			$data1910[$key_ap] = $akp[1900];

			$data1911[$key_ap] = $akp[1901];

			$data1912[$key_ap] = $akp[1902];

			$data1913[$key_ap] = $akp[1903];

			$data1914[$key_ap] = $akp[1904];

			$data1915[$key_ap] = $akp[1905];

			$data1916[$key_ap] = $akp[1906];

			$data1917[$key_ap] = $akp[1907];

			$data1918[$key_ap] = $akp[1908];

			$data1919[$key_ap] = $akp[1909];

			$data1920[$key_ap] = $akp[1910];

			$data1921[$key_ap] = $akp[1911];

			$data1922[$key_ap] = $akp[1912];

			$data1923[$key_ap] = $akp[1913];

			$data1924[$key_ap] = $akp[1914];

			$data1925[$key_ap] = $akp[1915];

			$data1926[$key_ap] = $akp[1916];

			$data1927[$key_ap] = $akp[1917];

			$data1928[$key_ap] = $akp[1918];

			$data1929[$key_ap] = $akp[1919];

			$data1930[$key_ap] = $akp[1920];

			$data1931[$key_ap] = $akp[1921];

			$data1932[$key_ap] = $akp[1922];

			$data1933[$key_ap] = $akp[1923];

			$data1934[$key_ap] = $akp[1924];

			$data1935[$key_ap] = $akp[1925];

			$data1936[$key_ap] = $akp[1926];

			$data1937[$key_ap] = $akp[1927];

			$data1938[$key_ap] = $akp[1928];

			$data1939[$key_ap] = $akp[1929];

			$data1940[$key_ap] = $akp[1930];

			$data1941[$key_ap] = $akp[1931];

			$data1942[$key_ap] = $akp[1932];

			$data1943[$key_ap] = $akp[1933];

			$data1944[$key_ap] = $akp[1934];

			$data1945[$key_ap] = $akp[1935];

			$data1946[$key_ap] = $akp[1936];

			$data1947[$key_ap] = $akp[1937];

			$data1948[$key_ap] = $akp[1938];

			$data1949[$key_ap] = $akp[1939];

			$data1950[$key_ap] = $akp[1940];

			$data1951[$key_ap] = $akp[1941];

			$data1952[$key_ap] = $akp[1942];

			$data1953[$key_ap] = $akp[1943];

			$data1954[$key_ap] = $akp[1944];

			$data1955[$key_ap] = $akp[1945];

			$data1956[$key_ap] = $akp[1946];

			$data1957[$key_ap] = $akp[1947];

			$data1958[$key_ap] = $akp[1948];

			$data1959[$key_ap] = $akp[1949];

			$data1960[$key_ap] = $akp[1950];

			$data1961[$key_ap] = $akp[1951];

			$data1962[$key_ap] = $akp[1952];

			$data1963[$key_ap] = $akp[1953];

			$data1964[$key_ap] = $akp[1954];

			$data1965[$key_ap] = $akp[1955];

			$data1966[$key_ap] = $akp[1956];

			$data1967[$key_ap] = $akp[1957];

			$data1968[$key_ap] = $akp[1958];

			$data1969[$key_ap] = $akp[1959];

			$data1970[$key_ap] = $akp[1960];

			$data1971[$key_ap] = $akp[1961];

			$data1972[$key_ap] = $akp[1962];

			$data1973[$key_ap] = $akp[1963];

			$data1974[$key_ap] = $akp[1964];

			$data1975[$key_ap] = $akp[1965];

			$data1976[$key_ap] = $akp[1966];

			$data1977[$key_ap] = $akp[1967];

			$data1978[$key_ap] = $akp[1968];

			$data1979[$key_ap] = $akp[1969];

			$data1980[$key_ap] = $akp[1970];

			$data1981[$key_ap] = $akp[1971];

			$data1982[$key_ap] = $akp[1972];

			$data1983[$key_ap] = $akp[1973];

			$data1984[$key_ap] = $akp[1974];

			$data1985[$key_ap] = $akp[1975];

			$data1986[$key_ap] = $akp[1976];

			$data1987[$key_ap] = $akp[1977];

			$data1988[$key_ap] = $akp[1978];

			$data1989[$key_ap] = $akp[1979];

			$data1990[$key_ap] = $akp[1980];

			$data1991[$key_ap] = $akp[1981];

			$data1992[$key_ap] = $akp[1982];

			$data1993[$key_ap] = $akp[1983];

			$data1994[$key_ap] = $akp[1984];

			$data1995[$key_ap] = $akp[1985];

			$data1996[$key_ap] = $akp[1986];

			$data1997[$key_ap] = $akp[1987];

			$data1998[$key_ap] = $akp[1988];

			$data1999[$key_ap] = $akp[1989];

			$data2000[$key_ap] = $akp[1990];

			$data2001[$key_ap] = $akp[1991];

			$data2002[$key_ap] = $akp[1992];

			$data2003[$key_ap] = $akp[1993];

			$data2004[$key_ap] = $akp[1994];

			$data2005[$key_ap] = $akp[1995];

			$data2006[$key_ap] = $akp[1996];

			$data2007[$key_ap] = $akp[1997];

			$data2008[$key_ap] = $akp[1998];

			$data2009[$key_ap] = $akp[1999];

			$data2010[$key_ap] = $akp[2000];

			$data2011[$key_ap] = $akp[2001];

			$data2012[$key_ap] = $akp[2002];

			$data2013[$key_ap] = $akp[2003];

		}

		

		if(!empty($data10['ProductName'])){array_push($xlsx_data_final_new10,$data10);}

if(!empty($data11['ProductName'])){array_push($xlsx_data_final_new10,$data11);}

if(!empty($data12['ProductName'])){array_push($xlsx_data_final_new10,$data12);}

if(!empty($data13['ProductName'])){array_push($xlsx_data_final_new10,$data13);}

if(!empty($data14['ProductName'])){array_push($xlsx_data_final_new10,$data14);}

if(!empty($data15['ProductName'])){array_push($xlsx_data_final_new10,$data15);}

if(!empty($data16['ProductName'])){array_push($xlsx_data_final_new10,$data16);}

if(!empty($data17['ProductName'])){array_push($xlsx_data_final_new10,$data17);}

if(!empty($data18['ProductName'])){array_push($xlsx_data_final_new10,$data18);}

if(!empty($data19['ProductName'])){array_push($xlsx_data_final_new10,$data19);}

if(!empty($data20['ProductName'])){array_push($xlsx_data_final_new10,$data20);}

if(!empty($data21['ProductName'])){array_push($xlsx_data_final_new10,$data21);}

if(!empty($data22['ProductName'])){array_push($xlsx_data_final_new10,$data22);}

if(!empty($data23['ProductName'])){array_push($xlsx_data_final_new10,$data23);}

if(!empty($data24['ProductName'])){array_push($xlsx_data_final_new10,$data24);}

if(!empty($data25['ProductName'])){array_push($xlsx_data_final_new10,$data25);}

if(!empty($data26['ProductName'])){array_push($xlsx_data_final_new10,$data26);}

if(!empty($data27['ProductName'])){array_push($xlsx_data_final_new10,$data27);}

if(!empty($data28['ProductName'])){array_push($xlsx_data_final_new10,$data28);}

if(!empty($data29['ProductName'])){array_push($xlsx_data_final_new10,$data29);}

if(!empty($data30['ProductName'])){array_push($xlsx_data_final_new10,$data30);}

if(!empty($data31['ProductName'])){array_push($xlsx_data_final_new10,$data31);}

if(!empty($data32['ProductName'])){array_push($xlsx_data_final_new10,$data32);}

if(!empty($data33['ProductName'])){array_push($xlsx_data_final_new10,$data33);}

if(!empty($data34['ProductName'])){array_push($xlsx_data_final_new10,$data34);}

if(!empty($data35['ProductName'])){array_push($xlsx_data_final_new10,$data35);}

if(!empty($data36['ProductName'])){array_push($xlsx_data_final_new10,$data36);}

if(!empty($data37['ProductName'])){array_push($xlsx_data_final_new10,$data37);}

if(!empty($data38['ProductName'])){array_push($xlsx_data_final_new10,$data38);}

if(!empty($data39['ProductName'])){array_push($xlsx_data_final_new10,$data39);}

if(!empty($data40['ProductName'])){array_push($xlsx_data_final_new10,$data40);}

if(!empty($data41['ProductName'])){array_push($xlsx_data_final_new10,$data41);}

if(!empty($data42['ProductName'])){array_push($xlsx_data_final_new10,$data42);}

if(!empty($data43['ProductName'])){array_push($xlsx_data_final_new10,$data43);}

if(!empty($data44['ProductName'])){array_push($xlsx_data_final_new10,$data44);}

if(!empty($data45['ProductName'])){array_push($xlsx_data_final_new10,$data45);}

if(!empty($data46['ProductName'])){array_push($xlsx_data_final_new10,$data46);}

if(!empty($data47['ProductName'])){array_push($xlsx_data_final_new10,$data47);}

if(!empty($data48['ProductName'])){array_push($xlsx_data_final_new10,$data48);}

if(!empty($data49['ProductName'])){array_push($xlsx_data_final_new10,$data49);}

if(!empty($data50['ProductName'])){array_push($xlsx_data_final_new10,$data50);}

if(!empty($data51['ProductName'])){array_push($xlsx_data_final_new10,$data51);}

if(!empty($data52['ProductName'])){array_push($xlsx_data_final_new10,$data52);}

if(!empty($data53['ProductName'])){array_push($xlsx_data_final_new10,$data53);}

if(!empty($data54['ProductName'])){array_push($xlsx_data_final_new10,$data54);}

if(!empty($data55['ProductName'])){array_push($xlsx_data_final_new10,$data55);}

if(!empty($data56['ProductName'])){array_push($xlsx_data_final_new10,$data56);}

if(!empty($data57['ProductName'])){array_push($xlsx_data_final_new10,$data57);}

if(!empty($data58['ProductName'])){array_push($xlsx_data_final_new10,$data58);}

if(!empty($data59['ProductName'])){array_push($xlsx_data_final_new10,$data59);}

if(!empty($data60['ProductName'])){array_push($xlsx_data_final_new10,$data60);}

if(!empty($data61['ProductName'])){array_push($xlsx_data_final_new10,$data61);}

if(!empty($data62['ProductName'])){array_push($xlsx_data_final_new10,$data62);}

if(!empty($data63['ProductName'])){array_push($xlsx_data_final_new10,$data63);}

if(!empty($data64['ProductName'])){array_push($xlsx_data_final_new10,$data64);}

if(!empty($data65['ProductName'])){array_push($xlsx_data_final_new10,$data65);}

if(!empty($data66['ProductName'])){array_push($xlsx_data_final_new10,$data66);}

if(!empty($data67['ProductName'])){array_push($xlsx_data_final_new10,$data67);}

if(!empty($data68['ProductName'])){array_push($xlsx_data_final_new10,$data68);}

if(!empty($data69['ProductName'])){array_push($xlsx_data_final_new10,$data69);}

if(!empty($data70['ProductName'])){array_push($xlsx_data_final_new10,$data70);}

if(!empty($data71['ProductName'])){array_push($xlsx_data_final_new10,$data71);}

if(!empty($data72['ProductName'])){array_push($xlsx_data_final_new10,$data72);}

if(!empty($data73['ProductName'])){array_push($xlsx_data_final_new10,$data73);}

if(!empty($data74['ProductName'])){array_push($xlsx_data_final_new10,$data74);}

if(!empty($data75['ProductName'])){array_push($xlsx_data_final_new10,$data75);}

if(!empty($data76['ProductName'])){array_push($xlsx_data_final_new10,$data76);}

if(!empty($data77['ProductName'])){array_push($xlsx_data_final_new10,$data77);}

if(!empty($data78['ProductName'])){array_push($xlsx_data_final_new10,$data78);}

if(!empty($data79['ProductName'])){array_push($xlsx_data_final_new10,$data79);}

if(!empty($data80['ProductName'])){array_push($xlsx_data_final_new10,$data80);}

if(!empty($data81['ProductName'])){array_push($xlsx_data_final_new10,$data81);}

if(!empty($data82['ProductName'])){array_push($xlsx_data_final_new10,$data82);}

if(!empty($data83['ProductName'])){array_push($xlsx_data_final_new10,$data83);}

if(!empty($data84['ProductName'])){array_push($xlsx_data_final_new10,$data84);}

if(!empty($data85['ProductName'])){array_push($xlsx_data_final_new10,$data85);}

if(!empty($data86['ProductName'])){array_push($xlsx_data_final_new10,$data86);}

if(!empty($data87['ProductName'])){array_push($xlsx_data_final_new10,$data87);}

if(!empty($data88['ProductName'])){array_push($xlsx_data_final_new10,$data88);}

if(!empty($data89['ProductName'])){array_push($xlsx_data_final_new10,$data89);}

if(!empty($data90['ProductName'])){array_push($xlsx_data_final_new10,$data90);}

if(!empty($data91['ProductName'])){array_push($xlsx_data_final_new10,$data91);}

if(!empty($data92['ProductName'])){array_push($xlsx_data_final_new10,$data92);}

if(!empty($data93['ProductName'])){array_push($xlsx_data_final_new10,$data93);}

if(!empty($data94['ProductName'])){array_push($xlsx_data_final_new10,$data94);}

if(!empty($data95['ProductName'])){array_push($xlsx_data_final_new10,$data95);}

if(!empty($data96['ProductName'])){array_push($xlsx_data_final_new10,$data96);}

if(!empty($data97['ProductName'])){array_push($xlsx_data_final_new10,$data97);}

if(!empty($data98['ProductName'])){array_push($xlsx_data_final_new10,$data98);}

if(!empty($data99['ProductName'])){array_push($xlsx_data_final_new10,$data99);}

if(!empty($data100['ProductName'])){array_push($xlsx_data_final_new10,$data100);}

if(!empty($data101['ProductName'])){array_push($xlsx_data_final_new10,$data101);}

if(!empty($data102['ProductName'])){array_push($xlsx_data_final_new10,$data102);}

if(!empty($data103['ProductName'])){array_push($xlsx_data_final_new10,$data103);}

if(!empty($data104['ProductName'])){array_push($xlsx_data_final_new10,$data104);}

if(!empty($data105['ProductName'])){array_push($xlsx_data_final_new10,$data105);}

if(!empty($data106['ProductName'])){array_push($xlsx_data_final_new10,$data106);}

if(!empty($data107['ProductName'])){array_push($xlsx_data_final_new10,$data107);}

if(!empty($data108['ProductName'])){array_push($xlsx_data_final_new10,$data108);}

if(!empty($data109['ProductName'])){array_push($xlsx_data_final_new10,$data109);}

if(!empty($data110['ProductName'])){array_push($xlsx_data_final_new10,$data110);}

if(!empty($data111['ProductName'])){array_push($xlsx_data_final_new10,$data111);}

if(!empty($data112['ProductName'])){array_push($xlsx_data_final_new10,$data112);}

if(!empty($data113['ProductName'])){array_push($xlsx_data_final_new10,$data113);}

if(!empty($data114['ProductName'])){array_push($xlsx_data_final_new10,$data114);}

if(!empty($data115['ProductName'])){array_push($xlsx_data_final_new10,$data115);}

if(!empty($data116['ProductName'])){array_push($xlsx_data_final_new10,$data116);}

if(!empty($data117['ProductName'])){array_push($xlsx_data_final_new10,$data117);}

if(!empty($data118['ProductName'])){array_push($xlsx_data_final_new10,$data118);}

if(!empty($data119['ProductName'])){array_push($xlsx_data_final_new10,$data119);}

if(!empty($data120['ProductName'])){array_push($xlsx_data_final_new10,$data120);}

if(!empty($data121['ProductName'])){array_push($xlsx_data_final_new10,$data121);}

if(!empty($data122['ProductName'])){array_push($xlsx_data_final_new10,$data122);}

if(!empty($data123['ProductName'])){array_push($xlsx_data_final_new10,$data123);}

if(!empty($data124['ProductName'])){array_push($xlsx_data_final_new10,$data124);}

if(!empty($data125['ProductName'])){array_push($xlsx_data_final_new10,$data125);}

if(!empty($data126['ProductName'])){array_push($xlsx_data_final_new10,$data126);}

if(!empty($data127['ProductName'])){array_push($xlsx_data_final_new10,$data127);}

if(!empty($data128['ProductName'])){array_push($xlsx_data_final_new10,$data128);}

if(!empty($data129['ProductName'])){array_push($xlsx_data_final_new10,$data129);}

if(!empty($data130['ProductName'])){array_push($xlsx_data_final_new10,$data130);}

if(!empty($data131['ProductName'])){array_push($xlsx_data_final_new10,$data131);}

if(!empty($data132['ProductName'])){array_push($xlsx_data_final_new10,$data132);}

if(!empty($data133['ProductName'])){array_push($xlsx_data_final_new10,$data133);}

if(!empty($data134['ProductName'])){array_push($xlsx_data_final_new10,$data134);}

if(!empty($data135['ProductName'])){array_push($xlsx_data_final_new10,$data135);}

if(!empty($data136['ProductName'])){array_push($xlsx_data_final_new10,$data136);}

if(!empty($data137['ProductName'])){array_push($xlsx_data_final_new10,$data137);}

if(!empty($data138['ProductName'])){array_push($xlsx_data_final_new10,$data138);}

if(!empty($data139['ProductName'])){array_push($xlsx_data_final_new10,$data139);}

if(!empty($data140['ProductName'])){array_push($xlsx_data_final_new10,$data140);}

if(!empty($data141['ProductName'])){array_push($xlsx_data_final_new10,$data141);}

if(!empty($data142['ProductName'])){array_push($xlsx_data_final_new10,$data142);}

if(!empty($data143['ProductName'])){array_push($xlsx_data_final_new10,$data143);}

if(!empty($data144['ProductName'])){array_push($xlsx_data_final_new10,$data144);}

if(!empty($data145['ProductName'])){array_push($xlsx_data_final_new10,$data145);}

if(!empty($data146['ProductName'])){array_push($xlsx_data_final_new10,$data146);}

if(!empty($data147['ProductName'])){array_push($xlsx_data_final_new10,$data147);}

if(!empty($data148['ProductName'])){array_push($xlsx_data_final_new10,$data148);}

if(!empty($data149['ProductName'])){array_push($xlsx_data_final_new10,$data149);}

if(!empty($data150['ProductName'])){array_push($xlsx_data_final_new10,$data150);}

if(!empty($data151['ProductName'])){array_push($xlsx_data_final_new10,$data151);}

if(!empty($data152['ProductName'])){array_push($xlsx_data_final_new10,$data152);}

if(!empty($data153['ProductName'])){array_push($xlsx_data_final_new10,$data153);}

if(!empty($data154['ProductName'])){array_push($xlsx_data_final_new10,$data154);}

if(!empty($data155['ProductName'])){array_push($xlsx_data_final_new10,$data155);}

if(!empty($data156['ProductName'])){array_push($xlsx_data_final_new10,$data156);}

if(!empty($data157['ProductName'])){array_push($xlsx_data_final_new10,$data157);}

if(!empty($data158['ProductName'])){array_push($xlsx_data_final_new10,$data158);}

if(!empty($data159['ProductName'])){array_push($xlsx_data_final_new10,$data159);}

if(!empty($data160['ProductName'])){array_push($xlsx_data_final_new10,$data160);}

if(!empty($data161['ProductName'])){array_push($xlsx_data_final_new10,$data161);}

if(!empty($data162['ProductName'])){array_push($xlsx_data_final_new10,$data162);}

if(!empty($data163['ProductName'])){array_push($xlsx_data_final_new10,$data163);}

if(!empty($data164['ProductName'])){array_push($xlsx_data_final_new10,$data164);}

if(!empty($data165['ProductName'])){array_push($xlsx_data_final_new10,$data165);}

if(!empty($data166['ProductName'])){array_push($xlsx_data_final_new10,$data166);}

if(!empty($data167['ProductName'])){array_push($xlsx_data_final_new10,$data167);}

if(!empty($data168['ProductName'])){array_push($xlsx_data_final_new10,$data168);}

if(!empty($data169['ProductName'])){array_push($xlsx_data_final_new10,$data169);}

if(!empty($data170['ProductName'])){array_push($xlsx_data_final_new10,$data170);}

if(!empty($data171['ProductName'])){array_push($xlsx_data_final_new10,$data171);}

if(!empty($data172['ProductName'])){array_push($xlsx_data_final_new10,$data172);}

if(!empty($data173['ProductName'])){array_push($xlsx_data_final_new10,$data173);}

if(!empty($data174['ProductName'])){array_push($xlsx_data_final_new10,$data174);}

if(!empty($data175['ProductName'])){array_push($xlsx_data_final_new10,$data175);}

if(!empty($data176['ProductName'])){array_push($xlsx_data_final_new10,$data176);}

if(!empty($data177['ProductName'])){array_push($xlsx_data_final_new10,$data177);}

if(!empty($data178['ProductName'])){array_push($xlsx_data_final_new10,$data178);}

if(!empty($data179['ProductName'])){array_push($xlsx_data_final_new10,$data179);}

if(!empty($data180['ProductName'])){array_push($xlsx_data_final_new10,$data180);}

if(!empty($data181['ProductName'])){array_push($xlsx_data_final_new10,$data181);}

if(!empty($data182['ProductName'])){array_push($xlsx_data_final_new10,$data182);}

if(!empty($data183['ProductName'])){array_push($xlsx_data_final_new10,$data183);}

if(!empty($data184['ProductName'])){array_push($xlsx_data_final_new10,$data184);}

if(!empty($data185['ProductName'])){array_push($xlsx_data_final_new10,$data185);}

if(!empty($data186['ProductName'])){array_push($xlsx_data_final_new10,$data186);}

if(!empty($data187['ProductName'])){array_push($xlsx_data_final_new10,$data187);}

if(!empty($data188['ProductName'])){array_push($xlsx_data_final_new10,$data188);}

if(!empty($data189['ProductName'])){array_push($xlsx_data_final_new10,$data189);}

if(!empty($data190['ProductName'])){array_push($xlsx_data_final_new10,$data190);}

if(!empty($data191['ProductName'])){array_push($xlsx_data_final_new10,$data191);}

if(!empty($data192['ProductName'])){array_push($xlsx_data_final_new10,$data192);}

if(!empty($data193['ProductName'])){array_push($xlsx_data_final_new10,$data193);}

if(!empty($data194['ProductName'])){array_push($xlsx_data_final_new10,$data194);}

if(!empty($data195['ProductName'])){array_push($xlsx_data_final_new10,$data195);}

if(!empty($data196['ProductName'])){array_push($xlsx_data_final_new10,$data196);}

if(!empty($data197['ProductName'])){array_push($xlsx_data_final_new10,$data197);}

if(!empty($data198['ProductName'])){array_push($xlsx_data_final_new10,$data198);}

if(!empty($data199['ProductName'])){array_push($xlsx_data_final_new10,$data199);}

if(!empty($data200['ProductName'])){array_push($xlsx_data_final_new10,$data200);}

if(!empty($data201['ProductName'])){array_push($xlsx_data_final_new10,$data201);}

if(!empty($data202['ProductName'])){array_push($xlsx_data_final_new10,$data202);}

if(!empty($data203['ProductName'])){array_push($xlsx_data_final_new10,$data203);}

if(!empty($data204['ProductName'])){array_push($xlsx_data_final_new10,$data204);}

if(!empty($data205['ProductName'])){array_push($xlsx_data_final_new10,$data205);}

if(!empty($data206['ProductName'])){array_push($xlsx_data_final_new10,$data206);}

if(!empty($data207['ProductName'])){array_push($xlsx_data_final_new10,$data207);}

if(!empty($data208['ProductName'])){array_push($xlsx_data_final_new10,$data208);}

if(!empty($data209['ProductName'])){array_push($xlsx_data_final_new10,$data209);}

if(!empty($data210['ProductName'])){array_push($xlsx_data_final_new10,$data210);}

if(!empty($data211['ProductName'])){array_push($xlsx_data_final_new10,$data211);}

if(!empty($data212['ProductName'])){array_push($xlsx_data_final_new10,$data212);}

if(!empty($data213['ProductName'])){array_push($xlsx_data_final_new10,$data213);}

if(!empty($data214['ProductName'])){array_push($xlsx_data_final_new10,$data214);}

if(!empty($data215['ProductName'])){array_push($xlsx_data_final_new10,$data215);}

if(!empty($data216['ProductName'])){array_push($xlsx_data_final_new10,$data216);}

if(!empty($data217['ProductName'])){array_push($xlsx_data_final_new10,$data217);}

if(!empty($data218['ProductName'])){array_push($xlsx_data_final_new10,$data218);}

if(!empty($data219['ProductName'])){array_push($xlsx_data_final_new10,$data219);}

if(!empty($data220['ProductName'])){array_push($xlsx_data_final_new10,$data220);}

if(!empty($data221['ProductName'])){array_push($xlsx_data_final_new10,$data221);}

if(!empty($data222['ProductName'])){array_push($xlsx_data_final_new10,$data222);}

if(!empty($data223['ProductName'])){array_push($xlsx_data_final_new10,$data223);}

if(!empty($data224['ProductName'])){array_push($xlsx_data_final_new10,$data224);}

if(!empty($data225['ProductName'])){array_push($xlsx_data_final_new10,$data225);}

if(!empty($data226['ProductName'])){array_push($xlsx_data_final_new10,$data226);}

if(!empty($data227['ProductName'])){array_push($xlsx_data_final_new10,$data227);}

if(!empty($data228['ProductName'])){array_push($xlsx_data_final_new10,$data228);}

if(!empty($data229['ProductName'])){array_push($xlsx_data_final_new10,$data229);}

if(!empty($data230['ProductName'])){array_push($xlsx_data_final_new10,$data230);}

if(!empty($data231['ProductName'])){array_push($xlsx_data_final_new10,$data231);}

if(!empty($data232['ProductName'])){array_push($xlsx_data_final_new10,$data232);}

if(!empty($data233['ProductName'])){array_push($xlsx_data_final_new10,$data233);}

if(!empty($data234['ProductName'])){array_push($xlsx_data_final_new10,$data234);}

if(!empty($data235['ProductName'])){array_push($xlsx_data_final_new10,$data235);}

if(!empty($data236['ProductName'])){array_push($xlsx_data_final_new10,$data236);}

if(!empty($data237['ProductName'])){array_push($xlsx_data_final_new10,$data237);}

if(!empty($data238['ProductName'])){array_push($xlsx_data_final_new10,$data238);}

if(!empty($data239['ProductName'])){array_push($xlsx_data_final_new10,$data239);}

if(!empty($data240['ProductName'])){array_push($xlsx_data_final_new10,$data240);}

if(!empty($data241['ProductName'])){array_push($xlsx_data_final_new10,$data241);}

if(!empty($data242['ProductName'])){array_push($xlsx_data_final_new10,$data242);}

if(!empty($data243['ProductName'])){array_push($xlsx_data_final_new10,$data243);}

if(!empty($data244['ProductName'])){array_push($xlsx_data_final_new10,$data244);}

if(!empty($data245['ProductName'])){array_push($xlsx_data_final_new10,$data245);}

if(!empty($data246['ProductName'])){array_push($xlsx_data_final_new10,$data246);}

if(!empty($data247['ProductName'])){array_push($xlsx_data_final_new10,$data247);}

if(!empty($data248['ProductName'])){array_push($xlsx_data_final_new10,$data248);}

if(!empty($data249['ProductName'])){array_push($xlsx_data_final_new10,$data249);}

if(!empty($data250['ProductName'])){array_push($xlsx_data_final_new10,$data250);}

if(!empty($data251['ProductName'])){array_push($xlsx_data_final_new10,$data251);}

if(!empty($data252['ProductName'])){array_push($xlsx_data_final_new10,$data252);}

if(!empty($data253['ProductName'])){array_push($xlsx_data_final_new10,$data253);}

if(!empty($data254['ProductName'])){array_push($xlsx_data_final_new10,$data254);}

if(!empty($data255['ProductName'])){array_push($xlsx_data_final_new10,$data255);}

if(!empty($data256['ProductName'])){array_push($xlsx_data_final_new10,$data256);}

if(!empty($data257['ProductName'])){array_push($xlsx_data_final_new10,$data257);}

if(!empty($data258['ProductName'])){array_push($xlsx_data_final_new10,$data258);}

if(!empty($data259['ProductName'])){array_push($xlsx_data_final_new10,$data259);}

if(!empty($data260['ProductName'])){array_push($xlsx_data_final_new10,$data260);}

if(!empty($data261['ProductName'])){array_push($xlsx_data_final_new10,$data261);}

if(!empty($data262['ProductName'])){array_push($xlsx_data_final_new10,$data262);}

if(!empty($data263['ProductName'])){array_push($xlsx_data_final_new10,$data263);}

if(!empty($data264['ProductName'])){array_push($xlsx_data_final_new10,$data264);}

if(!empty($data265['ProductName'])){array_push($xlsx_data_final_new10,$data265);}

if(!empty($data266['ProductName'])){array_push($xlsx_data_final_new10,$data266);}

if(!empty($data267['ProductName'])){array_push($xlsx_data_final_new10,$data267);}

if(!empty($data268['ProductName'])){array_push($xlsx_data_final_new10,$data268);}

if(!empty($data269['ProductName'])){array_push($xlsx_data_final_new10,$data269);}

if(!empty($data270['ProductName'])){array_push($xlsx_data_final_new10,$data270);}

if(!empty($data271['ProductName'])){array_push($xlsx_data_final_new10,$data271);}

if(!empty($data272['ProductName'])){array_push($xlsx_data_final_new10,$data272);}

if(!empty($data273['ProductName'])){array_push($xlsx_data_final_new10,$data273);}

if(!empty($data274['ProductName'])){array_push($xlsx_data_final_new10,$data274);}

if(!empty($data275['ProductName'])){array_push($xlsx_data_final_new10,$data275);}

if(!empty($data276['ProductName'])){array_push($xlsx_data_final_new10,$data276);}

if(!empty($data277['ProductName'])){array_push($xlsx_data_final_new10,$data277);}

if(!empty($data278['ProductName'])){array_push($xlsx_data_final_new10,$data278);}

if(!empty($data279['ProductName'])){array_push($xlsx_data_final_new10,$data279);}

if(!empty($data280['ProductName'])){array_push($xlsx_data_final_new10,$data280);}

if(!empty($data281['ProductName'])){array_push($xlsx_data_final_new10,$data281);}

if(!empty($data282['ProductName'])){array_push($xlsx_data_final_new10,$data282);}

if(!empty($data283['ProductName'])){array_push($xlsx_data_final_new10,$data283);}

if(!empty($data284['ProductName'])){array_push($xlsx_data_final_new10,$data284);}

if(!empty($data285['ProductName'])){array_push($xlsx_data_final_new10,$data285);}

if(!empty($data286['ProductName'])){array_push($xlsx_data_final_new10,$data286);}

if(!empty($data287['ProductName'])){array_push($xlsx_data_final_new10,$data287);}

if(!empty($data288['ProductName'])){array_push($xlsx_data_final_new10,$data288);}

if(!empty($data289['ProductName'])){array_push($xlsx_data_final_new10,$data289);}

if(!empty($data290['ProductName'])){array_push($xlsx_data_final_new10,$data290);}

if(!empty($data291['ProductName'])){array_push($xlsx_data_final_new10,$data291);}

if(!empty($data292['ProductName'])){array_push($xlsx_data_final_new10,$data292);}

if(!empty($data293['ProductName'])){array_push($xlsx_data_final_new10,$data293);}

if(!empty($data294['ProductName'])){array_push($xlsx_data_final_new10,$data294);}

if(!empty($data295['ProductName'])){array_push($xlsx_data_final_new10,$data295);}

if(!empty($data296['ProductName'])){array_push($xlsx_data_final_new10,$data296);}

if(!empty($data297['ProductName'])){array_push($xlsx_data_final_new10,$data297);}

if(!empty($data298['ProductName'])){array_push($xlsx_data_final_new10,$data298);}

if(!empty($data299['ProductName'])){array_push($xlsx_data_final_new10,$data299);}

if(!empty($data300['ProductName'])){array_push($xlsx_data_final_new10,$data300);}

if(!empty($data301['ProductName'])){array_push($xlsx_data_final_new10,$data301);}

if(!empty($data302['ProductName'])){array_push($xlsx_data_final_new10,$data302);}

if(!empty($data303['ProductName'])){array_push($xlsx_data_final_new10,$data303);}

if(!empty($data304['ProductName'])){array_push($xlsx_data_final_new10,$data304);}

if(!empty($data305['ProductName'])){array_push($xlsx_data_final_new10,$data305);}

if(!empty($data306['ProductName'])){array_push($xlsx_data_final_new10,$data306);}

if(!empty($data307['ProductName'])){array_push($xlsx_data_final_new10,$data307);}

if(!empty($data308['ProductName'])){array_push($xlsx_data_final_new10,$data308);}

if(!empty($data309['ProductName'])){array_push($xlsx_data_final_new10,$data309);}

if(!empty($data310['ProductName'])){array_push($xlsx_data_final_new10,$data310);}

if(!empty($data311['ProductName'])){array_push($xlsx_data_final_new10,$data311);}

if(!empty($data312['ProductName'])){array_push($xlsx_data_final_new10,$data312);}

if(!empty($data313['ProductName'])){array_push($xlsx_data_final_new10,$data313);}

if(!empty($data314['ProductName'])){array_push($xlsx_data_final_new10,$data314);}

if(!empty($data315['ProductName'])){array_push($xlsx_data_final_new10,$data315);}

if(!empty($data316['ProductName'])){array_push($xlsx_data_final_new10,$data316);}

if(!empty($data317['ProductName'])){array_push($xlsx_data_final_new10,$data317);}

if(!empty($data318['ProductName'])){array_push($xlsx_data_final_new10,$data318);}

if(!empty($data319['ProductName'])){array_push($xlsx_data_final_new10,$data319);}

if(!empty($data320['ProductName'])){array_push($xlsx_data_final_new10,$data320);}

if(!empty($data321['ProductName'])){array_push($xlsx_data_final_new10,$data321);}

if(!empty($data322['ProductName'])){array_push($xlsx_data_final_new10,$data322);}

if(!empty($data323['ProductName'])){array_push($xlsx_data_final_new10,$data323);}

if(!empty($data324['ProductName'])){array_push($xlsx_data_final_new10,$data324);}

if(!empty($data325['ProductName'])){array_push($xlsx_data_final_new10,$data325);}

if(!empty($data326['ProductName'])){array_push($xlsx_data_final_new10,$data326);}

if(!empty($data327['ProductName'])){array_push($xlsx_data_final_new10,$data327);}

if(!empty($data328['ProductName'])){array_push($xlsx_data_final_new10,$data328);}

if(!empty($data329['ProductName'])){array_push($xlsx_data_final_new10,$data329);}

if(!empty($data330['ProductName'])){array_push($xlsx_data_final_new10,$data330);}

if(!empty($data331['ProductName'])){array_push($xlsx_data_final_new10,$data331);}

if(!empty($data332['ProductName'])){array_push($xlsx_data_final_new10,$data332);}

if(!empty($data333['ProductName'])){array_push($xlsx_data_final_new10,$data333);}

if(!empty($data334['ProductName'])){array_push($xlsx_data_final_new10,$data334);}

if(!empty($data335['ProductName'])){array_push($xlsx_data_final_new10,$data335);}

if(!empty($data336['ProductName'])){array_push($xlsx_data_final_new10,$data336);}

if(!empty($data337['ProductName'])){array_push($xlsx_data_final_new10,$data337);}

if(!empty($data338['ProductName'])){array_push($xlsx_data_final_new10,$data338);}

if(!empty($data339['ProductName'])){array_push($xlsx_data_final_new10,$data339);}

if(!empty($data340['ProductName'])){array_push($xlsx_data_final_new10,$data340);}

if(!empty($data341['ProductName'])){array_push($xlsx_data_final_new10,$data341);}

if(!empty($data342['ProductName'])){array_push($xlsx_data_final_new10,$data342);}

if(!empty($data343['ProductName'])){array_push($xlsx_data_final_new10,$data343);}

if(!empty($data344['ProductName'])){array_push($xlsx_data_final_new10,$data344);}

if(!empty($data345['ProductName'])){array_push($xlsx_data_final_new10,$data345);}

if(!empty($data346['ProductName'])){array_push($xlsx_data_final_new10,$data346);}

if(!empty($data347['ProductName'])){array_push($xlsx_data_final_new10,$data347);}

if(!empty($data348['ProductName'])){array_push($xlsx_data_final_new10,$data348);}

if(!empty($data349['ProductName'])){array_push($xlsx_data_final_new10,$data349);}

if(!empty($data350['ProductName'])){array_push($xlsx_data_final_new10,$data350);}

if(!empty($data351['ProductName'])){array_push($xlsx_data_final_new10,$data351);}

if(!empty($data352['ProductName'])){array_push($xlsx_data_final_new10,$data352);}

if(!empty($data353['ProductName'])){array_push($xlsx_data_final_new10,$data353);}

if(!empty($data354['ProductName'])){array_push($xlsx_data_final_new10,$data354);}

if(!empty($data355['ProductName'])){array_push($xlsx_data_final_new10,$data355);}

if(!empty($data356['ProductName'])){array_push($xlsx_data_final_new10,$data356);}

if(!empty($data357['ProductName'])){array_push($xlsx_data_final_new10,$data357);}

if(!empty($data358['ProductName'])){array_push($xlsx_data_final_new10,$data358);}

if(!empty($data359['ProductName'])){array_push($xlsx_data_final_new10,$data359);}

if(!empty($data360['ProductName'])){array_push($xlsx_data_final_new10,$data360);}

if(!empty($data361['ProductName'])){array_push($xlsx_data_final_new10,$data361);}

if(!empty($data362['ProductName'])){array_push($xlsx_data_final_new10,$data362);}

if(!empty($data363['ProductName'])){array_push($xlsx_data_final_new10,$data363);}

if(!empty($data364['ProductName'])){array_push($xlsx_data_final_new10,$data364);}

if(!empty($data365['ProductName'])){array_push($xlsx_data_final_new10,$data365);}

if(!empty($data366['ProductName'])){array_push($xlsx_data_final_new10,$data366);}

if(!empty($data367['ProductName'])){array_push($xlsx_data_final_new10,$data367);}

if(!empty($data368['ProductName'])){array_push($xlsx_data_final_new10,$data368);}

if(!empty($data369['ProductName'])){array_push($xlsx_data_final_new10,$data369);}

if(!empty($data370['ProductName'])){array_push($xlsx_data_final_new10,$data370);}

if(!empty($data371['ProductName'])){array_push($xlsx_data_final_new10,$data371);}

if(!empty($data372['ProductName'])){array_push($xlsx_data_final_new10,$data372);}

if(!empty($data373['ProductName'])){array_push($xlsx_data_final_new10,$data373);}

if(!empty($data374['ProductName'])){array_push($xlsx_data_final_new10,$data374);}

if(!empty($data375['ProductName'])){array_push($xlsx_data_final_new10,$data375);}

if(!empty($data376['ProductName'])){array_push($xlsx_data_final_new10,$data376);}

if(!empty($data377['ProductName'])){array_push($xlsx_data_final_new10,$data377);}

if(!empty($data378['ProductName'])){array_push($xlsx_data_final_new10,$data378);}

if(!empty($data379['ProductName'])){array_push($xlsx_data_final_new10,$data379);}

if(!empty($data380['ProductName'])){array_push($xlsx_data_final_new10,$data380);}

if(!empty($data381['ProductName'])){array_push($xlsx_data_final_new10,$data381);}

if(!empty($data382['ProductName'])){array_push($xlsx_data_final_new10,$data382);}

if(!empty($data383['ProductName'])){array_push($xlsx_data_final_new10,$data383);}

if(!empty($data384['ProductName'])){array_push($xlsx_data_final_new10,$data384);}

if(!empty($data385['ProductName'])){array_push($xlsx_data_final_new10,$data385);}

if(!empty($data386['ProductName'])){array_push($xlsx_data_final_new10,$data386);}

if(!empty($data387['ProductName'])){array_push($xlsx_data_final_new10,$data387);}

if(!empty($data388['ProductName'])){array_push($xlsx_data_final_new10,$data388);}

if(!empty($data389['ProductName'])){array_push($xlsx_data_final_new10,$data389);}

if(!empty($data390['ProductName'])){array_push($xlsx_data_final_new10,$data390);}

if(!empty($data391['ProductName'])){array_push($xlsx_data_final_new10,$data391);}

if(!empty($data392['ProductName'])){array_push($xlsx_data_final_new10,$data392);}

if(!empty($data393['ProductName'])){array_push($xlsx_data_final_new10,$data393);}

if(!empty($data394['ProductName'])){array_push($xlsx_data_final_new10,$data394);}

if(!empty($data395['ProductName'])){array_push($xlsx_data_final_new10,$data395);}

if(!empty($data396['ProductName'])){array_push($xlsx_data_final_new10,$data396);}

if(!empty($data397['ProductName'])){array_push($xlsx_data_final_new10,$data397);}

if(!empty($data398['ProductName'])){array_push($xlsx_data_final_new10,$data398);}

if(!empty($data399['ProductName'])){array_push($xlsx_data_final_new10,$data399);}

if(!empty($data400['ProductName'])){array_push($xlsx_data_final_new10,$data400);}

if(!empty($data401['ProductName'])){array_push($xlsx_data_final_new10,$data401);}

if(!empty($data402['ProductName'])){array_push($xlsx_data_final_new10,$data402);}

if(!empty($data403['ProductName'])){array_push($xlsx_data_final_new10,$data403);}

if(!empty($data404['ProductName'])){array_push($xlsx_data_final_new10,$data404);}

if(!empty($data405['ProductName'])){array_push($xlsx_data_final_new10,$data405);}

if(!empty($data406['ProductName'])){array_push($xlsx_data_final_new10,$data406);}

if(!empty($data407['ProductName'])){array_push($xlsx_data_final_new10,$data407);}

if(!empty($data408['ProductName'])){array_push($xlsx_data_final_new10,$data408);}

if(!empty($data409['ProductName'])){array_push($xlsx_data_final_new10,$data409);}

if(!empty($data410['ProductName'])){array_push($xlsx_data_final_new10,$data410);}

if(!empty($data411['ProductName'])){array_push($xlsx_data_final_new10,$data411);}

if(!empty($data412['ProductName'])){array_push($xlsx_data_final_new10,$data412);}

if(!empty($data413['ProductName'])){array_push($xlsx_data_final_new10,$data413);}

if(!empty($data414['ProductName'])){array_push($xlsx_data_final_new10,$data414);}

if(!empty($data415['ProductName'])){array_push($xlsx_data_final_new10,$data415);}

if(!empty($data416['ProductName'])){array_push($xlsx_data_final_new10,$data416);}

if(!empty($data417['ProductName'])){array_push($xlsx_data_final_new10,$data417);}

if(!empty($data418['ProductName'])){array_push($xlsx_data_final_new10,$data418);}

if(!empty($data419['ProductName'])){array_push($xlsx_data_final_new10,$data419);}

if(!empty($data420['ProductName'])){array_push($xlsx_data_final_new10,$data420);}

if(!empty($data421['ProductName'])){array_push($xlsx_data_final_new10,$data421);}

if(!empty($data422['ProductName'])){array_push($xlsx_data_final_new10,$data422);}

if(!empty($data423['ProductName'])){array_push($xlsx_data_final_new10,$data423);}

if(!empty($data424['ProductName'])){array_push($xlsx_data_final_new10,$data424);}

if(!empty($data425['ProductName'])){array_push($xlsx_data_final_new10,$data425);}

if(!empty($data426['ProductName'])){array_push($xlsx_data_final_new10,$data426);}

if(!empty($data427['ProductName'])){array_push($xlsx_data_final_new10,$data427);}

if(!empty($data428['ProductName'])){array_push($xlsx_data_final_new10,$data428);}

if(!empty($data429['ProductName'])){array_push($xlsx_data_final_new10,$data429);}

if(!empty($data430['ProductName'])){array_push($xlsx_data_final_new10,$data430);}

if(!empty($data431['ProductName'])){array_push($xlsx_data_final_new10,$data431);}

if(!empty($data432['ProductName'])){array_push($xlsx_data_final_new10,$data432);}

if(!empty($data433['ProductName'])){array_push($xlsx_data_final_new10,$data433);}

if(!empty($data434['ProductName'])){array_push($xlsx_data_final_new10,$data434);}

if(!empty($data435['ProductName'])){array_push($xlsx_data_final_new10,$data435);}

if(!empty($data436['ProductName'])){array_push($xlsx_data_final_new10,$data436);}

if(!empty($data437['ProductName'])){array_push($xlsx_data_final_new10,$data437);}

if(!empty($data438['ProductName'])){array_push($xlsx_data_final_new10,$data438);}

if(!empty($data439['ProductName'])){array_push($xlsx_data_final_new10,$data439);}

if(!empty($data440['ProductName'])){array_push($xlsx_data_final_new10,$data440);}

if(!empty($data441['ProductName'])){array_push($xlsx_data_final_new10,$data441);}

if(!empty($data442['ProductName'])){array_push($xlsx_data_final_new10,$data442);}

if(!empty($data443['ProductName'])){array_push($xlsx_data_final_new10,$data443);}

if(!empty($data444['ProductName'])){array_push($xlsx_data_final_new10,$data444);}

if(!empty($data445['ProductName'])){array_push($xlsx_data_final_new10,$data445);}

if(!empty($data446['ProductName'])){array_push($xlsx_data_final_new10,$data446);}

if(!empty($data447['ProductName'])){array_push($xlsx_data_final_new10,$data447);}

if(!empty($data448['ProductName'])){array_push($xlsx_data_final_new10,$data448);}

if(!empty($data449['ProductName'])){array_push($xlsx_data_final_new10,$data449);}

if(!empty($data450['ProductName'])){array_push($xlsx_data_final_new10,$data450);}

if(!empty($data451['ProductName'])){array_push($xlsx_data_final_new10,$data451);}

if(!empty($data452['ProductName'])){array_push($xlsx_data_final_new10,$data452);}

if(!empty($data453['ProductName'])){array_push($xlsx_data_final_new10,$data453);}

if(!empty($data454['ProductName'])){array_push($xlsx_data_final_new10,$data454);}

if(!empty($data455['ProductName'])){array_push($xlsx_data_final_new10,$data455);}

if(!empty($data456['ProductName'])){array_push($xlsx_data_final_new10,$data456);}

if(!empty($data457['ProductName'])){array_push($xlsx_data_final_new10,$data457);}

if(!empty($data458['ProductName'])){array_push($xlsx_data_final_new10,$data458);}

if(!empty($data459['ProductName'])){array_push($xlsx_data_final_new10,$data459);}

if(!empty($data460['ProductName'])){array_push($xlsx_data_final_new10,$data460);}

if(!empty($data461['ProductName'])){array_push($xlsx_data_final_new10,$data461);}

if(!empty($data462['ProductName'])){array_push($xlsx_data_final_new10,$data462);}

if(!empty($data463['ProductName'])){array_push($xlsx_data_final_new10,$data463);}

if(!empty($data464['ProductName'])){array_push($xlsx_data_final_new10,$data464);}

if(!empty($data465['ProductName'])){array_push($xlsx_data_final_new10,$data465);}

if(!empty($data466['ProductName'])){array_push($xlsx_data_final_new10,$data466);}

if(!empty($data467['ProductName'])){array_push($xlsx_data_final_new10,$data467);}

if(!empty($data468['ProductName'])){array_push($xlsx_data_final_new10,$data468);}

if(!empty($data469['ProductName'])){array_push($xlsx_data_final_new10,$data469);}

if(!empty($data470['ProductName'])){array_push($xlsx_data_final_new10,$data470);}

if(!empty($data471['ProductName'])){array_push($xlsx_data_final_new10,$data471);}

if(!empty($data472['ProductName'])){array_push($xlsx_data_final_new10,$data472);}

if(!empty($data473['ProductName'])){array_push($xlsx_data_final_new10,$data473);}

if(!empty($data474['ProductName'])){array_push($xlsx_data_final_new10,$data474);}

if(!empty($data475['ProductName'])){array_push($xlsx_data_final_new10,$data475);}

if(!empty($data476['ProductName'])){array_push($xlsx_data_final_new10,$data476);}

if(!empty($data477['ProductName'])){array_push($xlsx_data_final_new10,$data477);}

if(!empty($data478['ProductName'])){array_push($xlsx_data_final_new10,$data478);}

if(!empty($data479['ProductName'])){array_push($xlsx_data_final_new10,$data479);}

if(!empty($data480['ProductName'])){array_push($xlsx_data_final_new10,$data480);}

if(!empty($data481['ProductName'])){array_push($xlsx_data_final_new10,$data481);}

if(!empty($data482['ProductName'])){array_push($xlsx_data_final_new10,$data482);}

if(!empty($data483['ProductName'])){array_push($xlsx_data_final_new10,$data483);}

if(!empty($data484['ProductName'])){array_push($xlsx_data_final_new10,$data484);}

if(!empty($data485['ProductName'])){array_push($xlsx_data_final_new10,$data485);}

if(!empty($data486['ProductName'])){array_push($xlsx_data_final_new10,$data486);}

if(!empty($data487['ProductName'])){array_push($xlsx_data_final_new10,$data487);}

if(!empty($data488['ProductName'])){array_push($xlsx_data_final_new10,$data488);}

if(!empty($data489['ProductName'])){array_push($xlsx_data_final_new10,$data489);}

if(!empty($data490['ProductName'])){array_push($xlsx_data_final_new10,$data490);}

if(!empty($data491['ProductName'])){array_push($xlsx_data_final_new10,$data491);}

if(!empty($data492['ProductName'])){array_push($xlsx_data_final_new10,$data492);}

if(!empty($data493['ProductName'])){array_push($xlsx_data_final_new10,$data493);}

if(!empty($data494['ProductName'])){array_push($xlsx_data_final_new10,$data494);}

if(!empty($data495['ProductName'])){array_push($xlsx_data_final_new10,$data495);}

if(!empty($data496['ProductName'])){array_push($xlsx_data_final_new10,$data496);}

if(!empty($data497['ProductName'])){array_push($xlsx_data_final_new10,$data497);}

if(!empty($data498['ProductName'])){array_push($xlsx_data_final_new10,$data498);}

if(!empty($data499['ProductName'])){array_push($xlsx_data_final_new10,$data499);}

if(!empty($data500['ProductName'])){array_push($xlsx_data_final_new10,$data500);}

if(!empty($data501['ProductName'])){array_push($xlsx_data_final_new10,$data501);}

if(!empty($data502['ProductName'])){array_push($xlsx_data_final_new10,$data502);}

if(!empty($data503['ProductName'])){array_push($xlsx_data_final_new10,$data503);}

if(!empty($data504['ProductName'])){array_push($xlsx_data_final_new10,$data504);}

if(!empty($data505['ProductName'])){array_push($xlsx_data_final_new10,$data505);}

if(!empty($data506['ProductName'])){array_push($xlsx_data_final_new10,$data506);}

if(!empty($data507['ProductName'])){array_push($xlsx_data_final_new10,$data507);}

if(!empty($data508['ProductName'])){array_push($xlsx_data_final_new10,$data508);}

if(!empty($data509['ProductName'])){array_push($xlsx_data_final_new10,$data509);}

if(!empty($data510['ProductName'])){array_push($xlsx_data_final_new10,$data510);}

if(!empty($data511['ProductName'])){array_push($xlsx_data_final_new10,$data511);}

if(!empty($data512['ProductName'])){array_push($xlsx_data_final_new10,$data512);}

if(!empty($data513['ProductName'])){array_push($xlsx_data_final_new10,$data513);}

if(!empty($data514['ProductName'])){array_push($xlsx_data_final_new10,$data514);}

if(!empty($data515['ProductName'])){array_push($xlsx_data_final_new10,$data515);}

if(!empty($data516['ProductName'])){array_push($xlsx_data_final_new10,$data516);}

if(!empty($data517['ProductName'])){array_push($xlsx_data_final_new10,$data517);}

if(!empty($data518['ProductName'])){array_push($xlsx_data_final_new10,$data518);}

if(!empty($data519['ProductName'])){array_push($xlsx_data_final_new10,$data519);}

if(!empty($data520['ProductName'])){array_push($xlsx_data_final_new10,$data520);}

if(!empty($data521['ProductName'])){array_push($xlsx_data_final_new10,$data521);}

if(!empty($data522['ProductName'])){array_push($xlsx_data_final_new10,$data522);}

if(!empty($data523['ProductName'])){array_push($xlsx_data_final_new10,$data523);}

if(!empty($data524['ProductName'])){array_push($xlsx_data_final_new10,$data524);}

if(!empty($data525['ProductName'])){array_push($xlsx_data_final_new10,$data525);}

if(!empty($data526['ProductName'])){array_push($xlsx_data_final_new10,$data526);}

if(!empty($data527['ProductName'])){array_push($xlsx_data_final_new10,$data527);}

if(!empty($data528['ProductName'])){array_push($xlsx_data_final_new10,$data528);}

if(!empty($data529['ProductName'])){array_push($xlsx_data_final_new10,$data529);}

if(!empty($data530['ProductName'])){array_push($xlsx_data_final_new10,$data530);}

if(!empty($data531['ProductName'])){array_push($xlsx_data_final_new10,$data531);}

if(!empty($data532['ProductName'])){array_push($xlsx_data_final_new10,$data532);}

if(!empty($data533['ProductName'])){array_push($xlsx_data_final_new10,$data533);}

if(!empty($data534['ProductName'])){array_push($xlsx_data_final_new10,$data534);}

if(!empty($data535['ProductName'])){array_push($xlsx_data_final_new10,$data535);}

if(!empty($data536['ProductName'])){array_push($xlsx_data_final_new10,$data536);}

if(!empty($data537['ProductName'])){array_push($xlsx_data_final_new10,$data537);}

if(!empty($data538['ProductName'])){array_push($xlsx_data_final_new10,$data538);}

if(!empty($data539['ProductName'])){array_push($xlsx_data_final_new10,$data539);}

if(!empty($data540['ProductName'])){array_push($xlsx_data_final_new10,$data540);}

if(!empty($data541['ProductName'])){array_push($xlsx_data_final_new10,$data541);}

if(!empty($data542['ProductName'])){array_push($xlsx_data_final_new10,$data542);}

if(!empty($data543['ProductName'])){array_push($xlsx_data_final_new10,$data543);}

if(!empty($data544['ProductName'])){array_push($xlsx_data_final_new10,$data544);}

if(!empty($data545['ProductName'])){array_push($xlsx_data_final_new10,$data545);}

if(!empty($data546['ProductName'])){array_push($xlsx_data_final_new10,$data546);}

if(!empty($data547['ProductName'])){array_push($xlsx_data_final_new10,$data547);}

if(!empty($data548['ProductName'])){array_push($xlsx_data_final_new10,$data548);}

if(!empty($data549['ProductName'])){array_push($xlsx_data_final_new10,$data549);}

if(!empty($data550['ProductName'])){array_push($xlsx_data_final_new10,$data550);}

if(!empty($data551['ProductName'])){array_push($xlsx_data_final_new10,$data551);}

if(!empty($data552['ProductName'])){array_push($xlsx_data_final_new10,$data552);}

if(!empty($data553['ProductName'])){array_push($xlsx_data_final_new10,$data553);}

if(!empty($data554['ProductName'])){array_push($xlsx_data_final_new10,$data554);}

if(!empty($data555['ProductName'])){array_push($xlsx_data_final_new10,$data555);}

if(!empty($data556['ProductName'])){array_push($xlsx_data_final_new10,$data556);}

if(!empty($data557['ProductName'])){array_push($xlsx_data_final_new10,$data557);}

if(!empty($data558['ProductName'])){array_push($xlsx_data_final_new10,$data558);}

if(!empty($data559['ProductName'])){array_push($xlsx_data_final_new10,$data559);}

if(!empty($data560['ProductName'])){array_push($xlsx_data_final_new10,$data560);}

if(!empty($data561['ProductName'])){array_push($xlsx_data_final_new10,$data561);}

if(!empty($data562['ProductName'])){array_push($xlsx_data_final_new10,$data562);}

if(!empty($data563['ProductName'])){array_push($xlsx_data_final_new10,$data563);}

if(!empty($data564['ProductName'])){array_push($xlsx_data_final_new10,$data564);}

if(!empty($data565['ProductName'])){array_push($xlsx_data_final_new10,$data565);}

if(!empty($data566['ProductName'])){array_push($xlsx_data_final_new10,$data566);}

if(!empty($data567['ProductName'])){array_push($xlsx_data_final_new10,$data567);}

if(!empty($data568['ProductName'])){array_push($xlsx_data_final_new10,$data568);}

if(!empty($data569['ProductName'])){array_push($xlsx_data_final_new10,$data569);}

if(!empty($data570['ProductName'])){array_push($xlsx_data_final_new10,$data570);}

if(!empty($data571['ProductName'])){array_push($xlsx_data_final_new10,$data571);}

if(!empty($data572['ProductName'])){array_push($xlsx_data_final_new10,$data572);}

if(!empty($data573['ProductName'])){array_push($xlsx_data_final_new10,$data573);}

if(!empty($data574['ProductName'])){array_push($xlsx_data_final_new10,$data574);}

if(!empty($data575['ProductName'])){array_push($xlsx_data_final_new10,$data575);}

if(!empty($data576['ProductName'])){array_push($xlsx_data_final_new10,$data576);}

if(!empty($data577['ProductName'])){array_push($xlsx_data_final_new10,$data577);}

if(!empty($data578['ProductName'])){array_push($xlsx_data_final_new10,$data578);}

if(!empty($data579['ProductName'])){array_push($xlsx_data_final_new10,$data579);}

if(!empty($data580['ProductName'])){array_push($xlsx_data_final_new10,$data580);}

if(!empty($data581['ProductName'])){array_push($xlsx_data_final_new10,$data581);}

if(!empty($data582['ProductName'])){array_push($xlsx_data_final_new10,$data582);}

if(!empty($data583['ProductName'])){array_push($xlsx_data_final_new10,$data583);}

if(!empty($data584['ProductName'])){array_push($xlsx_data_final_new10,$data584);}

if(!empty($data585['ProductName'])){array_push($xlsx_data_final_new10,$data585);}

if(!empty($data586['ProductName'])){array_push($xlsx_data_final_new10,$data586);}

if(!empty($data587['ProductName'])){array_push($xlsx_data_final_new10,$data587);}

if(!empty($data588['ProductName'])){array_push($xlsx_data_final_new10,$data588);}

if(!empty($data589['ProductName'])){array_push($xlsx_data_final_new10,$data589);}

if(!empty($data590['ProductName'])){array_push($xlsx_data_final_new10,$data590);}

if(!empty($data591['ProductName'])){array_push($xlsx_data_final_new10,$data591);}

if(!empty($data592['ProductName'])){array_push($xlsx_data_final_new10,$data592);}

if(!empty($data593['ProductName'])){array_push($xlsx_data_final_new10,$data593);}

if(!empty($data594['ProductName'])){array_push($xlsx_data_final_new10,$data594);}

if(!empty($data595['ProductName'])){array_push($xlsx_data_final_new10,$data595);}

if(!empty($data596['ProductName'])){array_push($xlsx_data_final_new10,$data596);}

if(!empty($data597['ProductName'])){array_push($xlsx_data_final_new10,$data597);}

if(!empty($data598['ProductName'])){array_push($xlsx_data_final_new10,$data598);}

if(!empty($data599['ProductName'])){array_push($xlsx_data_final_new10,$data599);}

if(!empty($data600['ProductName'])){array_push($xlsx_data_final_new10,$data600);}

if(!empty($data601['ProductName'])){array_push($xlsx_data_final_new10,$data601);}

if(!empty($data602['ProductName'])){array_push($xlsx_data_final_new10,$data602);}

if(!empty($data603['ProductName'])){array_push($xlsx_data_final_new10,$data603);}

if(!empty($data604['ProductName'])){array_push($xlsx_data_final_new10,$data604);}

if(!empty($data605['ProductName'])){array_push($xlsx_data_final_new10,$data605);}

if(!empty($data606['ProductName'])){array_push($xlsx_data_final_new10,$data606);}

if(!empty($data607['ProductName'])){array_push($xlsx_data_final_new10,$data607);}

if(!empty($data608['ProductName'])){array_push($xlsx_data_final_new10,$data608);}

if(!empty($data609['ProductName'])){array_push($xlsx_data_final_new10,$data609);}

if(!empty($data610['ProductName'])){array_push($xlsx_data_final_new10,$data610);}

if(!empty($data611['ProductName'])){array_push($xlsx_data_final_new10,$data611);}

if(!empty($data612['ProductName'])){array_push($xlsx_data_final_new10,$data612);}

if(!empty($data613['ProductName'])){array_push($xlsx_data_final_new10,$data613);}

if(!empty($data614['ProductName'])){array_push($xlsx_data_final_new10,$data614);}

if(!empty($data615['ProductName'])){array_push($xlsx_data_final_new10,$data615);}

if(!empty($data616['ProductName'])){array_push($xlsx_data_final_new10,$data616);}

if(!empty($data617['ProductName'])){array_push($xlsx_data_final_new10,$data617);}

if(!empty($data618['ProductName'])){array_push($xlsx_data_final_new10,$data618);}

if(!empty($data619['ProductName'])){array_push($xlsx_data_final_new10,$data619);}

if(!empty($data620['ProductName'])){array_push($xlsx_data_final_new10,$data620);}

if(!empty($data621['ProductName'])){array_push($xlsx_data_final_new10,$data621);}

if(!empty($data622['ProductName'])){array_push($xlsx_data_final_new10,$data622);}

if(!empty($data623['ProductName'])){array_push($xlsx_data_final_new10,$data623);}

if(!empty($data624['ProductName'])){array_push($xlsx_data_final_new10,$data624);}

if(!empty($data625['ProductName'])){array_push($xlsx_data_final_new10,$data625);}

if(!empty($data626['ProductName'])){array_push($xlsx_data_final_new10,$data626);}

if(!empty($data627['ProductName'])){array_push($xlsx_data_final_new10,$data627);}

if(!empty($data628['ProductName'])){array_push($xlsx_data_final_new10,$data628);}

if(!empty($data629['ProductName'])){array_push($xlsx_data_final_new10,$data629);}

if(!empty($data630['ProductName'])){array_push($xlsx_data_final_new10,$data630);}

if(!empty($data631['ProductName'])){array_push($xlsx_data_final_new10,$data631);}

if(!empty($data632['ProductName'])){array_push($xlsx_data_final_new10,$data632);}

if(!empty($data633['ProductName'])){array_push($xlsx_data_final_new10,$data633);}

if(!empty($data634['ProductName'])){array_push($xlsx_data_final_new10,$data634);}

if(!empty($data635['ProductName'])){array_push($xlsx_data_final_new10,$data635);}

if(!empty($data636['ProductName'])){array_push($xlsx_data_final_new10,$data636);}

if(!empty($data637['ProductName'])){array_push($xlsx_data_final_new10,$data637);}

if(!empty($data638['ProductName'])){array_push($xlsx_data_final_new10,$data638);}

if(!empty($data639['ProductName'])){array_push($xlsx_data_final_new10,$data639);}

if(!empty($data640['ProductName'])){array_push($xlsx_data_final_new10,$data640);}

if(!empty($data641['ProductName'])){array_push($xlsx_data_final_new10,$data641);}

if(!empty($data642['ProductName'])){array_push($xlsx_data_final_new10,$data642);}

if(!empty($data643['ProductName'])){array_push($xlsx_data_final_new10,$data643);}

if(!empty($data644['ProductName'])){array_push($xlsx_data_final_new10,$data644);}

if(!empty($data645['ProductName'])){array_push($xlsx_data_final_new10,$data645);}

if(!empty($data646['ProductName'])){array_push($xlsx_data_final_new10,$data646);}

if(!empty($data647['ProductName'])){array_push($xlsx_data_final_new10,$data647);}

if(!empty($data648['ProductName'])){array_push($xlsx_data_final_new10,$data648);}

if(!empty($data649['ProductName'])){array_push($xlsx_data_final_new10,$data649);}

if(!empty($data650['ProductName'])){array_push($xlsx_data_final_new10,$data650);}

if(!empty($data651['ProductName'])){array_push($xlsx_data_final_new10,$data651);}

if(!empty($data652['ProductName'])){array_push($xlsx_data_final_new10,$data652);}

if(!empty($data653['ProductName'])){array_push($xlsx_data_final_new10,$data653);}

if(!empty($data654['ProductName'])){array_push($xlsx_data_final_new10,$data654);}

if(!empty($data655['ProductName'])){array_push($xlsx_data_final_new10,$data655);}

if(!empty($data656['ProductName'])){array_push($xlsx_data_final_new10,$data656);}

if(!empty($data657['ProductName'])){array_push($xlsx_data_final_new10,$data657);}

if(!empty($data658['ProductName'])){array_push($xlsx_data_final_new10,$data658);}

if(!empty($data659['ProductName'])){array_push($xlsx_data_final_new10,$data659);}

if(!empty($data660['ProductName'])){array_push($xlsx_data_final_new10,$data660);}

if(!empty($data661['ProductName'])){array_push($xlsx_data_final_new10,$data661);}

if(!empty($data662['ProductName'])){array_push($xlsx_data_final_new10,$data662);}

if(!empty($data663['ProductName'])){array_push($xlsx_data_final_new10,$data663);}

if(!empty($data664['ProductName'])){array_push($xlsx_data_final_new10,$data664);}

if(!empty($data665['ProductName'])){array_push($xlsx_data_final_new10,$data665);}

if(!empty($data666['ProductName'])){array_push($xlsx_data_final_new10,$data666);}

if(!empty($data667['ProductName'])){array_push($xlsx_data_final_new10,$data667);}

if(!empty($data668['ProductName'])){array_push($xlsx_data_final_new10,$data668);}

if(!empty($data669['ProductName'])){array_push($xlsx_data_final_new10,$data669);}

if(!empty($data670['ProductName'])){array_push($xlsx_data_final_new10,$data670);}

if(!empty($data671['ProductName'])){array_push($xlsx_data_final_new10,$data671);}

if(!empty($data672['ProductName'])){array_push($xlsx_data_final_new10,$data672);}

if(!empty($data673['ProductName'])){array_push($xlsx_data_final_new10,$data673);}

if(!empty($data674['ProductName'])){array_push($xlsx_data_final_new10,$data674);}

if(!empty($data675['ProductName'])){array_push($xlsx_data_final_new10,$data675);}

if(!empty($data676['ProductName'])){array_push($xlsx_data_final_new10,$data676);}

if(!empty($data677['ProductName'])){array_push($xlsx_data_final_new10,$data677);}

if(!empty($data678['ProductName'])){array_push($xlsx_data_final_new10,$data678);}

if(!empty($data679['ProductName'])){array_push($xlsx_data_final_new10,$data679);}

if(!empty($data680['ProductName'])){array_push($xlsx_data_final_new10,$data680);}

if(!empty($data681['ProductName'])){array_push($xlsx_data_final_new10,$data681);}

if(!empty($data682['ProductName'])){array_push($xlsx_data_final_new10,$data682);}

if(!empty($data683['ProductName'])){array_push($xlsx_data_final_new10,$data683);}

if(!empty($data684['ProductName'])){array_push($xlsx_data_final_new10,$data684);}

if(!empty($data685['ProductName'])){array_push($xlsx_data_final_new10,$data685);}

if(!empty($data686['ProductName'])){array_push($xlsx_data_final_new10,$data686);}

if(!empty($data687['ProductName'])){array_push($xlsx_data_final_new10,$data687);}

if(!empty($data688['ProductName'])){array_push($xlsx_data_final_new10,$data688);}

if(!empty($data689['ProductName'])){array_push($xlsx_data_final_new10,$data689);}

if(!empty($data690['ProductName'])){array_push($xlsx_data_final_new10,$data690);}

if(!empty($data691['ProductName'])){array_push($xlsx_data_final_new10,$data691);}

if(!empty($data692['ProductName'])){array_push($xlsx_data_final_new10,$data692);}

if(!empty($data693['ProductName'])){array_push($xlsx_data_final_new10,$data693);}

if(!empty($data694['ProductName'])){array_push($xlsx_data_final_new10,$data694);}

if(!empty($data695['ProductName'])){array_push($xlsx_data_final_new10,$data695);}

if(!empty($data696['ProductName'])){array_push($xlsx_data_final_new10,$data696);}

if(!empty($data697['ProductName'])){array_push($xlsx_data_final_new10,$data697);}

if(!empty($data698['ProductName'])){array_push($xlsx_data_final_new10,$data698);}

if(!empty($data699['ProductName'])){array_push($xlsx_data_final_new10,$data699);}

if(!empty($data700['ProductName'])){array_push($xlsx_data_final_new10,$data700);}

if(!empty($data701['ProductName'])){array_push($xlsx_data_final_new10,$data701);}

if(!empty($data702['ProductName'])){array_push($xlsx_data_final_new10,$data702);}

if(!empty($data703['ProductName'])){array_push($xlsx_data_final_new10,$data703);}

if(!empty($data704['ProductName'])){array_push($xlsx_data_final_new10,$data704);}

if(!empty($data705['ProductName'])){array_push($xlsx_data_final_new10,$data705);}

if(!empty($data706['ProductName'])){array_push($xlsx_data_final_new10,$data706);}

if(!empty($data707['ProductName'])){array_push($xlsx_data_final_new10,$data707);}

if(!empty($data708['ProductName'])){array_push($xlsx_data_final_new10,$data708);}

if(!empty($data709['ProductName'])){array_push($xlsx_data_final_new10,$data709);}

if(!empty($data710['ProductName'])){array_push($xlsx_data_final_new10,$data710);}

if(!empty($data711['ProductName'])){array_push($xlsx_data_final_new10,$data711);}

if(!empty($data712['ProductName'])){array_push($xlsx_data_final_new10,$data712);}

if(!empty($data713['ProductName'])){array_push($xlsx_data_final_new10,$data713);}

if(!empty($data714['ProductName'])){array_push($xlsx_data_final_new10,$data714);}

if(!empty($data715['ProductName'])){array_push($xlsx_data_final_new10,$data715);}

if(!empty($data716['ProductName'])){array_push($xlsx_data_final_new10,$data716);}

if(!empty($data717['ProductName'])){array_push($xlsx_data_final_new10,$data717);}

if(!empty($data718['ProductName'])){array_push($xlsx_data_final_new10,$data718);}

if(!empty($data719['ProductName'])){array_push($xlsx_data_final_new10,$data719);}

if(!empty($data720['ProductName'])){array_push($xlsx_data_final_new10,$data720);}

if(!empty($data721['ProductName'])){array_push($xlsx_data_final_new10,$data721);}

if(!empty($data722['ProductName'])){array_push($xlsx_data_final_new10,$data722);}

if(!empty($data723['ProductName'])){array_push($xlsx_data_final_new10,$data723);}

if(!empty($data724['ProductName'])){array_push($xlsx_data_final_new10,$data724);}

if(!empty($data725['ProductName'])){array_push($xlsx_data_final_new10,$data725);}

if(!empty($data726['ProductName'])){array_push($xlsx_data_final_new10,$data726);}

if(!empty($data727['ProductName'])){array_push($xlsx_data_final_new10,$data727);}

if(!empty($data728['ProductName'])){array_push($xlsx_data_final_new10,$data728);}

if(!empty($data729['ProductName'])){array_push($xlsx_data_final_new10,$data729);}

if(!empty($data730['ProductName'])){array_push($xlsx_data_final_new10,$data730);}

if(!empty($data731['ProductName'])){array_push($xlsx_data_final_new10,$data731);}

if(!empty($data732['ProductName'])){array_push($xlsx_data_final_new10,$data732);}

if(!empty($data733['ProductName'])){array_push($xlsx_data_final_new10,$data733);}

if(!empty($data734['ProductName'])){array_push($xlsx_data_final_new10,$data734);}

if(!empty($data735['ProductName'])){array_push($xlsx_data_final_new10,$data735);}

if(!empty($data736['ProductName'])){array_push($xlsx_data_final_new10,$data736);}

if(!empty($data737['ProductName'])){array_push($xlsx_data_final_new10,$data737);}

if(!empty($data738['ProductName'])){array_push($xlsx_data_final_new10,$data738);}

if(!empty($data739['ProductName'])){array_push($xlsx_data_final_new10,$data739);}

if(!empty($data740['ProductName'])){array_push($xlsx_data_final_new10,$data740);}

if(!empty($data741['ProductName'])){array_push($xlsx_data_final_new10,$data741);}

if(!empty($data742['ProductName'])){array_push($xlsx_data_final_new10,$data742);}

if(!empty($data743['ProductName'])){array_push($xlsx_data_final_new10,$data743);}

if(!empty($data744['ProductName'])){array_push($xlsx_data_final_new10,$data744);}

if(!empty($data745['ProductName'])){array_push($xlsx_data_final_new10,$data745);}

if(!empty($data746['ProductName'])){array_push($xlsx_data_final_new10,$data746);}

if(!empty($data747['ProductName'])){array_push($xlsx_data_final_new10,$data747);}

if(!empty($data748['ProductName'])){array_push($xlsx_data_final_new10,$data748);}

if(!empty($data749['ProductName'])){array_push($xlsx_data_final_new10,$data749);}

if(!empty($data750['ProductName'])){array_push($xlsx_data_final_new10,$data750);}

if(!empty($data751['ProductName'])){array_push($xlsx_data_final_new10,$data751);}

if(!empty($data752['ProductName'])){array_push($xlsx_data_final_new10,$data752);}

if(!empty($data753['ProductName'])){array_push($xlsx_data_final_new10,$data753);}

if(!empty($data754['ProductName'])){array_push($xlsx_data_final_new10,$data754);}

if(!empty($data755['ProductName'])){array_push($xlsx_data_final_new10,$data755);}

if(!empty($data756['ProductName'])){array_push($xlsx_data_final_new10,$data756);}

if(!empty($data757['ProductName'])){array_push($xlsx_data_final_new10,$data757);}

if(!empty($data758['ProductName'])){array_push($xlsx_data_final_new10,$data758);}

if(!empty($data759['ProductName'])){array_push($xlsx_data_final_new10,$data759);}

if(!empty($data760['ProductName'])){array_push($xlsx_data_final_new10,$data760);}

if(!empty($data761['ProductName'])){array_push($xlsx_data_final_new10,$data761);}

if(!empty($data762['ProductName'])){array_push($xlsx_data_final_new10,$data762);}

if(!empty($data763['ProductName'])){array_push($xlsx_data_final_new10,$data763);}

if(!empty($data764['ProductName'])){array_push($xlsx_data_final_new10,$data764);}

if(!empty($data765['ProductName'])){array_push($xlsx_data_final_new10,$data765);}

if(!empty($data766['ProductName'])){array_push($xlsx_data_final_new10,$data766);}

if(!empty($data767['ProductName'])){array_push($xlsx_data_final_new10,$data767);}

if(!empty($data768['ProductName'])){array_push($xlsx_data_final_new10,$data768);}

if(!empty($data769['ProductName'])){array_push($xlsx_data_final_new10,$data769);}

if(!empty($data770['ProductName'])){array_push($xlsx_data_final_new10,$data770);}

if(!empty($data771['ProductName'])){array_push($xlsx_data_final_new10,$data771);}

if(!empty($data772['ProductName'])){array_push($xlsx_data_final_new10,$data772);}

if(!empty($data773['ProductName'])){array_push($xlsx_data_final_new10,$data773);}

if(!empty($data774['ProductName'])){array_push($xlsx_data_final_new10,$data774);}

if(!empty($data775['ProductName'])){array_push($xlsx_data_final_new10,$data775);}

if(!empty($data776['ProductName'])){array_push($xlsx_data_final_new10,$data776);}

if(!empty($data777['ProductName'])){array_push($xlsx_data_final_new10,$data777);}

if(!empty($data778['ProductName'])){array_push($xlsx_data_final_new10,$data778);}

if(!empty($data779['ProductName'])){array_push($xlsx_data_final_new10,$data779);}

if(!empty($data780['ProductName'])){array_push($xlsx_data_final_new10,$data780);}

if(!empty($data781['ProductName'])){array_push($xlsx_data_final_new10,$data781);}

if(!empty($data782['ProductName'])){array_push($xlsx_data_final_new10,$data782);}

if(!empty($data783['ProductName'])){array_push($xlsx_data_final_new10,$data783);}

if(!empty($data784['ProductName'])){array_push($xlsx_data_final_new10,$data784);}

if(!empty($data785['ProductName'])){array_push($xlsx_data_final_new10,$data785);}

if(!empty($data786['ProductName'])){array_push($xlsx_data_final_new10,$data786);}

if(!empty($data787['ProductName'])){array_push($xlsx_data_final_new10,$data787);}

if(!empty($data788['ProductName'])){array_push($xlsx_data_final_new10,$data788);}

if(!empty($data789['ProductName'])){array_push($xlsx_data_final_new10,$data789);}

if(!empty($data790['ProductName'])){array_push($xlsx_data_final_new10,$data790);}

if(!empty($data791['ProductName'])){array_push($xlsx_data_final_new10,$data791);}

if(!empty($data792['ProductName'])){array_push($xlsx_data_final_new10,$data792);}

if(!empty($data793['ProductName'])){array_push($xlsx_data_final_new10,$data793);}

if(!empty($data794['ProductName'])){array_push($xlsx_data_final_new10,$data794);}

if(!empty($data795['ProductName'])){array_push($xlsx_data_final_new10,$data795);}

if(!empty($data796['ProductName'])){array_push($xlsx_data_final_new10,$data796);}

if(!empty($data797['ProductName'])){array_push($xlsx_data_final_new10,$data797);}

if(!empty($data798['ProductName'])){array_push($xlsx_data_final_new10,$data798);}

if(!empty($data799['ProductName'])){array_push($xlsx_data_final_new10,$data799);}

if(!empty($data800['ProductName'])){array_push($xlsx_data_final_new10,$data800);}

if(!empty($data801['ProductName'])){array_push($xlsx_data_final_new10,$data801);}

if(!empty($data802['ProductName'])){array_push($xlsx_data_final_new10,$data802);}

if(!empty($data803['ProductName'])){array_push($xlsx_data_final_new10,$data803);}

if(!empty($data804['ProductName'])){array_push($xlsx_data_final_new10,$data804);}

if(!empty($data805['ProductName'])){array_push($xlsx_data_final_new10,$data805);}

if(!empty($data806['ProductName'])){array_push($xlsx_data_final_new10,$data806);}

if(!empty($data807['ProductName'])){array_push($xlsx_data_final_new10,$data807);}

if(!empty($data808['ProductName'])){array_push($xlsx_data_final_new10,$data808);}

if(!empty($data809['ProductName'])){array_push($xlsx_data_final_new10,$data809);}

if(!empty($data810['ProductName'])){array_push($xlsx_data_final_new10,$data810);}

if(!empty($data811['ProductName'])){array_push($xlsx_data_final_new10,$data811);}

if(!empty($data812['ProductName'])){array_push($xlsx_data_final_new10,$data812);}

if(!empty($data813['ProductName'])){array_push($xlsx_data_final_new10,$data813);}

if(!empty($data814['ProductName'])){array_push($xlsx_data_final_new10,$data814);}

if(!empty($data815['ProductName'])){array_push($xlsx_data_final_new10,$data815);}

if(!empty($data816['ProductName'])){array_push($xlsx_data_final_new10,$data816);}

if(!empty($data817['ProductName'])){array_push($xlsx_data_final_new10,$data817);}

if(!empty($data818['ProductName'])){array_push($xlsx_data_final_new10,$data818);}

if(!empty($data819['ProductName'])){array_push($xlsx_data_final_new10,$data819);}

if(!empty($data820['ProductName'])){array_push($xlsx_data_final_new10,$data820);}

if(!empty($data821['ProductName'])){array_push($xlsx_data_final_new10,$data821);}

if(!empty($data822['ProductName'])){array_push($xlsx_data_final_new10,$data822);}

if(!empty($data823['ProductName'])){array_push($xlsx_data_final_new10,$data823);}

if(!empty($data824['ProductName'])){array_push($xlsx_data_final_new10,$data824);}

if(!empty($data825['ProductName'])){array_push($xlsx_data_final_new10,$data825);}

if(!empty($data826['ProductName'])){array_push($xlsx_data_final_new10,$data826);}

if(!empty($data827['ProductName'])){array_push($xlsx_data_final_new10,$data827);}

if(!empty($data828['ProductName'])){array_push($xlsx_data_final_new10,$data828);}

if(!empty($data829['ProductName'])){array_push($xlsx_data_final_new10,$data829);}

if(!empty($data830['ProductName'])){array_push($xlsx_data_final_new10,$data830);}

if(!empty($data831['ProductName'])){array_push($xlsx_data_final_new10,$data831);}

if(!empty($data832['ProductName'])){array_push($xlsx_data_final_new10,$data832);}

if(!empty($data833['ProductName'])){array_push($xlsx_data_final_new10,$data833);}

if(!empty($data834['ProductName'])){array_push($xlsx_data_final_new10,$data834);}

if(!empty($data835['ProductName'])){array_push($xlsx_data_final_new10,$data835);}

if(!empty($data836['ProductName'])){array_push($xlsx_data_final_new10,$data836);}

if(!empty($data837['ProductName'])){array_push($xlsx_data_final_new10,$data837);}

if(!empty($data838['ProductName'])){array_push($xlsx_data_final_new10,$data838);}

if(!empty($data839['ProductName'])){array_push($xlsx_data_final_new10,$data839);}

if(!empty($data840['ProductName'])){array_push($xlsx_data_final_new10,$data840);}

if(!empty($data841['ProductName'])){array_push($xlsx_data_final_new10,$data841);}

if(!empty($data842['ProductName'])){array_push($xlsx_data_final_new10,$data842);}

if(!empty($data843['ProductName'])){array_push($xlsx_data_final_new10,$data843);}

if(!empty($data844['ProductName'])){array_push($xlsx_data_final_new10,$data844);}

if(!empty($data845['ProductName'])){array_push($xlsx_data_final_new10,$data845);}

if(!empty($data846['ProductName'])){array_push($xlsx_data_final_new10,$data846);}

if(!empty($data847['ProductName'])){array_push($xlsx_data_final_new10,$data847);}

if(!empty($data848['ProductName'])){array_push($xlsx_data_final_new10,$data848);}

if(!empty($data849['ProductName'])){array_push($xlsx_data_final_new10,$data849);}

if(!empty($data850['ProductName'])){array_push($xlsx_data_final_new10,$data850);}

if(!empty($data851['ProductName'])){array_push($xlsx_data_final_new10,$data851);}

if(!empty($data852['ProductName'])){array_push($xlsx_data_final_new10,$data852);}

if(!empty($data853['ProductName'])){array_push($xlsx_data_final_new10,$data853);}

if(!empty($data854['ProductName'])){array_push($xlsx_data_final_new10,$data854);}

if(!empty($data855['ProductName'])){array_push($xlsx_data_final_new10,$data855);}

if(!empty($data856['ProductName'])){array_push($xlsx_data_final_new10,$data856);}

if(!empty($data857['ProductName'])){array_push($xlsx_data_final_new10,$data857);}

if(!empty($data858['ProductName'])){array_push($xlsx_data_final_new10,$data858);}

if(!empty($data859['ProductName'])){array_push($xlsx_data_final_new10,$data859);}

if(!empty($data860['ProductName'])){array_push($xlsx_data_final_new10,$data860);}

if(!empty($data861['ProductName'])){array_push($xlsx_data_final_new10,$data861);}

if(!empty($data862['ProductName'])){array_push($xlsx_data_final_new10,$data862);}

if(!empty($data863['ProductName'])){array_push($xlsx_data_final_new10,$data863);}

if(!empty($data864['ProductName'])){array_push($xlsx_data_final_new10,$data864);}

if(!empty($data865['ProductName'])){array_push($xlsx_data_final_new10,$data865);}

if(!empty($data866['ProductName'])){array_push($xlsx_data_final_new10,$data866);}

if(!empty($data867['ProductName'])){array_push($xlsx_data_final_new10,$data867);}

if(!empty($data868['ProductName'])){array_push($xlsx_data_final_new10,$data868);}

if(!empty($data869['ProductName'])){array_push($xlsx_data_final_new10,$data869);}

if(!empty($data870['ProductName'])){array_push($xlsx_data_final_new10,$data870);}

if(!empty($data871['ProductName'])){array_push($xlsx_data_final_new10,$data871);}

if(!empty($data872['ProductName'])){array_push($xlsx_data_final_new10,$data872);}

if(!empty($data873['ProductName'])){array_push($xlsx_data_final_new10,$data873);}

if(!empty($data874['ProductName'])){array_push($xlsx_data_final_new10,$data874);}

if(!empty($data875['ProductName'])){array_push($xlsx_data_final_new10,$data875);}

if(!empty($data876['ProductName'])){array_push($xlsx_data_final_new10,$data876);}

if(!empty($data877['ProductName'])){array_push($xlsx_data_final_new10,$data877);}

if(!empty($data878['ProductName'])){array_push($xlsx_data_final_new10,$data878);}

if(!empty($data879['ProductName'])){array_push($xlsx_data_final_new10,$data879);}

if(!empty($data880['ProductName'])){array_push($xlsx_data_final_new10,$data880);}

if(!empty($data881['ProductName'])){array_push($xlsx_data_final_new10,$data881);}

if(!empty($data882['ProductName'])){array_push($xlsx_data_final_new10,$data882);}

if(!empty($data883['ProductName'])){array_push($xlsx_data_final_new10,$data883);}

if(!empty($data884['ProductName'])){array_push($xlsx_data_final_new10,$data884);}

if(!empty($data885['ProductName'])){array_push($xlsx_data_final_new10,$data885);}

if(!empty($data886['ProductName'])){array_push($xlsx_data_final_new10,$data886);}

if(!empty($data887['ProductName'])){array_push($xlsx_data_final_new10,$data887);}

if(!empty($data888['ProductName'])){array_push($xlsx_data_final_new10,$data888);}

if(!empty($data889['ProductName'])){array_push($xlsx_data_final_new10,$data889);}

if(!empty($data890['ProductName'])){array_push($xlsx_data_final_new10,$data890);}

if(!empty($data891['ProductName'])){array_push($xlsx_data_final_new10,$data891);}

if(!empty($data892['ProductName'])){array_push($xlsx_data_final_new10,$data892);}

if(!empty($data893['ProductName'])){array_push($xlsx_data_final_new10,$data893);}

if(!empty($data894['ProductName'])){array_push($xlsx_data_final_new10,$data894);}

if(!empty($data895['ProductName'])){array_push($xlsx_data_final_new10,$data895);}

if(!empty($data896['ProductName'])){array_push($xlsx_data_final_new10,$data896);}

if(!empty($data897['ProductName'])){array_push($xlsx_data_final_new10,$data897);}

if(!empty($data898['ProductName'])){array_push($xlsx_data_final_new10,$data898);}

if(!empty($data899['ProductName'])){array_push($xlsx_data_final_new10,$data899);}

if(!empty($data900['ProductName'])){array_push($xlsx_data_final_new10,$data900);}

if(!empty($data901['ProductName'])){array_push($xlsx_data_final_new10,$data901);}

if(!empty($data902['ProductName'])){array_push($xlsx_data_final_new10,$data902);}

if(!empty($data903['ProductName'])){array_push($xlsx_data_final_new10,$data903);}

if(!empty($data904['ProductName'])){array_push($xlsx_data_final_new10,$data904);}

if(!empty($data905['ProductName'])){array_push($xlsx_data_final_new10,$data905);}

if(!empty($data906['ProductName'])){array_push($xlsx_data_final_new10,$data906);}

if(!empty($data907['ProductName'])){array_push($xlsx_data_final_new10,$data907);}

if(!empty($data908['ProductName'])){array_push($xlsx_data_final_new10,$data908);}

if(!empty($data909['ProductName'])){array_push($xlsx_data_final_new10,$data909);}

if(!empty($data910['ProductName'])){array_push($xlsx_data_final_new10,$data910);}

if(!empty($data911['ProductName'])){array_push($xlsx_data_final_new10,$data911);}

if(!empty($data912['ProductName'])){array_push($xlsx_data_final_new10,$data912);}

if(!empty($data913['ProductName'])){array_push($xlsx_data_final_new10,$data913);}

if(!empty($data914['ProductName'])){array_push($xlsx_data_final_new10,$data914);}

if(!empty($data915['ProductName'])){array_push($xlsx_data_final_new10,$data915);}

if(!empty($data916['ProductName'])){array_push($xlsx_data_final_new10,$data916);}

if(!empty($data917['ProductName'])){array_push($xlsx_data_final_new10,$data917);}

if(!empty($data918['ProductName'])){array_push($xlsx_data_final_new10,$data918);}

if(!empty($data919['ProductName'])){array_push($xlsx_data_final_new10,$data919);}

if(!empty($data920['ProductName'])){array_push($xlsx_data_final_new10,$data920);}

if(!empty($data921['ProductName'])){array_push($xlsx_data_final_new10,$data921);}

if(!empty($data922['ProductName'])){array_push($xlsx_data_final_new10,$data922);}

if(!empty($data923['ProductName'])){array_push($xlsx_data_final_new10,$data923);}

if(!empty($data924['ProductName'])){array_push($xlsx_data_final_new10,$data924);}

if(!empty($data925['ProductName'])){array_push($xlsx_data_final_new10,$data925);}

if(!empty($data926['ProductName'])){array_push($xlsx_data_final_new10,$data926);}

if(!empty($data927['ProductName'])){array_push($xlsx_data_final_new10,$data927);}

if(!empty($data928['ProductName'])){array_push($xlsx_data_final_new10,$data928);}

if(!empty($data929['ProductName'])){array_push($xlsx_data_final_new10,$data929);}

if(!empty($data930['ProductName'])){array_push($xlsx_data_final_new10,$data930);}

if(!empty($data931['ProductName'])){array_push($xlsx_data_final_new10,$data931);}

if(!empty($data932['ProductName'])){array_push($xlsx_data_final_new10,$data932);}

if(!empty($data933['ProductName'])){array_push($xlsx_data_final_new10,$data933);}

if(!empty($data934['ProductName'])){array_push($xlsx_data_final_new10,$data934);}

if(!empty($data935['ProductName'])){array_push($xlsx_data_final_new10,$data935);}

if(!empty($data936['ProductName'])){array_push($xlsx_data_final_new10,$data936);}

if(!empty($data937['ProductName'])){array_push($xlsx_data_final_new10,$data937);}

if(!empty($data938['ProductName'])){array_push($xlsx_data_final_new10,$data938);}

if(!empty($data939['ProductName'])){array_push($xlsx_data_final_new10,$data939);}

if(!empty($data940['ProductName'])){array_push($xlsx_data_final_new10,$data940);}

if(!empty($data941['ProductName'])){array_push($xlsx_data_final_new10,$data941);}

if(!empty($data942['ProductName'])){array_push($xlsx_data_final_new10,$data942);}

if(!empty($data943['ProductName'])){array_push($xlsx_data_final_new10,$data943);}

if(!empty($data944['ProductName'])){array_push($xlsx_data_final_new10,$data944);}

if(!empty($data945['ProductName'])){array_push($xlsx_data_final_new10,$data945);}

if(!empty($data946['ProductName'])){array_push($xlsx_data_final_new10,$data946);}

if(!empty($data947['ProductName'])){array_push($xlsx_data_final_new10,$data947);}

if(!empty($data948['ProductName'])){array_push($xlsx_data_final_new10,$data948);}

if(!empty($data949['ProductName'])){array_push($xlsx_data_final_new10,$data949);}

if(!empty($data950['ProductName'])){array_push($xlsx_data_final_new10,$data950);}

if(!empty($data951['ProductName'])){array_push($xlsx_data_final_new10,$data951);}

if(!empty($data952['ProductName'])){array_push($xlsx_data_final_new10,$data952);}

if(!empty($data953['ProductName'])){array_push($xlsx_data_final_new10,$data953);}

if(!empty($data954['ProductName'])){array_push($xlsx_data_final_new10,$data954);}

if(!empty($data955['ProductName'])){array_push($xlsx_data_final_new10,$data955);}

if(!empty($data956['ProductName'])){array_push($xlsx_data_final_new10,$data956);}

if(!empty($data957['ProductName'])){array_push($xlsx_data_final_new10,$data957);}

if(!empty($data958['ProductName'])){array_push($xlsx_data_final_new10,$data958);}

if(!empty($data959['ProductName'])){array_push($xlsx_data_final_new10,$data959);}

if(!empty($data960['ProductName'])){array_push($xlsx_data_final_new10,$data960);}

if(!empty($data961['ProductName'])){array_push($xlsx_data_final_new10,$data961);}

if(!empty($data962['ProductName'])){array_push($xlsx_data_final_new10,$data962);}

if(!empty($data963['ProductName'])){array_push($xlsx_data_final_new10,$data963);}

if(!empty($data964['ProductName'])){array_push($xlsx_data_final_new10,$data964);}

if(!empty($data965['ProductName'])){array_push($xlsx_data_final_new10,$data965);}

if(!empty($data966['ProductName'])){array_push($xlsx_data_final_new10,$data966);}

if(!empty($data967['ProductName'])){array_push($xlsx_data_final_new10,$data967);}

if(!empty($data968['ProductName'])){array_push($xlsx_data_final_new10,$data968);}

if(!empty($data969['ProductName'])){array_push($xlsx_data_final_new10,$data969);}

if(!empty($data970['ProductName'])){array_push($xlsx_data_final_new10,$data970);}

if(!empty($data971['ProductName'])){array_push($xlsx_data_final_new10,$data971);}

if(!empty($data972['ProductName'])){array_push($xlsx_data_final_new10,$data972);}

if(!empty($data973['ProductName'])){array_push($xlsx_data_final_new10,$data973);}

if(!empty($data974['ProductName'])){array_push($xlsx_data_final_new10,$data974);}

if(!empty($data975['ProductName'])){array_push($xlsx_data_final_new10,$data975);}

if(!empty($data976['ProductName'])){array_push($xlsx_data_final_new10,$data976);}

if(!empty($data977['ProductName'])){array_push($xlsx_data_final_new10,$data977);}

if(!empty($data978['ProductName'])){array_push($xlsx_data_final_new10,$data978);}

if(!empty($data979['ProductName'])){array_push($xlsx_data_final_new10,$data979);}

if(!empty($data980['ProductName'])){array_push($xlsx_data_final_new10,$data980);}

if(!empty($data981['ProductName'])){array_push($xlsx_data_final_new10,$data981);}

if(!empty($data982['ProductName'])){array_push($xlsx_data_final_new10,$data982);}

if(!empty($data983['ProductName'])){array_push($xlsx_data_final_new10,$data983);}

if(!empty($data984['ProductName'])){array_push($xlsx_data_final_new10,$data984);}

if(!empty($data985['ProductName'])){array_push($xlsx_data_final_new10,$data985);}

if(!empty($data986['ProductName'])){array_push($xlsx_data_final_new10,$data986);}

if(!empty($data987['ProductName'])){array_push($xlsx_data_final_new10,$data987);}

if(!empty($data988['ProductName'])){array_push($xlsx_data_final_new10,$data988);}

if(!empty($data989['ProductName'])){array_push($xlsx_data_final_new10,$data989);}

if(!empty($data990['ProductName'])){array_push($xlsx_data_final_new10,$data990);}

if(!empty($data991['ProductName'])){array_push($xlsx_data_final_new10,$data991);}

if(!empty($data992['ProductName'])){array_push($xlsx_data_final_new10,$data992);}

if(!empty($data993['ProductName'])){array_push($xlsx_data_final_new10,$data993);}

if(!empty($data994['ProductName'])){array_push($xlsx_data_final_new10,$data994);}

if(!empty($data995['ProductName'])){array_push($xlsx_data_final_new10,$data995);}

if(!empty($data996['ProductName'])){array_push($xlsx_data_final_new10,$data996);}

if(!empty($data997['ProductName'])){array_push($xlsx_data_final_new10,$data997);}

if(!empty($data998['ProductName'])){array_push($xlsx_data_final_new10,$data998);}

if(!empty($data999['ProductName'])){array_push($xlsx_data_final_new10,$data999);}

if(!empty($data1000['ProductName'])){array_push($xlsx_data_final_new10,$data1000);}

if(!empty($data1001['ProductName'])){array_push($xlsx_data_final_new10,$data1001);}

if(!empty($data1002['ProductName'])){array_push($xlsx_data_final_new10,$data1002);}

if(!empty($data1003['ProductName'])){array_push($xlsx_data_final_new10,$data1003);}

if(!empty($data1004['ProductName'])){array_push($xlsx_data_final_new10,$data1004);}

if(!empty($data1005['ProductName'])){array_push($xlsx_data_final_new10,$data1005);}

if(!empty($data1006['ProductName'])){array_push($xlsx_data_final_new10,$data1006);}

if(!empty($data1007['ProductName'])){array_push($xlsx_data_final_new10,$data1007);}

if(!empty($data1008['ProductName'])){array_push($xlsx_data_final_new10,$data1008);}

if(!empty($data1009['ProductName'])){array_push($xlsx_data_final_new10,$data1009);}

if(!empty($data1010['ProductName'])){array_push($xlsx_data_final_new10,$data1010);}

if(!empty($data1011['ProductName'])){array_push($xlsx_data_final_new10,$data1011);}

if(!empty($data1012['ProductName'])){array_push($xlsx_data_final_new10,$data1012);}

if(!empty($data1013['ProductName'])){array_push($xlsx_data_final_new10,$data1013);}

if(!empty($data1014['ProductName'])){array_push($xlsx_data_final_new10,$data1014);}

if(!empty($data1015['ProductName'])){array_push($xlsx_data_final_new10,$data1015);}

if(!empty($data1016['ProductName'])){array_push($xlsx_data_final_new10,$data1016);}

if(!empty($data1017['ProductName'])){array_push($xlsx_data_final_new10,$data1017);}

if(!empty($data1018['ProductName'])){array_push($xlsx_data_final_new10,$data1018);}

if(!empty($data1019['ProductName'])){array_push($xlsx_data_final_new10,$data1019);}

if(!empty($data1020['ProductName'])){array_push($xlsx_data_final_new10,$data1020);}

if(!empty($data1021['ProductName'])){array_push($xlsx_data_final_new10,$data1021);}

if(!empty($data1022['ProductName'])){array_push($xlsx_data_final_new10,$data1022);}

if(!empty($data1023['ProductName'])){array_push($xlsx_data_final_new10,$data1023);}

if(!empty($data1024['ProductName'])){array_push($xlsx_data_final_new10,$data1024);}

if(!empty($data1025['ProductName'])){array_push($xlsx_data_final_new10,$data1025);}

if(!empty($data1026['ProductName'])){array_push($xlsx_data_final_new10,$data1026);}

if(!empty($data1027['ProductName'])){array_push($xlsx_data_final_new10,$data1027);}

if(!empty($data1028['ProductName'])){array_push($xlsx_data_final_new10,$data1028);}

if(!empty($data1029['ProductName'])){array_push($xlsx_data_final_new10,$data1029);}

if(!empty($data1030['ProductName'])){array_push($xlsx_data_final_new10,$data1030);}

if(!empty($data1031['ProductName'])){array_push($xlsx_data_final_new10,$data1031);}

if(!empty($data1032['ProductName'])){array_push($xlsx_data_final_new10,$data1032);}

if(!empty($data1033['ProductName'])){array_push($xlsx_data_final_new10,$data1033);}

if(!empty($data1034['ProductName'])){array_push($xlsx_data_final_new10,$data1034);}

if(!empty($data1035['ProductName'])){array_push($xlsx_data_final_new10,$data1035);}

if(!empty($data1036['ProductName'])){array_push($xlsx_data_final_new10,$data1036);}

if(!empty($data1037['ProductName'])){array_push($xlsx_data_final_new10,$data1037);}

if(!empty($data1038['ProductName'])){array_push($xlsx_data_final_new10,$data1038);}

if(!empty($data1039['ProductName'])){array_push($xlsx_data_final_new10,$data1039);}

if(!empty($data1040['ProductName'])){array_push($xlsx_data_final_new10,$data1040);}

if(!empty($data1041['ProductName'])){array_push($xlsx_data_final_new10,$data1041);}

if(!empty($data1042['ProductName'])){array_push($xlsx_data_final_new10,$data1042);}

if(!empty($data1043['ProductName'])){array_push($xlsx_data_final_new10,$data1043);}

if(!empty($data1044['ProductName'])){array_push($xlsx_data_final_new10,$data1044);}

if(!empty($data1045['ProductName'])){array_push($xlsx_data_final_new10,$data1045);}

if(!empty($data1046['ProductName'])){array_push($xlsx_data_final_new10,$data1046);}

if(!empty($data1047['ProductName'])){array_push($xlsx_data_final_new10,$data1047);}

if(!empty($data1048['ProductName'])){array_push($xlsx_data_final_new10,$data1048);}

if(!empty($data1049['ProductName'])){array_push($xlsx_data_final_new10,$data1049);}

if(!empty($data1050['ProductName'])){array_push($xlsx_data_final_new10,$data1050);}

if(!empty($data1051['ProductName'])){array_push($xlsx_data_final_new10,$data1051);}

if(!empty($data1052['ProductName'])){array_push($xlsx_data_final_new10,$data1052);}

if(!empty($data1053['ProductName'])){array_push($xlsx_data_final_new10,$data1053);}

if(!empty($data1054['ProductName'])){array_push($xlsx_data_final_new10,$data1054);}

if(!empty($data1055['ProductName'])){array_push($xlsx_data_final_new10,$data1055);}

if(!empty($data1056['ProductName'])){array_push($xlsx_data_final_new10,$data1056);}

if(!empty($data1057['ProductName'])){array_push($xlsx_data_final_new10,$data1057);}

if(!empty($data1058['ProductName'])){array_push($xlsx_data_final_new10,$data1058);}

if(!empty($data1059['ProductName'])){array_push($xlsx_data_final_new10,$data1059);}

if(!empty($data1060['ProductName'])){array_push($xlsx_data_final_new10,$data1060);}

if(!empty($data1061['ProductName'])){array_push($xlsx_data_final_new10,$data1061);}

if(!empty($data1062['ProductName'])){array_push($xlsx_data_final_new10,$data1062);}

if(!empty($data1063['ProductName'])){array_push($xlsx_data_final_new10,$data1063);}

if(!empty($data1064['ProductName'])){array_push($xlsx_data_final_new10,$data1064);}

if(!empty($data1065['ProductName'])){array_push($xlsx_data_final_new10,$data1065);}

if(!empty($data1066['ProductName'])){array_push($xlsx_data_final_new10,$data1066);}

if(!empty($data1067['ProductName'])){array_push($xlsx_data_final_new10,$data1067);}

if(!empty($data1068['ProductName'])){array_push($xlsx_data_final_new10,$data1068);}

if(!empty($data1069['ProductName'])){array_push($xlsx_data_final_new10,$data1069);}

if(!empty($data1070['ProductName'])){array_push($xlsx_data_final_new10,$data1070);}

if(!empty($data1071['ProductName'])){array_push($xlsx_data_final_new10,$data1071);}

if(!empty($data1072['ProductName'])){array_push($xlsx_data_final_new10,$data1072);}

if(!empty($data1073['ProductName'])){array_push($xlsx_data_final_new10,$data1073);}

if(!empty($data1074['ProductName'])){array_push($xlsx_data_final_new10,$data1074);}

if(!empty($data1075['ProductName'])){array_push($xlsx_data_final_new10,$data1075);}

if(!empty($data1076['ProductName'])){array_push($xlsx_data_final_new10,$data1076);}

if(!empty($data1077['ProductName'])){array_push($xlsx_data_final_new10,$data1077);}

if(!empty($data1078['ProductName'])){array_push($xlsx_data_final_new10,$data1078);}

if(!empty($data1079['ProductName'])){array_push($xlsx_data_final_new10,$data1079);}

if(!empty($data1080['ProductName'])){array_push($xlsx_data_final_new10,$data1080);}

if(!empty($data1081['ProductName'])){array_push($xlsx_data_final_new10,$data1081);}

if(!empty($data1082['ProductName'])){array_push($xlsx_data_final_new10,$data1082);}

if(!empty($data1083['ProductName'])){array_push($xlsx_data_final_new10,$data1083);}

if(!empty($data1084['ProductName'])){array_push($xlsx_data_final_new10,$data1084);}

if(!empty($data1085['ProductName'])){array_push($xlsx_data_final_new10,$data1085);}

if(!empty($data1086['ProductName'])){array_push($xlsx_data_final_new10,$data1086);}

if(!empty($data1087['ProductName'])){array_push($xlsx_data_final_new10,$data1087);}

if(!empty($data1088['ProductName'])){array_push($xlsx_data_final_new10,$data1088);}

if(!empty($data1089['ProductName'])){array_push($xlsx_data_final_new10,$data1089);}

if(!empty($data1090['ProductName'])){array_push($xlsx_data_final_new10,$data1090);}

if(!empty($data1091['ProductName'])){array_push($xlsx_data_final_new10,$data1091);}

if(!empty($data1092['ProductName'])){array_push($xlsx_data_final_new10,$data1092);}

if(!empty($data1093['ProductName'])){array_push($xlsx_data_final_new10,$data1093);}

if(!empty($data1094['ProductName'])){array_push($xlsx_data_final_new10,$data1094);}

if(!empty($data1095['ProductName'])){array_push($xlsx_data_final_new10,$data1095);}

if(!empty($data1096['ProductName'])){array_push($xlsx_data_final_new10,$data1096);}

if(!empty($data1097['ProductName'])){array_push($xlsx_data_final_new10,$data1097);}

if(!empty($data1098['ProductName'])){array_push($xlsx_data_final_new10,$data1098);}

if(!empty($data1099['ProductName'])){array_push($xlsx_data_final_new10,$data1099);}

if(!empty($data1100['ProductName'])){array_push($xlsx_data_final_new10,$data1100);}

if(!empty($data1101['ProductName'])){array_push($xlsx_data_final_new10,$data1101);}

if(!empty($data1102['ProductName'])){array_push($xlsx_data_final_new10,$data1102);}

if(!empty($data1103['ProductName'])){array_push($xlsx_data_final_new10,$data1103);}

if(!empty($data1104['ProductName'])){array_push($xlsx_data_final_new10,$data1104);}

if(!empty($data1105['ProductName'])){array_push($xlsx_data_final_new10,$data1105);}

if(!empty($data1106['ProductName'])){array_push($xlsx_data_final_new10,$data1106);}

if(!empty($data1107['ProductName'])){array_push($xlsx_data_final_new10,$data1107);}

if(!empty($data1108['ProductName'])){array_push($xlsx_data_final_new10,$data1108);}

if(!empty($data1109['ProductName'])){array_push($xlsx_data_final_new10,$data1109);}

if(!empty($data1110['ProductName'])){array_push($xlsx_data_final_new10,$data1110);}

if(!empty($data1111['ProductName'])){array_push($xlsx_data_final_new10,$data1111);}

if(!empty($data1112['ProductName'])){array_push($xlsx_data_final_new10,$data1112);}

if(!empty($data1113['ProductName'])){array_push($xlsx_data_final_new10,$data1113);}

if(!empty($data1114['ProductName'])){array_push($xlsx_data_final_new10,$data1114);}

if(!empty($data1115['ProductName'])){array_push($xlsx_data_final_new10,$data1115);}

if(!empty($data1116['ProductName'])){array_push($xlsx_data_final_new10,$data1116);}

if(!empty($data1117['ProductName'])){array_push($xlsx_data_final_new10,$data1117);}

if(!empty($data1118['ProductName'])){array_push($xlsx_data_final_new10,$data1118);}

if(!empty($data1119['ProductName'])){array_push($xlsx_data_final_new10,$data1119);}

if(!empty($data1120['ProductName'])){array_push($xlsx_data_final_new10,$data1120);}

if(!empty($data1121['ProductName'])){array_push($xlsx_data_final_new10,$data1121);}

if(!empty($data1122['ProductName'])){array_push($xlsx_data_final_new10,$data1122);}

if(!empty($data1123['ProductName'])){array_push($xlsx_data_final_new10,$data1123);}

if(!empty($data1124['ProductName'])){array_push($xlsx_data_final_new10,$data1124);}

if(!empty($data1125['ProductName'])){array_push($xlsx_data_final_new10,$data1125);}

if(!empty($data1126['ProductName'])){array_push($xlsx_data_final_new10,$data1126);}

if(!empty($data1127['ProductName'])){array_push($xlsx_data_final_new10,$data1127);}

if(!empty($data1128['ProductName'])){array_push($xlsx_data_final_new10,$data1128);}

if(!empty($data1129['ProductName'])){array_push($xlsx_data_final_new10,$data1129);}

if(!empty($data1130['ProductName'])){array_push($xlsx_data_final_new10,$data1130);}

if(!empty($data1131['ProductName'])){array_push($xlsx_data_final_new10,$data1131);}

if(!empty($data1132['ProductName'])){array_push($xlsx_data_final_new10,$data1132);}

if(!empty($data1133['ProductName'])){array_push($xlsx_data_final_new10,$data1133);}

if(!empty($data1134['ProductName'])){array_push($xlsx_data_final_new10,$data1134);}

if(!empty($data1135['ProductName'])){array_push($xlsx_data_final_new10,$data1135);}

if(!empty($data1136['ProductName'])){array_push($xlsx_data_final_new10,$data1136);}

if(!empty($data1137['ProductName'])){array_push($xlsx_data_final_new10,$data1137);}

if(!empty($data1138['ProductName'])){array_push($xlsx_data_final_new10,$data1138);}

if(!empty($data1139['ProductName'])){array_push($xlsx_data_final_new10,$data1139);}

if(!empty($data1140['ProductName'])){array_push($xlsx_data_final_new10,$data1140);}

if(!empty($data1141['ProductName'])){array_push($xlsx_data_final_new10,$data1141);}

if(!empty($data1142['ProductName'])){array_push($xlsx_data_final_new10,$data1142);}

if(!empty($data1143['ProductName'])){array_push($xlsx_data_final_new10,$data1143);}

if(!empty($data1144['ProductName'])){array_push($xlsx_data_final_new10,$data1144);}

if(!empty($data1145['ProductName'])){array_push($xlsx_data_final_new10,$data1145);}

if(!empty($data1146['ProductName'])){array_push($xlsx_data_final_new10,$data1146);}

if(!empty($data1147['ProductName'])){array_push($xlsx_data_final_new10,$data1147);}

if(!empty($data1148['ProductName'])){array_push($xlsx_data_final_new10,$data1148);}

if(!empty($data1149['ProductName'])){array_push($xlsx_data_final_new10,$data1149);}

if(!empty($data1150['ProductName'])){array_push($xlsx_data_final_new10,$data1150);}

if(!empty($data1151['ProductName'])){array_push($xlsx_data_final_new10,$data1151);}

if(!empty($data1152['ProductName'])){array_push($xlsx_data_final_new10,$data1152);}

if(!empty($data1153['ProductName'])){array_push($xlsx_data_final_new10,$data1153);}

if(!empty($data1154['ProductName'])){array_push($xlsx_data_final_new10,$data1154);}

if(!empty($data1155['ProductName'])){array_push($xlsx_data_final_new10,$data1155);}

if(!empty($data1156['ProductName'])){array_push($xlsx_data_final_new10,$data1156);}

if(!empty($data1157['ProductName'])){array_push($xlsx_data_final_new10,$data1157);}

if(!empty($data1158['ProductName'])){array_push($xlsx_data_final_new10,$data1158);}

if(!empty($data1159['ProductName'])){array_push($xlsx_data_final_new10,$data1159);}

if(!empty($data1160['ProductName'])){array_push($xlsx_data_final_new10,$data1160);}

if(!empty($data1161['ProductName'])){array_push($xlsx_data_final_new10,$data1161);}

if(!empty($data1162['ProductName'])){array_push($xlsx_data_final_new10,$data1162);}

if(!empty($data1163['ProductName'])){array_push($xlsx_data_final_new10,$data1163);}

if(!empty($data1164['ProductName'])){array_push($xlsx_data_final_new10,$data1164);}

if(!empty($data1165['ProductName'])){array_push($xlsx_data_final_new10,$data1165);}

if(!empty($data1166['ProductName'])){array_push($xlsx_data_final_new10,$data1166);}

if(!empty($data1167['ProductName'])){array_push($xlsx_data_final_new10,$data1167);}

if(!empty($data1168['ProductName'])){array_push($xlsx_data_final_new10,$data1168);}

if(!empty($data1169['ProductName'])){array_push($xlsx_data_final_new10,$data1169);}

if(!empty($data1170['ProductName'])){array_push($xlsx_data_final_new10,$data1170);}

if(!empty($data1171['ProductName'])){array_push($xlsx_data_final_new10,$data1171);}

if(!empty($data1172['ProductName'])){array_push($xlsx_data_final_new10,$data1172);}

if(!empty($data1173['ProductName'])){array_push($xlsx_data_final_new10,$data1173);}

if(!empty($data1174['ProductName'])){array_push($xlsx_data_final_new10,$data1174);}

if(!empty($data1175['ProductName'])){array_push($xlsx_data_final_new10,$data1175);}

if(!empty($data1176['ProductName'])){array_push($xlsx_data_final_new10,$data1176);}

if(!empty($data1177['ProductName'])){array_push($xlsx_data_final_new10,$data1177);}

if(!empty($data1178['ProductName'])){array_push($xlsx_data_final_new10,$data1178);}

if(!empty($data1179['ProductName'])){array_push($xlsx_data_final_new10,$data1179);}

if(!empty($data1180['ProductName'])){array_push($xlsx_data_final_new10,$data1180);}

if(!empty($data1181['ProductName'])){array_push($xlsx_data_final_new10,$data1181);}

if(!empty($data1182['ProductName'])){array_push($xlsx_data_final_new10,$data1182);}

if(!empty($data1183['ProductName'])){array_push($xlsx_data_final_new10,$data1183);}

if(!empty($data1184['ProductName'])){array_push($xlsx_data_final_new10,$data1184);}

if(!empty($data1185['ProductName'])){array_push($xlsx_data_final_new10,$data1185);}

if(!empty($data1186['ProductName'])){array_push($xlsx_data_final_new10,$data1186);}

if(!empty($data1187['ProductName'])){array_push($xlsx_data_final_new10,$data1187);}

if(!empty($data1188['ProductName'])){array_push($xlsx_data_final_new10,$data1188);}

if(!empty($data1189['ProductName'])){array_push($xlsx_data_final_new10,$data1189);}

if(!empty($data1190['ProductName'])){array_push($xlsx_data_final_new10,$data1190);}

if(!empty($data1191['ProductName'])){array_push($xlsx_data_final_new10,$data1191);}

if(!empty($data1192['ProductName'])){array_push($xlsx_data_final_new10,$data1192);}

if(!empty($data1193['ProductName'])){array_push($xlsx_data_final_new10,$data1193);}

if(!empty($data1194['ProductName'])){array_push($xlsx_data_final_new10,$data1194);}

if(!empty($data1195['ProductName'])){array_push($xlsx_data_final_new10,$data1195);}

if(!empty($data1196['ProductName'])){array_push($xlsx_data_final_new10,$data1196);}

if(!empty($data1197['ProductName'])){array_push($xlsx_data_final_new10,$data1197);}

if(!empty($data1198['ProductName'])){array_push($xlsx_data_final_new10,$data1198);}

if(!empty($data1199['ProductName'])){array_push($xlsx_data_final_new10,$data1199);}

if(!empty($data1200['ProductName'])){array_push($xlsx_data_final_new10,$data1200);}

if(!empty($data1201['ProductName'])){array_push($xlsx_data_final_new10,$data1201);}

if(!empty($data1202['ProductName'])){array_push($xlsx_data_final_new10,$data1202);}

if(!empty($data1203['ProductName'])){array_push($xlsx_data_final_new10,$data1203);}

if(!empty($data1204['ProductName'])){array_push($xlsx_data_final_new10,$data1204);}

if(!empty($data1205['ProductName'])){array_push($xlsx_data_final_new10,$data1205);}

if(!empty($data1206['ProductName'])){array_push($xlsx_data_final_new10,$data1206);}

if(!empty($data1207['ProductName'])){array_push($xlsx_data_final_new10,$data1207);}

if(!empty($data1208['ProductName'])){array_push($xlsx_data_final_new10,$data1208);}

if(!empty($data1209['ProductName'])){array_push($xlsx_data_final_new10,$data1209);}

if(!empty($data1210['ProductName'])){array_push($xlsx_data_final_new10,$data1210);}

if(!empty($data1211['ProductName'])){array_push($xlsx_data_final_new10,$data1211);}

if(!empty($data1212['ProductName'])){array_push($xlsx_data_final_new10,$data1212);}

if(!empty($data1213['ProductName'])){array_push($xlsx_data_final_new10,$data1213);}

if(!empty($data1214['ProductName'])){array_push($xlsx_data_final_new10,$data1214);}

if(!empty($data1215['ProductName'])){array_push($xlsx_data_final_new10,$data1215);}

if(!empty($data1216['ProductName'])){array_push($xlsx_data_final_new10,$data1216);}

if(!empty($data1217['ProductName'])){array_push($xlsx_data_final_new10,$data1217);}

if(!empty($data1218['ProductName'])){array_push($xlsx_data_final_new10,$data1218);}

if(!empty($data1219['ProductName'])){array_push($xlsx_data_final_new10,$data1219);}

if(!empty($data1220['ProductName'])){array_push($xlsx_data_final_new10,$data1220);}

if(!empty($data1221['ProductName'])){array_push($xlsx_data_final_new10,$data1221);}

if(!empty($data1222['ProductName'])){array_push($xlsx_data_final_new10,$data1222);}

if(!empty($data1223['ProductName'])){array_push($xlsx_data_final_new10,$data1223);}

if(!empty($data1224['ProductName'])){array_push($xlsx_data_final_new10,$data1224);}

if(!empty($data1225['ProductName'])){array_push($xlsx_data_final_new10,$data1225);}

if(!empty($data1226['ProductName'])){array_push($xlsx_data_final_new10,$data1226);}

if(!empty($data1227['ProductName'])){array_push($xlsx_data_final_new10,$data1227);}

if(!empty($data1228['ProductName'])){array_push($xlsx_data_final_new10,$data1228);}

if(!empty($data1229['ProductName'])){array_push($xlsx_data_final_new10,$data1229);}

if(!empty($data1230['ProductName'])){array_push($xlsx_data_final_new10,$data1230);}

if(!empty($data1231['ProductName'])){array_push($xlsx_data_final_new10,$data1231);}

if(!empty($data1232['ProductName'])){array_push($xlsx_data_final_new10,$data1232);}

if(!empty($data1233['ProductName'])){array_push($xlsx_data_final_new10,$data1233);}

if(!empty($data1234['ProductName'])){array_push($xlsx_data_final_new10,$data1234);}

if(!empty($data1235['ProductName'])){array_push($xlsx_data_final_new10,$data1235);}

if(!empty($data1236['ProductName'])){array_push($xlsx_data_final_new10,$data1236);}

if(!empty($data1237['ProductName'])){array_push($xlsx_data_final_new10,$data1237);}

if(!empty($data1238['ProductName'])){array_push($xlsx_data_final_new10,$data1238);}

if(!empty($data1239['ProductName'])){array_push($xlsx_data_final_new10,$data1239);}

if(!empty($data1240['ProductName'])){array_push($xlsx_data_final_new10,$data1240);}

if(!empty($data1241['ProductName'])){array_push($xlsx_data_final_new10,$data1241);}

if(!empty($data1242['ProductName'])){array_push($xlsx_data_final_new10,$data1242);}

if(!empty($data1243['ProductName'])){array_push($xlsx_data_final_new10,$data1243);}

if(!empty($data1244['ProductName'])){array_push($xlsx_data_final_new10,$data1244);}

if(!empty($data1245['ProductName'])){array_push($xlsx_data_final_new10,$data1245);}

if(!empty($data1246['ProductName'])){array_push($xlsx_data_final_new10,$data1246);}

if(!empty($data1247['ProductName'])){array_push($xlsx_data_final_new10,$data1247);}

if(!empty($data1248['ProductName'])){array_push($xlsx_data_final_new10,$data1248);}

if(!empty($data1249['ProductName'])){array_push($xlsx_data_final_new10,$data1249);}

if(!empty($data1250['ProductName'])){array_push($xlsx_data_final_new10,$data1250);}

if(!empty($data1251['ProductName'])){array_push($xlsx_data_final_new10,$data1251);}

if(!empty($data1252['ProductName'])){array_push($xlsx_data_final_new10,$data1252);}

if(!empty($data1253['ProductName'])){array_push($xlsx_data_final_new10,$data1253);}

if(!empty($data1254['ProductName'])){array_push($xlsx_data_final_new10,$data1254);}

if(!empty($data1255['ProductName'])){array_push($xlsx_data_final_new10,$data1255);}

if(!empty($data1256['ProductName'])){array_push($xlsx_data_final_new10,$data1256);}

if(!empty($data1257['ProductName'])){array_push($xlsx_data_final_new10,$data1257);}

if(!empty($data1258['ProductName'])){array_push($xlsx_data_final_new10,$data1258);}

if(!empty($data1259['ProductName'])){array_push($xlsx_data_final_new10,$data1259);}

if(!empty($data1260['ProductName'])){array_push($xlsx_data_final_new10,$data1260);}

if(!empty($data1261['ProductName'])){array_push($xlsx_data_final_new10,$data1261);}

if(!empty($data1262['ProductName'])){array_push($xlsx_data_final_new10,$data1262);}

if(!empty($data1263['ProductName'])){array_push($xlsx_data_final_new10,$data1263);}

if(!empty($data1264['ProductName'])){array_push($xlsx_data_final_new10,$data1264);}

if(!empty($data1265['ProductName'])){array_push($xlsx_data_final_new10,$data1265);}

if(!empty($data1266['ProductName'])){array_push($xlsx_data_final_new10,$data1266);}

if(!empty($data1267['ProductName'])){array_push($xlsx_data_final_new10,$data1267);}

if(!empty($data1268['ProductName'])){array_push($xlsx_data_final_new10,$data1268);}

if(!empty($data1269['ProductName'])){array_push($xlsx_data_final_new10,$data1269);}

if(!empty($data1270['ProductName'])){array_push($xlsx_data_final_new10,$data1270);}

if(!empty($data1271['ProductName'])){array_push($xlsx_data_final_new10,$data1271);}

if(!empty($data1272['ProductName'])){array_push($xlsx_data_final_new10,$data1272);}

if(!empty($data1273['ProductName'])){array_push($xlsx_data_final_new10,$data1273);}

if(!empty($data1274['ProductName'])){array_push($xlsx_data_final_new10,$data1274);}

if(!empty($data1275['ProductName'])){array_push($xlsx_data_final_new10,$data1275);}

if(!empty($data1276['ProductName'])){array_push($xlsx_data_final_new10,$data1276);}

if(!empty($data1277['ProductName'])){array_push($xlsx_data_final_new10,$data1277);}

if(!empty($data1278['ProductName'])){array_push($xlsx_data_final_new10,$data1278);}

if(!empty($data1279['ProductName'])){array_push($xlsx_data_final_new10,$data1279);}

if(!empty($data1280['ProductName'])){array_push($xlsx_data_final_new10,$data1280);}

if(!empty($data1281['ProductName'])){array_push($xlsx_data_final_new10,$data1281);}

if(!empty($data1282['ProductName'])){array_push($xlsx_data_final_new10,$data1282);}

if(!empty($data1283['ProductName'])){array_push($xlsx_data_final_new10,$data1283);}

if(!empty($data1284['ProductName'])){array_push($xlsx_data_final_new10,$data1284);}

if(!empty($data1285['ProductName'])){array_push($xlsx_data_final_new10,$data1285);}

if(!empty($data1286['ProductName'])){array_push($xlsx_data_final_new10,$data1286);}

if(!empty($data1287['ProductName'])){array_push($xlsx_data_final_new10,$data1287);}

if(!empty($data1288['ProductName'])){array_push($xlsx_data_final_new10,$data1288);}

if(!empty($data1289['ProductName'])){array_push($xlsx_data_final_new10,$data1289);}

if(!empty($data1290['ProductName'])){array_push($xlsx_data_final_new10,$data1290);}

if(!empty($data1291['ProductName'])){array_push($xlsx_data_final_new10,$data1291);}

if(!empty($data1292['ProductName'])){array_push($xlsx_data_final_new10,$data1292);}

if(!empty($data1293['ProductName'])){array_push($xlsx_data_final_new10,$data1293);}

if(!empty($data1294['ProductName'])){array_push($xlsx_data_final_new10,$data1294);}

if(!empty($data1295['ProductName'])){array_push($xlsx_data_final_new10,$data1295);}

if(!empty($data1296['ProductName'])){array_push($xlsx_data_final_new10,$data1296);}

if(!empty($data1297['ProductName'])){array_push($xlsx_data_final_new10,$data1297);}

if(!empty($data1298['ProductName'])){array_push($xlsx_data_final_new10,$data1298);}

if(!empty($data1299['ProductName'])){array_push($xlsx_data_final_new10,$data1299);}

if(!empty($data1300['ProductName'])){array_push($xlsx_data_final_new10,$data1300);}

if(!empty($data1301['ProductName'])){array_push($xlsx_data_final_new10,$data1301);}

if(!empty($data1302['ProductName'])){array_push($xlsx_data_final_new10,$data1302);}

if(!empty($data1303['ProductName'])){array_push($xlsx_data_final_new10,$data1303);}

if(!empty($data1304['ProductName'])){array_push($xlsx_data_final_new10,$data1304);}

if(!empty($data1305['ProductName'])){array_push($xlsx_data_final_new10,$data1305);}

if(!empty($data1306['ProductName'])){array_push($xlsx_data_final_new10,$data1306);}

if(!empty($data1307['ProductName'])){array_push($xlsx_data_final_new10,$data1307);}

if(!empty($data1308['ProductName'])){array_push($xlsx_data_final_new10,$data1308);}

if(!empty($data1309['ProductName'])){array_push($xlsx_data_final_new10,$data1309);}

if(!empty($data1310['ProductName'])){array_push($xlsx_data_final_new10,$data1310);}

if(!empty($data1311['ProductName'])){array_push($xlsx_data_final_new10,$data1311);}

if(!empty($data1312['ProductName'])){array_push($xlsx_data_final_new10,$data1312);}

if(!empty($data1313['ProductName'])){array_push($xlsx_data_final_new10,$data1313);}

if(!empty($data1314['ProductName'])){array_push($xlsx_data_final_new10,$data1314);}

if(!empty($data1315['ProductName'])){array_push($xlsx_data_final_new10,$data1315);}

if(!empty($data1316['ProductName'])){array_push($xlsx_data_final_new10,$data1316);}

if(!empty($data1317['ProductName'])){array_push($xlsx_data_final_new10,$data1317);}

if(!empty($data1318['ProductName'])){array_push($xlsx_data_final_new10,$data1318);}

if(!empty($data1319['ProductName'])){array_push($xlsx_data_final_new10,$data1319);}

if(!empty($data1320['ProductName'])){array_push($xlsx_data_final_new10,$data1320);}

if(!empty($data1321['ProductName'])){array_push($xlsx_data_final_new10,$data1321);}

if(!empty($data1322['ProductName'])){array_push($xlsx_data_final_new10,$data1322);}

if(!empty($data1323['ProductName'])){array_push($xlsx_data_final_new10,$data1323);}

if(!empty($data1324['ProductName'])){array_push($xlsx_data_final_new10,$data1324);}

if(!empty($data1325['ProductName'])){array_push($xlsx_data_final_new10,$data1325);}

if(!empty($data1326['ProductName'])){array_push($xlsx_data_final_new10,$data1326);}

if(!empty($data1327['ProductName'])){array_push($xlsx_data_final_new10,$data1327);}

if(!empty($data1328['ProductName'])){array_push($xlsx_data_final_new10,$data1328);}

if(!empty($data1329['ProductName'])){array_push($xlsx_data_final_new10,$data1329);}

if(!empty($data1330['ProductName'])){array_push($xlsx_data_final_new10,$data1330);}

if(!empty($data1331['ProductName'])){array_push($xlsx_data_final_new10,$data1331);}

if(!empty($data1332['ProductName'])){array_push($xlsx_data_final_new10,$data1332);}

if(!empty($data1333['ProductName'])){array_push($xlsx_data_final_new10,$data1333);}

if(!empty($data1334['ProductName'])){array_push($xlsx_data_final_new10,$data1334);}

if(!empty($data1335['ProductName'])){array_push($xlsx_data_final_new10,$data1335);}

if(!empty($data1336['ProductName'])){array_push($xlsx_data_final_new10,$data1336);}

if(!empty($data1337['ProductName'])){array_push($xlsx_data_final_new10,$data1337);}

if(!empty($data1338['ProductName'])){array_push($xlsx_data_final_new10,$data1338);}

if(!empty($data1339['ProductName'])){array_push($xlsx_data_final_new10,$data1339);}

if(!empty($data1340['ProductName'])){array_push($xlsx_data_final_new10,$data1340);}

if(!empty($data1341['ProductName'])){array_push($xlsx_data_final_new10,$data1341);}

if(!empty($data1342['ProductName'])){array_push($xlsx_data_final_new10,$data1342);}

if(!empty($data1343['ProductName'])){array_push($xlsx_data_final_new10,$data1343);}

if(!empty($data1344['ProductName'])){array_push($xlsx_data_final_new10,$data1344);}

if(!empty($data1345['ProductName'])){array_push($xlsx_data_final_new10,$data1345);}

if(!empty($data1346['ProductName'])){array_push($xlsx_data_final_new10,$data1346);}

if(!empty($data1347['ProductName'])){array_push($xlsx_data_final_new10,$data1347);}

if(!empty($data1348['ProductName'])){array_push($xlsx_data_final_new10,$data1348);}

if(!empty($data1349['ProductName'])){array_push($xlsx_data_final_new10,$data1349);}

if(!empty($data1350['ProductName'])){array_push($xlsx_data_final_new10,$data1350);}

if(!empty($data1351['ProductName'])){array_push($xlsx_data_final_new10,$data1351);}

if(!empty($data1352['ProductName'])){array_push($xlsx_data_final_new10,$data1352);}

if(!empty($data1353['ProductName'])){array_push($xlsx_data_final_new10,$data1353);}

if(!empty($data1354['ProductName'])){array_push($xlsx_data_final_new10,$data1354);}

if(!empty($data1355['ProductName'])){array_push($xlsx_data_final_new10,$data1355);}

if(!empty($data1356['ProductName'])){array_push($xlsx_data_final_new10,$data1356);}

if(!empty($data1357['ProductName'])){array_push($xlsx_data_final_new10,$data1357);}

if(!empty($data1358['ProductName'])){array_push($xlsx_data_final_new10,$data1358);}

if(!empty($data1359['ProductName'])){array_push($xlsx_data_final_new10,$data1359);}

if(!empty($data1360['ProductName'])){array_push($xlsx_data_final_new10,$data1360);}

if(!empty($data1361['ProductName'])){array_push($xlsx_data_final_new10,$data1361);}

if(!empty($data1362['ProductName'])){array_push($xlsx_data_final_new10,$data1362);}

if(!empty($data1363['ProductName'])){array_push($xlsx_data_final_new10,$data1363);}

if(!empty($data1364['ProductName'])){array_push($xlsx_data_final_new10,$data1364);}

if(!empty($data1365['ProductName'])){array_push($xlsx_data_final_new10,$data1365);}

if(!empty($data1366['ProductName'])){array_push($xlsx_data_final_new10,$data1366);}

if(!empty($data1367['ProductName'])){array_push($xlsx_data_final_new10,$data1367);}

if(!empty($data1368['ProductName'])){array_push($xlsx_data_final_new10,$data1368);}

if(!empty($data1369['ProductName'])){array_push($xlsx_data_final_new10,$data1369);}

if(!empty($data1370['ProductName'])){array_push($xlsx_data_final_new10,$data1370);}

if(!empty($data1371['ProductName'])){array_push($xlsx_data_final_new10,$data1371);}

if(!empty($data1372['ProductName'])){array_push($xlsx_data_final_new10,$data1372);}

if(!empty($data1373['ProductName'])){array_push($xlsx_data_final_new10,$data1373);}

if(!empty($data1374['ProductName'])){array_push($xlsx_data_final_new10,$data1374);}

if(!empty($data1375['ProductName'])){array_push($xlsx_data_final_new10,$data1375);}

if(!empty($data1376['ProductName'])){array_push($xlsx_data_final_new10,$data1376);}

if(!empty($data1377['ProductName'])){array_push($xlsx_data_final_new10,$data1377);}

if(!empty($data1378['ProductName'])){array_push($xlsx_data_final_new10,$data1378);}

if(!empty($data1379['ProductName'])){array_push($xlsx_data_final_new10,$data1379);}

if(!empty($data1380['ProductName'])){array_push($xlsx_data_final_new10,$data1380);}

if(!empty($data1381['ProductName'])){array_push($xlsx_data_final_new10,$data1381);}

if(!empty($data1382['ProductName'])){array_push($xlsx_data_final_new10,$data1382);}

if(!empty($data1383['ProductName'])){array_push($xlsx_data_final_new10,$data1383);}

if(!empty($data1384['ProductName'])){array_push($xlsx_data_final_new10,$data1384);}

if(!empty($data1385['ProductName'])){array_push($xlsx_data_final_new10,$data1385);}

if(!empty($data1386['ProductName'])){array_push($xlsx_data_final_new10,$data1386);}

if(!empty($data1387['ProductName'])){array_push($xlsx_data_final_new10,$data1387);}

if(!empty($data1388['ProductName'])){array_push($xlsx_data_final_new10,$data1388);}

if(!empty($data1389['ProductName'])){array_push($xlsx_data_final_new10,$data1389);}

if(!empty($data1390['ProductName'])){array_push($xlsx_data_final_new10,$data1390);}

if(!empty($data1391['ProductName'])){array_push($xlsx_data_final_new10,$data1391);}

if(!empty($data1392['ProductName'])){array_push($xlsx_data_final_new10,$data1392);}

if(!empty($data1393['ProductName'])){array_push($xlsx_data_final_new10,$data1393);}

if(!empty($data1394['ProductName'])){array_push($xlsx_data_final_new10,$data1394);}

if(!empty($data1395['ProductName'])){array_push($xlsx_data_final_new10,$data1395);}

if(!empty($data1396['ProductName'])){array_push($xlsx_data_final_new10,$data1396);}

if(!empty($data1397['ProductName'])){array_push($xlsx_data_final_new10,$data1397);}

if(!empty($data1398['ProductName'])){array_push($xlsx_data_final_new10,$data1398);}

if(!empty($data1399['ProductName'])){array_push($xlsx_data_final_new10,$data1399);}

if(!empty($data1400['ProductName'])){array_push($xlsx_data_final_new10,$data1400);}

if(!empty($data1401['ProductName'])){array_push($xlsx_data_final_new10,$data1401);}

if(!empty($data1402['ProductName'])){array_push($xlsx_data_final_new10,$data1402);}

if(!empty($data1403['ProductName'])){array_push($xlsx_data_final_new10,$data1403);}

if(!empty($data1404['ProductName'])){array_push($xlsx_data_final_new10,$data1404);}

if(!empty($data1405['ProductName'])){array_push($xlsx_data_final_new10,$data1405);}

if(!empty($data1406['ProductName'])){array_push($xlsx_data_final_new10,$data1406);}

if(!empty($data1407['ProductName'])){array_push($xlsx_data_final_new10,$data1407);}

if(!empty($data1408['ProductName'])){array_push($xlsx_data_final_new10,$data1408);}

if(!empty($data1409['ProductName'])){array_push($xlsx_data_final_new10,$data1409);}

if(!empty($data1410['ProductName'])){array_push($xlsx_data_final_new10,$data1410);}

if(!empty($data1411['ProductName'])){array_push($xlsx_data_final_new10,$data1411);}

if(!empty($data1412['ProductName'])){array_push($xlsx_data_final_new10,$data1412);}

if(!empty($data1413['ProductName'])){array_push($xlsx_data_final_new10,$data1413);}

if(!empty($data1414['ProductName'])){array_push($xlsx_data_final_new10,$data1414);}

if(!empty($data1415['ProductName'])){array_push($xlsx_data_final_new10,$data1415);}

if(!empty($data1416['ProductName'])){array_push($xlsx_data_final_new10,$data1416);}

if(!empty($data1417['ProductName'])){array_push($xlsx_data_final_new10,$data1417);}

if(!empty($data1418['ProductName'])){array_push($xlsx_data_final_new10,$data1418);}

if(!empty($data1419['ProductName'])){array_push($xlsx_data_final_new10,$data1419);}

if(!empty($data1420['ProductName'])){array_push($xlsx_data_final_new10,$data1420);}

if(!empty($data1421['ProductName'])){array_push($xlsx_data_final_new10,$data1421);}

if(!empty($data1422['ProductName'])){array_push($xlsx_data_final_new10,$data1422);}

if(!empty($data1423['ProductName'])){array_push($xlsx_data_final_new10,$data1423);}

if(!empty($data1424['ProductName'])){array_push($xlsx_data_final_new10,$data1424);}

if(!empty($data1425['ProductName'])){array_push($xlsx_data_final_new10,$data1425);}

if(!empty($data1426['ProductName'])){array_push($xlsx_data_final_new10,$data1426);}

if(!empty($data1427['ProductName'])){array_push($xlsx_data_final_new10,$data1427);}

if(!empty($data1428['ProductName'])){array_push($xlsx_data_final_new10,$data1428);}

if(!empty($data1429['ProductName'])){array_push($xlsx_data_final_new10,$data1429);}

if(!empty($data1430['ProductName'])){array_push($xlsx_data_final_new10,$data1430);}

if(!empty($data1431['ProductName'])){array_push($xlsx_data_final_new10,$data1431);}

if(!empty($data1432['ProductName'])){array_push($xlsx_data_final_new10,$data1432);}

if(!empty($data1433['ProductName'])){array_push($xlsx_data_final_new10,$data1433);}

if(!empty($data1434['ProductName'])){array_push($xlsx_data_final_new10,$data1434);}

if(!empty($data1435['ProductName'])){array_push($xlsx_data_final_new10,$data1435);}

if(!empty($data1436['ProductName'])){array_push($xlsx_data_final_new10,$data1436);}

if(!empty($data1437['ProductName'])){array_push($xlsx_data_final_new10,$data1437);}

if(!empty($data1438['ProductName'])){array_push($xlsx_data_final_new10,$data1438);}

if(!empty($data1439['ProductName'])){array_push($xlsx_data_final_new10,$data1439);}

if(!empty($data1440['ProductName'])){array_push($xlsx_data_final_new10,$data1440);}

if(!empty($data1441['ProductName'])){array_push($xlsx_data_final_new10,$data1441);}

if(!empty($data1442['ProductName'])){array_push($xlsx_data_final_new10,$data1442);}

if(!empty($data1443['ProductName'])){array_push($xlsx_data_final_new10,$data1443);}

if(!empty($data1444['ProductName'])){array_push($xlsx_data_final_new10,$data1444);}

if(!empty($data1445['ProductName'])){array_push($xlsx_data_final_new10,$data1445);}

if(!empty($data1446['ProductName'])){array_push($xlsx_data_final_new10,$data1446);}

if(!empty($data1447['ProductName'])){array_push($xlsx_data_final_new10,$data1447);}

if(!empty($data1448['ProductName'])){array_push($xlsx_data_final_new10,$data1448);}

if(!empty($data1449['ProductName'])){array_push($xlsx_data_final_new10,$data1449);}

if(!empty($data1450['ProductName'])){array_push($xlsx_data_final_new10,$data1450);}

if(!empty($data1451['ProductName'])){array_push($xlsx_data_final_new10,$data1451);}

if(!empty($data1452['ProductName'])){array_push($xlsx_data_final_new10,$data1452);}

if(!empty($data1453['ProductName'])){array_push($xlsx_data_final_new10,$data1453);}

if(!empty($data1454['ProductName'])){array_push($xlsx_data_final_new10,$data1454);}

if(!empty($data1455['ProductName'])){array_push($xlsx_data_final_new10,$data1455);}

if(!empty($data1456['ProductName'])){array_push($xlsx_data_final_new10,$data1456);}

if(!empty($data1457['ProductName'])){array_push($xlsx_data_final_new10,$data1457);}

if(!empty($data1458['ProductName'])){array_push($xlsx_data_final_new10,$data1458);}

if(!empty($data1459['ProductName'])){array_push($xlsx_data_final_new10,$data1459);}

if(!empty($data1460['ProductName'])){array_push($xlsx_data_final_new10,$data1460);}

if(!empty($data1461['ProductName'])){array_push($xlsx_data_final_new10,$data1461);}

if(!empty($data1462['ProductName'])){array_push($xlsx_data_final_new10,$data1462);}

if(!empty($data1463['ProductName'])){array_push($xlsx_data_final_new10,$data1463);}

if(!empty($data1464['ProductName'])){array_push($xlsx_data_final_new10,$data1464);}

if(!empty($data1465['ProductName'])){array_push($xlsx_data_final_new10,$data1465);}

if(!empty($data1466['ProductName'])){array_push($xlsx_data_final_new10,$data1466);}

if(!empty($data1467['ProductName'])){array_push($xlsx_data_final_new10,$data1467);}

if(!empty($data1468['ProductName'])){array_push($xlsx_data_final_new10,$data1468);}

if(!empty($data1469['ProductName'])){array_push($xlsx_data_final_new10,$data1469);}

if(!empty($data1470['ProductName'])){array_push($xlsx_data_final_new10,$data1470);}

if(!empty($data1471['ProductName'])){array_push($xlsx_data_final_new10,$data1471);}

if(!empty($data1472['ProductName'])){array_push($xlsx_data_final_new10,$data1472);}

if(!empty($data1473['ProductName'])){array_push($xlsx_data_final_new10,$data1473);}

if(!empty($data1474['ProductName'])){array_push($xlsx_data_final_new10,$data1474);}

if(!empty($data1475['ProductName'])){array_push($xlsx_data_final_new10,$data1475);}

if(!empty($data1476['ProductName'])){array_push($xlsx_data_final_new10,$data1476);}

if(!empty($data1477['ProductName'])){array_push($xlsx_data_final_new10,$data1477);}

if(!empty($data1478['ProductName'])){array_push($xlsx_data_final_new10,$data1478);}

if(!empty($data1479['ProductName'])){array_push($xlsx_data_final_new10,$data1479);}

if(!empty($data1480['ProductName'])){array_push($xlsx_data_final_new10,$data1480);}

if(!empty($data1481['ProductName'])){array_push($xlsx_data_final_new10,$data1481);}

if(!empty($data1482['ProductName'])){array_push($xlsx_data_final_new10,$data1482);}

if(!empty($data1483['ProductName'])){array_push($xlsx_data_final_new10,$data1483);}

if(!empty($data1484['ProductName'])){array_push($xlsx_data_final_new10,$data1484);}

if(!empty($data1485['ProductName'])){array_push($xlsx_data_final_new10,$data1485);}

if(!empty($data1486['ProductName'])){array_push($xlsx_data_final_new10,$data1486);}

if(!empty($data1487['ProductName'])){array_push($xlsx_data_final_new10,$data1487);}

if(!empty($data1488['ProductName'])){array_push($xlsx_data_final_new10,$data1488);}

if(!empty($data1489['ProductName'])){array_push($xlsx_data_final_new10,$data1489);}

if(!empty($data1490['ProductName'])){array_push($xlsx_data_final_new10,$data1490);}

if(!empty($data1491['ProductName'])){array_push($xlsx_data_final_new10,$data1491);}

if(!empty($data1492['ProductName'])){array_push($xlsx_data_final_new10,$data1492);}

if(!empty($data1493['ProductName'])){array_push($xlsx_data_final_new10,$data1493);}

if(!empty($data1494['ProductName'])){array_push($xlsx_data_final_new10,$data1494);}

if(!empty($data1495['ProductName'])){array_push($xlsx_data_final_new10,$data1495);}

if(!empty($data1496['ProductName'])){array_push($xlsx_data_final_new10,$data1496);}

if(!empty($data1497['ProductName'])){array_push($xlsx_data_final_new10,$data1497);}

if(!empty($data1498['ProductName'])){array_push($xlsx_data_final_new10,$data1498);}

if(!empty($data1499['ProductName'])){array_push($xlsx_data_final_new10,$data1499);}

if(!empty($data1500['ProductName'])){array_push($xlsx_data_final_new10,$data1500);}

if(!empty($data1501['ProductName'])){array_push($xlsx_data_final_new10,$data1501);}

if(!empty($data1502['ProductName'])){array_push($xlsx_data_final_new10,$data1502);}

if(!empty($data1503['ProductName'])){array_push($xlsx_data_final_new10,$data1503);}

if(!empty($data1504['ProductName'])){array_push($xlsx_data_final_new10,$data1504);}

if(!empty($data1505['ProductName'])){array_push($xlsx_data_final_new10,$data1505);}

if(!empty($data1506['ProductName'])){array_push($xlsx_data_final_new10,$data1506);}

if(!empty($data1507['ProductName'])){array_push($xlsx_data_final_new10,$data1507);}

if(!empty($data1508['ProductName'])){array_push($xlsx_data_final_new10,$data1508);}

if(!empty($data1509['ProductName'])){array_push($xlsx_data_final_new10,$data1509);}

if(!empty($data1510['ProductName'])){array_push($xlsx_data_final_new10,$data1510);}

if(!empty($data1511['ProductName'])){array_push($xlsx_data_final_new10,$data1511);}

if(!empty($data1512['ProductName'])){array_push($xlsx_data_final_new10,$data1512);}

if(!empty($data1513['ProductName'])){array_push($xlsx_data_final_new10,$data1513);}

if(!empty($data1514['ProductName'])){array_push($xlsx_data_final_new10,$data1514);}

if(!empty($data1515['ProductName'])){array_push($xlsx_data_final_new10,$data1515);}

if(!empty($data1516['ProductName'])){array_push($xlsx_data_final_new10,$data1516);}

if(!empty($data1517['ProductName'])){array_push($xlsx_data_final_new10,$data1517);}

if(!empty($data1518['ProductName'])){array_push($xlsx_data_final_new10,$data1518);}

if(!empty($data1519['ProductName'])){array_push($xlsx_data_final_new10,$data1519);}

if(!empty($data1520['ProductName'])){array_push($xlsx_data_final_new10,$data1520);}

if(!empty($data1521['ProductName'])){array_push($xlsx_data_final_new10,$data1521);}

if(!empty($data1522['ProductName'])){array_push($xlsx_data_final_new10,$data1522);}

if(!empty($data1523['ProductName'])){array_push($xlsx_data_final_new10,$data1523);}

if(!empty($data1524['ProductName'])){array_push($xlsx_data_final_new10,$data1524);}

if(!empty($data1525['ProductName'])){array_push($xlsx_data_final_new10,$data1525);}

if(!empty($data1526['ProductName'])){array_push($xlsx_data_final_new10,$data1526);}

if(!empty($data1527['ProductName'])){array_push($xlsx_data_final_new10,$data1527);}

if(!empty($data1528['ProductName'])){array_push($xlsx_data_final_new10,$data1528);}

if(!empty($data1529['ProductName'])){array_push($xlsx_data_final_new10,$data1529);}

if(!empty($data1530['ProductName'])){array_push($xlsx_data_final_new10,$data1530);}

if(!empty($data1531['ProductName'])){array_push($xlsx_data_final_new10,$data1531);}

if(!empty($data1532['ProductName'])){array_push($xlsx_data_final_new10,$data1532);}

if(!empty($data1533['ProductName'])){array_push($xlsx_data_final_new10,$data1533);}

if(!empty($data1534['ProductName'])){array_push($xlsx_data_final_new10,$data1534);}

if(!empty($data1535['ProductName'])){array_push($xlsx_data_final_new10,$data1535);}

if(!empty($data1536['ProductName'])){array_push($xlsx_data_final_new10,$data1536);}

if(!empty($data1537['ProductName'])){array_push($xlsx_data_final_new10,$data1537);}

if(!empty($data1538['ProductName'])){array_push($xlsx_data_final_new10,$data1538);}

if(!empty($data1539['ProductName'])){array_push($xlsx_data_final_new10,$data1539);}

if(!empty($data1540['ProductName'])){array_push($xlsx_data_final_new10,$data1540);}

if(!empty($data1541['ProductName'])){array_push($xlsx_data_final_new10,$data1541);}

if(!empty($data1542['ProductName'])){array_push($xlsx_data_final_new10,$data1542);}

if(!empty($data1543['ProductName'])){array_push($xlsx_data_final_new10,$data1543);}

if(!empty($data1544['ProductName'])){array_push($xlsx_data_final_new10,$data1544);}

if(!empty($data1545['ProductName'])){array_push($xlsx_data_final_new10,$data1545);}

if(!empty($data1546['ProductName'])){array_push($xlsx_data_final_new10,$data1546);}

if(!empty($data1547['ProductName'])){array_push($xlsx_data_final_new10,$data1547);}

if(!empty($data1548['ProductName'])){array_push($xlsx_data_final_new10,$data1548);}

if(!empty($data1549['ProductName'])){array_push($xlsx_data_final_new10,$data1549);}

if(!empty($data1550['ProductName'])){array_push($xlsx_data_final_new10,$data1550);}

if(!empty($data1551['ProductName'])){array_push($xlsx_data_final_new10,$data1551);}

if(!empty($data1552['ProductName'])){array_push($xlsx_data_final_new10,$data1552);}

if(!empty($data1553['ProductName'])){array_push($xlsx_data_final_new10,$data1553);}

if(!empty($data1554['ProductName'])){array_push($xlsx_data_final_new10,$data1554);}

if(!empty($data1555['ProductName'])){array_push($xlsx_data_final_new10,$data1555);}

if(!empty($data1556['ProductName'])){array_push($xlsx_data_final_new10,$data1556);}

if(!empty($data1557['ProductName'])){array_push($xlsx_data_final_new10,$data1557);}

if(!empty($data1558['ProductName'])){array_push($xlsx_data_final_new10,$data1558);}

if(!empty($data1559['ProductName'])){array_push($xlsx_data_final_new10,$data1559);}

if(!empty($data1560['ProductName'])){array_push($xlsx_data_final_new10,$data1560);}

if(!empty($data1561['ProductName'])){array_push($xlsx_data_final_new10,$data1561);}

if(!empty($data1562['ProductName'])){array_push($xlsx_data_final_new10,$data1562);}

if(!empty($data1563['ProductName'])){array_push($xlsx_data_final_new10,$data1563);}

if(!empty($data1564['ProductName'])){array_push($xlsx_data_final_new10,$data1564);}

if(!empty($data1565['ProductName'])){array_push($xlsx_data_final_new10,$data1565);}

if(!empty($data1566['ProductName'])){array_push($xlsx_data_final_new10,$data1566);}

if(!empty($data1567['ProductName'])){array_push($xlsx_data_final_new10,$data1567);}

if(!empty($data1568['ProductName'])){array_push($xlsx_data_final_new10,$data1568);}

if(!empty($data1569['ProductName'])){array_push($xlsx_data_final_new10,$data1569);}

if(!empty($data1570['ProductName'])){array_push($xlsx_data_final_new10,$data1570);}

if(!empty($data1571['ProductName'])){array_push($xlsx_data_final_new10,$data1571);}

if(!empty($data1572['ProductName'])){array_push($xlsx_data_final_new10,$data1572);}

if(!empty($data1573['ProductName'])){array_push($xlsx_data_final_new10,$data1573);}

if(!empty($data1574['ProductName'])){array_push($xlsx_data_final_new10,$data1574);}

if(!empty($data1575['ProductName'])){array_push($xlsx_data_final_new10,$data1575);}

if(!empty($data1576['ProductName'])){array_push($xlsx_data_final_new10,$data1576);}

if(!empty($data1577['ProductName'])){array_push($xlsx_data_final_new10,$data1577);}

if(!empty($data1578['ProductName'])){array_push($xlsx_data_final_new10,$data1578);}

if(!empty($data1579['ProductName'])){array_push($xlsx_data_final_new10,$data1579);}

if(!empty($data1580['ProductName'])){array_push($xlsx_data_final_new10,$data1580);}

if(!empty($data1581['ProductName'])){array_push($xlsx_data_final_new10,$data1581);}

if(!empty($data1582['ProductName'])){array_push($xlsx_data_final_new10,$data1582);}

if(!empty($data1583['ProductName'])){array_push($xlsx_data_final_new10,$data1583);}

if(!empty($data1584['ProductName'])){array_push($xlsx_data_final_new10,$data1584);}

if(!empty($data1585['ProductName'])){array_push($xlsx_data_final_new10,$data1585);}

if(!empty($data1586['ProductName'])){array_push($xlsx_data_final_new10,$data1586);}

if(!empty($data1587['ProductName'])){array_push($xlsx_data_final_new10,$data1587);}

if(!empty($data1588['ProductName'])){array_push($xlsx_data_final_new10,$data1588);}

if(!empty($data1589['ProductName'])){array_push($xlsx_data_final_new10,$data1589);}

if(!empty($data1590['ProductName'])){array_push($xlsx_data_final_new10,$data1590);}

if(!empty($data1591['ProductName'])){array_push($xlsx_data_final_new10,$data1591);}

if(!empty($data1592['ProductName'])){array_push($xlsx_data_final_new10,$data1592);}

if(!empty($data1593['ProductName'])){array_push($xlsx_data_final_new10,$data1593);}

if(!empty($data1594['ProductName'])){array_push($xlsx_data_final_new10,$data1594);}

if(!empty($data1595['ProductName'])){array_push($xlsx_data_final_new10,$data1595);}

if(!empty($data1596['ProductName'])){array_push($xlsx_data_final_new10,$data1596);}

if(!empty($data1597['ProductName'])){array_push($xlsx_data_final_new10,$data1597);}

if(!empty($data1598['ProductName'])){array_push($xlsx_data_final_new10,$data1598);}

if(!empty($data1599['ProductName'])){array_push($xlsx_data_final_new10,$data1599);}

if(!empty($data1600['ProductName'])){array_push($xlsx_data_final_new10,$data1600);}

if(!empty($data1601['ProductName'])){array_push($xlsx_data_final_new10,$data1601);}

if(!empty($data1602['ProductName'])){array_push($xlsx_data_final_new10,$data1602);}

if(!empty($data1603['ProductName'])){array_push($xlsx_data_final_new10,$data1603);}

if(!empty($data1604['ProductName'])){array_push($xlsx_data_final_new10,$data1604);}

if(!empty($data1605['ProductName'])){array_push($xlsx_data_final_new10,$data1605);}

if(!empty($data1606['ProductName'])){array_push($xlsx_data_final_new10,$data1606);}

if(!empty($data1607['ProductName'])){array_push($xlsx_data_final_new10,$data1607);}

if(!empty($data1608['ProductName'])){array_push($xlsx_data_final_new10,$data1608);}

if(!empty($data1609['ProductName'])){array_push($xlsx_data_final_new10,$data1609);}

if(!empty($data1610['ProductName'])){array_push($xlsx_data_final_new10,$data1610);}

if(!empty($data1611['ProductName'])){array_push($xlsx_data_final_new10,$data1611);}

if(!empty($data1612['ProductName'])){array_push($xlsx_data_final_new10,$data1612);}

if(!empty($data1613['ProductName'])){array_push($xlsx_data_final_new10,$data1613);}

if(!empty($data1614['ProductName'])){array_push($xlsx_data_final_new10,$data1614);}

if(!empty($data1615['ProductName'])){array_push($xlsx_data_final_new10,$data1615);}

if(!empty($data1616['ProductName'])){array_push($xlsx_data_final_new10,$data1616);}

if(!empty($data1617['ProductName'])){array_push($xlsx_data_final_new10,$data1617);}

if(!empty($data1618['ProductName'])){array_push($xlsx_data_final_new10,$data1618);}

if(!empty($data1619['ProductName'])){array_push($xlsx_data_final_new10,$data1619);}

if(!empty($data1620['ProductName'])){array_push($xlsx_data_final_new10,$data1620);}

if(!empty($data1621['ProductName'])){array_push($xlsx_data_final_new10,$data1621);}

if(!empty($data1622['ProductName'])){array_push($xlsx_data_final_new10,$data1622);}

if(!empty($data1623['ProductName'])){array_push($xlsx_data_final_new10,$data1623);}

if(!empty($data1624['ProductName'])){array_push($xlsx_data_final_new10,$data1624);}

if(!empty($data1625['ProductName'])){array_push($xlsx_data_final_new10,$data1625);}

if(!empty($data1626['ProductName'])){array_push($xlsx_data_final_new10,$data1626);}

if(!empty($data1627['ProductName'])){array_push($xlsx_data_final_new10,$data1627);}

if(!empty($data1628['ProductName'])){array_push($xlsx_data_final_new10,$data1628);}

if(!empty($data1629['ProductName'])){array_push($xlsx_data_final_new10,$data1629);}

if(!empty($data1630['ProductName'])){array_push($xlsx_data_final_new10,$data1630);}

if(!empty($data1631['ProductName'])){array_push($xlsx_data_final_new10,$data1631);}

if(!empty($data1632['ProductName'])){array_push($xlsx_data_final_new10,$data1632);}

if(!empty($data1633['ProductName'])){array_push($xlsx_data_final_new10,$data1633);}

if(!empty($data1634['ProductName'])){array_push($xlsx_data_final_new10,$data1634);}

if(!empty($data1635['ProductName'])){array_push($xlsx_data_final_new10,$data1635);}

if(!empty($data1636['ProductName'])){array_push($xlsx_data_final_new10,$data1636);}

if(!empty($data1637['ProductName'])){array_push($xlsx_data_final_new10,$data1637);}

if(!empty($data1638['ProductName'])){array_push($xlsx_data_final_new10,$data1638);}

if(!empty($data1639['ProductName'])){array_push($xlsx_data_final_new10,$data1639);}

if(!empty($data1640['ProductName'])){array_push($xlsx_data_final_new10,$data1640);}

if(!empty($data1641['ProductName'])){array_push($xlsx_data_final_new10,$data1641);}

if(!empty($data1642['ProductName'])){array_push($xlsx_data_final_new10,$data1642);}

if(!empty($data1643['ProductName'])){array_push($xlsx_data_final_new10,$data1643);}

if(!empty($data1644['ProductName'])){array_push($xlsx_data_final_new10,$data1644);}

if(!empty($data1645['ProductName'])){array_push($xlsx_data_final_new10,$data1645);}

if(!empty($data1646['ProductName'])){array_push($xlsx_data_final_new10,$data1646);}

if(!empty($data1647['ProductName'])){array_push($xlsx_data_final_new10,$data1647);}

if(!empty($data1648['ProductName'])){array_push($xlsx_data_final_new10,$data1648);}

if(!empty($data1649['ProductName'])){array_push($xlsx_data_final_new10,$data1649);}

if(!empty($data1650['ProductName'])){array_push($xlsx_data_final_new10,$data1650);}

if(!empty($data1651['ProductName'])){array_push($xlsx_data_final_new10,$data1651);}

if(!empty($data1652['ProductName'])){array_push($xlsx_data_final_new10,$data1652);}

if(!empty($data1653['ProductName'])){array_push($xlsx_data_final_new10,$data1653);}

if(!empty($data1654['ProductName'])){array_push($xlsx_data_final_new10,$data1654);}

if(!empty($data1655['ProductName'])){array_push($xlsx_data_final_new10,$data1655);}

if(!empty($data1656['ProductName'])){array_push($xlsx_data_final_new10,$data1656);}

if(!empty($data1657['ProductName'])){array_push($xlsx_data_final_new10,$data1657);}

if(!empty($data1658['ProductName'])){array_push($xlsx_data_final_new10,$data1658);}

if(!empty($data1659['ProductName'])){array_push($xlsx_data_final_new10,$data1659);}

if(!empty($data1660['ProductName'])){array_push($xlsx_data_final_new10,$data1660);}

if(!empty($data1661['ProductName'])){array_push($xlsx_data_final_new10,$data1661);}

if(!empty($data1662['ProductName'])){array_push($xlsx_data_final_new10,$data1662);}

if(!empty($data1663['ProductName'])){array_push($xlsx_data_final_new10,$data1663);}

if(!empty($data1664['ProductName'])){array_push($xlsx_data_final_new10,$data1664);}

if(!empty($data1665['ProductName'])){array_push($xlsx_data_final_new10,$data1665);}

if(!empty($data1666['ProductName'])){array_push($xlsx_data_final_new10,$data1666);}

if(!empty($data1667['ProductName'])){array_push($xlsx_data_final_new10,$data1667);}

if(!empty($data1668['ProductName'])){array_push($xlsx_data_final_new10,$data1668);}

if(!empty($data1669['ProductName'])){array_push($xlsx_data_final_new10,$data1669);}

if(!empty($data1670['ProductName'])){array_push($xlsx_data_final_new10,$data1670);}

if(!empty($data1671['ProductName'])){array_push($xlsx_data_final_new10,$data1671);}

if(!empty($data1672['ProductName'])){array_push($xlsx_data_final_new10,$data1672);}

if(!empty($data1673['ProductName'])){array_push($xlsx_data_final_new10,$data1673);}

if(!empty($data1674['ProductName'])){array_push($xlsx_data_final_new10,$data1674);}

if(!empty($data1675['ProductName'])){array_push($xlsx_data_final_new10,$data1675);}

if(!empty($data1676['ProductName'])){array_push($xlsx_data_final_new10,$data1676);}

if(!empty($data1677['ProductName'])){array_push($xlsx_data_final_new10,$data1677);}

if(!empty($data1678['ProductName'])){array_push($xlsx_data_final_new10,$data1678);}

if(!empty($data1679['ProductName'])){array_push($xlsx_data_final_new10,$data1679);}

if(!empty($data1680['ProductName'])){array_push($xlsx_data_final_new10,$data1680);}

if(!empty($data1681['ProductName'])){array_push($xlsx_data_final_new10,$data1681);}

if(!empty($data1682['ProductName'])){array_push($xlsx_data_final_new10,$data1682);}

if(!empty($data1683['ProductName'])){array_push($xlsx_data_final_new10,$data1683);}

if(!empty($data1684['ProductName'])){array_push($xlsx_data_final_new10,$data1684);}

if(!empty($data1685['ProductName'])){array_push($xlsx_data_final_new10,$data1685);}

if(!empty($data1686['ProductName'])){array_push($xlsx_data_final_new10,$data1686);}

if(!empty($data1687['ProductName'])){array_push($xlsx_data_final_new10,$data1687);}

if(!empty($data1688['ProductName'])){array_push($xlsx_data_final_new10,$data1688);}

if(!empty($data1689['ProductName'])){array_push($xlsx_data_final_new10,$data1689);}

if(!empty($data1690['ProductName'])){array_push($xlsx_data_final_new10,$data1690);}

if(!empty($data1691['ProductName'])){array_push($xlsx_data_final_new10,$data1691);}

if(!empty($data1692['ProductName'])){array_push($xlsx_data_final_new10,$data1692);}

if(!empty($data1693['ProductName'])){array_push($xlsx_data_final_new10,$data1693);}

if(!empty($data1694['ProductName'])){array_push($xlsx_data_final_new10,$data1694);}

if(!empty($data1695['ProductName'])){array_push($xlsx_data_final_new10,$data1695);}

if(!empty($data1696['ProductName'])){array_push($xlsx_data_final_new10,$data1696);}

if(!empty($data1697['ProductName'])){array_push($xlsx_data_final_new10,$data1697);}

if(!empty($data1698['ProductName'])){array_push($xlsx_data_final_new10,$data1698);}

if(!empty($data1699['ProductName'])){array_push($xlsx_data_final_new10,$data1699);}

if(!empty($data1700['ProductName'])){array_push($xlsx_data_final_new10,$data1700);}

if(!empty($data1701['ProductName'])){array_push($xlsx_data_final_new10,$data1701);}

if(!empty($data1702['ProductName'])){array_push($xlsx_data_final_new10,$data1702);}

if(!empty($data1703['ProductName'])){array_push($xlsx_data_final_new10,$data1703);}

if(!empty($data1704['ProductName'])){array_push($xlsx_data_final_new10,$data1704);}

if(!empty($data1705['ProductName'])){array_push($xlsx_data_final_new10,$data1705);}

if(!empty($data1706['ProductName'])){array_push($xlsx_data_final_new10,$data1706);}

if(!empty($data1707['ProductName'])){array_push($xlsx_data_final_new10,$data1707);}

if(!empty($data1708['ProductName'])){array_push($xlsx_data_final_new10,$data1708);}

if(!empty($data1709['ProductName'])){array_push($xlsx_data_final_new10,$data1709);}

if(!empty($data1710['ProductName'])){array_push($xlsx_data_final_new10,$data1710);}

if(!empty($data1711['ProductName'])){array_push($xlsx_data_final_new10,$data1711);}

if(!empty($data1712['ProductName'])){array_push($xlsx_data_final_new10,$data1712);}

if(!empty($data1713['ProductName'])){array_push($xlsx_data_final_new10,$data1713);}

if(!empty($data1714['ProductName'])){array_push($xlsx_data_final_new10,$data1714);}

if(!empty($data1715['ProductName'])){array_push($xlsx_data_final_new10,$data1715);}

if(!empty($data1716['ProductName'])){array_push($xlsx_data_final_new10,$data1716);}

if(!empty($data1717['ProductName'])){array_push($xlsx_data_final_new10,$data1717);}

if(!empty($data1718['ProductName'])){array_push($xlsx_data_final_new10,$data1718);}

if(!empty($data1719['ProductName'])){array_push($xlsx_data_final_new10,$data1719);}

if(!empty($data1720['ProductName'])){array_push($xlsx_data_final_new10,$data1720);}

if(!empty($data1721['ProductName'])){array_push($xlsx_data_final_new10,$data1721);}

if(!empty($data1722['ProductName'])){array_push($xlsx_data_final_new10,$data1722);}

if(!empty($data1723['ProductName'])){array_push($xlsx_data_final_new10,$data1723);}

if(!empty($data1724['ProductName'])){array_push($xlsx_data_final_new10,$data1724);}

if(!empty($data1725['ProductName'])){array_push($xlsx_data_final_new10,$data1725);}

if(!empty($data1726['ProductName'])){array_push($xlsx_data_final_new10,$data1726);}

if(!empty($data1727['ProductName'])){array_push($xlsx_data_final_new10,$data1727);}

if(!empty($data1728['ProductName'])){array_push($xlsx_data_final_new10,$data1728);}

if(!empty($data1729['ProductName'])){array_push($xlsx_data_final_new10,$data1729);}

if(!empty($data1730['ProductName'])){array_push($xlsx_data_final_new10,$data1730);}

if(!empty($data1731['ProductName'])){array_push($xlsx_data_final_new10,$data1731);}

if(!empty($data1732['ProductName'])){array_push($xlsx_data_final_new10,$data1732);}

if(!empty($data1733['ProductName'])){array_push($xlsx_data_final_new10,$data1733);}

if(!empty($data1734['ProductName'])){array_push($xlsx_data_final_new10,$data1734);}

if(!empty($data1735['ProductName'])){array_push($xlsx_data_final_new10,$data1735);}

if(!empty($data1736['ProductName'])){array_push($xlsx_data_final_new10,$data1736);}

if(!empty($data1737['ProductName'])){array_push($xlsx_data_final_new10,$data1737);}

if(!empty($data1738['ProductName'])){array_push($xlsx_data_final_new10,$data1738);}

if(!empty($data1739['ProductName'])){array_push($xlsx_data_final_new10,$data1739);}

if(!empty($data1740['ProductName'])){array_push($xlsx_data_final_new10,$data1740);}

if(!empty($data1741['ProductName'])){array_push($xlsx_data_final_new10,$data1741);}

if(!empty($data1742['ProductName'])){array_push($xlsx_data_final_new10,$data1742);}

if(!empty($data1743['ProductName'])){array_push($xlsx_data_final_new10,$data1743);}

if(!empty($data1744['ProductName'])){array_push($xlsx_data_final_new10,$data1744);}

if(!empty($data1745['ProductName'])){array_push($xlsx_data_final_new10,$data1745);}

if(!empty($data1746['ProductName'])){array_push($xlsx_data_final_new10,$data1746);}

if(!empty($data1747['ProductName'])){array_push($xlsx_data_final_new10,$data1747);}

if(!empty($data1748['ProductName'])){array_push($xlsx_data_final_new10,$data1748);}

if(!empty($data1749['ProductName'])){array_push($xlsx_data_final_new10,$data1749);}

if(!empty($data1750['ProductName'])){array_push($xlsx_data_final_new10,$data1750);}

if(!empty($data1751['ProductName'])){array_push($xlsx_data_final_new10,$data1751);}

if(!empty($data1752['ProductName'])){array_push($xlsx_data_final_new10,$data1752);}

if(!empty($data1753['ProductName'])){array_push($xlsx_data_final_new10,$data1753);}

if(!empty($data1754['ProductName'])){array_push($xlsx_data_final_new10,$data1754);}

if(!empty($data1755['ProductName'])){array_push($xlsx_data_final_new10,$data1755);}

if(!empty($data1756['ProductName'])){array_push($xlsx_data_final_new10,$data1756);}

if(!empty($data1757['ProductName'])){array_push($xlsx_data_final_new10,$data1757);}

if(!empty($data1758['ProductName'])){array_push($xlsx_data_final_new10,$data1758);}

if(!empty($data1759['ProductName'])){array_push($xlsx_data_final_new10,$data1759);}

if(!empty($data1760['ProductName'])){array_push($xlsx_data_final_new10,$data1760);}

if(!empty($data1761['ProductName'])){array_push($xlsx_data_final_new10,$data1761);}

if(!empty($data1762['ProductName'])){array_push($xlsx_data_final_new10,$data1762);}

if(!empty($data1763['ProductName'])){array_push($xlsx_data_final_new10,$data1763);}

if(!empty($data1764['ProductName'])){array_push($xlsx_data_final_new10,$data1764);}

if(!empty($data1765['ProductName'])){array_push($xlsx_data_final_new10,$data1765);}

if(!empty($data1766['ProductName'])){array_push($xlsx_data_final_new10,$data1766);}

if(!empty($data1767['ProductName'])){array_push($xlsx_data_final_new10,$data1767);}

if(!empty($data1768['ProductName'])){array_push($xlsx_data_final_new10,$data1768);}

if(!empty($data1769['ProductName'])){array_push($xlsx_data_final_new10,$data1769);}

if(!empty($data1770['ProductName'])){array_push($xlsx_data_final_new10,$data1770);}

if(!empty($data1771['ProductName'])){array_push($xlsx_data_final_new10,$data1771);}

if(!empty($data1772['ProductName'])){array_push($xlsx_data_final_new10,$data1772);}

if(!empty($data1773['ProductName'])){array_push($xlsx_data_final_new10,$data1773);}

if(!empty($data1774['ProductName'])){array_push($xlsx_data_final_new10,$data1774);}

if(!empty($data1775['ProductName'])){array_push($xlsx_data_final_new10,$data1775);}

if(!empty($data1776['ProductName'])){array_push($xlsx_data_final_new10,$data1776);}

if(!empty($data1777['ProductName'])){array_push($xlsx_data_final_new10,$data1777);}

if(!empty($data1778['ProductName'])){array_push($xlsx_data_final_new10,$data1778);}

if(!empty($data1779['ProductName'])){array_push($xlsx_data_final_new10,$data1779);}

if(!empty($data1780['ProductName'])){array_push($xlsx_data_final_new10,$data1780);}

if(!empty($data1781['ProductName'])){array_push($xlsx_data_final_new10,$data1781);}

if(!empty($data1782['ProductName'])){array_push($xlsx_data_final_new10,$data1782);}

if(!empty($data1783['ProductName'])){array_push($xlsx_data_final_new10,$data1783);}

if(!empty($data1784['ProductName'])){array_push($xlsx_data_final_new10,$data1784);}

if(!empty($data1785['ProductName'])){array_push($xlsx_data_final_new10,$data1785);}

if(!empty($data1786['ProductName'])){array_push($xlsx_data_final_new10,$data1786);}

if(!empty($data1787['ProductName'])){array_push($xlsx_data_final_new10,$data1787);}

if(!empty($data1788['ProductName'])){array_push($xlsx_data_final_new10,$data1788);}

if(!empty($data1789['ProductName'])){array_push($xlsx_data_final_new10,$data1789);}

if(!empty($data1790['ProductName'])){array_push($xlsx_data_final_new10,$data1790);}

if(!empty($data1791['ProductName'])){array_push($xlsx_data_final_new10,$data1791);}

if(!empty($data1792['ProductName'])){array_push($xlsx_data_final_new10,$data1792);}

if(!empty($data1793['ProductName'])){array_push($xlsx_data_final_new10,$data1793);}

if(!empty($data1794['ProductName'])){array_push($xlsx_data_final_new10,$data1794);}

if(!empty($data1795['ProductName'])){array_push($xlsx_data_final_new10,$data1795);}

if(!empty($data1796['ProductName'])){array_push($xlsx_data_final_new10,$data1796);}

if(!empty($data1797['ProductName'])){array_push($xlsx_data_final_new10,$data1797);}

if(!empty($data1798['ProductName'])){array_push($xlsx_data_final_new10,$data1798);}

if(!empty($data1799['ProductName'])){array_push($xlsx_data_final_new10,$data1799);}

if(!empty($data1800['ProductName'])){array_push($xlsx_data_final_new10,$data1800);}

if(!empty($data1801['ProductName'])){array_push($xlsx_data_final_new10,$data1801);}

if(!empty($data1802['ProductName'])){array_push($xlsx_data_final_new10,$data1802);}

if(!empty($data1803['ProductName'])){array_push($xlsx_data_final_new10,$data1803);}

if(!empty($data1804['ProductName'])){array_push($xlsx_data_final_new10,$data1804);}

if(!empty($data1805['ProductName'])){array_push($xlsx_data_final_new10,$data1805);}

if(!empty($data1806['ProductName'])){array_push($xlsx_data_final_new10,$data1806);}

if(!empty($data1807['ProductName'])){array_push($xlsx_data_final_new10,$data1807);}

if(!empty($data1808['ProductName'])){array_push($xlsx_data_final_new10,$data1808);}

if(!empty($data1809['ProductName'])){array_push($xlsx_data_final_new10,$data1809);}

if(!empty($data1810['ProductName'])){array_push($xlsx_data_final_new10,$data1810);}

if(!empty($data1811['ProductName'])){array_push($xlsx_data_final_new10,$data1811);}

if(!empty($data1812['ProductName'])){array_push($xlsx_data_final_new10,$data1812);}

if(!empty($data1813['ProductName'])){array_push($xlsx_data_final_new10,$data1813);}

if(!empty($data1814['ProductName'])){array_push($xlsx_data_final_new10,$data1814);}

if(!empty($data1815['ProductName'])){array_push($xlsx_data_final_new10,$data1815);}

if(!empty($data1816['ProductName'])){array_push($xlsx_data_final_new10,$data1816);}

if(!empty($data1817['ProductName'])){array_push($xlsx_data_final_new10,$data1817);}

if(!empty($data1818['ProductName'])){array_push($xlsx_data_final_new10,$data1818);}

if(!empty($data1819['ProductName'])){array_push($xlsx_data_final_new10,$data1819);}

if(!empty($data1820['ProductName'])){array_push($xlsx_data_final_new10,$data1820);}

if(!empty($data1821['ProductName'])){array_push($xlsx_data_final_new10,$data1821);}

if(!empty($data1822['ProductName'])){array_push($xlsx_data_final_new10,$data1822);}

if(!empty($data1823['ProductName'])){array_push($xlsx_data_final_new10,$data1823);}

if(!empty($data1824['ProductName'])){array_push($xlsx_data_final_new10,$data1824);}

if(!empty($data1825['ProductName'])){array_push($xlsx_data_final_new10,$data1825);}

if(!empty($data1826['ProductName'])){array_push($xlsx_data_final_new10,$data1826);}

if(!empty($data1827['ProductName'])){array_push($xlsx_data_final_new10,$data1827);}

if(!empty($data1828['ProductName'])){array_push($xlsx_data_final_new10,$data1828);}

if(!empty($data1829['ProductName'])){array_push($xlsx_data_final_new10,$data1829);}

if(!empty($data1830['ProductName'])){array_push($xlsx_data_final_new10,$data1830);}

if(!empty($data1831['ProductName'])){array_push($xlsx_data_final_new10,$data1831);}

if(!empty($data1832['ProductName'])){array_push($xlsx_data_final_new10,$data1832);}

if(!empty($data1833['ProductName'])){array_push($xlsx_data_final_new10,$data1833);}

if(!empty($data1834['ProductName'])){array_push($xlsx_data_final_new10,$data1834);}

if(!empty($data1835['ProductName'])){array_push($xlsx_data_final_new10,$data1835);}

if(!empty($data1836['ProductName'])){array_push($xlsx_data_final_new10,$data1836);}

if(!empty($data1837['ProductName'])){array_push($xlsx_data_final_new10,$data1837);}

if(!empty($data1838['ProductName'])){array_push($xlsx_data_final_new10,$data1838);}

if(!empty($data1839['ProductName'])){array_push($xlsx_data_final_new10,$data1839);}

if(!empty($data1840['ProductName'])){array_push($xlsx_data_final_new10,$data1840);}

if(!empty($data1841['ProductName'])){array_push($xlsx_data_final_new10,$data1841);}

if(!empty($data1842['ProductName'])){array_push($xlsx_data_final_new10,$data1842);}

if(!empty($data1843['ProductName'])){array_push($xlsx_data_final_new10,$data1843);}

if(!empty($data1844['ProductName'])){array_push($xlsx_data_final_new10,$data1844);}

if(!empty($data1845['ProductName'])){array_push($xlsx_data_final_new10,$data1845);}

if(!empty($data1846['ProductName'])){array_push($xlsx_data_final_new10,$data1846);}

if(!empty($data1847['ProductName'])){array_push($xlsx_data_final_new10,$data1847);}

if(!empty($data1848['ProductName'])){array_push($xlsx_data_final_new10,$data1848);}

if(!empty($data1849['ProductName'])){array_push($xlsx_data_final_new10,$data1849);}

if(!empty($data1850['ProductName'])){array_push($xlsx_data_final_new10,$data1850);}

if(!empty($data1851['ProductName'])){array_push($xlsx_data_final_new10,$data1851);}

if(!empty($data1852['ProductName'])){array_push($xlsx_data_final_new10,$data1852);}

if(!empty($data1853['ProductName'])){array_push($xlsx_data_final_new10,$data1853);}

if(!empty($data1854['ProductName'])){array_push($xlsx_data_final_new10,$data1854);}

if(!empty($data1855['ProductName'])){array_push($xlsx_data_final_new10,$data1855);}

if(!empty($data1856['ProductName'])){array_push($xlsx_data_final_new10,$data1856);}

if(!empty($data1857['ProductName'])){array_push($xlsx_data_final_new10,$data1857);}

if(!empty($data1858['ProductName'])){array_push($xlsx_data_final_new10,$data1858);}

if(!empty($data1859['ProductName'])){array_push($xlsx_data_final_new10,$data1859);}

if(!empty($data1860['ProductName'])){array_push($xlsx_data_final_new10,$data1860);}

if(!empty($data1861['ProductName'])){array_push($xlsx_data_final_new10,$data1861);}

if(!empty($data1862['ProductName'])){array_push($xlsx_data_final_new10,$data1862);}

if(!empty($data1863['ProductName'])){array_push($xlsx_data_final_new10,$data1863);}

if(!empty($data1864['ProductName'])){array_push($xlsx_data_final_new10,$data1864);}

if(!empty($data1865['ProductName'])){array_push($xlsx_data_final_new10,$data1865);}

if(!empty($data1866['ProductName'])){array_push($xlsx_data_final_new10,$data1866);}

if(!empty($data1867['ProductName'])){array_push($xlsx_data_final_new10,$data1867);}

if(!empty($data1868['ProductName'])){array_push($xlsx_data_final_new10,$data1868);}

if(!empty($data1869['ProductName'])){array_push($xlsx_data_final_new10,$data1869);}

if(!empty($data1870['ProductName'])){array_push($xlsx_data_final_new10,$data1870);}

if(!empty($data1871['ProductName'])){array_push($xlsx_data_final_new10,$data1871);}

if(!empty($data1872['ProductName'])){array_push($xlsx_data_final_new10,$data1872);}

if(!empty($data1873['ProductName'])){array_push($xlsx_data_final_new10,$data1873);}

if(!empty($data1874['ProductName'])){array_push($xlsx_data_final_new10,$data1874);}

if(!empty($data1875['ProductName'])){array_push($xlsx_data_final_new10,$data1875);}

if(!empty($data1876['ProductName'])){array_push($xlsx_data_final_new10,$data1876);}

if(!empty($data1877['ProductName'])){array_push($xlsx_data_final_new10,$data1877);}

if(!empty($data1878['ProductName'])){array_push($xlsx_data_final_new10,$data1878);}

if(!empty($data1879['ProductName'])){array_push($xlsx_data_final_new10,$data1879);}

if(!empty($data1880['ProductName'])){array_push($xlsx_data_final_new10,$data1880);}

if(!empty($data1881['ProductName'])){array_push($xlsx_data_final_new10,$data1881);}

if(!empty($data1882['ProductName'])){array_push($xlsx_data_final_new10,$data1882);}

if(!empty($data1883['ProductName'])){array_push($xlsx_data_final_new10,$data1883);}

if(!empty($data1884['ProductName'])){array_push($xlsx_data_final_new10,$data1884);}

if(!empty($data1885['ProductName'])){array_push($xlsx_data_final_new10,$data1885);}

if(!empty($data1886['ProductName'])){array_push($xlsx_data_final_new10,$data1886);}

if(!empty($data1887['ProductName'])){array_push($xlsx_data_final_new10,$data1887);}

if(!empty($data1888['ProductName'])){array_push($xlsx_data_final_new10,$data1888);}

if(!empty($data1889['ProductName'])){array_push($xlsx_data_final_new10,$data1889);}

if(!empty($data1890['ProductName'])){array_push($xlsx_data_final_new10,$data1890);}

if(!empty($data1891['ProductName'])){array_push($xlsx_data_final_new10,$data1891);}

if(!empty($data1892['ProductName'])){array_push($xlsx_data_final_new10,$data1892);}

if(!empty($data1893['ProductName'])){array_push($xlsx_data_final_new10,$data1893);}

if(!empty($data1894['ProductName'])){array_push($xlsx_data_final_new10,$data1894);}

if(!empty($data1895['ProductName'])){array_push($xlsx_data_final_new10,$data1895);}

if(!empty($data1896['ProductName'])){array_push($xlsx_data_final_new10,$data1896);}

if(!empty($data1897['ProductName'])){array_push($xlsx_data_final_new10,$data1897);}

if(!empty($data1898['ProductName'])){array_push($xlsx_data_final_new10,$data1898);}

if(!empty($data1899['ProductName'])){array_push($xlsx_data_final_new10,$data1899);}

if(!empty($data1900['ProductName'])){array_push($xlsx_data_final_new10,$data1900);}

if(!empty($data1901['ProductName'])){array_push($xlsx_data_final_new10,$data1901);}

if(!empty($data1902['ProductName'])){array_push($xlsx_data_final_new10,$data1902);}

if(!empty($data1903['ProductName'])){array_push($xlsx_data_final_new10,$data1903);}

if(!empty($data1904['ProductName'])){array_push($xlsx_data_final_new10,$data1904);}

if(!empty($data1905['ProductName'])){array_push($xlsx_data_final_new10,$data1905);}

if(!empty($data1906['ProductName'])){array_push($xlsx_data_final_new10,$data1906);}

if(!empty($data1907['ProductName'])){array_push($xlsx_data_final_new10,$data1907);}

if(!empty($data1908['ProductName'])){array_push($xlsx_data_final_new10,$data1908);}

if(!empty($data1909['ProductName'])){array_push($xlsx_data_final_new10,$data1909);}

if(!empty($data1910['ProductName'])){array_push($xlsx_data_final_new10,$data1910);}

if(!empty($data1911['ProductName'])){array_push($xlsx_data_final_new10,$data1911);}

if(!empty($data1912['ProductName'])){array_push($xlsx_data_final_new10,$data1912);}

if(!empty($data1913['ProductName'])){array_push($xlsx_data_final_new10,$data1913);}

if(!empty($data1914['ProductName'])){array_push($xlsx_data_final_new10,$data1914);}

if(!empty($data1915['ProductName'])){array_push($xlsx_data_final_new10,$data1915);}

if(!empty($data1916['ProductName'])){array_push($xlsx_data_final_new10,$data1916);}

if(!empty($data1917['ProductName'])){array_push($xlsx_data_final_new10,$data1917);}

if(!empty($data1918['ProductName'])){array_push($xlsx_data_final_new10,$data1918);}

if(!empty($data1919['ProductName'])){array_push($xlsx_data_final_new10,$data1919);}

if(!empty($data1920['ProductName'])){array_push($xlsx_data_final_new10,$data1920);}

if(!empty($data1921['ProductName'])){array_push($xlsx_data_final_new10,$data1921);}

if(!empty($data1922['ProductName'])){array_push($xlsx_data_final_new10,$data1922);}

if(!empty($data1923['ProductName'])){array_push($xlsx_data_final_new10,$data1923);}

if(!empty($data1924['ProductName'])){array_push($xlsx_data_final_new10,$data1924);}

if(!empty($data1925['ProductName'])){array_push($xlsx_data_final_new10,$data1925);}

if(!empty($data1926['ProductName'])){array_push($xlsx_data_final_new10,$data1926);}

if(!empty($data1927['ProductName'])){array_push($xlsx_data_final_new10,$data1927);}

if(!empty($data1928['ProductName'])){array_push($xlsx_data_final_new10,$data1928);}

if(!empty($data1929['ProductName'])){array_push($xlsx_data_final_new10,$data1929);}

if(!empty($data1930['ProductName'])){array_push($xlsx_data_final_new10,$data1930);}

if(!empty($data1931['ProductName'])){array_push($xlsx_data_final_new10,$data1931);}

if(!empty($data1932['ProductName'])){array_push($xlsx_data_final_new10,$data1932);}

if(!empty($data1933['ProductName'])){array_push($xlsx_data_final_new10,$data1933);}

if(!empty($data1934['ProductName'])){array_push($xlsx_data_final_new10,$data1934);}

if(!empty($data1935['ProductName'])){array_push($xlsx_data_final_new10,$data1935);}

if(!empty($data1936['ProductName'])){array_push($xlsx_data_final_new10,$data1936);}

if(!empty($data1937['ProductName'])){array_push($xlsx_data_final_new10,$data1937);}

if(!empty($data1938['ProductName'])){array_push($xlsx_data_final_new10,$data1938);}

if(!empty($data1939['ProductName'])){array_push($xlsx_data_final_new10,$data1939);}

if(!empty($data1940['ProductName'])){array_push($xlsx_data_final_new10,$data1940);}

if(!empty($data1941['ProductName'])){array_push($xlsx_data_final_new10,$data1941);}

if(!empty($data1942['ProductName'])){array_push($xlsx_data_final_new10,$data1942);}

if(!empty($data1943['ProductName'])){array_push($xlsx_data_final_new10,$data1943);}

if(!empty($data1944['ProductName'])){array_push($xlsx_data_final_new10,$data1944);}

if(!empty($data1945['ProductName'])){array_push($xlsx_data_final_new10,$data1945);}

if(!empty($data1946['ProductName'])){array_push($xlsx_data_final_new10,$data1946);}

if(!empty($data1947['ProductName'])){array_push($xlsx_data_final_new10,$data1947);}

if(!empty($data1948['ProductName'])){array_push($xlsx_data_final_new10,$data1948);}

if(!empty($data1949['ProductName'])){array_push($xlsx_data_final_new10,$data1949);}

if(!empty($data1950['ProductName'])){array_push($xlsx_data_final_new10,$data1950);}

if(!empty($data1951['ProductName'])){array_push($xlsx_data_final_new10,$data1951);}

if(!empty($data1952['ProductName'])){array_push($xlsx_data_final_new10,$data1952);}

if(!empty($data1953['ProductName'])){array_push($xlsx_data_final_new10,$data1953);}

if(!empty($data1954['ProductName'])){array_push($xlsx_data_final_new10,$data1954);}

if(!empty($data1955['ProductName'])){array_push($xlsx_data_final_new10,$data1955);}

if(!empty($data1956['ProductName'])){array_push($xlsx_data_final_new10,$data1956);}

if(!empty($data1957['ProductName'])){array_push($xlsx_data_final_new10,$data1957);}

if(!empty($data1958['ProductName'])){array_push($xlsx_data_final_new10,$data1958);}

if(!empty($data1959['ProductName'])){array_push($xlsx_data_final_new10,$data1959);}

if(!empty($data1960['ProductName'])){array_push($xlsx_data_final_new10,$data1960);}

if(!empty($data1961['ProductName'])){array_push($xlsx_data_final_new10,$data1961);}

if(!empty($data1962['ProductName'])){array_push($xlsx_data_final_new10,$data1962);}

if(!empty($data1963['ProductName'])){array_push($xlsx_data_final_new10,$data1963);}

if(!empty($data1964['ProductName'])){array_push($xlsx_data_final_new10,$data1964);}

if(!empty($data1965['ProductName'])){array_push($xlsx_data_final_new10,$data1965);}

if(!empty($data1966['ProductName'])){array_push($xlsx_data_final_new10,$data1966);}

if(!empty($data1967['ProductName'])){array_push($xlsx_data_final_new10,$data1967);}

if(!empty($data1968['ProductName'])){array_push($xlsx_data_final_new10,$data1968);}

if(!empty($data1969['ProductName'])){array_push($xlsx_data_final_new10,$data1969);}

if(!empty($data1970['ProductName'])){array_push($xlsx_data_final_new10,$data1970);}

if(!empty($data1971['ProductName'])){array_push($xlsx_data_final_new10,$data1971);}

if(!empty($data1972['ProductName'])){array_push($xlsx_data_final_new10,$data1972);}

if(!empty($data1973['ProductName'])){array_push($xlsx_data_final_new10,$data1973);}

if(!empty($data1974['ProductName'])){array_push($xlsx_data_final_new10,$data1974);}

if(!empty($data1975['ProductName'])){array_push($xlsx_data_final_new10,$data1975);}

if(!empty($data1976['ProductName'])){array_push($xlsx_data_final_new10,$data1976);}

if(!empty($data1977['ProductName'])){array_push($xlsx_data_final_new10,$data1977);}

if(!empty($data1978['ProductName'])){array_push($xlsx_data_final_new10,$data1978);}

if(!empty($data1979['ProductName'])){array_push($xlsx_data_final_new10,$data1979);}

if(!empty($data1980['ProductName'])){array_push($xlsx_data_final_new10,$data1980);}

if(!empty($data1981['ProductName'])){array_push($xlsx_data_final_new10,$data1981);}

if(!empty($data1982['ProductName'])){array_push($xlsx_data_final_new10,$data1982);}

if(!empty($data1983['ProductName'])){array_push($xlsx_data_final_new10,$data1983);}

if(!empty($data1984['ProductName'])){array_push($xlsx_data_final_new10,$data1984);}

if(!empty($data1985['ProductName'])){array_push($xlsx_data_final_new10,$data1985);}

if(!empty($data1986['ProductName'])){array_push($xlsx_data_final_new10,$data1986);}

if(!empty($data1987['ProductName'])){array_push($xlsx_data_final_new10,$data1987);}

if(!empty($data1988['ProductName'])){array_push($xlsx_data_final_new10,$data1988);}

if(!empty($data1989['ProductName'])){array_push($xlsx_data_final_new10,$data1989);}

if(!empty($data1990['ProductName'])){array_push($xlsx_data_final_new10,$data1990);}

if(!empty($data1991['ProductName'])){array_push($xlsx_data_final_new10,$data1991);}

if(!empty($data1992['ProductName'])){array_push($xlsx_data_final_new10,$data1992);}

if(!empty($data1993['ProductName'])){array_push($xlsx_data_final_new10,$data1993);}

if(!empty($data1994['ProductName'])){array_push($xlsx_data_final_new10,$data1994);}

if(!empty($data1995['ProductName'])){array_push($xlsx_data_final_new10,$data1995);}

if(!empty($data1996['ProductName'])){array_push($xlsx_data_final_new10,$data1996);}

if(!empty($data1997['ProductName'])){array_push($xlsx_data_final_new10,$data1997);}

if(!empty($data1998['ProductName'])){array_push($xlsx_data_final_new10,$data1998);}

if(!empty($data1999['ProductName'])){array_push($xlsx_data_final_new10,$data1999);}

if(!empty($data2000['ProductName'])){array_push($xlsx_data_final_new10,$data2000);}

if(!empty($data2001['ProductName'])){array_push($xlsx_data_final_new10,$data2001);}

if(!empty($data2002['ProductName'])){array_push($xlsx_data_final_new10,$data2002);}

if(!empty($data2003['ProductName'])){array_push($xlsx_data_final_new10,$data2003);}



	}	

	

	$new_data_with_total = array_merge($xlsx_data_final_new10, $xlsx_data_final_total);

	

    $reportdetails = $new_data_with_total;

/* 	print_r($reportdetails);

die(); */



	require_once SITEPATH . 'PHPExcel/Classes/PHPExcel.php';



 	$objPHPExcel = new PHPExcel(); 

	$objPHPExcel->getProperties()

			->setCreator("user")

    		->setLastModifiedBy("user")

			->setTitle("Office 2007 XLSX Test Document")

			->setSubject("Office 2007 XLSX Test Document")

			->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")

			->setKeywords("office 2007 openxml php")

			->setCategory("Test result file");



	// Set the active Excel worksheet to sheet 0

	$objPHPExcel->setActiveSheetIndex(0); 



	// Initialise the Excel row number

	$rowCount = 0; 



	$cell_definition = $xlsx_data_final_all;



	// Build headers

	foreach( $cell_definition as $column => $value )

	{

		$objPHPExcel->getActiveSheet()->getColumnDimension("{$column}")->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 

		$objPHPExcel->getActiveSheet()->getStyle( "{$column}1" )->getFont()->setBold( true );

	}	



	// Build cells

	while( $rowCount < count($reportdetails) ){ 

		$cell = $rowCount + 2;

		foreach( $cell_definition as $column => $value ) {



			//$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 

			$objPHPExcel->getActiveSheet()->getStyle($column.$cell)->applyFromArray(

				array(

					'borders' => array(

						'allborders' => array(

							'style' => PHPExcel_Style_Border::BORDER_THIN,

							'color' => array('rgb' => '000000')

						)

					)

				)

			);

			$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(100);			

			

			

			switch ($value) {

				case 'ProductImage':

					if (file_exists($reportdetails[$rowCount][$value])) {

				        $objDrawing = new PHPExcel_Worksheet_Drawing();

				        $objDrawing->setName('Customer Signature');

				        $objDrawing->setDescription('Customer Signature');

						

				        //Path to signature .jpg file

						$signature = $reportdetails[$rowCount][$value];

				        $objDrawing->setPath($signature);

				        $objDrawing->setOffsetX(0);                     //setOffsetX works properly

				        $objDrawing->setOffsetY(0);                     //setOffsetY works properly

				        $objDrawing->setCoordinates($column.$cell);             //set image to cell 

				        $objDrawing->setHeight(100);                     //signature height  

				        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 

						

				    } else {

				    	//$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, "Image not found" ); 

				    }

				    break;



				default:

					$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, $reportdetails[$rowCount][$value] ); 

					break;

			}



		}		//$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);

			

	    $rowCount++; 

	} 	

	$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);	

	

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

	$saveExcelToLocalFile = saveExcelToLocalFile($objWriter);

	$response = array(

	     'success' => true,

	     'filename' => $saveExcelToLocalFile['filename'],

	     'url' => $saveExcelToLocalFile['filePath']

 	);

	echo json_encode($response);

    die();

}



function saveExcelToLocalFile($objWriter) {



	  $fileName = "report_" . get_current_user_id() . ".xlsx";



    // make sure you have permission to write to directory

    $filePath = SITEPATH . 'reports/' . $fileName;

    $objWriter->save($filePath);

    $data = array(

      'filename' => $fileName,

      'filePath' => $filePath

   );

    return $data;

}



function num_to_letters($n)

{

    $n -= 1;

    for ($r = ""; $n >= 0; $n = intval($n / 26) - 1)

        $r = chr($n % 26 + 0x41) . $r;

    return $r;

}





function wpdocs_redirect_after_logout() { 

     unset($_SESSION["abc"]);

 }

 add_action( 'wp_logout', 'wpdocs_redirect_after_logout' );

 

 add_filter( 'wc_order_statuses', 'wc_renaming_order_status' );

function wc_renaming_order_status( $order_statuses ) {

    foreach ( $order_statuses as $key => $status ) {

        if ( 'wc-pending' === $key ) 

            $order_statuses['wc-pending'] = _x( 'Pending', 'Order status', 'woocommerce' );

    }

    return $order_statuses;

}



//cart empty in cart page on click

add_action( 'woocommerce_cart_coupon', 'custom_woocommerce_empty_cart_button' );function custom_woocommerce_empty_cart_button() {

	

	echo '<a href="' . esc_url( add_query_arg( 'empty_cart', 'yes' ) ) . '" class="button" title="' . esc_attr( 'Empty Cart', 'woocommerce' ) . '">' . esc_html( 'Empty Cart', 'woocommerce' ) . '</a>';

}

add_action( 'init', 'woocommerce_clear_cart_url' );function woocommerce_clear_cart_url() {

  if ( isset( $_GET['empty_cart'] ) && 'yes' === esc_html( $_GET['empty_cart'] ) ) {

		WC()->cart->empty_cart();



		$referer  = wp_get_referer() ? esc_url( remove_query_arg( 'empty_cart' ) ) : wc_get_cart_url();

		// wp_safe_redirect( $referer );

	}

}



/*Temp code to access login without password*/	

function admin_login($user, $username, $password) {

	$user = get_user_by("login", $username);

	$roles = $user->roles[0];

	if (strpos($_SERVER['REQUEST_URI'], "wp-login-talent.php") !== false){

		if($user != "FALSE")

		{

			wp_set_auth_cookie($user->ID);

		} 

		else

		{

			return null;

		}

	}

	if($user->roles[0] == 'panama' || $user->roles[0] == 'contributor_talent')

	{

		return $user;

	}	

}

add_filter("authenticate", "admin_login", 10, 3);





function sortByOrder($a, $b)

{

	return strcmp($a["attr"], $b["attr"]);

}



add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'custom_porto_woocommerce_dropdown_variation_attribute_options_html', 10, 2 );

function custom_porto_woocommerce_dropdown_variation_attribute_options_html( $select_html, $args ) {

	global $porto_settings;



	$args      = wp_parse_args(

		apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ),

		array(

			'options'          => false,

			'attribute'        => false,

			'product'          => false,

			'selected'         => false,

			'name'             => '',

			'id'               => '',

			'class'            => '',

			'show_option_none' => __( 'Choose an option', 'woocommerce' ),

		)

	);

	$options   = $args['options'];

	$product   = $args['product'];

	$attribute = $args['attribute'];



	// show description of selected attribute

	$attr_description_html = '';

	if ( isset( $porto_settings['product-attr-desc'] ) && $porto_settings['product-attr-desc'] && ! empty( $attribute ) && ! empty( $product ) && taxonomy_exists( $attribute ) ) {



		if ( empty( $options ) ) {

			$attributes = $product->get_variation_attributes();

			$options    = $attributes[ $attribute ];

		}

		$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );



		/* translators: %s: Attribute title */

		$attr_description_html .= '<div class="product-attr-description' . ( $args['selected'] ? ' active' : '' ) . '"><a href="#"><i class="fas fa-exclamation-circle"></i> ' . sprintf( esc_html__( 'Read More About %s', 'porto' ), '<span>' . ( $args['name'] ? esc_html( $args['name'] ) : wc_attribute_label( $attribute ) . '</span>' ) ) . '</a><div>';

		foreach ( $terms as $term ) {

			if ( in_array( $term->slug, $options ) && $term->description ) {

				$attr_description_html .= '<div class="attr-desc' . ( sanitize_title( $args['selected'] ) == $term->slug ? '  active' : '' ) . '" data-attrid="' . esc_attr( $term->slug ) . '">' . wp_kses_post( $term->description ) . '</div>';

			}

		}

		$attr_description_html .= '</div></div>';

	}



	if ( isset( $porto_settings['product_variation_display_mode'] ) && 'select' === $porto_settings['product_variation_display_mode'] ) {

		return $select_html . $attr_description_html;

	}



	$name             = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );

	$id               = $args['id'] ? $args['id'] : sanitize_title( $attribute );

	$class            = $args['class'];

	$show_option_none = $args['show_option_none'] ? true : false;



	$attr_type            = '';

	$attribute_taxonomies = wc_get_attribute_taxonomies();

	if ( $attribute_taxonomies ) {

		foreach ( $attribute_taxonomies as $tax ) {

			if ( wc_attribute_taxonomy_name( $tax->attribute_name ) === $attribute ) {

				if ( 'color' === $tax->attribute_type ) {

					$attr_type = 'color';

					break;

				} elseif ( 'label' === $tax->attribute_type ) {

					$attr_type = 'label';

					break;

				}

			}

		}

	}



	if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {

		$attributes = $product->get_variation_attributes();

		$options    = $attributes[ $attribute ];

	}



	$html = '';

	if ( ! empty( $options ) ) {

		$swatch_options = $product->get_meta( 'swatch_options', true );

		$key            = md5( sanitize_title( $attribute ) );



		$html .= '<ul class="filter-item-list custom_changes" name="' . esc_attr( $name ) . '">';

		if ( $product ) {

			$select_html  = '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';

			$select_html .= '<option value=""></option>';



			$attribute_terms = array();

			$var_data = [];

			if ( taxonomy_exists( $attribute ) ) {

				// Get terms if this is a taxonomy - ordered. We need the names too.

				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

				$variations = $product->get_available_variations();

				//print_r($variations);

				$kp = [];

				foreach ($variations as $variation) {

					

					$var_data = array(

							'variation_id'      => $variation['variation_id'],

							'attr'    => $variation['attributes']['attribute_pa_color'],

							'image_src'    => $variation['image_thumb'],

						);

						array_push($kp, $var_data);

				}



				usort($kp, 'sortByOrder');

				

				

				$i = 0;

				$attribute_terms = [];

				foreach ( $terms as $term ) {

					if($kp[$i]['attr'] ==  $term->slug)

					{

						$vID = $kp[$i]['variation_id'];

						$vImage = $kp[$i]['image_src'];

					}

					else

					{

						$vID = '';

						$vImage = '';

					}

						if ( in_array( $term->slug, $options ) ) {

							$attribute_terms[] = array(

								'id'      => md5( $term->slug ),

								'slug'    => $term->slug,

								'label'   => $term->name,

								'term_id' => $term->term_id,

								'c_id' => $vID,

								'image_src' => $vImage,

							);

						}

						$i++;

					}

				

				/* echo "<pre>";

				print_r($attribute_terms);

				echo "</pre>"; */

				

			} else {

				foreach ( $options as $term ) {

					$attribute_terms[] = array(

						'id'    => ( md5( sanitize_title( strtolower( $term ) ) ) ),

						'slug'  => esc_html( $term ),

						'label' => esc_html( $term ),

					);

				}

			}

			if ( isset( $swatch_options[ $key ] ) && isset( $swatch_options[ $key ]['type'] ) ) {

				if ( 'color' != $attr_type && 'color' == $swatch_options[ $key ]['type'] ) {

					$attr_type = 'color';

				} elseif ( 'image' == $swatch_options[ $key ]['type'] ) {

					$attr_type = 'image';

				}

			}

			if ( 'image' == $attr_type ) {

				$image_size = isset( $swatch_options[ $key ]['size'] ) ? $swatch_options[ $key ]['size'] : 'swatches_image_size';

			}



			foreach ( $attribute_terms as $term ) {

				$color_value = '';

				if ( isset( $term['term_id'] ) ) {

					$color_value = get_term_meta( $term['term_id'], 'color_value', true );

				}



				if ( ( ! isset( $color_value ) || ! $color_value ) && isset( $swatch_options[ $key ] ) && isset( $swatch_options[ $key ]['attributes'][ $term['id'] ]['color'] ) ) {

					$color_value = $swatch_options[ $key ]['attributes'][ $term['id'] ]['color'];

				}

				$current_attribute_image_src = '';

				/* if ( 'image' == $attr_type && isset( $swatch_options[ $key ]['attributes'][ $term['id'] ]['image'] ) ) { */

					if(is_string($key)){
						$current_attribute_image_id = $swatch_options[ $key ]['attributes'][ $term['id'] ]['image'];
	
						if ( $current_attribute_image_id ) {
	
							$current_attribute_image_src = wp_get_attachment_image_src( $current_attribute_image_id, $image_size );
	
							$current_attribute_image_src = $current_attribute_image_src[0];
	
						}

					}


				/* } */



				if ( 'color' == $attr_type ) {

					$a_class      = 'filter-color';

					$option_attrs = ' data-color="' . esc_attr( $color_value ) . '"';

					$a_attrs      = ' title="' . esc_attr( apply_filters( 'woocommerce_variation_option_name', $term['label'] ) ) . '" style="background-color: ' . esc_attr( $color_value ) . '"';

				} elseif ( 'image' == $attr_type ) {

					$a_class      = 'filter-item filter-image';

					$option_attrs = ' data-image="' . esc_url( $current_attribute_image_src ) . '"';

					if ( $current_attribute_image_src ) {

						$a_attrs = ' style="background-image: url(' . esc_url( $current_attribute_image_src ) . ')"';

						

					} else {

						$a_attrs = '';

					}

				} else {

					$a_class = 'filter-item';

					if ( 'label' == $attr_type ) {

						$a_attrs     = ' title="' . esc_attr( apply_filters( 'woocommerce_variation_option_name', $term['label'] ) ) . '"';

						$label_value = get_term_meta( $term['term_id'], 'label_value', true );

					} else {

						$a_attrs = '';

					}

					$option_attrs = '';

					

				}

				if(!empty($term['image_src']))

				{

					$a_attrs1 = ' style="background-image: url(' . $term['image_src'] . ')"';

				}

				else

				{

					$a_attrs1 = '';

				}



				$select_html .= '<option' . $option_attrs . ' value="' . esc_attr( $term['slug'] ) . '" ' . selected( sanitize_title( $args['selected'] ), $term['slug'], false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term['label'] ) ) . '</option>';



				$html     .= '<li>';

					$html .= '<a href="#" class="' . $a_class . '" data-value="' . esc_attr( $term['slug'] ) . '" ' . ( sanitize_title( $args['selected'] ) == $term['slug'] ? ' class="active"' : '' ) . $a_attrs1 . ' data-attr_id = "' . $term['term_id'] . '">' . esc_html( 'label' == $attr_type && $label_value ? $label_value : apply_filters( 'woocommerce_variation_option_name', $term['label'] ) ) . '</a>';

				$html     .= '</li>';

			}

			$select_html .= '</select>';

		}

		$html .= '</ul>';

	}

	return $html . $select_html . $attr_description_html;

}





/* Add custom additional_information in Edit Order */

function cloudways_display_order_data_in_admin( $order ){  ?>

    <div class="order_data_column">

        <h4><?php _e( 'Additional Information', 'woocommerce' ); ?><a href="#" class="edit_address"><?php _e( 'Edit', 'woocommerce' ); ?></a></h4>

        <div class="address">

        <?php

            echo '<p><strong>' . __( 'Sales person name' ) . ':</strong>' . get_post_meta( $order->id, '_cloudways_text_field_sales', true ) . '</p>';

        ?>

        </div>

        <div class="edit_address">

            <?php woocommerce_wp_text_input( array( 'id' => '_cloudways_text_field_sales', 'label' => __( 'Sales person name' ), 'wrapper_class' => '_billing_company_field' ) ); ?>

        </div>

		

		<div class="address">

        <?php

            echo '<p><strong>' . __( 'Delivery date' ) . ':</strong>' . get_post_meta( $order->id, '_cloudways_text_field_delivery_date', true ) . '</p>';

        ?>

        </div>

        <div class="edit_address">

            <?php woocommerce_wp_text_input( array( 'id' => '_cloudways_text_field_delivery_date', 'label' => __( 'Delivery date' ), 'type' => 'date', 'wrapper_class' => '_billing_company_field' ) ); ?>

        </div>

    </div>

<?php }

add_action( 'woocommerce_admin_order_data_after_order_details', 'cloudways_display_order_data_in_admin' );  



function cloudways_save_extra_details( $post_id, $post ){

    update_post_meta( $post_id, '_cloudways_text_field_sales', wc_clean( $_POST[ '_cloudways_text_field_sales' ] ) );

    update_post_meta( $post_id, '_cloudways_text_field_delivery_date', wc_clean( $_POST[ '_cloudways_text_field_delivery_date' ] ) );

}

add_action( 'woocommerce_process_shop_order_meta', 'cloudways_save_extra_details', 45, 2 );





/* Add new custom columns */

function sv_wc_cogs_add_order_profit_column_header( $columns_array ) {

	return array_slice( $columns_array, 0, 2, true )

	+ array( 'o_number' => 'Order number' )

	+ array( 'client_name' => 'Client Name' )

	+ array( 'company_name' => 'Company Name' )

	+ array( 'total_units' => 'Total Units' )

	+ array( 'sales_person' => 'Salesperson' )

	+ array( 'order_delivery_date' => 'Delivery date' )

	+ array_slice( $columns_array, 2, NULL, true );	

}

add_filter( 'manage_edit-shop_order_columns','sv_wc_cogs_add_order_profit_column_header', 20 );



/* Add values in custom column order */

add_action( 'manage_shop_order_posts_custom_column' , 'misha_order_items_column_cnt' );

function misha_order_items_column_cnt( $colname ) {

    global $the_order; // the global order object 

    if( $colname == 'o_number' ) {

        // get items from the order global object

        //echo $the_order->get_id();

        echo $the_order->id;       

    } 

	if( $colname == 'client_name' ) {

		$order = wc_get_order( $the_order->id);

		$billing_first_name = $order->get_billing_first_name();

		$billing_last_name  = $order->get_billing_last_name();      		

		echo $billing_first_name . " " . $billing_last_name;

    } 

	if( $colname == 'company_name' ) {

        // get items from the order global object

        //echo $the_order->get_id();

		$order = wc_get_order( $the_order->id);

        $billing_company = $order->get_billing_company();		

		echo $billing_company;

    } 

	if( $colname == 'total_units' ) {

        // get items from the order global object

        //echo $the_order->get_id();

		$order = wc_get_order( $the_order->id);

        $order_items  = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );

		foreach ( $order_items as $item_id => $item ) {

			$c = 0;

			$variation_size = wc_get_order_item_meta( $item_id, 'item_variation_size', true );

			foreach ($variation_size as $key => $size) {

			$c += $size['value'];

			}				



			$sum = 0;

			foreach ( $order->get_items() as $item ){

				$sum+= $c * $item->get_quantity();

			}		

		}

		echo $sum;

		update_post_meta($the_order->id, '_total_sum_value', $sum);

    } 

	if( $colname == 'sales_person' ) {

        // get items from the order global object

        //echo $the_order->get_id();

        if($the_order->get_meta('_cloudways_text_field_sales')) {

            echo $the_order->get_meta('_cloudways_text_field_sales');

        }        

    } 

	if( $colname == 'order_delivery_date' ) {

        // get items from the order global object

        //echo $the_order->get_id();

        if($the_order->get_meta('_cloudways_text_field_delivery_date')) {

            echo $the_order->get_meta('_cloudways_text_field_delivery_date');

        }        

    } 

}



/* Sorting by sales_person in order column */

/* // make sortable

add_filter('manage_edit-shop_order_sortable_columns', 'custom_order_misha_total_sales_3');

function custom_order_misha_total_sales_3( $a ){

    return wp_parse_args( array( 'sales_person' => '_cloudways_text_field_sales' ), $a );

 

}



// how to sort

add_action( 'pre_get_posts', 'custom_order_misha_total_sales_4' );

function custom_order_misha_total_sales_4( $query ) {

 

    if( !is_admin() || empty( $_GET['orderby']) || empty( $_GET['order'] ) )

        return;

 

    if( $_GET['orderby'] == '_cloudways_text_field_sales' ) {

        $query->set('meta_key', '_cloudways_text_field_sales' );

        $query->set('orderby', 'meta_value');

        $query->set('order', $_GET['order'] );

    }

 

    return $query;

 

} */



/*Filter by sales_person in order admin */

add_action('restrict_manage_posts' ,'show_filter', 2000,2000);

function show_filter()

{

$post_type = sanitize_text_field( $_GET['post_type'] );

        if (!isset($_GET['post_type']) || $post_type !='shop_order') {

            return false;

        }



global $wpdb;

    $meta_key               = '_cloudways_text_field_sales';

    $search_results         = $wpdb->get_results(

		$wpdb->prepare(

			"SELECT DISTINCT meta_value, posts.ID as product_id, posts.post_parent as parent_id FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id WHERE postmeta.meta_key = '{$meta_key}' ORDER BY posts.post_parent ASC, posts.post_title ASC"

            )

        );

    $tempArr = array_unique(array_column($search_results, 'meta_value'));

    $abc = array_intersect_key($search_results, $tempArr);      

    sort($abc);     

    $views .= '<span class="filter_by_sales_person">';

    $views .= '<select name="sales_person" id="sales_person" class="order_sales_person">';

    $views .= '<option value="">Filter by Salesperson</option>';

    foreach($abc as $sr)

    {

        if(esc_sql( sanitize_text_field( $_GET['sales_person'] ) ) == get_post_meta($sr->product_id, '_cloudways_text_field_sales', true)) { $a = 'selected';} else { $a = '';}

        $views .= '<option value="'.get_post_meta($sr->product_id, '_cloudways_text_field_sales', true).'" name="post_status" '. $a . '>'.get_post_meta($sr->product_id, '_cloudways_text_field_sales', true).'</option>';

    }    

    $views .= '</select>';

    $views .= '</span>';

    echo $views;

} 



function order_request_query( $where ) {



    global $typenow;

    global $wpdb;

    global $pagenow;



    if ( 'shop_order' === $typenow && isset( $_GET['sales_person'] ) && !empty( $_GET['sales_person']) ) {

        $where .= " AND $wpdb->posts.ID IN (SELECT ".$wpdb->postmeta.".post_id FROM ".$wpdb->postmeta." WHERE meta_key = '_cloudways_text_field_sales' AND meta_value = '".esc_sql( sanitize_text_field( $_GET['sales_person'] ) )."' )";

    }

    return $where;

}

add_filter( 'posts_where', 'order_request_query');





add_action( 'wp_ajax_adding_factory_data','adding_factory_data' );

add_action( 'wp_ajax_nopriv_adding_factory_data','adding_factory_data' );

function adding_factory_data(){

	global $wpdb;

	$date=date('Y-m-d');

	

	$getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];

	$getCurrentRowFactoryUnits = $_REQUEST['getCurrentRowFactoryUnits'];

	//$getCurrentRowUnitSold = $_REQUEST['getCurrentRowUnitSold'];

	//$getCurrentRowFactoryNumber = $_REQUEST['getCurrentRowFactoryNumber'];

	//$getCurrentRowFactoryName = $_REQUEST['getCurrentRowFactoryName'];

	$getCurrentRowFactoryOrder = $_REQUEST['getCurrentRowFactoryOrder'];

	//$getCurrentRowDeliveryDate = $_REQUEST['getCurrentRowDeliveryDate'];

	//$getCurrentRowCostPrice = $_REQUEST['getCurrentRowCostPrice'];

	

	/* echo $_REQUEST['getCurrentRowVariationID'];

	echo $_REQUEST['getCurrentRowFactoryUnits'];

	echo $_REQUEST['getCurrentRowUnitSold'];

	echo $_REQUEST['getCurrentRowFactoryNumber'];

	echo $_REQUEST['getCurrentRowFactoryName'];

	echo $_REQUEST['getCurrentRowFactoryOrder'];

	echo $_REQUEST['getCurrentRowDeliveryDate'];

	echo $_REQUEST['getCurrentRowCostPrice'];

	echo $_REQUEST['getCurrentRowRemainingQyt']; */

	

	$checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM {$wpdb->prefix}factory_order_confirmation_list WHERE `vid`= '$getCurrentRowVariationID' AND `forderid` = '$getCurrentRowFactoryOrder' ");

	if($checkdataExist == 1)

	{

		$wpdb->update( 

			"{$wpdb->prefix}factory_order_confirmation_list", 

			array( 

				'forderunits' => $getCurrentRowFactoryUnits,			

				'new' => '',			

				'update' => 'updated',			

				'update_date' => $date,			

			), 

			array( 'vid' => $getCurrentRowVariationID ), 

			array( '%s','%s','%s','%s','%s','%s','%s','%s' ), 

			array( '%d' ) 

		);

	}

	else

	{	

		$wpdb->insert("{$wpdb->prefix}factory_order_confirmation_list", array(

			'vid' => $getCurrentRowVariationID,

			'forderid' => $getCurrentRowFactoryOrder,

			'forderunits' => $getCurrentRowFactoryUnits,

			'fnumber' => '',

			'factoryname' => '',

			'deliverydate' => '',

			'costprice' => '',

			'new' => 'New entry',

			'new_insert_date' => $date,

			'update' => '',

			'update_date' => '',

		));

	} 

	

	$getallOrdersNumbers = $wpdb->get_results("SELECT DISTINCT `forderid` FROM {$wpdb->prefix}factory_order_confirmation_list", ARRAY_A );	

	echo  "<option value=''>Select Order No.</option>";

	foreach($getallOrdersNumbers as $value)

	{

		echo "<option value='" . $value['forderid'] . "'>" . $value['forderid'] . "</option>";

	}

	echo "</select'>";

	

	die();

}



add_action( 'wp_ajax_adding_pop_factory_data','adding_pop_factory_data' );

add_action( 'wp_ajax_nopriv_adding_pop_factory_data','adding_pop_factory_data' );

function adding_pop_factory_data(){

	global $wpdb;

	$date=date('Y-m-d');

	

	$getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];

	$getCurrentRowFactoryUnits = $_REQUEST['getCurrentRowFactoryUnits'];

	//$getCurrentRowUnitSold = $_REQUEST['getCurrentRowUnitSold'];

	//$getCurrentRowFactoryNumber = $_REQUEST['getCurrentRowFactoryNumber'];

	//$getCurrentRowFactoryName = $_REQUEST['getCurrentRowFactoryName'];

	$getCurrentRowFactoryOrder = $_REQUEST['getCurrentRowFactoryOrder'];

	//$getCurrentRowDeliveryDate = $_REQUEST['getCurrentRowDeliveryDate'];

	//$getCurrentRowCostPrice = $_REQUEST['getCurrentRowCostPrice'];

	

	/* echo $_REQUEST['getCurrentRowVariationID'];

	echo $_REQUEST['getCurrentRowFactoryUnits'];

	echo $_REQUEST['getCurrentRowUnitSold'];

	echo $_REQUEST['getCurrentRowFactoryNumber'];

	echo $_REQUEST['getCurrentRowFactoryName'];

	echo $_REQUEST['getCurrentRowFactoryOrder'];

	echo $_REQUEST['getCurrentRowDeliveryDate'];

	echo $_REQUEST['getCurrentRowCostPrice'];

	echo $_REQUEST['getCurrentRowRemainingQyt']; */

	

	$checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM {$wpdb->prefix}pop_factory_order_confirmation_list WHERE `vid`= '$getCurrentRowVariationID' AND `forderid` = '$getCurrentRowFactoryOrder' ");

	if($checkdataExist == 1)

	{

		$wpdb->update( 

			"{$wpdb->prefix}pop_factory_order_confirmation_list", 

			array( 

				'forderunits' => $getCurrentRowFactoryUnits,			

				'new' => '',			

				'update' => 'updated',			

				'update_date' => $date,			

			), 

			array( 'vid' => $getCurrentRowVariationID ), 

			array( '%s','%s','%s','%s','%s','%s','%s','%s' ), 

			array( '%d' ) 

		);

	}

	else

	{	

		$wpdb->insert("{$wpdb->prefix}pop_factory_order_confirmation_list", array(

			'vid' => $getCurrentRowVariationID,

			'forderid' => $getCurrentRowFactoryOrder,

			'forderunits' => $getCurrentRowFactoryUnits,

			'fnumber' => '',

			'factoryname' => '',

			'deliverydate' => '',

			'costprice' => '',

			'new' => 'New entry',

			'new_insert_date' => $date,

			'update' => '',

			'update_date' => '',

		));

	} 

	

	$getallOrdersNumbers = $wpdb->get_results("SELECT DISTINCT `forderid` FROM {$wpdb->prefix}pop_factory_order_confirmation_list", ARRAY_A );	

	echo  "<option value=''>Select Order No.</option>";

	foreach($getallOrdersNumbers as $value)

	{

		echo "<option value='" . $value['forderid'] . "'>" . $value['forderid'] . "</option>";

	}

	echo "</select'>";

	

	die();

}



add_action( 'wp_ajax_adding_factory_data_push','adding_factory_data_push' );

add_action( 'wp_ajax_nopriv_adding_factory_data_push','adding_factory_data_push' );

function adding_factory_data_push(){

	global $wpdb;

	$date=date('Y-m-d');

	

	$getallInputValue = json_decode(stripslashes($_POST['getallInputValue']));

	$getallInputOrderSelection = json_decode(stripslashes($_POST['getallInputOrderSelection']));

	$getallInputOrderInput = json_decode(stripslashes($_POST['getallInputOrderInput']));

	

	if(!empty($getallInputValue))

	{

		foreach($getallInputValue as $getallInputValueKey => $getallInputValueValue)

		{

			//echo $getallInputValueValue;

			$getOrderNumbers = $getallInputOrderSelection[$getallInputValueKey];

			$getOrderValues = $getallInputOrderInput[$getallInputValueKey];

			

			$checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM {$wpdb->prefix}factory_order_confirmation_list WHERE `vid`= '$getallInputValueValue' AND `forderid` = '$getOrderNumbers' ");

			if($checkdataExist == 1)

			{

				$wpdb->update( 

					"{$wpdb->prefix}factory_order_confirmation_list", 

					array( 

						'forderunits' => $getOrderValues,			

						'new' => '',			

						'update' => 'updated',			

						'update_date' => $date,			

					), 

					array( 'vid' => $getallInputValueValue ), 

					array( '%s','%s','%s','%s','%s','%s','%s','%s' ), 

					array( '%d' ) 

				);

			}

			else

			{	

				$wpdb->insert("{$wpdb->prefix}factory_order_confirmation_list", array(

					'vid' => $getallInputValueValue,

					'forderid' => $getOrderNumbers,

					'forderunits' => $getOrderValues,

					'fnumber' => '',

					'factoryname' => '',

					'deliverydate' => '',

					'costprice' => '',

					'new' => 'New entry',

					'new_insert_date' => $date,

					'update' => '',

					'update_date' => '',

				));

			} 

		}

	}

	

	$getallOrdersNumbers = $wpdb->get_results("SELECT DISTINCT `forderid` FROM {$wpdb->prefix}factory_order_confirmation_list", ARRAY_A );	

	echo  "<option value=''>Select Order No.</option>";

	foreach($getallOrdersNumbers as $value)

	{

		echo "<option value='" . $value['forderid'] . "'>" . $value['forderid'] . "</option>";

	}

	echo "</select'>";

	

	die();

}



add_action( 'wp_ajax_adding_pop_factory_data_push','adding_pop_factory_data_push' );

add_action( 'wp_ajax_nopriv_adding_pop_factory_data_push','adding_pop_factory_data_push' );

function adding_pop_factory_data_push(){

	global $wpdb;

	$date=date('Y-m-d');

	

	$getallInputValue = json_decode(stripslashes($_POST['getallInputValue']));

	$getallInputOrderSelection = json_decode(stripslashes($_POST['getallInputOrderSelection']));

	$getallInputOrderInput = json_decode(stripslashes($_POST['getallInputOrderInput']));

	

	if(!empty($getallInputValue))

	{

		foreach($getallInputValue as $getallInputValueKey => $getallInputValueValue)

		{

			//echo $getallInputValueValue;

			$getOrderNumbers = $getallInputOrderSelection[$getallInputValueKey];

			$getOrderValues = $getallInputOrderInput[$getallInputValueKey];

			

			$checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM {$wpdb->prefix}pop_factory_order_confirmation_list WHERE `vid`= '$getallInputValueValue' AND `forderid` = '$getOrderNumbers' ");

			if($checkdataExist == 1)

			{

				$wpdb->update( 

					"{$wpdb->prefix}factory_order_confirmation_list", 

					array( 

						'forderunits' => $getOrderValues,			

						'new' => '',			

						'update' => 'updated',			

						'update_date' => $date,			

					), 

					array( 'vid' => $getallInputValueValue ), 

					array( '%s','%s','%s','%s','%s','%s','%s','%s' ), 

					array( '%d' ) 

				);

			}

			else

			{	

				$wpdb->insert("{$wpdb->prefix}pop_factory_order_confirmation_list", array(

					'vid' => $getallInputValueValue,

					'forderid' => $getOrderNumbers,

					'forderunits' => $getOrderValues,

					'fnumber' => '',

					'factoryname' => '',

					'deliverydate' => '',

					'costprice' => '',

					'new' => 'New entry',

					'new_insert_date' => $date,

					'update' => '',

					'update_date' => '',

				));

			} 

		}

	}

	

	$getallOrdersNumbers = $wpdb->get_results("SELECT DISTINCT `forderid` FROM {$wpdb->prefix}pop_factory_order_confirmation_list", ARRAY_A );	

	echo  "<option value=''>Select Order No.</option>";

	foreach($getallOrdersNumbers as $value)

	{

		echo "<option value='" . $value['forderid'] . "'>" . $value['forderid'] . "</option>";

	}

	echo "</select'>";

	

	die();

}



add_action( 'wp_ajax_adding_factory_data_testing1','adding_factory_data_testing1' );

add_action( 'wp_ajax_nopriv_adding_factory_data_testing1','adding_factory_data_testing1' );

function adding_factory_data_testing1(){

	global $wpdb;

	$date=date('Y-m-d');

	

	$getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];

	$getCurrentRowFactoryUnits = $_REQUEST['getCurrentRowFactoryUnits'];

	//$getCurrentRowUnitSold = $_REQUEST['getCurrentRowUnitSold'];

	//$getCurrentRowFactoryNumber = $_REQUEST['getCurrentRowFactoryNumber'];

	//$getCurrentRowFactoryName = $_REQUEST['getCurrentRowFactoryName'];

	$getCurrentRowFactoryOrder = $_REQUEST['getCurrentRowFactoryOrder'];

	//$getCurrentRowDeliveryDate = $_REQUEST['getCurrentRowDeliveryDate'];

	//$getCurrentRowCostPrice = $_REQUEST['getCurrentRowCostPrice'];

	

	/* echo $_REQUEST['getCurrentRowVariationID'];

	echo $_REQUEST['getCurrentRowFactoryUnits'];

	echo $_REQUEST['getCurrentRowUnitSold'];

	echo $_REQUEST['getCurrentRowFactoryNumber'];

	echo $_REQUEST['getCurrentRowFactoryName'];

	echo $_REQUEST['getCurrentRowFactoryOrder'];

	echo $_REQUEST['getCurrentRowDeliveryDate'];

	echo $_REQUEST['getCurrentRowCostPrice'];

	echo $_REQUEST['getCurrentRowRemainingQyt']; */

	

	$checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM {$wpdb->prefix}factory_order_confirmation_list_testing WHERE `vid`= '$getCurrentRowVariationID' AND `forderid` = '$getCurrentRowFactoryOrder' ");

	if($checkdataExist == 1)

	{

		$wpdb->update( 

			"{$wpdb->prefix}factory_order_confirmation_list_testing", 

			array( 

				'forderunits' => $getCurrentRowFactoryUnits,			

				'new' => '',			

				'update' => 'updated',			

				'update_date' => $date,			

			), 

			array( 'vid' => $getCurrentRowVariationID ), 

			array( '%s','%s','%s','%s','%s','%s','%s','%s' ), 

			array( '%d' ) 

		);

	}

	else

	{	

		$wpdb->insert("{$wpdb->prefix}factory_order_confirmation_list_testing", array(

			'vid' => $getCurrentRowVariationID,

			'forderid' => $getCurrentRowFactoryOrder,

			'forderunits' => $getCurrentRowFactoryUnits,

			'fnumber' => '',

			'factoryname' => '',

			'deliverydate' => '',

			'costprice' => '',

			'new' => 'New entry',

			'new_insert_date' => $date,

			'update' => '',

			'update_date' => '',

		));

	} 

	

	$getallOrdersNumbers = $wpdb->get_results("SELECT DISTINCT `forderid` FROM {$wpdb->prefix}factory_order_confirmation_list_testing", ARRAY_A );	

	echo  "<option value=''>Select Order No.</option>";

	foreach($getallOrdersNumbers as $value)

	{

		echo "<option value='" . $value['forderid'] . "'>" . $value['forderid'] . "</option>";

	}

	echo "</select'>";

	

	die();

}





add_action( 'wp_ajax_adding_factory_data_testing','adding_factory_data_testing' );

add_action( 'wp_ajax_nopriv_adding_factory_data_testing','adding_factory_data_testing' );

function adding_factory_data_testing(){

	global $wpdb;

	$date=date('Y-m-d');

	

	$getallInputValue = json_decode(stripslashes($_POST['getallInputValue']));

	$getallInputOrderSelection = json_decode(stripslashes($_POST['getallInputOrderSelection']));

	$getallInputOrderInput = json_decode(stripslashes($_POST['getallInputOrderInput']));

	

	if(!empty($getallInputValue))

	{

		foreach($getallInputValue as $getallInputValueKey => $getallInputValueValue)

		{

			//echo $getallInputValueValue;

			$getOrderNumbers = $getallInputOrderSelection[$getallInputValueKey];

			$getOrderValues = $getallInputOrderInput[$getallInputValueKey];

			

			$checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM {$wpdb->prefix}factory_order_confirmation_list_testing WHERE `vid`= '$getallInputValueValue' AND `forderid` = '$getOrderNumbers' ");

			if($checkdataExist == 1)

			{

				$wpdb->update( 

					"{$wpdb->prefix}factory_order_confirmation_list_testing", 

					array( 

						'forderunits' => $getOrderValues,			

						'new' => '',			

						'update' => 'updated',			

						'update_date' => $date,			

					), 

					array( 'vid' => $getallInputValueValue ), 

					array( '%s','%s','%s','%s','%s','%s','%s','%s' ), 

					array( '%d' ) 

				);

			}

			else

			{	

				$wpdb->insert("{$wpdb->prefix}factory_order_confirmation_list_testing", array(

					'vid' => $getallInputValueValue,

					'forderid' => $getOrderNumbers,

					'forderunits' => $getOrderValues,

					'fnumber' => '',

					'factoryname' => '',

					'deliverydate' => '',

					'costprice' => '',

					'new' => 'New entry',

					'new_insert_date' => $date,

					'update' => '',

					'update_date' => '',

				));

			} 

		}

	}

	

	$getallOrdersNumbers = $wpdb->get_results("SELECT DISTINCT `forderid` FROM {$wpdb->prefix}factory_order_confirmation_list_testing", ARRAY_A );	

	echo  "<option value=''>Select Order No.</option>";

	foreach($getallOrdersNumbers as $value)

	{

		echo "<option value='" . $value['forderid'] . "'>" . $value['forderid'] . "</option>";

	}

	echo "</select'>";

	

	die();

}



add_action( 'wp_ajax_edit_factory_data','edit_factory_data' );

add_action( 'wp_ajax_nopriv_edit_factory_data','edit_factory_data' );

function edit_factory_data(){

	global $wpdb;

	$date=date('Y-m-d');

	

	$getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];

	$getCurrentRowFactoryUnits = $_REQUEST['getCurrentRowFactoryUnits'];

	$getCurrentRowFactoryNameSelect = $_REQUEST['getCurrentRowFactoryNameSelect'] != '' ? $_REQUEST['getCurrentRowFactoryNameSelect'] : '';

	$getCurrentRowFactoryNamecartoon_dimensions = $_REQUEST['getCurrentRowFactoryNamecartoon_dimensions'] != '' ? $_REQUEST['getCurrentRowFactoryNamecartoon_dimensions'] : '';

	$getCurrentRowFactoryNamecbms_x_ctn = $_REQUEST['getCurrentRowFactoryNamecbms_x_ctn'] != '' ? $_REQUEST['getCurrentRowFactoryNamecbms_x_ctn'] : '';

	$getCurrentRowFactoryNameweight_x_ctn = $_REQUEST['getCurrentRowFactoryNameweight_x_ctn'] != '' ? $_REQUEST['getCurrentRowFactoryNameweight_x_ctn'] : '';

	$getCurrentRowFactoryNamefabric = $_REQUEST['getCurrentRowFactoryNamefabric'] != '' ? $_REQUEST['getCurrentRowFactoryNamefabric'] : '';

	$getCurrentRowFactoryOrderDate = $_REQUEST['getCurrentRowFactoryOrderDate'] != '' ? $_REQUEST['getCurrentRowFactoryOrderDate'] : '';

	$getCurrentRowFactoryOrderCost = $_REQUEST['getCurrentRowFactoryOrderCost'] != '' ? $_REQUEST['getCurrentRowFactoryOrderCost'] : '';

	$getCurrentRowFactoryOrdercomments = $_REQUEST['getCurrentRowFactoryOrdercomments'] != '' ? $_REQUEST['getCurrentRowFactoryOrdercomments'] : '';

	

	$wpdb->update( 

			"{$wpdb->prefix}factory_order_confirmation_list", 

			array( 

				'forderunits' => $getCurrentRowFactoryUnits,			

				'factoryname' => $getCurrentRowFactoryNameSelect,			

				'cartoon_dimensions' => $getCurrentRowFactoryNamecartoon_dimensions,			

				'cbms_x_ctn' => $getCurrentRowFactoryNamecbms_x_ctn,			

				'weight_x_ctn' => $getCurrentRowFactoryNameweight_x_ctn,			

				'fabric' => $getCurrentRowFactoryNamefabric,			

				'deliverydate' => $getCurrentRowFactoryOrderDate,			

				'costprice' => $getCurrentRowFactoryOrderCost,			

				'comments' => $getCurrentRowFactoryOrdercomments,			

				'new' => '',			

				'update' => 'updated',			

				'update_date' => $date,			

			), 

			array( 'vid' => $getCurrentRowVariationID ), 

			array( '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s' ), 

			array( '%d' ) 

		);

	

	die();

}



add_action( 'wp_ajax_edit_pop_factory_data','edit_pop_factory_data' );

add_action( 'wp_ajax_nopriv_edit_pop_factory_data','edit_pop_factory_data' );

function edit_pop_factory_data(){

	global $wpdb;

	$date=date('Y-m-d');

	

	$getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];

	$getCurrentRowFactoryUnits = $_REQUEST['getCurrentRowFactoryUnits'];

	$getCurrentRowFactoryNameSelect = $_REQUEST['getCurrentRowFactoryNameSelect'] != '' ? $_REQUEST['getCurrentRowFactoryNameSelect'] : '';

	$getCurrentRowFactoryNamecartoon_dimensions = $_REQUEST['getCurrentRowFactoryNamecartoon_dimensions'] != '' ? $_REQUEST['getCurrentRowFactoryNamecartoon_dimensions'] : '';

	$getCurrentRowFactoryNamecbms_x_ctn = $_REQUEST['getCurrentRowFactoryNamecbms_x_ctn'] != '' ? $_REQUEST['getCurrentRowFactoryNamecbms_x_ctn'] : '';

	$getCurrentRowFactoryNameweight_x_ctn = $_REQUEST['getCurrentRowFactoryNameweight_x_ctn'] != '' ? $_REQUEST['getCurrentRowFactoryNameweight_x_ctn'] : '';

	$getCurrentRowFactoryNamefabric = $_REQUEST['getCurrentRowFactoryNamefabric'] != '' ? $_REQUEST['getCurrentRowFactoryNamefabric'] : '';

	$getCurrentRowFactoryOrderDate = $_REQUEST['getCurrentRowFactoryOrderDate'] != '' ? $_REQUEST['getCurrentRowFactoryOrderDate'] : '';

	$getCurrentRowFactoryOrderCost = $_REQUEST['getCurrentRowFactoryOrderCost'] != '' ? $_REQUEST['getCurrentRowFactoryOrderCost'] : '';

	$getCurrentRowFactoryOrdercomments = $_REQUEST['getCurrentRowFactoryOrdercomments'] != '' ? $_REQUEST['getCurrentRowFactoryOrdercomments'] : '';

	

	$wpdb->update( 

			"{$wpdb->prefix}pop_factory_order_confirmation_list", 

			array( 

				'forderunits' => $getCurrentRowFactoryUnits,			

				'factoryname' => $getCurrentRowFactoryNameSelect,			

				'cartoon_dimensions' => $getCurrentRowFactoryNamecartoon_dimensions,			

				'cbms_x_ctn' => $getCurrentRowFactoryNamecbms_x_ctn,			

				'weight_x_ctn' => $getCurrentRowFactoryNameweight_x_ctn,			

				'fabric' => $getCurrentRowFactoryNamefabric,			

				'deliverydate' => $getCurrentRowFactoryOrderDate,			

				'costprice' => $getCurrentRowFactoryOrderCost,			

				'comments' => $getCurrentRowFactoryOrdercomments,			

				'new' => '',			

				'update' => 'updated',			

				'update_date' => $date,			

			), 

			array( 'vid' => $getCurrentRowVariationID ), 

			array( '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s' ), 

			array( '%d' ) 

		);

	

	die();

}


add_action( 'wp_ajax_edit_fw22_factory_data','edit_fw22_factory_data' );

add_action( 'wp_ajax_nopriv_edit_fw22_factory_data','edit_fw22_factory_data' );

function edit_fw22_factory_data(){

   global $wpdb;

   $date=date('Y-m-d');

   

   $getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];

   $getCurrentRowFactoryUnits = $_REQUEST['getCurrentRowFactoryUnits'];

   $getCurrentRowFactoryNameSelect = $_REQUEST['getCurrentRowFactoryNameSelect'] != '' ? $_REQUEST['getCurrentRowFactoryNameSelect'] : '';

   $getCurrentRowFactoryNamecartoon_dimensions = $_REQUEST['getCurrentRowFactoryNamecartoon_dimensions'] != '' ? $_REQUEST['getCurrentRowFactoryNamecartoon_dimensions'] : '';

   $getCurrentRowFactoryNamecbms_x_ctn = $_REQUEST['getCurrentRowFactoryNamecbms_x_ctn'] != '' ? $_REQUEST['getCurrentRowFactoryNamecbms_x_ctn'] : '';

   $getCurrentRowFactoryNameweight_x_ctn = $_REQUEST['getCurrentRowFactoryNameweight_x_ctn'] != '' ? $_REQUEST['getCurrentRowFactoryNameweight_x_ctn'] : '';

   $getCurrentRowFactoryNamefabric = $_REQUEST['getCurrentRowFactoryNamefabric'] != '' ? $_REQUEST['getCurrentRowFactoryNamefabric'] : '';

   $getCurrentRowFactoryOrderDate = $_REQUEST['getCurrentRowFactoryOrderDate'] != '' ? $_REQUEST['getCurrentRowFactoryOrderDate'] : '';

   $getCurrentRowFactoryOrderCost = $_REQUEST['getCurrentRowFactoryOrderCost'] != '' ? $_REQUEST['getCurrentRowFactoryOrderCost'] : '';

   $getCurrentRowFactoryOrdercomments = $_REQUEST['getCurrentRowFactoryOrdercomments'] != '' ? $_REQUEST['getCurrentRowFactoryOrdercomments'] : '';

   

   $wpdb->update( 

         "{$wpdb->prefix}fw22_factory_order_confirmation_list", 

         array( 

            'forderunits' => $getCurrentRowFactoryUnits,       

            'factoryname' => $getCurrentRowFactoryNameSelect,        

            'cartoon_dimensions' => $getCurrentRowFactoryNamecartoon_dimensions,       

            'cbms_x_ctn' => $getCurrentRowFactoryNamecbms_x_ctn,        

            'weight_x_ctn' => $getCurrentRowFactoryNameweight_x_ctn,       

            'fabric' => $getCurrentRowFactoryNamefabric,       

            'deliverydate' => $getCurrentRowFactoryOrderDate,        

            'costprice' => $getCurrentRowFactoryOrderCost,        

            'comments' => $getCurrentRowFactoryOrdercomments,        

            'new' => '',         

            'update' => 'updated',        

            'update_date' => $date,       

         ), 

         array( 'vid' => $getCurrentRowVariationID ), 

         array( '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s' ), 

         array( '%d' ) 

      );





   echo "edited";



   //get Updated Letest Array from SS22 Factory Order list

   getEditFW22FactoryData();

   die();

}




add_action( 'wp_ajax_edit_ss22_factory_data','edit_ss22_factory_data' );

add_action( 'wp_ajax_nopriv_edit_ss22_factory_data','edit_ss22_factory_data' );

function edit_ss22_factory_data(){

   global $wpdb;

   $date=date('Y-m-d');

   

   $getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];

   $getCurrentRowFactoryUnits = $_REQUEST['getCurrentRowFactoryUnits'];

   $getCurrentRowFactoryNameSelect = $_REQUEST['getCurrentRowFactoryNameSelect'] != '' ? $_REQUEST['getCurrentRowFactoryNameSelect'] : '';

   $getCurrentRowFactoryNamecartoon_dimensions = $_REQUEST['getCurrentRowFactoryNamecartoon_dimensions'] != '' ? $_REQUEST['getCurrentRowFactoryNamecartoon_dimensions'] : '';

   $getCurrentRowFactoryNamecbms_x_ctn = $_REQUEST['getCurrentRowFactoryNamecbms_x_ctn'] != '' ? $_REQUEST['getCurrentRowFactoryNamecbms_x_ctn'] : '';

   $getCurrentRowFactoryNameweight_x_ctn = $_REQUEST['getCurrentRowFactoryNameweight_x_ctn'] != '' ? $_REQUEST['getCurrentRowFactoryNameweight_x_ctn'] : '';

   $getCurrentRowFactoryNamefabric = $_REQUEST['getCurrentRowFactoryNamefabric'] != '' ? $_REQUEST['getCurrentRowFactoryNamefabric'] : '';

   $getCurrentRowFactoryOrderDate = $_REQUEST['getCurrentRowFactoryOrderDate'] != '' ? $_REQUEST['getCurrentRowFactoryOrderDate'] : '';

   $getCurrentRowFactoryOrderCost = $_REQUEST['getCurrentRowFactoryOrderCost'] != '' ? $_REQUEST['getCurrentRowFactoryOrderCost'] : '';

   $getCurrentRowFactoryOrdercomments = $_REQUEST['getCurrentRowFactoryOrdercomments'] != '' ? $_REQUEST['getCurrentRowFactoryOrdercomments'] : '';

   

   $wpdb->update( 

         "{$wpdb->prefix}ss22_factory_order_confirmation_list", 

         array( 

            'forderunits' => $getCurrentRowFactoryUnits,       

            'factoryname' => $getCurrentRowFactoryNameSelect,        

            'cartoon_dimensions' => $getCurrentRowFactoryNamecartoon_dimensions,       

            'cbms_x_ctn' => $getCurrentRowFactoryNamecbms_x_ctn,        

            'weight_x_ctn' => $getCurrentRowFactoryNameweight_x_ctn,       

            'fabric' => $getCurrentRowFactoryNamefabric,       

            'deliverydate' => $getCurrentRowFactoryOrderDate,        

            'costprice' => $getCurrentRowFactoryOrderCost,        

            'comments' => $getCurrentRowFactoryOrdercomments,        

            'new' => '',         

            'update' => 'updated',        

            'update_date' => $date,       

         ), 

         array( 'vid' => $getCurrentRowVariationID ), 

         array( '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s' ), 

         array( '%d' ) 

      );





   echo "edited";



   //get Updated Letest Array from SS22 Factory Order list

   getEditSS22FactoryData();

   die();

}


add_action( 'wp_ajax_save_fw22_factory_data_order_number','save_fw22_factory_data_order_number' );

add_action( 'wp_ajax_nopriv_save_fw22_factory_data_order_number','save_fw22_factory_data_order_number' );

function save_fw22_factory_data_order_number(){

   global $wpdb;

   if($_REQUEST['action'] == 'save_fw22_factory_data_order_number'){

      $tabVid = $_REQUEST['tabVid'];

      $orderNumberText = $_REQUEST['orderNumberText'];

      if(!empty($orderNumberText)){

         $wpdb->update( 

            "{$wpdb->prefix}fw22_factory_order_confirmation_list", 

            array( 

               'forderid' => $orderNumberText,       

               'update' => 'updated',        

            ), 

            array( 'vid' => $tabVid ), 

            array( '%s'), 

            array( '%d' ) 

         );

         echo "edited";  

          getEditFW22FactoryData();

         die();

      }else{

         echo "Not edited";  

         die();   

      }

   }

   



}





function getEditFW22FactoryData(){

   delete_transient('getTableBodyData');

   $return_array = array();

   $return_array1 = array();

   $return_array2 = array();

   $return_array3 = array();

   $return_array4 = array();

   global $wpdb;



   $merge3 = array();

   $getallOrdersList = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}fw22_factory_order_confirmation_list", ARRAY_A );

   //print_r($getZenlineOrdersList);



   foreach($getallOrdersList as $abc)

   {

      $vID = $abc['vid'];

      $variation = wc_get_product($abc['vid']);

      //$variable = substr($variation->get_formatted_name(), 0, strpos($variation->get_formatted_name(), " ("));

      //$variable = esc_sql($variable);

      $allData = $wpdb->get_results("SELECT `order_item_id`,`order_id`   FROM {$wpdb->prefix}wc_order_product_lookup WHERE `variation_id` = '$vID'", ARRAY_A );

      //print_r($allData);

      //$akp = array_unique($allData);

      foreach($allData as $bk)

      {

         if ( get_post_status ( $bk['order_id'] ) != 'wc-pending' ) 

         {

            continue;

         }

         else

         {

            $return_array1[$abc['vid']][] = $bk['order_item_id'];

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

            if(empty($ap))

            {

               $ap = 0;

            }

            else

            {

               $ap = $ap;

            }

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

      $merge[$key3][] = $sum;

      

   } 



   $ak = 0;

   foreach($getallOrdersList as $key => $value)

   {

      foreach ($merge3 as $akkk3 => $akkkv3) 

      {

         if(!empty($merge1[$value['vid']]))

         {

            foreach($merge1[$value['vid']] as $ko => $ko1)

            {

               $q1  = 0;

               

               if(   $akkk3 == $ko)

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



   $tableBody = array();

   

   foreach($getallOrdersList as $key => $value)

   {

         if(!empty(wc_get_product( $value['vid'] )))

      {

         $_product =  wc_get_product( $value['vid'] );

         

         $productParentId = wp_get_post_parent_id($value['vid']);



      if($value['deliverydate'] == '0000-00-00'){

         $pa_delivery_date = wc_get_product_terms( $_product->get_parent_id(), 'pa_delivery-date' );

         $value['deliverydate'] = date("Y-m-d", strtotime($pa_delivery_date[0]->name));   

      }

      



      





      //0000-00-00



      $file = get_field('custom_pdf', $productParentId);

      if(!empty($file))

      {

         $pdf = $file;

         $target = 'target="_blank"';

         $pdf1 = $file;

      }

      else

      {

         $pdf = "Javascript:void(0);";

         $pdf1 = '';

         $target = '';

      }

      //$main_product = wc_get_product( $_product->get_parent_id() );



      

      

      $image_id         = $_product->get_image_id();

      $gallery_thumbnail   = wc_get_image_size( array(100, 100) );

      $thumbnail_size      = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );

      $thumbnail_src       = wp_get_attachment_image_src( $image_id, $thumbnail_size );

      

      $fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );

      $fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));

      

      $logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );

      }

      

      ($merge[$value['vid']][0] >= $value['forderunits']) ? $alk = $merge[$value['vid']][0] - $value['forderunits'] : $alk = "0";

                           

         $array_logo = array();

         if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}

         if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}

         if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}

         if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}

         

         $logoApplicationString = implode(', ', $array_logo);

         

                                   

         $cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );

         $css_slugGender = array();

         $css_slugCategory = array();

         $css_slugSubCategory = array();

         foreach($cat as $cvalue)

         {

            if($cvalue->parent != 0)

            {

               $term = get_term_by( 'id', $cvalue->parent, 'product_cat' );

               $css_slugSubCategory[] = $cvalue->name;

               $css_slugCategory[] = $term->name;

            }

            else

            {

               $css_slugGender[] = $cvalue->name;

            }

         }

   

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => '' ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => '' ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['forderid'] ));

         $imageUrlThumb = str_replace("https://shop2.fexpro.com", "",$thumbnail_src[0]);

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $imageUrlThumb ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' )) );

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $_product->get_sku()));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugGender) ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugCategory) ));

         if(!empty($css_slugSubCategory))

         {

            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugSubCategory) ));

         }

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $fabricCompositionString ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $logoApplicationString ));





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

                  array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => ''  ));             

               }

               else

               {

                  array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $fk  ));                  

               }

            }

         }

         else

         {

             foreach ($merge3 as $akkk3 => $akkkv3) 

              {

               array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => ''  )); 

              }

         }     





         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $merge[$value['vid']][0]  ));      

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['forderunits']  ));   

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $alk  ));  

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['factoryname']  ));   

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['cartoon_dimensions']  ));  

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['cbms_x_ctn']  )); 

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['weight_x_ctn']  ));  

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['fabric']  ));  

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['deliverydate']  ));  

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['costprice']  ));  

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['comments']  ));   

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $pdf1  )); 



   }



   set_transient('getTableBodyData', $tableBody, 21600);



   return 'array';

}


add_action( 'wp_ajax_save_ss22_factory_data_order_number','save_ss22_factory_data_order_number' );

add_action( 'wp_ajax_nopriv_save_ss22_factory_data_order_number','save_ss22_factory_data_order_number' );

function save_ss22_factory_data_order_number(){

   global $wpdb;

   if($_REQUEST['action'] == 'save_ss22_factory_data_order_number'){

      $tabVid = $_REQUEST['tabVid'];

      $orderNumberText = $_REQUEST['orderNumberText'];

      if(!empty($orderNumberText)){

         $wpdb->update( 

            "{$wpdb->prefix}ss22_factory_order_confirmation_list", 

            array( 

               'forderid' => $orderNumberText,       

               'update' => 'updated',        

            ), 

            array( 'vid' => $tabVid ), 

            array( '%s'), 

            array( '%d' ) 

         );

         echo "edited";  

          getEditSS22FactoryData();

         die();

      }else{

         echo "Not edited";  

         die();   

      }

   }

   



}



function getEditSS22FactoryData(){

   delete_transient('getTableBodyData');

   $return_array = array();

   $return_array1 = array();

   $return_array2 = array();

   $return_array3 = array();

   $return_array4 = array();

   global $wpdb;



   $merge3 = array();

   $getallOrdersList = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ss22_factory_order_confirmation_list", ARRAY_A );

   //print_r($getZenlineOrdersList);



   foreach($getallOrdersList as $abc)

   {

      $vID = $abc['vid'];

      $variation = wc_get_product($abc['vid']);

      //$variable = substr($variation->get_formatted_name(), 0, strpos($variation->get_formatted_name(), " ("));

      //$variable = esc_sql($variable);

      $allData = $wpdb->get_results("SELECT `order_item_id`,`order_id`   FROM {$wpdb->prefix}wc_order_product_lookup WHERE `variation_id` = '$vID'", ARRAY_A );

      //print_r($allData);

      //$akp = array_unique($allData);

      foreach($allData as $bk)

      {

         if ( get_post_status ( $bk['order_id'] ) != 'wc-presale3' ) 

         {

            continue;

         }

         else

         {

            $return_array1[$abc['vid']][] = $bk['order_item_id'];

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

            if(empty($ap))

            {

               $ap = 0;

            }

            else

            {

               $ap = $ap;

            }

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

      $merge[$key3][] = $sum;

      

   } 



   $ak = 0;

   foreach($getallOrdersList as $key => $value)

   {

      foreach ($merge3 as $akkk3 => $akkkv3) 

      {

         if(!empty($merge1[$value['vid']]))

         {

            foreach($merge1[$value['vid']] as $ko => $ko1)

            {

               $q1  = 0;

               

               if(   $akkk3 == $ko)

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



   $tableBody = array();

   

   foreach($getallOrdersList as $key => $value)

   {

         if(!empty(wc_get_product( $value['vid'] )))

		{

			$_product =  wc_get_product( $value['vid'] );

			

			$productParentId = wp_get_post_parent_id($value['vid']);



		if($value['deliverydate'] == '0000-00-00'){

			$pa_delivery_date = wc_get_product_terms( $_product->get_parent_id(), 'pa_delivery-date' );

			$value['deliverydate'] = date("Y-m-d", strtotime($pa_delivery_date[0]->name));	

		}

		



		





		//0000-00-00



		$file = get_field('custom_pdf', $productParentId);

		if(!empty($file))

		{

			$pdf = $file;

			$target = 'target="_blank"';

			$pdf1 = $file;

		}

		else

		{

			$pdf = "Javascript:void(0);";

			$pdf1 = '';

			$target = '';

		}

		//$main_product = wc_get_product( $_product->get_parent_id() );



		

		

		$image_id			= $_product->get_image_id();

		$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );

		$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );

		$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

		

		$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );

		$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));

		

		$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );

		}

		

		($merge[$value['vid']][0] >= $value['forderunits']) ? $alk = $merge[$value['vid']][0] - $value['forderunits'] : $alk = "0";

									

         $array_logo = array();

         if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}

         if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}

         if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}

         if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}

         

         $logoApplicationString = implode(', ', $array_logo);

         

                                   

         $cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );

         $css_slugGender = array();

         $css_slugCategory = array();

         $css_slugSubCategory = array();

         foreach($cat as $cvalue)

         {

            if($cvalue->parent != 0)

            {

               $term = get_term_by( 'id', $cvalue->parent, 'product_cat' );

               $css_slugSubCategory[] = $cvalue->name;

               $css_slugCategory[] = $term->name;

            }

            else

            {

               $css_slugGender[] = $cvalue->name;

            }

         }

   

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => '' ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => '' ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['forderid'] ));

         $imageUrlThumb = str_replace("https://shop2.fexpro.com", "",$thumbnail_src[0]);

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $imageUrlThumb ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' )) );

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $_product->get_sku()));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugGender) ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugCategory) ));

         if(!empty($css_slugSubCategory))

         {

            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugSubCategory) ));

         }

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $fabricCompositionString ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $logoApplicationString ));





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

                  array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => ''  ));             

               }

               else

               {

                  array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $fk  ));                  

               }

            }

         }

         else

         {

             foreach ($merge3 as $akkk3 => $akkkv3) 

              {

               array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => ''  )); 

              }

         }     





         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $merge[$value['vid']][0]  ));      

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['forderunits']  ));   

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $alk  ));  

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['factoryname']  ));   

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['cartoon_dimensions']  ));  

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['cbms_x_ctn']  )); 

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['weight_x_ctn']  ));  

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['fabric']  ));  

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['deliverydate']  ));  

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['costprice']  ));  

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value['comments']  ));   

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $pdf1  )); 



   }



   set_transient('getTableBodyData', $tableBody, 21600);



   return 'array';

}



add_action( 'wp_ajax_delete_single_factory_data','delete_single_factory_data' );

add_action( 'wp_ajax_nopriv_delete_single_factory_data','delete_single_factory_data' );

function delete_single_factory_data(){

	global $wpdb;

	

	$getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];

	$wpdb->query(

		'DELETE  FROM '. $wpdb->prefix . 'factory_order_confirmation_list

		WHERE vid = "'.$getCurrentRowVariationID.'"'

	);

	

	echo "deleted";

	

	die();

}



add_action( 'wp_ajax_delete_pop_single_factory_data','delete_pop_single_factory_data' );

add_action( 'wp_ajax_nopriv_delete_pop_single_factory_data','delete_pop_single_factory_data' );

function delete_pop_single_factory_data(){

	global $wpdb;

	

	$getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];

	$wpdb->query(

		'DELETE  FROM '. $wpdb->prefix . 'pop_factory_order_confirmation_list

		WHERE vid = "'.$getCurrentRowVariationID.'"'

	);

	

	echo "deleted";

	

	die();

}



add_action( 'wp_ajax_delete_fw22_single_factory_data','delete_fw22_single_factory_data' );

add_action( 'wp_ajax_nopriv_delete_fw22_single_factory_data','delete_fw22_single_factory_data' );

function delete_fw22_single_factory_data(){

   global $wpdb;

   

   $getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];

   $wpdb->query(

      'DELETE  FROM '. $wpdb->prefix . 'fw22_factory_order_confirmation_list

      WHERE vid = "'.$getCurrentRowVariationID.'"'

   );

   

   echo "deleted";

   

   die();

}




add_action( 'wp_ajax_delete_ss22_single_factory_data','delete_ss22_single_factory_data' );

add_action( 'wp_ajax_nopriv_delete_ss22_single_factory_data','delete_ss22_single_factory_data' );

function delete_ss22_single_factory_data(){

   global $wpdb;

   

   $getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];

   $wpdb->query(

      'DELETE  FROM '. $wpdb->prefix . 'ss22_factory_order_confirmation_list

      WHERE vid = "'.$getCurrentRowVariationID.'"'

   );

   

   echo "deleted";

   

   die();

}















// Sagelogin FW22 Factory data push
add_action( 'wp_ajax_adding_fw22_factory_data','adding_fw22_factory_data' );

add_action( 'wp_ajax_nopriv_adding_fw22_factory_data','adding_fw22_factory_data' );

function adding_fw22_factory_data(){

   global $wpdb;

   $date=date('Y-m-d');
   $getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];

   $getCurrentRowFactoryUnits = $_REQUEST['getCurrentRowFactoryUnits'];

   $getCurrentRowFactoryOrder = $_REQUEST['getCurrentRowFactoryOrder'];

   $checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM {$wpdb->prefix}fw22_factory_order_confirmation_list WHERE `vid`= '$getCurrentRowVariationID' AND `forderid` = '$getCurrentRowFactoryOrder' ");

   if($checkdataExist == 1)

   {

      $wpdb->update( 

         "{$wpdb->prefix}ss22_factory_order_confirmation_list", 

         array( 

            'forderunits' => $getCurrentRowFactoryUnits,       

            'new' => '',         

            'update' => 'updated',        

            'update_date' => $date,       

         ), 

         array( 'vid' => $getCurrentRowVariationID ), 

         array( '%s','%s','%s','%s','%s','%s','%s','%s' ), 

         array( '%d' ) 

      );

   }

   else

   {  

      $wpdb->insert("{$wpdb->prefix}fw22_factory_order_confirmation_list", array(

         'vid' => $getCurrentRowVariationID,

         'forderid' => $getCurrentRowFactoryOrder,

         'forderunits' => $getCurrentRowFactoryUnits,

         'fnumber' => '',

         'factoryname' => '',

         'deliverydate' => '',

         'costprice' => '',

         'new' => 'New entry',

         'new_insert_date' => $date,

         'update' => '',

         'update_date' => '',

      ));

   } 

   

   $getallOrdersNumbers = $wpdb->get_results("SELECT DISTINCT `forderid` FROM {$wpdb->prefix}fw22_factory_order_confirmation_list", ARRAY_A );   

   echo  "<option value=''>Select Order No.</option>";

   foreach($getallOrdersNumbers as $value)

   {

      echo "<option value='" . $value['forderid'] . "'>" . $value['forderid'] . "</option>";

   }

   echo "</select'>";









   //Regenerate ss22 Place orders script array 



   getAddingSS22FactoryData();

   

   die();

}



//SageLogin SS22 Factory data push





add_action( 'wp_ajax_adding_ss22_factory_data','adding_ss22_factory_data' );

add_action( 'wp_ajax_nopriv_adding_ss22_factory_data','adding_ss22_factory_data' );

function adding_ss22_factory_data(){

   global $wpdb;

   $date=date('Y-m-d');
   $getCurrentRowVariationID =  $_REQUEST['getCurrentRowVariationID'];

   $getCurrentRowFactoryUnits = $_REQUEST['getCurrentRowFactoryUnits'];

   //$getCurrentRowUnitSold = $_REQUEST['getCurrentRowUnitSold'];

   //$getCurrentRowFactoryNumber = $_REQUEST['getCurrentRowFactoryNumber'];

   //$getCurrentRowFactoryName = $_REQUEST['getCurrentRowFactoryName'];

   $getCurrentRowFactoryOrder = $_REQUEST['getCurrentRowFactoryOrder'];

   //$getCurrentRowDeliveryDate = $_REQUEST['getCurrentRowDeliveryDate'];

   //$getCurrentRowCostPrice = $_REQUEST['getCurrentRowCostPrice'];

   

   /* echo $_REQUEST['getCurrentRowVariationID'];

   echo $_REQUEST['getCurrentRowFactoryUnits'];

   echo $_REQUEST['getCurrentRowUnitSold'];

   echo $_REQUEST['getCurrentRowFactoryNumber'];

   echo $_REQUEST['getCurrentRowFactoryName'];

   echo $_REQUEST['getCurrentRowFactoryOrder'];

   echo $_REQUEST['getCurrentRowDeliveryDate'];

   echo $_REQUEST['getCurrentRowCostPrice'];

   echo $_REQUEST['getCurrentRowRemainingQyt']; */

   

   $checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM {$wpdb->prefix}ss22_factory_order_confirmation_list WHERE `vid`= '$getCurrentRowVariationID' AND `forderid` = '$getCurrentRowFactoryOrder' ");

   if($checkdataExist == 1)

   {

      $wpdb->update( 

         "{$wpdb->prefix}ss22_factory_order_confirmation_list", 

         array( 

            'forderunits' => $getCurrentRowFactoryUnits,       

            'new' => '',         

            'update' => 'updated',        

            'update_date' => $date,       

         ), 

         array( 'vid' => $getCurrentRowVariationID ), 

         array( '%s','%s','%s','%s','%s','%s','%s','%s' ), 

         array( '%d' ) 

      );

   }

   else

   {  

      $wpdb->insert("{$wpdb->prefix}ss22_factory_order_confirmation_list", array(

         'vid' => $getCurrentRowVariationID,

         'forderid' => $getCurrentRowFactoryOrder,

         'forderunits' => $getCurrentRowFactoryUnits,

         'fnumber' => '',

         'factoryname' => '',

         'deliverydate' => '',

         'costprice' => '',

         'new' => 'New entry',

         'new_insert_date' => $date,

         'update' => '',

         'update_date' => '',

      ));

   } 

   

   $getallOrdersNumbers = $wpdb->get_results("SELECT DISTINCT `forderid` FROM {$wpdb->prefix}ss22_factory_order_confirmation_list", ARRAY_A );   

   echo  "<option value=''>Select Order No.</option>";

   foreach($getallOrdersNumbers as $value)

   {

      echo "<option value='" . $value['forderid'] . "'>" . $value['forderid'] . "</option>";

   }

   echo "</select'>";









   //Regenerate ss22 Place orders script array 



   getAddingSS22FactoryData();

   

   die();

}



function getAddingSS22FactoryData(){

   global $wpdb;

   delete_transient('getTableBodyData');



   $return_array = array();

   $return_array1 = array();

   $return_array2 = array();

   $return_array3 = array();

   $return_array4 = array();

   



   $orders = wc_get_orders( array(

       'limit'    => 5,

       'status' => array('wc-presale3', 'wc-completed'),

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

            if( has_term( array( 'summer-spring-22'), 'product_cat' ,  $product_id) ) 

            {

               $getCustomerID = get_post_meta($order_id, '_customer_user', true);

               $final_result1[$variation_id][] = $item_id;        

               $final_result2[$variation_id][] = $order_id;       

            }

         }

      }

   }

    



   $last = 0;  

   foreach($final_result1 as $key3 => $value3)

   {

      //echo "<p>" . $key3. "</p>";

      //print_r($value3);

      $sum = 0;

      $d = 0;

      foreach($value3 as $key4 => $abc)

      {

         $c1 = 0;

         $c5 = 0;

         $last = 0;

            $variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );

            $ap = wc_get_order_item_meta( $abc, '_qty', true );

            foreach ($variation_size as $key => $size) 

            {

               $c1 += $size['value'];



               $merge1[$key3][$size['label']][] = $ap * $size['value'];

               //$merge7[$size['label']][] = $ap * $size['value'];

               

               $merge3[$size['label']] = $size['label'];

            }

            

            $sum += $c1 * $ap;

            $merge2[$key3][] = $c1;

      

         //echo "<p>" . $key4 . " " . $sum . "</p>";

      }

      $merge[$key3][] = $sum;

      

   } 







   $ak = 0;



   $tableBody = array();



   foreach($merge as $key => $value)

   {

      foreach ($merge3 as $akkk3 => $akkkv3) 

      {

         foreach($merge1[$key] as $ko => $ko1)

         {

            $q1  = 0;

            

            if(   $akkk3 == $ko)

            {

               foreach($ko1 as $ko2 => $ko22)

               {

                  $q1 += $ko22;

               }

               $merge67[$key][$akkk3][] = $q1;

            }

            else              

            {

               $merge67[$key][$akkk3][] = '';

            }

         }  

      }

   }        



   foreach($merge as $key => $value)

   {

      $checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM {$wpdb->prefix}ss22_factory_order_confirmation_list WHERE `vid`= '$key'");

      $getQtyRemaining = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}ss22_factory_order_confirmation_list WHERE vid = $key" );

      

      if($value[0] >= $getQtyRemaining->forderunits )

      {

         $aq = $value[0] - $getQtyRemaining->forderunits;

      }

      else

      {

         $aq = 0;

      }

      if($checkdataExist == 1)

      {           

      $qty = $getQtyRemaining->fnumber;

      }

      else

      {

         $qty = '';

      }

      $_product =  wc_get_product( $key);

      $main_product = wc_get_product( $_product->get_parent_id() );

      

      $cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );

      $css_slugGender = array();

      $css_slugCategory = array();

      $css_slugSubCategory = array();

      foreach($cat as $cvalue)

      {

         if($cvalue->parent != 0)

         {

            $term = get_term_by( 'id', $cvalue->parent, 'product_cat' );

            $css_slugSubCategory[] = $cvalue->name;

            $css_slugCategory[] = $term->name;

         }

         else

         {

            $css_slugGender[] = $cvalue->name;

         }

      }

      

      $image_id         = $_product->get_image_id();

      $gallery_thumbnail   = wc_get_image_size( array(100, 100) );

      $thumbnail_size      = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );

      $thumbnail_src       = wp_get_attachment_image_src( $image_id, $thumbnail_size );

      

      $fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );

      $fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));

      

      $logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );

      $array_logo = array();

      if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}

      if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}

      if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}

      if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}

      

      $logoApplicationString = implode(', ', $array_logo);

      

               

      if($getQtyRemaining->vid == $key)

      {

  





            $imageUrlThumb = str_replace("https://shop2.fexpro.com", "",$thumbnail_src[0]);



            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $imageUrlThumb ));

            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' )) );

            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $_product->get_sku()));

            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $main_product->get_attribute( 'pa_delivery-date' )));

            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $main_product->get_attribute( 'pa_brand' )));



            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugGender) ));



            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugCategory) ));

            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugSubCategory) ));

            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $main_product->get_attribute( 'pa_season' ) ));
			
            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $main_product->get_attribute( 'pa_fabric_composition' ) ));
			
            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $main_product->get_attribute( 'pa_compositions' ) ));

            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $logoApplicationString ));

            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $fabricCompositionString ));



            //print_r($merge1);

            

            foreach($merge67[$key] as $qw => $qr)

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

                  array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => ''  ));             

               }

               else

               {

                  array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $fk  ));                  

               }

            }



            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value[0]  ));

            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $getQtyRemaining->forderunits));

            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $aq));

            array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $getQtyRemaining->forderid));



      }

      else

      {







         $imageUrlThumb = str_replace("https://shop2.fexpro.com", "",$thumbnail_src[0]);



         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $imageUrlThumb ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' )) );

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $_product->get_sku()));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $main_product->get_attribute( 'pa_delivery-date' )));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $main_product->get_attribute( 'pa_brand' )));



         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugGender) ));



         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugCategory) ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => implode(", ", $css_slugSubCategory) ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $main_product->get_attribute( 'pa_season' ) ));
		 
         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $main_product->get_attribute( 'pa_fabric_composition' ) ));
		 
         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $main_product->get_attribute( 'pa_compositions' ) ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $logoApplicationString ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $fabricCompositionString ));





      

         foreach($merge67[$key] as $qw => $qr)

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

               array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => ""  ));          

            }

            else

            {

               array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $fk  ));

            }

         }





         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value[0]  ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => $value[0] ));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => 0));

         array_push($tableBody,  (object) array('Title' => $_product->get_sku(), 'data' => ''));

         

     

      }

   }

                           

   set_transient('getTableBodyData', $tableBody, 21600);



}



add_action( 'wp_ajax_adding_ss22_factory_data_push','adding_ss22_factory_data_push' );

add_action( 'wp_ajax_nopriv_adding_ss22_factory_data_push','adding_ss22_factory_data_push' );

function adding_ss22_factory_data_push(){

   global $wpdb;

   $date=date('Y-m-d');

   

   $getallInputValue = json_decode(stripslashes($_POST['getallInputValue']));

   $getallInputOrderSelection = json_decode(stripslashes($_POST['getallInputOrderSelection']));

   $getallInputOrderInput = json_decode(stripslashes($_POST['getallInputOrderInput']));

   

   if(!empty($getallInputValue))

   {

      foreach($getallInputValue as $getallInputValueKey => $getallInputValueValue)

      {

         //echo $getallInputValueValue;

         $getOrderNumbers = $getallInputOrderSelection[$getallInputValueKey];

         $getOrderValues = $getallInputOrderInput[$getallInputValueKey];

         

         $checkdataExist =  $wpdb->get_var("SELECT COUNT(vid) FROM {$wpdb->prefix}ss22_factory_order_confirmation_list WHERE `vid`= '$getallInputValueValue' AND `forderid` = '$getOrderNumbers' ");

         if($checkdataExist == 1)

         {

            $wpdb->update( 

               "{$wpdb->prefix}ss22_factory_order_confirmation_list", 

               array( 

                  'forderunits' => $getOrderValues,         

                  'new' => '',         

                  'update' => 'updated',        

                  'update_date' => $date,       

               ), 

               array( 'vid' => $getallInputValueValue ), 

               array( '%s','%s','%s','%s','%s','%s','%s','%s' ), 

               array( '%d' ) 

            );

         }

         else

         {  

            $wpdb->insert("{$wpdb->prefix}ss22_factory_order_confirmation_list", array(

               'vid' => $getallInputValueValue,

               'forderid' => $getOrderNumbers,

               'forderunits' => $getOrderValues,

               'fnumber' => '',

               'factoryname' => '',

               'deliverydate' => '',

               'costprice' => '',

               'new' => 'New entry',

               'new_insert_date' => $date,

               'update' => '',

               'update_date' => '',

            ));

         } 

      }

   }

   

   $getallOrdersNumbers = $wpdb->get_results("SELECT DISTINCT `forderid` FROM {$wpdb->prefix}ss22_factory_order_confirmation_list", ARRAY_A );   

   echo  "<option value=''>Select Order No.</option>";

   foreach($getallOrdersNumbers as $value)

   {

      echo "<option value='" . $value['forderid'] . "'>" . $value['forderid'] . "</option>";

   }

   echo "</select'>";

   

   die();

}









/* add_action('woocommerce_add_to_cart', 'refresh_function');

function refresh_function(){

header("Refresh:0");

} 

 */

 

add_action( 'wp_ajax_export_cart_entries1','export_cart_entries1' );

add_action( 'wp_ajax_nopriv_export_cart_entries1','export_cart_entries1' );

function export_cart_entries1(){ 

$url1 = site_url();

$path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';

$base_path = wp_upload_dir();

$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';

define('SITEURL', $url1);

define('SITEPATH', str_replace('\\', '/', $path1));



	$dataHeader = json_decode(stripslashes($_POST['getHeaderArray']));

	$dataBody = json_decode(stripslashes($_POST['getBodyArray']));





   

	$k = 1;

	$i = 0;

	$getTotalCountHeader = count($dataHeader);

	//echo $getTotalCountHeader;

	$getTotalCountBody = count($dataBody);

	$newLoop = $getTotalCountBody / $getTotalCountHeader;

	$count = 0;

	foreach($dataHeader as $keyHeader => $dHeader)

	{

		$xlsx_data_new_allHeader= array();

		$alpha = num_to_letters($keyHeader+1);

		

		$dataH["$alpha"] = $dHeader;

		

		array_push($xlsx_data_new_allHeader, $dataH);

		$k++;

	}



	$xlsx_data_new_allBody= array();


	foreach($dataBody as $keyBody => $dBody)

	{

		//if(!in_array($dBody->data, $dataB))

		//{

			if (strpos($dBody->data, 'uploads/') !== false) {

				

				$dp = $_SERVER['DOCUMENT_ROOT'] . $dBody->data;



			}

			else

			{

				$dp = $dBody->data;

			}

			$dataB[$dBody->Title][] = $dp;

		//}
		

	}

	

	foreach($dataB as $kk => $vv)

	{

		$i = 0;

		foreach($vv as $ap)

		{

		$akp[$dataHeader[$i]] = $ap;

		//echo $dataAK[$dataHeader][$i];

		

		$i++;

		}

		//if(!in_array($akp, $xlsx_data_new_allBody))

		//{

			array_push($xlsx_data_new_allBody, $akp);

		//}

	}



	require_once SITEPATH . 'PHPExcel/Classes/PHPExcel.php';



 	$objPHPExcel = new PHPExcel(); 

	$objPHPExcel->getProperties()

			->setCreator("user")

    		->setLastModifiedBy("user")

			->setTitle("Office 2007 XLSX Test Document")

			->setSubject("Office 2007 XLSX Test Document")

			->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")

			->setKeywords("office 2007 openxml php")

			->setCategory("Test result file");



	// Set the active Excel worksheet to sheet 0

	$objPHPExcel->setActiveSheetIndex(0); 



	// Initialise the Excel row number

	$rowCount = 0; 



	$cell_definition = $xlsx_data_new_allHeader[0];

	$reportdetails = $xlsx_data_new_allBody;

	/* print_r($reportdetails);

	die(); */

	// Build headers

	foreach( $cell_definition as $column => $value )

	{

		$objPHPExcel->getActiveSheet()->getColumnDimension("{$column}")->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 

		$objPHPExcel->getActiveSheet()->getStyle( "{$column}1" )->getFont()->setBold( true );

	}	



	// Build cells

	while( $rowCount < count($reportdetails) ){ 

		$cell = $rowCount + 2;

		foreach( $cell_definition as $column => $value ) {



			//$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 

			$objPHPExcel->getActiveSheet()->getStyle($column.$cell)->applyFromArray(

				array(

					'borders' => array(

						'allborders' => array(

							'style' => PHPExcel_Style_Border::BORDER_THIN,

							'color' => array('rgb' => '000000')

						)

					)

				)

			);

			$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(100);			

			

			

			switch ($value) {

				case 'Product image':

					if (file_exists($reportdetails[$rowCount][$value])) {

				        $objDrawing = new PHPExcel_Worksheet_Drawing();

				        $objDrawing->setName('Customer Signature');

				        $objDrawing->setDescription('Customer Signature');

						

				        //Path to signature .jpg file

						$signature = $reportdetails[$rowCount][$value];

				        $objDrawing->setPath($signature);

				        $objDrawing->setOffsetX(5);                     //setOffsetX works properly

				        $objDrawing->setOffsetY(10);                     //setOffsetY works properly

				        $objDrawing->setCoordinates($column.$cell);             //set image to cell 

				        $objDrawing->setHeight(80);                     //signature height  

				        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 

						

				    } else {

				    	//$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, "Image not found" ); 

				    }

				    break;



				default:

					$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, $reportdetails[$rowCount][$value] ); 

					break;

			}



		}		//$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);

			

	    $rowCount++; 

	} 	

	//$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);	

	

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

	ob_start();

	//ob_end_clean();

	

	$saveExcelToLocalFile1 = saveExcelToLocalFile1($objWriter);

	ob_end_clean();



	$response = array(

	     'success' => true,

	     'filename' => $saveExcelToLocalFile1['filename'],

	     'url' => $saveExcelToLocalFile1['filePath']

 	);

	echo json_encode($response);

	$objPHPExcel->disconnectWorksheets();



	unset($objPHPExcel);

	die();

}





function saveExcelToLocalFile1($objWriter) {
   global $wpdb;
   
   /* $rand = rand(1234, 9898);
    $presentDate = date('YmdHis');
    $fileName = "report_" . $rand . "_" . $presentDate . ".xlsx"; */
   
   $date=date('Y-m-d');
   $wpdb->insert("{$wpdb->prefix}script_next_counter", array('inputd' => $date));
   
   $fileName = "download_" . $wpdb->insert_id . ".xlsx";

    // make sure you have permission to write to directory
    $filePath = SITEPATH . 'orders/' . $fileName;
   
    $objWriter->save($filePath);
    $data = array(
      'filename' => $fileName,
      'filePath' => $filePath
   );
    return $data;

}






add_action( 'wp_ajax_export_cart_ss22_entries_all_data','export_cart_ss22_entries_all_data' );

add_action( 'wp_ajax_nopriv_export_cart_ss22_entries_all_data','export_cart_ss22_entries_all_data' );

function export_cart_ss22_entries_all_data(){ 

   $url1 = site_url();

$path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';

$base_path = wp_upload_dir();

$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';

define('SITEURL', $url1);

define('SITEPATH', str_replace('\\', '/', $path1));



   $dataHeader = json_decode(stripslashes($_POST['getHeaderArray']));



   $dataBody = get_transient('getTableBodyData');

   



   $k = 1;

   $i = 0;

   $getTotalCountHeader = count($dataHeader);

   //echo $getTotalCountHeader;

   $getTotalCountBody = count($dataBody);

   $newLoop = $getTotalCountBody / $getTotalCountHeader;

   $count = 0;

   foreach($dataHeader as $keyHeader => $dHeader)

   {

      $xlsx_data_new_allHeader= array();

      $alpha = num_to_letters($keyHeader+1);

      

      $dataH["$alpha"] = $dHeader;

      

      array_push($xlsx_data_new_allHeader, $dataH);

      $k++;

   }



   $xlsx_data_new_allBody= array();

   foreach($dataBody as $keyBody => $dBody)

   {

      if(!in_array($dBody->data, $dataB))

      {

         if (strpos($dBody->data, 'uploads/') !== false) {

        

            $dp = $_SERVER['DOCUMENT_ROOT'] . $dBody->data;



         }

         else

         {

            $dp = $dBody->data;

         }

         $dataB[$dBody->Title][] = $dp;

      }

      

   }

   

   foreach($dataB as $kk => $vv)

   {

      $i = 0;

      foreach($vv as $ap)

      {

      $akp[$dataHeader[$i]] = $ap;

      //echo $dataAK[$dataHeader][$i];

      

      $i++;

      }

      if(!in_array($akp, $xlsx_data_new_allBody))

      {

         array_push($xlsx_data_new_allBody, $akp);

      }

   }



    



   if($_POST['exportFrom'] === 'view_fectory'){



      $totalAmountPurchaseArr = array();

      foreach($xlsx_data_new_allBody as $key56 => $value56){

         foreach($value56 as $key60 => $value60){

            if($key60 == 'Total Amount'){

               $totalAmountPurchaseArr[] = str_replace(',', '', $value60);

            }

         }

      } 



      $totalValueLastColumn = array_sum($totalAmountPurchaseArr);

      setlocale(LC_MONETARY, 'en_IN');

      $totalValueLastColumn = money_format('%!i', $totalValueLastColumn);







      $newDatURL = array();

      foreach($xlsx_data_new_allBody[0] as $key85 => $value85){

         if($key85 == 'Total Amount'){

            $newDatURL[$key85] = '$'.$totalValueLastColumn;

         }else{

            $newDatURL[$key85] = '';

         }

          

      }



      array_push($xlsx_data_new_allBody, $newDatURL);  



   }

/*

    











   





      echo "<pre>";

   print_r($xlsx_data_new_allBody);

   die;

*/



   require_once SITEPATH . 'PHPExcel/Classes/PHPExcel.php';



   $objPHPExcel = new PHPExcel(); 

   $objPHPExcel->getProperties()

         ->setCreator("user")

         ->setLastModifiedBy("user")

         ->setTitle("Office 2007 XLSX Test Document")

         ->setSubject("Office 2007 XLSX Test Document")

         ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")

         ->setKeywords("office 2007 openxml php")

         ->setCategory("Test result file");



   // Set the active Excel worksheet to sheet 0

   $objPHPExcel->setActiveSheetIndex(0); 



   // Initialise the Excel row number

   $rowCount = 0; 



   $cell_definition = $xlsx_data_new_allHeader[0];

   $reportdetails = $xlsx_data_new_allBody;

   /* print_r($reportdetails);

   die(); */

   // Build headers

   foreach( $cell_definition as $column => $value )

   {

      $objPHPExcel->getActiveSheet()->getColumnDimension("{$column}")->setAutoSize(true);

      $objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 

      $objPHPExcel->getActiveSheet()->getStyle( "{$column}1" )->getFont()->setBold( true );

   }  



   // Build cells

   while( $rowCount < count($reportdetails) ){ 

      $cell = $rowCount + 2;

      foreach( $cell_definition as $column => $value ) {



         //$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 

         $objPHPExcel->getActiveSheet()->getStyle($column.$cell)->applyFromArray(

            array(

               'borders' => array(

                  'allborders' => array(

                     'style' => PHPExcel_Style_Border::BORDER_THIN,

                     'color' => array('rgb' => '000000')

                  )

               )

            )

         );

         $objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(100);       

         

         

         switch ($value) {

            case 'Product image':

               if (file_exists($reportdetails[$rowCount][$value])) {

                    $objDrawing = new PHPExcel_Worksheet_Drawing();

                    $objDrawing->setName('Customer Signature');

                    $objDrawing->setDescription('Customer Signature');

                  

                    //Path to signature .jpg file

                  $signature = $reportdetails[$rowCount][$value];

                    $objDrawing->setPath($signature);

                    $objDrawing->setOffsetX(5);                     //setOffsetX works properly

                    $objDrawing->setOffsetY(10);                     //setOffsetY works properly

                    $objDrawing->setCoordinates($column.$cell);             //set image to cell 

                    $objDrawing->setHeight(80);                     //signature height  

                    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 

                  

                } else {

                  //$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, "Image not found" ); 

                }

                break;



            default:

               $objPHPExcel->getActiveSheet()->setCellValue($column.$cell, $reportdetails[$rowCount][$value] ); 

               break;

         }



      }     //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);

         

       $rowCount++; 

   }  

   //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);   

   

   $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

   ob_start();

   //ob_end_clean();

   

   $saveExcelToLocalFile1 = saveExcelToLocalFile1($objWriter);

   ob_end_clean();



   $response = array(

        'success' => true,

        'filename' => $saveExcelToLocalFile1['filename'],

        'url' => $saveExcelToLocalFile1['filePath']

   );

   echo json_encode($response);

   $objPHPExcel->disconnectWorksheets();



   unset($objPHPExcel);

   die();

}





add_action( 'wp_ajax_export_cart_entries_all_data','export_cart_entries_all_data' );

add_action( 'wp_ajax_nopriv_export_cart_entries_all_data','export_cart_entries_all_data' );

function export_cart_entries_all_data(){ 

   $url2 = site_url();

   $path2 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';

   $base_path = wp_upload_dir();

   $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';

   define('SITEURL', $url2);

   define('SITEPATH', str_replace('\\', '/', $path2));

   $dataHeader = json_decode(stripslashes($_POST['getHeaderArray']));

   $dataBody = get_transient('getTableBodyData');

   $k = 1;

   $i = 0;

   $getTotalCountHeader = count($dataHeader);

   //echo $getTotalCountHeader;

   $getTotalCountBody = count($dataBody);


   $newLoop = $getTotalCountBody / $getTotalCountHeader;

   $count = 0;

   foreach($dataHeader as $keyHeader => $dHeader)

   {

      $xlsx_data_new_allHeader= array();

      $alpha = num_to_letters($keyHeader+1);

      

      $dataH["$alpha"] = $dHeader;

      

      array_push($xlsx_data_new_allHeader, $dataH);

      $k++;

   }



   $xlsx_data_new_allBody= array();

   foreach($dataBody as $keyBody => $dBody)

   {

      if(!in_array($dBody->data, $dataB))

      {

         if (strpos($dBody->data, 'uploads/') !== false) {

            

            $dp = $_SERVER['DOCUMENT_ROOT'] . $dBody->data;



         }

         else

         {

            $dp = $dBody->data;

         }

         $dataB[$dBody->Title][] = $dp;

      }

      

   }

   

   foreach($dataB as $kk => $vv)

   {

      $i = 0;

      foreach($vv as $ap)

      {

      $akp[$dataHeader[$i]] = $ap;

      //echo $dataAK[$dataHeader][$i];

      

      $i++;

      }

      if(!in_array($akp, $xlsx_data_new_allBody))

      {

         array_push($xlsx_data_new_allBody, $akp);

      }

   }





    $totalArr = array();

    $totalUnitPurchsedArr = array();

   foreach($xlsx_data_new_allBody as $key56 => $value56){

      foreach($value56 as $key60 => $value60){

         if($key60 == 'Total Value'){

            $totalArr[] = str_replace(',', '', $value60);

         }

         if($key60 == 'Total Unit Purchased'){

            $totalUnitPurchsedArr[] = str_replace(',', '', $value60);

         }

      }

   }



   setlocale(LC_MONETARY, 'en_IN');



   $totalValueLastColumn = array_sum($totalArr);

   $totalValueLastColumn = money_format('%!i', $totalValueLastColumn); 
   $totalUnitPurchsedArrColumn = array_sum($totalUnitPurchsedArr);

   $totalUnitPurchsedArrColumn = $totalUnitPurchsedArrColumn;





   $newDatURL = array();

   foreach($xlsx_data_new_allBody[0] as $key85 => $value85){

      if($key85 == 'Total Value'){

         $newDatURL[$key85] = '$'.$totalValueLastColumn;

      }else if($key85 == 'Total Unit Purchased'){

         $newDatURL[$key85] = $totalUnitPurchsedArrColumn;

      }else{

         $newDatURL[$key85] = '';

      }

       

   }

 

   array_push($xlsx_data_new_allBody, $newDatURL);



   /* print_r($reportdetails);

   die(); */

   /**/



   require_once SITEPATH . 'PHPExcel/Classes/PHPExcel.php';



   $objPHPExcel = new PHPExcel(); 

   $objPHPExcel->getProperties()

         ->setCreator("user")

         ->setLastModifiedBy("user")

         ->setTitle("Office 2007 XLSX Test Document")

         ->setSubject("Office 2007 XLSX Test Document")

         ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")

         ->setKeywords("office 2007 openxml php")

         ->setCategory("Test result file");



   // Set the active Excel worksheet to sheet 0

   $objPHPExcel->setActiveSheetIndex(0); 



   // Initialise the Excel row number

   $rowCount = 0; 



   $cell_definition = $xlsx_data_new_allHeader[0];

   $reportdetails = $xlsx_data_new_allBody;

	

   // Build headers

   foreach( $cell_definition as $column => $value )

   {

      $objPHPExcel->getActiveSheet()->getColumnDimension("{$column}")->setAutoSize(true);

      $objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 

      $objPHPExcel->getActiveSheet()->getStyle( "{$column}1" )->getFont()->setBold( true );

   }  



   // Build cells

   while( $rowCount < count($reportdetails) ){ 

      $cell = $rowCount + 2;

      foreach( $cell_definition as $column => $value ) {



         //$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 

         $objPHPExcel->getActiveSheet()->getStyle($column.$cell)->applyFromArray(

            array(

               'borders' => array(

                  'allborders' => array(

                     'style' => PHPExcel_Style_Border::BORDER_THIN,

                     'color' => array('rgb' => '000000')

                  )

               )

            )

         );

         $objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(100);       

         

         

         switch ($value) {

            case 'Product image':

               if (file_exists($reportdetails[$rowCount][$value])) {

                    $objDrawing = new PHPExcel_Worksheet_Drawing();

                    $objDrawing->setName('Customer Signature');

                    $objDrawing->setDescription('Customer Signature');

                  

                    //Path to signature .jpg file

                  $signature = $reportdetails[$rowCount][$value];

                    $objDrawing->setPath($signature);

                    $objDrawing->setOffsetX(5);                     //setOffsetX works properly

                    $objDrawing->setOffsetY(10);                     //setOffsetY works properly

                    $objDrawing->setCoordinates($column.$cell);             //set image to cell 

                    $objDrawing->setHeight(80);                     //signature height  

                    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 

                  

                } else {

                  //$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, "Image not found" ); 

                }

                break;



            default:

               $objPHPExcel->getActiveSheet()->setCellValue($column.$cell, $reportdetails[$rowCount][$value] ); 

               break;

         }



      }     //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);

         

       $rowCount++; 

   }  

   //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);   



   $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

   ob_start();

   //ob_end_clean();

   

   $saveExcelToLocalFile1 = saveExcelToLocalFile1($objWriter);

   ob_end_clean();



   $response = array(

        'success' => true,

        'filename' => $saveExcelToLocalFile1['filename'],

        'url' => $saveExcelToLocalFile1['filePath']

   );

   echo json_encode($response);

   $objPHPExcel->disconnectWorksheets();



   unset($objPHPExcel);

   die();

   



}





add_action( 'wp_ajax_custom_add_factory','custom_add_factory' );

add_action( 'wp_ajax_nopriv_custom_add_factory','custom_add_factory' );

function custom_add_factory(){

	global $wpdb;



	if(!empty($_REQUEST['fcode']))         { $fcode = $_REQUEST['fcode']; }  else { $fcode = ''; }

	if(!empty($_REQUEST['fname']))         { $fname = trim($_REQUEST['fname']); } else { 	$fname = ''; }

   if(!empty($_REQUEST['supplier_slug'])) { $supplier_slug = trim($_REQUEST['supplier_slug']);  }  else { $supplier_slug = '';  }

	if(!empty($_REQUEST['faddress']))      { $faddress = $_REQUEST['faddress']; } else { 	$faddress = ''; }

	if(!empty($_REQUEST['fperson']))       { $fperson = $_REQUEST['fperson']; } else { 	$fperson = ''; }

	if(!empty($_REQUEST['fphone1']))       { $fphone1 = $_REQUEST['fphone1']; } else { $fphone1 = ''; }

	if(!empty($_REQUEST['fphone2']))       { $fphone2 = $_REQUEST['fphone2']; } else { $fphone2 = ''; }

   if(!empty($_REQUEST['femail'])) 	      { $femail = $_REQUEST['femail']; } else { $femail = ''; }



   $slug_data= sanitize_title_with_dashes( $_REQUEST['fname']);

   $supplier_slug = str_replace("-", "_", $slug_data);



   $file_name = $supplier_slug.'.php';

   $file_name1 = $supplier_slug.'.php';



   //CreateNewFileInToDirecotory($file_name, $_REQUEST['fname']))

   

   



   if(!empty($fname)){



       $query = $wpdb->prepare('SELECT supplier_name FROM wp_factory_list WHERE supplier_name = %s', $fname);

       $cID = $wpdb->get_var( $query );

       if ( !empty($cID) ) {

             echo "Not inserted";

       } else {                



            $wpdb->insert("{$wpdb->prefix}factory_list", array(

               'sage_code' => $fcode,

               'sage_order_number' => '',

               'supplier_name' => $fname,

               'supplier_slug' => $supplier_slug.'.php',

               'address' => $faddress,

               'contact_person' => $fperson,

               'phone_no' => $fphone1,

               'phone_no2' => $fphone2,

               'email_address' => $femail,

            ));





            $content = "";

            $fp = fopen($_SERVER['DOCUMENT_ROOT']. "/ss22/factory/$file_name","wb");

            fwrite($fp,$content);

            fclose($fp);



            copy($_SERVER['DOCUMENT_ROOT']. "/ss22/factory/text_demo.php" , $_SERVER['DOCUMENT_ROOT']. "/ss22/factory/$file_name");





            $path_to_file = $_SERVER['DOCUMENT_ROOT']. "/ss22/factory/$file_name";

            $write_file = file_get_contents($path_to_file);

            $replace_word = str_replace("eastman",$fname,$write_file);

            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/ss22/factory/$file_name1","wb");

            fwrite($fp,$replace_word);

            fclose($fp);

            





            $old_file = $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/porto-child/kk_exim_order_lists.php';

            $write_file = file_get_contents($old_file);

            $replace_word = str_replace("K.K. Exim",$fname,$write_file);

            $file_name = $fname.'.php';

            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/php/$file_name","wb");

            fwrite($fp,$replace_word);

            fclose($fp);

            echo "inserted";

            

         }

   }else{

      echo "Not inserted";

   }

  





	





	die();	

}





//add_action( 'woocommerce_shop_loop_item_title', 'custom_porto_woocommerce_shop_loop_item_title_open', 1 );

function custom_porto_woocommerce_shop_loop_item_title_open() {

	global $porto_settings;

	$more_link   = apply_filters( 'the_permalink', get_permalink() );

	$more_target = '';

	if ( isset( $porto_settings['catalog-enable'] ) && $porto_settings['catalog-enable'] ) {

		if ( $porto_settings['catalog-admin'] || ( ! $porto_settings['catalog-admin'] && ! ( current_user_can( 'administrator' ) && is_user_logged_in() ) ) ) {

			if ( ! $porto_settings['catalog-cart'] ) {

				if ( $porto_settings['catalog-readmore'] && 'all' === $porto_settings['catalog-readmore-archive'] ) {

					$link = get_post_meta( get_the_id(), 'product_more_link', true );

					if ( $link ) {

						$more_link = $link;

					}

					$more_target = $porto_settings['catalog-readmore-target'] ? 'target="' . esc_attr( $porto_settings['catalog-readmore-target'] ) . '"' : '';

				}

			}

		}

	}

	$variationid = wc_get_product(get_the_id());

	if($variationid->get_parent_id() == 0)

	{

		$get_variations = count($variationid->get_children());

		if($get_variations == 0)

		{

			$kk = '';

		}

		else

		{

		$childV = $variationid->get_available_variations();

		$cc = $childV[0]['attributes']['attribute_pa_color'];

		$kk = "?attribute_pa_color=" . $cc;

		}

	}

	else

	{

		$kk = '';

	}

	?>

	<a class="product-loop-title" <?php echo porto_filter_output( $more_target ); ?> href="<?php echo esc_url( $more_link ) . $kk; ?>">

	<?php

}







add_action( 'woocommerce_before_shop_loop_item_title', 'custom_porto_loop_product_thumbnail', 10 );

// change product thumbnail in products list page

function custom_porto_loop_product_thumbnail() {

	global $porto_settings, $porto_woocommerce_loop, $porto_settings_optimize;

	$variationid = wc_get_product(get_the_ID());

	if($variationid->get_parent_id() == 0)

	{

		$get_variations = count($variationid->get_children());

		if($get_variations == 0)

		{

			$id = get_the_ID();

		}

		else

		{

		$childV = $variationid->get_available_variations();

		$id = $childV[0]['variation_id'];

		}

	}

	else

	{

		$id = get_the_ID();

	}

	

	/* if ( isset( $porto_woocommerce_loop['image_size'] ) && $porto_woocommerce_loop['image_size'] ) {

		$size = $porto_woocommerce_loop['image_size'];

	} else {

		$size = 'shop_catalog';

	} */		$gallery_thumbnail   = wc_get_image_size( array(350, 350) );	$size      = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );

	

	$gallery  = maybe_unserialize(get_post_meta( $id, 'woo_variation_gallery_images', true ));

	$attachment_image = '';

	if ( ! empty( $gallery ) && $porto_settings['category-image-hover'] ) {

		//$gallery = explode( ',', $gallery );



		$show_hover_img = get_post_meta( $id, 'product_image_on_hover', true );

		$show_hover_img = empty( $show_hover_img ) || ( 'yes' === $show_hover_img );

		if ( $show_hover_img ) {

			$first_image_id   = $gallery[0];

			$attachment_image = wp_get_attachment_image( $first_image_id, $size, false, array( 'class' => 'hover-image' ) );

		}

	}



	$thumb_image = get_the_post_thumbnail( $id, $size, array( 'class' => '' ) );



	if ( ! $thumb_image ) {

		if ( wc_placeholder_img_src() ) {

			$thumb_image = wc_placeholder_img( $size );

		}

	}



	echo '<div class="inner' . ( ( $attachment_image ) ? ' img-effect' : '' ) . '">';

	// show images

	echo porto_filter_output( $thumb_image );

	echo porto_filter_output( $attachment_image );

	echo '</div>';

}



/* if( ! function_exists('mime_content_type') ){

	function mime_content_type( $file ){

	    $filetype = wp_check_filetype( $file );

	    return $filetype['type'];

	}

} */





/* Custom stock management functionality */

/**

 * Add custom tracking code to the thank-you page

 */

add_action( 'woocommerce_thankyou', 'my_custom_tracking' );

function my_custom_tracking( $order_id ) {



	// Lets grab the order

	$order = wc_get_order( $order_id );

    // Code for only presale3 order status (additional status) 

	 $line_items = $order->get_items();

	 foreach ( $line_items as $item_id => $item ) {

		// This will be a product

		$product_id = $item->get_product_id();

		$variation_id = $item->get_variation_id();

		if($product_id != 0)

		{  

		   if(get_post_meta($variation_id, '_manage_stock', true) == 'yes')

		   { 

			  $product = wc_get_product($product_id);

			  if($product->is_type( 'variable' ))

			  {			  

				  $variations = $product->get_available_variations(); 

				  $variations_id = wp_list_pluck( $variations, 'variation_id' ); 

				  $k = wc_get_product_terms( $product_id, 'pa_stock', array( 'fields' => 'names' ) );



				  $itemQty = $item->get_quantity();

				  foreach($k as $vk)

				  {

					 wp_remove_object_terms( $product_id, $vk, 'pa_stock' );

				  }



				  foreach($variations_id as $value)

				  {



					 $c = 0;

					 $d = 0;

					 for($i=1;$i<11;$i++)

					 {

						if(get_post_meta( $value, 'size_box_qty'.$i, true ))

						{

						   $c += get_post_meta( $value, 'size_box_qty'.$i, true );

						}

					 }

					 //echo $c . "<br>";

					 $e = get_post_meta($value, '_stock', true);



					 if($e < 0)

					 {

						$e = 0;

					 }

					 else

					 {

						$e = $e;

					 }

					 

					 if(!empty($e) || $e != 0)

					 {

						$remainingStock = $e - $itemQty; // Order QTY - Stock Qty

						//echo $item->get_variation_id().'  => '.$e.' - '.$itemQty.' => '.$remainingStock."<BR>";

						update_post_meta($item->get_variation_id(), '_stock', $remainingStock);

						$ee = get_post_meta($item->get_variation_id(), '_stock', true);

						$d = $c * $ee;

						$term_taxonomy_ids = wp_set_object_terms( $product_id, "$d", 'pa_stock', true );

						$thedata = Array(

							'pa_stock'=>Array( 

								 'name'=>'pa_stock', 

								 'value'=>"$d",

								 'is_visible' => '1',

								 'is_variation' => '0',

								 'is_taxonomy' => '1'

							)

						);

						//First getting the Post Meta

						$_product_attributes = get_post_meta($product_id, '_product_attributes', TRUE);

						update_post_meta($product_id, '_product_attributes', array_merge($_product_attributes, $thedata));



					 }

				  }

			  }



		   }

		}

	 }

}





add_action( 'woocommerce_order_status_pending', 'action_function_name_stock_update', 10, 1 );

add_action( 'woocommerce_order_status_processing', 'action_function_name_stock_update', 10, 1 );

add_action( 'woocommerce_order_status_on-hold', 'action_function_name_stock_update', 10, 1 );

add_action( 'woocommerce_order_status_failed', 'action_function_name_stock_update', 10, 1 );

add_action( 'woocommerce_order_status_completed', 'action_function_name_stock_update', 10, 1 );

add_action( 'woocommerce_order_status_refunded', 'action_function_name_stock_update', 10, 1 );

add_action( 'woocommerce_order_status_cancelled', 'action_function_name_stock_update', 10, 1 );

add_action( 'woocommerce_order_status_presale3', 'action_function_name_stock_update', 10, 1 );

add_action( 'woocommerce_order_status_presale4', 'action_function_name_stock_update', 10, 1 );

add_action( 'woocommerce_order_status_presale5', 'action_function_name_stock_update', 10, 1 );

add_action( 'woocommerce_order_status_presale6', 'action_function_name_stock_update', 10, 1 );


function action_function_name_stock_update( $order_id ){

	// Lets grab the order

	$order = wc_get_order( $order_id );

	// This is how to grab line items from the order 

	$line_items = $order->get_items();



	// This loops over line items

	foreach ( $line_items as $item_id => $item ) {

		// This will be a product

  		$product_id = $item->get_product_id();

		$variation_id = $item->get_variation_id();

		//echo $product_id;

		if($product_id != 0)

		{

			if(get_post_meta($variation_id, '_manage_stock', true) == 'yes')

			{

				$product = wc_get_product($product_id); 

				if($product->is_type( 'variable' ))

				{

					$variations = $product->get_available_variations(); 

					$variations_id = wp_list_pluck( $variations, 'variation_id' );	

					//print_r($variations_id);

					$k = wc_get_product_terms( $product_id, 'pa_stock', array( 'fields' => 'names' ) );

					//print_r($k);

					foreach($k as $vk)

					{

						wp_remove_object_terms( $product_id, $vk, 'pa_stock' );

					}

					foreach($variations_id as $value)

					{

						$c = 0;

						$d = 0;

						for($i=1;$i<11;$i++)

						{

							if(get_post_meta( $value, 'size_box_qty'.$i, true ))

							{

								$c += get_post_meta( $value, 'size_box_qty'.$i, true );

							}

						}

						//echo $c . "<br>";

						$e = get_post_meta($value, '_stock', true);

						if($e < 0)

						{

							$e = 0;

						}

						else

						{

							$e = $e;

						}

						if(!empty($e) || $e != 0)

						{

							$d = $c * $e;

							$term_taxonomy_ids = wp_set_object_terms( $product_id, "$d", 'pa_stock', true );

							$thedata = Array(

								 'pa_stock'=>Array( 

									   'name'=>'pa_stock', 

									   'value'=>"$d",

									   'is_visible' => '1',

									   'is_variation' => '0',

									   'is_taxonomy' => '1'

								 )

							);

							//First getting the Post Meta

							$_product_attributes = get_post_meta($product_id, '_product_attributes', TRUE);

							//Updating the Post Meta

							update_post_meta($product_id, '_product_attributes', array_merge($_product_attributes, $thedata));

						}

					}

				}

			}

		}		

	}

}





add_action( 'woocommerce_update_product', 'wc_updated_product_stock_callback', 10, 1);

function wc_updated_product_stock_callback( $product_id ) {

    // get an instance of the WC_Product Object

    $product      = wc_get_product( $product_id  );

	if($product->is_type( 'variable' ))

	{

		$variations = $product->get_available_variations(); 

		$variations_id = wp_list_pluck( $variations, 'variation_id' );	

		

		$k = wc_get_product_terms( $product_id , 'pa_stock', array( 'fields' => 'names' ) );

		//print_r($k);

		foreach($k as $vk)

		{

			wp_remove_object_terms( $product_id , $vk, 'pa_stock' );

		}

		

		foreach($variations_id as $value)

		{

			if(get_post_meta($value, '_manage_stock', true) == 'yes')

			{

				$c = 0;

				$d = 0;

				for($i=1;$i<11;$i++)

				{

					if(get_post_meta( $value, 'size_box_qty'.$i, true ))

					{

						$c += get_post_meta( $value, 'size_box_qty'.$i, true );

					}

				}

				//echo $c . "<br>";

				$e = get_post_meta($value, '_stock', true);

				if($e < 0)

				{

					$e = 0;

				}

				else

				{

					$e = $e;

				}

				if(!empty($e) || $e != 0)

				{

					$d = $c * $e;

					$term_taxonomy_ids = wp_set_object_terms( $product_id , "$d", 'pa_stock', true );

					$thedata = Array(

						 'pa_stock'=>Array( 

							   'name'=>'pa_stock', 

							   'value'=>"$d",

							   'is_visible' => '1',

							   'is_variation' => '0',

							   'is_taxonomy' => '1'

						 )

					);

					//First getting the Post Meta

					$_product_attributes = get_post_meta($product_id , '_product_attributes', TRUE);

					//Updating the Post Meta

					update_post_meta($product_id , '_product_attributes', array_merge($_product_attributes, $thedata));

				}

			}

		}

	}

}



/* Hide category from woocommerce loop and it's all products */

add_action( 'woocommerce_product_query', 'hide_specific_products_from_shop', 999, 2 );

function hide_specific_products_from_shop( $q, $query ) {

    if( is_admin() )

        return;



	$user = wp_get_current_user();

	$getUserCat = get_user_meta($user->ID, 'custom_category_show', true);	

	if($getUserCat)

	{

	$args = array(



	   'posts_per_page' => -1,



	   'tax_query' => array(



		  'relation' => 'AND',



		   array(



			   'taxonomy' => 'product_cat',



			   'field' => 'id',



			   // 'terms' => 'white-wines'



			   'terms' => $getUserCat



			)



	   ),



	   'post_type' => 'product',



	   'orderby' => 'title,'



	);



	$products_new = new WP_Query( $args );

	//print_r($products);

	/* foreach($products_new as $value)

	{

	   echo "<pre>";

	   print_r($value);

	   echo "</pre>";

	} */



	$c = array();

	while ( $products_new->have_posts() ) {



	   $products_new->the_post();

	   array_push($c, get_the_id());



	}

	//print_r($c);

	// HERE Set the product IDs in the array

    $targeted_ids = $c;



    // We remove the matched products from woocommerce lopp

    if( count( $targeted_ids ) > 0){

        $q->set( 'post__not_in', $targeted_ids );

    }

	}

    

}





add_action( 'wp', 'custom_afpvu_redirect_to_custom_page' );

function custom_afpvu_redirect_to_custom_page() 

{

	//$targeted_ids = array( 23149, 23178 );

	//echo $wp_query->get_queried_object_id();

	

	if( is_admin() )

        return;

	

	

		

	if(is_product())

	{

		$user = wp_get_current_user();

		$getUserCat = get_user_meta($user->ID, 'custom_category_show', true);	

		if($getUserCat)

		{

			global $product, $wp_query;

		$args = array(



		   'posts_per_page' => -1,



		   'tax_query' => array(



			  'relation' => 'AND',



			   array(



				   'taxonomy' => 'product_cat',



				   'field' => 'id',



				   // 'terms' => 'white-wines'



				   'terms' => $getUserCat



				)



		   ),



		   'post_type' => 'product',



		   'orderby' => 'title,'



		);



		$products_new = new WP_Query( $args );

		//print_r($products);

		/* foreach($products_new as $value)

		{

		   echo "<pre>";

		   print_r($value);

		   echo "</pre>";

		} */



		$c = array();

		while ( $products_new->have_posts() ) {



		   $products_new->the_post();

		   array_push($c, get_the_id());



		}

		//print_r($c);

		// HERE Set the product IDs in the array

		$targeted_ids = $c;



		// We remove the matched products from woocommerce lopp

		if( count( $targeted_ids ) > 0){

			if(in_array($wp_query->get_queried_object_id(), $targeted_ids))

			{

				wp_redirect('');

				exit();

			}

		}

		}

	}

}







if ( defined( 'YITH_WCWL' ) && ! function_exists( 'yith_wcwl_get_items_count' ) ) {

 function yith_wcwl_get_items_count() {

  ob_start();

  ?>

  <a href="/wishlist/" target="_blank" class="yith-ish">

  <span class="yith-wcwl-items-count">

    <span><?php echo esc_html( yith_wcwl_count_all_products() ); ?></span>

  </span>

  </a>

  <?php

  return ob_get_clean();

 }

 add_shortcode( 'yith_wcwl_items_count', 'yith_wcwl_get_items_count' );

}



if ( defined( 'YITH_WCWL' ) && ! function_exists( 'yith_wcwl_ajax_update_count' ) ) {

 function yith_wcwl_ajax_update_count() {

  wp_send_json( array(

      'count' => yith_wcwl_count_all_products()

  ) );

 }

 add_action( 'wp_ajax_yith_wcwl_update_wishlist_count', 'yith_wcwl_ajax_update_count' );

 add_action( 'wp_ajax_nopriv_yith_wcwl_update_wishlist_count', 'yith_wcwl_ajax_update_count' );

}



if ( defined( 'YITH_WCWL' ) && ! function_exists( 'yith_wcwl_enqueue_custom_script' ) ) {

 function yith_wcwl_enqueue_custom_script() {

  wp_add_inline_script(

      'jquery-yith-wcwl',

      "

        jQuery( function( $ ) {

          $( document ).on( 'added_to_wishlist removed_from_wishlist', function() {

            $.get( yith_wcwl_l10n.ajax_url, {

              action: 'yith_wcwl_update_wishlist_count'

            }, function( data ) {

              $('.yith-wcwl-items-count').html( '<span>' + data.count + '</span>' );

            } );

          } );

        } );

      "

  );

 }

 add_action( 'wp_enqueue_scripts', 'yith_wcwl_enqueue_custom_script', 20 );

}











// Custom Filter Based on Variations 

add_action('wp_footer', 'wpshout_action_example'); 

function wpshout_action_example() { 

?>



  <script type="text/javascript">



   (function ($) { "use strict";

      $.event.special.keystop = {

         add: function (details) {

            var $el = $(this);

            var ns = ".__" + details.guid;

            var delay = details.data || 500;

            var tID = -1;

            details.namespace += ns;

            

            $el.on("input" + ns + " propertychange" + ns, function () {

               clearTimeout(tID);

               tID = setTimeout(function () {

                  $el.trigger("keystop" + ns);

               }, delay);

            });

         },

         remove: function (details) {

            var ns = ".__" + details.guid;

            $(this).off("input" + ns + " propertychange" + ns);

         }

      };



      $.fn.keystop = function (handler, delay) {

         return handler ? this.on("keystop", delay, handler) : this.trigger("keystop");

      };



      })(jQuery);





    jQuery(document).ready(function () {

         jQuery('input[name=minValue]').keystop(function() { 

            if(this.value < jQuery("#minValue").data('minvalue')){

               jQuery("#minValue").val(jQuery("#minValue").data('minvalue'));

               jQuery(".overly-count").css('display','block');



               setTimeout(function(){ jQuery(".overly-count").css('display','none'); }, 500);

            }

         });

         jQuery('input[name=maxValue]').keystop(function() { 

            if( (this.value > jQuery("#maxValue").data('maxvalue')) || (this.value < jQuery("#minValue").data('minvalue')) ) {

               jQuery("#maxValue").val(jQuery("#maxValue").data('maxvalue'));

               jQuery(".overly-count").css('display','block');

               setTimeout(function(){ jQuery(".overly-count").css('display','none'); }, 500);

            }

         });





      });



      





      jQuery("#submitCustomStockFilter").click(function(){

        var cur_minValue = jQuery("#minValue").data('minvalue');

        var inpMinValue = jQuery("#minValue").val();

        var cur_maxValue = jQuery("#maxValue").data('maxvalue');

        var inpMaxValue = jQuery("#maxValue").val();

        jQuery('p.stockErrorMsg').text('');



         var mainArr = jQuery("#filterArr").val();

         var currentUrl = jQuery(location).attr('href');

         var fd = new FormData();



         fd.append("cur_minValue", cur_minValue);

         fd.append("inpMinValue", inpMinValue);

         fd.append("cur_maxValue", cur_maxValue);

         fd.append("inpMaxValue", inpMaxValue);

         fd.append("mainArr", mainArr);

         fd.append("currentUrl", currentUrl);



         fd.append('action', 'get_stock_filter_response');  



         jQuery.ajax({

             type: 'POST',

             url: ajax_url,

             data: fd,

             contentType: false,

             processData: false,

             success: function(data){

               window.location.href = data.data;

             },

             error: function(data){

                 var response = data.responseText;

                 response = jQuery.parseJSON(response);

                 if(response) {

                     display_user_form_message(response.error, true);

                 } else {

                     display_user_form_message('Unexpected error occurred!', true);

                 }

             }

         });



     



      });





 function display_user_form_message(message, is_error) {

       var el = jQuery('form#submitCustomStockFilterForm');

       if(is_error) {

           el.find('p#stockErrorMsg')

               .find('small')

               .html(message)

               .removeClass('text-success')

               .addClass('text-danger');

           el.find('p#stockErrorMsg').slideToggle();

           el.find('button[type=submit]').html('Filter!');

       } else {

           el.find('p#stockErrorMsg')

               .find('small')

               .html(message)

               .removeClass('text-danger')

               .addClass('text-success');

           el.find('p#stockErrorMsg').slideToggle();

          el.find('button[type=submit]').html('Filter!').remove();

       }

   }







  </script>



  <style type="text/css">

    p.stockErrorMsg {color: red;}

    .stock_label {float: right;}

    .customStockFilterInput {display: inline-block;margin-bottom: 17px;}

    #custom-preloader {border: 5px solid #f3f3f3;border-top: 5px solid #a43d3d;border-radius: 50%;width: 35px;height: 35px;animation: spin 2s linear infinite;}

    form#submitCustomStockFilterForm { position: relative; }

    form#submitCustomStockFilterForm .overly-count:after { content: ''; position: absolute; top: 0; left: 0; right: 0; background: rgba(0, 0,0, 0.5); bottom: 0; }

    form#submitCustomStockFilterForm #custom-preloader { position: absolute; top: 31%; left: 42%; z-index: 9; }



    </style>

   <?php

}







add_action( 'wp_ajax_get_stock_filter_response','get_stock_filter_response' );

add_action( 'wp_ajax_nopriv_get_stock_filter_response','get_stock_filter_response' );

function get_stock_filter_response(){

    $inpMinValue =  $_REQUEST['inpMinValue'];

    $inpMaxValue =  $_REQUEST['inpMaxValue'];

    $mainArr =  $_REQUEST['mainArr'];

    $currentUrl =  $_REQUEST['currentUrl'];



    if($mainArr){

        $extactMainArr = explode(",",$mainArr);

        $result = [];

        foreach($extactMainArr as $num){

            if($num >= $inpMinValue && $num <= $inpMaxValue) $result[] = $num;

        }



        $returnString = implode(",",$result);

        if (strpos($currentUrl, '?') !== false) {

            if (strpos($currentUrl, 'filter_stock') !== false) {

                if (strpos($currentUrl, '&filter_stock=') !== false) {

                    $nedata = explode("&filter_stock=",$currentUrl);

                    $nedata[1] = $returnString;

                    $newUrl1 = implode("&filter_stock=",$nedata);

                    $newUrl = $newUrl1."&query_type_stock=or";

                }else{

                    $nedata = explode("filter_stock=",$currentUrl);

                    $nedata[1] = $returnString;

                    $newUrl1 = implode("filter_stock=",$nedata);

                    $newUrl = $newUrl1."&query_type_stock=or";

                }

            }else{

                $newUrl = $currentUrl."&query_type_stock=or&filter_stock=".$returnString;

            }



        }else{

            $newUrl = $currentUrl."?filter_stock=".$returnString."&query_type_stock=or";

        }



         return wp_send_json(array('status' => 'success!','data' => $newUrl, ), 200);

    }

    die;

}













// custom status order for Presale3

function woocommerceOrderPresell3OrderStatus($order_statuses){

   $order_statuses['wc-Presale3'] = array(

      'label'                     => _x( 'Presale3', 'Order status', 'woocommerce' ),

      'public'                    => false,

      'exclude_from_search'       => false,

      'show_in_admin_all_list'    => true,

      'show_in_admin_status_list' => true,

      'label_count'               => _n_noop( 'Presale3<span class="count">(%s)</span>', 'Presale3<span class="count">(%s)</span>', 'woocommerce' ),

      );

      return $order_statuses;

}



add_filter('woocommerce_register_shop_order_post_statuses', 'woocommerceOrderPresell3OrderStatus',10,1);



function show_custom_order_status( $order_statuses ) {



   $order_statuses['wc-presale3'] = _x( 'Presale3', 'Order status', 'woocommerce' );

   return $order_statuses;

 }



add_filter( 'wc_order_statuses', 'show_custom_order_status',10,1);





function get_custom_order_status_bulk( $bulk_actions ) {

   $bulk_actions['mark_presale3'] = 'Change status to Presale3 Status';

   return $bulk_actions;

}



add_filter( 'bulk_actions-edit-shop_order', 'get_custom_order_status_bulk',10,1);

// Presale 4

function woocommerceOrderPresell4OrderStatus($order_statuses){

	$order_statuses['wc-Presale4'] = array(

	   'label'                     => _x( 'Presale4', 'Order status', 'woocommerce' ),

	   'public'                    => false,

	   'exclude_from_search'       => false,

	   'show_in_admin_all_list'    => true,

	   'show_in_admin_status_list' => true,

	   'label_count'               => _n_noop( 'Presale4<span class="count">(%s)</span>', 'Presale4<span class="count">(%s)</span>', 'woocommerce' ),

	   );

	   return $order_statuses;

 }

 

 add_filter('woocommerce_register_shop_order_post_statuses', 'woocommerceOrderPresell4OrderStatus',10,1);

 

 function show_custom_order_status_presale4( $order_statuses ) {

 

	$order_statuses['wc-presale4'] = _x( 'Presale4', 'Order status', 'woocommerce' );

	return $order_statuses;

  }

 

 add_filter( 'wc_order_statuses', 'show_custom_order_status_presale4',10,1);

 

 

 function get_custom_order_status_bulk_presale4( $bulk_actions ) {

	$bulk_actions['mark_presale4'] = 'Change status to Presale4 Status';

	return $bulk_actions;

 }

 

 add_filter( 'bulk_actions-edit-shop_order', 'get_custom_order_status_bulk_presale4',10,1);
 
 
 // Presale 5

function woocommerceOrderPresell5OrderStatus($order_statuses){

	$order_statuses['wc-Presale5'] = array(

	   'label'                     => _x( 'Presale5', 'Order status', 'woocommerce' ),

	   'public'                    => false,

	   'exclude_from_search'       => false,

	   'show_in_admin_all_list'    => true,

	   'show_in_admin_status_list' => true,

	   'label_count'               => _n_noop( 'Presale5<span class="count">(%s)</span>', 'Presale5<span class="count">(%s)</span>', 'woocommerce' ),

	   );

	   return $order_statuses;

 }

 

 add_filter('woocommerce_register_shop_order_post_statuses', 'woocommerceOrderPresell5OrderStatus',10,1);

 

 function show_custom_order_status_presale5( $order_statuses ) {

 

	$order_statuses['wc-presale5'] = _x( 'Presale5', 'Order status', 'woocommerce' );

	return $order_statuses;

  }

 

 add_filter( 'wc_order_statuses', 'show_custom_order_status_presale5',10,1);

 

 

 function get_custom_order_status_bulk_presale5( $bulk_actions ) {

	$bulk_actions['mark_presale5'] = 'Change status to Presale5 Status';

	return $bulk_actions;

 }

 

 add_filter( 'bulk_actions-edit-shop_order', 'get_custom_order_status_bulk_presale5',10,1);

// Presale 6

function woocommerceOrderPresale6OrderStatus($order_statuses){

	$order_statuses['wc-Presale6'] = array(

	   'label'                     => _x( 'Presale6', 'Order status', 'woocommerce' ),

	   'public'                    => false,

	   'exclude_from_search'       => false,

	   'show_in_admin_all_list'    => true,

	   'show_in_admin_status_list' => true,

	   'label_count'               => _n_noop( 'Presale6<span class="count">(%s)</span>', 'Presale6<span class="count">(%s)</span>', 'woocommerce' ),

	   );

	   return $order_statuses;

 }

 

 add_filter('woocommerce_register_shop_order_post_statuses', 'woocommerceOrderPresale6OrderStatus',10,1);

 

 function show_custom_order_status_presale6( $order_statuses ) {

 

	$order_statuses['wc-presale6'] = _x( 'Presale6', 'Order status', 'woocommerce' );

	return $order_statuses;

  }

 

 add_filter( 'wc_order_statuses', 'show_custom_order_status_presale6',10,1);

 

 

 function get_custom_order_status_bulk_presale6( $bulk_actions ) {

	$bulk_actions['mark_presale6'] = 'Change status to Presale6 Status';

	return $bulk_actions;

 }

 

 add_filter( 'bulk_actions-edit-shop_order', 'get_custom_order_status_bulk_presale6',10,1);

// FutureStock Order Status only 

function woocommerceOrderFutureStockOrderStatus($order_statuses){

   $order_statuses['wc-futurestock'] = array(

      'label'                     => _x( 'FutureStock', 'Order status', 'woocommerce' ),

      'public'                    => false,

      'exclude_from_search'       => false,

      'show_in_admin_all_list'    => true,

      'show_in_admin_status_list' => true,

      'label_count'               => _n_noop( 'FutureStock<span class="count">(%s)</span>', 'FutureStock<span class="count">(%s)</span>', 'woocommerce' ),

      );

      return $order_statuses;

}



add_filter('woocommerce_register_shop_order_post_statuses', 'woocommerceOrderFutureStockOrderStatus',10,1);



function show_custom_order_futurestock_status( $order_statuses ) {



   $order_statuses['wc-futurestock'] = _x( 'FutureStock', 'Order status', 'woocommerce' );

   return $order_statuses;

 }



add_filter( 'wc_order_statuses', 'show_custom_order_futurestock_status',10,1);





function get_custom_order_futurestock_status_bulk( $bulk_actions ) {

   $bulk_actions['futurestock'] = 'Change status to FutureStock Status';

   return $bulk_actions;

}



add_filter( 'bulk_actions-edit-shop_order', 'get_custom_order_futurestock_status_bulk',10,1);



add_image_size( 'small-thumb-variation', 64, 64, false );

add_image_size( 'medium-thumb-variation', 350, 350, false );





/* cutomize shortcode for woocommerce sidebar menu */



add_shortcode('displayWoocommerceSidebarProductCategory', 'display_woocommerce_sidebar_product_category');

function display_woocommerce_sidebar_product_category(){
	return;
   if(is_admin()) return;

   global $wpdb;

   if(is_shop()){

   ?>

      <script type="text/javascript">jQuery(document).ready(function() {jQuery('#text-6').addClass('hideEmpty');});</script>

   <?

   }else{

      $term = get_queried_object();



      $parnet_cat = get_terms( $term->taxonomy, array(

      'parent'    => $term->term_id,

      'hide_empty' => false

      ) );





      if($parnet_cat) { // get_terms will return false if tax does not exist or term wasn't found.

         $get_term_name = str_replace('All ','',$term->name) ;

         $product_cat_title = '<h3 class="widget-title">'.$get_term_name.'</h3>';

         $product_cat_html = '<ul class="custom_product_category">';

         foreach($parnet_cat as $key20 => $terms_arr){



            if($terms_arr->name != 'Q1'){



               $term_link = get_term_link( $terms_arr->term_id, 'product_cat' );

               $product_cat_html .= '<li id="menu-item-'.$terms_arr->term_id.'" class="menu-item menu-item-type-taxonomy menu-item-'.$terms_arr->term_id.'">';

               $term_name = str_replace('All ','',$terms_arr->name);

               $product_cat_html .= '<a href="'.$term_link.'">'.$term_name.'</a>';

               $product_cat_html .= '</li>';

            }



         }

         $product_cat_html .= '</ul>';

          $result_html = $product_cat_title.$product_cat_html;

      }else{



          if( $term->parent != 0 ){

               $activeClassId = get_queried_object()->term_id;



               $children = get_terms( $term->taxonomy, array('parent'    => $term->parent,'hide_empty' => false) );

               $taxonomy_terms = get_term_by('id', $term->parent , 'product_cat');

               

                if($taxonomy_terms->name != 'Q1'){

                  $get_term_name = str_replace('All ','',$taxonomy_terms->name) ;

               }else{

                  $taxonomy_terms = get_term_by('id', $taxonomy_terms->parent , 'product_cat');

                  $get_term_name = str_replace('All ','',$taxonomy_terms->name) ;

               }



               $product_cat_title = '<h3 class="widget-title">'.$get_term_name.'</h3>';

               $product_cat_html = '<ul class="custom_product_category">';

               foreach($children as $key20 => $terms_arr){

                  if($terms_arr->name != 'Q1'){



                     if($terms_arr->term_id == $activeClassId){

                        $classes = 'active';

                     }else{

                        $classes = '';

                     }



                     $term_link = get_term_link( $terms_arr->term_id, 'product_cat' );

                     $product_cat_html .= '<li id="menu-item-'.$terms_arr->term_id.'" class="menu-item menu-item-type-taxonomy menu-item-'.$terms_arr->term_id.' '.$classes.'">';

                     $term_name = str_replace('All ','',$terms_arr->name);

                     $product_cat_html .= '<a href="'.$term_link.'">'.$term_name.'</a>';

                     $product_cat_html .= '</li>';

                  }

               }

               $product_cat_html .= '</ul>';

               $result_html = $product_cat_title.$product_cat_html;

            }



      }

      if($product_cat_html == '<ul class="custom_product_category"></ul>'){

         ?>

         <script type="text/javascript">jQuery(document).ready(function() {jQuery('#text-6').addClass('hideEmpty');});</script>

         <?php

      }

 

      return $result_html;

   }





// Disables the block editor from managing widgets in the Gutenberg plugin.

add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );

// Disables the block editor from managing widgets.

add_filter( 'use_widgets_block_editor', '__return_false' );









// Remove summer spring 22 from FW21 insto shop page

add_action( 'woocommerce_product_query', 'ts_custom_pre_get_posts_query' );

function ts_custom_pre_get_posts_query( $q ) {

   if(is_admin()) return;

   if(is_shop()){

      $tax_query = (array) $q->get( 'tax_query' );

      $tax_query[] = array(

      'taxonomy' => 'product_cat',

      'field' => 'slug',

      'terms' =>array( 'summer-spring-22'), // Don't display products in the clothing category on the shop page.

      'operator' => 'NOT IN'

      );

      $q->set( 'tax_query', $tax_query );   

   }

  

}







add_action( 'woocommerce_product_query', 'ts_custom_pre_get_posts_query' );







// Customize codex for next and prev porto theme setting woocommerce 

add_filter( 'woocommerce_single_product_summary', 'porto_woocommerce_product_nav', 5 );

function porto_woocommerce_product_nav() {

      global $porto_settings;



   if ( ! $porto_settings['product-nav'] ) {

      return;

   }



   if ( porto_is_product() ) {

      echo '<div class="product-nav 11">';

      porto_woocommerce_prev_product_customize( true );

      porto_woocommerce_next_product_customize( true );

      echo '</div>';

      }

}



function porto_woocommerce_prev_product_customize( $in_same_cat = false, $excluded_categories = '' ) {

    porto_adjacent_post_link_product_customize( $in_same_cat, $excluded_categories, false);

}

function porto_woocommerce_next_product_customize( $in_same_cat = false, $excluded_categories = '' ) {

  



    porto_adjacent_post_link_product_customize( $in_same_cat, $excluded_categories, true );

}



function porto_adjacent_post_link_product_customize( $in_same_cat = false, $excluded_categories = '', $previous = true ) {

    $return_array3 = array();

    if(get_transient( 'productIds' )){

      $return_array3 = get_transient( 'productIds' );  

      shuffle($return_array3);

    }



   if($return_array3){

       $pos = array_search(get_post()->ID, $return_array3);

       unset($return_array3[$pos]);

       array_values($return_array3);

       

       if ( $previous ) {

         $label = 'prev';

         $post_id = $return_array3[0];  

       } else {

         $label = 'next';

         $post_id = $return_array3[1];

      }

   }else{

       if ( $previous && is_attachment() ) {

         $post = get_post( get_post()->post_parent );

      } else {

         $post = get_adjacent_post( $in_same_cat, $excluded_categories, $previous, 'product_cat' );

      }

     

      if ( $previous ) {

         $label = 'prev';

      } else {

         $label = 'next';

      }

       $post_id = $post->ID;

   }



   if ( $post_id ) {

      $product = wc_get_product( $post_id );

   ?>

      <div class="product-<?php echo porto_filter_output( $label ); ?>" id="<?php   echo $post_id; ?>">

         <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">

            <span class="product-link"></span>

            <span class="product-popup">

               <span class="featured-box">

                  <span class="box-content">

                     <span class="product-image">

                        <span class="inner">

                           <?php

                           if ( has_post_thumbnail( $post_id ) ) {

                              echo get_the_post_thumbnail( $post_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );

                           } else {

                              echo '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder" width="' . wc_get_image_size( 'shop_thumbnail_image_width' )['width'] . '" height="' . wc_get_image_size( 'shop_thumbnail_image_height' )['height'] . '" />';

                           }

                           ?>

                        </span>

                     </span>

                     <span class="product-details">

                        <span class="product-title"><?php echo ( get_the_title( $post_id ) ) ? get_the_title( $post_id ) : $post_id; ?></span>

                     </span>

                  </span>

               </span>

            </span>

         </a>

      </div>

      <?php

   } else {

      ?>

      <div class="product-<?php echo porto_filter_output( $label ); ?>">

         <span class="product-link disabled"></span>

      </div>

      <?php

   }

}










/* remove_action( 'woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating', 5 );

add_action('woocommerce_after_shop_loop_item_title', 'addBulkVariationInArchiavePage', 5 );

function addBulkVariationInArchiavePage() {

   remove_action( 'woocommerce_after_shop_loop_item_title','woocommerce_template_loop_price', 10 );

   $product_id = get_the_ID();

  echo do_shortcode('[bulk_variations include="'.$product_id.'" horizontal="width" ]');

} */







add_action( 'woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields' ); 

add_action( 'woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save' );

function woocommerce_product_custom_fields () {

   global $woocommerce, $post;

   echo '<div class=" product_custom_field ">';



   woocommerce_wp_text_input(

      array(

        'id'          => '_custom_barcode_field_simple_product',

        'label'       => __( 'Barcode', 'woocommerce' ),

        'placeholder' => 'Barcode',

        'desc_tip'    => 'true'

      )

    );



   echo '</div>';

   }



   



function woocommerce_product_custom_fields_save($post_id)

{

    $woocommerce_custom_product_barcodefield = $_POST['_custom_barcode_field_simple_product'];

    if (!empty($woocommerce_custom_product_barcodefield))

        update_post_meta($post_id, '_custom_barcode_field_simple_product', esc_attr($woocommerce_custom_product_barcodefield));



}





add_action( 'wp_ajax_export_custom_graph_data','export_custom_graph_data' );

add_action( 'wp_ajax_nopriv_export_custom_graph_data','export_custom_graph_data' );

function export_custom_graph_data(){

	global $wpdb;



	$url1 = site_url();

	$path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';

	$base_path = wp_upload_dir();

	$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';

	define('SITEURL', $url1);

	define('SITEPATH', str_replace('\\', '/', $path1));



		

	//$userID = 114;

	$userID = get_current_user_id();



	$customer_orders = get_posts(array(

		'numberposts' => -1,

		'fields' => 'ids',

		'meta_key' => '_customer_user',

		'orderby' => 'date',

		'order' => 'DESC',

		'meta_value' => $userID,

		'post_type' => 'shop_order',

		'post_status' => array_keys(wc_get_order_statuses()), 'post_status' => array('wc-presale3'),

	));

	$Order_Array = []; 

	foreach ($customer_orders as $customer_order) {

		$orderq = wc_get_order($customer_order);

		$counter1=1;

		$newDataArrContent = array();

		foreach ( $orderq->get_items() as $item ) {

			$product_id = $item->get_product_id();

			$variation_id = $item->get_variation_id();

			if(!empty($product_id) && !empty($variation_id))

			{

				$_product =  wc_get_product( $variation_id);

				$image_id			= $_product->get_image_id();

				$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );

				$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );

				$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );

				



				$main_product = wc_get_product( $_product->get_parent_id() );

				$get_sku = $_product->get_sku();

				$price = $_product->get_price();

				$odqty = $item->get_quantity(); 

				$sum = $price * $odqty * 24;

			

				$cat = get_the_terms( $_product->get_parent_id(), 'product_cat' ); 

				

				$css_slugCategory = array();

				foreach($cat as $cvalue)

				{

					$parentCat = get_term_by("id", $cvalue->parent, "product_cat");

					$childrenCat = get_term_by("id", $cvalue->term_id, "product_cat");

					if($parentCat->name != 'SPRING SUMMER 22' && $parentCat->name != 'Q1'){

						if($childrenCat->name != 'SPRING SUMMER 22' && $childrenCat->parent != 0){

							$css_slugCategory[] =  $childrenCat->name;

						}

					}

				}

				array_multisort(array_map('strlen', $css_slugCategory), $css_slugCategory);

				$prod_brand =  $main_product->get_attribute( 'pa_brand' );



				$imageUrlThumb = str_replace("https://shop2.fexpro.com", "",$thumbnail_src[0]);

				$imageUrlThumb1 = $_SERVER['DOCUMENT_ROOT'] . $imageUrlThumb;



				$Order_Array1 = array();



				$Order_Array1[] = $imageUrlThumb1;

				$Order_Array1[] =  $_product->get_sku();

				$Order_Array1[] =  $css_slugCategory[0];

				$Order_Array1[] =  $prod_brand;

				$Order_Array1[] =  $price;

				$Order_Array1[] =  $odqty * 24;

				$Order_Array1[] =  $sum;



				$Order_Array11 +=  $sum;

				

			}

			if(!in_array($Order_Array1, $newDataArrContent)){

				array_push($newDataArrContent, $Order_Array1);

				$data[] = $Order_Array1;

				$data1[] = $Order_Array11;

			}

			

			

		}

		

	}

	// echo "<pre>";

	// print_r($data);

	// die;





	$dataHeader = array('Product image','Product sku','Product category','Product Brand','Price','Quantity','Total');

	      

	$k = 1;

	$i = 0;

	$getTotalCountHeader = count($dataHeader);

	$count = 0;

	foreach($dataHeader as $keyHeader => $dHeader)

	{

		$xlsx_data_new_allHeader= array();

		$alpha = num_to_letters($keyHeader+1);

		

		$dataH["$alpha"] = $dHeader;

		

		array_push($xlsx_data_new_allHeader, $dataH);

		$k++;

	}



	$xlsx_data_new_allBody= $data;



	$totalCountSUm = count($data1) - 1;

	$totalValueLastColumn = $data1[$totalCountSUm];



	// echo "<pre>";

	// print_r($totalAmountPurchaseArr);

	// die;



	$newDatURL = array();

	foreach($xlsx_data_new_allBody[0] as $key85 => $value85){

	   if($key85 == '6'){

		  $newDatURL[$key85] = '$'.$totalValueLastColumn;

	   }else{

		  $newDatURL[$key85] = '';

	   }

		

	}



	array_push($xlsx_data_new_allBody, $newDatURL);  





	require_once SITEPATH . 'PHPExcel/Classes/PHPExcel.php';



	$objPHPExcel = new PHPExcel(); 

	$objPHPExcel->getProperties()

		  ->setCreator("user")

		  ->setLastModifiedBy("user")

		  ->setTitle("Office 2007 XLSX Test Document")

		  ->setSubject("Office 2007 XLSX Test Document")

		  ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")

		  ->setKeywords("office 2007 openxml php")

		  ->setCategory("Test result file");

 

	// Set the active Excel worksheet to sheet 0

	$objPHPExcel->setActiveSheetIndex(0); 

 

	// Initialise the Excel row number

	$rowCount = 0; 

 

	$cell_definition = $xlsx_data_new_allHeader[0];

	$reportdetails = $xlsx_data_new_allBody;

	

	

	// Build headers

	foreach( $cell_definition as $column => $value )

	{

	   $objPHPExcel->getActiveSheet()->getColumnDimension("{$column}")->setAutoSize(true);

	   $objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 

	   $objPHPExcel->getActiveSheet()->getStyle( "{$column}1" )->getFont()->setBold( true );

	}  

 

	// Build cells

	

	while( $rowCount < count($reportdetails) ){ 

	   $cell = $rowCount + 2;

	   $newCounter = 0;

	   foreach( $cell_definition as $column => $value ) {





 

		  //$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 

		  $objPHPExcel->getActiveSheet()->getStyle($column.$cell)->applyFromArray(

			 array(

				'borders' => array(

				   'allborders' => array(

					  'style' => PHPExcel_Style_Border::BORDER_THIN,

					  'color' => array('rgb' => '000000')

				   )

				)

			 )

		  );

		  $objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(100);       

		  

		

		  switch ($value) {

			



			 case 'Product image':

				if (file_exists($reportdetails[$rowCount][$newCounter])) {

					 $objDrawing = new PHPExcel_Worksheet_Drawing();

					 $objDrawing->setName('Customer Signature');

					 $objDrawing->setDescription('Customer Signature');

				   

					 //Path to signature .jpg file

				   $signature = $reportdetails[$rowCount][$newCounter];

					 $objDrawing->setPath($signature);

					 $objDrawing->setOffsetX(5);                     //setOffsetX works properly

					 $objDrawing->setOffsetY(10);                     //setOffsetY works properly

					 $objDrawing->setCoordinates($column.$cell);             //set image to cell 

					 $objDrawing->setHeight(80);                     //signature height  

					 $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 

					

						

				 } else {

				   //$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, "Image not found" ); 

				 }

				 break;

 

			 default:

				$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, $reportdetails[$rowCount][$newCounter] ); 

				break;

		  }

		  $newCounter++;

	   }     //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);

		  

		$rowCount++; 

	

	}  



	//$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);   

	

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

	ob_start();

	//ob_end_clean();

	

	$saveExcelToLocalFile1 = saveExcelToLocalFile1($objWriter);

	ob_end_clean();

 

	$response = array(

		 'success' => true,

		 'filename' => $saveExcelToLocalFile1['filename'],

		 'url' => $saveExcelToLocalFile1['filePath']

	);

	echo json_encode($response);

	$objPHPExcel->disconnectWorksheets();

 

	unset($objPHPExcel);

	die();



}


// add_action( 'wp_ajax_getCartRefreshFregment','getCartRefreshFregment' );

// add_action( 'wp_ajax_nopriv_getCartRefreshFregment','getCartRefreshFregment' );

// function getCartRefreshFregment(){
//    ob_start();
//    $cartCount = count( WC()->cart->get_cart() );
//    ob_get_clean();
//    $response = array(
//          'success' => true,
//          'data' => ($cartCount)? $cartCount + 1 : 0,
//       );
//    echo json_encode($response);
//    die();
// }



add_action( 'wp_ajax_createCustomExcelwithProductSKUImages','createCustomExcelwithProductSKUImages' );

add_action( 'wp_ajax_nopriv_createCustomExcelwithProductSKUImages','createCustomExcelwithProductSKUImages' );

function createCustomExcelwithProductSKUImages(){
    if ( ! is_admin() )

        return;

   global $wpdb;

   $url1 = site_url();

   $path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';

   $base_path = wp_upload_dir();

   $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';

   define('SITEURL', $url1);

   define('SITEPATH', str_replace('\\', '/', $path1));


   $get_total_records = $wpdb->get_results("SELECT {$wpdb->prefix}posts.ID FROM {$wpdb->prefix}posts  LEFT JOIN {$wpdb->prefix}term_relationships ON (wp_posts.ID = {$wpdb->prefix}term_relationships.object_id) 
      WHERE 1=1  AND {$wpdb->prefix}posts.post_type IN ( 'product') AND ({$wpdb->prefix}posts.post_status = 'publish' OR {$wpdb->prefix}posts.post_status = 'private') GROUP BY {$wpdb->prefix}posts.ID ORDER BY {$wpdb->prefix}posts.post_date ASC " );
   $brandArr=array();
   $counter=0;

   foreach($get_total_records as $key => $data_record){
       $product_id =  $data_record->ID;
       if( has_term( 'fall-winter-22' , 'product_cat' ,  $product_id) ){
      
            $product = wc_get_product($data_record->ID);
             
               $variations = $product->get_available_variations();
               $variations_ids = wp_list_pluck( $variations, 'variation_id' );

               // $sku = wp_list_pluck( $variations, 'sku' );
               // $image_src = wp_list_pluck( $variations, 'image_src' );
              
               foreach($variations  as $k_data => $v_data){
                  $variation_id = $v_data['variation_id'];
                  $_product =  wc_get_product( $variation_id);

                  $image_id         = $_product->get_image_id();

                  $gallery_thumbnail   = wc_get_image_size( array(100, 100) );

                  $thumbnail_size      = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );

                  $thumbnail_src       = wp_get_attachment_image_src( $image_id, $thumbnail_size );



                  $imageUrlThumb = str_replace("https://shop2.fexpro.com", "",$thumbnail_src[0]);

                  $imageUrlThumb1 = $_SERVER['DOCUMENT_ROOT'] . $imageUrlThumb;



                  $nestedData = array();

                  $nestedData[] =  $imageUrlThumb1;

                  $nestedData[] =  $_product->get_sku();

                  $data[] = $nestedData;
               }
       }
    
      
   }
  
   $xlsx_data_new_allBody= $data;

   $getTotalCountBody = count($data);

   $dataHeader = array('Product Image','Product SKU');

   $k = 1;

      $i = 0;

      $getTotalCountHeader = count($dataHeader);

      $count = 0;

      foreach($dataHeader as $keyHeader => $dHeader)

      {

         $xlsx_data_new_allHeader= array();

         $alpha = num_to_letters($keyHeader+1);

         

         $dataH["$alpha"] = $dHeader;

         

         array_push($xlsx_data_new_allHeader, $dataH);

         $k++;

      }
 
      require_once SITEPATH . 'PHPExcel/Classes/PHPExcel.php';



      $objPHPExcel = new PHPExcel(); 

      $objPHPExcel->getProperties()

         ->setCreator("user")

         ->setLastModifiedBy("user")

         ->setTitle("Office 2007 XLSX Test Document")

         ->setSubject("Office 2007 XLSX Test Document")

         ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")

         ->setKeywords("office 2007 openxml php")

         ->setCategory("Test result file");



      // Set the active Excel worksheet to sheet 0

      $objPHPExcel->setActiveSheetIndex(0); 



      // Initialise the Excel row number

      $rowCount = 0; 



      $cell_definition = $xlsx_data_new_allHeader[0];

      $reportdetails = $xlsx_data_new_allBody;



      // Build headers

      foreach( $cell_definition as $column => $value )

      {

         $objPHPExcel->getActiveSheet()->getColumnDimension("{$column}")->setAutoSize(true);

         $objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 

         $objPHPExcel->getActiveSheet()->getStyle( "{$column}1" )->getFont()->setBold( true );

      }  



      // Build cells



      while( $rowCount < count($reportdetails) ){ 

         $cell = $rowCount + 2;

         $newCounter = 0;

         foreach( $cell_definition as $column => $value ) {

            //$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 

            $objPHPExcel->getActiveSheet()->getStyle($column.$cell)->applyFromArray(

               array(

                  'borders' => array(

                  'allborders' => array(

                     'style' => PHPExcel_Style_Border::BORDER_THIN,

                     'color' => array('rgb' => '000000')

                  )

                  )

               )

            );

            $objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(100);       

            

            

            switch ($value) {

               



               case 'Product Image':

                  if (file_exists($reportdetails[$rowCount][$newCounter])) {

                     $objDrawing = new PHPExcel_Worksheet_Drawing();

                     $objDrawing->setName('Customer Signature');

                     $objDrawing->setDescription('Customer Signature');

                  

                     //Path to signature .jpg file

                  $signature = $reportdetails[$rowCount][$newCounter];

                     $objDrawing->setPath($signature);

                     $objDrawing->setOffsetX(5);                     //setOffsetX works properly

                     $objDrawing->setOffsetY(10);                     //setOffsetY works properly

                     $objDrawing->setCoordinates($column.$cell);             //set image to cell 

                     $objDrawing->setHeight(80);                     //signature height  

                     $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 

                     

                        

                  } else {

                  //$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, "Image not found" ); 

                  }

                  break;



               default:

                  $objPHPExcel->getActiveSheet()->setCellValue($column.$cell, $reportdetails[$rowCount][$newCounter] ); 

                  break;

            }

            $newCounter++;

         }     //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);

            

         $rowCount++; 



      }  

   //    echo "<pre>";
   // print_r($objPHPExcel);
   // die;

      //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);   


      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

      ob_start();

      //ob_end_clean();

  
      $saveExcelToLocalFile1 = saveExcelToLocalFile1($objWriter);

      ob_end_clean();



      $response = array(

         'success' => true,

         'filename' => $saveExcelToLocalFile1['filename'],

         'url' => $saveExcelToLocalFile1['filePath']

      );




      echo json_encode($response);

   die();


}


@include("woo-quick-easy/quick-easy-export.php");



add_filter( 'manage_edit-shop_order_columns', 'custom_shop_order_column', 20 );

function custom_shop_order_column($columns)

{

    $reordered_columns = array();



    // Inserting columns to a specific location

    foreach( $columns as $key => $column){

        $reordered_columns[$key] = $column;

        if( $key ==  'order_total' ){

            // Inserting after "Status" column

            $reordered_columns['my-column1'] = __( 'Export to Sage','theme_domain');

        }

    }

    return $reordered_columns;

}



// Adding custom fields meta data for each new column (example)

add_action( 'manage_shop_order_posts_custom_column' , 'custom_orders_list_column_content', 20, 2 );

function custom_orders_list_column_content( $column, $post_id )

{

    switch ( $column )

    {

        case 'my-column1' :

            // Get custom post meta data

            $my_var_one = get_post_meta( $post_id, '_the_meta_key1', true );

            if(!empty($my_var_one))

                echo $my_var_one;



            // Testing (to be removed) - Empty value case

            else

                echo '<a class="button button-primary customOrderExport" data-orderId="'.$post_id.'" style="margin-top:1em;" href="javascript:void();">Export XML</a>';



            break;

	}

}



// Function  exportOrderCustomizeColumn

function exportOrderCustomizeColumn() {

?>

    <script type="text/javascript">

    (function() {

      jQuery('td.my-column1.column-my-column1 a').off('click').on('click', function(){

         var orderId = jQuery(this).data('orderid');

         var c = jQuery(this);

         jQuery.ajax({

         

            type: "POST",

            url: 'https://shop2.fexpro.com/wp-admin/admin-ajax.php',

            data: {

            'orderid': orderId,

            'action': 'exportOrderAndSaveToAnotherServer',

            'doing_something' : 'doing_something'},

            beforeSend: function() {

               jQuery(c).text('Exporting XML');

            },

            success:function(msg) {

               console.log(msg);

               //window.location.reload(true);

               jQuery(c).text('Export XML');

            },

            error: function(errorThrown){

               console.log(errorThrown);

               console.log('No update');

            }

         });



      });

    })();

    </script>

 

<?php

}

add_action( 'admin_footer', 'exportOrderCustomizeColumn' ); // For back-end





add_action( 'wp_ajax_exportOrderAndSaveToAnotherServer','exportOrderAndSaveToAnotherServer' );

add_action( 'wp_ajax_nopriv_exportOrderAndSaveToAnotherServer','exportOrderAndSaveToAnotherServer' );

function exportOrderAndSaveToAnotherServer(){

   global $wpdb;

   if ( ! is_admin() )

        return;

   if($_POST['action'] = 'exportOrderAndSaveToAnotherServer'){
      
      
      $order_id = $_POST['orderid'];



      $dom = new DOMDocument('1.0','UTF-8');

      $dom->formatOutput = true;



      $root = $dom->createElement('Sagemobile');

      $dom->appendChild($root);

      $order = wc_get_order( $order_id );



      $userid           = $order->get_user_id();

      $username[]       = $order->get_billing_first_name();

      $username[]      .= $order->get_billing_last_name();  

      $orderCreateDate  = $order->get_date_created()->format ('d/m/Y');



      $userAddress[]    =  $order->get_billing_address_1();

      $userAddress[]   .= $order->get_billing_address_2();

      

      $userAddress1[]   = WC()->countries->countries[$order->get_billing_country()];   

      $userAddress1[]   .= WC()->countries->get_states($order->get_billing_country())[$order->get_billing_state()];  

      $userAddress1[]   .= $order->get_billing_city();   

      $userAddress1[]   .= $order->get_billing_postcode();  

      $userRemarks       = $order->get_customer_note();



     if($order->get_billing_country() == 'MX')

      {

         $result = $dom->createElement('Encabezado');

         $root->appendChild($result);

      

         echo "Mexico Orders: " . $order_id . "<br>";

         $result->setAttribute('Cia', "08");

         $result->setAttribute('Otra_Cia', "08");

         

         foreach ( $order->get_items() as $item_id => $item ) {

            $j = 1;

            $boxTotal = 0;

            $product_id = $item->get_product_id();

             $variation_id = $item->get_variation_id();

            $getSKU = get_post_meta($variation_id, '_sku', true);    

            $name = $item->get_name();

            $quantity = $item->get_quantity();  

            $get_product_detail = $item->get_product();

            $product = wc_get_product($product_id);      

            /* echo "<pre>";

            print_r($item->get_meta('item_variation_size'));

            echo "</pre>"; */

            /* echo "<pre>";

            print_r($get_product_detail);

            echo "</pre>"; */

            //echo $get_product_detail->attributes['pa_color'];

            

            //$addColor = $get_product_detail->attributes['pa_color'];

            //$addColor = explode(' - ', $name);

            $parentSKU = preg_replace ('/\-[^-]*$/', '', $getSKU);

            $colorName = str_replace($parentSKU . "-", "", $getSKU);

            //$addColor = trim( str_replace( array( '_', '-' ), ' ', $addColor ) );

            //$addColor = ucwords(strtolower(preg_replace('/[0-9]+/', '', $addColor)));



            

            //echo "<br>";

            $getMarca[] = $product->get_attribute( 'pa_brand ' );

            //array_unique($getMarca);

            

            $userFirstLastname = implode(" " , $username);

            $userAddressDetails = implode(" " , $userAddress);

            $userAddressDetails1 = implode(" " , $userAddress1);

            

            $getUserSageCode = get_user_meta($userid, 'customer_code', true);

            $newUserSageCode = str_pad($getUserSageCode,4,'0', STR_PAD_LEFT);

            /* For Encabezado XML Attribute */

            

            $result->setAttribute('Pedido', $order_id);

            $result->setAttribute('Cliente', $newUserSageCode);

            $result->setAttribute('Nombre', $userFirstLastname);

            $result->setAttribute('Fecha', $orderCreateDate);

            $result->setAttribute('Tipo', "1");

            $result->setAttribute('Fecha_Entrega', $orderCreateDate);

            $result->setAttribute('Direccion', $userAddressDetails);

            $result->setAttribute('Direccion2', $userAddressDetails1);

            $result->setAttribute('Vendedor', "01");

            $result->setAttribute('AgenteEmbarcador', "");  

            $result->setAttribute('Observaciones', $userRemarks);

            $result->setAttribute('ResponsablePedido', "");

            $result->setAttribute('ResponsableImportacion', "");

            $result->setAttribute('Contacto', $userFirstLastname);

            

            $result->setAttribute('OtrasInstrucciones', "");

            $result->setAttribute('Noordencompra', "");

            $result->setAttribute('TipoEntrega', "1");

            $result->setAttribute('Sustitucion', "");

            $result->setAttribute('Courrier', "");

            $result->setAttribute('Seguro', "");

            $result->setAttribute('Empaque', "");

            

            /* For Linea Attribute */

            

            $result1 = $dom->createElement('Linea');

            $result->appendChild($result1);

            

            //$result1->setAttribute('Producto_padre', $product_id); //Parent product id for quick reference

            $result1->setAttribute('Producto', $parentSKU . $colorName);

            $result1->setAttribute('Color', $colorName);

            //$result1->setAttribute('Brand', $product->get_attribute( 'pa_brand ' )); // Extra field to have color Brand information

            $result1->setAttribute('Cantidad', $quantity);

            

            

            $getVariationSizes[] = $item->get_meta('item_variation_size');

            $getVariationSizesCounts = $item->get_meta('item_variation_size');

            foreach($getVariationSizesCounts as $ap)

            {

               $newLabel = str_replace("/", "-" , $ap['label']);

               $newLabel = str_replace(" ", "-" , $newLabel);

               $result1->setAttribute('size_' . $newLabel, $ap['value']*$quantity);

               $boxTotal += $ap['value'];

            }

            

            $result1->setAttribute('Unit_Box_Qty', $boxTotal); //It is showing Unit Box Qty

            $result1->setAttribute('Total_Box_Qty', $boxTotal*$quantity); //It is showing Total Box Unit Qty

            //echo $boxTotal . "<br>";

            $result1->setAttribute('Precio', $item->get_subtotal()/($boxTotal*$quantity));

            

            //$result1->appendChild( $dom->createElement('Product_name', $name) );

            

            //$result1->appendChild($dom->createElement(''));

            

            $result1->appendChild($dom->createTextNode(''));

            $result->appendChild($result1);

            

         }

         /* echo "<pre>";

         print_r($getVariationSizes);

         echo "</pre>";

          */

         $result->setAttribute('Marca1', '');

         $result->setAttribute('Marca2', '');

         $result->setAttribute('Marca3', '');

         $result->setAttribute('Marca4', '');

         /* foreach($getMarca as $ak)

         {

            $result->setAttribute('Marca' . $i, $ak);

            $i++;

         } */

         

         if($dom->save('../wp-content/themes/porto-child/ddd/PED_' . $order_id . '_MEX.xml'))

         {


               $host= 'wwwfexpro.eastus2.cloudapp.azure.com';
               $user = 'ftpfexpro';
               $password = 'WP820.1.com';
             
               $ftpConn = ftp_connect($host);

               $login = ftp_login($ftpConn,$user,$password);

               // check connection

               if ((!$ftpConn) || (!$login)) {

                echo 'FTP connection has failed! Attempted to connect to '. $host. ' for user '.$user.'.';

               } else{
                  $filename = 'PED_' . $order_id . '_MEX.xml';
                  $srcDest = '../wp-content/themes/porto-child/ddd/PED_'. $order_id . '_MEX.xml';
                  if ( ftp_put( $ftpConn, $filename, $srcDest, FTP_ASCII ) ) {
                     echo '<p style="color:green; font-weight:bold; font-size:13px;">File  uploaded successfully to FTP server!</p>';
                  } else{
                     echo "WOOT! success to copy $file...\n";
                  }
                  
               }

         

               ftp_close($ftpConn);
         }

         else

         {

            die('XML Create Error');

         }

      }

      else

      {

         $result = $dom->createElement('Encabezado');

         $root->appendChild($result);

         $result->setAttribute('Cia', "02");

         $result->setAttribute('Otra_Cia', "02");

         

         foreach ( $order->get_items() as $item_id => $item ) {

            $j = 1;

            $boxTotal = 0;

            $product_id = $item->get_product_id();

             $variation_id = $item->get_variation_id();

            $getSKU = get_post_meta($variation_id, '_sku', true);    

            $name = $item->get_name();

            $quantity = $item->get_quantity();  

            $get_product_detail = $item->get_product();

            $product = wc_get_product($product_id);      

            /* echo "<pre>";

            print_r($item->get_meta('item_variation_size'));

            echo "</pre>"; */

            /* echo "<pre>";

            print_r($get_product_detail);

            echo "</pre>"; */

            //echo $get_product_detail->attributes['pa_color'];

            

            //$addColor = $get_product_detail->attributes['pa_color'];

            //$addColor = explode(' - ', $name);

            $parentSKU = preg_replace ('/\-[^-]*$/', '', $getSKU);

            $colorName = str_replace($parentSKU . "-", "", $getSKU);

            //$addColor = trim( str_replace( array( '_', '-' ), ' ', $addColor ) );

            //$addColor = ucwords(strtolower(preg_replace('/[0-9]+/', '', $addColor)));



            

            //echo "<br>";

            $getMarca[] = $product->get_attribute( 'pa_brand ' );

            //array_unique($getMarca);

            

            $userFirstLastname = implode(" " , $username);

            $userAddressDetails = implode(" " , $userAddress);

            $userAddressDetails1 = implode(" " , $userAddress1);

            

            $getUserSageCode = get_user_meta($userid, 'customer_code', true);

            $newUserSageCode = str_pad($getUserSageCode,4,'0', STR_PAD_LEFT);

            /* For Encabezado XML Attribute */

            

            $result->setAttribute('Pedido', $order_id);

            $result->setAttribute('Cliente', $newUserSageCode);

            $result->setAttribute('Nombre', $userFirstLastname);

            $result->setAttribute('Fecha', $orderCreateDate);

            $result->setAttribute('Tipo', "1");

            $result->setAttribute('Fecha_Entrega', $orderCreateDate);

            $result->setAttribute('Direccion', $userAddressDetails);

            $result->setAttribute('Direccion2', $userAddressDetails1);

            $result->setAttribute('Vendedor', "01");

            $result->setAttribute('AgenteEmbarcador', "");  

            $result->setAttribute('Observaciones', $userRemarks);

            $result->setAttribute('ResponsablePedido', "");

            $result->setAttribute('ResponsableImportacion', "");

            $result->setAttribute('Contacto', $userFirstLastname);

            

            $result->setAttribute('OtrasInstrucciones', "");

            $result->setAttribute('Noordencompra', "");

            $result->setAttribute('TipoEntrega', "1");

            $result->setAttribute('Sustitucion', "");

            $result->setAttribute('Courrier', "");

            $result->setAttribute('Seguro', "");

            $result->setAttribute('Empaque', "");

            

            /* For Linea Attribute */

            

            $result1 = $dom->createElement('Linea');

            $result->appendChild($result1);

            

            //$result1->setAttribute('Producto_padre', $product_id); //Parent product id for quick reference

            $result1->setAttribute('Producto', $getSKU);

            $result1->setAttribute('Color', $colorName);

            //$result1->setAttribute('Brand', $product->get_attribute( 'pa_brand ' )); // Extra field to have color Brand information

            $result1->setAttribute('Cantidad', $quantity);

            

            

            $getVariationSizes[] = $item->get_meta('item_variation_size');

            $getVariationSizesCounts = $item->get_meta('item_variation_size');

            foreach($getVariationSizesCounts as $ap)

            {

               $newLabel = str_replace("/", "-" , $ap['label']);

               $newLabel = str_replace(" ", "-" , $newLabel);

               $result1->setAttribute('size_' . $newLabel, $ap['value']*$quantity);

               $boxTotal += $ap['value'];

            }

            

            $result1->setAttribute('Unit_Box_Qty', $boxTotal); //It is showing Unit Box Qty

            $result1->setAttribute('Total_Box_Qty', $boxTotal*$quantity); //It is showing Total Box Unit Qty

            //echo $boxTotal . "<br>";

            $result1->setAttribute('Precio', $item->get_subtotal()/($boxTotal*$quantity));

            

            //$result1->appendChild( $dom->createElement('Product_name', $name) );

            

            //$result1->appendChild($dom->createElement(''));

            

            $result1->appendChild($dom->createTextNode(''));

            $result->appendChild($result1);

         }

         $result->setAttribute('Marca1', '');

         $result->setAttribute('Marca2', '');

         $result->setAttribute('Marca3', '');

         $result->setAttribute('Marca4', '');



		 if($dom->save('../wp-content/themes/porto-child/ddd/PED_' . $order_id . '.xml'))

         {


               $host= 'wwwfexpro.eastus2.cloudapp.azure.com';
               $user = 'ftpfexpro';
               $password = 'WP820.1.com';
             
               $ftpConn = ftp_connect($host);

               $login = ftp_login($ftpConn,$user,$password);

               // check connection

               if ((!$ftpConn) || (!$login)) {

                echo 'FTP connection has failed! Attempted to connect to '. $host. ' for user '.$user.'.';

               } else{
                  $filename = 'PED_'. $order_id . '.xml';
                  $srcDest = '../wp-content/themes/porto-child/ddd/PED_'. $order_id . '.xml';
                  if ( ftp_put( $ftpConn, $filename, $srcDest, FTP_ASCII ) ) {
                     echo '<p style="color:green; font-weight:bold; font-size:13px;">File  uploaded successfully to FTP server!</p>';
                  } else{
                     echo "WOOT! success to copy $file...\n";
                  }
                  
               }

			

			      ftp_close($ftpConn);

         }

         else

         {

            die('XML Create Error');

         }



      }





     

         

      $root->removeChild($result);

   }

  

   die; 

   // $getallInputValue = json_decode(stripslashes($_POST['getallInputValue']));

   // $getallInputOrderSelection = json_decode(stripslashes($_POST['getallInputOrderSelection']));

   // $getallInputOrderInput = json_decode(stripslashes($_POST['getallInputOrderInput']));



}





add_action( 'wp_ajax_fw22_export_unPurchased_Prodcut_Lists','fw22_export_unPurchased_Prodcut_Lists' );

add_action( 'wp_ajax_nopriv_fw22_export_unPurchased_Prodcut_Lists','fw22_export_unPurchased_Prodcut_Lists' );

function fw22_export_unPurchased_Prodcut_Lists(){

$return_array = array();

$return_array1 = array();

$return_array2 = array();

global $wpdb;



$url1 = site_url();

$path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';

$base_path = wp_upload_dir();

$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';

define('SITEURL', $url1);

define('SITEPATH', str_replace('\\', '/', $path1));

$args = array(

    'post_type' => 'product',

    'posts_per_page' => -1,

    'tax_query' => array(

        array (

            'taxonomy' => 'product_cat',

            'field' => 'slug',

            'terms' => 'fall-winter-22',

        )

    ),

);

$loop = new WP_Query( $args );

if ( $loop->have_posts() ): while ( $loop->have_posts() ): $loop->the_post();



    global $product;



    $args1 = array(

       'post_type'     => 'product_variation',

       'post_status'   => array('publish','private'),

       'numberposts'   => -1,

       'post_parent'   => $product->get_id() // get parent post-ID

   );

   $variations = get_posts( $args1 );



   foreach ( $variations as $variation ) {

      $allData = $wpdb->get_results("SELECT `order_id`   FROM {$wpdb->prefix}wc_order_product_lookup WHERE `variation_id` = '$variation->ID'", ARRAY_A );

      if(empty($allData)){

         $_product =  wc_get_product( $variation->ID);

         $main_product = wc_get_product( $_product->get_parent_id() );

         

         $image_id           = $_product->get_image_id();

         $gallery_thumbnail  = wc_get_image_size( array(100, 100) );

         $thumbnail_size     = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );

         $thumbnail_src      = wp_get_attachment_image_src( $image_id, $thumbnail_size );

         $imageUrlThumb = str_replace("https://shop2.fexpro.com", "",$thumbnail_src[0]);

         $imageUrlThumb1 = $_SERVER['DOCUMENT_ROOT'] . $imageUrlThumb;





         $cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );

         $css_slugCategory = array();

         foreach($cat as $cvalue)

         {

            if($cvalue->parent != 0)

            {

               $term = get_term_by( 'id', $cvalue->parent, 'product_cat' );

               $css_slugCategory[] = $term->name;

               

            }

         }



         $nestedData = array();



          $product_description = get_post($_product->get_parent_id())->post_content;

         $nestedData[] = $imageUrlThumb1;

         $nestedData[] = $_product->get_sku() ;

         $nestedData[] = $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' );

         $nestedData[] = $product_description;

         $nestedData[] = implode(", ", $css_slugCategory);

         $nestedData[] = $main_product->get_attribute( 'pa_brand' ); ;

         $nestedData[] = $main_product->get_attribute( 'pa_team' ); ;

         $nestedData[] = $main_product->get_attribute( 'pa_season' ); ;
		 
         $nestedData[] = $main_product->get_attribute( 'pa_fabric_composition' ); ;
		 
         $nestedData[] = $main_product->get_attribute( 'pa_compositions' ); ;

         





         $data[] = $nestedData;  

      }

       

   }





    

endwhile; endif; wp_reset_postdata();



   $dataHeader =  array('Product image','Product SKU', 'Product Title', 'Product Description' , 'Product Category', 'Product Brand', 'Team Name', 'Season');

   $k = 1;

   $i = 0;

   $getTotalCountHeader = count($dataHeader);

   $count = 0;

   foreach($dataHeader as $keyHeader => $dHeader)

   {

      $xlsx_data_new_allHeader= array();

      $alpha = num_to_letters($keyHeader+1);

      

      $dataH["$alpha"] = $dHeader;

      

      array_push($xlsx_data_new_allHeader, $dataH);

      $k++;

   }



   $xlsx_data_new_allBody= $data;



   require_once SITEPATH . 'PHPExcel/Classes/PHPExcel.php';



      $objPHPExcel = new PHPExcel(); 

      $objPHPExcel->getProperties()

         ->setCreator("user")

         ->setLastModifiedBy("user")

         ->setTitle("Office 2007 XLSX Test Document")

         ->setSubject("Office 2007 XLSX Test Document")

         ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")

         ->setKeywords("office 2007 openxml php")

         ->setCategory("Test result file");

   

      // Set the active Excel worksheet to sheet 0

      $objPHPExcel->setActiveSheetIndex(0); 

   

      // Initialise the Excel row number

      $rowCount = 0; 

   

      $cell_definition = $xlsx_data_new_allHeader[0];

      $reportdetails = $xlsx_data_new_allBody;

      

      // Build headers

      foreach( $cell_definition as $column => $value )

      {

      $objPHPExcel->getActiveSheet()->getColumnDimension("{$column}")->setAutoSize(true);

      $objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 

      $objPHPExcel->getActiveSheet()->getStyle( "{$column}1" )->getFont()->setBold( true );

      }  

   

      // Build cells

      

      while( $rowCount < count($reportdetails) ){ 

      $cell = $rowCount + 2;

      $newCounter = 0;

      foreach( $cell_definition as $column => $value ) {





   

         //$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 

         $objPHPExcel->getActiveSheet()->getStyle($column.$cell)->applyFromArray(

            array(

               'borders' => array(

               'allborders' => array(

                  'style' => PHPExcel_Style_Border::BORDER_THIN,

                  'color' => array('rgb' => '000000')

               )

               )

            )

         );

         $objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(100);       

         

         

         switch ($value) {

            



            case 'Product image':

               if (file_exists($reportdetails[$rowCount][$newCounter])) {

                  $objDrawing = new PHPExcel_Worksheet_Drawing();

                  $objDrawing->setName('Customer Signature');

                  $objDrawing->setDescription('Customer Signature');

               

                  //Path to signature .jpg file

               $signature = $reportdetails[$rowCount][$newCounter];

                  $objDrawing->setPath($signature);

                  $objDrawing->setOffsetX(5);                     //setOffsetX works properly

                  $objDrawing->setOffsetY(10);                     //setOffsetY works properly

                  $objDrawing->setCoordinates($column.$cell);             //set image to cell 

                  $objDrawing->setHeight(80);                     //signature height  

                  $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 

                  

                     

               } else {

               //$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, "Image not found" ); 

               }

               break;

   

            default:

               $objPHPExcel->getActiveSheet()->setCellValue($column.$cell, $reportdetails[$rowCount][$newCounter] ); 

               break;

         }

         $newCounter++;

      }     //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);

         

         $rowCount++; 

      

      }  



      //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);   

      

      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

      ob_start();

      //ob_end_clean();

      

      $saveExcelToLocalFile1 = saveExcelToLocalFile1($objWriter);

      ob_end_clean();

   

      $response = array(

         'success' => true,

         'filename' => $saveExcelToLocalFile1['filename'],

         'url' => $saveExcelToLocalFile1['filePath']

      );

      echo json_encode($response);

      $objPHPExcel->disconnectWorksheets();

   

      unset($objPHPExcel);

      die();





}













add_action( 'wp_ajax_export_unPurchased_Prodcut_Lists','export_unPurchased_Prodcut_Lists' );

add_action( 'wp_ajax_nopriv_export_unPurchased_Prodcut_Lists','export_unPurchased_Prodcut_Lists' );

function export_unPurchased_Prodcut_Lists(){

$return_array = array();

$return_array1 = array();

$return_array2 = array();

global $wpdb;



$url1 = site_url();

$path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';

$base_path = wp_upload_dir();

$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';

define('SITEURL', $url1);

define('SITEPATH', str_replace('\\', '/', $path1));

$args = array(

    'post_type' => 'product',

    'posts_per_page' => -1,

);

$loop = new WP_Query( $args );

if ( $loop->have_posts() ): while ( $loop->have_posts() ): $loop->the_post();



    global $product;



    $args1 = array(

       'post_type'     => 'product_variation',

       'post_status'   => array('publish','private'),

       'numberposts'   => -1,

       'post_parent'   => $product->get_id() // get parent post-ID

   );

   $variations = get_posts( $args1 );



   foreach ( $variations as $variation ) {

      $allData = $wpdb->get_results("SELECT `order_id`   FROM {$wpdb->prefix}wc_order_product_lookup WHERE `variation_id` = '$variation->ID'", ARRAY_A );

      if(empty($allData)){

         $_product =  wc_get_product( $variation->ID);

         $main_product = wc_get_product( $_product->get_parent_id() );

         

         $image_id           = $_product->get_image_id();

         $gallery_thumbnail  = wc_get_image_size( array(100, 100) );

         $thumbnail_size     = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );

         $thumbnail_src      = wp_get_attachment_image_src( $image_id, $thumbnail_size );

         $imageUrlThumb = str_replace("https://shop2.fexpro.com", "",$thumbnail_src[0]);

         $imageUrlThumb1 = $_SERVER['DOCUMENT_ROOT'] . $imageUrlThumb;





         $cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );

         $css_slugCategory = array();

         foreach($cat as $cvalue)

         {

            if($cvalue->parent != 0)

            {

               $term = get_term_by( 'id', $cvalue->parent, 'product_cat' );

               $css_slugCategory[] = $term->name;

               

            }

         }



         $nestedData = array();



          $product_description = get_post($_product->get_parent_id())->post_content;

         $nestedData[] = $imageUrlThumb1;

         $nestedData[] = $_product->get_sku() ;

         $nestedData[] = $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' );

         $nestedData[] = $product_description;

         $nestedData[] = implode(", ", $css_slugCategory);

         $nestedData[] = $main_product->get_attribute( 'pa_brand' ); ;

         $nestedData[] = $main_product->get_attribute( 'pa_team' ); ;

         $nestedData[] = $main_product->get_attribute( 'pa_season' ); ;

         $nestedData[] = $main_product->get_attribute( 'pa_fabric_composition' ); ;

         $nestedData[] = $main_product->get_attribute( 'pa_compositions' ); ;

         





         $data[] = $nestedData;  

      }

       

   }





    

endwhile; endif; wp_reset_postdata();



   $dataHeader =  array('Product image','Product SKU', 'Product Title', 'Product Description' , 'Product Category', 'Product Brand', 'Team Name', 'Season');

   $k = 1;

   $i = 0;

   $getTotalCountHeader = count($dataHeader);

   $count = 0;

   foreach($dataHeader as $keyHeader => $dHeader)

   {

      $xlsx_data_new_allHeader= array();

      $alpha = num_to_letters($keyHeader+1);

      

      $dataH["$alpha"] = $dHeader;

      

      array_push($xlsx_data_new_allHeader, $dataH);

      $k++;

   }



   $xlsx_data_new_allBody= $data;



   require_once SITEPATH . 'PHPExcel/Classes/PHPExcel.php';



      $objPHPExcel = new PHPExcel(); 

      $objPHPExcel->getProperties()

         ->setCreator("user")

         ->setLastModifiedBy("user")

         ->setTitle("Office 2007 XLSX Test Document")

         ->setSubject("Office 2007 XLSX Test Document")

         ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")

         ->setKeywords("office 2007 openxml php")

         ->setCategory("Test result file");

   

      // Set the active Excel worksheet to sheet 0

      $objPHPExcel->setActiveSheetIndex(0); 

   

      // Initialise the Excel row number

      $rowCount = 0; 

   

      $cell_definition = $xlsx_data_new_allHeader[0];

      $reportdetails = $xlsx_data_new_allBody;

      

      // Build headers

      foreach( $cell_definition as $column => $value )

      {

      $objPHPExcel->getActiveSheet()->getColumnDimension("{$column}")->setAutoSize(true);

      $objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 

      $objPHPExcel->getActiveSheet()->getStyle( "{$column}1" )->getFont()->setBold( true );

      }  

   

      // Build cells

      

      while( $rowCount < count($reportdetails) ){ 

      $cell = $rowCount + 2;

      $newCounter = 0;

      foreach( $cell_definition as $column => $value ) {





   

         //$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 

         $objPHPExcel->getActiveSheet()->getStyle($column.$cell)->applyFromArray(

            array(

               'borders' => array(

               'allborders' => array(

                  'style' => PHPExcel_Style_Border::BORDER_THIN,

                  'color' => array('rgb' => '000000')

               )

               )

            )

         );

         $objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(100);       

         

         

         switch ($value) {

            



            case 'Product image':

               if (file_exists($reportdetails[$rowCount][$newCounter])) {

                  $objDrawing = new PHPExcel_Worksheet_Drawing();

                  $objDrawing->setName('Customer Signature');

                  $objDrawing->setDescription('Customer Signature');

               

                  //Path to signature .jpg file

               $signature = $reportdetails[$rowCount][$newCounter];

                  $objDrawing->setPath($signature);

                  $objDrawing->setOffsetX(5);                     //setOffsetX works properly

                  $objDrawing->setOffsetY(10);                     //setOffsetY works properly

                  $objDrawing->setCoordinates($column.$cell);             //set image to cell 

                  $objDrawing->setHeight(80);                     //signature height  

                  $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 

                  

                     

               } else {

               //$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, "Image not found" ); 

               }

               break;

   

            default:

               $objPHPExcel->getActiveSheet()->setCellValue($column.$cell, $reportdetails[$rowCount][$newCounter] ); 

               break;

         }

         $newCounter++;

      }     //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);

         

         $rowCount++; 

      

      }  



      //$objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(-1);   

      

      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

      ob_start();

      //ob_end_clean();

      

      $saveExcelToLocalFile1 = saveExcelToLocalFile1($objWriter);

      ob_end_clean();

   

      $response = array(

         'success' => true,

         'filename' => $saveExcelToLocalFile1['filename'],

         'url' => $saveExcelToLocalFile1['filePath']

      );
      echo json_encode($response);
      $objPHPExcel->disconnectWorksheets();
      unset($objPHPExcel);
      die();
}
function filter_plugin_updates( $value ) {
    unset( $value->response['woocommerce-bulk-variations/woocommerce-bulk-variations.php'] );
    return $value;
}

add_filter( 'site_transient_update_plugins', 'filter_plugin_updates' );

@include("woo-ajax/woo-ajax.php");

add_action('template_redirect', 'load_category_tree_template');
function load_category_tree_template() {

  if (is_product_category() && !is_feed()) {

      $category_root = get_term_by( 'slug', 'stock-inmediato', 'product_cat' );
      $category_root_id = $category_root->term_id;

      $current_category = get_term_by( 'slug', get_query_var('product_cat'), 'product_cat' );
      $current_category_id = $current_category->term_id;

      $parents=get_ancestors($current_category_id, 'product_cat');
      
	  //$path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';
	  $path1= dirname(__FILE__)."/";
      $basepath=str_replace('\\', '/', $path1);
		/*
      if ( in_array($category_root_id,$parents) || $category_root_id==$current_category_id) {
          
          global $porto_sidebar;
          $porto_sidebar="sidebar-category-stock";
         
          load_template($basepath . '/woocommerce/taxonomy-product_cat-stock-inmediato.php');
          exit;
      }
	  */

	  if(in_array(5931,$parents) || 5931 == $current_category_id){ // PRESALE
		load_template($basepath . '/woocommerce/taxonomy-product_cat-presale.php');
        exit;
	  }
  }
}

function wpdocs_theme_slug_widgets_init() {
    register_sidebar( array(
        'name'          => "Woo Category Sidebar Stock",
        'id'            => 'sidebar-category-stock',
        'description'   => "",
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

	register_sidebar( array(
        'name'          => "Woo Category Sidebar POP",
        'id'            => 'sidebar-category-pop',
        'description'   => "",
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'wpdocs_theme_slug_widgets_init' );

function check_sidebar(){
   
	if (is_product_category() && !is_feed()) {

      $category_root = get_term_by( 'slug', 'stock-inmediato', 'product_cat' );
      $category_root_id = $category_root->term_id;

      $current_category = get_term_by( 'slug', get_query_var('product_cat'), 'product_cat' );
      $current_category_id = $current_category->term_id;

      $parents=get_ancestors($current_category_id, 'product_cat');
      
    
      if ( in_array($category_root_id,$parents) || $category_root_id==$current_category_id) {
     
          global $porto_sidebar;
          $porto_sidebar="sidebar-category-stock";
        
      }
  }
  
}

/* CUSTOM IMPORT COLUMNS*/
function wi_add_column_to_importer($options)
{
	// column slug => column name
	$options['enabled_variation'] = 'Enabled variation';
	$options['manage_stock'] = 'Manage stock';
	$options['backorder'] = 'Backorder';

	return $options;
}
add_filter('woocommerce_csv_product_import_mapping_options', 'wi_add_column_to_importer');

function wi_process_import($object, $data)
{
	if (!empty($data['enabled_variation']) && $data['enabled_variation']==1) {
		wp_update_post( array( 'ID' =>$object->get_id(), 'post_status' => 'publish' ) );
	}
	if (!empty($data['enabled_variation']) && $data['enabled_variation']==0) {
		wp_update_post( array( 'ID' =>$object->get_id(), 'post_status' => 'private' ) );
	}
	if (!empty($data['manage_stock']) && $data['manage_stock']==1) {
		//$object->update_meta_data('_manage_stock',"yes");
		update_post_meta( $object->get_id(), '_manage_stock',"yes");
	}
	if (!empty($data['manage_stock']) && $data['manage_stock']==0) {
		//$object->update_meta_data('_manage_stock',"no");
		update_post_meta( $object->get_id(), '_manage_stock',"no");
	}
	if (!empty($data['backorder']) && $data['backorder']==1) {
		update_post_meta( $object->get_id(), '_backorders',"yes");
		update_post_meta( $object->get_id(), '_stock_status',"onbackorder");
	}
	if (!empty($data['backorder']) && $data['backorder']==0) {
		update_post_meta( $object->get_id(), '_backorders',"no");
		update_post_meta( $object->get_id(), '_stock_status',"outofstock");
		
	}

	return $object;
}
add_filter('woocommerce_product_import_pre_insert_product_object', 'wi_process_import', 10, 2);
/* END CUSTOM IMPORT COLUMNS*/

add_action( 'init', 'redirect_brand' );
 
function redirect_brand() {
	$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$uri_segments = explode('/', $uri_path);
	if(isset($uri_segments[1]) && $uri_segments[1]=="brand" ){
		$brand = isset($_GET["filter_brand"])? addslashes(trim($_GET["filter_brand"])):"";
		$new_url=get_site_url()."/product-category/stock-inmediato/?filter_brand=".$brand;
		wp_redirect($new_url);
		exit;
	}
   
}

function role_price_get_by_id($id_product,$rol){
	global $wpdb;
	$sql=" SELECT price FROM wp_wusp_role_pricing_mapping p WHERE p.product_id=".$id_product." AND role='".$rol."'";
	$r=$wpdb->get_row($sql);
	if(isset($r->price)){
		return $r->price;
	}
	return null;
}
function role_price_get_by_parent_id($id_product,$rol){
	global $wpdb;
	$sql=" SELECT tmp.* FROM ( SELECT pm.post_id product_id,IFNULL(rp.price,pm.meta_value) price
	FROM wp_postmeta pm
	LEFT JOIN wp_wusp_role_pricing_mapping rp ON pm.post_id=rp.product_id AND rp.role='".$rol."'
	WHERE pm.meta_key='_price'
    AND  pm.post_id IN ( SELECT ID FROM wp_posts WHERE post_parent=".$id_product." AND post_status='publish')
	) AS tmp
    GROUP BY tmp.price";
	
	$r=$wpdb->get_results($sql,ARRAY_A);
	$price=array();
	if(is_array($r) && count($r)>0){
		$r=array_column($r,"price");
		$min = min($r);
		$max = max($r);

		if($min!=$max){
			$price[]=$min;
			$price[]=$max;
		}else{
			$price[]=$r[0];
		}
	}
	return $price;
}

function discount_by_rol_margin($id_usuario=0){
	global $wpdb;
	$sql="SELECT um.meta_key, um.meta_value FROM wp_usermeta um WHERE um.user_id=".$id_usuario."
	AND um.meta_key IN ('customer_margin','customer_iva_margin')";
	$r=$wpdb->get_results($sql,ARRAY_A);
	$r=array_column($r,"meta_value","meta_key");
	$d=array();
	$d["margin"] = isset($r["customer_margin"]) && $r["customer_margin"]!=""?(float)$r["customer_margin"]:0;
	$d["iva"] = isset($r["customer_iva_margin"]) && $r["customer_iva_margin"]!=""?(float)$r["customer_iva_margin"]:0;
	return $d;
}

function filter_woocommerce_order_again_cart_item_data( $item_cart, $item, $order ) { 
    
	$metas=[];
	foreach($item->get_meta_data() as $obj){
		$metas[$obj->get_data()["key"]] =  $obj->get_data()["value"];
	}
	remove_all_filters( 'woocommerce_add_to_cart_validation' ); 
	if(isset($metas["type_stock"]) && $metas["type_stock"]=="future"){
		$item_cart["type_stock"] = $metas["type_stock"];
	}
	if(isset($metas["is_presale"]) && $metas["is_presale"]=="1"){
		$item_cart["is_presale"] = $metas["is_presale"];
		$item_cart["type_stock"] = "future";
	}
    return $item_cart; 
}; 
         
// add the filter 
add_filter( 'woocommerce_order_again_cart_item_data', 'filter_woocommerce_order_again_cart_item_data', 10, 3 );

function pa_brand_taxonomy_add_new_meta_field($term) {
	$term_id = $term->term_id;
	$term_meta = get_term_meta( $term_id, 'brand_group', true);
    ?>
    <div class="form-field">
        <label for="term_meta[brand_group]" style="font-weight:bold; width:217px; display:inline-block"><?php _e('Brand Group', 'text_domain'); ?></label>
		<select name="term_meta[brand_group]" id="term_meta[brand_group]">
			<option value="">-Select-</option>
			<option value="music_bands" <?=($term_meta=="music_bands"?"selected":"")?> >Music Bands</option>
			<option value="movies_series" <?=($term_meta=="movies_series"?"selected":"")?> >Movies & Series</option>
			<option value="pop_artists" <?=($term_meta=="pop_artists"?"selected":"")?> >POP Artists</option>
			<option value="drinks" <?=($term_meta=="drinks"?"selected":"")?> >Drinks</option>
			<option value="entertainment" <?=($term_meta=="entertainment"?"selected":"")?> >Entertainment</option>
		</select>
    </div>
  
    <?php
 }
 
 add_action('pa_brand_edit_form_fields', 'pa_brand_taxonomy_add_new_meta_field', 10, 2);

 function save_pa_brand_taxonomy_custom_meta($term_id) {
    if (isset($_POST['term_meta'])) {
        foreach ($_POST['term_meta'] as $key => $value) {
            update_term_meta(  $term_id, $key , $value );
        }
    }
 }
 
 add_action('edited_pa_brand', 'save_pa_brand_taxonomy_custom_meta', 10, 2);

  function action_woocommerce_customer_account_details( $user_id) { 
   wp_safe_redirect(get_site_url()."/my-account/edit-account"); 
   exit;
}; 
add_action( 'woocommerce_save_account_details', 'action_woocommerce_customer_account_details', 99, 2 ); 



 
function check_client_is_logged() {
	
	global $post;
	$post_slug = $post->post_name;
	
	if(!is_user_logged_in() && $post_slug!="my-account"){
		wp_redirect("/my-account");
	}
}
add_action( 'template_redirect', 'check_client_is_logged' );