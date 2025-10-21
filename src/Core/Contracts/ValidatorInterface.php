<?php
/**
 * Validator Interface
 *
 * Defines the contract for input validation.
 * Supports strategy pattern for flexible validation rules.
 *
 * @package    Nicescrollr
 * @subpackage Core\Contracts
 * @since      1.0.0
 */

namespace Nicescrollr\Core\Contracts;

/**
 * Interface ValidatorInterface
 *
 * @package Nicescrollr\Core\Contracts
 */
interface ValidatorInterface {

	/**
	 * Validate input data
	 *
	 * @param mixed $value The value to validate
	 * @param array $rules Validation rules
	 * @return bool True if valid
	 */
	public function validate( $value, array $rules ): bool;

	/**
	 * Get validation errors
	 *
	 * @return array Array of error messages
	 */
	public function errors(): array;

	/**
	 * Sanitize input value
	 *
	 * @param mixed $value The value to sanitize
	 * @param string $type The sanitization type
	 * @return mixed Sanitized value
	 */
	public function sanitize( $value, string $type );
}
