<?php

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class that represents the "reset section" within the plugin options tab.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/menu/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_reset_section {

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
	 * Assigns the required parameter to its instance.
	 *
	 * @since 0.1.0
	 * @param $plugin_domain
	 * @return void
	 */
	public function __construct( $domain ) {

		$this->domain = $domain;
	}

	/**
	 * Returns the meta data for the reset buttons.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return array $reset_buttons
	 */
	private function reset_buttons() {

		$reset_buttons = array(
			array(
				'id'    => 'reset_frontend',
				'class' => 'nsr-reset-button',
				'name'  => __( 'Reset Frontend', $this->domain ),
				'title' => __( 'Resets the settings for the frontend.', $this->domain ),
			),
			array(
				'id'    => 'reset_backend',
				'class' => 'nsr-reset-button',
				'name'  => __( 'Reset Backend', $this->domain ),
				'title' => __( 'Resets the settings for the backend.', $this->domain ),
			),
			array(
				'id'    => 'reset_plugin',
				'class' => 'nsr-reset-button',
				'name'  => __( 'Reset Plugin', $this->domain ),
				'title' => __( 'Resets the settings for the plugin.', $this->domain ),
			),
			array(
				'id'    => 'reset_all',
				'class' => 'nsr-reset-button',
				'name'  => __( 'Reset All', $this->domain ),
				'title' => __( 'Resets all Nicescrollr settings.', $this->domain ),
			),
		);

		return $reset_buttons;
	}

	/**
	 * Returns the html that defines the heading of the reset area.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string $html
	 */
	private function get_section_heading() {

		return '<h2 class="lower nicescrollr_settings_toggle"><i class="fa fa-sliders" aria-hidden="true"></i>' . __( 'Reset Settings', $this->domain ) . '</h2>';
	}

	/**
	 * Returns the html that defines the content of the reset area.
	 *
	 * @since  0.1.0
	 * @uses   reset_buttons()
	 * @access private
	 * @return string $html
	 */
	private function get_table() {

		$html = '<table class="form-table upper-panel" style="display: inline-block;">';
		$html .= '<tbody>';

		foreach( $this->reset_buttons() as $id => $button ) {

			$nonce = wp_create_nonce( $button['id'] . "_nonce" );

			$html .= '<tr>';
			$html .= '<th scope="row">' . $button['name'] . '</th>';
			$html .= '<td>';
			$html .= '<div class="form-table-td-wrap">';
			$html .= '<p class="nsr-input-container">';
			$html .= '<a name="' . $button['id'] . '" id="' . $button['id'] . '" class="button button-secondary dp-button float-left ' . $button['class'] . '" title="' . $button['title'] . '" data-nonce="' . $nonce . '">' . __( 'Reset', $this->domain ) . '</a>';
			$html .= '</p>';
			$html .= '</div>';
			$html .= '</td>';
			$html .= '</tr>';
		}

		$html .= '</tbody>';
		$html .= '</table>';

		return $html;
	}

	/**
	 * Echoes the html that defines the reset area and its content.
	 *
	 * @since  0.1.0
	 * @uses   get_section_heading()
	 * @uses   get_table()
	 * @return void / echo string $html
	 */
	public function echo_section() {

		$html = $this->get_section_heading();
		$html .= $this->get_table();

		echo $html;
	}

}
