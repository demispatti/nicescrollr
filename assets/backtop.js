/**
 * The script for the settings menu.
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

jQuery(function ($) {
	"use strict";

	function Plugin () {

		this.nsr_options = nsr_options;
		this.offset = 640;
		this.scrollDuration = 700;
		this.easing = 'easeInOutSine';
		this.document = $(document);
		this.body = $('body');
		this.html = $('html');
		this.window = $(window);
		this.backTop = null;
	}

	Plugin.prototype = {

		init: function () {

			this.createBackTop();
			this.applyBacktopConfiguration();
			this.bind();
		},
		createBackTop: function () {

			var bt_class = 'nsr-backtop';

			$(this.body).append("<a class='" + bt_class + "' href='#'></a>");
			this.backTop = $('.' + bt_class);
		},
		applyBacktopConfiguration: function () {

			this.backTop.css({
				'width': this.nsr_options.bt_width,
				'height': this.nsr_options.bt_height,

				'right': this.nsr_options.bt_posx_from_right,
				'bottom': this.nsr_options.bt_posy_from_bottom,

				'background-color': this.nsr_options.bt_background_color,
				'border-color': this.nsr_options.bt_border_color,
				'border-width': this.nsr_options.bt_border_width,
				'border-style': this.nsr_options.bt_border_style,

				'border-top-left-radius': this.nsr_options.bt_border_radius_top_left,
				'border-top-right-radius': this.nsr_options.bt_border_radius_top_right,
				'border-bottom-left-radius': this.nsr_options.bt_border_radius_bottom_left,
				'border-bottom-right-radius': this.nsr_options.bt_border_radius_bottom_right
			});
		},
		bind: function () {

			this.backTop.bind('click', { context: this }, this.backTopOnClick);
			this.window.bind('scroll', { context: this }, this.backTopOnScroll);
		},

		backTopOnScroll: function (event) {
			var $this = event.data.context;

			var button = $('.nsr-backtop');
			$this.document.scrollTop() > $this.offset ? button.addClass('top-is-visible') : button.removeClass('top-is-visible');
		},
		backTopOnClick: function (event) {
			var $this = event.data.context;

			$('html, body').animate({
					scrollTop: 0
				}, $this.scrollDuration, $this.easing
			);
		}

	};

	$(document).ready(function () {

		var plugin = new Plugin();
		plugin.init();
	});

});
