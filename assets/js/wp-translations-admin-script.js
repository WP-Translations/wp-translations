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

					$( '[data-slug="'+ slug +'"]' ).addClass('updated');
					$( '[data-slug="'+ slug +'"] .wp-translations-notice' ).addClass( 'updating-message' );

			},
		})

		.done( function( response, textStatus, jqXHR ) {

				$( '[data-slug="'+ slug +'"]' ).removeClass('updated');
				$( '[data-slug="'+ slug +'"] .wp-translations-notice' ).removeClass( 'notice-warning updating-message' ).addClass( 'updated-message notice-success' ).html( response );

		});

	});

});
