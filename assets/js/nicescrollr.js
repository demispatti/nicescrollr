/**
 * Nicescrollr Main Application
 *
 * Modern entry point using ES6 modules.
 * Initializes scrollbar and back-to-top functionality.
 *
 * @package    Nicescrollr
 * @subpackage Assets/JS
 * @since      1.0.0
 */

import { ScrollbarManager } from './modules/ScrollbarManager.js';
import { BackTopButton } from './modules/BackTopButton.js';
import { DOMUtils } from './modules/utils/DOMUtils.js';

/**
 * Nicescrollr Application Class
 *
 * Main application controller
 */
class Nicescrollr {
	/**
	 * Scrollbar manager instance
	 * @type {ScrollbarManager|null}
	 */
	#scrollbar = null;

	/**
	 * Back-to-top button instance
	 * @type {BackTopButton|null}
	 */
	#backTop = null;

	/**
	 * Configuration
	 * @type {Object}
	 */
	#config;

	/**
	 * Constructor
	 *
	 * @param {Object} config Configuration object from wp_localize_script
	 */
	constructor( config = {} ) {
		this.#config = config;
	}

	/**
	 * Initialize the application
	 *
	 * @return {void}
	 */
	init() {
		// Initialize scrollbar if enabled
		if ( this.#config.enabled === '1' || this.#config.enabled === true ) {
			this.#scrollbar = new ScrollbarManager( this.#config );
			this.#scrollbar.init();
		}

		// Initialize back-to-top button if enabled
		if ( this.#config.bt_enabled === '1' || this.#config.bt_enabled === true ) {
			this.#backTop = new BackTopButton( this.#config );
			this.#backTop.init();
		}

		// Expose to window for debugging (in development only)
		if ( this.#config.debug ) {
			window.NicescrollrInstance = this;
		}
	}

	/**
	 * Destroy the application
	 *
	 * @return {void}
	 */
	destroy() {
		if ( this.#scrollbar ) {
			this.#scrollbar.destroy();
			this.#scrollbar = null;
		}

		if ( this.#backTop ) {
			this.#backTop.destroy();
			this.#backTop = null;
		}
	}

	/**
	 * Refresh scrollbar
	 *
	 * @return {void}
	 */
	refresh() {
		if ( this.#scrollbar ) {
			this.#scrollbar.refresh();
		}
	}

	/**
	 * Get scrollbar instance
	 *
	 * @return {ScrollbarManager|null}
	 */
	getScrollbar() {
		return this.#scrollbar;
	}

	/**
	 * Get back-to-top instance
	 *
	 * @return {BackTopButton|null}
	 */
	getBackTop() {
		return this.#backTop;
	}
}

// Initialize when DOM is ready
DOMUtils.ready( () => {
	// Configuration is passed via wp_localize_script as window.Nsr_Options
	if ( typeof window.Nsr_Options !== 'undefined' ) {
		const app = new Nicescrollr( window.Nsr_Options );
		app.init();

		// Expose globally for potential integrations
		window.Nicescrollr = app;
	} else {
		console.warn( 'Nicescrollr: Configuration not found' );
	}
} );

// Export for module usage
export { Nicescrollr, ScrollbarManager, BackTopButton };
