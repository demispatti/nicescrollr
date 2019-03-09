<?php

namespace Nicescrollr\Admin;

use Nicescrollr\Includes as Includes;
use Nicescrollr\Admin\Includes as AdminIncludes;
use Nicescrollr\Admin\Menu as Menu;
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
if( ! class_exists( 'Includes\Nsr_Localisation' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'includes/class-nicescroll-localisation.php';
}
if( ! class_exists( 'Includes\Nsr_Localisation' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'includes/class-backtop-localisation.php';
}
if( ! class_exists( 'Includes\Nsr_Localisation' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'admin/includes/class-help-tab.php';
}
if( ! class_exists( 'Menu\Nsr_Menu' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'admin/menu/class-menu.php';
}
if( ! class_exists( 'MenuIncludes\Nsr_Options' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'admin/menu/includes/class-options.php';
}
if( ! class_exists( 'MenuIncludes\Nsr_Ajax_Localisation' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'admin/menu/includes/class-ajax-localisation.php';
}
if( ! class_exists( 'MenuIncludes\Nsr_Menu_Localisation' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'admin/menu/includes/class-menu-localisation.php';
}
if( ! class_exists( 'MenuIncludes\Nsr_Reset_Section' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'admin/menu/includes/class-reset-section.php';
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_Admin {

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
	 * @var    MenuIncludes\Nsr_Options $Options
	 */
	private $Options;

	/**
	 * The reference to the Nicescroll localisation class.
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
	 * Nsr_Admin constructor.
	 *
	 * @param string $domain
	 * @param MenuIncludes\Nsr_Options Nsr_Options $Options
	 * @param Includes\Nsr_Nicescroll_Localisation $Nicescroll_Localisation
	 * @param Includes\Nsr_Backtop_Localisation $Backtop_Localisation
	 */
	public function __construct( $domain, $Options, $Nicescroll_Localisation, $Backtop_Localisation ) {

		$this->domain = $domain;
		$this->Options = $Options;
		$this->Nicescroll_Localisation = $Nicescroll_Localisation;
		$this->Backtop_Localisation = $Backtop_Localisation;

		$this->settings = $this->Options->get_options();

		$this->set_prefixes();

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
		add_action( 'admin_enqueue_scripts', array( $this, 'initialize_localisations' ), 40 );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
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

		// Dashicons
		wp_enqueue_style( 'dashicons' );

		$handle_prefix = $this->handle_prefix;
		$file_prefix = $this->file_prefix;

		if( isset( $this->settings['backend']['bt_enabled'] ) && $this->settings['backend']['bt_enabled'] ) {

			wp_enqueue_style( 'nicescrollr-backtop' . $handle_prefix . '-css', NICESCROLLR_ROOT_URL . 'assets/backtop' . $file_prefix . '.css', array(), 'all' );
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

		$handle_prefix = $this->handle_prefix;
		$file_prefix = $this->file_prefix;

		// Gets executed if Nicescroll is enabled in the frontend.
		if( isset( $this->settings[$this->view]['enabled'] ) && $this->settings[$this->view]['enabled'] ) {

			// jQuery Easing
			wp_enqueue_script( 'nicescrollr-inc-easing-min-js', NICESCROLLR_ROOT_URL . 'vendor/jquery-easing/jquery.easing.min.js', array( 'jquery' ), 'all' );

			// Nicescroll Library
			$nice_url = 'https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js';
			$nice_cdn = wp_remote_get( $nice_url );
			if( (int) wp_remote_retrieve_response_code( $nice_cdn ) !== 200 ) {
				$nice_url = NICESCROLLR_ROOT_URL . 'vendor/nicescroll/jquery.nicescroll.min.js';
			}
			wp_enqueue_script( 'nicescrollr-inc-nicescroll-min-js', $nice_url, array( 'jquery', 'nicescrollr-inc-easing-min-js' ), 'all' );

			// Nicescroll configuration file
			wp_enqueue_script( 'nicescrollr-nicescroll' . $handle_prefix . '-js', NICESCROLLR_ROOT_URL . 'assets/nicescroll' . $file_prefix . '.js', array(
					'jquery',
					'nicescrollr-inc-nicescroll-min-js'
				), 'all' );
		}

		if( isset( $this->settings['backend']['bt_enabled'] ) && $this->settings['backend']['bt_enabled'] ) {

			// Backtop
			wp_enqueue_script( 'nicescrollr-backtop' . $handle_prefix . '-js', NICESCROLLR_ROOT_URL . 'assets/backtop' . $file_prefix . '.js', array( 'jquery' ), 'all' );
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

		$Menu_Localisation = new MenuIncludes\Nsr_Menu_Localisation( $this->domain, $this->Options );
		$Ajax_Localisation = new MenuIncludes\Nsr_Ajax_Localisation( $this->domain );
		$Reset_Section = new MenuIncludes\Nsr_Reset_Section( $this->domain );

		$menu = new Menu\Nsr_Menu( $this->domain, $this->Options, $Menu_Localisation, $Ajax_Localisation, $Reset_Section );
		// We need to hook this action already here
		add_action( 'wp_ajax_reset_options', array( $menu, 'MenuIncludes\reset_options' ) );
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

			$help_Tab = new AdminIncludes\Nsr_Help_Tab( $this->domain );
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

		$this->Nicescroll_Localisation->run( $this->view );
	}

	private function localize_backtop() {

		$this->Backtop_Localisation->run( $this->view );
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

}
