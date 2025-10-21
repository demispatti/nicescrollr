<?php
/**
 * Dependency Injection Container
 *
 * Simple but powerful DI container for managing dependencies.
 * Supports singletons, factories, and automatic resolution.
 *
 * @package    Nicescrollr
 * @subpackage Core\Support
 * @since      1.0.0
 */

namespace Nicescrollr\Core\Support;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionException;

/**
 * Class Container
 *
 * @package Nicescrollr\Core\Support
 */
class Container {

	/**
	 * Container instance (singleton)
	 *
	 * @var Container|null
	 */
	private static ?Container $instance = null;

	/**
	 * Registered bindings
	 *
	 * @var array
	 */
	private array $bindings = [];

	/**
	 * Singleton instances
	 *
	 * @var array
	 */
	private array $instances = [];

	/**
	 * Get container instance
	 *
	 * @return Container
	 */
	public static function getInstance(): Container {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Bind a class or interface to implementation
	 *
	 * @param string $abstract Class or interface name
	 * @param Closure|string|null $concrete Implementation
	 * @param bool $singleton Whether to treat as singleton
	 * @return void
	 */
	public function bind( string $abstract, $concrete = null, bool $singleton = false ): void {
		if ( $concrete === null ) {
			$concrete = $abstract;
		}

		$this->bindings[ $abstract ] = [
			'concrete' => $concrete,
			'singleton' => $singleton,
		];
	}

	/**
	 * Bind as singleton
	 *
	 * @param string $abstract Class or interface name
	 * @param Closure|string|null $concrete Implementation
	 * @return void
	 */
	public function singleton( string $abstract, $concrete = null ): void {
		$this->bind( $abstract, $concrete, true );
	}

	/**
	 * Register an existing instance
	 *
	 * @param string $abstract Class or interface name
	 * @param mixed $instance Instance to register
	 * @return void
	 */
	public function instance( string $abstract, $instance ): void {
		$this->instances[ $abstract ] = $instance;
	}

	/**
	 * Resolve a class from the container
	 *
	 * @param string $abstract Class or interface name
	 * @param array $parameters Constructor parameters
	 * @return mixed Resolved instance
	 * @throws Exception If unable to resolve
	 */
	public function make( string $abstract, array $parameters = [] ) {
		// Return existing singleton instance
		if ( isset( $this->instances[ $abstract ] ) ) {
			return $this->instances[ $abstract ];
		}

		// Get concrete implementation
		$concrete = $this->getConcrete( $abstract );

		// Build the instance
		$object = $this->build( $concrete, $parameters );

		// Store singleton if needed
		if ( isset( $this->bindings[ $abstract ] ) && $this->bindings[ $abstract ]['singleton'] ) {
			$this->instances[ $abstract ] = $object;
		}

		return $object;
	}

	/**
	 * Resolve and call a method with dependency injection
	 *
	 * @param callable $callback Callback to invoke
	 * @param array $parameters Additional parameters
	 * @return mixed Result of callback
	 * @throws Exception If unable to resolve dependencies
	 */
	public function call( callable $callback, array $parameters = [] ) {
		return call_user_func_array( $callback, $this->resolveParameters( $callback, $parameters ) );
	}

	/**
	 * Check if abstract is bound
	 *
	 * @param string $abstract Class or interface name
	 * @return bool
	 */
	public function has( string $abstract ): bool {
		return isset( $this->bindings[ $abstract ] ) || isset( $this->instances[ $abstract ] );
	}

	/**
	 * Get concrete implementation
	 *
	 * @param string $abstract Class or interface name
	 * @return mixed
	 */
	private function getConcrete( string $abstract ) {
		if ( isset( $this->bindings[ $abstract ] ) ) {
			return $this->bindings[ $abstract ]['concrete'];
		}

		return $abstract;
	}

	/**
	 * Build an instance
	 *
	 * @param mixed $concrete Concrete implementation
	 * @param array $parameters Constructor parameters
	 * @return mixed Built instance
	 * @throws Exception If unable to build
	 */
	private function build( $concrete, array $parameters = [] ) {
		// If closure, invoke it
		if ( $concrete instanceof Closure ) {
			return $concrete( $this, $parameters );
		}

		// Use reflection to build class
		try {
			$reflector = new ReflectionClass( $concrete );
		} catch ( ReflectionException $e ) {
			throw new Exception( "Target class [{$concrete}] does not exist.", 0, $e );
		}

		// Check if class is instantiable
		if ( ! $reflector->isInstantiable() ) {
			throw new Exception( "Target [{$concrete}] is not instantiable." );
		}

		// Get constructor
		$constructor = $reflector->getConstructor();

		// No constructor, return new instance
		if ( $constructor === null ) {
			return new $concrete();
		}

		// Resolve constructor dependencies
		$dependencies = $this->resolveParameters( [ $concrete, '__construct' ], $parameters );

		return $reflector->newInstanceArgs( $dependencies );
	}

	/**
	 * Resolve method/constructor parameters
	 *
	 * @param callable|array $callback Callback or [class, method]
	 * @param array $parameters Additional parameters
	 * @return array Resolved parameters
	 * @throws Exception If unable to resolve
	 */
	private function resolveParameters( $callback, array $parameters = [] ): array {
		try {
			if ( is_array( $callback ) ) {
				$reflection = new \ReflectionMethod( $callback[0], $callback[1] );
			} else {
				$reflection = new \ReflectionFunction( $callback );
			}

			$deps = [];

			foreach ( $reflection->getParameters() as $param ) {
				$name = $param->getName();

				// Use provided parameter if available
				if ( array_key_exists( $name, $parameters ) ) {
					$deps[] = $parameters[ $name ];
					continue;
				}

				// Try to resolve type-hinted parameter
				$type = $param->getType();

				if ( $type && ! $type->isBuiltin() ) {
					$deps[] = $this->make( $type->getName() );
					continue;
				}

				// Use default value if available
				if ( $param->isDefaultValueAvailable() ) {
					$deps[] = $param->getDefaultValue();
					continue;
				}

				throw new Exception( "Unable to resolve parameter [{$name}]" );
			}

			return $deps;

		} catch ( ReflectionException $e ) {
			throw new Exception( "Unable to resolve dependencies", 0, $e );
		}
	}

	/**
	 * Clear all bindings and instances
	 *
	 * @return void
	 */
	public function flush(): void {
		$this->bindings = [];
		$this->instances = [];
	}
}
