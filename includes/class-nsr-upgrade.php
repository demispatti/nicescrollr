<?php

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Executes function such as migrating image_options and refactoring existing meta_keys.
 *
 * @link              https://github.com/demispatti/nicescrollr/
 * @since             0.6.0
 * @package           nsr
 * @subpackage        nsr/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_upgrade {

	/**
	 * Calls the function that upgrades the database.
	 *
	 * @hooked_action
	 *
	 * @since    0.6.0
	 * @return   void
	 * @access   public
	 */
	public function run() {

		$this->upgrade_options();
	}

	/**
	 * Calls the function that upgrades the options.
	 * In this case, we tweak these two options since the saved user-defined values might be too slow
	 * now that the Nicescroll library got updated.
	 *
	 * @since    0.6.0
	 * @return   void
	 * @access   private
	 */
	private function upgrade_options() {

		$options = get_option( 'nicescrollr_options' );

		if( ! isset( $options['nicescrollr']['version'] ) ) {
			$this->migrate_stored_options();
		}

		if( isset( $options['nicescrollr']['version'] ) && '0.5.4' === $options['nicescrollr']['version'] ) {
			$options = new nsr_options( 'nicescrollr' );
			$options->seed_options();
		}

		$this->update_options();
	}

	private function migrate_stored_options() {

		$this->migrate( 'frontend' );
		$this->migrate( 'backend' );
	}

	private function migrate( $section ) {

		$keys_to_unset = array();

		if( 'frontend' || 'backend' === $section ) {

			$keys_to_unset = array(
				'defaultScrollbar',
				'touchbehavior',
				'usetransition',
				'spacebar',
				'cursormaxheight',
				'overflowx',
				'overflowy'
			);
		}
		if( 'plugin' === $section ) {

			$keys_to_unset = array(
				'backtop_enabled'
			);
		}

		$stored_options = get_option( 'nicescrollr_options' );
		$options = array();

		if( 'frontend' === $section || 'backend' === $section ) {

			$options = $stored_options[$section];

			if( null === $options ) {
				return;
			}
			foreach( (array) $options as $option_key => $value ) {
				if( in_array( $option_key, $keys_to_unset, true ) ) {
					unset( $options[$option_key] );
				}
			}

			// Optimisation due to Nicescroll update
			$options['default_scrollbar'] = isset( $stored_options[$section]['defaultScrollbar'] ) ? $stored_options[$section]['defaultScrollbar'] : false;
			$options['scrollspeed'] = 60;
			$options['mousescrollstep'] = 40;
			// New options
			$options['emulatetouch'] = false;
			$options['oneaxismousemode'] = 'auto';
			//$options[$section]['scriptpath'] = '';
			$options['preventmultitouchscrolling'] = true;
			$options['disablemutationobserver'] = false;
			$options['enableobserver'] = true;
			$options['scrollbarid'] = 'ascrail2000';
			// Formerly inactive options
			$options['spacebarenabled'] = isset( $stored_options[$section]['spacebar'] ) ? $stored_options[$section]['spacebar'] : true;
			$options['boxzoom'] = false;
			$options['dblclickzoom'] = true;
			$options['gesturezoom'] = true;
			$options['background'] = '';
			$options['iframeautoresize'] = true;
			$options['enabletranslate3d'] = true;
			$options['enablescrollonselection'] = true;

		}
		// Integrating "backtop" on/off checkboxes (deprecated plugin settings)
		if( 'frontend' === $section ) {
			$options['bt_enabled'] = isset( $stored_options['plugin']['frontend_backtop_enabled'] ) ? $stored_options['plugin']['frontend_backtop_enabled'] : false;
		}
		if( 'backend' === $section ) {
			$options['bt_enabled'] = isset( $stored_options['plugin']['backend_backtop_enabled'] ) ? $stored_options['plugin']['frontend_backtop_enabled'] : true;
		}

		foreach( $options as $option_key => $value ) {

			if( 'off' === $value ) {
				$options[$option_key] = false;
			}
			if( 'on' === $value ) {
				$options[$option_key] = true;
			}
		}
	}

	private function update_options() {

		$options = get_option( 'nicescrollr_options' );
		$options['nicescrollr']['version'] = '0.5.6';
		unset( $options['plugin'] );

		delete_option( 'nicescrollr_options' );
		update_option( 'nicescrollr_options', $options, true );
	}

}
