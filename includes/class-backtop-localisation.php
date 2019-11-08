<?php

namespace Nicescrollr\Includes;

use Nicescrollr\Admin\Menu\Includes as MenuIncludes;

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Include dependencies.
 */
if( ! class_exists( 'MenuIncludes\Nsr_Options' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'admin/menu/includes/class-options.php';
}

/**
 * The class responsible for localizing the backtop script.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/includes
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_Backtop_Localisation {

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
	 * @var    MenuIncludes\nsr_Options $Options
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
	 * Nsr_Backtop_Localisation constructor.
	 *
	 * @param $domain
	 * @param MenuIncludes\Nsr_Options $Options
	 */
	public function __construct( $domain, $Options ) {

		$this->domain = $domain;
		$this->Options = $Options;
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

		wp_localize_script( 'nicescrollr-backtop-js', 'Nsr_Options', $this->get_backtop_configuration( $view ) );
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

		$configuration = (array) $this->Options->get_options( $view );
		// Add the value which defines the view ( front- or backend) to the config-array.
		$configuration['view'] = $view;

		foreach( $configuration as $key => $value ) {
			if( 'false' === $value ) {
				$configuration[$key] = '0';
			}
			if( 'true' === $value ) {
				$configuration[$key] = '1';
			}
		}

		return $configuration;
	}

}
