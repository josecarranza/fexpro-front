<?php 
/*
Plugin Name: WI Quick Easy Purchase
Description: WI Quick Easy Purchase
Version: 1.0
Requires PHP: 7.0
Author: Web Info Team
Author URI: https://web-informatica.com/
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} //

define("WI_PLUGIN_PATH",dirname( __FILE__ ));
define("WI_PLUGIN_URL",get_site_url()."/wp-content/plugins/wi-quick-easy-purchase/");

require_once("classes/class-wi-quick-easy-purchase.php");

$wi_quick_easy_purchase = new WiQuickEasyPurchase();