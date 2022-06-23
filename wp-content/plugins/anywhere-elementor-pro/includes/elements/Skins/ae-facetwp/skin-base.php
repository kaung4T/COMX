<?php

namespace Aepro\Ae_FacetWP\Skins;

use Elementor\Controls_Manager;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;
use Aepro\Helper;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Skin_Base extends Elementor_Skin_Base
{
    protected function _register_controls_actions()
    {
        add_action('elementor/element/ae-facetwp/general/before_section_end', [$this, 'register_controls']);
        add_action('elementor/element/ae-facetwp/style/before_section_end', [$this, 'register_style_controls']);
    }

    public function register_controls(Widget_Base $widget){
        $this->parent = $widget;
    }
    
    
    protected function check_radio_style_control(){
        $this->add_control(
            'label_style',
            [
                'label' => __( 'Label', 'plugin-name' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'facet_label_typography',
                'selector' => '{{WRAPPER}} .facetwp-checkbox', '{{WRAPPER}} .facetwp-radio',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __( 'Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'default'   =>  '',
                'selectors' => [
                    '{{WRAPPER}} .facetwp-checkbox' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .facetwp-radio' => 'color: {{VALUE}}',
                ],

            ]
        );

        $this->add_responsive_control(
            'spacing',
            [
                'label' => __( 'Spacing', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .facetwp-checkbox' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .facetwp-radio' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_control(
            'separator',
            [
                'label' => __( 'Separator', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'your-plugin' ),
                'label_off' => __( 'Hide', 'your-plugin' ),
                'return_value' => 'yes',
                'default' => 'no',

            ]
        );

        $this->add_control(
            'separator_size',
            [
                'label' => __( 'Separator Size', 'ae-pro' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 2,
                'selectors' => [
                    '{{WRAPPER}} .facetwp-checkbox' => 'border-bottom-width:{{VALUE}}px',
                    '{{WRAPPER}} .facetwp-radio' => 'border-bottom-width:{{VALUE}}px',
                ],
                'condition' =>  [
                    $this->get_control_id('separator')     =>  'yes',
                ],

            ]
        );
        $this->add_control(
            'separator_type',
            [
                'label' => __( 'Separator Type', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'solid',
                'options' => [
                    'solid'  => __( 'Solid', 'ae-pro' ),
                    'dashed' => __( 'Dashed', 'ae-pro' ),
                    'dotted' => __( 'Dotted', 'ae-pro' ),
                    'double' => __( 'Double', 'ae-pro' ),
                    'none' => __( 'None', 'ae-pro' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .facetwp-checkbox' => 'border-bottom-style:{{VALUE}}',
                    '{{WRAPPER}} .facetwp-radio' => 'border-bottom-style:{{VALUE}}',
                ],
                'condition' =>  [
                    $this->get_control_id('separator')     =>  'yes',
                ],
            ]
        );
        $this->add_control(
            'separator_color',
            [
                'label' => __( 'Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'default'   =>  '',
                'selectors' => [
                    '{{WRAPPER}} .facetwp-checkbox' => 'border-bottom-color : {{VALUE}}',
                    '{{WRAPPER}} .facetwp-radio' => 'border-bottom-color : {{VALUE}}',
                ],
                'condition' =>  [
                    $this->get_control_id('separator')     =>  'yes',
                ],

            ]
        );

        $this->add_control(
            'left_indent',
            [
                'label' => __( 'Indent', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .facetwp-checkbox img' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .facetwp-radio img' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],

            ]
        );


        $this->add_responsive_control(
            'box_size',
            [
                'label' => __( 'Box Size', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .facetwp-checkbox img' => 'width: {{SIZE}}{{UNIT}}; height : {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .facetwp-radio' => 'background-size: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'counter_style',
            [
                'label' => __( 'Counter', 'plugin-name' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',

            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'counter_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .facetwp-checkbox .facetwp-counter','{{WRAPPER}} .facetwp-radio .facetwp-counter',




            ]
        );

        $this->add_control(
            'counter_color',
            [
                'label' => __( 'Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'default'   =>  '',
                'selectors' => [
                    '{{WRAPPER}} .facetwp-checkbox .facetwp-counter' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .facetwp-radio .facetwp-counter' => 'color: {{VALUE}}',
                ],

            ]
        );
    }

    protected function dropdown_style_control(){

    }
}