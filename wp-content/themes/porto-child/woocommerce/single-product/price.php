<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $wpdb;
if ( is_user_logged_in() ) {
	$u =  wp_get_current_user();
    $rol = isset($u->roles[0])?$u->roles[0]:array();
	$product_id = $product->get_id();


	$prices = role_price_get_by_parent_id($product_id ,$rol);
	

	if(count($prices)==1)
	{
		$base_price=$prices[0];
		if(in_array($rol,array("custom_role_mexico1","custom_role_mexico2"))){
			$discounts = discount_by_rol_margin($u->ID);
			if($discounts["margin"]!=0){
				$_margin = $base_price - ($base_price * ($discounts["margin"]/100));
				$iva = 1+($discounts["iva"]/100);
                $final=$_margin / $iva;
				$prices[0]=$final;
			}
		}
		$priceHTML = '<span class="wholesale-price">' . wc_price($prices[0]) . '</span>';
		if($base_price>$prices[0]){
			$priceHTML.='<span class="base-price-tach">' . wc_price($base_price) . '</span>';
		}
	}
	else
	{
		
		
		$base_price=$prices[0];
		$base_price2=$prices[1];
		if(in_array($rol,array("custom_role_mexico1","custom_role_mexico2"))){
			$discounts = discount_by_rol_margin($u->ID);
			if($discounts["margin"]!=0){
				$_margin = $base_price - ($base_price * ($discounts["margin"]/100));
				$iva = 1+($discounts["iva"]/100);
                $final=$_margin / $iva;
				$prices[0]=$final;

				$_margin = $base_price2 - ($base_price2 * ($discounts["margin"]/100));
				$iva = 1+($discounts["iva"]/100);
                $final=$_margin / $iva;
				$prices[1]=$final;
			}
		}

		$priceHTML = '<span class="wholesale-price">' . wc_price($prices[0]) . '</span> - <span class="wholesale-price">' . wc_price($prices[1]) . '</span>';

		if($base_price>$prices[0]){
			$priceHTML .= '<br><span class="wholesale-price base-price-tach2">' . wc_price($base_price) . '</span> - <span class="wholesale-price base-price-tach2">' . wc_price($base_price2) . '</span>';
		}

		//$priceHTML = wc_price($min) . "-" . wc_price($max);
	}
	//number_format((float)$foo, 2, '.', '');
?>
<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $priceHTML; ?></p>
<?php
} 
else
{
?>
<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $product->get_price_html(); ?></p>
<?php
}
?>
