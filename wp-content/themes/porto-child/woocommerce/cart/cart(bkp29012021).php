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

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>
			<tr>
				<th class="product-remove">&nbsp;</th>
				<th class="product-thumbnail">&nbsp;</th>
				<th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
				<th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			/* echo "<pre>";
			print_r(WC()->cart->get_cart());
			echo "</pre>";
			$c = 0; */
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

						<td class="product-remove">
							<?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										esc_html__( 'Remove this item', 'woocommerce' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									),
									$cart_item_key
								);
							?>
						</td>

						<td class="product-thumbnail">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

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
						if ( ! $product_permalink ) {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
						} else {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
							//echo "<div class='color-title'><h4>Color: <span>" . $_product->attributes['color'] . "</span></h4></div>";
							/* echo "<div class='cart-sizes-attribute'>";
								$row3 = "<div class='size-guide " . ((get_post_meta( $getVarID, 'custom_field1', true ) && get_post_meta( $getVarID, 'custom_field2', true ) && get_post_meta( $getVarID, 'custom_field3', true ) && get_post_meta( $getVarID, 'custom_field4', true ) && get_post_meta( $getVarID, 'custom_field5', true ) && get_post_meta( $getVarID, 'custom_field6', true ) && get_post_meta( $getVarID, 'custom_field7', true ) && get_post_meta( $getVarID, 'custom_field8', true ) && get_post_meta( $getVarID, 'custom_field9', true ) && get_post_meta( $getVarID, 'custom_field10', true ))  ? 'no-hide-it' : 'hide-it')  . "'><h5>Sizes</h5>";
								if(get_post_meta( $get_id, 'custom_field1', true ) && get_post_meta( $get_id, 'size_box_qty1', true ))
								{
									$ak = intval(get_post_meta( $get_id, 'size_box_qty1', true ));
									$row3 .= "<div class='inner-size'><span>" . get_post_meta( $get_id, 'custom_field1', true )  . "</span><span>" . get_post_meta( $get_id, 'size_box_qty1', true ) . "</span></div>";								
								}
								if(get_post_meta( $get_id, 'custom_field2', true ) && get_post_meta( $get_id, 'size_box_qty2', true ))
								{
									$ak += intval(get_post_meta( $get_id, 'size_box_qty2', true ));
									$row3 .= "<div class='inner-size'><span>" . get_post_meta( $get_id, 'custom_field2', true )  . "</span><span>" . get_post_meta( $get_id, 'size_box_qty2', true ) . "</span></div>";
								}
								if(get_post_meta( $get_id, 'custom_field3', true ) && get_post_meta( $get_id, 'size_box_qty3', true ))
								{
									$ak += intval(get_post_meta( $get_id, 'size_box_qty3', true ));
									$row3 .= "<div class='inner-size'><span>" . get_post_meta( $get_id, 'custom_field3', true )  . "</span><span>" . get_post_meta( $get_id, 'size_box_qty3', true ) . "</span></div>";
								}
								if(get_post_meta( $get_id, 'custom_field4', true ) && get_post_meta( $get_id, 'size_box_qty4', true ))
								{
									$ak += intval(get_post_meta( $get_id, 'size_box_qty4', true ));
									$row3 .= "<div class='inner-size'><span>" . get_post_meta( $get_id, 'custom_field4', true )  . "</span><span>" . get_post_meta( $get_id, 'size_box_qty4', true ) . "</span></div>";
								}
								if(get_post_meta( $get_id, 'custom_field5', true ) && get_post_meta( $get_id, 'size_box_qty5', true ))
								{
									$ak += intval(get_post_meta( $get_id, 'size_box_qty5', true ));
									$row3 .= "<div class='inner-size'><span>" . get_post_meta( $get_id, 'custom_field5', true )  . "</span><span>" . get_post_meta( $get_id, 'size_box_qty5', true ) . "</span></div>";
								}
								if(get_post_meta( $get_id, 'custom_field6', true ) && get_post_meta( $get_id, 'size_box_qty6', true ))
								{
									$ak += intval(get_post_meta( $get_id, 'size_box_qty6', true ));
									$row3 .= "<div class='inner-size'><span>" . get_post_meta( $get_id, 'custom_field6', true )  . "</span><span>" . get_post_meta( $get_id, 'size_box_qty6', true ) . "</span></div>";
								}
								if(get_post_meta( $get_id, 'custom_field7', true ) && get_post_meta( $get_id, 'size_box_qty7', true ))
								{
									$ak += intval(get_post_meta( $get_id, 'size_box_qty7', true ));
									$row3 .= "<div class='inner-size'><span>" . get_post_meta( $get_id, 'custom_field7', true )  . "</span><span>" . get_post_meta( $get_id, 'size_box_qty7', true ) . "</span></div>";
								}
								if(get_post_meta( $get_id, 'custom_field8', true ) && get_post_meta( $get_id, 'size_box_qty8', true ))
								{
									$ak += intval(get_post_meta( $get_id, 'size_box_qty8', true ));
									$row3 .= "<div class='inner-size'><span>" . get_post_meta( $get_id, 'custom_field8', true )  . "</span><span>" . get_post_meta( $get_id, 'size_box_qty8', true ) . "</span></div>";
								}
								if(get_post_meta( $get_id, 'custom_field9', true ) && get_post_meta( $get_id, 'size_box_qty9', true ))
								{
									$ak += intval(get_post_meta( $get_id, 'size_box_qty9', true ));
									$row3 .= "<div class='inner-size'><span>" . get_post_meta( $get_id, 'custom_field9', true )  . "</span><span>" . get_post_meta( $get_id, 'size_box_qty9', true ) . "</span></div>";
								}
								if(get_post_meta( $get_id, 'custom_field10', true ) && get_post_meta( $get_id, 'size_box_qty10', true ))
								{
									$ak += intval(get_post_meta( $get_id, 'size_box_qty10', true ));
									$row3 .= "<div class='inner-size'><span>" . get_post_meta( $get_id, 'custom_field10', true )  . "</span><span>" . get_post_meta( $get_id, 'size_box_qty10', true ) . "</span></div>";
								}
							
								$row3 .= "<div class='inner-size " . (($ak == 0)  ? 'hide-it' : 'no-hide-it')  . "'><span>Total</span><span>" . intval($ak) * $cart_item['quantity']  . "</span></div>";
								$row3 .= "</div>";
								echo $row3;
							echo "</div>"; */
							$c = 0;
							$hide = (count($cart_item['variation_size'])==0)  ? "hide-it" : "no-hide-it";
							echo "<div class='cart-sizes-attribute'>";
								$row3 = '<div class="size-guide '.$hide.' "><h5>Sizes</h5>';
								foreach ($cart_item['variation_size'] as $key => $size) {
									$row3 .= "<div class='inner-size'><span>" . $size['label']  . "</span><span>" . $size['value'] . "</span></div>";
									$c += $size['value']; 
								}
														
								$row3 .= "<div class='inner-size " .$hide. "'><span>Total</span><span>" . $c * $cart_item['quantity']  . "</span></div>";
								echo $row3;
							echo "</div>";
							echo "</div>";
						}

						do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

						// Meta data.
						echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

						// Backorder notification.
						if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
						}
						?>
						</td>

						<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
							<?php
								// echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							echo apply_filters( 'woocommerce_cart_item_price', wc_price($_product->get_price()/$c), $cart_item, $cart_item_key );
							?>
						</td>

						<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
						<?php
						if ( $_product->is_sold_individually() ) {
							$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
						} else {
							$product_quantity = woocommerce_quantity_input(
								array(
									'input_name'   => "cart[{$cart_item_key}][qty]",
									'input_value'  => $cart_item['quantity'],
									'max_value'    => $_product->get_max_purchase_quantity(),
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
							<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</td>
					</tr>
					<?php
				}
			}
			?>

			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<tr>
				<td colspan="6" class="actions">

					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon">
							<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php } ?>

					<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

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
