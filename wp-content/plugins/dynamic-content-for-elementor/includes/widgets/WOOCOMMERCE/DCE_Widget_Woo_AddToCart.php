<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Utils;
use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\Controls\DCE_Group_Control_Transform_Element;
//use DynamicContentForElementor\Group_Control_AnimationElement;

if (!defined('ABSPATH')) {
    exit;
}
class DCE_Widget_Woo_AddToCart extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-woocommerce-addToCart';
    }
    
    static public function is_enabled() {
        return false;
    }
    
    public function get_title() {
        return __('Add to cart', 'dynamic-content-for-elementor');
    }
    
    public function get_icon() {
        return 'icon-dyn-woo_addtocart';
    }
    
    static public function get_position() {
        return 1;
    }
    public function get_plugin_depends() {
        return array('woocommerce' => 'woocommerce');
    }
    protected function _register_controls() {
        $this->start_controls_section(
            'section_settings', [
                'label' => __('Settings', 'dynamic-content-for-elementor'),
            ]
        );
        $this->add_responsive_control(
            'align', [
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
                    ]
                ],
                'default' => 'left',
                'prefix_class' => 'align-',
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .dce-add-to-cart form' => 'justify-content: {{VALUE}};'
                ], 
            //'prefix_class' => 'acfposts-align-'
            ]
        );
        $this->add_control(
            'hide_quantity', [
                'label' => __('Hide Quantity', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .quantity' => 'display: {{VALUE}};',
                ], 
            ]
        );
        $this->add_control(
            'hide_stock', [
                'label' => __('Hide Stock', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .stock' => 'display: {{VALUE}};',
                ], 
            ]
        );
        $this->end_controls_section();

        // ------------------------------------------- [SECTION STYLE]
        $this->start_controls_section(
            'section_style', [
                'label' => __('Button', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'wooc_color', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .button > span, {{WRAPPER}} .dce-add-to-cart .button .icon-rm' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .button--asolo:before, {{WRAPPER}} .button--asolo:after' => 'border-color: {{VALUE}};'
                ],
                
            ]
        );

        $this->add_control(
            'wooc_bgcolor', [
                'label' => __('Background Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .button:not(.button--pipaluk), {{WRAPPER}} .button--pipaluk:after, {{WRAPPER}} .button--tamaya:before, {{WRAPPER}} .button--tamaya:after' => 'background-color: {{VALUE}};',
                ],
                
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .dce-add-to-cart .button',
            ]
        );
        
        $this->add_control(
            'wooc_space_heading',
            [
                'label' => __( 'Space', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'wooc_padding', [
                'label' => __('Padding', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],
                'default' => [
                    'top' => 10,
                    'right' => 20,
                    'bottom' => 10,
                    'left' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .button > span, {{WRAPPER}} .dce-add-to-cart .button:after, {{WRAPPER}} .dce-add-to-cart .button:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .dce-add-to-cart .button.icon_button .icon-rm' => 'top: {{TOP}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};'
                ],
                
            ]
        );
        $this->add_responsive_control(
            'wooc_margin',
                [
                'label'         => __( 'Margin', 'dynamic-content-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', '%' ],
                'selectors'     => [
                        '{{WRAPPER}} .dce-add-to-cart .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_control(
            'wooc_style_heading',
            [
                'label' => __( 'Style', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'wooc_border',
                'label' => __('Border', 'dynamic-content-for-elementor'),            
                'selector' => '{{WRAPPER}} .dce-add-to-cart .button, {{WRAPPER}} .button--asolo:after, {{WRAPPER}} .button--asolo:before',
            ]
        );
        
        $this->add_control(
            'wooc_border_radius', [
                'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .button, {{WRAPPER}} .dce-add-to-cart .button:before, {{WRAPPER}} .dce-add-to-cart .button:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                
            ]
        );
        $this->add_group_control(
             Group_Control_Text_Shadow::get_type(),
                [
                    'name'      => 'text_shadow',
                    'selector'  => '{{WRAPPER}} .dce-add-to-cart .button',
                ]
            );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(), [
                'name' => 'box_shadow_wooc',
                'selector' => '{{WRAPPER}} .dce-add-to-cart .button',
                
            ]
        );
        
        $this->end_controls_section();


        // ------------------------------------------- [SECTION STYLE - Quantity]
        $this->start_controls_section(
            'section_quantity_style', [
                'label' => __('Quantity', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'hide_quantity' => '',
                ]
            ]
        );
        
        $this->add_control(
            'quantity_color', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .quantity .qty' => 'color: {{VALUE}};'
                ],
                
            ]
        );

        $this->add_control(
            'quantity_bgcolor', [
                'label' => __('Background Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .quantity .qty' => 'background-color: {{VALUE}};',
                ],
                
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'qty_typography',
                'label' => __('Typography', 'dynamic-content-for-elementor'),
                'selector' => '{{WRAPPER}} .dce-add-to-cart .quantity .qty',
            ]
        );
        
        $this->add_control(
            'quantity_width', [
                'label' => __('Width', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .quantity .qty' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'quantity_style_heading',
            [
                'label' => __( 'Style', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'quantity_border',
                'label' => __('Border', 'dynamic-content-for-elementor'),            
                'selector' => '{{WRAPPER}} .dce-add-to-cart .quantity .qty',
                
            ]
        );
        
        $this->add_control(
            'quantity_border_radius', [
                'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .quantity .qty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                
            ]
        );
        $this->add_group_control(
             Group_Control_Text_Shadow::get_type(),
                [
                    'name'      => 'qty_text_shadow',
                    'selector'  => '{{WRAPPER}} .dce-add-to-cart .quantity .qty',
                ]
            );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(), [
                'name' => 'qty_box_shadow',
                'selector' => '{{WRAPPER}} .dce-add-to-cart .quantity .qty',
                
            ]
        );
        $this->add_control(
            'quantity_space_heading',
            [
                'label' => __( 'Space', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'quantity_padding', [
                'label' => __('Padding', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],
                
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .quantity .qty' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                
            ]
        );
        
        $this->add_responsive_control(
            'quantity_margin',
                [
                'label'         => __( 'Margin', 'dynamic-content-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', '%' ],
                'selectors'     => [
                        '{{WRAPPER}} .dce-add-to-cart .quantity .qty' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_section();


        // ------------------------------------------- [SECTION STYLE - Quantity Plus/Minus]
        $this->start_controls_section(
            'section_plusminus_style', [
                'label' => __('Plus/Minus', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'quantity_enable!' => '',
                ]
            ]
        );
         $this->add_control(
            'plusminus_color', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .quantity .plus, {{WRAPPER}} .dce-add-to-cart .quantity .minus' => 'color: {{VALUE}};'
                ],
                
            ]
        );

        $this->add_control(
            'plusminus_bgcolor', [
                'label' => __('Background Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .quantity .plus, {{WRAPPER}} .dce-add-to-cart .quantity .minus' => 'background-color: {{VALUE}};',
                ],
                
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'plusminus_typography',
                'label' => __('Typography', 'dynamic-content-for-elementor'),
                'selector' => '{{WRAPPER}} .dce-add-to-cart .quantity .plus, {{WRAPPER}} .dce-add-to-cart .quantity .minus',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'plusminus_border',
                'label' => __('Border', 'dynamic-content-for-elementor'),            
                'selector' => '{{WRAPPER}} .dce-add-to-cart .quantity .plus, {{WRAPPER}} .dce-add-to-cart .quantity .minus',
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'plusminus_border_radius', [
                'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .quantity .plus, {{WRAPPER}} .dce-add-to-cart .quantity .minus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                
            ]
        );
         $this->add_responsive_control(
            'plusminus_padding', [
                'label' => __('Padding Plus/Minus', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],
                
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .quantity .plus, {{WRAPPER}} .dce-add-to-cart .quantity .minus' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                
            ]
        );
         $this->add_responsive_control(
            'plusminus_margin', [
                'label' => __('Margin Plus/Minus', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],
                
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .quantity .plus, {{WRAPPER}} .dce-add-to-cart .quantity .minus' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                
            ]
        );
        $this->end_controls_section();

        // ------------------------------------------- [SECTION STYLE - Quantity Stock]
        $this->start_controls_section(
            'section_stock_style', [
                'label' => __('Stock', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'hide_stock' => '',
                ]
            ]
        );
         $this->add_control(
            'stock_color', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .stock' => 'color: {{VALUE}};'
                ],
                
            ]
        );

        $this->add_control(
            'stock_bgcolor', [
                'label' => __('Background Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .stock' => 'background-color: {{VALUE}};',
                ],
                
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'stocktypography',
                'label' => __('Typography', 'dynamic-content-for-elementor'),
                'selector' => '{{WRAPPER}} .dce-add-to-cart .stock',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'stock_border',
                'label' => __('Border', 'dynamic-content-for-elementor'),            
                'selector' => '{{WRAPPER}} .dce-add-to-cart .stock',
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'stock_radius', [
                'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .stock' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                
            ]
        );
         $this->add_responsive_control(
            'stock_padding', [
                'label' => __('Padding Plus/Minus', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],
                
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .stock' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                
            ]
        );

        $this->end_controls_section();

        // ------------------------------------------- [SECTION STYLE - Quantity Symbol]
        $this->start_controls_section(
            'section_symbol_style', [
                'label' => __('Stock', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
         $this->add_control(
            'symbol_color', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .woocommerce-Price-currencySymbol' => 'color: {{VALUE}};'
                ],
                
            ]
        );

        $this->add_control(
            'symbol_bgcolor', [
                'label' => __('Background Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .woocommerce-Price-currencySymbol' => 'background-color: {{VALUE}};',
                ],
                
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'symboltypography',
                'label' => __('Typography', 'dynamic-content-for-elementor'),
                'selector' => '{{WRAPPER}} .dce-add-to-cart .woocommerce-Price-currencySymbol',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'symbol_border',
                'label' => __('Border', 'dynamic-content-for-elementor'),            
                'selector' => '{{WRAPPER}} .dce-add-to-cart .woocommerce-Price-currencySymbol',
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'symbol_radius', [
                'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .woocommerce-Price-currencySymbol' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                
            ]
        );
         $this->add_responsive_control(
            'symbol_padding', [
                'label' => __('Padding Plus/Minus', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],
                
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .woocommerce-Price-currencySymbol' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                
            ]
        );

        $this->end_controls_section();

        // ------------------------------------------- [SECTION STYLE - ROLL-HOVER]
        $this->start_controls_section(
            'section_rolhover_style', [
                'label' => __('Roll-Hover', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                ]
            ]
        );
        $this->add_control(
            'wooc_hover_heading',
            [
                'label' => __( 'Roll-Hover', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                
            ]
        );
        $this->add_control(
            'wooc_color_hover', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .button:hover > span, {{WRAPPER}} .dce-add-to-cart .button:hover:after,  {{WRAPPER}} .dce-add-to-cart .button:hover:before, {{WRAPPER}} .dce-add-to-cart .button .icon-rm' => 'color: {{VALUE}};',
                ],
                
            ]
        );

        $this->add_control(
            'wooc_bgcolor_hover', [
                'label' => __('Background Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .button:not(.button--pipaluk):not(.button--isi):not(.button--aylen):hover, {{WRAPPER}} .dce-add-to-cart .button:not(.button--pipaluk):hover:after, {{WRAPPER}} .dce-add-to-cart .button:not(.button--pipaluk):not(.button--wapasha):not(.button--nina):hover:before, {{WRAPPER}} .button--pipaluk:hover:after, {{WRAPPER}} .button--moema:before, {{WRAPPER}} .button--aylen:after, {{WRAPPER}} .button--aylen:before' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .button--pipaluk:before, {{WRAPPER}} .button--wapasha:before, {{WRAPPER}} .button--antiman:before, {{WRAPPER}} .button--itzel:before' => 'border-color: {{VALUE}};'
                ],
                
            ]
        );
        $this->add_control(
            'wooc_bordercolor_hover', [
                'label' => __('Border Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dce-add-to-cart .button:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [ 
                    'wooc_border!' => 'none',
                ]
            ]
        );
        $this->add_control(
            'style_effect', [
                'label' => __('Effect', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'options' => [
                    '' => __('None', 'dynamic-content-for-elementor'),
                    'button--asolo' => __('Asolo', 'dynamic-content-for-elementor'),
                    'button--winona' => __('Winona', 'dynamic-content-for-elementor'),
                    'button--ujarak' => __('Ujarak', 'dynamic-content-for-elementor'),
                    'button--wayra' => __('Wayra', 'dynamic-content-for-elementor'),
                    'button--tamaya' => __('Tamaya', 'dynamic-content-for-elementor'),
                    'button--rayen' => __('Rayen', 'dynamic-content-for-elementor'),
                    'button--pipaluk' => __('Pipaluk', 'dynamic-content-for-elementor'),
                    'button--nuka' => __('Nuka', 'dynamic-content-for-elementor'),
                    'button--moema' => __('Moema', 'dynamic-content-for-elementor'),
                    'button--isi' => __('Isi', 'dynamic-content-for-elementor'),
                    'button--aylen' => __('Aylen', 'dynamic-content-for-elementor'),
                    'button--saqui' => __('Saqui', 'dynamic-content-for-elementor'),
                    'button--wapasha' => __('Wapasha', 'dynamic-content-for-elementor'),
                    'button--nina' => __('Nina', 'dynamic-content-for-elementor'),
                    'button--nanuk' => __('Nanuk', 'dynamic-content-for-elementor'),
                    'button--antiman' => __('Antiman', 'dynamic-content-for-elementor'),
                    'button--itzel' => __('Itzel', 'dynamic-content-for-elementor'),
                    // 'button--naira' => __('Naira', 'dynamic-content-for-elementor'),
                    // 'button--quidel' => __('Quidel', 'dynamic-content-for-elementor'),
                    // 'button--sacnite' => __('Sacnite', 'dynamic-content-for-elementor'),
                    // 'button--shikoba' => __('Shikoba', 'dynamic-content-for-elementor'),
                ],
                'default' => '',
                'condition' => [
                    'hover_animation' => '',
                ]
            ]
        );
        $this->add_control(
            'hover_animation', [
                'label' => __('Hover Animation', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::HOVER_ANIMATION,
                'condition' => [
                    'style_effect' => ''
                ],
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_borders', [
                'label' => __('Form', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'form_border',
                'label' => __('Border', 'dynamic-content-for-elementor'),            
                'selector' => '{{WRAPPER}} form.cart',
                
            ]
        );
        $this->add_responsive_control(
            'form_inner_space', [
                'label' => __('Inner Space', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => '',
                ],
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} form.cart' => 'padding-bottom: {{SIZE}}{{UNIT}}; padding-top: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'form_outer_space', [
                'label' => __('Outer Space', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => '',
                ],
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} form.cart' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-top: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_active_settings();

        if ( empty( $settings ) )
           return;
        //
        // ------------------------------------------
        $dce_data = DCE_Helper::dce_dynamic_data();
        $id_page = $dce_data['id'];
        $global_is = $dce_data['is'];
        // ------------------------------------------ 
        global $product;
        if ( empty( $product ) ) {
            return;
        }  

        ?>
        <div class="dce-add-to-cart dce-product-<?php echo esc_attr( $product->get_type() ); ?>">
            <?php woocommerce_template_single_add_to_cart(); ?>
            <?php //wc_get_template( '/single-product/related.php' ); ?>
            <?php 
            $animation_class = !empty($settings['hover_animation']) ? ' elementor-animation-' . $settings['hover_animation'] : '';
            $effect_class = !empty($settings['style_effect']) ? ' eff_button '.$settings['style_effect'] : '';
            $txbtn = $product->single_add_to_cart_text();
                echo '<script>

                        jQuery(".dce-add-to-cart .button").wrapInner("<span></span>");
                        jQuery(".dce-add-to-cart .button").attr("data-text","'.$txbtn.'");
                        jQuery(".dce-add-to-cart .button").addClass("'.$animation_class.$effect_class.'");
                        jQuery(".button--nina > span, .button--nanuk > span").each(function(){
                        jQuery(this).html(jQuery(this).text().replace(/([^\x00-\x80]|\w)/g, "<span>$&</span>"));
                    });</script>'; ?>
        </div>
        <?php
    }

    protected function _content_template() {
        
    }
    protected function crea_wooc($product) {
        
        if ( ! $product->is_purchasable() ) {
            return;
        }

        echo wc_get_stock_html( $product ); // WPCS: XSS ok.

        if ( $product->is_in_stock() ) : ?>

            <?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

            <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

                <?php
                do_action( 'woocommerce_before_add_to_cart_quantity' );

                woocommerce_quantity_input( array(
                    'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
                    'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
                    'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                ) );

                do_action( 'woocommerce_after_add_to_cart_quantity' );
                ?>

                <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

                <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
            </form>

            <?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

        <?php endif;
    }
}
