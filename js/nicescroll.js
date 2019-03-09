/**
 * The file that pre-processes and then runs the passed parameters with Nicescroll.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/js
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

(function ( $ ) {

	var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test( navigator.userAgent );
	var plug     = null;

	function Plugin () {
		"use strict";
	}

	Plugin.prototype = {

		fixAdminBar : function () {
			"use strict";

			// Workaround for the adminbar. Top rocks bottom s*cks so far. @todo
			this.adminbar = $( '#wpadminbar' );
			if ( this.adminbar.length !== 0 ) {

				$( '#ascrail2000' ).css( {
					top : this.adminbar.height()
				} );
			}
		},

		retrieveCursorBorder      : function () {
			"use strict";
			// Cursor border
			return nsrOptions.cursorborderwidth + ' ' +
				nsrOptions.cursorborderstate + ' ' +
				nsrOptions.cursorbordercolor;
		},
		retrieveRailPadding       : function () {
			"use strict";
			// Rail padding
			return {
				top    : nsrOptions.railpaddingtop,
				right  : nsrOptions.railpaddingright,
				bottom : nsrOptions.railpaddingleft,
				left   : nsrOptions.railpaddingleft
			};
		},
		retrieveAutohideMode      : function () {
			"use strict";
			// Autohidemode mode
			var Autohidemode = null;
			switch ( nsrOptions.autohidemode ) {
				case 'off':
					Autohidemode = false;
					break;
				case 'on':
					Autohidemode = true;
					break;
				case 'cursor':
					Autohidemode = 'cursor';
					break;
			}
			return Autohidemode;
		},
		retrieveRailOffset        : function () {
			"use strict";
			// Rail offset
			var Railoffset = nsrOptions.railoffset;
			if ( Railoffset == 'off' ) {
				Railoffset = false;
			}
			return Railoffset;
		},
		retrieveCursorFixedHeight : function () {
			"use strict";
			// Cursor fixed height
			var Cursorfixedheight = nsrOptions.cursorfixedheight;
			if ( Cursorfixedheight == 'off' ) {
				Cursorfixedheight = false;
			}
			return Cursorfixedheight;
		},

		setConfig     : function () {

			return ({
				zindex                  : nsrOptions.zindex,
				cursoropacitymin        : nsrOptions.cursoropacitymin,
				cursoropacitymax        : nsrOptions.cursoropacitymax,
				cursorcolor             : nsrOptions.cursorcolor,
				cursorwidth             : nsrOptions.cursorwidth,
				cursorborder            : this.retrieveCursorBorder(),
				cursorborderradius      : nsrOptions.cursorborderradius,
				scrollspeed             : nsrOptions.scrollspeed,
				mousescrollstep         : nsrOptions.mousescrollstep,
				touchbehavior           : nsrOptions.touchbehavior,
				hwacceleration          : nsrOptions.hwacceleration,
				usetransition           : nsrOptions.usetransition,
				boxzoom                 : nsrOptions.boxzoom,
				dblclickzoom            : nsrOptions.dblclickzoom,
				gesturezoom             : nsrOptions.gesturezoom,
				grabcursorenabled       : nsrOptions.grabcursorenabled,
				autohidemode            : this.retrieveAutohideMode(),
				background              : nsrOptions.background,
				iframeautoresize        : nsrOptions.iframeautoresize,
				cursorminheight         : nsrOptions.cursorminheight,
				preservenativescrolling : nsrOptions.preservenativescrolling,
				railoffset              : this.retrieveRailOffset(),
				railhoffset             : false/*this.Railhoffset*/,
				bouncescroll            : nsrOptions.bouncescroll,
				//spacebar                : nsrOptions.spacebar,
				spacebarenabled         : nsrOptions.spacebar,
				railpadding             : this.retrieveRailPadding(),
				disableoutline          : nsrOptions.disableoutline,
				horizrailenabled        : nsrOptions.horizrailenabled,
				railalign               : nsrOptions.railalign,
				railvalign              : nsrOptions.railvalign,
				enabletranslate3d       : nsrOptions.enabletranslate3d,
				enablemousewheel        : nsrOptions.enablemousewheel,
				enablekeyboard          : nsrOptions.enablekeyboard,
				smoothscroll            : nsrOptions.smoothscroll,
				sensitiverail           : nsrOptions.sensitiverail,
				enablemouselockapi      : nsrOptions.enablemouselockapi,
				//cursormaxheight         :false,
				cursorfixedheight       : this.retrieveCursorFixedHeight(),
				directionlockdeadzone   : nsrOptions.directionlockdeadzone,
				hidecursordelay         : nsrOptions.hidecursordelay,
				nativeparentscrolling   : nsrOptions.nativeparentscrolling,
				enablescrollonselection : nsrOptions.enablescrollonselection,
				overflowx               : nsrOptions.overflowx,
				overflowy               : nsrOptions.overflowy,
				cursordragspeed         : nsrOptions.cursordragspeed,
				rtlmode                 : nsrOptions.rtlmode,
				cursordragontouch       : nsrOptions.cursordragontouch,

				oneaxismousemode           : "auto",
				//scriptpath                 : getScriptPath(),
				preventmultitouchscrolling : true,
				disablemutationobserver    : false
			});
		},
		preStyler     : function ( styler ) {
			var opt = {};
			switch ( styler ) {
				case "fb":
					opt.autohidemode       = this.Autohidemode;
					opt.cursorcolor        = nsrOptions.cursorcolor;
					opt.railcolor          = nsrOptions.background;
					opt.cursoropacitymax   = nsrOptions.cursoropacitymax;
					opt.cursorwidth        = this.stretchedwidth;
					opt.cursorborder       = this.Cursorborder;
					opt.cursorborderradius = this.stretchedradius + "px";
					break;
			}
			return opt;
		},
		doStyler      : function ( styler, nc ) {
			if ( ! nc.rail ) {
				return;
			}

			if ( 'fb' == styler ) {

				nc.cursor.stop().animate( {
					width                   : nsrOptions.cursorwidth,
					"-webkit-border-radius" : plug.stretchedradius + "px",
					"-moz-border-radius"    : plug.stretchedradius + "px",
					"border-radius"         : plug.stretchedradius + "px"
				}, plug.duration );

				var obj = (nc.ispage) ? nc.rail : nc.win;

				function endHover () {
					nc._stylerfbstate = false;

					nc.cursor.stop().animate( {
						width             : nsrOptions.cursorwidth,
						"backgroundColor" : nsrOptions.background
					}, plug.duration );
				}

				obj.hover( function () {
						nc._stylerfbstate = true;

						nc.cursor.stop().animate( {
							width             : plug.stretchedwidth,
							"backgroundColor" : nsrOptions.background
						}, plug.duration );
					},
					function () {
						if ( nc.rail.drag ) {
							return;
						}
						endHover();
					} );

				$( document ).mouseup( function () {
					if ( nc._stylerfbstate ) {
						endHover();
					}
				} );
			}
		},
		runNicescroll : function () {
			"use strict";

			var _super = {
				"niceScroll"    : $.fn.niceScroll,
				"getNiceScroll" : $.fn.getNiceScroll
			};

			$.fn.niceScroll = function ( wrapper, opt ) {

				if ( ! (typeof wrapper == "undefined") ) {
					if ( typeof wrapper == "object" ) {
						opt     = wrapper;
						wrapper = false;
					}
				}

				var styler = (opt && opt.styler) || $.nicescroll.options.styler;

				if ( styler ) {
					var nw = plug.preStyler( styler );
					$.extend( nw, opt );
					opt = nw;
				}

				var ret = _super.niceScroll.call( this, wrapper, opt );

				if ( styler ) {
					plug.doStyler( styler, ret );
				}

				ret.scrollTo = function ( el ) {
					var off = this.win.position();
					var pos = this.win.find( el ).position();
					if ( pos ) {
						var top = Math.floor( pos.top - off.top + this.scrollTop() );
						this.doScrollTop( top );
					}
				};

				return ret;
			};

			$.fn.getNiceScroll = function ( index ) {
				var ret      = _super.getNiceScroll.call( this, index );
				ret.scrollTo = function ( el ) {
					this.each( function () {
						this.scrollTo.call( this, el );
					} );
				};
				return ret;
			};

			var nice = this.body.niceScroll( plug.config );

			var ncwidth = nsrOptions.cursorwidth.replace( 'px', '' );

			$.extend( nice.options, {

				styler : ncwidth < plug.stretchedwidth ? 'fb' : false
			} );

			if ( nsrOptions.defaultScrollbar ) {

				// Remove Nicescroll's scrollbar
				$( '#ascrail2000, #ascrail2000-hr' ).remove();

				// Set these styles
				if ( nsrOptions.view == 'frontend' ) {

					this.body.css( {
						'overflow-y'         : 'scroll',
						'-ms-overflow-style' : 'scrollbar',
						'overflow-style'     : 'scrollbar'
					} );
				}
				else {

					this.body.css( {
						'overflow-y'         : 'scroll',
						'-ms-overflow-style' : 'scrollbar',
						'overflow-style'     : 'scrollbar'
					} );
				}
			}
			else {

				if ( nsrOptions.view == 'frontend' ) {

					this.html.css( {
						'overflow-y'         : 'hidden',
						'-ms-overflow-style' : 'none',
						'overflow-style'     : 'none'
					} );
					this.body.css( {
						'overflow-y'         : 'hidden',
						'-ms-overflow-style' : 'none',
						'overflow-style'     : 'none'
					} );
				}
				else {

					this.body.css( {
						'overflow-y'         : 'hidden',
						'-ms-overflow-style' : 'none',
						'overflow-style'     : 'none'
					} );
				}
			}
		},
		init          : function () {
			"use strict";

			this.html = $( "html" );
			this.body = $( "body" );

			this.stretchedwidth  = 24;
			this.stretchedradius = 32;
			this.duration        = 200;

			this.fixAdminBar();
			this.config = this.setConfig();
			// A fix @todo
			plug        = this;
			this.runNicescroll();

			if ( nsrOptions.view == 'backend' ) {
				this.checkDOMChange();
			}

		},

		checkDOMChange : function () {

			if ( setTimeout( plug.checkDOMChange, 400 ) ) {

				plug.html.getNiceScroll().resize();
			}
		}

		/*observeDOM : function () {
		 var MutationObserver       = window.MutationObserver || window.WebKitMutationObserver,
		 eventListenerSupported = window.addEventListener;

		 return function ( obj, callback ) {
		 if ( MutationObserver ) {
		 // define a new observer
		 var obs = new MutationObserver( function ( mutations, observer ) {
		 if ( mutations[0].addedNodes.length || mutations[0].removedNodes.length ) {
		 callback();
		 }
		 } );
		 // have the observer observe foo for changes in children
		 obs.observe( obj, { childList : true, subtree : true } );
		 }
		 else if ( eventListenerSupported ) {
		 obj.addEventListener( 'DOMNodeInserted', callback, false );
		 obj.addEventListener( 'DOMNodeRemoved', callback, false );
		 }
		 }
		 },*/
	};

	$( document ).ready( function () {

		if ( ! isMobile ) {

			var plugin = new Plugin();
			plugin.init();
		}
	} );

})( jQuery );
