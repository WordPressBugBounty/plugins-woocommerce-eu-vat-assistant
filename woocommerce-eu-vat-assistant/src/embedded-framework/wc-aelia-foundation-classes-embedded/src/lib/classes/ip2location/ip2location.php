<?php
namespace Aelia\WC;
if(!defined('ABSPATH')) { exit; } // Exit if accessed directly

use Exception;
use GeoIp2\Database\Reader;
use WP_Error;

/**
 * Handles the retrieval of Geolocation information from an IP Address.
 */
class IP2Location extends Base_Class {
	protected $text_domain = 'aelia_ip2location';

	// @var Reder The instance of the MaxMind database reader.
	protected $_db_reader;

	/**
	 * The name of the MaxMind database to utilize.
	 */
	const DATABASE = 'GeoLite2-City';

	/**
	 * The extension for the MaxMind database.
	 */
	const DATABASE_EXTENSION = '.mmdb';

	public static $geoip_db_file = 'GeoLite2-City.mmdb';

	// @var array An array of error messages.
	protected $errors = array();

	// @var array An array used to cache the results of geolocation detection
	protected $detected_cities = array();

	/**
	 * Renders a button that allows to trigger the manual installation. The button
	 * is only accessible to users with the proper permission.
	 *
	 * @return string The HTML code for the button.
	 * @since 1.6.1.150728
	 */
	public static function get_geoip_install_html($button_label) {
		// Don't show the button to users without the proper permissions
		if(!current_user_can('manage_woocommerce')) {
			return '';
		}

		$request = explode('?', $_SERVER['REQUEST_URI']);
		$path = array_shift($request);
		$args = array_merge($_GET, array(
			Definitions::ARG_INSTALL_GEOIP_DB => 1,
		));

		$link_url = $path . '?' . http_build_query($args);
		$plugin_action = '<a href="' . $link_url . '" ' .
										 'class="button">';
		// It's assumed that the label was already passed through the translation functions
		$plugin_action .= $button_label;
		$plugin_action .= '</a>';
		return $plugin_action;
	}


	/**
	 * Returns the full path and file name of the GeoIP database.
	 *
	 * @return string
	 */
	public static function geoip_db_file() {
		$upload_dir = wp_upload_dir();
		$file_path = $upload_dir['basedir'] . '/' . self::$geoip_db_file;
		return apply_filters('wc_aelia_afc_geodb_location', $file_path);
	}

	/**
	 * Installs geoip database, if it doesn't exist yet.
	 *
	 * @since 1.6.0.150724
	 */
	public static function install_database(string $license_key) {
		// Allow 3rd parties to alter the result of the "GeoIP database exist" check
		// @since 2.0.8.190822
		// @link https://aelia.freshdesk.com/a/tickets/23407
		if(!apply_filters('wc_aelia_geoip_database_exists', file_exists(self::geoip_db_file()), self::geoip_db_file())) {
			// Download and install the latest GeoIP database
			$result = self::update_database($license_key);

			if(is_wp_error($result)) {
				Messages::admin_message(wp_kses_post(implode(' ', [
					__('Could not download and install the GeoIP database.', WC_AeliaFoundationClasses::$text_domain),
					$result->get_error_message(),
				])),
				array(
					'level' => E_USER_ERROR,
					'code' => Definitions::ERR_COULD_NOT_UPDATE_GEOIP_DATABASE,
				));
			}
		}
		return true;
	}

	/**
	 * Updates geoip database. Adapted from https://wordpress.org/plugins/geoip-detect/.
	 *
	 * @since 1.6.0.150724
	 * @link https://wordpress.org/plugins/geoip-detect/
	 */
	public static function update_database(string $license_key) {
		$result = true;
		$afc = WC_AeliaFoundationClasses::instance();

		// Set time limit to 5 minutes, if possible. Downloading the database can
		// take some time
		@set_time_limit(5 * 60);

		require_once(ABSPATH . 'wp-admin/includes/file.php');

		// Build the URL to download the GeoLite database from MaxMind
		// @since 2.6.0.241007
		$download_uri = add_query_arg(
			array(
				'edition_id' => static::DATABASE,
				'license_key' => urlencode(wc_clean($license_key)),
				'suffix' => 'tar.gz',
			),
			'https://download.maxmind.com/app/geoip_download'
		);

		// Needed for the download_url call right below.
		require_once ABSPATH . 'wp-admin/includes/file.php';

		$tmp_archive_path = download_url(esc_url_raw($download_uri));
		if(is_wp_error($tmp_archive_path)) {
			$afc->get_logger()->error(__('Unable to download GeoIP Database.', WC_AeliaFoundationClasses::$text_domain), array(
				'GeoIP Database URL' => $download_uri,
				'Error Message' => $tmp_archive_path->get_error_message(),
			));

			$error_data = $tmp_archive_path->get_error_data();

			// Inform the administrator if the database could not be downloaded
			// @since 2.6.0.241007
			if(isset($error_data['code'])) {
				switch($error_data['code']) {
					// An error 401 indicates a missing authorisation
					case 401:
						$result = new WP_Error('wc_aelia_geolocation_database_license_key', wp_kses_post(implode(' ', [
							__('The MaxMind license key is invalid.', WC_AeliaFoundationClasses::$text_domain),
							sprintf(
								__('Please go to <a href="%1$s">WooCommerce > Settings > Integration > MaxMind Geolocation</a> and enter your licence key.', WC_AeliaFoundationClasses::$text_domain),
								admin_url('admin.php?page=wc-settings&tab=integration')
							),
							__('If you have recently created this key, you may need to wait for it to become active.', WC_AeliaFoundationClasses::$text_domain),
						])));
					break;
					default:
						$result = new WP_Error('wc_aelia_geolocation_database_generic_error', wp_kses_post(implode(' ', [
							sprintf(__('Please %s.', WC_AeliaFoundationClasses::$text_domain), self::get_geoip_install_html(__('try to install the database again', ))),
							sprintf(__('If the error persists, please download the the database ' .
												 'manually, from <a href="%1$s">the MaxMind website</a> (you will need to create a free account on their site). Extract file ' .
												 '<strong>%2$s</strong> from the archive and copy it to ' .
												 '<code>%3$s</code>.',
												 WC_AeliaFoundationClasses::$text_domain),
											'https://dev.maxmind.com/geoip/geoip2/geolite2/',
											IP2Location::$geoip_db_file,
											dirname(IP2Location::geoip_db_file())),
							__('Geolocation features will become available automatically, as soon as the GeoIP database is copied in the indicated folder.', WC_AeliaFoundationClasses::$text_domain),
							sprintf(__('For more information about this message, <a href="%s">please refer to our knowledge base</a>.', WC_AeliaFoundationClasses::$text_domain), 'http://bit.ly/AFC_Geolocation'),
						])));
					break;
				}
			}

			return $result;
		}

		// Extract the database from the downloaded archive
		try {
			$file = new \PharData($tmp_archive_path);

			$tmp_database_path = trailingslashit(dirname($tmp_archive_path)) . trailingslashit($file->current()->getFilename()) . self::DATABASE . self::DATABASE_EXTENSION;

			$file->extractTo(
				dirname($tmp_archive_path),
				trailingslashit($file->current()->getFilename()) . self::DATABASE . self::DATABASE_EXTENSION,
				true
			);
		}
		catch(Exception $exception) {
			$afc->get_logger()->error(__('Unable to open downloaded GeoIP database file. The GeoIP database could not be updated.', WC_AeliaFoundationClasses::$text_domain), array(
				'GeoIP Database URL' => $download_uri,
				'Temporary Downloaded File Path' => $tmp_archive_path,
				'Target File' => $geolocation_database_path,
			));
			return new WP_Error('wc_aelia_geolocation_database_archive_error', $exception->getMessage());
		}
		finally {
			// Remove the archive since we only care about a single file in it.
			unlink($tmp_archive_path);
		}

		if(!WP_Filesystem()) {
			$error_msg = __('Failed to initialise WC_Filesystem API while trying to update the MaxMind Geolocation database.', WC_AeliaFoundationClasses::$text_domain);
			$afc->get_logger()->warning($error_msg);
			return new WP_Error('wc_aelia_geolocation_database_archive_extract_error', $error_msg);
		}

		global $wp_filesystem;
		$target_database_path = self::geoip_db_file();

		// Delete previous databases
		if($wp_filesystem->exists($target_database_path)) {
			$wp_filesystem->delete($target_database_path);
		}

		// Move the new database to the appropriate location
		$wp_filesystem->move($tmp_database_path, $target_database_path, true);
		$wp_filesystem->delete(dirname($tmp_database_path));

		return $result;
	}

	/**
	 * Deletes the geoip database.
	 *
	 * @since 2.6.0.241007
	 */
	public static function delete_database() {
		$result = true;
		if(!WP_Filesystem()) {
			$error_msg = __('Failed to initialise WC_Filesystem API while trying to delete the MaxMind Geolocation database.', WC_AeliaFoundationClasses::$text_domain);
			WC_AeliaFoundationClasses::instance()->get_logger()->warning($error_msg);
			return new WP_Error('wc_aelia_geolocation_database_archive_delete_error', $error_msg);
		}

		global $wp_filesystem;
		$target_database_path = self::geoip_db_file();

		// Delete previous databases
		if($wp_filesystem->exists($target_database_path)) {
			$wp_filesystem->delete($target_database_path);
		}

		return $result;
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->logger = WC_AeliaFoundationClasses::instance()->get_logger();
	}

	/**
	 * Factory method.
	 *
	 * @return Aelia\WC\IP2Location.
	 */
	public static function factory() {
		return new self();
	}

	/**
	 * Returns the array of errors occurred during geolocation.
	 *
	 * @return array
	 */
	public function get_errors(){
		return implode("\n", $this->errors);
	}

	/**
	 * Returns the instance of the MaxMind GeoIP Database Reader.
	 *
	 * @return GeoIp2\Database\Reader|bool The Reader instance, or false if the
	 * reader could not be instantiated.
	 * @since 1.6.0.150724
	 */
	protected function get_db_reader() {
		if(empty($this->_db_reader)) {
			try {
				$geoip_db_file = self::geoip_db_file();
				if(file_exists($geoip_db_file)) {
					$this->_db_reader = new Reader(self::geoip_db_file());
				}
				else {
					$this->logger->warning(sprintf('GeoIP database does not exist: "%s".', $geoip_db_file));

					$this->_db_reader = false;
				}
			}
			catch(Exception $e) {
				$this->logger->warning(sprintf('Could not instantiate GeoIP Database Reader. Error: "%s".', $e->getMessage()));
				$this->_db_reader = false;
			}
		}
		return $this->_db_reader;
	}

	protected function valid_ip_address($ip_address) {
		// IP address must be either an IPv4 or an IPv6
		if((filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) &&
			 (filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false)) {
			$this->errors[] = sprintf(__('Method IP2Location::get_country_code() expects a valid IPv4 or IPv6 ' .
																	 'address (it will not work with host names). "%s" was passed, which is ' .
																	 'not a valid address.',
																	 $this->text_domain),
																$ip_address);
			return false;
		}
		return true;
	}

	/**
	 * Returns the 2-digit Country Code matching a given IP address.
	 *
	 * @param string ip_address The IP address for which to retrieve the Country Code.
	 * @return string|bool A Country Code on success, or False on failure.
	 */
	public function get_country_code($ip_address){
		// Log the attempt to resolve the IP address to a country code
		// @since 2.0.3.190129
		$this->logger->debug(__('Attempting to detect country from IP address.', WC_AeliaFoundationClasses::$text_domain), array(
			'IP Address' => $ip_address,
		));

		// Allow 3rd parties to set the country code, if they wish
		$country_code = apply_filters('wc_aelia_ip2location_before_get_country_code', '', $ip_address);

		// Get the country code from CloudFlare, if it was passed
		if(empty($country_code) && !empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
			$country_code =  strtoupper($_SERVER['HTTP_CF_IPCOUNTRY']);
		}

		// If the country code is still empty at this stage, perform the detection
		if(empty($country_code)) {
			$city = $this->get_city($ip_address);
			if(!$city) {
				$country_code = false;
			}
			else {
				$country_code = $city->country->isoCode;
			}
		}

		// Log the detected country code
		// @since 2.0.3.190129
		$this->logger->debug(__('Country detected.', WC_AeliaFoundationClasses::$text_domain), array(
			'IP Address' => $ip_address,
			'Country Code' => $country_code,
		));

		return apply_filters('wc_aelia_ip2location_country_code', $country_code, $ip_address);
	}

	/**
	 * Returns a City object with the details of the city associated to an IP
	 * address.
	 *
	 * @param string ip_address The IP address to locate.
	 * @return Geoip2\City|false A City object, or false if the location could not
	 * be determined.
	 * @since 1.6.0.150724
	 */
	public function get_city($ip_address) {
		// If a cached detection result exists, use it directly. Cached results have
		// already been processed by the filters, there's no need to trigger those
		// filters again
		if(!empty($this->detected_cities[$ip_address])) {
			return $this->detected_cities[$ip_address];
		}

		$city = false;
		if($this->valid_ip_address($ip_address)) {
			try {
				// Create the Reader object, which should be reused across lookups.
				$reader = $this->get_db_reader();
				if($reader === false) {
					$this->logger->warning(__('Could not instantiate GeoIP DB Reader. Geolocation aborted.', WC_AeliaFoundationClasses::$text_domain));
					return false;
				}
				$city = $reader->city($ip_address);
			}
			catch(\Exception $e) {
				$this->errors[] = sprintf(__('Error(s) occurred while retrieving Geolocation information ' .
																		 'for IP Address "%s". Error: %s.',
																		 $this->text_domain),
																	$ip_address,
																	$e->getMessage());
				$city = false;
			}
		}
		// Cache the detected city, so that we won't have to look up for it again
		$this->detected_cities[$ip_address] = apply_filters('wc_aelia_ip2location_city', $city, $ip_address);
		return $this->detected_cities[$ip_address];
	}

	/**
	 * Returns the State related to an IP address.
	 *
	 * @param string ip_address The IP address to locate.
	 * @return string|false The ISO code of the state, or false if it could not be
	 * determined.
	 * @since 1.6.0.150724
	 */
	public function get_state($ip_address) {
		// Log the attempt to resolve the IP address to a state code
		// @since 2.0.3.190129
		$this->logger->debug(__('Attempting to detect state from IP address.', WC_AeliaFoundationClasses::$text_domain), array(
			'IP Address' => $ip_address,
		));

		// Allow 3rd parties to set the state/county code, if they wish
		$state_code = apply_filters('wc_aelia_ip2location_before_get_state_code', '', $ip_address);

		if(empty($state_code)) {
			$city = $this->get_city($ip_address);
			if(!$city) {
				$state_code = false;
			}
			else {
				$state_code = $city->mostSpecificSubdivision->isoCode;
			}
		}

		// Log the detected state code
		// @since 2.0.3.190129
		$this->logger->debug(__('State detected.', WC_AeliaFoundationClasses::$text_domain), array(
			'IP Address' => $ip_address,
			'State Code' => $state_code,
		));

		return apply_filters('wc_aelia_ip2location_state', $state_code, $ip_address, $city);
	}

	/**
	 * Alias for IP2Location::get_state().
	 *
	 * @param string ip_address The IP address to locate.
	 * @return string|false The ISO code of the state, or false if it could not be
	 * determined.
	 * @see IP2Location::get_state().
	 * @since 1.6.0.150724
	 */
	public function get_county($ip_address) {
		return $this->get_state($ip_address);
	}

	/**
	 * Returns the visitor's IP address, handling the case in which a standard
	 * reverse proxy is used.
	 *
	 * @return string
	 */
	public function get_visitor_ip_address() {
		// Log the attempt to resolve the IP address
		// @since 2.0.3.190129
		$this->logger->debug(__('Attempting to fetch IP address of visitor.', WC_AeliaFoundationClasses::$text_domain), array(
			'$_SERVER[HTTP_CLIENT_IP]' => isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : '',
			'$_SERVER[HTTP_X_FORWARDED_FOR]' => isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '',
			'$_SERVER[HTTP_X_REAL_IP]' =>isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : '',
			'$_SERVER[REMOTE_ADDR]' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
		));

		// Parse the proxy headers that might contain the real IP address
		// @since
		foreach(array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR') as $header_key) {
			if(!empty($_SERVER[$header_key])) {
				// Store the original value, so that we can pass it to a filter
				$forwarded_for = $_SERVER[$header_key];

				// Reverse proxy headers may contain multiple addresses, separated by a
				// comma. The first valid one is the real client, followed by intermediate
				// proxy servers
				foreach(explode(',', $forwarded_for) as $ip_address) {
					$ip_address = trim($ip_address);
					// Take the first valid IP address and return it
					if(filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
						// Log the detected IP address
						// @since 2.0.3.190129
						$this->logger->debug(__('Found valid IP address.', WC_AeliaFoundationClasses::$text_domain), array(
							'Visitor IP Address' => $ip_address,
						));

						return apply_filters('wc_aelia_visitor_ip', $ip_address, $forwarded_for);
					}
				}
			}
		}

		// As a fallback, return an empty string, to indicate that it was not possible
		// to fetch the IP address
		return '';
	}

	/**
	 * Returns the visitor's country, deriving it from his IP address.
	 *
	 * @return string
	 */
	public function get_visitor_country() {
		return $this->get_country_code($this->get_visitor_ip_address());
	}

	/**
	 * Returns the visitor's State/county, deriving it from his IP address.
	 *
	 * @return string
	 * @since 1.6.1.150728
	 */
	public function get_visitor_state() {
		return $this->get_state($this->get_visitor_ip_address());
	}

	/**
	 * Returns the visitor's city, deriving it from his IP address.
	 *
	 * @return GeoIp2/City A City object, with properties describing country, State,
	 * and so on.
	 * @since 1.6.1.150728
	 * @see GeoIp2/City
	 */
	public function get_visitor_city() {
		return $this->get_city($this->get_visitor_ip_address());
	}
}
