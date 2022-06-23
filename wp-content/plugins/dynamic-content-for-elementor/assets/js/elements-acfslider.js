( function( $ ) {
	var WidgetElements_ACFSliderHandler = function( $scope, $ ) {
		//console.log( ' WidgetElements_ACFSliderHandler' );
		//alert('ACFSider');
		
		var elementSettings = get_Dyncontel_ElementSettings( $scope );

		var elementSwiper = $scope.find('.swiper-container')[0];
		var id_scope = $scope.attr('data-id');
		//alert($scope.attr('data-id'));
		//alert(elementSwiper);
		

		var interleaveOffset = -.5;

		var interleaveEffect = {
		  
		  onProgress: function(swiper, progress){
		        
		    for (var i = 0; i < swiper.slides.length; i++){
		      
		      var slide = swiper.slides[i];
		      var translate, innerTranslate;
		      progress = slide.progress;
		            
		      if (progress > 0) {
		        translate = progress * swiper.width;
		        innerTranslate = translate * interleaveOffset;        
		      }
		      else {        
		        innerTranslate = Math.abs( progress * swiper.width ) * interleaveOffset;
		        translate = 0;
		      }
		                if (i == 0) {
		                    console.log(progress + ' <- progress');
		                }
		      var transizione;
		      if(elementSettings.directionSlide == 'horizontal'){
		      	transizione = 'translate3d(' + translate + 'px,0,0)';
		      } else if(elementSettings.directionSlide == 'vertical'){
				transizione = 'translate3d(0,' + translate + 'px,0)';
		      }
		      $(slide).css({
		        transform: transizione,
		      });
		      // ----------
		      var transizioneInterna;
			  if(elementSettings.directionSlide == 'horizontal'){
			    transizioneInterna = 'translate3d(' + innerTranslate + 'px,0,0)';
		      } else if(elementSettings.directionSlide == 'vertical'){
				transizioneInterna = 'translate3d(0,' + innerTranslate + 'px,0)';
		      }
		      $(slide).find('.slide-inner').css({
		        transform: transizioneInterna
		      });
		    }
		  },

		  onTouchStart: function(swiper){
		    for (var i = 0; i < swiper.slides.length; i++){
		      $(swiper.slides[i]).css({ transition: '' });
		    }
		  },

		  onSetTransition: function(swiper, speed) {
		    for (var i = 0; i < swiper.slides.length; i++){
		      $(swiper.slides[i])
		        .find('.slide-inner')
		        .andSelf()
		        .css({ transition: speed + 'ms' });
		    }
		  }
		};
		var swpEffect = 'slide';
		if(elementSettings.effects != 'custom1'){
			swpEffect = elementSettings.effects || 'slide';
		}
		//alert($scope.find('.swiper-slide').length+' '+elementSettings.slidesPerView);
		var centroDiapo = false;
		var cicloInfinito = false;
		
		centroDiapo =  Boolean( elementSettings.centeredSlides );
		cicloInfinito = Boolean( elementSettings.loop );
		
		//alert(swpEffect+' '+elementSettings.directionSlide);
		var swiperOptions = {
			//------------------- Base Settings
			direction: String(elementSettings.directionSlide) || 'horizontal',
			speed: Number(elementSettings.speedSlide) || 300,
			//setWrapperSize: Boolean( elementSettings.setWrapperSize ),
			//virtualTranslate:  Boolean( elementSettings.virtualTranslate ),
			autoHeight:  Boolean( elementSettings.autoHeight ),
			roundLengths:  Boolean( elementSettings.roundLengths ),
			nested: Boolean( elementSettings.nested ),
			grabCursor: Boolean( elementSettings.grabCursor ),
			//------------------- Autoplay
			//autoplay: Number(elementSettings.autoplay) || '',
			//autoplayStopOnLast: Boolean( elementSettings.autoplayStopOnLast ), 
			//autoplayDisableOnInteraction: Boolean( elementSettings.autoplayDisableOnInteraction ),
			//------------------- Progress
			watchSlidesProgress: Boolean( elementSettings.watchSlidesProgress ),
			watchSlidesVisibility: Boolean( elementSettings.watchSlidesVisibility ),
			//------------------- Freemode 
			freeMode: Boolean( elementSettings.freeMode ),
			freeModeMomentum: Boolean( elementSettings.freeModeMomentum ),
			freeModeMomentumRatio: Number(elementSettings.freeModeMomentumRatio) || 1,
			freeModeMomentumVelocityRatio: Number(elementSettings.freeModeMomentumVelocityRatio) || 1, 
			freeModeMomentumBounce: Boolean( elementSettings.freeModeMomentumBounce ), 
			//freeModeMomentumBounceRatio: Number(elementSettings.speed) || 1, 
			//freeModeMinimumVelocity: Number(elementSettings.speed) || 0.02, 
			freeModeSticky: Boolean( elementSettings.freeModeSticky ),
			//------------------- Effects
			effect: swpEffect,
			/*coverflow: {
	            rotate: 50,
	            stretch: 0,
	            depth: 100,
	            modifier: 1,
	            slideShadows : true
	        },*/
	        //------------------- Grid Swiper
	        centerInsufficientSlides: true,
            watchOverflow: true,
			centeredSlides: centroDiapo,
        	
        	spaceBetween: Number(elementSettings.spaceBetween.size) || 0,
        	slidesPerView: Number(elementSettings.slidesPerView) || 'auto',
		   	slidesPerGroup: Number(elementSettings.slidesPerGroup) || 1,
		    slidesPerColumn: Number(elementSettings.slidesColumn) || 1, // 1, // Number of slides per column, for multirow layout
			slidesPerColumnFill: 'row', // Could be 'column' or 'row'. Defines how slides should fill rows, by column or by row

			//------------------- Parallax

			//------------------- Touches, Touch
			//------------------- Swiping / No
			//------------------- Navigation
			//------------------- Keyboard / Mousewheel
			keyboard: Boolean( elementSettings.keyboardControl ),
			mousewheel: Boolean( elementSettings.mousewheelControl ),
			//------------------- Hash/History
			//------------------- Images
			//------------------- Loop
			loop: cicloInfinito,
			//------------------- Zoom


			//------------------- Controls
			// If we need pagination
		    // pagination: '.pagination-acfslider-'+id_scope,
		    // paginationType: String(elementSettings.pagination_type) || 'bullets', //"bullets", "fraction", "progress" 
		    // paginationHide: false,
		    // paginationClickable: false,
		    // paginationElement: 'span',
		    /*paginationFractionRender: function (swiper, currentClassName, totalClassName) {
								return '<span class="' + currentClassName + '"></span>' +
								       '<span class="separator">' + String(elementSettings.fraction_separator) + '</span>' +
								       '<span class="' + totalClassName + '"></span>';
								},*/
			/*paginationBulletRender: function (swiper, index, className) {
								return '<span class="' + className + '">' + (index + 1) + '</span>';
								},*/
			/*paginationProgressRender: function (swiper, progressbarClass) {
								return '<span class="' + progressbarClass + '"></span>';
								},*/
		    pagination: {
			        el: '.pagination-'+id_scope, //'.swiper-pagination', //'.pagination-acfslider-'+id_scope,
			        clickable: true,
			        //hideOnClick: true,
			        type: String(elementSettings.pagination_type) || 'bullets', //"bullets", "fraction", "progressbar" or "custom"
			        //bulletElement: 'span',
			        dynamicBullets: true,
					/*renderBullet: function (index, className) {
					          return '<span class="' + className + '">' + (index + 1) + '</span>';
					        },*/
					renderFraction: function (currentClass, totalClass) {
								return '<span class="' + currentClass + '"></span>' +
								       '<span class="separator">' + String(elementSettings.fraction_separator) + '</span>' +
								       '<span class="' + totalClass + '"></span>';
								},
					/*renderProgressbar: function (progressbarFillClass) {
						      return '<span class="' + progressbarFillClass + '"></span>';
						  },*/
					/*renderCustom: function (swiper, current, total) {
						      return current + ' of ' + total;
						  }*/
					// bulletClass:: 	'swiper-pagination-bullet', //	CSS class name of single pagination bullet
					// bulletActiveClass: 	'swiper-pagination-bullet-active', //	CSS class name of currently active pagination bullet
					// modifierClass: 	'swiper-pagination-', //	The beginning of the modifier CSS class name that will be added to pagination depending on parameters
					// currentClass: 	'swiper-pagination-current', //	CSS class name of the element with currently active index in "fraction" pagination
					// totalClass: 	'swiper-pagination-total', //	CSS class name of the element with total number of "snaps" in "fraction" pagination
					// hiddenClass: 	'swiper-pagination-hidden', //	CSS class name of pagination when it becomes inactive
					// progressbarFillClass: 	'swiper-pagination-progressbar-fill', //	CSS class name of pagination progressbar fill element
					// clickableClass: 	'swiper-pagination-clickable', //	CSS class name set to pagination when it is clickable
				},

			// *********************************************************************************************
		    // Navigation arrows
		    //nextButton: '.next-'+id_scope,
		    //prevButton: '.prev-'+id_scope,
		    spaceBetween: Number(elementSettings.slidesPerView) || 0,
		    navigation: {
			    nextEl: '.next-'+id_scope, //'.swiper-button-next',
			    prevEl: '.prev-'+id_scope, //'.swiper-button-prev',
			    //hideOnClick: false,
			    //disabledClass: 'swiper-button-disabled', //	CSS class name added to navigation button when it becomes disabled
				//hiddenClass: 'swiper-button-hidden', //	CSS class name added to navigation button when it becomes hidden
			},
		    // And if we need scrollbar
		    scrollbar: '.swiper-scrollbar',
			// 
			//------------------- Responsive Params
		    breakpoints: {
			    // Mobile
			    480: {
			      slidesPerView: Number(elementSettings.slidesPerView_mobile) || Number(elementSettings.slidesPerView) || 'auto',
			      slidesPerGroup: Number(elementSettings.slidesPerGroup_mobile) || Number(elementSettings.slidesPerGroup) || 1,
			      spaceBetween: Number(elementSettings.spaceBetween_mobile) || Number(elementSettings.spaceBetween) || 0,
			      slidesPerColumn: Number(elementSettings.slidesColumn_mobile) || Number(elementSettings.slidesColumn) || 1,
			    },
			    // Tablet
			    992: {
			      slidesPerView: Number(elementSettings.slidesPerView_tablet) || Number(elementSettings.slidesPerView) || 'auto',
			      slidesPerGroup: Number(elementSettings.slidesPerGroup_tablet) || Number(elementSettings.slidesPerGroup) || 1,
			      spaceBetween: Number(elementSettings.spaceBetween_tablet) || Number(elementSettings.spaceBetween) || 0,
			      slidesPerColumn: Number(elementSettings.slidesColumn_tablet) || Number(elementSettings.slidesColumn) || 1,
			    }
			  },

		};
		//alert(elementSettings.useAutoplay);
		if( elementSettings.useAutoplay ){
				//alert('autoPlay');
				swiperOptions = $.extend(swiperOptions,{autoplay: true} );
				
			
				if( Number(elementSettings.autoplay) != '' ){
					//delay: Number(elementSettings.autoplay) || 3000, // 2500, // Delay between transitions (in ms). If this parameter is not specified, auto play will be disabled
					swiperOptions = $.extend(swiperOptions,{autoplay: { delay: Number(elementSettings.autoplay) }} );
				}
				if(elementSettings.autoplayDisableOnInteraction){
					//disableOnInteraction:  Boolean( elementSettings.autoplayDisableOnInteraction, // false, // Set to false and autoplay will not be disabled after user interactions (swipes), it will be restarted every time after interaction
					swiperOptions = $.extend(swiperOptions,{autoplay: { disableOnInteraction: Boolean( elementSettings.autoplayDisableOnInteraction ) }} );
				}
				if(elementSettings.autoplayStopOnLast){	
				    swiperOptions = $.extend(swiperOptions,{autoplay: { disableOnInteraction: Boolean( elementSettings.autoplayStopOnLast ) }} );
				}

			}
		//alert(swiperOptions.autoplay);
		//console.log(swOpt); 
		
		if(elementSettings.effects == 'custom1'){
			swiperOptions = $.extend(swiperOptions, interleaveEffect);
		}
		
		if($scope.find('.swiper-slide').length > 1) var dce_swiper = new Swiper( elementSwiper,swiperOptions); 
		
		/*var swiper = new Swiper(elementSwiper, {
	      direction: 'vertical',
	      pagination: {
	        el: '.swiper-pagination',
	        clickable: true,
	      },
	    });*/
		///////////////////////////////////////////////////////////////////////////
		// 								PHOTO SWIPE 							//
		//////////////////////////////////////////////////////////////////////////
		var initPhotoSwipeFromDOM = function(gallerySelector) {
			//alert('PhotoSwipe');
		    // parse slide data (url, title, size ...) from DOM elements 
		    // (children of gallerySelector)
		    var parseThumbnailElements = function(el) {
		        var thumbElements = el.childNodes,
		            numNodes = thumbElements.length,
		            items = [],
		            figureEl,
		            linkEl,
		            size,
		            item;

		        for(var i = 0; i < numNodes; i++) {
		        	//alert(el);
		            figureEl = thumbElements[i]; // <figure> element

		            // include only element nodes 
		            if(figureEl.nodeType !== 1) {
		                continue;
		            }

		            linkEl = figureEl.children[0].getElementsByTagName('a')[0]; // <a> element

		            size = linkEl.getAttribute('data-size').split('x');

		            // create slide object
		            item = {
		                src: linkEl.getAttribute('href'),
		                w: parseInt(size[0], 10),
		                h: parseInt(size[1], 10)
		            };



		            if(figureEl.children.length > 1) {
		                // <figcaption> content
		                item.title = figureEl.children[1].innerHTML; 
		            }

		            if(linkEl.children.length > 0) {
		                // <img> thumbnail element, retrieving thumbnail url
		                item.msrc = linkEl.children[0].getAttribute('src');
		            } 

		            item.el = figureEl; // save link to element for getThumbBoundsFn
		            items.push(item);
		        }

		        return items;
		    };

		    // find nearest parent element
		    var closest = function closest(el, fn) {
		        return el && ( fn(el) ? el : closest(el.parentNode, fn) );
		    };

		    // triggers when user clicks on thumbnail
		    var onThumbnailsClick = function(e) {
		        e = e || window.event;
		        e.preventDefault ? e.preventDefault() : e.returnValue = false;

		        var eTarget = e.target || e.srcElement;

		        // find root element of slide
		        var clickedListItem = closest(eTarget, function(el) {
		            return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
		        });

		        if(!clickedListItem) {
		            return;
		        }

		        // find index of clicked item by looping through all child nodes
		        // alternatively, you may define index via data- attribute
		        var clickedGallery = clickedListItem.parentNode,
		            childNodes = clickedListItem.parentNode.childNodes,
		            numChildNodes = childNodes.length,
		            nodeIndex = 0,
		            index;

		        for (var i = 0; i < numChildNodes; i++) {
		            if(childNodes[i].nodeType !== 1) { 
		                continue; 
		            }

		            if(childNodes[i] === clickedListItem) {
		                index = nodeIndex;
		                break;
		            }
		            nodeIndex++;
		        }



		        if(index >= 0) {
		            // open PhotoSwipe if valid index found
		            openPhotoSwipe( index, clickedGallery );
		        }
		        return false;
		    };

		    // parse picture index and gallery index from URL (#&pid=1&gid=2)
		    var photoswipeParseHash = function() {
		        var hash = window.location.hash.substring(1),
		        params = {};

		        if(hash.length < 5) {
		            return params;
		        }

		        var vars = hash.split('&');
		        for (var i = 0; i < vars.length; i++) {
		            if(!vars[i]) {
		                continue;
		            }
		            var pair = vars[i].split('=');  
		            if(pair.length < 2) {
		                continue;
		            }           
		            params[pair[0]] = pair[1];
		        }

		        if(params.gid) {
		            params.gid = parseInt(params.gid, 10);
		        }

		        return params;
		    };

		    var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
		        var pswpElement = document.querySelectorAll('.pswp')[0],
		            gallery,
		            options,
		            items;

		        items = parseThumbnailElements(galleryElement);

		        // define options (if needed)
		        options = {

		            // define gallery index (for URL)
		            galleryUID: galleryElement.getAttribute('data-pswp-uid'),

		            getThumbBoundsFn: function(index) {
		                // See Options -> getThumbBoundsFn section of documentation for more info
		                var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
		                    pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
		                    rect = thumbnail.getBoundingClientRect(); 

		                return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
		            }

		        };

		        // PhotoSwipe opened from URL
		        if(fromURL) {
		            if(options.galleryPIDs) {
		                // parse real index when custom PIDs are used 
		                // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
		                for(var j = 0; j < items.length; j++) {
		                    if(items[j].pid == index) {
		                        options.index = j;
		                        break;
		                    }
		                }
		            } else {
		                // in URL indexes start from 1
		                options.index = parseInt(index, 10) - 1;
		            }
		        } else {
		            options.index = parseInt(index, 10);
		        }

		        // exit if index not found
		        if( isNaN(options.index) ) {
		            return;
		        }

		        if(disableAnimation) {
		            options.showAnimationDuration = 0;
		        }

		        // Pass data to PhotoSwipe and initialize it
		        gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
		        gallery.init();
		    };

		    // loop through all gallery elements and bind events
		    var galleryElements = document.querySelectorAll( gallerySelector );

		    for(var i = 0, l = galleryElements.length; i < l; i++) {
		        galleryElements[i].setAttribute('data-pswp-uid', i+1);
		        galleryElements[i].onclick = onThumbnailsClick;
		    }

		    // Parse URL and open gallery if it contains #&pid=3&gid=1
		    var hashData = photoswipeParseHash();
		    if(hashData.pid && hashData.gid) {
		        openPhotoSwipe( hashData.pid ,  galleryElements[ hashData.gid - 1 ], true, true );
		    }
		};
		//alert($scope.find('.dynamic_acfslider.gallery-lightbox').length);
		// execute above function
		if($scope.find('.dynamic_acfslider.is-lightbox.photoswipe').length > 0){
			if( $('body').find('.pswp').length < 1 ) photoSwipeContent();
			initPhotoSwipeFromDOM('.dynamic_acfslider.is-lightbox.photoswipe');
		}
	};
	
	// Make sure you run this code under Elementor..
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/dyncontel-acfslider.default', WidgetElements_ACFSliderHandler );
	} );
	var photoSwipeContent = function(){
		$('body').append('<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true"><div class="pswp__bg"></div><div class="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div><div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter"></div><button class="pswp__button pswp__button--close" title="Close (Esc)"></button><button class="pswp__button pswp__button--share" title="Share"></button><button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button><button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"><div class="pswp__share-tooltip"></div></div><button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button><button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button><div class="pswp__caption"><div class="pswp__caption__center"></div></div></div></div></div>');
	}
} )( jQuery );