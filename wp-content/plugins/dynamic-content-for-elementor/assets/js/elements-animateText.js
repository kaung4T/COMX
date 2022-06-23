(function ($) {
    
    
    function random(min, max){
        return (Math.random() * (max - min)) + min;
    }
    function curve(i){
        var n = i / numTitleChars * 6.24;
        return  (Math.cos(n)) * -200;
    }
    var WidgetElements_AnimateTextHandler = function ($scope, $) {
        //console.log( $scope );
        var elementSettings = get_Dyncontel_ElementSettings($scope);
        var id_scope = $scope.attr('data-id');
        
        var eff_in = elementSettings.animatetext_animationstyle_in;
        var eff_out = elementSettings.animatetext_animationstyle_out;

        var target = $scope.find('.dce-animatetext');
        var words = elementSettings.words;
        var type = elementSettings.animatetext_splittype;
        
        var repeater_words = elementSettings.words;

        var texts = [];
        var ids = [];
        var ciccio = [];
        if( elementorFrontend.isEditMode()){
            ciccio = repeater_words.models;
        }else{
            ciccio = repeater_words;
        }
        $.each(ciccio, function(i, el){
            var pippo = [];
            if( elementorFrontend.isEditMode()){
                pippo = repeater_words.models[i].attributes;
            }else{
                pippo = repeater_words[i];
            }
            texts.push(pippo.text_word);
            ids.push(pippo._id);
        });

        var repeat = texts.length > 1 ? elementSettings.animatetext_repeat : 0;

        // --------- IN
        var splitOrigin_in = elementSettings.animatetext_splitorigin_in;
        var speed_in = elementSettings.speed_animation_in.size;
        var amount_in = elementSettings.amount_speed_in.size;
        var delaySteps_in = elementSettings.delay_animation_in.size;
        var anim_easing_in = elementSettings.animFrom_easing_ease_in+'.'+elementSettings.animFrom_easing_in;
        
        // --------- OUT
        var splitOrigin_out = elementSettings.animatetext_splitorigin_out;
        var speed_out = elementSettings.speed_animation_out.size;
        var amount_out = elementSettings.amount_speed_out.size;
        var delaySteps_out = elementSettings.delay_animation_out.size;
        var anim_easing_out = elementSettings.animFrom_easing_ease_out+'.'+elementSettings.animFrom_easing_out;
        
        var splitter;
        var split;
        
        var tl = new TimelineMax({
                //delay:0.5,
                //repeatDelay:0.5,
                repeat: repeat
            });
        
        var active_scrollAnalysi = function($el){
            if($el){
                /*var waypoint = new Waypoint({
                  element: $($el)[0],
                  handler: function(direction) {
                    console.log('Scrolled to waypoint!')
                  }
                })*/
                
                
                var runAnim = function(dir){
                    
                    step = $('#dce-svg-'+id_scope).attr('data-morphid');
                    //riproduci();
                    if(dir == 'down'){
                        
                        // play  
                    }else if(dir == 'up'){
                        // stop
                    }
                    $('#dce-svg-'+id_scope).attr('data-morphid',step);
                }
                var waypointOptions = {
                    offset: '100%',
                    triggerOnce: false
                };
                elementorFrontend.waypoint($($el), runAnim, waypointOptions);
            }
        }
        // -----------------------------
        var old_count = -1;
        var count = 0;
        var changeText = function(){
            old_count = count;
            if(count < texts.length-1){
                count ++;
            }else{
                count = 0;
            }
            changeAndSplittingText(count, old_count);
        }
        //
        var changeAndSplittingText = function($i, $ii){
            target.empty().html(texts[$i]);
            target.removeClass('elementor-repeater-item-'+ids[$ii]).addClass('elementor-repeater-item-'+ids[$i]);
            //alert(target.html().length);
            split = new SplitText(target, {
                type: ['chars','words','lines'],
                //wordDelimiter: "*"
              });
            //alert(type);
            switch (type) {
              case 'chars':
                    splitter = split.chars;
                break;
              case 'words':
                    splitter = split.words;
                break;
                
              case 'lines':
                    splitter = split.lines;
                break;
              default:
                
                break;
            }
            // --------- STAGGER
            var staggerOpt_in = {
                ease: Linear.easeNone,
                //grid:grid, 
                axis:null, //'null' 'x' 'y'
                amount: Math.floor(splitter.length / 2) * (amount_in/100),
                from: splitOrigin_in,
            }
            // --------- STAGGER OUT
            var staggerOpt_out = {
                ease: Linear.easeNone,
                //grid:grid, 
                axis:null, //'null' 'x' 'y'
                amount: Math.floor(splitter.length / 2) * (amount_out/100),
                from: splitOrigin_out,
            }
            //alert(eff_in+' '+amount_in+' '+speed_in+' '+anim_easing_in);
            switch (eff_in) {
                case 'fading':
                    //alert(eff_in);
                    tl.staggerFrom(splitter, speed_in, {
                        opacity: 0,
                        stagger: staggerOpt_in,

                        delay: delaySteps_in,
                        //ease: Back.easeIn.config(8)
                        ease: anim_easing_out
                    });

                    break;
                case 'from_left':
                    tl.staggerFrom(splitter, speed_in, {
                        x: -100,
                        opacity: 0,
                        delay: delaySteps_in,
                        stagger: staggerOpt_in,
                        //ease: Back.easeIn.config(8)
                        ease: anim_easing_in
                    });
                    break;
                    
                case 'from_right':
                     tl.staggerFrom(splitter, speed_in, {
                        x: 100,
                        opacity: 0,
                        delay: delaySteps_in,
                        stagger: staggerOpt_in,
                        //ease: Back.easeIn.config(8)
                        ease: anim_easing_in
                    });   
                    break;
                case 'from_top':
                    tl.staggerFrom(splitter, speed_in, {
                        y: -100,
                        opacity: 0,
                        delay: delaySteps_in,
                        stagger: staggerOpt_in,
                        //ease: Back.easeIn.config(8)
                        ease: anim_easing_in
                    });

                    break;
                case 'from_bottom':
                    tl.staggerFrom(splitter, speed_in, {
                        y: 100,
                        opacity: 0,
                        delay: delaySteps_in,
                        stagger: staggerOpt_in,
                        //ease: Back.easeIn.config(8)
                        ease: anim_easing_in
                    });  
                    break;
                case 'zoom_front':
                    tl.staggerFrom(splitter, speed_in, {
                        scale: 1.6,
                        opacity: 0,
                        delay: delaySteps_in,
                        stagger: staggerOpt_in,
                        //ease: Back.easeIn.config(8)
                        ease: anim_easing_in
                    });    
                    break;
                case 'zoom_back':
                    tl.staggerFrom(splitter, speed_in, {
                        scale: 0.1,
                        opacity: 0,
                        /*cycle:{
                            y:[100, -100], 
                            rotation:[-120, 120]
                        },*/
                        delay: delaySteps_in,
                        stagger: staggerOpt_in,
                        //ease: Back.easeIn.config(8)
                        ease: anim_easing_in
                    });    
                    break;
                case 'random_position':
                    tl.staggerFrom(splitter, speed_in, {
                        
                        opacity: 0,
                        cycle:{
                            scale: function() { return random(0.1, 3); },
                            x:function() { return random(-500, 500); },
                            y:function() { return random(-500, 500); },
                            z:function() { return random(-500, 500); },
                            rotation:function() { return random(-120, 120); }
                        },
                        delay: delaySteps_in,
                        stagger: staggerOpt_in,
                        //ease: Back.easeIn.config(8)
                        ease: anim_easing_in
                    }); 
                    /*$(splitter).each(function(i){
                        TweenMax.from($(this), speed_in, {
                            opacity: 0,
                            x: random(-500, 500),
                            y: random(-500, 500),
                            z: random(-500, 500),
                            //scale: random(0.1, 3),
                            delay: delaySteps_in + (i * .02),
                            ease: anim_easing_in
                            //yoyo: true,
                            //repeat: -1,
                            //repeatDelay: 3
                        });
                    });*/
                    break;
                case 'elastic':
                    tl.staggerFrom(splitter, 1, {
                        y:100, 
                        rotation:90, 
                        opacity:0,

                         stagger: {
                            ease: Linear.easeNone,
                            //grid:grid, 
                            axis:null, //'null' 'x' 'y'
                            amount: 0.5, //or if you want to make it dynamic, Math.floor(boxes.length / 2) * 0.25
                            from: splitOrigin,
                          },
                        

                        ease:Elastic.easeOut
                    }, 0.03); 
                    break;
                default:
                
                    break;
            }
            //all'uscita se i testi ripetitori sono == 1 si ferma
            if(texts.length > 1)
            switch (eff_out) {
                case 'fading':
                    //alert(eff_out);
                    tl.staggerTo(splitter, speed_out, {
                        opacity: 0,
                        //y: 50,
                        delay: delaySteps_out,
                        stagger: staggerOpt_out,
                        //ease: Back.easeIn.config(8)
                        ease: anim_easing_out
                    },null,null,changeText);

                    break;
                case 'to_left':
                    tl.staggerTo(splitter, speed_out, {
                        x: -100,
                        opacity: 0,
                        delay: delaySteps_out,
                        stagger: staggerOpt_out,
                        //ease: Back.easeIn.config(8)
                        ease: anim_easing_out
                    },null,null,changeText);
                    break;
                    
                case 'to_right':
                    tl.staggerTo(splitter, speed_out, {
                        x: 100,
                        opacity: 0,
                        delay: delaySteps_out,
                        stagger: staggerOpt_out,
                        //ease: Back.easeIn.config(8)
                        ease: anim_easing_out
                    },null,null,changeText);   
                    break;
                case 'to_top':
                    tl.staggerTo(splitter, speed_out, {
                        y: -100,
                        opacity: 0,
                        delay: delaySteps_out,
                        stagger: staggerOpt_out,
                        //ease: Back.easeIn.config(8)
                        ease: anim_easing_out
                    },null,null,changeText);

                    break;
                case 'to_bottom':
                    tl.staggerTo(splitter, speed_out, {
                        y: 100,
                        opacity: 0,
                        delay: delaySteps_out,
                        stagger: staggerOpt_out,
                        //ease: Back.easeIn.config(8)
                        ease: anim_easing_out
                    },null,null,changeText);    
                    break;
                case 'zoom_front':
                     tl.staggerTo(splitter, speed_out, {
                        scale: 1.6,
                        opacity: 0,
                        delay: delaySteps_out,
                        stagger: staggerOpt_out,
                        //ease: Back.easeIn.config(8)
                        ease: anim_easing_out
                    },null,null,changeText);   
                    break;
                case 'zoom_back':
                     tl.staggerTo(splitter, speed_out, {
                        scale: 0.1,
                        opacity: 0,
                        delay: delaySteps_out,
                        stagger: staggerOpt_out,
                        //ease: Back.easeIn.config(8)
                        ease: anim_easing_out
                    },null,null,changeText);      
                    break;
                case 'random_position':
                    tl.staggerTo(splitter, speed_out, {
                        
                        opacity: 0,
                        cycle:{
                            scale: function() { return random(0.1, 3); },
                            x:function() { return random(-500, 500); },
                            y:function() { return random(-500, 500); },
                            z:function() { return random(-500, 500); },
                            rotation:function() { return random(-120, 120); }
                        },
                        delay: delaySteps_out,
                        stagger: staggerOpt_out,
                        //ease: Back.easeIn.config(8)
                        ease: anim_easing_out
                    },null,null,changeText); 

                    break;
                case 'elastic':
                    tl.staggerTo(splitter, 0.5, {
                        opacity:0, 

                        stagger: {
                            ease: Linear.easeNone,
                            //grid:grid, 
                            axis:null, //'null' 'x' 'y'
                            amount: 0.5, //or if you want to make it dynamic, Math.floor(boxes.length / 2) * 0.25
                            from: splitOrigin,
                          },

                        ease: anim_easing_out
                    }, 0.08, 2); 
                    break;
                default:
                
                    break;
            }
        }

        
        
        // -----------------------------
        changeAndSplittingText(count, old_count);
        // -----------------------------

        

       

        // ----------------------------------
        /*tl.staggerFrom(splitter, 0.8, {
                ease:Back.easeOut, 
                x:100, cycle:{y:curve
            }, 
            opacity:0}, 0.02)
        .staggerTo(splitter, 0.8, {
                ease:Back.easeOut, 
                cycle:{
                    y:[100, -100], 
                    rotation:[-120, 120]
                }, 
                opacity:0
            }, 0.03, "+=1");*/
        // ----------------------------------

        
    };
    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {
        //alert('animate text');
        elementorFrontend.hooks.addAction('frontend/element_ready/dyncontel-animateText.default', WidgetElements_AnimateTextHandler);
    });
})(jQuery);