/**
 * Scripts related to Reports
 */
jQuery(document).ready(function($) {
	var main = this;
	this.$eu_vat_by_country_report_header = $('#eu_vat_by_country_report_header');
	if(this.$eu_vat_by_country_report_header.length <= 0) {
		return;
	}
	this.params = aelia_eu_vat_assistant_admin_params;

	var $tax_type_field = $('<input type="hidden" id="tax_type" name="tax_type">');
	var $exchange_rates_type_field = $('<input type="hidden" id="exchange_rates_type" name="exchange_rates_type">');
	var $refunds_period_field = $('<input type="hidden" id="refunds_period" name="refunds_period">');
	var $refunded_orders_field = $('<input type="hidden" id="include_refunded_orders" name="include_refunded_orders">');

	// Add extra options to the report form
	var $form = $('#poststuff').find('form');
	$form.append($tax_type_field);
	$form.append($exchange_rates_type_field);
	$form.append($refunds_period_field);
	$form.append($refunded_orders_field);

	// Update the form parameters when the options change
	$form.on('submit', function() {
		main.$eu_vat_by_country_report_header.find('.options input:checked').each(function() {
			var $selected_field = $(this);
			var $target_field = $('#' + $selected_field.attr('target_field'));
			if($target_field.length > 0) {
				$target_field.val($selected_field.val());
			}
		})
	});

	$('#poststuff').find('.stats_range ul li > a').on('click', function(e) {
		var new_url_params = {};
		main.$eu_vat_by_country_report_header.find('.options input:checked, .options select').each(function() {
			var $option = $(this);
			new_url_params[$option.attr('name')] = $option.val();
		});

		// Extract current URL arguments
		let url = new URL($(this).attr('href'), location.origin);
		let url_params = new URLSearchParams(url.search);

		// Replace the existing arguments with the new ones selected in the reports
		// interface
		// @since 1.14.8.200918
		for(const [key, value] of Object.entries(new_url_params)) {
			url_params.set(key, value);
		}

		// Reload the reports page with the new arguments
		window.location = window.location.pathname + '?' + url_params.toString();
		return false;
	});
});
