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
class DCE_Widget_Woo_Cart extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-woocommerce-cart';
    }
    
    static public function is_enabled() {
        return false;
    }
    
    public function get_title() {
        return __('View Cart', 'dynamic-content-for-elementor');
    }
    
    public function get_icon() {
        return 'icon-dyn-woo_cart todo';
    }
    
    static public function get_position() {
        return 9;
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
        $demoPage = get_post_meta(get_the_ID(), 'demo_id', true);
        //
        $id_page = ''; //get_the_ID();
        $type_page = '';

        global $global_ID;
        global $global_TYPE;
        global $in_the_loop;
        global $global_is;
        //
        global $product;
        //
        if(!empty($demoPage)){
        //echo 'DEMO';
          $id_page = $demoPage;
          $type_page = get_post_type($demoPage);
          $product = wc_get_product( $demoPage );
          //echo 'DEMO ...';
        } 
        else if (!empty($global_ID)) {
          //echo 'GLOBAL';
          $id_page = $global_ID;
          $type_page = get_post_type($id_page);
          //echo 'global ... '.$global_ID;
          //var_dump($product);
          // se non esiste $product
          if(!isset($product) || !$product){
            $product = wc_get_product( $global_ID );
          }
        }else {
          //echo 'Select DEMO product for show the value.';
          $id_page = get_the_id();
          $type_page = get_post_type();
          //echo 'natural ...';
        }

        if ( empty( $product ) )
           return;
        // ------------------------------------------
        
        //var_dump($product);
        
        echo 'CART';

        
    }

    protected function _content_template() {
        
    }

}
