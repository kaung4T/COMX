<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Taxonomy & Terms Menu
 *
 * Elementor widget for Dynamic Content for Elementor
 *
 */
class DCE_Widget_TaxonomyTermsMenu extends DCE_Widget_Prototype {

    public function get_name() {
        return 'taxonomy-terms-menu';
    }

    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('Taxonomy Terms List', 'dynamic-content-for-elementor');
    }

    public function get_description() {
        return __('Write a taxonomy for your article', 'dynamic-content-for-elementor');
    }

    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/taxonomy-terms-list/';
    }

    public function get_icon() {
        return 'icon-dyn-parenttax';
    }

    /* public function get_style_depends() {
      return [ 'dce-list' ];
      } */

    protected function _register_controls() {
        $taxonomies = \DynamicContentForElementor\DCE_Helper::get_taxonomies();

        $this->start_controls_section(
                'section_content',
                [
                    'label' => __('Menu of terms from Taxonomy', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'taxonomy_select',
                [
                    'label' => __('Select Taxonomy', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => DCE_Helper::get_taxonomies(),
                    'default' => '',
                ]
        );
        foreach ($taxonomies as $tkey => $atax) {
            if ($tkey) {
                $this->add_control(
                        'prent_term_' . $tkey, [
                        'label' => __('From parent term', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SELECT,
                        
                        'options' => ['my_parent' => __('My parent', 'dynamic-content-for-elementor')] + DCE_Helper::get_parentterms($tkey),
                        'default' => '0',
                        'condition' => [
                            'taxonomy_select' => $tkey,
                        ],
                        'render_type' => 'template',
                        'dynamic' => [
                                'active' => true,
                            ],
                        ]
                );
            }
        }
        /*$this->add_control(
                'prent_term',
                [
                    'label' => __('From parent term', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => DCE_Helper::get_parentterms('servizi'),
                    'default' => '',
                ]
        );*/
        $this->add_control(
                'menu_style',
                [
                    'label' => __('Style', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'horizontal' => __('Horizontal', 'dynamic-content-for-elementor'),
                        'vertical' => __('Vertical', 'dynamic-content-for-elementor')
                    ],
                    'default' => 'vertical',
                ]
        );

        $this->add_control(
                'heading_settings_menu',
                [
                    'label' => __('Settings', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'taxonomy_dynamic',
                [
                    'label' => __('Enable Dynamic', 'dynamic-content-for-elementor'),
                    'description' => __('Change to depending on the page that displays it.', 'dynamic-content-for-elementor')
                        .'<br>'.__('In POST page will show all Terms associated to current post.', 'dynamic-content-for-elementor')
                        .'<br>'.__('In TERM page will show all his Terms children.', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'sparator' => 'before'
                ]
        );
        $this->add_control(
                'hide_empty',
                [
                    'label' => __('Hide Empty', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'sparator' => 'before'
                ]
        );
        $this->add_control(
                'link_term',
                [
                    'label' => __('Use Link', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'sparator' => 'before'
                ]
        );
        $this->add_control(
                    'dce_tax_orderby', [
                'label' => __('Order by', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'parent' => 'Parent', 
                    'count' => 'Count (number of associated posts)', 
                    'term_order' => 'Order', 
                    'name' => 'Name', 
                    'slug' => 'Slug', 
                    'term_group' => 'Group', 
                    'term_id' => 'ID'
                ),
                'default' => 'parent',
                    ]
            );
        $this->add_control(
                'dce_tax_order', [
            'label' => __('Sorting', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'ASC' => [
                    'title' => __('ASC', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-sort-up',
                ],
                'DESC' => [
                    'title' => __('DESC', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-sort-down',
                ]
            ],
            'toggle' => false,
            'default' => 'ASC',
                ]
        );

        $this->add_control(
                'heading_options_menu',
                [
                    'label' => __('Options', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'show_taxonomy',
                [
                    'label' => __('Show Taxonomy Name', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'toggle' => false,
                    'label_block' => false,
                    'options' => [
                        '1' => [
                            'title' => __('Yes', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-check',
                        ],
                        '0' => [
                            'title' => __('No', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-ban',
                        ]
                    ],
                    'default' => '1'
                ]
        );


        //
        $this->add_control(
                'tax_text',
                [
                    'label' => __('Custom Taxonomy Name', 'dynamic-content-for-elementor'),
                    'description' => __('If you do not want to use your native label', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                    'condition' => [
                        'show_taxonomy' => '1',
                    ],
                ]
        );
        $this->add_control(
            'tax_link', [
                'label' => __('Custom Link', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('http://your-link.com', 'dynamic-content-for-elementor'),
                'condition' => [
                    'show_taxonomy' => '1',
                    'tax_text!' => '',
                ],
                'default' => [
                    'url' => '',
                ],
                'show_label' => false,
            ]
        );
        $this->add_control(
                'show_childlist',
                [
                    'label' => __('Show Child List', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'toggle' => false,
                    'options' => [
                        '1' => [
                            'title' => __('Yes', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-check',
                        ],
                        '0' => [
                            'title' => __('No', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-ban',
                        ]
                    ],
                    'default' => '1'
                ]
        );
        $this->add_responsive_control(
                'show_border',
                [
                    'label' => __('Show Border', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'toggle' => false,
                    'options' => [
                        '1' => [
                            'title' => __('Yes', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-check',
                        ],
                        '0' => [
                            'title' => __('No', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-ban',
                        ],
                        '2' => [
                            'title' => __('Any', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-square-o',
                        ]
                    ],
                    'default' => '1',
                    'render_type' => 'template',
                    'prefix_class' => 'border-',
                    'condition' => [
                        'show_taxonomy' => '1'
                    ]
                ]
        );
        $this->add_responsive_control(
                'show_separators',
                [
                    'label' => __('Show Separator', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'toggle' => false,
                    'options' => [
                        'solid' => [
                            'title' => __('Yes', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-check',
                        ],
                        'none' => [
                            'title' => __('No', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-ban',
                        ],
                    ],
                    'condition' => [
                        'menu_style' => 'horizontal',
                    ],
                    'toggle' => true,
                    'default' => 'solid',
                    'selectors' => [
                        '{{WRAPPER}} .dce-menu.horizontal li' => 'border-left-style: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'heading_spaces_menu',
                [
                    'label' => __('Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    /*'condition' => [
                        'show_childlist!' => '0',
                        'show_taxonomy!' => '0'
                    ],*/
                ]
        );
        $this->add_responsive_control(
                'menu_space',
                [
                    'label' => __('Header Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-menu .dce-parent-title' => 'margin-bottom: calc( {{SIZE}}{{UNIT}} / 2);',
                        '{{WRAPPER}} .dce-menu hr' => 'margin-bottom: calc( {{SIZE}}{{UNIT}} / 2);',
                        '{{WRAPPER}} .dce-menu div.box' => 'padding: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'show_taxonomy' => '1'
                    ]
                ]
        );
        $this->add_responsive_control(
                'item_width',
                [
                    'label' => __('Items width', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => '',
                    ],
                    'size_units' => ['%','px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 300,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-menu.horizontal li' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'menu_style' => 'horizontal',
                    ],
                ]
        );
        $this->add_responsive_control(
                'menu_list_space',
                [
                    'label' => __('List Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-menu ul.first-level li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'show_childlist' => '1',
                    ],
                ]
        );
        $this->add_responsive_control(
                'menu_indent',
                [
                    'label' => __('Indent', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-menu li' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'show_childlist' => '1',
                    ],
                ]
        );
        if (DCE_Helper::is_plugin_active('acf')) {
            $this->add_control(
                    'heading_image_acf',
                    [
                        'label' => __('ACF Term image', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
            );
            $this->add_control(
                    'image_acf_enable',
                    [
                        'label' => __('Enable', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SWITCHER,
                    ]
            );
            $this->add_control(
                    'acf_field_image', [
                'label' => __('ACF Field', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                //'options' => $this->get_acf_field(),
                'groups' => DCE_Helper::get_acf_fields('image', true),
                'default' => 'Select the Field',
                'condition' => [
                    'image_acf_enable!' => '',
                ]
                    ]
            );
            $this->add_group_control(
                    Group_Control_Image_Size::get_type(), [
                'name' => 'size',
                'label' => __('Image Size', 'dynamic-content-for-elementor'),
                'default' => 'large',
                'render_type' => 'template',
                'condition' => [
                    'image_acf_enable!' => '',
                ]
                    ]
            );
            $this->add_control(
                    'block_enable', [
                'label' => __('Block', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'block',
                'selectors' => [
                    '{{WRAPPER}} .dce-menu li img' => 'display: {{VALUE}};',
                ],
                'condition' => [
                    'image_acf_enable!' => '',
                ],
                    ]
            );
            $this->add_control(
                    'image_acf_space',
                    [
                        'label' => __('Space', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 0,
                        ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .dce-menu li img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        ],
                        'condition' => [
                            'image_acf_enable!' => '',
                            'block_enable' => 'block'
                        ],
                    ]
            );
            $this->add_control(
                    'image_acf_space_right',
                    [
                        'label' => __('Space', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 5,
                        ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .dce-menu li img' => 'margin-right: {{SIZE}}{{UNIT}};',
                        ],
                        'condition' => [
                            'image_acf_enable!' => '',
                            'block_enable' => ''
                        ],
                    ]
            );
            $this->add_responsive_control(
                    'space', [
                'label' => __('Size', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 800,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-menu li img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'image_acf_enable!' => '',
                ],
                    ]
            );
        }// end is_plugin_active
        $this->end_controls_section();
        // ------------------------------------------ STYLE
        $this->start_controls_section(
                'section_style',
                [
                    'label' => __('Style', 'dynamic-content-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        $this->add_responsive_control(
                'menu_align',
                [
                    'label' => __('Text Alignment', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'toggle' => false,
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
                        ]
                    ],
                    'default' => 'flex-start',
                    'prefix_class' => 'menu-align-',
                    'selectors' => [
                        '{{WRAPPER}} .dce-menu ul, {{WRAPPER}} .dce-parent-title' => 'align-items: {{VALUE}}; justify-content: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'heading_colors',
                [
                    'label' => __('List items', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'menu_color',
                [
                    'label' => __('Text Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => [
                        'show_childlist' => '1',
                    ],
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .dce-menu a, {{WRAPPER}} .dce-menu li' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'menu_color_hover',
                [
                    'label' => __('Text Hover Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => [
                        'show_childlist' => '1',
                    ],
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .dce-menu a:hover' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'link_term!' => ''
                    ]
                ]
        );
        $this->add_control(
                'menu_color_active',
                [
                    'label' => __('Text Active Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => [
                        'show_childlist' => '1',
                    ],
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .dce-menu a.active' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'typography_list',
                    'selector' => '{{WRAPPER}} .dce-menu li',
                ]
        );


        $this->add_control(
                'heading_title',
                [
                    'label' => __('Title', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'show_taxonomy' => '1',
                    ],
                ]
        );
        $this->add_control(
                'menu_title_color',
                [
                    'label' => __('Title Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => [
                        'show_taxonomy' => '1',
                    ],
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .dce-menu .dce-parent-title a' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'menu_title_color_hover',
                [
                    'label' => __('Title Hover Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .dce-menu .dce-parent-title a:hover' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'show_taxonomy' => '1',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'typography_tit',
                    'selector' => '{{WRAPPER}} .dce-menu .dce-parent-title',
                    'condition' => [
                        'show_taxonomy' => '1',
                    ],
                ]
        );

        $this->add_control(
                'heading_border',
                [
                    'label' => __('Border', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'show_border' => ['1', '2'],
                    ],
                ]
        );

        $this->add_control(
                'menu_border_color',
                [
                    'label' => __('Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'toggle' => false,
                    'label_block' => false,
                    'default' => '',
                    'condition' => [
                        'show_border' => ['1', '2'],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-menu hr' => 'border-color: {{VALUE}};',
                        //'{{WRAPPER}} .dce-menu.horizontal li' => 'border-left-color: {{VALUE}};',
                        '{{WRAPPER}} .dce-menu .box' => 'border-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'menu_border_size',
                [
                    'label' => __('Weight', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'toggle' => false,
                    'label_block' => false,
                    'default' => [
                        'size' => 1,
                        'unit' => 'px',
                    ],
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 1,
                            'max' => 20,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-menu hr' => 'border-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'show_border' => ['1', '2']
                    ],
                ]
        );
        $this->add_control(
                'menu_border_width',
                [
                    'label' => __('Width', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'toggle' => false,
                    'label_block' => false,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 1,
                            'max' => 1000,
                        ],
                        '%' => [
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-menu hr' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'show_border' => ['1', '2']
                    ],
                ]
        );
        $this->add_control(
                'heading_separator',
                [
                    'label' => __('Separator', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'show_separators' => 'solid',
                        'menu_style' => 'horizontal',
                    ],
                ]
        );
        $this->add_control(
                'menu_color_separator',
                [
                    'label' => __('Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => [
                        'show_separators' => 'solid',
                        'menu_style' => 'horizontal',
                    ],
                    'default' => '#999999',
                    'selectors' => [
                        '{{WRAPPER}} .dce-menu.horizontal li' => 'border-left-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_responsive_control(
                'menu_size_separator',
                [
                    'label' => __('Weight', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 1,
                        'unit' => 'px',
                    ],
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-menu.horizontal li' => 'border-left-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'show_separators' => 'solid',
                        'menu_style' => 'horizontal',
                    ],
                ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;
        //
        // ------------------------------------------
        $dce_data = DCE_Helper::dce_dynamic_data();
        // ------------------------------------------
        $id_page = $dce_data['id'];
        $global_is = $dce_data['is'];
        $type_page = $dce_data['type'];

        //echo $type_page;
        //var_dump($settings['dce_tax_orderby']);
        /* $args = array(
          'post_type'         => 'page',
          'posts_per_page'    => -1,
          'order'             => 'ASC',
          'orderby'           => 'menu_order',
          'page_id'           =>  $settings['taxonomy_select']
          );
          $p_query = new \WP_Query( $args );

          $counter = 0;
          echo '<ul class="title">';
          if ( $p_query->have_posts() ) :
          echo '<li>';
          echo $settings['taxonomy_select'];
          echo '</li>';

          // End post check
          endif; */
        /* $args = array(
          'sort_order' => 'desc',
          'sort_column' => 'menu_order',
          'hierarchical' => 1,
          'exclude' => '',
          'include' => '',
          'meta_key' => '',
          'meta_value' => '',
          'authors' => '',
          'child_of' => 0,
          'parent' => -1,
          'exclude_tree' => '',
          'number' => '',
          'offset' => 0,
          'post_type' => 'page',
          'post_status' => 'publish'
          );
          $pages = get_pages($args);
          $listPage = [];


          foreach ( $pages as $page ) {

          $terms = get_children( 'post_parent='.$page->ID );
          $parents = get_post_ancestors( $page->ID );
          //
          if( !$parents && count($terms) > 0 ) $listPage[$page->ID] = $page->post_title ;
          } */
    
        if ($settings['taxonomy_dynamic']) {
            
            $queried_object = get_queried_object();
            //echo '<pre>';var_dump( $queried_object );echo '</pre>';
 
            if ($queried_object) {
                if (get_class($queried_object) == 'WP_Term') {
                    
                    //var_dump($queried_object);
                    $term_ID = $queried_object->term_id;
                    $term_ID_parent = $queried_object->parent;
                    
                    $terms_args = array(
                        'taxonomy' => $queried_object->taxonomy,
                        'hide_empty' => $settings['hide_empty'] ? true : false,
                        'orderby' => $settings['dce_tax_orderby'],
                        'order' => $settings['dce_tax_order'],
                    );

                    // PARENT ----------------
                    $parentTerm = $settings['prent_term_' . $settings['taxonomy_select']];
                    //$parentTermId = 
                    
                    if( $parentTerm == 'my_parent' ){
                        $terms_args['parent'] = $term_ID_parent;
                    }else{
                        $terms_args['parent'] = $term_ID;
                    }

                    //
                    $terms = get_terms($terms_args);

                    
                }
            }
            if (($queried_object && get_class($queried_object) == 'WP_Post') || ($id_page && DCE_Helper::in_the_loop())) {
                $terms = wp_get_post_terms($id_page, $settings['taxonomy_select'], array(
                    'hide_empty' => $settings['hide_empty'] ? true : false,
                    'orderby' => $settings['dce_tax_orderby'],
                    'order' => $settings['dce_tax_order'],
                ));
            }
            //var_dump($terms);
        } else {
            //$taxonomy_list = get_post_taxonomies($id_page);
            $parentTerm = $settings['prent_term_' . $settings['taxonomy_select']];
            $terms_args = array(
                'taxonomy' => $settings['taxonomy_select'],
                'hide_empty' => $settings['hide_empty'] ? true : false,
                'orderby' => $settings['dce_tax_orderby'],
                'order' => $settings['dce_tax_order'],
                
            );
            if($parentTerm) $terms_args['parent'] = $parentTerm;
            $terms = get_terms($terms_args);

            
            
        }

        //var_dump($settings['prent_term_' . $settings['taxonomy_select']]);

        /**/
        $styleMenu = $settings['menu_style'];
        $clssStyleMenu = $styleMenu;

        if (!empty($terms) && !is_wp_error($terms)) {
            echo '<nav class="dce-menu dce-flex-menu ' . $clssStyleMenu . '" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">';
            //echo $settings['taxonomy_select'];

            if ($settings['show_border'] == 2)
                echo '<div class="box">';

            if ($settings['show_taxonomy'] != 0) {

                // fromPartner
                $parentTaxonomy = $settings['prent_term_' . $settings['taxonomy_select']];
               
                if ($settings['tax_text'] != "") {
                    $taxtext = $settings['tax_text'];
                } else if($parentTaxonomy){
                    $taxtext = get_term($parentTaxonomy)->name;
                } else {
                    $taxtext = $settings['taxonomy_select'];
                }
                

                 

                if ($settings['tax_link']['url'] != "") {
                    $taxlink = $settings['tax_link']['url'];
                } else if($parentTaxonomy){
                    $taxlink = get_term_link((int)$parentTaxonomy);
                } else {
                    $taxlink = get_post_type_archive_link($settings['taxonomy_select']);
                }
                
                $linkStart = '';
                $linkEnd = '';
                if ($taxlink != ""){
                    $linkStart = '<a href="' . $taxlink . '">';
                    $linkEnd = '</a>';
                }
                //var_dump( $parentTaxonomy );
                //var_dump( $taxlink );

                echo '<h3 class="dce-parent-title">' . $linkStart . $taxtext . $linkEnd . '</h3>';
                if ($settings['show_border'] == 1)
                    echo '<hr />';
            }
            if ($settings['show_childlist']) {
                echo '<ul class="first-level">';

                foreach ($terms as $term) {
                    $term_link = get_term_link($term);
                    $myIDterm = get_queried_object_id();
                    //echo get_the_title($myIDterm);
                    $myTerms = wp_get_post_terms($myIDterm, $settings['taxonomy_select']);

                    //var_dump($myTerms);
                    $linkActive = '';
                    if (/* is_single() && */ isset($myTerms) && count($myTerms) > 0) {




                        $myT = $myTerms[0]->term_id;

                        if ($myT == $term->term_id) {
                            $linkActive = ' class="active"';
                        } else {
                            $linkActive = '';
                        }
                    }

                    // --------------------- Image ACF
                    $image_acf = '';
                    if ($settings['image_acf_enable']) {

                        $idFields = $settings['acf_field_image'];
                        $imageField = get_field($idFields, 'term_' . $term->term_id);
                        $typeField = '';

                        //echo $typeField.': '.$imageField;
                        if (is_string($imageField)) {
                            //echo 'url: '.$imageField;
                            $typeField = 'image_url';
                            $imageSrc = $imageField;
                        } else if (is_numeric($imageField)) {
                            //echo 'id: '.$imageField;
                            $typeField = 'image';
                            $imageSrc = Group_Control_Image_Size::get_attachment_image_src($imageField, 'size', $settings);
                        } else if (is_array($imageField)) {
                            //echo 'array: '.$imageField;
                            $typeField = 'image_array';
                            $imageSrc = Group_Control_Image_Size::get_attachment_image_src($imageField['ID'], 'size', $settings);
                        }
                        if(isset($imageSrc) ) $image_acf = '<img src="' . $imageSrc . '" />';
                    }
                    $a_start = '';
                    $a_end = '';
                    if($settings['link_term']){
                        $a_start = '<a href="' . $term_link . '"' . $linkActive . '>';
                        $a_end = '</a>';
                    }
                    echo '<li class="dce-term-' . $term->term_id . '">' . $a_start . $image_acf . '<span>' . $term->name . '</span>'.$a_end.'</li>';
                } // end each

                echo '</ul>';
                if ($settings['show_border'] == 2)
                    echo '</div>';
            }
            echo '</nav>';
        }
    }

}
