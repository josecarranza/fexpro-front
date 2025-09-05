<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../../wp-load.php');
global $wpdb;
$r1 = array();
$return_array1 = array();
$return_array2 = array();

$getallOrdersList = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}pop_factory_order_confirmation_list WHERE `forderunits` != '0'", ARRAY_A );
/* echo "<pre>";
print_r($getallOrdersList);
echo "</pre>"; */

$dom = new DOMDocument('1.0','UTF-8');
$dom->formatOutput = true;

$root = $dom->createElement('Sage');
$dom->appendChild($root);
foreach($getallOrdersList as $value)
{
	if(!in_array($value['forderid'], $r1))
	{
		array_push($r1, $value['forderid']);
	}
	
	$vID = $value['vid'];
	$allData = $wpdb->get_results("SELECT `order_item_id`,`order_id`   FROM {$wpdb->prefix}wc_order_product_lookup WHERE `variation_id` = '$vID'", ARRAY_A );
	//print_r($allData);
	//$akp = array_unique($allData);
	foreach($allData as $bk)
	{
		if ( get_post_status ( $bk['order_id'] ) == 'wc-cancelled' || get_post_status ( $bk['order_id'] ) == 'trash') 
		{
			continue;
		}
		else
		{
			//$return_array1[$vID][] = $bk['order_item_id'];
			$getCountry = get_post_meta($bk['order_id'], '_billing_country', true);
			if($getCountry != 'MX')
			{
				//$return_array1[$vID][] = $bk['order_item_id'];
				continue;
			}
			else
			{
				$return_array1[$vID][] = $bk['order_item_id'];
				//continue;
			}
		}
	}
}

//print_r($return_array1);

foreach($return_array1 as $key3 => $value3)
{
	$sum = 0;
	foreach($value3 as $key4 => $abc)
	{
		$c1 = 0;
		
			$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
			$get_variation_id = wc_get_order_item_meta( $abc, '_variation_id', true );
			$ap = wc_get_order_item_meta( $abc, '_qty', true );
			/* if(!empty($ap))
			{
				$ap = $ap;
			}
			else
			{
				$ap = 0;
			} */
			if(!in_array($abc, $return_array2))
			{
				if($get_variation_id == $key3)
				{
					//echo $key3 . " - " . $abc . " - " . $ap . "<br>";
					
					foreach ($variation_size as $key => $size) 
					{
						
						$c1 += $size['value'];
						/* if(!in_array($label, $return_array3))
						{
							array_push($return_array3, $label);
						} */
						//echo $key3 . " - " . $size['label'] . " = " . $ap * $size['value'] . "<br>";
						$merge1[$key3][$size['label']][] = $ap * $size['value'];
						//$merge3[$size['label']] = $size['label'];
					}
					
				}
				array_push($return_array2, $abc);
			}
			
			//$sum += $c1 * $ap; 
			
		//echo "<p>" . $key4 . " " . $sum . "</p>";
	}
	//$merge[$key3][] = $sum;
	
}


/* echo "<pre>";
print_r($merge1);
echo "</pre>";
die(); */

foreach($r1 as $r1value)
{
	$result = $dom->createElement('Confirmacion');
	$root->appendChild($result);
		
	$getallOrdersList1 = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}pop_factory_order_confirmation_list WHERE `forderid` = '$r1value' AND `forderunits` != '0'", ARRAY_A );
	foreach($getallOrdersList1 as $key => $val)
	{
		$getFactorySageNumber = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}factory_list WHERE `supplier_name` = %s", $val['factoryname'] ) );		

		if($val['factoryname'] == 'JINJIANG KELUO' || $val['factoryname'] == 'Dishang Group/Weihai Textile Group Import & Export Co,. Ltd')
		{
			$FechaEntrega = '';
			$FechaEmbarque = '';
			$FechaLlegada = '';
			$FechaMuestras = '';
		}
		else
		{
			$FechaEntrega = '30/09/2021';
			$FechaEmbarque = '05/10/2021';
			$FechaLlegada = '30/10/2021';
			$FechaMuestras = '30/09/2021';
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

		$result->setAttribute('Cia', "08");
		$result->setAttribute('NoConfirm', $r1value);
		$result->setAttribute('Fecha', $FechaEntrega);
		$result->setAttribute('Suplidor', $supplierCode);
		$result->setAttribute('Temporada', "212");
		$result->setAttribute('FechaEntrega', $FechaEntrega);
		$result->setAttribute('FechaEmbarque', $FechaEmbarque);
		$result->setAttribute('FechaLlegada', $FechaLlegada);
		$result->setAttribute('FechaMuestras', $FechaMuestras);

		
		if($merge1[$val['vid']])
		{
			$result1 = $dom->createElement('Linea');
			$result->appendChild($result1);	
			
			$result1->setAttribute('Producto', $parentSKU . $colorName);
			$result1->setAttribute('Color', $colorName);
			$q1  = 0;
			
				foreach ($merge1[$val['vid']] as $akkk => $akkkv) 
				{
					$newLabel = str_replace("/", "-" , $akkk);
					$newLabel = str_replace(" ", "" , $newLabel);
					$q  = 0;
					foreach($akkkv as $akkk1 => $akkkv1)
					{
						$q += $akkkv1;
						$q1 += $akkkv1;
					}
					$result1->setAttribute('size_' . $newLabel, $q);
				}
			
					
			$result1->setAttribute('Total_Box_Qty', $q1);
			$result1->setAttribute('Costo', $costPrice);
			$result1->setAttribute('FechaEntrega', $FechaEntrega);
			$result1->setAttribute('FechaLlegada', $FechaLlegada);
			$result1->setAttribute('FechaMuestras', $FechaMuestras);
			

			$result1->appendChild($dom->createTextNode(''));
			$result->appendChild($result1);
		}
	}
	$newName = strtolower($r1value);
	if($dom->save('CNF_' . $newName . '_pop_MEX.xml'))
	{
	   echo "Fexpro POP Factory Mexico XML is created please click <a href='". site_url() ."/wp-content/themes/porto-child/factory_order/pop/CNF_" . $newName . "_pop_MEX.xml' target='_blank'>Click Here " . $newName ."</a> <br><hr>"; 
	} 
	else
	{
		die('XML Create Error');
	}
		
	$root->removeChild($result);
}