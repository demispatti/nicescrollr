<?php

require_once plugin_dir_path(dirname(__FILE__)) . "admin/menu/includes/class-nsr-options.php";

/**
 * The class responsible for the plugin activation.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_activator extends nsr {

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
     * @since    0.1.0
     * @access   static
     * @return   void
     */
    public static function activate() {

        // Gets the administrator role.
        $role = get_role('administrator');

        // If the acting user has admin rights, the capability gets added.
        if( ! empty( $role ) ) {
            $role->add_cap(self::$capability);
        }

        // Checks for already stored options.
        $Activator = new nsr_activator();
        $Activator->check_for_options();
    }

    /**
     * Creates a reference to the data class and writes the default option values to the database if there aren't any yet.
     *
     * @since  0.1.0
     * @uses   get_settings_sections()
     * @uses   seed_options()
     * @see    admin/menu/includes/class-nsr-options.php
     * @access private
     * @return void
     */
    private function check_for_options() {

        // A reference to the class maintaining all plugin-related data.
        $Options = new nsr_options($this->keys);

        // Loops trough the option groups and seeds the default options if there is no data in the database yet.
        foreach( $Options->get_settings_sections() as $section ) {

            if( false === get_option($this->keys['option_group']) ) {
                $Options->seed_options($section);
            }
        }
    }
}
