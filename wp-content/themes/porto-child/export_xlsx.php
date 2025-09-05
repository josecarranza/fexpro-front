<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../../wp-load.php");


require_once 'XLSXGen/class_genxlsx.php';

  $filename = 'ecm_order_data' . get_current_user_id() . '.xlsx';
  $xlsx_data = genrate_xlsx_data();
  $xlsx = SimpleXLSXGen::fromArray( $xlsx_data )->downloadAs($filename);



function genrate_xlsx_data()
{
global $woocommerce;
$items = $woocommerce->cart->get_cart();

//print_r($items);

		
	$xlsx_data= array();  
	
        foreach($items as $item => $values) {
		$xlsx_data= array();  			
		if(!array_key_exists("ProductID", $xlsx_data))
		{
			$data['ProductID'] = 'Product ID';
			$data['ProductName'] = 'Product Name';
			$data['ProductSKU'] = 'Product SKU';
			$data['Unitprice'] = 'Unit Price';
			$data['Boxunits'] = 'Box Units';
		}
		if(!array_key_exists("SizeTotal", $xlsx_data))
		{
			$data['SizeTotal'] = 'Size Total';
			$data['Subtotal'] = 'Subtotal';
		}
			foreach ($values['variation_size'] as $key => $size) 
			{
				//$xlsx_data= array(); 
				if (!array_key_exists("Size: " . $size['label'], $xlsx_data))
				{
					$data['Size: ' . $size['label']] = 'Size: ' . $size['label'];
				}				
			}
			
			array_push($xlsx_data, $data);
        }
		
		foreach($items as $item => $values) { 
		$_product =  wc_get_product( $values['data']->get_id()); 
		$c = 0;
		$d = 0;
			$data['ProductID'] = $values['data']->get_id();
			$data['ProductName'] = $_product->get_title();
			$data['ProductSKU'] = $_product->get_sku();
			$data['Unitprice'] = $_product->get_price();
			$data['Boxunits'] = $values['quantity'];
			//print_r($values['variation_size']);
			
			foreach ($values['variation_size'] as $key => $size) 
			{
				
				$data['Size: ' . $size['label']] = $size['value'] * $values['quantity'];
				$c += $size['value']; 				
			}
			$d  = $c * $values['quantity'];
			$data['SizeTotal'] = $c * $values['quantity'];
			$data['Subtotal'] = $d * $_product->get_price();
			array_push($xlsx_data, $data);
        } 
	return $xlsx_data;
}
