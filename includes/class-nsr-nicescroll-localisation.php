<?php

/**
 * The class responsible for localizing the Nicescroll configuration file.
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
class nsr_nicescroll_localisation {

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
*@since  0.1.0
	 * @access private
	 * @var    object $Options
	 */
	private $Options;

	/**
	 * The name of the view for the admin area.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $view
	 */
	public $view;

	/**
	 * Kick off.
	 *
	 * @param array $keys
	 */
	public function __construct( $keys ) {

		$this->keys = $keys;

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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/menu/includes/class-nsr-options.php";

		$this->Options = new nsr_options( $this->get_keys() );
	}

	/**
	 * Calls the function that passes the parameters to the Nicescroll library.
	 *
	 * @since  0.1.0
	 * @param  string $view
	 * @return void
	 */
	public function run( $view ) {

		$this->localize_nicescroll( $view );
	}

	/**
	 * Localzes the Nicescroll instance.
	 * @since  0.1.0
	 * @param  string $view
	 * @return void
	 */
	private function localize_nicescroll( $view ) {

		wp_localize_script( $this->keys['plugin_name'] . '-nicescroll-js', 'GlobalOptions', $this->get_nicescroll_configuration( $view ) );
	}

	/**
	 * Retrieves the options per requested view from the database
	 * and removes the determined prefix so that
	 * the option keys correspond to the naming conventions of the Nicescroll library.
	 * it contains a fallback to prevent "undefined"-errors in the script that's to be localized.
	 *
	 * @since  0.1.0
	 * @param  string $view
	 * @return array
	 */
	public function get_nicescroll_configuration( $view ) {

		if( false !== get_option( $this->keys['option_group'] ) ) {

			$options = get_option( $this->keys['option_group'] );
		} else {

			$options = $this->Options->get_default_options( $view );
		}

		$configuration = $options[ $view ];
		// Removes the first entry, since it is not a Nicescroll configuration parameter.
		array_shift( $configuration );

		return $configuration;
	}

	/**
	 * Retrieves the application keys.
	 *
	 * @since  0.1.0
	 * @return array
	 */
	public function get_keys() {

		return $this->keys;
	}

}
