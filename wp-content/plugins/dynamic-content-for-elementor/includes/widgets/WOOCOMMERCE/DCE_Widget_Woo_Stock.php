<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use DynamicContentForElementor\DCE_Helper;
//use DynamicContentForElementor\Group_Control_AnimationElement;

if (!defined('ABSPATH')) {
    exit;
}
class DCE_Widget_Woo_Stock extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-woocommerce-stock';
    }
    
    static public function is_enabled() {
        return false;
    }
    
    public function get_title() {
        return __('Stock', 'dynamic-content-for-elementor');
    }
    
    public function get_icon() {
        return 'icon-dyn-woo_stock todo';
    }
    
    static public function get_position() {
        return 8;
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
        $availability = $product->get_availability();
        //var_dump($availability);
        ?>
        <p class="stock <?php echo esc_attr( $availability['class'] ); ?>"><?php echo wp_kses_post( $availability['availability'] ); ?></p>
        <?php
        //wc_get_stock_html($product);
    }

    protected function _content_template() {
        
    }

}
