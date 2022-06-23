<?php

namespace Aepro;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use WC_Booking_Cart_Manager;

class Aepro_Woo_Add_To_Cart extends Widget_Base{
    public function get_name() {
        return 'ae-woo-add-to-cart';
    }

    public function get_title() {
        return __( 'AE - Woo Add To Cart', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-woocommerce';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    public function _register_controls()
    {
        $this->start_controls_section(
            'section_title',
            [
                'label' => __( 'General', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'show_qty_box',
            [
                'label' => __('Show Quantity Box','ae-pro'),
                'type'  => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __( 'Show', 'ae-pro' ),
                'label_off' => __( 'Hide', 'ae-pro' ),
                'return_value' => 'yes',

            ]
        );
        $this->add_control(
            'layout_mode',
            [
                'label' => __( 'Layout', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'horizontal' => [
                        'title' => __( 'Horizontal', 'ae-pro' ),
                        'icon' => 'fa fa-arrows-h',
                    ],
                    'vertical' => [
                        'title' => __( 'Vertical', 'ae-pro' ),
                        'icon' => 'fa fa-arrows-v',
                    ]
                ],
                'default' => 'horizontal',
                'condition' => [
                    'show_qty_box' => 'yes'
                ],
                'prefix_class' => 'ae-element-woo-layout-'
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Alignment', 'ae-pro' ),
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
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_qty_style',
            [
                'label' => __( 'Quantity Box', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_qty_box' => 'yes'
                ]
            ]

        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography_qty',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .ae-element-woo-add-to-cart .quantity .qty',
            ]
        );
        $this->add_control(
            'qty_height',
            [
                'label' => __( 'Quantity Box Height', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,

                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-add-to-cart .quantity .qty' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'qty_width',
            [
                'label' => __( 'Quantity Box Width', 'ae-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%'],
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 5,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-add-to-cart .quantity .qty' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'qty_padding',
            [
                'label' => __( 'Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-add-to-cart .quantity input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'qty_margin',
            [
                'label' => __( 'Margin', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-add-to-cart .quantity input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button_style',
            [
                'label' => __( 'Add To Cart Button', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]

        );

        $this->start_controls_tabs('tabs1');

        $this->start_controls_tab('woo_normal',['label' => 'Normal']);

        $this->load_woo_normal_settings();

        $this->end_controls_tab();

        $this->start_controls_tab('woo_hover',['label' => 'Hover']);

        $this->load_woo_hover_settings();

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'button_margin',
            [
                'label' => __( 'Margin', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-add-to-cart-wrapper .ae-element-woo-add-to-cart-btn, {{WRAPPER}} .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_padding',
            [
                'label' => __( 'Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-add-to-cart-wrapper .ae-element-woo-add-to-cart-btn, {{WRAPPER}} .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(){
        $settings = $this->get_settings();
        $helper = new Helper();
        global $product;
        $product = $helper->get_ae_woo_product_data();
        if(!$product){
            return '';
        }

		$this->add_render_attribute( 'woo-add-to-cart-wrapper', 'class', 'ae-element-woo-add-to-cart-wrapper' );
		$this->add_render_attribute( 'woo-add-to-cart-wrapper', 'class', 'ae-element-woo-add-to-cart' );
		$this->add_render_attribute( 'woo-add-to-cart-wrapper', 'class', 'ae-element-woo-'.$product->get_type().'-add-to-cart' );
		?>
		<div <?php echo $this->get_render_attribute_string('woo-add-to-cart-wrapper');?>>
		<?php
            if($settings['show_qty_box'] == 'yes'){
                $this->ae_woo_add_to_cart($product);
            }else{
                $this->ae_woo_loop_add_to_cart($product);
            }
			?>
		</div>
		<?php
    }

   protected function ae_woo_loop_add_to_cart($product){

       $product_type = $product->get_type();

       $class = implode( ' ', array_filter( [
           'product_type_' . $product_type,
           $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
           $product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
       ] ) );


       echo apply_filters( 'woocommerce_loop_add_to_cart_link',
           sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
               esc_url( $product->add_to_cart_url() ),
               esc_attr( isset( $quantity ) ? $quantity : 1 ),
               esc_attr( $product->get_id() ),
               esc_attr( $product->get_sku() ),
               esc_attr( isset( $class ) ? $class.' button ae-element-woo-add-to-cart-bt' : 'button ae-element-woo-add-to-cart-btn' ),
               esc_html( $product->add_to_cart_text() )
           ),
           $product );
   }

    protected function ae_woo_add_to_cart($product){

        if($product->get_type() == 'simple'){
            $func = 'ae_woo_'.$product->get_type().'_add_to_cart';
            $this->$func($product);

        }else{
            do_action('woocommerce_'.$product->get_type().'_add_to_cart');
        }

    }

   protected function ae_woo_simple_add_to_cart($product){
       $settings = $this->get_settings();

       $this->add_render_attribute( 'woo-add-to-cart-class', 'class', 'cart' );
       $this->add_render_attribute( 'woo-add-to-cart-class', 'class','ae-element-woo-layout-'.$settings['layout_mode']);
       $this->add_render_attribute( 'woo-add-to-cart-class', 'method', 'post' );
       $this->add_render_attribute( 'woo-add-to-cart-class', 'enctype', 'multipart/form-data' );
       $this->add_render_attribute( 'woo-add-to-cart-btn-class', 'class', 'ae-element-woo-add-to-cart-btn' );
       $this->add_render_attribute( 'woo-add-to-cart-btn-class', 'class', 'single_add_to_cart_button' );
       $this->add_render_attribute( 'woo-add-to-cart-btn-class', 'class', 'button alt ajax_add_to_cart' );


       if ( $product->is_in_stock() ){
           ?>
           <?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

           <form <?php echo $this->get_render_attribute_string('woo-add-to-cart-class');?>>
               <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
               <?php
               do_action( 'woocommerce_before_add_to_cart_quantity' );
               if ( ! $product->is_sold_individually() ) {
                   woocommerce_quantity_input( array(
                       'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
                       'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ),
                       'input_value' => ( isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 )
                   ) );
               }
               do_action( 'woocommerce_after_add_to_cart_quantity' );
               ?>
               <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" />

               <button type="submit"<?php echo $this->get_render_attribute_string('woo-add-to-cart-btn-class');?> name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

               <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
           </form>

           <?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
       <?php }
   }

   public function ae_woo_variable_add_to_cart($product){
       woocommerce_variable_add_to_cart();
   }



    protected function load_woo_normal_settings(){
        $this->add_control(
            'button_color',
            [
                'label' => __( 'Text Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-add-to-cart-wrapper .ae-element-woo-add-to-cart-btn, {{WRAPPER}} .button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .ae-element-woo-add-to-cart-wrapper .ae-element-woo-add-to-cart-btn, {{WRAPPER}} .button',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-element-woo-add-to-cart-wrapper .ae-element-woo-add-to-cart-btn, {{WRAPPER}} .button',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-add-to-cart-wrapper .ae-element-woo-add-to-cart-btn, {{WRAPPER}} .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_bgcolor',
            [
                'label' => __( 'Background Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-add-to-cart-wrapper .ae-element-woo-add-to-cart-btn, {{WRAPPER}} .button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'label' => __( 'Box Shadow', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-element-woo-add-to-cart-wrapper .ae-element-woo-add-to-cart-btn, {{WRAPPER}} .button',
            ]
        );

    }
    protected function load_woo_hover_settings(){
        $this->add_control(
            'text_hover_color',
            [
                'label' => __( 'Text Hover Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-add-to-cart-wrapper .ae-element-woo-add-to-cart-btn:hover, {{WRAPPER}} .button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'hover_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .ae-element-woo-add-to-cart-wrapper .ae-element-woo-add-to-cart-btn:hover, {{WRAPPER}} .button:hover',
            ]
        );


        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_hover_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-element-woo-add-to-cart-wrapper .ae-element-woo-add-to-cart-btn:hover',
            ]
        );

        $this->add_control(
            'button_hover_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-add-to-cart-wrapper .ae-element-woo-add-to-cart-btn:hover, {{WRAPPER}} .button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Background Hover Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-element-woo-add-to-cart-wrapper .ae-element-woo-add-to-cart-btn:hover, {{WRAPPER}} .button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
    }

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Woo_Add_To_Cart() );