<?php

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
	 * The array holding the application keys.
	 *
	 * @since 0.1.0
	 * @acces private
	 * @var   array $keys
	 */
	private $keys;

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
	 * @var    object $Options
	 */
	private $Options;

	/**
	 * The reference to the localisation class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    object $Nicescroll_Localisation
	 */
	private $Nicescroll_Localisation;

	/**
	 * Initializes the public part of the plugin.
	 *
	 * @since 0.1.0
	 * @param array $keys
	 */
	public function __construct( $keys ) {

		$this->keys = $keys;

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

		$this->Options = new nsr_options( $this->get_keys() );

		// The class responsible for passing the configuration to this plugin's instance of Nicescroll.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nsr-nicescroll-localisation.php';

		$this->Nicescroll_Localisation = new nsr_nicescroll_localisation( $this->get_keys() );
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

		$options = get_option( $this->keys['option_group'] );

		if( !is_array( $options['frontend'] ) ) {

			$this->Options->seed_options( 'frontend' );
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

		// Here we check if a plugin called "cbParallax" is enabled and is set to preserve scrolling.
		// If so, we bail and let that plugin handle the scrolling behaviour on the frontend for consistent results. Developer's choice.
		// So, then here we (may) get a transient from "cbParallax". If  true, no nicescroll js-library will be loaded by Nicescrollr.
		$is_superseeded_by_cb_parallax_preserve_scrolling = get_transient( 'cb_parallax_superseeds_nicescrollr_plugin_on_frontend' );
		delete_transient( 'cb_parallax_superseeds_nicescrollr_plugin_on_frontend' );
		if( isset( $is_superseeded_by_cb_parallax_preserve_scrolling ) && true === $is_superseeded_by_cb_parallax_preserve_scrolling ) {

			return;
		}

		$option = get_option( $this->keys['option_group'] );

		// We only enqueue these scripts if Nicescroll is enabled in the frontend.
		if( isset($option[ $this->view ]['enabled']) && $option[ $this->view ]['enabled'] ) {

			// Checks which "version" of nicescroll to load.
			if( isset($option[ $this->view ]['defaultScrollbar']) && $option[ $this->view ]['defaultScrollbar'] ) {

				// NSR Nicescroll library
				wp_enqueue_script(
					$this->keys['plugin_name'] . '-inc-nicescroll-min-js',
					plugin_dir_url( __FILE__ ) . '../vendor/nicescroll/jquery.nsr.nicescroll.min.js',
					array(
						'jquery',
					),
					$this->keys['plugin_version'],
					true
				);
			} else {
				// Nicescroll library
				wp_enqueue_script(
					$this->keys['plugin_name'] . '-inc-nicescroll-min-js',
					plugin_dir_url( __FILE__ ) . '../vendor/nicescroll/jquery.nicescroll.min.js',
					array(
						'jquery',
					),
					$this->keys['plugin_version'],
					true
				);
			}

			// Nicescroll configuration file
			wp_enqueue_script(
				$this->keys['plugin_name'] . '-nicescroll-js',
				plugin_dir_url( __FILE__ ) . '../js/nicescroll.js',
				array(
					'jquery',
					$this->keys['plugin_name'] . '-inc-nicescroll-min-js',
				),
				$this->keys['plugin_version'],
				true
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
		$option = get_option( $this->keys['option_group'] );

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

		$this->Nicescroll_Localisation->run( $this->view );
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

}
