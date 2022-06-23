(function ($) {
    var settings_page = {};
    var is_scrollEffects = false;
    var sectionsAvailable = [];

    var datalax = [
        'data-lax-opacity',
        'data-lax-translate',
        'data-lax-translate-x',
        'data-lax-translate-y',
        'data-lax-scale',
        'data-lax-scale-x',
        'data-lax-scale-y',
        'data-lax-skew',
        'data-lax-skew-x',
        'data-lax-skew-y',
        'data-lax-rotate',
        'data-lax-rotate-x',
        'data-lax-rotate-y',

        'data-lax-brightness',
        'data-lax-contrast',
        'data-lax-hue-rotate',
        'data-lax-blur',
        'data-lax-invert',
        'data-lax-saturate',
        'data-lax-grayscale',

        'data-lax-bg-pos',
        'data-lax-bg-pos-x',
        'data-lax-bg-pos-y',

        'data-lax-anchor'
    ]



    var init_PageScroll = function (  ) {
        $('body').addClass('dce-pageScroll dce-scrolling');

        if (settings_page.custom_class_section) {
            $customClass = settings_page.custom_class_section;
        } else {
            $customClass = 'elementor-section';
        }

        // Get the section widgets of frst level in content-page
        // settings_page.scrollEffects_id_page
        //alert(settings_page.scroll_target);
        $target_sections = settings_page.scroll_target + ' ';
        $target_sections = '.elementor-' + settings_page.scroll_id_page;
        if (!$target_sections)
            $target_sections = '';

        var sezioni = $target_sections + '.elementor > .elementor-inner > .elementor-section-wrap > .' + $customClass;
        sectionsAvailable = $(sezioni);

        // Class direction
        $($target_sections).addClass('scroll-direction-' + settings_page.directionScroll);

        // property
        animationType = settings_page.animation_effects || ['spin']; //$scope.find('#dce_pagescroll').data('animation'),
        var animationType_string = [];

        if (animationType.length)
            animationType_string = animationType.join(' ');
        // configure
        sectionsAvailable.addClass('lax');
        setStyleEffects(animationType_string);

        // -------------------------------
        //alert(sectionsAvailable.length);

        lax.addPreset("scaleDown", function () {
            return {

                //"data-lax-scale": "-vh 0, 0 1, vh 1.5",
                "data-lax-scale": "0 1, (document.body.scrollHeight) 0",
                //"data-lax-translate-y": "0 0, vh 200",
            }
        });
        lax.addPreset("zoomInOut", function () {
            return {

                //"data-lax-scale": "-vh 0, 0 1, vh 1.5",
                "data-lax-scale": "-vh 0, 0 1, vh 0",
                "data-lax-translate-y": "0 0, vh -vh*0.2",
            }
        });

        lax.addPreset("leftToRight", function () {
            return {

                //"data-lax-scale": "-vh 0, 0 1, vh 1.5",
                "data-lax-translate-x": "-vh -vw,0 0, 0 1, vh vw",
            }
        });
        lax.addPreset("rightToLeft", function () {
            return {

                //"data-lax-scale": "-vh 0, 0 1, vh 1.5",
                "data-lax-translate-x": "-vh vw,0 0, 0 1, vh -vw",
            }
        });
        lax.addPreset("opacity", function () {
            return {
                "data-lax-opacity": "-vh 0, 0 1, vh 0",

            }
        });
        lax.addPreset("fixed", function () {
            return {
                "data-lax-translate-y": "0 0, vh elh",

            }
        });
        lax.addPreset("parallax", function () {
            return {
                "data-lax-translate-y": "0 0, elh elh",

            }
        });
        lax.addPreset("rotation", function () {
            return {
                "data-lax-rotate": "0 0, vh -30",

            }
        });
        lax.setup() // init Laxxx
        const updateLax = () => {
            if (lax)
                lax.update(window.scrollY)
            window.requestAnimationFrame(updateLax);
        }

        window.requestAnimationFrame(updateLax)


        is_scrollEffects = true;
    }


    // UTIL
    function removeScrollEffects() {
        //$('.elementor-'+settings_page.scrollEffects_id_page).removeClass('dce-pageScroll-element');

        $('body').removeClass('dce-pageScroll');
        if (sectionsAvailable.length)
            sectionsAvailable.removeClass('lax');
        clearStyleEffects();

        //updateLax = null;
        lax.removeElement();
        is_scrollEffects = false;
    }



    function setStyleEffects(effect) {
        if (effect)
            sectionsAvailable.attr('data-lax-preset', effect);
    }
    function clearStyleEffects() {
        //alert(sectionsAvailable.length);
        for (var i = 0; i < datalax.length; i++) {
            if (sectionsAvailable.length)
                sectionsAvailable.removeAttr(datalax[i]);
            if (lax)
                lax.updateElements();
        }
        sectionsAvailable.removeAttr('style');
    }


    // Change CallBack - - - - - - - - - - - - - - - - - - - - - - - - -

    function handleScrollEffects(newValue) {
        if (newValue) {
            // SI
            if (is_scrollEffects) {
                removeScrollEffects();
            } else {
                settings_page = elementor.settings.page.model.attributes;
            }
            setTimeout(function () {
                init_PageScroll();
            }, 100);
        } else {
            // NO
            removeScrollEffects();

        }

    }

    function handleScrollEffects_animations(newValue) {
        //
        //clearStyleEffects();


        var animationType_string = newValue.join(' ');
        if (newValue.length) {

            removeScrollEffects();

            settings_page = elementor.settings.page.model.attributes;
            init_PageScroll();
            setStyleEffects(animationType_string);
            lax.updateElements();
        }
        lax.updateElements();
    }


    $(window).on('elementor/frontend/init', function () {


    });

    window.onload = function () {

    }
    window.addEventListener("resize", function () {
        if (typeof lax !== 'undefined') {
            if (lax)
                lax.updateElements();
        }
    });
    $(document).on('ready', function () {
        //alert($('.elementor[data-elementor-type=page]').length);
        if (typeof elementorFrontendConfig.settings.page !== 'undefined') {
            /*if ( elementorFrontend.isEditMode() ){
             
             }else{
             settings_page = JSON.parse( $('.elementor').attr('data-elementor-settings') ); //
             }*/
            settings_page = elementorFrontendConfig.settings.page;
            //alert(settings_page.enable_scrollEffects);
            //console.log($('.elementor').attr('data-elementor-settings'));
            //alert(elementSettings.enable_scrollEffects);
            if (settings_page) {
                var is_enable_dceScrolling = settings_page.enable_dceScrolling;
                var is_enable_scrollEffects = settings_page.enable_scrollEffects;
                //console.log(elementorFrontendConfig.post.id);
                if (is_enable_scrollEffects && is_enable_dceScrolling) {
                    //
                    //alert('lax: '.elementorFrontendConfig.post.id);
                    setTimeout(function () {

                    }, 100);
                    init_PageScroll(); //INIT

                }

                if (elementorFrontend.isEditMode()) {
                    /*elementor.once( 'preview:loaded', function() {
                     // questo è il callBack di fine loading della preview
                     
                     } );*/
                    elementor.settings.page.addChangeCallback('enable_scrollEffects', handleScrollEffects);
                    elementor.settings.page.addChangeCallback('animation_effects', handleScrollEffects_animations);
                    //elementor.settings.page.addChangeCallback( 'enable_dceScrolling', handleScrollEffects );


                }
            }
        }



    });
})(jQuery);