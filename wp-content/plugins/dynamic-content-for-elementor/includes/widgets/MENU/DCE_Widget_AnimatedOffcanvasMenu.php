<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor AnimatedOffcanvasMenu
 *
 * Elementor widget for Dynamic Content Elements
 *
 */
class DCE_Widget_AnimatedOffcanvasMenu extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-animatedoffcanvasmenu';
    }

    static public function is_enabled() {
        return false;
    }

    public function get_title() {
        return __('Animated Offcanvas Menu', 'dynamic-content-for-elementor');
    }
    public function get_description() {
      return __('Animated Offcanvas Menu', 'dynamic-content-for-elementor');
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/animated-offcanvas-menu/';
    }
    public function get_icon() {
        return 'icon-dyn-animatedoffcamvasmenu';
    }
    static public function get_position() {
        return 1;
    }
    public function get_script_depends() {
        return [ 'dce-tweenMax-lib','dce-timelineMax-lib','dce-animatedoffcanvasmenu'];
    }

    public function get_style_depends() {
        return [ 'elementor-icons'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
                'section_animatedoffcanvasmenu_settings', [
            'label' => __('Menu', 'dynamic-content-for-elementor'),
                ]
        );
        
        $this->add_control(
                'menu_animatedoffcanvasmenu', [
            'label' => __('Select menu', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => DCE_Helper::get_taxonomy_terms('nav_menu'),
            'default' => '0',
            
                ]
        );
        $this->add_control(
                'animatedoffcanvasmenu_depth', [
            'label' => __('Depth of levels', 'dynamic-content-for-elementor'),
            'description' => 'If 0, is any',
            'separator' => 'before',
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'min' => 0,
            'max' => 3,
            'step' => 1,
            'dynamic' => [
                'active' => false,
              ],
                ]
        );
        $this->add_responsive_control(
                'animatedoffcanvasmenu_rate', [
            'label' => __('Menu/SideBackground Rate', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'separator' => 'before',
            'default' => [
                'size' => 45,
                'unit' => '%',
            ],
            'size_units' => ['%'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1
                ]
            ],
            'frontend_available' => true,
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'animatedoffcanvasmenu_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-nav .dce-menu ul li a',
            'separator' => 'before',
                ]
        );
        
        $this->add_control(
                'title_menu_colors', [
            'label' => __('Colors', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            
                ]
        );

        $this->start_controls_tabs('menu_colors');

        $this->start_controls_tab(
                'menu_colors_normal',
                [
                    'label' => __('Normal', 'dynamic-content-for-elementor'),
                    
                ]
        );
        
        
        $this->add_control(
                'menu_color', [
            'label' => __('Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-nav .dce-menu ul li a' => 'color: {{VALUE}};',
            ],
            
                ]
        );


        /*$this->add_control(
                'menu_background_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-nav .dce-menu ul li a' => 'background-color: {{VALUE}};',
            ],
           
                ]
        );*/

        $this->end_controls_tab();

        $this->start_controls_tab(
                'menu_colors_hover',
                [
                    'label' => __('Hover', 'dynamic-content-for-elementor'),
                    
                ]
        );
        
        $this->add_control(
                'title_menu_hover', [
            'label' => __('Hover', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
                ]
        );

        
        $this->add_control(
                'menu_hover_color', [
            'label' => __('Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-nav .dce-menu ul li a:hover' => 'color: {{VALUE}};',
            ],
            
                ]
        );
        /*$this->add_control(
                'menu_background_hover_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-nav .dce-menu ul li a:hover' => 'background-color: {{VALUE}};',
            ]
                ]
        );*/

        $this->add_control(
                'menu_hover_border_color', [
            'label' => __('Border Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'condition' => [
                'button_border_border!' => '',
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-button-popoup:hover' => 'border-color: {{VALUE}};',
            ],
                ]
        );


        

        $this->end_controls_tab();

        $this->end_controls_tabs();



        $this->add_control(
                'dynamic_template',
                [
                    'label' => __('Template after menu', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Template Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'separator' => 'before',
                    'object_type' => 'elementor_library',
                ]
        );



        $this->end_controls_section();


        // ---------------- HAMBURGER ---------------



        $this->start_controls_section(
                'section_animatedoffcanvasmenu_hamburger', [
            'label' => __('Hamburger', 'dynamic-content-for-elementor'),
                ]
        );

        $this->add_control(
                'hamburger_style', [
            'label' => __('Hamburger Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'x',
            'options' => [
                'x' => __('X', 'dynamic-content-for-elementor'),
                'arrow_left' => __('Arrow Left', 'dynamic-content-for-elementor'),
                'arrow_right' => __('Arrow Right', 'dynamic-content-for-elementor'),
                'fall' => __('Fall', 'dynamic-content-for-elementor'),
            ],
            
                ]
        );
        $this->add_control(
                'title_button_colors', [
            'label' => __('Colors', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            
                ]
        );
        $this->start_controls_tabs('hamburger_colors');

        $this->start_controls_tab(
                'hamburger_colors_normal',
                [
                    'label' => __('Normal', 'dynamic-content-for-elementor'),
                ]
        );

        $this->add_control(
                'bars_color', [
            'label' => __('Bars Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-button-hamburger .bar' => 'background-color: {{VALUE}};',
            ],
            
                ]
        );


        $this->add_control(
                'button_background_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-button-hamburger .con' => 'background-color: {{VALUE}};',
            ],
            
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'hamburger_colors_hover',
                [
                    'label' => __('Hover', 'dynamic-content-for-elementor'),
                    
                ]
        );

        $this->add_control(
                'title_button_hover', [
            'label' => __('Hover', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
                ]
        );

        
        $this->add_control(
                'bars_hover_color', [
            'label' => __('Bars Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-button-hamburger .con.open .bar, {{WRAPPER}} .special-con.open .bar' => 'background-color: {{VALUE}};',
            ],
            
                ]
        );
        $this->add_control(
                'button_background_hover_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-button-popoup:hover' => 'background-color: {{VALUE}};',
            ]
                ]
        );

        $this->add_control(
                'button_hover_border_color', [
            'label' => __('Border Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'condition' => [
                'button_border_border!' => '',
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-button-popoup:hover' => 'border-color: {{VALUE}};',
            ],
                ]
        );


        

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        // ---------------------------------------------------------------
        $this->add_responsive_control(
                'button_align', [
            'label' => __('Alignment', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-right',
                ],
                
            ],
            'separator' => 'before',
            'prefix_class' => 'elementor-align-',
            'selectors' => [
                '{{WRAPPER}} .dce-button-wrapper' => 'text-align: {{VALUE}};',
            ],
            'default' => '',
                ]
        );
        // ---------------------------------------------------------------

        $this->add_responsive_control(
                'hamburger_size', [
            'label' => __('Hamburger Size', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'separator' => 'before',
            'default' => [
                'size' => 50,
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1
                ]
            ],
            /*'condition' => [
                'button_type' => 'hamburger'
            ],*/
            'selectors' => [
                '{{WRAPPER}} .dce-button-hamburger .bar' => 'width: {{SIZE}}{{UNIT}}',
                '{{WRAPPER}} .dce-button-hamburger .con.open .arrow-top, {{WRAPPER}} .dce-button-hamburger .con.open .arrow-bottom, {{WRAPPER}} .dce-button-hamburger .con.open .arrow-top-r, {{WRAPPER}} .dce-button-hamburger .con.open .arrow-bottom-r' => 'width: calc({{SIZE}}{{UNIT}} / 2)',
            ],
                ]
        );
        $this->add_control(
                'hamburger_weight', [
            'label' => __('Hamburger weight', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 5,
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 20,
                    'step' => 1
                ]
            ],
            /*'condition' => [
                'button_type' => 'hamburger'
            ],*/
            'selectors' => [
                '{{WRAPPER}} .dce-button-hamburger .bar' => 'height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .dce-button-hamburger .con.open .top' => 'top: 0; transform: translate(0px, calc(50% + ({{SIZE}}{{UNIT}} / 2))) rotate(45deg);',
                '{{WRAPPER}} .dce-button-hamburger .con.open .middle' => 'top: 0; width: 0;',
                '{{WRAPPER}} .dce-button-hamburger .con.open .bottom' => 'top: 0; transform: translate(0px, calc(50% - (({{SIZE}}{{UNIT}} / 2) + {{SIZE}}{{UNIT}}))) rotate(-45deg);',
                '{{WRAPPER}} .dce-button-hamburger .con.open .arrow-top' => 'top: calc({{SIZE}}{{UNIT}} / 4); transform: translate(0%,{{SIZE}}{{UNIT}}) rotateZ(45deg);',
                '{{WRAPPER}} .dce-button-hamburger .con.open .arrow-middle' => 'top: 0; transform: translate(-50%, 0)',
                '{{WRAPPER}} .dce-button-hamburger .con.open .arrow-bottom' => 'top: calc(-{{SIZE}}{{UNIT}} / 4); transform: translate(0%,-{{SIZE}}{{UNIT}}) rotateZ(-45deg);',
                '{{WRAPPER}} .dce-button-hamburger .con.open .arrow-top-r' => 'top: calc({{SIZE}}{{UNIT}} / 4); transform: translate(100%,{{SIZE}}{{UNIT}}) rotateZ(-45deg);',
                '{{WRAPPER}} .dce-button-hamburger .con.open .arrow-middle-r' => 'top: 0; transform: translate(50%, 0)',
                '{{WRAPPER}} .dce-button-hamburger .con.open .arrow-bottom-r' => 'top: calc(-{{SIZE}}{{UNIT}} / 4); transform: translate(100%,-{{SIZE}}{{UNIT}}) rotateZ(45deg);',
                '{{WRAPPER}} .dce-button-hamburger .special-con.open .arrow-top-fall' => 'top: 0;',
                '{{WRAPPER}} .dce-button-hamburger .special-con.open .arrow-middle-fall' => 'top: 0;',
                '{{WRAPPER}} .dce-button-hamburger .special-con.open .arrow-bottom-fall' => 'top: 0'
            ],
                ]
        );
        /*
          top: transform: translateY(calc(50% + {{SIZE}}{{UNIT}})) rotate(45deg);
          bottom: transform: translateY(calc(50% - {{SIZE}}{{UNIT}})) rotate(-45deg);

          top_r: rotateZ(-45deg) translate(calc(50% - 10px),9px);
          middle_r: transform: translateX(50%);
          bottom_r: rotateZ(45deg) translate(calc(50% - 10px),-9px);
         */
        $this->add_control(
                'hamburger_space', [
            'label' => __('Hamburger space', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 10,
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                '%' => [
                    'min' => 1,
                    'max' => 50,
                    'step' => 1
                ]
            ],
            /*'condition' => [
                'button_type' => 'hamburger'
            ],*/
            'selectors' => [
                //'{{WRAPPER}} .dce-button-hamburger .bar' => 'margin: {{SIZE}}{{UNIT}} auto',
                '{{WRAPPER}} .dce-button-hamburger .con .top, {{WRAPPER}} .dce-button-hamburger .con .arrow-top, {{WRAPPER}} .dce-button-hamburger .con .arrow-top-r, {{WRAPPER}} .dce-button-hamburger .special-con .arrow-top-fall' => 'top: -{{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .dce-button-hamburger .con .bottom, {{WRAPPER}} .dce-button-hamburger .con .arrow-bottom, {{WRAPPER}} .dce-button-hamburger .con .arrow-bottom-r, {{WRAPPER}} .dce-button-hamburger .special-con .arrow-bottom-fall' => 'top: {{SIZE}}{{UNIT}};'
                ],
            ]
        );


        // ********

        $this->add_control(
                'button_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-button-popoup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'button_type!' => 'image'
            ]
                ]
        );

        $this->add_control(
                'title_button_border', [
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'button_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'placeholder' => '1px',
            'default' => '1px',
            'selector' => '{{WRAPPER}} .dce-button-popoup',
                ]
        );

        $this->add_control(
                'button_border_radius', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-button-popoup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(), [
            'name' => 'button_box_shadow',
            'selector' => '{{WRAPPER}} .dce-button-popoup',
            'condition' => [
                'button_type!' => 'image'
            ]
                ]
        );


        $this->end_controls_section();
        

        $this->start_controls_section(
                'section_animatedoffcanvasmenu_sideof', [
            'label' => __('Side Background', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'background_overlay',
                    'label' => __('Background Overlay Color', 'dynamic-content-for-elementor'),
                    'types' => ['classic', 'gradient'],
                    /* 'fields_options' => [
                      'background' => [
                      'frontend_available' => true,
                      ],
                      'video_link' => [
                      'frontend_available' => true,
                      ],
                      ], */
                    /* 'default' => [
                      'color' => 'rgba(0,0,0,0.4)'
                      ], */
                    'selector' => '{{WRAPPER}} .dce-bg',
                    
                ]
        );
        $this->end_controls_section();
        

        $this->start_controls_section(
                'section_animatedoffcanvasmenu_items', [
            'label' => __('Custom ITEMS', 'dynamic-content-for-elementor'),
                ]
        );

        
        $repeater = new Repeater();

        

        $repeater->add_control(
                'animatedoffcanvasmenu_image', [
            'label' => __('Image', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::MEDIA,
            'default' => [
                'url' => '',
            ],
                ]
        );
        $repeater->add_control(
                'animatedoffcanvasmenu_speed_item', [
            'label' => __('Speed Factor', 'dynamic-content-for-elementor'),
            //'description' => 'If 0, the default value will be used',
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'min' => -1,
            'max' => 1,
            'step' => 0.01,
                ]
        );

        

        $this->add_control(
                'animatedoffcanvasmenujs', [
            'label' => __('Items', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::REPEATER,
            'default' => [
            ],
            'fields' => array_values($repeater->get_controls()),
            'title_field' => 'AnimatedOffcanvasMenu Item'
                ]
        );

        $this->end_controls_section();






        // ++++++++++++++++++++++ Close ++++++++++++++++++++++

        $this->start_controls_section(
                'section_style_close', [
            'label' => __('Close button', 'dynamic-content-for-elementor'),
            //'tab' => Controls_Manager::TAB_STYLE,
            
                ]
        );
        $this->add_control(
                'enable_close_button', [
            'label' => __('Enable close button', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                ]
        );
        $this->add_control(
                'close_type', [
            'label' => __('Close type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'x' => [
                    'title' => __('X', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-close',
                ],
                'icon' => [
                    'title' => __('Icon', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-asterisk',
                ],
                'image' => [
                    'title' => __('Image', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-picture-o',
                ],
                'text' => [
                    'title' => __('Text', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-italic',
                ],
            ],
            'toggle' => false,
            'default' => 'x',
            'condition' => [
                
            ]
                ]
        );

        $this->add_control(
                'close_icon', [
            'label' => __('Close Icon', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::ICON,
            'label_block' => true,
            'default' => 'fa fa-times',
            'condition' => [
                'close_type' => 'icon',
                
            ]
                ]
        );

        $this->add_control(
                'close_image',
                [
                    'label' => __('Close Image', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => '',
                    ],
                    'condition' => [
                        'close_type' => 'image',
                        
                    ]
                ]
        );

        $this->add_control(
                'close_text', [
            'label' => __('Close Text', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Close', 'dynamic-content-for-elementor'),
            'condition' => [
                'close_type' => 'text',
                
            ]
                ]
        );

        $this->start_controls_tabs('close_colors');

        $this->start_controls_tab(
                'close_colors_normal',
                [
                    'label' => __('Normal', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'close_type!' => 'image',
                        
                    ]
                ]
        );
        $this->add_control(
                'close_icon_color', [
            'label' => __('Icon color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-menu button.dce-close' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'close_type' => 'icon',
                
            ]
                ]
        );

        $this->add_control(
                'close_text_color', [
            'label' => __('Text color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-menu button.dce-close' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'close_type' => 'text',
                'close_text!' => '',
                
            ]
                ]
        );
        $this->add_control(
                'x_close_text_color', [
            'label' => __('X color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-menu .dce-close .dce-quit-ics:after, {{WRAPPER}} .dce-menu .dce-close .dce-quit-ics:before' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'close_type' => 'x',
                
            ]
                ]
        );
        $this->add_control(
                'close_bg_color', [
            'label' => __('Background color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-menu button.dce-close' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                
                'close_type!' => ['image', 'x'],
            ]
                ]
        );
        $this->add_control(
                'x_close_bg_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'condition' => [
                'close_type' => 'x',
                
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-menu .dce-close .dce-quit-ics' => 'background-color: {{VALUE}};',
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'x_close_bg_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-menu .dce-close .dce-quit-ics',
            'condition' => [
                'close_type' => 'x',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'close_bg_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-menu button.dce-close',
            'condition' => [
                'close_type!' => 'x',
            ]
                ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
                'close_colors_hover',
                [
                    'label' => __('Hover', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'close_type!' => 'image',
                        
                    ]
                ]
        );
        $this->add_control(
                'close_icon_color_hover', [
            'label' => __('Icon color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-menu button.dce-close:hover' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'close_type' => 'icon',
                
            ]
                ]
        );
        $this->add_control(
                'close_text_color_hover', [
            'label' => __('Text color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-menu button.dce-close:hover' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'close_type' => 'text',
                'close_text!' => '',
                
            ]
                ]
        );
        $this->add_control(
                'x_close_text_color_hover', [
            'label' => __('X color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-menu .dce-close:hover .dce-quit-ics:after, {{WRAPPER}} .dce-menu .dce-close:hover .dce-quit-ics:before' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'close_type' => 'x',
                
            ]
                ]
        );
        $this->add_control(
                'close_background_color_hover', [
            'label' => __('Background color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-menu button.dce-close:hover' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'close_type!' => ['image', 'x'],
                
            ]
                ]
        );
        $this->add_control(
                'x_close_background_color_hover', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-menu .dce-close .dce-quit-ics:hover' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'close_type' => 'x',
                
            ]
                ]
        );
        $this->add_control(
                'close_bg_color_hover', [
            'label' => __('Background color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-menu button.dce-close:hover' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                
                'close_bg_border_border!' => ''
            ]
                ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();



        //
        $this->add_responsive_control(
                'x_buttonsize_closemodal', [
            'label' => __('Button Size', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'separator' => 'before',
            'default' => [
                'size' => 50,
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 20,
                    'max' => 100,
                    'step' => 1
                ]
            ],
            'condition' => [
                'close_type' => 'x',
                
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-menu .dce-close .dce-quit-ics' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'x_weight_closemodal', [
            'label' => __('Close Width', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 1,
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 20,
                    'step' => 1
                ]
            ],
            'condition' => [
                'close_type' => 'x',
                
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-menu .dce-close .dce-quit-ics:after, {{WRAPPER}} .dce-menu .dce-close .dce-quit-ics:before' => 'height: {{SIZE}}{{UNIT}}; top: calc(50% - ({{SIZE}}{{UNIT}}/2));',
            ],
                ]
        );
        $this->add_control(
                'x_size_closemodal', [
            'label' => __('Close Size (%)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 60,
                'unit' => '%',
            ],
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 20,
                    'max' => 200,
                    'step' => 1
                ]
            ],
            'condition' => [
                'close_type' => 'x',
                
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-menu .dce-close .dce-quit-ics:after, {{WRAPPER}} .dce-menu .dce-close .dce-quit-ics:before' => 'width: {{SIZE}}{{UNIT}}; left: calc(50% - ({{SIZE}}{{UNIT}}/2));',
            ],
                ]
        );
        $this->add_responsive_control(
                'x_vertical_close', [
            'label' => __('Y Position', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 0,
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1
                ]
            ],
            'condition' => [
                'close_type' => 'x',
                
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-menu .dce-close .dce-quit-ics' => 'top: {{SIZE}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'x_horizontal_close', [
            'label' => __('X Position', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 0,
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1
                ]
            ],
            'condition' => [
                'close_type' => 'x',
                
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-menu .dce-close .dce-quit-ics' => 'right: {{SIZE}}{{UNIT}};',
            ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'close_typography',
            'label' => __('Close Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-menu button.dce-close:not(i)',
            'condition' => [
                'close_type' => 'text',
                'close_text!' => '',
                
            ]
                ]
        );

        
        $this->add_responsive_control(
                'close_size', [
            'label' => __('Icon Size', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 6,
                    'max' => 300,
                ],
            ],
            'default' => [
                'size' => 20,
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-menu button.dce-close' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .dce-menu button.dce-close .close-img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
            'condition' => [
                'close_type' => ['icon', 'image'],
                
            ]
                ]
        );


        $this->add_control(
                'close_bg_radius', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-menu button.dce-close' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'close_type!' => 'x',
                
            ]
                ]
        );
        $this->add_control(
                'close_margin', [
            'label' => __('Close Margin', 'dynamic-content-for-elementor'),
            'description' => __('Helpful to put close button external from modal putting negative values', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-menu button.dce-close' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'separator' => 'before',
            'condition' => [
                'close_type!' => 'x',
                
            ]
                ]
        );

        $this->add_control(
                'close_padding', [
            'label' => __('Close Padding', 'dynamic-content-for-elementor'),
            'description' => __('Please note that padding bottom has no effect - Left/Right padding will depend on button position!', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-menu button.dce-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'separator' => 'before',
            'condition' => [
                'close_type!' => 'x',
                
            ]
                ]
        );




        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings ) )
            return;

        ?>
        <div class="dce-menu-strip">
             





              
              <div class="dce-button-wrapper">
                  <div class="dce-button-hamburger">

                      <?php if ($settings['hamburger_style'] == 'x') { ?>
                          <div class="con">
                              <div class="bar top"></div>
                              <div class="bar middle"></div>
                              <div class="bar bottom"></div>
                          </div>
                      <?php } else if ($settings['hamburger_style'] == 'arrow_left') { ?>
                          <div class="con">
                              <div class="bar arrow-top-r"></div>
                              <div class="bar arrow-middle-r"></div>
                              <div class="bar arrow-bottom-r"></div>
                          </div>
                      <?php } else if ($settings['hamburger_style'] == 'arrow_right') { ?>
                          <div class="con">
                              <div class="bar arrow-top"></div>
                              <div class="bar arrow-middle"></div>
                              <div class="bar arrow-bottom"></div>
                          </div>
                      <?php } else if ($settings['hamburger_style'] == 'fall') { ?>
                          <div class="special-con">
                              <div class="bar arrow-top-fall"></div>
                              <div class="bar arrow-middle-fall"></div>
                              <div class="bar arrow-bottom-fall"></div>
                          </div>
                      <?php } ?>


                  </div>
              </div>
          
        </div>
        <!-- end Strip -->

        <!-- start Menu -->
        <div class="dce-menu-wrap">

           
           <div class="dce-bg"></div>
                  

 

            <div class="dce-nav">
              <?php
              /*wp_nav_menu( array(
                  'theme_location' => 'primary',
                  'items_wrap'     => '<ul><li id="item-id"><?php __( 'Menu:', 'textdomain' ); ?></li>%3$s</ul>'
              ) );*/
              
                ?>
                  
                    <div class="dce-menu">
                        <a class="dce-close close-<?php echo $settings['close_type']; ?>" aria-label="Close">

                            <?php if ($settings['close_type'] == 'text') { ?><span class="dce-button-text"><?php echo __($settings['close_text'], 'dynamic-content-for-elementor' . '_texts'); ?></span><?php } ?>

                                <?php if ($settings['close_type'] == 'icon') { ?><?php if ($settings['close_icon']) { ?><i class="<?php echo esc_attr($settings['close_icon']); ?>" aria-hidden="true"></i><?php } ?><?php } ?>

                                <?php if ($settings['close_type'] == 'image') { ?><?php if ($settings['close_image']['id']) { ?><img class="close-img" aria-hidden="true" src="<?php echo $settings['close_image']['url']; ?>" /><?php } ?><?php } ?>

                            <?php if ($settings['close_type'] == 'x') { ?>
                                                    <span class="dce-quit-ics"></span>
                            <?php } ?>

                            </a>

                      <div class="dce-nav-menu">
                        <?php
                        wp_nav_menu( array( 
                            'menu' => $settings['menu_animatedoffcanvasmenu'],
                            'menu_id'         => 'dce-ul-menu', 
                            'depth' => $settings['animatedoffcanvasmenu_depth'],
                            //'theme_location' => $settings['menu_animatedoffcanvasmenu'], 
                            //'container_class' => 'custom-menu-class' 
                        )); 
                        ?>
                        <!-- <ul>
                              <li class="dce-menu-item-1">
                                    <span id="order">01. </span>
                                    <span id="menu">&nbsp;Home /</span>
                                    <span id="tag">introduction</span>
                              </li>
                              <li class="dce-menu-item-2">
                                    <span id="order">02. </span>
                                    <span id="menu">&nbsp;Our Story /</span>
                                    <span id="tag">the beginning</span>
                              </li>
                              <li class="dce-menu-item-3">
                                    <span id="order">03. </span>
                                    <span id="menu">&nbsp;Portfolio /</span>
                                    <span id="tag">work we've done</span>
                              </li>
                              <li class="dce-menu-item-4">
                                    <span id="order">04. </span>
                                    <span id="menu">&nbsp;Clients/</span>
                                    <span id="tag">who we've worked with</span>
                              </li>
                              <li class="dce-menu-item-5">
                                    <span id="order">05. </span>
                                    <span id="menu">&nbsp;Contact /</span>
                                    <span id="tag">say hi!</span>
                              </li>
                        </ul> -->
                        
                        <div class="dce-template-after">
                          <?php
                          $dce_default_template = $settings[ 'dynamic_template' ];
                          //echo $dce_default_template; 
                          if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
                                $inlinecss = 'inlinecss="true"';
                            }else{
                                $inlinecss = '';
                            }
                          if (!empty($dce_default_template)) {
                              echo do_shortcode('[dce-elementor-template id="' . $dce_default_template . '" '.$inlinecss.']');
                          }

                          ?>
                        
                        </div>

                        <!--  -->
                    </div>

                </div>
            </div>
            <!-- end NAV -->

        </div>
        <!-- end Menu -->
        <?php
      
    }

    protected function _content_template() {
        
    }
}
