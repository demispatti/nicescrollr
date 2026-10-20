/**
 * The script for the settings menu.
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
    function NsrMenu()
    {
        // Default elements
        this.nsr_menu = Nsr_Menu;
        this.document = $(document);
        this.body = $('body');
        this.html = $('html');
        this.window = $(window);
        this.nice = $('#ascrail2000');
        this.colorpicker = this.initColorPicker(this.colorpicker);
        this.wrap = $('.form-table td');
        // Settings Toggles and their settings sections
        this.basicSettingsToggle = $('h2.settings-toggle.basic');
        this.upperPanel = this.basicSettingsToggle.next('table');
        this.advancedSettingsToggle = $('h2.settings-toggle.advanced');
        this.lowerPanel = this.advancedSettingsToggle.next('table');
        this.backtopSettingsToggle = $('h2.settings-toggle.backtop');
        this.backtopPanel = this.backtopSettingsToggle.next('table');
        this.resetSettingsToggle = $('h2.settings-toggle.reset');
        this.resetPanel = this.resetSettingsToggle.next('table');
    }

    NsrMenu.prototype = {
        init: function() {
            this.wrapTable();
            this.enableScrollTo();
            if (this.nsr_menu.locale === 'de_DE') {
                this.localizeCheckboxes();
            }
            this.initBasicSettingsPanel();
            this.initExtendedSettingsPanel();
            this.initBacktopSettingsPanel();
            this.initResetSettingsPanel();
            this.addEvents();
        },
        initColorPicker: function(element) {
            element = $('.nsr-color-picker').wpColorPicker();
            return element;
        },
        wrapTable: function() {
            this.wrap.wrapInner('<div class="form-table-td-wrap"></div>');
        },
        // Settings section
        initBasicSettingsPanel: function() {
            this.basicSettingsToggle.addClass('nicescrollr_settings_toggle is-open').attr({
                role: 'button',
                tabindex: '0',
                'aria-controls': 'nsr-basic-panel',
                'aria-expanded': 'true'
            });
            this.upperPanel.addClass('upper-panel').attr({id: 'nsr-basic-panel', 'aria-hidden': 'false'});
            this.upperPanel.css('display', 'inline-block').animate({
                height: '100%'
            }, 320);
        },
        initExtendedSettingsPanel: function() {
            this.advancedSettingsToggle.addClass('nicescrollr_settings_toggle').attr({
                role: 'button',
                tabindex: '0',
                'aria-controls': 'nsr-advanced-panel',
                'aria-expanded': 'false'
            });
            this.lowerPanel.addClass('lower-panel').attr({id: 'nsr-advanced-panel', 'aria-hidden': 'true'});
            this.lowerPanel.css('display', 'none');
        },
        initBacktopSettingsPanel: function() {
            this.backtopSettingsToggle.addClass('nicescrollr_settings_toggle').attr({
                role: 'button',
                tabindex: '0',
                'aria-controls': 'nsr-backtop-panel',
                'aria-expanded': 'false'
            });
            this.backtopPanel.addClass('backtop-panel').attr({id: 'nsr-backtop-panel', 'aria-hidden': 'true'});
            this.backtopPanel.css('display', 'none');
        },
        initResetSettingsPanel: function() {
            this.resetSettingsToggle.addClass('nicescrollr_settings_toggle').attr({
                role: 'button',
                tabindex: '0',
                'aria-controls': 'nsr-reset-panel',
                'aria-expanded': 'false'
            });
            this.resetPanel.addClass('reset-panel').attr({id: 'nsr-reset-panel', 'aria-hidden': 'true'});
            this.resetPanel.css('display', 'none');
        },
        localizeCheckboxes: function() {
            $('<style>.nsr-switch-label:before{content:"' + this.nsr_menu.Off + '";}</style>').appendTo('head');
            $('<style>.nsr-switch-label:after{content:"' + this.nsr_menu.On + '";}</style>').appendTo('head');
        },
        addEvents: function() {
            this.basicSettingsToggle.on('click', {context: this}, this.basicSettingsToggleOnClick);
            this.basicSettingsToggle.on('keydown', {context: this, clickHandler: this.basicSettingsToggleOnClick}, this.settingsToggleOnKeydown);
            this.advancedSettingsToggle.on('click', {context: this}, this.extendedSettingsToggleOnClick);
            this.advancedSettingsToggle.on('keydown', {
                context: this,
                clickHandler: this.extendedSettingsToggleOnClick
            }, this.settingsToggleOnKeydown);
            this.backtopSettingsToggle.on('click', {context: this}, this.backtopSettingsToggleOnClick);
            this.backtopSettingsToggle.on('keydown', {
                context: this,
                clickHandler: this.backtopSettingsToggleOnClick
            }, this.settingsToggleOnKeydown);
            this.resetSettingsToggle.on('click', {context: this}, this.resetSettingsToggleOnClick);
            this.resetSettingsToggle.on('keydown', {context: this, clickHandler: this.resetSettingsToggleOnClick}, this.settingsToggleOnKeydown);
        },
        settingsToggleOnKeydown: function(event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                event.data.clickHandler(event);
            }
        },
        syncPanelState: function(toggle, panel) {
            var isOpen = toggle.hasClass('is-open');
            toggle.attr('aria-expanded', isOpen ? 'true' : 'false');
            panel.attr('aria-hidden', isOpen ? 'false' : 'true');
        },

        basicSettingsToggleOnClick: function(event) {
            var $this = event.data.context;
            $this.basicSettingsToggle.toggleClass('is-open');
            $this.syncPanelState($this.basicSettingsToggle, $this.upperPanel);
            $this.upperPanel.slideToggle(320);
        },
        extendedSettingsToggleOnClick: function(event) {
            var $this = event.data.context;
            $this.advancedSettingsToggle.toggleClass('is-open');
            $this.syncPanelState($this.advancedSettingsToggle, $this.lowerPanel);
            $this.lowerPanel.slideToggle(320);
        },
        backtopSettingsToggleOnClick: function(event) {
            var $this = event.data.context;
            $this.backtopSettingsToggle.toggleClass('is-open');
            $this.syncPanelState($this.backtopSettingsToggle, $this.backtopPanel);
            $this.backtopPanel.slideToggle(320);
        },
        resetSettingsToggleOnClick: function(event) {
            var $this = event.data.context;
            $this.resetSettingsToggle.toggleClass('is-open');
            $this.syncPanelState($this.resetSettingsToggle, $this.resetPanel);
            $this.resetPanel.slideToggle(320);
        },

        enableScrollTo: function() {
            this.errorLink = $('.error a');
            this.errorLink.on('click', {context: this}, this.scrollToOnClick);
        },
        scrollToOnClick: function(event) {
            event.preventDefault();
            var $this = event.data.context;
            var address = $(this).attr('href');
            var target;
            var href = address;
            // If the target is a color picker and thus it is an anchor with an id and not an input element,
            // we change the targeted element to keep the scrollTo-functionality fully functional.
            if (address === '#cursorcolor' || address === '#cursorbordercolor' || address === '#background') {
                var element = $(href);
                target = element.parent().prev();
                target.attr('id', $(this).attr('href'));
                $(this).removeAttr('id');
                $(this).parent().prev().attr('id', address);
            } else {
                target = $(href);
            }

            if ($(this).data('index') < $this.nsr_menu.basic_options_count) {
                if ($this.upperPanel.css('display') === 'none') {
                    $this.upperPanel.css('display', 'inline-block');
                }
            }
            if ($(this).data('index') >= $this.nsr_menu.basic_options_count && $(this).data('index') <= $this.nsr_menu.basic_options_count) {
                if ($this.lowerPanel.css('display') === 'none') {
                    $this.lowerPanel.css('display', 'inline-block');
                }
            } else {
                if ($this.backtopPanel.css('display') === 'none') {
                    $this.backtopPanel.css('display', 'inline-block');
                }
            }

            $('html, body').animate({
                scrollTop: $(href).offset().top - 80
            }, 400);

            target.focus();
            target.addClass('validation-error-focus');

            target.on('blur', function() {
                $(this).removeClass('validation-error-focus');
            });
        }

    };

    $(document).ready(function() {
        var nsrMenu = new NsrMenu();
        nsrMenu.init();
    });

})(jQuery);
