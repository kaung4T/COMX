<?php
namespace Aepro\Ae_FACETWP\Skins;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Aepro\Helper;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Radio extends Skin_Base
{

    public function get_id()
    {
        return 'radio';
    }

    public function get_title()
    {
        return __('Radio', 'ae-pro');
    }

    public function register_controls( Widget_Base $widget )
    {
        $this->parent = $widget;
        $this->facet();
    }
    public function register_style_controls( Widget_Base $widget )
    {
        $this->parent = $widget;
        parent::check_radio_style_control();
    }
    public function facet(){
        $helper = new Helper();
        $facet = $helper->get_facetwp_data('radio');
        $this->add_control(
            'facet_name',
            [
                'label' =>  __('Facet' , 'ae-pro'),
                'type'  =>  Controls_Manager::SELECT,
                'options'   =>  $facet,
            ]
        );
    }

    public function radio_output($output , $params){
        $facet = $params['facet'];

        $output = '';
        $values = (array) $params['values'];
        $selected_values = (array) $params['selected_values'];

        $key = 0;
        foreach ( $values as $key => $result ) {
            $selected = in_array( $result['facet_value'], $selected_values ) ? ' checked' : '';
            $selected .= ( 0 == $result['counter'] && '' == $selected ) ? ' disabled' : '';
            $output .= '<div class="facetwp-radio' . $selected . '" data-value="' . esc_attr( $result['facet_value'] ) . '">';
            $src = (!empty($selected) && $selected != 'disabled') ? AE_PRO_URL.'includes/assets/images/radio-on.png' :  AE_PRO_URL.'includes/assets/images/radio.png';
            $output .= '<img src="'. $src . '">';
            $output .= esc_html( $result['facet_display_value'] ) . ' <span class="facetwp-counter">(' . $result['counter'] . ')</span>';
            $output .= '</div>';
        }
        return $output;
    }

    public function render(){
        add_filter( 'facetwp_facet_html',[$this , 'radio_output'], 10, 2 );
        $facet_name = $this->get_instance_value('facet_name');
        if(!empty($facet_name)){
            echo facetwp_display( 'facet', $facet_name);
        }
        //remove_filter('facetwp_facet_html' , [$this , 'radio_output'] , '10' );
    }

}