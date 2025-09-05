<?php 
/*
Plugin Name: WI Woocommerce Import Images
Description: WI Woocommerce Import Images
Version: 1.0
Requires PHP: 7.0
Author: Web Info Team
Author URI: https://web-informatica.com/
 */
include("wi-install.php");

register_activation_hook(__FILE__,'wi_woo_import_images_install');

include("wi-imagenes.php");
?>