<?php
if(!defined('ABSPATH')) { exit; } // Exit if accessed directly

require_once('aelia-wc-requirementscheck.php');

/**
 * Checks that plugin's requirements are met.
 */
class Aelia_WC_EU_VAT_Assistant_RequirementsChecks extends Aelia_WC_RequirementsChecks {
	// @var string The namespace for the messages displayed by the class.
	protected $text_domain = 'wc-aelia-eu-vat-assistant';
	// @var string The plugin for which the requirements are being checked. Change it in descendant classes.
	protected $plugin_name = 'WooCommerce EU VAT Assistant';

	// @var array An array of PHP extensions required by the plugin
	protected $required_extensions = array(
		'curl',
	);

	/**
	 * The minimum version of PHP required by the plugin.
	 *
	 * @var string
	 * @since 2.0.19.210629
	 */
	protected $required_php_version = '7.1';

	// @var array An array of WordPress plugins (name => version) required by the plugin.
	protected $required_plugins = array(
		'WooCommerce' => '3.5',
		'Aelia Foundation Classes for WooCommerce' => array(
			'version' => '2.1.1.201208',
			'extra_info' => 'You can get the plugin <a href="https://bit.ly/WC_AFC_S3">from Aelia</a>, free of charge.',
			'autoload' => true,
			'url' => 'https://bit.ly/WC_AFC_S3',
		),
	);

	/**
	 * Factory method. It MUST be copied to every descendant class, as it has to
	 * be compatible with PHP 5.2 and earlier, so that the class can be instantiated
	 * in any case and and gracefully tell the user if PHP version is insufficient.
	 *
	 * @return Aelia_WC_AFC_RequirementsChecks
	 */
	public static function factory() {
		return new self();
	}

	// { WordPress.org-Feature-Start }
	/**
	 * Indicates if the standalone AFC is being installed. In such case,
	 * the embedded framework should not be loaded, or it could cause a
	 * conflict during the installation.
	 *
	 * @return bool
	 * @since 1.9.3.181217
	 */
	protected function skip_embedded_framework() {
		if(isset($_REQUEST['action']) && isset($_REQUEST['plugin'])) {
			// Activation or deletion of AFC via standard WordPress link on Plugins page
			if(in_array($_REQUEST['action'], array('activate', 'delete-plugin')) && (strpos($_REQUEST['plugin'], 'wc-aelia-foundation-classes.php') !== false)) {
				return true;
			}

			// Installation or activation of AFC via Aelia's Ajax functions
			if(($_REQUEST['plugin'] === 'aelia-foundation-classes-for-woocommerce') &&
					((strpos($_REQUEST['action'], 'install_plugin_') === 0) ||
					(strpos($_REQUEST['action'], 'activate_plugin_') === 0))) {
				return true;
			}
		}
		return false;
	}
	// { WordPress.org-Feature-End }

	// { WordPress.org-Feature-Start }
	/**
	 * Alters the requirements checking logic to allow the plugin to load an
	 * "embedded" version of the Aelia Foundation Classes. Required by the
	 * WordPress.org guidelines.
	 *
	 * @param bool $autoload_plugins
	 * @since 1.9.1.181209
	 */
	protected function check_required_plugins($autoload_plugins = true) {
		parent::check_required_plugins($autoload_plugins);

		// Skip the loading of the embedded framework while the standalone
		// AFC is being installed or plugins are modified (installed, deleted).
		// This will prevent conflicts arising during the installation or
		// removal of the AFC
		// @since 1.9.3.181217
		if($this->skip_embedded_framework()) {
			return;
		}

		if(!empty($this->plugin_actions)) {
			foreach($this->plugin_actions as $plugin_name => $action) {
				// Set AFC "plugin" directory, to allow loading its CSS and JS files
				// from the correct location when the embedded framework is used
				if(!defined('AFC_PLUGIN_DIR')) {
					define('AFC_PLUGIN_DIR', 'woocommerce-eu-vat-assistant/src/embedded-framework/wc-aelia-foundation-classes-embedded');
				}

				// If the AFC is missing, use the "embedded" framework. If the AFC is
				// present, but inactive, then the plugin will ask the Administrator
				// to activate it instead
				if(($plugin_name === 'Aelia Foundation Classes for WooCommerce')) {
					// Try to load the "embedded" AFC. If successful, remove the
					// "AFC missing" message and configure the AFC to run as an
					// embedded framework
					if(@include_once(__DIR__ . '/../../../embedded-framework/wc-aelia-foundation-classes-embedded/wc-aelia-foundation-classes.php')) {
						// Disable the AFC Updaters. They can't run properly when the AFC is loaded
						// as a local framework
						if(!defined('DISABLE_AFC_UPDATERS')) {
							define('DISABLE_AFC_UPDATERS', true);
						}

						// Remove the" missing requirement" message
						unset($this->plugin_actions[$plugin_name]);
						unset($this->requirements_errors[$plugin_name]);
					};

				}
			}
		}
	}
	// { WordPress.org-Feature-End }

	/**
	 * Performs requirement checks
	 *
	 * @return bool
	 * @since 2.0.18.210622
	 */
	public function check_requirements() {
		$result = parent::check_requirements();

		if($result) {
			// If the requirements are met, check if the Aelia VAT Assistant is also installed. The two plugins
			// can't be used together
			// @since 2.0.18.210622
			$aelia_vat_assistant_info = $this->get_wp_plugin_info('Aelia VAT Assistant for WooCommerce');

			// If the Currency Switcher version is 4.11.0.210517 or higher, prevent the Shipping Pricing addon from
			// loading and show the notice to indicate that the Currency Switcher includes it out of the box
			if(!empty($aelia_vat_assistant_info)) {
				$result = false;
				add_action('admin_notices', array($this, 'plugin_retired_notice'));
			}
		}
		return $result;
	}

	/**
	 * Displays a "plugin retired" notice, to inform administrators that the plugin is now
	 * replaced by the Aelia VAT Assistant and should be uninstalled.
	 *
	 * @since 2.0.18.210622
	 */
	public function plugin_retired_notice(): void {
		?>
		<div class="wc_aelia message error fade">
			<h3 class="wc_aeliamessage_header" style="margin: 1em 0 0 0"><?php
				echo wp_kses_post(__('The WooCommerce EU VAT Assistant has been replaced by the "Aelia VAT Assistant for WooCommerce"', $this->text_domain));
			?></h3>
			<p class="info"><?php
				echo wp_kses_post(implode(' ', array(
					__('It looks like the plugin "Aelia VAT Assistant for WooCommerce" is installed on your site.', $this->text_domain),
					__('That is a premium solution that replaces the original, free WooCommerce EU VAT Assistant.', $this->text_domain),
					sprintf(__('Please <a href="%2$s" target="_blank">go to the Plugins page</a>, then disable and remove plugin "%1$s".', $this->text_domain), $this->plugin_name, admin_url('/plugins.php', true)),
					__('Its features will remain available, as they are included in the Aelia VAT Assistant for WooCommerce.', $this->text_domain),
				)));
			?></p>
		</div>
		<?php
	}
}
