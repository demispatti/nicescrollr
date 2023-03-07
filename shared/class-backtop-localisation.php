<?php
namespace Nicescrollr\Shared;

use Nicescrollr\Admin\Includes as AdminIncludes;

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Include dependencies.
 */
if( ! class_exists( 'AdminIncludes\Nsr_Options' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'admin/includes/class-options.php';
}

/**
 * The class responsible for localizing the backtop script.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/shared
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
	 * @var    AdminIncludes\nsr_Options $Options
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
     * @param AdminIncludes\Nsr_Options $Options
     */
	public function __construct($domain, $Options) {

		$this->domain = $domain;
		$this->Options = $Options;
	}

    /**
     * Calls the function that passes the parameters to the Nicescroll library.
     *
     * @param string $view
     *
     * @return void
     * @since  0.1.0
     *
     */
	public function run($view) {

		$this->localize_backtop( $view );
	}

    /**
     * Localzes the backtop script.
     * @param string $view
     *
     * @return void
     * @since  0.1.0
     *
     */
	private function localize_backtop($view) {

		wp_localize_script( 'nicescrollr-backtop-js', 'Nsr_Options', array_merge( $this->get_backtop_configuration( $view ), array( 'nicescrollr_root_dir' =>
			NICESCROLLR_ROOT_URL ) ) );
	}

    /**
     * Retrieves the options for the backtop functionality.
     *
     * @param string $view
     *
     * @return array
     * @since  0.1.0
     *
     */
	public function get_backtop_configuration($view)
    {

		$configuration = (array) $this->Options->get_options( $view );

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
