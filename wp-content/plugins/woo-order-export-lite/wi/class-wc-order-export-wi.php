<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class  WC_Order_Export_WI {
    public static $db_option_name="woocommerce-order-export-wi";
    public function __construct() {

        /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/
        

        add_action('wp_ajax_order_exporter_wi', array($this,'method_control'));
        add_action('wp_ajax_nopriv_order_exporter_wi',  array($this,'method_control'));
    }
    public static function get_profiles(){
        $prev_settings=get_option(WC_Order_Export_WI::$db_option_name);
        if(!$prev_settings){
            $prev_settings=array();
        }
        unset($prev_settings["default"]);
        unset($prev_settings[""]);
        return $prev_settings;
    }
    public static function form_config(){
        $wi_profiles = WC_Order_Export_WI::get_profiles();
        $wi_current_profile = isset($_GET["profile"])?$_GET["profile"]:"";
        include("view-wi-settings.php");
    }

    public function method_control(){
        
        $method = isset($_POST["method"]) ? $_POST["method"] : "";
        switch($method){
            case "new_profile":
                $this->save_new_profile();
            break;
            case "save_profile_settings":
                $this->save_profile_settings();
            break;
        }
        
        die();
    }
    function save_new_profile(){
        $prev_settings =WC_Order_Export_WI::get_profiles();
        
    
        $key_config=sanitize_title($_POST["profile_name"]);
        $item_save=array();
        $item_save["name"] = trim($_POST["profile_name"]);
        $item_save["content"] = null;
        $prev_settings[$key_config] = $item_save;

        update_option( WC_Order_Export_WI::$db_option_name, $prev_settings, false );
    }
    function save_profile_settings(){
        $prev_settings =WC_Order_Export_WI::get_profiles();

        $key_config=$_POST["profile"]!=""?$_POST["profile"]:"default";
        if(isset($prev_settings[$key_config])){
            $prev_settings[$key_config]["content"] = urldecode($_POST["content"]);
        }
        

        update_option( WC_Order_Export_WI::$db_option_name, $prev_settings, false );

    }
  
    
}