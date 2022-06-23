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
class DCE_Widget_Woo_AdditionalInformation extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-woocommerce-additionalInformation';
    }
    
    static public function is_enabled() {
        return false;
    }
    
    public function get_title() {
        return __('Additional Information', 'dynamic-content-for-elementor');
    }
    
    public function get_icon() {
        return 'icon-dyn-woo_addinfo';
    }
    
    static public function get_position() {
        return 11;
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
        $this->add_responsive_control(
            'addinfo_align', [
                'label' => __('Alignment', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-right',
                    ]
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
                
            //'prefix_class' => 'acfposts-align-'
            ]
        );
        $this->add_control(
            'addinfo_space',
            [
                'label' => __( 'Space', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                        'size' => 0,
                ],
                'range' => [
                        'px' => [
                                'min' => 0,
                                'max' => 100,
                        ],
                ],
                'selectors' => [
                        '{{WRAPPER}}' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
                ],
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
        //wc_get_template( 'single-product/tabs/additional-information.php' );
        $this->crea_wooaddinfo($product);
    }

    protected function _content_template() {
        
    }
    protected function crea_wooaddinfo($product) {
       $heading = esc_html( apply_filters( 'woocommerce_product_additional_information_heading', __( 'Additional information', 'woocommerce' ) ) ); ?>

        <?php if ( $heading ) : ?>
            <!-- <h2><?php echo $heading; ?></h2> -->
        <?php endif; ?>
        <?php 
        wc_get_template( 'single-product/product-attributes.php', array(
            'product'            => $product,
            'attributes'         => array_filter( $product->get_attributes(), 'wc_attributes_array_filter_visible' ),
            'display_dimensions' => false, //apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() ),
        ) );
        //do_action( 'woocommerce_product_additional_information', $product );
    }
}
