=== Nicescrollr ===
Tags: nicescroll, scroll, scrollbar, back to top, scroll to top, frontend, backend
Requires at least: 5.6
Tested up to: 6.5.2
Requires PHP: 5.6+
Version: 0.9.3
Stable tag: 0.9.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Author: Demis Patti
Author URI: https://demispatti.ch
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XLMMS7C62S76Q

== Description ==
Get Nicescroll and a ScrollTop button! I really like InuYaksa's Nicescroll JS plugin. I'm a fan. And I thought there may be others like me who would enjoy using his famous
library with a simple interface. So I came up with this plugin. It is a wrapper for that popular library, which is developed and maintained by <a
href="https://wordpress.org/support/users/inuyaksa/" target="_blank">InuYaksa</a>. You can visit his official Nicescroll homepage <a href="https://www.areaaperta
.com/nicescroll/" target="_blank">here</a>. An overview of the default parameters can be found on <a href="https://github.com/inuyaksa/jquery.nicescroll"
target="_blank">Github</a> at the bottom of the page. This plugin enables you to use the Nicescroll scrollbar on both the frontend and the backend. It is fully customizable, you can tweak and tune every single parameter Nicescroll has to offer! You can style it totally different for both views. It's all up to you!

== Features ==
+ Nicescroll
+ ScrollTop Button
+ Frontend and Backend

== Requirements ==
- PHP 5.6+

== Installation ==
1. Upload the `nicescrollr` folder to your `/wp-content/plugins/` directory.
2. Activate the "Nicescrollr" plugin through the "Plugins" menu in WordPress.
3. You will find its settings page listed in the "settings" section.
3. Go to the plugin settings page and fit it to your needs :-) Have fun!

== Known issues ==
- There may be issues with scrolling, if you are trying to scroll with a pen or the middle mouse button.

== Limitations ==
- Not yet compatible with PHP version 8 and above

== Frequently Asked Questions ==
= Why doesn't it work with my theme? =
Most likely, this is because your theme (or another plugin?) already has the Nicescroll library on board. If that's the case, I advise you to uninstall this plugin again to prevent compatibility issues. Of course, you can always ask your theme developer to implement a function to disable the built-in solution if you like to use this plugin and its Options.

The capability required for being able to customize settings is the following:
* `nicescrollr_edit` - The user can change settings regarding this plugin.

= Can you help me? =
Well, I provide some basic support on this plugin's support page. I check that place once or twice a month, so you may have to be a bit patient.

== Screenshots ==
1. Basic settings
2. Extended settings
3. ScrollTop settings

# Changelog

## Version 0.9.0
- Bug fixes

## Version 0.8.3
- Made code compatible with PHP version 5.6 again

## Version 0.8.2
- Fixed grab cursor bug
- Fixed text selection problem (thanks peopleinside)
- Fixed css errors (thanks Malae)
- Fixed deprecated js symbol (thanks Malae)
- Fixed Chrome issue with passive event listener
- Fixed parse error in class-nsr.php (thanks Malae)
- "Show on mobile" and "show default scrollbar" options disabled until a fix is available

## Version 0.8.1
- Replaced deprecated jQuery .bind() method (Thanks to Malae for pointing this out)

## Version 0.8
- Checked for compatibility with WordPress version 5.6

## Version 0.7.6.2
- Fixed the options issue

## Version 0.7.6.1
- Fixed missing style sheet for backtop

## Version 0.7.6
- Fixed an issue regarding options that may be missing since v0.7.5

## Version 0.7.5
- Re-factored and re-organised the code for the admin part
- Removed redundant code
- Refactored the backtop functionality
- Re-evaluated the default settings for mobile devices

## Version 0.7.4
- Optimized for WordPress version 5.3
- Fixed scrolling issues with older IE versions

## Version 0.7.3
- Fixed scrolling issues on iFrames
- Removed default scrollbar with nicescroll effects

## Version 0.7.2
- Set default value for "grab cursor" to false

## Version 0.7.1
- Added an option to enable / disable the scrollTop button on mobile devices
- Added four predefined sizes for the scrollTop button. NOTE: You will have to visit the plugin settings page and manually save the options for both the frontend and the backend in order to activate the new options

## Version 0.7.0
- Introduced namespaces
- Introduced automated testing
- Fixed a variety of bugs including not resetting options, not saving options and translation errors
- Enhanced performance
- Added minified versions of js and css files
- Added Dashicons
- Added custom scroll-to
- Added option to change the arrow color of the backtop arrow (NOTE: width and height must be 48px for it to be centered,  for now)
- Removed Font Awesome Fonts
- Removed Fancy Select Library
- Removed Alertify Library
- Removed ScrollTo Library

## Version 0.6.7
- Should have resolved that compatibility warning on this plugin's homepage

## Version 0.6.6
- Should have resolved that compatibility warning on this plugin's homepage

## Version 0.6.5
- Minor css improvement

## Version 0.6.4
- Fixed scrollbar display issues
- Renamed the main plugin file from nsr.php to nicescrollr.php for testing reasons, so you may need to activate the plugin again

## Version 0.6.3
- Minor code clean up
- Added Options to set border color and background color of the backtop button for hover state

## Version 0.6.2
- Renamed css class "top-is-visible"  to "nsr-backtop-is-visible" due to compatibility reasons with buttons that are shipped with themes
- Renamed the plugin's js instances to avoid any possible conflicts with other js libraries
- Changed the element type for the backtop button, since no anchor is needed
- Changed the css for the backtop button to appear more neutral
- Changed some default option values
- The color picker accepts keyboard inputs now
- The color picker offers an alpha channel now
- Minor style changes
- Minor code changes

## Version 0.6.0
- Moved the option "Scrollbar Default Look" down the list and renamed it to "Show Default Scrollbar"
- Hooked plugin initialisation to "plugins_loaded"
- Minor style changes
- Minor code changes
- Added an option to enable Nicescroll on mobile devices. default value is off

## Version 0.5.8
- Checked compatibility with the latest version of WordPress

## Version 0.5.7
- Changed default z-index value to 99998 (it is on top of everything except the admin bar)
- Changed default auto-hide mode for frontend and backend to 'disabled'
- Optimized the listener that watches changes on the document

## Version 0.5.6
- Renamed the class name for the backtop button
- Fixed a bug regarding the scroll-to functionality, which is used to scroll to settings that did not pass Validation
- Several code improvements
- Updated the note regarding my support forum visits
- Moved the reset buttons to the respective settings tabs
- Added some styling Options for the backtop buttons. I advise you to navigate to the settings
- Added credit to the author of Nicescroll in the description text
- Removed the deprecated plugin settings tab

## Version 0.5.5
- The plugin can handle document changes now (adopts to changes in height)

## Version 0.5.4
- Fixed an error that occurred during plugin activation

## Version 0.5.3
- Updated the Nicescroll library to the latest version (3.7.6), now loads from CDN with local fallback
- Updated jQuery Easing library to the latest version (1.4.1), now loads from CDN with local fallback
- Updated the settings and the default values to fit the current Nicescroll version
- Updated readme files and the help tab
- Back-top button is now available for the entire backend
- Minor stability and performance improvements
- Minor UI changes
- Added the optional back-top button for the entire frontend

## Version 0.5.2
- Fixed a bug regarding the case of an empty Options array
- Minor code optimisations
- Minor UI changes
- Disabled Nicescroll Options that are not related to this plugins's functionality (boxzoom, iframeautozesize, ...)
- Removed a plugin setting - ScrollTo is enabled by default now
- Removed local fallback for Font Awesome

## Version 0.5.0
- Upgraded Nicescroll to version 3.6.8. Scroll behaviour may has changed since the library changed. Tweak the settings mentioned under 4. in this list if necessary
- Added easing
- Upgraded Alertify
- Replaced IcoMoon icons in favour of Font Awesome 4.7.0 ( loads via CDN with local fallback )
- Moved "Scroll Speed" and "Mouse Scroll Step" to the basic settings section for easier access
- Refactored and optimized scripts and code
- Updated the default settings
- Resolved an issue with "scroll top"

## Version 0.4.0
- Minor bug fixes
- Minor code optimisations

## Version 0.3.0
- Updated the user interface, all Options are available again
- Refactored the javascripts
- Support for PHP Version 5.3 ( was 5.4+ )
- Minor code optimizations
- Removed "Fugaz One" font family

## Version 0.2.2
- Nicescroll is now being disabled on mobile devices with a screen width below 960px to enhance user experience and prevent touch-related incompatibilities

## Version 0.2.1.1
- Fixed compatibility with "cbParallax", which uses the Nicescroll library
- Fixed missing stuff...

## Version 0.2.1
- Some minor code and comment cleanup
- Added an option to replace the nicescroll scrollbar with the browsers default scrollbar

## Version 0.2.0
- Resolved the translation bugs
- Optimized the default settings for both the frontend and the backend
- Implemented the nicescroll.plus-library. It basically widens the rail and the cursor if their width is below 16px to 16px on hover so it's easier to grab it
- Modified the interface
- Updated the screenshots

## Version 0.1.1
- Fixed the icons
- Replaced the switch for the checkbox with one that's compatible with Safari browsers
- Slightly modified the content of the help settings_tab
- Updated the German translation
- Fixed an issue with a missing url for "scrollTo"
- Made the Validation error notices dismissable
- The scrollTo-extension now also works for the color pickers
- Some minor bug fixes
- Added the option to assign a background color to the rail on both frontend and backend

## Version 0.1.0
First commit.
