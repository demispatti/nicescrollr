<?php
namespace Nicescrollr\Admin\Includes;

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
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
class Nsr_Help_Tab {

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
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function set_tab() {

		$this->tabs = array( __( 'HELP', $this->domain ) => array( 'title' => __( 'Nicescrollr help', $this->domain ) ) );
	}

    /**
     * Sets the tab.
     * Determines if we're on the options page,
     * and if so, it hooks the action to load the help tab.
     *
     * @param string $domain
     *
     */
	public function __construct($domain) {

		$this->domain = $domain;

		$this->set_tab();

		// We do only add the help tab on the plugin options page.
		if( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'nicescrollr_settings' ) {

			add_action( "load-{$GLOBALS['pagenow']}", array( $this, 'add_nsr_help_tab' ), 15 );
		}
	}

	/**
	 * Register the hooks with WordPress.
	 *
	 * @since  0.5.2
	 *
	 * @return void
	 */
	public function add_hooks() {

		add_action( 'in_admin_header', array( $this, 'add_nsr_help_tab' ), 15 );
	}

	/**
	 * Adds the contents of the help tab to the current screen.
	 *
	 * @hooked_action
	 * @since  0.1.0
	 * @return void
	 */
	public function add_nsr_help_tab() {

		foreach( $this->tabs as $id => $data ) {

			$title = __( $data['title'], $this->domain );

			get_current_screen()->add_help_tab( array(
				'id' => $id,
				'title' => __( $title, $this->domain ),
				'content' => $this->display_content_callback(),
			) );
		}
	}

	/**
	 * The callback function containing the content of the help tab.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string  $html
	 */
	private function display_content_callback()
    {

		ob_start();
		?>

		<p><?php echo __( 'Nicescrollr integrates the Nicescroll jQuery library into your WordPress powered website.', $this->domain ) ?></p>

		<p><?php echo __( 'This plugin is a wrapper for the popular "Nicescroll" javascript library, which is made by <a href="https://wordpress.org/support/users/inuyaksa/" target="_blank">InuYaksa</a>.', $this->domain) . __( 'You can visit his official Nicescroll homepage <a href="https://www.areaaperta.com/nicescroll/" target="_blank">here</a>.') . __( 'An overview of the default parameters can be found on <a href="https://github.com/inuyaksa/jquery.nicescroll" target="_blank">Github</a> at the bottom of the page.', $this->domain ) ?>

		<p><?php echo __( "For help regarding 'Nicescroll', please refer to the official <a href='https://areaaperta.com/nicescroll/' target='_blank'>Nicescroll</a> website.", $this->domain ) ?></p>

		<p><?php echo __( "There are also optional 'back to top' buttons for both frontend and backend, which may come in handy. If your theme gets shipped with this functionality already, you can disable the plugin's integrated feature on the 'Plugin' settings page.", $this->domain ) ?></p>

		<p><?php echo __( "If you have any questions, comments or issues regarding 'Nicescrollr', please visit the <a href='https://wordpress.org/plugins/nicescrollr/' target='_blank'>Nicescrollr</a> plugin homepage.", $this->domain ) ?></p>

		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

}
