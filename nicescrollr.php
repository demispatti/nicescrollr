<?php

namespace Nicescrollr;

use Nicescrollr\Includes as Includes;

/**
 * The plugin bootstrap file.
 *
 * This plugin provides a simple interface for the included jQuery Nicescroll library.
 * It comes with an extensive options panel giving you
 * full control over almost all available options the Nicescroll library gets shipped with.
 *
 *
 * @since             0.1.0
 * @package           Nsr
 * @wordpress-plugin
 * Plugin Name:       Nicescrollr
 * Plugin URI:        https://wordpress.org/plugins/nicescrollr/
 * Description:       This plugin is a wrapper for the popular "Nicescroll" javascript library, which is made by <a href="https://wordpress.org/support/users/inuyaksa/" target="_blank">InuYaksa</a>. You can use it on both the frontend and the backend. It is fully customizable, so you can tweak and tune every single parameter Nicescroll has to offer! You can style it totally different for both parts of your website. You can even keep the default scrollbar if you like. It's all up to you.
 * Tags: nicescroll, scroll, scrollbar, back to top, scroll to top, frontend, backend
 * Requires at least: 5.1
 * Tested up to: 5.3
 * Requires PHP: 5.6+
 * Version: 0.7.3
 * Stable tag: 0.7.3
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Author: demispatti
 * Author URI: https://demispatti.ch
 * Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XLMMS7C62S76Q
 */

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

define( 'NICESCROLLR_DEBUG', '1' );

/**
 * Define plugin constants.
 */
if( ! defined( 'NICESCROLLR_ROOT_DIR' ) ) {
	define( 'NICESCROLLR_ROOT_DIR', plugin_dir_path( __FILE__ ) );
}
if( ! defined( 'NICESCROLLR_ROOT_URL' ) ) {
	define( 'NICESCROLLR_ROOT_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Include dependencies.
 */
if( ! class_exists( 'Includes\Nsr' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'includes/class-nsr.php';
}
if( ! class_exists( 'Includes\Nsr_Activator' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'includes/class-activator.php';
}
if( ! class_exists( 'Includes\Nsr_Deactivator' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'includes/class-deactivator.php';
}

/**
 * The function that gets fired on plugin activation.
 *
 * @since 0.1.0
 * @uses  activate_nsr()
 * @see   includes/class-Nsr-activator.php
 */
function activate_nsr() {

	Includes\nsr_activator::activate();
}

/**
 * The function that gets fired on plugin deactivation.
 *
 * @since 0.1.0
 * @uses  deactivate_nsr()
 * @see   includes/class-Nsr-deactivator.php
 */
function deactivate_nsr() {

	Includes\nsr_deactivator::deactivate();
}

register_activation_hook( __FILE__, "Nicescrollr\activate_nsr" );
register_deactivation_hook( __FILE__, 'Nicescrollr\deactivate_nsr' );

/**
 * Begins execution of the plugin.
 *
 * @since 0.1.0
 */
function run_nsr() {

	$plugin = new Includes\Nsr();
	$plugin->run();
}

add_action( 'plugins_loaded', 'Nicescrollr\run_nsr' );
