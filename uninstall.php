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
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

$remove_site_data = static function() : void
{
    delete_option('nicescrollr_options');
    
    $role = get_role('administrator');
    if (!empty($role)) {
        $role->remove_cap('nicescrollr_edit');
    }
};

if (is_multisite()) {
    $site_ids = get_sites(array(
            'fields' => 'ids',
            'number' => 0,
        ));
    
    foreach ($site_ids as $site_id) {
        switch_to_blog((int) $site_id);
        $remove_site_data();
        restore_current_blog();
    }
} else {
    $remove_site_data();
}
