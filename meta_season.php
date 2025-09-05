<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include("wp-load.php");
    
 global $wpdb;

 $row = 0;
if (($handle = fopen("update_price.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle)) !== FALSE) {
        $num = count($data);
        $row++;
        if($row != 1)
        {
        update_post_meta($data[0], '_regular_price' ,$data[1]);
        update_post_meta($data[0], '_price' ,$data[1]);
		
        echo $data[0];
        echo $data[1];
        }
    }
    fclose($handle);
}
?>