<?php
namespace Nicescrollr\Shared;

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
if (!class_exists('AdminIncludes\Nsr_Options')) {
    require_once NICESCROLLR_ROOT_DIR.'admin/includes/class-options.php';
}

/**
 * The class responsible for localizing the backtop script.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/shared
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_Backtop
{
    
    /**
     * The domain of the plugin.
     *
     * @since  0.7.5
     *
     * @access private
     *
     * @var string $domain
     */
    private $domain;
    
    /**
     * The reference to the options class.
     *
     * @since  0.7.5
     * @access private
     * @var    AdminIncludes\nsr_Options $Options
     */
    private $Options;
    
    /**
     * The name of the view for the admin area.
     *
     * @since  0.7.5
     * @access private
     * @var    string $view
     */
    public $view;

    /**
     * Nsr_Backtop constructor.
     *
     * @param        $domain
     * @param AdminIncludes\Nsr_Options $Options
     * @param string $view
     */
    public function __construct($domain, $Options, $view)
    {
        $this->domain = $domain;
        $this->Options = $Options;
        $this->view = $view;
    }
    
    /**
     * Register the hooks with WordPress.
     *
     * @return void
     * @since  0.7.5
     *
     */
    public function add_hooks()
    {
        if ('backend' === $this->view) {
            $hook = 'admin_footer';
        } else {
            $hook = 'wp_footer';
        }
        add_action($hook, array(
            $this,
            'add_backtop_button'
        ));
    }
    
    /**
     * Calls the function that passes the parameters to the Nicescroll library.
     *
     * @param string $view
     *
     * @return void
     * @since  0.7.5
     *
     */
    public function add_backtop_button()
    {
        $Nsr_Backtop_Localisation = new Nsr_Backtop_Localisation($this->domain, $this->Options);
        $config = $Nsr_Backtop_Localisation->get_backtop_configuration($this->view);
        
        $allowed_sizes = array(
            'small',
            'medium',
            'default',
            'large'
        );
        $bt_class = isset($config['bt_size']) && in_array($config['bt_size'], $allowed_sizes, true) ? $config['bt_size'] : 'default';
        $bt_arrow_color = isset($config['bt_arrow_color']) ? (string) $config['bt_arrow_color'] : '#ffffff';
        
        if (!preg_match('/^(?:#[0-9a-f]{3,4}|#[0-9a-f]{6}(?:[0-9a-f]{2})?|[a-z]+|(?:rgb|hsl)a?\([0-9.\s,%+\-\/]*(?:(?:deg|rad|grad|turn)[0-9.\s,%+\-\/]*)?\))$/i', $bt_arrow_color)) {
            $bt_arrow_color = '#ffffff';
        }
        
        $color = esc_attr($bt_arrow_color);
        $label = esc_attr__('Back to top', $this->domain);
        $bt_arrow = '<svg id="nsrBackTopArrow" width="66%" height="66%" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><g transform="matrix(0.689387,-0.6898,0.707291,0.706868,-129.386,75.0026)"><rect x="26.023" y="158.089" width="185.738" height="35.456" style="fill:'.$color.';"/></g><g transform="matrix(-0.547574,-0.547901,-0.707291,0.706868,381.27,69.9762)"><rect x="26.023" y="158.089" width="185.738" height="35.456" style="fill:'.$color.';"/></g></svg>';
        
        echo '<button type="button" id="nsr_backtop" class="nsr-backtop '.esc_attr($bt_class).'" aria-label="'.$label.'">'.$bt_arrow.'</button>';
    }

}
