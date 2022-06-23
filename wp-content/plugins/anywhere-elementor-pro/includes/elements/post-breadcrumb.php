<?php

namespace Aepro;


use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

class Aepro_Breadcrumb extends Widget_Base{
    public function get_name() {
        return 'ae-breadcrumb';
    }

    public function get_title() {
        return __( 'AE - Breadcrumb', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon fa fa-angle-double-right';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    public function get_custom_help_url() {
        $helper = new Helper();
        return $helper->get_help_url_prefix() . $this->get_name();
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_General_title',
            [
                'label' => __('General Style', 'ae-pro'),
            ]
        );
        $this->add_responsive_control(
            'anchor_align',
            [
                'label' => __( 'Alignment', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'ae-pro' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'ae-pro' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'ae-pro' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'separator_color',
            [
                'label' => __( 'Separator Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} span span' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'anchor_style',
            [
                'label' => __( 'Anchor Style', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->start_controls_tabs('button_style');
            $this->start_controls_tab('anchor_normal',[ 'label' => __('Normal','ae-pro') ]);
                $this->add_control(
                    'anchor_normal_color',
                    [
                        'label' => __( 'Color', 'ae-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'scheme' => [
                            'type' => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_1,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} a' => 'color: {{VALUE}};',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name' => 'anchor_normal_typography',
                        'label' => __( 'Anchor Typography', 'ae-pro' ),
                        'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                        'selector' => '{{WRAPPER}} a',
                    ]
                );
            $this->end_controls_tab();

            $this->start_controls_tab('anchor_hover',[ 'label' => __('Hover','ae-pro') ]);
                $this->add_control(
                    'anchor_hover_color',
                    [
                        'label' => __( 'Color', 'ae-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'scheme' => [
                            'type' => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_1,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} a:hover' => 'color: {{VALUE}};',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name' => 'anchor_hover_typography',
                        'label' => __( 'Anchor Typography', 'ae-pro' ),
                        'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                        'selector' => '{{WRAPPER}} a:hover',
                    ]
                );
            $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'current_page_style',
            [
                'label' => __( 'Current Page Style', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control(
            'current_page_color',
            [
                'label' => __( 'Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} span .breadcrumb_last' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'current_page_typography',
                'label' => __( 'Current Page Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .breadcrumb_last',
            ]
        );
        $this->end_controls_section();
    }

    protected function render( ) {
        $breadcrumbs = '';
        if ( function_exists('yoast_breadcrumb') ) {
            $breadcrumbs = yoast_breadcrumb("","",false);
        }
        echo $breadcrumbs;
    }

}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Breadcrumb() );