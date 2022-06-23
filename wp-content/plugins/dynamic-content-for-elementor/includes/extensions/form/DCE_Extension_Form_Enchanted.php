<?php

namespace DynamicContentForElementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

function _dce_extension_form_enchanted($field) {
    switch ($field) {
        case 'enabled':
            return true;
        case 'docs':
            return 'https://www.dynamic.ooo/widget/enchanted-form-for-elementor-pro-form/';
        case 'description' :
            return __('Add Select2, Password Check, Icons and more in Elementor PRO Form', 'dynamic-content-for-elementor');
    }
}

if (!DCE_Helper::is_plugin_active('elementor-pro')) {

    class DCE_Extension_Form_Enchanted extends DCE_Extension_Prototype {

        public $name = 'Form Enchanted';
        private $is_common = false;
        public static $depended_plugins = ['elementor-pro'];

        static public function is_enabled() {
            return _dce_extension_form_enchanted('enabled');
        }

        public static function get_description() {
            return _dce_extension_form_enchanted('description');
        }

        public function get_docs() {
            return _dce_extension_form_enchanted('docs');
        }

    }

} else {

    class DCE_Extension_Form_Enchanted extends DCE_Extension_Prototype {

        public $name = 'Form Enchanted';
        public static $depended_plugins = ['elementor-pro'];
        public static $docs = 'https://www.dynamic.ooo/';
        private $is_common = false;
        public $has_action = false;

        static public function is_enabled() {
            return _dce_extension_form_enchanted('enabled');
        }

        public static function get_description() {
            return _dce_extension_form_enchanted('description');
        }

        public function get_docs() {
            return _dce_extension_form_enchanted('docs');
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
            return 'dce_form_enchanted';
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
            return __('Form Enchanted', 'dynamic-content-for-elementor');
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
            
            add_action( 'elementor/widget/print_template', function( $template, $widget ) {
               if ( 'form' === $widget->get_name() ) {
                    $template = false;
               }
               return $template;
            }, 10, 2 );

            wp_register_script(
                    'jquery-elementor-select2',
                    ELEMENTOR_ASSETS_URL . 'lib/e-select2/js/e-select2.full.min.js',
                    [
                        'jquery',
                    ],
                    '4.0.6-rc.1',
                    true
            );


            wp_register_style(
                    'elementor-select2',
                    ELEMENTOR_ASSETS_URL . 'lib/e-select2/css/e-select2.min.css',
                    [],
                    '4.0.6-rc.1'
            );
            
            wp_register_style(
                    'font-awesome',
                    ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/font-awesome.min.css',
                    [],
                    '4.7.0'
		);
            wp_register_style(
                    'fontawesome',
                    ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/fontawesome.min.css',
                    [],
                    '5.9.0'
		);
        }

        public function _render_form($content, $widget) {
            $new_content = $content;
            if ($widget->get_name() == 'form') {
                $new_content = self::_add_icon($new_content, $widget);
                $new_content = self::_add_description($new_content, $widget);
                $new_content = self::_add_select2($new_content, $widget);
                $new_content = self::_add_password_visibility($new_content, $widget);
                $new_content = self::_add_inline_align($new_content, $widget);
            }
            return $new_content;
        }

        public static function _add_inline_align($content, $widget) {
            $new_content = $content;
            $settings = $widget->get_settings_for_display();
            $add_js = '<script>jQuery(document).ready(function(){';
            $has_js = false;
            foreach ($settings['form_fields'] as $key => $afield) {
                if ($afield["field_type"] == 'radio' || $afield["field_type"] == 'checkbox') {
                    if (!empty($afield['inline_align'])) {
                        $has_js = true;
                        $add_js .= "jQuery('.elementor-field-group-" . $afield['custom_id'] . "').addClass('elementor-repeater-item-".$afield['_id']."');";
                    }
                }
            }
            $add_js .= '});</script>';
            if ($has_js) {
                return $new_content . $add_js;
            }
            return $new_content;
        }
        
        public static function _add_select2($content, $widget) {
            $new_content = $content;
            $settings = $widget->get_settings_for_display();
            $add_js = '<script>jQuery(document).ready(function(){';
            $has_select2 = false;
            foreach ($settings['form_fields'] as $key => $afield) {
                if ($afield["field_type"] == 'select') {
                    if (!empty($afield['field_select2'])) {
                        $has_select2 = true;
                        $add_js .= "jQuery('#form-field-" . $afield['custom_id'] . "').select2({containerCssClass: jQuery('#form-field-" . $afield['custom_id'] . "').attr('class')});";
                    }
                }
            }
            $add_js .= "jQuery('.elementor-element-" . $widget->get_id() . " .select2-selection__arrow').remove();";
            $add_js .= '});</script>';
            if ($has_select2) {
                wp_enqueue_script('jquery-elementor-select2');
                wp_enqueue_style('elementor-select2');
                return $new_content . $add_js;
            }
            return $new_content;
        }

        public static function _add_password_visibility($content, $widget) {
            $new_content = $content;
            $settings = $widget->get_settings_for_display();
            $add_js = '<script>jQuery(document).ready(function(){' . PHP_EOL;
            $has_select2 = false;
            foreach ($settings['form_fields'] as $key => $afield) {
                if ($afield["field_type"] == 'password') {
                    if (!empty($afield['field_psw_visiblity'])) {
                        $has_select2 = true;
                        //$add_js .= "alert('#form-field-".$afield['custom_id']."');".PHP_EOL;
                        $add_js .= "jQuery('#form-field-" . $afield['custom_id'] . "').addClass('dce-form-password-toggle');" . PHP_EOL;
                    }
                }
            }
            if ($has_select2) {
                wp_enqueue_style('font-awesome');
                //$add_js .= "alert('psw');".PHP_EOL;
                $add_js .= "jQuery('.elementor-element-" . $widget->get_id() . " .dce-form-password-toggle').each(function() {" . PHP_EOL;
                $add_js .= "jQuery(this).wrap('<div class=\"elementor-field-input-wrapper elementor-field-input-wrapper-" . $afield['custom_id'] . "\"></div>');";
                $add_js .= "jQuery(this).parent().append('<span class=\"fa far fa-eye-slash field-icon dce-toggle-password\"></span>');";
                $add_js .= "jQuery(this).next('.dce-toggle-password').on('click', function(){ "
                        . "var input_psw = jQuery(this).prev(); "
                        . "if (input_psw.attr('type') == 'password') { "
                        . "input_psw.attr('type', 'text'); } else { "
                        . "input_psw.attr('type', 'password'); } "
                        . "jQuery(this).toggleClass('fa-eye').toggleClass('fa-eye-slash');"
                        . "});";
                $add_js .= "});" . PHP_EOL;
            }
            $add_js .= '});</script>' . PHP_EOL;
            if ($has_select2) {
                return $new_content . $add_js;
            }
            return $new_content;
        }

        public static function _add_icon($content, $widget) {
            $new_content = $content;
            $settings = $widget->get_settings_for_display();

            // Using the reader to dynamically get the icons array. It's resource intensive and you must cache the result.
            $css_path = ELEMENTOR_ASSETS_PATH . 'lib/font-awesome/css/fontawesome.css';
            $icons_fa = new \Awps\FontAwesomeReader($css_path);
            /*
              $css_path = ELEMENTOR_ASSETS_PATH . 'lib/font-awesome/css/regular.css';
              $icons_far    = new \Awps\FontAwesomeReader( $css_path );
              $css_path = ELEMENTOR_ASSETS_PATH . 'lib/font-awesome/css/solid.css';
              $icons_fas    = new \Awps\FontAwesomeReader( $css_path );
              $css_path = ELEMENTOR_ASSETS_PATH . 'lib/font-awesome/css/brands.css';
              $icons_fab    = new \Awps\FontAwesomeReader( $css_path );
             */
            // .... or better use the static class
            //$icons = new \Awps\FontAwesome();            

            $add_css = '<style>';
            $add_js = '<script>jQuery(document).ready(function(){' . PHP_EOL;
            $has_icon = false;
            foreach ($settings['form_fields'] as $key => $afield) {
                //if ($afield["field_type"] == 'select') {
                if (!empty($afield['field_icon'])) {
                    wp_enqueue_style('fontawesome');
                    //var_dump($afield['field_icon']);
                    $fa_classes = explode(' ', $afield['field_icon']['value']);
                    $fa_family = reset($fa_classes);
                    $fa_class = end($fa_classes);
                    $fa_family_name = 'Font Awesome 5 Free';
                    $fa_weight = 400;
                    $fa_unicode = $icons_fa->getIconUnicode($fa_class);
                    switch ($fa_family) {
                        case 'far':
                            //$fa_unicode = $icons_far->getIconUnicode($fa_class);                            
                            break;
                        case 'fas':
                            $fa_weight = 900;
                            //$fa_unicode = $icons_fas->getIconUnicode($fa_class);
                            break;
                        case 'fab':
                            $fa_family_name = "Font Awesome 5 Brands";
                            //$fa_unicode = $icons_fab->getIconUnicode($fa_class);
                            break;
                        default:
                            $fa_unicode = $icons_fa->getIconUnicode($fa_class);
                    }
                    $has_icon = true;
                    if ($afield['field_icon_position'] == 'elementor-field-label') {
                        $add_css .= ".elementor-element-" . $widget->get_id() . " .elementor-field-group-" . $afield['custom_id'] . " .elementor-field-label:before { content: '" . $fa_unicode . "'; font-family: \"" . $fa_family_name . "\"; font-weight: " . $fa_weight . "; margin-right: 5px; }";
                    }
                    if ($afield['field_icon_position'] == 'elementor-field') {
                        //$add_js .= "alert('#form-field-".$afield['custom_id']."');";
                        $add_js .= "jQuery('.elementor-element-" . $widget->get_id() . " #form-field-" . $afield['custom_id'] . "').wrap('<div class=\"elementor-field-input-wrapper elementor-field-input-wrapper-" . $afield['custom_id'] . "\"></div>');";
                        switch ($afield['field_type']) {
                            case 'textarea':
                                $add_css .= ".elementor-element-" . $widget->get_id() . " .elementor-field-input-wrapper-" . $afield['custom_id'] . ":after { content: '" . $fa_unicode . "'; font-family: \"" . $fa_family_name . "\"; font-weight: " . $fa_weight . "; position: absolute; top: 5px; left: 16px; }";
                                break;
                            default:
                                $add_css .= ".elementor-element-" . $widget->get_id() . " .elementor-field-input-wrapper-" . $afield['custom_id'] . ":after { content: '" . $fa_unicode . "'; font-family: \"" . $fa_family_name . "\"; font-weight: " . $fa_weight . "; position: absolute; top: 50%; transform: translateY(-50%); left: 16px; }";
                        }                        
                        $add_css .= ".elementor-element-" . $widget->get_id() . " #form-field-" . $afield['custom_id'] . ", .elementor-element-" . $widget->get_id() . " .elementor-field-group-" . $afield['custom_id'] . " .elementor-field-textual { padding-left: 42px; }";
                    }
                }
                //}
            }
            $add_css .= '</style>';
            $add_js .= '});</script>' . PHP_EOL;
            if ($has_icon) {
                return $new_content . $add_css . $add_js;
            }
            return $new_content;
        }

        public static function _add_description($content, $widget) {
            $new_content = $content;
            $settings = $widget->get_settings_for_display();
            $add_css = '<style>.elementor-element.elementor-element-' . $widget->get_id() . ' .elementor-field-group { align-self: flex-start; }</style>';
            $add_js = '<script>jQuery(document).ready(function(){' . PHP_EOL;
            $has_description = false;
            foreach ($settings['form_fields'] as $key => $afield) {
                if (!empty($afield['field_description']) && $afield['field_description_position'] != 'no-description') {
                    $has_description = true;
                    $field_description = str_replace("'", "\\'", $afield['field_description']);
                    $field_description = preg_replace('/\s+/', ' ', trim($field_description));
                    if ($afield['field_description_position'] == 'elementor-field-label') {
                        $add_js .= "jQuery('.elementor-element-" . $widget->get_id() . " .elementor-field-group-" . $afield['custom_id'] . " .elementor-field-label').wrap('<abbr class=\"elementor-field-label-description elementor-field-label-description-" . $afield['custom_id'] . "\" title=\"".$field_description."\"></abbr>');";
                        //$add_css = '<style>.elementor-element-' . $widget->get_id() . ' </style>';
                    }
                    if ($afield['field_description_position'] == 'elementor-field') {
                        //$add_js .= "alert('#form-field-".$afield['custom_id']."');";
                        $add_js .= "jQuery('.elementor-element-" . $widget->get_id() . " .elementor-field-group-" . $afield['custom_id'] . "').append('<div class=\"elementor-field-input-description elementor-field-input-description-" . $afield['custom_id'] . "\">".$field_description."</div>');";
                    }
                }
                //}
            }
            $add_js .= '});</script>' . PHP_EOL;
            if ($has_description) {
                return $new_content . $add_css . $add_js;
            }
            return $new_content;
        }
        
        public static function _add_to_form(Controls_Stack $element, $control_id, $control_data, $options = []) {
            
            if ($element->get_name() == 'form' && $control_id == 'form_fields') {
                $control_data['fields']['form_fields_enchanted_tab'] = array(
                        "type" => "tab",
                        "tab" => "enchanted",
                        "label" => '<i class="fa fa-magic" aria-hidden="true"></i>', //__('Enchanted', 'dynamic-content-for-elementor'),
                        "tabs_wrapper" => "form_fields_tabs",
                        "name" => "form_fields_enchanted_tab",
                        'condition' => [
                            'field_type!' => 'step',
                        ],
                    );
            }
            
            $control_data = self::_add_form_select2($element, $control_id, $control_data, $options);
            $control_data = self::_add_form_password_visibility($element, $control_id, $control_data, $options);            
            $control_data = self::_add_form_inline_align($element, $control_id, $control_data, $options);
            $control_data = self::_add_form_icon($element, $control_id, $control_data, $options);
            $control_data = self::_add_form_description($element, $control_id, $control_data, $options);
            $control_data = self::_add_form_btn_style($element, $control_id, $control_data, $options);
            return $control_data;
        }

        public static function _add_form_select2(Controls_Stack $element, $control_id, $control_data, $options = []) {

            if ($element->get_name() == 'form') {

                if ($control_id == 'form_fields') {
                    $control_data['fields']['field_select2'] = array(
                        'name' => 'field_select2',
                        'label' => __('Enable Select2', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SWITCHER,
                        'return_value' => 'true',
                        'default' => 'true',
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'field_type',
                                    'value' => 'select',
                                ],
                            ],
                        ],
                        "tabs_wrapper" => "form_fields_tabs",
                        "inner_tab" => "form_fields_enchanted_tab",
                        "tab" => "enchanted",
                    );
                }

                // apply same style
                if ($control_id == 'field_background_color') {
                    $control_data['selectors']['{{WRAPPER}} .elementor-field-group .elementor-select-wrapper .select2'] = 'background-color: {{VALUE}};';
                    $control_data['selectors']['{{WRAPPER}} .elementor-field-group .elementor-select-wrapper .select2 .elementor-field-textual'] = 'background-color: {{VALUE}};';
                }
                if ($control_id == 'field_border_color') {
                    $control_data['selectors']['{{WRAPPER}} .elementor-field-group .elementor-select-wrapper .select2'] = 'border-color: {{VALUE}};';
                    $control_data['selectors']['{{WRAPPER}} .elementor-field-group .elementor-select-wrapper .select2 .elementor-field-textual'] = 'border-color: {{VALUE}};';
                }
                if ($control_id == 'field_border_width') {
                    $control_data['selectors']['{{WRAPPER}} .elementor-field-group .elementor-select-wrapper .select2'] = 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};';
                    $control_data['selectors']['{{WRAPPER}} .elementor-field-group .elementor-select-wrapper .select2 .elementor-field-textual'] = 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};';
                }
                if ($control_id == 'field_border_radius') {
                    $control_data['selectors']['{{WRAPPER}} .elementor-field-group .elementor-select-wrapper .select2'] = 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};';
                    $control_data['selectors']['{{WRAPPER}} .elementor-field-group .elementor-select-wrapper .select2 .elementor-field-textual'] = 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};';
                }
            }


            return $control_data;
        }

        public static function _add_form_btn_style(Controls_Stack $element, $control_id, $control_data, $options = []) {
            if ($element->get_name() == 'form') {
                if ($control_id == 'button_text_padding') {
                    $element->add_control(
                            'button_margin',
                            [
                                'label' => __('Margin', 'elementor'),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => ['px', 'em', '%'],
                                'selectors' => [
                                    '{{WRAPPER}} .elementor-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                            ]
                    );
                }
            }
            return $control_data;
        }

        public static function _add_form_password_visibility(Controls_Stack $element, $control_id, $control_data, $options = []) {

            if ($element->get_name() == 'form') {

                if ($control_id == 'form_fields') {
                    $control_data['fields']['field_psw_visiblity'] = array(
                        'name' => 'field_psw_visiblity',
                        'label' => __('Enable Password Visible', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SWITCHER,
                        'return_value' => 'true',
                        'default' => 'true',
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'field_type',
                                    'value' => 'password',
                                ],
                            ],
                        ],
                        "tabs_wrapper" => "form_fields_tabs",
                        "inner_tab" => "form_fields_enchanted_tab",
                        "tab" => "enchanted",
                    );
                }
                /*
                  // apply same style
                  if ($control_id == 'field_background_color') {
                  $control_data['selectors']['{{WRAPPER}} .elementor-field-group .elementor-select-wrapper .select2'] = 'background-color: {{VALUE}};';
                  $control_data['selectors']['{{WRAPPER}} .elementor-field-group .elementor-select-wrapper .select2 .elementor-field-textual'] = 'background-color: {{VALUE}};';
                  }
                  if ($control_id == 'field_border_color') {
                  $control_data['selectors']['{{WRAPPER}} .elementor-field-group .elementor-select-wrapper .select2'] = 'border-color: {{VALUE}};';
                  $control_data['selectors']['{{WRAPPER}} .elementor-field-group .elementor-select-wrapper .select2 .elementor-field-textual'] = 'border-color: {{VALUE}};';
                  }
                  if ($control_id == 'field_border_width') {
                  $control_data['selectors']['{{WRAPPER}} .elementor-field-group .elementor-select-wrapper .select2'] = 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};';
                  $control_data['selectors']['{{WRAPPER}} .elementor-field-group .elementor-select-wrapper .select2 .elementor-field-textual'] = 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};';
                  }
                  if ($control_id == 'field_border_radius') {
                  $control_data['selectors']['{{WRAPPER}} .elementor-field-group .elementor-select-wrapper .select2'] = 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};';
                  $control_data['selectors']['{{WRAPPER}} .elementor-field-group .elementor-select-wrapper .select2 .elementor-field-textual'] = 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};';
                  }
                 */
            }


            return $control_data;
        }

        public static function _add_form_icon(Controls_Stack $element, $control_id, $control_data, $options = []) {

            if ($element->get_name() == 'form') {

                if ($control_id == 'form_fields') {
                    $control_data['fields']['field_icon_position'] = array(
                        'name' => 'field_icon_position',
                        'label' => __('Icon', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::CHOOSE,
                        'options' => [
                            'no-icon' => [
                                'title' => __('No Icon', 'dynamic-content-for-elementor'),
                                'icon' => 'fa fa-times',
                            ],
                            'elementor-field-label' => [
                                'title' => __('On Label', 'dynamic-content-for-elementor'),
                                'icon' => 'fa fa-tag',
                            ],
                            'elementor-field' => [
                                'title' => __('On Input', 'dynamic-content-for-elementor'),
                                'icon' => 'fa fa-square-o',
                            ]
                        ],
                        'toggle' => false,
                        'default' => 'no-icon',
                        "tabs_wrapper" => "form_fields_tabs",
                        "inner_tab" => "form_fields_enchanted_tab",
                        "tab" => "enchanted",
                    );
                    $control_data['fields']['field_icon'] = array(
                        'name' => 'field_icon',
                        'label' => __('Select Icon', 'elementor'),
                        'type' => Controls_Manager::ICONS,
                        'label_block' => true,
                        'fa4compatibility' => 'icon',
                        'condition' => [
                            'field_icon_position!' => 'no-icon',
                        ],
                        "tabs_wrapper" => "form_fields_tabs",
                        "inner_tab" => "form_fields_enchanted_tab",
                        "tab" => "enchanted",
                    );
                }
                
                if ($control_id == 'field_background_color') {
                    $element->add_control(
			'field_icon_color',
                            [
                                'label' => __( 'Icon Color', 'elementor-pro' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .elementor-field-input-wrapper:after' => 'color: {{VALUE}};',
                                ],
                                'separator' => 'before',
                            ]
                    );
                }
                if ($control_id == 'mark_required_color') {
                    $element->add_control(
			'label_icon_color',
                            [
                                'label' => __( 'Icon Color', 'elementor-pro' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .elementor-field-label:before' => 'color: {{VALUE}};',
                                ],
                            ]
                    );
                }
                
            }

            return $control_data;
        }
        
        public static function _add_form_description(Controls_Stack $element, $control_id, $control_data, $options = []) {

            if ($element->get_name() == 'form') {

                if ($control_id == 'form_fields') {
                    $control_data['fields']['field_description_position'] = array(
                        'name' => 'field_description_position',
                        'label' => __('Description', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::CHOOSE,
                        'options' => [
                            'no-description' => [
                                'title' => __('No Description', 'dynamic-content-for-elementor'),
                                'icon' => 'fa fa-times',
                            ],
                            'elementor-field-label' => [
                                'title' => __('On Label', 'dynamic-content-for-elementor'),
                                'icon' => 'fa fa-tag',
                            ],
                            'elementor-field' => [
                                'title' => __('Below Input', 'dynamic-content-for-elementor'),
                                'icon' => 'fa fa-square-o',
                            ]
                        ],
                        'toggle' => false,
                        'default' => 'no-description',
                        "tabs_wrapper" => "form_fields_tabs",
                        "inner_tab" => "form_fields_enchanted_tab",
                        "tab" => "enchanted",
                    );
                    $control_data['fields']['field_description'] = array(
                        'name' => 'field_description',
                        'label' => __('Description HTML', 'elementor'),
                        'type' => Controls_Manager::TEXTAREA,
                        'label_block' => true,
                        'fa4compatibility' => 'icon',
                        'condition' => [
                            'field_description_position!' => 'no-description',
                        ],
                        "tabs_wrapper" => "form_fields_tabs",
                        "inner_tab" => "form_fields_enchanted_tab",
                        "tab" => "enchanted",
                    );
                }
                
                if ($control_id == 'field_background_color') {
                    $element->add_control(
			'field_description_color',
                            [
                                'label' => __( 'Description Color', 'elementor-pro' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .elementor-field-input-description' => 'color: {{VALUE}};',
                                ],
                                'separator' => 'before',
                            ]
                    );
                    $element->add_group_control(
                            Group_Control_Typography::get_type(), [
                        'name' => 'field_description_typography',
                        'label' => __('Typography', 'dynamic-content-for-elementor'),
                        'selector' => '{{WRAPPER}} .elementor-field-input-description',
                            ]
                    );
                /*}
                if ($control_id == 'mark_required_color') {*/
                    $element->add_control(
			'label_description_color',
                            [
                                'label' => __( 'Label Description Color', 'elementor-pro' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .elementor-field-label-description:after' => "
                                            content: '?';
                                            display: inline-block;
                                            border-radius: 50%;
                                            padding: 2px 0;
                                            height: 1.2em;
                                            line-height: 1;
                                            font-size: 80%;
                                            width: 1.2em;
                                            text-align: center;
                                            margin-left: 0.2em;
                                            color: {{VALUE}};",
                                ],
                                'separator' => 'before',
                                'default' => '#ffffff',
                            ]
                    );
                    $element->add_control(
			'label_description_bgcolor',
                            [
                                'label' => __( 'Label Description Background Color', 'elementor-pro' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .elementor-field-label-description:after' => 'background-color: {{VALUE}};',
                                ],
                                'default' => '#666666',
                            ]
                    );
                }
                
            }

            return $control_data;
        }

        public static function _add_form_inline_align(Controls_Stack $element, $control_id, $control_data, $options = []) {

            if ($element->get_name() == 'form') {

                if ($control_id == 'form_fields') {
                    $control_data['fields']['inline_align'] = array(
                        'name' => 'inline_align',
                        'label' => __('Inline align', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::CHOOSE,
                        'options' => [
                            'flex-start' => [
                                'title' => __('Left', 'elementor-pro'),
                                'icon' => 'eicon-text-align-left',
                            ],
                            'center' => [
                                'title' => __('Center', 'elementor-pro'),
                                'icon' => 'eicon-text-align-center',
                            ],
                            'flex-end' => [
                                'title' => __('Right', 'elementor-pro'),
                                'icon' => 'eicon-text-align-right',
                            ],
                            'space-around' => [
                                'title' => __('Around', 'elementor-pro'),
                                'icon' => 'eicon-text-align-justify',
                            ],
                            'space-evenly' => [
                                'title' => __('Evenly', 'elementor-pro'),
                                'icon' => 'eicon-text-align-justify',
                            ],
                            'space-between' => [
                                'title' => __('Between', 'elementor-pro'),
                                'icon' => 'eicon-text-align-justify',
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} {{CURRENT_ITEM}} .elementor-subgroup-inline' => 'width: 100%; justify-content: {{VALUE}};',
                            //'{{WRAPPER}} .elementor-subgroup-inline' => 'justify-content: {{VALUE}};',
                        ],
                        'render_type' => 'ui',
                        'condition' => [
                            'field_type' => ['checkbox', 'radio'],
                            'inline_list!' => '',
                        ],
                        "tabs_wrapper" => "form_fields_tabs",
                        "inner_tab" => "form_fields_enchanted_tab",
                        "tab" => "enchanted",
                    );
                }
            }


            return $control_data;
        }

    }

}