<?php

namespace Aepro;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;


class Aepro_Woo_Category extends Widget_Base{
    public function get_name() {
        return 'ae-woo-category';
    }

    public function get_title() {
        return __( 'AE - Woo Category', 'ae-pro' );
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
            'layout_mode',
            [
                'label' => __( 'Layout', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'horizontal' => [
                        'title' => __( 'Horizontal', 'ae-pro' ),
                        'icon' => 'fa fa-arrows-h',
                    ],
                    'vertical' => [
                        'title' => __( 'Vertical', 'ae-pro' ),
                        'icon' => 'fa fa-arrows-v',
                    ]
                ],
                'default' => 'horizontal'
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

        $this->add_control(
            'cat_icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => 'fa fa-folder',
            ]
        );

        $this->add_control(
            'cat_prefix',
            [
                'label' => __( 'Prefix', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Categories: ', 'ae-pro' ),
                'default' => __( '', 'ae-pro' )
            ]
        );


        $this->add_control(
            'cat_separator',
            [
                'label' => __( 'Category Separator', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter separator', 'ae-pro' ),
                'default' => __( ',', 'ae-pro' ),
                'condition' => [
                    'layout_mode' => 'horizontal',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_category_style',
            [
                'label' => __( 'Category', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]

        );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'cat_typography',
			    'label' => __( 'Item Typography', 'ae-pro' ),
			    'scheme' => Scheme_Typography::TYPOGRAPHY_4,
			    'selector' => '{{WRAPPER}} .ae-element-woo-category a, {{WRAPPER}} label, {{WRAPPER}} .ae-element-woo-category',
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'label_typography',
			    'label' => __( 'Label Typography', 'ae-pro' ),
			    'scheme' => Scheme_Typography::TYPOGRAPHY_4,
			    'selector' => '{{WRAPPER}} .woo-cat-prefix label'
		    ]
	    );

	    $this->start_controls_tabs('text_styles');

	        $this->start_controls_tab(
                'text_normal',
                [
	                'label' => __('Normal', 'ae-pro')
                ]
            );

            $this->add_control(
                'cat_color',
                [
                    'label' => __( 'Text Color', 'ae-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_4,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ae-element-woo-category a' => 'color: {{VALUE}};',
                    ],

                ]
            );

            $this->add_control(
                'label_color',
                [
                    'label' => __( 'Label Color', 'ae-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ae-element-woo-category label' => 'color: {{VALUE}};',
                    ],

                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cat_border',
                    'label' => __( 'Border', 'ae-pro' ),
                    'selector' => '{{WRAPPER}} .ae-element-woo-category a',
                ]
            );

            $this->add_control(
                'cat_border_radius',
                [
                    'label' => __( 'Border Radius', 'ae-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .ae-element-woo-category a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'cat_section_bg',
                [
                    'label' => __( 'Background', 'ae-pro' ),
                    'type' => Controls_Manager::COLOR,

                    'selectors' => [
                        '{{WRAPPER}} .ae-element-woo-category a' => 'background-color: {{VALUE}};',
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
                        'value' => Scheme_Color::COLOR_4,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .item-separator' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'layout_mode' => 'horizontal',
                    ],
                ]
            );


	        $this->end_controls_tab();

            $this->start_controls_tab(
                'text_hover',
                [
                    'label' => __('Hover', 'ae-pro')
                ]
            );

                $this->add_control(
                    'cat_hover_color',
                    [
                        'label' => __( 'Text Hover Color', 'ae-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .ae-element-woo-category a:hover' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'label_hover_color',
                    [
                        'label' => __( 'Label Color', 'ae-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .ae-element-woo-category label:hover' => 'color: {{VALUE}};',
                        ],

                    ]
                );

                $this->add_control(
                    'item_border_hover',
                    [
                        'label' => __( 'Border Color', 'ae-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .ae-element-woo-category a:hover' => 'border-color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'cat_border_radius_hover',
                    [
                        'label' => __( 'Border Radius', 'ae-pro' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'selectors' => [
                            '{{WRAPPER}} .ae-element-woo-category a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );

                $this->add_control(
                    'cat_section_bg_hover',
                    [
                        'label' => __( 'Background', 'ae-pro' ),
                        'type' => Controls_Manager::COLOR,

                        'selectors' => [
                            '{{WRAPPER}} .ae-element-woo-category a:hover' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );


            $this->end_controls_tab();


	    $this->end_controls_tabs();











	    $this->add_responsive_control(
		    'cat_padding',
		    [
			    'label' => __( 'Padding', 'ae-pro' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', 'em', '%' ],
			    'selectors' => [
				    '{{WRAPPER}} .ae-element-woo-category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
                'separator' => 'before'
		    ]
	    );
	    $this->add_responsive_control(
		    'cat_margin',
		    [
			    'label' => __( 'Margin', 'ae-pro' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', 'em', '%' ],
			    'selectors' => [
				    '{{WRAPPER}} .ae-element-woo-category a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );







        $this->add_control(
            'icon_settings',
            [
                'label' => __( 'Icon Settings', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Icon Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon-wrapper i' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => __( 'Icon Hover Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-wrapper i:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_spacing',
            [
                'label' => __( 'Icon Spacing', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon-wrapper i' => 'padding-right: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .icon-wrapper i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
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

        $woo_c = wc_get_product_category_list($product->get_id());
        $woo_cat = explode(",",$woo_c);
        $this->add_render_attribute( 'woo-category-class', 'class', 'ae-element-woo-category' );
        $this->add_render_attribute('woo-category-icon-class','class','icon-wrapper');
        $this->add_render_attribute('woo-category-icon-class','class','ae-element-woo-category-icon');
        $this->add_render_attribute('woo-category-icon','class',$settings['cat_icon']);
        if(!count($woo_cat)){
            return false;
        }

        if(empty($settings['cat_separator'])){
            $settings['cat_separator'] = ' ';
        }

        if($settings['layout_mode'] == 'vertical'){
            $this->add_render_attribute('woo-category-class', 'class', 'ae-cat-vertical' );
            $settings['cat_separator'] = '';
        }
        ?>

        <div <?php echo $this->get_render_attribute_string( 'woo-category-class' ); ?>>
            <?php if(!empty($settings['cat_icon'])){ ?>
                <span <?php echo $this->get_render_attribute_string( 'woo-category-icon-class' ); ?>>
                <i <?php echo $this->get_render_attribute_string( 'woo-category-icon' ); ?>></i>
            </span>
            <?php } ?>


            <?php if(!empty($settings['cat_prefix'])){ ?>
                <span class="woo-cat-prefix">
                <label><?php echo $settings['cat_prefix']; ?></label>
            </span>
            <?php } ?>

            <span <?php echo $this->get_render_attribute_string('woo-category-class'); ?>>
            <?php $woo_categories = implode($settings['cat_separator'], $woo_cat);
            echo $woo_categories;
            ?>
        </span>
        </div>

        <?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Woo_Category() );