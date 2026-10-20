<?php
namespace Nicescrollr\Admin\Includes;

/**
 * If this file is called directly, abort.
 */
if (!defined('WPINC')) {
    die;
}

/**
 * The class responsible for the ajax functionality.
 *
 * @since             0.7.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/includes
 * @author            Demis Patti <demis@demispatti.ch>
 */
class Nsr_Ajax
{
    
    /**
     * The domain of the plugin.
     *
     * @since  0.7.0
     *
     * @access private
     *
     * @var string $domain
     */
    protected string $domain;
    
    /**
     * The reference to the options class.
     *
     * @since  0.1.0
     * @access private
     * @var    \Nsr_Options $Options
     */
    private $Options;
    
    /**
     * Holds the error text for failed nonce checks
     *
     * @var string $nonce_error_text
     * @since    0.7.0
     * @access   private
     */
    private $nonce_error_text;

    /**
     * Nsr_Ajax constructor.
     *
     * @param string $domain
     * @param Nsr_Options $Options
     *
     * @since 0.7.0
     */
    public function __construct(string $domain, Nsr_Options $Options)
    {
        $this->domain = $domain;
        $this->Options = $Options;
        $this->nonce_error_text = __('That won\'t do.', $this->domain);
    }
    
    /**
     * Registers the methods that need to be hooked with WordPress.
     *
     * @return void
     * @since 0.7.0
     */
    public function add_hooks()
    {
        add_action('wp_ajax_nicescrollr_reset_options', array(
            $this,
            'nicescrollr_reset_options'
        ));
    }
    
    /**
     * Saves the options.
     *
     * @return void
     * @since 0.7.0
     */
    public function nicescrollr_reset_options()
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You are not allowed to change these settings.', $this->domain)), 403);
        }
        
        // Verify nonce from POST request
        $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
        
        if (false === wp_verify_nonce($nonce, 'nicescrollr_reset_options_nonce')) {
            wp_send_json_error(array('message' => $this->nonce_error_text), 403);
        }
        
        // Sanitize section parameter
        $section = isset($_POST['section']) ? sanitize_text_field(wp_unslash($_POST['section'])) : '';
        if (!in_array($section, array(
            Nsr_Options::VIEW_FRONTEND,
            Nsr_Options::VIEW_BACKEND
        ), true)) {
            wp_send_json_error(array('message' => __('Invalid settings section.', $this->domain)), 400);
        }
        
        // Save options
        $result = $this->Options->reset_settings($section);
        if (is_wp_error($result)) {
            wp_send_json_error(array(
                    'message' => $result->get_error_message().' '.__('Please try again later.', $this->domain),
                ), 500);
        } else {
            wp_send_json_success(array('message' => __('Settings set to default.', $this->domain)));
        }
    }

}
