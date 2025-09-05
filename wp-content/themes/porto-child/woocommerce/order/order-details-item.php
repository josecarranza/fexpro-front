<?php
/**
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
	return;
}
global $wpdb;
$qty = $item->get_quantity();
$refunded_qty = $order->get_qty_refunded_for_item( $item_id );

if ( $refunded_qty ) {
	$qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
} else {
	$qty_display = esc_html( $qty );
}

?>
<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'woocommerce-table__line-item order_item', $item, $order ) ); ?>">

	<td class="woocommerce-table__product-name product-name">
		<?php
		$is_visible        = $product && $product->is_visible();
		$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );

		echo "<div class='title-quantity-combie'>";
		echo apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s">%s</a>', $product_permalink, $item->get_name() ) : $item->get_name(), $item, $is_visible ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '[Qty : %s]', $qty_display ) . '</strong>', $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$type_stock = wc_get_order_item_meta( $item_id, 'type_stock', true );
		if(!empty($type_stock) == "future"){
				echo '<span class="cart-tag" style="display: inline-block;padding: 2px 10px;background: #000;color: #fff;border-radius: 10px;font-size: 12px;vertical-align: middle; margin-left:10px;margin-bottom: 2px;">Stock Future</span>';
		}

		echo "</div>";
		$return_array1 = array();
		$vname = $item->get_name();
		
		$allData = $wpdb->get_results("SELECT `order_item_id`,`order_id`   FROM `wp_woocommerce_order_items` WHERE `order_item_name` = '$vname' AND `order_item_type` = 'line_item'", ARRAY_A );
		foreach($allData as $bk)
		{
			if ( get_post_status ( $bk['order_id'] ) != 'wc-presale3' ) {
				continue;
			}
			else
			{
				$return_array1[$item->get_variation_id()][] = $bk['order_item_id'];
			}
		}

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
		
		$c = 0;
		$e = 0;
		$pending_moq = 0;
		$product_id = $item->get_variation_id();
		$_product = wc_get_product($product_id) ;
		$image_id         = get_post_meta( $product_id, '_thumbnail_id', true );
		if(empty($image_id))
		{
			$src  = WC()->plugin_url() . '/assets/images/placeholder.png';
			
			$thumbnail_src = $src;
		}
		else
		{
		$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
		$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
		$thumbnail_src     = wp_get_attachment_image_src( $image_id, $thumbnail_size );
		$thumbnail_src = $thumbnail_src[0];
		}
		// $img_url = get_the_post_thumbnail_url($product_id);
		$variation_size = wc_get_order_item_meta( $item_id, 'item_variation_size', true );
		$hide = (count($variation_size)==0)  ? "hide-it" : "no-hide-it";
		foreach ($variation_size as $key => $size) {
			$e += $size['value']; 
		}
		$ttl1 = $e * $item->get_quantity();
		//echo $product_id;
		$moq = 600;
		/* if($ttl1 < $moq)
		{
			if($ttl1 < 239)
			{
				$clas = 'red';	
			}
			else if($ttl1 >= 239 && $ttl1 < 601)
			{
				$clas = 'yellow';	
			}
			else
			{
				$clas = 'green';	
			}	
		}
		else
		{
			$clas = 'green';
		} */
		if($merge[$product_id][0] < 239)
		{
			$clas = 'red';	
		}
		else if($merge[$product_id][0] >= 239 && $merge[$product_id][0] < 600)
		{
			$clas = 'yellow';	
		}
		else
		{
			$clas = 'green';	
		}
		
		
		echo "<div class='cart-sizes-attribute " . $clas . "'>";
			echo "<div class='variation_img'><img src='".$thumbnail_src."'></div>";
			$row3 = '<div class="size-guide '.$hide.' "><h5>Sizes</h5>';
			foreach ($variation_size as $key => $size) {
				$row3 .= "<div class='inner-size'><span>" . $size['label']  . "</span><span class='clr_val'>" . $size['value'] * $item->get_quantity() . "</span></div>";
				$c += $size['value']; 
			}
			$ttl = $merge[$product_id][0];
			if($ttl < $moq)
			{			
				$pending_moq = $moq - $ttl;
				$pendingContent = "<div class='inner-size " .$hide. " jk_moq'><span class='moq'>Pending MOQ</span><span class='clr_val'>" . $pending_moq  . "</span></div>";
			}
			else
			{
				$pendingContent = '';
			}
			
			$row3 .= "<div class='inner-size " .$hide. " jk'><span>Total</span><span>" . $c * $item->get_quantity()  . "</span></div>";
			$row3 .= $pendingContent;
			echo $row3;
			
		echo "</div>";
		echo "</div>";
		/*$qty          = $item->get_quantity();
		$refunded_qty = $order->get_qty_refunded_for_item( $item_id );

		if ( $refunded_qty ) {
			$qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
		} else {
			$qty_display = esc_html( $qty );
		}*/

		//echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $qty_display ) . '</strong>', $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );

		//wc_display_item_meta( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );
		?>
	</td>

	<td class="woocommerce-table__product-total product-total">
		<?php echo $order->get_formatted_line_subtotal( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</td>

</tr>

<?php if ( $show_purchase_note && $purchase_note ) : ?>

<tr class="woocommerce-table__product-purchase-note product-purchase-note">

	<td colspan="2"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>

</tr>

<?php endif; ?>
