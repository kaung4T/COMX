jQuery(document).on('elementor/render/cf-video',function(e,id,a_ratio){
    container_element = '.elementor-element-'+ id;
    iframe_element = '.elementor-element-' + id + ' .cf-type-video iframe';
    iframe_width = jQuery(iframe_element).width();

    // get aspect ratio

    aspectRatio = a_ratio;
    if(aspectRatio == 169){
        ar = [16,9];
    }else if(aspectRatio == 43){
        ar = [4,3]
    }else{
        ar = [3,2]
    }

    iframe_height = iframe_width * (ar[1]/ar[0]);

    jQuery(iframe_element).height(iframe_height);
});


jQuery(document).on('click','.elementor-widget-ae-post-blocks .ae-pagination-wrapper a',function(){
    var page_num = 1;
    var wrapper = jQuery(this).closest('.ae-post-widget-wrapper');

    var source = wrapper.data('source');

    if(wrapper.hasClass('no-ajax')){
        return true;
    }
    var ae_post_overlay = wrapper.siblings('.ae-post-overlay');

    var wid = wrapper.data('wid');
    var e_wrapper = wrapper.closest('.elementor').attr('class');

    if(wrapper.parents('.ae_data').length > 0){
        var pid = wrapper.parents('.ae_data').attr('data-aetid');
    }else{
        var pid = wrapper.data('pid');
    }

    pid = e_wrapper.split('-')[1];

    cpid = '';
    if(source == 'related' || source == 'relation'){
        var cpid = wrapper.data('pid');
    }

    //var url = jQuery(this).attr('href');
    //if(typeof url.split("page/")[1] != 'undefined'){
    //    page_num = url.split("page/")[1].split('/')[0];
    //}

    page_num = jQuery(this).data('ae-page-id');

    ae_post_overlay.show();
    var data = {
        'pid' : pid,
        'wid' : wid,
        'cpid' : cpid,
        'page_num' : page_num,
        'curr_url' : aepro.current_url,
        action: 'ae_post_data',
        fetch_mode: 'paged'
    }

    jQuery.ajax({
        url: aepro.ajaxurl,
        dataType: 'json',
        data: data,
        method: 'POST',
        success: function (res) {
            wrapper.html(res.data);
            wrapper.find('.ae-featured-bg-yes').each(function(){
                img = jQuery(this).attr('data-ae-bg');
                jQuery(this).css('background-image','url(' + img + ')');
            });

            if ( wrapper.find('.ae-link-yes').data( 'ae-url' ) ){
                wrapper.find('.ae-link-yes').on('click', function (e) {

                    if ( jQuery(this).data( 'ae-url' ) && jQuery(this).hasClass('ae-new-window-yes') ) {
                        window.open(jQuery(this).data('ae-url'));
                    }else{
                        location.href = jQuery(this).data('ae-url');
                    }
                });
            }


            // hide black custom field widget wrapper
            wrapper.find('.ae-cf-wrapper.hide').each(function(){
                jQuery(this).closest('.elementor-widget-ae-custom-field').hide();
            });

            // reinitialize masonry
            if(wrapper.hasClass('ae-masonry-yes')){
                var grid = wrapper.find('.ae-post-list-wrapper');
                var $grid_obj = grid.masonry({
                    horizontalOrder: true
                });

                $grid_obj.imagesLoaded().progress(function(){
                    $grid_obj.masonry('layout');
                });
            }
            wrapper.find('.elementor-invisible').each(function(){
                // get settings
                settings = jQuery(this).data('settings');
                animation = settings.animation || settings._animation;

                jQuery(this).removeClass('elementor-invisible').removeClass(animation).addClass(animation);

            });

            var disable_scroll_on_ajax_load = wrapper.data('disable_scroll_on_ajax_load');
            if(disable_scroll_on_ajax_load == 'no') {
                var pagination_scroll_top_offset = wrapper.data('pagination_scroll_top_offset');
                jQuery('html,body').animate({
                        scrollTop: wrapper.offset().top - pagination_scroll_top_offset
                    },
                    'slow');
            }


            ae_post_overlay.hide();

            /* EAE Modal Popup Widget compatibility on post block ajax */

            if(wrapper.find('.eae-popup-link').length){

                $close_btn = wrapper.find('.eae-popup-wrapper').data('close-btn');

                $magnific = wrapper.find('.eae-popup-link').eaePopup({
                    type: 'inline',

                    mainClass: 'eae-popup eae-popup-' + wrapper.find('.eae-popup-link').data('id') + ' eae-wrap-' + wrapper.find('.eae-popup-link').data('ctrl-id'),

                    closeBtnInside: wrapper.find('.eae-popup-wrapper').data('close-in-out'),

                    closeMarkup: '<i class="eae-close ' + $close_btn + '"> </i>',
                });

            }
            /* EAE Modal Popup Widget compatibility on post block ajax */

            //wrapper.find('.ae-cf-gmap').each(function () {
            //    $map_scope = jQuery(this);
            //    CFGoogleMap($map_scope, $);
            //});
        }
    });

    return false;
});

jQuery(document).on('click','.elementor-widget-ae-portfolio .ae-pagination-wrapper a, .elementor-widget-ae-portfolio .filter-items a',function(){
    var page_num = 1;
    var wrapper = jQuery(this).closest('.ae-post-widget-wrapper');

    var ae_post_overlay = wrapper.siblings('.ae-post-overlay');

    var wid = wrapper.data('wid');
    var e_wrapper = wrapper.closest('.elementor').attr('class');

    if(wrapper.parents('.ae_data').length > 0){
        var pid = wrapper.parents('.ae_data').attr('data-aetid');
    }else{
        var pid = wrapper.data('pid');
    }

    pid = e_wrapper.split('-')[1];


    term_id = jQuery(this).data('term-id');
    if(typeof term_id == 'undefined'){
        term_id = wrapper.find('.filter-items.active a').data('term-id');

    }
    cpid = '';

    //var url = jQuery(this).attr('href');
    //if(typeof url.split("page/")[1] != 'undefined'){
    //    page_num = url.split("page/")[1].split('/')[0];
    //}

    page_num = jQuery(this).data('ae-page-id');

    ae_post_overlay.show();

    var data = {
        'pid' : pid,
        'wid' : wid,
        'cpid' : cpid,
        'term_id': term_id,
        'page_num' : page_num,
        action: 'ae_post_data',
        fetch_mode: 'paged'
    }

    jQuery.ajax({
        url: aepro.ajaxurl,
        dataType: 'json',
        data: data,
        method: 'POST',
        success: function (res) {
            wrapper.html(res.data);
            wrapper.find('.ae-featured-bg-yes').each(function(){
                img = jQuery(this).attr('data-ae-bg');
                jQuery(this).css('background-image','url(' + img + ')');
            });

            if ( wrapper.find('.ae-link-yes').data( 'ae-url' ) ){
                wrapper.find('.ae-link-yes').on('click', function (e) {

                    if ( jQuery(this).data( 'ae-url' ) && jQuery(this).hasClass('ae-new-window-yes') ) {
                        window.open(jQuery(this).data('ae-url'));
                    }else{
                        location.href = jQuery(this).data('ae-url');
                    }
                });
            }


            // hide black custom field widget wrapper
            wrapper.find('.ae-cf-wrapper.hide').each(function(){
                jQuery(this).closest('.elementor-widget-ae-custom-field').hide();
            });

            // reinitialize masonry
            if(wrapper.hasClass('ae-masonry-yes')){
                var grid = wrapper.find('.ae-post-list-wrapper');
                var $grid_obj = grid.masonry({
                    horizontalOrder: true
                });

                $grid_obj.imagesLoaded().progress(function(){
                    $grid_obj.masonry('layout');
                });
            }

            wrapper.find('.elementor-invisible').each(function(){
                // get settings
                settings = jQuery(this).data('settings');
                animation = settings.animation || settings._animation;

                jQuery(this).removeClass('elementor-invisible').removeClass(animation).addClass(animation);

            });

            wrapper.find('article.ae-post-list-item').css('opacity', '1');
            ae_post_overlay.hide();

            /* EAE Modal Popup Widget compatibility on portfolio ajax */

            if(wrapper.find('.eae-popup-link').length){

                $close_btn = wrapper.find('.eae-popup-wrapper').data('close-btn');

                $magnific = wrapper.find('.eae-popup-link').eaePopup({
                    type: 'inline',

                    mainClass: 'eae-popup eae-popup-' + wrapper.find('.eae-popup-link').data('id') + ' eae-wrap-' + wrapper.find('.eae-popup-link').data('ctrl-id'),

                    closeBtnInside: wrapper.find('.eae-popup-wrapper').data('close-in-out'),

                    closeMarkup: '<i class="eae-close ' + $close_btn + '"> </i>',
                });

            }
            /* EAE Modal Popup Widget compatibility on portfolio ajax */

            //wrapper.find('.ae-cf-gmap').each(function () {
            //    $map_scope = jQuery(this);
            //    CFGoogleMap($map_scope, $);
            //});
        }
    });

    return false;
});



