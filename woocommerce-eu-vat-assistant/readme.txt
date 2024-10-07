=== WooCommerce EU VAT Assistant ===
Contributors: daigo75
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LVSZCS2SABN7Y
Requires at least: 5.0
Tested up to: 6.5.9
Tags: woocommerce, vat compliance, tax compliance, digital vat, aelia
License: GPLv3
Stable tag: 2.1.17.241007

Extends the standard WooCommerce sale process and assists in achieving compliance with the new EU VAT regime starting on the 1st of January 2015.

== Description ==

= Important =
**The EU VAT Assistant reached its end of life on the 30th of June 2022** ([see the announcement from January 2022](https://aelia.co/ioss-compliance-aelia-eu-vat-assistant/)). The plugin is still functional and it can be used, but it's no longer maintained or supported. We're keeping it available for a while longer as a courtesy to existing users who have developers who can take care of its maintenance and troubleshooting.

For more information about the plugin retirement and the recomemnded alternative, please refer to the sticky post in the support forum: [EU VAT Assistant – End of life and recommended alternative](https://wordpress.org/support/topic/eu-vat-assistant-end-of-life-and-recommended-alternative/).

= This is a full version of the premium EU VAT Assistant plugin =

We are proud to say that this is [the most powerful **free** EU VAT solution](https://aelia.co/shop/eu-vat-assistant-woocommerce/?src=wp) on the market. **It was designed with you, the merchant, in mind**, and it will make it easier to deal with the new, complex EU VAT regulations. this plugin was developed by [Aelia Team - The WooCommerce internationalisation experts](https://aelia.co).

The WooCommerce EU VAT Assistant is designed to help achieving compliance with the new European VAT regulations, coming into effect on the 1st of January 2015. Starting from that date, digital goods sold to consumers in the European Union are liable to EU VAT, no matter where the seller is located. The VAT rate to apply to each sale is the one charged in the country of consumption, i.e. where the customer  resides. These new rules apply to worldwide sellers, whether resident in the European Union or not, who sell their products to EU customers. For more information: [EU: 2015 Place of Supply Changes - Mini One-Stop-Shop](https://www2.deloitte.com/global/en/pages/tax/articles/eu-2015-place-of-supply-changes-mini-one-stop-shop.html).

= How this plugin will help you =

The EU VAT Assistant plugin extends the standard WooCommerce sale process and calculates the VAT due under the new regime. The information gathered by the plugin can then be used to prepare VAT reports, which will help filing the necessary VAT/MOSS returns.

* **Tracks and records customers' location**. The EU VAT Assistant plugin also records details about each sale, to prove that the correct VAT rate was applied. This is done to comply with the new rules, which require that at least two pieces of non contradictory evidence must be gathered, for each sale, as a proof of customer's location. The evidence is saved automatically against each new order, from the moment the EU VAT compliance plugin is activated.
* **Collects evidence required by the new regulations**. All the data used to determine the VAT regime to apply is recorded in real-time, stored with the order and made available as needed.
* **Accepts and validates EU VAT numbers, adjusting VAT accordingly**. Validation of European VAT numbers is performed via the official VIES service, provided by the European Commission. This feature is equivalent to the one provided by the EU VAT Number plugin.
* **Supports a dedicated VAT currency**, which is used to generate the reports. You can sell in any currency you like, the EU VAT Assistant plugin will take care of converting the VAT amounts to the currency you will use to file your returns.
* **Can automatically populates the VAT rates for all EU countries**. With a single click, you enter the VAT rates for all 28 EU countries. No more tedious manual typing!
* **Includes advanced Reports**
	* *EU VAT report by Country*. This report will show you all the VAT collected under the VAT MOSS regime, as well as the VAT collected for your domestic VAT return.
	* *VIES report*. This report shows all the supplies provided to B2B customers.
	* *INTRASTAT report*. This report shows all the sales made to the EU.
	* Sales by Country (**in development**).
* **Supports ECB exchange rates in VAT MOSS reports**. VAT MOSS Reports can use either the exchange rate saved with each order, or the European Central Bank rate required to produce the official VAT MOSS returns (ref. [official documentation](https://www.revenue.ie/en/tax/vat/leaflets/mini-one-stop-shop.html)). This feature will allow you to use the most appropriate rate when producing your domestic VAT return and the VAT MOSS return.
* **Supports mixed products/services scenarios**. The new EU VAT MOSS regime applies to the sale of digital products and services that do not require significal manual intervention. Sale of services that are provided with human intervention, such as support, consultancy, design, are still subject to VAT at source. In this case, VAT has to be paid to the revenue in merchant's country. WooCommerce allows to specify to which country a tax applies, but not to which country it should be paid once collected. The EU VAT Assistant can help, by allowing merchants to specify the "payable to" country for each VAT. Such information is then displayed in the VAT reports.
* **Allows to force B2B or B2C sales**. You can decide if you wish to force customers to a valid EU VAT number at checkout, thus accepting only B2B transactions, or prevent them from doing it, thus accepting only B2C transactions.
* **Can prevent sales to specific countries**. You can exclude some countries from the list of allowed ones, thus preventing customers from those countries from placing an order.
* **It's fully compatible with our internationalisation solutions**, such the [WooCommerce Currency Switcher, for multi-currency support](https://aelia.co/shop/currency-switcher-woocommerce/), [Prices by Country](https://aelia.co/shop/prices-by-country-woocommerce/), [Tax Display by Country](https://aelia.co/shop/tax-display-by-country-for-woocommerce/) and Prices by Role (coming soon).
* **Automatically updates the exchange rates that are be used to produce the VAT reports in the selected VAT currency**. The plugin can fetch exchange rates from the following providers:
  * European Central Bank
	* HM Revenue and Customs service
	* Bitpay
	* Irish Revenue (experimental)
	* Danish National Bank (sponsored by [Asbjoern Andersen](https://www.asoundeffect.com/)).
* **Fully supports refunds**. Refunds were introduced in WooCommerce 2.2, and support for it was added to our plugin right from the start.
* **Integrates with [PDF Invoices and Packing Slips plugin](https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/)**, to automatically generate EU VAT-compliant invoices.

== Requirements ==

* WordPress 4.0 or newer.
* PHP 7.1 or newer.
* WooCommerce 3.5 or newer.
* [Aelia Foundation Classes](https://aelia.co/downloads/wc-aelia-foundation-classes.zip) framework 2.1.0.201112 or newer.

== Disclaimer ==

This product has been designed to help you fulfil the requirements of the following new EU VAT regulations:

* Identify customers' location.
* Collect at least two non-contradictory pieces of evidence about the determined location.
* Apply the correct VAT rate.
* Ensure that VAT numbers used for B2B transactions are valid before applying VAT exemption.
* Collect all the data required to prepare VAT returns.

We cannot, however, give any legal guarantee that the features provided by this product will be sufficient for you to be fully compliant. By using this product, you declare that you understand and agree that we cannot take any responsibility for errors, omissions or any non-compliance arising from the use of this plugin, alone or together with other products, plugins, themes, extensions or services. It will be your responsibility to check the data produced by this product and file accurate VAT returns on time with your Revenue authority. For more information, please refer to our [terms and conditions of sale and support](https://aelia.co/terms-and-conditions-of-sale/#FreeSupportCovers).

== Frequently Asked Questions ==

= Does the EU VAT Assistant include features to comply with the EU VAT OSS regulations that apply to physical products? =

In short, no. We originally developed the EU VAT Assistant to help with the VAT MOSS regulations that apply to **digital** products from the 1st of January 2015 (VAT MOSS regulations). The EU VAT Assistant can be used, to a certain extent, even after the 1st of July. It's possible, with some custom filters, to cover some the rules applicable to the shipping of goods, such as the VAT exemption over 150 EUR (or 135 GBP, for the UK). However, please keep in mind that it's designed primarily for digital products, and doesn't implement features specific to physical goods, such as handling the aspects of shipping.

= What is Aelia's recommended solution for compliance with rules introduced by the VAT OSS regime, Brexit and Norway's VOEC? =

Over the course of 2021 we have been working on a new plugin to handle the new VAT regulations, to replace the EU VAT Assistant. After carefuly consideration, we came to the conclusion that the product we have been developing could not be competitive, in a market where several of these solutions already exist. Due to that, we opted not to release our own premium VAT compliance product, and collaborate with the authors of existing plugins instead.

Towards the end of 2021, Aelia established a collaboration with David Anderson, founder of Simba Hosting and author of the popular Updraft Plus backup plugin, and of [the WooCommerce EU/UK VAT / IVA Compliance plugin](https://wordpress.org/plugins/woocommerce-eu-vat-compliance/). If you're looking for a comprehensive plugin to help you complying with the new regulations, we recommend to try that plugin.

We chose to endorse the David's solution, instead of releasing our own, because we have been working together and exchanging information for years and we know that it's a reliable product.

We are also organising to offer an optional migration service, to convert the data stored by the Aelia EU VAT Assistant into the formate used by the WooCommerce EU/UK VAT / IVA Compliance plugin. This will be an optional, paid service, which we will provide on request.

You can read more about the collaboration between Aelia and Simba Hosting in the following article: [VAT OSS Compliance – From Aelia EU VAT Assistant to Simba Hosting's WooCommerce EU/UK VAT/IVA Compliance](https://aelia.co/ioss-compliance-aelia-eu-vat-assistant/).

= What features are included in the EU VAT Assistant? =

The EU VAT Assistant includes all the features to handle the VAT MOSS regulations for EU countries (see notes about UK Brexit, applicable from the 1st of January 2021). It's based on the same framework we use for our other premium products, such as the [Aelia Currency Switcher for Woocommerce](https://aelia.co/shop/currency-switcher-woocommerce/), [Prices by Country for WooCommerce](https://aelia.co/shop/prices-by-country-woocommerce/), [Aelia Tax Display by Country for WooCommerce](https://aelia.co/shop/tax-display-by-country-for-woocommerce/), and it follows the same quality standards. All the included features are fully functional, without restrictions or time limitations.

= Can the EU VAT Assistant validate EU VAT numbers? =

Yes. The EU VAT Assistant automatically validates the EU VAT number entered by the customer on the checkout page. When a valid VAT number is entered, the plugin informs WooCommerce that a VAT exemption should be applied.

= How does the EU VAT Assistant validate EU VAT numbers? =

Our solution relies on the official VIES service to validate EU VAT numbers. The EU VAT Assistant also includes several options to accept VAT numbers when the remote VIES service is unavailable or overloaded. Such options are disabled by default, and can be enabled in the plugin settings.

= Does the EU VAT Assistant validate UK VAT numbers? =

The EU VAT Assistant could validate UK VAT numbers up until the 31st of December 2020. From the 1st of January 2021, the VIES service no handles UK VAT numbers, which must be validated through a different service, provided by the UK Revenue Office (HMRC). [We wrote an addon for the EU VAT Assistant to cover that aspect](https://aelia.co/ioss-compliance-aelia-eu-vat-assistant/#brexit), but we have since shifted our focus on other endeavours (see FAQs above).

= Will the EU VAT Assistant include features dedicated to UK merchants? =

The EU VAT Assistant features aim to simplify compliance with the VAT MOSS regulations that apply to EU merchants. Out of the box, the plugin which should cover most of the needs of UK merchants as well (with the exclusion of the validation of UK VAT number). If you're looking for a comprehensive solution that covers that aspect, we recommend [the WooCommerce EU/UK VAT / IVA Compliance plugin](https://wordpress.org/plugins/woocommerce-eu-vat-compliance/), developed by David Anderson.

= Can the EU VAT Assistant show the correct VAT rate as soon as a visitor lands on the site? =

Such feature is provided by our [Tax Display by Country plugin](https://aelia.co/shop/tax-display-by-country-for-woocommerce/). If you like the EU VAT Assistant, we invite you to purchase the Tax Display by Country as well, and enjoy the powerful features of a comprehensive tax compliance solution, at a small price.

= I would like to show the same prices to all customers, regardless of the applicable VAT =
Our [Tax Display by Country plugin](https://aelia.co/shop/tax-display-by-country-for-woocommerce/) includes such feature as well, using it is as simple as ticking a box.

= Does the EU VAT Assistant guarantee compliance with regulations? =

We developed the EU VAT Assistant to be as accurate as possible, in order to help fulfil the requiremenets of the EU VAT MOSS regulations. [As required by WordPress policies](https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/), we can't guarantee legal compliance.
Similarly, although our solution is flexible enough to cover scenarios that falls outside the EU VAT MOSS regulations (e.g. the sale of physical products), we can't promise that it will cover all of them. That's simply due to the presence of too many variables, which could introduce edge cases that our tests didn't cover.

Of course, if you spot a specific condition in which the EU VAT Assistant doesn't seem to work as expected, please feel free to report any issue you might encounter, either [via our contact form](https://aelia.co/contact) (premium support) or [on the public forum](https://wordpress.org/support/plugin/woocommerce-eu-vat-assistant) (free support). We're always happy to review each special cases, and see if we can support them in our solution.

= What is the support policy for this plugin? =

Should you need assistance with this plugin, you can post your query [in the public Support section](https://wordpress.org/support/plugin/woocommerce-eu-vat-assistant/), above**. We review that section on a regular basis, and we will reply as soon as we can (usually within a couple of days). Posting the request there will also allow other users to see it, and they may be able to assist you. Please note that we can only provide **basic** support on the public forum. We can't offer assistance with customisations, nor implement them, and we won't be able to perform in-depth investigations about issues.

= I have a question unrelated to support, where can I ask it? =

Should you have any question about this product, please use the [contact form on our site](https://aelia.co/contact). We will deal with each enquiry as soon as possible. **Important**: we won't be able to provide advice about taxation, accounting or legal matters of any kind.

== Installation ==

1. Extract the zip file and drop the contents in the ```wp-content/plugins/``` directory of your WordPress installation.
2. Activate the EU VAT Assistant plugin through the **Plugins** menu in WordPress.
3. Go to ```WooCommerce > EU VAT Assistant``` to configure the plugin. **Important**: the EU VAT Assistant is very flexible and includes many options. We recommend reading the descriptions carefully, to ensure that you have a clear understanding of what each setting does. Its features can be summarised as follows:

For more information about installation and management of plugins, please refer to [WordPress documentation](https://codex.wordpress.org/Managing_Plugins#Installing_Plugins).

== Screenshots ==

1. **Settings > Checkout**. In this section you can configure how the EU VAT Assistant will behave on the checkout page.
2. **Settings > Self-certification**. In this section you can configure if the plugin should allow customers to self-certify their location.
3. **Settings > Currency**. In this section you can specify which currency you would like to use for VAT reports. It doesn't have to match the WooCommerce base currency. In the lower section, you can choose which provider you would like to use to retrieve the exchange rates that will be used to calculate the amounts in VAT currency.
4. **Settings > Sales**. This section contains the settings that can be used to control how sales are handled (e.g. by preventing sales to some specific countries).
5. **Settings > Options**. Miscellaneous options.
6. **Settings > Shortcuts**. This section contains a few handy shortcuts to reach the WooCommerce sections related to the EU VAT compliance.
7. **Frontend > Checkout**. This screenshot shows the new elements displayed to the customer at checkout. The **EU VAT Number** field can be used by EU businesses to enter their own VAT number. The number is validated using the VIES service and, when valid, a VAT exemption is applied automatically. The **self-certification** element can be used to allow the customer to self-certify that he is resident in the country he selected. This information can be used as a further piece of evidence to prove that the correct VAT rate was applied.
8. **Admin > WooCommerce > Order edit page**. This page shows how the VAT details are displayed when an order is reviewed in the Admin section. The meta box shows the details of the VAT charged for order items and shipping, as well as the amounts refunded. **Note**: refunds are available in WooCommerce 2.2 and later.
9. **Admin > WooCommerce > Tax Settings**. This screenshots shows the Tax Settings page extended by the EU VAT Assistant. The new user inerface allows to automatically retrieve and update the European VAT rates. It's possible to choose which VAT rates are applied in each page. Another important feature is the possibility to specify to which country a VAT will have to be paid. It will be possible, for example, to apply a *20% UK VAT* for services to a German customer who buys consultancy hours, and still keep track of the fact that such tax will have to be paid to HMRC (i.e. outside of MOSS scheme).
10. **Report > EU VAT by Country**. This report shows the totals of VAT applied and refunded at each rate, for both items and shipping, grouped by country. The Export CSV button allows to export the data to a CSV file, which can be easily imported by accounting software.

== Changelog ==

= 2.1.17.241007 =
* Updated embedded AFC framework.
* Declared compatibility with WooCommerce 9.4.

= 2.1.16.240910 =
* Declared compatibility with WooCommerce 9.3.

= 2.1.15.240807 =
* Declared compatibility with WooCommerce 9.2.

= 2.1.14.240709 =
* Declared compatibility with WooCommerce 9.1.

= 2.1.13.240606 =
* Declared compatibility with WooCommerce 9.0.

= 2.1.12.240514 =
* Declared compatibility with WooCommerce 8.9.

= 2.1.11.240414 =
* Declared compatibility with WooCommerce 8.8.

= 2.1.10.240307 =
* Declared compatibility with WooCommerce 8.7.
* Declared compatibility with WordPress 6.5.9.

= 2.1.9.240205 =
* Declared compatibility with WooCommerce 8.6.

= 2.1.8.240109 =
* Declared compatibility with WooCommerce 8.5.

= 2.1.7.231213 =
* Declared compatibility with WooCommerce 8.4.

= 2.1.6.231102 =
* Declared compatibility with WooCommerce 8.3.
* Declared compatibility with WordPress 6.4.x.

= 2.1.5.231003 =
* Updated supported WooCommerce versions.

= 2.1.4.230905 =
* Updated supported WooCommerce versions.

= 2.1.3.230809 =
* Updated supported WooCommerce versions.
* Updated supported WordPress versions.

= 2.1.2.230718 =
* Updated embedded AFC framework. The new version includes an updated version of the Freemius library (v 2.5.10).

= 2.1.1.230705 =
* Updated supported WooCommerce versions.

= 2.1.0.230703 =
* Tweak - Streamlined installation process, removing unnecessary steps.
* Updated supported WooCommerce versions.

= 2.0.45.230524 =
* Updated supported WooCommerce versions.

= 2.0.44.230524 =
* Updated supported WooCommerce versions.

= 2.0.43.230518 =
* Tweak - Updated embedded framework to fix error thrown by method `FeaturesUtil::declare_compatibility()`.

= 2.0.42.230503 =
* Updated supported WooCommerce versions.

= 2.0.41.230406 =
* Updated supported WooCommerce versions.
* Updated supported WordPress versions.

= 2.0.40.230315 =
* Updated supported WooCommerce versions.

= 2.0.39.230214 =
* Updated supported WooCommerce versions.

= 2.0.38.230109 =
* Updated supported WooCommerce versions.

= 2.0.37.221203 =
* Updated supported WooCommerce versions.

= 2.0.36.221110 =
* Updated supported WooCommerce versions.
* Updated supported WordPress versions.

= 2.0.35.221012 =
* Updated supported WooCommerce versions.

= 2.0.34.220830 =
* Updated supported WooCommerce versions.

= 2.0.33.220804 =
* Updated supported WooCommerce versions.

= 2.0.32.220704 =
* Updated supported WooCommerce versions.

= 2.0.31.220607 =
* Updated supported WooCommerce versions.

= 2.0.30.220502 =
* Updated supported WooCommerce versions.
* Updated supported WordPress versions.

= 2.0.29.220330 =
* Updated supported WooCommerce versions.

= 2.0.28.220224 =
* Updated supported WooCommerce versions.
* Updated embedded framework.

= 2.0.27.220124 =
* Updated supported WooCommerce versions.

= 2.0.26.220104 =
* Updated supported WooCommerce versions.
* Updated supported WordPress versions.

= 2.0.25.211210 =
* Updated supported WooCommerce versions.

= 2.0.24.211102 =
* Updated supported WooCommerce versions.

= 2.0.23.211019 =
* Tweak - Modified filter `wc_aelia_eu_vat_assistant_customer_vat_exemption`, so that it always receives customer's country and VAT number, even if one of the two values is empty.

= 2.0.22.211007 =
* Updated supported WooCommerce versions.

= 2.0.21.210910 =
* Fix - Fixed check against the validation response returned by the VIES service.

= 2.0.20.210817 =
* Updated supported WooCommerce versions.
* Updated supported WordPress versions.

= 2.0.19.210629 =
* Updated requirements. The plugin now requires at least PHP 7.1.

= 2.0.18.210622 =
* Tweak - Added logic to disable the EU VAT Assistant when the premium Aelia VAT Assistant plugin is installed.
* Updated supported WooCommerce versions.

= 2.0.17.210504 =
* Tweak - Updated links to the Tax Display by Country plugin.
* Updated supported WooCommerce versions.

= 2.0.16.210406 =
* Tweak - Rewritten logic used to remove the country prefix from VAT numbers. The new logic can now handle prefixes longer than two characters.
* Updated supported WooCommerce versions.

= 2.0.15.210319 =
* Tweak - Refactored class `VAT_Number_Validation_Service`. Added support for a settings section callback, which VAT number validation services can use to display some information about their configuration.

= 2.0.14.210317 =
* UI - Improved validation logic for the VAT number field on the checkout page. The "invalid field" styles are now removed before each validation.
* Fix - Fixed logic used to check if the company name field is filled on the checkout page, before validating the VAT number.

= 2.0.13.210316 =
* Feature - Added new filter `wc_aelia_euva_script_params`, to allow 3rd parties to alter the arguments used to localise the frontend scripts.
* Updated supported WordPress versions.

= 2.0.12.210301 =
* Feature - Added filters `wc_aelia_euva_set_customer_vat_exemption_country` and `wc_aelia_euva_set_customer_vat_exemption_vat_number`, fired during the validation of VAT numbers at checkout.
* Fix - Fixed logic used to hide the self-certification field when sufficient location evidence is available.
* Updated supported WooCommerce versions.

= 2.0.11.210128 =
* Updated supported WooCommerce versions.

= 2.0.10.210123 =
* Feature - Added new filter `wc_aelia_euva_enabled_currencies`.

= 2.0.9.210118 =
* Tweak - Improved handling of VIES response. Added logic to handle the condition in which parts of the trader address are missing.

= 2.0.8.210112 =
* Fix - Fixed handling of the self-certification field when the VAT number field is not displayed on the checkout page.
* Updated supported WooCommerce versions.

= 2.0.7.210109 =
* Set minimum required WooCommerce version to 3.5.

= 2.0.6.210108 =
* Set PHP requirements in the plugin header.

= 2.0.5.210102 =
* Fix - Fixed bug that could trigger an error when a customer didn't enter a VAT number at checkout.
* Tweak - Added Northern Ireland, with ISO-2 code "XI", to the list of countries supported by the VIES VAT number validation service.

= 2.0.4.201231 =
* Fix - Fixed validation logic in dummy validation service class.
* UI - Improved Settings page.
* Updated language files.

= 2.0.3.201229 =
* Tweak - Refactored base class `VAT_Number_Validation_Service` to make it more flexible.
* UI - Improved Settings page.

= 2.0.2.201221 =
* Tweak - Improved VAT validation logic. Added logic to remove country prefixes from VAT numbers before the validation, to ensure a consistent result.
* Feature - Added `wc_aelia_euva_order_vat_number_required` filter. The filter will allow 3rd parties to decide if a valid EU VAT number is required for specific countries.
* Feature - Added `wc_aelia_euva_checkout_vat_number_required_message` filter. The filter will allow 3rd parties to alter the message displayed when the checkout is blocked because a valid VAT number was not entered.
* Deprecation - Deprecated filter `wc_aelia_euva_order_is_eu_vat_number_required`.
* Tweak - Upgraded jQuery UI CSS to version 1.12.1.

= 2.0.1.201215 =
* Bumped major version.
* Tweak - Optimised loading of frontend scripts.
* Feature - Added support for WooCommerce Privacy Policy features.
