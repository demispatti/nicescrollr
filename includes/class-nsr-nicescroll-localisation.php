<?php

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

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
	 * @param array $app
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/menu/includes/class-nsr-options.php";

		$this->options = new nsr_options( $this->get_domain() );
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

		wp_localize_script( 'nicescrollr' . '-nicescroll-js', 'nsrOptions', $this->get_nicescroll_configuration( $view ) );
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

		if( false !== get_option( 'nicescrollr_options' ) ) {

			$options = get_option( 'nicescrollr_options' );
		} else {

			$options = $this->options->get_default_options( $view );
		}

		$configuration = $options[ $view ];
		// Add the value which defines the view ( front- or backend).
		$configuration['view'] = $view;

		return $configuration;
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
