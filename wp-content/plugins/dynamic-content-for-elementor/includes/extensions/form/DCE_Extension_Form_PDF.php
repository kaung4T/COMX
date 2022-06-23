<?php

namespace DynamicContentForElementor\Extensions;

use Elementor\Controls_Manager;
use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\DCE_Tokens;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

function _dce_extension_form_pdf($field) {
    switch ($field) {
        case 'enabled':
            return true;
        case 'docs':
            return 'https://www.dynamic.ooo/widget/pdf-generator-for-elementor-pro-form/';
        case 'description' :
            return __('Add PDF Creation Actions to Elementor PRO Form', 'dynamic-content-for-elementor');
    }
}

if (!DCE_Helper::is_plugin_active('elementor-pro')) {

    class DCE_Extension_Form_PDF extends DCE_Extension_Prototype {

        public $name = 'Form PDF';
        private $is_common = false;
        public static $depended_plugins = ['elementor-pro'];

        static public function is_enabled() {
            return _dce_extension_form_pdf('enabled');
        }

        public static function get_description() {
            return _dce_extension_form_pdf('description');
        }

        public function get_docs() {
            return _dce_extension_form_pdf('docs');
        }

    }

} else {

    class DCE_Extension_Form_PDF extends \ElementorPro\Modules\Forms\Classes\Action_Base {

        public $name = 'Form PDF';
        public static $depended_plugins = ['elementor-pro'];
        public $has_action = true;

        static public function is_enabled() {
            return _dce_extension_form_pdf('enabled');
        }

        public static function get_description() {
            return _dce_extension_form_pdf('description');
        }

        public function get_docs() {
            return _dce_extension_form_pdf('docs');
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
            return 'dce_form_pdf';
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
            return __('PDF', 'dynamic-content-for-elementor');
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
                    'section_dce_form_pdf',
                    [
                        'label' => $this->get_label(), //__('DCE', 'dynamic-content-for-elementor'),
                        'condition' => [
                            'submit_actions' => $this->get_name(),
                        ],
                    ]
            );

            $widget->add_control(
                    'dce_form_pdf_name', [
                'label' => __('Name', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '[date|U]',
                'description' => __('The PDF file name, the .pdf extension will automatically added', 'dynamic-content-for-elementor'),
                'label_block' => true,
                    ]
            );

            $widget->add_control(
                    'dce_form_pdf_folder', [
                'label' => __('Folder', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'elementor/pdf/[date|Y]/[date|m]',
                'description' => __('The directory inside /wp-content/uploads/xxx where save the PDF file', 'dynamic-content-for-elementor'),
                'label_block' => true,
                    ]
            );

            $widget->add_control(
                    'dce_form_pdf_template',
                    [
                        'label' => __('Template', 'dynamic-content-for-elementor'),
                        'type' => 'ooo_query',
                        'placeholder' => __('Template Name', 'dynamic-content-for-elementor'),
                        'label_block' => true,
                        'query_type' => 'posts',
                        'object_type' => 'elementor_library',
                        'description' => 'Use a Elementor Template as body fo this PDF.',
                    ]
            );

            $paper_sizes = array_keys(\Dompdf\Adapter\CPDF::$PAPER_SIZES);
            $tmp = array();
            foreach ($paper_sizes as $asize) {
                $tmp[$asize] = strtoupper($asize);
            }
            $paper_sizes = $tmp;
            $widget->add_control(
                    'dce_form_pdf_size',
                    [
                        'label' => __('Page Size', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'a4',
                        'options' => $paper_sizes,
                    ]
            );

            $widget->add_control(
                    'dce_form_pdf_orientation', [
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
            $widget->add_control(
                'dce_form_pdf_margin', [
                'label' => __('Page Margin', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                    ]
            );

            $widget->add_control(
                    'dce_form_pdf_save', [
                'label' => __('Save', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'description' => __('Save the generated PDF file as Media', 'dynamic-content-for-elementor'),
                    ]
            );
            $widget->add_control(
                    'dce_form_pdf_title', [
                'label' => __('Title', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Form data by [field id="name"] in [date|Y-m-d H:i:s]',
                'description' => __('The PDF file Title', 'dynamic-content-for-elementor'),
                'label_block' => true,
                'condition' => [
                    'dce_form_pdf_save!' => '',
                ],
                    ]
            );
            $widget->add_control(
                    'dce_form_pdf_content', [
                'label' => __('Description', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '[field id="message"]',
                'description' => __('The PDF file Description', 'dynamic-content-for-elementor'),
                'label_block' => true,
                'condition' => [
                    'dce_form_pdf_save!' => '',
                ],
                    ]
            );
            
            $widget->add_control(
                    'dce_form_pdf_help', [
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

            $this->dce_elementor_form_pdf($fields, $settings, $ajax_handler);
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

        function dce_elementor_form_pdf($fields, $settings = null, $ajax_handler = null) {
            global $dce_form, $post;
            
            if (empty($settings['dce_form_pdf_template'])) {
                $ajax_handler->add_error_message(__('Error: PDF Template not found or not setted', 'dynamic-content-for-elementor'));
                return;
            }
            // verify Template 
            $template = get_post($settings['dce_form_pdf_template']);
            if (!$template || $template->post_type != 'elementor_library') {
                $ajax_handler->add_error_message(__('Error: PDF Template not setted correctly', 'dynamic-content-for-elementor'));
                return;
            }
            
            $post = get_post($fields['submitted_on_id']); // to retrive dynamic data from post where the form was submitted
            
            $pdf_folder = '/' . $settings['dce_form_pdf_folder'] . '/';

            $upload = wp_upload_dir();
            $pdf_dir = $upload['basedir'] . $pdf_folder;
            $pdf_url = $upload['baseurl'] . $pdf_folder;
            $pdf_name = $settings['dce_form_pdf_name'] . '.pdf';            
            $dce_form['pdf']['path'] = $pdf_dir . $pdf_name;
            $dce_form['pdf']['url'] = $pdf_url . $pdf_name;
            //var_dump($dce_form); die();
            $pdf_html = do_shortcode('[dce-elementor-template id="' . $settings['dce_form_pdf_template'] . '"]');
            $pdf_html = DCE_Helper::get_dynamic_value($pdf_html, $fields);
            
            // add CSS
            $css = DCE_Helper::get_post_css($settings['dce_form_pdf_template']);
            // from flex to table
            $css .= '.elementor-section .elementor-container { display: table !important; width: 100% !important; }';
            $css .= '.elementor-row { display: table-row !important; }';
            $css .= '.elementor-column { display: table-cell !important; }';
            $css .= '.elementor-column-wrap, .elementor-widget-wrap { display: block !important; }';
            $css = str_replace(':not(.elementor-motion-effects-element-type-background) > .elementor-element-populated', ':not(.elementor-motion-effects-element-type-background)', $css);
            $css .= '.elementor-column .elementor-widget-image .elementor-image img { max-width: none !important; }';
            $cssToInlineStyles = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles();
            $pdf_html = $cssToInlineStyles->convert(
                $pdf_html,
                $css
            );
            
            // link image from url to path
            $site_url = site_url();
            //$pdf_html = str_replace('src="'.$site_url, 'src="'.$upload['basedir'], $pdf_html);            
            
            // from div to table
            //$pdf_html = DCE_Helper::tablefy($pdf_html);
            
            //$ajax_handler->add_error_message($pdf_html); return false; 
            $pdf_html .= '<style>@page { margin: '.$settings['dce_form_pdf_margin']['top'].$settings['dce_form_pdf_margin']['unit'].' '.$settings['dce_form_pdf_margin']['right'].$settings['dce_form_pdf_margin']['unit'].' '.$settings['dce_form_pdf_margin']['bottom'].$settings['dce_form_pdf_margin']['unit'].' '.$settings['dce_form_pdf_margin']['left'].$settings['dce_form_pdf_margin']['unit'].'; }</style>';

            if (!is_dir($pdf_dir)) {
                // create dir
                mkdir($pdf_dir, 0777, true);
            }

            // https://github.com/dompdf/dompdf
            //$auth = base64_encode("username:password");
            $context = stream_context_create(array(
              'ssl' => array(
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                // 'allow_self_signed'=> TRUE
              ),
              /*'http' => array(
                'header' => "Authorization: Basic $auth"
              )*/
            ));
            
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', TRUE);
            $options->setIsRemoteEnabled(true);
            //$options->set('defaultFont', 'Courier');
            // instantiate and use the dompdf class
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->setHttpContext($context);
            $dompdf->loadHtml($pdf_html);
            //echo $pdf_html; die();
            $dompdf->set_option('isRemoteEnabled', TRUE);
            $dompdf->set_option('isHtml5ParserEnabled', true);
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper($settings['dce_form_pdf_size'], $settings['dce_form_pdf_orientation']);
            // Render the HTML as PDF
            $dompdf->render();
            // Output the generated PDF to Browser
            //$dompdf->stream();
            $output = $dompdf->output();
            if (!file_put_contents($pdf_dir . $pdf_name, $output)) {
                $ajax_handler->add_error_message(__('Error generating PDF', 'dynamic-content-for-elementor'));
            }
            
            if ($settings['dce_form_pdf_save']) {
                // Insert the post into the database
                
                // https://codex.wordpress.org/Function_Reference/wp_insert_attachment
                // $filename should be the path to a file in the upload directory.
                $filename = $dce_form['pdf']['path'];
                // The ID of the post this attachment is for.
                $parent_post_id = $fields['submitted_on_id'];
                // Check the type of file. We'll use this as the 'post_mime_type'.
                $filetype = wp_check_filetype( basename( $filename ), null );
                // Get the path to the upload directory.
                $wp_upload_dir = wp_upload_dir();
                // Prepare an array of post data for the attachment.
                $attachment = array(
                        'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
                        'post_mime_type' => $filetype['type'],
                        'post_status'    => 'inherit',
                        'post_title' => $settings['dce_form_pdf_title'],
                        'post_content' => $settings['dce_form_pdf_content'],
                );
                // Insert the attachment.
                $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
                wp_update_attachment_metadata( $attach_id, $attach_data );
                //set_post_thumbnail( $parent_post_id, $attach_id );
                
                
                // https://developer.wordpress.org/reference/functions/wp_insert_post/
                /*$db_ins = array(
                    'post_title' => $settings['dce_form_pdf_title'],
                    'post_status' => 'public',
                    'post_type' => 'attachment',
                    'post_content' => $settings['dce_form_pdf_content'],
                );
                $obj_id = wp_insert_post($db_ins);*/

                if ($attach_id) {
                    $dce_form['pdf']['id'] = $attach_id;
                    $dce_form['pdf']['title'] = $settings['dce_form_pdf_title'];
                    $dce_form['pdf']['description'] = $settings['dce_form_pdf_content'];
                    if (!empty($fields) && is_array($fields)) {
                        foreach ($fields as $akey => $adata) {
                            update_post_meta($attach_id, $akey, $adata);
                        }
                    }
                } else {
                    $ajax_handler->add_error_message(__('Error saving PDF as Media', 'dynamic-content-for-elementor'));
                }
            }
        }

    }

}