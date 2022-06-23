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
class DCE_Widget_Calendar extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce_add_to_calendar';
    }

    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('Add to Calendar', 'dynamic-content-for-elementor');
    }

    public function get_icon() {
        return 'icon-dyn-buttoncalendar';
    }

    public function get_description() {
        return __('Add current event to your personal calendar', 'dynamic-content-for-elementor');
    }

    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/button-calendar/';
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
                    'default' => __('Add to Calendar', 'elementor'),
                    'placeholder' => __('Add to Calendar', 'elementor'),
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
                'section_calendar',
                [
                    'label' => __('Calendar', 'elementor'),
                ]
        );

        $format = array(
            'gcalendar' => 'Google Calendar',
            'ics' => 'ICS (iCal, Outlook, etc)',
        );
        $this->add_control(
                'dce_calendar_format',
                [
                    'label' => __('Type', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'gcalendar' => [
                            'title' => __('Google Calendar', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-google',
                        ],
                        'ics' => [
                            'title' => __('ICS (iCal, Outlook, etc)', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-calendar',
                        ],
                    ],
                    'label_block' => true,
                    'default' => 'gcalendar',
                    'toggle' => false,
                ]
        );

        // title
        $this->add_control(
                'dce_calendar_title', [
            'label' => __('Title', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
                ]
        );

        $this->add_control(
                'dce_calendar_datetime_format',
                [
                    'label' => __('Datetime Field', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'picker' => [
                            'title' => __('Static Datetime Picker', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-calendar-plus-o',
                        ],
                        'string' => [
                            'title' => __('Dynamic String', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-i-cursor',
                        ],
                    ],
                    'label_block' => true,
                    'default' => 'string',
                    'toggle' => false,
                ]
        );

        // datetime start
        $this->add_control(
                'dce_calendar_datetime_start', [
            'label' => __('DateTime Start', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DATE_TIME,
            'label_block' => true,
            'condition' => [
                'dce_calendar_datetime_format' => 'picker',
            ]
                ]
        );
        // datetime end
        $this->add_control(
                'dce_calendar_datetime_end', [
            'label' => __('DateTime End', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DATE_TIME,
            'label_block' => true,
            'condition' => [
                'dce_calendar_datetime_format' => 'picker',
            ]
                ]
        );
        // datetime start
        $this->add_control(
                'dce_calendar_datetime_start_string', [
            'label' => __('DateTime Start', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => 'YYYY-mm-dd HH:ii',
            'label_block' => true,
            'condition' => [
                'dce_calendar_datetime_format' => 'string',
            ]
                ]
        );
        // datetime end
        $this->add_control(
                'dce_calendar_datetime_end_string', [
            'label' => __('DateTime End', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'placeholder' => 'YYYY-mm-dd HH:ii',
            'condition' => [
                'dce_calendar_datetime_format' => 'string',
            ]
                ]
        );

        // description
        $this->add_control(
                'dce_calendar_description', [
            'label' => __('Description', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::WYSIWYG,
                ]
        );
        // location
        $this->add_control(
                'dce_calendar_location', [
            'label' => __('Location', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
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

        if ($settings['dce_calendar_format'] == 'ics') {
            $cal_url = DCE_URL . 'assets/ics.php';
            $cal_url .= '?post_id=' . get_the_ID();
            $cal_url .= '&element_id=' . $this->get_id();
        } else {
            $cal_url = "https://www.google.com/calendar/render?action=TEMPLATE";
            if ($settings['dce_calendar_title'])
                $cal_url .= '&text=' . urlencode($settings['dce_calendar_title']);
            if ($settings['dce_calendar_description'])
                $cal_url .= '&details=' . urlencode($settings['dce_calendar_description']);
            if ($settings['dce_calendar_location'])
                $cal_url .= '&location=' . urlencode($settings['dce_calendar_location']);

            $start = ($settings['dce_calendar_datetime_format'] != 'string') ? $settings['dce_calendar_datetime_start'] : $settings['dce_calendar_datetime_start_string'];
            $end = ($settings['dce_calendar_datetime_format'] != 'string') ? $settings['dce_calendar_datetime_end'] : $settings['dce_calendar_datetime_end_string'];
            if ($start) {
                $cal_url .= '&dates=' . urlencode(date('Ymd\\THi00\\Z', strtotime($start)));
                if ($end) {
                    $cal_url .= '%2F' . urlencode(date('Ymd\\THi00\\Z', strtotime($end)));
                }
            }
            //$cal_url .= '&action=TEMPLATE';
            //$cal_url .= '&trp=false';
            //$cal_url .= '&sf=true';
            //$cal_url .= '&sprop=';
            //$cal_url .= '&sprop=name:';
        }


        $this->add_render_attribute('wrapper', 'class', 'elementor-button-wrapper');

        //if (!empty($settings['link']['url'])) {
        $this->add_render_attribute('button', 'href', $cal_url);
        $this->add_render_attribute('button', 'class', 'elementor-button-link');
        $this->add_render_attribute('button', 'target', '_blank');
        $this->add_render_attribute('button', 'rel', 'nofollow');

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
                'class' => ['elementor-button-content-wrapper', 'dce-flexbox'],
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
