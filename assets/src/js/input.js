(function( $ ) {
	
	// Slider Search
	var SliderSearch = function ( $el ) {

		var search   = this,
				selected = {},
				prefix   = '.acf-generous-slider--search',
				account  = $( prefix + '-account', $el ).val(),
				$title   = $( prefix + '-input-title', $el ),
				$id      = $( prefix + '-input-id', $el ),
				$results = $( prefix + '-results', $el ),
				timer;

		this.init = function () {

			if ( typeof account !== 'undefined' && account !== '' ) {
				search.prepare();
				search.bind();
			}

			return search;

		};

		this.prepare = function () {

			if ( $id.val() === '' ) {
				selected.id = false;
				selected.title = false;
			} else {
				selected.id = $id.val();
				selected.title = $title.val();
			}

		};

		this.bind = function () {

			$title.keyup(function( event ) {
				var value = $( this ).val();

				search.clearTimer();

				if ( value !== '' ) {
					timer = setTimeout(function() {
						search.request( value );
					}, 250);
				} else {
					search.clearTimer();
					search.resetResults();
					search.removeSelection();
				}
			});

			$title.blur(function() {
				setTimeout(function() {
					search.clearTimer();
					search.resetResults();

					if ( selected.id === false ) {
						search.removeSelection();
					} else {
						$title.val( selected.title );
					}
				}, 300);
			});

		};

		this.request = function ( term ) {

			$.ajax({
				type: 'GET',
				url: 'https://api.genero.us/v0/accounts/' + account + '/search',
				data: {
					term: term
				},
				dataType:'json',
				success: function( response ) {
					search.output( response, $title );
				}
			});

		};

		this.output = function ( response, inputItem ) {

			if ( inputItem.val() !== '' ) {
				search.resetResults();

				if ( ! response.error ) {
					for ( var i = 0; i < response.length; i++ ) {
						var result = search.create( response[ i ] );
						$results.append( result );
					}
				}
			}

		};

		this.create = function ( current ) {

			var $result = $( '<div>' )
					.addClass( 'acf-generous-slider--search-result' )
					.attr( 'data-slider-id', current.id )
					.text( current.title );

			$result.on('click', function() {
				search.setSelection( current.id, current.title );
				search.resetResults();
			});

			return $result;

		};

		this.setSelection = function ( id, title ) {

			$id.val( id );
			$title.val( title );

			selected.id = id;
			selected.title = title;

		};

		this.removeSelection = function () {

			search.setSelection( '', '' );

			selected.id = false;
			selected.title = false;

		};

		this.resetResults = function () {

			$results.html( '' );

		};

		this.clearTimer = function () {

			clearInterval( timer );

		};

		return this.init();

	};

	// Initiate ACF
	if( typeof acf.add_action !== 'undefined' ) {

		// (ACF5) ready append
		acf.add_action( 'ready append', function( $el ) {
			acf.get_fields( { type : 'generous_slider'}, $el ).each( function() {
				new SliderSearch( $( this ) );
			});
		});

	} else {

		// (ACF4) acf/setup_fields
		$( document ).live( 'acf/setup_fields', function( event, postbox ) {
			$( postbox ).find( '.field[data-field_type="generous_slider"]' ).each( function() {
				new SliderSearch( $( this ) );
			});
		});

	}

})(jQuery);