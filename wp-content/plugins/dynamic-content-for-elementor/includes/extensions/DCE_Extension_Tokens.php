<?php

namespace DynamicContentForElementor\Extensions;

use DynamicContentForElementor\DCE_Tokens;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 *
 * Animations Effects
 *
 */

class DCE_Extension_Tokens extends DCE_Extension_Prototype {
    
    public $name = 'Tokens';
    
    public static $dce_token_types = [
        Controls_Manager::TEXT, 
        Controls_Manager::TEXTAREA,
        Controls_Manager::WYSIWYG,
        Controls_Manager::CODE, 
        Controls_Manager::NUMBER,
        Controls_Manager::URL, 
    ];
    
    static public function is_enabled() {
        return true;
    }

    private $is_common = true;

    public static function get_description() {
        return __('Add support for Tokens in Dynamic Tag for Text, Number and Textarea settings');
    }
    
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/dynamic-tag-token/';
    }
    
    public function init($param = null) {
        
        parent::init();
        
        $this->add_dynamic_tags();
        
        // activate Token Shorcode
        add_shortcode('dce-token', [$this, 'do_shortcode']);
        
        // add token to 
        add_filter('widget_text', [$this, 'add_dce_to_widget']);
    }
    
    public function add_dce_to_widget($text) {
        $new_content = DCE_Tokens::do_tokens($text);
        //$new_content .= '<br>:)';
        return $new_content;
    }   
    
    public function do_shortcode($params = array()) {
        if (empty($params['value'])) {
            return '';
        }
        $override_id = '';
        if (!empty($params['id'])) {
            $override_id = '|'.intval($params['id']);
        }
        return DCE_Tokens::do_tokens('['.$params['value'].$override_id.']');
    }
    
    /**
    * Register tags.
    *
    * Add all the available dynamic tags.
    *
    * @since 2.0.0
    * @access public
    *
    * @param Manager $dynamic_tags
    */
    public function add_dynamic_tags() {
        add_action( 'elementor/dynamic_tags/register_tags', function( $dynamic_tags ) {
            // In our Dynamic Tag we use a group named request-variables so we need 
            // To register that group as well before the tag
            \Elementor\Plugin::$instance->dynamic_tags->register_group( 'dce', [
                'title' => 'Dynamic Content' 
            ] );

            // Include the Dynamic tag class file
            include_once( DCE_PATH . '/modules/dynamic-tags/tags/DCE_DynamicTag_Token.php' );

            // Finally register the tag
            $class_name = '\\DynamicContentForElementor\\Modules\\DynamicTags\\Tags\\DCE_DynamicTag_Token';
            $dynamic_tags->register_tag( $class_name );
        } );
    }
    
    public static function _add_dynamic_tags($control_data) {
        if (!empty($control_data)) {
            foreach ($control_data as $key => $acontrol) {
                if ($key != 'dynamic') {
                    if (is_array($acontrol)) {
                        $control_data[$key] = self::_add_dynamic_tags($acontrol);
                    }         
                }
            }
        }
        if (isset($control_data['type'])) {
            if ( in_array($control_data['type'], self::$dce_token_types) ) {
                if (!isset($control_data['dynamic'])) {
                    $control_data['dynamic']['active'] = true; // add it
                } else {
                    if (isset($control_data['dynamic']['active'])) {
                        // natively
                    } else {
                        // active => false, so no force them
                    }
                }
            }
        }
        return $control_data;
    }

}
