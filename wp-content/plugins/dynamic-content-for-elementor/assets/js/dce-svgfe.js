(function ($) {
    var WidgetElements_SvgFilterEffectsHandler = function ($scope, $) {
          var elementSettings = get_Dyncontel_ElementSettings($scope);
          var id_scope = $scope.attr('data-id');

          //console.log(elementSettings);

          

          // i valori dei filtri
          // duotone
          var valColor1 = '';
          var valColor2 = '';

          // broken
          var valBroken = '';

          // squiggly
          var valBaseFrequency = '';
          var valNumOctaves = '';
          var valScale = '';
          var valSeed = '';

          // sketch
          var valSketch = '';

          // glitch
          var glitch = '';
          // morphology
          
          // posterize
          




          var feDisp = $scope.find('feDisplacementMap#displacement-map')[0];
          // ---------
          var feImage = $scope.find('feImage#displacement-image')[0];

          var scaleMapTo = 0;
          // ---------


          var is_running = false;
          var run = $('#dce-svg-'+id_scope).attr('data-run');

          // ----------------------------------------------------------
          var animation_delay = 1;
          var animation_speed = 3;

          var easing_animation_ease = 'Power3';
          var easing_animation = 'easeInOut';
          var easeFunction = easing_animation_ease+'.'+easing_animation;


          // ---------------------------------------------------------
          /*var xlink = "http://www.w3.org/1999/xlink";
          //var imgUrl = image_url;
          
          dce_toBase64(image_url, function (data) {

            feImage.setAttributeNS(xlink, "xlink:href", data);
           

          });*/

          
          var svg_trigger = elementSettings.svg_trigger;
          var tl = new TimelineMax({ repeat: -1, repeatDelay: animation_delay });

          var interrompi = function(){
            tl.pause();
            is_running = false;
          }
          var ferma = function(){
           
            tl.stop();
            is_running = false;
          }
          var riproduci = function(){

            tl.play();

            is_running = true;
          }
          var inverti = function(){
            tl.reverse();

            is_running = true;
          }
          var riprendi = function(){
            tl.restart();

            is_running = true;
          }
          
          // ------------------
          var playShapeEl = function() {
        
            

            function repeatOften() {

              if(run != $('#dce-svg-'+id_scope).attr('data-run')){
                
                run = $('#dce-svg-'+id_scope).attr('data-run');
                if( run == 'running'){
                  riproduci();
                }else{
                  ferma();
                }
                
              }
              
              requestAnimationFrame(repeatOften);
              
            }
            requestAnimationFrame(repeatOften);
          }
          // ------------------
          var mouseenterFn = function(){

            var tl = new TimelineMax({ repeat: 0 });
            tl.to(feDisp,animation_speed,{attr:{scale:scaleMapTo},ease:easeFunction},0);
          };
          var mouseleaveFn = function(){
            var tl = new TimelineMax({ repeat: 0 });
            tl.to(feDisp,animation_speed,{attr:{scale:scaleMap},ease:easeFunction},0);
          };
          // ------------------------------
          var active_scrollAnalysi = function($el){
            if($el){
              var tl = new TimelineMax({ repeat: 0, paused: true, });
              var runAnim = function(dir){
                //
                if(dir == 'down'){
                  
                  //tl.to(feDisp,animation_speed,{attr:{scale:scaleMapTo},ease:easeFunction},animation_delay);
                  //tl.restart();
                }else if(dir == 'up'){

                  //tl.to(feDisp,animation_speed,{attr:{scale:scaleMap},ease:easeFunction},animation_delay);
                  //tl.restart();
                }
              }
              var waypointOptions = {
                /*offset: function() {
                  return -this.element.clientHeight
                },*/
                triggerOnce: false,
                continuous: true
              };
              elementorFrontend.waypoint($($el), runAnim, waypointOptions);
            }
          }
          // pulisco tutto
          if(elementorFrontend.isEditMode()){
            if(tl) tl.kill(feDisp);
            
            $('.elementor-element[data-id='+id_scope+'] svg');
            $('.elementor-element[data-id='+id_scope+'] svg');
            $('.elementor-element[data-id='+id_scope+'] svg');
            $('.elementor-element[data-id='+id_scope+'] svg');
          }

          // *********
          /*if(elementSettings.svg_trigger == 'animation'){
            animation_delay = elementSettings.delay_animation.size || 1;
            animation_speed = elementSettings.speed_animation.size || 3;

            easing_animation_ease = elementSettings.easing_animation_ease || 'Power3';
            easing_animation = elementSettings.easing_animation || 'easeInOut';
            easeFunction = easing_animation_ease+'.'+easing_animation;

            createAnimation();
            if( elementorFrontend.isEditMode() ) playShapeEl();

          }else if(elementSettings.svg_trigger == 'rollover'){
            animation_delay = elementSettings.delay_animation.size || 1;
            animation_speed = elementSettings.speed_animation.size || 3;

            easing_animation_ease = elementSettings.easing_animation_ease || 'Power3';
            easing_animation = elementSettings.easing_animation || 'easeInOut';
            easeFunction = easing_animation_ease+'.'+easing_animation;

            scaleMapTo = elementSettings.disp_factor_to.size 
            // $('#dce-svg-'+id_scope)
            $('.elementor-element[data-id='+id_scope+'] svg').on('mouseenter', mouseenterFn);
            $('.elementor-element[data-id='+id_scope+'] svg').on('mouseleave', mouseleaveFn);
            $('.elementor-element[data-id='+id_scope+'] svg').on('touchstart', mouseenterFn);
            $('.elementor-element[data-id='+id_scope+'] svg').on('touchend', mouseleaveFn);

          }else if(elementSettings.svg_trigger == 'scroll'){

            animation_delay = elementSettings.delay_animation.size || 1;
            animation_speed = elementSettings.speed_animation.size || 3;

            easing_animation_ease = elementSettings.easing_animation_ease || 'Power3';
            easing_animation = elementSettings.easing_animation || 'easeInOut';
            easeFunction = easing_animation_ease+'.'+easing_animation;

            scaleMapTo = elementSettings.disp_factor_to.size 
            
            $('#dce-svg-'+id_scope).attr('data-run','paused')

            //if( elementorFrontend.isEditMode() ) playShapeEl();
            active_scrollAnalysi( '#dce-svg-'+id_scope );
          }
          
          var moveFnStart = function(){
            
          }
          function createAnimation(){
            //if(tl) tl.kill(feDisp);
            
            scaleMap = elementSettings.disp_factor.size;

            console.log(feDisp);
            //alert(animation_speed);
            setTimeout(function(){
              tl.to(feDisp,animation_speed,{onStart: moveFnStart, attr:{scale:0},ease:easeFunction },0).to(feDisp,animation_speed,{attr:{scale:scaleMap},ease:easeFunction },animation_speed);

              is_running = true;
              if( run == 'paused' && elementorFrontend.isEditMode() ){
                ferma();
              }else{
                riproduci();
              }
              
              //console.log(tl);
            },1000);
             
            
        }*/
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/dyncontel-filtereffects.default', WidgetElements_SvgFilterEffectsHandler);
    });
})(jQuery);
