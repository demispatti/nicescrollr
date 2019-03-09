/**
 * The script for the settings menu.
 *
 * @link              https://wordpress.org/plugins/nicescrollr/
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/assets
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
"use strict";
(function ($) {

	var backTopIsMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

	function NsrBacktop () {
		this.nsr_options = Nsr_Options;
		this.offset = 640;
		this.scrollDuration = 700;
		this.easing = 'swing';
		this.document = $(document);
		this.body = $('body');
		this.html = $('html');
		this.window = $(window);
		this.nsrBackTop = null;
		this.bt_class = 'nsr-backtop';
	}

	NsrBacktop.prototype = {
		init: function () {
			this.createBackTop();
			this.applyBacktopConfiguration();
			this.bind();
		},
		createBackTop: function () {
			var size = undefined !== this.nsr_options.bt_size ? this.nsr_options.bt_size : '';

			$(this.body).append("<span class='" + this.bt_class + " " + size + "'></span>");
			this.nsrBackTop = $('.' + this.bt_class);
		},
		applyBacktopConfiguration: function () {

			var config = this.getBacktopConfiguration();
			this.nsrBackTop.css(config);

			// Create a separate stylesheet so we don't mess up existing styles ;-)
			var sheet = document.head.appendChild(document.createElement('style')).sheet;
			// Get the length of the stylesheet so we can apply the rules at continous indices
			var length = sheet.cssRules.length;
			if ('' !== config["color"]) {
				sheet.insertRule('.' + this.bt_class + '::before {color:' + config["color"] + ';}', length ++);
			}
			if ('' !== config["hover-color"]) {
				sheet.insertRule('.' + this.bt_class + ':hover::before{color:' + config["hover-color"] + ';}', length ++);
			}

		},
		getBacktopConfiguration: function () {

			return {
				'color': this.nsr_options.bt_arrow_color,
				'hover-color': this.nsr_options.bt_arrow_hover_color,

				'background-color': this.nsr_options.bt_background_color,
				'border-color': this.nsr_options.bt_border_color,
				'border-width': this.nsr_options.bt_border_width,
				'border-style': this.nsr_options.bt_border_style,

				'border-top-left-radius': this.nsr_options.bt_border_radius_top_left,
				'border-top-right-radius': this.nsr_options.bt_border_radius_top_right,
				'border-bottom-left-radius': this.nsr_options.bt_border_radius_bottom_left,
				'border-bottom-right-radius': this.nsr_options.bt_border_radius_bottom_right
			};
		},
		bind: function () {
			this.nsrBackTop.bind('mouseenter', { context: this }, this.backTopOnMouseenter);
			this.nsrBackTop.bind('mouseleave', { context: this }, this.backTopOnMouseleave);
			this.nsrBackTop.bind('click', { context: this }, this.backTopOnClick);
			this.window.bind('scroll', { context: this }, this.backTopOnScroll);
			$(this.window).trigger('scroll');
		},
		// Events
		backTopOnMouseenter: function (event) {
			var $this = event.data.context;
			$(this).css({
				'background-color': $this.nsr_options.bt_hover_background_color,
				'border-color': $this.nsr_options.bt_hover_border_color,
				'cursor': 'pointer'
			});
		},
		backTopOnMouseleave: function (event) {
			var $this = event.data.context;
			$(this).css({
				'background-color': $this.nsr_options.bt_background_color,
				'border-color': $this.nsr_options.bt_border_color
			});
		},
		backTopOnClick: function (event) {
			var $this = event.data.context;
			$('html, body').animate({
					scrollTop: 0
				}, $this.scrollDuration, $this.easing
			);
		},
		backTopOnScroll: function (event) {
			var $this = event.data.context;
			$this.document.scrollTop() > $this.offset ? $this.nsrBackTop.addClass('nsr-backtop-is-visible') : $this.nsrBackTop.removeClass('nsr-backtop-is-visible');
		}
	};

	$(document).ready(function () {
		if ('1' === Nsr_Options.bt_enabled && false === backTopIsMobile || true === backTopIsMobile && '1' === Nsr_Options.bt_enabled && '1' === Nsr_Options.bt_mobile_enabled) {
			var nsrBacktop = new NsrBacktop();
			nsrBacktop.init();
		}
	});

})(jQuery);
