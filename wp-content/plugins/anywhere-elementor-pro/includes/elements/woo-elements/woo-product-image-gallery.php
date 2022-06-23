<?php

namespace Aepro;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;


class Aepro_Woo_Product_Image_Gallery extends Widget_Base{
    public function get_name() {
        return 'ae-woo-gallery';
    }

    public function get_title() {
        return __( 'AE - Woo Gallery', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-woocommerce';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    public function get_script_depends() {
        return [ 'ae-swiper' ];
    }

    public function is_reload_preview_required() {
        return false;
    }

    public function _register_controls(){

        $this->start_controls_section(
            'gallery_style',
            [
                'label' => __('Gallery Style','ae-pro')
            ]
        );


        $this->add_control(
            'gallery_type',
            [
                'label' => __('Gallery type','ae-pro'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'stock' =>   __( 'Stock', 'ae-pro' ),
                    'swipe'  =>   __( 'Swipe', 'ae-pro' ),
                    'swap' =>   __( 'Swap', 'ae-pro' ),
                ],
                'default' => 'stock',

            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image',
                'exclude' => [ 'custom' ],
                'condition' => [
                    'gallery_type!' => 'stock'
                ]

            ]
        );

        $this->add_control(
            'open_lightbox',
            [
                'label' => __('Lightbox', 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' =>
                    [
                        'default' => __( 'Default' , 'ae-pro'),
                        'yes' =>__( 'Yes' , 'ae-pro'),
                        'no' =>__('No' , 'ae-pro'),
                    ],
                'default'=>'no',
                'condition' => [
                    'gallery_type' => 'swipe'
                ]
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __('Loop', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'label_off',
                'label_on' => __('Yes', 'ae-pro'),
                'label_off' => __('No', 'ae-pro'),
                'return_value' => 'yes',
                'condition' => [
                    'gallery_type' => 'swipe'
                ]
            ]
        );

        $this->add_control(
            'gallery_item_spacing',
            [
                'label' => __('Gallery Item Spacing','ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .flex-control-thumbs li' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'gallery_type' => 'stock'
                ]
            ]
        );

        /*$this->add_responsive_control(
            'image_align',
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .woo-entry-image-swap' => 'text-align: {{VALUE}};',
                ]
            ]
        );*/

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'gallery_item_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .flex-control-thumbs li img, {{WRAPPER}} .ae-woo-image-swap .ae-woo-image-main, {{WRAPPER}} .ae-woo-image-swap .ae-woo-image-secondary, {{WRAPPER}} .ae-swiper-slide-wrapper img',
            ]
        );

        $this->add_control(
            'gallery_item_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .flex-control-thumbs li img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-woo-image-swap .ae-woo-image-main' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-woo-image-swap .ae-woo-image-secondary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'thumb_box_padding',
            [
                'label'  => __('Thumbnail Wrapper Padding','ae-pro'),
                'type'   => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} ol.flex-control-nav.flex-control-thumbs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'gallery_type' => 'stock'
                ]

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'arrow_control',
            [
                'label' => __('Navigation','ae-pro'),
                'condition' =>
                    [
                        'gallery_type' => 'swipe'
                    ]
            ]
        );

        $this->add_control(
            'arrow_show_on',
            [
                'label' => __('Show Arrows', 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' =>
                    [
                        'on_hover' => __( 'On hover' , 'ae-pro'),
                        'always' =>__( 'Always' , 'ae-pro'),
                        'no' => __('No' , 'ae-pro')
                    ],
                'default'=>'on_hover',
                'condition' => [
                    'gallery_type' => 'swipe'
                ]
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __('Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev i' => 'color:{{VAlUE}};',
                    '{{WRAPPER}} .ae-swiper-button-next i' => 'color:{{VAlUE}};'
                ],
                'default' => '#444',
                'condition' =>
                    [
                        'gallery_type' => 'swipe'
                    ]
            ]
        );

        $this->add_control(
            'arrow_size',
            [
                'label' => __('Arrow Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 25
                    ],
                'range' =>
                    [
                        'min' => 20,
                        'max' => 100,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev i' => 'font-size:{{SIZE}}px;',
                    '{{WRAPPER}} .ae-swiper-button-next i' => 'font-size:{{SIZE}}px;',
                ],
                'condition' =>
                    [
                        'gallery_type' => 'swipe'
                    ]
            ]
        );

        $this->add_responsive_control(
            'horizontal_arrow_offset',
            [
                'label' => __('Horizontal Offset', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ae-swiper-button-next' => 'right: {{SIZE}}{{UNIT}}',

                ],
                'condition' => [
                    'gallery_type' => 'swipe'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'dots_control',
            [
                'label' => __('Dots','ae-pro'),
                'condition' =>
                    [
                        'gallery_type' => 'swipe'
                    ]
            ]
        );

        $this->add_control(
            'dots_size',
            [
                'label' => __('Dots Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 5
                    ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'width:{{SIZE}}px; height:{{SIZE}}px;',
                ],
                'condition' =>
                    [
                        'gallery_type' => 'swipe'
                    ]
            ]
        );

        $this->add_control(
            'dots_color',
            [
                'label' => __('Active Dot Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color:{{VAlUE}} !important;',
                ],
                'condition' =>
                    [
                        'gallery_type' => 'swipe'
                    ]
            ]
        );

        $this->add_control(
            'inactive_dots_color',
            [
                'label' => __('Inactive Dot Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        'gallery_type' => 'swipe'
                    ]
            ]
        );

        $this->end_controls_section();
    }

    public function render(){
        global $product;
        $settings = $this->get_settings();

        $helper = new Helper();
        $product = $helper->get_ae_woo_product_data();
        if(!$product){
            return '';
        }

        
        $temp_post = $GLOBALS['post'];
        $GLOBALS['post'] = get_post($product->get_id());
        $gallery_type = $settings['gallery_type'];
        $image_size = $settings['image_size'];

        switch($gallery_type){
            case 'swipe':   if($settings['open_lightbox'] != 'no') {
                                $this->add_render_attribute('link', [
                                    'data-elementor-open-lightbox' => $settings['open_lightbox'],
                                    'data-elementor-lightbox-slideshow' => $product->get_id(),
                                ]);
                            }
                            $loop = $settings['loop'];
                            $navigation_button = $settings['arrow_show_on'];
                            $this->add_render_attribute('outer-wrapper', 'class', 'ae-swiper-outer-wrapper');
                            $this->add_render_attribute('outer-wrapper', 'class', 'ae-arrow-show-' . $settings['arrow_show_on'] );

                            if ($loop == 'yes') {
                                $this->add_render_attribute('outer-wrapper', 'data-loop', $loop);
                            } else {
                                autoplayStopOnLast:
                                true;
                            }
                            if ($navigation_button == 'no') {
                                $this->add_render_attribute('outer-wrapper', 'data-navigation', $navigation_button);
                            }
                            $attachment = get_post_thumbnail_id();
                            $attachment_ids = array();
                            if(!empty($attachment)) {
                                $attachment_ids = [$attachment];
                            }
                            $attachment_ids = array_merge($attachment_ids, $product->get_gallery_image_ids());
                            ?>
                            <div <?php echo $this->get_render_attribute_string('outer-wrapper'); ?> >
                                <div class="ae-swiper-container swiper-container">
                                    <div class="ae-swiper-wrapper swiper-wrapper">
                                        <?php
                                        foreach ($attachment_ids as $attachment_id) {
                                            ?>
                                            <div class="ae-swiper-slide swiper-slide">
                                                <div class="ae-swiper-slide-wrapper swiper-slide-wrapper">
                                                    <?php if ($settings['open_lightbox'] != 'no') { ?>
                                                    <a <?php echo $this->get_render_attribute_string('link'); ?> href="<?php echo wp_get_attachment_url($attachment_id, 'full'); ?>">
                                                        <?php } ?>
                                                        <?php echo wp_get_attachment_image($attachment_id, $image_size); ?>
                                                        <?php if ($settings['open_lightbox'] != 'no') { ?>
                                                    </a>
                                                <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class = "ae-swiper-pagination swiper-pagination"></div>
                                    <?php if ($navigation_button != 'no') { ?>
                                    <div class = "ae-swiper-button-prev swiper-button-prev"><i class="fa fa-angle-left"></i></div>
                                    <div class = "ae-swiper-button-next swiper-button-next"><i class="fa fa-angle-right"></i></div>
                                    <?php } ?>
                                </div>
                            </div>

            <?php    break;
            case 'swap' :   $attachment = get_post_thumbnail_id();

                            $attachment_ids   = $product->get_gallery_image_ids();
                            $attachment_ids[] = $attachment; // Add featured image to the array
                            $secondary_img_id = '';

                            if ( ! empty( $attachment_ids ) ) {
                                $attachment_ids = array_unique( $attachment_ids ); // remove duplicate images
                                if ( count( $attachment_ids ) > '1' ) {
                                    if ( $attachment_ids['0'] !== $attachment ) {
                                        $secondary_img_id = $attachment_ids['0'];
                                    } elseif ( $attachment_ids['1'] !== $attachment ) {
                                        $secondary_img_id = $attachment_ids['1'];
                                    }
                                }
                            }

                            // Image args
                            $first_img = array(
                                'class'         => 'ae-woo-image-main',
                                'alt'           => get_the_title(),
                                'itemprop'      => 'image'
                            );

                            $second_img = array(
                                'class'         => 'ae-woo-image-secondary',
                                'alt'           => get_the_title(),
                                'itemprop'      => 'image'
                            );


            // Return thumbnail
                            if ( $secondary_img_id ) : ?>

                                <div class="ae-woo-image-swap woo-entry-image clr">
                                    <a href="<?php the_permalink(); ?>" class="ae-woocommerce-LoopProduct-link">
                                        <?php
                                        // Main Image
                                        echo wp_get_attachment_image( $attachment, $image_size, '', $first_img ); ?>
                                        <?php
                                        // Secondary Image
                                        echo wp_get_attachment_image( $secondary_img_id, $image_size, '', $second_img ); ?>
                                    </a>
                                </div><!-- .woo-entry-image-swap -->

                            <?php else : ?>

                                <div class="ae-woo-image-swap woo-entry-image clr">
                                    <a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link">
                                        <?php
                                        // Single Image
                                        echo wp_get_attachment_image( $attachment, $image_size, '', $first_img ); ?>
                                    </a>
                                </div><!-- .woo-entry-image -->

                            <?php endif;
                break;
            default :  $columns = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
                        $post_thumbnail_id = get_post_thumbnail_id( $product->get_id() );
                        $full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
                        $image_title       = get_post_field( 'post_excerpt', $post_thumbnail_id );
                        $placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
                        $wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
                            'woocommerce-product-gallery',
                            'woocommerce-product-gallery--' . $placeholder,
                            'woocommerce-product-gallery--columns-' . absint( $columns ),
                            'images',
                        ) );

                        ?>
                        <div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
                            <figure class="woocommerce-product-gallery__wrapper">
                                <?php
                                $attributes = array(
                                    'title'                   => $image_title,
                                    'data-src'                => $full_size_image[0],
                                    'data-large_image'        => $full_size_image[0],
                                    'data-large_image_width'  => $full_size_image[1],
                                    'data-large_image_height' => $full_size_image[2],
                                );

                                if ( has_post_thumbnail() ) {
                                    $html  = '<div data-thumb="' . get_the_post_thumbnail_url( $product->get_id(), 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
                                    $html .= get_the_post_thumbnail( $product->get_id(), 'shop_single', $attributes );
                                    $html .= '</a></div>';
                                } else {
                                    $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
                                    $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
                                    $html .= '</div>';
                                }

                                echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $product->get_id() ) );

                                do_action( 'woocommerce_product_thumbnails' );
                                ?>
                            </figure>
                        </div>
     <?php   }
          ?>
    <?php
         $GLOBALS['post'] = $temp_post;
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Woo_Product_Image_Gallery() );
