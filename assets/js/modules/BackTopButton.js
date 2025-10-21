/**
 * Back-to-Top Button
 *
 * Modern ES6+ module for smooth scroll-to-top functionality.
 * Implements observer pattern for scroll events with performance optimization.
 *
 * @package    Nicescrollr
 * @subpackage Assets/JS
 * @since      1.0.0
 */

import { DeviceDetector } from './utils/DeviceDetector.js';

/**
 * BackTopButton class
 *
 * Manages back-to-top button visibility and behavior
 */
export class BackTopButton {
	/**
	 * Configuration options
	 * @type {Object}
	 */
	#config;

	/**
	 * Button element
	 * @type {HTMLElement|null}
	 */
	#button = null;

	/**
	 * Device detector
	 * @type {DeviceDetector}
	 */
	#device;

	/**
	 * Scroll listener with throttling
	 * @type {Function|null}
	 */
	#scrollListener = null;

	/**
	 * Throttle timeout
	 * @type {number|null}
	 */
	#throttleTimeout = null;

	/**
	 * Constructor
	 *
	 * @param {Object} config Configuration object
	 */
	constructor( config = {} ) {
		this.#config = this.#normalizeConfig( config );
		this.#device = new DeviceDetector();
	}

	/**
	 * Initialize the button
	 *
	 * @return {BackTopButton} Fluent interface
	 */
	init() {
		// Check if should show on mobile
		if ( this.#device.isMobile() && ! this.#config.mobileEnabled ) {
			return this;
		}

		if ( ! this.#config.enabled ) {
			return this;
		}

		this.#findButton();

		if ( ! this.#button ) {
			console.warn( 'Nicescrollr: Back-to-top button element not found' );
			return this;
		}

		this.#applyStyles();
		this.#attachEvents();

		return this;
	}

	/**
	 * Destroy the button instance
	 *
	 * @return {void}
	 */
	destroy() {
		if ( this.#scrollListener ) {
			window.removeEventListener( 'scroll', this.#scrollListener );
			this.#scrollListener = null;
		}

		if ( this.#button ) {
			this.#button.removeEventListener( 'click', this.#handleClick );
			this.#button.removeEventListener( 'mouseenter', this.#handleMouseEnter );
			this.#button.removeEventListener( 'mouseleave', this.#handleMouseLeave );
		}
	}

	/**
	 * Find button element
	 *
	 * @private
	 * @return {void}
	 */
	#findButton() {
		this.#button = document.querySelector( '.nsr-backtop' );
	}

	/**
	 * Apply CSS styles to button
	 *
	 * @private
	 * @return {void}
	 */
	#applyStyles() {
		if ( ! this.#button ) {
			return;
		}

		// Apply configuration styles
		const styles = {
			backgroundColor: this.#config.backgroundColor,
			borderColor: this.#config.borderColor,
			borderWidth: this.#config.borderWidth,
			borderStyle: this.#config.borderStyle,
			borderTopLeftRadius: this.#config.borderRadiusTopLeft,
			borderTopRightRadius: this.#config.borderRadiusTopRight,
			borderBottomLeftRadius: this.#config.borderRadiusBottomLeft,
			borderBottomRightRadius: this.#config.borderRadiusBottomRight,
		};

		Object.assign( this.#button.style, styles );

		// Apply arrow color to SVG if present
		const arrow = this.#button.querySelector( '#nsrBackTopArrow rect' );
		if ( arrow ) {
			arrow.style.fill = this.#config.arrowColor;
		}
	}

	/**
	 * Attach event listeners
	 *
	 * @private
	 * @return {void}
	 */
	#attachEvents() {
		// Click event for scrolling
		this.#button.addEventListener( 'click', this.#handleClick.bind( this ) );

		// Hover events
		this.#button.addEventListener( 'mouseenter', this.#handleMouseEnter.bind( this ) );
		this.#button.addEventListener( 'mouseleave', this.#handleMouseLeave.bind( this ) );

		// Scroll event with throttling
		this.#scrollListener = this.#throttle( this.#handleScroll.bind( this ), 100 );
		window.addEventListener( 'scroll', this.#scrollListener, { passive: true } );

		// Initial check
		this.#handleScroll();
	}

	/**
	 * Handle click event
	 *
	 * @private
	 * @param {Event} event Click event
	 * @return {void}
	 */
	#handleClick( event ) {
		event.preventDefault();

		// Smooth scroll to top
		window.scrollTo( {
			top: 0,
			behavior: 'smooth',
		} );
	}

	/**
	 * Handle mouse enter
	 *
	 * @private
	 * @return {void}
	 */
	#handleMouseEnter() {
		if ( ! this.#button ) {
			return;
		}

		// Apply hover styles
		this.#button.style.backgroundColor = this.#config.hoverBackgroundColor;
		this.#button.style.borderColor = this.#config.hoverBorderColor;
		this.#button.style.cursor = 'pointer';

		// Change arrow color
		const arrow = this.#button.querySelector( '#nsrBackTopArrow rect' );
		if ( arrow ) {
			arrow.style.fill = this.#config.arrowHoverColor;
		}
	}

	/**
	 * Handle mouse leave
	 *
	 * @private
	 * @return {void}
	 */
	#handleMouseLeave() {
		if ( ! this.#button ) {
			return;
		}

		// Restore original styles
		this.#button.style.backgroundColor = this.#config.backgroundColor;
		this.#button.style.borderColor = this.#config.borderColor;

		// Restore arrow color
		const arrow = this.#button.querySelector( '#nsrBackTopArrow rect' );
		if ( arrow ) {
			arrow.style.fill = this.#config.arrowColor;
		}
	}

	/**
	 * Handle scroll event
	 *
	 * @private
	 * @return {void}
	 */
	#handleScroll() {
		if ( ! this.#button ) {
			return;
		}

		const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
		const shouldShow = scrollTop > this.#config.offset;

		if ( shouldShow ) {
			this.#button.classList.add( 'nsr-backtop-is-visible' );
		} else {
			this.#button.classList.remove( 'nsr-backtop-is-visible' );
		}
	}

	/**
	 * Throttle function execution
	 *
	 * @private
	 * @param {Function} func Function to throttle
	 * @param {number} delay Delay in milliseconds
	 * @return {Function} Throttled function
	 */
	#throttle( func, delay ) {
		return ( ...args ) => {
			if ( this.#throttleTimeout ) {
				return;
			}

			this.#throttleTimeout = setTimeout( () => {
				func( ...args );
				this.#throttleTimeout = null;
			}, delay );
		};
	}

	/**
	 * Normalize configuration
	 *
	 * @private
	 * @param {Object} config Raw configuration
	 * @return {Object} Normalized configuration
	 */
	#normalizeConfig( config ) {
		return {
			enabled: config.bt_enabled === '1' || config.bt_enabled === true,
			mobileEnabled: config.bt_mobile_enabled === '1' || config.bt_mobile_enabled === true,
			offset: parseInt( config.bt_offset || 640, 10 ),
			arrowColor: config.bt_arrow_color || '#000000',
			arrowHoverColor: config.bt_arrow_hover_color || '#ffffff',
			backgroundColor: config.bt_background_color || 'rgba(0, 0, 0, 0.12)',
			hoverBackgroundColor: config.bt_hover_background_color || 'rgba(0, 0, 0, 0.6)',
			borderColor: config.bt_border_color || 'rgba(0, 0, 0, 0.12)',
			hoverBorderColor: config.bt_hover_border_color || 'rgba(0, 0, 0, 0.6)',
			borderWidth: config.bt_border_width || '3px',
			borderStyle: config.bt_border_style || 'solid',
			borderRadiusTopLeft: config.bt_border_radius_top_left || '50%',
			borderRadiusTopRight: config.bt_border_radius_top_right || '50%',
			borderRadiusBottomLeft: config.bt_border_radius_bottom_left || '50%',
			borderRadiusBottomRight: config.bt_border_radius_bottom_right || '50%',
		};
	}
}
