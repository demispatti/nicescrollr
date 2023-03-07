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
 * The class responsible for localizing the Nicescroll configuration file.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/includes
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_Nicescroll_Localisation {

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
	 * @var    AdminIncludes\Nsr_Options $Options
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
     * Nsr_Nicescroll_Localisation constructor.
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

		$this->localize_nicescroll( $view );
	}

    /**
     * Retrieves the options per requested view from the database
     * and removes the determined prefix so that
     * the option keys correspond to the naming conventions of the Nicescroll library.
     * it contains a fallback to prevent "undefined"-errors in the script that's to be localized.
     *
     * @param string $view
     *
     * @return array
     * @since  0.1.0
     *
     */
	public function get_nicescroll_configuration($view)
    {

		$configuration = (array) $this->Options->get_options( $view );

		// Add the value which defines the view ( front- or backend) to the config-array.
		$configuration['view'] = $view;

		return $configuration;
	}

    /**
     * Localzes the Nicescroll instance.
     * @param string $view
     *
     * @return void
     * @since  0.1.0
     *
     */
	private function localize_nicescroll($view) {

		wp_localize_script( 'nicescrollr-nicescroll-js', 'Nsr_Options', $this->get_nicescroll_configuration( $view ) );
	}

}
