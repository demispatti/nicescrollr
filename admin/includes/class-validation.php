<?php
namespace Nicescrollr\Admin\Includes;

use Nicescrollr\Admin\Includes as AdminIncludes;
use WP_Error;

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class responsible for sanitizing and validating the user inputs.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/includes
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_Validation {

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
	 * The reference to the options class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    AdminIncludes\Nsr_Options $Options
	 */
	private $Options;

    /**
     * Assigns the required parameters to its instance.
     *
     * @param string $domain
     * @param Nsr_Options $Options
     *
     * @since 0.1.0
     */
	public function __construct($domain, $Options) {

		$this->domain = $domain;
		$this->Options = $Options;
	}

    /**
     * Kicks off sanitisation and validation - if there's any input given.
     *
     * @param array $input
     * @param string $section
     *
     * @return array
     * @since  0.1.0
     */
	public function run($input, $section)
    {

		if( isset( $_SESSION['Nsr_Upgrade'] ) || isset( $input['internal'] ) ) {

			return $input;
		}

		$input = $this->sanitize( $input );
		$valid = $this->validate( $input, $section );

		$valid = $this->fill( $valid, $section );

		return $this->merge_options( $valid, $section );
	}

    /**
     * Sanitizes the input.
     *
     * @param array $input
     *
     * @return array $output
     * @since  0.1.0
     *
     */
	private function sanitize($input)
    {

		$output = array();

		foreach( $input as $key => $value ) {

			if( isset ( $value ) ) {
				$output[$key] = strip_tags( stripslashes( $value ) );
			}
		}

		return apply_filters( 'sanitize', $output, $input );
	}

    /**
     * Validates the input.
     *
     * since  0.1.0
     * @param array $input
     * @param string $section
     *
     * @return array $output
     * @uses   get_default_settings()
     * @see    admin/includes/class-Nsr-options.php
     * @uses   translate_to_default_locale()
     */
	private function validate($input, $section)
    {

		$defaults = $this->Options->get_default_settings( $section );
		$options_meta = $this->Options->get_options_meta();
		$notice_levels = $this->Options->get_notice_levels();
		$output = array();
		$errors = array();
		$validation_value = null;
		$rgba_pattern = '/(^[a-zA-Z]+$)|(#(?:[0-9a-f]{2}){2,4}|#[0-9a-f]{3}|(?:rgba?|hsla?)\((?:\d+%?(?:deg|rad|grad|turn)?(?:,|\s)+){2,3}[\s\/]*[\d\.]+%?\))/i';

		$i = 0;
		foreach( $input as $option_key => $value ) {

			switch( $option_key ) {

				case ( $option_key === 'cursoropacitymin' );

					if( $value !== '' ) {

						if( ( ! ( (int) $value >= 0 ) && ! ( (int) $value <= 1.00 ) ) || ! preg_match( '/^[0-9]+(\.[0-9]{1,2})?$/', $value ) ) {

							$value = '0';
							$errors[$option_key] = $this->add_error( $i, $option_key, $options_meta, $notice_levels );
						}
					}

					break;

				case ( $option_key === 'cursoropacitymax' );

					if( ( ! ( (int) $value >= 0 ) && ! ( (int) $value <= 1.00 ) ) || ! preg_match( '/^[0-9]+(\.[0-9]{1,2})?$/', $value ) ) {

						$value = $defaults[$option_key];
						$errors[$option_key] = $this->add_error( $i, $option_key, $options_meta, $notice_levels );
					}

					break;

				case ( $option_key === 'cursorbordercolor' );

					if( isset( $value ) && ! preg_match( $rgba_pattern, $value ) ) {

						$value = $defaults[$option_key];
						$errors[$option_key] = $this->add_error( $i, $option_key, $options_meta, $notice_levels );
					}

					break;

				case( $option_key === 'zindex' );

					if( ! ctype_digit( $value ) ) {

						if( 'auto' !== $value ) {

							$value = $defaults[$option_key];
							$errors[$option_key] = $this->add_error( $i, $option_key, $options_meta, $notice_levels );
						}
					}

					break;

				case ( $option_key === 'scrollspeed' );

					if( $value === '0' || ! ctype_digit( $value ) ) {

						$value = $defaults[$option_key];
						$errors[$option_key] = $this->add_error( $i, $option_key, $options_meta, $notice_levels );
					}

					break;

				case ( $option_key === 'background' );

					if( '' !== $value && ! preg_match( $rgba_pattern, $value ) ) {

						$value = $defaults[$option_key];
						$errors[$option_key] = $this->add_error( $i, $option_key, $options_meta, $notice_levels );
					}

					break;

				case( $option_key === 'hidecursordelay' );

					if( $value !== '' ) {

						if( ! ctype_digit( $value ) ) {

							$value = '';
							$errors[$option_key] = $this->add_error( $i, $option_key, $options_meta, $notice_levels );
						}
					}

					break;

				case( $option_key === 'cursordragspeed' || $option_key === 'mousescrollstep' );

					if( $value !== '' ) {

						if( ! preg_match( '/^[0-9]+(\.[0-9]{1,2})?$/', $value ) ) {

							$value = '';
							$errors[$option_key] = $this->add_error( $i, $option_key, $options_meta, $notice_levels );
						}
					}

					break;

				case( $option_key === 'scrollbarid' );

					if( '' || false !== $value ) {

						$value = $this->check_text_fields( $value );

						if( is_wp_error( $value ) ) {

							$value = '';
							$errors[$option_key] = $this->add_error( $i, $option_key, $options_meta, $notice_levels );
						}
					}

					break;

				case ( $option_key === 'bt_arrow_color' || $option_key === 'bt_arrow_hover_color' || $option_key === 'bt_background_color' || $option_key === 'bt_hover_background_color' || $option_key === 'bt_border_color' || $option_key === 'bt_hover_border_color' || $option_key === 'cursorcolor' );

					if( ! preg_match( $rgba_pattern, $value ) ) {

						$value = $defaults[$option_key];
						$errors[$option_key] = $this->add_error( $i, $option_key, $options_meta, $notice_levels );
					}

					break;

				case( $option_key === 'bt_posx_from_right' || $option_key === 'bt_posy_from_bottom' || $option_key === 'bt_width' || $option_key === 'bt_height' ||
					$option_key === 'bt_border_width' || $option_key === 'directionlockdeadzone' || $option_key === 'railpaddingtop' || $option_key === 'railpaddingright' || $option_key === 'railpaddingbottom' || $option_key === 'railpaddingleft' || $option_key === 'cursorminheight' || $option_key === 'cursorborderradius' || $option_key === 'cursorborderwidth' || $option_key === 'cursorwidth' );

					if( $value !== '' ) {

						// Pattern to remove any reasonable unit for pixels.
						$pattern = '/pixels+|pixel+|px+/i';
						if( preg_match( $pattern, $value ) ) {
							$value = preg_replace( $pattern, '', $value );
							$value = trim( $value );
						}
						// If the value is not an integer after removing any reasonable unit for pixels...
						if( ! ctype_digit( $value ) ) {
							$value = $defaults[$option_key];
							$errors[$option_key] = $this->add_error( $i, $option_key, $options_meta, $notice_levels );
						}
						else if( $value !== 0 && ctype_digit( $value )) {
							$value = $this->set_unit( $option_key, $value, 'px' );
						}
					}

					break;

				case( $option_key === 'bt_border_radius_top_left' || $option_key === 'bt_border_radius_top_right' || $option_key === 'bt_border_radius_bottom_left' || $option_key === 'bt_border_radius_bottom_right' );

					if( $value !== '' ) {

						$unit = 'px';
						// Pattern to remove any reasonable unit for pixels.
						$pattern = '/pixels+|pixel+|px+|%+/i';
						if( preg_match( $pattern, $value ) ) {

							if( preg_match( '/%/', $value ) ) {
								$unit = '%';
							}

							$value = preg_replace( $pattern, '', $value );
							$value = trim( $value );
						}
						// If the value is not an integer after removing any reasonable unit for pixels...
						if( ! ctype_digit( $value ) ) {

							$value = $defaults[$option_key];
							$errors[$option_key] = $this->add_error( $i, $option_key, $options_meta, $notice_levels );
						}
						else {

							$value = $this->set_unit( $option_key, $value, $unit );
						}
					}

					break;
			}
			// The array holding the processed values.
			$output[$option_key] = $value;
			$i ++;
		}

		// Fill unset options with "false".
		foreach( $defaults as $key => $value ) {

			$output[$key] = isset( $output[$key] ) ? $output[$key] : false;
		}

		// If there were errors and transients were created, we create one more containing the ids of the previously created ones.
		if( ! empty( $errors )) {

			set_transient( 'nicescrollr_validation_transient', $errors, 60 );
		}

		return apply_filters( 'validate', $output, $input );
	}

	/**
	 * Validates text fields.
	 *
	 * @param $value
	 *
	 * @return string|WP_Error
	 */
	private function check_text_fields( $value ) {

		if( '' === $value ) {
			return $value;
		}

		if( ! preg_match( '/^([A-Za-z0-9_ ().-]-)*[A-Za-z0-9\_().-]+$/', $value ) ) {

			return new WP_Error( 'broke', __( "Your input didn't pass validation. Please use numbers and/or alphabetical characters.", $this->domain ) );
		}

		return (string) $value;
	}

    /**
     * Fill options that are not set and give them a specified default value.
     *
     * @param array $valid
     * @param string $section
     *
     * @return array $valid
     * @access private
     *
     * @since  0.2.1
     */
	private function fill($valid, $section)
    {

		if( 'frontend' === $section || 'backend' === $section ) {

			$option_args = array_merge( $this->Options->get_settings_per_section( 'basic' ), $this->Options->get_settings_per_section( 'extended' ), $this->Options->get_settings_per_section( 'backtop' ) );

			// Make sure there is at least the option key with an empty value getting stored.
			foreach( $option_args as $option_key => $args ) {

				if( ! isset( $valid[$option_key] ) ) {

					if( $option_args['input_type'] === 'checkbox' ) {
						$valid[$option_key] = '0';
					}
					if( $option_args['input_type'] === 'text' ) {
						$valid[$option_key] = '';
					}
				}
			}
		}

		return $valid;
	}

	/**
	 * Merges the validated options back into the array of options.
	 *
	 * @param $valid
	 * @param $section
	 *
	 * @return array $options
	 */
	private function merge_options( $valid, $section )
    {

		$options = get_option( 'nicescrollr_options' );

		unset( $options[$section] );

		$options[$section] = $valid;

		ksort( $options );

		return $options;
	}

	/**
	 * Creates a human readable version of the 'option key'.
	 *
	 * @param $string
	 *
	 * @return string
	 */
	private function make_readable( $string )
    {

		// Remove the file extension
		$name = preg_replace( array( '/-/', '/_/' ), array( ' ', ' ' ), $string );

		if( preg_match( '/\s/', $name ) ) {
			// Remove whitespace and capitalize.
			$name = implode( ' ', array_map( 'ucfirst', explode( ' ', $name ) ) );
			$output = $name;
		}
		else {

			$output = ucfirst( $name );
		}

		return $output;
	}

	/**
	 * Sets the respective units.
	 *
	 * @param $option_key
	 * @param $value
	 * @param $unit
	 *
	 * @return bool|string
	 */
	private function set_unit( $option_key, $value, $unit ) {

		$px_related_option_keys = array(
			'cursorwidth',
			'cursorborderwidth',
			'cursorborderradius',
			'cursorminheight',
			'directionlockdeadzone',
			'bt_border_width',
			'bt_width',
			'bt_height',
			'bt_posx_from_right',
			'bt_posy_from_bottom'
		);
		if( in_array( $option_key, $px_related_option_keys, true ) ) {

			if( 'px' === $unit ) {

				if( '0' !== $value ) {

					return $value . 'px';
				}

				return $value;
			}
		}

		$maybe_percent_related_option_keys = array(
			'bt_border_radius_top_left',
			'bt_border_radius_top_right',
			'bt_border_radius_bottom_left',
			'bt_border_radius_bottom_right'
		);
		if( in_array( $option_key, $maybe_percent_related_option_keys, true ) ) {

			if( '%' === $unit ) {

				if( '0' !== $value ) {

					return $value . '%';
				}

				return $value;
			}

			if( 'px' === $unit ) {

				if( '0' !== $value ) {

					return $value . 'px';
				}

				return $value;
			}
		}

		return false;
	}

	/**
	 * Helper function, that translates "non-default-locale strings" into strings of the default locale.
	 * This task is necessary, since Nicescroll needs some strings as parameters and they have to be served in English.
	 * With this step, localisation remains fully functional.
	 *
	 * @since  0.1.0
	 * @access private
	 * @see    admin/includes/class-nsr-settings.php | translate_to_custom_locale()
	 *
	 * @param  $input
	 *
	 * @return mixed
	 *
	 * @deprecated
	 */
	private function translate_to_default_locale( $input ) {

		$output = array();

		foreach( (array) $input as $option_key => $value ) {

			switch( $option_key ) {

				case( $option_key === 'cursorborderstate' );
					if( null !== $value && $value === __( 'none', $this->domain ) ) {

						$output[$option_key] = 'none';
					}
					else {
						$output[$option_key] = $value;
					}
					break;

				case( $option_key === 'autohidemode' );

					if( true === $value || 'enabled' === $value ) {

						$output[$option_key] = 'true';
					}
					else if( 'cursor' === $value ) {

						$output[$option_key] = 'cursor';
					}
					else if( false === $value || 'disabled' === $value ) {

						$output[$option_key] = 'false';
					}
					else if( 'leave' === $value ) {

						$output[$option_key] = 'leave';
					}
					else if( 'hidden' === $value ) {

						$output[$option_key] = 'hidden';
					}
					else if( 'scroll' === $value ) {

						$output[$option_key] = 'scroll';
					}
					else {
						$output[$option_key] = $value;
					}
					break;

				case( $option_key === 'railoffset' );

					if( null !== $value && $value === __( 'false', $this->domain ) ) {

						$output[$option_key] = 'false';
					}
					else if( null !== $value && $value === __( 'top', $this->domain ) ) {

						$output[$option_key] = 'top';
					}
					else if( null !== $value && $value === __( 'left', $this->domain ) ) {

						$output[$option_key] = 'left';
					}
					else {
						$output[$option_key] = $value;
					}
					break;

				case( $option_key === 'railalign' );

					if( null !== $value && $value === __( 'right', $this->domain ) ) {

						$output[$option_key] = 'right';
					}
					else if( null !== $value && $value === __( 'left', $this->domain ) ) {

						$output[$option_key] = 'left';
					}
					else {
						$output[$option_key] = $value;
					}
					break;

				case( $option_key === 'railvalign' );

					if( null !== $value && $value === __( 'bottom', $this->domain ) ) {

						$output[$option_key] = 'bottom';
					}
					else if( null !== $value && $value === __( 'top', $this->domain ) ) {

						$output[$option_key] = 'top';
					}
					else {
						$output[$option_key] = $value;
					}
					break;

				case( $option_key === 'cursorfixedheight' );

					if( false === $value ) {

						$output[$option_key] = 'false';
					}
					else {
						$output[$option_key] = $value;
					}
					break;

				default:
					$output[$option_key] = $value;
			}
		}

		return apply_filters( 'translate_to_default_locale', $output, $input );
	}

	/**
	 * Creates and returns an array containing the error meta data for the given option.
	 *
	 * @param $i
	 * @param $option_key
	 * @param $options_meta
	 * @param $notice_levels
	 *
	 * @return array
	 *
	 * @since  0.7.5
	 * @access private
	 */
	private function add_error($i, $option_key, $options_meta, $notice_levels)
    {

		return array(
			'option_key' => $option_key,
			'name' => $this->make_readable( $option_key ),
			'index' => $i,
			'notice_level' => $notice_levels[$option_key],
			'wp_error' => $this->get_wp_error( $options_meta, $option_key ),
		);
}

    /**
     * Creates and returns a WP_Error instance for the given option.
     *
     * @param array $options_meta
     * @param string $option_key
     *
     * @return WP_Error
     *
     * @since  0.7.5
     * @access private
     */
	private function get_wp_error($options_meta, $option_key) {
		
		return new WP_Error( -1, $options_meta[$option_key]['validation_error_message'] );
	}

}
