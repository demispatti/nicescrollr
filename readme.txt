=== Nicescrollr ===
Contributors: flavor
Tags: nicescroll, scrollbar, scroll, back-to-top, scrolling
Requires at least: 5.6
Tested up to: 7.1
Requires PHP: 7.4
Version: 1.1.0
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A wrapper plugin for the Nicescroll library with full customization options for both frontend and backend.

== Description ==

Get Nicescroll and a ScrollTop button! I really like InuYaksa's Nicescroll JS plugin. I'm a fan. And I thought there may be others like me who would enjoy using his famous
library with a simple interface. So I came up with this plugin. It is a wrapper for that popular library, which is developed and maintained by [InuYaksa](https://wordpress.org/support/users/inuyaksa/). You can visit his official Nicescroll homepage [here](https://www.areaaperta.com/nicescroll/). An overview of the default parameters can be found on [Github](https://github.com/inuyaksa/jquery.nicescroll) at the bottom of the page. This plugin enables you to use the Nicescroll scrollbar on both the frontend and the backend. It is fully customizable, you can tweak and tune every single parameter Nicescroll has to offer! You can style it totally different for both views. It's all up to you!

== Features ==

* Nicescroll scrollbar library
* ScrollTop Button
* Fully customizable for Frontend and Backend

== Requirements ==

* PHP 7.4 or higher
* WordPress 5.6 or higher

== Installation ==

1. Upload the `nicescrollr` folder to your `/wp-content/plugins/` directory.
2. Activate the "Nicescrollr" plugin through the "Plugins" menu in WordPress.
3. You will find its settings page listed in the "settings" section.
4. Go to the plugin settings page and fit it to your needs. Have fun!

== Known issues ==

* There may be issues with scrolling if you are trying to scroll with a pen or the middle mouse button.

== Frequently Asked Questions ==

= Why doesn't it work with my theme? =

Most likely, this is because your theme (or another plugin) already has the Nicescroll library on board. If that's the case, I advise you to uninstall this plugin again to prevent compatibility issues. Of course, you can always ask your theme developer to implement a function to disable the built-in solution if you like to use this plugin and its options.

The capability required for being able to customize settings is the following:

* `nicescrollr_edit` - The user can change settings regarding this plugin.

= Can you help me? =

I provide basic support on this plugin's support page. I check that place periodically, so you may have to be patient.

== Screenshots ==

1. Basic settings
2. Extended settings
3. ScrollTop settings

== Upgrade Notice ==

= 1.1.0 =
Security hardening, reliable production asset loading, WordPress 7.1 compatibility, and performance fixes.

= 1.0.0 =
Major update with PHP 8 compatibility, bug fixes, and improved default settings. Requires PHP 7.4+.

== Changelog ==

= 1.1.0 =
* Added authorization checks and strict section validation to settings reset requests
* Fixed production script localization for minified assets
* Fixed activation hooks when the plugin is loaded through a wrapper bootstrap
* Escaped settings values and Back-to-top output to prevent stored markup injection
* Rejects malformed CSS color values and reports validation errors without PHP warnings
* Fixed unsafe and unnecessary option writes during normal requests
* Fixed mobile pages being locked by scrollbar CSS when Nicescroll is not initialized
* Updated WP Color Picker Alpha to 3.0.4
* Removed an unexpected third-party cursor asset request from the bundled Nicescroll library
* Improved Back-to-top accessibility and multisite uninstall cleanup
* Added WordPress 7.1 and PHP 8.5 compatibility
* Improved asset cache busting and footer loading

= 1.0.0 =
* Fixed PHP 8+ compatibility issues with switch statement syntax
* Fixed translation loading timing for WordPress 6.7+
* Fixed rail padding bug in nicescroll.js
* Improved default scroll settings for better user experience
* Added expandable arrows to settings toggles
* Fixed select dropdown CSS scoping
* Updated minimum PHP requirement to 7.4
* Code cleanup and optimization

= 0.9.3 =
* Resolved problem where the page is not scrolling on mobile devices

= 0.9.2 =
* Code cleanup

= 0.9.0 =
* Bug fixes

= 0.8.3 =
* Made code compatible with PHP version 5.6 again

= 0.8.2 =
* Fixed grab cursor bug
* Fixed text selection problem (thanks peopleinside)
* Fixed css errors (thanks Malae)
* Fixed deprecated js symbol (thanks Malae)
* Fixed Chrome issue with passive event listener
* Fixed parse error in class-nsr.php (thanks Malae)
* "Show on mobile" and "show default scrollbar" options disabled until a fix is available

= 0.8.1 =
* Replaced deprecated jQuery .bind() method (Thanks to Malae for pointing this out)

= 0.8 =
* Checked for compatibility with WordPress version 5.6

= 0.7.6.2 =
* Fixed the options issue

= 0.7.6.1 =
* Fixed missing style sheet for backtop

= 0.7.6 =
* Fixed an issue regarding options that may be missing since v0.7.5

= 0.7.5 =
* Re-factored and re-organised the code for the admin part
* Removed redundant code
* Refactored the backtop functionality
* Re-evaluated the default settings for mobile devices

= 0.7.4 =
* Optimized for WordPress version 5.3
* Fixed scrolling issues with older IE versions

= 0.7.3 =
* Fixed scrolling issues on iFrames
* Removed default scrollbar with nicescroll effects

= 0.7.2 =
* Set default value for "grab cursor" to false

= 0.7.1 =
* Added an option to enable / disable the scrollTop button on mobile devices
* Added four predefined sizes for the scrollTop button

= 0.7.0 =
* Introduced namespaces
* Introduced automated testing
* Fixed a variety of bugs including not resetting options, not saving options and translation errors
* Enhanced performance
* Added minified versions of js and css files
* Added Dashicons
* Added custom scroll-to
* Added option to change the arrow color of the backtop arrow
* Removed Font Awesome Fonts
* Removed Fancy Select Library
* Removed Alertify Library
* Removed ScrollTo Library

= 0.6.7 =
* Should have resolved compatibility warning on this plugin's homepage

= 0.6.6 =
* Should have resolved compatibility warning on this plugin's homepage

= 0.6.5 =
* Minor css improvement

= 0.6.4 =
* Fixed scrollbar display issues
* Renamed the main plugin file from nsr.php to nicescrollr.php

= 0.6.3 =
* Minor code clean up
* Added options to set border color and background color of the backtop button for hover state

= 0.6.2 =
* Renamed css class "top-is-visible" to "nsr-backtop-is-visible" for compatibility
* Renamed the plugin's js instances to avoid conflicts
* Changed the element type for the backtop button
* Changed the css for the backtop button to appear more neutral
* Changed some default option values
* The color picker accepts keyboard inputs now
* The color picker offers an alpha channel now

= 0.6.0 =
* Moved the option "Scrollbar Default Look" and renamed it to "Show Default Scrollbar"
* Hooked plugin initialisation to "plugins_loaded"
* Added an option to enable Nicescroll on mobile devices (default: off)

= 0.5.8 =
* Checked compatibility with the latest version of WordPress

= 0.5.7 =
* Changed default z-index value to 99998
* Changed default auto-hide mode for frontend and backend to 'disabled'
* Optimized the listener that watches changes on the document

= 0.5.6 =
* Renamed the class name for the backtop button
* Fixed a bug regarding the scroll-to functionality
* Several code improvements
* Moved the reset buttons to the respective settings tabs
* Added styling options for the backtop buttons
* Added credit to the author of Nicescroll in the description text
* Removed the deprecated plugin settings tab

= 0.5.5 =
* The plugin can handle document changes now (adopts to changes in height)

= 0.5.4 =
* Fixed an error that occurred during plugin activation

= 0.5.3 =
* Updated the Nicescroll library to the latest version (3.7.6)
* Updated jQuery Easing library to the latest version (1.4.1)
* Updated the settings and the default values
* Back-top button is now available for the entire backend
* Added the optional back-top button for the entire frontend

= 0.5.2 =
* Fixed a bug regarding the case of an empty options array
* Minor code optimisations
* Disabled Nicescroll options that are not related to this plugin's functionality

= 0.5.0 =
* Upgraded Nicescroll to version 3.6.8
* Added easing
* Updated the default settings
* Resolved an issue with "scroll top"

= 0.4.0 =
* Minor bug fixes
* Minor code optimisations

= 0.3.0 =
* Updated the user interface
* Refactored the javascripts
* Support for PHP Version 5.3

= 0.2.2 =
* Nicescroll is disabled on mobile devices with screen width below 960px

= 0.2.1.1 =
* Fixed compatibility with "cbParallax"

= 0.2.1 =
* Added an option to replace the nicescroll scrollbar with the browser's default scrollbar

= 0.2.0 =
* Resolved the translation bugs
* Optimized the default settings
* Implemented the nicescroll.plus-library
* Modified the interface

= 0.1.1 =
* Fixed the icons
* Replaced the switch for Safari browser compatibility
* Updated the German translation
* Fixed an issue with a missing url for "scrollTo"
* Made the validation error notices dismissable
* Added the option to assign a background color to the rail

= 0.1.0 =
* First commit.
