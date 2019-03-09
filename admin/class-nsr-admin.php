<?php

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
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
	 * The reference to the options class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    nsr_options $options
	 */
	private $options;

	/**
	 * The reference to the Nicescroll localisation class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    nsr_nicescroll_localisation $nicescroll_localisation
	 */
	private $nicescroll_localisation;

	/**
	 * The reference to the localisation class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    nsr_backtop_localisation $backtop_localisation
	 */
	private $backtop_localisation;

	/**
	 * The array that holds the stored option.
	 *
	 * @since  0.5.2
	 * @access private
	 * @var    array $settings
	 */
	private $settings;

	/**
	 * Initializes the admin part of the plugin.
	 *
	 * @since 0.1.0
	 *
	 * @param $app
	 * @param $Loader
	 */
	public function __construct( $domain ) {

		$this->domain = $domain;

		$this->load_dependencies();

		$this->settings = $this->options->get_options();

		$this->initialize_menu();
		$this->initialize_help_tab();
	}

	/**
	 * Register the hooks with WordPress.
	 *
	 * @since  0.5.2
	 *
	 * @return void
	 */
	public function add_hooks() {

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'initialize_localisations' ), 100 );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

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

		require_once plugin_dir_path( __DIR__ ) . 'includes/class-nsr-nicescroll-localisation.php';
		$this->nicescroll_localisation = new nsr_nicescroll_localisation( $this->get_domain() );

		require_once plugin_dir_path( __DIR__ ) . 'includes/class-nsr-backtop-localisation.php';
		$this->backtop_localisation = new nsr_backtop_localisation( $this->get_domain() );

		require_once plugin_dir_path( __DIR__ ) . 'admin/includes/class-nsr-help-tab.php';

		require_once plugin_dir_path( __DIR__ ) . 'admin/menu/class-nsr-menu.php';

		require_once plugin_dir_path( __DIR__ ) . 'admin/menu/includes/class-nsr-options.php';
		$this->options = new nsr_options( $this->get_domain() );
	}

	/**
	 * Registers the st<les for the admin menu.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function enqueue_styles() {

		if( isset( $this->settings['backend']['bt_enabled'] ) && $this->settings['backend']['bt_enabled'] ) {

			wp_enqueue_style( 'nicescrollr-backtop-css', plugin_dir_url( __FILE__ ) . '../assets/backtop.css', array(), 'all' );
		}
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
		if( isset( $this->settings[$this->view]['enabled'] ) && $this->settings[$this->view]['enabled'] ) {

			// jQuery Easing
			$easing_url = 'https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js';
			$easing_cdn = wp_remote_get( $easing_url );
			if( (int) wp_remote_retrieve_response_code( $easing_cdn ) !== 200 ) {

				$easing_url = plugin_dir_url( __FILE__ ) . '../vendor/jquery-easing/jquery.easing.min.js';
			}
			wp_enqueue_script( 'nicescrollr-easing-min-js', $easing_url, array( 'jquery' ), 'all' );

			// Nicescroll Library
			$nice_url = 'https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js';
			$nice_cdn = wp_remote_get( $nice_url );
			if( (int) wp_remote_retrieve_response_code( $nice_cdn ) !== 200 ) {

				$nice_url = plugin_dir_url( __FILE__ ) . '../vendor/nicescroll/jquery.nicescroll.min.js';
			}
			wp_enqueue_script( 'nicescrollr-inc-nicescroll-min-js', $nice_url, array(
				'jquery',
				'nicescrollr-easing-min-js'
			), 'all' );

			// Nicescroll configuration file
			wp_enqueue_script( 'nicescrollr-nicescroll-js', plugin_dir_url( __FILE__ ) . '../assets/nicescroll.js', array(
					'jquery',
					'nicescrollr-inc-nicescroll-min-js',
				), 'all' );
		}

		if( isset( $this->settings['backend']['bt_enabled'] ) && $this->settings['backend']['bt_enabled'] ) {

			// Backtop
			wp_enqueue_script( 'nicescrollr-backtop-js', plugin_dir_url( __FILE__ ) . '../assets/backtop.js', array(
					'jquery',
				), 'all' );
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
	public function initialize_menu() {

		$menu = new nsr_menu( $this->get_domain() );
		// We need to hook this action already here
		add_action( 'wp_ajax_reset_options', array( $menu, 'reset_options' ) );
		$menu->add_hooks();
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

		if( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'nicescrollr_settings' ) {

			$help_Tab = new nsr_help_tab( $this->get_domain() );
			$help_Tab->add_hooks();
		}
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
	public function initialize_localisations() {

		if( isset( $this->settings[$this->view]['enabled'] ) && $this->settings[$this->view]['enabled'] ) {
			$this->localize_nicescroll();
		}
		if( isset( $this->settings[$this->view]['bt_enabled'] ) && $this->settings[$this->view]['bt_enabled'] ) {
			$this->localize_backtop();
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

	private function localize_backtop() {

		$this->backtop_localisation->run( $this->view );
	}

	/**
	 * Adds support, rating, and donation links to the plugin row meta on the plugins admin screen.
	 *
	 * @since  0.1.0
	 *
	 * @param  array $meta
	 * @param  string $file
	 *
	 * @return array  $meta
	 */
	public function plugin_row_meta( $meta, $file ) {

		$plugin = plugin_basename( 'nicescrollr/nsr.php' );

		if( $file === $plugin ) {
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

		require_once plugin_dir_path( __DIR__ ) . 'includes/class-nsr-upgrade.php';

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
