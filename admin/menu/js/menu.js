/**
 * The script for the settings menu.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/menu/js
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
"use strict";
jQuery(function ($) {

	function NsrMenu () {

		this.nsr_menu = Nsr_Menu;
		this.document = $(document);
		this.body = $('body');
		this.html = $('html');
		this.window = $(window);
		this.nice = $('#ascrail2000');
		this.fancyselect = null;
	}

	NsrMenu.prototype = {
		setObjects: function () {
			this.fancyselect = this.initFancySelect(this.fancyselect);
			this.colorpicker = this.initColorPicker(this.colorpicker);

			this.wrap = $('.form-table td');

			this.toggles = $('form#nsr_form h2.nicescrollr_settings_toggle');
			this.tables = $("table.form-table");

			this.basicSettingsToggle = this.toggles.eq(0);
			this.basicSettingsToggle.addClass('nicescrollr_settings_toggle');
			this.upperPanel = this.tables.eq(0);

			this.advancedSettingsToggle = this.toggles.eq(1);
			this.advancedSettingsToggle.addClass('nicescrollr_settings_toggle');
			this.lowerPanel = this.tables.eq(1);

			this.backtopSettingsToggle = this.toggles.eq(2);
			this.backtopSettingsToggle.addClass('nicescrollr_settings_toggle');
			this.backtopPanel = this.tables.eq(2);

			this.resetSettingsToggle = this.toggles.eq(3);
			this.resetSettingsToggle.addClass('nicescrollr_settings_toggle');
			this.resetPanel = this.tables.eq(3);
		},
		initFancySelect: function(element){
			element = $('.nsr-fancy-select').fancySelect();

			return element;
		},
		initColorPicker: function (element) {
			element = $('.nsr-color-picker').wpColorPicker();

			return element;
		},
		wrapTable: function () {
			this.wrap.wrapInner('<div class="form-table-td-wrap"></div>');
		},
		enableScrollTo: function () {

			this.errorLink = $('.error a');

			if (this.errorLink.hasClass('nsr-validation-error')) {
				this.errorLink.bind('click', { context: this }, this.scrollToOnClick);
			}
		},

		initBasicSettingsPanel: function () {
			this.basicSettingsToggle.addClass('nicescrollr_settings_toggle');
			// Adds the class.
			this.upperPanel.addClass('upper-panel');
			// Sets the initial display.
			this.upperPanel.css('display', 'inline-block').animate({
				height: '100%'
			}, 320);
		},
		initExtendedSettingsPanel: function () {
			this.advancedSettingsToggle.addClass('nicescrollr_settings_toggle');
			// Wrap it for styling purposes
			this.lowerPanel.addClass('lower-panel');
			// Set the initial display
			this.lowerPanel.css('display', 'none');
		},
		initBacktopSettingsPanel: function () {
			this.backtopSettingsToggle.addClass('nicescrollr_settings_toggle');
			// Wrap it for styling purposes
			this.backtopPanel.addClass('backtop-panel');
			// Set the initial display
			this.backtopPanel.css('display', 'none');
		},
		initResetSettingsPanel: function () {
			this.resetSettingsToggle.addClass('nicescrollr_settings_toggle');
			// Wrap it for styling purposes
			this.resetPanel.addClass('reset-panel');
			// Set the initial display
			this.resetPanel.css('display', 'none');
		},
		localizeCheckboxes: function () {
			$('<style>.nsr-switch-label:before{content:"' + this.nsr_menu.Off + '";}</style>').appendTo('head');
			$('<style>.nsr-switch-label:after{content:"' + this.nsr_menu.On + '";}</style>').appendTo('head');
		},

		bind: function () {
			this.basicSettingsToggle.bind('click', { context: this }, this.basicSettingsToggleOnClick);
			this.advancedSettingsToggle.bind('click', { context: this }, this.extendedSettingsToggleOnClick);
			this.backtopSettingsToggle.bind('click', { context: this }, this.backtopSettingsToggleOnClick);
			this.resetSettingsToggle.bind('click', { context: this }, this.resetSettingsToggleOnClick);
		},
		init: function () {

			this.setObjects();
			this.wrapTable();
			// Hides the tables initially.
			this.tables.css({ display: 'none' });

			this.enableScrollTo();

			if (this.nsr_menu.locale === 'de_DE') {
				this.localizeCheckboxes();
			}

			this.initBasicSettingsPanel();
			this.initExtendedSettingsPanel();
			this.initBacktopSettingsPanel();
			this.initResetSettingsPanel();
			this.bind();
		},

		basicSettingsToggleOnClick: function (event) {
			var $this = event.data.context;

			$this.upperPanel.slideToggle(320);
		},
		extendedSettingsToggleOnClick: function (event) {
			var $this = event.data.context;

			$this.lowerPanel.slideToggle(320);
		},
		backtopSettingsToggleOnClick: function (event) {
			var $this = event.data.context;

			$this.backtopPanel.slideToggle(320);
		},
		resetSettingsToggleOnClick: function (event) {
			var $this = event.data.context;

			$this.resetPanel.slideToggle(320);
		},

		scrollToOnClick: function (event) {
			var $this = event.data.context;

			event = event || window.event;
			event.preventDefault();

			var address = $(this).attr('href');
			var target = null;

			// If the target is a color picker and thus it is an anchor with an id and not an input element,
			// we change the targeted element to keep the scrollTo-functionality fully functional.
			if (address === '#cursorcolor' || address === '#cursorbordercolor' || address === '#background' || address === '#bt_background_color' || address === '#bt_background_hover_color' || address === '#bt_border_color' || address === '#bt_border_hover_color') {

				var element = $(address);
				target = element.parent().prev();
				target.attr('id', $(this).attr('href'));
				$(this).removeAttr('id');
				$(this).parent().prev().attr('id', address);
			}
			else {

				target = $(address);
			}


			if ($(this).data('index') < $this.nsr_menu.basic_options_count) {

				if ($this.upperPanel.css('display') === 'none') {
					$this.upperPanel.css('display', 'inline-block');
				}
			}
			else if ($(this).data('index') >= $this.nsr_menu.basic_options_count && $(this).data('index') < ($this.nsr_menu.basic_options_count + $this.nsr_menu.extended_options_count)) {

				if ($this.lowerPanel.css('display') === 'none') {
					$this.lowerPanel.css('display', 'inline-block');
				}
			}
			else if ($(this).data('index') >= ($this.nsr_menu.basic_options_count + $this.nsr_menu.extended_options_count)) {

				if ($this.backtopPanel.css('display') === 'none') {
					$this.backtopPanel.css('display', 'inline-block');
				}
			}

			// Scroll
			$(window).scrollTo(target, 400);

			// Add focus
			target.focus();

			/*// Remove focus
			target.on('blur', function () {
				$(this).removeClass('.validation-error-focus');
			});*/
		}
	};

	$(document).ready(function () {
		var nsrMenu = new NsrMenu();
		nsrMenu.init();
	});

});
