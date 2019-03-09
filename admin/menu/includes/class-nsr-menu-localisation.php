<?php

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class responsible for localizing the admin part of this plugin.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/menu/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_menu_localisation {

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
	 * @var    nsr_options $options
	 */
	private $options;

	/**
	 * Assigns the required parameters to its instance.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $domain
	 *
	 * @return void
	 */
	public function __construct( $domain ) {

		$this->domain = $domain;

		$this->load_dependencies();
	}

	/**
	 * Loads it's dependencies.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {

		// The class that maintains all data like default values and their meta data.
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-nsr-options.php';

		$this->options = new nsr_options( $this->get_domain() );
	}

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

		wp_localize_script( 'nicescrollr-menu-js', 'nsr_menu', array_merge( $this->options->count_basic_settings(), $this->options->count_extended_settings(), $this->get_localized_strings_for_switches() ) );
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

	/**
	 * Retrieve the name of the domain.
	 *
	 * @since  0.1.0
	 *
	 * @access private
	 *
	 * @return string $domain
	 */
	private function get_domain() {

		return $this->domain;
	}

}
