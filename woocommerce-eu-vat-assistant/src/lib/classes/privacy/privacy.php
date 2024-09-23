<?php
namespace Aelia\WC\EU_VAT_Assistant;
if(!defined('ABSPATH')) { exit; } // Exit if accessed directly

if(!class_exists('\WC_Abstract_Privacy')) {
	return;
}

/**
 * Handles Privacy Policy tasks.
 *
 * @since 2.0.1.201215
 */
class WC_Aelia_EU_VAT_Assistant_Privacy extends \WC_Abstract_Privacy {
	use \Aelia\WC\Traits\Singleton;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(__('EU VAT Assistant', Definitions::TEXT_DOMAIN));

		$this->add_exporter(WC_Aelia_EU_VAT_Assistant::$plugin_slug . '-order-data', __('WooCommerce EU VAT Order Data', Definitions::TEXT_DOMAIN), array($this, 'order_data_exporter'));
		$this->add_exporter(WC_Aelia_EU_VAT_Assistant::$plugin_slug . '-customer-data', __('WooCommerce EU VAT Customer Data', Definitions::TEXT_DOMAIN), array($this, 'customer_data_exporter'));

		// Enable the "data eraser" for the EU VAT Assistant, if such setting is configured
		// @since 2.0.4.201231
		if(WC_Aelia_EU_VAT_Assistant::settings()->get(Settings::FIELD_PRIVACY_REMOVE_VAT_DATA_ON_DATA_ERASURE)) {
			// The VAT data should not necessarily be deleted, as it's required for tax compliance
			$this->add_eraser(WC_Aelia_EU_VAT_Assistant::$plugin_slug . '-customer-data', __('WooCommerce EU VAT Customer Data', Definitions::TEXT_DOMAIN), array($this, 'customer_data_eraser'));
			$this->add_eraser(WC_Aelia_EU_VAT_Assistant::$plugin_slug . '-order-data', __('WooCommerce EU VAT Order Data', Definitions::TEXT_DOMAIN), array($this, 'order_data_eraser'));
		}
	}

	/**
	 * Returns a list of orders.
	 *
	 * @param string  $email_address
	 * @param int $page
	 *
	 * @return array WP_Post
	 */
	protected function get_orders($email_address, $page) {
		$user = get_user_by('email', $email_address); // Check if user has an ID in the DB to load stored personal data.

		$order_query = array(
			'limit' => 10,
			'page' => $page,
		);

		if($user instanceof WP_User) {
			$order_query['customer_id'] = (int)$user->ID;
		}
		else {
			$order_query['billing_email'] = $email_address;
		}

		return wc_get_orders($order_query);
	}

	/**
	 * Returns a list of the VAT number validation services that may be used
	 * by the EU VAT Assistant and related addons.
	 *
	 * @return string
	 */
	protected static function get_vat_validation_services_list() {
		$services = apply_filters('wc_aelia_euva_privacy_vat_validation_services', array(
			sprintf('<a href="%2$s">%1$s</a>', __('EU VIES VAT Number validation service', Definitions::TEXT_DOMAIN), 'https://ec.europa.eu/taxation_customs/vies/'),
		));

		return '<ol><li>' . implode('</li><li>', $services) . '</li></ol>';
	}

	/**
	 * Return the message of the privacy with suggestions to help merchants preparing their privacy policy.
	 */
	public function get_privacy_message() {
		return wpautop(implode(' ', array(
			__('By using this extension, you may be storing personal data or sharing data with an external service.', Definitions::TEXT_DOMAIN),
			__('In the specific, the EU VAT Assistant handles the following data:', Definitions::TEXT_DOMAIN),
			'<ol>',
			'<li>' . __("Customer's country (billing and/or shipping)", Definitions::TEXT_DOMAIN) . '</li>',
			'<li>' . __("Customer's VAT number", Definitions::TEXT_DOMAIN) . '</li>',
			'<li>' . __("Customer's IP address", Definitions::TEXT_DOMAIN) . '</li>',
			'</ol>',
			__('The EU VAT Assistant handles the data for the following purposes:', Definitions::TEXT_DOMAIN),
			'<ol>',
			'<li>' . __("To collect evidence about customer's location, for compliance with the EU VAT MOSS regulations.", Definitions::TEXT_DOMAIN) . '</li>',
			'<li>' .
				__("To validate customer's VAT number and apply the appropriate VAT regime (exemption, reverse charge).", Definitions::TEXT_DOMAIN) .
				' ' .
				__("The validation process is performed by one or more of the following services, depending on your setup:", Definitions::TEXT_DOMAIN) .
				self::get_vat_validation_services_list(),
			'</li>',
			'</ol>',
			__('The EU VAT Assistant retains the data for the following purposes:', Definitions::TEXT_DOMAIN),
			'<ol>',
			'<li>' . __("To collect evidence about customer's location, for compliance with the EU VAT MOSS regulations.", Definitions::TEXT_DOMAIN) . '</li>',
			'<li>' . __("To validate customer's VAT number and apply the appropriate VAT regime (exemption, reverse charge).", Definitions::TEXT_DOMAIN) . '</li>',
			'<li>' . __("To store evidence for compliance with other tax regulations, which require the data to be stored for a set period of time.", Definitions::TEXT_DOMAIN) . '</li>',
			'</ol>',
		)));
	}

	/**
	 * Handle exporting data for Orders.
	 *
	 * @param string $email_address E-mail address to export.
	 * @param int $page Pagination of data.
	 *
	 * @return array
	 */
	public function order_data_exporter($email_address, $page = 1) {
		$done = false;
		$data_to_export = array();

		$orders = $this->get_orders($email_address, (int)$page);

		$done = true;

		if(count($orders) > 0) {
			foreach($orders as $order) {
				$data_to_export[] = array(
					'group_id' => 'woocommerce_orders',
					'group_label' => __('Orders', Definitions::TEXT_DOMAIN),
					'item_id' => 'order-' . $order->get_id(),
					'data' => array(
						array(
							'name' => __('VAT Number', Definitions::TEXT_DOMAIN),
							'value' => $order->get_meta('vat_number'),
						),
						array(
							'name' => __('VAT Number Country', Definitions::TEXT_DOMAIN),
							'value' => $order->get_meta('_vat_country'),
						),
						array(
							'name' => __('VAT Number Validation Result', Definitions::TEXT_DOMAIN),
							'value' => $order->get_meta('_vat_number_validated'),
						),
					),
				);
			}

			// Process up to 10 orders at a time
			$done = count($orders) < 10;
		}

		return array(
			'data' => $data_to_export,
			'done' => $done,
		);
	}

	/**
	 * Finds and exports customer data by email address.
	 *
	 * @param string $email_address The user email address.
	 * @param int $page Page.
	 * @return array An array of personal data in name value pairs
	 */
	public function customer_data_exporter($email_address, $page) {
		$user = get_user_by('email', $email_address); // Check if user has an ID in the DB to load stored personal data.
		$data_to_export = array();

		if($user instanceof \WP_User) {
			$data_to_export[] = array(
				'group_id' => 'woocommerce_customer',
				'group_label' => __('Customer Data', Definitions::TEXT_DOMAIN),
				'item_id' => 'user',
				'data' => array(
					array(
						'name' => __('VAT Number', Definitions::TEXT_DOMAIN),
						'value' => get_user_meta($user->ID, 'vat_number', true),
					),
				),
			);
		}

		return array(
			'data' => $data_to_export,
			'done' => true,
		);
	}

	/**
	 * Finds and erases customer data by email address.
	 *
	 * @param string $email_address The user email address.
	 * @param int $page Page.
	 * @return array An array of personal data in name value pairs
	 */
	public function customer_data_eraser($email_address, $page) {
		$page = (int)$page;
		$user = get_user_by('email', $email_address); // Check if user has an ID in the DB to load stored personal data.

		$vat_number = get_user_meta($user->ID, 'vat_number', true);

		$items_removed=  false;
		$messages = array();

		if(!empty($vat_number)) {
			$items_removed = true;
			delete_user_meta($user->ID, 'vat_number');
			delete_user_meta($user->ID, '_vat_country');
			delete_user_meta($user->ID, '_vat_number_validated');

			$messages[] = __('EU VAT Assistant - User VAT Data Erased.', Definitions::TEXT_DOMAIN);
		}

		return array(
			'items_removed' => $items_removed,
			'items_retained' => false,
			'messages' => $messages,
			'done' => true,
		);
	}

	/**
	 * Finds and erases order data by email address.
	 *
	 * @since 3.4.0
	 * @param string $email_address The user email address.
	 * @param int    $page  Page.
	 * @return array An array of personal data in name value pairs
	 */
	public function order_data_eraser($email_address, $page) {
		$orders = $this->get_orders($email_address, (int) $page);

		$items_removed=  false;
		$items_retained = false;
		$messages = array();

		foreach((array)$orders as $order) {
			$order = wc_get_order($order->get_id());

			list($removed, $retained, $msgs) = $this->maybe_handle_order($order);
			$items_removed  |= $removed;
			$items_retained |= $retained;
			$messages = array_merge($messages, $msgs);
		}

		// Tell core if we have more orders to work on still
		$done = count($orders) < 10;

		return array(
			'items_removed' => $items_removed,
			'items_retained' => $items_retained,
			'messages' => $messages,
			'done' => $done,
		);
	}

	/**
	 * Handle eraser of data tied to orders.
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	protected function maybe_handle_order($order) {
		$order_id = $order->get_id();

		$vat_number = $order->get_meta($order_id, 'vat_number', true);
		if(empty($vat_number)) {
			return array(false, false, array());
		}

		$order->delete_meta_data('vat_number');
		$order->delete_meta_data('_vat_country');
		$order->delete_meta_data('_vat_number_validated');
		$order->save_meta_data();

		return array(true, false, array(__('EU VAT Assistant - Order VAT Data Erased.', Definitions::TEXT_DOMAIN)));
	}
}
