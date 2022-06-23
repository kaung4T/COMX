<?php
namespace DynamicContentForElementor;
/**
 * Description of DCE_Trait_Plugin
 *
 */
trait DCE_Trait_Static {
    
    public static function get_post_orderby_options() {
        $orderby = array(
            'ID' => 'Post Id',
            'author' => 'Post Author',
            'title' => 'Title',
            'date' => 'Date',
            'modified' => 'Last Modified Date',
            'parent' => 'Parent Id',
            'rand' => 'Random',
            'comment_count' => 'Comment Count',
            'menu_order' => 'Menu Order',
            'meta_value_num' => 'Meta Value NUM',
            'meta_value_date' => 'Meta Value DATE',
        );

        return $orderby;
    }

    

    public static function get_anim_timingFunctions() {
        $tf_p = [
            'linear' => __('Linear', 'dynamic-content-for-elementor'),
            'ease' => __('Ease', 'dynamic-content-for-elementor'),
            'ease-in' => __('Ease In', 'dynamic-content-for-elementor'),
            'ease-out' => __('Ease Out', 'dynamic-content-for-elementor'),
            'ease-in-out' => __('Ease In Out', 'dynamic-content-for-elementor'),
            'cubic-bezier(0.755, 0.05, 0.855, 0.06)' => __('easeInQuint', 'dynamic-content-for-elementor'),
            'cubic-bezier(0.23, 1, 0.32, 1)' => __('easeOutQuint', 'dynamic-content-for-elementor'),
            'cubic-bezier(0.86, 0, 0.07, 1)' => __('easeInOutQuint', 'dynamic-content-for-elementor'),
            'cubic-bezier(0.6, 0.04, 0.98, 0.335)' => __('easeInCirc', 'dynamic-content-for-elementor'),
            'cubic-bezier(0.075, 0.82, 0.165, 1)' => __('easeOutCirc', 'dynamic-content-for-elementor'),
            'cubic-bezier(0.785, 0.135, 0.15, 0.86)' => __('easeInOutCirc', 'dynamic-content-for-elementor'),
            'cubic-bezier(0.95, 0.05, 0.795, 0.035)' => __('easeInExpo', 'dynamic-content-for-elementor'),
            'cubic-bezier(0.19, 1, 0.22, 1)' => __('easeOutExpo', 'dynamic-content-for-elementor'),
            'cubic-bezier(1, 0, 0, 1)' => __('easeInOutExpo', 'dynamic-content-for-elementor'),
            'cubic-bezier(0.6, -0.28, 0.735, 0.045)' => __('easeInBack', 'dynamic-content-for-elementor'),
            'cubic-bezier(0.175, 0.885, 0.32, 1.275)' => __('easeOutBack', 'dynamic-content-for-elementor'),
            'cubic-bezier(0.68, -0.55, 0.265, 1.55)' => __('easeInOutBack', 'dynamic-content-for-elementor'),
        ];
        return $tf_p;
    }

    /*
      easingSinusoidalInOut,
      easingQuadraticInOut,
      easingCubicInOut,
      easingQuarticInOut,
      easingQuinticInOut,
      easingCircularInOut,
      easingExponentialInOut.

      easingBackInOut

      easingElasticInOut

      easingBounceInOut
     */

    public static function get_kute_timingFunctions() {
        $tf_p = [
            'linear' => __('Linear', 'dynamic-content-for-elementor'),
            'easingSinusoidalIn' => 'easingSinusoidalIn',
            'easingSinusoidalOut' => 'easingSinusoidalOut',
            'easingSinusoidalInOut' => 'easingSinusoidalInOut',
            'easingQuadraticInOut' => 'easingQuadraticInOut',
            'easingCubicInOut' => 'easingCubicInOut',
            'easingQuarticInOut' => 'easingQuarticInOut',
            'easingQuinticInOut' => 'easingQuinticInOut',
            'easingCircularInOut' => 'easingCircularInOut',
            'easingExponentialInOut' => 'easingExponentialInOut',
            'easingSinusoidalInOut' => 'easingSinusoidalInOut',
            'easingBackInOut' => 'easingBackInOut',
            'easingElasticInOut' => 'easingElasticInOut',
            'easingBounceInOut' => 'easingBounceInOut',
        ];
        return $tf_p;
    }

    public static function get_gsap_ease() {
        $tf_p = [
            'easeNone' => __('None', 'dynamic-content-for-elementor'),
            'easeIn' => __('In', 'dynamic-content-for-elementor'),
            'easeOut' => __('Out', 'dynamic-content-for-elementor'),
            'easeInOut' => __('InOut', 'dynamic-content-for-elementor'),
        ];
        return $tf_p;
    }

    public static function get_gsap_timingFunctions() {
        $tf_p = [
            'Power0' => __('Linear', 'dynamic-content-for-elementor'),
            'Power1' => __('Power1', 'dynamic-content-for-elementor'),
            'Power2' => __('Power2', 'dynamic-content-for-elementor'),
            'Power3' => __('Power3', 'dynamic-content-for-elementor'),
            'Power4' => __('Power4', 'dynamic-content-for-elementor'),
            'SlowMo' => __(' SlowMo', 'dynamic-content-for-elementor'),
            'Back' => __('Back', 'dynamic-content-for-elementor'),
            'Elastic' => __('Elastic', 'dynamic-content-for-elementor'),
            'Bounce' => __('Bounce', 'dynamic-content-for-elementor'),
            'Circ' => __('Circ', 'dynamic-content-for-elementor'),
            'Expo' => __('Expo', 'dynamic-content-for-elementor'),
            'Sine' => __('Sine', 'dynamic-content-for-elementor'),
        ];
        return $tf_p;
    }

    public static function get_ease_timingFunctions() {
        $tf_p = [
            'linear' => __('Linear', 'dynamic-content-for-elementor'),
            'easeInQuad' => 'easeInQuad',
            'easeInCubic' => 'easeInCubic',
            'easeInQuart' => 'easeInQuart',
            'easeInQuint' => 'easeInQuint',
            'easeInSine' => 'easeInSine',
            'easeInExpo' => 'easeInExpo',
            'easeInCirc' => 'easeInCirc',
            'easeInBack' => 'easeInBack',
            'easeInElastic' => 'easeInElastic',
            'easeOutQuad' => 'easeOutQuad',
            'easeOutCubic' => 'easeOutCubic',
            'easeOutQuart' => 'easeOutQuart',
            'easeOutQuint' => 'easeOutQuint',
            'easeOutSine' => 'easeOutSine',
            'easeOutExpo' => 'easeOutExpo',
            'easeOutCirc' => 'easeOutCirc',
            'easeOutBack' => 'easeOutBack',
            'easeOutElastic' => 'easeOutElastic',
            'easeInOutQuad' => 'easeInOutQuad',
            'easeInOutCubic' => 'easeInOutCubic',
            'easeInOutQuart' => 'easeInOutQuart',
            'easeInOutQuint' => 'easeInOutQuint',
            'easeInOutSine' => 'easeInOutSine',
            'easeInOutExpo' => 'easeInOutExpo',
            'easeInOutCirc' => 'easeInOutCirc',
            'easeInOutBack' => 'easeInOutBack',
            'easeInOutElastic' => 'easeInOutElastic',
        ];
        return $tf_p;
    }

    public static function get_anim_in() {
        $anim = [
            [
                'label' => 'Fading',
                'options' => [
                    'fadeIn' => 'Fade In',
                    'fadeInDown' => 'Fade In Down',
                    'fadeInLeft' => 'Fade In Left',
                    'fadeInRight' => 'Fade In Right',
                    'fadeInUp' => 'Fade In Up',
                ],
            ],
            [
                'label' => 'Zooming',
                'options' => [
                    'zoomIn' => 'Zoom In',
                    'zoomInDown' => 'Zoom In Down',
                    'zoomInLeft' => 'Zoom In Left',
                    'zoomInRight' => 'Zoom In Right',
                    'zoomInUp' => 'Zoom In Up',
                ],
            ],
            [
                'label' => 'Bouncing',
                'options' => [
                    'bounceIn' => 'Bounce In',
                    'bounceInDown' => 'Bounce In Down',
                    'bounceInLeft' => 'Bounce In Left',
                    'bounceInRight' => 'Bounce In Right',
                    'bounceInUp' => 'Bounce In Up',
                ],
            ],
            [
                'label' => 'Sliding',
                'options' => [
                    'slideInDown' => 'Slide In Down',
                    'slideInLeft' => 'Slide In Left',
                    'slideInRight' => 'Slide In Right',
                    'slideInUp' => 'Slide In Up',
                ],
            ],
            [
                'label' => 'Rotating',
                'options' => [
                    'rotateIn' => 'Rotate In',
                    'rotateInDownLeft' => 'Rotate In Down Left',
                    'rotateInDownRight' => 'Rotate In Down Right',
                    'rotateInUpLeft' => 'Rotate In Up Left',
                    'rotateInUpRight' => 'Rotate In Up Right',
                ],
            ],
            [
                'label' => 'Attention Seekers',
                'options' => [
                    'bounce' => 'Bounce',
                    'flash' => 'Flash',
                    'pulse' => 'Pulse',
                    'rubberBand' => 'Rubber Band',
                    'shake' => 'Shake',
                    'headShake' => 'Head Shake',
                    'swing' => 'Swing',
                    'tada' => 'Tada',
                    'wobble' => 'Wobble',
                    'jello' => 'Jello',
                ],
            ],
            [
                'label' => 'Light Speed',
                'options' => [
                    'lightSpeedIn' => 'Light Speed In',
                ],
            ],
            [
                'label' => 'Specials',
                'options' => [
                    'rollIn' => 'Roll In',
                ],
            ]
        ];
        return $anim;
    }

    public static function get_anim_out() {
        $anim = [
            [
                'label' => 'Fading',
                'options' => [
                    'fadeOut' => 'Fade Out',
                    'fadeOutDown' => 'Fade Out Down',
                    'fadeOutLeft' => 'Fade Out Left',
                    'fadeOutRight' => 'Fade Out Right',
                    'fadeOutUp' => 'Fade Out Up',
                ],
            ],
            [
                'label' => 'Zooming',
                'options' => [
                    'zoomOut' => 'Zoom Out',
                    'zoomOutDown' => 'Zoom Out Down',
                    'zoomOutLeft' => 'Zoom Out Left',
                    'zoomOutRight' => 'Zoom Out Right',
                    'zoomOutUp' => 'Zoom Out Up',
                ],
            ],
            [
                'label' => 'Bouncing',
                'options' => [
                    'bounceOut' => 'Bounce Out',
                    'bounceOutDown' => 'Bounce Out Down',
                    'bounceOutLeft' => 'Bounce Out Left',
                    'bounceOutRight' => 'Bounce Out Right',
                    'bounceOutUp' => 'Bounce Out Up',
                ],
            ],
            [
                'label' => 'Sliding',
                'options' => [
                    'slideOutDown' => 'Slide Out Down',
                    'slideOutLeft' => 'Slide Out Left',
                    'slideOutRight' => 'Slide Out Right',
                    'slideOutUp' => 'Slide Out Up',
                ],
            ],
            [
                'label' => 'Rotating',
                'options' => [
                    'rotateOut' => 'Rotate Out',
                    'rotateOutDownLeft' => 'Rotate Out Down Left',
                    'rotateOutDownRight' => 'Rotate Out Down Right',
                    'rotateOutUpLeft' => 'Rotate Out Up Left',
                    'rotateOutUpRight' => 'Rotate Out Up Right',
                ],
            ],
            [
                'label' => 'Attention Seekers',
                'options' => [
                    'bounce' => 'Bounce',
                    'flash' => 'Flash',
                    'pulse' => 'Pulse',
                    'rubberBand' => 'Rubber Band',
                    'shake' => 'Shake',
                    'headShake' => 'Head Shake',
                    'swing' => 'Swing',
                    'tada' => 'Tada',
                    'wobble' => 'Wobble',
                    'jello' => 'Jello',
                ],
            ],
            [
                'label' => 'Light Speed',
                'options' => [
                    'lightSpeedOut' => 'Light Speed Out',
                ],
            ],
            [
                'label' => 'Specials',
                'options' => [
                    'rollOut' => 'Roll Out',
                ],
            ]
        ];
        return $anim;
    }

    public static function get_anim_open() {
        $anim_p = [
            'noneIn' => _x('None', 'Ajax Page', 'dynamic-content-for-elementor'),
            'enterFromFade' => _x('Fade', 'Ajax Page', 'dynamic-content-for-elementor'),
            'enterFromLeft' => _x('Left', 'Ajax Page', 'dynamic-content-for-elementor'),
            'enterFromRight' => _x('Right', 'Ajax Page', 'dynamic-content-for-elementor'),
            'enterFromTop' => _x('Top', 'Ajax Page', 'dynamic-content-for-elementor'),
            'enterFromBottom' => _x('Bottom', 'Ajax Page', 'dynamic-content-for-elementor'),
            'enterFormScaleBack' => _x('Zoom Back', 'Ajax Page', 'dynamic-content-for-elementor'),
            'enterFormScaleFront' => _x('Zoom Front', 'Ajax Page', 'dynamic-content-for-elementor'),
            'flipInLeft' => _x('Flip Left', 'Ajax Page', 'dynamic-content-for-elementor'),
            'flipInRight' => _x('Flip Right', 'Ajax Page', 'dynamic-content-for-elementor'),
            'flipInTop' => _x('Flip Top', 'Ajax Page', 'dynamic-content-for-elementor'),
            'flipInBottom' => _x('Flip Bottom', 'Ajax Page', 'dynamic-content-for-elementor'),
                //'flip' => _x( 'Flip', 'Ajax Page', 'dynamic-content-for-elementor' ),
                //'pushSlide' => _x( 'Push Slide', 'Ajax Page', 'dynamic-content-for-elementor' ),
        ];

        return $anim_p;
    }

    public static function get_anim_close() {
        $anim_p = [
            'noneOut' => _x('None', 'Ajax Page', 'dynamic-content-for-elementor'),
            'exitToFade' => _x('Fade', 'Ajax Page', 'dynamic-content-for-elementor'),
            'exitToLeft' => _x('Left', 'Ajax Page', 'dynamic-content-for-elementor'),
            'exitToRight' => _x('Right', 'Ajax Page', 'dynamic-content-for-elementor'),
            'exitToTop' => _x('Top', 'Ajax Page', 'dynamic-content-for-elementor'),
            'exitToBottom' => _x('Bottom', 'Ajax Page', 'dynamic-content-for-elementor'),
            'exitToScaleBack' => _x('Zoom Back', 'Ajax Page', 'dynamic-content-for-elementor'),
            'exitToScaleFront' => _x('Zoom Front', 'Ajax Page', 'dynamic-content-for-elementor'),
            'flipOutLeft' => _x('Flip Left', 'Ajax Page', 'dynamic-content-for-elementor'),
            'flipOutRight' => _x('Flip Right', 'Ajax Page', 'dynamic-content-for-elementor'),
            'flipOutTop' => _x('Flip Top', 'Ajax Page', 'dynamic-content-for-elementor'),
            'flipOutBottom' => _x('Flip Bottom', 'Ajax Page', 'dynamic-content-for-elementor'),
                //'flip' => _x( 'Flip', 'Ajax Page', 'dynamic-content-for-elementor' ),
                //'pushSlide' => _x( 'Push Slide', 'Ajax Page', 'dynamic-content-for-elementor' ),
        ];

        return $anim_p;
    }
    
    
    public static function bootstrap_button_sizes() {
        return [
            'xs' => __('Extra Small', 'dynamic-content-for-elementor'),
            'sm' => __('Small', 'dynamic-content-for-elementor'),
            'md' => __('Medium', 'dynamic-content-for-elementor'),
            'lg' => __('Large', 'dynamic-content-for-elementor'),
            'xl' => __('Extra Large', 'dynamic-content-for-elementor'),
        ];
    }

    public static function bootstrap_styles() {
        return [
            '' => __('Default', 'dynamic-content-for-elementor'),
            'info' => __('Info', 'dynamic-content-for-elementor'),
            'success' => __('Success', 'dynamic-content-for-elementor'),
            'warning' => __('Warning', 'dynamic-content-for-elementor'),
            'danger' => __('Danger', 'dynamic-content-for-elementor'),
        ];
    }
    
    public static function get_sql_operators() {
        $compare = self::get_wp_meta_compare();
        //$compare["LIKE WILD"] = "LIKE %...%";
        $compare["IS NULL"] = "IS NULL";
        $compare["IS NOT NULL"] = "IS NOT NULL";
        return $compare;
    }

    public static function get_wp_meta_compare() {
        // meta_compare (string) - Operator to test the 'meta_value'. Possible values are '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN', 'NOT EXISTS', 'REGEXP', 'NOT REGEXP' or 'RLIKE'. Default value is '='.
        return array(
            "=" => "=",
            ">" => "&gt;",
            ">=" => "&gt;=",
            "<" => "&lt;",
            "<=" => "&lt;=",
            "!=" => "!=",
            "LIKE" => "LIKE",
            "RLIKE" => "RLIKE",
            /*
              "E" => "=",
              "GT" => "&gt;",
              "GTE" => "&gt;=",
              "LT" => "&lt;",
              "LTE" => "&lt;=",
              "NE" => "!=",
              "LIKE_WILD" => "LIKE %...%",
             */
            "NOT LIKE" => "NOT LIKE",
            "IN" => "IN (...)",
            "NOT IN" => "NOT IN (...)",
            "BETWEEN" => "BETWEEN",
            "NOT BETWEEN" => "NOT BETWEEN",
            "NOT EXISTS" => "NOT EXISTS",
            "REGEXP" => "REGEXP",
            "NOT REGEXP" => "NOT REGEXP",
        );
    }

    public static function get_post_stati() {
        return array(
            'published' => __('Published'),
            'future' => __('Future'),
            'draft' => __('Draft'),
            'pending' => __('Pending'),
            'private' => __('Private'),
            'trash' => __('Trash'),
            'auto-draft' => __('Auto-Draft'),
            'inherit' => __('Inherit'),
        );
    }
    
    public static function get_gravatar_styles() {
        $gravatar_images = array(
            '404' => '404 (empty with fallback)',
            'retro' => '8bit',
            'monsterid' => 'Monster (Default)',
            'wavatar' => 'Cartoon face',
            'indenticon' => 'The Quilt',
            'mp' => 'Mystery',
            'mm' => 'Mystery Man',
            'robohash' => 'RoboHash',
            'blank' => 'transparent GIF',
            'gravatar_default' => 'The Gravatar logo'
        );
        return $gravatar_images;
    }

    public static function get_post_formats() {
        return array(
            'standard' => 'Standard', 
            'aside' => 'Aside', 
            'chat' => 'Chat', 
            'gallery' => 'Gallery', 
            'link' => 'Link', 
            'image' => 'Image', 
            'quote' => 'Quote', 
            'status' => 'Status', 
            'video' => 'Video', 
            'audio' => 'Audio'
        );
    }
    
    public static function get_button_sizes() {
        return [
            'xs' => __('Extra Small', 'elementor'),
            'sm' => __('Small', 'elementor'),
            'md' => __('Medium', 'elementor'),
            'lg' => __('Large', 'elementor'),
            'xl' => __('Extra Large', 'elementor'),
        ];
    }

    public static function get_jquery_display_mode() {
        return [
            '' => __('None', 'dynamic-content-for-elementor'),
            'slide' => __('Slide', 'dynamic-content-for-elementor'),
            'fade' => __('Fade', 'dynamic-content-for-elementor'),
        ];
    }
    
    public static function get_string_comparison() {
        return array(
            "empty" => "empty",
            "not_empty" => "not empty",
            "equal_to" => "equals to",
            "not_equal" => "not equals",
            "gt" => "greater than",
            "ge" => "greater than or equal",
            "lt" => "less than",
            "le" => "less than or equal",
            "contain" => "contains",
            "not_contain" => "not contains",
            "is_checked" => "is checked",
            "not_checked" => "not checked",
        );
    }
    
}
