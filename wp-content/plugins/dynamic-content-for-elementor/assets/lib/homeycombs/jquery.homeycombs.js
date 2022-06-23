(function($) {

    $.fn.honeycombs = function(options) {

        // Establish our default settings
        var settings = $.extend({
            combWidth: 250,
            margin: 10,
            item: '.comb'
        }, options);

        function initialise(element) {
            
            $(element).addClass('honeycombs-wrapper');
            
            var width = 0;
            var combWidth = 0;
            var combHeight = 0;
            var num = 0;
            var $wrapper = null;
            var item = settings.item;
            /**
             * Build the dom
             */
            function buildHtml(){
                // add the 2 other boxes
                $(element).find(item).wrapAll('<div class="honeycombs-inner-wrapper"></div>');
                $wrapper = $(element).find('.honeycombs-inner-wrapper');
                
                $(element).find(item).append('<div class="hex_l"></div>');
                $(element).find('.hex_l').append('<div class="hex_r"></div>');
                $(element).find('.hex_r').append('<div class="hex_inner"></div>');

                $(element).find('.hex_inner').append('<div class="inner_span"><div class="inner-text"></div></div>');
                
                num = 0;
                
                $(element).find(item).each(function(){
                    num = num + 1;
                    var image = $(this).find('img').attr('src');
                    var css = 'url("'+image+'") ';
                    
                    $(this).find('.hex_inner').attr('style', 'background-image: '+css);
                    
                    if($(this).find('span').length > 0){
                        $(this).find('.inner_span .inner-text').html($(this).find('span').html());
                    }else{
                        $(this).find('.inner_span').remove();
                    };
                });
                
                $(element).find('img, span, .inner_span').hide();
            }
            
            /**
             * Update all scale values
             */
            function updateScales(){
                combWidth = settings.combWidth;
                combHeight = ( Math.sqrt(3) * combWidth ) / 2;
                edgeWidth = combWidth / 2;
                
                
                $(element).find(item).width(combWidth).height(combHeight);
                $(element).find('.hex_l, .hex_r').width(combWidth).height(combHeight);
                $(element).find('.hex_inner').width(combWidth).height(combHeight);
            }
            
            /**
             * update css classes
             */
            function reorder(animate){
                
                updateScales();
                width = $(element).width();
                
                newWidth = ( num / 1.5) * settings.combWidth;
                
                if(newWidth < width){
                    width = newWidth;
                }
                
                $wrapper.width(width);
                
                var row = 0; // current row
                var upDown = 1; // 1 is down
                var left = 0; // pos left
                var top = 0; // pos top
                
                var cols = 0;
                
                $(element).find(item).each(function(index){
                    
                    top = ( row * (combHeight + settings.margin) ) + (upDown * (combHeight / 2 + (settings.margin / 2)));
                    
                    if(animate == true){
                        $(this).stop(true, false);
                        $(this).animate({'left': left, 'top': top});
                    }else{
                        $(this).css('left', left).css('top', top);
                    }
                    
                    left = left + ( combWidth - combWidth / 4 + settings.margin );
                    upDown = (upDown + 1) % 2;
                    
                    if(row == 0){
                        cols = cols + 1;
                    }
                        
                    if(left + combWidth > width){
                        left = 0;
                        row = row + 1;
                        upDown = 1;
                    }
                });
                
                $wrapper
                    .width(cols * (combWidth / 4 * 3 + settings.margin) + combWidth / 4)
                    .height((row + 1) * (combHeight + settings.margin) + combHeight / 2);
            }
            
            $(window).resize(function(){
                reorder(true);
            });
            
            $(element).find(item).mouseenter(function(){
                $(this).find('.inner_span').stop(true, true);
                $(this).find('.inner_span').fadeIn();
            });
            
            $(element).find(item).mouseleave(function(){
                $(this).find('.inner_span').stop(true, true);
                $(this).find('.inner_span').fadeOut();
            });
            
            buildHtml();
            reorder(false);
        }

        return this.each(function() {
            initialise(this);
        });

    }

}(jQuery));