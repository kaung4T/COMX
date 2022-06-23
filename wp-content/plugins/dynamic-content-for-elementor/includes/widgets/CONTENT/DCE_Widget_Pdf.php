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
 * Elementor Idea
 *
 * Elementor widget for Dinamic Content Elements
 *
 */
class DCE_Widget_Pdf extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce_pdf_button';
    }

    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('PDF Button', 'dynamic-content-for-elementor');
    }

    public function get_icon() {
        return 'icon-dyn-buttonpdf';
    }

    public function get_description() {
        return __('Export your content in PDF, generate them dynamically and stylized', 'dynamic-content-for-elementor');
    }

    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/pdf-button/';
    }

    /**
     * Register button widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {
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
                'text',
                [
                    'label' => __('Text', 'elementor'),
                    'type' => Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'default' => __('Download PDF', 'elementor'),
                    'placeholder' => __('Download PDF', 'elementor'),
                ]
        );

        /* $this->add_control(
          'link',
          [
          'label' => __('Link', 'elementor'),
          'type' => Controls_Manager::URL,
          'dynamic' => [
          'active' => true,
          ],
          'placeholder' => __('https://your-link.com', 'elementor'),
          'default' => [
          'url' => '#',
          ],
          ]
          ); */

        $this->add_control(
                'link',
                [
                    'label' => __('Link', 'elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'is_external',
                [
                    'label' => __('Open in new window', 'elementor'),
                    'type' => Controls_Manager::SWITCHER,
                ]
        );
        $this->add_control(
                'nofollow',
                [
                    'label' => __('Add nofollow', 'elementor'),
                    'type' => Controls_Manager::SWITCHER,
                ]
        );
        $this->add_control(
                'download',
                [
                    'label' => __('Force Download', 'elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'separator' => 'after',
                    'condition' => [
                        'is_external' => '',
                    ]
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
                'selected_icon',
                [
                    'label' => __('Icon', 'elementor'),
                    'type' => Controls_Manager::ICONS,
                    'label_block' => true,
                    'fa4compatibility' => 'icon',
                ]
        );

        $this->add_control(
                'icon_align',
                [
                    'label' => __('Icon Position', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'left',
                    'options' => [
                        'left' => __('Before', 'elementor'),
                        'right' => __('After', 'elementor'),
                    ],
                    'condition' => [
                        'selected_icon[value]!' => '',
                    ],
                ]
        );

        $this->add_control(
                'icon_indent',
                [
                    'label' => __('Icon Spacing', 'elementor'),
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
                ]
        );
        $this->add_control(
                'icon_size',
                [
                    'label' => __('Icon Size', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 60,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-button .elementor-button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_control(
                'view',
                [
                    'label' => __('View', 'elementor'),
                    'type' => Controls_Manager::HIDDEN,
                    'default' => 'traditional',
                ]
        );

        $this->add_control(
                'button_css_id',
                [
                    'label' => __('Button ID', 'elementor'),
                    'type' => Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'default' => '',
                    'title' => __('Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor'),
                    'label_block' => false,
                    'description' => __('Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'elementor'),
                    'separator' => 'before',
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
                'section_dce_pdf',
                [
                    'label' => __('PDF', 'dynamic-content-for-elementor'),
                ]
        );

        $this->add_control(
                'dce_pdf_button_title', [
            'label' => __('Title', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '[post:name]',
            'description' => __('The PDF file name, the .pdf extension will automatically added', 'dynamic-content-for-elementor'),
            'label_block' => true,
                ]
        );

        $this->add_control(
                'dce_pdf_button_body', [
            'label' => __('Body', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'post' => [
                    'title' => __('Current Post', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-left',
                ],
                'template' => [
                    'title' => __('Template', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-th-large',
                ]
            ],
            'toggle' => false,
            'default' => 'post',
                ]
        );
        $this->add_control(
                'dce_pdf_button_container',
                [
                    'label' => __('Html Container', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => '.elementor-inner',
                    'placeholder' => __('body', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'description' => 'Use jQuery selector to identify the content for this PDF.',
                    'condition' => [
                        'dce_pdf_button_body' => 'post',
                    ],
                ]
        );
        $this->add_control(
                'dce_pdf_button_template',
                [
                    'label' => __('Template', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Template Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'object_type' => 'elementor_library',
                    'description' => 'Use a Elementor Template as body fo this PDF.',
                    'condition' => [
                        'dce_pdf_button_body' => 'template',
                    ],
                ]
        );

        $paper_sizes = array_keys(\Dompdf\Adapter\CPDF::$PAPER_SIZES);
        $tmp = array();
        foreach ($paper_sizes as $asize) {
            $tmp[$asize] = strtoupper($asize);
        }
        $paper_sizes = $tmp;
        $this->add_control(
                'dce_pdf_button_size',
                [
                    'label' => __('Page Size', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'a4',
                    'options' => $paper_sizes,
                ]
        );

        $this->add_control(
                'dce_pdf_button_orientation', [
            'label' => __('Page Orientation', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'portrait' => [
                    'title' => __('Portrait', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-arrows-v',
                ],
                'landscape' => [
                    'title' => __('Landscape', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-arrows-h',
                ]
            ],
            'toggle' => false,
            'default' => 'portrait',
                ]
        );
        $this->add_control(
                'dce_pdf_button_margin', [
            'label' => __('Page Margin', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'default' => [
                'top' => 20,
                'right' => 20,
                'bottom' => 20,
                'left' => 20,
                'unit' => 'px',
                'isLinked' => true,
            ],
            'size_units' => ['px', '%', 'em'],
                ]
        );

        $this->add_control(
                'dce_pdf_button_styles', [
            'label' => __('Use Styles', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'unstyled' => [
                    'title' => __('No Style', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-circle-o',
                ],
                'elementor' => [
                    'title' => __('Only Elementor', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-adjust',
                ],
                'all' => [
                    'title' => __('Elementor & Theme', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-circle',
                ]
            ],
            'toggle' => false,
            'default' => 'elementor',
                ]
        );

        $this->add_control(
                'dce_pdf_button_converter', [
            'label' => __('Converter', 'dynamic-content-for-elementor'),
            //'type' => Controls_Manager::HIDDEN,
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'dompdf' => [
                    'title' => __('DomPDF', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-paint-brush',
                ],
                'tcpdf' => [
                    'title' => __('TCPDF', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-rocket',
                ],
                'browser' => [
                    'title' => __('Browser', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-window-maximize',
                ]
            ],
            'toggle' => false,
            'default' => 'dompdf',
                ]
        );

        $this->end_controls_section();
    }

    /**
     * Render button widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $pdf_url = DCE_URL . 'assets/pdf.php';
        $pdf_url .= '?post_id=' . get_the_ID();
        if ($settings['dce_pdf_button_body'] == 'template') {
            $pdf_url .= '&template_id=' . $settings['dce_pdf_button_template'];
        } else {
            $pdf_url .= '&container=' . urlencode($settings['dce_pdf_button_container']);
        }
        $pdf_url .= '&styles=' . $settings['dce_pdf_button_styles'];

        $pdf_url .= '&title=' . $settings['dce_pdf_button_title'];
        $pdf_url .= '&size=' . $settings['dce_pdf_button_size'];
        $pdf_url .= '&orientation=' . $settings['dce_pdf_button_orientation'];
        if (isset($settings['dce_pdf_button_margin']['top']) && $settings['dce_pdf_button_margin']['top'] != '') {
            $pdf_url .= '&margin=' . urlencode($settings['dce_pdf_button_margin']['top'] . $settings['dce_pdf_button_margin']['unit'] . ' ' . $settings['dce_pdf_button_margin']['right'] . $settings['dce_pdf_button_margin']['unit'] . ' ' . $settings['dce_pdf_button_margin']['bottom'] . $settings['dce_pdf_button_margin']['unit'] . ' ' . $settings['dce_pdf_button_margin']['left'] . $settings['dce_pdf_button_margin']['unit']);
        }
        $pdf_url .= '&converter=' . $settings['dce_pdf_button_converter'];

        if ($settings['download']) {
            $pdf_url .= '&dest=F';
        }

        $this->add_render_attribute('wrapper', 'class', 'elementor-button-wrapper');

        //if (!empty($settings['link']['url'])) {
        $this->add_render_attribute('button', 'href', $pdf_url);
        $this->add_render_attribute('button', 'class', 'elementor-button-link');

        if ($settings['is_external']) {
            $this->add_render_attribute('button', 'target', '_blank');
        }

        if ($settings['nofollow']) {
            $this->add_render_attribute('button', 'rel', 'nofollow');
        }

        if ($settings['download']) {
            $this->add_render_attribute('button', 'download', '');
        }
        //}

        $this->add_render_attribute('button', 'class', 'elementor-button');
        $this->add_render_attribute('button', 'role', 'button');

        if (!empty($settings['button_css_id'])) {
            $this->add_render_attribute('button', 'id', $settings['button_css_id']);
        }

        if (!empty($settings['size'])) {
            $this->add_render_attribute('button', 'class', 'elementor-size-' . $settings['size']);
        }

        if ($settings['hover_animation']) {
            $this->add_render_attribute('button', 'class', 'elementor-animation-' . $settings['hover_animation']);
        }
        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
            <a <?php echo $this->get_render_attribute_string('button'); ?>>
                <?php $this->render_text(); ?>
            </a>
        </div>
        <?php
    }

    /**
     * Render button text.
     *
     * Render button widget text.
     *
     * @since 1.5.0
     * @access protected
     */
    protected function render_text() {
        $settings = $this->get_settings_for_display();

        $migrated = isset($settings['__fa4_migrated']['selected_icon']);
        $is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();

        if (!$is_new && empty($settings['icon_align'])) {
            // @todo: remove when deprecated
            // added as bc in 2.6
            //old default
            $settings['icon_align'] = $this->get_settings('icon_align');
        }

        $this->add_render_attribute([
            'content-wrapper' => [
                'class' => ['elementor-button-content-wrapper', 'dce-flex'],
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
        ?>
        <span <?php echo $this->get_render_attribute_string('content-wrapper'); ?>>
            <?php if (!empty($settings['icon']) || !empty($settings['selected_icon']['value'])) : ?>
                <span <?php echo $this->get_render_attribute_string('icon-align'); ?>>
                    <?php
                    if ($is_new || $migrated) :
                        Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);
                    else :
                        ?>
                        <i class="<?php echo esc_attr($settings['icon']); ?>" aria-hidden="true"></i>
                    <?php endif; ?>
                </span>
            <?php endif; ?>
            <span <?php echo $this->get_render_attribute_string('text'); ?>><?php echo $settings['text']; ?></span>
        </span>
        <?php
    }

    public function on_import($element) {
        return Icons_Manager::on_import_migration($element, 'icon', 'selected_icon');
    }

}
