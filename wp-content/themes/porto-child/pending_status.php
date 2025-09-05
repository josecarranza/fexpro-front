<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../wp-load.php');
global $wpdb;
$r1 = array();

$getallOrdersList = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}factory_order_confirmation_list", ARRAY_A );
/* echo "<pre>";
print_r($getallOrdersList);
echo "</pre>"; */

/* $dom = new DOMDocument('1.0','UTF-8');
$dom->formatOutput = true;

$root = $dom->createElement('Sage');
$dom->appendChild($root); */
foreach($getallOrdersList as $value)
{
	if(!in_array($value['forderid'], $r1))
	{
		array_push($r1, $value['forderid']);
	}
}

print_r($r1);

foreach($r1 as $r1value)
{
	$result = $dom->createElement('Confirmacion');
	$root->appendChild($result);
		
	$getallOrdersList1 = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}factory_order_confirmation_list WHERE `forderid` = '$r1value'", ARRAY_A );
	foreach($getallOrdersList1 as $key => $val)
	{
		/* $getFactorySageNumber = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}factory_list WHERE `supplier_name` = %s", $val['factoryname'] ) );		

		if($val['factoryname'] == 'JINJIANG KELUO' || $val['factoryname'] == 'Dishang Group/Weihai Textile Group Import & Export Co,. Ltd')
		{
			$FechaEntrega = '';
			$FechaEmbarque = '';
			$FechaLlegada = '';
			$FechaMuestras = '';
		}
		else
		{
			$FechaEntrega = '15-07-2021';
			$FechaEmbarque = '30-07-2021';
			$FechaLlegada = '30-08-2021';
			$FechaMuestras = '15-05-2021';
		}

		if($val['costprice'] != '')
		{
			$costPrice = $val['costprice'];
		}

		if($getFactorySageNumber->sage_code != '0')
		{
			$supplierCode = $getFactorySageNumber->sage_code;
		}
		else
		{
			$supplierCode = '';
			echo $val['factoryname'] . "<br>";
		}

		$variation = wc_get_product($val['vid']);
		$name = $variation->get_formatted_name();
		//echo $name . "<br>";
		$getSKU = $variation->get_sku();

		$parentSKU = preg_replace ('/\-[^-]*$/', '', $getSKU);
		$colorName = str_replace($parentSKU . "-", "", $getSKU);

		$addColor = explode(' - ', $name);
		$addColor = explode(' ', end($addColor));

		$result->setAttribute('NoConfirm', $r1value);
		$result->setAttribute('Fecha', $FechaEntrega);
		$result->setAttribute('Suplidor', $supplierCode);
		$result->setAttribute('Temporada', "212");
		$result->setAttribute('FechaEntrega', $FechaEntrega);
		$result->setAttribute('FechaEmbarque', $FechaEmbarque);
		$result->setAttribute('FechaLlegada', $FechaLlegada);
		$result->setAttribute('FechaMuestras', $FechaMuestras);

		$result1 = $dom->createElement('Linea');
		$result->appendChild($result1);	

		$result1->setAttribute('Producto', $getSKU);
		$result1->setAttribute('Color', $colorName);
		$result1->setAttribute('Costo', $costPrice);
		$result1->setAttribute('FechaEntrega', $FechaEntrega);
		$result1->setAttribute('FechaLlegada', $FechaLlegada);
		$result1->setAttribute('FechaMuestras', $FechaMuestras);

		$result1->appendChild($dom->createTextNode(''));
		$result->appendChild($result1); */
	}
	/* $newName = strtolower($r1value);
	if($dom->save('CNF_' . $newName . '.xml'))
	{
	   echo "FEXpro Factory XML is created please click <a href='". site_url() ."/wp-content/themes/porto-child/factory_order/CNF_" . $newName . ".xml' target='_blank'>Click Here " . $newName ."</a> <br><hr>"; 
	}
	else
	{
		die('XML Create Error');
	}
		
	$root->removeChild($result); */
}