<?php
/**
 * Service Interface
 *
 * Base contract for service layer classes.
 * Services encapsulate business logic and orchestrate operations.
 *
 * @package    Nicescrollr
 * @subpackage Core\Contracts
 * @since      1.0.0
 */

namespace Nicescrollr\Core\Contracts;

/**
 * Interface ServiceInterface
 *
 * @package Nicescrollr\Core\Contracts
 */
interface ServiceInterface {

	/**
	 * Initialize the service
	 *
	 * @return void
	 */
	public function boot(): void;

	/**
	 * Register service hooks with WordPress
	 *
	 * @return void
	 */
	public function register(): void;
}
