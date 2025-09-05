<?php
if (!defined('EDD_WCSP_STORE_URL')) {
	define('EDD_WCSP_STORE_URL', 'https://wisdmlabs.com/check-license/');
}

if (!defined('EDD_WCSP_ITEM_NAME')) {
	define('EDD_WCSP_ITEM_NAME', 'Customer Specific Pricing for WooCommerce');
}

add_action('plugins_loaded', 'cspLoadLicense', 5);
add_action('plugins_loaded', 'cspUpdateCheck');



/**
 * Sets global variable of plugin data
 */
function cspLoadLicense() {
	global $wdmPluginDataCSP;
	$wdmPluginDataCSP = include_once 'license.config.php';
	require_once 'licensing/class-wdm-license.php';
	new \Licensing\WdmLicense($wdmPluginDataCSP);
}

/**
 * Update tables for CSP if plugin is updated
 *
 * @global array $wdmPluginDataCSP array of CSP Plugin data.
 */
function cspUpdateCheck() {
	global $wdmPluginDataCSP;

	$get_plugin_version = get_option($wdmPluginDataCSP['pluginSlug'] . '_version', false);

	if (false === $get_plugin_version || $get_plugin_version !== $wdmPluginDataCSP['pluginVersion']) {
		 \WdmWuspInstall\WdmWuspInstall::createTables();
		 update_option('csp-update-status', 'updated_and_notice_undismissed');
		 update_option($wdmPluginDataCSP['pluginSlug'] . '_version', $wdmPluginDataCSP['pluginVersion']);
	}
}


if (!function_exists('wisdmCSPShowLicenceNotice')) {
	/**
	 * This function returns the html code for the license renewal notice
	 *
	 * @since 4.6.0
	 * @param string $messageHead Notification heading.
	 * @param string $message Notification message body.
	 * @param string $buttonText Text to be displayed on the action button in the notification.
	 * @param string $link Link for the action button in the notification default to wisdmlabs.com
	 * @return void
	 */
	function wisdmCSPShowLicenceNotice( $messageHead, $message, $buttonText, $link = 'https://wisdmlabs.com/') {
		$wisdmLogo = plugin_dir_url(CSP_PLUGIN_FILE) . 'images/wisdmlabs_logo.png';	
		wp_enqueue_style('csp-common-notice-styles');
		include plugin_dir_path(CSP_PLUGIN_FILE) . 'templates/license-notice.php';
	}
}	


if (!function_exists('cspLicenseNotice')) {
	/**
	 * This function checks for the Licence validity of a plugin & displays a
	 * notice with a renewal link for the expired license key
	 *
	 * @since 4.6.0
	 * @return void
	 * */
	function cspLicenseNotice() {
		global $wdmPluginDataCSP, $pagenow;
		$cspPage          = isset($_GET['page'])?sanitize_text_field($_GET['page']):'';
		$status 		  = get_option('edd_csp_license_status', null);
		if (empty($wdmPluginDataCSP['pluginSlug']) || in_array($status, array('no_activations_left', 'deactivated', 'invalid', null), true)) {
			//License Not Added
			$buttonLink  = get_admin_url(null, 'admin.php?page=wisdmlabs-licenses', null );
			$messageHead = __('You have not activated the license for "WisdmLabs Customer Specific Pricing".', 'customer-specific-pricing-for-woocommerce');
			$message     = __('Please activate your license to keep getting feature updates & premium support.', 'customer-specific-pricing-for-woocommerce');
			$buttonText  = __('Activate License', 'customer-specific-pricing-for-woocommerce');
			if ($cspPage=='wisdmlabs-licenses') {
				return;
			}
			wisdmCSPShowLicenceNotice($messageHead, $message, $buttonText, $buttonLink);
			return;
		}
		$pluginSlug       = trim($wdmPluginDataCSP['pluginSlug']);
		$licenseTransient = 'wdm_' . $pluginSlug . '_license_trans';
		$licenseData      = get_option($licenseTransient, false);
	
		if (empty($licenseData['value']) || 'expired'!==trim(str_replace('"', '', $licenseData['value']))) {
			return;
		}
	
	
		$buttonLink  = get_option('wdm_' . $pluginSlug . '_product_site', false);
		$messageHead = __('Your License For "Customer Specific Pricing" Has Expired', 'customer-specific-pricing-for-woocommerce');
		$message     = __('renew your license today, to keep getting feature updates & premium support', 'customer-specific-pricing-for-woocommerce');
		$buttonText  = __('Renew License', 'customer-specific-pricing-for-woocommerce');
		wisdmCSPShowLicenceNotice($messageHead, $message, $buttonText, $buttonLink);
		
	}
}
