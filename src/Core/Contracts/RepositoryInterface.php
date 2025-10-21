<?php
/**
 * Repository Interface
 *
 * Defines the contract for data persistence operations.
 * Follows Repository pattern for clean separation between business logic and data access.
 *
 * @package    Nicescrollr
 * @subpackage Core\Contracts
 * @since      1.0.0
 */

namespace Nicescrollr\Core\Contracts;

/**
 * Interface RepositoryInterface
 *
 * @package Nicescrollr\Core\Contracts
 */
interface RepositoryInterface {

	/**
	 * Retrieve all items
	 *
	 * @return array
	 */
	public function all(): array;

	/**
	 * Find item by key
	 *
	 * @param string $key The item key
	 * @return mixed|null
	 */
	public function find( string $key );

	/**
	 * Save item
	 *
	 * @param string $key The item key
	 * @param mixed $value The value to save
	 * @return bool Success status
	 */
	public function save( string $key, $value ): bool;

	/**
	 * Delete item
	 *
	 * @param string $key The item key
	 * @return bool Success status
	 */
	public function delete( string $key ): bool;

	/**
	 * Check if item exists
	 *
	 * @param string $key The item key
	 * @return bool
	 */
	public function exists( string $key ): bool;
}
