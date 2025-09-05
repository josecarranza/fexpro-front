<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../../wp-load.php');
global $wpdb;

$query = new WC_Order_Query( array(
    'limit' => -1,
    'status' => 'presale4',
    'return' => 'ids',
) );
$orders = $query->get_orders();
/* echo "<pre>";
print_r($orders);
echo "</pre>"; */

$dom = new DOMDocument('1.0','UTF-8');
$dom->formatOutput = true;

$root = $dom->createElement('Sagemobile');
$dom->appendChild($root);
$ab = array(); 
foreach($orders as $order_id)
{
	
	$username = [];
	$userAddress = [];
	$userAddress1 = [];
	//$getMarca = [];
	$getVariationSizes = [];
	
	$i = 1;
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

	//echo $order->get_formatted_billing_full_name();
	
	if($order->get_billing_country() == 'MX')
	{
		continue;
	}
	else
	{
		$result = $dom->createElement('Encabezado');
		$root->appendChild($result);
	
		echo "Panama Orders: " . $order_id . "<br>";
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
		//$getMarca[] = $product->get_attribute( 'pa_brand' );
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
			$newLabel = str_replace(" ", "" , $newLabel);
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
		
			if($dom->save('PED_' . $order_id . '_pre4.xml'))
			{
			   echo "Presale4 Panama Order XML is created please click <a href='". site_url() ."/wp-content/themes/porto-child/orders_fexpro/presale4/PED_" . $order_id . "_pre4.xml' target='_blank'>Click Here " . $order_id . " </a> <br>"; 
			}
			else
			{
				die('XML Create Error');
			}
			$root->removeChild($result);
			$result->removeChild($result1);
	}	
}
