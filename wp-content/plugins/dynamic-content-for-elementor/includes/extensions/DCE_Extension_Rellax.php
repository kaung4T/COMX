<?php

namespace DynamicContentForElementor\Extensions;

use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 *
 * Parallax Effect - Rellax
 *
 */

class DCE_Extension_Rellax extends DCE_Extension_Prototype {

    public $name = 'Rellax';
    public $has_controls = true;
    public $common_sections_actions = array(
        array(
            'element' => 'common',
            'action' => '_section_style',
        ),
        
        array(
            'element' => 'column',
            'action' => 'section_advanced',
        ),


    );

    public static function get_description() {
        return __('Rellax Parallax rules for Widgets and Rows', 'dynamic-content-for-elementor');
    }
    
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/rellax-parallax/';
    }    

    public function get_script_depends() {        
        return ['dce-rellaxjs-lib','dce-rellax'];
    }

    private function add_controls($element, $args) {

        $element_type = $element->get_type();

        $element->add_control(
                'enabled_rellax', [
            'label' => __('Enabled Rellax', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
            'frontend_available' => true,
                //            
                ]
        );
        $element->add_responsive_control(
                'speed_rellax', [
            'label' => __('Speed', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -10,
                    'max' => 10,
                    'step' => 0.1,
                ]
            ],
            'render_type' => 'template',
            'frontend_available' => true,
            'condition' => [
                'enabled_rellax' => 'yes',
            ]
                ]
        );
        $element->add_responsive_control(
                'percentage_rellax', [
            'label' => __('Percentage', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 0.5,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.01,
                ]
            ],
            'render_type' => 'template',
            'condition' => [
                'enabled_rellax' => 'yes',
            ]
                ]
        );
        $element->add_control(
                'zindex_rellax', [
            'label' => __('Z-Index', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'min' => -1,
            'max' => 50,
            'step' => 1,
            'condition' => [
                'enabled_rellax' => 'yes',
            ]
                ]
        );
        /* $element->add_control(
          'vertical_rellax',
          [
          'label' => __( 'Vertical', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::SWITCHER,
          'default' => 'yes',
          'label_on' => __( 'Yes', 'dynamic-content-for-elementor' ),
          'label_off' => __( 'No', 'dynamic-content-for-elementor' ),
          'return_value' => 'yes',
          'frontend_available' => true,
          'condition'     => [
          'enabled_rellax' => 'yes',
          ]
          ]
          );
          $element->add_control(
          'horizontal_rellax',
          [
          'label' => __( 'Horizontal', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::SWITCHER,
          'default' => '',
          'label_on' => __( 'Yes', 'dynamic-content-for-elementor' ),
          'label_off' => __( 'No', 'dynamic-content-for-elementor' ),
          'return_value' => 'yes',
          'frontend_available' => true,
          'condition'     => [
          'enabled_rellax' => 'yes',
          ]
          ]
          ); */
    }

    protected function add_actions() {

        // Activate controls for widgets
        add_action('elementor/element/common/dce_section_rellax_advanced/before_section_end', function( $element, $args ) {
            $this->add_controls($element, $args);
        }, 10, 2);

        add_filter('elementor/widget/print_template', array($this, 'rellax_print_template'), 11, 2);

        add_action('elementor/widget/render_content', array($this, 'rellax_render_template'), 11, 2);

        // Activate controls for columns
        add_action('elementor/element/column/dce_section_rellax_advanced/before_section_end', function( $element, $args ) {
            $this->add_controls($element, $args);
        }, 10, 2);
    }

    public function rellax_print_template($content, $widget) {
        if (!$content)
            return '';

        $id_item = $widget->get_id();

        $content = "<# if ( '' !== settings.enabled_rellax ) { #><div id=\"rellax-{{id}}\" class=\"rellax\" data-rellax-percentage=\"{{ settings.percentage_rellax.size }}\" data-rellax-zindex=\"{{ settings.zindex_rellax }}\">" . $content . "</div><# } else { #>" . $content . "<# } #>";
        return $content;
    }

    public function rellax_render_template($content, $widget) {
        $settings = $widget->get_settings_for_display();
        //return var_export($widget, true);
        //echo $widget['ID'];
        if (isset($settings['enabled_rellax']) && $settings['enabled_rellax'] == 'yes') {
            
            $this->_enqueue_alles();

            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                
            }
            $id_item = $widget->get_id();
            //echo 'rellax is enable';
            $content = '<div id="rellax-' . $id_item . '" class="rellax" data-rellax-percentage="' . $settings['percentage_rellax']['size'] . '" data-rellax-zindex="' . $settings['zindex_rellax'] . '">' . $content . '</div>';
        }
        return $content; // mostro il widget
    }

}
