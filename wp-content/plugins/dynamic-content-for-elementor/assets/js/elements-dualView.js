(function ($) {
    // -----------
    //$('body').addClass('loading');
    jQuery('body').addClass('dce-dual-view');
    // -----------
    if ($('#dce-wrap').length == 0) {
        $('body').wrapInner('<div id="dce-outer-wrap"><div id="dce-wrap"></div></div>');
    }
    var WidgetElements_DualViewHandler = function ($scope, $) {
        console.log($scope);

        var dualViewId = $scope.data('id');
        var dualView_Istance = $scope.find('.dce-dualView');
        var quickView_Element = '<div id="cd-quick-view-' + dualViewId + '" class="cd-quick-view"></div>';
        //$('body').append('<div class="dce-dualView"></div>');
        $(quickView_Element).appendTo("body");
        var quickView_Instance = $('#cd-quick-view-' + dualViewId); //$scope.find('.cd-quick-view');
        //alert(quickView_Instance.attr('id'));
        var emptedImg;
        //alert(dualViewId);

        // -----------
        $('body').addClass('dce-dual-view-' + dualViewId);
        // -----------

        var dualViewSettings = get_Dyncontel_ElementSettings($scope);

        // -----------------------------------------------------------

        /*var sliderFinalWidth = 400,
         maxQuickWidth = 900;*/
        var sliderFinalWidth = $(window).width(),
                maxQuickWidth = $(window).width(),
                maxQuickHeight = $(window).height();

        //open the quick view panel
        $scope.find('.cd-trigger').on('click', function (event) {
            var selectedImage = $(this).parent('.cd-item').children('img'),
                    slectedImageUrl = selectedImage.attr('src');



            //update the visible slider image in the quick view panel
            //you don't need to implement/use the updateQuickView if retrieving the quick view data with ajax

            // OK riempio il quick-view con i dati dell'elemento cliccato
            var linkHref = jQuery(this).attr('href');
            var $tid = dualViewSettings.ajax_page_template;
            //

            //
            if (typeof $tid !== 'undefined') {

                newLocation = linkHref;
                //
                jQuery.ajax({
                    url: dceAjaxPath.ajaxurl, //linkHref, //
                    dataType: "html",
                    type: 'POST',
                    //context: document.body,
                    data: {
                        'action': 'dualview_action',
                        'post_href': linkHref,
                        'template_id': $tid,
                    },
                    error: function () {
                        alert('An error has occurred');
                    },

                    success: function (data, status, xhr) {
                        //
                        var $result = data; //$.parseHTML(xhr.responseText,true);
                        //

                        var quelloCheVoglio = jQuery(data).filter('.cd-contenuto').html();
                        quickView_Instance.html(quelloCheVoglio);
                        //alert('fatto');

                        // --------------------------------------------------------------------------
                        $('body').addClass('overlay-layer');

                        animateQuickView(selectedImage, sliderFinalWidth, maxQuickWidth, 'open');
                        oceanwpCustomSelects();
                        updateQuickView(slectedImageUrl);
                        // --------------------------------------------------------------------------
                        //close the quick view panel
                        $('body').on('click', function (event) {
                            if ($(event.target).is('.cd-close') || $(event.target).is('body.overlay-layer')) {
                                closeQuickView(sliderFinalWidth, maxQuickWidth);
                            }
                        });
                        $(document).keyup(function (event) {
                            //check if user has pressed 'Esc'
                            if (event.which == '27') {
                                closeQuickView(sliderFinalWidth, maxQuickWidth);
                            }
                        });

                    },
                });
            } else {
                alert('Select a template');
            } // end IF ....



            return false;
        });

        //quick view slider implementation
        quickView_Instance.on('click', '.cd-slider-navigation a', function () {
            updateSlider($(this));
        });

        //center quick-view on window resize
        $(window).on('resize', function () {
            if (quickView_Instance.hasClass('is-visible')) {
                window.requestAnimationFrame(resizeQuickView);
            }
        });
        function updateSlider(navigation) {
            var sliderConatiner = navigation.parents('.cd-slider-wrapper').find('.cd-slider'),
                    activeSlider = sliderConatiner.children('.selected').removeClass('selected');
            if (navigation.hasClass('cd-next')) {
                (!activeSlider.is(':last-child')) ? activeSlider.next().addClass('selected') : sliderConatiner.children('li').eq(0).addClass('selected');
            } else {
                (!activeSlider.is(':first-child')) ? activeSlider.prev().addClass('selected') : sliderConatiner.children('li').last().addClass('selected');
            }
        }
        function updateQuickView(url) {
            quickView_Instance.find('.cd-slider li').removeClass('selected').find('img[src="' + url + '"]').parent('li').addClass('selected');
        }

        function resizeQuickView() {
            var quickViewLeft = ($(window).width() - quickView_Instance.width()) / 2,
                    quickViewTop = ($(window).height() - quickView_Instance.height()) / 2;
            quickView_Instance.css({
                "top": quickViewTop,
                "left": quickViewLeft,
            });
        }

        function closeQuickView(finalWidth, maxQuickWidth) {
            //alert('qv '+quickView_Instance.attr('id'));
            var close = quickView_Instance.find('.cd-close'),
                    activeSliderUrl = close.siblings('.cd-slider-wrapper').find('.selected img').attr('src'),
                    selectedImage = $('.empty-box').find('img');

            //alert('imm '+close.attr('class'));
            //update the image in the gallery
            if (!quickView_Instance.hasClass('velocity-animating') && quickView_Instance.hasClass('add-content')) {
                selectedImage.attr('src', activeSliderUrl);
                animateQuickView(selectedImage, finalWidth, maxQuickWidth, 'close');
            } else {
                closeNoAnimation(selectedImage, finalWidth, maxQuickWidth);
            }
            jQuery('body').off('click');
            jQuery(document).off('keyup');
        }

        function animateQuickView(image, finalWidth, maxQuickWidth, animationType) {
            //alert(image.attr('src'));
            //store some image data (width, top position, ...)
            //store window data to calculate quick view panel position
            var parentListItem = image.parent('.cd-item'),
                    topSelected = image.offset().top - $(window).scrollTop(),
                    leftSelected = image.offset().left,
                    widthSelected = image.width(),
                    heightSelected = image.height(),
                    windowWidth = $(window).width(),
                    windowHeight = $(window).height(),
                    finalLeft = (windowWidth - finalWidth) / 2,
                    finalHeight = finalWidth * heightSelected / widthSelected,
                    finalTop = (windowHeight - finalHeight) / 2,
                    quickViewWidth = (windowWidth * .8 < maxQuickWidth) ? windowWidth * .8 : maxQuickWidth,
                    quickViewLeft = (windowWidth - quickViewWidth) / 2,
                    scrollation = $(window).scrollTop();


            // Correggo
            finalTop = 0;
            finalLeft = 0;
            finalWidth = $(window).width() / 2;
            finalHeight = $(window).height();
            quickViewWidth = (windowWidth < maxQuickWidth) ? windowWidth : maxQuickWidth;
            quickViewHeight = (windowWidth < maxQuickWidth) ? windowWidth : maxQuickWidth;
            quickViewLeft = (windowWidth - quickViewWidth) / 2;

            if (animationType == 'open') {
                //hide the image in the gallery
                parentListItem.addClass('empty-box');
                emptedImg = parentListItem.find('img');
                //place the quick view over the image gallery and give it the dimension of the gallery image
                quickView_Instance.css({
                    "top": topSelected,
                    "left": leftSelected,
                    "width": widthSelected,
                    //"height": heightSelected,
                }).velocity({
                    //animate the quick view: animate its width and center it in the viewport
                    //during this animation, only the slider image is visible
                    'top': finalTop + 'px',
                    'left': finalLeft + 'px',
                    //'height': finalHeight+'px',
                    'width': finalWidth + 'px',
                }, 700, 'easeInOutExpo', function () {
                    //animate the quick view: animate its width to the final value
                    quickView_Instance.addClass('animate-width').velocity({
                        'left': quickViewLeft + 'px',
                        'width': quickViewWidth + 'px',
                    }, 500, 'easeInOutExpo', function () {
                        //show quick view content
                        quickView_Instance.addClass('add-content');
                        $('html,body').addClass('no-scroll');
                    });
                }).addClass('is-visible');
            } else {
                //close the quick view reverting the animation
                //alert('inverse '+finalTop+' '+finalLeft+' '+finalWidth);
                quickView_Instance.removeClass('add-content').velocity({
                    'top': finalTop + 'px',
                    'left': finalLeft + 'px',
                    'width': finalWidth + 'px',
                    //'overflow-y': 'hidden',
                    //'scrollTop': 0
                }, 400, 'easeInOutExpo', function () {
                    $('body').removeClass('overlay-layer');
                    $('html,body').removeClass('no-scroll');
                    quickView_Instance.removeClass('animate-width').velocity({
                        "top": topSelected,
                        "left": leftSelected,
                        "width": widthSelected,
                    }, 400, 'easeInOutExpo', function () {
                        quickView_Instance.removeClass('is-visible');
                        parentListItem.removeClass('empty-box');

                    });
                });
                /*$(window).velocity("scroll", { 
                 container: $(".dce-post-item-19"),
                 duration: 800,
                 //delay: 500
                 });*/
            }
        }
        function closeNoAnimation(image, finalWidth, maxQuickWidth) {
            //alert(image.attr('src'));
            var parentListItem = image.parent('.cd-item'),
                    topSelected = image.offset().top - $(window).scrollTop(),
                    leftSelected = image.offset().left,
                    widthSelected = image.width();

            $('body').removeClass('overlay-layer');
            parentListItem.removeClass('empty-box');
            quickView_Instance.velocity("stop").removeClass('add-content animate-width is-visible').css({
                "top": topSelected,
                "left": leftSelected,
                "width": widthSelected,
            });
        }
    };

    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/dyncontel-dualView.default', WidgetElements_DualViewHandler);
    });
})(jQuery);