=== Aelia Foundation Classes for WooCommerce ===
Contributors: daigo75, aelia
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=F8ND89AA8B8QJ
Tags: woocommerce, utility, framework, aelia
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Requires at least: 5.0
Tested up to: 6.5.9

Adds a set of classes that can simplify the development of other plugins for WooCommerce.

== Description ==
The Aelia Foundation Classes add several classes that can simplify the development of plugins for WooCommerce. Some of the available classes are listed below.

**Namespace `Aelia\WC`**

* `IP2Location`. Implements methods to determine visitor's country. Library relies on MaxMind GeoLite2 library.
* `Order`. An extended Order class, which includes methods to retrieve attributes of orders generated in multi-currency setups.
* `Settings`. Allows to manage the settings of a plugin. The class does not rely on WooCommerce Settings API.
* `Settings_Renderer`. Allows to render the settings interface for a plugin. The can automatically render a tabbed interface, using jQuery UI.
* `Logger`. A Logger class.
* `Aelia_Plugin`. A base plugin class, which other plugins can extend. The class implements convenience methods to access plugin settings, WooCommerce settings, common paths and URLs, and automatically load CSS and JavaScript files when needed.
* `Semaphore`. Implements a simple semaphore logic, which can be used to prevent race conditions in operations that cannot run concurrently.

**Global namespace**

* Aelia_WC_RequirementsChecks. Implements the logic to use for requirement checking. When requirements are not met, a message is displayed to the site administrators and the plugin doesn't run. Everything is handled gracefully, and displayed messages are clear also to non-technical users.

This product includes GeoLite2 data created by MaxMind, available from [https://www.maxmind.com](https://www.maxmind.com). By using this software, you agree to use the GeoLite2 exclusively according to the terms set in the GeoLite2 End User License Agreement, which is available here: https://www.maxmind.com/en/geolite2/eula. If you do not wish to accept the terms, you must stop using the software immediately.

= Acknowledgements =
* The Aelia Currency Switcher for WooCommerce includes GeoLite data created by MaxMind, available from https://www.maxmind.com.
* The Aelia Currency Switcher for WooCommerce includes <div>icons made by <a href="https://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>

== Requirements ==
* WordPress 4.0 or newer.
* PHP 7.2 or newer.
* WooCommerce 3.0 or newer.

= Notes =
* This plugin is provided as-is, and it's not covered by our support service. See FAQ for more details.

== Frequently Asked Questions ==

= What plugins used this library? =

Most of our free and premium plugins use this library. We released it to the public as many of our customers and collaborators expressed interest in using it.

= What is the support policy for this plugin? =

As indicated in the Description section, we offer this plugin **free of charge**, but we cannot afford to also provide free, direct support for it as we do for our paid products.
Should you encounter any difficulties with this plugin, and need support, you have several options:

1. **[Contact us](https://aelia.co/contact) to report the issue**, and we will look into it as soon as possible. This option is **free of charge**, and it's offered on a best effort basis. Please note that we won't be able to offer hands-on troubleshooting on issues related to a specific site, such as incompatibilities with a specific environment or 3rd party plugins.
2. **If you need urgent support**, you can avail of our standard paid support. As part of paid support, you will receive direct assistance from our team, who will troubleshoot your site and help you to make it work smoothly. We can also help you with installation, customisation and development of new features.

= I have a question unrelated to support, where can I ask it? =

Should you have any question about this product, please feel free to [contact us](https://aelia.co/contact). We will deal with each enquiry as soon as possible.

== Installation ==

1. Extract the zip file and drop the contents in the ``wp-content/plugins/`` directory of your WordPress installation.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Done. No further configuration is required.

For more information about installation and management of plugins, please refer to [WordPress documentation](https://codex.wordpress.org/Managing_Plugins#Installing_Plugins).

== Changelog ==

= 2.6.0.241007 =
* Tweak - Optimised logic used to update the Geolocation database. The file is now downloaded only when the Aelia geolocation feature is required.
* Declared compatibility with WooCommerce 9.4.

= 2.5.1.240910 =
* Declared compatibility with WooCommerce 9.3.

= 2.5.0.240825 =
* Set minimum PHP version to 7.2.
* Updated Nategood/Httpful dependency.
* Updated Freemius SDK to version 2.7.3.

= 2.4.31.240807 =
* Declared compatibility with WooCommerce 9.2.

= 2.4.30.240709 =
* Declared compatibility with WooCommerce 9.1.

= 2.4.29.240606 =
* Declared compatibility with WooCommerce 9.0.

= 2.4.28.240520 =
* Fix - Added check to identify a Quick Edit operation, ensuring that it's handled as a backend context.

= 2.4.27.240514 =
* Declared compatibility with WooCommerce 8.9.

= 2.4.26.240414 =
* Declared compatibility with WooCommerce 8.8.

= 2.4.26.240317 =
* PHP 8.3 compatibility - Fixed deprecation notice related to a call to the `get_class()` function.

= 2.4.25.240307 =
* Declared compatibility with WooCommerce 8.7.
* Declared compatibility with WordPress 6.5.9.

= 2.4.24.240214 =
* Fix - Fixed PHP 8.2 deprecation notice for property `ExchangeRatesModel::_current_rates`.
* Fix - Updated Nategood/Httpful dependency for PHP 8.2 compatibility.

= 2.4.23.240205 =
* Declared compatibility with WooCommerce 8.6.

= 2.4.22.240109 =
* Declared compatibility with WooCommerce 8.5.

= 2.4.21.231213 =
* Declared compatibility with WooCommerce 8.4.

= 2.4.20.231204 =
* Dependencies - Updated geolocation dependencies (geoip2/geoip2, maxmind-db/reader, maxmind/web-service-common).

= 2.4.19.231122 =
* Tweak - Added "country" icon for "Rest of the World", with ISO2 code "ZZ".

= 2.4.18.231102 =
* Declared compatibility with WooCommerce 8.3.
* Declared compatibility with WordPress 6.4.x.
* Fix - Applied patch proposed by the authors of the Plugin Update Checker library to prevent a conflict with Query Monitor.
* Updated bundled library Yahnis Elsts Plugin Update Checker to version 5.3.

= 2.4.17.231020 =
* Updated embedded Plugin Update Checker library to version 5.2.

= 2.4.16.231003 =
* Declared compatibility with WooCommerce 8.2.

= 2.4.15.230905 =
* Declared compatibility with WooCommerce 8.1.

= 2.4.14.230824 =
* Tweak - Updated definition of interface `IScheduled_Task` to specify the additional methods that implementations must include.

= 2.4.13.230809 =
* Declared compatibility with WooCommerce 8.0.

= 2.4.12.230803 =
* Compatibility - Improved compatibility with PHP 8.2. Removed dynamic properties from main class.
* Declared compatibility with WooCommerce 7.9.

= 2.4.11.230721 =
* Updated bundled Freemius library to version 2.5.10.

= 2.4.10.230701 =
* Updated bundled library Yahnis Elsts Plugin Update Checker to version 5.x.
* Declared compatibility with WooCommerce 7.8.

= 2.4.9.230616 =
* Feature - Added task scheduling features.
* Fix -Fixed error that could occur on the orders list page when a refund total was not a valid number.

= 2.4.8.230524 =
* Updated supported WooCommerce versions.

= 2.4.7.230524 =
* Updated supported WooCommerce versions.

= 2.4.6.230518 =
* HPOS Compatibility - Updated base Aelia_Plugin class. Added logic to detect the new Edit Order page.

= 2.4.5.230512 =
* Fix - Fixed cause of a deprecation notice related to the notification messages handler.

= 2.4.4.230503 =
* Updated supported WooCommerce versions.

= 2.4.3.230406 =
* Updated supported WooCommerce versions.
* Updated supported WordPress versions.

= 2.4.2.230315 =
* Updated supported WooCommerce versions.

= 2.4.1.230214 =
* Updated supported WooCommerce versions.

= 2.4.0.230202 =
* Feature - Added support for High Performance Order Storage feature.
* Feature - Added function `aelia_declare_feature_support()`, to declare support for a specific feature.
* Feature - Added function `aelia_is_hpos_feature_enabled()`, to detect if the High Performance Order Storage feature is enabled.

= 2.3.5.230109 =
* Updated supported WooCommerce versions.

= 2.3.4.221203 =
* Updated supported WooCommerce versions.

= 2.3.3.221110 =
* Updated supported WooCommerce versions.
* Updated supported WordPress versions.

= 2.3.2.221012 =
* Updated supported WooCommerce versions.

= 2.3.1.220830 =
* Tweak - Added namespacing of Monolog dependency, to prevent the error caused by the version of that library bundled with WP Rocket.
* Updated supported WooCommerce versions.

= 2.3.0.220730 =
* Improved compatibility with PHP 8.2. Introduced new logic to track auxiliary object data, without relying on dynamic properties.
* Updated supported WooCommerce versions.

= 2.2.9.220704 =
* Updated supported WooCommerce versions.

= 2.2.8.220607 =
* Updated supported WooCommerce versions.

= 2.2.7.220502 =
* Updated supported WordPress versions.
* Added function `aelia_set_object_read()`.

= 2.2.6.220421 =
* Updated ISO2 currency code for Mauritanian ouguiya.
* Updated supported WooCommerce versions.

= 2.2.5.220330 =
* Updated supported WooCommerce versions.

= 2.2.4.220224 =
* Updated supported WooCommerce versions.

= 2.2.3.220124 =
* Updated supported WooCommerce versions.

= 2.2.2.220104 =
* Updated supported WooCommerce versions.

= 2.2.1.211208 =
* Updated supported WooCommerce versions.
* Updated supported WordPress versions.

= 2.2.0.211117 =
* Feature - Added new class `Aelia\WC\Exceptions\NotInitializedException`.

= 2.1.21.211102 =
* Updated supported WooCommerce versions.

= 2.1.20.211024 =
* Fix - The Freemius support form embedded in the settings pages no longer covers the "Save" buttons at the bottom of the page.

= 2.1.19.211020 =
* Tweak - Refactored class `NotImplementedException` to use a default message and error code.

= 2.1.18.211006 =
* UI - Added note on the legacy Aelia Licenses page, to help customers to migrate their licences to Freemius.

= 2.1.17.211005 =
* Updated supported WooCommerce versions.
* Updated Composer bundle.

= 2.1.16.210906 =
* Updated supported WooCommerce versions.

= 2.1.15.210831 =
* Tweak - Rewritten logic used to initialise the Freemius framework. The previous logic relied on Composer, and it could trigger an errors in some edge case scenarios.
* Updated supported WordPress and WooCommerce versions.

= 2.1.14.210805 =
* Updated embedded library `Yahnis Elsts - Plugin Update Checker`.
* Updated supported WordPress and WooCommerce versions.

= 2.1.13.210706 =
* Fix - Fixed bug in method `Country_Flags::get_country_flags()`.

= 2.1.12.210608 =
* Feature - Added functions to manage country flags.

= 2.1.11.210623 =
* Updated requirements. The AFC now requires at least PHP 7.1.

= 2.1.10.210622 =
* Updated supported WooCommerce versions.
* Updated supported WordPress versions.

= 2.1.9.210525 =
* Feature - Added shortcodes to Freemius integration: `aelia_freemius_account_page`, `aelia_freemius_contact_form`, `aelia_freemius_plugin_pricing`.

= 2.1.8.210518 =
* Tweak - Added `addons` path to the paths handled by the base plugin class.
* Tweak - Added logic to initialise plugin updaters on "init", for compatibility with WP CLI tool.

= 2.1.7.210513 =
* Updated supported WooCommerce versions.

= 2.1.6.210406 =
* Updated supported WooCommerce versions.

= 2.1.5.210311 =
* Updated supported WordPress and WooCommerce versions.

= 2.1.4.210208 =
* Feature - Added filter `wc_aelia_exchange_rates_base_currency`, to allow 3rd parties to alter the base currency used to fetch exchange rates.
* Feature - Added filter `wc_aelia_exchange_rates_currencies`, to allow 3rd parties to alter the list of currencies for which an exchange rate should be fetched.

= 2.1.3.210128 =
* Feature - Added filter `wc_aelia_exchange_rates`, to allow 3rd parties to alter the exchange rates before they are returned.
* Updated supported WooCommerce versions.

= 2.1.2.210108 =
* Tweak - Downgraded 3rd party dependencies, to ensure compatibility with PHP 7.0.
* Updated supported WooCommerce versions.

= 2.1.1.201208 =
* Feature - Extended Settings API. Added support for `settings_key` and `value` field attributes in `Settings_Renderer` base class.

= 2.1.0.201112 =
* Refactoring - Added new methods to `Settings_Renderer` base class, to make the settings API more flexible and easier to extend.
* Added new `Singleton` trait, to simplify the implementation of `Some_Class::instance()` methods.
* Updated supported WooCommerce versions.

= 2.0.24.201103 =
* Corrected version.

= 2.0.23.201029 =
* Fix - Fixed logging for "headers already sent by" cookie warning. The bug prevented the correct tracking of the file that sent the headers prematurely.
* Updated supported WooCommerce versions.

= 2.0.22.201005 =
* Updated supported WooCommerce versions.
* Updated supported WordPress versions.

= 2.0.21.200904 =
* Updated supported WordPress and WooCommerce versions.

= 2.0.22.200820 =
* Feature - Added new filter `wc_aelia_is_frontend`, to allow 3rd parties to determine dynamically if context should be considered "frontend".

= 2.0.21.200813 =
* Updated supported WordPress and WooCommerce versions.

= 2.1.0.200724 =
* Tweak - Improved UI on the premium licence management page.

= 2.0.21.200629 =
* Fix - Fixed signature of method `Aelia\WC\Settings:factory()` to restore backward compatibility.

= 2.0.20.200625 =
* Tweak - Fixed admin UI to show that the log files now contain a timestamp in their name.
* Fix - Fixed bug in the logic used to install the GeoIP database manually.
* Updated supported WooCommerce versions.

= 2.0.20.200605 =
* UI - Added message to invite administrators to reactivate premium licences when a site changes domain or URL.
* Feature - Added new function `aelia_array_insert_after()`.

= 2.0.19.200527 =
* Tweak - Configured default logger to use rotating log files. The new logic will create one file per day, keeping a maximum of 30 files.

= 2.0.18.200512 =
* Tweak - Added logic to skip exchange rates cache when debug mode is enabled.
* Updated supported WooCommerce versions.

= 2.0.17.200504 =
* Tweak - Improved logging of events that prevent cookies from being set.

= 2.0.16.200428 =
* Feature - Added filter `wc_aelia_afc_required_plugin_info` to the logic used for requirement checking. This is to allow 3rd parties to interact with the checks.
* Fix - Fixed issue caused by the absence of the special WooCommerce plugin headers in the result returned by function `get_plugins()`.
* Updated supported WooCommerce versions.

= 2.0.16.200317 =
* Tweak - Improved error handling during automatic updates of premium plugins.
* Tweak - Added check to set secure cookies automatically, when the site is using HTTPS and SSL.

= 2.0.15.200310 =
* Updated supported WooCommerce versions.

= 2.0.14.200214 =
* Tweak - Improved error logging during automatic update of GeoIP database.

= 2.0.13.200131 =
* Tweak - Added possibility to stop setting a cookie in `Aelia_SessionManager` class.
* Tweak - Improved notice message displayed when cookies can't be set due to the headers having already been sent.
* Updated supported WooCommerce versions.

= 2.0.12.200101 =
* Fix - Downgraded MaxMind GeoIP library to align it with the one used by WooCommerce.

= 2.0.11.191231 =
* Fix - Fixed invalid reference to the Definitions class in the Updater library.

= 2.0.10.191231 =
* Tweak - Updated logic to fetch the GeoIP database.
* Updated supported WooCommerce versions.

= 2.0.9.191108 =
* Feature- Added support for custom field types to the internal settings API.
* Fix - Fixed collation used to create table `aelia_dismissed_messages`.
* Tweak - Optimised logic used to perform initialisation and cleanup on activate/deactivate events.
* Updated supported WooCommerce versions.

= 2.0.8.190822 =
* Improvement - Added filter `wc_aelia_geoip_database_exists`, to allow 3rd party to override the result of the check for the existence of the GeoIP database.
* Updated supported WooCommerce versions.

= 2.0.7.190809 =
* Updated Yahnis Elsts Plugin Update Checker library.

= 2.0.8.190715 =
* Tweak - Set default URL of GeoIP database to HTTPS protocol.

= 2.0.7.190613 =
* Tweak - Improved error handling during deactivation of a premium licence.
* Tweak - Added logging of errors occurring during the dismissal of admin messages.

= 2.0.6.190322 =
* Tweak - Added check on frontend script, to handle the case where the `wc_cart_fragments_params` variable has been removed by disabling WooCommerce's cart fragments.

= 2.0.5.190301 =
* Tweak - Improved error handling and logging in `Aelia\WC\Order` class, to handle the condition in which an invalid order ID is passed.

= 2.0.4.190201 =
* Fix - Removed notices caused by IP2Location logging.

= 2.0.3.190129 =
* Tweak - Added logging to simplify debugging of the geolocation feature.

= 2.0.2.181203 =
* Tweak - Added "httponly" option to `Aelia_SessionManager::set_cookie()`.

= 2.0.1.180821 =
* Feature - Added new method `Order::get_total_refunded_in_base_currency`;

= 2.0.0.180808 =
* Feature - Added new auto-update and licence management features for premium plugins.

= 1.9.19.180713 =
* Tweak - Added new function `aelia_date_to_string`.

= 1.9.18.180319 =
* Feature - Premium Licenses. Added possibility to easily replace a license key, without having to deactivate it first.

= 1.9.17.180307 =
* Tweak - Improved validation of remote API responses in Premium Updater.

= 1.9.16.180213 =
* Tweak - Improved handling of remote API errors in Premium Updater.

= 1.9.15.180210 =
* Dev - Added new updater to handle updates for development versions of free plugins.
* Fixed call to `empty()` in Premium Plugin Updater.

= 1.9.14.180126 =
* Fix - Fixed bug that caused the licence data from a plugin to overwrite the data from another plugin during update checks.
* Tweak - Improved IP2Location class to parse additional reverse proxy headers.

= 1.9.13.180123 =
* Improvement - Improved validation of Ajax responses.

= 1.9.12.180104 =
* Added new filters to allow filtering keys and values for cookies and session storage.

= 1.9.11.171214 =
* Updated Yahnis Plugin Updater library to version 4.x.

= 1.9.10.171201 =
* UI - Added message to inform shop administrators about the new licensing system.

= 1.9.9.171120 =
* Improvement - Added handling of exceptions in the Logger, to prevent crashes due to incorrect folder permissions.

= 1.9.8.171002 =
* UI - Consolidated the Admin UI for the support/debug settings and the license management under a single Aelia tab.

= 1.9.7.170912 =
* Fix - Improved logic used to ensure that minicart is updated when the currency changes, to handle the new "hashed" cart fragment IDs.

= 1.9.6.170823 =
* Improved license management interface for Aelia Premium Plugin Updater.

= 1.9.4.170410 =
* Improved logging. Added logic to initialise extra handlers (e.g. ChromePHP) in debug mode.

= 1.8.9.170629 =
* Fix - Logger doesn't track debug messages anymore when debug mode is deactivated.

= 1.8.8.170506 =
* Tweak - Updated logic to detect frontend Ajax calls

= 1.8.7.170426 =
* Lowered permission required to manage plugin's settings to "manage_woocommerce".

= 1.8.6.170408 =
* Improved support for WooCommerce 3.0:
	*Updated Aelia\WC\Order class. Used method `Order::get_id()` instead of direct access to `Order::$id` in WooCommerce 3.0 and later.

= 1.8.6.170405 =
* Improved support for WooCommerce 3.0:
	* Extended `Aelia\WC\Order` class with getter methods, to allow accessing order meta transparently in WooCommerce 3.0 and earlier.

= 1.8.5.170317 =
* Improved Logger:
	* Cleaned up logging logic to avoid the creation of spurious log files.

= 1.8.4.170307 =
* Improved Admin Messages. Added support for custom headers and improved look.
* Added new function `aelia_wp_version_is`. The function allows to check the WordPress version.

= 1.8.3.170202 =
* Improved compatibility with WooCommerce 2.7:
	* Added method `Order::get_currency()`.
* Removed legacy code.

= 1.8.3.170110 =
* Added logic to check for updates for premium plugins.
* Added new class `Aelia\WC\AFC\Messages`, to handle plugin's messages.

= 1.8.2.161216 =
* Improved compatibility with WooCommerce 2.7:
	* Added function `aelia_get_product_id()`.
	* Added checks to prevent product raw meta from being overwritten after a currency conversion.

= 1.8.1.160816 =
* Added new wc_aelia_afc_validate_ajax_nonce filter. The filter allows to bypess the validation of the Ajax nonce for requests that don't need it.

= 1.8.0.160728 =
* Introduced new Logger class, based on Monolog.

= 1.7.5.160722 =
* Added workaround for logging issue introduced by WooCommerce 2.6. WC 2.6 may trigger "loggable" events too early, when the required WordPress functions are not yet loaded. The workaround will prevent the logger from crashing.

= 1.7.4.160705 =
* Added new filter `wc_aelia_afc_exchange_rates_decimals`. The filter allows to alter the amount of decimals used when retrieving the exchange rates for a currency.

= 1.7.3.160531 =
* Added functions to handle Ajax calls.

= 1.7.2.160513 =
* Fixed minor issue in the SQL for the creation of `aelia_dismissed_messages` table. The CREATE TABLE statement contained a field set as NULLable as part of the primary key, and this could cause an error in some MySQL configurations.

= 1.7.1.160403 =
* Fixed bug caused by CloudFlare geolocation passing lowercase country codes.

= 1.7.0.160329 =
* Added new `Aelia_Integration` class.
* Updated external libraries.

= 1.6.16.160317 =
* Fixed bug in installer class. The bug caused the error message "Updates halted" to be displayed when the `aelia_dismissed_messages` was not created because it already existed. Such condition does not represent an error, and should not interrupt the updates.

= 1.6.15.160304 =
* Added new `Settings::factory()` method.
* Added new CSS classes to settings pages. The classes will help identify the elements on settings pages of Aelia plugins.
* Added logic to return the HTML of settings elements, instead of rendering them.

= 1.6.14.160209 =
* Updated 3rd party libraries.

= 1.6.13.160128 =
* Fixed bug in `Order::get_meta()` method. The method did not return the loaded value, thus always returning "null".

= 1.6.12.160120 =
* Improved flexibility of permission handling. Now it's easier to change the capability required to manage the settings of a plugin.

= 1.6.11.151217 =
* Improved support for HTML5 input fields. The SettingsRenderer class can now render input fields of different types, such as number, email, and so on.
* Fixed missing property `ExchangeRatesModel::$_base_currency`.

= 1.6.10.151105 =
* Improved support for admin notices. The logic used to render admin notices has been refactored to be more efficient and easier to maintain.
* Optimised performance. Added a caching mechanism to speed up the retrieval of the settings controller instance.
* Prevented loading of frontend JavaScript in Admin section.
* Refactored method `Order::get_order_currency()` to be compatible with parent method's signature.

= 1.6.9.151103 =
* Added support for dismissable messages.
* Updated language files.

= 1.6.8.151007 =
* Removed unneeded code. The calls to `register_install_hook()` and `register_uninstall_hook()` have been removed from the base plugin class, as they were superfluous (they registered hooks were never invoked).

= 1.6.7.150910 =
* Updated CSS to improve backend UI.
* Fixed bug in the initialisation of AFC Logger instance.

= 1.6.6.150825 =
* Fixed text domain references in `IP2Location` class.

= 1.6.5.150822 =
* Fixed reference to logger class in IP2Location::update_database().

= 1.6.4.150820 =
* Improved performance of requirement checking class.

= 1.6.3.150815 =
* Added new Aelia_Plugin::is_frontend() method. This method will allow plugins to implement their custom logic to determine if they are working on a frontend page.

= 1.6.2.150731 =
* Improved geolocation features:
	* Added message explaining how the new geolocation system works.
	* Fixed bug in the installation of the Geolocation database.
* Improved requirement checking class.
* Added language files.

= 1.6.1.150728 =
* Improved geolocation:
	* Removed geolocation DB from plugin package.
	* Added automatic installation of geolocation database on activation.
	* Improved error checking, to ensure that issues encountered during automatic update of geolocation DB won't cause crashes.
	* Added caching of geolocation results.
	* Improved logging mechanism.
	* Added new method to retrieve visitor's State/county and city.
* Added new "Admin Messages" feature. This feature will allow plugins to display admin messages in a simple and straightforward way.

= 1.6.0.150724 =
* Improved geolocation:
	* Replaced MaxMind database with "City" one.
	* Added automatic update of GeoIP database.
	* Added functions to geolocate the city information.

= 1.5.19.150625 =
* Added new `Aelia_Plugin::editing_order()` method.
* Added new `Aelia_Plugin::doing_reports()` method.
* Improved rendering of setting pages. Now content posted by customer is escaped, to prevent issues due to HTML embedded in it.

= 1.5.18.150604 =
* Added new `aelia_wc_registered_order_types()` function. The function will provide a list of the registered order types even in WC2.1 and earlier.

= 1.5.17.150529 =
* Improved requirement checking. Now the plugin gracefully informs the user if an unsupported PHP version is installed.

= 1.5.16.150519 =
* Updated requirements. The AFC plugin now requires at least WooCommerce 2.1.9, as method `WC_Session_Handler::has_session()`, invoked by the plugin Session Manager, was introduced in that release.

= 1.5.15.150518 =
* Improved rendering of plugin settings page:
	* Added check to prevent raising a warning when no plugin settings are found.
	* Added CSS class to the plugin settings form, to simplify styling.

= 1.5.14.150514 =
* Improved performance. Moved call to updater and installer to the `admin_init` event.

= 1.5.13.150514 =
* Fixed bug in initialisation of WooCommerce session. the bug caused the session to be initialised when not needed.
* Added caching of session status, to improve performance.

= 1.5.12.150512 =
* Optimised Composer autoloader to improve performance.
* Updated GeoIP library and database.
* Refactored `get_value()` function. The function now uses `isset()` to determine the existence of a key. NOTE: this change makes the function behave differently from before. Now it will return the default value also if the key IS set, but it's "null".
* Refactored `Aelia_Plugin::path()` and `Aelia_Plugin::url()` to improve performance.
* Added `get_arr_value()` function.

= 1.5.11.150507 =
* Extended `Aelia_SessionManager class`. Added `set_cookie()` and `get_cookie()` methods.

= 1.5.10.150505 =
* Added `aelia_wc_version_is()` function. The function allows quickly compare the version of WooCommerce against an arbitrary version value.

= 1.5.9.150504 =
* Added support for CloudFlare. The IP2Location class can now use the country detect by CloudFlare and skips its internal detection logic.
* Added new *wc_aelia_ip2location_before_get_country_code* filter. This new filter will allow 3rd parties to set the country code as they wish, skipping the geolocation detection logic.

= 1.5.8.150429 =
* Improved check to prevent initialising a WooCommerce session when one was already started.
* Added new method to check if a WooCommerce session was started.
* Removed legacy code for WooCommerce 1.6.x.

= 1.5.7.150408 =
* Changed scope of `Aelia_Plugin::visitor_is_bot()` to static.
* Updated GeoIP database.

= 1.5.6.150402 =
* Optimised auto-update logic. The logic now keeps track of the last successful step and, in case of error, it resumes the updates starting from it, rather than from the beginning .
* Added logic to prevent automatic initialisation of sessions when bots visit the site.

= 1.5.5.150318 =
* Fixed bug in handling of error messages related to invalid widget classes.

= 1.5.4.150316 =
* Refactored `Aelia_WC_RequirementsChecks` class to fix incompatibility with wpMandrill plugin.
* Fixed bug in plugin activation logic. The bug caused the plugin activation to fail if the plugin requirements were not indicated as an array.

= 1.5.3.150312 =
* `Aelia_WC_RequirementsChecks` class - Fixed notice related to check for plugin url.

= 1.5.2.150309 =
* Fixed loading of text domain in `Aelia\WC\Aelia_Plugin` base class.

= 1.5.1.150305 =
* Improved `Aelia_WC_RequirementsChecks` class:
	* Fixed `Aelia_WC_RequirementsChecks::js_url()` method. The method now returns the full path to the JS Admin files, without requiring further
	* Improved messages displayed to the Admins.
	* Added `Aelia_WC_RequirementsChecks::$js_dir` property, to allow plugins to specify a custom JS directory.
	* Added `Aelia_WC_RequirementsChecks::$required_php_version` property, to allow plugins to specify the version of PHP.
	* Added logic to prevent enqueuing the `admin-install.js` script more than once.

= 1.5.0.150225 =
* Extended `Aelia_WC_RequirementsChecks` class. Added support for automatic installation and activation of required plugins.

= 1.4.11.150220 =
* Improved `Aelia_Install::add_column()` method. The method is now more powerful and flexible.

= 1.4.10.150209 =
* Changed scope of `Aelia\WC\Aelia_Plugin::log()` to `public`.

= 1.4.9.150111 =
* Added `Aelia_Install::column_exists()` method.
* Added `Aelia_Install::add_column()` method.

= 1.4.8.150109 =
* Added `attributes` argument to `Settings_Renderer::render_text_field()`.
* Added `attributes` argument to `Settings_Renderer::render_checkbox_field()`.

= 1.4.7.150107 =
* Improved requirement checker class. Replaced absolute plugin path with `WP_PLUGIN_DIR` constant.

= 1.4.6.150106 =
* Fixed bug in `Settings_Renderer::render_dropdown_field()`. The bug prevented the CSS class specified in field settings from being applied to the rendered element.

= 1.4.5.141230 =
* Added Httpful library.

= 1.4.4.141224 =
* Fixed bug in auto-update mechanism that prevented external plugin from being able to call it.

= 1.4.3.141223 =
* Refactored `Semaphore` class to use MySQL `GET_LOCK()`.
* Moved automatic updates to WordPress `init` event.

= 1.4.2.141222 =
* Updated GeoIP database.

= 1.4.1.141214 =
* Improved display of "missing requirements" messages.

= 1.4.0.141210 =
* Improved performance in Admin sections. Admin pages now run initialisation code only when they are requested.
* Improved rendering of checkboxes. The new logic ensures that a value is always posted for a checkbox, whether it's ticked or unticked.
* Added `Aelia\WC\Order::get_meta()` method.

= 1.3.0.141208 =
* Added base `Aelia\WC\ExchangeRatesModel` class.
* Added `Settings_Renderer::render_dropdown_field()` method.
* Improved semaphore logic used during auto-updates to reduce race conditions.
* Updated GeoIP database.

= 1.2.3.141129 =
* Added `WC\Aelia\Aelia_Plugin::log()` method.
* Added `Aelia\WC\Order::get_customer_vat_number()` method.
* Added possibility to show a description below the fields in plugin's settings page.
* Added `Aelia\WC\Settings_Renderer::render_text_field()` method.
* Added `Aelia\WC\Settings_Renderer::render_checkbox_field()` method.
* Added `AeliaSimpleXMLElement` class.
* Added support for database transactions to `Aelia_Install` class.
* Added `Model` class.
* Fixed bug in `Settings_Renderer::default_settings()` method.

= 1.2.2.141023 =
* Improved checks in `Aelia\WC\Settings::load()`. Method can now detect and ignore corrupt settings.

= 1.2.1.141017 =
* Added new `Aelia\WC\Aelia_Plugin::init_woocommerce_session()` method, which initialises the WooCommerce session.

= 1.2.0.141013 =
* Added `aelia_t()` function. The function integrates with WPML to translate dynamically generated text and plugin settings.

= 1.1.3.140910 =
* Updated reference to `plugin-update-checker` library.

= 1.1.2.140909 =
* Updated `readme.txt`.

= 1.1.1.140909 =
* Cleaned up build file.

= 1.1.0.140909 =
* Added automatic update mechanism.

= 1.0.12.140908 =
* Added `Aelia_SessionManager::session()` method, as a convenience to retrieve WC session

= 1.0.11.140825 =
* Fixed minor bug in `IP2Location` class that generated a notice message.

= 1.0.10.140819 =
* Fixed logic used to check and load plugin dependencies in `Aelia_WC_RequirementsChecks` class.

= 1.0.9.140717 =
* Refactored semaphore class:
	* Optimised logic used for auto-updates to improve performance.
	* Fixed logic to determine the lock ID for the semaphore.

= 1.0.8.140711 =
* Improved semaphore used for auto-updates:
	* Reduced timeout to forcibly release a lock to 180 seconds.
* Modified loading of several classes to work around quirks of Opcode Caching extensions, such as APC and XCache.

= 1.0.7.140626 =
* Added geolocation resolution for IPv6 addresses.
* Updated Geolite database.

= 1.0.6.140619 =
* Modified loading of Aelia_WC_RequirementsChecks class to work around quirks of Opcode Caching extensions, such as APC and XCache.

= 1.0.5.140611 =
* Corrected loading of plugin's text domain.

= 1.0.4.140607 =
* Modified logic used to load main class to allow dependent plugins to load AFC for unit testing.

= 1.0.3.140530 =
* Optimised auto-update logic to reduce the amount of queries.

= 1.0.2.140509 =
* Updated Composer dependencies.
* Removed unneeded code.
