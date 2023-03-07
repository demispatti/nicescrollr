<?php
namespace Nicescrollr\Includes;

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define the internationalization functionality.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/includes
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_I18n {

	/**
	 * The name of the domain.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $domain
	 */
	private $domain;

    /**
     * Bonaire_i18n constructor.
     *
     * @param string $domain
     *
     * @since 1.0.0
     */
	public function __construct($domain) {

		$this->domain = $domain;
	}

	/**
	 * Registers the methods that need to be hooked with WordPress.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function add_hooks() {

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Loads the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( $this->domain, false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
	}

}
