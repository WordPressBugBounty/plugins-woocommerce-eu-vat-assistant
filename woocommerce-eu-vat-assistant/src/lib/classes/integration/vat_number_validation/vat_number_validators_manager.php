<?php
namespace Aelia\WC\EU_VAT_Assistant\Validation;
if(!defined('ABSPATH')) { exit; } // Exit if accessed directly

use \Aelia\WC\EU_VAT_Assistant\WC_Aelia_EU_VAT_Assistant;
use \Aelia\WC\EU_VAT_Assistant\Definitions;

/**
 * Implement functions to manage the VAT number validation classes
 * and their instances.
 *
 * @since 1.15.0.201125
 */
class VAT_Number_Validators_Manager extends \Aelia\WC\Base_Class {
	use \Aelia\WC\EU_VAT_Assistant\Traits\Logger_Trait;

	/**
	 * A map of { country code -> VAT number validator class } pairs. Used to
	 * determine which validator to use for a specific country.
	 *
	 * @var array
	 */
	protected static $vat_number_validators = array();

	/**
	 * Registers the available VAT number validators.
	 *
	 * @since 1.15.0.201119
	 */
	protected static function get_vat_number_validators() {
		if(empty(self::$vat_number_validators)) {
			foreach(apply_filters('wc_aelia_register_euva_validators', array(
				'Aelia\WC\EU_VAT_Assistant\Validation\EU_VAT_Number_Validator',
			)) as $validator_class) {
				foreach($validator_class::get_supported_countries() as $country) {
					self::$vat_number_validators[$country] = $validator_class;
				}
			}
			self::get_logger()->debug(__('VAT number validators registered.', Definitions::TEXT_DOMAIN), array(
				'Registered Validators' => self::$vat_number_validators,
			));
		}

		// Allow 3rd parties to alter the validators assigned to each country
		// @since 2.0.1.201215
		return apply_filters('wc_aelia_euva_validators', self::$vat_number_validators);
	}

	/**
	 * Given a country code, returns the validator to be used to validate
	 * a VAT number from that country.
	 *
	 * @param string $country
	 * @return Aelia\WC\EU_VAT_Assistant\Validation\VAT_Number_Validator
	 * @since 1.15.0.201119
	 */
	public static function get_validator_for_country($country) {
		$validator_class = self::get_vat_number_validators()[$country] ?? '\Aelia\WC\EU_VAT_Assistant\Validation\Dummy_VAT_Number_Validator';

		self::get_logger()->debug(__('Found VAT number validator for country.', Definitions::TEXT_DOMAIN), array(
			'Country' => $country,
			'Validator Class' => $validator_class,
		));

		return !empty($validator_class) ? $validator_class::instance() : null;
	}

	/**
	 * Returns a list of countries that have a VAT number validator assigned to them
	 *
	 * @return array
	 * @since 1.14.13.201103
	 */
	public static function get_countries_with_vat_number_validators() {
		// Return the list of country codes linked to a validator
		return apply_filters('wc_aelia_euva_countries_with_vat_number_validators', array_keys(self::get_vat_number_validators()));
	}
}