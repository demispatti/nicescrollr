<?php

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class that deals with the settings api.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/menu/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_settings {

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
	 * The array containing the default options.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    array $default_options
	 */
	private $default_options;

	/**
	 * The reference to the options class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    nsr_options $options
	 */
	private $options;

	/**
	 * The reference to the validation class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    nsr_validation $validation
	 */
	private $validation;

	private $stored_options;

	/**
	 * Kicks off the settings class.
	 *
	 * @since 0.1.0
	 *
	 * @param array $app
	 * @param string $section
	 */
	public function __construct( $domain, $section ) {

		$this->domain = $domain;
		$this->section = $section;

		$this->load_dependencies();

		$this->stored_options = get_option( 'nicescrollr_options' );
	}

	/**
	 * Register the hooks with WordPress.
	 *
	 * @since  0.5.2
	 *
	 * @return void
	 */
	public function add_hooks() {

		add_action( 'admin_init', array( $this, 'register_settings' ), 1 );
		//add_action( 'admin_init', array( $this, 'check_for_options' ), 2 );
		add_action( 'admin_init', array( $this, 'load_default_options' ), 3 );
		add_action( 'admin_init', array( $this, 'initialize_options' ), 10 );
	}

	/**
	 * Loads it's dependencies.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( __DIR__ ) . 'includes/class-nsr-options.php';
		$this->options = new nsr_options( $this->get_domain() );

		require_once plugin_dir_path( __DIR__ ) . 'includes/class-nsr-validation.php';
		$this->validation = new nsr_validation( $this->get_domain(), $this->get_options_instance(), $this->get_section() );
	}

	/**
	 * Retrieves the reference to the "plugin data" class.
	 *
	 * @since  0.1.0
	 * @return nsr_options $options
	 */
	public function get_options_instance() {

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
	 * Registers the settings group and the validation-callback with WordPress.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function register_settings() {

		register_setting( 'nicescrollr_options', 'nicescrollr_options', array( $this, 'run_validation' ) );
	}

	/**
	 * Kicks off the validation process.
	 *
	 * @since  0.1.0
	 * @return $output
	 */
	public function run_validation( $input ) {

		if( isset( $_REQUEST['section'] ) ) {

			$section = $_REQUEST['section'];
		}
		else if( isset( $_REQUEST['cb_parallax_upgrade'] ) ) {

			return $input;
		}
		else {

			$section = false;
		}

		$output = $this->validation->run( $input, $section );

		return $output;
	}

	/**
	 * Checks for options in the database and seeds the default values if the options group should be empty.
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

		if( false === $options ) {

			$this->options->seed_options();
		}
		else if( ! isset( $options['frontend']['cursorcolor'] ) ) {

			$option_groups = array( 'frontend', 'backend' );

			foreach( $option_groups as $i => $option_group ) {

				if( key( $options ) !== $option_group ) {

					$this->options->seed_options( $option_group );
				}
			}
		}
		else {

			return;
		}
	}

	/**
	 * Calls the function that retrieves the default options.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function load_default_options() {

		$this->set_default_options();
	}

	/**
	 * Retrieves the default options for the requested section and sets them.
	 *
	 * @since  0.1.0
	 * @uses   get_default_settings()
	 * @see    admin/menu/includes/class-nsr-options.php
	 * @access private
	 * @return void
	 */
	private function set_default_options() {

		$this->default_options = $this->options->get_default_settings( $this->section );
	}

	/**
	 * Registers the sections, their headings and settings fields with WordPress.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @uses   get_section_heading()
	 * @uses   get_options_meta()
	 * @see    admin/menu/includes/class-nsr-options.php
	 * @return void
	 */
	public function initialize_options() {

		$this->add_settings_section( $this->options->get_section_heading( 'basic' ) );
		$this->add_settings_field( $this->options->get_args( 'basic' ) );

		$this->add_settings_section( $this->options->get_section_heading( 'extended' ) );
		$this->add_settings_field( $this->options->get_args( 'extended' ) );

		$this->add_settings_section( $this->options->get_section_heading( 'backtop' ) );
		$this->add_settings_field( $this->options->get_args( 'backtop' ) );
	}

	/**
	 * Registers the settings sections with WordPress.
	 *
	 * @since  0.1.0
	 *
	 * @param  array $section_heading
	 *
	 * @return void
	 */
	private function add_settings_section( $section_heading ) {

		add_settings_section( $section_heading['settings_group'] . '_settings_section', $section_heading['title'], array(
				$this,
				$section_heading['callback']
			), 'nicescrollr_settings' );
	}

	/**
	 * Registers the settings fields with WordPress.
	 *
	 * @since  0.1.0
	 *
	 * @param  array $settings_fields
	 *
	 * @return void
	 */
	private function add_settings_field( $settings_fields ) {

		foreach( $settings_fields as $option_key => $args ) {

			add_settings_field( $option_key, $args['name'], array( $this, $args['callback'] ), 'nicescrollr_settings', $args['settings_group'] . '_settings_section', array(
					'option_key' => $option_key,
					'section' => $this->section,
					'title' => $args['title'],
					'input_type' => $args['input_type'],
					'select_values' => $args['select_values'],
				) );
		}
	}

	/**
	 * Renders the description for the "basic settings section".
	 *
	 * @since 0.1.0
	 * @return void / echo
	 */
	public function basic_settings_section_callback() {

		//echo '<h2 class="basic nicescrollr_settings_toggle wtf"><i class="fa fa-sliders" aria-hidden="true"></i>' . __( 'Basic Settings', $this->domain ) . '</h2>';
	}

	/**
	 * Renders the description for the "extended settings section".
	 *
	 * @since 0.1.0
	 * @return void / echo
	 */
	public function extended_settings_section_callback() {

		//echo '<h2 class="extended nicescrollr_settings_toggle"><i class="fa fa-sliders" aria-hidden="true"></i>' . __( 'Extended Settings', $this->domain ) . '</h2>';
	}

	/**
	 * Renders the description for the "plugin section".
	 *
	 * @since 0.1.0
	 * @return void / echo
	 */
	public function backtop_settings_section_callback() {

		//echo '<h2 class="backtop nicescrollr_settings_toggle"><i class="fa fa-sliders" aria-hidden="true"></i>' . __( 'Plugin Settings', $this->domain ) . '</h2>';
	}

	/**
	 * Calls the corresponding callback function that renders the section field.
	 *
	 * @since  0.1.0
	 *
	 * @param  array $args
	 *
	 * @return void / echo
	 */
	public function render_settings_field_callback( $args ) {

		switch( $args['input_type'] ) {

			case( $args['input_type'] === 'checkbox' );

				$this->echo_checkbox_field( $args );
				break;

			case( $args['input_type'] === 'text' );

				$this->echo_text_field( $args );
				break;

			case( $args['input_type'] === 'color' );

				$this->echo_color_picker_field( $args );
				break;

			case( $args['input_type'] === 'select' );

				$this->echo_select_field( $args );
				break;
		}
	}

	private function get_option_value( $section, $option_key ) {

		if( isset( $this->stored_options[$section][$option_key] ) ) {

			return $this->stored_options[$section][$option_key];
		}

		return $this->default_options[$option_key];
	}

	/**
	 * Renders a settings field with a checkbox.
	 *
	 * @since 0.1.0
	 *
	 * @param $args
	 *
	 * @return void / echo string $html
	 */
	public function echo_checkbox_field( $args ) {

		$option_key = $args['option_key'];
		$title = $args['title'];
		$section = $args['section'];

		$value = $this->get_option_value( $section, $option_key );

		$html = '<label class="nsr-switch label-for-nsr-switch" title="' . $title . '">';
		$html .= '<input type="checkbox" id="' . $option_key . '" class="nsr-switch-input nsr-input-checkbox" name="' . 'nicescrollr_options' . '[' . $option_key . ']" value="1" ' . checked( 1, isset( $value ) ? $value : 0, false ) . '>';
		$html .= '<span class="nsr-switch-label" data-on="On" data-off="Off"></span>';
		$html .= '<span class="nsr-switch-handle"></span>';
		$html .= '</label>';

		echo $html;
	}

	/**
	 * Renders a settings field with a text field.
	 *
	 * @since 0.1.0
	 *
	 * @param $args
	 *
	 * @return void / echo string $html
	 */
	public function echo_text_field( $args ) {

		$option_key = $args['option_key'];
		$title = $args['title'];
		$section = $args['section'];

		$value = $this->get_option_value( $section, $option_key );

		$html = '<p class="nsr-input-container">';
		$html .= '<input type="text" id="' . $option_key . '" class="nsr-input-text" title="' . $title . '" name="' . 'nicescrollr_options' . '[' . $option_key . ']" Placeholder="' . $this->default_options[$option_key] . '" value="' . $value . '">';
		$html .= '</p>';

		echo $html;
	}

	/**
	 * Renders a settings field with a color picker.
	 *
	 * @since 0.1.0
	 *
	 * @param $args
	 *
	 * @return void / echo string $html
	 */
	public function echo_color_picker_field( $args ) {

		$option_key = $args['option_key'];
		$title = $args['title'];
		$section = $args['section'];

		$value = $this->get_option_value( $section, $option_key );

		$html = '<p class="nsr-input-container">';
		$html .= '<input type="text" id="' . $option_key . '" title="' . $title . '" name="' . 'nicescrollr_options' . '[' . $option_key . ']" Placeholder="' . $this->default_options[$option_key] . '" value="' . $value . '" class="' . $option_key . ' nsr-color-picker nsr-input-color-picker">';
		$html .= '</p>';

		echo $html;
	}

	/**
	 * Renders a settings field with a select dropdown.
	 *
	 * @since  0.1.0
	 * @uses   translate_to_custom_locale()
	 *
	 * @param  $args
	 *
	 * @return void / echo string $html
	 */
	public function echo_select_field( $args ) {

		$option_key = $args['option_key'];
		$title = $args['title'];
		$section = $args['section'];
		$select_values = (array) $args['select_values'];

		$retrieved_value = $this->get_option_value( $section, $option_key );

		if( get_locale() !== 'en_US' ) {

			$retrieved_value = $this->translate_to_custom_locale( $retrieved_value, $option_key );
		}

		$html = '<p class="nsr-input-container">';
		$html .= '<select title="' . $title . '" name="' . 'nicescrollr_options' . '[' . $option_key . ']" class="floating-element fancy-select nsr-fancy-select nsr-input-select" id="' . $option_key . '">';
		foreach( $select_values as $value ) {

			$html .= '<option value="' . $value . '"' . selected( $value, $retrieved_value, false ) . '>' . $value . '</option>';
		}
		$html .= '</select>';
		$html .= '</p>';

		echo $html;
	}

	/**
	 * Translation helper function for some select box values.
	 * Since Nicescroll makes use of strings as parameters - and it does only "speak" English -
	 * this function translates the values that were stored in the default locale into strings of the current locale.
	 * This way, the localisation feature remains fully functional.
	 *
	 * @since  0.1.0
	 *
	 * @see    admin/menu/includes/class-nsr-validation.php | translate_to_default_locale()
	 *
	 * @return string $output
	 */
	public function translate_to_custom_locale( $string, $option_key ) {

		switch( $option_key ) {

			case( $option_key === 'cursorborderstate' );
				if( isset( $string ) && $string === 'none' ) {

					$output = __( 'none', $this->domain );
				}
				else {
					$output = $string;
				}
				break;

			case( $option_key === 'autohidemode' );

				if( isset( $string ) && $string === 'off' ) {

					$output = __( 'off', $this->domain );
				}
				else if( isset( $string ) && $string === 'on' ) {

					$output = __( 'on', $this->domain );
				}
				else if( isset( $string ) && $string === 'cursor' ) {

					$output = __( 'cursor', $this->domain );
				}
				else {
					$output = $string;
				}
				break;

			case( $option_key === 'railoffset' );

				if( isset( $string ) && $string === 'off' ) {

					$output = __( 'off', $this->domain );
				}
				else if( isset( $string ) && $string === 'top' ) {

					$output = __( 'top', $this->domain );
				}
				else if( isset( $string ) && $string === 'left' ) {

					$output = __( 'left', $this->domain );
				}
				else {
					$output = $string;
				}
				break;

			case( $option_key === 'railalign' );

				if( isset( $string ) && $string === 'right' ) {

					$output = __( 'right', $this->domain );
				}
				else if( isset( $string ) && $string === 'left' ) {

					$output = __( 'left', $this->domain );
				}
				else {
					$output = $string;
				}
				break;

			case( $option_key === 'railvalign' );

				if( isset( $string ) && $string === 'bottom' ) {

					$output = __( 'bottom', $this->domain );
				}
				else if( isset( $string ) && $string === 'top' ) {

					$output = __( 'top', $this->domain );
				}
				else {
					$output = $string;
				}
				break;

			case( $option_key === 'cursorfixedheight' );

				if( isset( $string ) && $string === 'off' ) {

					$output = __( 'off', $this->domain );
				}
				else {
					$output = $string;
				}
				break;

			default:
				$output = $string;
		}

		return $output;
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
