<?php 
function wi_woo_import_images_install(){
    global $wpdb;

 
    $r=$wpdb->get_row("SHOW TABLES LIKE 'sys_lote_img'");
    if($r==null){
      $create_q="
        CREATE TABLE `sys_lote_img` (
            `id_lote` INT(11) NOT NULL AUTO_INCREMENT,
            `nombre_archivo` VARCHAR(50) NOT NULL,
            `fecha_carga` DATETIME NOT NULL,
            `id_usuario` INT(11) NOT NULL,
            `cantidad` INT(11) NOT NULL DEFAULT '0',
            `items_encontrados` INT(11) NOT NULL DEFAULT '0',
            `items_procesados` INT(11) NOT NULL DEFAULT '0',
            `status` VARCHAR(50) NOT NULL,
            `ruta_archivo` VARCHAR(100) NOT NULL,
            `token` VARCHAR(100) NOT NULL,
            PRIMARY KEY (`id_lote`)
        )
        ENGINE=InnoDB
        AUTO_INCREMENT=1
      ";
      $wpdb->query($create_q);
    }
}

?>