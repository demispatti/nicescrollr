<?php
namespace Nicescrollr\Includes;

use Nicescrollr\Admin as Admin;
use Nicescrollr\Pub as Pub;
use Nicescrollr\Includes as Includes;
use Nicescrollr\Admin\Includes as AdminIncludes;
use Nicescrollr\Shared as Shared;

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Include dependencies.
 */
if( ! class_exists( 'Includes\Nsr_I18n' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'includes/class-i18n.php';
}
if( ! class_exists( 'Shared\Nsr_Nicescroll_Localisation' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'shared/class-nicescroll-localisation.php';
}
if( ! class_exists( 'Shared\Nsr_Backtop' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'shared/class-backtop.php';
}
if( ! class_exists( 'Shared\Nsr_Backtop_Localisation' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'shared/class-backtop-localisation.php';
}
if( ! class_exists( 'Admin\Nsr_Admin' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'admin/class-admin.php';
}
if( ! class_exists( 'Pub\Nsr_Public' ) ) {
	require_once NICESCROLLR_ROOT_DIR . 'public/class-public.php';
}

/**
 * The file that defines the core plugin class.
 *
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/includes
 * Author:            demispatti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr {

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
	 * The reference to the localisation class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    Shared\Nsr_Nicescroll_Localisation $Nicescroll_Localisation
	 */
	private $Nicescroll_Localisation;

	/**
	 * The reference to the localisation class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    Shared\Nsr_Backtop_Localisation $Backtop_Localisation
	 */
	private $Backtop_Localisation;

	private function set_instances() {

		$this->Options = new AdminIncludes\Nsr_Options( $this->domain );
		$this->Nicescroll_Localisation = new Shared\Nsr_Nicescroll_Localisation( $this->domain, $this->Options );
		$this->Backtop_Localisation = new Shared\Nsr_Backtop_Localisation( $this->domain, $this->Options );
	}

	/**
	 * Defines the core functionality of the plugin.
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function __construct() {

		$this->domain = 'nicescrollr';
		$this->set_instances();
		$this->set_locale();
	}

	/**
	 * Defines the locale for this plugin.
	 *
	 * @since  0.1.0
	 * @uses   set_plugin_domain()
	 * @see    includes/class-nsr-i18n.php
	 * @access private
	 */
	private function set_locale() {

		$Plugin_i18n = new Includes\Nsr_I18n( $this->domain );
		$Plugin_i18n->add_hooks();
	}

	/**
	 * Creates an instance and registers all hooks related to the admin part.
	 *
	 * @since  0.1.0
	 * @see    admin/class-nsr-admin.php
	 * @access private
	 */
	private function define_admin_hooks() {

		if( ! is_admin() ) {
			return;
		}

        /**
         * @param AdminIncludes\Nsr_Options $Options
         * @param Shared\Nsr_Nicescroll_Localisation $Nicescroll_Localisation
         * @param Shared\Nsr_Backtop_Localisation $Backtop_Localisation
         */
		$admin = new Admin\Nsr_Admin( $this->domain, $this->Options, $this->Nicescroll_Localisation, $this->Backtop_Localisation );
		$admin->add_hooks();
	}

	/**
	 * Creates an instance and registers all hooks related to the public part.
	 *
	 * @since  0.1.0
	 * @see    public/class-nsr-public.php
	 * @access private
	 */
	private function define_public_hooks() {

		if( is_admin() ) {
			return;
		}

		$public = new Pub\Nsr_Public( $this->domain, $this->Options, $this->Nicescroll_Localisation, $this->Backtop_Localisation );
		$public->add_hooks();
	}

	/**
	 * Runs the loader to execute all registered hooks with WordPress.
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function run() {

		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

}
