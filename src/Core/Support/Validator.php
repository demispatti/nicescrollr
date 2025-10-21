<?php
/**
 * Validator
 *
 * Handles input validation and sanitization using WordPress functions.
 * Supports strategy pattern with configurable validation rules.
 *
 * @package    Nicescrollr
 * @subpackage Core\Support
 * @since      1.0.0
 */

namespace Nicescrollr\Core\Support;

use Nicescrollr\Core\Contracts\ValidatorInterface;

/**
 * Class Validator
 *
 * @package Nicescrollr\Core\Support
 */
class Validator implements ValidatorInterface {

	/**
	 * Validation errors
	 *
	 * @var array
	 */
	private array $errors = [];

	/**
	 * Validate input against rules
	 *
	 * @param mixed $value Value to validate
	 * @param array $rules Validation rules
	 * @return bool True if valid
	 */
	public function validate( $value, array $rules ): bool {
		$this->errors = [];

		foreach ( $rules as $rule ) {
			$result = $this->applyRule( $value, $rule );

			if ( ! $result ) {
				$this->errors[] = sprintf( 'Validation failed for rule: %s', $rule );
				return false;
			}
		}

		return true;
	}

	/**
	 * Get validation errors
	 *
	 * @return array
	 */
	public function errors(): array {
		return $this->errors;
	}

	/**
	 * Sanitize value based on type
	 *
	 * @param mixed $value Value to sanitize
	 * @param string $type Sanitization type
	 * @return mixed Sanitized value
	 */
	public function sanitize( $value, string $type ) {
		switch ( $type ) {
			case 'text':
			case 'select':
				return sanitize_text_field( $value );

			case 'email':
				return sanitize_email( $value );

			case 'url':
				return esc_url_raw( $value );

			case 'number':
				return is_numeric( $value ) ? floatval( $value ) : 0;

			case 'checkbox':
			case 'boolean':
				return (bool) $value;

			case 'color':
				return $this->sanitizeColor( $value );

			case 'dimension':
				return $this->sanitizeDimension( $value );

			case 'textarea':
				return sanitize_textarea_field( $value );

			default:
				return sanitize_text_field( $value );
		}
	}

	/**
	 * Apply a single validation rule
	 *
	 * @param mixed $value Value to validate
	 * @param string $rule Validation rule
	 * @return bool
	 */
	private function applyRule( $value, string $rule ): bool {
		// Parse rule and parameters
		$parts = explode( ':', $rule, 2 );
		$ruleName = $parts[0];
		$params = isset( $parts[1] ) ? explode( ',', $parts[1] ) : [];

		switch ( $ruleName ) {
			case 'required':
				return ! empty( $value );

			case 'numeric':
				return is_numeric( $value );

			case 'boolean':
				return is_bool( $value ) || in_array( $value, [ 0, 1, '0', '1', true, false ], true );

			case 'min':
				return is_numeric( $value ) && $value >= floatval( $params[0] );

			case 'max':
				return is_numeric( $value ) && $value <= floatval( $params[0] );

			case 'between':
				return is_numeric( $value )
					&& $value >= floatval( $params[0] )
					&& $value <= floatval( $params[1] );

			case 'in':
				return in_array( $value, $params, true );

			case 'color':
				return $this->isValidColor( $value );

			case 'dimension':
				return $this->isValidDimension( $value );

			case 'email':
				return is_email( $value );

			case 'url':
				return filter_var( $value, FILTER_VALIDATE_URL ) !== false;

			default:
				return true;
		}
	}

	/**
	 * Validate color format
	 *
	 * @param mixed $value Color value
	 * @return bool
	 */
	private function isValidColor( $value ): bool {
		if ( ! is_string( $value ) ) {
			return false;
		}

		// Hex color
		if ( preg_match( '/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value ) ) {
			return true;
		}

		// RGB/RGBA
		if ( preg_match( '/^rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*(,\s*[\d.]+\s*)?\)$/i', $value ) ) {
			return true;
		}

		// Named colors
		$namedColors = [ 'transparent', 'black', 'white', 'red', 'blue', 'green', 'yellow' ];
		return in_array( strtolower( $value ), $namedColors, true );
	}

	/**
	 * Validate dimension format (e.g., "10px", "2em")
	 *
	 * @param mixed $value Dimension value
	 * @return bool
	 */
	private function isValidDimension( $value ): bool {
		if ( ! is_string( $value ) && ! is_numeric( $value ) ) {
			return false;
		}

		return preg_match( '/^\d+(\.\d+)?(px|em|rem|%)?$/i', $value ) === 1;
	}

	/**
	 * Sanitize color value
	 *
	 * @param mixed $value Color value
	 * @return string
	 */
	private function sanitizeColor( $value ): string {
		if ( ! $this->isValidColor( $value ) ) {
			return '';
		}

		return sanitize_text_field( $value );
	}

	/**
	 * Sanitize dimension value
	 *
	 * @param mixed $value Dimension value
	 * @return string
	 */
	private function sanitizeDimension( $value ): string {
		if ( ! $this->isValidDimension( $value ) ) {
			return '0px';
		}

		return sanitize_text_field( $value );
	}
}
