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

function _dce_extension_form_amount($field) {
    switch ($field) {
        case 'enabled':
            return true;
        case 'docs':
            return 'https://www.dynamic.ooo/widget/amount-elementor-pro-form/';
        case 'description' :
            return __('Add Amount Field to Elementor PRO Form', 'dynamic-content-for-elementor');
    }
}

if (!DCE_Helper::is_plugin_active('elementor-pro')) {

    class DCE_Extension_Form_Amount extends DCE_Extension_Prototype {

        public $name = 'Form Amount';
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

    class DCE_Extension_Form_Amount extends DCE_Extension_Prototype {

        public $name = 'Form Amount';
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
            return 'dce_form_amount';
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
            return __('Form Amount', 'dynamic-content-for-elementor');
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
        }

        public static function _add_to_form(Controls_Stack $element, $control_id, $control_data, $options = []) {
            //echo 'adsa: '; var_dump($control_id); //die();
            if ($element->get_name() == 'form' && $control_id == 'form_fields') {
                //var_dump($control_data); die();

                $control_data["fields"]["field_type"]["options"]['amount'] = __('Amount', 'dynamic-content-for-elementor');

                if ($control_id == 'form_fields') {
                    $control_data['fields']['dce_amount_expression'] = array(
                        'name' => 'dce_amount_expression',
                        'label' => __('Amount Expression', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::TEXT,
                        'placeholder' => __('[form:field_1] * [form:field_2] + 1.4', 'dynamic-content-for-elementor'),
                        'label_block' => true,
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'field_type',
                                    'value' => 'amount',
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

        public function _render_form($content, $widget) {
            $new_content = $content;
            if ($widget->get_name() == 'form') {
                $settings = $widget->get_settings_for_display();

                // FIELDS
                $fields = $form_fields = array();
                if (!empty($settings['form_fields'])) {
                    foreach ($settings['form_fields'] as $key => $afield) {
                        $form_fields[$afield['custom_id']] = $afield;
                        if ($afield["field_type"] == 'amount') {
                            $fields[] = $afield;
                            $field_class = 'elementor-field-group-'.$afield['custom_id'];
                            $pieces = explode($field_class, $new_content, 2);
                            if (count($pieces) > 1) {
                                $tmp = explode('</div>', end($pieces), 2);
                                if (count($tmp) > 1) {
                                    $amount_field = '<input value="" type="text" name="form_fields['.$afield['custom_id'].']" id="form-field-'.$afield['custom_id'].'" disabled>';
                                    $new_content = reset($pieces).$field_class.reset($tmp).$amount_field.'</div>'.end($tmp);
                                }
                            }
                        }
                    }
                }

                if (!empty($fields)) {
                    ob_start();
                    // add custom js
                    ?>
                    <script>
                        jQuery(document).ready(function () {
                            <?php
                            $fields = array();
                            if (!empty($settings['form_fields'])) {
                                $js_expression = $afield['dce_amount_expression'];
                                foreach ($settings['form_fields'] as $key => $afield) {
                                    if ($afield["field_type"] == 'amount') {
                                        $fields_name = array();
                                        $pieces = explode('[form:', $js_expression);
                                        foreach ($pieces as $apiece) {
                                            $tmp = explode(']', $apiece, 2);
                                            if (count($tmp) > 1) {
                                                $field_name = reset($tmp);                                                
                                                $field_input_name = '.elementor-element-'.$widget->get_id().' .elementor-field-group-'.$field_name.' ';
                                                if ($form_fields[$field_name]['field_type'] == 'select') {
                                                    $field_input_name .= 'select';
                                                } else {
                                                    $field_input_name .= 'input';
                                                }
                                                $fields_name[] = $exp_field_input_name = $field_input_name;
                                                if ($form_fields[$field_name]['field_type'] == 'radio') {
                                                    $exp_field_input_name .= ':checked';
                                                }
                                                $js_expression = str_replace('[form:'.$field_name.']', '(parseFloat(jQuery("'.$exp_field_input_name.'").val())||0)', $js_expression);
                                            }
                                        }
                                        //var_dump($fields_name);
                                        if (!empty($fields_name)) { ?>
                                            console.log('<?php echo $js_expression; ?>');
                                            jQuery('<?php echo implode(', ', $fields_name); ?>').on('change', function(){
                                                jQuery('<?php echo implode(', ', $fields_name); ?>').each(function(){
                                                   console.log(jQuery(this).attr('id')); 
                                                   console.log(jQuery(this).val()); 
                                                });
                                                jQuery('#form-field-<?php echo $afield['custom_id']; ?>').val(<?php echo $js_expression; ?>);
                                            });
                                            jQuery('<?php echo reset($fields_name); ?>').trigger('change');
                                            
                                        <?php }
                                    }
                                }
                            }
                            ?>
                            //alert("It works");
                        });
                    </script>    
                    <?php
                    $new_content .= ob_get_contents();
                    ob_end_clean();
                }
                
            }

            return $new_content;
        }

    }

}
