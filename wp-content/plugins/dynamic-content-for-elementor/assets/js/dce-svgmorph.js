( function( $ ) {
	
	var WidgetElements_SvgMorphHandler = function( $scope, $ ) {
		//console.log( $scope );
		//alert('svg');

		var elementSettings = get_Dyncontel_ElementSettings($scope);
		var id_scope = $scope.attr('data-id');
		//alert(elementSettings.repeater_shape_polygon);
		//console.log(elementSettings);
		
		// il tipo di forma: path | polygon | circle | ECC..
		var forma = elementSettings.type_of_shape;
		var playpause_control = elementSettings.playpause_control || 'paused';
		// sincronizzo con il valore del field playpause_control
		

		var step = 0;
		var run = $('#dce-svg-'+id_scope).attr('data-run');

		var is_running = false;
		var trigger_svg = elementSettings.svg_trigger;

		var one_by_one = elementSettings.one_by_one;
		var enable_image = elementSettings.enable_image || 0;
		
		var pattern_image = '';
		if(enable_image) pattern_image = elementSettings.svg_image.id;


		// ciclo il ripetitore in base alla Forma
		var ripetitore = 'repeater_shape_'+forma;
		eval('var repeaterShape = elementSettings.'+ripetitore);
		//alert('ripetitore '+ripetitore);
		var contentElemsTotal = repeaterShape.length;
		var numberOfElements = repeaterShape.length;
		var shapes = [];
		//alert(numberOfElements.length);
		//console.log(repeaterShape);
		
		//console.log(repeaterShape);
		//alert(repeaterShape.length);

		
		/*
		easingSinusoidalInOut, 
		easingQuadraticInOut, 
		easingCubicInOut,
		easingQuarticInOut, 
		easingQuinticInOut, 
		easingCircularInOut,
		easingExponentialInOut.
		*/


		var dceshape = "#forma-"+id_scope;
		var dceshape_svg = "#dce-svg-"+id_scope;

		// timelinemax
		if(tl) tl.kill($(dceshape));
		var tl = null;
		tl = new TimelineMax();


		if(tlpos) tlpos.kill($(dceshape_svg));
		var tlpos = null;
		tlpos = new TimelineMax();


		
		var transitionImgAll = new TimelineMax();
		var transitionImg = new TimelineMax();

		var dceshape_delay = elementSettings.duration_morph.size || 2,
		dceshape_speed = elementSettings.speed_morph.size || 1;

		var easing_morph_ease = elementSettings.easing_morph_ease || 'Power3',
		easing_morph = elementSettings.easing_morph || 'easeInOut';
		
		var repeat_morph = elementSettings.repeat_morph;

		if(transitionTl) transitionTl.kill($(dceshape));
		var transitionTl = null;

		if(transitionTl) transitionTlpos.kill($(dceshape_svg));
		var transitionTlpos = null;

		// - - - - - - - - - - - - - - - - - - 

		var get_data_anim = function(){
			var duration_anim = elementSettings.duration_morph.size || 3;
			var speed_anim = elementSettings.speed_morph.size || 1;
			
			easing_morph_ease = elementSettings.easing_morph_ease;
			easing_morph = elementSettings.easing_morph;

			repeat_morph = elementSettings.repeat_morph;

			dceshape_delay = duration_anim;
			dceshape_speed = speed_anim;
		}
		var get_data_shape = function(){
			shapes = [];

			var ciccio = [];
			if( elementorFrontend.isEditMode()){
				ciccio = repeaterShape.models;
				//console.log('back:');
				//console.log(ciccio);
			}else{
				ciccio = repeaterShape;
				//console.log('front:');
				//console.log(ciccio);
			}
			var old_points = '';
			$.each(ciccio, function(i, el){
				var pippo = [];
				if( elementorFrontend.isEditMode()){
					pippo = repeaterShape.models[i].attributes;
				}else{
					pippo = repeaterShape[i];
				}
				//alert(pippo);
				
				var id_shape = pippo.id_shape;
				var points = pippo.shape_numbers;
				//alert(points);
				if(points == ''){
					points = old_points;
					//alert(old_points);
				}
				old_points = points; 
				//alert('!! '+points);


				// var x_position = pippo.x_position.size;
				// var y_position = pippo.y_position.size;
				// var rotate = pippo.rotate.size;
				// var scale = pippo.scale.size;
				var fillColor = pippo.fill_color;
				var strokeColor = pippo.stroke_color;
				var fillImage = pippo.fill_image.id;


				var strokeWidth = pippo.stroke_width.size || 0;
				
				var shapeX = pippo.shape_x.size || 0;
				var shapeY = pippo.shape_y.size || 0;
				var shapeRotate = pippo.shape_rotation.size || 0;

				var dceshape_delay = elementSettings.duration_morph.size || 2,
				dceshape_speed = elementSettings.speed_morph.size || 1;

				//alert(strokeWidth);
				var objRep = {
					//d: points,
					points: points,

					//scaleX: scale,
					//scaleY: scale,
					//rotate: rotate,
					//tx: x_position,
					//ty: y_position,
					path: {
						duration: pippo.duration_morph.size,
						speed: pippo.speed_morph.size,
						easing: pippo.easing_morph_ease,
						morph: pippo.easing_morph,
						elasticity: 600,
					},
					fill: {
						color: fillColor,
						image: pippo.fill_image.id
					},
					stroke: {
						width: strokeWidth,
						color: strokeColor
					},
					svg: {
						x: shapeX,
						y: shapeY,
						rotate: shapeRotate,
						elasticity: 600
						
					}
				}
				shapes.push(objRep);
				//console.log(shapes[step]);
			});
			
		}
		var getCustomData_speed = function(i){
			if( shapes[i].path.speed ){
				dceshape_speed = shapes[i].path.speed;
			}else{
				dceshape_speed = elementSettings.speed_morph.size;
			}
			//console.log(dceshape_speed);
			return dceshape_speed;
		}
		var getCustomData_duration = function(i){
			if( shapes[i].path.duration ){
				dceshape_delay = shapes[i].path.duration;
			}else{
				dceshape_delay = elementSettings.duration_morph.size;
			}
			return dceshape_delay;
		}
		var getCustomData_easing = function(i){

			if( shapes[i].path.easing ){
				easing_morph_ease = shapes[i].path.easing;
			}else{
				easing_morph_ease = elementSettings.easing_morph_ease;
			}
			return easing_morph_ease;
		}
		var getCustomData_morph = function(i){
			//alert(shapes[i].path.morph);
			if( shapes[i].path.morph ){
				easing_morph = shapes[i].path.morph;
			}else{
				easing_morph = elementSettings.easing_morph;
			}
			return easing_morph;
		}
		var getCustomData_image = function(i){
			//alert(shapes[i].path.morph);
			if( shapes[i].fill.image ){
				easing_morph = shapes[i].fill.image;
			}else{
				easing_morph = elementSettings.easing_morph;
			}
			return easing_morph;
		}
		var createTween = function(){
			//console.log(shapes);
			//alert('createTween');
    		if($("#forma-"+id_scope).length){
    			//alert(repeaterShape.length);

    			var tweenSVG = 'tlpos';
				var tweenString = 'tl';

				$.each(shapes, function(i, el){

						//if( shapes[i].path.duration ) dceshape_delay = shapes[i].path.duration;
						var fill_element = 'fill:"'+shapes[i].fill.color+'", ';
						if(enable_image && (shapes[i].fill.image || pattern_image)){
							fill_element = ''; //'fill: url(#pattern-'+id_scope+')';
							$(dceshape).attr('fill','url(#pattern-'+id_scope+')');
						}
						if(i > 0){
							tweenString += '.to("'+dceshape+'", '+getCustomData_speed(i)+', {onStart: moveFnStart, onStartParams:['+i+'], onComplete: myFunction1, onCompleteParams:['+i+'], morphSVG:`'+shapes[i].points+'`, ease: '+getCustomData_easing(i)+'.'+getCustomData_morph(i)+', attr:{'+fill_element+'"stroke-width":'+shapes[i].stroke.width+', stroke:"'+shapes[i].stroke.color+'"}}, "+='+getCustomData_duration(i)+'")';
							tweenSVG += '.to("'+dceshape_svg+'", '+getCustomData_speed(i)+', {rotation:'+shapes[i].svg.rotate+', x:'+shapes[i].svg.x+', y:'+shapes[i].svg.y+', ease: '+getCustomData_easing(i)+'.'+getCustomData_morph(i)+'}, "+='+getCustomData_duration(i)+'")';
							//alert(shapes[i].svg.x);
						}
				});
				var fill_element = 'fill:"'+shapes[0].fill.color+'", ';
				if(enable_image && (shapes[0].fill.image || pattern_image)){
					fill_element = ''; //'fill: url(#pattern-'+id_scope+')';
					$(dceshape).attr('fill','url(#pattern-'+id_scope+')');
				}
				tweenString += '.to("'+dceshape+'", '+getCustomData_speed(0)+', {onStart: moveFnStart, onStartParams:[0], onComplete: myFunction1, onCompleteParams:[0], morphSVG:`'+shapes[0].points+'`, ease: '+getCustomData_easing(0)+'.'+getCustomData_morph(0)+', attr:{'+fill_element+'"stroke-width":'+shapes[0].stroke.width+', stroke:"'+shapes[0].stroke.color+'"}}, "+='+getCustomData_duration(0)+'")';
				tweenString += ';';

				tweenSVG += '.to("'+dceshape_svg+'", '+getCustomData_speed(0)+', {rotation:'+shapes[0].svg.rotate+', x:'+shapes[0].svg.x+', y:'+shapes[0].svg.y+', ease: '+getCustomData_easing(0)+'.'+getCustomData_morph(0)+'}, "+='+getCustomData_duration(0)+'")';
				tweenSVG += ';';
			}

			//alert(tweenString);
			
			//TweenLite.to("#forma-"6212a99", 1, {onComplete: myFunction1, onCompleteParams:[], morphSVG:"M275.8,159.8l93.3,159.8H184.6H0l93.3-159.8L184.6,0L275.8,159.8z"}, "+=1").to("#forma-"6212a99", 1, {morphSVG:"M275.8,159.8l93.3,159.8H184.6H0l93.3-159.8L184.6,0L275.8,159.8z"}, "+=1").to("#forma-"6212a99", 1, {morphSVG:"M394.9,171l-98.7,171H98.7L0,171L98.7,0h197.5L394.9,171z"}, "+=1").to("#forma-"6212a99", 1, {morphSVG:"M326,326H0l1-163L0,0h326l-1,164.7L326,326z"}, "+=1").to("#forma-"6212a99", 1, {morphSVG:"M326,326H0c0,0,112.7-51,113.3-163C114,51,0,0,0,0h326L212.7,164.7L326,326z"}, "+=1").to("#forma-"6212a99", 1, {morphSVG:"M0,0l140.6,39l214.3,71.2l-234.2,43.2L52.6,354.1L27.5,191.3L0,0z"}, "+=1").to("#forma-"6212a99", 1, {morphSVG:"M458.4,235.7L225.3,463.6L111.4,347L0,0l227.9,233.1l117.7-112.7L458.4,235.7z"}, "+=1");
			eval(tweenString);
			eval(tweenSVG);

			is_running = true;
			if( run == 'paused' && elementorFrontend.isEditMode() ){
				ferma();
			}

			if( trigger_svg == 'rollover' || trigger_svg == 'scroll' ){
				ferma();
			}

			// $('#dce-svg-'+id_scope).attr('data-morphid',count);	

			//console.log($('#dce-svg-'+id_scope));
			


			/*$('#dce-svg-'+id_scope).attr('data-morphid').change(function() {
				  // Check input( $( this ).val() ) for validity here
				  alert('PPPPPPP');
				});*/
			/*$("body").on('DOMSubtreeModified', $('#dce-svg-'+id_scope), function() {
			    // code here
			    
			    if(step != $('#dce-svg-'+id_scope).data('morphid')) step = $('#dce-svg-'+id_scope).data('morphid');
			    //alert($('#dce-svg-'+id_scope).data('morphid')+step);
			});*/

			

			// myAnimation.eventCallback("onComplete", myFunction, ["param1","param2"]);

			// alla fine dell'intero ciclo
			tl.eventCallback("onRepeat", myFunction, ["param1","param2"]);
			

			if(repeat_morph != 0){
				tl.repeat(repeat_morph);
				tlpos.repeat(repeat_morph);
			}
			

			if(elementSettings.yoyo){
				if(tl.reversed()) tl.repeatDelay(elementSettings.duration_morph.size);
				if(tlpos.reversed()) tlpos.repeatDelay(elementSettings.duration_morph.size);

				tl.yoyo(true);
				tlpos.yoyo(true);
			}
			//alert(tl.repeatDelay());

		} // end createTween
		var myFunction = function(a,b){
			// ad ogni giro
			//alert('giro');
			/*if( 1 == $('#dce-svg-'+id_scope).attr('data-morphid')){
			tl.delay(elementSettings.duration_morph.size);
			tlpos.delay(elementSettings.duration_morph.size);
			}*/
			
		}
		var myFunction1 = function(id_step){
			// ad ogni trasformazione
			//alert(id_step);

			$('#dce-svg-'+id_scope).attr('data-morphid',id_step);

		}
		var movetoFn = function(id_step){
			if(transitionTl) transitionTl.kill($(dceshape));
			if(transitionTlpos) transitionTl.kill($(dceshape_svg));
			
		}
		var moveFnStart = function(id_step){
			//alert(id_step);
			if(enable_image){
				//transitionImg.kill($('#dce-svg-'+id_scope+' pattern image.dce-shape-image'));
				transitionImgAll = TweenMax.to('#dce-svg-'+id_scope+' pattern image.dce-shape-image', getCustomData_speed(id_step), {opacity:0, ease: + (getCustomData_easing(id_step)+'.'+getCustomData_morph(id_step))});
				transitionImg = TweenMax.to('#dce-svg-'+id_scope+' pattern image#img-patt-'+id_step, getCustomData_speed(id_step), {opacity:1, ease: + (getCustomData_easing(id_step)+'.'+getCustomData_morph(id_step))});

				//$('#dce-svg-'+id_scope+' pattern image.dce-shape-image').tweenMax({'opacity':0});
				//$('#dce-svg-'+id_scope+' pattern image#img-patt-'+id_step).css('opacity',1);
			}
			
		}
		var interrompi = function(){
			tl.pause();
			tlpos.pause();
			is_running = false;
		}
		var ferma = function(){
			if(transitionTl)transitionTl.stop();
			if(transitionTlpos)transitionTlpos.stop();
			tl.stop();
			tlpos.stop();
			is_running = false;
		}
		var riproduci = function(){
			
			tl.play();
			tlpos.play();
			is_running = true;
		}
		var inverti = function(){
			tl.reverse();
			tlpos.reverse();
			is_running = true;
		}
		var riprendi = function(){
			tl.restart();
			tlpos.restart();
			is_running = true;
		}
		var moveToStep = function(step){
					
				get_data_shape();
				 
				if (typeof shapes[step] !== "undefined") {
					//if(transitionTl) transitionTl.stop();
					//if(transitionTlpos) transitionTlpos.stop();
					if(transitionTl) transitionTl.kill($(dceshape));
					if(transitionTlpos) transitionTlpos.kill($(dceshape_svg));
					
					var fill_element = 'fill:"'+shapes[step].fill.color+'", ';
					if(enable_image && (shapes[step].fill.image || pattern_image)){
						fill_element = ''; //'fill: url(#pattern-'+id_scope+')';
						$(dceshape).attr('fill','url(#pattern-'+id_scope+')');
					}
					var tweenString = 'transitionTl.to("'+dceshape+'", '+getCustomData_speed(step)+', {onStart: moveFnStart, onStartParams:['+step+'], onComplete: movetoFn, onCompleteParams:['+step+'], morphSVG:`'+shapes[step].points+'`, ease: '+getCustomData_easing(step)+'.'+getCustomData_morph(step)+', attr:{'+fill_element+'"stroke-width":'+shapes[step].stroke.width+', stroke:"'+shapes[step].stroke.color+'"}});';
					var tweenStringPos = 'transitionTlpos.to("'+dceshape_svg+'", '+getCustomData_speed(step)+', {rotation: '+shapes[step].svg.rotate+', x:'+shapes[step].svg.x+', y:'+shapes[step].svg.y+', ease: '+getCustomData_easing(step)+'.'+getCustomData_morph(step)+'});';
					
					//alert(tweenStringPos);
				    eval(tweenStringPos);
				    eval(tweenString); 
				
					
				
				//alert(dceshape+' '+dceshape_speed);
				}
		}
		
		var playShapeEl = function() {
			
			//if($("#dce-svg-"+id_scope).attr('data-run') == 'paused') speed_anim = 100;
			if(transitionTl) transitionTl.kill($(dceshape));
			if(transitionTlpos) transitionTlpos.kill($(dceshape_svg));

			transitionTl = new TimelineMax();
			transitionTlpos = new TimelineMax();

			function repeatOften() {


				if(run != $('#dce-svg-'+id_scope).attr('data-run')){
					get_data_anim();
					//alert($('#dce-svg-'+id_scope).attr('data-run') + ' ... ' + run);
					run = $('#dce-svg-'+id_scope).attr('data-run');
					if( run == 'running'){
						riproduci();
					}else{
						ferma();
					}
					
				}

				if(!is_running){
					if( step != $('#dce-svg-'+id_scope).attr('data-morphid')){
						step = $('#dce-svg-'+id_scope).attr('data-morphid');
						
						moveToStep(step);
					}
				}
				
				
			  // Do whatever
			  requestAnimationFrame(repeatOften);
			  
			}
			requestAnimationFrame(repeatOften);
		}

		// ------------------------------
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
						
						if(one_by_one){
							//alert(step+' / '+numberOfElements);
							if(step < numberOfElements-1){
								step ++;
							}else{
								step = 0;
							}
							moveToStep(step);
						}else{
							//console.log(step);
							riproduci()
						}	
					}else if(dir == 'up'){
						if(one_by_one){
							
						}else{
							//console.log(step);
							interrompi()
						}
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
		// ------------------------------

		var mouseenterFn = function(){
			//$('#dce-svg-'+id_scope).attr('data-run','running');
			//console.log('play');
			//if(!is_running && !elementorFrontend.isEditMode()) 
			
			// ****************
			// step = $('#dce-svg-'+id_scope).attr('data-morphid');

			step = 1;
			//console.log(step);
			$('#dce-svg-'+id_scope).attr('data-morphid',step);

			moveToStep(step);
			//riprendi();
		};
		var mouseleaveFn = function(){
			//$('#dce-svg-'+id_scope).attr('data-run','paused');
			//console.log('stop');
			//if(is_running && !elementorFrontend.isEditMode()) ferma();

			// ****************
			// step = $('#dce-svg-'+id_scope).attr('data-morphid');
			step = 0;
			console.log(step);
			$('#dce-svg-'+id_scope).attr('data-morphid',step);

			moveToStep(step);
			//inverti();
		};
		
		
		// in frontend rendo obbligatorio l'animazione se sono con più di un elemento
		if(!elementorFrontend.isEditMode() && contentElemsTotal > 1 && elementSettings.svg_trigger == 'animation'){ 
			$('#dce-svg-'+id_scope).attr('data-run','running');
		}
		//alert('Morph '+id_scope);
		


		// pulisco tutto
		if(elementorFrontend.isEditMode()){
			if(transitionTl) transitionTl.kill($(dceshape));
			if(transitionTlpos) transitionTlpos.kill($(dceshape_svg));
			$('.elementor-element[data-id='+id_scope+']').off('mouseenter','svg');
			$('.elementor-element[data-id='+id_scope+']').off('mouseleave','svg');
			$('.elementor-element[data-id='+id_scope+']').off('touchstart','svg');
			$('.elementor-element[data-id='+id_scope+']').off('touchend','svg');
		}

		setTimeout(function(){
			get_data_anim();  
			get_data_shape(); 
			
			if(elementSettings.svg_trigger == 'animation'){

				createTween();

				// Comincia L'animazione ...........
				if( elementorFrontend.isEditMode() && contentElemsTotal > 1) playShapeEl();

			}else if(elementSettings.svg_trigger == 'rollover'){
				
				if(transitionTl) transitionTl.kill($(dceshape));
				if(transitionTlpos) transitionTlpos.kill($(dceshape_svg));

				transitionTl = new TimelineMax();
				transitionTlpos = new TimelineMax();

				// porto in stop la sequenza...
				// is_running = false;
				// e dat-run in pauded

				// $('#dce-svg-'+id_scope)
				$('.elementor-element[data-id='+id_scope+']').on('mouseenter','svg', mouseenterFn);
				$('.elementor-element[data-id='+id_scope+']').on('mouseleave','svg', mouseleaveFn);
				$('.elementor-element[data-id='+id_scope+']').on('touchstart','svg', mouseenterFn);
				$('.elementor-element[data-id='+id_scope+']').on('touchend','svg', mouseleaveFn);

				
			
			}else if(elementSettings.svg_trigger == 'scroll'){
				//createTween();
				//ferma();
				if(one_by_one){
					if(transitionTl) transitionTl.kill($(dceshape));
					if(transitionTlpos) transitionTlpos.kill($(dceshape_svg));
					
					transitionTl = new TimelineMax();
					transitionTlpos = new TimelineMax();
				}else{
					
					if(playpause_control == 'paused'){
						ferma();
						
					}else{
						createTween();
					}
					
					
					//alert(playpause_control);

					// Comincia L'animazione ...........
					if( elementorFrontend.isEditMode() && contentElemsTotal > 1) playShapeEl();
				}
				
				active_scrollAnalysi( '#dce-svg-'+id_scope );
			}

		},100);
	
		
		
	};
	// Make sure you run this code under Elementor..
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/dyncontel-svgmorphing.default', WidgetElements_SvgMorphHandler );
		
		
	} );
} )( jQuery );