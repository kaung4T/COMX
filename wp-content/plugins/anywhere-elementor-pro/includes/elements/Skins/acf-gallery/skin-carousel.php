<?php
namespace Aepro\ACF_Gallery\Skins;



use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Carousel extends Skin_Base {

    public function get_id() {
        return 'carousel';
    }

    public function get_title() {
        return __( 'Carousel', 'ae-pro' );

    }

    protected function _register_controls_actions() {
        parent::_register_controls_actions();

        add_action( 'elementor/element/ae-acf-gallery/section_layout/before_section_end', [ $this, 'register_layout_controls' ] );
    }

    public function register_controls( Widget_Base $widget ) {
        $this->parent = $widget;

        parent::field_control();
        parent::image_carousel_control();
        parent::common_controls();
        parent::pagination_controls();

    }

    public function render()
    {
        // TODO: Implement render() method.
       // echo 'In Carousel Skin';

        //parent::get_gallery_data();
        parent::swiper_html();
    }

    public function register_layout_controls(){
       $this->update_control(
           'effect',
           [
               'options' => [
                   'slide' => __('Slide', 'ae-pro'),
                   'coverflow' => __('Coverflow', 'ae-pro')
               ]
           ]
       );
    }

    public function register_style_controls(){

        parent::common_style_control();

    }

    public function register_overlay_controls(){

    }

    public function register_overlay_style_controls(){

    }



}
