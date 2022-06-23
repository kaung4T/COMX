<?php
namespace Aepro\Ae_FACETWP\Skins;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Aepro\Helper;
use FacetWP_Helper;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Checkbox extends Skin_Base
{

    public function get_id()
    {
        return 'checkbox';
    }

    public function get_title()
    {
        return __('Checkbox', 'ae-pro');
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
        $facet = $helper->get_facetwp_data('checkboxes');
        $this->add_control(
            'facet_name',
            [
                'label' =>  __('Facet' , 'ae-pro'),
                'type'  =>  Controls_Manager::SELECT,
                'options'   =>  $facet,
            ]
        );
    }

    function checkbox_output( $output, $params )
    {
        $facet = $params['facet'];


        if (isset($facet['hierarchical']) && 'yes' == $facet['hierarchical']) {
           return $this->render_hierarchy($params);
       }
        $output = '';
        $values          = (array)$params['values'];
        $selected_values = (array)$params['selected_values'];
        $soft_limit      = empty($facet['soft_limit']) ? 0 : (int)$facet['soft_limit'];

        $key = 0;
        foreach ($values as $key => $result) {
            if (0 < $soft_limit && $key == $soft_limit) {
                $output .= '<div class="facetwp-overflow facetwp-hidden">';
            }
            $selected = in_array($result['facet_value'], $selected_values) ? ' checked' : '';
            $selected .= (0 == $result['counter'] && '' == $selected) ? ' disabled' : '';
            $output   .= '<div class="facetwp-checkbox' . $selected . '" data-value="' . esc_attr($result['facet_value']) . '">';
            $src      = !empty($selected) ? AE_PRO_URL . 'includes/assets/images/checkbox-on.png' : AE_PRO_URL . 'includes/assets/images/checkbox.png';
            $output   .= '<img src=' . $src . '>';
            $output   .= esc_html($result['facet_display_value']) . ' <span class="facetwp-counter">(' . $result['counter'] . ')</span>';
            $output   .= '</div>';
        }

        if (0 < $soft_limit && $soft_limit <= $key) {
            $output .= '</div>';
            $output .= '<a class="facetwp-toggle">' . __('See {num} more', 'fwp-front') . '</a>';
            $output .= '<a class="facetwp-toggle facetwp-hidden">' . __('See less', 'fwp-front') . '</a>';
        }

        return $output;
    }

    /**
     * Generate the facet HTML (hierarchical taxonomies)
     */
    function render_hierarchy( $params ) {
        $output = '';
        $facet = $params['facet'];
        $selected_values = (array) $params['selected_values'];
        $values = FWP()->helper->sort_taxonomy_values( $params['values'], $facet['orderby'] );
        $init_depth = -1;
        $last_depth = -1;

        foreach ( $values as $result ) {
            $depth = (int) $result['depth'];

            if ( -1 == $last_depth ) {
                $init_depth = $depth;
            }
            elseif ( $depth > $last_depth ) {
                $output .= '<div class="facetwp-depth">';
            }
            elseif ( $depth < $last_depth ) {
                for ( $i = $last_depth; $i > $depth; $i-- ) {
                    $output .= '</div>';
                }
            }

            $selected = in_array($result['facet_value'], $selected_values) ? ' checked' : '';
            $selected .= (0 == $result['counter'] && '' == $selected) ? ' disabled' : '';
            $output   .= '<div class="facetwp-checkbox' . $selected . '" data-value="' . esc_attr($result['facet_value']) . '">';
            $src      = !empty($selected) ? AE_PRO_URL . 'includes/assets/images/checkbox-on.png' : AE_PRO_URL . 'includes/assets/images/checkbox.png';
            $output   .= '<img src=' . $src . '>';
            $output   .= esc_html($result['facet_display_value']) . ' <span class="facetwp-counter">(' . $result['counter'] . ')</span>';
            $output   .= '</div>';

            $last_depth = $depth;
        }

        for ( $i = $last_depth; $i > $init_depth; $i-- ) {
            $output .= '</div>';
        }

        return $output;
    }

    public function render( )
    {
        add_filter( 'facetwp_facet_html',[$this , 'checkbox_output'], 10, 2 );
        $facet_name = $this->get_instance_value('facet_name');
        if(!empty($facet_name)){
            echo facetwp_display( 'facet', $facet_name);
        }
        remove_filter('facetwp_facet_html' , [ $this, 'checkbox_output' ] , '10' );
    }

}