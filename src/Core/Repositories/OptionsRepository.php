<?php
/**
 * Options Repository
 *
 * Handles data persistence for plugin options using WordPress Options API.
 * Implements Repository pattern for clean separation of data access.
 *
 * @package    Nicescrollr
 * @subpackage Core\Repositories
 * @since      1.0.0
 */

namespace Nicescrollr\Core\Repositories;

use Nicescrollr\Core\Contracts\RepositoryInterface;

/**
 * Class OptionsRepository
 *
 * @package Nicescrollr\Core\Repositories
 */
class OptionsRepository implements RepositoryInterface {

	/**
	 * Option name in WordPress database
	 *
	 * @var string
	 */
	private const OPTION_NAME = 'nicescrollr_options';

	/**
	 * Cached options
	 *
	 * @var array|null
	 */
	private ?array $cache = null;

	/**
	 * Retrieve all options
	 *
	 * @return array
	 */
	public function all(): array {
		if ( $this->cache !== null ) {
			return $this->cache;
		}

		$options = get_option( self::OPTION_NAME, [] );
		$this->cache = is_array( $options ) ? $options : [];

		return $this->cache;
	}

	/**
	 * Find option by key
	 *
	 * @param string $key Option key (supports dot notation: 'frontend.enabled')
	 * @return mixed|null
	 */
	public function find( string $key ) {
		$options = $this->all();

		// Support dot notation for nested keys
		if ( strpos( $key, '.' ) !== false ) {
			return $this->getNestedValue( $options, $key );
		}

		return $options[ $key ] ?? null;
	}

	/**
	 * Save option
	 *
	 * @param string $key Option key
	 * @param mixed $value Value to save
	 * @return bool Success status
	 */
	public function save( string $key, $value ): bool {
		$options = $this->all();

		// Support dot notation for nested keys
		if ( strpos( $key, '.' ) !== false ) {
			$options = $this->setNestedValue( $options, $key, $value );
		} else {
			$options[ $key ] = $value;
		}

		$result = update_option( self::OPTION_NAME, $options );

		// Update cache
		if ( $result ) {
			$this->cache = $options;
		}

		return $result;
	}

	/**
	 * Save all options at once
	 *
	 * @param array $options Complete options array
	 * @return bool Success status
	 */
	public function saveAll( array $options ): bool {
		$result = update_option( self::OPTION_NAME, $options );

		if ( $result ) {
			$this->cache = $options;
		}

		return $result;
	}

	/**
	 * Delete option
	 *
	 * @param string $key Option key
	 * @return bool Success status
	 */
	public function delete( string $key ): bool {
		$options = $this->all();

		if ( ! isset( $options[ $key ] ) ) {
			return false;
		}

		unset( $options[ $key ] );

		$result = update_option( self::OPTION_NAME, $options );

		if ( $result ) {
			$this->cache = $options;
		}

		return $result;
	}

	/**
	 * Check if option exists
	 *
	 * @param string $key Option key
	 * @return bool
	 */
	public function exists( string $key ): bool {
		$options = $this->all();

		if ( strpos( $key, '.' ) !== false ) {
			return $this->getNestedValue( $options, $key ) !== null;
		}

		return isset( $options[ $key ] );
	}

	/**
	 * Reset all options to defaults
	 *
	 * @param array $defaults Default values
	 * @return bool Success status
	 */
	public function reset( array $defaults ): bool {
		$result = update_option( self::OPTION_NAME, $defaults );

		if ( $result ) {
			$this->cache = $defaults;
		}

		return $result;
	}

	/**
	 * Clear the cache
	 *
	 * @return void
	 */
	public function clearCache(): void {
		$this->cache = null;
	}

	/**
	 * Get nested value using dot notation
	 *
	 * @param array $array Source array
	 * @param string $key Dot-notated key
	 * @return mixed|null
	 */
	private function getNestedValue( array $array, string $key ) {
		$keys = explode( '.', $key );

		foreach ( $keys as $segment ) {
			if ( ! is_array( $array ) || ! isset( $array[ $segment ] ) ) {
				return null;
			}
			$array = $array[ $segment ];
		}

		return $array;
	}

	/**
	 * Set nested value using dot notation
	 *
	 * @param array $array Target array
	 * @param string $key Dot-notated key
	 * @param mixed $value Value to set
	 * @return array Modified array
	 */
	private function setNestedValue( array $array, string $key, $value ): array {
		$keys = explode( '.', $key );
		$current = &$array;

		foreach ( $keys as $i => $segment ) {
			if ( $i === count( $keys ) - 1 ) {
				$current[ $segment ] = $value;
			} else {
				if ( ! isset( $current[ $segment ] ) || ! is_array( $current[ $segment ] ) ) {
					$current[ $segment ] = [];
				}
				$current = &$current[ $segment ];
			}
		}

		return $array;
	}
}
