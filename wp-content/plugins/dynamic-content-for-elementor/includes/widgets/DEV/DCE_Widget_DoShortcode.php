<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor DoShortcode
 *
 * Elementor widget for Dinamic Content Elements
 *
 */

class DCE_Widget_DoShortcode extends DCE_Widget_Prototype {
    
    public function get_name() {
        return 'dyncontel-doshortcode';
    }
    
    static public function is_enabled() {
        return true;
    }
    
    public function get_title() {
        return __('DoShortcode', 'dynamic-content-for-elementor');
    }
    public function get_description() {
        return __('Apply a WordPress shortcode', 'dynamic-content-for-elementor');
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/doshortcode/';
    }
    public function get_icon() {
        return 'icon-dyn-doshortc';
    }
    
    protected function _register_controls() {
        $this->start_controls_section(
                'section_doshortcode', [
                'label' => __('DoShortcode', 'dynamic-content-for-elementor'),
            ]
        );
       $this->add_control(
          'doshortcode_string',
          [
             'label'   => __( 'Shortcode', 'dynamic-content-for-elementor' ),
             'type'    => Controls_Manager::TEXTAREA,
             'description' => 'ex: [gallery ids="66,67,28"]'
          ]
        );
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $doshortcode_string = $settings['doshortcode_string'];
        if( $doshortcode_string != '' ){
            echo do_shortcode( $doshortcode_string );
        }
    }

}
