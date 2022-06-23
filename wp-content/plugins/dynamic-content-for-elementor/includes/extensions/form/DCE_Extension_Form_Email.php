<?php

namespace DynamicContentForElementor\Extensions;

use Elementor\Controls_Manager;
use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\DCE_Tokens;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

function _dce_extension_form_email($field) {
    switch ($field) {
        case 'enabled':
            return true;
        case 'docs':
            return 'https://www.dynamic.ooo/widget/dynamic-email-for-elementor-pro-form/';
        case 'description' :
            return __('Add Dynamic Email Actions to Elementor PRO Form', 'dynamic-content-for-elementor');
    }
}

if (!DCE_Helper::is_plugin_active('elementor-pro')) {

    class DCE_Extension_Form_Email extends DCE_Extension_Prototype {

        public $name = 'Form Email';
        private $is_common = false;
        public static $depended_plugins = ['elementor-pro'];

        static public function is_enabled() {
            return _dce_extension_form_email('enabled');
        }

        public static function get_description() {
            return _dce_extension_form_email('description');
        }

        public function get_docs() {
            return _dce_extension_form_email('docs');
        }

    }

} else {

    class DCE_Extension_Form_Email extends \ElementorPro\Modules\Forms\Classes\Action_Base {

        public $name = 'Form Email';
        public static $depended_plugins = ['elementor-pro'];
        public static $docs = 'https://www.dynamic.ooo';
        public $has_action = true;

        static public function is_enabled() {
            return _dce_extension_form_email('enabled');
        }

        public static function get_description() {
            return _dce_extension_form_email('description');
        }

        public function get_docs() {
            return _dce_extension_form_email('docs');
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
            return 'dce_form_email';
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
            return __('Dynamic Email', 'dynamic-content-for-elementor');
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
                    'section_dce_form_email',
                    [
                        'label' => $this->get_label(), //__('DCE', 'dynamic-content-for-elementor'),
                        'condition' => [
                            'submit_actions' => $this->get_name(),
                        ],
                    ]
            );

            $repeater_fields = new \Elementor\Repeater();
            $repeater_fields->add_control(
                    'dce_form_email_enable',
                    [
                        'label' => __('Enable email', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'default' => 'yes',
                        'description' => __('You can temporary disable it without delete settings and reactivate it next time', 'dynamic-content-for-elementor'),
                        'separator' => 'after',
                    ]
            );
            $repeater_fields->add_control(
                    'dce_form_email_condition_field', [
                'label' => __('Condition', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'description' => __('Write here the ID of the form field to check, or leave empty to always send this email', 'dynamic-content-for-elementor'),
                    ]
            );
            $repeater_fields->add_control(
                    'dce_form_email_condition_status', [
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
                    'dce_form_email_condition_field!' => '',
                ],
                    ]
            );
            $repeater_fields->add_control(
                    'dce_form_email_condition_value', [
                'label' => __('Condition Value', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'description' => __('A value to compare the value of the field', 'dynamic-content-for-elementor'),
                'condition' => [
                    'dce_form_email_condition_field!' => '',
                    'dce_form_email_condition_status' => ['lt', 'gt', 'equal'],
                ],
                    ]
            );

            /* translators: %s: Site title. */
            $default_message = sprintf(__('New message from "%s"', 'elementor-pro'), get_option('blogname'));
            $repeater_fields->add_control(
                    'dce_form_email_subject',
                    [
                        'label' => __('Subject', 'elementor-pro'),
                        'type' => Controls_Manager::TEXT,
                        'default' => $default_message,
                        'placeholder' => $default_message,
                        'label_block' => true,
                        'render_type' => 'none',
                        'separator' => 'before',
                    ]
            );

            $repeater_fields->add_control(
                    'dce_form_email_to',
                    [
                        'label' => __('To', 'elementor-pro'),
                        'type' => Controls_Manager::TEXT,
                        'default' => get_option('admin_email'),
                        'placeholder' => get_option('admin_email'),
                        'label_block' => true,
                        'title' => __('Separate emails with commas', 'elementor-pro'),
                        'render_type' => 'none',
                        'separator' => 'before',
                    ]
            );

            $site_domain = \ElementorPro\Classes\Utils::get_site_domain();
            $repeater_fields->add_control(
                    'dce_form_email_from',
                    [
                        'label' => __('From Email', 'elementor-pro'),
                        'type' => Controls_Manager::TEXT,
                        'default' => 'email@' . $site_domain,
                        'render_type' => 'none',
                    ]
            );

            $repeater_fields->add_control(
                    'dce_form_email_from_name',
                    [
                        'label' => __('From Name', 'elementor-pro'),
                        'type' => Controls_Manager::TEXT,
                        'default' => get_bloginfo('name'),
                        'render_type' => 'none',
                    ]
            );

            $repeater_fields->add_control(
                    'dce_form_email_reply_to',
                    [
                        'label' => __('Reply-To', 'elementor-pro'),
                        'type' => Controls_Manager::TEXT,
                        'render_type' => 'none',
                    ]
            );

            $repeater_fields->add_control(
                    'dce_form_email_to_cc',
                    [
                        'label' => __('Cc', 'elementor-pro'),
                        'type' => Controls_Manager::TEXT,
                        'default' => '',
                        'title' => __('Separate emails with commas', 'elementor-pro'),
                        'render_type' => 'none',
                    ]
            );

            $repeater_fields->add_control(
                    'dce_form_email_to_bcc',
                    [
                        'label' => __('Bcc', 'elementor-pro'),
                        'type' => Controls_Manager::TEXT,
                        'default' => '',
                        'title' => __('Separate emails with commas', 'elementor-pro'),
                        'render_type' => 'none',
                    ]
            );

            $repeater_fields->add_control(
                    'dce_form_email_content_type',
                    [
                        'label' => __('Send As', 'elementor-pro'),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'html',
                        'render_type' => 'none',
                        'options' => [
                            'html' => __('HTML', 'elementor-pro'),
                            'plain' => __('Plain', 'elementor-pro'),
                        ],
                        'separator' => 'before',
                    ]
            );

            $repeater_fields->add_control(
                    'dce_form_email_content_type_advanced', [
                'label' => __('Email body', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'text' => [
                        'title' => __('Message', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'template' => [
                        'title' => __('Template', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-th-large',
                    ]
                ],
                'toggle' => false,
                'default' => 'text',
                'condition' => [
                    'dce_form_email_content_type' => 'html',
                ],
                    ]
            );

            $repeater_fields->add_control(
                    'dce_form_email_content',
                    [
                        'label' => __('Message', 'elementor-pro'),
                        'type' => Controls_Manager::WYSIWYG,
                        'default' => '[all-fields]',
                        'placeholder' => '[all-fields]',
                        'description' => sprintf(__('By default, all form fields are sent via %s shortcode. To customize sent fields, copy the shortcode that appears inside each field and paste it above.', 'elementor-pro'), '<code>[all-fields]</code>'),
                        'label_block' => true,
                        'render_type' => 'none',
                        'condition' => [
                            'dce_form_email_content_type_advanced' => 'text',
                        ],
                    ]
            );

            $repeater_fields->add_control(
                    'dce_form_email_content_template',
                    [
                        'label' => __('Template', 'dynamic-content-for-elementor'),
                        'type' => 'ooo_query',
                        'placeholder' => __('Template Name', 'dynamic-content-for-elementor'),
                        'label_block' => true,
                        'query_type' => 'posts',
                        'object_type' => 'elementor_library',
                        'description' => 'Use a Elementor Template as body fo this Email.',
                        'condition' => [
                            'dce_form_email_content_type' => 'html',
                            'dce_form_email_content_type_advanced' => 'template',
                        ],
                    ]
            );
            $repeater_fields->add_control(
                    'dce_form_email_content_template_style', [
                'label' => __('Styles', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '' => [
                        'title' => __('Only HTML', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-window-close-o',
                    ],
                    'inline' => [
                        'title' => __('Inline', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                /* 'embed' => [
                  'title' => __('Embed', 'dynamic-content-for-elementor'),
                  'icon' => 'fa fa-external-link',
                  ] */
                ],
                'default' => 'inline',
                'condition' => [
                    'dce_form_email_content_type' => 'html',
                    'dce_form_email_content_type_advanced' => 'template',
                ],
                    ]
            );
            $repeater_fields->add_control(
                    'dce_form_email_content_template_layout', [
                'label' => __('Flex or Table', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex' => [
                        'title' => __('Css FLEX', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-leaf',
                    ],
                    'table' => [
                        'title' => __('Css TABLE', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-th-large',
                    ],
                    'html' => [
                        'title' => __('Html TABLE', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-table',
                    ]
                ],
                'default' => 'table',
                'description' => __('Add more compatibility for columned layout visualization', 'dynamic-content-for-elementor'),
                'condition' => [
                    'dce_form_email_content_type' => 'html',
                    'dce_form_email_content_type_advanced' => 'template',
                    'dce_form_email_content_template_style' => 'inline'
                ],
                    ]
            );
            $repeater_fields->add_control(
                    'dce_form_email_attachments',
                    [
                        'label' => __('Add Upload files as Attachments', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'description' => __('Send all Uploaded Files as Email Attachments', 'dynamic-content-for-elementor'),
                        'separator' => 'before',
                    ]
            );
            

            $widget->add_control(
                    'dce_form_email_repeater', [
                'label' => __('Emails', 'dynamic-content-for-elementor'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'title_field' => '{{{ dce_form_email_subject }}}',
                'fields' => $repeater_fields->get_controls(),
                'description' => __('Send all Email you need', 'dynamic-content-for-elementor'),
                    ]
            );
            
            $widget->add_control(
                    'dce_form_email_help', [
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

            $this->dce_elementor_form_email($fields, $settings, $ajax_handler, $record);
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

        function dce_elementor_form_email($fields, $settings = null, $ajax_handler = null, $record = null) {

            foreach ($settings['dce_form_email_repeater'] as $mkey => $amail) {

                if ($amail['dce_form_email_enable']) {


                    $condition_satisfy = true;
                    if (!empty($amail['dce_form_email_condition_field'])) {
                        switch ($amail['dce_form_email_condition_status']) {
                            case 'empty':
                                if (!empty($fields[$amail['dce_form_email_condition_field']])) {
                                    $condition_satisfy = false;
                                }
                                break;
                            case 'valued':
                                if (!empty($fields[$amail['dce_form_email_condition_field']])) {
                                    $condition_satisfy = false;
                                }
                                break;
                            case 'lt':
                                if (empty($fields[$amail['dce_form_email_condition_field']]) || $fields[$amail['dce_form_email_condition_field']] > $amail['dce_form_email_condition_value']) {
                                    $condition_satisfy = false;
                                }
                                break;
                            case 'gt':
                                if (empty($fields[$amail['dce_form_email_condition_field']]) || $fields[$amail['dce_form_email_condition_field']] < $amail['dce_form_email_condition_value']) {
                                    $condition_satisfy = false;
                                }
                                break;
                            case 'equal':
                                if ($fields[$amail['dce_form_email_condition_field']] != $amail['dce_form_email_condition_value']) {
                                    $condition_satisfy = false;
                                }
                                break;
                        }
                    }

                    if ($condition_satisfy) {

                        $send_html = 'plain' !== $amail['dce_form_email_content_type'];
                        $line_break = $send_html ? '<br>' : "\n";
                        $attachments = array();

                        $email_fields = [
                            'dce_form_email_to' => get_option('admin_email'),
                            /* translators: %s: Site title. */
                            'dce_form_email_subject' => sprintf(__('New message from "%s"', 'elementor-pro'), get_bloginfo('name')),
                            'dce_form_email_content' => '[all-fields]',
                            'dce_form_email_from_name' => get_bloginfo('name'),
                            'dce_form_email_from' => get_bloginfo('admin_email'),
                            'dce_form_email_reply_to' => 'noreplay@' . \ElementorPro\Classes\Utils::get_site_domain(),
                            'dce_form_email_to_cc' => '',
                            'dce_form_email_to_bcc' => '',
                        ];

                        foreach ($email_fields as $key => $default) {
                            $setting = $amail[$key];
                            //$setting = DCE_Helper::get_dynamic_value($setting, $fields);
                            if (!empty($setting)) {
                                $email_fields[$key] = $setting;
                            }
                        }

                        $headers = sprintf('From: %s <%s>' . "\r\n", $email_fields['dce_form_email_from_name'], $email_fields['dce_form_email_from']);
                        
                        if (!empty($email_fields['dce_form_email_reply_to'])) {
                            if (filter_var($email_fields['dce_form_email_reply_to'], FILTER_VALIDATE_EMAIL)) { // control if is a valid email
                                $headers .= sprintf('Reply-To: %s' . "\r\n", $email_fields['dce_form_email_reply_to']);
                            }
                        }
                        

                        if ($send_html) {
                            $headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
                        }

                        $cc_header = '';
                        if (!empty($email_fields['dce_form_email_to_cc'])) {
                            $cc_header = 'Cc: ' . $email_fields['dce_form_email_to_cc'] . "\r\n";
                        }

                        $bcc_header = '';
                        if (!empty($email_fields['dce_form_email_to_bcc'])) {
                            $bcc_header = 'Bcc: ' . $email_fields['dce_form_email_to_bcc'] . "\r\n";
                        }

                        /**
                         * Email headers.
                         *
                         * Filters the additional headers sent when the form send an email.
                         *
                         * @since 1.0.0
                         *
                         * @param string|array $headers Additional headers.
                         */
                        $headers = apply_filters('elementor_pro/forms/wp_mail_headers', $headers);

                        /**
                         * Email content.
                         *
                         * Filters the content of the email sent by the form.
                         *
                         * @since 1.0.0
                         *
                         * @param string $email_content Email content.
                         */
                        if (!empty($amail['dce_form_email_content_type_advanced']) && $amail['dce_form_email_content_type_advanced'] == 'template') {
                            // using a template
                            $inline = '';
                            if ($amail['dce_form_email_content_template_style'] == 'embed') {
                                $inline = ' inlinecss="true"';
                            }

                            $author = '';
                            $current_user_id = get_current_user_id();
                            if ($current_user_id) {
                                $author = ' author_id="' . $current_user_id . '"';
                            }

                            
                            $dce_form_email_content = do_shortcode('[dce-elementor-template id="' . $amail['dce_form_email_content_template'] . '"' . $inline . $author . ']');
                            //$dce_form_email_content = DCE_Tokens::replace_form_tokens($dce_form_email_content);
                            $attachments = $this->get_email_attachments($dce_form_email_content, $fields, $amail);
                            $dce_form_email_content = $this->remove_attachment_tokens($dce_form_email_content, $fields);
                            $dce_form_email_content = DCE_Helper::get_dynamic_value($dce_form_email_content, $fields);

                            if ($amail['dce_form_email_content_template_style']) {
                                $css = DCE_Helper::get_post_css($amail['dce_form_email_content_template']);
                                // add some fixies
                                $css .= '/*.elementor-column-wrap,*/ .elementor-widget-wrap { display: block !important; }';
                                if (!empty($amail['dce_form_email_content_template_layout']) && $amail['dce_form_email_content_template_layout'] != 'flex') {
                                    // from flex to table
                                    $css .= '.elementor-section .elementor-container { display: table !important; width: 100% !important; }';
                                    $css .= '.elementor-row { display: table-row !important; }';
                                    $css .= '.elementor-column { display: table-cell !important; }';
                                    $css .= '.elementor-column-wrap, .elementor-widget-wrap { display: block !important; }';
                                    $css = str_replace(':not(.elementor-motion-effects-element-type-background) > .elementor-element-populated', ':not(.elementor-motion-effects-element-type-background)', $css);
                                }

                                //$css = str_replace('}.elementor-column-wrap{', '}.dce-elementor-column-wrap{', $css); // disable column flex

                                if ($amail['dce_form_email_content_template_style'] == 'inline') {
                                    // https://github.com/tijsverkoyen/CssToInlineStyles
                                    // create instance
                                    $cssToInlineStyles = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles();
                                    // output
                                    $dce_form_email_content = $cssToInlineStyles->convert(
                                            $dce_form_email_content,
                                            $css
                                    );
                                    //$ajax_handler->add_error_message($dce_form_email_content); return true;
                                }

                                if (!empty($amail['dce_form_email_content_template_layout']) && $amail['dce_form_email_content_template_layout'] == 'html') {
                                    // from div to table
                                    $dce_form_email_content = DCE_Helper::tablefy($dce_form_email_content);
                                }

                                if ($amail['dce_form_email_content_template_style'] == 'embed') {
                                    $dce_form_email_content = '<style>' . $css . '</style>' . $dce_form_email_content;
                                }
                            }
                        } else {
                            $settings_raw = $record->get('form_settings');
                            // from message textarea with dynamic token
                            $dce_form_email_content = $settings_raw['dce_form_email_repeater'][$mkey]['dce_form_email_content'];
                            $attachments = $this->get_email_attachments($dce_form_email_content, $fields, $amail);
                            $dce_form_email_content = $this->remove_attachment_tokens($dce_form_email_content, $fields);
                            $dce_form_email_content = $this->replace_content_shortcodes($dce_form_email_content, $record, $line_break);
                            $dce_form_email_content = DCE_Helper::get_dynamic_value($dce_form_email_content, $fields);
                            $dce_form_email_content = apply_filters('elementor_pro/forms/wp_mail_message', $dce_form_email_content);
                        }     
                        
                        $email_sent = wp_mail($email_fields['dce_form_email_to'], $email_fields['dce_form_email_subject'], $dce_form_email_content, $headers . $cc_header . $bcc_header, $attachments);
                        
                        /* if (!empty($email_fields['dce_form_email_to_bcc'])) {
                          $bcc_emails = explode(',', $email_fields['dce_form_email_to_bcc']);
                          foreach ($bcc_emails as $bcc_email) {
                          wp_mail(trim($bcc_email), $email_fields['dce_form_email_subject'], $dce_form_email_content, $headers);
                          }
                          } */

                        /**
                         * Elementor form mail sent.
                         *
                         * Fires when an email was sent successfully.
                         *
                         * @since 1.0.0
                         *
                         * @param array       $settings Form settings.
                         * @param Form_Record $record   An instance of the form record.
                         */
                        do_action('elementor_pro/forms/mail_sent', $amail, $record);
                        //do_action('dynamic_content_for_elementor/forms/mail_sent', $settings, $record);

                        if (!$email_sent) {
                            $ajax_handler->add_error_message(\ElementorPro\Modules\Forms\Classes\Ajax_Handler::get_default_message(\ElementorPro\Modules\Forms\Classes\Ajax_Handler::SERVER_ERROR, $amail));
                        }
                    }
                }
            }
        }
        
        public function remove_attachment_tokens($dce_form_email_content, $fields) {
            $attachments_tokens = explode(':attachment]', $dce_form_email_content);
            foreach ($attachments_tokens as $akey => $avalue) {
                $pieces = explode('[form:', $avalue);
                if (count($pieces) > 2) {
                    $field = end($pieces);
                    if (isset($fields[$field])) {
                        $dce_form_email_content = str_replace('[form:'.$field.':attachment]', '', $dce_form_email_content);
                    }
                }
            }
            return $dce_form_email_content;
        }
        
        public function get_email_attachments($dce_form_email_content, $fields, $amail) {
            $attachments = array();
            $pdf_attachment = '<!--[dce_form_pdf:attachment]-->';
            $pdf_form = '[form:pdf]';
            $pos_pdf_token = strpos($dce_form_email_content, $pdf_attachment);
            $pos_pdf_form = strpos($dce_form_email_content, $pdf_form);
            if ($pos_pdf_token !== false || $pos_pdf_form !== false) {
                // add PDF as attachment
                global $dce_form;
                if (isset($dce_form['pdf']) && isset($dce_form['pdf']['path'])) {
                    $pdf_path = $dce_form['pdf']['path'];
                    $attachments[] = $pdf_path;
                }
                $dce_form_email_content = str_replace($pdf_attachment, '', $dce_form_email_content);
                $dce_form_email_content = str_replace($pdf_form, '', $dce_form_email_content);
            }

            $attachments_tokens = explode(':attachment]', $dce_form_email_content);
            foreach ($attachments_tokens as $akey => $avalue) {
                $pieces = explode('[form:', $avalue);
                if (count($pieces) > 1) {
                    $field = end($pieces);
                    if (isset($fields[$field])) {
                        $file_path = DCE_Helper::url_to_path($fields[$field]);
                        if (is_file($file_path)) {
                            $attachments[] = $file_path;
                        }
                    }
                }
            }
            if ($amail['dce_form_email_attachments']) {                
                if (!empty($fields) && is_array($fields)) {
                    foreach ($fields as $akey => $adata) {
                        if (filter_var($adata, FILTER_VALIDATE_URL)) {
                            //$adata = str_replace(get_bloginfo('url'), WP, $value);
                            $file_path = DCE_Helper::url_to_path($adata);
                            if (is_file($file_path)) {
                                if (!in_array($file_path, $attachments)) {
                                    $attachments[] = $file_path;
                                }
                            }
                        }
                    }
                }
            }
            return $attachments;
        }

        /**
         * @param string      $email_content
         * @param Form_Record $record
         *
         * @return string
         */
        public function replace_content_shortcodes($email_content, $record, $line_break) {            
            $all_fields_shortcode = '[all-fields]';
            if (false !== strpos($email_content, $all_fields_shortcode)) {
                $text = '';
                foreach ($record->get('fields') as $field) {
                    $formatted = $this->field_formatted($field);
                    if (( 'textarea' === $field['type'] ) && ( '<br>' === $line_break )) {
                        $formatted = str_replace(["\r\n", "\n", "\r"], '<br />', $formatted);
                    }
                    $text .= $formatted . $line_break;
                }
                $email_content = str_replace($all_fields_shortcode, $text, $email_content);
            }
            return $email_content;
        }

        private function field_formatted($field) {
            $formatted = '';
            if (!empty($field['title'])) {
                $formatted = sprintf('%s: %s', $field['title'], $field['value']);
            } elseif (!empty($field['value'])) {
                $formatted = sprintf('%s', $field['value']);
            }
            return $formatted;
        }
        
        public static function add_dce_email_template_type() {
            // Add Email Template Type
            include_once( DCE_PATH .'modules/theme-builder/documents/DCE_Email.php' );
            $dce_email = '\ElementorPro\Modules\ThemeBuilder\Documents\DCE_Email';
            \Elementor\Plugin::instance()->documents->register_document_type( $dce_email::get_name_static(), \ElementorPro\Modules\ThemeBuilder\Documents\DCE_Email::get_class_full_name() );
            \Elementor\TemplateLibrary\Source_Local::add_template_type( \ElementorPro\Modules\ThemeBuilder\Documents\DCE_Email::get_name_static() );
            add_filter( 'elementor_pro/editor/localize_settings', '\ElementorPro\Modules\ThemeBuilder\Documents\DCE_Email::dce_add_more_types' );
        }

    }
    
    

}