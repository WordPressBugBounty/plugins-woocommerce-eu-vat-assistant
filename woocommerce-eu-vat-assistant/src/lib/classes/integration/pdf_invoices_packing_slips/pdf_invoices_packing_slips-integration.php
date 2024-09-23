<?php
namespace Aelia\WC\EU_VAT_Assistant\WCPDF;
if(!defined('ABSPATH')) { exit; } // Exit if accessed directly

use Aelia\WC\EU_VAT_Assistant\WC_Aelia_EU_VAT_Assistant;
use Aelia\WC\EU_VAT_Assistant\Definitions;

/**
 * Implements features to integrate with the PDF Invoices & Packing Slips plugin.
 *
 * @since 2.0.1.201215
 * @link https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/
 */
class PDF_Invoices_Packing_Slips_Integration {
	use \Aelia\WC\Traits\Singleton;

	/**
	 * Token to display merchant's VAT number.
	 *
	 * @var string
	 */
	const TOKEN_MERCHANT_VAT_NUMBER = 'merchant_vat_number';

	/**
	 * Initialisation function. Implemented for consistency with other integration classes.
	 *
	 * @return Aelia\WC\EU_VAT_Assistant\WCPDF\PDF_Invoices_Packing_Slips_Integration
	 */
	public static function init() {
		return static::instance();
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->set_hooks();
	}

	protected function set_hooks() {
		// Legacy filter. Needed because the PIPS Pro plugin still seems to use it, and replaces any
		// unknown token with an empty string, before the filter for "wpo_wcpdf_shop_address_settings_text" is
		// executed
		// @see WPO\WC\PDF_Invoices\Documents\Order_Document::get_settings_text()
		// @see WPO\WC\PDF_Invoices_Pro\Functions::make_replacements()
		add_filter('wpo_wcpdf_shop_address', array($this, 'wpo_wcpdf_shop_address_settings_text'), 1, 2);
		add_filter('wpo_wcpdf_shop_address_settings_text', array($this, 'wpo_wcpdf_shop_address_settings_text'), 11, 2);

		// Extend the settings page
		add_filter('wpo_wcpdf_settings_fields_general', array($this, 'wpo_wcpdf_settings_fields_general'), 10, 4);
	}

	/**
	 * Returns the tokens to be used to replace elements on the invoices.
	 *
	 * @return array
	 */
	protected static function get_tokens() {
		return apply_filters('wc_aelia_pips_integration_token_replacements', array(
			self::TOKEN_MERCHANT_VAT_NUMBER => implode('', WC_Aelia_EU_VAT_Assistant::settings()->get_requester_vat_number()),
		));
	}

	/**
	 * Replaces the EU VAT Assistant tokens found in the Shop Address text with their corresponding values.
	 *
	 * @param string $shop_address
	 * @param Order_Document\WPO\WC\PDF_Invoices\Document $pips_document
	 * @return string
	 */
	public function wpo_wcpdf_shop_address_settings_text($shop_address, $pips_document) {
		$tokens = apply_filters('wc_aelia_pips_integration_shop_address_token_replacements', self::get_tokens(), $shop_address, $pips_document);
		return str_replace(array_map(function($token) {
			// Wrap each token into double curly braces. That will ensure that we won't replace other
			// text around the content. Note: we use TRIPLE curly brances, because PHP will replace {$token}
			// with the value of the token. That is, if a token is "something", the string "{{{something}}}"
			// will become "{{something}}"
			return "{{{$token}}}";
		}, array_keys($tokens)), $tokens, $shop_address);
	}

	/**
	 * Adds the description the custom features added by the EU VAT Assistant to the  Shop Address setting's description.
	 *
	 * @param array $settings_fields
	 * @param string $page
	 * @param string $option_group
	 * @param string $option_name
	 * @return array
	 */
	public function wpo_wcpdf_settings_fields_general(array $settings_fields, $page, $option_group, $option_name) {
		foreach($settings_fields as $idx => $field) {
			if($field['id'] === 'shop_address') {
				$field['args']['description'] = ($field['args']['description'] ?? '') . self::get_euva_pips_address_customization_description();

				$settings_fields[$idx] = $field;
				break;
			}
		}

		return $settings_fields;
	}

	/**
	 * Returns the description of the tokens made available by the EU VAT Assistant for the Shop Address setting.
	 *
	 * @return string
	 */
	protected static function get_euva_pips_address_customization_description() {
		ob_start();
		echo __('The EU VAT Assistant offers the possibility to add some merchant information to the Shop Address displayed on your invoices.', Definitions::TEXT_DOMAIN);
		echo '<br />';
		echo __('Below you can find a list of the available tokens and their description.', Definitions::TEXT_DOMAIN);
		echo ' ';
		echo __('New tokens may be added in future versions of the plugin, or by addons.', Definitions::TEXT_DOMAIN);
		?>
		<style>
			.euva_settings.merchant_address_tokens {
				background-color: #eee;
				border: 1px solid #aaa;
				margin-top: 8px;
			}

			.euva_settings thead th {
				text-align: left;
				padding: 5px 5px 0 5px;
			}
			.euva_settings tbody td {
				vertical-align: top;
				padding: 5px;
			}

			.euva_settings .token_id {
				width: 10em;
			}
		</style>
		<table class="euva_settings merchant_address_tokens">
			<thead>
				<tr>
					<th class="token_id" scope="col"><?= __('Token', Definitions::TEXT_DOMAIN); ?></th>
					<th class="token_description" scope="col"><?= __('Description', Definitions::TEXT_DOMAIN); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="token_id">
						{{<?= self::TOKEN_MERCHANT_VAT_NUMBER ?>}}
					</td>
					<td class="token_description"><?php
						echo __('This token will be replaced by the requester VAT number entered in the VAT Number Validation section, inclusive of the country prefix.', Definitions::TEXT_DOMAIN);
						echo ' ';
						echo __('Other addons can replace this value dynamically, e.g. to show a different VAT number for specific countries.', Definitions::TEXT_DOMAIN);
					?></td>
				</tr>
			</tbody>
		</table>
		<?php
		return ob_get_clean();
	}
}