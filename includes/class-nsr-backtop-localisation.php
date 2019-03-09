<?php

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class responsible for localizing the backtop script.
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
class nsr_backtop_localisation {

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

		require_once plugin_dir_path( __DIR__ ) . 'admin/menu/includes/class-nsr-options.php';
		$this->options = new nsr_options( $this->get_domain() );
	}

	/**
	 * Calls the function that passes the parameters to the Nicescroll library.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $view
	 *
	 * @return void
	 */
	public function run( $view ) {

		$this->localize_backtop( $view );
	}

	/**
	 * Localzes the backtop script.
	 * @since  0.1.0
	 *
	 * @param  string $view
	 *
	 * @return void
	 */
	private function localize_backtop( $view ) {

		wp_localize_script( 'nicescrollr-backtop-js', 'nsr_options', $this->get_backtop_configuration( $view ) );
	}

	/**
	 * Retrieves the options for the backtop functionality.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $view
	 *
	 * @return array
	 */
	public function get_backtop_configuration( $view ) {

		$configuration = (array) $this->options->get_options( $view );
		// Add the value which defines the view ( front- or backend) to the config-array.
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
