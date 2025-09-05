<?php 
/*
Plugin Name: WI Collection config
Description: WI Collection config
Version: 1.0
Requires PHP: 7.0
Author: Web Info Team
Author URI: https://web-informatica.com/
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} //

define("WI_PLUGIN_COLL_PATH",dirname( __FILE__ ));
define("WI_PLUGIN_COLL_URL",get_site_url()."/wp-content/plugins/wi-collection-config/");

require_once("classes/class-wi-collection-config.php");

$wi_collection_config = new WiCollectionConfig();