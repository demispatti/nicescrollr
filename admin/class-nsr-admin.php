<?php

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
	 * The array holding the application keys.
	 *
	 * @since 0.1.0
	 * @acces private
	 * @var   array $keys
	 */
	private $keys;

	/**
	 * The reference to the loader class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    object $Loader
	 */
	private $Loader;

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
	 * @var    object $Nicescroll_Localisation
	 */
	private $Nicescroll_Localisation;

	/**
	 * Initializes the admin part of the plugin.
	 *
	 * @since 0.1.0
	 * @param $keys
	 * @param $Loader
	 */
	public function __construct( $keys, $Loader ) {

		$this->keys = $keys;
		$this->Loader = $Loader;

		$this->load_dependencies();
		$this->initialize_settings_menu();
		$this->initialize_help_tab();
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

		$this->Nicescroll_Localisation = new nsr_nicescroll_localisation( $this->get_keys() );
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

		$is_superseeded_by_cb_parallax_preserve_scrolling = false;//get_transient( 'cb_parallax_superseeds_nicescrollr_plugin_on_backend' ); //@todo idea maybe I'll see

		// Gets executed if Nicescroll is enabled in the frontend.
		$option = get_option( $this->keys['option_group'] );

		if( isset($option[ $this->view ]['enabled']) && $option[ $this->view ]['enabled'] ) {

			// Checks which "version" of nicescroll to load.
			if( isset($is_superseeded_by_cb_parallax_preserve_scrolling) && true === $is_superseeded_by_cb_parallax_preserve_scrolling ) {

				return;
			} else if( isset($option[ $this->view ]['defaultScrollbar']) && $option[ $this->view ]['defaultScrollbar'] ) {

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
	 * Registers all necessary hooks and instanciates all necessary objects the settings menu is made of.
	 *
	 * @since  0.1.0
	 * @see    admin/menu/class-nsr-menu.php
	 * @access private
	 * @return void
	 */
	public function initialize_settings_menu() {

		// Creates an instance of the "settings menu class" and registers the hooks that will be executed on it.
		$Menu = new nsr_menu( $this->get_keys(), $this->get_loader() );

		$this->Loader->add_action( 'admin_menu', $Menu, 'add_options_page', 20 );

		if( isset($_REQUEST['page']) && $_REQUEST['page'] !== $this->keys['settings_page'] ) {
			return;
		}

		$this->Loader->add_action( 'admin_enqueue_scripts', $Menu, 'enqueue_styles' );
		$this->Loader->add_action( 'admin_enqueue_scripts', $Menu, 'enqueue_scripts' );
		$this->Loader->add_action( 'admin_enqueue_scripts', $Menu, 'initialize_localisation', 100 );
		$this->Loader->add_action( 'admin_notices', $Menu, 'admin_notice_display' );
		$this->Loader->add_action( 'admin_menu', $Menu, 'set_section', 10 );
		$this->Loader->add_action( 'admin_menu', $Menu, 'initialize_settings_section', 40 );

		$this->Loader->add_action( 'wp_ajax_reset_options', $Menu, 'reset_options' );
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

		$Help_Tab = new nsr_help_tab( $this->keys );

		$this->Loader->add_action( 'in_admin_header', $Help_Tab, 'add_nsr_help_tab', 15 );
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
		$option = get_option( $this->keys['option_group'] );

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

		$this->Nicescroll_Localisation->run( $this->view );
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
			$meta[] = '<a href="https://wordpress.org/support/plugin/nicescrollr" target="_blank">' . __( 'Plugin support', $this->keys['plugin_domain'] ) . '</a>';
			$meta[] = '<a href="https://wordpress.org/support/view/plugin-reviews/nicescrollr" target="_blank">' . __( 'Rate plugin', $this->keys['plugin_domain'] ) . '</a>';
			$meta[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XLMMS7C62S76Q" target="_blank">' . __( 'Donate', $this->keys['plugin_domain'] ) . '</a>';
		}

		return $meta;
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
	 * @return object $Loader
	 */
	public function get_loader() {

		return $this->Loader;
	}

}
