<?php

namespace Aepro;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;


class Aepro_Woo_Title extends Widget_Base{
    public function get_name() {
        return 'ae-woo-title';
    }

    public function get_title() {
        return __( 'AE - Woo Title', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-woocommerce';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    public function _register_controls()
    {
        $this->start_controls_section(
            'section_title',
            [
                'label' => __( 'General', 'ae-pro' ),
            ]
        );
        $this->add_control(
            'use_link',
            [
                'label' => __( 'Product Link', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [
                        'title' => __( 'Yes', 'ae-pro' ),
                        'icon' => 'fa fa-check',
                    ],
                    '0' => [
                        'title' => __( 'No', 'ae-pro' ),
                        'icon' => 'fa fa-ban',
                    ]
                ],
                'default' => '1'
            ]
        );
        $this->add_control(
            'title_tag',
            [
                'label' => __( 'HTML Tag', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => __( 'H1', 'ae-pro' ),
                    'h2' => __( 'H2', 'ae-pro' ),
                    'h3' => __( 'H3', 'ae-pro' ),
                    'h4' => __( 'H4', 'ae-pro' ),
                    'h5' => __( 'H5', 'ae-pro' ),
                    'h6' => __( 'H6', 'ae-pro' ),
                    'div' => __( 'div', 'ae-pro' ),
                    'span' => __( 'span', 'ae-pro' )
                ],
                'default' => 'h1',
            ]
        );
        $this->add_responsive_control(
            'align',
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
        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __( 'General', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .ae-element-woo-title',
            ]
        );
        $this->end_controls_section();
    }

    public function render(){
        $settings = $this->get_settings();
        $helper = new Helper();
        $product = $helper->get_ae_woo_product_data();

        if(!$product){
            return '';
        }
        $this->add_render_attribute( 'woo-title-class', 'class', 'ae-element-woo-title' );

        $title_html = '';
        if($settings['use_link'] == 1){
            $title_html = '<a href="'.$product->get_permalink().'">';
        }

        $title_html .= sprintf('<%1$s %2$s>%3$s</%1$s>',$settings['title_tag'],$this->get_render_attribute_string('woo-title-class'),$product->get_title());

        if($settings['use_link'] == 1){
            $title_html .= '</a>';
        }

        echo $title_html;

    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Woo_Title() );