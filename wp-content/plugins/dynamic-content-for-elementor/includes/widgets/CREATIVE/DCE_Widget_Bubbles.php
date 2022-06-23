<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Bubbles SVG BETA
 *
 * Elementor widget for Dinamic Content Elements
 *
 */

class DCE_Widget_Bubbles extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-bubbles';
    }
    
    static public function is_enabled() {
        return false;
    }

    public function get_title() {
        return __('Bubbles', 'dynamic-content-for-elementor');
    }

    public function get_icon() {
        return 'icon-dyn-bubbles todo';
    }
    public function get_script_depends() {
        return [ ];
    }
    public function get_style_depends() {
        return [ 'dce-bubbles' ];
    }
    static public function get_position() {
        return 9;
    }
    protected function _register_controls() {
        $this->start_controls_section(
                'section_bubbles', [
                'label' => __('Bubbles', 'dynamic-content-for-elementor'),
            ]
        );
        $this->add_control(
            'indicatore_bubbles',
            [
                'label' => __( 'Enable Indicatore', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Show', 'dynamic-content-for-elementor' ),
                'label_off' => __( 'Hide', 'dynamic-content-for-elementor' ),
                'prefix_class' => 'indicatore-',
                'return_value' => 'yes',
            ]
        );
         $this->add_control(
            'dimension_bubbles',
            [
                'label' => __( 'Dimension', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'rem',
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 600,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'rem','px' ],
                'selectors' => [
                    '{{WRAPPER}} .dce-container-bubbles' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
         $this->add_control(
            'starting_depth_bubbles',
            [
                'label' => __( 'Starting Depth', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 2048,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 5000,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px','rem' ],
                'selectors' => [
                    '{{WRAPPER}} .bubbles-large > g, {{WRAPPER}} .bubbles-small > g' => 'transform: translateY({{SIZE}}{{UNIT}});',
                ],
            ]
        );
         $this->add_control(
          'power_bubbles',
          [
             'label'       => __( 'Power', 'dynamic-content-for-elementor' ),
             'type' => Controls_Manager::SELECT,
             'default' => '',
             'options' => [
                'short' => _x( 'Short', 'Animation Control', 'dynamic-content-for-elementor' ),
                '' => _x( 'Medium', 'Animation Control', 'dynamic-content-for-elementor' ),
                'long' => _x( 'Long', 'Animation Control', 'dynamic-content-for-elementor' ),
            ],
             'selectors' => [ // You can use the selected value in an auto-generated css rule.
                '{{WRAPPER}} .bubbles-large > g, {{WRAPPER}} .bubbles-small > g' => 'animation-name: up_bubble{{VALUE}}; -webkit-animation-name: up_bubble{{VALUE}};',
             ],
          ]
        );
         $this->add_control(
            'bubbles_color',
            [
                'label' => __( 'Bubbles Color', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .bubbles circle' => 'stroke: {{VALUE}}',
                ],
            ]
        );
          $this->add_control(
            'bubbles_color1',
            [
                'label' => __( 'Bubbles Color A', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                
                'default' => '#87f5fb',
                'selectors' => [
                    '{{WRAPPER}} .bubbles > g > g:nth-of-type(3n) circle' => 'stroke: {{VALUE}}',
                ],
            ]
        );
           $this->add_control(
            'bubbles_color2',
            [
                'label' => __( 'Bubbles Color B', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                
                'default' => '#8be8cb',
                'selectors' => [
                    '{{WRAPPER}} .bubbles > g > g:nth-of-type(4n) circle' => 'stroke: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_active_settings();
        if ( empty( $settings ) )
            return;
        ?>
        <div class="dce-container-bubbles">

    <div class="bubbles-container">
        <svg class="bubbles" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 701 1024" style="overflow: visible;">

        <g class="bubbles-large" stroke-width="7">
            <g>
                <g transform="translate(10 940)">
                    <circle cx="35" cy="35" r="35"/>
                </g>
            </g>
            <g>
                <g transform="translate(373 940)">
                    <circle cx="35" cy="35" r="35"/>
                </g>
            </g>
            <g>
                <g transform="translate(408 940)">
                    <circle cx="35" cy="35" r="35"/>
                </g>
            </g>
            <g>
                <g transform="translate(621 940)">
                    <circle cx="35" cy="35" r="35"/>
                </g>
            </g>
            <g>
                <g transform="translate(179 940)">
                    <circle cx="35" cy="35" r="35"/>
                </g>
            </g>
        </g>

        <g class="bubbles-small" stroke-width="4">
            <g>
                <g transform="translate(147 984)">
                    <circle cx="15" cy="15" r="15"/>
                </g>
            </g>
            <g>
                <g transform="translate(255 984)">
                    <circle cx="15" cy="15" r="15"/>
                </g>
            </g>
            <g>
                <g transform="translate(573 984)">
                    <circle cx="15" cy="15" r="15"/>
                </g>
            </g>
            <g>
                <g transform="translate(429 984)">
                    <circle cx="15" cy="15" r="15"/>
                </g>
            </g>
            <g>
                <g transform="translate(91 984)">
                    <circle cx="15" cy="15" r="15"/>
                </g>
            </g>
            <g>
                <g transform="translate(640 984)">
                    <circle cx="15" cy="15" r="15"/>
                </g>
            </g>
            <g>
                <g transform="translate(321 984)">
                    <circle cx="15" cy="15" r="15"/>
                </g>
            </g>
            <g>
                <g transform="translate(376 984)">
                    <circle cx="15" cy="15" r="15"/>
                </g>
            </g>
            <g>
                <g transform="translate(376 984)">
                    <circle cx="15" cy="15" r="15"/>
                </g>
            </g>
            <g>
                <g transform="translate(497 984)">
                    <circle cx="15" cy="15" r="15"/>
                </g>
            </g>
        </g>

    </svg>
    </div>


</div>
<?php

    }

}
