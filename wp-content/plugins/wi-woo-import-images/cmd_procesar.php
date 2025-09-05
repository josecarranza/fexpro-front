<?php

set_time_limit(0);
ini_set("memory_limit", "-1");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('WP_USE_THEMES', false);
global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header;
require( './wp-load.php');
require_once( ABSPATH . 'wp-admin/includes/image.php' );
//$upload_dir = wp_upload_dir();
function subir_imagen($imagen){
    $image_url = $imagen;
   
    
    $upload_dir = wp_upload_dir();
    
    $image_data = file_get_contents( $image_url );
    
    $filename = basename( $image_url );
    
    if ( wp_mkdir_p( $upload_dir['path'] ) ) {
      $file = $upload_dir['path'] . '/' . $filename;
    }
    else {
      $file = $upload_dir['basedir'] . '/' . $filename;
    }
    
    file_put_contents( $file, $image_data );
    
    $wp_filetype = wp_check_filetype( $filename, null );
    
    $attachment = array(
      'post_mime_type' => $wp_filetype['type'],
      'post_title' => sanitize_file_name( $filename ),
      'post_content' => '',
      'post_status' => 'inherit'
    );
    
    $attach_id = wp_insert_attachment( $attachment, $file );
    
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    wp_update_attachment_metadata( $attach_id, $attach_data );
    return $attach_id;
}



$file=isset($argv[1])?$argv[1]:"";
//sleep(3);
$id=subir_imagen($file);
echo $id;

?>