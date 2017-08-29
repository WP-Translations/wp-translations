jQuery( function( $ ) {

	$( function() {

		$( '.theme' ).each( function() {

				var slug = $(this).attr('data-slug');

				if( wpt_update_ajax.themes_translations.hasOwnProperty( slug ) ) {

					var langs = wpt_update_ajax['themes_translations'][''+slug+'']['updates'] + ' <button id="wp-translations-update-' + slug + '" class="button-link" type="button" data-type="themes" data-slug="' + slug + '">Update now</button>';
					if ( $( '.theme[data-slug="'+ slug +'"] .notice' ).length ) {
						$( '.theme[data-slug="'+ slug +'"] .notice').append( '<p>' + wpt_update_ajax.update_message + ' ' + langs + '</p>' );
					} else {
						$( '.theme[data-slug="'+ slug +'"]').append('<div class="wp-translations-notice update-message notice inline notice-warning notice-alt"><p>' +  wpt_update_ajax.update_message + '' + langs + '</p></div>');
					}

				}

		});

	});

	$( function() {
		$( '.wp-translations-update-row' ).each( function() {

			var list = $( this );
			$( list ).prev().addClass('update');

		});
	});

	$( "[id^=wp-translations-update-]" ).live( "click", function(e) {
		e.preventDefault();

		var slug = $(this).attr('data-slug');
		var type = $(this).attr('data-type');

		$.ajax({
			type: "POST",
			url: wpt_update_ajax.ajaxurl,
			data: {
				'action': 'wp_translations_update_translations',
				'nonce': wpt_update_ajax.nonce,
				'slug' : slug,
				'type' : type,
			},
			beforeSend: function(reponse) {
				if( 'plugins' == type ) {
					$( '[data-slug="'+ slug +'"]' ).addClass('updated');
				}
				$( '[data-slug="'+ slug +'"] .wp-translations-notice' ).addClass( 'updating-message' );
			},
		})

		.done( function( response, textStatus, jqXHR ) {
			if( 'plugins' == type ) {
				$( '[data-slug="'+ slug +'"]' ).removeClass('updated');
			}
			$( '[data-slug="'+ slug +'"] .wp-translations-notice' ).removeClass( 'notice-warning updating-message' ).addClass( 'updated-message notice-success' ).html( response );
		});

	});

	$( ".wp-translations-edit-rule" ).live( "click", function(e) {
		e.preventDefault();

		var id = $(this).attr('data-id');
		var name = $(this).attr('data-name');

		$.ajax({
			type: "POST",
			url: wpt_update_ajax.ajaxurl,
			data: {
				'action': 'wp_translations_quick_edit_form',
				'nonce': wpt_update_ajax.nonce,
				'id': id,
				'name': name,
			},
		})

		.done( function( response, textStatus, jqXHR ) {

			$( '#domain-' + id ).after("<tr class='inline-edit-row inline-edit-row-page inline-edit-page quick-edit-row quick-edit-row-page inline-edit-page inline-editor'><td class='colspanchange' colspan='5'>" + response + "</td></tr>");
			$( '#domain-' + id ).hide();
		});
	});

	$( ".wp-translations-inline-cancel" ).live( "click", function(e) {
		e.preventDefault();
		var id = $(this).attr('data-id');

		$( '.inline-edit-row' ).remove();
		$( '#domain-' + id ).show();

	});

});
