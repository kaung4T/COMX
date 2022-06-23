<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use DynamicContentForElementor\DCE_Helper;
use Elementor\Repeater;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor WebGL with Threejs plane
 *
 * Elementor widget by Dynamic.ooo
 *
 */

class DCE_Widget_BgCanvas extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-bgcanvas';
    }
    
    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('BG Canvas', 'dynamic-content-for-elementor');
    }

    public function get_icon() {
        return 'icon-dyn-canvas';
    }
    
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/webgl-background-canvas/';
    }
    
    public function get_description() {
        return __('Easily integrate in your site WebGL with Canvas for Shader effects', 'dynamic-content-for-elementor');
    }
    
    public function get_script_depends() {
        return [    'dce-bgcanvas',
                    'dce-threejs-lib',
            
                    'dce-threejs-EffectComposer',
                    'dce-threejs-RenderPass',
                    'dce-threejs-ShaderPass',
                    'dce-threejs-BloomPass',
                    'dce-threejs-FilmPass',
                    'dce-threejs-HalftonePass',
                    'dce-threejs-DotScreenPass',
                    'dce-threejs-GlitchPass',

                    'dce-threejs-AsciiEffect',

                    'dce-threejs-CopyShader',
                    'dce-threejs-HalftoneShader',
                    'dce-threejs-RGBShiftShader',
                    'dce-threejs-DotScreenShader',
                    'dce-threejs-ConvolutionShader',
                    'dce-threejs-FilmShader',
                    'dce-threejs-ColorifyShader',
                    'dce-threejs-VignetteShader',
                    'dce-threejs-DigitalGlitch',
                    'dce-threejs-PixelShader',
                    'dce-threejs-LuminosityShader',
                    'dce-threejs-SobelOperatorShader',


                    'dce-tweenMax-lib',
                    'dce-timelineMax-lib' ];
    }
       
    /*public function get_style_depends() {
        return [ '' ];
    }*/
    /*static public function get_position() {
        return 9;
    }*/
    protected function _register_controls() {
        $this->start_controls_section(
                'section_bgcanvas', [
                'label' => __('Image', 'dynamic-content-for-elementor'),
            ]
        );
        $this->add_control(
              'bgcanvas_image',
              [
                 'label' => __( 'Image', 'dynamic-content-for-elementor' ),
                 'type' => Controls_Manager::MEDIA,
                 'dynamic' => [
                    'active' => true,
                  ],
                 'default' => [
                    'url' => DCE_Helper::get_placeholder_image_src(),
                 ],
              ]
            );
        $this->add_group_control(
          Group_Control_Image_Size::get_type(),
          [
            'name' => 'image', // Actually its `image_size`
            'default' => 'thumbnail',
            'condition' => [
              'bgcanvas_image[id]!' => '',
            ],
          ]
        );
        $this->add_responsive_control(
            'bgcanvas_height', [
                'label' => __('Height', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 400,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'render_type' => 'template',
                'size_units' => [ 'px', '%', 'vh'],
                'separator' => 'after',
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                
                'selectors' => [
                    '{{WRAPPER}} .dce-container-bgcanvas' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        /*$repeater = new Repeater();

        $repeater->add_control(
            'postprocessing_pass', [
                  'label' => __('Postprocessing', 'dynamic-content-for-elementor'),
                  'type' => Controls_Manager::SELECT,
                  'options' => [
                      'none' => __('None', 'dynamic-content-for-elementor'),
                      'film' => __('Film', 'dynamic-content-for-elementor'),
                      'halftone' => __('Half Tone', 'dynamic-content-for-elementor'),
                      'rgbShiftShader' => __('RGB Shift', 'dynamic-content-for-elementor'),
                      'sepia' => __('Sepia', 'dynamic-content-for-elementor'),
                      'colorify' => __('Colorify', 'dynamic-content-for-elementor'),
                      'vignette' => __('Vignette', 'dynamic-content-for-elementor'),
                      'glitch' => __('Glitch', 'dynamic-content-for-elementor'),
                      'dot' => __('Dot', 'dynamic-content-for-elementor'),
                      'bloom' => __('Bloom', 'dynamic-content-for-elementor'),
                      'afterimage' => __('After Image', 'dynamic-content-for-elementor'),
                      'pixels' => __('Pixels', 'dynamic-content-for-elementor'),
                  ],
                  'default' => 'none',
                  'separator' => 'after',
                  'render_type' => 'template',
            ]
        );*/
        $this->end_controls_section();


        $this->start_controls_section(
                'section_postprocessing', [
                'label' => __('Postprocessing & Shaders', 'dynamic-content-for-elementor'),
            ]
        );
        // ----------- film
        $this->add_control(
            'postprocessing_film',
            [
                'label' => '<b>'.__( 'Film', 'dynamic-content-for-elementor' ).'</b>',
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                //'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
                
            ]
        );
        $this->add_control(
            'postprocessing_film_grayscale',
            [
                'label' => __( 'Gray Scale', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                //'render_type' => 'template',
                'frontend_available' => true,
                'condition' => [
                    'postprocessing_film!' => ''
                ],
            ]
        );
         $this->add_control(
            'postprocessing_film_noiseIntensity', 
            [
                'label' => __('Noise Intensity', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,
                'label_block' => false,
                'default' => [
                    'size' => 0.35,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.01,
                        'max' => 1,
                        'step' => 0.01
                    ]
                ],
                'condition' => [
                    'postprocessing_film!' => ''
                ],
            ]
        );
         
         $this->add_control(
            'postprocessing_film_scanlinesIntensity', 
            [
                'label' => __('Scanlines Intensity', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,
                'label_block' => false,
                'default' => [
                    'size' => 0.035,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.010,
                        'max' => 1,
                        'step' => 0.001
                    ]
                ],
                'condition' => [
                    'postprocessing_film!' => ''
                ],
            ]
        );
          $this->add_control(
            'postprocessing_film_scanlinesCount', 
            [
                'label' => __('Scanlines Count', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'label_block' => false,
                'frontend_available' => true,
                'default' => [
                    'size' => 648,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1
                    ]
                ],
                'condition' => [
                    'postprocessing_film!' => ''
                ],
            ]
        );
        // ----------- halftone
        $this->add_control(
            'postprocessing_halftone',
            [
                'label' => '<b>'.__( 'Halftone', 'dynamic-content-for-elementor' ).'</b>',
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                //'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'postprocessing_halftone_shape', [
                  'label' => __('Shape', 'dynamic-content-for-elementor'),
                  'type' => Controls_Manager::SELECT,
                  'frontend_available' => true,
                  'options' => [
                      '1' => __('Dots', 'dynamic-content-for-elementor'),
                      '2' => __('Ellipse', 'dynamic-content-for-elementor'),
                      '3' => __('Lines', 'dynamic-content-for-elementor'),
                      '4' => __('Squre', 'dynamic-content-for-elementor')
                  ],
                  'default' => '1',
                  'condition' => [
                    'postprocessing_halftone!' => ''
                ],
            ]
        );
        $this->add_control(
            'postprocessing_halftone_grayscale',
            [
                'label' => __( 'Gray Scale', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                //'render_type' => 'template',
                'frontend_available' => true,
                'condition' => [
                    'postprocessing_halftone!' => ''
                ],
            ]
        );
        $this->add_control(
            'postprocessing_halftone_radius', 
            [
                'label' => __('Dot Radius', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,
                'label_block' => false,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'condition' => [
                    'postprocessing_halftone!' => ''
                ],
            ]
        );
        // ----------- rgbShiftShader
        $this->add_control(
            'postprocessing_rgbShiftShader',
            [
                'label' => '<b>'.__( 'RGB Shift Shader', 'dynamic-content-for-elementor' ).'</b>',
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                //'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'postprocessing_rgbshift_amount', 
            [
                'label' => __('Amount', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,
                'label_block' => false,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 30,
                        'step' => 0.001
                    ]
                ],
                'condition' => [
                    'postprocessing_rgbShiftShader!' => ''
                ],
            ]
        );
        // ----------- sepia
        /*$this->add_control(
            'postprocessing_sepia',
            [
                'label' => '<b>'.__( 'Sepia', 'dynamic-content-for-elementor' ).'</b>',
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                //'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );*/
        // ----------- sepia
        /*$this->add_control(
            'postprocessing_sobel',
            [
                'label' => '<b>'.__( 'Sobel', 'dynamic-content-for-elementor' ).'</b>',
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                //'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );*/
        // ----------- colorify
        /*$this->add_control(
            'postprocessing_colorify',
            [
                'label' => '<b>'.__( 'Colorify', 'dynamic-content-for-elementor' ).'</b>',
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                //'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );*/
        // ----------- vignette
        /*$this->add_control(
            'postprocessing_vignette',
            [
                'label' => '<b>'.__( 'Vignette', 'dynamic-content-for-elementor' ).'</b>',
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                //'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );*/
        // ----------- glitch
        $this->add_control(
            'postprocessing_glitch',
            [
                'label' => '<b>'.__( 'Glitch', 'dynamic-content-for-elementor' ).'</b>',
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                //'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );
        // ----------- dot
        $this->add_control(
            'postprocessing_dot',
            [
                'label' => '<b>'.__( 'Dot', 'dynamic-content-for-elementor' ).'</b>',
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                //'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'postprocessing_dot_scale', 
            [
                'label' => __('Dot Scale', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,
                'label_block' => false,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 10,
                        'step' => 0.1
                    ]
                ],
                'condition' => [
                    'postprocessing_dot!' => ''
                ],
            ]
        );
        $this->add_control(
            'postprocessing_dot_angle', 
            [
                'label' => __('Dot Angle', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,
                'label_block' => false,
                'default' => [
                    'size' => 0.5,
                ],
                'range' => [
                    'px' => [
                        'min' => -1,
                        'max' => 1,
                        'step' => 0.01
                    ]
                ],
                'condition' => [
                    'postprocessing_dot!' => ''
                ],
            ]
        );
        // ----------- bloom
        /*$this->add_control(
            'postprocessing_bloom',
            [
                'label' => '<b>'.__( 'Bloom', 'dynamic-content-for-elementor' ).'</b>',
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                //'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );*/
        // ----------- afterimage
       /* $this->add_control(
            'postprocessing_afterimage',
            [
                'label' => '<b>'.__( 'After Image', 'dynamic-content-for-elementor' ).'</b>',
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                //'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );*/
        // ----------- pixels
        $this->add_control(
            'postprocessing_pixels',
            [
                'label' => '<b>'.__( 'Pixels', 'dynamic-content-for-elementor' ).'</b>',
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                //'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'postprocessing_pixels_size', 
            [
                'label' => __('Pixels Size', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,
                'label_block' => false,
                'default' => [
                    'size' => 16,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'condition' => [
                    'postprocessing_pixels!' => ''
                ],
            ]
        );
        /*$this->add_control(
                'postprocessing_pass_layer',
                [
                    'label' => __( 'Postprocessing', 'dynamic-content-for-elementor' ),
                    'type' => Controls_Manager::REPEATER,
                    'default' => '',
                    'frontend_available' => true,
                    'fields' => $repeater->get_controls(),
                    'title_field' => 'Pass: {{{ postprocessing_pass }}}',
                    'frontend_available' => true,
                ]
            );*/

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_active_settings();
        if ( empty( $settings ) )
            return;

        $image_url = Group_Control_Image_Size::get_attachment_image_src($settings['bgcanvas_image']['id'], 'image', $settings);
        ?>
        <!-- <video id="video" class="" crossOrigin="anonymous" autoplay="" muted="" playsinline="" loop src="/video/marketing.mp4" width="1280" height="720" style="display:none"></video> -->
        <!-- <video id="video" loop crossOrigin="anonymous" webkit-playsinline style="display:none">
            <source src="textures/sintel.ogv" type='video/ogg; codecs="theora, vorbis"'>
            <source src="textures/sintel.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
        </video> -->
        <div class="dce-container-bgcanvas" data-bgcanvasimage="<?php echo $image_url; ?>">
            <div class="scene js-scene"></div>
        </div>
    <?php
    }
    protected function _content_template() {
        ?>
       
        <#
        var image = {
          id: settings.bgcanvas_image.id,
          url: settings.bgcanvas_image.url,
          size: settings.image_size,
          dimension: settings.image_custom_dimension,
          model: view.getEditModel()
        };
        var url_image = elementor.imagesManager.getImageUrl( image );
        //alert(settings.image_size);
        #>
        <div class="dce-container-bgcanvas" data-bgcanvasimage="{{url_image}}">
            <div class="scene js-scene"></div>
        </div>
        <?php
    }
}
