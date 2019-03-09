<?php

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The public-specific functionality of the plugin.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/public
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_public {

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
	private $view = 'frontend';

	/**
	 * The reference to the options class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    object $options
	 */
	private $options;

	/**
	 * The reference to the localisation class.
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
	 * Initializes the public part of the plugin.
	 *
	 * @since 0.1.0
	 *
	 * @param array $app
	 */
	public function __construct( $domain ) {

		$this->domain = $domain;

		$this->load_dependencies();

		$this->settings = $this->options->get_options();
	}

	/**
	 * Register the hooks with WordPress.
	 *
	 * @since  0.5.2
	 *
	 * @return void
	 */
	public function add_hooks() {

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'initialize_localisation' ), 21 );
	}

	/**
	 * Loads it's dependencies.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( __DIR__ ) . 'admin/menu/includes/class-nsr-options.php';
		$this->options = new nsr_options( $this->get_domain() );

		require_once plugin_dir_path( __DIR__ ) . 'includes/class-nsr-nicescroll-localisation.php';
		$this->nicescroll_localisation = new nsr_nicescroll_localisation( $this->get_domain() );

		require_once plugin_dir_path( __DIR__ ) . 'includes/class-nsr-backtop-localisation.php';
		$this->backtop_localisation = new nsr_backtop_localisation( $this->get_domain() );
	}

	/**
	 * Registers the styles for the admin menu.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function enqueue_styles() {

		if( isset( $this->settings['frontend']['bt_enabled'] ) && $this->settings['frontend']['bt_enabled'] ) {

			wp_enqueue_style( 'nicescrollr-backtop-css', plugin_dir_url( __FILE__ ) . '../assets/backtop.css', array(), 'all' );
		}
	}

	/**
	 * Registers the scripts for the public area.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function enqueue_scripts() {

		// We only enqueue these scripts if Nicescroll is enabled in the frontend.
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

			// Nicescroll Configuration File
			wp_enqueue_script( 'nicescrollr-nicescroll-js', plugin_dir_url( __FILE__ ) . '../assets/nicescroll.js', array(
					'jquery',
					'nicescrollr-inc-nicescroll-min-js',
				), 'all' );
		}

		if( isset( $this->settings['frontend']['bt_enabled'] ) && $this->settings['frontend']['bt_enabled'] ) {

			// Backtop
			wp_enqueue_script( 'nicescrollr-backtop-js', plugin_dir_url( __FILE__ ) . '../assets/backtop.js', array(
					'jquery',
				), 'all' );
		}
	}

	/**
	 * Initiates localisation of the frontend view.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function initialize_localisation() {

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
