( function( $ ) {
	var WidgetElements_FullPageHandler = function( $scope, $ ) {
		console.log( $scope );
		
		var fpistance = $scope.find('#dce_fullpage');
		var fullpageSettings = get_Dyncontel_ElementSettings( $scope );
		//alert($.fn.fullpage);
		
		if (typeof $.fn.fullpage.destroy == 'function') { 
			  $.fn.fullpage.destroy('all');
			}
		//$.fn.fullpage.reBuild();
		//alert(fullpageSettings.fullpage)
		var repeater = fullpageSettings.fullpage;
		var dyn_sections_color = [];
		var dyn_sections_nome = [];
		//console.log( repeater );

		for( var i = 0; i < repeater.length; i++){
			if( elementorFrontend.isEditMode()){
				var color_item = repeater.models[i].attributes.colorbg_section;
				var nome_item = repeater.models[i].attributes.id_name;
			}else{
				var color_item = repeater[i].colorbg_section;
				var nome_item = repeater[i].id_name;
			}
			if( color_item != '' )  dyn_sections_color.push(color_item);
			if( nome_item != '' )  dyn_sections_nome.push(nome_item);
		}
		if( fullpageSettings.enabled_tooltips == "" ){
			dyn_sections_tt = [];
		}else{
			dyn_sections_tt = dyn_sections_nome;
		}
		fpistance.fullpage({
			//Navigation
			// menu: '#menu',
			// lockAnchors: false,
			// anchors:['firstPage', 'secondPage'],
			navigation: Boolean( fullpageSettings.navigation ),
			navigationPosition: fullpageSettings.navigationPosition || 'right', //'right',
			navigationTooltips: dyn_sections_tt, //['firstSlide', 'secondSlide'],
			showActiveTooltip: true,
			// slidesNavigation: false,
			// slidesNavPosition: 'bottom',

			// //Scrolling
			css3:  Boolean( fullpageSettings.css3 ),
			easing: fullpageSettings.easing || 'easeInOutCubic',
			easingcss3: fullpageSettings.easing_css || 'ease',
			scrollingSpeed: fullpageSettings.scrollingSpeed || 700,
			
			autoScrolling:  Boolean( fullpageSettings.autoScrolling ),
			// fitToSection: true,
			// fitToSectionDelay: 1000,
			// scrollBar: false,
			
			
			loopBottom: Boolean( fullpageSettings.loopTop ), //false,
                        loopTop: Boolean( fullpageSettings.loopBottom ), //false,
			
			// loopHorizontal: true,
			continuousVertical: Boolean( fullpageSettings.continuousVertical ), //false
			
			// continuousHorizontal: false,
			// scrollHorizontally: false,
			// interlockedSlides: false,
			// dragAndMove: false,
			// offsetSections: false,
			// resetSliders: false,
			// fadingEffect: false,
			// normalScrollElements: '.section1, .section2',
			
			scrollOverflow: true,
			
			// scrollOverflowReset: false,
			// scrollOverflowOptions: null,
			// touchSensitivity: 15,
			// normalScrollElementTouchThreshold: 5,
			// bigSectionsDestination: null,

			// //Accessibility
			// keyboardScrolling: true,
			// animateAnchor: true,
			// recordHistory: true,

			// //Design
			controlArrows: Boolean( fullpageSettings.controlArrows ), //true
			verticalCentered: Boolean( fullpageSettings.verticalCentered ), //true
			sectionsColor : dyn_sections_color,
			// paddingTop: '3em',
			// paddingBottom: '10px',
			// fixedElements: '#header, .footer',
			// responsiveWidth: 0,
			// responsiveHeight: 0,
			// responsiveSlides: true, //false,
			// parallax: false,
			// parallaxOptions: {type: 'reveal', percentage: 62, property: 'translate'},

			// //Custom selectors
			// sectionSelector: '.section',
			// slideSelector: '.slide',

			// lazyLoading: true,

			// //events
			// onLeave: function(index, nextIndex, direction){},
			// afterLoad: function(anchorLink, index){},
			// afterRender: function(){ },
			// afterResize: function(){},
			// afterResponsive: function(isResponsive){},
			// afterSlideLoad: function(anchorLink, index, slideAnchor, slideIndex){},
			// onSlideLeave: function(anchorLink, index, slideIndex, direction, nextSlideIndex){}

		});
		//alert('FullPage '+fpistance);
		
	};
	
	// Make sure you run this code under Elementor..
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/dyncontel-fullpage.default', WidgetElements_FullPageHandler );
	} );
} )( jQuery );
