<?php
namespace Aelia\WC\EU_VAT_Assistant\Validation;
if(!defined('ABSPATH')) { exit; } // Exit if accessed directly

use \Aelia\WC\EU_VAT_Assistant\Definitions;

/**
 * "Dummy" validator, used when there isn't a validator available for a specific country.
 *
 * NOTE
 * This class doesn't perform any validation and always returns an error code.
 *
 * @since 1.15.0.201119
 */
class Dummy_VAT_Number_Validator extends VAT_Number_Validator {
	/**
	 * Identifies the validator, to distinguish between the ones used for
	 * different countries.
	 */
	public static $id = 'dummy-vat-number-validator';

	/**
	 * An associative array of country code => EU VAT prefix pairs.
	 * @var array
	 */
	protected $vat_country_prefixes = array();

	/**
	 * This validator doesn't support any specific country.
	 *
	 * @return array
	 */
	public static function get_supported_countries() { // NOSONAR php:S4144
		return array();
	}

	/**
	 * This validator doesn't handle VAT numbers.
	 *
	 * @return array
	 */
	protected function get_countries_vat_number_min_lengths() { // NOSONAR php:S4144
		return array();
	}

	/**
	 * This validator doesn't perform any actual validation.
	 *
	 * @param string $country
	 * @param string $vat_number
	 * @param string $requester_country
	 * @param string $requester_vat_number
	 * @return array
	 * @since 1.15.0.201119
	 */
	protected function perform_vat_number_validation($country, $vat_number, $requester_country = null, $requester_vat_number = null) {
		return array();
	}

	/**
	 * Validates a VAT number.
	 *
	 * @param string country The country code to which the VAT number belongs.
	 * @param string vat_number The VAT number to validate.
	 * @param string requester_country The country code of the requester.
	 * @param string requester_vat_number The VAT number of the requester.
	 * @return array|bool An array with the validation response returned by the
	 * VIES service, or false when the request could not be sent for some reason.
	 * @link https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl
	 */
	public function validate_vat_number($country, $vat_number, $requester_country = null, $requester_vat_number = null) {
		self::get_logger()->notice(__('Used dummy VAT number validator for unsupported VAT number.', Definitions::TEXT_DOMAIN), array(
			'Country Code' => $country,
			'VAT Number' => $vat_number,
			'Requester Country Code' => $requester_country,
			'Requester VAT Number' => $requester_vat_number,
		));

		return array(
			'valid' => null,
			'company_name' => '',
			'company_address' => '',
			'errors' => array(
				__('A VAT number validator is not available for this country.', Definitions::TEXT_DOMAIN),
			),
			'raw_response' => null,
			// Add the validation source, for reference.
			// Normally, the source is returned by the validation service. In this case, we don't have such
			// a service, therefore we can just use the ID of the dummy validator class
			// @since 2.0.3.201229
			'validation_source' => static::$id,
		);
	}
}
