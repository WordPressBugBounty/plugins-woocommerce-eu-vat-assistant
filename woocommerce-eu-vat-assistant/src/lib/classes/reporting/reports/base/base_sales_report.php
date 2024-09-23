<?php
namespace Aelia\WC\EU_VAT_Assistant\Reports;
if(!defined('ABSPATH')) exit; // Exit if accessed directly

use Aelia\WC\EU_VAT_Assistant\WC_Aelia_EU_VAT_Assistant;
use Aelia\WC\EU_VAT_Assistant\Settings;
use Aelia\WC\EU_VAT_Assistant\Definitions;

/**
 * Renders the report containing the EU VAT for each country in a specific
 * period.
 */
abstract class Base_Sales_Report extends \Aelia\WC\EU_VAT_Assistant\Reports\Base_Report {
	// @var string Indicates which salkes region should be inclued (EU, non-EU, all)
	protected $sales_region_visible;
	// @var string Indicates which exchange rates should be used (stored with order or ECB).
	protected $exchange_rates_to_use;

	/**
	 * Indicates if the tax passed as a parameter should be skipped (i.e. excluded
	 * from the report).
	 *
	 * @param array tax_details An array of data describing a tax.
	 * @return bool True (tax should be excluded from the report) or false (tax
	 * should be displayed on the report).
	 */
	protected function should_skip($sale_data) {
		return false;
	}

	/**
	 * Indicates if reports should be rendered using the exchange rates associated
	 * with the orders.
	 *
	 * @return bool
	 */
	protected function should_use_orders_exchange_rates() {
		return ($this->exchange_rates_to_use === Definitions::FX_SAVED_WITH_ORDER);
	}

	public function __construct() {
		parent::__construct();

		// Store which tax types should be shown
		$this->taxes_to_show = get_value(Definitions::ARG_TAX_TYPE, $_REQUEST, Definitions::TAX_MOSS_ONLY);
		// Store which exchange rates should be used
		$this->exchange_rates_to_use = get_value(Definitions::ARG_EXCHANGE_RATES_TYPE, $_REQUEST, Definitions::FX_SAVED_WITH_ORDER);
	}

	/**
	 * Returns the meta keys of the order items that should be loaded by the report.
	 * For this report, line totals and cost indicate the price of products and
	 * the price of shipping, respectively.
	 *
	 * @return array
	 */
	protected function get_order_items_meta_keys() {
		return array(
			// _line_total: total charged for order items
			'_line_total',
			// cost: total charged for shipping
			'cost',
		);
	}

	/**
	 * Returns the tax data for the report.
	 *
	 * @return array The tax data.
	 */
	protected function get_sales_data() {
		// This method must be implemented by descendant classes
		return array();
	}

	/**
	 * Retrieves the refunds for the specified period and adds them to the tax
	 * data.
	 *
	 * @param array tax_data The tax data to which refund details should be added.
	 * @return array The tax data including the refunds applied in the specified
	 * period.
	 */
	protected function get_refunds_data() { // NOSONAR This is a dummy method
		// This method must be implemented by descendant classes
		return array();
	}

	/**
	 * Get the data for the report.
	 *
	 * @return string
	 */
	public function get_main_chart() {
		// This method must be implemented by descendant classes
	}

	protected function render_group_header($moss_group, $report_columns) {
		// This method must be implemented by descendant classes
	}

	/**
	 * Renders a header on top of the standard reporting UI.
	 */
	protected function render_ui_header() {
		include(WC_Aelia_EU_VAT_Assistant::instance()->path('views') . '/admin/reports/sales-report-header.php');
	}
}
