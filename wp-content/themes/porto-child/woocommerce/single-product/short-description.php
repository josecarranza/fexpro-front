<?php
/**
 * Single product short description
 *
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );

if ( ! $short_description ) {
	//return;
	$short_description="";
}
?>

<div class="description woocommerce-product-details__short-description">
	<?php echo ! $short_description ? '' : $short_description; // WPCS: XSS ok. ?>
</div>
<span class="prod-sku">{{current_sku}}</span><br>
<?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
