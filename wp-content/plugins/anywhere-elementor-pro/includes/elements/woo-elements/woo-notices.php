<?php

namespace Aepro;

use Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;

class Aepro_Woo_Notices extends Widget_Base{
    public function get_name() {
        return 'ae-woo-notices';
    }

    public function get_title() {
        return __( 'AE - Woo Notices', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-woocommerce';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    protected function _register_controls() {
        $helper = new Helper();
        $this->start_controls_section(
            'section_general_style',
            [
                'label' => __( 'General', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'message_box_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __('Message Box','ae-pro')
            ]
        );

        $helper->box_model_controls($this,[
            'name' => 'message_box',
            'label' => __('Message Box'),
            'border' => true,
            'border-radius' => true,
            'margin' => true,
            'padding' => true,
            'box-shadow' => true,
            'selector' => '{{WRAPPER}} .woocommerce-message'
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'messaage_typography',
                'label' => __( 'Message Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .woocommerce-message',
            ]
        );

        $this->add_control(
            'message_color',
            [
                'label' => __('Message Color','ae-pro'),
                'type'  => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-message' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_button_style',
            [
                'label' => __( 'Button Style', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('button');

            $this->start_controls_tab('button_normal',[
               'label' => __('Normal','ae-pro')
            ]);

                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name' => 'button_normal_typography',
                        'label' => __( 'Typography', 'ae-pro' ),
                        'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                        'selector' => '{{WRAPPER}} .woocommerce-message .button',
                    ]
                );

                $this->add_control(
                    'button_normal_color',
                    [
                        'label' => __('Color','ae-pro'),
                        'type'  => Controls_Manager::COLOR,
                        'scheme' => [
                            'type' => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_1,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .woocommerce-message .button' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'button_normal_bgcolor',
                    [
                        'label' => __('Background','ae-pro'),
                        'type'  => Controls_Manager::COLOR,
                        'scheme' => [
                            'type' => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_1,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .woocommerce-message .button' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );

                $helper->box_model_controls($this,[
                    'name' => 'button',
                    'label' => __('Button','ae-pro'),
                    'border' => true,
                    'border-radius' => true,
                    'margin' => false,
                    'padding' => true,
                    'box-shadow' => true,
                    'selector' => '{{WRAPPER}} .woocommerce-message .button'
                ]);

            $this->end_controls_tab();


            $this->start_controls_tab('button_hover',[
                'label' => __('Hover','ae-pro')
            ]);

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'button_hover_typography',
                    'label' => __( 'Typography', 'ae-pro' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                    'selector' => '{{WRAPPER}} .woocommerce-message .button:hover',
                ]
            );

            $this->add_control(
                'button_hover_color',
                [
                    'label' => __('Color','ae-pro'),
                    'type'  => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-message .button:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'button_hover_bgcolor',
                [
                    'label' => __('Background','ae-pro'),
                    'type'  => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-message .button:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $helper->box_model_controls($this,[
                'name' => 'button_normal',
                'label' => __('Button','ae-pro'),
                'border' => true,
                'border-radius' => true,
                'margin' => false,
                'padding' => true,
                'box-shadow' => true,
                'selector' => '{{WRAPPER}} .woocommerce-message .button:hover'
            ]);

            $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render( ) {
        $settings = $this->get_settings();
        $helper = new Helper();
        $product = $helper->get_ae_woo_product_data();
        if(!$product){
            return '';
        }

        if(Elementor\Plugin::instance()->editor->is_edit_mode()){
            // show dummy message
            ?>
            <div class="woocommerce-message"><a href="#" class="button wc-forward">View cart</a>Sample notice for preview</div>
            <?php
        }else{
            echo wc_print_notices();
        }


    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Woo_Notices() );