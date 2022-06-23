//var settings_global = {};
//var settings_page = {};
var is_animsition = false;
(function ($) {
    jQuery('body').addClass('dce-animsition');
    // -----------
    var initWrapping = function () {
        if (!$('.animsition').length) {
            $('#main').wrapInner('<div class="animsition" data-animsition-in-class="fade-in" data-animsition-in-duration="1000" data-animsition-out-class="fade-out" data-animsition-out-duration="800"></div>');
        }
    }
    //initWrapping();
    var PosttElements_AnimsitionHandler = function ( ) {
        //console.log( $scope );
        //alert('scrollify Handle');
        var sezioni = '.elementor-inner > .elementor-section-wrap > .elementor-section';

        // --------------------------------------------------------
        //console.log(elementor.settings.page.model.attributes);
        //alert(elementor.settings.page.model.get( 'scrollSpeed' ));
        //alert(elementor.settings.page.model.attributes.scrollSpeed.size);
        // --------------------------------------------------------

        $("#main, #footer").animsition({
            inClass: 'fade-in',
            outClass: 'fade-out',
            inDuration: 1500,
            outDuration: 800,
            linkElement: 'a:not([target="_blank"]):not([href^="#"]):not([href^="mailto"]):not([href^="tel"]):not(.gallery-lightbox):not(.elementor-clickable):not(.oceanwp-lightbox)',
            // e.g. linkElement: 'a:not([target="_blank"]):not([href^="#"])'
            loading: true,
            loadingParentElement: 'body', //animsition wrapper element
            loadingClass: 'animsition-loading',
            loadingInner: '', // e.g '<img src="loading.svg" />'
            timeout: false,
            timeoutCountdown: 5000,
            onLoadEvent: true,
            browser: ['animation-duration', '-webkit-animation-duration'],
            // "browser" option allows you to disable the "animsition" in case the css property in the array is not supported by your browser.
            // The default setting is to disable the "animsition" in a browser that does not support "animation-duration".
            overlay: false,
            overlayClass: 'animsition-overlay-slide',
            overlayParentElement: 'body',
            transition: function (url) {
                window.location.href = url;
            }
        });
    };
    function handleAnimsition(newValue) {

        //settings_page.enable_animsition = newValue;

        //elementor.reloadPreview();

        if (newValue) {
            // SI

            if (is_animsition) {
                //$.scrollify.enable();

            } else {
                settings_page = elementor.settings.page.model.attributes;
            }


            PosttElements_AnimsitionHandler();
        } else {
            // NO

            //$.scrollify.isDisabled();
            //$.scrollify.destroy();

            //

        }
    }


    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {
        //alert('aaaaa');
        var settings_page = elementorFrontendConfig.settings.page;
        var settings_global = elementorFrontendConfig.settings.dynamicooo;
        //
        //console.log( settings_page );
        console.log(elementorFrontendConfig.settings.dynamicooo);
        if (settings_global) {
            if (settings_global.enable_animsition)
                PosttElements_AnimsitionHandler( );
        }
        /*setTimeout(function () {

        }, 100);*/
        //
        // per il renderin della preview in EditMode
        if (elementorFrontend.isEditMode()) {
            elementor.once('preview:loaded', function () {
                // questo è il callBack di fine loading della preview
                //alert('fine '+settings_page.enable_animsition);
            });
            elementor.settings.page.addChangeCallback('enable_animsition', handleAnimsition);

        }



    });
})(jQuery);
