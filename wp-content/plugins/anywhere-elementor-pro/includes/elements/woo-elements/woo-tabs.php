<?php

namespace Aepro;

use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;


class Aepro_Woo_Tabs extends Widget_Base{
    public function get_name() {
        return 'ae-woo-tabs';
    }

    public function get_title() {
        return __( 'AE - Woo Tabs', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-woocommerce';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    public function _register_controls()
    {
        $helper = new Helper();
        $this->start_controls_section(
            'section_title',
            [
                'label' => __( 'General', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'tab_layout',
            [
                'label' => __('Layout', 'ae-pro'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => __( 'Horizontal', 'ae-pro' ),
                    'vertical' => __( 'Vertical', 'ae-pro' ),
                ],
                'prefix_class' => 'ae-woo-tabs-view-',
                'default' => 'horizontal'

            ]
        );

        $this->add_responsive_control(
            'tab_align',
            [
                'label' => __('Tab Align','ae-pro'),
                'type'  => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}}.ae-woo-tabs-view-horizontal .ae-woo-tabs-wrapper' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'tab_layout' => 'horizontal'
                ]
            ]
        );

        $repeater = new Repeater();


        if(is_singular() && in_array($GLOBALS['post']->post_type,['ae_global_templates','product'])){
            $registered_tabs = $helper->get_woo_registered_tabs();
        }
        $registered_tabs['description'] = __('Description', 'ae-pro');
        $registered_tabs['additional_information'] = __('Additional Information','ae-pro');
        $registered_tabs['reviews'] = __('Reviews','ae-pro');
        $registered_tabs['custom'] = __('Custom','ae-pro');

        $repeater->add_control(
            'tab_type',
            [
                'label' => __( 'Tab Type', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => $registered_tabs,
                'default' => 'description',
            ]
        );

        $repeater->add_control(
            'tab_title',
            [
                'label' => __( 'Tab Title', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Description',
            ]
        );

        $repeater->add_control(
            'custom_tab_content',
            [
                'label' => __( 'Tab Content', 'ae-pro' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => '',
                'condition' => [
                    'tab_type' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label' => __( 'Tabs', 'ae-pro'),
                'type'  => Controls_Manager::REPEATER,
                'fields' => array_values($repeater->get_controls()),
                'default' => [
                    [
                        'tab_type' => 'description',
                        'tab_title' => __('Description','ae-pro')
                    ],
                    [
                        'tab_type' => 'additional_information',
                        'tab_title' => __('Additional Information','ae-pro')
                    ],
                    [
                        'tab_type' => 'reviews',
                        'tab_title' => __('Reviews','ae-pro')
                    ],
                ],
                'title_field' => '{{{ tab_title }}}'

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tabs_style',
            [
                'label' => __( 'Tabs', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'navigation_width',
            [
                'label' => __( 'Navigation Width', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-woo-tabs-wrapper' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'tab_layout' => 'vertical',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'navigation_border',
                'label' => __( 'Border', 'ae-pro' ),
                'default' => '1px',
                'selector' => '{{WRAPPER}} .ae-woo-tabs-wrapper',
                'condition' => [
                    'tab_layout'    => 'horizontal'
                ]
            ]
        );

        $this->add_responsive_control(
            'tab_title_padding',
            [
                'label'  => __('Tab Padding','ae-pro'),
                'type'   => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-woo-tab-title.ae-woo-tab-desktop-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_width',
            [
                'label' => __( 'Border Width', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-woo-tab-title, {{WRAPPER}} .ae-woo-tab-title:before, {{WRAPPER}} .ae-woo-tab-title:after, {{WRAPPER}} .ae-woo-tab-content, {{WRAPPER}} .ae-woo-tabs-content-wrapper' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => __( 'Border Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-woo-tab-mobile-title, {{WRAPPER}} .ae-woo-tab-desktop-title.active,
                    {{WRAPPER}} .ae-woo-tab-title:before, {{WRAPPER}} .ae-woo-tab-title:after,
                    {{WRAPPER}} .ae-woo-tab-content, {{WRAPPER}} .ae-woo-tabs-content-wrapper' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __( 'Background Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-woo-tab-desktop-title.active' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ae-woo-tabs-content-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_title',
            [
                'label' => __( 'Title', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tab_color',
            [
                'label' => __( 'Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-woo-tab-title' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
            ]
        );

        $this->add_control(
            'tab_active_color',
            [
                'label' => __( 'Active Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-woo-tab-title.active' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tab_typography',
                'selector' => '{{WRAPPER}} .ae-woo-tab-title',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );

        $this->add_control(
            'heading_content',
            [
                'label' => __( 'Content', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __( 'Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-woo-tab-content' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .ae-woo-tab-content',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->end_controls_section();
    }

    public function render(){
        global $product;
        $settings = $this->get_settings();
        $helper = new Helper();
        $product = $helper->get_ae_woo_product_data();
        if(!$product){
            return '';
        }


        setup_postdata($product->get_id());
        $registered_tabs = $helper->get_woo_registered_tabs('full');

        if(count($settings['tabs']) && count($registered_tabs)) {
            ?>
            <div class="ae-woo-tabs" role="tablist">
                <?php
                $counter = 1; ?>
                <div class="ae-woo-tabs-wrapper" role="tab">
                    <?php foreach ($settings['tabs'] as $tab) :
                        if (!$this->is_tab_valid($tab, $registered_tabs)) {
                            continue;
                        }
                        ?>
                        <div class="ae-woo-tab-title ae-woo-tab-desktop-title"
                             data-tab="<?php echo $counter; ?>" data-hashtag="<?php echo $tab['tab_type']; ?>"><?php echo $tab['tab_title']; ?></div>
                        <?php
                        $counter++;
                    endforeach; ?>
                </div>

                <?php
                $counter = 1; ?>
                <div class="ae-woo-tabs-content-wrapper" role="tabpanel">
                    <?php foreach ($settings['tabs'] as $tab) :
                        if (!$this->is_tab_valid($tab, $registered_tabs)) {
                            continue;
                        }
                        ?>
                        <div class="ae-woo-tab-title ae-woo-tab-mobile-title"
                             data-tab="<?php echo $counter; ?>" data-hashtag="<?php echo $tab['tab_type']; ?>"><?php echo $tab['tab_title']; ?></div>
                        <div class="ae-woo-tab-content elementor-clearfix" data-tab="<?php echo $counter; ?>"  data-hashtag="<?php echo $tab['tab_type']; ?>">
                            <?php
                            if ($tab['tab_type'] == 'custom') {
                                echo do_shortcode(wpautop($tab['custom_tab_content']));
                            } else {
                                call_user_func($registered_tabs[$tab['tab_type']]['callback'], $tab['tab_type'], $registered_tabs[$tab['tab_type']]);
                            }
                            ?>
                        </div>
                        <?php
                        $counter++;
                    endforeach; ?>
                </div>
            </div>
            <?php
        }else{
            echo 'Please add some tabs.';
        }
        wp_reset_postdata();


    }

    /**
     * Checks if tab is valid for this product
     * @param $tab
     * @param $registered_tab
     * @return bool
     */
    function is_tab_valid($tab,$registered_tabs){

        if($tab['tab_type'] == 'custom' || in_array($tab['tab_type'],array_keys($registered_tabs))){
            return true;
        }

        return false;
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Woo_Tabs() );