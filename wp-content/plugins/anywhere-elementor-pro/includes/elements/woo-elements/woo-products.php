<?php

namespace Aepro;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Aepro\Woo_Products\Skins;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Ae_Woo_Products extends Widget_Base{

    protected $_has_template_content = false;

    public function get_name() {
        return 'ae-woo-products';
    }

    public function get_title() {
        return __( 'AE - Woo Products', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-woocommerce';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    public function get_script_depends() {
        return [ 'jquery-masonry' ];
    }

    protected function _register_skins() {
        //$this->add_skin( new Skins\Skin_Carousel( $this ) );
        $this->add_skin( new Skins\Skin_Grid( $this ) );
        //$this->add_skin(new Skins\Skin_Slider($this));
    }



    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_layout',
            [
                'label' => __( 'Layout', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Layout','ae-pro'),
                'tab'   => Controls_Manager::TAB_STYLE
            ]
        );

        $this->end_controls_section();
    }


}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Ae_Woo_Products() );