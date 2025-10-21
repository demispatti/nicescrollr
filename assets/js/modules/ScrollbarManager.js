/**
 * Scrollbar Manager
 *
 * Modern ES6+ module for managing custom scrollbars using Nicescroll.
 * Implements clean separation of concerns with dependency injection.
 *
 * @package    Nicescrollr
 * @subpackage Assets/JS
 * @since      1.0.0
 */

import { DeviceDetector } from './utils/DeviceDetector.js';
import { DOMUtils } from './utils/DOMUtils.js';

/**
 * ScrollbarManager class
 *
 * Manages the initialization and configuration of custom scrollbars
 */
export class ScrollbarManager {
	/**
	 * Configuration options
	 * @type {Object}
	 */
	#config;

	/**
	 * DOM elements
	 * @type {Object}
	 */
	#elements;

	/**
	 * Device detector instance
	 * @type {DeviceDetector}
	 */
	#device;

	/**
	 * Nicescroll instance
	 * @type {Object|null}
	 */
	#scrollInstance = null;

	/**
	 * Constructor
	 *
	 * @param {Object} config Configuration object
	 */
	constructor( config = {} ) {
		this.#config = this.#normalizeConfig( config );
		this.#device = new DeviceDetector();
		this.#elements = {
			body: document.body,
			html: document.documentElement,
			adminBar: document.getElementById( 'wpadminbar' ),
			editorContainer: document.getElementById( 'wp-content-editor-container' ),
		};
	}

	/**
	 * Initialize the scrollbar
	 *
	 * @return {ScrollbarManager} Fluent interface
	 */
	init() {
		// Don't initialize on mobile if not configured
		if ( this.#device.isMobile() && ! this.#config.enabled ) {
			return this;
		}

		if ( ! this.#config.enabled ) {
			return this;
		}

		this.#initializeScrollbar();
		this.#setupResizeObserver();
		this.#handleAdminEditor();

		return this;
	}

	/**
	 * Destroy the scrollbar instance
	 *
	 * @return {void}
	 */
	destroy() {
		if ( this.#scrollInstance ) {
			if ( typeof jQuery !== 'undefined' && jQuery( this.#elements.body ).getNiceScroll ) {
				jQuery( this.#elements.body ).getNiceScroll().remove();
			}
			this.#scrollInstance = null;
		}
	}

	/**
	 * Refresh the scrollbar
	 *
	 * @return {void}
	 */
	refresh() {
		if ( this.#scrollInstance ) {
			if ( typeof jQuery !== 'undefined' && jQuery( this.#elements.body ).getNiceScroll ) {
				jQuery( this.#elements.body ).getNiceScroll().resize();
			}
		}
	}

	/**
	 * Initialize Nicescroll
	 *
	 * @private
	 * @return {void}
	 */
	#initializeScrollbar() {
		if ( typeof jQuery === 'undefined' || ! jQuery.fn.niceScroll ) {
			console.error( 'Nicescrollr: jQuery or Nicescroll library not loaded' );
			return;
		}

		const scrollConfig = this.#buildScrollbarConfig();
		this.#scrollInstance = jQuery( this.#elements.body ).niceScroll( scrollConfig );
	}

	/**
	 * Build scrollbar configuration
	 *
	 * @private
	 * @return {Object} Nicescroll configuration object
	 */
	#buildScrollbarConfig() {
		return {
			// Cursor styling
			cursorcolor: this.#config.cursorcolor,
			cursoropacitymin: parseFloat( this.#config.cursoropacitymin ),
			cursoropacitymax: parseFloat( this.#config.cursoropacitymax ),
			cursorwidth: this.#config.cursorwidth,
			cursorborder: this.#buildCursorBorder(),
			cursorborderradius: this.#config.cursorborderradius,
			cursorminheight: parseInt( this.#config.cursorminheight, 10 ),
			cursorfixedheight: this.#parseFixedHeight( this.#config.cursorfixedheight ),

			// Behavior
			scrollspeed: parseInt( this.#config.scrollspeed, 10 ),
			mousescrollstep: parseInt( this.#config.mousescrollstep, 10 ),
			autohidemode: this.#parseAutohideMode( this.#config.autohidemode ),
			hidecursordelay: parseInt( this.#config.hidecursordelay, 10 ),

			// Rail styling
			background: this.#config.background,
			railoffset: this.#parseRailOffset( this.#config.railoffset ),
			railpadding: this.#buildRailPadding(),
			railalign: this.#config.railalign,
			railvalign: this.#config.railvalign,

			// Features
			horizrailenabled: this.#parseBoolean( this.#config.horizrailenabled ),
			bouncescroll: this.#parseBoolean( this.#config.bouncescroll ),
			smoothscroll: this.#parseBoolean( this.#config.smoothscroll ),
			emulatetouch: this.#parseBoolean( this.#config.emulatetouch ),
			hwacceleration: this.#parseBoolean( this.#config.hwacceleration ),
			enabletranslate3d: this.#parseBoolean( this.#config.enabletranslate3d ),

			// Interaction
			grabcursorenabled: this.#parseBoolean( this.#config.grabcursorenabled ),
			cursordragontouch: this.#parseBoolean( this.#config.cursordragontouch ),
			cursordragspeed: parseFloat( this.#config.cursordragspeed ),

			// Keyboard and mouse
			enablemousewheel: this.#parseBoolean( this.#config.enablemousewheel ),
			enablekeyboard: this.#parseBoolean( this.#config.enablekeyboard ),
			spacebarenabled: this.#parseBoolean( this.#config.spacebarenabled ),
			sensitiverail: this.#parseBoolean( this.#config.sensitiverail ),

			// Advanced
			zindex: parseInt( this.#config.zindex, 10 ),
			iframeautoresize: this.#parseBoolean( this.#config.iframeautoresize ),
			preservenativescrolling: this.#parseBoolean( this.#config.preservenativescrolling ),
			nativeparentscrolling: this.#parseBoolean( this.#config.nativeparentscrolling ),
			disableoutline: this.#parseBoolean( this.#config.disableoutline ),
			enablescrollonselection: this.#parseBoolean( this.#config.enablescrollonselection ),
			rtlmode: this.#parseBoolean( this.#config.rtlmode ),

			// Technical
			enableobserver: this.#parseBoolean( this.#config.enableobserver ),
			disablemutationobserver: this.#parseBoolean( this.#config.disablemutationobserver ),
			scrollbarid: this.#config.scrollbarid || false,
		};
	}

	/**
	 * Build cursor border CSS string
	 *
	 * @private
	 * @return {string}
	 */
	#buildCursorBorder() {
		return `${this.#config.cursorborderwidth} ${this.#config.cursorborderstate} ${this.#config.cursorbordercolor}`;
	}

	/**
	 * Build rail padding object
	 *
	 * @private
	 * @return {Object}
	 */
	#buildRailPadding() {
		return {
			top: this.#config.railpaddingtop,
			right: this.#config.railpaddingright,
			bottom: this.#config.railpaddingbottom,
			left: this.#config.railpaddingleft,
		};
	}

	/**
	 * Parse autohide mode
	 *
	 * @private
	 * @param {string} value Autohide mode value
	 * @return {boolean|string}
	 */
	#parseAutohideMode( value ) {
		if ( value === 'enabled' || value === 'true' || value === true ) {
			return true;
		}
		if ( value === 'disabled' || value === 'false' || value === false ) {
			return false;
		}
		return value; // 'cursor', 'leave', 'hidden', 'scroll'
	}

	/**
	 * Parse rail offset
	 *
	 * @private
	 * @param {string|boolean} value Rail offset value
	 * @return {boolean|string}
	 */
	#parseRailOffset( value ) {
		return value === 'false' || value === false ? false : value;
	}

	/**
	 * Parse fixed height
	 *
	 * @private
	 * @param {string|boolean} value Fixed height value
	 * @return {boolean|number}
	 */
	#parseFixedHeight( value ) {
		return value === 'false' || value === false ? false : parseInt( value, 10 );
	}

	/**
	 * Parse boolean value
	 *
	 * @private
	 * @param {*} value Value to parse
	 * @return {boolean}
	 */
	#parseBoolean( value ) {
		if ( typeof value === 'boolean' ) {
			return value;
		}
		if ( typeof value === 'string' ) {
			return value === 'true' || value === '1';
		}
		return Boolean( value );
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
			enabled: config.enabled === '1' || config.enabled === true,
			cursorcolor: config.cursorcolor || 'rgba(193, 193, 193, 1)',
			cursoropacitymin: config.cursoropacitymin || '0',
			cursoropacitymax: config.cursoropacitymax || '1',
			cursorwidth: config.cursorwidth || '8px',
			cursorborderwidth: config.cursorborderwidth || '1px',
			cursorborderstate: config.cursorborderstate || 'solid',
			cursorbordercolor: config.cursorbordercolor || 'rgba(255, 255, 255, 1)',
			cursorborderradius: config.cursorborderradius || '8px',
			scrollspeed: config.scrollspeed || '60',
			mousescrollstep: config.mousescrollstep || '40',
			autohidemode: config.autohidemode || 'disabled',
			background: config.background || '',
			...config,
		};
	}

	/**
	 * Setup resize observer for dynamic content
	 *
	 * @private
	 * @return {void}
	 */
	#setupResizeObserver() {
		const MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

		if ( ! MutationObserver ) {
			return;
		}

		const observer = new MutationObserver( () => {
			// Debounce resize calls
			if ( this.#resizeTimeout ) {
				clearTimeout( this.#resizeTimeout );
			}
			this.#resizeTimeout = setTimeout( () => this.refresh(), 300 );
		} );

		observer.observe( document.body, {
			subtree: true,
			attributes: true,
			childList: true,
		} );
	}

	/**
	 * Handle WordPress admin editor iframe
	 *
	 * @private
	 * @return {void}
	 */
	#handleAdminEditor() {
		if ( ! this.#elements.editorContainer || ! document.body.classList.contains( 'wp-admin' ) ) {
			return;
		}

		const browser = this.#device.getBrowserType();

		this.#elements.editorContainer.addEventListener( 'mouseenter', () => {
			this.#applyEditorStyles( browser, true );
		} );

		this.#elements.editorContainer.addEventListener( 'mouseleave', () => {
			this.#applyEditorStyles( browser, false );
		} );
	}

	/**
	 * Apply browser-specific editor styles
	 *
	 * @private
	 * @param {string} browser Browser type
	 * @param {boolean} entering Whether mouse is entering
	 * @return {void}
	 */
	#applyEditorStyles( browser, entering ) {
		const style = entering ? 'initial' : 'hidden';

		switch ( browser ) {
			case 'firefox':
				this.#elements.html.style.scrollbarWidth = 'none';
				this.#elements.body.style.overflowY = style;
				break;

			case 'edge':
			case 'ie':
				this.#elements.body.style.msOverflowStyle = 'none';
				this.#elements.body.style.msOverflowY = style;
				break;

			default: // Chrome, Safari, etc.
				this.#elements.body.style.overflowY = style;
				break;
		}
	}
}
