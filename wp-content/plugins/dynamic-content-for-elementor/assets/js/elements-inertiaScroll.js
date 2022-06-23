( function( $ ) {
	var settings_page = {};
	var is_inertiaScroll = false;
	var sectionsAvailable = [];
	var directionScroll = 'vertical';
	var coefSpeed_inertiaScroll = 0.05;
	var html = document.documentElement;
	var body = document.body;
	var scroller = {};
	
	function init_InertiaScroll($dir){
		//alert($dir);
		$('body').addClass('dce-inertiaScroll dce-scrolling');
		//$('body').prepend('<div class="trace"></div>');
		
		if( settings_page.custom_class_section ){
    		$customClass = settings_page.custom_class_section;
    	}else{
    		$customClass = 'elementor-section';
    	}


    	// DIRECTIONS
    	if(typeof(settings_page.directionScroll) !== 'undefined') directionScroll = settings_page.directionScroll || $dir;
    	if( typeof($dir) !== 'undefined' && ($dir == 'horizontal' || $dir == 'vertical')) directionScroll = $dir;
    	//alert('sett: ' + directionScroll);


    	// SPEED
    	if(typeof(settings_page.coefSpeed_inertiaScroll) !== 'undefined') coefSpeed_inertiaScroll = Number(settings_page.coefSpeed_inertiaScroll.size);


    	//$target_sections = settings_page.scroll_target+' ';
    	$target_sections = '.elementor-'+settings_page.scroll_id_page;
    	if(!$target_sections) $target_sections = '';

    	// Get the section widgets of frst level in content-page
		var sezioni = $target_sections + '.elementor > .elementor-inner > .elementor-section-wrap > .' + $customClass;    	
		sectionsAvailable = $(sezioni);

		// Class direction
		$($target_sections).addClass('scroll-direction-'+directionScroll);

		// configure
		sectionsAvailable.addClass('inertia-scroll');
		

		$scrollContent = settings_page.scroll_contentScroll;
		if(settings_page.scroll_target) $scrollContent = settings_page.scroll_target;
		
		scroller = {
		  viewport: document.querySelector(settings_page.scroll_viewport) || document.querySelector('#outer-wrap'),
		  target: document.querySelector($scrollContent) || document.querySelector('#wrap'),
		  ease:  coefSpeed_inertiaScroll || 0.05, // <= scroll speed ...  
		  endY: 0,
		  endX: 0,
		  y: 0,
		  x: 0,
		  resizeRequest: 1,
		  scrollRequest: 0,
		};
		//alert(coefSpeed_inertiaScroll);
		var requestId = undefined;

		TweenMax.set(scroller.target, {
		  //rotation: 0.01,
		  force3D: true
		});
			
		// Viewoport 
		/*TweenMax.set(scroller.viewport, {
		  overflow: hidden;
		  position: fixed;
		  height: '100%';
		  width: '100%';
		  top: 0;
		  left: 0;
		  right: 0;
		  bottom: 0;
		});*/
		TweenMax.set(scroller.viewport, {
												  overflow: 'hidden',
												  position: 'fixed',
												  //height: '100%',
												  //width: '100%',
												  top: 0,
												  left: 0,
												  //right: 0,
												  //bottom: 0,
												});
		 updateScroller();  
		 window.focus();
		 window.addEventListener("resize", onResize);
		 document.addEventListener("scroll", onScroll); 

		 is_inertiaScroll = true;
	}
	function removeInertiaScroll(){
		$('body').removeClass('dce-inertiaScroll');
		if(sectionsAvailable.length) sectionsAvailable.removeClass('inertia-scroll');
		
		
		TweenMax.kill( scroller.target );
		TweenMax.set(scroller.target, {clearProps:"all"});
		TweenMax.set(scroller.viewport, {clearProps:"all"});
		sectionsAvailable.each(function(i, el){
				  	 TweenMax.set(el, {clearProps:"all"});
				});
		scroller = {
		  endY: 0,
		  y: 0,
		  resizeRequest: 1,
		  scrollRequest: 0,
		};

		if (requestId) {
	       window.cancelAnimationFrame(requestId);
	       
	       requestId = undefined;
	    }
		window.removeEventListener("resize", onResize);
		document.removeEventListener("scroll", onScroll);

		is_inertiaScroll = false;
	}

	//
	function handleInertiaScroll ( newValue ) {
		//console.log( newValue );
		//elementor.reloadPreview();

		if(newValue){
			// SI
			if(is_inertiaScroll){
				removeInertiaScroll();
			}else{
				settings_page = elementor.settings.page.model.attributes;
			}
			setTimeout(function(){
				if( settings_page.enable_inertiaScroll ) init_InertiaScroll(newValue);	
			},100);
		}else{
			// NO
			removeInertiaScroll();
			
		}
	}
	function handleDCEScroll ( newValue ) {

		if(newValue){
			
		}else{
			// NO
			//removeInertiaScroll();
			
		}
	}
	// EVENTS
	function updateScroller() {
	  
		  var resized = scroller.resizeRequest > 0;
		  
		  var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
		  var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
		  var cfc = h/w;



		  // qui sto elaborando la [ Y ]  -----------------------------------------------
		  if( directionScroll == 'vertical' ) {

			  
			  if (resized) {    
			    var height = scroller.target.clientHeight;
			    body.style.height = height + "px";
			    scroller.resizeRequest = 0;
			  }

			  var scrollY = window.pageYOffset || html.scrollTop || body.scrollTop || 0;

			  scroller.endY = scrollY;
			  scroller.y += (scrollY - scroller.y) * scroller.ease;

			  if (Math.abs(scrollY - scroller.y) < 0.05 || resized) {
			    scroller.y = scrollY;
			    scroller.scrollRequest = 0;
			  }
			  // ------------------------
			  TweenMax.set(scroller.target, { 
			    y: -scroller.y 
			  });

		  }else if( directionScroll == 'horizontal' ){
	  		  // qui invece elaboro la [ X ] -----------------------------------------------
			  if (resized) {  
			 	//alert(sectionsAvailable.length);
			 	var totalWidth = 0;
			 	var completeWidth = 0;
			 	var count = 0;

			 	
				sectionsAvailable.each(function(i, el){
				  	//alert($(el).width());
				  	completeWidth += $(el).width();
				  	 if( count > 0 ) totalWidth += $(el).width();
				  	 //$(el).css({'position':'absolute','width':'100%','height':'100vh','left':(i*100)+'vw'});
				  	 //$(el).css({'float':'left','width':(100/sectionsAvailable.length)+'%','height':'100vh'});
				  	 TweenMax.set(el, { 
					    float: 'left',
					    width: (100/sectionsAvailable.length)+'%',
					    //height: '100vh'
					});
				  	count++;
				});
				//alert(totalWidth);
				TweenMax.set(scroller.target, { 
				    width: completeWidth
				});
				totalWidth += h;
				//totalWidth -= ($(settings_page.scroll_viewport).find('footer').height())  
			    //var width = //scroller.target.clientWidth*3;
			    body.style.height = totalWidth + "px";
			    scroller.resizeRequest = 0;
			  }

			  
			  var scrollX = window.pageYOffset || html.scrollTop || body.scrollTop || 0;

			  scroller.endX = scrollX;
			  scroller.x += (scrollX - scroller.x) * scroller.ease;
			  //scroller.x = scrollX;
			  
			  if (Math.abs(scrollX - scroller.x) < 0.05 || resized) {
			    scroller.x = scrollX;
			    scroller.scrollRequest = 0;
			  }

			  // ------------------------
			  TweenMax.set(scroller.target, { 
			    x: -scroller.x 
			  });
		  }

		  //$('.trace').text(window.scrollY); //scroller.x
		  requestId = scroller.scrollRequest > 0 ? requestAnimationFrame(updateScroller) : null;

	}

	function onScroll() {
		  scroller.scrollRequest++;
		  if (!requestId) {
		    requestId = requestAnimationFrame(updateScroller);
		  }

	}

	function onResize() {
		  scroller.resizeRequest++;
		  if (!requestId) {
		    requestId = requestAnimationFrame(updateScroller);
		  }
	}

	// Make sure you run this code under Elementor..
	$( document ).on( 'ready', function() {
		
		/*if ( elementorFrontend.isEditMode() ) {
			dce_isEditMode = true;
		}

		if ( $('body').is('.admin-bar') ) {
			dce_isAdminBar = true;
		}*/
		/*if ( elementorFrontend.isEditMode() ){
			
		}else{
			settings_page = JSON.parse( $('.elementor').attr('data-elementor-settings') ); //
		}*/
		settings_page = elementorFrontendConfig.settings.page;
		if(settings_page){
			var is_enable_dceScrolling = settings_page.enable_dceScrolling;
			var is_enable_inertiaScroll = settings_page.enable_inertiaScroll;
			//alert(settings_page.enable_inertiaScroll);
			if( is_enable_inertiaScroll && is_enable_dceScrolling ){
				setTimeout(function(){
					init_InertiaScroll();
				},100); 
				
			}
		}
		// per il renderin della preview in EditMode
		if ( elementorFrontend.isEditMode() ){
			elementor.settings.page.addChangeCallback( 'enable_inertiaScroll', handleInertiaScroll );
			elementor.settings.page.addChangeCallback( 'directionScroll', handleInertiaScroll );
			elementor.settings.page.addChangeCallback( 'scroll_target', handleInertiaScroll );
			elementor.settings.page.addChangeCallback( 'coefSpeed_inertiaScroll', handleInertiaScroll );
			//elementor.settings.page.addChangeCallback( 'enable_dceScrolling', handleInertiaScroll );
		} 
		
		// ------------
		/*elementor.hooks.addAction( 'panel/open_editor/post/enable_scrollify', function( panel, model, view ) {
		   var $element = view.$el.find( '.elementor-selector' );
		   alert('open editor');
		   if ( $element.length ) {
		   	$element.click( function() {
		   	  alert( 'Some Message' );
		   	} );
		   }
		} );*/
		// -----------
		/*elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $scope ) {
			console.log($scope);
			if ( $scope.data( 'shake' ) ){
				$scope.shake();
			}
		} );*/

	} );
} )( jQuery );
