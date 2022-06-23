<?php

namespace DynamicContentForElementor\Extensions;

use Elementor\Controls_Manager;
use DynamicContentForElementor\Controls\DCE_Group_Control_Transform_Element;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 *
 * Widget Transforms
 *
 */

class DCE_Extension_Transforms extends DCE_Extension_Prototype {

    public $name = 'Transforms';
    public $has_controls = true;
    public static function get_description() {
        return __('Apply CSS Transforms to Element');
    }
    
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/transforms/';
    }

    private function add_controls($element, $args) {

        $element_type = $element->get_type();

        $element->add_control(
                'enabled_transform', [
            'label' => __('Enabled Transforms', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
                ]
        );

        $element->add_group_control(
                DCE_Group_Control_Transform_Element::get_type(), [
            'name' => 'transforms',
            'label' => 'Transforms',
            'default' => '',
            'selector' => '{{WRAPPER}} .dce-transforms',
            'condition' => [
                'enabled_transform!' => '',
            ],
                ]
        );
    }


    protected function add_actions() {

        // Activate controls for widgets
        add_action('elementor/element/common/dce_section_transforms_advanced/before_section_end', function( $element, $args ) {
            $this->add_controls($element, $args);
        }, 10, 2);

        add_filter('elementor/widget/print_template', array($this, 'transforms_print_template'), 10, 2);

        add_action('elementor/widget/render_content', array($this, 'transforms_render_template'), 10, 2);
    }

    public function transforms_print_template($content, $widget) {
        if (!$content)
            return '';

        $content = "<# if ( settings.enabled_transform ) { #><div class=\"dce-transforms\"><div class=\"dce-transforms-wrap\">" . $content . "</div></div><# } else { #>" . $content . "<# } #>";
        return $content;
    }

    public function transforms_render_template($content, $widget) {
        $settings = $widget->get_settings_for_display();

        if ($settings['enabled_transform']) {

            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                
            }
            $content = '<div class="dce-transforms"><div class="dce-transforms-wrap">' . $content . '</div></div>';
        }
        return $content; // mostro il widget
    }

}
