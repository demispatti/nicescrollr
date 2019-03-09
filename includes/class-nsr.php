<?php

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The file that defines the core plugin class.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/includes
 * Author:            demispatti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr {

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
	 * Defines the core functionality of the plugin.
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function __construct() {

		$this->domain = 'nicescrollr';

		$this->load_dependencies();
		$this->set_locale();
	}

	/**
	 * Loads it's dependencies.
	 *
	 * @since  0.1.0
	 * @access private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( __DIR__ ) . 'includes/class-nsr-i18n.php';

		require_once plugin_dir_path( __DIR__ ) . 'admin/class-nsr-admin.php';

		require_once plugin_dir_path( __DIR__ ) . 'public/class-nsr-public.php';
	}

	/**
	 * Defines the locale for this plugin.
	 *
	 * @since  0.1.0
	 * @uses   set_plugin_domain()
	 * @see    includes/class-nsr-i18n.php
	 * @access private
	 */
	private function set_locale() {

		$Plugin_i18n = new nsr_i18n();
		$Plugin_i18n->set_domain( $this->domain );

		add_action( 'plugins_loaded', array( $Plugin_i18n, 'load_plugin_textdomain' ) );
	}

	/**
	 * Creates an instance and registers all hooks related to the admin part.
	 *
	 * @since  0.1.0
	 * @see    admin/class-nsr-admin.php
	 * @access private
	 */
	private function define_admin_hooks() {

		$admin = new nsr_admin( $this->get_domain() );
		$admin->add_hooks();
	}

	/**
	 * Creates an instance and registers all hooks related to the public part.
	 *
	 * @since  0.1.0
	 * @see    public/class-nsr-public.php
	 * @access private
	 */
	private function define_public_hooks() {

		$public = new nsr_public( $this->get_domain() );
		$public->add_hooks();
	}

	/**
	 * Runs the loader to execute all registered hooks with WordPress.
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function run() {

		$this->define_admin_hooks();
		$this->define_public_hooks();
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
