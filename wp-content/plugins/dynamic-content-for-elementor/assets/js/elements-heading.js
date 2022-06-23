( function( $ ) {
	var WidgetElementsTitleDCEHandler = function( $scope, $ ) {
		//console.log( 'pppppppppppppppp '+$scope );
		//alert('dce js'+$scope)
	};
	
	// Make sure you run this code under Elementor..
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/dyncontel-heading.default', WidgetElementsTitleDCEHandler );
	} );
} )( jQuery );
