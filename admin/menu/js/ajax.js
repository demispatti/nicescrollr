/**
 * The script for the ajax functionality.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/menu/js
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
(function ( $ ){
    'use strict';

    $(document).ready(function (){

        alertify.set({
            labels: {
                ok: nsrAjax.okiDoki,
                cancel: nsrAjax.noWayJose,
                buttonFocus: nsrAjax.noWayJose
            },
            delay: 3000
        });

        // This function process the ajax requests for the "reset settings" tasks.
        $(".nsr-reset-button").click(function ( event ){

            event.preventDefault();
            var id = event.target.id;
            var section = id.replace('reset_', '');

            var data = {
                id: id,
                section: section,
                action: 'reset_options',
                nonce: $(this).attr("data-nonce")
            };

            if( section == 'all'){

                alertify.confirm(nsrAjax.resetAllConfirmation, function ( e ){
                    if (e){
                        $.post(ajaxurl, data, function ( response ){

                            if (response.success == true){

                                alertify.success(response.data.success);
                            } else{

                                alertify.error(response.data.success);
                            }
                        });
                    } else{

                        return false;
                    }
                });

            } else if(section == 'plugin'){

                alertify.confirm(nsrAjax.resetPluginConfirmation, function ( e ){
                    if (e){
                        $.post(ajaxurl, data, function ( response ){

                            if (response.success == true){
                                alertify.success(response.data.success);
                            } else{
                                alertify.error(response.data.success);
                            }
                        });
                    } else{
                        return false;
                    }
                });

            } else if(section == 'backend'){

                alertify.confirm(nsrAjax.resetBackendConfirmation, function ( e ){
                    if (e){
                        $.post(ajaxurl, data, function ( response ){

                            if (response.success == true){

                                alertify.success(response.data.success);
                            } else{

                                alertify.error(response.data.success);
                            }
                        });
                    } else{

                        return false;
                    }
                });

            } else if(section == 'frontend'){

                alertify.confirm(nsrAjax.resetFrontendConfirmation, function ( e ){
                    if (e){
                        $.post(ajaxurl, data, function ( response ){

                            if (response.success == true){

                                alertify.success(response.data.success);
                            } else{

                                alertify.error(response.data.success);
                            }
                        });
                    } else{

                        return false;
                    }
                });

            } else{

                return false;
            }
        });
    });

})(jQuery);
