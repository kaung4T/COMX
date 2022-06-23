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
use DynamicContentForElementor\Controls\DCE_Group_Control_Transform_Element;
//use DynamicContentForElementor\Group_Control_AnimationElement;

if (!defined('ABSPATH')) {
    exit;
}
class DCE_Widget_Woo_SaleFlash extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-woocommerce-saleflash';
    }
    
    static public function is_enabled() {
        return false;
    }
    
    public function get_title() {
        return __('Sale-Flash', 'dynamic-content-for-elementor');
    }
    
    public function get_icon() {
        return 'icon-dyn-woo_salesflash todo';
    }
    
    static public function get_position() {
        return 4;
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
        wc_get_template( '/single-product/sale-flash.php' );
        //$this->crea_woocsalefash($product);
        
    }

    protected function _content_template() {
        
    }
    protected function crea_woocsalefash($product) {
        global $post, $product;

        ?>
        <?php if ( $product->is_on_sale() ) : ?>

            <?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woocommerce' ) . '</span>', $post, $product ); ?>

        <?php endif;
    }
}
