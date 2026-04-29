<?php if(!defined('ABSPATH')) { exit; } // Exit if accessed directly
/*
Plugin Name: EU VAT Assistant for WooCommerce
Plugin URI: https://aelia.co/shop/eu-vat-assistant-woocommerce/
Description: Assists with EU VAT compliance, for the new VAT regime beginning 1st January 2015.
Author: Aelia
Author URI: https://aelia.co
Version: 2.1.30.260413
Text Domain: woocommerce-eu-vat-assistant
Domain Path: /languages
WC requires at least: 3.5
WC tested up to: 10.7
Requires at least: 5.0
Requires PHP: 7.1
License: GPLv3 (https://www.gnu.org/licenses/gpl-3.0.html)
*/

require_once dirname(__FILE__) . '/src/lib/classes/install/aelia-wc-eu-vat-assistant-requirementscheck.php';
// If requirements are not met, deactivate the plugin
if(Aelia_WC_EU_VAT_Assistant_RequirementsChecks::factory()->check_requirements()) {
	require_once dirname(__FILE__) . '/src/plugin-main.php';

	// Set the path and name of the main plugin file (i.e. this file), for update
	// checks. This is needed because this is the main plugin file, but the updates
	// will be checked from within plugin-main.php
	$GLOBALS['wc-aelia-eu-vat-assistant']->set_main_plugin_file(__FILE__);
}

add_action('before_woocommerce_init', function() {
	if(class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
		// Declare compatibility with Product Instance Caching
		// @since 2.1.28.260202
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('product_instance_caching', __FILE__, true);
	}
});
