<?php

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
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
	 * @return   mixed
	 * @access   private
	 */
	private function upgrade_options() {

		if( false === get_option( 'nicescrollr_options' ) ) {
			return;
		}

		$options = get_option( 'nicescrollr_options' );

		// We bail if the upgrade already took place.
		if( isset( $options['internal']['upgrade'] ) && $options['internal']['upgrade'] == '0.5.0' ) {
			return;
		}

		// A flag to surpass the validation process.
		$_SESSION['cb_parallax_upgrade'] = true;

		$options['frontend']['scrollspeed']     = '64';
		$options['frontend']['mousescrollstep'] = '96';

		$options['backend']['scrollspeed']     = '64';
		$options['backend']['mousescrollstep'] = '96';

		$options['internal'] = array( 'upgrade' => '0.5.0' );

		if( update_option( 'nicescrollr_options', $options ) ) {

			return true;

		} else {

			return false;
		}
	}

}
