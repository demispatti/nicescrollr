/**
 * The script for the settings menu.
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
jQuery(function ($) {

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
			$(this.body).append("<span class='" + this.bt_class + "'></span>");
			this.nsrBackTop = $('.' + this.bt_class);
		},
		applyBacktopConfiguration: function () {
			this.nsrBackTop.css(this.getBacktopConfiguration());
		},
		getBacktopConfiguration: function () {

			return {
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
			};
		},
		bind: function () {
			this.nsrBackTop.bind('mouseenter', { context: this }, this.backTopOnMouseenter);
			this.nsrBackTop.bind('mouseleave', { context: this }, this.backTopOnMouseleave);
			this.nsrBackTop.bind('click', { context: this }, this.backTopOnClick);
			this.window.bind('scroll', { context: this }, this.backTopOnScroll);
			$(this.window).trigger('scroll');
		},

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

			//var button = $('.Nsr-backtop');
			$this.document.scrollTop() > $this.offset ? $this.nsrBackTop.addClass('nsr-backtop-is-visible') : $this.nsrBackTop.removeClass('nsr-backtop-is-visible');
		}
	};

	$(document).ready(function () {
		if (false !== Nsr_Options.bt_enabled) {
			var nsrBacktop = new NsrBacktop();
			nsrBacktop.init();
		}
	});

});
