<?php
namespace Nicescrollr\Admin\Includes;

use Nicescrollr\Admin\Includes as AdminIncludes;

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
 * @subpackage        nicescrollr/admin/includes
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_Menu_Localisation {

	/**
	 * The reference to the options class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    AdminIncludes\Nsr_Options $Options
	 */
	private Nsr_Options $Options;

    /**
     * Nsr_Menu_Localisation constructor.
     *
     * @param Nsr_Options $Options
     */
	public function __construct(Nsr_Options $Options) {

		$this->Options = $Options;
	}

	/**
	 * Kicks off localisation of the menu.
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function run(): void
    {

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
	private function localize_script(): void
    {

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
	private function get_localized_strings_for_switches(): array
    {
		$current_locale = get_locale();

        if($current_locale == "de_DE") {
            return array('locale' => $current_locale, 'On' => 'Ein', 'Off' => 'Aus');
        }
        else {
            return array('locale' => 'default', 'On' => 'On', 'Off' => 'Off');
        }
	}

}
