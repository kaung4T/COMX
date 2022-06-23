(function(){

    jQuery(document).ready(function() {

        /* ---------------------------------------------- /*
         * Scroll top
         /* ---------------------------------------------- */

        jQuery(window).scroll(function() {
            if (jQuery(this).scrollTop() > 100) {
                jQuery('.page-scroll-up').fadeIn();
            } else {
                jQuery('.page-scroll-up').fadeOut();
            }
        });
		
		jQuery('.page-scroll-up').click(function () {
			jQuery("html, body").animate({
				scrollTop: 0
			}, 700);
			return false;
		});
    });
})(jQuery);