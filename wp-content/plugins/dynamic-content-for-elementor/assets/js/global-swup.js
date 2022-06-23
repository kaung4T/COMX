(function ($) {
    var settings_global = {};
    var settings_page = {};
    var is_swup = false;

    jQuery('body').addClass('dce-swup');
    // -----------

    //initWrapping();
    var PosttElements_SwupHandler = function ( ) {
        
        //alert('PosttElements_SwupHandler ');

        let dce_swup_options = {
            LINK_SELECTOR: 'a[href^="' + window.location.origin + '"]:not([data-no-swup]):not(.is-lightbox), a[href^="/"]:not([data-no-swup]), a[href^="#"]:not([data-no-swup])',
            //LINK_SELECTOR: 'a.menu-link',
            //FORM_SELECTOR: 'form[data-swup-form]',
            //#outer-barba-wrap
            elements: ['#outer-wrap'],
            
            //animationSelector: '#main',
            cache: true,
            //pageClassPrefix: '',
            //scroll: true,
            //debugMode: false,
            preload: true,
            //support: true,
            /*skipPopStateHandling: function(event){
                if (event.state && event.state.source == "swup") {
                    return false;
                }
                return true;
            },*/
            plugins: [
                swupMergeHeadPlugin,
                //swupGtmPlugin,
                //swupGaPlugin
            ],
            //animateHistoryBrowsing: false,
        }
        swup = new Swup(dce_swup_options);



        /*const swup = new Swup({
              cache: true,
              animationSelector: '[class^="a-"]',
              elements: ['#swup'],
              pageClassPrefix: '',
              debugMode: false,
              scroll: true,
              preload: true,
              support: true,
              disableIE: false,

              animateScrollToAnchor: false,
              animateScrollOnMobile: false,
              doScrollingRightAway: false,
              scrollDuration: 0,

              LINK_SELECTOR: 'a[href^="/"]:not([data-no-swup]), a[href^="#"]:not([data-no-swup]), a[xlink\\:href]'
        });*/

        swup.usePlugin(swupMergeHeadPlugin, {runScripts: true });

        // trigger page view for GTM
        /*swup.on('pageView', function () {
            dataLayer.push({
                'event': 'VirtualPageview',
                'virtualPageURL': window.location.pathname,
                'virtualPageTitle' : document.title
            });
        });*/

        swup.on('contentReplaced', function () {

            /*window.ga('set', 'title', document.title);
            window.ga('set', 'page', window.location.pathname + window.location.search);
            window.ga('send', 'pageview');*/
            //console.log(elementorFrontend);

            elementorFrontend.elementsHandler.initHandlers();
            swup.options.elements.forEach((selector) => {
                

                //console.log(selector);
                // load scripts for all elements with 'selector'
                var element_el = $(selector).find('.elementor-element');
                element_el.each(function (i) {
                    var el = jQuery(this).attr('data-element_type');
                    //alert(el);

                    elementorFrontend.elementsHandler.runReadyTrigger(jQuery(this));
                });
                
            })
            //alert('init');
            //$( window ).trigger( 'elementor/frontend/init');

            //$( document ).trigger('ready');
               
            //$( 'body' ).trigger( 'init' );
            
            //$( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' );
        });
    };

    function handleSwup(newValue) {

        //settings_page.enable_swup = newValue;

        //elementor.reloadPreview();
        //alert(newValue);
        if (newValue) {
            // SI
            if (is_swup) {
                //$.scrollify.enable();

            } else {
                settings_page = elementor.settings.page.model.attributes;
            }


            PosttElements_SwupHandler();
        } else {
            // NO

            //$.scrollify.isDisabled();
            //$.scrollify.destroy();

            //

        }
    }
    

    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {
        
        //
        //
        //console.log(elementorFrontendConfig.settings.dynamicooo);
        // per il renderin della preview in EditMode
        /*if (elementorFrontend.isEditMode()) {
            elementor.once('preview:loaded', function () {
                // questo è il callBack di fine loading della preview
                //alert('fine '+settings_page.enable_swup);
            });
            //elementor.settings.dynamicooo.addChangeCallback('enable_swup', handleSwup);

        }*/
    
        


    });
    //alert('aaaaa');
    //settings_page = elementorFrontendConfig.settings.page;
    //
    if( typeof elementorFrontendConfig.settings.dynamicooo !== 'undefined' ) {
        settings_global = elementorFrontendConfig.settings.dynamicooo;
        //
        if (settings_global.enable_swup){
            PosttElements_SwupHandler( );
        }
    }
    //
    //console.log( settings_page );
    //
    
    
})(jQuery);
