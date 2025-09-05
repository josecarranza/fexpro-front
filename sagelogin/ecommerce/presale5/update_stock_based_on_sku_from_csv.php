<?php
require_once 'include/common.php';
error_reporting(E_ALL);

$row = 1;
$newDataArr = array();
global $wpdb;
$counter=0;

if (($handle = fopen("SageInv.Csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    
      if(strtolower($data[0]) != 'sku'){
          $id =  wc_get_product_id_by_sku( $data[0] );
          if($id){
                $newDataArr[$id] = $data[1];
          }
      }
    
    $counter++;
  }
  fclose($handle);
}

// foreach($newDataArr as $vid => $stock){
//     $stockData = serialize($stock);
// }


echo "<pre>";
print_r($newDataArr);
die;

// foreach($newDataArr as $sku => $stock){
//     global $wpdb;
//     $id =  wc_get_product_id_by_sku( $sku );
//     if($id){
//         $product = wc_get_product( $id );
//         if ( $product != null ) {
//               update_post_meta($id, '_manage_stock', 'yes');
//               update_post_meta($id, '_stock', $stock);
//         }
//     }
// }


// $neweData1 =array();

// foreach($newDataArr as $sku => $stock){
//     global $wpdb;
//     $id =  wc_get_product_id_by_sku( $sku );
//     if($id){
//         $product_id = wp_get_post_parent_id($id);
//         if($product_id != 0){
//             $neweData1[$product_id] = $stock;
//         }
//     }
// }


// if(!empty($neweData1)){
//     foreach($neweData1 as $key => $value){
//         if($value != 0){
//             wc_delete_product_transients( $key );
//             do_action( 'woocommerce_update_product', intval($key) ); 
//         }
//     }
// }


?>