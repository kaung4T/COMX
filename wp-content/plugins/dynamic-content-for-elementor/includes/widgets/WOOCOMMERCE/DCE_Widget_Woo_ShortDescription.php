<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Utils;
use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\Group_Control_Outline;
use DynamicContentForElementor\Controls\DCE_Group_Control_Filters_CSS;
use DynamicContentForElementor\Controls\DCE_Group_Control_Transform_Element;
//use DynamicContentForElementor\Group_Control_AnimationElement;

if (!defined('ABSPATH')) {
    exit;
}
class DCE_Widget_Woo_Shortdescription extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-woocommerce-shortdescription';
    }
    
    static public function is_enabled() {
        return false;
    }
    
    public function get_title() {
        return __('Short Description', 'dynamic-content-for-elementor');
    }
    
    public function get_icon() {
        return 'icon-dyn-woo_shortdesc todo';
    }
    
    static public function get_position() {
        return 3;
    }
    public function get_plugin_depends() {
        return array('woocommerce' => 'woocommerce');
    }
    protected function _register_controls() {
        $this->start_controls_section(
            'section_content', [
                'label' => __('Settings', 'dynamic-content-for-elementor'),
            ]
        );
        
        $this->end_controls_section();

        
    }
    
    protected function render() {
        $settings = $this->get_active_settings();
        if ( empty( $settings ) )
           return;
        //
        // ------------------------------------------
        $dce_data = DCE_Helper::dce_dynamic_data();
        // ------------------------------------------  
        global $product;
        if ( empty( $product ) ) {
            return;
        }      
        wc_get_template( 'single-product/short-description.php' );
    }

    protected function _content_template() {
        
    }

}
