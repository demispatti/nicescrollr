<?php

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
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
	 * @var    object $options
	 */
	private $options;

	/**
	 * The reference to the validation class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    object $validation
	 */
	private $validation;

	/**
	 * Kicks off the settings class.
	 *
	 * @since 0.1.0
	 * @param array  $app
	 * @param string $section
	 */
	public function __construct( $domain, $section ) {

		$this->domain  = $domain;
		$this->section = $section;

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
		// The class that holds all plugin-related data.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "includes/class-nsr-options.php";
		// The class responsible for the validation tasks.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "includes/class-nsr-validation.php";

		$this->options    = new nsr_options( $this->get_domain() );
		$this->validation = new nsr_validation( $this->get_domain(), $this->get_plugin_options(), $this->get_section() );
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
	 * Adds the actions to be executed with WordPress:
	 * - Registers the settings group and the validation-callback with WordPress
	 * - Checks if there are any options in the database and seeds them if there are none stored
	 * - Loads the default backend settings to populate the placeholders on empty input fields
	 * - Registers the settings sections and fields with WordPress
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function init() {

		add_action( 'admin_init', array( &$this, 'register_settings' ), 1 );
		add_action( 'admin_init', array( &$this, 'check_for_options' ), 2 );
		add_action( 'admin_init', array( &$this, 'load_default_options' ), 3 );
		add_action( 'admin_init', array( &$this, 'initialize_options' ), 10 );
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

		if( isset($_REQUEST['section']) ) {

			$section = $_REQUEST['section'];
		} else if ( isset( $_REQUEST['cb_parallax_upgrade'] ) ) {

			return $input;
		} else {

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
		} else if( ! isset($options['frontend']['cursorcolor'] ) ) {

			$option_groups = array( 'frontend', 'backend', 'plugin' );

			foreach( $option_groups as $i => $option_group ) {

				if( key( $options ) !== $option_group ) {

					$this->options->seed_options( $option_group );
				}
			}
		} else {

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
	 * @uses   get_default_options()
	 * @see    admin/menu/includes/class-nsr-options.php
	 * @access private
	 * @return void
	 */
	private function set_default_options() {

		$this->default_options = $this->options->get_default_options( $this->section );
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

		if( 'plugin' == $this->section ) {

			$this->add_settings_section( $this->options->get_section_heading( 'plugin' ) );

			$this->add_settings_field( $this->options->get_args( 'plugin' ) );
		} else {

			$this->add_settings_section( $this->options->get_section_heading( 'basic' ) );

			$this->add_settings_field( $this->options->get_args( 'basic' ) );

			$this->add_settings_section( $this->options->get_section_heading( 'extended' ) );

			$this->add_settings_field( $this->options->get_args( 'extended' ) );
		}
	}

	/**
	 * Registers the settings sections with WordPress.
	 *
	 * @since  0.1.0
	 * @param  array $section_heading
	 * @return void
	 */
	private function add_settings_section( $section_heading ) {

		add_settings_section(
			$section_heading['settings_group'] . '_settings_section',
			$section_heading['title'],
			array( &$this, $section_heading['callback'] ),
			'nicescrollr_settings'
		);
	}

	/**
	 * Registers the settings fields with WordPress.
	 *
	 * @since  0.1.0
	 * @param  array $settings_fields
	 * @return void
	 */
	private function add_settings_field( $settings_fields ) {

		foreach( $settings_fields as $option_key => $args ) {

			add_settings_field(
				$option_key,
				$args['name'],
				array( $this, $args['callback'] ),
				'nicescrollr_settings',
				$args['settings_group'] . '_settings_section',
				array(
					'option_key'    => $option_key,
					'section'       => $this->section,
					'title'         => $args['title'],
					'input_type'    => $args['input_type'],
					'select_values' => $args['select_values'],
				)
			);
		}
	}

	/**
	 * Renders the description for the "basic settings section".
	 *
	 * @since 0.1.0
	 * @return void / echo
	 */
	public function basic_settings_section_callback() {

		//echo '<h2 class="nicescrollr_settings_toggle"><i class="fa fa-sliders" aria-hidden="true"></i>' . __( 'Basic Settings', $this->domain ) . '</h2>';
	}

	/**
	 * Renders the description for the "extended settings section".
	 *
	 * @since 0.1.0
	 * @return void / echo
	 */
	public function extended_settings_section_callback() {

		//echo '<h2 class="nicescrollr_settings_toggle"><i class="fa fa-sliders" aria-hidden="true"></i>' . __( 'Extended Settings', $this->domain ) . '</h2>';
	}

	/**
	 * Renders the description for the "plugin section".
	 *
	 * @since 0.1.0
	 * @return void / echo
	 */
	public function plugin_settings_section_callback() {

		//echo '<h2 class="nicescrollr_settings_toggle"><i class="fa fa-sliders" aria-hidden="true"></i>' . __( 'Plugin Settings', $this->domain ) . '</h2>';
	}

	/**
	 * Calls the corresponding callback function that renders the section field.
	 *
	 * @since  0.1.0
	 *
	 * @param  array $args
	 * @return void / echo
	 */
	public function render_settings_field_callback( $args ) {

		switch( $args['input_type'] ) {

			case($args['input_type'] == 'checkbox');

				$this->echo_checkbox_field( $args );
				break;

			case($args['input_type'] == 'text');

				$this->echo_text_field( $args );
				break;

			case($args['input_type'] == 'color');

				$this->echo_color_picker_field( $args );
				break;

			case($args['input_type'] == 'select');

				$this->echo_select_field( $args );
				break;
		}
	}

	/**
	 * Renders a settings field with a checkbox.
	 *
	 * @since 0.1.0
	 * @param $args
	 * @return void / echo string $html
	 */
	public function echo_checkbox_field( $args ) {

		$options = get_option( 'nicescrollr_options' );

		$option_key = $args['option_key'];
		$title = $args['title'];
		$section = $args['section'];

		$html = '<label class="nsr-switch label-for-nsr-switch" title="' . $title . '">';
		$html .= '<input type="checkbox" id="' . $option_key . '" class="nsr-switch-input nsr-input-checkbox" name="' . 'nicescrollr_options' . '[' . $option_key . ']" value="1" ' . checked( 1, isset($options[ $section ][ $option_key ]) ? $options[ $section ][ $option_key ] : 0, false ) . '></input>';
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

		$options = get_option( 'nicescrollr_options' );

		$option_key = $args['option_key'];
		$title = $args['title'];
		$section = $args['section'];

		$html = '<p class="nsr-input-container">';
		$html .= '<input type="text" id="' . $option_key . '" class="nsr-input-text" title="' . $title . '" name="' . 'nicescrollr_options' . '[' . $option_key . ']" Placeholder="' . $this->default_options[ $option_key ] . '" value="' . $options[ $section ][ $option_key ] . '"></input>';
		$html .= '</p>';

		echo $html;
	}

	/**
	 * Renders a settings field with a color picker.
	 *
	 * @since 0.1.0
	 * @param $args
	 * @return void / echo string $html
	 */
	public function echo_color_picker_field( $args ) {

		$options = get_option( 'nicescrollr_options' );

		$option_key = $args['option_key'];
		$title = $args['title'];
		$section = $args['section'];

		$html = '<p class="nsr-input-container">';
		$html .= '<input type="text" id="' . $option_key . '" title="' . $title . '" name="' . 'nicescrollr_options' . '[' . $option_key . ']" Placeholder="' . $this->default_options[ $option_key ] . '" value="' . $options[ $section ][ $option_key ] . '" class="' . $option_key . ' nsr-color-picker nsr-input-color-picker"></input>';
		$html .= '</p>';

		echo $html;
	}

	/**
	 * Renders a settings field with a select dropdown.
	 *
	 * @since  0.1.0
	 * @uses   translate_to_custom_locale()
	 * @param  $args
	 * @return void / echo string $html
	 */
	public function echo_select_field( $args ) {

		if( get_locale() !== 'en_US' ) {

			$options = get_option( 'nicescrollr_options' );

			$options = $this->translate_to_custom_locale( $options[ $this->section ] );
		} else {

			$options = get_option( 'nicescrollr_options' );

			$options = $options[ $this->section ];
		}

		$option_key = $args['option_key'];
		$title = $args['title'];
		$select_values = $args['select_values'];

		$html = '<p class="nsr-input-container">';
		$html .= '<select title="' . $title . '" name="' . 'nicescrollr_options' . '[' . $option_key . ']" class="floating-element fancy-select nsr-fancy-select nsr-input-select" id="' . $option_key . '">';
		foreach( $select_values as $value ) {

			$html .= '<option value="' . $value . '"' . selected( $options[ $option_key ], $value, false ) . '>' . $value . '</option>';
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
	 * @access private
	 * @see    admin/menu/includes/class-nsr-validation.php | translate_to_default_locale()
	 * @return array $output
	 */
	public function translate_to_custom_locale( $input ) {

		$output = array();

		foreach( $input as $option_key => $value ) {

			switch( $option_key ) {

				case($option_key == 'cursorborderstate');
					if( isset($value) && $value == 'none' ) {

						$output[ $option_key ] = __( 'none', $this->domain );
					} else {
						$output[ $option_key ] = $value;
					}
					break;

				case($option_key == 'autohidemode');

					if( isset($value) && $value == 'off' ) {

						$output[ $option_key ] = __( 'off', $this->domain );
					} else if( isset($value) && $value == 'on' ) {

						$output[ $option_key ] = __( 'on', $this->domain );
					} else if( isset($value) && $value == 'cursor' ) {

						$output[ $option_key ] = __( 'cursor', $this->domain );
					} else {
						$output[ $option_key ] = $value;
					}
					break;

				case($option_key == 'railoffset');

					if( isset($value) && $value == 'off' ) {

						$output[ $option_key ] = __( 'off', $this->domain );
					} else if( isset($value) && $value == 'top' ) {

						$output[ $option_key ] = __( 'top', $this->domain );
					} else if( isset($value) && $value == 'left' ) {

						$output[ $option_key ] = __( 'left', $this->domain );
					} else {
						$output[ $option_key ] = $value;
					}
					break;

				case($option_key == 'railalign');

					if( isset($value) && $value == 'right' ) {

						$output[ $option_key ] = __( 'right', $this->domain );
					} else if( isset($value) && $value == 'left' ) {

						$output[ $option_key ] = __( 'left', $this->domain );
					} else {
						$output[ $option_key ] = $value;
					}
					break;

				case($option_key == 'railvalign');

					if( isset($value) && $value == 'bottom' ) {

						$output[ $option_key ] = __( 'bottom', $this->domain );
					} else if( isset($value) && $value == 'top' ) {

						$output[ $option_key ] = __( 'top', $this->domain );
					} else {
						$output[ $option_key ] = $value;
					}
					break;

				case($option_key == 'cursorfixedheight');

					if( isset($value) && $value == 'off' ) {

						$output[ $option_key ] = __( 'off', $this->domain );
					} else {
						$output[ $option_key ] = $value;
					}
					break;

				default:
					$output[ $option_key ] = $value;
			}
		}

		return apply_filters( 'translate_to_custom_locale', $output, $input );
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
