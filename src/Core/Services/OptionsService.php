<?php
/**
 * Options Service
 *
 * Business logic layer for managing plugin options.
 * Orchestrates between repository, configuration, and validation.
 *
 * @package    Nicescrollr
 * @subpackage Core\Services
 * @since      1.0.0
 */

namespace Nicescrollr\Core\Services;

use Nicescrollr\Core\Contracts\ServiceInterface;
use Nicescrollr\Core\Contracts\ConfigInterface;
use Nicescrollr\Core\Contracts\ValidatorInterface;
use Nicescrollr\Core\Repositories\OptionsRepository;

/**
 * Class OptionsService
 *
 * @package Nicescrollr\Core\Services
 */
class OptionsService implements ServiceInterface {

	/**
	 * Options repository
	 *
	 * @var OptionsRepository
	 */
	private OptionsRepository $repository;

	/**
	 * Configuration
	 *
	 * @var ConfigInterface
	 */
	private ConfigInterface $config;

	/**
	 * Validator
	 *
	 * @var ValidatorInterface
	 */
	private ValidatorInterface $validator;

	/**
	 * Constructor
	 *
	 * @param OptionsRepository $repository Options repository
	 * @param ConfigInterface $config Configuration
	 * @param ValidatorInterface $validator Validator
	 */
	public function __construct(
		OptionsRepository $repository,
		ConfigInterface $config,
		ValidatorInterface $validator
	) {
		$this->repository = $repository;
		$this->config = $config;
		$this->validator = $validator;
	}

	/**
	 * Initialize the service
	 *
	 * @return void
	 */
	public function boot(): void {
		$this->ensureOptionsExist();
	}

	/**
	 * Register WordPress hooks
	 *
	 * @return void
	 */
	public function register(): void {
		// Hook into WordPress option updates
		add_filter( 'pre_update_option_nicescrollr_options', [ $this, 'validateBeforeSave' ], 10, 2 );
	}

	/**
	 * Get all options for a context
	 *
	 * @param string $context 'frontend' or 'backend'
	 * @return array
	 */
	public function getOptions( string $context = 'frontend' ): array {
		return $this->repository->find( $context ) ?? $this->config->defaults( $context );
	}

	/**
	 * Get a specific option value
	 *
	 * @param string $key Option key (supports dot notation)
	 * @param mixed $default Default value
	 * @return mixed
	 */
	public function get( string $key, $default = null ) {
		$value = $this->repository->find( $key );
		return $value !== null ? $value : $default;
	}

	/**
	 * Update an option
	 *
	 * @param string $key Option key
	 * @param mixed $value New value
	 * @return bool Success status
	 */
	public function update( string $key, $value ): bool {
		// Get validation rules from config
		$optionKey = explode( '.', $key )[1] ?? $key;
		$schema = $this->config->get( $optionKey );

		if ( $schema && isset( $schema['validation'] ) ) {
			if ( ! $this->validator->validate( $value, $schema['validation'] ) ) {
				return false;
			}

			// Sanitize based on type
			$type = $schema['type'] ?? 'text';
			$value = $this->validator->sanitize( $value, $type );
		}

		return $this->repository->save( $key, $value );
	}

	/**
	 * Reset options to defaults
	 *
	 * @param string|null $context Specific context to reset, or null for all
	 * @return bool Success status
	 */
	public function reset( ?string $context = null ): bool {
		if ( $context ) {
			$defaults = [ $context => $this->config->defaults( $context ) ];
			return $this->repository->save( $context, $defaults[ $context ] );
		}

		// Reset all
		$defaults = [
			'frontend' => $this->config->defaults( 'frontend' ),
			'backend' => $this->config->defaults( 'backend' ),
		];

		return $this->repository->saveAll( $defaults );
	}

	/**
	 * Validate options before saving
	 *
	 * @param mixed $value New value
	 * @param mixed $old_value Old value
	 * @return mixed Validated value
	 */
	public function validateBeforeSave( $value, $old_value ) {
		if ( ! is_array( $value ) ) {
			return $old_value;
		}

		$validated = [];

		foreach ( $value as $context => $options ) {
			if ( ! is_array( $options ) ) {
				continue;
			}

			foreach ( $options as $key => $val ) {
				$schema = $this->config->get( $key );

				if ( ! $schema ) {
					continue;
				}

				// Validate
				if ( isset( $schema['validation'] ) ) {
					if ( $this->validator->validate( $val, $schema['validation'] ) ) {
						$type = $schema['type'] ?? 'text';
						$validated[ $context ][ $key ] = $this->validator->sanitize( $val, $type );
					} else {
						// Use old value if validation fails
						$validated[ $context ][ $key ] = $old_value[ $context ][ $key ] ?? $this->config->defaults( $context )[ $key ] ?? null;
					}
				} else {
					$validated[ $context ][ $key ] = $val;
				}
			}
		}

		return $validated;
	}

	/**
	 * Ensure options exist in database
	 *
	 * @return void
	 */
	private function ensureOptionsExist(): void {
		$options = $this->repository->all();

		if ( empty( $options ) || ! isset( $options['frontend'], $options['backend'] ) ) {
			$this->reset();
		}
	}
}
