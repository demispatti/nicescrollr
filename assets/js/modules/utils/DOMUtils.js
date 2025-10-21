/**
 * DOM Utilities
 *
 * Helper functions for DOM manipulation without jQuery dependency.
 * Modern vanilla JavaScript with clean, semantic API.
 *
 * @package    Nicescrollr
 * @subpackage Assets/JS/Utils
 * @since      1.0.0
 */

/**
 * DOMUtils class
 *
 * Static utility methods for DOM operations
 */
export class DOMUtils {
	/**
	 * Query single element
	 *
	 * @param {string} selector CSS selector
	 * @param {HTMLElement} context Context element (default: document)
	 * @return {HTMLElement|null}
	 */
	static query( selector, context = document ) {
		return context.querySelector( selector );
	}

	/**
	 * Query multiple elements
	 *
	 * @param {string} selector CSS selector
	 * @param {HTMLElement} context Context element (default: document)
	 * @return {Array<HTMLElement>}
	 */
	static queryAll( selector, context = document ) {
		return Array.from( context.querySelectorAll( selector ) );
	}

	/**
	 * Add class to element
	 *
	 * @param {HTMLElement} element Target element
	 * @param {...string} classes Class names
	 * @return {void}
	 */
	static addClass( element, ...classes ) {
		if ( ! element ) {
			return;
		}
		element.classList.add( ...classes );
	}

	/**
	 * Remove class from element
	 *
	 * @param {HTMLElement} element Target element
	 * @param {...string} classes Class names
	 * @return {void}
	 */
	static removeClass( element, ...classes ) {
		if ( ! element ) {
			return;
		}
		element.classList.remove( ...classes );
	}

	/**
	 * Toggle class on element
	 *
	 * @param {HTMLElement} element Target element
	 * @param {string} className Class name
	 * @param {boolean} force Force state
	 * @return {void}
	 */
	static toggleClass( element, className, force = undefined ) {
		if ( ! element ) {
			return;
		}
		element.classList.toggle( className, force );
	}

	/**
	 * Check if element has class
	 *
	 * @param {HTMLElement} element Target element
	 * @param {string} className Class name
	 * @return {boolean}
	 */
	static hasClass( element, className ) {
		if ( ! element ) {
			return false;
		}
		return element.classList.contains( className );
	}

	/**
	 * Set multiple CSS properties
	 *
	 * @param {HTMLElement} element Target element
	 * @param {Object} styles Style object
	 * @return {void}
	 */
	static css( element, styles ) {
		if ( ! element || ! styles ) {
			return;
		}

		Object.assign( element.style, styles );
	}

	/**
	 * Get computed style value
	 *
	 * @param {HTMLElement} element Target element
	 * @param {string} property CSS property
	 * @return {string}
	 */
	static getStyle( element, property ) {
		if ( ! element ) {
			return '';
		}

		return window.getComputedStyle( element ).getPropertyValue( property );
	}

	/**
	 * Set attribute
	 *
	 * @param {HTMLElement} element Target element
	 * @param {string} name Attribute name
	 * @param {string} value Attribute value
	 * @return {void}
	 */
	static attr( element, name, value ) {
		if ( ! element ) {
			return;
		}

		if ( value === null ) {
			element.removeAttribute( name );
		} else {
			element.setAttribute( name, value );
		}
	}

	/**
	 * Get attribute
	 *
	 * @param {HTMLElement} element Target element
	 * @param {string} name Attribute name
	 * @return {string|null}
	 */
	static getAttr( element, name ) {
		if ( ! element ) {
			return null;
		}

		return element.getAttribute( name );
	}

	/**
	 * Attach event listener
	 *
	 * @param {HTMLElement|Window|Document} element Target element
	 * @param {string} event Event name
	 * @param {Function} handler Event handler
	 * @param {Object} options Event options
	 * @return {void}
	 */
	static on( element, event, handler, options = {} ) {
		if ( ! element ) {
			return;
		}

		element.addEventListener( event, handler, options );
	}

	/**
	 * Remove event listener
	 *
	 * @param {HTMLElement|Window|Document} element Target element
	 * @param {string} event Event name
	 * @param {Function} handler Event handler
	 * @return {void}
	 */
	static off( element, event, handler ) {
		if ( ! element ) {
			return;
		}

		element.removeEventListener( event, handler );
	}

	/**
	 * Trigger custom event
	 *
	 * @param {HTMLElement} element Target element
	 * @param {string} eventName Event name
	 * @param {Object} detail Event detail
	 * @return {void}
	 */
	static trigger( element, eventName, detail = {} ) {
		if ( ! element ) {
			return;
		}

		const event = new CustomEvent( eventName, {
			bubbles: true,
			cancelable: true,
			detail,
		} );

		element.dispatchEvent( event );
	}

	/**
	 * Wait for DOM ready
	 *
	 * @param {Function} callback Callback function
	 * @return {void}
	 */
	static ready( callback ) {
		if ( document.readyState === 'loading' ) {
			document.addEventListener( 'DOMContentLoaded', callback );
		} else {
			callback();
		}
	}

	/**
	 * Create element with attributes
	 *
	 * @param {string} tag Tag name
	 * @param {Object} attributes Attributes object
	 * @param {string|HTMLElement} content Content
	 * @return {HTMLElement}
	 */
	static create( tag, attributes = {}, content = null ) {
		const element = document.createElement( tag );

		Object.entries( attributes ).forEach( ( [ key, value ] ) => {
			if ( key === 'class' ) {
				element.className = value;
			} else if ( key === 'style' && typeof value === 'object' ) {
				Object.assign( element.style, value );
			} else {
				element.setAttribute( key, value );
			}
		} );

		if ( content !== null ) {
			if ( typeof content === 'string' ) {
				element.textContent = content;
			} else if ( content instanceof HTMLElement ) {
				element.appendChild( content );
			}
		}

		return element;
	}

	/**
	 * Remove element from DOM
	 *
	 * @param {HTMLElement} element Element to remove
	 * @return {void}
	 */
	static remove( element ) {
		if ( ! element || ! element.parentNode ) {
			return;
		}

		element.parentNode.removeChild( element );
	}

	/**
	 * Get element offset
	 *
	 * @param {HTMLElement} element Target element
	 * @return {Object} Top and left offsets
	 */
	static offset( element ) {
		if ( ! element ) {
			return { top: 0, left: 0 };
		}

		const rect = element.getBoundingClientRect();
		const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
		const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

		return {
			top: rect.top + scrollTop,
			left: rect.left + scrollLeft,
		};
	}
}
