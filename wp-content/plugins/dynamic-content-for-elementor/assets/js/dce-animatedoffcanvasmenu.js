( function( $ ) {
	var WidgetElements_AnimatedOffcanvasMenu = function( $scope, $ ) {
		var elementSettings = get_Dyncontel_ElementSettings($scope);
        var id_scope = $scope.attr('data-id');

        var class_menu_li = '.dce-menu ul#dce-ul-menu > li';
        var class_template_before = '.dce-template-after';
        var count_menu = $(class_menu_li).length;
	    var speed_menu = 0.6;

        var class_hamburger = '.dce-button-hamburger';
        var class_modal = '.dce-menu';
        var class_sidebg = '.dce-bg';
        var class_quit = ".dce-menu .dce-close .dce-quit-ics";
        var items_menu = $scope.find( class_menu_li + ', ' + class_template_before)

        var rate_menuside_desktop = Number(elementSettings.animatedoffcanvasmenu_rate.size);
        var rate_menuside_tablet = Number(elementSettings.animatedoffcanvasmenu_rate_tablet.size);
        var rate_menuside_mobile = Number(elementSettings.animatedoffcanvasmenu_rate_mobile.size);
        var rate_menuside = rate_menuside_desktop;

        
        var deviceMode = $('body').attr('data-elementor-device-mode');

        if( deviceMode == 'tablet' && rate_menuside_tablet ){
        	rate_menuside = rate_menuside_tablet;
        }else if( deviceMode == 'mobile' && rate_menuside_mobile ){
        	rate_menuside = rate_menuside_mobile;
        }

        var close_menu = function(){
        	tl.reversed(!tl.reversed());
            $(class_hamburger).find('.con').removeClass('actived').removeClass('open');

            if (!elementorFrontend.isEditMode()) {
                $('body,html').removeClass('dce-modal-open');
            }
        }
        // GSAP animations Timeline
        var tl = new TimelineMax({paused: true});
	    tl.set(class_modal, {
	     	width: 0,
	    });
	    tl.set(class_sidebg, {
	     	right: rate_menuside+'%',
	    });
	    
	    tl.to(class_modal, 0.6, {
	            width: rate_menuside+'%',
	            ease: Expo.easeOut,
	            delay: 0
	    });
	    tl.to(class_sidebg, 0.6, {
	            width: (100-rate_menuside)+'%',
	            ease: Expo.easeInOut,
	            delay: 0
	    });


	    tl.staggerFrom(items_menu, speed_menu, {y: 20 , opacity: 0, ease: Expo.easeInOut}, 0.1);
	    
	    tl.to(class_quit, 0.6, {
	            scale: 1,
	            ease: Expo.easeInOut,
	            delay: 0
	    });

	    tl.reverse();
	    

	    // EVENTES
	    $scope.on("click", class_hamburger, function() {
	        tl.reversed(!tl.reversed());
	        $(this).find('.con').toggleClass('actived');

	        //aggiungo al body la classe aperto
            if (!elementorFrontend.isEditMode()) {
                $('body,html').addClass('dce-modal-open');
            }
	    });


	    $scope.on("mouseover", class_hamburger, function() {
	      	$(this).find('.con').addClass('open');
	    });
	    $scope.on("mouseout", class_hamburger, function() {
	      	$(this).find('.con:not(.actived)').removeClass('open');
	    });

	    $scope.on("click", class_quit, function() {
	        close_menu();
	    });
		$(document).on('keyup',function(evt) {
            if (evt.keyCode == 27) {
                close_menu();
        	}
        });

		// ACCORDION Menu
		$('#dce-ul-menu li.menu-item-has-children > a').click(function(e){
		  e.preventDefault();
		  $(this).closest('li').find('> .sub-menu').not(':animated').slideToggle();
		});

	    if (!elementorFrontend.isEditMode()) {
      		//$('.dce-menu-strip').prependTo("body");
            //$('.dce-nav').prependTo("body");
        }
	};
	
	// Make sure you run this code under Elementor..
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/dyncontel-animatedoffcanvasmenu.default', WidgetElements_AnimatedOffcanvasMenu );
	} );
} )( jQuery );
