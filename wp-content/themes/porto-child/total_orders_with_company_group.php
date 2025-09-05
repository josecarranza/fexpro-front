<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../wp-load.php');
$return_array = array();
$return_array1 = array();
$return_array2 = array();
$return_array3 = array();
$return_array4 = array();
$return_array5 = array();
$return_array6 = array();
$return_array7 = array();
$return_array8 = array();
$return_array9 = array();
$return_array10 = array();
$return_array11 = array();
$return_array12 = array();
$return_array13 = array();
$return_array14 = array();
$return_array15 = array();
$return_array16 = array();
global $wpdb;


$getallOrdersList = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}factory_order_confirmation_list", ARRAY_A );
//print_r($getZenlineOrdersList);

foreach($getallOrdersList as $abc)
{
	$vID = $abc['vid'];
	$variation = wc_get_product($abc['vid']);
	$variable = substr($variation->get_formatted_name(), 0, strpos($variation->get_formatted_name(), " ("));
	$variable = esc_sql($variable);
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
			
			if(get_post_meta($bk['order_id'], '_billing_company', true) == 'PARIS' && get_post_meta($bk['order_id'], '_billing_country', true) == 'CL')
			{
				$getParis = get_post_meta($bk['order_id'], '_billing_company', true);
				$return_array5[$abc['vid']][$getParis][] = $bk['order_item_id'];
			}
			else if(get_post_meta($bk['order_id'], '_billing_company', true) == 'FALABELLA CHILE')
			{
				$getFallabellaChile = get_post_meta($bk['order_id'], '_billing_company', true);
				$return_array6[$abc['vid']][$getFallabellaChile][] = $bk['order_item_id'];				
			}
			else if(get_post_meta($bk['order_id'], '_billing_company', true) == 'FALABELLA' && get_post_meta($bk['order_id'], '_billing_country', true) == 'PE')
			{
				$getFallabellaPeru = get_post_meta($bk['order_id'], '_billing_company', true) . " " . get_post_meta($bk['order_id'], '_billing_country', true);
				$return_array7[$abc['vid']][$getFallabellaPeru][] = $bk['order_item_id'];
			}
			else if(get_post_meta($bk['order_id'], '_billing_company', true) == 'FALABELLA' && get_post_meta($bk['order_id'], '_billing_country', true) == 'CO')
			{
				$getFallabellaColumbia= get_post_meta($bk['order_id'], '_billing_company', true) . " " . get_post_meta($bk['order_id'], '_billing_country', true);
				$return_array8[$abc['vid']][$getFallabellaColumbia][] = $bk['order_item_id'];
			}
			else
			{
				$getAllCompanyOrder = get_post_meta($bk['order_id'], '_billing_company', true);
				$return_array9[$abc['vid']][$getAllCompanyOrder][] = $bk['order_item_id'];				
			}
			$return_array1[$abc['vid']][] = $bk['order_item_id'];
		}
	}
}


/* echo "<pre>";
print_r($return_array1);
echo "</pre>"; */


foreach($return_array5 as $key67 => $value67)
{
	foreach($value67 as $key89 => $value89)
	{
		foreach($value89 as $finalV)
		{
			$c2 = 0;
			$variation_size = wc_get_order_item_meta( $finalV, 'item_variation_size', true );
			$get_variation_id = wc_get_order_item_meta( $finalV, '_variation_id', true );
			$ap = wc_get_order_item_meta( $finalV, '_qty', true );
	
				if(!in_array($finalV, $return_array10))
				{
					if($get_variation_id == $key67)
					{
						//echo $key3 . " - " . $abc . " - " . $ap . "<br>";
						
						foreach ($variation_size as $key => $size) 
						{
							
							/* if(!in_array($label, $return_array3))
							{
								array_push($return_array3, $label);
							} */
							//echo $key3 . " - " . $size['label'] . " = " . $ap * $size['value'] . "<br>";
							$merge12[$key67]['PARIS - ' . $size['label'] . '#' . $size['value']][] = $ap * $size['value'];
							$merge32['PARIS - ' . $size['label'] . '#' . $size['value']] = $size['label'];
						}
					}
					array_push($return_array10, $finalV);
				}
				
				//$merge32['PARIS - TTY'] = 'TTY Qtys';
		}
	}	
}
$merge32['PARIS - TTY Qtys'] = 'PARIS - TTY Qtys';


foreach($merge12 as $key11 => $value11)
{	
	$fr = 0;
	foreach($value11 as $value111)
	{
		foreach($value111 as $value1114)
		{
			$fr += $value1114;
		}
		//$fr += $value111;
	}
	$value1111[$key11][] = $fr;
}

foreach($value1111 as $key1111 => $value11116)
{
	$merge12[$key1111]['PARIS - TTY Qtys'] = $value11116;
}
/* echo "<pre>";
print_r($merge12);
echo "</pre>"; */

foreach($return_array6 as $key671 => $value671)
{
	foreach($value671 as $key891 => $value891)
	{
		foreach($value891 as $finalV1)
		{
			$variation_size = wc_get_order_item_meta( $finalV1, 'item_variation_size', true );
			$get_variation_id = wc_get_order_item_meta( $finalV1, '_variation_id', true );
			$ap = wc_get_order_item_meta( $finalV1, '_qty', true );
	
				if(!in_array($finalV1, $return_array11))
				{
					if($get_variation_id == $key671)
					{
						//echo $key3 . " - " . $abc . " - " . $ap . "<br>";
						
						foreach ($variation_size as $key => $size) 
						{
							
							/* if(!in_array($label, $return_array3))
							{
								array_push($return_array3, $label);
							} */
							//$merge121[$key671]['FALABELLA CHILE - ' . $size['label'] . '#' . $size['value']][] = $ap * $size['value'];
							if($size['label'] == 'XL')
							{
								$merge321['FALABELLA CHILE - ' . $size['label'] . '#' . $size['value']] = $size['label']. '#' . $size['value'];
								$merge321['FALABELLA CHILE - XXL#4'] = 'XXL#4';
								$merge121[$key671]['FALABELLA CHILE - ' . $size['label'] . '#' . $size['value']][] = $ap * $size['value'];
								$merge121[$key671]['FALABELLA CHILE - XXL#4'][] = $ap * $size['value'];
							}
							else
							{
								if($size['label'] == 'M' || $size['label'] == 'L')
								{
									$sizeValue = $size['value'] - 2;
									$merge321['FALABELLA CHILE - ' . $size['label'] . '#' . $sizeValue] = $size['label']. '#' . $sizeValue;
									$merge121[$key671]['FALABELLA CHILE - ' . $size['label'] . '#' . $sizeValue][] = $ap * $sizeValue;
								}
								else
								{
									$merge321['FALABELLA CHILE - ' . $size['label'] . '#' . $size['value']] = $size['label']. '#' . $size['value'];
									$merge121[$key671]['FALABELLA CHILE - ' . $size['label'] . '#' . $size['value']][] = $ap * $size['value'];
								}
							}								
						}
					}
					array_push($return_array11, $finalV1);
				}
				
				//$merge32['PARIS - TTY'] = 'TTY Qtys';
		}
	}	
}
$merge321['FALABELLA CHILE - TTY Qtys'] = 'FALABELLA CHILE - TTY Qtys';

foreach($merge121 as $key112 => $value112)
{	
	$fr1 = 0;
	foreach($value112 as $value1112)
	{
		foreach($value1112 as $value11142)
		{
			$fr1 += $value11142;
		}
		//$fr += $value111;
	}
	$value11112[$key112][] = $fr1;
}

foreach($value11112 as $key11112 => $value111162)
{
	$merge121[$key11112]['FALABELLA CHILE - TTY Qtys'] = $value111162;
}

/* echo "<pre>";
print_r($merge121);
echo "</pre>"; */

foreach($return_array7 as $key672 => $value672)
{
	foreach($value672 as $key892 => $value892)
	{
		foreach($value892 as $finalV2)
		{
			$c2 = 0;
			$variation_size = wc_get_order_item_meta( $finalV2, 'item_variation_size', true );
			$get_variation_id = wc_get_order_item_meta( $finalV2, '_variation_id', true );
			$ap = wc_get_order_item_meta( $finalV2, '_qty', true );
	
				if(!in_array($finalV2, $return_array12))
				{
					if($get_variation_id == $key672)
					{
						//echo $key3 . " - " . $abc . " - " . $ap . "<br>";
						
						foreach ($variation_size as $key => $size) 
						{
							
							/* if(!in_array($label, $return_array3))
							{
								array_push($return_array3, $label);
							} */
							//echo $key3 . " - " . $size['label'] . " = " . $ap * $size['value'] . "<br>";
							//$merge122[$key672]['FALABELLA PERU - ' . $size['label'] . '#' . $size['value']][] = $ap * $size['value'];
							if($size['label'] == 'XL')
							{
								$merge322['FALABELLA PERU - ' . $size['label'] . '#' . $size['value']] = $size['label'] . '#' . $size['value'];
								$merge322['FALABELLA PERU - XXL#4'] = 'XXL#4';
								$merge122[$key672]['FALABELLA PERU - ' . $size['label'] . '#' . $size['value']][] = $ap * $size['value'];
								$merge122[$key672]['FALABELLA PERU - XXL#4'][] = $ap * $size['value'];
							}
							else
							{
								if($size['label'] == 'M' || $size['label'] == 'L')
								{
									$sizeValue = $size['value'] - 2;
									$merge322['FALABELLA PERU - ' . $size['label'] . '#' . $sizeValue] = $size['label'] . '#' . $sizeValue;
									$merge122[$key672]['FALABELLA PERU - ' . $size['label'] . '#' . $sizeValue][] = $ap * $sizeValue;
								}
								else
								{
									$merge322['FALABELLA PERU - ' . $size['label'] . '#' . $size['value']] = $size['label'] . '#' . $size['value'];
									$merge122[$key672]['FALABELLA PERU - ' . $size['label'] . '#' . $size['value']][] = $ap * $size['value'];
								}
							}
						}
					}
					array_push($return_array12, $finalV2);
				}
				
				//$merge32['PARIS - TTY'] = 'TTY Qtys';
		}
	}	
}
$merge322['FALABELLA PERU - TTY Qtys'] = 'FALABELLA PERU - TTY Qtys';


foreach($merge122 as $key1122 => $value1122)
{	
	$fr = 0;
	foreach($value1122 as $value11122)
	{
		foreach($value11122 as $value111422)
		{
			$fr += $value111422;
		}
		//$fr += $value111;
	}
	$value111122[$key1122][] = $fr;
}

foreach($value111122 as $key111122 => $value1111622)
{
	$merge122[$key111122]['FALABELLA PERU - TTY Qtys'] = $value1111622;
}

foreach($return_array8 as $key673 => $value673)
{
	foreach($value673 as $key893 => $value893)
	{
		foreach($value893 as $finalV3)
		{
			$c2 = 0;
			$variation_size = wc_get_order_item_meta( $finalV3, 'item_variation_size', true );
			$get_variation_id = wc_get_order_item_meta( $finalV3, '_variation_id', true );
			$ap = wc_get_order_item_meta( $finalV3, '_qty', true );
	
				if(!in_array($finalV3, $return_array13))
				{
					if($get_variation_id == $key673)
					{
						//echo $key3 . " - " . $abc . " - " . $ap . "<br>";
						
						foreach ($variation_size as $key => $size) 
						{
							
							/* if(!in_array($label, $return_array3))
							{
								array_push($return_array3, $label);
							} */
							//echo $key3 . " - " . $size['label'] . " = " . $ap * $size['value'] . "<br>";
							
							//$merge123[$key673]['FALABELLA COLUMBIA - ' . $size['label'] . '#' . $size['value']][] = $ap * $size['value'];
							if($size['label'] == 'XL')
							{
								$merge323['FALABELLA COLUMBIA - ' . $size['label'] . '#' . $size['value']] = $size['label'] . '#' . $size['value'];
								$merge323['FALABELLA COLUMBIA - XXL#4'] = 'XXL#4';
								$merge123[$key673]['FALABELLA COLUMBIA - ' . $size['label'] . '#' . $size['value']][] = $ap * $size['value'];
								$merge123[$key673]['FALABELLA COLUMBIA - XXL#4'][] = $ap * $size['value'];
							}
							else
							{
								if($size['label'] == 'M' || $size['label'] == 'L')
								{
									$sizeValue = $size['value'] - 2;
									$merge323['FALABELLA COLUMBIA - ' . $size['label']. '#' . $sizeValue] = $size['label'] . '#' . $sizeValue;
									$merge123[$key673]['FALABELLA COLUMBIA - ' . $size['label'] . '#' . $sizeValue][] = $ap * $sizeValue;
								}
								else
								{
									$merge323['FALABELLA COLUMBIA - ' . $size['label']. '#' . $size['value']] = $size['label'] . '#' . $size['value'];
									$merge123[$key673]['FALABELLA COLUMBIA - ' . $size['label'] . '#' . $size['value']][] = $ap * $size['value'];
								}
							}
						}
					}
					array_push($return_array13, $finalV3);
				}
				
				//$merge32['PARIS - TTY'] = 'TTY Qtys';
		}
	}	
}
$merge323['FALABELLA COLUMBIA - TTY Qtys'] = 'FALABELLA COLUMBIA - TTY Qtys';

foreach($merge123 as $key11222 => $value11222)
{	
	$fr = 0;
	foreach($value11222 as $value111222)
	{
		foreach($value111222 as $value1114222)
		{
			$fr += $value1114222;
		}
		//$fr += $value111;
	}
	$value1111222[$key11222][] = $fr;
}

foreach($value1111222 as $key1111222 => $value11116222)
{
	$merge123[$key1111222]['FALABELLA COLUMBIA - TTY Qtys'] = $value11116222;
}

foreach($return_array9 as $key674 => $value674)
{
	foreach($value674 as $key894 => $value894)
	{
		foreach($value894 as $finalV4)
		{
			$c2 = 0;
			$variation_size = wc_get_order_item_meta( $finalV4, 'item_variation_size', true );
			$get_variation_id = wc_get_order_item_meta( $finalV4, '_variation_id', true );
			$ap = wc_get_order_item_meta( $finalV4, '_qty', true );
	
				if(!in_array($finalV4, $return_array14))
				{
					if($get_variation_id == $key674)
					{
						//echo $key3 . " - " . $abc . " - " . $ap . "<br>";
						
						foreach ($variation_size as $key => $size) 
						{
							
							/* if(!in_array($label, $return_array3))
							{
								array_push($return_array3, $label);
							} */
							//echo $key3 . " - " . $size['label'] . " = " . $ap * $size['value'] . "<br>";
							$merge124[$key674]['FEXPRO - ' . $size['label'] . '#' . $size['value']][] = $ap * $size['value'];
							$merge324['FEXPRO - ' . $size['label'] . '#' . $size['value']] = $size['label'] . '#' . $size['value'];
						}
					}
					array_push($return_array14, $finalV4);
				}
				
				//$merge32['PARIS - TTY'] = 'TTY Qtys';
		}
	}	
}
$merge324['FEXPRO - TTY Qtys'] = 'FEXPRO - TTY Qtys';

foreach($merge124 as $key112222 => $value112222)
{	
	$fr = 0;
	foreach($value112222 as $value1112222)
	{
		foreach($value1112222 as $value11142222)
		{
			$fr += $value11142222;
		}
		//$fr += $value111;
	}
	$value11112222[$key112222][] = $fr;
}

foreach($value11112222 as $key11112222 => $value111162222)
{
	$merge124[$key11112222]['FEXPRO - TTY Qtys'] = $value111162222;
}

$j = 0;
foreach($return_array1 as $key3 => $value3)
{
	$sum = 0;
	foreach($value3 as $key4 => $abc)
	{
		$c1 = 0;
		
			$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
			$get_variation_id = wc_get_order_item_meta( $abc, '_variation_id', true );
			$ap = wc_get_order_item_meta( $abc, '_qty', true );
			if(!empty($ap))
			{
				$ap = $ap;
			}
			else
			{
				$ap = 0;
			}
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
						$merge3[$size['label']] = $size['label'];
					}
					
				}
				array_push($return_array2, $abc);
			}
			
			$sum += $c1 * $ap; 
			
		//echo "<p>" . $key4 . " " . $sum . "</p>";
	}
	// echo $key3 . "<br>";
	// echo "sum: " . $sum . "<br>"; 
	$merge[$key3][] = $sum;
	
} 
/* echo "<pre>";
print_r($merge1);
echo "</pre>"; */
//die();

$getAllSupplier = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}factory_list", ARRAY_A );
$return_array3 = "<select class='factory_name'>";
$return_array3 .= "<option value=''>Select Factory</option>";
foreach($getAllSupplier as $value)
{
	$return_array3 .= "<option value='" . $value['supplier_name'] . "'>" . $value['supplier_name'] . "</option>";
}
$return_array3 .= "</select'>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Fexpro - Purchase content</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
  <script src="jquery_script.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
  tbody#myTable > tr > td:first-child img {
    width: 100px;
	}
	
	span.error {
		color: #f00;
		font-size: 10px;
		display: block;
	}
	.order_screen_container {
		float: left;
		width: 100%;
		margin-bottom: 15px;
		background: red;
		padding: 15px;
	}
	a.submit-it {
		background: black;
		color: #fff;
		padding: .375rem .75rem;
		font-size: 1rem;
		height: calc(1.5em + .75rem + 2px);
		line-height: 1.5;
		width: auto;
		float: right;
	}	
	
	a.single-submit-it {
		background: black;
		color: #fff;
		padding: .375rem .75rem;
		font-size: 1rem;
		height: calc(1.5em + .75rem + 2px);
		line-height: 1.5;		
	}
	.order_screen_container input {
		float: left;
		width: auto;
	}
	tbody#myTable tr td .red {
		border-color: red !important;
	}
	table#demo thead tr.fltrow > td:first-child input, table#demo thead tr.fltrow > th:first-child input, .deliverydate-value, table#demo thead tr.fltrow > th:nth-child(2) input, .cartoon_dimensions-value, .cbms_x_ctn-value, .weight_x_ctn-value, .fabric-value, .comments-add, .pdf-add {
		display: none !important;
	}
	.cart-sizes-attribute {
		min-width: 350px;
		width: 100%;
		margin-top: 20px;    
	}
	.cart-sizes-attribute .size-guide h5 {
    border: solid 1px #000;
	padding: 20px 9px!important;
	}
	.size-guide h5{
    color: #000;
    font-size: 13px;
    font-weight: 700;
    line-height: 20px;
   
    margin-bottom: 0;
   
	}
	.cart-sizes-attribute .size-guide {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
	}

	.inner-size {
		display: block;
		width: 100%;
		-webkit-box-align: center;
		-ms-flex-align: center;
		align-items: center;
		text-align: center;
	}
	.cart-sizes-attribute .size-guide .inner-size {
		border: solid 1px #000;
		border-right: 0;
		border-left: 0;
	}
	.inner-size span:first-child {
		font-weight: bold;
		background: #008188;
		color: #fff;
	}
	.inner-size span {
		display: block;
		width: 100%;
		border-bottom: solid 1px #000;
		border-right: 1px solid #000;
		color: #000;
		padding: 5px 10px;
	}
	span#exportexcel {
		background: #000;
		color: #fff;
		cursor: pointer;
		font-size: 24px;
		text-align: center;
		font-weight: bold;
		margin-bottom: 15px;
		padding: 5px 15px;
		display: inline-block;
		margin-left: 5px;
		border-radius: 5px;
		transition: all 0.2s ease;
	}
	span#exportexcel:hover {
		background: #b41520;
	}
	span#stop-refresh {
		display: none;
		color: #f00;
		font-size: 18px;
		margin-left: 5px;
		margin-bottom: 15px;
		width: 100%;
	}
	.for-Excel-only, .order1-number2, .factory_order_number, .costprice{display: none;}
	input.factory_order_number {
		margin-top: 10px;
	}
	.add-new {
		cursor: pointer;
		background: #000;
		display: inline-block;
		color: #fff;
		padding: 5px;
		border-radius: 5px;
		margin: 10px 0;
	}
	.order1-number2, .only1 {
    text-align: center;
    font-size: 17px;
    color: #f00;
    font-weight: bold;
}
.show .order1-number2{display: block;}
.adding-data:before {
    content: "";
    background: rgba(0,0,0,0.5);
    z-index: 2;
    position: absolute;
    width: 100%;
    height: 100%;
}

table caption{caption-side: top;}


.TF.sticky tr.fltrow th {
    top: -1px !important;
    background: #af0f2c;
}


.TF.sticky th {
    top: 34px !important;
}

body table.TF tr.fltrow th {
    border-bottom: 1px solid #000;
    border-top: 1px solid #000;
    border-left: 1px solid #000;
    border-right: 1px solid #000;
    padding: 0;
    color: #fff;
}
a.submit-in-one {
    position: fixed;
    bottom: 0;
    background: #af0f2c;
    color: #fff;
    padding: 5px;
    right: 0;
    font-size: 18px;
    cursor: pointer;
}
input.factory_order, input.cost-price {
    max-width: 90px;
}
.onumber
{
	display: block;
    min-width: 210px;
    width: 100%;
    text-align: center;
	font-weight: bold;
}
input.delivery-date {
    max-width: 150px;
}
a.single-delete-it {
    background: #af0f2c;
    color: #fff;
    padding: .375rem .75rem;
    font-size: 1rem;
    height: calc(1.5em + .75rem + 2px);
    line-height: 1.5;
}
select.rspg + span {
    display: none;
}
  </style>
</head>
<body>

<h2>All Order Lists</h2>
<!--<div class="order_screen_container">
<input class="form-control" id="myInput" type="text" placeholder="Search..">
<a href="Javascript:void(0);" class="submit-it"><i class="fas fa-save"></i></a>
<span id="exportexcel" onclick="fnExcelReport();">Export to XLSX</span>
</div>-->
<span id="exportexcel" onclick="fnExcelReport();">Export to XLSX</span>
<span id="stop-refresh">Exporting is inprogress. Please don't refresh the page.</span>

<table class="table table-bordered" id="demo">
   <thead>
        <tr>
          <th></th>
		  <th></th>
		  <th style="vertical-align : middle;text-align:center;">Order Number</th>
          <th style="vertical-align : middle;text-align:center;">Product image</th>
          <th style="vertical-align : middle;text-align:center;">Item name</th>
          <th style="vertical-align : middle;text-align:center;">Style sku</th>
		  <th  style="vertical-align : middle;text-align:center;">Gender</th>
		  <th  style="vertical-align : middle;text-align:center;">Category</th>
          <th  style="vertical-align : middle;text-align:center;">Sub-category</th>
          <th style="vertical-align : middle;text-align:center;">Composition</th>
          <th style="vertical-align : middle;text-align:center;">Producto logo</th>
		  <?php 		  
		  foreach ($merge3 as $akkk3 => $akkkv3) 
		  {
			echo '<th style="vertical-align : middle;text-align:center; display: none;">'. $akkk3 .'</th>';
		  }
		  foreach ($merge32 as $akkk32 => $akkkv32) 
		  {
			echo '<th style="vertical-align : middle;text-align:center;">'. $akkk32 .'</th>';
		  }
		  foreach ($merge321 as $akkk321 => $akkkv321) 
		  {
			echo '<th style="vertical-align : middle;text-align:center;">'. $akkk321 .'</th>';
		  }
		  foreach ($merge322 as $akkk322 => $akkkv322) 
		  {
			echo '<th style="vertical-align : middle;text-align:center;">'. $akkk322 .'</th>';
		  }
		  foreach ($merge323 as $akkk323 => $akkkv323) 
		  {
			echo '<th style="vertical-align : middle;text-align:center;">'. $akkk323 .'</th>';
		  }
		  foreach ($merge324 as $akkk324 => $akkkv324) 
		  {
			echo '<th style="vertical-align : middle;text-align:center;">'. $akkk324 .'</th>';
		  }
		  ?>
          <th style="vertical-align : middle;text-align:center;">Unit Sold</th>
          <th style="vertical-align : middle;text-align:center;">Factory Order</th>
          <th style="vertical-align : middle;text-align:center;">Open Units</th>
          <th style="vertical-align : middle;text-align:center;">Factory Name</th>
          <th style="vertical-align : middle;text-align:center;">Carton Dimensions</th>
          <th style="vertical-align : middle;text-align:center;">CBMS X CTN</th>
          <th style="vertical-align : middle;text-align:center;">WEIGHT X CTN</th>
          <th style="vertical-align : middle;text-align:center;">Fabric</th>
          <th style="vertical-align : middle;text-align:center;">Delivery Date</th>
          <th style="vertical-align : middle;text-align:center;">Cost price</th>          
          <th style="vertical-align : middle;text-align:center;">Comments</th>          
          <th style="vertical-align : middle;text-align:center;">PDF</th>          
		  
        </tr>
      </thead>
	<tbody id="myTable">
		<?php 
		$ak = 0;
		foreach($getallOrdersList as $key => $value)
		{
			foreach ($merge3 as $akkk3 => $akkkv3) 
			{
				if(!empty($merge1[$value['vid']]))
				{
					foreach($merge1[$value['vid']] as $ko => $ko1)
					{
						$q1  = 0;
						
						if(	$akkk3 == $ko)
						{
							foreach($ko1 as $ko2 => $ko22)
							{
								$q1 += $ko22;
							}
							$merge67[$value['vid']][$akkk3][] = $q1;
						}
						else					
						{
							$merge67[$value['vid']][$akkk3][] = '';
						}
					}	
				}
			}
		}
		
		foreach($getallOrdersList as $key => $value)
		{
			foreach ($merge32 as $akkk32 => $akkkv32) 
			{
				if(!empty($merge12[$value['vid']]))
				{
					foreach($merge12[$value['vid']] as $ko => $ko1)
					{
						$q1  = 0;
						//print_r($ko1);
						if(	$akkk32 == $ko)
						{
							foreach($ko1 as $ko2 => $ko22)
							{
								$q1 += $ko22;
							}
							$merge671[$value['vid']][$akkk32][] = $q1;
						}
						else					
						{
							$merge671[$value['vid']][$akkk32][] = '';
						}
					}
				}
			}
		}
		/* echo "<pre>";
		print_r($merge671);
		echo "</pre>"; */
		
		foreach($getallOrdersList as $key => $value)
		{
			foreach ($merge321 as $akkk321 => $akkkv321) 
			{
				if(!empty($merge121[$value['vid']]))
				{
					foreach($merge121[$value['vid']] as $ko => $ko1)
					{
						$q1  = 0;
						
						if(	$akkk321 == $ko)
						{
							foreach($ko1 as $ko2 => $ko22)
							{
								$q1 += $ko22;
							}
							$merge6711[$value['vid']][$akkk321][] = $q1;
						}
						else					
						{
							$merge6711[$value['vid']][$akkk321][] = '';
						}
					}	
				}
			}
		}
		
		foreach($getallOrdersList as $key => $value)
		{
			foreach ($merge322 as $akkk322 => $akkkv322) 
			{
				if(!empty($merge122[$value['vid']]))
				{
					foreach($merge122[$value['vid']] as $ko => $ko1)
					{
						$q1  = 0;
						
						if(	$akkk322 == $ko)
						{
							foreach($ko1 as $ko2 => $ko22)
							{
								$q1 += $ko22;
							}
							$merge6712[$value['vid']][$akkk322][] = $q1;
						}
						else					
						{
							$merge6712[$value['vid']][$akkk322][] = '';
						}
					}	
				}
			}
		}
		
		foreach($getallOrdersList as $key => $value)
		{
			foreach ($merge323 as $akkk323 => $akkkv323) 
			{
				if(!empty($merge123[$value['vid']]))
				{
					foreach($merge123[$value['vid']] as $ko => $ko1)
					{
						$q1  = 0;
						
						if(	$akkk323 == $ko)
						{
							foreach($ko1 as $ko2 => $ko22)
							{
								$q1 += $ko22;
							}
							$merge6713[$value['vid']][$akkk323][] = $q1;
						}
						else					
						{
							$merge6713[$value['vid']][$akkk323][] = '';
						}
					}	
				}
			}
		}
		
		foreach($getallOrdersList as $key => $value)
		{
			foreach ($merge324 as $akkk324 => $akkkv324) 
			{
				if(!empty($merge124[$value['vid']]))
				{
					foreach($merge124[$value['vid']] as $ko => $ko1)
					{
						$q1  = 0;
						
						if(	$akkk324 == $ko)
						{
							foreach($ko1 as $ko2 => $ko22)
							{
								$q1 += $ko22;
							}
							$merge6714[$value['vid']][$akkk324][] = $q1;
						}
						else					
						{
							$merge6714[$value['vid']][$akkk324][] = '';
						}
					}	
				}
			}
		}
		
		/* echo "<pre>";
		print_r($merge6711);
		echo "</pre>"; */
		
		foreach($getallOrdersList as $key => $value)
		{
			$_product =  wc_get_product( $value['vid']);
			$productParentId = wp_get_post_parent_id($value['vid']);
			$file = get_field('custom_pdf', $productParentId);
			if(!empty($file))
			{
				$pdf = $file;
				$target = 'target="_blank"';
				$pdf1 = $file;
			}
			else
			{
				$pdf = "Javascript:void(0);";
				$pdf1 = '';
				$target = '';
			}
			//$main_product = wc_get_product( $_product->get_parent_id() );
			($merge[$value['vid']][0] >= $value['forderunits']) ? $alk = $merge[$value['vid']][0] - $value['forderunits'] : $alk = "0";
			
			$image_id			= $_product->get_image_id();
			$gallery_thumbnail 	= wc_get_image_size( array(100, 100) );
			$thumbnail_size    	= apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
			$thumbnail_src     	= wp_get_attachment_image_src( $image_id, $thumbnail_size );
			
			$fabricComposition = get_the_terms( $_product->get_parent_id(), 'pa_fabric-composition' );
			$fabricCompositionString = $fabricComposition[0]->name; //join(', ', wp_list_pluck($fabricComposition, 'name'));
			
			$logoApplication = get_the_terms( $_product->get_parent_id(), 'pa_logo-application' );
			$array_logo = array();
			if(!empty($logoApplication[0]->name)){$array_logo[] = $logoApplication[0]->name;}
			if(!empty($logoApplication[1]->name)){$array_logo[] = $logoApplication[1]->name;}
			if(!empty($logoApplication[2]->name)){$array_logo[] = $logoApplication[2]->name;}
			if(!empty($logoApplication[3]->name)){$array_logo[] = $logoApplication[3]->name;}
			
			$logoApplicationString = implode(', ', $array_logo);
			
			$row3 = "<div class='cart-sizes-attribute'>";
			$row3 .= '<div class="size-guide"><h5>Sizes</h5>';
			if(!empty($merge1[$value['vid']]))
			{
				foreach ($merge1[$value['vid']] as $akkk => $akkkv) {
					$q  = 0;
					$row3 .= "<div class='inner-size'><span>" . $akkk  . "</span>";
					foreach($akkkv as $akkk1 => $akkkv1)
					{
						$q += $akkkv1;
					}
					$row3 .= "<span class='clr_val'>" . $q . "</span>";
					$row3 .= "</div>";
				}
			}	
			$row3 .= "</div>";
			$row3 .= "</div>";
			
			$cat = get_the_terms( $_product->get_parent_id() , 'product_cat' );
			$css_slugGender = array();
			$css_slugCategory = array();
			$css_slugSubCategory = array();
			foreach($cat as $cvalue)
			{
				if($cvalue->parent != 0)
				{
					$term = get_term_by( 'id', $cvalue->parent, 'product_cat' );
					$css_slugSubCategory[] = $cvalue->name;
					$css_slugCategory[] = $term->name;
				}
				else
				{
					$css_slugGender[] = $cvalue->name;
				}
			}
			echo "<tr>";
				echo "<td class='hide-in-excel' style='vertical-align : middle;text-align:center;'><a href='Javascript:void(0);' class='single-submit-it'><i class='fas fa-edit'></i></a></td>";
				echo "<td class='hide-in-excel' style='vertical-align : middle;text-align:center;'><a href='Javascript:void(0);' class='single-delete-it'><i class='fas fa-trash-alt'></i></a></td>";
				echo "<td class='".$_product->get_sku()."' style='vertical-align : middle;text-align:center;'><span class='onumber'>" . $value['forderid'] . "</span></td>";
				echo "<td class='".$_product->get_sku()."'><img src='" . $thumbnail_src[0] . "'/></td>";
				echo "<td class='".$_product->get_sku()."'>" . $_product->get_title() . " - " . $_product->get_attribute( 'pa_color' );
					echo $row3;
				echo "</td>";
				echo "<td class='".$_product->get_sku()."'>" . $_product->get_sku() . "</td>";
				echo "<td class='".$_product->get_sku()."'>" . implode(", ", $css_slugGender) . "</td>";
				echo "<td class='".$_product->get_sku()."'>" . implode(", ", $css_slugCategory) . "</td>";
				echo "<td class='".$_product->get_sku()."'>";
				if(!empty($css_slugSubCategory))
				{
					echo implode(", ", $css_slugSubCategory);
				}
				echo "</td>";
				echo "<td class='".$_product->get_sku()."'>" . $fabricCompositionString . "</td>";
				echo "<td class='".$_product->get_sku()."'>" . $logoApplicationString . "</td>";
				if(!empty($merge67[$value['vid']]))
				{
					foreach($merge67[$value['vid']] as $qw => $qr)
					{
						$fk = 0;
						foreach($qr as $vl)
						{
							if($vl == '')
							{
								continue;
							}
							else
							{
								$fk = $vl;
							}
						}
						if($fk == 0)
						{
							echo "<td class='".$_product->get_sku()."' style='display: none;'></td>";						
						}
						else
						{
							echo "<td class='".$_product->get_sku()."' style='display: none;'>" . $fk . "</td>";						
						}
					}
				}
				else
				{
					 foreach ($merge3 as $akkk3 => $akkkv3) 
				  {
					echo "<td class='".$_product->get_sku()."' style='display: none;'></td>";
				  }
				}
				
				if(!empty($merge671[$value['vid']]))
				{
					foreach($merge671[$value['vid']] as $qw => $qr)
					{
						$fk = 0;
						foreach($qr as $vl)
						{
							if($vl == '')
							{
								continue;
							}
							else
							{
								$fk = $vl;
							}
						}
						if($fk == 0)
						{
							echo "<td class='".$_product->get_sku()."'></td>";						
						}
						else
						{
							echo "<td class='".$_product->get_sku()."' >" . $fk . "</td>";						
						}
					}
				}
				else
				{
					foreach ($merge32 as $akkk32 => $akkkv32) 
					{
						echo "<td class='".$_product->get_sku()."' ></td>";
					}
				}
			
				if(!empty($merge6711[$value['vid']]))
				{
					foreach($merge6711[$value['vid']] as $qw => $qr)
					{
						$fk = 0;
						foreach($qr as $vl)
						{
							if($vl == '')
							{
								continue;
							}
							else
							{
								$fk = $vl;
							}
						}
						if($fk == 0)
						{
							echo "<td class='".$_product->get_sku()."'></td>";						
						}
						else
						{
							echo "<td class='".$_product->get_sku()."' >" . $fk . "</td>";						
						}
					}
				}
				else
				{
					foreach ($merge321 as $akkk321 => $akkkv321) 
					{
						echo "<td class='".$_product->get_sku()."' ></td>";
					}
				}
				
				if(!empty($merge6712[$value['vid']]))
				{
					foreach($merge6712[$value['vid']] as $qw => $qr)
					{
						$fk = 0;
						foreach($qr as $vl)
						{
							if($vl == '')
							{
								continue;
							}
							else
							{
								$fk = $vl;
							}
						}
						if($fk == 0)
						{
							echo "<td class='".$_product->get_sku()."'></td>";						
						}
						else
						{
							echo "<td class='".$_product->get_sku()."' >" . $fk . "</td>";						
						}
					}
				}
				else
				{
					foreach ($merge322 as $akkk322 => $akkkv322) 
					{
						echo "<td class='".$_product->get_sku()."' ></td>";
					}
				}

				if(!empty($merge6713[$value['vid']]))
				{
					foreach($merge6713[$value['vid']] as $qw => $qr)
					{
						$fk = 0;
						foreach($qr as $vl)
						{
							if($vl == '')
							{
								continue;
							}
							else
							{
								$fk = $vl;
							}
						}
						if($fk == 0)
						{
							echo "<td class='".$_product->get_sku()."'></td>";						
						}
						else
						{
							echo "<td class='".$_product->get_sku()."' >" . $fk . "</td>";						
						}
					}
				}
				else
				{
					foreach ($merge323 as $akkk323 => $akkkv323) 
					{
						echo "<td class='".$_product->get_sku()."' ></td>";
					}
				}
				
				if(!empty($merge6714[$value['vid']]))
				{
					foreach($merge6714[$value['vid']] as $qw => $qr)
					{
						$fk = 0;
						foreach($qr as $vl)
						{
							if($vl == '')
							{
								continue;
							}
							else
							{
								$fk = $vl;
							}
						}
						if($fk == 0)
						{
							echo "<td class='".$_product->get_sku()."'></td>";						
						}
						else
						{
							echo "<td class='".$_product->get_sku()."' >" . $fk . "</td>";						
						}
					}
				}
				else
				{
					foreach ($merge324 as $akkk324 => $akkkv324) 
					{
						echo "<td class='".$_product->get_sku()."' ></td>";
					}
				}
				echo "<td class='".$_product->get_sku()."'>" . $merge[$value['vid']][0] . "</td>";
				echo "<td class='".$_product->get_sku()."'><input type='number' name='factory_order' class='factory_order' data-variation_id ='" . $value['vid'] . "' value='" . $value['forderunits'] ."'/><span class='for-Excel-only'>" . $value['forderunits'] . "</span></td>";
				echo "<td class='".$_product->get_sku()."'>" . $alk ." </td>";
				echo "<td class='".$_product->get_sku()."'>" . $return_array3 . " <input type='hidden' /><span class='order1-number2'>" . $value['factoryname'] . "</span></td>";
				echo "<td class='".$_product->get_sku()."'><input type='text' class='cartoon_dimensions' value='" . $value['cartoon_dimensions'] . "'/><span class='cartoon_dimensions-value'>". $value['cartoon_dimensions'] ."</span></td>";
				echo "<td class='".$_product->get_sku()."'><input type='text' class='cbms_x_ctn' value='" . $value['cbms_x_ctn'] . "'/><span class='cbms_x_ctn-value'>". $value['cbms_x_ctn'] ."</span></td>";
				echo "<td class='".$_product->get_sku()."'><input type='text' class='weight_x_ctn' value='" . $value['weight_x_ctn'] . "'/><span class='weight_x_ctn-value'>". $value['weight_x_ctn'] ."</span></td>";
				echo "<td class='".$_product->get_sku()."'><input type='text' class='fabric' value='" . $value['fabric'] . "'/><span class='fabric-value'>". $value['fabric'] ."</span></td>";				
				echo "<td class='".$_product->get_sku()."'><input type='date' class='delivery-date' value='" . $value['deliverydate'] . "'/><span class='deliverydate-value'>". $value['deliverydate'] ."</span></td>";
				echo "<td class='".$_product->get_sku()."'><input type='number' class='cost-price' placeholder='$' value='" . $value['costprice'] . "'/><span class='costprice'>" . $value['costprice'] . "</span></td>";				
				echo "<td class='".$_product->get_sku()."'><textarea class='comments' placeholder='Add comments' l'>" . $value['comments'] . "</textarea><span class='comments-add'>" . $value['comments'] . "</span></td>";	
				echo "<td class='".$_product->get_sku()."'><a href='$pdf' $target>Download</a><span class='pdf-add'>$pdf1</span></td>";				
			echo "</tr>";
		}
		?>
	</tbody>
</table>
<script src="dist/tablefilter/tablefilter.js"></script>
<script src="test-filters-visibility-for-orders.js"></script>

<script>
$(document).ready(function(){  

$( "table thead tr:not(.fltrow) th" ).each(function( index ) {
  if($(this).is(":hidden"))
  {
  console.log( index + ": ");
  //jQuery(".fltrow td [ct='"+index+"']").parent().hide();
  jQuery(".fltrow th [ct='"+index+"']").parent().hide();
  }
});

	jQuery(".order1-number2").each(function() {
		var getThis = jQuery(this).text();
		jQuery(this).parent().find(".factory_name").children("option[value='"+getThis+"']").attr("selected","selected");
	});
	
	jQuery(".factory_order").on('keyup', function() {		
		var getLiveValue = $(this).val();
		var getUnitSoldValue = parseInt($(this).parent().prev().text());
		if(getUnitSoldValue < getLiveValue)
		{
			//$(this).parent().append('<span class="error">Value must be less than Unit Sold Total</span>');
			$(this).parent().next().text("0");
			//$(this).parent().parent().children().last().text("0");
		}
		else
		{
			var getDifference = getUnitSoldValue - getLiveValue;
			$(this).parent().next().text(getDifference);
			//$(this).parent().parent().children().last().text(getDifference);
		}
		$(this).parent().find('span').text(getLiveValue);
	});
	
	jQuery(".factory_name").change(function() {
		var getLiveValue = $(this).val();
		$(this).parent().find('.order1-number2').text(getLiveValue);
	});
	
	jQuery(".delivery-date").change(function() {
		var getLiveValueDate = $(this).val();
		$(this).parent().find('.deliverydate-value').text(getLiveValueDate);
	});
	
	jQuery(".cost-price").on('keyup', function() {
		var getLiveValueCostPrice = $(this).val();
		$(this).parent().find('.costprice').text(getLiveValueCostPrice);
	});
	
	jQuery(".cartoon_dimensions").on('keyup', function() {
		var getLiveValueCostPrice = $(this).val();
		$(this).parent().find('.cartoon_dimensions-value').text(getLiveValueCostPrice);
	});
	
	jQuery(".cbms_x_ctn").on('keyup', function() {
		var getLiveValueCostPrice = $(this).val();
		$(this).parent().find('.cbms_x_ctn-value').text(getLiveValueCostPrice);
	});
	
	jQuery(".weight_x_ctn").on('keyup', function() {
		var getLiveValueCostPrice = $(this).val();
		$(this).parent().find('.weight_x_ctn-value').text(getLiveValueCostPrice);
	});
	
	jQuery(".fabric").on('keyup', function() {
		var getLiveValueCostPrice = $(this).val();
		$(this).parent().find('.fabric-value').text(getLiveValueCostPrice);
	});
	
	jQuery(".comments").on('keyup', function() {
		var getLiveValueCostPrice = $(this).val();
		$(this).parent().find('.comments-add').text(getLiveValueCostPrice);
	});
	
	jQuery(".single-submit-it").on('click', function() {
		
		var form_data = new FormData();		
		var getCurrentRowVariationID = $(this).parent().parent().find("td .factory_order").data('variation_id');
		var getCurrentRowFactoryUnits = $(this).parent().parent().find("td .factory_order").val();
		var getCurrentRowFactoryNameSelect = $(this).parent().parent().find("td select.factory_name:visible option:selected").val();
		var getCurrentRowFactoryNamecartoon_dimensions = $(this).parent().parent().find("td .cartoon_dimensions").val();
		var getCurrentRowFactoryNamecbms_x_ctn = $(this).parent().parent().find("td .cbms_x_ctn").val();
		var getCurrentRowFactoryNameweight_x_ctn = $(this).parent().parent().find("td .weight_x_ctn").val();
		var getCurrentRowFactoryNamefabric = $(this).parent().parent().find("td .fabric").val();
		var getCurrentRowFactoryOrderDate = $(this).parent().parent().find("td .delivery-date:visible").val();
		var getCurrentRowFactoryOrderCost = $(this).parent().parent().find("td .cost-price:visible").val();
		var getCurrentRowFactoryOrdercomments = $(this).parent().parent().find("td .comments").val();
				
		var getCurrentRowClass = $(this).parent().parent();
		var getCurrentRowClass1 = $(this);
		
		console.log(getCurrentRowVariationID);
		console.log(getCurrentRowFactoryUnits);
		console.log(getCurrentRowFactoryNameSelect);
		console.log(getCurrentRowFactoryOrderDate);
		console.log(getCurrentRowFactoryOrderCost);
		
		
		/* if(getCurrentRowFactoryUnits == '')
		{
			$(this).parent().parent().find("td .factory_order").addClass('red');
		}
		else
		{
			$(this).parent().parent().find("td .factory_order").removeClass('red');
		}
		
		if(getCurrentRowFactoryOrderDate == '')
		{
			$(this).parent().parent().find("td .delivery-date:visible").addClass('red');
		}
		else
		{
			$(this).parent().parent().find("td .delivery-date:visible").removeClass('red');
		}
		
		if(getCurrentRowFactoryNameSelect == '')
		{
			$(this).parent().parent().find("td select.factory_name").addClass('red');
		}
		else
		{
			$(this).parent().parent().find("td select.factory_name").removeClass('red');
		}
		
		if(getCurrentRowFactoryOrderCost == '')
		{
			$(this).parent().parent().find("td .cost-price").addClass('red');
		}
		else
		{
			$(this).parent().parent().find("td .cost-price").removeClass('red');
		} */
		
		
		/* if(getCurrentRowFactoryUnits == '' || getCurrentRowFactoryOrderDate == '' || getCurrentRowFactoryNameSelect == '' || getCurrentRowFactoryOrderCost == '')
		{
			alert("Data selection is incomplete");
		}
		else
		{ */
			
			form_data.append('getCurrentRowVariationID', getCurrentRowVariationID);
			form_data.append('getCurrentRowFactoryUnits', getCurrentRowFactoryUnits);
			form_data.append('getCurrentRowFactoryNameSelect', getCurrentRowFactoryNameSelect);
			form_data.append('getCurrentRowFactoryNamecartoon_dimensions', getCurrentRowFactoryNamecartoon_dimensions);
			form_data.append('getCurrentRowFactoryNamecbms_x_ctn', getCurrentRowFactoryNamecbms_x_ctn);
			form_data.append('getCurrentRowFactoryNameweight_x_ctn', getCurrentRowFactoryNameweight_x_ctn);
			form_data.append('getCurrentRowFactoryNamefabric', getCurrentRowFactoryNamefabric);
			form_data.append('getCurrentRowFactoryOrderDate', getCurrentRowFactoryOrderDate);
			form_data.append('getCurrentRowFactoryOrderCost', getCurrentRowFactoryOrderCost);
			form_data.append('getCurrentRowFactoryOrdercomments', getCurrentRowFactoryOrdercomments);
			
			form_data.append('action', 'edit_factory_data');
			
			$.ajax({
				type: "POST",
				url: "https://shop.fexpro.com/wp-admin/admin-ajax.php",
				contentType: false,
				processData: false,
				data: form_data,
				beforeSend: function() {

				},
				success:function(data) {
					console.log(data);
				},
				error: function(errorThrown){
					console.log(errorThrown);
					console.log('No update');
				}
			});
		/* } */
	});
	jQuery(".single-delete-it").on('click', function() {
		var form_data = new FormData();		
		var getCurrentRowVariationStyleName = $(this).parent().parent().find("td:eq(3)").text();
		var getCurrentRowVariationID = $(this).parent().parent().find("td .factory_order").data('variation_id');
		var getCurrentRowClass = $(this).parent().parent();
		
		form_data.append('getCurrentRowVariationID', getCurrentRowVariationID);	
		form_data.append('action', 'delete_single_factory_data');	
		
		var txt;
		var r = confirm("Are you sure you want to delete this style SKU: " + getCurrentRowVariationStyleName);
		if (r == true) {
		console.log("pressed OK!");
		
			$.ajax({
				type: "POST",
				url: "https://shop.fexpro.com/wp-admin/admin-ajax.php",
				contentType: false,
				processData: false,
				data: form_data,
				beforeSend: function() {

				},
				success:function(data) {
					console.log(data);
					getCurrentRowClass.find("td .factory_order").parent().parent().remove();
				},
				error: function(errorThrown){
					console.log(errorThrown);
					console.log('No update');
				}
			});
		} else {
		console.log("pressed Cancel!");
		}
	});
});

function fnExcelReport()
{
    var SITEURL = "<?php echo site_url(); ?>/wp-content/themes/porto-child/";
	var form_data = new FormData();   
	var myArray = [];
	var myArray1 = [];
	var myArrayImage2 = [];
	var data = {};
	var tab = document.getElementById('myTable');
	var i=0, k=0;
	
	jQuery( 'table#demo thead > tr:nth-child(2) th:not(:first-child):not(:nth-child(2))' ).each(function() {
			myArray.push(jQuery(this).text());		
	});
	console.log(myArray);
	form_data.append('getHeaderArray', JSON.stringify(myArray));
	console.log(tab.rows);
	for(i = 0 ; i < tab.rows.length ; i++) 
    {     
		if(tab.rows[i].getAttribute("style") == 'display: none;')
		{	
			continue;
		}
		else
		{
			// console.log(tab.rows[j]);
			
			//console.log(tab.rows[j].innerHTML);
			for(k = 0 ; k < tab.rows[i].cells.length ; k++) 
			{
				if(tab.rows[i].cells[k].innerHTML.indexOf("pdf-add") != -1)
				{
					var abc689 = tab.rows[i].cells[k].innerHTML.split('<span class="pdf-add">');
					//console.log(abc689);
					var res689 = abc689[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  ''
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("uploads") != -1)
				{
					var abc = tab.rows[i].cells[k].innerHTML.split("https://shop.fexpro.com");
					var res = abc[1].replace('">', "");
					//myArray1.push(res);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("single-submit-it") != -1)
				{
					continue;
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("single-delete-it") != -1)
				{
					continue;
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("for-Excel-only") != -1)
				{
					var abc4 = tab.rows[i].cells[k].innerHTML.split('<span class="for-Excel-only">');
					var res4 = abc4[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res4
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("order1-number2") != -1)
				{
					var abc5 = tab.rows[i].cells[k].innerHTML.split('<span class="order1-number2">');
					var res5 = abc5[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res5
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("deliverydate-value") != -1)
				{
					var abc2 = tab.rows[i].cells[k].innerHTML.split('<span class="deliverydate-value">');
					var res2 = abc2[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res2
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("onumber") != -1)
				{
					var abc7 = tab.rows[i].cells[k].innerHTML.split('<span class="onumber">');
					var res7 = abc7[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res7
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("costprice") != -1)
				{
					var abc6 = tab.rows[i].cells[k].innerHTML.split('<span class="costprice">');
					var res6 = abc6[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res6
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("cartoon_dimensions-value") != -1)
				{
					var abc66 = tab.rows[i].cells[k].innerHTML.split('<span class="cartoon_dimensions-value">');
					var res66 = abc66[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res66
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("cbms_x_ctn-value") != -1)
				{
					var abc67 = tab.rows[i].cells[k].innerHTML.split('<span class="cbms_x_ctn-value">');
					var res67 = abc67[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res67
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("weight_x_ctn-value") != -1)
				{
					var abc68 = tab.rows[i].cells[k].innerHTML.split('<span class="weight_x_ctn-value">');
					var res68 = abc68[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res68
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("fabric-value") != -1)
				{
					var abc69 = tab.rows[i].cells[k].innerHTML.split('<span class="fabric-value">');
					var res69 = abc69[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res69
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("comments-add") != -1)
				{
					var abc60 = tab.rows[i].cells[k].innerHTML.split('<span class="comments-add">');
					var res60 = abc60[1].replace('</span>', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res60
					});
				}
				else if(tab.rows[i].cells[k].innerHTML.indexOf("cart-sizes-attribute") != -1)
				{
					var abc3 = tab.rows[i].cells[k].innerHTML.split('<div class="cart-sizes-attribute"');
					var res3 = abc3[0].replace('<div class="cart-sizes-attribute"', "");
					//myArray1.push(res2);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  res3
					});
				}
				else
				{
					//myArray1.push(tab.rows[i].cells[k].innerHTML);
					myArray1.push({
						'Title': tab.rows[i].cells[k].getAttribute("class"), 
						'data':  tab.rows[i].cells[k].innerHTML
					});
				}
			}
			//tab.rows[j].innerHTML;
		}
    } 

	
	
	//console.log(tab);
	console.log(myArray1);
	form_data.append('getBodyArray', JSON.stringify(myArray1));
	form_data.append('action', 'export_cart_entries1');
	jQuery.ajax({
		type: "POST",
		url: "https://shop.fexpro.com/wp-admin/admin-ajax.php",
		contentType: false,
		processData: false,
		data: form_data,
		beforeSend: function() {
			jQuery('#exportexcel').text('Creating XLSX File');
			jQuery('#stop-refresh').show();
		},
		success:function(msg) {
			console.log(msg);	
			jQuery('#exportexcel').text('Data Exported');
			setTimeout(function() {
				jQuery('#exportexcel').text('Export Cart in XLSX');
			},500);
			jQuery('#stop-refresh').hide();
			var data = JSON.parse(msg);
			window.open(SITEURL+"orders/"+data.filename, '_blank');
		},
		error: function(errorThrown){
			console.log(errorThrown);
			console.log('No update');
		}
	});
}
</script>

</body>
</html>
