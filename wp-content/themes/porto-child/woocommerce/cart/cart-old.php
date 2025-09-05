<?php
	/**
	 * Cart Page
	 *
	 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
	 *
	 * HOWEVER, on occasion WooCommerce will need to update template files and you
	 * (the theme developer) will need to copy the new files to your theme to
	 * maintain compatibility. We try to do this as little as possible, but it does
	 * happen. When this occurs the version of the template file will be bumped and
	 * the readme will list any important changes.
	 *
	 * @see     https://docs.woocommerce.com/document/template-structure/
	 * @package WooCommerce\Templates
	 * @version 3.8.0
	 */
	if(isset($_GET["newlayout"])){
		include("cart-new.php");
		return;
	}
	defined( 'ABSPATH' ) || exit;
	prefix_update_existing_cart_item_meta();
	$return_array1 = array();
	global $wpdb;
	do_action( 'woocommerce_before_cart' ); ?>


	<style>
		.inline-b { display: block !important; justify-content: space-between; align-items: center; float: right;}
		#main {    background-color: #f1f1f17a;}
		.shop_table a { color: #fff; text-decoration: none;}
		.brandColumns {background: #fff;margin: 12px 0px;padding: 15px; box-shadow: 0px 0px 6px 1px rgb(0 0 0 / 35%); -webkit-box-shadow: 0px 0px 6px 1px rgb(0 0 0 / 35%); -moz-box-shadow: 0px 0px 6px 1px rgba(0,0,0,0.35); }
		#myCartFilter .product-remove { width: 50px; padding: 0; height: 68px; }
		#myCartFilter .product-remove img { height: 30px; width: 30px; opacity: 0.5; margin-top: 17px; }

		span.brandTitle {font-size: 22px;line-height: 33px;color: #000000 !important;font-weight: 700;}
		div#catBlock { margin: 10px; border-radius: 14px; padding: 19px;background:#fff; width: 95%;}
		span.catTitle {font-size: 15px; color: #fff;  background: #e31e1a; padding: 5px 10px; border-radius: 5px; font-weight: 700;}
		/* div#catBlock td.product-remove a { background: #000000;  padding: 9px; color: #fff; border-radius: 7px;} */
		span.dataSummery {text-align: right;display: block; margin-right: 15px; font-size: 20px; font-weight: 900; margin-top: 16px;}
		thead {float: right;width: 100%;}
		tr.woocommerce-cart-form__cart-item.cart_item td.product-remove, th.product-remove {width: 10%;text-align: center;float: right;
	    margin-right: -45px !important;     margin-top: 50px !important;}
		tr.woocommerce-cart-form__cart-item.cart_item td.product-thumbnail, th.product-thumbnail {width: 10%;text-align: center;}
		tr.woocommerce-cart-form__cart-item.cart_item td.product-name, th.product-name {width: 60%;}
		tr.woocommerce-cart-form__cart-item.cart_item td.product-price, th.product-price {width: 10%;text-align: center;padding-top: 45px !important;}
		tr.woocommerce-cart-form__cart-item.cart_item td.product-quantity, th.product-quantity {width: 10%;text-align: center;padding-top: 50px !important;}
		tr.woocommerce-cart-form__cart-item.cart_item td.product-subtotal, th.product-subtotal {width: 10%;text-align: center;padding-top: 45px !important;}
		tr.woocommerce-cart-form__cart-item.cart_item td { font-size: 16px;vertical-align: middle; color: #000;}
		span.dataSummery small { color: #000; padding: 6px 15px;  border-radius: 5px; font-weight: 900;}
		span.catTitle {display: none; visibility: hidden;}
		td.product-price label, td.product-quantity label, td.product-subtotal label {width: 100%;margin-bottom: 20px;    font-weight: 700;}
		.size-guide.no-hide-it span:nth-of-type(1) {line-height: 35px; background: #dadada;}
		.cart-sizes-attribute .size-guide .inner-size:first-child { border-left: solid 2px #000; }
		.inner-size span, .inner-size div { display: block; width: 100%; border-bottom: solid 2px #000; border-right: 2px solid #000; color: #000; padding: 5px 10px ; }
		.cart-sizes-attribute .size-guide .inner-size { border: solid 2 px #000; border-right: 0; border-left: 0; }
		:not(.woocommerce-order-received) .cart-sizes-attribute.red .size-guide.no-hide-it .inner-size span:last-child { background: #efefef; color: #000; }
		.cart-sizes-attribute .size-guide .inner-size { border: solid 2px #000; border-right: 0; border-left: 0; }
		.quantity.buttons_added {margin-top: -5px !important; }
		thead {display: none;}
		span.brandTitle::after, .woocommerce-cart .cart-collaterals .cart_totals h2::after { content: ''; border-bottom: 3px solid #dadada; width: 100% !important;	display: block;}
		.shop_table thead tr, .shop_table tr:not(:last-child){  border-bottom: none;}
		#myCartFilter tr .actions { /* position: absolute; right: 0; width: 19%;display: flex;justify-content: space-between; */ padding: 20px 10px; position: absolute; right: 9px; width: 19%; /* display: flex; */ justify-content: space-between; box-shadow: 0px 0px 6px 1px rgb(0 0 0 / 35%); -webkit-box-shadow: 0px 0px 6px 1px rgb(0 0 0 / 35%); -moz-box-shadow: 0px 0px 6px 1px rgba(0,0,0,0.35); background: #fff; top: 18px; visibility: hidden; opacity: 0; }
		/* .woocommerce-cart .cart-collaterals {width: 18%;float: right; padding-top: 80px;} */
		.woocommerce-cart .cart-collaterals, .woocommerce-cart .customSidebarButton { width: 19%; float: right; margin-top: 15px; box-shadow: 0px 0px 6px 1px rgb(0 0 0 / 35%); -webkit-box-shadow: 0px 0px 6px 1px rgb(0 0 0 / 35%); -moz-box-shadow: 0px 0px 6px 1px rgba(0,0,0,0.35); background: #fff; padding: 5px 15px; }
		.coupon + button[type="submit"][disabled] { background: transparent; cursor: not-allowed; border-color:  transparent; color: #000; padding-left: 40px; position: relative; }
		.coupon + button { background: transparent; cursor: not-allowed; border-color:  transparent; color: #000; padding-left: 40px; position: relative; }
		.coupon + button:hover { background: transparent; cursor: not-allowed; border-color:  transparent; color: #000; padding-left: 40px; position: relative; }
		.coupon + button:before { content: ''; background-image: url(/wp-content/uploads/2021/11/newicon.png); background-repeat: no-repeat; background-position: center; height: 20px; width: 30px; position: absolute; left: 0; z-index: 99; background-size: contain; top: 10px; }
		.actions .coupon a, { background: transparent; border-color: transparent; color: #000; padding-left: 40px; position: relative; }
		.coupon + button[type="submit"][disabled]:before { content: ''; background-image: url(/wp-content/uploads/2021/11/newicon.png); background-repeat: no-repeat; background-position: top; height: 20px; width: 20px; position: absolute; left: 0; z-index: 99; background-size: contain; top: 8px; }
		.actions .coupon a:before { content: ''; background-image: url(/wp-content/uploads/2021/11/newicon1.png); background-repeat: no-repeat; background-position: center; height: 20px; width: 20px; position: absolute; left: 0; z-index: 99; background-size: contain; top: 8px; }
		.woocommerce-cart .cart-collaterals .shop_table .custom_units_items_total th , .woocommerce-cart .cart-collaterals .shop_table .custom_units_items_total td { border: none !important; background-color: #f2f2f2; color:#000; }

		.woocommerce-cart .cart-collaterals .shop_table .custom_units th , .woocommerce-cart .cart-collaterals .shop_table .custom_units td { border: none !important; background-color: #ededed; }
		.woocommerce-cart .cart-collaterals .cart_totals h2 { max-width: initial; margin-right: 0; margin-left: auto; margin-bottom: 15px; text-transform: uppercase; }
		.woocommerce-cart .cart-collaterals .shop_table .custom_units_items_total td , .woocommerce-cart .cart-collaterals .shop_table .custom_units td { text-align: right; }
		.cart-collaterals .wc-proceed-to-checkout { text-align: right; margin-bottom:0px }
		.cart-collaterals .inline-b { width:100%; text-align:right; }
		.cart-collaterals .inline-b a.export_xlsx { text-align:right; background: #70ad46; position:relative; padding: 13px; font-size: 16px; font-weight: 700; }
		.cart-collaterals .inline-b a.export_xlsx:before { content: ''; background-image: url(/wp-content/uploads/2021/11/newicon3.png); position: absolute; width: 50px; height: 50px; left: 6px; background-repeat: no-repeat; background-position: center; background-size: contain; top: 2px; }
		.cart-collaterals .checkout-button { width: 100%; position: relative; text-align: right; background: #e31e1a; padding: 13px; font-size: 16px; font-weight: 700; }
		.cart-collaterals .checkout-button:before { content: ''; background-image: url(/wp-content/uploads/2021/11/newicon2.png); position: absolute; width: 50px; height: 50px; left: 6px; top: 1px; background-repeat: no-repeat; background-position: center; background-size: contain; }
		a.seePreviousPurchase { padding: 13px; font-size: 16px; font-weight: 700; width: 100%; }
			#myCartFilter tr .actions .coupon { order: 2;  margin-right: 0px;}
		
		.customSidebarButton a { background: transparent; border-color: transparent; color: #000; padding-left: 40px; position: relative; text-decoration: none !important; }
		.customSidebarButton .updateCartCustom:before { content: ''; background-image: url(/wp-content/uploads/2021/11/newicon.png); background-repeat: no-repeat; background-position: center; height: 20px; width: 20px; position: absolute; left: 0; z-index: 99; background-size: contain; top: 0px; }
		.customSidebarButton .emptyButton:before { content: ''; background-image: url(/wp-content/uploads/2021/11/newicon1.png); background-repeat: no-repeat; background-position: center; height: 20px; width: 30px; position: absolute; left: 0; z-index: 99; background-size: contain; top: 0px; }
		.customSidebarButton { display: flex; justify-content: space-between; padding: 22px 15px  !important; }
		span.span1 { margin-right: 45px;color: #a29e9e; text-transform: uppercase;  font-weight: 700;}
		span.span2 { border-top: 3px solid #dadada; border-bottom: 3px solid #dadada; padding: 5px 0px; padding-left: 20px; }
		span.dataSummery .small1 {margin-right: 120px;}
		span.dataSummery .small1 span.span4 {  padding-left: 25px;}
		@media (min-width:2120px)
		{
			.customSidebarButton { display: flex; justify-content: space-between; padding: 36px 15px !important; }
			.customSidebarButton a { font-size:22px; }		
			.customSidebarButton .updateCartCustom:before { content: ''; background-image: url(/wp-content/uploads/2021/11/newicon.png); background-repeat: no-repeat; background-position: center; height: 30px; width: 30px; position: absolute; left: 0; z-index: 99; background-size: contain; top: -8px; }
			.customSidebarButton .emptyButton:before { content: ''; background-image: url(/wp-content/uploads/2021/11/newicon1.png); background-repeat: no-repeat; background-position: center; height: 30px; width: 30px; position: absolute; left: 0; z-index: 99; background-size: contain; top: -7px; }
		}

		.cart-collaterals .inline-b a.import_xlsx { 
			text-align:right; 
			background: #70ad46; 
			position:relative; 
			padding: 13px; 
			font-size: 16px; 
			font-weight: 700;
			display: block;
    		color: #fff;
		}

		.cart-collaterals .inline-b a.import_xlsx:before {
		 content: ''; 
		background-image: url("data:image/svg+xml,%3Csvg version='1.1' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512' xmlns:xlink='http://www.w3.org/1999/xlink' enable-background='new 0 0 512 512' style='fill: %23fff%3B'%3E%3Cg%3E%3Cg%3E%3Cpath d='m153.7 171.5l81.9-88.1v265.3c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4v-265.3l81.9 88.1c7.7 8.3 20.6 8.7 28.9 1.1 8.3-7.7 8.7-20.6 1.1-28.9l-117.3-126.2c-11.5-11.6-25.6-5.2-29.9 0l-117.3 126.2c-7.7 8.3-7.2 21.2 1.1 28.9 8.2 7.6 21.1 7.2 28.8-1.1z'/%3E%3Cpath d='M480.6 341.2c-11.3 0-20.4 9.1-20.4 20.4V460H51.8v-98.4c0-11.3-9.1-20.4-20.4-20.4S11 350.4 11 361.6v118.8 c0 11.3 9.1 20.4 20.4 20.4h449.2c11.3 0 20.4-9.1 20.4-20.4V361.6C501 350.4 491.9 341.2 480.6 341.2z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
		    position: absolute;
		width: 30px;
		height: 30px;
		left: 14px;
		background-repeat: no-repeat;
		background-position: center;
		background-size: contain;
		top: 7px;
		}
		.cart-tag{
			display: inline-block;
			padding: 2px 10px;
			background: #000;
			color: #fff;
			border-radius: 10px;
			font-size: 12px;
			vertical-align: bottom;
			margin-left: 15px;
			margin-bottom: 2px;
		}

	</style>



	<a href="<?php echo site_url() . "/ecm_order_data" . get_current_user_id() . ".xlsx" ?>" style="display: none;" class="kkk">Download</a>
	<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
			<thead>
				<tr>
					
					<th class="product-thumbnail">&nbsp;</th>
					<th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
					<?php
					if ( is_user_logged_in() ) 
					{
						$userID = get_current_user_id();
						$user_meta=get_userdata($userID);
						$getGroupID = $wpdb->get_row("SELECT `group_id` from {$wpdb->prefix}groups_user_group WHERE `user_id` = '$userID'");
						$mexicoUserGroupID = $getGroupID->group_id;	
						if($mexicoUserGroupID == 2)
						{
							?>
							<th class="product-price"><?php esc_html_e( 'Wholesale Price', 'woocommerce' ); ?></th>
							<th class="product-price"><?php esc_html_e( 'Retail Price', 'woocommerce' ); ?></th>
							<?php
						}
						else
						{?>
							<th class="product-price"><?php esc_html_e( 'Unit Price', 'woocommerce' ); ?></th>
						<?php
						}
					}
					else				
					{
						?>
						<th class="product-price"><?php esc_html_e( 'Unit Price', 'woocommerce' ); ?></th>
						<?php
					}
					?>
					<th class="product-quantity"><?php esc_html_e( 'Box Units', 'woocommerce' ); ?></th>
					<th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
					<th class="product-remove">&nbsp;</th>
				</tr>
			</thead>
			<tbody id="myCartFilter">
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php
				/* if($_GET['abcd'])
									{
				echo "<pre>";
				print_r(WC()->cart->get_cart());
				echo "</pre>";
									} */

									$getTotalSummeryBrand = array();		
				$getTotalSummeryBrandPicis = array();
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
					$get_productBrand = get_the_terms($product_id,'pa_brand');
					
					$product_metas = get_post_meta($_product->get_id());
     
					$stock_avalible = isset($product_metas["_stock_present"][0])?(int)$product_metas["_stock_present"][0]:0;
					$stock_future = isset($product_metas["_stock_future"][0])?(int)$product_metas["_stock_future"][0]:0;

					$is_stock_future = isset($cart_item["type_stock"]) && $cart_item["type_stock"]=="future"?true:false;

					$_stock_max = $is_stock_future ? $stock_future :$stock_avalible ;
					
					
					$cat = get_the_terms( $_product->get_id() , 'product_cat' );
					$css_slugCategory = array();
					foreach($cat as $cvalue)
					{
						
							 $parentCat = get_term_by("id", $cvalue->parent, "product_cat");
							 $childrenCat = get_term_by("id", $cvalue->term_id, "product_cat");
							 if($parentCat->name != 'SPRING SUMMER 22' && $parentCat->name != 'Q1'){
							 	if($childrenCat->name != 'SPRING SUMMER 22'){
							 		$css_slugCategory[] = $childrenCat->name;
							 	}
							 }

					}
									
					$newDataProdcut1 = implode(",", $css_slugCategory);

					//echo $cart_item['variation_id'];
					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
						<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>" data-brandName="<?php echo $get_productBrand[0]->name; ?>" data-category="<?php echo $newDataProdcut1; ?>" data-tempCat="<?php echo $get_productBrand[0]->name; ?> - <?php echo $newDataProdcut1; ?>" >

						

							<td class="product-thumbnail">
							<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(array(100,100)), $cart_item, $cart_item_key );

							if ( ! $product_permalink ) {
								echo $thumbnail; // PHPCS: XSS ok.
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
							}
							?>
							</td>

							<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
							<?php
							//print_r($_product);
							$get_id = $_product->get_id();
							// if ( ! $product_permalink ) {
							// 	echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
								
							// } else {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );

								if($is_stock_future){
									echo '<span class="cart-tag">Future Stock</span>';
								}

								$vname = $_product->get_name();
								$vname = esc_sql($vname);
								$c = 0;
								$hide = (count($cart_item['variation_size'])==0)  ? "hide-it" : "no-hide-it";
						
									$allData = $wpdb->get_results("SELECT `order_item_id`,`order_id`   FROM `wp_woocommerce_order_items` WHERE `order_item_name` = '$vname' AND `order_item_type` = 'line_item'", ARRAY_A );
									foreach($allData as $bk)
									{
										if ( get_post_status ( $bk['order_id'] ) != 'wc-presale3' ) {
											continue;
										}
										else
										{
											$return_array1[$get_id][] = $bk['order_item_id'];
										}
									}


								/* echo "<pre>";
								print_r($return_array1);
								echo "</pre>";  */
								foreach($return_array1 as $key3 => $value3)
								{
									$sum = 0;
									foreach($value3 as $key4 => $abc)
									{
										$c1 = 0;
											$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
											$get_variation_id = wc_get_order_item_meta( $abc, '_variation_id', true );
											
											if(!in_array($abc, $return_array2))
											{
												if($get_variation_id == $key3)
												{
													//echo $key3 . " - " . $abc . "<br>";
													
													foreach ($variation_size as $key => $size) 
													{
														$c1 += $size['value'];
													}
												}
												array_push($return_array2, $abc);
											}
											
											$ap = wc_get_order_item_meta( $abc, '_qty', true );
											$sum += $c1 * $ap; 
									
										//echo "<p>" . $key4 . " " . $sum . "</p>";
									}
									$merge[$key3][] = $sum;
									
								} 
								/* echo "<pre>";
								print_r($merge);
								echo "</pre>"; */
								$c3 = 0;
								foreach ($cart_item['variation_size'] as $key => $size) {
									$c3 += $size['value']; 
								}
								$ttl1 = $c3 * $cart_item['quantity'];
								$newMoq = $ttl1 + $merge[$get_id][0];

								//echo $ttl1 . "<br>";	
								//echo $merge[$get_id][0] . "<br>";	
								$moq = 600;
											
								if($newMoq < 239)
								{
									$clas = 'red';	
								}
								else if($newMoq >= 239 && $newMoq < 600)
								{
									$clas = 'yellow';	
								}
								else
								{
									$clas = 'green';	
								}

								//echo $clas;
									
								echo "<div class='cart-sizes-attribute " . $clas . "'>";
									$row3 = '<div class="size-guide '.$hide.' ">';
									foreach ($cart_item['variation_size'] as $key => $size) {
										$row3 .= "<div class='inner-size'><span>" . $size['label']  . "</span><span>" . $size['value']*$cart_item['quantity'] . "</span></div>";
										$c += $size['value']; 
									}
									$ttl = $merge[$get_id][0];
									
									//ECHO $ttl;
									if($newMoq < $moq)
									{			
										$pending_moq = $moq - $newMoq;
										$pendingContent = "<div class='inner-size " .$hide. " jk_moq'><span class='moq'>Pending MOQ</span><span class='clr_val'>" . $pending_moq  . "</span></div>";
									}
									else
									{
										$pendingContent = '';
									}						
									$row3 .= "<div class='inner-size " .$hide. "'><span>Units</span><span>" . $c * $cart_item['quantity']  . "</span></div>";
									$row3 .= $pendingContent;
									echo $row3;
								echo "</div>";
								echo "</div>";
							//}

							do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

							// Meta data.
							echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

							// Backorder notification.
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
							}
							?>
							</td>
							<?php
							if ( is_user_logged_in() ) 
							{
								$price = $_product->get_price()/$c;
								//echo $price;
								//$userID = get_current_user_id();
								if(get_user_meta( $userID, 'customer_margin', true))
								{
									$getMargin = get_user_meta( $userID, 'customer_margin', true);
									if(get_user_meta( $ak, 'customer_iva_margin', true)){
										$getMargin = $getMargin + get_user_meta( $ak, 'customer_iva_margin', true);
									}
									$discountRule = (100 - $getMargin) / 100;
									$priceCustom = $price;
									$discountClass = 'wholesale-price';
								}
								else
								{	
									$discountRule = 1;
									$discountClass = '';
									$priceCustom = $_product->get_price()/$c;
								}
							}
							else if($user_meta->roles[0] == 'custom_role_puerto_rico')
							{
								$priceCustom = $_product->get_price()/$c * 1.25; 
							}
							else
							{
								$priceCustom = $_product->get_price()/$c;
							}						
							if($mexicoUserGroupID == 2)
							{
							?>
							<td class="product-price <?php echo $discountClass; ?>" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
								<label>Wholesale Price</label>
								<?php
									// echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								
								//echo apply_filters( 'woocommerce_cart_item_price', wc_price($priceCustom), $cart_item, $cart_item_key );
								echo wc_price($priceCustom);
								?>
							</td>
							
							<td class="product-price retail-price" >
								<label>Retail Price</label>
								<?php
									// echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								
								//echo apply_filters( 'woocommerce_cart_item_price', wc_price($priceCustom), $cart_item, $cart_item_key );
								echo apply_filters( 'woocommerce_cart_item_price', wc_price($_product->get_price()/$c), $cart_item, $cart_item_key );
								?>
							</td>
							<?php } 
							else if($user_meta->roles[0] == 'custom_role_puerto_rico')
							{
								?>
							<td class="product-price" >
								<label>Price</label>
								<?php
									// echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								
								//echo apply_filters( 'woocommerce_cart_item_price', wc_price($priceCustom), $cart_item, $cart_item_key );
								//echo apply_filters( 'woocommerce_cart_item_price', wc_price($_product->get_price()/$c), $cart_item, $cart_item_key );
								echo wc_price($priceCustom);
								?>
							</td>
								<?php
							}
							else {?>
							<td class="product-price" >
								<label>Price</label>
								<?php
									// echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								
								//echo apply_filters( 'woocommerce_cart_item_price', wc_price($priceCustom), $cart_item, $cart_item_key );
								echo apply_filters( 'woocommerce_cart_item_price', wc_price($_product->get_price()/$c), $cart_item, $cart_item_key );
								?>
							</td>
							<?php } ?>
							<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
								<label>Quantity</label>
							<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input(
									array(
										'input_name'   => "cart[{$cart_item_key}][qty]",
										'input_value'  => $cart_item['quantity'],
										'max_value'    => $_stock_max,//$_product->get_max_purchase_quantity(),
										'min_value'    => '0',
										'product_name' => $_product->get_name(),
									),
									$_product,
									false
								);
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
							?>
							</td>

							<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
								<label>Subtotal</label>
								<?php
									$cartPriceProduct = wc_price ( $_product->get_price() *  $cart_item['quantity']  );
									echo apply_filters( 'woocommerce_cart_item_subtotal', $cartPriceProduct, $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								?>
							</td>
								<td class="product-remove">
								<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><img src="'.site_url().'/wp-content/uploads/2021/11/newicon1.png" /></a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_html__( 'Remove this item', 'woocommerce' ),
											esc_attr( $product_id ),
											esc_attr( $_product->get_sku() )
										),
										$cart_item_key
									);
								?>
							</td>
						</tr>
						<?php
						//$getTotalSummeryBrand[$get_productBrand[0]->name][] = ( ($c * $cart_item['quantity']) * ($_product->get_price() *  $cart_item['quantity']) );
						$getTotalSummeryBrand[$get_productBrand[0]->name][] = ($_product->get_price() *  $cart_item['quantity']) ;
							$getTotalSummeryBrandPicis[$get_productBrand[0]->name][] = ($c * $cart_item['quantity']);


					}
				}

				$totalSummeryArr = array();
				foreach($getTotalSummeryBrand as $j => $v){
					$totalSummeryArr[$j] = number_format(array_sum($v),2,'.','');
				}
				$totalSummeryPicArr = array();
				foreach($getTotalSummeryBrandPicis as $jj => $vv){
					$totalSummeryPicArr[$jj] = array_sum($vv);
				}

				?>
				<input type="hidden" name="liveUpdateCartDatatotalSummeryArr" id="liveUpdateCartDatatotalSummeryArr" value='<?php echo json_encode($totalSummeryArr); ?>' />
				<input type="hidden" name="liveUpdateCartDatatotalSummeryPicArr" id="liveUpdateCartDatatotalSummeryPicArr" value='<?php echo json_encode($totalSummeryPicArr); ?>' />
				<?php do_action( 'woocommerce_cart_contents' ); ?>

				<tr>
					<td colspan="6" class="actions">
						<?php if ( wc_coupons_enabled() ) { ?>
							<div class="coupon" id="customBtnCart">
								<!-- <label for="coupon_code"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button> -->
								<?php do_action( 'woocommerce_cart_coupon' ); ?>
							</div>
						<?php } ?>
						

						<button type="submit" class="button" name="update_cart" id="customUpdateCartBtn" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

						<?php do_action( 'woocommerce_cart_actions' ); ?>

						<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

						

					</td>
				</tr>

				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			</tbody>
		</table>
		<?php do_action( 'woocommerce_after_cart_table' ); ?>
	</form>

	<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

	<div class="customSidebarButton">
		<a href="javascript:void(0);" class="updateCartCustom" >Update cart</a>
		<?php echo '<a href="' . esc_url( add_query_arg( 'empty_cart', 'yes' ) ) . '" class="emptyButton" title="' . esc_attr( 'Empty Cart', 'woocommerce' ) . '">' . esc_html( 'Empty Cart', 'woocommerce' ) . '</a>'; ?>

	</div>


	<div class="cart-collaterals">
		<?php
			/**
			 * Cart collaterals hook.
			 *
			 * @hooked woocommerce_cross_sell_display
			 * @hooked woocommerce_cart_totals - 10
			 */
			do_action( 'woocommerce_cart_collaterals' );
		?>
	</div>

	<?php do_action( 'woocommerce_after_cart' ); ?>
	

	<script>

	jQuery(function() {

		jQuery('.updateCartCustom').off('click').on('click',function(){
			jQuery('#customUpdateCartBtn').trigger('click');
		})


	    var sortable = jQuery("#myCartFilter");
	    var groups = [];
	    sortable.find("tr").each(function(i) { 
	        var brandname = jQuery(this).data("brandname");
			if(jQuery.inArray(brandname, groups) === -1) {
			   groups.push(brandname);
			}
			
	    });


	    groups.forEach(function(group) { 
	    	//<span>"+group+"</span>
	        var liElements = sortable.find("tr[data-brandname='" + group + "']"),
	            groupUl = jQuery("<div class='brandColumns' data-brand='"+group+"' data-cat='' ><span class='brandTitle' >"+group+"</span>").append(liElements);
	       	jQuery("#myCartFilter").append(groupUl);
	    });
	  
	    sortable.find(".brandColumns").each(function(i) { 
	        var brandname = jQuery(this).data("brand");
	        if(brandname == 'undefined' ){
				jQuery(this).remove();
	        }
	    });
		
		//

	    var categorygroups = [];
		var brandgroups = [];
	    sortable.find(".brandColumns tr").each(function(i) { 
			var category = jQuery(this).data("tempcat");
			var brandname = jQuery(this).data("brandname");
			if(brandname == jQuery(this).closest(".brandColumns").data('brand')  ){
				if(jQuery.inArray(category, categorygroups) === -1 && jQuery.inArray(brandname, brandgroups) === -1) {
					jQuery('.brandColumns tr[data-tempcat="' + jQuery(this).data("tempcat") + '"]').wrapAll('<div id="catBlock" data-categoryname="'+jQuery(this).data("category")+'" />')
					categorygroups.push(category);
					
				}
			}

	    });
	    sortable.find(".brandColumns #catBlock").each(function(i) { jQuery(this).prepend('<span class="catTitle">'+jQuery(this).data("categoryname")+'</span>'); 	});


	    var totalSummeryArr = jQuery.parseJSON(jQuery('#liveUpdateCartDatatotalSummeryArr').val());
	    var totalSummeryPicArr = jQuery.parseJSON(jQuery('#liveUpdateCartDatatotalSummeryPicArr').val()); 

	   	sortable.find(".brandColumns").each(function(i) { 
			    	jQuery(this).find('#catBlock tr').last().after('<tr class="dataSummery"> <td></td> <td class="small1"><span class="span3">Total Units</span> <span class="span4">'+totalSummeryPicArr[jQuery(this).attr('data-brand')]+'</span> </td> <td></td> <td class="total-td-col"><span class="span1">Total</span></td> <td class="last-td-col"><span class="span2"> $'+totalSummeryArr[jQuery(this).attr('data-brand')] + '</span> </td> </tr>');
			    });
	    
		jQuery( document.body ).on( 'updated_cart_totals', function(){
			var sortable = jQuery("#myCartFilter");
				var groups = [];
				sortable.find("tr").each(function(i) { 
					var brandname = jQuery(this).data("brandname");
					if(jQuery.inArray(brandname, groups) === -1) {
					groups.push(brandname);
					}
					
				});


				groups.forEach(function(group) { 
					//<span>"+group+"</span>
					var liElements = sortable.find("tr[data-brandname='" + group + "']"),
						groupUl = jQuery("<div class='brandColumns' data-brand='"+group+"' data-cat='' ><span class='brandTitle' >"+group+"</span>").append(liElements);
					jQuery("#myCartFilter").append(groupUl);
				});
			
				sortable.find(".brandColumns").each(function(i) { 
					var brandname = jQuery(this).data("brand");
					if(brandname == 'undefined' ){
						jQuery(this).remove();
					}
				});
				
				//

				var categorygroups = [];
				var brandgroups = [];
				sortable.find(".brandColumns tr").each(function(i) { 
					var category = jQuery(this).data("tempcat");
					var brandname = jQuery(this).data("brandname");
					if(brandname == jQuery(this).closest(".brandColumns").data('brand')  ){
						if(jQuery.inArray(category, categorygroups) === -1 && jQuery.inArray(brandname, brandgroups) === -1) {
							jQuery('.brandColumns tr[data-tempcat="' + jQuery(this).data("tempcat") + '"]').wrapAll('<div id="catBlock" data-categoryname="'+jQuery(this).data("category")+'" />')
							categorygroups.push(category);
							
						}
					}

				});
				sortable.find(".brandColumns #catBlock").each(function(i) { jQuery(this).prepend('<span class="catTitle">'+jQuery(this).data("categoryname")+'</span>');});

				var totalSummeryArr1 = jQuery.parseJSON(jQuery('#liveUpdateCartDatatotalSummeryArr').val());
	    		var totalSummeryPicArr1 = jQuery.parseJSON(jQuery('#liveUpdateCartDatatotalSummeryPicArr').val()); 


				

				   		sortable.find(".brandColumns").each(function(i) { 
			    	jQuery(this).find('#catBlock tr').last().after('<span class="dataSummery"> <small class="small1"><span class="span3">Total Units</span> <span class="span4">'+totalSummeryPicArr1[jQuery(this).attr('data-brand')]+'</span> </small> <small><span class="span1">Total</span> <span class="span2"> $'+totalSummeryArr1[jQuery(this).attr('data-brand')] + '</span> </small> </span>');
			    });
				  

		});

	    
	});


	if( jQuery(".woocommerce-message").text().indexOf('The cart has been filled with the items') >= 0) {
		window.location.reload(true);
	}
	</script>
