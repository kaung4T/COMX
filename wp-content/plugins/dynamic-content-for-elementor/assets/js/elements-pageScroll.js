( function( $ ) {
	var settings_page = {};
	var is_scrollEffects = false;
	var sectionsAvailable = [];
	
    function bindEvents(MQ, bool) {
    	//alert(MQ);
    	if( MQ == 'desktop' && bool) {   		
    		//bind the animation to the window scroll event, arrows click and keyboard

			if( hijacking == 'on' ) {

				initHijacking();
				//alert('MouseWheelEvent');
				$(window).on('DOMMouseScroll mousewheel', scrollHijacking);

			} else {
				scrollAnimation();
				$(window).on('scroll', scrollAnimation);
				
			}
			prevArrow.on('click', prevSection);
    		nextArrow.on('click', nextSection);

    		//alert(sectionsAvailable.eq(0).offset().top);
    		
    		$(document).on('keydown', function(event){
				if( event.which=='0' && !nextArrow.hasClass('inactive') ) {
					event.preventDefault();
					nextSection();
				} else if( event.which=='38' && (!prevArrow.hasClass('inactive') || (prevArrow.hasClass('inactive') && $(window).scrollTop() != sectionsAvailable.eq(0).offset().top) ) ) {
					event.preventDefault();
					prevSection();
				}
			});
			//set navigation arrows visibility
			checkNavigation();
		} else if( MQ == 'mobile' ) {
			//reset and unbind
			removeScrollEffects();
		}
    }
    function removeScrollEffects(){
    		resetSectionStyle();
			$(window).off('DOMMouseScroll mousewheel');
			$(window).off('scroll');
			prevArrow.off('click', prevSection);
    		nextArrow.off('click', nextSection);
    		$(document).off('keydown');
    		
    		$('.elementor-'+settings_page.scrollEffects_id_page).removeClass('dce-pageScroll-element');

    		$('body').removeClass('dce-pageScroll');

    		$('body').removeAttr('data-hijacking');
			$('body').removeAttr('data-animation');

			if(settings_page && settings_page.scrollEffects_navigation) $('body').remove('scrollEffects-nav');

    		is_scrollEffects = false;
    	}
	function scrollAnimation(){
		//normal scroll - use requestAnimationFrame (if defined) to optimize performance
		(!window.requestAnimationFrame) ? animateSection() : window.requestAnimationFrame(animateSection);
	}

	function animateSection() {
		var scrollTop = $(window).scrollTop(),
			windowHeight = $(window).height(),
			windowWidth = $(window).width();
		
		sectionsAvailable.each(function(){
			var actualBlock = $(this),
				offset = scrollTop - actualBlock.offset().top;

			//according to animation type and window scroll, define animation parameters
			var animationValues = setSectionAnimation(offset, windowHeight, animationType);
			// @p
			transformSection(actualBlock.children('.elementor-container'), animationValues[0], animationValues[1], animationValues[2], animationValues[3], animationValues[4]);
			( offset >= 0 && offset < windowHeight ) ? actualBlock.addClass('dce-ps-visible') : actualBlock.removeClass('dce-ps-visible');		
		});
		
		checkNavigation();
	}

	function transformSection(element, translateY, scaleValue, rotateXValue, opacityValue, boxShadow) {

		//transform sections - normal scroll
		element.velocity({
			translateY: translateY+'vh',
			scale: scaleValue,
			rotateX: rotateXValue,
			opacity: opacityValue,
			boxShadowBlur: boxShadow+'px',
			translateZ: 0,
		}, 0);
	}

	function initHijacking() {
		//alert('Hijacking');
		//alert(sectionsAvailable.filter('.dce-ps-visible').length);
		//
		// initialize section style - scrollhijacking
		var visibleSection = sectionsAvailable.filter('.dce-ps-visible'),
			topSection = visibleSection.prevAll(sezioni),
			bottomSection = visibleSection.nextAll(sezioni),
			animationParams = selectAnimation(animationType, false),
			animationVisible = animationParams[0],
			animationTop = animationParams[1],
			animationBottom = animationParams[2];

		visibleSection.children('.elementor-container').velocity(animationVisible, 1, function(){
			visibleSection.css('opacity', 1);
	    	topSection.css('opacity', 1);
	    	bottomSection.css('opacity', 1);
		});
        topSection.children('.elementor-container').velocity(animationTop, 0);
        bottomSection.children('.elementor-container').velocity(animationBottom, 0);
	}

	function scrollHijacking (event) {
		//alert('delta '+event.originalEvent.detail);
		// on mouse scroll - check if animate section
        if (event.originalEvent.detail < 0 || event.originalEvent.wheelDelta > 0) { 
            delta--;
            ( Math.abs(delta) >= scrollThreshold) && prevSection();
            //alert('prev');
        } else {
            delta++;
            (delta >= scrollThreshold) && nextSection();
            //alert('next');
        }

        return false;
    }

    function prevSection(event) {
    	//alert('prev');
    	//go to previous section
    	typeof event !== 'undefined' && event.preventDefault();
    	
    	var visibleSection = sectionsAvailable.filter('.dce-ps-visible'),
    		middleScroll = ( hijacking == 'off' && $(window).scrollTop() != visibleSection.offset().top) ? true : false;

    	
    	//$scope.find('.trace').text('prev '+visibleSection.index());
    	visibleSection = middleScroll ? visibleSection.next(sezioni) : visibleSection;
		//alert(visibleSection);
    	var animationParams = selectAnimation(animationType, middleScroll, 'prev');
    	unbindScroll(visibleSection.prev(sezioni), animationParams[3]);
    	
        if( !animating && !visibleSection.is(":first-child") ) {
        	animating = true;

        	

            visibleSection.removeClass('dce-ps-visible').children('.elementor-container').velocity(animationParams[2], animationParams[3], animationParams[4])
            .end().prev(sezioni).addClass('dce-ps-visible').children('.elementor-container').velocity(animationParams[0] , animationParams[3], animationParams[4], function(){
            	animating = false;
            	//alert('-> '+visibleSection.is(":first-child")+' - '+animationParams);
            	if( hijacking == 'off') $(window).on('scroll', scrollAnimation);
            });
            
            actual = actual - 1;
        }

        resetScroll();
    }
    function nextSection(event) {
    	//alert('next');
    	//go to next section
    	typeof event !== 'undefined' && event.preventDefault();

        var visibleSection = sectionsAvailable.filter('.dce-ps-visible'),
    		middleScroll = ( hijacking == 'off' && $(window).scrollTop() != visibleSection.offset().top) ? true : false;

    	var animationParams = selectAnimation(animationType, middleScroll, 'next');
    	unbindScroll(visibleSection.next(sezioni), animationParams[3]);

    	/*$scope.find('.trace.tt').text('next '+visibleSection.index() );
    	$scope.find('.trace.t0').text('animp0 '+animationParams[0] );
    	$scope.find('.trace.t1').text('animp1 '+animationParams[1] );
    	$scope.find('.trace.t2').text('animp2 '+animationParams[2] );
    	$scope.find('.trace.t3').text('animp3 '+animationParams[3] );
    	$scope.find('.trace.t4').text('animp4 '+animationParams[4] );*/
    	//alert(visibleSection.is(":last-of-type")+' '+visibleSection.attr('class'));
        if(!animating && !visibleSection.is(":last-of-type") ) {

            animating = true;
            visibleSection.removeClass('dce-ps-visible').children('.elementor-container').velocity(animationParams[1], animationParams[3], animationParams[4] )
            .end().next(sezioni).addClass('dce-ps-visible').children('.elementor-container').velocity(animationParams[0], animationParams[3], animationParams[4], function(){
            	animating = false;
            	//alert($.Velocity);
            	if( hijacking == 'off') $(window).on('scroll', scrollAnimation);
            });
            
            actual = actual +1;
        }
        resetScroll();
    }

    function unbindScroll(section, time) {
    	//if clicking on navigation - unbind scroll and animate using custom velocity animation
    	if( hijacking == 'off') {
    		$(window).off('scroll', scrollAnimation);
    		( animationType == 'catch') ? $('body, html').scrollTop(section.offset().top) : section.velocity("scroll", { duration: time });
    	}
    }

    function resetScroll() {
        delta = 0;
        checkNavigation();
    }

    function checkNavigation() {
    	//update navigation arrows visibility
		( sectionsAvailable.filter('.dce-ps-visible').is(':first-of-type') ) ? prevArrow.addClass('inactive') : prevArrow.removeClass('inactive');
		( sectionsAvailable.filter('.dce-ps-visible').is(':last-of-type')  ) ? nextArrow.addClass('inactive') : nextArrow.removeClass('inactive');
	}

	function resetSectionStyle() {
		//on mobile - remove style applied with jQuery
		sectionsAvailable.children('div:not(sfondo)').each(function(){
			$(this).attr('style', '');
		});
	}

	function deviceType() {
		//detect if desktop/mobile
		var currentDevice = elementorFrontend.getCurrentDeviceMode();

		return currentDevice;//window.getComputedStyle(document.querySelector('body'), '::before').getPropertyValue('content').replace(/"/g, "").replace(/'/g, "");
	}

	function selectAnimation(animationName, middleScroll, direction) {
		// select section animation - scrollhijacking
		var animationVisible = 'translateNone',
			animationTop = 'translateUp',
			animationBottom = 'translateDown',
			easing = 'ease',
			animDuration = 800;

		switch(animationName) {
		    case 'scaleDown':
		    	animationTop = 'scaleDown';
		    	easing = 'easeInCubic';
		        break;
		    case 'rotate':
		    	if( hijacking == 'off') {
		    		animationTop = 'rotation.scroll';
		    		animationBottom = 'translateNone';
		    	} else {
		    		animationTop = 'rotation';
		    		easing = 'easeInCubic';
		    	}
		        break;
		    case 'gallery':
		    	animDuration = 1500;
		    	if( middleScroll ) {
		    		animationTop = 'scaleDown.moveUp.scroll';
		    		animationVisible = 'scaleUp.moveUp.scroll';
		    		animationBottom = 'scaleDown.moveDown.scroll';
		    	} else {
		    		animationVisible = (direction == 'next') ? 'scaleUp.moveUp' : 'scaleUp.moveDown';
					animationTop = 'scaleDown.moveUp';
					animationBottom = 'scaleDown.moveDown';
		    	}
		        break;
		    case 'catch':
		    	animationVisible = 'translateUp.delay';
		        break;
		    case 'opacity':
		    	animDuration = 700;
				animationTop = 'hide.scaleUp';
				animationBottom = 'hide.scaleDown';
		        break;
		    case 'fixed':
		    	animationTop = 'translateNone';
		    	easing = 'easeInCubic';
		        break;
		    case 'parallax':
		    	animationTop = 'translateUp.half';
		    	easing = 'easeInCubic';
		        break;
		}

		return [animationVisible, animationTop, animationBottom, animDuration, easing];
	}

	function setSectionAnimation(sectionOffset, windowHeight, animationName ) {
		// select section animation - normal scroll
		var scale = 1,
			translateY = 100,
			rotateX = '0deg',
			opacity = 1,
			boxShadowBlur = 0;
			
			//$scope.find('.trace').text('f');
		if( sectionOffset >= -windowHeight && sectionOffset <= 0 ) {
			// section entering the viewport
			translateY = (-sectionOffset)*100/windowHeight;
			
			switch(animationName) {
			    case 'scaleDown':
			        scale = 1;
					opacity = 1;
					break;
				case 'rotate':
					translateY = 0;
					break;
				case 'gallery':
			        if( sectionOffset>= -windowHeight &&  sectionOffset< -0.9*windowHeight ) {
			        	scale = -sectionOffset/windowHeight;
			        	translateY = (-sectionOffset)*100/windowHeight;
			        	boxShadowBlur = 0*(1+sectionOffset/windowHeight);
			        } else if( sectionOffset>= -0.9*windowHeight &&  sectionOffset< -0.1*windowHeight) {
			        	scale = 0.9;
			        	translateY = -(9/8)*(sectionOffset+0.1*windowHeight)*100/windowHeight;
			        	boxShadowBlur = 0;
			        } else {
			        	scale = 1 + sectionOffset/windowHeight;
			        	translateY = 0;
			        	boxShadowBlur = -0*sectionOffset/windowHeight;
			        }
					break;
				case 'catch':
			        if( sectionOffset>= -windowHeight &&  sectionOffset< -0.75*windowHeight ) {
			        	translateY = 100;
			        	boxShadowBlur = (1 + sectionOffset/windowHeight)*160;
			        } else {
			        	translateY = -(10/7.5)*sectionOffset*100/windowHeight;
			        	boxShadowBlur = -160*sectionOffset/(3*windowHeight);
			        }
					break;
				case 'opacity':
					translateY = 0;
			        scale = (sectionOffset + 5*windowHeight)*0.2/windowHeight;
			        opacity = (sectionOffset + windowHeight)/windowHeight;
					break;
			}

		} else if( sectionOffset > 0 && sectionOffset <= windowHeight ) {
			//section leaving the viewport - still has the '.dce-ps-visible' class
			translateY = (-sectionOffset)*100/windowHeight;
			
			switch(animationName) {
			    case 'scaleDown':
			        scale = (1 - ( sectionOffset * 0.3/windowHeight)).toFixed(5);
					opacity = ( 1 - ( sectionOffset/windowHeight) ).toFixed(5);
					translateY = 0;
					boxShadowBlur = 0*(sectionOffset/windowHeight);

					break;
				case 'rotate':
					opacity = ( 1 - ( sectionOffset/windowHeight) ).toFixed(5);
					rotateX = sectionOffset*90/windowHeight + 'deg';
					translateY = 0;
					break;
				case 'gallery':
			        if( sectionOffset >= 0 && sectionOffset < 0.1*windowHeight ) {
			        	scale = (windowHeight - sectionOffset)/windowHeight;
			        	translateY = - (sectionOffset/windowHeight)*100;
			        	boxShadowBlur = 0*sectionOffset/windowHeight;
			        } else if( sectionOffset >= 0.1*windowHeight && sectionOffset < 0.9*windowHeight ) {
			        	scale = 0.9;
			        	translateY = -(9/8)*(sectionOffset - 0.1*windowHeight/9)*100/windowHeight;
			        	boxShadowBlur = 0;
			        } else {
			        	scale = sectionOffset/windowHeight;
			        	translateY = -100;
			        	boxShadowBlur = 0*(1-sectionOffset/windowHeight);
			        }
					break;
				case 'catch':
					if(sectionOffset>= 0 &&  sectionOffset< windowHeight/2) {
						boxShadowBlur = sectionOffset*80/windowHeight;
					} else {
						boxShadowBlur = 80*(1 - sectionOffset/windowHeight);
					} 
					break;
				case 'opacity':
					translateY = 0;
			        scale = (sectionOffset + 5*windowHeight)*0.2/windowHeight;
			        opacity = ( windowHeight - sectionOffset )/windowHeight;
					break;
				case 'fixed':
					translateY = 0;
					break;
				case 'parallax':
					translateY = (-sectionOffset)*50/windowHeight;
					break;

			}

		} else if( sectionOffset < -windowHeight ) {
			//section not yet visible
			translateY = 100;

			switch(animationName) {
			    case 'scaleDown':
			        scale = 1;
					opacity = 1;
					break;
				case 'gallery':
			        scale = 1;
					break;
				case 'opacity':
					translateY = 0;
			        scale = 0.8;
			        opacity = 0;
					break;
			}

		} else {
			//section not visible anymore
			translateY = -100;

			switch(animationName) {
			    case 'scaleDown':
			        scale = 0;
					opacity = 0.7;
					translateY = 0;
					break;
				case 'rotate':
					translateY = 0;
			        rotateX = '90deg';
			        break;
			    case 'gallery':
			        scale = 1;
					break;
				case 'opacity':
					translateY = 0;
			        scale = 1.2;
			        opacity = 0;
					break;
				case 'fixed':
					translateY = 0;
					break;
				case 'parallax':
					translateY = -50;
					break;
			}
		}

		return [translateY, scale, rotateX, opacity, boxShadowBlur]; 
	}
	
	


	// -----------------------------
	var set_sectionHeight = function(){
		//alert(settings_page.scrollEffects_id_page);
		if(sectionsAvailable.length) sectionsAvailable.each(function(){
			var childrenContainer = $(this).children('.elementor-container');
			//alert(childrenContainer.height());
			$(this).height(childrenContainer.height());
		});
	}
	var reset_sectionHeight = function(){
		//alert(settings_page.scrollEffects_id_page);
		if(sectionsAvailable.length) sectionsAvailable.each(function(){
			var childrenContainer = $(this).children('.elementor-container');
			//alert(childrenContainer.height());
			$(this).removeAttr('style');
		});
	}
	var WidgetElements_PageScroll = function(  ) {
		//alert('PageScroll');
				hijacking = 'off'; //get_hijacking(settings_page.hijacking) || 'off'; //$scope.find('#dce_pagescroll').data('hijacking'),
				animationType = 'scaleDown'; //$scope.find('#dce_pagescroll').data('animation'),
				delta = 0,
		        scrollThreshold = 5,
		        actual = 1,
	        	animating = false;

	        	if( settings_page.custom_class_section ){
	        		$customClass = settings_page.custom_class_section;
	        	}else{
	        		$customClass = 'elementor-section';
	        	}
	        	//alert($customClass);
	        	//sezioni = '.elementor-' + settings_page.scrollEffects_id_page + ' > .elementor-inner > .elementor-section-wrap > .elementor-section';
	        	sezioni = '.elementor-' + settings_page.scrollEffects_id_page + ' > .elementor-inner > .elementor-section-wrap > .' + $customClass;
	        	
				sectionsAvailable = $(sezioni);

				// ----------------------------- [ INIZIAMO ]
				animationType = settings_page.animation_effects || 'scaleDown'; //$scope.find('#dce_pagescroll').data('animation'),
				
				// Elements
				sectionsAvailable.addClass('dce-ps-section').removeClass('elementor-section-boxed');
				$('.elementor-' + settings_page.scrollEffects_id_page + ' > .elementor-inner > .elementor-section-wrap > .'+$customClass+':first-of-type').addClass('dce-ps-visible');
				
		/* Custom effects registration - feature available in the Velocity UI pack */
		$.Velocity
		    .RegisterEffect("translateUp", {
		    	defaultDuration: 1,
		        calls: [ 
		            [ { translateY: '-100%'}, 1]
		        ]
		    });
		$.Velocity
		    .RegisterEffect("translateDown", {
		    	defaultDuration: 1,
		        calls: [ 
		            [ { translateY: '100%'}, 1]
		        ]
		    });
		$.Velocity
		    .RegisterEffect("translateNone", {
		    	defaultDuration: 1,
		        calls: [ 
		            [ { translateY: '0', opacity: '1', scale: '1', rotateX: '0', boxShadowBlur: '0'}, 1]
		        ]
		    });

		//scale down
		$.Velocity
		    .RegisterEffect("scaleDown", {
		    	defaultDuration: 1,
		        calls: [ 
		            [ { opacity: '0', scale: '0.7', boxShadowBlur: '0px' }, 1]
		        ]
		    });
		//rotation
		$.Velocity
		    .RegisterEffect("rotation", {
		    	defaultDuration: 1,
		        calls: [ 
		            [ { opacity: '0', rotateX: '90', translateY: '-100%'}, 1]
		        ]
		    });
		$.Velocity
		    .RegisterEffect("rotation.scroll", {
		    	defaultDuration: 1,
		        calls: [ 
		            [ { opacity: '0', rotateX: '90', translateY: '0'}, 1]
		        ]
		    });
		//gallery
		$.Velocity
		    .RegisterEffect("scaleDown.moveUp", {
		    	defaultDuration: 1,
		        calls: [ 
		        	[ { translateY: '-10%', scale: '0.9', boxShadowBlur: '0px'}, 0.20 ],
		        	[ { translateY: '-100%' }, 0.60 ],
		        	[ { translateY: '-100%', scale: '1', boxShadowBlur: '0' }, 0.20 ]
		        ]
		    });
		$.Velocity
		    .RegisterEffect("scaleDown.moveUp.scroll", {
		    	defaultDuration: 1,
		        calls: [ 
		        	[ { translateY: '-100%', scale: '0.9', boxShadowBlur: '0px' }, 0.60 ],
		        	[ { translateY: '-100%', scale: '1', boxShadowBlur: '0' }, 0.0 ]
		        ]
		    });
		$.Velocity
		    .RegisterEffect("scaleUp.moveUp", {
		    	defaultDuration: 1,
		        calls: [ 
		        	[ { translateY: '90%', scale: '0.9', boxShadowBlur: '0px' }, 0.20 ],
		        	[ { translateY: '0%' }, 0.60 ],
		        	[ { translateY: '0%', scale: '1', boxShadowBlur: '0'}, 0.20 ]
		        ]
		    });
		$.Velocity
		    .RegisterEffect("scaleUp.moveUp.scroll", {
		    	defaultDuration: 1,
		        calls: [ 
		        	[ { translateY: '0%', scale: '0.9' , boxShadowBlur: '0px' }, 0.60 ],
		        	[ { translateY: '0%', scale: '1', boxShadowBlur: '0'}, 0.0 ]
		        ]
		    });
		$.Velocity
		    .RegisterEffect("scaleDown.moveDown", {
		    	defaultDuration: 1,
		        calls: [ 
		        	[ { translateY: '10%', scale: '0.9', boxShadowBlur: '0px'}, 0.20 ],
		        	[ { translateY: '100%' }, 0.60 ],
		        	[ { translateY: '100%', scale: '1', boxShadowBlur: '0'}, 0.20 ]
		        ]
		    });
		$.Velocity
		    .RegisterEffect("scaleDown.moveDown.scroll", {
		    	defaultDuration: 1,
		        calls: [ 
		        	[ { translateY: '100%', scale: '0.9', boxShadowBlur: '0px' }, 0.60 ],
		        	[ { translateY: '100%', scale: '1', boxShadowBlur: '0' }, 0.0 ]
		        ]
		    });
		$.Velocity
		    .RegisterEffect("scaleUp.moveDown", {
		    	defaultDuration: 1,
		        calls: [ 
		        	[ { translateY: '-90%', scale: '0.9', boxShadowBlur: '0px' }, 0.20 ],
		        	[ { translateY: '0%' }, 0.60 ],
		        	[ { translateY: '0%', scale: '1', boxShadowBlur: '0'}, 0.20 ]
		        ]
		    });
		//catch up
		$.Velocity
		    .RegisterEffect("translateUp.delay", {
		    	defaultDuration: 1,
		        calls: [ 
		            [ { translateY: '0%'}, 0.8, { delay: 100 }],
		        ]
		    });
		//opacity
		$.Velocity
		    .RegisterEffect("hide.scaleUp", {
		    	defaultDuration: 1,
		        calls: [ 
		            [ { opacity: '0', scale: '1.2'}, 1 ]
		        ]
		    });
		$.Velocity
		    .RegisterEffect("hide.scaleDown", {
		    	defaultDuration: 1,
		        calls: [ 
		            [ { opacity: '0', scale: '0.8'}, 1 ]
		        ]
		    });
		//parallax
		$.Velocity
		    .RegisterEffect("translateUp.half", {
		    	defaultDuration: 1,
		        calls: [ 
		            [ { translateY: '-50%'}, 1]
		        ]
	    	});

		//alert($('.elementor-'+settings_page.scrollEffects_id_page+ ' .elementor-inner').length);
		$('.elementor-'+settings_page.scrollEffects_id_page).addClass('dce-pageScroll-element');
		// $('.elementor-'+settings_page.scrollEffects_id_page).removeAttr('data-hijacking');
		// $('.elementor-'+settings_page.scrollEffects_id_page).removeAttr('data-animation');

		$('body').addClass('dce-pageScroll');
		//$('body').attr('data-hijacking',hijacking);
		$('body').attr('data-animation',animationType);

		set_sectionHeight();
		if(settings_page && settings_page.scrollEffects_navigation) $('body').append('<nav class="scrollEffects-nav"><ul class="cd-vertical-nav"><li><a href="#0" class="cd-prev inactive">Next</a></li><li><a href="#0" class="cd-next">Prev</a></li></ul></nav>')
		
		//DOM elements
	    // 
		verticalNav = $('.cd-vertical-nav'),
    	prevArrow = verticalNav.find('a.cd-prev'),
    	nextArrow = verticalNav.find('a.cd-next');

		//check the media query and bind corresponding events
		var MQ = deviceType(),
			bindToggle = false;
		
		bindEvents(MQ, true);
		
		$(window).on('resize', function(){
			MQ = deviceType();
			bindEvents(MQ, bindToggle);
			if( MQ == 'mobile' ) bindToggle = true;
			if( MQ == 'desktop' ) bindToggle = false;
		});
		is_scrollEffects = true;
	};

	// --------------------------
	function handleScrollEffects ( newValue ) {
		
		if(newValue){
			// SI
			if(is_scrollEffects){
				removeScrollEffects();
			}else{
				settings_page = elementor.settings.page.model.attributes;
				//alert(settings_page);
			}
			setTimeout(function(){
				WidgetElements_PageScroll();	
			},500);
		}else{
			// NO
			removeScrollEffects();
			reset_sectionHeight();
		}
	}
	function handleScrollEffects_animations ( newValue ) {
		animationType = newValue;
	}
	function handleScrollEffects_hijacking ( newValue ) {
		
		// SI
		// alert(is_scrollEffects);
		removeScrollEffects();
		
		settings_page = elementor.settings.page.model.attributes;
		hijacking = get_hijacking(newValue);
		setTimeout(function(){
				WidgetElements_PageScroll();	
			},1000);

	}
	function handleScrollEffects_height ( newValue ) {

		set_sectionHeight();
	}
	function get_hijacking ( $value ) {
		if($value){
			return 'on';
		}else{
			return 'off';
		}
	}
	// Make sure you run this code under Elementor..
	$( document ).on( 'ready', function() {
		
		//console.log(elementorFrontendConfig.settings.page);
		

		//alert(sectionsAvailable.length);   
		

		if( typeof elementorFrontendConfig.settings.page !== 'undefined' ){
			//alert(elementorFrontendConfig.settings.page);
			//alert(settings_page.animation_effects);
			//variables

			settings_page = elementorFrontendConfig.settings.page;
			
			if( settings_page ){
				
				// ----------------------------- [ INIZIAMO ]
				//setTimeout(function(){
					if( settings_page.enable_scrollEffects ){
						//
						WidgetElements_PageScroll();
					}
					
				//},1000);

				if ( elementorFrontend.isEditMode() ){
					/*elementor.once( 'preview:loaded', function() {
						// questo è il callBack di fine loading della preview

					} );*/
					elementor.settings.page.addChangeCallback( 'enable_scrollEffects', handleScrollEffects );
					elementor.settings.page.addChangeCallback( 'animation_effects', handleScrollEffects_animations );
					elementor.settings.page.addChangeCallback( 'hijacking', handleScrollEffects_hijacking );
					elementor.settings.page.addChangeCallback( 'scrollEffects_height', handleScrollEffects_height );
					
					/*var c_settings = Object.keys(settings_page).length;
					var c = 1;
					for (var key in settings_page) {
					    console.log(key, settings_page[key]);
						alert(key+' '+settings_page[key]);
						elementor.settings.page.addChangeCallback( key, function ( newValue ) {
												
												//settings_page = elementorFrontendConfig.settings.page;
												settings_page[key] = newValue;
												//console.log( settings_page );

												if( c == c_settings ) PosttElements_ScrollifyHandler( settings_page );

												//elementor.reloadPreview();

												c ++;
											} );
					}*/

				}
			}
		}
	} );
	
} )( jQuery );