<?php
namespace DynamicContentForElementor\Controls;

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Custom transform-element group control
 *
 */
class DCE_Group_Control_Transform_Element extends Group_Control_Base {
    
    protected static $fields;

    public static function get_type() {
        return 'transform-element';
    }

    protected function init_fields() {
        $controls = [];
        $controls['transform_type'] = [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'custom',
        ];
        
        $controls['transform'] = [
            'label' => _x( 'Transformations', 'Transform Control', 'elementor' ),
            'type' => 'transforms',
            'responsive' => true,
            'render_type' => 'ui',
            'condition' => [
                'transform_type' => 'custom',
            ],
            'selectors' => [
                '{{SELECTOR}} > *:first-child' => 'transform: rotateZ({{ANGLE}}deg) rotateX({{ROTATE_X}}deg) rotateY({{ROTATE_Y}}deg) scale({{SCALE}}) translateX({{TRANSLATE_X}}px) translateY({{TRANSLATE_Y}}px) translateZ({{TRANSLATE_Z}}px);',
            ],
        ];
        
        /*$controls['origin_hr'] = [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ];*/
        $controls['transform-origin'] = [
            'label' => _x( 'Transform origin', 'Transform-origin x/y Control', 'elementor' ),
            'type' => 'xy_positions',
            'responsive' => true,
            'render_type' => 'ui',
            'condition' => [
                'transform_type' => 'custom',
            ],
            'selectors' => [
                '{{SELECTOR}} > *:first-child' => 'transform-origin: {{X}}% {{Y}}%; -webkit-transform-origin: {{X}}% {{Y}}%;',
            ],
        ];
        
        $controls['perspective_hr'] = [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ];
        /*$controls['perspective_heading'] = [
                'label' => __( 'Perspective', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ];*/
        $controls['perspective'] = [
            'label' => _x( 'Perspective', 'Perspective Control', 'dynamic-content-for-elementor' ),
            'type' => Controls_Manager::SLIDER,
            'responsive' => true,
            //'render_type' => 'template',
            'render_type' => 'ui',
            'default' => [
                'size' => '',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                  'max' => 1200,
                  'min' => 0,
                  'step' => 1,
               ],
            ],
            
            'selectors' => [
                '{{SELECTOR}}' => 'perspective: {{SIZE}}{{UNIT}}; -webkit-perspective: {{SIZE}}{{UNIT}};',
            ],
        ];
        $controls['perspective-origin'] = [
            'label' => _x( 'Perspective origin', 'Perspective-origin x/y Control', 'elementor' ),
            'type' => 'xy_positions',
            'responsive' => true,
            'condition' => [
                'transform_type' => 'custom',
            ],
            'selectors' => [
                '{{SELECTOR}} > *:first-child' => 'perspective-origin: {{X}}% {{Y}}%; -webkit-perspective-origin:: {{X}}% {{Y}}%;',
            ],
        ];
        
        return $controls;
    }
    
    protected function prepare_fields( $fields ) {
        //var_dump($fields);
        //echo 'pppp-';
        array_walk( $fields, function ( &$field, $field_name ) {
            if ( in_array( $field_name, [ 'transform_element', 'popover_toggle' ] ) ) {
                return;
            }
            /*
            //echo $field_name.'<br>';
            if($field_name == 'transform'){
                //echo '------> {{VALUE}}';
                $selector_value = 'transform: ';
               
                $valore_angle = '';
                $valore_rotatex = '';
                $valore_rotatey = '';
                $valore_scale = '';
                $valore_translatex = '';
                $valore_translatey = '';
                $valore_translatez = '';


                $dato_angle = '{{ANGLE}}';
                $dato_rotatex = '{{ROTATE_X}}';
                $dato_rotatey = '{{ROTATE_Y}}';
                $dato_scale = '{{SCALE}}';
                $dato_translatex = '{{TRANSLATE_X}}';
                $dato_translatey = '{{TRANSLATE_Y}}';
                $dato_translatez = '{{TRANSLATE_Z}}';
                if($dato_angle != '') $valore_angle = ' rotateZ({{ANGLE}}deg)';
                //echo 'v: '.var_export($dato_angle);
                //echo '{{VALUE.SCALE}}';
                $field['selectors'] = [
                    '{{SELECTOR}} > *:first-child' => 'transform:'.$valore_angle.' rotateX({{ROTATE_X}}deg) rotateY({{ROTATE_Y}}deg) scale({{SCALE}}) translateX({{TRANSLATE_X}}px) translateY({{TRANSLATE_Y}}px) translateZ({{TRANSLATE_Z}}px);',
                ];
                
            }
            
            if(isset($field['selector_value'])){
                
                $field['selectors'] = [
                    '{{SELECTOR}} > *:first-child' => $selector_value.';',
                ];
            }*/
            /*$field['condition'] = [
                'transform_element' => 'custom',
            ];*/
        } );

        return parent::prepare_fields( $fields );
    }

    protected function get_default_options() {
        return [
            'popover' => false,
            /*'popover' => [
                'starter_title' => _x( 'Transform', 'Transform Control', 'dynamic-content-for-elementor' ),
                'starter_name' => 'transform_element',
            ],*/
        ];
    }
}