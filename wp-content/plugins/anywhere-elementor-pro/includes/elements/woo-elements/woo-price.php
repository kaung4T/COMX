<?php

namespace Aepro;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;


class Aepro_Woo_Price extends Widget_Base{
    public function get_name() {
        return 'ae-woo-price';
    }

    public function get_title() {
        return __( 'AE - Woo Price', 'ae-pro' );
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

        $this->add_responsive_control(
            'padding',
            [
                'label' => __( 'Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-price .woocommerce-Price-amount' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'margin',
            [
                'label' => __( 'Margin', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-price .woocommerce-Price-amount' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_price_style',
            [
                'label' => __( 'Price', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]

        );
        $this->add_control(
            'price_color',
            [
                'label' => __( 'Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-price .woocommerce-Price-amount' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .ae-element-woo-price .woocommerce-Price-amount',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_sale_price_style',
            [
                'label' => __( 'Sale Price', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]

        );
        $this->add_control(
            'sale_price_color',
            [
                'label' => __( 'Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-price del .woocommerce-Price-amount' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography_sale',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .ae-element-woo-price del .woocommerce-Price-amount',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_currency_price_style',
            [
                'label' => __( 'Currency Symbol', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]

        );
        $this->add_control(
            'currency_color',
            [
                'label' => __( 'Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-price .woocommerce-Price-currencySymbol' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography_currency',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .ae-element-woo-price .woocommerce-Price-currencySymbol',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_currency_sale_price_style',
            [
                'label' => __( 'Currency Symbol (Sale Price)', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]

        );
        $this->add_control(
            'currency_color_sale_price',
            [
                'label' => __( 'Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-price del .woocommerce-Price-currencySymbol' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography_currency_sale_price',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .ae-element-woo-price del .woocommerce-Price-currencySymbol',
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

        $this->add_render_attribute( 'woo-price-class', 'class', 'ae-element-woo-price' );
        ?>
        <p <?php echo $this->get_render_attribute_string('woo-price-class');?>>
            <?php echo $product->get_price_html();?>
        </p>
        <?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Woo_Price() );

