<?php
namespace Nicescrollr\Includes;

use Nicescrollr\Admin\Includes as AdminIncludes;

/**
 * If this file is called directly, abort.
 */
if (!defined('WPINC')) {
    die;
}

/**
 * Include dependencies.
 */
if (!class_exists(AdminIncludes\Nsr_Options::class)) {
    require_once NICESCROLLR_ROOT_DIR.'admin/includes/class-options.php';
}

/**
 * The class responsible for the plugin activation.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/includes
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_Activator
{
    
    /**
     * The name of the capability.
     *
     * @since  0.1.0
     * @access static
     * @var    string $capability
     */
    public static $capability = 'nicescrollr_edit';
    
    /**
     * Fired during the activation of the plugin.
     * Adds the capability to interact with this plugin.
     * Creates a reference of the activator class to check if there are already any options stored in the database.
     * If not, it writes the default values for each option group to the database.
     *
     * @return   void
     * @since    0.1.0
     * @access   static
     */
    public static function activate()
    {
        // Gets the administrator role.
        $role = get_role('administrator');
        
        // If the acting user has admin rights, the capability gets added.
        if (!empty($role)) {
            $role->add_cap(self::$capability);
        }
        
        // Add newly introduced defaults without overwriting existing settings.
        $options = new AdminIncludes\Nsr_Options('nicescrollr');
        $options->maybe_fill_missing_options();
    }
}
