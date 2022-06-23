<?php

namespace Aepro;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;


class Aepro_Woo_SKU extends Widget_Base{
    public function get_name() {
        return 'ae-woo-sku';
    }

    public function get_title() {
        return __( 'AE - Woo SKU', 'ae-pro' );
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
            'section_sku',
            [
                'label' => __( 'General', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'sku_tag',
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

        $this->add_control(
            'sku_prefix',
            [
                'label' => __( 'Prefix', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'SKU: ', 'ae-pro' ),
                'default' => __( '', 'ae-pro' )
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
            'section_sku_style',
            [
                'label' => __( 'General', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'typography',
			    'label' => __('Typography', 'ae-pro'),
			    'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			    'selector' => '{{WRAPPER}} .ae-element-woo-sku, {{WRAPPER}} label',
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'label_typography',
			    'label' => __('Label Typography', 'ae-pro'),
			    'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			    'selector' => '{{WRAPPER}} .product_meta label',
		    ]
	    );


	    $this->start_controls_tabs('sku_styles');

	        $this->start_controls_tab(
	          'sku_normal',
               [
                    'label' => __('Normal', 'ae-pro')
               ]
            );


            $this->add_control(
                'sku_color',
                [
                    'label' => __( 'Color', 'ae-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_3,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ae-element-woo-sku' => 'color: {{VALUE}};',
                        '{{WRAPPER}} label' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'label_color',
                [
                    'label' => __( 'Label Color', 'ae-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_3,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .product_meta label' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'sku_border',
                    'label' => __( 'Border', 'ae-pro' ),
                    'selector' => '{{WRAPPER}} .ae-element-woo-sku',
                ]
            );

            $this->add_control(
                'sku_border_radius',
                [
                    'label' => __( 'Border Radius', 'ae-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .ae-element-woo-sku' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                'sku_bgcolor',
                [
                    'label' => __( 'Background Color', 'ae-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ae-element-woo-sku' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

	        $this->end_controls_tab();

            $this->start_controls_tab(
                'sku_hover',
                [
                    'label' => __('Hover', 'ae-pro')
                ]
            );

            $this->add_control(
                'sku_hover_color',
                [
                    'label' => __( 'Color', 'ae-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ae-element-woo-sku:hover' => 'color: {{VALUE}};',
                        '{{WRAPPER}} label:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'label_hover_color',
                [
                    'label' => __( 'Label Color', 'ae-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .product_meta label:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

	    $this->add_control(
		    'border_hover_color',
		    [
			    'label' => __( 'Border Color', 'ae-pro' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .ae-element-woo-sku:hover' => 'border-color: {{VALUE}};',
			    ],
		    ]
	    );


            $this->add_control(
                'sku_hover_border_radius',
                [
                    'label' => __( 'Border Radius', 'ae-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .ae-element-woo-sku:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                'sku_hover_bgcolor',
                [
                    'label' => __( 'Background Color', 'ae-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ae-element-woo-sku:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

        $this->end_controls_tabs();





        $this->add_control(
            'sku_padding',
            [
                'label' => __( 'Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-sku' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

	    $this->add_control(
		    'sku_margin',
		    [
			    'label' => __( 'Margin', 'ae-pro' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', '%', 'em' ],
			    'selectors' => [
				    '{{WRAPPER}} .ae-element-woo-sku' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->end_controls_section();
    }

    public function render(){
        $settings = $this->get_settings();
        $helper = new Helper();
        global $product;
        $product = $helper->get_ae_woo_product_data();
        if(!$product){
            return '';
        }

        $this->add_render_attribute( 'woo-sku-class', 'class', 'ae-element-woo-sku' );
        $this->add_render_attribute( 'woo-sku-class', 'class', 'sku' );
        $this->add_render_attribute( 'woo-sku-label-class', 'class', 'ae-element-woo-sku-label' );
        $sku_html = sprintf('<%1$s %2$s>%3$s</%1$s>',$settings['sku_tag'],$this->get_render_attribute_string('woo-sku-class'),$product->get_sku());

        ?>
        <div class="product_meta">
            <?php if(!empty($settings['sku_prefix'])){ ?>
                <label ><?php echo $settings['sku_prefix']; ?></label>
            <?php } ?>
            <?php echo $sku_html; ?>
        </div>
        <?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Woo_SKU() );