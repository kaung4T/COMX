<?php
namespace Aepro;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ShowOnHover{

    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {


        add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ] );
        add_action('elementor/frontend/element/before_render',[ $this, 'before_section_render'],10,1);
    }

    public function get_name() {
        return 'showonhover';
    }

    public function register_controls($element){

            $element->start_controls_section(
                'show_on_hover',
                [
                    'tab' => Controls_Manager::TAB_ADVANCED,
                    'label' => __( 'AE Pro - Advance', 'ae-pro' ),
                ]
            );

            $element->add_control(
                'show_on_hover_enabled',
                [
                    'label' => __( 'Show On Hover', 'ae-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __( 'Yes', 'ae-pro' ),
                    'label_off' => __( 'No', 'ae-pro' ),
                    'return_value' => 'yes',
                    //'prefix_class'  => 'ae-show-on-hover-'
                ]
            );

        $element->add_control(
            'hover_setting',
            [
                'label' => __( 'Hover', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'show_on_section_hover' => __( 'Show on Section Hover', 'ae-pro' ),
                    'show_on_column_hover' => __( 'Show on Column Hover', 'ae-pro' ),
                    'hide_on_section_hover' => __( 'Hide on Section Hover', 'ae-pro' ),
                    'hide_on_column_hover' => __( 'Hide on Column Hover', 'ae-pro' ),
                ],
                'default' => 'show_on_section_hover',
                'prefix_class'  => '',
                'condition' => [
                    'show_on_hover_enabled' => 'yes',
                ]
            ]
        );

            $element->end_controls_section();

    }

    function before_section_render($element){

        //$hover_style = '';
        //$hover_style .= '<style>';
        //$hover_style .= '.elementor-element-' . $element->get_id() . ' .ae-show-on-hover-yes{ display: none; }';
        //$hover_style .= '.elementor-element-' . $element->get_id() . ':hover .ae-show-on-hover-yes{ display: block; }';
        //$hover_style .= '</style>';
        //echo $hover_style;
    }

}
ShowOnHover::instance();