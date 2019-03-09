<?php

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
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
	 * @var    object $nicescroll_localisation
	 */
	private $nicescroll_localisation;

	/**
	 * Initializes the public part of the plugin.
	 *
	 * @since 0.1.0
	 * @param array $app
	 */
	public function __construct( $domain ) {

		$this->domain = $domain;

		$this->load_dependencies();
		$this->check_for_options();
	}

	/**
	 * Loads it's dependencies.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {

		// The class that holds all plugin-related data.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/menu/includes/class-nsr-options.php";
		$this->options = new nsr_options( $this->get_domain() );

		// The class responsible for passing the configuration to this plugin's instance of Nicescroll.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nsr-nicescroll-localisation.php';
		$this->nicescroll_localisation = new nsr_nicescroll_localisation( $this->get_domain() );
	}

	/**
	 * Checks for options in the database and seeds the default values for the frontend if the options group should be empty.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @uses   seed_default_options()
	 * @see    admin/menu/includes/class-nsr-options.php
	 * @return void
	 */
	public function check_for_options() {

		$options = get_option( 'nicescrollr_options' );

		if( !is_array( $options['frontend'] ) ) {

			$this->options->seed_options( 'frontend' );
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


		$option = get_option( 'nicescrollr_options' );

		// We only enqueue these scripts if Nicescroll is enabled in the frontend.
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
				false
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
				false
			);
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

		// Gets executed if Nicescroll is enabled in the frontend.
		$option = get_option( 'nicescrollr_options' );

		if( isset($option[ $this->view ]['enabled']) && $option[ $this->view ]['enabled'] ) {

			$this->localize_view();
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
	private function localize_view() {

		$this->nicescroll_localisation->run( $this->view );
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
