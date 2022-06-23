<?php

namespace DynamicContentForElementor\Extensions;

use Elementor\Controls_Manager;
use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\DCE_Tokens;

/*
Email ID:
sb-wtayd730539@personal.example.com
System Generated Password:
pU;%*9&q
*/

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

function _dce_extension_form_paypal($field) {
    switch ($field) {
        case 'enabled':
            return false;
        case 'docs':
            return 'https://www.dynamic.ooo/widget/';
            // https://www.paypalobjects.com/it_IT/html/IntegrationCentre/standard.htm
        case 'description' :
            return __('Add Pay with Paypal Actions to Elementor PRO Form', 'dynamic-content-for-elementor');
    }
}

if (!DCE_Helper::is_plugin_active('elementor-pro')) {

    class DCE_Extension_Form_Paypal extends DCE_Extension_Prototype {

        public $name = 'Form Save';
        private $is_common = false;
        public static $depended_plugins = ['elementor-pro'];

        static public function is_enabled() {
            return _dce_extension_form_paypal('enabled');
        }

        public static function get_description() {
            return _dce_extension_form_paypal('description');
        }

        public function get_docs() {
            return _dce_extension_form_paypal('docs');
        }

    }

} else {

    class DCE_Extension_Form_Paypal extends \ElementorPro\Modules\Forms\Classes\Action_Base {

        public $name = 'PayPal';
        public static $depended_plugins = ['elementor-pro'];
        public static $docs = 'https://www.dynamic.ooo/widget/paypal-elementor-pro-form/';
        public $has_action = true;
        
        /**
        * Constructor
        *
        * @since 0.0.1
        *
        * @access public
        */
        public function __construct() {
            $this->init();
        }

        static public function is_enabled() {
            return _dce_extension_form_paypal('enabled');
        }

        public static function get_description() {
            return _dce_extension_form_paypal('description');
        }

        public function get_docs() {
            return _dce_extension_form_paypal('docs');
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
            return 'dce_form_paypal';
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
            return __('PayPal', 'dynamic-content-for-elementor');
        }

        public function init($param = null) {
            if (_dce_extension_form_paypal('enabled')) {
            //add_action( 'elementor_pro/init', function() {
                add_action("elementor/frontend/widget/before_render", array($this, '_before_render_form'));
                add_action("elementor/widget/render_content", array($this, '_render_form'), 10, 2);
                add_action( 'elementor_pro/forms/render/item', [ $this, 'set_field_value' ], 10, 3 );
                //add_filter( 'elementor_pro/forms/render/item/text', array($this, 'set_field_value'), 10, 3 );
            //});
            }
        }
        
        public static function is_from_paypal($widget) {
            $settings = $widget->get_settings_for_display();
            $referer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : false; // HTTPS (paypal) to HTTP (maybe your site) return false value
            return ($referer && strpos($referer, 'paypal.com') !== false) || ($settings['dce_form_paypal_sandbox'] && self::is_paypal_completed());
        }
        
        public static function is_paypal_completed() {
            return true || isset($_POST['payment_status']) && $_POST['payment_status'] == 'Completed';
        }
        
        public function on_paypal_return($content, $widget) {
            $settings = $widget->get_settings_for_display();
            //$settings = DCE_Helper::get_settings_by_id($item_number);
            
            // show thank you page
            
            //var_dump($_POST);
            // save data
            /*
            //var_dump($_POST);
            ["payer_email"]=> string(35) "sb-wtayd730539@personal.example.com" 
            ["payer_id"]=> string(13) "KW7NYFBT3VJ8J" 
            ["payer_status"]=> string(8) "VERIFIED" 
            ["first_name"]=> string(4) "John" 
            ["last_name"]=> string(3) "Doe" 
            ["address_name"]=> string(8) "John Doe" 
            ["address_street"]=> string(19) "Via Unit? d\'Italia" 
            ["address_city"]=> string(6) "Napoli" 
            ["address_state"]=> string(6) "Napoli" 
            ["address_country_code"]=> string(2) "IT" 
            ["address_zip"]=> string(5) "80127" 
            ["residence_country"]=> string(2) "IT" 
            ["txn_id"]=> string(17) "7BT96686SK6836825" 
            ["mc_currency"]=> string(3) "EUR" 
            ["mc_fee"]=> string(4) "2.87" 
            ["mc_gross"]=> string(5) "74.00" 
            ["protection_eligibility"]=> string(8) "ELIGIBLE" 
            ["payment_fee"]=> string(4) "2.87" 
            ["payment_gross"]=> string(5) "74.00" 
            ["payment_status"]=> string(9) "Completed" 
            ["payment_type"]=> string(7) "instant" 
            ["handling_amount"]=> string(4) "0.00" 
            ["shipping"]=> string(4) "0.00" 
            ["item_name"]=> string(8) "New Form" 
            ["item_number"]=> string(7) "5e1a659" 
            ["quantity"]=> string(1) "1" 
            ["txn_type"]=> string(10) "web_accept" 
            ["payment_date"]=> string(20) "2019-12-15T14:45:01Z" 
            ["business"]=> string(36) "sb-47chdl731279@business.example.com" 
            ["receiver_id"]=> string(13) "ST4KCDUMKYYYA" 
            ["notify_version"]=> string(11) "UNVERSIONED" 
            ["verify_sign"]=> string(56) "AFSwvZBQQvLB9-zYHALuShhKJILQAhpo6RLldgYAvJRxbZlThfxf3o8F"
            */
            
            $post_id = self::get_reference_post_id();         
            //var_dump($post_id);
            if ($post_id) {
                
                $error = false;
                $verified = true; //false;
                
                // local verification
                if (isset($_POST['payment_gross'])) {
                    $paypal_total = floatval($_POST['payment_gross']);
                    $form_total = floatval(get_post_meta($post_id, 'paypal_total', true));                
                    if ($form_total != $paypal_total) {
                        $error = sprintf( __('ERROR: Paid TOTAL on PayPal (%s) and Form TOTAL (%s) are not equal...Please contact the administrator.', 'dynamic-content-for-elementor'), $paypal_total, $form_total );
                    } else {
                        $verified = true;
                    }
                }
                
                // paypal verification
                if (isset($_POST['verify_sign'])) {
                    $ipn = new \DynamicContentForElementor\DCE_Paypal();
                    if ($settings['dce_form_paypal_sandbox']) {
                        $ipn->useSandbox();
                    }
                    //$ipn->usePHPCerts(); // ssl validation
                    $ipn_verified = $ipn->verifyIPN();
                    if (!$ipn_verified) {
                        $error = __('ERROR: Paypal not verified the payment...Please contact the administrator.', 'dynamic-content-for-elementor');
                    } else {
                        $verified = true;
                    }
                }
                
                
                if ($error) {
                    // display error
                    $content = '<div class="elementor-message elementor-message-danger" role="alert">'.$error.'</div>'.$content;
                } else {
                    if ($verified) {
                        
                        // publish post
                        //wp_publish_post($post_id);                        
                        wp_update_post(array(
                            'ID'    =>  $post_id,
                            'post_status'   =>  $settings['dce_form_paypal_post_state'],
                        ));
                
                        // simulate submit to execute other actions
                        if (strpos($content, 'name="payment_status"') === false) {
                            $paypal_completed = '<input type="text" name="payment_status" value="Completed">';
                            //$form_submit = '<script>jQuery(document).ready(function(){ jQuery(".elementor-element-'.$widget->get_id().' form").submit();});</script>';
                            $form_submit = '<script>jQuery(document).ready(function(){ jQuery(".elementor-element-'.$widget->get_id().' .elementor-button[type=submit]").trigger("click");});</script>';
                            $content = str_replace('</form>', $paypal_completed.'</form>'.$form_submit, $content);
                        }
                    }
                }

                // save all data from PayPal
                if (!empty($_POST)) {
                    foreach ($_POST as $meta_key => $meta_value) {
                        update_post_meta($post_id, 'paypal_'.$meta_key, $meta_value);
                    }
                }
                
            }
            
            // other actions
            $form_actions = \ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->get_form_actions();
            // simulate ajax action?
            
            return $content;
        }
        
        public function get_reference_post_id() {
            $post_id = $ref = null;
            if (isset($_GET['ref'])) {
                $ref = $_GET['ref'];
            }
            $referer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : false;
            if (wp_doing_ajax() && $referer) {
                $pieces = explode('?ref=', $referer, 2);
                if (count($pieces) > 1) {
                    $ref = end($pieces);
                }
            }
            if ($ref) {
                $pieces = explode('-', $ref);
                if (count($pieces) > 1) {
                    $post_id = $pieces[1];
                    
                    $paypal_time_get = end($pieces);
                    $paypal_time = get_post_meta($post_id, 'paypal_time', true);
                    
                    $paypal_post = get_post($post_id);
                    if (!$paypal_post) {
                        return false;
                    } 
                    if (get_post_status($post_id) != 'draft') {
                        return false;
                    }
                    if ($paypal_time != $paypal_time_get) {
                        return false;
                    }
                }
                
            }
            return $post_id;
        }
        
        public function set_field_value( $item, $index, $form ) {
            $post_id = self::get_reference_post_id();
            if ($post_id) {
                $item['field_value'] = get_post_meta($post_id, $item['custom_id'], true);
                $form->remove_render_attribute( 'input' . $index, 'value');
                $form->add_render_attribute( 'input' . $index, 'value', $item['field_value'] );
            } else {
                if ($item['field_type'] == 'number' && empty($item['field_value'])) {
                    $form->remove_render_attribute( 'input' . $index, 'value');
                    $form->add_render_attribute( 'input' . $index, 'value', '0' );
                } 
            }
            return $item;
	}
        
        public function _before_render_form($widget) {
            if ($widget->get_name() == 'form') {
                //if return from paypal                
                if (self::is_from_paypal($widget) || true) {
                    $post_id = self::get_reference_post_id();
                    //var_dump($post_id);
                    if ($post_id) {
                        $settings = $widget->get_settings();
                        // fill form fields with previous data
                        if (!empty($settings['form_fields'])) {
                            foreach ($settings['form_fields'] as $key => $afield) {                                  
                                $settings['form_fields'][$key]["field_value"] = get_post_meta($post_id, $afield['custom_id'], true);
                            }
                        }
                        //echo '<pre>'; var_dump($settings['form_fields']); echo '</pre>'; 
                        //echo '<pre>'; var_dump($widget); echo '</pre>'; 
                        $widget->set_settings('form_fields', $settings['form_fields']);
                        $widget->set_settings($settings);
                        //$widget->_print_content();
                        // WHY it don't works??? :(
                    }
                }
            }
        }

        public function _render_form($content, $widget) {
            $new_content = $content;
            if ($widget->get_name() == 'form') {
                //if return from paypal                
                if (self::is_from_paypal($widget)) {
                    if (self::is_paypal_completed()) {
                        $new_content = $this->on_paypal_return($new_content, $widget);
                    }
                }
            }
            return $new_content;
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

            $roles = DCE_Helper::get_roles(false);
            $post_types = DCE_Helper::get_post_types();
            $taxonomies = DCE_Helper::get_taxonomies();

            $widget->start_controls_section(
                    'section_dce_form_paypal',
                    [
                        'label' => $this->get_label(), //__('DCE', 'dynamic-content-for-elementor'),
                        'condition' => [
                            'submit_actions' => $this->get_name(),
                        ],
                    ]
            );
            
            $widget->add_control(
                    'dce_form_paypal_condition_field', [
                'label' => __('Condition', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'description' => __('Select the ID of the form field to check, or leave empty to always pay with Paypal', 'dynamic-content-for-elementor'),
                    ]
            );
            $widget->add_control(
                    'dce_form_paypal_condition_status', [
                'label' => __('Condition Status', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'empty' => [
                        'title' => __('Empty', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-circle-o',
                    ],
                    'valued' => [
                        'title' => __('Valued', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-dot-circle-o',
                    ],
                    'lt' => [
                        'title' => __('Less than', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-angle-left',
                    ],
                    'gt' => [
                        'title' => __('Greater than', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-angle-right',
                    ],
                    'equal' => [
                        'title' => __('Equal to', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-check-circle-o ',
                    ]
                ],
                'default' => 'valued',
                'toggle' => false,
                'label_block' => true,
                'condition' => [
                    'dce_form_paypal_condition_field!' => '',
                ],
                    ]
            );
            $widget->add_control(
                    'dce_form_paypal_condition_value', [
                'label' => __('Condition Value', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'description' => __('A value to compare the value of the field', 'dynamic-content-for-elementor'),
                'condition' => [
                    'dce_form_paypal_condition_field!' => '',
                    'dce_form_paypal_condition_status' => ['lt', 'gt', 'equal'],
                ],
                    ]
            );
            
            
            $widget->add_control(
                    'dce_form_paypal_sandbox',
                    [
                        'label' => __('SandBox Mode', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'default' => 'yes',
                        'separator' => 'before',
                    ]
            );

            $widget->add_control(
                    'dce_form_paypal_business',
                    [
                        'label' => __('PayPal Business Email', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => get_bloginfo('admin_email'),
                        'label_block' => 'true',
                    ]
            );
            $widget->add_control(
                    'dce_form_paypal_item_name',
                    [
                        'label' => __('PayPal Item Name', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => '[form:form_name]',
                        'label_block' => 'true',
                    ]
            );
            /*$widget->add_control(
                    'dce_form_paypal_item_number',
                    [
                        'label' => __('Paypal Item Number', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => $widget->get_id(),
                        'label_block' => 'true',
                    ]
            );*/
            
            $currencies = array("USD","AUD","BRL","GBP","CAD","CZK","DKK","EUR","HKD","HUF","ILS","JPY","MXN","TWD","NZD","NOK","PHP","PLN","RUB","SGD","SEK","CHF","THB");
            $currencies_options = array();
            foreach ($currencies as $key => $value) {
                $currencies_options[$value] = $value;
            }
            $widget->add_control(
                    'dce_form_paypal_currency_code',
                    [
                        'label' => __('PayPal Currency', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'options' => $currencies_options,
                        'default' => 'EUR',
                    ]
            );
            
            
            $languages = array('US', 'GB', 'IT', 'DE', 'FR', 'ES', 'NL', 'CN', 'JP');
            $languages_options = array();
            foreach ($languages as $key => $value) {
                $languages_options[$value] = $value;
            }
            $widget->add_control(
                    'dce_form_paypal_Ic',
                    [
                        'label' => __('PayPal Language', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'options' => $languages_options,
                        'default' => 'US',
                        //'default' => get_locale(),
                    ]
            );
            
            $widget->add_control(
                    'dce_form_paypal_amount',
                    [
                        'label' => __('PayPal Amount', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => '([form:field_1] * [form:field_2]) + [form:field_3] + 2.5',
                    ]
            );
            $widget->add_control(
                    'dce_form_paypal_shipping',
                    [
                        'label' => __('PayPal Shipping', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => '([form:field_1] * [form:field_2]) + [form:field_3] + 2.5',
                    ]
            );
            $widget->add_control(
                    'dce_form_paypal_discount_amount',
                    [
                        'label' => __('PayPal Discount', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => '([form:field_1] * [form:field_2]) + [form:field_3] + 2.5',
                    ]
            );
            
            
            $widget->add_control(
                    'dce_form_paypal_btn',
                    [
                        'label' => __('PayPal Button Type', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::CHOOSE,
                        'options' => [
                            'paypal' => [
                                'title' => __('Default', 'dynamic-content-for-elementor'),
                                'icon' => 'fa fa-paypal',
                            ],
                            'image' => [
                                'title' => __('Image', 'dynamic-content-for-elementor'),
                                'icon' => 'fa fa-file-image-o',
                            ],
                            'button' => [
                                'title' => __('Button', 'dynamic-content-for-elementor'),
                                'icon' => 'eicon-button',
                            ],
                        ],
                        'default' => 'paypal',
                        'toggle' => false,
                        'label_block' => 'true',
                    ]
            );
            $widget->add_control(
                    'dce_form_paypal_btn_text',
                    [
                        'label' => __('Button Text', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => __('Pay now with Paypal', 'dynamic-content-for-elementor'),
                    ]
            );
            $widget->add_control(
                    'dce_form_paypal_target',
                    [
                        'label' => __('Open PayPal in new tab', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                    ]
            );
            
            
            /**********************************************************/
            /*$widget->add_control(
                    'dce_form_paypal_before_payment', [
                'label' => __('Before Payment', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                    ]
            );*/
            $widget->add_control(
                    'dce_form_paypal_post',
                    [
                        'label' => __('Save Form Data', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::HEADING,
                        //'type' => \Elementor\Controls_Manager::SWITCHER,
                        //'default' => 'yes',                        
                        'separator' => 'before',
                    ]
            );
            $widget->add_control(
                    'dce_form_paypal_post_type', [
                        'label' => __('Post Type', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SELECT,
                        'options' => $post_types + array('dce_elementor_paypal' => __('Default', 'dynamic-content-for-elementor')),
                        'default' => 'dce_elementor_paypal',
                        /*'condition' => [
                            'dce_form_paypal_post!' => '',
                        ]*/
                    ]
            );
            $widget->add_control(
                    'dce_form_paypal_post_title',
                    [
                        'label' => __('Post Title', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => '[form:form_name]',
                        /*'condition' => [
                            'dce_form_paypal_post!' => '',
                        ]*/
                    ]
            );
            $widget->add_control(
                    'dce_form_paypal_post_state', [
                        'label' => __('Post State', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SELECT,
                        'options' => get_post_stati(),
                        'description' => __('Before payment save all form data as Post in draft, then after Payment it will take selected state.', 'dynamic-content-for-elementor'),
                        'default' => 'private',
                        /*'condition' => [
                            'dce_form_paypal_post!' => '',
                        ]*/
                    ]
            );
            
            
            /**********************************************************/
            /*
            $widget->add_control(
                    'dce_form_paypal_after_payment', [
                'label' => __('After Payment', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                    ]
            );
            
            $widget->add_control(
                    'dce_form_paypal_message', [
                'label' => __('Confirmation Message', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => __('Congratulation! Thanks for the payment.', 'dynamic-content-for-elementor'),
                    ]
            );
            
            $widget->add_control(
                    'dce_form_paypal_add_user_role', [
                'label' => __('Add User Role', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => $roles,
                'default' => 'subscriber',
                    ]
            );
            */ 
            
            $widget->add_control(
                    'dce_form_paypal_help', [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div id="elementor-panel__editor__help" class="p-0"><a id="elementor-panel__editor__help__link" href="'.$this->get_docs().'" target="_blank">'.__( 'Need Help', 'elementor' ).' <i class="eicon-help-o"></i></a></div>',
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

            $this->dce_elementor_form_paypal($fields, $settings, $ajax_handler);
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

        function dce_elementor_form_paypal($record, $settings = null, $ajax_handler = null) {

            $before_pay = !self::is_paypal_completed();
            var_dump($before_pay); die();
            if ($before_pay) {
                    
                $fields = array();
                if (is_object($record)) {
                    // from add action
                    $data = $record->get_formatted_data(true);
                    foreach ($data as $label => $value) {
                        $fields[$label] = sanitize_text_field($value);
                    }
                    $fields['form_name'] = $record->get_form_settings('form_name');
                } else {
                    // from form extension
                    $fields = $record;
                    /* $form_record = new \ElementorPro\Modules\Forms\Classes\Form_Record();
                      $record = $form_record; */
                    $fields['form_name'] = $settings['form_name'];
                }

                if ($ajax_handler->is_success) {
                    
                    $condition_satisfy = true;
                    if (!empty($amail['dce_form_paypal_condition_field'])) {
                        switch ($amail['dce_form_paypal_condition_status']) {
                            case 'empty':
                                if (!empty($fields[$amail['dce_form_paypal_condition_field']])) {
                                    $condition_satisfy = false;
                                }
                                break;
                            case 'valued':
                                if (!empty($fields[$amail['dce_form_paypal_condition_field']])) {
                                    $condition_satisfy = false;
                                }
                                break;
                            case 'lt':
                                if (empty($fields[$amail['dce_form_paypal_condition_field']]) || $fields[$amail['dce_form_paypal_condition_field']] > $amail['dce_form_email_condition_value']) {
                                    $condition_satisfy = false;
                                }
                                break;
                            case 'gt':
                                if (empty($fields[$amail['dce_form_paypal_condition_field']]) || $fields[$amail['dce_form_paypal_condition_field']] < $amail['dce_form_email_condition_value']) {
                                    $condition_satisfy = false;
                                }
                                break;
                            case 'equal':
                                if ($fields[$amail['dce_form_paypal_condition_field']] != $amail['dce_form_paypal_condition_value']) {
                                    $condition_satisfy = false;
                                }
                                break;
                        }
                    }

                    if ($condition_satisfy) {

                        $settings_raw = $settings;
                        $settings = DCE_Helper::get_dynamic_value($settings, $fields);

                        $operations = $settings_raw['dce_form_paypal_amount'].$settings_raw['dce_form_paypal_shipping'].$settings_raw['dce_form_paypal_discount_amount'];
                        //var_dump($operations);
                        foreach ($fields as $fkey => $afield) {
                            if (strpos($operations, '[form:'.$fkey.']') !== false) {
                                if (!floatval($afield)) {
                                    $fields[$fkey] = '0';
                                }
                            }
                        }

                        //$amount_operation = DCE_Helper::get_dynamic_value($settings_raw['dce_form_paypal_amount'], $fields);
                        $amount_operation = DCE_Tokens::replace_var_tokens($settings_raw['dce_form_paypal_amount'], 'form', $fields);
                        if ($amount_operation) {
                            eval('$amount = '.$amount_operation.';');
                        } else {
                            $amount = 0;
                        }
                        $amount_formatted = number_format($amount,2,".","");
                        $fields['paypal_amount'] = $amount;

                        //$shipping_operation = DCE_Helper::get_dynamic_value($settings_raw['dce_form_paypal_shipping'], $fields);
                        $shipping_operation = DCE_Tokens::replace_var_tokens($settings_raw['dce_form_paypal_shipping'], 'form', $fields);
                        if ($shipping_operation) {
                            eval('$shipping = '.$shipping_operation.';');
                        } else {
                            $shipping = 0;
                        }
                        $shipping_formatted = number_format($shipping,2,".","");
                        $fields['paypal_shipping'] = $shipping;

                        //$discount_amount_operation = DCE_Helper::get_dynamic_value($settings_raw['dce_form_paypal_discount_amount'], $fields);
                        $discount_amount_operation = DCE_Tokens::replace_var_tokens($settings_raw['dce_form_paypal_discount_amount'], 'form', $fields);
                        if ($discount_amount_operation) {
                            eval('$discount_amount = '.$discount_amount_operation.';');
                        } else {
                            $discount_amount = 0;
                        }
                        $discount_amount_formatted = number_format($discount_amount,2,".","");
                        $fields['paypal_discount_amount'] = $discount_amount;

                        $total = $amount + $shipping - $discount_amount;
                        $total_formatted = number_format($total,2,".","");
                        $fields['paypal_total'] = $total;

                        $fields['paypal_time'] = time();
                        $reference_number = $settings['id'];

                        if($settings['dce_form_paypal_post']) {
                            $post_id = self::get_reference_post_id();
                            // Insert the post into the database
                            // https://developer.wordpress.org/reference/functions/wp_insert_post/
                            $settings['dce_form_paypal_post_title'] = DCE_Helper::get_dynamic_value($settings['dce_form_paypal_post_title'], $fields);            

                            $db_ins = array(
                                'post_title' => $settings['dce_form_paypal_post_title'],
                                'post_status' => 'draft',
                                'post_type' => $settings['dce_form_paypal_post_type'],
                            );
                            if ($post_id) {
                                $db_ins['ID'] = $post_id;
                                $obj_id = wp_update_post($db_ins);            
                            } else {
                                $obj_id = wp_insert_post($db_ins);            
                            }
                            if ($obj_id && !is_wp_error($obj_id)) {
                                if (!empty($fields) && is_array($fields)) {
                                    foreach ($fields as $akey => $adata) {                        
                                        update_post_meta($obj_id, $akey, $adata);
                                    }
                                }
                                $reference_number .= '-'.$obj_id.'-'.$fields['paypal_time'];
                            }

                        }

                        $return_url = get_permalink();
                        $cancel_return = $return_url = esc_url( add_query_arg( 'ref', $reference_number, $return_url ) );
                        //$return_url = esc_url( add_query_arg( 'status', 'paid', $return_url ) );

                        $target = ($settings['dce_form_paypal_target']) ? ' target="paypal"' : '';
                        $sandbox = ($settings['dce_form_paypal_sandbox']) ? 'sandbox.' : '';

                        ob_start();
                        ?>
                        <a href="<?php echo $return_url; ?>">Change</a>
                        <h3>Total: <?php echo $total_formatted . ' ' . $settings['dce_form_paypal_currency_code']; ?></h3>
                        <form<?php echo $target; ?> action="https://www.<?php echo $sandbox; ?>paypal.com/cgi-bin/webscr" method="post" id="dce_paypal_<?php echo $settings['id']; ?>">
                            <input type="hidden" name="charset" value="utf8">
                            <input type="hidden" name="no_note" value="1">

                            <input type="hidden" name="cmd" value="_xclick">                
                            <input type="hidden" name="button_subtype" value="products">

                            <input type="hidden" name="business" value="<?php echo $settings['dce_form_paypal_business']; ?>">
                            <input type="hidden" name="item_name" value="<?php echo $settings['dce_form_paypal_item_name']; ?>">
                            <input type="hidden" name="item_number" value="<?php echo $settings['id']; ?>">
                            <input type="hidden" name="amount" value="<?php echo $amount_formatted; ?>">

                            <?php if ($discount_amount) { ?>
                                <input type="hidden" name="discount_amount" value="<?php echo number_format($discount_amount,2,".",""); ?>">
                            <?php } ?>

                            <?php if ($shipping) { ?>
                                <input type="hidden" name="shipping" value="<?php echo number_format($shipping,2,".",""); ?>">
                            <?php } else { ?>
                                <input type="hidden" name="no_shipping" value="2">
                            <?php } ?>

                            <input type="hidden" name="Ic" value="<?php echo $settings['dce_form_paypal_Ic']; ?>">
                            <input type="hidden" name="currency_code" value="<?php echo $settings['dce_form_paypal_currency_code']; ?>">

                            <input type="hidden" name="bn" value="DCE-FormBtn">
                            <!--<input type="hidden" name="bn" value="PP-ShopCartBF:btn_cart_LG.gif:NonHosted">-->

                            <?php switch ($settings['dce_form_paypal_btn']) {
                                case 'image': ?>
                                    <input type="image" src="<?php echo wp_get_attachment_url($settings['dce_form_paypal_btn_media']); ?>" border="0" name="submit" alt="<?php echo $settings['dce_form_paypal_btn_text']; ?>">
                                    <?php
                                    break;
                                case 'button': ?>
                                    <button type="submit" form="dce_paypal_<?php echo $settings['id']; ?>" name="submit" value="Submit"><?php echo $settings['dce_form_paypal_btn_text']; ?></button>
                                    <?php
                                    break;
                                default: ?>
                                    <input type="image" src="https://www.paypalobjects.com/<?php echo get_locale(); ?>/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="<?php echo $settings['dce_form_paypal_btn_text']; ?>">
                                    <?php
                            } ?>

                            <img alt="" border="0" src="https://www.paypalobjects.com/<?php echo get_locale(); ?>/i/scr/pixel.gif" width="1" height="1">

                            <input type="hidden" name="rm" value="2">
                            <input type="hidden" name="return" value="<?php echo $return_url; ?>">
                            <input type="hidden" name="cancel_return" value="<?php echo $cancel_return; ?>">
                            <input type="hidden" name="notify_url" value="<?php echo DCE_URL; ?>assets/ipn.php?ref=<?php echo $reference_number; ?>">

                            <style>
                                .elementor-element-<?php echo $settings['id']; ?> .elementor-message.elementor-message-danger::before, 
                                .elementor-element-<?php echo $settings['id']; ?> .elementor-field-type-submit { 
                                    display: none; 
                                }
                            </style>
                        </form>
                        <?php
                        $message_html = ob_get_contents();
                        ob_end_clean();

                        wp_send_json_error([
                            'message' => $message_html,
                            'data' => $ajax_handler->data,
                        ]);
                        die();
                    }
                }
            }
        }
    }
}