jQuery( function( $ ) {

	$( function() {
		$( '.wp-translations-update-row' ).each( function() {

			var list = $( this );
			$( list ).prev().addClass('update');

		});
	});

	$( "[id^=wp-translations-update-]" ).live( "click", function(e) {
		e.preventDefault();

		var slug = $(this).attr('data-slug');


    $.ajax({
			type: "POST",
			url: wpt_update_ajax.ajaxurl,
			data: {
				'action': 'wp_translations_update_translations',
				'nonce': wpt_update_ajax.nonce,
				'slug' : slug,
			},
			beforeSend: function(reponse) {
				$( 'tr[data-slug="'+ slug +'"]' ).addClass('updated');
				$( 'tr[data-slug="'+ slug +'"] .notice' ).addClass( 'updating-message' );
			},
	  })

    .done( function( response, textStatus, jqXHR ) {
			$( 'tr[data-slug="'+ slug +'"]' ).removeClass('updated');
			$( 'tr[data-slug="'+ slug +'"] .notice' ).removeClass( 'notice-warning updating-message' ).addClass( 'updated-message notice-success' ).html( response );
		});

	});

});
