<?php

namespace Aepro\ACF_Gallery\Skins;

use Elementor\Controls_Manager;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;
use Elementor\Plugin;
use Aepro\Helper;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Skin_Base extends Elementor_Skin_Base
{

    protected function _register_controls_actions()
    {
        add_action('elementor/element/ae-acf-gallery/section_layout/before_section_end', [$this, 'register_controls']);

        add_action('elementor/element/ae-acf-gallery/section_style/before_section_end', [$this, 'register_style_controls']);

        add_action('elementor/element/ae-acf-gallery/section_overlay/before_section_end', [$this, 'register_overlay_controls']);

        add_action('elementor/element/ae-acf-gallery/section_overlay_style/before_section_end', [$this, 'register_overlay_style_controls']);

    }

    public function register_controls(Widget_Base $widget){
        $this->parent = $widget;

    }

    public function common_style_control(){

        $this->add_control(
            'heading_style_arrow',
            [
                'label' => __('Arrow', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'condition' =>
                    [
                        $this->get_control_id('navigation_button') => 'yes'
                    ]
            ]
        );
        $this->add_control(
            'arrow_size',
            [
                'label' => __('Arrow Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'range' =>
                [
	                'px' => [
		                'min' => 1,
		                'max' => 100,
		                'step' => 1
	                ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev' => '-webkit-mask-size:{{SIZE}}px auto; mask-size:{{SIZE}}px auto; width:{{SIZE}}px; height:calc({{SIZE}}px*2)',
                    '{{WRAPPER}} .ae-swiper-button-next' => '-webkit-mask-size:{{SIZE}}px auto; mask-size:{{SIZE}}px auto; width:{{SIZE}}px; height:calc({{SIZE}}px*2)',
                ],
                'condition' =>
                    [
                        $this->get_control_id('navigation_button') => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __('Arrow Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev' => 'background-color:{{VAlUE}};',
                    '{{WRAPPER}} .ae-swiper-button-next' => 'background-color:{{VAlUE}};',
                    '{{WRAPPER}} .ae-swiper-button-prev.swiper-button-disabled' => 'background-color:{{VAlUE}}; opacity: .5;',
				    '{{WRAPPER}} .ae-swiper-button-next.swiper-button-disabled' => 'background-color:{{VAlUE}}; opacity: .5;'
                ],
                'condition' =>
                    [
                        $this->get_control_id('navigation_button') => 'yes'
                    ]
            ]
        );


        $this->add_control(
            'heading_style_dots',
            [
                'label' => __('Dots', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'bullets'
                    ]
            ]
        );

        $this->add_control(
            'dots_size',
            [
                'label' => __('Dots Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 5
                    ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'width:{{SIZE}}px; height:{{SIZE}}px;',
                ],
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'bullets'
                    ]
            ]
        );

        $this->add_control(
            'dots_color',
            [
                'label' => __('Dots Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'bullets'
                    ]
            ]
        );




        $this->add_control(
            'heading_style_scroll',
            [
                'label' => __('Scrollbar', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' =>
                    [
                        $this->get_control_id('scrollbar') => 'yes'
                    ]
            ]
        );
        $this->add_control(
            'scroll_size',
            [
                'label' => __('Scrollbar Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 5
                    ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-scrollbar' => 'height:{{SIZE}}px;',
                ],
                'condition' =>
                    [
                        $this->get_control_id('scrollbar') => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'scrollbar_color',
            [
                'label' => __('Scrollbar Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-scrollbar-drag' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        $this->get_control_id('scrollbar') => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'scroll_color',
            [
                'label' => __('Scroll Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-scrollbar' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        $this->get_control_id('scrollbar') => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'heading_style_progress',
            [
                'label' => __('Progress Bar', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'yes'
                    ]
            ]
        );
        $this->add_control(
            'progressbar_color',
            [
                'label' => __('Prgress Bar Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-progressbar' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'progress'
                    ]
            ]
        );

        $this->add_control(
            'progress_color',
            [
                'label' => __('Prgress Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-progress' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'progress'
                    ]
            ]
        );

        $this->add_control(
            'progressbar_size',
            [
                'label' => __('Prgress Bar Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 5
                    ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-progress' => 'height:{{SIZE}}px;',
                ],
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'progress'
                    ]
            ]
        );




    }

    protected function common_controls()
    {
        $this->add_control(
            'common_comtrols',
            [
                'label' => __('Setting', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );





        $this->add_control(
            'speed',
            [
                'label' => __('Speed', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 300,
                ],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 900,
                        'step' => 1
                    ]
                ]

            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('On', 'ae-pro'),
                'label_off' => __('Off', 'ae-pro'),
                'return_value' => 'yes',
            ]

        );

        $this->add_control(
            'duration',
            [
                'label' => __('Duration', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 3000,
                ],
                'range' => [
                    'px' =>[
                        'min' => 1000,
                        'max' => 10000,
                        'step' => 1000,
                    ]
                ],
                'condition' => [
                    $this->get_control_id('autoplay') => 'yes'
                ],
            ]
        );

        // Todo:: different effects management
        $this->add_control(
            'effect',
            [
                'label' => __('Effects', 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'fade' => __('Fade', 'ae-pro'),
                    'slide' => __('Slide', 'ae-pro'),
                    'cube' => __('Cube', 'ae-pro'),
                    'coverflow' => __('Coverflow', 'ae-pro'),
                    'flip' => __('Flip', 'ae-pro'),
                ],
                'default'=>'slide',
            ]
        );

        $this->add_responsive_control(
            'space',
            [
                'label' => __('Space Between Slides', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>[
                    'size' => 15,
                ],
                'tablet_default' => [
                    'size' => 10,
                ],
                'mobile_default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px'=>[
                        'min'=> 0,
                        'max'=> 50,
                        'step'=> 5,
                    ]
                ]
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __('Loop', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'ae-pro'),
                'label_off' => __('No', 'ae-pro'),
                'return_value' => 'yes',
            ]
        );

	    $this->add_control(
		    'auto_height',
		    [
			    'label' => __('Auto Height', 'ae-pro'),
			    'type' => Controls_Manager::SWITCHER,
			    'default' => '',
			    'label_on' => __('Yes', 'ae-pro'),
			    'label_off' => __('No', 'ae-pro'),
			    'return_value' => 'yes',
		    ]
	    );

        $this->add_control(
            'zoom',
            [
                'label' => __('Zoom', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'ae-pro'),
                'label_off' => __('No', 'ae-pro'),
                'return_value' => 'yes',
            ]
        );

    }

    protected function field_control(){
        $this->add_control(
            'field_name',
            [
                'label' => __('Custom Field Name', 'ae-pro'),
                'type'  => Controls_Manager::TEXT,
            ]
        );


        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'exclude' => [ 'custom' ],
            ]
        );
        $this->add_control(
            'open_lightbox',
            [
                'label' => __('Lightbox', 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' =>
                    [
                        'default' => __( 'Default' , 'ae-pro'),
                        'yes' =>__( 'Yes' , 'ae-pro'),
                        'no' =>__('No' , 'ae-pro'),
                    ],
                'default'=>'no'
            ]
        );
    }

    protected function image_carousel_control()
    {

        $this->add_control(
            'image_carousel',
            [
                'label' => __('Carousel', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control( 
            'slide_per_view',
            [
                'label' => __( 'Slides Per View', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
               /* 'options'=> [
                    1 => __('1','ae-pro'),
                    2 => __('2','ae-pro'),
                    3 => __('3','ae-pro'),
                    4 => __('4','ae-pro'),
                ], */
                'default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
            ]
        );

        $this->add_responsive_control(
            'slides_per_group',
            [
                'label' => __( 'Slides Per Group', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => 1,
                'tablet_default' => 1,
                'mobile_default' => 1,
            ]
        );

    }

    protected function pagination_controls(){



        $this->add_control(
            'pagination_heading',
            [
                'label' => __('Pagination', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );



        $this -> add_control(
            'ptype',
            [
                'label' => __(' Pagination Type' , 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' =>
                    [
                        ''        => __('None', 'ae-pro'),
                        'bullets' => __( 'Bullets' , 'ae-pro'),
                        'fraction' =>__( 'Fraction' , 'ae-pro'),
                        'progress' =>__('Progress' , 'ae-pro'),
                    ],
                'default'=>'bullets'
            ]
        );

        $this->add_control(
            'clickable',
            [
                'label' =>__('Clickable' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on'=>__('Yes', 'ae-pro'),
                'label_off' =>__('No' , 'ae-pro'),
                'condition'=> [
                    $this->get_control_id('ptype') => 'bullets'
                ],
            ]
        );

        $this->add_control(
            'navigation_button',
            [
                'label' => __('Previous/Next Button' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes' , 'ae-pro'),
                'label_off' => __('No' , 'ae-pro'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'keyboard',
            [
                'label' => __('Keyboard Control' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default'=> 'yes',
                'label_on'=>__('Yes', 'ae-pro'),
                'label_off' =>__('No' , 'ae-pro'),
                'return_value'=>'yes',
            ]
        );

        $this->add_control(
            'scrollbar',
            [
                'label' =>__('Scroll bar', 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default'=>'yes',
                'label_on' =>__('Yes' , 'ae-pro'),
                'label_off'=>__('No' , 'ae-pro'),
                'return_value' => 'yes',
            ]
        );
    }

    protected function grid_view(){
        $this->add_control(
            'grid_layout',
            [

                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'ae-pro'),
                'type'  => Controls_Manager::NUMBER,
                'desktop_default' => '4',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'min' => 1,
                'max' => 6,
                'selectors' => [
                    '{{WRAPPER}} .ae-grid-item' => 'width: calc(100%/{{VALUE}})',
                ]
            ]
        );

        $this->add_control(
          'masonry',
            [
                'label' =>__('Masonry Layout' , 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('On', 'ae-pro'),
                'label_off' => __('Off', 'ae-pro'),
                'return_value' => 'yes',
                'condition' => [
                    $this->get_control_id('columns!') => 1
                ]
            ]

        );

        $this->add_responsive_control(
            'gutter',
            [
                'label' => __('Gutter','ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'range'=>[
                    'px'=>[
                        'min' => 0,
                        'max' =>40,
                        'step' => 2,
                    ]
                ],
                'default'=>[
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-grid-item' => 'padding-left:calc({{SIZE}}{{UNIT}}/2);' ,
                    '{{WRAPPER}} .ae-grid-wrapper .ae-grid-item' => 'padding-right:calc({{SIZE}}{{UNIT}}/2);' ,
                    '{{WRAPPER}} .ae-grid .ae-grid-item' => 'margin-bottom:{{SIZE}}{{UNIT}};'
                ]
            ]
        );



    }
    protected function grid_overlay_controls(){
        $this->add_control(
            'show_overlay',
            [
                'label' => __('Show Overlay','ae-pro'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'hover' => __('On Hover','ae-pro'),
                    'always' => __('Always','ae-pro'),
                    'never' => __('Never','ae-pro'),
                    'hide-on-hover' => __('Hide on Hover' , 'ae-pro')
                ],
                'default'   => 'hover',
                'prefix_class'  => 'overlay-'
            ]
        );


        $this->add_control(
        'caption',
            [
                'label' => __('Caption' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default'=> 'yes',
                'label_on'=>__('Yes', 'ae-pro'),
                'label_off' =>__('No' , 'ae-pro'),
                'return_value'=>'yes',
                'condition'=>
                    [
                        $this->get_control_id('show_overlay!')=>'never',
                    ]
            ]


        );

        $this->add_control(
            'icon_style',
            [
                'label'=>__('Icon','ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition'=>
                    [
                        $this->get_control_id('show_overlay!')=>'never',
                    ]

            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => 'fa fa-link',
                'condition'=>
                    [
                        $this->get_control_id('show_overlay!')=>'never',
                    ]
            ]
        );

        $this->add_control(
            'view',
            [
                'label' => __( 'View', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => __( 'Default', 'ae-pro' ),
                    'stacked' => __( 'Stacked', 'ae-pro' ),
                    'framed' => __( 'Framed', 'ae-pro' ),

                ],
                'default' => 'default',
                'prefix_class' => 'ae-icon-view-',
                'condition'=>[
                    $this->get_control_id('icon!')=>'',
                    $this->get_control_id('show_overlay!') => 'never'
                ],
            ]
        );


    }
    protected function grid_overlay_style_control(){


        $this->add_control(
            'overlay',
            [
                'label' => __('Overlay','ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition'=>[
                    $this->get_control_id('show_overlay!')=>'never',
                ]
            ]

        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_color',
                'label' => __( 'Color', 'ae-pro' ),
                'types' => [ 'none', 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .ae-grid-overlay',
                'condition'=>[
                    $this->get_control_id('show_overlay!')=>'never',
                ]
            ]
        );

        $this->add_control(
            'animation',
            [
                'label' => __('Animation', 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __('None','ae-pro'),
                    'pulse' => __('Pulse', 'ae-pro'),
                    'headShake' => __('Head Shake', 'ae-pro'),
                    'tada' => __('Tada', 'ae-pro'),
                    'fadeIn' => __('Fade In', 'ae-pro'),
                    'fadeInDown' => __('Fade In Down', 'ae-pro'),
                    'fadeInLeft' => __('Fade In Left', 'ae-pro'),
                    'fadeInRight' => __('Fade In Right', 'ae-pro'),
                    'fadeInUp' => __('Fade In Up', 'ae-pro'),
                    'rotateInDownLeft' => __('Rotate In Down Left','ae-pro'),
                    'rotateInDownRight' => __('Rotate In Down Right','ae-pro'),
                    'rotateInUpLeft' => __('Rotate In Up Left','ae-pro'),
                    'rotateInUpRight' =>__('Rotate In Up Right','ae-pro'),
                    'zoomIn' => __('Zoom In','ae-pro'),
                    'zoomInDown' => __('Zoom In Down','ae-pro'),
                    'zoomInLeft' => __('Zoom In Left', 'ae-pro'),
                    'zoomInRight' => __('Zoom In Right', 'ae-pro'),
                    'zoomInUp' => __('Zoom In Up', 'ae-pro'),
                    'slideInLeft' => __('Slide In Left', 'ae-pro'),
                    'slideInRight' => __('Slide In Right', 'ae-pro'),
                    'slideInUp' => __('Slide In Up', 'ae-pro'),
                    'slideInDown' => __('Slide In Down', 'ae-pro'),
                ],
                'default'=>'fadeIn',
                'condition' =>[
                    $this->get_control_id('show_overlay')=> ['hover', 'hide-on-hover'],
                ]
            ]
        );

        $this->add_control(
            'animation_time',
            [
                'label' => __('Animation Time','ae-pro'),
                'type'  => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1.00
                ],
                'range' => [
                    'min' => 1.00,
                    'max' => 10.00,
                    'step' => 0.01
                ],
                'condition' => [
                    $this->get_control_id('animation!') => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-grid-overlay' => 'animation-duration:{{SIZE}}s;'
                ]
            ]
        );

        $this->add_control(
            'caption_style',
            [
                'label' => __('Caption','ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition'=>[
                    $this->get_control_id('caption')=>'yes',
                ]
            ]

        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => __( 'Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .ae-overlay-caption',
                'condition'=>[
                    $this->get_control_id('caption')=>'yes',
                ]
            ]
        );

        $this->add_control(
            'caption_color',
            [
                'label' => __('Color','ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-overlay-caption' => 'color:{{VALUE}};'
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'condition'=>[
                    $this->get_control_id('caption')=>'yes',
                ]
            ]
        );

        $this->add_control(
            'caption_color_hover',
            [
                'label' => __('Hover Color','ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-overlay-caption:hover' => 'color:{{VALUE}};'
                ],
                'condition'=>[
                    $this->get_control_id('caption')=>'yes',
                ]
            ]
        );

        $this->add_control(
            'icon_overlay_style',
            [
                'label'=>__('Icon','ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition'=>[
                    $this->get_control_id('icon!')=>'',
                ],

            ]

        );

        $this->add_control(
            'primary_color',
            [
                'label' => __( 'Primary Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.ae-icon-view-stacked .ae-overlay-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.ae-icon-view-framed .ae-overlay-icon, {{WRAPPER}}.ae-icon-view-default .ae-overlay-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'condition'=>[
                    $this->get_control_id('icon!')=>'',
                ],
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label' => __( 'Secondary Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'condition' => [
                    $this->get_control_id('view!') => 'default',
                ],
                'selectors' => [
                    '{{WRAPPER}}.ae-icon-view-framed .ae-overlay-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.ae-icon-view-stacked .ae-overlay-icon' => 'color: {{VALUE}};',
                ],
                'condition'=>[
                    $this->get_control_id('icon!')=>'',
                ],
            ]
        );

        $this->add_control(
            'primary_color_hover',
            [
                'label' => __( 'Primary Color Hover', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.ae-icon-view-stacked:hover .ae-overlay-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.ae-icon-view-framed:hover .ae-overlay-icon:hover, {{WRAPPER}}.ae-icon-view-default .ae-overlay-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],'condition'=>[
                $this->get_control_id('icon!')=>'',
            ],

            ]
        );

        $this->add_control(
            'secondary_color_hover',
            [
                'label' => __( 'Secondary Color Hover', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'condition' => [
                    $this->get_control_id('view!') => 'default',
                ],
                'selectors' => [
                    '{{WRAPPER}}.ae-icon-view-framed:hover .ae-overlay-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.ae-icon-view-stacked:hover .ae-overlay-icon:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'size',
            [
                'label' => __( 'Size', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-overlay-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition'=>[
                    $this->get_control_id('icon!')=>'',
                ],
            ]
        );

        $this->add_control(
            'icon_padding',
            [
                'label' => __( 'Icon Padding', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .ae-overlay-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'condition' => [
                    $this->get_control_id('view!') => 'default',
                ],

            ]
        );

        $this->add_control(
            'rotate',
            [
                'label' => __( 'Rotate', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-overlay-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
                'condition'=>[
                    $this->get_control_id('icon!')=>'',
                ],
            ]
        );

        $this->add_control(
            'border_width',
            [
                'label' => __( 'Border Width', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .ae-overlay-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    $this->get_control_id('view') => 'framed',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-overlay-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    $this->get_control_id('view!') => 'default',
                ],
            ]
        );

    }

    protected function grid_style_control(){

        $this->start_controls_tabs('style_tabs');

            $this->start_controls_tab(
                'normal',
                [
                    'label' => __('Normal','ae-pro')
                ]
            );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'grid_border',
                        'label' => __( 'Border', 'ae-pro' ),
                        'selector' => '{{WRAPPER}} .ae-grid-item .ae-grid-item-inner',
                    ]
                );

                $this->add_control(
                    'item_border_radius',
                    [
                        'label' => __( 'Border Radius', 'ae-pro' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'selectors' => [
                            '{{WRAPPER}} .ae-grid-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'item_box_shadow',
                        'label' => __( 'Item Shadow', 'ae-pro' ),
                        'selector' => '{{WRAPPER}} .ae-grid-item-inner',
                    ]
                );

            $this->end_controls_tab();


            $this->start_controls_tab(
                'hover',
                [
                    'label' => __('Hover','ae-pro')
                ]
            );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'grid_border_hover',
                        'label' => __( 'Border', 'ae-pro' ),
                        'selector' => '{{WRAPPER}} .ae-grid-item-inner:hover',
                    ]
                );

                $this->add_control(
                    'item_border_radius_hover',
                    [
                        'label' => __( 'Border Radius', 'ae-pro' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'selectors' => [
                            '{{WRAPPER}} .ae-grid-item-inner:hover *' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            '{{WRAPPER}} .ae-grid-item-inner:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                        ],
                    ]
                );

                $this->add_group_control(
                     Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'item_box_shadow_hover',
                        'label' => __( 'Item Shadow', 'ae-pro' ),
                        'selector' => '{{WRAPPER}} .ae-grid-item-inner:hover ',
                    ]
        );

            $this->end_controls_tab();

        $this->end_controls_tabs();

    }

    protected function get_gallery_data(){
        $images = [];
        $helper = new Helper();
        $post_data = $helper->get_demo_post_data();

        if($post_data->post_type == 'elementor_library'){
            return false;
        }

        $cf_name = $this->get_instance_value('field_name');

        if(!empty($cf_name)){
            if(class_exists('acf_pro')){
                $images = get_field($cf_name, $post_data->ID);
	            $repeater = $helper->is_repeater_block_layout();
	            if($repeater['is_repeater']){
		            if(isset($repeater['field'])){
			            $repeater_field = get_field($repeater['field'], $post_data->ID);
			            $images = $repeater_field[0][$cf_name];

		            }else {
			            $images = get_sub_field($cf_name);
		            }
	            }
            }elseif(class_exists('acf_plugin_photo_gallery')) {
                $images_arr = [];
                $images_arr = acf_photo_gallery($cf_name, $post_data->ID);
                $index = 0;
                foreach($images_arr as $img){
                    $images[$index]['ID'] = $img['id'];
                    $images[$index]['id'] = $img['id'];
                    $images[$index]['title'] = $img['title'];
                    $images[$index]['filename'] = $img['title'];
                    $images[$index]['url'] = $img['full_image_url'];
                    $image_sizes = $helper->ae_get_intermediate_image_sizes_for_acf_photo_gallery();
                    foreach($image_sizes as $image_size => $size_data){
                        $img_data = wp_get_attachment_image_src($img['id'],$image_size);
                        $images[$index]['sizes'][$size_data] = $img_data[0];
                        $images[$index]['sizes'][$size_data.'-width'] = $img_data[1];
                        $images[$index]['sizes'][$size_data.'-height'] = $img_data[2];
                    }
                    $index = $index + 1;
                }
            }else{
                $images = [];
            }

            //echo '<pre>'; print_r($images); echo '</pre>';
        }
        return $images;
    }

    protected function swiper_html(){
        $image_size = $this->get_instance_value('thumbnail_size');
        $images = $this->get_gallery_data();
        $slide_per_view['desktop'] = $this->get_instance_value('slide_per_view');
        $slide_per_view['tablet'] = $this->get_instance_value('slide_per_view_tablet');
        $slide_per_view['mobile'] = $this->get_instance_value('slide_per_view_mobile');

        $slides_per_group['desktop'] = $this->get_instance_value('slides_per_group');
        $slides_per_group['tablet'] = $this->get_instance_value('slides_per_group_tablet');
        $slides_per_group['mobile'] = $this->get_instance_value('slides_per_group_mobile');
         //echo '<pre>';print_r($slide_per_view);'</pre>';

       // $direction = $this->get_instance_value('orientation');
        $speed = $this->get_instance_value('speed');
        $autoplay = $this->get_instance_value('autoplay');
        $duration = $this->get_instance_value('duration');
        $effect = $this->get_instance_value('effect');
        $space['desktop'] = $this->get_instance_value('space')['size'];
        $space['tablet'] = $this->get_instance_value('space_tablet')['size'];
        $space['mobile'] = $this->get_instance_value('space_mobile')['size'];
        //print_r(json_encode($space));
        $loop = $this->get_instance_value('loop');
	    $auto_height = $this->get_instance_value('auto_height');
        $zoom = $this->get_instance_value('zoom');
        $pagination_type = $this->get_instance_value('ptype');
        $navigation_button = $this->get_instance_value('navigation_button');
        $clickable = $this->get_instance_value('clickable');
        $keyboard = $this->get_instance_value('keyboard');
        $scrollbar = $this->get_instance_value('scrollbar');
        $ptype= $this->get_instance_value('ptype');



        if(!empty($images)) {

            $this->parent->add_render_attribute('outer-wrapper', 'class', 'ae-swiper-outer-wrapper');
           // $this->parent->add_render_attribute('outer-wrapper', 'data-direction', $direction);
            $this->parent->add_render_attribute('outer-wrapper', 'data-speed', $speed['size']);
            if ($autoplay == 'yes') {
                $this->parent->add_render_attribute('outer-wrapper', 'data-autoplay', $autoplay);
            }
            if ($autoplay == 'yes') {
                $this->parent->add_render_attribute('outer-wrapper', 'data-duration', $duration['size']);
            }
            $this->parent->add_render_attribute('outer-wrapper', 'data-effect', $effect);
            $this->parent->add_render_attribute('outer-wrapper', 'data-space', json_encode($space, JSON_NUMERIC_CHECK));
            if ($loop == 'yes') {
                $this->parent->add_render_attribute('outer-wrapper', 'data-loop', $loop);
            }
            else{
                autoplayStopOnLast:true;
            }

	        if ($auto_height == 'yes') {
		        $this->parent->add_render_attribute('outer-wrapper', 'data-auto-height', 'true');
	        } else {
		        $this->parent->add_render_attribute('outer-wrapper', 'data-auto-height', 'false');
	        }
            if ($zoom == 'yes') {
                $this->parent->add_render_attribute('outer-wrapper', 'data-zoom', $zoom);
            }

            if(!empty($slide_per_view)){
                $this->parent->add_render_attribute('outer-wrapper', 'data-slides-per-view', json_encode($slide_per_view, JSON_NUMERIC_CHECK));
            }
            if(!empty($slides_per_group)){
                $this->parent->add_render_attribute('outer-wrapper', 'data-slides-per-group', json_encode($slides_per_group, JSON_NUMERIC_CHECK));
            }


            if ($ptype != '') {
                $this->parent->add_render_attribute('outer-wrapper', 'data-ptype', $ptype);
            }
            if ($pagination_type == 'bullets' && $clickable == 'yes') {
                $this->parent->add_render_attribute('outer-wrapper', 'data-clickable', $clickable);
            }
            if($navigation_button == 'yes'){
                $this-> parent->add_render_attribute('outer-wrapper', 'data-navigation', $navigation_button);
            }
            if($keyboard == 'yes') {
                $this->parent->add_render_attribute('outer-wrapper', 'data-keyboard', $keyboard);
            }
            if($scrollbar == 'yes') {
                $this->parent->add_render_attribute('outer-wrapper', 'data-scrollbar', $scrollbar);
            }
            ?>
            <?php
            if($this->get_instance_value('open_lightbox') != 'no') {
                $this->parent->add_render_attribute('link', [
                    'data-elementor-open-lightbox' => $this->get_instance_value('open_lightbox'),
                    'data-elementor-lightbox-slideshow' => 'ae-acf-gallery-'.rand(0,99999),
                ]);
                if (Plugin::$instance->editor->is_edit_mode()) {
                    $this->parent->add_render_attribute('link', [
                        'class' => 'elementor-clickable',
                    ]);
                }
            }
            ?>
            <div <?php echo $this->parent->get_render_attribute_string('outer-wrapper'); ?> >
                <div class="ae-swiper-container swiper-container">
                    <div class="ae-swiper-wrapper swiper-wrapper">

                        <?php
                            foreach ($images as $image) {
                                ?>
                                <div class="ae-swiper-slide swiper-slide">
                                    <div class="ae-swiper-slide-wrapper swiper-slide-wrapper">
                                        <?php if ($this->get_instance_value('open_lightbox') != 'no') { ?>
                                            <a <?php echo $this->parent->get_render_attribute_string('link'); ?> href="<?php echo wp_get_attachment_url($image['id'], 'full'); ?>">
                                        <?php } ?>
                                            <?php echo wp_get_attachment_image($image['id'], $image_size); ?>
                                        <?php if ($this->get_instance_value('open_lightbox') != 'no') { ?>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                    </div>

                    <?php if($pagination_type != ''){ ?>
                        <div class = "ae-swiper-pagination swiper-pagination"></div>
                    <?php } ?>

                    <?php if($navigation_button == 'yes'){ ?>
                            <div class = "ae-swiper-button-prev swiper-button-prev"></div>
                            <div class = "ae-swiper-button-next swiper-button-next"></div>
                    <?php } ?>

                    <?php if($scrollbar == 'yes'){ ?>
                        <div class = "ae-swiper-scrollbar swiper-scrollbar"></div>

                    <?php } ?>

                </div>
            </div>

        <?php }
    }
    protected function grid_html(){
        $image_size = $this->get_instance_value('thumbnail_size');
        $masonry= $this->get_instance_value('masonry');
        $animation = $this->get_instance_value('animation');
        $images = $this->get_gallery_data();
       // echo'<pre>'; print_r($images); echo '<pre>';
        $icon=$this->get_instance_value('icon');

        $caption=$this->get_instance_value('caption');

        $this->parent->add_render_attribute('grid-wrapper','class','ae-masonry-'.$masonry);
        $this->parent->add_render_attribute('grid-wrapper','class','ae-grid-wrapper');
           ?>
        <?php
            $this->parent->add_render_attribute('link', [
                'data-elementor-open-lightbox' => $this->get_instance_value('open_lightbox'),
                'data-elementor-lightbox-slideshow' => 'ae-acf-gallery-'.rand(0,99999),
            ]);
        if (Plugin::$instance->editor->is_edit_mode()) {
            $this->parent->add_render_attribute('link', [
                'class' => 'elementor-clickable',
            ]);
        }
        ?>

               <div <?php echo $this->parent->get_render_attribute_string('grid-wrapper'); ?>>
                    <div class="ae-grid">
                        <?php
                        if(!empty($images)) {
                            foreach ($images as $image) {
                                if ($image_size == 'full') {
                                    $src = $image['url'];
                                } else {
                                    $src = $image['sizes'][$image_size];
                                } ?>
                                <figure class="ae-grid-item">
                                    <div class="ae-grid-item-inner">
                                        <a href="<?php echo $image['url']; ?>" <?php echo $this->parent->get_render_attribute_string('link'); ?>>
                                            <img src="<?php echo $src; ?>"/>
                                            <div class="ae-grid-overlay <?php echo $animation ?>">
                                                <div class="ae-grid-overlay-inner">
                                                    <div class="ae-icon-wrapper">
                                                        <?php if (!empty($icon)) { ?>
                                                            <div class="ae-overlay-icon"><i
                                                                        class="<?php echo $icon ?>"> </i></div>
                                                        <?php } ?>
                                                    </div>

                                                    <?php if (!empty($image['caption']) && $caption == 'yes') { ?>
                                                        <div class="ae-overlay-caption"><?php echo $image['caption']; ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </a>

                                    </div>
                                </figure>
                            <?php }
                        }?>
                     </div>
               </div>
        <?php
    }



}