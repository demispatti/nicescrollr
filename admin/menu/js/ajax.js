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

jQuery( function ( $ ) {
	"use strict";

	function Plugin () {
	}

	Plugin.prototype = {

		setObjects  : function () {

			/*--------------------------------------------------
			 * Reset Button
			 *------------------------------------------------*/
			this.resetbutton = $( ".nsr-reset-button" );

			/*--------------------------------------------------
			 * Alertify
			 *------------------------------------------------*/
			/*alertify.set({
			 labels: {
			 ok         : nsrAjax.okiDoki,
			 cancel     : nsrAjax.noWayJose,
			 buttonFocus: nsrAjax.noWayJose
			 },
			 delay : 3000
			 });*/
		},
		setAlertify : function () {

			alertify.defaults = {
				// dialogs defaults
				autoReset        : true,
				basic            : false,
				closable         : true,
				closableByDimmer : true,
				frameless        : false,
				maintainFocus    : true, // <== global default not per instance, applies to all dialogs
				maximizable      : true,
				modal            : true,
				movable          : true,
				moveBounded      : false,
				overflow         : true,
				padding          : true,
				pinnable         : true,
				pinned           : true,
				preventBodyShift : false, // <== global default not per instance, applies to all dialogs
				resizable        : true,
				startMaximized   : false,
				transition       : 'zoom',

				// notifier defaults
				notifier : {
					// auto-dismiss wait time (in seconds)
					delay    : 8,
					// default position
					position : 'bottom-right'
				},

				// language resources
				glossary : {
					// dialogs default title
					title  : 'NSR',
					// ok button text
					ok     : nsrAjax.okiDoki,
					// cancel button text
					cancel : nsrAjax.noWayJose
				},

				// theme settings
				theme : {
					// class name attached to prompt dialog input textbox.
					input  : 'ajs-input',
					// class name attached to ok button
					ok     : 'ajs-ok',
					// class name attached to cancel button
					cancel : 'ajs-cancel'
				}
			};
		},
		bind        : function () {
			this.resetbutton.bind( 'click', { context : this }, this.resetButtonOnClick );
		},
		init        : function () {
			this.setObjects();
			this.setAlertify();
			this.bind();
		},

		resetButtonOnClick : function ( event ) {

			event.preventDefault();

			var id      = event.target.id;
			var section = id.replace( 'reset_', '' );

			var data = {
				id      : id,
				section : section,
				action  : 'reset_options',
				nonce   : $( this ).attr( "data-nonce" )
			};

			if ( section == 'all' ) {

				alertify.confirm().set( { 'title' : nsrAjax.resetAllConfirmationHeading } );
				alertify.confirm( nsrAjax.resetAllConfirmation, function ( e ) {
					if ( e ) {
						$.post( ajaxurl, data, function ( response ) {

							if ( response.success == true ) {

								alertify.success( response.data.success );
							}
							else {

								alertify.error( response.data.success );
							}
						} );
					}
					else {

						return false;
					}
				} );

			}
			else if ( section == 'plugin' ) {

				alertify.confirm().set( { 'title' : nsrAjax.resetPluginConfirmationHeading } );
				alertify.confirm( nsrAjax.resetPluginConfirmation, function ( e ) {
					if ( e ) {
						$.post( ajaxurl, data, function ( response ) {

							if ( response.success == true ) {
								alertify.success( response.data.success );
							}
							else {
								alertify.error( response.data.success );
							}
						} );
					}
					else {
						return false;
					}
				} );

			}
			else if ( section == 'backend' ) {

				alertify.confirm().set( { 'title' : nsrAjax.resetBackendConfirmationHeading } );
				alertify.confirm( nsrAjax.resetBackendConfirmation, function ( e ) {
					if ( e ) {
						$.post( ajaxurl, data, function ( response ) {

							if ( response.success == true ) {

								alertify.success( response.data.success );
							}
							else {

								alertify.error( response.data.success );
							}
						} );
					}
					else {

						return false;
					}
				} );

			}
			else if ( section == 'frontend' ) {

				alertify.confirm().set( { title : nsrAjax.resetFrontendConfirmationHeading, focus : 'no ' } );
				alertify.confirm( nsrAjax.resetFrontendConfirmation, function ( e ) {
					if ( e ) {
						$.post( ajaxurl, data, function ( response ) {

							if ( response.success == true ) {

								alertify.success( response.data.success );
							}
							else {

								alertify.error( response.data.success );
							}
						} );
					}
					else {

						return false;
					}
				} );

			}
			else {

				return false;
			}
		}

	};

	$( document ).ready( function () {

		var plugin = new Plugin();
		plugin.init();
	} );

} );
