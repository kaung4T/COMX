<?php
namespace Aepro\Ae_FACETWP\Skins;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Aepro\Helper;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_fSelect extends Skin_Base
{

    public function get_id()
    {
        return 'fselect';
    }

    public function get_title()
    {
        return __('fSelect', 'ae-pro');
    }

    public function register_controls( Widget_Base $widget )
    {
        $this->parent = $widget;
        $this->facet();
    }
    public function register_style_controls( Widget_Base $widget )
    {
        $this->parent = $widget;
    }
    public function facet(){
        $helper = new Helper();
        $facet = $helper->get_facetwp_data('fselect');
        $this->add_control(
            'facet_name',
            [
                'label' =>  __('Facet' , 'ae-pro'),
                'type'  =>  Controls_Manager::SELECT,
                'options'   =>  $facet,
            ]
        );
    }
    public function render(){
        $facet_name = $this->get_instance_value('facet_name');
        if(!empty($facet_name)){
            echo facetwp_display( 'facet', $facet_name);
        }
    }

}