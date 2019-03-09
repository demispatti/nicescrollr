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

jQuery(function ($)
{
	"use strict";

	function Plugin() {
		this.offset = 980;
		this.scrollDuration = 700;
		this.body = $('body');
		this.window = $(window);
	}

	Plugin.prototype = {

		init: function()
		{
			this.setObjects();

			if (nsrMenu.backtop_enabled) {
				this.createBackTop();
			}

			this.wrapTable();

			// Hides the tables initially.
			this.tables.css({display: 'none'});

			if (nsrMenu.scrollto_enabled) {
				this.enableScrollTo();
			}

			if (nsrMenu.locale == 'de_DE') {
				this.localizeCheckboxes();
			}

			this.initUpperPanel();
			this.initLowerPanel();
			this.bind();
		},

		setObjects: function()
		{
			/*--------------------------------------------------
			 * Color Picker
			 *------------------------------------------------*/
			this.colorpicker = $('.cursorcolor, .cursorbordercolor, .background').wpColorPicker();

			/*--------------------------------------------------
			 * Form Table Wrap
			 *------------------------------------------------*/
			this.wrap = $('.form-table td');

			/*--------------------------------------------------
			 * Fancy Select
			 *------------------------------------------------*/
			this.fancyselect = $('.nsr-fancy-select').fancySelect();

			/*--------------------------------------------------
			 * Upper Settings Panel
			 *------------------------------------------------*/
			this.toggles = $('form#nsr_form h2');
			this.tables = $("table.form-table");

			this.upperToggle = this.toggles.eq(0);
			this.upperToggle.addClass('icomoon icomoon-equalizer nicescrollr_settings_toggle');
			// Sets the element to toggle the visibility of the upper settings table.
			this.upperPanel = this.tables.eq(0);

			/*--------------------------------------------------
			 * Lower Settings Panel
			 *------------------------------------------------*/
			this.lowerToggle = this.toggles.eq(1);
			this.lowerToggle.addClass('icomoon icomoon-equalizer nicescrollr_settings_toggle');
			// Set the element to toggle
			this.lowerPanel = this.tables.eq(1);

			/*--------------------------------------------------
			 * BackTop
			 *------------------------------------------------*/
			this.backTop = $('#backTop');
		},

		wrapTable: function()
		{
			this.wrap.wrapInner('<div class="form-table-td-wrap"></div>');
		},

		createBackTop: function()
		{
			this.body.after("<a id='backTop' class='dp-backTop'></a>");
		},

		enableScrollTo: function()
		{
			//var self = event.data.context;
			this.errorLink = $('.error a');

			if (nsrMenu.scrollto_enabled) {

				if (this.errorLink.hasClass('nsr-validation-error')) {

					this.upperPanel.css('display', 'inline-block');
					this.lowerPanel.css('display', 'none');

					this.errorLink.bind('click', {context: this}, this.scrollToOnClick);
				}

			}
			else if (this.errorLink.hasClass('nsr-validation-error-no-scrollto')) {

				this.lowerPanel.css('display', 'inline-block');
			}
			else {

				this.lowerPanel.css('display', 'none');
			}
		},

		initUpperPanel: function()
		{
			this.upperToggle.addClass('icomoon icomoon-equalizer nicescrollr_settings_toggle');
			// Adds the class.
			this.upperPanel.addClass('upper-panel');
			// Sets the initial display.
			this.upperPanel.css('display', 'inline-block').animate({
				height: '100%'
			}, 320);
		},

		initLowerPanel: function()
		{
			this.lowerToggle.addClass('icomoon icomoon-equalizer nicescrollr_settings_toggle');
			// Wrap it for styling purposes
			this.lowerPanel.addClass('lower-panel');
			// Set the initial display
			this.lowerPanel.css('display', 'none');
		},

		localizeCheckboxes: function()
		{
			$('<style>.nsr-switch-label:before{content:"' + nsrMenu.Off + '";}</style>').appendTo('head');
			$('<style>.nsr-switch-label:after{content:"' + nsrMenu.On + '";}</style>').appendTo('head');
		},

		bind: function()
		{
			this.upperToggle.bind('click', {context: this}, this.upperToggleOnClick);
			this.lowerToggle.bind('click', {context: this}, this.lowerToggleOnClick);

			this.backTop.bind('click', {context: this}, this.onClick);
			this.window.bind('scroll', {context: this}, this.onScroll);
		},

		upperToggleOnClick: function(event)
		{
			var self = event.data.context;

			self.upperPanel.slideToggle(320);
		},

		lowerToggleOnClick: function(event)
		{
			var self = event.data.context;

			self.lowerPanel.slideToggle(320);
			self.lowerPanel.css('display', 'inline-block');
		},

		onClick: function(event)
		{
			var self = event.data.context;

			self.body.animate({
					scrollTop: 0
				}, self.scrollDuration
			);
		},

		onScroll: function(event)
		{
			var self = event.data.context;

			if ($(window).scrollTop() > self.offset) {
				self.backTop.addClass('is-visible');
			}
			else {
				self.backTop.removeClass('is-visible');
			}
		},

		scrollToOnClick: function(event)
		{

			var self = event.data.context;

			event = event || window.event;
			event.preventDefault();

			var address = $(this).attr('href');

			// If the target is a color picker and thus it is an anchor with an id and not an input element,
			// we change the targeted element to keep the scrollTo-functionality fully functional.
			if (address == '#cursorcolor' || address == '#cursorbordercolor' || address == '#background') {

				var element = $('input' + address);
				var target = element.parent().prev();
				target.attr('id', $(this).attr('href'));
				$(this).removeAttr('id');
				$(this).parent().prev().attr('id', address);
			}
			else {

				var target = $('input' + address);
			}

			if ($(this).data('index') >= nsrMenu.basic_options_count) {

				if (self.lowerPanel.css('display') == 'none') {

					self.lowerPanel.css('display', 'inline-block');
				}
			}
			else if ($(this).data('index') < nsrMenu.basic_options_count) {

				if (self.upperPanel.css('display') == 'none') {

					self.upperPanel.css('display', 'inline-block');
				}
			}

			$(window).scrollTo(target, 400, {offset: -120});

			target.focus();

			/*target.on('blur', function() {
			 $(this).removeClass('.validation-error-focus');
			 });*/
		}

	};

	$(document).on( 'ready', function ()
	{
		var instance = new Plugin();

		instance.init();

	});

});
