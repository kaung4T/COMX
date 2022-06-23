<?php

namespace Aepro;


use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;


class Aepro_Post_Meta extends Widget_Base{
    public function get_name() {
        return 'ae-post-meta';
    }

    public function get_title() {
        return __( 'AE - Post Meta', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-post';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    public function get_custom_help_url() {
        $helper = new Helper();
        return $helper->get_help_url_prefix() . $this->get_name();
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_title',
            [
                'label' => __( 'General', 'ae-pro' ),
            ]
        );

        $this->add_responsive_control(
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

        $this->start_controls_tabs('tabs1');

            $this->start_controls_tab('post_category',['label' => 'Category']);

                $this->load_category_content_settings();

            $this->end_controls_tab();


            $this->start_controls_tab('post_tag',['label' => 'Tag']);

                $this->load_tag_content_settings();

            $this->end_controls_tab();


            $this->start_controls_tab('post_date',['label' => 'Date']);

                $this->load_date_content_settings();

            $this->end_controls_tab();


            $this->start_controls_tab('post_author',['label' => 'Author']);

                $this->load_author_content_settings();

            $this->end_controls_tab();

            $this->start_controls_tab('post_comment',['label' => 'Comment']);

                $this->load_comment_content_settings();

            $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_general_style',
            [
                'label' => __( 'Global Style', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-post-meta-item-wrapper' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ae-post-meta-item-wrapper a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ae-element-post-category' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ae-element-post-tags' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ae-element-post-date' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ae-element-post-author' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ae-element-post-comment' => 'color: {{VALUE}};',

                ],

            ]
        );

        $this->add_control(
            'text_hover_color',
            [
                'label' => __( 'Text Hover Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-post-meta-item-wrapper a:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ae-post-meta-item-wrapper span span:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => __( 'Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selectors' => [
                    '{{WRAPPER}} .ae-post-meta-item-wrapper a',
                    '{{WRAPPER}} .item-separator a',
                    '{{WRAPPER}} .ae-post-meta-item-wrapper span span',
                ]
            ]
        );

        $this->add_responsive_control(
            'horizontal_align',
            [
                'label'     => __('Alignment','ae-pro'),
                'type'      => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __( 'Left', 'ae-pro' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'ae-pro' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => __( 'Right', 'ae-pro' ),
                        'icon' => 'fa fa-align-right',
                    ]
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ae-pm-layout-horizontal' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'layout_mode' => 'horizontal',
                ],
            ]
        );

        $this->add_control(
            'item_separator',
            [
                'label' => __( 'Meta Separator', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter separator', 'ae-pro' ),
                'default' => __( '/', 'ae-pro' ),
                'condition' => [
                    'layout_mode' => 'horizontal',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => __( 'Item Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'allowed_dimensions' => 'horizontal',
                'selectors' => [
                    '{{WRAPPER}} .ae-post-meta-item-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_mode' => 'horizontal',
                ],
            ]
        );

        $this->add_control(
            'icon_settings',
            [
                'label' => __( 'Icon Settings', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                /*'condition' => [
                    'cat_icon!' => '',
                    'tag_icon!' => '',
                    'date_icon!' => '',
                    'author_icon!' => '',
                ]*/
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
                ]
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
                ]
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
                ]
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
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_cat_style',
            [
                'label' => __( 'Categories', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_category' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'cat_label_color',
            [
                'label' => __( 'Label Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-category-label' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'cat_label!' => '',
                ]

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cat_label_typography',
                'label' => __( 'Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .ae-element-post-category-label',
                'condition' => [
                    'cat_label!' => '',
                ]

            ]
        );

        $this->add_control(
            'cat_spacing',
            [
                'label' => __( 'Label Spacing', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-category-label' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'cat_label!' => '',
                ]
            ]
        );

        $this->add_responsive_control(
            'cat_padding',
            [
                'label' => __( 'Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-element-post-category span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'cat_margin',
            [
                'label' => __( 'Margin', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-category a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-element-post-category span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'cat_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-element-post-category a, {{WRAPPER}} .ae-element-post-category span',
            ]
        );

        $this->add_control(
            'cat_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-category a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-element-post-category span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'cat_section_bg',
            [
                'label' => __( 'Background', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-category a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ae-element-post-category span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cat_hover_color',
            [
                'label' => __( 'Background Hover Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-category a:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ae-element-post-category span:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_tag_style',
            [
                'label' => __( 'Tags', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_tags' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'tag_label_color',
            [
                'label' => __( 'Label Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-tags-label' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'tag_label!' => '',
                ]

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tag_label_typography',
                'label' => __( 'Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .ae-element-post-tags-label',
                'condition' => [
                    'tag_label!' => '',
                ]
            ]
        );

        $this->add_control(
            'tag_spacing',
            [
                'label' => __( 'Label Spacing', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-tags-label' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'tag_label!' => '',
                ]
            ]
        );

        $this->add_responsive_control(
            'tag_padding',
            [
                'label' => __( 'Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-tags a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-element-post-tags span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tag_margin',
            [
                'label' => __( 'Margin', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-tags a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-element-post-tags span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tag_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-element-post-tags a, {{WRAPPER}} .ae-element-post-tags span',
            ]
        );

        $this->add_control(
            'tags_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-tags a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-element-post-tags span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'tags_section_bg',
            [
                'label' => __( 'Background', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-tags a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ae-element-post-tags span' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'tag_hover_color',
            [
                'label' => __( 'Background Hover Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-tags a:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ae-element-post-tags span:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_date_style',
            [
                'label' => __( 'Date', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_date' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'date_label_color',
            [
                'label' => __( 'Label Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-date-label' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'date_label!' => '',
                ]

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_label_typography',
                'label' => __( 'Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .ae-element-post-date-label',
                'condition' => [
                    'date_label!' => '',
                ]

            ]
        );

        $this->add_control(
            'date_spacing',
            [
                'label' => __( 'Label Spacing', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-date-label' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'date_label!' => '',
                ]
            ]
        );

        $this->add_responsive_control(
            'date_padding',
            [
                'label' => __( 'Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-date-category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-element-date-category span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'date_margin',
            [
                'label' => __( 'Margin', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-date-category a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-element-date-category span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'date_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-element-post-date a, {{WRAPPER}} .ae-element-post-date span',
            ]
        );

        $this->add_control(
            'date_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-date a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-element-post-date span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'date_section_bg',
            [
                'label' => __( 'Background', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-date a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ae-element-post-date span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'date_hover_color',
            [
                'label' => __( 'Background Hover Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-date a:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ae-element-post-date span:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_author_style',
            [
                'label' => __( 'Author', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_author' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'author_label_color',
            [
                'label' => __( 'Label Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-author-label' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'author_label!' => '',
                ]

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'author_label_typography',
                'label' => __( 'Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .ae-element-post-author-label',
                'condition' => [
                    'author_label!' => '',
                ]

            ]
        );

        $this->add_control(
            'author_spacing',
            [
                'label' => __( 'Label Spacing', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-author-label' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'author_label!' => '',
                ]
            ]
        );

        $this->add_responsive_control(
            'author_padding',
            [
                'label' => __( 'Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-author a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-element-post-author span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_margin',
            [
                'label' => __( 'Margin', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-author a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-element-post-author span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'author_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-element-post-author a, {{WRAPPER}} .ae-element-post-author span',
            ]
        );

        $this->add_control(
            'author_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-author a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-element-post-author span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'author_section_bg',
            [
                'label' => __( 'Background', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-author a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ae-element-post-author span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'author_hover_color',
            [
                'label' => __( 'Background Hover Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-author a:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ae-element-post-author span:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_comment_style',
            [
                'label' => __( 'Comments', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_comment' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'comment_label_color',
            [
                'label' => __( 'Label Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-comment-label' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'comment_label!' => '',
                ]

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'comment_label_typography',
                'label' => __( 'Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .ae-element-post-comment-label',
                'condition' => [
                    'comment_label!' => '',
                ]

            ]
        );

        $this->add_control(
            'comment_spacing',
            [
                'label' => __( 'Label Spacing', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-comment-label' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'comment_label!' => '',
                ]
            ]
        );

        $this->add_responsive_control(
            'comment_padding',
            [
                'label' => __( 'Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-comment a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-post-meta-item-wrapper span.ae-element-post-comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'comment_margin',
            [
                'label' => __( 'Margin', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-comment a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-post-meta-item-wrapper span.ae-element-post-comment' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'comment_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-element-post-comment a',
            ]
        );

        $this->add_control(
            'comment_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-comment a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-post-meta-item-wrapper span.ae-element-post-comment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'comment_section_bg',
            [
                'label' => __( 'Background', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-comment a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ae-post-meta-item-wrapper span.ae-element-post-comment' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'comment_hover_color',
            [
                'label' => __( 'Background Hover Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-comment a:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ae-post-meta-item-wrapper span.ae-element-post-comment:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

    }

    protected function render( ) {
        $settings = $this->get_settings();
        $helper = new Helper();
        $post_data = $helper->get_demo_post_data();

        $this->add_render_attribute('post-meta-wrapper','class','ae-post-meta-list-wrapper');
        $this->add_render_attribute('post-meta-wrapper','class','ae-pm-layout-'.$settings['layout_mode'] );
        $this->add_render_attribute('post-meta-wrapper','class','ae-pm-layout-tablet-'.$settings['layout_mode_tablet'] );
        $this->add_render_attribute('post-meta-wrapper','class','ae-pm-layout-mobile-'.$settings['layout_mode_mobile'] );
        //echo '<pre>'; print_r($settings);
        $this->add_render_attribute('post-meta-item-wrapper','class','ae-post-meta-item-wrapper');

        if($settings['show_category']){
            $render_order['category'] = $settings['cat_order'];
        }

        if($settings['show_tags']){
            $render_order['tag'] = $settings['tag_order'];
        }

        if($settings['show_date']){
            $render_order['date'] = $settings['date_order'];
        }

        if($settings['show_author']){
            $render_order['author'] = $settings['author_order'];
        }

	    if($settings['show_comment']){
		    $render_order['comment'] = $settings['comment_order'];
	    }

        asort($render_order);

        if(count($render_order)){
            ?>
            <div <?php echo $this->get_render_attribute_string('post-meta-wrapper'); ?>>
                <?php
                    $i = 0;
                    foreach($render_order as $k => $v){
                        $i++;
                        $func_name = 'render_'.$k;
                        $attribute_string = $this->get_render_attribute_string('post-meta-item-wrapper');
                        $item_data = $this->$func_name($post_data,$settings,$attribute_string);
                        if($i < count($render_order) && $settings['layout_mode'] == "horizontal" && $settings['item_separator'] != "" && $item_data){
                            ?>
                            <div class="item-separator"><?php echo $settings['item_separator'];?></div>
                            <?php
                        }
                    }
                ?>
            </div>
            <?php
        }


    }

    protected function load_category_content_settings(){
        $this->add_control(
            'show_category',
            [
                'label' => __( 'Show Categories', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __( 'Show', 'ae-pro' ),
                'label_off' => __( 'Hide', 'ae-pro' ),
                'return_value' => 'yes',
            ]
        );


        $this->add_control(
            'cat_icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => 'fa fa-folder',
                'condition' => [
                    'show_category' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'cat_order',
            [
                'label' => __( 'Order', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => [
                    '1' => __( '1', 'ae-pro' ),
                    '2' => __( '2', 'ae-pro' ),
                    '3' => __( '3', 'ae-pro' ),
                    '4' => __( '4', 'ae-pro' ),
                    '5' => __( '5', 'ae-pro' )
                ],
                'condition' => [
                    'show_category' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'cat_label',
            [
                'label' => __( 'Category Label', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter Label', 'ae-pro' ),
                'default' => __( '', 'ae-pro' ),
                'condition' => [
                    'show_category' => 'yes',
                ]
            ]
        );



        $this->add_responsive_control(
            'cat_vertical',
            [
                'label' => __( 'Vertical List', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Show', 'ae-pro' ),
                'label_off' => __( 'Hide', 'ae-pro' ),
                'return_value' => 'yes',
                'condition' => [
                    'show_category' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'cat_horizontal_align',
            [
                'label'     => __('Alignment','ae-pro'),
                'type'      => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} .ae-post-meta-item-wrapper' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'cat_vertical' => 'yes'
                ]
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
                    'show_category' => 'yes',
                    'cat_vertical!' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'disable_category_link',
            [
                'label' => __( 'Disable Link', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'link_on' => __( 'Yes', 'ae-pro' ),
                'link_off' => __( 'No    ', 'ae-pro' ),
                'return_value' => 'yes'
            ]
        );
    }

    protected function load_tag_content_settings(){
        $this->add_control(
            'show_tags',
            [
                'label' => __( 'Show Tags', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __( 'Show', 'ae-pro' ),
                'label_off' => __( 'Hide', 'ae-pro' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'tag_icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => 'fa fa-tags',
                'condition' => [
                    'show_tags' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'tag_order',
            [
                'label' => __( 'Order', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => [
                    '1' => __( '1', 'ae-pro' ),
                    '2' => __( '2', 'ae-pro' ),
                    '3' => __( '3', 'ae-pro' ),
                    '4' => __( '4', 'ae-pro' ),
                    '5' => __( '5', 'ae-pro' )
                ],
                'condition' => [
                    'show_tags' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'tag_label',
            [
                'label' => __( 'Tag Label', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter Label', 'ae-pro' ),
                'default' => __( '', 'ae-pro' ),
                'condition' => [
                    'show_tags' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'tag_vertical',
            [
                'label' => __( 'Vertical List', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Show', 'ae-pro' ),
                'label_off' => __( 'Hide', 'ae-pro' ),
                'return_value' => 'yes',
                'condition' => [
                    'show_tags' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'tag_horizontal_align',
            [
                'label'     => __('Alignment','ae-pro'),
                'type'      => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} .ae-post-meta-item-wrapper' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'tag_vertical' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'tag_separator',
            [
                'label' => __( 'Tag Separator', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter separator', 'ae-pro' ),
                'default' => __( ',', 'ae-pro' ),
                'condition' => [
                    'show_tags' => 'yes',
                    'tag_vertical!' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'disable_tag_link',
            [
                'label' => __( 'Disable Link', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'link_on' => __( 'Yes', 'ae-pro' ),
                'link_off' => __( 'No    ', 'ae-pro' ),
                'return_value' => 'yes'
            ]
        );
    }

    protected function load_date_content_settings(){
        $this->add_control(
            'show_date',
            [
                'label' => __( 'Show Date', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __( 'Show', 'ae-pro' ),
                'label_off' => __( 'Hide', 'ae-pro' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'date_icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => 'fa fa-clock-o',
                'condition' => [
                    'show_date' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'date_label',
            [
                'label' => __( 'Date Label', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter Label', 'ae-pro' ),
                'default' => __( '', 'ae-pro' ),
                'condition' => [
                    'show_date' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'date_type',
            [
                'label' => __( 'Date Type', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'published' => __( 'Published Date', 'ae-pro' ),
                    'modified' => __( 'Modified Date', 'ae-pro' ),
                ],
                'default' => 'published',
                'condition' => [
                    'show_date' => 'yes',
                ]
            ]
        );

        $helper = new Helper();
        $this->add_control(
            'date_format',
            [
                'label' => __( 'Date format', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => $helper->ae_get_date_format(),
                'default' => 'F j, Y',
                'condition' => [
                    'show_date' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'date_custom_format',
            [
                'label' => __( 'Date Format', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter Date Format', 'ae-pro' ),
                'default' => 'y:m:d',
                'condition' => [
                    'show_date' => 'yes',
                    'date_format' => 'custom'
                ]
            ]
        );

        $this->add_control(
            'date_order',
            [
                'label' => __( 'Order', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => [
                    '1' => __( '1', 'ae-pro' ),
                    '2' => __( '2', 'ae-pro' ),
                    '3' => __( '3', 'ae-pro' ),
                    '4' => __( '4', 'ae-pro' ),
                    '5' => __( '5', 'ae-pro' )
                ],
                'condition' => [
                    'show_date' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'disable_date_link',
            [
                'label' => __( 'Disable Link', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'link_on' => __( 'Yes', 'ae-pro' ),
                'link_off' => __( 'No    ', 'ae-pro' ),
                'return_value' => 'yes'
            ]
        );
    }

    protected function load_author_content_settings(){

        $this->add_control(
            'show_author',
            [
                'label' => __( 'Show Author', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __( 'Show', 'ae-pro' ),
                'label_off' => __( 'Hide', 'ae-pro' ),
                'return_value' => 'yes',
            ]
        );


        $this->add_control(
            'author_icon',
            [
                'label' => __( 'Icon', 'ae-pro' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => 'fa fa-user',
                'condition' => [
                    'show_author' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'author_label',
            [
                'label' => __( 'Author Label', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter Label', 'ae-pro' ),
                'default' => __( '', 'ae-pro' ),
                'condition' => [
                    'show_author' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'author_order',
            [
                'label' => __( 'Order', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => [
                    '1' => __( '1', 'ae-pro' ),
                    '2' => __( '2', 'ae-pro' ),
                    '3' => __( '3', 'ae-pro' ),
                    '4' => __( '4', 'ae-pro' ),
                    '5' => __( '5', 'ae-pro' )
                ],
                'condition' => [
                    'show_author' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'disable_author_link',
            [
                'label' => __( 'Disable Link', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'link_on' => __( 'Yes', 'ae-pro' ),
                'link_off' => __( 'No    ', 'ae-pro' ),
                'return_value' => 'yes'
            ]
        );
    }

    protected function load_comment_content_settings(){

	    $this->add_control(
		    'show_comment',
		    [
			    'label' => __( 'Show Comment', 'ae-pro' ),
			    'type' => Controls_Manager::SWITCHER,
			    'default' => '',
			    'label_on' => __( 'Show', 'ae-pro' ),
			    'label_off' => __( 'Hide', 'ae-pro' ),
			    'return_value' => 'yes',
		    ]
	    );


	    $this->add_control(
		    'comment_icon',
		    [
			    'label' => __( 'Icon', 'ae-pro' ),
			    'type' => Controls_Manager::ICON,
			    'label_block' => true,
			    'default' => 'fa fa-user',
			    'condition' => [
				    'show_comment' => 'yes',
			    ]
		    ]
	    );

        $this->add_control(
            'comment_label',
            [
                'label' => __( 'Comments Label', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter Label', 'ae-pro' ),
                'default' => __( '', 'ae-pro' ),
                'condition' => [
                    'show_comment' => 'yes',
                ]
            ]
        );

	    $this->add_control(
		    'comment_order',
		    [
			    'label' => __( 'Order', 'ae-pro' ),
			    'type' => Controls_Manager::SELECT,
			    'label_block' => true,
			    'options' => [
				    '1' => __( '1', 'ae-pro' ),
				    '2' => __( '2', 'ae-pro' ),
				    '3' => __( '3', 'ae-pro' ),
				    '4' => __( '4', 'ae-pro' ),
				    '5' => __( '5', 'ae-pro' ),
			    ],
			    'condition' => [
				    'show_comment' => 'yes',
			    ]
		    ]
	    );
        $this->add_control(
            'comment_labels',
            [
                'label' => __( 'Labels', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => [
                    'show_comment' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'no_comment_label',
            [
                'label' => __( 'No Comment', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'No Comment',
                'condition' => [
                    'show_comment' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'one_comment_label',
            [
                'label' => __( 'One Comment', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => '1 Comment',
                'condition' => [
                    'show_comment' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'more_comment_label',
            [
                'label' => __( 'More than One Comment', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => '% Comments',
                'condition' => [
                    'show_comment' => 'yes',
                ],
                'description' => __('Please keep "%" to be replaced by number of comments', 'ae-pro')
            ]
        );

    }

    protected function render_category($p,$settings,$attribute_string){
        $post_categories = wp_get_post_categories($p->ID);
        if(!count($post_categories)){
            return false;
        }

        $this->add_render_attribute('post-category-class', 'class', 'ae-element-post-category' );
        $this->add_render_attribute('post-category-label-class', 'class', 'ae-element-post-category-label' );
        $this->add_render_attribute('post-category-icon-class','class','icon-wrapper');
        $this->add_render_attribute('post-category-icon-class','class','ae-element-post-category-icon');
        $this->add_render_attribute('post-category-icon','class',$settings['cat_icon']);

        if(empty($settings['cat_separator'])){
            $settings['cat_separator'] = ' ';
        }

        if($settings['cat_vertical'] == 'yes'){
            $this->add_render_attribute('post-category-class', 'class', 'ae-cat-vertical' );
            $settings['cat_separator'] = '';
        }


        $cats = array();

        foreach ($post_categories as $c) {
            $cat = get_category($c);
            $cat_link = get_category_link( $c );
            if($settings['disable_category_link'] == 'yes'){
                $cats[] = '<span>' . $cat->name . '</span>';
            }else{
                $cats[] = '<a href="'.$cat_link.'" rel="category">' . $cat->name . '</a>';
            }

        }
        ?>
        <div <?php echo $attribute_string; ?>>
            <?php if(!empty($settings['cat_icon'])){ ?>
                <span <?php echo $this->get_render_attribute_string( 'post-category-icon-class' ); ?>>
                    <i <?php echo $this->get_render_attribute_string( 'post-category-icon' ); ?>></i>
                </span>
            <?php } ?>

            <span <?php echo $this->get_render_attribute_string('post-category-label-class');?>>
               <?php echo $settings['cat_label'];?>
            </span>
            <span <?php echo $this->get_render_attribute_string('post-category-class'); ?>>
                <?php   $post_categories = implode($settings['cat_separator'], $cats);
                echo $post_categories;
                ?>
            </span>
        </div>
        <?php
        return true;
    }

    protected function render_tag($p,$settings,$attribute_string){
        $post_tags = wp_get_post_tags($p->ID);
        if(!count($post_tags)){
            return false;
        }
        if(empty($settings['tag_separator'])){
            $settings['tag_separator'] = ' ';
        }
        $tags = array();
        $this->add_render_attribute('post-tags-class', 'class', 'ae-element-post-tags' );
        $this->add_render_attribute('post-tags-label-class', 'class', 'ae-element-post-tags-label' );
        $this->add_render_attribute('post-tags-icon-class','class','icon-wrapper');
        $this->add_render_attribute('post-tags-icon-class','class','ae-element-post-tags-icon');
        $this->add_render_attribute('post-tags-icon','class',$settings['tag_icon']);


        if($settings['tag_vertical'] == 'yes'){
            $this->add_render_attribute('post-tags-class', 'class', 'ae-tag-vertical' );
            $settings['tag_separator'] = '';
        }

        foreach ($post_tags as $tag) {
            $tag_link =  get_tag_link($tag->term_id);
            if($settings['disable_tag_link'] == 'yes'){
                $tags[] = '<span>' . $tag->name . '</span>';
            }else {
                $tags[] = '<a href="'.$tag_link.'" rel="tag">' . $tag->name . '</a>';
            }
        }
        ?>

        <div <?php echo $attribute_string; ?>>
            <?php if(!empty($settings['tag_icon'])){ ?>
                <span <?php echo $this->get_render_attribute_string( 'post-tags-icon-class' ); ?>>
                    <i <?php echo $this->get_render_attribute_string( 'post-tags-icon' ); ?>></i>
                </span>
            <?php } ?>
            <span <?php echo $this->get_render_attribute_string('post-tags-label-class');?>>
                <?php echo $settings['tag_label'];?>
            </span>
            <span <?php echo $this->get_render_attribute_string('post-tags-class'); ?>>
                <?php $post_tags = implode($settings['tag_separator'], $tags);
                echo $post_tags;
                ?>
            </span>
        </div>
        <?php
        return true;
    }

    protected function render_date($p,$settings,$attribute_string){
        $format_time = "g:i A";
        if($settings['date_format']=='custom'){
            $format = $settings['date_custom_format'];
        }else{
            $format = $settings['date_format'];
        }

        if($settings['date_type']=="published"){
            $post_date = get_the_date( $format, $p->ID );
        }else{
            $post_date = get_the_modified_date( $format, $p->ID );
        }
        
        $post_time = get_post_time($format_time, $p->ID);

        $this->add_render_attribute('post-date-class', 'class', 'ae-element-post-date' );
        $this->add_render_attribute('post-date-class', 'title', $post_time );
        $this->add_render_attribute('post-date-class', 'rel', 'date' );
        $this->add_render_attribute('post-date-icon-class','class','icon-wrapper');
        $this->add_render_attribute('post-date-icon-class','class','ae-element-post-date-icon');
        $this->add_render_attribute('post-date-icon','class',$settings['date_icon']);
        $this->add_render_attribute('post-date-label-class', 'class', 'ae-element-post-date-label' );
        ?>
        <div <?php echo $attribute_string; ?>>
            <?php if(!empty($settings['date_icon'])){ ?>
                <span <?php echo $this->get_render_attribute_string( 'post-date-icon-class' ); ?>>
                    <i <?php echo $this->get_render_attribute_string( 'post-date-icon' ); ?>></i>
                </span>
            <?php } ?>
            <span <?php echo $this->get_render_attribute_string('post-date-label-class');?>>
                <?php echo $settings['date_label'];?>
            </span>
            <span <?php echo $this->get_render_attribute_string('post-date-class'); ?>>
            <?php if($settings['disable_date_link'] == 'yes'){
                echo '<span>' . $post_date . '</span>';
            } else { ?>
                <a href="#" <?php echo $this->get_render_attribute_string( 'post-date-class' ); ?>><?php echo $post_date; ?></a>
            <?php } ?>
            </span>
        </div>
        <?php
        return true;
    }

    protected function render_author($p,$settings,$attribute_string){
        $post_author = $p->post_author;
        $post_author_name = get_the_author_meta('display_name', $post_author);
        $author_link = get_author_posts_url($post_author);
        $this->add_render_attribute('post-author-class', 'class', 'ae-element-post-author' );
        $this->add_render_attribute('post-author-class-span', 'class', 'ae-element-post-author' );
        $this->add_render_attribute('post-author-class', 'title', 'View All posts by '.$post_author_name );
        $this->add_render_attribute('post-author-class', 'rel', 'author' );
        $this->add_render_attribute('post-author-icon-class','class','icon-wrapper');
        $this->add_render_attribute('post-author-icon-class','class','ae-element-post-author-icon');
        $this->add_render_attribute('post-author-icon','class',$settings['author_icon']);
        $this->add_render_attribute('post-author-label-class', 'class', 'ae-element-post-author-label' );
        ?>
        <div <?php echo $attribute_string; ?>>
            <?php if(!empty($settings['author_icon'])){ ?>
                <span <?php echo $this->get_render_attribute_string( 'post-author-icon-class' ); ?>>
                    <i <?php echo $this->get_render_attribute_string( 'post-author-icon' ); ?>></i>
                </span>
            <?php } ?>
            <span <?php echo $this->get_render_attribute_string('post-author-label-class');?>>
                <?php echo $settings['author_label'];?>
            </span>
            <span <?php echo $this->get_render_attribute_string('post-author-class-span'); ?>>
            <?php if($settings['disable_author_link'] == 'yes'){ ?>
                <?php echo '<span>' . $post_author_name . '</span>'; ?>
            <?php } else { ?>
                <a href="<?php echo $author_link;?>" <?php echo $this->get_render_attribute_string('post-author-class'); ?>>
                <?php echo $post_author_name; ?>
                </a>
            <?php } ?>
            </span>
        </div>
        <?php
        return true;
    }

	protected function render_comment($p,$settings,$attribute_string){
        global $post;
        $this->add_render_attribute('post-comment-class', 'class', 'ae-element-post-comment' );
        $this->add_render_attribute('post-comment-class', 'rel', 'comment' );
        $this->add_render_attribute('post-comment-icon-class','class','icon-wrapper');
        $this->add_render_attribute('post-comment-icon-class','class','ae-element-post-comment-icon');
        $this->add_render_attribute('post-comment-icon','class',$settings['comment_icon']);
        $this->add_render_attribute('post-comment-label-class', 'class', 'ae-element-post-author-label' );

        ?>
        <div <?php echo $attribute_string; ?>>
            <?php if(!empty($settings['comment_icon'])){ ?>
                <span <?php echo $this->get_render_attribute_string( 'post-comment-icon-class' ); ?>>
                    <i <?php echo $this->get_render_attribute_string( 'post-comment-icon' ); ?>></i>
                </span>
            <?php } ?>
            <span <?php echo $this->get_render_attribute_string('post-comment-label-class');?>>
                <?php echo $settings['comment_label'];?>
            </span>
            <span <?php echo $this->get_render_attribute_string('post-comment-class'); ?>>
                <?php comments_popup_link(  $settings['no_comment_label'],  $settings['one_comment_label'],   $settings['more_comment_label'] ); ?>
            </span>
        </div>
        <?php

		return true;
	}
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Post_Meta() );