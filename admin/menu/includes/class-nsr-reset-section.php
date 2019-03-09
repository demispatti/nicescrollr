<?php

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
	 * The array holding the application keys.
	 *
	 * @since 0.1.0
	 * @acces private
	 * @var   array $keys
	 */
	private $keys;

	/**
	 * Assigns the required parameter to its instance.
	 *
	 * @since 0.1.0
	 * @param $plugin_domain
	 * @return mixed
	 */
	public function __construct( $keys ) {

		$this->keys = $keys;
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
				'id'               => 'reset_frontend',
				'class'            => 'nsr-reset-button',
				'name'             => __( 'Reset Frontend', $this->keys['plugin_domain'] ),
				'title'      => __( 'Resets the settings for the frontend.', $this->keys['plugin_domain'] )
			),
			array(
				'id'               => 'reset_backend',
				'class'            => 'nsr-reset-button',
				'name'             => __( 'Reset Backend', $this->keys['plugin_domain'] ),
				'title'      => __( 'Resets the settings for the backend.', $this->keys['plugin_domain'] )
			),
			array(
				'id'               => 'reset_plugin',
				'class'            => 'nsr-reset-button',
				'name'             => __( 'Reset Plugin', $this->keys['plugin_domain'] ),
				'title'      => __( 'Resets the settings for the plugin.', $this->keys['plugin_domain'] )
			),
			array(
				'id'               => 'reset_all',
				'class'            => 'nsr-reset-button',
				'name'             => __( 'Reset All', $this->keys['plugin_domain'] ),
				'title'      => __( 'Resets all Nicescrollr settings.', $this->keys['plugin_domain'] )
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

		return '<h3 class="nicescrollr_settings_toggle">' . __( 'Reset Settings', $this->keys['plugin_domain'] ) . '</h3>';
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

		foreach ( $this->reset_buttons() as $id => $button ) {

			$nonce = wp_create_nonce( $button['id'] . "_nonce" );

			$html .= '<tr>';
			$html .= '<th scope="row">' . $button['name'] . '</th>';
			$html .= '<td>';
			$html .= '<div class="form-table-td-wrap">';
			$html .= '<p class="nsr-input-container">';
			$html .= '<a name="' . $button['id'] . '" id="' . $button['id'] . '" class="button button-primary dp-button float-left ' . $button['class'] . '" title="' . $button['title'] . '" data-nonce="' . $nonce . '">' . __( 'Reset', $this->keys['plugin_domain'] ). '</a>';
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
	 * @return echo
	 */
	public function echo_section() {

		$html = $this->get_section_heading();
		$html .= $this->get_table();

		echo $html;
	}

}
