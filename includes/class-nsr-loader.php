<?php

/**
 * Register all actions and filters for the plugin.
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
class nsr_loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    array   $actions
	 */
	private $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since  0.1.0
	 * @access privale
	 * @var    array   $filters
	 */
	private $filters;

	/**
	 * Initializes the collections used to maintain the actions and filters.
	 *
	 * @since  0.1.0
	 * @return mixed / void
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();
	}

	/**
	 * Adds a new action to the collection to be registered with WordPress.
	 *
	 * @since 0.1.0
	 * @param string $hook
	 * @param object $component
	 * @param string $callback
	 * @param int    $priority
	 * @param int    $accepted_args optional
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {

		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Adds a new filter to the collection to be registered with WordPress.
	 *
	 * @since 0.1.0
	 * @param string $hook
	 * @param object $component
	 * @param string $callback
	 * @param int    $priority
	 * @param int    $accepted_args optional
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {

		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single collection.
	 *
	 * @since  0.1.0
	 * @access private
	 * @param  array   $hooks
	 * @param  string  $hook
	 * @param  object  $component
	 * @param  string  $callback
	 * @param  int     $priority      Optional $priority
	 * @param  int     $accepted_args Optional $accepted_args
	 * @return array
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;
	}

	/**
	 * Registers the filters and actions with WordPress.
	 *
	 * @since  0.1.0
	 * @return mixed | void
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}
	}

}
