<?php
namespace Nicescrollr\Admin\Includes;

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
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
class Nsr_Ajax {

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
	public function __construct(string $domain, Nsr_Options $Options) {

		$this->domain = $domain;
		$this->Options = $Options;
		$this->nonce_error_text = __( 'That won\'t do.', $this->domain );
	}

	/**
	 * Registers the methods that need to be hooked with WordPress.
	 *
	 * @since 0.7.0
	 * @return void
	 */
	public function add_hooks() {

		add_action( 'wp_ajax_nicescrollr_reset_options', array( $this, 'nicescrollr_reset_options' ) );
	}

	/**
	 * Saves the options.
	 *
	 * @since 0.7.0
	 * @return void
	 */
	public function nicescrollr_reset_options() {

		$nonce = $_REQUEST['nonce'];

		if( false === wp_verify_nonce( $nonce, 'nicescrollr_reset_options_nonce' ) ) {

			$response = array(
				'success' => false,
				'message' => $this->nonce_error_text
			);

			wp_send_json_error( $response );
		}

		// Save options
		$result = $this->Options->reset_settings( $_REQUEST['section'] );
		if( is_wp_error( $result ) ) {

			$code = $result->get_error_code();
			$msg = $result->get_error_message();

			if( - 1 === $code ) {

				$response = array(
					'success' => true,
					'message' => $msg
				);

				wp_send_json_success( $response );
			}

			$response = array(
				'success' => false,
				'message' => $msg . ' ' . __( 'Please try again later.', $this->domain ) . ' (' . $code . ')'
			);

			wp_send_json_error( $response );
		}
		else {
			/**
			 * @var array $result
			 */
			$response = array(
				'success' => true,
				'message' => __( 'Settings set to default.', $this->domain ),
				'smtp_state' => $result['smtp_state'],
				'imap_state' => $result['imap_state']
			);

			wp_send_json_success( $response );
		}
	}

}
