( function( $ ) {
	$( document ).ready( function(){
	
		$( '#author_profile_picture_remove' ).on( 'click', function() {
			$('#author_profile_picture_url').val('');
			$( '.author_profile_picture_preview' ).attr( 'src', $( '.author_profile_picture_preview' ).data( 'preview' ) );
			$( this ).hide();
			$( '#upload_success' ).text( 'Picture removed, please now scroll to the bottom of this page and click Update Profile.' );
		});
		
		if( $( '#author_profile_picture_url' ).val() ) {
			$( '#author_profile_picture_remove' ).show();
		} else {
			$( '#author_profile_picture_remove' ).hide();
		}
		$( '#author_profile_picture_button' ).on( 'click', function( event ) {
	        var file_frame;

		    event.preventDefault();

		    // If the media frame already exists, reopen it.
		    if ( file_frame ) {
		    	file_frame.open();
		      	return;
		    }

		    // Create the media frame.
		    file_frame = wp.media.frames.file_frame = wp.media({
		    	title: $( this ).data( 'popup-title' ),
		      	button: {
		        	text: $( this ).data( 'popup-button-title' ),
		      	},
		      	multiple: false
		    });

		    // When an image is selected, run a callback.
		    file_frame.on( 'select', function() {
		      	var attachment = file_frame.state().get('selection').first();

		      	var thumburl = attachment.attributes.sizes.thumbnail;
		      	if ( thumburl.url ) {
					$( '#author_profile_picture_url' ).val( thumburl.url ); // updates our hidden field that will update our author's meta when the form is saved
					$( '.author_profile_picture_preview' ).attr( 'src', thumburl.url );
					$('#upload_success').text('Here is a preview of the profile picture you chose. To save it as your profile picture, scroll to the bottom of this page and click Update Profile.');
				}
				if( $( '#author_profile_picture_url' ).val() ) {
					$( '#author_profile_picture_remove' ).show();
				} else {
					$( '#author_profile_picture_remove' ).hide();
				}
		    });

		    // Finally, open the modal
		    file_frame.open();
		});
	});
});
