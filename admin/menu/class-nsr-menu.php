<?php

/**
 * The class that manages the settings menu.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/menu
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_menu {

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
	 *  The name of the section.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $section
	 */
	private $section;

	/**
	 * The reference to the options class.
	 *
     * @since  0.1.0
	 * @access private
	 * @var    object $Options
	 */
	private $Options;

	/**
	 * The reference to the class that represents
	 * the "reset section" on the plugin options tab.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    object $Reset_Section
	 */
	private $Reset_Section;

	/**
	 * The reference to the class responsible for
	 * localizing the admin part of this plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    object $Menu_Localisation
	 */
	private $Menu_Localisation;

	/**
	 * The reference to the class responsible for
	 * the ajax functionality.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    object $Ajax_Localisation
	 */
	private $Ajax_Localisation;

	/**
	 * Sets the name of the section.
	 *
	 * @since  0.1.0
	 * @uses   $_REQUEST['tab'] on page load
	 * @uses   $_REQUEST['option_page'] on "save changes"-action
	 * @return void
	 */
	public function set_section() {

		$active_tab = null;
		$section    = null;

		if ( isset( $_REQUEST['tab'] ) ) {

			$tab = $_REQUEST['tab'];

			if ( 'plugin_options' == $tab ) {

				$this->section = 'plugin';
			} else if ( 'backend_options' == $tab ) {

				$this->section = 'backend';
			} else {

				$this->section = 'frontend';
			}
		} else {

			$this->section = 'frontend';
		}

		if ( isset( $_REQUEST['option_page'] ) ) {

			$option_page = $_REQUEST['option_page'];

			if ( 'nicescrollr_plugin_options' == $option_page ) {

				$this->section = 'plugin';
			} else if ( 'nicescrollr_backend_options' == $option_page ) {

				$this->section = 'backend';
			}
		}
	}

	/**
	 * Assigns the required parameters, loads its dependencies and hooks the required actions.
	 *
	 * @since  0.1.0
	 * @param  array $keys
	 * @param  object $Loader
	 * @return mixed | void
	 */
	public function __construct( $keys, $Loader ) {

		$this->keys   = $keys;
		$this->Loader = $Loader;

		$this->load_dependencies();
		$this->init();
	}

	/**
	 * Loads it's dependencies.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {

		// The class responsible for all tasks concerning the settings api.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "menu/includes/class-nsr-settings.php";

		// The class that maintains all data like default values and their meta data.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "menu/includes/class-nsr-options.php";

		// The class that defines the section with the reset buttons.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "menu/includes/class-nsr-reset-section.php";

		// The class responsible for localizing the admin script.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "menu/includes/class-nsr-menu-localisation.php";

		// The class responsible for localizing the ajax script.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "menu/includes/class-nsr-ajax-localisation.php";

		$this->Reset_Section     = new nsr_reset_section( $this->get_keys() );
		$this->Options           = new nsr_options( $this->get_keys() );
		$this->Menu_Localisation = new nsr_menu_localisation( $this->get_keys(), $this->get_plugin_options() );
		$this->Ajax_Localisation = new nsr_ajax_localisation( $this->get_keys() );
	}

	/**
	 * Registers the st<les for the admin menu.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function enqueue_styles( $hook_suffix ) {

		if ( isset( $hook_suffix ) && $hook_suffix === $this->keys['hook_suffix'] ) {

			if ( ! wp_style_is( 'color-picker.min.css' ) && ! wp_style_is( 'color-picker.css' ) ) {

				// Color Picker.
				wp_enqueue_style( 'wp-color-picker' );
			}

			if ( ! wp_style_is( 'alertify.core.min.css' ) && ! wp_style_is( 'alertify.core.css' ) ) {

				// Alertify
				wp_enqueue_style(
					$this->keys['plugin_name'] . '-alertify-core-css',
					plugin_dir_url( __FILE__ ) . '../../vendor/alertify/alertify.core.css',
					array(),
					$this->keys['plugin_version'],
					'all'
				);
			}

			// Fugaz One
			wp_enqueue_style(
				$this->keys['plugin_name'] . '-fugazone-css',
				plugin_dir_url( __FILE__ ) . 'fonts/fugazone/style.css',
				array(),
				$this->keys['plugin_version'],
				'all'
			);

			// Icomoon
			wp_enqueue_style(
				$this->keys['plugin_name'] . '-icomoon-css',
				plugin_dir_url( __FILE__ ) . 'fonts/icomoon/style.css',
				array(),
				$this->keys['plugin_version'],
				'all'
			);

			// Admin
			wp_enqueue_style(
				$this->keys['plugin_name'] . '-menu-css',
				plugin_dir_url( __FILE__ ) . 'css/menu.css',
				array(),
				$this->keys['plugin_version'],
				'all'
			);
		}
	}

	/**
	 * Registers the scripts for the admin menu.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function enqueue_scripts( $hook_suffix ) {

		if ( isset( $hook_suffix ) && $hook_suffix === $this->keys['hook_suffix'] ) {

			// Loads the file only if the user has the "backTop" option activated.
			$option = get_option( $this->keys['option_group'] );

			// Loads the file only if the user has the "backTop" option activated.
			if ( isset( $option['plugin']['backtop_enabled'] ) && $option['plugin']['backtop_enabled'] ) {

				// BackTop library.
				wp_enqueue_script(
					$this->keys['plugin_name'] . '-inc-backtop-min-js',
					plugin_dir_url( __FILE__ ) . '../../vendor/backtop/jquery.backTop.min.js',
					array( 'jquery' ),
					$this->keys['plugin_version'],
					false
				);
			}

			// Loads the file only if the user has the "scrollTo" option activated.
			if ( isset( $option['plugin']['scrollto_enabled'] ) && $option['plugin']['scrollto_enabled'] ) {

				// ScrollTo Library.
				wp_enqueue_script(
					$this->keys['plugin_name'] . '-inc-scrollto-min-js',
					plugin_dir_url( __FILE__ ) . '../../vendor/scrollto/jquery.scrollTo.min.js',
					array( 'jquery' ),
					$this->keys['plugin_version'],
					false
				);
			}

			// Color Picker.
			wp_enqueue_script( 'wp-color-picker' );

			// Fancy Select.
			wp_enqueue_script(
				$this->keys['plugin_name'] . '-inc-fancy-select-js',
				plugin_dir_url( __FILE__ ) . '../../vendor/fancy-select/fancySelect.js',
				array( 'jquery' ),
				$this->keys['plugin_version'],
				false
			);

			// Loads only on the "plugin settings" tab.
			if ( ( isset( $_REQUEST['tab'] ) && $_REQUEST['tab'] == 'plugin_options' ) && ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === $this->keys['settings_page'] ) ) {

				// Alertify.
				wp_enqueue_script(
					$this->keys['plugin_name'] . '-inc-alertify-js',
					plugin_dir_url( __FILE__ ) . '../../vendor/alertify/alertify.js',
					array( 'jquery' ),
					$this->keys['plugin_version'],
					false
				);

				// Ajax Reset Functionality.
				wp_enqueue_script(
					$this->keys['plugin_name'] . '-ajax-js',
					plugin_dir_url( __FILE__ ) . 'js/ajax.js',
					array(
						'jquery',
						$this->keys['plugin_name'] . '-inc-alertify-js'
					),
					$this->keys['plugin_version'],
					false
				);
			}
			// Settings Menu.
			wp_enqueue_script(
				$this->keys['plugin_name'] . '-menu-js',
				plugin_dir_url( __FILE__ ) . 'js/menu.js',
				array(
					'jquery',
					'wp-color-picker',
					$this->keys['plugin_name'] . '-inc-fancy-select-js',
					false !== $option['plugin']['backtop_enabled'] ? $this->keys['plugin_name'] . '-inc-backtop-min-js' : null,
					false !== $option['plugin']['scrollto_enabled'] ? $this->keys['plugin_name'] . '-inc-scrollto-min-js' : null
				),
				$this->keys['plugin_version'],
				false
			);
		}
	}

	/**
	 * Initiates localisation of the options page and the Ajax script if necessary.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function initialize_localisation() {

		$this->localize_menu();

		if ( ( isset( $_REQUEST['tab'] ) && $_REQUEST['tab'] == 'plugin_options' ) && ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === $this->keys['settings_page'] ) ) {

			$this->localize_ajax();
		}
	}

	/**
	 * Initiates the localisation of the admin part.
	 *
	 * @since  0.1.0
	 * @uses   run()
	 * @see    admin/menu/includes/class-nsr-menu-localisation.php
	 * @access private
	 * @return void
	 */
	private function localize_menu() {

		$this->Menu_Localisation->run();
	}

	/**
	 * Initiates the localisation of the ajax part.
	 *
	 * @since  0.1.0
	 * @uses   run()
	 * @see    admin/menu/includes/class-nsr-ajax-localisation.php
	 * @access private
	 * @return void
	 */
	private function localize_ajax() {

		$this->Ajax_Localisation->run();
	}

	/**
	 * Loads the admin error notice and the components for the settings menu - if we're in the right spot.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function init() {

		add_action( 'admin_notices', array( &$this, 'admin_notice_display' ) );

		add_action( 'admin_menu', array( &$this, 'set_section' ), 10 );
		add_action( 'admin_menu', array( &$this, 'add_options_page' ), 20 );
		add_action( 'admin_menu', array( &$this, 'initialize_settings_section' ), 40 );

		add_action( 'wp_ajax_reset_options', array( &$this, 'reset_options' ) );
	}

	/**
	 * Registers the settings page with WordPress.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function add_options_page() {

		add_options_page(
			'Nicescrollr',
			'Nicescrollr',
			'manage_options',
			$this->keys['settings_page'],
			array( &$this, 'menu_display' )
		);
	}

	/**
	 * Initializes the components for the settings section.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function initialize_settings_section() {

		$Settings = new nsr_settings( $this->get_keys(), $this->get_section() );

		$this->Loader->add_action( 'admin_init', $Settings, 'register_settings', 1 );
		$this->Loader->add_action( 'admin_init', $Settings, 'check_for_options', 2 );
		$this->Loader->add_action( 'admin_init', $Settings, 'load_default_options', 3 );
		$this->Loader->add_action( 'admin_init', $Settings, 'initialize_options', 10 );
	}

	/**
	 * Renders the page for the menu.
	 *
	 * @since  0.1.0
	 * @uses   echo_section()
	 * @see    admin/menu/includes/class-nsr-reset-section.php
	 * @param  $active_tab
	 * @return mixed
	 */
	public function menu_display( $active_tab = '' ) {

		?>

		<div class="wrap">
			<h2></h2>
			<!--<div id="icon-themes" class="icon32"></div>-->
			<h2 class="nsr-page-title"><?php echo __( 'Nicescrollr', $this->keys['plugin_domain'] ); ?></h2>

			<!--<h3 class="nsr-page-subtitle"><?php /*echo __( '...for a convenient user experience :)', $this->keys['plugin_domain'] ); */?></h3>-->

			<form id="nsr_form" method="POST" action="options.php">

				<?php
				// Sets the active tab.
				if ( isset( $_GET['tab'] ) ) {

					$active_tab = $_GET['tab'];
				} else if ( $active_tab == 'plugin_options' ) {

					$active_tab = 'plugin_options';
				} else if ( $active_tab == 'backend_options' ) {

					$active_tab = 'backend_options';
				} else {

					$active_tab = 'frontend_options';
				} ?>

				<!-- Submit button wrapper for styling purposes -->
				<!--<div class="dp-submit-button-wrap">
					<?php
/*						submit_button();
					*/?>
				</div>-->

				<!-- Nav tab -->
				<h2 class="nav-tab-wrapper">
					<a href="?page=nicescrollr_settings&tab=frontend_options"
					   class="nav-tab <?php echo $active_tab == 'frontend_options' ? 'nav-tab-active' : ''; ?> icomoon icon-home"><?php _e( 'Frontend', $this->keys['plugin_domain'] ); ?></a>
					<a href="?page=nicescrollr_settings&tab=backend_options"
					   class="nav-tab <?php echo $active_tab == 'backend_options' ? 'nav-tab-active' : ''; ?> icomoon icon-wordpress"><?php _e( 'Backend', $this->keys['plugin_domain'] ); ?></a>
					<a href="?page=nicescrollr_settings&tab=plugin_options"
					   class="nav-tab <?php echo $active_tab == 'plugin_options' ? 'nav-tab-active' : ''; ?> icomoon icon-wrench"><?php _e( 'Plugin', $this->keys['plugin_domain'] ); ?></a>
				</h2>

				<?php
				// Sets a hidden field containing the name of the settings section.
				if ( $active_tab == 'plugin_options' ) {

					echo '<input type="hidden" name="section" value="plugin" />';
				} else if ( $active_tab == 'backend_options' ) {

					echo '<input type="hidden" name="section" value="backend" />';
				} else {

					echo '<input type="hidden" name="section" value="frontend" />';
				} /**/?><!--

				--><?php
				// Settings fields.
				settings_fields( $this->keys['option_group'] );
				do_settings_sections( $this->keys['settings_group'] );

				// Reset buttons
				if ( $active_tab == 'plugin_options' ) {

					$this->Reset_Section->echo_section();
				}

				submit_button();

				?>
			</form>

		</div><!-- /.wrap -->
	<?php
	}

	/**
	 * Displays the validation errors in the admin notice area.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @uses   get_errors_meta_data()
	 * @see    admin/menu/includes/class-nsr-options.php
	 * @return echo
	 */
	public function admin_notice_display() {

		// If there are any error-related transients
		if ( false !== get_transient( $this->keys['validation_transient'] ) ) {

			// Retrieves the error-array and the corresponding meta data
			$errors = get_transient( $this->keys['validation_transient'] );

			// Outputs all eventual errors.
			foreach ( $errors as $option_key => $error_meta ) {

				// Assigns the transient containing the error message to the array of notices.
				$notices[ $option_key ] = get_transient( $option_key );

				// Extracts the error message and echoes it inside the admin notice area.
				if ( is_wp_error( $error_meta['message'] ) ) {

					$error_message = $error_meta['message']->get_error_message();

					$option = get_option( $this->keys['option_group'] );

					// scrollTo conditional
					if ( isset( $option['plugin']['scrollto_enabled'] ) && $option['plugin']['scrollto_enabled'] ) {

						$class = 'nsr-validation-error';
					} else {

						$class = 'nsr-validation-error-no-scrollto';
					}

					// Admin notice.
					$html = '<div class="error notice is-dismissible ' . $error_meta["notice_level"] . '">';
					$html .= '<p>';
					$html .= '<a class="' . $class . '" href="#' . $option_key . '" data-index="' . $error_meta["index"] . '">';
					$html .= $error_meta['name'];
					$html .= '</a>';
					$html .= '</p>';

					$html .= '<p>';
					$html .= $error_message;
					$html .= '</p>';
					$html .= '</div>';

					echo $html;
				}
			}
			// Clean up
			delete_transient( $this->keys['validation_transient'] );
		}
	}

	/**
	 * Retrieves a reset-settings-request, processes it and returns the response.
	 *
	 * @hooked_action
	 *
	 * @see    admin/menu/js/ajax.js
	 * @uses   reset_settings()
	 * @see    admin/menu/includes/class-nsr-options.php
	 * @since  0.1.0
	 * @access private
	 * @return ajax response
	 */
	public function reset_options() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], "reset_" . $_REQUEST['section'] . "_nonce" ) ) {
			exit( __( "One more try and your browser will burst into flames ;-)", $this->keys['plugin_domain'] ) );
		}

		if ( isset( $_REQUEST['section'] ) && $_REQUEST['section'] !== 'all' ) {

			$settings_section = $_REQUEST['section'];

			// Resets the requested section.
			if ( true === $this->Options->reset_settings( $settings_section ) ) {

				$response = array(
					'success' => __( "All done! Please refresh the page for the settings to take effect.", $this->keys['plugin_domain'] )
				);
				wp_send_json_success( $response );
			} else {

				$response = array(
					'success' => __( "Couldn't reset the settings. Please try again.", $this->keys['plugin_domain'] )
				);
				wp_send_json_error( $response );
			}

		} else {

			// Resets all sections.
			if ( true === $this->Options->reset_settings() ) {

				$response = array(
					'success' => __( "All done! Please refresh the page for the settings to take effect.", $this->keys['plugin_domain'] )
				);
				wp_send_json_success( $response );
			} else {

				$response = array(
					'success' => __( "Couldn't reset the settings. Please try again.", $this->keys['plugin_domain'] )
				);
				wp_send_json_error( $response );
			}
		}
	}

	/**
	 * Retrieves the reference to the "plugin data" class.
	 *
	 * @since  0.1.0
	 * @return object $Options
	 */
	public function get_plugin_options() {

		return $this->Options;
	}

	/**
	 * Retrieves the name of the section.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return string $section
	 */
	public function get_section() {

		return $this->section;
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
