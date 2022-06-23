;
var isAdminBar = false,
        isEditMode = false;

(function ($) {
    var get_Dyncontel_ElementSettings = function ($element) {

        var elementSettings = {},
                modelCID = $element.data('model-cid');

        if (elementorFrontend.isEditMode() && modelCID) {
            var settings = elementorFrontend.config.elements.data[ modelCID ],
                    settingsKeys = elementorFrontend.config.elements.keys[ settings.attributes.widgetType || settings.attributes.elType ];

            jQuery.each(settings.getActiveControls(), function (controlKey) {
                if (-1 !== settingsKeys.indexOf(controlKey)) {
                    elementSettings[ controlKey ] = settings.attributes[ controlKey ];
                }
            });
        } else {
            elementSettings = $element.data('settings') || {};
        }
        return elementSettings;
    }

    var WidgetElementsPostsDCEHandler = function ($scope, $) {
        var infScroll = null;
        //imagesloaded
        //alert('acfPosts');
        // init
        var elementSettings = get_Dyncontel_ElementSettings($scope),
                id_scope = $scope.attr('data-id'),
                elementorElement = '.elementor-element-' + id_scope,
                is_history = Boolean( elementSettings.infiniteScroll_enable_history ) ? 'replace' : false
            $block_acfposts = '.acfposts-grid',
                $objBlock_acfposts = $scope.find($block_acfposts);

        /*if(elementSettings.ajax_page_enabled){
         ajaxPage_init(elementSettings.ajax_page_template, id_scope);
         }*/
        //alert( $objBlock_acfposts.data('style') );
        //
        //alert( 'normal: '+elementSettings.slides_to_show+' '+elementSettings.slides_to_scroll );
        //alert( 'tablet: '+elementSettings.slides_to_show_tablet+' '+elementSettings.slides_to_scroll_tablet );
        //alert( 'mobile: '+elementSettings.slides_to_show_mobile+' '+elementSettings.slides_to_scroll_mobile );
        //
        if ($objBlock_acfposts.data('style') == 'grid') {

            ////////////////////////////////////////////////////////////////// Masonry Isotope

            // ------------ [ Isotope ] -----------
            $layoutMode = 'masonry';
            if ($objBlock_acfposts.data('fitrow'))
                $layoutMode = 'fitRows';
            var $grid_dce_posts = $objBlock_acfposts.isotope({
                //columnWidth: 200,
                itemSelector: '.dce-post-item',
                layoutMode: $layoutMode,
                sortBy: 'original-order',
                percentPosition: true,
                masonry: {
                    columnWidth: '.dce-post-item'
                }
            });
            // ---------- [ imagesLoaded ] ---------
            $grid_dce_posts.imagesLoaded().progress(function () {
                $grid_dce_posts.isotope('layout');
                //alert('x');
            });
            // ---------- [ infiniteScroll ] ---------
            var iso = $grid_dce_posts.data('isotope');

            $scope.find('.dce-filters .filters-item').on('click', 'a', function (e) {
                var filterValue = $(this).attr('data-filter');
                $(this).parent().siblings().removeClass('filter-active');
                $(this).parent().addClass('filter-active');
                //alert(filterValue);

                $grid_dce_posts.isotope({filter: filterValue});
                
                // callto infinite scroll
                if (elementSettings.infiniteScroll_enable) {
                    if ($objBlock_acfposts.length) {
                        $objBlock_acfposts.infiniteScroll('loadNextPage');
                    }
                }
                
                return false
            });


        } else if ($($scope).find($block_acfposts).data('style') == 'carousel') {

            // alert( 'normal: '+elementSettings.slides_to_show+' '+elementSettings.slides_to_scroll );
            // alert( 'tablet: '+elementSettings.slides_to_show_tablet+' '+elementSettings.slides_to_scroll_tablet );
            // alert( 'mobile: '+elementSettings.slides_to_show_mobile+' '+elementSettings.slides_to_scroll_mobile );

            var slidesToShow = elementSettings.slides_to_show || 3,
                    isSingleSlide = 1 === slidesToShow,
                    centro = true,
                    cicloInfinito = false;;
            //alert($objBlock_acfposts.children().length+' '+centro);
            //if ($objBlock_acfposts.children().length > 1)    
              //  centro = 'yes' === elementSettings.carousel_center_enable;
            
            var slideNum = $scope.find('.dce-post-item').length;
            // 
            if (slideNum < Number(elementSettings.slides_to_show)) {
                centroDiapo = true;
                cicloInfinito = false;
                slideInitNum = Math.ceil(slideNum / 2);
                //slidesPerView = slideNum;

            } else {
                centro = Boolean( elementSettings.carousel_center_enable );
                cicloInfinito = Boolean( elementSettings.carousel_infinite_enable );
                //slidesPerView = Number(elementSettings.slidesPerView);
            }
            //alert(elementSettings.slides_to_show);
            var slickOptions = {
                dots: Boolean( elementSettings.carousel_dots_enable ),
                arrows: Boolean( elementSettings.carousel_arrow_enable ),
                prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',
                infinite: cicloInfinito,
                autoplay: Boolean( elementSettings.carousel_autoplay_enable ),
                centerPadding: false,
                //rtl: false,
                centerMode: Boolean(centro),
                //variableWidth: true,
                speed: elementSettings.carousel_speed || 500,
                autoplaySpeed: elementSettings.carousel_autoplayspeed || 3000,
                slidesToShow: Number(elementSettings.slides_to_show) || 4,
                slidesToScroll: Number(elementSettings.slides_to_scroll) || 4,
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: Number(elementSettings.slides_to_show_tablet) || 2,
                            slidesToScroll: Number(elementSettings.slides_to_scroll_tablet) || 2,
                            dots: false
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: Number(elementSettings.slides_to_show_mobile) || 1,
                            slidesToScroll: Number(elementSettings.slides_to_scroll_mobile) || 1,
                            dots: false
                        }
                    }
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ]
            };
            if (isSingleSlide) {
                slickOptions.fade = 'fade' === elementSettings.carousel_effect;
            } else {
                slickOptions.slidesToScroll = +elementSettings.slides_to_scroll;
            }
            ////////////////////////////////////////////////////////////////// Carosello
            //$('.slick-arrow').live('click',function(){ elementSettings.slides_to_scroll });
            //alert($num_col+' '+$num_col_tablet+' '+$num_col_mobile);
            //alert(elementSettings.slides_to_show+' - '+elementSettings.slides_to_scroll)
            //alert(elementSettings.carousel_dots_enable);
            //alert(Boolean(centro));
            $objBlock_acfposts.slick(slickOptions);
            

        } else if ($($scope).find($block_acfposts).data('style') == 'swiper') {

            // ============================= Swiper 4  (Non uso ancora per compatibilità con Elementor) ==============================


            //alert($objBlock_acfposts.attr('class'));

            var elementSettings = get_Dyncontel_ElementSettings($scope);
            var elementSwiper = $scope.find('.swiper-container')[0];

            var id_scope = $scope.attr('data-id');

            var centroDiapo = false;
            var cicloInfinito = false;
            var slideInitNum = 0;
            var slidesPerView = Number(elementSettings.slidesPerView);

            var slideNum = $scope.find('.dce-post-item').length;
            //
            /*
            if (slideNum < Number(elementSettings.slidesPerView)) {
                centroDiapo = true;
                cicloInfinito = false;
                slideInitNum = Math.ceil(slideNum / 2)-1;
                //slidesPerView = slideNum;

            }*/
            centerDiapo = Boolean( elementSettings.centeredSlides );
            cicloInfinito = Boolean( elementSettings.loop );
                //slidesPerView = Number(elementSettings.slidesPerView);
           
            //alert($scope.find('.dce-post-item').length+' '+Number(elementSettings.slidesPerView));
            
            var swiperOptions = {
                // Optional parameters
                direction: 'horizontal', //String(elementSettings.direction_slider) || 'horizontal', //vertical
                // 
                initialSlide: slideInitNum,
                // 
                speed: Number(elementSettings.speed_slider) || 300,
                // setWrapperSize: false, // Enabled this option and plugin will set width/height on swiper wrapper equal to total size of all slides. Mostly should be used as compatibility fallback option for browser that don't support flexbox layout well
                // virtualTranslate: false, // Enabled this option and swiper will be operated as usual except it will not move, real translate values on wrapper will not be set. Useful when you may need to create custom slide transition
                autoHeight: Boolean( elementSettings.autoHeight ), //false, // Set to true and slider wrapper will adopt its height to the height of the currently active slide
                roundLengths: Boolean( elementSettings.roundLengths ), //false, // Set to true to round values of slides width and height to prevent blurry texts on usual resolution screens (if you have such)
                // nested : Boolean( elementSettings.nested ), //false, // Set to true on nested Swiper for correct touch events interception. Use only on nested swipers that use same direction as the parent one
                // uniqueNavElements: true, // If enabled (by default) and navigation elements' parameters passed as a string (like ".pagination") then Swiper will look for such elements through child elements first. Applies for pagination, prev/next buttons and scrollbar elements
                //
                //effect: 'cube', //"slide", "fade", "cube", "coverflow" or "flip"
                effect: elementSettings.effects || 'slide',
                /*cubeEffect: {
                 shadow: true,
                 slideShadows: true,
                 shadowOffset: 20,
                 shadowScale: 0.94,
                 },*/
                /*coverflowEffect: {
                 rotate: 50,
                 stretch: 0,
                 depth: 100,
                 modifier: 1,
                 slideShadows : true,
                 },*/
                /*flipEffect: {
                 rotate: 30,
                 //slideShadows: true, //   Enables slides shadows
                 //limitRotation: true, //  Limit edge slides rotation
                 },*/

                // PARALLAX (è da implementare)
                //paralax: true,

                // LAZY-LOADING (è da implementare)
                //lazy: true,
                /*lazy {
                 loadPrevNext: false, //    Set to "true" to enable lazy loading for the closest slides images (for previous and next slide images)
                 loadPrevNextAmount: 1, //  Amount of next/prev slides to preload lazy images in. Can't be less than slidesPerView
                 loadOnTransitionStart: false, //   By default, Swiper will load lazy images after transition to this slide, so you may enable this parameter if you need it to start loading of new image in the beginning of transition
                 elementClass: 'swiper-lazy', //    CSS class name of lazy element
                 loadingClass: 'swiper-lazy-loading', //    CSS class name of lazy loading element
                 loadedClass: 'swiper-lazy-loaded', //  CSS class name of lazy loaded element
                 preloaderClass: 'swiper-lazy-preloader', //    CSS class name of lazy preloader
                 },*/

                // ZOOM (è da implementare)
                /*zoom {
                 maxRatio:  3, // Maximum image zoom multiplier
                 minRatio: 1, //    Minimal image zoom multiplier
                 toggle: true, //   Enable/disable zoom-in by slide's double tap
                 containerClass:    'swiper-zoom-container', // CSS class name of zoom container
                 zoomedSlideClass: 'swiper-slide-zoomed' // CSS class name of zoomed in container
                 },*/
                //slidesPerView: 'auto',
                slidesPerView: slidesPerView || 'auto',
                slidesPerGroup: Number(elementSettings.slidesPerGroup) || 1, // Set numbers of slides to define and enable group sliding. Useful to use with slidesPerView > 1


                spaceBetween: Number(elementSettings.spaceBetween) || 0, // 30,
                // ----------------------------
                slidesOffsetBefore: 0, //   Add (in px) additional slide offset in the beginning of the container (before all slides)
                slidesOffsetAfter: 0, //    Add (in px) additional slide offset in the end of the container (after all slides)

                slidesPerColumn: Number(elementSettings.slidesColumn) || 1, // 1, // Number of slides per column, for multirow layout
                slidesPerColumnFill: 'row', // Could be 'column' or 'row'. Defines how slides should fill rows, by column or by row

                centerInsufficientSlides: true,
                watchOverflow: true,
                centeredSlides: centroDiapo,

                grabCursor: Boolean( elementSettings.grabCursor ), //true,

                //------------------- Freemode
                freeMode: Boolean( elementSettings.freeMode ),
                freeModeMomentum: Boolean( elementSettings.freeModeMomentum ),
                freeModeMomentumRatio: Number(elementSettings.freeModeMomentumRatio) || 1,
                freeModeMomentumVelocityRatio: Number(elementSettings.freeModeMomentumVelocityRatio) || 1,
                freeModeMomentumBounce: Boolean( elementSettings.freeModeMomentumBounce ),
                freeModeMomentumBounceRatio: Number(elementSettings.speed) || 1,
                freeModeMinimumVelocity: Number(elementSettings.speed) || 0.02,
                freeModeSticky: Boolean( elementSettings.freeModeSticky ),

                loop: cicloInfinito, // true,
                //loopFillGroupWithBlank: true,

                // ----------------------------
                // HASH (è da implementare)
                /*hashNavigation: {
                 //watchState   //default: false    Set to true to enable also navigation through slides (when hashnav is enabled) by browser history or by setting directly hash on document location
                 replaceState: true,    // default: false //    Works in addition to hashnav to replace current url state with the new one instead of adding it to history
                 },*/
                // HISTORY (è da implementare)
                //history: false,
                /*history: {
                 replaceState: false, //    Works in addition to hashnav or history to replace current url state with the new one instead of adding it to history
                 key: 'slides' //   Url key for slides
                 },*/
                // CONTROLLER (è da implementare)
                //controller: false,
                /*controller: {
                 control:   [Swiper Instance]   undefined   Pass here another Swiper instance or array with Swiper instances that should be controlled by this Swiper
                 inverse: false, // Set to true and controlling will be in inverse direction
                 by: 'slide', // Can be 'slide' or 'container'. Defines a way how to control another slider: slide by slide (with respect to other slider's grid) or depending on all slides/container (depending on total slider percentage)
                 },*/


                // ----------------------------


                navigation: {
                    nextEl: '.next-' + id_scope, //'.swiper-button-next',
                    prevEl: '.prev-' + id_scope, //'.swiper-button-prev',
                    //hideOnClick: false,
                    //disabledClass: 'swiper-button-disabled', //   CSS class name added to navigation button when it becomes disabled
                    //hiddenClass: 'swiper-button-hidden', //   CSS class name added to navigation button when it becomes hidden
                },
                pagination: {
                    el: '.pagination-' + id_scope, //'.swiper-pagination', //'.pagination-acfslider-'+id_scope,
                    clickable: true,
                    //hideOnClick: true,
                    type: String(elementSettings.pagination_type) || 'bullets', //"bullets", "fraction", "progressbar" or "custom"
                    //bulletElement: 'span',
                    dynamicBullets: true,
                    //dynamicMainBullets: 1,
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
                    // bulletClass::    'swiper-pagination-bullet', //  CSS class name of single pagination bullet
                    // bulletActiveClass:   'swiper-pagination-bullet-active', //   CSS class name of currently active pagination bullet
                    // modifierClass:   'swiper-pagination-', //    The beginning of the modifier CSS class name that will be added to pagination depending on parameters
                    // currentClass:    'swiper-pagination-current', // CSS class name of the element with currently active index in "fraction" pagination
                    // totalClass:  'swiper-pagination-total', //   CSS class name of the element with total number of "snaps" in "fraction" pagination
                    // hiddenClass:     'swiper-pagination-hidden', //  CSS class name of pagination when it becomes inactive
                    // progressbarFillClass:    'swiper-pagination-progressbar-fill', //    CSS class name of pagination progressbar fill element
                    // clickableClass:  'swiper-pagination-clickable', //   CSS class name set to pagination when it is clickable
                },
                // watchSlidesProgress:  Boolean( elementSettings.watchSlidesProgress ), //false, // Enable this feature to calculate each slides progress
                // watchSlidesVisibility:  Boolean( elementSettings.watchSlidesVisibility ), // false, // watchSlidesProgress should be enabled. Enable this option and slides that are in viewport will have additional visible class
                /*scrollbar: {
                 
                 
                 el: '.swiper-scrollbar', //    null    String with CSS selector or HTML element of the container with scrollbar.
                 hide: true,    // boolean  true    Hide scrollbar automatically after user interaction
                 //draggable: false, // Set to true to enable make scrollbar draggable that allows you to control slider position
                 //snapOnRelease: false, // Set to true to snap slider position to slides when you release scrollbar
                 //dragSize: 'auto', //     string/number   Size of scrollbar draggable element in px
                 },*/
                mousewheel: Boolean( elementSettings.mousewheelControl ), // true,
                /*mousewheel: {
                    forceToAxis: false //   Set to true to force mousewheel swipes to axis. So in horizontal mode mousewheel will work only with horizontal mousewheel scrolling, and only with vertical scrolling in vertical mode.
                    releaseOnEdges: false // Set to true and swiper will release mousewheel event and allow page scrolling when swiper is on edge positions (in the beginning or in the end)
                    invert: false // Set to true to invert sliding direction
                    sensitivity: 1, // Multiplier of mousewheel data, allows to tweak mouse wheel sensitivity
                    eventsTarged: 'container' // String with CSS selector or HTML element of the container accepting mousewheel events. By default it is swiper-container
                },*/
                //keyboard: Boolean( elementSettings.keyboardControl ),
                
                 keyboard: {
                    enabled: Boolean( elementSettings.keyboardControl ),
                    //onlyInViewport: false,
                },
                //     },
                //------------------- Responsive Params
                breakpoints: {
                    // Mobile
                    600: {
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

                on: {
                    init: function () {
                      $('body').attr('data-carousel-'+id_scope, this.realIndex);
                    },
                    slideChange: function (e) {
                      $('body').attr('data-carousel-'+id_scope, this.realIndex);
                    },
                  }
            };
            if (elementSettings.useAutoplay) {
                swiperOptions = $.extend(swiperOptions, {autoplay: true});


                if (Number(elementSettings.autoplay) != '') {
                    //delay: Number(elementSettings.autoplay) || 3000, // 2500, // Delay between transitions (in ms). If this parameter is not specified, auto play will be disabled
                    swiperOptions = $.extend(swiperOptions, {autoplay: {delay: Number(elementSettings.autoplay)}});
                }
                if (elementSettings.autoplayDisableOnInteraction ) {
                    //disableOnInteraction:  Boolean( elementSettings.autoplayDisableOnInteraction, // false, // Set to false and autoplay will not be disabled after user interactions (swipes), it will be restarted every time after interaction
                    swiperOptions = $.extend(swiperOptions, {autoplay: {disableOnInteraction: Boolean( elementSettings.autoplayDisableOnInteraction )}});
                }
                if (elementSettings.autoplayStopOnLast) {
                    swiperOptions = $.extend(swiperOptions, {autoplay: {disableOnInteraction: Boolean( elementSettings.autoplayStopOnLast )}});
                }

            }
            //alert(swiperOptions['watchOverflow']+' num: '+slideNum+' - sxv:'+swiperOptions['slidesPerView']);
            
                var mySwiper = new Swiper(elementSwiper, swiperOptions);

        }
        // ====================================================================================== InfiniteScroll
        if ($objBlock_acfposts.data('style') == 'grid' || $objBlock_acfposts.data('style') == 'flexgrid') {
            if (elementSettings.infiniteScroll_enable) {
                //
                var infiniteScroll_options = {
                    // Infinite Scroll options...
                    path: elementorElement + ' .pagination__next',

                    history: is_history,
                    //history: 'push',

                    append: elementorElement + ' .dce-post-item',
                    outlayer: iso,

                    status: elementorElement + ' .page-load-status',
                    hideNav: elementorElement + '.pagination',

                    // disable loading on scroll
                    scrollThreshold: 'scroll' === elementSettings.infiniteScroll_trigger ? true : false,
                    loadOnScroll: 'scroll' === elementSettings.infiniteScroll_trigger ? true : false,
                    //prefill: true

                    onInit: function () {
                        this.on('load', function () {
                            //console.log('Infinite Scroll load');
                        });
                    }
                }
                if (elementSettings.infiniteScroll_trigger == 'button') {
                    // load pages on button click
                    infiniteScroll_options['button'] = elementorElement + ' .view-more-button';
                }
                infScroll = $objBlock_acfposts.infiniteScroll(infiniteScroll_options);
                
                // fix for infinitescroll + masonry
                var nElements = jQuery(elementorElement + ' .dce-post-item:visible').length; // initial length
                //console.log('elements: '+nElements);
                $objBlock_acfposts.on( 'append.infiniteScroll', function( event, response, path, items ) {
                    setTimeout(function(){
                        var nElementsVisible = jQuery(elementorElement + ' .dce-post-item:visible').length;
                        //console.log('carico altri: '+nElementsVisible);
                        if (nElementsVisible <= nElements) {
                            // force another load 
                            //console.log('carico altri: '+nElementsVisible);
                            $objBlock_acfposts.infiniteScroll('loadNextPage');
                        }
                        //console.log('elements: '+nElements);
                    }, 1000);
                });
            }
        }
        // ====================================================================================== WOW
        if (elementSettings.enabled_wow) {
            var wow = new WOW(
                    {
                        boxClass: 'wow', // animated element css class (default is wow)
                        animateClass: 'animated', // animation css class (default is animated)
                        offset: 0, // distance to the element when triggering the animation (default is 0)
                        mobile: true, // trigger animations on mobile devices (default is true)
                        live: true, // act on asynchronously loaded content (default is true)
                        callback: function (box) {
                            // the callback is fired every time an animation is started
                            // the argument that is passed in is the DOM node being animated
                        },
                        scrollContainer: null // optional scroll container selector, otherwise use window
                    }
            );
            wow.init();
        }
        // ====================================================================================== VERTCAL-TIMELINE
        function VerticalTimeline(element) {
            this.element = element;
            this.blocks = this.element.getElementsByClassName("js-cd-block");
            this.images = this.element.getElementsByClassName("js-cd-img");
            this.contents = this.element.getElementsByClassName("js-cd-content");
            this.offset = 0.8;
            this.hideBlocks();
        }
        ;

        VerticalTimeline.prototype.hideBlocks = function () {
            //hide timeline blocks which are outside the viewport
            if (!"classList" in document.documentElement) {
                return;
            }
            var self = this;
            for (var i = 0; i < this.blocks.length; i++) {
                (function (i) {
                    if (self.blocks[i].getBoundingClientRect().top > window.innerHeight * self.offset) {
                        if (self.images[i]) {
                            self.images[i].classList.add("cd-is-hidden");
                        }
                        if (self.contents[i]) {
                            self.contents[i].classList.add("cd-is-hidden");
                        }
                    }
                })(i);
            }
        };

        VerticalTimeline.prototype.showBlocks = function () {
            if (!"classList" in document.documentElement) {
                return;
            }
            var self = this;
            if (self.contents.length) {
                for (var i = 0; i < this.blocks.length; i++) {
                    (function (i) {
                        if (self.contents[i].classList.contains("cd-is-hidden") && self.blocks[i].getBoundingClientRect().top <= window.innerHeight * self.offset) {
                            // add bounce-in animation
                            self.images[i].classList.add("cd-timeline__img--bounce-in");
                            self.contents[i].classList.add("cd-timeline__content--bounce-in");
                            self.images[i].classList.remove("cd-is-hidden");
                            self.contents[i].classList.remove("cd-is-hidden");
                        }
                    })(i);
                }
            }
        };


        // ----- Inizializzo la timeline -----
        var verticalTimelines = document.getElementsByClassName("js-cd-timeline"),
                verticalTimelinesArray = [],
                scrolling = false;
        if (verticalTimelines.length > 0) {
            for (var i = 0; i < verticalTimelines.length; i++) {
                (function (i) {
                    verticalTimelinesArray.push(new VerticalTimeline(verticalTimelines[i]));
                })(i);
            }
            jQuery('.wrap-p .modal-p').on("scroll", function (event) {
                if (!scrolling) {
                    scrolling = true;
                    (!window.requestAnimationFrame) ? setTimeout(checkTimelineScroll, 250) : window.requestAnimationFrame(checkTimelineScroll);
                }
            });
            //show timeline blocks on scrolling
            window.addEventListener("scroll", function (event) {
                if (!scrolling) {
                    scrolling = true;
                    (!window.requestAnimationFrame) ? setTimeout(checkTimelineScroll, 250) : window.requestAnimationFrame(checkTimelineScroll);
                }
            });
        }

        function checkTimelineScroll() {
            verticalTimelinesArray.forEach(function (timeline) {
                timeline.showBlocks();
            });
            scrolling = false;
        }
        ;

    };  // ---> end elementor Ready




    // *********************************************************************************
    $(window).on('elementor/frontend/init', function () {
        //alert('sssss');
        if (elementorFrontend.isEditMode()) {
            isEditMode = true;
        }

        if ($('body').is('.admin-bar')) {
            isAdminBar = true;
        }
        //inizioP();
        elementorFrontend.hooks.addAction('frontend/element_ready/dyncontel-acfposts.default', WidgetElementsPostsDCEHandler);
    });
    // *********************************************************************************


})(jQuery);
