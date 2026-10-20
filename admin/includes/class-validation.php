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
 * The class responsible for sanitizing and validating the user inputs.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/includes
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_Validation
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
     * The reference to the options class.
     *
     * @since  0.1.0
     * @access private
     * @var    AdminIncludes\Nsr_Options $Options
     */
    private $Options;

    /**
     * Assigns the required parameters to its instance.
     *
     * @param string $domain
     * @param Nsr_Options $Options
     *
     * @since 0.1.0
     */
    public function __construct($domain, $Options)
    {
        $this->domain = $domain;
        $this->Options = $Options;
    }

    /**
     * Kicks off sanitisation and validation - if there's any input given.
     *
     * @param array $input
     * @param string $section
     *
     * @return array
     * @since  0.1.0
     */
    public function run($input, $section)
    {
        if (!is_array($input) || !in_array($section, Nsr_Options::$settings_tabs, true)) {
            return get_option('nicescrollr_options', array());
        }
        
        $input = $this->sanitize($input);
        $valid = $this->validate($input, $section);
        
        $valid = $this->fill($valid, $section);
        
        return $this->merge_options($valid, $section);
    }

    /**
     * Sanitizes the input using WordPress sanitization functions.
     *
     * @param array $input
     *
     * @return array $output
     * @since  0.1.0
     * @updated 0.9.4 - Replaced deprecated strip_tags/stripslashes with sanitize_text_field
     *
     */
    private function sanitize($input)
    {
        $output = array();
        
        foreach ($input as $key => $value) {
            if (isset($value) && is_scalar($value)) {
                $output[sanitize_key($key)] = sanitize_text_field((string) $value);
            }
        }
        
        return apply_filters('nicescrollr_sanitize_options', $output, $input);
    }

    /**
     * Validates the input.
     *
     * since  0.1.0
     *
     * @param array $input
     * @param string $section
     *
     * @return array $output
     * @uses   get_default_settings()
     * @see    admin/includes/class-Nsr-options.php
     * @uses   translate_to_default_locale()
     */
    private function validate($input, $section)
    {
        $defaults = $this->Options->get_default_settings($section);
        $options_meta = $this->Options->get_options_meta();
        $notice_levels = $this->Options->get_notice_levels();
        $option_definitions = array_merge($this->Options->get_settings_per_section('basic'), $this->Options->get_settings_per_section('extended'), $this->Options->get_settings_per_section('backtop'));
        $output = array();
        $errors = array();
        $rgba_pattern = '/^(?:#[0-9a-f]{3,4}|#[0-9a-f]{6}(?:[0-9a-f]{2})?|[a-z]+|(?:rgb|hsl)a?\([0-9.\s,%+\-\/]*(?:(?:deg|rad|grad|turn)[0-9.\s,%+\-\/]*)?\))$/i';
        
        $i = 0;
        foreach ($input as $option_key => $value) {
            if (!array_key_exists($option_key, $defaults) || !isset($option_definitions[$option_key])) {
                continue;
            }
            
            switch ($option_key) {
                case 'cursoropacitymin':
                    if ($value !== '') {
                        if (!is_numeric($value) || (float) $value < 0 || (float) $value > 1) {
                            $value = '0';
                            $errors[$option_key] = $this->add_error($i, $option_key, $options_meta, $notice_levels);
                        }
                    }
                    break;
                
                case 'cursoropacitymax':
                    if (!is_numeric($value) || (float) $value < 0 || (float) $value > 1) {
                        $value = $defaults[$option_key];
                        $errors[$option_key] = $this->add_error($i, $option_key, $options_meta, $notice_levels);
                    }
                    break;
                
                case 'cursorbordercolor':
                    if (isset($value) && !preg_match($rgba_pattern, $value)) {
                        $value = $defaults[$option_key];
                        $errors[$option_key] = $this->add_error($i, $option_key, $options_meta, $notice_levels);
                    }
                    break;
                
                case 'zindex':
                    if (!ctype_digit($value)) {
                        if ('auto' !== $value) {
                            $value = $defaults[$option_key];
                            $errors[$option_key] = $this->add_error($i, $option_key, $options_meta, $notice_levels);
                        }
                    }
                    break;
                
                case 'scrollspeed':
                    if ($value === '0' || !ctype_digit($value)) {
                        $value = $defaults[$option_key];
                        $errors[$option_key] = $this->add_error($i, $option_key, $options_meta, $notice_levels);
                    }
                    break;
                
                case 'background':
                    if ('' !== $value && !preg_match($rgba_pattern, $value)) {
                        $value = $defaults[$option_key];
                        $errors[$option_key] = $this->add_error($i, $option_key, $options_meta, $notice_levels);
                    }
                    break;
                
                case 'hidecursordelay':
                    if ($value !== '') {
                        if (!ctype_digit($value)) {
                            $value = '';
                            $errors[$option_key] = $this->add_error($i, $option_key, $options_meta, $notice_levels);
                        }
                    }
                    break;
                
                case 'cursordragspeed':
                case 'mousescrollstep':
                    if ($value !== '') {
                        if (!preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $value)) {
                            $value = '';
                            $errors[$option_key] = $this->add_error($i, $option_key, $options_meta, $notice_levels);
                        }
                    }
                    break;
                
                case 'scrollbarid':
                    if ('' !== $value) {
                        $value = $this->check_text_fields($value);
                        if (is_wp_error($value)) {
                            $value = '';
                            $errors[$option_key] = $this->add_error($i, $option_key, $options_meta, $notice_levels);
                        }
                    }
                    break;
                
                case 'bt_arrow_color':
                case 'bt_arrow_hover_color':
                case 'bt_background_color':
                case 'bt_hover_background_color':
                case 'bt_border_color':
                case 'bt_hover_border_color':
                case 'cursorcolor':
                    if (!preg_match($rgba_pattern, $value)) {
                        $value = $defaults[$option_key];
                        $errors[$option_key] = $this->add_error($i, $option_key, $options_meta, $notice_levels);
                    }
                    break;
                
                case 'bt_posx_from_right':
                case 'bt_posy_from_bottom':
                case 'bt_width':
                case 'bt_height':
                case 'bt_border_width':
                case 'directionlockdeadzone':
                case 'railpaddingtop':
                case 'railpaddingright':
                case 'railpaddingbottom':
                case 'railpaddingleft':
                case 'cursorminheight':
                case 'cursorborderradius':
                case 'cursorborderwidth':
                case 'cursorwidth':
                    if ($value !== '') {
                        // Pattern to remove any reasonable unit for pixels.
                        $pattern = '/pixels+|pixel+|px+/i';
                        if (preg_match($pattern, $value)) {
                            $value = preg_replace($pattern, '', $value);
                            $value = trim($value);
                        }
                        // If the value is not an integer after removing any reasonable unit for pixels...
                        if (!ctype_digit($value)) {
                            $value = $defaults[$option_key];
                            $errors[$option_key] = $this->add_error($i, $option_key, $options_meta, $notice_levels);
                        } else {
                            if ($value !== 0 && ctype_digit($value)) {
                                $value = $this->set_unit($option_key, $value, 'px');
                            }
                        }
                    }
                    break;
                
                case 'bt_border_radius_top_left':
                case 'bt_border_radius_top_right':
                case 'bt_border_radius_bottom_left':
                case 'bt_border_radius_bottom_right':
                    if ($value !== '') {
                        $unit = 'px';
                        // Pattern to remove any reasonable unit for pixels.
                        $pattern = '/pixels+|pixel+|px+|%+/i';
                        if (preg_match($pattern, $value)) {
                            if (preg_match('/%/', $value)) {
                                $unit = '%';
                            }
                            $value = preg_replace($pattern, '', $value);
                            $value = trim($value);
                        }
                        // If the value is not an integer after removing any reasonable unit for pixels...
                        if (!ctype_digit($value)) {
                            $value = $defaults[$option_key];
                            $errors[$option_key] = $this->add_error($i, $option_key, $options_meta, $notice_levels);
                        } else {
                            $value = $this->set_unit($option_key, $value, $unit);
                        }
                    }
                    break;
            }
            
            if ('select' === $option_definitions[$option_key]['input_type']) {
                $allowed_values = array_map('strval', array_keys((array) $option_definitions[$option_key]['select_values']));
                if (!in_array((string) $value, $allowed_values, true)) {
                    $value = $defaults[$option_key];
                    $errors[$option_key] = $this->add_error($i, $option_key, $options_meta, $notice_levels);
                }
            }
            
            if ('checkbox' === $option_definitions[$option_key]['input_type']) {
                $value = '1' === (string) $value ? '1' : '0';
            }
            
            // The array holding the processed values.
            $output[$option_key] = $value;
            $i++;
        }
        
        // Fill unset options with "false".
        foreach ($defaults as $key => $value) {
            $output[$key] = isset($output[$key]) ? $output[$key] : false;
        }
        
        // If there were errors and transients were created, we create one more containing the ids of the previously created ones.
        if (!empty($errors)) {
            set_transient('nicescrollr_validation_transient', $errors, 60);
        }
        
        return apply_filters('nicescrollr_validate_options', $output, $input);
    }
    
    /**
     * Validates text fields.
     *
     * @param $value
     *
     * @return string|WP_Error
     */
    private function check_text_fields($value)
    {
        if ('' === $value) {
            return $value;
        }
        
        if (!preg_match('/^([A-Za-z0-9_ ().-]-)*[A-Za-z0-9\_().-]+$/', $value)) {
            return new WP_Error('broke', __("Your input didn't pass validation. Please use numbers and/or alphabetical characters.", "nicescrollr"));
        }
        
        return (string) $value;
    }
    
    /**
     * Fill options that are not set and give them a specified default value.
     *
     * @param array  $valid
     * @param string $section
     *
     * @return array $valid
     * @access private
     *
     * @since  0.2.1
     */
    private function fill($valid, $section)
    {
        if ('frontend' === $section || 'backend' === $section) {
            $option_args = array_merge($this->Options->get_settings_per_section('basic'), $this->Options->get_settings_per_section('extended'), $this->Options->get_settings_per_section('backtop'));
            
            // Make sure there is at least the option key with an empty value getting stored.
            foreach ($option_args as $option_key => $args) {
                if (!isset($valid[$option_key])) {
                    if ($args['input_type'] === 'checkbox') {
                        $valid[$option_key] = '0';
                    }
                    if (in_array($args['input_type'], array(
                        'text',
                        'color'
                    ), true)) {
                        $valid[$option_key] = '';
                    }
                }
            }
        }
        
        return $valid;
    }
    
    /**
     * Merges the validated options back into the array of options.
     *
     * @param $valid
     * @param $section
     *
     * @return array $options
     */
    private function merge_options($valid, $section)
    {
        $options = get_option('nicescrollr_options', array());
        $options = is_array($options) ? $options : array();
        
        unset($options[$section]);
        
        $options[$section] = $valid;
        
        ksort($options);
        
        return $options;
    }
    
    /**
     * Creates a human readable version of the 'option key'.
     *
     * @param $string
     *
     * @return string
     */
    private function make_readable($string)
    {
        // Remove the file extension
        $name = preg_replace(array(
            '/-/',
            '/_/'
        ), array(
            ' ',
            ' '
        ), $string);
        
        if (preg_match('/\s/', $name)) {
            // Remove whitespace and capitalize.
            $name = implode(' ', array_map('ucfirst', explode(' ', $name)));
            $output = $name;
        } else {
            $output = ucfirst($name);
        }
        
        return $output;
    }
    
    /**
     * Sets the respective units.
     *
     * @param $option_key
     * @param $value
     * @param $unit
     *
     * @return bool|string
     */
    private function set_unit($option_key, $value, $unit)
    {
        $px_related_option_keys = array(
            'cursorwidth',
            'cursorborderwidth',
            'cursorborderradius',
            'cursorminheight',
            'directionlockdeadzone',
            'bt_border_width',
            'bt_width',
            'bt_height',
            'bt_posx_from_right',
            'bt_posy_from_bottom'
        );
        if (in_array($option_key, $px_related_option_keys, true)) {
            if ('px' === $unit) {
                if ('0' !== $value) {
                    return $value.'px';
                }
                
                return $value;
            }
        }
        
        $maybe_percent_related_option_keys = array(
            'bt_border_radius_top_left',
            'bt_border_radius_top_right',
            'bt_border_radius_bottom_left',
            'bt_border_radius_bottom_right'
        );
        if (in_array($option_key, $maybe_percent_related_option_keys, true)) {
            if ('%' === $unit) {
                if ('0' !== $value) {
                    return $value.'%';
                }
                
                return $value;
            }
            
            if ('px' === $unit) {
                if ('0' !== $value) {
                    return $value.'px';
                }
                
                return $value;
            }
        }
        
        return false;
    }
    
    /**
     * Helper function, that translates "non-default-locale strings" into strings of the default locale.
     * This task is necessary, since Nicescroll needs some strings as parameters and they have to be served in English.
     * With this step, localisation remains fully functional.
     *
     * @param  $input
     *
     * @return mixed
     *
     * @since  0.1.0
     * @access private
     * @see    admin/includes/class-nsr-settings.php | translate_to_custom_locale()
     *
     * @deprecated
     */
    private function translate_to_default_locale($input)
    {
        $output = array();
        
        foreach ((array) $input as $option_key => $value) {
            switch ($option_key) {
                case 'cursorborderstate':
                    if (null !== $value && $value === __('none', "nicescrollr")) {
                        $output[$option_key] = 'none';
                    } else {
                        $output[$option_key] = $value;
                    }
                    break;
                
                case 'autohidemode':
                    
                    if (true === $value || 'enabled' === $value) {
                        $output[$option_key] = 'true';
                    } else {
                        if ('cursor' === $value) {
                            $output[$option_key] = 'cursor';
                        } else {
                            if (false === $value || 'disabled' === $value) {
                                $output[$option_key] = 'false';
                            } else {
                                if ('leave' === $value) {
                                    $output[$option_key] = 'leave';
                                } else {
                                    if ('hidden' === $value) {
                                        $output[$option_key] = 'hidden';
                                    } else {
                                        if ('scroll' === $value) {
                                            $output[$option_key] = 'scroll';
                                        } else {
                                            $output[$option_key] = $value;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    break;
                
                case 'railoffset':
                    
                    if (null !== $value && $value === __('false', "nicescrollr")) {
                        $output[$option_key] = 'false';
                    } else {
                        if (null !== $value && $value === __('top', "nicescrollr")) {
                            $output[$option_key] = 'top';
                        } else {
                            if (null !== $value && $value === __('left', "nicescrollr")) {
                                $output[$option_key] = 'left';
                            } else {
                                $output[$option_key] = $value;
                            }
                        }
                    }
                    break;
                
                case 'railalign':
                    
                    if (null !== $value && $value === __('right', "nicescrollr")) {
                        $output[$option_key] = 'right';
                    } else {
                        if (null !== $value && $value === __('left', "nicescrollr")) {
                            $output[$option_key] = 'left';
                        } else {
                            $output[$option_key] = $value;
                        }
                    }
                    break;
                
                case 'railvalign':
                    
                    if (null !== $value && $value === __('bottom', "nicescrollr")) {
                        $output[$option_key] = 'bottom';
                    } else {
                        if (null !== $value && $value === __('top', "nicescrollr")) {
                            $output[$option_key] = 'top';
                        } else {
                            $output[$option_key] = $value;
                        }
                    }
                    break;
                
                case 'cursorfixedheight':
                    
                    if (false === $value) {
                        $output[$option_key] = 'false';
                    } else {
                        $output[$option_key] = $value;
                    }
                    break;
                
                default:
                    $output[$option_key] = $value;
            }
        }
        
        return apply_filters('translate_to_default_locale', $output, $input);
    }
    
    /**
     * Creates and returns an array containing the error meta data for the given option.
     *
     * @param $i
     * @param $option_key
     * @param $options_meta
     * @param $notice_levels
     *
     * @return array
     *
     * @since  0.7.5
     * @access private
     */
    private function add_error($i, $option_key, $options_meta, $notice_levels)
    {
        return array(
            'option_key'   => $option_key,
            'name'         => $this->make_readable($option_key),
            'index'        => $i,
            'notice_level' => isset($notice_levels[$option_key]) ? $notice_levels[$option_key] : 'notice-correction',
            'wp_error'     => $this->get_wp_error($options_meta, $option_key),
        );
    }
    
    /**
     * Creates and returns a WP_Error instance for the given option.
     *
     * @param array  $options_meta
     * @param string $option_key
     *
     * @return WP_Error
     *
     * @since  0.7.5
     * @access private
     */
    private function get_wp_error($options_meta, $option_key)
    {
        $label = isset($options_meta[$option_key]['name']) ? $options_meta[$option_key]['name'] : $this->make_readable($option_key);
        
        /* translators: %s: settings field label. */
        $message = sprintf(__('Invalid value for “%s”. The default value was restored.', "nicescrollr"), $label);
        
        return new WP_Error('nicescrollr_invalid_option', $message );
	}

}
