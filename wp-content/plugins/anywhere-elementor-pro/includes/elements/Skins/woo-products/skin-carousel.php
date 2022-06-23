<?php
namespace Aepro\Woo_Products\Skins;

use Elementor\Widget_Base;

class Skin_Carousel extends Skin_Base{

    public function get_id() {
        return 'carousel';
    }

    public function get_title() {
        return __( 'Carousel', 'ae-pro' );
    }

    protected function _register_controls_actions() {
        parent::_register_controls_actions();
    }

    public function register_controls( Widget_Base $widget ) {
        $this->parent = $widget;

        parent::product_query_controls();
        parent::product_carousel_control();
        parent::common_controls();

        parent::pagination_controls();
    }
    public function register_style_controls(){
        parent::common_style_control();
    }
    public function render(){
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
}