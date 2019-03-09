<?php

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_admin {

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
	 * The name of this view.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $view
	 */
	private $view = 'backend';

	/**
	 * The reference to the Nicescroll localisation class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    object $nicescroll_localisation
	 */
	private $nicescroll_localisation;

	/**
	 * Initializes the admin part of the plugin.
	 *
	 * @since 0.1.0
	 * @param $app
	 * @param $Loader
	 */
	public function __construct( $domain ) {

		$this->domain = $domain;

		$this->load_dependencies();
		$this->initialize_settings_menu();
		$this->initialize_help_tab();
	}

	public function add_hooks() {

		add_action( 'upgrader_process_complete', array( $this, 'upgrade' ), 20 );
	}

	/**
	 * Loads it's dependencies.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {
		// The classes that passes the settings to Nicescroll.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "includes/class-nsr-nicescroll-localisation.php";

		// The class that defines the help tab.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/includes/class-nsr-help-tab.php";

		// The classes that defines the settings menu.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/menu/class-nsr-menu.php";

		$this->nicescroll_localisation = new nsr_nicescroll_localisation( $this->get_domain() );
	}

	/**
	 * Registers the scripts for the admin area.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function enqueue_scripts() {

		// Gets executed if Nicescroll is enabled in the frontend.
		$option = get_option( 'nicescrollr_options' );

		if( isset($option[ $this->view ]['enabled']) && $option[ $this->view ]['enabled'] ) {

			// jQuery easing
			wp_enqueue_script(
				'nicescrollr-cb-parallax-easing-min-js',
				plugin_dir_url( __FILE__ ) . '../vendor/jquery-easing/jquery.easing.min.js',
				array( 'jquery' ),
				'all',
				false
			);

			// Nicescroll library
			wp_enqueue_script(
				'nicescrollr-inc-nicescroll-min-js',
				plugin_dir_url( __FILE__ ) . '../vendor/nicescroll/jquery.nicescroll.min.js',
				array(
					'jquery',
					'nicescrollr-cb-parallax-easing-min-js'
				),
				'all',
				true
			);

			// Nicescroll configuration file
			wp_enqueue_script(
				'nicescrollr-nicescroll-js',
				plugin_dir_url( __FILE__ ) . '../js/nicescroll.js',
				array(
					'jquery',
					'nicescrollr-inc-nicescroll-min-js',
				),
				'all',
				true
			);
		}
	}

	/**
	 * Registers all necessary hooks and instanciates all necessary objects the settings menu is made of.
	 *
	 * @since  0.1.0
	 * @see    admin/menu/class-nsr-menu.php
	 * @access private
	 * @return void
	 */
	public function initialize_settings_menu() {

		// Creates an instance of the "settings menu class" and registers the hooks that will be executed on it.
		$Menu = new nsr_menu( $this->get_domain() );

		add_action( 'admin_menu', array( $Menu, 'add_options_page' ), 20 );

		if( isset($_REQUEST['page']) && $_REQUEST['page'] !== 'nicescrollr_settings' ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', array( $Menu, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $Menu, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $Menu, 'initialize_localisation' ), 100 );
		add_action( 'admin_notices', array( $Menu, 'admin_notice_display' ) );
		add_action( 'admin_menu', array( $Menu, 'set_section' ), 10 );
		add_action( 'admin_menu', array( $Menu, 'initialize_settings_section' ), 40 );

		add_action( 'wp_ajax_reset_options', array( $Menu, 'reset_options' ) );
	}

	/**
	 * Creates a reference to the "help tab class" and hooks the initial function with WordPress.
	 *
	 * @since  0.1.0
	 * @see    admin/includes/class-nsr-help-tab.php
	 * @access private
	 * @return void
	 */
	private function initialize_help_tab() {

		if( isset($_REQUEST['page']) && $_REQUEST['page'] !== 'nicescrollr_settings' ) {
			return;
		}

		$Help_Tab = new nsr_help_tab( $this->get_domain() );

		add_action( 'in_admin_header', array( $Help_Tab, 'add_nsr_help_tab' ), 15 );
	}

	/**
	 * Initiates localisation of the scripts.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @see    js/nicescroll.js
	 * @return void
	 */
	public function initialize_localisation() {

		// Gets executed if Nicescroll is enabled in the frontend.
		$option = get_option( 'nicescrollr_options' );

		if( isset($option[ $this->view ]['enabled']) && $option[ $this->view ]['enabled'] ) {

			$this->localize_nicescroll();
		}
	}

	/**
	 * Initiates the localisation of the frontend view.
	 *
	 * @since  0.1.0
	 * @uses   run()
	 * @see    includes/class-nsr-nicescroll-localisation.php
	 * @access private
	 * @return void
	 */
	private function localize_nicescroll() {

		$this->nicescroll_localisation->run( $this->view );
	}

	/**
	 * Adds support, rating, and donation links to the plugin row meta on the plugins admin screen.
	 *
	 * @since  0.1.0
	 * @param  array  $meta
	 * @param  string $file
	 * @return array  $meta
	 */
	public function plugin_row_meta( $meta, $file ) {

		$plugin = plugin_basename( 'nicescrollr/nsr.php' );

		if( $file == $plugin ) {
			$meta[] = '<a href="https://wordpress.org/support/plugin/nicescrollr" target="_blank">' . __( 'Plugin support', $this->domain ) . '</a>';
			$meta[] = '<a href="https://wordpress.org/support/view/plugin-reviews/nicescrollr" target="_blank">' . __( 'Rate plugin', $this->domain ) . '</a>';
			$meta[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XLMMS7C62S76Q" target="_blank">' . __( 'Donate', $this->domain ) . '</a>';
		}

		return $meta;
	}

	/**
	 * Calls the class responsible for any eventual upgrade-related functions.
	 *
	 * @hooked_action
	 *
	 * @since    0.6.0
	 * @access   public
	 */
	public function upgrade() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nsr-upgrade.php';

		$upgrader = new nsr_upgrade();

		$upgrader->run();
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
