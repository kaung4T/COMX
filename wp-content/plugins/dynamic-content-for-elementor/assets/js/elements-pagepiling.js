(function ($) {
    var WidgetElements_PagePilingHandler = function ($scope, $) {
        console.log($scope);

        //fissaAltezza("#main");

        var fpistance = $scope.find('#dce_pagepiling');
        var pagepilingSettings = get_Dyncontel_ElementSettings($scope);

        if ($('#pp-nav').length > 0) {
            $('#pp-nav').remove();
        }
        /*if (typeof $.fn.pagepiling.destroy == 'function') { 
         $.fn.pagepiling.destroy('all');
         }*/
        //$.fn.pagepiling.reBuild();

        var repeater = pagepilingSettings.pagepiling;
        var dyn_sections_color = [];
        var dyn_sections_nome = [];
        var dyn_sections_anchor = [];
        var dyn_section_normalScroll = [];
        //console.log( repeater );
        for (var i = 0; i < repeater.length; i++) {
            if (elementorFrontend.isEditMode()) {
                var color_item = repeater.models[i].attributes.colorbg_section;
                var nome_item = repeater.models[i].attributes.id_name;
                var slug_item = repeater.models[i].attributes.slug_name;
                var normalScroll = repeater.models[i].attributes.normal_scroll;
            } else {
                var color_item = repeater[i].colorbg_section;
                var nome_item = repeater[i].id_name;
                var slug_item = repeater[i].slug_name;
                var normalScroll = repeater[i].normal_scroll;
            }
            if (color_item != '')
                dyn_sections_color.push(color_item);
            if (nome_item != '')
                dyn_sections_nome.push(nome_item);
            if (slug_item != '')
                dyn_sections_anchor.push(slug_item);
            if (normalScroll != '')
                dyn_section_normalScroll.push('#' + slug_item);
            //alert(normalScroll);
        }

        if (pagepilingSettings.enabled_tooltips) {
            dyn_sections_tt = dyn_sections_nome;
        } else {
            dyn_sections_tt = [];
        }
        if (dyn_section_normalScroll.length > 0) {
            dyn_section_normalScroll_str = dyn_section_normalScroll.join(); // converto l'array in stringa separata da (,)
        } else {
            dyn_section_normalScroll_str = null;
        }

        var hashUrl = pagepilingSettings.enabled_hash;
        if (!hashUrl)
            dyn_sections_anchor = [];

        if (pagepilingSettings.enabled_tooltips_label) {
            dyn_tooltip_label = dyn_sections_nome;
        } else {
            dyn_tooltip_label = [];
        }
        if (pagepilingSettings.navigation) {
            var navigation_dots = {
                'textColor': pagepilingSettings.navigation_textColor || '#000',
                'bulletsColor': pagepilingSettings.navigation_bulletsColor || '#000',
                'position': pagepilingSettings.navigationPosition || 'right',
                'tooltips': dyn_tooltip_label,
            }
        } else {
            var navigation_dots = false;
        }
        //alert(hashUrl);

        fpistance.pagepiling({

            menu: null,
            direction: 'vertical',
            verticalCentered: Boolean( pagepilingSettings.verticalCentered ),
            sectionsColor: dyn_sections_color,
            anchors: dyn_sections_anchor,
            scrollingSpeed: pagepilingSettings.scrollingSpeed || 700,
            easing: pagepilingSettings.easing || 'swing',
            loopBottom: Boolean( pagepilingSettings.loopTop ), //false,
            loopTop: Boolean( pagepilingSettings.loopBottom ), //false,
            css3: Boolean( pagepilingSettings.css3 ), //true
            navigation: navigation_dots,
            normalScrollElements: dyn_section_normalScroll_str, //dyn_section_normalScroll,
            normalScrollElementTouchThreshold: 5,
            touchSensitivity: 5,
            keyboardScrolling: true,
            sectionSelector: '.section',
            animateAnchor: false,

            //events
            onLeave: function (index, nextIndex, direction) {},
            afterLoad: function (anchorLink, index) {},
            afterRender: function () {},

            /*//Navigation
             // menu: '#menu',
             // lockAnchors: false,
             // anchors:['firstPage', 'secondPage'],
             navigation: Boolean( pagepilingSettings.navigation ),
             navigationPosition: pagepilingSettings.navigationPosition || 'right', //'right',
             navigationTooltips: dyn_sections_tt, //['firstSlide', 'secondSlide'],
             showActiveTooltip: true,
             // slidesNavigation: false,
             // slidesNavPosition: 'bottom',
             
             // //Scrolling
             css3:  Boolean( pagepilingSettings.css3 ),
             easing: pagepilingSettings.easing || 'easeInOutCubic',
             easingcss3: pagepilingSettings.easing_css || 'ease',
             scrollingSpeed: pagepilingSettings.scrollingSpeed || 700,
             
             autoScrolling:  Boolean( pagepilingSettings.autoScrolling ),
             // fitToSection: true,
             // fitToSectionDelay: 1000,
             // scrollBar: false,
             
             
             // loopBottom: false,
             // loopTop: false,
             
             // loopHorizontal: true,
             continuousVertical: Boolean( pagepilingSettings.continuousVertical, //false
             
             // continuousHorizontal: false,
             // scrollHorizontally: false,
             // interlockedSlides: false,
             // dragAndMove: false,
             // offsetSections: false,
             // resetSliders: false,
             // fadingEffect: false,
             // normalScrollElements: '.section1, .section2',
             
             scrollOverflow: true,
             
             // scrollOverflowReset: false,
             // scrollOverflowOptions: null,
             // touchSensitivity: 15,
             // normalScrollElementTouchThreshold: 5,
             // bigSectionsDestination: null,
             
             // //Accessibility
             // keyboardScrolling: true,
             // animateAnchor: true,
             // recordHistory: true,
             
             // //Design
             controlArrows: Boolean( pagepilingSettings.controlArrows ), //true
             verticalCentered: Boolean( pagepilingSettings.verticalCentered ), //true
             sectionsColor : dyn_sections_color,
             // paddingTop: '3em',
             // paddingBottom: '10px',
             // fixedElements: '#header, .footer',
             // responsiveWidth: 0,
             // responsiveHeight: 0,
             // responsiveSlides: true, //false,
             // parallax: false,
             // parallaxOptions: {type: 'reveal', percentage: 62, property: 'translate'},
             
             // //Custom selectors
             // sectionSelector: '.section',
             // slideSelector: '.slide',
             
             // lazyLoading: true,
             
             // //events
             // onLeave: function(index, nextIndex, direction){},
             // afterLoad: function(anchorLink, index){},
             // afterRender: function(){ },
             // afterResize: function(){},
             // afterResponsive: function(isResponsive){},
             // afterSlideLoad: function(anchorLink, index, slideAnchor, slideIndex){},
             // onSlideLeave: function(anchorLink, index, slideIndex, direction, nextSlideIndex){}*/

        });


    };

    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/dyncontel-pagepiling.default', WidgetElements_PagePilingHandler);
    });

    var wwin;
    var hwin;
    var fissaAltezza = function (el) {
        wwin = $(window).width();
        hwin = $(window).height();

        if (wwin > 768) {
            $(el).height(hwin);
        } else {
            $(el).height('auto'); //-(hhead+hfoot+hsubf+htopb)

        }

    }

    /*$(window).on('resize', function(){ 
     
     fissaAltezza("#main"); 
     
     });*/
})(jQuery);
