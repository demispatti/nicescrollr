<?php

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

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
	 * @var    nsr_options $options
	 */
	private $options;

	/**
	 * The reference to the class that represents
	 * the "reset section" on the plugin options tab.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    nsr_reset_section $reset_section
	 */
	private $reset_section;

	/**
	 * The reference to the class responsible for
	 * localizing the admin part of this plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    nsr_menu_localisation $menu_localisation
	 */
	private $menu_localisation;

	/**
	 * The reference to the class responsible for
	 * the ajax functionality.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    nsr_ajax_localisation $ajax_localisation
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

		$active_tab = null;
		$section = null;

		if( isset( $_REQUEST['tab'] ) ) {

			$tab = $_REQUEST['tab'];

			$this->section = 'frontend';

			if( 'backend' === $tab ) {

				$this->section = 'backend';

			}
		}
		else {

			$this->section = 'frontend';
		}
	}

	/**
	 * Assigns the required parameters, loads its dependencies and hooks the required actions.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $domain
	 *
	 * @return void
	 */
	public function __construct( $domain ) {

		$this->domain = $domain;

		$this->load_dependencies();
	}

	/**
	 * Register the hooks with WordPress.
	 *
	 * @since  0.5.2
	 *
	 * @return void
	 */
	public function add_hooks() {

		add_action( 'admin_menu', array( $this, 'add_options_page' ), 20 );

		if( ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'nicescrollr_settings' ) || ( isset( $_REQUEST['option_page'] ) && $_REQUEST['option_page'] === 'nicescrollr_options' ) ) {

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'initialize_localisation' ), 100 );
			add_action( 'admin_notices', array( $this, 'admin_notice_display' ) );
			add_action( 'admin_menu', array( $this, 'set_section' ), 10 );
			add_action( 'admin_menu', array( $this, 'initialize_settings_section' ), 40 );

			add_filter( 'admin_body_class', array( $this, 'add_body_class' ) );
		}
	}

	/**
	 * Loads it's dependencies.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( __DIR__ ) . 'menu/includes/class-nsr-settings.php';

		require_once plugin_dir_path( __DIR__ ) . 'menu/includes/class-nsr-options.php';
		$this->options = new nsr_options( $this->get_domain() );

		require_once plugin_dir_path( __DIR__ ) . 'menu/includes/class-nsr-reset-section.php';
		$this->reset_section = new nsr_reset_section( $this->get_domain() );

		require_once plugin_dir_path( __DIR__ ) . 'menu/includes/class-nsr-menu-localisation.php';
		$this->menu_localisation = new nsr_menu_localisation( $this->get_domain() );

		require_once plugin_dir_path( __DIR__ ) . 'menu/includes/class-nsr-ajax-localisation.php';
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

		if( $hook_suffix === 'settings_page_nicescrollr_settings' ) {

			// Color Picker
			if( ! wp_style_is( 'color-picker.min.css' ) && ! wp_style_is( 'color-picker.css' ) ) {

				wp_enqueue_style( 'wp-color-picker' );
			}

			// Alertify.
			wp_enqueue_style( 'inc-alertify-min-css', plugin_dir_url( __FILE__ ) . '../../vendor/alertify/css/alertify.min.css', array(), 'all' );
			// Alertify semantic theme.
			wp_enqueue_style( 'inc-alertify-theme-semantic-min-css', plugin_dir_url( __FILE__ ) . '../../vendor/alertify/css/themes/semantic.min.css', array(), 'all' );

			// Menu
			wp_enqueue_style( 'nicescrollr-menu-css', plugin_dir_url( __FILE__ ) . 'css/menu.css', array(), 'all' );

			// Font Awesome.
			$fa_url = 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';
			$fa_cdn = wp_remote_get( $fa_url );
			if( (int) wp_remote_retrieve_response_code( $fa_cdn ) !== 200 ) {
				return;
			}
			wp_enqueue_style( 'inc-font-awesome', $fa_url, false );
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

		if( $hook_suffix === 'settings_page_nicescrollr_settings' ) {

			// Alertify
			wp_enqueue_script( 'nicescrollr-inc-alertify-js', plugin_dir_url( __FILE__ ) . '../../vendor/alertify/alertify.min.js', array( 'jquery' ), 'all' );

			// Ajax Reset Functionality
			wp_enqueue_script( 'nicescrollr-ajax-js', plugin_dir_url( __FILE__ ) . 'js/ajax.js', array(
					'jquery',
					'nicescrollr-inc-alertify-js',
				), 'all' );

			// ScrollTo
			wp_enqueue_script( 'nicescrollr-inc-scrollto-min-js', plugin_dir_url( __FILE__ ) . '../../vendor/scrollto/jquery.scrollTo.min.js', array( 'jquery' ), 'all' );

			// Color Picker
			wp_enqueue_script( 'wp-color-picker' );

			// Fancy Select
			wp_enqueue_script( 'nicescrollr-inc-fancy-select-js', plugin_dir_url( __FILE__ ) . '../../vendor/fancy-select/fancySelect.js', array( 'jquery' ), 'all' );

			// Settings Menu
			wp_enqueue_script( 'nicescrollr-menu-js', plugin_dir_url( __FILE__ ) . 'js/menu.js', array(
					'jquery',
					'wp-color-picker',
					'nicescrollr-inc-fancy-select-js',
					'nicescrollr-inc-scrollto-min-js'
				), 'all' );
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

		if( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'nicescrollr_settings' ) {

			$ci = 'nsr';
			$prefix = $ci . '-';

			$section = isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : '';

			$class = '';

			if( isset( $_REQUEST['tab'] ) ) {

				switch( $section ) {
					case 'frontend':
						$class = 'frontend';
						break;
					case 'backend':
						$class = 'backend';
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

		if( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'nicescrollr_settings' ) {

			$this->menu_localisation->run();
			$this->ajax_localisation->run();
		}
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

		add_options_page( 'Nicescrollr', 'Nicescrollr', 'manage_options', 'nicescrollr_settings', array( $this, 'menu_display' ) );
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

		$settings = new nsr_settings( $this->get_domain(), $this->get_section() );
		$settings->add_hooks();
	}

	/**
	 * Renders the page for the menu.
	 *
	 * @since  0.1.0
	 * @uses   echo_section()
	 * @see    admin/menu/includes/class-nsr-reset-section.php
	 *
	 * @param  $active_tab
	 *
	 * @return void
	 */
	public function menu_display() {

		$active_tab = $this->section;
		?>
		
		<div class="wrap">

			<h2 class="nsr-page-title"><?php echo __( 'Nicescrollr', $this->domain ); ?></h2>

			<form id="nsr_form" method="POST" action="options.php">

				<?php
				// Sets the active tab.
				if( $active_tab === 'backend' ) {

					$active_tab = 'backend';
				}
				else {

					$active_tab = 'frontend';
				} ?>
				
				<!-- Nav tab -->
				<div class="nav-tab-wrapper">
					<a href="?page=nicescrollr_settings&tab=frontend" class="nav-tab <?php echo $active_tab === 'frontend' ? 'nav-tab-active' : ''; ?> "><i class="fa fa-television"></i><?php _e( 'Frontend', $this->domain ); ?>
					</a>
					<a href="?page=nicescrollr_settings&tab=backend" class="nav-tab <?php echo $active_tab === 'backend' ? 'nav-tab-active' : ''; ?> "><i class="fa fa-wordpress"></i><?php _e( 'Backend', $this->domain ); ?>
					</a>
				</div>

				<?php
				// Set a hidden field containing the name of the settings section.
				if( $active_tab === 'backend' ) {

					echo '<input type="hidden" name="section" value="backend" />';
				}
				else {

					echo '<input type="hidden" name="section" value="frontend" />';
				}

				// Settings fields.
				settings_fields( 'nicescrollr_options' );
				do_settings_sections( 'nicescrollr_settings' );

				// Reset button
				$this->reset_section->echo_section( $active_tab );

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
	 * @return void | echo $html
	 */
	public function admin_notice_display() {

		// If there are any error-related transients
		if( false !== get_transient( 'nicescrollr_validation_transient' ) ) {

			// Retrieves the error-array and the corresponding meta data
			$errors = (array) get_transient( 'nicescrollr_validation_transient' );

			// Outputs all eventual errors.
			foreach( $errors as $option_key => $error_meta ) {

				$error_meta = (object) $error_meta;

				// Assigns the transient containing the error message to the array of notices.
				$notices[$option_key] = get_transient( $option_key );

				// Extracts the error message and echoes it inside the admin notice area.
				if( is_wp_error( $error_meta->message ) ) {

					$error_message = $error_meta->message->get_error_message();

					// Admin notice.
					$html = '<div class="error notice is-dismissible ' . $error_meta->notice_level . '">';
					$html .= '<p>';
					$html .= '<a class="nsr-validation-error" href="#' . $option_key . '" data-index="' . $error_meta->index . '">';
					$html .= $error_meta->name;
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

		if( ! wp_verify_nonce( $_REQUEST['nonce'], 'reset_' . $_REQUEST['section'] . '_nonce' ) ) {
			exit( __( 'One more try and your browser will burst into flames ;-)', $this->domain ) );
		}

		// Resets the requested section.
		if( isset( $_REQUEST['section'] ) ) {

			$settings_section = $_REQUEST['section'];

			if( true === $this->options->reset_settings( $settings_section ) ) {

				$response = array(
					'success' => __( 'All done! Please refresh the page for the settings to take effect.', $this->domain ),
				);
				wp_send_json_success( $response );
			}
			else {

				$response = array(
					'success' => __( 'Couldn\'t reset the settings. Please try again.', $this->domain ),
				);
				wp_send_json_error( $response );
			}
		}

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
