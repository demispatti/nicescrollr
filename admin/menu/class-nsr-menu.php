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
	 * @var    object $options
	 */
	private $options;

	/**
	 * The reference to the class that represents
	 * the "reset section" on the plugin options tab.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    object $reset_section
	 */
	private $reset_section;

	/**
	 * The reference to the class responsible for
	 * localizing the admin part of this plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    object $menu_localisation
	 */
	private $menu_localisation;

	/**
	 * The reference to the class responsible for
	 * the ajax functionality.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    object $ajax_localisation
	 */
	private $ajax_localisation;

	/**
	 * Sets the name of the section.
	 *
	 * @since  0.1.0
	 * @uses   $_REQUEST['tab'] on page load
	 * @uses   $_REQUEST['option_page'] on "save changes"-action
	 * @return void
	 */
	public function set_section() {

		$active_tab = NULL;
		$section = NULL;

		if( isset($_REQUEST['tab']) ) {

			$tab = $_REQUEST['tab'];

			if( 'plugin_options' == $tab ) {

				$this->section = 'plugin';
			} else if( 'backend_options' == $tab ) {

				$this->section = 'backend';
			} else {

				$this->section = 'frontend';
			}
		} else {

			$this->section = 'frontend';
		}

		if( isset($_REQUEST['option_page']) ) {

			$option_page = $_REQUEST['option_page'];

			if( 'nicescrollr_plugin_options' == $option_page ) {

				$this->section = 'plugin';
			} else if( 'nicescrollr_backend_options' == $option_page ) {

				$this->section = 'backend';
			}
		}
	}

	/**
	 * Assigns the required parameters, loads its dependencies and hooks the required actions.
	 *
	 * @since  0.1.0
	 * @param  array  $app
	 * @param  object $Loader
	 * @return mixed | void
	 */
	public function __construct( $domain ) {

		$this->domain = $domain;

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

		$this->reset_section     = new nsr_reset_section( $this->get_domain() );
		$this->options           = new nsr_options( $this->get_domain() );
		$this->menu_localisation = new nsr_menu_localisation( $this->get_domain() );
		$this->ajax_localisation = new nsr_ajax_localisation( $this->get_domain() );
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

		if( isset($hook_suffix) && $hook_suffix === 'settings_page_nicescrollr_settings' ) {

			if( !wp_style_is( 'color-picker.min.css' ) && !wp_style_is( 'color-picker.css' ) ) {

				// Color Picker
				wp_enqueue_style( 'wp-color-picker' );
			}

			if( !wp_style_is( 'alertify.core.min.css' ) && !wp_style_is( 'alertify.core.css' ) ) {

				// Alertify
				wp_enqueue_style(
					'nicescrollr-inc-alertify-core-css',
					plugin_dir_url( __FILE__ ) . '../../vendor/alertify/alertify.core.css',
					array(),
					'all',
					'all'
				);
			}

			// Icomoon
			wp_enqueue_style(
				'nicescrollr-icomoon-css',
				plugin_dir_url( __FILE__ ) . 'fonts/icomoon/style.css',
				array(),
				'all',
				'all'
			);

			// Menu
			wp_enqueue_style(
				'nicescrollr-menu-css',
				plugin_dir_url( __FILE__ ) . 'css/menu.css',
				array(),
				'all',
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

		if( isset($hook_suffix) && $hook_suffix === 'settings_page_nicescrollr_settings' ) {

			// Loads the file only if the user has the "backTop" option activated.
			$option = get_option( 'nicescrollr_options' );

			// Loads the file only if the user has the "scrollTo" option activated.
			if( isset($option['plugin']['scrollto_enabled']) && $option['plugin']['scrollto_enabled'] ) {

				// ScrollTo
				wp_enqueue_script(
					'nicescrollr-inc-scrollto-min-js',
					plugin_dir_url( __FILE__ ) . '../../vendor/scrollto/jquery.scrollTo.min.js',
					array( 'jquery' ),
					'all',
					false
				);
			}

			// Color Picker
			wp_enqueue_script( 'wp-color-picker' );

			// Fancy Select
			wp_enqueue_script(
				'nicescrollr-inc-fancy-select-js',
				plugin_dir_url( __FILE__ ) . '../../vendor/fancy-select/fancySelect.js',
				array( 'jquery' ),
				'all',
				false
			);

			// Loads only on the "plugin settings" tab.
			if( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'nicescrollr_settings' ) {

				// Alertify
				wp_enqueue_script(
					'nicescrollr-inc-alertify-js',
					plugin_dir_url( __FILE__ ) . '../../vendor/alertify/alertify.js',
					array( 'jquery' ),
					'all',
					false
				);

				// Ajax Reset Functionality
				wp_enqueue_script(
					'nicescrollr-ajax-js',
					plugin_dir_url( __FILE__ ) . 'js/ajax.js',
					array(
						'jquery',
						'nicescrollr' . '-inc-alertify-js',
					),
					'all',
					false
				);
			}

			// Settings Menu
			wp_enqueue_script(
				'nicescrollr-menu-js',
				plugin_dir_url( __FILE__ ) . 'js/menu.js',
				array(
					'jquery',
					'wp-color-picker',
					'nicescrollr' . '-inc-fancy-select-js',
					false !== $option['plugin']['scrollto_enabled'] ? 'nicescrollr' . '-inc-scrollto-min-js' : NULL,
				),
				'all',
				false
			);
		}
	}

	/**
	 * Localizes an ajax related script.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string $classes
	 */
	public function add_body_class( $classes ) {

		if ( isset($_REQUEST['page'] ) && $_REQUEST['page'] == 'nicescrollr_settings' ) {

			$ci     = 'nsr';
			$prefix = $ci . '-';

			$section = isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : '';

			$class = '';

			if( isset( $_REQUEST['tab'] ) ) {

				switch ( $section ) {
					case 'frontend_options':
						$class = 'frontend';
						break;
					case 'backend_options':
						$class = 'backend';
						break;
					case 'plugin_options':
						$class = 'plugin';
						break;
					default:
						$class = '';
				}
			}

			$classes .= ' ' . $prefix . 'settings-page' . ' ' . $class;
		}

		return $classes;
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

		if( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'nicescrollr_settings' ) {

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

		$this->menu_localisation->run();
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

		$this->ajax_localisation->run();
	}

	/**
	 * Loads the admin error notice and the components for the settings menu - if we're in the right spot.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function init() {

		add_action( 'admin_notices', array( $this, 'admin_notice_display' ) );

		add_action( 'admin_menu', array( $this, 'set_section' ), 10 );
		add_action( 'admin_menu', array( $this, 'add_options_page' ), 20 );
		add_action( 'admin_menu', array( $this, 'initialize_settings_section' ), 40 );

		add_action( 'wp_ajax_reset_options', array( $this, 'reset_options' ) );

		add_filter( "admin_body_class", array( $this, "add_body_class" ) );
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
			'nicescrollr_settings',
			array( $this, 'menu_display' )
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

		$Settings = new nsr_settings( $this->get_domain(), $this->get_section() );

		add_action( 'admin_init', array( $Settings, 'register_settings' ), 1 );
		add_action( 'admin_init', array( $Settings, 'check_for_options' ), 2 );
		add_action( 'admin_init', array( $Settings, 'load_default_options' ), 3 );
		add_action( 'admin_init', array( $Settings, 'initialize_options' ), 10 );
	}

	/**
	 * Renders the page for the menu.
	 *
	 * @since  0.1.0
	 * @uses   echo_section()
	 * @see    admin/menu/includes/class-nsr-reset-section.php
	 * @param  $active_tab
	 * @return void
	 */
	public function menu_display( $active_tab = '' ) {

		?>

		<div class="wrap">

			<h2 class="nsr-page-title"><?php echo __( 'Nicescrollr', $this->domain ); ?></h2>

			<form id="nsr_form" method="POST" action="options.php">

				<?php
				// Sets the active tab.
				if( isset($_GET['tab']) ) {

					$active_tab = $_GET['tab'];
				} else if( $active_tab == 'plugin_options' ) {

					$active_tab = 'plugin_options';
				} else if( $active_tab == 'backend_options' ) {

					$active_tab = 'backend_options';
				} else {

					$active_tab = 'frontend_options';
				} ?>

				<!-- Nav tab -->
				<div class="nav-tab-wrapper">
					<a href="?page=nicescrollr_settings&tab=frontend_options"
					   class="nav-tab <?php echo $active_tab == 'frontend_options' ? 'nav-tab-active' : ''; ?> icomoon icomoon-display"><?php _e( 'Frontend', $this->domain ); ?></a>
					<a href="?page=nicescrollr_settings&tab=backend_options"
					   class="nav-tab <?php echo $active_tab == 'backend_options' ? 'nav-tab-active' : ''; ?> icomoon icomoon-wordpress"><?php _e( 'Backend', $this->domain ); ?></a>
					<a href="?page=nicescrollr_settings&tab=plugin_options"
					   class="nav-tab <?php echo $active_tab == 'plugin_options' ? 'nav-tab-active' : ''; ?> icomoon icomoon-power-cord"><?php _e( 'Plugin', $this->domain ); ?></a>
				</div>

				<?php
				// Sets a hidden field containing the name of the settings section.
				if( $active_tab == 'plugin_options' ) {

					echo '<input type="hidden" name="section" value="plugin" />';
				} else if( $active_tab == 'backend_options' ) {

					echo '<input type="hidden" name="section" value="backend" />';
				} else {

					echo '<input type="hidden" name="section" value="frontend" />';
				} /**/ ?><!--

				--><?php
				// Settings fields.
				settings_fields( 'nicescrollr_options' );
				do_settings_sections( 'nicescrollr_settings' );

				// Reset buttons
				if( $active_tab == 'plugin_options' ) {

					$this->reset_section->echo_section();
				}

				submit_button();

				?>
			</form>

		</div>
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
	 * @return echo $html
	 */
	public function admin_notice_display() {

		// If there are any error-related transients
		if( false !== get_transient( 'nicescrollr_validation_transient' ) ) {

			// Retrieves the error-array and the corresponding meta data
			$errors = get_transient( 'nicescrollr_validation_transient' );

			// Outputs all eventual errors.
			foreach( $errors as $option_key => $error_meta ) {

				// Assigns the transient containing the error message to the array of notices.
				$notices[ $option_key ] = get_transient( $option_key );

				// Extracts the error message and echoes it inside the admin notice area.
				if( is_wp_error( $error_meta['message'] ) ) {

					$error_message = $error_meta['message']->get_error_message();

					$option = get_option( 'nicescrollr_options' );

					// scrollTo conditional
					if( isset($option['plugin']['scrollto_enabled']) && $option['plugin']['scrollto_enabled'] ) {

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
			delete_transient( 'nicescrollr_validation_transient' );
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
	 * @return void / array $response
	 */
	public function reset_options() {

		if( !wp_verify_nonce( $_REQUEST['nonce'], "reset_" . $_REQUEST['section'] . "_nonce" ) ) {
			exit(__( "One more try and your browser will burst into flames ;-)", $this->domain ));
		}

		if( isset($_REQUEST['section']) && $_REQUEST['section'] !== 'all' ) {

			$settings_section = $_REQUEST['section'];

			// Resets the requested section.
			if( true === $this->options->reset_settings( $settings_section ) ) {

				$response = array(
					'success' => __( "All done! Please refresh the page for the settings to take effect.", $this->domain ),
				);
				wp_send_json_success( $response );
			} else {

				$response = array(
					'success' => __( "Couldn't reset the settings. Please try again.", $this->domain ),
				);
				wp_send_json_error( $response );
			}
		} else {

			// Resets all sections.
			if( true === $this->options->reset_settings() ) {

				$response = array(
					'success' => __( "All done! Please refresh the page for the settings to take effect.", $this->domain ),
				);
				wp_send_json_success( $response );
			} else {

				$response = array(
					'success' => __( "Couldn't reset the settings. Please try again.", $this->domain ),
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

		return $this->options;
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
