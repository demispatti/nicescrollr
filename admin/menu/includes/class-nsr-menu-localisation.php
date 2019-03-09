<?php

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
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
	 * @var    object $options
	 */
	private $options;

	/**
	 * Assigns the required parameters to its instance.
	 *
	 * @since  0.1.0
	 *
*@param  array        $domain
	 * @param  object $Plugin_Data
	 *
*@return void
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "includes/class-nsr-options.php";

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

		wp_localize_script(
			'nicescrollr-menu-js',
			'nsrMenu',
			array_merge(
				$this->get_plugin_options(),
				$this->options->count_basic_settings(),
				$this->get_localized_strings_for_switches()
			)
		);
	}

	/**
	 * Retrieves the plugin options prepared for localisation.
	 * Contains a fallback for the case that there is no plugin options stored in the database.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return array $plugin_settings
	 */
	private function get_plugin_options() {

		if( false !== get_option( 'nicescrollr_options' ) && '1' !== get_option( 'nicescrollr_options' ) ) {

			$options = get_option( 'nicescrollr_options' );

			$plugin_options = $options['plugin'];
		} else {

			$plugin_options = $this->options->get_plugin_options();
			$count = array();

			foreach( $plugin_options as $key => $option ) {

				array_push( $count, '0' );
			}

			$plugin_options = $count;
		}

		return $plugin_options;
	}

	/**
	 * Retrieves the "basic options" count.
	 * This value is used to determine if there are validation errors
	 * inside the "extended settings" section. If so, the section will
	 * be expanded for the scrollTo-functionality to work. Else it won't work.
	 *
	 * @since  0.1.0
	 * @uses   get_basic_options_count()
	 * @see    admin/menu/includes/class-nsr-options.php
	 * @see    admin/menu/js/menu.js
	 * @access private
	 * @return array
	 */
	private function get_basic_options_count() {

		$count = $this->options->count_basic_settings();

		return $count;
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

		$current = $this->get_locale();

		switch( $current ) {

			case($current['locale'] == 'de_DE');
				$labels = array( 'locale' => $current['locale'], 'On' => 'Ein', 'Off' => 'Aus' );
				break;

			default:
				$labels = array( 'locale' => 'default', 'On' => 'On', 'Off' => 'Off' );
		}

		return $labels;
	}

	/**
	 * Retrieves the locale of the WordPress installation.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string
	 */
	private function get_locale() {

		$locale = array( 'locale' => get_locale() );

		return $locale;
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
