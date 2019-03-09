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

(function( $ ){

	var html = $( "html" );

	// Cursor border
	var Cursorborder = GlobalOptions.cursorborderwidth + ' ' +
		GlobalOptions.cursorborderstate + ' ' +
		GlobalOptions.cursorbordercolor;

	// Rail padding
	var Railpadding = "top: " + GlobalOptions.railpaddingtop +
		", right: " + GlobalOptions.railpaddingright +
		", bottom: " + GlobalOptions.railpaddingbottom +
		", left: " + GlobalOptions.railpaddingleft;

	// Autohidemode mode
	var Autohidemode = null;
	switch( GlobalOptions.autohidemode ){
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

	// Cursor fixed height
	var Cursorfixedheight = GlobalOptions.cursorfixedheight;
	if( Cursorfixedheight == 'off' ){
		Cursorfixedheight = false;
	}

	// Rail offset
	var Railoffset = GlobalOptions.railoffset;
	if( Railoffset == 'off' ){
		Railoffset = false;
	}

	// Nicescroll configuration object
	var config = ({
		zindex: GlobalOptions.zindex,
		cursoropacitymin: GlobalOptions.cursoropacitymin,
		cursoropacitymax: GlobalOptions.cursoropacitymax,
		cursorcolor: GlobalOptions.cursorcolor,
		cursorwidth: GlobalOptions.cursorwidth,
		cursorborder: Cursorborder,
		cursorborderradius: GlobalOptions.cursorborderradius,
		scrollspeed: GlobalOptions.scrollspeed,
		mousescrollstep: GlobalOptions.mousescrollstep,
		touchbehavior: GlobalOptions.touchbehavior,
		hwacceleration: GlobalOptions.hwacceleration,
		usetransition: GlobalOptions.usetransition,
		boxzoom: GlobalOptions.boxzoom,
		dblclickzoom: GlobalOptions.dblclickzoom,
		gesturezoom: GlobalOptions.gesturezoom,
		grabcursorenabled: GlobalOptions.grabcursorenabled,
		autohidemode: Autohidemode,
		background: GlobalOptions.background,
		iframeautoresize: GlobalOptions.iframeautoresize,
		cursorminheight: GlobalOptions.cursorminheight,
		preservenativescrolling: GlobalOptions.preservenativescrolling,
		railoffset: Railoffset,
		bouncescroll: GlobalOptions.bouncescroll,
		spacebar: GlobalOptions.spacebar,
		railpadding: Railpadding,
		disableoutline: GlobalOptions.disableoutline,
		horizrailenabled: GlobalOptions.horizrailenabled,
		railalign: GlobalOptions.railalign,
		railvalign: GlobalOptions.railvalign,
		enabletranslate3d: GlobalOptions.enabletranslate3d,
		enablemousewheel: GlobalOptions.enablemousewheel,
		enablekeyboard: GlobalOptions.enablekeyboard,
		smoothscroll: GlobalOptions.smoothscroll,
		sensitiverail: GlobalOptions.sensitiverail,
		enablemouselockapi: GlobalOptions.enablemouselockapi,
		/*cursormaxheight         : GlobalOptions.cursormaxheight,*/
		cursorfixedheight: Cursorfixedheight,
		directionlockdeadzone: GlobalOptions.directionlockdeadzone,
		hidecursordelay: GlobalOptions.hidecursordelay,
		nativeparentscrolling: GlobalOptions.nativeparentscrolling,
		enablescrollonselection: GlobalOptions.enablescrollonselection,
		overflowx: GlobalOptions.overflowx,
		overflowy: GlobalOptions.overflowy,
		cursordragspeed: GlobalOptions.cursordragspeed,
		rtlmode: GlobalOptions.rtlmode,
		cursordragontouch: GlobalOptions.cursordragontouch
	});

	/* jquery.nicescroll.plus
	 -- the addon for nicescroll
	 -- version 1.0.0 BETA
	 -- copyright 13 InuYaksa*2013
	 -- licensed under the MIT
	 --
	 -- http://areaaperta.com/nicescroll
	 -- https://github.com/inuyaksa/jquery.nicescroll
	 --
	 */
	if( !$ || !("nicescroll" in $) ){
		return;
	}

	// Checks if the the default scrollbar option is active.

	var stretchedwidth = 16;

	var stretchedradius = 32;

	var duration = 200;

	var ncwidth = GlobalOptions.cursorwidth.replace( 'px', '' );

	$.extend( $.nicescroll.options, {

		styler: ncwidth < stretchedwidth ? 'fb' : false
	} );

	var _super = {
		"niceScroll": $.fn.niceScroll,
		"getNiceScroll": $.fn.getNiceScroll
	};

	$.fn.niceScroll = function( wrapper, opt ){

		if( !(typeof wrapper == "undefined") ){
			if( typeof wrapper == "object" ){
				opt = wrapper;
				wrapper = false;
			}
		}

		var styler = (opt && opt.styler) || $.nicescroll.options.styler;

		if( styler ){
			var nw = preStyler( styler );
			$.extend( nw, opt );
			opt = nw;
		}

		var ret = _super.niceScroll.call( this, wrapper, opt );

		if( styler ) doStyler( styler, ret );

		ret.scrollTo = function( el ){
			var off = this.win.position();
			var pos = this.win.find( el ).position();
			if( pos ){
				var top = Math.floor( pos.top - off.top + this.scrollTop() );
				this.doScrollTop( top );
			}
		};

		return ret;
	};

	$.fn.getNiceScroll = function( index ){
		var ret = _super.getNiceScroll.call( this, index );
		ret.scrollTo = function( el ){
			this.each( function(){
				this.scrollTo.call( this, el );
			} );
		};
		return ret;
	};

	function preStyler( styler ){
		var opt = {};
		switch( styler ){
			case "fb":
				opt.autohidemode = Autohidemode;
				opt.cursorcolor = GlobalOptions.cursorcolor;
				opt.railcolor = GlobalOptions.background;
				opt.cursoropacitymax = GlobalOptions.cursoropacitymax;
				opt.cursorwidth = stretchedwidth;
				opt.cursorborder = Cursorborder;
				opt.cursorborderradius = stretchedradius + "px";
				break;
		}
		return opt;
	}

	function doStyler( styler, nc ){
		if( !nc.rail ) return;

		switch( styler ){
			case "fb":

				nc.cursor.stop().animate( {
					width: GlobalOptions.cursorwidth,
					"-webkit-border-radius": stretchedradius + "px",
					"-moz-border-radius": stretchedradius + "px",
					"border-radius": stretchedradius + "px"
				}, duration );

				var obj = (nc.ispage) ? nc.rail : nc.win;

			function endHover(){
				nc._stylerfbstate = false;

				nc.cursor.stop().animate( {
					width: GlobalOptions.cursorwidth,
					"backgroundColor": GlobalOptions.background
				}, duration );
			}

				obj.hover( function(){
						nc._stylerfbstate = true;

						nc.cursor.stop().animate( {
							width: stretchedwidth,
							"backgroundColor": GlobalOptions.background
						}, duration );
					},
					function(){
						if( nc.rail.drag ) return;
						endHover();
					} );

				$( document ).mouseup( function(){
					if( nc._stylerfbstate ) endHover();
				} );

				break;
		}
	}

	function run() {

		if(GlobalOptions.defaultScrollbar){
			var nice = html.niceScroll(config);
			// Hides the rail and the scrollbar.
			nice.hide();
			// Forces display of the default scrollbar in IE.
			$('body').css({
				'-ms-overflow-style': 'scrollbar'
			});
		} else{
			var nice = html.niceScroll(config);
		}

		// Workaround for the adminbar. Top rocks bottom s*cks so far. @todo
		var adminbar = $('#wpadminbar');
		if(adminbar.length !== 0){

			$('#ascrail2000').css({
				top: adminbar.height()
			});
		}
	}


	// Checks for dom changes.
	function checkDOMChange(){

		if(setTimeout(checkDOMChange, 400)){

			html.getNiceScroll().resize();
		}
	}

	$( document ).ready( function(){

		// Let's roll.
		var hasMaxWidth = window.matchMedia("only screen and (max-width: 960px)");
		var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

		// Checks for mobile devices with an extra check on devices smaller than 960px. Nicescroll will be disabled on mobile devices with a max-width below 960px.
		if(isMobile && !hasMaxWidth.matches){
			run();
			checkDOMChange();
		} else if(!isMobile) {
			run();
			checkDOMChange();
		}

	} );

})( jQuery );
