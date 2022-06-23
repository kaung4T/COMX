<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Parallax
 *
 * Elementor widget for Dynamic Content Elements
 *
 */

class DCE_Widget_Parallax extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-parallax';
    }

    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('Parallax', 'dynamic-content-for-elementor');
    }
    public function get_description() {
      return __('Manage parallax movements with your mouse on desktop or device orientation on mobile', 'dynamic-content-for-elementor');
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/parallax/';
    }
    public function get_icon() {
        return 'icon-dyn-parallax';
    }
    static public function get_position() {
        return 1;
    }
    /**
     * A list of scripts that the widgets is depended in
     * @since 1.3.0
     * */
    public function get_script_depends() {
        return [ 'jquery', 'dce-parallaxjs-lib'];
    }

    public function get_style_depends() {
        return [ 'dce-parallax'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
                'section_parallaxsettings', [
            'label' => __('Parallax', 'dynamic-content-for-elementor'),
                ]
        );
        /*$this->add_control(
            'acf_text_before', [
                'label' => __('Text before', 'dynamic-content-for-elementor'),
                'description' => __('Un testo prima dell\'elemento', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );*/
        $this->add_control(
                'parallaxjs_relative_input', [
            'label' => __('Relative Input', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                'parallaxjs_clip_relative_input', [
            'label' => __('Clip Relative Input', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                'parallaxjs_hover_only', [
            'label' => __('Hover Only', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                'parallaxjs_input_element', [
            'label' => __('Input Element', 'dynamic-content-for-elementor'),
            //'description' => __('Un testo dopo dell\'elemento','dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '#myinput',
                ]
        );
        $this->add_control(
                'parallaxjs_calibrate_x', [
            'label' => __('Calibrate X', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                'parallaxjs_calibrate_y', [
            'label' => __('Calibrate Y', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                'parallaxjs_invert_x', [
            'label' => __('Invert X', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                'parallaxjs_invert_y', [
            'label' => __('Invert Y', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                'parallaxjs_limit_x', [
            'label' => __('Linit X', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 100,
            'min' => 0,
            'max' => 1000,
            'step' => 10,
                ]
        );
        $this->add_control(
                'parallaxjs_limit_y', [
            'label' => __('Linit Y', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 100,
            'min' => 0,
            'max' => 1000,
            'step' => 10,
                ]
        );
        $this->add_control(
                'parallaxjs_scalar_x', [
            'label' => __('Scalar X', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 2,
            ],
            'range' => [

                'min' => 0,
                'max' => 100,
            ],
                ]
        );
        $this->add_control(
                'parallaxjs_scalar_y', [
            'label' => __('Scalar Y', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 8,
            ],
            'range' => [

                'min' => 0,
                'max' => 100,
            ],
                ]
        );
        $this->add_control(
                'parallaxjs_friction_x', [
            'label' => __('Friction X', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0.1,
            'min' => 0,
            'max' => 1,
            'step' => 0.1,
                ]
        );
        $this->add_control(
                'parallaxjs_friction_y', [
            'label' => __('Friction Y', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0.1,
            'min' => 0,
            'max' => 1,
            'step' => 0.1,
                ]
        );




        $this->add_control(
                'parallaxjs_origin_x', [
            'label' => __('Origin X', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0.5,
            'min' => 0,
            'max' => 1,
            'step' => 0.1,
                ]
        );
        $this->add_control(
                'parallaxjs_origin_y', [
            'label' => __('Origin Y', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0.5,
            'min' => 0,
            'max' => 1,
            'step' => 0.1,
                ]
        );
        $this->add_control(
                'parallaxjs_pointer_events', [
            'label' => __('Pointer Events', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
                ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
                'section_parallaxitems', [
            'label' => __('Parallax ITEMS', 'dynamic-content-for-elementor'),
                ]
        );

        // coefficiente per default
        $this->add_control(
                'parallax_coef', [
            'label' => __('Default depth factor', 'dynamic-content-for-elementor'),
            'description' => __('It is used if the DepthFactor value is 0', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0.2,
            'min' => 0.05,
            'max' => 1,
            'step' => 0.05,
                ]
        );
        $repeater = new Repeater();

        //$repeater->start_controls_tabs('parallaxjs_repeater');

        //$repeater->start_controls_tab('tab_content', [ 'label' => __('Item', 'dynamic-content-for-elementor')]);

        $repeater->add_control(
                'parallax_image', [
            'label' => __('Image', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::MEDIA,
            'default' => [
                'url' => '', //'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
            ],
                ]
        );
        $repeater->add_control(
                'factor_item', [
            'label' => __('Depth Factor', 'dynamic-content-for-elementor'),
            'description' => 'If 0, the default value will be used',
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'min' => -1,
            'max' => 1,
            'step' => 0.01,
                ]
        );

        /* $repeater->add_control(
          'acf_field_item',
          [
          'label' => __( 'ACF Fields List', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::SELECT,
          'options' => [ '' => __( 'Title', 'dynamic-content-for-elementor').'(CORE)' ] + ['contenuto' => __( 'Content', 'dynamic-content-for-elementor').'(CORE)' ] + ['taxx' => __( 'Taxonomy MetaData', 'dynamic-content-for-elementor').'(CORE)' ] + $this->get_acf_field(),
          'default' => '0',

          ]
          );
          $repeater->add_control(
          'html_tag_item',
          [
          'label' => __( 'HTML Tag', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::SELECT,
          'options' => [
          '' => __( 'None', 'dynamic-content-for-elementor'),
          'h1' => __( 'H1', 'dynamic-content-for-elementor' ),
          'h2' => __( 'H2', 'dynamic-content-for-elementor' ),
          'h3' => __( 'H3', 'dynamic-content-for-elementor' ),
          'h4' => __( 'H4', 'dynamic-content-for-elementor' ),
          'h5' => __( 'H5', 'dynamic-content-for-elementor' ),
          'h6' => __( 'H6', 'dynamic-content-for-elementor' ),
          'p'  => __( 'p', 'dynamic-content-for-elementor' ),
          'div' => __( 'div', 'dynamic-content-for-elementor' ),
          'span' => __( 'span', 'dynamic-content-for-elementor' ),
          ],
          'default' => '',

          ]
          );
          $repeater->add_control(
          'link_to',
          [
          'label' => __( 'Link to', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::SELECT,
          'default' => 'none',
          'options' => [
          'none' => __( 'None', 'dynamic-content-for-elementor' ),
          'home' => __( 'Home URL', 'dynamic-content-for-elementor' ),
          'post' => 'Post URL',

          'custom' => __( 'Custom URL', 'dynamic-content-for-elementor' ),
          ],

          ]
          ); */
        
        //$repeater->end_controls_tab();

        /* $repeater->start_controls_tab( 'tab_media', [ 'label' => __( 'Media', 'dynamic-content-for-elementor' ) ] );



          $repeater->end_controls_tab(); */

        //$repeater->start_controls_tab('tab_transform', [ 'label' => __('Transform', 'dynamic-content-for-elementor')]);
        
        /* $repeater->add_control(
          'block_enable',
          [
          'label'         => __( 'Block', 'dynamic-content-for-elementor' ),
          'type'          => Controls_Manager::SWITCHER,
          'default'       => '',
          'label_on'      => __( 'Yes', 'dynamic-content-for-elementor' ),
          'label_off'     => __( 'No', 'dynamic-content-for-elementor' ),
          'return_value'  => 'yes',
          'frontend_available' => true,
          ]
          );
          $repeater->add_control(
          'padding_item',
          [
          'label'         => __( 'Padding', 'dynamic-content-for-elementor' ),
          'type'          => Controls_Manager::DIMENSIONS,
          'size_units'    => [ 'px', '%' ],
          'selectors'     => [
          '{{WRAPPER}} .dce-acfposts_content {{CURRENT_ITEM}}'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ],
          'condition' => [
          'block_enable' => 'yes',
          ],
          ]
          );
          $repeater->add_control(
          'color_item',
          [
          'label' => __( 'Text Color', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::COLOR,
          
          'selectors' => [
          '{{WRAPPER}} .dce-acfposts_content {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
          '{{WRAPPER}} .dce-acfposts_content {{CURRENT_ITEM}} a' => 'color: {{VALUE}};',
          ],
          ]
          );
          $repeater->add_control(
          'hover_color_item',
          [
          'label' => __( 'Hover Color', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::COLOR,
          
          'selectors' => [
          '{{WRAPPER}} .dce-acfposts_content {{CURRENT_ITEM}} a:hover' => 'color: {{VALUE}};',
          ],
          ]
          );

          $repeater->add_group_control(
          Group_Control_Typography::get_type(),
          [
          'name' => 'typography_item',
          'scheme' => Scheme_Typography::TYPOGRAPHY_1,
          'selector' => '{{WRAPPER}} .dce-acfposts_content {{CURRENT_ITEM}}'

          ]
          ); */

        //$repeater->end_controls_tab();


        //$repeater->end_controls_tabs();

        $this->add_control(
                'parallaxjs', [
            'label' => __('Items', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::REPEATER,
            'default' => [
            ],
            'fields' => array_values($repeater->get_controls()),
            'title_field' => 'Parallax Item'
                ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        global $global_ID;

        $relativeinput = $settings['parallaxjs_relative_input'] == 'yes' ? 'true' : 'false';
        $clipRelativeInput = $settings['parallaxjs_clip_relative_input'] == 'yes' ? 'true' : 'false';
        $hoverOnly = $settings['parallaxjs_hover_only'] == 'yes' ? 'true' : 'false';
        // $inputElement: document.getElementById('myinput'),
        $calibrateX = $settings['parallaxjs_calibrate_x'] == 'yes' ? 'true' : 'false';
        $calibrateY = $settings['parallaxjs_calibrate_y'] == 'yes' ? 'true' : 'false';
        $invertX = $settings['parallaxjs_invert_x'] == 'yes' ? 'true' : 'false';
        $invertY = $settings['parallaxjs_invert_y'] == 'yes' ? 'true' : 'false';

        // $limitX: false,
        $limitX = $settings['parallaxjs_limit_x'];
        $limitY = $settings['parallaxjs_limit_y'];
        $scalarX = $settings['parallaxjs_scalar_x']['size'];
        $scalarY = $settings['parallaxjs_scalar_y']['size'];
        $frictionX = $settings['parallaxjs_friction_x'];
        $frictionY = $settings['parallaxjs_friction_y'];
        $originX = $settings['parallaxjs_origin_x'];
        $originY = $settings['parallaxjs_origin_y'];
        $precision = 1;
        $pointerEvents = $settings['parallaxjs_pointer_events'] == 'yes' ? 'true' : 'false';

        echo '<div id="container" class="container">';
        echo '<div id="scene" class="scene" ';
        echo 'data-relative-input="' . $relativeinput . '" ';
        echo 'data-clip-relative-input="' . $clipRelativeInput . '" ';
        echo 'data-hover-only="' . $hoverOnly . '" ';
        echo 'data-input-element="#myinput" ';
        echo 'data-calibrate-x="' . $calibrateX . '" ';
        echo 'data-calibrate-y="' . $calibrateY . '" ';
        echo 'data-invert-x="' . $invertX . '" ';
        echo 'data-invert-y="' . $invertY . '" ';
        echo 'data-limit-x="' . $limitX . '" ';
        echo 'data-limit-y="' . $limitY . '" ';
        echo 'data-scalar-x="' . $scalarX . '" ';
        echo 'data-scalar-y="' . $scalarY . '" ';
        echo 'data-friction-x="' . $frictionX . '" ';
        echo 'data-friction-y="' . $frictionY . '" ';
        echo 'data-origin-x="' . $originX . '" ';
        echo 'data-origin-y="' . $originY . '" ';
        echo 'data-precision="1" ';
        echo 'data-pointer-events="' . $pointerEvents . '">';

        //$counter_item = 1;
        $parallaxItems = $settings['parallaxjs'];
        if (!empty($parallaxItems)) {
            foreach ($parallaxItems as $key => $parallaxitem) :
                //echo 'a ';
                $factor = $parallaxitem['factor_item'];
                $imageParallaxItem = plugins_url('/assets/lib/parallaxjs/img/layer' . ($key+1) . '.png', DCE__FILE__);
                if ($factor == 0)
                    $factor = $key * $settings['parallax_coef']; //0.2;

                if ($parallaxitem['parallax_image']['url'] != '') {

                    $imageParallaxItem = $parallaxitem['parallax_image']['url'];
                    //echo $imageParallaxItem;
                }
                //
                //echo $imageParallaxItem;
                echo '<div class="layer" data-depth="' . $factor . '"><img src="' . $imageParallaxItem . '"></div>';

                //$counter_item++;
            endforeach;
        }
        ?>
        </div>
        </div>
        <?php
    }

    protected function _content_template() {
        ?>
        <#
        //var target = settings.link.is_external ? 'target="_blank"' : '';
        var relativeinput = settings.parallaxjs_relative_input ? 'true' : 'false';
        var clipRelativeInput = settings.parallaxjs_clip_relative_input ? 'true' : 'false';
        var hoverOnly = settings.parallaxjs_hover_only ? 'true' : 'false';
        // var inputElement: document.getElementById('myinput'),
        var calibrateX = settings.parallaxjs_calibrate_x ? 'true' : 'false';
        var calibrateY = settings.parallaxjs_calibrate_y ? 'true' : 'false';
        var invertX = settings.parallaxjs_invert_x ? 'true' : 'false';
        var invertY = settings.parallaxjs_invert_y ? 'true' : 'false';

        // var limitX: false,
        var limitX = settings.parallaxjs_limit_x;
        var limitY = settings.parallaxjs_limit_y;
        var scalarX = settings.parallaxjs_scalar_x.size;
        var scalarY = settings.parallaxjs_scalar_y.size;
        var frictionX = settings.parallaxjs_friction_x;
        var frictionY = settings.parallaxjs_friction_y;
        var originX = settings.parallaxjs_origin_x;
        var originY = settings.parallaxjs_origin_y;
        var precision = 1;
        var pointerEvents = settings.parallaxjs_pointer_events ? 'true' : 'false';

        //print( html );
        var counter_item = 1;
        if ( settings.parallaxjs.length ) { #>
        <div id="container" class="container">
            
            <div id="scene" class="scene"
                data-relative-input="{{relativeinput}}"
                data-clip-relative-input="{{clipRelativeInput}}"
                data-hover-only="{{hoverOnly}}"
                data-input-element="#myinput"
                data-calibrate-x="{{calibrateX}}"
                data-calibrate-y="{{calibrateY}}"
                data-invert-x="{{invertX}}"
                data-invert-y="{{invertY}}"
                data-limit-x="{{limitX}}"
                data-limit-y="{{limitY}}"
                data-scalar-x="{{scalarX}}"
                data-scalar-y="{{scalarY}}"
                data-friction-x="{{frictionX}}"
                data-friction-y="{{frictionY}}"
                data-origin-x="{{originX}}"
                data-origin-y="{{originY}}"
                data-precision="1"
                data-pointer-events="{{pointerEvents}}">
                <# _.each( settings.parallaxjs, function( parallaxitem, index ) { 
                    var factor = parallaxitem.factor_item;
                    if (factor == 0) {
                        factor = index * settings.parallax_coef; //0.2;
                    }
                    var imageParallaxItem = '<?php echo DCE_URL; ?>/assets/lib/parallaxjs/img/layer'+(index+1)+'.png';
                    if (parallaxitem.parallax_image.url != '') {
                        imageParallaxItem = parallaxitem.parallax_image.url;
                    }
                    #>
                    <div class="layer" data-depth="{{factor}}"><img src="{{imageParallaxItem}}"></div>
                    
                <# }); #>
            </div>
        </div>
        <# } #>
        <?php
    }
}
