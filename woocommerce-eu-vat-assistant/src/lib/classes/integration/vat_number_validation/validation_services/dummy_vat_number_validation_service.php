<?php
namespace Aelia\WC\EU_VAT_Assistant\Validation\Services;

use Aelia\WC\EU_VAT_Assistant\Definitions;

if(!defined('ABSPATH')) { exit; } // Exit if accessed directly

/**
 * Implements a dummy VAT number validation service. This service doesn't validate any number and
 * always returns a "null" response.
 *
 * @since 2.0.3.201229
 */
class Dummy_VAT_Number_Validation_Service extends VAT_Number_Validation_Service implements IVAT_Number_Validation_Service{
	/**
	 * Error code. Indicates that the service is just a dummy, which doesn't perform any validation.
	 *
	 * @var string
	 * @since 2.0.3.201229
	 */
	const ERR_DUMMY_VALIDATION_SERVICE = 'dummy-validation-service';

	/**
	 * Identifies the validator, to distinguish between the ones used for
	 * different countries.
	 */
	public static $id = 'dummy-vat-number-validation-service';

	/**
	 * Describes the validator.
	 *
	 * @var string
	 */
	public static $name = 'Dummy VAT Number Validation Service. Placeholder, does not perform any actual validation.';

	/**
	 * Returns the countries supported by the validation service.
	 *
	 * @return array
	 */
	public static function get_supported_countries(): array {
		return array();
	}

	/**
	 * Returns the settings required by the service.
	 *
	 * @param \Aelia\WC\Settings $settings_controller
	 * @return array
	 */
	public static function get_settings(\Aelia\WC\Settings $settings_controller): array { // NOSONAR
		return array();
	}

	/**
	 * Validates the settings required by the validation service.
	 *
	 * @param array $settings
	 * @param array $errors Returns the list of errors occurred while validating the settings.
	 * @return bool
	 */
	public static function validate_settings(array $settings, array &$errors = array()): bool {
		// The settings for this gateway don't need to be validated. If either the client ID, or the
		// Client Secret is empty, the client will disable itself
		return true;
	}

	/**
	 * Indicates if the validation service is available (e.g. if it has been initialised correctly).
	 *
	 * @return bool
	 * @since 1.0.1.201229
	 */
	public function is_available(): bool {
		return true;
	}

	/**
	 * Mocks the validation of a VAT number.
	 *
	 * @param string $country The country code to which the VAT number belongs. Not used by the HMRC service.
	 * @param string $vat_number The VAT number to validate.
	 * @param string $requester_country The country code of the requester. Not used by the HMRC service.
	 * @param string $requester_vat_number The VAT number of the requester.
	 * @return WP_Error An error indicating that the service is just a dummy and can't perform any validation.
	 */
	public function validate_vat_number($country, $vat_number, $requester_country = null, $requester_vat_number = null) {
		self::$logger->info($err_msg = implode(' ', array(
			__('A VAT number was passed to the Dummy Validator Service.', Definitions::TEXT_DOMAIN),
			__('This service is just a placeholder. No validation has been performed.', Definitions::TEXT_DOMAIN),
		)), array(
			'ID' => static::get_id(),
			'Target Country' => $country,
			'Target VAT Number' => $vat_number,
			'Requester Country' => $requester_country,
			'Requester VAT Number' => $requester_vat_number,
		));

		return array_merge(self::get_empty_validation_response(array($err_msg)), array(
			'validation_source' => static::get_id(),
		));
	}
}
