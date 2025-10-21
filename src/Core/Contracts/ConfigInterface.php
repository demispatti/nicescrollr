<?php
/**
 * Configuration Interface
 *
 * Defines the contract for configuration management.
 * Supports immutable configuration with type safety.
 *
 * @package    Nicescrollr
 * @subpackage Core\Contracts
 * @since      1.0.0
 */

namespace Nicescrollr\Core\Contracts;

/**
 * Interface ConfigInterface
 *
 * @package Nicescrollr\Core\Contracts
 */
interface ConfigInterface {

	/**
	 * Get configuration value
	 *
	 * @param string $key Configuration key (supports dot notation)
	 * @param mixed $default Default value if key doesn't exist
	 * @return mixed
	 */
	public function get( string $key, $default = null );

	/**
	 * Check if configuration key exists
	 *
	 * @param string $key Configuration key
	 * @return bool
	 */
	public function has( string $key ): bool;

	/**
	 * Get all configuration
	 *
	 * @return array
	 */
	public function all(): array;

	/**
	 * Get configuration schema
	 *
	 * @return array
	 */
	public function schema(): array;
}
