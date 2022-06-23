<?php
namespace DynamicContentForElementor;

use MatthiasMullie\Minify;

/**
 * Main Plugin Class
 *
 * Register new elementor widget.
 *
 * @since 0.0.1
 */
class DCE_Assets {

    public static $styles = array(
        'dce-style' => '/assets/css/style.css',
        'dce-style-base' => '/assets/css/base.css',
        'dce-acf' => '/assets/css/elements-acf.css',
        'dce-acfSlider' => '/assets/css/elements-acfSlider.css',
        'dce-acfGallery' => '/assets/css/elements-acfGallery.css',
        'dce-acfRepeater' => '/assets/css/elements-acfRepeater.css',
        'dce-pods' => '/assets/css/elements-pods.css',
        'dce-pods-gallery' => '/assets/css/dce-pods-gallery.css',
        //'dce-acfGooglemap'=>'/assets/css/elements-googleMap.css',
        'dce-dynamicPosts' => '/assets/css/elements-dynamicPosts.css',
        'dce-dynamicPosts_slick' => '/assets/css/elements-dynamicPosts_slick.css',
        'dce-dynamicPosts_swiper' => '/assets/css/elements-dynamicPosts_swiper.css',

        'dce-dynamicPosts_timeline' => '/assets/css/elements-dynamicPosts_timeline.css',
        'dce-dynamicUsers' => '/assets/css/elements-dynamicUsers.css',
        'dce-featuredImage' => '/assets/css/elements-featuredImage.css',
        'dce-iconFormat' => '/assets/css/elements-iconFormat.css',
        'dce-nextPrev' => '/assets/css/elements-nextPrev.css',
        'dce-list' => '/assets/css/elements-list.css',
        'dce-modalWindowstyle' => '/assets/css/elements-modalWindow.css',
        //'dce-fullpage' => '/assets/css/elements-fullpage.css',
        'dce-pageScroll' => '/assets/css/elements-pageScroll.css',
        //'dce-pagePiling' => '/assets/css/elements-pagePiling.css',
        //'dce-posterSlider' => '/assets/css/elements-posterSlider.css',
        //'dce-swiper' => '/assets/css/elements-swiper.css',
        'dce-threesixtySlider' => '/assets/css/elements-threesixtySlider.css',
        'dce-twentytwenty' => '/assets/css/elements-twentytwenty.css',
        'dce-bubbles' => '/assets/css/elements-bubbles.css',
        'dce-parallax' => '/assets/css/elements-parallax.css',
        'dce-filebrowser' => '/assets/css/elements-filebrowser.css',
        'dce-animatetext' => '/assets/css/elements-animateText.css',
        'dce-dualView' => '/assets/css/elements-dualView.css',
        'dce-modal' => '/assets/css/dce-modal.css',
        'dce-woocommerce' => '/assets/css/dce-woocommerce.css',
        'dce-animatedoffcanvasmenu' => '/assets/css/dce-animatedoffcanvasmenu.css',
    );
    public static $vendorsCss = array(
        'dce-photoSwipe_default' => '/assets/lib/photoSwipe/photoswipe.min.css',
        'dce-photoSwipe_skin' => '/assets/lib/photoSwipe/default-skin/default-skin.min.css',
        'dce-justifiedGallery' => '/assets/lib/justifiedGallery/css/justifiedGallery.min.css',
        'dce-file-icon' => '/assets/lib/file-icon/file-icon-vivid.min.css',
        'animatecss' => '/assets/lib/animate/animate.min.css',
        'datatables' => '/assets/lib/datatables/datatables.min.css',
    );
    public static $minifyCss = 'assets/css/dce-frontend.min.css';

    public static $vendorsJs = array(
        'datatables' => '/assets/lib/datatables/datatables.min.js',
        // -----------------------------------------------------------
        // Widgets Libs
        'wow' => '/assets/lib/wow/wow.min.js',
        'isotope' => '/assets/lib/isotope/isotope.pkgd.min.js',
        'infinitescroll' => '/assets/lib/infiniteScroll/infinite-scroll.pkgd.min.js',
        'imagesLoaded' => '/assets/lib/imagesLoaded/imagesloaded.pkgd.min.js',
        'jquery-slick' => '/assets/lib/slick/slick.min.js',
        //'jquery-swiper' => '/assets/lib/swiper/js/swiper.min.js',
        'velocity' => '/assets/lib/velocity/velocity.min.js',
        'velocity-ui' => '/assets/lib/velocity/velocity.ui.min.js',
        'diamonds' => '/assets/lib/diamonds/jquery.diamonds.js',
        'homeycombs' => '/assets/lib/homeycombs/jquery.homeycombs.js',
        'photoswipe' => '/assets/lib/photoSwipe/photoswipe.min.js',
        'photoswipe-ui' => '/assets/lib/photoSwipe/photoswipe-ui-default.min.js',
        'tilt-lib' => '/assets/lib/tilt/tilt.jquery.min.js',

        'dce-jquery-visible' => '/assets/lib/visible/jquery-visible.min.js',
        'jquery-easing' => '/assets/lib/jquery-easing/jquery-easing.min.js',
        'justifiedGallery-lib' => '/assets/lib/justifiedGallery/js/jquery.justifiedGallery.min.js',
        'dce-parallaxjs-lib' => '/assets/lib/parallaxjs/parallax.min.js',

        //

        'dce-threesixtyslider-lib' => '/assets/lib/threesixty-slider/threesixty.min.js',
        'dce-jqueryeventmove-lib' => '/assets/lib/twentytwenty/jquery.event.move.js',
        'dce-twentytwenty-lib' => '/assets/lib/twentytwenty/jquery.twentytwenty.js',
        'dce-anime-lib' => '/assets/lib/anime/anime.min.js',

        'dce-aframe' => '/assets/lib/aframe/aframe-v0.8.2.min.js',
        'dce-revealFx' => '/assets/lib/reveal/revealFx.js',

        // ---------------- WEB-GL
        'dce-threejs-lib' => 'https://cdnjs.cloudflare.com/ajax/libs/three.js/109/three.min.js', //'/assets/lib/threejs/three.min.js',
        
        'dce-threejs-figure' => '/assets/lib/threejs/figure.js',

        'dce-threejs-EffectComposer' =>  '/assets/lib/threejs/postprocessing/EffectComposer.js',
        'dce-threejs-RenderPass' =>  '/assets/lib/threejs/postprocessing/RenderPass.js',
        'dce-threejs-ShaderPass' =>  '/assets/lib/threejs/postprocessing/ShaderPass.js',
        'dce-threejs-BloomPass' =>  '/assets/lib/threejs/postprocessing/BloomPass.js',
        'dce-threejs-FilmPass' =>  '/assets/lib/threejs/postprocessing/FilmPass.js',
        'dce-threejs-HalftonePass' =>  '/assets/lib/threejs/postprocessing/HalftonePass.js',
        'dce-threejs-DotScreenPass' =>  '/assets/lib/threejs/postprocessing/DotScreenPass.js',
        'dce-threejs-GlitchPass' =>  '/assets/lib/threejs/postprocessing/GlitchPass.js',

        'dce-threejs-CopyShader' =>  '/assets/lib/threejs/shaders/CopyShader.js',
        'dce-threejs-HalftoneShader' =>  '/assets/lib/threejs/shaders/HalftoneShader.js',
        'dce-threejs-RGBShiftShader' =>  '/assets/lib/threejs/shaders/RGBShiftShader.js',
        'dce-threejs-DotScreenShader' =>  '/assets/lib/threejs/shaders/DotScreenShader.js',
        'dce-threejs-ConvolutionShader' =>  '/assets/lib/threejs/shaders/ConvolutionShader.js',
        'dce-threejs-FilmShader' =>  '/assets/lib/threejs/shaders/FilmShader.js',
        'dce-threejs-DotScreenShader' =>  '/assets/lib/threejs/shaders/DotScreenShader.js',
        'dce-threejs-ColorifyShader' =>  '/assets/lib/threejs/shaders/ColorifyShader.js',
        'dce-threejs-VignetteShader' =>  '/assets/lib/threejs/shaders/VignetteShader.js',
        'dce-threejs-DigitalGlitch' =>  '/assets/lib/threejs/shaders/DigitalGlitch.js',
        'dce-threejs-PixelShader' =>  '/assets/lib/threejs/shaders/PixelShader.js',
        'dce-threejs-LuminosityShader' =>  '/assets/lib/threejs/shaders/LuminosityShader.js',
        'dce-threejs-SobelOperatorShader' =>  '/assets/lib/threejs/shaders/SobelOperatorShader.js',

        'dce-threejs-AsciiEffect' =>  '/assets/lib/threejs/effects/AsciiEffect.js',

        //'data-gui' => '/assets/lib/threejs/libs/dat.gui.min.js',
        //'displacement-distortion' => '/assets/lib/threejs/displacement_distortion.js',


        //'dce-charming-lib' => '/assets/lib/charming/charming.min.js',
        //'dce-pagepiling-lib' => '/assets/lib/pagepiling/jquery.pagepiling.min.js',
        //'dce-fullpage-lib' => '/assets/lib/fullpage/jquery.fullpage.min.js',
        //'dce-extension-lib' => '/assets/lib/fullpage/jquery.fullpage.extensions.min.js',

        'dce-tweenMax-lib' => '/assets/lib/greensock/TweenMax.min.js',
        'dce-tweenLite-lib' => '/assets/lib/greensock/TweenLite.min.js',

        'dce-timelineLite-lib' => '/assets/lib/greensock/TimelineLite.min.js',
        'dce-timelineMax-lib' => '/assets/lib/greensock/TimelineMax.min.js',

        'dce-morphSVG-lib' => '/assets/lib/greensock/plugins/MorphSVGPlugin.min.js',
        'dce-splitText-lib' => '/assets/lib/greensock/utils/SplitText.min.js',
        'dce-textPlugin-lib' => '/assets/lib/greensock/plugins/TextPlugin.min.js',
        'dce-svgdraw-lib' => '/assets/lib/greensock/plugins/DrawSVGPlugin.min.js',
        //'dce-attr-lib' => 'https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/plugins/AttrPlugin.min.js',

        // -----------------------------------------------------------
        // Extension Advanced
        'dce-rellaxjs-lib' => '/assets/lib/rellax/rellax.min.js',     
        
        'dce-clipboard-js' => '/assets/lib/clipboard.js/clipboard.min.js',
        // -----------------------------------------------------------
        // Document
        
        'scrollify' => '/assets/lib/scrollify/jquery.scrollify.js',
        'inertiaScroll' => '/assets/lib/inertiaScroll/jquery-inertiaScroll.js',
        'dce-lax-lib' => '/assets/lib/lax/lax.min.js',        
        //'dce-swup' => '/assets/js/global-swup.js',


        // -----------------------------------------------------------
        // Global Settings
        // TESTS for Smooth Transition of pages .... NOT WORK!!!!!!
        //'dce-animsition-lib' => '/assets/lib/animsition/js/animsition.min.js',
        // 'dce-animsition' => '/assets/js/global-animsition.js',

        //'ajaxify' => '/assets/lib/ajaxify/ajaxify.min.js',

        // 'dce-barbajs-lib' => '/assets/lib/barbajs/barba.min.js',
        // 'dce-barbajs' => '/assets/js/global-barbajs.js',

        // 'dce-swup-lib' => '/assets/lib/swup/swup.js',
        // 'dce-swup-lib-swupMergeHeadPlugin' => '/assets/lib/swup/plugins/swupMergeHeadPlugin.js',
        // 'dce-swup-lib-swupGaPlugin' => '/assets/lib/swup/plugins/swupGaPlugin.min.js',
        // 'dce-swup-lib-swupGtmPlugin' => '/assets/lib/swup/plugins/swupGtmPlugin.min.js',


        'dce-googlemaps-api' => 'https://maps.googleapis.com/maps/api/js?key=dce_api_gmaps',
        'dce-googlemaps-markerclusterer' => 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js',
        'dce-google-maps' => '/assets/js/google-maps.js',
        
        'dce-bgcanvas' => '/assets/js/dce-bgcanvas.js',
    );
    public static $scripts = array(
        
        'dce-main' => 'assets/js/main.js',
        'dce-ajaxmodal' => 'assets/js/ajaxmodal.js',
        'dce-settings' => '/assets/js/dce-settings.js',
        'dce-animatetext' => '/assets/js/elements-animateText.js',

        

        'dce-reveal' => '/assets/js/elements-reveal.js',

        'dce-popup' => '/assets/js/elements-popup.js',
        'dce-acfgallery' => '/assets/js/elements-acfgallery.js',
        'dce-acfslider' => '/assets/js/elements-acfslider.js',

        'dce-parallax' => '/assets/js/elements-parallax.js',

        //'dce-swiper' => '/assets/js/elements-swiper.js',

        'dce-threesixtyslider' => '/assets/js/elements-threesixtyslider.js',
        'dce-twentytwenty' => '/assets/js/elements-twentytwenty.js',

        'dce-tilt' => '/assets/js/elements-tilt.js',
        'dce-acf_posts' => '/assets/js/elements-acfposts.js',
        'dce-acf_repeater' => '/assets/js/elements-acfrepeater.js',

        'dce-content' => '/assets/js/elements-content.js',
        'dce-dynamic_users' => '/assets/js/elements-dynamicusers.js',
        'dce-acf_fields' => '/assets/js/elements-acf.js',
        
        'dce-modalwindow' => '/assets/js/elements-modalwindow.js',
        'dce-nextPrev' => '/assets/js/dce-nextprev.js',
        //'dce-youtube' => '/assets/js/dce-youtube.js',

        'dce-rellax' => '/assets/js/elements-rellax.js',

        //'dce-dualView' => '/assets/js/elements-dualView.js',

        'dce-svgmorph' => '/assets/js/dce-svgmorph.js',
        'dce-svgdistortion' => '/assets/js/dce-svgdistortion.js',
        'dce-svgfe' => '/assets/js/dce-svgfe.js',
        'dce-svgblob' => '/assets/js/dce-svgblob.js',
        // 'dce-distortion' => '/assets/js/elements-distortion.js',
        
        'dce-scrolling' => '/assets/js/elements-documentScrolling.js',

        //'dce-poster-slider' => '/assets/js/poster-slider.js',
        //'dce-fullpage' => '/assets/js/elements-fullpage.js',
        //'dce-pagepiling' => '/assets/js/elements-pagepiling.js',

        'dce-animatedoffcanvasmenu' => '/assets/js/dce-animatedoffcanvasmenu.js',
    );
    public static $minifyJs = 'assets/js/dce-frontend.min.js';

    public function __construct() {
        $this->init();
    }

    public function init() {

        // Admin Style
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));

        // REGENERATE STYLE
        add_action( 'elementor/core/files/clear_cache', array($this, 'regenerate_style') );
        add_action( 'elementor/core/files/clear_cache', array($this, 'regenerate_script') );

        // -------------------- SCRIPT
        add_action('elementor/frontend/after_enqueue_scripts', function() {
            $theme = wp_get_theme();
            if ('OceanWP' == $theme->name || 'oceanwp' == $theme->template) {
                $dir = OCEANWP_JS_DIR_URI;
                $theme_version = OCEANWP_THEME_VERSION;
                wp_enqueue_script('oceanwp-main', $dir . 'main.min.js', array('jquery'), $theme_version, true);
            }
        });

        //
        add_action('elementor/frontend/after_register_scripts', array($this, 'dce_frontend_register_script'));
        add_action('elementor/frontend/after_enqueue_scripts', [ $this, 'dce_frontend_enqueue_scripts']);

        //add_action( 'elementor/preview/enqueue_styles', array( $this, 'dce_preview_style') );
        add_action('elementor/frontend/after_register_styles', array($this, 'dce_frontend_register_style'));
        add_action('elementor/frontend/after_enqueue_styles', array($this, 'dce_frontend_enqueue_style'));

        //
        // -------------------- STYLE
        // Basic Style
        //add_action('wp_enqueue_scripts', array($this, 'enqueue_base_styles'), 100);
        // DCE Custom Icons - in Elementor Editor
        add_action('elementor/editor/after_enqueue_scripts', array($this, 'dce_editor'));
        add_action('elementor/preview/enqueue_styles', array($this, 'dce_preview'));
        // ELEMENTOR Style
        /*add_action('elementor/frontend/after_register_styles', function() {
            //wp_register_style( 'dynamic-content-elements-style', plugins_url( '/assets/css/dynamic-content-elements.css', DCE__FILE__ ) );

            //wp_register_style('dce-style', plugins_url('/assets/css/style.css', DCE__FILE__));
            //wp_register_style('animatecss', plugins_url('/assets/lib/animate/animate.min.css', DCE__FILE__));


            // photoswipe
            //wp_register_style( 'photoswipe', plugins_url( '/assets/css/photoSwipe/photoswipe.min.css.css', DCE__FILE__ ) );
            //wp_register_style( 'photoswipe-default-skin', plugins_url( '/assets/photoSwipe/default-skin/default-skin.min.css', DCE__FILE__ ) );
        });*/

        /*add_action('elementor/frontend/after_enqueue_styles', function() {
            wp_enqueue_style('dashicons');
            wp_enqueue_style('animatecss');
            wp_enqueue_style('dce-style');
        });*/
    }

    static public function dce_frontend_enqueue_style() {
        // @FISH
        if (file_exists(DCE_PATH . self::$minifyCss) && !WP_DEBUG) {
            //echo 'css minimizzato'; die();
            wp_enqueue_style('dce-all-css');
        } else {
            foreach (self::$styles as $key => $value) {
                wp_enqueue_style($key);
            }

        }
         wp_enqueue_style('dashicons'); // @ Marco, serve?


        //wp_enqueue_style('dce-photoSwipe_default');
        //wp_enqueue_style('dce-photoSwipe_skin');
        //
        //wp_enqueue_style('dce-file-icon');
        //wp_enqueue_style('dce-pageanimations');
        /*
        if ( DCE_Helper::is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            wp_enqueue_style('woocommerce-layout');
            wp_enqueue_style('woocommerce-smallscreen');
            wp_enqueue_style('woocommerce-general');
            wp_enqueue_style('woocommerce_prettyPhoto_css');
            //wp_enqueue_script('oceanwp-woocommerce');
        }
        */
    }

    public function regenerate_style($cache = false) {
        if (file_exists(DCE_PATH . self::$minifyCss)) {
            if ($cache) {
                return true;
            }
            unlink(DCE_PATH . self::$minifyCss);
            $mins = glob(DCE_PATH . 'assets/css/min/*');
            foreach ($mins as $amin) {
                unlink($amin);
            }
        }
        if (!file_exists(DCE_PATH . self::$minifyCss)) {
            // MINIFY CSS
            foreach (self::$styles as $key => $value) {
                $fileName = basename($value);
                $pezzi = explode('.', $fileName);
                array_pop($pezzi);
                $fileName = implode('.', $pezzi);
                $minifier = new Minify\CSS();
                $minifier->add(DCE_PATH . $value);
                // save minified file to disk
                $minifier->minify(DCE_PATH . 'assets/css/min/' . $fileName . '.min.css');
            }
            touch(DCE_PATH . self::$minifyCss);
            $mins = glob(DCE_PATH . 'assets/css/min/*');
            //var_dump($mins);
            foreach ($mins as $amin) {
                file_put_contents(DCE_PATH . self::$minifyCss, PHP_EOL . '/*' . basename($amin) . '*/' . PHP_EOL, FILE_APPEND | LOCK_EX);
                file_put_contents(DCE_PATH . self::$minifyCss, file_get_contents(DCE_PATH . 'assets/css/min/' . basename($amin)), FILE_APPEND | LOCK_EX);
            }
        }
    }

    public function regenerate_script($cache = false) {
        if (file_exists(DCE_PATH . self::$minifyJs)) {
            if ($cache) {
                return true;
            }
            unlink(DCE_PATH . self::$minifyJs);
            $mins = glob(DCE_PATH . 'assets/js/min/*');
            foreach ($mins as $amin) {
                unlink($amin);
            }
        }
        if (!file_exists(DCE_PATH . self::$minifyJs)) {
            // MINIFY
            foreach (self::$scripts as $key => $value) {
                $fileName = basename($value);
                $pezzi = explode('.', $fileName);
                array_pop($pezzi);
                $fileName = implode('.', $pezzi);
                $minifier = new Minify\JS();
                $minifier->add(DCE_PATH . $value);
                // save minified file to disk
                $minifier->minify(DCE_PATH . 'assets/js/min/' . $fileName . '.min.js');
            }
            touch(DCE_PATH . self::$minifyJs);
            $mins = glob(DCE_PATH . 'assets/js/min/*');
            foreach ($mins as $amin) {
                file_put_contents(DCE_PATH . self::$minifyJs, PHP_EOL . '/*' . basename($amin) . '*/' . PHP_EOL, FILE_APPEND | LOCK_EX);
                file_put_contents(DCE_PATH . self::$minifyJs, ';'.file_get_contents(DCE_PATH . 'assets/js/min/' . basename($amin)), FILE_APPEND | LOCK_EX);
            }
        }
    }

    public function dce_frontend_register_style() {

        foreach (self::$styles as $key => $value) {
            if (!WP_DEBUG) {
                $value = str_replace('assets/css/', 'assets/css/min/', $value);
                $pieces = explode('.', $value);
                $ext = array_pop($pieces);
                if ($ext == 'css') {
                    $value = implode('.', $pieces) . '.min.css';
                }
            }
            wp_register_style($key, plugins_url($value, DCE__FILE__));
        }   
        
        if (!WP_DEBUG) {
            if (!file_exists(DCE_PATH . self::$minifyCss)) {
                $this->regenerate_style();
            }
            wp_register_style('dce-all-css', DCE_URL . self::$minifyCss);
        }
        
        foreach (self::$vendorsCss as $key => $value) {
            /*if (substr($value, 0, 4) != 'http') {
                $value = plugins_url($value, DCE__FILE__);
            }*/
            wp_register_style($key, plugins_url($value, DCE__FILE__));
        }
        
    }

    public function dce_frontend_register_script() {
        $dce_apis = self::get_dce_apis();
        foreach (self::$scripts as $key => $value) {
            // setting configurated api key
            if (!empty($dce_apis)) {
                foreach ($dce_apis as $api_key => $api_value) {
                    $value = str_replace($api_key, $api_value, $value);
                }
            }
            if (!WP_DEBUG) {
                $value = str_replace('assets/js/', 'assets/js/min/', $value);
                $pieces = explode('.', $value);
                $ext = array_pop($pieces);
                if ($ext == 'js') {
                    $value = implode('.', $pieces) . '.min.js';
                }
            }
            if (substr($value, 0, 4) != 'http') {
                $value = plugins_url($value, DCE__FILE__);
            }
            wp_register_script($key, $value);
        }
        
        if (!WP_DEBUG) {
            if (!file_exists(DCE_PATH . self::$minifyJs)) {
                $this->regenerate_script();
            }
            wp_register_script('dce-all-js', DCE_URL . self::$minifyJs);
        }

        foreach (self::$vendorsJs as $key => $value) {
            // setting configurated api key
            if (!empty($dce_apis)) {
                foreach ($dce_apis as $api_key => $api_value) {
                    $value = str_replace($api_key, $api_value, $value);
                }
            }
            if (substr($value, 0, 4) != 'http') {
                $value = plugins_url($value, DCE__FILE__);
            }
            wp_register_script($key, $value);
        }
    }

    //
    public function dce_frontend_enqueue_scripts() {
        if (file_exists(DCE_PATH . self::$minifyJs) && !WP_DEBUG) {
            wp_enqueue_script('dce-all-js');
        } else {
            foreach (self::$scripts as $key => $value) {
                wp_enqueue_script($key);
            }
        }

        /*
        // LIB
        wp_enqueue_script('wow');
        wp_enqueue_script('isotope');
        wp_enqueue_script('infinitescroll');
        wp_enqueue_script('velocity');
        wp_enqueue_script('homeycombs');
        wp_enqueue_script('diamonds');
        wp_enqueue_script('dce-threesixtyslider-lib');
        wp_enqueue_script('dce-twentytwenty-lib');
        wp_enqueue_script('dce-parallaxjs-lib');
        wp_enqueue_script('dce-lax-lib');
        wp_enqueue_script('dce-swup-lib');
        wp_enqueue_script('dce-swup-lib-swupMergeHeadPlugin');
        wp_enqueue_script('dce-swup-lib-swupGaPlugin');
        wp_enqueue_script('dce-swup-lib-swupGtmPlugin');
        // DCE
        wp_enqueue_script('dce-revealFx');
        wp_enqueue_script('dce-acfgallery');
        wp_enqueue_script('dce-acfslider');
        wp_enqueue_script('dce-acf_posts');
        wp_enqueue_script('dce-content');
        wp_enqueue_script('dce-dynamic_users');
        wp_enqueue_script('dce-acf_fields');
        wp_enqueue_script('dce-google-maps');
        wp_enqueue_script('dce-twentytwenty');
        wp_enqueue_script('dce-rellax');
        wp_enqueue_script('dce-reveal');
        wp_enqueue_script('dce-animatetext');
        wp_enqueue_script('dce-modalWindow');
        wp_enqueue_script('dce-modal');
        wp_enqueue_script('dce-popup');
        wp_enqueue_script('dce-threesixtyslider');
        //wp_enqueue_script('dce-aframe');
        wp_enqueue_script('dce-parallax');
        // Page settings
        wp_enqueue_script('dce-scrollify');
        wp_enqueue_script('dce-inertiaScroll');

        wp_enqueue_script('dce-swup');
        //wp_enqueue_script('dce-barbajs-lib');
        //wp_enqueue_script('dce-barbajs');

        //wp_enqueue_script('dce-distortion');
        //wp_enqueue_script('dce-svgmorph');
        //wp_enqueue_script('dce-dualView');
        //
        //wp_enqueue_script('dce-animsition-lib');
        //wp_enqueue_script('dce-animsition');
        */

        /*
        wp_enqueue_script('photoswipe');
        wp_enqueue_script('photoswipe-ui');
        wp_enqueue_script('wow');
        wp_enqueue_script('isotope');

        wp_enqueue_script('dce-googlemaps-api');
        wp_enqueue_script('imagesloaded');
        */

        if ( DCE_Helper::is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            //plugin is activated
            self::dce_wc_enqueue_scripts();
        }



    }

    // Woocommerce script
    public function dce_wc_enqueue_scripts() {
        // In preview mode it's not a real Product page - enqueue manually.
        /*if ( Plugin::elementor()->preview->is_preview_mode( $this->get_main_id() ) ) {*/

            if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
                wp_enqueue_script( 'zoom' );
            }
            if ( current_theme_supports( 'wc-product-gallery-slider' ) ) {
                wp_enqueue_script( 'flexslider' );
            }
            if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
                wp_enqueue_script( 'photoswipe-ui-default' );
                wp_enqueue_style( 'photoswipe-default-skin' );
                //add_action( 'wp_footer', 'woocommerce_photoswipe' );
            }
            wp_enqueue_script( 'wc-single-product' );
            wp_enqueue_script( 'woocommerce' );


            wp_enqueue_style( 'photoswipe' );
            wp_enqueue_style( 'photoswipe-default-skin' );
            wp_enqueue_style( 'photoswipe-default-skin' );
            wp_enqueue_style( 'woocommerce_prettyPhoto_css' );
        /*}*/
    }

    /**
     * Enqueue admin styles
     *
     * @since 0.0.3
     *
     * @access public
     */
    public function enqueue_base_styles() {
        // Register styles
        wp_register_style(
            'dce-style-base', plugins_url('/assets/css/base.css', DCE__FILE__), [], DCE_VERSION
        );
        // Enqueue styles
        wp_enqueue_style('dce-style-base');
    }

    /**
     * Enqueue admin styles
     *
     * @since 0.0.3
     *
     * @access public
     */
    public function enqueue_admin_styles($hook) {
        //var_dump($hook); die();
        //$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        // Register styles
        // prima o poi dobbiamo minimizzare tutto e fare così per gestire i files assets per bene ;)
        wp_register_style('dce-admin-css', DCE_URL . 'assets/css/admin.min.css', [], DCE_VERSION);
        //'dce-admin', plugins_url('/assets/css/admin' . $suffix . '.css', DCE__FILE__), [], DCE_VERSION
        wp_enqueue_script('dce-admin-js', DCE_URL . 'assets/js/admin.min.js', [], DCE_VERSION);

        // select2
        wp_enqueue_style('dce-select2', DCE_URL . 'assets/css/select2.min.css', [], '4.0.7'); // '3.5.4'); versione vecchia per compatibilità con wpml
        wp_enqueue_script('dce-select2', DCE_URL . 'assets/js/select2.full.min.js', array('jquery'), '4.0.7', true); // '3.5.4'); versione vecchia per compatibilità con wpml
        //echo 'in admin'; die();
        // Enqueue styles Admin
        wp_enqueue_style('dce-admin-css');
    }

    /**
     * Enqueue admin styles
     *
     * @since 0.7.0
     *
     * @access public
     */
    public function dce_editor() {
        // Register styles
        wp_register_style(
                'dce-style-icons', plugins_url('/assets/css/dce-icon.css', DCE__FILE__), [], DCE_VERSION
        );
        // Enqueue styles Icons
        wp_enqueue_style('dce-style-icons');

        // Register styles
        wp_register_style(
                'dce-style-editor', plugins_url('/assets/css/dce-editor.css', DCE__FILE__), [], DCE_VERSION
        );
        // Enqueue styles Icons
        wp_enqueue_style('dce-style-editor');

        wp_register_script(
                'dce-script-editor', plugins_url('/assets/js/dce-editor.js', DCE__FILE__), [], DCE_VERSION
        );
        wp_enqueue_script('dce-script-editor');
        
        wp_register_script(
                'dce-script-editor-activate', plugins_url('/assets/js/dce-editor-activate.js', DCE__FILE__), [], DCE_VERSION
        );
        wp_enqueue_script('dce-script-editor-activate');
        //
        $this->dce_wc_enqueue_scripts();
    }

    /**
     * Enqueue admin styles
     *
     * @since 1.0.3
     *
     * @access public
     */
    public function dce_preview() {
        wp_register_style(
                'dce-preview', plugins_url('/assets/css/dce-preview.css', DCE__FILE__), [], DCE_VERSION
        );
        // Enqueue DCE Elementor Style
        wp_enqueue_style('dce-preview');
    }

    static public function dce_icon() {
        // Register styles
        wp_register_style(
                'dce-style-icons', plugins_url('/assets/css/dce-icon.css', DCE__FILE__), [], DCE_VERSION
        );
        // Enqueue styles Icons
        wp_enqueue_style('dce-style-icons');
    }

    static public function get_dce_apis() {
        return get_option(SL_PRODUCT_ID . '_apis', array());
    }

}
