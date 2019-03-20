/**
 * The script for the ajax functionality.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/menu/js
 * Author:            Demis Patti <demispatti@gmail.com>
 * Author URI:
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
"use strict";
(function ($, alertify) {

	function NsrAjax () {

		this.nsrAjax = Nsr_Ajax;
		this.resetbutton = $(".nsr-reset-button");
	}

	NsrAjax.prototype = {

		init: function () {

			this.setAlertify();
			this.bind();
		},
		setAlertify: function () {

			alertify.defaults = {
				// dialogs defaults
				autoReset: true,
				basic: false,
				closable: true,
				closableByDimmer: true,
				frameless: false,
				maintainFocus: true, // <== global default not per instance, applies to all dialogs
				maximizable: true,
				modal: true,
				movable: true,
				moveBounded: false,
				overflow: true,
				padding: true,
				pinnable: true,
				pinned: true,
				preventBodyShift: false, // <== global default not per instance, applies to all dialogs
				resizable: true,
				startMaximized: false,
				transition: 'zoom',

				// notifier defaults
				notifier: {
					// auto-dismiss wait time (in seconds)
					delay: 8,
					// default position
					position: 'bottom-right'
				},

				// language resources
				glossary: {
					// dialogs default title
					title: 'Nicescrollr',
					// ok button text
					ok: this.nsrAjax.okiDoki,
					// cancel button text
					cancel: this.nsrAjax.noWayJose
				},

				// theme settings
				theme: {
					// class name attached to prompt dialog input textbox.
					input: 'ajs-input',
					// class name attached to ok button
					ok: 'ajs-ok',
					// class name attached to cancel button
					cancel: 'ajs-cancel'
				}
			};
		},
		bind: function () {
			this.resetbutton.bind('click', { context: this }, this.resetButtonOnClick);
		},
		resetButtonOnClick: function (event) {
			event.preventDefault();

			var $this = event.data.context;
			var id = event.target.id;
			var section = id.replace('reset_', '');

			var data = {
				id: id,
				section: section,
				action: 'reset_options',
				nonce: $(this).attr("data-nonce")
			};

			if (section === 'backend') {

				alertify.confirm().set({ 'title': $this.nsrAjax.resetBackendConfirmationHeading });
				alertify.confirm($this.nsrAjax.resetBackendConfirmation, function (e) {
					if (e) {
						$.post($this.nsrAjax.ajax_url, data, function (response) {

							if (response.success === true) {

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
			else if (section === 'frontend') {

				alertify.confirm().set({ title: $this.nsrAjax.resetFrontendConfirmationHeading, focus: 'no ' });
				alertify.confirm($this.nsrAjax.resetFrontendConfirmation, function (e) {
					if (e) {
						$.post($this.nsrAjax.ajax_url, data, function (response) {

							if (response.success === true) {

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

	$(document).ready(function () {

		var Nsr_Ajax = new NsrAjax();
		Nsr_Ajax.init();
	});

})(jQuery, alertify);
