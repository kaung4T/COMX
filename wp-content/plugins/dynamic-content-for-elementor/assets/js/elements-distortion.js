(function ($) {
    var WidgetElements_DistortionHandler = function ($scope, $) {
        imagesLoaded(document.querySelectorAll('img'), () => {

        });
        //alert($scope.find('.grid__item-img').length);
        Array.from($scope.find('.grid__item-img')).forEach((el) => {
            const imgs = Array.from(el.querySelectorAll('img'));
            new hoverEffect({
                parent: el,
                intensity: el.dataset.intensity || undefined,
                speedIn: el.dataset.speedin || undefined,
                speedOut: el.dataset.speedout || undefined,
                easing: el.dataset.easing || undefined,
                hover: el.dataset.hover || undefined,
                image1: imgs[0].getAttribute('src'),
                image2: imgs[1].getAttribute('src'),
                displacementImage: el.dataset.displacement
            });
        });

    };

    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/dyncontel-distortion.default', WidgetElements_DistortionHandler);
    });
})(jQuery);
