<?php if(!defined('ABSPATH')) { exit; } // Exit if accessed directly
/*
Plugin Name: WooCommerce EU VAT Assistant
Plugin URI: https://aelia.co/shop/eu-vat-assistant-woocommerce/
Description: Assists with EU VAT compliance, for the new VAT regime beginning 1st January 2015.
Author: Aelia
Author URI: https://aelia.co
Version: 2.1.17.241007
Text Domain: woocommerce-eu-vat-assistant
Domain Path: /languages
WC requires at least: 3.5
WC tested up to: 9.4
Requires at least: 5.0
Requires PHP: 7.1
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

