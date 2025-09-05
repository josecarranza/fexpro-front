<?php
/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* global $product;
?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<span class="price"><?php echo $price_html; ?></span>
<?php endif; ?> */

global $product, $wpdb;
if ( is_user_logged_in() ) {
	/* echo "<pre>";
	print_r($product);
	echo "</pre>"; */
	//print_r($prices1);
	
    $userID = get_current_user_id();
	$user_meta=get_userdata($userID);

	//$user_roles=$user_meta->roles;
	$product_id = $product->get_id();
	$getChildren = $product->get_children();
	if(get_user_meta( $userID, 'customer_margin', true))
	{
		
		$getMargin = get_user_meta( $userID, 'customer_margin', true);
		if(get_user_meta( $userID, 'customer_iva_margin', true)){
			$getMargin = $getMargin + get_user_meta( $userID, 'customer_iva_margin', true);
		}
		$discountRule = (100 - $getMargin) / 100;
		//echo $discountRule;
		if($discountRule != '' || $discountRule != 0)
		{
			$strike = 'strike-through';
		}
		$user_group_table = _groups_get_tablename( 'user_group' );
		$getGroup = $wpdb->get_row("SELECT `group_id` FROM $user_group_table WHERE `user_id` = '$userID'");
		$getGroupID =  $getGroup->group_id;
		//echo $getGroupID;
		$product_id = $product->get_id();
		$getChildren = $product->get_children();
		//print_r($getChildren);
		foreach($getChildren as $value)
		{
			$getGroupPrice = $wpdb->get_row("SELECT `price` from {$wpdb->prefix}wusp_group_product_price_mapping WHERE `group_id` = '$getGroupID' AND `product_id` = $value");
			$prices[] = $getGroupPrice->price;
		}
		//print_r($prices);
		$min = min($prices);
		$max = max($prices);
		//echo $min . "<br>";
		//echo $max . "<br>";
		if($min == $max)
		{
			$priceHTML = '<div class="custom_margin_price ' . $strike . '"><span class="regular-price">' . wc_price($min * $discountRule) . '</span>' . ' <span class="wholesale-price">' . wc_price($min) . '</span></div>';
		}
		else
		{
			$priceHTML = '<div class="custom_margin_price ' . $strike . '"><span class="regular-price">' . wc_price($min) . '</span>' . ' <span class="wholesale-price">' . wc_price($min * $discountRule) . '</span> - <span class="regular-price">' . wc_price($max) . '</span>' . ' <span class="wholesale-price">' . wc_price($max * $discountRule) . '</span></div>';
			//$priceHTML = wc_price($min) . "-" . wc_price($max);
		}
		//number_format((float)$foo, 2, '.', '');
?>
<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $priceHTML; ?></p>
<?php
	}
	else if($user_meta->roles[0] == 'custom_role_puerto_rico')
	{
		//print_r($getChildren);
		foreach($getChildren as $value)
		{
			$prices[]  = get_post_meta($value, '_regular_price',  true);
		}
		$min = min($prices);
		$max = max($prices);
		//echo $min . "<br>";
		//echo $max . "<br>";
		if($min == $max)
		{
			$priceHTML = '<div class="custom_margin_price"><span class="regular-price min">' . wc_price($min * 1.25) . '</span></span></div>';
		}
		else
		{
			$priceHTML = '<div class="custom_margin_price"><span class="regular-price min">' . wc_price($min * 1.25) . '</span> - <span class="regular-price max">' . wc_price($max * 1.25) . '</span></div>';
			//$priceHTML = wc_price($min) . "-" . wc_price($max);
		}
		?>
		<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $priceHTML; ?></p>
		<?php
	}
	else
	{
	?>
	<!--<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $product->get_price_html(); ?></p>-->
	
	<?php
	
		$user_group_table = _groups_get_tablename( 'user_group' );
		$getGroup = $wpdb->get_row("SELECT `group_id` FROM $user_group_table WHERE `user_id` = '$userID'");
		$getGroupID =  $getGroup->group_id;
		//echo $getGroupID;
		if($getGroupID == 2)
		{
		$product_id = $product->get_id();
		$getChildren = $product->get_children();
		//print_r($getChildren);
		foreach($getChildren as $value)
		{
			$getGroupPrice = $wpdb->get_row("SELECT `price` from {$wpdb->prefix}wusp_group_product_price_mapping WHERE `group_id` = '$getGroupID' AND `product_id` = $value");
			$prices[] = $getGroupPrice->price;
		}
		//print_r($prices);
		$min = min($prices);
		$max = max($prices);
		//echo $min . "<br>";
		//echo $max . "<br>";
		$priceHTML = '<div class="custom_margin_price"><span class="regular-price max">' . wc_price($max) . '</span></div>';
		}
		else
		{
			$priceHTML = $product->get_price_html();
		}
		?>
		<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $priceHTML; ?></p>
		<?php
	}
} 
else
{
?>
<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $product->get_price_html(); ?></p>
<?php
}
