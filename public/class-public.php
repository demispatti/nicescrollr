<?php

namespace Nicescrollr\Pub;

use Nicescrollr\Includes as Includes;
use Nicescrollr\Admin\Menu\Includes as MenuIncludes;

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Include dependencies.
 */
if( ! class_exists( 'Includes\Nsr_Nicescroll_Localisation' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'includes/class-nicescroll-localisation.php';
}
if( ! class_exists( 'Includes\Nsr_Backtop_Localisation' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'includes/class-backtop-localisation.php';
}
if( ! class_exists( 'MenuIncludes\Nsr_Options' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'admin/menu/includes/class-options.php';
}

/**
 * The public-specific functionality of the plugin.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/public
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_Public {

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
	 * @var    MenuIncludes\Nsr_Options $Options
	 */
	private $Options;

	/**
	 * The reference to the localisation class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    Includes\Nsr_Nicescroll_Localisation $Nicescroll_Localisation
	 */
	private $Nicescroll_Localisation;

	/**
	 * The reference to the localisation class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    Includes\Nsr_Backtop_Localisation $Backtop_Localisation
	 */
	private $Backtop_Localisation;

	/**
	 * The array that holds the stored option.
	 *
	 * @since  0.5.2
	 * @access private
	 * @var    array $settings
	 */
	private $settings;

	/**
	 * The string that holds the '-min' prefix for the js and css file handles.
	 *
	 * @since  0.7.0
	 * @access private
	 * @var    string $handle_prefix
	 */
	private $handle_prefix;

	/**
	 * The string that holds the '.min' prefix for the js and css file paths.
	 *
	 * @since  0.7.0
	 * @access private
	 * @var    string $file_prefix
	 */
	private $file_prefix;

	private function set_prefixes() {

		$this->handle_prefix = defined( 'NICESCROLLR_DEBUG' ) ? '' : '-min';
		$this->file_prefix = defined( 'NICESCROLLR_DEBUG' ) ? '' : '.min';
	}

	/**
	 * Nsr_Public constructor.
	 *
	 * @param $domain
	 * @param MenuIncludes\Nsr_Options $Options
	 * @param Includes\Nsr_Nicescroll_Localisation $Nicescroll_Localisation
	 * @param Includes\Nsr_Backtop_Localisation $Backtop_Localisation
	 */
	public function __construct( $domain, $Options, $Nicescroll_Localisation, $Backtop_Localisation ) {

		$this->domain = $domain;
		$this->Options =$Options;
		$this->Nicescroll_Localisation = $Nicescroll_Localisation;
		$this->Backtop_Localisation = $Backtop_Localisation;

		$this->set_prefixes();

		$this->settings = $this->Options->get_options();
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
	 * Registers the styles for the admin menu.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function enqueue_styles() {

		$handle_prefix = $this->handle_prefix;
		$file_prefix = $this->file_prefix;

		if( isset( $this->settings['frontend']['bt_enabled'] ) && $this->settings['frontend']['bt_enabled'] ) {

			wp_enqueue_style( 'nicescrollr-backtop' . $handle_prefix . '-css', NICESCROLLR_ROOT_URL . 'assets/backtop' . $file_prefix . '.css', array(), 'all' );
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

		$handle_prefix = $this->handle_prefix;
		$file_prefix = $this->file_prefix;

		// We only enqueue these scripts if Nicescroll is enabled in the frontend.
		if( isset( $this->settings[$this->view]['enabled'] ) && $this->settings[$this->view]['enabled'] ) {

			// jQuery Easing
			$easing_url = 'https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js';
			$easing_cdn = wp_remote_get( $easing_url );
			if( (int) wp_remote_retrieve_response_code( $easing_cdn ) !== 200 ) {
				$easing_url = NICESCROLLR_ROOT_URL . 'vendor/jquery-easing/jquery.easing.min.js';
			}
			wp_enqueue_script( 'nicescrollr-easing-min-js',
				$easing_url,
				array(
					'jquery'
				),
				'all'
			);

			// Nicescroll Library
			$nice_url = 'https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js';
			$nice_cdn = wp_remote_get( $nice_url );
			if( (int) wp_remote_retrieve_response_code( $nice_cdn ) !== 200 ) {
				$nice_url = NICESCROLLR_ROOT_URL . 'vendor/nicescroll/jquery.nicescroll.min.js';
			}
			wp_enqueue_script( 'nicescrollr-inc-nicescroll-min-js',
				$nice_url,
				array(
					'jquery',
					'nicescrollr-easing-min-js'
				),
				'all'
			);

			// Nicescroll Configuration File
			wp_enqueue_script( 'nicescrollr-nicescroll' . $handle_prefix . '-js',
				NICESCROLLR_ROOT_URL . 'assets/nicescroll' . $file_prefix . '.js',
				array(
					'jquery',
					'nicescrollr-inc-nicescroll-min-js',
				),
				'all'
			);
		}

		if( isset( $this->settings['frontend']['bt_enabled'] ) && $this->settings['frontend']['bt_enabled'] ) {

			// Backtop
			wp_enqueue_script( 'nicescrollr-backtop' . $handle_prefix . '-js',
				NICESCROLLR_ROOT_URL . 'assets/backtop' . $file_prefix . '.js',
				array(
					'jquery',
					'nicescrollr-nicescroll-min-js'
				),
				'all'
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
	 * @see    includes/class-Nsr-nicescroll-localisation.php
	 * @access private
	 * @return void
	 */
	private function localize_nicescroll() {

		$this->Nicescroll_Localisation->run( $this->view );
	}

	private function localize_backtop() {

		$this->Backtop_Localisation->run( $this->view );
	}

}