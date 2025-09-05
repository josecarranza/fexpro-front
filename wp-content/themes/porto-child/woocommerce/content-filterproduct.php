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

$variationid = wc_get_product($product->get_id());
$get_variations = $variationid->get_available_variations();

$filter_color = $_GET['filter_color'];
/*$words = preg_replace('/[0-9]+/', '', $filter_color);
$newFilterColor = explode(",",$words);
$newFilterColor = array_unique($newFilterColor);
$newFilterColor = array_values($newFilterColor);*/

$newFilterColor = explode(",",$filter_color);
$getVariationFilterArr = [];
foreach($get_variations as $key22 => $value22){
 if(in_array($value22['attributes']['attribute_pa_color'], $newFilterColor)){

 	$getVariationFilterArr[] = $value22;
 }
}

if(!empty($getVariationFilterArr)){ 

?>
	<?php 	foreach($getVariationFilterArr as $images){ 


		?>
		<input type="hidden" name="asdfsadfsadfsadf" value="<?php //echo "<pre>"; print_r($images); echo "</pre>"; ?>" />
	
		<li <?php wc_product_class( $classes, $product ); ?> data-hideClass="<?php echo $hideClass; ?>" data-hideNFL="<?php echo $_SESSION['abc'] . "" . strtolower($product->get_attribute( 'pa_brand' ));?>">
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
				<a <?php echo porto_filter_output( $more_target ); ?> href="<?php echo esc_url( $more_link ); ?>?attribute_pa_color=<?php echo $images['attributes']['attribute_pa_color']; ?>">

					<div class="inner img-effect" >
						<img width="<?php echo $images['image']['thumb_src_w']; ?>" height="<?php echo $images['image']['thumb_src_h']; ?>" src="<?php echo $images['image']['thumb_src']; ?>" class=" wp-post-image" alt="" loading="lazy" srcset="<?php echo $images['image']['srcset']; ?>" sizes="<?php echo $images['image']['sizes']; ?>">

						<?php if(count($images['variation_gallery_images'])==2){?>
							<img width="<?php echo $images['variation_gallery_images'][1]['archive_src_w']; ?>" height="<?php echo $images['variation_gallery_images'][1]['archive_src_h']; ?>" src="<?php echo $images['variation_gallery_images'][1]['archive_src']; ?>" class="hover-image" alt="" loading="lazy" srcset="<?php echo $images['variation_gallery_images'][1]['srcset']; ?>" sizes="<?php echo $images['variation_gallery_images'][1]['sizes']; ?>"></div>
						<?php }else {?>
							<img width="<?php echo $images['variation_gallery_images'][0]['archive_src_w']; ?>" height="<?php echo $images['variation_gallery_images'][0]['archive_src_h']; ?>" src="<?php echo $images['variation_gallery_images'][0]['archive_src']; ?>" class="hover-image" alt="" loading="lazy" srcset="<?php echo $images['variation_gallery_images'][0]['srcset']; ?>" sizes="<?php echo $images['variation_gallery_images'][0]['sizes']; ?>"></div>
						<?php } ?>
				</a>

			<?php if ( ( ! isset( $porto_woocommerce_loop['widget'] ) || ! $porto_woocommerce_loop['widget'] ) && ( ! isset( $porto_woocommerce_loop['use_simple_layout'] ) || ! $porto_woocommerce_loop['use_simple_layout'] ) && isset( $woocommerce_loop['addlinks_pos'] ) && ! empty( $woocommerce_loop['addlinks_pos'] ) && ( ! in_array( $woocommerce_loop['addlinks_pos'], array( 'default', 'onhover', 'outimage' ) ) || ( class_exists( 'YITH_WCWL' ) && $porto_settings['product-wishlist'] && 'onimage' == $woocommerce_loop['addlinks_pos'] ) ) && ( ! isset( $porto_woocommerce_loop['view'] ) || 'list' != $porto_woocommerce_loop['view'] ) ) : ?>
			
				<div class="links-on-image">
					<div class="add-links-wrap">
						<div class="add-links no-effect clearfix">
							<a href="<?php echo esc_url( $more_link ); ?>?attribute_pa_color=<?php echo $images['attributes']['attribute_pa_color']; ?>" data-quantity="<?php echo $images['min_qty']; ?>" class="viewcart-style-1 button product_type_variable add_to_cart_button" data-product_id="<?php echo $product->get_id(); ?>" data-product_sku="<?php echo $product->get_sku(); ?>-<?php echo strtoupper($images['attributes']['attribute_pa_color']); ?>" aria-label="Select options for “<?php echo $product->get_title(); ?>”" rel="nofollow">Select options</a>
						</div>
					</div>
				</div>

			<?php endif; ?>
			</div>

			<div class="product-content">
				<?php do_action( 'porto_woocommerce_before_shop_loop_item_title' ); ?>

				<a class="product-loop-title" href="<?php echo esc_url( $more_link ); ?>?attribute_pa_color=<?php echo $images['attributes']['attribute_pa_color']; ?>">
					<h3 class="woocommerce-loop-product__title"><?php echo $product->get_title(); ?>-<?php echo strtoupper($images['attributes']['attribute_pa_color']); ?></h3>	
				</a>


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

	<?php  } ?>


<?php } ?>