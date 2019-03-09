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
	 * The array holding the application keys.
	 *
	 * @since 0.1.0
	 * @acces protected
	 * @var   array $keys
	 */
	protected $keys;

	/**
	 * The reference to the loader class.
	 *
	 * @since 0.1.0
	 * @acces private
	 * @var   object $Loader
	 */
	private $Loader;

	/**
	 * Sets the application keys.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function set_keys() {

		$Keys = new nsr_keys();

		$this->keys = $Keys->retrieve_keys();
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
		$Plugin_i18n->set_plugin_domain( $this->keys['plugin_domain'] );

		$this->Loader->add_action( 'plugins_loaded', $Plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Defines the core functionality of the plugin.
	 *
	 * @since  0.1.0
	 * @return mixed / void
	 */
	public function __construct() {

		$this->load_dependencies();
		$this->set_keys();
		$this->set_locale();

		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Loads it's dependencies.
	 *
	 * @since  0.1.0
	 * @access private
	 */
	private function load_dependencies() {

		// The class that holds the application keys.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nsr-keys.php';

		// The class responsible for orchestrating the actions and filters of the core plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nsr-loader.php';

		// The class responsible for defining internationalization functionality of the plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nsr-i18n.php';

		// The class responsible for defining all actions that occur in the admin area.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-nsr-admin.php';

		// The class responsible for defining all actions that occur in the public-facing side of the site.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-nsr-public.php';

		// The references
		$this->Loader = new nsr_loader();
	}

	/**
	 * Creates an instance and registers all hooks related to the admin part.
	 *
	 * @since  0.1.0
	 * @see    admin/class-nsr-admin.php
	 * @access private
	 */
	private function define_admin_hooks() {

		$Admin = new nsr_admin( $this->get_keys(), $this->get_loader() );

		$this->Loader->add_action( 'admin_enqueue_scripts', $Admin, 'enqueue_scripts', 20 );
		$this->Loader->add_action( 'admin_enqueue_scripts', $Admin, 'initialize_localisation', 100 );
		$this->Loader->add_action( 'plugin_row_meta', $Admin, 'plugin_row_meta', 10, 2 );
	}

	/**
	 * Creates an instance and registers all hooks related to the public part.
	 *
	 * @since  0.1.0
	 * @see    public/class-nsr-public.php
	 * @access private
	 */
	private function define_public_hooks() {

		$Public = new nsr_public( $this->get_keys() );

		$this->Loader->add_action( 'wp_enqueue_scripts', $Public, 'enqueue_scripts', 20 );
		$this->Loader->add_action( 'wp_enqueue_scripts', $Public, 'initialize_localisation', 21 );
	}

	/**
	 * Runs the loader to execute all registered hooks with WordPress.
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function run() {

		$this->Loader->run();
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

	/**
	 * Retrieves the reference to the loader class.
	 *
	 * @since  0.1.0
	 * @return object
	 */
	public function get_loader() {

		return $this->Loader;
	}

}
