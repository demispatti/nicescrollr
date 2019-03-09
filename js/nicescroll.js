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

(function ( $ ){
    "use strict";

    $(document).ready(function (){

        var html = $("html");

        // Cursor border
        var Cursorborder = GlobalOptions.cursorborderwidth + ' ' +
            GlobalOptions.cursorborderstate + ' ' +
            GlobalOptions.cursorbordercolor;

        // Rail padding
        var Railpadding = "top: " + GlobalOptions.railpaddingtop +
            ",right: " + GlobalOptions.railpaddingright +
            ",  bottom: " + GlobalOptions.railpaddingbottom +
            ", left: " + GlobalOptions.railpaddingleft;

        // Autohidemode mode
        var Autohidemode = null;
        switch (GlobalOptions.autohidemode){
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
        if(Cursorfixedheight == 'off') {
            Cursorfixedheight = false;
        }

        // Rail offset
        var Railoffset = GlobalOptions.railoffset;
        if (Railoffset == 'off'){
            Railoffset = false;
        }

        // Nicescroll configuration object
        var config = ({
            zindex:                  GlobalOptions.zindex,
            cursoropacitymin:        GlobalOptions.cursoropacitymin,
            cursoropacitymax:        GlobalOptions.cursoropacitymax,
            cursorcolor:             GlobalOptions.cursorcolor,
            cursorwidth:             GlobalOptions.cursorwidth,
            cursorborder:            Cursorborder,
            cursorborderradius:      GlobalOptions.cursorborderradius,
            scrollspeed:             GlobalOptions.scrollspeed,
            mousescrollstep:         GlobalOptions.mousescrollstep,
            touchbehavior:           GlobalOptions.touchbehavior,
            hwacceleration:          GlobalOptions.hwacceleration,
            usetransition:           GlobalOptions.usetransition,
            boxzoom:                 GlobalOptions.boxzoom,
            dblclickzoom:            GlobalOptions.dblclickzoom,
            gesturezoom:             GlobalOptions.gesturezoom,
            grabcursorenabled:       GlobalOptions.grabcursorenabled,
            autohidemode:            Autohidemode,
            /*background              : GlobalOptions.background,*/
            iframeautoresize:        GlobalOptions.iframeautoresize,
            cursorminheight:         GlobalOptions.cursorminheight,
            preservenativescrolling: GlobalOptions.preservenativescrolling,
            railoffset:              Railoffset,
            bouncescroll:            GlobalOptions.bouncescroll,
            spacebar:                GlobalOptions.spacebar,
            railpadding:             Railpadding,
            disableoutline:          GlobalOptions.disableoutline,
            horizrailenabled:        GlobalOptions.horizrailenabled,
            railalign:               GlobalOptions.railalign,
            railvalign:              GlobalOptions.railvalign,
            enabletranslate3d:       GlobalOptions.enabletranslate3d,
            enablemousewheel:        GlobalOptions.enablemousewheel,
            enablekeyboard:          GlobalOptions.enablekeyboard,
            smoothscroll:            GlobalOptions.smoothscroll,
            sensitiverail:           GlobalOptions.sensitiverail,
            enablemouselockapi:      GlobalOptions.enablemouselockapi,
            /*cursormaxheight         : GlobalOptions.cursormaxheight,*/
            cursorfixedheight:       Cursorfixedheight,
            directionlockdeadzone:   GlobalOptions.directionlockdeadzone,
            hidecursordelay:         GlobalOptions.hidecursordelay,
            nativeparentscrolling:   GlobalOptions.nativeparentscrolling,
            enablescrollonselection: GlobalOptions.enablescrollonselection,
            overflowx:               GlobalOptions.overflowx,
            overflowy:               GlobalOptions.overflowy,
            cursordragspeed:         GlobalOptions.cursordragspeed,
            rtlmode:                 GlobalOptions.rtlmode,
            cursordragontouch:       GlobalOptions.cursordragontouch
        });

        // Let's roll
        html.niceScroll(config);

        // Checks for dom changes.
        function checkDOMChange () {

            if (setTimeout(checkDOMChange, 400)){

                html.getNiceScroll().resize();
            }
        }

        checkDOMChange();
    });

})(jQuery);
