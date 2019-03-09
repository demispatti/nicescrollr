<?php

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
	 * The array holding the application keys.
	 *
	 * @since 0.1.0
	 * @acces private
	 * @var   array $keys
	 */
	private $keys;

	/**
	 * The reference to the options class.
	 *
     * @since  0.1.0
	 * @access private
	 * @var    object $Options
	 */
	private $Options;

	/**
	 * Assigns the required parameters to its instance.
	 *
	 * @since  0.1.0
	 * @param  array $keys
	 * @param  object $Plugin_Data
	 * @return mixed
	 */
	public function __construct( $keys, $Plugin_Data ) {

		$this->keys = $keys;
		$this->Options = $Plugin_Data;
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
			$this->keys['plugin_name'] . '-menu-js',
			'nsrMenu',
			 array_merge(
				$this->get_plugin_options(),
				$this->get_basic_options_count(),
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

		if( false !== get_option( $this->keys['option_group'] ) && '1' !== get_option( $this->keys['option_group'] ) ) {

			$options = get_option( $this->keys['option_group'] );

			$plugin_options = $options['plugin'];
		} else {

			$plugin_options = $this->Options->get_plugin_options();
			$count   = [];

			foreach ( $plugin_options as $key => $option ) {

				array_push( $count, '0' );
			}

			$plugin_options  = $count;
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

		$count = $this->Options->count_basic_settings();

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

		$locale = $this->get_locale();

		switch( $locale ) {

			case( $locale == 'de_DE');
				$labels = array( 'locale' => $locale, 'On' => 'Ein', 'Off' => 'Aus' );
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

}
