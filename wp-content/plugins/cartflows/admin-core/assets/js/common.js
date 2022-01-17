( function ( $ ) {
	var wcf_back_step_button = function () {
		if ( 'cartflows_step' === typenow ) {
			var step_back_button = $( '#wcf-gutenberg-back-step-button' );

			if ( step_back_button.length > 0 ) {
				$( '#editor' )
					.find( '.edit-post-header__toolbar' )
					.append( step_back_button.html() );
			}
		}
	};

	// Copy the log to clipboard.
	var wcf_copy_the_log = function () {
		$( '.wcf-log--copy' ).on( 'click', function ( e ) {
			e.preventDefault();
			let $this = $( this );

			var copy_boundry = document.createRange();
			copy_boundry.selectNode(
				document.getElementById( 'wcf-log--text' )
			);
			window.getSelection().removeAllRanges();
			window.getSelection().addRange( copy_boundry );
			document.execCommand( 'copy' );
			window.getSelection().removeAllRanges();

			$this.text( $this.attr( 'data-success' ) );

			setTimeout( function () {
				$this.text( $this.attr( 'data-default' ) );
			}, 500 );
		} );
	};

	$( document ).on( 'ready', function ( $ ) {
		setTimeout( function () {
			wcf_back_step_button();
		}, 300 );

		// Copy the log to clipboard.
		wcf_copy_the_log();
	} );
} )( jQuery );
