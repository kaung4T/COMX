(function ($) {
    var WidgetElements_TiltHandler = function ($scope, $) {
        //console.log( $scope );

        var tiltistance = $scope.find('.js-tilt');
        var tiltSettings = get_Dyncontel_ElementSettings($scope);

        //alert(tiltSettings.tilt_maxtilt);
        var tilt = $scope.find(tiltistance).tilt({
            maxTilt: tiltSettings.tilt_maxtilt['size'], //20,
            perspective: tiltSettings.tilt_perspective['size'], //1000,   // Transform perspective, the lower the more extreme the tilt gets.
            easing: "cubic-bezier(.03,.98,.52,.99)", // Easing on enter/exit.
            scale: tiltSettings.tilt_scale || 1, //1,      // 2 = 200%, 1.5 = 150%, etc..
            speed: tiltSettings.tilt_speed['size'], //300,    // Speed of the enter/exit transition.
            transition: Boolean( tiltSettings.tilt_transition ), //true,   // Set a transition on enter/exit.
            axis: null, // What axis should be disabled. Can be X or Y.
            reset: Boolean( tiltSettings.tilt_reset ), //true,   // If the tilt effect has to be reset on exit.
            glare: Boolean( tiltSettings.tilt_glare ), //false,  // Enables glare effect
            maxGlare: tiltSettings.tilt_maxGlare       // From 0 - 1.

                    /*maxTilt:        20,
                     perspective:    1000,   // Transform perspective, the lower the more extreme the tilt gets.
                     easing:         "cubic-bezier(.03,.98,.52,.99)",    // Easing on enter/exit.
                     scale:          1,      // 2 = 200%, 1.5 = 150%, etc..
                     speed:          300,    // Speed of the enter/exit transition.
                     transition:     true,   // Set a transition on enter/exit.
                     axis:           null,   // What axis should be disabled. Can be X or Y.
                     reset:          true,   // If the tilt effect has to be reset on exit.
                     glare:          false,  // Enables glare effect
                     maxGlare:       1       // From 0 - 1.*/
        });

    };

    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/dyncontel-tilt.default', WidgetElements_TiltHandler);
    });
})(jQuery);
