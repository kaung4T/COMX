<?php
namespace Aepro\ACF_Gallery\Skins;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Slider extends Skin_Base {

    public function get_id() {
        return 'slider';
    }

    public function get_title() {
        return __( 'Slider', 'ae-pro' );
    }



    public function register_controls( Widget_Base $widget ) {
        $this->parent = $widget;
        parent::field_control();
        parent::common_controls();
        parent::pagination_controls();

    }

    public function render()
    {
        // TODO: Implement render() method.
        //echo 'In Slider Skin';
        parent::swiper_html();

       //echo '<pre>'; print_r($this->parent->get_settings()); echo '</pre>';

    }

    public function register_style_controls(){
        parent::common_style_control();

    }

    public function register_overlay_controls(){

    }

    public function register_overlay_style_controls(){

    }

}
