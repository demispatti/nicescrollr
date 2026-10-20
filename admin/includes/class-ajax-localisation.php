<?php
namespace Nicescrollr\Admin\Includes;

/**
 * If this file is called directly, abort.
 */
if (!defined('WPINC')) {
    die;
}

/**
 * The class responsible for localizing the ajax part of this plugin.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/includes
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_Ajax_Localisation
{
    
    /**
     * The domain of the plugin.
     *
     * @since  0.1.0
     *
     * @access private
     *
     * @var string $domain
     */
    private string $domain;

    /**
     * Assigns the required parameters to its instance.
     *
     * @param string $domain
     * @since 0.1.0
     *
     */
    public function __construct(string $domain)
    {
        $this->domain = $domain;
    }
    
    /**
     * Kicks off localisation of the ajax part of this plugin.
     *
     * @return void
     * @since  0.1.0
     */
    public function run()
    {
        $this->localize_script();
    }
    
    /**
     * Retrieves the confirmation texts for the ajax script and passes them to it.
     *
     * @return void
     * @see    admin/js/ajax.js
     * @access private
     * @since  0.1.0
     */
    private function localize_script()
    {
        wp_localize_script('nicescrollr-ajax-js', 'Nsr_Ajax', array_merge($this->get_confirmation_texts(), array('admin_url' => admin_url('admin-ajax.php'))));
    }
    
    /**
     * Returns the array containing the preceeding confirmation texts.
     *
     * @return   array $texts
     * @since    0.1.0
     */
    public function get_confirmation_texts()
    {
        return array(
            'resetFrontendConfirmationHeading' => __('Reset', "nicescrollr"),
            'resetBackendConfirmationHeading'  => __('Reset', "nicescrollr"),
            'resetPluginConfirmationHeading'   => __('Reset', "nicescrollr"),
            'resetAllConfirmationHeading'      => __('Reset', "nicescrollr"),
            
            'resetFrontendConfirmation' => __('This resets the frontend view to it\'s defaults. Continue?', "nicescrollr"),
            'resetBackendConfirmation'  => __('This resets the backend view to it\'s defaults. Continue?', "nicescrollr"),
            'resetPluginConfirmation'   => __('This resets the plugin to it\'s defaults. Continue?', "nicescrollr"),
            'resetAllConfirmation'      => __('This resets all Nicescrollr settings to their defaults. Continue?', "nicescrollr"),
            
            'okiDoki'   => __('ok', "nicescrollr"),
            'noWayJose' => __('cancel', "nicescrollr")
        );
    }
    
}
