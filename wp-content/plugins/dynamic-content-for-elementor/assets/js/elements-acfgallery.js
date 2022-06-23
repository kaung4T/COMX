;(function ($) {
    var WidgetDyncontel_ACFGalleryHandler = function ($scope, $) {
        //console.log( $scope );
        var elementSettingsACFGallery = get_Dyncontel_ElementSettings($scope);
        //alert('ACF GALLERY');

        var $block_acfgallery = '.dynamic_acfgallery';
        var $items_acfgallery = '.acfgallery-item';

        var $grid_dce_posts = $scope.find($block_acfgallery);

        if (elementSettingsACFGallery.gallery_type == 'masonry') {
            
            
            // ---------- [ imagesLoaded ] ---------
            $grid_dce_posts.imagesLoaded().progress(function () {
                
                var $masonry_dce_posts = $grid_dce_posts.masonry();
                $scope.find($items_acfgallery).css('opacity',1);
                $masonry_dce_posts.masonry('layout');
            });
        }else if(elementSettingsACFGallery.gallery_type == 'justified') {
            $scope.find('.justified-grid').imagesLoaded().progress(function () {
                
            });
            $scope.find('.justified-grid').justifiedGallery({
                rowHeight : Number(elementSettingsACFGallery.justified_rowHeight.size) || 170,
                maxRowHeight : -1,
                //sort: true,
                selector: 'figure, div:not(.spinner)',
                imgSelector: '> img, > a > img, > div > a > img, > div > img',
                margins: Number(elementSettingsACFGallery.justified_margin.size) || 0,
                lastRow: elementSettingsACFGallery.justified_lastRow
            });
            /*
            rowHeight : 70,
            lastRow : 'nojustify',
            margins : 3
            */

        }else if (elementSettingsACFGallery.gallery_type == 'diamond') {
            var $size_d = elementSettingsACFGallery.size_diamond;
            var column_d = elementSettingsACFGallery.column_diamond;
            /*if( $(window).width() < 992){
             $size_d = elementSettingsACFGallery.size_diamond_mobile;
             }*/
            //alert($size_d.size);
            //
            //$scope.find($block_acfgallery).text('aaa');
            var $diamond_grid = $scope.find($block_acfgallery).diamonds({
                size: $size_d.size || 240, // Size of the squares
                gap: elementSettingsACFGallery.gap_diamond || 0, // Pixels between squares
                itemSelector: ".acfgallery-item",
                hideIncompleteRow: Boolean( elementSettingsACFGallery.hideIncompleteRow ),
                autoRedraw: true,
                minDiamondsPerRow: column_d,
            });
            //alert(elementSettingsACFGallery.gap_diamond);
            /*$scope.find($block_acfgallery).imagesLoaded().progress( function() {
             $scope.find($block_acfgallery).diamonds("draw");
             });*/
            /*$scope.find($block_acfgallery).on("diamonds:beforeSetOptions", function(event, newOptions) {
             alert('sss')
             newOptions.gap = 10; // Always set the gap to 10
             });*/
            /*$scope.find($block_acfgallery).on("diamonds:afterDraw", function(event) { 
             $(this).diamonds("setOptions", {
             gap: 40
             });
             event.preventDefault();
             });*/
            $(window).resize(function () {
                $scope.find($block_acfgallery).diamonds("draw");
                /*$(".diamondswrap").diamonds("setOptions", {
                 size: 
                 });*/
            });
        } else if (elementSettingsACFGallery.gallery_type == 'hexagon') {
            var $size_d = '';
            //alert( $(window).width() < 769 + "   " + $('elementor-preview-responsive-wrapper').innerWidth());
            /*if( $(window).width() < 769 || $('elementor-preview-responsive-wrapper').innerWidth() < 992 ){
             $size_d = elementSettingsACFGallery.size_honeycombs_tablet;
             }else if( $(window).width() < 480 || $('elementor-preview-responsive-wrapper').innerWidth() < 480 ){
             $size_d = elementSettingsACFGallery.size_honeycombs_mobile;
             }else{*/
            $size_d = elementSettingsACFGallery.size_honeycombs;
            /*}*/
            //alert($scope.find($block_acfgallery).attr('class'));
            $scope.find('.honeycombs-grid').honeycombs({
                combWidth: $size_d,
                margin: Number(elementSettingsACFGallery.gap_honeycombs),
                //item: ".acfgallery-item"
            });
        }

        // .................................................................
        /*$scope.find('.grid-item a').on('click',function(e){ 
         //e.preventDefault();
         return false; 
         });*/

        // ======================================================================================
        if (elementSettingsACFGallery.enabled_wow) {
            var wow = new WOW(
                    {
                        //boxClass: 'wow', // animated element css class (default is wow)
                        //animateClass: 'animated', // animation css class (default is animated)
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

        ///////////////////////////////////////////////////////////////////////////
        //                              PHOTO SWIPE                             //
        //////////////////////////////////////////////////////////////////////////
        var initPhotoSwipeFromDOM = function (gallerySelector) {
            //alert('PhotoSwipe');
            // parse slide data (url, title, size ...) from DOM elements 
            // (children of gallerySelector)
            var parseThumbnailElements = function (el) {
                var thumbElements = el.childNodes,
                        numNodes = thumbElements.length,
                        items = [],
                        figureEl,
                        linkEl,
                        size,
                        item;

                for (var i = 0; i < numNodes; i++) {
                    //alert(el);
                    figureEl = thumbElements[i]; // <figure> element

                    // include only element nodes 
                    if (figureEl.nodeType !== 1) {
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



                    if (figureEl.children.length > 1) {
                        // <figcaption> content
                        item.title = figureEl.children[1].innerHTML;
                    }

                    if (linkEl.children.length > 0) {
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
                return el && (fn(el) ? el : closest(el.parentNode, fn));
            };

            // triggers when user clicks on thumbnail
            var onThumbnailsClick = function (e) {
                e = e || window.event;
                e.preventDefault ? e.preventDefault() : e.returnValue = false;

                var eTarget = e.target || e.srcElement;

                // find root element of slide
                var clickedListItem = closest(eTarget, function (el) {
                    return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
                });

                if (!clickedListItem) {
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
                    if (childNodes[i].nodeType !== 1) {
                        continue;
                    }

                    if (childNodes[i] === clickedListItem) {
                        index = nodeIndex;
                        break;
                    }
                    nodeIndex++;
                }



                if (index >= 0) {
                    // open PhotoSwipe if valid index found
                    openPhotoSwipe(index, clickedGallery);
                }
                return false;
            };

            // parse picture index and gallery index from URL (#&pid=1&gid=2)
            var photoswipeParseHash = function () {
                var hash = window.location.hash.substring(1),
                        params = {};

                if (hash.length < 5) {
                    return params;
                }

                var vars = hash.split('&');
                for (var i = 0; i < vars.length; i++) {
                    if (!vars[i]) {
                        continue;
                    }
                    var pair = vars[i].split('=');
                    if (pair.length < 2) {
                        continue;
                    }
                    params[pair[0]] = pair[1];
                }

                if (params.gid) {
                    params.gid = parseInt(params.gid, 10);
                }

                return params;
            };

            var openPhotoSwipe = function (index, galleryElement, disableAnimation, fromURL) {
                var pswpElement = document.querySelectorAll('.pswp')[0],
                        gallery,
                        options,
                        items;

                items = parseThumbnailElements(galleryElement);

                // define options (if needed)
                options = {

                    // define gallery index (for URL)
                    galleryUID: galleryElement.getAttribute('data-pswp-uid'),

                    getThumbBoundsFn: function (index) {
                        // See Options -> getThumbBoundsFn section of documentation for more info
                        var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
                                pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                                rect = thumbnail.getBoundingClientRect();

                        return {x: rect.left, y: rect.top + pageYScroll, w: rect.width};
                    }

                };

                // PhotoSwipe opened from URL
                if (fromURL) {
                    if (options.galleryPIDs) {
                        // parse real index when custom PIDs are used 
                        // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
                        for (var j = 0; j < items.length; j++) {
                            if (items[j].pid == index) {
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
                if (isNaN(options.index)) {
                    return;
                }

                if (disableAnimation) {
                    options.showAnimationDuration = 0;
                }

                // Pass data to PhotoSwipe and initialize it
                gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
                gallery.init();
            };

            // loop through all gallery elements and bind events
            var galleryElements = document.querySelectorAll(gallerySelector);

            for (var i = 0, l = galleryElements.length; i < l; i++) {
                galleryElements[i].setAttribute('data-pswp-uid', i + 1);
                galleryElements[i].onclick = onThumbnailsClick;
            }

            // Parse URL and open gallery if it contains #&pid=3&gid=1
            var hashData = photoswipeParseHash();
            if (hashData.pid && hashData.gid) {
                openPhotoSwipe(hashData.pid, galleryElements[ hashData.gid - 1 ], true, true);
            }
        };
        //alert($scope.find('.dynamic_acfgallery.gallery-lightbox').length);
        // execute above function
        if ($scope.find('.dynamic_acfgallery.is-lightbox.photoswipe, .dynamic_gallery.is-lightbox.photoswipe').length > 0) {
            //alert('photoswipe');
            if ($('body').find('.pswp').length < 1)
                photoSwipeContent();
            initPhotoSwipeFromDOM('.dynamic_acfgallery.is-lightbox.photoswipe, .dynamic_gallery.is-lightbox.photoswipe');
        }
    };

    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/dyncontel-acfgallery.default', WidgetDyncontel_ACFGalleryHandler);
    });
    var photoSwipeContent = function () {
        $('body').append('<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true"><div class="pswp__bg"></div><div class="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div><div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter"></div><button class="pswp__button pswp__button--close" title="Close (Esc)"></button><button class="pswp__button pswp__button--share" title="Share"></button><button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button><button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"><div class="pswp__share-tooltip"></div></div><button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button><button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button><div class="pswp__caption"><div class="pswp__caption__center"></div></div></div></div></div>');
    };
})(jQuery);
