<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * Cleans up all plugin data including:
 * - Plugin options from the database
 * - Custom capabilities from user roles
 *
 * @link       https://github.com/demispatti/nicescrollr
 * @since      0.1.0
 * @package    Nsr
 */

// If uninstall not called from WordPress, then exit.
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Remove plugin options from database
 */
delete_option( 'nicescrollr_options' );

// For multisite installations
if ( is_multisite() ) {
	global $wpdb;
	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

	foreach ( $blog_ids as $blog_id ) {
		switch_to_blog( $blog_id );
		delete_option( 'nicescrollr_options' );
		restore_current_blog();
	}
}

/**
 * Remove custom capability from administrator role
 */
$role = get_role( 'administrator' );
if ( ! empty( $role ) ) {
	$role->remove_cap( 'nicescrollr_edit' );
}
