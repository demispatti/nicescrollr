<?php
/**
 * PSR-4 Autoloader
 *
 * Simple PSR-4 compliant autoloader for the plugin.
 * Maps Nicescrollr namespace to src/ directory.
 *
 * @package    Nicescrollr
 * @since      1.0.0
 */

spl_autoload_register( function ( $class ) {
	// Base namespace
	$prefix = 'Nicescrollr\\';

	// Base directory for the namespace
	$base_dir = __DIR__ . '/';

	// Check if the class uses the namespace prefix
	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		// No, move to the next registered autoloader
		return;
	}

	// Get the relative class name
	$relative_class = substr( $class, $len );

	// Replace namespace separators with directory separators
	// and append .php
	$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

	// If the file exists, require it
	if ( file_exists( $file ) ) {
		require $file;
	}
} );
