<?php
namespace Aelia\WC\EU_VAT_Assistant\Validation;
if(!defined('ABSPATH')) { exit; } // Exit if accessed directly

use \nusoap_client;
use \wsdl;
use \Aelia\WC\EU_VAT_Assistant\WC_Aelia_EU_VAT_Assistant;
use \Aelia\WC\EU_VAT_Assistant\Definitions;

/**
 * Handles the validation of EU VAT numbers using the VIES service.
 */
class EU_VAT_Number_Validator extends VAT_Number_Validator {
	/**
	 * Identifies the service used to validate a VAT number. This information will be passed with the response
	 *
	 * @var string
	 */
	// TODO Replace the ID with the one returned by the VIES validation service, after the refactoring
	const VALIDATION_SERVICE_ID = 'vies';

	/**
	 * Identifies the validator, to distinguish between the ones used for
	 * different countries.
	 */
	public static $id = 'eu-vat-number-validator';

	/**
	 * An associative array of country code => EU VAT prefix pairs.
	 * @var array
	 */
	protected $vat_country_prefixes = array();

	/**
	 * Returns the countries supported by the validator.
	 *
	 * @return array
	 */
	public static function get_supported_countries() {
		return WC_Aelia_EU_VAT_Assistant::instance()->get_eu_vat_countries();
	}

	/**
	 * Returns sn associative array of country code => EU VAT prefix pairs.
	 *
	 * @return array
	 */
	protected function get_vat_country_prefixes() {
		if(empty($this->vat_country_prefixes)) {
			$this->vat_country_prefixes = array();
			foreach(self::get_supported_countries() as $country_code) {
				$this->vat_country_prefixes[$country_code] = $country_code;
			}

			// Correct vat prefixes that don't match the country code and add some
			// extra ones
			// Greece
			$this->vat_country_prefixes['GR'] = 'EL';
			// Isle of Man
			$this->vat_country_prefixes['IM'] = 'GB';
			// Monaco
			$this->vat_country_prefixes['MC'] = 'FR';
		}

		// Call the legacy filter
		$this->vat_country_prefixes = apply_filters('wc_aelia_euva_vat_country_prefixes', $this->vat_country_prefixes);

		// New filter to allow 3rd parties to alter the prefixes
		// @since 1.15.0.201119
		return apply_filters('wc_aelia_euva_' . static::$id . '_vat_country_prefixes', $this->vat_country_prefixes);
	}

	/**
	 * Returns the minimum lengths of VAT numbers for the countries supported by this validator.
	 *
	 * @return array
	 * @since 1.15.0.201119
	 */
	protected function get_countries_vat_number_min_lengths() {
		return array(
			'AT' => 9,
			'BE' => 10,
			'BG' => 9,
			'CY' => 9,
			'CZ' => 8,
			'DE' => 9,
			'DK' => 8,
			'EE' => 9,
			'EL' => 9,
			'ES' => 9,
			'FI' => 8,
			'FR' => 11,
			'GB' => 5,
			'HR' => 11,
			'HU' => 8,
			'IE' => 8,
			'IT' => 11,
			'LT' => 9,
			'LV' => 11,
			'LU' => 8,
			'MT' => 8,
			'NL' => 12,
			'PL' => 10,
			'PT' => 9,
			'RO' => 2,
			'SE' => 12,
			'SI' => 8,
			'SK' => 10,
		);
	}

	/**
	 * Validates an EU VAT number.
	 *
	 * @param string country The country code to which the VAT number belongs.
	 * @param string vat_number The VAT number to validate.
	 * @param string requester_country The country code of the requester.
	 * @param string requester_vat_number The VAT number of the requester.
	 * @return array|bool An array with the validation response returned by the
	 * VIES service, or false when the request could not be sent for some reason.
	 * @link https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl
	 * @since 1.15.0.201119
	 */
	public function perform_vat_number_validation($country, $vat_number, $requester_country = null, $requester_vat_number = null) {
		// TODO Extract the logic used to call the VIES service and fetch the response to a separate class, inheriting from Aelia\WC\EU_VAT_Assistant\Validation\Services

		// Cache the WSDL
		$wsdl = get_transient('VIES_WSDL');
		if(empty($wsdl) || self::debug_mode()) {
			$wsdl = new wsdl('https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl', '', '', '', '', 5);
			// Cache the WSDL for one minute. Sometimes VIES returns an invalid WSDL,
			// caching it for too long could cause the whole validation system to fail
			set_transient('VIES_WSDL', $wsdl, 60);
		}

		// Create SOAP client
		$client = new nusoap_client($wsdl, 'wsdl');
		// Ensure that UTF-8 encoding is used, so that the client won't crash when
		// "odd" characters are used
		$client->decode_utf8 = false;
		$client->soap_defencoding = 'UTF-8';

		// Using CURL seems to throw an "error 60 - Could not validate host certificate". Removing
		// this line solves the issue
		// @since 1.13.1.200319
		// @link https://wordpress.org/support/topic/vat-number-not-validating-for-requester-country-greece/
		// @link https://wordpress.org/support/topic/vat-number-not-validating-for-requester-country-poland/
		//$client->setUseCurl(true);

		// Check if any error occurred initialising the SOAP client. We won't be able
		// to continue, in such case.
		$error = $client->getError();
		if($error) {
			$this->errors[] = __('An error occurred initialising SOAP client.', Definitions::TEXT_DOMAIN) .
												' ' .
												sprintf(__('Error message: "%s".', Definitions::TEXT_DOMAIN), $error);
			// Log the initialisation error
			// @since 1.9.7.190221
			self::get_logger()->error(__('An error occurred initialising SOAP client.', Definitions::TEXT_DOMAIN), array(
				'Error message' => $error,
			));

			return false;
		}

		$request_args = array(
			'countryCode' => $this->vat_prefix,
			'vatNumber' => $this->vat_number,
		);

		// Add the Requester details, if specified
		// @since 1.9.0.181022
		if(!empty($this->requester_vat_prefix) && !empty($this->requester_vat_number)) {
			$request_args['requesterCountryCode'] = $this->requester_vat_prefix;
			$request_args['requesterVatNumber'] = $this->requester_vat_number;
		}

		// Log the request arguments
		// @since 1.10.1.191108
		self::get_logger()->debug(__('VAT number validation request.', Definitions::TEXT_DOMAIN), array(
			'Request Arguments' => $request_args,
		));

		// Call the VIES service to validate the VAT number
		$response = $client->call('checkVatApprox', $request_args);

		self::get_logger()->debug(__('VAT number validation complete.', Definitions::TEXT_DOMAIN), array(
			'Country Code' => $this->vat_prefix,
			'VAT Number' => $this->vat_number,
			'VIES Response' => $response,
		));

		// Format the response before returning it
		// @since 2.0.1.201215
		if(is_array($response)) {
			return self::format_vies_response($response);
		}

		return $response;
	}

	/**
	 * Given an array with the VIES respose, returns an array with a the standard format
	 * used by all validators.
	 *
	 * @param array $response
	 * @return array
	 * @since 2.0.1.201215
	 */
	protected static function format_vies_response(array $response) {
		// Change all the keys to lower-case. This is to avoid issues with keys being
		// returned like "faultString", "FaultString", "faultstring" by different versions
		// of the VIES service
		// @since 1.9.16.191004
		$response = array_change_key_case($response, CASE_LOWER);

		if(isset($response['traderaddress'])) {
			// If the trader address was returned by the VIES service, take it as it is
			// @since 2.0.9.210118
			$trader_address = $response['traderaddress'];
		}
		else {
			// If the trader address was NOT returned by the VIES service, try to build it from
			// elements like the street, post code and city, if present
			// @since 2.0.9.210118
			$trader_address = trim(implode(' ', array(
				$response['traderstreet'] ?? '',
				$response['traderpostcode'] ?? '',
				$response['tradercity'] ?? '',
			)));
		}

		// Format the response so that it's consistent with the one returned by
		// other services and not coupled with the specific format used by the VIES service
		return array(
			'valid' => isset($response['valid']) ? ($response['valid'] === 'true') : false,
			'company_name' => $response['tradername'] ?? '',
			'company_address' => $trader_address,
			'errors' => isset($response['faultstring']) ? array($response['faultstring']) : array(),
			// Pass the consultation number as a top level field, like the other validators do
			// @since 2.0.3.201229
			'requestidentifier' => $response['requestidentifier'] ?? '',
			'raw_response' => $response,
			// Add the validation source, for reference
			// @since 2.0.3.201229
			// TODO Replace the static ID with the one returned by the validation service class
			'validation_source' => self::VALIDATION_SERVICE_ID,
		);
	}
}
