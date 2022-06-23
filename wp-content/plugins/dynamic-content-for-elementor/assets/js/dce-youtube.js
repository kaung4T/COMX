/*
( function( $ ) {
    var WidgetElementsYTHandler = function( $scope, $ ) {
*/    

        var ytplayer = null;
        var ytplayers = [];//null;

        function setVideoCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            var expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }
        function getVideoCookie(cname) {
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

        function videoPlay(ytplayer) {
          if (ytplayer) {
            ytplayer.playVideo();
            jQuery('.dce-youtube-play').removeClass('paused');
          }
        }
        function videoPause(ytplayer) {
            ytplayer.pauseVideo(ytplayer);
            jQuery('.dce-youtube-play').addClass('paused');
        }
        function videoStop(ytplayer) {
          if (ytplayer) {
            ytplayer.stopVideo(ytplayer);
          }
        }
        function videoPauseToggle(ytplayer) {
          if (ytplayer) {
            //console.log(ytplayer.getPlayerState());
            if (ytplayer.getPlayerState() == 2 /*paused*/ || ytplayer.getPlayerState() == 5 /*stopped*/) {
                videoPlay(ytplayer);
            } else {
                //alert('pausa');
                videoPause(ytplayer);
            }
            //removeVideoInfo(ytplayer);
          }
        }
        
        function videoMuteToggle(ytplayer) {
          if (ytplayer) {
            if (ytplayer.isMuted()) {
                setVideoCookie("video-mute", 0, 1);
                ytplayer.unMute();
                jQuery('.dce-youtube-mute').removeClass('mute');
            } else {
                setVideoCookie("video-mute", 1, 1);
                ytplayer.mute();
                jQuery('.dce-youtube-mute').addClass('mute');
            }
            //jQuery('.dce-youtube-mute').toggleClass('mute');
          }
        }
        
        // Cors rescriction block this...
        /*
        function removeVideoInfo(ytplayer) {
            if (ytplayer) {
                var ytid = getVideoId(ytplayer);
                console.log('remove info from iframe');
                jQuery('iframe#'+ytid).contents().find('.ytp-scroll-min, .ytp-pause-overlay, .ytp-chrome-top').remove();
            }
        }
        */
       
        function setVideoHeight(ytplayer) {
            if (ytplayer) {
                var ytid = getVideoId(ytplayer);
                var ww = jQuery(window).width();
                //if (ww > 767) {
                    var vw = jQuery('#'+ytid).parent().width();
                    var wh = jQuery(window).height();
                    var vh = Math.round((720*vw) / 1280);
                    var wvh = vh;
                    //console.log(ytid + ' - ' +vw);
                    //console.log(ytid + ' - ' +vh);
                    jQuery('#'+ytid).height(vh+'px');
                //}
            }
        }

        function onPlayerReady(event) {
            ytplayer = event.target;
            var ytid = getVideoId(ytplayer);
            ytplayers[ytid] = ytplayer;
            //console.log(ytplayer.a);
            var elementSettings = jQuery('#'+ytid).closest('.elementor-widget-dce-ytvideo').data('settings') || {};
            
            if (elementSettings.autoplay) {
                ytplayer.playVideo();
                //removeVideoInfo(ytplayer);
            }
  
            // MUTE
            if (elementSettings.mute) {
                //console.log('youtube video start mute');
                ytplayer.mute();
                jQuery('.dce-youtube-mute').addClass('mute');
            }
            if (getVideoCookie("video-mute") == '1') {
                ytplayer.mute();
                jQuery('.dce-youtube-mute').addClass('mute');
            }
            jQuery('.dce-youtube-mute').on('click', function(){
                videoMuteToggle(ytplayer);
                return false;
            });
            
   
            if (ytplayer) {
                if (ytplayer.getPlayerState() == 5) {
                    // il video non è partito automaticamente
                    // probabilmente sono su mobile
                    videoPlay(ytplayer);
                    //jQuery('.dce-youtube-play').remove();
                }
                jQuery('.dce-youtube-play').on('click tap', function(){
                    //console.log('play-pause');
                    videoPauseToggle(ytplayer);
                    return false;
                });
            }

            setVideoHeight(ytplayer);
            
            jQuery(window).scroll(function () {
                if (ytplayer) {
                    var ytid = getVideoId(ytplayer);
                    var vscroll = jQuery(window).scrollTop();
                    var offset = jQuery('#'+ytid).offset();
                    var maxh = offset.top + Math.round(jQuery('#'+ytid).height() / 2);
                    //console.log(maxh);
                    if (maxh && vscroll >= maxh) {
                        videoPause(ytplayer);
                    } else {
                        //videoPlay(ytplayer);
                    }
                }
            });

        }
        
        function getVideoId(ytplayer) {
            return jQuery(ytplayer.a).attr('id');
        }
        
        jQuery(window).load(function () {
            setVideoHeight(ytplayer);
        });

        jQuery(window).resize(function () {
            setVideoHeight(ytplayer);
        });
        
        
/*    
    }
    $( window ).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/dce-ytvideo.default', WidgetElementsYTHandler );
    } );
} )( jQuery );
*/