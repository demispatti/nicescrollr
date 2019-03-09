<?php

/**
 * The class responsible for the deactivation process.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_deactivator {

	/**
	 * The name of the capability.
	 *
	 * @since  0.1.0
	 * @access static
	 * @var    string $capability
	 */
	public static $capability = 'nicescrollr_edit';

	/**
	 * Fired during deactivation of the plugin.
	 * Removes the capability to edit custom backgrounds from the administrator role.
	 *
	 * @since  0.1.0
	 * @access static
	 * @return void
	 */
	public static function deactivate() {

		// Gets the administrator role.
		$role = get_role( 'administrator' );

		// The capability gets removed.
		if( !empty($role) ) {
			$role->remove_cap( self::$capability );
		}
	}
}
