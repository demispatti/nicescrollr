<?php
/**
 * Bootstrap File
 *
 * Initializes the modern architecture with dependency injection.
 * Sets up all services, repositories, and configurations.
 *
 * @package    Nicescrollr
 * @since      1.0.0
 */

use Nicescrollr\Core\Support\Container;
use Nicescrollr\Core\Config\ScrollbarConfig;
use Nicescrollr\Core\Repositories\OptionsRepository;
use Nicescrollr\Core\Services\OptionsService;
use Nicescrollr\Core\Support\Validator;
use Nicescrollr\Core\Contracts\ConfigInterface;
use Nicescrollr\Core\Contracts\ValidatorInterface;
use Nicescrollr\Core\Contracts\RepositoryInterface;

// Load autoloader
require_once __DIR__ . '/autoload.php';

/**
 * Get the DI container instance
 *
 * @return Container
 */
function nicescrollr_container(): Container {
	return Container::getInstance();
}

/**
 * Bootstrap the application
 *
 * @return void
 */
function nicescrollr_bootstrap(): void {
	$container = nicescrollr_container();

	// Bind core interfaces to implementations
	$container->singleton( ConfigInterface::class, ScrollbarConfig::class );
	$container->singleton( ValidatorInterface::class, Validator::class );
	$container->singleton( RepositoryInterface::class, OptionsRepository::class );
	$container->singleton( OptionsRepository::class, OptionsRepository::class );

	// Bind services
	$container->singleton( OptionsService::class, function ( Container $c ) {
		return new OptionsService(
			$c->make( OptionsRepository::class ),
			$c->make( ConfigInterface::class ),
			$c->make( ValidatorInterface::class )
		);
	} );

	// Boot services
	$options_service = $container->make( OptionsService::class );
	$options_service->boot();
	$options_service->register();

	/**
	 * Fires after the container is bootstrapped
	 *
	 * @param Container $container The DI container
	 */
	do_action( 'nicescrollr_bootstrapped', $container );
}

/**
 * Helper function to get service from container
 *
 * @param string $abstract Service class or interface
 * @return mixed
 */
function nicescrollr( string $abstract = null ) {
	$container = nicescrollr_container();

	if ( $abstract === null ) {
		return $container;
	}

	return $container->make( $abstract );
}

// Initialize on plugins_loaded
add_action( 'plugins_loaded', 'nicescrollr_bootstrap', 5 );
