<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Utils;
use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\Controls\DCE_Group_Control_Filters_CSS;
use DynamicContentForElementor\Controls\DCE_Group_Control_Transform_Element;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dynamic Content Title
 *
 * Widget ACF for Dynamic Content for Elementor
 *
 */
class DCE_Widget_Acf extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-acf';
    }

    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('ACF Fields', 'dynamic-content-for-elementor');
    }

    public function get_description() {
        return __('Add a customized field realized with Advanced Custom Fields and check its features whether it is: text, text area, select, image or embed video', 'dynamic-content-for-elementor');
    }

    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/acf-fields/';
    }

    public function get_icon() {
        return 'icon-dyn-acffields';
    }

    public function get_script_depends() {
        return ['elementor-dialog'/* , 'dce-acf_fields' */];
    }

    static public function get_position() {
        return 1;
    }

    /* public function get_style_depends() {
      return [ 'dce-acf' ];
      } */

    public function get_plugin_depends() {
        return array('acf');
    }

    protected function _register_controls() {

        // ********************************************************************************* Section BASE
        $this->start_controls_section(
                'section_content', [
            'label' => __('ACF', 'dynamic-content-for-elementor')
                ]
        );
        $this->add_control(
                'acf_field_list', [
            'label' => __('ACF Fields List', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'label_block' => true,
            //'options' => $this->get_acf_field(),
            'groups' => DCE_Helper::get_acf_fields('dyncontel-acf', true),
            'default' => 'Select the Field',
                /* 'condition' => [
                  'acf_group_list' => '',
                  ] */
                ]
        );
        $this->add_control(
                'acf_type', [
            'label' => __('ACF type of field', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'empty' => __('Empty', 'dynamic-content-for-elementor'),
                'text' => __('Text', 'dynamic-content-for-elementor'),
                'wysiwyg' => __('Wysiwyg Text Content', 'dynamic-content-for-elementor'),
                'textarea' => __('TextArea', 'dynamic-content-for-elementor'),
                'date' => __('Date', 'dynamic-content-for-elementor'),
                'number' => __('Number', 'dynamic-content-for-elementor'),
                'url' => __('Url', 'dynamic-content-for-elementor'),
                'select' => __('Select', 'dynamic-content-for-elementor'),
                'image' => __('Image', 'dynamic-content-for-elementor'),
                'video' => __('Video oembed', 'dynamic-content-for-elementor'),
            //'audio' => __( 'Audio', 'dynamic-content-for-elementor' ),
            //'file' => __( 'File', 'dynamic-content-for-elementor' ),
            //'map' => __( 'Map', 'dynamic-content-for-elementor' ),
            //'gallery' => __( 'Gallery', 'dynamic-content-for-elementor' ),
            //'terms-taxonomy' => __( 'Terms Taxonomy', 'dynamic-content-for-elementor' )
            ],
            'default' => 'text',
                ]
        );

        $this->add_control(
                'acf_dynamic', [
            'label' => __('Enable Shortcodes', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'acf_type' => ['text', 'textarea', 'wysiwyg'],
            ]
                ]
        );

        $this->add_control(
                'acf_text_before', [
            'label' => __('Text before', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
                ]
        );
        $this->add_responsive_control(
                'acf_text_before_block', [
            'label' => __('Before - Inline or Block', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('Inline', 'dynamic-content-for-elementor'),
            'label_on' => __('Block', 'dynamic-content-for-elementor'),
            'return_value' => 'block',
            'selectors' => [
                '{{WRAPPER}} .dynamic-content-for-elementor-acf span.tx-before' => 'display: {{VALUE}};',
            ],
            'condition' => [
                'acf_text_before!' => '',
            ]
                ]
        );
        $this->add_control(
                'acf_text_after', [
            'label' => __('Text after', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
                ]
        );
        $this->add_responsive_control(
                'acf_text_after_block', [
            'label' => __('After - List or Block', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('List', 'dynamic-content-for-elementor'),
            'label_on' => __('Block', 'dynamic-content-for-elementor'),
            'return_value' => 'block',
            'selectors' => [
                '{{WRAPPER}} .dynamic-content-for-elementor-acf span.tx-after' => 'display: {{VALUE}};',
            ],
            'condition' => [
                'acf_text_after!' => '',
            ]
                ]
        );
        // Capolettera
        $this->add_control(
                'drop_cap', [
            'label' => __('Drop Cap', 'elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('Off', 'elementor'),
            'label_on' => __('On', 'elementor'),
            'separator' => 'before',
            'render_type' => 'template',
            'prefix_class' => 'elementor-drop-cap-',
            'frontend_available' => true,
            'condition' => [
                'acf_type' => ['text', 'textarea'], //wysiwyg
            ]
                ]
        );
        $this->end_controls_section();
        // ********************************************************************************* Section SETTINGS
        $this->start_controls_section(
                'section_settings', [
            'label' => 'Settings',
            'condition' => [
                'acf_type' => ['text', 'wysiwyg', 'textarea', 'date', 'image', 'empty', 'select'],
            ]
                ]
        );
        $this->add_control(
                'html_tag', [
            'label' => __('HTML Tag', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
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
                'acf_type!' => 'empty',
            ]
                ]
        );


        // Link
        $this->add_control(
                'link_to', [
            'label' => __('Link to', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => __('None', 'dynamic-content-for-elementor'),
                'home' => __('Home URL', 'dynamic-content-for-elementor'),
                'post_url' => __('Post URL', 'dynamic-content-for-elementor'),
                'acf_url' => __('ACF URL', 'dynamic-content-for-elementor'),
                'custom' => __('Custom URL', 'dynamic-content-for-elementor'),
            ],
            'condition' => [
                'acf_type!' => 'empty',
            ]
                ]
        );
        $this->add_control(
                'acf_field_url', [
            'label' => __('ACF Field Url', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'groups' => DCE_Helper::get_acf_field_urlfile(true),
            //'options' => $this->get_acf_field_urlfile(),
            'default' => 'Select the Field',
            'condition' => [
                //'acf_type' => ['url', 'file'],
                'link_to' => 'acf_url',
            ]
                ]
        );
        $this->add_control(
                'acf_field_url_target', [
            'label' => __('Blank', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'condition' => [
                //'acf_type' => ['url', 'file'],
                'link_to' => 'acf_url',
            ]
                ]
        );
        $this->add_control(
                'link', [
            'label' => __('Link', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::URL,
            'placeholder' => __('http://your-link.com', 'dynamic-content-for-elementor'),
            'default' => [
                'url' => '',
            ],
            'show_label' => false,
            'condition' => [
                'acf_type!' => 'empty',
                'link_to' => 'custom',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Image_Size::get_type(), [
            'name' => 'size',
            'label' => __('Image Size', 'dynamic-content-for-elementor'),
            'default' => 'large',
            'condition' => [
                'acf_type' => ['image'],
            ]
                ]
        );
        $this->add_control(
                'use_bg', [
            'label' => __('Background', 'dynamic-content-for-elementor'),
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
            'render_type' => 'template',
            'default' => '0',
            'separator' => 'before',
            'prefix_class' => 'use-bg',
            'condition' => [
                'acf_type' => ['image'],
            ]
                ]
        );
        $this->add_control(
                'bg_position',
                [
                    'label' => __('Background position', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'top center',
                    'options' => [
                        '' => __('Default', 'dynamic-content-for-elementor'),
                        'top left' => __('Top Left', 'dynamic-content-for-elementor'),
                        'top center' => __('Top Center', 'dynamic-content-for-elementor'),
                        'top right' => __('Top Right', 'dynamic-content-for-elementor'),
                        'center left' => __('Center Left', 'dynamic-content-for-elementor'),
                        'center center' => __('Center Center', 'dynamic-content-for-elementor'),
                        'center right' => __('Center Right', 'dynamic-content-for-elementor'),
                        'bottom left' => __('Bottom Left', 'dynamic-content-for-elementor'),
                        'bottom center' => __('Bottom Center', 'dynamic-content-for-elementor'),
                        'bottom right' => __('Bottom Right', 'dynamic-content-for-elementor'),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dynamic-content-for-elementor-acfimage-bg' => 'background-position: {{VALUE}};',
                    ],
                    'condition' => [
                        'acf_type' => ['image'],
                        'use_bg' => '1',
                    ],
                ]
        );
        $this->add_responsive_control(
                'height', [
            'label' => __('Bg Minimus Height', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 200,
                'unit' => 'px',
            ],
            'tablet_default' => [
                'unit' => 'px',
            ],
            'mobile_default' => [
                'unit' => 'px',
            ],
            'size_units' => ['px', '%', 'vh'],
            'range' => [
                '%' => [
                    'min' => 1,
                    'max' => 100,
                ],
                'px' => [
                    'min' => 1,
                    'max' => 1000,
                ],
                'vh' => [
                    'min' => 1,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dynamic-content-for-elementor-acfimage-bg' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'acf_type' => ['image'],
                'use_bg' => '1',
            /* 'bg_extend' => '' */
            ],
                ]
        );
        $this->add_responsive_control(
                'bg_width', [
            'label' => __('Bg width', 'dynamic-content-for-elementor'),
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
            'size_units' => ['px', '%', 'vw'],
            'range' => [
                '%' => [
                    'min' => 1,
                    'max' => 100,
                ],
                'px' => [
                    'min' => 1,
                    'max' => 1000,
                ],
                'vh' => [
                    'min' => 1,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dynamic-content-for-elementor-acfimage-bg' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'acf_type' => ['image'],
                'use_bg' => '1',
            /* 'bg_extend' => '' */
            ],
                ]
        );
        $this->add_control(
                'bg_extend',
                [
                    'label' => __('Extend Background', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __('Show', 'dynamic-content-for-elementor'),
                    'label_off' => __('Hide', 'dynamic-content-for-elementor'),
                    'return_value' => 'yes',
                    'condition' => [
                        'use_bg' => '1',
                    ],
                    'prefix_class' => 'extendbg-',
                    'condition' => [
                        'acf_type' => ['image'],
                        'use_bg' => '1',
                    ],
                ]
        );
        $this->end_controls_section();

        // ------------------------------------------------------------ [ OVERLAY Image ]
        $this->start_controls_section(
                'section_overlay', [
            'label' => __('Overlay Image', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'acf_type' => ['image'],
            ]
                ]
        );
        $this->add_control(
                'overlay_heading',
                [
                    'label' => __('Overlay', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'acf_type' => ['text', 'image'],
                    ]
                ]
        );
        $this->add_control(
                'use_overlay',
                [
                    'label' => __('Overlay Image', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __('Show', 'dynamic-content-for-elementor'),
                    'label_off' => __('Hide', 'dynamic-content-for-elementor'),
                    'return_value' => 'yes',
                ]
        );
        $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'background_overlay',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .dce-overlay',
                    'condition' => [
                        'use_overlay' => 'yes',
                    ]
                ]
        );
        $this->end_controls_section();
        // ------------------------------------------------------------ [ SECTION Date ]
        $this->start_controls_section(
                'section_datetime', [
            'label' => __('Date time', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'acf_type' => 'date',
            ]
                ]
        );
        $this->add_control(
                'date_format', [
            'label' => __('Format date', 'dynamic-content-for-elementor'),
            'description' => '<a target="_blank" href="https://www.php.net/manual/en/function.date.php">' . __('Use standard PHP format character') . '</a>',
            'type' => Controls_Manager::TEXT,
            'default' => 'F j, Y, g:i a',
            'label_block' => true
                ]
        );
        $this->end_controls_section();
        // ------------------------------------------------------------ [ SECTION Filters Image ]
        $this->start_controls_section(
                'section_filters', [
            'label' => __('Filters Image', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'acf_type' => ['image'],
            ]
                ]
        );
        $this->add_group_control(
                DCE_Group_Control_Filters_CSS::get_type(),
                [
                    'name' => 'filters_image',
                    'label' => 'Filters image',
                    //'selector' => '{{WRAPPER}} img, {{WRAPPER}} .dynamic-content-featuredimage-bg',
                    'selector' => '{{WRAPPER}} .wrap-filters',
                ]
        );
        $this->add_control(
                'blend_mode',
                [
                    'label' => __('Blend Mode', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => __('Normal', 'elementor'),
                        'multiply' => 'Multiply',
                        'screen' => 'Screen',
                        'overlay' => 'Overlay',
                        'darken' => 'Darken',
                        'lighten' => 'Lighten',
                        'color-dodge' => 'Color Dodge',
                        'saturation' => 'Saturation',
                        'color' => 'Color',
                        'difference' => 'Difference',
                        'exclusion' => 'Exclusion',
                        'hue' => 'Hue',
                        'luminosity' => 'Luminosity',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .acf-image' => 'mix-blend-mode: {{VALUE}}',
                    ],
                    'separator' => 'none',
                ]
        );
        $this->end_controls_section();


        // ********************************************************************************* Section STYLE


        $this->start_controls_section(
                'section_style', [
            'label' => __('Style', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
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
            'prefix_class' => 'align-dce-',
            'selectors' => [
                '{{WRAPPER}}' => 'text-align: {{VALUE}};',
            ],
                /* 'condition' => [
                  'acf_type!' => 'empty',
                  ], */
                ]
        );
        $this->add_control(
                'hyphens', [
            'label' => __('Hyphens', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'prefix_class' => 'hyphens-',
            'condition' => [
                'align' => 'justify',
            ]
                ]
        );
        $this->end_controls_section();


        /* ------------------ Style Align  ------------ */
        $this->start_controls_section(
                'section_style_text', [
            'label' => __('Text', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'acf_type' => ['text', 'date', 'textarea', 'select', 'wysiwyg', 'number', 'empty'],
            ]
                ]
        );


        /* ------------------ Text TextArea Select ------------ */

        $this->add_control(
                'color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dynamic-content-for-elementor-acf .edc-acf' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'acf_type' => ['text', 'date', 'textarea', 'select', 'wysiwyg', 'number', 'empty'],
            ]
                ]
        );
        $this->add_control(
                'bg_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dynamic-content-for-elementor-acf' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'acf_type' => ['text', 'date', 'textarea', 'select', 'wysiwyg', 'number', 'empty'],
            ]
                ]
        );
        $this->add_responsive_control(
                'acf_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dynamic-content-for-elementor-acf' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'acf_space', [
            'label' => __('Space', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dynamic-content-for-elementor-acf' => 'margin-bottom: {{SIZE}}{{UNIT}};'
            ],
                ]
        );
        $this->add_responsive_control(
                'acf_shift', [
            'label' => __('Shift', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'max' => 180,
                    'min' => -180,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dynamic-content-for-elementor-acf' => 'left: {{SIZE}}{{UNIT}};'
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'typography_tx',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dynamic-content-for-elementor-acf',
            'condition' => [
                'acf_type' => ['text', 'date', 'textarea', 'select', 'wysiwyg', 'number', 'empty'],
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'text_shadow',
                    'selector' => '{{WRAPPER}} .dynamic-content-for-elementor-acf',
                ]
        );
        /* ------------------ Text Before ------------ */
        $this->add_control(
                'txbefore_heading',
                [
                    'label' => __('Text before', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'acf_text_before!' => '',
                    ]
                ]
        );
        $this->add_control(
                'tx_before_color', [
            'label' => __('Text Before Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dynamic-content-for-elementor-acf span.tx-before' => 'color: {{VALUE}};',
                '{{WRAPPER}} .dynamic-content-for-elementor-acf a span.tx-before' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'acf_text_before!' => '',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'typography_tx_before',
            'label' => __('Font Before', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dynamic-content-for-elementor-acf span.tx-before',
            'condition' => [
                'acf_text_before!' => '',
            ]
                ]
        );



        /* ------------------ Text Before ------------ */
        $this->add_control(
                'txafter_heading',
                [
                    'label' => __('Text after', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'acf_text_after!' => '',
                    ]
                ]
        );
        $this->add_control(
                'tx_after_color', [
            'label' => __('Text After Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dynamic-content-for-elementor-acf span.tx-after' => 'color: {{VALUE}};',
                '{{WRAPPER}} .dynamic-content-for-elementor-acf a span.tx-after' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'acf_text_after!' => '',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'typography_tx_after',
            'label' => __('Font After', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dynamic-content-for-elementor-acf span.tx-after',
            'condition' => [
                'acf_text_after!' => '',
            ]
                ]
        );

        $this->end_controls_section();

        // ================================== IMAGE ===================================

        $this->start_controls_section(
                'section_style_image', [
            'label' => __('Image', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'acf_type' => ['image'],
            ]
                ]
        );
        // ***************** IMAGE
        $this->add_responsive_control(
                'space', [
            'label' => __('Size (%)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'unit' => '%',
            ],
            'size_units' => ['%', 'px', 'vw'],
            'range' => [
                '%' => [
                    'min' => 1,
                    'max' => 100,
                ],
                'px' => [
                    'min' => 1,
                    'max' => 800,
                ],
                'vw' => [
                    'min' => 1,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .acf-image' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'acf_type' => ['image'],
                'bg_extend' => '',
            ]
                ]
        );
        $this->add_control(
                'force_width', [
            'label' => __('Force Width', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'prefix_class' => 'forcewidth-',
            'condition' => [
                'acf_type' => ['image'],
                'bg_extend' => '',
            ]
                ]
        );
        /* $this->add_group_control(
          DCE_Group_Control_Transform_Element::get_type(),
          [
          'name' => 'transform_image',
          'label' => 'Transform image',
          'selector' => '{{WRAPPER}} > .elementor-widget-container', //'{{WRAPPER}} .acf-image',
          'condition' => [
          'bg_extend' => '',
          ]
          ]
          ); */
        /* $this->add_responsive_control(
          'opacity', [
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
          '{{WRAPPER}} .acf-image' => 'opacity: {{SIZE}};',
          ],
          'condition' => [
          'acf_type' => ['image'],
          ]
          ]
          );
          $this->add_control(
          'angle', [
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
          // '{{WRAPPER}} img, {{WRAPPER}} .dce-overlay, {{WRAPPER}} .dce-overlay_hover, {{WRAPPER}} .dynamic-content-featuredimage-bg'
          'selectors' => [
          '{{WRAPPER}} .acf-image' => '-webkit-transform: rotate({{SIZE}}deg); -moz-transform: rotate({{SIZE}}deg); -ms-transform: rotate({{SIZE}}deg); -o-transform: rotate({{SIZE}}deg); transform: rotate({{SIZE}}deg);',
          ],
          'condition' => [
          'acf_type' => ['image'],
          ]
          ]
          ); */
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'image_border',
            'label' => __('Image Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .acf-image',
            'condition' => [
                'acf_type' => ['image'],
            ]
                ]
        );
        $this->add_control(
                'image_border_radius', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .acf-image, {{WRAPPER}} .acf-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'acf_type' => ['image'],
            ]
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(), [
            'name' => 'image_box_shadow',
            'selector' => '{{WRAPPER}} .acf-image',
            'condition' => [
                'acf_type' => ['image'],
            ]
                ]
        );
        // *********************** (???)
        $this->end_controls_section();

        // *********************************************************************************  [ Roll-Hover]
        $this->start_controls_section(
                'section_hover_style', [
            'label' => 'Roll-Hover',
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'link_to!' => 'none',
            ]
                ]
        );
        $this->add_control(
                'acf_color_hover', [
            'label' => __('Text Color Hover', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dynamic-content-for-elementor-acf a:hover .edc-acf' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'link_to!' => 'none',
            ]
                ]
        );

        $this->add_control(
                'acf_bgcolor_hover', [
            'label' => __('Background Color Hover', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dynamic-content-for-elementor-acf:hover' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'link_to!' => 'none',
            ]
                ]
        );
        $this->add_control(
                'acf_bgcolor_overlay_hover', [
            'label' => __('Background overlay Hover', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'overlay_hover_color',
                    'label' => __('Background', 'dynamic-content-for-elementor'),
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .dce-overlay_hover',
                    'separator' => 'after',
                    'condition' => [
                        'link_to!' => 'none',
                        'acf_type' => ['image'],
                    ]
                ]
        );

        // . . . . . . . . . . . . . . . .  Hover ElementorAMINATION
        $this->add_control(
                'hover_animation', [
            'label' => __('Animation', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HOVER_ANIMATION,
            'condition' => [
                'link_to!' => 'none',
                'acf_type' => ['image'],
            ]
                ]
        );
        // . . . . . . . . . . . . . . . .  Hover FILTERS
        $this->add_group_control(
                DCE_Group_Control_Filters_CSS::get_type(),
                [
                    'name' => 'filters_image_hover',
                    'label' => __('Filters', 'dynamic-content-for-elementor'),
                    //'selector' => '{{WRAPPER}} a:hover img, {{WRAPPER}} a:hover .dynamic-content-featuredimage-bg',
                    'selector' => '{{WRAPPER}} a:hover .wrap-filters',
                    'condition' => [
                        'link_to!' => 'none',
                        'acf_type' => ['image'],
                    ]
                ]
        );

        // . . . . . . . . . . . . . . . .  Hover EFFECTS
        $this->add_control(
                'hover_effects', [
            'label' => __('Effects', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '' => __('None', 'dynamic-content-for-elementor'),
                'zoom' => __('Zoom', 'dynamic-content-for-elementor'),
                'slow-zoom' => __('Slow Zoom', 'dynamic-content-for-elementor'),
            ],
            'separator' => 'before',
            'prefix_class' => 'hovereffect-',
            'condition' => [
                'link_to!' => 'none',
            ]
                ]
        );


        $this->end_controls_section();

        // ********************************************************************************* Section MEDIA: video e audio

        $this->start_controls_section(
                'section_settings_media', [
            'label' => __('Media Settings', 'dynamic-content-for-elementor'),
            'condition' => [
                'acf_type' => ['video', 'audio'],
            ]
                ]
        );
        // ****** FIELDS ******
        $this->add_control(
                'video_type', [
            'label' => __('Video Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'youtube',
            'options' => [
                'youtube' => __('YouTube', 'dynamic-content-for-elementor'),
                'vimeo' => __('Vimeo', 'dynamic-content-for-elementor'),
            //'hosted' => __( 'HTML5 Video', 'dynamic-content-for-elementor' ),
            ],
                ]
        );
        /*
          $this->add_control(
          'youtube_link', [
          'label' => __( 'Youtube Link', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::TEXT,
          'placeholder' => __( 'Enter your YouTube link', 'dynamic-content-for-elementor' ),
          'default' => 'https://www.youtube.com/watch?v=9uOETcuFjbE',
          'label_block' => true,
          'condition' => [
          'video_type' => 'youtube',
          ],
          ]
          );

          $this->add_control(
          'vimeo_link', [
          'label' => __( 'Vimeo Link', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::TEXT,
          'placeholder' => __( 'Enter your Vimeo link', 'dynamic-content-for-elementor' ),
          'default' => 'https://vimeo.com/170933924',
          'label_block' => true,
          'condition' => [
          'video_type' => 'vimeo',
          ],
          ]
          );
         */

        $this->add_control(
                'hosted_link', [
            'label' => __('Link', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => __('Enter your video link', 'dynamic-content-for-elementor'),
            'default' => '',
            'label_block' => true,
            'condition' => [
                'video_type' => 'hosted',
            ],
                ]
        );

        $this->add_control(
                'aspect_ratio', [
            'label' => __('Aspect Ratio', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'frontend_available' => true,
            'options' => [
                '169' => '16:9',
                '43' => '4:3',
                '32' => '3:2',
                'customheight' => 'Custom Height'
            ],
            'default' => '169',
            'prefix_class' => 'elementor-aspect-ratio-',
                ]
        );
        $this->add_responsive_control(
                'custom_height', [
            'label' => __('Custom Height', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 300,
                'unit' => 'px',
            ],
            'tablet_default' => [
                'unit' => 'px',
            ],
            'mobile_default' => [
                'unit' => 'px',
            ],
            'size_units' => ['px', '%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                ],
                'px' => [
                    'min' => 0,
                    'max' => 600,
                ]
            ],
            'selectors' => [
                //'{{WRAPPER}} .elementor-video-wrapper'    => 'padding-bottom: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} iframe' => 'height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'aspect_ratio' => 'customheight',
            ],
                ]
        );
        $this->add_control(
                'heading_youtube', [
            'label' => __('Video Options', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );

        // YouTube
        $this->add_control(
                'yt_autoplay', [
            'label' => __('Autoplay', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'video_type' => 'youtube',
            ],
                ]
        );
        $this->add_control(
                'yt_loop', [
            'label' => __('Loop', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'video_type' => 'youtube',
            ],
                ]
        );
        $this->add_control(
                'yt_mute', [
            'label' => __('Mute', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'video_type' => 'youtube',
            ],
                ]
        );
        $this->add_control(
                'yt_rel', [
            'label' => __('Suggested Videos', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('Hide', 'dynamic-content-for-elementor'),
            'label_on' => __('Show', 'dynamic-content-for-elementor'),
            'condition' => [
                'video_type' => 'youtube',
            ],
                ]
        );

        $this->add_control(
                'yt_controls', [
            'label' => __('Player Control', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('Hide', 'dynamic-content-for-elementor'),
            'label_on' => __('Show', 'dynamic-content-for-elementor'),
            'default' => 'yes',
            'condition' => [
                'video_type' => 'youtube',
            ],
                ]
        );

        $this->add_control(
                'yt_showinfo', [
            'label' => __('Player Title & Actions', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('Hide', 'dynamic-content-for-elementor'),
            'label_on' => __('Show', 'dynamic-content-for-elementor'),
            'default' => 'yes',
            'condition' => [
                'video_type' => 'youtube',
            ],
                ]
        );

        // Vimeo
        $this->add_control(
                'vimeo_autoplay', [
            'label' => __('Autoplay', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'video_type' => 'vimeo',
            ],
                ]
        );

        $this->add_control(
                'vimeo_loop', [
            'label' => __('Loop', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'video_type' => 'vimeo',
            ],
                ]
        );

        $this->add_control(
                'vimeo_title', [
            'label' => __('Intro Title', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('Hide', 'dynamic-content-for-elementor'),
            'label_on' => __('Show', 'dynamic-content-for-elementor'),
            'default' => 'yes',
            'condition' => [
                'video_type' => 'vimeo',
            ],
                ]
        );

        $this->add_control(
                'vimeo_portrait', [
            'label' => __('Intro Portrait', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('Hide', 'dynamic-content-for-elementor'),
            'label_on' => __('Show', 'dynamic-content-for-elementor'),
            'default' => 'yes',
            'condition' => [
                'video_type' => 'vimeo',
            ],
                ]
        );

        $this->add_control(
                'vimeo_byline', [
            'label' => __('Intro Byline', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('Hide', 'dynamic-content-for-elementor'),
            'label_on' => __('Show', 'dynamic-content-for-elementor'),
            'default' => 'yes',
            'condition' => [
                'video_type' => 'vimeo',
            ],
                ]
        );

        $this->add_control(
                'vimeo_color', [
            'label' => __('Controls Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'condition' => [
                'video_type' => 'vimeo',
            ],
                ]
        );
        $this->add_control(
                'view', [
            'label' => __('View', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HIDDEN,
            'default' => 'youtube',
                ]
        );
        $this->end_controls_section();

        // ********************************************************************************* Section VIDEO Image Overlay
        $this->start_controls_section(
                'section_image_overlay', [
            'label' => __('Image Overlay', 'dynamic-content-for-elementor'),
            'condition' => [
                'acf_type' => 'video',
            ],
            'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );

        $this->add_control(
                'show_image_overlay', [
            'label' => __('Enable Image Overlay', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('Hide', 'dynamic-content-for-elementor'),
            'label_on' => __('Show', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'image_overlay_type', [
            'label' => __('Image Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'custom',
            'options' => [
                'custom' => __('Custom', 'dynamic-content-for-elementor'),
                'acf' => __('ACF', 'dynamic-content-for-elementor'),
            ],
            'condition' => [
                'show_image_overlay' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'image_overlay_acf', [
            'label' => __('Field Image', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            //'options' => $this->get_acf_field_image(),
            'groups' => DCE_Helper::get_acf_fields('image', true),
            'default' => 'Select the Field',
            'condition' => [
                'image_overlay_type' => 'acf',
            ]
                ]
        );

        $this->add_control(
                'image_overlay', [
            'label' => __('Image', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::MEDIA,
            'default' => [
                'url' => Utils::get_placeholder_image_src(),
            ],
            'condition' => [
                'show_image_overlay' => 'yes',
                'image_overlay_type' => 'custom',
            ],
                ]
        );

        $this->add_control(
                'show_play_icon', [
            'label' => __('Play Icon', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'yes',
            'options' => [
                'yes' => __('Yes', 'dynamic-content-for-elementor'),
                'no' => __('No', 'dynamic-content-for-elementor'),
            ],
            'condition' => [
                'show_image_overlay' => 'yes',
                'image_overlay[url]!' => '',
            ],
                ]
        );

        $this->add_control(
                'lightbox', [
            'label' => __('Lightbox', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'condition' => [
                'show_image_overlay' => 'yes',
                'image_overlay[url]!' => '',
            ],
            'separator' => 'before',
                ]
        );

        $this->add_control(
                'lightbox_color', [
            'label' => __('Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '#elementor-video-modal-{{ID}}' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'show_image_overlay' => 'yes',
                'image_overlay[url]!' => '',
                'lightbox' => 'yes',
            ],
                ]
        );

        $this->add_control(
                'lightbox_content_width', [
            'label' => __('Content Width', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'units' => ['%'],
            'default' => [
                'unit' => '%',
            ],
            'range' => [
                '%' => [
                    'min' => 50,
                ],
            ],
            'selectors' => [
                '#elementor-video-modal-{{ID}} .dialog-widget-content' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'show_image_overlay' => 'yes',
                'image_overlay[url]!' => '',
                'lightbox' => 'yes',
            ],
                ]
        );

        $this->add_control(
                'lightbox_content_position', [
            'label' => __('Content Position', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'center center',
            'frontend_available' => true,
            'options' => [
                'center center' => __('Center', 'dynamic-content-for-elementor'),
                'center top' => __('Top', 'dynamic-content-for-elementor'),
            ],
            'condition' => [
                'show_image_overlay' => 'yes',
                'image_overlay[url]!' => '',
                'lightbox' => 'yes',
            ],
            'render_type' => 'none',
                ]
        );

        $this->add_control(
                'lightbox_content_animation', [
            'label' => __('Entrance Animation', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::ANIMATION,
            'default' => '',
            'frontend_available' => true,
            'label_block' => true,
            'condition' => [
                'show_image_overlay' => 'yes',
                'image_overlay[url]!' => '',
                'lightbox' => 'yes',
            ],
            'render_type' => 'none',
                ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
                'section_drop_cap',
                [
                    'label' => __('Drop Cap', 'elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'drop_cap' => 'yes',
                        'acf_type' => ['text', 'textarea'],
                    ],
                ]
        );

        $this->add_control(
                'drop_cap_view',
                [
                    'label' => __('View', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'default' => __('Default', 'elementor'),
                        'stacked' => __('Stacked', 'elementor'),
                        'framed' => __('Framed', 'elementor'),
                    ],
                    'default' => 'default',
                    'prefix_class' => 'elementor-drop-cap-view-',
                    'condition' => [
                        'drop_cap' => 'yes',
                    ],
                ]
        );

        $this->add_control(
                'drop_cap_primary_color',
                [
                    'label' => __('Primary Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}.elementor-drop-cap-view-stacked .elementor-drop-cap' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}}.elementor-drop-cap-view-framed .elementor-drop-cap, {{WRAPPER}}.elementor-drop-cap-view-default .elementor-drop-cap' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'drop_cap' => 'yes',
                    ],
                ]
        );

        $this->add_control(
                'drop_cap_secondary_color',
                [
                    'label' => __('Secondary Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}.elementor-drop-cap-view-framed .elementor-drop-cap' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}}.elementor-drop-cap-view-stacked .elementor-drop-cap' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'drop_cap_view!' => 'default',
                    ],
                ]
        );

        $this->add_control(
                'drop_cap_size',
                [
                    'label' => __('Size', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 5,
                    ],
                    'range' => [
                        'px' => [
                            'max' => 30,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-drop-cap' => 'padding: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'drop_cap_view!' => 'default',
                    ],
                ]
        );

        $this->add_control(
                'drop_cap_space',
                [
                    'label' => __('Space', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        'body:not(.rtl) {{WRAPPER}} .elementor-drop-cap' => 'margin-right: {{SIZE}}{{UNIT}};',
                        'body.rtl {{WRAPPER}} .elementor-drop-cap' => 'margin-left: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'drop_cap_border_radius',
                [
                    'label' => __('Border Radius', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['%', 'px'],
                    'default' => [
                        'unit' => '%',
                    ],
                    'range' => [
                        '%' => [
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-drop-cap' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'drop_cap_border_width', [
            'label' => __('Border Width', 'elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .elementor-drop-cap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'drop_cap_view' => 'framed',
            ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'drop_cap_typography',
                    'selector' => '{{WRAPPER}} .elementor-drop-cap-letter',
                    'exclude' => [
                        'letter_spacing',
                    ],
                    'condition' => [
                        'drop_cap' => 'yes',
                    ],
                ]
        );

        $this->end_controls_section();
        // **************************************** Section TERMS & TAXONOMY
        /* $this->start_controls_section(
          'section_settings_termtax', [
          'label' => __('Terms & Taxonomy Settings', 'dynamic-content-for-elementor'),
          'condition' => [
          'acf_type' => ['terms-taxonomy'],
          ]
          ]
          );

          $this->end_controls_section(); */


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
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;

        // ------------------------------------------
        $dce_data = DCE_Helper::dce_dynamic_data($settings['other_post_source']);
        $id_page = $dce_data['id'];
        $type_page = $dce_data['type'];
        $global_is = $dce_data['is'];
        $is_block = $dce_data['block'];
        // ------------------------------------------
        //
        //echo $id_page;
        // è probabile che sto passaggio non serve a una mazza perché (get_acffield_filtred) fa gia la stessa cosa.
        if (is_archive()) {
            if ($global_is != 'archive' && $global_is != 'user' && $global_is != 'singular') {
                $id_page = get_queried_object();
                //echo $global_is;
            }
        }

        // $idFields
        // if( $acfFields )
        // se $idFields 
        //$this->settings = $settings;
        $acfResult = "";
        $idFields = "";
        // ------------------------------------------

        $idFields = $settings['acf_field_list'];
        $typeField = $settings['acf_type'];

        $image_size = $settings['size_size'];
        $use_bg = $settings["use_bg"];

        // ------------------------------------------

        $wrap_effect_start = '<div class="mask"><div class="wrap-filters">';
        $wrap_effect_end = '</div></div>';

        // ------------------------------------------
        $overlay_block = "";
        if ($settings['use_overlay'] == 'yes') {
            $overlay_block = '<div class="dce-overlay"></div>';
        }
        // ------------------------------------------
        $overlay_hover_block = '<div class="dce-overlay_hover"></div>';
        // ------------------------------------------

        if ($typeField == 'image' || $typeField == 'image_url') {

            $imageField = DCE_Helper::get_acffield_filtred($idFields, $id_page);

            //echo $idFields.' ... '.$typeField.': '.$imageField;
            if (is_string($imageField)) {
                //echo 'url: '.$imageField;
                $typeField = 'image_url';
            } else if (is_numeric($imageField)) {
                //echo 'id: '.$imageField;
                $typeField = 'image';
            } else if (is_array($imageField)) {
                //echo 'array: '.$imageField;
                $typeField = 'image_array';
            }
        }



        //if( ! empty($global_ID) ) $id_page = $global_ID;
        //echo 'acf: '.$id_page;
        //echo get_the_ID();
        //global $post;
        //echo $typeField;
        if ($typeField == 'text' ||
                $typeField == 'textarea' ||
                $typeField == 'select' ||
                $typeField == 'wysiwyg' ||
                $typeField == 'number'
        ) {
            // se sono un TESTO .................
            //$acfResult = get_field( $idFields , $id_page);

            if ($typeField == 'select') {
                $acfResult = __(DCE_Helper::get_acffield_filtred($idFields, $id_page), 'dynamic-content-for-elementor' . '_texts');
            } else if ($typeField == 'wysiwyg') {
                $acfResult = wpautop(DCE_Helper::get_acffield_filtred($idFields, $id_page));
            } else {
                $acfResult = DCE_Helper::get_acffield_filtred($idFields, $id_page);
            }


            //$acfResult = get_post_meta($id_page, $idFields, true);
            //if($typeField == 'select' && is_array($acfResult)) $acfResult = $acfResult['label'];
            // Default ;-)
            //var_dump($acfResult);
            //var_dump($dce_data['global_id']);
            //var_dump($_GET['post']);
            $dataACFieldPost = DCE_Helper::get_acf_field_post($idFields);

            /*
              $parentID = $dataACFieldPost->post_parent;
              $field_settings = DCE_Helper::get_acf_field_settings($parentID);
             */

            //if(isset($field_settings['type'])) var_dump($field_settings['type']);


            $elibpost = isset($_GET['post']) ? $_GET['post'] : 0;
            //var_dump($field_settings['type']);
            if (\Elementor\Plugin::$instance->editor->is_edit_mode() && get_post_type($elibpost) == 'elementor_library' && (isset($field_settings['type']) && $field_settings['type'] != 'repeater'))
                if (!$acfResult && $typeField == 'text') {
                    $acfResult = 'This is a ACF text ' . $idFields;
                } else if (!$acfResult && $typeField == 'textarea') {
                    $acfResult = 'This is a textarea. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla placerat faucibus ultrices. Proin tristique augue turpis.';
                } else if (!$acfResult && $typeField == 'select') {
                    $acfResult = 'SelectField';
                } else if (!$acfResult && $typeField == 'wysiwyg') {
                    $acfResult = 'This is a wysiwyg text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla placerat faucibus ultrices. Proin tristique augue turpis. Phasellus accumsan nunc dui, eget mollis nibh fringilla at. Aliquam ante enim, mattis vel mi porttitor, efficitur dapibus turpis. Donec quis ipsum nisl. Sed elit sem, lobortis id erat et, tristique dignissim nisi. Donec egestas nunc tellus, sed vestibulum ex finibus sed.';
                } else if (!$acfResult && $typeField == 'number') {
                    $acfResult = '3';
                }
        } else if ($typeField == 'url' ||
                $typeField == 'file') {

            $acfResult = DCE_Helper::get_acffield_filtred($idFields, $id_page); //get_field( $idFields , $id_page);
            // Default ;-)
            if ($type_page == 'elementor_library')
                if ($acfResult == '')
                    $acfResult = '#';
        } else if ($typeField == 'date') {
            $acfResult = DCE_Helper::get_acffield_filtred($idFields, $id_page); //get_field( $idFields , $id_page);
            // zzz

            if ($acfResult == '') {
                $acfResult = '1972/01/12 00:00:00';
            } else {

                $dataDate = get_field_object($idFields);
                $dataDate = DCE_Helper::get_acf_field_settings($idFields);
                //var_dump($dataDate);
                $format_display = $settings['date_format'];
                if (!$format_display) {
                    $format_display = $dataDate['return_format'];
                }
                $d = \DateTime::createFromFormat($dataDate['return_format'], $acfResult);
                if ($d) {
                    $acfResult = $d->format($format_display);
                } else {
                    $timestamp = strtotime($acfResult);
                    $acfResult = date_i18n($format_display, $timestamp);
                }
            }
        } else if ($typeField == 'image') {

            // if ( empty( $imageField ) )
            // return;
            //echo $imageField.' iiii';
            // 
            $settings['html_tag'] = 'div';
            //$imageSrc = wp_get_attachment_image_src( $imageField, $image_size);
            //$imageSrcUrl = $imageSrc[0];

            $imageSrc = Group_Control_Image_Size::get_attachment_image_src($imageField, 'size', $settings);
            $imageSrcUrl = $imageSrc;

            // Default ;-)
            if ($type_page == 'elementor_library')
                if ($imageSrcUrl == '')
                    $imageSrcUrl = DCE_Helper::get_placeholder_image_src();

            if (empty($imageSrcUrl))
                return;

            /*
              if($get_featured_img == ""){
              $featured_image = $wrap_effect_start.'<img src="'.DCE_Helper::get_placeholder_image_src().'" />'.$wrap_effect_end.$overlay_block.$overlay_hover_block;
              }
             */

            if ($use_bg == 0) {

                $acfResult = '<div class="acf-image">' . $wrap_effect_start . '<img src="' . $imageSrcUrl . '" alt="' . $imageField['description'] . '" />' . $wrap_effect_end . $overlay_block . $overlay_hover_block . '</div>';
            } else {
                $bg_featured_image = '<div class="acf-image acf-bg-image">' . $wrap_effect_start . '<figure class="dynamic-content-for-elementor-acfimage-bg" style="background-image: url(' . $imageSrcUrl . '); background-repeat: no-repeat; background-size: cover;"></figure>' . $wrap_effect_end . $overlay_block . $overlay_hover_block . '</div>';
                $acfResult = $bg_featured_image;
            }

            /*
              $imageSrc = wp_get_attachment_url( $imageField['id'], $image_size );
              $acfResult = '<img src="'.$imageSrc.'" alt="'.$imageField['description'].'" />'; //$featured_image; //;
             */
        } else if ($typeField == 'image_url') {

            /* if ( empty( $imageField ) )
              return; */

            // Default ;-)
            if ($type_page == 'elementor_library')
                if ($imageField == '')
                    $imageSrcUrl = DCE_Helper::get_placeholder_image_src();

            if (empty($imageField))
                return;

            $settings['html_tag'] = 'div';
            if (is_numeric($imageField))
                $imageField = Group_Control_Image_Size::get_attachment_image_src($imageField, 'size', $settings);
            if ($use_bg == 0) {

                $acfResult = '<div class="acf-image">' . $wrap_effect_start . '<img src="' . $imageField . '" />' . $wrap_effect_end . $overlay_block . $overlay_hover_block . '</div>';
            } else {
                $bg_featured_image = '<div class="acf-image acf-bg-image">' . $wrap_effect_start . '<figure class="dynamic-content-for-elementor-acfimage-bg" style="background-image: url(' . $imageField . '); background-repeat: no-repeat; background-size: cover;"></figure>' . $wrap_effect_end . $overlay_block . $overlay_hover_block . '</div>';
                $acfResult = $bg_featured_image;
            }
        } else if ($typeField == 'image_array') {

            /* if ( empty( $imageField ) )
              return; */
            //
            //var_dump($imageField);
            //

            $settings['html_tag'] = 'div';
            //$imageAttach = wp_get_attachment_image( $imageField['ID'], $image_size);
            //$imageSrc = wp_get_attachment_image_src( $imageField['ID'], $image_size);
            $imageAlt = $imageField['alt'];
            $imageDesc = $imageField['description'];
            $imageCapt = $imageField['caption'];

            $imageSrc = Group_Control_Image_Size::get_attachment_image_src($imageField['ID'], 'size', $settings);
            $imageAttach = '<img src="' . $imageSrc . '" alt="' . $imageAlt . '" />';
            $imageSrcUrl = $imageSrc;



            // Default ;-)
            if ($type_page == 'elementor_library')
                if ($imageSrcUrl == '')
                    $imageAttach = '<img src="' . DCE_Helper::get_placeholder_image_src() . '" />';

            if (empty($imageSrcUrl))
                return;

            // echo $imageSrc[0];
            // echo $imageAlt;
            // echo $imageDesc;
            // echo $imageCapt;
            //echo $imageField['ID'];
            //echo $imageAttach;

            if ($use_bg == 0) {
                $acfResult = '<div class="acf-image">' . $wrap_effect_start . $imageAttach . $wrap_effect_end . $overlay_block . $overlay_hover_block . '</div>';
            } else {
                $bg_featured_image = '<div class="acf-image acf-bg-image">' . $wrap_effect_start . '<figure class="dynamic-content-for-elementor-acfimage-bg" style="background-image: url(' . $imageSrcUrl . '); background-repeat: no-repeat; background-size: cover;"></figure>' . $wrap_effect_end . $overlay_block . $overlay_hover_block . '</div>';
                $acfResult = $bg_featured_image;
            }
        } else if ($typeField == 'video') {
            $videoField = DCE_Helper::get_acffield_filtred($idFields, $id_page); //get_field( $idFields , $id_page, false);
            // Default ;-)
            if ($type_page == 'elementor_library')
                if ($videoField == '')
                    $videoField = 'https://www.youtube.com/watch?v=9uOETcuFjbE';
            //$videoField = get_post_meta( $id_page, $idFields, true );
            $params = [];

            //$youtube_video_url = get_field( $idFields, $id_page, false );
            //echo $youtube_video_url;
            if (empty($videoField))
                return;

            add_filter('oembed_result', [$this, 'filter_oembed_result'], 50, 3);

            $video_link = 'youtube' === $settings['video_type'] ? $videoField : $videoField;

            if (empty($video_link))
                return;

            $video_html = wp_oembed_get($video_link, wp_embed_defaults());

            remove_filter('oembed_result', [$this, 'filter_oembed_result'], 50);

            if (!$video_html) {
                //echo $video_link.' xxxx '.' - '.$settings['youtube_link'].' * '.$videoField;
                echo $video_link;
                return;
            }

            $this->add_render_attribute('video-wrapper', 'class', 'elementor-wrapper');

            if (!$settings['lightbox']) {
                $this->add_render_attribute('video-wrapper', 'class', 'elementor-video-wrapper');
            }

            $this->add_render_attribute('video-wrapper', 'class', 'elementor-open-' . ( $settings['lightbox'] ? 'lightbox' : 'inline' ));
            ?>
            <div <?php echo $this->get_render_attribute_string('video-wrapper'); ?>>
            <?php
            echo $video_html;

            if ($this->has_image_overlay()) {
                $this->add_render_attribute('image-overlay', 'class', 'elementor-custom-embed-image-overlay');

                if (!$settings['lightbox']) {
                    if ($settings['image_overlay_type'] == 'custom') {
                        echo 'sono custom';
                        $this->add_render_attribute('image-overlay', 'style', 'background-image: url(' . $settings['image_overlay']['url'] . ');');
                    } else if ($settings['image_overlay_type'] == 'acf') {
                        echo 'sono acf image';
                        //$immagine_acf_overlay = get_field( $settings['image_overlay_acf'], $id_page);
                        $immagine_acf_overlay = DCE_Helper::get_acffield_filtred($settings['image_overlay_acf'], $id_page);
                        if (is_string($immagine_acf_overlay)) {
                            $immagine_acf_overlay = $immagine_acf_overlay;
                        } else if (is_numeric($immagine_acf_overlay)) {
                            $imageSrc = wp_get_attachment_image_src($immagine_acf_overlay, 'full');
                            $imageSrcUrl = $imageSrc[0];
                            $immagine_acf_overlay = $imageSrcUrl;
                        } else if (is_array($immagine_acf_overlay)) {
                            $imageSrc = wp_get_attachment_image_src($immagine_acf_overlay['ID'], 'full');
                            $imageSrcUrl = $imageSrc[0];
                            $immagine_acf_overlay = $imageSrcUrl;
                        }
                        if ($immagine_acf_overlay == '')
                            $immagine_acf_overlay = DCE_Helper::get_placeholder_image_src();

                        //$immagine_acf_overlay = get_post_meta( $id_page, $settings['image_overlay_acf'], true );
                        $this->add_render_attribute('image-overlay', 'style', 'background-image: url(' . $immagine_acf_overlay . ');');
                    }
                }
                ?>
                    <div <?php echo $this->get_render_attribute_string('image-overlay'); ?>>

                    <?php if ($settings['lightbox']) : ?>
                            <img src="<?php echo $settings['image_overlay']['url']; ?>">
                    <?php endif; ?>
                    <?php if ('yes' === $settings['show_play_icon']) : ?>
                            <div class="elementor-custom-embed-play">
                                <i class="fa fa-play-circle"></i>
                            </div>
                    <?php endif; ?>
                    </div>
                    <?php } ?>
            </div>

                    <?php
                } else if ($typeField == 'empty') {
                    $acfResult = '';
                }

                switch ($settings['link_to']) {
                    case 'custom' :
                        if (!empty($settings['link']['url'])) {
                            $link = esc_url($settings['link']['url']);
                            $target = $settings['link']['is_external'] ? ' target="_blank"' : '';
                        } else {
                            $link = false;
                        }
                        break;

                    case 'acf_url' :
                        //echo get_field( $settings['acf_field_url'] , $id_page);
                        if (!empty($settings['acf_field_url'])) {
                            $link = esc_url(DCE_Helper::get_acffield_filtred($settings['acf_field_url'], $id_page));
                            //$link = get_post_meta( $id_page, $settings['acf_field_url'], true );      
                            $target = $settings['acf_field_url_target'] ? ' target="_blank"' : '';
                        } else {
                            $link = false;
                        }
                        break;
                    case 'post_url' :
                        $link = esc_url(get_permalink($id_page));
                        $target = '';
                        break;

                    case 'home' :
                        $link = esc_url(get_home_url());
                        $target = '';
                        break;

                    case 'none' :
                    default:
                        $link = false;
                        $target = '';
                        break;
                }

                $html = '';

                $animation_class = !empty($settings['hover_animation']) ? 'elementor-animation-' . $settings['hover_animation'] : '';
                //echo $settings['acf_field_url'];
                // ------------------------
                if ($acfResult != '' || $typeField == 'empty') {

                    if ($settings['acf_dynamic']) {
                        //$acfResult = do_shortcode($acfResult); // if text contain an extra shortcode
                        $acfResult = DCE_Helper::get_dynamic_value($acfResult); // if text contain tokens
                    }

                    if ($typeField != 'empty') {
                        $acfResult = '<div class="edc-acf">' . $acfResult . '</div>';
                    }
                    $tagField = 'div';
                    if ($settings['html_tag']) {
                        $tagField = $settings['html_tag'];
                    }
                    $html = '<'.$tagField.' class="dynamic-content-for-elementor-acf '.$animation_class.'">';
                    if ($settings['acf_text_before'] != "") {
                        $acfResult = '<span class="tx-before">' . __($settings['acf_text_before'], 'dynamic-content-for-elementor' . '_texts') . '</span>' . $acfResult;
                    }
                    if ($settings['acf_text_after'] != "") {
                        $acfResult = $acfResult . '<span class="tx-after">' . __($settings['acf_text_after'], 'dynamic-content-for-elementor' . '_texts') . '</span>';
                    }
                    if ($link) {
                        //echo $link;
                        $html .= '<a href="'.$link.'"'.$target.'>'.$acfResult.'</a>';
                    } else {
                        $html .= $acfResult;
                    }
                    $html .= '</' . $tagField . '>';
                }
                // ------------------------
                echo $html;
                //
                /* if( $settings['data_source'] == '' ){
                  $global_ID = $original_global_ID;
                  } */
            }

            protected function _content_template() {
                
            }

            // ----- video -------
            /* public function render_plain_content() {
              $settings = $this->get_active_settings();

              echo 'youtube' === $settings['video_type'] ? $settings['youtube_link'] : $settings['vimeo_link'];
              } */

            public function filter_oembed_result($html) {

                $settings = $this->get_settings_for_display();

                $params = [];

                if ('youtube' === $settings['video_type']) {
                    $youtube_options = ['autoplay', 'loop', 'mute', 'rel', 'controls', 'showinfo'];

                    foreach ($youtube_options as $option) {
                        if ('autoplay' === $option && $this->has_image_overlay())
                            continue;

                        $value = ( 'yes' === $settings['yt_' . $option] ) ? '1' : '0';

                        if ($settings['yt_loop'] == 'yes') {


                            $ytID = $this->youtube_id_from_url($html);
                            $params['playlist'] = $ytID;
                        }
                        $params[$option] = $value;
                    }

                    $params['wmode'] = 'opaque';
                }

                if ('vimeo' === $settings['video_type']) {
                    $vimeo_options = ['autoplay', 'loop', 'title', 'portrait', 'byline'];

                    foreach ($vimeo_options as $option) {
                        if ('autoplay' === $option && $this->has_image_overlay())
                            continue;

                        $value = ( 'yes' === $settings['vimeo_' . $option] ) ? '1' : '0';
                        $params[$option] = $value;
                    }

                    $params['color'] = str_replace('#', '', $settings['vimeo_color']);
                }

                if (!empty($params)) {
                    preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $html, $matches);
                    $url = esc_url(add_query_arg($params, $matches[1]));

                    $html = str_replace($matches[1], $url, $html);
                }

                //extract the ID

                return $html;
            }

            protected function youtube_id_from_url($html) {
                preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $html, $matches);
                $url = esc_url($matches[1]);
                //echo 'youtube_id_from_url: '.$url;
                $pattern = "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/";
                $result = preg_match($pattern, $url, $matches);
                //echo '::: '.$result;
                if ($result) {
                    return $matches[1];
                }
                return false;
            }

            protected function get_hosted_params() {
                $settings = $this->get_settings_for_display();

                $params = [];

                $params['src'] = $settings['hosted_link'];

                $hosted_options = ['autoplay', 'loop'];

                foreach ($hosted_options as $key => $option) {
                    $value = ( 'yes' === $settings['hosted_' . $option] ) ? '1' : '0';
                    $params[$option] = $value;
                }

                if (!empty($settings['hosted_width'])) {
                    $params['width'] = $settings['hosted_width'];
                }

                if (!empty($settings['hosted_height'])) {
                    $params['height'] = $settings['hosted_height'];
                }
                return $params;
            }

            protected function has_image_overlay() {
                $settings = $this->get_settings_for_display();

                return !empty($settings['image_overlay']['url']) && 'yes' === $settings['show_image_overlay'];
            }

        }
        