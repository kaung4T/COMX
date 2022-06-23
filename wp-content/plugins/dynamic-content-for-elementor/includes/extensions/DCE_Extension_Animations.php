<?php

namespace DynamicContentForElementor\Extensions;

use Elementor\Controls_Manager;
use DynamicContentForElementor\Controls\DCE_Group_Control_Animation_Element;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 *
 * Animations Effects
 *
 */

class DCE_Extension_Animations extends DCE_Extension_Prototype {
    
    public $name = 'Animations';
    public $has_controls = true;
    protected $is_common = true;
    
    public static function get_description() {
        return __('Predefined CSS-Animations with keyframe.');
    }
    
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/loop-animations/';
    }

    private function add_controls($element, $args) {

        $element_type = $element->get_type();
        
        $element->add_control(
                'enabled_animations', [
            'label' => __('Enable Animations', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
                ]
        );
        $element->add_group_control(
                DCE_Group_Control_Animation_Element::get_type(), [
            'name' => 'animate_image',
            'selector' => '{{WRAPPER}} .dce-animations',
            'condition' => [
                'enabled_animations' => 'yes',
            ],
                ]
        );
        
    }

    protected function add_actions() {

        // Activate controls for widgets
        add_action('elementor/element/common/dce_section_animations_advanced/before_section_end', function( $element, $args ) {
            $this->add_controls($element, $args);
        }, 10, 2);
        
        add_filter('elementor/widget/print_template', array($this, 'animations_print_template'), 10, 2);

        add_action('elementor/widget/render_content', array($this, 'animations_render_template'), 10, 2);
    }
    public function animations_print_template($content, $widget) {
        if (!$content)
            return '';

        $content = "<# if ( settings.enabled_animations ) { #><div class=\"dce-animations\">" . $content . "</div><# } else { #>" . $content . "<# } #>";
        return $content;
    }

    public function animations_render_template($content, $widget) {
        $settings = $widget->get_settings_for_display();

        if ($settings['enabled_animations']) {

            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                
            }
            $content = '<div class="dce-animations">' . $content . '</div>';
        }
        return $content; // mostro il widget
    }
}