<?php
namespace Aelia\WC\EU_VAT_Assistant;
if(!defined('ABSPATH')) { exit; } // Exit if accessed directly

use \WC_Tax;

/**
 * Implements a class that will render the settings page.
 */
class Settings_Renderer extends \Aelia\WC\Settings_Renderer {
	// @var string The URL to the support portal.
	const SUPPORT_URL = 'https://aelia.freshdesk.com/support/home';
	// @var string The URL to the contact form for general enquiries.
	const CONTACT_URL = 'https://aelia.co/contact/';

	/*** Settings Tabs ***/
	const TAB_CHECKOUT = 'checkout';
	const TAB_SELF_CERTIFICATION = 'self-certification';
	const TAB_CURRENCY = 'currency';
	const TAB_SALES = 'sales';
	const TAB_REPORTS = 'reports';
	const TAB_OPTIONS = 'options';
	const TAB_LINKS = 'links';
	const TAB_SUPPORT = 'support';
	// @since 1.11.0.191108
	const TAB_VAT_NUMBER_VALIDATION = 'var-number-validation';
	// External services, e.g. VAT number validators
	// @since 2.0.1.201215
	const TAB_SERVICES = 'services';
	// Invoices settings, e.g. integration with the PDF Invoices & Packing Slips plugin
	// @since 2.0.1.201215
	// TODO Implement the content of this tab, or remove it if not needed
	const TAB_INVOICES = 'invoices';

	/*** Settings sections ***/
	const SECTION_CHECKOUT = 'checkout';
	const SECTION_SELF_CERTIFICATION = 'self_certification';
	const SECTION_CURRENCY = 'currency';
	const SECTION_EXCHANGE_RATES_UPDATE = 'exchange_rates_update';
	const SECTION_EXCHANGE_RATES = 'exchange_rates';
	const SECTION_REPORTS = 'reports';
	const SECTION_SALE_RESTRICTIONS = 'sale_restrictions';
	const SECTION_VIES_VALIDATION = 'vies_validation';
	const SECTION_OPTIONS = 'options';
	const SECTION_LINKS = 'links';
	const SECTION_DEBUG = 'debug';
	const SECTION_SUPPORT = 'support_section';

	// Privacy
	// @since 2.0.4.201231
	const SECTION_PRIVACY = 'privacy';

	// VAT Layer services settings
	// @since 2.0.1.201215
	const SECTION_SERVICES = 'services';

	// @since 1.11.0.191108
	const SECTION_VIES_VALIDATION_OPTIONS = 'vies_validation_options';

	/**
	 * Transforms an array of currency codes into an associative array of
	 * currency code => currency description entries. Currency labels are retrieved
	 * from the list of currencies available in WooCommerce.
	 *
	 * @param array currencies An array of currency codes.
	 * @return array
	 */
	protected function add_currency_labels(array $currencies) {
		$woocommerce_currencies = get_woocommerce_currencies();

		// Add the VAT currency to the list, in case it's not already there
		$currencies[] = $this->_settings_controller->vat_currency();

		$result = array();
		foreach($currencies as $currency_code) {
			$result[$currency_code] = get_value($currency_code,
																					$woocommerce_currencies,
																					sprintf(__('Label not found for currency "%s"', Definitions::TEXT_DOMAIN),
																									$currency_code));
		}

		return $result;
	}

	/**
	 * Returns the tabs to be used to render the Settings page.
	 *
	 * @since 1.15.0.201119
	 */
	protected function get_settings_tabs() {
		return array(
			// Checkout settings
			self::TAB_CHECKOUT => array(
				'id' => self::TAB_CHECKOUT,
				'label' => __('Checkout', Definitions::TEXT_DOMAIN),
				'priority' => 100,
			),
			// VAT Number validation settings
			// @since 1.11.0.191108
			self::TAB_VAT_NUMBER_VALIDATION => array(
				'id' => self::TAB_VAT_NUMBER_VALIDATION,
				'label' => __('VAT Number Validation', Definitions::TEXT_DOMAIN),
				'priority' => 110,
			),
			// Self-certification
			self::TAB_SELF_CERTIFICATION => array(
				'id' => self::TAB_SELF_CERTIFICATION,
				'label' => __('Self-certification', Definitions::TEXT_DOMAIN),
				'priority' => 120,
			),
			// Currency
			self::TAB_CURRENCY => array(
				'id' => self::TAB_CURRENCY,
				'label' => __('Currency', Definitions::TEXT_DOMAIN),
				'priority' => 130,
			),
			// Sales
			self::TAB_SALES => array(
				'id' => self::TAB_SALES,
				'label' => __('Sales', Definitions::TEXT_DOMAIN),
				'priority' => 140,
			),
			// Reports
			self::TAB_REPORTS => array(
				'id' => self::TAB_REPORTS,
				'label' => __('Reports', Definitions::TEXT_DOMAIN),
				'priority' => 150,
			),
			// Options
			self::TAB_OPTIONS => array(
				'id' => self::TAB_OPTIONS,
				'label' => __('Options', Definitions::TEXT_DOMAIN),
				'priority' => 160,
			),
			// Integrations tab
			// @since 2.0.1.201215
			// TODO Implement the content of this tab, or remove it if not needed
			// self::TAB_INVOICES => array(
			// 	'id' => self::TAB_INVOICES,
			// 	'label' => __('Integrations', Definitions::TEXT_DOMAIN),
			// 	'priority' => 170,
			// ),
			// Support tab
			// Services tab
			// @since 2.0.1.201215
			self::TAB_SERVICES => array(
				'id' => self::TAB_SERVICES,
				'label' => __('Services', Definitions::TEXT_DOMAIN),
				'priority' => 180,
			),
			// Support tab
			self::TAB_SUPPORT => array(
				'id' => self::TAB_SUPPORT,
				'label' => __('Support', Definitions::TEXT_DOMAIN),
				'priority' => 250,
			),
		);
	}

	/**
	 * Returns the plugin settings sections.
	 *
	 * @since 1.15.0.201119
	 */
	protected function get_settings_sections() {
		return array(
			self::TAB_CHECKOUT => array(
				// Checkout settings
				array(
					'id' => self::SECTION_CHECKOUT,
					'label' => __('Checkout settings', Definitions::TEXT_DOMAIN),
					'callback' => array($this, 'checkout_section_callback'),
					'priority' => 100,
				),
			),
			self::TAB_VAT_NUMBER_VALIDATION => array(
				array(
					'id' => self::SECTION_VIES_VALIDATION,
					'label' => __('VIES Validation', Definitions::TEXT_DOMAIN),
					'callback' => array($this, 'vies_validation_section_callback'),
					'priority' => 100,
				),
				array(
					'id' => self::SECTION_VIES_VALIDATION_OPTIONS,
					'label' => __('Validation options', Definitions::TEXT_DOMAIN),
					'callback' => array($this, 'vies_validation_tweaks_section_callback'),
					'priority' => 110,
				),
			),
			// Self-certification
			self::TAB_SELF_CERTIFICATION => array(
				array(
					'id' => self::SECTION_SELF_CERTIFICATION,
					'label' => __('Self-certification settings', Definitions::TEXT_DOMAIN),
					'callback' => array($this, 'self_certification_section_callback'),
					'priority' => 100,
				),
			),
			// Currency
			self::TAB_CURRENCY => array(
				array(
					'id' => self::SECTION_CURRENCY,
					'label' => __('Currency', Definitions::TEXT_DOMAIN),
					'callback' => null,
					'priority' => 100,
				),
				array(
					'id' => self::SECTION_EXCHANGE_RATES_UPDATE,
					'label' => __('Automatic update of exchange rates', Definitions::TEXT_DOMAIN),
					'callback' => null,
					'priority' => 110,
				),
				array(
					'id' => self::SECTION_EXCHANGE_RATES,
					'label' => __('Exchange rates', Definitions::TEXT_DOMAIN),
					'callback' => array($this, 'exchange_rates_section_callback'),
					'priority' => 120,
				),
			),
			// Sales
			self::TAB_SALES => array(
				array(
					'id' => self::SECTION_SALE_RESTRICTIONS,
					'label' => __('Sale restrictions', Definitions::TEXT_DOMAIN),
					'callback' => null,
					'priority' => 100,
				),
			),
			// Reports
			self::TAB_REPORTS => array(
				array(
					'id' => self::SECTION_REPORTS,
					'label' => __('Reports settings', Definitions::TEXT_DOMAIN),
					'callback' => null,
					'priority' => 100,
				),
				array(
					'id' => self::SECTION_LINKS,
					'label' => __('Shortcuts', Definitions::TEXT_DOMAIN),
					'callback' => array($this, 'links_section_callback'),
					'priority' => 110,
				),
			),
			// Options
			self::TAB_OPTIONS => array(
				array(
					'id' => self::SECTION_OPTIONS,
					'label' => __('Options', Definitions::TEXT_DOMAIN),
					'callback' => array($this, 'options_section_callback'),
					'priority' => 100,
				),
				array(
					'id' => self::SECTION_PRIVACY,
					'label' => __('Privacy', Definitions::TEXT_DOMAIN),
					'callback' => array($this, 'privacy_section_callback'),
					'priority' => 110,
				),
			),
			// Support tab
			self::TAB_SUPPORT => array(
				array(
					'id' => self::SECTION_SUPPORT,
					'label' => __('Support Information', Definitions::TEXT_DOMAIN),
					'callback' => array($this, 'support_section_callback'),
					'priority' => 100,
				),
				array(
					'id' => self::SECTION_DEBUG,
					'label' => __('Debug', Definitions::TEXT_DOMAIN),
					'callback' => null,
					'priority' => 110,
				),
			),
			// Support tab
			self::TAB_SUPPORT => array(
				array(
					'id' => self::SECTION_SUPPORT,
					'label' => __('Support Information', Definitions::TEXT_DOMAIN),
					'callback' => array($this, 'support_section_callback'),
					'priority' => 100,
				),
				array(
					'id' => self::SECTION_DEBUG,
					'label' => __('Debug', Definitions::TEXT_DOMAIN),
					'callback' => null,
					'priority' => 110,
				),
			),
			// Services tab
			// @since 2.0.1.201215
			self::TAB_SERVICES => array(
				array(
					'id' => self::SECTION_SERVICES,
					// This section doesn't need a label, as it's just a description
					'label' => '',
					'callback' => array($this, 'services_section_callback'),
					'priority' => 100,
				),
			),
		);
	}

	/**
	 * Returns the available tax classes.
	 * Using WC_Tax::get_tax_classes() should prevent compatibility issues in the future.
	 *
	 * @return array
	 * @since 1.15.0.201119
	 * @link https://aelia.freshdesk.com/a/tickets/84141
	 */
	protected function get_available_tax_classes() {
		$woocommerce_tax_classes = array_filter(array_map('trim', WC_Tax::get_tax_classes()));
		$available_tax_classes = array(
			// The "Standard" tax class actually has a blank key
			'' => 'Standard',
		);
		foreach($woocommerce_tax_classes as $tax_class_name) {
			$available_tax_classes[sanitize_title($tax_class_name)] = $tax_class_name;
		}
		return $available_tax_classes;
	}

	/**
	 * Configures the plugin settings fields.
	 *
	 * @since 1.15.0.201119
	 */
	protected function get_settings_fields() { // NOSONAR
		$schedule_info = $this->_settings_controller->get_exchange_rates_schedule_info();

		return array(
			self::SECTION_CHECKOUT => array(
				// VAT Number status (optional, required, etc)
				array(
					'id' => Settings::FIELD_EU_VAT_NUMBER_FIELD_REQUIRED,
					// Note
					// This label is correct. It will appear as "EU VAT number field will be <chosen option>" after
					// the user selects an option from the dropdown list.
					// Example: EU VAT number field will be <Always required>
					'label' => __('The VAT number field will be', Definitions::TEXT_DOMAIN),
					'description' => __('Choose if you would like to display the EU VAT Number field, and ' .
															'how you would like to handle it.'.
															'<ul class="description">' .
															'<li><strong>Optional</strong> - Customers can enter a EU VAT ' .
															'number to get VAT exemption.</li>' .
															'<li><strong>Always required</strong> - Customers must enter a ' .
															'valid EU VAT number to complete a purchase. This means that ' .
															'only B2B sales with EU businesses can be completed.</li>' .
															'<li><strong>Required only for EU addresses</strong> - Customers ' .
															'who select a billing country that is part of the EU must enter a ' .
															'valid EU VAT number to complete a purchase. Customer who select ' .
															'a non-EU country can proceed without entering the VAT number.</li>' .
															'<li><strong>Hidden</strong> - Customers will not be able ' .
															'to enter a EU VAT number. This option is useful if you do not ' .
															'plan to sell to EU businesses.</li>' .
															'</ul>',
															Definitions::TEXT_DOMAIN),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'dropdown',
					'options' => array(
						Settings::OPTION_EU_VAT_NUMBER_FIELD_OPTIONAL => __('Optional', Definitions::TEXT_DOMAIN),
						Settings::OPTION_EU_VAT_NUMBER_FIELD_REQUIRED => __('Always required', Definitions::TEXT_DOMAIN),
						Settings::OPTION_EU_VAT_NUMBER_FIELD_REQUIRED_EU_ONLY => __('Required only for EU addresses', Definitions::TEXT_DOMAIN),
						Settings::OPTION_EU_VAT_NUMBER_FIELD_REQUIRED_IF_COMPANY_FILLED => __('Required if customer enters a company name', Definitions::TEXT_DOMAIN),
						Settings::OPTION_EU_VAT_NUMBER_FIELD_REQUIRED_IF_COMPANY_FILLED_EU_ONLY => __('Required if customer enters a company name (EU addresses only)', Definitions::TEXT_DOMAIN),
						Settings::OPTION_EU_VAT_NUMBER_FIELD_HIDDEN => __('Hidden', Definitions::TEXT_DOMAIN),
					),
				),
				// Label for EU VAT field
				array(
					'id' => Settings::FIELD_EU_VAT_FIELD_TITLE,
					'label' => __('EU VAT field label', Definitions::TEXT_DOMAIN),
					'description' => __('The label that will be displayed above the EU VAT field at checkout.', Definitions::TEXT_DOMAIN),
					'css_class' => 'title',
					'attributes' => array(),
					'type' => 'text',
				),
				// Description for EU VAT field
				array(
					'id' => Settings::FIELD_EU_VAT_FIELD_DESCRIPTION,
					'label' => __('EU VAT field description', Definitions::TEXT_DOMAIN),
					'description' => __('A description that will be displayed above the EU VAT field at checkout.', Definitions::TEXT_DOMAIN),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'text',
				),
				// VAT Number field position
				// @since 1.11.0.191108
				array(
					'id' => Settings::FIELD_VAT_NUMBER_FIELD_POSITION,
					'label' => __('EU VAT field position (index)', Definitions::TEXT_DOMAIN),
					'description' => implode(' ', array(
						__('This numeric value will determine where the VAT number field will be displayed on the checkout page.', Definitions::TEXT_DOMAIN),
						__('The higher the number, the lower the field will appear on the page.', Definitions::TEXT_DOMAIN),
						__('For example, if you wish to show it just below the Company Name field, you will have to enter a value higher ' .
							 'than the index for that field (try with "35").', Definitions::TEXT_DOMAIN),
					 )),
					'css_class' => 'field_index',
					'attributes' => array(),
					'type' => 'number',
				),
				// Show VAT Number field is customer is in shop's base country
				array(
					'id' => Settings::FIELD_SHOW_EU_VAT_FIELD_IF_CUSTOMER_IN_BASE_COUNTRY,
					'label' => __('Show EU VAT field when customer is located in base country', Definitions::TEXT_DOMAIN),
					'description' => __('Show the EU VAT field when customer address is located in any European ' .
															'country, including your shop\'s base country. If this option is <strong>not</strong> selected, ' .
															'the EU VAT field will be hidden when the customer is from the same country specified ' .
															'as your shop\'s base country.',
															Definitions::TEXT_DOMAIN),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'checkbox',
				),
				// Deduct VAT from order field is customer is in shop's base country
				array(
					'id' => Settings::FIELD_REMOVE_VAT_IF_CUSTOMER_IN_BASE_COUNTRY,
					'label' => __('Deduct VAT if customer is located in base country', Definitions::TEXT_DOMAIN),
					'description' => __('Enable this option to deduct VAT from orders placed by customers who are located ' .
															'in your shop\'s base country, if they enter a valid EU VAT number.',
															Definitions::TEXT_DOMAIN),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'checkbox',
				),
				// Store invalid VAT numbers
				array(
					'id' => Settings::FIELD_STORE_INVALID_VAT_NUMBERS,
					'label' => __('Accept invalid VAT numbers', Definitions::TEXT_DOMAIN),
					'description' => __('If you disable this option, the EU VAT Assistant will not allow customers to proceed with ' .
															'the checkout if they enter an invalid VAT number.', Definitions::TEXT_DOMAIN) .
															' ' .
															__('When the VAT number is set to "optional", customers will have to either enter a valid number, or ' .
																 'leave the field empty.', Definitions::TEXT_DOMAIN) .
															' ' .
															__('If you enable this option, the EU VAT Assistant will accept VAT numbers that the VIES system ' .
																 'returned as not valid, allowing customers to complete the checkout.', Definitions::TEXT_DOMAIN) .
															'<br/>' .
															'<strong>' . __('Note', Definitions::TEXT_DOMAIN) . '</strong>: ' .
															__('when an invalid VAT number is accepted because this option is enabled, VAT is still applied to the order.', Definitions::TEXT_DOMAIN),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'checkbox',
				),
			),
			self::SECTION_SELF_CERTIFICATION => array(
				// Enable/disable self-certification field
				array(
					'id' => Settings::FIELD_SHOW_SELF_CERTIFICATION_FIELD,
					'label' => __('Allow customers to self-certify their location of residence', Definitions::TEXT_DOMAIN),
					'description' => __('Choose if you would like to display a "self-certification" field at ' .
															'checkout. By ticking the self-certification box, customers will be ' .
															'allowed to certify that the country entered as part of the billing ' .
															'address is their country of residence. Such declaration will be ' .
															'recorded with the completed order as part of the EU VAT evidence.',
															Definitions::TEXT_DOMAIN),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'dropdown',
					'options' => array(
						Settings::OPTION_SELF_CERTIFICATION_FIELD_NO => __('No (never show it)', Definitions::TEXT_DOMAIN),
						Settings::OPTION_SELF_CERTIFICATION_FIELD_YES => __('Yes (always show it)', Definitions::TEXT_DOMAIN),
						Settings::OPTION_SELF_CERTIFICATION_FIELD_CONFLICT_ONLY => __('Only when there is insufficient ' .
																																					'evidence about customer\'s location', Definitions::TEXT_DOMAIN),
					),
				),
				// Self-certification field position
				// @since 1.11.0.191108
				array(
					'id' => Settings::FIELD_SELF_CERTIFICATION_FIELD_POSITION,
					'label' => __('Self-certification field position (index)', Definitions::TEXT_DOMAIN),
					'description' => implode(' ', array(
						__('This numeric value will determine where the VAT number field will be displayed on the checkout page.', Definitions::TEXT_DOMAIN),
						__('The higher the number, the lower the field will appear on the page.', Definitions::TEXT_DOMAIN),
					 )),
					'css_class' => 'field_index',
					'attributes' => array(),
					'type' => 'number',
				),
				// Self-certification required in case of conflict
				array(
					'id' => Settings::FIELD_SELF_CERTIFICATION_FIELD_REQUIRED_WHEN_CONFLICT,
					'label' => __('Require self-certification when the evidence about location is insufficient', Definitions::TEXT_DOMAIN),
					'description' => __('<a href="http://en.wikipedia.org/wiki/European_Union_value_added_tax#EU_VAT_area">' .
															'EU regulations require at least two pieces of non-conflicting ' .
															'evidence</a> to prove a customer\'s location (e.g. billing address, shipping ' .
															'address, IP address). If you enable this option, the self-certification ' .
															'will become mandatory unless at least two of these information will ' .
															'match the same country. <strong>Important</strong>: this rule ' .
															'applies only when the self-certification field is visible to the ' .
															'customer (see visibility options, above).',
															Definitions::TEXT_DOMAIN),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'checkbox',
				),
				// Use shipping address as evidence
				array(
					'id' => Settings::FIELD_USE_SHIPPING_ADDRESS_AS_EVIDENCE,
					'label' => __('Consider the shipping address as valid location evidence', Definitions::TEXT_DOMAIN),
					'description' => __('Tick this box if you would like to use the shipping address ' .
															'as evidence to validate customer\'s location. When this ' .
															'option is enabled, and the customer enters the same country ' .
															'in both billing and shipping address, the plugin will consider ' .
															'them two pieces of non contradictory evidence and it will no ' .
															'longer ask for self-certification. ' .
															'We would recommend that you discuss with your Revenue office ' .
															'the possibility of using the shipping address as evidence ' .
															'before enabling this option.',
															Definitions::TEXT_DOMAIN),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'checkbox',
				),
				// Hide self-certification field when a valid VAT number is entered
				array(
					'id' => Settings::FIELD_HIDE_SELF_CERTIFICATION_FIELD_VALID_VAT_NUMBER,
					'label' => __('Hide the self-certification field when a valid VAT number is entered', Definitions::TEXT_DOMAIN),
					'description' => __('Enable this option if you would like to hide the self-certification ' .
															'field at checkout even when the customer enters a valid VAT number. ' .
															'<strong>Important</strong>: when this option is enabled, if the ' .
															'customer will enter a valid VAT number the self-certification ' .
															'requirement above will be ignored.',
															Definitions::TEXT_DOMAIN),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'checkbox',
				),
				array(
					'id' => Settings::FIELD_SELF_CERTIFICATION_FIELD_TITLE,
					'label' => __('Self-certification field label', Definitions::TEXT_DOMAIN),
					'description' => __('The label that will be displayed above the self-certification ' .
															'field at checkout. You can use the <code>{billing_country}</code> ' .
															'placeholder to automatically show the billing country chosen ' .
															'by the customer.',
															Definitions::TEXT_DOMAIN),
					'css_class' => 'title',
					'attributes' => array(),
					'type' => 'text',
				),
			),
			self::SECTION_CURRENCY => array(
				// VAT Currency
				array(
					'id' => Settings::FIELD_VAT_CURRENCY,
					'label' => __('VAT Currency', Definitions::TEXT_DOMAIN),
					'description' => __('EU regulations require that all payment and VAT amounts ' .
															'are indicated in the currency where your business is based. ' .
															'If you file your VAT reports in a currency different from ' .
															'shop\'s base currency, you can choose it here. ' .
															'<strong>Important:</strong> all VAT data will be calculated and ' .
															'stored with the orders as they are created. We strongly recommend ' .
															'to double check the currency you have selected, as changing it later ' .
															'could result in incorrect VAT reports being generated.',
															Definitions::TEXT_DOMAIN),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'dropdown',
					'options' => get_woocommerce_currencies(),
				),
			),
			self::SECTION_EXCHANGE_RATES_UPDATE => array(
				// Enable automatic update of exchange rates
				array(
					'id' => Settings::FIELD_EXCHANGE_RATES_UPDATE_ENABLE,
					'label' => __('Tick this box to enable automatic updating of exchange rates.', Definitions::TEXT_DOMAIN),
					'description' => '',
					'css_class' => '',
					'attributes' => array(),
					'type' => 'checkbox',
				),
				// Exchange Rates Schedule
				array(
					'id' => Settings::FIELD_EXCHANGE_RATES_UPDATE_SCHEDULE,
					'label' => __('Select how often you would like to update the exchange rates.', Definitions::TEXT_DOMAIN),
					'description' => sprintf(__('<p>Last update: <span id="last_exchange_rates_update">%s</span>.</p>' .
																			'<p>Next update: <span id="next_exchange_rates_update">%s</span>.</p>',
																			Definitions::TEXT_DOMAIN),
																	$schedule_info['last_update'],
																	$schedule_info['next_update']),
					'css_class' => 'exchange_rates_schedule',
					'attributes' => array(),
					'type' => 'dropdown',
					'options' => $this->_settings_controller->get_schedule_options(),
				),
				// Exchange Rates Provider
				array(
					'id' => Settings::FIELD_EXCHANGE_RATES_PROVIDER,
					'label' => __('Exchange rates provider', Definitions::TEXT_DOMAIN),
					'description' => __('Select the provider from which the exchange rates will be fetched.', Definitions::TEXT_DOMAIN),
					'css_class' => 'exchange_rates_provider',
					'attributes' => array(),
					'type' => 'dropdown',
					'options' => $this->_settings_controller->exchange_rates_providers_options(),
				),
			),
			self::SECTION_EXCHANGE_RATES => array(
				// Exchange Rates Table
				array(
					'id' => Settings::FIELD_EXCHANGE_RATES,
					'label' => '',
					'description' => __('Select the provider from which the exchange rates will be fetched.', Definitions::TEXT_DOMAIN),
					'css_class' => 'exchange_rates_provider',
					'attributes' => array(
						'settings_key' => $this->_settings_key,
						'exchange_rates' => $this->current_settings(Settings::FIELD_EXCHANGE_RATES, $this->default_settings(Settings::FIELD_EXCHANGE_RATES, array())),
						'id' => Settings::FIELD_EXCHANGE_RATES,
						'label_for' => Settings::FIELD_EXCHANGE_RATES,
						'attributes' => array(
							'class' => Settings::FIELD_EXCHANGE_RATES,
						),
					),
					'type' => 'custom',
					'render_callback' => array($this, 'render_exchange_rates_options'),
				),

			),
			self::SECTION_SALE_RESTRICTIONS => array(
				// Sale restrictions
				array(
					'id' => Settings::FIELD_SALE_DISALLOWED_COUNTRIES,
					'label' => __('Prevent sales to these countries', Definitions::TEXT_DOMAIN),
					'description' => __('Here you can add a list of countries to which you do not want ' .
															'to sell. The countries in this list will not appear to the customer ' .
															'at any stage, thus preventing him from completing an order. Leave ' .
															'this field empty to allow sales to all countries configured in ' .
															'<strong>WooCommerce > Settings</strong> section.',
															Definitions::TEXT_DOMAIN),
					'css_class' => 'multi_country_selector',
					'attributes' => array(
						'multiple' => 'multiple',
					),
					'type' => 'dropdown',
					'options' => wc()->countries->get_countries(),
				),
			),
			self::SECTION_REPORTS => array(
				// Tax classes to exclude from VAT MOSS
				array(
					'id' => Settings::FIELD_TAX_CLASSES_EXCLUDED_FROM_MOSS,
					'label' => __('The following tax classes should not be included in MOSS reports', Definitions::TEXT_DOMAIN),
					'description' => __('Here you can select one or more tax classes whose rates should ' .
															'be excluded from MOSS reports. The tax information for ' .
															'those rates will still be tracked, this setting will just ' .
															'be used to filter the data using the report filters ' .
															'in the <strong>Reports</strong> interface.',
															Definitions::TEXT_DOMAIN),
					'css_class' => 'moss_excluded_tax_classes',
					'attributes' => array(
						'multiple' => 'multiple',
					),
					'type' => 'dropdown',
					'options' => $this->get_available_tax_classes(),
				),
			),
			self::SECTION_OPTIONS => array(
				// Decimals for rounding of VAT calculations
				array(
					'id' => Settings::FIELD_VAT_ROUNDING_DECIMALS,
					'label' => __('Decimals for VAT rounding', Definitions::TEXT_DOMAIN),
					'description' => __('The amount of decimals to use when rounding VAT. This setting ' .
															'applies when the VAT evidence is generated (for example during ' .
															'the conversion of VAT totals to the appropriate VAT currency). ' .
															'If you are not sure of how many decimals you should use, please ' .
															'contact your Revenue office.',
															Definitions::TEXT_DOMAIN),
					'css_class' => 'numeric',
					'attributes' => array(),
					'type' => 'text',
				),
				// Collect data for manual orders
				array(
					'id' => Settings::FIELD_COLLECT_VAT_DATA_FOR_MANUAL_ORDERS,
					'label' => __('Collect VAT data for orders entered or modified manually', Definitions::TEXT_DOMAIN),
					'description' => __('When this option is selected, the EU VAT Assistant will ' .
															'collect VAT data for orders that are added or modified manually by ' .
															'going to <em>WooCommerce > Orders > Add new order</em>. ' .
															'The data will be collected automatically, when the ' .
															'"Calculate Totals" or "Recalculate" button is clicked.',
															Definitions::TEXT_DOMAIN) .
													 '<br/><br/> ' .
													 '<strong>' . __('Important', Definitions::TEXT_DOMAIN) . '</strong>: ' .
													 __('The VAT data collected from orders created or modified manually will be the same ' .
															'collected from orders placed by customers ' .
															'and it will appear on the tax reports.', Definitions::TEXT_DOMAIN) .
													 ' ' .
													 __('Since the VAT MOSS scheme explicitly covers sales that do ' .
															'not require manual intervention, orders entered manually ' .
															'should fall outside the scope of such scheme.', Definitions::TEXT_DOMAIN) .
													 ' ' .
													 __('For this reason, this option is disabled by default. ' .
															'We recommend that you consult your accountant before enabling ' .
															'this option.', Definitions::TEXT_DOMAIN),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'checkbox',
				),
			),
			// Privacy section
			// @since 2.0.4.201231
			self::SECTION_PRIVACY => array(
				// Erase VAT data when deleting personal data
				// @since 2.0.4.201231
				array(
					'id' => Settings::FIELD_PRIVACY_REMOVE_VAT_DATA_ON_DATA_ERASURE,
					'label' => __('Delete VAT data when erasing personal data', Definitions::TEXT_DOMAIN),
					'description' => implode(' ', array(
						__('When this option is selected, the EU VAT Assistant will delete the VAT data it stored against orders ' .
							 'when the personal data of customers is erased.', Definitions::TEXT_DOMAIN),
						'<br/><br/>',
						'<strong>' . __('Important', Definitions::TEXT_DOMAIN) . '</strong>: ',
						__('In most jurisdictions, VAT data must be preserved for compliance with tax regulations.', Definitions::TEXT_DOMAIN),
						__('It is recommended to contact your tax advisor, before enabling this option.', Definitions::TEXT_DOMAIN),
					)),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'checkbox',
				),
			),
			self::SECTION_VIES_VALIDATION => array(
				// Requester VAT Country for the VIES Validation service
				// @since 1.9.0.181022
				array(
					'id' => Settings::FIELD_VIES_REQUESTER_VAT_COUNTRY,
					'label' => __('Requester country code for the VAT validation service', Definitions::TEXT_DOMAIN),
					'description' => '',
					'css_class' => 'shop_vat_country',
					'attributes' => array(
					),
					'type' => 'dropdown',
					// Show a list of EU Countries to select the "requester VAT country"
					// @since 2.0.5.210102
					'options' => array_filter(wc()->countries->get_countries(), function($country_code) {
						return in_array($country_code, wc()->countries->get_european_union_countries('eu_vat'));
					}, ARRAY_FILTER_USE_KEY),
				),
				// Requester VAT Number for the VIES Validation service
				// @since 1.9.0.181022
				array(
					'id' => Settings::FIELD_VIES_REQUESTER_VAT_NUMBER,
					'label' => __('Requester VAT number for the VIES VAT validation service', Definitions::TEXT_DOMAIN),
					'description' => implode(' ', array(
						__('If you enter a valid EU VAT number, it will be passed to the EU VIES service with each EU VAT number validation request.', Definitions::TEXT_DOMAIN),
						__('With this information, the VIES service will return a a "consultation number", which can be used as proof of the validity of VAT numbers entered by your customers, should your revenue office ask for such evidence.', Definitions::TEXT_DOMAIN),
						'<br />',
						'<strong>' . __('Important', Definitions::TEXT_DOMAIN) . '</strong>',
						'<br />',
						'- ' . __('Please make sure that you enter you VAT number without the country code prefix. The country code will be added automatically, ' .
						'by the EU VAT Assistant, using the country you selected above.', Definitions::TEXT_DOMAIN),
						'<br />',
						'- <strong>' . __('From the 1st of January 2021, you can no longer enter a UK VAT number in this field. ', Definitions::TEXT_DOMAIN) . '</strong>',
						__('If you have a UK VAT number, please leave this field empty. ', Definitions::TEXT_DOMAIN),
					)),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'text',
				),
				// Retry VAT number validation upon failure
				// @since 1.11.0.191108
				array(
					'id' => Settings::FIELD_RETRY_VALIDATION_WHEN_REQUESTER_VAT_NUMBER_INVALID,
					'label' => __('Retry the validation of a VAT number when it fails due to the requested VAT number being invalid', Definitions::TEXT_DOMAIN),
					'description' => implode(' ', array(
															__('The remote VIES service may return an "invalid requester" response when it cannot validate the requester VAT number sent with a validation request.', Definitions::TEXT_DOMAIN),
															__('When you enable this option, the EU VAT Assistant will try to validate the VAT number from the customer again, without sending the requester details.', Definitions::TEXT_DOMAIN),
															__('In this second case, the VIES service will not return a consultation number upon a successful validation.', Definitions::TEXT_DOMAIN),
															sprintf(__('For more information about the purpose of the consultation number, <a href="%1$s">please refer to the documentation of the VIES service</a>.', Definitions::TEXT_DOMAIN),
																			'http://ec.europa.eu/taxation_customs/vies/help.html'),
													)),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'checkbox',
				),
			),
			self::SECTION_VIES_VALIDATION_OPTIONS => array(
				// Accept VAT numbers when the VIES response is "server busy"
				array(
					'id' => Settings::FIELD_ACCEPT_VAT_NUMBER_WHEN_SERVICE_UNAVAILABLE,
					'label' => __('Accept VAT numbers as valid when the remote service is busy', Definitions::TEXT_DOMAIN),
					'description' => implode(' ', array(
														__('The remote VIES service may return a "busy" response when it cannot process a validation request.', Definitions::TEXT_DOMAIN),
														__('In such case, the VAT number validation request was not processed and its validity is unknown.', Definitions::TEXT_DOMAIN),
														__('If you enable this option, the VAT number will be considered valid anyway, and a VAT exemption will be applied at checkout.', Definitions::TEXT_DOMAIN),
													 )),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'checkbox',
				),
				// Accept VAT numbers when the VIES response is "service unavailable"
				array(
					'id' => Settings::FIELD_ACCEPT_VAT_NUMBER_WHEN_VALIDATION_SERVER_BUSY,
					'label' => __('Accept VAT numbers as valid when the remote service is unavailable', Definitions::TEXT_DOMAIN),
					'description' => implode(' ', array(
														__('The remote VIES service may return an "unavailable" response when the main service, or the service from a specific member state, is unavailable.', Definitions::TEXT_DOMAIN),
														__('In such case, the VAT number validation request was not processed and its validity is unknown.', Definitions::TEXT_DOMAIN),
														__('If you enable this option, the VAT number will be considered valid anyway, and a VAT exemption will be applied at checkout.', Definitions::TEXT_DOMAIN),
													)),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'checkbox',
				),
				// Accept VAT numbers when the VIES response is "too many requests"
				array(
					'id' => Settings::FIELD_ACCEPT_VAT_NUMBER_WHEN_SERVICE_REJECTS_TOO_MANY_REQUESTS,
					'label' => __('Accept VAT numbers as valid when the remote service is handling too many requests', Definitions::TEXT_DOMAIN),
					'description' => implode(' ', array(
															__('The remote VIES service may return a "too many requests" response, when the main service, or a member state, is overloaded.', Definitions::TEXT_DOMAIN),
															__('In such case, the VAT number validation request was not processed and its validity is unknown.', Definitions::TEXT_DOMAIN),
															__('If you enable this option, the VAT number will be considered valid anyway, and a VAT exemption will be applied at checkout.', Definitions::TEXT_DOMAIN),
															'<br />',
															'<strong>' . __('Important', Definitions::TEXT_DOMAIN) . '</strong>:',
															__('A "too many requests" error does NOT indicate that your site is making too many calls.', Definitions::TEXT_DOMAIN),
															__('That error refers to the global amount of requests received by the VIES service, or member state, not just from your site.', Definitions::TEXT_DOMAIN),
													)),
					'css_class' => '',
					'attributes' => array(),
					'type' => 'checkbox',
				),
			),
			self::SECTION_DEBUG => array(
				// Debug mode
				array(
					'id' => Settings::FIELD_DEBUG_MODE,
					'label' => __('Enable debug mode.', Definitions::TEXT_DOMAIN),
					'description' => __('When the debug mode is enabled, the plugin will log additional ' .
															'information about the operations it performs. The log file ' .
															'will be located at', Definitions::TEXT_DOMAIN) .
															' <code class="log_file_path">' .
															// Show the new name of log files, to indicate that they contain a timestamp
															// @since 1.15.0.201119
															str_replace('.log', '-[TIMESTAMP].log', \Aelia\WC\Logger::get_log_file_name(WC_Aelia_EU_VAT_Assistant::$plugin_slug)) .
			 												'</code>',
					'css_class' => '',
					'attributes' => array(),
					'type' => 'checkbox',
				),
			),
		);
	}

	/**
	 * Returns the title for the menu item that will bring to the plugin's
	 * settings page.
	 *
	 * @return string
	 */
	protected function menu_title() {
		return __('EU VAT Assistant', Definitions::TEXT_DOMAIN);
	}

	/**
	 * Returns the slug for the menu item that will bring to the plugin's
	 * settings page.
	 *
	 * @return string
	 */
	protected function menu_slug() {
		return Definitions::MENU_SLUG;
	}

	/**
	 * Returns the title for the settings page.
	 *
	 * @return string
	 */
	protected function page_title() {
		return __('EU VAT Assistant - Settings', Definitions::TEXT_DOMAIN) .
					 sprintf('&nbsp;(v. %s)', WC_Aelia_EU_VAT_Assistant::$version);
	}

	/**
	 * Returns the description for the settings page.
	 *
	 * @return string
	 */
	protected function page_description() {
		// TODO Restore link to documentation
		return __('In this page you can configure the settings for the EU VAT Assistant plugin.',
							Definitions::TEXT_DOMAIN);
	}

	/*** Settings sections callbacks ***/

	/**
	 * Callback - Checkout section.
	 *
	 * @return void
	 */
	public function checkout_section_callback() {
		echo __('Here you can configure various parameters related to checkout.', Definitions::TEXT_DOMAIN);

		echo '<noscript>';
		echo __('This page requires JavaScript to work properly. Please enable JavaScript ' .
						'in your browser and refresh the page.</u>.',
						Definitions::TEXT_DOMAIN);
		echo '</noscript>';
	}

	/**
	 * Callback - Self-certification section.
	 *
	 * @return void
	 */
	public function self_certification_section_callback() {
		echo __('Here you can decide to display an additional self-certification field that ' .
						'customers can use to confirm their country of residence.', Definitions::TEXT_DOMAIN);
	}

	/**
	 * Callback - Exchange rates section.
	 *
	 * @return void
	 */
	public function exchange_rates_section_callback() {
		echo __('These exchange rates will be used to convert ' .
						'the order amounts (totals, VAT, etc) in the currency you use to file your VAT returns. ' .
						'<strong>Important</strong>: you can enter the exchange rates manually, if you wish, but ' .
						'please make sure that they are in line with the values that your Revenue office would ' .
						'consider acceptable. The responsibility of ensuring that the rates are correct lies ' .
						'upon you. If you are in doubt, please contact your revenue office to determine which ' .
						'exchange rates they would consider acceptable. Most revenue offices have their own ' .
						'list of exchange rates, which you can enter here.', Definitions::TEXT_DOMAIN);
		echo '<br /><br />';
		echo __('To set an exchange rate manually, tick the box next to the exchange rate field ' .
						'for the desired currency and enter the rate in the exchange rate field itself. ' .
						'The checkbox next to the "<strong>Set manually</strong>" label will select/deselect the checkboxes ' .
						'for all the currencies. <strong>Important</strong>: ensure that all exchange rates ' .
						'you flagged to be entered manually are filled. An empty exchange rate could lead to ' .
						'unpredictable results, as it\'s intepreted as zero.', Definitions::TEXT_DOMAIN);
	}

	/**
	 * VIES validation settings.
	 *
	 * @since 1.9.0.181022
	 */
	public function vies_validation_section_callback() {
		echo __('Here you can specify the parameters that will be used to communicate with ' .
						'the VIES service, used to validate EU VAT numbers.', Definitions::TEXT_DOMAIN);
	}

	/**
	 * VIES validation tweaks settings.
	 *
	 * @since 1.11.0.191108
	 */
	public function vies_validation_tweaks_section_callback() {
		echo '<strong>' . __('Important', Definitions::TEXT_DOMAIN) . '</strong>: ';
		echo implode(' ', array(
			__('We strongly recommend to consult your tax advisor before enabling any of the options ' .
				 'that allow to accept VAT numbers that could not be validated due to a VIES service error.', Definitions::TEXT_DOMAIN),
			__('While accepting potentially invalid VAT number can help customers to complete orders when the validation service ' .
				 'is not working, it also exposes you to the possibility of granting a VAT exemption to customers who are not entitled ' .
				 'to it.', Definitions::TEXT_DOMAIN),
			__('The responsibility of applying a VAT exemption only when needed, providing evidence in relation to such exemption '.
				 'to the Revenue Office, and rectify exemptions granted when not due, remains exclusively yours.', Definitions::TEXT_DOMAIN),
		));
	}

	/**
	 * Callback - Miscellaneous options section.
	 *
	 * @return void
	 */
	public function options_section_callback() {
		echo __('Miscellaneous options.', Definitions::TEXT_DOMAIN);
	}

	/**
	 * Callback - Privacy options section.
	 *
	 * @return void
	 * @since 2.0.4.201231
	 */
	public function privacy_section_callback() {
		echo __('Privacy options.', Definitions::TEXT_DOMAIN);
	}

	/**
	 * Callback - Links/shortcuts section.
	 *
	 * @return void
	 */
	public function links_section_callback() {
		?>
		<div class="links">
			<p><?php
				echo __('This section contains some convenient links to the ' .
								'sections of WooCommerce relevant to EU VAT compliance', Definitions::TEXT_DOMAIN);
			?></p>
			<div class="settings">
				<h4 class="title"><?php
					echo __('Settings', Definitions::TEXT_DOMAIN);
				?></h4>
				<ul>
					<li class="tax">
						<a href="<?php echo admin_url('admin.php?page=wc-settings&tab=tax'); ?>"><?php
							echo __('Tax Settings', Definitions::TEXT_DOMAIN);
						?></a>
					</li>
				</ul>
			</div>
			<div class="reports">
				<h4 class="title"><?php
					echo __('Reports', Definitions::TEXT_DOMAIN);
				?></h4>
				<ul>
					<li class="eu_vat_report">
						<a href="<?php echo admin_url('admin.php?page=wc-reports&tab=taxes&report=eu_vat_by_country_report'); ?>"><?php
							echo __('EU VAT By Country', Definitions::TEXT_DOMAIN);
						?></a>
					</li>
					<li class="vies_report">
						<a href="<?php echo admin_url('admin.php?page=wc-reports&tab=taxes&report=vies_report'); ?>"><?php
							echo __('VIES', Definitions::TEXT_DOMAIN);
						?></a>
					</li>
					<li class="intrastat_report">
						<a href="<?php echo admin_url('admin.php?page=wc-reports&tab=taxes&report=intrastat_report'); ?>"><?php
							echo __('INTRASTAT', Definitions::TEXT_DOMAIN);
						?></a>
					</li>
					<li class="sales_summary_report">
						<a href="<?php echo admin_url('admin.php?page=wc-reports&tab=taxes&report=sales_summary_report'); ?>"><?php
							echo __('Sales Summary (VAT RTD)', Definitions::TEXT_DOMAIN);
						?></a>
					</li>
				</ul>
			</div>
		</div>
		<?php
	}

	/**
	 * Callback - Support section.
	 *
	 * @return void
	 */
	public function support_section_callback() {
		?>
		<div class="support_information">
			<p><?=
				__('We designed this plugin to be robust and effective, ' .
						'as well as intuitive and easy to use. However, we are aware that, despite ' .
						'all best efforts, issues can arise and that there is always room for ' .
						'improvement.',
						Definitions::TEXT_DOMAIN);
			?></p>
			<p><?=
				__('Should you need assistance, or if you just would like to get in touch ' .
					'with us, please use one of the links below.',
						Definitions::TEXT_DOMAIN);
			?></p>
			<?php // Support links ?>
			<ul id="contact_links">
				<li><?=
					sprintf(__('<span class="label">To request support</span>, please use our <a href="%s">Support portal</a>. ' .
										'The portal also contains a Knowledge Base, where you can find the ' .
										'answers to the most common questions related to our products.',
										Definitions::TEXT_DOMAIN),
									self::SUPPORT_URL)
				?></li>
				<li><?=
					sprintf(__('<span class="label">To send us general feedback</span>, suggestions, or enquiries, please use ' .
										'the <a href="%s">contact form on our website.</a>',
										Definitions::TEXT_DOMAIN),
										self::CONTACT_URL)
				?></li>
			</ul>
		</div>
		<?php
	}

	/**
	 * Renders the description for the Services > External Services section.
	 *
	 * @since 2.0.1.201215
	 */
	public function services_section_callback() {
		?>
		<div class="services_settings">
			<p><?php
				echo __('In this section you can configure the settings for external services, for example for the validation of VAT numbers.', Definitions::TEXT_DOMAIN);
				echo ' ';
				echo __("If this section is empty, it means that there isn't any service to be configured.", Definitions::TEXT_DOMAIN);
			?></p>
		</div>
		<?php
	}

	/*** Rendering methods ***/
	/**
	 * Renders the buttons at the bottom of the settings page.
	 */
	protected function render_buttons() {
		parent::render_buttons();
		submit_button(__('Save and update exchange rates', Definitions::TEXT_DOMAIN),
									'secondary',
									$this->_settings_key . '[update_exchange_rates_button]',
									false);
	}

	protected function render_exchange_rates_fields() {
		// Prepare fields to display the Exchange Rate options for each selected currency
		$exchange_rates_field_id = Settings::FIELD_EXCHANGE_RATES;
		$exchange_rates = $this->current_settings($exchange_rates_field_id, $this->default_settings($exchange_rates_field_id, array()));
		// Add "Exchange Rates" table
		add_settings_field(
			$exchange_rates_field_id,
			'', // No label needed
			array($this, 'render_exchange_rates_options'),
			$this->_settings_key,
			self::SECTION_EXCHANGE_RATES,
			array(
				'settings_key' => $this->_settings_key,
				'exchange_rates' => $exchange_rates,
				'id' => $exchange_rates_field_id,
				'label_for' => $exchange_rates_field_id,
				// Input field attributes
				'attributes' => array(
					'class' => $exchange_rates_field_id,
				),
			)
		);
	}

	/**
	 * Renders a table containing several fields that admins can use to configure
	 * the Exchange Rates for the various currencies.
	 *
	 * @param array args An array of arguments passed by add_settings_field().
	 * @see add_settings_field().
	 */
	public function render_exchange_rates_options($args) {
		/* Generate the base field ID and field name that will be used to dynamically
		 * create the hierarchy of fields for the exchange rates. Every field will
		 * have a name like "base_field_id[currency]", so that PHP will automatically
		 * build a hierarchy out of them when the settings will be saved.
		 *
		 * Note: $base_field_id and $base_field_name are output parameters, they will
		 * be populated by the method.
		 */
		$this->get_field_ids($args, $base_field_id, $base_field_name);

		// Retrieve the enabled currencies
		$currencies = $this->add_currency_labels($this->_settings_controller->enabled_currencies());
		// Retrieve the exchange rates
		$exchange_rates = get_value(Settings::FIELD_EXCHANGE_RATES, $args, array());
		if(!is_array($exchange_rates)) {
			throw new InvalidArgumentException(__('Argument "exchange_rates" must be an array.', Definitions::TEXT_DOMAIN));
		}

		// Retrieve the Currency used internally by WooCommerce
		$vat_currency = $this->_settings_controller->vat_currency();

		$html = '<table id="exchange_rates_settings">';
		// Table header
		$html .= '<thead>';
		$html .= '<tr>';
		$html .= '<th class="currency_name">' . __('Currency', Definitions::TEXT_DOMAIN) . '</th>';
		$html .= '<th class="exchange_rate">' . __('Exchange Rate', Definitions::TEXT_DOMAIN) . '</th>';
		$html .= '<th class="set_manually">' .
						 __('Set Manually', Definitions::TEXT_DOMAIN) .
						 '<span class="help-icon" title="' .
						 __('Tick the box next to a currency if you would like to enter its ' .
								'exchange rate manually. By doing that, the rate you enter for ' .
								'that currency will not change, even if you have enabled the automatic ' .
								'update of exchange rates, below',
								Definitions::TEXT_DOMAIN) .
						 '"></span>' .
						 '<div class="selectors">' .
						 '<span class="select_all">' . __('Select', Definitions::TEXT_DOMAIN) . '</span>' .
						 '/' .
						 '<span class="deselect_all">' . __('Deselect', Definitions::TEXT_DOMAIN) . '</span>' .
						 __('all', Definitions::TEXT_DOMAIN) .
						 '</div>' .
						 '</th>';
		$html .= '</th>';

		$html .= '</tr>';
		$html .= '</thead>';
		$html .= '<tbody>';

		// Render one line to display settings for base currency
		$html .= $this->render_settings_for_vat_currency($vat_currency,
																										 $currencies[$vat_currency],
																										 $exchange_rates,
																										 $base_field_id,
																										 $base_field_name);

		foreach($currencies as $currency => $currency_name) {
			// No need to render an Exchange Rate for main currency, as it would be 1:1
			if($currency == $vat_currency) {
				continue;
			}

			$currency_field_id = $this->group_field($currency, $base_field_id);

			$html .= '<tr>';
			// Output currency label
			$html .= '<td class="currency_name">';
			$html .= "<span>$currency_name ($currency)</span>";
			$html .= '</td>';

			$currency_settings = get_value($currency, $exchange_rates, array());
			$currency_settings = array_merge($this->_settings_controller->default_currency_settings(), $currency_settings);

			// Render exchange rate field
			$html .= '<td class="exchange_rate">';
			$field_args = array(
				'id' => $currency_field_id . '[rate]',
				'value' => get_value('rate', $currency_settings, ''),
				'attributes' => array(
					'class' => 'numeric',
				),
			);
			ob_start();
			$this->render_textbox($field_args);
      $field_html = ob_get_contents();
      ob_end_clean();
			$html .= $field_html;
			$html .= '</td>';

			// Render "Set Manually" checkbox
			$html .= '<td class="set_manually">';
			$field_args = array(
				'id' => $currency_field_id . '[set_manually]',
				'value' => 1,
				'attributes' => array(
					'class' => 'exchange_rate_set_manually',
					'checked' => get_value('set_manually', $currency_settings),
				),
			);
			ob_start();
			$this->render_checkbox($field_args);
			$field_html = ob_get_contents();
			ob_end_clean();
			$html .= $field_html;
			$html .= '</td>';
			$html .= '</tr>';
		}

		$html .= '</tbody>';
		$html .= '</table>';

		echo $html;
	}

	/**
	 * Renders a "special" row on the exchange rates table, which contains the
	 * settings for the base currency.
	 *
	 * @param string currency The currency to display on the row.
	 * @param string exchange_rates An array of currency settings.
	 * @param string base_field_id The base ID that will be assigned to the
	 * fields in the row.
	 * @param string base_field_id The base name that will be assigned to the
	 * fields in the row.
	 * @return string The HTML for the row.
	 */
	protected function render_settings_for_vat_currency($currency, $currency_name, $exchange_rates, $base_field_id, $base_field_name) {
		$html = '<tr>';
		// Output currency label
		$html .= '<td class="currency_name">';
		$html .= "<span>$currency_name ($currency)</span>";
		$html .= '</td>';

		$currency_settings = get_value($currency, $exchange_rates, array());
		$currency_settings = array_merge($this->_settings_controller->default_currency_settings(), $currency_settings);

		// Render exchange rate field
		$html .= '<td class="exchange_rate numeric">';
		$html .= '1'; // Exchange rate for base currency is always 1
		$html .= '</td>';

		// Render "Set Manually" checkbox
		$html .= '<td>';
		$html .= '</td>';

		// Render exchange rate markup field
		$html .= '</tr>';

		return $html;
	}
}
