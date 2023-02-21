/**
 * The script for the ajax functionality.
 *
 * @link              https://wordpress.org/plugins/nicescrollr/
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/js
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
"use strict";
(function ($) {

	function NsrAjax () {

		// noinspection JSUnresolvedVariable
        this.nsr_ajax = Nsr_Ajax;
		this.resetbutton = $(".nsr-reset-button");
	}

	NsrAjax.prototype = {

		init: function () {
			this.addEvents();
		},
		addEvents: function () {
			this.resetbutton.on('click', { context: this }, this.resetButtonOnClick);
		},
		resetButtonOnClick: function (event) {
			event.preventDefault();

			var $this = event.data.context;
			var id = event.target.id;
			var section = id.replace('reset_', '');

			var data = {
				id: id,
				section: section,
				action: 'nicescrollr_reset_options',
				nonce: $(this).attr("data-nonce")
			};

			if (! confirm($this.nsr_ajax.resetBackendConfirmation)) {
				return false;
			}
			$.post(ajaxurl, data, function (response) {

				if (response.success === true) {
					var message = response.data.message;
					alert(message);
					location.reload();
				}
				else {
					alert('Error! Please reload the page and try again.');
				}
			});
		}
	};

	$(document).ready(function () {


		var nsrAjax = new NsrAjax();
		nsrAjax.init();
	});

})(jQuery);
