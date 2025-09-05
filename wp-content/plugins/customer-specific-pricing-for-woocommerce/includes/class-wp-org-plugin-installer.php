<?php

namespace WdmCSP;

if (!class_exists('WpOrgPluginInstaller')) {
	/**
	 * A class to install, activate a plugin from the WordPress repository.
	 */
	class WpOrgPluginInstaller {

		/**
		 * Plugin's slug to search in the repository.
		 *
		 * @var null
		 */
		protected $plugin_slug = null;

		/**
		 * Plugin directory to create activate link.
		 *
		 * @var null
		 */
		protected $plugin_dir = null;

		/**
		 * A plugin file to create activate link.
		 *
		 * @var null
		 */
		protected $plugin_file = null;

		/**
		 * Let's initiate the class using plugin details.
		 *
		 * @param string $plugin_slug Plugin's directory slug.
		 * @param string $plugin_dir  Plugin's directory name.
		 * @param string $plugin_file Plugin's main PHP file.
		 */
		public function __construct( $plugin_slug, $plugin_dir, $plugin_file) {
			$this->plugin_slug = $plugin_slug;
			$this->plugin_dir  = $plugin_dir;
			$this->plugin_file = $plugin_file;
		}

		/**
		 * Plugin installation URL.
		 *
		 * @return string URL.
		 */
		public function getInstallUrl() {
			$action = 'install-plugin';
			return wp_nonce_url(
				add_query_arg(
					array(
						'action' => $action,
						'plugin' => $this->plugin_slug
					),
					admin_url('update.php' )
				),
				$action . '_' . $this->plugin_slug
			);
		}

		/**
		 * Plugin activation URL.
		 *
		 * @return string URL.
		 */
		public function getActivateUrl() {
			$action = 'activate';
			return wp_nonce_url(
				add_query_arg(
					array(
						'action' => $action,
						'plugin' => $this->plugin_dir . DIRECTORY_SEPARATOR . $this->plugin_file
					),
					admin_url('plugins.php' )
				),
				$action . '-plugin_' . $this->plugin_dir . DIRECTORY_SEPARATOR . $this->plugin_file
			);
		}

		/**
		 * Plugin installation HTML element.
		 *
		 * @param string $link_text This text is used to link the URL.
		 *
		 * @return string Anchor tag.
		 */
		public function getInstallUrlHtml( $link_text ) {
			$url = $this->getInstallUrl();
			return '<a href="' . esc_url( $url ) . '" target="_blank">' . $link_text . '</a>';
		}

		/**
		 * Plugin activation HTML element.
		 *
		 * @param string $link_text This text is used to link the URL.
		 *
		 * @return string Anchor tag.
		 */
		public function getActivateUrlHtml( $link_text ) {
			$url = $this->getActivateUrl();
			return '<a href="' . esc_url( $url ) . '" target="_blank">' . $link_text . '</a>';
		}
	}
}
