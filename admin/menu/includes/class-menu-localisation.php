<?php

namespace Nicescrollr\Admin\Menu\Includes;

use Nicescrollr\Admin\Menu\Includes as MenuIncludes;

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class responsible for localizing the admin part of this plugin.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/menu/includes
 * Author:            Demis Patti <demispatti@gmail.com>
 * Author URI:
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_Menu_Localisation {

	/**
	 * The domain of the plugin.
	 *
	 * @since  0.1.0
	 *
	 * @access private
	 *
	 * @var string $domain
	 */
	private $domain;

	/**
	 * The reference to the options class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    MenuIncludes\Nsr_Options $Options
	 */
	private $Options;

	/**
	 * Nsr_Menu_Localisation constructor.
	 *
	 * @param $domain
	 * @param MenuIncludes\Nsr_Options $Options
	 */
	public function __construct( $domain, $Options ) {

		$this->domain = $domain;
		$this->Options = $Options;

		//$this->load_dependencies();
	}

	/**
	 * Loads it's dependencies.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	/*private function load_dependencies() {

		// The class that maintains all data like default values and their meta data.
		require_once NICESCROLLR_ROOT_DIR . 'admin/menu/includes/class-options.php';

		$this->options = new MenuIncludes\Nsr_Options( $this->get_domain() );
	}*/

	/**
	 * Kicks off localisation of the menu.
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function run() {

		$this->localize_script();
	}

	/**
	 * Localizes the menu.
	 *
	 * @since  0.1.0
	 * @see    admin/menu/js/menu.js
	 * @access private
	 * @return void
	 */
	private function localize_script() {

		wp_localize_script( 'nicescrollr-menu-js', 'Nsr_Menu', array_merge( $this->Options->count_basic_settings(), $this->Options->count_extended_settings(), $this->get_localized_strings_for_switches() ) );
	}

	/**
	 * Retrieves - so far German - strings to localize some css-pseudo-selectors.
	 *
	 * @since  0.1.0
	 * @see    admin/settings-menu/css/checkboxes.css
	 * @see    admin/settings-menu/js/menu.js
	 * @access private
	 * @return array
	 */
	private function get_localized_strings_for_switches() {

		$current_locale = get_locale();

		switch( $current_locale ) {

			case( $current_locale === 'de_DE' );
				$labels = array( 'locale' => $current_locale, 'On' => 'Ein', 'Off' => 'Aus' );
				break;

			default:
				$labels = array( 'locale' => 'default', 'On' => 'On', 'Off' => 'Off' );
		}

		return $labels;
	}

}
