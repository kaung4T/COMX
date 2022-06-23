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
class DCE_Widget_Woo_Tabs extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-woocommerce-tabs';
    }
    
    static public function is_enabled() {
        return false;
    }
    
    public function get_title() {
        return __('Tabs', 'dynamic-content-for-elementor');
    }
    
    public function get_icon() {
        return 'icon-dyn-woo_tabs';
    }
    
    static public function get_position() {
        return 5;
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

       
        setup_postdata( $dce_data['id'] );
        
        wc_get_template( 'single-product/tabs/tabs.php' );
        //$this->crea_wooc($product);
        

        // On render widget from Editor - trigger the init manually.
        if ( Utils::is_ajax() ) {
            ?>
            <script>
                jQuery( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' );
            </script>
            <?php
        }
       
        
    }

    protected function _content_template() {
        
    }
    protected function crea_wooc($product) {
       
        $tabs = apply_filters( 'woocommerce_product_tabs', array() );

        if ( ! empty( $tabs ) ) : ?>

            <div class="woocommerce-tabs wc-tabs-wrapper">
                <ul class="tabs wc-tabs" role="tablist">
                    <?php foreach ( $tabs as $key => $tab ) : ?>
                        <li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
                            <a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php foreach ( $tabs as $key => $tab ) : ?>
                    <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
                        <?php if ( isset( $tab['callback'] ) ) { call_user_func( $tab['callback'], $key, $tab ); } ?>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif;
    }
}