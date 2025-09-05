<?php
/**
 * Description tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

global $post;

$heading = apply_filters( 'woocommerce_product_description_heading', __( 'Description:&nbsp;', 'woocommerce' ) );

//echo $post->ID;
$customProduct = wc_get_product( $post->ID );

$stringCountry_of_origin = $customProduct->get_attribute( 'pa_country-of-origin' );
$stringFabric_composition = $customProduct->get_attribute( 'pa_fabric-composition' );
$stringLogo_Application = $customProduct->get_attribute( 'pa_logo-application' );
if ( is_user_logged_in() ) {      
	$user = wp_get_current_user(); // getting & setting the current user 
	if($user->roles[0] == 'custom_role')
	{
		$stringdelivery_date = $customProduct->get_attribute( 'pa_paris-delivery-date' );
	}
	else if($user->roles[0] == 'custom_role_fexpro_pop_dominican')
	{
		$stringdelivery_date = $customProduct->get_attribute( 'pa_delivery-date' );
		$stringdelivery_date = str_replace("August 30 2021, ","",$stringdelivery_date);	
	}
	else
	{
		$stringdelivery_date = $customProduct->get_attribute( 'pa_delivery-date' );
		$stringdelivery_date = str_replace(", SEPTEMBER 30 2021","",$stringdelivery_date);	
	}
}
$stringport_of_loading = $customProduct->get_attribute( 'pa_port-of-loading' );
$stringcbm_per_master_ctn = $customProduct->get_attribute( 'pa_cbm-per-master-ctn' );
$stringcbm_per_composicion = $customProduct->get_attribute( 'pa_composicion' );
$stringSingleProductBarcode = get_post_meta($post->ID, '_custom_barcode_field_simple_product', true);

//merge-more-information-in-description-sku-description-country_of_origin-fabric_compostion
echo "<div class='m-m-i-i-d-s-d-c-f'>";
echo "<div class='m-m-i-i-d-s-d-c-f-content'>";
echo "<h5 class='m-m-i-i-d-s-d-c-f-content-sku'><span>" . esc_html__( 'SKU: ', 'woocommerce' ) . "</span>" . $customProduct->get_sku() . "</h5>";

echo "<div class='m-m-i-i-d-s-d-c-f-content-description'>";
if ( $heading ) : ?>
	<h5><span><?php echo esc_html( $heading ); ?></span></h5>
<?php endif; ?>

<?php the_content(); 

echo "</div>";
if(!empty($stringCountry_of_origin))
{
echo "<h5 class='m-m-i-i-d-s-d-c-f-content-country_of_origin'><span>" . esc_html__( 'Country of origin: ', 'woocommerce' ) . "</span>" . $stringCountry_of_origin . "</h5>";
}
if(!empty($stringFabric_composition))
{
echo "<h5 class='m-m-i-i-d-s-d-c-f-content-fabric_compostion'><span>" . esc_html__( 'Fabric composition: ', 'woocommerce' ) . "</span>" . $stringFabric_composition . "</h5>";
}
if(!empty($stringLogo_Application))
{
echo "<h5 class='m-m-i-i-d-s-d-c-f-content-logo_application'><span>" . esc_html__( 'Logo application: ', 'woocommerce' ) . "</span>" . $stringLogo_Application . "</h5>";
}
if(!empty($stringdelivery_date))
{
echo "<h5 class='m-m-i-i-d-s-d-c-f-content-logo_application'><span>" . esc_html__( 'Delivery Date: ', 'woocommerce' ) . "</span>" . $stringdelivery_date . "</h5>";
}
if(!empty($stringport_of_loading))
{
echo "<h5 class='m-m-i-i-d-s-d-c-f-content-logo_application'><span>" . esc_html__( 'Port of loading: ', 'woocommerce' ) . "</span>" . $stringport_of_loading . "</h5>";
}
if(!empty($stringcbm_per_master_ctn))
{
echo "<h5 class='m-m-i-i-d-s-d-c-f-content-logo_application'><span>" . esc_html__( 'CBM per Master CTN: ', 'woocommerce' ) . "</span>" . $stringcbm_per_master_ctn . "</h5>";
}
if(!empty($stringcbm_per_composicion))
{
echo "<h5 class='m-m-i-i-d-s-d-c-f-content-logo_application'><span>" . esc_html__( 'Composición: ', 'woocommerce' ) . "</span>" . $stringcbm_per_composicion . "</h5>";
}
if(!empty($stringSingleProductBarcode))
{
echo "<h5 class='m-m-i-i-d-s-d-c-f-content-logo_application'><span>" . esc_html__( 'Barcode: ', 'woocommerce' ) . "</span>" . $stringSingleProductBarcode . "</h5>";
}
echo "</div></div>"; 
//echo str_replace(",","",number_format("7.45E+12"));
?>



