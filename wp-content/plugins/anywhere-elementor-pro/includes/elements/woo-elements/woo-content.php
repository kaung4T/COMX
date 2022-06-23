<?php

namespace Aepro;

use Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;

class Aepro_Woo_Content extends Widget_Base{
    public function get_name() {
        return 'ae-woo-description';
    }

    public function get_title() {
        return __( 'AE - Woo Description', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-woocommerce';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_layout_settings',
            [
                'label' => __( 'Layout Settings', 'ae-pro' )
            ]
        );

        $this->add_control(
            'description_type',
            [
                'label' => __( 'Source', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'short' => __( 'Short Description', 'ae-pro' ),
                    'full' => __( 'Full Description', 'ae-pro' ),
                ],
                'default' => 'full'
            ]
        );

        $this->add_control(
            'description_size',
            [
                'label' => __( 'Description Size', 'ae-pro' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '10',
                'condition' => [
                    'description_type' => 'short',
                ]

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_general_style',
            [
                'label' => __( 'Description', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'color',
            [
                'label' => __( 'Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Align', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'ae-pro' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'ae-pro' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'ae-pro' ),
                        'icon' => 'fa fa-align-right',
                    ]
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => __( 'Description Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .ae-element-woo-content',
            ]
        );

        $this->end_controls_section();
    }

    protected function render( ) {
        $settings = $this->get_settings();
        $helper = new Helper();
        $product = $helper->get_ae_woo_product_data();
        if(!$product){
            return '';
        }

        $this->add_render_attribute( 'woo-content-class', 'class', 'ae-element-woo-content' );
        ?>
        <?php if($settings['description_type'] == 'short'): ?>
            <div <?php echo $this->get_render_attribute_string('woo-content-class');?>>
            <?php
                $product_short_description =  wpautop($product->get_short_description() );
                if($product_short_description != ''){
                    if($settings['description_size'] > 0){
                        echo wp_trim_words( $product_short_description, $settings['description_size'], '...' );
                    }else {
	                    $product_short_description = wpautop($product_short_description);
	                    if(isset($GLOBALS['wp_embed'])){
		                    $product_short_description = $GLOBALS['wp_embed']->autoembed($product_short_description);
	                    }
	                    echo do_shortcode($product_short_description);

                    }

                }
            ?>
            </div>
        <?php else:

            $edit_mode = get_post_meta($product->get_id(),'_elementor_edit_mode','');
            if(isset($edit_mode[0]) && $edit_mode[0] == 'builder'){
                $product_description = '<div class="ae_data elementor elementor-<?php echo $product_id; ?>">';
                $product_description .=  Elementor\Plugin::instance()->frontend->get_builder_content( $product->get_id() );
                $product_description .= '</div>';
            }else{
                $product_description = wpautop($product->get_description());
	            if(isset($GLOBALS['wp_embed'])){
		            $product_description = $GLOBALS['wp_embed']->autoembed($product_description);
	            }
            }
        ?>
            <div <?php echo $this->get_render_attribute_string('woo-content-class');?>>
                <?php echo do_shortcode($product_description); ?>
            </div>
        <?php endif; ?>
        <?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Woo_Content() );