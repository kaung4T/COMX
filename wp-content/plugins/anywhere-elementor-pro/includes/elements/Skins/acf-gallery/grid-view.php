<?php
namespace Aepro\ACF_Gallery\Skins;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Grid extends Skin_Base {

    public function get_id() {
        return 'grid';
    }

    public function get_title() {
        return __( 'Grid', 'ae-pro' );
    }

    public function register_controls( Widget_Base $widget ) {
    $this->parent = $widget;
    parent::field_control();
    parent::grid_view();
}

    public function render()
    {
    // TODO: Implement render() method.
        //echo '<pre>'; print_r($this->parent->get_settings()); echo '</pre>';
        parent::grid_html();
    }

    public function register_style_controls(){
        parent:: grid_style_control();
    }

    public function register_overlay_controls(){
        parent::grid_overlay_controls();
    }
    public function register_overlay_style_controls(){
        parent::grid_overlay_style_control();

    }
}
