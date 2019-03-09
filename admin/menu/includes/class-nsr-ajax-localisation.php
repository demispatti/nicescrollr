<?php

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class responsible for localizing the ajax part of this plugin.
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
class nsr_ajax_localisation {

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
	 * Assigns the required parameters to its instance.
	 *
	 * @since 0.1.0
	 * @param $plugin_name
	 * @param $plugin_domain
	 */
	public function __construct( $domain ) {

		$this->domain = $domain;
	}

	/**
	 * Kicks off localisation of the ajax part of this plugin.
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function run() {

		$this->localize_script();
	}

	/**
	 * Retrieves the confirmation texts for the ajax script and passes them to it.
	 *
	 * @since  0.1.0
	 * @see    admin/menu/js/ajax.js
	 * @access private
	 * @return void
	 */
	private function localize_script() {

		wp_localize_script( 'nicescrollr-ajax-js', 'nsrAjax', array_merge( $this->get_confirmation_texts(), $this->get_confirmation_dialog_labels() ) );
	}

	/**
	 * Returns the string that shows up when the user wants to reset all options.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string $string
	 */
	private function get_reset_frontend_confirmation_heading() {

		$string = __( "Reset", $this->domain );

		return $string;
	}

	/**
	 * Returns the string that shows up when the user wants to reset all options.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string $string
	 */
	private function get_reset_backend_confirmation_heading() {

		$string = __( "Reset", $this->domain );

		return $string;
	}

	/**
	 * Returns the string that shows up when the user wants to reset all options.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string $string
	 */
	private function get_reset_plugin_confirmation_heading() {

		$string = __( "Reset", $this->domain );

		return $string;
	}

	/**
	 * Returns the string that shows up when the user wants to reset all options.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string $string
	 */
	private function get_reset_all_confirmation_heading() {

		$string = __( "Reset", $this->domain );

		return $string;
	}

	/**
	 * Returns the string that shows up when the user wants to reset the frontend options.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string $confirm_reset
	 */
	private function get_reset_frontend_confirmation_text() {

		$confirm_reset = __( "This resets the frontend view to it's defaults. Continue?", $this->domain );

		return $confirm_reset;
	}

	/**
	 * Returns the string that shows up when the user wants to reset the backend options.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string $confirm_reset
	 */
	private function get_reset_backend_confirmation_text() {

		$confirm_reset = __( "This resets the backend view to it's defaults. Continue?", $this->domain );

		return $confirm_reset;
	}

	/**
	 * Returns the string that shows up when the user wants to reset the plugin options.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string $confirm_reset
	 */
	private function get_reset_plugin_confirmation_text() {

		$confirm_reset = __( "This resets the plugin to it's defaults. Continue?", $this->domain );

		return $confirm_reset;
	}

	/**
	 * Returns the string that shows up when the user wants to reset all options.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string $confirm_reset
	 */
	private function get_reset_all_confirmation_text() {

		$confirm_reset = __( "This resets all Nicescrollr settings to their defaults. Continue?", $this->domain );

		return $confirm_reset;
	}

	/**
	 * Returns the array containing the preceeding confirmation texts.
	 *
	 * @since    0.1.0
	 * @return   array $texts
	 */
	public function get_confirmation_texts() {

		$texts = array(
			'resetFrontendConfirmationHeading' => $this->get_reset_frontend_confirmation_heading(),
			'resetBackendConfirmationHeading'  => $this->get_reset_backend_confirmation_heading(),
			'resetPluginConfirmationHeading'   => $this->get_reset_plugin_confirmation_heading(),
			'resetAllConfirmationHeading'      => $this->get_reset_all_confirmation_heading(),

			'resetFrontendConfirmation' => $this->get_reset_frontend_confirmation_text(),
			'resetBackendConfirmation'  => $this->get_reset_backend_confirmation_text(),
			'resetPluginConfirmation'   => $this->get_reset_plugin_confirmation_text(),
			'resetAllConfirmation'      => $this->get_reset_all_confirmation_text(),
		);

		return $texts;
	}

	/**
	 * Returns an array containing the localized labels for the confirmation dialog.
	 *
	 * @since    0.1.0
	 * @return   array $labels
	 */
	public function get_confirmation_dialog_labels() {

		$labels = array(
			'okiDoki'   => __( 'ok', $this->domain ),
			'noWayJose' => __( 'cancel', $this->domain ),
		);

		return $labels;
	}

}
