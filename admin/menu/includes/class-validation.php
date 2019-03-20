<?php

namespace Nicescrollr\Admin\Menu\Includes;

use Nicescrollr\Admin\Menu\Includes as MenuIncludes;

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
 * @subpackage        nicescrollr/admin/menu/includes
 * Author:            Demis Patti <demispatti@gmail.com>
 * Author URI:
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
	 * @var    MenuIncludes\Nsr_Options $Options
	 */
	private $Options;

	/**
	 * Assigns the required parameters to its instance.
	 *
	 * @since 0.1.0
	 *
	 * @param string $domain
	 * @param MenuIncludes\Nsr_Options $Options
	 * @param string $section
	 */
	public function __construct( $domain, $Options ) {

		$this->domain = $domain;
		$this->Options = $Options;
	}

	/**
	 * Kicks off sanitisation and validation - if there's any input given.
	 *
	 * @since  0.1.0
	 *
	 * @param  array $input
	 *
	 * @return mixed
	 */
	public function run( $input, $section ) {

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
	 * @since  0.1.0
	 *
	 * @param  array $input
	 *
	 * @return array $output
	 */
	private function sanitize( $input ) {

		$output = array();

		foreach( $input as $key => $value ) {

			if( isset ( $input[$key] ) ) {
				$output[$key] = strip_tags( stripslashes( $value ) );
			}
		}

		return apply_filters( 'sanitize', $output, $input );
	}

	/**
	 * Validates the input.
	 *
	 * since  0.1.0
	 * @uses   get_default_settings()
	 * @see    admin/menu/includes/class-Nsr-options.php
	 * @uses   translate_to_default_locale()
	 *
	 * @param  array $input
	 *
	 * @return array $output
	 */
	private function validate( $input, $section ) {

		$defaults = (array) $this->Options->get_default_settings( $section );
		$notice_levels = $this->Options->get_notice_levels();
		$output = array();
		$errors = array();
		$validation_value = null;
		$rgba_pattern = '/(^[a-zA-Z]+$)|(#(?:[0-9a-f]{2}){2,4}|#[0-9a-f]{3}|(?:rgba?|hsla?)\((?:\d+%?(?:deg|rad|grad|turn)?(?:,|\s)+){2,3}[\s\/]*[\d\.]+%?\))/i';

		$i = 0;
		foreach( $input as $option_key => $value ) {

			switch( $option_key ) {

				case ( $option_key === 'cursorcolor' );

					if( ! preg_match( $rgba_pattern, $value ) ) {

						$value = $defaults[$option_key];
						$errors[$option_key] = array(
							'option_key' => $option_key,
							'name' => $this->make_readable( $option_key ),
							'index' => $i,
							'notice_level' => $notice_levels[$option_key],
							'message' => $this->cursorcolor_error_message(),
						);
					}

					break;

				case ( $option_key === 'cursoropacitymin' );

					if( $value !== '' ) {

						if( ( ! ( (int) $value >= 0 ) && ! ( (int) $value <= 1.00 ) ) || ! preg_match( '/^[0-9]+(\.[0-9]{1,2})?$/', $value ) ) {

							$value = '0';
							$errors[$option_key] = array(
								'option_key' => $option_key,
								'name' => $this->make_readable( $option_key ),
								'index' => $i,
								'notice_level' => $notice_levels[$option_key],
								'message' => $this->cursoropacitymin_error_message(),
							);
						}
					}

					break;

				case ( $option_key === 'cursoropacitymax' );

					if( ( ! ( (int) $value >= 0 ) && ! ( (int) $value <= 1.00 ) ) || ! preg_match( '/^[0-9]+(\.[0-9]{1,2})?$/', $value ) ) {

						$value = $defaults[$option_key];
						$errors[$option_key] = array(
							'option_key' => $option_key,
							'name' => $this->make_readable( $option_key ),
							'index' => $i,
							'notice_level' => $notice_levels[$option_key],
							'message' => $this->cursoropacitymax_error_message(),
						);
					}

					break;

				case ( $option_key === 'cursorwidth' );

					// Pattern to remove any reasonable unit for pixels.
					$pattern = '/pixels+|pixel+|px+/i';
					if( preg_match( $pattern, $value ) ) {

						$value = preg_replace( $pattern, '', $value );

						$value = trim( $value );
					}
					// If the value is not an integer after removing any reasonable unit for pixels...
					if( ! ctype_digit( $value ) ) {

						$value = $defaults[$option_key];
						$errors[$option_key] = array(
							'option_key' => $option_key,
							'name' => $this->make_readable( $option_key ),
							'index' => $i,
							'notice_level' => $notice_levels[$option_key],
							'message' => $this->cursorwidth_error_message(),
						);
					}
					else if( $value !== 0 && ctype_digit( $value ) ) {
						// The value is an integer, and it gets the unit added again.
						$value = $this->set_unit( $option_key, $value, 'px' );
					}

					break;

				case ( $option_key === 'cursorborderwidth' );

					// Pattern to remove any reasonable unit for pixels.
					$pattern = '/pixels+|pixel+|px+/i';
					if( preg_match( $pattern, $value ) ) {

						$value = preg_replace( $pattern, '', $value );

						$value = trim( $value );
					}
					// If the value is not an integer after removing any reasonable unit for pixels...
					if( ! ctype_digit( $value ) ) {

						$value = $defaults[$option_key];
						$errors[$option_key] = array(
							'option_key' => $option_key,
							'name' => $this->make_readable( $option_key ),
							'index' => $i,
							'notice_level' => $notice_levels[$option_key],
							'message' => $this->cursorborderwidth_error_message(),
						);
					}
					else if( $value !== 0 && ctype_digit( $value ) ) {
						// The value is an integer, and it gets the unit added again.
						$value = $this->set_unit( $option_key, $value, 'px' );
					}

					break;

				case ( $option_key === 'cursorbordercolor' );

					if( isset( $value ) && ! preg_match( $rgba_pattern, $value ) ) {

						$value = $defaults[$option_key];
						$errors[$option_key] = array(
							'option_key' => $option_key,
							'name' => $this->make_readable( $option_key ),
							'index' => $i,
							'notice_level' => $notice_levels[$option_key],
							'message' => $this->cursorbordercolor_error_message(),
						);
					}

					break;

				case ( $option_key === 'cursorborderradius' );

					// Pattern to remove any reasonable unit for pixels.
					$pattern = '/pixels+|pixel+|px+/i';
					if( preg_match( $pattern, $value ) ) {

						$value = preg_replace( $pattern, '', $value );

						$value = trim( $value );
					}
					// If the value is not an integer after removing any reasonable unit for pixels...
					if( ! ctype_digit( $value ) ) {

						$value = $defaults[$option_key];
						$errors[$option_key] = array(
							'option_key' => $option_key,
							'name' => $this->make_readable( $option_key ),
							'index' => $i,
							'notice_level' => $notice_levels[$option_key],
							'message' => $this->cursorborderradius_error_message(),
						);
					}
					else if( $value !== 0 ) {
						// The value is an integer, and it gets the unit added again.
						$value = $this->set_unit( $option_key, $value, 'px' );
					}

					break;

				case( $option_key === 'zindex' );

					if( ! ctype_digit( $value ) ) {

						if( 'auto' !== $value ) {

							$value = $defaults[$option_key];
							$errors[$option_key] = array(
								'option_key' => $option_key,
								'name' => $this->make_readable( $option_key ),
								'index' => $i,
								'notice_level' => $notice_levels[$option_key],
								'message' => $this->zindex_error_message(),
							);
						}
					}

					break;

				case ( $option_key === 'scrollspeed' );

					if( $value === '0' || ! ctype_digit( $value ) ) {

						$value = $defaults[$option_key];
						$errors[$option_key] = array(
							'option_key' => $option_key,
							'name' => $this->make_readable( $option_key ),
							'index' => $i,
							'notice_level' => $notice_levels[$option_key],
							'message' => $this->scrollspeed_error_message(),
						);
					}

					break;

				case( $option_key === 'mousescrollstep' );

					if( $value === '0' || ! ctype_digit( $value ) ) {

						$value = $defaults[$option_key];
						$errors[$option_key] = array(
							'option_key' => $option_key,
							'name' => $this->make_readable( $option_key ),
							'index' => $i,
							'notice_level' => $notice_levels[$option_key],
							'message' => $this->mousescrollstep_error_message(),
						);
					}

					break;

				case ( $option_key === 'background' );

					if( '' !== $value && ! preg_match( $rgba_pattern, $value ) ) {

						$value = $defaults[$option_key];
						$errors[$option_key] = array(
							'option_key' => $option_key,
							'name' => $this->make_readable( $option_key ),
							'index' => $i,
							'notice_level' => $notice_levels[$option_key],
							'message' => $this->background_error_message(),
						);
					}

					break;

				case( $option_key === 'cursorminheight' );

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
							$errors[$option_key] = array(
								'option_key' => $option_key,
								'name' => $this->make_readable( $option_key ),
								'index' => $i,
								'notice_level' => $notice_levels[$option_key],
								'message' => $this->cursorminheight_error_message(),
							);
						}
						else {

							$value = $this->set_unit( $option_key, $value, 'px' );
						}
					}

					break;

				case( ( $option_key === 'railpaddingtop' ) || ( $option_key === 'railpaddingright' ) || ( $option_key === 'railpaddingbottom' ) || ( $option_key === 'railpaddingleft' ) );

					if( $value !== '' ) {

						if( ! ctype_digit( $value ) ) {

							$value = '';
							$errors[$option_key] = array(
								'option_key' => $option_key,
								'name' => $this->make_readable( $option_key ),
								'index' => $i,
								'notice_level' => $notice_levels[$option_key],
								'message' => $this->railpadding_error_message(),
							);
						}
						else {

							$value = $this->set_unit( $option_key, $value, 'px' );
						}
					}

					break;

				case( $option_key === 'hidecursordelay' );

					if( $value !== '' ) {

						if( ! ctype_digit( $value ) ) {

							$value = '';
							$errors[$option_key] = array(
								'option_key' => $option_key,
								'name' => $this->make_readable( $option_key ),
								'index' => $i,
								'notice_level' => $notice_levels[$option_key],
								'message' => $this->hidecursordelay_error_message(),
							);
						}
					}

					break;

				case( $option_key === 'directionlockdeadzone' );

					if( $value !== '' ) {

						// Pattern to remove any reasonable unit for pixels.
						$pattern = '/pixels+|pixel+|px+/i';
						if( preg_match( $pattern, $value ) ) {

							$value = preg_replace( $pattern, '', $value );
							$value = trim( $value );
						}
						// If the value is not an integer after removing any reasonable unit for pixels...
						if( ! ctype_digit( $value ) ) {

							$value = '';
							$errors[$option_key] = array(
								'option_key' => $option_key,
								'name' => $this->make_readable( $option_key ),
								'index' => $i,
								'notice_level' => $notice_levels[$option_key],
								'message' => $this->directionlockdeadzone_error_message(),
							);
						}
						else if( $value !== 0 ) {
							// The value is an integer, and it gets the unit added again.
							$value = $this->set_unit( $option_key, $value, 'px' );
						}
					}

					break;

				case( $option_key === 'cursordragspeed' );

					if( $value !== '' ) {

						if( ! preg_match( '/^[0-9]+(\.[0-9]{1,2})?$/', $value ) ) {

							$value = '';
							$errors[$option_key] = array(
								'option_key' => $option_key,
								'name' => $this->make_readable( $option_key ),
								'index' => $i,
								'notice_level' => $notice_levels[$option_key],
								'message' => $this->cursordragspeed_error_message(),
							);
						}
					}

					break;

				case( $option_key === 'scrollbarid' );

					if( '' || false !== $value ) {

						$value = $this->check_text_fields( $value );

						if( is_wp_error( $value ) ) {

							$value = '';
							$errors[$option_key] = array(
								'option_key' => $option_key,
								'name' => $this->make_readable( $option_key ),
								'index' => $i,
								'notice_level' => $notice_levels[$option_key],
								'message' => $this->scrollbarid_error_message(),
							);
						}
					}

					break;

				case ( $option_key === 'bt_background_color' || $option_key === 'bt_hober_background_color' );

					if( ! preg_match( $rgba_pattern, $value ) ) {

						$value = $defaults[$option_key];
						$errors[$option_key] = array(
							'option_key' => $option_key,
							'name' => $this->make_readable( $option_key ),
							'index' => $i,
							'notice_level' => $notice_levels[$option_key],
							'message' => $this->bt_background_color_error_message(),
						);
					}

					break;

				case ( $option_key === 'bt_border_color' || $option_key === 'bt_hover_border_color' );

					if( ! preg_match( $rgba_pattern, $value ) ) {

						$value = $defaults[$option_key];
						$errors[$option_key] = array(
							'option_key' => $option_key,
							'name' => $this->make_readable( $option_key ),
							'index' => $i,
							'notice_level' => $notice_levels[$option_key],
							'message' => $this->bt_border_color_error_message(),
						);
					}

					break;

				case( $option_key === 'bt_border_width' );

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
							$errors[$option_key] = array(
								'option_key' => $option_key,
								'name' => $this->make_readable( $option_key ),
								'index' => $i,
								'notice_level' => $notice_levels[$option_key],
								'message' => $this->bt_border_width_error_message(),
							);
						}
						else {

							$value = $this->set_unit( $option_key, $value, 'px' );
						}
					}

					break;

				case( $option_key === 'bt_width' );

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
							$errors[$option_key] = array(
								'option_key' => $option_key,
								'name' => $this->make_readable( $option_key ),
								'index' => $i,
								'notice_level' => $notice_levels[$option_key],
								'message' => $this->bt_width_error_message(),
							);
						}
						else {

							$value = $this->set_unit( $option_key, $value, 'px' );
						}
					}

					break;

				case( $option_key === 'bt_height' );

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
							$errors[$option_key] = array(
								'option_key' => $option_key,
								'name' => $this->make_readable( $option_key ),
								'index' => $i,
								'notice_level' => $notice_levels[$option_key],
								'message' => $this->bt_height_error_message(),
							);
						}
						else {

							$value = $this->set_unit( $option_key, $value, 'px' );
						}
					}

					break;

				case( $option_key === 'bt_posx_from_right' );

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
							$errors[$option_key] = array(
								'option_key' => $option_key,
								'name' => $this->make_readable( $option_key ),
								'index' => $i,
								'notice_level' => $notice_levels[$option_key],
								'message' => $this->bt_width_error_message(),
							);
						}
						else {

							$value = $this->set_unit( $option_key, $value, 'px' );
						}
					}

					break;

				case( $option_key === 'bt_posy_from_bottom' );

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
							$errors[$option_key] = array(
								'option_key' => $option_key,
								'name' => $this->make_readable( $option_key ),
								'index' => $i,
								'notice_level' => $notice_levels[$option_key],
								'message' => $this->bt_width_error_message(),
							);
						}
						else {

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
							$errors[$option_key] = array(
								'option_key' => $option_key,
								'name' => $this->make_readable( $option_key ),
								'index' => $i,
								'notice_level' => $notice_levels[$option_key],
								'message' => $this->bt_border_radius_error_message(),
							);
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

		// @deprecated
		/*if( get_locale() !== 'en_US' ) {
			$output = $this->translate_to_default_locale( $output );
		}*/

		// If there were errors and transients were created, we create one more containing the ids of the previously created ones.
		if( null !== $errors && ( ! empty( $errors ) ) ) {

			set_transient( 'nicescrollr_validation_transient', $errors, 60 );
		}

		return apply_filters( 'validate', $output, $input );
	}

	private function check_text_fields( $value ) {

		if( '' === $value ) {
			return $value;
		}

		if( '' !== $value && ! preg_match( '/^([A-Za-z0-9\_ ().-]-)*[A-Za-z0-9\_ ().-]+$/', $value ) ) {

			return $this->character_not_allowed_error_message();
		}

		return (string) $value;
	}

	/**
	 * Fill options that are not set and give them a specified default value.
	 *
	 * @since  0.2.1
	 *
	 * @param array $valid
	 * @param string $section
	 *
	 * @access private
	 *
	 * @return array $valid
	 */
	private function fill( $valid, $section ) {

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

	private function merge_options( $valid, $section ) {

		$options = get_option( 'nicescrollr_options' );

		unset( $options[$section] );

		$options[$section] = $valid;

		ksort( $options );

		return $options;
	}

	private function make_readable( $string ) {

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
	 * @see    admin/menu/includes/class-nsr-settings.php | translate_to_custom_locale()
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
	 * Returns the specified error message.
	 *
	 * @since   0.1.0
	 * @return \WP_Error
	 */
	public function cursorcolor_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a hexadecimal color value. It was reset to its default. To customize it, please input a color value like '#fff' or '#0073AA'.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursoropacitymin_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive number between 0 and 1, with max two decimal places (or left blank).", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursoropacitymax_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive number between 0 and 1, with max two decimal places. It was reset to it's default.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursorwidth_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive, integer pixel value including 0 (zero). It was reset to it's default.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursorborderwidth_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive, integer pixel value including 0 (zero). It was reset to it's default.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursorbordercolor_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a hexadecimal color value. (It was reset to its default.) To customize it, please input a color value like '#fff' or '#0073AA'.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursorborderradius_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive, integer pixel value or left blank. It was reset to it's default.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function zindex_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be an integer value. It was reset to it's default.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function scrollspeed_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer but must not be 0 (zero). To aviod unwanted scrolling behaviour, the scrollspeed was reset to its default. (Note: If you intended to disable the mousewheel, please visit the extended settings panel.)", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function mousescrollstep_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer but it must not be 0 (zero). To aviod unwanted scrolling behaviour, it was reset to its default.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since   0.1.0
	 * @return \WP_Error
	 */
	public function background_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a hexadecimal color value. It was reset to its default. To customize it, please input a color value like '#fff' or '#0073AA'.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since   0.1.0
	 * @return \WP_Error
	 */
	public function scrollbarid_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. Do not enter '#'. The name must look somthing like 'nice_scrollbar', 'my-cool-div', 'scrollbar123' etc. It was reset to it\'s default value.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursorminheight_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer including 0 (zero). It was reset to it's default.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function railpadding_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer including 0 (zero) or left blank.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function directionlockdeadzone_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer including 0 (zero) or left blank. To customize it, please have a look at its placeholder.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function hidecursordelay_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer including 0 (zero) or left blank. It represents the delay in miliseconds. ", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursordragspeed_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive number with max two decimal places or left blank. Please review its placeholder.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function character_not_allowed_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. Please use numbers and/or alphabetical characters.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since   0.1.0
	 * @return \WP_Error
	 */
	public function bt_background_color_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a hexadecimal color value. It was reset to its default. To customize it, please input a color value like '#fff' or '#0073AA'.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since   0.1.0
	 * @return \WP_Error
	 */
	public function bt_border_color_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a hexadecimal color value. It was reset to its default. To customize it, please input a color value like '#fff' or '#0073AA'.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function bt_border_width_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive, integer pixel value including 0 (zero). It was reset to it's default.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function bt_width_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive, integer pixel value including 0 (zero). It was reset to it's default.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function bt_height_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive, integer pixel value including 0 (zero). It was reset to it's default.", $this->domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function bt_border_radius_error_message() {

		return new \WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive, integer pixel- or percent- value including 0 (zero). It was reset to it's default.", $this->domain ) );
	}

}
