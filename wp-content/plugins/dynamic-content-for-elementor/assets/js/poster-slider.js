( function( $ ) {
	var WidgetElements_PosterSliderHandler = function( $scope, $ ) {
		console.log( $scope );
		//alert('posterSlider');

		$scope.find('.dce-sliderposter-wrap').slick({
			dots: true,
			infinite: false,
			speed: 300,
			slidesToShow: 1,//$num_col,
			slidesToScroll: 1, //$num_col,
			dots: false,
			fade: true,
		    //centerMode: true,
		    focusOnSelect: true,
		    infinite: true,
			speed: 700,
			prevArrow : '<button type="button" class="slick-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
			nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',
			//slidesToShow: 1,
			
			//variableWidth: true,
			/*responsive: [
			  {
			    breakpoint: 1024,
			    settings: {
						    slidesToShow: 3,
						    slidesToScroll: 3,
						    infinite: true,
						    dots: true
						   }
			   },
			    {
			      breakpoint: 600,
			      settings: {
					        slidesToShow: 2,
					        slidesToScroll: 2
					      }
			    },
			    {
			      breakpoint: 480,
			      settings: {
					        slidesToShow: 1,
					        slidesToScroll: 1
					      }
			    }
			    // You can unslick at a given breakpoint now by adding:
			    // settings: "unslick"
			    // instead of a settings object
			  ]*/
        });
	};
	
	// Make sure you run this code under Elementor..
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/dyncontel-posterSlider.default', WidgetElements_PosterSliderHandler );
	} );
} )( jQuery );
