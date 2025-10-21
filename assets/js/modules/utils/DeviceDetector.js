/**
 * Device Detector Utility
 *
 * Modern device and browser detection using feature detection
 * where possible, with UA sniffing as fallback.
 *
 * @package    Nicescrollr
 * @subpackage Assets/JS/Utils
 * @since      1.0.0
 */

/**
 * DeviceDetector class
 *
 * Detects device type, browser, and capabilities
 */
export class DeviceDetector {
	/**
	 * User agent string
	 * @type {string}
	 */
	#userAgent;

	/**
	 * Cached mobile detection result
	 * @type {boolean|null}
	 */
	#isMobileCache = null;

	/**
	 * Constructor
	 */
	constructor() {
		this.#userAgent = navigator.userAgent;
	}

	/**
	 * Check if device is mobile
	 *
	 * Uses touch points and screen size as primary detection,
	 * falls back to user agent if needed
	 *
	 * @return {boolean}
	 */
	isMobile() {
		if ( this.#isMobileCache !== null ) {
			return this.#isMobileCache;
		}

		// Feature detection (preferred method)
		const hasTouchScreen = ( 'maxTouchPoints' in navigator && navigator.maxTouchPoints > 0 )
			|| ( 'msMaxTouchPoints' in navigator && navigator.msMaxTouchPoints > 0 );

		const hasSmallScreen = window.matchMedia && window.matchMedia( '(max-width: 768px)' ).matches;

		// UA fallback for older browsers
		const mobileUA = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test( this.#userAgent );

		this.#isMobileCache = ( hasTouchScreen && hasSmallScreen ) || mobileUA;

		return this.#isMobileCache;
	}

	/**
	 * Check if device is tablet
	 *
	 * @return {boolean}
	 */
	isTablet() {
		const isIpad = /iPad/.test( this.#userAgent )
			|| ( navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1 );

		const isAndroidTablet = /Android/.test( this.#userAgent ) && ! /Mobile/.test( this.#userAgent );

		return isIpad || isAndroidTablet;
	}

	/**
	 * Check if device is desktop
	 *
	 * @return {boolean}
	 */
	isDesktop() {
		return ! this.isMobile() && ! this.isTablet();
	}

	/**
	 * Get browser type
	 *
	 * @return {string} Browser identifier
	 */
	getBrowserType() {
		const ua = this.#userAgent;

		// Edge (Chromium-based)
		if ( /Edg/.test( ua ) ) {
			return 'edge';
		}

		// Old Edge
		if ( /Edge/.test( ua ) ) {
			return 'edge-legacy';
		}

		// Internet Explorer
		if ( /Trident/.test( ua ) || /MSIE/.test( ua ) ) {
			return 'ie';
		}

		// Firefox
		if ( /Firefox/.test( ua ) ) {
			return 'firefox';
		}

		// Safari (must be before Chrome check)
		if ( /Safari/.test( ua ) && ! /Chrome/.test( ua ) ) {
			return 'safari';
		}

		// Chrome
		if ( /Chrome/.test( ua ) ) {
			return 'chrome';
		}

		// Opera
		if ( /Opera|OPR/.test( ua ) ) {
			return 'opera';
		}

		return 'unknown';
	}

	/**
	 * Check if browser supports a feature
	 *
	 * @param {string} feature Feature name
	 * @return {boolean}
	 */
	supports( feature ) {
		const features = {
			touch: 'ontouchstart' in window || navigator.maxTouchPoints > 0,
			passiveEvents: this.#checkPassiveEvents(),
			smoothScroll: 'scrollBehavior' in document.documentElement.style,
			customProperties: CSS.supports( 'color', 'var(--test)' ),
			grid: CSS.supports( 'display', 'grid' ),
			flexbox: CSS.supports( 'display', 'flex' ),
			intersectionObserver: 'IntersectionObserver' in window,
			mutationObserver: 'MutationObserver' in window,
			requestAnimationFrame: 'requestAnimationFrame' in window,
		};

		return features[ feature ] || false;
	}

	/**
	 * Get device pixel ratio
	 *
	 * @return {number}
	 */
	getPixelRatio() {
		return window.devicePixelRatio || 1;
	}

	/**
	 * Get viewport dimensions
	 *
	 * @return {Object} Width and height
	 */
	getViewport() {
		return {
			width: window.innerWidth || document.documentElement.clientWidth,
			height: window.innerHeight || document.documentElement.clientHeight,
		};
	}

	/**
	 * Check if passive event listeners are supported
	 *
	 * @private
	 * @return {boolean}
	 */
	#checkPassiveEvents() {
		let passive = false;

		try {
			const options = {
				get passive() {
					passive = true;
					return false;
				},
			};

			window.addEventListener( 'test', null, options );
			window.removeEventListener( 'test', null, options );
		} catch ( err ) {
			passive = false;
		}

		return passive;
	}
}
