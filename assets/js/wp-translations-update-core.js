jQuery( function( $ ) {

	$( 'form[name="upgrade-translations"] .button' ).live( "click", function(e) {
		e.preventDefault();

		$(".translations [type=checkbox]:checked").each ( function() {
				var slug = $( this ).val();
				var type = $( this ).attr( 'data-type' );

				$.ajax({
					type: "POST",
					url: wpt_update_core.ajaxurl,
					data: {
						'action': 'wp_translations_update_translations',
						'nonce': wpt_update_core.nonce,
						'slug' : slug,
						'type' : type,
					},
					beforeSend: function(reponse) {
							$( '#wp-translations-update-'+ slug ).addClass( 'updating-message' ).html( wpt_update_core.updating_message );
					}
				})

				.done( function( response, textStatus, jqXHR ) {
						$( '#wp-translations-update-'+ slug ).removeClass( 'updating-message wp-translations-to-update' ).addClass( 'updated-message' ).html( wpt_update_core.updated_message );

						var availables_updates = $( '.wp-translations-to-update' ).length;
						if( availables_updates == 0 ) {
							$( '#update-translations-table' ).slideUp("slow").remove();
							$( 'form[name="upgrade-translations"] .button' ).remove();
							$( 'form[name="upgrade-translations"] p:first-of-type' ).html( wpt_update_core.all_updated_message );
						}
				});

		});


	});

	$( '[id^="wp-translations-update-"]' ).live( "click", function(e) {
		e.preventDefault();

		var slug = $( this ).attr( 'data-slug' );
		var type = $( this ).attr( 'data-type' );

		$.ajax({
			type: "POST",
			url: wpt_update_core.ajaxurl,
			data: {
				'action': 'wp_translations_update_translations',
				'nonce': wpt_update_core.nonce,
				'slug' : slug,
				'type' : type,
			},
			beforeSend: function(reponse) {
					$( '#wp-translations-update-'+ slug ).addClass( 'updating-message' ).html( wpt_update_core.updating_message );
			}
		})

		.done( function( response, textStatus, jqXHR ) {
				$( '#wp-translations-update-' + slug ).removeClass( 'updating-message wp-translations-to-update' ).addClass( 'updated-message' ).html( wpt_update_core.updated_message );
				$( '#wp-translations-update-result-' + slug ).html( response );

				var availables_updates = $( '.wp-translations-to-update' ).length;
				if( availables_updates == 0 ) {
					$( '#update-translations-table' ).slideUp().remove();
					$( 'form[name="upgrade-translations"] .button' ).remove();
					$( 'form[name="upgrade-translations"] p:first-of-type' ).html( wpt_update_core.all_updated_message );
				}
		});

	});

});
