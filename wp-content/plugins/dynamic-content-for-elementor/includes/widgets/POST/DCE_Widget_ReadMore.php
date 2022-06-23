<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Icons_Manager;
use DynamicContentForElementor\Controls\DCE_Group_Control_Ajax_Page;
use DynamicContentForElementor\DCE_Helper;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Elementor Button Readmore
 *
 * Elementor widget for Dynamic Content for Elementor
 *
 */
class DCE_Widget_ReadMore extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-readmore';
    }
    
    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('Read More', 'dynamic-content-for-elementor');
    }
    public function get_description() {
        return __('Add a “read more” button below your post or on the block in the archive, create a call-to-action. The button is pure code based, which will allow you to write formatted text and also an SVG directly inside the CLICK', 'dynamic-content-for-elementor');
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/read-more-button/';
    }
    public function get_icon() {
        return 'icon-dyn-readmore';
    }
    static public function get_position() {
        return 3;
    }
    protected function _register_controls() {

        $post_type_object = get_post_type_object(get_post_type());

        $this->start_controls_section(
            'section_readmore', [
                    'label' => __('Settings', 'dynamic-content-for-elementor')
            ]
        );

        $this->add_control(
            'html_tag', [
                'label' => __('HTML Tag', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'button' => __('Button', 'dynamic-content-for-elementor'),
                    'h1' => __('H1', 'dynamic-content-for-elementor'),
                    'h2' => __('H2', 'dynamic-content-for-elementor'),
                    'h3' => __('H3', 'dynamic-content-for-elementor'),
                    'h4' => __('H4', 'dynamic-content-for-elementor'),
                    'h5' => __('H5', 'dynamic-content-for-elementor'),
                    'h6' => __('H6', 'dynamic-content-for-elementor'),
                    'p' => __('p', 'dynamic-content-for-elementor'),
                    'div' => __('div', 'dynamic-content-for-elementor'),
                    'span' => __('span', 'dynamic-content-for-elementor'),
                ],
                'default' => 'div',
                'condition' => [
                    'link_to' => 'none',
                ]
            ]
        );
        $this->add_control(
            'type_of_button', [
                'label' => __('Button type', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'html',
                'options' => [
                    'text' => __('Text', 'dynamic-content-for-elementor'),
                    'html' => __('Html', 'dynamic-content-for-elementor'),
                    'image' => 'Image',
                ],
            ]
        );

        $this->add_control(
            'button_text', [
                'label' => __('Button Text', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Read More', 'dynamic-content-for-elementor'),
                'placeholder' => __('Read More', 'dynamic-content-for-elementor'),
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'type_of_button' => 'text',
                ]
            ]
        );
        $this->add_control(
            'button_html', [
                'label' => __('Button HTML', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CODE,
                'language' => 'html',
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Read <b>more</b>',
                'condition' => [
                    'type_of_button' => 'html',
                ],
            ]
        );
        $this->add_control(
          'button_image',
          [
             'label' => __( 'Button Image', 'dynamic-content-for-elementor' ),
             'type' => Controls_Manager::MEDIA,
             'default' => [
                'url' => '',
             ],
             'condition' => [
                    'type_of_button' => 'image',
                ]
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
                    ],
                    'justify' => [
                        'title' => __('Justified', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => '',
                'prefix_class' => 'rmbtn-align-',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'type_of_button' => 'html',
                ],
            ]
        );
        $this->add_responsive_control(
            'align_html', [
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
                'default' => '',
                'prefix_class' => 'rmbtn-align-',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'type_of_button' => 'text',
                ],
            ]
        );
        $this->add_responsive_control(
            'align_justify', [
                'label' => __('Justify Alignment', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Left', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => '',
                
                'selectors' => [
                    '{{WRAPPER}} .dce-btn-readmore, {{WRAPPER}} .dce-btn-readmore:after, {{WRAPPER}} .dce-btn-readmore:before' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'align' => 'justify',
                    'type_of_button' => 'html'

                ],
            ]
        );
        $this->add_responsive_control(
          'rm_width', [
            'label' => __('Width', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HIDDEN,
            'default' => [
                'size' => '',
                'unit' => '%'
            ],
            'size_units' => [ '%', 'px' ],
            'range' => [
                '%' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
                'px' => [
                    'max' => 300,
                    'min' => 0,
                    'step' => 1,
                ],
                
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-btn-readmore' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                    'align!' => 'justify',
                    'type_of_button' => 'html'
                ],
          ]
        );
        if (version_compare(ELEMENTOR_VERSION, '2.6.0', '<')) {
        $this->add_control(
            'icon_rm',
            [
                'label' => __( 'Icons', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::ICON,
            ]
        );
        }
        $this->add_control(
            'selected_icon_rm',
            [
                'label' => __( 'Icons', 'dynamic-content-for-elementor' ),
                'type' => 'icons', //Controls_Manager::ICONS,
                'default' => [
                        'value' => '',
                        'library' => 'solid',
                    ],
                'fa4compatibility' => 'icon_rm',
            ]
        );
        $this->add_control(
            'icon_rm_position',
            [
                'label' => __( 'Icon Position', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Before', 'dynamic-content-for-elementor' ),
                    'right' => __( 'After', 'dynamic-content-for-elementor' ),
                ],
                'render_type' => 'template',
                'prefix_class' => 'icon-',
                'condition' => [
                    'selected_icon_rm[value]!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'link_to', [
                'label' => __('Link to', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'post',
                'options' => [
                    'none' => __('None', 'dynamic-content-for-elementor'),
                    'home' => __('Home URL', 'dynamic-content-for-elementor'),
                    'post' => 'Post URL',
                    'custom_field' => 'Custom Field',
                    'custom' => __('Custom URL', 'dynamic-content-for-elementor'),
                ],
            ]
        );

        $this->add_control(
            'link', [
                'label' => __('Link', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('http://your-link.com', 'dynamic-content-for-elementor'),
                'condition' => [
                    'link_to' => 'custom',
                ],
                'default' => [
                    'url' => '',
                ],
                'show_label' => false,
                'dynamic' => [
                                'active' => true,
                            ],
            ]
        );
        $this->add_control(
                'custom_field_id', [
                    'label' => __('META Field', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Meta key or Field Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'metas',
                    'object_type' => 'post',
                    'condition' => [
                        'link_to' => 'custom_field',
                    ],
                ]
        );
        $this->add_control(
            'custom_field_target',
            [
              'label' => __( 'Target', 'dynamic-content-for-elementor' ),
              'type' => Controls_Manager::SWITCHER,
              'default' => 'yes',
              'label_on' => __( 'Blank', 'dynamic-content-for-elementor' ),
              'label_off' => __( 'Same', 'dynamic-content-for-elementor' ),
              'return_value' => 'yes',
             
              'condition' => [
                    'link_to' => 'custom_field',
                ],
            ]
        );
        

         $this->add_control(
            'data_source',
            [
              'label' => __( 'Source', 'dynamic-content-for-elementor' ),
              'description' => __( 'Select the data source', 'dynamic-content-for-elementor' ),
              'type' => Controls_Manager::SWITCHER,
              'default' => 'yes',
              'label_on' => __( 'Same', 'dynamic-content-for-elementor' ),
              'label_off' => __( 'other', 'dynamic-content-for-elementor' ),
              'return_value' => 'yes',
              'separator' => 'before',
              'condition' => [
                    'link_to' => 'post',
                ],
            ]
        );
        /*$this->add_control(
            'other_post_source', [
              'label' => __('Select from other source post', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SELECT,
              
              'groups' => DCE_Helper::get_all_posts(get_the_ID(), true),
              'default' => '',
              'condition' => [
                    'link_to' => 'post',
                    'data_source' => '',
                ], 
            ]
        );*/
        $this->add_control(
                'other_post_source',
                [
                    'label' => __('Select from other source post', 'dynamic-content-for-elementor'),
                    'type'      => 'ooo_query',
                    'placeholder'   => __( 'Post Title', 'dynamic-content-for-elementor' ),
                    'label_block'   => true,
                    'query_type'    => 'posts',
                    'condition' => [
                        'link_to' => 'post',
                        'data_source' => '',
                    ],
                ]
        );
        $this->end_controls_section();


         // ------------------------------------------- [SECTION Ajax]

        $this->start_controls_section(
            'section_ajax', [
                'label' => __('Ajax', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'link_to' => 'post',
                ],
            ]
        );
        $this->add_group_control(
            DCE_Group_Control_Ajax_Page::get_type(),
            [
                'name' => 'ajax_page',
                'label' => 'Ajax PAGE',
                'selector' => $this->get_id(),
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
            'readmore_color', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dce-btn-readmore > span, {{WRAPPER}} .dce-btn-readmore .icon-rm,  {{WRAPPER}} .dce-btn-readmore:before,  {{WRAPPER}} .dce-btn-readmore:after' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .button--asolo:before, {{WRAPPER}} .button--asolo:after' => 'border-color: {{VALUE}};'
                ],
                
            ]
        );

        $this->add_control(
            'readmore_bgcolor', [
                'label' => __('Background Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dce-btn-readmore:not(.button--pipaluk), {{WRAPPER}} .button--pipaluk:after, {{WRAPPER}} .button--tamaya:before, {{WRAPPER}} .button--tamaya:after' => 'background-color: {{VALUE}};',
                ],
                
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .dce-btn-readmore',
            ]
        );
        
        $this->add_control(
            'readmore_space_heading',
            [
                'label' => __( 'Space', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'readmore_padding', [
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
                    '{{WRAPPER}} .dce-btn-readmore > span, {{WRAPPER}} .dce-btn-readmore:after, {{WRAPPER}} .dce-btn-readmore:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .dce-btn-readmore.icon_button .icon-rm' => 'padding-left: {{LEFT}}{{UNIT}}; padding-right: {{LEFT}}{{UNIT}};'
                ],
                
            ]
        );
        $this->add_responsive_control(
            'readmore_margin',
                [
                'label'         => __( 'Margin', 'dynamic-content-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', '%' ],
                'selectors'     => [
                        '{{WRAPPER}} .dce-btn-readmore' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_control(
            'readmore_style_heading',
            [
                'label' => __( 'Style', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'readmore_border',
                'label' => __('Border', 'dynamic-content-for-elementor'),            
                'selector' => '{{WRAPPER}} .dce-btn-readmore, {{WRAPPER}} .button--asolo:after, {{WRAPPER}} .button--asolo:before',
                
            ]
        );
        
        $this->add_control(
            'readmore_border_radius', [
                'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dce-btn-readmore, {{WRAPPER}} .dce-btn-readmore:before, {{WRAPPER}} .dce-btn-readmore:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                
            ]
        );
        $this->add_group_control(
             Group_Control_Text_Shadow::get_type(),
                [
                    'name'      => 'text_shadow',
                    'selector'  => '{{WRAPPER}} .dce-btn-readmore',
                ]
            );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(), [
                'name' => 'box_shadow_readmore',
                'selector' => '{{WRAPPER}} .dce-btn-readmore',
                
            ]
        );
        
        $this->end_controls_section();
 
        // ------------------------------------------- [SECTION STYLE - ICON]
        $this->start_controls_section(
            'section_icon_style', [
                'label' => __('Icon', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'selected_icon_rm[value]!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'readmore_icon_color', [
                'label' => __('Icon Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dce-btn-readmore .icon-rm' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'size_icon_rm',
            [
                'label' => __( 'Size', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                        'px' => [
                                'min' => 0,
                                'max' => 100,
                        ],
                ],
                'selectors' => [
                        '{{WRAPPER}} .dce-btn-readmore .icon-rm' => 'font-size: {{SIZE}}{{UNIT}};',
                        //'{{WRAPPER}}.icon-left .dce-btn-readmore.icon_button:before' => 'padding-left: {{SIZE}}{{UNIT}} !important;'
                ],
            ]
        );
        $this->add_control(
            'space_icon_rm',
            [
                'label' => __( 'Space', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                        'size' => 7,
                ],
                'range' => [
                        'px' => [
                                'min' => 0,
                                'max' => 100,
                        ],
                ],
                'selectors' => [
                        '{{WRAPPER}}.icon-left .icon-rm' => 'padding-right: {{SIZE}}{{UNIT}} !important;',
                        '{{WRAPPER}}.icon-right .icon-rm' => 'padding-left: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'selected_icon_rm[value]!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'ypos_icon_rm',
            [
                'label' => __( 'Position Y', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                        'px' => [
                                'min' => -100,
                                'max' => 100,
                        ],
                ],
                'default' => [
                    'size' => '',
                ],
                'selectors' => [
                        '{{WRAPPER}} .dce-btn-readmore .icon-rm' => 'top: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'align!' => 'justify',
                ],
            ]
        );
        $this->add_responsive_control(
            'xpos_icon_rm',
            [
                'label' => __( 'Position X', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                        'px' => [
                                'min' => -100,
                                'max' => 100,
                        ],
                ],
                'default' => [
                    'size' => '',
                ],
                'selectors' => [
                        '{{WRAPPER}} .dce-btn-readmore .icon-rm' => 'left: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'align!' => 'justify',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_rolhover_style', [
                'label' => __('Roll-Hover', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'link_to!' => 'none',
                ]
            ]
        );
        $this->add_control(
            'readmore_hover_heading',
            [
                'label' => __( 'Roll-Hover', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                
            ]
        );
        $this->add_control(
            'readmore_color_hover', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} a.dce-btn-readmore:hover > span, {{WRAPPER}} a.dce-btn-readmore:hover:after,  {{WRAPPER}} a.dce-btn-readmore:hover:before, {{WRAPPER}} a.dce-btn-readmore:hover .icon-rm' => 'color: {{VALUE}};',
                ],
                
            ]
        );

        $this->add_control(
            'readmore_bgcolor_hover', [
                'label' => __('Background Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} a.dce-btn-readmore:not(.button--pipaluk):not(.button--isi):not(.button--aylen):hover, {{WRAPPER}} a.dce-btn-readmore:not(.button--pipaluk):hover:after, {{WRAPPER}} a.dce-btn-readmore:not(.button--pipaluk):not(.button--wapasha):not(.button--nina):hover:before, {{WRAPPER}} .button--pipaluk:hover:after, {{WRAPPER}} .button--moema:before, {{WRAPPER}} .button--aylen:after, {{WRAPPER}} .button--aylen:before, {{WRAPPER}} .dce-type-html:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .button--pipaluk:before, {{WRAPPER}} .button--wapasha:before, {{WRAPPER}} .button--antiman:before, {{WRAPPER}} .button--itzel:before' => 'border-color: {{VALUE}};'
                ],
                
            ]
        );
        $this->add_control(
            'readmore_icon_color_hover', [
                'label' => __('Icon Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} a.dce-btn-readmore:hover .icon-rm' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'selected_icon_rm[value]!' => '',
                ],
            ]
        );
        $this->add_control(
            'readmore_bordercolor_hover', [
                'label' => __('Border Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} a.dce-btn-readmore:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'readmore_border_border!' => '',
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
                    'link_to!' => 'none',
                    'hover_animation' => '',
                    'type_of_button' => 'text'
                ]
            ]
        );
        $this->add_control(
            'hover_animation', [
                'label' => __('Hover Animation', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::HOVER_ANIMATION,
                'condition' => [
                    'link_to!' => 'none',
                    'style_effect' => ''
                ],
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings ) )
            return;
        //
        // ------------------------------------------
        $dce_data = DCE_Helper::dce_dynamic_data($settings['other_post_source']);
        $id_page = $dce_data['id'];
        $global_is = $dce_data['is'];
        $type_page = $dce_data['type'];
        // ------------------------------------------
        
        if ($settings['ajax_page_enabled'] == 'open'){
            ?>
            <script type='text/javascript'>
            /* <![CDATA[ */
            var dceAjaxPath = {"ajaxurl":"<?php echo admin_url( 'admin-ajax.php' ); ?>"};
            /* ]]> */
            </script>
            
            <?php
        }

        // -----------------------------------------
        $content_button_raw = '';
        if( $settings['type_of_button'] == 'text' ){
            $content_button_raw = __($settings['button_text'], 'dynamic-content-for-elementor'.'_texts');
        }else if( $settings['type_of_button'] == 'html' ){
            $content_button_raw = __($settings['button_html'], 'dynamic-content-for-elementor'.'_texts');
        }else if( $settings['type_of_button'] == 'image' ){
            $content_button_raw = $settings['button_image']['url'];
        }
        
        $title = '<span>'.$content_button_raw.'</span>';
        //var_dump(get_post_meta($id_page,$settings['custom_field_id'],true));

        switch ($settings['link_to']) {
            case 'custom' :
                if ( $settings['link']['url'] ) {
                    $link = esc_url($settings['link']['url']);
                } else {
                    $link = '#';
                }
                break;

            case 'post' :
                $link = esc_url(get_the_permalink($id_page));
                break;

            case 'custom_field' :
                $link = get_post_meta($id_page,$settings['custom_field_id'],true);

                if(is_numeric($link)) $link = wp_get_attachment_url($link);
                
                break;

            case 'home' :
                $link = esc_url(get_home_url());
                break;

            case 'none' :
            default:
                $link = false;
                break;
        }
        $target = $settings['link']['is_external'] ? 'target="_blank"' : '';
        $nofollow = $settings['link']['nofollow'] ? 'rel="nofollow"' : '';
        if( $settings['custom_field_target'] ) $target = 'target="_blank"';

        $animation_class = !empty($settings['hover_animation']) ? ' elementor-animation-' . $settings['hover_animation'] : '';
        $effect_class = !empty($settings['style_effect']) && $link ? ' eff_button '.$settings['style_effect'] : '';
        
        // se l'icona non esiste
        

        // Nuovo sistema di icone --------
        if ( empty( $settings['icon_rm'] ) && ! Icons_Manager::is_migration_allowed() ) {
            // add old default
            $settings['icon_rm'] = ''; //questo è il valore di default in caso di vecchio metodo
        }

        $migrated = isset( $settings['__fa4_migrated']['selected_icon_rm'] );
        $is_new = empty( $settings['icon_rm'] ) && Icons_Manager::is_migration_allowed();
        
        if ( $is_new || $migrated ){
            $icon_class = !empty($settings['selected_icon_rm']['value']) ? ' icon_button' : '';
            //$title = '<span class="icon-rm">'.Icons_Manager::render_icon( $settings['selected_icon_rm'], [ 'aria-hidden' => 'true' ] ).'</span>'.$title;
            
            if($settings['icon_rm_position'] == 'left'){
                $title = '<i class="icon-rm '.$settings['selected_icon_rm']['value'].'" aria-hidden="true"></i>'.$title;
            }else if($settings['icon_rm_position'] == 'right'){
                $title = $title.'<i class="icon-rm '.$settings['selected_icon_rm']['value'].'" aria-hidden="true"></i>';
            }
        }else{
            $icon_class = !empty($settings['icon_rm']) ? ' icon_button' : '';
            if($settings['icon_rm_position'] == 'left'){
                $title = '<i class="icon-rm '.$settings['icon_rm'].'" aria-hidden="true"></i>'.$title;
            }else if($settings['icon_rm_position'] == 'right'){
                $title = $title.'<i class="icon-rm '.$settings['icon_rm'].'" aria-hidden="true"></i>';
            }
            
        }
        // --------------------------------
        /*if($settings['icon_rm']){
            $title = '<i class="icon-rm '.$settings['icon_rm'].'" aria-hidden="true"></i>'.$title;
            //$title = '<span><i class="icon-rm '.$settings['icon_rm'].'"></i>'.$content_button_raw.'</span>';
        }*/
        // -------------------------------
        if (empty($title))
            return;

        $html = '';

        $data_text_effect = '';
        $class_typebutton = ' dce-type-html';
        if( $settings['style_effect'] != '' && $settings['type_of_button'] == 'text' && $link){
            $data_text_effect = ' data-text="'.$content_button_raw.'"';
            $class_typebutton = ' dce-type-text';
        }
        if ($link) {
            $html .= sprintf('<a id="dce-readmore-'.$this->get_id().'" class="dce-btn-readmore%4$s%5$s%6$s" href="%1$s" %2$s%8$s%7$s>%3$s</a>', $link, $target, $title, $animation_class, $effect_class, $icon_class.$class_typebutton, $data_text_effect, $nofollow);
        } else {
            $html_tag = $settings['html_tag'] ? $settings['html_tag'] : 'span';
            $html .= sprintf('<%1$s id="dce-readmore-'.$this->get_id().'" class="dce-btn-readmore%2$s%3$s%4$s">%5$s</%s>', $html_tag, $animation_class, $effect_class, $icon_class.$class_typebutton, $title);
        }
        $scriptLetters = '';
        if( $settings['style_effect'] == 'button--nina' || $settings['style_effect'] == 'button--nanuk'){
            $scriptLetters = '<script>jQuery(".button--nina > span, .button--nanuk > span").each(function(){
                        jQuery(this).html(jQuery(this).text().replace(/([^\x00-\x80]|\w)/g, "<span>$&</span>"));
                    });</script>';
        }
        $effScripts = '';
        
        $spaceI = (intval($settings['space_icon_rm']['size'])+intval($settings['size_icon_rm']['size'])+intval($settings['readmore_padding']['left']));
        $effStyle = '<style>.rmbtn-align-justify.icon-left #dce-readmore-'.$this->get_id().'.icon_button:after, .rmbtn-align-justify.icon-left #dce-readmore-'.$this->get_id().'.icon_button:before{padding-left: '.$spaceI.'px !important; }.rmbtn-align-justify.icon-right #dce-readmore-'.$this->get_id().'.icon_button:after, .rmbtn-align-justify.icon-left #dce-readmore-'.$this->get_id().'.icon_button:before{padding-right: '.$spaceI.'px !important; }</style>';

        echo '<div class="dce-wrapper">';
        echo $html.$scriptLetters.$effStyle;
        echo '</div>';
    }

    protected function _content_template() {
        
    }
    public function on_import( $element ) {
        return Icons_Manager::on_import_migration( $element, 'icon_rm', 'selected_icon_rm', true );
    }
}
