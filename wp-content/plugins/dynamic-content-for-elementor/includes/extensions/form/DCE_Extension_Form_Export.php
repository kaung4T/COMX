<?php

namespace DynamicContentForElementor\Extensions;

use Elementor\Controls_Manager;
use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\DCE_Tokens;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

function _dce_extension_form_export($field) {
    switch ($field) {
        case 'enabled':
            return true;
        case 'docs':
            return 'https://www.dynamic.ooo/widget/export-for-elementor-pro-form/';
        case 'description' :
            return __('Add Export Actions to Elementor PRO Form', 'dynamic-content-for-elementor');
    }
}

if (!DCE_Helper::is_plugin_active('elementor-pro')) {

    class DCE_Extension_Form_Export extends DCE_Extension_Prototype {

        public $name = 'Form Export';
        private $is_common = false;
        public static $depended_plugins = ['elementor-pro'];

        static public function is_enabled() {
            return _dce_extension_form_export('enabled');
        }

        public static function get_description() {
            return _dce_extension_form_export('description');
        }

        public function get_docs() {
            return _dce_extension_form_export('docs');
        }

    }

} else {

    class DCE_Extension_Form_Export extends \ElementorPro\Modules\Forms\Classes\Action_Base {

        public $name = 'Form Export';
        public static $depended_plugins = ['elementor-pro'];
        public static $docs = 'https://www.dynamic.ooo';
        public $has_action = true;

        static public function is_enabled() {
            return _dce_extension_form_export('enabled');
        }

        public static function get_description() {
            return _dce_extension_form_export('description');
        }

        public function get_docs() {
            return _dce_extension_form_export('docs');
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
            return 'dce_form_export';
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
            return __('Export', 'dynamic-content-for-elementor');
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
                    'section_dce_form_export',
                    [
                        'label' => $this->get_label(), //__('DCE', 'dynamic-content-for-elementor'),
                        'condition' => [
                            'submit_actions' => $this->get_name(),
                        ],
                    ]
            );

            $widget->add_control(
                    'dce_form_export_url',
                    [
                        'label' => __('Endpoint URL', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => 'https://www.external.ext/save_data.php',
                        'label_block' => true,
                    ]
            );
            $widget->add_control(
                    'dce_form_export_port',
                    [
                        'label' => __('Port', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'placeholder' => '80',
                    ]
            );
            $widget->add_control(
                    'dce_form_export_method',
                    [
                        'label' => __('Method (GET, POST or HEAD)', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::CHOOSE,
                        'options' => [
                            'get' => [
                                'title' => __('GET', 'dynamic-content-for-elementor'),
                                'icon' => 'fa fa-square',
                            ],
                            'post' => [
                                'title' => __('POST', 'dynamic-content-for-elementor'),
                                'icon' => 'fa fa-square-o',
                            ],
                            'head' => [
                                'title' => __('HEAD', 'dynamic-content-for-elementor'),
                                'icon' => 'fa fa-circle-o',
                            ],
                        ],
                        'default' => 'get',
                        'toggle' => false,
                        'label_block' => 'true',
                    ]
            );
            $widget->add_control(
                    'dce_form_export_ssl', [
                'label' => __('Enable SSL Certificate verify', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                    ]
            );
            $widget->add_control(
                    'dce_form_export_empty', [
                'label' => __('Ignore fields with empty value', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                        'default' => 'yes',
                    ]
            );
            $widget->add_control(
                    'dce_form_export_json', [
                'label' => __('Encode Post Data in Json', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'dce_form_export_method' => 'post',
                ],
                    ]
            );
            $repeater_fields = new \Elementor\Repeater();
            $repeater_fields->add_control(
                    'dce_form_export_field_key', [
                'label' => __('Field Key', 'dynamic-content-for-elementor'),
                'description' => __('Is the key of the parameter in the request', 'dynamic-content-for-elementor')
                . '<br>?<b>field_key</b>=FieldValue&<b>page</b>=2&<b>txt</b>=Test<br>',
                'type' => Controls_Manager::TEXT,
                    ]
            );
            $repeater_fields->add_control(
                    'dce_form_export_field_value', [
                'label' => __('Field Value', 'dynamic-content-for-elementor'),
                'description' => __('Is the value of the parameter in the request', 'dynamic-content-for-elementor')
                . '<br>?field_key=<b>FieldValue</b>&page=<b>2</b>&txt=<b>Test</b><br>' .
                __('Can use static text, field Shortcode, Token or mixed', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                    ]
            );
            $widget->add_control(
                    'dce_form_export_fields', [
                'label' => __('Exported Arguments list', 'dynamic-content-for-elementor'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater_fields->get_controls(),
                //'title_field' => '{{{ dce_form_export_field_key }}}',
                'title_field' => '{{{ dce_form_export_field_key }}} = {{{ dce_form_export_field_value }}}',
                'prevent_empty' => false,
                    ]
            );
            
            $repeater_headers = new \Elementor\Repeater();
            $repeater_headers->add_control(
                    'dce_form_export_header_key', [
                'label' => __('Header Key', 'dynamic-content-for-elementor'),
                'placeholder' => 'Content-Type',                        
                'type' => Controls_Manager::TEXT,
                    ]
            );
            $repeater_headers->add_control(
                    'dce_form_export_header_value', [
                'label' => __('Header Value', 'dynamic-content-for-elementor'),
                'placeholder' => 'application/json',
                'type' => Controls_Manager::TEXT,
                    ]
            );
            $widget->add_control(
                    'dce_form_export_headers', [
                'label' => __('Add Headers', 'dynamic-content-for-elementor'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater_headers->get_controls(),
                'title_field' => '{{{ dce_form_export_header_key }}}: {{{ dce_form_export_header_value }}}',
                'default' => [ ['dce_form_export_header_key' => 'Connection', 'dce_form_export_header_value' => 'keep-alive']],
                'prevent_empty' => false,
                    ]
            );
            
            $widget->add_control(
                    'dce_form_pdf_log', [
                'label' => __('Enable log', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'description' => __('Create a log for Export result', 'dynamic-content-for-elementor'),
                'default' => 'yes',
                    ]
            );
            $widget->add_control(
                    'dce_form_pdf_log_path', [
                'label' => __('Log Path', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'elementor/export/log_'.$widget->get_id().'_[date|Ymd].txt',
                'description' => __('The Log path', 'dynamic-content-for-elementor'),
                'label_block' => true,
                'condition' => [
                    'dce_form_pdf_log!' => '',
                ],
                    ]
            );
            
            $widget->add_control(
                    'dce_form_pdf_error', [
                'label' => __('Show error on failure', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'description' => __('If the remote request fail (not response code 200) then the form return an error to user', 'dynamic-content-for-elementor'),
                'default' => 'yes',
                    ]
            );
            
            $widget->add_control(
                    'dce_form_export_help', [
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
            $settings = DCE_Helper::get_dynamic_value($settings, $fields);

            $this->dce_elementor_form_export($fields, $settings, $ajax_handler);
            
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

        function dce_elementor_form_export($fields, $settings = null, $ajax_handler = null) {

            $export_data = array();
            if (!empty($settings['dce_form_export_fields'])) {
                foreach ($settings['dce_form_export_fields'] as $akey => $adata) {
                    // TOKENIZE parameters repeater
                    $pvalue = DCE_Helper::get_dynamic_value($adata['dce_form_export_field_value'], $fields);
                    if ($pvalue == '' && $settings['dce_form_export_empty']) {
                        continue;
                    }
                    if ((substr(trim($pvalue),0,1) == '{' && substr(trim($pvalue),-1,1) == '}')
                        || (substr(trim($pvalue),0,1) == '[' && substr(trim($pvalue),-1,1) == ']')){
                        $pvalue = json_decode($pvalue);
                    }
                    $export_data[$adata['dce_form_export_field_key']] = $pvalue;
                }
            }

            $args = array();
            $exp_url = $settings['dce_form_export_url'];
            if ($exp_url) {
                $pieces = explode('/', $exp_url);
                if (count($pieces) >= 3) {
                    if ($settings['dce_form_export_port']) {
                        $pieces[2] = $pieces[2] . ':' . $settings['dce_form_export_port'];
                        $exp_url = implode('/', $pieces);
                    }
                    if ($settings['dce_form_export_method'] == 'get') {
                        if (!empty($export_data)) {
                            foreach ($export_data as $akey => $avalue) {
                                $exp_url = add_query_arg($akey, $avalue, $exp_url);
                            }
                        }
                        /* $args = array(
                          'timeout'     => 5,
                          'redirection' => 5,
                          'httpversion' => '1.0',
                          'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
                          'blocking'    => true,
                          'headers'     => array(),
                          'cookies'     => array(),
                          'body'        => null,
                          'compress'    => false,
                          'decompress'  => true,
                          'sslverify'   => true,
                          'stream'      => false,
                          'filename'    => null
                          ); */
                    } else {
                        if ($settings['dce_form_export_json']) {
                            $args['body'] = json_encode($export_data);
                            $args['headers'] = array('Content-Type' => 'application/json; charset=utf-8');
                            $args['data_format'] = 'body';
                        } else {
                            $args['body'] = $export_data;
                        }
                        /* $args =
                         * method: POST, 
                         * timeout: 5, 
                         * redirection: 5, 
                         * httpversion: 1.0, 
                         * blocking: true, 
                         * headers: array(), 
                         * body: null, 
                         * cookies: array() */
                    }
                    
                    if (!empty($settings['dce_form_export_headers'])) {
                        foreach ($settings['dce_form_export_headers'] as $akey => $adata) {
                            // TOKENIZE parameters repeater
                            $pvalue = DCE_Helper::get_dynamic_value($adata['dce_form_export_header_value'], $fields);
                            $args['headers'][$adata['dce_form_export_header_key']] = $pvalue;
                        }
                    }
                    
                    //$ajax_handler->add_error_message($exp_url);
                    //var_dump($exp_url); die();
                    if (!$settings['dce_form_export_ssl']) {
                        add_filter( 'https_ssl_verify', '__return_false' );
                    }
                    // Send the request
                    $req = 'wp_remote_' . $settings['dce_form_export_method'];
                    $ret = call_user_func($req, $exp_url, $args);
                    $ret_code = wp_remote_retrieve_response_code($ret);
                    if ($ret_code == 200) {
                        $log = 'Form Export: OK';
                    } else {
                        $log = 'Form Export: ERROR '.$ret_code;
                        if ($settings['dce_form_pdf_error']) {
                            $ajax_handler->add_error_message(\ElementorPro\Modules\Forms\Classes\Ajax_Handler::get_default_message(\ElementorPro\Modules\Forms\Classes\Ajax_Handler::SERVER_ERROR, $settings));
                        }
                    }
                    
                    if ($settings['dce_form_pdf_log']) {
                        //error_log($log);
                        $ret_body = wp_remote_retrieve_body($ret);
                        //if (!$ret_body) {
                            $ret_body = $ret;
                        //}
                        $log = $log . ' - ' . $req . PHP_EOL; 
                        $log .= 'request_url: ' . $exp_url . PHP_EOL; 
                        if ($settings['dce_form_export_method'] == 'post') {
                            $log .= 'request_data: ' .var_export($args['body'], true) . PHP_EOL; 
                        }
                        $log .= 'return_body: '.var_export($ret_body, true);
                        $log = PHP_EOL.'['.date('Y-m-d H:i:s').'] '.$log;
                        
                        $upload = wp_upload_dir();
                        $upload_dir = $upload['basedir'];
                        $log_dir = $upload_dir.'/'.DCE_Helper::get_dynamic_value(dirname($settings['dce_form_pdf_log_path']), $fields);
                        $log_filename = DCE_Helper::get_dynamic_value(basename($settings['dce_form_pdf_log_path']), $fields);
                        if ((!is_dir($log_dir) && !mkdir($log_dir, 0777, true)) || !file_put_contents($log_dir.'/'.$log_filename, $log, FILE_APPEND)) {
                            $ajax_handler->add_error_message('Error on LOG creation in '.$log_dir.'/'.$log_filename);
                        }
                    }
                }
            }
        }

    }

}