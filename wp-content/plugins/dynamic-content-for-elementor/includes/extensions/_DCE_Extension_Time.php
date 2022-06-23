<?php

namespace DynamicContentForElementor\Extensions;

use Elementor\Controls_Manager;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Visibility extenstion
 *
 * Conditional Visibility Widgets & Rows/Sections
 *
 * @since 1.0.1
 */
class DCE_Extension_Time extends DCE_Extension_Prototype {

    public $name = 'Time';
    public $has_controls = true;
    
    public $microtime = 0;
    
    public $common_sections_actions = array(
        array(
            'element' => 'common',
            'action' => '_section_style',
        ),
        array(
            'element' => 'section',
            'action' => 'section_advanced',
        )
    );
    

    /**
     * The description of the current extension
     *
     * @since 0.5.4
     * */
    public static function get_description() {
        return __('Rendering time for Debug Widgets', 'dynamic-content-for-elementor');
    }
    
    public function get_docs() {
        return 'https://www.dynamic.ooo/';
    }

    /**
     * Add Actions
     *
     * @since 0.5.5
     *
     * @access private
     */
    protected function add_actions() {
        
        add_action("elementor/frontend/widget/before_render", function( $element ) {
            $this->microtime = microtime(true);
        }, 10, 1);
        
        add_action("elementor/frontend/widget/after_render", function( $element ) {
            $render_time = microtime(true) - $this->microtime;
            
            if (WP_DEBUG) {
                if (is_user_logged_in()){
                    if (\Elementor\Plugin::$instance->editor->is_edit_mode() || current_user_can('administrator')) {
                        echo '<div><small>';
                        echo '<b>Name:</b> '.$element->get_name().'<br>';
                        echo '<b>ID:</b> '.$element->get_id().'<br>';
                        echo '<b>Time:</b> '.$render_time;
                        echo '</small></div>';
                    }
                }
            }
        }, 10, 1);

    }

}
