<?php 
/*
Plugin Name: WI Export Order XML SAGE
Description: WI Export Order XML SAGE
Version: 1.0
Requires PHP: 7.0
Author: Web Info Team
Author URI: https://web-informatica.com/
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} //

define("WI_PLUGIN_EXPSAGE_PATH",dirname( __FILE__ ));
define("WI_PLUGIN_EXPSAGE_URL",get_site_url()."/wp-content/plugins/wi-export-xml-sage/");

require_once("classes/class-wi-export-xml-sage.php");

$wi_export_xml_sage = new WiExportXmlSage();