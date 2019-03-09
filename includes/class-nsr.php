<?php

/**
 * The file that defines the core plugin class.
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
		// The class responsible for defining internationalization functionality of the plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nsr-i18n.php';

		// The class responsible for defining all actions that occur in the admin area.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-nsr-admin.php';

		// The class responsible for defining all actions that occur in the public-facing side of the site.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-nsr-public.php';
	}

	/**
	 * Creates an instance and registers all hooks related to the admin part.
	 *
	 * @since  0.1.0
	 * @see    admin/class-nsr-admin.php
	 * @access private
	 */
	private function define_admin_hooks() {

		$Admin = new nsr_admin( $this->get_domain() );

		add_action( 'admin_enqueue_scripts', array( $Admin, 'enqueue_scripts' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $Admin, 'initialize_localisation' ), 100 );
		add_filter( 'plugin_row_meta', array( $Admin, 'plugin_row_meta' ), 10, 2 );
	}

	/**
	 * Creates an instance and registers all hooks related to the public part.
	 *
	 * @since  0.1.0
	 * @see    public/class-nsr-public.php
	 * @access private
	 */
	private function define_public_hooks() {

		$Public = new nsr_public( $this->get_domain() );

		add_action( 'wp_enqueue_scripts', array( $Public, 'enqueue_scripts' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $Public, 'initialize_localisation' ), 21 );
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
