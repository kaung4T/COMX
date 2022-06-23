<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;

use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\Controls\DCE_Group_Control_Transform_Element;
use DynamicContentForElementor\Controls\DCE_Group_Control_Filters_CSS;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Dual View
 *
 * Elementor widget for Dynamic Content for Elementor
 *
 */
class DCE_Widget_DualView extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-dualView';
    }

    static public function get_position() {
        return 6;
    }

    static public function is_enabled() {
        return false;
    }

    public function get_title() {
        return __('Dual View', 'dynamic-content-for-elementor');
    }

    public function get_icon() {
        return 'icon-dyn-dualview';
    }

    public function get_script_depends() {
        return ['jquery', 'imagesloaded', 'dce-charming-lib', 'velocity', 'dce-dualView'];
    }

    /* public function get_style_depends() {
      return [ 'dce-modalWindow' ];
      } */

    protected function _register_controls() {
        // ------------------------------------------------------------------------------------ [SECTION]
        $this->start_controls_section(
                'section_cpt',
                [
                    'label' => __('Post Type Query', 'dynamic-content-for-elementor'),
                ]
        );

        $this->add_control(
                'query_type',
                [
                    'label' => __('Query Type', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'get_cpt' => [
                            'title' => 'Custom Post Type',
                            'icon' => 'fa fa-files-o',
                        ],
                        'dynamic_mode' => [
                            'title' => 'Dynamic',
                            'icon' => 'fa fa-cogs',
                        ],
                        'acf_relations' => [
                            'title' => 'ACF Relations',
                            'icon' => 'fa fa-american-sign-language-interpreting',
                        ],
                        'specific_posts' => [
                            'title' => 'From Specific Post',
                            'icon' => 'fa fa-list-ul',
                        ]
                    ],
                    'default' => 'get_cpt',
                ]
        );
        // --------------------------------- [ Specific Pages ]
        $types = DCE_Helper::get_post_types();
        foreach ($types as $t => $tname) {
            $data_page = DCE_Helper::get_posts_by_type($t, get_the_ID(), false);
            if (count($data_page) > 0) {

                $object_t = get_post_type_object($t)->labels;
                $label_t = $object_t->name;

                $this->add_control(
                        'specific_pages' . $t,
                        [
                            'label' => __($label_t, 'dynamic-content-for-elementor'),
                            'type' => Controls_Manager::SELECT2,
                            //'options' => DCE_Helper::get_pages(),
                            'options' => $data_page,
                            //'groups' => DCE_Helper::get_all_posts(get_the_ID(), true),
                            'multiple' => true,
                            'label_block' => true,
                            'condition' => [
                                'query_type' => 'specific_posts',
                            ],
                        ]
                );
            }
        }

        // --------------------------------- [ ACF relations ]
        $this->add_control(
                'acf_relationship',
                [
                    'label' => __('Relations (ACF)', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    //'options' => get_post_taxonomies( $post->ID ),
                    'options' => DCE_Helper::get_acf_field_relations(),
                    'default' => '0',
                    'condition' => [
                        'query_type' => 'acf_relations',
                    ],
                ]
        );
        // --------------------------------- [ Custom Post Type ]

        $this->add_control(
                'post_type',
                [
                    'label' => __('Post Type', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT2,
                    'options' => DCE_Helper::get_post_types(),
                    'multiple' => true,
                    'label_block' => true,
                    'default' => 'post',
                    'condition' => [
                        'query_type' => 'get_cpt',
                    ],
                ]
        );
        $this->add_control(
                'exclude_io',
                [
                    'label' => __('Exclude myself', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                    'label_off' => __('No', 'dynamic-content-for-elementor'),
                    'return_value' => 'yes',
                    'condition' => [
                    //'post_type' => 'page',
                    ]
                ]
        );
        $this->add_control(
                'exclude_page_parent',
                [
                    'label' => __('Exclude page parent', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                    'label_off' => __('No', 'dynamic-content-for-elementor'),
                    'return_value' => 'yes',
                    'condition' => [
                    //'post_type' => 'page',
                    ]
                ]
        );
        $this->add_control(
                'parentpage_options', [
            'label' => __('Parent PAGE options', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'page_parent',
                [
                    'label' => __('Enabled ParentChild Options', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                    'label_off' => __('No', 'dynamic-content-for-elementor'),
                    'return_value' => 'yes',
                ]
        );
        $this->add_control(
                'parent_source',
                [
                    'label' => __('Get from Parent', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __('Same', 'dynamic-content-for-elementor'),
                    'label_off' => __('other', 'dynamic-content-for-elementor'),
                    'return_value' => 'yes',
                    'condition' => [
                        'page_parent' => 'yes',
                    ],
                ]
        );
        $this->add_control(
                'child_source',
                [
                    'label' => __('Get from Children', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __('Same', 'dynamic-content-for-elementor'),
                    'label_off' => __('other', 'dynamic-content-for-elementor'),
                    'return_value' => 'yes',
                    'condition' => [
                        'page_parent' => 'yes',
                        'parent_source' => ''
                    ],
                ]
        );
        $this->add_control(
                'specific_page_parent',
                [
                    'label' => __('Get from custom', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => DCE_Helper::get_pages(),
                    'default' => '0',
                    'condition' => [
                        'page_parent' => 'yes',
                        'parent_source' => '',
                        'child_source' => ''
                    ],
                ]
        );
        $this->add_control(
                'hr_end_pageparent',
                [
                    'type' => Controls_Manager::DIVIDER,
                    'style' => 'thick',
                ]
        );
        $this->add_control(
                'taxonomy', [
            'label' => __('Filter Taxonomy', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            //'options' => get_post_taxonomies( $post->ID ),
            'options' => ['' => __('None', 'dynamic-content-for-elementor')] + get_taxonomies(array('public' => true)),
            'default' => '',
            'condition' => [
                'query_type' => ['get_cpt'],
            ],
                ]
        );
        /* $this->add_control(
          'taxonomy',
          [
          'label' => __( 'Taxonomy', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::SELECT2,
          'options' => [ '' => __('None','dynamic-content-for-elementor') ] + get_taxonomies( array( 'public' => true ) ),
          'multiple' => true,
          'label_block' => true,
          'default' => '',
          'condition' => [
          'query_type' => ['get_cpt','dynamic_mode'],
          ],
          ]
          ); */
        $this->add_control(
                'category', [
            'label' => __('Terms ID', 'dynamic-content-for-elementor'),
            'description' => __('Comma separated list of category ids', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'condition' => [
                'taxonomy!' => '',
                'query_type' => 'get_cpt',
            ],
                ]
        );
        /* $this->add_control(
          'exclude_posts', [
          'label' => __('Exclude posts', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SELECT2,
          'separator' => 'before',
          'multiple' => true,
          'options' => DCE_Helper::get_all_posts(),
          //'groups' => DCE_Helper::get_all_posts(get_the_ID(), true),
          'default' => '',
          'condition' => [
          'query_type' => ['get_cpt', 'dynamic_mode'],
          ],
          ]
          ); */
        $this->add_control(
                'exclude_posts',
                [
                    'label' => __('Exclude posts', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Post Title', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'separator' => 'before',
                    'multiple' => true,
                    'condition' => [
                        'query_type' => ['get_cpt', 'dynamic_mode'],
                    ],
                ]
        );
        $this->add_control(
                'num_posts', [
            'label' => __('Number of Post', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => '-1',
            'separator' => 'before',
            'condition' => [
                'query_type' => ['get_cpt', 'dynamic_mode'],
            ],
                ]
        );
        $this->add_control(
                'post_offset', [
            'label' => __('Post Offset', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => '0',
            'condition' => [
                'query_type' => ['get_cpt', 'dynamic_mode'],
            ],
                ]
        );
        $this->add_control(
                'orderby', [
            'label' => __('Order By', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => DCE_Helper::get_post_orderby_options(),
            'default' => 'date',
            'condition' => [
                'query_type' => ['get_cpt', 'dynamic_mode'],
            ],
                ]
        );
        $this->add_control(
                'order', [
            'label' => __('Order', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'asc' => 'Ascending',
                'desc' => 'Descending'
            ],
            'default' => 'desc',
            'condition' => [
                'query_type' => ['get_cpt', 'dynamic_mode'],
            ],
                ]
        );
        $this->end_controls_section();


        // ------------------------------------------------------------------------------------ [ SECTION Image ]
        $this->start_controls_section(
                'section_template',
                [
                    'label' => __('Template', 'dynamic-content-for-elementor'),
                ]
        );
        /*$this->add_control(
                'ajax_page_template',
                [
                    'label' => __('Select Template', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    //'options' => get_post_taxonomies( $post->ID ),
                    'options' => DCE_Helper::get_all_template(false),
                    'default' => '0',
                    'frontend_available' => true,
                ]
        );*/
        $this->add_control(
                'ajax_page_template',
                [
                    'label' => __('Select Template', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Template Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'object_type' => 'elementor_library',
                    'frontend_available' => true,
                ]
        );
        $this->end_controls_section();
        // ------------------------------------------------------------------------------------ [ SECTION Image ]
        $this->start_controls_section(
                'section_image', [
            'label' => __('Image', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );
        $this->add_group_control(
                Group_Control_Image_Size::get_type(), [
            'name' => 'imagesize',
            'label' => __('Image Size', 'dynamic-content-for-elementor'),
            'frontend_available' => true,
                ]
        );
        $this->add_responsive_control(
                'size_image', [
            'label' => __('Size (%)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            /* 'default' => [
              'size' => 100,
              'unit' => '%',
              ],
              'tablet_default' => [
              'unit' => '%',
              ],
              'mobile_default' => [
              'unit' => '%',
              ], */
            'size_units' => ['%', 'px'],
            'range' => [
                '%' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1
                ],
                'px' => [
                    'min' => 1,
                    'max' => 300,
                    'step' => 1
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cd-item img' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
                ]
        );

        // overlay...
        $this->add_control(
                'use_overlay', [
            'label' => __('Overlay', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
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
            'default' => '0',
                ]
        );
        // overlay color ...
        $this->add_control(
                'overlay_color', [
            'label' => __('Overlay Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'scheme' => [
                'type' => Scheme_Color::get_type(),
                'value' => Scheme_Color::COLOR_1,
            ],
            'default' => 'rgba(0,0,0,0.4)',
            'selectors' => [
                '{{WRAPPER}} .dce-overlay' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'use_overlay' => '1',
            ]
                ]
        );
        $this->end_controls_section();


        //////////////////////////////////////////////////////////////////////////// [ SECTION Grid ]
        $this->start_controls_section(
                'section_grid', [
            'label' => __('Grid', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );
        /* $this->add_responsive_control(
          'num_col',
          [
          'label' => __( 'Zoom Level', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::SLIDER,
          'default' => [
          'size' => 4,
          ],
          'range' => [
          'px' => [
          'min' => 1,
          'max' => 8,
          ],
          ],
          ]
          ); */

        $this->add_responsive_control(
                'columns_grid', [
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
                '{{WRAPPER}} .dce-post-item' => 'width: calc( 100% / {{VALUE}} );',
                '{{WRAPPER}} .dce-post-item.equalHMR' => 'flex: 0 1 calc( 100% / {{VALUE}} );',
            ],
                ]
        );
        /*
          flex-grow: 0;
          flex-shrink: 1;
          flex-basis: calc(33.3333%)
         */
        $this->add_control(
                'fitrow_enable', [
            'label' => __('Fit Row', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
                //'frontend_available' => true,
                ]
        );


        $this->add_control(
                'sameheight_enable', [
            'label' => __('Same Height', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
                //'frontend_available' => true,
                ]
        );
        $this->add_control(
                'flex_grow', [
            'label' => __('Flex grow', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
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
                ]
        );
        $this->add_responsive_control(
                'flexgrid_mode', [
            'label' => __('Alignment grid', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'flex-start',
            'tablet_default' => '3',
            'mobile_default' => '1',
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
            ],
            'default' => 'top',
            'selectors' => [
                '{{WRAPPER}} .equalHMR' => 'align-self: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'filters_enable', [
            'label' => __('Show Filters', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
            'frontend_available' => true,
                ]
        );
        $this->end_controls_section();


        // ------------------------------------------------------------------------- [SECTION Hover Effects]
        $this->start_controls_section(
                'section_hover_effect', [
            'label' => __('Hover effect', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );
        $this->add_responsive_control(
                'hover_opacity', [
            'label' => __('Hover Opacity (%)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 1,
            ],
            'range' => [
                'px' => [
                    'max' => 1,
                    'min' => 0.10,
                    'step' => 0.01,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-post-item:hover' => 'opacity: {{SIZE}};',
            ],
                ]
        );
        $this->add_group_control(
                DCE_Group_Control_Filters_CSS::get_type(),
                [
                    'name' => 'hover_filters_image',
                    'label' => 'Filters image',
                    'selector' => '{{WRAPPER}} .cd-item:hover img',
                ]
        );
        $this->add_control(
                'hover_animation', [
            'label' => __('Hover Animation', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HOVER_ANIMATION,
                ]
        );
        // overlay...
        $this->add_control(
                'use_overlay_hover', [
            'label' => __('Overlay', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
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
            'default' => '0'
                ]
        );
        // overlay color ...
        /* $this->add_control(
          'overlay_color_hover', [
          'label' => __('Overlay Color', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::COLOR,
          'scheme' => [
          'type' => Scheme_Color::get_type(),
          'value' => Scheme_Color::COLOR_1,
          ],
          'default' => '',
          'selectors' => [
          '{{WRAPPER}} .dce-overlay_hover' => 'background-color: {{VALUE}};',
          ],
          'condition' => [
          'use_overlay_hover' => '1',
          ],
          ]
          ); */
        $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'overlay_color_hover',
                    'label' => __('Background', 'dynamic-content-for-elementor'),
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .dce-overlay_hover',
                    'condition' => [
                        'use_overlay_hover' => '1',
                    ]
                ]
        );

        $this->end_controls_section();


        ////////////////////////////////////////////////////////////////////////////////////////// STYLE TAB
        // -------------------------------------------------------------------------- [ section Style - Image ]

        $this->start_controls_section(
                'section_style_image', [
            'label' => __('Image', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        $this->add_responsive_control(
                'img_space', [
            'label' => __('Space', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => -100,
                    'step' => 1,
                ],
                '%' => [
                    'max' => 100,
                    'min' => -100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cd-item img' => 'margin-bottom: {{SIZE}}{{UNIT}};'
            ],
                ]
        );
        $this->add_control(
                'popover-toggle',
                [
                    'label' => __('Transforms image', 'plugin-name'),
                    'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                    'label_off' => __('No', 'dynamic-content-for-elementor'),
                    'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                    'return_value' => 'yes',
                ]
        );
        $this->start_popover();

        $this->add_group_control(
                DCE_Group_Control_Transform_Element::get_type(),
                [
                    'name' => 'transform_image',
                    'label' => 'Transform image',
                    'selector' => '{{WRAPPER}} .cd-item',
                ]
        );
        $this->end_popover();
        $this->add_group_control(
                DCE_Group_Control_Filters_CSS::get_type(),
                [
                    'name' => 'filters_image',
                    'label' => 'Filters image',
                    //'selector' => '{{WRAPPER}} img, {{WRAPPER}} .dynamic-content-featuredimage-bg',
                    'selector' => '{{WRAPPER}} .cd-item img',
                ]
        );
        /* $this->add_responsive_control(
          'opacity_image', [
          'label' => __('Opacity (%)', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SLIDER,
          'default' => [
          'size' => 1,
          ],
          'range' => [
          'px' => [
          'max' => 1,
          'min' => 0.10,
          'step' => 0.01,
          ],
          ],
          'selectors' => [
          '{{WRAPPER}} .acfposts-image' => 'opacity: {{SIZE}};',
          ],
          'condition' => [
          'show_image' => '1',
          ]
          ]
          );

          $this->add_control(
          'angle_image', [
          'label' => __('Angle (deg)', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SLIDER,
          'size_units' => [ 'deg'],
          'default' => [
          'unit' => 'deg',
          'size' => 0,
          ],
          'range' => [
          'deg' => [
          'max' => 360,
          'min' => -360,
          'step' => 1,
          ],
          ],
          'selectors' => [
          '{{WRAPPER}} .acfposts-image' => '-webkit-transform: rotate({{SIZE}}deg); -moz-transform: rotate({{SIZE}}deg); -ms-transform: rotate({{SIZE}}deg); -o-transform: rotate({{SIZE}}deg); transform: rotate({{SIZE}}deg);',
          ],
          'condition' => [
          'show_image' => '1',
          ]
          ]
          ); */

        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'border_image',
            'label' => __('Image Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .acfposts-image',
            'condition' => [
                'show_image' => '1',
            ]
                ]
        );

        $this->add_control(
                'border_radius_image', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .acfposts-image, {{WRAPPER}} .dce-overlay_hover, {{WRAPPER}} .dce-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'show_image' => '1',
            ]
                ]
        );
        $this->add_control(
                'padding_image', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .acfposts-image, {{WRAPPER}} .dce-overlay_hover, {{WRAPPER}} .dce-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'show_image' => '1',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(), [
            'name' => 'box_shadow_image',
            'selector' => '{{WRAPPER}} .acfposts-image',
            'condition' => [
                'show_image' => '1',
            ]
                ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        global $global_ID;

        // ------------------------------------------
        $demoPage = get_post_meta(get_the_ID(), 'demo_id', true);
        //
        $id_page = ''; //get_the_ID();
        $type_page = '';

        global $global_ID;
        global $global_TYPE;
        global $in_the_loop;
        global $global_is;
        //
        global $in_the_loop;
        $in_the_loop = true;
        //
        if (!empty($demoPage)) {
            $id_page = $demoPage;
            $type_page = get_post_type($demoPage);
            //echo 'DEMO ...';
        } else if (!empty($global_ID)) {
            $id_page = $global_ID;
            $type_page = get_post_type($id_page);
            //echo 'global ...';
        } else {
            $id_page = get_the_id();
            $type_page = get_post_type();
        }

        // ------------------------------------------------------------------------------------ [ SECTION QUERY ]
        $args = array();
        $taxquery = array();

        $exclude_io = array();
        $posts_excluded = array();
        if (is_singular()) {
            //echo 'ei: '.$settings['exclude_io'].' '.count($exclude_io);
            if ($settings['exclude_io'] == 'yes')
                $exclude_io = array($id_page);
        } else if (is_home() || is_archive()) {
            $exclude_io = array();
        }

        if ($settings['exclude_posts'])
            $posts_excluded = $settings['exclude_posts'];
        if ($settings['exclude_page_parent'] == 'yes') {
            $use_parent_page = array(0);
        } else {
            $use_parent_page = array();
        }
        $terms_query = 'all';
        if ($settings['category'] != '') {
            $terms_query = explode(',', $settings['category']);
        }

        if ($settings['taxonomy'] != "")
            $taxquery = array(
                array(
                    'taxonomy' => $settings['taxonomy'],
                    'field' => 'id',
                    'terms' => $terms_query
                )
            );

        if ($settings['query_type'] == 'specific_posts') {
            $types = DCE_Helper::get_post_types();
            $specific_posts = array();
            foreach ($types as $t => $tname) {
                if (isset($settings['specific_pages' . $t])) {
                    $t_array = $settings['specific_pages' . $t];
                    if (is_array($t_array) || is_object($t_array)) {
                        $specific_posts = array_merge($specific_posts, $t_array);
                    }
                }
            }
            $args = array(
                'post_type' => $types,
                'post__in' => $specific_posts,
                'order' => 'asc',
                'order_by' => 'post__in',
                'post_status' => 'publish',
            );
            //acf_relationship
        } else if ($settings['query_type'] == 'dynamic_mode') {

            $array_taxquery = [];
            $taxonomy_list = get_post_taxonomies($global_ID);
            foreach ($taxonomy_list as $tax) {

                $terms_list = wp_get_post_terms($id_page, $tax, array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all', 'hide_empty' => true));

                $lista_dei_termini = [];
                foreach ($terms_list as $term) {
                    $lista_dei_termini[] = $term->term_id;
                }
                if (count($lista_dei_termini) > 0) {
                    $array_taxquery[] = array(
                        'taxonomy' => $tax,
                        'field' => 'id',
                        'terms' => $lista_dei_termini
                    );
                }
            }
            if ('elementor_library' == $type_page)
                $type_page = 'post';
            $args = array(
                'post_type' => $type_page,
                'posts_per_page' => $settings['num_posts'],
                'offset' => $settings['post_offset'],
                'order' => $settings['order'],
                'orderby' => $settings['orderby'],
                'post__not_in' => array_merge($posts_excluded, $exclude_io),
                'post_parent__not_in' => $use_parent_page,
                'tax_query' => $array_taxquery,
                'post_status' => 'publish',
            );
            // ----------------------------------------------------------
            if ($settings['page_parent'] == 'yes') {
                //
                if ($settings['parent_source'] == 'yes') {
                    // rispetto a me-stesso prendo il post genitore
                    $args['post_parent'] = wp_get_post_parent_id($id_page);
                } else if ($settings['child_source'] == 'yes') {
                    $args['post_parent'] = $id_page;
                } else {
                    $args['post_parent'] = $settings['specific_page_parent'];
                }
            }
            // ----------------------------------------------------------
        } else if ($settings['query_type'] == 'acf_relations') {
            $relations_ids = get_field($settings['acf_relationship'], $id_page, false);
            //$relations_ids = unserialize(get_post_meta( $id_page, $settings['acf_relationship'] ));
            if (!empty($relations_ids)) {

                $relations_type = get_post_type($relations_ids[0]);
                //echo $relations_type;
                $args = array(
                    'post_type' => 'any', //$relations_type,
                    'posts_per_page' => -1,
                    'post__in' => $relations_ids,
                    'post_status' => 'publish',
                    'orderby' => 'menu_order',
                );
            }
        } else if ($settings['query_type'] == 'get_cpt') {
            $args = array(
                'post_type' => $settings['post_type'],
                'posts_per_page' => $settings['num_posts'],
                'offset' => $settings['post_offset'],
                'order' => $settings['order'],
                'orderby' => $settings['orderby'],
                'tax_query' => $taxquery,
                'post_parent__not_in' => $use_parent_page,
                'post__not_in' => array_merge($posts_excluded, $exclude_io),
                'post_status' => 'publish',
            );
            //
            if ($settings['page_parent'] == 'yes') {
                //
                if ($settings['parent_source'] == 'yes') {
                    // rispetto a me-stesso prendo il post genitore
                    $args['post_parent'] = wp_get_post_parent_id($id_page);
                } else if ($settings['child_source'] == 'yes') {
                    $args['post_parent'] = $id_page;
                } else {
                    $args['post_parent'] = $settings['specific_page_parent'];
                }
            }
        }




        // *********************************************************
        ?>
        <script type='text/javascript'>
            /* <![CDATA[ */
            var dceAjaxPath = {"ajaxurl": "<?php echo admin_url('admin-ajax.php'); ?>"};
            /* ]]> */
        </script>

        <?php
        $stringSameHeightWrap = '';
        $stringSameHeightItem = '';
        if ($settings['sameheight_enable'] == 'yes') {
            $stringSameHeightWrap = ' equalHMRWrap eqWrap';
            $stringSameHeightItem = ' equalHMR eq';
        }
        // data-speed="7"
        ?>

        <section class="dce-dualView">

        <?php
        // Output posts
        $p_query = new \WP_Query($args);
        // ////////////////////////////////////////// Query POST ///////////////////////////////////////////
        if ($p_query->have_posts()) :
            //
            $counter = 0;
            $animation_class = !empty($settings['hover_animation']) ? 'elementor-animation-' . $settings['hover_animation'] : '';
            $sonoPronto = true;
            //
            $classItemImage = '';
            //
            $original_global_ID = $id_page;
            // Start loop
            //var_dump($settings);
            ?>
                <ul class="cd-items cd-container<?php echo $stringSameHeightWrap; ?>">
                <?php
                while ($p_query->have_posts()) : $p_query->the_post();
                    $id_page = get_the_ID();
                    //
                    if (has_post_thumbnail()) {
                        $image_url = Group_Control_Image_Size::get_attachment_image_src(get_post_thumbnail_id(), 'imagesize', $settings);

                        //echo $id_page;
                        ?>

                            <li class="cd-item dce-post-item dce-post-item-<?php echo $id_page . $stringSameHeightItem; ?>">
                                <!-- <img src="/demo/img/item-2.jpg" alt="Item Preview"> -->
                                <!-- <div class="dce-acfposts_image"> -->

                                <img src="<?php echo $image_url; ?>" title="<?php echo get_the_title(); ?>" class="acfposts-image" />


                                <a href="<?php echo get_the_permalink(); ?>" class="cd-trigger">Quick View</a>
                                <!-- </div> -->
                            </li> <!-- cd-item -->
                <?php
                }
                //
                $counter++;
            endwhile;

            wp_reset_query();
            wp_reset_postdata();
            // End post check
            ?>
                </ul> <!-- cd-items -->
                    <?php
                endif;
                ?>
            <!-- <div id="cd-quick-view-<?php echo $this->get_id(); ?>" class="cd-quick-view"> -->
                <?php //qui viene caricato il contenuto dal loading ajax gestito in plugin.php {dce_dualView_ajax_action}  ?>
            <!-- </div>  cd-quick-view -->

        </section>
            <?php
            $global_ID = $original_global_ID;
        }

    }
    