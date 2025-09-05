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
			$getCountry = get_post_meta($bk['order_id'], '_billing_country', true);
			if($getCountry == 'MX')
			{
				//$return_array1[$abc['vid']]['MX'][] = $bk['order_item_id'];
				continue;
			}
			else
			{
				$return_array1[$abc['vid']][] = $bk['order_item_id'];
			}
		}
	}
}


foreach($return_array1 as $key3 => $value)
{
	$sum = 0;
	//echo $key ."<br>";
	foreach($value as $abc)
	{
		$c1 = 0;		
		$variation_size = wc_get_order_item_meta( $abc, 'item_variation_size', true );
		$get_variation_id = wc_get_order_item_meta( $abc, '_variation_id', true );
		$ap = wc_get_order_item_meta( $abc, '_qty', true );
		if(empty($ap))
		{
			$ap = 0;
		}
		else
		{
			$ap = $ap;
		}
		if(!in_array($abc, $return_array2))
		{
			if($get_variation_id == $key3)
			{
				//echo $key3 . " - " . $abc . " - " . $ap . "<br>";
				foreach ($variation_size as $key => $size) 
				{
					$c1 += $size['value'];
				}
			}
			array_push($return_array2, $abc);
		}
		$sum += $c1 * $ap; 
	}
	$merge[$key3][] = $sum;
}

/* echo "<pre>";
print_r($merge);
echo "</pre>"; */


foreach($getallOrdersList as $abc1)
{
	if(array_key_exists($abc1['vid'], $merge))
	{
		echo "Exist: " . $abc1['vid'] . "<br>";
		$wpdb->update( 
			"{$wpdb->prefix}factory_order_confirmation_list", 
			array( 
				'forderunits' => $merge[$abc1['vid']][0]
			), 
			array( 'vid' => $abc1['vid'] ), 
			array( 
				'%d',   // value1
			), 
			array( '%d' ) 
		);
	}
	else
	{
		echo "Not Exist: " . $abc1['vid'] . "<br>";
		$wpdb->delete( "{$wpdb->prefix}factory_order_confirmation_list", array( 'vid' => $abc1['vid'] ) );
	}
}