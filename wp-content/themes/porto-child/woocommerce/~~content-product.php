<?php

/**
 * The template for displaying product content within loops
 *
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $woocommerce_loop, $product, $porto_settings, $porto_woocommerce_loop, $porto_layout;



$getas = get_option('afpvu_user_role_visibility');
$ak = maybe_unserialize($getas);
//print_r($ak);
$porto_woo_version = porto_get_woo_version_number();

// Ensure visibility.
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Extra post classes
$classes = array( 'product-col' );

if ( ! $porto_settings['category-hover'] ) {
	$classes[] = 'hover';
}


if ( ( function_exists( 'wc_get_loop_prop' ) && ! wc_get_loop_prop( 'is_paginated' ) ) || isset( $porto_woocommerce_loop['view'] ) || ! isset( $_COOKIE['gridcookie'] ) || 'list' != $_COOKIE['gridcookie'] ) {
	if ( ! isset( $porto_woocommerce_loop['view'] ) || 'list' != $porto_woocommerce_loop['view'] ) {
		if ( isset( $woocommerce_loop['addlinks_pos'] ) && 'quantity' == $woocommerce_loop['addlinks_pos'] ) {
			$classes[] = 'product-wq_onimage';
		} elseif ( isset( $woocommerce_loop['addlinks_pos'] ) ) {
			if ( 'outimage_aq_onimage2' == $woocommerce_loop['addlinks_pos'] ) {
				$classes[] = 'product-outimage_aq_onimage with-padding';
			} elseif ( 'onhover' == $woocommerce_loop['addlinks_pos'] ) {
				$classes[] = 'product-default show-links-hover';
			} else {
				$classes[] = 'product-' . esc_attr( $woocommerce_loop['addlinks_pos'] );
			}
		}
	}
}

if ( isset( $porto_woocommerce_loop['view'] ) && 'creative' == $porto_woocommerce_loop['view'] && isset( $porto_woocommerce_loop['grid_layout'] ) && isset( $porto_woocommerce_loop['grid_layout'][ $woocommerce_loop['product_loop'] % count( $porto_woocommerce_loop['grid_layout'] ) ] ) ) {
	$grid_layout = $porto_woocommerce_loop['grid_layout'][ $woocommerce_loop['product_loop'] % count( $porto_woocommerce_loop['grid_layout'] ) ];
	$classes[]   = 'grid-col-' . $grid_layout['width'] . ' grid-col-md-' . $grid_layout['width_md'] . ( isset( $grid_layout['width_lg'] ) ? ' grid-col-lg-' . $grid_layout['width_lg'] : '' ) . ' grid-height-' . $grid_layout['height'];

	$porto_woocommerce_loop['image_size'] = $grid_layout['size'];
}

$woocommerce_loop['product_loop']++;

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

//echo $product->get_id();


/* $variationid = wc_get_product($product->get_id());
$productid = wc_get_product( $variationid->get_parent_id() );

if($variationid->get_parent_id() == 0)
{
$get_variations = count($product->get_children());
if($get_variations == 0)
{
	$kk = '';
}
else
{
$childV = $product->get_available_variations();
echo "<pre>";
//print_r($childV[0]['variation_gallery_images']);
//print_r($childV[0]);
//echo $childV[0]['variation_id'];
echo "</pre>";
$cc = $childV[0]['attributes']['attribute_pa_color'];
$kk = "?attribute_pa_color=" . $cc;
}
}
else
{
$kk = '';
} */
$hideClass = '';
//echo $_SESSION['abc'];
if(isset($_SESSION['abc']) && !empty($_SESSION['abc']))
{
if($ak[$_SESSION['abc']]['afpvu_show_hide_role'] == 'hide')
{
	if(in_array($product->get_id(),$ak[$_SESSION['abc']]['afpvu_applied_products_role']) || in_array($productid->id,$ak[$_SESSION['abc']]['afpvu_applied_products_role']))
	{
		$hideClass = 'hiding';
	}
}
}
//data-hideNFL="<?php echo $_SESSION['abc'] . "" . strtolower($product->get_attribute( 'pa_brand' ));
?>

	<li <?php wc_product_class( $classes, $product ); ?> data-hideClass="<?php echo $hideClass; ?>" data-hideNFL="<?php echo $_SESSION['abc'] . "" . strtolower($product->get_attribute( 'pa_collection' ));?>" data-brandName="<?php echo strtolower($product->get_attribute( 'pa_collection' ));?>">
	<div class="product-inner">
		<?php
		/**
		 * Hook: woocommerce_before_shop_loop_item.
		 *
		 * @hooked woocommerce_template_loop_product_link_open - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item' );
		?>

		<div class="product-image">

			<a <?php echo porto_filter_output( $more_target ); ?> href="<?php echo esc_url( $more_link ); ?>">
				<?php

					/**
					 * Hook: woocommerce_before_shop_loop_item_title.
					 *
					 * @hooked woocommerce_show_product_loop_sale_flash - 10
					 * @hooked woocommerce_template_loop_product_thumbnail - 10
					 */
					do_action( 'woocommerce_before_shop_loop_item_title' );
				?>
			</a>
		<?php if ( ( ! isset( $porto_woocommerce_loop['widget'] ) || ! $porto_woocommerce_loop['widget'] ) && ( ! isset( $porto_woocommerce_loop['use_simple_layout'] ) || ! $porto_woocommerce_loop['use_simple_layout'] ) && isset( $woocommerce_loop['addlinks_pos'] ) && ! empty( $woocommerce_loop['addlinks_pos'] ) && ( ! in_array( $woocommerce_loop['addlinks_pos'], array( 'default', 'onhover', 'outimage' ) ) || ( class_exists( 'YITH_WCWL' ) && $porto_settings['product-wishlist'] && 'onimage' == $woocommerce_loop['addlinks_pos'] ) ) && ( ! isset( $porto_woocommerce_loop['view'] ) || 'list' != $porto_woocommerce_loop['view'] ) ) : ?>
			<div class="links-on-image">
				<?php do_action( 'porto_woocommerce_loop_links_on_image' ); ?>
			</div>
		<?php endif; ?>
		</div>

		<div class="product-content">
			<?php do_action( 'porto_woocommerce_before_shop_loop_item_title' ); ?>

			<?php
				/**
				 * Hook: woocommerce_shop_loop_item_title.
				 *
				 * @hooked woocommerce_template_loop_product_title - 10
				 */
				do_action( 'woocommerce_shop_loop_item_title' );
			?>

			<?php
				/**
				 * Hook: woocommerce_after_shop_loop_item_title.
				 *
				 * @hooked woocommerce_template_loop_rating - 5
				 * @hooked woocommerce_template_loop_price - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item_title' );
			?>

			<?php
				/**
				* Hook: woocommerce_after_shop_loop_item.
				*
				* @hooked woocommerce_template_loop_product_link_close - 5 : removed
				* @hooked woocommerce_template_loop_add_to_cart - 10
				*/
				do_action( 'woocommerce_after_shop_loop_item' );
			?>
		</div>
	</div>
	</li>
