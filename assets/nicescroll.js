/**
 * The file that pre-processes and then runs the passed parameters with Nicescroll.
 *
 * @link              https://wordpress.org/plugins/nicescrollr/
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/assets
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
"use strict";
jQuery(function ($) {

	let nicescrollrIsMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

	function Nicescrollr () {
		this.nsr_options = Nsr_Options;
		this.document = $(document);
		this.window = $(window);
		this.html = $('html');
		this.body = $('body');
		this.adminbar = $('#wpadminbar');
		this.editorContainer = $('#wp-content-editor-container');
	}

	Nicescrollr.prototype = {
		init: function () {
			this.runNicescroll();
			this.listenForDocumentChanges();
		},
		runNicescroll: function () {
			let config = this.getNicescrollConfiguration();
			this.body.niceScroll(this.getNicescrollConfiguration());
			this.runIframeHelper();
		},
		getNicescrollConfiguration: function () {

			return ({
				cursorcolor: this.nsr_options.cursorcolor,
				cursoropacitymin: this.nsr_options.cursoropacitymin,
				cursoropacitymax: this.nsr_options.cursoropacitymax,
				cursorwidth: this.nsr_options.cursorwidth,
				cursorborder: this.retrieveCursorBorder(),
				cursorborderradius: this.nsr_options.cursorborderradius,
				zindex: this.nsr_options.zindex,
				scrollspeed: this.nsr_options.scrollspeed,
				mousescrollstep: this.nsr_options.mousescrollstep,
				emulatetouch: this.nsr_options.emulatetouch,
				hwacceleration: this.nsr_options.hwacceleration,
				boxzoom: this.nsr_options.boxzoom,
				dblclickzoom: this.nsr_options.dblclickzoom,
				gesturezoom: this.nsr_options.gesturezoom,
				grabcursorenabled: this.nsr_options.grabcursorenabled,
				autohidemode: this.retrieveAutohideMode(),
				background: this.nsr_options.background,
				iframeautoresize: this.nsr_options.iframeautoresize,
				cursorminheight: this.nsr_options.cursorminheight,
				preservenativescrolling: this.nsr_options.preservenativescrolling,
				railoffset: this.retrieveRailOffset(),
				bouncescroll: this.nsr_options.bouncescroll,
				spacebarenabled: this.nsr_options.spacebarenabled,
				railpadding: this.retrieveRailPadding(),
				disableoutline: this.nsr_options.disableoutline,
				horizrailenabled: this.nsr_options.horizrailenabled,
				railalign: this.nsr_options.railalign,
				railvalign: this.nsr_options.railvalign,
				enabletranslate3d: this.nsr_options.enabletranslate3d,
				enablemousewheel: this.nsr_options.enablemousewheel,
				enablekeyboard: this.nsr_options.enablekeyboard,
				smoothscroll: this.nsr_options.smoothscroll,
				sensitiverail: this.nsr_options.sensitiverail,
				enablemouselockapi: this.nsr_options.enablemouselockapi,
				cursorfixedheight: this.retrieveCursorFixedHeight(),
				hidecursordelay: this.nsr_options.hidecursordelay,
				directionlockdeadzone: this.nsr_options.directionlockdeadzone,
				nativeparentscrolling: this.nsr_options.nativeparentscrolling,
				enablescrollonselection: this.nsr_options.enablescrollonselection,
				cursordragspeed: this.nsr_options.cursordragspeed,
				rtlmode: this.nsr_options.rtlmode,
				cursordragontouch: this.nsr_options.cursordragontouch,
				oneaxismousemode: this.nsr_options.oneaxismousemode,
				//scriptpath                 : this.getScriptPath(),
				preventmultitouchscrolling: this.nsr_options.preventmultitouchscrolling,
				disablemutationobserver: this.nsr_options.disablemutationobserver,
				enableobserver: this.nsr_options.enableobserver,
				scrollbarid: this.nsr_options.scrollbarid
			});
		},
		runIframeHelper: function() {

			let isIE = false;
			let ua = window.navigator.userAgent;
			let old_ie = ua.indexOf('Trident/');
			let new_ie = ua.indexOf('Edge');

			if((old_ie > - 1) || (new_ie > - 1)){

				if(old_ie > - 1){
					this.addMsieIframeHelper();
				} else {
					this.addEdgeIframeHelper();
				}

				return;
			}
			else if (navigator.userAgent.search("Firefox") >= 0) {
				this.addMozIframeHelper();

				return;
			}
			else if (navigator.userAgent.search("Chrome") >= 0) {
				this.addWebkitIframeHelper();

				return;
			}
			else if (navigator.userAgent.search("Opera") >= 0) {
				this.addWebkitIframeHelper();

				return;
			}
			else if (navigator.userAgent.search("Safari") >= 0) {
				this.addWebkitIframeHelper();
			}
		},

		retrieveCursorBorder: function () {

			return this.nsr_options.cursorborderwidth + ' ' +
				this.nsr_options.cursorborderstate + ' ' +
				this.nsr_options.cursorbordercolor;
		},
		retrieveRailPadding: function () {

			return {
				top: this.nsr_options.railpaddingtop,
				right: this.nsr_options.railpaddingright,
				bottom: this.nsr_options.railpaddingleft,
				left: this.nsr_options.railpaddingleft
			};
		},
		retrieveAutohideMode: function () {

			let autohidemode = this.nsr_options.autohidemode;

			if ('enabled' === autohidemode || 'true' === autohidemode) {
				autohidemode = true;
			}
			if ('disabled' === autohidemode || 'false' === autohidemode) {
				autohidemode = false;
			}

			return autohidemode;
		},
		retrieveRailOffset: function () {

			let Railoffset = this.nsr_options.railoffset;
			if ('false' === Railoffset) {
				Railoffset = false;
			}
			return Railoffset;
		},
		retrieveCursorFixedHeight: function () {

			let Cursorfixedheight = this.nsr_options.cursorfixedheight;
			if ('false' === Cursorfixedheight) {
				Cursorfixedheight = false;
			}
			return Cursorfixedheight;
		},

		listenForDocumentChanges: function () {
			let $this = this;

			MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
			let observer = new MutationObserver(function () {
				// fired when a mutation occurs
				$this.triggerResize();
			});
			// define what element should be observed by the observer
			// and what types of mutations trigger the callback
			observer.observe(document, {
				subtree: true,
				attributes: true,
				childList: true
			});
		},

		addWebkitIframeHelper: function() {
			this.body.addClass('nsr-is-webkit');
			let $this = this;
			this.editorContainer.on({
				mouseenter: function(){
					$this.body.attr('style', 'overflow-y: initial');
				},
				mouseleave: function () {
					$this.body.attr('style', 'overflow-y: hidden');
				},
			});
			if(this.editorContainer.has(':hover')){
				$this.editorContainer.trigger('mouseenter');
			} else {
				$this.editorContainer.trigger('mouseleave');
			}
		},
		addMozIframeHelper: function () {
			this.body.addClass('nsr-is-moz');
			let $this = this;
			let mozBody = this.html;
			this.editorContainer.on({
				mouseenter: function () {
					mozBody.attr('style', 'scrollbar-width: none');
					$this.body.attr('style', 'overflow-y: initial');
				},
				mouseleave: function () {
					mozBody.attr('style', 'scrollbar-width: none');
					$this.body.attr('style', 'overflow-y: hidden');
				},
			});
		},
		addMsieIframeHelper: function () {
			this.html.addClass('nsr-is-msie');
			let $this = this;
			this.editorContainer.on({
				mouseenter: function () {
					$this.body.attr('style', '-ms-overflow-style: none');
					$this.html.attr('style', '-ms-overflow-y: initial');
				},
				mouseleave: function () {
					$this.body.attr('style', '-ms-overflow-style: none');
					$this.html.attr('style', '-ms-overflow-y: initial');
				},
			});
		},
		addEdgeIframeHelper: function () {
			this.body.addClass('nsr-is-edge');
			let $this = this;
			this.editorContainer.on({
				mouseenter: function () {
					$this.body.attr('style', '-ms-overflow-style: none');
					$this.body.attr('style', '-ms-overflow-y: initial');
				},
				mouseleave: function () {
					$this.body.attr('style', '-ms-overflow-style: none');
					$this.body.attr('style', '-ms-overflow-y: initial');
				},
			});
		},
		triggerResize: function () {
			let $this = this;

			setTimeout(function () {
				$this.body.getNiceScroll().resize();
			}, 120);
		}
	};

	$(document).ready(function () {
		if (false === nicescrollrIsMobile && Nsr_Options.enabled === '1' || true === nicescrollrIsMobile && Nsr_Options.enabled === '1' && Nsr_Options.mobile_devices_enabled === '1') {
			let nicescrollr = new Nicescrollr();
			nicescrollr.init();
		}
	});

});
