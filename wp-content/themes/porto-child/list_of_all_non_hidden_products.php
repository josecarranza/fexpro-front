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

//$getAllProducts = get_posts(array('post_type' => 'product_variation', 'post_status' => 'publish', 'posts_per_page' => -1));
$getAllProducts = get_posts(array('post_type' => 'product_variation', 'post_status' => array('publish', 'private'), 'posts_per_page' => -1));
//$getAllFexproProducts = $wpdb->get_results("SELECT `vid` FROM {$wpdb->prefix}show_fexpro_products", ARRAY_A);
//print_r($getAllProducts);
//die();
foreach($getAllProducts as $value)
{
	/* $updateID = $wpdb->get_row( "SELECT `vid` FROM {$wpdb->prefix}show_fexpro_products WHERE vid = $value->ID" );
	if(empty($updateID))
	{ */
		//echo $value->ID . "<br>";
		$getPostParent = $wpdb->get_row( "SELECT `post_parent` FROM {$wpdb->prefix}posts WHERE ID = $value->ID" );
		//echo $getPostParent->post_parent . "<br>";
		
		$merge[$getPostParent->post_parent][] = $value->ID;
		
	/* } */
}

/* echo "<pre>";
print_r($merge);
echo "</pre>";

die(); */
foreach($merge as $key => $v)
{
	$i = 0;
	$j = 0;
	$c = array();
	$d = array();
	foreach($v as $q)
	{
		$updateID = $wpdb->get_row( "SELECT `vid` FROM {$wpdb->prefix}show_fexpro_products WHERE vid = $q" );
		if(empty($updateID))
		{
			//echo $q;
			$c[] = $q;
			$i++;
			//echo "Not Purchased: " . $q . "<br>";
		}
		else
		{
			$d[] = $q;
			$j++;
			echo "Fexpro Purchased: " .$q . "<br>";
			
			/* $wpdb->update( 
				"{$wpdb->prefix}posts", 
				array( 
					'post_status' => 'publish',
				), 
				array( 'ID' => $key ), 
				array( 
					'%s'
				), 
				array( '%d' )
			); */

			$wpdb->update( 
				"{$wpdb->prefix}posts", 
				array( 
					'post_status' => 'publish',
				), 
				array( 'ID' => $q ), 
				array( 
					'%s'
				), 
				array( '%d' )
			);
		}
		
	}
	
	$merge1[$key][0] = $d;
	/* if(empty($c))
	{
		$c = $d;
	} */
	
	$merge1[$key][1] = $c;
	//$merge4[$key][] = $j;
	
}

/* echo "<pre>";
print_r($merge1);
echo "</pre>";

echo "<pre>";
print_r($merge4);
echo "</pre>";
die(); */
//$new_rule = array_merge($merge1, $merge2);
/* echo "<pre>";
print_r($merge1);
echo "</pre>";
 */
/* echo "<pre>";
print_r($merge1);
echo "</pre>";

die(); */
/* foreach($merge1 as $k => $v1)
{
	if(empty($v1[1]))
	{
		echo "Empty: " . $k . "<br>";
		$wpdb->update( 
			"{$wpdb->prefix}posts", 
			array( 
				'post_status' => 'private',
			), 
			array( 'ID' => $k ), 
			array( 
				'%s'
			), 
			array( '%d' ) 
		);
		echo "<hr>";
	}
	else if(count($v1[0]) == count($v1[1]))
	{
		echo "Same Count: " . $k . "<br>";
		$wpdb->update( 
			"{$wpdb->prefix}posts", 
			array( 
				'post_status' => 'private',
			), 
			array( 'ID' => $k ), 
			array( 
				'%s'
			), 
			array( '%d' )
		);
		echo "<hr>";
	}
	else if(count($v1[0]) != count($v1[1]))
	{
		echo "Not Same: " . $k . "<br>";
		
		foreach($v1[1] as $v11)
		{
			echo "Not Same Child: " . $v11 . "<br>";
			$wpdb->update( 
				"{$wpdb->prefix}posts", 
				array( 
					'post_status' => 'private',
				), 
				array( 'ID' => $v11 ), 
				array( 
					'%s'
				), 
				array( '%d' )
			);
		}
		echo "<hr>";
	}
	else
	{
	}
} */