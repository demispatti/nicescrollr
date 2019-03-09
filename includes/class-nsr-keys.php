<?php

/**
 * The class maintaining the application keys.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_keys {

	/**
	 * The name of this plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $plugin_name
	 */
	private $plugin_name           = 'nicescrollr';

	/**
	 * The name of the domain.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $plugin_domain
	 */
	private $plugin_domain         = 'nicescrollr';

	/**
	 * The version number of the plugin.
	 *
     * @since  0.1.0
	 * @access private
	 * @var    string $plugin_version
	 */
	private $plugin_version        = '0.1.0';

	/**
	 * The name of the option group.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $option_group
	 */
	private $option_group          = 'nicescrollr_options';

	/**
	 * The name of the hook suffix.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $hook_suffix
	 */
	private $hook_suffix           = 'settings_page_nicescrollr_settings';

	/**
	 * The name of the settings page.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $settings_page
	 */
	private $settings_page         = 'nicescrollr_settings';

	/**
	 * The name of the settings section.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $settings_section
	 */
	private $settings_section      = 'nicescrollr_settings_section';

	/**
	 * The name of the settings group.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $settings_group
	 */
	private $settings_group        = 'nicescrollr_settings';

	/**
	 * The name of the validation transient.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $validation_transient
	 */
	private $validation_transient  = 'nicescrollr_validation_transient';

	/**
	 * The array holding the application keys.
	 *
	 * @since  0.1.0
	 * @access protected
	 * @var    array $keys
	 */
	protected $keys;

	/**
	 * Sets the keys.
	 *
	 * @access private
	 */
	private function set_keys() {

		$this->keys['plugin_name']    = $this->plugin_name;
		$this->keys['plugin_domain']  = $this->plugin_domain;
		$this->keys['plugin_version'] = $this->plugin_version;

		$this->keys['option_group']     = $this->option_group;
		$this->keys['hook_suffix']      = $this->hook_suffix;
		$this->keys['settings_page']    = $this->settings_page;
		$this->keys['settings_section'] = $this->settings_section;
		$this->keys['settings_group']   = $this->settings_group;

		$this->keys['validation_transient']  = $this->validation_transient;
	}

	/**
	 * Kick off
	 */
	public function __construct() {

		$this->set_keys();
	}

	/**
	 * retrieves the application keys.
	 *
	 * @return array
	 */
	public function retrieve_keys() {

		return $this->keys;
	}

}
