<?php

namespace DynamicContentForElementor\Extensions;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 *
 * Animations Effects
 *
 */

class DCE_Extension_Editor extends DCE_Extension_Prototype {
    
    public $name = 'Enchanted Editor';
    
    static public function is_enabled() {
        return true;
    }

    private $is_common = false;

    public static function get_description() {
        return __('Add some useful scripts for a quickest work in Elementor Backend Editor');
    }
    
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/enchanted-editor/';
    }

    protected function add_actions() {

        add_action('elementor/editor/after_enqueue_scripts', function() {
            wp_register_script(
                    'dce-script-editor-enchant', plugins_url('/assets/js/dce-editor-enchant.js', DCE__FILE__), [], DCE_VERSION
            );
            wp_enqueue_script('dce-script-editor-enchant');
        });
        
    }
    

}
