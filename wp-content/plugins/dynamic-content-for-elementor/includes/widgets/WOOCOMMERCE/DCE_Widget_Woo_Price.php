<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;

use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\Controls\DCE_Group_Control_Filters_CSS;
//use DynamicContentForElementor\Group_Control_AnimationElement;

if (!defined('ABSPATH')) {
    exit;
}
class DCE_Widget_Woo_Price extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-woocommerce-price';
    }
    
    static public function is_enabled() {
        return false;
    }
    
    public function get_title() {
        return __('Price', 'dynamic-content-for-elementor');
    }
    
    public function get_icon() {
        return 'icon-dyn-woo_price';
    }
    
    static public function get_position() {
        return 3;
    }
    public function get_plugin_depends() {
        return array('woocommerce' => 'woocommerce');
    }
    protected function _register_controls() {
        $this->start_controls_section(
            'section_settings', [
                'label' => __('Settings', 'dynamic-content-for-elementor'),
            ]
        );
        $this->add_responsive_control(
            'align', [
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
            'block_enable', [
                'label' => __('Block', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'block',
                'selectors' => [
                    '{{WRAPPER}} .dce-price del' => 'display: {{VALUE}};',
                ], 
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_price', [
                'label' => __('Text Price', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color', [
                'label' => __('Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .price .amount' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .price',
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .price',
            ]
        );
        
        $this->add_control(
            'blend_mode',
            [
                'label' => __( 'Blend Mode', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Normal', 'elementor' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .price' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_sale', [
                'label' => __('Sale price', 'dynamic-content-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'sale_color', [
                'label' => __('Text Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .price del .amount' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'sale_bar_color', [
                'label' => __('Bar Color', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .price del' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'sale_typography',
                'selector' => '{{WRAPPER}} .price del',
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'sale_text_shadow',
                'selector' => '{{WRAPPER}} .price del',
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
        //var_dump($product); ?>
        <div class="dce-price">
            <?php wc_get_template( '/single-product/price.php' );
            /*if ( $product->get_price() ) {
              echo '<p class="price">HTML Price '.$product->get_price_html().'</p>';
              //echo '<p class="price">Price '.$product->get_regular_price() . $product->get_price_suffix().'</p>';
              //echo '<p class="price sale">Sale '.$product->get_sale_price() . $product->get_price_suffix().'</p>';
            }*/
            ?>
        </div>
        <?php
    }

    protected function _content_template() {
        
    }

}
