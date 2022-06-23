(function ($) {
    var WidgetElements_SvgDistortionHandler = function ($scope, $) {
      var elementSettings = get_Dyncontel_ElementSettings($scope);
      var id_scope = $scope.attr('data-id');

      //console.log(elementSettings);

      var imgDisplacment = elementSettings.displacement_image.url;

      var image_url = $scope.find('.dce_distortion').attr('data-dispimage');

      if ( !image_url ) {
        return;
      }

      // Il filtro displacenent
      var feDisp = $scope.find('feDisplacementMap#displacement-map')[0];
      // L'immagine di sistorsione
      var feImage = $scope.find('feImage#displacement-image')[0];
      //if(feImage.length)

      // funzione globale (deprecata)
      //dce_feImage(image_url[0],feImage);


      // ----------------------------------------------------------

     
      var scaleMap = elementSettings.disp_factor.size;
      var scaleImage = elementSettings.disp_scale.size+'%';
      var posImage = ((100-(Number(elementSettings.disp_scale.size)))/2)+'%';

      var random_animation = false;
      var random_animation_range = 0;

      //in caso di rollover e scroll ho i valori di arrivo..
      if(elementSettings.svg_trigger == 'rollover' || elementSettings.svg_trigger == 'scroll'){
        var scaleMapTo = elementSettings.disp_factor_to.size || 0;
        var scaleImageTo = elementSettings.disp_scale_to.size+'%' || '100%';
        var posImageTo = ((100-(Number(elementSettings.disp_scale_to.size)))/2)+'%' || '0%';
      }
      // quando è animato e considero il valore random
      if(elementSettings.svg_trigger == 'animation'){
        random_animation = Boolean(elementSettings.random_animation);

        if(random_animation){
          random_animation_range = Number(elementSettings.random_animation_range.size);

          var scaleMap_rand_min = Number(scaleMap - random_animation_range);
          var scaleMap_rand_max = Number(scaleMap + random_animation_range);

          var scaleImage_rand_min = Number(scaleImage - random_animation_range);
          var scaleImage_rand_max = Number(scaleImage + random_animation_range);

          // parto dal valore 1 impostato
          var random_val_1 = scaleMap;
          // passo ad un valore casuale compreso nel range
          var random_val_2 = getRandomValue(scaleMap_rand_min,scaleMap_rand_max);

        }

        
      }
      // per tutte le animazioni
      if(elementSettings.svg_trigger != 'static'){
        
        var animation_delay = elementSettings.delay_animation.size || 1;
        var animation_speed = elementSettings.speed_animation.size || 3;

        var easing_animation_ease = elementSettings.easing_animation_ease || 'Power3';
        var easing_animation = elementSettings.easing_animation || 'easeInOut';
        var easeFunction = easing_animation_ease+'.'+easing_animation;
      }
      // in caso di animation vado da zero(0) al valore di partenza...

      

      // in caso di random vado dal valore di partenza ad un valore a caso vicino (considero un range)



      // ----------------------------------------------------------

      var is_running = false;
      var run = $('#dce-svg-'+id_scope).attr('data-run');

      // ----------------------------------------------------------
      
      

      /*var xlink = "http://www.w3.org/1999/xlink";
      //var imgUrl = image_url;
      
      dce_toBase64(image_url, function (data) {

        feImage.setAttributeNS(xlink, "xlink:href", data);
       

      });*/

      // pulisco tutto 
      if(elementorFrontend.isEditMode()){
        if(tl) tl.kill(feDisp);
        if(tli) tli.kill(feImage);
        
        $('.elementor-element[data-id='+id_scope+'] svg, .'+elementSettings.id_svg_class+' a').off('mouseenter');
        $('.elementor-element[data-id='+id_scope+'] svg, .'+elementSettings.id_svg_class+' a').off('mouseleave');
        $('.elementor-element[data-id='+id_scope+'] svg, .'+elementSettings.id_svg_class+' a').off('touchstart');
        $('.elementor-element[data-id='+id_scope+'] svg, .'+elementSettings.id_svg_class+' a').off('touchend');
      }

      var svg_trigger = elementSettings.svg_trigger;
      var tl = new TimelineMax({ repeat: -1, repeatDelay: animation_delay });
      var tli = new TimelineMax({ repeat: -1, repeatDelay: animation_delay });

      var interrompi = function(){
        tl.pause();
        tli.pause();
        is_running = false;
      }
      var ferma = function(){
        tl.stop();
        tli.stop();
        is_running = false;
      }
      var riproduci = function(){
        tl.play();
        tli.play();
        is_running = true;
      }
      var inverti = function(){
        tl.reverse();
        tli.reverse();
        is_running = true;
      }
      var riprendi = function(){
        tl.restart();
        tli.restart();
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
      // ------------------ ANIMATIONS
      var moveFnComplete = function(){
          

          random_val_1 = random_val_2;
          random_val_2 = getRandomValue(scaleMap_rand_min,scaleMap_rand_max);
          //alert(scaleMap+' '+random_animation_range+' - '+random_val_2);
          //alert(random_val_1+' '+random_val_2);
          //console.log(scaleMap_rand_min +' - '+ scaleMap_rand_max);
          createAnimation(true);
        }
      function createAnimation($random = false){
        
          if($random){
            tl = new TimelineMax({ repeat: 0 });
            //tli = new TimelineMax({ repeat: 0 });

            //alert(random_val_1 +' - '+ random_val_2);
            tl.to(feDisp,animation_speed,{onComplete: moveFnComplete, attr:{scale:random_val_1},ease:easeFunction },0).to(feDisp,animation_speed,{attr:{scale:random_val_2},ease:easeFunction },animation_speed);
            //tli.to(feImage,animation_speed,{ attr:{x:'0%', y:'0%', width:'100%',height:'100%'},ease:easeFunction },0).to(feImage,animation_speed,{attr:{x:posImage, y:posImage, width:scaleImage, height:scaleImage},ease:easeFunction },animation_speed);
          }else{
            tl.to(feDisp,animation_speed,{attr:{scale:0},ease:easeFunction },0).to(feDisp,animation_speed,{attr:{scale:scaleMap},ease:easeFunction },animation_speed);
            //tli.to(feImage,animation_speed,{attr:{x:'0%', y:'0%', width:'100%',height:'100%'},ease:easeFunction },0).to(feImage,animation_speed,{attr:{x:posImage, y:posImage, width:scaleImage, height:scaleImage},ease:easeFunction },animation_speed);
          }
          is_running = true;
          if( run == 'paused' && elementorFrontend.isEditMode() ){
            ferma();
          }else{
            riproduci();
          }
          
        

      }

      // ------------------ ROLL-HOVER
      var mouseenterFn = function(){

        tl = new TimelineMax({ repeat: 0 });
        tli = new TimelineMax({ repeat: 0 });

        tl.to(feDisp,animation_speed,{attr:{scale:scaleMapTo},ease:easeFunction},0);
        tli.to(feImage,animation_speed,{attr:{x:posImageTo, y:posImageTo, width:scaleImageTo,height:scaleImageTo},ease:easeFunction},0);
      };
      var mouseleaveFn = function(){
        tl = new TimelineMax({ repeat: 0 });
        tli = new TimelineMax({ repeat: 0 });

        tl.to(feDisp,animation_speed,{attr:{scale:scaleMap},ease:easeFunction},0);
        tli.to(feImage,animation_speed,{attr:{x:posImage, y:posImage, width:scaleImage,height:scaleImage},ease:easeFunction},0);
      };
      // ------------------- SCROLL 
      var active_scrollAnalysi = function($el){
        if($el){
          
          tl = new TimelineMax({ repeat: 0, paused: true, });
          
          var runAnim = function(dir){
            //
            //alert(dir+' '+scaleMapTo+' '+scaleMap);
            if(dir == 'down'){
              
              tl.to(feDisp,animation_speed,{attr:{scale:scaleMapTo},ease:easeFunction},animation_delay);
              tli.to(feImage,animation_speed,{attr:{x:posImageTo, y:posImageTo, width:scaleImageTo,height:scaleImageTo},ease:easeFunction},animation_delay);
              tl.restart();
              tli.restart();
            }else if(dir == 'up'){

              tl.to(feDisp,animation_speed,{attr:{scale:scaleMap},ease:easeFunction},animation_delay);
              tli.to(feImage,animation_speed,{attr:{x:posImage, y:posImage, width:scaleImage,height:scaleImage},ease:easeFunction},animation_delay);
              tl.restart();
              tli.restart();
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
      

      // **********************

      if(elementSettings.svg_trigger == 'animation'){
        
        createAnimation(random_animation);
        if( elementorFrontend.isEditMode() ) playShapeEl();

      }else if(elementSettings.svg_trigger == 'rollover'){
        
        // $('#dce-svg-'+id_scope)
        $('.elementor-element[data-id='+id_scope+'] svg, .'+elementSettings.id_svg_class+' a').on('mouseenter', mouseenterFn);
        $('.elementor-element[data-id='+id_scope+'] svg, .'+elementSettings.id_svg_class+' a').on('mouseleave', mouseleaveFn);
        $('.elementor-element[data-id='+id_scope+'] svg, .'+elementSettings.id_svg_class+' a').on('touchstart', mouseenterFn);
        $('.elementor-element[data-id='+id_scope+'] svg, .'+elementSettings.id_svg_class+' a').on('touchend', mouseleaveFn);

      }else if(elementSettings.svg_trigger == 'scroll'){
        
        
        $('#dce-svg-'+id_scope).attr('data-run','paused')

        //if( elementorFrontend.isEditMode() ) playShapeEl();
        active_scrollAnalysi( '#dce-svg-'+id_scope );
      }
      //
      function getRandomValue(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min)) + min; //Il max è escluso e il min è incluso

        //return Math.random() * (max - min) + min;
      }
      // Deprecate ------
      function dce_toBase64(url, callback) {
            var img = new Image();
            img.crossOrigin = "anonymous";
            img.onload = function () {
              var canvas = document.createElement("canvas");
              var ctx = canvas.getContext("2d");
              canvas.height = this.height;
              canvas.width = this.width;
              ctx.drawImage(this, 0, 0);

              var dataURL = canvas.toDataURL("image/png");
              callback(dataURL);
              canvas = null;
            };

            img.src = url;
      }

    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/dyncontel-svgdistortion.default', WidgetElements_SvgDistortionHandler);
    });
})(jQuery);