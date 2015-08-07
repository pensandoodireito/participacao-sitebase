(function($) {
	$(document).ready( function() {
		$( '#cntctfrm_show_multi_notice' ).removeAttr('href title').css('cursor', 'pointer');

		$( '#cntctfrm_change_label' ).change( function() {
			if ( $( this ).is( ':checked' ) ) {
				$( '.cntctfrm_change_label_block' ).removeClass( 'cntctfrm_hidden' );
			} else {
				$( '.cntctfrm_change_label_block' ).addClass( 'cntctfrm_hidden' );
			}
		});
		$( '#cntctfrm_display_add_info' ).change( function() {
			if ( $( this ).is( ':checked' ) ) {
				$( '.cntctfrm_display_add_info_block' ).removeClass( 'cntctfrm_hidden' );
			} else {
				$( '.cntctfrm_display_add_info_block' ).addClass( 'cntctfrm_hidden' );
			}
		});
		$( '#cntctfrm_add_language_button' ).click( function() {
			$.ajax({
				url: '../wp-admin/admin-ajax.php',/* update_url, */
				type: "POST",
				data: "action=cntctfrm_add_language&lang=" + $( '#cntctfrm_languages' ).val() + '&cntctfrm_ajax_nonce_field=' + cntctfrm_ajax.cntctfrm_nonce,
				success: function( result ) {
					var text = $.parseJSON( result );
					var lang_val = $( '#cntctfrm_languages' ).val();
					$( '.cntctfrm_change_label_block .cntctfrm_language_tab, .cntctfrm_action_after_send_block .cntctfrm_language_tab' ).each( function() {
						$( this ).addClass( 'hidden' );
					});
					$( '.cntctfrm_change_label_block .cntctfrm_language_tab' ).first().clone().appendTo( '.cntctfrm_change_label_block' ).removeClass( 'hidden' ).removeClass( 'cntctfrm_tab_en' ).addClass( 'cntctfrm_tab_' + lang_val );
					$( '.cntctfrm_action_after_send_block .cntctfrm_language_tab' ).first().clone().insertBefore( '#cntctfrm_before' ).removeClass( 'hidden' ).removeClass( 'cntctfrm_tab_en' ).addClass( 'cntctfrm_tab_' + lang_val );
					$( '.cntctfrm_change_label_block .cntctfrm_language_tab' ).last().find( 'input' ).each( function() {
						$( this ).val( '' );
						$( this ).attr( 'name', $( this ).attr( 'name' ).replace( '[en]', '[' + lang_val + ']' ) );
					});
					$( '.cntctfrm_change_label_block .cntctfrm_language_tab' ).last().find( '.cntctfrm_info' ).last().html( text );
					$( '.cntctfrm_action_after_send_block .cntctfrm_language_tab' ).last().find( 'input' ).val( '' ).attr( 'name', $( '.cntctfrm_action_after_send_block .cntctfrm_language_tab' ).last().find( 'input' ).attr( 'name' ).replace( '[en]', '[' + lang_val + ']' ) );
					$( '.cntctfrm_action_after_send_block .cntctfrm_language_tab' ).last().find( '.cntctfrm_info' ).last().html( text );
					$( '.cntctfrm_change_label_block .cntctfrm_label_language_tab, .cntctfrm_action_after_send_block .cntctfrm_label_language_tab' ).each( function() {
						$( this ).removeClass( 'cntctfrm_active' );
					});
					$( '.cntctfrm_change_label_block .clear' ).prev().clone().attr( 'id', 'cntctfrm_label_' + lang_val ).addClass( 'cntctfrm_active' ).html( $( '#cntctfrm_languages option:selected' ).text() + ' <span class="cntctfrm_delete" rel="' + lang_val + '">X</span>').insertBefore( '.cntctfrm_change_label_block .clear' );
					$( '.cntctfrm_action_after_send_block .clear' ).prev().clone().attr( 'id', 'cntctfrm_text_' + lang_val ).addClass( 'cntctfrm_active' ).html( $( '#cntctfrm_languages option:selected' ).text() + ' <span class="cntctfrm_delete" rel="' + lang_val + '">X</span>').insertBefore( '.cntctfrm_action_after_send_block .clear' );
					$( '#cntctfrm_languages option:selected' ).remove();
				},
				error: function( request, status, error ) {
					alert( error + request.status );
				}
			});
		});
		$( '.cntctfrm_language_tab_block' ).css( 'display', 'none' );
		$( '.cntctfrm_language_tab_block_mini' ).css( 'display', 'block' );
		$( '.cntctfrm_help_box' ).mouseover( function() {
			$( this ).children().css( 'display', 'block' );
		});
		$( '.cntctfrm_help_box' ).mouseout( function() {
			$( this ).children().css( 'display', 'none' );
		});

		/* add notice about changing in the settings page */
		$( '#cntctfrm_settings_form input' ).bind( "change click select", function() {
			if ( $( this ).attr( 'type' ) != 'submit' ) {
				$( '.updated.fade' ).css( 'display', 'none' );
				$( '#cntctfrm_settings_notice' ).css( 'display', 'block' );
			};
		});
		$( 'select[name="cntctfrm_user_email"]').focus( function() {
			$('#cntctfrm_select_email_user').attr( 'checked', 'checked' );
			$( '.updated.fade' ).css( 'display', 'none' );
			$( '#cntctfrm_settings_notice' ).css( 'display', 'block' );
		});
	});
	$(document).on( "click", ".cntctfrm_language_tab_block_mini", function() {
		if ( $( '.cntctfrm_language_tab_block' ).css( 'display' ) == 'none' ) {
			$( '.cntctfrm_language_tab_block' ).css( 'display', 'block' );
			$( '.cntctfrm_language_tab_block_mini' ).css( 'background-position', '1px -3px' );
		} else {
			$( '.cntctfrm_language_tab_block' ).css( 'display', 'none' );
			$( '.cntctfrm_language_tab_block_mini' ).css( 'background-position', '' );
		}
	});
	$(document).on( "click", ".cntctfrm_change_label_block .cntctfrm_label_language_tab", function() {
		$( '.cntctfrm_label_language_tab' ).each( function() {
			$( this ).removeClass( 'cntctfrm_active' );
		});
		var index = $( '.cntctfrm_change_label_block .cntctfrm_label_language_tab' ).index( $( this ) );
		$( this ).addClass( 'cntctfrm_active' );
		var blocks = $( '.cntctfrm_action_after_send_block .cntctfrm_label_language_tab' );
		$( blocks[ index ] ).addClass( 'cntctfrm_active' );
		$( '.cntctfrm_language_tab' ).each( function() {
			$( this ).addClass( 'hidden' );
		});
		$( '.' + this.id.replace( 'label', 'tab' ) ).removeClass( 'hidden' );
	});
	$(document).on( "click", ".cntctfrm_action_after_send_block .cntctfrm_label_language_tab", function() {
		$( '.cntctfrm_label_language_tab' ).each( function() {
			$( this ).removeClass( 'cntctfrm_active' );
		});
		var index = $( '.cntctfrm_action_after_send_block .cntctfrm_label_language_tab' ).index( $( this ) );
		$( this ).addClass( 'cntctfrm_active' );
		var blocks = $( '.cntctfrm_change_label_block .cntctfrm_label_language_tab' );
		$( blocks[ index ] ).addClass( 'cntctfrm_active' );
		$( '.cntctfrm_language_tab' ).each( function() {
			$( this ).addClass( 'hidden' );
		});
		console.log( this.id.replace( 'text', 'tab' ), index );
		$( '.' + this.id.replace( 'text', 'tab' ) ).removeClass( 'hidden' );
	});
	$(document).on( "click", ".cntctfrm_delete", function( event ) {
		event.stopPropagation();
		if ( confirm( cntctfrm_ajax.cntctfrm_confirm_text ) ) {
			var lang = $( this ).attr( 'rel' );
			$.ajax({
				url: '../wp-admin/admin-ajax.php',/* update_url, */
				type: "POST",
				data: "action=cntctfrm_remove_language&lang=" + lang + '&cntctfrm_ajax_nonce_field=' + cntctfrm_ajax.cntctfrm_nonce,
				success: function( result ) {
					$( '#cntctfrm_label_' + lang + ', #cntctfrm_text_' + lang + ', .cntctfrm_tab_' + lang ).each( function() {
						$( this ).remove();
					});
					$( '.cntctfrm_change_label_block .cntctfrm_label_language_tab' ).removeClass( 'cntctfrm_active' ).first().addClass( 'cntctfrm_active' );
					$( '.cntctfrm_action_after_send_block .cntctfrm_label_language_tab' ).removeClass( 'cntctfrm_active' ).first().addClass( 'cntctfrm_active' );
					$( '.cntctfrm_change_label_block .cntctfrm_language_tab' ).addClass( 'hidden' ).first().removeClass( 'hidden' );
					$( '.cntctfrm_action_after_send_block .cntctfrm_language_tab' ).addClass( 'hidden' ).first().removeClass( 'hidden' );
				},
				error: function( request, status, error ) {
					alert( error + request.status );
				}
			});
		}
	});
})(jQuery);