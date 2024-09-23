<?php
namespace Aelia\WC\EU_VAT_Assistant\Validation;
if(!defined('ABSPATH')) { exit; } // Exit if accessed directly

use \Aelia\WC\EU_VAT_Assistant\Definitions;

/**
 * Implement functions to manage the VAT number validation services and their instances.
 *
 * @since 2.0.3.201229
 */
class VAT_Number_Validation_Services_Manager extends \Aelia\WC\Base_Class {
	use \Aelia\WC\EU_VAT_Assistant\Traits\Logger_Trait;

	/**
	 * A map of { country code -> VAT number validation service class } pairs. Used to
	 * determine which validator to use for a specific country.
	 *
	 * @var array
	 */
	protected static $vat_number_validation_services = array();

	/**
	 * Registers the available VAT number validator services.
	 */
	public static function get_vat_number_validation_services() {
		if(empty(self::$vat_number_validation_services)) {
			foreach(apply_filters('wc_aelia_register_euva_validation_services', array(
				// Add built-in validation services, such as the VIES one, here
			)) as $validation_service_class) {
				self::$vat_number_validation_services[$validation_service_class::get_id()] = $validation_service_class;
			}
			self::get_logger()->debug(__('VAT number validation services registered.', Definitions::TEXT_DOMAIN), array(
				'Registered Validation Services' => self::$vat_number_validation_services,
			));
		}

		// Allow 3rd parties to alter the validators assigned to each country
		// @since 2.0.1.201215
		return apply_filters('wc_aelia_euva_validation_services', self::$vat_number_validation_services);
	}

	/**
	 * Given a country code, returns the validator to be used to validate
	 * a VAT number from that country.
	 *
	 * @param string $country
	 * @return array
	 */
	public static function get_validation_services_for_country($country): array {
		$services = array();

		foreach(self::get_vat_number_validation_services() as $class) {
			if(in_array($country, $class::get_supported_countries())) {
				$services[] = $class;
			}
		}

		if(!empty($services)) {
			self::get_logger()->debug(__('Found VAT number validation services for the specified country.', Definitions::TEXT_DOMAIN), array(
				'Country' => $country,
				'Services' => $services,
			));
		}
		else {
			$services[] = '\Aelia\WC\EU_VAT_Assistant\Validation\Services\Dummy_VAT_Number_Validation_Service';

			self::get_logger()->debug(
				__('No validation service was found for the specified country.', Definitions::TEXT_DOMAIN) . ' ' .
				__('Returned Dummy Validation Service as a fallback.', Definitions::TEXT_DOMAIN),
				array(
				'Country' => $country,
			));
		}

		return $services;
	}

	/**
	 * Given an ID, returns the validation service registered with such ID, or null
	 * if that service is not found.
	 *
	 * @param string $service_id
	 * @param bool $return_dummy_if_not_found Indicates if the dummy service should be
	 * returned if a service with the specified ID is not found.
	 * @return void
	 */
	public static function get_validation_service_by_id(string $service_id, $return_dummy_if_not_found = true) {
		$service = self::get_vat_number_validation_services()[$service_id] ?? null;

		// If a service with the specified ID is not found and $return_dummy_if_not_found is set, returned
		// the dummy service as a fallback
		if(empty($service) && $return_dummy_if_not_found) {
			$service = '\Aelia\WC\EU_VAT_Assistant\Validation\Services\Dummy_VAT_Number_Validation_Service';
		}

		self::get_logger()->debug(__('Could not find a VAT number validation service with the specified ID.', Definitions::TEXT_DOMAIN), array(
			'Service ID' => $service_id,
		));

		return $service;
	}
}