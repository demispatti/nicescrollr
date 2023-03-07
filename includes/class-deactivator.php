<?php
namespace Nicescrollr\Includes;

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class responsible for the deactivation process.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_Deactivator {

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
		if( ! empty( $role ) ) {
			$role->remove_cap( self::$capability );
		}
	}
}
