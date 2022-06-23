;( function( $ ) {
    var WidgetElementsPopupHandler = function( $scope, $ ) {

        var dce_popup_settings = get_Dyncontel_ElementSettings( $scope );
        var id_scope = $scope.attr('data-id');
        //

        function dce_getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for(var i = 0; i <ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
        function dce_setCookie(cname, cvalue, exdays) {
            var d = new Date();
            //d.setTime(d.getTime() + (exdays*24*60*60*1000)); // in days
            d.setTime(d.getTime() + (exdays*1000)); // in seconds
            var expires = "expires="+ d.toUTCString()+";";
            if (!exdays) {
                expires = '';
            }
            document.cookie = cname + "=" + cvalue + ";" + expires + "path=/";
        }


        function dce_show_modal(id_modal) {
             //$('#'+id_modal).closest('.elementor-element').data('settings');

            //console.log('show modal: '+id_modal);
            var open_delay = 0;
            if (dce_popup_settings.open_delay.size) {
                open_delay = dce_popup_settings.open_delay.size;

            }
            //alert(dce_popup_settings.open_delay+' '+open_delay);
            setTimeout(function(){

                

                //aggiungo al body la classe aperto
                if (!elementorFrontend.isEditMode()) {
                    $('body').addClass('modal-open-'+id_modal).addClass('dce-modal-open');
                    $('html').addClass('dce-modal-open');
                }
                if( dce_popup_settings.wrapper_maincontent ){

                    $(dce_popup_settings.wrapper_maincontent).addClass('dce-push').addClass('animated').parent().addClass('perspective');
                }
                $('#'+id_modal).show();
                //$('#'+id_modal+' .modal-dialog').addClass(dce_popup_settings.open_animation); //modal();
                $('#'+id_modal+'-background').show().removeClass('fadeOut').addClass('fadeIn');
            }, open_delay);
        }

        function dce_hide_modal(id_modal) {
             //$('#'+id_modal).closest('.elementor-element').data('settings');
            // set cookie
            console.log('set cookie for: '+id_modal);
            if (!dce_popup_settings.always_visible) {
                dce_setCookie(id_modal,1,dce_popup_settings.cookie_lifetime);
            }
            var settings_close_delay = 0;
            if (dce_popup_settings.close_delay) {
                settings_close_delay = dce_popup_settings.close_delay;
            }

            //levo dal body la classe aperto
            $('body').removeClass('modal-open-'+id_modal);
            $('body').addClass('modal-close-'+id_modal);

            //
            $('#'+id_modal+'-background').one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(el){
                $('#'+id_modal+'-background').hide();
                $(el.currentTarget).off('webkitAnimationEnd oanimationend msAnimationEnd animationend');
            });
            $('#'+id_modal+' .modal-dialog').one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(el){
                               $('#'+id_modal).hide();
                               
                               //$(el.currentTarget).removeClass(dce_popup_settings.close_animation);
                               $(el.currentTarget).off('webkitAnimationEnd oanimationend msAnimationEnd animationend');

                               setTimeout(function(){
                                    if (!elementorFrontend.isEditMode()) {
                                        $('body').removeClass('modal-close-'+id_modal).removeClass('dce-modal-open');
                                        $('html').removeClass('dce-modal-open');
                                    }
                                    if( dce_popup_settings.wrapper_maincontent ) $( dce_popup_settings.wrapper_maincontent ).removeClass('dce-push').removeClass('animated').parent().removeClass('perspective');

                                },300);

                            });

            setTimeout(function(){
                //alert(dce_popup_settings.close_animation);
                //$('#'+id_modal+' .modal-dialog').removeClass(dce_popup_settings.open_animation).addClass(dce_popup_settings.close_animation); //modal();
                $('#'+id_modal+'-background').removeClass('fadeIn').addClass('fadeOut');
            }, settings_close_delay);
        }



        /*document.addEventListener('DOMContentLoaded', function() {



        }, false);*/





        //var dce_popup_settings = $(this).closest('.elementor-element').data('settings');
        var modal = $scope.find('.dce-popup-container-'+id_scope);

        function push_actions() {
            /*if( typeof elementSettings.enabled_push !== 'undefined' &&  elementSettings.enabled_push){
                //alert(elementSettings.enabled_push);
                if(!$('#dce-wrap').length){

                    // avvolgo il contenuto del body per poterlo spostare
                    $('body').wrapInner('<div id="dce-outer-wrap"><div id="dce-wrap" class="dce-wrap-animated animated"></div></div>');
                    //sposto il modale fuori

                }
                // ....

            }*/
            if (!elementorFrontend.isEditMode()) {
                $(modal).prependTo("body");
            }

        }
        push_actions();



        // - * - * - * - * - * - * - * - * - * - * -


        // ON LOAD
        $('.dce-popup-onload').each(function(){
             //$(this).closest('.elementor-element').data('settings');
            var id_modal = $(this).find('.dce-modal').attr('id');
            //console.log('trigger onload for: '+id_modal);
            // read cookie
            var cookie_popup = dce_getCookie(id_modal);
            if (dce_popup_settings.always_visible) {
                cookie_popup = false;
            }
            if (!cookie_popup) {
                    dce_show_modal(id_modal);
            } else {
                //console.log('cookie already setted for: '+id_modal);
            }
        });

        // BUTTON
        $scope.on('click', '.dce-button-open-modal, .dce-button-next-modal', function() {
            var id_modal = $(this).data('target')
            //console.log('trigger click btn for: '+id_modal);
            dce_show_modal(id_modal);
        });


        // WIDGET
        $('.dce-popup-widget').each(function(){
            var id_modal = $(this).find('.dce-modal').attr('id');
             //$(this).closest('.elementor-element').data('settings');
            var cookie_popup = dce_getCookie(id_modal);

            if (dce_popup_settings.always_visible) {
                cookie_popup = false;
            }
            if (cookie_popup) {
                $(this).removeClass('dce-popup-widget');
                //console.log('cookie setted for: '+id_modal);
            }
        });
        //alert($('.dce-popup-widget').length);
        if ($('.dce-popup-widget').length) {
            $(window).on('load scroll resize', function(){
                $('.dce-popup-widget').each(function(){
                    if ($(this).visible()) {
                        $(this).removeClass('dce-popup-widget');
                        var id_modal = $(this).find('.dce-modal').attr('id');
                        //console.log('trigger widget for: '+id_modal);
                        dce_show_modal(id_modal);
                        //console.log('visible widget: '+id_modal);
                    }
                });
            });
        }

        // SCROLL
        if ($('.dce-popup-scroll').length) {
            $(window).on('scroll', function(){
                $('.dce-popup-scroll').each(function(){
                     //$(this).closest('.elementor-element').data('settings');
                    if ($(window).scrollTop() > dce_popup_settings.scroll_display_displacement) {
                        $(this).removeClass('dce-popup-scroll');
                        var id_modal = $(this).find('.dce-modal').attr('id');
                        //console.log('trigger scroll for: '+id_modal);
                        dce_show_modal(id_modal);
                        //console.log('visible scroll: '+id_modal);
                    }
                });
            });
        }

        $(window).on('scroll', function(){
            $('.modal-hide-on-scroll:visible').each(function(){
                $(this).removeClass('modal-hide-on-scroll');
                dce_hide_modal($(this).attr('id'));
            });
        });

        $(document).on('keyup',function(evt) {
            if (evt.keyCode == 27) {
                $('.modal-hide-esc:visible').each(function(){
                    //$(this).removeClass('modal-hide-esc');
                    dce_hide_modal($(this).attr('id'));
                });
            }
        });

        $(document).on('click', '.dce-modal .dce-modal-close, .dce-button-close-modal, .dce-modal .dce-button-next-modal', function() {
           dce_hide_modal($(this).closest('.dce-modal').attr('id'));
           //alert($(this).closest('.dce-modal').attr('id'));
        });

        $(document).on('click', '.dce-modal-background-layer-close', function() {
           dce_hide_modal($(this).attr('data-target'));
        });

        // VIDEO
        /*(function($) {
                $('#popup-<?php echo $selectedPopup->post->ID; ?>').on('hide.bs.modal', function(e) {
                        var $if = $(e.delegateTarget).find('iframe');
                        var src = $if.attr("src");
                        $if.attr("src", '/empty.html');
                        $if.attr("src", src);
                });
        })($);*/

    }

    $( window ).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/dyncontel-popup.default', WidgetElementsPopupHandler );
    } );
} )( jQuery );