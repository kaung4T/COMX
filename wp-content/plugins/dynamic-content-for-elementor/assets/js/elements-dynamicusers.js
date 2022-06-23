;
var isAdminBar = false,
    isEditMode = false;

( function( $ ) {

	var WidgetElementsDynamicUsersDCEHandler = function( $scope, $ ) {
		//console.log( 'pppppppppppppppp '+$scope );
		//
		//imagesloaded
		//alert('acfPosts');
		// init
		var elementSettings = get_Dyncontel_ElementSettings( $scope );
		var id_scope = $scope.attr('data-id');
		//alert( elementSettings.slides_to_show_tablet );
		//

		$block_acfposts = '.dce-grid-users';
		$objBlock_acfposts = $scope.find($block_acfposts);
		//alert('aaaaaaa');
		/*if(elementSettings.ajax_page_enabled){
			ajaxPage_init(elementSettings.ajax_page_template, id_scope);

		}*/
		//alert( $objBlock_acfposts.data('style') );
		//
		//alert( 'normal: '+elementSettings.slides_to_show+' '+elementSettings.slides_to_scroll );
		//alert( 'tablet: '+elementSettings.slides_to_show_tablet+' '+elementSettings.slides_to_scroll_tablet );
		//alert( 'mobile: '+elementSettings.slides_to_show_mobile+' '+elementSettings.slides_to_scroll_mobile );
		//
		if( elementSettings.posts_style == 'grid' ){
			//alert('mmmmm');
			////////////////////////////////////////////////////////////////// Masonry Isotope
			
			// ------------ [ Isotope ] -----------
			$layoutMode = 'masonry';
			if( $objBlock_acfposts.data('fitrow') ) $layoutMode = 'fitRows';
			var $grid_dce_posts = $objBlock_acfposts.isotope({
			  //columnWidth: 200,
			  itemSelector: '.dce-item-user',
			  layoutMode: $layoutMode,
			  sortBy: 'original-order',
			  percentPosition: true,
				  masonry: {
				    columnWidth: '.dce-item-user'
				  }
			});
			// ---------- [ imagesLoaded ] ---------
		  	$grid_dce_posts.imagesLoaded().progress( function() {
			  	$grid_dce_posts.isotope('layout');
			  	//alert('x');
			});
			// ---------- [ infiniteScroll ] ---------
			/*var iso = $grid_dce_posts.data('isotope');
			$grid_dce_posts.infiniteScroll({
			  // Infinite Scroll options...
			  path: '.pagination__next',
			  history: false,
			  append: '.dce-item-user',
			  outlayer: iso,
			});*/
			//alert('isotope');
			$scope.find('.dce-users-filters .users-filters-item').on( 'click', 'a', function(e) {
				
				//alert($(this).attr('data-filter'));
				
				var filterValue = $(this).attr('data-filter');
				$(this).parent().siblings().removeClass('filter-active');
				$(this).parent().addClass('filter-active');
				//alert(filterValue);
				
				$grid_dce_posts.isotope({ filter: filterValue });
				return false
			});


			
		} 



		// ====================================================================================== WOW
		if ( elementSettings.enabled_wow ){
			var wow = new WOW(
			  {
			    boxClass:     'wow',      // animated element css class (default is wow)
			    animateClass: 'animated', // animation css class (default is animated)
			    offset:       0,          // distance to the element when triggering the animation (default is 0)
			    mobile:       true,       // trigger animations on mobile devices (default is true)
			    live:         true,       // act on asynchronously loaded content (default is true)
			    callback:     function(box) {
			      // the callback is fired every time an animation is started
			      // the argument that is passed in is the DOM node being animated
			    },
			    scrollContainer: null // optional scroll container selector, otherwise use window
			  }
			);
			wow.init();
		}
		// ====================================================================================== VERTCAL-TIMELINE
		function VerticalTimeline( element ) {
			this.element = element;
			this.blocks = this.element.getElementsByClassName("js-cd-block");
			this.images = this.element.getElementsByClassName("js-cd-img");
			this.contents = this.element.getElementsByClassName("js-cd-content");
			this.offset = 0.8;
			this.hideBlocks();
		};

		VerticalTimeline.prototype.hideBlocks = function() {
			//hide timeline blocks which are outside the viewport
			if ( !"classList" in document.documentElement ) {
				return;
			}
			var self = this;
			for( var i = 0; i < this.blocks.length; i++) {
				(function(i){
					if( self.blocks[i].getBoundingClientRect().top > window.innerHeight*self.offset ) {
						self.images[i].classList.add("cd-is-hidden"); 
						self.contents[i].classList.add("cd-is-hidden"); 
					}
				})(i);
			}
		};

		VerticalTimeline.prototype.showBlocks = function() {
			if ( ! "classList" in document.documentElement ) {
				return;
			}
			var self = this;
			for( var i = 0; i < this.blocks.length; i++) {
				(function(i){
					if( self.contents[i].classList.contains("cd-is-hidden") && self.blocks[i].getBoundingClientRect().top <= window.innerHeight*self.offset ) {
						// add bounce-in animation
						self.images[i].classList.add("cd-timeline__img--bounce-in");
						self.contents[i].classList.add("cd-timeline__content--bounce-in");
						self.images[i].classList.remove("cd-is-hidden");
						self.contents[i].classList.remove("cd-is-hidden");
					}
				})(i);
			}
		};


		// ----- Inizializzo la timeline -----
		var verticalTimelines = document.getElementsByClassName("js-cd-timeline"),
			verticalTimelinesArray = [],
			scrolling = false;
		if( verticalTimelines.length > 0 ) {
			for( var i = 0; i < verticalTimelines.length; i++) {
				(function(i){
					verticalTimelinesArray.push(new VerticalTimeline(verticalTimelines[i]));
				})(i);
			}

			//show timeline blocks on scrolling
			window.addEventListener("scroll", function(event) {
				if( !scrolling ) {
					scrolling = true;
					(!window.requestAnimationFrame) ? setTimeout(checkTimelineScroll, 250) : window.requestAnimationFrame(checkTimelineScroll);
				}
			});
		}

		function checkTimelineScroll() {
			verticalTimelinesArray.forEach(function(timeline){
				timeline.showBlocks();
			});
			scrolling = false;
		};

	};  // ---> end elementor Ready




	// *********************************************************************************
	$( window ).on( 'elementor/frontend/init', function() {
		//alert('sssss');
		if ( elementorFrontend.isEditMode() ) {
			isEditMode = true;
		}

		if ( $('body').is('.admin-bar') ) {
			isAdminBar = true;
		}
		//inizioP();
		elementorFrontend.hooks.addAction( 'frontend/element_ready/dyncontel-dynamicusers.default', WidgetElementsDynamicUsersDCEHandler );
	} );
	// *********************************************************************************


} )( jQuery );
