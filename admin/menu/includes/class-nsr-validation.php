<?php

/**
 * The class responsible for sanitizing and validating the user inputs.
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
class nsr_validation {

	/**
	 * The array holding the application keys.
	 *
	 * @since 0.1.0
	 * @acces private
	 * @var   array $keys
	 */
	private $keys;

	/**
	 * The reference to the options class.

     *
*@since  0.1.0
	 * @access private
	 * @var    object $Options
	 */
	private $Options;

	/**
	 * The name of the section to be validated.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $section
	 */
	private $section;

	/**
	 * Assigns the required parameters to its instance.
     *
     * @since 0.1.0
	 * @param array $keys
	 * @param object $Options
	 * @param string $section
	 */
	public function __construct( $keys, $Options, $section = false ) {

		$this->keys    = $keys;
		$this->Options = $Options;
		$this->section = $section;
	}

	/**
	 * Kicks off sanitisation and validation - if there's any input given.
	 *
	 * @since  0.1.0
	 * @param  array $input
	 * @return array $output
	 */
	public function run( $input, $section ) {

		if ( isset( $input ) && false === $section ) {

			$output = $input;

			return $output;
		} else if( isset( $input ) && isset( $section ) ) {

			$input = $this->sanitize( $input );
			$valid = $this->validate( $input, $section );

			return $this->merge_options( $valid, $section );
		} else {

			return true;
		}
	}

	/**
	 * Sanitizes the input.
	 *
	 * @since  0.1.0
	 * @param  array $input
	 * @return array $output
	 */
	private function sanitize( $input ) {

		$output = array();

		foreach ( $input as $key => $value ) {

			if ( isset ( $input[ $key ] ) ) {
				$output[ $key ] = strip_tags( stripslashes( $value ) );
			}
		}

		return apply_filters( 'sanitize', $output, $input );
	}

	/**
	 * Validates the input.
     *
     * since  0.1.0
	 * @uses   get_default_options()
	 * @see    admin/menu/includes/class-nsr-options.php
	 * @uses   translate_to_default_locale()
	 * @param  array $input
	 * @return array $output
	 */
	private function validate( $input, $section ) {

		$defaults = $this->Options->get_default_options( $section );
		$notice_levels = $this->Options->get_notice_levels();
		$output   = [ ];
		$errors   = [];

		$i = 0;
		foreach ( $input as $option_key => $value ) {

			switch ( $option_key ) {

				case ( $option_key === 'cursorcolor' );

					if ( ! preg_match( '/^#[a-f0-9]{3,6}$/i', $value ) ) {

						$value = $defaults[ $option_key ];
						$errors[ $option_key ] = array(
							'option_key'   => $option_key,
							'index'        => $i,
							'notice_level' => $notice_levels[ $option_key ],
							'message'      => $this->cursorcolor_error_message()
						);
					}

					break;

				case ( $option_key === 'cursoropacitymin' );

					if ( $value !== '' ) {

						if ( ! preg_match( '/^[0-9]+(\.[0-9]{1,2})?$/', $value ) || ( ! ( (int) $value >= 0 ) && ! ( (int) $value <= 1.00 ) ) ) {

							$value = '0';
							$errors[ $option_key ] = array(
								'option_key'   => $option_key,
								'index'        => $i,
								'notice_level' => $notice_levels[ $option_key ],
								'message'      => $this->cursoropacitymin_error_message()
							);
						}
					}

					break;

				case ( $option_key === 'cursoropacitymax' );

					if ( ! preg_match( '/^[0-9]+(\.[0-9]{1,2})?$/', $value ) || ( ! ( (int) $value >= 0 ) && ! ( (int) $value <= 1.00 ) ) ) {

						$value = $defaults[ $option_key ];
						$errors[ $option_key ] = array(
							'option_key'   => $option_key,
							'index'        => $i,
							'notice_level' => $notice_levels[ $option_key ],
							'message'      => $this->cursoropacitymax_error_message()
						);
					}

					break;

				case ( $option_key === 'cursorwidth' );

					// Pattern to remove any reasonable unit for pixels.
					$pattern = '/pixels+|pixel+|px+/i';
					if ( preg_match( $pattern, $value ) ) {

						$value = preg_replace( $pattern, '', $value );

						$value = trim( $value );
					}
					// If the value is not an integer after removing any reasonable unit for pixels...
					if ( ! ctype_digit( $value ) ) {

						$value = $defaults[ $option_key ];
						$errors[ $option_key ] = array(
							'option_key'   => $option_key,
							'index'        => $i,
							'notice_level' => $notice_levels[ $option_key ],
							'message'      => $this->cursorwidth_error_message()
						);
					} else if ( ctype_digit( $value ) && $value != 0 ) {
						// The value is an integer, and it gets the unit added again.
						$value = $value . 'px';
					}

					break;

				case ( $option_key === 'cursorborderwidth' );

					// Pattern to remove any reasonable unit for pixels.
					$pattern = '/pixels+|pixel+|px+/i';
					if ( preg_match( $pattern, $value ) ) {

						$value = preg_replace( $pattern, '', $value );

						$value = trim( $value );
					}
					// If the value is not an integer after removing any reasonable unit for pixels...
					if ( ! ctype_digit( $value ) ) {

						$value = $defaults[ $option_key ];
						$errors[ $option_key ] = array(
							'option_key'   => $option_key,
							'index'        => $i,
							'notice_level' => $notice_levels[ $option_key ],
							'message'      => $this->cursorborderwidth_error_message()
						);
					} else if ( ctype_digit( $value ) && $value != 0 ) {
						// The value is an integer, and it gets the unit added again.
						$value = $value . 'px';
					}

					break;

				case ( $option_key === 'cursorbordercolor' );

					if ( isset( $value ) && ! preg_match( '/^#[a-f0-9]{3,6}$/i', $value ) ) {

						$value = $defaults[ $option_key ];
						$errors[ $option_key ] = array(
							'option_key'   => $option_key,
							'index'        => $i,
							'notice_level' => $notice_levels[ $option_key ],
							'message'      => $this->cursorbordercolor_error_message()
						);
					}

					break;

				case ( $option_key === 'cursorborderradius' );

					// Pattern to remove any reasonable unit for pixels.
					$pattern = '/pixels+|pixel+|px+/i';
					if ( preg_match( $pattern, $value ) ) {

						$value = preg_replace( $pattern, '', $value );

						$value = trim( $value );
					}
					// If the value is not an integer after removing any reasonable unit for pixels...
					if ( ! ctype_digit( $value ) ) {

						$value = $defaults[ $option_key ];
						$errors[ $option_key ] = array(
							'option_key'   => $option_key,
							'index'        => $i,
							'notice_level' => $notice_levels[ $option_key ],
							'message'      => $this->cursorborderradius_error_message()
						);
					} else if ( ctype_digit( $value ) && $value != 0 ) {
						// The value is an integer, and it gets the unit added again.
						$value = $value . 'px';
					}

					break;

				case( $option_key === 'zindex' );

					if ( ! ctype_digit( $value ) ) {

						if ( ! ctype_digit( ltrim( $value, '-' ) ) ) {

							$value = $defaults[ $option_key ];
							$errors[ $option_key ] = array(
								'option_key'   => $option_key,
								'index'        => $i,
								'notice_level' => $notice_levels[ $option_key ],
								'message'      => $this->zindex_error_message()
							);
						}
					}

					break;

				case ( $option_key === 'scrollspeed' );

					if ( ! ctype_digit( $value ) || $value === '0' ) {

						$value = $defaults[ $option_key ];
						$errors[ $option_key ] = array(
							'option_key'   => $option_key,
							'index'        => $i,
							'notice_level' => $notice_levels[ $option_key ],
							'message'      => $this->scrollspeed_error_message()
						);
					}

					break;

				case( $option_key === 'mousescrollstep' );

					if ( ! ctype_digit( $value ) || $value === '0' ) {

						$value = $defaults[ $option_key ];
						$errors[ $option_key ] = array(
							'option_key'   => $option_key,
							'index'        => $i,
							'notice_level' => $notice_levels[ $option_key ],
							'message'      => $this->mousescrollstep_error_message()
						);
					}

					break;

				case( $option_key === 'cursorminheight' );

					if ( $value !== '' ) {

						// Pattern to remove any reasonable unit for pixels.
						$pattern = '/pixels+|pixel+|px+/i';
						if ( preg_match( $pattern, $value ) ) {

							$value = preg_replace( $pattern, '', $value );

							$value = trim( $value );
						}
						// If the value is not an integer after removing any reasonable unit for pixels...
						if ( ! ctype_digit( $value ) ) {

							$value = $defaults[ $option_key ];
							$errors[ $option_key ] = array(
								'option_key'   => $option_key,
								'index'        => $i,
								'notice_level' => $notice_levels[ $option_key ],
								'message'      => $this->cursorminheight_error_message()
							);
						}
					}

					break;

				case( ( $option_key === 'railpaddingtop' ) || ( $option_key === 'railpaddingright' ) ||
				      ( $option_key === 'railpaddingbottom' ) || ( $option_key === 'railpaddingleft' ) );

					if ( $value !== '' ) {

						if ( ! ctype_digit( $value ) ) {

							$value = '';
							$errors[ $option_key ] = array(
								'option_key'   => $option_key,
								'index'        => $i,
								'notice_level' => $notice_levels[ $option_key ],
								'message'      => $this->railpadding_error_message()
							);
						}
					}

					break;

				case( $option_key === 'hidecursordelay' );

					if ( $value !== '' ) {

						if ( ! ctype_digit( $value ) ) {

							$value = '';
							$errors[ $option_key ] = array(
								'option_key'   => $option_key,
								'index'        => $i,
								'notice_level' => $notice_levels[ $option_key ],
								'message'      => $this->hidecursordelay_error_message()
							);
						}
					}

					break;

				case( $option_key === 'directionlockdeadzone' );

					if ( $value !== '' ) {

						// Pattern to remove any reasonable unit for pixels.
						$pattern = '/pixels+|pixel+|px+/i';
						if ( preg_match( $pattern, $value ) ) {

							$value = preg_replace( $pattern, '', $value );

							$value = trim( $value );
						}
						// If the value is not an integer after removing any reasonable unit for pixels...
						if ( ! ctype_digit( $value ) ) {

							$value = '';
							$errors[ $option_key ] = array(
								'option_key'   => $option_key,
								'index'        => $i,
								'notice_level' => $notice_levels[ $option_key ],
								'message'      => $this->directionlockdeadzone_error_message()
							);
						} else if ( ctype_digit( $value ) && $value != 0 ) {
							// The value is an integer, and it gets the unit added again.
							$value = $value . 'px';
						}
					}

					break;

				case( $option_key === 'cursordragspeed' );

					if ( $value !== '' ) {

						if ( ! preg_match( '/^[0-9]+(\.[0-9]{1,2})?$/', $value ) ) {

							$value = '';
							$errors[ $option_key ] = array(
								'option_key'   => $option_key,
								'index'        => $i,
								'notice_level' => $notice_levels[ $option_key ],
								'message'      => $this->cursordragspeed_error_message()
							);
						}
					}

					break;
			}
			// The array holding the processed values.
			$output[ $option_key ] = $value;
			$i++;

		}

		// Fill unset options with "false".
		foreach( $defaults as $key => $value ) {

			$output[ $key ] = isset( $output[ $key ] ) ? $output[ $key ] : false;
		}

		if ( get_locale() !== 'en_US' ) {

			$output = $this->translate_to_default_locale( $output );
		}

		// If there were errors and transients were created, we create one more containing the ids of the previously created ones.
		if ( isset( $errors ) && ( ! empty( $errors ) ) ) {

			set_transient( $this->keys['validation_transient'], $errors, 60 );
		}

		return apply_filters( 'validate', $output, $input );
	}

	private function merge_options( $valid, $section ) {

		$options = get_option( $this->keys['option_group'] );

		unset( $options[ $section  ] );

		$options[ $section ] = $valid;

		ksort( $options );

		return $options;
	}

	/**
	 * Helper function, that translates "non-default-locale strings" into strings of the default locale.
	 * This task is necessary, since Nicescroll needs some strings as parameters and they have to be served in English.
	 * With this step, localisation remains fully functional.
	 *
	 * @since  0.1.0
	 * @access private
	 * @see    admin/menu/includes/class-nsr-settings.php | translate_to_custom_locale()
	 * @param  $input
	 * @return mixed|void
	 */
	public function translate_to_default_locale( $input ) {

		$output = [];

		foreach( $input as $option_key => $value ) {

			switch ( $option_key ) {

				case( $option_key === 'cursorborderstate' );
					if ( isset( $value ) && $value == __( 'none', $this->keys['plugin_domain'] ) ) {

						$output[ $option_key ] = 'none';
					} else {
						$output[ $option_key ] = $value;
					}
					break;

				case( $option_key === 'autohidemode' );

					if ( isset( $value ) && $value == __( 'off', $this->keys['plugin_domain'] ) ) {

						$output[ $option_key ] = 'off';
					} else if ( isset( $value ) && $value == __( 'on', $this->keys['plugin_domain'] ) ) {

						$output[ $option_key ] = 'on';
					} else if ( isset( $value ) && $value == __( 'cursor', $this->keys['plugin_domain'] ) ) {

						$output[ $option_key ] = 'cursor';
					} else {
						$output[ $option_key ] = $value;
					}
					break;

				case( $option_key === 'railoffset' );

					if ( isset( $value ) && $value == __( 'off', $this->keys['plugin_domain'] ) ) {

						$output[ $option_key ] = 'off';
					} else if ( isset( $value ) && $value == __( 'top', $this->keys['plugin_domain'] ) ) {

						$output[ $option_key ] = 'top';
					} else if ( isset( $value ) && $value == __( 'left', $this->keys['plugin_domain'] ) ) {

						$output[ $option_key ] = 'left';
					} else {
						$output[ $option_key ] = $value;
					}
					break;

				case( $option_key === 'railalign' );

					if ( isset( $value ) && $value == __( 'right', $this->keys['plugin_domain'] ) ) {

						$output[ $option_key ] = 'right';
					} else if ( isset( $value ) && $value == __( 'left', $this->keys['plugin_domain'] ) ) {

						$output[ $option_key ] = 'left';
					} else {
						$output[ $option_key ] = $value;
					}
					break;

				case( $option_key === 'railvalign' );

					if ( isset( $value ) && $value == __( 'bottom', $this->keys['plugin_domain'] ) ) {

						$output[ $option_key ] = 'bottom';
					} else if ( isset( $value ) && $value == __( 'top', $this->keys['plugin_domain'] ) ) {

						$output[ $option_key ] = 'top';
					} else {
						$output[ $option_key ] = $value;
					}
					break;

				case( $option_key === 'cursorfixedheight' );

					if ( isset( $value ) && $value == __( 'off', $this->keys['plugin_domain'] ) ) {

						$output[ $option_key ] = 'off';
					} else {
						$output[ $option_key ] = $value;
					}
					break;

				default:
					$output[ $option_key ] = $value;
			}
		}

		return apply_filters( 'translate_to_default_locale', $output, $input );
	}

	/* ------------------------------------------------------------------------ *
	 * Error Messages
	 * ------------------------------------------------------------------------ */
	/**
	 * Returns the specified error message.
	 *
	 * @since   0.1.0
	 * @return \WP_Error
	 */
	public function cursorcolor_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a hexadecimal color value. It was reset to its default. To customize it, please input a color value like '#fff' or '#0073AA'.", $this->keys['plugin_domain'] ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursoropacitymin_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive number between 0 and 1, with max two decimal places (or left blank).", $this->keys['plugin_domain'] ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursoropacitymax_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive number between 0 and 1, with max two decimal places. It was reset to it's default.", $this->keys['plugin_domain'] ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursorwidth_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive, integer pixel value including 0 (zero). It was reset to it's default.", $this->keys['plugin_domain'] ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursorborderwidth_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive, integer pixel value including 0 (zero). It was reset to it's default.", $this->keys['plugin_domain'] ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursorbordercolor_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a hexadecimal color value. (It was reset to its default.) To customize it, please input a color value like '#fff' or '#0073AA'.", $this->keys['plugin_domain'] ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursorborderradius_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive, integer pixel value or left blank. It was reset to it's default.", $this->keys['plugin_domain'] ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function zindex_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be an integer value. It was reset to it's default.", $this->keys['plugin_domain'] ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function scrollspeed_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer but must not be 0 (zero). To aviod unwanted scrolling behaviour, the scrollspeed was reset to its default. (Note: If you intended to disable the mousewheel, please visit the extended settings panel.)", $this->keys['plugin_domain'] ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function mousescrollstep_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer but it must not be 0 (zero). To aviod unwanted scrolling behaviour, it was reset to its default.", $this->keys['plugin_domain'] ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursorminheight_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer including 0 (zero). It was reset to it's default.", $this->keys['plugin_domain'] ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function railpadding_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer including 0 (zero) or left blank.", $this->keys['plugin_domain'] ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function directionlockdeadzone_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer including 0 (zero) or left blank. To customize it, please have a look at its placeholder.", $this->keys['plugin_domain'] ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function hidecursordelay_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer including 0 (zero) or left blank. It represents the delay in miliseconds. ", $this->keys['plugin_domain'] ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursordragspeed_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive number with max two decimal places or left blank. Please review its placeholder.", $this->keys['plugin_domain'] ) );
	}

}
