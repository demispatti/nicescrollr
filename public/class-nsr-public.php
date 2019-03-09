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
	}

	/**
	 * Loads it's dependencies.
	 *
     * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {

		// The class responsible for passing the configuration to this plugin's instance of Nicescroll.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nsr-nicescroll-localisation.php';

		$this->Nicescroll_Localisation = new nsr_nicescroll_localisation( $this->get_keys() );
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

		$option = get_option( $this->keys['option_group'] );

		// We only enqueue these scripts if Nicescroll is enabled in the frontend.
		if ( isset( $option[ $this->view ]['enabled'] ) && $option[ $this->view ]['enabled'] ) {

			// Nicescroll library
			wp_enqueue_script(
				$this->keys['plugin_name'] . '-inc-nicescroll-min-js',
				plugin_dir_url( __FILE__ ) . '../vendor/nicescroll/jquery.nicescroll.min.js',
				array( 'jquery' ),
				$this->keys['plugin_version'],
				true
			);

			// The file containing our instance of Nicescroll.
			wp_enqueue_script(
				$this->keys['plugin_name'] . '-nicescroll-js',
				plugin_dir_url( __FILE__ ) . '../js/nicescroll.js',
				array(
					'jquery',
					$this->keys['plugin_name'] . '-inc-nicescroll-min-js'
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

		if ( isset( $option[ $this->view ]['enabled'] ) && $option[ $this->view ]['enabled'] ) {

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
