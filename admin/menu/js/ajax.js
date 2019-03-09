/**
 * The script for the ajax functionality.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/menu/js
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

jQuery(function ($)
{
	"use strict";

	function Plugin() {}

	Plugin.prototype = {


		constructor: function()
		{

		},


		init: function()
		{
			this.setObjects();
			this.bind();
		},


		setObjects: function()
		{

			/*--------------------------------------------------
			 * Reset Button
			 *------------------------------------------------*/
			this.resetbutton = $(".nsr-reset-button");

			/*--------------------------------------------------
			 * Alertify
			 *------------------------------------------------*/
			alertify.set({
				labels: {
					ok         : nsrAjax.okiDoki,
					cancel     : nsrAjax.noWayJose,
					buttonFocus: nsrAjax.noWayJose
				},
				delay : 3000
			});
		},


		bind: function()
		{
			this.resetbutton.bind('click', {context: this}, this.resetButtonOnClick);
		},


		resetButtonOnClick: function(event)
		{

			event.preventDefault();

			var id = event.target.id;
			var section = id.replace('reset_', '');

			var data = {
				id     : id,
				section: section,
				action : 'reset_options',
				nonce  : $(this).attr("data-nonce")
			};

			if (section == 'all') {

				alertify.confirm(nsrAjax.resetAllConfirmation, function (e) {
					if (e) {
						$.post(ajaxurl, data, function (response) {

							if (response.success == true) {

								alertify.success(response.data.success);
							}
							else {

								alertify.error(response.data.success);
							}
						});
					}
					else {

						return false;
					}
				});

			}
			else if (section == 'plugin') {

				alertify.confirm(nsrAjax.resetPluginConfirmation, function (e) {
					if (e) {
						$.post(ajaxurl, data, function (response) {

							if (response.success == true) {
								alertify.success(response.data.success);
							}
							else {
								alertify.error(response.data.success);
							}
						});
					}
					else {
						return false;
					}
				});

			}
			else if (section == 'backend') {

				alertify.confirm(nsrAjax.resetBackendConfirmation, function (e) {
					if (e) {
						$.post(ajaxurl, data, function (response) {

							if (response.success == true) {

								alertify.success(response.data.success);
							}
							else {

								alertify.error(response.data.success);
							}
						});
					}
					else {

						return false;
					}
				});

			}
			else if (section == 'frontend') {

				alertify.confirm(nsrAjax.resetFrontendConfirmation, function (e) {
					if (e) {
						$.post(ajaxurl, data, function (response) {

							if (response.success == true) {

								alertify.success(response.data.success);
							}
							else {

								alertify.error(response.data.success);
							}
						});
					}
					else {

						return false;
					}
				});

			}
			else {

				return false;
			}
		}

	};

	$(document).ready(function ()
	{
		var instance = new Plugin();

		instance.init();
	});

});
