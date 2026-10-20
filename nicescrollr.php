<?php
namespace Nicescrollr;

use Nicescrollr\Includes as Includes;
use Nicescrollr\Admin\Includes as AdminIncludes;

/**
 * The plugin bootstrap file
 *
 * This plugin provides a simple interface for the included jQuery Nicescroll library.
 * It comes with an extensive options panel giving you
 * full control over almost all available options the Nicescroll library gets shipped with.
 *
 * @wordpress-plugin
 * @package:          Nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @author            Demis Patti
 * @link   :             https://wordpress.org/plugins/nicescrollr/
 * Plugin URI:        https://wordpress.org/plugins/nicescrollr/
 * Plugin Name:       Nicescrollr
 * Description:       Configurable Nicescroll scrollbars and back-to-top buttons for the WordPress frontend and admin area.
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nicescrollr
 * Domain Path:       /languages
 * Version:           1.1.0
 * Stable tag:        1.1.0
 * Requires at least: 5.6
 * Tested up to:      7.1
 * Requires PHP:      7.4
 */

/**
 * If this file is called directly, abort.
 */
if (!defined('WPINC')) {
    die;
}

if (!defined('NICESCROLLR_PLUGIN_FILE')) {
    define('NICESCROLLR_PLUGIN_FILE', __FILE__);
}

if (!defined('NICESCROLLR_VERSION')) {
    define('NICESCROLLR_VERSION', '1.1.0');
}

/**
 * Define debug mode - uses WordPress WP_DEBUG constant or can be overridden
 * Set to true to use unminified assets for development/debugging
 */
if (!defined('NICESCROLLR_DEBUG')) {
    define('NICESCROLLR_DEBUG', defined('WP_DEBUG') && WP_DEBUG ? '1' : '0');
}

/**
 * Define plugin constants.
 */
if (!defined('NICESCROLLR_ROOT_DIR')) {
    define('NICESCROLLR_ROOT_DIR', plugin_dir_path(__FILE__));
}
if (!defined('NICESCROLLR_ROOT_URL')) {
    define('NICESCROLLR_ROOT_URL', plugin_dir_url(__FILE__));
}

/**
 * Include dependencies.
 */
if (!class_exists(Includes\Nsr::class)) {
    require_once NICESCROLLR_ROOT_DIR.'includes/class-nsr.php';
}
if (!class_exists(Includes\Nsr_Activator::class)) {
    require_once NICESCROLLR_ROOT_DIR.'includes/class-activator.php';
}
if (!class_exists(Includes\Nsr_Deactivator::class)) {
    require_once NICESCROLLR_ROOT_DIR.'includes/class-deactivator.php';
}
if (!class_exists(AdminIncludes\Nsr_Options::class)) {
    require_once NICESCROLLR_ROOT_DIR.'admin/includes/class-options.php';
}

/**
 * The function that gets fired on plugin activation.
 *
 * @since 0.1.0
 * @uses  activate_nsr()
 * @see   includes/class-Nsr-activator.php
 */
function activate_nsr() : void
{
    Includes\nsr_activator::activate();
}

/**
 * The function that gets fired on plugin deactivation.
 *
 * @since 0.1.0
 * @uses  deactivate_nsr()
 * @see   includes/class-Nsr-deactivator.php
 */
function deactivate_nsr() : void
{
    Includes\nsr_deactivator::deactivate();
}

register_activation_hook(NICESCROLLR_PLUGIN_FILE, 'Nicescrollr\activate_nsr');
register_deactivation_hook(NICESCROLLR_PLUGIN_FILE, 'Nicescrollr\deactivate_nsr');

/**
 * Begins execution of the plugin.
 *
 * @since 0.1.0
 */
function run_nsr() : void
{
    // @fix, else add these options during next update routine and remove fix
    $options = new AdminIncludes\Nsr_Options('nicescrollr');
    $options->maybe_fill_missing_options();
    
    $plugin = new Includes\Nsr();
    $plugin->run();
}

add_action('init', 'Nicescrollr\run_nsr');
