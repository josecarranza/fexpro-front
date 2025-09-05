<?php 
/*
Plugin Name: WI Update Stocks from SAGE
Description: WI Update Stocks from SAGE
Version: 1.0
Requires PHP: 7.0
Author: Web Info Team
Author URI: https://web-informatica.com/
 */
include("class-sage-inventario.php");

add_action( 'woocommerce_variation_options_pricing', 'variation_stock_future', 10, 3 );
add_action( 'woocommerce_save_product_variation', 'save_variation_stock_future', 10, 2 );
add_filter( 'woocommerce_available_variation', 'load_variation_stock_future' );

function variation_stock_future( $loop, $variation_data, $variation ) {
	woocommerce_wp_text_input(
        array(
            'id'            => "_stock_present{$loop}",
            'name'          => "_stock_present[{$loop}]",
            'value'         => get_post_meta( $variation->ID, '_stock_present', true ),
            'label'         => __( 'Stock Present', 'woocommerce' ),
            'desc_tip'      => true,
            'description'   => __( 'Stock Present.', 'woocommerce' ),
            'wrapper_class' => 'form-row form-row-full',
        )
    );
    woocommerce_wp_text_input(
        array(
            'id'            => "_stock_present_china{$loop}",
            'name'          => "_stock_present_china[{$loop}]",
            'value'         => get_post_meta( $variation->ID, '_stock_present_china', true ),
            'label'         => __( 'Stock Present Logisfashion', 'woocommerce' ),
            'desc_tip'      => true,
            'description'   => __( 'Stock Present Logisfashion', 'woocommerce' ),
            'wrapper_class' => 'form-row form-row-full',
        )
    );
    woocommerce_wp_text_input(
        array(
            'id'            => "_stock_future{$loop}",
            'name'          => "_stock_future[{$loop}]",
            'value'         => get_post_meta( $variation->ID, '_stock_future', true ),
            'label'         => __( 'Stock Future', 'woocommerce' ),
            'desc_tip'      => true,
            'description'   => __( 'Stock Future.', 'woocommerce' ),
            'wrapper_class' => 'form-row form-row-full',
        )
    );
}

function save_variation_stock_future( $variation_id, $loop ) {
	$text_field_p = $_POST['_stock_present'][ $loop ];

    if ( $text_field_p!="" ) {
        update_post_meta( $variation_id, '_stock_present', (int)( $text_field_p ));
    }

    $text_field_p2 = $_POST['_stock_present_china'][ $loop ];

    if ( $text_field_p!="" ) {
        update_post_meta( $variation_id, '_stock_present_china', (int)( $text_field_p2 ));
    }

    $text_field = $_POST['_stock_future'][ $loop ];

    if ( $text_field!="") {
        update_post_meta( $variation_id, '_stock_future',  (int)( $text_field ));
    }

    if($text_field_p!="" || $text_field!="" ){
        $current_presente = (int)get_post_meta( $variation_id, '_stock_present', true );
        $current_future = (int)get_post_meta( $variation_id, '_stock_future', true );
        $current_presente_china = (int)get_post_meta( $variation_id, '_stock_present_china', true );
        $total = $current_presente + $current_future + $current_presente_china;
        update_post_meta( $variation_id, '_stock',  $total);
        if($total>0){
            update_post_meta( $variation_id, '_stock_status',  'instock');
        }
        
        
    }
}

function load_variation_stock_future( $variation ) { 
	$variation['_stock_present'] = get_post_meta( $variation[ 'variation_id' ], '_stock_present', true );   
    $variation['_stock_future']  = get_post_meta( $variation[ 'variation_id' ], '_stock_future', true );
    $variation['_stock_present_china']  = get_post_meta( $variation[ 'variation_id' ], '_stock_present_china', true );
    return $variation;
}



function init_task_get_stocks()
{
    if(isset($_GET['task_get_stocks'])){
        task_get_stocks();
    }
    if(isset($_GET['task_get_stocks_manual'])){
        task_get_stocks_manual();
    }
}
add_action('init', 'init_task_get_stocks');

function task_get_stocks_manual(){
    $url = get_site_url()."?task_get_stocks=1";
    callcomand2($url);
    wp_redirect("wp-admin/edit.php?post_type=product&page=wi-stock-sage");
    exit;
}
function task_get_stocks(){
    date_default_timezone_set("America/Panama");
    set_time_limit(0);

    global $wpdb;
    
    $filename=create_log_file();
    add_log_file($filename,"SKU,Encontrado,Stock,Stock China,Stock Futuro");
    $lote = array();
    $lote["fecha_ejecucion"] = date("Y-m-d H:i:s");
    $lote["items_total"] = 0;
    $lote["status"] = "CONECTANDO SAGE";
    $lote["archivo_log"] = $filename;
    $id_lote = lote_stock_save($lote);

   
    $sage = new SageInventario();
    $xml_str="";
    $inventario = $sage->inventario_get( $xml_str);
    $total_productos = count($inventario);
    @$filename_xml = str_replace(".csv",".xml",$filename);
    @file_put_contents($filename_xml, $xml_str);

    
    $lot_update_1["id_log"] = $id_lote;
    $lot_update_1["items_total"] = $total_productos;
    $lot_update_1["status"] = "PROCESO";
    lote_stock_save($lot_update_1);

 
   
    
    $encontrados=0;
    $no_encontrados=0;

    if($total_productos>0){
        $count=0;
        
    	foreach($inventario as $i => $prod):

		    $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $prod["sku"] ) );
		    if($product_id!=null):

		        $stock  = (int)$prod["stock"];
		        $stock_futuro   = (int)$prod["stock_futuro"];
                $stock_china    = (int)$prod["stock_china"];
                $stock_total = $stock+$stock_futuro+$stock_china;
		        update_post_meta( $product_id, '_stock',$stock_total );
                update_post_meta( $product_id, '_stock_present',$stock );
                update_post_meta( $product_id, '_stock_present_china',$stock_china );
		        update_post_meta( $product_id, '_stock_future',$stock_futuro );
                update_post_meta( $product_id, 'delivery_date',$prod["delivery_date"]);
                if($stock_total>0){
                    wp_update_post( array( 'ID' =>$product_id, 'post_status' => 'publish' ) );
                    update_post_meta( $product_id, '_manage_stock',"yes");
                    update_post_meta( $product_id, '_stock_status',  'instock');
                }
                $encontrados++;
                add_log_file($filename,$prod["sku"].",SI,".$stock.",".$stock_china .",".$stock_futuro);
		    else:
                $no_encontrados++;
                $stock  = (int)$prod["stock"];
                $stock_futuro   = (int)$prod["stock_futuro"];
                $stock_china    = (int)$prod["stock_china"];
		    	add_log_file($filename,$prod["sku"].",NO,".$stock.",".$stock_china.",".$stock_futuro);
		    endif;
            $count++;
            if($count>=100){
                $count=0;
                $lot_update["id_log"] = $id_lote;
                $lot_update["items_encontrados"] = $encontrados;
                $lot_update["items_no_encontrados"] = $no_encontrados;
                lote_stock_save($lot_update);
            }

	    endforeach;
       
    }
    $lot_update["id_log"] = $id_lote;
    $lot_update["items_encontrados"] = $encontrados;
    $lot_update["items_no_encontrados"] = $no_encontrados;
    $lot_update["fecha_finalizacion"] = date("Y-m-d H:i:s");
    $lot_update["status"] = "FINALIZADO";
    lote_stock_save($lot_update);
    exit;
}
function lote_stock_save($lote){
    global $wpdb;
    if(isset($lote["id_log"]) && $lote["id_log"]>0){
        $wpdb->update("sys_log_actualizacion",$lote,array("id_log"=>$lote["id_log"]));
       
        return $lote["id_log"];
    }else{
        $wpdb->insert("sys_log_actualizacion",$lote);
        return $wpdb->insert_id;
    }
    
}
function lote_stock_get($id_lote){
    global $wpdb;
    return $wpdb->get_row($wpdb->prepare("SELECT a.* from sys_log_actualizacion a WHERE a.id_lote=%s",$id_lote));
}
function create_log_file(){
    
    
    $PATH = $base_dir= substr(__DIR__, strlen(ABSPATH))."/logs/".date("Y-m")."/";
  
    if(!is_dir($PATH)){
        mkdir("./".$PATH);
    }
    $filename_base = "sage-stock-".date("Ymd");
    $filename=$filename_base;
    $i=0;
    while(file_exists("./".$PATH.$filename.".csv")){
        $i++;
        $filename = $filename_base."_".$i;
    }
    $filenamefull=$PATH.$filename.".csv";
    file_put_contents($filenamefull, "");
    return $filenamefull;
}
function lotes_get(){
    global $wpdb;
    $sql="select * from sys_log_actualizacion a ORDER BY id_log DESC LIMIT 30";
    return $wpdb->get_results($sql);
}
function add_log_file($filename,$txt){
    file_put_contents("./".$filename, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
}
function checklotes_log(){
    date_default_timezone_set("America/Panama");
    global $wpdb;
    $current_d=date("Y-m-d H:i:s");
    $sql=" UPDATE sys_log_actualizacion a SET STATUS='ERROR DE CARGA' 
    WHERE a.`status`='PROCESANDO' AND a.items_encontrados=0 AND (a.`fecha_carga` + INTERVAL 30 MINUTE ) < '".$current_d."'";
    $wpdb->query($sql);
}

if ( is_admin() ) {
    add_action('admin_menu', 'register_my_custom_submenu_page_stock_sage',110);
    }
    function register_my_custom_submenu_page_stock_sage() {
       
        add_submenu_page(
          'edit.php?post_type=product',
          "Stock Sage",
          "Stock Sage",
          'manage_woocommerce',
          'wi-stock-sage',
          'wi_stock_sage'
      );
    }

    function wi_stock_sage() {
        checklotes_log();

        $lotes = lotes_get();
        include("views/stock-sage-log.php");
     
     }

     function callcomand2($url){
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
    
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "null");
        curl_setopt($ch, CURLOPT_POSTREDIR, 3);
    
    
        curl_exec($ch);
        curl_close($ch);
      }
?>