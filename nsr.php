<?php

/**
 * The plugin bootstrap file.
 *
 * This plugin provides a simple interface for the included jQuery Nicescroll library.
 * It comes with an extensive options panel giving you
 * full control over almost all available options the Nicescroll library gets shipped with.
 *
 *
 * @link              https://wordpress.org/plugins/nicescrollr/
 * @since             0.1.0
 * @package           nsr
 * @wordpress-plugin
 * Plugin Name:       Nicescrollr
 * Plugin URI:        https://wordpress.org/plugins/nicescrollr/
 * Description:       This plugin brings the "Nicescroll" library to your website. You can use it on both the frontend and the backend. It is fully customizable, so you can tweak and tune every single parameter Nicescroll has to offer! You can style it totally different for both parts of your website. You can even keep the default scrollbar if you like. It's all up to you.
 * Version:           0.4.0
 * Stable tag:        0.4.0
 * Author:            Demis Patti <demispatti@gmail.com>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nicescrollr
 * Domain Path:       /languages
 */

/**
 * If this file is called directly, abort.
 */
if( !defined( 'WPINC' ) ) {
	die;
}

/**
 * The function that gets fired on plugin activation.
 *
 * @since 0.1.0
 * @uses  activate_nsr()
 * @see   includes/class-nsr-activator.php
 */
function activate_nsr() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nsr-activator.php';
	nsr_activator::activate();
}

/**
 * The function that gets fired on plugin deactivation.
 *
 * @since 0.1.0
 * @uses  deactivate_nsr()
 * @see   includes/class-nsr-deactivator.php
 */
function deactivate_nsr() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nsr-deactivator.php';
	nsr_deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_nsr' );
register_deactivation_hook( __FILE__, 'deactivate_nsr' );

/**
 * The core plugin class.
 *
 * @since 0.1.0
 * @see   includes/class-nsr.php
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nsr.php';

/**
 * Begins execution of the plugin.
 *
 * @since 0.1.0
 */
function run_nsr() {

	$Plugin = new nsr();
	$Plugin->run();
}

run_nsr();
