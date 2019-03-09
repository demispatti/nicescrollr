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

(function ($) {
    'use strict';

    $(document).ready(function () {

        /*--------------------------------------------------
         * Color Picker
         *------------------------------------------------*/
        $('.cursorcolor, .cursorbordercolor, .background').wpColorPicker();

        /*--------------------------------------------------
         * Form Table Wrap
         *------------------------------------------------*/
        $('.form-table td').wrapInner('<div class="form-table-td-wrap"></div>');

        /*--------------------------------------------------
         * Fancy Select
         *------------------------------------------------*/
        $('.nsr-fancy-select').fancySelect();

        /*--------------------------------------------------
         * Upper Settings Toggle
         *------------------------------------------------*/
        var toggles = $('form#nsr_form h3');
        var tables = $("table.form-table");

        // Hides the tables initially.
        tables.css({display: 'none'});

        // Sets the button.
        var upperToggle = toggles.eq(0);
        upperToggle.addClass('icomoon icomoon-equalizer nicescrollr_settings_toggle');

        // Sets the element to toggle the visibility of the upper settings table.
        var upperPanel = tables.eq(0);

        // Adds the class.
        upperPanel.addClass('upper-panel');

        // Sets the initial display.
        upperPanel.css('display', 'inline-block').animate({
            height: '100%'
        }, 600 );

        // Toggle the extended settings panel
        upperToggle.click(function (event) {
            event = event || window.event;
            event.preventDefault();

            upperPanel.slideToggle(600);
        });

        /*--------------------------------------------------
         * Lower Settings Toggle
         *------------------------------------------------*/
        // Set the button
        var lowerToggle = toggles.eq(1);
        lowerToggle.addClass('icomoon icomoon-equalizer nicescrollr_settings_toggle');

        // Set the element to toggle
        var lowerPanel = tables.eq(1);

        // Wrap it for styling purposes
        lowerPanel.addClass('lower-panel');

        // Set the initial display
        lowerPanel.css('display', 'none');

        // Toggle the extended settings panel
        lowerToggle.click(function (event) {
            event = event || window.event;
            event.preventDefault();

            lowerPanel.slideToggle(600);
            lowerPanel.css('display', 'inline-block');
        });

        /*--------------------------------------------------
         * backTop
         *------------------------------------------------*/
        if (nsrMenu.backtop_enabled) {

            // Adds the element for the button
            $('.settings_page_nicescrollr_settings').after("<a id='backTop' class='dp-backTop'></a>");

            $('#backTop').backTop({'position': 400, 'speed': 500, 'color': 'black'});
        }

        /*--------------------------------------------------
         * scrollTo
         *------------------------------------------------*/
        if (nsrMenu.scrollto_enabled) {

            if ($('.error a').hasClass('nsr-validation-error')) {

                upperPanel.css('display', 'inline-block');
                lowerPanel.css('display', 'none');

                $('.nsr-validation-error').click(function (event) {

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
                    } else {

                        var target = $('input' + address);
                    }

                    if ($(this).data('index') >= nsrMenu.basic_options_count) {

                        if (lowerPanel.css('display', 'none')) {

                            lowerPanel.css('display', 'inline-block');
                        }
                    } else if ($(this).data('index') < nsrMenu.basic_options_count) {

                        if (upperPanel.css('display', 'none')) {

                            upperPanel.css('display', 'inline-block');
                        }
                    }

                    //target.focus();
                    $(window).scrollTo(target, 400, {offset: -120});

                    //target.addClass('validation-error-focus');

                    target.focus();

                    /*target.on('blur', function() {
                        $(this).removeClass('.validation-error-focus');
                    });*/
                });
            }

        } else if ($('.error a').hasClass('nsr-validation-error-no-scrollto')) {

            lowerPanel.css('display', 'inline-block');
        } else {

            lowerPanel.css('display', 'none');
        }

        /*--------------------------------------------------
         * Localisation for the text on the switches (checkboxes). @todo: May find a less ugly solution...
         *------------------------------------------------*/
        if (nsrMenu.locale == 'de_DE') {

            $('<style>.nsr-switch-label:before{content:"' + nsrMenu.Off + '";}</style>').appendTo('head');
            $('<style>.nsr-switch-label:after{content:"' + nsrMenu.On + '";}</style>').appendTo('head');

        }
    });

})(jQuery);
