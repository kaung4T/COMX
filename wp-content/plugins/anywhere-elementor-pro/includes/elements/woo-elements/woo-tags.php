<?php

namespace Aepro;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;


class Aepro_Woo_Tags extends Widget_Base{
    public function get_name() {
        return 'ae-woo-tags';
    }

    public function get_title() {
        return __( 'AE - Woo Tags', 'ae-pro' );
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
            'tags_icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => 'fa fa-folder',
            ]
        );

	    $this->add_control(
		    'tag_prefix',
		    [
			    'label' => __( 'Prefix', 'ae-pro' ),
			    'type' => Controls_Manager::TEXT,
			    'placeholder' => __( 'Tags: ', 'ae-pro' ),
			    'default' => __( '', 'ae-pro' )
		    ]
	    );

        $this->add_control(
            'tags_separator',
            [
                'label' => __( 'Tags Separator', 'ae-pro' ),
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
            'section_tags_style',
            [
                'label' => __( 'Tags', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]

        );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'tags_typography',
			    'label' => __( 'Typography', 'ae-pro' ),
			    'scheme' => Scheme_Typography::TYPOGRAPHY_4,
			    'selector' => '{{WRAPPER}} .ae-element-woo-tags a, {{WRAPPER}} label, {{WRAPPER}} .ae-element-woo-tags-wrapper',
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'label_typography',
			    'label' => __( 'Label Typography', 'ae-pro' ),
			    'scheme' => Scheme_Typography::TYPOGRAPHY_4,
			    'selector' => '{{WRAPPER}} .woo-tag-prefix label'
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

        $this->add_responsive_control(
            'tags_padding',
            [
                'label' => __( 'Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-tags a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tags_margin',
            [
                'label' => __( 'Margin', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-tags a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        //$woo_t = $product->get_tags();
        $woo_t =  wc_get_product_tag_list($product->get_id());

        $woo_tag = explode(",",$woo_t);
        $this->add_render_attribute( 'woo-tags-wrapper', 'class', 'ae-element-woo-tags-wrapper' );
        $this->add_render_attribute( 'woo-tags-class', 'class', 'ae-element-woo-tags' );
        $this->add_render_attribute('woo-tags-icon-class','class','icon-wrapper');
        $this->add_render_attribute('woo-tags-icon-class','class','ae-element-woo-tags-icon');
        $this->add_render_attribute('woo-tags-icon','class',$settings['tags_icon']);
        if(!count($woo_tag)){
            return false;
        }
    if($settings['layout_mode'] == 'vertical'){
        $this->add_render_attribute('woo-tags-class', 'class', 'ae-tags-vertical' );
    }

    if(empty($settings['tags_separator'])){
        $settings['tags_separator'] = ' ';
    }

?>
    <div <?php echo $this->get_render_attribute_string( 'woo-tags-wrapper' ); ?>>
        <?php if(!empty($settings['tags_icon'])){ ?>
            <span <?php echo $this->get_render_attribute_string( 'woo-tags-icon-class' ); ?>>
                <i <?php echo $this->get_render_attribute_string( 'woo-tags-icon' ); ?>></i>
            </span>
        <?php } ?>

	    <?php if(!empty($settings['tag_prefix'])){ ?>
            <span class="woo-tag-prefix">
                <label><?php echo $settings['tag_prefix']; ?></label>
            </span>
	    <?php } ?>


        <span <?php echo $this->get_render_attribute_string('woo-tags-class'); ?>>
            <?php $woo_tags = implode($settings['tags_separator'], $woo_tag);
                echo $woo_tags;
            ?>
        </span>
    </div>
    <?php
        return true;
    ?>

        <?php
    }

    protected function load_woo_normal_settings(){
        $this->add_control(
            'tags_color',
            [
                'label' => __( 'Text Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-tags' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ae-element-woo-tags a' => 'color: {{VALUE}};',
                ],

            ]
        );

	    $this->add_control(
		    'label_color',
		    [
			    'label' => __( 'Label Color', 'ae-pro' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .woo-tag-prefix label' => 'color: {{VALUE}};'
			    ],

		    ]
	    );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tags_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-element-woo-tags a',
            ]
        );

        $this->add_control(
            'tags_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-tags a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'tags_section_bg',
            [
                'label' => __( 'Background', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-tags a' => 'background-color: {{VALUE}};',
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
    }

    protected function load_woo_hover_settings(){
        $this->add_control(
            'tags_hover_color',
            [
                'label' => __( 'Text Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-tags a:hover' => 'color: {{VALUE}};',
                ],

            ]
        );

	    $this->add_control(
		    'label_hover_color',
		    [
			    'label' => __( 'Label Color', 'ae-pro' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .woo-tag-prefix label:hover' => 'color: {{VALUE}};'
			    ],

		    ]
	    );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tags_hover_typography',
                'label' => __( 'Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .ae-element-woo-tags a:hover',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tags_hover_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-element-woo-tags a:hover',
            ]
        );

        $this->add_control(
            'tags_hover_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-tags a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'tags_hover_section_bg',
            [
                'label' => __( 'Background', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-tags a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_hover_settings',
            [
                'label' => __( 'Icon Settings', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'icon_hover_color',
            [
                'label' => __( 'Icon Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon-wrapper i:hover' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .icon-wrapper i:hover' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Woo_Tags() );

