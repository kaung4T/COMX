<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Press Elements Post Custom Field BETA
 *
 * Elementor widget for Dynamic Content for Elementor
 *
 */
class DCE_Widget_Favorites extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-add-to-favorites';
    }

    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('Add to favorites', 'dynamic-content-for-elementor');
    }
    
    public function get_description() {
        return __('Create a user Posts favourite list', 'dynamic-content-for-elementor');
    }

    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/add-to-favorites/';
    }

    public function get_icon() {
        return 'icon-dyn-like';
    }

    protected function _register_controls() {

        $this->start_controls_section(
                'section_content', [
            'label' => __('Settings', 'dynamic-content-for-elementor')
                ]
        );

        $this->add_control(
                'dce_favorite_scope', [
            'label' => __('Scope', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'cookie' => [
                    'title' => __('Cookie', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-circle-o',
                ],
                'user' => [
                    'title' => __('User', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-user',
                ],
                'global' => [
                    'title' => __('Global', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-globe',
                ]
            ],
            'toggle' => false,
            'default' => 'user',
                ]
        );
        $this->add_control(
                'dce_favorite_counter',
                [
                    'label' => __('Show counter', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'dce_favorite_scope' => ['user','cookie'],
                    ],
                ]
        );
        $this->add_control(
                'dce_favorite_counter_icon',
                [
                    'label' => __('Icon Counter', 'elementor'),
                    'type' => Controls_Manager::ICONS,
                    'label_block' => true,
                    'fa4compatibility' => 'icon',
                    'condition' => [
                        'dce_favorite_scope' => ['user','cookie'],
                        'dce_favorite_counter!' => '',
                    ],
                ]
        );
        $this->add_control(
                'dce_favorite_sticky',
                [
                    'label' => __('Save as Sticky Posts', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'dce_favorite_scope' => 'global',
                    ],
                ]
        );
        $this->add_control(
                'dce_favorite_title', [
            'label' => __('Title', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => 'My Favorites',
            'separator' => 'after',
            'condition' => [
                'dce_favorite_scope' => 'global',
                'dce_favorite_sticky!' => '',
            ],
                ]
        );
        /* $this->add_control(
          'dce_favorite_icon',
          [
          'label' => __('Icon', 'elementor'),
          'type' => Controls_Manager::ICONS,
          'label_block' => true,
          'fa4compatibility' => 'icon',
          'condition' => [
          'dce_favorite_scope' => 'global',
          'dce_favorite_sticky!' => '',
          ],
          ]
          ); */
        
        
        $this->start_controls_tabs(
                'sticky_tabs',
                [
                    'condition' => [
                        'dce_favorite_scope' => 'global',
                        'dce_favorite_sticky!' => '',
                    ],
                ]
        );
        $this->start_controls_tab(
                'add_tab',
                [
                    'label' => __('Add', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'dce_favorite_title_add', [
            'label' => __('Title Add', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Add to my Favorites',
                ]
        );
        $this->add_control(
                'dce_favorite_icon_add',
                [
                    'label' => __('Icon Add', 'elementor'),
                    'type' => Controls_Manager::ICONS,
                    'label_block' => true,
                    'fa4compatibility' => 'icon',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
                'remove_tab',
                [
                    'label' => __('Remove', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'dce_favorite_title_remove', [
            'label' => __('Title Remove', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Remove from my Favorites',
                ]
        );
        $this->add_control(
                'dce_favorite_icon_remove',
                [
                    'label' => __('Icon Remove', 'elementor'),
                    'type' => Controls_Manager::ICONS,
                    'label_block' => true,
                    'fa4compatibility' => 'icon',
                ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_control(
                'dce_favorite_cookie_days', [
            'label' => __('Cookie expiration', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 365,
            'min' => 0,
            'description' => __('Value is in Days. Set 0 or empty for Session duration.', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_favorite_scope' => 'cookie',
            ],
                ]
        );

        $repeater_fields = new \Elementor\Repeater();
        $repeater_fields->add_control(
                'dce_favorite_key', [
            'label' => __('Key', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => 'dce_favorite_' . mt_rand(),
                ]
        );
        $repeater_fields->add_control(
                'dce_favorite_title', [
            'label' => __('Title', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'separator' => 'after',
                ]
        );
        /* $repeater_fields->add_control(
          'dce_favorite_icon',
          [
          'label' => __('Icon', 'elementor'),
          'type' => Controls_Manager::ICONS,
          'label_block' => true,
          'fa4compatibility' => 'icon',
          ]
          ); */
        $repeater_fields->start_controls_tabs(
                'repe_tabs'
        );
        $repeater_fields->start_controls_tab(
                'add_repe_tab',
                [
                    'label' => __('Add', 'dynamic-content-for-elementor'),
                ]
        );
        $repeater_fields->add_control(
                'dce_favorite_title_add', [
            'label' => __('Title Add', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
                ]
        );
        $repeater_fields->add_control(
                'dce_favorite_icon_add',
                [
                    'label' => __('Icon Add', 'elementor'),
                    'type' => Controls_Manager::ICONS,
                    'label_block' => true,
                    'fa4compatibility' => 'icon',
                ]
        );
        $repeater_fields->end_controls_tab();
        $repeater_fields->start_controls_tab(
                'remove_repe_tab',
                [
                    'label' => __('Remove', 'dynamic-content-for-elementor'),
                ]
        );
        $repeater_fields->add_control(
                'dce_favorite_title_remove', [
            'label' => __('Title Remove', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
                ]
        );
        $repeater_fields->add_control(
                'dce_favorite_icon_remove',
                [
                    'label' => __('Icon Remove', 'elementor'),
                    'type' => Controls_Manager::ICONS,
                    'label_block' => true,
                    'fa4compatibility' => 'icon',
                ]
        );
        $repeater_fields->end_controls_tab();
        $repeater_fields->end_controls_tabs();
        $this->add_control(
                'dce_favorite_list', [
            'label' => __('Favorite lists', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater_fields->get_controls(),
            'title_field' => '{{{ dce_favorite_title }}}',
            'default' => ['dce_favorite_key' => 'my_favorites', 'dce_favorite_title' => 'My Favorites', 'dce_favorite_title_add' => 'Add to my Favorites', 'dce_favorite_title_remove' => 'Remove from my Favorites'],
            'condition' => [
                //'dce_favorite_scope!' => 'global',
                'dce_favorite_sticky' => '',
            ],
                ]
        );

        $this->add_control(
                'dce_favorite_remove',
                [
                    'label' => __('Can remove', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                ]
        );

        $this->add_control(
                'dce_favorite_ajax',
                [
                    'label' => __('Ajax', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                ]
        );
        $this->end_controls_section();


        $this->start_controls_section(
                'section_button',
                [
                    'label' => __('Button', 'elementor'),
                ]
        );
        $this->add_control(
                'button_type',
                [
                    'label' => __('Type', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        '' => __('Default', 'elementor'),
                        'info' => __('Info', 'elementor'),
                        'success' => __('Success', 'elementor'),
                        'warning' => __('Warning', 'elementor'),
                        'danger' => __('Danger', 'elementor'),
                    ],
                    'prefix_class' => 'elementor-button-',
                ]
        );
        $this->add_control(
                'size',
                [
                    'label' => __('Size', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'sm',
                    'options' => DCE_Helper::get_button_sizes(),
                    'style_transfer' => true,
                ]
        );
        $this->add_control(
                'icon_align',
                [
                    'label' => __('Icon Position', 'elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'default' => 'left',
                    'options' => [
                        'left' => [
                            'title' => __('Before', 'elementor'),
                            'icon' => 'fa fa-align-left',
                            ],
                        'right' => [
                            'title' => __('After', 'elementor'),
                            'icon' => 'fa fa-align-right',
                            ],
                    ],
                    'toggle' => false,
                ]
        );
        $this->add_control(
                'icon_indent',
                [
                    'label' => __('Counter Spacing', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'dce_favorite_counter!' => '',
                    ],
                ]
        );
        
        $this->add_control(
                'counter_align',
                [
                    'label' => __('Counter Position', 'elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'default' => 'left',
                    'options' => [
                        'left' => [
                            'title' => __('Before', 'elementor'),
                            'icon' => 'fa fa-align-left',
                            ],
                        'right' => [
                            'title' => __('After', 'elementor'),
                            'icon' => 'fa fa-align-right',
                            ],
                    ],
                    'toggle' => false,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-button .elementor-button-counter' => 'float: {{VALUE}};',
                        //'{{WRAPPER}} .elementor-button .elementor-align-counter-right' => 'float: {{VALUE}};',
                        '{{WRAPPER}} .elementor-button .elementor-align-counter-left' => 'border-right: 4px solid;',
                        '{{WRAPPER}} .elementor-button .elementor-align-counter-right' => 'border-left: 4px solid;',
                    ],
                    'render_type' => 'template',
                    'condition' => [
                        'dce_favorite_counter!' => '',
                    ],
                ]
        );
        $this->add_control(
                'counter_padding',
                [
                    'label' => __('Counter Padding', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-button .elementor-align-counter-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .elementor-button .elementor-align-counter-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'dce_favorite_counter!' => '',
                    ],
                ]
        );
        $this->add_control(
                'counter_indent',
                [
                    'label' => __('Counter Spacing', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-button .elementor-align-counter-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .elementor-button .elementor-align-counter-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'dce_favorite_counter!' => '',
                    ],
                ]
        );
        $this->end_controls_section();
        
        $this->start_controls_section(
                'section_visitors',
                [
                    'label' => __('Visitors', 'elementor'),
                    'condition' => [
                        'dce_favorite_scope' => 'user',
                    ],
                ]
        );
        $this->add_control(
                'dce_favorite_visitor_hide',
                [
                    'label' => __('Hide Button for NON Logged Users', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                ]
        );
        $this->add_control(
                'dce_favorite_visitor_login', [
                    'label' => __('Login url', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::URL,
                    'default' => [
                            'url' => wp_login_url(),
                            'is_external' => false,
                            'nofollow' => false,
                    ],
                    'condition' => [
                        'dce_favorite_visitor_hide' => '',
                    ],
                ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
                'section_messages',
                [
                    'label' => __('Messages', 'elementor'),
                ]
        );
        $this->add_control(
                'dce_favorite_msg_add_enable',
                [
                    'label' => __('Enable Add success message', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                ]
        );
        $this->add_control(
                'dce_favorite_msg_add', [
            'label' => __('Add success message', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Post saved in your favorites',
            'condition' => [
                'dce_favorite_msg_add_enable!' => '',
            ],
                ]
        );


        $this->add_control(
                'dce_favorite_msg_remove_enable',
                [
                    'label' => __('Enable Remove success message', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'dce_favorite_remove!' => '',
                    ],
                ]
        );
        $this->add_control(
                'dce_favorite_msg_remove', [
            'label' => __('Remove success message', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Post removed from your favorites',
            'condition' => [
                'dce_favorite_msg_remove_enable!' => '',
                'dce_favorite_remove!' => '',
            ],
                ]
        );
        
        $this->add_control(
                'dce_favorite_msg_floating',
                [
                    'label' => __('Floating message', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .dce-notice' => 'position: fixed; display: block; z-index: 100;',
                    ],
                ]
        );
        $this->end_controls_section();


        $this->start_controls_section(
                'section_style',
                [
                    'label' => __('Button', 'elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_responsive_control(
                'align',
                [
                    'label' => __('Alignment', 'elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __('Left', 'elementor'),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __('Center', 'elementor'),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __('Right', 'elementor'),
                            'icon' => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => __('Justified', 'elementor'),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'prefix_class' => 'elementor%s-align-',
                    'default' => '',
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'typography',
                    'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button',
                ]
        );

        $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'text_shadow',
                    'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button',
                ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
                'tab_button_normal',
                [
                    'label' => __('Normal', 'elementor'),
                ]
        );

        $this->add_control(
                'button_text_color',
                [
                    'label' => __('Text Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'background_color',
                [
                    'label' => __('Background Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_button_hover',
                [
                    'label' => __('Hover', 'elementor'),
                ]
        );

        $this->add_control(
                'hover_color',
                [
                    'label' => __('Text Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
                        '{{WRAPPER}} a.elementor-button:hover svg, {{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} a.elementor-button:focus svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_background_hover_color',
                [
                    'label' => __('Background Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_hover_border_color',
                [
                    'label' => __('Border Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => [
                        'border_border!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'hover_animation',
                [
                    'label' => __('Hover Animation', 'elementor'),
                    'type' => Controls_Manager::HOVER_ANIMATION,
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'border',
                    'selector' => '{{WRAPPER}} .elementor-button',
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'border_radius',
                [
                    'label' => __('Border Radius', 'elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'button_box_shadow',
                    'selector' => '{{WRAPPER}} .elementor-button',
                ]
        );

        $this->add_responsive_control(
                'text_padding',
                [
                    'label' => __('Padding', 'elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
                'section_messages_style',
                [
                    'label' => __('Messages', 'elementor-pro'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'message_typography',
                    'selector' => '{{WRAPPER}} .elementor-message',
                ]
        );

        $this->add_control(
                'success_message_color',
                [
                    'label' => __('Success Message Color', 'elementor-pro'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-message.elementor-message-success' => 'color: {{COLOR}};',
                    ],
                ]
        );

        $this->add_control(
                'error_message_color',
                [
                    'label' => __('Error Message Color', 'elementor-pro'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-message.elementor-message-danger' => 'color: {{COLOR}};',
                    ],
                ]
        );

        $this->add_control(
                'inline_message_color',
                [
                    'label' => __('Inline Message Color', 'elementor-pro'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-message.elementor-help-inline' => 'color: {{COLOR}};',
                    ],
                ]
        );
        
        $this->add_control(
                'message_full_width', [
            'label' => __('Extend Full Window', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'selectors' => [
                '{{WRAPPER}} .dce-notice' => 'width: 100%; left: 0;',
            ],
                ]
        );
        $this->add_control(
                'message_align', [
            'label' => __('Horizontal alignment', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-h-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-h-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-h-align-right',
                ],
            ],
            'default' => 'right',
                    'condition' => ['message_full_width' => ''],
                ]
        );

        $this->add_control(
                'message_valign', [
            'label' => __('Vertical alignment', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'bottom' => [
                    'title' => __('Bottom', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-v-align-bottom',
                ],
                'middle' => [
                    'title' => __('Middle', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-v-align-middle',
                ],
                'top' => [
                    'title' => __('Top', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-v-align-top',
                ],
            ],
            'default' => 'bottom',
                ]
        );
        $this->add_control(
                'message_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-notice' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'message_margin', [
            'label' => __('Margin', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-notice' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'message_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'placeholder' => '1px',
            'selector' => '{{WRAPPER}} .dce-notice',
                ]
        );
        $this->add_control(
                'message_border_radius', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-notice' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(), [
            'label' => __('Message shadow', 'dynamic-content-for-elementor'),
            'name' => 'message_box_shadow',
            'selector' => '{{WRAPPER}} .dce-notice',
                ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;
        // ------------------------------------------
        
        $user_id = get_current_user_id();
        if (!$user_id && $settings['dce_favorite_scope'] == 'user' && $settings['dce_favorite_visitor_hide']) {
            return;
        }
        
        
        $post_ID = get_the_ID();
        if (!empty($_GET['dce_post_id']) && $_GET['dce_post_id'] != $post_ID) {
            $post_ID = $_GET['dce_post_id'];
        }
        $element_ID = $this->get_id();

        $act_add = $act_remove = false;
        // add or remove
        if (!empty($_GET['eid']) && $_GET['eid'] == $element_ID) {
            if (!empty($_GET['dce_list'])) {
                $list_key = $_GET['dce_list'];

                $favorite_value = $this->get_favorite_value($list_key, $settings['dce_favorite_scope']);

                $dce_act = (!empty($_GET['dce_act'])) ? $_GET['dce_act'] : 'auto';
                if (!empty($favorite_value) && is_array($favorite_value)) {
                    $favorite_pos = array_search($post_ID, $favorite_value);
                    switch ($dce_act) {
                        case 'add':
                            if ($favorite_pos === false) {
                                $favorite_value[] = $post_ID;
                                $act_add = true;
                            }
                            break;
                        case 'remove':
                            if ($favorite_pos !== false) {
                                if ($settings['dce_favorite_remove']) {
                                    unset($favorite_value[$favorite_pos]);
                                    $act_remove = true;
                                }
                            }
                            break;
                        default:
                            if ($favorite_pos === false) {
                                $favorite_value[] = $post_ID;
                                $act_add = true;
                            } else {
                                if ($settings['dce_favorite_remove']) {
                                    unset($favorite_value[$favorite_pos]);
                                    $act_remove = true;
                                }
                            }
                    }
                } else {
                    if ($dce_act != 'remove') {
                        $favorite_value = array($post_ID);
                        $act_add = true;
                    }
                }

                switch ($settings['dce_favorite_scope']) {
                    case 'user':
                        $user_id = get_current_user_id();
                        update_user_meta($user_id, $list_key, $favorite_value);
                        break;
                    case 'global':
                        update_option($list_key, $favorite_value);
                        break;
                    case 'cookie':
                        $favorite_value = implode(', ', $favorite_value);
                        $cookie_days = ($settings['dce_favorite_cookie_days']) ? time() + (86400 * $settings['dce_favorite_cookie_days']) : 0; // 86400 = 1 day
                        @setcookie($list_key, $favorite_value, $cookie_days, "/");
                        if ($settings['dce_favorite_counter']) {
                            $cookies = get_option('dce_favorite_cookies', array());
                            if (isset($cookies[$list_key][$post_ID])) {
                                if ($act_add) {
                                    $cookies[$list_key][$post_ID]++;
                                } else {
                                    $cookies[$list_key][$post_ID]--;
                                }
                            } else {
                                $cookies[$list_key][$post_ID] = 1;
                            }
                            //var_dump($cookies);
                            update_option('dce_favorite_cookies', $cookies);
                        }
                        break;
                }
            }
        }




        $this->add_render_attribute('wrapper', 'class', 'elementor-button-wrapper');

        $this->add_render_attribute('button', 'class', 'dce-button');
        $this->add_render_attribute('button', 'class', 'elementor-button');
        $this->add_render_attribute('button', 'role', 'button');
        $this->add_render_attribute('button', 'type', 'button');
        $this->add_render_attribute('button', 'id', "dce-favorite-btn-" . $element_ID);

        if (!empty($settings['button_css_id'])) {
            $this->add_render_attribute('button', 'id', $settings['button_css_id']);
        }

        if (!empty($settings['size'])) {
            $this->add_render_attribute('button', 'class', 'elementor-size-' . $settings['size']);
        }

        if ($settings['hover_animation']) {
            $this->add_render_attribute('button', 'class', 'elementor-animation-' . $settings['hover_animation']);
        }
        
        if (!empty($settings['dce_favorite_list']) && count($settings['dce_favorite_list']) > 1) {
            $this->add_render_attribute('wrapper', 'class', 'btn-group');
            $this->add_render_attribute('wrapper', 'role', 'group');
        }

        $this->add_render_attribute([
            'content-wrapper' => [
                'class' => 'elementor-button-content-wrapper',
            ],
            'icon-align' => [
                'class' => [
                    'elementor-button-icon',
                    'elementor-align-icon-' . $settings['icon_align'],
                ],
            ],
            'text' => [
                'class' => 'elementor-button-text',
            ],
        ]);
        $this->add_inline_editing_attributes('text', 'none');
        
        if (!empty($settings['dce_favorite_counter'])) {
            $this->add_render_attribute([
                'counter-align' => [
                    'class' => [
                        'elementor-button-counter',
                        'elementor-align-counter-' . $settings['counter_align'],
                    ],
                ]
            ]);
        }


        if ($settings['dce_favorite_scope'] == 'global' && $settings['dce_favorite_sticky']) {
            $settings['dce_favorite_list'] = array(
                array(
                    'dce_favorite_title_add' => $settings['dce_favorite_title_add'],
                    'dce_favorite_icon_add' => $settings['dce_favorite_icon_add'],
                    'dce_favorite_title_remove' => $settings['dce_favorite_title_remove'],
                    'dce_favorite_icon_remove' => $settings['dce_favorite_icon_remove'],
                    'dce_favorite_key' => 'sticky_posts',
                ),
            );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>   

        <?php if (count($settings['dce_favorite_list']) > 1) { ?>
                <!--<a href="#" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php
                echo "Select your list";
                ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" href="#">Dropdown link</a>
                    <a class="dropdown-item" href="#">Dropdown link</a>
                </div>-->
                COMING SOON ;)

            <?php
        } else {
            $list = reset($settings['dce_favorite_list']);
            
            $favorite_value = $this->get_favorite_value($list['dce_favorite_key'], $settings['dce_favorite_scope']);
            //var_dump($favorite_value);
            $is_favorite = $act_add;
            if ($favorite_value && in_array($post_ID, $favorite_value) && !$act_remove) {
                $is_favorite = true;
            }
            if ($is_favorite) {
                $icon = 'dce_favorite_icon_remove';
                $title = 'dce_favorite_title_remove';
            } else {
                $icon = 'dce_favorite_icon_add';
                $title = 'dce_favorite_title_add';
            }

            if ($is_favorite && !$settings['dce_favorite_remove']) {
                $this->add_render_attribute('button', 'class', 'elementor-button-disabled');
                $this->add_render_attribute('button', 'href', '#');
                $this->add_render_attribute('button', 'onclick', 'return false;');
            } else {
                if (!$user_id && $settings['dce_favorite_scope'] == 'user' && !$settings['dce_favorite_visitor_hide']) {
                    $btn_url = $settings['dce_favorite_visitor_login']['url'];
                    if ( $settings['dce_favorite_visitor_login']['nofollow'] ) {
                        $this->add_render_attribute( 'button', 'rel', 'nofollow' );
                    }
                    if ( $settings['dce_favorite_visitor_login']['is_external'] ) {
                        $this->add_render_attribute( 'button', 'target', '_blank' );
                    }
                } else {
                    $btn_url = get_permalink();
                    $btn_url = add_query_arg('eid', $element_ID, $btn_url);
                    $btn_url = add_query_arg('dce_list', $list['dce_favorite_key'], $btn_url);
                    $btn_url = add_query_arg('dce_post_id', $post_ID, $btn_url);
                }
                $this->add_render_attribute('button', 'href', $btn_url);
                $this->add_render_attribute('button', 'class', 'elementor-button-link');                
            }
            ?>
                <a <?php echo $this->get_render_attribute_string('button'); ?>>
                    <span <?php echo $this->get_render_attribute_string('content-wrapper'); ?>>
                        <?php if (!empty($settings['dce_favorite_counter'])) { 
                            if ($settings['dce_favorite_scope'] == 'user') {
                                $counter = $this->get_user_counter($list['dce_favorite_key']);
                            }
                            if ($settings['dce_favorite_scope'] == 'cookie') {
                                $counter = $this->get_cookie_counter($list['dce_favorite_key'], $post_ID);
                            }
                            //var_dump($counter);
                            if ($counter) {
                            ?>
                            <span <?php echo $this->get_render_attribute_string('counter-align'); ?>>
                                <?php if (!empty($settings['dce_favorite_counter_icon']['value'])) { Icons_Manager::render_icon($settings['dce_favorite_counter_icon'], ['aria-hidden' => 'true']); } ?> <?php echo $counter ?>
                            </span>
                        <?php }
                        } ?>
                        <?php if (!empty($list[$icon]['value'])) { ?>
                            <span <?php echo $this->get_render_attribute_string('icon-align'); ?>>
                            <?php Icons_Manager::render_icon($list[$icon], ['aria-hidden' => 'true']); ?>
                            </span>
                        <?php } ?>
                        <span <?php echo $this->get_render_attribute_string('text'); ?>><?php echo $list[$title]; ?></span>
                    </span>
                </a>
            <?php
        }
        ?>

        </div>
        <?php 
        $modal_class = $settings['dce_favorite_msg_floating'] ? ' dce-modal' : '';
        $modal_class = $settings['dce_favorite_msg_floating'] && !empty($settings['message_align']) ? $modal_class.' modal-'.$settings['message_align'] : $modal_class;
        $modal_class = $settings['dce_favorite_msg_floating'] && !empty($settings['message_valign']) ? $modal_class.' modal-'.$settings['message_valign'] : $modal_class;
        ?>
        <?php if (($act_add || \Elementor\Plugin::$instance->editor->is_edit_mode()) && $settings['dce_favorite_msg_add_enable']) { ?>
            <div class="elementor-message elementor-message-success dce-notice dce-notice-favorite dce-notice-favorite-add elementor-alert elementor-alert-success<?php echo $modal_class; ?>">
                <span class="elementor-alert-description-asd"><?php echo $settings['dce_favorite_msg_add']; ?></span>
                <button type="button" class="elementor-alert-dismiss" onClick="jQuery(this).parent().fadeOut();">
                    <span aria-hidden="true">&times;</span>
                    <span class="elementor-screen-only"><?php echo __('Dismiss alert', 'elementor'); ?></span>
                </button>
            </div>
        <?php }
        if (($act_remove || \Elementor\Plugin::$instance->editor->is_edit_mode()) && $settings['dce_favorite_msg_remove_enable']) {
            ?>
            <div class="elementor-message elementor-message-danger dce-notice dce-notice-favorite dce-notice-favorite-remove elementor-alert elementor-alert-warning<?php echo $modal_class; ?>">
                <span class="elementor-alert-description-asd"><?php echo $settings['dce_favorite_msg_remove']; ?></span>
                <button type="button" class="elementor-alert-dismiss" onClick="jQuery(this).parent().fadeOut();">
                    <span aria-hidden="true">&times;</span>
                    <span class="elementor-screen-only"><?php echo __('Dismiss alert', 'elementor'); ?></span>
                </button>
            </div>
        <?php
        }
    }

    public function get_user_favorites($meta_key = '', $post_ID = 0, $user_ID = 0) {
        if (!$post_ID) {
            $post_ID = get_the_ID();
        }
        if (!$meta_key) {
            $settings = $this->get_settings_for_display();
            if (!empty($settings['dce_favorite_list'])) {
                $list = reset($settings['dce_favorite_list']);
                $meta_key = $list['dce_favorite_key'];
            }
        }
        $args = array(
            'meta_query' => array(
                array(
                    'key' => $meta_key,
                    'value' => sprintf(':"%s";', $post_ID),
                    'compare' => 'LIKE'
                )
            )
        );
        $user_query = new WP_User_Query($args);
        // User Loop
        $count = 0;
        if (!empty($user_query->get_results())) {
            foreach ($user_query->get_results() as $user) {
                //echo '<p>' . $user->display_name . '</p>';
                $user_favorites = get_user_meta($user->ID, $meta_key, true);
                if (in_array($post_ID, $user_favorites)) {
                    $count++;
                }
            }
        }
        //$count = $user_query->total_users;
        return $count;
    }

    public function get_favorite_value($list_key = '', $scope = '') {
        $favorite_value = array();
        switch ($scope) {
            case 'user':
                $user_id = get_current_user_id();
                $favorite_value = get_user_meta($user_id, $list_key, true);
                break;
            case 'global':
                $favorite_value = get_option($list_key);
                break;
            case 'cookie':
                if (isset($_COOKIE[$list_key])) {
                    $favorite_value = DCE_Helper::str_to_array(',', $_COOKIE[$list_key]);
                }
                break;
        }
        return $favorite_value;
    }
    
    public function get_user_counter($list_key = '') {
        $args  = array(
            'meta_key' => $list_key,
            //'meta_value' => 'developer',
            'meta_compare' => 'EXISTS'
        );
        $user_query = new \WP_User_Query( $args );
        return $user_query->get_total();
    }
    
    public function get_cookie_counter($list_key = '', $post_ID) {
        $cookies = get_option('dce_favorite_cookies', array());
        if (isset($cookies[$list_key][$post_ID])) {
            return intval($cookies[$list_key][$post_ID]);
        }
        return 0;
    }

}
