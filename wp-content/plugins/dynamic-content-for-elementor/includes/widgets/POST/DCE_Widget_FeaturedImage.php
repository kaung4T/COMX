<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Utils;
use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\Group_Control_Outline;
use DynamicContentForElementor\Controls\DCE_Group_Control_Filters_CSS;
use DynamicContentForElementor\Controls\DCE_Group_Control_Transform_Element;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Icon Format
 *
 * Elementor widget for Dynamic Content for Elementor
 *
 */
class DCE_Widget_FeaturedImage extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-featured-image';
    }
    
    static public function is_enabled() {
        return true;
    }
    
    public function get_title() {
        return __('Featured Image', 'dynamic-content-for-elementor');
    }
    public function get_description() {
        return __('Add a featured image on your article', 'dynamic-content-for-elementor');
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/featured-image/';
    }
    public function get_icon() {
        return 'icon-dyn-image';
    }
    public function get_dce_style_depends() {
        return ['dce-featuredImage'];
    }
    
    static public function get_position() {
        return 3;
    }
    /*public function get_style_depends() {
        return [ 'dce-featuredImage' ];
    }*/
    protected function _register_controls() {
        $post_type_object = get_post_type_object(get_post_type());
        $this->start_controls_section(
            'section_content', [
                'label' => __('Image settings', 'dynamic-content-for-elementor'),
            ]
        );
        $this->add_control(
            'preview', [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => get_the_post_thumbnail(),
                'separator' => 'none',
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(), [
                'name' => 'size',
                'label' => __('Image Size', 'dynamic-content-for-elementor'),
                'default' => 'large',
            ]
        );
        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Alignment', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'dynamic-content-for-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'dynamic-content-for-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'dynamic-content-for-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => '',
                //'prefix_class' => 'image-align-',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                    '' => ''
                ],
            ]
        );
        
       
       $this->add_control(
            'link_to', [
                'label' => __('Link to', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('None', 'dynamic-content-for-elementor'),
                    'home' => __('Home URL', 'dynamic-content-for-elementor'),
                    'post' => 'Post URL',
                    'acf_url' => __('ACF URL', 'dynamic-content-for-elementor'),
                    'file' => __('Media File URL', 'dynamic-content-for-elementor'),
                    'custom' => __('Custom URL', 'dynamic-content-for-elementor'),
                ],
            ]
        );
        $this->add_control(
            'acf_field_url', [
                'label' => __('ACF Field Url', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'groups' => DCE_Helper::get_acf_field_urlfile(true),
                //'options' => $this->get_acf_field_urlfile(),
                'default' => 'Select the Field',
                'condition' => [
                    'link_to' => 'acf_url',
                ]
            ]
        );
        $this->add_control(
            'acf_field_url_target', [
                'label' => __('Blank', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'condition' => [
                    'link_to' => 'acf_url',
                ]
            ]
        );
        $this->add_control(
            'link', [
                'label' => __('Link to', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('http://your-link.com', 'dynamic-content-for-elementor'),
                'condition' => [
                    'link_to' => 'custom',
                ],
                'show_label' => false,
            ]
        );
        $this->end_controls_section();


        /* -------------------- Background ------------------ */
        $post_type_object = get_post_type_object(get_post_type());
        $this->start_controls_section(
            'section_backgroundimage', [
                'label' => __('Background', 'dynamic-content-for-elementor'),
            ]
        );
        $this->add_control(
            'use_bg', [
                'label' => __('Background', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    '1' => [
                        'title' => __('Yes', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-check',
                    ],
                    '0' => [
                        'title' => __('No', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-ban',
                    ]
                ],
                //'prefix_class' => 'usebg-',
                'default' => '0'
            ]
        );
        $this->add_control(
          'bg_position',
          [
             'label'       => __( 'Background position', 'dynamic-content-for-elementor' ),
             'type' => Controls_Manager::SELECT,
             'default' => 'top center',
             'options' => [
                '' => __( 'Default', 'dynamic-content-for-elementor' ),
                'top left' => __( 'Top Left', 'dynamic-content-for-elementor' ),
                'top center' => __( 'Top Center', 'dynamic-content-for-elementor' ),
                'top right' => __( 'Top Right', 'dynamic-content-for-elementor' ),
                'center left' => __( 'Center Left', 'dynamic-content-for-elementor' ),
                'center center' => __( 'Center Center', 'dynamic-content-for-elementor' ),
                'center right' => __( 'Center Right', 'dynamic-content-for-elementor' ),
                'bottom left' => __( 'Bottom Left', 'dynamic-content-for-elementor' ),
                'bottom center' => __( 'Bottom Center', 'dynamic-content-for-elementor' ),
                'bottom right' => __( 'Bottom Right', 'dynamic-content-for-elementor' ),
            ],
             'selectors' => [
                '{{WRAPPER}} .dynamic-content-featuredimage-bg' => 'background-position: {{VALUE}};',
            ],
            'condition' => [
                    'use_bg' => '1',
                ],
          ]
        );
         $this->add_control(
            'bg_extend',
            [
                'label' => __( 'Extend Background', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Yes', 'dynamic-content-for-elementor' ),
                'label_off' => __( 'No', 'dynamic-content-for-elementor' ),
                'return_value' => 'yes',
                'condition' => [
                    'use_bg' => '1',
                ],
                'prefix_class' => 'extendbg-',
            ]
        );
         $this->add_responsive_control(
            'minimum_height', [
                'label' => __('Minimum Height', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', '%', 'vh'],
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
                    '{{WRAPPER}} .dynamic-content-featuredimage-bg' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'use_bg' => '1',
                    'bg_extend' => 'yes'
                ],
            ]
        );
        $this->add_responsive_control(
            'height', [
                'label' => __('Height', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 200,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', '%', 'vh'],
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
                    '{{WRAPPER}} .dynamic-content-featuredimage-bg' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'use_bg' => '1',
                    'bg_extend' => ''
                ],
            ]
        );

        $this->end_controls_section();

        // ------------------------------------------------------------- [ Overlay style ]
        $this->start_controls_section(
            'section_overlay', [
                'label' => 'Overlay',
                
            ]
        );
        
        
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'background_overlay',
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .dce-overlay',
                ]
            );
            $this->add_control(
                'opacity_overlay', [
                    'label' => __('Opacity', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 1,
                            'min' => 0,
                            'step' => 0.01,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-overlay' => 'opacity: {{SIZE}};',
                    ],
                    'condition' => [
                        'background_overlay_background' => [ 'classic', 'gradient' ],
                    ]
                ]
                
            );
        
        
        
        
        // overlay color ...
        /*$this->add_control(
            'overlay_color', [
                'label' => __('Overlay Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dce-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );*/
        
        $this->end_controls_section();

       // ------------------------------------------------------------- [ Roll-Hover]
        $this->start_controls_section(
            'section_hover_style', [
                'label' => 'Rollover',
                'condition' => [
                    'link_to!' => 'none',
                ]
            ]
        );
        $this->add_control(
            'bghover_heading',
            [
                'label' => __( 'Background color', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_hover_color',
                'label' => __('Background', 'dynamic-content-for-elementor'),
                'description' => 'Background',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .dce-overlay_hover',
                'condition' => [
                    'link_to!' => 'none',
                ]
            ]
        );
        $this->add_control(
            'bgoverlayhover_heading',
            [
                'label' => __( 'Change background color of overlay', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                        'background_overlay_background' => [ 'classic', 'gradient' ],
                    ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_color_on_hover',
                'label' => __('Background overlay', 'dynamic-content-for-elementor'),
                'description' => 'Background color of overlay',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} a:hover .dce-overlay',
                'condition' => [
                    'background_overlay_background' => [ 'classic', 'gradient' ],
                    'link_to!' => 'none',
                ]
            ]
        );
        $this->add_control(
                'opacity_overlay_on_hover', [
                    'label' => __('Overlay Opacity', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 1,
                            'min' => 0,
                            'step' => 0.01,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} a:hover .dce-overlay' => 'opacity: {{SIZE}};',
                    ],
                    'condition' => [
                        'background_overlay_background' => [ 'classic', 'gradient' ],
                        'link_to!' => 'none',
                    ]
                ]
                
            );
        /*$this->add_control(
            'overlay_hover_color', [
                'label' => __('Hover Overlay Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'default' => 'rgba(0,0,0,0.4)',
                'selectors' => [
                    '{{WRAPPER}} .dce-overlay_hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'use_overlay_hover' => '1',
                ]
            ]
        );*/
        
        
        $this->add_control(
            'imageanimations_heading',
            [
                'label' => __( 'Rollover Animations', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        // . . . . . . . . . . . . . . . .  Hover ElementorAMINATION
        $this->add_control(
            'hover_animation', [
                'label' => __('Animation', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::HOVER_ANIMATION,
                
            ]
        );
        // . . . . . . . . . . . . . . . .  Hover FILTERS
        $this->add_control(
            'imagefilters_heading',
            [
                'label' => __( 'Rollover Filters', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            DCE_Group_Control_Filters_CSS::get_type(),
            [
                'name' => 'filters_image_hover',
                'label' => __('Filters', 'dynamic-content-for-elementor'),
                //'selector' => '{{WRAPPER}} a:hover img, {{WRAPPER}} a:hover .dynamic-content-featuredimage-bg',
                'selector' => '{{WRAPPER}} a:hover .wrap-filters',
            ]
        );
        /*$this->add_control(
            'enable_grey_effect', [
                'label' => __('Black & White', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .grey-filters img, {{WRAPPER}} figure.grey-filters' => '-webkit-filter: grayscale(1); -moz-filter: grayscale(1); -ms-filter: grayscale(1); filter: grayscale(1);',
                    '{{WRAPPER}}:hover .grey-filters img, {{WRAPPER}}:hover figure.grey-filters' => '-webkit-filter: grayscale(0); -moz-filter: grayscale(0); -ms-filter: grayscale(0); filter: grayscale(0);',
                ],
                'condition' => [
                    'link_to!' => 'none',
                ]
            ]
        );*/
        
        // . . . . . . . . . . . . . . . .  Hover EFFECTS
        $this->add_control(
            'imageeffects_heading',
            [
                'label' => __( 'Rollover Effects', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'hover_effects', [
                'label' => __('Effects', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __('None', 'dynamic-content-for-elementor'),
                    'zoom' => __('Zoom', 'dynamic-content-for-elementor'),
                    /*'slow-zoom' => __('Slow Zoom', 'dynamic-content-for-elementor'),*/
                ],
                'default' => '',
                'prefix_class' => 'hovereffect-',
                'condition' => [
                    'link_to!' => 'none',
                ]
            ]
        );
        

        $this->end_controls_section();


        $this->start_controls_section(
            'section_placeholder', [
                'label' => __('Placeholder', 'dynamic-content-for-elementor'),
            ]
        );
        $this->add_control(
            'use_placeholter', [
                'label' => __('Use placeholder Image', 'dynamic-content-for-elementor'),
                'description' => 'Use another image if the featured one does not exist.',
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    '1' => [
                        'title' => __('Yes', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-check',
                    ],
                    '0' => [
                        'title' => __('No', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-ban',
                    ]
                ],
                //'prefix_class' => 'usebg-',
                'default' => '0'
            ]

        );
        $this->add_control(
          'custom_placeholder_image',
          [
             'label' => __( 'Placeholder Image', 'dynamic-content-for-elementor' ),
             'type' => Controls_Manager::MEDIA,
             'default' => [
                'url' => DCE_Helper::get_placeholder_image_src(),
             ],
             'condition' => [
                    'use_placeholter' => '1',
                ],
          ]
        );
        $this->end_controls_section();



        $this->start_controls_section(
            'section_style', [
                'label' => __('Image', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'space', [
                'label' => __('Size', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                   
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-featured-image' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .dce-featured-image.is-bg' => 'display: inline-block;'

                ],
                'condition' => [
                    /*'use_bg' => '0',*/
                    'bg_extend' => ''
                ], 
            ]
        );
        $this->add_responsive_control(
            'maxheight', [
                'label' => __('Max Height', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                   
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-featured-image img' => 'max-height: {{SIZE}}{{UNIT}};',

                ],
                'condition' => [
                    /*'use_bg' => '0',*/
                    'bg_extend' => ''
                ], 
            ]
        );
        /*$this->add_group_control(
            DCE_Group_Control_Transform_Element::get_type(),
            [
              'name' => 'transform_image',
              'label' => 'Transform image',
              'selector' => '{{WRAPPER}} > .elementor-widget-container', //'{{WRAPPER}} .dce-featured-image',
              'condition' => [
                    'bg_extend' => ''
                ]
            ]
          );*/
        $this->add_group_control(
            DCE_Group_Control_Filters_CSS::get_type(),
            [
              'name' => 'filters_image',
              'label' => 'Filters image',
              //'selector' => '{{WRAPPER}} img, {{WRAPPER}} .dynamic-content-featuredimage-bg',
              'selector' => '{{WRAPPER}} .wrap-filters',
            ]
        );
        $this->add_control(
            'blend_mode',
            [
                'label' => __( 'Blend Mode', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Normal', 'elementor' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-featured-image' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );
        /*$this->add_responsive_control(
            'opacity', [
                'label' => __('Opacity (%)', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-featured-image' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'angle', [
                'label' => __('Angle (deg)', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg'],
                'default' => [ ],
                'range' => [
                    'deg' => [
                        'max' => 360,
                        'min' => -360,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-featured-image' => '-webkit-transform: rotate({{SIZE}}deg); -moz-transform: rotate({{SIZE}}deg); -ms-transform: rotate({{SIZE}}deg); -o-transform: rotate({{SIZE}}deg); transform: rotate({{SIZE}}deg);'
                ],
                'condition' => [
                    'link_to!' => '',
                    'enable_slowzoom_effect' => '',
                ],
            ]
        );*/
        
        // ---------------------- Border -----------------
        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'image_border',
                'label' => __('Image Border', 'dynamic-content-for-elementor'),
                'selector' => '{{WRAPPER}} .dce-featured-image',
                'condition' => [
                    'use_bg' => '0',
                    'bg_extend' => ''
                ],
            ]
        );
        /*$this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'bg_border',
                'label' => __('Image Border', 'dynamic-content-for-elementor'),
                'selector' => '{{WRAPPER}} .dce-featured-image .dynamic-content-featuredimage-bg',
                'condition' => [
                    'use_bg' => '1',
                ],
            ]
        );*/
        /*$this->add_group_control(
        Group_Control_Outline::get_type(),
        [
          'name' => 'image_outline',
          'label' => 'Outline',
          'selector' => '{{WRAPPER}} .dce-featured-image',
        ]
      );*/
        $this->add_control(
            'image_border_radius', [
                'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dce-featured-image, {{WRAPPER}} .dce-featured-image .dce-overlay_hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition' => [
                    'use_bg' => '0',
                    'bg_extend' => ''
                ],
            ]
        );
        $this->add_control(
            'image_padding', [
                'label' => __('Padding', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dce-featured-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition' => [
                    'use_bg' => '0',
                    'bg_extend' => ''
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(), [
                'name' => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .dce-featured-image',
                'condition' => [
                    'use_bg' => '0',
                    'bg_extend' => ''
                ],
            ]
        );
        /*$this->add_group_control(
            Group_Control_Box_Shadow::get_type(), [
                'name' => 'bg_box_shadow',
                'selector' => '{{WRAPPER}} .dce-featured-image .dynamic-content-featuredimage-bg',
                'condition' => [
                    'use_bg' => '1',
                ],
            ]
        );*/
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_dce_settings', [
                'label' => __('Dynamic content', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_SETTINGS,

            ]
        );
         $this->add_control(
            'data_source',
            [
              'label' => __( 'Source', 'dynamic-content-for-elementor' ),
              'description' => __( 'Select the data source', 'dynamic-content-for-elementor' ),
              'type' => Controls_Manager::SWITCHER,
              'default' => 'yes',
              'label_on' => __( 'Same', 'dynamic-content-for-elementor' ),
              'label_off' => __( 'other', 'dynamic-content-for-elementor' ),
              'return_value' => 'yes',
            ]
        );
        /*$this->add_control(
            'other_post_source', [
              'label' => __('Select from other source post', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SELECT,
              'label_block' => true,
              'groups' => DCE_Helper::get_all_posts(get_the_ID(), true),
              'default' => 'none',
              'condition' => [
                'data_source' => '',
                'other_post_parent' => '',
              ], 
            ]
        );*/
        $this->add_control(
                'other_post_source',
                [
                    'label' => __('Select from other source post', 'dynamic-content-for-elementor'),
                    'type' 		=> 'ooo_query',
                    'placeholder'	=> __( 'Post Title', 'dynamic-content-for-elementor' ),
                    'label_block' 	=> true,
                    'query_type'	=> 'posts',
                    'condition' => [
                        'data_source' => '',
                    ],
                ]
        );
        $this->add_control(
            'other_post_parent',
            [
                'label' => __( 'From post parent', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Yes', 'dynamic-content-for-elementor' ),
                'label_off' => __( 'No', 'dynamic-content-for-elementor' ),
                'return_value' => 'yes',
                'condition' => [
                    'data_source' => '',
                    
                ],
            ]
        );
        $this->end_controls_section();
        
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings ) )
            return;

        //
        // ------------------------------------------
        $dce_data = DCE_Helper::dce_dynamic_data($settings['other_post_source'],$settings['other_post_parent']);
        $id_page = $dce_data['id'];
        $global_is = $dce_data['is'];
        $global_id = $dce_data['global_id'];
        $type_page = $dce_data['type'];
        // ------------------------------------------
        //

        $overlay_hover_block = '';
        if ($settings['link_to'] != 'none') {
            $overlay_hover_block = '<div class="dce-overlay_hover"></div>';
        }
        
      
        $overlay_block = '<div class="dce-overlay"></div>';

        $wrap_effect_start = '<div class="mask"><div class="wrap-filters">';
        $wrap_effect_end = '</div></div>';


        /*$grey_effect_class = ' grey-filters';
        if( $settings['enable_grey_effect'] == 'yes' ){
            $grey_effect_class = ' grey-filters';
        }*/

        $image_size = $settings['size_size'];
        //echo $image_size;
        $featuredImageID = get_post_thumbnail_id($id_page);

        // se il post parent non ha un'immagine, uso uso l'immagine dello stesso
        if( $featuredImageID == "" && $settings['other_post_parent'] == 'yes' ){
            $featuredImageID = get_post_thumbnail_id($global_id);
        }

        //echo 'ID of image: '.get_the_ID().'<br />type of post: '.$type_page;
        if( $type_page == 'attachment' ) $featuredImageID = get_the_ID();
        $image_url = Group_Control_Image_Size::get_attachment_image_src( $featuredImageID, 'size', $settings );
        $image_alt = get_post_meta( $featuredImageID, '_wp_attachment_image_alt', true);
        
        $featured_img_url = $image_url;

        if($featuredImageID == "" && $settings['other_post_parent'] != 'yes' ){
            if( $settings['use_placeholter'] && $settings['custom_placeholder_image'] != '' ){
                $featured_img_url = $settings['custom_placeholder_image']['url'];
            }else{
                $featured_img_url = '';
            } 
            //Utils::get_placeholder_image_src();
            //echo $featured_img_url;
        }
        //echo $featuredImageID.' - '.DCE_Helper::get_placeholder_image_src();
        $get_featured_img = '';
        if( $featured_img_url != '') $get_featured_img = '<img src="'.$featured_img_url.'" alt="'.$image_alt.'" />'; //get_the_post_thumbnail( $id_page, $image_size, array( 'src'   => $image_url ) ); //


        //echo esc_attr( $image_urll ).' - '.get_post_thumbnail_id($id_page);
        //echo $image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'preview' );
        $featured_image = '';
        if($get_featured_img == "" && $settings['other_post_parent'] != 'yes' ){
            $featured_image = $wrap_effect_start.'<img src="'.$featured_img_url.'" />'.$wrap_effect_end.$overlay_block.$overlay_hover_block;
        }
        if( $get_featured_img != "" ){
            $featured_image = $wrap_effect_start.$get_featured_img.$wrap_effect_end.$overlay_block.$overlay_hover_block;
        
        }

        
        $use_bg = $settings["use_bg"];
        $bg_class = '';
        if( $use_bg == '1'){
            $bg_class = 'is-bg ';
        }
        if ( empty( $featured_image ) )
            return;


        $target = $settings['link']['is_external'] ? 'target="_blank"' : '';
        switch ( $settings['link_to'] ) {
            case 'custom' :
                if ( ! empty( $settings['link']['url'] ) ) {
                    $link = esc_url( $settings['link']['url'] );
                } else {
                    $link = false;
                }
                break;
            case 'acf_url' :
                //echo get_field( $settings['acf_field_url'] , $id_page);
                if ( ! empty( $settings['acf_field_url'] ) ) {
                    $link = esc_url( get_field( $settings['acf_field_url'] , $id_page) );
                    //$link = get_post_meta( $id_page, $settings['acf_field_url'], true );      
                    $target = $settings['acf_field_url_target'] ? 'target="_blank"' : '';
                } else {
                    $link = false;
                }
                break;
            case 'file' :
                $imageFull_url = wp_get_attachment_image_src( $featuredImageID, 'full' );
                $link = esc_url( $imageFull_url[0] );
                break;

            case 'post' :
                $link = esc_url( get_the_permalink( $id_page ) );
                break;

            case 'home' :
                $link = esc_url( get_home_url() );
                break;

            case 'none' :
            default:
                $link = false;
                break;
        }
        

        if( $settings['hover_animation'] != '' ){
            $animation_class = ! empty( $settings['hover_animation'] ) ? 'elementor-animation-' . $settings['hover_animation'] : '';
        }else{
            $animation_class = '';
        }
        $html = '<div class="dce-featured-image ' . $bg_class . $animation_class . '">';

        if($use_bg == 0){
            if ( $link ) {
                $html .= sprintf( '<a href="%1$s" %2$s>%3$s</a>', $link, $target, $featured_image );
            } else {
                $html .= $featured_image;
            }
        }else{
            // style="background-image: url('. $imageField .'); background-repeat: no-repeat; background-size: cover;"
            // .'" style="'.$effcts_style.'"
            $bg_featured_image = $wrap_effect_start.'<figure class="dynamic-content-featuredimage-bg ' . $animation_class . '" style="background-image: url('.$featured_img_url .'); background-repeat: no-repeat; background-size: cover;">&nbsp;</figure>'.$wrap_effect_end.$overlay_block.$overlay_hover_block;
            if ( $link ) {
                $html .= sprintf( '<a href="%1$s" %2$s>%3$s</a>', $link, $target, $bg_featured_image );
            } else {
                $html .= $bg_featured_image;
            }
        }
        $html .= '</div>';
        
        echo $html;
    }

    protected function _content_template() {
        /*
          $image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
          ?>
          <#
          var featured_images = [];
          <?php
          $all_image_sizes = Group_Control_Image_Size::get_all_image_sizes();
          foreach ( $all_image_sizes as $key => $value ) {
          printf( 'featured_images[ "%1$s" ] = \'%2$s\';', $key, get_the_post_thumbnail( null, $key ) );
          }
          printf( 'featured_images[ "full" ] = \'%2$s\';', $key, get_the_post_thumbnail( null, 'full' ) );
          ?>
          var featured_image = featured_images[ settings.size_size ];

          var link_url;
          switch( settings.link_to ) {
          case 'custom':
          link_url = settings.link.url;
          break;
          case 'file':
          link_url = '<?php echo esc_url( $image_url[0] ); ?>';
          break;
          case 'post':
          link_url = '<?php echo esc_url( get_the_permalink() ); ?>';
          break;
          case 'home':
          link_url = '<?php echo esc_url( get_home_url() ); ?>';
          break;
          case 'none':
          default:
          link_url = false;
          }

          var animation_class = '';
          if ( '' !== settings.hover_animation ) {
          animation_class = 'elementor-animation-' + settings.hover_animation;
          }

          var html = '<div class="dce-featured-image ' + animation_class + '">';
          if( settings.use_bg == 0 ){

          if ( link_url ) {
          html += '<a href="' + link_url + '">' + featured_image + '</a>';
          } else {
          html += featured_image;
          }

          }else{
          var bg_featured_image = '<div class="dynamic-content-featuredimage-bg dce-featured-image ' + animation_class + '" style="background: #ccc url(<?php echo esc_url( $image_url[0] ); ?>) no-repeat center; background-size: cover "></div>';

          if ( link_url ) {
          html += '<a href="' + link_url + '">' + bg_featured_image + '</a>';
          } else {
          html += bg_featured_image;
          }
          }
          html += '</div>';

          print( html );
          #>
          <?php
       */  
    }

}
