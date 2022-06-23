(function ($) {
    var elementSettings360 = {};

    var WidgetElements_ThreeSixtySliderHandler = function ($scope, $) {
        console.log($scope);
        elementSettings360 = get_Dyncontel_ElementSettings($scope);
        var car;
        //alert(elementSettings360.height.size);
        car = $scope.find('.dce-threesixty').ThreeSixty({
            totalFrames: Number(elementSettings360.total_frame) - 1, // Total no. of image you have for 360 slider
            endFrame: Number(elementSettings360.end_frame) - 1, // end frame for the auto spin animation
            //currentFrame: Number(elementSettings360.current_frame), // This the start frame for auto spin
            imgList: '.threesixty_images', // selector for image list
            progress: '.spinner', // selector to show the loading progress
            imagePath: elementSettings360.pathimages, //'http://localhost:8888/poglie17/imagestest360/', // path of the image assets
            filePrefix: '', // file prefix if any
            ext: '.' + elementSettings360.format_file, // extention for the assets
            height: elementSettings360.height.size,
            width: '100%',
            navigation: Boolean( elementSettings360.navigation ), // false
            responsive: Boolean( elementSettings360.responsive ), // true
        });

    };

    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/dyncontel-threesixtyslider.default', WidgetElements_ThreeSixtySliderHandler);
    });
})(jQuery);