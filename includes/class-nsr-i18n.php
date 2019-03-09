<?php

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define the internationalization functionality.
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
class nsr_i18n {

	/**
	 * The name of the domain.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $domain
	 */
	private $domain;

	/**
	 * Sets the domain equal to that of the specified domain.
	 *
	 * @since 0.1.0
	 *
	 * @param string $domain
	 */
	public function set_domain( $domain ) {

		$this->domain = $domain;
	}

	/**
	 * Loads the plugin text domain for translation.
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( $this->domain, false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
	}

}
