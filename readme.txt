=== Nicescrollr ===

Tags: nicescroll, scroll, scrollbar, scrolling, nice, frontend, backend, scrollen  

Requires at least: 4.7
Tested up to: 5.0.2
Requires PHP: 5.4
Version: 0.7.0
Stable tag: 0.7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Author: demispatti
Author URI: https://demispatti.ch

Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XLMMS7C62S76Q

== Description ==

I really like InuYaksa's Nicescroll JS plugin. I'm a fan. And I thought there may be others like me who would enjoy using his famous library with a simple interface. So I came up with this plugin. It is a wrapper for the popular "Nicescroll" javascript library, which is developed and maintained by <a href="https://wordpress.org/support/users/inuyaksa/" target="_blank">InuYaksa</a>. You can visit his official Nicescroll homepage <a href="https://www.areaaperta.com/nicescroll/" target="_blank">here</a>. An overview of the default parameters can be found on <a href="https://github.com/inuyaksa/jquery.nicescroll" target="_blank">Github</a> at the bottom of the page. This plugin enables you to use the Nicescroll scrollbar on both the frontend and the backend. It is fully customizable, you can tweak and tune every single parameter Nicescroll has to offer! You can style it totally different for both views. You can even keep the default scrollbar with Nicescroll's smooth scroll behaviour if you like. It's all up to you. Install and enjoy :-)


== Features ==

+ Nicescroll jQuery library
+ Fully customizable
+ Frontend and backend
+ Back to top buttons


== Requirements ==
- PHP 5.4+


== Installation ==

1. Upload the `nicescrollr` folder to your `/wp-content/plugins/` directory.
2. Activate the "Nicescrollr" plugin through the "Plugins" menu in WordPress.
3. You will find its settings page listed in the "settings" section.
3. Go to the plugin settings page and fit it to your needs :-) Have fun!


== Frequently Asked Questions ==

= Why doesn't it work with my theme? =
Most likely, this is because your theme (or another plugin?) already has the Nicescroll library on board. If that's the case, I advise you to uninstall this plugin again to prevent compatibility issues. Of course, you can always ask your theme developer to implement a function to disable the built-in solution if you like to use this plugin and its Options.

The capability required for being able to customize settings is the following:

* `nicescrollr_edit` - The user can change settings regarding this plugin.

= Can you help me? =
Well, I provide some basic support on this plugin's support page. I check that place once or twice a month, so you may have to be a bit patient.


== Screenshots ==

1. Basic settings panel.
2. Extended settings panel, upper part.
3. Extended settings panel, lower part.


== Changelog ==

= Version 0.7.0 =
1. Introduced namespaces
2. Introduced automated testing
3. Fixed a variety of bugs
4. Updated dependencies
5. Enhanced performance
6. Added minified versions of js and css files

= Version 0.6.7 =
1. Should have resolved that compatibility warning on this plugin's homepage

= Version 0.6.6 =
1. Should have resolved that compatibility warning on this plugin's homepage

= Version 0.6.5 =
1. Minor css improvement

= Version 0.6.4 =
1. Fixed scrollbar display issues
2. Renamed the main plugin file from nsr.php to nicescrollr.php for testing reasons, so you may need to activate the plugin again

= Version 0.6.3 =
1. Added Options to set border color and background color of the backtop button for hover state
2. Minor code clean up

= Version 0.6.2 =
1. Renamed css class "top-is-visible"  to "nsr-backtop-is-visible" due to compatibility reasons with buttons that are shipped with themes
2. Renamed the plugin's js instances to avoid any possible conflicts with other js libraries
3. Changed the element type for the backtop button, since no anchor is needed
4. Changed the css for the backtop button to appear more neutral
5. Changed some default option values
6. The color picker accepts keyboard inputs now
7. The color picker offers an alpha channel now
8. Minor style changes
9. Minor code changes

= Version 0.6.0 =
1. Added an option to enable Nicescroll on mobile devices. default value is off
2. Moved the option "Scrollbar Default Look" down the list and renamed it to "Show Default Scrollbar"
3. Hooked plugin initialisation to "plugins_loaded"
4. Minor style changes
5. Minor code changes

= Version 0.5.8 =
1. Checked compatibility with the latest version of WordPress

= Version 0.5.7 =
1. Changed default z-index value to 99998 (it is on top of everything except the admin bar)
2. Changed default auto-hide mode for frontend and backend to 'disabled'
3. Optimized the listener that watches changes on the document

= Version 0.5.6 =
1. Added some styling Options for the backtop buttons. I advise you to navigate to the settings pages for the frontend and the backend and simply hit the "save settings" button to add the new features

2. Removed the deprecated plugin settings tab
3. Renamed the class name for the backtop button
4. Fixed a bug regarding the scroll-to functionality, which is used to scroll to settings that did not pass Validation
5. Added credit to the author of Nicescroll in the description text
6. Several code improvements
7. Updated the note regarding my support forum visits
8. Moved the reset buttons to the respective settings tabs

= Version 0.5.5 =
1. The plugin can handle document changes now (adopts to changes in height)

= Version 0.5.4 =
1. Fixed an error that occurred during plugin activation

= Version 0.5.3 =
1. Updated the Nicescroll library to the latest version (3.7.6), now loads from CDN with local fallback
2. Updated jQuery Easing library to the latest version (1.4.1), now loads from CDN with local fallback
3. Updated the settings and the default values to fit the current Nicescroll version
4. Updated readme files and the help tab
5. Back-top button is now available for the entire backend
6. Added the optional back-top button for the entire frontend
7. Minor stability and performance improvements
8. Minor UI changes

= Version 0.5.2 =
1. Fixed a bug regarding the case of an empty Options array
2. Minor code optimisations
3. Removed a plugin setting - ScrollTo is enabled by default now
4. Minor UI changes
5. Disabled Nicescroll Options that are not related to this plugins's functionality (boxzoom, iframeautozesize, ...)
6. Removed local fallback for Font Awesome

= Version 0.5.0 =
1. Upgraded Nicescroll to version 3.6.8. Scroll behaviour may has changed since the library changed. Tweak the settings mentioned under 4. in this list if necessary
2. Added easing
2. Upgraded Alertify
3. Replaced IcoMoon icons in favour of Font Awesome 4.7.0 ( loads via CDN with local fallback )
4. Moved "Scroll Speed" and "Mouse Scroll Step" to the basic settings section for easier access
5. Refactored and optimized scripts and code
6. Updated the default settings
7. Resolved an issue with "scroll top"

= Version 0.4.0 =
1. Minor bug fixes
2. Minor code optimisations

= Version 0.3.0 =
1. Updated the user interface, all Options are available again
2. Refactored the javascripts
3. Support for PHP Version 5.3 ( was 5.4+ )
4. Removed "Fugaz One" font family
5. Minor code optimizations

= Version 0.2.2 =
1. Nicescroll is now being disabled on mobile devices with a screen width below 960px to enhance user experience and prevent touch-related incompatibilities.

= Version 0.2.1.1 =
1. Fixed missing stuff...
2. Fixed compatibility with "cbParallax", which uses the Nicescroll library

= Version 0.2.1 =
1. Added an option to replace the nicescroll scrollbar with the browsers default scrollbar
2. Some minor code and comment cleanup

= Version 0.2.0 =
1. Resolved the translation bugs
2. Optimized the default settings for both the frontend and the backend
3. Implemented the nicescroll.plus-library.
   It basically widens the rail and the cursor if their width is below 16px to 16px on hover so it's easier to grab it
4. Modified the interface
5. Updated the screenshots

= Version 0.1.1 =
1. Fixed the icons.
2. Replaced the switch for the checkbox with one that's compatible with Safari browsers.
3. Added the option to assign a background color to the rail on both frontend and backend.
4. Slightly modified the content of the help settings_tab.
5. Updated the German translation.
6. Fixed an issue with a missing url for "scrollTo".
7. Made the Validation error notices dismissable.
8. The scrollTo-extension now also works for the color pickers.
8. Some minor bug fixes.

= Version 0.1.0 =
First commit.
