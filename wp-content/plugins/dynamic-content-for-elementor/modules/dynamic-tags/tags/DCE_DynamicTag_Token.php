<?php

namespace DynamicContentForElementor\Modules\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Tag;
use \Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class DCE_DynamicTag_Token extends Tag {

    public function get_name() {
        return 'dce-token';
    }

    public function get_title() {
        return __('Token', 'dynamic-content-for-elementor');
    }

    public function get_group() {
        return 'dce';
    }

    public function get_categories() {
        return [
            'base', //\Elementor\Modules\DynamicTags\Module::BASE_GROUP
            'text', //\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
            'url', //\Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
            'number', //\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
            'post_meta', //\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY
            'date', //\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
            'datetime', //\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
            'media', //\Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
            'image', //\Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY,
            'gallery', //\Elementor\Modules\DynamicTags\Module::GALLERY_CATEGORY,
        ];
    }

    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/tokens/';
    }

    /**
     * Register Controls
     *
     * Registers the Dynamic tag controls
     *
     * @since 2.0.0
     * @access protected
     *
     * @return void
     */
    protected function _register_controls() {

        $objects = array('post', 'user', 'term');

        $this->add_control(
                'dce_token_wizard',
                [
                    'label' => __('Wizard mode', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                ]
        );

        $this->add_control(
                'dce_token',
                [
                    'label' => __('Token', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true,
                    'placeholder' => '[post:title], [post:meta_key], [user:display_name], [term:name], [wp_query:posts]',
                    'condition' => [
                        'dce_token_wizard' => '',
                    ],
                ]
        );

        $this->add_control(
                'dce_token_object', [
            'label' => __('Object', 'dynamic-content-for-elementor'),
            'label_block' => true,
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'post' => [
                    'title' => __('Post', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-files-o',
                ],
                'user' => [
                    'title' => __('User', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-users',
                ],
                'term' => [
                    'title' => __('Term', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-tags',
                ],
                /*'comment' => [
                    'title' => __('Comment', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-comments',
                ],*/
                'option' => [
                    'title' => __('Option', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-list',
                ],
                'wp_query' => [
                    'title' => __('WP Query', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-search',
                ],
                'date' => [
                    'title' => __('Date', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-calendar',
                ],
                'system' => [
                    'title' => __('System', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-cogs',
                ]
            ],
            'default' => 'post',
            'toggle' => false,
            'condition' => [
                'dce_token_wizard!' => '',
            ],
                ]
        );

        $this->add_control(
                'dce_token_field_date',
                [
                    'label' => __('Date Modificator', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => '+1 week, -2 mounths, yesterday, timestamp',
                    'description' => __('A time modificator compabile with strtotime OR a timestamp', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'condition' => [
                        'dce_token_wizard!' => '',
                        'dce_token_object' => 'date',
                    ],
                ]
        );
        $this->add_control(
                'dce_token_field_date_format',
                [
                    'label' => __('Date Format', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => 'Y-m-d H:i:s',
                    'label_block' => true,
                    'condition' => [
                        'dce_token_wizard!' => '',
                        'dce_token_object' => 'date',
                    ],
                ]
        );
        
        $this->add_control(
                'dce_token_field_system',
                [
                    'label' => __('Field', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true,
                    'placeholder' => __('_GET, _POST, _SERVER, MY_CONSTANT', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'dce_token_wizard!' => '',
                        'dce_token_object' => 'system',
                    ],
                ]
        );

        foreach ($objects as $aobj) {
            $this->add_control(
                    'dce_token_field_' . $aobj,
                    [
                        'label' => __('Field', 'dynamic-content-for-elementor'),
                        'type' => 'ooo_query',
                        'placeholder' => __('Meta key or Field Name', 'dynamic-content-for-elementor'),
                        'label_block' => true,
                        'query_type' => 'fields',
                        'object_type' => $aobj,
                        'condition' => [
                            'dce_token_wizard!' => '',
                            'dce_token_object' => $aobj,
                        ],
                    ]
            );
        }
        
        $this->add_control(
                    'dce_token_field_option',
                    [
                        'label' => __('Field', 'dynamic-content-for-elementor'),
                        'type' => 'ooo_query',
                        'placeholder' => __('Option key', 'dynamic-content-for-elementor'),
                        'label_block' => true,
                        'query_type' => 'options',
                        'condition' => [
                            'dce_token_wizard!' => '',
                            'dce_token_object' => 'option',
                        ],
                    ]
            );


        $this->add_control(
                'dce_token_subfield',
                [
                    'label' => __('SubField', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true,
                    'placeholder' => __('my_sub:label', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'dce_token_wizard!' => '',
                        'dce_token_object!' => 'date',
                    ],
                ]
        );
        /*$this->add_control(
                'dce_token_source',
                [
                    'label' => __('Source', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true,
                    'condition' => [
                        'dce_token_wizard!' => '',
                    ],
                ]
        );*/
        foreach ($objects as $aobj) {
            $this->add_control(
                    'dce_token_source_'.$aobj,
                    [
                        'label' => __('Source', 'dynamic-content-for-elementor'),
                        'type' => 'ooo_query',
                        'placeholder' => __('Search '.ucfirst($aobj), 'dynamic-content-for-elementor'),
                        'label_block' => true,
                        'query_type' => $aobj.'s',
                        'condition' => [
                            'dce_token_wizard!' => '',
                            'dce_token_object' => $aobj,
                        ],
                    ]
            );
        }
        
        $this->add_control(
                'dce_token_filter',
                [
                    'label' => __('Filters', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                    'rows' => 2,
                    'placeholder' => 'trim',
                    'label_block' => true,
                    'condition' => [
                        'dce_token_wizard!' => '',
                    ],
                ]
        );

        $this->add_control(
                'dce_token_code',
                [
                    'label' => __('Show code', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'condition' => [
                        'dce_token_wizard!' => '',
                    ],
                ]
        );
        
        $this->add_control(
                'dce_token_help', [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => '<div id="elementor-panel__editor__help" class="p-0"><a id="elementor-panel__editor__help__link" href="' . $this->get_docs() . '" target="_blank">' . __('Need Help', 'elementor') . ' <i class="eicon-help-o"></i></a></div>',
            'separator' => 'before',
                ]
        );
    }

    public function render() {
        $settings = $this->get_settings_for_display(null, true);
        if (empty($settings))
            return;
        
        if (!empty($settings['dce_token_wizard'])) {
            $objects = array('post', 'user', 'term');
            
            $token = '[';
            
            $token .= $settings['dce_token_object'];
            
            foreach ($objects as $aobj) {
                if ($settings['dce_token_field_'.$aobj]) {
                    $token .= ':'.$settings['dce_token_field_'.$aobj];
                }
            }
            if ($settings['dce_token_field_date']) {
                $token .= ':'.$settings['dce_token_field_date'];
            }
            if ($settings['dce_token_field_date_format']) {
                $token .= '|'.$settings['dce_token_field_date_format'];
            }
            
            if ($settings['dce_token_field_system']) {
                $token .= ':'.$settings['dce_token_field_system'];
            }
            if ($settings['dce_token_field_option']) {
                $token .= ':'.$settings['dce_token_field_option'];
            }
            
            if ($settings['dce_token_subfield']) {
                $token .= ':'.$settings['dce_token_subfield'];
            }
            
            if ($settings['dce_token_filter']) {
                $filters = explode(PHP_EOL, $settings['dce_token_filter']);
                $token .= '|'.implode('|', $filters);
            }
            
            foreach ($objects as $aobj) {
                if ($settings['dce_token_source_'.$aobj]) {
                    $token .= '|'.$settings['dce_token_source_'.$aobj];
                }
            }
            
            $token .= ']';
            //var_dump(\Elementor\Plugin::$instance->editor->is_edit_mode());
            //var_dump($_GET['elementor-preview']);
            if (/*\Elementor\Plugin::$instance->editor->is_edit_mode()*/ /*isset($_GET['elementor-preview']) &&*/ $settings['dce_token_code']) {
                echo $token; 
                return;
            }
        } else {
            $token = $settings['dce_token'];
        }
        
        $value = \DynamicContentForElementor\DCE_Helper::get_dynamic_value($token);
        
        if ( empty( $value ) && $this->get_settings( 'fallback' ) ) {
                $value = $this->get_settings( 'fallback' );
                $value = \DynamicContentForElementor\DCE_Helper::get_dynamic_value($value);
        }
        //echo \DynamicContentForElementor\DCE_Tokens::do_tokens($settings['dce_token']);
        echo $value;
    }

}
