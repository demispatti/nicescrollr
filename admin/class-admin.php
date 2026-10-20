<?php
namespace Nicescrollr\Admin;

use Nicescrollr\Shared as Shared;
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
if (!class_exists('AdminIncludes\Nsr_Help_Tab')) {
    require_once NICESCROLLR_ROOT_DIR.'admin/includes/class-help-tab.php';
}
if (!class_exists('AdminIncludes\Nsr_Menu')) {
    require_once NICESCROLLR_ROOT_DIR.'admin/includes/class-menu.php';
}
if (!class_exists('AdminIncludes\Nsr_Options')) {
    require_once NICESCROLLR_ROOT_DIR.'admin/includes/class-options.php';
}
if (!class_exists('AdminIncludes\Nsr_Ajax_Localisation')) {
    require_once NICESCROLLR_ROOT_DIR.'admin/includes/class-ajax-localisation.php';
}
if (!class_exists('AdminIncludes\Nsr_Menu_Localisation')) {
    require_once NICESCROLLR_ROOT_DIR.'admin/includes/class-menu-localisation.php';
}
if (!class_exists('AdminIncludes\Nsr_Reset_Section')) {
    require_once NICESCROLLR_ROOT_DIR.'admin/includes/class-reset-section.php';
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_Admin
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
    private $domain;
    
    /**
     * The name of this view.
     *
     * @since  0.1.0
     * @access private
     * @var    string $view
     */
    private $view = 'backend';
    
    /**
     * The reference to the options class.
     *
     * @since  0.1.0
     * @access private
     * @var    AdminIncludes\Nsr_Options $Options
     */
    private $Options;
    
    /**
     * The reference to the Nicescroll localisation class.
     *
     * @since  0.1.0
     * @access private
     * @var    Shared\Nsr_Nicescroll_Localisation $Nicescroll_Localisation
     */
    private $Nicescroll_Localisation;
    
    /**
     * The reference to the localisation class.
     *
     * @since  0.1.0
     * @access private
     * @var    Shared\Nsr_Backtop_Localisation $Backtop_Localisation
     */
    private $Backtop_Localisation;
    
    /**
     * The array that holds the stored option.
     *
     * @since  0.5.2
     * @access private
     * @var    array $settings
     */
    private $settings;
    
    /**
     * The string that holds the '-min' prefix for the js and css file handles.
     *
     * @since  0.7.0
     * @access private
     * @var    string $handle_prefix
     */
    private $handle_prefix;
    
    /**
     * The string that holds the '.min' prefix for the js and css file paths.
     *
     * @since  0.7.0
     * @access private
     * @var    string $file_prefix
     */
    private $file_prefix;
    
    /**
     * Sets the prefix for the scripts and stylesheets to control
     * the inclusion of minified versions of these files.
     *
     * @return void
     *
     * @since  0.7.4
     */
    private function set_prefixes()
    {
        $this->handle_prefix = '';
        $this->file_prefix = defined('NICESCROLLR_DEBUG') && NICESCROLLR_DEBUG === '1' ? '' : '.min';
    }

    /**
     * Nsr_Admin constructor.
     *
     * @param string                             $domain                  The text domain for translations
     * @param AdminIncludes\Nsr_Options          $Options                 Options manager instance
     * @param Shared\Nsr_Nicescroll_Localisation $Nicescroll_Localisation Nicescroll localisation instance
     * @param Shared\Nsr_Backtop_Localisation    $Backtop_Localisation    Backtop localisation instance
     */
    public function __construct(string $domain, AdminIncludes\Nsr_Options $Options, Shared\Nsr_Nicescroll_Localisation $Nicescroll_Localisation, Shared\Nsr_Backtop_Localisation $Backtop_Localisation)
    {
        $this->domain = $domain;
        $this->Options = $Options;
        $this->Nicescroll_Localisation = $Nicescroll_Localisation;
        $this->Backtop_Localisation = $Backtop_Localisation;
        
        $this->settings = $this->Options->get_options();
        
        $this->set_prefixes();
        
        $this->initialize_menu();
        $this->initialize_help_tab();
        $this->include_backtop();
    }
    
    /**
     * Register the hooks with WordPress.
     *
     * @return void
     * @since  0.5.2
     *
     */
    public function add_hooks()
    {
        add_action('admin_enqueue_scripts', array(
            $this,
            'enqueue_styles'
        ), 20);
        add_action('admin_enqueue_scripts', array(
            $this,
            'enqueue_scripts'
        ), 20);
        add_action('admin_enqueue_scripts', array(
            $this,
            'initialize_localisations'
        ), 40);
        add_filter('plugin_row_meta', array(
            $this,
            'plugin_row_meta'
        ), 10, 2);
    }
    
    /**
     * Registers the st<les for the admin menu.
     *
     * @hooked_action
     *
     * @return void
     * @since  0.1.0
     */
    public function enqueue_styles()
    {
        // Dashicons
        wp_enqueue_style('dashicons');
        
        if (isset($this->settings['backend']['bt_enabled']) && $this->settings['backend']['bt_enabled']) {
            wp_enqueue_style('nicescrollr-backtop-css', NICESCROLLR_ROOT_URL.'shared/backtop'.$this->file_prefix.'.css', array(), NICESCROLLR_VERSION);
        }
        
        wp_enqueue_style('nicescrollr-admin-css', NICESCROLLR_ROOT_URL.'admin/css/admin'.$this->file_prefix.'.css', array(), NICESCROLLR_VERSION);
        
        $menu_stylesheet = NICESCROLLR_ROOT_DIR.'admin/css/menu'.$this->file_prefix.'.css';
        $menu_version = file_exists($menu_stylesheet) ? (string) filemtime($menu_stylesheet) : NICESCROLLR_VERSION;
        wp_enqueue_style('nicescrollr-menu-css', NICESCROLLR_ROOT_URL.'admin/css/menu'.$this->file_prefix.'.css', array(), $menu_version);
    }
    
    /**
     * Registers the scripts for the admin area.
     *
     * @hooked_action
     *
     * @return void
     * @since  0.1.0
     */
    public function enqueue_scripts()
    {
        // Gets executed if Nicescroll is enabled in the frontend.
        if (isset($this->settings[$this->view]['enabled']) && $this->settings[$this->view]['enabled']) {
            // jQuery Easing
            wp_enqueue_script('nicescrollr-inc-easing-min-js', NICESCROLLR_ROOT_URL.'vendor/jquery-easing/jquery.easing.min.js', array('jquery'), '1.4.1', true);
            
            $nice_url = NICESCROLLR_ROOT_URL.'vendor/nicescroll/jquery.nicescroll.min.js';
            wp_enqueue_script('nicescrollr-inc-nicescroll-min-js', $nice_url, array(
                'jquery',
                'nicescrollr-inc-easing-min-js'
            ), '3.7.6', true);
            
            // Nicescroll configuration file
            wp_enqueue_script('nicescrollr-nicescroll'.$this->handle_prefix.'-js', NICESCROLLR_ROOT_URL.'shared/nicescroll'.$this->file_prefix.'.js', array(
                'jquery',
                'nicescrollr-inc-nicescroll-min-js'
            ), NICESCROLLR_VERSION, true);
        }
        
        if (isset($this->settings['backend']['bt_enabled']) && $this->settings['backend']['bt_enabled']) {
            // Backtop
            wp_enqueue_script('nicescrollr-backtop-js', NICESCROLLR_ROOT_URL.'shared/backtop'.$this->file_prefix.'.js', array('jquery'), NICESCROLLR_VERSION, true);
        }
    }
    
    /**
     * Registers all necessary hooks and instanciates all necessary objects the settings menu is made of.
     *
     * @return void
     * @see    admin/menu/class-nsr-menu.php
     * @access private
     * @since  0.1.0
     */
    public function initialize_menu()
    {
        $Menu_Localisation = new AdminIncludes\Nsr_Menu_Localisation($this->Options);
        $Ajax_Localisation = new AdminIncludes\Nsr_Ajax_Localisation($this->domain);
        $Reset_Section = new AdminIncludes\Nsr_Reset_Section($this->domain);
        
        $menu = new AdminIncludes\Nsr_Menu($this->domain, $this->Options, $Menu_Localisation, $Ajax_Localisation, $Reset_Section);
        $menu->add_hooks();
    }
    
    /**
     * Creates a reference to the "help tab class" and hooks the initial function with WordPress.
     *
     * @return void
     * @see    admin/includes/class-nsr-help-tab.php
     * @access private
     * @since  0.1.0
     */
    private function initialize_help_tab()
    {
        if (isset($_GET['page']) && sanitize_text_field(wp_unslash($_GET['page'])) === 'nicescrollr_settings') {
            $help_Tab = new AdminIncludes\Nsr_Help_Tab($this->domain);
            $help_Tab->add_hooks();
        }
    }
    
    /**
     * Initiates localisation of the scripts.
     *
     * @hooked_action
     *
     * @return void
     * @see    js/nicescroll.js
     * @since  0.1.0
     */
    public function initialize_localisations()
    {
        if (isset($this->settings[$this->view]['enabled']) && $this->settings[$this->view]['enabled']) {
            $this->localize_nicescroll();
        }
        if (isset($this->settings[$this->view]['bt_enabled']) && $this->settings[$this->view]['bt_enabled']) {
            $this->localize_backtop();
        }
    }
    
    /**
     * Initiates the localisation of the frontend view.
     *
     * @return void
     * @uses   run()
     * @see    includes/class-nsr-nicescroll-localisation.php
     * @access private
     * @since  0.1.0
     */
    private function localize_nicescroll()
    {
        $this->Nicescroll_Localisation->run($this->view);
    }
    
    /**
     * Initializes the components for the backtop functionality.
     *
     * @return void
     *
     * @since  0.7.5
     */
    private function include_backtop()
    {
        if (isset($this->settings['backend']['bt_enabled']) && $this->settings['backend']['bt_enabled']) {
            $Backtop = new Shared\Nsr_Backtop($this->domain, $this->Options, 'backend');
            $Backtop->add_hooks();
        }
    }
    
    /**
     * Initiates the localisation of the back top button.
     *
     * @return void
     *
     * @access private
     * @since  0.7.5
     */
    private function localize_backtop()
    {
        $this->Backtop_Localisation->run($this->view);
    }

    /**
     * Adds support, rating, and donation links to the plugin row meta on the plugins admin screen.
     *
     * @param array $meta
     * @param string $file
     *
     * @return array  $meta
     * @since  0.1.0
     */
	public function plugin_row_meta($meta, $file)
    {
        $plugin = plugin_basename(NICESCROLLR_PLUGIN_FILE);
        
        if ($file === $plugin) {
            $meta[] = '<a href="https://wordpress.org/support/plugin/nicescrollr" target="_blank" rel="noopener noreferrer">'.esc_html__('Plugin support', "nicescrollr").'</a>';
            $meta[] = '<a href="https://wordpress.org/support/plugin/nicescrollr/reviews/" target="_blank" rel="noopener noreferrer">'.esc_html__('Rate plugin', "nicescrollr").'</a>';
            $meta[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=XLMMS7C62S76Q" target="_blank" rel="noopener noreferrer">'.esc_html__('Donate', "nicescrollr").'</a>';
        }
        
        return $meta;
    }
    
}
