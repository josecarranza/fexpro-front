<?php 
/*
Plugin Name: WI Order Status
Description: WI Order Status
Version: 1.0
Requires PHP: 7.0
Author: Web Info Team
Author URI: https://web-informatica.com/
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} //

define("WI_PLUGIN_ORDERSTATUS_PATH",dirname( __FILE__ ));
//define("WI_PLUGIN_URL",get_site_url()."/wp-content/plugins/wi-quick-easy-purchase/");

require_once("classes/class-wi-order-status.php");

$wi_order_status = new WiOrderStatus();