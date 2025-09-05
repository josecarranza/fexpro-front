<?php

 if ( is_admin() ) {
 add_action('admin_menu', 'register_my_custom_submenu_page_img',110);
 }
 function register_my_custom_submenu_page_img() {
    
     add_submenu_page(
       'edit.php?post_type=product',
       "Import images",
       "Import imagens",
       'manage_woocommerce',
       'wi-import-images',
       'wi_woocommerce_extend_init_img'
   );
 }

 function wi_woocommerce_extend_init_img() {
    require("helper.php");
    error_reporting(E_ALL);
    $action=isset($_GET["action"])?$_GET["action"]:"list";
    $id=isset($_GET["id"])?(int)$_GET["id"]:0;
    
    
	switch ($action) {
	
        case "imagenes":
            cargar_imagenes();
            exit;
        break;
       
		case 'list':
		default:
        checklotes_img();
        $lotes = lotes_img_get();
       
		include("views/imagenes.php");
		break;
	}
 
 }

 function lotes_img_get(){
    global $wpdb;
    return $wpdb->get_results("SELECT a.*, b.display_name usuario FROM sys_lote_img a LEFT JOIN ".$wpdb->prefix."users b ON a.id_usuario=b.ID");
}
function checklotes_img(){
    global $wpdb;
    $current_d=date("Y-m-d H:i:s");
    $sql=" UPDATE sys_lote_img a SET STATUS='ERROR DE CARGA' 
    WHERE a.`status`='PROCESANDO' AND a.items_procesados=0 AND (a.`fecha_carga` + INTERVAL 5 MINUTE ) < '".$current_d."'";
    $wpdb->query($sql);
}
function crear_lote_img($nombre_archivo,$ruta,$token,$items=0){
    global $wpdb;

    $lote["nombre_archivo"] = $nombre_archivo;
    $lote["fecha_carga"]    = date("Y-m-d H:i:s");
    $lote["id_usuario"]     = get_current_user_id();
    $lote["items_encontrados"] = $items;
    $lote["status"]   =  "PROCESANDO";
    $lote["ruta_archivo"]   =  $ruta;
    $lote["token"] = $token;

    $wpdb->insert("sys_lote_img",$lote);
    return  $wpdb->insert_id;
}
function scanAllDir($dir) {
    $result = [];
    foreach(scandir($dir) as $filename) {
      if ($filename[0] === '.') continue;
      $filePath = $dir . '/' . $filename;
      if (is_dir($filePath)) {
        foreach (scanAllDir($filePath) as $childFilename) {
          $result[] = $filename . '/' . $childFilename;
        }
      } else {
        $result[] = $filename;
      }
    }
    sort($result);
    return $result;
  }
function cargar_imagenes(){
    set_time_limit(0);
   
    if(isset($_FILES["import"]) && $_FILES["import"]["error"]==0){

        $tmp_name=uniqid();
        $ruta =  get_home_path()."wp-content/uploads/wc-tmp-files/";
        if(!is_dir($ruta)){
            mkdir($ruta);
        }
        $ruta=$ruta.$tmp_name;
        //move_uploaded_file($_FILES["import"]["tmp_name"],$ruta);
        $unzip = new ZipArchive;
        $out = $unzip->open($_FILES["import"]["tmp_name"]);
        if ($out === TRUE) {
        $unzip->extractTo($ruta);
        $unzip->close();
        //echo 'File unzipped';
        } else {
            wp_redirect("edit.php?post_type=product&page=wi-import-images&status=error");
        return;
        }
        $items = scanAllDir($ruta);
        $count_items= count($items);
        $id_lote     = crear_lote_img( $_FILES["import"]["name"],$ruta,$tmp_name,$count_items);

        callcomand_images(get_site_url()."?procesar_lote_imagenes=".$tmp_name);

        sleep(1);
        
        wp_redirect("edit.php?post_type=product&page=wi-import-images&status=success");

    }
}
function init_procesar_lote_img()
{
    if(isset($_GET['procesar_lote_imagenes'])){
        procesar_lote_img($_GET['procesar_lote_imagenes']);
    }
}
add_action('init', 'init_procesar_lote_img');
function get_lote_img_by_token($token){
    global $wpdb;
    $r=$wpdb->get_row( $wpdb->prepare("select * from sys_lote_img a WHERE a.token=%s",$token));
    if(isset($r->id_lote)){
        return $r;
    }else{
        return false;
    }
}
function getsku($ruta,&$is_main=false){
    $fullname=pathinfo($ruta, PATHINFO_FILENAME);
    $sku =  str_replace(array('-back','-side1','-side2'), '', $fullname);
    $is_main = !(bool)preg_match('(-back|-side1|-side2)', $fullname);
    return $sku;
}
function procesar_lote_img($token=""){

    define("PHP_PATH","/opt/plesk/php/7.4/bin/php");
    //define("PHP_PATH","php");
    set_time_limit(0);
    global $wpdb;
    $lote=get_lote_img_by_token($token);
    if($lote!=false){
        $productos_encontrados=array();
        $items = scanAllDir($lote->ruta_archivo );

        $path_plugin = WP_PLUGIN_DIR."/wi-woo-import-images/";
        foreach($items as $img){
            $command=PHP_PATH." ".$path_plugin."cmd_procesar.php \"".$lote->ruta_archivo."/".$img."\"";
          
            //echo $command."<br>";
            $is_main=false;
            $sku=trim(getsku($img,$is_main));
            $id_producto= get_product_by_sku_img($sku);
            if($id_producto==0){
                $wpdb->query("UPDATE sys_lote_img SET no_encontrados=IF(no_encontrados='' OR no_encontrados IS NULL,'".$sku."',CONCAT(no_encontrados,',','".$sku."')) WHERE id_lote=".$lote->id_lote);
                $wpdb->query("update sys_lote_img set items_procesados=(items_procesados+1) WHERE id_lote=".$lote->id_lote);
                continue;
            }
           
            //echo "id_prod".$id_producto."<br>";
            if(!isset($productos_encontrados[$id_producto])){
                $productos_encontrados[$id_producto]=array();
            }
             

            $output="";
            if ( substr(php_uname(), 0, 7) == "Windows" ) {
                //windows
                //echo "window";
                //echo $command;
                $output=exec($command);
            }
            else
            {
                //linux
                $output= shell_exec( $command );
            }
           
            //var_dump($output);
            $attach_id  = (int)$output;
            //echo $attach_id;
            $productos_encontrados[$id_producto][]=$attach_id;

            //error_log($img."|".$sku."\n", 3, $path_plugin."log.txt");
            
            //if(count($productos_encontrados[$id_producto])==1){
            if($is_main){
               
                set_post_thumbnail( $id_producto, $attach_id ); // asignar primera imagen a producto
                //limpiar galeria de producto
                //update_post_meta( $id_producto, '_product_image_gallery', '');
            }else{
                $gale_prev = get_post_meta($id_producto, 'woo_variation_gallery_images',true);
                if($gale_prev=="" || $gale_prev==null){
                    $gale_prev=array();
                }
                /*else{
                    $gale_prev=explode(",",$gale_prev);
                }*/
                
                if(is_array($gale_prev)){
                    $gale_prev[]=$attach_id;
                    update_post_meta( $id_producto, 'woo_variation_gallery_images', $gale_prev);
                }
            }
            
            //echo $attach_id."<br>"; 
            //exit;
            $wpdb->query("update sys_lote_img set items_procesados=(items_procesados+1) WHERE id_lote=".$lote->id_lote);
            @unlink($lote->ruta_archivo."/".$img);
        }
        $lote_upd["id_lote"] =  $lote->id_lote;
        $lote_upd["status"] = "FINALIZADO";
       
        update_lote_img($lote_upd);

        
    }
    
    exit;
}
function get_product_by_sku_img( $sku ) {
    global $wpdb;
    $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT b.ID FROM ".$wpdb->prefix."postmeta a
    INNER JOIN ".$wpdb->prefix."posts b ON a.post_id=b.ID 
    WHERE a.meta_key='_sku' AND a.meta_value='%s' AND b.post_type='product_variation'
    LIMIT 1", $sku ) );
   
    if ( $product_id ) return $product_id;
    return 0;
  }
  function update_lote_img($lote){
    global $wpdb;
    $wpdb->update("sys_lote_img",$lote,array("id_lote"=>$lote["id_lote"]));
}


add_action('wp_ajax_info_lote_img', 'info_lote_img');
add_action('wp_ajax_nopriv_info_lote_img', 'info_lote_img');
function info_lote_img(){
    $lotes=implode(",",$_POST["lotes"]);
    global $wpdb;
    $r=$wpdb->get_results("SELECT id_lote, items_encontrados,items_procesados FROM sys_lote_img WHERE id_lote in (".$lotes.") ");
    
    $r=array_map(function($item){
        $tmp["id_lote"] = $item->id_lote;
        $tmp["procesado"] = $item->items_procesados==0 || $item->items_encontrados ==0 ?0:(int)(($item->items_procesados/$item->items_encontrados)*100);
        return $tmp;
    },$r);
    echo json_encode($r);
    exit;
}
