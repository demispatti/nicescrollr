=== Nicescrollr ===

Tags: nicescroll, scroll, scrollbar, scrolling, nice, frontend, backend, scrollen  

Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XLMMS7C62S76Q  
Requires at least: 4.5
Tested up to: 4.7.2
Version: 0.5.0
Stable tag: 0.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Contributors: demispatti

== Description ==

This plugin brings the "Nicescroll" scrollbar to your website. You can use it on both the frontend and the backend. It is fully customizable, so you can tweak and tune every single parameter Nicescroll has to offer! You can style it totally different for both views. You can even keep the default scrollbar if you like. It's all up to you. Install and enjoy :-)

== Features ==

+ The Nicescroll jQuery plugin
+ Smooth scroll
+ Fully customizable
+ Frontend and backend

== Requirements ==

- PHP 5.3+

== Installation ==

1. Upload the `nicescrollr` folder to your `/wp-content/plugins/` directory.
2. Activate the "Nicescrollr" plugin through the "Plugins" menu in WordPress.
3. You will find its settings page listed in the "settings" section.
3. Go to the plugin settings page and fit it to your needs :-) Have fun!

== Frequently Asked Questions ==

= Why doesn't it work with my theme? =

Most likely, this is because your theme (or another plugin?) already has the Nicescroll library on board. If that's the case, I advise you to uninstall this plugin again to prevent compatibility issues. Of course, you can always ask your theme developer to implement a function to disable the built-in solution if you like to use this plugin and its options.

The capability required for being able to customize settings is the following:

* `nicescrollr_edit` - The user can change settings regarding this plugin.

= Can you help me? =

Well, I provide some basic support on this plugins support page. I check that place up to three times a week, so you may have to be a bit patient ;-)

== Screenshots ==

1. Basic settings panel.
2. Extended settings panel, upper part.
3. Extended settings panel, lower part.

== Konwn Issues ==
The backtop-button on the backend does not yet work with firefox.
This will be fixed in the next release

== Changelog ==

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
1. Updated the user interface, all options are available again
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
4. Slightly modified the content of the help tab.
5. Updated the German translation.
6. Fixed an issue with a missing url for "scrollTo".
7. Made the validation error notices dismissable.
8. The scrollTo-extension now also works for the color pickers.
8. Some minor bug fixes.

= Version 0.1.0 =

First commit.
