<?php

/**
 * The plugin bootstrap file.
 *
 * This plugin provides a simple interface for the included jQuery Nicescroll library.
 * It comes with an extensive options panel giving you
 * full control over almost all available options the Nicescroll library gets shipped with.
 *
 * I wrote this plugin because I wanted to have a nice scroll behavoiur while working in the backend.
 * You can enable and customize it for both the backend and the frontend.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nsr
 * @wordpress-plugin
 * Plugin Name:       Nicescrollr
 * Plugin URI:        https://github.com/demispatti/nicescrollr/
 * Description:       An easy to use interface for the included jQuery Nicescroll plugin, packed with an extensive options panel.
 * Version:           0.1.0
 * Stable tag:        0.1.0
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nicescrollr
 * Domain Path:       /languages
 */

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
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
