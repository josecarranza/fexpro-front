<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../wp-load.php');
global $wpdb;

//delete brand

$deleteTableData = "DELETE FROM wp_fw_22";
$wpdb->query( $deleteTableData );
$regenerateId = "ALTER TABLE wp_fw_22 AUTO_INCREMENT = 1";
$wpdb->query( $regenerateId );

//Delete wp_ss22_bulk_filter_brand_custom_filter table data 
$deleteTableData1 = "DELETE FROM wp_ss22_bulk_filter_brand_custom_filter";
$wpdb->query( $deleteTableData1 );
$regenerateId1 = "ALTER TABLE wp_ss22_bulk_filter_brand_custom_filter AUTO_INCREMENT = 1";
$wpdb->query( $regenerateId1 );



$get_total_records = $wpdb->get_results("SELECT {$wpdb->prefix}posts.ID FROM {$wpdb->prefix}posts  LEFT JOIN {$wpdb->prefix}term_relationships ON (wp_posts.ID = {$wpdb->prefix}term_relationships.object_id) 
	WHERE 1=1  AND {$wpdb->prefix}posts.post_type IN ( 'product') AND ({$wpdb->prefix}posts.post_status = 'publish' OR {$wpdb->prefix}posts.post_status = 'private') GROUP BY {$wpdb->prefix}posts.ID ORDER BY {$wpdb->prefix}posts.post_date ASC " );
$brandArr=array();
$counter=0;

$product_cat_term_arr = array();
$pa_color_arr_list = array();
$color_arr = array();
$cat_arr = array();
foreach($get_total_records as $key => $data_record){
    $product_id =  $data_record->ID;
   
	$cat_arr = array();
    if( has_term( 'fall-winter-22' , 'product_cat' ,  $product_id) ){



            $product_cat_term_arr = wp_get_post_terms( $product_id, 'product_cat', array('hide_empty' => true) );
            foreach($product_cat_term_arr as $dataValue){
                if(!in_array($dataValue->term_id, $cat_arr)){
                    array_push($cat_arr,$dataValue->term_id );
                }
                
            }

          
            $product = wc_get_product($data_record->ID);
            $get_brand= get_the_terms($data_record->ID,'pa_brand');
            $brandArr[$get_brand[0]->term_id] = $get_brand[0]->name; 


            $variations = $product->get_available_variations();
            $variations_ids = wp_list_pluck( $variations, 'variation_id' );

            
            foreach($variations_ids  as $k_data => $v_data){
                $pa_team_arr_list = get_post_meta($v_data, 'product_team', true);
                $pa_season_arr_list = get_post_meta($v_data, 'pa_season', true);
                $pa_color_arr_list = get_post_meta($v_data,'attribute_pa_color',true);


                $variable_product_ids[$v_data] = array('brand_id'=>$get_brand[0]->term_id, 'cat_arr' => implode(",",$cat_arr), 'color_arr' => strtoupper($pa_color_arr_list), 'term_arr' => $pa_team_arr_list , 'season_arr' => $pa_season_arr_list, 'product_id' => $product_id);
            }

        

    }

    

}


// print_r($variable_product_ids);
// die;
   
if(!empty($brandArr)){
    foreach($brandArr as $brandKey => $brandVal){
        $sql = $wpdb->prepare("INSERT INTO wp_fw_22 (`term_id`, `name`) values (%d, %s)", $brandKey, $brandVal);
        $wpdb->query($sql);
    }
}

if(!empty($variable_product_ids)){

    foreach($variable_product_ids as $key1 => $value){
      
        $wp_sql = $wpdb->prepare("INSERT INTO wp_ss22_bulk_filter_brand_custom_filter (`v_id`, `brand_id`, `cat_id`, `color_id`, `team`, `season`,`p_id`) values (%d, %d, %s, %s, %s, %s, %d)", $key1, $value['brand_id'], $value['cat_arr'], $value['color_arr'], $value['term_arr'], $value['season_arr'], $value['product_id']  );
        $wpdb->query($wp_sql);
    }
}

echo "Both Table Update Success fully";


?>