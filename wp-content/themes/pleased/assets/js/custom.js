jQuery(document).ready(function($) {

/*------------------------------------------------
            DECLARATIONS
------------------------------------------------*/

    var loader              = $('#loader');
    var loader_container    = $('#preloader');
    var scroll              = $(window).scrollTop();  
    var scrollup            = $('.backtotop');
    var menu_toggle         = $('.menu-toggle');
    var dropdown_toggle     = $('.main-navigation button.dropdown-toggle');
    var nav_menu            = $('.main-navigation ul.nav-menu');
    var latest              = $('.latest');
    var masonry_gallery     = $('.grid');
    var regular             = $('#special-menu .regular')
    var testimonial_wrapper = $('#testimonial .testimonial-wrapper')


/*------------------------------------------------
            PRELOADER
------------------------------------------------*/

    loader_container.delay(1000).fadeOut();
    loader.delay(1000).fadeOut("slow");

/*------------------------------------------------
            BACK TO TOP
------------------------------------------------*/

    $(window).scroll(function() {
        if ($(this).scrollTop() > 1) {
            scrollup.css({bottom:"25px"});
        } 
        else {
            scrollup.css({bottom:"-100px"});
        }
    });

    scrollup.click(function() {
        $('html, body').animate({scrollTop: '0px'}, 800);
        return false;
    });

/*------------------------------------------------
            MAIN NAVIGATION
------------------------------------------------*/

    menu_toggle.click(function(){
        nav_menu.slideToggle();
       $('.main-navigation').toggleClass('menu-open');
       $('.menu-overlay').toggleClass('active');

       if( $('.main-navigation .search-submit a').hasClass('search-active') ) {
            $('.main-navigation .search-submit a').removeClass('search-active');
            $('.main-navigation #search-modern').fadeOut();
            $('.menu-overlay').addClass('active');
        }
    });

    dropdown_toggle.click(function() {
        $(this).toggleClass('active');
       $(this).parent().find('.sub-menu').first().slideToggle();
    });


    $('.main-navigation ul li.search-menu a').click(function(event) {
        event.preventDefault();
        $(this).toggleClass('search-active');
        $('.main-navigation #search').fadeToggle();
        $('.main-navigation .search-field').focus();

    });




    $(window).scroll(function() {
        if ($(this).scrollTop() > 1) {
            $('.menu-sticky #masthead').addClass('nav-shrink');
        }
        if ($(this).scrollTop() > 50) {
            $('.menu-sticky #masthead').css({ 'box-shadow' : '0 1px rgba(34, 34, 34, 0.1)' });
        }
        else {
            $('.menu-sticky #masthead').removeClass('nav-shrink');
            $('.menu-sticky #masthead').css({ 'box-shadow' : 'none' });
        }
    });



/*------------------------------------------------
                PROJECT SLIDER   
------------------------------------------------*/


    latest.slick({
        responsive: [
            {
                breakpoint: 1200,
                    settings: {
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 900,
                    settings: {
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 567,
                    settings: {
                    slidesToShow: 1
                }
            }
        ]
    });

    regular.slick({
        responsive: [
            {
                breakpoint: 1200,
                    settings: {
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 900,
                    settings: {
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 567,
                    settings: {
                    slidesToShow: 1
                }
            }
        ]
    });
     testimonial_wrapper.slick({
        responsive: [
            {
                breakpoint: 1200,
                    settings: {
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 900,
                    settings: {
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 567,
                    settings: {
                    slidesToShow: 1
                }
            }
        ]
    });


/*------------------------------------------------
            PRODUCTS FILTERING
------------------------------------------------*/
$('.gallery-filtering ul li a').click(function(){
    $('.gallery-filtering ul li').removeClass('active');
    $(this).parent().addClass('active');
});

$('.gallery-filtering ul li a').click( function(e) {
    e.preventDefault();
    var currentCategory = '.' + $(this).data('slug');
    $('#gallery ul.products').slick('slickUnfilter');
    $('#gallery ul.products').slick('slickFilter', currentCategory);
});


/*------------------------------------------------
            MASONRY GALLERY
------------------------------------------------*/

masonry_gallery.packery({ itemSelector: '.grid-item' });

$('#testimonial .hentry').matchHeight();
$('#testimonial .hentry .entry-container').matchHeight();


/*------------------------------------------------
            POPUP VIDEO
------------------------------------------------*/

    $("#video-button").click(function (event) {
        event.preventDefault();
        $('#header-featured-image').addClass('active');
        $('#header-featured-image .widget.widget_media_video').fadeIn();
    });

    $(document).click(function (e) {
        var container = $("#video-button, .widget_media_video");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            $(".mejs-controls .mejs-playpause-button.mejs-pause button").trigger("click");
            $('#header-featured-image').removeClass('active');
            $('#header-featured-image .widget.widget_media_video').fadeOut();
            
        }
    });

/*--------------------------------------------------------------
 Keyboard Navigation
----------------------------------------------------------------*/
    if( $(window).width() < 1024 ) {
        $('#primary-menu').find("li").last().bind( 'keydown', function(e) {
            if( e.which === 9 ) {
                e.preventDefault();
                $('#masthead').find('.menu-toggle').focus();
            }
        });
    }
    else {
        $( '#primary-menu li:last-child' ).unbind('keydown');
    }

    $(window).resize(function() {
        if( $(window).width() < 1024 ) {
            $('#primary-menu').find("li").last().bind( 'keydown', function(e) {
                if( e.which === 9 ) {
                    e.preventDefault();
                    $('#masthead').find('.menu-toggle').focus();
                }
            });
        }
        else {
            $( '#primary-menu li:last-child' ).unbind('keydown');
        }
    });

/*------------------------------------------------
                END JQUERY
------------------------------------------------*/

});