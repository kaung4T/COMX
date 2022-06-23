<?php
namespace Aepro;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Aepro\ACF_Gallery\Skins;
use Elementor\Controls_Manager;

class Aepro_ACF_Gallery extends Widget_Base{

    protected $_has_template_content = false;
    
    public function get_name() {
        return 'ae-acf-gallery';
    }

    public function get_title() {
        return __( 'AE - ACF Gallery', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    protected function _register_skins() {
        $this->add_skin( new Skins\Skin_Grid ($this));
        $this->add_skin( new Skins\Skin_Carousel( $this ) );
        //$this->add_skin( new Skins\Skin_Slider( $this ) );

    }

    public function get_script_depends() {

        return [ 'jquery-masonry', 'ae-swiper' ];

    }

    public function get_custom_help_url() {
        $helper = new Helper();
        return $helper->get_help_url_prefix() . $this->get_name();
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_layout',
            [
                'label' => __( 'Layout', 'ae-pro' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_overlay',
            [
                'label' => __( 'Overlay', 'ae-pro' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
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

        $this->start_controls_section(
            'section_overlay_style',
            [
                'label' => __( 'Overlay Setting', 'ae-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();

    }


}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_ACF_Gallery() );