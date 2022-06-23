<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
//use DynamicContentForElementor\Controls\DCE_Group_Control_Filters_CSS;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor ACF-repeater
 *
 * Elementor widget for Dinamic Content Elements
 *
 */
class DCE_Widget_Repeater extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-acf-repeater';
    }

    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('ACF Repeater', 'dynamic-content-for-elementor');
    }

    public function get_icon() {
        return 'icon-dyn-repeater';
    }
    
    public function get_description() {
        return __('Take advantage of the power and flexibility of ACF Repeaters in a easy way', 'dynamic-content-for-elementor');
    }

    public function get_script_depends() {
        return ['imagesloaded', 'swiper', 'jquery-masonry', 'wow', 'dce-acf_repeater'];
    }

    static public function get_position() {
        return 5;
    }

    public function get_plugin_depends() {
        return array('advanced-custom-fields-pro' => 'acf');
    }

    protected function _register_controls() {
        $repeaters = DCE_Helper::get_acf_fields('repeater');
        //var_dump($repeaters);

        $this->start_controls_section(
                'section_dynamictemplate', [
            'label' => __('ACF Repeater', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'dce_acf_repeater',
                [
                    'label' => __('ACF Repeater', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'options' => $repeaters,
                ]
        );
        array_shift($repeaters);

        $this->add_control(
                'dce_acf_repeater_mode', [
            'label' => __('Display mode', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'repeater' => [
                    'title' => __('Repeater', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-list-ul',
                ],
                'html' => [
                    'title' => __('HTML & Token', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-code',
                ],
                'template' => [
                    'title' => __('Template', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-th-large',
                ]
            ],
            'toggle' => false,
            'default' => 'repeater',
                ]
        );
        //"text", "textarea", "number", "range", "email", "url", "password", "image", "file", "wysiwyg", "oembed", "gallery", "select", "checkbox", "radio", "button_group", "true_false", "link", "post_object", "page_link", "relationship", "taxonomy", "user", "google_map", "date_picker", "date_time_picker", "time_picker", "color_picker", "message", "accordion", "tab", "group", "repeater", "flexible_content", "clone"
        $supported_types = ['text', 'textarea', 'wysiwyg', 'date_picker', 'time_picker', 'date_time_picker', 'number', 'select', 'image', 'url'];
        foreach ($repeaters as $arepeater => $arepeater_title) {
            if ($arepeater) {
                $arepeater_fields = DCE_Helper::get_acf_repeater_fields($arepeater);
                //var_dump($arepeater_fields);die();
                $default = [];
                foreach ($arepeater_fields as $key => $acfitem) {
                    if (false && !in_array($acfitem['type'], $supported_types))
                        continue;
                    $default[] = [
                        'dce_acf_repeater_field_name' => $key,
                        'dce_acf_repeater_field_type' => $acfitem['type'],
                        'dce_acf_repeater_field_show' => 'yes',
                        'dce_views_select_field_label' => $acfitem['title'],
                    ];
                }
                $repeater_fields = new \Elementor\Repeater();

                $repeater_fields->start_controls_tabs('acfitems_repeater');

                $repeater_fields->start_controls_tab('tab_content', ['label' => __('Item', 'dynamic-content-for-elementor')]);

                /* $repeater_fields->add_control(
                  'dce_acf_repeater_field_title', [
                  'label' => $arepeater, //$acfitem['title'],
                  'type' => Controls_Manager::HEADING,
                  ]
                  ); */
                $repeater_fields->add_control(
                        'dce_acf_repeater_field_name', [
                    'label' => __('Name', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HIDDEN,
                        //'value' => $key,
                        ]
                );
                $repeater_fields->add_control(
                        'dce_acf_repeater_field_type', [
                    'label' => __('Type', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HIDDEN,
                        //'value' => $acfitem['type'],
                        ]
                );
                $repeater_fields->add_control(
                        'dce_views_select_field_label', [
                    'label' => __('Label', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                        ]
                );
                $repeater_fields->add_control(
                        'dce_acf_repeater_field_show', [
                    'label' => __('Show', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                        //'render_type' => 'template'
                        ]
                );
                $repeater_fields->add_control(
                        'dce_acf_repeater_field_tag',
                        [
                            'label' => __('HTML Tag', 'elementor'),
                            'type' => Controls_Manager::SELECT,
                            'options' => [
                                '' => 'None',
                                'h1' => 'H1',
                                'h2' => 'H2',
                                'h3' => 'H3',
                                'h4' => 'H4',
                                'h5' => 'H5',
                                'h6' => 'H6',
                                'div' => 'div',
                                'span' => 'span',
                                'p' => 'p',
                            ],
                            'default' => 'div',
                        ]
                );
                $repeater_fields->add_control(
                        'dce_acf_repeater_label_tag',
                        [
                            'label' => __('HTML Label', 'elementor'),
                            'type' => Controls_Manager::SELECT,
                            'options' => [
                                '' => 'None',
                                'label' => 'label',
                                'div' => 'div',
                                'span' => 'span',
                                'p' => 'p',
                            ],
                            'default' => 'label',
                            'condition' => [
                                'dce_views_select_field_label!' => '',
                            ]
                        ]
                );
                $repeater_fields->end_controls_tab();

                $repeater_fields->start_controls_tab('tab_style', ['label' => __('Style', 'dynamic-content-for-elementor')]);

                $repeater_fields->add_responsive_control(
                        'dce_acf_repeater_field_align', [
                    'label' => __('Alignment', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'toggle' => true,
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
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}};',
                    ],
                        //'prefix_class' => 'acfposts-align-'
                        ]
                );

                $repeater_fields->add_responsive_control(
                        'dce_acf_repeater_field_space', [
                    'label' => __('Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 100,
                            'min' => 0,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}}  {{CURRENT_ITEM}}' => 'margin-bottom: {{SIZE}}{{UNIT}};'
                    ],
                        ]
                );

                $repeater_fields->add_control(
                        'dce_acf_repeater_field_padding', [
                    'label' => __('Padding', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'after',
                        ]
                );

                // ---------- Texts
                $repeater_fields->add_control(
                        'dce_acf_repeater_h_texts', [
                    'label' => __('Texts', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'condition' => [
                        'dce_acf_repeater_field_type' => ['text', 'textarea', 'wysiwyg', 'date_picker', 'date_time', 'date_time_picker', 'number', 'select'],
                    ]
                        ]
                );
               
                $repeater_fields->add_control(
                    'dce_acf_repeater_field_color', [
                        'label' => __('Text Color', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::COLOR,
                        //'render_type' => 'ui',
                        'selectors' => [
                            '{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
                            '{{WRAPPER}} {{CURRENT_ITEM}} a' => 'color: {{VALUE}};',
                        ],
                        'condition' => [
                            'dce_acf_repeater_field_type' => ['text', 'textarea', 'wysiwyg', 'date_picker', 'date_time', 'date_time_picker', 'number', 'select'],
                        ]
                    ]
                );
                 $repeater_fields->add_control(
                      'dce_acf_repeater_field_hover_color', [
                          'label' => __('Hover Color', 'dynamic-content-for-elementor'),
                          'type' => Controls_Manager::COLOR,
                          'selectors' => [
                          '{{WRAPPER}} {{CURRENT_ITEM}} a:hover' => 'color: {{VALUE}};',
                          ],
                          'condition' => [
                            'dce_acf_repeater_field_type' => ['text','textarea','wysiwyg','date_picker','date_time','date_time_picker','number','select'],
                            'dce_acf_repeater_enable_link!' => '',
                          ]
                      ]
                  ); 
                $repeater_fields->add_group_control(
                    Group_Control_Typography::get_type(), [
                        'name' => 'dce_acf_repeater_field_typography',
                        'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
                        //'render_type' => 'ui',
                        'pop_hover' => true,
                        'condition' => [
                            'dce_acf_repeater_field_type' => ['text', 'textarea', 'wysiwyg', 'date_picker', 'date_time', 'date_time_picker', 'number', 'select'],
                        ]
                    ]
                );


                // ---------- Image
                $repeater_fields->add_control(
                    'dce_acf_repeater_h_image', [
                        'label' => __('Image', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::HEADING,
                        'condition' => [
                            'dce_acf_repeater_field_type' => ['image'],
                        ]
                    ]
                );
                $repeater_fields->add_responsive_control(
                        'dce_acf_repeater_field_size_image', [
                    'label' => __('Max-Width', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => '',
                        'unit' => '%',
                    ],
                    //'render_type' => 'ui',
                    /* ,
                      'tablet_default' => [
                      'unit' => '%',
                      ],
                      'mobile_default' => [
                      'unit' => '%',
                      ],
                     */
                    'size_units' => ['%', 'px'],
                    'range' => [
                        '%' => [
                            'min' => 1,
                            'max' => 100,
                            'step' => 1
                        ],
                        'px' => [
                            'min' => 1,
                            'max' => 800,
                            'step' => 1
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} img' => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'dce_acf_repeater_field_type' => ['image'],
                    ]
                        ]
                );
                $repeater_fields->add_group_control(
                        Group_Control_Border::get_type(), [
                    'name' => 'image_border',
                    'label' => __('Image Border', 'dynamic-content-for-elementor'),
                    'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} img',
                    'condition' => [
                        'dce_acf_repeater_field_type' => ['image'],
                    ]
                        ]
                );
                $repeater_fields->add_control(
                        'image_border_radius', [
                    'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'dce_acf_repeater_field_type' => ['image'],
                    ]
                        ]
                );
                //  ------------------------------------- [SHADOW]
                $repeater_fields->add_group_control(
                        Group_Control_Box_Shadow::get_type(), [
                    'name' => 'image_box_shadow',
                    'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} img',
                    'condition' => [
                        'dce_acf_repeater_field_type' => ['image'],
                    ]
                        ]
                );
                //  ------------------------------------- [FILTERS]
                $repeater_fields->add_group_control(
                        Group_Control_Css_Filter::get_type(),
                        [
                            'name' => 'filters_image',
                            'label' => __('Filters image', 'dynamic-content-for-elementor'),
                            //'selector' => '{{WRAPPER}} img, {{WRAPPER}} .dynamic-content-featuredimage-bg',
                            'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} img',
                            'condition' => [
                                'dce_acf_repeater_field_type' => ['image'],
                            ]
                        ]
                );
                $repeater_fields->end_controls_tab();

                $repeater_fields->start_controls_tab('tab_link', ['label' => __('Link', 'dynamic-content-for-elementor')]);
                
                $repeater_fields->add_control(
                    'dce_acf_repeater_enable_link', [
                        'label' => __('Enable link', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SWITCHER,
                    //'render_type' => 'template'
                    ]
                );
                $link_fields = DCE_Helper::get_acf_fields('url');
                $repeater_fields->add_control(
                    'dce_acf_repeater_acfield_link', [
                        'label' => __('URL Field', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SELECT,
                        'label_block' => true,
                        'groups' => $link_fields,
                        'default' => 0,
                        'frontend_available' => true,
                        'condition' => [
                            'dce_acf_repeater_enable_link!' => '',
                        ]
                    ]
                );
                $repeater_fields->add_control(
                    'dce_acf_repeater_target_link', [
                        'label' => __('Open in new window', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SWITCHER,
                        'condition' => [
                            'dce_acf_repeater_enable_link!' => '',
                            'dce_acf_repeater_acfield_link!' => ''
                        ]
                    //'render_type' => 'template'
                    ]
                );
                $repeater_fields->end_controls_tab();
                
                $repeater_fields->end_controls_tabs();

                $this->add_control(
                        'dce_acf_repeater_fields_' . $arepeater, [
                    'label' => __('Repeater fields', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $repeater_fields->get_controls(),
                    'title_field' => '{{{ dce_views_select_field_label }}}',
                    'default' => $default,
                    //'render_type' => 'ui',
                    'item_actions' => [
                        'add' => false,
                        'duplicate' => false,
                        'remove' => false,
                        'sort' => true,
                    ],
                    'condition' => [
                        'dce_acf_repeater' => $arepeater,
                        'dce_acf_repeater_mode' => 'repeater',
                    ],
                        ]
                );
                /* $this->add_control(
                  'dce_acf_repeater_hide_fields_'.$arepeater, [
                  //'label' => __('Repeater fields', 'dynamic-content-for-elementor'),
                  'type' => \Elementor\Controls_Manager::RAW_HTML,
                  'raw' => '<style>.elementor-control-dce_acf_repeater_fields_'.$arepeater.'.elementor-control-type-repeater .elementor-repeater-row-tools .elementor-repeater-row-tool { display: none !important; }</style>',
                  'condition' => [
                  'dce_acf_repeater' => $arepeater,
                  ],
                  ]
                  ); */
            }
        }
        $this->add_control(
                'dce_acf_repeater_html', [
            'label' => __('Custom HTML', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CODE, //WYSIWYG,
            'default' => "[ROW]",
            'description' => __("Write here some content, you can use HTML and TOKENS.", 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_acf_repeater_mode' => 'html',
            ],
                ]
        );
        $this->add_control(
                'dce_acf_repeater_template',
                [
                    'label' => __('Template', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Select Template', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'object_type' => 'elementor_library',
                    'description' => 'Use a Elementor Template as content of popup, usefull for complex structure.',
                    'condition' => [
                        'dce_acf_repeater_mode' => 'template',
                    ],
                ]
        );
        $this->add_control(
                'dce_acf_repeater_pagination', [
            'label' => __('Show row item', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => 'first, last, 1, 3-4',
            'description' => __('Leave empty to print all rows, otherwise write the number of them. Use "first" and "last" to indicate the first and last element.', 'dynamic-content-for-elementor'),
                ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
                'dce_acf_repeater_options', [
            'label' => __('Options', 'dynamic-content-for-elementor'),
                ]
        );

        $this->add_group_control(
                Group_Control_Image_Size::get_type(), [
            'name' => 'imgsize',
            'label' => __('Image Size', 'dynamic-content-for-elementor'),
            'default' => 'large',
            'render_type' => 'template',
                ]
        );

        /* $this->add_control(
          'date_format', [
          'label' => __('Format date', 'dynamic-content-for-elementor'),
          'description' => '<a target="_blank" href="https://www.php.net/manual/en/function.date.php">' . __('Use standard PHP format character') .'</a>',
          'type' => Controls_Manager::TEXT,
          'default' => 'F j, Y, g:i a',
          'label_block' => true
          ]
          ); */
        $this->add_responsive_control(
                'alignment', [
            'label' => __('Global Alignment', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => true,
            'separator' => 'before',
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
            'selectors' => [
                '{{WRAPPER}}' => 'text-align: {{VALUE}};',
            ],
                //'prefix_class' => 'acfposts-align-'
                ]
        );
        $this->add_responsive_control(
                'dce_acf_repeater_field_col', [
            'label' => __('Col space', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'tablet_default' => [
                'size' => '',
            ],
            'mobile_default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 50,
                    'min' => 0,
                    'step' => 1.0000,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-acf-repeater-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'dce_acf_repeater_field_row', [
            'label' => __('Row space', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'tablet_default' => [
                'size' => '',
            ],
            'mobile_default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 50,
                    'min' => 0,
                    'step' => 1.0000,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-acf-repeater-item' => 'padding-bottom: {{SIZE}}{{UNIT}};',
            ],
                ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
                'dce_acf_repeater_h_render', [
            'label' => __('Render', 'dynamic-content-for-elementor'),
                ]
        );

        $this->add_control(
                'dce_acf_repeater_format', [
            'label' => __('Render as ', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'frontend_available' => true,
            'options' => array('' => 'Natual', 'grid' => 'Grid', 'masonry' => 'Masonry', 'slider_carousel' => 'Slider/Carousel', 'table' => 'Table (No Template)', 'list' => 'List'),
            'default' => 'grid',
                ]
        );
        
        // -------------------------------- SHOW LABEL ON GRID
        $this->add_control(
            'dce_acf_repeater_grid_label', [
        'label' => __('Show label', 'dynamic-content-for-elementor'),
        'type' => Controls_Manager::SWITCHER,
        'condition' => [
            'dce_acf_repeater_format' => 'grid',
        ],
            ]
        );
        
        $this->add_control(
                'dce_acf_repeater_separator', [
            'label' => __('Separator', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => ', ',
            'condition' => [
                'dce_acf_repeater_format' => '',
            ],
                ]
        );




        // ---------------------------------- LIST
        $this->add_control(
                'dce_acf_repeater_list', [
            'label' => __('List type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'ul' => [
                    'title' => __('Unordered list', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-list-ul',
                ],
                'ol' => [
                    'title' => __('Ordered list', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-list-ol',
                ]
            ],
            'toggle' => false,
            'default' => 'ul',
            'condition' => [
                'dce_acf_repeater_format' => 'list',
            ],
                ]
        );
        /* $this->add_responsive_control(
          'dce_acf_repeater_col', [
          'label' => __('Columns', 'dynamic-content-for-elementor'),
          'type' => \Elementor\Controls_Manager::NUMBER,
          'default' => 3,
          'min' => 1,
          'description' => __("Set 1 to show one result per line", 'dynamic-content-for-elementor'),
          'condition' => [
          'dce_acf_repeater_format' => 'grid',
          ],
          ]
          ); */

        // ---------------------------------------------- GRID
        $this->add_responsive_control(
                'dce_acf_repeater_col', [
            'label' => __('Columns', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => '5',
            'tablet_default' => '3',
            'mobile_default' => '1',
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7'
            ],
            //'frontend_available' => true,
            //'prefix_class' => 'columns-',
            'render_type' => 'template',
            'selectors' => [
                '{{WRAPPER}} .dce-acf-repeater-item' => 'width: calc( 100% / {{VALUE}} );',
                '{{WRAPPER}} .dce-acf-repeater-item.equalHMR' => 'flex: 0 1 calc( 100% / {{VALUE}} );',
            ],
            'condition' => [
                'dce_acf_repeater_format' => ['grid', 'masonry'],
            ],
                ]
        );
        $this->add_control(
                'flex_grow', [
            'label' => __('Flex grow', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'label_block' => false,
            'options' => [
                '1' => [
                    'title' => __('1', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-check',
                ],
                '0' => [
                    'title' => __('0', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-ban',
                ]
            ],
            'default' => 1,
            'selectors' => [
                '{{WRAPPER}} .dce-post-item.equalHMR' => 'flex-grow: {{VALUE}};',
            ],
            'condition' => [
                'dce_acf_repeater_format' => 'grid',
            ],
                ]
        );
        $this->add_responsive_control(
                'flexgrid_mode', [
            'label' => __('Alignment grid', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'flex-start',
            'tablet_default' => '3',
            'mobile_default' => '1',
            'label_block' => true,
            'options' => [
                'flex-start' => 'Flex start',
                'flex-end' => 'Flex end',
                'center' => 'Center',
                'space-between' => 'Space Between',
                'space-around' => 'Space Around',
            ],
            //'frontend_available' => true,
            'selectors' => [
                '{{WRAPPER}} .equalHMRWrap' => 'justify-content: {{VALUE}};',
            ],
            'condition' => [
                'dce_acf_repeater_format' => 'grid',
                'flex_grow' => '0'
            ],
                ]
        );
        $this->add_responsive_control(
                'v_align_items', [
            'label' => __('Vertical Alignment', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => __('Top', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => __('Middle', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-v-align-middle',
                ],
                'flex-end' => [
                    'title' => __('Down', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-v-align-bottom',
                ],
                'stretch' => [
                    'title' => __('Stretch', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-v-align-stretch',
                ],
            ],
            'default' => 'top',
            'selectors' => [
                '{{WRAPPER}} .equalHMR' => 'align-self: {{VALUE}};',
            ],
            'condition' => [
                'dce_acf_repeater_format' => 'grid',
                'flex_grow' => '0'
            ],
                ]
        );





        // -------------------------------- TABLE
        $this->add_control(
                'dce_acf_repeater_thead', [
            'label' => __('Table Head', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'dce_acf_repeater_format' => 'table',
            ],
                ]
        );

        /* $this->add_control(
          'dce_page_value',
          [
          'label' => __('Fields', 'dynamic-content-for-elementor'),
          'type' 		=> 'ooo_query',
          'label_block' 	=> true,
          'multiple'	=> true,
          'query_type'	=> 'fields',
          'object_type'	=> 'post',
          ]
          ); */

        $this->end_controls_section();

        //
        //////////////////////////////////////////////////////////// [ SECTION Slider & Carousel ]
        //
        // ------------------------------ Base Settings, Slides grid, Grab Cursor
        $this->start_controls_section(
                'section_slidercarousel_mode', [
            'label' => __('Slider Carousel', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_acf_repeater_format' => 'slider_carousel',
            ],
                ]
        );
        // -------------------------------- Progressione ------
        // da valutare ....
        $this->add_control(
                'effects', [
            'label' => __('Effect of transition', 'dynamic-content-for-elementor'),
            'description' => __('Tranisition effect from the slides.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'slide' => __('Slide', 'dynamic-content-for-elementor'),
                'fade' => __('Fade', 'dynamic-content-for-elementor'),
                'cube' => __('Cube', 'dynamic-content-for-elementor'),
                'coverflow' => __('Coverflow', 'dynamic-content-for-elementor'),
                'flip' => __('Flip', 'dynamic-content-for-elementor'),
            ],
            'default' => 'slide',
            'frontend_available' => true,
                /* 'prefix_class' => 'effect-' */
                ]
        );


        $this->add_responsive_control(
                'slidesPerView', [
            'label' => __('Slides Per View', 'dynamic-content-for-elementor'),
            'description' => __('Number of slides per view (slides visible at the same time on slider\'s container). If you use it with "auto" value and along with loop: true then you need to specify loopedSlides parameter with amount of slides to loop (duplicate). SlidesPerView: \'auto\' is currently not compatible with multirow mode, when slidesPerColumn > 1', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => '1',
            //'tablet_default' => '',
            //'mobile_default' => '',
            'min' => 1,
            'max' => 12,
            'step' => 1,
            'frontend_available' => true
                ]
        );


        $this->add_responsive_control(
                'slidesColumn', [
            'label' => __('Slides Column', 'dynamic-content-for-elementor'),
            'description' => __('Number of slides per column, for multirow layout.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => '1',
            //'tablet_default' => '',
            //'mobile_default' => '',
            'min' => 1,
            'max' => 4,
            'step' => 1,
            'frontend_available' => true
                ]
        );
        $this->add_responsive_control(
                'slidesPerGroup', [
            'label' => __('Slides Per Group', 'dynamic-content-for-elementor'),
            'description' => __('Set numbers of slides to define and enable group sliding. Useful to use with slidesPerView > 1', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'tablet_default' => '',
            'mobile_default' => '',
            'min' => 1,
            'max' => 12,
            'step' => 1,
            'frontend_available' => true
                ]
        );

        $this->add_control(
                'direction_slider', [
            'label' => __('Direction', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HIDDEN,
            'options' => [
                'horizontal' => __('Horizontal', 'dynamic-content-for-elementor'),
                'vertical' => __('Vertical', 'dynamic-content-for-elementor'),
            ],
            'default' => 'horizontal',
            'frontend_available' => true
                ]
        );
        $this->add_control(
                'speed_slider', [
            'label' => __('Speed', 'dynamic-content-for-elementor'),
            'description' => __('Duration of transition between slides (in ms)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 300,
            'min' => 0,
            'max' => 3000,
            'step' => 10,
            'frontend_available' => true
                ]
        );
        $this->add_responsive_control(
                'spaceBetween', [
            'label' => __('Space Between', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'tablet_default' => '',
            'mobile_default' => '',
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'frontend_available' => true
                ]
        );

        $this->add_control(
                'centeredSlides', [
            'label' => __('Centered Slides', 'dynamic-content-for-elementor'),
            'description' => __('If true, then active slide will be centered, not always on the left side.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true
                ]
        );

        // -------------------------------- Free Mode ------
        $this->add_control(
                'freemode_options', [
            'label' => __('Free Mode', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'freeMode', [
            'label' => __('Free Mode', 'dynamic-content-for-elementor'),
            'description' => __('If true then slides will not have fixed positions', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true
                ]
        );
        $this->add_control(
                'freeModeMomentum', [
            'label' => __('Free Mode Momentum', 'dynamic-content-for-elementor'),
            'description' => __('If true, then slide will keep moving for a while after you release it', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'condition' => [
                'freeMode' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'freeModeMomentumRatio', [
            'label' => __('Free Mode Momentum Ratio', 'dynamic-content-for-elementor'),
            'description' => __('Higher value produces larger momentum distance after you release slider', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 0,
            'max' => 10,
            'step' => 0.1,
            'frontend_available' => true,
            'condition' => [
                'freeMode' => 'yes',
                'freeModeMomentum' => 'yes'
            ]
                ]
        );
        $this->add_control(
                'freeModeMomentumVelocityRatio', [
            'label' => __('Free Mode Momentum Velocity Ratio', 'dynamic-content-for-elementor'),
            'description' => __('Higher value produces larger momentum velocity after you release slider', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 0,
            'max' => 10,
            'step' => 0.1,
            'frontend_available' => true,
            'condition' => [
                'freeMode' => 'yes',
                'freeModeMomentum' => 'yes'
            ]
                ]
        );
        $this->add_control(
                'freeModeMomentumBounce', [
            'label' => __('Free Mode Momentum Bounce', 'dynamic-content-for-elementor'),
            'description' => __('Set to false if you want to disable momentum bounce in free mode', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'frontend_available' => true,
            'condition' => [
                'freeMode' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'freeModeMomentumBounceRatio', [
            'label' => __('Free Mode Momentum Bounce Ratio', 'dynamic-content-for-elementor'),
            'description' => __('Higher value produces larger momentum bounce effect', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 0,
            'max' => 10,
            'step' => 0.1,
            'frontend_available' => true,
            'condition' => [
                'freeMode' => 'yes',
                'freeModeMomentumBounce' => 'yes'
            ]
                ]
        );
        $this->add_control(
                'freeModeMinimumVelocity', [
            'label' => __('Free Mode Momentum Velocity Ratio', 'dynamic-content-for-elementor'),
            'description' => __('Minimum touchmove-velocity required to trigger free mode momentum', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0.02,
            'min' => 0,
            'max' => 1,
            'step' => 0.01,
            'frontend_available' => true,
            'condition' => [
                'freeMode' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'freeModeSticky', [
            'label' => __('Free Mode Sticky', 'dynamic-content-for-elementor'),
            'description' => __('Set \'yes\' to enable snap to slides positions in free mode', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'condition' => [
                'freeMode' => 'yes',
            ]
                ]
        );
        // -------------------------------- Navigation options ------
        $this->add_control(
                'navigation_options', [
            'label' => __('Navigation options', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'useNavigation', [
            'label' => __('Use Navigation', 'dynamic-content-for-elementor'),
            'description' => __('Set "yes", you will use the navigation arrows.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'frontend_available' => true,
                ]
        );

        // ------------------------------------------------- Navigations Arrow Options
        $this->add_control(
                'navigation_arrow_color', [
            'label' => __('Arrows color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-button-next path, {{WRAPPER}} .swiper-button-prev path, ' => 'fill: {{VALUE}};',
                '{{WRAPPER}} .swiper-button-next line, {{WRAPPER}} .swiper-button-prev line, {{WRAPPER}} .swiper-button-next polyline, {{WRAPPER}} .swiper-button-prev polyline' => 'stroke: {{VALUE}};',
            ],
            'condition' => [
                'useNavigation' => 'yes'
            ]
                ]
        );

        $this->add_control(
                'useNavigation_animationHover', [
            'label' => __('Use animation in rollover', 'dynamic-content-for-elementor'),
            'description' => __('If "yes", a short animation will take place at the rollover.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'prefix_class' => 'hoveranim-',
            'separator' => 'before',
            'condition' => [
                'useNavigation' => 'yes'
            ]
                ]
        );
        $this->add_control(
                'navigation_arrow_color_hover', [
            'label' => __('Hover color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-button-next:hover path, {{WRAPPER}} .swiper-button-prev:hover path, ' => 'fill: {{VALUE}};',
                '{{WRAPPER}} .swiper-button-next:hover line, {{WRAPPER}} .swiper-button-prev:hover line, {{WRAPPER}} .swiper-button-next:hover polyline, {{WRAPPER}} .swiper-button-prev:hover polyline' => 'stroke: {{VALUE}};',
            ],
            'condition' => [
                'useNavigation' => 'yes'
            ],
            'separator' => 'after',
                ]
        );
        $this->add_responsive_control(
                'pagination_stroke_1', [
            'label' => __('Stroke Arrow', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'tablet_default' => [
                'size' => '',
            ],
            'mobile_default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 50,
                    'min' => 0,
                    'step' => 1.0000,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-button-prev polyline, {{WRAPPER}} .swiper-button-next polyline' => 'stroke-width: {{SIZE}};',
            ],
            'condition' => [
                'useNavigation' => 'yes'
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_stroke_2', [
            'label' => __('Stroke Line', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'tablet_default' => [
                'size' => '',
            ],
            'mobile_default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 50,
                    'min' => 0,
                    'step' => 1.0000,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-button-next line, {{WRAPPER}} .swiper-button-prev line' => 'stroke-width: {{SIZE}};',
            ],
            'condition' => [
                'useNavigation' => 'yes'
            ]
                ]
        );

        ////////
        $this->add_control(
                'pagination_tratteggio', [
            'label' => __('Dashed', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '0',
            ],
            'range' => [
                'px' => [
                    'max' => 50,
                    'min' => 0,
                    'step' => 1.0000,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-button-prev line, {{WRAPPER}} .swiper-button-next line, {{WRAPPER}} .swiper-button-prev polyline, {{WRAPPER}} .swiper-button-next polyline' => 'stroke-dasharray: {{SIZE}},{{SIZE}};',
            ],
            'condition' => [
                'useNavigation' => 'yes'
            ]
                ]
        );

        $this->add_responsive_control(
                'pagination_scale', [
            'label' => __('Scale', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'tablet_default' => [
                'size' => '',
            ],
            'mobile_default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 2,
                    'min' => 0.10,
                    'step' => 0.01,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'transform: scale({{SIZE}});',
            ],
            'condition' => [
                'useNavigation' => 'yes'
            ]
                ]
        );

        $this->add_responsive_control(
                'pagination_position', [
            'label' => __('Horozontal Position', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'tablet_default' => [
                'size' => '',
            ],
            'mobile_default' => [
                'size' => '',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => -100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                //'{{WRAPPER}} .swiper-container-horizontal .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
                //'{{WRAPPER}} .swiper-container-horizontal .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'useNavigation' => 'yes'
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_position_v', [
            'label' => __('Verical Position', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 50,
                'unit' => '%'
            ],
            'size_units' => ['%', 'px'],
            'range' => [
                '%' => [
                    'max' => 120,
                    'min' => -20,
                    'step' => 1,
                ],
                'px' => [
                    'max' => 200,
                    'min' => -200,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'top: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'useNavigation' => 'yes'
            ]
                ]
        );
        // --------------------------------------------------- Pagination options ------
        $this->add_control(
                'pagination_options', [
            'label' => __('Pagination options', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_responsive_control(
                'usePagination', [
            'label' => __('Use Pagination', 'dynamic-content-for-elementor'),
            'description' => __('If "yes", use the slide progression display system ("bullets", "fraction", "progress").', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'frontend_available' => true,
                ]
        );
        $this->add_control(
                'pagination_type', [
            'label' => __('Pagination Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'bullets' => __('Bullets', 'dynamic-content-for-elementor'),
                'fraction' => __('Fraction', 'dynamic-content-for-elementor'),
                'progress' => __('Progress', 'dynamic-content-for-elementor'),
            ],
            'default' => 'bullets',
            'frontend_available' => true,
            'condition' => [
                'usePagination' => 'yes',
            ]
                ]
        );

        // ------------------------------------------------- Pagination Fraction Options
        $this->add_control(
                'fraction_separator', [
            'label' => __('Fraction text separator', 'dynamic-content-for-elementor'),
            'description' => __('The text that separates the 2 numbers', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'frontend_available' => true,
            'default' => '/',
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'fraction',
            ]
                ]
        );
        $this->add_responsive_control(
                'fraction_space', [
            'label' => __('Spacing', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '4',
                'unit' => 'px',
            ],
            'tablet_default' => [
                'unit' => 'px',
            ],
            'mobile_default' => [
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -20,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-fraction .separator' => 'margin: 0 {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'fraction',
            ]
                ]
        );
        $this->add_control(
                'fraction_color', [
            'label' => __('Il colore dei numeri', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-fraction > *' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'fraction',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'fraction_typography',
            'label' => __('Typography numeri', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .swiper-pagination-fraction > *',
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'fraction',
            ]
                ]
        );
        $this->add_control(
                'fraction_current_color', [
            'label' => __('Il colore del numero corrente', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-fraction .swiper-pagination-current' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'fraction',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'fraction_typography_current',
            'label' => __('Current number typography', 'dynamic-content-for-elementor'),
            'default' => '',
            'selector' => '{{WRAPPER}} .swiper-pagination-fraction .swiper-pagination-current',
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'fraction',
            ]
                ]
        );
        $this->add_control(
                'fraction_separator_color', [
            'label' => __('The color of the separator', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-fraction .separator' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'fraction',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => __('fraction_typography_separator', 'dynamic-content-for-elementor'),
            'label' => 'Typography separator',
            'default' => '',
            'selector' => '{{WRAPPER}} .swiper-pagination-fraction .separator',
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'fraction',
            ]
                ]
        );

        // ------------------------------------------------- Pagination Bullets Options
        $this->add_responsive_control(
                'bullets_space', [
            'label' => __('Space', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '5',
                'unit' => 'px',
            ],
            'tablet_default' => [
                'unit' => 'px',
            ],
            'mobile_default' => [
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'bullets',
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_bullets', [
            'label' => __('Bullets dimension', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '8',
                'unit' => 'px',
            ],
            'tablet_default' => [
                'unit' => 'px',
            ],
            'mobile_default' => [
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'bullets',
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_posy', [
            'label' => __('Shift', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '10',
                'unit' => 'px',
            ],
            'tablet_default' => [
                'unit' => 'px',
            ],
            'mobile_default' => [
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -160,
                    'max' => 160,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination' => ' bottom: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => ['bullets', 'fraction'],
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_space', [
            'label' => __('Space', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '10',
                'unit' => 'px',
            ],
            'tablet_default' => [
                'unit' => 'px',
            ],
            'mobile_default' => [
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 60,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination' => 'padding-right: {{SIZE}}{{UNIT}}; padding-left: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => ['bullets', 'fraction'],
            ]
                ]
        );
        $this->add_control(
                'bullets_color', [
            'label' => __('Bullets Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'bullets',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'border_bullet',
            'label' => __('Dullets border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet',
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'bullets',
            ]
                ]
        );
        $this->add_responsive_control(
                'current_bullet', [
            'label' => __('Dimension of active bullet', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'tablet_default' => [
                'unit' => 'px',
            ],
            'mobile_default' => [
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .swiper-pagination.swiper-pagination-bullets' => 'height: {{SIZE}}{{UNIT}}'
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'bullets',
            ]
                ]
        );
        $this->add_control(
                'current_bullet_color', [
            'label' => __('Active bullet color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'bullets',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'border_current_bullet',
            'label' => __('Active bullet border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet-active',
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'bullets',
            ]
                ]
        );
        $this->add_control(
                'progress_color', [
            'label' => __('Progress color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-progress' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'progress',
            ]
                ]
        );
        $this->add_control(
                'progressbar_color', [
            'label' => __('Progressbar color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-progress .swiper-pagination-progressbar' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'progress',
            ]
                ]
        );
        // -------------------------------- Scrollbar options ------
        /* $this->add_control(
          'scrollbar_options', [
          'label' => __('Scrollbar options', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::HEADING,
          'separator' => 'before',
          ]
          );
          $this->add_control(
          'useScrollbar', [
          'label' => __('Use Scrollbar', 'dynamic-content-for-elementor'),
          'description' => __('If "yes", you will use a scrollbar that displays navigation', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SWITCHER,
          'default' => '',
          'label_on' => __('Yes', 'dynamic-content-for-elementor'),
          'label_off' => __('No', 'dynamic-content-for-elementor'),
          'return_value' => 'yes',
          ]
          ); */
        $this->add_responsive_control(
                'h_align_pagination', [
            'label' => __('Horizontal Alignment', 'dynamic-content-for-elementor'),
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
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination' => 'text-align: {{VALUE}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => ['bullets', 'fraction'],
            ]
                ]
        );
        // -------------------------------- Autoplay ------
        $this->add_control(
                'autoplay_options', [
            'label' => __('Autoplay options', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'useAutoplay', [
            'label' => __('Use Autoplay', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
                ]
        );
        $this->add_control(
                'autoplay', [
            'label' => __('Auto Play', 'dynamic-content-for-elementor'),
            'description' => __('Delay between transitions (in ms). If this parameter is not specified (by default), autoplay will be disabled', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 3000,
            'min' => 0,
            'max' => 7000,
            'step' => 100,
            'frontend_available' => true,
            'condition' => [
                'useAutoplay' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'autoplayStopOnLast', [
            'label' => __('Autoplay stop on last slide', 'dynamic-content-for-elementor'),
            'description' => __('Enable this parameter and autoplay will be stopped when it reaches last slide (has no effect in loop mode)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'condition' => [
                'autoplay!' => '',
            ]
                ]
        );
        $this->add_control(
                'autoplayDisableOnInteraction', [
            'label' => __('Autoplay Disable on interaction', 'dynamic-content-for-elementor'),
            'description' => __('Set to "false" and autoplay will not be disabled after user interactions (swipes), it will be restarted every time after interaction', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'frontend_available' => true,
            'condition' => [
                'autoplay!' => '',
            ]
                ]
        );
        // -------------------------------- Keyboard ------
        $this->add_control(
                'keyboard_options', [
            'label' => __('Keyboard / Mousewheel', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'keyboardControl', [
            'label' => __('Keyboard Control', 'dynamic-content-for-elementor'),
            'description' => __('Set to true to enable keyboard control', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
                ]
        );
        $this->add_control(
                'mousewheelControl', [
            'label' => __('Mousewheel Control', 'dynamic-content-for-elementor'),
            'description' => __('Enables navigation through slides using mouse wheel', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
                ]
        );



        // -------------------------------- Ciclo ------
        $this->add_control(
                'cicleloop_options', [
            'label' => __('Cicle / Loop', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'loop', [
            'label' => __('Loop', 'dynamic-content-for-elementor'),
            'description' => __('Set to true to enable continuous loop mode', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
                ]
        );
        // -------------------------------- Special options ---------
        $this->add_control(
                'special_options', [
            'label' => __('Specials options', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'autoHeight', [
            'label' => __('Auto Height', 'dynamic-content-for-elementor'),
            'description' => __('Set to true and slider wrapper will adopt its height to the height of the currently active slide', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true
                ]
        );
        $this->add_control(
                'grabCursor', [
            'label' => __('Grab Cursor', 'dynamic-content-for-elementor'),
            'description' => __('This option may a little improve desktop usability. If true, user will see the "grab" cursor when hover on Swiper', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true
                ]
        );
        $this->add_control(
                'masking_enable', [
            'label' => __('Remove Masking', 'dynamic-content-for-elementor'),
            'description' => 'Remove the mask on the carousel to allow the display of the elements outside.',
            'type' => Controls_Manager::SWITCHER,
            'separator' => 'before',
            'prefix_class' => 'no-masking-',
            'default' => '',
                ]
        );
        $this->end_controls_section();


        // ------------------------------- OTHER SOUCE
        $this->start_controls_section(
                'section_dce_settings', [
            'label' => __('Dynamic Content', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_SETTINGS,
                ]
        );
        $this->add_control(
                'data_source',
                [
                    'label' => __('Source', 'dynamic-content-for-elementor'),
                    'description' => __('Select the data source', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'label_on' => __('Same', 'dynamic-content-for-elementor'),
                    'label_off' => __('other', 'dynamic-content-for-elementor'),
                    'return_value' => 'yes',
                ]
        );
        /* $this->add_control(
          'other_post_source', [
          'label' => __('Select from other source post', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SELECT,

          //'options' => DCE_Helper::get_all_posts(),
          'groups' => DCE_Helper::get_all_posts(get_the_ID(), true),
          'default' => '',
          'condition' => [
          'data_source' => '',
          ],
          ]
          ); */
        $this->add_control(
                'other_post_source',
                [
                    'label' => __('Select from other source post', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Post Title', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'condition' => [
                        'data_source' => '',
                    ],
                ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_active_settings();
        if (empty($settings))
            return;

        // ------------------------------------------
        $dce_data = DCE_Helper::dce_dynamic_data($settings['other_post_source']);
        $id_page = $dce_data['id'];
        $type_page = $dce_data['type'];
        // ------------------------------------------

        if ($settings['dce_acf_repeater']) {
            $values = array();

            if (have_rows($settings['dce_acf_repeater'], $id_page)) {

                $row_id = 0;
                $sub_fields = DCE_Helper::get_acf_repeater_fields($settings['dce_acf_repeater']);
                if (!empty($sub_fields)) {
                    while (have_rows($settings['dce_acf_repeater'], $id_page)) {
                        the_row();
                        $row_id++;
                        if ($settings['dce_acf_repeater_mode'] == 'template') {
                            //echo 'xx';
                            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                $inlinecss = 'inlinecss="true"';
                            } else {
                                $inlinecss = '';
                            }

                            $idtemplate = $settings['dce_acf_repeater_template'];
                            //
                            //var_dump($id_page);
                            $values[$row_id]['template'] = do_shortcode('[dce-elementor-template id="' . $idtemplate . '" ' . $inlinecss . ']');
                            //
                            //var_dump($idtemplate);
                        }


                        foreach ($sub_fields as $key => $acfitem) {
                            $value = get_sub_field($key);
                            $values[$row_id][$key] = $value;
                            $values[$row_id]['id'] = $row_id;
                        }
                    }
                }



                $repeater_count = $row_id;
                $class = '  class="dce-acf-repeater dce-acf-repeater-' . $settings['dce_acf_repeater_format'] . ($settings['dce_acf_repeater_format'] == 'grid' ? ' equalHMRWrap' : '') . ($settings['dce_acf_repeater_format'] == 'slider_carousel' ? ' swiper-container swiper-container-' . $settings['direction_slider'] : '') . '"';
                switch ($settings['dce_acf_repeater_format']) {
                    case 'list':
                        echo '<' . $settings['dce_acf_repeater_list'] . $class . '>';
                        break;
                    case 'grid':
                    case 'masonry':
                        echo '<div' . $class . '>';
                        break;
                    case 'slider_carousel':
                        echo '<div' . $class . '><div class="swiper-wrapper">';
                        break;
                    case 'table':
                        echo '<table' . $class . '>';
                        echo '<thead>';
                        if ($settings['dce_acf_repeater_thead']) {
                            if ($settings['dce_acf_repeater_mode'] == 'repeater') {
                                $fields = $settings['dce_acf_repeater_fields_' . $settings['dce_acf_repeater']];
                                if (!empty($fields)) {
                                    foreach ($fields as $key => $acfitem) {
                                        echo '<th>';
                                        if ($acfitem['dce_acf_repeater_label_tag']) {
                                                echo '<' . $acfitem['dce_acf_repeater_label_tag'] . '>';
                                        }
                                        echo $acfitem['dce_views_select_field_label'];
                                        if ($acfitem['dce_acf_repeater_label_tag']) {
                                                echo '</' . $acfitem['dce_acf_repeater_label_tag'] . '>';
                                        }
                                        echo '</th>';
                                    }
                                }
                            } else {
                                if (!empty($sub_fields)) {
                                    foreach ($sub_fields as $key => $acfitem) {
                                        echo '<th>' . $acfitem['title'] . '</th>';
                                    }
                                }
                            }
                        }
                        echo '</thead>';
                }

                $paginations = $this->get_pagination($settings['dce_acf_repeater_pagination'], $repeater_count);
                foreach ($values as $row_id => $row_fields) {
                    if (empty($paginations) || in_array($row_id, $paginations)) {

                        switch ($settings['dce_acf_repeater_format']) {
                            case 'list':
                                echo '<li>';
                                break;
                            case 'grid':
                                echo '<div class="dce-acf-repeater-item equalHMR">';
                                break;
                            case 'masonry':
                                echo '<div class="dce-acf-repeater-item">';
                                break;
                            case 'slider_carousel':
                                echo '<div class="dce-acf-repeater-item swiper-slide">';
                                break;
                            case 'table':
                                echo '<tr>';
                        }

                        switch ($settings['dce_acf_repeater_mode']) {
                            case 'repeater':
                                $fields = $settings['dce_acf_repeater_fields_' . $settings['dce_acf_repeater']];
                                if (!empty($fields)) {
                                    foreach ($fields as $key => $acfitem) {
                                        if ($acfitem['dce_acf_repeater_field_show']) {
                                            $value = $row_fields[$acfitem['dce_acf_repeater_field_name']];

                                            if (!empty($value)) {
                                                $field_settings = DCE_Helper::get_acf_field_settings($acfitem['dce_acf_repeater_field_name']);
                                                //var_dump($field_settings);
                                                $field_type = $field_settings['type'];
                                                //var_dump($field_type);
                                                //i base al tipo elaboro il dato per generare il giusto tag

                                                switch ($field_type) {

                                                    case 'url':

                                                        break;
                                                    case 'wysiwyg':
                                                        $value = wpautop($value);
                                                        break;
                                                    case 'date_time_picker':
                                                    case 'date_picker':

                                                        break;
                                                    case 'image':
                                                        $imageAlt = '';

                                                        if (is_string($value)) {
                                                            $imageSrc = $value;
                                                        } else if (is_numeric($value)) {
                                                            $imageSrc = Group_Control_Image_Size::get_attachment_image_src($value, 'imgsize', $settings);
                                                            $imageAlt = get_post_meta($value, '_wp_attachment_image_alt', TRUE);
                                                        } else if (is_array($value)) {
                                                            $imageSrc = Group_Control_Image_Size::get_attachment_image_src($value['ID'], 'imgsize', $settings);
                                                            $imageAlt = $value['alt'];
                                                        }
                                                        $value = '<img src="' . $imageSrc . '" alt="' . $imageAlt . '" />';

                                                        break;

                                                    case 'text':
                                                    case 'textarea':
                                                    default:
                                                }
                                            }
                                            if( !empty($row_fields[$acfitem['dce_acf_repeater_acfield_link']]) && !empty($acfitem['dce_acf_repeater_enable_link']) ){
                                                //var_dump($row_fields[$acfitem['dce_acf_repeater_acfield_link']]);
                                                //echo DCE_Helper::get_acf_field_settings($acfitem['dce_acf_repeater_acfield_link']);
                                                $targetLink = '';
                                                if (!empty($acfitem['dce_acf_repeater_target_link'])) {
                                                    $targetLink = ' target="_blank"';
                                                }
                                                $value = '<a href="'.$row_fields[$acfitem['dce_acf_repeater_acfield_link']].'"'.$targetLink.'>'.$value.'</a>';
                                            }
                                            
                                            switch ($settings['dce_acf_repeater_format']) {
                                                case 'grid':
                                                    if ($settings['dce_acf_repeater_grid_label'] && $acfitem['dce_views_select_field_label'] && $value) {
                                                        $label = '';
                                                        if ($acfitem['dce_acf_repeater_label_tag']) {
                                                            $label .= '<' . $acfitem['dce_acf_repeater_label_tag'] . '>';
                                                        }
                                                        $label .= $acfitem['dce_views_select_field_label'];
                                                        if ($acfitem['dce_acf_repeater_label_tag']) {
                                                            $label .= '</' . $acfitem['dce_acf_repeater_label_tag'] . '>';
                                                        }
                                                        $value = $label . $value;
                                                    }
                                                    break;
                                            }

                                            if ($acfitem['dce_acf_repeater_field_tag']) {
                                                $value = '<' . $acfitem['dce_acf_repeater_field_tag'] . ' class="repeater-item elementor-repeater-item-' . $acfitem['_id'] . '">' . $value . '</' . $acfitem['dce_acf_repeater_field_tag'] . '>';
                                            }

                                            switch ($settings['dce_acf_repeater_format']) {
                                                case 'table':
                                                    echo '<td>';
                                                    break;
                                            }
                                            echo $value;
                                            switch ($settings['dce_acf_repeater_format']) {
                                                case 'table':
                                                    echo '</td>';
                                            }
                                        }
                                    }
                                }
                                break;
                            case 'html':
                                $text = $settings['dce_acf_repeater_html'];
                                //var_dump($text);
                                
                                echo \DynamicContentForElementor\DCE_Tokens::replace_var_tokens($text, 'ROW', $row_fields);
                                                                
                                break;
                            case 'template':
                                echo $row_fields['template'];
                                break;
                        }

                        switch ($settings['dce_acf_repeater_format']) {
                            case '':
                                if ($row_id < $repeater_count) {
                                    echo $settings['dce_acf_repeater_separator'];
                                }
                                break;
                            case 'list':
                                echo '</li>';
                                break;
                            case 'grid':
                            case 'masonry':
                            case 'slider_carousel':
                                echo '</div>';
                                break;
                            case 'table':
                                echo '</tr>';
                        }

                    }
                }

                switch ($settings['dce_acf_repeater_format']) {
                    case 'list':
                        echo '</' . $settings['dce_acf_repeater_list'] . '>';
                        break;
                    case 'grid':
                    case 'masonry':
                        echo '</div>';
                        break;
                    case 'slider_carousel':
                        echo '</div></div>';
                        break;
                    case 'table':
                        echo '<tfooter></tfooter>';
                        echo '</table>';
                }

                if ($settings['dce_acf_repeater_format'] == 'slider_carousel') {

                    // NOTA: la paginazione e la navigazione per lo swiper è fuori dal suo contenitore per poter spostare gli elementi a mio piacimento, visto che il contenitore è in overflow: hidden, e se fossero all'interno (come di default) si nasconderebbero fuori dall'area.

                    if ($settings['usePagination']) {
                        // Add Pagination
                        echo '<div class="swiper-container-' . $settings['direction_slider'] . '"><div class="swiper-pagination pagination-' . $this->get_id() . '"></div></div>';
                    }
                    if ($settings['useNavigation']) {
                        // Add Arrows

                        echo '<div class="swiper-button-prev prev-' . $this->get_id() . '"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        width="85.039px" height="85.039px" viewBox="378.426 255.12 85.039 85.039" enable-background="new 378.426 255.12 85.039 85.039"
                        xml:space="preserve">
                        <line fill="none" stroke="#000000" stroke-width="1.3845" stroke-dasharray="0,0" stroke-miterlimit="10" x1="382.456" y1="298.077" x2="458.375" y2="298.077"/>
                        <polyline fill="none" stroke="#000000" stroke-width="1.3845" stroke-dasharray="0,0" stroke-miterlimit="10" points="416.287,331.909 382.456,298.077 
                        416.287,264.245 "/>
                        </svg></div>';

                        echo '<div class="swiper-button-next next-' . $this->get_id() . '"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        width="85.039px" height="85.039px" viewBox="378.426 255.12 85.039 85.039" enable-background="new 378.426 255.12 85.039 85.039"
                        xml:space="preserve">
                        <line fill="none" stroke="#000000" stroke-width="1.3845" stroke-miterlimit="10" x1="458.375" y1="298.077" x2="382.456" y2="298.077"/>
                        <polyline fill="none" stroke="#000000" stroke-width="1.3845" stroke-miterlimit="10" points="424.543,264.245 458.375,298.077 
                        424.543,331.909 "/>
                        </svg></div>';
                    }
                }// end if slider_carousel
            }
        } else {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                _e('Please select an ACF Repeater field', 'dynamic-content-for-elementor');
            }
        }
    }

    public function get_pagination($pages, $count = 0) {
        $pages = DCE_Helper::str_to_array(',', $pages);
        $ret = array();
        if (!empty($pages)) {
            foreach ($pages as $key => $value) {
                switch ($value) {
                    case 'first':
                        $ret[] = 1;
                        break;
                    case 'last':
                        $ret[] = $count;
                        break;
                    default:
                        $range = explode('-', $value, 2);
                        if (count($range) == 2) {
                            $start = intval(reset($range));
                            $end = intval(end($range));
                            if ($start <= $end) {
                                while ($start <= $end) {
                                    $ret[] = $start;
                                    $start++;
                                }
                            }
                        } else {
                            $ret[] = intval($value);
                        }
                }
            }
        }
        return $ret;
    }

}
