<?php

namespace cspGlobalDiscounts;

if (!class_exists('WisdmGlobalDiscountSettings')) {
	/**
	 * This class contains the functionality of settings for the feature Global Discounts.
	 * * Displays the admin settings page responsible to edit and add global discount rules & settings.
	 * 
	 * * USP - User Specific Pricing
	 * * RSP - Role Specific Pricing
	 * * GSP - Group Specific Pricing
	 */
	class WisdmGlobalDiscountSettings {
		private static $minusIcon;
		private static $plusIcon;
		
		/**
		 * Loads all the elements required to edit/add global discount rules.
		 *
		 * @return void
		 */
		public static function displaySettingsPage() {
			include_once 'class-global-discount-data-store.php';
			self::$minusIcon = plugins_url('/images/minus-icon.png', dirname(dirname(__FILE__)));
			self::$plusIcon  = plugins_url('/images/plus-icon.png', dirname(dirname(__FILE__)));

			self::enqueueStyles();
			self::featureHead();
			// self::featureStatusHtml();
			self::ruleSectionHtml();
			self::enqueueScripts();
		}

		/**
		 * Displays the heading of the settings page.
		 *
		 * @return void
		 */
		public static function featureHead() {
			?>
			<div class="wrap csp-global-discounts-tab"><h3 class="csp-tab-header">
			<?php esc_html_e('Global Discounts', 'customer-specific-pricing-for-woocommerce'); ?></h3>
			<?php
			self::featureStatusHtml();
			?>
			</div>
			<?php
		}
		
		/**
		 * Displays the setting to enable/disable the feature.
		 *
		 * @return void
		 */
		public static function featureStatusHtml() {
			$status = get_option('wdm_csp_gd_status');
			?>
			<!-- Enable Disable Sec -->
			<div class="row feature-switch-row ">
				<div class="feature-switch">
					<div class="row">
						<label class="switch" title="Enable/Disable Feature">
							<input type="checkbox" id="csp-gd-feature-switch" <?php checked($status, 'enable'); ?> >
							<span class="slider round"></span>
						</label>
					</div>
				</div>
				<div class="messages">
					<h4 class="loading-text text-right">
						<?php esc_html_e('Please Wait . . .', 'customer-specific-pricing-for-woocommerce'); ?>
					</h4>
				</div>
			</div>
			<?php
		}


		public static function ruleSectionHtml() {
			?>
			<div class="row csp-gd-main-div-notes" style="display: block;">
				<div class="gd-notes-title"><?php esc_html_e('Notes', 'customer-specific-pricing-for-woocommerce'); ?></div>
				<div class="gd-notes-content">
					<ol>
						<li><?php esc_html_e('Global discounts will be applied on all the products listed on the site.', 'customer-specific-pricing-for-woocommerce'); ?></li>
						<li><?php esc_html_e('If the category discounts feature is enabled, then the product level discounts will be prioratized over the global discounts.', 'customer-specific-pricing-for-woocommerce'); ?></li>
					</ol>
					<ul>
						<li>
							<a href="https://wisdmlabs.com/docs/article/wisdm-customer-specific-pricing/csp-getting-started/csp-user-guide/sitewide-discounts/" target="_blank">
							<?php esc_html_e('Global Discounts'); ?><span class="dashicons dashicons-external" style="font-size: 16px;"></span>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="row csp-gd-main-div" style="display:none;">
				<div class="csp-gd-collapse-wrapper center-block">
					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
						<?php
							self::getHtmlForGlobalUserRules();
							self::getHtmlForGlobalRoleRules();
							self::getHtmlForGlobalGroupRules();
						?>
					</div>
				</div>
				<div class="save-row">
					<div>
						<button class="btn btn-primary save-all-gd-rules">
							<?php esc_html_e('Save All', 'customer-specific-pricing-for-woocommerce'); ?>
						</button>
					</div>
					<div class="save-status">
					</div>
				</div>
				</div>
			<?php
		}
	

		/**
		 * Outputs a collapse section for user specific global discount Rules,
		 * uses method getGdRulesDefinedForUser() to print the user specific rules 
		 * defined for the feature.
		 *
		 * @return void
		 */
		public static function getHtmlForGlobalUserRules() {
			?>
			<div class="panel panel-default" id='users-gd-rule-panel'>
				<div class="panel-heading" id="gd-users">
					<h4 class="panel-title">
						<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
						<?php esc_html_e('User Specific Rules', 'customer-specific-pricing-for-woocommerce'); ?>
						</a>
					</h4>
				</div>
				<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="gd-users">
					<div class="panel-body">
						<?php
							self::getGdRulesDefinedForUser();
						?>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Outputs a collapse section for role specific global discount Rules,
		 * uses method getGdRulesDefinedForUserRoles() to print the role specific
		 * rules defined for the feature.
		 *
		 * @return void
		 */
		public static function getHtmlForGlobalRoleRules() {
			?>
			<div class="panel panel-default" id='roles-gd-rule-panel'>
				<div class="panel-heading" id="gd-roles">
					<h4 class="panel-title">
						<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						<?php esc_html_e('Role Specific Rules', 'customer-specific-pricing-for-woocommerce'); ?>
						</a>
					</h4>
				</div>
				<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="gd-roles">
					<div class="panel-body">
						<?php
							self::getGdRulesDefinedForUserRoles();
						?>
					</div>
				</div>
			</div>
			<?php
		}


		/**
		 * Outputs a collapse section for group specific global discount Rules,
		 * uses method getGdRulesDefinedForUserGroups() to print the group specific
		 * rules defined for the feature.
		 *
		 * @return void
		 */
		public static function getHtmlForGlobalGroupRules() {
			global $cspFunctions;

			?>
			<div class="panel panel-default" id='groups-gd-rule-panel'>
				<div class="panel-heading" id="gd-groups">
					<h4 class="panel-title">
						<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						<?php esc_html_e('Group Specific Rules', 'customer-specific-pricing-for-woocommerce'); ?>
						</a>
					</h4>
				</div>
				<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="gd-groups">
					<div class="panel-body">
						<?php
						if (! $cspFunctions->wdmIsActive('groups/groups.php')) {
							$cspFunctions->showGroupPluginDirectInstallLinks();
						} else {
							self::getGdRulesDefinedForUserGroups();
						}
						?>
					</div>
				</div>
			</div>
			<?php
		}


		/**
		 * Lists html for all the user specific global discount rules.
		 *
		 * @param string $entity
		 * @return void
		 */
		public static function getGdRulesDefinedForUser() {
			$rules = WisdmGlobalDiscountDataStore::getAllRulesFor('USP');
			if (! empty($rules)) {
				//Put a html row for each role present
				foreach ($rules as $aRule) {
					self::putRow('user', $aRule);
				}
			}
			self::putEmptyRowFor('user');
		}

		/**
		 * Lists html for all the user role specific global discount rules.
		 * 
		 * @return void
		 */
		public static function getGdRulesDefinedForUserRoles() {
			$rules = WisdmGlobalDiscountDataStore::getAllRulesFor('RSP');
			if (!empty($rules)) {
				//Put a html row for each role present
				foreach ($rules as $aRule) {
					self::putRow('role', $aRule);
				}
			}
			self::putEmptyRowFor('role');
		}


		/**
		 * Lists html for all the user group specific global discount rules.
		 * 
		 * @return void
		 */
		public static function getGdRulesDefinedForUserGroups() {
			$rules = WisdmGlobalDiscountDataStore::getAllRulesFor('GSP');
			if (!empty($rules)) {
				//Put a html row for each role present
				foreach ($rules as $aRule) {
					self::putRow('group', $aRule);
				}
			}
			self::putEmptyRowFor('group');
		}

		/**
		 * Outputs a row with html input fields containing the
		 * global discount rules
		 *
		 * @param string $type - pricing type
		 * @param array $aRule - global discount rule in array format
		 * @return void
		 */
		public static function putRow( $type = 'user', $aRule = array()) {
			?>
			<div class="gd-rule-row">
				<?php self::entitySelectionDropdown($type, $aRule->type_id); ?>
				<select name="discount-type" tabindex="0">
					<option value="-1" <?php selected($aRule->flat_or_discount_price, '-1'); ?>><?php esc_html_e('Select Pricing Type', 'customer-specific-pricing-for-woocommerce'); ?></option>
					<option value="1" <?php selected($aRule->flat_or_discount_price, '1'); ?>><?php esc_html_e('Flat', 'customer-specific-pricing-for-woocommerce'); ?></option>
					<option value="2" <?php selected($aRule->flat_or_discount_price, '2'); ?>><?php esc_html_e('% Discount', 'customer-specific-pricing-for-woocommerce'); ?></option>
				</select>
				<input type="number" name="min-qty" min=1 step=1 placeholder="<?php esc_html_e('Minimum Quantity', 'customer-specific-pricing-for-woocommerce'); ?>" value=<?php echo esc_html($aRule->min_qty); ?> tabindex="0">
				<input type="number" name="discount-value" placeholder="<?php esc_html_e('Flat Price / % Discount', 'customer-specific-pricing-for-woocommerce'); ?>" min=0 value=<?php echo esc_html($aRule->price); ?> tabindex="0">
				<span class = "add_remove_button">
				<img class="remove_user_row_image" alt="Remove Row" title="Remove Row" tabindex="0" src="<?php echo esc_url(self::$minusIcon); ?>" />
				</span>
			</div>

			<?php
		}

		/**
		 * Outputs a row with empty html input fields.
		 * this method is used to print the last line with the input elements in
		 * every section. 
		 * 
		 * @param string $type - rule type
		 * @return void
		 */
		public static function putEmptyRowFor( $RuleType = 'user') {
			?>
			<div class="gd-rule-row">
				<?php self::entitySelectionDropdown($RuleType, ''); ?>
				<select name="discount-type" tabindex="0">
					<option value="-1" selected><?php esc_html_e('Select Pricing Type', 'customer-specific-pricing-for-woocommerce'); ?></option>
					<option value="1"><?php esc_html_e('Flat', 'customer-specific-pricing-for-woocommerce'); ?></option>
					<option value="2"><?php esc_html_e('% Discount', 'customer-specific-pricing-for-woocommerce'); ?></option>
				</select>
				<input type="number" name="min-qty" min=1 step=1 placeholder="<?php esc_html_e('Minimum Quantity', 'customer-specific-pricing-for-woocommerce'); ?>" value='' tabindex="0">
				<input type="number" name="discount-value" placeholder="<?php esc_html_e('Flat Price/% Discount', 'customer-specific-pricing-for-woocommerce'); ?>" min=0 value='' tabindex="0">
				<span class = "add_remove_button">
						<img class="remove_user_row_image" alt="Remove Row" title="Remove Row" tabindex="0" src="<?php echo esc_url(self::$minusIcon); ?>" />
						<img class='add_new_user_row_image' alt="Add Row" title="Add Row" tabindex="0" src='<?php echo esc_url(self::$plusIcon); ?>'/>
					</span>
			</div>
			<?php
		}



		/**
		 * Outputs a html dropdown menu for the following entities
		 * * Users
		 * * User Roles
		 * * User Groups
		 * 
		 * @param string $entityType		- 'user' | 'role' | 'group'
		 * @param int|string $selectedEntity- userId | role_slug | groupId
		 * @return void
		 */
		public static function entitySelectionDropdown( $entityType, $selectedEntity) {
			switch ($entityType) {
				case 'user':
					self::userSelectionDropdown($selectedEntity);
					break;
				case 'role':
					self::roleSelectionDropdown($selectedEntity);
					break;
				case 'group':
					self::groupSelectionDropdown($selectedEntity);
					break;
				default:
					break;
			}
		}

		/**
		 * Outputs the html dropdown select box with the usernames
		 *
		 * @param string $user - pre-selected user
		 * @return void
		 */
		public static function userSelectionDropdown( $user = '') {
			global $wp_version;
			// Fall back for WordPress version below 4.5.0
			$show_user = 'user_login';
			if ($wp_version >= '4.5.0') {
				$show_user = 'display_name_with_login';
			}
			/**
			 * Filter which can be use to filter the user parameters defined by CSP to generate user dropdowns for CSP rules.
			 * 
			 * @param array $userArgs array of  arguements specifying what user data to fetch. 
			 */
			$userArgs = apply_filters('wdm_usp_user_dropdown_params', array(
				'show_option_all'        => null, // string
				'show_option_none'       => 'Select User', // string
				'hide_if_only_one_author'    => null, // string
				'orderby'            => 'display_name',
				'order'              => 'ASC',
				'include'            => null, // string
				'exclude'            => null, // string
				'multi'              => false,
				'show'               => $show_user,
				'echo'               => false,
				'selected'           => $user,
				'include_selected'       => false,
				'name'               => 'wdm_woo_username[]', // string
				'id'                 => null, // integer
				'class'              => 'user-select gd-type-select', // string
				'blog_id'            => $GLOBALS['blog_id'],
				'who'                => null, // string
				));

			echo wp_dropdown_users($userArgs);
		}


		/**
		 * Outputs the html dropdown select box with the user-roles
		 *
		 * @param string $user - pre-selected user role
		 * @return void
		 */
		public static function roleSelectionDropdown( $roleSlug) {
			?>
			<select class='user-role-select gd-type-select'>
				<option value="-1"><?php esc_html_e('Select Role', 'customer-specific-pricing-for-woocommerce'); ?></option>
			<?php
					$allowedHTMLInDropdown = array(	'select'=>array(
						'name' => true,
						'id' => true,
						'class'=>true
					),
					'option'=>array(
								'value'=>true,
								'selected'=>true
					),
					);

					echo wp_kses(csp_dropdown_roles($roleSlug), $allowedHTMLInDropdown);
					?>
			</select>
			<?php
		}

		/**
		 * Outputs the html dropdown select box with the user-groups
		 *
		 * @param string $user - pre-selected user-group
		 * @return void
		 */
		public static function groupSelectionDropdown( $groupId) {
			global $wpdb;
			$groupIdNamePairs = array();
			if ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->prefix . 'groups_group'))) {
				$groupIdNamePairs = $wpdb->get_results('SELECT group_id, name FROM ' . $wpdb->prefix . 'groups_group');
			}
			?>
			<select class="user-group-select gd-type-select">
			<option value="-1"><?php esc_html_e('Select Group', 'customer-specific-pricing-for-woocommerce'); ?></option>
			<?php
			foreach ($groupIdNamePairs as $aGroupIdNamePair) {
					echo '<option value=' . esc_attr($aGroupIdNamePair->group_id) . ' ' . selected($aGroupIdNamePair->group_id, $groupId) . '>' . esc_html($aGroupIdNamePair->name) . '</option>';
			}
			?>
			</select>
			<?php
		}
	
		/**
		 * Enques the styles required for the Global discounts admin menu page
		 *
		 * @return void
		 */
		public static function enqueueStyles() {
			wp_enqueue_style('csp_bootstrap_css', plugins_url('/css/import-css/bootstrap.css', dirname(dirname(__FILE__))), array(), CSP_VERSION);
			wp_enqueue_style('csp_global_discount_settings_style', plugins_url('includes/global-discount/assets/css/global-discount-settings.css', dirname(dirname(__FILE__))), array(), CSP_VERSION);
		}
	
		/**
		 * Enqueues & localize the scripts required on the Global Discounts admin menu page
		 *
		 * @return void
		 */
		public static function enqueueScripts() {
			wp_enqueue_script('bootstrap_js', plugins_url('/js/import-js/bootstrap.min.js', dirname(dirname(__FILE__))), array('jquery'), CSP_VERSION, true);
			wp_enqueue_script('csp_global_discount_settings_js', plugins_url('includes/global-discount/assets/js/global-discount-validations.js', dirname(dirname(__FILE__))), array('jquery'), CSP_VERSION, true);
			$errorMessages = array(
				'no_valid_rules'			=> __('No valid rules found to save, do you want to remove all the existing global discount rules', 'customer-specific-pricing-for-woocommerce'),
				'save_failed_message'		=> __('Failed to save the rules please try again', 'customer-specific-pricing-for-woocommerce'),
				'request_timeout_message'	=> __('Request timeout occured', 'customer-specific-pricing-for-woocommerce'),
			);
			$globalDiscountSettingsObject = array(
				'ajax_url' 			=> admin_url('admin-ajax.php'),
				'nonce'    			=> wp_create_nonce('wdm-csp-gd'),
				'error_mesages'		=> $errorMessages,
				'save_text'			=> __('Save All', 'customer-specific-pricing-for-woocommerce'),
				'saving_text'		=> __('Saving', 'customer-specific-pricing-for-woocommerce'),
				'save_success_message'=> __('Successfully Updated The Rules', 'customer-specific-pricing-for-woocommerce'),
				'window_close_confirm_message' => __('Are you sure to leave this page ?', 'customer-specific-pricing-for-woocommerce'),
			);
			wp_localize_script('csp_global_discount_settings_js', 'wdm_csp_gd_object', $globalDiscountSettingsObject);
		}
	}
}
