var isEditMode = false;

( function( $ ) {


    var WooTabsHandler = function( $scope, $ ) {

        var defaultActiveTab = $scope.find( '.ae-woo-tabs' ).data( 'active-tab' ),
            $tabsTitles = $scope.find( '.ae-woo-tab-title' ),
            $tabs = $scope.find( '.ae-woo-tab-content' ),
            $active,
            $content;

        if ( ! defaultActiveTab ) {
            defaultActiveTab = 1;
        }

        var activateTab = function( tabIndex ) {
            if ( $active ) {
                $active.removeClass( 'active' );
                $content.hide();
            }

            $active = $tabsTitles.filter( '[data-tab="' + tabIndex + '"]' );
            $active.addClass( 'active' );

            $content = $tabs.filter( '[data-tab="' + tabIndex + '"]' );
            $content.show();
        };

        activateTab( defaultActiveTab );
        $tabsTitles.on( 'click', function() {
            activateTab( this.dataset.tab );
        });

        var $reviewtaglink = window.location.hash.substring(1);
        if($reviewtaglink == 'reviews'){
            var $reviewtab = $tabsTitles.filter('[data-hashtag="reviews"]' );
            activateTab( $reviewtab.data('tab') );
        }


    };

    var WooRatingHandler = function( $scope, $ ) {
        var $ratinglink = $scope.find('.woocommerce-review-link');
        var $tabsTitles = $( '.ae-woo-tab-title' );
        var $tabs = $( '.ae-woo-tab-content' );
        $ratinglink.on('click', function () {
            if($tabsTitles.length) {
                $tabsTitles.removeClass('active');
                $tabs.hide();
                $tabsTitles.filter('[data-hashtag="reviews"]').addClass('active');
                $tabs.filter('[data-hashtag="reviews"]').show();
            }
        })

    };

    var WooGalleryHandler = function ( $scope, $ ) {

        if($scope.parents('.elementor-editor-active').length){
            jQuery( '.woocommerce-product-gallery' ).each( function() {
                jQuery( this ).wc_product_gallery();
                wc_single_product_params.zoom_enabled = 0;
            } );
        }

        outer_wrapper =  $scope.find('.ae-swiper-outer-wrapper');

        if(outer_wrapper.length) {

            wid = $scope.data('id');
            wclass = '.elementor-element-' + wid;

            var loop = outer_wrapper.data('loop');
            if (loop == 'yes') {
                loop = true;
            }
            else {
                loop = false;
            }
            var navigation = outer_wrapper.data('navigation');

            adata = {
                wrapperClass: 'ae-swiper-wrapper',
                slideClass: 'ae-swiper-slide',
                observer: true,
                loop: loop,
                pagination: {
                    el: '.ae-swiper-pagination',
                    type: 'bullets',
                    clickable: true,
                }
            };

            if (navigation != 'no') {
                adata['navigation'] = {
                    nextEl: '.ae-swiper-button-next',
                    prevEl: '.ae-swiper-button-prev',
                }
            }

            adata['init'] = false;

            var mswiper = new Swiper('.elementor-element-' + wid + ' .ae-swiper-container', adata);

            if($('.elementor-element-' + wid + ' .ae-swiper-container').length <= 1) {
                mswiper.on('slideChangeTransitionStart', function () {

                    // set dynamic background
                    mswiper.$wrapperEl.find('.ae-featured-bg-yes').each(function () {
                        if ($(this).css('background-image') == 'none') {
                            img = jQuery(this).attr('data-ae-bg');
                            $(this).css('background-image', 'url(' + img + ')');
                        }
                    });

                    // reveal animated widgets
                    mswiper.$wrapperEl.find('.swiper-slide-active').find('.elementor-invisible').each(function () {
                        // get settings
                        settings = jQuery(this).data('settings');
                        animation = settings.animation || settings._animation;

                        $(this).removeClass('elementor-invisible').removeClass(animation).addClass(animation);

                    });

                });
                mswiper.init();
            }else {

                $('.elementor-element-' + wid + ' .ae-swiper-container').each(function (i) {

                    mswiper[i].on('slideChangeTransitionStart', function () {

                        // set dynamic background
                        mswiper[i].$wrapperEl.find('.ae-featured-bg-yes').each(function () {
                            if ($(this).css('background-image') == 'none') {
                                img = jQuery(this).attr('data-ae-bg');
                                $(this).css('background-image', 'url(' + img + ')');
                            }
                        });

                        // reveal animated widgets
                        mswiper[i].$wrapperEl.find('.swiper-slide-active').find('.elementor-invisible').each(function () {
                            // get settings
                            settings = jQuery(this).data('settings');
                            animation = settings.animation || settings._animation;

                            $(this).removeClass('elementor-invisible').removeClass(animation).addClass(animation);

                        });

                    });
                    mswiper[i].init();
                });

            }

            $('.elementor-element-' + wid + ' .ae-swiper-container').css('visibility', 'visible');
        }

    };

    var CustomFieldHandler = function ( $scope , $ ) {
        if(isEditMode){
            return;
        }

        if($scope.find('.ae-cf-wrapper').hasClass('hide')){
            $scope.find('.ae-cf-wrapper').closest('.elementor-widget-ae-custom-field').hide();
        }

    };
    
    var PortfolioHandler = function ( $scope , $ ) {
        if($scope.find('.ae-post-widget-wrapper').hasClass('ae-masonry-yes')){
            var grid = $scope.find('.ae-post-list-wrapper');
            var $grid_obj = grid.masonry();

            $grid_obj.imagesLoaded().progress(function(){
                $grid_obj.masonry('layout');
            });
        }

        $scope.find('article.ae-post-list-item').css('opacity', '1');
    };
    
    var ACFGallerySlider = function ( $scope , $ ) {
        outer_wrapper =  $scope.find('.ae-swiper-outer-wrapper');

        wid = $scope.data('id');
        wclass = '.elementor-element-' + wid;
        //var direction = outer_wrapper.data('direction');
        var speed = outer_wrapper.data('speed');
        var autoplay = outer_wrapper.data('autoplay');
        var duration = outer_wrapper.data('duration');
        var effect = outer_wrapper.data('effect');
        var space = outer_wrapper.data('space');

        var loop = outer_wrapper.data('loop');
        if(loop == 'yes'){
            loop = true;
        }
        else{
            loop = false;
        }
        var zoom = outer_wrapper.data('zoom');
        var slides_per_view = outer_wrapper.data('slides-per-view');
        var ptype = outer_wrapper.data('ptype');
        var navigation = outer_wrapper.data('navigation');
        var clickable = outer_wrapper.data('clickable');

        var pclickable = true;
        if (clickable == 'yes') {
            pclickable = true;
        }else{
            pclickable = false;
        }

        var keyboard = outer_wrapper.data('keyboard');
        var scrollbar = outer_wrapper.data('scrollbar');
        adata = {
            direction:'horizontal',
            speed: speed,
            autoplay: duration,
            effect: effect,
            spaceBetween: space,
            loop: loop,
            zoom: zoom,
            keyboard: keyboard,
            autoHeight:false,
            height:200,
            autoplayDisableOnInteraction:false,
            wrapperClass: 'ae-swiper-wrapper',
            slideClass: 'ae-swiper-slide'
        };

        if (navigation == 'yes') {
            adata['navigation'] = {
                nextEl: '.ae-swiper-button-next',
                prevEl: '.ae-swiper-button-prev',
            }
        }

        if (ptype != '') {

            adata['pagination'] = {
                el: '.ae-swiper-pagination',
                type: ptype,
                clickable: pclickable
            }
        }
        if (scrollbar == 'yes') {

            adata['scrollbar'] = {
                el: '.ae-swiper-scrollbar',
                hide: true
            };
        }
        if(loop == false) {
            adata['autoplayStopOnLast'] = true;
        }

        var mswiper = new Swiper( '.elementor-element-' + wid  + ' .ae-swiper-container', adata);
    };

    var ACFGalleryCarousel = function ( $scope , $ ) {
        outer_wrapper =  $scope.find('.ae-swiper-outer-wrapper');

        wid = $scope.data('id');
        wclass = '.elementor-element-' + wid;

        var direction = 'horizontal';
        var speed = outer_wrapper.data('speed');


        if (outer_wrapper.data('autoplay') == 'yes'){

            var duration = outer_wrapper.data('duration');
            var autoplay = {
                delay: duration
            }
        }else{
            var autoplay = false;
        }
        var effect = outer_wrapper.data('effect');
        var space = outer_wrapper.data('space');

        var loop = outer_wrapper.data('loop');
        if (loop == 'yes') {
            loop = true;
        }
        else {
            loop = false;
        }
        var auto_height = outer_wrapper.data('auto-height');
        var zoom = outer_wrapper.data('zoom');
        var slides_per_view = outer_wrapper.data('slides-per-view');
        var slides_per_group = outer_wrapper.data('slides-per-group');

        var ptype = outer_wrapper.data('ptype');
        var navigation = outer_wrapper.data('navigation');
        var clickable = outer_wrapper.data('clickable');

        var pclickable = true;
        if (clickable == 'yes') {
            pclickable = true;
        }else{
            pclickable = false;
        }

        if(outer_wrapper.data('keyboard') == 'yes'){
            var keyboard = {
                enabled: true,
                onlyInViewport: true,
            }
        }else{
            var keyboard = false;
        }

        var scrollbar = outer_wrapper.data('scrollbar');


        adata = {
            direction: direction,
            speed: speed,
            autoplay: autoplay,
            effect: effect,
            spaceBetween: space.desktop,
            loop: loop,
            autoHeight: auto_height,
            zoom: zoom,
            slidesPerView: slides_per_view.desktop,
            slidesPerGroup: slides_per_group.desktop,
            keyboard: keyboard,
            wrapperClass: 'ae-swiper-wrapper',
            slideClass: 'ae-swiper-slide',
            observer: true,
            breakpoints: {
                1024: {
                    spaceBetween: space.tablet,
                    slidesPerView: slides_per_view.tablet,
                    slidesPerGroup: slides_per_group.tablet
                },
                767: {
                    spaceBetween: space.mobile,
                    slidesPerView: slides_per_view.mobile,
                    slidesPerGroup: slides_per_group.mobile
                }
            }
        };

        if (navigation == 'yes') {
            adata['navigation'] = {
                nextEl: '.ae-swiper-button-next',
                prevEl: '.ae-swiper-button-prev',
            }
        }

        if (ptype != '') {

            adata['pagination'] = {
                el: '.ae-swiper-pagination',
                type: ptype,
                clickable: pclickable
            }
        }
        if (scrollbar == 'yes') {

            adata['scrollbar'] = {
                el: '.ae-swiper-scrollbar',
                hide: true
            };
        }

        if(loop == false) {
            adata['autoplayStopOnLast'] = true;
        }
        //console.log(adata);

        window.mswiper = new Swiper( '.elementor-element-' + wid  + ' .ae-swiper-container', adata);
        $('.elementor-element-' + wid  + ' .ae-swiper-container').css('visibility','visible');
    };
    
    var ACFGalleryGrid = function ( $scope , $ ) {

        if($scope.find('.ae-grid-wrapper').hasClass('ae-masonry-yes')){
            var grid = $scope.find('.ae-grid');
            var $grid_obj = grid.masonry({
            });

            $grid_obj.imagesLoaded().progress(function(){
                $grid_obj.masonry('layout');
            });
        }

        $scope.find('.ae-grid-item-inner').hover(function(){
            $(this).find('.ae-grid-overlay').addClass('animated');
        });
    };

    var ACFRepeaterHandler = function ( $scope, $ ) {

        if($scope.find('.ae-acf-repeater-widget-wrapper').hasClass('ae-masonry-yes')){
            var grid = $scope.find('.ae-acf-repeater-wrapper');
            var $grid_obj = grid.masonry();

            $grid_obj.imagesLoaded().progress(function(){
                $grid_obj.masonry('layout');
            });
        }

        // Carousel
        if($scope.find('.ae-acf-repeater-widget-wrapper').hasClass('ae-carousel-yes')) {

            outer_wrapper = $scope.find('.ae-swiper-outer-wrapper');
            if(outer_wrapper.length) {
                if ($scope.find('.ae-acf-repeater-item').length) {

                    wid = $scope.data('id');
                    wclass = '.elementor-element-' + wid;
                    var direction = outer_wrapper.data('direction');
                    if (direction == 'vertical') {
                        var aeswiperslide = outer_wrapper.find('.ae-swiper-slide').height();
                    }

                    //var direction = 'vertical';
                    var speed = outer_wrapper.data('speed');
                    if (outer_wrapper.data('autoplay') == 'yes') {

                        var duration = outer_wrapper.data('duration');
                        var autoplay = {
                            delay: duration
                        }
                    } else {
                        var autoplay = false;
                    }
                    var effect = outer_wrapper.data('effect');
                    var space = outer_wrapper.data('space');

                    var loop = outer_wrapper.data('loop');
                    if (loop == 'yes') {
                        loop = true;
                    }
                    else {
                        loop = false;
                    }
                    var auto_height = outer_wrapper.data('auto-height');
                    var zoom = outer_wrapper.data('zoom');
                    var slides_per_view = outer_wrapper.data('slides-per-view');
                    var slides_per_group = outer_wrapper.data('slides-per-group');

                    var ptype = outer_wrapper.data('ptype');
                    var navigation = outer_wrapper.data('navigation');
                    var clickable = outer_wrapper.data('clickable');

                    var pclickable = true;
                    if (clickable == 'yes') {
                        pclickable = true;
                    } else {
                        pclickable = false;
                    }

                    if (outer_wrapper.data('keyboard') == 'yes') {
                        var keyboard = {
                            enabled: true,
                            onlyInViewport: true,
                        }
                    } else {
                        var keyboard = false;
                    }

                    var scrollbar = outer_wrapper.data('scrollbar');

                    adata = {
                        direction: direction,
                        speed: speed,
                        autoHeight: auto_height,
                        autoplay: autoplay,
                        effect: effect,
                        spaceBetween: space.desktop,
                        loop: loop,
                        zoom: zoom,
                        slidesPerView: slides_per_view.desktop,
                        slidesPerGroup: slides_per_group.desktop,
                        keyboard: keyboard,
                        wrapperClass: 'ae-swiper-wrapper',
                        slideClass: 'ae-swiper-slide',
                        observer: true,
                        breakpoints: {
                            1024: {
                                spaceBetween: space.tablet,
                                slidesPerView: slides_per_view.tablet,
                                slidesPerGroup: slides_per_group.tablet,
                            },
                            767: {
                                spaceBetween: space.mobile,
                                slidesPerView: slides_per_view.mobile,
                                slidesPerGroup: slides_per_group.tablet,
                            }
                        },
                        on: {
                            slideChange: function () {
                                var wrapper = $scope.find('.ae-acf-repeater-wrapper .ae-acf-repeater-item .ae-link-yes');
                                if ( wrapper.data( 'ae-url' ) && wrapper.hasClass('ae-link-yes') ){

                                    wrapper.on('click', function (e) {
                                        console.log('test');
                                        if ( wrapper.data( 'ae-url' ) && wrapper.hasClass('ae-new-window-yes') ) {
                                            window.open(wrapper.data('ae-url'));
                                        }else{
                                            location.href = wrapper.data('ae-url');
                                        }
                                    })
                                }
                            },
                        }
                    };


                    if (navigation == 'yes') {
                        adata['navigation'] = {
                            nextEl: '.ae-swiper-button-next',
                            prevEl: '.ae-swiper-button-prev',
                        }
                    }

                    if (ptype != '') {

                        adata['pagination'] = {
                            el: '.ae-swiper-pagination',
                            type: ptype,
                            clickable: pclickable
                        }
                    }
                    if (scrollbar == 'yes') {

                        adata['scrollbar'] = {
                            el: '.ae-swiper-scrollbar',
                            hide: true
                        };
                    }

                    if (loop == false) {
                        adata['autoplayStopOnLast'] = true;
                    }

                    adata['init'] = false;

                    var mswiper = new Swiper('.elementor-element-' + wid + ' .ae-swiper-container', adata);
                    if ($('.elementor-element-' + wid + ' .ae-swiper-container').length <= 1) {
                        mswiper.on('slideChangeTransitionStart', function () {

                            // set dynamic background
                            mswiper.$wrapperEl.find('.ae-featured-bg-yes').each(function () {
                                if ($(this).css('background-image') == 'none') {
                                    img = jQuery(this).attr('data-ae-bg');
                                    $(this).css('background-image', 'url(' + img + ')');
                                }
                            });

                            // reveal animated widgets
                            mswiper.$wrapperEl.find('.swiper-slide-active').find('.elementor-invisible').each(function () {
                                // get settings
                                settings = jQuery(this).data('settings');
                                animation = settings.animation || settings._animation;

                                $(this).removeClass('elementor-invisible').removeClass(animation).addClass(animation);

                            });

                        });
                        mswiper.init();
                    } else {

                        $('.elementor-element-' + wid + ' .ae-swiper-container').each(function (i) {

                            mswiper[i].on('slideChangeTransitionStart', function () {

                                // set dynamic background
                                mswiper[i].$wrapperEl.find('.ae-featured-bg-yes').each(function () {
                                    if ($(this).css('background-image') == 'none') {
                                        img = jQuery(this).attr('data-ae-bg');
                                        $(this).css('background-image', 'url(' + img + ')');
                                    }
                                });

                                // reveal animated widgets
                                mswiper[i].$wrapperEl.find('.swiper-slide-active').find('.elementor-invisible').each(function () {
                                    // get settings
                                    settings = jQuery(this).data('settings');
                                    animation = settings.animation || settings._animation;

                                    $(this).removeClass('elementor-invisible').removeClass(animation).addClass(animation);

                                });

                            });
                            mswiper[i].init();
                        });
                    }


                    $('.elementor-element-' + wid + ' .ae-swiper-container').css('visibility', 'visible');

                    if (direction == 'vertical') {
                        $('.elementor-element-' + wid + ' .ae-swiper-container').css('max-height', aeswiperslide + 'px');
                    }

                }
            }
        }

    };

    var PostBlocksHandler = function ( $scope , $ ) {

        // Set Carousel
        if($scope.find('.ae-post-widget-wrapper').hasClass('ae-masonry-yes')){
            var grid = $scope.find('.ae-post-list-wrapper');
            var $grid_obj = grid.masonry({
                horizontalOrder: true
            });
            $grid_obj.imagesLoaded().progress(function(){
                $grid_obj.masonry('layout');
            });
        }


        // Infinite Scroll
        if ($scope.find('.ae-post-widget-wrapper').hasClass('ae-ias-yes')) {
            if ($scope.find('.ae-pagination-wrapper .page-numbers').length) {
                _ias($scope, $, grid)
            }else{
                $scope.find('.scroller-status').hide();
                $scope.find('.load-more-wrapper').hide();
            }
        }


        // Refresh Dynamic BG
        if(isEditMode){
            $sections = $scope.find('.elementor-section');
            $.each($sections, function(key, $section){
               DynamicBgHandler($($section), $);
            });

            $columns = $scope.find('.elementor-column');
            $.each($columns, function(key, $column){
                DynamicBgHandler($($column), $);
            });
        }


        // Carousel
        if($scope.find('.ae-post-widget-wrapper').hasClass('ae-carousel-yes')) {

            outer_wrapper = $scope.find('.ae-swiper-outer-wrapper');
            if(outer_wrapper.length) {

                wid = $scope.data('id');
                wclass = '.elementor-element-' + wid;
                var direction = outer_wrapper.data('direction');
                if(direction == 'vertical') {
                    var aeswiperslide = outer_wrapper.find('.ae-swiper-slide').height();
                }

                //var direction = 'vertical';
                var speed = outer_wrapper.data('speed');
                if (outer_wrapper.data('autoplay') == 'yes'){

                    var duration = outer_wrapper.data('duration');
                    var autoplay = {
                        delay: duration
                    }
                }else{
                    var autoplay = false;
                }
                var effect = outer_wrapper.data('effect');
                var space = outer_wrapper.data('space');

                var loop = outer_wrapper.data('loop');
                if (loop == 'yes') {
                    loop = true;
                }
                else {
                    loop = false;
                }
                var auto_height = outer_wrapper.data('auto-height');
                var pause_on_hover = outer_wrapper.data('pause-on-hover');
                var zoom = outer_wrapper.data('zoom');
                var slides_per_view = outer_wrapper.data('slides-per-view');
                var slides_per_group = outer_wrapper.data('slides-per-group');

                var ptype = outer_wrapper.data('ptype');
                var navigation = outer_wrapper.data('navigation');
                var clickable = outer_wrapper.data('clickable');

                var pclickable = true;
                if (clickable == 'yes') {
                    pclickable = true;
                }else{
                    pclickable = false;
                }

                if(outer_wrapper.data('keyboard') == 'yes'){
                    var keyboard = {
                        enabled: true,
                        onlyInViewport: true,
                    }
                }else{
                    var keyboard = false;
                }

                var scrollbar = outer_wrapper.data('scrollbar');

                adata = {
                    direction: direction,
                    speed: speed,
                    autoHeight: auto_height,
                    autoplay: autoplay,
                    effect: effect,
                    spaceBetween: space.desktop,
                    loop: loop,
                    zoom: zoom,
                    slidesPerView: slides_per_view.desktop,
                    slidesPerGroup: slides_per_group.desktop,
                    keyboard: keyboard,
                    wrapperClass: 'ae-swiper-wrapper',
                    slideClass: 'ae-swiper-slide',
                    observer: true,
                    breakpoints: {
                        1024: {
                            spaceBetween: space.tablet,
                            slidesPerView: slides_per_view.tablet,
                            slidesPerGroup: slides_per_group.tablet,
                        },
                        767: {
                            spaceBetween: space.mobile,
                            slidesPerView: slides_per_view.mobile,
                            slidesPerGroup: slides_per_group.mobile,
                        }
                    },
                    on: {
                        slideChange: function () {
                            var wrapper = $scope.find('.ae-post-list-wrapper .ae-post-list-item .ae-link-yes');
                            if ( wrapper.data( 'ae-url' ) && wrapper.hasClass('ae-link-yes') ){

                                wrapper.on('click', function (e) {
                                    console.log('test');
                                    if ( wrapper.data( 'ae-url' ) && wrapper.hasClass('ae-new-window-yes') ) {
                                        window.open(wrapper.data('ae-url'));
                                    }else{
                                        location.href = wrapper.data('ae-url');
                                    }
                                })
                            }
                        },
                    }
                };



                if (navigation == 'yes') {
                    adata['navigation'] = {
                        nextEl: '.ae-swiper-button-next',
                        prevEl: '.ae-swiper-button-prev',
                    }
                }

                if (ptype != '') {

                    adata['pagination'] = {
                        el: '.ae-swiper-pagination',
                        type: ptype,
                        clickable: pclickable
                    }
                }
                if (scrollbar == 'yes') {

                    adata['scrollbar'] = {
                        el: '.ae-swiper-scrollbar',
                        hide: true
                    };
                }

                if (loop == false) {
                    adata['autoplayStopOnLast'] = true;
                }

                adata['init'] = false;

                var mswiper = new Swiper('.elementor-element-' + wid + ' .ae-swiper-container', adata);

                mswiper.on('slideChangeTransitionStart', function () {

                    // set dynamic background
                    mswiper.$wrapperEl.find('.ae-featured-bg-yes').each(function(){
                        if($(this).css('background-image') == 'none'){
                            img = jQuery(this).attr('data-ae-bg');
                            $(this).css('background-image','url(' + img + ')');
                        }
                    });

                    // reveal animated widgets
                    mswiper.$wrapperEl.find('.swiper-slide-active').find('.elementor-invisible').each(function(){
                        // get settings
                        settings = jQuery(this).data('settings');
                        animation = settings.animation || settings._animation;

                        $(this).removeClass('elementor-invisible').removeClass(animation).addClass(animation);

                    });

                });

                mswiper.init();

                $('.elementor-element-' + wid + ' .ae-swiper-container').css('visibility', 'visible');

                if(direction == 'vertical'){
                    $('.elementor-element-' + wid + ' .ae-swiper-container').css('max-height', aeswiperslide + 'px' );
                }

                if(pause_on_hover) {

                    $('.elementor-element-' + wid + ' .ae-swiper-container').hover(function () {
                        mswiper.autoplay.stop();
                    }, function () {
                        mswiper.autoplay.start();
                    });
                }

            }
        }
        
        
    };
    
    var WooProductsCarousel = function ( $scope , $ ) {

        outer_wrapper =  $scope.find('.ae-swiper-outer-wrapper');

        wid = $scope.data('id');
        wclass = '.elementor-element-' + wid;
        var direction = outer_wrapper.data('direction');
        var speed = outer_wrapper.data('speed');
        var autoplay = outer_wrapper.data('autoplay');
        var duration = outer_wrapper.data('duration');
        var effect = outer_wrapper.data('effect');
        var space = outer_wrapper.data('space');

        var loop = outer_wrapper.data('loop');
        if(loop == 'yes'){
            loop = true;
        }
        else{
            loop = false;
        }
        var zoom = outer_wrapper.data('zoom');
        var slides_per_view = outer_wrapper.data('slides-per-view');
        var ptype = outer_wrapper.data('ptype');
        var navigation = outer_wrapper.data('navigation');
        var clickable = outer_wrapper.data('clickable');

        var pclickable = true;
        if (clickable == 'yes') {
            pclickable = true;
        }else{
            pclickable = false;
        }

        var keyboard = outer_wrapper.data('keyboard');
        var scrollbar = outer_wrapper.data('scrollbar');
        adata = {
            direction: direction,
            speed: speed,
            autoplay: duration,
            effect: effect,
            spaceBetween: space,
            loop: loop,
            zoom: zoom,
            slidesPerView: slides_per_view,
            keyboard: keyboard,
            wrapperClass: 'ae-swiper-wrapper',
            slideClass: 'ae-swiper-slide',
            onInit: function(swiper){

            }
        };

        if (navigation == 'yes') {
            adata['navigation'] = {
                nextEl: '.ae-swiper-button-next',
                prevEl: '.ae-swiper-button-prev',
            }
        }

        if (ptype != '') {

            adata['pagination'] = {
                el: '.ae-swiper-pagination',
                type: ptype,
                clickable: pclickable
            }
        }
        if (scrollbar == 'yes') {

            adata['scrollbar'] = {
                el: '.ae-swiper-scrollbar',
                hide: true
            };
        }

        if(loop == false) {
            adata['autoplayStopOnLast'] = true;
        }


        window.mswiper = new Swiper( '.elementor-element-' + wid  + ' .ae-swiper-container', adata);
        $('.elementor-element-' + wid  + ' .ae-swiper-container').css('visibility','visible');
    };

    var WooProductsGrid = function ( $scope , $ ) {

        if($scope.hasClass('ae-masonry-yes')){
            var grid = $scope.find('.ae-grid');
            var $grid_obj = grid.masonry({

            });

            $grid_obj.imagesLoaded().progress(function(){
                $grid_obj.masonry('layout');
            });

            $(window).resize(function(){
                // Todo:: Overlap on render mode
                //$grid_obj.masonry('layout');
            });
        }

        $scope.find('.ae-grid-item-inner').hover(function(){
            $(this).find('.ae-grid-overlay').addClass('animated');
        });
    };
    
    var DynamicBgHandler = function ( $scope , $ ) {
        if ( $scope.data( 'ae-bg' ) ){
            $scope.css('background-image','url(' + $scope.data( 'ae-bg' ) + ')');
        }

        BgSliderHandler( $scope , $);

        if(isEditMode){
            return;
        }
        if ( $scope.data( 'ae-url' ) && $scope.hasClass('ae-link-yes') ){
            $scope.on('click', function (e) {

                if ( $scope.data( 'ae-url' ) && $scope.hasClass('ae-new-window-yes') ) {
                    window.open($scope.data('ae-url'));
                }else{
                    location.href = $scope.data('ae-url');
                }
            })
        }
    };

    var ContentUnfold = function ( $scope, $ ) {

        if($scope.find('.ae-element-post-content').hasClass('ae-post-content-unfold-yes')) {
            var postcontent = $scope.find('.ae-element-post-content');
            var postcontentinner = $scope.find('.ae-element-post-content-inner');
            var postcontentunfold = postcontent.find('.ae-post-content-unfold');
            var postcontentunfoldlink = postcontentunfold.find('.ae-post-content-unfold-link');
            var totalHeight = 0;
            totalHeight = postcontentinner.outerHeight();
            if(totalHeight){
                totalHeight += postcontentunfold.outerHeight();
            }
            if((postcontentinner.outerHeight() <= postcontentunfold.data('unfold-max-height')) && postcontentunfold.data('auto-hide-unfold') == 'yes' ){
                postcontentunfold.css({ 'display': 'none'});
            }else{
                postcontentunfoldlink.on( 'click', function() {
                    if(postcontentunfold.hasClass('fold')){
                        postcontent.css({ 'height': postcontent.outerHeight(), 'max-height': 9999 }).animate({ 'height': totalHeight }, {'duration': postcontentunfold.data('animation-speed')});
                        postcontentunfold.toggleClass('fold');
                        postcontentunfoldlink.html(postcontentunfold.data('fold-text'));
                    }else{
                        postcontent.css({ 'max-height': totalHeight }).animate({ 'max-height' : postcontentunfold.data('unfold-max-height') }, {'duration': postcontentunfold.data('animation-speed')});
                        postcontentunfold.toggleClass('fold');
                        postcontentunfoldlink.html(postcontentunfold.data('unfold-text'))
                    }

                });
            }
        }

    };

    var FoldToUnfold = function ($scope, $) {

        if($scope.find('.ae-acf-wrapper').hasClass('ae-acf-unfold-yes')) {
            var acfcontent = $scope.find('.ae-acf-wrapper');
            var acfcontentinner = $scope.find('.ae-acf-content-wrapper');
            var acfcontentunfold = acfcontent.find('.ae-acf-unfold');
            var acfcontentunfoldlink = acfcontentunfold.find('.ae-acf-unfold-link');
            var acfcontentunfoldlinktext = acfcontentunfold.find('.ae-acf-unfold-button-text');
            var acfcontentunfoldlinkicon = acfcontentunfold.find('.ae-acf-unfold-button-icon');
            var totalHeight = 0;
            totalHeight = acfcontentinner.outerHeight();
            if(totalHeight){
                totalHeight += acfcontentunfold.outerHeight();
            }
            if((acfcontentinner.outerHeight() <= acfcontentunfold.data('unfold-max-height')) && acfcontentunfold.data('auto-hide-unfold') == 'yes' ){
                acfcontentunfold.css({ 'display': 'none'});
            }else {
                acfcontentunfoldlink.on('click', function () {
                    if (acfcontentunfold.hasClass('fold')) {
                        acfcontent.css({
                            'height': acfcontent.outerHeight(),
                            'max-height': 9999
                        }).animate({'height': totalHeight}, {'duration': acfcontentunfold.data('animation-speed')});
                        acfcontentunfold.toggleClass('fold');
                        acfcontentunfoldlinktext.html(acfcontentunfold.data('fold-text'));
                        acfcontentunfoldlinkicon.html('<i class="' + acfcontentunfold.data('fold-icon') + '"></i>');
                    } else {
                        acfcontent.css({'max-height': totalHeight}).animate({'max-height': acfcontentunfold.data('unfold-max-height')}, {'duration': acfcontentunfold.data('animation-speed')});
                        acfcontentunfold.toggleClass('fold');
                        acfcontentunfoldlinktext.html(acfcontentunfold.data('unfold-text'))
                        acfcontentunfoldlinkicon.html('<i class="' + acfcontentunfold.data('unfold-icon') + '"></i>');
                    }

                });
            }

        }
    };

    var BgSliderHandler = function ( $scope , $ ) {

        var aepro_slides = [];
        var aepro_slides_json = [];
        var aepro_transition;
        var aepro_animation;
        var aepro_custom_overlay;
        var aepro_overlay;
        var aepro_cover;
        var aepro_delay;
        var aepro_timer;
        var slider_wrapper = $scope.children( '.aepro-section-bs').children('.aepro-section-bs-inner');

        if ( slider_wrapper && slider_wrapper.data('aepro-bg-slider')){

            slider_images = slider_wrapper.data('aepro-bg-slider');
            aepro_transition = slider_wrapper.data('aepro-bg-slider-transition');
            aepro_animation = slider_wrapper.data('aepro-bg-slider-animation');
            aepro_custom_overlay = slider_wrapper.data('aepro-bg-custom-overlay');
            if(aepro_custom_overlay == 'yes'){
                aepro_overlay = aepro_editor.plugin_url + '/includes/assets/lib/vegas/overlays/' + slider_wrapper.data('aepro-bg-slider-overlay');
            }else{
                if(slider_wrapper.data('aepro-bg-slider-overlay')){
                    aepro_overlay = aepro_editor.plugin_url + '/includes/assets/lib/vegas/overlays/' + slider_wrapper.data('aepro-bg-slider-overlay');
                }else{
                    aepro_overlay = aepro_editor.plugin_url + '/includes/assets/lib/vegas/overlays/' + slider_wrapper.data('aepro-bg-slider-overlay');
                }
            }

            aepro_cover = slider_wrapper.data('aepro-bg-slider-cover');
            aepro_delay = slider_wrapper.data('aepro-bs-slider-delay');
            aepro_timer = slider_wrapper.data('aepro-bs-slider-timer');

            if(typeof slider_images != 'undefined'){
                aepro_slides = slider_images.split(",");

                jQuery.each(aepro_slides,function(key,value){
                    var slide = [];
                    slide.src = value;
                    aepro_slides_json.push(slide);
                });

                slider_wrapper.vegas({
                    slides: aepro_slides_json,
                    transition:aepro_transition,
                    animation: aepro_animation,
                    overlay: aepro_overlay,
                    cover: aepro_cover,
                    delay: aepro_delay,
                    timer: aepro_timer,
                    init: function(){
                        if(aepro_custom_overlay == 'yes') {
                            var ob_vegas_overlay = slider_wrapper.children('.vegas-overlay');
                            ob_vegas_overlay.css('background-image', '');
                        }
                    }
                });

            }
        }
    };

    var CFGoogleMap = function ( $scope , $) {

        if($scope.find('.ae-cf-gmap').length) {
            map = new_map($scope.find('.ae-cf-gmap'));


            function new_map($el) {
                var zoom = $scope.find('.ae-cf-gmap').data('zoom');
                var $markers = $el.find('.marker');
                var styles = $scope.find('.ae-cf-gmap').data('styles');

                // vars
                var args = {
                    zoom: zoom,
                    center: new google.maps.LatLng(0, 0),
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    styles: styles
                };

                // create map
                var map = new google.maps.Map($el[0], args);

                // add a markers reference
                map.markers = [];

                // add markers
                $markers.each(function () {
                    add_marker(jQuery(this), map);
                });

                // center map
                center_map(map, zoom);

                // return
                return map;
            }

            function add_marker($marker, map) {
                // var
                var latlng = new google.maps.LatLng($marker.attr('data-lat'), $marker.attr('data-lng'));

                // create marker
                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                });

                // add to array
                map.markers.push(marker);

                // if marker contains HTML, add it to an infoWindow

                if ($marker.html()) {
                    // create info window
                    var infowindow = new google.maps.InfoWindow({
                        content: $marker.html()
                    });

                    // show info window when marker is clicked
                    google.maps.event.addListener(marker, 'click', function () {
                        infowindow.open(map, marker);
                    });
                }
            }

            function center_map(map, zoom) {

                // vars
                var bounds = new google.maps.LatLngBounds();
                // loop through all markers and create bounds
                jQuery.each(map.markers, function (i, marker) {
                    var latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
                    bounds.extend(latlng);
                });

                // only 1 marker?
                if (map.markers.length == 1) {
                    // set center of map
                    map.setCenter(bounds.getCenter());
                    map.setZoom(zoom);
                }
                else {
                    // fit to bounds
                    map.fitBounds(bounds);
                }
            }
        }

    };

    var PostImageHandler = function ($scope , $ ){

        if($scope.find('.ae_thumb_wrapper').hasClass('ae_image_ratio_yes')) {

            var imageParent = $scope.find('.ae-post-image');
            var image = $scope.find('.ae-post-image img');

            var imageParentRatio = imageParent.outerHeight() / imageParent.outerWidth();
            var imageRatio = image.height() / image.width();

            if(imageRatio < imageParentRatio){
                imageParent.addClass( 'ae-post-image-fit' );
            }else{
                imageParent.removeClass( 'ae-post-image-fit' );
            }


        }

    };

    var NavMenuHandler = function ($scope , $ ){
        if($('body').data('elementor-device-mode') == "mobile") {
            $('body').css({ 'overflow-x': 'hidden' });
            $scope.find('#ae-menu-toggle').on( 'click', function(e) {
                e.preventDefault();
                $scope.find('.nav-container').toggleClass('open');
            });
            if($scope.find('.menu-item').hasClass('menu-item-has-children')){
                $scope.find('.menu-item.menu-item-has-children > .ae-nav-menu-toggle').css({ 'display': 'block' });
            }
            $scope.find('.menu-item.menu-item-has-children > .ae-nav-menu-toggle').on( 'click', function() {
                $(this).parent().toggleClass('open');
                $(this).find('.ae-nav-menu-toggle i').addClasses('fa fa-minus');
            });
        }
    };

    var safariResize = function($scope) {
        // Targetting collapsed images in pjax loading box (safari bug)
        var imgLoad = imagesLoaded( $scope.find('.ae-element-post-image > .ae_thumb_wrapper img') );
        imgLoad.on( 'progress', function( instance, image ) {
            if(image.isLoaded && image.img.height == 0){
                var naturalH = image.img.naturalHeight,
                    naturalW = image.img.naturalWidth;
                if( image.img.parentElement.clientWidth < naturalW ){
                    var ratio = naturalH / naturalW;
                    naturalW = image.img.parentElement.clientWidth;
                    naturalH = naturalW * ratio;
                }
                image.img.setAttribute("style","width: "+naturalW+"px; height: "+naturalH+"px; display:none;");
                $(image.img).fadeIn();
            }
        });
    };
    
    var _ias = function ( $scope , $, grid ) {

        var ias = $scope.find('.ae-post-list-wrapper');
        var msnry = '';
        if($scope.find('.ae-post-widget-wrapper').hasClass('ae-masonry-yes')) {
            msnry = grid.data('masonry');
        }
        var $ias_obj = ias.infiniteScroll({
                            path: '.next',
                            append: '.ae-post-list-item',
                            status: '.scroller-status',
                            hideNav: '.ae-pagination-wrapper',
                            outlayer: msnry,
                            button: '.view-more-button',
                            history: $scope.find('.ae-post-widget-wrapper').data('ias-history'),
                        });

        ias.on('append.infiniteScroll', function() {
            $scope.find('.ae-post-list-wrapper').find('.elementor-invisible').each(function(){
                // get settings
                settings = $(this).data('settings');
                animation = settings.animation || settings._animation;
                $(this).removeClass('elementor-invisible').removeClass(animation).addClass(animation);

            });
            $scope.find('.ae-post-list-wrapper').find('.ae-featured-bg-yes').each(function() {
                if ($(this).data('ae-bg')) {
                    $(this).css('background-image', 'url(' + $(this).data('ae-bg') + ')');
                }
            });

            if ( $scope.find('.ae-link-yes').data( 'ae-url' ) ){
                $scope.find('.ae-link-yes').on('click', function (e) {

                    if ( jQuery(this).data( 'ae-url' ) && jQuery(this).hasClass('ae-new-window-yes') ) {
                        window.open(jQuery(this).data('ae-url'));
                    }else{
                        location.href = jQuery(this).data('ae-url');
                    }
                });
            }

            $scope.find('.ae-post-list-wrapper').find('.ae-cf-wrapper').each(function() {
                if ($(this).hasClass('hide')) {
                    $(this).closest('.elementor-widget-ae-custom-field').hide();
                }
            });

            /* EAE Modal Popup Widget compatibility on IAS ajax */

            if($scope.find('.eae-popup-link').length){

                $close_btn = $scope.find('.eae-popup-wrapper').data('close-btn');

                $magnific = $scope.find('.eae-popup-link').eaePopup({
                    type: 'inline',

                    mainClass: 'eae-popup eae-popup-' + $scope.find('.eae-popup-link').data('id') + ' eae-wrap-' + $scope.find('.eae-popup-link').data('ctrl-id'),

                    closeBtnInside: $scope.find('.eae-popup-wrapper').data('close-in-out'),

                    closeMarkup: '<i class="eae-close ' + $close_btn + '"> </i>',
                });

            }
            /* EAE Modal Popup Widget compatibility on IAS ajax */

            if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1)  {
                safariResize($scope);
            } else if (navigator.userAgent.indexOf('iPad') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
                safariResize($scope);
            }

            if (navigator.userAgent.indexOf('Safari') != -1 || navigator.userAgent.indexOf('iPad') != -1 || navigator.userAgent.indexOf('iPhone') != -1 && navigator.userAgent.indexOf('Chrome') == -1){
                var ias_image = $scope.find('.ae-post-list-wrapper').find('.wp-post-image');
                ias_image.each(function (index, ias_image) {
                    ias_image.outerHTML = ias_image.outerHTML;
                });
            }
        });

        /*ias.on( 'load.infiniteScroll', function( event, response ) {
            if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1)  {
                safariResize($scope);
            } else if (navigator.userAgent.indexOf('iPad') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
                safariResize($scope);
            }
        });*/

        if($scope.find('.ae-post-widget-wrapper').hasClass('ae-ias-load-with-button-yes')) {
            var $viewMoreButton = $('.view-more-button');

            // get Infinite Scroll instance
            var infScroll = ias.data('infiniteScroll');

            ias.on('load.infiniteScroll', onPageLoad);
            var loadCount = $scope.find('.ae-post-widget-wrapper').data('load-offset-page') - 1;
            function onPageLoad() {
                if (infScroll.loadCount == loadCount) {
                    // after 2nd page loaded
                    // disable loading on scroll
                    ias.infiniteScroll('option', {
                        loadOnScroll: false,
                    });
                    // show button
                    $viewMoreButton.show();
                    // remove event listener
                    ias.off('load.infiniteScroll', onPageLoad);
                }
            }
        }

        ias.on( 'last.infiniteScroll', function( event, response, path ) {
            // parse JSON
            $scope.find('.load-more-wrapper').hide();
            // do something with JSON...
        });
    };

    var PodsGalleryCarousel = function ( $scope , $ ) {
        outer_wrapper =  $scope.find('.ae-swiper-outer-wrapper');

        wid = $scope.data('id');
        wclass = '.elementor-element-' + wid;

        var direction = 'horizontal';
        var speed = outer_wrapper.data('speed');


        if (outer_wrapper.data('autoplay') == 'yes'){

            var duration = outer_wrapper.data('duration');
            var autoplay = {
                delay: duration
            }
        }else{
            var autoplay = false;
        }
        var effect = outer_wrapper.data('effect');
        var space = outer_wrapper.data('space');

        var loop = outer_wrapper.data('loop');
        if (loop == 'yes') {
            loop = true;
        }
        else {
            loop = false;
        }
        var auto_height = outer_wrapper.data('auto-height');
        var zoom = outer_wrapper.data('zoom');
        var slides_per_view = outer_wrapper.data('slides-per-view');
        var slides_per_group = outer_wrapper.data('slides-per-group');

        var ptype = outer_wrapper.data('ptype');
        var navigation = outer_wrapper.data('navigation');
        var clickable = outer_wrapper.data('clickable');

        var pclickable = true;
        if (clickable == 'yes') {
            pclickable = true;
        }else{
            pclickable = false;
        }

        if(outer_wrapper.data('keyboard') == 'yes'){
            var keyboard = {
                enabled: true,
                onlyInViewport: true,
            }
        }else{
            var keyboard = false;
        }

        var scrollbar = outer_wrapper.data('scrollbar');


        adata = {
            direction: direction,
            speed: speed,
            autoplay: autoplay,
            effect: effect,
            spaceBetween: space.desktop,
            loop: loop,
            autoHeight: auto_height,
            zoom: zoom,
            slidesPerView: slides_per_view.desktop,
            slidesPerGroup: slides_per_group.desktop,
            keyboard: keyboard,
            wrapperClass: 'ae-swiper-wrapper',
            slideClass: 'ae-swiper-slide',
            observer: true,
            breakpoints: {
                1024: {
                    spaceBetween: space.tablet,
                    slidesPerView: slides_per_view.tablet,
                    slidesPerGroup: slides_per_group.tablet
                },
                767: {
                    spaceBetween: space.mobile,
                    slidesPerView: slides_per_view.mobile,
                    slidesPerGroup: slides_per_group.mobile
                }
            }
        };

        if (navigation == 'yes') {
            adata['navigation'] = {
                nextEl: '.ae-swiper-button-next',
                prevEl: '.ae-swiper-button-prev',
            }
        }

        if (ptype != '') {

            adata['pagination'] = {
                el: '.ae-swiper-pagination',
                type: ptype,
                clickable: pclickable
            }
        }
        if (scrollbar == 'yes') {

            adata['scrollbar'] = {
                el: '.ae-swiper-scrollbar',
                hide: true
            };
        }

        if(loop == false) {
            adata['autoplayStopOnLast'] = true;
        }
        //console.log(adata);

        window.mswiper = new Swiper('.elementor-element-' + wid + ' .ae-swiper-container', adata);
        $('.elementor-element-' + wid + ' .ae-swiper-container').css('visibility', 'visible');
    }

    var PodsGalleryGrid = function ( $scope, $ ) {
        if($scope.find('.ae-grid-wrapper').hasClass('ae-masonry-yes')){
            var grid = $scope.find('.ae-grid');
            var $grid_obj = grid.masonry({
            });

            $grid_obj.imagesLoaded().progress(function(){
                $grid_obj.masonry('layout');
            });
        }

        $scope.find('.ae-grid-item-inner').hover(function(){
            $(this).find('.ae-grid-overlay').addClass('animated');
        });
    }

    var PodsGallery = function ( $scope , $ ) {
        if($scope.hasClass('ae-pods-gallery-carousel')) {
            PodsGalleryCarousel($scope, $);
        }

        if($scope.hasClass('ae-pods-gallery-grid')) {
            PodsGalleryGrid($scope, $);
        }
    };

    $(window).on( 'elementor/frontend/init', function() {

        if ( elementorFrontend.isEditMode() ) {
            isEditMode = true;
        }


        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-post-blocks.default', 	PostBlocksHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-portfolio.default', 	    PortfolioHandler );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-custom-field.default', 	CustomFieldHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-cf-google-map.default', 	CFGoogleMap );


        // ACF Gallery Skins
        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-acf-gallery.slider', 	    ACFGallerySlider );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-acf-gallery.carousel', 	ACFGalleryCarousel );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-acf-gallery.grid', 	    ACFGalleryGrid );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-woo-tabs.default', 	    WooTabsHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-woo-gallery.default', 	WooGalleryHandler );
        
        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-woo-products.carousel', 	WooProductsCarousel );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-woo-products.grid', 	    WooProductsGrid );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/global', 	                DynamicBgHandler );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-post-content.default',    ContentUnfold );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-post-image.default',      PostImageHandler );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-woo-rating.default', 	    WooRatingHandler );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-acf-repeater.default', 	ACFRepeaterHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-nav-menu.default', 	    NavMenuHandler );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-acf.wysiwyg',    FoldToUnfold );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-acf.text-area',    FoldToUnfold );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-pods.file_gallery', 	PodsGallery );

    });

} )( jQuery );