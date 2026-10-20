<?php
namespace Nicescrollr\Admin\Includes;

/**
 * If this file is called directly, abort.
 */
if (!defined('WPINC')) {
    die;
}

/**
 * The class responsible for creating and displaying the help tab on this plugin's option page.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/includes
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_Help_Tab
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
     * The array containing the title and the content of the help tab.
     *
     * @since  0.1.0
     * @access private
     * @var    array $tabs
     */
    private $tabs;

    /**
     * Sets the content of the help tab.
     *
     * @return void
     * @since  0.1.0
     * @access private
     */
    private function set_tab()
    {
        $this->tabs = array('nicescrollr-help' => array('title' => __('Nicescrollr help', $this->domain)));
    }

    /**
     * Sets the tab.
     * Determines if we're on the options page,
     * and if so, it hooks the action to load the help tab.
     *
     * @param string $domain
     *
     */
    public function __construct($domain)
    {
        $this->domain = $domain;

        $this->set_tab();

        // We do only add the help tab on the plugin options page.
        $page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';

        if ($page === 'nicescrollr_settings') {
            add_action("load-{$GLOBALS['pagenow']}", array(
                    $this,
                    'add_nsr_help_tab'
            ), 15);
        }
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
        add_action('in_admin_header', array(
                $this,
                'add_nsr_help_tab'
        ), 15);
    }

    /**
     * Adds the contents of the help tab to the current screen.
     *
     * @hooked_action
     * @return void
     * @since  0.1.0
     */
    public function add_nsr_help_tab()
    {
        foreach ($this->tabs as $id => $data) {
            get_current_screen()->add_help_tab(array(
                    'id'      => $id,
                    'title'   => $data['title'],
                    'content' => $this->display_content_callback(),
            ));
        }
    }

    /**
     * The callback function containing the content of the help tab.
     *
     * @return string  $html
     * @since  0.1.0
     * @access private
     */
    private function display_content_callback()
    {
		$inuyaksa_link = '<a href="'.esc_url('https://wordpress.org/support/users/inuyaksa/').'" target="_blank" rel="noopener noreferrer">';
        $nicescroll_link = '<a href="'.esc_url('https://www.areaaperta.com/nicescroll/').'" target="_blank" rel="noopener noreferrer">';
        $github_link = '<a href="'.esc_url('https://github.com/inuyaksa/jquery.nicescroll').'" target="_blank" rel="noopener noreferrer">';
        $plugin_link = '<a href="'.esc_url('https://wordpress.org/plugins/nicescrollr/').'" target="_blank" rel="noopener noreferrer">';
        $link_close = '</a>';

        ob_start();
        ?>

        <p><?php echo esc_html__('Nicescrollr integrates the Nicescroll jQuery library into your WordPress powered website.', $this->domain); ?></p>

        <p><?php
            /* translators: 1: opening author link, 2: closing author link. */
            echo wp_kses_post(sprintf(__('This plugin is a wrapper for the popular "Nicescroll" JavaScript library, which was made by %1$sInuYaksa%2$s.', $this->domain), $inuyaksa_link, $link_close));
            ?></p>

        <p><?php
            /* translators: 1: opening Nicescroll link, 2: closing link, 3: opening GitHub link. */
            echo wp_kses_post(sprintf(__('Visit the official %1$sNicescroll homepage%2$s or see the default parameters on %3$sGitHub%2$s.', $this->domain), $nicescroll_link, $link_close, $github_link));
            ?></p>

        <p><?php
            /* translators: 1: opening Nicescroll link, 2: closing link. */
            echo wp_kses_post(sprintf(__('For help with Nicescroll, please refer to the official %1$sNicescroll website%2$s.', $this->domain), $nicescroll_link, $link_close));
            ?></p>

        <p><?php echo esc_html__("There are also optional 'back to top' buttons for both frontend and backend, which may come in handy. If your theme gets shipped with this functionality already, you can disable the plugin's integrated feature on the 'Plugin' settings page.", $this->domain); ?></p>

        <p><?php
            /* translators: 1: opening plugin link, 2: closing link. */
            echo wp_kses_post(sprintf(__('For questions, comments, or issues with Nicescrollr, please visit the %1$splugin homepage%2$s.', $this->domain), $plugin_link, $link_close));
            ?></p>

        <?php
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

}
