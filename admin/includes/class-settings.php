<?php
namespace Nicescrollr\Admin\Includes;

use WP_Error;
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
if (!class_exists('AdminIncludes\Nsr_Validation')) {
    require_once NICESCROLLR_ROOT_DIR.'admin/includes/class-validation.php';
}

/**
 * The class that deals with the settings api.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/includes
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_Settings
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
     *  The name of the section.
     *
     * @since  0.1.0
     * @access private
     * @var    string $section
     */
    private $section;
    
    /**
     * The array containing the default options.
     *
     * @since  0.1.0
     * @access private
     * @var    array $default_options
     */
    private $default_options;
    
    /**
     * The reference to the options class.
     *
     * @since  0.1.0
     * @access private
     * @var    Nsr_Options $Options
     */
    private $Options;
    
    /**
     * The reference to the validation class.
     *
     * @since  0.1.0
     * @access private
     * @var    AdminIncludes\Nsr_Validation $Validation
     */
    private $Validation;
    
    /**
     * The array that holds the stored options.
     *
     * @since  0.1.0
     * @access private
     * @var    array $stored_options
     */
    private $stored_options;

    /**
     * Nsr_Settings constructor.
     *
     * @param                           $domain
     * @param                           $section
     * @param AdminIncludes\Nsr_Options $Options
     * @param AdminIncludes\Nsr_Validation $Validation
     */
    public function __construct($domain, $section, $Options, $Validation)
    {
        $this->domain = $domain;
        $this->section = $section;
        $this->Options = $Options;
        $this->Validation = $Validation;
        
        $this->stored_options = get_option('nicescrollr_options');
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
        add_action('admin_init', array(
            $this,
            'register_settings'
        ), 1);
        add_action('admin_init', array(
            $this,
            'load_default_options'
        ), 3);
        add_action('admin_init', array(
            $this,
            'initialize_options'
        ), 10);
    }
    
    /**
     * Retrieves the reference to the "plugin data" class.
     *
     * @return Nsr_Options $options
     * @since  0.1.0
     */
    public function get_options_instance()
    {
        return $this->Options;
    }
    
    /**
     * Retrieves the name of the section.
     *
     * @hooked_action
     *
     * @return string $section
     * @since  0.1.0
     */
    public function get_section()
    {
        return $this->section;
    }
    
    /**
     * Registers the settings group and the validation-callback with WordPress.
     *
     * @hooked_action
     *
     * @return void
     * @since  0.1.0
     */
    public function register_settings()
    {
        register_setting('nicescrollr_options', 'nicescrollr_options', array(
                'type'              => 'array',
                'sanitize_callback' => array(
                    $this,
                    'run_validation'
                ),
                'default'           => array(),
                'show_in_rest'      => false,
            ));
    }
    
    /**
     * Kicks off the validation process.
     *
     * @param array $input
     *
     * @return array|WP_Error
     * @since  0.1.0
     */
	public function run_validation($input)
    {
        if (!current_user_can('manage_options') || !is_array($input)) {
            return get_option('nicescrollr_options', array());
        }
        
        if (isset($_POST['section'])) {
            $section = sanitize_text_field(wp_unslash($_POST['section']));
            if (!in_array($section, Nsr_Options::$settings_tabs, true)) {
                add_settings_error('nicescrollr_options', 'invalid_section', __('Invalid settings section.', "nicescrollr"));
                
                return get_option('nicescrollr_options', array());
            }
        } else {
            return new WP_Error(-1, __('There is no section related to the data.', "nicescrollr"));
        }
        
        return $this->Validation->run($input, $section);
    }
    
    /**
     * Checks for options in the database and seeds the default values if the options group should be empty.
     *
     * @hooked_action
     *
     * @return void
     * @uses   seed_default_options()
     * @see    admin/includes/class-nsr-options.php
     * @since  0.1.0
     */
    public function check_for_options()
    {
        $options = get_option('nicescrollr_options');
        
        if (false === $options) {
            $this->Options->seed_options();
        } else {
            if (!isset($options['frontend']['cursorcolor'])) {
                $option_groups = array(
                    'frontend',
                    'backend'
                );
                
                foreach ($option_groups as $option_group) {
                    if (key($options) !== $option_group) {
                        $this->Options->seed_options($option_group);
                    }
                }
            } else {
                return;
            }
        }
    }
    
    /**
     * Calls the function that retrieves the default options.
     *
     * @hooked_action
     *
     * @return void
     * @since  0.1.0
     */
    public function load_default_options()
    {
        $this->set_default_options();
    }
    
    /**
     * Retrieves the default options for the requested section and sets them.
     *
     * @return void
     * @uses   get_default_settings()
     * @see    admin/includes/class-nsr-options.php
     * @access private
     * @since  0.1.0
     */
    private function set_default_options()
    {
        $this->default_options = $this->Options->get_default_settings($this->section);
    }
    
    /**
     * Registers the sections, their headings and settings fields with WordPress.
     *
     * @hooked_action
     *
     * @return void
     * @uses   get_section_heading()
     * @uses   get_options_meta()
     * @see    admin/includes/class-nsr-options.php
     * @since  0.1.0
     */
    public function initialize_options()
    {
        $this->add_settings_section($this->Options->get_section_heading('basic'));
        $this->add_settings_field($this->Options->get_args());
        
        $this->add_settings_section($this->Options->get_section_heading('extended'));
        $this->add_settings_field($this->Options->get_args('extended'));
        
        $this->add_settings_section($this->Options->get_section_heading('backtop'));
        $this->add_settings_field($this->Options->get_args('backtop'));
    }
    
    /**
     * Registers the settings sections with WordPress.
     *
     * @param array $section_heading
     *
     * @return void
     * @since  0.1.0
     *
     */
    private function add_settings_section($section_heading)
    {
        add_settings_section($section_heading['settings_group'].'_settings_section', $section_heading['title'], array(
            $this,
            $section_heading['callback']
        ), 'nicescrollr_settings');
    }
    
    /**
     * Registers the settings fields with WordPress.
     *
     * @param array $settings_fields
     *
     * @return void
     * @since  0.1.0
     *
     */
    private function add_settings_field($settings_fields)
    {
        foreach ($settings_fields as $option_key => $args) {
            add_settings_field($option_key, $args['name'], array(
                $this,
                $args['callback']
            ), 'nicescrollr_settings', $args['settings_group'].'_settings_section', array(
                'option_key'    => $option_key,
                'section'       => $this->section,
                'title'         => $args['title'],
                'input_type'    => $args['input_type'],
                'select_values' => $args['select_values'],
            ));
        }
    }
    
    /**
     * Renders the description for the "basic settings section".
     *
     * @return void / echo
     * @since 0.1.0
     */
    public function basic_settings_section_callback()
    {
        //echo '<h2 class="basic nicescrollr_settings_toggle wtf"><i class="fa fa-sliders" aria-hidden="true"></i>' . __( 'Basic Settings', "nicescrollr" ) . '</h2>';
    }
    
    /**
     * Renders the description for the "extended settings section".
     *
     * @return void / echo
     * @since 0.1.0
     */
    public function extended_settings_section_callback()
    {
        //echo '<h2 class="extended nicescrollr_settings_toggle"><i class="fa fa-sliders" aria-hidden="true"></i>' . __( 'Extended Settings', "nicescrollr" ) . '</h2>';
    }
    
    /**
     * Renders the description for the "plugin section".
     *
     * @return void / echo
     * @since 0.1.0
     */
    public function backtop_settings_section_callback()
    {
        //echo '<h2 class="backtop nicescrollr_settings_toggle"><i class="fa fa-sliders" aria-hidden="true"></i>' . __( 'Plugin Settings', "nicescrollr" ) . '</h2>';
    }
    
    /**
     * Calls the corresponding callback function that renders the section field.
     *
     * @param array $args
     *
     * @return void / echo
     * @since  0.1.0
     *
     */
	public function render_settings_field_callback($args)
    {
        switch ($args['input_type']) {
            case 'checkbox':
                $this->echo_checkbox_field($args);
                break;
            
            case 'text':
                $this->echo_text_field($args);
                break;
            
            case 'color':
                $this->echo_color_picker_field($args);
                break;
            
            case 'select':
                $this->echo_select_field($args);
                break;
        }
    }
    
    /**
     * Returns the value related the given 'section' and 'option key'.
     *
     * @param $section
     * @param $option_key
     *
     * @return mixed
     */
    private function get_option_value($section, $option_key)
    {
        if (isset($this->stored_options[$section][$option_key])) {
            return $this->stored_options[$section][$option_key];
        }
        
        return $this->default_options[$option_key];
    }
    
    /**
     * Renders a settings field with a checkbox.
     *
     * @param $args
     *
     * @return void / echo string $html
     * @since 0.1.0
     *
     */
    public function echo_checkbox_field($args)
    {
        $option_key = $args['option_key'];
        $title = $args['title'];
        $section = $args['section'];
        
        $value = $this->get_option_value($section, $option_key);
        
        $html = '<label class="nsr-switch label-for-nsr-switch" title="'.esc_attr($title).'">';
        $html .= '<input type="checkbox" id="'.esc_attr($option_key).'" class="nsr-switch-input nsr-input-checkbox" name="nicescrollr_options['.esc_attr($option_key).']" value="1" '.checked(1, isset($value) ? $value : 0, false).'>';
        $html .= '<span class="nsr-switch-label" data-on="On" data-off="Off"></span>';
        $html .= '<span class="nsr-switch-handle"></span>';
        $html .= '</label>';
        
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Markup is assembled above exclusively from static HTML and escaped values.
        echo $html;
    }
    
    /**
     * Renders a settings field with a text field.
     *
     * @param $args
     *
     * @return void / echo string $html
     * @since 0.1.0
     *
     */
    public function echo_text_field($args)
    {
        $option_key = $args['option_key'];
        $title = $args['title'];
        $section = $args['section'];
        
        $value = $this->get_option_value($section, $option_key);
        
        $html = '<p class="nsr-input-container">';
        $html .= '<input type="text" id="'.esc_attr($option_key).'" class="nsr-input-text" title="'.esc_attr($title).'" name="nicescrollr_options['.esc_attr($option_key).']" placeholder="'.esc_attr($this->default_options[$option_key]).'" value="'.esc_attr($value).'">';
        $html .= '</p>';
        
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Markup is assembled above exclusively from static HTML and escaped values.
        echo $html;
    }
    
    /**
     * Renders a settings field with a color picker.
     *
     * @param $args
     *
     * @return void / echo string $html
     * @since 0.1.0
     *
     */
    public function echo_color_picker_field($args)
    {
        $option_key = $args['option_key'];
        $title = $args['title'];
        $section = $args['section'];
        
        $value = $this->get_option_value($section, $option_key);
        
        $html = '<p class="nsr-input-container">';
        $html .= '<input type="text" id="'.esc_attr($option_key).'" title="'.esc_attr($title).'" name="nicescrollr_options['.esc_attr($option_key).']" placeholder="'.esc_attr($this->default_options[$option_key]).'" value="'.esc_attr($value).'" class="'.esc_attr($option_key).' nsr-color-picker nsr-input-color-picker" data-alpha-enabled="true">';
        $html .= '</p>';
        
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Markup is assembled above exclusively from static HTML and escaped values.
        echo $html;
    }
    
    /**
     * Renders a settings field with a select dropdown.
     *
     * @param  $args
     *
     * @return void / echo string $html
     * @since  0.1.0
     * @uses   translate_to_custom_locale()
     *
     */
    public function echo_select_field($args)
    {
        $option_key = $args['option_key'];
        $title = $args['title'];
        $section = $args['section'];
        $select_values = (array) $args['select_values'];
        $retrieved_value = $this->get_option_value($section, $option_key);
        
        $html = '<p class="nsr-input-container">';
        $html .= '<select title="'.esc_attr($title).'" name="nicescrollr_options['.esc_attr($option_key).']" class="nsr-input-select" id="'.esc_attr($option_key).'">';
        foreach ($select_values as $attribute => $select_value) {
            $html .= '<option '.selected((string) $attribute, (string) $retrieved_value, false).' value="'.esc_attr($attribute).'">'.esc_html($select_value).'</option>';
        }
        $html .= '</select>';
        $html .= '</p>';
        
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Markup is assembled above exclusively from static HTML and escaped values.
        echo $html;
    }
    
    /**
     * Translation helper function for some select box values.
     * Since Nicescroll makes use of strings as parameters - and it does only "speak" English -
     * this function translates the values that were stored in the default locale into strings of the current locale.
     * This way, the localisation feature remains fully functional.
     *
     * @param string $string
     * @param string $option_key
     *
     * @return string $output
     *
     * @since  0.1.0
     *
     * @see    admin/includes/class-nsr-validation.php | translate_to_default_locale()
     *
     * @deprecated
     */
	public function translate_to_custom_locale($string, $option_key)
    {
        switch( $option_key) {
            case 'cursorborderstate':
                if (isset($string) && $string === 'none') {
                    $output = __('none', "nicescrollr");
                } else {
                    $output = $string;
                }
                break;
            
            case 'autohidemode':
                if (isset($string) && $string === 'off') {
                    $output = __('off', "nicescrollr");
                } else {
                    if (isset($string) && $string === 'on') {
                        $output = __('on', "nicescrollr");
                    } else {
                        if (isset($string) && $string === 'cursor') {
                            $output = __('cursor', "nicescrollr");
                        } else {
                            $output = $string;
                        }
                    }
                }
                break;
            
            case 'railoffset':
                if (isset($string) && $string === 'off') {
                    $output = __('off', "nicescrollr");
                } else {
                    if (isset($string) && $string === 'top') {
                        $output = __('top', "nicescrollr");
                    } else {
                        if (isset($string) && $string === 'left') {
                            $output = __('left', "nicescrollr");
                        } else {
                            $output = $string;
                        }
                    }
                }
                break;
            
            case 'railalign':
                if (isset($string) && $string === 'right') {
                    $output = __('right', "nicescrollr");
                } else {
                    if (isset($string) && $string === 'left') {
                        $output = __('left', "nicescrollr");
                    } else {
                        $output = $string;
                    }
                }
                break;
            
            case 'railvalign':
                if (isset($string) && $string === 'bottom') {
                    $output = __('bottom', "nicescrollr");
                } else {
                    if (isset($string) && $string === 'top') {
                        $output = __('top', "nicescrollr");
                    } else {
                        $output = $string;
                    }
                }
                break;
            
            case 'cursorfixedheight':
                if (isset($string) && $string === 'off') {
                    $output = __('off', "nicescrollr");
                } else {
                    $output = $string;
                }
                break;
            
            default:
                $output = $string;
        }
        
        return $output;
    }
    
}
