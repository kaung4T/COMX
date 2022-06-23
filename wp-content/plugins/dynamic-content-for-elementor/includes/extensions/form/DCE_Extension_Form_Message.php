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

function _dce_extension_form_message($field) {
    switch ($field) {
        case 'enabled':
            return true;
        case 'docs':
            return 'https://www.dynamic.ooo/widget/message-generator-for-elementor-pro-form/';
        case 'description' :
            return __('Add custom Message to Elementor PRO Form', 'dynamic-content-for-elementor');
    }
}

if (!DCE_Helper::is_plugin_active('elementor-pro')) {

    class DCE_Extension_Form_Message extends DCE_Extension_Prototype {

        public $name = 'Form Message';
        private $is_common = false;
        public static $depended_plugins = ['elementor-pro'];

        static public function is_enabled() {
            return _dce_extension_form_message('enabled');
        }

        public static function get_description() {
            return _dce_extension_form_message('description');
        }

        public function get_docs() {
            return _dce_extension_form_message('docs');
        }

    }

} else {

    class DCE_Extension_Form_Message extends \ElementorPro\Modules\Forms\Classes\Action_Base {

        public $name = 'Form Message';
        public static $depended_plugins = ['elementor-pro'];
        public static $docs = 'https://www.dynamic.ooo/widget/message-generator-for-elementor-pro-form/';
        public $has_action = true;

        static public function is_enabled() {
            return _dce_extension_form_message('enabled');
        }

        public static function get_description() {
            return _dce_extension_form_message('description');
        }

        public function get_docs() {
            return _dce_extension_form_message('docs');
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
            return 'dce_form_message';
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
            return __('Message', 'dynamic-content-for-elementor');
        }

        /**
         * Register Settings Section
         *
         * Registers the Action controls
         *
         * @access public
         * @param \Elementor\Widget_Base $widget
         */
        public function register_settings_section($widget) {

            $widget->start_controls_section(
                    'section_dce_form_message',
                    [
                        'label' => $this->get_label(), //__('DCE', 'dynamic-content-for-elementor'),
                        'condition' => [
                            'submit_actions' => $this->get_name(),
                        ],
                    ]
            );

            $widget->add_control(
                    'dce_form_message_type', [
                'label' => __('Message type', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'text' => [
                        'title' => __('Text', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'template' => [
                        'title' => __('Template', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-th-large',
                    ]
                ],
                'toggle' => false,
                'default' => 'text',
                    ]
            );

            $widget->add_control(
                    'dce_form_message_text', [
                'label' => __('Message Text', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => __('Thanks to submit this form', 'dynamic-content-for-elementor'),
                'label_block' => true,
                'condition' => [
                    'dce_form_message_type' => 'text',
                ],
                    ]
            );

            $widget->add_control(
                    'dce_form_message_text_floating',
                    [
                        'label' => __('Floating message', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SWITCHER,
                        'selectors' => [
                            '{{WRAPPER}} .elementor-message' => 'position: fixed; display: block; z-index: 100; bottom: 0;',
                        ],
                        'condition' => [
                            'dce_form_message_type' => 'text',
                        ],
                    ]
            );
            $widget->add_control(
                    'dce_form_message_text_floating_align', [
                'label' => __('Floationg Position', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'dynamic-content-for-elementor'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    /* 'center' => [
                      'title' => __('Center', 'dynamic-content-for-elementor'),
                      'icon' => 'eicon-h-align-center',
                      ], */
                    'right' => [
                        'title' => __('Right', 'dynamic-content-for-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'right',
                'selectors' => [
                    '{{WRAPPER}} .elementor-message' => '{{VALUE}}: 0;',
                ],
                'condition' => [
                    'dce_form_message_type' => 'text',
                    'dce_form_message_text_floating!' => '',
                ],
                    ]
            );

            $widget->add_control(
                    'dce_form_message_template',
                    [
                        'label' => __('Template', 'dynamic-content-for-elementor'),
                        'type' => 'ooo_query',
                        'placeholder' => __('Template Name', 'dynamic-content-for-elementor'),
                        'label_block' => true,
                        'query_type' => 'posts',
                        'object_type' => 'elementor_library',
                        'description' => 'Use a Elementor Template as body fo this Email.',
                        'condition' => [
                            'dce_form_message_type' => 'template',
                        ],
                    ]
            );

            $widget->add_control(
                    'dce_form_message_post',
                    [
                        'label' => __('Post', 'dynamic-content-for-elementor'),
                        'type' => 'ooo_query',
                        'placeholder' => __('Select a Post', 'dynamic-content-for-elementor'),
                        'label_block' => true,
                        'query_type' => 'posts',
                        'description' => __('Force a Post as Template content for Dynamic fields. Leave empty for use current Page.', 'dynamic-content-for-elementor'),
                        'condition' => [
                            'dce_form_message_type' => 'template',
                        ],
                    ]
            );
            $widget->add_control(
                    'dce_form_message_user',
                    [
                        'label' => __('User', 'dynamic-content-for-elementor'),
                        'type' => 'ooo_query',
                        'placeholder' => __('Select a User', 'dynamic-content-for-elementor'),
                        'label_block' => true,
                        'query_type' => 'users',
                        'description' => __('Force a User as Template content for Dynamic fields. Leave empty for use current User.', 'dynamic-content-for-elementor'),
                        'condition' => [
                            'dce_form_message_type' => 'template',
                        ],
                    ]
            );

            $widget->add_control(
                    'dce_form_message_close', [
                'label' => __('Add close button to message', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                    ]
            );
            $widget->add_control(
                    'dce_form_message_close_position', [
                'label' => __('Close button Position', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'right' => [
                        'title' => __('Right', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-right',
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-message-dce' => 'position: relative;',
                    '{{WRAPPER}} .elementor-message-btn-dismiss' => 'position: absolute; top: 0; {{VALUE}}: 0; cursor: pointer;',
                ],
                'toggle' => false,
                'default' => 'right',
                'condition' => [
                    'dce_form_message_close!' => '',
                ]
                    ]
            );

            $widget->add_control(
                    'dce_form_message_hide', [
                'label' => __('Hide Form after submit', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                    ]
            );

            $widget->add_control(
                    'dce_form_message_help', [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div id="elementor-panel__editor__help" class="p-0"><a id="elementor-panel__editor__help__link" href="' . $this->get_docs() . '" target="_blank">' . __('Need Help', 'elementor') . ' <i class="eicon-help-o"></i></a></div>',
                'separator' => 'before',
                    ]
            );

            $widget->end_controls_section();
        }

        /**
         * Run
         *
         * Runs the action after submit
         *
         * @access public
         * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
         * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
         */
        public function run($record, $ajax_handler) {
            $settings = $record->get('form_settings');

            $fields = DCE_Helper::get_form_data($record);
            $settings = DCE_Helper::get_dynamic_value($settings, $fields);

            $this->dce_elementor_form_message($fields, $settings, $ajax_handler);
        }

        /**
         * On Export
         *
         * Clears form settings on export
         * @access Public
         * @param array $element
         */
        public function on_export($element) {
            $tmp = array();
            if (!empty($element)) {
                foreach ($element as $key => $value) {
                    if (substr($key, 0, 4) == 'dce_') {
                        $element[$key];
                    }
                }
            }
        }

        function dce_elementor_form_message($fields, $settings = null, $ajax_handler = null) {

            $element_id = $settings['id'];

            $message_html = '';
            if ($settings['dce_form_message_type'] == 'template') {
                if (!empty($settings['dce_form_message_template'])) {
                    $dce_short = '[dce-elementor-template id="' . $settings['dce_form_message_template'] . '"';
                    if (!empty($settings['dce_form_message_post'])) {
                        $dce_short .= ' post_id="' . $settings['dce_form_message_post'] . '"]';
                    }
                    if (!empty($settings['dce_form_message_user'])) {
                        $dce_short .= ' author_id="' . $settings['dce_form_message_user'] . '"]';
                    }
                    $dce_short .= ' inlinecss="true"';
                    $dce_short .= ']';

                    $message_html = do_shortcode($dce_short);
                    $message_html = '</div><div class="elementor-message-dce" role="alert">' . $message_html;
                    $message_html .= '<style>.elementor-element-' . $element_id . ' .elementor-form .elementor-message {display: none !important;}</style>';
                }
            } else {
                $message_html = $settings['dce_form_message_text'];
                $message_html .= '<style>.elementor-form .elementor-message{position: relative;}.elementor-form .elementor-message::before{float: left;}</style>';
            }

            if ($settings['dce_form_message_close']) {
                $message_html .= '<div class="elementor-message-btn-dismiss" onClick="jQuery(this).parent().fadeOut();"><i class="eicon-editor-close" aria-hidden="true"></i></div>';
            }

            if ($settings['dce_form_message_hide']) {
                $message_html .= '<style>.elementor-element-' . $element_id . ' .elementor-form-fields-wrapper {display: none !important;}</style>';
            }

            $message_html = DCE_Helper::get_dynamic_value($message_html, $fields);

            //$ajax_handler->add_success_message($message_html);
            //$ajax_handler->messages['success'] = array($message_html);
            //$ajax_handler->is_success = true;
            if ($ajax_handler->is_success) {
                wp_send_json_success([
                    'message' => $message_html,
                    'data' => $ajax_handler->data,
                ]);
                die();
            }
            $ajax_handler->add_error_message($message_html);
        }

        public static function _add_to_form(Controls_Stack $element, $control_id, $control_data, $options = []) {
            if ($control_id == 'success_message_color') {
                $element->add_control(
                        'success_message_header',
                        [
                            'label' => __('Success Message', 'dynamic-content-for-elementor'),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                        ]
                );
                $element->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'success_message_bgcolor',
                            'types' => ['classic', 'gradient'],
                            'selector' => '{{WRAPPER}} .elementor-message.elementor-message-success',
                        ]
                );
                $element->add_group_control(
                        Group_Control_Border::get_type(), [
                    'name' => 'success_message_border',
                    'selector' => '{{WRAPPER}} .elementor-message.elementor-message-success',
                        ]
                );
                $element->add_control(
                        'success_message_border_radius',
                        [
                            'label' => __('Border Radius', 'elementor-pro'),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .elementor-message.elementor-message-success' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                );
                $element->add_responsive_control(
                        'success_message_padding',
                        [
                            'label' => __('Padding', 'elementor-pro'),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .elementor-message.elementor-message-success' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                );
                $element->add_responsive_control(
                        'success_message_margin',
                        [
                            'label' => __('Margin', 'elementor-pro'),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .elementor-message.elementor-message-success' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ]
                        ]
                );
                $element->add_responsive_control(
                        'success_message_width',
                        [
                            'label' => __('Width', 'elementor'),
                            'type' => Controls_Manager::SLIDER,
                            'default' => [
                                'unit' => '%',
                            ],
                            'tablet_default' => [
                                'unit' => '%',
                            ],
                            'mobile_default' => [
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
                                    'max' => 1000,
                                ],
                                'vw' => [
                                    'min' => 1,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .elementor-message.elementor-message-success' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'dce_form_message_text_floating!' => '',
                            ]
                        ]
                );
            }
            if ($control_id == 'error_message_color') {
                $element->add_control(
                        'error_message_header',
                        [
                            'label' => __('Error Message', 'dynamic-content-for-elementor'),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                        ]
                );
                $element->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'error_message_bgcolor',
                            'types' => ['classic', 'gradient'],
                            'selector' => '{{WRAPPER}} .elementor-message.elementor-message-error',
                        ]
                );
                $element->add_group_control(
                        Group_Control_Border::get_type(), [
                    'name' => 'error_message_border',
                    'selector' => '{{WRAPPER}} .elementor-message.elementor-message-error',
                        ]
                );
                $element->add_control(
                        'error_message_border_radius',
                        [
                            'label' => __('Border Radius', 'elementor-pro'),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .elementor-message.elementor-message-error' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                );
                $element->add_responsive_control(
                        'error_message_padding',
                        [
                            'label' => __('Padding', 'elementor-pro'),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .elementor-message.elementor-message-error' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                );
                $element->add_responsive_control(
                        'error_message_margin',
                        [
                            'label' => __('Margin', 'elementor-pro'),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .elementor-message.elementor-message-error' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ]
                        ]
                );
                $element->add_responsive_control(
                        'error_message_width',
                        [
                            'label' => __('Width', 'elementor'),
                            'type' => Controls_Manager::SLIDER,
                            'default' => [
                                'unit' => '%',
                            ],
                            'tablet_default' => [
                                'unit' => '%',
                            ],
                            'mobile_default' => [
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
                                    'max' => 1000,
                                ],
                                'vw' => [
                                    'min' => 1,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .elementor-message.elementor-message-error' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'dce_form_message_text_floating!' => '',
                            ]
                        ]
                );
            }
            if ($control_id == 'inline_message_color') {
                $element->add_control(
                        'inline_message_header',
                        [
                            'label' => __('Inline Message', 'dynamic-content-for-elementor'),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                        ]
                );
                $element->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'inline_message_bgcolor',
                            'types' => ['classic', 'gradient'],
                            'selector' => '{{WRAPPER}} .elementor-message.elementor-help-inline',
                        ]
                );
            }
            return $control_data;
        }

    }

}