<?php 
/*
Plugin Name: WI MODAL MESSI
Description: WI MODAL MESSI
Version: 1.0
Requires PHP: 7.0
Author: Web Info Team
Author URI: https://web-informatica.com/
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} //

function modal_messi($atts=[]){
    
    $url_imagen = isset($atts['url_imagen'])? $atts['url_imagen']:"";
    $url_imagen_ad = isset($atts['url_imagen_ad'])? $atts['url_imagen_ad']:"";
    $link = isset($atts['link'])?$atts['link']:'';
    ob_start();
    include("shortcode-modal-messi.php");
    return ob_get_clean();
}

add_shortcode('modal_messi', 'modal_messi');