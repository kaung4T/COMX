<?php

namespace Aepro;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;

class Aepro_Woo_Readmore extends Widget_Base{
    public function get_name() {
        return 'ae-woo-readmore';
    }

    public function get_title() {
        return __( 'AE - Woo Read More', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-woocommerce';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_readmore_settings',
            [
                'label' => __( 'Read More Settings', 'ae-pro' )
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => __( 'Read More Text', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Read More', 'ae-pro' ),
                'default' => __( 'Read More', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
            ]
        );

        $this->add_responsive_control(
            'icon_position',
            [
                'label' => __( 'Icon Position', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => [
                    'right' => __( 'After', 'ae-pro' ),
                    'left' => __( 'Before', 'ae-pro' ),
                ],
                'default' => 'left',
                'condition' => [
                    'icon!' => '',
                ]
            ]
        );

        $this->add_control(
            'icon_indent',
            [
                'label' => __( 'Icon Spacing', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'condition' => [
                    'icon!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-read-more .icon-align-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ae-element-woo-read-more .icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'readmore_align',
            [
                'label' => __( 'Align', 'ae-pro' ),
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
                    ]
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-read-more' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'read_more_text!' => ''
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_general_style',
            [
                'label' => __( 'Read More', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs1');

        $this->start_controls_tab('woo_normal',['label' => 'Normal']);

        $this->load_woo_normal_settings();

        $this->end_controls_tab();

        $this->start_controls_tab('woo_hover',['label' => 'Hover']);

        $this->load_woo_hover_settings();

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'readmore_text_padding',
            [
                'label' => __( 'Text Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-read-more a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render( ) {
        $settings = $this->get_settings();
        $helper = new Helper();
        $product = $helper->get_ae_woo_product_data();
        if(!$product){
            return '';
        }

        $product_id = $product->get_id();
        $product_link = get_permalink($product_id );

        $this->add_render_attribute('woo-content-readmore', 'class', 'ae-element-woo-read-more');
        $this->add_render_attribute('woo-icon-class','class','icon-wrapper');
        $this->add_render_attribute('woo-icon-class','class','icon-align-'.$settings['icon_position']);
        $this->add_render_attribute('woo-icon-class','class','ae-element-icon');
        $this->add_render_attribute('woo-icon','class',$settings['icon']);

        ?>
            <div <?php echo $this->get_render_attribute_string('woo-content-readmore');?>>
                <a href="<?php echo $product_link; ?>" title="<?php the_title(); ?>" <?php echo $this->get_render_attribute_string('woo-content-readmore-link');?>>
                    <?php if(!empty($settings['icon'])){ ?>
                        <span <?php echo $this->get_render_attribute_string( 'woo-icon-class' ); ?>>
                        <i <?php echo $this->get_render_attribute_string( 'woo-icon' ); ?>></i>
                    </span>
                    <?php } ?>
                    <span class="woo-read-text"><?php echo $settings['read_more_text']; ?></span>
                </a>
            </div>
        <?php
    }
    protected function load_woo_normal_settings(){

        $this->add_control(
            'readmore_color',
            [
                'label' => __( 'Read More Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-read-more a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'read_more_text!' => ''
                ],
            ]
        );



        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Icon Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-icon.icon-wrapper i' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'icon!' => ''
                ],
            ]
        );
        $this->add_control(
            'icon_size',
            [
                'label' => __( 'Icon Size', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-icon.icon-wrapper i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'icon!' => ''
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'readmore_typography',
                'label' => __( 'Readmore Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .ae-element-woo-read-more a',
            ]
        );

        $this->add_control(
            'readmore_bg',
            [
                'label' => __( 'Background Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-read-more a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'readmore_border',
                'label' => __( 'Border', 'ae-pro' ),
                'default' => '1px',
                'selector' => '{{WRAPPER}} .ae-element-woo-read-more a',
            ]
        );

        $this->add_control(
            'readmore_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-read-more a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

    }

    protected function load_woo_hover_settings(){
        $this->add_control(
            'readmore_hover_color',
            [
                'label' => __( 'Read More Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-read-more a:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'read_more_text!' => ''
                ],
            ]
        );



        $this->add_control(
            'icon_hover_color',
            [
                'label' => __( 'Icon Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-icon.icon-wrapper i:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'icon!' => ''
                ],
            ]
        );
        $this->add_control(
            'icon_hover_size',
            [
                'label' => __( 'Icon Size', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-icon.icon-wrapper i:hover' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'icon!' => ''
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'readmore_hover_typography',
                'label' => __( 'Readmore Hover Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .ae-element-woo-read-more a:hover',
            ]
        );

        $this->add_control(
            'readmore_hover_bg',
            [
                'label' => __( 'Background Hover Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-read-more a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'readmore_hover_border',
                'label' => __( 'Border', 'ae-pro' ),
                'default' => '1px',
                'selector' => '{{WRAPPER}} .ae-element-woo-read-more a:hover',
            ]
        );

        $this->add_control(
            'readmore_hover_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-read-more a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Woo_Readmore() );