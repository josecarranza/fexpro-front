<?php 
/*
Plugin Name: WI FEXPRO DASHBOARD
Description: WI FEXPRO DASHBOARD
Version: 1.0
Requires PHP: 7.0
Author: Web Info Team
Author URI: https://web-informatica.com/
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} //

define("WI_PLUGIN_FEXPRO_DASHBOARD_PATH",dirname( __FILE__ ));
define("WI_PLUGIN_FEXPRO_DASHBOARD_URL",get_site_url()."/wp-content/plugins/wi-fexpro-dashboard/");

require_once("classes/class-orders-xml.php");

require_once("classes/class-wi-fexpro-dashboard.php");
require_once("model/model-wi-fexpro-dashboard.php");

$wi_fexpro_dashboard = new WiFexproDashboard();