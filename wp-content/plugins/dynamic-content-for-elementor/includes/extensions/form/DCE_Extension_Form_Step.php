<?php

namespace DynamicContentForElementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\DCE_Tokens;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

function _dce_extension_form_step($field) {
    switch ($field) {
        case 'enabled':
            return true;
        case 'docs':
            return 'https://www.dynamic.ooo/widget/form-steps-for-elementor-pro-form/';
        case 'description' :
            return __('Add Steps to Elementor PRO Form', 'dynamic-content-for-elementor');
    }
}

if (!DCE_Helper::is_plugin_active('elementor-pro')) {

    class DCE_Extension_Form_Step extends DCE_Extension_Prototype {

        public $name = 'Form Steps';
        private $is_common = false;
        public static $depended_plugins = ['elementor-pro'];

        static public function is_enabled() {
            return _dce_extension_form_step('enabled');
        }

        public static function get_description() {
            return _dce_extension_form_step('description');
        }

        public function get_docs() {
            return _dce_extension_form_step('docs');
        }

    }

} else {

    class DCE_Extension_Form_Step extends DCE_Extension_Prototype {

        public $name = 'Form Steps';
        public static $depended_plugins = ['elementor-pro'];
        public static $docs = 'https://www.dynamic.ooo/';
        private $is_common = false;
        public $has_action = false;

        static public function is_enabled() {
            return _dce_extension_form_step('enabled');
        }

        public static function get_description() {
            return _dce_extension_form_step('description');
        }

        public function get_docs() {
            return _dce_extension_form_step('docs');
        }

        public static function get_plugin_depends() {
            return self::$depended_plugins;
        }

        static public function get_satisfy_dependencies($ret = false) {
            return true;
        }

        /**
         * Get Name
         *
         * Return the action name
         *
         * @access public
         * @return string
         */
        public function get_name() {
            return 'dce_form_step';
        }

        /**
         * Get Label
         *
         * Returns the action label
         *
         * @access public
         * @return string
         */
        public function get_label() {
            return __('Form Steps', 'dynamic-content-for-elementor');
        }

        /**
         * Add Actions
         *
         * @since 0.5.5
         *
         * @access private
         */
        protected function add_actions() {
            add_action("elementor/widget/render_content", array($this, '_render_form'), 10, 2);

            add_action('elementor/element/form/section_form_style/before_section_start', [$this, 'add_control_section_to_form'], 10, 2);
            //add_action("elementor/frontend/widget/before_render", array($this, '_before_render_form'), 10, 2);    
            
            add_action( 'elementor/widget/print_template', function( $template, $widget ) {
               if ( 'form' === $widget->get_name() ) {
                    $template = false;
               }
               return $template;
            }, 10, 2 );


        }

        public function add_control_section_to_form($element, $args) {
            $element->start_controls_section(
                    'dce_step_section',
                    [
                        'label' => __('Steps', 'dynamic-content-for-elementor'),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
            );

            $element->add_control(
                    'dce_step_legend',
                    [
                        'label' => __('Use Label as Legend', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                    ]
            );

            $element->add_control(
                    'dce_step_show',
                    [
                        'label' => __('Show All steps', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                    ]
            );

            $element->add_control(
                    'dce_step_progressbar',
                    [
                        'label' => __('Enable Step ProgressBar', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'condition' => [
                            'dce_step_show' => '',
                        ],
                    ]
            );

            $element->add_control(
                    'dce_step_help', [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div id="elementor-panel__editor__help" class="p-0"><a id="elementor-panel__editor__help__link" href="' . $this->get_docs() . '" target="_blank">' . __('Need Help', 'elementor') . ' <i class="eicon-help-o"></i></a></div>',
                'separator' => 'before',
                    ]
            );


            /* $element->add_control(
              'dce_step_reload', [
              'type' => \Elementor\Controls_Manager::RAW_HTML,
              'raw' => '<div class="elementor-update-preview">
              <div class="elementor-update-preview-title">'.__( 'Update changes to page', 'elementor' ).'</div>
              <div class="elementor-update-preview-button-wrapper">
              <button class="elementor-update-preview-button elementor-button elementor-button-success">'.__( 'Apply', 'elementor' ).'</button>
              </div>
              </div>',
              'separator' => 'before',
              ]
              ); */

            $element->end_controls_section();

            $element->start_controls_section(
                    'dce_step_section_style',
                    [
                        'label' => __('Steps', 'dynamic-content-for-elementor'),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    ]
            );
            $element->add_responsive_control(
                    'dce_step_padding', [
                'label' => __('Padding', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .dce-form-step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                    ]
            );
            $element->add_responsive_control(
                    'dce_step_margin', [
                'label' => __('Margin', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .dce-form-step' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                    ]
            );

            // Border ----------------
            $element->add_control(
                    'dce_step_heading_border',
                    [
                        'label' => __('Border', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
            );
            $element->add_group_control(
                    Group_Control_Border::get_type(), [
                'name' => 'dce_step_border',
                'label' => __('Border', 'dynamic-content-for-elementor'),
                'selector' => '{{WRAPPER}} .dce-form-step',
                    ]
            );
            $element->add_control(
                    'dce_step_border_radius', [
                'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dce-form-step' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                    ]
            );

            // Background ----------------
            $element->add_control(
                    'dce_step_heading_background',
                    [
                        'label' => __('Background', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
            );
            $element->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'dce_step_background',
                        'types' => ['classic', 'gradient'],
                        'selector' => '{{WRAPPER}} .dce-form-step',
                    ]
            );

            // Title ----------------
            $element->add_control(
                    'dce_step_heading_title',
                    [
                        'label' => __('Title', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                        'condition' => [
                            'dce_step_legend!' => '',
                        ],
                    ]
            );
            $element->add_responsive_control(
                    'dce_step_title_align',
                    [
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
                        'selectors' => [
                            '{{WRAPPER}} .dce-form-step legend' => 'text-align: {{VALUE}};',
                        ],
                        'condition' => [
                            'dce_step_legend!' => '',
                        ],
                    ]
            );
            $element->add_control(
                    'dce_step_title_color',
                    [
                        'label' => __('Color', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::COLOR,
                        'default' => '',
                        'selectors' => [
                            '{{WRAPPER}} .dce-form-step legend' => 'color: {{VALUE}};',
                        ],
                        'condition' => [
                            'dce_step_legend!' => '',
                        ],
                    ]
            );
            $element->add_group_control(
                    Group_Control_Typography::get_type(), [
                'name' => 'dce_step_title_typography',
                'label' => __('Typography', 'dynamic-content-for-elementor'),
                'selector' => '{{WRAPPER}} .dce-form-step legend',
                'condition' => [
                    'dce_step_legend!' => '',
                ],
                    ]
            );
            $element->add_control(
                    'dce_step_title_space',
                    [
                        'label' => __('Space', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 10,
                        ],
                        'range' => [
                            'px' => [
                                'min' => -50,
                                'max' => 100,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .dce-form-step legend' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        ],
                        'condition' => [
                            'dce_step_legend!' => '',
                        ],
                    ]
            );
            $element->add_group_control(
                    Group_Control_Text_Shadow::get_type(),
                    [
                        'name' => 'dce_step_text_shadow',
                        'selector' => '{{WRAPPER}} .dce-form-step legend',
                        'condition' => [
                            'dce_step_legend!' => '',
                        ],
                    ]
            );

            /* $element->add_control(
              'border_popover_toggle',
              [
              'label' => __( 'Border', 'plugin-domain' ),
              'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
              'label_off' => __( 'Default', 'your-plugin' ),
              'label_on' => __( 'Custom', 'your-plugin' ),
              'return_value' => 'yes',
              'default' => 'yes',
              ]
              ); */

            $element->end_controls_section();

            $element->start_controls_section(
                    'dce_step_section_button',
                    [
                        'label' => __('Steps Navigation Buttons', 'dynamic-content-for-elementor'),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                        'condition' => [
                            'dce_step_show' => '',
                        ],
                    ]
            );

            $element->add_control(
                    'dce_step_button_size',
                    [
                        'label' => __('Size', 'elementor-pro'),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'sm',
                        'options' => DCE_Helper::get_button_sizes(),
                    ]
            );

            $element->start_controls_tabs('dce_step_tabs_button_style');

            $element->start_controls_tab(
                    'dce_step_tab_button_normal',
                    [
                        'label' => __('Normal', 'elementor-pro'),
                    ]
            );

            $element->add_control(
                    'dce_step_button_background_color',
                    [
                        'label' => __('Background Color', 'elementor-pro'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .dce-step-elementor-button' => 'background-color: {{VALUE}};',
                        ],
                    ]
            );

            $element->add_control(
                    'dce_step_button_text_color',
                    [
                        'label' => __('Text Color', 'elementor-pro'),
                        'type' => Controls_Manager::COLOR,
                        'default' => '',
                        'selectors' => [
                            '{{WRAPPER}} .dce-step-elementor-button' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .dce-step-elementor-button svg' => 'fill: {{VALUE}};',
                        ],
                    ]
            );

            $element->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name' => 'dce_step_button_typography',
                        'selector' => '{{WRAPPER}} .dce-step-elementor-button',
                    ]
            );

            $element->add_group_control(
                    Group_Control_Border::get_type(), [
                'name' => 'dce_step_button_border',
                'selector' => '{{WRAPPER}} .dce-step-elementor-button',
                    ]
            );

            $element->add_control(
                    'dce_step_button_border_radius',
                    [
                        'label' => __('Border Radius', 'elementor-pro'),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', '%'],
                        'selectors' => [
                            '{{WRAPPER}} .dce-step-elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
            );

            $element->add_control(
                    'dce_step_button_text_padding',
                    [
                        'label' => __('Text Padding', 'elementor-pro'),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors' => [
                            '{{WRAPPER}} .dce-step-elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
            );

            $element->end_controls_tab();

            $element->start_controls_tab(
                    'dce_step_tab_button_hover',
                    [
                        'label' => __('Hover', 'elementor-pro'),
                    ]
            );

            $element->add_control(
                    'dce_step_button_background_hover_color',
                    [
                        'label' => __('Background Color', 'elementor-pro'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .dce-step-elementor-button:hover' => 'background-color: {{VALUE}};',
                        ],
                    ]
            );

            $element->add_control(
                    'dce_step_button_hover_color',
                    [
                        'label' => __('Text Color', 'elementor-pro'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .dce-step-elementor-button:hover' => 'color: {{VALUE}};',
                        ],
                    ]
            );

            $element->add_control(
                    'dce_step_button_hover_border_color',
                    [
                        'label' => __('Border Color', 'elementor-pro'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .dce-step-elementor-button:hover' => 'border-color: {{VALUE}};',
                        ],
                        'condition' => [
                            'dce_step_button_border_border!' => '',
                        ],
                    ]
            );

            $element->add_control(
                    'dce_step_button_hover_animation',
                    [
                        'label' => __('Animation', 'elementor-pro'),
                        'type' => Controls_Manager::HOVER_ANIMATION,
                    ]
            );

            $element->end_controls_tab();

            $element->end_controls_tabs();


            $element->add_control(
                    'dce_step_button_css_class',
                    [
                        'label' => __('Custom Classes', 'elementor-pro'),
                        'type' => Controls_Manager::TEXT,
                        'default' => '',
                        //'title' => __('Add your custom classes WITHOUT the dot key. e.g: my-class', 'dynamic-content-for-elementor'),
                        'label_block' => true,
                        //'description' => __('Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'elementor-pro'),
                        'separator' => 'before',
                    ]
            );

            $element->end_controls_section();







            $element->start_controls_section(
                    'dce_step_section_progressbar',
                    [
                        'label' => __('Steps ProgressBar', 'dynamic-content-for-elementor'),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                        'condition' => [
                            'dce_step_show' => '',
                            'dce_step_progressbar!' => '',
                        ],
                    ]
            );

            $element->add_control(
                    'dce_step_progressbar_size',
                    [
                        'label' => __('Size', 'elementor-pro'),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'sm',
                        'options' => DCE_Helper::get_button_sizes(),
                    ]
            );

            $element->start_controls_tabs('dce_step_tabs_progressbar_style');

            $element->start_controls_tab(
                    'dce_step_tab_progressbar_normal',
                    [
                        'label' => __('Normal', 'elementor-pro'),
                    ]
            );

            $element->add_control(
                    'dce_step_progressbar_background_color',
                    [
                        'label' => __('Background Color', 'elementor-pro'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .dce-form-step-progressbar .elementor-button' => 'background-color: {{VALUE}};',
                        ],
                    ]
            );

            $element->add_control(
                    'dce_step_progressbar_text_color',
                    [
                        'label' => __('Text Color', 'elementor-pro'),
                        'type' => Controls_Manager::COLOR,
                        'default' => '',
                        'selectors' => [
                            '{{WRAPPER}} .dce-form-step-progressbar .elementor-button' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .dce-form-step-progressbar .elementor-button svg' => 'fill: {{VALUE}};',
                        ],
                    ]
            );

            $element->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name' => 'dce_step_progressbar_typography',
                        'selector' => '{{WRAPPER}} .dce-form-step-progressbar .elementor-button',
                    ]
            );

            $element->add_group_control(
                    Group_Control_Border::get_type(), [
                'name' => 'dce_step_progressbar_border',
                'selector' => '{{WRAPPER}} .dce-form-step-progressbar .elementor-button',
                    ]
            );

            $element->add_control(
                    'dce_step_progressbar_border_radius',
                    [
                        'label' => __('Border Radius', 'elementor-pro'),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', '%'],
                        'selectors' => [
                            '{{WRAPPER}} .dce-form-step-progressbar .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
            );

            $element->add_control(
                    'dce_step_progressbar_text_padding',
                    [
                        'label' => __('Text Padding', 'elementor-pro'),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors' => [
                            '{{WRAPPER}} .dce-form-step-progressbar .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
            );

            $element->end_controls_tab();

            $element->start_controls_tab(
                    'dce_step_tab_progressbar_active',
                    [
                        'label' => __('Active', 'elementor-pro'),
                    ]
            );

            $element->add_control(
                    'dce_step_progressbar_background_hover_color',
                    [
                        'label' => __('Background Color', 'elementor-pro'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .dce-form-step-progressbar .elementor-button:hover' => 'background-color: {{VALUE}};',
                            '{{WRAPPER}} .dce-form-step-progressbar.dce-step-active-progressbar .elementor-button' => 'background-color: {{VALUE}};',
                        ],
                    ]
            );

            $element->add_control(
                    'dce_step_progressbar_hover_color',
                    [
                        'label' => __('Text Color', 'elementor-pro'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .dce-form-step-progressbar .elementor-button:hover' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .dce-form-step-progressbar.dce-step-active-progressbar .elementor-button' => 'color: {{VALUE}};',
                        ],
                    ]
            );

            $element->add_control(
                    'dce_step_progressbar_hover_border_color',
                    [
                        'label' => __('Border Color', 'elementor-pro'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .dce-form-step-progressbar .elementor-button:hover' => 'border-color: {{VALUE}};',
                            '{{WRAPPER}} .dce-form-step-progressbar.dce-step-active-progressbar .elementor-button' => 'border-color: {{VALUE}};',
                        ],
                        'condition' => [
                            'dce_step_progressbar_border_border!' => '',
                        ],
                    ]
            );

            $element->end_controls_tab();

            $element->end_controls_tabs();

            $element->end_controls_section();
        }

        public static function _add_to_form(Controls_Stack $element, $control_id, $control_data, $options = []) {
            //echo 'adsa: '; var_dump($control_id); //die();
            if ($element->get_name() == 'form' && $control_id == 'form_fields') {
                //var_dump($control_data); die();

                $control_data["fields"]["field_type"]["options"]['step'] = __('Step', 'dynamic-content-for-elementor');

                if ($control_id == 'form_fields') {
                    $control_data['fields']['dce_step_next'] = array(
                        'name' => 'dce_step_next',
                        'label' => __('Text Next', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::TEXT,
                        'default' => __('Next', 'dynamic-content-for-elementor'),
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'field_type',
                                    'value' => 'step',
                                ],
                            ],
                        ],
                        "tabs_wrapper" => "form_fields_tabs",
                        "tab" => "content",
                    );
                    $control_data['fields']['dce_step_prev'] = array(
                        'name' => 'dce_step_prev',
                        'label' => __('Text Prev', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::TEXT,
                        'default' => __('Prev', 'dynamic-content-for-elementor'),
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'field_type',
                                    'value' => 'step',
                                ],
                            ],
                        ],
                        "tabs_wrapper" => "form_fields_tabs",
                        "tab" => "content",
                    );

                    //$control_data['fields']['field_html']['conditions']['terms']['value'] = array('html','step');
                    $control_data['fields']['field_step'] = array(
                        'name' => 'field_step',
                        'label' => __('HTML', 'elementor-pro'),
                        'type' => Controls_Manager::TEXTAREA,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'field_type',
                                    'value' => 'step',
                                ],
                            ],
                        ],
                        "tabs_wrapper" => "form_fields_tabs",
                        "tab" => "content",
                    );
                }
            }

            return $control_data;
        }

        public function _progressbar($widget) {
            $settings = $widget->get_settings_for_display();
            if (!$settings['dce_step_progressbar']) {
                return '';
            }

            // FIELDS
            $steps = array();
            if (!empty($settings['form_fields'])) {
                foreach ($settings['form_fields'] as $key => $afield) {                    
                    if ($afield["field_type"] == 'step') {
                        $steps[] = $afield;
                        // TODO: remove it from form_fields
                    }
                }
            }

            $bar = '';
            if (!empty($steps)) {
                $bar .= '<ol class="dce-form-progressbar">';
                foreach ($steps as $key => $astep) {
                    $bar .= '<li id="dce-form-step-' . $astep['custom_id'] . '-progressbar" class="dce-form-step-progressbar' . (!$key ? ' dce-step-active-progressbar' : '') . '">';
                    $bar .= '<a class="elementor-button elementor-button-progressbar elementor-size-' . $settings['dce_step_progressbar_size'] . '" href="#" data-target="' . $astep['custom_id'] . '">';
                    $bar .= $astep['field_label'];
                    $bar .= '</a>';
                    $bar .= '</li>';
                }
                $bar .= '</ol>';
            }
            return $bar;
        }

        public function _render_form($content, $widget) {
            $new_content = $content;
            if ($widget->get_name() == 'form') {
                $settings = $widget->get_settings_for_display();

                //ar_dump($settings['form_fields']); die();
                // FIELDS
                $steps = array();
                if (!empty($settings['form_fields'])) {
                    foreach ($settings['form_fields'] as $key => $afield) {
                        if (!$key && $afield["field_type"] != 'step') {
                            break;
                        }
                        if ($afield["field_type"] == 'step') {
                            $steps[] = $afield;
                        }
                    }
                }

                if (!empty($steps)) {
                    ob_start();
                    //foreach($steps as $astep) { }
                    // add custom js
                    ?>
                    <script>
                        jQuery(document).ready(function () {
                            var form_id = '<?php echo $widget->get_id(); ?>';
                            var settings = <?php echo json_encode($settings); ?>;
                            //console.log(settings);
                            jQuery('.elementor-field-type-step').hide();
                            //jQuery('.elementor-field-type-step').hide();
                            var step_last = false;
                            if (settings['form_fields'].length) {
                                jQuery(settings.form_fields).each(function (index, afield) {
                                    //console.log(index);
                                    //console.log(afield);
                                    if (!index && afield.field_type != 'step') {
                                        // force first step
                                        
                                    }
                                    if (afield.field_type == 'step') {
                                        var width = afield.width;
                                        if (!width) {
                                            width = 100;
                                        }
                                        jQuery('.elementor-element-' + form_id + ' .elementor-form > .elementor-form-fields-wrapper').append('<fieldset id="dce-form-step-' + afield.custom_id + '" data-custom_id="' + afield.custom_id + '" class="dce-form-step elementor-column elementor-col-' + width + '"></fieldset>');
                                        if (!step_last) {
                                            // first step
                                            jQuery('#dce-form-step-' + afield.custom_id).addClass('dce-step-active');
                                        }
                                        jQuery('#dce-form-step-' + afield.custom_id).append('<div class="elementor-field-type-step elementor-field-group elementor-column elementor-field-group-' + afield.custom_id + ' elementor-col-100">' + afield.field_step + '</div>');
                                    }
                                    if (afield.field_type == 'step' && step_last) {
                                            <?php if ($settings['dce_step_legend']) { ?>
                                            // legend
                                            jQuery('.elementor-element-' + form_id + ' .elementor-form-fields-wrapper #dce-form-step-' + step_last.custom_id).prepend('<legend>' + step_last.field_label + '</legend>');
                                            <?php } ?>
                                            <?php if (!$settings['dce_step_show']) { ?>
                                            // clear
                                            jQuery('.elementor-element-' + form_id + ' .elementor-form-fields-wrapper #dce-form-step-' + step_last.custom_id).append('<div class="elementor-field-group elementor-column elementor-col-100"></div>');
                                            
                                            <?php 
                                            $btn_class = '';
                                            if ($settings['dce_step_button_css_class']) {
                                                $btn_class .= $settings['dce_step_button_css_class'] . ' ';
                                            } 
                                            if ($settings['dce_step_button_hover_animation']) {
                                                $btn_class .= 'elementor-animation-' . $settings['dce_step_button_hover_animation'] . ' ';
                                            }
                                            ?>
                                            // prev
                                            jQuery('.elementor-element-' + form_id + ' .elementor-form-fields-wrapper #dce-form-step-' + afield.custom_id).append('<div class="elementor-field-group elementor-column elementor-col-50 dce-form-step-bnt-prev"><button type="button" class="<?php echo $btn_class; ?>elementor-button dce-step-elementor-button elementor-button-prev elementor-size-<?php echo $settings['dce_step_button_size']; ?>" data-target="' + step_last.custom_id + '"><span><span class="elementor-button-text">' + step_last.dce_step_prev + '</span></span></button></div>');
                                            // first prev empty
                                            //alert('#dce-form-step-'+step_last.custom_id);
                                            if (jQuery('#dce-form-step-'+step_last.custom_id).hasClass('dce-step-active')) {                                                
                                                jQuery('.elementor-element-' + form_id + ' .elementor-form-fields-wrapper #dce-form-step-' + step_last.custom_id).append('<div class="elementor-field-group elementor-column elementor-col-50 dce-form-step-bnt-prev"></div>');
                                            } else {
                                                // prev to bottom
                                                jQuery('.elementor-element-' + form_id + ' .elementor-form-fields-wrapper #dce-form-step-' + step_last.custom_id + ' .dce-form-step-bnt-prev').appendTo('.elementor-element-' + form_id + ' .elementor-form-fields-wrapper #dce-form-step-' + step_last.custom_id);
                                            }
                                            // next
                                            jQuery('.elementor-element-' + form_id + ' .elementor-form-fields-wrapper #dce-form-step-' + step_last.custom_id).append('<div class="elementor-field-group elementor-column elementor-col-50 dce-form-step-bnt-next"><button type="button" class="<?php echo $btn_class; ?>elementor-button dce-step-elementor-button elementor-button-next elementor-size-<?php echo $settings['dce_step_button_size']; ?>" data-target="' + afield.custom_id + '"><span><span class="elementor-button-text">' + step_last.dce_step_next + '</span></span></button></div>');
                                            
                                        <?php } ?>
                                        // bugfix for flex on Chrome    
                                        jQuery('.elementor-element-' + form_id + ' .elementor-form-fields-wrapper #dce-form-step-' + step_last.custom_id).wrapInner('<div class="elementor-form-fields-wrapper elementor-form-fields-wrapper-' + step_last.custom_id+' elementor-labels-above elementor-column elementor-col-100"></div>');
                                    }
                                    if (afield.field_type == 'step') {
                                        step_last = afield;
                                    }
                                    jQuery('.elementor-element-' + form_id + ' .elementor-form-fields-wrapper .elementor-field-group-' + afield.custom_id).appendTo('#dce-form-step-' + step_last.custom_id);
                                });

                                if (step_last) {
                    <?php if ($settings['dce_step_legend']) { ?>
                                        // legend
                                        jQuery('.elementor-element-' + form_id + ' .elementor-form-fields-wrapper #dce-form-step-' + step_last.custom_id).prepend('<legend>' + step_last.field_label + '</legend>');
                    <?php } ?>

                                    // submit
                    <?php if (!$settings['dce_step_show']) { ?>
                                        // prev to bottom
                                        jQuery('.elementor-element-' + form_id + ' .elementor-form-fields-wrapper #dce-form-step-' + step_last.custom_id + ' .dce-form-step-bnt-prev').appendTo('.elementor-element-' + form_id + ' .elementor-form-fields-wrapper #dce-form-step-' + step_last.custom_id);
                                        jQuery('.elementor-element-' + form_id + ' .elementor-form-fields-wrapper #dce-form-step-' + step_last.custom_id).append('<div class="elementor-field-group elementor-column elementor-col-50 dce-form-step-bnt-next"></div>');
                                        jQuery('.elementor-element-' + form_id + ' .elementor-form-fields-wrapper .elementor-field-group.elementor-field-type-submit').appendTo('#dce-form-step-' + step_last.custom_id + ' .dce-form-step-bnt-next');
                                        //jQuery('.elementor-element-'+form_id+' .elementor-form-fields-wrapper .elementor-field-group.elementor-field-type-submit').addClass('elementor-col-50');
                    <?php } else { ?>
                                        jQuery('.elementor-element-' + form_id + ' .elementor-form-fields-wrapper .elementor-field-group.elementor-field-type-submit').appendTo('.elementor-element-' + form_id + ' .elementor-form > .elementor-form-fields-wrapper');
                    <?php } ?>
                                }

                                jQuery('.elementor-element-' + form_id + ' .dce-form-step-bnt-prev .elementor-button-prev').on('click', function () {
                                    var target = jQuery(this).attr('data-target');
                                    dce_show_step_<?php echo $widget->get_id(); ?>(target);
                                });
                                jQuery('.elementor-element-' + form_id + ' .dce-form-step-bnt-next .elementor-button-next').on('click', function () {
                                    var target = jQuery(this).attr('data-target');
                                    var step = jQuery(this).closest('.dce-form-step');
                                    var next = dce_validate_step(step);
                                    if (next) {
                                        dce_show_step_<?php echo $widget->get_id(); ?>(target);
                                    } else {
                                        //jQuery(this).closest('form').triggerHandler( "submit" ); //
                                        //jQuery(this).closest('form').submit();
                                    }
                                });
                                jQuery('.elementor-element-' + form_id + ' .dce-form-step-progressbar .elementor-button-progressbar').on('click', function () {
                                    var target = jQuery(this).attr('data-target');
                                    var next = true;
                                    jQuery(this).closest('.dce-form-step-progressbar').prevAll().each(function () {
                                        var custom_id = jQuery(this).find('.elementor-button').attr('data-target');
                                        console.log(custom_id);
                                        next = dce_validate_step(jQuery('#dce-form-step-' + custom_id));
                                    });
                                    if (next) {
                                        dce_show_step_<?php echo $widget->get_id(); ?>(target);
                                    }
                                    return false;
                                });


                            }
                            //alert("It works");
                        });
                        function dce_show_step_<?php echo $widget->get_id(); ?>(target) {
                            jQuery('.elementor-error').removeClass('elementor-error');
                            jQuery('.dce-form-step').hide();
                            jQuery('.dce-step-active').removeClass('dce-step-active');
                            jQuery('.dce-step-active-progressbar').removeClass('dce-step-active-progressbar');
                            jQuery('#dce-form-step-' + target).show().addClass('dce-step-active');
                            jQuery('#dce-form-step-' + target + '-progressbar').addClass('dce-step-active-progressbar');
                        }
                        function dce_validate_step(step) {
                            var next = true;
                            step.find('.elementor-field-required input, .elementor-field-required select, .elementor-field-required textarea').each(function () {
                                //console.log(jQuery(this).val());
                                if (!jQuery(this).val()) {
                                    jQuery(this).closest('.elementor-field-required').addClass('elementor-error');
                                    next = false;
                                }
                            });
                            return next;
                        }
                    </script>
                    <style>
                        .elementor-form-fields-wrapper.elementor-labels-above > .dce-form-step > .elementor-field-group .elementor-field-subgroup, 
                        .elementor-form-fields-wrapper.elementor-labels-above > .dce-form-step > .elementor-field-group > .elementor-select-wrapper, 
                        .elementor-form-fields-wrapper.elementor-labels-above > .dce-form-step > .elementor-field-group > input, 
                        .elementor-form-fields-wrapper.elementor-labels-above > .dce-form-step > .elementor-field-group > textarea
                         {
                            -webkit-flex-basis: 100%;
                            -ms-flex-preferred-size: 100%;
                            flex-basis: 100%;
                            max-width: 100%;
                        }
                        .elementor-element-<?php echo $widget->get_id(); ?> .dce-form-step {
                            flex-wrap: wrap;                            
                            max-width: 100%;                            
                            display: flex;
                            align-content: flex-start;
                        }
                    <?php if (!$settings['dce_step_show']) { ?>
                            .elementor-element-<?php echo $widget->get_id(); ?> .dce-form-step {    
                                display: none;
                            }
                            .elementor-element-<?php echo $widget->get_id(); ?> .dce-form-step:first-child {
                                display: flex;
                                flex-basis: 100%;
                            }
                            .elementor-element-<?php echo $widget->get_id(); ?> .dce-form-step .dce-form-step-bnt-prev {
                                justify-content: left;
                                float: left;
                            }
                            .elementor-element-<?php echo $widget->get_id(); ?> .dce-form-step .dce-form-step-bnt-next {
                                justify-content: right;
                            }
                    <?php } ?>

                        /*progressbar*/
                        .dce-form-progressbar {
                            position: relative;
                            display: flex;
                            justify-content: space-between;
                            margin: 0;
                        }
                        .dce-form-progressbar li {
                            list-style: none;
                        }
                    </style>
                    <?php
                    $new_content .= ob_get_contents();
                    ob_end_clean();
                    
                    $new_content = $this->_progressbar($widget) . $new_content;
                }
                
            }

            return $new_content;
        }

    }

}
