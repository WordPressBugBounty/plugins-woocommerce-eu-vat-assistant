<?php
namespace Aelia\WC\EU_VAT_Assistant;
if(!defined('ABSPATH')) { exit; } // Exit if accessed directly

/**
 * Runs automatic updates for the EU VAT Assistant plugin.
 **/
class WC_Aelia_EU_VAT_Assistant_Install extends \Aelia\WC\Aelia_Install {
	// @var string The name of the lock that will be used by the installer to prevent race conditions.
	protected $lock_name = 'WC_AELIA_EU_VAT_ASSISTANT';
	// @var string The text domain, used for localisation.
	protected $text_domain;

	/**
	 * Returns the instance of the EU VAT Assistant plugin.
	 *
	 * @return \Aelia\WC\EU_VAT_Assistant\WC_Aelia_EU_VAT_Assistant
	 * @since 1.3.20.150330
	 */
	protected function EUVA() {
		return WC_Aelia_EU_VAT_Assistant::instance();
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->logger = $this->EUVA()->get_logger();
		$this->text_domain = DEFINITIONS::TEXT_DOMAIN;
	}

	/**
	 * Runs the updates required by the EU VAT Assistant 1.1.3.150111 and later.
	 *
	 * @return bool
	 * @since 1.1.3.150111
	 */
	protected function update_to_1_1_3_150111() {
		global $wpdb;

		// Adds an extra column to the taxes table, to keep track of whic country
		// each tax should be paid to
		$table_name = $wpdb->prefix . 'woocommerce_tax_rates';
		$column_name = 'tax_payable_to_country';
		if($this->column_exists($table_name, $column_name)) {
			return true;
		}
		/* Note: we are using the extremely large VARCHAR(200) type for the
		 * "tax_payable_to_country" column because the core "tax_rate_country"
		 * column is also using the same. Since both columns are going to contain a
		 * country, it makes sense that they have the same type (although the size
		 * is probably excessive).
		 */
		return $this->add_column($table_name, $column_name, 'VARCHAR(200)');
	}

	/**
	 * Runs the updates required by the EU VAT Assistant 1.2.0.150215 and later.
	 *
	 * @return bool
	 * @since 1.2.0.150215
	 */
	protected function update_to_1_2_0_150215() {
		$this->start_transaction();

		try {
			$charset_collate = $this->wpdb->get_charset_collate();
			$table_name = $this->wpdb->prefix . 'aelia_exchange_rates_history';
			$SQL = "
				CREATE TABLE IF NOT EXISTS `$table_name` (
					`provider_name` varchar(170) NOT NULL,
					`base_currency` char(3) NOT NULL,
					`rates_date` datetime NOT NULL,
					`rates` longtext DEFAULT NULL,
					`date_updated` datetime DEFAULT NULL,
					PRIMARY KEY (`provider_name`, `rates_date`)
				) {$charset_collate};
			";
			$result = $this->exec($SQL);

			if($result === false) {
				$this->add_message(E_USER_ERROR,
													 sprintf(__('Creation of table "%s" failed. Please ' .
																			'check PHP error log for error messages ' .
																			'related to the operation.', $this->text_domain),
																	 $table_name));
				$this->rollback_transaction();
			}
			else {
				$this->add_message(E_USER_NOTICE,
													 sprintf(__('Table "%s" created successfullly.', $this->text_domain),
																	 $table_name));
				$this->commit_transaction();
				$result = true;
			}
		}
		catch(\Exception $e) {
			$this->rollback_transaction();
			$this->log($e->getMessage());
			$this->add_message(E_USER_ERROR, $e->getMessage());
			return false;
		}
		return (bool)$result;
	}

	/**
	 * Overrides standard update method to ensure that requirements for update are
	 * in place.
	 *
	 * @param string plugin_id The ID of the plugin.
	 * @param string new_version The new version of the plugin, which will be
	 * stored after a successful update to keep track of the status.
	 * @return bool
	 */
	public function update($plugin_id, $new_version) {
		//delete_option($plugin_id);
		return parent::update($plugin_id, $new_version);
	}
}
