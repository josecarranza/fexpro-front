<?php
namespace WisdmPluginUpdates;

/**
 * Manages Customer Specific Pricing for Woocommerce plugin updates on the Plugins screen.
 *
 * @package     CSP/Includes/WisdmPluginUpdates
 * @version     3.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Admin_Plugins_Updates
 */
class Admin_Plugins_Updates {

	/**
	 * The upgrade notice shown inline.
	 *
	 * @var string
	 */
	protected $upgrade_notice = '';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_plugin_update_css' ) );
		add_action( 'in_plugin_update_message-customer-specific-pricing-for-woocommerce/customer-specific-pricing-for-woocommerce.php', array( $this, 'csp_in_plugin_update_message' ), 10, 2 );
	}

	/**
	 * CSP enqueue plugin css.
	 */
	public function enqueue_plugin_update_css() {
		// no need of checking page css will load in as we have already included the class file on plugin.php screen only.
		$css_path = '/css/csp-upgrade-notice.css';
		wp_enqueue_style( 'csp-upgrade-css', CSP_PLUGIN_SITE_URL . $css_path, array(), filemtime( CSP_PLUGIN_PATH . $css_path ) );
	}

	/**
	 * Show plugin update notice under the plugin on plugin listing page.
	 *
	 * @param array    $args Unused parameter.
	 * @param stdClass $response Plugin update response.
	 * @SuppressWarnings("unused")
	 */
	public function csp_in_plugin_update_message( $args, $response ) {
		$this->new_version    = $response->new_version;
		$this->upgrade_notice = $this->get_upgrade_notice( $response->new_version );

		$cur_ver_parts = explode( '.', CSP_VERSION );
		$new_ver_parts = explode( '.', $this->new_version );

		// If user has already moved to the minor version, we don't need to flag up anything.
		if ( version_compare( $cur_ver_parts[0] . '.' . $cur_ver_parts[1], $new_ver_parts[0] . '.' . $new_ver_parts[1], '=' ) ) {
			return;
		}

		/**
		 * Plugin update message.
		 *
		 * @hook [filter] csp_in_plugin_update_message
		 * @since 3.0.1
		 * 
		 * @param string $this->upgrade_notice.
		 */
		echo wp_kses_post( apply_filters( 'csp_in_plugin_update_message', $this->upgrade_notice ? '</p>' . $this->upgrade_notice . '<p class="dummy">' : '' ) );
	}

	/**
	 * Get the upgrade notice from wisdmlabs.com.
	 *
	 * @param  string $version WooCommerce new version.
	 * @return string
	 */
	protected function get_upgrade_notice( $version ) {
		$transient_name = 'csp_upgrade_notice_' . $version;
		$upgrade_notice = get_transient( $transient_name );

		if ( false === $upgrade_notice ) {
			$response = wp_safe_remote_get( 'https://wisdmlabs.com/releases/csp/readme.txt' );

			if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {
				$upgrade_notice = $this->parse_update_notice( $response['body'], $version );
				set_transient( $transient_name, $upgrade_notice, DAY_IN_SECONDS );
			}
		}
		return $upgrade_notice;
	}

	/**
	 * Parse update notice from readme file. Code Adopted from WooCommerce.
	 *
	 * @param  string $content CSP readme file content.
	 * @param  string $new_version WooCommerce new version.
	 * @return string
	 */
	private function parse_update_notice( $content, $new_version ) {
		$version_parts     = explode( '.', $new_version );
		$check_for_notices = array(
			$version_parts[0] . '.0', // Major.
			$version_parts[0] . '.0.0', // Major.
			$version_parts[0] . '.' . $version_parts[1], // Minor.
			$version_parts[0] . '.' . $version_parts[1] . '.' . $version_parts[2], // Patch.
		);
		$notice_regexp     = '~==\s*Upgrade Notice\s*==\s*=\s*(.*)\s*=(.*)(=\s*' . preg_quote( $new_version ) . '\s*=|$)~Uis';
		$upgrade_notice    = '';

		foreach ( $check_for_notices as $check_version ) {
			if ( version_compare( CSP_VERSION, $check_version, '>' ) ) {
				continue;
			}

			$matches = null;
			if ( preg_match( $notice_regexp, $content, $matches ) ) {
				$notices = (array) preg_split( '~[\r\n]+~', trim( $matches[2] ) );

				if ( version_compare( trim( $matches[1] ), $check_version, '=' ) ) {
					$upgrade_notice .= '<p class="csp_plugin_upgrade_notice">';

					foreach ( $notices as $index => $line ) {
						unset( $index );
						$upgrade_notice .= preg_replace( '~\[([^\]]*)\]\(([^\)]*)\)~', '<a href="${2}">${1}</a>', $line );
					}

					$upgrade_notice .= '</p>';
				}
				break;
			}
		}
		return wp_kses_post( $upgrade_notice );
	}

}
new Admin_Plugins_Updates();
