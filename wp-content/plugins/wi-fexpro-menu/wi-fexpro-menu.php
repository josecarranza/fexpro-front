<?php 
/*
Plugin Name: WI FEXPRO MENU
Description: WI FEXPRO MENU
Version: 1.0
Requires PHP: 7.0
Author: Web Info Team
Author URI: https://web-informatica.com/
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} //

define("WI_PLUGIN_FEXPRO_MENU_PATH",dirname( __FILE__ ));
define("WI_PLUGIN_FEXPRO_MENU_URL",get_site_url()."/wp-content/plugins/wi-fexpro-menu/");

require_once("models/model-wi-fexpro-menu.php");
require_once("classes/class-wi-fexpro-menu.php");

$wi_fexpro_menu = new WiFexproMenu();