<?php

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __DIR__ ) . 'admin/menu/includes/class-nsr-options.php';

/**
 * The class responsible for the plugin activation.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_activator extends nsr {

	/**
	 * The name of the capability.
	 *
	 * @since  0.1.0
	 * @access static
	 * @var    string $capability
	 */
	public static $capability = 'nicescrollr_edit';

	/**
	 * Fired during the activation of the plugin.
	 * Adds the capability to interact with this plugin.
	 * Creates a reference of the activator class to check if there are already any options stored in the database.
	 * If not, it writes the default values for each option group to the database.
	 *
	 * @since    0.1.0
	 * @access   static
	 * @return   void
	 */
	public static function activate() {

		// Gets the administrator role.
		$role = get_role( 'administrator' );

		// If the acting user has admin rights, the capability gets added.
		if( ! empty( $role ) ) {
			$role->add_cap( self::$capability );
		}

		// Checks for already stored options.
		$Activator = new self();
		$Activator->check_for_options();
	}

	/**
	 * Creates a reference to the data class and writes the default option values to the database if there aren't any yet.
	 *
	 * @since  0.1.0
	 * @uses   get_settings_sections()
	 * @uses   seed_options()
	 * @see    admin/menu/includes/class-nsr-options.php
	 * @access private
	 * @return void
	 */
	private function check_for_options() {

		$nsr_options = new nsr_options( 'nicescrollr' );
		$options = get_option( 'nicescrollr_options' );

		// Seed initial options
		if( false === $options ) {
			foreach( $nsr_options::$settings_tabs as $i => $section ) {
				$nsr_options->seed_options( $section );
			}

			return;
		}

		// Else add initial options per section if necessary
		$options = get_option( 'nicescrollr_options' );
		if( null === $options['frontend'] ) {
			$nsr_options->seed_options( 'frontend' );
		}
		if( null === $options['backend'] ) {
			$nsr_options->seed_options( 'backend' );
		}
	}
}
