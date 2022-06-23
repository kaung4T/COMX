(function ($) {
    var WidgetElements_SwiperHandler = function ($scope, $) {
        console.log(' WidgetElements_SwiperHandler');
        alert('Swiper');

        var elementSettings = get_Dyncontel_ElementSettings($scope);

        var elementSwiper = $scope.find('.swiper-container')[0];
        //alert(elementSwiper);
        var oggg = {
            //swipeHandler: '.swiper-container',
            // Optional parameters
            //direction: 'horizontal',//'vertical',
            //speed: 800,
            loop: true,

            // If we need pagination
            pagination: '.swiper-pagination',

            // Navigation arrows
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',

            // And if we need scrollbar
            //scrollbar: '.swiper-scrollbar',

            effect: 'slide', //"slide", "fade", "cube", "coverflow" or "flip"

            // ********* Keyboard / Mousewheel ********
            keyboardControl: true,
            mousewheelControl: true,
            //
            // mousewheelForceToAxis
            // mousewheelReleaseOnEdges
            // mousewheelInvert
            // mousewheelSensitivity
            // mousewheelEventsTarged
        };



        var interleaveOffset = -.5;

        var interleaveEffect = {

            onProgress: function (swiper, progress) {

                for (var i = 0; i < swiper.slides.length; i++) {

                    var slide = swiper.slides[i];
                    var translate, innerTranslate;
                    progress = slide.progress;

                    if (progress > 0) {
                        translate = progress * swiper.width;
                        innerTranslate = translate * interleaveOffset;
                    } else {
                        innerTranslate = Math.abs(progress * swiper.width) * interleaveOffset;
                        translate = 0;
                    }
                    if (i == 0) {
                        console.log(progress + ' <- progress');
                    }
                    var transizione;
                    if (elementSettings.direction == 'horizontal') {
                        transizione = 'translate3d(' + translate + 'px,0,0)';
                    } else if (elementSettings.direction == 'vertical') {
                        transizione = 'translate3d(0,' + translate + 'px,0)';
                    }
                    $(slide).css({
                        transform: transizione,
                    });
                    // ----------
                    var transizioneInterna;
                    if (elementSettings.direction == 'horizontal') {
                        transizioneInterna = 'translate3d(' + innerTranslate + 'px,0,0)';
                    } else if (elementSettings.direction == 'vertical') {
                        transizioneInterna = 'translate3d(0,' + innerTranslate + 'px,0)';
                    }
                    $(slide).find('.slide-inner').css({
                        transform: transizioneInterna
                    });
                }
            },

            onTouchStart: function (swiper) {
                for (var i = 0; i < swiper.slides.length; i++) {
                    $(swiper.slides[i]).css({transition: ''});
                }
            },

            onSetTransition: function (swiper, speed) {
                for (var i = 0; i < swiper.slides.length; i++) {
                    $(swiper.slides[i])
                            .find('.slide-inner')
                            .andSelf()
                            .css({transition: speed + 'ms'});
                }
            }
        };
        var swpEffect = 'slide';
        if (elementSettings.effects != 'custom1') {
            swpEffect = elementSettings.effects || 'slide';
        }
        //alert(swpEffect+' '+elementSettings.direction);
        //alert('--- '+elementSettings.slidesPerView);
        var swiperOptions = {
            //------------------- Base Settings
            direction: String(elementSettings.direction) || 'horizontal',
            speed: Number(elementSettings.speed) || 300,
            //setWrapperSize: Boolean( elementSettings.setWrapperSize ),
            //virtualTranslate:  Boolean( elementSettings.virtualTranslate ),
            autoHeight: Boolean( elementSettings.autoHeight ),
            roundLengths: Boolean( elementSettings.roundLengths ),
            nested: Boolean( elementSettings.nested ),
            grabCursor: Boolean( elementSettings.grabCursor ),
            //------------------- Autoplay
            autoplay: Boolean( elementSettings.autoplay ),
            autoplayStopOnLast: Boolean( elementSettings.autoplayStopOnLast ),
            autoplayDisableOnInteraction: Boolean( elementSettings.autoplayDisableOnInteraction ),
            //------------------- Progress
            watchSlidesProgress: Boolean( elementSettings.watchSlidesProgress ),
            watchSlidesVisibility: Boolean( elementSettings.watchSlidesVisibility ),
            //------------------- Freemode
            freeMode: Boolean( elementSettings.freeMode ),
            freeModeMomentum: Boolean( elementSettings.freeModeMomentum ),
            freeModeMomentumRatio: Number(elementSettings.freeModeMomentumRatio) || 1,
            freeModeMomentumVelocityRatio: Number(elementSettings.freeModeMomentumVelocityRatio) || 1,
            freeModeMomentumBounce: Boolean( elementSettings.freeModeMomentumBounce ),
            freeModeMomentumBounceRatio: Number(elementSettings.speed) || 1,
            freeModeMinimumVelocity: Number(elementSettings.speed) || 0.02,
            freeModeSticky: Boolean( elementSettings.freeModeSticky ),
            //------------------- Effects
            effect: swpEffect,
            coverflow: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: true
            },
            //------------------- Grid Swiper
            centeredSlides: Boolean( elementSettings.centeredSlides ),

            spaceBetween: Number(elementSettings.spaceBetween) || 0,
            slidesPerView: Number(elementSettings.slidesPerView) || 'auto',
            slidesPerGroup: Number(elementSettings.slidesPerGroup) || 1,
            //------------------- Responsive Params
            breakpoints: {
                // Mobile
                480: {
                    slidesPerView: Number(elementSettings.slidesPerView_mobile) || Number(elementSettings.slidesPerView) || 'auto',
                    slidesPerGroup: Number(elementSettings.slidesPerGroup_mobile) || Number(elementSettings.slidesPerGroup) || 1,
                    spaceBetween: Number(elementSettings.spaceBetween_mobile) || Number(elementSettings.spaceBetween) || 0,
                },
                // Tablet
                992: {
                    slidesPerView: Number(elementSettings.slidesPerView_tablet) || Number(elementSettings.slidesPerView) || 'auto',
                    slidesPerGroup: Number(elementSettings.slidesPerGroup_tablet) || Number(elementSettings.slidesPerGroup) || 1,
                    spaceBetween: Number(elementSettings.spaceBetween_tablet) || Number(elementSettings.spaceBetween) || 0,
                }
            },

            //------------------- Parallax

            //------------------- Touches, Touch
            //------------------- Swiping / No
            //------------------- Navigation
            //------------------- Keyboard / Mousewheel
            keyboardControl: Boolean( elementSettings.keyboardControl ),
            mousewheelControl: Boolean( elementSettings.mousewheelControl ),
            //------------------- Hash/History
            //------------------- Images
            //------------------- Loop
            loop: Boolean( elementSettings.loop ),
            //------------------- Zoom


            //------------------- Controls
            // If we need pagination
            pagination: '.swiper-pagination',
            //paginationType: 'fraction',//"bullets", "fraction", "progress" 
            // Navigation arrows
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',

            // And if we need scrollbar
            //scrollbar: '.swiper-scrollbar',

        };
        //alert(swiperOptions.spaceBetween);
        //console.log(swOpt); 

        if (elementSettings.effects == 'custom1') {
            //alert('custom1');
            swiperOptions = $.extend(swiperOptions, interleaveEffect);
        }
        //var mySwiper = new Swiper (elementSwiper, oggg);
        var dce_swiper = new Swiper(elementSwiper, swiperOptions);

    };

    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/dyncontel-swiper.default', WidgetElements_SwiperHandler);
    });
})(jQuery);
